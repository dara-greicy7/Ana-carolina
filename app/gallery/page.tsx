import Link from "next/link";
import { DestinationGallery } from "@/components/sections/destination-gallery";
import {
  galleryDestinations,
  getGalleryDestination,
} from "@/lib/gallery-destinations";

type GalleryPageProps = {
  searchParams?: {
    destination?: string;
  };
};

export default function GalleryPage({ searchParams }: GalleryPageProps) {
  const destination = getGalleryDestination(searchParams?.destination);

  return (
    <main className="relative min-h-screen overflow-hidden bg-[#060814]">
      <div className="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.16),_transparent_40%),linear-gradient(180deg,_rgba(9,12,24,0.98),_rgba(4,6,14,1))]" />

      <div className="mx-auto flex max-w-7xl flex-wrap gap-3 px-6 pt-6">
        {galleryDestinations.map((item) => {
          const isActive = item.slug === destination.slug;

          return (
            <Link
              key={item.slug}
              href={`/gallery?destination=${encodeURIComponent(item.slug)}`}
              className={[
                "rounded-full border px-4 py-2 text-sm font-medium transition-colors",
                isActive
                  ? "border-white/25 bg-white/15 text-white"
                  : "border-white/10 bg-white/[0.04] text-white/70 hover:bg-white/[0.08] hover:text-white",
              ].join(" ")}
            >
              {item.title}
            </Link>
          );
        })}
      </div>

      <DestinationGallery destination={destination} />
    </main>
  );
}
