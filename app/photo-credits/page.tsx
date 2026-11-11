import type { Metadata } from "next";
import credits from "@/lib/photo-credits.json";
import { destinations } from "@/lib/destinations";

export const metadata: Metadata = { title: "Photo credits | Conative Time" };

const LICENSE_URL: Record<string, string> = {
  "CC BY 2.0": "https://creativecommons.org/licenses/by/2.0/",
  "CC BY 3.0": "https://creativecommons.org/licenses/by/3.0/",
  "CC BY 4.0": "https://creativecommons.org/licenses/by/4.0/",
  "CC BY-SA 2.0": "https://creativecommons.org/licenses/by-sa/2.0/",
  "CC BY-SA 2.5": "https://creativecommons.org/licenses/by-sa/2.5/",
  "CC BY-SA 3.0": "https://creativecommons.org/licenses/by-sa/3.0/",
  "CC BY-SA 4.0": "https://creativecommons.org/licenses/by-sa/4.0/",
  CC0: "https://creativecommons.org/publicdomain/zero/1.0/",
};

export default function PhotoCreditsPage() {
  const name = (slug: string) => destinations.find((d) => d.slug === slug)?.name ?? slug;
  return (
    <section className="mx-auto max-w-4xl px-6 pt-32 pb-24">
      <h1 className="text-4xl font-bold">Photo credits</h1>
      <p className="mt-4 text-white/70">
        The destination photos on the home page are real photographs from Wikimedia Commons, used under the licences below.
        They are resized for the web; nothing else is changed.
      </p>
      <ul className="mt-10 divide-y divide-white/10">
        {credits.map((c) => (
          <li key={`${c.slug}-${c.phase}`} className="py-4 text-sm">
            <p className="font-semibold text-white">
              {name(c.slug)} · {c.phase === "day" ? "Day" : "Night"}
            </p>
            <p className="mt-1 text-white/70">
              <a href={c.source} target="_blank" rel="noreferrer" className="underline hover:text-white">{c.title}</a>
              {c.author && <> by {c.author}</>},{" "}
              {LICENSE_URL[c.license] ? (
                <a href={LICENSE_URL[c.license]} target="_blank" rel="noreferrer" className="underline hover:text-white">{c.license}</a>
              ) : (
                c.license
              )}
            </p>
          </li>
        ))}
      </ul>
    </section>
  );
}
