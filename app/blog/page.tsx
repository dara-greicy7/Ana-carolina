import content from "@/data/content.json";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import Link from "next/link";

export default function BlogPage() {
  // @ts-ignore
  const blogItems = content.blog || [];

  return (
    <div className="pt-24 pb-16 flex flex-col items-center min-h-screen relative">
      <div className="fixed inset-0 bg-cover bg-center -z-20 opacity-20" style={{ backgroundImage: 'url(/images/layout/blog.jpg)' }}></div>
      <AnimatedSection>
        <h1 className="text-4xl md:text-6xl font-bold tracking-tight text-white mb-6">Our Blog</h1>
        <p className="text-lg text-white/80 max-w-2xl mb-12">
          Read our latest stories, insights, and adventures.
        </p>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {blogItems.map((item: any, idx: number) => (
            <Link key={idx} href={`/blog/${item.alias}`}>
              <GlassCard 
                className="flex flex-col h-full group cursor-pointer overflow-hidden relative"
                aria-label={`Read more about ${item.title}`}
              >
                <div className="absolute inset-0 bg-gradient-to-b from-transparent to-black/80 z-0"></div>
                {/* Using real extracted image paths */}
                <div 
                  className="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110 -z-10"
                  style={{ backgroundImage: `url(${item.image || 'https://images.unsplash.com/photo-1508739773434-c26b3d09e071?w=800&auto=format&fit=crop'})` }}
                  role="img"
                  aria-label={item.title}
                ></div>
                
                <div className="relative z-10 flex flex-col justify-end h-64 p-6">
                  <h2 className="text-2xl font-bold text-white mb-2 leading-tight">{item.title}</h2>
                  <div 
                    className="text-white/80 text-sm line-clamp-3 leading-relaxed"
                    dangerouslySetInnerHTML={{ __html: item.content }}
                  />
                </div>
              </GlassCard>
            </Link>
          ))}
        </div>
      </AnimatedSection>
    </div>
  );
}
