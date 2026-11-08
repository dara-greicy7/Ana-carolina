import { HomePageClient } from "@/components/home-page-client";
import { getHeroImageUrls, getRandomHeroImageUrl } from "@/lib/hero-image-service";

export const dynamic = "force-dynamic";

export default function HomePage() {
  // A random pick per request makes the hero change on every page refresh;
  // the client then rotates sequentially from there every 21 seconds.
  const initialHeroImageUrl = getRandomHeroImageUrl() ?? "/hero-images/image_1.jpg";

  // The hero photo is a CSS background, which the browser only discovers after styles
  // load; preloading it is the largest single LCP win on this page.
  return (
    <>
      <link rel="preload" as="image" href={initialHeroImageUrl} fetchPriority="high" />
      <HomePageClient
        initialHeroImageUrl={initialHeroImageUrl}
        heroImageUrls={getHeroImageUrls()}
      />
    </>
  );
}
