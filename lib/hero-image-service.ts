import fs from "node:fs";
import os from "node:os";
import path from "node:path";

export const heroImagesDirectory = path.join(process.cwd(), "images");
const heroImagesStateFile = path.join(os.tmpdir(), "conative-time-hero-image-state.json");
const heroImagesLockFile = `${heroImagesStateFile}.lock`;

type HeroImageSnapshot = {
  files: string[];
  index: number;
  lastAdvancedAt: number | null;
};

type HeroImageService = {
  getCurrentFile: () => string | null;
  getCurrentImageUrl: () => string | null;
  advance: () => string | null;
  refreshFiles: () => void;
  snapshot: () => HeroImageSnapshot;
};

declare global {
  // eslint-disable-next-line no-var
  var __heroImageService: HeroImageService | undefined;
}

const IMAGE_EXTENSIONS = new Set([".jpg", ".jpeg", ".png", ".webp", ".gif", ".avif"]);
const IMAGE_ORDER = new Intl.Collator(undefined, {
  numeric: true,
  sensitivity: "base",
});

function isSupportedImageFile(fileName: string) {
  return IMAGE_EXTENSIONS.has(path.extname(fileName).toLowerCase());
}

function scanHeroImagesDirectory() {
  if (!fs.existsSync(heroImagesDirectory)) {
    return [];
  }

  return fs
    .readdirSync(heroImagesDirectory, { withFileTypes: true })
    .filter((entry) => entry.isFile() && isSupportedImageFile(entry.name))
    .map((entry) => entry.name)
    .sort((left, right) => IMAGE_ORDER.compare(left, right));
}

function readPersistedIndex() {
  if (!fs.existsSync(heroImagesStateFile)) {
    return -1;
  }

  try {
    const raw = fs.readFileSync(heroImagesStateFile, "utf8");
    const parsed = JSON.parse(raw) as { index?: unknown };
    return typeof parsed.index === "number" ? parsed.index : -1;
  } catch {
    return -1;
  }
}

function writePersistedIndex(index: number) {
  const payload = JSON.stringify({ index, updatedAt: new Date().toISOString() });
  const temporaryPath = `${heroImagesStateFile}.${process.pid}.tmp`;
  fs.writeFileSync(temporaryPath, payload, "utf8");
  fs.renameSync(temporaryPath, heroImagesStateFile);
}

function readCurrentStateIndex() {
  return readPersistedIndex();
}

function withLock<T>(run: () => T): T {
  while (true) {
    try {
      const lockHandle = fs.openSync(heroImagesLockFile, "wx");

      try {
        return run();
      } finally {
        fs.closeSync(lockHandle);
        fs.unlinkSync(heroImagesLockFile);
      }
    } catch (error) {
      if (
        typeof error === "object" &&
        error !== null &&
        "code" in error &&
        (error as { code?: unknown }).code === "EEXIST"
      ) {
        try {
          const lockStats = fs.statSync(heroImagesLockFile);
          if (Date.now() - lockStats.mtimeMs > 15000) {
            fs.unlinkSync(heroImagesLockFile);
          }
        } catch {
          // If the lock disappeared between the failed open and the stat, retry immediately.
        }

        const waitUntil = Date.now() + 15;
        while (Date.now() < waitUntil) {
          // Busy-wait briefly to keep the implementation dependency-free in this local dev app.
        }
        continue;
      }

      throw error;
    }
  }
}

function createHeroImageService(): HeroImageService {
  let files = scanHeroImagesDirectory();
  let index = readPersistedIndex();
  let lastAdvancedAt: number | null = null;

  const refreshFiles = () => {
    const nextFiles = scanHeroImagesDirectory();
    const changed =
      nextFiles.length !== files.length ||
      nextFiles.some((file, fileIndex) => file !== files[fileIndex]);

    if (changed) {
      files = nextFiles;
      index = files.length === 0 ? -1 : ((index % files.length) + files.length) % files.length;
    }
  };

  const getCurrentFile = () => {
    refreshFiles();
    if (files.length === 0) {
      return null;
    }

    const safeIndex = index < 0 ? 0 : index % files.length;
    return files[safeIndex] ?? null;
  };

  const getCurrentImageUrl = () => {
    const currentFile = getCurrentFile();
    if (!currentFile) {
      return null;
    }

    return `/api/hero-image/file?name=${encodeURIComponent(currentFile)}`;
  };

  const advance = () => {
    return withLock(() => {
      refreshFiles();
      if (files.length === 0) {
        return null;
      }

      const currentPersistedIndex = readCurrentStateIndex();
      index = currentPersistedIndex < 0 ? 0 : (currentPersistedIndex + 1) % files.length;
      lastAdvancedAt = Date.now();
      writePersistedIndex(index);
      return getCurrentImageUrl();
    });
  };

  return {
    getCurrentFile,
    getCurrentImageUrl,
    advance,
    refreshFiles,
    snapshot: () => ({
      files: [...files],
      index,
      lastAdvancedAt,
    }),
  };
}

export function getHeroImageService() {
  globalThis.__heroImageService ??= createHeroImageService();
  return globalThis.__heroImageService;
}
