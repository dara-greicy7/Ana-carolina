import { HomePageClient } from "@/components/home-page-client";
import { destinations } from "@/lib/destinations";
import { miniPlanet } from "@/lib/hero-planets";

export default function HomePage() {
  // The hero opens on the first planet's far side; fetch it with the HTML.
  return (
    <>
      <link rel="preload" as="image" href={miniPlanet(destinations[0].slug)} fetchPriority="high" />
      <HomePageClient />
    </>
  );
}
