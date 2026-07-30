"use client";

import * as React from "react";
import { motion, useScroll, useTransform } from "framer-motion";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";

interface NavLink {
  label: string;
  href: string;
}

interface NavbarProps {
  logo: React.ReactNode;
  links: NavLink[];
}

export function Navbar({ logo, links }: NavbarProps) {
  const { scrollY } = useScroll();
  const backgroundColor = useTransform(
    scrollY,
    [0, 100],
    ["rgba(0, 0, 0, 0)", "rgba(0, 0, 0, 0.8)"]
  );
  const backdropBlur = useTransform(
    scrollY,
    [0, 100],
    ["blur(0px)", "blur(12px)"]
  );
  const borderOpacity = useTransform(
    scrollY,
    [0, 100],
    ["rgba(255, 255, 255, 0)", "rgba(255, 255, 255, 0.1)"]
  );

  return (
    <motion.header
      style={{
        backgroundColor,
        backdropFilter: backdropBlur,
        borderColor: borderOpacity,
      }}
      className="fixed top-0 left-0 right-0 z-50 h-20 flex items-center justify-between px-6 md:px-12 border-b transition-all duration-300"
    >
      <div className="flex items-center gap-2">{logo}</div>
      {/* Glass pill keeps the links legible over any hero image or section color */}
      <nav className="hidden md:flex items-center gap-8 rounded-full bg-black/35 backdrop-blur-md border border-white/15 px-7 py-2.5 shadow-[0_2px_12px_rgba(0,0,0,0.25)]">
        {links.map((link) => (
          <a
            key={link.label}
            href={link.href}
            onClick={(e) => {
               if (link.href.startsWith("#")) {
                 e.preventDefault();
                 document.querySelector(link.href)?.scrollIntoView({ behavior: 'smooth' });
               }
            }}
            className="text-base font-semibold tracking-wide text-white/90 transition-colors hover:text-white [text-shadow:0_1px_2px_rgba(0,0,0,0.6)]"
          >
            {link.label}
          </a>
        ))}
      </nav>
      <div className="flex items-center gap-4">
        <Button 
          onClick={() => document.getElementById('contact')?.scrollIntoView({ behavior: 'smooth' })}
        className="bg-white/10 backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 hidden md:flex text-sm font-semibold tracking-wide"
      >
          Consultation
        </Button>
        {/* Mobile menu icon could go here */}
      </div>
    </motion.header>
  );
}
