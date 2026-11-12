"use client";

import { Moon, Sun } from "lucide-react";
import { useDayPhase } from "@/lib/use-day-phase";

const OPTIONS = [
  { value: "day", label: "Show daytime photos", Icon: Sun },
  { value: "night", label: "Show night photos", Icon: Moon },
] as const;

export function DayNightToggle() {
  const { phase, choose } = useDayPhase();
  return (
    <div role="group" aria-label="Background photos" className="flex items-center">
      {OPTIONS.map(({ value, label, Icon }) => (
        <button
          key={value}
          type="button"
          onClick={() => choose(value)}
          aria-pressed={phase === value}
          aria-label={label}
          title={label}
          className={`flex h-11 w-7 items-center justify-center rounded-full transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-sky-300 ${
            phase === value ? "text-white" : "text-white/45 hover:text-white"
          }`}
        >
          <Icon aria-hidden className="h-5 w-5" strokeWidth={phase === value ? 2.25 : 1.75} />
        </button>
      ))}
    </div>
  );
}
