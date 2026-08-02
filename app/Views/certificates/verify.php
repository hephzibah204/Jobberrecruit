<?= $this->extend('templates/base') ?>

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

/* ══ VERIFY PAGE ══ */
.vrf-hero{background:radial-gradient(1000px 500px at 80% -10%,rgba(237,144,32,.16),transparent 55%),linear-gradient(155deg,#0A2F57,#064A85);color:#fff;padding:48px 0;text-align:center}
.vrf-hero h1{font-size:1.9rem;font-weight:800;letter-spacing:-.02em;margin-bottom:8px;color:#fff}
.vrf-hero p{font-size:.98rem;color:rgba(255,255,255,.82);max-width:520px;margin:0 auto}
.vrf-wrap{max-width:720px;margin:-32px auto 0;padding:0 20px 64px;position:relative;z-index:2}

/* status card */
.vrf-card{background:#fff;border:1px solid var(--border);border-radius:18px;box-shadow:0 14px 40px rgba(10,47,87,.14);overflow:hidden}
.vrf-status{display:flex;align-items:center;gap:14px;padding:24px 28px;border-bottom:1px solid var(--border)}
.vrf-status.valid{background:#f0fdf4}
.vrf-status.invalid{background:#fef2f2}
.vrf-status-ic{width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.vrf-status.valid .vrf-status-ic{background:#16a34a;color:#fff}
.vrf-status.invalid .vrf-status-ic{background:#dc2626;color:#fff}
.vrf-status-ic svg{width:28px;height:28px}
.vrf-status h2{font-size:1.2rem;font-weight:800;margin-bottom:2px}
.vrf-status.valid h2{color:#15803d}
.vrf-status.invalid h2{color:#b91c1c}
.vrf-status p{font-size:.86rem;color:var(--muted)}

/* details */
.vrf-
.vrf-recipient{text-align:center;padding-bottom:24px;border-bottom:1px solid var(--border);margin-bottom:24px}
.vrf-recipient .lbl{font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);font-weight:700;margin-bottom:6px}
.vrf-recipient .name{font-family:'Sora',sans-serif;font-size:1.7rem;font-weight:800;color:var(--brand-deep);line-height:1.1}
.vrf-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.vrf-field .lbl{font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);font-weight:700;margin-bottom:4px}
.vrf-field .val{font-size:.96rem;color:var(--text);font-weight:600;line-height:1.4}
.vrf-field.full{grid-column:1/-1}
.vrf-field .val.id{font-family:'Sora',sans-serif;letter-spacing:.02em;color:var(--brand)}

.vrf-issuer{display:flex;align-items:center;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border)}
.vrf-issuer-mark{width:40px;height:40px;border-radius:9px;background:linear-gradient(135deg,var(--brand),var(--brand-deep));display:flex;align-items:center;justify-content:center;flex-shrink:0}
.vrf-issuer-mark svg{width:22px;height:22px;color:#fff}
.vrf-issuer-text strong{display:block;font-size:.9rem;color:var(--text)}
.vrf-issuer-text span{font-size:.78rem;color:var(--muted)}

.vrf-actions{display:flex;gap:12px;margin-top:24px;flex-wrap:wrap}
.vrf-actions 

.vrf-note{display:flex;align-items:flex-start;gap:10px;margin-top:20px;padding:14px 16px;background:#f7fafd;border:1px solid #e4edf6;border-radius:10px;font-size:.8rem;color:var(--muted);line-height:1.5}
.vrf-note svg{width:17px;height:17px;color:var(--brand);flex-shrink:0;margin-top:1px}

/* manual check form */
.vrf-manual{max-width:520px;margin:36px auto 0;text-align:center}
.vrf-manual h3{font-size:1.05rem;font-weight:700;color:var(--brand-deep);margin-bottom:10px}
.vrf-manual-form{display:flex;gap:10px;margin-top:14px}
.vrf-manual-form input{flex:1;min-height:48px;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:'Inter',sans-serif;font-size:16px}
.vrf-manual-form input:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 3px rgba(8,97,169,.12)}

@media(max-width:580px){
  .vrf-hero h1{font-size:1.5rem}
  .vrf-grid{grid-template-columns:1fr}
  .vrf-status{padding:20px}
  .vrf-
  .vrf-recipient .name{font-size:1.4rem}
  .vrf-manual-form{flex-direction:column}
  input,select,textare
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main-content">
<section class="vrf-hero">
  <div class="container">
    <h1>Certificate Verification</h1>
    <p>Confirm the authenticity of any certificate issued by JobberRecruit.</p>
  </div>
</section>

<div class="vrf-wrap">
  <!-- VALID state (backend renders this when ID is found) -->
  <div class="vrf-card" id="vrf-result">
    <?php if ($certificateData): ?>
    <div class="vrf-result valid" id="result-valid">
      <div class="vrf-status-bar" style="background:#f0fdf4;padding:24px 28px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:14px;">
        <div class="vrf-status-ic" style="width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:#16a34a;color:#fff"><svg style="width:28px;height:28px" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></div>
        <div>
          <h2 style="font-size:1.2rem;font-weight:800;color:#15803d;margin-bottom:2px">Verified Authentic Certificate</h2>
          <p style="font-size:.86rem;color:var(--muted)">This certificate is authentic and was issued by JobberRecruit.</p>
        </div>
      </div>
      <div class="vrf-body">
      <div class="vrf-recipient">
        <div class="lbl">Awarded to</div>
        <div class="name"><?= esc($certificateData['recipient']) ?></div>
      </div>
      <div class="vrf-grid">
        <div class="vrf-field full">
          <div class="lbl">Programme</div>
          <div class="val"><?= esc($certificateData['course_title']) ?></div>
        </div>
        <div class="vrf-field">
          <div class="lbl">Type</div>
          <div class="val"><?= esc($certificateData['type']) ?></div>
        </div>
        <div class="vrf-field">
          <div class="lbl">Date Issued</div>
          <div class="val"><?= esc($certificateData['issued_at']) ?></div>
        </div>
        <div class="vrf-field">
          <div class="lbl">Duration</div>
          <div class="val"><?= esc($certificateData['duration']) ?></div>
        </div>
        <div class="vrf-field">
          <div class="lbl">Certificate ID</div>
          <div class="val id"><?= esc($certificateData['code']) ?></div>
        </div>
      </div>

      <div class="vrf-issuer">
        <div class="vrf-issuer-mark"><svg aria-hidden="true"><use href="#i-cap"/></svg></div>
        <div class="vrf-issuer-text">
          <strong>Issued by JobberRecruit Ltd</strong>
          <span>Lagos, Nigeria</span>
        </div>
      </div>

      <div class="vrf-actions">
        <a href="<?= base_url('certificate?id=' . $certificateData['code']) ?>" class="btn btn-primary">View certificate</a>
        <a href="<?= base_url('training/courses') ?>" class="btn btn-outline">Explore our courses</a>
      </div>

      <div class="vrf-note">
        <svg aria-hidden="true"><use href="#i-shield"/></svg>
        <span>This record is maintained by JobberRecruit. If the details above do not match the certificate presented to you, the document may have been altered &mdash; please contact us to report it.</span>
      </div>
    </div>
    </div>
    <?php elseif ($code): ?>
    <div class="vrf-result invalid">
      <div class="vrf-status-bar" style="background:#fef2f2;padding:24px 28px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:14px;color:#b91c1c;">
        <div class="vrf-status-ic" style="width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:#dc2626;color:#fff"><svg style="width:28px;height:28px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
        <div>
          <h2 style="font-size:1.2rem;font-weight:800;color:#b91c1c;margin-bottom:2px">Certificate Not Found</h2>
        </div>
      </div>
      <div class="vrf-body text-center" style="padding: 40px 24px">
        <p>No certificate was found matching ID: <strong><?= esc($code) ?></strong></p>
        <p style="margin-top:10px; font-size:0.9rem; color:var(--muted)">Please check the ID and try again, or contact support.</p>
      </div>
    </div>
    <?php endif; ?>

  </div>

  <!-- Manual lookup -->
  <div class="vrf-manual">
    <h3>Verify a certificate</h3>
    <p style="font-size:.88rem;color:var(--muted)">Enter a certificate ID to check its authenticity.</p>
    <form class="vrf-manual-form" action="<?= base_url('certificates/verify') ?>" method="get">
      <input type="text" id="vrf-input" name="id" placeholder="e.g. JR-CERT-000001" aria-label="Certificate ID" required value="<?= esc($code ?? '') ?>">
      <button type="submit" class="btn btn-primary">Verify</button>
    </form>
  </div>
</div>
</main>
<?= $this->endSection() ?>

