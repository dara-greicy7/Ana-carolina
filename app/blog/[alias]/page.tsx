import type { Metadata } from "next";
import Image from "next/image";
import { notFound } from "next/navigation";
import { AnimatedSection } from "@/components/ui/animated-section";
import { GlassCard } from "@/components/ui/glass-card";
import { blogPosts, getBlogPost } from "@/lib/content";
import { sanitizeContent, stripHtml } from "@/lib/sanitize";

type BlogPostPageProps = {
  params: Promise<{ alias: string }>;
};

export function generateStaticParams() {
  return blogPosts.map((item) => ({ alias: item.alias }));
}

export async function generateMetadata({
  params,
}: BlogPostPageProps): Promise<Metadata> {
  const { alias } = await params;
  const post = getBlogPost(alias);

  if (!post) {
    return { title: "Post not found | Conative Time" };
  }

  return {
    title: `${post.title} | Conative Time`,
    description: stripHtml(post.content).slice(0, 160),
  };
}

export default async function BlogPostDetail({ params }: BlogPostPageProps) {
  const { alias } = await params;
  const blogItem = getBlogPost(alias);

  if (!blogItem) {
    notFound();
  }

  return (
    <div className="pt-24 pb-16 min-h-screen">
      <AnimatedSection className="max-w-4xl mx-auto px-4">
        <GlassCard className="p-8 md:p-12">
          <div className="mb-8">
            {blogItem.image && (
              <div className="relative w-full h-[400px] rounded-2xl overflow-hidden mb-8 shadow-2xl">
                <Image
                  src={blogItem.image}
                  alt={blogItem.title}
                  fill
                  sizes="(max-width: 896px) 100vw, 896px"
                  className="object-cover"
                  priority
                />
              </div>
            )}
            <h1 className="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
              {blogItem.title}
            </h1>
          </div>

          <article lang="es">
            <div
              className="text-white/80 prose prose-invert max-w-none prose-p:leading-relaxed prose-p:mb-6 prose-p:text-lg"
              dangerouslySetInnerHTML={{ __html: sanitizeContent(blogItem.content) }}
            />
          </article>
        </GlassCard>
      </AnimatedSection>
    </div>
  );
}
