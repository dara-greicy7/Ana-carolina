import content from "@/data/content.json";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import { notFound } from "next/navigation";

export default function ServiceDetail({ params }: { params: { alias: string } }) {
  const serviceItem = content.servicios.find((item: any) => item.alias === params.alias);

  if (!serviceItem) {
    notFound();
  }

  return (
    <div className="pt-24 pb-16 min-h-screen">
      <AnimatedSection className="max-w-4xl mx-auto px-4">
        <GlassCard className="p-8 md:p-12">
          <div className="mb-8">
             {serviceItem.image && (
                <div className="w-full h-[400px] rounded-2xl overflow-hidden mb-8 shadow-2xl">
                   <img 
                    src={serviceItem.image} 
                    alt={serviceItem.title} 
                    className="w-full h-full object-cover"
                   />
                </div>
             )}
            <h1 className="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
              {serviceItem.title}
            </h1>
          </div>

          <div 
            className="text-white/80 prose prose-invert max-w-none prose-p:leading-relaxed prose-p:mb-6 prose-p:text-lg"
            dangerouslySetInnerHTML={{ __html: serviceItem.content }}
          />
        </GlassCard>
      </AnimatedSection>
    </div>
  );
}

export async function generateStaticParams() {
  return content.servicios.map((item: any) => ({
    alias: item.alias,
  }));
}
