import { HomePageClient } from "@/components/home-page-client";
import { getCurrentHeroImageUrl } from "@/lib/hero-image-service";

export const dynamic = "force-dynamic";

export default function HomePage() {
  const initialHeroImageUrl = getCurrentHeroImageUrl() ?? "/hero-images/image_1.jpg";

  return <HomePageClient initialHeroImageUrl={initialHeroImageUrl} />;
}
