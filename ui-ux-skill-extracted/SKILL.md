---
name: ui-ux-pro-max
description: AI-powered design intelligence for building professional UIs. Use when you need industry-specific design rules, color palettes, typography pairings, UX guidelines, or landing page patterns for any product niche.
---

# UI UX Pro Max

## Overview

The **UI UX Pro Max** skill is a design reasoning engine that provides structured, data-driven guidance for building professional-grade user interfaces. It transforms general development tasks into high-fidelity, industry-specific design implementations.

## Design Reasoning Engine

When tasked with design or UI implementation, follow this 4-step workflow:

### 1. Analyze (Context Mapping)
Identify the following from the user request or codebase:
- **Product Niche**: (e.g., Fintech, SaaS, E-commerce, Healthcare)
- **Tech Stack**: (e.g., React + Tailwind, Next.js, Flutter, React Native)
- **Primary Goal**: (e.g., Landing page, Dashboard, Mobile App, Form)

### 2. Match (Data Retrieval)
Use `grep_search` on the reference files to retrieve specific design rules:
- **Niche Specs**: Search `references/products.csv` for the product type to find recommended styles and considerations.
- **Visual Style**: Search `references/styles.csv` for the "Primary Style Recommendation" from step 1 to get CSS/Technical keywords and implementation checklists.
- **Color Palette**: Search `references/colors.csv` for the product type to get WCAG-compliant HEX codes for Primary, Secondary, Accent, Background, etc.
- **Typography**: Search `references/typography.csv` for pairings that match the "Typography Mood" recommended in `ui-reasoning.csv`.

### 3. Structure (Pattern Application)
Apply established layout patterns based on the goal:
- **Web Landing**: Reference `references/landing.csv` for section order and CTA placement.
- **Dashboards**: Reference `references/ui-reasoning.csv` for data-dense layout rules.
- **Mobile Apps**: Reference `references/app-interface.csv` for touch targets and mobile navigation.

### 4. Verify (UX Audit)
Check the final implementation against `references/ux-guidelines.csv`.
- Ensure performance (image optimization, lazy loading).
- Verify accessibility (contrast, ARIA labels, focus states).
- Avoid common anti-patterns listed in `ui-reasoning.csv`.

## Core Capabilities

### Niche-Specific Design
Search for a product type (e.g., "Fintech") in `references/products.csv` to get:
- Primary/Secondary Styles
- Landing Page Patterns
- Color Palette Focus
- Key Considerations (e.g., "Trust is paramount")

### Professional Color Systems
Retrieve full semantic color palettes from `references/colors.csv` including:
- `Primary`, `Secondary`, `Accent` (with `On` variants)
- `Background`, `Foreground`, `Card`, `Muted`, `Border`
- `Destructive` and `Ring`

### Component-Level Logic
Use `references/ui-reasoning.csv` to determine:
- Key Effects (e.g., "Backdrop blur 20px", "0px sharp corners")
- Decision Rules (e.g., "if_data_heavy: add-glassmorphism")
- Anti-Patterns to avoid.

## Examples

### Request: "Design a landing page for a new Crypto Wallet"
1. **Search** `products.csv` for "Fintech/Crypto" → Recommended Style: "Glassmorphism + Dark Mode".
2. **Search** `colors.csv` for "Fintech/Crypto" → Palette: #F59E0B (Gold), #0F172A (Dark), #8B5CF6 (Purple).
3. **Search** `styles.csv` for "Glassmorphism" → Implementation: `backdrop-filter: blur(15px)`, 1px white border 20% opacity.
4. **Search** `landing.csv` for "Hero + Features + CTA" → Section Order: Hero, Value Prop, Features, CTA.

### Request: "Fix the UX of my registration form"
1. **Search** `ux-guidelines.csv` for "Forms" → Rules: "Input Labels", "Inline Validation", "Submit Feedback".
2. **Apply** "Every input needs a visible label", "Show error below related input", "Disable button during submit".
