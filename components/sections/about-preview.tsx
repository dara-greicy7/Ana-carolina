"use client";

import Image from "next/image";
import { motion } from "framer-motion";
import { GlassCard } from "@/components/ui/glass-card";

const awards = [
  { year: "2023", title: "Smart Woman - Innovator Award Recipient", org: "Smart Meetings" },
  { year: "2021", title: "Kathy Keegan Cummins Professional Development Award", org: "MPI New England" },
  { year: "2021", title: "Helen Brett™ Scholarship Recipient", org: "IAEE" }
];

export function AboutPreview() {
  return (
    <section className="py-24 px-6 relative overflow-hidden" id="about">
      <div className="max-w-7xl mx-auto">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <motion.div
            initial={{ opacity: 0, x: -50 }}
            whileInView={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.8 }}
            viewport={{ once: true }}
            className="relative aspect-square max-w-md w-full mx-auto lg:mx-0 rounded-[2rem] overflow-hidden border border-white/10 shadow-2xl shadow-black/50"
          >
            <Image
              src="/images/layout/carol.jpeg"
              alt="Ana Carolina Villar, Chief Experience Strategist at Conative TIME"
              fill
              sizes="(max-width: 1024px) 100vw, 448px"
              className="object-cover"
            />
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 50 }}
            whileInView={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
            viewport={{ once: true }}
          >
            <h2 className="text-sm font-bold tracking-widest text-white/50 uppercase mb-4">Our Story</h2>
            <h3 className="text-4xl md:text-5xl font-bold text-white mb-8 leading-tight">
              Ana Carolina Villar, <br/>
              <span className="text-white/60">MS, CMP, VEMM</span>
            </h3>
            <p className="text-xl text-white/70 leading-relaxed mb-8">
              Chief Experience Strategist at Conative TIME. We help organizations execute events that result in company growth. Our mission is to take customers on a discovery journey, enabling relationships that result in transformative experiences.
            </p>
            <div className="flex flex-wrap gap-4">
              <div className="px-6 py-3 rounded-full bg-white/10 border border-white/20 text-white font-medium">Innovator</div>
              <div className="px-6 py-3 rounded-full bg-white/10 border border-white/20 text-white font-medium">Strategist</div>
              <div className="px-6 py-3 rounded-full bg-white/10 border border-white/20 text-white font-medium">Leader</div>
            </div>
          </motion.div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16">
          {awards.map((award, i) => (
            <motion.div
              key={award.title}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: i * 0.1 }}
              viewport={{ once: true }}
            >
              <GlassCard className="p-6 h-full flex gap-6 items-center hover:bg-white/5 transition-colors">
                <div className="text-3xl font-bold text-white/20 font-mono">{award.year}</div>
                <div>
                  <div className="text-white font-bold text-lg mb-1">{award.title}</div>
                  <div className="text-white/50 text-sm uppercase tracking-wider">{award.org}</div>
                </div>
              </GlassCard>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
