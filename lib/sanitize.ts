import sanitizeHtml from "sanitize-html";

/**
 * Sanitization policy for HTML extracted from the legacy Joomla CMS
 * (data/content.json). The content is rendered with dangerouslySetInnerHTML,
 * so it must pass through this whitelist first: formatting tags only,
 * http(s)/mailto/tel links, and same-origin or https images. Scripts, event
 * handlers, iframes, forms and styles are always stripped.
 */
const sanitizeOptions: sanitizeHtml.IOptions = {
  allowedTags: [
    "p",
    "br",
    "strong",
    "b",
    "em",
    "i",
    "u",
    "a",
    "ul",
    "ol",
    "li",
    "h2",
    "h3",
    "h4",
    "blockquote",
    "img",
    "figure",
    "figcaption",
  ],
  allowedAttributes: {
    a: ["href", "title", "target", "rel"],
    img: ["src", "alt", "title", "width", "height", "loading"],
  },
  allowedSchemes: ["http", "https", "mailto", "tel"],
  allowedSchemesByTag: {
    img: ["http", "https"],
  },
  allowProtocolRelative: false,
  transformTags: {
    a: sanitizeHtml.simpleTransform("a", { rel: "noopener noreferrer" }),
  },
};

export function sanitizeContent(html: string | null | undefined): string {
  if (!html) {
    return "";
  }
  return sanitizeHtml(html, sanitizeOptions);
}

/** Plain-text version of CMS HTML, for previews and meta descriptions. */
export function stripHtml(html: string | null | undefined): string {
  if (!html) {
    return "";
  }
  return sanitizeHtml(html, { allowedTags: [], allowedAttributes: {} }).trim();
}
