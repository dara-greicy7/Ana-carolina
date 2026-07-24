"use client";

import { motion } from "framer-motion";

export function SkillsSection() {
  const certifications = [
    { title: "CMP", name: "Certified Meeting Professional", desc: "The global standard for event excellence." },
    { title: "VEMM", name: "Virtual Event & Meeting Management", desc: "Expertise in digital engagement strategies." },
    { title: "MS", name: "Master of Science", desc: "Advanced academic foundation in strategic management." }
  ];

  return (
    <section className="py-24 px-6 relative" id="skills">
      <div className="max-w-7xl mx-auto">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
          {certifications.map((cert, i) => (
            <motion.div
              key={i}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: i * 0.15 }}
              viewport={{ once: true }}
              className="group"
            >
              <div className="text-6xl font-black text-white/5 mb-6 group-hover:text-white/10 transition-colors duration-500">
                {cert.title}
              </div>
              <h4 className="text-2xl font-bold text-white mb-4">{cert.name}</h4>
              <p className="text-white/60 leading-relaxed">
                {cert.desc}
              </p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
