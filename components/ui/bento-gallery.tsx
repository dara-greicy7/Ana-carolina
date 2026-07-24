import React from "react";
import { cn } from "@/lib/utils";

export interface BentoImage {
  src: string;
  alt: string;
}

interface BentoGalleryProps {
  images: BentoImage[];
  className?: string;
}

export function BentoGallery({ images, className }: BentoGalleryProps) {
  // Ensure we have exactly 4 images for this layout, pad if necessary
  const safeImages = [...images];
  while (safeImages.length < 4) {
    safeImages.push(images[0] || { src: "", alt: "Fallback Image" });
  }

  return (
    <div className={cn("grid grid-cols-1 md:grid-cols-4 md:grid-rows-2 gap-4 w-full h-[1200px] md:h-[600px] lg:h-[700px]", className)}>
      {/* Left Item (col-span-2, row-span-2) */}
      <div className="md:col-span-2 md:row-span-2 rounded-2xl overflow-hidden group relative shadow-2xl">
        <div 
          className="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
          style={{ backgroundImage: `url('${safeImages[0].src}')` }}
          role="img"
          aria-label={safeImages[0].alt}
        />
        <div className="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors duration-500" />
      </div>

      {/* Middle Top Item (col-span-1, row-span-1) */}
      <div className="md:col-span-1 md:row-span-1 rounded-2xl overflow-hidden group relative shadow-xl">
        <div 
          className="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
          style={{ backgroundImage: `url('${safeImages[1].src}')` }}
          role="img"
          aria-label={safeImages[1].alt}
        />
        <div className="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors duration-500" />
      </div>

      {/* Middle Bottom Item (col-span-1, row-span-1) */}
      <div className="md:col-span-1 md:row-span-1 rounded-2xl overflow-hidden group relative shadow-xl">
        <div 
          className="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
          style={{ backgroundImage: `url('${safeImages[2].src}')` }}
          role="img"
          aria-label={safeImages[2].alt}
        />
        <div className="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors duration-500" />
      </div>

      {/* Right Item (col-span-1, row-span-2) */}
      <div className="md:col-span-1 md:row-span-2 rounded-2xl overflow-hidden group relative shadow-2xl">
        <div 
          className="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
          style={{ backgroundImage: `url('${safeImages[3].src}')` }}
          role="img"
          aria-label={safeImages[3].alt}
        />
        <div className="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors duration-500" />
      </div>
    </div>
  );
}
