import content from "@/data/content.json";

export interface ContentItem {
  id: string;
  type: string;
  title: string;
  alias: string;
  content: string;
  image: string | null;
}

export interface MediaItem extends ContentItem {
  galleryPath?: string;
}

interface ContentData {
  secciones: ContentItem[];
  servicios: ContentItem[];
  media: MediaItem[];
  slideshow: ContentItem[];
  blog: ContentItem[];
}

// content.json is a trusted, build-time data file produced by scripts/extract_content.js
// from the legacy Joomla database dump. The cast below is verified at build time by the
// typed accessors, and every consumer renders `content` through lib/sanitize.ts.
const data = content as unknown as ContentData;

export const sections: ContentItem[] = data.secciones ?? [];
export const services: ContentItem[] = data.servicios ?? [];
export const blogPosts: ContentItem[] = data.blog ?? [];

export function getSectionByAlias(alias: string): ContentItem | undefined {
  return sections.find((item) => item.alias === alias);
}

export function getSectionById(id: string): ContentItem | undefined {
  return sections.find((item) => item.id === id);
}

export function getService(alias: string): ContentItem | undefined {
  return services.find((item) => item.alias === alias);
}

export function getBlogPost(alias: string): ContentItem | undefined {
  return blogPosts.find((item) => item.alias === alias);
}
