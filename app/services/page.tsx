import type { Metadata } from "next";
import Link from "next/link";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import { services } from "@/lib/content";
import { stripHtml } from "@/lib/sanitize";

export const metadata: Metadata = {
  title: "Services | Conative Time",
  description:
    "Comprehensive event and meeting design, destination sourcing, travel consultancy, and incentive programs.",
};

export default function ServicesPage() {
  return (
    <div className="pt-24 pb-16 flex flex-col items-center min-h-screen relative">
      <div className="fixed inset-0 bg-cover bg-center -z-20 opacity-20" style={{ backgroundImage: 'url(/images/layout/servicios.jpg)' }}></div>
      <AnimatedSection>
        <h1 className="text-4xl md:text-6xl font-bold tracking-tight text-white mb-6">Our Services</h1>
        <p className="text-lg text-white/80 max-w-2xl mb-12">
          Comprehensive event and meeting design, destination sourcing, and more.
        </p>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {services.map((item) => (
            <Link key={item.alias} href={`/services/${item.alias}`}>
              <GlassCard
                className="flex flex-col h-full group cursor-pointer overflow-hidden relative min-h-[350px]"
                aria-label={`View details for ${item.title}`}
              >
                <div className="absolute inset-0 bg-gradient-to-b from-transparent to-black/90 z-0"></div>
                {item.image && (
                  <div
                    className="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110 -z-10"
                    style={{ backgroundImage: `url(${item.image})` }}
                    role="img"
                    aria-label={item.title}
                  ></div>
                )}

                <div className="relative z-10 flex flex-col justify-end h-full p-6">
                  <h2 className="text-2xl font-bold text-white mb-3 leading-tight">{item.title}</h2>
                  <p className="text-white/80 text-sm line-clamp-4 leading-relaxed">
                    {stripHtml(item.content)}
                  </p>
                </div>
              </GlassCard>
            </Link>
          ))}
        </div>
      </AnimatedSection>
    </div>
  );
}
