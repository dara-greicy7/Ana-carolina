"use client";

import { useEffect, useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { AnimatePresence, motion } from "framer-motion";
import { cn } from "@/lib/utils";
import type { GalleryDestination } from "@/lib/gallery-destinations";

type DestinationGalleryProps = {
  destination: GalleryDestination;
};

type ActiveImage = {
  src: string;
  alt: string;
};

const tileSpans = [
  "md:col-span-6 md:row-span-3",
  "md:col-span-3 md:row-span-2",
  "md:col-span-3 md:row-span-2",
  "md:col-span-4 md:row-span-3",
  "md:col-span-4 md:row-span-3",
  "md:col-span-4 md:row-span-2",
  "md:col-span-3 md:row-span-2",
  "md:col-span-3 md:row-span-2",
];

function buildImageAlt(destination: GalleryDestination, index: number) {
  return `${destination.title} image ${index + 1}`;
}

export function DestinationGallery({ destination }: DestinationGalleryProps) {
  const [activeImage, setActiveImage] = useState<ActiveImage | null>(null);

  useEffect(() => {
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") {
        setActiveImage(null);
      }
    };

    window.addEventListener("keydown", onKeyDown);
    return () => window.removeEventListener("keydown", onKeyDown);
  }, []);

  return (
    <div className="min-h-screen px-6 pb-20 pt-24">
      <div className="mx-auto max-w-7xl">
        <div className="mb-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
          <div className="max-w-3xl">
            <p className="text-xs font-bold uppercase tracking-[0.45em] text-white/45">
              Gallery
            </p>
            <h1 className="mt-4 text-4xl font-bold tracking-tight text-white md:text-6xl">
              {destination.title}
            </h1>
            <p className="mt-5 text-base leading-8 text-white/65 md:text-lg">
              {destination.summary}
            </p>
          </div>

          <Link
            href="/#portfolio"
            className="inline-flex w-fit items-center rounded-full border border-white/15 bg-white/[0.06] px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/[0.12]"
          >
            Back to destinations
          </Link>
        </div>

        <div className="grid grid-cols-1 gap-4 md:grid-cols-12 md:auto-rows-[120px] md:grid-flow-dense">
          {destination.images.map((src, index) => {
            const spanClass = tileSpans[index] ?? "md:col-span-3 md:row-span-2";
            const alt = buildImageAlt(destination, index);

            return (
              <motion.button
                key={src}
                type="button"
                initial={{ opacity: 0, scale: 0.98 }}
                whileInView={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.45, delay: Math.min(index * 0.03, 0.3) }}
                viewport={{ once: true }}
                onClick={() => setActiveImage({ src, alt })}
                className={cn(
                  "group relative min-h-[16rem] overflow-hidden rounded-[1.75rem] border border-white/10 bg-white/[0.03] text-left shadow-xl shadow-black/15 md:min-h-0",
                  spanClass
                )}
              >
                <Image
                  src={src}
                  alt={alt}
                  fill
                  sizes="(max-width: 768px) 100vw, 25vw"
                  className="object-cover transition-transform duration-700 group-hover:scale-105"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent opacity-50 transition-opacity duration-500 group-hover:opacity-20" />
                <div className="absolute inset-x-0 bottom-0 p-4">
                  <div className="text-[11px] uppercase tracking-[0.35em] text-white/75 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    Open image
                  </div>
                </div>
              </motion.button>
            );
          })}
        </div>
      </div>

      <AnimatePresence>
        {activeImage && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/85 px-4 py-8"
            onClick={() => setActiveImage(null)}
          >
            <motion.div
              initial={{ scale: 0.95, y: 16 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.95, y: 16 }}
              transition={{ duration: 0.2 }}
              className="relative max-h-[90vh] w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/10 bg-black shadow-2xl shadow-black/60"
              onClick={(event) => event.stopPropagation()}
            >
              <button
                type="button"
                onClick={() => setActiveImage(null)}
                className="absolute right-4 top-4 z-10 rounded-full border border-white/15 bg-black/50 px-4 py-2 text-xs uppercase tracking-[0.35em] text-white backdrop-blur-md"
              >
                Close
              </button>
              <div className="relative aspect-[16/10] w-full">
                <Image
                  src={activeImage.src}
                  alt={activeImage.alt}
                  fill
                  sizes="100vw"
                  className="bg-black object-contain"
                  priority
                />
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
