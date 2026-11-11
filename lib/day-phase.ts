export type DayPhase = "day" | "night";

// Day is 6:30 am through 6:30 pm on the visitor's own clock; 6:31 pm through
// 6:29 am is night. Both ends of the day window are inclusive.
const DAY_START_MIN = 6 * 60 + 30;
const DAY_END_MIN = 18 * 60 + 30;

export function dayPhaseAt(date: Date): DayPhase {
  const minutes = date.getHours() * 60 + date.getMinutes();
  return minutes >= DAY_START_MIN && minutes <= DAY_END_MIN ? "day" : "night";
}

// Milliseconds until the phase can next change: the start of the next minute.
export function msUntilNextMinute(date: Date): number {
  return 60_000 - (date.getSeconds() * 1000 + date.getMilliseconds());
}
