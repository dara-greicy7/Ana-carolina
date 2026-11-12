// Frames come from PLACES/blender/MINI-PLANET-3D/pipeline.py: each planet turns
// 180 degrees sideways. Its far side carries the PREVIOUS city's artwork, so
// frame 0 is where the last planet stopped and the turn goes from one real city
// to the next; no city is mirrored onto its own far side, and the "-180"
// antipode images are not used.
// scripts/build-hero-planets.sh encodes them into public/hero-planets/<slug>/.
export const heroPlanetFrames = 25;

// Every frame is rendered with the same orthographic scale (WEB_ORTHO = 2.2 in
// the pipeline), so the planet's diameter is the same fraction of every frame.
export const heroPlanetDisc = 2 / 2.2;

export const heroPlanetFrame = (slug: string, i: number) =>
  `/hero-planets/${slug}/${String(i).padStart(2, "0")}.webp`;
