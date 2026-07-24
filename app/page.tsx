import { HomePageClient } from "@/components/home-page-client";
import { getHeroImageService } from "@/lib/hero-image-service";

export const dynamic = "force-dynamic";

export default function HomePage() {
  const service = getHeroImageService();
  const initialHeroImageUrl =
    service.advance() ?? service.getCurrentImageUrl() ?? "/api/hero-image/file?name=image_1.jpg";

  return <HomePageClient initialHeroImageUrl={initialHeroImageUrl} />;
}
