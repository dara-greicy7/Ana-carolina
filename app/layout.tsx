import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import "./globals.css";

export const metadata: Metadata = {
  title: "Conative Time | Chief Experience Strategist",
  description:
    "Igniting people's transformation through travel and immersive experiences.",
};

// The CSP proxy (proxy.ts) issues a per-request nonce that Next.js can only
// inject during server-side rendering, so every page must render dynamically.
export const dynamic = "force-dynamic";

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en" className="dark scroll-smooth">
      <body className="min-h-screen bg-[#020617] text-slate-50 antialiased selection:bg-white/20">
        <main>{children}</main>

        <footer className="py-24 px-6 border-t border-white/5 bg-[#010409]">
          <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16">
            <div className="space-y-6">
              <Image
                src="/images/layout/logo.png"
                alt="Conative Time Logo"
                width={350}
                height={51}
                className="h-10 w-auto invert brightness-0 opacity-40 grayscale"
              />
              <p className="text-white/40 leading-relaxed">
                Taking customers on a discovery journey, enabling relationships that result in transformative experiences.
              </p>
            </div>

            <div className="space-y-6">
              <h4 className="font-bold text-white uppercase tracking-widest text-sm">Navigation</h4>
              <nav className="flex flex-col gap-4 text-white/60">
                <Link href="/#about" className="hover:text-white transition-colors">About Us</Link>
                <Link href="/#services" className="hover:text-white transition-colors">Services</Link>
                <Link href="/#portfolio" className="hover:text-white transition-colors">Portfolio</Link>
                <Link href="/blog" className="hover:text-white transition-colors">Insights (Blog)</Link>
                <a href="/files/Terms_and_Conditions.pdf" target="_blank" rel="noreferrer" className="hover:text-white transition-colors">Terms &amp; Conditions</a>
              </nav>
            </div>

            <div className="space-y-6">
              <h4 className="font-bold text-white uppercase tracking-widest text-sm">Contact</h4>
              <div className="flex flex-col gap-4 text-white/60">
                <a href="mailto:ana@conativetime.com" className="hover:text-white transition-colors">ana@conativetime.com</a>
                <a href="tel:617-682-7002" className="hover:text-white transition-colors">O: 617-682-7002</a>
                <a href="tel:978-728-7172" className="hover:text-white transition-colors">M: 978-728-7172</a>
                <div className="leading-relaxed">
                  40 Cambridge St.<br/>
                  Lawrence, MA 01843
                </div>
              </div>
            </div>
          </div>

          <div className="max-w-7xl mx-auto mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 text-xs text-white/40">
            <div className="space-y-2 max-w-3xl">
              <p>An Independent Affiliate of Travel Planners International, a member of the Signature Travel Network.</p>
              <p>FL Seller of Travel License ST17873 | CST# 2063964</p>
              <p>By using this site, you agree to our terms of service when booking travel through an Independent Affiliate of Travel Planners International.</p>
            </div>
            <div className="font-mono text-white/20 shrink-0 text-right">
              <p>© {new Date().getFullYear()} Conative Time all rights reserved</p>
              <p>
                Developed by{" "}
                <a
                  href="https://forgerdigital.com"
                  target="_blank"
                  rel="noreferrer"
                  className="hover:text-white transition-colors"
                >
                  Forger Digital — forgerdigital.com
                </a>
              </p>
            </div>
          </div>
        </footer>
      </body>
    </html>
  );
}
