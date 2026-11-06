"use client";

import { useEffect, useRef, useState } from "react";
import { useMotionValueEvent, useReducedMotion, useScroll } from "framer-motion";
import { destinations, type Destination } from "@/lib/destinations";

const COUNT = destinations.length;
// The cubic-bezier(0.25, 0.1, 0.25, 1) "ease" curve, approximated by a smoothstep so
// transforms can be computed directly from scroll position each frame.
const ease = (t: number) => t * t * (3 - 2 * t);
const clamp = (v: number, min = 0, max = 1) => Math.min(max, Math.max(min, v));

const front = (d: Destination) => `/planets/${d.slug}-front.webp`;
const back = (d: Destination) => `/planets/${d.slug}-back.webp`;

// Soft circular falloff hides the square edge of each source image so the globe
// blends into the extended ambient background instead of showing a hard seam.
const globeMask = "radial-gradient(circle at 50% 50%, #000 62%, rgba(0,0,0,0.85) 66%, transparent 71%)";

type BatteryManager = { level: number; charging: boolean; addEventListener: (t: string, cb: () => void) => void };

function useLowPower() {
  const [low, setLow] = useState(false);
  useEffect(() => {
    const nav = navigator as Navigator & { getBattery?: () => Promise<BatteryManager> };
    if (!nav.getBattery) return;
    void nav.getBattery().then((b) => {
      const update = () => setLow(!b.charging && b.level < 0.2);
      update();
      b.addEventListener("levelchange", update);
      b.addEventListener("chargingchange", update);
    });
  }, []);
  return low;
}

function planetStyle(local: number, lowPower: boolean): React.CSSProperties {
  // local: 0 = arriving/settled, 1 = leaving. Negative = not yet arrived.
  const enter = ease(clamp((local + 0.22) / 0.22)); // arrives while the previous globe exits
  const flip = ease(clamp((local - 0.3) / 0.35)); // reveal "-180" side mid-segment
  const exit = ease(clamp((local - 0.82) / 0.18));
  const scale = 0.35 + 0.65 * enter - 0.45 * exit;
  const x = (1 - enter) * 55 - exit * 60;
  const y = (1 - enter) * 18 + exit * 8;
  const opacity = clamp(enter * 1.4) * (1 - exit);
  const blur = (1 - enter) * 8 + exit * 10;
  return {
    transform: `translate3d(${x}vw, ${y}vh, 0) scale(${scale}) rotateY(${flip * 180 - exit * 25}deg) rotateZ(${local * 18}deg)`,
    opacity,
    filter: !lowPower && blur > 0.2 ? `blur(${blur.toFixed(1)}px)` : undefined,
  };
}

export function PlanetJourney() {
  const reduceMotion = useReducedMotion();
  const lowPower = useLowPower();
  const sectionRef = useRef<HTMLElement>(null);
  const [position, setPosition] = useState(0);
  const [paused, setPaused] = useState(false);
  const pauseTimer = useRef<ReturnType<typeof setTimeout>>();

  const { scrollYProgress } = useScroll({ target: sectionRef, offset: ["start start", "end end"] });

  useMotionValueEvent(scrollYProgress, "change", (v) => {
    setPosition(clamp(v) * COUNT);
    setPaused(false);
    clearTimeout(pauseTimer.current);
    pauseTimer.current = setTimeout(() => setPaused(true), 650);
  });

  useEffect(() => {
    pauseTimer.current = setTimeout(() => setPaused(true), 650);
    return () => clearTimeout(pauseTimer.current);
  }, []);

  const active = Math.min(COUNT - 1, Math.floor(position));
  const local = position - active;
  const settled = local > 0.08 && local < 0.84;
  const current = destinations[active];

  const jumpTo = (index: number) => {
    const el = sectionRef.current;
    if (!el) return;
    const top = el.getBoundingClientRect().top + window.scrollY;
    const segment = (el.offsetHeight - window.innerHeight) / COUNT;
    window.scrollTo({ top: top + segment * (index + 0.2), behavior: "smooth" });
  };

  const skip = () => {
    document.getElementById("about")?.scrollIntoView({ behavior: "smooth" });
  };

  if (reduceMotion) {
    return (
      <section id="destinations" aria-labelledby="destinations-title" className="py-24 px-6 bg-[#020617]">
        <div className="max-w-6xl mx-auto">
          <p className="text-xs uppercase tracking-[0.3em] text-white/50">Destinations</p>
          <h2 id="destinations-title" className="mt-3 text-3xl md:text-5xl font-bold">Where will you go next?</h2>
          <ul className="mt-12 grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
            {destinations.map((d) => (
              <li key={d.slug} className="space-y-4">
                <img src={front(d)} alt={`Illustrated little planet of ${d.name}`} loading="lazy" className="w-full aspect-square object-cover" style={{ maskImage: globeMask, WebkitMaskImage: globeMask }} />
                <h3 className="text-xl font-semibold">{d.headline}</h3>
                <p className="text-sm text-white/60">{d.stat}</p>
                <p className="text-white/75 leading-relaxed">{d.story}</p>
              </li>
            ))}
          </ul>
        </div>
      </section>
    );
  }

  return (
    <section
      id="destinations"
      ref={sectionRef}
      aria-labelledby="destinations-title"
      className="relative bg-[#020617]"
      style={{ height: `${COUNT * 100 + 100}vh` }}
    >
      <h2 id="destinations-title" className="sr-only">Destinations</h2>
      <ol className="sr-only">
        {destinations.map((d) => (
          <li key={d.slug}>
            {d.headline}. {d.stat}. {d.story}
          </li>
        ))}
      </ol>

      <div className="sticky top-0 h-[100svh] overflow-hidden" aria-hidden="false">
        {/* Extended background: the active globe itself, blurred, supplies a sky that matches its palette. */}
        {destinations.map((d, i) =>
          Math.abs(i - position) < 1.5 ? (
            <div
              key={`bg-${d.slug}`}
              aria-hidden
              className="absolute inset-0 bg-cover bg-center"
              style={{
                backgroundImage: `url(${front(d)})`,
                filter: "blur(70px) saturate(1.2) brightness(0.55)",
                transform: "scale(1.4)",
                opacity: clamp(1 - Math.abs(i + 0.5 - position) * 0.9) * 0.9,
              }}
            />
          ) : null,
        )}
        <div aria-hidden className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_30%,#020617_85%)]" />

        <div aria-hidden className="absolute inset-0 flex items-center justify-center md:justify-end md:pr-[6vw] [perspective:1600px]">
          {destinations.map((d, i) => {
            const l = position - i;
            if (l < -0.25 || l > 1.05) return null;
            return (
              <div
                key={d.slug}
                className="absolute w-[min(88vw,62vh)] md:w-[min(52vw,82vh)] aspect-square will-change-transform [transform-style:preserve-3d]"
                style={planetStyle(l, lowPower)}
              >
                <img
                  src={front(d)}
                  alt=""
                  decoding="async"
                  className="absolute inset-0 h-full w-full [backface-visibility:hidden] drop-shadow-[0_40px_80px_rgba(56,189,248,0.25)]"
                  style={{ maskImage: globeMask, WebkitMaskImage: globeMask }}
                />
                <img
                  src={back(d)}
                  alt=""
                  decoding="async"
                  className="absolute inset-0 h-full w-full [backface-visibility:hidden] [transform:rotateY(180deg)]"
                  style={{ maskImage: globeMask, WebkitMaskImage: globeMask }}
                />
                <div className="absolute inset-[6%] rounded-full shadow-[0_0_120px_30px_rgba(125,211,252,0.18)] pointer-events-none" />
              </div>
            );
          })}
        </div>

        {!lowPower && <div aria-hidden className="pointer-events-none absolute inset-0 opacity-[0.07] mix-blend-overlay [background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22160%22 height=%22160%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%222%22/></filter><rect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/></svg>')]" />}

        <div className="absolute top-24 left-6 md:left-[6vw] flex items-baseline gap-3 font-mono text-xs tracking-[0.3em] text-white/60">
          <span className="text-white">{String(active + 1).padStart(2, "0")}</span>
          <span>/ {String(COUNT).padStart(2, "0")}</span>
          <span className="ml-3 h-px w-24 bg-white/15 relative overflow-hidden">
            <span className="absolute inset-y-0 left-0 bg-white/80" style={{ width: `${(position / COUNT) * 100}%` }} />
          </span>
        </div>

        <div
          className="absolute inset-x-4 bottom-8 md:inset-x-auto md:left-[6vw] md:bottom-auto md:top-1/2 md:-translate-y-1/2 md:w-[min(440px,38vw)] transition-all duration-700 ease-[cubic-bezier(0.25,0.1,0.25,1)]"
          style={{ opacity: settled ? 1 : 0, transform: `translateY(${settled ? 0 : 24}px)` }}
          aria-live="polite"
        >
          <div className="rounded-2xl border border-white/10 bg-[#020617]/70 backdrop-blur-xl p-6 md:p-8 shadow-2xl">
            <p className="text-xs uppercase tracking-[0.3em] text-sky-200/80">{current.region}</p>
            <h3 className="mt-3 text-2xl md:text-4xl font-bold leading-tight text-white">{current.headline}</h3>
            <p className="mt-4 text-sm font-medium text-white/80">{current.stat}</p>
            <div
              className="grid transition-[grid-template-rows,opacity] duration-500"
              style={{ gridTemplateRows: paused ? "1fr" : "0fr", opacity: paused ? 1 : 0 }}
            >
              <div className="overflow-hidden">
                <p className="mt-4 text-white/75 leading-relaxed">{current.story}</p>
                <div className="mt-6 flex flex-wrap gap-3">
                  <a href="/services" tabIndex={settled && paused ? 0 : -1} className="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-sky-100 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-300">
                    View Packages
                  </a>
                  <a href="#contact" tabIndex={settled && paused ? 0 : -1} className="rounded-full border border-white/25 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-300">
                    Plan this trip
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <nav aria-label="Jump to destination" className="absolute right-3 md:right-6 top-1/2 -translate-y-1/2 hidden sm:flex flex-col gap-2.5">
          {destinations.map((d, i) => (
            <button
              key={d.slug}
              type="button"
              onClick={() => jumpTo(i)}
              aria-label={`Go to ${d.name}`}
              aria-current={i === active ? "true" : undefined}
              className="group flex items-center justify-end gap-3 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-sky-300 rounded-full"
            >
              <span className="text-[11px] tracking-wide text-white/0 group-hover:text-white/80 group-focus-visible:text-white/80 transition-colors">{d.name}</span>
              <span className={`block rounded-full transition-all duration-300 ${i === active ? "h-2.5 w-2.5 bg-white" : "h-1.5 w-1.5 bg-white/35 group-hover:bg-white/70"}`} />
            </button>
          ))}
        </nav>

        <button
          type="button"
          onClick={skip}
          className="absolute bottom-8 right-4 md:right-6 hidden md:block rounded-full border border-white/15 bg-white/5 px-4 py-2 text-xs uppercase tracking-[0.2em] text-white/70 backdrop-blur hover:bg-white/10 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-sky-300"
        >
          Skip journey
        </button>
      </div>
    </section>
  );
}
