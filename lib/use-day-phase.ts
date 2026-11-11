"use client";

import { useEffect, useState } from "react";
import { dayPhaseAt, msUntilNextMinute, type DayPhase } from "@/lib/day-phase";

// null until mounted: the server cannot know the visitor's clock, so the
// phase is only decided in the browser. It is re-checked at every minute
// boundary, so the photos change at 6:30 am and 6:31 pm while the page is open.
export function useDayPhase(): DayPhase | null {
  const [phase, setPhase] = useState<DayPhase | null>(null);
  useEffect(() => {
    // ?phase=day or ?phase=night pins the phase, for previewing either set.
    const forced = new URLSearchParams(window.location.search).get("phase");
    if (forced === "day" || forced === "night") {
      setPhase(forced);
      return;
    }
    let timer = 0;
    const tick = () => {
      const now = new Date();
      setPhase(dayPhaseAt(now));
      timer = window.setTimeout(tick, msUntilNextMinute(now) + 50);
    };
    tick();
    return () => window.clearTimeout(timer);
  }, []);
  return phase;
}
