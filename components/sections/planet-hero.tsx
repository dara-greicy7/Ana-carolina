"use client";

import { useEffect, useRef, useState } from "react";
import { useMotionValueEvent, useReducedMotion, useScroll, useSpring } from "framer-motion";
import { ChevronDown } from "lucide-react";
import { Button } from "@/components/ui/button";
import { destinations } from "@/lib/destinations";
import { PLANET_SPIN_DEG, miniPlanet } from "@/lib/hero-planets";
import type { DayPhase } from "@/lib/day-phase";
import { useDayPhase } from "@/lib/use-day-phase";

type PlanetHeroProps = {
  title: string;
  subtitle: string;
  description: string;
  primaryCta: { text: string; onClick: () => void };
  secondaryCta: { text: string; onClick: () => void };
};

// Scroll budget per destination, in viewport heights.
const PER_CITY_VH = 40;
// Within one destination: the planet spins upright, then holds, with that
// city's close-up as the backdrop the whole time.
const TURN_END = 0.55;

const clamp = (v: number, lo = 0, hi = 1) => Math.min(hi, Math.max(lo, v));
const ease = (t: number) => t * t * (3 - 2 * t);
// Real photographs (see /photo-credits), one for daytime and one for night.
// Machu Picchu has no usable night photo, so it keeps its day photo at night.
const NO_NIGHT_PHOTO = new Set(["machu-picchu"]);
const photoBase = (slug: string, phase: DayPhase) =>
  `/planets/${slug}-${phase === "night" && !NO_NIGHT_PHOTO.has(slug) ? "night" : "day"}`;
const photo = (slug: string, phase: DayPhase) => `${photoBase(slug, phase)}-1920.webp`;
// 3840 px (4K) for large or high-density screens; the browser picks per viewport.
const photoSet = (slug: string, phase: DayPhase) =>
  `${photoBase(slug, phase)}-1920.webp 1920w, ${photoBase(slug, phase)}-3840.webp 3840w`;

export function PlanetHero({ title, subtitle, description, primaryCta, secondaryCta }: PlanetHeroProps) {
  const reduce = useReducedMotion();
  const trackRef = useRef<HTMLElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const posRef = useRef(0);
  const [index, setIndex] = useState(0);
  // Exactly one city is ever behind the planet. The swap waits until the new
  // close-up has decoded, so it is a clean cut rather than a blank or a blend.
  const { phase } = useDayPhase();
  const [bg, setBg] = useState<{ slug: string; phase: DayPhase } | null>(null);
  const count = destinations.length;

  const { scrollYProgress } = useScroll({ target: trackRef, offset: ["start start", "end end"] });
  const smooth = useSpring(scrollYProgress, { stiffness: 120, damping: 26, restDelta: 0.0001 });
  useMotionValueEvent(smooth, "change", (v) => {
    const pos = clamp(v) * count;
    posRef.current = pos;
    const i = Math.min(count - 1, Math.floor(pos));
    setIndex((cur) => (cur === i ? cur : i));
  });

  useEffect(() => {
    if (reduce) return;
    const canvas = canvasRef.current;
    const ctx = canvas?.getContext("2d");
    if (!canvas || !ctx) return;
    const cache = new Map<string, HTMLImageElement>();
    let dirty = true;
    const get = (src: string) => {
      let img = cache.get(src);
      if (!img) {
        img = new Image();
        img.decoding = "async";
        img.onload = () => {
          dirty = true;
        };
        img.src = src;
        cache.set(src, img);
      }
      return img.complete && img.naturalWidth > 0 ? img : null;
    };

    let raf = 0;
    let last = -1;
    let shown: HTMLImageElement | null = null;
    const frame = () => {
      raf = requestAnimationFrame(frame);
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      const w = Math.round(canvas.clientWidth * dpr);
      const h = Math.round(canvas.clientHeight * dpr);
      if (canvas.width !== w || canvas.height !== h) {
        canvas.width = w;
        canvas.height = h;
        dirty = true;
      }
      const pos = posRef.current;
      if (!dirty && pos === last) return;
      last = pos;
      dirty = false;

      const i = Math.min(count - 1, Math.floor(pos));
      const local = pos - i;
      // Keep the last decoded planet on screen until the next one is ready, so
      // the planet never blinks out on a slow connection.
      const img = get(miniPlanet(destinations[i].slug)) ?? shown;
      shown = img;
      if (i + 1 < count) get(miniPlanet(destinations[i + 1].slug));
      if (i + 2 < count) get(miniPlanet(destinations[i + 2].slug));

      // Scrolling down turns the planet clockwise until the city stands
      // upright; scrolling up turns it back. The next planet takes over with a
      // clean cut at the same scroll point as the background photo, because a
      // dissolve between two artworks reads as a double exposure.
      const turn = ease(clamp(local / TURN_END));
      const angle = (-PLANET_SPIN_DEG * (1 - turn) * Math.PI) / 180;

      ctx.clearRect(0, 0, w, h);
      // Desktop sizing measured from the approved reference: the disc spans
      // 90.5% of the viewport height (43.2% of its width), its right edge sits
      // at 94.4% of the width and its centre at 54.1% of the height.
      const wide = canvas.clientWidth >= 768;
      const disc = wide ? Math.min(0.905 * h, 0.432 * w) : Math.min(w, h) * 0.92 * 0.98;
      const cx = wide ? 0.944 * w - disc / 2 : w / 2;
      const cy = wide ? 0.541 * h : h / 2;
      if (img) {
        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(angle);
        ctx.drawImage(img, -disc / 2, -disc / 2, disc, disc);
        ctx.restore();
        // The light does not turn with the planet: a fixed highlight and
        // limb shadow make the spinning artwork read as a lit sphere.
        // Stops short of the artwork's faded rim (scripts/build-mini-planets.py
        // fades the last 3% of the width) so it never draws past the planet.
        const limb = disc * 0.47;
        const shade = ctx.createRadialGradient(cx - disc * 0.18, cy - disc * 0.2, disc * 0.05, cx, cy, limb);
        shade.addColorStop(0, "rgba(255,255,255,0.10)");
        shade.addColorStop(0.55, "rgba(255,255,255,0)");
        shade.addColorStop(0.85, "rgba(2,6,23,0.10)");
        shade.addColorStop(1, "rgba(2,6,23,0.22)");
        ctx.fillStyle = shade;
        ctx.beginPath();
        ctx.arc(cx, cy, limb, 0, Math.PI * 2);
        ctx.fill();
      }
    };
    raf = requestAnimationFrame(frame);
    return () => cancelAnimationFrame(raf);
  }, [reduce, count]);

  // Lands where the next planet has just turned to face the visitor.
  const nextCity = () => {
    const track = trackRef.current;
    if (!track || index >= count - 1) {
      document.getElementById("about")?.scrollIntoView({ behavior: "smooth" });
      return;
    }
    const top = track.getBoundingClientRect().top + window.scrollY;
    const length = track.offsetHeight - window.innerHeight;
    window.scrollTo({ top: top + (length * (index + 1 + TURN_END)) / count, behavior: "smooth" });
  };

  const skipCities = () => {
    document.getElementById("about")?.scrollIntoView({ behavior: "smooth" });
  };

  useEffect(() => {
    if (!phase) return;
    let live = true;
    const load = (slug: string) => {
      const img = new Image();
      img.sizes = "100vw";
      img.srcset = photoSet(slug, phase);
      img.src = photo(slug, phase);
      return img.decode().catch(() => undefined);
    };
    const slug = destinations[index].slug;
    load(slug).then(() => live && setBg({ slug, phase }));
    if (index + 1 < destinations.length) load(destinations[index + 1].slug);
    return () => {
      live = false;
    };
  }, [index, phase]);

  const d = destinations[index];
  const copy = (
    <div className="relative z-10 max-w-xl [text-shadow:0_2px_14px_rgba(2,6,23,0.85),0_1px_3px_rgba(2,6,23,0.9)]">
      <h1 className="text-4xl font-extrabold tracking-tight text-white sm:text-6xl md:text-7xl">{title}</h1>
      <p className="mt-3 text-xl font-bold tracking-tight text-white/90 sm:mt-4 sm:text-3xl">{subtitle}</p>
      <p className="mt-4 text-base font-medium leading-7 text-white/85 sm:mt-6 sm:text-lg sm:leading-8">{description}</p>
      <div className="mt-6 flex flex-wrap items-center gap-3 sm:mt-8 sm:gap-4">
        <Button onClick={primaryCta.onClick} size="lg" className="bg-white/10 backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 text-base font-semibold tracking-wide">
          {primaryCta.text}
        </Button>
        <Button onClick={secondaryCta.onClick} size="lg" className="bg-white text-slate-900 hover:bg-sky-100 text-base font-semibold tracking-wide">
          {secondaryCta.text}
        </Button>
      </div>
    </div>
  );

  if (reduce) {
    return (
      <section aria-label="Welcome" className="relative flex min-h-screen items-center bg-[#0b1224] px-6 pt-24 pb-16 md:px-12">
        <div className="mx-auto grid w-full max-w-7xl items-center gap-10 md:grid-cols-2">
          {copy}
          <img src={miniPlanet(destinations[0].slug)} alt={`Little planet of ${destinations[0].name}`} width={1254} height={1254} className="mx-auto w-full max-w-[560px]" />
        </div>
      </section>
    );
  }

  return (
    <section ref={trackRef} id="destinations" aria-label="Welcome" className="relative bg-[#0b1224]" style={{ height: `calc(100vh + ${count * PER_CITY_VH}vh)` }}>
      <div className="sticky top-0 h-screen overflow-hidden">
        {/* eslint-disable-next-line @next/next/no-img-element */}
        {bg && <img src={photo(bg.slug, bg.phase)} srcSet={photoSet(bg.slug, bg.phase)} sizes="100vw" alt="" aria-hidden data-phase={bg.phase} className="absolute inset-0 h-full w-full object-cover" />}
        <div aria-hidden className="absolute inset-0 bg-gradient-to-b from-[#0b1224]/75 via-[#0b1224]/45 to-transparent md:bg-gradient-to-r md:from-[#0b1224]/80 md:via-[#0b1224]/40 md:via-35% md:to-transparent md:to-55%" />
        <div className="relative mx-auto grid h-full md:static w-full max-w-7xl grid-rows-[auto_1fr] items-center gap-2 px-6 pt-24 pb-10 md:grid-cols-[minmax(0,1fr)_minmax(0,1.1fr)] md:grid-rows-1 md:px-12 md:pt-20">
          <div>
            {copy}
            <p className="relative z-10 mt-4 text-xs font-semibold [text-shadow:0_1px_3px_rgba(2,6,23,0.9)] sm:mt-8 sm:text-sm uppercase tracking-[0.2em] text-sky-200" aria-hidden>
              {d.name}, {d.region}
            </p>
          </div>
          <canvas ref={canvasRef} aria-hidden className="h-full max-h-[78vh] w-full md:absolute md:inset-0 md:max-h-none" />
        </div>
        <p className="sr-only">Scrolling spins a small planet for each of our {count} destinations.</p>
        <button
          type="button"
          onClick={nextCity}
          aria-label={index < count - 1 ? `Next destination: ${destinations[index + 1].name}` : "Continue to About"}
          className="absolute bottom-3 left-1/2 flex h-11 w-11 -translate-x-1/2 items-center justify-center rounded-full text-white/70 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-sky-300"
        >
          <ChevronDown aria-hidden className="h-6 w-6 animate-bounce" />
        </button>
        <button
          type="button"
          onClick={skipCities}
          className="absolute bottom-8 right-4 md:right-6 hidden md:block rounded-full border border-white/15 bg-white/5 px-4 py-2 text-xs uppercase tracking-[0.2em] text-white/70 backdrop-blur hover:bg-white/10 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-sky-300"
        >
          Skip cities
        </button>
      </div>
    </section>
  );
}
