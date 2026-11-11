"use client";

import { useEffect } from "react";
import { PlanetHero } from "@/components/sections/planet-hero";

import { AboutPreview } from "@/components/sections/about-preview";
import { ServicesPreview } from "@/components/sections/services-preview";
import { GalleryPreview } from "@/components/sections/gallery-preview";
import { SkillsSection } from "@/components/sections/skills-section";
import { TestimonialsSection } from "@/components/sections/testimonials-section";
import { ContactSection } from "@/components/sections/contact-section";

export function HomePageClient() {
  // Arriving from another page via "/#section": the root has scroll-behavior:
  // smooth, so the browser animates the jump across a very tall page and keeps
  // getting interrupted. Jump instantly once the page is interactive instead.
  // The browser's own pending hash scroll can land after ours, so re-apply while the
  // page settles, and stop the moment the visitor scrolls or types.
  useEffect(() => {
    const id = decodeURIComponent(window.location.hash.slice(1));
    const target = id ? document.getElementById(id) : null;
    if (!target) return;
    const jump = () => target.scrollIntoView({ behavior: "instant", block: "start" });
    const timers = [0, 250, 800, 1600].map((ms) => window.setTimeout(jump, ms));
    const stop = () => timers.forEach(clearTimeout);
    const opts = { once: true, passive: true } as const;
    window.addEventListener("wheel", stop, opts);
    window.addEventListener("touchstart", stop, opts);
    window.addEventListener("keydown", stop, opts);
    return () => {
      stop();
      window.removeEventListener("wheel", stop);
      window.removeEventListener("touchstart", stop);
      window.removeEventListener("keydown", stop);
    };
  }, []);

  const handleCtaClick = () => {
    document.getElementById("services")?.scrollIntoView({ behavior: "smooth" });
  };

  const handleSecondaryCtaClick = () => {
    document.getElementById("contact")?.scrollIntoView({ behavior: "smooth" });
  };

  return (
    <div className="flex flex-col">
      <PlanetHero
        title="The Joy Of Travel"
        subtitle="Igniting people's transformation"
        description="Take our customers on a discovery journey, nurturing their special interests and enabling relationships that result in transformative experiences."
        primaryCta={{ text: "Discover Services", onClick: handleCtaClick }}
        secondaryCta={{ text: "Plan Your Journey", onClick: handleSecondaryCtaClick }}
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
