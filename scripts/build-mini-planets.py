"""Encode the 14 original mini-planet artworks for the hero.

python3 scripts/build-mini-planets.py

Each PLACES/MINI-PLANET/<n>_<KEY>.png is kept whole: only the square's corners
outside the inscribed circle are faded out, so no landmark painted near the rim
is cut. The hero draws that inscribed circle at the planet's on-screen diameter
and spins it as the visitor scrolls.
"""
import numpy as np
from PIL import Image

SRC = "/Users/mango/Desktop/Alex_Ana Carolina/PLACES/MINI-PLANET"
SLUGS = {1: "paris", 2: "rome", 3: "spain", 4: "grece", 5: "tokyo", 6: "jungle", 7: "dubai",
         8: "istanbul", 9: "mountain-peak", 10: "punta-cana", 11: "thailand", 12: "israel",
         13: "golden-circle", 14: "machu-picchu"}
KEYS = {1: "PARIS", 2: "ROME", 3: "SPAIN", 4: "GRECE", 5: "TOKYO", 6: "JUNGLE", 7: "DUBAI",
        8: "ISTANBUL", 9: "MOUNTAIN-PEAK", 10: "PUNTA-CANA", 11: "THAILAND", 12: "ISRAEL",
        13: "GOLDEN-CIRCLE", 14: "MACHU-PICCHU"}
FEATHER = 0.03  # of the width: soft fade just inside the inscribed circle

def radius_map(w, h):
    yy, xx = np.mgrid[0:h, 0:w]
    return np.hypot(xx - (w - 1) / 2, yy - (h - 1) / 2) / w

for n, slug in SLUGS.items():
    im = Image.open(f"{SRC}/{n}_{KEYS[n]}.png").convert("RGB")
    r = radius_map(*im.size)
    alpha = np.clip((0.5 - r) / FEATHER, 0, 1)
    rgba = np.dstack([np.asarray(im), (alpha * 255).astype(np.uint8)])
    Image.fromarray(rgba, "RGBA").save(f"public/mini-planets/{slug}.webp", "WEBP", quality=90, method=6)
    print(slug, im.size)
