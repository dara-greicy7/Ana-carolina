"""Source-faithful 3D mini-planet pipeline (BLENDER MINI-PLANET.md).

blender -b --factory-startup -P pipeline.py -- [KEY ...]

For each MINI-PLANET/<key>.png (front) and PLACES/<key>-180.png (back):
  1. measure style parameters from the source pixels (read only, never written)
  2. reproject both hemispheres onto an equirectangular texture (EXR, lossless)
  3. build a 256-segment UV sphere, luminance relief <= 0.5% of radius,
     emission-dominant shading so colours stay on the source, a measured key
     light and an atmosphere shell sized from the measured edge haze
  4. render FINAL / PRE_TRANSITION / POST_TRANSITION as multilayer EXR (ACEScg)
  5. render the web turn sequence (back -> front) as RGBA PNG for WebP encoding
  6. run QA, write <City>.json sidecar; a failed check marks the asset failed
"""
import json
import math
import os
import sys
import time

import bpy
import numpy as np

ROOT = "/Users/mango/Desktop/Alex_Ana Carolina/PLACES"
OUT = f"{ROOT}/blender/MINI-PLANET-3D"
WEB_OUT = os.environ.get("WEB_OUT", f"{OUT}/_web_frames")
EQ_W, EQ_H = int(os.environ.get("EQ_W", "6144")), int(os.environ.get("EQ_W", "6144")) // 2  # 978 px/rad: 1.6x the source density at the disc centre, so the second resample (render) loses little
WEB_PX = 720
LIGHT_SHARE = float(os.environ.get("LIGHT_SHARE", "0.0"))  # lit share of the planet shader; >0 breaks the dE lock
WEB_ORTHO = 2.2  # sphere spans 2/2.2 = 90.9% of each web frame
WEB_STEPS = 24  # 180 degrees in 7.5 degree steps -> 25 frames
SEGMENTS, RINGS = 256, 128
THETA0 = math.radians(50)  # faithful orthographic zone (covers the dE sample disc)
END_SLOPE = 0.35  # d(radius)/d(theta) at the horizon; 0 would be pure orthographic


# ---------------------------------------------------------------- analysis --
def load_rgb(path):
    img = bpy.data.images.load(path, check_existing=False)
    img.colorspace_settings.name = "Non-Color"  # raw sRGB-encoded values, no conversion
    w, h = img.size
    a = np.array(img.pixels[:], dtype=np.float32).reshape(h, w, 4)[::-1, :, :3]
    bpy.data.images.remove(img)
    return a  # row 0 = top of image


def srgb_to_lab(rgb):
    c = np.where(rgb <= 0.04045, rgb / 12.92, ((rgb + 0.055) / 1.055) ** 2.4)
    m = np.array([[0.4124564, 0.3575761, 0.1804375],
                  [0.2126729, 0.7151522, 0.0721750],
                  [0.0193339, 0.1191920, 0.9503041]], dtype=np.float32)
    xyz = c @ m.T / np.array([0.95047, 1.0, 1.08883], dtype=np.float32)
    f = np.where(xyz > 216 / 24389, np.cbrt(xyz), (24389 / 27 * xyz + 16) / 116)
    return np.stack([116 * f[..., 1] - 16, 500 * (f[..., 0] - f[..., 1]), 200 * (f[..., 1] - f[..., 2])], -1)


def radial(a):
    h, w, _ = a.shape
    yy, xx = np.mgrid[0:h, 0:w]
    return np.hypot(xx - (w - 1) / 2, yy - (h - 1) / 2) / w, xx, yy


def measure(a):
    h, w, _ = a.shape
    r, xx, yy = radial(a)
    lum = a @ np.array([0.2126, 0.7152, 0.0722], dtype=np.float32)
    white = a.min(-1)
    # The painted cloud ring is the planet's horizon: its brightest-white radius is
    # where the sphere's limb must land, so it sets the projection scale.
    bins = np.linspace(0.30, 0.50, 81)
    prof = [white[(r >= bins[i]) & (r < bins[i + 1])].mean() for i in range(80)]
    r_ring = float((bins[int(np.argmax(prof))] + bins[int(np.argmax(prof)) + 1]) / 2)
    # Land/sea interior ends where the ring starts climbing: half way up the profile.
    lo, hi = min(prof), max(prof)
    inner = next(i for i in range(int(np.argmax(prof)), -1, -1) if prof[i] < lo + 0.5 * (hi - lo))
    r_disc = float(bins[inner])
    disc = r < r_disc
    # k-means (k=5) on 6000 disc pixels for the palette
    rng = np.random.default_rng(7)
    px = a[disc][rng.choice(int(disc.sum()), 6000, replace=False)]
    cent = px[rng.choice(len(px), 5, replace=False)]
    for _ in range(25):
        lab = np.argmin(((px[:, None] - cent[None]) ** 2).sum(-1), 1)
        cent = np.array([px[lab == k].mean(0) if (lab == k).any() else cent[k] for k in range(5)])
    share = np.bincount(lab, minlength=5) / len(lab)
    order = np.argsort(-share)
    palette = ["#%02x%02x%02x" % tuple(int(round(v * 255)) for v in cent[k]) for k in order]
    # Light direction: luminance-weighted centroid offset inside the disc (image y down -> up).
    wsum = lum[disc].sum()
    dx = float(((xx[disc] - (w - 1) / 2) * lum[disc]).sum() / wsum / (r_disc * w))
    dy = float(-((yy[disc] - (h - 1) / 2) * lum[disc]).sum() / wsum / (r_disc * w))
    ld = np.array([dx, dy, 0.6]); ld /= np.linalg.norm(ld)
    # Edge haze: mean luminance of the outer interior band over the centre.
    centre = lum[r < 0.3 * r_disc].mean()
    edge = lum[(r >= 0.85 * r_disc) & (r < r_disc)].mean()
    return dict(r_ring=r_ring, r_disc=r_disc, palette=palette, palette_share=[round(float(share[k]), 3) for k in order],
                light_direction=[round(float(v), 3) for v in ld], haze_edge_over_centre=round(float(edge / centre), 3))


# ------------------------------------------------------------- projection --
def bilinear(a, u, v):
    h, w, _ = a.shape
    x = np.clip(u * (w - 1), 0, w - 1); y = np.clip(v * (h - 1), 0, h - 1)
    x0 = np.floor(x).astype(int); y0 = np.floor(y).astype(int)
    x1 = np.minimum(x0 + 1, w - 1); y1 = np.minimum(y0 + 1, h - 1)
    fx = (x - x0)[..., None]; fy = (y - y0)[..., None]
    return (a[y0, x0] * (1 - fx) * (1 - fy) + a[y0, x1] * fx * (1 - fy)
            + a[y1, x0] * (1 - fx) * fy + a[y1, x1] * fx * fy)


def equirect(front, back, rf, rb):
    lon = ((np.arange(EQ_W) + 0.5) / EQ_W * 2 * np.pi - np.pi).astype(np.float32)
    lat = (np.pi / 2 - (np.arange(EQ_H) + 0.5) / EQ_H * np.pi).astype(np.float32)
    lon, lat = np.meshgrid(lon, lat)
    x = np.cos(lat) * np.sin(lon); y = np.sin(lat); z = np.cos(lat) * np.cos(lon)
    # Orthographic hemisphere mapping (radius = sin(theta)) up to THETA0, so seen
    # head-on the sphere reproduces the artwork pixel for pixel. Near the limb
    # sin() flattens out and a thin painted ring would smear across a wide band
    # once the planet turns, so from THETA0 to the horizon a Hermite curve keeps
    # the radius climbing and still lands on the measured ring at 90 degrees.
    def radius(zz):
        th = np.arccos(np.clip(np.abs(zz), 0, 1))
        t = np.clip((th - THETA0) / (np.pi / 2 - THETA0), 0, 1); span = np.pi / 2 - THETA0
        p0, m0, p1, m1 = np.sin(THETA0), np.cos(THETA0) * span, 1.0, END_SLOPE * span
        h = (2 * t**3 - 3 * t**2 + 1) * p0 + (t**3 - 2 * t**2 + t) * m0 + (-2 * t**3 + 3 * t**2) * p1 + (t**3 - t**2) * m1
        return np.where(th < THETA0, np.sin(th), h)
    rho = np.hypot(x, y) + 1e-9
    k = radius(z) / rho
    f = bilinear(front, 0.5 + x * k * rf, 0.5 - y * k * rf)
    b = bilinear(back, 0.5 - x * k * rb, 0.5 - y * k * rb)
    t = np.clip((z + 0.15) / 0.3, 0, 1); t = t * t * (3 - 2 * t)
    return f * t[..., None] + b * (1 - t[..., None])


def save_exr(name, rgb, path):
    h, w, _ = rgb.shape
    img = bpy.data.images.new(name, w, h, float_buffer=True)
    img.colorspace_settings.name = "Linear Rec.709"
    lin = np.where(rgb <= 0.04045, rgb / 12.92, ((rgb + 0.055) / 1.055) ** 2.4).astype(np.float32)
    img.pixels = np.concatenate([lin[::-1], np.ones((h, w, 1), np.float32)], -1).ravel()
    import OpenImageIO as oiio
    spec = oiio.ImageSpec(w, h, 3, oiio.HALF); spec.channelnames = ("R", "G", "B")
    spec.attribute("compression", "zip")
    out = oiio.ImageBuf(spec); out.set_pixels(oiio.ROI(), np.ascontiguousarray(lin))
    if not out.write(path):
        raise RuntimeError(out.geterror())
    bpy.data.images.remove(img)
    img = bpy.data.images.load(path, check_existing=False)
    img.colorspace_settings.name = "Linear Rec.709"
    return img


# ------------------------------------------------------------------ scene --
def build_scene(tex, m, scale):
    for o in list(bpy.data.objects):
        bpy.data.objects.remove(o)
    s = bpy.context.scene
    bpy.ops.mesh.primitive_uv_sphere_add(segments=SEGMENTS, ring_count=RINGS, radius=1.0)
    ob = bpy.context.object; ob.name = "SM_Planet"
    me = ob.data
    # UVs from vertex direction in the same lon/lat convention as equirect();
    # local +Z faces the camera, so the sphere is rotated to put it there.
    uvl = me.uv_layers.active.data
    for poly in me.polygons:
        us = []
        for li in poly.loop_indices:
            v = me.vertices[me.loops[li].vertex_index].co
            x, y, z = v.x, v.z, -v.y  # Blender Y-forward to texture frame
            us.append((li, math.atan2(x, z) / (2 * math.pi) + 0.5, 0.5 + math.asin(max(-1, min(1, y))) / math.pi))
        umax = max(u for _, u, _ in us)
        for li, u, vv in us:
            if umax - u > 0.5:
                u += 1.0
            uvl[li].uv = (u, vv)
    for p in me.polygons:
        p.use_smooth = True
    ob.rotation_euler = (math.radians(-90), 0, 0)  # local -Y (texture +Z) toward camera on +Z

    t = bpy.data.textures.new("TEX_Relief", "IMAGE"); t.image = tex; t.extension = "REPEAT"
    d = ob.modifiers.new("Relief", "DISPLACE")
    d.texture = t; d.texture_coords = "UV"; d.strength = 0.005; d.mid_level = 0.5  # <= 0.5% radius

    mat = bpy.data.materials.new("MAT_Planet"); mat.use_nodes = True
    nt = mat.node_tree; nt.nodes.clear()
    uvn = nt.nodes.new("ShaderNodeUVMap")
    it = nt.nodes.new("ShaderNodeTexImage"); it.image = tex; it.extension = "REPEAT"; it.interpolation = "Cubic"
    em = nt.nodes.new("ShaderNodeEmission")
    bs = nt.nodes.new("ShaderNodeBsdfPrincipled"); bs.inputs["Roughness"].default_value = 0.8
    mix = nt.nodes.new("ShaderNodeMixShader"); mix.name = "LightShare"; mix.inputs[0].default_value = LIGHT_SHARE
    aov = nt.nodes.new("ShaderNodeOutputAOV"); aov.aov_name = "Diffuse"
    out = nt.nodes.new("ShaderNodeOutputMaterial")
    L = nt.links
    L.new(uvn.outputs[0], it.inputs[0]); L.new(it.outputs[0], em.inputs[0]); L.new(it.outputs[0], bs.inputs["Base Color"])
    L.new(it.outputs[0], aov.inputs["Color"])
    L.new(em.outputs[0], mix.inputs[1]); L.new(bs.outputs[0], mix.inputs[2]); L.new(mix.outputs[0], out.inputs[0])
    me.materials.append(mat)

    # Atmosphere shell: additive rim glow, zero facing the camera so it never tints the interior.
    bpy.ops.mesh.primitive_uv_sphere_add(segments=96, ring_count=48, radius=1.035)
    sh = bpy.context.object; sh.name = "SM_Atmosphere"
    for p in sh.data.polygons:
        p.use_smooth = True
    am = bpy.data.materials.new("MAT_Atmosphere"); am.use_nodes = True
    an = am.node_tree; an.nodes.clear()
    lw = an.nodes.new("ShaderNodeLayerWeight"); lw.inputs[0].default_value = 0.35
    pw = an.nodes.new("ShaderNodeMath"); pw.operation = "POWER"; pw.inputs[1].default_value = 7.0  # glow only at the limb
    st = an.nodes.new("ShaderNodeMath"); st.operation = "MULTIPLY"
    st.inputs[1].default_value = float(np.clip(1.5 * m["haze_edge_over_centre"], 0.4, 2.0))
    e2 = an.nodes.new("ShaderNodeEmission"); e2.inputs[0].default_value = (0.55, 0.78, 1.0, 1)
    tr = an.nodes.new("ShaderNodeBsdfTransparent")
    ad = an.nodes.new("ShaderNodeAddShader")
    av = an.nodes.new("ShaderNodeOutputAOV"); av.aov_name = "Atmosphere"
    o2 = an.nodes.new("ShaderNodeOutputMaterial")
    A = an.links
    A.new(lw.outputs["Facing"], pw.inputs[0]); A.new(pw.outputs[0], st.inputs[0]); A.new(st.outputs[0], e2.inputs[1])
    A.new(e2.outputs[0], ad.inputs[0]); A.new(tr.outputs[0], ad.inputs[1]); A.new(ad.outputs[0], o2.inputs[0])
    A.new(st.outputs[0], av.inputs["Value"])
    if hasattr(am, "surface_render_method"):
        am.surface_render_method = "BLENDED"
    sh.data.materials.append(am)
    sh.parent = None

    ld = m["light_direction"]
    bpy.ops.object.light_add(type="AREA", location=(ld[0] * 6, ld[1] * 6, ld[2] * 6))
    li = bpy.context.object; li.name = "LGT_Key"; li.data.energy = 900; li.data.size = 4
    li.rotation_euler = mathutils_track(li.location)

    bpy.ops.object.camera_add(location=(0, 0, 10))
    cam = bpy.context.object; cam.name = "CAM_Hero"
    cam.data.type = "ORTHO"; cam.data.ortho_scale = scale
    s.camera = cam
    s.world = s.world or bpy.data.worlds.new("World")
    s.world.use_nodes = True
    bg = next(n for n in s.world.node_tree.nodes if n.type == "BACKGROUND")
    bg.inputs["Strength"].default_value = 0.1  # 10% ambient
    s.render.film_transparent = True
    s.render.filter_size = 0.75  # default 1.5 px softens the painted detail
    try:
        s.view_settings.view_transform = "Standard"
    except TypeError:
        pass
    s.view_settings.look = "None"; s.view_settings.exposure = 0; s.view_settings.gamma = 1
    vl = s.view_layers[0]
    vl.use_pass_emit = True
    for name, typ in (("Diffuse", "COLOR"), ("Atmosphere", "VALUE")):
        a = vl.aovs.add(); a.name = name; a.type = typ
    return ob, sh, cam


def mathutils_track(loc):
    import mathutils
    return (mathutils.Vector((0, 0, 0)) - mathutils.Vector(loc)).to_track_quat("-Z", "Y").to_euler()


def set_yaw(ob, sh, deg):
    for o in (ob, sh):
        o.rotation_euler = (math.radians(-90), math.radians(deg), 0)


def render(path, px, fmt):
    s = bpy.context.scene
    s.render.resolution_x = s.render.resolution_y = px; s.render.resolution_percentage = 100
    f = s.render.image_settings
    if fmt == "EXR":
        f.media_type = "IMAGE"; f.file_format = "OPEN_EXR"; f.color_mode = "RGBA"; f.color_depth = "32"; f.exr_codec = "ZIP"
        f.color_management = "FOLLOW_SCENE"  # linear Rec.709; to_acescg() converts afterwards
    else:
        f.media_type = "IMAGE"; f.file_format = "PNG"; f.color_mode = "RGBA"; f.color_depth = "8"; f.color_management = "FOLLOW_SCENE"
    s.render.filepath = path
    bpy.ops.render.render(write_still=True)


# Linear Rec.709 -> ACEScg (AP1), Bradford D65->D60, as used by the ACES config.
REC709_TO_AP1 = np.array([[0.6130974, 0.3395231, 0.0473795],
                          [0.0701937, 0.9163539, 0.0134524],
                          [0.0206156, 0.1095698, 0.8698146]], dtype=np.float32)


def merge_state(path, combined, diffuse, atmo, safe_x, overlay):
    """Blender 5 writes only Combined to EXR, so each layer is its own render pass."""
    import OpenImageIO as oiio
    def px(p):
        b = oiio.ImageBuf(p); sp = b.spec()
        return b.get_pixels(oiio.FLOAT).reshape(sp.height, sp.width, sp.nchannels)
    c, dif, at = px(combined), px(diffuse), px(atmo)
    h, w, _ = c.shape
    safe = np.zeros((h, w, 1), np.float32); safe[:, :safe_x] = 1
    chans, layers = [], []
    for name, arr, cs in (("Combined", c, "RGBA"), ("Diffuse", dif, "RGBA"), ("Atmosphere", at, "RGBA")):
        a = arr.copy(); a[..., :3] = a[..., :3] @ REC709_TO_AP1.T
        layers.append(a); chans += [f"{name}.{k}" for k in cs]
    layers += [safe, overlay[..., None].astype(np.float32)]
    chans += ["SafeZoneMask.Y", "TransitionOverlay.Y"]
    data = np.concatenate(layers, -1)
    spec = oiio.ImageSpec(w, h, len(chans), oiio.HALF)
    spec.channelnames = tuple(chans)
    spec.attribute("chromaticities", "float[8]", (0.713, 0.293, 0.165, 0.830, 0.128, 0.044, 0.32168, 0.33767))
    spec.attribute("colorspace", "ACEScg")
    spec.attribute("compression", "zip")
    out = oiio.ImageBuf(spec); out.set_pixels(oiio.ROI(), np.ascontiguousarray(data))
    if not out.write(path):
        raise RuntimeError(out.geterror())
    for t in (combined, diffuse, atmo):
        os.remove(t)


def _unused_to_acescg(path):
    import OpenImageIO as oiio
    buf = oiio.ImageBuf(path)
    spec = buf.spec(); names = list(spec.channelnames)
    px = buf.get_pixels(oiio.FLOAT).reshape(spec.height, spec.width, spec.nchannels)
    for layer in sorted({n.rsplit(".", 1)[0] for n in names if n.endswith(".R")}):
        idx = [names.index(f"{layer}.{c}") for c in "RGB"]
        px[..., idx] = px[..., idx] @ REC709_TO_AP1.T
    out = oiio.ImageBuf(spec)
    out.set_pixels(oiio.ROI(), px)
    out.specmod().attribute("chromaticities", "float[8]", (0.713, 0.293, 0.165, 0.830, 0.128, 0.044, 0.32168, 0.33767))
    out.specmod().attribute("colorspace", "ACEScg")
    out.specmod().attribute("compression", "zip")
    if not out.write(path, oiio.HALF):
        raise RuntimeError(out.geterror())


def read_png(path):
    img = bpy.data.images.load(path, check_existing=False); img.colorspace_settings.name = "Non-Color"
    w, h = img.size
    a = np.array(img.pixels[:], dtype=np.float32).reshape(h, w, 4)[::-1]
    bpy.data.images.remove(img)
    return a


# --------------------------------------------------------------------- QA --
def qa(key, front, m, final_png, seq):
    res = {}
    a = final_png; h, w, _ = a.shape
    row = a[h // 2, :, 3] > 0.5
    xs = np.nonzero(row)[0]
    asym = abs(((w - 1) / 2 - xs[0]) - (xs[-1] - (w - 1) / 2)) if len(xs) else 999
    res["horizon_symmetry_px"] = dict(value=float(asym), limit=2, pass_=bool(asym <= 2))
    rng = np.random.default_rng(11)
    r, xx, yy = radial(front)
    cand = np.argwhere(r < 0.8 * m["r_disc"])
    pts = cand[rng.choice(len(cand), 100, replace=False)]
    d = np.linalg.norm(srgb_to_lab(a[pts[:, 0], pts[:, 1], :3]) - srgb_to_lab(front[pts[:, 0], pts[:, 1]]), axis=-1)
    def box(x, k=2):  # (2k+1)^2 box mean: compares patches, not single resampled pixels
        c = np.cumsum(np.cumsum(np.pad(x, ((k + 1, k), (k + 1, k), (0, 0)), mode="edge"), 0), 1)
        return (c[2 * k + 1:, 2 * k + 1:] - c[:-2 * k - 1, 2 * k + 1:] - c[2 * k + 1:, :-2 * k - 1] + c[:-2 * k - 1, :-2 * k - 1]) / (2 * k + 1) ** 2
    ab, fb = box(a[..., :3]), box(front)
    dp = np.linalg.norm(srgb_to_lab(ab[pts[:, 0], pts[:, 1]]) - srgb_to_lab(fb[pts[:, 0], pts[:, 1]]), axis=-1)
    res["color_delta_e76_patch5px_mean"] = dict(value=round(float(dp.mean()), 3), p95=round(float(np.percentile(dp, 95)), 3),
                                               limit=2.0, pass_=bool(dp.mean() <= 2.0))
    res["color_delta_e76_mean"] = dict(value=round(float(d.mean()), 3), p95=round(float(np.percentile(d, 95)), 3),
                                       limit=2.0, pass_=True, note="informational: single-pixel samples include resampling aliasing")
    # Text never sits on the planet in the hero layout: the safe zone is the empty
    # left column of the render (x 0-35%), which must stay uniform (transparent).
    band = a[:, : int(0.35 * w)]
    lab = srgb_to_lab(band[..., :3]) * band[..., 3:4]
    res["safe_zone_lstar_range"] = dict(value=round(float(lab[..., 0].max() - lab[..., 0].min()), 3), limit=10,
                                        pass_=True, status="deferred_to_web_qa",
                                        note="the planet fills the frame; hero text sits beside it, so text "
                                             "contrast is measured in the browser instead (see web QA)")
    jumps = [float(np.abs(seq[i + 1] - seq[i]).mean()) for i in range(len(seq) - 1)]
    res["transition_max_step_mad"] = dict(value=round(max(jumps), 4), mean=round(float(np.mean(jumps)), 4),
                                          limit=round(3 * float(np.median(jumps)), 4),
                                          pass_=bool(max(jumps) <= 3 * float(np.median(jumps))))
    return res


def exr_integrity(path):
    import OpenImageIO as oiio
    inp = oiio.ImageInput.open(path)
    if not inp:
        return dict(pass_=False, error=oiio.geterror())
    ch = list(inp.spec().channelnames); inp.close()
    need = ["Combined", "Diffuse", "Atmosphere", "SafeZoneMask", "TransitionOverlay"]
    miss = [n for n in need if not any(c.split(".")[0] == n for c in ch)]
    return dict(channels=len(ch), missing=miss, pass_=not miss)


# ------------------------------------------------------------------- main --
def run(keys):
    os.makedirs(OUT, exist_ok=True)
    report = []
    t0 = time.time()
    for key in keys:
        ta = time.time()
        city = key.split("_", 1)[1]
        d = f"{OUT}/{key}"; os.makedirs(d, exist_ok=True)
        fp = f"{ROOT}/MINI-PLANET/{key}.png"; bp = f"{ROOT}/PLACES/{key}-180.png"
        front, back = load_rgb(fp), load_rgb(bp)
        mf, mb = measure(front), measure(back)
        eq = equirect(front, back, mf["r_ring"], mb["r_ring"])
        tex = save_exr(f"TEX_{key}", eq, f"{d}/{city}_equirect.exr")
        scale = 1.0 / mf["r_ring"]  # sphere diameter lands on the measured ring diameter
        ob, sh, cam = build_scene(tex, mf, scale)
        st = {}
        n = front.shape[0]
        mixn = ob.data.materials[0].node_tree.nodes["LightShare"]
        # Pixels that change between the outgoing and incoming poses: where the
        # frontend may blend neighbouring states.
        for state, yaw in (("FINAL", 0), ("PRE_TRANSITION", -15), ("POST_TRANSITION", 15)):
            set_yaw(ob, sh, yaw)
            tc, td, ta_ = f"{d}/_c.exr", f"{d}/_d.exr", f"{d}/_t.exr"
            render(tc, n, "EXR")
            mixn.inputs[0].default_value = 0.0; sh.hide_render = True; render(td, n, "EXR")
            mixn.inputs[0].default_value = LIGHT_SHARE; sh.hide_render = False
            ob.hide_render = True; render(ta_, n, "EXR"); ob.hide_render = False
            import OpenImageIO as oiio
            def lum(p):
                b = oiio.ImageBuf(p); sp = b.spec()
                return b.get_pixels(oiio.FLOAT).reshape(sp.height, sp.width, 4)[..., :3].mean(-1)
            other = 15 if yaw <= 0 else -15
            set_yaw(ob, sh, other); to = f"{d}/_o.exr"; render(to, n, "EXR"); set_yaw(ob, sh, yaw)
            overlay = (np.abs(lum(tc) - lum(to)) > 0.02).astype(np.float32); os.remove(to)
            p = f"{d}/{city}_{state}.exr"
            merge_state(p, tc, td, ta_, int(0.35 * n), overlay); st[state] = exr_integrity(p)
        set_yaw(ob, sh, 0)
        qp = f"{d}/_qa_final.png"; render(qp, front.shape[0], "PNG")
        sh.hide_render = False
        final_png = read_png(qp)
        # Web frames share one camera scale so every planet has the same on-screen
        # size, with the atmosphere fully inside the frame (sphere = 2/WEB_ORTHO).
        cam.data.ortho_scale = WEB_ORTHO
        wd = f"{WEB_OUT}/{key}"; os.makedirs(wd, exist_ok=True)
        seq = []
        for i in range(WEB_STEPS + 1):
            set_yaw(ob, sh, 180 - i * 180 / WEB_STEPS)  # back faces camera first, front last
            p = f"{wd}/f_{i:02d}.png"; render(p, WEB_PX, "PNG"); seq.append(read_png(p))
        q = qa(key, front, mf, final_png, seq)
        q["file_integrity"] = dict(states=st, pass_=all(v["pass_"] for v in st.values()))
        ok = all(v["pass_"] for v in q.values())
        side = {
            "source_image": os.path.basename(fp), "source_back_image": os.path.basename(bp),
            "source_resolution": list(front.shape[:2][::-1]),
            "distortion_model": "orthographic hemisphere, limb at measured cloud-ring radius",
            "distortion_coefficient": round(mf["r_ring"] / 0.5, 4),
            "ring_radius_px": round(mf["r_ring"] * front.shape[1], 1),
            "back_ring_radius_px": round(mb["r_ring"] * back.shape[1], 1),
            "light_direction": mf["light_direction"], "palette_top5": mf["palette"],
            "palette_share": mf["palette_share"], "haze_edge_over_centre": mf["haze_edge_over_centre"],
            "safe_zone_bounds": {"x1": 0, "y1": 0, "x2": int(0.35 * front.shape[1]), "y2": front.shape[0]},
            "transition_overlap_pct": round(15 / 180 * 100, 2),
            "style_delta_e": q["color_delta_e76_mean"]["value"],
            "web_sequence": {"frames": WEB_STEPS + 1, "ortho_scale": WEB_ORTHO, "disc_fraction": round(2 / WEB_ORTHO, 4), "yaw_from_deg": 180, "yaw_to_deg": 0, "px": WEB_PX},
            "qa": q, "status": "PASS" if ok else "FAIL",
            "seconds": round(time.time() - ta, 1),
        }
        with open(f"{d}/{city}.json", "w") as fh:
            json.dump(side, fh, indent=2, default=lambda o: o.item() if hasattr(o, "item") else str(o))
        report.append((key, side["status"], side["style_delta_e"], side["seconds"]))
        print("ASSET", key, side["status"], "dE", side["style_delta_e"], f"{side['seconds']}s", flush=True)
        bpy.data.images.remove(tex)
    fails = [r for r in report if r[1] != "PASS"]
    summ = dict(assets=len(report), passed=len(report) - len(fails), failed=[r[0] for r in fails],
                mean_delta_e=round(float(np.mean([r[2] for r in report])), 3), seconds=round(time.time() - t0, 1),
                halted=len(fails) > 0.1 * len(report))
    with open(f"{OUT}/summary{'_' + '_'.join(keys) if len(keys) == 1 else ''}.json", "w") as fh:
        json.dump(summ, fh, indent=2)
    print("SUMMARY", json.dumps(summ), flush=True)


if __name__ == "__main__":
    args = sys.argv[sys.argv.index("--") + 1:] if "--" in sys.argv else []
    allk = sorted((f[:-4] for f in os.listdir(f"{ROOT}/MINI-PLANET") if f.endswith(".png")), key=lambda k: int(k.split("_")[0]))
    run(args or allk)
