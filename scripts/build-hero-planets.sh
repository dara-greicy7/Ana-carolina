#!/usr/bin/env bash
# Encode the Blender turn frames (RGBA PNG) into the WebP sequences the hero reads.
# Usage: scripts/build-hero-planets.sh [frames_dir]
set -euo pipefail
SRC="${1:-/Users/mango/Desktop/Alex_Ana Carolina/PLACES/blender/MINI-PLANET-3D/_web_frames}"
OUT="$(cd "$(dirname "$0")/.." && pwd)/public/hero-planets"
command -v cwebp >/dev/null || { echo "cwebp not found (brew install webp)"; exit 1; }
for dir in "$SRC"/*/; do
  key="$(basename "$dir")"
  slug="$(echo "${key#*_}" | tr '[:upper:]' '[:lower:]')"
  mkdir -p "$OUT/$slug"
  for png in "$dir"f_*.png; do
    n="$(basename "$png" .png)"; n="${n#f_}"
    cwebp -quiet -q 78 -alpha_q 90 -m 6 "$png" -o "$OUT/$slug/$n.webp"
  done
  echo "$slug: $(ls "$OUT/$slug" | wc -l | tr -d ' ') frames"
done
