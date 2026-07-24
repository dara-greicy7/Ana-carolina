"use client";

import Image from "next/image";
import Link from "next/link";
import { motion } from "framer-motion";
import { cn } from "@/lib/utils";
import { galleryDestinations } from "@/lib/gallery-destinations";

function buildDestinationHref(slug: string) {
  return `/gallery?destination=${encodeURIComponent(slug)}`;
}

export function GalleryPreview() {
  return (
    <section className="py-24 px-6" id="portfolio">
      <div className="max-w-7xl mx-auto">
        <div className="text-center mb-14">
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            viewport={{ once: true }}
            className="text-sm font-bold tracking-widest text-white/50 uppercase mb-4"
          >
            Portfolio
          </motion.h2>
          <motion.h3
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.1 }}
            viewport={{ once: true }}
            className="text-4xl md:text-5xl font-bold text-white mb-4"
          >
            Global Destinations
          </motion.h3>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            viewport={{ once: true }}
            className="mx-auto max-w-3xl text-white/65 text-lg leading-8"
          >
            Choose a destination to open its gallery page.
          </motion.p>
        </div>

        <div className="grid grid-cols-1 gap-5 lg:grid-cols-3">
          {galleryDestinations.map((destination, index) => (
            <motion.div
              key={destination.slug}
              initial={{ opacity: 0, y: 24 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: index * 0.08 }}
              viewport={{ once: true }}
            >
              <Link
                href={buildDestinationHref(destination.slug)}
                className={cn(
                  "group relative block overflow-hidden rounded-[2rem] border text-left transition-all duration-300",
                  "border-white/10 bg-white/[0.03] hover:border-white/20 hover:bg-white/[0.06]"
                )}
              >
                <div className="relative aspect-[4/3]">
                  <div className="absolute inset-0">
                    <Image
                      src={destination.images[0]}
                      alt={destination.title}
                      fill
                      sizes="(max-width: 1024px) 100vw, 33vw"
                      className="object-cover transition-transform duration-700 group-hover:scale-105"
                      priority={index === 0}
                    />
                  </div>
                  <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />
                  <div className="absolute inset-x-0 bottom-0 p-6">
                    <div className="text-[11px] uppercase tracking-[0.35em] text-white/55">
                      {String(index + 1).padStart(2, "0")} /{" "}
                      {String(galleryDestinations.length).padStart(2, "0")}
                    </div>
                    <h4 className="mt-3 text-3xl font-bold text-white">
                      {destination.title}
                    </h4>
                    <p className="mt-3 text-sm leading-6 text-white/75 max-w-md">
                      {destination.summary}
                    </p>
                  </div>
                  <div className="absolute right-5 top-5 rounded-full border border-white/15 bg-black/35 px-4 py-2 text-xs uppercase tracking-[0.3em] text-white/80 backdrop-blur-md">
                    {destination.images.length} images
                  </div>
                </div>
              </Link>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
