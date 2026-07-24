"use client";

import { motion } from "framer-motion";
import { GlassCard } from "@/components/ui/glass-card";
import { Button } from "@/components/ui/button";

export function ContactSection() {
  return (
    <section className="py-24 px-6 bg-white/[0.02]" id="contact">
      <div className="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8 }}
          viewport={{ once: true }}
        >
          <h2 className="text-sm font-bold tracking-widest text-white/50 uppercase mb-4">Contact</h2>
          <h3 className="text-4xl md:text-5xl font-bold text-white mb-8">
            Ready to Plan Your Next <br/>
            <span className="text-white/60">Big Experience?</span>
          </h3>
          
          <div className="space-y-8 mt-12">
            <div>
              <div className="text-white/40 text-sm uppercase tracking-widest mb-2">Email</div>
              <a href="mailto:ana@conativetime.com" className="text-2xl text-white hover:text-white/70 transition-colors">
                ana@conativetime.com
              </a>
            </div>
            <div>
              <div className="text-white/40 text-sm uppercase tracking-widest mb-2">Office Phone</div>
              <a href="tel:617-682-7002" className="text-2xl text-white hover:text-white/70 transition-colors">
                617-682-7002
              </a>
            </div>
            <div>
              <div className="text-white/40 text-sm uppercase tracking-widest mb-2">Location</div>
              <div className="text-2xl text-white">
                40 Cambridge St., Lawrence, MA 01843
              </div>
            </div>
          </div>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, scale: 0.95 }}
          whileInView={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.8, delay: 0.2 }}
          viewport={{ once: true }}
        >
          <GlassCard className="p-8 md:p-12">
            <form className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label className="block text-sm font-medium text-white/50 mb-2">First Name</label>
                  <input type="text" className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-white/20 transition-all" placeholder="Jane" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-white/50 mb-2">Last Name</label>
                  <input type="text" className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-white/20 transition-all" placeholder="Doe" />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-white/50 mb-2">Email Address</label>
                <input type="email" className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-white/20 transition-all" placeholder="jane@example.com" />
              </div>
              <div>
                <label className="block text-sm font-medium text-white/50 mb-2">Message</label>
                <textarea rows={4} className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-white/20 transition-all" placeholder="Tell us about your project..."></textarea>
              </div>
              <Button className="w-full py-8 text-lg font-bold">Start Your Journey</Button>
            </form>
          </GlassCard>
        </motion.div>
      </div>
    </section>
  );
}
