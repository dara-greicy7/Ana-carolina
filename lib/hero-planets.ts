// The 14 original mini-planet artworks (PLACES/MINI-PLANET), encoded whole by
// scripts/build-mini-planets.py: only the corners outside the inscribed circle
// are faded, so every painted landmark stays intact. The hero spins each one
// about its own axis as the visitor scrolls.
export const miniPlanet = (slug: string) => `/mini-planets/${slug}.webp`;

// Degrees a planet turns while it settles upright; it keeps turning the same
// way as it hands over to the next city.
export const PLANET_SPIN_DEG = 120;
