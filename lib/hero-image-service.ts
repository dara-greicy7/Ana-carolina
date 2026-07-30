import { heroImageFiles } from "./hero-manifest";

// Rotation is derived from wall-clock time instead of persisted state so it
// works identically on Node and Cloudflare Workers (no filesystem access).
// The 21-second cadence matches the client rotation interval in
// components/ui/animated-hero-section-1.tsx.
export const HERO_ROTATION_INTERVAL_MS = 21 * 1000;

export function getHeroImageUrls(): string[] {
  return heroImageFiles.map((file) => `/hero-images/${encodeURIComponent(file)}`);
}

export function getRandomHeroImageUrl(): string | null {
  if (heroImageFiles.length === 0) {
    return null;
  }

  const index = Math.floor(Math.random() * heroImageFiles.length);
  const file = heroImageFiles[index];
  return file ? `/hero-images/${encodeURIComponent(file)}` : null;
}

export function getHeroImageIndex(now: number = Date.now()): number {
  if (heroImageFiles.length === 0) {
    return -1;
  }

  return Math.floor(now / HERO_ROTATION_INTERVAL_MS) % heroImageFiles.length;
}

export function getCurrentHeroFile(now: number = Date.now()): string | null {
  const index = getHeroImageIndex(now);
  return index < 0 ? null : heroImageFiles[index] ?? null;
}

export function getCurrentHeroImageUrl(now: number = Date.now()): string | null {
  const file = getCurrentHeroFile(now);
  return file ? `/hero-images/${encodeURIComponent(file)}` : null;
}

export function isHeroImageFile(name: string): boolean {
  return heroImageFiles.includes(name);
}

export function getRotationTimestamps(now: number = Date.now()) {
  const start = Math.floor(now / HERO_ROTATION_INTERVAL_MS) * HERO_ROTATION_INTERVAL_MS;
  return {
    rotatedAt: new Date(start).toISOString(),
    nextRotationAt: new Date(start + HERO_ROTATION_INTERVAL_MS).toISOString(),
  };
}
