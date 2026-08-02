<?= $this->extend('templates/base') ?>

<?= $this->section('meta') ?>
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
<meta name="geo.region" content="NG">
<meta name="geo.placename" content="Nigeria">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= base_url('assets/og-candidate-hub.jpg') ?>">
<meta property="og:locale" content="en_NG">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="<?= base_url('assets/og-candidate-hub.jpg') ?>">
<?= $this->endSection() ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","@id":"<?= current_url() ?>#webpage","name":"Candidate Hub","url":"<?= current_url() ?>","inLanguage":"en-NG","description":"A hub for job seekers in Nigeria to find jobs, build CVs, practise interviews, track applications and access career advice.","isPartOf":{"@type":"WebSite","name":"JobberRecruit","url":"<?= base_url() ?>"},"publisher":{"@type":"Organization","name":"JobberRecruit","url":"<?= base_url() ?>","logo":{"@type":"ImageObject","url":"<?= base_url('assets/logo.png') ?>"}}}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[
{"@type":"ListItem","position":1,"name":"Home","item":"<?= base_url() ?>"},
{"@type":"ListItem","position":2,"name":"Candidate hub","item":"<?= current_url() ?>"}]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"ItemList","name":"Career tools for job seekers","itemListElement":[
{"@type":"ListItem","position":1,"name":"AI Resume Builder","url":"<?= base_url('ai-tools/resume-builder') ?>"},
{"@type":"ListItem","position":2,"name":"AI Mock Interview","url":"<?= base_url('ai-tools/mock-interview') ?>"},
{"@type":"ListItem","position":3,"name":"CV Revamp","url":"<?= base_url('training/cv-revamp') ?>"},
{"@type":"ListItem","position":4,"name":"Salary Calculator","url":"<?= base_url('ai-tools/salary-calculator') ?>"},
{"@type":"ListItem","position":5,"name":"Career Webinars","url":"<?= base_url('training/webinars') ?>"},
{"@type":"ListItem","position":6,"name":"Career Path Quiz","url":"<?= base_url('ai-tools/career-quiz') ?>"}]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[
{"@type":"Question","name":"Is JobberRecruit free for job seekers?","acceptedAnswer":{"@type":"Answer","text":"Yes. Creating a candidate account, searching and applying for jobs, building your CV, practising interviews and tracking applications are all free for job seekers."}},
{"@type":"Question","name":"Do I need an account to apply for jobs?","acceptedAnswer":{"@type":"Answer","text":"You can browse jobs without an account, but you'll need a free candidate account to apply, save jobs, track applications and set up job alerts."}},
{"@type":"Question","name":"What career tools are available?","acceptedAnswer":{"@type":"Answer","text":"An AI resume builder, AI mock interview practice, professional CV revamp, a salary calculator, career webinars and a career path quiz — all designed to help you land your next role."}}
]}
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* ── Reset / Token overrides for specific design ── */
:root {
  --brand:        #0861A9;
  --brand-dark:   #064A85;
  --brand-deep:   #0A2F57;
  --brand-light:  #E6F0F8;
  --accent:       #ED9020;
  --accent-dark:  #C8770E;
  --text:         #141926;
  --muted:        #5b6577;
  --bg:           #f5f7fb;
  --white:        #ffffff;
  --border:       #e2e8f2;
  --success:      #16a34a;
  --radius:       10px;
  --shadow:       0 2px 14px rgba(10,47,87,.08);
  --shadow-lg:    0 14px 40px rgba(10,47,87,.16);
  --transition:   .18s ease;
}

html { scroll-behavior: smooth; }

.section   { padding: 76px 0; background-color: #f5f7fb; }
.section.white-bg { background-color: #ffffff; }

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
.btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 7px;
  padding: 11px 22px; border-radius: 8px;
  font-family: 'Inter', sans-serif; font-size: .88rem; font-weight: 600;
  cursor: pointer; border: 1.5px solid transparent;
  transition: var(--transition); text-decoration: none;
  -webkit-tap-highlight-color: transparent; touch-action: manipulation;
}
.btn svg { width: 16px; height: 16px; }
.btn-primary  { background: var(--brand);  color: var(--white); border-color: var(--brand); }
.btn-primary:hover  { background: var(--brand-dark); border-color: var(--brand-dark); text-decoration: none; color: #fff; }
.btn-outline  { background: transparent; color: var(--brand); border-color: var(--border); }
.btn-outline:hover  { background: var(--brand); color: var(--white); border-color: var(--brand); text-decoration: none; }
.btn-accent   { background: var(--accent); color: var(--brand-deep); border-color: var(--accent); }
.btn-accent:hover   { background: var(--accent-dark); border-color: var(--accent-dark); color: var(--brand-deep); text-decoration: none; }
.btn-white    { background: var(--white); color: var(--brand); border-color: var(--white); }
.btn-white:hover    { background: var(--brand-light); text-decoration: none; }
.btn-sm       { padding: 8px 14px; font-size: .78rem; }
.btn-lg       { padding: 14px 32px; font-size: .95rem; }

.badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
.badge svg { width: 12px; height: 12px; }
.badge-blue  { background: var(--brand-light); color: var(--brand); }
.badge-accent { background: #fef3c7; color: var(--accent-dark); }
.badge-green  { background: #ecfdf5; color: #15803d; }

:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; border-radius: 4px; }

/* Horizontal card variant (featured top story) */
.article-card--hero { flex-direction: row; }
.article-card--hero .article-thumb { width: 320px; height: auto; min-height: 240px; flex-shrink: 0; }
.article-card--hero .article-body { padding: 28px 30px; }

/* ══ DUAL CTA ══ */
.dual-cta { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.cta-panel { border-radius: 12px; padding: 44px 32px; }
.cta-panel.blue { background: linear-gradient(150deg, #0A2F57, var(--brand)); color: var(--white) !important; }
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

/* iOS zoom fix: form controls >=16px on mobile */
@media (max-width: 580px) {
  input, select, textarea { font-size: 16px !important; }
}

/* ===== CANDIDATE HUB ===== */
.ch-hero {
  background: radial-gradient(ellipse 60% 50% at 85% 20%, rgba(237, 144, 32, .18) 0%, transparent 55%), radial-gradient(ellipse 70% 60% at 5% 95%, rgba(8, 97, 169, .35) 0%, transparent 55%), linear-gradient(155deg, #0A2F57 0%, #0A2F57 40%, #064A85 100%);
  color: #fff;
  position: relative;
  overflow: hidden;
}
.ch-hero .gridbg {
  position: absolute;
  inset: 0;
  opacity: .4;
  pointer-events: none;
  background-image: linear-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, .05) 1px, transparent 1px);
  background-size: 48px 48px;
  -webkit-mask-image: radial-gradient(ellipse 80% 80% at 50% 25%, #000 30%, transparent 80%);
  mask-image: radial-gradient(ellipse 80% 80% at 50% 25%, #000 30%, transparent 80%);
}
.ch-hero-inner {
  position: relative;
  z-index: 1;
  text-align: center;
  max-width: 760px;
  margin: 0 auto;
  padding: 56px 0 44px;
}
.ch-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  background: rgba(255, 255, 255, .1);
  border: 1px solid rgba(255, 255, 255, .2);
  border-radius: 20px;
  padding: 6px 15px;
  color: rgba(255, 255, 255, .92);
  margin-bottom: 20px;
}
.ch-eyebrow svg { width: 14px; height: 14px; color: var(--accent); }
.ch-hero h1 {
  font-family: 'Sora', sans-serif;
  font-size: clamp(2.1rem, 4.6vw, 3.3rem);
  font-weight: 800;
  line-height: 1.08;
  letter-spacing: -.025em;
  margin-bottom: 16px;
}
.ch-hero h1 span { color: var(--accent); }
.ch-hero .lede {
  font-size: 1.08rem;
  color: rgba(255, 255, 255, .76);
  line-height: 1.6;
  max-width: 560px;
  margin: 0 auto 28px;
}
/* hero search */
.ch-search {
  display: flex;
  gap: 8px;
  background: #fff;
  border-radius: 14px;
  padding: 8px;
  max-width: 580px;
  margin: 0 auto;
  box-shadow: 0 18px 50px rgba(0, 0, 0, .28);
}
.ch-search .field {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 0 12px;
}
.ch-search .field svg { width: 18px; height: 18px; color: var(--muted); flex-shrink: 0; }
.ch-search input {
  border: none;
  outline: none;
  font-family: 'Inter', sans-serif;
  font-size: .95rem;
  width: 100%;
  color: var(--text);
  background: none;
  padding: 12px 0;
}
.ch-search .btn { flex-shrink: 0; }
.ch-pop { margin-top: 16px; font-size: .83rem; color: rgba(255, 255, 255, .6); }
.ch-pop a {
  color: rgba(255, 255, 255, .85);
  text-decoration: underline;
  text-underline-offset: 2px;
  margin: 0 4px;
}
.ch-pop a:hover { color: #fff; }
/* hero stat row */
.ch-statrow {
  display: flex;
  justify-content: center;
  gap: 34px;
  margin-top: 30px;
  flex-wrap: wrap;
}
.ch-stat { text-align: center; }
.ch-stat-n { font-family: 'Sora', sans-serif; font-size: 1.5rem; font-weight: 800; color: #fff; }
.ch-stat-n span { color: var(--accent); }
.ch-stat-l { font-size: .74rem; color: rgba(255, 255, 255, .55); margin-top: 2px; }
/* sections */
.sec { padding: 68px 0; }
.sec.tint { background: var(--bg); }
.sec.white { background: #fff; }
.sec-head { text-align: center; max-width: 660px; margin: 0 auto 44px; }
.sec-head .section-title { font-size: clamp(1.5rem, 2.8vw, 2.2rem); font-weight: 800; line-height: 1.15; margin-bottom: 12px; }
.sec-head p { color: var(--muted); font-size: .96rem; line-height: 1.6; }
/* PILLARS (4 big feature blocks) */
.pillar { display: grid; grid-template-columns: 1fr 1fr; gap: 44px; align-items: center; margin-bottom: 64px; }
.pillar:last-child { margin-bottom: 0; }
.pillar.rev .pillar-media { order: 2; }
.pillar-label {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .06em;
  text-transform: uppercase;
  color: var(--brand);
  background: var(--brand-light);
  padding: 5px 13px;
  border-radius: 20px;
  margin-bottom: 14px;
}
.pillar-label svg { width: 14px; height: 14px; }
.pillar h2 { font-family: 'Sora', sans-serif; font-size: clamp(1.4rem, 2.5vw, 2rem); font-weight: 800; line-height: 1.18; margin-bottom: 12px; }
.pillar > div > p { color: var(--muted); font-size: .95rem; line-height: 1.65; margin-bottom: 18px; }
.pillar-list { list-style: none; display: flex; flex-direction: column; gap: 10px; margin-bottom: 22px; }
.pillar-list li { display: flex; align-items: flex-start; gap: 10px; font-size: .9rem; color: var(--text); line-height: 1.5; }
.pillar-list svg { width: 18px; height: 18px; color: var(--success); flex-shrink: 0; margin-top: 1px; }
.pillar-actions { display: flex; gap: 10px; flex-wrap: wrap; }
/* pillar media mockups */
.pillar-media { position: relative; }
.mock { background: #fff; border: 1px solid var(--border); border-radius: 16px; box-shadow: var(--shadow-lg); overflow: hidden; }
.mock-top { display: flex; align-items: center; gap: 7px; padding: 13px 16px; border-bottom: 1px solid var(--border); background: var(--bg); }
.mock-dot { width: 9px; height: 9px; border-radius: 50%; }
.mock-body { padding: 18px; }
/* job result mock */
.jobrow { display: flex; gap: 12px; align-items: center; padding: 12px; border: 1px solid var(--border); border-radius: 11px; margin-bottom: 10px; }
.jobrow:last-child { margin-bottom: 0; }
.joblogo { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-weight: 800; color: #fff; font-size: .85rem; flex-shrink: 0; }
.jobinfo { flex: 1; min-width: 0; }
.jobinfo h4 { font-family: 'Sora', sans-serif; font-size: .88rem; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.jobinfo p { font-size: .76rem; color: var(--muted); }
.jobmeta { font-size: .68rem; font-weight: 700; color: var(--brand); background: var(--brand-light); padding: 4px 9px; border-radius: 12px; flex-shrink: 0; }
/* tracker mock */
.trk-step { display: flex; align-items: center; gap: 12px; padding: 11px 0; }
.trk-step + .trk-step { border-top: 1px solid var(--border); }
.trk-ic { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.trk-ic svg { width: 16px; height: 16px; }
.trk-done { background: #dcfce7; color: #16a34a; }
.trk-active { background: var(--accent); color: #fff; }
.trk-todo { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
.trk-tx { flex: 1; }
.trk-tx h4 { font-family: 'Sora', sans-serif; font-size: .85rem; font-weight: 600; color: var(--text); }
.trk-tx p { font-size: .72rem; color: var(--muted); }
.trk-badge { font-size: .66rem; font-weight: 700; padding: 3px 9px; border-radius: 11px; }
.tb-done { color: #16a34a; background: #dcfce7; }
.tb-active { color: var(--accent-dark); background: #fef3c7; }
/* profile meter mock */
.pm-ring { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
.pm-circ { width: 64px; height: 64px; border-radius: 50%; background: conic-gradient(var(--brand) 75%, var(--border) 0); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.pm-circ span { width: 48px; height: 48px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-weight: 800; font-size: .95rem; color: var(--brand); }
.pm-tx h4 { font-family: 'Sora', sans-serif; font-size: .92rem; font-weight: 700; }
.pm-tx p { font-size: .78rem; color: var(--muted); }
.pm-task { display: flex; align-items: center; gap: 9px; font-size: .82rem; padding: 7px 0; color: var(--text); }
.pm-task svg { width: 16px; height: 16px; flex-shrink: 0; }
.pm-task.done { color: var(--muted); }
.pm-task.done svg { color: var(--success); }
.pm-task.todo svg { color: var(--border); }
/* TOOLS grid */
.tools-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.tool-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 26px 24px; transition: var(--transition); display: flex; flex-direction: column; text-decoration: none; }
.tool-card:hover { border-color: var(--brand); box-shadow: var(--shadow-lg); transform: translateY(-4px); text-decoration: none; }
.tool-ic { width: 50px; height: 50px; border-radius: 13px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; }
.tool-ic svg { width: 25px; height: 25px; }
.t1 { background: #e6f0f8; color: #0D609E; }
.t2 { background: #f3e8ff; color: #7c3aed; }
.t3 { background: #dcfce7; color: #16a34a; }
.t4 { background: #fef3c7; color: #C8770E; }
.t5 { background: #e0f2fe; color: #0891b2; }
.t6 { background: #fee2e2; color: #dc2626; }
.tool-card h3 { font-family: 'Sora', sans-serif; font-size: 1.05rem; font-weight: 700; color: var(--text); margin-bottom: 7px; display: flex; align-items: center; gap: 7px; }
.tool-card p { font-size: .85rem; color: var(--muted); line-height: 1.6; margin-bottom: 14px; flex: 1; }
.tool-link { font-size: .84rem; font-weight: 700; color: var(--brand); display: inline-flex; align-items: center; gap: 5px; }
.tool-badge { font-size: .6rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; color: #fff; background: var(--accent); padding: 3px 8px; border-radius: 10px; }
/* CATEGORIES */
.cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
.cat-chip { display: flex; align-items: center; gap: 11px; background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 15px 16px; text-decoration: none; transition: var(--transition); }
.cat-chip:hover { border-color: var(--brand); background: var(--brand-light); text-decoration: none; transform: translateY(-2px); }
.cat-ic { width: 38px; height: 38px; border-radius: 10px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cat-ic svg { width: 19px; height: 19px; }
.cat-tx h4 { font-family: 'Sora', sans-serif; font-size: .88rem; font-weight: 700; color: var(--text); }
.cat-tx p { font-size: .73rem; color: var(--muted); }
/* ADVICE / blog cards */
.adv-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
.adv-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; text-decoration: none; transition: var(--transition); display: flex; flex-direction: column; }
.adv-card:hover { border-color: var(--brand); box-shadow: var(--shadow-lg); transform: translateY(-3px); text-decoration: none; }
.adv-thumb { height: 120px; display: flex; align-items: center; justify-content: center; }
.adv-thumb svg { width: 36px; height: 36px; color: rgba(255, 255, 255, .3); }
.adv-cat { font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--brand); margin-bottom: 6px; }
.adv-body h3 { font-family: 'Sora', sans-serif; font-size: .96rem; font-weight: 700; line-height: 1.3; color: var(--text); margin-bottom: 7px; }
.adv-body { padding: 18px; }
.adv-meta { font-size: .74rem; color: var(--muted); }
/* STEPS to start */
.start-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
.start-card { text-align: center; padding: 8px; }
.start-num { width: 46px; height: 46px; border-radius: 50%; background: var(--brand); color: #fff; font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1.15rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
.start-card h3 { font-family: 'Sora', sans-serif; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; }
.start-card p { font-size: .87rem; color: var(--muted); line-height: 1.6; }
/* FAQ */
.faq-wrap { max-width: 760px; margin: 0 auto; }
.faq-item { background: #fff; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 12px; overflow: hidden; transition: var(--transition); }
.faq-item:hover { border-color: #cfe0f1; }
.faq-q { width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 22px; background: none; border: none; cursor: pointer; font-family: 'Sora', sans-serif; font-size: .95rem; font-weight: 700; color: var(--text); text-align: left; line-height: 1.4; }
.faq-q svg { width: 19px; height: 19px; color: var(--brand); flex-shrink: 0; transition: transform .2s; }
.faq-item.open .faq-q svg { transform: rotate(45deg); }
.faq-a { max-height: 0; overflow: hidden; transition: max-height .26s ease; }
.faq-a-in { padding: 0 22px 18px; font-size: .88rem; color: var(--muted); line-height: 1.7; }
.faq-item.open .faq-a { max-height: 240px; }
/* final CTA */
.final-cta {
  background: radial-gradient(ellipse 60% 80% at 50% 0%, rgba(237, 144, 32, .18), transparent 60%), linear-gradient(160deg, #0A2F57, #064A85);
  color: #fff;
  border-radius: 20px;
  padding: 54px 40px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.final-cta h2 { font-family: 'Sora', sans-serif; font-size: clamp(1.6rem, 3vw, 2.3rem); font-weight: 800; margin-bottom: 12px; }
.final-cta p { font-size: 1rem; color: rgba(255, 255, 255, .74); margin-bottom: 26px; max-width: 520px; margin: 0 auto 26px; }
.final-cta-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.sticky-cta { display: none; }

@media (max-width: 780px) {
  .sticky-cta {
    display: flex;
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 950;
    gap: 10px;
    padding: 12px 16px calc(12px + env(safe-area-inset-bottom, 0px));
    background: rgba(255, 255, 255, .96);
    backdrop-filter: blur(10px);
    border-top: 1px solid var(--border);
    box-shadow: 0 -4px 20px rgba(10, 47, 87, .1);
  }
  .sticky-cta .btn { flex: 1; justify-content: center; min-height: 46px; }
  body { padding-bottom: 72px; }
}
@media (max-width: 900px) {
  .pillar { grid-template-columns: 1fr; gap: 26px; margin-bottom: 48px; }
  .pillar.rev .pillar-media { order: 0; }
  .tools-grid, .adv-grid, .start-grid { grid-template-columns: repeat(2, 1fr); }
  .cat-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 580px) {
  .sec { padding: 46px 0; }
  .tools-grid, .adv-grid, .start-grid, .cat-grid { grid-template-columns: 1fr; }
  .ch-search { flex-direction: column; padding: 12px; }
  .ch-search .field { padding: 4px 8px; }
  .ch-search .btn { width: 100%; justify-content: center; }
  .ch-statrow { gap: 22px; }
  .final-cta { padding: 34px 22px; }
}

/* trust strip */
.trust-strip { background: #fff; border-bottom: 1px solid var(--border); }
.trust-inner { display: flex; justify-content: center; gap: 32px; flex-wrap: wrap; padding: 20px 0; }
.trust-item { display: flex; align-items: center; gap: 9px; font-size: .86rem; font-weight: 600; color: var(--text); }
.trust-item svg { width: 18px; height: 18px; color: var(--success); flex-shrink: 0; }
.trust-item.report svg { color: var(--brand); }
/* testimonials */
.tm-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
.tm-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 26px 24px; display: flex; flex-direction: column; }
.tm-stars { display: flex; gap: 2px; margin-bottom: 12px; }
.tm-stars svg { width: 15px; height: 15px; color: var(--accent); }
.tm-card p { font-size: .9rem; color: var(--text); line-height: 1.6; margin-bottom: 18px; flex: 1; }
.tm-who { display: flex; align-items: center; gap: 11px; }
.tm-av { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-weight: 800; color: #fff; font-size: .85rem; flex-shrink: 0; }
.tm-who strong { display: block; font-size: .86rem; color: var(--text); }
.tm-who span { font-size: .76rem; color: var(--muted); }
@media (max-width: 900px) {
  .tm-grid { grid-template-columns: 1fr; }
  .trust-inner { gap: 18px; }
}
.pg-bc {
  position: relative;
  z-index: 1;
  display: flex;
  gap: 7px;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  font-family: 'Inter', sans-serif;
  font-size: .76rem;
  color: rgba(255, 255, 255, .6);
  margin-bottom: 18px;
}
.pg-bc a { color: rgba(255, 255, 255, .6); text-decoration: none; }
.pg-bc a:hover { color: #fff; }
.pg-bc svg { width: 12px; height: 12px; opacity: .5; }
.pg-bc [aria-current] { color: rgba(255, 255, 255, .85); font-weight: 600; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main">

  <!-- HERO with search -->
  <section class="ch-hero">
    <span class="gridbg" aria-hidden="true"></span>
    <div class="container">
      <div class="ch-hero-inner">
        <nav class="pg-bc" aria-label="Breadcrumb">
          <a href="<?= base_url() ?>">Home</a>
          <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
          <span aria-current="page">Candidate hub</span>
        </nav>
        <div class="ch-eyebrow"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-rocket"/></svg>Your candidate hub</div>
        <h1>Your career, <span>all in one place</span></h1>
        <p class="lede">Search jobs, build a standout CV, practise interviews, track your applications and learn from expert advice &mdash; everything you need to land your next role, free.</p>
        <form class="ch-search" role="search" onsubmit="return goSearch(event)">
          <div class="field">
            <svg aria-hidden="true" style="width:18px;height:18px"><use href="#i-search"/></svg>
            <input type="search" id="ch-q" placeholder="Job title, skill or company" aria-label="Search jobs">
          </div>
          <button type="submit" class="btn btn-accent">Search jobs</button>
        </form>
        <div class="ch-pop">Popular: 
          <a href="<?= base_url('jobs?q=software+developer') ?>">Software Developer</a> 
          <a href="<?= base_url('jobs?q=accountant') ?>">Accountant</a> 
          <a href="<?= base_url('jobs?q=remote') ?>">Remote</a> 
          <a href="<?= base_url('jobs?q=sales') ?>">Sales</a>
        </div>
        <div class="ch-statrow">
          <div class="ch-stat"><div class="ch-stat-n"><?= number_format($live_jobs_count ?? 1500) ?><span>+</span></div><div class="ch-stat-l">Live jobs</div></div>
          <div class="ch-stat"><div class="ch-stat-n"><?= number_format($employer_count ?? 250) ?><span>+</span></div><div class="ch-stat-l">Hiring companies</div></div>
          <div class="ch-stat"><div class="ch-stat-n">6</div><div class="ch-stat-l">Free career tools</div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- TRUST STRIP -->
  <section class="trust-strip" aria-label="Why job seekers trust us">
    <div class="container">
      <div class="trust-inner">
        <div class="trust-item"><svg aria-hidden="true"><use href="#i-shield"/></svg>Verified employers only</div>
        <div class="trust-item"><svg aria-hidden="true"><use href="#i-lock"/></svg>We never charge job seekers</div>
        <div class="trust-item"><svg aria-hidden="true"><use href="#i-check-circle"/></svg>100% free to apply</div>
        <div class="trust-item report"><svg aria-hidden="true"><use href="#i-bell"/></svg>Report a suspicious job anytime</div>
      </div>
    </div>
  </section>

  <!-- FOUR PILLARS -->
  <section class="sec white">
    <div class="container">

      <!-- Pillar 1: Job search -->
      <div class="pillar">
        <div>
          <div class="pillar-label"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-search"/></svg>Find jobs</div>
          <h2>Land a job that actually fits</h2>
          <p>Skip the endless scrolling. Search verified roles, filter to exactly what you want, and let matched recommendations bring the right jobs to you.</p>
          <ul class="pillar-list">
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Smart search &amp; filters by role, location and pay</li>
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Personalised job recommendations</li>
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Save jobs and set up instant job alerts</li>
          </ul>
          <div class="pillar-actions">
            <a href="<?= base_url('jobs') ?>" class="btn btn-primary">Browse jobs</a>
            <a href="<?= base_url('register?type=candidate') ?>" class="btn btn-outline">Get job alerts</a>
          </div>
        </div>
        <div class="pillar-media">
          <div class="mock">
            <div class="mock-top"><span class="mock-dot" style="background:#ef4444"></span><span class="mock-dot" style="background:#f59e0b"></span><span class="mock-dot" style="background:#22c55e"></span></div>
            <div class="mock-body">
              <div class="jobrow"><span class="joblogo" style="background:#0861A9">PB</span><span class="jobinfo"><h4>Frontend Developer</h4><p>Paybridge &middot; Lagos &middot; Hybrid</p></span><span class="jobmeta">New</span></div>
              <div class="jobrow"><span class="joblogo" style="background:#16a34a">GT</span><span class="jobinfo"><h4>Data Analyst</h4><p>GreenTrust &middot; Remote</p></span><span class="jobmeta">Featured</span></div>
              <div class="jobrow"><span class="joblogo" style="background:#C8770E">KM</span><span class="jobinfo"><h4>Marketing Manager</h4><p>KamiMedia &middot; Abuja</p></span><span class="jobmeta">Urgent</span></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pillar 2: Career tools -->
      <div class="pillar rev">
        <div class="pillar-media">
          <div class="mock">
            <div class="mock-top"><span class="mock-dot" style="background:#ef4444"></span><span class="mock-dot" style="background:#f59e0b"></span><span class="mock-dot" style="background:#22c55e"></span></div>
            <div class="mock-body">
              <div class="pm-ring">
                <div class="pm-circ"><span>75%</span></div>
                <div class="pm-tx"><h4>CV strength</h4><p>Almost there &mdash; add 2 more sections</p></div>
              </div>
              <div class="pm-task done"><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Contact details added</div>
              <div class="pm-task done"><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Work experience added</div>
              <div class="pm-task todo"><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Add a professional summary</div>
              <div class="pm-task todo"><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Add key skills</div>
            </div>
          </div>
        </div>
        <div>
          <div class="pillar-label"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-spark"/></svg>Career tools</div>
          <h2>Get past the filter and into the interview</h2>
          <p>Most CVs are rejected by software before a human sees them. Build an ATS-ready CV, rehearse real interview questions, and walk in knowing your worth.</p>
          <ul class="pillar-list">
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>AI Resume Builder with instant scoring</li>
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>AI Mock Interview with feedback</li>
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Salary calculator for Nigerian roles</li>
          </ul>
          <div class="pillar-actions">
            <a href="#tools" class="btn btn-primary">Explore all tools</a>
          </div>
        </div>
      </div>

      <!-- Pillar 3: Application tracking -->
      <div class="pillar">
        <div>
          <div class="pillar-label"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-sliders"/></svg>Track progress</div>
          <h2>Always know your next move</h2>
          <p>No more wondering &ldquo;did they see it?&rdquo; See exactly where every application stands and what to do next &mdash; all in one place.</p>
          <ul class="pillar-list">
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Live status on every application</li>
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Saved jobs and reminders</li>
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Profile completion guidance</li>
          </ul>
          <div class="pillar-actions">
            <a href="<?= base_url('register?type=candidate') ?>" class="btn btn-primary">Create free account</a>
          </div>
        </div>
        <div class="pillar-media">
          <div class="mock">
            <div class="mock-top"><span class="mock-dot" style="background:#ef4444"></span><span class="mock-dot" style="background:#f59e0b"></span><span class="mock-dot" style="background:#22c55e"></span></div>
            <div class="mock-body">
              <div class="trk-step"><span class="trk-ic trk-done"><svg aria-hidden="true"><use href="#i-check"/></svg></span><span class="trk-tx"><h4>Frontend Developer &middot; Paybridge</h4><p>Applied 2 days ago</p></span><span class="trk-badge tb-active">In review</span></div>
              <div class="trk-step"><span class="trk-ic trk-done"><svg aria-hidden="true"><use href="#i-check"/></svg></span><span class="trk-tx"><h4>Data Analyst &middot; GreenTrust</h4><p>Applied 5 days ago</p></span><span class="trk-badge tb-done">Shortlisted</span></div>
              <div class="trk-step"><span class="trk-ic trk-active"><svg aria-hidden="true"><use href="#i-bell"/></svg></span><span class="trk-tx"><h4>UX Designer &middot; Kanto</h4><p>Interview Thu, 10am</p></span><span class="trk-badge tb-active">Interview</span></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pillar 4: Training & advice -->
      <div class="pillar rev">
        <div class="pillar-media">
          <div class="mock">
            <div class="mock-top"><span class="mock-dot" style="background:#ef4444"></span><span class="mock-dot" style="background:#f59e0b"></span><span class="mock-dot" style="background:#22c55e"></span></div>
            <div class="mock-body">
              <div class="jobrow"><span class="joblogo" style="background:#7c3aed"><svg aria-hidden="true" style="width:20px;height:20px;color:#fff"><use href="#i-mic"/></svg></span><span class="jobinfo"><h4>Acing Your Interview</h4><p>Live webinar &middot; Sat 11am</p></span><span class="jobmeta">Free</span></div>
              <div class="jobrow"><span class="joblogo" style="background:#0891b2"><svg aria-hidden="true" style="width:20px;height:20px;color:#fff"><use href="#i-doc"/></svg></span><span class="jobinfo"><h4>CV Writing Masterclass</h4><p>On-demand course</p></span><span class="jobmeta">New</span></div>
              <div class="jobrow"><span class="joblogo" style="background:#16a34a"><svg aria-hidden="true" style="width:20px;height:20px;color:#fff"><use href="#i-cap"/></svg></span><span class="jobinfo"><h4>Breaking Into Tech</h4><p>Guide &middot; 16 min read</p></span><span class="jobmeta">Popular</span></div>
            </div>
          </div>
        </div>
        <div>
          <div class="pillar-label"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-cap"/></svg>Learn &amp; grow</div>
          <h2>Learn what hiring managers want</h2>
          <p>Get the inside track with free webinars, courses and guides from the people who actually do the hiring in Nigeria.</p>
          <ul class="pillar-list">
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Live and on-demand career webinars</li>
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Courses to build in-demand skills</li>
            <li><svg aria-hidden="true"><use href="#i-check-circle"/></svg>Expert guides on the JobberRecruit blog</li>
          </ul>
          <div class="pillar-actions">
            <a href="<?= base_url('training/webinars') ?>" class="btn btn-primary">Browse webinars</a>
            <a href="<?= base_url('blog') ?>" class="btn btn-outline">Read the blog</a>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- TOOLS GRID -->
  <section class="sec tint" id="tools" aria-labelledby="tools-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-spark"/></svg>Free career tools</div>
        <h2 class="section-title" id="tools-h">Everything you need to <span>stand out</span></h2>
        <p>Six free tools designed to help you get noticed, prepare, and land the offer.</p>
      </div>
      <div class="tools-grid">
        <a class="tool-card" href="<?= base_url('candidate/resumes') ?>"><div class="tool-ic t1"><svg aria-hidden="true" style="width:25px;height:25px"><use href="#i-doc"/></svg></div><h3>AI Resume Builder</h3><p>Create an ATS-friendly CV in minutes with smart suggestions and instant scoring.</p><span class="tool-link">Build my CV <svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-rocket"/></svg></span></a>
        <a class="tool-card" href="<?= base_url('candidate/career-tools/mock-interview') ?>"><div class="tool-ic t2"><svg aria-hidden="true" style="width:25px;height:25px"><use href="#i-mic"/></svg></div><h3>AI Mock Interview <span class="tool-badge">New</span></h3><p>Practise real interview questions and get instant, actionable AI feedback.</p><span class="tool-link">Start practising <svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-rocket"/></svg></span></a>
        <a class="tool-card" href="<?= base_url('cv-review') ?>"><div class="tool-ic t3"><svg aria-hidden="true" style="width:25px;height:25px"><use href="#i-edit"/></svg></div><h3>CV Revamp</h3><p>Get your existing CV professionally rewritten and optimised by experts.</p><span class="tool-link">Revamp my CV <svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-rocket"/></svg></span></a>
        <a class="tool-card" href="<?= base_url('candidate/career-tools/salary-negotiation') ?>"><div class="tool-ic t4"><svg aria-hidden="true" style="width:25px;height:25px"><use href="#i-coins"/></svg></div><h3>Salary Calculator</h3><p>Know what your role really pays in Nigeria before you negotiate.</p><span class="tool-link">Check salary <svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-rocket"/></svg></span></a>
        <a class="tool-card" href="<?= base_url('webinars') ?>"><div class="tool-ic t5"><svg aria-hidden="true" style="width:25px;height:25px"><use href="#i-mic"/></svg></div><h3>Career Webinars</h3><p>Join free live sessions with recruiters and industry experts.</p><span class="tool-link">See webinars <svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-rocket"/></svg></span></a>
        <a class="tool-card" href="<?= base_url('aptitude') ?>"><div class="tool-ic t6"><svg aria-hidden="true" style="width:25px;height:25px"><use href="#i-bulb"/></svg></div><h3>Career Path Quiz</h3><p>Not sure what&rsquo;s next? Discover roles that fit your strengths.</p><span class="tool-link">Take the quiz <svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-rocket"/></svg></span></a>
      </div>
    </div>
  </section>

  <!-- BROWSE BY CATEGORY -->
  <section class="sec white" aria-labelledby="cat-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-building"/></svg>Browse by field</div>
        <h2 class="section-title" id="cat-h">Find jobs in <span>your industry</span></h2>
      </div>
      <div class="cat-grid">
        <a class="cat-chip" href="<?= base_url('jobs?category=it') ?>"><span class="cat-ic"><svg aria-hidden="true"><use href="#i-chip"/></svg></span><span class="cat-tx"><h4>IT &amp; Software</h4><p><?= number_format($count_it ?? 0) ?> jobs</p></span></a>
        <a class="cat-chip" href="<?= base_url('jobs?category=banking') ?>"><span class="cat-ic"><svg aria-hidden="true"><use href="#i-bank"/></svg></span><span class="cat-tx"><h4>Banking &amp; Finance</h4><p><?= number_format($count_banking ?? 0) ?> jobs</p></span></a>
        <a class="cat-chip" href="<?= base_url('jobs?category=healthcare') ?>"><span class="cat-ic"><svg aria-hidden="true"><use href="#i-heart-pulse"/></svg></span><span class="cat-tx"><h4>Healthcare</h4><p><?= number_format($count_healthcare ?? 0) ?> jobs</p></span></a>
        <a class="cat-chip" href="<?= base_url('jobs?category=marketing') ?>"><span class="cat-ic"><svg aria-hidden="true"><use href="#i-mega"/></svg></span><span class="cat-tx"><h4>Marketing &amp; Sales</h4><p><?= number_format($count_marketing ?? 0) ?> jobs</p></span></a>
        <a class="cat-chip" href="<?= base_url('jobs?category=engineering') ?>"><span class="cat-ic"><svg aria-hidden="true"><use href="#i-gear"/></svg></span><span class="cat-tx"><h4>Engineering</h4><p><?= number_format($count_engineering ?? 0) ?> jobs</p></span></a>
        <a class="cat-chip" href="<?= base_url('jobs?category=education') ?>"><span class="cat-ic"><svg aria-hidden="true"><use href="#i-cap"/></svg></span><span class="cat-tx"><h4>Education</h4><p><?= number_format($count_education ?? 0) ?> jobs</p></span></a>
        <a class="cat-chip" href="<?= base_url('jobs?category=operations') ?>"><span class="cat-ic"><svg aria-hidden="true"><use href="#i-bag"/></svg></span><span class="cat-tx"><h4>Operations &amp; Admin</h4><p><?= number_format($count_operations ?? 0) ?> jobs</p></span></a>
        <a class="cat-chip" href="<?= base_url('jobs?category=remote') ?>"><span class="cat-ic"><svg aria-hidden="true"><use href="#i-globe"/></svg></span><span class="cat-tx"><h4>Remote Jobs</h4><p><?= number_format($count_remote ?? 0) ?> jobs</p></span></a>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section class="sec white" aria-labelledby="tm-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-star"/></svg>Real candidates, real results</div>
        <h2 class="section-title" id="tm-h">Job seekers who <span>got hired</span></h2>
        <p>Thousands of Nigerians have found their next role through JobberRecruit.</p>
      </div>
      <div class="tm-grid">
        <div class="tm-card">
          <div class="tm-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div>
          <p>&ldquo;The AI resume builder helped me fix my CV, and I started getting interview calls within a week. Landed a frontend role in under a month.&rdquo;</p>
          <div class="tm-who"><span class="tm-av" style="background:#0D609E">CN</span><div><strong>Chioma N.</strong><span>Frontend Developer, Lagos</span></div></div>
        </div>
        <div class="tm-card">
          <div class="tm-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div>
          <p>&ldquo;I practised with the mock interview tool before my final round. Walked in confident and got the offer. The salary calculator helped me negotiate too.&rdquo;</p>
          <div class="tm-who"><span class="tm-av" style="background:#16a34a">EO</span><div><strong>Emeka O.</strong><span>Data Analyst, Remote</span></div></div>
        </div>
        <div class="tm-card">
          <div class="tm-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div>
          <p>&ldquo;Being able to track all my applications in one place kept me sane during my job search. No more guessing where I stood with each company.&rdquo;</p>
          <div class="tm-who"><span class="tm-av" style="background:#C8770E">AB</span><div><strong>Aisha B.</strong><span>Marketing Manager, Abuja</span></div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW TO START -->
  <section class="sec tint" aria-labelledby="start-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-rocket"/></svg>Get started</div>
        <h2 class="section-title" id="start-h">Start in <span>three simple steps</span></h2>
      </div>
      <div class="start-grid">
        <div class="start-card"><div class="start-num">1</div><h3>Create your free account</h3><p>Sign up in under two minutes and build your candidate profile.</p></div>
        <div class="start-card"><div class="start-num">2</div><h3>Build your CV &amp; apply</h3><p>Use the AI tools to polish your CV, then apply to matched jobs.</p></div>
        <div class="start-card"><div class="start-num">3</div><h3>Track &amp; land the offer</h3><p>Follow your applications, prep with webinars, and get hired.</p></div>
      </div>
    </div>
  </section>

  <!-- ADVICE / blog -->
  <section class="sec white" aria-labelledby="adv-h">
    <div class="container">
      <div class="sec-head">
        <div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-doc"/></svg>Career advice</div>
        <h2 class="section-title" id="adv-h">Most-read <span>career guides</span></h2>
        <p>Practical, Nigeria-specific advice to help you at every stage.</p>
      </div>
      <div class="adv-grid">
        <a class="adv-card" href="<?= base_url('blog/how-to-write-a-cv-nigeria') ?>"><div class="adv-thumb" style="background:linear-gradient(135deg,#0A2F57,#16a34a)"><svg aria-hidden="true" style="width:36px;height:36px"><use href="#i-doc"/></svg></div><div class="adv-body"><div class="adv-cat">CV Writing</div><h3>How to Write a CV That Gets Interviews in Nigeria</h3><div class="adv-meta">10 min read</div></div></a>
        <a class="adv-card" href="<?= base_url('blog/lagos-tech-jobs-yaba-ecosystem') ?>"><div class="adv-thumb" style="background:linear-gradient(135deg,#0A2F57,#0891b2)"><svg aria-hidden="true" style="width:36px;height:36px"><use href="#i-chip"/></svg></div><div class="adv-body"><div class="adv-cat">Industry</div><h3>Lagos Tech Jobs: A Guide to the Yaba Ecosystem</h3><div class="adv-meta">16 min read</div></div></a>
        <a class="adv-card" href="<?= base_url('blog/nigeria-salary-guide-2026') ?>"><div class="adv-thumb" style="background:linear-gradient(135deg,#064A85,#ED9020)"><svg aria-hidden="true" style="width:36px;height:36px"><use href="#i-coins"/></svg></div><div class="adv-body"><div class="adv-cat">Salary</div><h3>Nigeria Salary Guide 2026: What Employers Pay</h3><div class="adv-meta">14 min read</div></div></a>
      </div>
      <div style="text-align:center;margin-top:32px"><a href="<?= base_url('blog') ?>" class="btn btn-outline">Read more on the blog</a></div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="sec tint" aria-labelledby="faq-h">
    <div class="container">
      <div class="sec-head"><div class="section-label" style="margin:0 auto 14px"><svg aria-hidden="true" style="width:14px;height:14px"><use href="#i-bulb"/></svg>Questions &amp; answers</div><h2 class="section-title" id="faq-h">Job seeker <span>FAQ</span></h2></div>
      <div class="faq-wrap">
        <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">Is JobberRecruit free for job seekers? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Yes. Creating a candidate account, searching and applying for jobs, building your CV, practising interviews and tracking applications are all free for job seekers.</div></div></div>
        <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">Do I need an account to apply for jobs? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">You can browse jobs without an account, but you&rsquo;ll need a free candidate account to apply, save jobs, track applications and set up job alerts.</div></div></div>
        <div class="faq-item"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">What career tools are available? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">An AI resume builder, AI mock interview practice, professional CV revamp, a salary calculator, career webinars and a career path quiz &mdash; all designed to help you land your next role.</div></div></div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="sec white" style="padding-top:0">
    <div class="container">
      <div class="final-cta">
        <h2>Ready to land your next role?</h2>
        <p>Create your free candidate account and get the jobs, tools and guidance to move your career forward.</p>
        <div class="final-cta-actions">
          <a href="<?= base_url('register?type=candidate') ?>" class="btn btn-accent btn-lg"><svg aria-hidden="true" style="width:16px;height:16px"><use href="#i-rocket"/></svg>Create free account</a>
          <a href="<?= base_url('jobs') ?>" class="btn btn-white btn-lg">Browse jobs</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Sticky CTA for Mobile Devices -->
  <div class="sticky-cta" aria-label="Quick actions">
    <a href="<?= base_url('register?type=candidate') ?>" class="btn btn-accent"><svg aria-hidden="true" style="width:16px;height:16px"><use href="#i-rocket"/></svg>Create free account</a>
    <a href="<?= base_url('jobs') ?>" class="btn btn-outline">Browse jobs</a>
  </div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleFaq(btn) {
  var item = btn.parentElement;
  var opened = item.classList.toggle('open');
  btn.setAttribute('aria-expanded', String(opened));
}
function goSearch(e) {
  e.preventDefault();
  var q = document.getElementById('ch-q').value.trim();
  window.location.href = '<?= base_url('jobs') ?>' + (q ? ('?q=' + encodeURIComponent(q)) : '');
  return false;
}
</script>
<?= $this->endSection() ?>
