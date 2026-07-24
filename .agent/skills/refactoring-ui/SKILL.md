---
name: refactoring-ui
description: 'Audit and fix visual hierarchy, spacing, color, and depth in web UIs. Use when the user mentions "my UI looks off", "fix the design", "Tailwind styling", "color palette", "visual hierarchy", "design system", "spacing scale", or "component styling".'
license: MIT
metadata:
  author: wondelai
  version: "1.2.0"
  source: https://raw.githubusercontent.com/wondelai/skills/main/refactoring-ui/SKILL.md
---

A practical, opinionated approach to UI design. **Design in grayscale first. Add color last.**

## Core Principle
Great UI isn't about creativity — it's about systems. Constrained scales for spacing, type, color, and shadows produce consistently professional results.

## 7 Principles

### 1. Visual Hierarchy
Not everything can be important. Three levers: **size, weight, color**.
- Primary text = large OR bold OR dark (not all three — save that for ONE element)
- Labels are secondary — form labels support the data, don't compete with it
- De-emphasize labels: smaller, lighter, or uppercase-small

### 2. Spacing & Sizing
Use constrained scale: **4, 8, 16, 24, 32, 48, 64px**
- Start with too much white space, then remove
- Spacing between groups > spacing within groups
- Text: max-w-prose (~65ch); Forms: max 300-500px width
- CSS: `p-1`(4px) `p-2`(8px) `p-4`(16px) `p-6`(24px) `p-8`(32px)

### 3. Typography
Modular scale: **12, 14, 16, 20, 24, 30, 36px** (1.25 ratio)
- Headings: tight line-height (1.0–1.25)
- Body: relaxed line-height (1.5–1.75)
- Two fonts maximum: headings + body
- CSS: `leading-tight`(1.25) `leading-normal`(1.5) `leading-relaxed`(1.75)

### 4. Color
Build systematic palette: **5-9 shades (50–900) per color**
- Don't use pure black (`#000`) — use gray-900 (`#111827`)
- Pure grays look lifeless — add subtle saturation (cool blue for tech UIs)
- Body text minimum 4.5:1 contrast ratio
- Use `#374151` (gray-700) on white minimum for readable text

### 5. Depth & Shadows
Shadow scale = elevation level:
- `shadow-sm` — buttons (slightly raised)
- `shadow-md` — cards (clear separation)
- `shadow-lg` — dropdowns (floating)
- `shadow-xl` — modals (highest elevation)

### 6. Images & Icons
- Icons sized relative to context; consistent stroke width
- `object-fit: cover` with fixed aspect-ratio for images
- Image overlay: `bg-gradient-to-t from-black/60 to-transparent`
- Empty states = illustration + clear CTA (not just text)

### 7. Layout
- Left-align text by default; center only heroes, short headlines, empty states
- Cards can bleed images to edges — break out of rigid boxes
- `text-left` default; `text-center` for heroes only
- `max-w-4xl mx-auto` for page containers

## Quick Diagnostic
| Question | If No | Action |
|----------|-------|--------|
| Does hierarchy read when squinting? | Elements competing | Increase contrast between primary/secondary |
| Does it work in grayscale? | Relying on color for hierarchy | Strengthen size/weight/spacing |
| Is there enough white space? | Too dense | Increase spacing, especially between groups |
| Does spacing follow consistent scale? | Arbitrary spacing | Use 4/8/16/24/32/48/64 only |
| Is text width constrained? | Reader fatigue | Apply max-w-prose (~65ch) |
| Do colors have sufficient contrast? | Accessibility failure | Use gray-700+ on white |

## Common Mistakes
| Mistake | Fix |
|---------|-----|
| "Looks amateur" | Add more white space, constrain widths |
| "Feels flat" | Add subtle shadows, border-bottom on sections |
| "Text is hard to read" | Increase line-height, constrain width |
| "Everything looks the same" | Vary size/weight/color |
| "Colors clash" | Reduce saturation, use more grays, limit palette |
| Using arbitrary px values (13, 17, 23) | Stick to spacing and type scales |
