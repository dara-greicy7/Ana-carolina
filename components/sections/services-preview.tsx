"use client";

import { motion } from "framer-motion";
import { GlassCard } from "@/components/ui/glass-card";
import content from "@/data/content.json";
import Link from "next/link";

export function ServicesPreview() {
  // @ts-ignore
  const services = content.servicios?.slice(0, 3) || [];

  return (
    <section className="py-24 px-6 bg-white/[0.02]" id="services">
      <div className="max-w-7xl mx-auto">
        <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            viewport={{ once: true }}
          >
            <h2 className="text-sm font-bold tracking-widest text-white/50 uppercase mb-4">Our Services</h2>
            <h3 className="text-4xl md:text-5xl font-bold text-white leading-tight">
              Crafting Immersive <br/> Experiences
            </h3>
          </motion.div>
          <Link href="/services" className="text-white hover:text-white/70 transition-colors border-b border-white/20 pb-2">
            View All Services
          </Link>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {services.map((service: any, i: number) => (
            <motion.div
              key={i}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: i * 0.1 }}
              viewport={{ once: true }}
            >
              <Link href={`/services/${service.alias}`}>
                <GlassCard className="p-8 h-full flex flex-col group cursor-pointer hover:bg-white/5 transition-all duration-500">
                  <div className="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-8 group-hover:bg-white/20 transition-colors">
                    <span className="text-white font-bold text-xl">{i + 1}</span>
                  </div>
                  <h4 className="text-2xl font-bold text-white mb-4 group-hover:translate-x-2 transition-transform duration-500">
                    {service.title}
                  </h4>
                  <p className="text-white/60 leading-relaxed line-clamp-3">
                    {service.content.replace(/<[^>]*>/g, '')}
                  </p>
                </GlassCard>
              </Link>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
