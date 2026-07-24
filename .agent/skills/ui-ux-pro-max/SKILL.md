---
name: ui-ux-pro-max
description: "UI/UX design intelligence for web and mobile. Includes 50+ styles, 161 color palettes, 57 font pairings, 161 product types, 99 UX guidelines, and 25 chart types across 10 stacks (React, Next.js, Vue, Svelte, SwiftUI, React Native, Flutter, Tailwind, shadcn/ui, and HTML/CSS). Actions: plan, build, create, design, implement, review, fix, improve, optimize, enhance, refactor, and check UI/UX code. Projects: website, landing page, dashboard, admin panel, e-commerce, SaaS, portfolio, blog, and mobile app. Elements: button, modal, navbar, sidebar, card, table, form, and chart. Styles: glassmorphism, claymorphism, minimalism, brutalism, neumorphism, bento grid, dark mode, responsive, skeuomorphism, and flat design. Topics: color systems, accessibility, animation, layout, typography, font pairing, spacing, interaction states, shadow, and gradient. Integrations: shadcn/ui MCP for component search and examples."
source: https://github.com/nextlevelbuilder/ui-ux-pro-max-skill
license: MIT
metadata:
  author: nextlevelbuilder
  version: "latest"
---

Comprehensive design guide for web and mobile applications. Contains 50+ styles, 161 color palettes, 57 font pairings, 161 product types with reasoning rules, 99 UX guidelines, and 25 chart types across 10 technology stacks.

### Must Use
Invoke when:
- Designing new pages (Landing Page, Dashboard, Admin, SaaS, Mobile App)
- Creating or refactoring UI components (buttons, modals, forms, tables, charts)
- Choosing color schemes, typography systems, spacing standards, or layout systems
- Reviewing UI code for user experience, accessibility, or visual consistency
- Implementing navigation structures, animations, or responsive behavior
- Making product-level design decisions (style, information hierarchy, brand expression)
- Improving perceived quality, clarity, or usability of interfaces

### Skip
Not needed for:
- Pure backend logic development
- Only involving API or database design
- Performance optimization unrelated to the interface
- Infrastructure or DevOps work

## Priority Reference Table

| Priority | Category | Impact | Key Checks | Anti-Patterns |
|----------|----------|--------|------------|----------------|
| 1 | Accessibility | CRITICAL | Contrast 4.5:1, Alt text, Keyboard nav, Aria-labels | Removing focus rings, Icon-only buttons without labels |
| 2 | Touch & Interaction | CRITICAL | Min size 44×44px, 8px+ spacing, Loading feedback | Reliance on hover only, Instant state changes (0ms) |
| 3 | Performance | HIGH | WebP/AVIF, Lazy loading, Reserve space (CLS < 0.1) | Layout thrashing, Cumulative Layout Shift |
| 4 | Style Selection | HIGH | Match product type, Consistency, SVG icons (no emoji) | Mixing flat & skeuomorphic randomly |
| 5 | Layout & Responsive | HIGH | Mobile-first breakpoints, Viewport meta, No horizontal scroll | Fixed px container widths |
| 6 | Typography & Color | MEDIUM | Base 16px, Line-height 1.5, Semantic color tokens | Text < 12px body, Gray-on-gray |
| 7 | Animation | MEDIUM | Duration 150–300ms, Motion conveys meaning | Decorative-only animation, No reduced-motion |
| 8 | Forms & Feedback | MEDIUM | Visible labels, Error near field, Helper text | Placeholder-only label |
| 9 | Navigation Patterns | HIGH | Predictable back, Bottom nav ≤5, Deep linking | Overloaded nav |
| 10 | Charts & Data | LOW | Legends, Tooltips, Accessible colors | Relying on color alone |

## Accessibility (CRITICAL)
- Minimum 4.5:1 contrast ratio for normal text
- Visible focus rings on interactive elements (2–4px)
- Descriptive alt text for meaningful images
- aria-label for icon-only buttons
- Tab order matches visual order; full keyboard support
- Use label with for attribute on forms
- Sequential h1→h6, no level skip
- Don't convey info by color alone (add icon/text)
- Respect prefers-reduced-motion

## Touch & Interaction (CRITICAL)
- Min 44×44pt (Apple) / 48×48dp (Material)
- Minimum 8px gap between touch targets
- Use click/tap for primary interactions; don't rely on hover alone
- Disable button during async operations; show spinner
- Clear error messages near problem
- Use touch-action: manipulation to reduce 300ms delay

## Layout & Responsive (HIGH)
- width=device-width initial-scale=1 (never disable zoom)
- Design mobile-first, then scale up
- Use systematic breakpoints (375 / 768 / 1024 / 1440)
- Minimum 16px body text on mobile
- No horizontal scroll on mobile
- Use 4pt/8dp incremental spacing system
- Consistent max-width on desktop (max-w-6xl / 7xl)

## Typography & Color (MEDIUM)
- Line-height 1.5-1.75 for body text
- Limit to 65-75 characters per line
- Font-weight hierarchy: Bold headings (600–700), Regular body (400)
- Define semantic color tokens (primary, secondary, error, surface)
- Dark mode uses desaturated/lighter tonal variants, not inverted colors

## Animation (MEDIUM)
- Duration 150–300ms for micro-interactions
- Use transform/opacity only; avoid animating width/height
- Show skeleton or progress indicator when loading exceeds 300ms
- Use ease-out for entering, ease-in for exiting

## Pre-Delivery Checklist
- [ ] No emojis used as icons (use SVG instead)
- [ ] All tappable elements provide clear pressed feedback
- [ ] Touch targets meet minimum size (≥44x44pt iOS, ≥48x48dp Android)
- [ ] Primary text contrast ≥4.5:1 in both light and dark mode
- [ ] Safe areas are respected for headers, tab bars
- [ ] Verified on small phone, large phone (portrait + landscape)
- [ ] 4/8dp spacing rhythm maintained
- [ ] All meaningful images/icons have accessibility labels
