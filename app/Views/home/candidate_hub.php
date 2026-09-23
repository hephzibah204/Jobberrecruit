<?= $this->extend('templates/base') ?>

<?= $this->section('meta') ?>
<meta name="description" content="Search jobs, build your CV, practise interviews, track applications and learn from expert advice — free for job seekers in Nigeria.">
<meta name="keywords" content="find jobs Nigeria, job search, CV builder, interview practice, career tools, job seeker Nigeria, career advice">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
<meta name="geo.region" content="NG"><meta name="geo.placename" content="Nigeria">
<link rel="canonical" href="<?= base_url('candidates') ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="Candidate Hub — Find Jobs &amp; Grow Your Career | JobberRecruit">
<meta property="og:description" content="Search jobs, build your CV, practise interviews, track applications and learn — free for job seekers in Nigeria.">
<meta property="og:url" content="<?= base_url('candidates') ?>">
<meta property="og:image" content="<?= base_url('assets/og-candidate-hub.jpg') ?>">
<meta property="og:image:width" content="1200"><meta property="og:image:height" content="630">
<meta property="og:locale" content="en_NG"><meta property="og:site_name" content="JobberRecruit">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Candidate Hub — Find Jobs &amp; Grow Your Career">
<meta name="twitter:description" content="Everything job seekers need, free: jobs, CV tools, interview practice and advice.">
<meta name="twitter:image" content="<?= base_url('assets/og-candidate-hub.jpg') ?>">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","name":"Candidate Hub","url":"<?= base_url('candidates') ?>","inLanguage":"en-NG","description":"A hub for job seekers in Nigeria to find jobs, build CVs, practise interviews, track applications and access career advice.","isPartOf":{"@type":"WebSite","name":"JobberRecruit","url":"<?= base_url('/') ?>"},"publisher":{"@type":"Organization","name":"JobberRecruit","url":"<?= base_url('/') ?>","logo":{"@type":"ImageObject","url":"<?= base_url('assets/logo.png') ?>"}}}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"<?= base_url('/') ?>"},{"@type":"ListItem","position":2,"name":"Candidate hub","item":"<?= base_url('candidates') ?>"}]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"Is JobberRecruit free for job seekers?","acceptedAnswer":{"@type":"Answer","text":"Yes. Creating a candidate account, searching and applying for jobs, building your CV, practising interviews and tracking applications are all free for job seekers."}},{"@type":"Question","name":"Do I need an account to apply for jobs?","acceptedAnswer":{"@type":"Answer","text":"You can browse jobs without an account, but you'll need a free candidate account to apply, save jobs, track applications and set up job alerts."}},{"@type":"Question","name":"What career tools are available?","acceptedAnswer":{"@type":"Answer","text":"An AI resume builder, AI mock interview practice, professional CV revamp, a salary calculator, career webinars and a career path quiz - all designed to help you land your next role."}}]}
</script>
<?= $this->endSection() ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","name":"Candidate Hub","url":"<?= base_url('candidates') ?>","inLanguage":"en-NG","description":"A hub for job seekers in Nigeria to find jobs, build CVs, practise interviews, track applications and access career advice.","isPartOf":{"@type":"WebSite","name":"JobberRecruit","url":"<?= base_url('/') ?>"},"publisher":{"@type":"Organization","name":"JobberRecruit","url":"<?= base_url('/') ?>","logo":{"@type":"ImageObject","url":"<?= base_url('assets/logo.png') ?>"}}}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"<?= base_url('/') ?>"},{"@type":"ListItem","position":2,"name":"Candidate hub","item":"<?= base_url('candidates') ?>"}]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"Is JobberRecruit free for job seekers?","acceptedAnswer":{"@type":"Answer","text":"Yes. Creating a candidate account, searching and applying for jobs, building your CV, practising interviews and tracking applications are all free for job seekers."}},{"@type":"Question","name":"Do I need an account to apply for jobs?","acceptedAnswer":{"@type":"Answer","text":"You can browse jobs without an account, but you'll need a free candidate account to apply, save jobs, track applications and set up job alerts."}},{"@type":"Question","name":"What career tools are available?","acceptedAnswer":{"@type":"Answer","text":"An AI resume builder, AI mock interview practice, professional CV revamp, a salary calculator, career webinars and a career path quiz - all designed to help you land your next role."}}]}
</script>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* ===== CANDIDATE HUB — page-scoped styles ===== */
.ch-hero{background:radial-gradient(ellipse 60% 50% at 85% 20%,rgba(237,144,32,.18) 0%,transparent 55%),radial-gradient(ellipse 70% 60% at 5% 95%,rgba(8,97,169,.35) 0%,transparent 55%),linear-gradient(155deg,#0A2F57 0%,#0A2F57 40%,#064A85 100%);color:#fff;position:relative;overflow:hidden}
.ch-hero .gridbg{position:absolute;inset:0;opacity:.4;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:48px 48px;-webkit-mask-image:radial-gradient(ellipse 80% 80% at 50% 25%,#000 30%,transparent 80%);mask-image:radial-gradient(ellipse 80% 80% at 50% 25%,#000 30%,transparent 80%)}
.ch-hero-inner{position:relative;z-index:1;text-align:center;max-width:760px;margin:0 auto;padding:56px 0 44px}
.ch-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:20px;padding:6px 15px;color:rgba(255,255,255,.92);margin-bottom:20px}
.ch-eyebrow svg{width:14px;height:14px;color:#ED9020}
.ch-hero h1{font-family:'Sora',sans-serif;font-size:clamp(2.1rem,4.6vw,3.3rem);font-weight:800;line-height:1.08;letter-spacing:-.025em;margin-bottom:16px}
.ch-hero h1 span{color:#ED9020}
.ch-hero .lede{font-size:1.08rem;color:rgba(255,255,255,.76);line-height:1.6;max-width:560px;margin:0 auto 28px}
.ch-search{display:flex;gap:8px;background:#fff;border-radius:14px;padding:8px;max-width:580px;margin:0 auto;box-shadow:0 18px 50px rgba(0,0,0,.28)}
.ch-search .field{flex:1;display:flex;align-items:center;gap:9px;padding:0 12px}
.ch-search .field svg{width:18px;height:18px;color:#5b6577;flex-shrink:0}
.ch-search input{border:none;outline:none;font-family:'Inter',sans-serif;font-size:.95rem;width:100%;color:#141926;background:none;padding:12px 0}
.ch-search .btn{flex-shrink:0}
.ch-pop{margin-top:16px;font-size:.83rem;color:rgba(255,255,255,.6)}
.ch-pop a{color:rgba(255,255,255,.85);text-decoration:underline;text-underline-offset:2px;margin:0 4px}
.ch-pop a:hover{color:#fff}
.ch-statrow{display:flex;justify-content:center;gap:34px;margin-top:30px;flex-wrap:wrap}
.ch-stat{text-align:center}
.ch-stat-n{font-family:'Sora',sans-serif;font-size:1.5rem;font-weight:800;color:#fff}
.ch-stat-n span{color:#ED9020}
.ch-stat-l{font-size:.74rem;color:rgba(255,255,255,.55);margin-top:2px}

/* sections */
.ch-sec{padding:68px 0}
.ch-sec.ch-tint{background:#f5f7fb}
.ch-sec.ch-white{background:#fff}
.ch-sec-head{text-align:center;max-width:660px;margin:0 auto 44px}
.ch-sec-head h2{font-family:'Sora',sans-serif;font-size:clamp(1.5rem,2.8vw,2.2rem);font-weight:800;line-height:1.15;margin-bottom:12px;color:#141926}
.ch-sec-head h2 span{color:#0861A9}
.ch-sec-head p{color:#5b6577;font-size:.96rem;line-height:1.6}

/* TRUST STRIP */
.ch-trust{background:#fff;border-bottom:1px solid #e2e8f2}
.ch-trust-inner{display:flex;justify-content:center;gap:32px;flex-wrap:wrap;padding:20px 0}
.ch-trust-item{display:flex;align-items:center;gap:9px;font-size:.86rem;font-weight:600;color:#141926}
.ch-trust-item svg{width:18px;height:18px;color:#16a34a;flex-shrink:0}
.ch-trust-item.brand svg{color:#0861A9}

/* PILLARS */
.ch-pillar{display:grid;grid-template-columns:1fr 1fr;gap:44px;align-items:center;margin-bottom:64px}
.ch-pillar:last-child{margin-bottom:0}
.ch-pillar.rev .ch-pillar-media{order:2}
.ch-pillar-label{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#0861A9;background:#E6F0F8;padding:5px 13px;border-radius:20px;margin-bottom:14px}
.ch-pillar-label svg{width:14px;height:14px}
.ch-pillar h2{font-family:'Sora',sans-serif;font-size:clamp(1.4rem,2.5vw,2rem);font-weight:800;line-height:1.18;margin-bottom:12px;color:#141926}
.ch-pillar>div>p{color:#5b6577;font-size:.95rem;line-height:1.65;margin-bottom:18px}
.ch-pillar-list{list-style:none;display:flex;flex-direction:column;gap:10px;margin-bottom:22px;padding:0}
.ch-pillar-list li{display:flex;align-items:flex-start;gap:10px;font-size:.9rem;color:#141926;line-height:1.5}
.ch-pillar-list svg{width:18px;height:18px;color:#16a34a;flex-shrink:0;margin-top:1px}
.ch-pillar-actions{display:flex;gap:10px;flex-wrap:wrap}
.ch-pillar-media{position:relative}

/* mockup cards */
.ch-mock{background:#fff;border:1px solid #e2e8f2;border-radius:16px;box-shadow:0 14px 40px rgba(10,47,87,.16);overflow:hidden}
.ch-mock-top{display:flex;align-items:center;gap:7px;padding:13px 16px;border-bottom:1px solid #e2e8f2;background:#f5f7fb}
.ch-mock-dot{width:9px;height:9px;border-radius:50%}
.ch-mock-body{padding:18px}
.ch-jobrow{display:flex;gap:12px;align-items:center;padding:12px;border:1px solid #e2e8f2;border-radius:11px;margin-bottom:10px}
.ch-jobrow:last-child{margin-bottom:0}
.ch-joblogo{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;color:#fff;font-size:.85rem;flex-shrink:0}
.ch-jobinfo{flex:1;min-width:0}
.ch-jobinfo h4{font-family:'Sora',sans-serif;font-size:.88rem;font-weight:700;color:#141926;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:0}
.ch-jobinfo p{font-size:.76rem;color:#5b6577;margin:0}
.ch-jobmeta{font-size:.68rem;font-weight:700;color:#0861A9;background:#E6F0F8;padding:4px 9px;border-radius:12px;flex-shrink:0;white-space:nowrap}
.ch-trk-step{display:flex;align-items:center;gap:12px;padding:11px 0}
.ch-trk-step+.ch-trk-step{border-top:1px solid #e2e8f2}
.ch-trk-ic{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ch-trk-ic svg{width:16px;height:16px}
.ch-trk-done{background:#dcfce7;color:#16a34a}
.ch-trk-active{background:#ED9020;color:#fff}
.ch-trk-tx{flex:1}
.ch-trk-tx h4{font-family:'Sora',sans-serif;font-size:.85rem;font-weight:600;color:#141926;margin:0}
.ch-trk-tx p{font-size:.72rem;color:#5b6577;margin:0}
.ch-trk-badge{font-size:.66rem;font-weight:700;padding:3px 9px;border-radius:11px;white-space:nowrap}
.ch-tb-done{color:#16a34a;background:#dcfce7}
.ch-tb-active{color:#C8770E;background:#fef3c7}
.ch-pm-ring{display:flex;align-items:center;gap:16px;margin-bottom:16px}
.ch-pm-circ{width:64px;height:64px;border-radius:50%;background:conic-gradient(#0861A9 75%,#e2e8f2 0);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ch-pm-circ span{width:48px;height:48px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:.95rem;color:#0861A9}
.ch-pm-tx h4{font-family:'Sora',sans-serif;font-size:.92rem;font-weight:700;margin:0}
.ch-pm-tx p{font-size:.78rem;color:#5b6577;margin:0}
.ch-pm-task{display:flex;align-items:center;gap:9px;font-size:.82rem;padding:7px 0;color:#141926}
.ch-pm-task svg{width:16px;height:16px;flex-shrink:0}
.ch-pm-task.done{color:#5b6577}
.ch-pm-task.done svg{color:#16a34a}
.ch-pm-task.todo svg{color:#e2e8f2}

/* TOOLS GRID */
.ch-tools-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.ch-tool-card{background:#fff;border:1px solid #e2e8f2;border-radius:14px;padding:26px 24px;transition:.18s ease;display:flex;flex-direction:column;text-decoration:none}
.ch-tool-card:hover{border-color:#0861A9;box-shadow:0 14px 40px rgba(10,47,87,.16);transform:translateY(-4px);text-decoration:none}
.ch-tool-ic{width:50px;height:50px;border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:15px}
.ch-tool-ic svg{width:25px;height:25px}
.ch-t1{background:#e6f0f8;color:#0861A9}
.ch-t2{background:#f3e8ff;color:#7c3aed}
.ch-t3{background:#dcfce7;color:#16a34a}
.ch-t4{background:#fef3c7;color:#C8770E}
.ch-t5{background:#e0f2fe;color:#0891b2}
.ch-t6{background:#fee2e2;color:#dc2626}
.ch-tool-card h3{font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:700;color:#141926;margin-bottom:7px;display:flex;align-items:center;gap:7px}
.ch-tool-card p{font-size:.85rem;color:#5b6577;line-height:1.6;margin-bottom:14px;flex:1}
.ch-tool-link{font-size:.84rem;font-weight:700;color:#0861A9;display:inline-flex;align-items:center;gap:5px}
.ch-tool-badge{font-size:.6rem;font-weight:800;letter-spacing:.04em;text-transform:uppercase;color:#fff;background:#ED9020;padding:3px 8px;border-radius:10px}

/* CATEGORIES */
.ch-cat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.ch-cat-chip{display:flex;align-items:center;gap:11px;background:#fff;border:1px solid #e2e8f2;border-radius:12px;padding:15px 16px;text-decoration:none;transition:.18s ease}
.ch-cat-chip:hover{border-color:#0861A9;background:#E6F0F8;text-decoration:none;transform:translateY(-2px)}
.ch-cat-ic{width:38px;height:38px;border-radius:10px;background:#E6F0F8;color:#0861A9;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ch-cat-ic svg{width:19px;height:19px}
.ch-cat-tx h4{font-family:'Sora',sans-serif;font-size:.88rem;font-weight:700;color:#141926;margin:0}
.ch-cat-tx p{font-size:.73rem;color:#5b6577;margin:0}

/* ADVICE CARDS */
.ch-adv-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.ch-adv-card{background:#fff;border:1px solid #e2e8f2;border-radius:14px;overflow:hidden;text-decoration:none;transition:.18s ease;display:flex;flex-direction:column}
.ch-adv-card:hover{border-color:#0861A9;box-shadow:0 14px 40px rgba(10,47,87,.16);transform:translateY(-3px);text-decoration:none}
.ch-adv-thumb{height:120px;display:flex;align-items:center;justify-content:center}
.ch-adv-thumb svg{width:36px;height:36px;color:rgba(255,255,255,.3)}
.ch-adv-body{padding:18px;flex:1;display:flex;flex-direction:column}
.ch-adv-cat{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#0861A9;margin-bottom:6px}
.ch-adv-body h3{font-family:'Sora',sans-serif;font-size:.96rem;font-weight:700;line-height:1.3;color:#141926;margin-bottom:7px}
.ch-adv-meta{font-size:.73rem;color:#5b6577;margin-top:auto}

/* STEPS */
.ch-start-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.ch-start-card{text-align:center;padding:8px}
.ch-start-num{width:46px;height:46px;border-radius:50%;background:#0861A9;color:#fff;font-family:'Sora',sans-serif;font-weight:800;font-size:1.15rem;display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.ch-start-card h3{font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:8px;color:#141926}
.ch-start-card p{font-size:.87rem;color:#5b6577;line-height:1.6;margin:0}

/* TESTIMONIALS */
.ch-tm-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.ch-tm-card{background:#fff;border:1px solid #e2e8f2;border-radius:14px;padding:26px 24px;display:flex;flex-direction:column}
.ch-tm-stars{display:flex;gap:2px;margin-bottom:12px}
.ch-tm-stars svg{width:15px;height:15px;color:#ED9020}
.ch-tm-card p{font-size:.9rem;color:#141926;line-height:1.6;margin-bottom:18px;flex:1}
.ch-tm-who{display:flex;align-items:center;gap:11px}
.ch-tm-av{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;color:#fff;font-size:.85rem;flex-shrink:0}
.ch-tm-who strong{display:block;font-size:.86rem;color:#141926}
.ch-tm-who span{font-size:.76rem;color:#5b6577}

/* FAQ */
.ch-faq-wrap{max-width:760px;margin:0 auto}
.ch-faq-item{background:#fff;border:1px solid #e2e8f2;border-radius:12px;margin-bottom:12px;overflow:hidden;transition:.18s ease}
.ch-faq-item:hover{border-color:#cfe0f1}
.ch-faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px;background:none;border:none;cursor:pointer;font-family:'Sora',sans-serif;font-size:.95rem;font-weight:700;color:#141926;text-align:left;line-height:1.4}
.ch-faq-q svg{width:19px;height:19px;color:#0861A9;flex-shrink:0;transition:transform .2s}
.ch-faq-item.open .ch-faq-q svg{transform:rotate(45deg)}
.ch-faq-a{max-height:0;overflow:hidden;transition:max-height .26s ease}
.ch-faq-a-in{padding:0 22px 18px;font-size:.88rem;color:#5b6577;line-height:1.7}
.ch-faq-item.open .ch-faq-a{max-height:240px}

/* FINAL CTA */
.ch-final-cta{background:radial-gradient(ellipse 60% 80% at 50% 0%,rgba(237,144,32,.18),transparent 60%),linear-gradient(160deg,#0A2F57,#064A85);color:#fff;border-radius:20px;padding:54px 40px;text-align:center;position:relative;overflow:hidden}
.ch-final-cta h2{font-family:'Sora',sans-serif;font-size:clamp(1.6rem,3vw,2.3rem);font-weight:800;margin-bottom:12px}
.ch-final-cta p{font-size:1rem;color:rgba(255,255,255,.74);margin-bottom:26px;max-width:520px;margin-left:auto;margin-right:auto}
.ch-final-cta-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}

/* breadcrumb in hero */
.ch-pg-bc{position:relative;z-index:1;display:flex;gap:7px;align-items:center;justify-content:center;flex-wrap:wrap;font-family:'Inter',sans-serif;font-size:.76rem;color:rgba(255,255,255,.6);margin-bottom:18px}
.ch-pg-bc a{color:rgba(255,255,255,.6);text-decoration:none}
.ch-pg-bc a:hover{color:#fff}
.ch-pg-bc svg{width:12px;height:12px;opacity:.5}
.ch-pg-bc [aria-current]{color:rgba(255,255,255,.85);font-weight:600}

/* section-label used within ch- sections */
.ch-sec-label{display:inline-flex;align-items:center;gap:7px;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#0861A9;background:#E6F0F8;padding:5px 13px;border-radius:20px;margin:0 auto 14px}
.ch-sec-label svg{width:13px;height:13px}

/* btn-white (used in final CTA) */
.btn-white{background:#fff;color:#0861A9;border:1.5px solid #fff}
.btn-white:hover{background:#E6F0F8;text-decoration:none}

/* back to top */
#ch-btt{position:fixed;bottom:24px;right:24px;bottom:max(24px,calc(24px + env(safe-area-inset-bottom,0px)));width:46px;height:46px;border-radius:50%;background:#0861A9;color:#fff;border:none;cursor:pointer;box-shadow:0 14px 40px rgba(10,47,87,.16);display:none;align-items:center;justify-content:center;z-index:900;transition:.18s ease}
#ch-btt svg{width:20px;height:20px}
#ch-btt.show{display:flex}
#ch-btt:hover{background:#064A85}

/* sticky mobile cta */
.ch-sticky-cta{display:none}
@media(max-width:780px){
  .ch-sticky-cta{display:flex;position:fixed;left:0;right:0;bottom:0;z-index:950;gap:10px;padding:12px 16px calc(12px + env(safe-area-inset-bottom,0px));background:rgba(255,255,255,.96);backdrop-filter:blur(10px);border-top:1px solid #e2e8f2;box-shadow:0 -4px 20px rgba(10,47,87,.1)}
  .ch-sticky-cta .btn{flex:1;justify-content:center;min-height:46px}
  body{padding-bottom:72px}
}
@media(max-width:900px){
  .ch-pillar{grid-template-columns:1fr;gap:26px;margin-bottom:48px}
  .ch-pillar.rev .ch-pillar-media{order:0}
  .ch-tools-grid,.ch-adv-grid,.ch-start-grid{grid-template-columns:repeat(2,1fr)}
  .ch-cat-grid{grid-template-columns:repeat(2,1fr)}
  .ch-tm-grid{grid-template-columns:1fr}
  .ch-trust-inner{gap:18px}
}
@media(max-width:580px){
  .ch-sec{padding:46px 0}
  .ch-tools-grid,.ch-adv-grid,.ch-start-grid,.ch-cat-grid{grid-template-columns:1fr}
  .ch-search{flex-direction:column;padding:12px}
  .ch-search .field{padding:4px 8px}
  .ch-search .btn{width:100%;justify-content:center}
  .ch-statrow{gap:22px}
  .ch-final-cta{padding:34px 22px}
}
@media(max-width:580px){input,select,textarea{font-size:16px!important}}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- SVG sprite (icons) -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <defs>
    <symbol id="chi-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
    <symbol id="chi-bag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></symbol>
    <symbol id="chi-shield" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></symbol>
    <symbol id="chi-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></symbol>
    <symbol id="chi-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="chi-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="chi-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></symbol>
    <symbol id="chi-spark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4M12 17v4M5 12H1M23 12h-4M6.3 6.3 3.5 3.5M20.5 20.5l-2.8-2.8M17.7 6.3l2.8-2.8M3.5 20.5l2.8-2.8"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="chi-mic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3"/></symbol>
    <symbol id="chi-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M9 14a5 5 0 1 1 6 0c-.7.5-1 1.2-1 2H10c0-.8-.3-1.5-1-2Z"/></symbol>
    <symbol id="chi-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="chi-cap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 9 12 4 2 9l10 5 10-5Z"/><path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></symbol>
    <symbol id="chi-coins" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="9" cy="6" rx="6" ry="3"/><path d="M3 6v6c0 1.7 2.7 3 6 3s6-1.3 6-3V6"/><path d="M15 11c2.5.2 6 1.2 6 3 0 1.7-2.7 3-6 3-1 0-2-.1-3-.3"/><path d="M3 12c0 1.7 2.7 3 6 3"/></symbol>
    <symbol id="chi-chip" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 2v3M15 2v3M9 19v3M15 19v3M2 9h3M2 15h3M19 9h3M19 15h3"/></symbol>
    <symbol id="chi-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18Z"/></symbol>
    <symbol id="chi-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.5-1.5 3-3.3 3-5.5A4.5 4.5 0 0 0 12 5.5 4.5 4.5 0 0 0 2 8.5c0 2.2 1.5 4 3 5.5l7 7Z"/><path d="M3.2 12h4l1.5-3 2.5 5 1.5-2h4.5"/></symbol>
    <symbol id="chi-bank" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6M4 10v8M20 10v8M8 10v8M16 10v8M3 21h18"/></symbol>
    <symbol id="chi-mega" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1Z"/><path d="M15 8a4 4 0 0 1 0 8M18 5a8 8 0 0 1 0 14"/></symbol>
    <symbol id="chi-gear" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></symbol>
    <symbol id="chi-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="1"/><path d="M9 7h.01M15 7h.01M9 11h.01M15 11h.01M9 15h.01M15 15h.01M10 21v-3h4v3"/></symbol>
    <symbol id="chi-rocket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13c-1.5.5-3 2.5-3 5 2.5 0 4.5-1.5 5-3"/><path d="M13 7a8 8 0 0 1 7-4 8 8 0 0 1-4 7l-4 3-2-2Z"/><path d="m9 11-3 3 4 4 3-3M15 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></symbol>
    <symbol id="chi-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
    <symbol id="chi-sliders" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/></symbol>
    <symbol id="chi-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></symbol>
    <symbol id="chi-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></symbol>
  </defs>
</svg>

<main id="main">

<!-- HERO with search -->
<section class="ch-hero">
  <span class="gridbg" aria-hidden="true"></span>
  <div class="container">
    <div class="ch-hero-inner">
      <nav class="ch-pg-bc" aria-label="Breadcrumb">
        <a href="<?= base_url('/') ?>">Home</a>
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        <span aria-current="page">Candidate hub</span>
      </nav>
      <div class="ch-eyebrow"><svg aria-hidden="true"><use href="#chi-rocket"/></svg>Your candidate hub</div>
      <h1>Your career, <span>all in one place</span></h1>
      <p class="lede">Search jobs, build a standout CV, practise interviews, track your applications and learn from expert advice — everything you need to land your next role, free.</p>
      <form class="ch-search" role="search" onsubmit="return chGoSearch(event)">
        <div class="field"><svg aria-hidden="true"><use href="#chi-search"/></svg><input type="search" id="ch-q" placeholder="Job title, skill or company" aria-label="Search jobs"></div>
        <button type="submit" class="btn btn-accent">Search jobs</button>
      </form>
      <div class="ch-pop">Popular: <a href="<?= base_url('jobs?q=software+developer') ?>">Software Developer</a> <a href="<?= base_url('jobs?q=accountant') ?>">Accountant</a> <a href="<?= base_url('jobs?q=remote') ?>">Remote</a> <a href="<?= base_url('jobs?q=sales') ?>">Sales</a></div>
      <div class="ch-statrow">
        <div class="ch-stat"><div class="ch-stat-n"><?= number_format($liveJobsCount ?? 0) ?><span>+</span></div><div class="ch-stat-l">Live jobs</div></div>
        <div class="ch-stat"><div class="ch-stat-n"><?= number_format($employerCount ?? 0) ?><span>+</span></div><div class="ch-stat-l">Hiring companies</div></div>
        <div class="ch-stat"><div class="ch-stat-n">6</div><div class="ch-stat-l">Free career tools</div></div>
      </div>
    </div>
  </div>
</section>

<!-- TRUST STRIP -->
<section class="ch-trust" aria-label="Why job seekers trust us">
  <div class="container">
    <div class="ch-trust-inner">
      <div class="ch-trust-item"><svg aria-hidden="true"><use href="#chi-shield"/></svg>Verified employers only</div>
      <div class="ch-trust-item"><svg aria-hidden="true"><use href="#chi-lock"/></svg>We never charge job seekers</div>
      <div class="ch-trust-item"><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>100% free to apply</div>
      <div class="ch-trust-item brand"><svg aria-hidden="true"><use href="#chi-bell"/></svg>Report a suspicious job anytime</div>
    </div>
  </div>
</section>

<!-- FOUR PILLARS -->
<section class="ch-sec ch-white">
  <div class="container">

    <!-- Pillar 1: Job search -->
    <div class="ch-pillar">
      <div>
        <div class="ch-pillar-label"><svg aria-hidden="true"><use href="#chi-search"/></svg>Find jobs</div>
        <h2>Land a job that actually fits</h2>
        <p>Skip the endless scrolling. Search verified roles, filter to exactly what you want, and let matched recommendations bring the right jobs to you.</p>
        <ul class="ch-pillar-list">
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Smart search &amp; filters by role, location and pay</li>
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Personalised job recommendations</li>
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Save jobs and set up instant job alerts</li>
        </ul>
        <div class="ch-pillar-actions">
          <a href="<?= base_url('jobs') ?>" class="btn btn-primary">Browse jobs</a>
          <a href="<?= base_url('register?type=candidate') ?>" class="btn btn-outline">Get job alerts</a>
        </div>
      </div>
      <div class="ch-pillar-media">
        <div class="ch-mock">
          <div class="ch-mock-top"><span class="ch-mock-dot" style="background:#ef4444"></span><span class="ch-mock-dot" style="background:#f59e0b"></span><span class="ch-mock-dot" style="background:#22c55e"></span></div>
          <div class="ch-mock-body">
            <div class="ch-jobrow"><span class="ch-joblogo" style="background:#0861A9">PB</span><span class="ch-jobinfo"><h4>Frontend Developer</h4><p>Paybridge · Lagos · Hybrid</p></span><span class="ch-jobmeta">New</span></div>
            <div class="ch-jobrow"><span class="ch-joblogo" style="background:#16a34a">GT</span><span class="ch-jobinfo"><h4>Data Analyst</h4><p>GreenTrust · Remote</p></span><span class="ch-jobmeta">Featured</span></div>
            <div class="ch-jobrow"><span class="ch-joblogo" style="background:#C8770E">KM</span><span class="ch-jobinfo"><h4>Marketing Manager</h4><p>KamiMedia · Abuja</p></span><span class="ch-jobmeta">Urgent</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pillar 2: Career tools -->
    <div class="ch-pillar rev">
      <div class="ch-pillar-media">
        <div class="ch-mock">
          <div class="ch-mock-top"><span class="ch-mock-dot" style="background:#ef4444"></span><span class="ch-mock-dot" style="background:#f59e0b"></span><span class="ch-mock-dot" style="background:#22c55e"></span></div>
          <div class="ch-mock-body">
            <div class="ch-pm-ring">
              <div class="ch-pm-circ"><span>75%</span></div>
              <div class="ch-pm-tx"><h4>CV strength</h4><p>Almost there — add 2 more sections</p></div>
            </div>
            <div class="ch-pm-task done"><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Contact details added</div>
            <div class="ch-pm-task done"><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Work experience added</div>
            <div class="ch-pm-task todo"><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Add a professional summary</div>
            <div class="ch-pm-task todo"><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Add key skills</div>
          </div>
        </div>
      </div>
      <div>
        <div class="ch-pillar-label"><svg aria-hidden="true"><use href="#chi-spark"/></svg>Career tools</div>
        <h2>Get past the filter and into the interview</h2>
        <p>Most CVs are rejected by software before a human sees them. Build an ATS-ready CV, rehearse real interview questions, and walk in knowing your worth.</p>
        <ul class="ch-pillar-list">
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>AI Resume Builder with instant scoring</li>
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>AI Mock Interview with feedback</li>
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Salary calculator for Nigerian roles</li>
        </ul>
        <div class="ch-pillar-actions">
          <a href="#ch-tools" class="btn btn-primary">Explore all tools</a>
        </div>
      </div>
    </div>

    <!-- Pillar 3: Application tracking -->
    <div class="ch-pillar">
      <div>
        <div class="ch-pillar-label"><svg aria-hidden="true"><use href="#chi-sliders"/></svg>Track progress</div>
        <h2>Always know your next move</h2>
        <p>No more wondering &ldquo;did they see it?&rdquo; See exactly where every application stands and what to do next &mdash; all in one place.</p>
        <ul class="ch-pillar-list">
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Live status on every application</li>
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Saved jobs and reminders</li>
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Profile completion guidance</li>
        </ul>
        <div class="ch-pillar-actions">
          <a href="<?= base_url('register?type=candidate') ?>" class="btn btn-primary">Create free account</a>
        </div>
      </div>
      <div class="ch-pillar-media">
        <div class="ch-mock">
          <div class="ch-mock-top"><span class="ch-mock-dot" style="background:#ef4444"></span><span class="ch-mock-dot" style="background:#f59e0b"></span><span class="ch-mock-dot" style="background:#22c55e"></span></div>
          <div class="ch-mock-body">
            <div class="ch-trk-step"><span class="ch-trk-ic ch-trk-done"><svg aria-hidden="true"><use href="#chi-check"/></svg></span><span class="ch-trk-tx"><h4>Frontend Developer · Paybridge</h4><p>Applied 2 days ago</p></span><span class="ch-trk-badge ch-tb-active">In review</span></div>
            <div class="ch-trk-step"><span class="ch-trk-ic ch-trk-done"><svg aria-hidden="true"><use href="#chi-check"/></svg></span><span class="ch-trk-tx"><h4>Data Analyst · GreenTrust</h4><p>Applied 5 days ago</p></span><span class="ch-trk-badge ch-tb-done">Shortlisted</span></div>
            <div class="ch-trk-step"><span class="ch-trk-ic ch-trk-active"><svg aria-hidden="true"><use href="#chi-bell"/></svg></span><span class="ch-trk-tx"><h4>UX Designer · Kanto</h4><p>Interview Thu, 10am</p></span><span class="ch-trk-badge ch-tb-active">Interview</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pillar 4: Training & advice -->
    <div class="ch-pillar rev">
      <div class="ch-pillar-media">
        <div class="ch-mock">
          <div class="ch-mock-top"><span class="ch-mock-dot" style="background:#ef4444"></span><span class="ch-mock-dot" style="background:#f59e0b"></span><span class="ch-mock-dot" style="background:#22c55e"></span></div>
          <div class="ch-mock-body">
            <div class="ch-jobrow"><span class="ch-joblogo" style="background:#7c3aed"><svg aria-hidden="true" style="width:20px;height:20px;color:#fff"><use href="#chi-mic"/></svg></span><span class="ch-jobinfo"><h4>Acing Your Interview</h4><p>Live webinar · Sat 11am</p></span><span class="ch-jobmeta">Free</span></div>
            <div class="ch-jobrow"><span class="ch-joblogo" style="background:#0891b2"><svg aria-hidden="true" style="width:20px;height:20px;color:#fff"><use href="#chi-doc"/></svg></span><span class="ch-jobinfo"><h4>CV Writing Masterclass</h4><p>On-demand course</p></span><span class="ch-jobmeta">New</span></div>
            <div class="ch-jobrow"><span class="ch-joblogo" style="background:#16a34a"><svg aria-hidden="true" style="width:20px;height:20px;color:#fff"><use href="#chi-cap"/></svg></span><span class="ch-jobinfo"><h4>Breaking Into Tech</h4><p>Guide · 16 min read</p></span><span class="ch-jobmeta">Popular</span></div>
          </div>
        </div>
      </div>
      <div>
        <div class="ch-pillar-label"><svg aria-hidden="true"><use href="#chi-cap"/></svg>Learn &amp; grow</div>
        <h2>Learn what hiring managers want</h2>
        <p>Get the inside track with free webinars, courses and guides from the people who actually do the hiring in Nigeria.</p>
        <ul class="ch-pillar-list">
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Live and on-demand career webinars</li>
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Courses to build in-demand skills</li>
          <li><svg aria-hidden="true"><use href="#chi-check-circle"/></svg>Expert guides on the JobberRecruit blog</li>
        </ul>
        <div class="ch-pillar-actions">
          <a href="<?= base_url('webinars') ?>" class="btn btn-primary">Browse webinars</a>
          <a href="<?= base_url('blog') ?>" class="btn btn-outline">Read the blog</a>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- TOOLS GRID -->
<section class="ch-sec ch-tint" id="ch-tools" aria-labelledby="ch-tools-h">
  <div class="container">
    <div class="ch-sec-head">
      <div class="ch-sec-label"><svg aria-hidden="true"><use href="#chi-spark"/></svg>Free career tools</div>
      <h2 id="ch-tools-h">Everything you need to <span>stand out</span></h2>
      <p>Six free tools designed to help you get noticed, prepare, and land the offer.</p>
    </div>
    <div class="ch-tools-grid">
      <a class="ch-tool-card" href="<?= base_url('candidate/resumes/build') ?>"><div class="ch-tool-ic ch-t1"><svg aria-hidden="true"><use href="#chi-doc"/></svg></div><h3>AI Resume Builder</h3><p>Create an ATS-friendly CV in minutes with smart suggestions and instant scoring.</p><span class="ch-tool-link">Build my CV <svg aria-hidden="true" style="width:14px;height:14px"><use href="#chi-rocket"/></svg></span></a>
      <a class="ch-tool-card" href="<?= base_url('candidate/career-tools/mock-interview') ?>"><div class="ch-tool-ic ch-t2"><svg aria-hidden="true"><use href="#chi-mic"/></svg></div><h3>AI Mock Interview <span class="ch-tool-badge">New</span></h3><p>Practise real interview questions and get instant, actionable AI feedback.</p><span class="ch-tool-link">Start practising <svg aria-hidden="true" style="width:14px;height:14px"><use href="#chi-rocket"/></svg></span></a>
      <a class="ch-tool-card" href="<?= base_url('cv-review') ?>"><div class="ch-tool-ic ch-t3"><svg aria-hidden="true"><use href="#chi-edit"/></svg></div><h3>CV Revamp</h3><p>Get your existing CV professionally rewritten and optimised by experts.</p><span class="ch-tool-link">Revamp my CV <svg aria-hidden="true" style="width:14px;height:14px"><use href="#chi-rocket"/></svg></span></a>
      <a class="ch-tool-card" href="<?= base_url('candidate/career-tools/salary-negotiation') ?>"><div class="ch-tool-ic ch-t4"><svg aria-hidden="true"><use href="#chi-coins"/></svg></div><h3>Salary Calculator</h3><p>Know what your role really pays in Nigeria before you negotiate.</p><span class="ch-tool-link">Check salary <svg aria-hidden="true" style="width:14px;height:14px"><use href="#chi-rocket"/></svg></span></a>
      <a class="ch-tool-card" href="<?= base_url('webinars') ?>"><div class="ch-tool-ic ch-t5"><svg aria-hidden="true"><use href="#chi-mic"/></svg></div><h3>Career Webinars</h3><p>Join free live sessions with recruiters and industry experts.</p><span class="ch-tool-link">See webinars <svg aria-hidden="true" style="width:14px;height:14px"><use href="#chi-rocket"/></svg></span></a>
      <a class="ch-tool-card" href="<?= base_url('candidate/career-tools/career-advice') ?>"><div class="ch-tool-ic ch-t6"><svg aria-hidden="true"><use href="#chi-bulb"/></svg></div><h3>Career Path Quiz</h3><p>Not sure what&rsquo;s next? Discover roles that fit your strengths.</p><span class="ch-tool-link">Take the quiz <svg aria-hidden="true" style="width:14px;height:14px"><use href="#chi-rocket"/></svg></span></a>
    </div>
  </div>
</section>

<!-- BROWSE BY CATEGORY -->
<section class="ch-sec ch-white" aria-labelledby="ch-cat-h">
  <div class="container">
    <div class="ch-sec-head">
      <div class="ch-sec-label"><svg aria-hidden="true"><use href="#chi-building"/></svg>Browse by field</div>
      <h2 id="ch-cat-h">Find jobs in <span>your industry</span></h2>
    </div>
    <div class="ch-cat-grid">
      <a class="ch-cat-chip" href="<?= base_url('jobs?category=it') ?>"><span class="ch-cat-ic"><svg aria-hidden="true"><use href="#chi-chip"/></svg></span><span class="ch-cat-tx"><h4>IT &amp; Software</h4><p>Browse open roles</p></span></a>
      <a class="ch-cat-chip" href="<?= base_url('jobs?category=banking') ?>"><span class="ch-cat-ic"><svg aria-hidden="true"><use href="#chi-bank"/></svg></span><span class="ch-cat-tx"><h4>Banking &amp; Finance</h4><p>Browse open roles</p></span></a>
      <a class="ch-cat-chip" href="<?= base_url('jobs?category=healthcare') ?>"><span class="ch-cat-ic"><svg aria-hidden="true"><use href="#chi-heart"/></svg></span><span class="ch-cat-tx"><h4>Healthcare</h4><p>Browse open roles</p></span></a>
      <a class="ch-cat-chip" href="<?= base_url('jobs?category=marketing') ?>"><span class="ch-cat-ic"><svg aria-hidden="true"><use href="#chi-mega"/></svg></span><span class="ch-cat-tx"><h4>Marketing &amp; Sales</h4><p>Browse open roles</p></span></a>
      <a class="ch-cat-chip" href="<?= base_url('jobs?category=engineering') ?>"><span class="ch-cat-ic"><svg aria-hidden="true"><use href="#chi-gear"/></svg></span><span class="ch-cat-tx"><h4>Engineering</h4><p>Browse open roles</p></span></a>
      <a class="ch-cat-chip" href="<?= base_url('jobs?category=education') ?>"><span class="ch-cat-ic"><svg aria-hidden="true"><use href="#chi-cap"/></svg></span><span class="ch-cat-tx"><h4>Education</h4><p>Browse open roles</p></span></a>
      <a class="ch-cat-chip" href="<?= base_url('jobs?category=operations') ?>"><span class="ch-cat-ic"><svg aria-hidden="true"><use href="#chi-bag"/></svg></span><span class="ch-cat-tx"><h4>Operations &amp; Admin</h4><p>Browse open roles</p></span></a>
      <a class="ch-cat-chip" href="<?= base_url('jobs?category=remote') ?>"><span class="ch-cat-ic"><svg aria-hidden="true"><use href="#chi-globe"/></svg></span><span class="ch-cat-tx"><h4>Remote Jobs</h4><p>Browse open roles</p></span></a>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="ch-sec ch-white" aria-labelledby="ch-tm-h">
  <div class="container">
    <div class="ch-sec-head">
      <div class="ch-sec-label"><svg aria-hidden="true"><use href="#chi-star"/></svg>Real candidates, real results</div>
      <h2 id="ch-tm-h">Job seekers who <span>got hired</span></h2>
      <p>Thousands of Nigerians have found their next role through JobberRecruit.</p>
    </div>
    <div class="ch-tm-grid">
      <div class="ch-tm-card">
        <div class="ch-tm-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg></div>
        <p>&ldquo;The AI resume builder helped me fix my CV, and I started getting interview calls within a week. Landed a frontend role in under a month.&rdquo;</p>
        <div class="ch-tm-who"><span class="ch-tm-av" style="background:#0861A9">CN</span><div><strong>Chioma N.</strong><span>Frontend Developer, Lagos</span></div></div>
      </div>
      <div class="ch-tm-card">
        <div class="ch-tm-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg></div>
        <p>&ldquo;I practised with the mock interview tool before my final round. Walked in confident and got the offer. The salary calculator helped me negotiate too.&rdquo;</p>
        <div class="ch-tm-who"><span class="ch-tm-av" style="background:#16a34a">EO</span><div><strong>Emeka O.</strong><span>Data Analyst, Remote</span></div></div>
      </div>
      <div class="ch-tm-card">
        <div class="ch-tm-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg><svg aria-hidden="true"><use href="#chi-star"/></svg></div>
        <p>&ldquo;Being able to track all my applications in one place kept me sane during my job search. No more guessing where I stood with each company.&rdquo;</p>
        <div class="ch-tm-who"><span class="ch-tm-av" style="background:#C8770E">AB</span><div><strong>Aisha B.</strong><span>Marketing Manager, Abuja</span></div></div>
      </div>
    </div>
  </div>
</section>

<!-- HOW TO START -->
<section class="ch-sec ch-tint" aria-labelledby="ch-start-h">
  <div class="container">
    <div class="ch-sec-head">
      <div class="ch-sec-label"><svg aria-hidden="true"><use href="#chi-rocket"/></svg>Get started</div>
      <h2 id="ch-start-h">Start in <span>three simple steps</span></h2>
    </div>
    <div class="ch-start-grid">
      <div class="ch-start-card"><div class="ch-start-num">1</div><h3>Create your free account</h3><p>Sign up in under two minutes and build your candidate profile.</p></div>
      <div class="ch-start-card"><div class="ch-start-num">2</div><h3>Build your CV &amp; apply</h3><p>Use the AI tools to polish your CV, then apply to matched jobs.</p></div>
      <div class="ch-start-card"><div class="ch-start-num">3</div><h3>Track &amp; land the offer</h3><p>Follow your applications, prep with webinars, and get hired.</p></div>
    </div>
  </div>
</section>

<!-- ADVICE / blog -->
<section class="ch-sec ch-white" aria-labelledby="ch-adv-h">
  <div class="container">
    <div class="ch-sec-head">
      <div class="ch-sec-label"><svg aria-hidden="true"><use href="#chi-doc"/></svg>Career advice</div>
      <h2 id="ch-adv-h">Most-read <span>career guides</span></h2>
      <p>Practical, Nigeria-specific advice to help you at every stage.</p>
    </div>
    <div class="ch-adv-grid">
      <?php if (!empty($recentBlogs)): ?>
        <?php foreach ($recentBlogs as $blog): ?>
          <a class="ch-adv-card" href="<?= base_url('blog/' . $blog->slug) ?>">
            <?php if (!empty($blog->featured_image)): ?>
              <div class="ch-adv-thumb" style="background:linear-gradient(135deg,#0A2F57,#0861A9);padding:0">
                <img src="<?= base_url($blog->featured_image) ?>" alt="<?= esc($blog->title) ?>" style="width:100%;height:100%;object-fit:cover;display:block">
              </div>
            <?php else: ?>
              <div class="ch-adv-thumb" style="background:linear-gradient(135deg,#0A2F57,#0891b2)"><svg aria-hidden="true"><use href="#chi-doc"/></svg></div>
            <?php endif; ?>
            <div class="ch-adv-body">
              <div class="ch-adv-cat"><?= esc($blog->category ?? 'Career Advice') ?></div>
              <h3><?= esc($blog->title) ?></h3>
              <div class="ch-adv-meta"><?= date('M d, Y', strtotime($blog->created_at)) ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color:#5b6577;grid-column:1/-1;text-align:center">No recent articles found. <a href="<?= base_url('blog') ?>">Browse all blog posts</a>.</p>
      <?php endif; ?>
    </div>
    <div style="text-align:center;margin-top:32px"><a href="<?= base_url('blog') ?>" class="btn btn-outline">Read more on the blog</a></div>
  </div>
</section>

<!-- FAQ -->
<section class="ch-sec ch-tint" aria-labelledby="ch-faq-h">
  <div class="container">
    <div class="ch-sec-head">
      <div class="ch-sec-label"><svg aria-hidden="true"><use href="#chi-bulb"/></svg>Questions &amp; answers</div>
      <h2 id="ch-faq-h">Job seeker <span>FAQ</span></h2>
    </div>
    <div class="ch-faq-wrap">
      <div class="ch-faq-item"><button class="ch-faq-q" aria-expanded="false" onclick="chToggleFaq(this)">Is JobberRecruit free for job seekers? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="ch-faq-a"><div class="ch-faq-a-in">Yes. Creating a candidate account, searching and applying for jobs, building your CV, practising interviews and tracking applications are all free for job seekers.</div></div></div>
      <div class="ch-faq-item"><button class="ch-faq-q" aria-expanded="false" onclick="chToggleFaq(this)">Do I need an account to apply for jobs? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="ch-faq-a"><div class="ch-faq-a-in">You can browse jobs without an account, but you&rsquo;ll need a free candidate account to apply, save jobs, track applications and set up job alerts.</div></div></div>
      <div class="ch-faq-item"><button class="ch-faq-q" aria-expanded="false" onclick="chToggleFaq(this)">What career tools are available? <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="ch-faq-a"><div class="ch-faq-a-in">An AI resume builder, AI mock interview practice, professional CV revamp, a salary calculator, career webinars and a career path quiz &mdash; all designed to help you land your next role.</div></div></div>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="ch-sec ch-white" style="padding-top:0">
  <div class="container">
    <div class="ch-final-cta">
      <h2>Ready to land your next role?</h2>
      <p>Create your free candidate account and get the jobs, tools and guidance to move your career forward.</p>
      <div class="ch-final-cta-actions">
        <a href="<?= base_url('register?type=candidate') ?>" class="btn btn-accent btn-lg"><svg aria-hidden="true"><use href="#chi-rocket"/></svg>Create free account</a>
        <a href="<?= base_url('jobs') ?>" class="btn btn-white btn-lg">Browse jobs</a>
      </div>
    </div>
  </div>
</section>

</main>

<!-- Back to top -->
<button id="ch-btt" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
</button>

<!-- Mobile sticky CTA -->
<div class="ch-sticky-cta" aria-label="Quick actions">
  <a href="<?= base_url('register?type=candidate') ?>" class="btn btn-accent"><svg aria-hidden="true" style="width:16px;height:16px"><use href="#chi-rocket"/></svg>Create free account</a>
  <a href="<?= base_url('jobs') ?>" class="btn btn-outline">Browse jobs</a>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function chGoSearch(e){
  e.preventDefault();
  var q = document.getElementById('ch-q').value.trim();
  window.location.href = '<?= base_url('jobs') ?>' + (q ? ('?q=' + encodeURIComponent(q)) : '');
  return false;
}
function chToggleFaq(btn){
  var item = btn.parentElement;
  var open = item.classList.toggle('open');
  btn.setAttribute('aria-expanded', String(open));
}
// Back to top visibility
window.addEventListener('scroll', function(){
  var btt = document.getElementById('ch-btt');
  if(btt) btt.classList.toggle('show', window.scrollY > 400);
});
</script>
<?= $this->endSection() ?>
