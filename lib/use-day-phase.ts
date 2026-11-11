"use client";

import { useCallback, useEffect, useState } from "react";
import { dayPhaseAt, msUntilNextMinute, type DayPhase } from "@/lib/day-phase";

const CHOICE_KEY = "conative-day-phase";

const isPhase = (v: unknown): v is DayPhase => v === "day" || v === "night";

function readChoice(): DayPhase | null {
  try {
    const v = window.sessionStorage.getItem(CHOICE_KEY);
    return isPhase(v) ? v : null;
  } catch {
    return null;
  }
}

// phase is null until mounted: the server cannot know the visitor's clock, so
// it is only decided in the browser. Without a choice it follows the clock and
// is re-checked at every minute boundary (6:30 am / 6:31 pm). Picking day or
// night with the sun/moon buttons pins it for the rest of the visit (session
// storage); ?phase=day|night in the URL pins it for previews.
export function useDayPhase(): { phase: DayPhase | null; choose: (p: DayPhase) => void } {
  const [clock, setClock] = useState<DayPhase | null>(null);
  const [choice, setChoice] = useState<DayPhase | null>(null);

  useEffect(() => {
    const forced = new URLSearchParams(window.location.search).get("phase");
    setChoice(isPhase(forced) ? forced : readChoice());
    let timer = 0;
    const tick = () => {
      const now = new Date();
      setClock(dayPhaseAt(now));
      timer = window.setTimeout(tick, msUntilNextMinute(now) + 50);
    };
    tick();
    return () => window.clearTimeout(timer);
  }, []);

  const choose = useCallback((p: DayPhase) => {
    setChoice(p);
    try {
      window.sessionStorage.setItem(CHOICE_KEY, p);
    } catch {
      // Storage can be blocked (private mode); the choice still holds for this page.
    }
  }, []);

  return { phase: choice ?? clock, choose };
}
