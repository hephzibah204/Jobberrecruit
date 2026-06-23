# Site-wide auto-dark fix — JobberRecruit

This stops mobile browsers (iOS Safari's auto dark-website tint / "Smart Invert",
and the in-app webviews inside WhatsApp, Facebook, Instagram, Telegram) from
darkening page backgrounds while leaving your dark text colours unchanged —
the bug that made "8 Jobs Found" and section headings vanish at night.

Apply it **once** in your shared layout + base stylesheet and it covers every
current and future page. Three pieces:

---

## 1. Head partial — add inside `<head>`

Put this next to your existing `<meta name="theme-color">`:

```html
<meta name="color-scheme" content="light">
```

That `theme-color` meta you already have stays as-is.

---

## 2. Base stylesheet — add to `:root`

Add the one declaration at the top of your `:root` token block:

```css
:root {
  color-scheme: light;   /* keep form controls & browser UI surfaces light */
  /* …your existing --brand, --bg, --text, etc. tokens stay below… */
}
```

---

## 3. Base stylesheet — paste this block once (anywhere after the reset)

This is the part that actually defeats the auto-darkening: it gives the page's
wrapper surfaces EXPLICIT backgrounds so the browser has nothing to override,
and re-asserts text colours. Harmless in normal light viewing.

```css
/* ════════════════════════════════════════════════════════════════════
   iOS / mobile AUTO-DARK DEFEAT  (site-wide)
   Wrappers that rely on INHERITED background get darkened by mobile
   auto-dark while explicit text colours are left alone — making dark
   headings disappear. Setting explicit backgrounds stops it.
   ════════════════════════════════════════════════════════════════════ */
html, body { background: #f5f7fb; }            /* --bg */

/* Wrappers that must never inherit a darkened background */
main, .section, .container { background-color: #f5f7fb; }

/* Sections intentionally white keep white (add your own *-bg classes here) */
.section.hiw-bg,
.section.faq-bg,
.section.training-bg,
.section.testi-bg { background-color: #ffffff; }

/* Re-assert core text colours so they can't be left dark on a dark fill */
.section-title, .job-title, .cat-name, .loc-name, .step-title,
.course-title, .testi-name, .feat-name,
.results-count strong, .cta-panel.light h2 { color: #141926; }   /* --text */
.section-title span { color: #0861A9; }                          /* --brand */
.section-sub, .results-count span { color: #5b6577; }            /* --muted */

/* Card & key surfaces: explicit, never inherited */
.job-card,
.cat-card, .loc-card, .feat-card, .step-card, .course-card,
.testi-card, .ai-card.light, .cta-panel.light,
details.faq-item, .filters, .results-empty,
.pagination a, .pagination span { background-color: #ffffff; }

.job-card--featured { background: linear-gradient(180deg, #fffaf0, #fff); }
.loc-card.featured  { background: #0861A9; }   /* --brand */
.step-card          { background: #f5f7fb; }   /* --bg */
.filters-head       { background: #E6F0F8; }   /* --brand-light */
```

---

## Notes

- The hex values mirror your tokens (`--bg #f5f7fb`, `--text #141926`,
  `--brand #0861A9`, `--muted #5b6577`, `--brand-light #E6F0F8`). If you ever
  change a token, update the matching hex here, or swap these for `var(--…)`
  references if this block lives in the same stylesheet as your tokens.
- Selectors not present on a given page simply do nothing — safe to ship globally.
- This is a **light-lock**, the right call for a job board (matches Jobberman,
  MyJobMag, Indeed, LinkedIn — all light-only on web). If you later build a
  logged-in candidate dashboard, that surface is where a real dark theme earns
  its keep; the public/marketing pages stay light.
- After deploying, test on a real iPhone at night through Safari AND through a
  WhatsApp/Facebook link, since in-app webviews are the most aggressive.
