# Lagos location view comparison — 19 October 2026

## Scope and evidence

Reference: `Doc/UI and UX Doc/jobberrecruit HTML Files/jobberrecruit-location-lagos.html`.
Implementation: `app/Views/home/location_hub.php`, `css/location-hub.css`, shared header/footer and base template.
Live page inspected: https://jobberrecruit.com/jobs-in-lagos-state, logged out, desktop 1440 × 1000 and mobile 390 × 844.

LIMITATION: The browser rejected opening the reference file because file:// navigation is prohibited. The reference was inspected as HTML/CSS, not rendered. Therefore this is a live-page inspection plus source comparison, NOT an exhaustive screenshot-to-screenshot visual comparison. Other states share the template but were not individually opened. No application files were changed for this review.

## Main conclusion

The complete CSS extracted from the mockup is retained in location-hub.css, with an introductory comment and an appended integration/mobile override block. The underlying colors, typography declarations, hero gradient, card styles, section structure and responsive rules were not broadly rewritten. Most differences are content, conditional markup, shared components and the appended overrides.

## Confirmed differences

1. **Hero emphasis is missing (high priority).** Mockup wraps “Lagos State” in `<em>`, styled orange. The live heading is entirely white. In location_hub.php, the custom `seo_h1` branch outputs escaped text without emphasis, bypassing the styled fallback. Preserve intentional emphasis when rendering custom headings.
2. **Hero title wording differs.** Mockup: “Jobs in Lagos State, Nigeria — Verified Vacancies”. Live: “… — Find Verified Vacancies”. This changes line wrapping, especially on narrow screens.
3. **Hero supporting text differs.** Mockup includes “oil & gas and more”; live copy stops at “FMCG, and more”. This affects line breaks and text height.
4. **Breadcrumb wording differs.** Mockup: “Lagos State Jobs”. Live: “Lagos Jobs”.
5. **Navigation items differ.** Mockup includes Career advice in the primary navigation; live substitutes/adds Candidate hub and Blog and has no primary Career advice entry. Header density and spacing therefore differ. The mobile menu derives from the shared header rather than the standalone mockup.
6. **Logo assets differ.** Mockup references header/footer assets under `/assets/jobberrecruit-logo-...` with picture/source variants. Shared templates use `/images/logo.png` and `/images/logo-white.png`. Footer source uses an explicit 52px height rather than the reference image attributes of 232 × 60. Exact rendered-image differences require a rendered reference.
7. **Mobile logo sizing differs in source.** Added integration CSS caps the header logo at 135px below 580px and 110px below 380px. This is additional to the reference rules. Live 390px inspection shows a compact logo and hamburger.
8. **Mobile header CTA rule conflict.** Added location CSS attempts to show “Get started free” below 580px, but scopes this to `.homepage-reference`; the shared header also hides non-dashboard buttons at that size. The live 390px screenshot shows no CTA. Treat this as a local-rule/live-render mismatch to resolve deliberately, not proof that the reference itself displays a CTA at 390px.
9. **About paragraph grouping differs (medium priority).** Mockup uses two paragraphs. Live renders the description as one long paragraph, making the block denser. The view splits only on blank lines, so stored text without paragraph breaks loses the reference spacing. Some wording is also different: the live copy contains “Nigeria's commercial capital …, hosting …” rather than the reference’s separate “As Nigeria's commercial capital …” sentence.
10. **Job count and page length differ (expected live data).** Reference has seven cards, “Showing 7 of 7”, and “7+” in the statistics panel. Live has three cards, “Showing 3 of 3”, and “3”. On desktop the live cards occupy one row rather than the reference’s three rows. Do not add fake jobs to match the mockup.
11. **Card selection and order differ (expected live data).** Live shows the AP graduate role, HR/People & Operations Manager and Renaissance vacancies. Reference additionally includes the NAE scholarship, Sales and Procurement Manager, Social Media Manager/Creative Assistant, and Administrative Officer. These are not demonstrated missing records; availability/filtering was not audited.
12. **Company imagery differs.** Reference cards use JR/CE text placeholders. Live Jobber Recruit cards show image logos; the confidential card displays “Co”. These produce a different visual weight in the logo square.
13. **Anonymous company label differs.** Reference uses “Confidential”; live uses “Confidential Employer”. The longer label changes available space.
14. **Anonymous verification badge differs.** Reference displays a verified mark even on confidential example cards. Implementation deliberately suppresses verification when a job is anonymous; live confidential card has no mark. This is a behavior/privacy choice, not automatically a defect.
15. **Job metadata formatting differs.** Reference uses “Full-time” and compact “Entry Level (0–2 yrs)”/“Mid Level (2–5 yrs)”. Live shows raw “full-time” and “Entry Level (0-2 years)”/“Mid Level (2-5 years)”. The longer strings change wrapping and card density. Normalize display labels if matching the reference is desired.
16. **Job title casing differs.** The HR role is all uppercase on the live page, whereas the reference uses mixed case after “WE ARE HIRING”. This changes visual emphasis. Live titles are links; reference titles are plain heading text, so hover behavior also differs.
17. **Salary variety differs (expected live data).** All three live cards show Negotiable. Reference includes numeric monthly salaries on several cards. Salary layout cannot be fully compared using the current live sample.
18. **Save controls differ by authentication.** Reference uses Save buttons. Logged-out live cards use login links styled as Save controls; signed-in implementation wraps buttons in forms. Added CSS sets `.job-save-form` to flex. Signed-in alignment was not visually tested.
19. **Advertisement content differs at inspection time.** Live displays a filled “Discover more” ad with Search Jobs, Find Recruiters and jobs links. Reference includes an AdSense slot but its rendered fill was not observed. Creative content and height are runtime-dependent, not fixed design mismatches.
20. **State-grid mobile sizing differs in source.** Reference uses auto-fill columns with a 180px minimum; the appended location CSS explicitly sets two equal columns below 580px and allows word wrapping. If that override is active, it changes narrow-phone column counts compared with the reference. Live lower-grid mobile rendering remains unverified.
21. **State-grid subtitle differs slightly.** Reference says “all 36 states and the FCT”; live says “all 36 states and FCT”. State destinations also differ for FCT: mockup `jobs-in-fct-state`, implementation/live `jobs-in-fct-abuja`.
22. **Footer destinations differ, mostly intentional routing updates.** Resume builder, mock interview, referral, post-a-job, recruitment, job ad pricing, About, FAQs and Terms point to application routes rather than reference paths. Visible footer section labels and overall content remain largely equivalent. WhatsApp uses a real contact destination instead of the reference placeholder number.
23. **Additional integration rules affect edge cases.** The app adds global hidden/d-none handling, inherited title-link color, fixed view-all icon sizing, contained logo images, flexible card heading widths, bottom-aligned card actions, save feedback text and overflow wrapping for description/statistics content. These are additions, not removals from the reference CSS; visual impact depends on content and state.
24. **Shared-base behavior is additional.** The application imposes a custom image cursor and includes authenticated chatbot UI. The latter can introduce an overlay absent from the standalone mockup; it was not assessed because inspection was logged out. The observed live page also exposes a small framework/debug-looking floating control at the lower right; its identity and deployment configuration were not verified.

## Elements retained

Section order: navigation → hero/statistics → job-market introduction → latest jobs → advertisement → state links → footer. The source preserves Sora/Inter typography, blue/orange brand tokens, gradient hero, featured ribbon styling, rounded cards, three-column desktop job layout, and the reference state-link styling. These are source matches, not a claim of pixel equality.

## Recommended priorities

1. Restore the orange location emphasis in the custom heading path.
2. Restore two readable paragraphs in the Lagos introduction.
3. Decide whether navigation changes are intentional or should follow the reference.
4. Normalize job metadata labels and confidential-company initials.
5. Reconcile mobile header overrides with the actual rendered class structure.
6. Complete reference rendering before adjusting exact spacing, heights, font metrics, logos or mobile breakpoints.

## Remaining verification

A rendered mockup is required to finish the requested side-by-side comparison. Still unverified: exact pixel spacing, font loading equivalence, reference asset loading, hover/focus states, open menus, signed-in save controls, empty-state rendering, other states, and matching mobile state-grid/footer screenshots. Browser security explicitly prohibits working around the blocked file navigation. The user can supply screenshots of the mockup for the remaining visual review.

## Implementation follow-up

Updated locally on 19 October 2026: highlighted location names even in custom SEO headings; removed the extra Find wording in standard headings; state breadcrumbs; Lagos reference hero and two-paragraph introduction; readable paragraph grouping for other states; dedicated reference-order location navigation and footer sizing; mobile header fit; confidential label/CE fallback; employment/experience display labels; state subtitle; reference auto-fill grid; standard cursor behavior.

Browser-verified local Lagos at 390 × 844 and 1440 × 1000: orange emphasis rgb(237,144,32), two paragraphs, seven real local records, three desktop job columns, no horizontal page overflow. Ogun also renders the shared template and highlights its own location name.

Intentional production differences retained: actual job counts, titles, salaries and employer images; live ads; authenticated actions; correct application routes; privacy-aware verification; available brand assets rather than nonexistent reference asset paths. No fabricated jobs, salaries or verification badges were introduced. The local and production databases have different records.

Not deployed. Exact pixel parity with the local mockup remains unverified because its browser rendering was blocked. Signed-in controls and ad creatives were not visually regression-tested. The framework toolbar is local development UI, not a design element to reproduce.
