import fs from "node:fs/promises";
import os from "node:os";
import path from "node:path";

export const heroImagesDirectory = path.join(process.cwd(), "images");

const heroImagesStateFile = path.join(
  os.tmpdir(),
  "conative-time-hero-image-state.json"
);

/** Hero background rotates on a fixed schedule, not per request. */
export const HERO_ROTATE_INTERVAL_MS = 30 * 60 * 1000;

const IMAGE_EXTENSIONS = new Set([
  ".jpg",
  ".jpeg",
  ".png",
  ".webp",
  ".gif",
  ".avif",
]);

const IMAGE_ORDER = new Intl.Collator(undefined, {
  numeric: true,
  sensitivity: "base",
});

export type HeroImage = {
  fileName: string;
  url: string;
  rotatedAt: number;
  nextRotationAt: number;
};

type PersistedState = {
  index: number;
  lastAdvancedAt: number;
};

export function isSupportedImageFile(fileName: string): boolean {
  return IMAGE_EXTENSIONS.has(path.extname(fileName).toLowerCase());
}

/**
 * Serializes state mutations through a promise chain. Node runs this app as a
 * single process, so an in-process queue replaces the old file-lock + busy-wait
 * (which blocked the event loop for every concurrent request). If the app is
 * ever scaled to multiple processes, the worst case is an occasional skipped
 * rotation — harmless — but ideally the state moves to a shared store.
 */
let queue: Promise<void> = Promise.resolve();

function serialized<T>(task: () => Promise<T>): Promise<T> {
  const run = queue.then(task);
  queue = run.then(
    () => undefined,
    () => undefined
  );
  return run;
}

async function scanHeroImages(): Promise<string[]> {
  try {
    const entries = await fs.readdir(heroImagesDirectory, {
      withFileTypes: true,
    });
    return entries
      .filter((entry) => entry.isFile() && isSupportedImageFile(entry.name))
      .map((entry) => entry.name)
      .sort((left, right) => IMAGE_ORDER.compare(left, right));
  } catch {
    return [];
  }
}

async function readState(): Promise<PersistedState | null> {
  try {
    const raw = await fs.readFile(heroImagesStateFile, "utf8");
    const parsed = JSON.parse(raw) as Partial<PersistedState>;
    if (
      typeof parsed.index === "number" &&
      typeof parsed.lastAdvancedAt === "number"
    ) {
      return { index: parsed.index, lastAdvancedAt: parsed.lastAdvancedAt };
    }
    return null;
  } catch {
    return null;
  }
}

async function writeState(state: PersistedState): Promise<void> {
  const temporaryPath = `${heroImagesStateFile}.${process.pid}.tmp`;
  try {
    await fs.writeFile(temporaryPath, JSON.stringify(state), "utf8");
    await fs.rename(temporaryPath, heroImagesStateFile);
  } catch (error) {
    // A failed persist must never take the page down; rotation just restarts.
    console.warn("[hero-image] could not persist rotation state:", error);
  }
}

function toHeroImage(fileName: string, rotatedAt: number): HeroImage {
  return {
    fileName,
    url: `/api/hero-image/file?name=${encodeURIComponent(fileName)}`,
    rotatedAt,
    nextRotationAt: rotatedAt + HERO_ROTATE_INTERVAL_MS,
  };
}

/**
 * Returns the current hero image, advancing the rotation when the schedule has
 * elapsed. If the server was down for several intervals, the index catches up
 * by the number of missed steps so the schedule stays aligned.
 */
export async function getCurrentHeroImage(): Promise<HeroImage | null> {
  return serialized(async () => {
    const files = await scanHeroImages();
    if (files.length === 0) {
      return null;
    }

    const now = Date.now();
    const state = await readState();

    if (!state) {
      const next: PersistedState = { index: 0, lastAdvancedAt: now };
      await writeState(next);
      return toHeroImage(files[0], now);
    }

    const elapsed = now - state.lastAdvancedAt;
    if (elapsed >= HERO_ROTATE_INTERVAL_MS) {
      const missedSteps = Math.floor(elapsed / HERO_ROTATE_INTERVAL_MS);
      const next: PersistedState = {
        index: (state.index + missedSteps) % files.length,
        lastAdvancedAt: now,
      };
      await writeState(next);
      return toHeroImage(files[next.index], now);
    }

    const safeIndex =
      ((state.index % files.length) + files.length) % files.length;
    return toHeroImage(files[safeIndex], state.lastAdvancedAt);
  });
}
