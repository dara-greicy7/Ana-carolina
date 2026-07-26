import { NextRequest, NextResponse } from "next/server";
import { getCurrentHeroImage } from "@/lib/hero-image-service";
import { getClientKey, rateLimit } from "@/lib/rate-limit";

export const runtime = "nodejs";

export async function GET(request: NextRequest) {
  const limit = rateLimit(`hero-image:${getClientKey(request)}`, 60, 60_000);
  if (!limit.allowed) {
    return NextResponse.json(
      { error: "Too many requests" },
      {
        status: 429,
        headers: { "Retry-After": String(limit.retryAfterSeconds) },
      }
    );
  }

  const hero = await getCurrentHeroImage();

  return NextResponse.json(
    {
      imageUrl: hero?.url ?? null,
      fileName: hero?.fileName ?? null,
      rotatedAt: hero?.rotatedAt ?? null,
      nextRotationAt: hero?.nextRotationAt ?? null,
    },
    { headers: { "Cache-Control": "no-store" } }
  );
}
