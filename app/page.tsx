import { HomePageClient } from "@/components/home-page-client";
import { getHeroImageUrls, getRandomHeroImageUrl } from "@/lib/hero-image-service";

export const dynamic = "force-dynamic";

export default function HomePage() {
  // A random pick per request makes the hero change on every page refresh;
  // the client then rotates sequentially from there every 21 seconds.
  const initialHeroImageUrl = getRandomHeroImageUrl() ?? "/hero-images/image_1.jpg";

  return (
    <HomePageClient
      initialHeroImageUrl={initialHeroImageUrl}
      heroImageUrls={getHeroImageUrls()}
    />
  );
}
