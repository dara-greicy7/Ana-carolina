/**
 * Dependency-free fixed-window rate limiter.
 *
 * Limitations by design: state is per-process memory, so it resets on restart
 * and is not shared across multiple server instances. That is acceptable for
 * this single-instance site; behind a fleet, move the buckets to Redis or an
 * edge rate-limiting service.
 */

type Bucket = {
  count: number;
  resetAt: number;
};

type RateLimitResult = {
  allowed: boolean;
  remaining: number;
  retryAfterSeconds: number;
};

const MAX_BUCKETS = 10_000;

declare global {
  var __rateLimitBuckets: Map<string, Bucket> | undefined;
}

const buckets = (globalThis.__rateLimitBuckets ??= new Map<string, Bucket>());

function evictExpired(now: number) {
  const expiredKeys: string[] = [];
  buckets.forEach((bucket, key) => {
    if (bucket.resetAt <= now) {
      expiredKeys.push(key);
    }
  });
  for (const key of expiredKeys) {
    buckets.delete(key);
  }
}

export function rateLimit(
  key: string,
  limit: number,
  windowMs: number
): RateLimitResult {
  const now = Date.now();

  if (buckets.size >= MAX_BUCKETS) {
    evictExpired(now);
  }

  const bucket = buckets.get(key);

  if (!bucket || bucket.resetAt <= now) {
    buckets.set(key, { count: 1, resetAt: now + windowMs });
    return { allowed: true, remaining: limit - 1, retryAfterSeconds: 0 };
  }

  if (bucket.count >= limit) {
    return {
      allowed: false,
      remaining: 0,
      retryAfterSeconds: Math.max(1, Math.ceil((bucket.resetAt - now) / 1000)),
    };
  }

  bucket.count += 1;
  return { allowed: true, remaining: limit - bucket.count, retryAfterSeconds: 0 };
}

/** Best-effort client identifier for throttling. */
export function getClientKey(request: Request): string {
  const forwarded =
    request.headers.get("x-forwarded-for")?.split(",")[0]?.trim() ||
    request.headers.get("x-real-ip")?.trim();

  return forwarded && forwarded.length <= 64 ? forwarded : "unknown";
}
