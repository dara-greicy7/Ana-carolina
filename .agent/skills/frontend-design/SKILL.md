---
name: frontend-design
description: 'Best practices for building modern, accessible, and performant frontend UIs. Use when building components, implementing layouts, choosing CSS approaches, or reviewing frontend code quality for web projects.'
license: MIT
metadata:
  author: wondelai
  version: "1.0.0"
  source: https://raw.githubusercontent.com/wondelai/skills/main/frontend-design/SKILL.md
---

Practical frontend design engineering principles for building high-quality web interfaces.

## Core Principle
Build systems, not one-offs. Constrained design tokens (spacing, color, typography) produce consistent, maintainable UIs faster than ad-hoc values.

## Component Architecture
- Keep components focused and single-responsibility
- Extract reusable primitives (Button, Card, Input) before building page-specific components
- Co-locate styles with components (CSS modules, Tailwind, or styled-components)
- Use composition over inheritance for component variants

## CSS & Styling
- Use utility-first CSS (Tailwind) for rapid, consistent development
- Define design tokens for: colors, spacing, typography, shadows, border-radius
- Mobile-first: write base styles for mobile, use min-width breakpoints to scale up
- Avoid magic numbers — use the spacing scale (4, 8, 16, 24, 32, 48, 64px)
- CSS custom properties for theming: `--color-primary`, `--space-4`, `--radius-md`

## Responsive Design
- Breakpoints: 375px (mobile) / 768px (tablet) / 1024px (desktop) / 1440px (wide)
- `min-h-dvh` over `100vh` on mobile (accounts for browser chrome)
- `max-w-prose` for readable text (≈65 characters)
- `max-w-6xl mx-auto` for page containers
- Never disable zoom (`user-scalable=no` is an accessibility violation)

## Performance
- Lazy load images below the fold (`loading="lazy"`)
- Use WebP/AVIF image formats with fallbacks
- Declare `width` and `height` on images to prevent layout shift (CLS)
- Avoid layout-triggering CSS properties in animations (use `transform`/`opacity` only)
- Code-split by route; dynamic imports for heavy components

## Accessibility
- Semantic HTML first: `<button>`, `<nav>`, `<main>`, `<article>`, `<section>`
- All interactive elements keyboard-accessible (visible focus styles)
- ARIA labels for icon-only buttons and complex widgets
- Color contrast minimum 4.5:1 for text, 3:1 for large text/UI components
- Never convey information through color alone (add icon + text)
- `prefers-reduced-motion`: wrap animations in media query

## Tailwind Patterns
```css
/* Spacing scale */
p-1 (4px) p-2 (8px) p-4 (16px) p-6 (24px) p-8 (32px) p-12 (48px) p-16 (64px)

/* Typography */
text-sm (14px) text-base (16px) text-lg (18px) text-xl (20px) text-2xl (24px)
font-normal (400) font-medium (500) font-semibold (600) font-bold (700)

/* Layout */
max-w-prose max-w-md max-w-lg max-w-xl max-w-2xl max-w-4xl max-w-6xl

/* Dark mode */
dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700
```

## Common Mistakes
| Mistake | Fix |
|---------|-----|
| Inline styles everywhere | Use design tokens / utility classes |
| Magic px values (13, 17, 23) | Use spacing scale only |
| No dark mode | Implement from the start, not as an afterthought |
| Images without dimensions | Always set width/height to prevent CLS |
| No focus styles | Never `outline: none` without replacement |
| Hover-only interactions | Always provide tap/click equivalent |
| Non-semantic HTML | Use proper elements (button, not div with onClick) |
