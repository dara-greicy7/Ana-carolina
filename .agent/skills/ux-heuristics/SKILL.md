---
name: ux-heuristics
description: 'Evaluate and improve interface usability using heuristic analysis. Use when the user mentions "usability audit", "UX review", "users are confused", "heuristic evaluation", "Nielsen heuristics", "cognitive walkthrough", or "usability testing".'
license: MIT
metadata:
  author: wondelai
  version: "1.3.0"
  source: https://raw.githubusercontent.com/wondelai/skills/main/ux-heuristics/SKILL.md
---

Practical usability principles for evaluating and improving user interfaces. Based on: "Users don't read, they scan. They don't make optimal choices, they satisfice."

## Core Principle
**"Don't Make Me Think"** - Every page should be self-evident. Every question mark that pops into a user's head adds cognitive load.

## Krug Principles

### 1. Don't Make Me Think
- Clever names lose to clear names every time
- Use "Sign in" not "Access your account portal"
- If a label needs explanation, simplify the label

### 2. Clicks Don't Matter — Confidence Does
- 3 mindless clicks beat 1 confusing click
- Users abandon when confused, not when they've clicked too many times

### 3. Get Rid of Half the Words
- Remove happy-talk ("Welcome to our website!")
- Remove instructions nobody reads
- Shorter pages = less scrolling, faster scanning

### 4. The Trunk Test
Users dropped on any page should instantly know:
- What site they're on (brand/logo)
- What page they're on (clear heading)
- Major sections (navigation)
- Search (always visible in header)

## Nielsen's 10 Heuristics

1. **Visibility of System Status** — Progress bars, confirmations, skeleton screens
2. **Match System to Real World** — "Sign in" not "Authenticate"; shopping cart not "Item repository"
3. **User Control and Freedom** — Undo beats confirmation dialogs; clear exit from every flow
4. **Consistency and Standards** — One term per concept everywhere ("Projects" not "Workspaces")
5. **Error Prevention** — Date pickers over text fields, autocomplete, "unsaved changes" warnings
6. **Recognition Rather Than Recall** — Breadcrumbs, recent searches, pre-filled fields
7. **Flexibility and Efficiency** — Keyboard shortcuts, bulk actions, command palettes (Cmd+K)
8. **Aesthetic and Minimalist Design** — One primary CTA per page; everything earns its place
9. **Help Users Recover from Errors** — What happened + why + how to fix it (plain language)
10. **Help and Documentation** — Searchable, task-focused, contextual

## Severity Ratings
| Severity | Rating | Priority |
|----------|--------|----------|
| Not a problem | 0 | Ignore |
| Cosmetic | 1 | Fix if time |
| Minor | 2 | Schedule fix |
| Major | 3 | Fix soon |
| Catastrophic | 4 | Fix immediately |

## Common Mistakes
| Mistake | Fix |
|---------|-----|
| Mystery meat navigation (icons only) | Add text labels |
| No "you are here" indicator | Highlight current section |
| No inline validation | Validate on blur with specific messages |
| Wall of text | Break up with headings, bullets, whitespace |
| No loading indicators | Show spinner, progress bar, or skeleton |
| Tiny tap targets | Minimum 44x44 px |
| No undo | Provide undo for all non-destructive actions |
| Poor error messages ("Invalid input") | Explain what's wrong and how to fix it |
