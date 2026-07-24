import content from "@/data/content.json";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import { Button } from "@/components/ui/button";

export default function ContactPage() {
  // @ts-ignore
  const contactInfo = (content.secciones?.find(s => s.id === "8") as any) || {};

  return (
    <div className="pt-24 pb-16 flex flex-col items-center min-h-screen relative">
      <div className="fixed inset-0 bg-cover bg-center -z-20 opacity-30" style={{ backgroundImage: 'url(/images/layout/contatos-bg.jpg)' }}></div>
      <AnimatedSection className="w-full max-w-4xl px-4">
        <h1 className="text-4xl md:text-6xl font-bold tracking-tight text-white mb-6 text-center">Contact Us</h1>
        <p className="text-lg text-white/80 max-w-2xl mb-12 text-center mx-auto">
          We'd love to hear from you. Whether you have a question about our services, pricing, or anything else, our team is ready to answer all your questions.
        </p>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
          <GlassCard className="p-8">
            <h2 className="text-2xl font-bold text-white mb-6">Get in Touch</h2>
            <div 
              className="text-white/80 prose prose-invert mb-8"
              dangerouslySetInnerHTML={{ __html: contactInfo.content || "" }}
            />
            
            <div className="space-y-4">
              <div className="flex items-center gap-4 text-white/80">
                <div className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <span>ana@conativetime.com</span>
              </div>
              <div className="flex items-center gap-4 text-white/80">
                <div className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <div className="flex flex-col">
                  <span>O: 617-682-7002</span>
                  <span>M: 978-728-7172</span>
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
            <form className="space-y-6">
              <div>
                <label className="block text-sm font-medium text-white/70 mb-2">Name</label>
                <input type="text" className="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-white/20 transition-all" placeholder="Your name" />
              </div>
              <div>
                <label className="block text-sm font-medium text-white/70 mb-2">Email</label>
                <input type="email" className="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-white/20 transition-all" placeholder="your@email.com" />
              </div>
              <div>
                <label className="block text-sm font-medium text-white/70 mb-2">Message</label>
                <textarea rows={4} className="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-white/20 transition-all" placeholder="How can we help?"></textarea>
              </div>
              <Button className="w-full py-6 text-lg">Send Message</Button>
            </form>
          </GlassCard>
        </div>
      </AnimatedSection>
    </div>
  );
}
