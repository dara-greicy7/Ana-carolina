import type { Metadata } from "next";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import { ContactForm } from "@/components/sections/contact-form";
import { getSectionById } from "@/lib/content";
import { sanitizeContent } from "@/lib/sanitize";

export const metadata: Metadata = {
  title: "Contact Us | Conative Time",
  description:
    "Get in touch with Conative Time to plan your next immersive travel or event experience.",
};

export default function ContactPage() {
  const contactInfo = getSectionById("8");

  return (
    <div className="pt-24 pb-16 flex flex-col items-center min-h-screen relative">
      <div className="fixed inset-0 bg-cover bg-center -z-20 opacity-30" style={{ backgroundImage: 'url(/images/layout/contatos-bg.jpg)' }}></div>
      <AnimatedSection className="w-full max-w-4xl px-4">
        <h1 className="text-4xl md:text-6xl font-bold tracking-tight text-white mb-6 text-center">Contact Us</h1>
        <p className="text-lg text-white/80 max-w-2xl mb-12 text-center mx-auto">
          We&apos;d love to hear from you. Whether you have a question about our services, pricing, or anything else, our team is ready to answer all your questions.
        </p>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
          <GlassCard className="p-8">
            <h2 className="text-2xl font-bold text-white mb-6">Get in Touch</h2>
            {contactInfo && (
              <div
                className="text-white/80 prose prose-invert mb-8"
                dangerouslySetInnerHTML={{ __html: sanitizeContent(contactInfo.content) }}
              />
            )}

            <div className="space-y-4">
              <div className="flex items-center gap-4 text-white/80">
                <div className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <a href="mailto:ana@conativetime.com" className="hover:text-white transition-colors">ana@conativetime.com</a>
              </div>
              <div className="flex items-center gap-4 text-white/80">
                <div className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <div className="flex flex-col">
                  <a href="tel:617-682-7002" className="hover:text-white transition-colors">O: 617-682-7002</a>
                  <a href="tel:978-728-7172" className="hover:text-white transition-colors">M: 978-728-7172</a>
                </div>
              </div>
              <div className="flex items-center gap-4 text-white/80">
                <div className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <span>40 Cambridge St.<br/>Lawrence, MA 01843</span>
              </div>
            </div>
          </GlassCard>

          <GlassCard className="p-8">
            <ContactForm />
          </GlassCard>
        </div>
      </AnimatedSection>
    </div>
  );
}
