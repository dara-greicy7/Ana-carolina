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
  heroImageUrls?: string[];
};

export function HomePageClient({ initialHeroImageUrl, heroImageUrls }: HomePageClientProps) {
  const navLinks = [
    { label: "About", href: "#about" },
    { label: "Services", href: "#services" },
    { label: "Skills", href: "#skills" },
    { label: "Portfolio", href: "#portfolio" },
    { label: "Testimonials", href: "#testimonials" },
    { label: "Contact", href: "#contact" },
  ];

  const handleCtaClick = () => {
    document.getElementById("services")?.scrollIntoView({ behavior: "smooth" });
  };

  const handleSecondaryCtaClick = () => {
    document.getElementById("contact")?.scrollIntoView({ behavior: "smooth" });
  };

  const logo = (
    <div className="flex items-center">
      <img
        src="/images/layout/logo.png"
        alt="Conative Time Logo"
        className="h-8 md:h-10 w-auto invert brightness-0"
      />
    </div>
  );

  return (
    <div className="flex flex-col">
      <Navbar logo={logo} links={navLinks} />

      <AnimatedHero
        backgroundImageUrl={initialHeroImageUrl}
        backgroundImageUrls={heroImageUrls}
        logo={logo}
        navLinks={navLinks}
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
