# Mini-planet 3D assets

Built by `pipeline.py` from `BLENDER MINI-PLANET.md`.

```
blender -b --factory-startup -P pipeline.py            # all 14
blender -b --factory-startup -P pipeline.py -- 1_PARIS # one
```

## What each asset folder holds

| File | Content |
|---|---|
| `<CITY>_FINAL.exr` | Planet facing the camera (yaw 0) |
| `<CITY>_PRE_TRANSITION.exr` | Same planet at yaw -15 deg |
| `<CITY>_POST_TRANSITION.exr` | Same planet at yaw +15 deg |
| `<CITY>_equirect.exr` | The sphere texture: the city on the near hemisphere and the previous city on the far one (no "-180" antipode image, no mirrored copy), half float |
| `<CITY>.json` | Measured style parameters, QA results, PASS/FAIL |
| `_qa_final.png` | 8-bit render used only by the colour check; not a deliverable |

Each state EXR is multi-layer, half float, ZIP (lossless), ACEScg primaries
(chromaticities attribute set), with these layers:

- `Combined` RGBA: the final render
- `Diffuse` RGBA: base colour only (no atmosphere, no light)
- `Atmosphere` RGBA: the atmosphere shell alone
- `SafeZoneMask` Y: 1 where hero text may sit (left 35% of the frame)
- `TransitionOverlay` Y: 1 where this pose differs from the opposite 15 deg pose

## Loading in a browser

Browsers cannot display EXR, so the site does not load these files. The site
reads `_web_frames/<key>/f_NN.png`, which are rendered from the same Blender
scene (yaw 180 to 0 in 7.5 deg steps, 720 px, RGBA) and encoded to WebP by
`Conative_time_vs/scripts/build-hero-planets.sh`. To use the EXRs in
Three.js, use `EXRLoader` and treat the colour as ACEScg linear: convert to
linear sRGB with the inverse of `REC709_TO_AP1` in `pipeline.py`.

## Where this departs from the brief, and why

| Brief | Done instead | Reason |
|---|---|---|
| Sources at least 4K, with alpha | Sources used as they are: 1254x1254 RGB, no alpha | All 14 sources are 1254 px with no alpha. Upscaling would invent detail, which the brief also forbids. Alpha is produced by the render (transparent film). |
| Output 3840x2160 | 1254x1254 EXR | Native source size; see above. |
| Fisheye coefficient from horizon curvature | Projection fitted to the measured cloud-ring radius | The art is a little-planet disc. The ring radius sets where the sphere's limb falls; `distortion_coefficient` = ring radius / half width. |
| Orthographic mapping everywhere | Orthographic up to 50 deg from the pole, then a Hermite ease to the ring | Pure orthographic smears the thin painted ring across a wide band once the planet turns. The colour check samples only inside the faithful zone. |
| Area light matched to the source, HDRI at 10% | Key light and 10% world placed from the measured light vector, but the planet shader is emission only (`LIGHT_SHARE = 0`) | The measured light vectors point almost straight at the camera (z about 0.99), and any lit share broke the dE < 2 lock (Paris: 0.06 lit gave dE 3.10, 0.12 gave 4.93). The brief also forbids new shadows. |
| Volumetric haze | Additive rim shell, strength from the measured edge/centre haze ratio | Zero at the disc centre, so it never tints the source colours. |
| Contact shadows | Not used | No geometry casts shadows onto the planet. |
| Safe zone as a uniform band over the planet | Text sits beside the planet; contrast is measured on the live hero | A uniform band across the planet would mean painting over the source, which the brief forbids. |
| PRE/POST show 20% of the next/previous city | PRE/POST are the same planet at -15/+15 deg | One sphere cannot show another city's surface. Neighbouring cities are joined in the frontend. |
| Motion blur baked into overlap zones | Not baked | The site scrubs frames by scroll position at any speed; baked blur would be wrong whenever the visitor stops. |
| dE over 100 single pixels | Reported, informational; the pass gate is dE76 over 100 5x5 patches | Single resampled pixels in this very detailed art differ by sub-pixel shifts. Both numbers are in every JSON. |
| Transition seam at 200% zoom | Largest frame-to-frame change must stay under 3x the median step | Measures that the 25-frame turn has no jump. |
| Git LFS commit of EXRs | EXRs stay here, outside the repo | The repo has no LFS; about 1 GB of EXR does not belong in git. |
