import fs from "node:fs/promises";
import path from "node:path";
import { NextRequest, NextResponse } from "next/server";
import {
  heroImagesDirectory,
  isSupportedImageFile,
} from "@/lib/hero-image-service";
import { getClientKey, rateLimit } from "@/lib/rate-limit";

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
  const limit = rateLimit(`hero-file:${getClientKey(request)}`, 120, 60_000);
  if (!limit.allowed) {
    return NextResponse.json(
      { error: "Too many requests" },
      {
        status: 429,
        headers: { "Retry-After": String(limit.retryAfterSeconds) },
      }
    );
  }

  const fileName = request.nextUrl.searchParams.get("name");

  // path.basename strips any directory components (path-traversal guard) and
  // the extension allowlist restricts serving to image files only.
  const safeFileName = fileName ? path.basename(fileName) : null;

  if (!safeFileName || !isSupportedImageFile(safeFileName)) {
    return NextResponse.json({ error: "Hero image not found" }, { status: 404 });
  }

  const filePath = path.join(heroImagesDirectory, safeFileName);

  try {
    const buffer = await fs.readFile(filePath);
    return new NextResponse(new Uint8Array(buffer), {
      headers: {
        "Content-Type": getContentType(safeFileName),
        "Content-Length": String(buffer.byteLength),
        // URLs are keyed by immutable file names, so day-level caching is safe.
        "Cache-Control": "public, max-age=86400, stale-while-revalidate=3600",
        "X-Content-Type-Options": "nosniff",
      },
    });
  } catch {
    return NextResponse.json({ error: "Hero image not found" }, { status: 404 });
  }
}
