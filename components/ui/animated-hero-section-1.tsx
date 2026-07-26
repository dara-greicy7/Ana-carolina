"use client";

import * as React from "react";
import { AnomalousMatter } from "@/components/ui/anomalous-matter";
import { motion } from "framer-motion";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";

interface AnimatedHeroProps {
  backgroundImageUrl: string;
  title: string;
  subtitle?: string;
  description: string;
  ctaButton: {
    text: string;
    onClick: () => void;
  };
  secondaryCta?: {
    text: string;
    onClick: () => void;
  };
  className?: string;
}

const containerVariants = {
  hidden: { opacity: 0 },
  visible: {
    opacity: 1,
    transition: {
      staggerChildren: 0.15,
      delayChildren: 0.2,
    },
  },
};

const itemVariants = {
  hidden: { y: 20, opacity: 0 },
  visible: {
    y: 0,
    opacity: 1,
    transition: {
      duration: 0.6,
      ease: "easeOut" as const,
    },
  },
};

// Matches the server-side rotation schedule in lib/hero-image-service.ts.
const HERO_REFRESH_INTERVAL_MS = 30 * 60 * 1000;

export const AnimatedHero = ({
  backgroundImageUrl,
  title,
  subtitle,
  description,
  ctaButton,
  secondaryCta,
  className,
}: AnimatedHeroProps) => {
  const [resolvedBackgroundImageUrl, setResolvedBackgroundImageUrl] =
    React.useState(backgroundImageUrl);

  // Sync prop changes during render (React's recommended alternative to
  // setState-in-effect for derived state).
  const [previousUrl, setPreviousUrl] = React.useState(backgroundImageUrl);
  if (previousUrl !== backgroundImageUrl) {
    setPreviousUrl(backgroundImageUrl);
    setResolvedBackgroundImageUrl(backgroundImageUrl);
  }

  React.useEffect(() => {
    const controller = new AbortController();

    // Polls the current hero image; rotation itself happens server-side on a
    // fixed schedule, so clients never trigger advances.
    const refreshHeroImage = async () => {
      try {
        const response = await fetch("/api/hero-image", {
          cache: "no-store",
          signal: controller.signal,
        });

        if (!response.ok) {
          return;
        }

        const payload: unknown = await response.json();
        if (
          typeof payload === "object" &&
          payload !== null &&
          "imageUrl" in payload &&
          typeof (payload as { imageUrl: unknown }).imageUrl === "string" &&
          (payload as { imageUrl: string }).imageUrl.length > 0
        ) {
          setResolvedBackgroundImageUrl((payload as { imageUrl: string }).imageUrl);
        }
      } catch {
        // Keep the current hero background if the service is temporarily unavailable.
      }
    };

    const intervalId = window.setInterval(() => {
      void refreshHeroImage();
    }, HERO_REFRESH_INTERVAL_MS);

    return () => {
      controller.abort();
      window.clearInterval(intervalId);
    };
  }, []);

  const glassButtonClassName =
    "bg-white/10 backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 transition-colors";

  return (
    <div
      className={cn(
        "relative flex min-h-screen w-full flex-col items-center justify-center overflow-hidden bg-background",
        className
      )}
    >
      <div
        className="absolute inset-0 z-0 bg-cover bg-center"
        style={{ backgroundImage: `url(${resolvedBackgroundImageUrl})` }}
      />

      <AnomalousMatter />

      <motion.div
        variants={containerVariants}
        initial="hidden"
        animate="visible"
        className="relative z-10 flex flex-col items-start justify-center text-left px-6 md:px-12 max-w-4xl w-full text-white"
      >
        <motion.h1
          variants={itemVariants}
          className="text-5xl font-extrabold tracking-tight text-white sm:text-6xl md:text-7xl lg:text-8xl"
        >
          {title}
        </motion.h1>
        {subtitle && (
          <motion.h2
            variants={itemVariants}
            className="mt-4 text-3xl font-bold tracking-tight text-white/90 sm:text-4xl"
          >
            {subtitle}
          </motion.h2>
        )}
        <motion.p
          variants={itemVariants}
          className="mt-6 max-w-2xl text-xl font-medium leading-9 text-white/80"
        >
          {description}
        </motion.p>
        <motion.div
          variants={itemVariants}
          className="mt-10 flex items-center gap-x-4"
        >
          <Button
            onClick={ctaButton.onClick}
            size="lg"
            className={`${glassButtonClassName} text-base font-semibold tracking-wide`}
          >
            {ctaButton.text}
          </Button>
          {secondaryCta && (
            <Button
              onClick={secondaryCta.onClick}
              size="lg"
              className={`${glassButtonClassName} text-base font-semibold tracking-wide`}
            >
              {secondaryCta.text}
            </Button>
          )}
        </motion.div>
      </motion.div>
    </div>
  );
};
