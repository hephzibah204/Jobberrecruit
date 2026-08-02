<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Service',
    'name'     => 'Professional CV Review Service',
    'serviceType' => 'CV / Resume Review',
    'provider' => [
        '@type' => 'Organization',
        'name'  => 'JobberRecruit',
        'url'   => 'https://www.jobberrecruit.com',
    ],
    'areaServed' => [
        '@type' => 'Country',
        'name'  => 'Nigeria',
    ],
    'description' => 'Professional CV review by certified HR professionals and recruitment experts, including ATS compatibility scanning, structure and formatting checks, keyword analysis, and line-by-line feedback.',
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => '4.9',
        'reviewCount' => '1024',
        'bestRating' => '5',
    ],
    'offers' => [
        [
            '@type' => 'Offer',
            'name'  => 'Basic Review',
            'price' => $planPrices['basic'],
            'priceCurrency' => 'NGN',
            'description' => 'AI-powered ATS compatibility and formatting analysis.',
        ],
        [
            '@type' => 'Offer',
            'name'  => 'Professional Review',
            'price' => $planPrices['professional'],
            'priceCurrency' => 'NGN',
            'description' => 'Full human expert analysis with line-by-line feedback and rewritten sections.',
        ],
        [
            '@type' => 'Offer',
            'name'  => 'Premium Review',
            'price' => $planPrices['premium'],
            'priceCurrency' => 'NGN',
            'description' => 'Everything in Professional plus a 1-on-1 consultation call and cover letter.',
        ],
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https://www.jobberrecruit.com/"},{"@type":"ListItem","position":2,"name":"Training","item":"https://www.jobberrecruit.com/training"},{"@type":"ListItem","position":3,"name":"CV Review","item":"https://www.jobberrecruit.com/cv-review"}]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"How long does a CV review take?","acceptedAnswer":{"@type":"Answer","text":"Basic reviews are completed within 48 hours, Professional reviews within 24 hours, and Premium reviews within 12 hours. Express options are available for urgent requests."}},{"@type":"Question","name":"What file formats do you accept?","acceptedAnswer":{"@type":"Answer","text":"We accept PDF, DOC, and DOCX files up to 5MB. For best results we recommend uploading a PDF version of your CV."}},{"@type":"Question","name":"What is the difference between the review tiers?","acceptedAnswer":{"@type":"Answer","text":"Basic is an AI-powered analysis covering ATS compatibility, structure, and keywords. Professional adds a full human expert review with line-by-line feedback and rewritten sections. Premium adds a 1-on-1 consultation call and a tailored cover letter."}},{"@type":"Question","name":"Will my CV be kept confidential?","acceptedAnswer":{"@type":"Answer","text":"Yes. All uploaded documents are encrypted in transit and at rest, accessible only to your assigned reviewer, and never shared with third parties. You can request deletion at any time."}},{"@type":"Question","name":"What if I am not satisfied with the review?","acceptedAnswer":{"@type":"Answer","text":"Your satisfaction is our priority. If you are not happy with your review we will re-review your CV for free, and Premium includes a consultation call to address your concerns."}},{"@type":"Question","name":"Do you review CVs for specific industries?","acceptedAnswer":{"@type":"Answer","text":"Yes. Our experts cover technology, finance, healthcare, education, marketing, sales, government, and consulting. Select your target industry when you upload and we match you with the most relevant reviewer."}}]}
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* ── Reset ── */


/* ── Brand Tokens ── */




html, 



img { max-width: 100%; height: auto; display: block; }
svg { flex-shrink: 0; }

/* ── Utility ── */
.container { max-width: 1160px; margin: 0 auto; padding: 0 20px; }
.section   { padding: 76px 0; background-color: #f5f7fb; }
.section.white-bg { background-color: #ffffff; }
.sr-only   { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
.text-center { text-align: center; }
.ic { display: inline-flex; align-items: center; justify-content: center; line-height: 0; }

.section-label {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .72rem; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: var(--brand);
  background: var(--brand-light); padding: 5px 13px;
  border-radius: 20px; margin-bottom: 14px;
}
.section-label svg { width: 13px; height: 13px; }
.section-title {
  font-size: clamp(1.6rem, 2.9vw, 2.25rem);
  font-weight: 800; line-height: 1.15; margin-bottom: 12px; color: #141926;
}
.section-title span { color: var(--brand); }
.section-sub { color: var(--muted); font-size: .95rem; max-width: 560px; }

/* Buttons */













.badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
.badge svg { width: 12px; height: 12px; }
.badge-blue  { background: var(--brand-light); color: var(--brand); }
.badge-accent { background: #fef3c7; color: var(--accent-dark); }
.badge-green  { background: #ecfdf5; color: #15803d; }

/* Skip link */
.skip-link {
  position: absolute; top: -50px; left: 16px;
  background: var(--brand); color: var(--white);
  padding: 8px 16px; border-radius: 0 0 6px 6px;
  font-weight: 600; z-index: 9999; transition: top .2s;
}
.skip-link:focus { top: 0; }


/* ══ NAVBAR ══ */


.nav-logo { display: flex; align-items: center; text-decoration: none; flex-shrink: 0; }
.nav-logo img { height: 60px; width: auto; display: block; }






.nav-caret { width: 13px; height: 13px; transition: transform .18s; }





.mob-group-label { font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); padding: 4px 0; margin-top: 6px; }
.mob-group 
.nav-actions { display: flex; align-items: center; gap: 8px; }
.nav-actions 
.nav-actions 




.blog-hero-grid {
  position: absolute; inset: 0; pointer-events: none; opacity: .45;
  background-image:
    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size: 46px 46px;
  -webkit-mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%);
          mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%);
}
.blog-hero-inner { position: relative; z-index: 1; }
.blog-hero-tag {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .72rem; font-weight: 700; letter-spacing: .12em;
  text-transform: uppercase; color: var(--accent); margin-bottom: 18px;
}
.blog-hero-tag svg { width: 14px; height: 14px; }
.blog-hero h1 {
  font-size: clamp(2rem, 5vw, 3.2rem);
  font-weight: 800; line-height: 1.1; margin-bottom: 16px;
}
.blog-hero h1 em { font-style: normal; color: var(--accent); }
.blog-hero-sub {
  font-size: 1.05rem; opacity: .9; max-width: 580px; margin-bottom: 32px;
}
.blog-stats {
  display: flex; flex-wrap: wrap; gap: 24px; margin-bottom: 36px;
}
.blog-stat {
  background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18);
  border-radius: 10px; padding: 16px 24px; text-align: center; min-width: 130px;
}
.blog-stat-num {
  font-family: 'Sora', sans-serif; font-size: 1.9rem; font-weight: 800;
  color: var(--accent); line-height: 1;
}
.blog-stat-label { font-size: .78rem; opacity: .8; margin-top: 4px; }

/* Blog search */
.blog-search-bar {
  display: flex; gap: 0; max-width: 560px;
  background: var(--white); border-radius: 10px;
  box-shadow: var(--shadow-lg); overflow: hidden;
}
.blog-search-bar svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; color: var(--muted); pointer-events: none; }
.blog-search-wrap { position: relative; flex: 1; }
.blog-search-bar input {
  width: 100%; border: none; outline: none;
  padding: 13px 14px 13px 42px;
  font-family: 'Inter', sans-serif; font-size: .95rem;
  color: var(--text); background: transparent; min-height: 50px;
}
.blog-search-bar button {
  flex-shrink: 0; padding: 0 22px;
  background: var(--accent); color: var(--brand-deep);
  border: none; font-family: 'Inter', sans-serif; font-size: .88rem; font-weight: 700;
  cursor: pointer; transition: var(--transition); min-height: 50px;
  display: inline-flex; align-items: center; gap: 7px;
}
.blog-search-bar button:hover { background: var(--accent-dark); }
.blog-search-bar button svg { position: static; transform: none; width: 16px; height: 16px; color: inherit; }

/* ── BREADCRUMB ── */
.breadcrumb {
  display: flex; align-items: center; gap: 6px;
  font-size: .78rem; color: var(--muted); padding: 14px 0; flex-wrap: wrap;
}
.breadcrumb 
.breadcrumb 
.breadcrumb-sep { color: var(--muted); opacity: .5; }

/* ══ TOPIC PILLS ══ */
.topic-pills {
  display: flex; flex-wrap: wrap; gap: 8px; margin: 24px 0 0;
}
.topic-pill {
  padding: 7px 16px; border-radius: 20px; font-size: .78rem; font-weight: 600;
  border: 1.5px solid var(--border); color: var(--text); background: var(--white);
  cursor: pointer; transition: var(--transition); text-decoration: none;
  min-height: 36px; display: inline-flex; align-items: center; gap: 6px;
}
.topic-pill:hover, .topic-pill.active { background: var(--brand); color: var(--white); border-color: var(--brand); text-decoration: none; }
.topic-pill.active-accent { background: var(--accent); color: var(--brand-deep); border-color: var(--accent); }

/* ══ BLOG LAYOUT ══ */
.blog-layout { display: grid; grid-template-columns: 1fr 320px; gap: 36px; align-items: start; }

/* ── ARTICLE CARDS ── */
.article-list { display: flex; flex-direction: column; gap: 28px; }
.article-card {
  background: var(--white); border: 1px solid var(--border); border-radius: 12px;
  overflow: hidden; display: flex; gap: 0; transition: var(--transition);
  flex-direction: column;
}
.article-card:hover { box-shadow: var(--shadow-lg); border-color: var(--brand); transform: translateY(-3px); }
.article-card:hover { text-decoration: none; }
.article-card--featured { border-left: 4px solid var(--accent); background: linear-gradient(180deg, #fffbf2, #fff); }
.article-card--featured:hover { border-left-color: var(--accent-dark); border-color: var(--accent); }

.article-thumb {
  width: 100%; height: 200px; overflow: hidden; position: relative;
  background: linear-gradient(135deg, #0A2F57, #0D609E);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.article-thumb svg { width: 56px; height: 56px; color: rgba(255,255,255,.28); }
.article-thumb-label {
  position: absolute; top: 14px; left: 14px;
  background: var(--accent); color: var(--brand-deep);
  font-size: .68rem; font-weight: 800; padding: 4px 11px; border-radius: 20px;
  letter-spacing: .04em; display: inline-flex; align-items: center; gap: 5px;
}
.article-thumb-label svg { width: 11px; height: 11px; }
.article-thumb-read {
  position: absolute; top: 14px; right: 14px;
  background: rgba(10,47,87,.7); color: rgba(255,255,255,.9);
  font-size: .7rem; font-weight: 600; padding: 4px 11px; border-radius: 20px;
  backdrop-filter: blur(4px);
}

.article-
.article-met
.article-meta span { display: inline-flex; align-items: center; gap: 5px; }
.article-meta svg { width: 13px; height: 13px; }
.article-topic {
  display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px;
  font-size: .7rem; font-weight: 700; background: var(--brand-light); color: var(--brand);
}
.article-topic svg { width: 11px; height: 11px; }
.article-title {
  font-family: 'Sora', sans-serif;
  font-size: 1.12rem; font-weight: 700; line-height: 1.3;
  color: #141926; margin: 0;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.article-card:hover .article-title { color: var(--brand); }
.article-excerpt {
  font-size: .87rem; color: var(--muted); line-height: 1.7;
  display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.article-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 4px; flex-wrap: wrap; }
.article-author { display: flex; align-items: center; gap: 9px; }
.article-avatar {
  width: 34px; height: 34px; border-radius: 50%;
  background: var(--brand); color: var(--white);
  font-family: 'Sora', sans-serif; font-weight: 700; font-size: .78rem;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.article-avatar--accent { background: var(--accent); color: var(--brand-deep); }
.article-avatar--deep { background: var(--brand-deep); }
.article-author-name { font-weight: 600; font-size: .82rem; }
.article-author-role { font-size: .72rem; color: var(--muted); }
.article-read-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: .82rem; font-weight: 700; color: var(--brand);
  flex-shrink: 0; transition: var(--transition);
}
.article-read-link svg { width: 15px; height: 15px; transition: transform .18s; }
.article-card:hover .article-read-link { color: var(--accent-dark); }
.article-card:hover .article-read-link svg { transform: translateX(3px); }

/* Horizontal card variant (featured top story) */
.article-card--hero { flex-direction: row; }
.article-card--hero .article-thumb { width: 320px; height: auto; min-height: 240px; flex-shrink: 0; }
.article-card--hero .article-

/* ══ SIDEBAR ══ */
.blog-sidebar { display: flex; flex-direction: column; gap: 24px; }
.sidebar-card {
  background: var(--white); border: 1px solid var(--border);
  border-radius: 12px; overflow: hidden;
}
.sidebar-card-header {
  padding: 16px 20px 14px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 8px;
}
.sidebar-card-header h3 {
  font-family: 'Sora', sans-serif; font-size: .88rem; font-weight: 700; color: var(--text);
}
.sidebar-card-header svg { width: 16px; height: 16px; color: var(--brand); }
.sidebar-card-

/* Trending list */
.trending-list { display: flex; flex-direction: column; gap: 0; }
.trending-item {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 12px 0; border-bottom: 1px solid var(--border);
  text-decoration: none; transition: var(--transition);
}
.trending-item:last-child { border-bottom: none; padding-bottom: 0; }
.trending-item:hover { text-decoration: none; }
.trending-item:hover .trending-title { color: var(--brand); }
.trending-num {
  font-family: 'Sora', sans-serif; font-size: 1.3rem; font-weight: 800;
  color: var(--brand-light); line-height: 1; flex-shrink: 0; min-width: 24px;
}
.trending-item:first-child .trending-num { color: var(--accent); }
.trending-title { font-size: .84rem; font-weight: 600; color: var(--text); line-height: 1.4; margin-bottom: 4px; }
.trending-met

/* Topics cloud */
.topics-cloud { display: flex; flex-wrap: wrap; gap: 7px; }
.topic-tag {
  padding: 5px 12px; border-radius: 20px; font-size: .74rem; font-weight: 600;
  border: 1.5px solid var(--border); color: var(--muted); background: var(--bg);
  text-decoration: none; transition: var(--transition);
}
.topic-tag:hover { background: var(--brand); color: var(--white); border-color: var(--brand); text-decoration: none; }
.topic-tag.t-blue   { background: var(--brand-light); color: var(--brand); border-color: var(--brand-light); }
.topic-tag.t-orange { background: #fef3c7; color: var(--accent-dark); border-color: #fde68a; }
.topic-tag.t-green  { background: #ecfdf5; color: #15803d; border-color: #a7f3d0; }

/* Newsletter sidebar card */
.sidebar-newsletter { background: linear-gradient(150deg, #0A2F57, var(--brand)); color: var(--white); border: none; }
.sidebar-newsletter .sidebar-card-header { border-color: rgba(255,255,255,.12); }
.sidebar-newsletter .sidebar-card-header h3 { color: var(--white); }
.sidebar-newsletter .sidebar-card-header svg { color: var(--accent); }
.sidebar-newsletter .sidebar-card-body p { font-size: .83rem; opacity: .85; margin-bottom: 14px; }
.sidebar-nl-form { display: flex; flex-direction: column; gap: 8px; }
.sidebar-nl-form input {
  width: 100%; padding: 10px 14px; border-radius: 8px;
  border: 1px solid rgba(255,255,255,.2); background: rgba(255,255,255,.1);
  color: var(--white); font-family: 'Inter', sans-serif; font-size: .85rem; outline: none;
}
.sidebar-nl-form input::placeholder { color: rgba(255,255,255,.5); }
.sidebar-nl-form input:focus { border-color: var(--accent); background: rgba(255,255,255,.16); }
.sidebar-nl-form button {
  width: 100%; padding: 10px; border-radius: 8px;
  background: var(--accent); color: var(--brand-deep); border: none;
  font-family: 'Inter', sans-serif; font-size: .85rem; font-weight: 700;
  cursor: pointer; transition: var(--transition);
}
.sidebar-nl-form button:hover { background: var(--accent-dark); }
.sidebar-nl-disclaimer { font-size: .7rem; opacity: .55; margin-top: 6px; }

/* AI tools sidebar */
.sidebar-tools-list { display: flex; flex-direction: column; gap: 10px; }
.sidebar-tool {
  display: flex; align-items: center; gap: 12px;
  padding: 11px 14px; border-radius: 9px;
  background: var(--bg); border: 1px solid var(--border);
  text-decoration: none; transition: var(--transition);
}
.sidebar-tool:hover { border-color: var(--brand); background: var(--brand-light); text-decoration: none; }
.sidebar-tool-ic {
  width: 36px; height: 36px; border-radius: 9px;
  background: var(--brand); color: var(--white);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.sidebar-tool-ic.accent { background: var(--accent); color: var(--brand-deep); }
.sidebar-tool-ic svg { width: 18px; height: 18px; }
.sidebar-tool-name { font-weight: 600; font-size: .83rem; color: var(--text); }
.sidebar-tool-desc { font-size: .73rem; color: var(--muted); }

/* Referral sidebar */
.sidebar-referral {
  background: linear-gradient(120deg, #fffbeb, #fef3c7);
  border: 1px solid #fde68a; border-radius: 12px; padding: 20px;
}
.sidebar-referral-ic {
  width: 44px; height: 44px; border-radius: 12px;
  background: var(--accent); color: var(--brand-deep);
  display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
}
.sidebar-referral-ic svg { width: 22px; height: 22px; }
.sidebar-referral h3 {
  font-family: 'Sora', sans-serif; font-size: .95rem; font-weight: 800;
  line-height: 1.25; margin-bottom: 6px;
}
.sidebar-referral h3 span { color: var(--accent-dark); }
.sidebar-referral p { font-size: .8rem; color: var(--muted); margin-bottom: 14px; }

/* ══ LOAD MORE ══ */
.load-more-wrap { text-align: center; margin-top: 8px; }

/* ══ NEWSLETTER BAND ══ */
.newsletter-band { background: linear-gradient(120deg, var(--brand-light) 0%, #dce9f8 100%); padding: 52px 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
.newsletter-inner { display: flex; align-items: center; justify-content: space-between; gap: 36px; flex-wrap: wrap; }
.newsletter-text { flex: 1 1 380px; }
.newsletter-text .section-label { margin-bottom: 10px; }
.newsletter-title { font-family: 'Sora', sans-serif; font-size: clamp(1.3rem, 2.4vw, 1.7rem); font-weight: 800; line-height: 1.2; letter-spacing: -.02em; margin-bottom: 8px; color: var(--brand-deep); }
.newsletter-title span { color: var(--brand); }
.newsletter-sub { font-size: .88rem; color: var(--muted); max-width: 460px; }
.newsletter-form { display: flex; gap: 8px; flex-wrap: wrap; flex: 0 1 440px; }
.newsletter-form input {
  flex: 1 1 220px; padding: 13px 16px; border-radius: 8px;
  border: 1.5px solid var(--border); font-family: 'Inter', sans-serif; font-size: .9rem;
  color: var(--text); background: var(--white); outline: none;
}
.newsletter-form input:focus { border-color: var(--brand); }
.newsletter-form button {
  flex-shrink: 0; padding: 13px 22px; background: var(--brand); color: var(--white);
  border: none; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: .88rem;
  font-weight: 700; cursor: pointer; transition: var(--transition);
  display: inline-flex; align-items: center; gap: 7px;
}
.newsletter-form button:hover { background: var(--brand-dark); }
.newsletter-form button svg { width: 16px; height: 16px; }
.newsletter-disclaimer { font-size: .72rem; color: var(--muted); margin-top: 8px; width: 100%; }

/* ══ DUAL CTA ══ */
.dual-ct
.cta-panel { border-radius: 12px; padding: 44px 32px; }
.cta-panel.blue { background: linear-gradient(150deg, #0A2F57, var(--brand)); color: var(--white); }
.cta-panel.blue h2, .cta-panel.blue p, .cta-panel.blue li, .cta-panel.blue strong, .cta-panel.blue a { color: var(--white) !important; }
.cta-panel.light { background: var(--white); color: var(--text); border: 1px solid var(--border); }
.cta-ic { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
.cta-panel.blue .cta-ic { background: rgba(255,255,255,.14); color: var(--white); }
.cta-panel.light .cta-ic { background: var(--brand-light); color: var(--brand); }
.cta-ic svg { width: 25px; height: 25px; }
.cta-panel h2 { font-size: 1.35rem; font-weight: 700; margin-bottom: 10px; }
.cta-panel p { font-size: .87rem; margin-bottom: 22px; }
.cta-panel.blue p { opacity: .86; }
.cta-panel.light p { color: var(--muted); }
.cta-list { list-style: none; margin-bottom: 26px; display: flex; flex-direction: column; gap: 9px; }
.cta-list li { display: flex; align-items: center; gap: 9px; font-size: .85rem; }
.cta-tag { font-size: .64rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: var(--accent); color: var(--brand-deep); padding: 1px 7px; border-radius: 20px; margin-left: 2px; }
.cta-list li svg { width: 16px; height: 16px; flex-shrink: 0; color: var(--accent); }

/* ══ FOOTER ══ */
.footer { background: #0A2F57; color: rgba(255,255,255,.78); padding: 56px 0 0; padding-bottom: env(safe-area-inset-bottom, 0); }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 36px; margin-bottom: 44px; }
.footer-logo { display: flex; align-items: center; text-decoration: none; margin-bottom: 14px; }
.footer-logo-img { height: 52px; width: auto; }
.footer-brand p { font-size: .83rem; line-height: 1.75; opacity: .78; margin-bottom: 18px; }
.footer-socials { display: flex; gap: 8px; flex-wrap: wrap; }
.footer-socials 
.footer-socials a svg { width: 17px; height: 17px; }
.footer-socials 
.footer-col h3 { font-family: 'Sora', sans-serif; font-size: .78rem; font-weight: 700; color: var(--white); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 15px; }
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.footer-col ul 
.footer-col ul 
.footer-bottom { border-top: 1px solid rgba(255,255,255,.1); padding: 18px 0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px; font-size: .78rem; color: rgba(255,255,255,.45); }
.footer-bottom 
.footer-bottom 
.footer-links { display: flex; gap: 18px; flex-wrap: wrap; }

/* Back to top */
#btt { position: fixed; bottom: 24px; right: 24px; bottom: max(24px, calc(24px + env(safe-area-inset-bottom, 0px))); width: 46px; height: 46px; border-radius: 50%; background: var(--brand); color: var(--white); border: none; cursor: pointer; box-shadow: var(--shadow-lg); display: none; align-items: center; justify-content: center; z-index: 900; transition: var(--transition); }
#btt svg { width: 20px; height: 20px; }
#btt.show { display: flex; }
#btt:hover { background: var(--brand-dark); }

/* Arrow icon inline */
.ic-arrow-right { display: inline-block; }

/* ══ RESPONSIVE ══ */
@media (max-width: 960px) {
  .blog-layout { grid-template-columns: 1fr; }
  .blog-sidebar { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
  .article-card--hero { flex-direction: column; }
  .article-card--hero .article-thumb { width: 100%; height: 220px; }
}
@media (max-width: 860px) {
  
  
  .dual-ct
  .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 580px) {
  .section { padding: 54px 0; }
  .blog-hero { padding: max(56px, calc(56px + env(safe-area-inset-top, 0px))) 0 40px; }
  .container { padding: 0 16px; }
  .footer-grid { grid-template-columns: 1fr; gap: 24px; }
  .dual-ct
  .cta-panel { padding: 30px 22px; }
  .blog-stats { gap: 12px; }
  .blog-stat { min-width: 110px; padding: 12px 16px; }
  .blog-stat-num { font-size: 1.5rem; }
  .article-card--hero { flex-direction: column; }
  .article-card--hero .article-thumb { width: 100%; height: 200px; }
  .footer-bottom { flex-direction: column; text-align: center; }
  .footer-links { justify-content: center; }
  .newsletter-inner { flex-direction: column; }
  .newsletter-form { flex-direction: column; width: 100%; }
  .newsletter-form input, .newsletter-form button { width: 100%; }
  .blog-sidebar { display: flex; }
}
@media (prefers-reduced-motion: reduce) {
  
}

/* ── Author block (named) ── */
.article-author-name { font-weight: 600; font-size: .82rem; display: inline-flex; align-items: center; gap: 4px; }
.article-author-name .verified { width: 13px; height: 13px; color: var(--brand); }
/* ── Updated / fresh badge ── */
.article-updated { display: inline-flex; align-items: center; gap: 4px; font-weight: 600; color: var(--success); }
.article-updated svg { width: 13px; height: 13px; }
/* ── Save / bookmark button ── */
.article-save {
  background: none; border: 1.5px solid var(--border); border-radius: 8px;
  width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
  cursor: pointer; color: var(--muted); transition: var(--transition); flex-shrink: 0;
  -webkit-tap-highlight-color: transparent;
}
.article-save svg { width: 16px; height: 16px; }
.article-save:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
.article-save[aria-pressed="true"] { border-color: var(--accent); color: var(--accent-dark); background: #fef3c7; }
.article-footer-actions { display: inline-flex; align-items: center; gap: 10px; flex-shrink: 0; }
/* ── Topic pill / tag counts ── */
.pill-count {
  font-size: .68rem; font-weight: 700; background: rgba(8,97,169,.12); color: var(--brand);
  border-radius: 20px; padding: 1px 7px; margin-left: 2px;
}
.topic-pill.active .pill-count, .topic-pill:hover .pill-count { background: rgba(255,255,255,.25); color: #fff; }
.topic-tag .tag-count { opacity: .65; font-weight: 700; margin-left: 3px; }
/* ── Source citations on data posts ── */
.article-sources {
  font-size: .73rem; color: var(--muted); margin-top: 2px;
  display: flex; align-items: center; gap: 5px; flex-wrap: wrap;
}
.article-sources svg { width: 12px; height: 12px; flex-shrink: 0; }
.article-sources strong { color: var(--text); font-weight: 600; }
/* ── "Was this helpful" + community strip ── */
.helpful-strip {
  background: var(--white); border: 1px solid var(--border); border-radius: 12px;
  padding: 18px 22px; margin-top: 28px;
  display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.helpful-strip p { font-size: .9rem; font-weight: 600; color: var(--text); display: inline-flex; align-items: center; gap: 8px; }
.helpful-strip p svg { width: 18px; height: 18px; color: var(--brand); }
.helpful-actions { display: flex; gap: 8px; }
.helpful-btn {
  border: 1.5px solid var(--border); background: var(--bg); border-radius: 8px;
  padding: 8px 16px; font-family: 'Inter', sans-serif; font-size: .82rem; font-weight: 600;
  color: var(--text); cursor: pointer; transition: var(--transition);
  display: inline-flex; align-items: center; gap: 6px; min-height: 40px;
}
.helpful-btn:hover { border-color: var(--brand); background: var(--brand-light); color: var(--brand); }
.helpful-btn.chosen { border-color: var(--success); background: #ecfdf5; color: #15803d; }
.helpful-btn svg { width: 15px; height: 15px; }
/* ── Lead magnet (newsletter) ── */
.nl-magnet {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .78rem; font-weight: 700; color: var(--accent);
  background: rgba(237,144,32,.14); border: 1px solid rgba(237,144,32,.3);
  border-radius: 8px; padding: 7px 12px; margin-bottom: 12px;
}
.nl-magnet svg { width: 15px; height: 15px; }
.newsletter-magnet {
  display: inline-flex; align-items: center; gap: 7px; margin-bottom: 10px;
  font-size: .82rem; font-weight: 700; color: var(--accent-dark);
  background: #fff; border: 1px solid #fde68a; border-radius: 8px; padding: 7px 13px;
}
.newsletter-magnet svg { width: 16px; height: 16px; color: var(--accent); }
/* ── Trust bar in hero ── */
.blog-trust {
  display: flex; align-items: center; gap: 8px; margin-top: 18px;
  font-size: .8rem; opacity: .85; flex-wrap: wrap;
}
.blog-trust svg { width: 15px; height: 15px; color: var(--accent); }
.blog-trust-dot { opacity: .4; }

/* ===== CANDIDATE HUB ===== */
.ch-hero{background:radial-gradient(ellipse 60% 50% at 85% 20%,rgba(237,144,32,.18) 0%,transparent 55%),radial-gradient(ellipse 70% 60% at 5% 95%,rgba(8,97,169,.35) 0%,transparent 55%),linear-gradient(155deg,#0A2F57 0%,#0A2F57 40%,#064A85 100%);color:#fff;position:relative;overflow:hidden}
.ch-hero .gridbg{position:absolute;inset:0;opacity:.4;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:48px 48px;-webkit-mask-image:radial-gradient(ellipse 80% 80% at 50% 25%,#000 30%,transparent 80%);mask-image:radial-gradient(ellipse 80% 80% at 50% 25%,#000 30%,transparent 80%)}
.ch-hero-inner{position:relative;z-index:1;text-align:center;max-width:760px;margin:0 auto;padding:56px 0 44px}
.ch-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:20px;padding:6px 15px;color:rgba(255,255,255,.92);margin-bottom:20px}
.ch-eyebrow svg{width:14px;height:14px;color:var(--accent)}
.ch-hero h1{font-family:'Sora',sans-serif;font-size:clamp(2.1rem,4.6vw,3.3rem);font-weight:800;line-height:1.08;letter-spacing:-.025em;margin-bottom:16px}
.ch-hero h1 span{color:var(--accent)}
.ch-hero .lede{font-size:1.08rem;color:rgba(255,255,255,.76);line-height:1.6;max-width:560px;margin:0 auto 28px}
/* hero search */
.ch-search{display:flex;gap:8px;background:#fff;border-radius:14px;padding:8px;max-width:580px;margin:0 auto;box-shadow:0 18px 50px rgba(0,0,0,.28)}
.ch-search .field{flex:1;display:flex;align-items:center;gap:9px;padding:0 12px}
.ch-search .field svg{width:18px;height:18px;color:var(--muted);flex-shrink:0}
.ch-search input{border:none;outline:none;font-family:'Inter',sans-serif;font-size:.95rem;width:100%;color:var(--text);background:none;padding:12px 0}
.ch-search 
.ch-pop{margin-top:16px;font-size:.83rem;color:rgba(255,255,255,.6)}
.ch-pop 
.ch-pop 
/* hero stat row */
.ch-statrow{display:flex;justify-content:center;gap:34px;margin-top:30px;flex-wrap:wrap}
.ch-stat{text-align:center}
.ch-stat-n{font-family:'Sora',sans-serif;font-size:1.5rem;font-weight:800;color:#fff}
.ch-stat-n span{color:var(--accent)}
.ch-stat-l{font-size:.74rem;color:rgba(255,255,255,.55);margin-top:2px}
/* sections */
.sec{padding:68px 0}.sec.tint{background:var(--bg)}.sec.white{background:#fff}
.sec-head{text-align:center;max-width:660px;margin:0 auto 44px}
.sec-head .section-title{font-size:clamp(1.5rem,2.8vw,2.2rem);font-weight:800;line-height:1.15;margin-bottom:12px}
.sec-head p{color:var(--muted);font-size:.96rem;line-height:1.6}
/* PILLARS (4 big feature blocks) */
.pillar{display:grid;grid-template-columns:1fr 1fr;gap:44px;align-items:center;margin-bottom:64px}
.pillar:last-child{margin-bottom:0}
.pillar.rev .pillar-medi
.pillar-label{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--brand);background:var(--brand-light);padding:5px 13px;border-radius:20px;margin-bottom:14px}
.pillar-label svg{width:14px;height:14px}
.pillar h2{font-family:'Sora',sans-serif;font-size:clamp(1.4rem,2.5vw,2rem);font-weight:800;line-height:1.18;margin-bottom:12px}
.pillar>div>p{color:var(--muted);font-size:.95rem;line-height:1.65;margin-bottom:18px}
.pillar-list{list-style:none;display:flex;flex-direction:column;gap:10px;margin-bottom:22px}
.pillar-list li{display:flex;align-items:flex-start;gap:10px;font-size:.9rem;color:var(--text);line-height:1.5}
.pillar-list svg{width:18px;height:18px;color:var(--success);flex-shrink:0;margin-top:1px}
.pillar-actions{display:flex;gap:10px;flex-wrap:wrap}
/* pillar media mockups */
.pillar-medi
.mock{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-lg);overflow:hidden}
.mock-top{display:flex;align-items:center;gap:7px;padding:13px 16px;border-bottom:1px solid var(--border);background:var(--bg)}
.mock-dot{width:9px;height:9px;border-radius:50%}
.mock-
/* job result mock */
.jobrow{display:flex;gap:12px;align-items:center;padding:12px;border:1px solid var(--border);border-radius:11px;margin-bottom:10px}
.jobrow:last-child{margin-bottom:0}
.joblogo{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;color:#fff;font-size:.85rem;flex-shrink:0}
.jobinfo{flex:1;min-width:0}
.jobinfo h4{font-family:'Sora',sans-serif;font-size:.88rem;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.jobinfo p{font-size:.76rem;color:var(--muted)}
.jobmet
/* tracker mock */
.trk-step{display:flex;align-items:center;gap:12px;padding:11px 0}
.trk-step+.trk-step{border-top:1px solid var(--border)}
.trk-ic{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.trk-ic svg{width:16px;height:16px}
.trk-done{background:#dcfce7;color:#16a34a}.trk-active{background:var(--accent);color:#fff}.trk-todo{background:var(--bg);color:var(--muted);border:1px solid var(--border)}
.trk-tx{flex:1}.trk-tx h4{font-family:'Sora',sans-serif;font-size:.85rem;font-weight:600;color:var(--text)}.trk-tx p{font-size:.72rem;color:var(--muted)}
.trk-badge{font-size:.66rem;font-weight:700;padding:3px 9px;border-radius:11px}
.tb-done{color:#16a34a;background:#dcfce7}.tb-active{color:var(--accent-dark);background:#fef3c7}
/* profile meter mock */
.pm-ring{display:flex;align-items:center;gap:16px;margin-bottom:16px}
.pm-circ{width:64px;height:64px;border-radius:50%;background:conic-gradient(var(--brand) 75%,var(--border) 0);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.pm-circ span{width:48px;height:48px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:.95rem;color:var(--brand)}
.pm-tx h4{font-family:'Sora',sans-serif;font-size:.92rem;font-weight:700}.pm-tx p{font-size:.78rem;color:var(--muted)}
.pm-task{display:flex;align-items:center;gap:9px;font-size:.82rem;padding:7px 0;color:var(--text)}
.pm-task svg{width:16px;height:16px;flex-shrink:0}
.pm-task.done{color:var(--muted)}.pm-task.done svg{color:var(--success)}
.pm-task.todo svg{color:var(--border)}
/* TOOLS grid */
.tools-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.tool-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:26px 24px;transition:var(--transition);display:flex;flex-direction:column;text-decoration:none}
.tool-card:hover{border-color:var(--brand);box-shadow:var(--shadow-lg);transform:translateY(-4px);text-decoration:none}
.tool-ic{width:50px;height:50px;border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:15px}
.tool-ic svg{width:25px;height:25px}
.t1{background:#e6f0f8;color:#0D609E}.t2{background:#f3e8ff;color:#7c3aed}.t3{background:#dcfce7;color:#16a34a}
.t4{background:#fef3c7;color:#C8770E}.t5{background:#e0f2fe;color:#0891b2}.t6{background:#fee2e2;color:#dc2626}
.tool-card h3{font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:700;color:var(--text);margin-bottom:7px;display:flex;align-items:center;gap:7px}
.tool-card p{font-size:.85rem;color:var(--muted);line-height:1.6;margin-bottom:14px;flex:1}
.tool-link{font-size:.84rem;font-weight:700;color:var(--brand);display:inline-flex;align-items:center;gap:5px}
.tool-badge{font-size:.6rem;font-weight:800;letter-spacing:.04em;text-transform:uppercase;color:#fff;background:var(--accent);padding:3px 8px;border-radius:10px}
/* CATEGORIES */
.cat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.cat-chip{display:flex;align-items:center;gap:11px;background:#fff;border:1px solid var(--border);border-radius:12px;padding:15px 16px;text-decoration:none;transition:var(--transition)}
.cat-chip:hover{border-color:var(--brand);background:var(--brand-light);text-decoration:none;transform:translateY(-2px)}
.cat-ic{width:38px;height:38px;border-radius:10px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.cat-ic svg{width:19px;height:19px}
.cat-tx h4{font-family:'Sora',sans-serif;font-size:.88rem;font-weight:700;color:var(--text)}
.cat-tx p{font-size:.73rem;color:var(--muted)}
/* ADVICE / blog cards */
.adv-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.adv-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;text-decoration:none;transition:var(--transition);display:flex;flex-direction:column}
.adv-card:hover{border-color:var(--brand);box-shadow:var(--shadow-lg);transform:translateY(-3px);text-decoration:none}
.adv-thumb{height:120px;display:flex;align-items:center;justify-content:center}
.adv-thumb svg{width:36px;height:36px;color:rgba(255,255,255,.3)}
.adv-
.adv-cat{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--brand);margin-bottom:6px}
.adv-body h3{font-family:'Sora',sans-serif;font-size:.96rem;font-weight:700;line-height:1.3;color:var(--text);margin-bottom:7px}
.adv-met
/* STEPS to start */
.start-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.start-card{text-align:center;padding:8px}
.start-num{width:46px;height:46px;border-radius:50%;background:var(--brand);color:#fff;font-family:'Sora',sans-serif;font-weight:800;font-size:1.15rem;display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.start-card h3{font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:8px}
.start-card p{font-size:.87rem;color:var(--muted);line-height:1.6}
/* FAQ */
.faq-wrap{max-width:760px;margin:0 auto}
.faq-item{background:#fff;border:1px solid var(--border);border-radius:12px;margin-bottom:12px;overflow:hidden;transition:var(--transition)}
.faq-item:hover{border-color:#cfe0f1}
.faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px;background:none;border:none;cursor:pointer;font-family:'Sora',sans-serif;font-size:.95rem;font-weight:700;color:var(--text);text-align:left;line-height:1.4}
.faq-q svg{width:19px;height:19px;color:var(--brand);flex-shrink:0;transition:transform .2s}
.faq-item.open .faq-q svg{transform:rotate(45deg)}
.faq-
.faq-a-in{padding:0 22px 18px;font-size:.88rem;color:var(--muted);line-height:1.7}
.faq-item.open .faq-
/* final CTA */
.final-ct
.final-cta h2{font-family:'Sora',sans-serif;font-size:clamp(1.6rem,3vw,2.3rem);font-weight:800;margin-bottom:12px}
.final-cta p{font-size:1rem;color:rgba(255,255,255,.74);margin-bottom:26px;max-width:520px;margin:0 auto 26px}
.final-cta-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.sticky-ct
@media(max-width:780px){.sticky-ct.sticky-cta }
@media(max-width:900px){
  .pillar{grid-template-columns:1fr;gap:26px;margin-bottom:48px}.pillar.rev .pillar-medi
  .tools-grid,.adv-grid,.start-grid{grid-template-columns:repeat(2,1fr)}
  .cat-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:580px){
  .sec{padding:46px 0}
  .tools-grid,.adv-grid,.start-grid,.cat-grid{grid-template-columns:1fr}
  .ch-search{flex-direction:column;padding:12px}.ch-search .field{padding:4px 8px}.ch-search 
  .ch-statrow{gap:22px}.final-ct
}

/* iOS zoom fix: form controls >=16px on mobile */
@media(max-width:580px){input,select,textare}

/* trust strip */
.trust-strip{background:#fff;border-bottom:1px solid var(--border)}
.trust-inner{display:flex;justify-content:center;gap:32px;flex-wrap:wrap;padding:20px 0}
.trust-item{display:flex;align-items:center;gap:9px;font-size:.86rem;font-weight:600;color:var(--text)}
.trust-item svg{width:18px;height:18px;color:var(--success);flex-shrink:0}
.trust-item.report svg{color:var(--brand)}
/* testimonials */
.tm-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.tm-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:26px 24px;display:flex;flex-direction:column}
.tm-stars{display:flex;gap:2px;margin-bottom:12px}
.tm-stars svg{width:15px;height:15px;color:var(--accent)}
.tm-card p{font-size:.9rem;color:var(--text);line-height:1.6;margin-bottom:18px;flex:1}
.tm-who{display:flex;align-items:center;gap:11px}
.tm-av{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;color:#fff;font-size:.85rem;flex-shrink:0}
.tm-who strong{display:block;font-size:.86rem;color:var(--text)}
.tm-who span{font-size:.76rem;color:var(--muted)}
@media(max-width:900px){.tm-grid{grid-template-columns:1fr}.trust-inner{gap:18px}}
.pg-bc{position:relative;z-index:1;display:flex;gap:7px;align-items:center;justify-content:center;flex-wrap:wrap;font-family:'Inter',sans-serif;font-size:.76rem;color:rgba(255,255,255,.6);margin-bottom:18px}
.pg-bc 
.pg-bc 
.pg-bc svg{width:12px;height:12px;opacity:.5}
.pg-bc [aria-current]{color:rgba(255,255,255,.85);font-weight:600}


/* ══ CV REVIEW PAGE ══ */
.cvr-hero{background:radial-gradient(1200px 600px at 80% -10%,rgba(237,144,32,.18),transparent 55%),radial-gradient(900px 500px at -10% 110%,rgba(8,97,169,.35),transparent 55%),linear-gradient(155deg,#0A2F57 0%,#0A2F57 40%,#064A85 100%);color:#fff;padding:64px 0 72px;position:relative;overflow:hidden}
.cvr-hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:44px 44px;mask-image:radial-gradient(circle at 70% 30%,#000,transparent 75%)}
.cvr-hero-inner{position:relative;z-index:1;display:grid;grid-template-columns:1.1fr .9fr;gap:48px;align-items:center}
.cvr-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.74rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:20px;padding:7px 15px;color:rgba(255,255,255,.92);margin-bottom:20px}
.cvr-eyebrow svg{width:14px;height:14px;color:var(--accent)}
.cvr-hero h1{font-size:2.5rem;font-weight:800;line-height:1.12;letter-spacing:-.02em;margin-bottom:16px;color:#fff}
.cvr-hero h1 span{color:var(--accent)}
.cvr-hero-lede{font-size:1.05rem;color:rgba(255,255,255,.85);line-height:1.6;margin-bottom:28px;max-width:520px}
.cvr-hero-ct
.cvr-hero-cta 




/* hero stat card */
.cvr-stats{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.16);border-radius:18px;padding:30px;display:grid;grid-template-columns:1fr 1fr;gap:24px;backdrop-filter:blur(6px)}
.cvr-stat .n{font-family:'Sora',sans-serif;font-size:2rem;font-weight:800;color:#fff;line-height:1}
.cvr-stat .n span{color:var(--accent)}
.cvr-stat .l{font-size:.82rem;color:rgba(255,255,255,.7);margin-top:5px}
/* sections */
.cvr-sec{padding:64px 0}
.cvr-sec.tint{background:var(--brand-light)}
.cvr-sec.white{background:#fff}
.cvr-sec-head{text-align:center;max-width:660px;margin:0 auto 44px}
.cvr-label{display:inline-flex;align-items:center;gap:7px;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--brand);background:var(--brand-light);padding:5px 13px;border-radius:20px;margin-bottom:14px}
.cvr-label svg{width:13px;height:13px}
.cvr-sec.tint .cvr-label{background:#fff}
.cvr-sec-head h2{font-size:1.9rem;font-weight:800;color:var(--brand-deep);letter-spacing:-.02em;margin-bottom:10px}
.cvr-sec-head h2 span{color:var(--brand)}
.cvr-sec-head p{font-size:1rem;color:var(--muted);line-height:1.55}
/* steps */
.cvr-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
.cvr-step{background:#fff;border:1px solid var(--border);border-radius:14px;padding:26px 22px;position:relative;transition:var(--transition)}
.cvr-step:hover{transform:translateY(-3px);box-shadow:var(--shadow-lg)}
.cvr-step-n{position:absolute;top:-14px;left:22px;width:30px;height:30px;border-radius:9px;background:var(--brand);color:#fff;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:.9rem}
.cvr-step-ic{width:44px;height:44px;border-radius:11px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;margin:8px 0 14px}
.cvr-step-ic svg{width:23px;height:23px}
.cvr-step h3{font-size:1rem;font-weight:700;color:var(--text);margin-bottom:7px}
.cvr-step p{font-size:.86rem;color:var(--muted);line-height:1.55}
/* pricing */
.cvr-pricing{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;align-items:stretch;max-width:1000px;margin:0 auto}
.price-card{background:#fff;border:1.5px solid var(--border);border-radius:16px;padding:30px 26px;display:flex;flex-direction:column;position:relative;transition:var(--transition)}
.price-card:hover{box-shadow:var(--shadow-lg);transform:translateY(-3px)}
.price-card.featured{border:2px solid var(--brand);box-shadow:0 10px 36px rgba(8,97,169,.16)}
.price-badge{position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:var(--accent);color:#fff;font-size:.7rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;padding:5px 15px;border-radius:20px;white-space:nowrap}
.price-name{font-family:'Sora',sans-serif;font-size:1.1rem;font-weight:700;color:var(--brand-deep)}
.price-tag-line{font-size:.82rem;color:var(--muted);margin:4px 0 18px}
.price-amount{display:flex;align-items:baseline;gap:4px;margin-bottom:4px}
.price-amount .cur{font-family:'Sora',sans-serif;font-size:1.2rem;font-weight:700;color:var(--text)}
.price-amount .val{font-family:'Sora',sans-serif;font-size:2.4rem;font-weight:800;color:var(--text);line-height:1}
.price-amount .per{font-size:.82rem;color:var(--muted)}
.price-sub{font-size:.78rem;color:var(--muted);margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border)}
.price-feats{list-style:none;display:flex;flex-direction:column;gap:11px;margin-bottom:24px;flex:1}
.price-feats li{display:flex;align-items:flex-start;gap:9px;font-size:.86rem;color:var(--text);line-height:1.45}
.price-feats svg{width:17px;height:17px;color:var(--success);flex-shrink:0;margin-top:1px}
.price-feats li.off{color:#9aa6b6}
.price-feats li.off svg{color:#cbd5e1}
.price-card 
/* review grid (what we review) */
.cvr-review-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.cvr-rev-item{background:#fff;border:1px solid var(--border);border-radius:13px;padding:24px}
.cvr-rev-ic{width:42px;height:42px;border-radius:11px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.cvr-rev-ic svg{width:22px;height:22px}
.cvr-rev-item h3{font-size:.96rem;font-weight:700;color:var(--text);margin-bottom:7px}
.cvr-rev-item p{font-size:.85rem;color:var(--muted);line-height:1.55}
/* testimonials */
.cvr-tm-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.cvr-tm{background:#fff;border:1px solid var(--border);border-radius:14px;padding:26px;display:flex;flex-direction:column}
.cvr-tm-stars{display:flex;gap:2px;margin-bottom:13px}
.cvr-tm-stars svg{width:15px;height:15px;color:var(--accent)}
.cvr-tm p{font-size:.88rem;color:var(--text);line-height:1.6;margin-bottom:18px;flex:1}
.cvr-tm-who{display:flex;align-items:center;gap:11px}
.cvr-tm-av{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:.85rem;color:#fff;flex-shrink:0}
.cvr-tm-who strong{display:block;font-size:.85rem;color:var(--text)}
.cvr-tm-who span{font-size:.76rem;color:var(--muted)}
/* report preview (visual proof) */
.cvr-proof{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center}
.cvr-proof-text h2{font-size:1.7rem;font-weight:800;color:var(--brand-deep);margin-bottom:14px;letter-spacing:-.02em}
.cvr-proof-text h2 span{color:var(--brand)}
.cvr-proof-text p{font-size:.95rem;color:var(--muted);line-height:1.6;margin-bottom:18px}
.cvr-proof-list{list-style:none;display:flex;flex-direction:column;gap:12px}
.cvr-proof-list li{display:flex;align-items:flex-start;gap:10px;font-size:.9rem;color:var(--text)}
.cvr-proof-list svg{width:19px;height:19px;color:var(--success);flex-shrink:0;margin-top:1px}
.cvr-report-img{border-radius:16px;overflow:hidden;border:1px solid var(--border);box-shadow:var(--shadow-lg);background:#fff;position:relative;aspect-ratio:4/3}
.cvr-report-img img{width:100%;height:100%;object-fit:cover}
.cvr-report-ph{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg,#f0f6fc,#e6f0f8);color:var(--brand);gap:10px}
.cvr-report-ph svg{width:54px;height:54px;opacity:.5}
.cvr-report-ph span{font-size:.82rem;color:var(--muted);font-weight:600}
/* sign in required */
.cvr-signin{max-width:560px;margin:0 auto;background:#fff;border:1px solid var(--border);border-radius:18px;padding:40px;text-align:center;box-shadow:var(--shadow)}
.cvr-signin-ic{width:60px;height:60px;border-radius:15px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;margin:0 auto 18px}
.cvr-signin-ic svg{width:30px;height:30px}
.cvr-signin h2{font-size:1.4rem;font-weight:800;color:var(--brand-deep);margin-bottom:8px}
.cvr-signin p{font-size:.92rem;color:var(--muted);margin-bottom:8px;line-height:1.55}
.cvr-signin-actions{display:flex;gap:12px;justify-content:center;margin-top:22px;flex-wrap:wrap}
.cvr-signin-actions 
/* FAQ */
.cvr-faq{max-width:760px;margin:0 auto;display:flex;flex-direction:column;gap:12px}
.faq-item{background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden}
.faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:14px;padding:18px 22px;background:none;border:none;font-family:'Inter',sans-serif;font-size:.95rem;font-weight:600;color:var(--text);cursor:pointer;text-align:left}
.faq-q svg{width:20px;height:20px;color:var(--brand);flex-shrink:0;transition:var(--transition)}
.faq-q[aria-expanded="true"] svg{transform:rotate(45deg)}
.faq-
.faq-a-in{padding:0 22px 20px;font-size:.88rem;color:var(--muted);line-height:1.6}
/* final cta */
.cvr-final{background:radial-gradient(900px 500px at 50% -20%,rgba(237,144,32,.16),transparent 60%),linear-gradient(155deg,#0A2F57,#064A85);color:#fff;padding:60px 0;text-align:center}
.cvr-final h2{font-size:2rem;font-weight:800;margin-bottom:12px;letter-spacing:-.02em}
.cvr-final p{font-size:1.02rem;color:rgba(255,255,255,.85);max-width:560px;margin:0 auto 26px;line-height:1.55}
/* mobile */
@media(max-width:960px){
  .cvr-hero-inner{grid-template-columns:1fr;gap:32px}
  .cvr-steps{grid-template-columns:1fr 1fr}
  .cvr-pricing{grid-template-columns:1fr;max-width:440px}
  .price-card.featured{order:-1}
  .cvr-review-grid{grid-template-columns:1fr 1fr}
  .cvr-tm-grid{grid-template-columns:1fr}
  .cvr-proof{grid-template-columns:1fr;gap:28px}
}
@media(max-width:580px){
  .cvr-hero h1{font-size:1.85rem}
  .cvr-hero{padding:44px 0 52px}
  .cvr-stats{grid-template-columns:1fr 1fr;padding:22px;gap:18px}
  .cvr-steps{grid-template-columns:1fr}
  .cvr-review-grid{grid-template-columns:1fr}
  .cvr-sec{padding:48px 0}
  .cvr-sec-head h2{font-size:1.5rem}
  .cvr-final h2{font-size:1.5rem}
  input,select,textare
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main-content">

<!-- HERO -->
<section class="cvr-hero" aria-labelledby="cvr-h1">
  <div class="cvr-hero-grid" aria-hidden="true"></div>
  <div class="container">
    <div class="cvr-hero-inner">
      <div>
        <div class="cvr-eyebrow"><svg aria-hidden="true"><use href="#i-star"/></svg>Trusted by 10,000+ professionals</div>
        <h1 id="cvr-h1">Professional <span>CV Review</span> by certified experts</h1>
        <p class="cvr-hero-lede">Get your CV reviewed by certified HR professionals and recruitment experts. Receive actionable, line-by-line feedback that helps you land more interviews at top companies.</p>
        <div class="cvr-hero-cta">
          <a href="#pricing" class="btn btn-accent btn-lg">Get your CV reviewed</a>
          <a href="#how" class="btn btn-ghost-light btn-lg">How it works</a>
        </div>
      </div>
      <div class="cvr-stats">
        <div class="cvr-stat"><div class="n">10K<span>+</span></div><div class="l">CVs reviewed</div></div>
        <div class="cvr-stat"><div class="n">4.9<span>/5</span></div><div class="l">Client rating</div></div>
        <div class="cvr-stat"><div class="n">48<span>hr</span></div><div class="l">Avg. turnaround</div></div>
        <div class="cvr-stat"><div class="n">78<span>%</span></div><div class="l">Interview success</div></div>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="cvr-sec white" id="how" aria-labelledby="how-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><svg aria-hidden="true"><use href="#i-sliders"/></svg>How it works</div>
      <h2 id="how-h">Your CV review <span>journey</span></h2>
      <p>Four simple steps to a standout CV that gets you noticed by recruiters and hiring managers.</p>
    </div>
    <div class="cvr-steps">
      <div class="cvr-step">
        <div class="cvr-step-n">1</div>
        <div class="cvr-step-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div>
        <h3>Upload your CV</h3>
        <p>Submit your CV in PDF, DOC, or DOCX format. No sign-up required for a quick AI scan.</p>
      </div>
      <div class="cvr-step">
        <div class="cvr-step-n">2</div>
        <div class="cvr-step-ic"><svg aria-hidden="true"><use href="#i-search"/></svg></div>
        <h3>Expert analysis</h3>
        <p>A certified reviewer evaluates your CV against industry standards and ATS systems.</p>
      </div>
      <div class="cvr-step">
        <div class="cvr-step-n">3</div>
        <div class="cvr-step-ic"><svg aria-hidden="true"><use href="#i-chart"/></svg></div>
        <h3>Detailed report</h3>
        <p>Receive a comprehensive report with actionable recommendations and before/after examples.</p>
      </div>
      <div class="cvr-step">
        <div class="cvr-step-n">4</div>
        <div class="cvr-step-ic"><svg aria-hidden="true"><use href="#i-rocket"/></svg></div>
        <h3>Land interviews</h3>
        <p>Apply with confidence using your optimised CV that stands out to recruiters.</p>
      </div>
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="cvr-sec tint" id="pricing" aria-labelledby="price-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><svg aria-hidden="true"><use href="#i-coins"/></svg>Pricing</div>
      <h2 id="price-h">Choose your <span>review package</span></h2>
      <p>Select the level of feedback that matches your career goals and budget. All prices in Nigerian Naira.</p>
    </div>
    <div class="cvr-pricing">
      <div class="price-card">
        <div class="price-name">Basic</div>
        <div class="price-tag-line">AI-powered analysis</div>
        <div class="price-amount">
          <?php if ($planPrices['basic'] > 0): ?>
            <span class="cur">&#8358;</span><span class="val"><?= number_format($planPrices['basic']) ?></span><span class="per">/review</span>
          <?php else: ?>
            <span class="val">Free</span>
          <?php endif; ?>
        </div>
        <div class="price-sub">Fast automated feedback to fix the essentials.</div>
        <ul class="price-feats">
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> ATS compatibility scan</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Structure &amp; formatting check</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Keyword analysis</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Overall impact score</li>
          <li class="off"><svg aria-hidden="true"><use href="#i-x-circle"/></svg> Human expert review</li>
          <li class="off"><svg aria-hidden="true"><use href="#i-x-circle"/></svg> Rewritten sections</li>
        </ul>
        <a href="<?= base_url($isLoggedIn ? 'cv-review/submit?plan=basic' : 'login?redirect=cv-review/submit?plan=basic') ?>"/ class="btn btn-outline">Choose Basic</a>
      </div>
      <div class="price-card featured">
        <div class="price-badge">Most popular</div>
        <div class="price-name">Professional</div>
        <div class="price-tag-line">Expert human analysis</div>
        <div class="price-amount"><span class="cur">&#8358;</span><span class="val"><?= number_format($planPrices['professional']) ?></span><span class="per">/review</span></div>
        <div class="price-sub">Full certified-expert review with rewrites.</div>
        <ul class="price-feats">
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Everything in Basic</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> HR expert review</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Line-by-line feedback</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Rewritten sections</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Cover letter tips</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> 24-hour turnaround</li>
        </ul>
        <a href="<?= base_url($isLoggedIn ? 'cv-review/submit?plan=professional' : 'login?redirect=cv-review/submit?plan=professional') ?>"/ class="btn btn-accent">Get reviewed now</a>
      </div>
      <div class="price-card">
        <div class="price-name">Premium</div>
        <div class="price-tag-line">Expert review + coaching</div>
        <div class="price-amount"><span class="cur">&#8358;</span><span class="val"><?= number_format($planPrices['premium']) ?></span><span class="per">/review</span></div>
        <div class="price-sub">The complete package with 1-on-1 support.</div>
        <ul class="price-feats">
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Everything in Professional</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> 1-on-1 consultation call</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Tailored cover letter</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> LinkedIn profile tips</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> 12-hour priority turnaround</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Free re-review</li>
        </ul>
        <a href="<?= base_url($isLoggedIn ? 'cv-review/submit?plan=premium' : 'login?redirect=cv-review/submit?plan=premium') ?>"/ class="btn btn-outline">Choose Premium</a>
      </div>
    </div>
  </div>
</section>

<!-- VISUAL PROOF / REPORT PREVIEW -->
<section class="cvr-sec white" aria-labelledby="proof-h">
  <div class="container">
    <div class="cvr-proof">
      <div class="cvr-proof-text">
        <div class="cvr-label"><svg aria-hidden="true"><use href="#i-eye"/></svg>What you get</div>
        <h2 id="proof-h">A clear, <span>actionable report</span> &mdash; not vague advice</h2>
        <p>Every review delivers a structured report you can act on immediately, with specific examples drawn from your own CV.</p>
        <ul class="cvr-proof-list">
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Before-and-after rewrites of your weakest sections</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Your ATS compatibility score and exactly what to fix</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Prioritised checklist, ranked by impact</li>
          <li><svg aria-hidden="true"><use href="#i-check"/></svg> Industry-specific keyword recommendations</li>
        </ul>
      </div>
      <figure class="cvr-report-img">
        <img src="<?= base_url('assets/cv-review/sample-report.jpg') ?>"/ alt="Sample CV review report showing ATS score and section feedback" loading="lazy" decoding="async" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
        <div class="cvr-report-ph" style="display:none"><svg aria-hidden="true"><use href="#i-doc"/></svg><span>Sample report preview</span></div>
      </figure>
    </div>
  </div>
</section>

<!-- WHAT WE REVIEW -->
<section class="cvr-sec tint" aria-labelledby="rev-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><svg aria-hidden="true"><use href="#i-sliders"/></svg>What we review</div>
      <h2 id="rev-h">Comprehensive <span>CV analysis</span></h2>
      <p>Every CV undergoes a thorough multi-point inspection across these key areas.</p>
    </div>
    <div class="cvr-review-grid">
      <div class="cvr-rev-item"><div class="cvr-rev-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div><h3>Structure &amp; formatting</h3><p>Layout, section ordering, spacing, font consistency, and page balance.</p></div>
      <div class="cvr-rev-item"><div class="cvr-rev-ic"><svg aria-hidden="true"><use href="#i-edit"/></svg></div><h3>Professional summary</h3><p>Impact, clarity, keyword optimisation, and alignment with target roles.</p></div>
      <div class="cvr-rev-item"><div class="cvr-rev-ic"><svg aria-hidden="true"><use href="#i-bag"/></svg></div><h3>Work experience</h3><p>Action verbs, quantified achievements, relevance, and career progression.</p></div>
      <div class="cvr-rev-item"><div class="cvr-rev-ic"><svg aria-hidden="true"><use href="#i-spark"/></svg></div><h3>Skills presentation</h3><p>Relevance, categorisation, proficiency indicators, and keyword density.</p></div>
      <div class="cvr-rev-item"><div class="cvr-rev-ic"><svg aria-hidden="true"><use href="#i-bot"/></svg></div><h3>ATS compatibility</h3><p>Parseability, keyword match rate, file format, and section header recognition.</p></div>
      <div class="cvr-rev-item"><div class="cvr-rev-ic"><svg aria-hidden="true"><use href="#i-star"/></svg></div><h3>Overall impact</h3><p>First impression, uniqueness, value proposition, and call to action.</p></div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="cvr-sec white" aria-labelledby="tm-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><svg aria-hidden="true"><use href="#i-star"/></svg>Testimonials</div>
      <h2 id="tm-h">What our <span>clients say</span></h2>
      <p>Hear from professionals who transformed their job search with our CV review service.</p>
    </div>
    <div class="cvr-tm-grid">
      <div class="cvr-tm">
        <div class="cvr-tm-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div>
        <p>&ldquo;The CV review was eye-opening. I had no idea my CV wasn't ATS-friendly. After implementing the changes, I got 3 interview invites in the first week!&rdquo;</p>
        <div class="cvr-tm-who"><span class="cvr-tm-av" style="background:#0D609E">AM</span><div><strong>Adebayo Martins</strong><span>Software Engineer, Access Bank</span></div></div>
      </div>
      <div class="cvr-tm">
        <div class="cvr-tm-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div>
        <p>&ldquo;Worth every penny! The detailed line-by-line feedback helped me completely rewrite my CV. Landed my dream job within 3 weeks of the premium review.&rdquo;</p>
        <div class="cvr-tm-who"><span class="cvr-tm-av" style="background:#16a34a">FO</span><div><strong>Folake Ogunlesi</strong><span>Marketing Manager, MTN Nigeria</span></div></div>
      </div>
      <div class="cvr-tm">
        <div class="cvr-tm-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div>
        <p>&ldquo;The free basic review already gave me so much value. I upgraded to professional and the before/after comparison was incredible. Highly recommended!&rdquo;</p>
        <div class="cvr-tm-who"><span class="cvr-tm-av" style="background:#C8770E">CE</span><div><strong>Chidi Eze</strong><span>Data Analyst, PwC</span></div></div>
      </div>
    </div>
  </div>
</section>

<!-- SIGN IN REQUIRED -->
<?php if (!$isLoggedIn): ?>
<section class="cvr-sec tint" aria-labelledby="si-h">
  <div class="container">
    <div class="cvr-signin">
      <div class="cvr-signin-ic"><svg aria-hidden="true"><use href="#i-lock"/></svg></div>
      <h2 id="si-h">Ready to submit your CV?</h2>
      <p>You'll need a free account to upload your CV and receive your review. It takes less than a minute.</p>
      <div class="cvr-signin-actions">
        <a href="<?= base_url('login?redirect=/cv-review') ?>"/ class="btn btn-primary">Sign in</a>
        <a href="<?= base_url('register?redirect=/cv-review') ?>"/ class="btn btn-outline">Create free account</a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FAQ -->
<section class="cvr-sec white" aria-labelledby="faq-h">
  <div class="container">
    <div class="cvr-sec-head">
      <div class="cvr-label"><svg aria-hidden="true"><use href="#i-bulb"/></svg>FAQ</div>
      <h2 id="faq-h">Frequently asked <span>questions</span></h2>
      <p>Everything you need to know about our CV review service.</p>
    </div>
    <div class="cvr-faq">
      <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">How long does a CV review take? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Basic reviews are completed within 48 hours, Professional within 24 hours, and Premium within 12 hours. Express options are available for urgent requests.</div></div></div>
      <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">What file formats do you accept? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">We accept PDF, DOC, and DOCX files up to 5MB. For best results we recommend uploading a PDF version of your CV.</div></div></div>
      <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">What's the difference between the review tiers? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Basic is an AI-powered analysis covering ATS compatibility, structure, and keywords. Professional adds a full human expert review with line-by-line feedback and rewritten sections. Premium adds a 1-on-1 consultation call and a tailored cover letter.</div></div></div>
      <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">Will my CV be kept confidential? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Yes. All uploaded documents are encrypted in transit and at rest, accessible only to your assigned reviewer, and never shared with third parties. You can request deletion of your documents at any time.</div></div></div>
      <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">What if I'm not satisfied with the review? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Your satisfaction is our priority. If you're not happy with your review, we'll re-review your CV for free, and the Premium package includes a consultation call to address all your concerns.</div></div></div>
      <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">Do you review CVs for specific industries? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Yes. Our team covers technology, finance, healthcare, education, marketing, sales, government, and consulting. When you upload your CV, select your target industry so we match you with the most relevant reviewer.</div></div></div>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="cvr-final" aria-labelledby="final-h">
  <div class="container">
    <h2 id="final-h">Ready to transform your CV?</h2>
    <p>Join thousands of professionals who landed their dream jobs after a professional CV review. Start your journey today.</p>
    <a href="#pricing" class="btn btn-accent btn-lg">Get your CV reviewed</a>
  </div>
</section>

</main>
<?= $this->endSection() ?>

