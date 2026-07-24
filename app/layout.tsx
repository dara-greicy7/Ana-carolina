import "./globals.css";
import type { Metadata } from "next";


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
        <main>{children}</main>
        
        <footer className="py-24 px-6 border-t border-white/5 bg-[#010409]">
          <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16">
            <div className="space-y-6">
              <img 
                src="/images/layout/logo.png" 
                alt="Conative Time Logo" 
                className="h-10 w-auto invert brightness-0 opacity-40 grayscale" 
              />
              <p className="text-white/40 leading-relaxed">
                Taking customers on a discovery journey, enabling relationships that result in transformative experiences.
              </p>
            </div>
            
            <div className="space-y-6">
              <h4 className="font-bold text-white uppercase tracking-widest text-sm">Navigation</h4>
              <nav className="flex flex-col gap-4 text-white/60">
                <a href="#about" className="hover:text-white transition-colors">About Us</a>
                <a href="#services" className="hover:text-white transition-colors">Services</a>
                <a href="#portfolio" className="hover:text-white transition-colors">Portfolio</a>
                <a href="/blog" className="hover:text-white transition-colors">Insights (Blog)</a>
                <a href="/files/Terms_and_Conditions.pdf" target="_blank" className="hover:text-white transition-colors">Terms & Conditions</a>
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

            <div className="space-y-6">
              <h4 className="font-bold text-white uppercase tracking-widest text-sm">Social</h4>
              <div className="flex gap-4">
                <a href="#" className="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-colors">
                  <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
                <a href="#" className="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-colors">
                  <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                </a>
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
              <p>© 2026 Conative Time all rights reserved</p>
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
