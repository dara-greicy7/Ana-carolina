import "./globals.css";
import type { Metadata } from "next";
import { Navbar } from "@/components/ui/navbar";


export const metadata: Metadata = {
  title: "Conative Time | Chief Experience Strategist",
  description: "Igniting people's transformation through travel and immersive experiences.",
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en" className="dark scroll-smooth">
      <body className="min-h-screen bg-[#020617] text-slate-50 antialiased selection:bg-white/20">
        <Navbar />
        <main>{children}</main>
        
        <footer className="py-24 px-6 border-t border-white/5 bg-[#010409]">
          <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16">
            <div className="space-y-6">
              <img 
                src="/images/layout/logo.png" 
                alt="Conative Time Logo" 
                className="h-10 w-auto invert brightness-0 opacity-40 grayscale" 
              />
              <p className="text-white/60 leading-relaxed">
                Taking customers on a discovery journey, enabling relationships that result in transformative experiences.
              </p>
            </div>
            
            <div className="space-y-6">
              <h2 className="font-bold text-white uppercase tracking-widest text-sm">Navigation</h2>
              <nav className="flex flex-col gap-4 text-white/60">
                <a href="#about" className="hover:text-white transition-colors">About Us</a>
                <a href="#services" className="hover:text-white transition-colors">Services</a>
                <a href="#portfolio" className="hover:text-white transition-colors">Portfolio</a>
                <a href="/blog" className="hover:text-white transition-colors">Insights (Blog)</a>
                <a href="/files/Terms_and_Conditions.pdf" target="_blank" className="hover:text-white transition-colors">Terms & Conditions</a>
                <a href="/photo-credits" className="hover:text-white transition-colors">Photo Credits</a>
              </nav>
            </div>

            <div className="space-y-6">
              <h2 className="font-bold text-white uppercase tracking-widest text-sm">Contact</h2>
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

            <div className="space-y-6">
              <h2 className="font-bold text-white uppercase tracking-widest text-sm">Social</h2>
              <div className="flex gap-4">
                <a href="#" aria-label="LinkedIn" className="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center transition-colors hover:bg-white hover:text-[#0A66C2] hover:border-white">
                  <svg aria-hidden="true" className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
                <a href="#" aria-label="X (formerly Twitter)" className="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center transition-colors hover:bg-black hover:text-white hover:border-white/40">
                  <svg aria-hidden="true" className="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                </a>
              </div>
            </div>
          </div>
          
          <div className="max-w-7xl mx-auto mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 text-xs text-white/60">
            <div className="space-y-2 max-w-3xl">
              <p>An Independent Affiliate of Travel Planners International, a member of the Signature Travel Network.</p>
              <p>FL Seller of Travel License ST17873 | CST# 2063964</p>
              <p>By using this site, you agree to our terms of service when booking travel through an Independent Affiliate of Travel Planners International.</p>
            </div>
            <div className="font-mono text-white/50 shrink-0 text-right">
              <p>© 2026 Conative Time all rights reserved</p>
              <p className="flex items-center justify-end gap-2">
                <span>Developed by</span>
                <a
                  href="https://forgerdigital.com"
                  target="_blank"
                  rel="noreferrer"
                  className="inline-flex items-center gap-2 hover:text-[#e63e00] transition-colors"
                >
                  {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img src="/images/layout/forger-digital-icon.webp" alt="" width={20} height={19} className="h-5 w-auto" />
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
