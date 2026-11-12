"use client";

import { useSyncExternalStore } from "react";
import { dayPhaseAt, msUntilNextMinute, type DayPhase } from "@/lib/day-phase";

const CHOICE_KEY = "conative-day-phase";

const isPhase = (v: unknown): v is DayPhase => v === "day" || v === "night";

// One shared store, so the sun/moon buttons in the navbar and the hero
// backgrounds always agree. Without a choice the phase follows the visitor's
// clock, re-checked at every minute boundary (6:30 am / 6:31 pm). A sun/moon
// choice pins it for the rest of the visit (session storage); ?phase=day|night
// in the URL pins it for previews.
let clock: DayPhase | null = null;
let choice: DayPhase | null = null;
let started = false;
let timer = 0;
const listeners = new Set<() => void>();
const emit = () => listeners.forEach((l) => l());

function start() {
  if (started) return;
  started = true;
  const forced = new URLSearchParams(window.location.search).get("phase");
  if (isPhase(forced)) choice = forced;
  else {
    try {
      const saved = window.sessionStorage.getItem(CHOICE_KEY);
      if (isPhase(saved)) choice = saved;
    } catch {
      // Storage can be blocked (private mode); fall back to the clock.
    }
  }
  const tick = () => {
    const now = new Date();
    clock = dayPhaseAt(now);
    emit();
    timer = window.setTimeout(tick, msUntilNextMinute(now) + 50);
  };
  tick();
}

function subscribe(listener: () => void) {
  listeners.add(listener);
  start();
  return () => {
    listeners.delete(listener);
    if (listeners.size === 0) {
      window.clearTimeout(timer);
      started = false;
    }
  };
}

const getSnapshot = (): DayPhase | null => choice ?? clock;
const getServerSnapshot = (): DayPhase | null => null;

export function chooseDayPhase(p: DayPhase) {
  choice = p;
  try {
    window.sessionStorage.setItem(CHOICE_KEY, p);
  } catch {
    // The choice still holds for this page.
  }
  emit();
}

// null on the server and until mounted: only the browser knows the clock.
export function useDayPhase(): { phase: DayPhase | null; choose: (p: DayPhase) => void } {
  const phase = useSyncExternalStore(subscribe, getSnapshot, getServerSnapshot);
  return { phase, choose: chooseDayPhase };
}
