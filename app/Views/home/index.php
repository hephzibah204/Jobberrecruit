<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "@id": "<?= base_url() ?>#website",
      "url": "<?= base_url() ?>",
      "name": "JobberRecruit",
      "description": "Nigeria's trusted job platform. AI career tools, training & instant certification.",
      "inLanguage": "en-NG",
      "publisher": { "@id": "<?= base_url() ?>#organization" },
      "potentialAction": {
        "@type": "SearchAction",
        "target": { "@type": "EntryPoint", "urlTemplate": "<?= base_url('jobs') ?>?q={search_term_string}" },
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "Organization",
      "@id": "<?= base_url() ?>#organization",
      "name": "JobberRecruit",
      "url": "<?= base_url() ?>",
      "logo": { "@type": "ImageObject", "url": "<?= base_url('images/logo.png') ?>", "width": 232, "height": 60 },
      "sameAs": [
        "https://twitter.com/jobberrecruit",
        "https://www.linkedin.com/company/jobberrecruit",
        "https://www.facebook.com/jobberrecruit",
        "https://www.instagram.com/jobberrecruit"
      ],
      "contactPoint": { "@type": "ContactPoint", "contactType": "customer support", "url": "<?= base_url('contact-us') ?>", "areaServed": "NG", "availableLanguage": "English" }
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        { "@type": "Question", "name": "Is JobberRecruit free to use for job seekers?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Creating an account, searching for jobs, setting up job alerts, and applying to listings are all completely free for job seekers on JobberRecruit." } },
        { "@type": "Question", "name": "How do I search for jobs in Lagos, Abuja, or Port Harcourt?", "acceptedAnswer": { "@type": "Answer", "text": "Use the search bar on the homepage. Select your preferred city from the Location dropdown, enter a job title or keyword, and click Search jobs." } },
        { "@type": "Question", "name": "What is the AI Resume Builder?", "acceptedAnswer": { "@type": "Answer", "text": "The AI Resume Builder helps you create a professional, ATS-optimised resume in minutes, tailored to specific job roles and industries in Nigeria." } },
        { "@type": "Question", "name": "Can employers post jobs for free on JobberRecruit?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Employers can post their first job listing for free. Premium plans offer additional visibility and featured placements. Access to the verified candidate database is a separate paid product and is not included in premium plans." } },
        { "@type": "Question", "name": "How does the JobberRecruit referral programme work?", "acceptedAnswer": { "@type": "Answer", "text": "Share your unique referral link. When someone you refer makes their first qualifying payment, you instantly receive 10% commission added to your JobberRecruit wallet. Wallet funds are non-withdrawable but can be spent on JobberRecruit services." } }
      ]
    }
  ]
}
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* ============================================================
   DESIGN SYSTEM — BRAND COLORS ONLY
   ⚠️ IMPORTANT: Reference/mockup design files may use
   different hex values (e.g. #0D609E, #ED9020). NEVER copy
   those hexes directly — always use the BRAND COLORS below:
     Blue   → var(--brand)  (primary)
     Orange → var(--accent)  (accent)
   ============================================================ */
:root {
  --brand: #0D609E;
  --brand-dark: #0A4D7E;
  --brand-deep: #07304F;
  --brand-light: #E6F0F9;
  --accent: #F08F1A;
  --accent-dark: #C8750E;
  --text: #141926;
  --muted: #5b6577;
  --bg: #f5f7fb;
  --white: #ffffff;
  --border: #e2e8f2;
  --success: #16a34a;
  --warning-color: #F59E0B;
  --radius: 10px; 
  --shadow: 0 2px 14px rgba(10,47,87,.08); 
  --shadow-lg: 0 14px 40px rgba(10,47,87,.16); 
  --transition: .18s ease;
}

*, *::before, *::after { box-sizing: border-box; }
html { scroll-behavior: smooth; }
/* Page-level background: the reference uses #f5f7fb, not white */
html, body { background: #f5f7fb; }
main, .section, .container, .jobs-header, .jobs-grid { background-color: transparent; }
.section { background-color: #f5f7fb; }
.section.hiw-bg, .section.faq-bg, .section.training-bg, .section.testi-bg { background-color: #ffffff; }
.section-title, .job-title, .cat-name, .loc-name, .step-title, .course-title, .testi-name, .feat-name, .cta-panel.blue h2,
.cta-panel.blue p { color: #ffffff; }
.cta-panel.light h2 { color: #141926; }
.section-title span { color: var(--brand); }
.section-sub { color: #5b6577; }
.job-card { background: #ffffff; }
.job-card--featured { background: linear-gradient(180deg, #fffaf0, #fff); }
.cat-card, .loc-card, .feat-card, .step-card, .course-card, .testi-card, .ai-card.light, .cta-panel.light, details.faq-item { background: #ffffff; }
.loc-card.featured { background: var(--brand); }
.step-card { background: #f5f7fb; }

/* Custom homepage style classes using mockup mapping */
.section { padding: 76px 0; background-color: var(--bg); }
.section.hiw-bg, .section.faq-bg, .section.training-bg, .section.testi-bg { background-color: var(--white); }
.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
.text-center { text-align: center; }
.ic { display: inline-flex; align-items: center; justify-content: center; line-height: 0; }
.section-label { display: inline-flex; align-items: center; gap: 7px; font-size: .72rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--brand); background: var(--brand-light); padding: 5px 13px; border-radius: 20px; margin-bottom: 14px; }
.section-label svg { width: 13px; height: 13px; }
.section-title { font-size: clamp(1.6rem, 2.9vw, 2.25rem); font-weight: 800; line-height: 1.15; margin-bottom: 12px; color: var(--text); }
.section-title span { color: var(--brand); }
.section-sub { color: var(--muted); font-size: .95rem; max-width: 560px; }

/* Buttons — Reference uses .btn, current codebase uses .btn-m.
   Both selectors are listed so either works, matching the reference's
   exact visual output (padding, gap, radius, font, transition). */
.btn, .btn-m { display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 11px 22px; border-radius: 8px; font-family: 'Inter',sans-serif; font-size: .88rem; font-weight: 600; cursor: pointer; border: 1.5px solid transparent; transition: var(--transition); text-decoration: none; -webkit-tap-highlight-color: transparent; touch-action: manipulation; }
.btn svg, .btn-m svg { width: 16px; height: 16px; }
.btn-primary, .btn-m-primary { background: var(--brand); color: #fff; border-color: var(--brand); }
.btn-primary:hover, .btn-m-primary:hover { background: var(--brand-dark); border-color: var(--brand-dark); text-decoration: none; color: #fff; }
.btn-outline, .btn-m-outline { background: transparent; color: var(--brand); border-color: var(--border); }
.btn-outline:hover, .btn-m-outline:hover { background: var(--brand); color: #fff; border-color: var(--brand); text-decoration: none; }
.btn-accent, .btn-m-accent { background: var(--accent); color: var(--brand-deep); border-color: var(--accent); }
.btn-accent:hover, .btn-m-accent:hover { background: var(--accent-dark); border-color: var(--accent-dark); color: var(--brand-deep); text-decoration: none; }
.btn-white, .btn-m-white { background: var(--white); color: var(--brand); border-color: var(--white); }
.btn-white:hover, .btn-m-white:hover { background: var(--brand-light); text-decoration: none; }
.btn-sm, .btn-m-sm { padding: 8px 14px; font-size: .78rem; }
.btn-lg, .btn-m-lg { padding: 14px 32px; font-size: .95rem; }

/* Badges */
.badge-featured { background: var(--accent); color: var(--brand-deep); font-size: .68rem; font-weight: 700; padding: 4px 11px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px; letter-spacing: .03em; }
.badge-featured svg { width: 12px; height: 12px; }
.badge-verified { background: var(--brand); color: #fff; font-size: .68rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px; border: 1px solid var(--brand); }
.badge-verified svg { width: 12px; height: 12px; }

/* Hero */
.hero { background: radial-gradient(ellipse 70% 60% at 82% 20%, rgba(240,143,26,.16) 0%, transparent 55%), radial-gradient(ellipse 80% 70% at 10% 90%, rgba(13,96,158,.34) 0%, transparent 55%), linear-gradient(160deg, var(--brand-deep) 0%, var(--brand-dark) 55%, var(--brand) 100%); color: #fff; padding: 64px 0 0; position: relative; overflow: hidden; }
.hero-grid-bg { position: absolute; inset: 0; pointer-events: none; opacity: .5; background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px); background-size: 46px 46px; -webkit-mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%); mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%); }
.hero-motif { position: absolute; top: 46%; right: -50px; transform: translateY(-50%); width: min(500px, 44vw); height: auto; pointer-events: none; z-index: 0; opacity: .55; }
.hero-motif .ring { animation: motif-float 7s ease-in-out infinite; transform-origin: center; }
.hero-motif .head { animation: motif-bob 7s ease-in-out infinite; transform-origin: center; }
.hero-motif .scan { transform-origin: 50% 50%; }
@keyframes motif-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
@keyframes motif-bob { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
@media (max-width: 900px) { .hero-motif { top: -30px; right: -70px; transform: none; width: 240px; opacity: .28; } }
@media (max-width: 580px) { .hero-motif { top: -24px; right: -80px; width: 190px; opacity: .22; } }
.hero-inner { position: relative; z-index: 1; padding-bottom: 44px; }
#hero-seeker:not([hidden]), #hero-employer:not([hidden]) { animation: hero-fade .32s ease; }
@keyframes hero-fade { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
.hero-tabs { display: inline-flex; background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.18); border-radius: 10px; padding: 4px; margin-bottom: 26px; }
.hero-tabs button { display: inline-flex; align-items: center; gap: 7px; padding: 8px 18px; border-radius: 7px; border: none; background: transparent; color: rgba(255,255,255,.92); font-family: 'Inter',sans-serif; font-size: .83rem; font-weight: 600; cursor: pointer; transition: var(--transition); min-height: 40px; }
.hero-tabs button svg { width: 15px; height: 15px; }
.hero-tabs button:not(.active):hover { background: rgba(255,255,255,.12); }
.hero-tabs button.active { background: #fff; color: var(--brand); }
.hero-tag { display: inline-flex; align-items: center; gap: 7px; font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--accent); margin-bottom: 16px; }
.hero-tag svg { width: 14px; height: 14px; }
.hero h1 { font-size: clamp(1.9rem, 4.8vw, 3.1rem); font-weight: 800; line-height: 1.1; margin-bottom: 16px; color: #fff; }
.hero h1 em { font-style: normal; color: var(--accent); }
.hero-sub { font-size: 1rem; color: rgba(255,255,255,.9); max-width: 560px; margin-bottom: 28px; }
.hero-trust { display: flex; flex-wrap: wrap; gap: 18px; font-size: .82rem; margin-bottom: 28px; opacity: .94; }
.hero-trust span { display: flex; align-items: center; gap: 6px; }
.hero-trust svg { width: 15px; height: 15px; color: var(--accent); }
.search-card { background: #fff; border-radius: 12px; padding: 10px; display: flex; flex-wrap: wrap; gap: 8px; box-shadow: var(--shadow-lg); max-width: 820px; }
.search-field { position: relative; flex: 1 1 150px; display: flex; align-items: center; }
.search-field svg { position: absolute; left: 12px; width: 17px; height: 17px; color: var(--muted); pointer-events: none; }
.search-card input, .search-card select { width: 100%; border: 1px solid var(--border); border-radius: 7px; padding: 11px 14px 11px 38px; font-family: 'Inter',sans-serif; font-size: 1rem; color: var(--text); background: var(--bg); outline: none; appearance: none; -webkit-appearance: none; min-height: 46px; }
.search-card select { padding-left: 38px; }
.search-card input:focus, .search-card select:focus { border-color: var(--brand); background: var(--white); }
.search-card > button { flex: 0 0 auto; padding: 11px 24px; background: var(--accent); color: var(--brand-deep); border: none; border-radius: 7px; font-family: 'Inter',sans-serif; font-size: 1rem; font-weight: 600; cursor: pointer; transition: var(--transition); min-height: 46px; display: inline-flex; align-items: center; gap: 7px; }
.search-card > button svg { width: 17px; height: 17px; }
.search-card > button:hover { background: var(--accent-dark); }
.trending { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 18px; font-size: .8rem; }
.trending strong { opacity: .8; letter-spacing: .04em; }
.trending a { background: rgba(255,255,255,.12); color: #fff; padding: 5px 12px; border-radius: 20px; font-weight: 500; border: 1px solid rgba(255,255,255,.2); transition: var(--transition); min-height: 32px; display: inline-flex; align-items: center; }
.trending a:hover { background: rgba(255,255,255,.26); text-decoration: none; }
.hero-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 22px; }
.hero-pills a { background: rgba(255,255,255,.09); color: #fff; padding: 8px 13px; border-radius: 8px; font-size: .8rem; font-weight: 500; border: 1px solid rgba(255,255,255,.16); transition: var(--transition); text-decoration: none; min-height: 36px; display: inline-flex; align-items: center; gap: 7px; }
.hero-pills a svg { width: 15px; height: 15px; color: var(--accent); }
.hero-pills a:hover { background: rgba(255,255,255,.2); }
.hero-employer-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 8px; margin-bottom: 28px; }
.hero-employer-h2 { font-size: clamp(1.9rem, 4.8vw, 3.1rem); font-weight: 800; line-height: 1.1; margin-bottom: 16px; color: #fff; }
.hero-employer-h2 em { font-style: normal; color: var(--accent); }

/* Ticker */
.ticker { position: relative; z-index: 1; background: rgba(10,47,87,.55); border-top: 1px solid rgba(255,255,255,.12); backdrop-filter: blur(6px); overflow: hidden; display: flex; align-items: stretch; }
.ticker-label { flex-shrink: 0; display: flex; align-items: center; gap: 8px; background: var(--accent); color: var(--brand-deep); font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; padding: 0 16px; z-index: 2; }
.ticker-dot { width: 9px; height: 9px; border-radius: 50%; background: #fff; box-shadow: 0 0 0 1.5px rgba(10,47,87,.55); animation: pulse 1.5s ease-in-out infinite; }
@keyframes pulse { 0%,100% { transform: scale(1); opacity: 1; } 50% { transform: scale(.72); opacity: .7; } }
.ticker-viewport { flex: 1; overflow: hidden; position: relative; -webkit-mask-image: linear-gradient(90deg, transparent, #000 4%, #000 96%, transparent); mask-image: linear-gradient(90deg, transparent, #000 4%, #000 96%, transparent); }
.ticker-track { display: inline-flex; align-items: center; white-space: nowrap; padding: 12px 0; will-change: transform; animation: ticker-scroll 48s linear infinite; }
.ticker-viewport:hover .ticker-track { animation-play-state: paused; }
@keyframes ticker-scroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.ticker-item { display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,.92); font-size: .82rem; padding: 0 22px; border-right: 1px solid rgba(255,255,255,.1); text-decoration: none; }
.ticker-item:hover { text-decoration: none; color: #fff; }
.ticker-item:hover .ticker-role { color: var(--accent); }
.ticker-role { font-weight: 600; }
.ticker-co { opacity: .65; }
.ticker-loc { display: inline-flex; align-items: center; gap: 4px; opacity: .65; font-size: .78rem; }
.ticker-loc svg { width: 11px; height: 11px; }
.ticker-new { background: var(--accent); color: var(--brand-deep); font-size: .64rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; letter-spacing: .04em; }

/* Job Card Grid */
.jobs-header { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px; margin-bottom: 6px; }
.jobs-header-cta { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.jobs-total { font-size: .82rem; color: var(--muted); }
.jobs-total strong { color: var(--brand); font-weight: 700; }
.jobs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px; margin-top: 28px; }
.job-card { position: relative; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 22px; transition: var(--transition); display: flex; flex-direction: column; gap: 11px; }
.job-card:hover { box-shadow: var(--shadow-lg); border-color: var(--brand); transform: translateY(-3px); }
.job-card--featured { background: linear-gradient(180deg, rgba(245, 166, 35, 0.05), var(--white)); border-color: rgba(245,166,32,.35); border-left: 3px solid var(--accent); }
.job-card--featured:hover { border-color: var(--accent); }
.job-card .badge-featured { position: absolute; top: -9px; left: 16px; z-index: 2; box-shadow: 0 2px 6px rgba(10,47,87,.18); }
.job-card--featured { padding-top: 24px; }
.job-card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
.job-card-top > div:first-child { min-width: 0; }
.job-logo { width: 44px; height: 44px; border-radius: 9px; background: var(--brand-light); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-family: 'Sora',sans-serif; font-weight: 700; font-size: .9rem; color: var(--brand); flex-shrink: 0; }
.job-logo img { max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 8px; }
.job-title { font-size: 1rem; font-weight: 700; overflow-wrap: anywhere; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: var(--text); }
.job-company { font-size: .82rem; color: var(--muted); display: inline-flex; align-items: center; gap: 5px; max-width: 100%; }
.job-company-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0; }

.verified-check { position: relative; display: inline-flex; align-items: center; justify-content: center; color: var(--brand); flex-shrink: 0; background: none; border: none; padding: 0 0 0 1px; margin: 0; cursor: pointer; line-height: 0; vertical-align: middle; }
.verified-check svg { width: 14px; height: 14px; pointer-events: none; }
.verified-check:hover { color: var(--brand-dark); }
.verified-tip { position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%) translateY(4px); background: var(--white); color: var(--text); font-size: .72rem; font-weight: 600; line-height: 1.4; letter-spacing: 0; white-space: nowrap; padding: 7px 11px; border-radius: 8px; border: 1px solid var(--border); box-shadow: 0 8px 24px rgba(10,47,87,.16); opacity: 0; visibility: hidden; pointer-events: none; transition: opacity .16s ease, transform .16s ease; z-index: 40; display: inline-flex; align-items: center; gap: 6px; }
.verified-tip::after { content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); border: 6px solid transparent; border-top-color: var(--white); filter: drop-shadow(0 1px 0 var(--border)); }
.verified-tip svg { width: 13px; height: 13px; color: var(--success); }
.verified-tip strong { font-weight: 700; }
.verified-check.open .verified-tip, .verified-check:hover .verified-tip { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }

.job-meta { display: flex; flex-wrap: wrap; gap: 12px; font-size: .78rem; color: var(--muted); }
.job-meta span { display: inline-flex; align-items: center; gap: 5px; }
.job-meta svg { width: 13px; height: 13px; color: var(--muted); }
.job-salary { font-size: .92rem; font-weight: 700; color: var(--accent-dark); }
.job-salary-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.job-actions { display: flex; gap: 8px; margin-top: 4px; }
.job-actions .btn-m { flex: 1; padding: 10px; font-size: .82rem; min-height: 44px; }
.save-btn { background: none; border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 13px; cursor: pointer; color: var(--muted); display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; font-family: 'Inter',sans-serif; transition: var(--transition); min-height: 44px; }
.save-btn svg { width: 15px; height: 15px; }
.save-btn:hover { border-color: var(--brand); color: var(--brand); }
.save-btn[data-saved="true"] { color: var(--success); border-color: var(--success); }

/* Category Grid */
.cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-top: 28px; }
.cat-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 16px; text-align: center; text-decoration: none; display: block; transition: var(--transition); }
.cat-card:hover { border-color: var(--brand); box-shadow: var(--shadow); transform: translateY(-3px); text-decoration: none; }
.cat-icon { width: 44px; height: 44px; margin: 0 auto 10px; border-radius: 12px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; }
.cat-icon svg { width: 23px; height: 23px; }
.cat-card:hover .cat-icon { background: var(--brand); color: #fff; }
.cat-name { font-weight: 700; font-size: .88rem; color: var(--text); margin-bottom: 3px; overflow-wrap: anywhere; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.4em; }
.cat-count { font-size: .76rem; color: var(--muted); }

/* Location Grid */
.loc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; margin-top: 28px; }
.loc-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 14px; text-align: center; text-decoration: none; transition: var(--transition); display: block; }
.loc-card:hover { border-color: var(--brand); box-shadow: var(--shadow); transform: translateY(-3px); text-decoration: none; }
.loc-card.featured { background: var(--brand); border-color: var(--brand); }
.loc-ic { width: 38px; height: 38px; margin: 0 auto 8px; border-radius: 10px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; }
.loc-ic svg { width: 19px; height: 19px; }
.loc-card.featured .loc-ic { background: rgba(255,255,255,.16); color: #fff; }
.loc-name { font-weight: 700; font-size: .85rem; color: var(--text); }
.loc-count { font-size: .74rem; color: var(--muted); margin-top: 2px; }
.loc-card.featured .loc-name, .loc-card.featured .loc-count { color: #fff; }

/* How It Works Steps */
.steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 20px; margin-top: 40px; counter-reset: step; }
.step-card { position: relative; border: 1px solid var(--border); border-radius: var(--radius); padding: 30px 24px 26px; background: var(--bg); transition: var(--transition); }
.step-card:hover { box-shadow: var(--shadow-lg); border-color: var(--brand); transform: translateY(-3px); background: var(--white); }
.step-num { counter-increment: step; font-family: 'Sora',sans-serif; font-size: 2.6rem; font-weight: 900; color: var(--brand-dark); line-height: 1; margin-bottom: 4px; letter-spacing: -.03em; }
.step-num::before { content: counter(step, decimal-leading-zero); }
.step-card:last-child .step-num { color: var(--accent-dark); }
.step-ic { width: 40px; height: 40px; border-radius: 10px; background: var(--brand); color: #fff; display: flex; align-items: center; justify-content: center; margin: -22px 0 14px auto; }
.step-card:last-child .step-ic { background: var(--accent); color: var(--brand-deep); }
.step-ic svg { width: 20px; height: 20px; }
.step-title { font-weight: 700; font-size: .98rem; margin-bottom: 8px; color: var(--text); }
.step-desc { font-size: .83rem; color: var(--muted); line-height: 1.65; }

/* Platform Features Grid */
.feat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px; margin-top: 28px; }
.feat-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px 20px; display: flex; gap: 14px; align-items: flex-start; }
.feat-icon { width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; }
.feat-icon svg { width: 21px; height: 21px; }
.feat-name { font-weight: 700; font-size: .9rem; margin-bottom: 3px; color: var(--text); }
.feat-desc { font-size: .8rem; color: var(--muted); line-height: 1.55; }
.ai-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; margin-top: 18px; }
.ai-card { border-radius: var(--radius); padding: 30px 26px; display: flex; flex-direction: column; }
.ai-card.dark { background: linear-gradient(150deg, var(--brand-deep), var(--brand)); color: #fff; }
.ai-card.light { background: var(--white); border: 1px solid var(--border); color: var(--text); }
.ai-icon { width: 50px; height: 50px; border-radius: 13px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; background: var(--brand-light); color: var(--brand); }
.ai-card.dark .ai-icon { background: rgba(255,255,255,.12); color: #fff; }
.ai-icon svg { width: 26px; height: 26px; }
.ai-card h3 { font-size: 1.08rem; font-weight: 700; margin-bottom: 8px; }
.ai-card p { font-size: .85rem; margin-bottom: 18px; flex: 1; }
.ai-card.dark p { opacity: .86; }
.ai-card.light p { color: var(--muted); }
.ai-badge { display: inline-flex; align-items: center; gap: 5px; font-size: .68rem; font-weight: 700; letter-spacing: .03em; padding: 4px 11px; border-radius: 20px; margin-bottom: 14px; width: fit-content; }
.ai-badge svg { width: 12px; height: 12px; }
.ai-badge--ai { background: var(--brand-light); color: var(--brand-dark); }
.ai-badge--human { background: #ecfdf5; color: #166534; }
.ai-card.dark .ai-badge--ai { background: rgba(255,255,255,.15); color: #fff; }

/* Course Card Teasers */
.course-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 18px; margin-top: 28px; }
.course-card { position: relative; border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; background: var(--white); transition: var(--transition); display: flex; flex-direction: column; }
.course-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-3px); }
.course-featured-badge { position: absolute; top: 10px; left: 10px; z-index: 3; display: inline-flex; align-items: center; gap: 5px; background: var(--accent); color: var(--brand-deep); font-size: .64rem; font-weight: 800; letter-spacing: .03em; padding: 4px 9px; border-radius: 20px; box-shadow: 0 2px 6px rgba(10,47,87,.22); }
.course-featured-badge svg { width: 11px; height: 11px; }
.course-thumb { height: 110px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.92); position: relative; overflow: hidden; }
.course-thumb svg { width: 42px; height: 42px; }
.course-thumb img.thumb-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.thumb-blue { background: linear-gradient(135deg, var(--brand-deep), var(--brand)); }
.thumb-purple { background: linear-gradient(135deg, var(--brand-dark), #1d6fb8); }
.thumb-green { background: linear-gradient(135deg, var(--brand), var(--accent-dark)); }
.thumb-orange { background: linear-gradient(135deg, var(--brand-deep), var(--accent)); }
.course-body { padding: 18px; flex: 1; }
.course-title { font-weight: 700; font-size: .9rem; margin-bottom: 6px; overflow-wrap: anywhere; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.5em; color: var(--text); }
.course-meta { font-size: .75rem; color: var(--muted); display: flex; gap: 14px; }
.course-meta span { display: inline-flex; align-items: center; gap: 5px; }
.course-meta svg { width: 13px; height: 13px; }
.course-footer { padding: 12px 18px; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 10px; }
.course-cert { font-size: .72rem; color: var(--brand); font-weight: 600; display: inline-flex; align-items: center; gap: 5px; align-self: flex-start; background: var(--brand-light); padding: 3px 9px; border-radius: 20px; }
.course-cert svg { width: 13px; height: 13px; }
.course-price-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.course-price { font-size: .9rem; font-weight: 800; }
.course-price--free { color: var(--accent-dark); }
.course-price--paid { color: var(--brand); }

/* Testimonials */
.testi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 36px; }
.testi-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 26px; }
.testi-stars { color: var(--accent); display: flex; gap: 2px; margin-bottom: 14px; }
.testi-stars svg { width: 16px; height: 16px; }
.testi-text { font-size: .88rem; color: var(--text); line-height: 1.75; margin-bottom: 16px; }
.testi-author { display: flex; align-items: center; gap: 11px; }
.testi-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--brand); color: #fff; font-family: 'Sora',sans-serif; font-weight: 600; font-size: .82rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.testi-name { font-weight: 700; font-size: .85rem; color: var(--text); }
.testi-role { font-size: .74rem; color: var(--muted); }

/* Referral Band */
.referral-band { background: linear-gradient(120deg, #fffbeb, #fef3c7); border: 1px solid #fde68a; border-radius: 14px; padding: 24px 28px; display: flex; align-items: center; justify-content: space-between; gap: 28px; flex-wrap: wrap; }
.referral-band-text { display: flex; align-items: center; gap: 18px; flex: 1 1 420px; }
.ref-ic { width: 46px; height: 46px; border-radius: 12px; background: var(--accent); color: var(--brand-deep); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.ref-ic svg { width: 22px; height: 22px; }
.ref-ic--lg { width: 54px; height: 54px; }
.ref-ic--lg svg { width: 26px; height: 26px; }
.referral-band-title { font-family: 'Sora',sans-serif; font-size: clamp(1.15rem, 2vw, 1.45rem); font-weight: 800; line-height: 1.25; letter-spacing: -.01em; margin-bottom: 4px; color: var(--text); }
.referral-band-title span { color: var(--accent-dark); }
.referral-band-sub { font-size: .86rem; color: var(--muted); max-width: 520px; }
.referral-band-cta { flex-shrink: 0; }

/* Newsletter */
.newsletter-band { background: linear-gradient(120deg, var(--brand-light) 0%, #dce9f8 100%); padding: 40px 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
.newsletter-inner { display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; }
.newsletter-text { flex: 1 1 380px; }
.newsletter-text .section-label { margin-bottom: 10px; }
.newsletter-title { font-family: 'Sora',sans-serif; font-size: clamp(1.25rem, 2.2vw, 1.6rem); font-weight: 800; line-height: 1.2; letter-spacing: -.02em; margin-bottom: 8px; color: var(--text); }
.newsletter-title span { color: var(--brand); }
.newsletter-sub { color: var(--muted); font-size: .88rem; max-width: 460px; }
.newsletter-form { flex: 0 1 420px; display: flex; gap: 8px; }
.newsletter-field { position: relative; flex: 1; display: flex; align-items: center; }
.newsletter-field svg { position: absolute; left: 13px; width: 17px; height: 17px; color: var(--muted); pointer-events: none; }
.newsletter-field input { width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 12px 14px 12px 38px; font-family: 'Inter',sans-serif; font-size: 1rem; color: var(--text); background: var(--white); outline: none; min-height: 46px; }
.newsletter-field input:focus { border-color: var(--brand); }
.newsletter-form .btn-m { flex: 0 0 auto; min-height: 46px; padding: 12px 24px; }
.newsletter-form .btn-m:hover { background: var(--accent); border-color: var(--accent); color: var(--brand-deep); }

/* FAQ Accordion using details */
.faq-list { max-width: 760px; margin: 32px auto 0; }
details.faq-item { border: 1px solid var(--border); border-radius: var(--radius); background: var(--white); margin-bottom: 8px; overflow: hidden; }
details.faq-item[open] { border-color: var(--brand); }
details.faq-item summary { padding: 18px 22px; font-weight: 600; font-size: .9rem; cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center; gap: 12px; user-select: none; min-height: 48px; color: var(--text); }
details.faq-item summary::-webkit-details-marker { display: none; }
.faq-chev { flex-shrink: 0; width: 18px; height: 18px; color: var(--brand); transition: transform .2s; }
details.faq-item[open] .faq-chev { transform: rotate(180deg); }
.faq-answer { padding: 0 22px 18px; font-size: .87rem; color: var(--muted); line-height: 1.75; }
.faq-more { text-align: center; margin-top: 24px; }

/* Dual Call to Action panels */
.dual-cta { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.cta-panel { border-radius: 12px; padding: 44px 32px; }
.cta-panel.blue { background: linear-gradient(150deg, var(--brand-deep), var(--brand)); color: #fff; }
.cta-panel.light { background: var(--white); color: var(--text); border: 1px solid var(--border); }
.cta-ic { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
.cta-panel.blue .cta-ic { background: rgba(255,255,255,.14); color: #fff; }
.cta-panel.light .cta-ic { background: var(--brand-light); color: var(--brand); }
.cta-ic svg { width: 25px; height: 25px; }
.cta-panel h2 { font-size: 1.35rem; font-weight: 700; margin-bottom: 10px; color: inherit; }
.cta-panel p { font-size: .87rem; margin-bottom: 22px; }
.cta-panel.blue p { opacity: .86; }
.cta-panel.light p { color: var(--muted); }
.cta-list { list-style: none; margin-bottom: 26px; display: flex; flex-direction: column; gap: 9px; padding-left: 0; }
.cta-list li { display: flex; align-items: center; gap: 9px; font-size: .85rem; }
.cta-tag { font-size: .64rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: var(--accent); color: var(--brand-deep); padding: 1px 7px; border-radius: 20px; margin-left: 2px; }
.cta-list li svg { width: 16px; height: 16px; flex-shrink: 0; color: var(--accent); }

/* Ad slot — Google AdSense container matching reference spacing */
.ad-slot { margin: 0 auto; max-width: 1160px; padding: 0 20px; }
.ad-unit { background: #f0f3f8; border: 1px solid var(--border); border-radius: 12px; padding: 12px; text-align: center; overflow: hidden; min-height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
.ad-unit::before { content: "Advertisement"; display: block; font-size: .64rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; align-self: center; }
.ad-unit .adsbygoogle { width: 100%; display: block; }
.ad-section { padding: 28px 0; }

/* Responsive adjustments */
@media (max-width: 860px) { .nav-links, .nav-actions .btn-outline { display: none; } .hamburger { display: block; } .dual-cta { grid-template-columns: 1fr; } .footer-grid { grid-template-columns: 1fr 1fr; } .cat-grid { grid-template-columns: repeat(2, 1fr); } .ticker-label { padding: 0 12px; font-size: .68rem; } }
@media (max-width: 580px) {
  .section { padding: 54px 0; }
  .search-card { flex-direction: column; }
  .search-field { flex: 1 1 auto; width: 100%; }
  .search-card > button { width: 100%; justify-content: center; }
  .cta-panel { padding: 30px 22px; }
  .referral-band { flex-direction: column; align-items: flex-start; text-align: left; padding: 22px 20px; gap: 18px; }
  .referral-band-text { align-items: flex-start; gap: 14px; flex: 1 1 auto; width: 100%; }
  .referral-band-cta { width: 100%; justify-content: center; }
  .hero-tabs { width: 100%; }
  .hero-tabs button { flex: 1; justify-content: center; padding: 8px 10px; font-size: .78rem; }
  .hero-trust { gap: 12px; font-size: .78rem; }
  .jobs-grid { grid-template-columns: 1fr; }
  .ai-grid { grid-template-columns: 1fr; }
  .newsletter-form { flex: 1 1 100%; width: 100%; }
  .cat-grid, .loc-grid { grid-template-columns: repeat(2, 1fr); }
  .footer-bottom { flex-direction: column; text-align: center; }
  .footer-links { justify-content: center; }
  .ticker-label { padding: 0 10px; font-size: .6rem; letter-spacing: .04em; }
}
@media (max-width: 380px) { .container { padding: 0 14px; } .nav-logo img { height: 50px; } .cat-grid, .loc-grid { grid-template-columns: repeat(2, 1fr); } .steps-grid, .course-grid { grid-template-columns: 1fr; } }
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
  .ticker-track { animation: none !important; transform: none !important; }
  .ticker-dot { animation: none !important; }
  .hero-motif .ring, .hero-motif .head { animation: none !important; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main-content">


  <!-- ========== 1. HERO ========== -->
  <section class="hero" aria-label="Find jobs in Nigeria">
    <span class="hero-grid-bg" aria-hidden="true"></span>
    <svg class="hero-motif" viewBox="0 0 400 400" fill="none" aria-hidden="true" focusable="false">
      <defs>
        <radialGradient id="motifGlow" cx="50%" cy="42%" r="55%"><stop offset="0%" stop-color="var(--accent)" stop-opacity=".22"/><stop offset="100%" stop-color="var(--accent)" stop-opacity="0"/></radialGradient>
        <clipPath id="ringClip"><circle cx="200" cy="190" r="92"/></clipPath>
      </defs>
      <circle cx="200" cy="188" r="150" fill="url(#motifGlow)"/>
      <g clip-path="url(#ringClip)">
        <g class="scan">
          <rect x="108" y="98" width="184" height="184" fill="none"/>
          <line x1="200" y1="190" x2="200" y2="98" stroke="var(--accent)" stroke-width="3" stroke-opacity=".5">
            <animateTransform attributeName="transform" type="rotate" from="0 200 190" to="360 200 190" dur="6s" repeatCount="indefinite"/>
          </line>
        </g>
        <line x1="120" y1="190" x2="280" y2="190" stroke="#fff" stroke-opacity=".06" stroke-width="1"/>
        <line x1="200" y1="110" x2="200" y2="270" stroke="#fff" stroke-opacity=".06" stroke-width="1"/>
      </g>
      <line class="handle" x1="262" y1="252" x2="318" y2="312" stroke="var(--accent)" stroke-width="26" stroke-linecap="round"/>
      <circle class="ring" cx="200" cy="190" r="78" fill="none" stroke="var(--accent)" stroke-width="26"/>
      <circle class="head" cx="200" cy="78" r="30" fill="var(--accent)"/>
    </svg>
    <div class="container hero-inner">
      <p class="hero-tag"><svg aria-hidden="true"><use href="#i-shield"/></svg> Verified job platform · Nigeria</p>
      <div class="hero-tabs" role="group" aria-label="Select your role">
        <button class="active" onclick="switchHero(this,'seeker')" aria-pressed="true"><svg aria-hidden="true"><use href="#i-search"/></svg> Job seeker</button>
        <button onclick="switchHero(this,'employer')" aria-pressed="false"><svg aria-hidden="true"><use href="#i-building"/></svg> Employer</button>
      </div>
      
      <div id="hero-seeker">
        <h1>Find jobs, learn skills<br>&amp; <em>grow your career</em></h1>
        <p class="hero-sub">Search verified jobs, build AI-powered resumes, practise interviews, earn professional certificates, and connect with top employers across Nigeria.</p>
        <form class="search-card" action="<?= base_url('jobs') ?>" method="get" role="search" aria-label="Job search form">
          <label for="q" class="sr-only">Job title or keyword</label>
          <div class="search-field"><svg aria-hidden="true"><use href="#i-search"/></svg><input id="q" type="search" name="q" placeholder="e.g. Software Engineer" autocomplete="off" value="<?= esc($q) ?>"></div>
          <label for="loc" class="sr-only">Location</label>
          <div class="search-field"><svg aria-hidden="true"><use href="#i-pin"/></svg>
            <select id="loc" name="state_id">
              <option value="">All locations</option>
              <?php foreach ($states as $s): ?>
                <option value="<?= $s->id ?>"><?= esc($s->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <label for="cat" class="sr-only">Category</label>
          <div class="search-field"><svg aria-hidden="true"><use href="#i-bag"/></svg>
            <select id="cat" name="category_id">
              <option value="">All categories</option>
              <?php foreach ($categories as $c): ?>
                <option value="<?= $c->id ?>"><?= esc($c->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit"><svg aria-hidden="true"><use href="#i-search"/></svg> Search jobs</button>
        </form>
        <div class="trending" aria-label="Trending searches">
          <strong>TRENDING:</strong>
          <a href="<?= base_url('jobs?work_arrangement=remote') ?>">Remote</a>
          <a href="<?= base_url('jobs?q=software+developer') ?>">Software Developer</a>
          <a href="<?= base_url('jobs?q=marketing') ?>">Marketing</a>
          <a href="<?= base_url('jobs?q=data+analyst') ?>">Data Analyst</a>
          <a href="<?= base_url('jobs?q=finance') ?>">Finance</a>
          <a href="<?= base_url('jobs?q=oil+and+gas') ?>">Oil &amp; Gas</a>
        </div>
        <div class="hero-pills" aria-label="Quick access">
          <a href="<?= base_url('ai-tools/resume-builder') ?>"><svg aria-hidden="true"><use href="#i-bot"/></svg> AI resume builder</a>
          <a href="<?= base_url('training/cv-revamp') ?>"><svg aria-hidden="true"><use href="#i-doc"/></svg> CV revamp</a>
          <a href="<?= base_url('training') ?>"><svg aria-hidden="true"><use href="#i-cap"/></svg> Instant certificates</a>
          <a href="<?= base_url('ai-tools/mock-interview') ?>"><svg aria-hidden="true"><use href="#i-mic"/></svg> AI mock interview</a>
          <a href="<?= base_url('job-alerts') ?>"><svg aria-hidden="true"><use href="#i-bell"/></svg> Job alerts</a>
          <a href="<?= base_url('referral') ?>"><svg aria-hidden="true"><use href="#i-gift"/></svg> Referral rewards</a>
        </div>
      </div>
      
      <div id="hero-employer" hidden>
        <h2 class="hero-employer-h2">Hire top Nigerian talent — <em>fast</em></h2>
        <p class="hero-sub">Post your vacancy free and reach verified, pre-screened candidates across Lagos, Abuja, Port Harcourt, and every state in Nigeria.</p>
        <div class="hero-trust"><span><svg aria-hidden="true"><use href="#i-check"/></svg> Verified candidate pool</span><span><svg aria-hidden="true"><use href="#i-check"/></svg> Post your first job free</span><span><svg aria-hidden="true"><use href="#i-check"/></svg> Smart recruitment dashboard</span></div>
        <div class="hero-employer-actions">
          <a href="<?= base_url('employer/post-job') ?>" class="btn-m btn-m-accent"><svg aria-hidden="true"><use href="#i-edit"/></svg> Post a job free</a>
          <a href="<?= base_url('contact-us') ?>" class="btn-m btn-m-white">Let us help you recruit</a>
        </div>
        <div class="hero-pills" aria-label="Employer quick access">
          <a href="<?= base_url('job-ads') ?>"><svg aria-hidden="true"><use href="#i-bag"/></svg> View pricing plans</a>
          <a href="<?= base_url('candidates') ?>"><svg aria-hidden="true"><use href="#i-users"/></svg> Browse candidate database</a>
          <a href="<?= base_url('employer/post-job') ?>"><svg aria-hidden="true"><use href="#i-doc"/></svg> Job posting guide</a>
          <a href="<?= base_url('referral') ?>"><svg aria-hidden="true"><use href="#i-gift"/></svg> Referral rewards</a>
        </div>
      </div>
    </div>
    
    <!-- Ticker Track with Dynamic Duplicated Jobs for Seamless Scrolling -->
    <div class="ticker" aria-label="Latest jobs posted">
      <div class="ticker-label"><span class="ticker-dot" aria-hidden="true"></span><span>Just posted</span></div>
      <div class="ticker-viewport">
        <div class="ticker-track" id="ticker-track" aria-hidden="true">
          <?php
          if (!empty($jobs)) {
              $tickerJobs = array_merge($jobs, $jobs);
              foreach ($tickerJobs as $j):
                  $isNew = (strtotime($j->created_at) > strtotime('-3 days'));
                  $coName = !empty($j->anonymous) ? 'Confidential Employer' : ($j->employer_name ?? 'Company');
                  $coLogo = !empty($j->anonymous) ? base_url('images/favicon.png') : resolve_image_url($j->company_logo ?? '', 'company', $j->employer_name ?? 'Company');
                  ?>
                  <a class="ticker-item" href="<?= base_url('job/view/' . $j->id) ?>">
                    <?php if ($isNew): ?><span class="ticker-new">NEW</span><?php endif; ?>
                    <span class="ticker-role"><?= esc($j->title) ?></span>
                    <span class="ticker-co">· <?= esc($coName) ?></span>
                    <span class="ticker-loc"><svg aria-hidden="true"><use href="#i-pin"/></svg><?= esc($j->location ?? 'Nigeria') ?></span>
                  </a>
                  <?php
              endforeach;
          }
          ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== 2. RECENT JOBS GRID ========== -->
  <section class="section" id="jobs" aria-labelledby="jobs-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-star"/></svg> Hand-picked opportunities</p>
      <div class="jobs-header">
        <h2 class="section-title" id="jobs-h">Recent jobs in <span>Nigeria</span></h2>
        <div class="jobs-header-cta">
          <span class="jobs-total">Showing <strong><?= min(count($jobs), 8) ?></strong> of <strong class="live-count" data-count="<?= $activeJobsCount ?? 0 ?>"><?= number_format($activeJobsCount ?? 0) ?></strong>+ live jobs</span>
          <a href="<?= base_url('jobs') ?>" class="btn-m btn-m-outline">All featured jobs →</a>
        </div>
      </div>
      <p class="section-sub">Top companies are actively hiring. Don't miss your window.</p>
      
      <div class="jobs-grid">
        <?php if (!empty($jobs)): ?>
          <?php foreach (array_slice($jobs, 0, 8) as $job): 
              $isFeatured = !empty($job->is_featured);
              $coName = !empty($job->anonymous) ? 'Confidential Employer' : ($job->employer_name ?? 'Company');
              $coLogo = !empty($job->anonymous) ? base_url('images/favicon.png') : resolve_image_url($job->company_logo ?? '', 'company', $job->employer_name ?? 'Company');
              $timeAgo = time_ago($job->created_at);
          ?>
            <article class="job-card <?= $isFeatured ? 'job-card--featured' : '' ?>" aria-label="<?= esc($job->title) ?> – Jobs in Nigeria">
              <?php if ($isFeatured): ?>
                <span class="badge-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
              <?php endif; ?>
              <div class="job-card-top">
                <div>
                  <h3 class="job-title" title="<?= esc($job->title) ?>"><?= esc($job->title) ?></h3>
                  <div class="job-company">
                    <span class="job-company-name"><?= esc($coName) ?></span>
                    <?php if (!empty($job->show_trust_badge)): ?>
                      <button type="button" class="verified-check" aria-label="Verified employer — tap for details">
                        <svg aria-hidden="true"><use href="#i-verified-disc"/></svg>
                        <span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="job-logo" aria-hidden="true">
                  <?php if (!empty($coLogo)): ?>
                    <img src="<?= $coLogo ?>" alt="">
                  <?php else: ?>
                    <?= esc(substr($coName, 0, 2)) ?>
                  <?php endif; ?>
                </div>
              </div>
              <div class="job-meta">
                <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($job->location ?? 'Nigeria') ?></span>
                <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc($job->employment_type ?? 'Full-time') ?></span>
                <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= $timeAgo ?></span>
              </div>
              <div class="job-salary-row">
                <span class="job-salary">
                  <?php 
                  if (!empty($job->min_salary) && !empty($job->max_salary)) {
                      echo '₦' . number_format($job->min_salary) . ' - ₦' . number_format($job->max_salary);
                  } elseif (!empty($job->min_salary)) {
                      echo '₦' . number_format($job->min_salary);
                  } else {
                      echo 'Negotiable';
                  }
                  ?>
                </span>
              </div>
              <div class="job-actions">
                <a href="<?= base_url('job/view/' . $job->id) ?>" class="btn-m btn-m-primary">Quick apply</a>
                <button class="save-btn" data-job-id="<?= $job->id ?>" aria-label="Save job" data-saved="false">
                  <svg aria-hidden="true"><use href="#i-bookmark"/></svg> Save
                </button>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center py-5">
            <h5 class="text-muted">No recent jobs available at the moment.</h5>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ========== 3. BROWSE BY CATEGORY ========== -->
  <section class="section hiw-bg" id="categories" aria-labelledby="cat-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-bag"/></svg> Find your field</p>
      <div class="jobs-header">
        <h2 class="section-title" id="cat-h">Browse jobs by <span>category</span></h2>
        <a href="<?= base_url('jobs') ?>" class="btn-m btn-m-outline">All categories →</a>
      </div>
      <p class="section-sub">Explore opportunities across every industry in Nigeria — from technology to oil &amp; gas, healthcare, and beyond.</p>
      
      <div class="cat-grid">
        <?php
        $svgMap = [
            'Technology' => '#i-chip',
            'Finance' => '#i-bank',
            'Banking' => '#i-bank',
            'Marketing' => '#i-mega',
            'Engineering' => '#i-gear',
            'Oil & Gas' => '#i-drop',
            'Oil and Gas' => '#i-drop',
            'Healthcare' => '#i-heart-pulse',
            'Medical' => '#i-heart-pulse',
            'Education' => '#i-book',
            'Sales' => '#i-handshake'
        ];
        if (!empty($categories)): ?>
          <?php foreach (array_slice($categories, 0, 8) as $cat): 
              $svgIcon = '#i-bag'; // Fallback
              foreach ($svgMap as $key => $val) {
                  if (stripos($cat->name, $key) !== false) {
                      $svgIcon = $val;
                      break;
                  }
              }
          ?>
            <a href="<?= base_url('jobs?category_id=' . $cat->id) ?>" class="cat-card">
              <div class="cat-icon"><svg aria-hidden="true"><use href="<?= $svgIcon ?>"/></svg></div>
              <h3 class="cat-name"><?= esc($cat->name) ?></h3>
              <div class="cat-count"><span class="live-count" data-count="<?= $cat->job_count ?? 0 ?>"><?= number_format($cat->job_count ?? 0) ?></span> open roles</div>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ========== 4. BROWSE BY LOCATION ========== -->
  <section class="section" id="locations" aria-labelledby="loc-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-pin"/></svg> Across Nigeria</p>
      <h2 class="section-title" id="loc-h">Browse jobs by <span>location</span></h2>
      <p class="section-sub">Jobs in top cities and states across Nigeria — or work remotely from anywhere.</p>
      
      <div class="loc-grid">
        <?php if (!empty($top_locations)): ?>
          <?php foreach ($top_locations as $loc): 
              $locName = $loc->name;
              $svgIcon = '#i-building'; // Default
              if (stripos($locName, 'Lagos') !== false) $svgIcon = '#i-building';
              elseif (stripos($locName, 'Abuja') !== false || stripos($locName, 'FCT') !== false) $svgIcon = '#i-building';
              elseif (stripos($locName, 'Harcourt') !== false) $svgIcon = '#i-drop';
              elseif (stripos($locName, 'Remote') !== false) $svgIcon = '#i-globe';
          ?>
            <a href="<?= esc($loc->url) ?>" class="loc-card">
              <div class="loc-ic"><svg aria-hidden="true"><use href="<?= $svgIcon ?>"/></svg></div>
              <h3 class="loc-name"><?= esc($loc->name) ?></h3>
              <div class="loc-count"><span class="live-count" data-count="<?= $loc->job_count ?? 0 ?>"><?= number_format($loc->job_count ?? 0) ?></span> jobs</div>
            </a>
          <?php endforeach; ?>
          <a href="<?= base_url('jobs') ?>" class="loc-card featured">
            <div class="loc-ic"><svg aria-hidden="true"><use href="#i-pin"/></svg></div>
            <h3 class="loc-name">All 36 states</h3>
            <div class="loc-count">View all locations</div>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ========== 5. HOW IT WORKS ========== -->
  <section class="section hiw-bg" id="how-it-works" aria-labelledby="hiw-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-rocket"/></svg> How it works</p>
      <h2 class="section-title" id="hiw-h">Start your job search <span>in minutes</span></h2>
      <p class="section-sub">Four simple steps from sign-up to getting hired — no confusion, no waiting.</p>
      <div class="steps-grid">
        <div class="step-card"><div class="step-num" aria-hidden="true"></div><div class="step-ic"><svg aria-hidden="true"><use href="#i-edit"/></svg></div><div class="step-title">Create a free account</div><p class="step-desc">Sign up in under 60 seconds. No card needed. Instant access to verified jobs across Nigeria.</p></div>
        <div class="step-card"><div class="step-num" aria-hidden="true"></div><div class="step-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div><div class="step-title">Build your AI resume</div><p class="step-desc">Use the AI resume builder to create an ATS-optimised resume tailored to your target roles.</p></div>
        <div class="step-card"><div class="step-num" aria-hidden="true"></div><div class="step-ic"><svg aria-hidden="true"><use href="#i-search"/></svg></div><div class="step-title">Search &amp; apply</div><p class="step-desc">Filter by location, salary, category, or type. Apply to verified employers with a single click.</p></div>
        <div class="step-card"><div class="step-num" aria-hidden="true"></div><div class="step-ic"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></div><div class="step-title">Get hired</div><p class="step-desc">Practise with AI mock interviews, track your applications, and land your next role faster.</p></div>
      </div>
    </div>
  </section>

  <!-- ========== 6. FEATURED COURSES (DYNAMIC UPSKILL TEASER) ========== -->
  <section class="section training-bg" id="training" aria-labelledby="train-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-cap"/></svg> Upskill &amp; certify</p>
      <div class="jobs-header">
        <h2 class="section-title" id="train-h">Learn new skills &amp; earn <span>instant certificates</span></h2>
        <a href="<?= base_url('training') ?>" class="btn-m btn-m-outline">Browse courses →</a>
      </div>
      <p class="section-sub">Industry-relevant courses — paid courses come with an employer-recognised certificate. Learn at your own pace.</p>
      
      <div class="course-grid">
        <?php if (!empty($featured_courses)): ?>
          <?php foreach ($featured_courses as $c): 
              $isPaid = ($c->price > 0);
              $colorClasses = ['thumb-blue', 'thumb-purple', 'thumb-green', 'thumb-orange'];
              $gradientClass = $colorClasses[$c->id % count($colorClasses)];
              $imgUrl = !empty($c->thumbnail) ? base_url($c->thumbnail) : '';
          ?>
            <div class="course-card">
              <span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
              <div class="course-thumb <?= empty($imgUrl) ? $gradientClass : '' ?>">
                <?php if (!empty($imgUrl)): ?>
                  <img class="thumb-img" src="<?= $imgUrl ?>" alt="" loading="lazy">
                <?php else: ?>
                  <svg aria-hidden="true"><use href="#i-book"/></svg>
                <?php endif; ?>
              </div>
              <div class="course-body">
                <h3 class="course-title"><?= esc($c->title) ?></h3>
                <div class="course-meta">
                  <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= esc($c->duration) ?></span>
                  <span>
                    <?php if ($isPaid): ?>
                      <svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate
                    <?php else: ?>
                      <svg aria-hidden="true"><use href="#i-book"/></svg> <?= esc($c->level ?? 'Beginner') ?>
                    <?php endif; ?>
                  </span>
                </div>
              </div>
              <div class="course-footer">
                <?php if ($isPaid): ?>
                  <span class="course-cert"><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span>
                <?php endif; ?>
                <div class="course-price-row">
                  <span class="course-price <?= $isPaid ? 'course-price--paid' : 'course-price--free' ?>">
                    <?= $isPaid ? '₦' . number_format($c->price) : 'Free' ?>
                  </span>
                  <a href="<?= base_url('training') ?>" class="btn-m btn-m-primary btn-m-sm">
                    <?= $isPaid ? 'Enrol now' : 'Enrol free' ?>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="course-card"><span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span><div class="course-thumb"><img class="thumb-img" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=240&fit=crop" alt="" loading="lazy"></div><div class="course-body"><h3 class="course-title">Data Analysis with Excel &amp; Python</h3><div class="course-meta"><span><svg aria-hidden="true"><use href="#i-clock"/></svg> 8 hours</span><span><svg aria-hidden="true"><use href="#i-book"/></svg> Beginner</span></div></div><div class="course-footer"><div class="course-price-row"><span class="course-price course-price--free">Free</span><a href="<?= base_url('training') ?>" class="btn-m btn-m-primary btn-m-sm">Enrol free</a></div></div></div>
          <div class="course-card"><span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span><div class="course-thumb thumb-purple"><svg aria-hidden="true"><use href="#i-mega"/></svg></div><div class="course-body"><h3 class="course-title">Digital Marketing Fundamentals</h3><div class="course-meta"><span><svg aria-hidden="true"><use href="#i-clock"/></svg> 6 hours</span><span><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span></div></div><div class="course-footer"><span class="course-cert"><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span><div class="course-price-row"><span class="course-price course-price--paid">₦15,000</span><a href="<?= base_url('training') ?>" class="btn-m btn-m-primary btn-m-sm">Enrol now</a></div></div></div>
          <div class="course-card"><span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span><div class="course-thumb thumb-green"><svg aria-hidden="true"><use href="#i-bag"/></svg></div><div class="course-body"><h3 class="course-title">Project Management Essentials</h3><div class="course-meta"><span><svg aria-hidden="true"><use href="#i-clock"/></svg> 10 hours</span><span><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span></div></div><div class="course-footer"><span class="course-cert"><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span><div class="course-price-row"><span class="course-price course-price--paid">₦25,000</span><a href="<?= base_url('training') ?>" class="btn-m btn-m-primary btn-m-sm">Enrol now</a></div></div></div>
          <div class="course-card"><span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span><div class="course-thumb thumb-orange"><svg aria-hidden="true"><use href="#i-lock"/></svg></div><div class="course-body"><h3 class="course-title">Cybersecurity for Beginners</h3><div class="course-meta"><span><svg aria-hidden="true"><use href="#i-clock"/></svg> 12 hours</span><span><svg aria-hidden="true"><use href="#i-book"/></svg> Beginner</span></div></div><div class="course-footer"><div class="course-price-row"><span class="course-price course-price--free">Free</span><a href="<?= base_url('training') ?>" class="btn-m btn-m-primary btn-m-sm">Enrol free</a></div></div></div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ========== 7. SUCCESS STORIES (DYNAMIC TESTIMONIALS) ========== -->
  <section class="section" id="testimonials" aria-labelledby="testi-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-star"/></svg> Success stories</p>
      <h2 class="section-title" id="testi-h">Nigerians are <span>getting hired</span></h2>
      <p class="section-sub">Real people, real careers. Here's what they're saying about JobberRecruit.</p>
      
      <div class="testi-grid">
        <!-- Hardcoded testimonials from the reference design — always shown first -->
        <article class="testi-card"><div class="testi-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div><blockquote class="testi-text">"I had been job hunting for 8 months with no luck. I used the AI resume builder on JobberRecruit and got two interview calls within a week. I accepted an offer 3 weeks later!"</blockquote><div class="testi-author"><div class="testi-avatar" aria-hidden="true">AO</div><div><div class="testi-name">Adesola Okafor</div><div class="testi-role">Software Engineer · Lagos</div></div></div></article>
        <article class="testi-card"><div class="testi-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div><blockquote class="testi-text">"The AI mock interview feature is genuinely brilliant. It asked me the exact types of questions I faced in my real interview. I walked in so much more confident."</blockquote><div class="testi-author"><div class="testi-avatar" aria-hidden="true">CE</div><div><div class="testi-name">Chioma Ezenwachi</div><div class="testi-role">Finance Analyst · Abuja</div></div></div></article>
        <article class="testi-card"><div class="testi-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div><blockquote class="testi-text">"I earned my Digital Marketing certificate on JobberRecruit in 3 days. My employer specifically mentioned it during onboarding. Absolutely worth it."</blockquote><div class="testi-author"><div class="testi-avatar" aria-hidden="true">TI</div><div><div class="testi-name">Tunde Idowu</div><div class="testi-role">Marketing Manager · Port Harcourt</div></div></div></article>
        <article class="testi-card"><div class="testi-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div><blockquote class="testi-text">"I found my dream job through JobberRecruit in less than two weeks. The job alerts matched my skills perfectly and the application process was seamless."</blockquote><div class="testi-author"><div class="testi-avatar" aria-hidden="true">FK</div><div><div class="testi-name">Fatima Kabir</div><div class="testi-role">Data Analyst · Kano</div></div></div></article>
        <!-- Dynamic DB testimonials appended after hardcoded ones -->
        <?php if (!empty($testimonials)): ?>
          <?php foreach (array_slice($testimonials, 0, 10) as $t): 
              // Skip dummy entries
              $skipNames = ['john doe', 'james smith'];
              if (in_array(strtolower(trim($t->name ?? '')), $skipNames)) continue;
              $initials = '';
              $parts = explode(' ', $t->name ?? '');
              foreach ($parts as $p) {
                  $initials .= substr($p, 0, 1);
              }
              $initials = strtoupper(substr($initials, 0, 2));
          ?>
            <article class="testi-card">
              <div class="testi-stars" aria-label="5 out of 5 stars">
                <?php for ($i=0; $i<5; $i++): ?><svg aria-hidden="true"><use href="#i-star"/></svg><?php endfor; ?>
              </div>
              <blockquote class="testi-text">"<?= esc($t->testimonial) ?>"</blockquote>
              <div class="testi-author">
                <div class="testi-avatar" aria-hidden="true"><?= esc($initials) ?></div>
                <div>
                  <div class="testi-name"><?= esc($t->name) ?></div>
                  <div class="testi-role"><?= esc($t->role ?? 'Jobber') ?></div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ========== 8. NEWSLETTER ========== -->
  <section class="newsletter-band" id="newsletter" aria-labelledby="news-h">
    <div class="container newsletter-inner">
      <div class="newsletter-text">
        <p class="section-label"><svg aria-hidden="true"><use href="#i-mail"/></svg> Newsletter</p>
        <h2 class="newsletter-title" id="news-h">Career tips &amp; job market insights, <span>straight to your inbox</span></h2>
        <p class="newsletter-sub">Weekly advice on landing roles, salary trends, and hiring in Nigeria. No account needed — unsubscribe anytime.</p>
      </div>
      <form class="newsletter-form" action="<?= base_url('newsletter/subscribe') ?>" method="post" aria-label="Newsletter signup">
        <label for="news-email" class="sr-only">Email address</label>
        <div class="newsletter-field">
          <svg aria-hidden="true"><use href="#i-mail"/></svg>
          <input id="news-email" type="email" name="email" placeholder="Enter your email" required autocomplete="email">
        </div>
        <button type="submit" class="btn-m btn-m-primary">Subscribe</button>
      </form>
    </div>
  </section>

  <!-- ========== 9. REFERRAL BAND ========== -->
  <section class="section" id="referral" aria-labelledby="ref-h">
    <div class="container">
      <div class="referral-band">
        <div class="referral-band-text">
          <div class="ref-ic ref-ic--lg"><svg aria-hidden="true"><use href="#i-gift"/></svg></div>
          <div>
            <h2 class="referral-band-title" id="ref-h">Refer &amp; earn <span>10% commission</span> — no limit</h2>
            <p class="referral-band-sub">Sign up, share your link, and earn 10% every time someone you refer makes their first payment. Earnings go to your wallet and can be spent on JobberRecruit services (wallet funds are non-withdrawable).</p>
          </div>
        </div>
        <a href="<?= base_url('register') ?>" class="btn-m btn-m-accent referral-band-cta"><svg aria-hidden="true"><use href="#i-gift"/></svg> Start earning</a>
      </div>
    </div>
  </section>

  <!-- ========== 10. FAQ ========== -->
  <section class="section faq-bg" id="faq" aria-labelledby="faq-h">
    <div class="container">
      <p class="section-label text-center" style="margin-left:auto;margin-right:auto"><svg aria-hidden="true"><use href="#i-bulb"/></svg> Got questions?</p>
      <h2 class="section-title text-center" id="faq-h">Frequently asked <span>questions</span></h2>
      <div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">Is JobberRecruit free to use for job seekers?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">Yes. Creating an account, searching for jobs, setting up job alerts, and applying to listings are all completely free for job seekers on JobberRecruit.</p></div></details>
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">How do I search for jobs in Lagos, Abuja, or Port Harcourt?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">Use the search bar on the homepage. Select your preferred city from the Location dropdown, enter a job title or keyword, and click Search jobs. You can also browse by location directly from the Locations section.</p></div></details>
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">What is the AI Resume Builder?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">The AI Resume Builder is a smart tool that helps you create a professional, ATS-optimised resume in minutes. It tailors your resume to specific job roles and industries in Nigeria, helping you pass automated screening and stand out to employers.</p></div></details>
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">Can employers post jobs for free on JobberRecruit?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">Yes. Employers can post their first job listing for free. Premium plans offer additional visibility and featured placements. Access to the verified candidate database is a separate paid product and is not included in premium plans. Visit our Pricing page for full details.</p></div></details>
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">How does the JobberRecruit referral programme work?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">Share your unique referral link with friends, candidates, or employers. When someone you referred makes their first qualifying payment, you instantly receive 10% commission added to your JobberRecruit wallet. There is no limit on referrals. Please note: all wallet funds — whether earned through referrals or added by you — are non-withdrawable and non-refundable. Wallet balance cannot be cashed out, but can be used to pay for JobberRecruit services such as job postings, premium plans, and certificates.</p></div></details>
      </div>
      <div class="faq-more"><a href="<?= base_url('faq') ?>" class="btn-m btn-m-outline">See all FAQs →</a></div>
    </div>
  </section>

  <!-- ========== 11. DUAL CALL TO ACTION ========== -->
  <section class="section" aria-label="Call to action">
    <div class="container">
      <div class="dual-cta">
        <div class="cta-panel blue">
          <div class="cta-ic"><svg aria-hidden="true"><use href="#i-bag"/></svg></div>
          <h2>Ready to find your next job?</h2>
          <p>Join the growing community of Nigerians using JobberRecruit to land great roles faster.</p>
          <ul class="cta-list" aria-label="Job seeker benefits">
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> AI resume builder</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> AI mock interviews</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Personalised career advice</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Smart job alerts</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Training &amp; certificates</li>
          </ul>
          <a href="<?= base_url('register') ?>" class="btn-m btn-m-accent">Create free account →</a>
        </div>
        <div class="cta-panel light">
          <div class="cta-ic"><svg aria-hidden="true"><use href="#i-rocket"/></svg></div>
          <h2>Looking to hire? Post a job free</h2>
          <p>Post your vacancy free and reach verified candidates across Nigeria.</p>
          <ul class="cta-list" aria-label="Employer benefits">
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Post your first job free</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Recruitment dashboard</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Smart application management</li>
            <li class="cta-list-paid"><svg aria-hidden="true"><use href="#i-check"/></svg> Verified candidate database <span class="cta-tag">Paid</span></li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Referral rewards</li>
          </ul>
          <a href="<?= base_url('employer/post-job') ?>" class="btn-m btn-m-primary">Post a job free →</a>
        </div>
      </div>
    </div>
  </section>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
window.JR_USER = {
  loggedIn: <?= json_encode($auth->loggedIn()) ?>
};

function switchHero(btn, mode) {
  document.querySelectorAll('.hero-tabs button').forEach(b => {
    b.classList.remove('active');
    b.setAttribute('aria-pressed', 'false');
  });
  btn.classList.add('active');
  btn.setAttribute('aria-pressed', 'true');
  const seeker = document.getElementById('hero-seeker');
  const employer = document.getElementById('hero-employer');
  seeker.hidden = (mode !== 'seeker');
  employer.hidden = (mode !== 'employer');
  const shown = (mode === 'seeker') ? seeker : employer;
  shown.style.animation = 'none';
  void shown.offsetWidth;
  shown.style.animation = '';
}

document.querySelectorAll('.verified-check').forEach(btn => {
  btn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    const wasOpen = btn.classList.contains('open');
    document.querySelectorAll('.verified-check.open').forEach(o => o.classList.remove('open'));
    if (!wasOpen) btn.classList.add('open');
  });
});
document.addEventListener('click', () => {
  document.querySelectorAll('.verified-check.open').forEach(o => o.classList.remove('open'));
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.verified-check.open').forEach(o => o.classList.remove('open'));
  }
});

const BM = '<svg aria-hidden="true"><use href="#i-bookmark"/></svg> Save';
const BMF = '<svg aria-hidden="true"><use href="#i-bookmark-fill"/></svg> Saved';

function isLoggedIn() {
  return !!(window.JR_USER && window.JR_USER.loggedIn);
}
function renderSaveBtn(btn, saved) {
  btn.dataset.saved = String(saved);
  btn.innerHTML = saved ? BMF : BM;
  btn.setAttribute('aria-label', saved ? 'Job saved — click to remove' : 'Save job');
}
function redirectToLogin(jobId) {
  const here = window.location.pathname + window.location.search;
  const ret = encodeURIComponent(here + (here.includes('?') ? '&' : '?') + 'save=' + encodeURIComponent(jobId));
  window.location.href = '/login?redirect=' + ret;
}
function persistSave(jobId, save) {
  return fetch('<?= base_url('jobs/toggle-save') ?>/' + jobId, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  }).then(r => r.ok ? r.json() : Promise.reject(r.status));
}

document.querySelectorAll('.save-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const jobId = btn.dataset.jobId || '';
    if (!isLoggedIn()) {
      redirectToLogin(jobId);
      return;
    }
    const wasSaved = btn.dataset.saved === 'true';
    const next = !wasSaved;
    renderSaveBtn(btn, next);
    btn.disabled = true;
    persistSave(jobId, next).then(data => {
      btn.disabled = false;
      if (data && data.success) {
        renderSaveBtn(btn, data.saved);
      } else {
        renderSaveBtn(btn, wasSaved);
      }
    }).catch(() => {
      renderSaveBtn(btn, wasSaved);
      btn.disabled = false;
      btn.setAttribute('aria-label', 'Could not save — try again');
    });
  });
});

(function resumePendingSave() {
  if (!isLoggedIn()) return;
  const pending = new URLSearchParams(window.location.search).get('save');
  if (!pending) return;
  const btn = document.querySelector('.save-btn[data-job-id="' + pending + '"');
  if (btn && btn.dataset.saved !== 'true') {
    renderSaveBtn(btn, true);
    persistSave(pending, true).catch(() => renderSaveBtn(btn, false));
  }
})();
</script>

<!-- SVG Icon Sprites -->
<svg xmlns="http://www.w3.org/2000/svg" style="display:none">
  <symbol id="i-rocket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M5 13c-1.5.5-3 2.5-3 5 2.5 0 4.5-1.5 5-3"/><path d="M13 7a8 8 0 0 1 7-4 8 8 0 0 1-4 7l-4 3-2-2Z"/><path d="m9 11-3 3 4 4 3-3M15 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/>
  </symbol>
  <symbol id="i-bag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
  </symbol>
  <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="20 6 9 17 4 12"/>
  </symbol>
  <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
  </symbol>
</svg>

<?= $this->endSection() ?>
