import { HomePageClient } from "@/components/home-page-client";
import { getCurrentHeroImage } from "@/lib/hero-image-service";

// The hero rotation schedule lives server-side, so the page must render per
// request (this also gives the CSP proxy a per-request nonce to work with).
export const dynamic = "force-dynamic";

export default async function HomePage() {
  const hero = await getCurrentHeroImage();
  const initialHeroImageUrl = hero?.url ?? "/images/layout/blog.jpg";

  return <HomePageClient initialHeroImageUrl={initialHeroImageUrl} />;
}
