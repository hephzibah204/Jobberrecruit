<?= $this->extend('templates/base') ?>

<?= $this->section('meta') ?>
<meta name="description" content="Join free and premium live webinars on interview prep, CV writing, salary negotiation, and tech skills — hosted by Nigeria's top career experts.">
<meta name="keywords" content="career webinars Nigeria, free job interview webinar, CV writing webinar, salary negotiation training, tech career webinar Lagos">
<meta name="author" content="JobberRecruit">

<meta property="og:type" content="website">
<meta property="og:site_name" content="JobberRecruit">
<meta property="og:title" content="Free Career Webinars in Nigeria | Live Expert-Led Training — JobberRecruit">
<meta property="og:description" content="Join free and premium live webinars on interview prep, CV writing, salary negotiation and tech skills — hosted by Nigeria's top career experts.">
<meta property="og:image" content="<?= base_url('assets/og/webinars.png') ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="en_NG">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@jobberrecruit">
<meta name="twitter:title" content="Free Career Webinars in Nigeria — JobberRecruit">
<meta name="twitter:description" content="Live expert-led training on interviews, CVs, salary negotiation and tech skills. Mostly free. Register today.">
<meta name="twitter:image" content="<?= base_url('assets/og/webinars.png') ?>">

<link rel="canonical" href="<?= current_url() ?>">
<?= $this->endSection() ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[
{"@type":"ListItem","position":1,"name":"Home","item":"<?= base_url() ?>"},
{"@type":"ListItem","position":2,"name":"Training","item":"<?= base_url('training') ?>"},
{"@type":"ListItem","position":3,"name":"Webinars","item":"<?= current_url() ?>"}]}
</script>

<?php if (!empty($webinars)): ?>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"ItemList","itemListElement":[
<?php foreach ($webinars as $i => $w): ?>
{"@type":"ListItem","position":<?= $i + 1 ?>,"item":{"@type":"Event","name":"<?= esc($w->title) ?>","description":"<?= esc($w->description) ?>","startDate":"<?= date('c', strtotime($w->scheduled_at)) ?>","eventAttendanceMode":"https://schema.org/OnlineEventAttendanceMode","eventStatus":"https://schema.org/EventScheduled","location":{"@type":"VirtualLocation","url":"<?= base_url('training/webinars/' . $w->id) ?>"},"performer":{"@type":"Person","name":"<?= esc($w->speaker_name) ?>"},"organizer":{"@type":"Organization","name":"JobberRecruit","url":"<?= base_url() ?>"},"offers":{"@type":"Offer","price":"0","priceCurrency":"NGN","availability":"https://schema.org/InStock","url":"<?= base_url('training/webinars/' . $w->id) ?>"}}}
<?php if ($i < count($webinars) - 1): ?>,<?php endif; ?>
<?php endforeach; ?>
]}</script>
<?php endif; ?>

<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[
{"@type":"Question","name":"Are JobberRecruit webinars really free?","acceptedAnswer":{"@type":"Answer","text":"Most of our webinars are completely free to attend. A small number of in-depth premium workshops carry a low fee, always clearly marked on the webinar card before you register."}},
{"@type":"Question","name":"Will I get a recording if I miss the live session?","acceptedAnswer":{"@type":"Answer","text":"Yes. Everyone who registers receives a link to the recording by email after the session, so you can catch up even if you cannot attend live."}},
{"@type":"Question","name":"Do I get a certificate for attending?","acceptedAnswer":{"@type":"Answer","text":"Verified JobberRecruit certificates of attendance are issued for premium (paid) webinars only. After attending a premium session you receive a certificate you can add to your LinkedIn profile and CV. Free webinars do not include a certificate."}},
{"@type":"Question","name":"Which platforms are the webinars hosted on?","acceptedAnswer":{"@type":"Answer","text":"Webinars are hosted on Zoom, Google Meet, or Microsoft Teams. The platform for each session is shown on its card, and the join link is emailed to you after you register."}},
{"@type":"Question","name":"How do I register for a webinar?","acceptedAnswer":{"@type":"Answer","text":"Click the Register button on any webinar card. If you are logged in, registration is one tap. You will receive a confirmation email with the join link and a calendar invite."}},
{"@type":"Question","name":"Can I ask questions during the webinar?","acceptedAnswer":{"@type":"Answer","text":"Absolutely. Every webinar includes a live Q&A segment where you can ask the speaker your questions directly."}}
]}
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
:root{--w-brand:#0D609E;--w-brand-dark:#0A4D7E;--w-brand-deep:#07304F;--w-brand-light:#E6F0F9;--w-accent:#F08F1A;--w-accent-dark:#D07D10;--w-text:#141926;--w-muted:#5b6577;--w-bg:#f5f7fb;--w-white:#fff;--w-border:#e2e8f2;--w-success:#16a34a;--w-radius:10px;--w-shadow:0 2px 14px rgba(10,47,87,.08);--w-shadow-lg:0 14px 40px rgba(10,47,87,.16);--w-transition:.18s ease}
.w-hero{background:radial-gradient(ellipse 70% 60% at 82% 20%,rgba(240,143,26,.18) 0%,transparent 55%),radial-gradient(ellipse 80% 70% at 10% 90%,rgba(13,96,158,.32) 0%,transparent 55%),linear-gradient(160deg,#07304F 0%,#0A4D7E 55%,#0D609E 100%);color:#fff;padding:60px 0 0;position:relative;overflow:hidden}
.w-hero-grid{position:absolute;inset:0;pointer-events:none;opacity:.45;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:46px 46px;-webkit-mask-image:radial-gradient(ellipse 90% 80% at 50% 30%,#000 30%,transparent 80%);mask-image:radial-gradient(ellipse 90% 80% at 50% 30%,#000 30%,transparent 80%)}
.w-hero-inner{position:relative;z-index:1;display:grid;grid-template-columns:1fr 400px;gap:52px;align-items:center;padding-bottom:0}
.w-hero-bc{display:flex;gap:7px;align-items:center;flex-wrap:wrap;font-size:.76rem;color:rgba(255,255,255,.6);margin-bottom:18px}
.w-hero-bc a{color:rgba(255,255,255,.6);text-decoration:none}
.w-hero-bc a:hover{color:#fff}
.w-hero-bc [aria-current]{color:rgba(255,255,255,.85);font-weight:600}
.w-hero-tag{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:20px;padding:5px 14px;color:rgba(255,255,255,.92);margin-bottom:18px}
.w-live-dot{width:7px;height:7px;border-radius:50%;background:var(--w-accent);animation:w-pulse 1.6s ease-in-out infinite}
@keyframes w-pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.3;transform:scale(.7)}}
.w-hero h1{font-size:clamp(2rem,4vw,3rem);font-weight:800;line-height:1.1;margin-bottom:16px;color:#fff}
.w-hero h1 em{font-style:normal;color:var(--w-accent)}
.w-hero-sub{font-size:.95rem;color:rgba(255,255,255,.68);max-width:460px;line-height:1.72;margin-bottom:30px}
.w-chips{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:32px}
.w-chip{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;border-radius:8px;font-size:.78rem;font-weight:600;background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.16);color:rgba(255,255,255,.88);transition:var(--w-transition);text-decoration:none}
.w-chip:hover{background:rgba(255,255,255,.18);text-decoration:none;color:#fff}
.w-chip i{font-size:14px;color:var(--w-accent)}
.w-hero-stats{display:flex;gap:32px;padding:24px 0 56px;border-top:1px solid rgba(255,255,255,.12)}
.w-stat-val{font-family:'Sora',sans-serif;font-size:1.8rem;font-weight:800;line-height:1;color:#fff}
.w-stat-val span{color:var(--w-accent)}
.w-stat-lbl{font-size:.7rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:rgba(255,255,255,.45);margin-top:3px}
.w-feat-card{background:var(--w-white);border-radius:16px;box-shadow:0 28px 70px rgba(0,0,0,.32);overflow:hidden;transform:translateY(32px)}
.w-fc-thumb{height:120px;display:flex;align-items:center;justify-content:center;position:relative;background:linear-gradient(135deg,#07304F,#0D609E)}
.w-fc-thumb i{font-size:48px;color:rgba(255,255,255,.25)}
.w-fc-avatar{position:absolute;bottom:-20px;left:18px;width:44px;height:44px;border-radius:50%;border:3px solid #fff;background:var(--w-brand);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:.82rem;font-weight:800;color:#fff}
.w-fc-badges{position:absolute;top:10px;right:10px;display:flex;gap:6px}
.w-fc-badge{font-size:.62rem;font-weight:800;padding:3px 8px;border-radius:20px;letter-spacing:.04em}
.w-fc-badge-next{background:var(--w-accent);color:var(--w-brand-deep)}
.w-fc-badge-cat{background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.3)}
.w-fc-body{padding:28px 18px 16px}
.w-fc-spk-name{font-weight:700;font-size:.82rem;color:var(--w-text)}
.w-fc-spk-role{font-size:.72rem;color:var(--w-muted);margin-bottom:10px}
.w-fc-title{font-family:'Sora',sans-serif;font-size:.98rem;font-weight:800;color:var(--w-text);line-height:1.32;margin-bottom:11px}
.w-fc-meta{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px}
.w-fc-mi{display:flex;align-items:center;gap:4px;font-size:.74rem;color:var(--w-muted)}
.w-fc-mi i{font-size:12px}
.w-prov-z{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:12px;font-size:.7rem;font-weight:600;background:var(--w-brand-light);color:var(--w-brand)}
.w-countdown{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin-bottom:14px}
.w-cd-box{background:var(--w-brand-deep);border-radius:8px;padding:8px 4px;text-align:center}
.w-cd-n{font-family:'Sora',sans-serif;font-size:1.3rem;font-weight:800;color:#fff;line-height:1}
.w-cd-l{font-size:.58rem;color:rgba(255,255,255,.4);font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-top:3px}
.w-fc-cta{display:block;width:100%;padding:11px;background:var(--w-accent);color:var(--w-brand-deep);border:none;border-radius:8px;font-size:.88rem;font-weight:700;font-family:'Sora',sans-serif;cursor:pointer;transition:var(--w-transition);text-decoration:none;text-align:center}
.w-fc-cta:hover{background:var(--w-accent-dark);text-decoration:none;color:var(--w-brand-deep)}
.w-fc-note{display:flex;align-items:center;justify-content:center;gap:5px;margin-top:8px;font-size:.72rem;color:var(--w-muted)}
.w-fc-note i{font-size:13px;color:var(--w-success)}
.w-stats-band{background:var(--w-brand-deep);padding:20px 0}
.w-stats-inner{display:flex;align-items:center;justify-content:center;flex-wrap:wrap}
.w-sb-item{display:flex;align-items:center;gap:10px;padding:8px 36px;border-right:1px solid rgba(255,255,255,.1)}
.w-sb-item:last-child{border-right:none}
.w-sb-icon{width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--w-accent);flex-shrink:0}
.w-sb-icon i{font-size:18px}
.w-sb-val{font-family:'Sora',sans-serif;font-size:1.15rem;font-weight:800;color:#fff;line-height:1}
.w-sb-lbl{font-size:.72rem;color:rgba(255,255,255,.5);font-weight:500;margin-top:2px}
.w-filter-wrap{background:var(--w-white);border-bottom:1px solid var(--w-border);padding:28px 0}
.w-filter-top{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px}
.w-filter-top h2{font-size:1.25rem;font-weight:800;color:var(--w-text)}
.w-filter-top .w-count{font-size:.82rem;color:var(--w-muted)}
.w-pill-row{display:flex;gap:7px;flex-wrap:wrap}
.w-pill{padding:7px 16px;border-radius:22px;border:1.5px solid var(--w-border);background:var(--w-white);font-size:.78rem;font-weight:600;color:var(--w-muted);cursor:pointer;transition:var(--w-transition);font-family:'Inter',sans-serif;min-height:36px}
.w-pill:hover{border-color:var(--w-brand);color:var(--w-brand)}
.w-pill.on{background:var(--w-brand);color:var(--w-white);border-color:var(--w-brand)}
.w-grid-section{padding:32px 0 64px}
.w-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.wb-card{background:var(--w-white);border:1px solid var(--w-border);border-radius:var(--w-radius);overflow:hidden;display:flex;flex-direction:column;transition:var(--w-transition)}
.wb-card:hover{box-shadow:var(--w-shadow-lg);border-color:var(--w-brand);transform:translateY(-4px)}
.wb-thumb{height:110px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
.wb-thumb i{font-size:40px;color:rgba(255,255,255,.85)}
.wb-thumb-av{position:absolute;bottom:-16px;left:16px;width:38px;height:38px;border-radius:50%;border:3px solid #fff;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:.72rem;font-weight:800;color:#fff}
.wb-badge{position:absolute;top:8px;left:8px;font-size:.6rem;font-weight:800;padding:3px 9px;border-radius:20px;letter-spacing:.04em}
.wb-free{background:var(--w-success);color:#fff}
.wb-prem{background:var(--w-accent);color:var(--w-brand-deep)}
.wb-reg{position:absolute;top:8px;right:8px;background:rgba(0,0,0,.4);color:#fff;font-size:.6rem;font-weight:700;padding:3px 8px;border-radius:20px;display:flex;align-items:center;gap:3px}
.wb-reg i{font-size:10px}
.t-blue{background:linear-gradient(135deg,#07304F,#0D609E)}
.t-orange{background:linear-gradient(135deg,#07304F,#F08F1A)}
.t-green{background:linear-gradient(135deg,#0A4D7E,#16a34a)}
.t-purple{background:linear-gradient(135deg,#0A4D7E,#7c3aed)}
.t-teal{background:linear-gradient(135deg,#07304F,#0891b2)}
.t-amber{background:linear-gradient(135deg,#0A4D7E,#d97706)}
.wb-body{padding:24px 16px 12px;flex:1;display:flex;flex-direction:column}
.wb-cat{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--w-brand);margin-bottom:8px}
.wb-title{font-family:'Sora',sans-serif;font-size:.92rem;font-weight:800;color:var(--w-text);line-height:1.35;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.5em}
.wb-spk{font-size:.76rem;color:var(--w-muted);margin-bottom:12px}
.wb-spk strong{color:var(--w-text);font-weight:600}
.wb-meta{display:flex;flex-wrap:wrap;gap:10px;font-size:.74rem;color:var(--w-muted);padding-top:10px;border-top:1px solid var(--w-border);margin-top:auto}
.wb-mi{display:flex;align-items:center;gap:4px}
.wb-mi i{font-size:12px}
.wb-foot{padding:12px 16px;border-top:1px solid var(--w-border);display:flex;align-items:center;justify-content:space-between;gap:8px}
.wb-prov{display:inline-flex;align-items:center;gap:4px;font-size:.72rem;font-weight:600;padding:3px 9px;border-radius:12px}
.wp-z{background:var(--w-brand-light);color:var(--w-brand)}
.wp-m{background:#fefce8;color:#92400e}
.wp-t{background:#f3efff;color:#5b21b6}
.wb-foot .btn{padding:7px 14px;font-size:.78rem}
.wb-seats{padding:0 16px 12px}
.wb-seat-bar{height:5px;border-radius:4px;background:var(--w-border);overflow:hidden;margin-bottom:5px}
.wb-seat-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--w-accent),var(--w-accent-dark));transition:width .6s ease}
.wb-seat-label{font-size:.7rem;color:var(--w-muted);display:flex;justify-content:space-between}
.wb-seat-label strong{color:var(--w-accent-dark);font-weight:700}
.wb-seat-label.almost strong{color:#dc2626}
.wb-cal{display:inline-flex;align-items:center;gap:4px;font-size:.72rem;font-weight:600;color:var(--w-brand);background:none;border:none;cursor:pointer;padding:0;font-family:'Inter',sans-serif}
.wb-cal i{font-size:13px}
.wb-cal:hover{color:var(--w-brand-dark);text-decoration:underline}
.wb-foot-2{padding:0 16px 14px;display:flex;justify-content:flex-end}
.w-live-proof{display:flex;align-items:center;gap:9px;background:var(--w-brand-light);border:1px solid #cfe0f1;border-radius:10px;padding:9px 14px;margin-bottom:18px;font-size:.8rem;color:var(--w-brand-dark)}
.w-lp-dot{width:8px;height:8px;border-radius:50%;background:var(--w-success);flex-shrink:0;animation:w-pulse 1.6s ease-in-out infinite;box-shadow:0 0 0 3px rgba(22,163,74,.18)}
.w-lp-avatars{display:flex;margin-left:auto}
.w-lp-av{width:24px;height:24px;border-radius:50%;border:2px solid var(--w-brand-light);margin-left:-8px;font-size:.58rem;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center}
.w-view-tabs{display:inline-flex;background:var(--w-bg);border:1px solid var(--w-border);border-radius:10px;padding:4px;margin-bottom:18px;gap:2px}
.w-view-tab{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:7px;border:none;background:transparent;color:var(--w-muted);font-family:'Inter',sans-serif;font-size:.83rem;font-weight:600;cursor:pointer;transition:var(--w-transition);min-height:38px}
.w-view-tab i{font-size:15px}
.w-view-tab.active{background:var(--w-white);color:var(--w-brand);box-shadow:0 1px 4px rgba(10,47,87,.1)}
.w-search-sort{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:14px}
.w-search-box{position:relative;display:flex;align-items:center;flex:1;min-width:220px}
.w-search-box i{position:absolute;left:12px;font-size:16px;color:var(--w-muted);pointer-events:none;z-index:2}
.w-search-box input{width:100%;border:1.5px solid var(--w-border);border-radius:8px;padding:10px 14px 10px 36px;font-family:'Inter',sans-serif;font-size:.86rem;color:var(--w-text);background:var(--w-white);outline:none;min-height:42px}
.w-search-box input:focus{border-color:var(--w-brand)}
.w-sort-select{border:1.5px solid var(--w-border);border-radius:8px;padding:10px 36px 10px 14px;font-family:'Inter',sans-serif;font-size:.84rem;font-weight:500;color:var(--w-text);background:var(--w-white);cursor:pointer;outline:none;min-height:42px;-webkit-appearance:none;appearance:none}
.w-sort-select:focus{border-color:var(--w-brand)}
.w-empty-state{display:none;text-align:center;padding:48px 20px;color:var(--w-muted)}
.w-empty-state i{font-size:48px;color:var(--w-border);margin-bottom:14px}
.w-empty-state h3{font-size:1.05rem;font-weight:700;color:var(--w-text);margin-bottom:6px}
.w-empty-state p{font-size:.86rem;max-width:320px;margin:0 auto 16px}
.w-why-section{background:var(--w-white);padding:72px 0}
.w-why-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
.w-why-card{border:1px solid var(--w-border);border-radius:var(--w-radius);padding:26px 20px;background:var(--w-bg);transition:var(--w-transition)}
.w-why-card:hover{border-color:var(--w-brand);box-shadow:var(--w-shadow);transform:translateY(-3px);background:var(--w-white)}
.w-why-icon{width:46px;height:46px;border-radius:12px;background:var(--w-brand-light);color:var(--w-brand);display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.w-why-icon i{font-size:22px}
.w-why-card:hover .w-why-icon{background:var(--w-brand);color:var(--w-white)}
.w-why-title{font-weight:700;font-size:.92rem;margin-bottom:7px}
.w-why-desc{font-size:.82rem;color:var(--w-muted);line-height:1.62}
.w-spk-section{padding:72px 0;background:var(--w-bg)}
.w-spk-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:36px}
.w-spk-card{background:var(--w-white);border:1px solid var(--w-border);border-radius:var(--w-radius);padding:28px 22px;text-align:center;transition:var(--w-transition)}
.w-spk-card:hover{box-shadow:var(--w-shadow-lg);border-color:var(--w-brand);transform:translateY(-3px)}
.w-spk-av{width:72px;height:72px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:1.2rem;font-weight:800;color:#fff;margin:0 auto 14px;box-shadow:0 0 0 3px var(--w-white),0 0 0 5px var(--w-brand)}
.w-spk-name{font-family:'Sora',sans-serif;font-weight:700;font-size:1rem;color:var(--w-text);margin-bottom:3px}
.w-spk-role{font-size:.78rem;color:var(--w-muted);margin-bottom:12px;line-height:1.5}
.w-spk-tag{display:inline-flex;align-items:center;gap:5px;background:var(--w-brand-light);color:var(--w-brand);font-size:.7rem;font-weight:700;padding:4px 11px;border-radius:20px}
.w-spk-tag i{font-size:12px}
.w-spk-count{font-size:.76rem;color:var(--w-muted);margin-top:10px}
.w-testi-section{background:var(--w-white);padding:72px 0}
.w-testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:36px}
.w-testi-card{background:var(--w-bg);border:1px solid var(--w-border);border-radius:var(--w-radius);padding:26px}
.w-testi-stars{display:flex;gap:2px;margin-bottom:12px;color:var(--w-accent)}
.w-testi-stars i{font-size:16px}
.w-testi-q{font-size:.88rem;color:var(--w-text);line-height:1.75;margin-bottom:18px}
.w-testi-q::before{content:'\201C';font-size:1.5rem;color:var(--w-accent);font-family:'Sora',sans-serif;font-weight:800;line-height:.5;margin-right:2px;vertical-align:-.2em}
.w-testi-author{display:flex;align-items:center;gap:10px}
.w-testi-av{width:38px;height:38px;border-radius:50%;color:#fff;font-family:'Sora',sans-serif;font-size:.78rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.w-testi-name{font-weight:700;font-size:.84rem;color:var(--w-text)}
.w-testi-role{font-size:.72rem;color:var(--w-muted)}
.w-nl-band{background:linear-gradient(120deg,var(--w-brand-light) 0%,#dce9f8 100%);padding:52px 0;border-top:1px solid var(--w-border);border-bottom:1px solid var(--w-border)}
.w-nl-inner{display:flex;align-items:center;justify-content:space-between;gap:36px;flex-wrap:wrap}
.w-nl-text{flex:1 1 380px}
.w-nl-title{font-family:'Sora',sans-serif;font-size:clamp(1.25rem,2.2vw,1.6rem);font-weight:800;line-height:1.2;letter-spacing:-.02em;margin-bottom:8px}
.w-nl-title span{color:var(--w-brand)}
.w-nl-sub{color:var(--w-muted);font-size:.88rem;max-width:420px;line-height:1.65}
.w-nl-perks{display:flex;flex-direction:column;gap:7px;margin-top:14px}
.w-nl-perk{display:flex;align-items:center;gap:8px;font-size:.83rem;color:var(--w-text)}
.w-nl-perk i{font-size:14px;color:var(--w-success);flex-shrink:0}
.w-nl-form-wrap{flex:0 1 400px}
.w-nl-form{display:flex;flex-direction:column;gap:9px}
.w-nl-field{position:relative;display:flex;align-items:center}
.w-nl-field i{position:absolute;left:12px;font-size:16px;color:var(--w-muted);pointer-events:none}
.w-nl-field input{width:100%;border:1px solid var(--w-border);border-radius:8px;padding:11px 14px 11px 36px;font-family:'Inter',sans-serif;font-size:.9rem;color:var(--w-text);background:var(--w-white);outline:none;min-height:44px}
.w-nl-field input:focus{border-color:var(--w-brand)}
.w-nl-note{font-size:.72rem;color:var(--w-muted)}
.w-cta-section{padding:72px 0}
.w-dual-cta{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.w-cta-panel{border-radius:12px;padding:44px 32px}
.w-cta-panel.blue{background:linear-gradient(150deg,#07304F,var(--w-brand));color:var(--w-white)}
.w-cta-panel.blue h2, .w-cta-panel.blue p, .w-cta-panel.blue li, .w-cta-panel.blue strong, .w-cta-panel.blue a { color: var(--w-white, #fff) !important; }
.w-cta-panel.light{background:var(--w-white);color:var(--w-text);border:1px solid var(--w-border)}
.w-cta-ic{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.w-cta-ic i{font-size:25px}
.w-cta-panel.blue .w-cta-ic{background:rgba(255,255,255,.14);color:var(--w-white)}
.w-cta-panel.light .w-cta-ic{background:var(--w-brand-light);color:var(--w-brand)}
.w-cta-panel h2{font-size:1.35rem;font-weight:700;margin-bottom:10px}
.w-cta-panel p{font-size:.87rem;margin-bottom:22px}
.w-cta-panel.blue p{opacity:.86}
.w-cta-panel.light p{color:var(--w-muted)}
.w-cta-list{list-style:none;margin-bottom:26px;display:flex;flex-direction:column;gap:9px}
.w-cta-list li{display:flex;align-items:center;gap:9px;font-size:.85rem}
.w-cta-list li i{font-size:16px;color:var(--w-accent);flex-shrink:0}
.w-faq-section{background:var(--w-bg);padding:72px 0}
.w-faq-wrap{max-width:760px;margin:30px auto 0}
.w-faq-item{background:var(--w-white);border:1px solid var(--w-border);border-radius:var(--w-radius);margin-bottom:12px;overflow:hidden;transition:var(--w-transition)}
.w-faq-item:hover{border-color:#cfe0f1}
.w-faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 20px;background:none;border:none;cursor:pointer;font-family:'Sora',sans-serif;font-size:.92rem;font-weight:700;color:var(--w-text);text-align:left;line-height:1.4}
.w-faq-q i{font-size:18px;color:var(--w-brand);flex-shrink:0;transition:transform .2s}
.w-faq-item.open .w-faq-q i{transform:rotate(45deg)}
.w-faq-a{max-height:0;overflow:hidden;transition:max-height .26s ease}
.w-faq-a-inner{padding:0 20px 18px;font-size:.86rem;color:var(--w-muted);line-height:1.7}
.w-faq-item.open .w-faq-a{max-height:240px}
.w-section-label{display:inline-flex;align-items:center;gap:7px;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--w-brand);background:var(--w-brand-light);padding:5px 13px;border-radius:20px;margin-bottom:14px}
.w-section-label i{font-size:13px}
.w-section-title{font-size:clamp(1.6rem,2.9vw,2.25rem);font-weight:800;line-height:1.15;margin-bottom:12px}
.w-section-title span{color:var(--w-brand)}
.w-section-sub{color:var(--w-muted);font-size:.95rem;max-width:560px}
@media(max-width:860px){.w-hero-inner{grid-template-columns:1fr}.w-feat-card{transform:none;max-width:420px}.w-hero-stats{padding-bottom:36px}.w-grid{grid-template-columns:repeat(2,1fr)}.w-sb-item{padding:8px 20px}.w-dual-cta,.w-why-grid,.w-spk-grid,.w-testi-grid{grid-template-columns:1fr 1fr}}
@media(max-width:580px){.w-grid,.w-dual-cta,.w-why-grid,.w-spk-grid,.w-testi-grid{grid-template-columns:1fr}.w-cta-panel{padding:30px 22px}.w-stats-inner{flex-direction:column}.w-sb-item{border-right:none;border-bottom:1px solid rgba(255,255,255,.1);width:100%;justify-content:center}.w-sb-item:last-child{border-bottom:none}.w-nl-inner{flex-direction:column;gap:20px}.w-nl-form-wrap{flex:1 1 auto;width:100%}.w-nl-band{padding:40px 0}.w-faq-section{padding:48px 0}.w-cta-section{padding:48px 0}.w-why-section,.w-spk-section,.w-testi-section{padding:48px 0}.w-grid-section{padding:24px 0 40px}.w-search-sort{flex-direction:column;align-items:stretch}.w-sort-select{width:100%}}@media(max-width:580px){input,select,textarea{font-size:16px!important}}
.w-toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--w-brand-deep);color:#fff;padding:12px 20px;border-radius:10px;font-size:.85rem;font-weight:600;box-shadow:var(--w-shadow-lg);display:flex;align-items:center;gap:9px;z-index:1200;opacity:0;pointer-events:none;transition:opacity .25s,transform .25s;max-width:90vw}
.w-toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
.w-toast i{font-size:17px;color:#5ee9a0;flex-shrink:0}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
function getInitials($name) {
    $parts = explode(' ', $name);
    $initials = '';
    foreach ($parts as $p) {
        if (!empty(trim($p))) $initials .= strtoupper(substr(trim($p), 0, 1));
    }
    return substr($initials, 0, 2);
}

function getMeetingProvider($link) {
    $link = strtolower($link);
    if (strpos($link, 'zoom.us') !== false) return ['name' => 'Zoom', 'class' => 'wp-z'];
    if (strpos($link, 'meet.google.com') !== false) return ['name' => 'Google Meet', 'class' => 'wp-m'];
    if (strpos($link, 'teams.microsoft.com') !== false) return ['name' => 'Teams', 'class' => 'wp-t'];
    return ['name' => 'Online', 'class' => 'wp-z'];
}

function getProviderIcon($link) {
    $link = strtolower($link);
    if (strpos($link, 'zoom.us') !== false) return 'ti ti-video';
    if (strpos($link, 'meet.google.com') !== false) return 'ti ti-brand-google';
    if (strpos($link, 'teams.microsoft.com') !== false) return 'ti ti-brand-microsoft';
    return 'ti ti-world';
}

$gradients = ['t-blue', 't-orange', 't-green', 't-purple', 't-teal', 't-amber'];
$categories = ['Interview Prep', 'CV & Resume', 'Salary & Negotiation', 'Tech Skills', 'LinkedIn & Brand', 'Remote Work'];
$catSlugs = ['interview', 'cv', 'salary', 'tech', 'linkedin', 'remote'];

// Find featured webinar (first upcoming)
$featured = !empty($webinars) ? $webinars[0] : null;
?>

<!-- HERO -->
<section class="w-hero" aria-label="Career Webinars">
  <span class="w-hero-grid" aria-hidden="true"></span>
  <div class="container">
    <div class="w-hero-inner">
      <div>
        <nav class="w-hero-bc" aria-label="Breadcrumb">
          <a href="<?= base_url() ?>"><i class="ti ti-home"></i> Home</a>
          <i class="ti ti-chevron-right"></i>
          <a href="<?= base_url('training') ?>">Training</a>
          <i class="ti ti-chevron-right"></i>
          <span aria-current="page">Webinars</span>
        </nav>
        <div class="w-hero-tag"><span class="w-live-dot"></span> Live sessions every week</div>
        <h1>Free Career <em>Webinars</em> in Nigeria</h1>
        <p class="w-hero-sub">Level up with free and premium live training on interviews, CV writing, salary negotiation, and in-demand tech skills &mdash; taught by Nigeria&rsquo;s top career experts.</p>
        <div class="w-chips">
          <a href="<?= base_url('training/webinars?topic=interview') ?>" class="w-chip"><i class="ti ti-microphone"></i> Interview Prep</a>
          <a href="<?= base_url('training/webinars?topic=cv') ?>" class="w-chip"><i class="ti ti-file-text"></i> CV &amp; Resume</a>
          <a href="<?= base_url('training/webinars?topic=salary') ?>" class="w-chip"><i class="ti ti-currency-naira"></i> Salary Negotiation</a>
          <a href="<?= base_url('training/webinars?topic=tech') ?>" class="w-chip"><i class="ti ti-chip"></i> Tech Skills</a>
        </div>
        <div class="w-hero-stats">
          <div><div class="w-stat-val">2,<span>400</span>+</div><div class="w-stat-lbl">professionals trained</div></div>
          <div><div class="w-stat-val"><span>48</span></div><div class="w-stat-lbl">sessions hosted</div></div>
          <div><div class="w-stat-val">4.<span>9</span>&#9733;</div><div class="w-stat-lbl">average rating</div></div>
        </div>
      </div>
      <?php if ($featured): ?>
      <?php
        $fi = getInitials($featured->speaker_name);
        $fProvider = getMeetingProvider($featured->meeting_link);
        $fDate = date('D, j F Y', strtotime($featured->scheduled_at));
        $fTime = date('h:i A', strtotime($featured->scheduled_at));
        $fDuration = '60 mins';
        $fTimestamp = strtotime($featured->scheduled_at);
      ?>
      <div class="w-feat-card" role="region" aria-label="Next webinar">
        <div class="w-fc-thumb">
          <i class="ti ti-users"></i>
          <div class="w-fc-avatar"><?= $fi ?></div>
          <div class="w-fc-badges"><span class="w-fc-badge w-fc-badge-next">UP NEXT</span><span class="w-fc-badge w-fc-badge-cat">Career</span></div>
        </div>
        <div class="w-fc-body">
          <div class="w-fc-spk-name"><?= esc($featured->speaker_name) ?></div>
          <div class="w-fc-spk-role">Career Expert</div>
          <div class="w-fc-title"><?= esc($featured->title) ?></div>
          <div class="w-fc-meta">
            <span class="w-fc-mi"><i class="ti ti-calendar"></i> <?= $fDate ?></span>
            <span class="w-fc-mi"><i class="ti ti-clock"></i> <?= $fTime ?></span>
            <span class="w-fc-mi"><span class="w-prov-z"><i class="<?= getProviderIcon($featured->meeting_link) ?>"></i> <?= $fProvider['name'] ?></span></span>
          </div>
          <div class="w-countdown" aria-label="Countdown" data-target="<?= $fTimestamp ?>">
            <div class="w-cd-box"><div class="w-cd-n cd-d">00</div><div class="w-cd-l">Days</div></div>
            <div class="w-cd-box"><div class="w-cd-n cd-h">00</div><div class="w-cd-l">Hrs</div></div>
            <div class="w-cd-box"><div class="w-cd-n cd-m">00</div><div class="w-cd-l">Min</div></div>
            <div class="w-cd-box"><div class="w-cd-n cd-s">00</div><div class="w-cd-l">Sec</div></div>
          </div>
          <?php if (auth()->loggedIn()): ?>
            <button class="w-fc-cta btn-register" data-id="<?= $featured->id ?>">Reserve your free seat &rarr;</button>
          <?php else: ?>
            <a href="<?= base_url('login') ?>" class="w-fc-cta">Reserve your free seat &rarr;</a>
          <?php endif; ?>
          <div class="w-fc-note"><i class="ti ti-circle-check"></i> Free &middot; Register now</div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- STATS BAND -->
<div class="w-stats-band">
  <div class="container">
    <div class="w-stats-inner">
      <div class="w-sb-item">
        <div class="w-sb-icon"><i class="ti ti-users"></i></div>
        <div><div class="w-sb-val">2,400+</div><div class="w-sb-lbl">Professionals Trained</div></div>
      </div>
      <div class="w-sb-item">
        <div class="w-sb-icon"><i class="ti ti-video"></i></div>
        <div><div class="w-sb-val">48</div><div class="w-sb-lbl">Sessions Hosted</div></div>
      </div>
      <div class="w-sb-item">
        <div class="w-sb-icon"><i class="ti ti-star"></i></div>
        <div><div class="w-sb-val">4.9&#9733;</div><div class="w-sb-lbl">Average Rating</div></div>
      </div>
      <div class="w-sb-item">
        <div class="w-sb-icon"><i class="ti ti-certificate"></i></div>
        <div><div class="w-sb-val">100%</div><div class="w-sb-lbl">Live &amp; Interactive</div></div>
      </div>
    </div>
  </div>
</div>

<!-- FILTER + GRID -->
<div class="w-filter-wrap">
  <div class="container">
    <div class="w-live-proof" role="status">
      <span class="w-lp-dot" aria-hidden="true"></span>
      <span><?= !empty($webinars) ? '<strong>' . count($webinars) . ' sessions</strong> available &middot; Register now to secure your seat' : '<strong>Check back soon</strong> for upcoming sessions' ?></span>
    </div>

    <div class="w-view-tabs" role="tablist" aria-label="Webinar view">
      <button class="w-view-tab active" role="tab" aria-selected="true" onclick="setView('upcoming',this)"><i class="ti ti-calendar"></i> Upcoming</button>
      <button class="w-view-tab" role="tab" aria-selected="false" onclick="setView('ondemand',this)"><i class="ti ti-clock"></i> On-demand</button>
    </div>

    <div class="w-filter-top">
      <h2 id="sessions-h">Upcoming sessions</h2>
      <span class="w-count" id="wcount">Showing <?= count($webinars) ?> webinar<?= count($webinars) !== 1 ? 's' : '' ?></span>
    </div>

    <div class="w-search-sort">
      <div class="w-search-box">
        <i class="ti ti-search"></i>
        <input type="search" id="wsearch" placeholder="Search webinars, topics or speakers&hellip;" oninput="applyFilters()" aria-label="Search webinars">
      </div>
      <select class="w-sort-select" id="wsort" onchange="applyFilters()" aria-label="Sort webinars">
        <option value="date">Soonest first</option>
        <option value="popular">Most popular</option>
        <option value="price">Free first</option>
      </select>
    </div>

    <div class="w-pill-row" role="group" aria-label="Filter by topic">
      <button class="w-pill on" aria-pressed="true" data-filter="all" onclick="doFilter('all',this)">All topics</button>
      <button class="w-pill" aria-pressed="false" data-filter="interview" onclick="doFilter('interview',this)">Interview prep</button>
      <button class="w-pill" aria-pressed="false" data-filter="cv" onclick="doFilter('cv',this)">CV &amp; Resume</button>
      <button class="w-pill" aria-pressed="false" data-filter="salary" onclick="doFilter('salary',this)">Salary &amp; Negotiation</button>
      <button class="w-pill" aria-pressed="false" data-filter="tech" onclick="doFilter('tech',this)">Tech skills</button>
      <button class="w-pill" aria-pressed="false" data-filter="linkedin" onclick="doFilter('linkedin',this)">LinkedIn &amp; Brand</button>
      <button class="w-pill" aria-pressed="false" data-filter="remote" onclick="doFilter('remote',this)">Remote work</button>
    </div>
    <p id="wcount-live" class="sr-only" role="status" aria-live="polite"></p>
  </div>
</div>

<!-- WEBINAR GRID -->
<section class="w-grid-section">
  <div class="container">
    <div class="w-grid" id="wgrid">
      <?php if (empty($webinars)): ?>
        <div class="w-empty-state" style="display:block;grid-column:1/-1">
          <i class="ti ti-video-off"></i>
          <h3>No upcoming webinars at the moment</h3>
          <p>Check back later or subscribe to our newsletter to get notified.</p>
        </div>
      <?php else: ?>
        <?php foreach ($webinars as $i => $webinar): ?>
        <?php
          $gi = $i % count($gradients);
          $gClass = $gradients[$gi];
          $ci = $i % count($categories);
          $cat = $categories[$ci];
          $cSlug = $catSlugs[$ci];
          $initials = getInitials($webinar->speaker_name);
          $provider = getMeetingProvider($webinar->meeting_link);
          $pIcon = getProviderIcon($webinar->meeting_link);
          $dateFormatted = date('D j M', strtotime($webinar->scheduled_at));
          $timeFormatted = date('h:i A', strtotime($webinar->scheduled_at));
          $wTimestamp = strtotime($webinar->scheduled_at);
          $isUpcoming = $webinar->status === 'upcoming';
        ?>
        <article class="wb-card" data-cat="<?= $cSlug ?>" data-date="<?= date('Y-m-d', strtotime($webinar->scheduled_at)) ?>" data-pop="0" data-price="0" data-title="<?= strtolower(esc($webinar->title)) ?>">
          <div class="wb-thumb <?= $gClass ?>">
            <i class="ti ti-users"></i>
            <div class="wb-thumb-av" style="background:var(--w-brand)"><?= $initials ?></div>
            <span class="wb-badge wb-free">FREE</span>
            <span class="wb-reg"><i class="ti ti-user"></i>0</span>
          </div>
          <div class="wb-body">
            <div class="wb-cat"><?= $cat ?></div>
            <div class="wb-title"><?= esc($webinar->title) ?></div>
            <div class="wb-spk"><strong><?= esc($webinar->speaker_name) ?></strong></div>
            <div class="wb-meta">
              <span class="wb-mi"><i class="ti ti-calendar"></i> <?= $dateFormatted ?> &middot; <?= $timeFormatted ?></span>
            </div>
          </div>
          <div class="wb-foot">
            <span class="wb-prov <?= $provider['class'] ?>"><i class="<?= $pIcon ?>"></i> <?= $provider['name'] ?></span>
            <?php if ($isUpcoming): ?>
              <?php if (auth()->loggedIn()): ?>
                <button class="btn btn-primary btn-sm btn-register" data-id="<?= $webinar->id ?>">Register free &rarr;</button>
              <?php else: ?>
                <a href="<?= base_url('login') ?>" class="btn btn-primary btn-sm">Register free &rarr;</a>
              <?php endif; ?>
            <?php else: ?>
              <span class="badge bg-secondary"><?= ucfirst($webinar->status) ?></span>
            <?php endif; ?>
          </div>
        </article>
        <?php endforeach; ?>
      <?php endif; ?>

      <div class="w-empty-state" id="empty-state">
        <i class="ti ti-search"></i>
        <h3>No webinars match your search</h3>
        <p>Try a different keyword or clear your filters to see all upcoming sessions.</p>
        <button class="btn btn-outline-primary" type="button" onclick="clearFilters()">Clear filters</button>
      </div>
    </div>
  </div>
</section>

<!-- WHY ATTEND -->
<section class="w-why-section" aria-labelledby="why-h">
  <div class="container">
    <div class="text-center mb-5">
      <div class="w-section-label" style="margin:0 auto 14px"><i class="ti ti-lightbulb"></i> Why attend</div>
      <h2 class="w-section-title" id="why-h">Everything you need to <span>get hired faster</span></h2>
      <p class="w-section-sub" style="margin:0 auto">Built for Nigerian professionals &mdash; practical, actionable, led by people who actually hire.</p>
    </div>
    <div class="w-why-grid">
      <div class="w-why-card"><div class="w-why-icon"><i class="ti ti-shield"></i></div><div class="w-why-title">Verified expert speakers</div><div class="w-why-desc">Every speaker is a practising hiring manager or HR lead from companies you recognise &mdash; no generic career coaches.</div></div>
      <div class="w-why-card"><div class="w-why-icon"><i class="ti ti-microphone"></i></div><div class="w-why-title">Live Q&amp;A every session</div><div class="w-why-desc">Ask questions directly during the live session. No pre-recorded videos &mdash; every webinar is 100% live and interactive.</div></div>
      <div class="w-why-card"><div class="w-why-icon"><i class="ti ti-cap"></i></div><div class="w-why-title">Certificate on premium sessions</div><div class="w-why-desc">Attend any premium (paid) webinar and earn a verified JobberRecruit certificate &mdash; add it to your LinkedIn profile and CV to stand out to employers.</div></div>
      <div class="w-why-card"><div class="w-why-icon"><i class="ti ti-currency-naira"></i></div><div class="w-why-title">Mostly free to attend</div><div class="w-why-desc">Most sessions are completely free. Premium workshops are priced lower than any alternative currently available in Nigeria.</div></div>
    </div>
  </div>
</section>

<!-- SPEAKERS -->
<section class="w-spk-section" aria-labelledby="spk-h">
  <div class="container">
    <div class="text-center mb-5">
      <div class="w-section-label" style="margin:0 auto 14px"><i class="ti ti-microphone"></i> Meet the speakers</div>
      <h2 class="w-section-title" id="spk-h">Taught by people who <span>actually hire</span></h2>
      <p class="w-section-sub" style="margin:0 auto">Senior professionals actively working in recruitment, HR, or their industry &mdash; not career coaches.</p>
    </div>
    <div class="w-spk-grid">
      <?php
        $uniqueSpeakers = [];
        foreach ($webinars as $w) {
            $name = trim($w->speaker_name);
            if (!isset($uniqueSpeakers[$name])) {
                $uniqueSpeakers[$name] = ['name' => $name, 'count' => 0];
            }
            $uniqueSpeakers[$name]['count']++;
        }
        $spkColors = ['var(--w-brand)', '#7c3aed', '#16a34a', '#0891b2', '#d97706', '#dc2626'];
        $spkIdx = 0;
      ?>
      <?php foreach (array_slice($uniqueSpeakers, 0, 6) as $spk): ?>
      <?php
        $si = getInitials($spk['name']);
        $sc = $spkColors[$spkIdx % count($spkColors)];
        $spkIdx++;
      ?>
      <div class="w-spk-card">
        <div class="w-spk-av" style="background:<?= $sc ?>"><?= $si ?></div>
        <div class="w-spk-name"><?= esc($spk['name']) ?></div>
        <div class="w-spk-role">Career Expert</div>
        <span class="w-spk-tag"><i class="ti ti-cap"></i> Career Development</span>
        <div class="w-spk-count"><?= $spk['count'] ?> session<?= $spk['count'] > 1 ? 's' : '' ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="w-testi-section" aria-labelledby="tst-h">
  <div class="container">
    <div class="text-center mb-5">
      <div class="w-section-label" style="margin:0 auto 14px"><i class="ti ti-star"></i> What attendees say</div>
      <h2 class="w-section-title" id="tst-h">Real results from <span>real professionals</span></h2>
    </div>
    <div class="w-testi-grid">
      <div class="w-testi-card">
        <div class="w-testi-stars"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></div>
        <p class="w-testi-q">I attended the interview prep webinar and received a job offer the following Monday. The frameworks shared were spot-on for Nigerian corporate culture.</p>
        <div class="w-testi-author"><div class="w-testi-av" style="background:var(--w-brand)">OK</div><div><div class="w-testi-name">Oluwaseun Kehinde</div><div class="w-testi-role">Marketing Manager &middot; Lagos</div></div></div>
      </div>
      <div class="w-testi-card">
        <div class="w-testi-stars"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></div>
        <p class="w-testi-q">The CV webinar helped me completely rewrite my resume. Within two weeks I had three interview calls from companies that had previously ignored me for months.</p>
        <div class="w-testi-author"><div class="w-testi-av" style="background:#16a34a">BI</div><div><div class="w-testi-name">Blessing Ihejirika</div><div class="w-testi-role">Finance Analyst &middot; Abuja</div></div></div>
      </div>
      <div class="w-testi-card">
        <div class="w-testi-stars"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></div>
        <p class="w-testi-q">The tech career webinar was the push I needed. I switched from banking into software and doubled my salary within 8 months. Best investment I ever made.</p>
        <div class="w-testi-author"><div class="w-testi-av" style="background:#7c3aed">CA</div><div><div class="w-testi-name">Chukwuemeka Adaeze</div><div class="w-testi-role">Software Engineer &middot; Remote</div></div></div>
      </div>
    </div>
  </div>
</section>

<!-- NEWSLETTER BAND -->
<div class="w-nl-band">
  <div class="container">
    <div class="w-nl-inner">
      <div class="w-nl-text">
        <div class="w-section-label"><i class="ti ti-cap"></i> Stay in the loop</div>
        <div class="w-nl-title">Never miss a <span>career webinar</span></div>
        <p class="w-nl-sub">Weekly schedule, early access to premium sessions, and exclusive career tips from Nigeria&rsquo;s top employers &mdash; free to your inbox.</p>
        <div class="w-nl-perks">
          <div class="w-nl-perk"><i class="ti ti-check"></i> Weekly webinar schedule every Monday</div>
          <div class="w-nl-perk"><i class="ti ti-check"></i> Early access to premium sessions</div>
          <div class="w-nl-perk"><i class="ti ti-check"></i> Career advice from top Nigerian employers</div>
          <div class="w-nl-perk"><i class="ti ti-check"></i> No spam &mdash; unsubscribe any time</div>
        </div>
      </div>
      <div class="w-nl-form-wrap">
        <form id="webinar-newsletter-form" class="w-nl-form">
          <div class="w-nl-field"><i class="ti ti-mail"></i><input type="email" name="email" placeholder="Enter your email address" required></div>
          <button type="submit" class="btn btn-primary" id="btn-webinar-subscribe">Subscribe to Career Webinar Newsletter</button>
          <p class="w-nl-note">By subscribing you agree to our <a href="<?= base_url('privacy-policy') ?>">Privacy policy</a>. Unsubscribe any time.</p>
        </form>
        <div id="webinar-newsletter-msg" class="mt-2 small"></div>
      </div>
    </div>
  </div>
</div>

<!-- FAQ -->
<section class="w-faq-section" aria-labelledby="faq-h">
  <div class="container">
    <div class="text-center mb-5">
      <div class="w-section-label" style="margin:0 auto 14px"><i class="ti ti-lightbulb"></i> Questions &amp; answers</div>
      <h2 class="w-section-title" id="faq-h">Everything you need to <span>know</span></h2>
    </div>
    <div class="w-faq-wrap">
      <div class="w-faq-item">
        <button class="w-faq-q" aria-expanded="false" onclick="toggleFaq(this)">Are JobberRecruit webinars really free? <i class="ti ti-plus"></i></button>
        <div class="w-faq-a"><div class="w-faq-a-inner">Most of our webinars are completely free to attend. A small number of in-depth premium workshops carry a low fee, always clearly marked with the price on the webinar card before you register.</div></div>
      </div>
      <div class="w-faq-item">
        <button class="w-faq-q" aria-expanded="false" onclick="toggleFaq(this)">Will I get a recording if I miss the live session? <i class="ti ti-plus"></i></button>
        <div class="w-faq-a"><div class="w-faq-a-inner">Yes. Everyone who registers receives a link to the recording by email after the session, so you can catch up even if you can&rsquo;t attend live.</div></div>
      </div>
      <div class="w-faq-item">
        <button class="w-faq-q" aria-expanded="false" onclick="toggleFaq(this)">Do I get a certificate for attending? <i class="ti ti-plus"></i></button>
        <div class="w-faq-a"><div class="w-faq-a-inner">Certificates are issued for premium (paid) webinars only. After attending a premium session you receive a verified JobberRecruit certificate of attendance that you can add to your LinkedIn profile and CV to stand out to employers. Free webinars don&rsquo;t include a certificate.</div></div>
      </div>
      <div class="w-faq-item">
        <button class="w-faq-q" aria-expanded="false" onclick="toggleFaq(this)">Which platforms are the webinars hosted on? <i class="ti ti-plus"></i></button>
        <div class="w-faq-a"><div class="w-faq-a-inner">Webinars run on Zoom, Google Meet, or Microsoft Teams. The platform for each session is shown on its card, and the join link is emailed to you after you register.</div></div>
      </div>
      <div class="w-faq-item">
        <button class="w-faq-q" aria-expanded="false" onclick="toggleFaq(this)">How do I register for a webinar? <i class="ti ti-plus"></i></button>
        <div class="w-faq-a"><div class="w-faq-a-inner">Click the Register button on any webinar card. If you&rsquo;re logged in, registration is one tap. You&rsquo;ll get a confirmation email with the join link and a calendar invite straight away.</div></div>
      </div>
      <div class="w-faq-item">
        <button class="w-faq-q" aria-expanded="false" onclick="toggleFaq(this)">Can I ask questions during the webinar? <i class="ti ti-plus"></i></button>
        <div class="w-faq-a"><div class="w-faq-a-inner">Absolutely. Every webinar includes a live Q&amp;A segment where you can ask the speaker your questions directly &mdash; no pre-recorded videos.</div></div>
      </div>
    </div>
  </div>
</section>

<!-- DUAL CTA -->
<section class="w-cta-section">
  <div class="container">
    <div class="w-dual-cta">
      <div class="w-cta-panel blue">
        <div class="w-cta-ic"><i class="ti ti-briefcase"></i></div>
        <h2>Ready to find your next job?</h2>
        <p>Join the growing community of Nigerians using JobberRecruit to land great roles faster.</p>
        <ul class="w-cta-list">
          <li><i class="ti ti-check"></i> AI resume builder</li>
          <li><i class="ti ti-check"></i> AI mock interviews</li>
          <li><i class="ti ti-check"></i> Personalised career advice</li>
          <li><i class="ti ti-check"></i> Smart job alerts</li>
          <li><i class="ti ti-check"></i> Training &amp; certificates</li>
        </ul>
        <a href="<?= base_url('register') ?>" class="btn btn-warning fw-bold text-dark">Create free account &rarr;</a>
      </div>
      <div class="w-cta-panel light">
        <div class="w-cta-ic"><i class="ti ti-rocket"></i></div>
        <h2>Looking to hire? Post a job free</h2>
        <p>Post your vacancy free and reach verified candidates across Nigeria.</p>
        <ul class="w-cta-list">
          <li><i class="ti ti-check"></i> Post your first job free</li>
          <li><i class="ti ti-check"></i> Recruitment dashboard</li>
          <li><i class="ti ti-check"></i> Smart application management</li>
          <li><i class="ti ti-check"></i> Verified candidate database</li>
          <li><i class="ti ti-check"></i> Referral rewards</li>
        </ul>
        <a href="<?= base_url('post-a-job') ?>" class="btn btn-primary">Post a job free &rarr;</a>
      </div>
    </div>
  </div>
</section>

<div class="w-toast" id="w-toast" role="status" aria-live="polite"><i class="ti ti-circle-check"></i><span id="w-toast-msg"></span></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/* ── COUNTDOWN ── */
(function() {
  var cdTarget = document.querySelector('.w-countdown');
  if (!cdTarget) return;
  var target = new Date(parseInt(cdTarget.getAttribute('data-target')) * 1000);
  function tick() {
    var diff = target - new Date();
    if (diff <= 0) {
      document.querySelectorAll('.w-countdown .cd-d, .w-countdown .cd-h, .w-countdown .cd-m, .w-countdown .cd-s').forEach(function(el) { el.textContent = '00'; });
      return;
    }
    var d = Math.floor(diff / 864e5);
    var h = Math.floor((diff % 864e5) / 36e5);
    var m = Math.floor((diff % 36e5) / 6e4);
    var s = Math.floor((diff % 6e4) / 1e3);
    document.querySelector('.w-countdown .cd-d').textContent = String(d).padStart(2, '0');
    document.querySelector('.w-countdown .cd-h').textContent = String(h).padStart(2, '0');
    document.querySelector('.w-countdown .cd-m').textContent = String(m).padStart(2, '0');
    document.querySelector('.w-countdown .cd-s').textContent = String(s).padStart(2, '0');
  }
  tick();
  setInterval(tick, 1000);
})();

/* ── FILTER + SEARCH + SORT ── */
var activeCat = 'all';
function doFilter(cat, btn) {
  activeCat = cat;
  document.querySelectorAll('.w-pill').forEach(function(p) { p.classList.remove('on'); p.setAttribute('aria-pressed', 'false'); });
  btn.classList.add('on'); btn.setAttribute('aria-pressed', 'true');
  applyFilters();
}
function applyFilters() {
  var q = (document.getElementById('wsearch') || {}).value || '';
  q = q.trim().toLowerCase();
  var sort = (document.getElementById('wsort') || {}).value || 'date';
  var grid = document.getElementById('wgrid');
  var cards = Array.prototype.slice.call(document.querySelectorAll('.wb-card'));
  var visible = [];
  cards.forEach(function(c) {
    var okCat = activeCat === 'all' || c.dataset.cat === activeCat;
    var hay = (c.dataset.title || '') + ' ' + (c.textContent || '').toLowerCase();
    var okSearch = !q || hay.indexOf(q) !== -1;
    var show = okCat && okSearch;
    c.style.display = show ? '' : 'none';
    if (show) visible.push(c);
  });
  visible.sort(function(a, b) {
    if (sort === 'popular') return (+b.dataset.pop) - (+a.dataset.pop);
    if (sort === 'price') return (+a.dataset.price) - (+b.dataset.price);
    return new Date(a.dataset.date) - new Date(b.dataset.date);
  });
  visible.forEach(function(c) { grid.appendChild(c); });
  var n = visible.length;
  var el = document.getElementById('wcount'); if (el) el.textContent = 'Showing ' + n + ' webinar' + (n !== 1 ? 's' : '');
  var live = document.getElementById('wcount-live'); if (live) live.textContent = n + ' webinar' + (n !== 1 ? 's' : '') + ' found';
  var empty = document.getElementById('empty-state'); if (empty) empty.style.display = n === 0 ? 'block' : 'none';
}
function clearFilters() {
  activeCat = 'all';
  document.querySelectorAll('.w-pill').forEach(function(p) { var on = p.dataset.filter === 'all'; p.classList.toggle('on', on); p.setAttribute('aria-pressed', String(on)); });
  var s = document.getElementById('wsearch'); if (s) s.value = '';
  var so = document.getElementById('wsort'); if (so) so.value = 'date';
  applyFilters();
}

/* ── VIEW TABS ── */
function setView(view, btn) {
  document.querySelectorAll('.w-view-tab').forEach(function(t) { t.classList.remove('active'); t.setAttribute('aria-selected', 'false'); });
  btn.classList.add('active'); btn.setAttribute('aria-selected', 'true');
  var h = document.getElementById('sessions-h');
  if (view === 'ondemand') {
    if (h) h.textContent = 'On-demand recordings';
    showToast('On-demand recordings are coming soon \u2014 subscribe to be notified.');
  } else {
    if (h) h.textContent = 'Upcoming sessions';
  }
}

/* ── FAQ ACCORDION ── */
function toggleFaq(btn) {
  var item = btn.parentElement;
  var open = item.classList.toggle('open');
  btn.setAttribute('aria-expanded', String(open));
}

/* ── TOAST ── */
var toastT;
function showToast(msg) {
  var t = document.getElementById('w-toast');
  if (!t) return;
  document.getElementById('w-toast-msg').textContent = msg;
  t.classList.add('show');
  clearTimeout(toastT);
  toastT = setTimeout(function() { t.classList.remove('show'); }, 3400);
}

/* initial sort by date */
applyFilters();

/* ── WEBINAR REGISTRATION (preserved from original) ── */
document.addEventListener('DOMContentLoaded', function() {
  const btns = document.querySelectorAll('.btn-register');
  btns.forEach(btn => {
    btn.addEventListener('click', function() {
      const webinarId = this.getAttribute('data-id');
      const originalHtml = this.innerHTML;
      this.disabled = true;
      this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
      fetch('<?= base_url('webinars/register/') ?>' + webinarId, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 201 || !data.error) {
          toastr.success(data.message || 'Successfully registered!');
          this.classList.remove('btn-primary');
          this.classList.add('btn-success');
          this.innerHTML = '<i class="ti ti-check me-1"></i> Registered';
        } else {
          toastr.error(data.messages ? data.messages.error : (data.message || 'An error occurred'));
          this.disabled = false;
          this.innerHTML = originalHtml;
        }
      })
      .catch(error => {
        toastr.error('An error occurred. Please try again.');
        this.disabled = false;
        this.innerHTML = originalHtml;
      });
    });
  });

  /* ── NEWSLETTER SUBSCRIPTION (preserved from original) ── */
  const webinarForm = document.getElementById('webinar-newsletter-form');
  if (webinarForm) {
    webinarForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = document.getElementById('btn-webinar-subscribe');
      const msg = document.getElementById('webinar-newsletter-msg');
      const formData = new FormData(webinarForm);
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
      fetch('<?= base_url('newsletter/subscribe') ?>', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 201 || data.status === 200 || !data.error) {
          msg.className = 'mt-2 small text-success';
          msg.innerHTML = '<i class="ti ti-check-circle me-1"></i>' + (data.message || 'Successfully subscribed!');
          webinarForm.reset();
        } else {
          msg.className = 'mt-2 small text-warning';
          msg.innerHTML = data.messages ? data.messages.error : (data.message || 'An error occurred');
        }
      })
      .catch(error => {
        msg.className = 'mt-2 small text-warning';
        msg.innerHTML = 'An error occurred. Please try again.';
      })
      .finally(() => {
        btn.disabled = false;
        btn.innerHTML = 'Subscribe';
      });
    });
  }
});
</script>
<?= $this->endSection() ?>
