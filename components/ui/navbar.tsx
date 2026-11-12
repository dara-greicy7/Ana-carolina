"use client";

import * as React from "react";
import { usePathname } from "next/navigation";
import { motion, useScroll, useTransform } from "framer-motion";
import { Menu, X } from "lucide-react";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import { DayNightToggle } from "@/components/ui/day-night-toggle";

// Section anchors live on the homepage. Linking to "/#id" lets every page use the
// same menu: on the homepage the click smooth-scrolls, elsewhere it navigates home.
const links = [
  { label: "Destinations", id: "destinations" },
  { label: "About", id: "about" },
  { label: "Services", id: "services" },
  { label: "Skills", id: "skills" },
  { label: "Portfolio", id: "portfolio" },
  { label: "Testimonials", id: "testimonials" },
  { label: "Contact", id: "contact" },
];

export function Navbar() {
  const pathname = usePathname();
  const [open, setOpen] = React.useState(false);
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

  React.useEffect(() => setOpen(false), [pathname]);

  React.useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => e.key === "Escape" && setOpen(false);
    const desktop = window.matchMedia("(min-width: 768px)");
    const onResize = () => desktop.matches && setOpen(false);
    window.addEventListener("keydown", onKey);
    desktop.addEventListener("change", onResize);
    return () => {
      window.removeEventListener("keydown", onKey);
      desktop.removeEventListener("change", onResize);
    };
  }, [open]);

  const goTo = (id: string) => (e: React.MouseEvent) => {
    setOpen(false);
    const target = pathname === "/" ? document.getElementById(id) : null;
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: "smooth" });
      history.replaceState(null, "", `#${id}`);
    }
  };

  return (
    <motion.header
      style={{
        backgroundColor: open ? "rgb(2, 6, 23)" : backgroundColor,
        backdropFilter: backdropBlur,
        borderColor: borderOpacity,
      }}
      className="fixed top-0 left-0 right-0 z-50 border-b transition-colors duration-300"
    >
      <div className="h-20 flex items-center justify-between px-6 md:px-12">
        <a href="/" aria-label="Conative Time home" className="flex items-center">
          <img
            src="/images/layout/logo.png"
            alt="Conative Time Logo"
            className="h-8 md:h-10 w-auto invert brightness-0"
          />
        </a>
        {/* Glass pill keeps the links legible over any hero image or section color */}
        <nav
          aria-label="Main"
          className="hidden md:flex items-center gap-8 rounded-full bg-black/35 backdrop-blur-md border border-white/15 px-7 py-2.5 shadow-[0_2px_12px_rgba(0,0,0,0.25)]"
        >
          {links.map((link) => (
            <a
              key={link.id}
              href={`/#${link.id}`}
              onClick={goTo(link.id)}
              className="text-xs tracking-[0.2em] text-white/70 transition-colors hover:text-white [text-shadow:0_1px_2px_rgba(0,0,0,0.6)]"
            >
              {link.label}
            </a>
          ))}
        </nav>
        <div className="flex items-center gap-2 md:gap-3">
          {/* Day/night only changes the home page hero backgrounds. */}
          {pathname === "/" && <DayNightToggle />}
          <Button
            asChild
            className="bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 hidden md:flex text-xs font-normal tracking-[0.2em] text-white/70 hover:text-white"
          >
            <a href="/#contact" onClick={goTo("contact")}>
              Consultation
            </a>
          </Button>
          <button
            type="button"
            onClick={() => setOpen((v) => !v)}
            aria-expanded={open}
            aria-controls="mobile-menu"
            aria-label={open ? "Close menu" : "Open menu"}
            className="md:hidden inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-black/35 text-white backdrop-blur-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-300"
          >
            {open ? <X className="h-5 w-5" aria-hidden /> : <Menu className="h-5 w-5" aria-hidden />}
          </button>
        </div>
      </div>

      <nav
        id="mobile-menu"
        aria-label="Main"
        hidden={!open}
        className={cn("md:hidden border-t border-white/10 px-6 pb-8 pt-4")}
      >
        <ul className="flex flex-col">
          {links.map((link) => (
            <li key={link.id}>
              <a
                href={`/#${link.id}`}
                onClick={goTo(link.id)}
                className="block py-3 text-lg font-semibold text-white/90 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-sky-300 rounded"
              >
                {link.label}
              </a>
            </li>
          ))}
        </ul>
        <a
          href="/#contact"
          onClick={goTo("contact")}
          className="mt-4 flex h-12 items-center justify-center rounded-full bg-white text-sm font-semibold text-slate-900 hover:bg-sky-100"
        >
          Consultation
        </a>
      </nav>
    </motion.header>
  );
}
