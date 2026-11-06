"use client";

import { useEffect, useRef } from "react";
import type { Destination } from "@/lib/destinations";

// The little-planet artwork is a globe seen head-on (front) and from behind (the
// "-180" file). Both are close to orthographic projections of a sphere, so a
// fragment shader can wrap them back onto a real sphere: the silhouette stays a
// circle at every rotation, which a CSS card flip cannot do.
const VERT = `
attribute vec2 aPos;
uniform vec2 uCenter;   // clip space
uniform vec2 uHalf;     // clip-space half size of the quad
varying vec2 vUv;
void main() {
  vUv = aPos * 1.14;    // extra margin around the disc for the atmosphere halo
  gl_Position = vec4(uCenter + aPos * uHalf * 1.14, 0.0, 1.0);
}`;

const FRAG = `
precision mediump float;
varying vec2 vUv;
uniform sampler2D uFront;
uniform sampler2D uBack;
uniform mat3 uRot;      // view-space normal -> globe-space normal
uniform float uR;       // disc radius inside the source image, in UV units
uniform float uAlpha;
uniform float uStereo;  // 0 = orthographic (artwork as drawn), 1 = stereographic
// Radial image distance for a point theta radians from the hemisphere's pole.
// Facing the camera the artwork is shown unchanged (orthographic); while turning,
// the mapping eases toward stereographic, which is how little-planet art is made
// and which keeps real detail at the limb instead of stretching the edge pixels.
vec2 project(vec2 xy, float z) {
  float l = length(xy);
  if (l < 1e-5) return vec2(0.0);
  float theta = atan(l, z);
  float r = mix(sin(min(theta, 1.5707963)), tan(min(theta, 1.9) * 0.5), uStereo);
  return xy / l * r;
}
void main() {
  float d = length(vUv);
  if (d > 1.0) {
    float halo = 1.0 - smoothstep(1.0, 1.14, d);
    float a = halo * halo * 0.32 * uAlpha;
    gl_FragColor = vec4(vec3(0.45, 0.72, 1.0) * a, a);
    return;
  }
  vec3 n = vec3(vUv, sqrt(max(0.0, 1.0 - d * d)));
  vec3 g = uRot * n;
  vec2 pf = project(g.xy, g.z);
  vec2 pb = project(vec2(-g.x, g.y), -g.z);
  vec4 front = texture2D(uFront, vec2(0.5 + pf.x * uR, 0.5 - pf.y * uR));
  vec4 back = texture2D(uBack, vec2(0.5 + pb.x * uR, 0.5 - pb.y * uR));
  vec3 base = mix(back.rgb, front.rgb, smoothstep(-0.15, 0.15, g.z));
  vec3 L = normalize(vec3(-0.45, 0.55, 0.75));
  float diffuse = 0.7 + 0.4 * max(dot(n, L), 0.0);
  float rim = pow(1.0 - n.z, 3.0);
  vec3 color = base * diffuse * (1.0 - 0.35 * rim) + vec3(0.45, 0.72, 1.0) * rim * 0.55;
  float edge = 1.0 - smoothstep(0.985, 1.0, d);
  float a = edge * uAlpha;
  gl_FragColor = vec4(color * a, a);
}`;

const DISC_RADIUS_UV = 0.44;

type GlobePose = { yaw: number; pitch: number; x: number; y: number; scale: number; alpha: number };

type GlobeCanvasProps = {
  destinations: Destination[];
  positionRef: React.MutableRefObject<number>;
  pose: (local: number, widthOverRadius: number) => GlobePose | null;
  frontSrc: (d: Destination) => string;
  backSrc: (d: Destination) => string;
  lowPower: boolean;
  onUnsupported: () => void;
};

function compile(gl: WebGLRenderingContext, type: number, src: string) {
  const s = gl.createShader(type);
  if (!s) throw new Error("createShader failed");
  gl.shaderSource(s, src);
  gl.compileShader(s);
  if (!gl.getShaderParameter(s, gl.COMPILE_STATUS)) throw new Error(gl.getShaderInfoLog(s) ?? "shader error");
  return s;
}

// Inverse (transpose) of Ry(yaw) · Rx(pitch), column-major for uniformMatrix3fv.
function inverseRotation(yaw: number, pitch: number) {
  const cy = Math.cos(yaw), sy = Math.sin(yaw), cp = Math.cos(pitch), sp = Math.sin(pitch);
  const m = [
    [cy, sy * sp, sy * cp],
    [0, cp, -sp],
    [-sy, cy * sp, cy * cp],
  ];
  // transpose(m) in column-major order is m's rows laid out one after another
  return new Float32Array([m[0][0], m[0][1], m[0][2], m[1][0], m[1][1], m[1][2], m[2][0], m[2][1], m[2][2]]);
}

export function GlobeCanvas({ destinations, positionRef, pose, frontSrc, backSrc, lowPower, onUnsupported }: GlobeCanvasProps) {
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const poseRef = useRef(pose);
  poseRef.current = pose;

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const gl = canvas.getContext("webgl", { premultipliedAlpha: true, antialias: true, alpha: true });
    if (!gl) {
      onUnsupported();
      return;
    }

    let program: WebGLProgram;
    try {
      program = gl.createProgram() as WebGLProgram;
      gl.attachShader(program, compile(gl, gl.VERTEX_SHADER, VERT));
      gl.attachShader(program, compile(gl, gl.FRAGMENT_SHADER, FRAG));
      gl.linkProgram(program);
      if (!gl.getProgramParameter(program, gl.LINK_STATUS)) throw new Error("link failed");
    } catch {
      onUnsupported();
      return;
    }
    gl.useProgram(program);

    const buf = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buf);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
    const aPos = gl.getAttribLocation(program, "aPos");
    gl.enableVertexAttribArray(aPos);
    gl.vertexAttribPointer(aPos, 2, gl.FLOAT, false, 0, 0);

    const u = {
      center: gl.getUniformLocation(program, "uCenter"),
      half: gl.getUniformLocation(program, "uHalf"),
      front: gl.getUniformLocation(program, "uFront"),
      back: gl.getUniformLocation(program, "uBack"),
      rot: gl.getUniformLocation(program, "uRot"),
      r: gl.getUniformLocation(program, "uR"),
      alpha: gl.getUniformLocation(program, "uAlpha"),
      stereo: gl.getUniformLocation(program, "uStereo"),
    };
    gl.uniform1i(u.front, 0);
    gl.uniform1i(u.back, 1);
    gl.uniform1f(u.r, DISC_RADIUS_UV);
    gl.enable(gl.BLEND);
    gl.blendFunc(gl.ONE, gl.ONE_MINUS_SRC_ALPHA);

    const textures = new Map<string, WebGLTexture | "loading">();
    let dirty = true;
    const texture = (src: string) => {
      const t = textures.get(src);
      if (t && t !== "loading") return t;
      if (!t) {
        textures.set(src, "loading");
        const img = new Image();
        img.decoding = "async";
        img.onload = () => {
          const tex = gl.createTexture();
          if (!tex) return;
          gl.bindTexture(gl.TEXTURE_2D, tex);
          gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, img);
          gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
          gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
          gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
          gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
          textures.set(src, tex);
          dirty = true;
        };
        img.src = src;
      }
      return null;
    };

    const resize = () => {
      const dpr = Math.min(window.devicePixelRatio || 1, lowPower ? 1 : 2);
      const w = Math.round(canvas.clientWidth * dpr);
      const h = Math.round(canvas.clientHeight * dpr);
      if (canvas.width !== w || canvas.height !== h) {
        canvas.width = w;
        canvas.height = h;
        dirty = true;
      }
    };

    let raf = 0;
    let lastPos = Number.NaN;
    const frame = () => {
      raf = requestAnimationFrame(frame);
      resize();
      const pos = positionRef.current;
      if (!dirty && pos === lastPos) return;
      lastPos = pos;
      dirty = false;

      const W = canvas.clientWidth;
      const H = canvas.clientHeight;
      gl.viewport(0, 0, canvas.width, canvas.height);
      gl.clearColor(0, 0, 0, 0);
      gl.clear(gl.COLOR_BUFFER_BIT);

      // Same footprint and anchor as the CSS layout the info card was designed around.
      const desktop = W >= 768;
      const D = desktop ? Math.min(0.52 * W, 0.82 * H) : Math.min(0.88 * W, 0.62 * H);
      const cx = desktop ? W - 0.06 * W - D / 2 : W / 2;
      const cy = H / 2;

      // Preload neighbours so a globe is ready before it rolls in.
      for (let i = Math.max(0, Math.floor(pos) - 1); i <= Math.min(destinations.length - 1, Math.floor(pos) + 2); i++) {
        texture(frontSrc(destinations[i]));
        texture(backSrc(destinations[i]));
      }

      destinations.forEach((d, i) => {
        const p = poseRef.current(pos - i, W / (D / 2));
        if (!p || p.alpha <= 0.002) return;
        const f = texture(frontSrc(d));
        const b = texture(backSrc(d));
        if (!f || !b) {
          dirty = true; // keep polling until both hemispheres are uploaded
          return;
        }
        const radius = (D / 2) * p.scale;
        const px = cx + p.x * W;
        const py = cy + p.y * H;
        gl.uniform2f(u.center, (px / W) * 2 - 1, 1 - (py / H) * 2);
        gl.uniform2f(u.half, (radius / W) * 2, (radius / H) * 2);
        gl.uniformMatrix3fv(u.rot, false, inverseRotation(p.yaw, p.pitch));
        gl.uniform1f(u.alpha, p.alpha);
        // Zero when a hemisphere faces the camera, so resting globes match the artwork.
        gl.uniform1f(u.stereo, Math.abs(Math.sin(p.yaw)));
        gl.activeTexture(gl.TEXTURE0);
        gl.bindTexture(gl.TEXTURE_2D, f);
        gl.activeTexture(gl.TEXTURE1);
        gl.bindTexture(gl.TEXTURE_2D, b);
        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
      });
    };
    raf = requestAnimationFrame(frame);

    const lost = (e: Event) => {
      e.preventDefault();
      onUnsupported();
    };
    canvas.addEventListener("webglcontextlost", lost);
    return () => {
      cancelAnimationFrame(raf);
      canvas.removeEventListener("webglcontextlost", lost);
      textures.forEach((t) => t !== "loading" && gl.deleteTexture(t));
      gl.deleteBuffer(buf);
      gl.deleteProgram(program);
    };
  }, [destinations, positionRef, frontSrc, backSrc, lowPower, onUnsupported]);

  return <canvas ref={canvasRef} aria-hidden className="absolute inset-0 h-full w-full" />;
}

export type { GlobePose };
