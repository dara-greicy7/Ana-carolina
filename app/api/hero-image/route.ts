import { NextRequest, NextResponse } from "next/server";
import { getHeroImageService } from "@/lib/hero-image-service";

export const runtime = "nodejs";

export async function GET(request: NextRequest) {
  const service = getHeroImageService();
  const shouldAdvance =
    request.nextUrl.searchParams.get("advance") === "1" ||
    request.nextUrl.searchParams.get("refresh") === "1";

  const imageUrl = shouldAdvance
    ? service.advance() ?? service.getCurrentImageUrl()
    : service.getCurrentImageUrl();

  return NextResponse.json({
    imageUrl,
    fileName: service.getCurrentFile(),
    advanced: shouldAdvance,
    snapshot: service.snapshot(),
  });
}
