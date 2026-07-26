import type { Metadata } from "next";
import Link from "next/link";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import { blogPosts } from "@/lib/content";
import { stripHtml } from "@/lib/sanitize";

export const metadata: Metadata = {
  title: "Blog | Conative Time",
  description: "Read our latest stories, insights, and adventures.",
};

export default function BlogPage() {
  return (
    <div className="pt-24 pb-16 flex flex-col items-center min-h-screen relative">
      <div className="fixed inset-0 bg-cover bg-center -z-20 opacity-20" style={{ backgroundImage: 'url(/images/layout/blog.jpg)' }}></div>
      <AnimatedSection>
        <h1 className="text-4xl md:text-6xl font-bold tracking-tight text-white mb-6">Our Blog</h1>
        <p className="text-lg text-white/80 max-w-2xl mb-12">
          Read our latest stories, insights, and adventures.
        </p>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {blogPosts.map((item) => (
            <Link key={item.alias} href={`/blog/${item.alias}`}>
              <GlassCard
                className="flex flex-col h-full group cursor-pointer overflow-hidden relative"
                aria-label={`Read more about ${item.title}`}
              >
                <div className="absolute inset-0 bg-gradient-to-b from-transparent to-black/80 z-0"></div>
                {item.image && (
                  <div
                    className="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110 -z-10"
                    style={{ backgroundImage: `url(${item.image})` }}
                    role="img"
                    aria-label={item.title}
                  ></div>
                )}

                <div className="relative z-10 flex flex-col justify-end h-64 p-6">
                  <h2 className="text-2xl font-bold text-white mb-2 leading-tight">{item.title}</h2>
                  <p className="text-white/80 text-sm line-clamp-3 leading-relaxed">
                    {stripHtml(item.content)}
                  </p>
                </div>
              </GlassCard>
            </Link>
          ))}
        </div>
      </AnimatedSection>
    </div>
  );
}
