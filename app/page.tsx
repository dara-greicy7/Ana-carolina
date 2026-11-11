import { HomePageClient } from "@/components/home-page-client";
import { destinations } from "@/lib/destinations";
import { heroPlanetFrame } from "@/lib/hero-planets";

export default function HomePage() {
  // The hero opens on the first planet's far side; fetch it with the HTML.
  return (
    <>
      <link rel="preload" as="image" href={heroPlanetFrame(destinations[0].slug, 0)} fetchPriority="high" />
      <HomePageClient />
    </>
  );
}
