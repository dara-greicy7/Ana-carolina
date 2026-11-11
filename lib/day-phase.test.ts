import { describe, expect, it } from "vitest";
import { dayPhaseAt, msUntilNextMinute } from "./day-phase";

const at = (h: number, m: number, s = 0) => new Date(2026, 10, 11, h, m, s);

describe("dayPhaseAt", () => {
  it.each([
    [6, 29, "night"],
    [6, 30, "day"],
    [12, 0, "day"],
    [18, 30, "day"],
    [18, 31, "night"],
    [23, 59, "night"],
    [0, 0, "night"],
  ] as const)("%i:%i is %s", (h, m, phase) => {
    expect(dayPhaseAt(at(h, m))).toBe(phase);
  });

  it("stays day for the whole 6:30 and 18:30 minutes", () => {
    expect(dayPhaseAt(at(6, 30, 59))).toBe("day");
    expect(dayPhaseAt(at(18, 30, 59))).toBe("day");
  });
});

describe("msUntilNextMinute", () => {
  it("counts to the next minute boundary", () => {
    expect(msUntilNextMinute(at(10, 0, 0))).toBe(60_000);
    expect(msUntilNextMinute(at(10, 0, 45))).toBe(15_000);
  });
});
