import { NextResponse } from "next/server";
import {
  getCurrentHeroFile,
  getCurrentHeroImageUrl,
  getRotationTimestamps,
} from "@/lib/hero-image-service";

export const dynamic = "force-dynamic";

export async function GET() {
  const now = Date.now();
  const { rotatedAt, nextRotationAt } = getRotationTimestamps(now);

  return NextResponse.json(
    {
      imageUrl: getCurrentHeroImageUrl(now),
      fileName: getCurrentHeroFile(now),
      rotatedAt,
      nextRotationAt,
    },
    { headers: { "Cache-Control": "no-store" } }
  );
}
