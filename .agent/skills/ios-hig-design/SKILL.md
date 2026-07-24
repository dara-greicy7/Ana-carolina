---
name: ios-hig-design
description: 'Design native iOS interfaces following Apple Human Interface Guidelines. Use when the user mentions "iPhone app", "iPad layout", "SwiftUI", "UIKit", "Dynamic Island", "safe areas", "HIG compliance", "SF Symbols", "haptic feedback", or "iOS accessibility".'
license: MIT
metadata:
  author: wondelai
  version: "1.2.0"
  source: https://raw.githubusercontent.com/wondelai/skills/main/ios-hig-design/SKILL.md
---

Framework for designing native iOS interfaces. Three pillars: clarity, deference, depth.

## Layout & Safe Areas
- Minimum touch target: 44x44pt
- Standard margins: 16-20pt from screen edges
- Never place interactive elements under notch/Dynamic Island/home indicator
- Spacing: 8 / 16 / 24pt increments

## Typography & Dynamic Type
- Large Title: 34pt Bold | Body: 17pt Regular | Caption: 12-13pt
- NEVER hardcode font sizes — use `.title`, `.body`, `.caption`
- Always support Dynamic Type; test at largest setting
- Left-aligned only, never justified

## Color & Dark Mode
- Use semantic colors: `Color(.label)`, `Color(.systemBackground)`
- Dark Mode is NOT optional — always ship both modes
- Maintain 4.5:1 contrast in both light and dark

## Navigation
- Tab bar (2-5 items) for primary navigation — NEVER hamburger menus on iOS
- Navigation stack for hierarchical drill-down
- Modals for focused tasks only; swipe-down to dismiss
- Use `NavigationStack` (not deprecated `NavigationView`)

## Accessibility
- Every interactive element needs `.accessibilityLabel`
- Minimum contrast: 4.5:1
- Test full app flow with VoiceOver only

## SF Symbols
- Use `Image(systemName:)` for all standard icons
- Filled variant for selected state (`house.fill`)
- App icon: 1024x1024px square

## Gestures & Haptics
- NEVER override: swipe-right (back), swipe-down (dismiss), pull-down (refresh)
- Impact haptic: physical actions | Notification: outcomes | Selection: UI changes

## Common Mistakes
| Mistake | Fix |
|---------|-----|
| Hamburger menus | Use tab bars |
| Hardcoded font sizes | Use semantic text styles |
| Ignoring safe areas | `.ignoresSafeArea()` for backgrounds only |
| Skipping Dark Mode | Semantic colors; test both appearances |
| Touch targets under 44pt | Ensure all ≥44x44pt |
