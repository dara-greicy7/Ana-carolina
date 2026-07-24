 "use client";

import * as React from "react";
import { AnomalousMatter } from "@/components/ui/anomalous-matter";
import { motion } from "framer-motion";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";

// Interface definitions remain the same
interface NavLink {
  label: string;
  href: string;
}

interface AnimatedHeroProps {
  backgroundImageUrl: string;
  logo: React.ReactNode;
  navLinks: NavLink[];
  topRightAction?: React.ReactNode;
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

// Animation variants remain the same
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
      ease: "easeOut",
    },
  },
};

export const AnimatedHero = ({
  backgroundImageUrl,
  logo,
  navLinks,
  topRightAction,
  title,
  subtitle,
  description,
  ctaButton,
  secondaryCta,
  className,
}: AnimatedHeroProps) => {
  const [resolvedBackgroundImageUrl, setResolvedBackgroundImageUrl] = React.useState(backgroundImageUrl);

  React.useEffect(() => {
    setResolvedBackgroundImageUrl(backgroundImageUrl);
  }, [backgroundImageUrl]);

  React.useEffect(() => {
    const controller = new AbortController();

    const loadHeroImage = async (advance: boolean) => {
      try {
        const cacheBuster = `ts=${Date.now()}`;
        const response = await fetch(
          `/api/hero-image${advance ? "?advance=1&" : "?"}${cacheBuster}`,
          {
            cache: "no-store",
            signal: controller.signal,
          }
        );

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
        // Keep the current hero background if the microservice is temporarily unavailable.
      }
    };

    const intervalId = window.setInterval(() => {
      void loadHeroImage(true);
    }, 30 * 60 * 1000);

    return () => {
      controller.abort();
      window.clearInterval(intervalId);
    };
  }, []);

  // Define the new reusable glass button style
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
          {/* UPDATED: Applied the new glass button style */}
          <Button
            onClick={ctaButton.onClick}
            size="lg"
            className={`${glassButtonClassName} text-base font-semibold tracking-wide`}
          >
            {ctaButton.text}
          </Button>
          {/* UPDATED: Applied the new glass button style */}
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
