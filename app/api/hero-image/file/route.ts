import { NextRequest, NextResponse } from "next/server";
import { isHeroImageFile } from "@/lib/hero-image-service";

export const dynamic = "force-dynamic";

export async function GET(request: NextRequest) {
  const fileName = request.nextUrl.searchParams.get("name");

  if (!fileName || !isHeroImageFile(fileName)) {
    return NextResponse.json({ error: "Hero image not found" }, { status: 404 });
  }

  return NextResponse.redirect(
    new URL(`/hero-images/${encodeURIComponent(fileName)}`, request.url),
    302
  );
}
