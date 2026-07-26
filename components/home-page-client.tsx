"use client";

import { AnimatedHero } from "@/components/ui/animated-hero-section-1";
import { Navbar } from "@/components/ui/navbar";

import { AboutPreview } from "@/components/sections/about-preview";
import { ServicesPreview } from "@/components/sections/services-preview";
import { GalleryPreview } from "@/components/sections/gallery-preview";
import { SkillsSection } from "@/components/sections/skills-section";
import { TestimonialsSection } from "@/components/sections/testimonials-section";
import { ContactSection } from "@/components/sections/contact-section";

type HomePageClientProps = {
  initialHeroImageUrl: string;
};

const navLinks = [
  { label: "About", href: "#about" },
  { label: "Services", href: "#services" },
  { label: "Skills", href: "#skills" },
  { label: "Portfolio", href: "#portfolio" },
  { label: "Testimonials", href: "#testimonials" },
  { label: "Contact", href: "#contact" },
];

export function HomePageClient({ initialHeroImageUrl }: HomePageClientProps) {
  const handleCtaClick = () => {
    document.getElementById("services")?.scrollIntoView({ behavior: "smooth" });
  };

  const handleSecondaryCtaClick = () => {
    document.getElementById("contact")?.scrollIntoView({ behavior: "smooth" });
  };

  return (
    <div className="flex flex-col">
      <Navbar links={navLinks} />

      <AnimatedHero
        backgroundImageUrl={initialHeroImageUrl}
        title="The Joy Of Travel"
        subtitle="Igniting people's transformation"
        description="Take our customers on a discovery journey, nurturing their special interests and enabling relationships that result in transformative experiences."
        ctaButton={{
          text: "Discover Services",
          onClick: handleCtaClick,
        }}
        secondaryCta={{
          text: "Plan Your Journey",
          onClick: handleSecondaryCtaClick,
        }}
      />

      <AboutPreview />
      <ServicesPreview />
      <SkillsSection />
      <GalleryPreview />
      <TestimonialsSection />
      <ContactSection />
    </div>
  );
}
