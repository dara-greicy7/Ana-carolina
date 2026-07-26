import type { Metadata } from "next";
import Image from "next/image";
import { notFound } from "next/navigation";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import { getService, services } from "@/lib/content";
import { sanitizeContent, stripHtml } from "@/lib/sanitize";

type ServicePageProps = {
  params: Promise<{ alias: string }>;
};

export function generateStaticParams() {
  return services.map((item) => ({ alias: item.alias }));
}

export async function generateMetadata({
  params,
}: ServicePageProps): Promise<Metadata> {
  const { alias } = await params;
  const service = getService(alias);

  if (!service) {
    return { title: "Service not found | Conative Time" };
  }

  return {
    title: `${service.title} | Conative Time`,
    description: stripHtml(service.content).slice(0, 160),
  };
}

export default async function ServiceDetail({ params }: ServicePageProps) {
  const { alias } = await params;
  const serviceItem = getService(alias);

  if (!serviceItem) {
    notFound();
  }

  return (
    <div className="pt-24 pb-16 min-h-screen">
      <AnimatedSection className="max-w-4xl mx-auto px-4">
        <GlassCard className="p-8 md:p-12">
          <div className="mb-8">
            {serviceItem.image && (
              <div className="relative w-full h-[400px] rounded-2xl overflow-hidden mb-8 shadow-2xl">
                <Image
                  src={serviceItem.image}
                  alt={serviceItem.title}
                  fill
                  sizes="(max-width: 896px) 100vw, 896px"
                  className="object-cover"
                  priority
                />
              </div>
            )}
            <h1 className="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
              {serviceItem.title}
            </h1>
          </div>

          <div
            className="text-white/80 prose prose-invert max-w-none prose-p:leading-relaxed prose-p:mb-6 prose-p:text-lg"
            dangerouslySetInnerHTML={{ __html: sanitizeContent(serviceItem.content) }}
          />
        </GlassCard>
      </AnimatedSection>
    </div>
  );
}
