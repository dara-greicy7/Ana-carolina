import type { Metadata } from "next";
import Image from "next/image";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import { getSectionByAlias } from "@/lib/content";
import { sanitizeContent } from "@/lib/sanitize";

export const metadata: Metadata = {
  title: "About Us | Conative Time",
  description:
    "Discover our story, our mission, and what drives us forward.",
};

export default function AboutPage() {
  const aboutItem = getSectionByAlias("about-me");

  return (
    <div className="pt-24 pb-16 flex flex-col items-center min-h-screen relative">
      <div className="fixed inset-0 bg-cover bg-center -z-20 opacity-20" style={{ backgroundImage: 'url(/images/layout/about-me.jpg)' }}></div>
      <AnimatedSection>
        <h1 className="text-4xl md:text-6xl font-bold tracking-tight text-white mb-6">About Us</h1>
        <p className="text-lg text-white/80 max-w-2xl mb-12">
          Discover our story, our mission, and what drives us forward.
        </p>

        <div className="flex flex-col gap-8 max-w-5xl mx-auto">
          {aboutItem && (
            <GlassCard className="flex flex-col md:flex-row gap-8 p-8 items-start">
              {aboutItem.image && (
                <div className="relative w-full md:w-1/3 h-80 rounded-2xl overflow-hidden shrink-0 shadow-2xl shadow-black/50">
                  <Image
                    src={aboutItem.image}
                    alt={aboutItem.title}
                    fill
                    sizes="(max-width: 768px) 100vw, 33vw"
                    className="object-cover transition-transform duration-700 hover:scale-105"
                  />
                </div>
              )}
              <div className="flex flex-col flex-1">
                <h2 className="text-3xl md:text-4xl font-bold text-white mb-6 tracking-tight">{aboutItem.title}</h2>
                <div
                  className="text-white/80 prose prose-invert max-w-none prose-p:leading-relaxed prose-p:mb-4"
                  dangerouslySetInnerHTML={{ __html: sanitizeContent(aboutItem.content) }}
                />
              </div>
            </GlassCard>
          )}

          {/* Executive Credentials Section */}
          <GlassCard className="flex flex-col gap-6 p-8 relative overflow-hidden mt-8 border-white/20">
            <div>
              <h2 className="text-3xl font-bold text-white mb-2 tracking-tight">Ana Carolina Villar</h2>
              <p className="text-lg text-[#0ea5e9] font-medium mb-1">MS, CMP, VEMM | Chief Experience Strategist</p>
              <p className="text-white/60">Conative TIME</p>
            </div>

            <div className="border-t border-white/10 pt-6 mt-2">
              <h3 className="text-xl font-semibold text-white mb-4">Industry Recognition &amp; Awards</h3>
              <ul className="space-y-4 text-white/80">
                <li className="flex items-start gap-3">
                  <span className="text-[#0ea5e9] shrink-0 mt-1">✦</span>
                  <span><strong>2023 Smart Woman - Innovator Award Recipient</strong> | Smart Meetings</span>
                </li>
                <li className="flex items-start gap-3">
                  <span className="text-[#0ea5e9] shrink-0 mt-1">✦</span>
                  <span><strong>2021 Kathy Keegan Cummins Professional Development Award Recipient</strong> | Meeting Professionals International (MPI) New England Chapter</span>
                </li>
                <li className="flex items-start gap-3">
                  <span className="text-[#0ea5e9] shrink-0 mt-1">✦</span>
                  <span><strong>2021 Helen Brett™ Scholarship Recipient</strong> | International Association of Exhibitions and Events (IAEE)</span>
                </li>
              </ul>
            </div>
          </GlassCard>
        </div>
      </AnimatedSection>
    </div>
  );
}
