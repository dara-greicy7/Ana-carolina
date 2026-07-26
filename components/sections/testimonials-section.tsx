"use client";

import { motion } from "framer-motion";
import { GlassCard } from "@/components/ui/glass-card";

export function TestimonialsSection() {
  const testimonials = [
    {
      quote: "Conative TIME transformed our annual conference into a global immersive experience. The attention to detail was unparalleled.",
      author: "Director of Events",
      company: "Fortune 500 Tech Corp"
    },
    {
      quote: "Ana's strategic vision helped us navigate the transition to hybrid events seamlessly. Truly an innovator in the space.",
      author: "Marketing VP",
      company: "Global Travel Group"
    },
    {
      quote: "The discovery journey they planned for our leadership retreat was life-changing for our team culture.",
      author: "CEO",
      company: "Innova Startups"
    }
  ];

  return (
    <section className="py-24 px-6 bg-white/[0.01]" id="testimonials">
      <div className="max-w-7xl mx-auto">
        <h2 className="text-sm font-bold tracking-widest text-white/50 uppercase mb-4 text-center">Testimonials</h2>
        <h3 className="text-4xl md:text-5xl font-bold text-white mb-20 text-center">What Our Partners Say</h3>
        
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {testimonials.map((t, i) => (
            <motion.div
              key={i}
              initial={{ opacity: 0, scale: 0.95 }}
              whileInView={{ opacity: 1, scale: 1 }}
              transition={{ duration: 0.8, delay: i * 0.1 }}
              viewport={{ once: true }}
            >
              <GlassCard className="p-8 h-full flex flex-col justify-between italic">
                <p className="text-lg text-white/80 mb-8">&ldquo;{t.quote}&rdquo;</p>
                <div className="not-italic">
                  <div className="text-white font-bold">{t.author}</div>
                  <div className="text-white/40 text-sm">{t.company}</div>
                </div>
              </GlassCard>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
