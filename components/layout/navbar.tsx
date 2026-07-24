import Link from "next/link";
import { MountainIcon } from "lucide-react";
import { Button } from "@/components/ui/button";

export function Navbar() {
  const navLinks = [
    { label: "Home", href: "/" },
    { label: "About", href: "/about" },
    { label: "Services", href: "/services" },
    { label: "Locations", href: "/locations" },
    { label: "Blog", href: "/blog" },
  ];

  return (
    <header className="fixed top-0 z-50 flex h-20 w-full items-center justify-between px-6 md:px-12 text-white bg-black/20 backdrop-blur-md border-b border-white/10">
      <Link href="/" className="flex items-center gap-2">
        <MountainIcon className="h-6 w-6 text-primary-foreground" />
        <span className="font-semibold text-primary-foreground">Conative Time</span>
      </Link>
      
      <nav className="hidden md:flex items-center gap-6">
        {navLinks.map((link) => (
          <Link
            key={link.label}
            href={link.href}
            className="text-sm font-medium text-primary-foreground/80 transition-colors hover:text-primary-foreground"
          >
            {link.label}
          </Link>
        ))}
      </nav>
      
      <div className="hidden md:block">
        <Button className="bg-white/10 backdrop-blur-sm border border-white/20 text-primary-foreground hover:bg-white/20 transition-colors">
          Contact Us
        </Button>
      </div>
    </header>
  );
}
