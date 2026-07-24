import fs from "node:fs/promises";
import path from "node:path";
import { NextRequest, NextResponse } from "next/server";
import { heroImagesDirectory } from "@/lib/hero-image-service";

export const runtime = "nodejs";

function getContentType(fileName: string) {
  const ext = path.extname(fileName).toLowerCase();

  switch (ext) {
    case ".jpg":
    case ".jpeg":
      return "image/jpeg";
    case ".png":
      return "image/png";
    case ".webp":
      return "image/webp";
    case ".gif":
      return "image/gif";
    case ".avif":
      return "image/avif";
    default:
      return "application/octet-stream";
  }
}

export async function GET(request: NextRequest) {
  const fileName = request.nextUrl.searchParams.get("name");

  if (!fileName) {
    return NextResponse.json({ error: "Hero image not found" }, { status: 404 });
  }

  const safeFileName = path.basename(fileName);
  const filePath = path.join(heroImagesDirectory, safeFileName);

  try {
    await fs.access(filePath);
    const buffer = await fs.readFile(filePath);
    return new NextResponse(buffer, {
      headers: {
        "Content-Type": getContentType(safeFileName),
        "Cache-Control": "no-store, must-revalidate",
      },
    });
  } catch {
    return NextResponse.json({ error: "Hero image not found" }, { status: 404 });
  }
}
