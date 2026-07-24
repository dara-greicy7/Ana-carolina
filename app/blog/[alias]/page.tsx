import content from "@/data/content.json";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import { notFound } from "next/navigation";

export default function BlogPostDetail({ params }: { params: { alias: string } }) {
  const blogItem = content.blog.find((item: any) => item.alias === params.alias);

  if (!blogItem) {
    notFound();
  }

  return (
    <div className="pt-24 pb-16 min-h-screen">
      <AnimatedSection className="max-w-4xl mx-auto px-4">
        <GlassCard className="p-8 md:p-12">
          <div className="mb-8">
             {blogItem.image && (
                <div className="w-full h-[400px] rounded-2xl overflow-hidden mb-8 shadow-2xl">
                   <img 
                    src={blogItem.image} 
                    alt={blogItem.title} 
                    className="w-full h-full object-cover"
                   />
                </div>
             )}
            <h1 className="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
              {blogItem.title}
            </h1>
          </div>

          <div 
            className="text-white/80 prose prose-invert max-w-none prose-p:leading-relaxed prose-p:mb-6 prose-p:text-lg"
            dangerouslySetInnerHTML={{ __html: blogItem.content }}
          />
        </GlassCard>
      </AnimatedSection>
    </div>
  );
}

export async function generateStaticParams() {
  return content.blog.map((item: any) => ({
    alias: item.alias,
  }));
}
