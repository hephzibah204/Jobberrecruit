<?php $page_title = 'Salary Negotiation Coach'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
:root {
  --brand: #0861A9;
  --brand-dark: #064A85;
  --brand-deep: #0A2F57;
  --brand-light: #E6F0F8;
  --accent: #ED9020;
  --accent-dark: #C8770E;
  --accent-light: #FDF1E0;
  --text: #141926;
  --muted: #5b6577;
  --bg: #f5f7fb;
  --white: #fff;
  --border: #e2e8f2;
  --success: #16a34a;
  --success-light: #e8f7ee;
  --danger: #dc2626;
  --danger-light: #fdeaea;
  --radius: 10px;
  --radius-lg: 14px;
  --transition: .18s ease;
}

/* ═══ EXTRACTED MOCKUP CSS MISSING IN PHP VIEW ═══ */
.content{flex:1;padding:clamp(18px,3vw,32px);padding-bottom:110px;display:flex;flex-direction:column;gap:clamp(16px,2.2vw,24px)}
@media (min-width:1025px){.content{padding-bottom:clamp(18px,3vw,32px)}}
.nego-hero{position:relative;overflow:hidden;border-radius:var(--radius-lg);color:#fff;padding:clamp(22px,3.2vw,34px);
  background:radial-gradient(ellipse 60% 90% at 88% 8%,rgba(237,144,32,.22) 0%,transparent 55%),linear-gradient(150deg,#0A2F57 0%,#064A85 55%,#0861A9 100%);box-shadow:var(--shadow)}
.nego-hero::before{content:'';position:absolute;inset:0;pointer-events:none;opacity:.5;
  background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);
  background-size:42px 42px;-webkit-mask-image:radial-gradient(ellipse 90% 80% at 80% 0%,#000 30%,transparent 80%);mask-image:radial-gradient(ellipse 90% 80% at 80% 0%,#000 30%,transparent 80%)}
.nego-hero .hero-grid{position:relative;display:grid;grid-template-columns:minmax(0,1fr) 190px;gap:clamp(16px,3vw,40px);align-items:center}
@media (max-width:760px){.nego-hero .hero-grid{grid-template-columns:1fr}.hero-orb{display:none}}
.hero-badges{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
.hb{display:inline-flex;align-items:center;gap:6px;font-size:.66rem;font-weight:700;letter-spacing:.04em;padding:5px 12px;border-radius:20px}
.hb svg{width:12px;height:12px}
.hb--premium{background:rgba(237,144,32,.18);border:1px solid rgba(237,144,32,.45);color:#ffd9a8}
.hb--live{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.22);color:#d8ecff}
.pulse{width:7px;height:7px;border-radius:50%;background:#4ade80;box-shadow:0 0 0 0 rgba(74,222,128,.6);animation:pulse 1.8s infinite}
@keyframes pulse{0%{box-shadow:0 0 0 0 rgba(74,222,128,.55)}70%{box-shadow:0 0 0 8px rgba(74,222,128,0)}100%{box-shadow:0 0 0 0 rgba(74,222,128,0)}}
.nego-hero h1{font-size:clamp(1.5rem,3.4vw,2.15rem);font-weight:800;line-height:1.15;margin:2px 0 8px}
.nego-hero h1 span{color:var(--accent)}
.hero-sub{font-size:clamp(.84rem,1.4vw,.95rem);color:rgba(255,255,255,.82);max-width:560px;line-height:1.65}
.hero-chips{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
.hchip{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.16);border-radius:12px;padding:10px 14px;min-width:150px}
.hchip-lbl{display:inline-flex;align-items:center;gap:6px;font-size:.62rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.6)}
.hchip-lbl svg{width:12px;height:12px;color:var(--accent)}
.hchip-val{font-family:'Sora',sans-serif;font-weight:800;font-size:1.02rem;margin-top:3px}
.hchip-val small{font-size:.66rem;font-weight:600;color:rgba(255,255,255,.55)}
.goal-track{height:5px;border-radius:20px;background:rgba(255,255,255,.15);overflow:hidden;margin-top:7px}
.goal-fill{height:100%;border-radius:20px;background:linear-gradient(90deg,var(--accent),#ffc069);transition:width .6s ease}
.hero-orb{position:relative;width:190px;height:190px;justify-self:end}
.hero-orb svg{width:100%;height:100%}
.orb-ring{transform-origin:100px 100px;animation:orbit 14s linear infinite}
.orb-ring--2{animation-duration:9s;animation-direction:reverse}
@keyframes orbit{to{transform:rotate(360deg)}}
.orb-core{animation:breathe 3.4s ease-in-out infinite}
@keyframes breathe{0%,100%{transform:scale(1)}50%{transform:scale(1.05)}}

/* layout overrides for nego coach dashboard compatibility */
.btn-ghost-w{background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.28)}
.btn-ghost-w:hover{background:rgba(255,255,255,.2)}
.btn-sm{padding:8px 14px;font-size:.78rem;min-height:38px}

.pill{display:inline-flex;align-items:center;gap:5px;font-size:.66rem;font-weight:700;padding:4px 11px;border-radius:20px;letter-spacing:.02em;white-space:nowrap}
.pill svg{width:11px;height:11px}
.pill--pending{background:var(--accent-light);color:var(--accent-dark)}
.pill--brand{background:var(--brand-light);color:var(--brand)}
.pill--success{background:var(--success-light);color:var(--success)}
.pill--muted{background:var(--bg);color:var(--muted)}

/* ═══ STATS ROW ═══ */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:clamp(10px,1.4vw,16px);margin-bottom:24px}
@media (max-width:1100px){.stats{grid-template-columns:repeat(2,1fr)}}
@media (max-width:540px){.stats{grid-template-columns:1fr}}
.stat{position:relative;background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:16px 17px 13px;overflow:hidden;transition:var(--transition)}
.stat:hover{box-shadow:var(--shadow);transform:translateY(-2px)}
.stat::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--st-bar,var(--brand))}
.stat-top{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:8px}
.stat-ic{width:36px;height:36px;border-radius:10px;background:var(--st-icbg,var(--brand-light));color:var(--st-ic,var(--brand));display:flex;align-items:center;justify-content:center}
.stat-ic svg{width:16px;height:16px}
.trend{display:inline-flex;align-items:center;gap:4px;font-size:.64rem;font-weight:700;padding:3px 9px;border-radius:20px}
.trend svg{width:11px;height:11px}
.trend--up{background:var(--success-light);color:var(--success)}
.trend--flat{background:var(--bg);color:var(--muted)}
.stat-num{font-family:'Sora',sans-serif;font-weight:800;font-size:1.5rem;color:var(--brand-deep);line-height:1.1}
.stat-num--empty{font-size:1.1rem;color:var(--muted);font-weight:700}
.stat-lbl{font-size:.7rem;font-weight:600;color:var(--muted);margin-top:2px}
.spark{width:100%;height:30px;margin-top:9px}
.spark rect { transition: fill 0.3s ease; }
.stat-edit{position:absolute;top:6px;right:6px;background:none;border:none;color:var(--muted);cursor:pointer;padding:12px;border-radius:6px;line-height:0;min-width:44px;min-height:44px;display:flex;align-items:center;justify-content:center}
.stat-edit:hover{color:var(--brand);background:var(--brand-light)}
.stat-edit svg{width:13px;height:13px}

/* ═══ STUDIO LAYOUT (config + sidebar) ═══ */
.nego-grid{display:grid;grid-template-columns:290px minmax(0,1fr);gap:clamp(14px,1.8vw,22px);align-items:start;margin-bottom:24px}
@media (max-width:1180px){.nego-grid{grid-template-columns:1fr}.rail{order:2}}
.rail{display:flex;flex-direction:column;gap:clamp(14px,1.8vw,20px);min-width:0}
.rail-tabs{display:flex;gap:4px;background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:4px}
.rail-tab{flex:1;border:none;background:none;font-family:'Inter',sans-serif;font-size:.7rem;font-weight:700;color:var(--muted);padding:8px 4px;border-radius:7px;cursor:pointer;transition:var(--transition);min-height:44px}
.rail-tab[aria-selected="true"]{background:#fff;color:var(--brand);box-shadow:0 1px 4px rgba(10,47,87,.1)}
.hist-item{display:flex;align-items:center;gap:11px;padding:11px 0;border-bottom:1px solid var(--border);width:100%;background:none;border-left:none;border-right:none;border-top:none;text-align:left;cursor:pointer;font-family:inherit}
.hist-item:last-child{border-bottom:none}
.hist-item:hover .hi-title{color:var(--brand)}
.hi-ic{width:38px;height:38px;border-radius:10px;background:var(--bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--brand);flex-shrink:0}
.hi-ic svg{width:15px;height:15px}
.hi-body{flex:1;min-width:0}
.hi-title{display:block;font-size:.78rem;font-weight:600;color:var(--brand-deep);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;transition:var(--transition)}
.hi-meta{font-style:normal;font-size:.66rem;color:var(--muted)}
.hi-score{font-family:'Sora',sans-serif;font-weight:800;font-size:.82rem;color:var(--brand);flex-shrink:0}
.fav-line{display:flex;gap:9px;align-items:flex-start;padding:10px 0;border-bottom:1px solid var(--border);font-size:.76rem;color:var(--text);line-height:1.55}
.fav-line:last-child{border-bottom:none}
.fav-line svg{width:13px;height:13px;color:var(--accent-dark);flex-shrink:0;margin-top:3px}

/* ═══ SIMULATOR (glass) ═══ */
.glass-card{position:relative;border-radius:var(--radius-lg);border:1px solid rgba(226,232,242,.9);overflow:hidden;
  background:linear-gradient(160deg,rgba(255,255,255,.9),rgba(245,249,254,.85));-webkit-backdrop-filter:blur(14px);backdrop-filter:blur(14px);box-shadow:var(--shadow)}
.glass-card::before{content:'';position:absolute;top:-70px;right:-70px;width:220px;height:220px;border-radius:50%;pointer-events:none;background:radial-gradient(circle,rgba(237,144,32,.1),transparent 70%)}
.sim-head{display:flex;align-items:flex-start;gap:13px;padding:20px 22px 0}
.sim-head-ic{width:44px;height:44px;border-radius:13px;flex-shrink:0;background:linear-gradient(135deg,var(--brand-deep),var(--brand));color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(8,97,169,.28)}
.sim-head-ic svg{width:20px;height:20px}
.sim-head h2{font-size:1.05rem;font-weight:800;color:var(--brand-deep);margin:0;}
.sim-head p{font-size:.78rem;color:var(--muted);margin-top:2px;margin-bottom:0;}
.sim-body{padding:18px 22px 22px;position:relative}
.phase{display:none}
.phase.active{display:block;animation:rise .3s ease}
@keyframes rise{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

/* stepper */
.stepper{display:flex;align-items:center;gap:0;margin:14px 22px 0;padding-bottom:4px}
.step{display:flex;align-items:center;gap:8px;font-size:.7rem;font-weight:700;color:var(--muted)}
.step-dot{width:26px;height:26px;border-radius:50%;border:2px solid var(--border);background:#fff;color:var(--muted);display:flex;align-items:center;justify-content:center;font-size:.66rem;font-family:'Sora',sans-serif;transition:var(--transition)}
.step[data-on="1"]{color:var(--brand)}
.step[data-on="1"] .step-dot{border-color:var(--brand);background:var(--brand);color:#fff}
.step[data-done="1"] .step-dot{border-color:var(--success);background:var(--success);color:#fff}
.step-bar{flex:1;height:2px;background:var(--border);margin:0 10px;min-width:20px}
.step-bar[data-on="1"]{background:var(--brand)}
@media (max-width:560px){.step span.stx{display:none}}

/* setup form */
.setup-grid{display:grid;grid-template-columns:1fr 1fr;gap:15px 16px}
@media (max-width:680px){.setup-grid{grid-template-columns:1fr}}
.span2{grid-column:1/-1}
.seg{display:flex;flex-wrap:wrap;gap:7px}
.seg input{position:absolute;opacity:0;pointer-events:none}
.seg label{display:inline-flex;align-items:center;gap:7px;padding:9px 15px;min-height:42px;border:1.5px solid var(--border);border-radius:9px;background:#fff;font-size:.78rem;font-weight:600;color:var(--muted);cursor:pointer;transition:var(--transition);-webkit-tap-highlight-color:transparent}
.seg label svg{width:14px;height:14px}
.seg input:checked + label{border-color:var(--brand);background:var(--brand-light);color:var(--brand)}
.seg input:focus-visible + label{outline:3px solid var(--accent);outline-offset:2px}
.cfg-set{border:none;margin:0;padding:0}

/* upload chips */
.up-row{display:flex;gap:10px;flex-wrap:wrap}
.up-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 15px;min-height:44px;border:1.5 dashed #b9cde0;border-radius:10px;background:#fbfdff;font-size:.76rem;font-weight:600;color:var(--brand-dark);cursor:pointer;transition:var(--transition);font-family:inherit}
.up-btn:hover{border-color:var(--brand);background:var(--brand-light)}
.up-btn svg{width:15px;height:15px;color:var(--brand)}
.up-btn[data-done="1"]{border-style:solid;border-color:#bfe3cc;background:var(--success-light);color:var(--success)}
.up-btn[data-done="1"] svg{color:var(--success)}

/* start CTA */
.btn-launch{position:relative;width:100%;min-height:58px;font-size:1rem;font-weight:700;font-family:'Sora',sans-serif;letter-spacing:.01em;border-radius:13px;overflow:hidden;background:linear-gradient(120deg,var(--accent) 0%,#f2a437 55%,var(--accent) 100%);color:var(--brand-deep);border:1.5px solid var(--accent-dark);box-shadow:0 8px 22px rgba(237,144,32,.35);transition:transform .18s ease,box-shadow .18s ease;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:8px}
.btn-launch:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(237,144,32,.45)}
.btn-launch:active{transform:translateY(0)}
.btn-launch svg{width:19px;height:19px}
.btn-launch::after{content:'';position:absolute;top:0;bottom:0;left:-70%;width:45%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.55),transparent);transform:skewX(-18deg);animation:shine 3.2s ease-in-out infinite}
@keyframes shine{0%,55%{left:-70%}85%,100%{left:130%}}
.btn-launch[data-loading="1"]{pointer-events:none;filter:saturate(.6)}
.btn-launch[data-loading="1"]::after{display:none}
.launch-note{display:flex;align-items:center;justify-content:center;gap:6px;font-size:.7rem;color:var(--muted);margin-top:9px;text-align:center}
.launch-note svg{width:12px;height:12px;color:var(--success);flex-shrink:0}

/* ═══ live conversation ═══ */
.convo-top{display:flex;align-items:center;gap:12px;padding:12px 14px;border:1px solid var(--border);border-radius:12px;background:#fff;margin-bottom:14px;flex-wrap:wrap}
.rec-ava{position:relative;width:46px;height:46px;border-radius:13px;flex-shrink:0;background:linear-gradient(135deg,var(--brand-deep),var(--brand));display:flex;align-items:center;justify-content:center;box-shadow:0 5px 14px rgba(8,97,169,.28);color:#fff}
.rec-ava svg{width:24px;height:24px}
.rec-ava .live{position:absolute;bottom:-3px;right:-3px;width:13px;height:13px;border-radius:50%;background:var(--success);border:2.5px solid #fff}
.convo-id b{display:block;font-family:'Sora',sans-serif;font-weight:800;font-size:.88rem;color:var(--brand-deep)}
.convo-id i{font-style:normal;font-size:.68rem;color:var(--muted)}
.convo-meta{margin-left:auto;display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.round-pill{font-size:.66rem;font-weight:700;padding:5px 12px;border-radius:20px;background:var(--brand-light);color:var(--brand)}
.chat{height:min(430px,56vh);overflow-y:auto;display:flex;flex-direction:column;gap:13px;padding:6px 4px 10px;scrollbar-width:thin}
.msg{display:flex;gap:10px;max-width:86%}
.msg-ava{width:34px;height:34px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:700;font-size:.7rem}
.msg--ai .msg-ava{background:linear-gradient(135deg,var(--brand-deep),var(--brand));color:#fff}
.msg--me{align-self:flex-end;flex-direction:row-reverse}
.msg--me .msg-ava{background:var(--accent-light);color:var(--accent-dark)}
.bubble{border-radius:13px;padding:11px 14px;font-size:.82rem;line-height:1.6;box-shadow:0 1px 4px rgba(10,47,87,.06)}
.msg--ai .bubble{background:#fff;border:1px solid var(--border);color:var(--text);border-top-left-radius:4px}
.msg--me .bubble{background:linear-gradient(135deg,var(--brand),var(--brand-dark));color:#fff;border-top-right-radius:4px}
.msg time{display:block;font-size:.6rem;color:var(--muted);margin-top:5px}
.msg--me time{color:rgba(255,255,255,.6)}
.typing .bubble{display:inline-flex;gap:5px;align-items:center;padding:14px 16px}
.typing .bubble i{width:7px;height:7px;border-radius:50%;background:#a8bdd2;animation:tp 1.2s infinite}
.typing .bubble i:nth-child(2){animation-delay:.18s}.typing .bubble i:nth-child(3){animation-delay:.36s}
@keyframes tp{0%,60%,100%{transform:translateY(0);opacity:.5}30%{transform:translateY(-5px);opacity:1}}

/* coach nudges */
.nudge{align-self:center;display:inline-flex;gap:8px;align-items:center;font-size:.68rem;font-weight:600;color:var(--brand-dark);background:var(--brand-light);border:1px solid #cfe2f2;border-radius:20px;padding:6px 14px;max-width:92%}
.nudge svg{width:12px;height:12px;color:var(--accent-dark);flex-shrink:0}

/* suggestion chips + composer */
.sugs{display:flex;gap:7px;flex-wrap:wrap;margin:10px 0 10px}
.sug{display:inline-flex;align-items:center;gap:6px;padding:8px 13px;min-height:44px;border-radius:20px;border:1.5px solid var(--border);background:#fff;font-size:.72rem;font-weight:600;color:var(--brand-deep);cursor:pointer;transition:.15s ease;font-family:inherit}
.sug:hover{border-color:var(--brand);background:var(--brand-light);color:var(--brand)}
.sug svg{width:12px;height:12px;color:var(--accent-dark)}
.composer{display:flex;gap:9px;align-items:flex-end}
.composer textarea{flex:1;min-height:52px;max-height:130px;resize:none;padding:13px 15px;border:1.5px solid var(--border);border-radius:12px;font-family:'Inter',sans-serif;font-size:16px;line-height:1.5;background:#fff;color:var(--text);transition:var(--transition)}
.composer textarea:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 3px rgba(8,97,169,.12)}
.send-btn{width:52px;height:52px;border-radius:12px;border:none;background:linear-gradient(135deg,var(--brand),var(--brand-dark));color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:var(--transition);flex-shrink:0}
.send-btn:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(8,97,169,.35)}
.send-btn svg{width:19px;height:19px}
.send-btn:disabled{opacity:.5;pointer-events:none}
.convo-actions{display:flex;gap:9px;margin-top:12px;flex-wrap:wrap}

/* ═══ BOARDROOM INTELLIGENCE UPGRADE ═══ */
.boardroom-grid{display:grid;grid-template-columns:minmax(0,1fr) 250px;gap:16px;align-items:start}
@media (max-width:900px){.boardroom-grid{grid-template-columns:1fr}}
.transcript-col{min-width:0}
.transcript-eyebrow{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
.transcript-eyebrow span:first-child{font-size:.62rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
.transcript-eyebrow span:last-child{font-size:.66rem;font-weight:700;color:var(--brand);font-family:'Sora',sans-serif}
.intel-col{display:flex;flex-direction:column;gap:12px;min-width:0}
.intel-card{border:1px solid var(--border);border-radius:12px;background:#fff;padding:13px 14px}
.intel-card h4{display:flex;align-items:center;gap:7px;font-size:.66rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:11px}
.intel-card h4 svg{width:12px;height:12px;color:var(--brand);flex-shrink:0}
.live-meter{margin-bottom:11px}
.live-meter:last-child{margin-bottom:0}
.live-meter .lm-top{display:flex;justify-content:space-between;font-size:.7rem;font-weight:600;color:var(--text);margin-bottom:5px}
.live-meter .lm-top b{font-family:'Sora',sans-serif;font-weight:800;color:var(--brand-deep)}
.lm-track{height:7px;border-radius:20px;background:var(--bg);overflow:hidden}
.lm-fill{height:100%;border-radius:20px;background:linear-gradient(90deg,var(--brand-dark),var(--brand));width:0%;transition:width .5s ease}
.lm-fill--pers{background:linear-gradient(90deg,var(--accent-dark),var(--accent))}
.style-badge{display:inline-flex;align-items:center;gap:6px;font-size:.64rem;font-weight:800;letter-spacing:.03em;padding:5px 12px;border-radius:20px;transition:transform .2s ease}
.style-badge svg{width:11px;height:11px}
.style--friendly{background:var(--success-light);color:var(--success)}
.style--difficult{background:var(--accent-light);color:var(--accent-dark)}
.style--aggressive{background:var(--danger-light);color:var(--danger)}
.style--executive{background:var(--brand-light);color:var(--brand-deep)}
.style-badge.flash{animation:badgeflash .6s ease}
@keyframes badgeflash{0%{transform:scale(1)}35%{transform:scale(1.16)}100%{transform:scale(1)}}
.tactic-feed{display:flex;flex-direction:column;gap:7px;max-height:180px;overflow-y:auto;scrollbar-width:thin}
.tactic-row{display:flex;align-items:center;gap:8px}
.tactic-tag{display:inline-flex;align-items:center;font-size:.62rem;font-weight:700;padding:4px 10px;border-radius:20px;white-space:nowrap}
.tactic-tag--ai{background:var(--brand-light);color:var(--brand)}
.tactic-tag--you{background:var(--accent-light);color:var(--accent-dark)}
.tactic-tag--warn{background:var(--danger-light);color:var(--danger)}
.tactic-row time{margin-left:auto;font-size:.6rem;color:#8fa0b8;flex-shrink:0}
.cc-empty{font-size:.7rem;color:var(--muted);font-style:italic;line-height:1.5}
.cc-list{display:flex;flex-direction:column;gap:10px}
.cc-item{display:flex;gap:8px;font-size:.72rem;color:var(--text);line-height:1.5}
.cc-item svg{width:13px;height:13px;flex-shrink:0;margin-top:2px}
.cc-item.you svg{color:var(--accent-dark)}
.cc-item.them svg{color:var(--brand)}
.preview-badge{display:inline-flex;align-items:center;gap:5px;font-size:.58rem;font-weight:800;letter-spacing:.04em;text-transform:uppercase;padding:3px 9px;border-radius:20px;background:var(--bg);color:var(--muted);margin-left:auto}
.tone-grid{display:flex;flex-direction:column;gap:9px}
.tone-row .t-top{display:flex;justify-content:space-between;font-size:.7rem;font-weight:600;color:var(--text);margin-bottom:4px}
.tone-row .t-top i{font-style:normal;color:var(--brand-deep);font-weight:700}
.t-track{height:5px;border-radius:20px;background:var(--bg);overflow:hidden}
.t-fill{height:100%;border-radius:20px;background:linear-gradient(90deg,#94a8bd,#6f89a5);transition:width .5s ease}
.conf-badge{display:inline-flex;align-items:center;gap:5px;font-size:.6rem;font-weight:700;padding:4px 10px;border-radius:20px;background:var(--success-light);color:var(--success)}
.msg--interrupt .bubble{border-left:3px solid var(--danger);background:#fff7f5}
.interrupt-tag{display:flex;align-items:center;gap:5px;font-size:.6rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:var(--danger);margin-bottom:5px}
.sys-notice{align-self:center;display:inline-flex;gap:8px;align-items:center;font-size:.68rem;font-weight:700;color:var(--brand-deep);background:var(--bg);border:1px dashed var(--border);border-radius:20px;padding:7px 16px;max-width:94%;text-align:center}
.sys-notice svg{width:12px;height:12px;flex-shrink:0;color:var(--accent-dark)}

/* ═══ PREMIUM REPORT ═══ */
.score-hero{display:grid;grid-template-columns:auto minmax(0,1fr);gap:18px;align-items:center;padding:16px;border:1px solid var(--border);border-radius:13px;background:#fff;margin-bottom:16px}
@media (max-width:560px){.score-hero{grid-template-columns:1fr;text-align:center;justify-items:center}}
.score-ring{position:relative;width:96px;height:96px;flex-shrink:0}
.score-ring svg{width:96px;height:96px;transform:rotate(-90deg)}
.score-ring .track{fill:none;stroke:var(--bg);stroke-width:9}
.score-ring .prog{fill:none;stroke:var(--brand);stroke-width:9;stroke-linecap:round;stroke-dasharray:264;stroke-dashoffset:264;transition:stroke-dashoffset 1s ease .2s}
.score-ring .num{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:1.5rem;color:var(--brand-deep);line-height:1}
.score-ring .num i{font-style:normal;font-size:.56rem;font-weight:700;color:var(--muted);letter-spacing:.06em}
.score-copy h3{font-size:1rem;font-weight:800;color:var(--brand-deep);margin:0;}
.score-copy p{font-size:.78rem;color:var(--muted);line-height:1.6;margin-top:3px;margin-bottom:0;}
.band-strip{display:flex;gap:5px;margin-top:10px;max-width:320px}
.band-strip b{flex:1;height:8px;border-radius:4px;background:var(--border)}
.band-strip b.on{background:var(--accent)}
.band-lbl{font-size:.66rem;font-weight:700;color:var(--accent-dark);margin-top:5px;display:block}

.skillbars{display:grid;grid-template-columns:1fr 1fr;gap:11px 22px}
@media (max-width:640px){.skillbars{grid-template-columns:1fr}}
.sb-row{min-width:0}
.sb-row .top{display:flex;justify-content:space-between;font-size:.72rem;font-weight:600;color:var(--brand-deep);margin-bottom:5px}
.sb-row .top b{font-family:'Sora',sans-serif;color:var(--brand)}
.sb-track{height:8px;border-radius:20px;background:var(--bg);overflow:hidden}
.sb-fill{height:100%;border-radius:20px;background:linear-gradient(90deg,var(--brand-dark),var(--brand));width:0;transition:width .9s ease .25s}
.sb-fill--warn{background:linear-gradient(90deg,var(--accent-dark),var(--accent))}

.ba{border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:12px}
.ba:last-child{margin-bottom:0}
.ba .said,.ba .better{padding:12px 15px;font-size:.78rem;line-height:1.6}
.ba .said{background:#fdf7f7;border-bottom:1px solid var(--border);color:var(--text)}
.ba .better{background:#f4faf6;color:var(--text)}
.ba b{display:flex;align-items:center;gap:6px;font-size:.64rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin-bottom:5px}
.ba .said b{color:var(--danger)}
.ba .better b{color:var(--success)}
.ba b svg{width:12px;height:12px}

.counter{border:1px dashed #cfe2f2;background:linear-gradient(135deg,#f4f9fe,#fdf8f0);border-radius:13px;padding:16px 18px;display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:4px}
@media (max-width:640px){.counter{grid-template-columns:1fr}}
.co-cell{background:#fff;border:1px solid var(--border);border-radius:11px;padding:12px 14px}
.co-cell i{font-style:normal;font-size:.62rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);display:block}
.co-cell b{font-family:'Sora',sans-serif;font-weight:800;font-size:1.08rem;color:var(--brand-deep)}
.co-cell small{display:block;font-size:.64rem;color:var(--muted);margin-top:2px}

.ach-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:9px}
@media (max-width:560px){.ach-grid{grid-template-columns:repeat(2,1fr)}}
.ach{aspect-ratio:1;border:1.5px solid var(--border);border-radius:12px;background:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;padding:6px;transition:var(--transition);cursor:default;text-align:center}
.ach:hover{transform:translateY(-2px);box-shadow:var(--shadow)}
.ach svg{width:20px;height:20px}
.ach span{font-size:.56rem;font-weight:700;color:var(--muted);line-height:1.25}
.ach--won{border-color:#f3d9ae;background:linear-gradient(160deg,#fdf6ea,#fff)}
.ach--won svg{color:var(--accent-dark)}
.ach--won span{color:var(--accent-dark)}
.ach--locked{opacity:.5}
.ach--locked svg{color:var(--muted)}

.rep-sec{margin-top:18px}
.rep-sec > h3{display:flex;align-items:center;gap:8px;font-size:.82rem;font-weight:800;color:var(--brand-deep);margin-bottom:11px;margin-top:0;}
.rep-sec > h3 svg{width:15px;height:15px;color:var(--brand)}
.rep-lists{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media (max-width:560px){.rep-lists{grid-template-columns:1fr}}
.rep-list h4{display:flex;align-items:center;gap:7px;font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;margin-bottom:9px;margin-top:0;}
.rep-list h4 svg{width:14px;height:14px}
.rep-list--str h4{color:var(--success)}
.rep-list--wk h4{color:var(--accent-dark)}
.rep-list ul{list-style:none;display:flex;flex-direction:column;gap:7px;padding-left:0;}
.rep-list li{display:flex;gap:8px;font-size:.76rem;color:var(--text);line-height:1.5}
.rep-list li::before{content:'';width:5px;height:5px;border-radius:50%;flex-shrink:0;margin-top:8px}
.rep-list--str li::before{background:var(--success)}
.rep-list--wk li::before{background:var(--accent)}
.rec-notes{border:1px solid var(--border);border-left:3.5px solid var(--brand);border-radius:10px;background:var(--bg);padding:13px 15px}
.rec-notes b{display:flex;align-items:center;gap:7px;font-size:.74rem;font-weight:700;color:var(--brand-deep);margin-bottom:5px}
.rec-notes b svg{width:13px;height:13px;color:var(--brand)}
.rec-notes p{font-size:.76rem;color:var(--text);line-height:1.6;margin-bottom:0;}

.trend-chart{width:100%;height:auto}
.trend-chart .grid-l{stroke:var(--border);stroke-width:1}
.trend-chart .line-conf{fill:none;stroke:var(--brand);stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
.trend-chart .line-pers{fill:none;stroke:var(--accent);stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:5 5}
.trend-chart text{font-family:'Inter',sans-serif;font-size:9.5px;font-weight:600;fill:var(--muted)}
.legend{display:flex;gap:16px;font-size:.68rem;font-weight:600;color:var(--muted);margin-top:8px;flex-wrap:wrap}
.legend i{display:inline-flex;align-items:center;gap:6px;font-style:normal}
.legend i::before{content:'';width:14px;height:3px;border-radius:2px;background:var(--brand)}
.legend i.pers::before{background:var(--accent)}
.rep-actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:20px}

.plan-list{display:flex;flex-direction:column;gap:10px}
.plan-item{display:flex;gap:12px;padding:13px 15px;border:1px solid var(--border);border-radius:11px;background:#fff}
.plan-num{width:27px;height:27px;border-radius:8px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:.78rem;flex-shrink:0}
.plan-item b{display:block;font-size:.8rem;font-weight:700;color:var(--brand-deep)}
.plan-item p{font-size:.75rem;color:var(--muted);line-height:1.55;margin-top:3px;margin-bottom:0;}
.plan-item a{font-size:.71rem;font-weight:700;color:var(--brand);display:inline-flex;align-items:center;gap:4px;margin-top:7px}
.plan-item a:hover{text-decoration:underline}

.spin{width:16px;height:16px;border:2.5px solid var(--brand-light);border-top-color:var(--brand);border-radius:50%;animation:spin .8s linear infinite;flex-shrink:0}
.report-loading{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;padding:60px 20px;text-align:center;animation:rise .3s ease}
.report-loading .spin{width:26px;height:26px;border-width:3px}
.report-loading b{font-family:'Sora',sans-serif;font-weight:700;font-size:.92rem;color:var(--brand-deep)}
.report-loading p{font-size:.78rem;color:var(--muted);max-width:320px;line-height:1.6;margin-bottom:0;}
.ai-micro{display:inline-block;font-size:.7rem;color:var(--muted);font-style:italic;margin-bottom:2px}

.notice{display:flex;gap:9px;align-items:flex-start;font-size:.78rem;border-radius:10px;padding:12px 14px;border:1px solid;margin-top:20px}
.notice svg{width:15px;height:15px;flex-shrink:0;margin-top:2px}
.notice--info{background:var(--brand-light);border-color:#cfe2f2;color:var(--brand-dark)}

.mobile-cta{position:fixed;left:0;right:0;bottom:0;z-index:950;display:none;gap:10px;padding:10px clamp(14px,4vw,18px);padding-bottom:max(10px,env(safe-area-inset-bottom,0));background:rgba(255,255,255,.94);-webkit-backdrop-filter:saturate(180%) blur(12px);backdrop-filter:saturate(180%) blur(12px);border-top:1px solid var(--border);transition:transform .28s ease}
.mobile-cta.hidden{transform:translateY(110%)}
.mobile-cta .btn{flex:1}
@media (max-width:1024px){.mobile-cta{display:flex}}

.toast{position:fixed;bottom:24px;left:50%;transform:translate(-50%,20px);z-index:1400;display:flex;align-items:center;gap:10px;background:var(--brand-deep);color:#fff;font-size:.82rem;font-weight:600;padding:13px 20px;border-radius:12px;box-shadow:var(--shadow-lg);opacity:0;visibility:hidden;transition:opacity .25s ease,transform .25s ease,visibility .25s;max-width:min(420px,calc(100vw - 32px))}
.toast.show{opacity:1;visibility:visible;transform:translate(-50%,0)}
.toast svg{width:17px;height:17px;color:var(--accent);flex-shrink:0}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
      <defs>
        <symbol id="i-briefcase" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18"/></symbol>
        <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
        <symbol id="i-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></symbol>
        <symbol id="i-share" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></symbol>
        <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.9 1.9 0 0 0 3.4 0"/></symbol>
        <symbol id="i-cog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h0a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h0a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></symbol>
        <symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21 12 16 5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></symbol>
        <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6"/><path d="M8.2 13.9 7 22l5-3 5 3-1.2-8.1"/></symbol>
        <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6M21 20a5.5 5.5 0 0 0-4-5.3"/></symbol>
        <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></symbol>
        <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
        <symbol id="i-arrow-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
        <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.4 1 2.3h6c0-.9.4-1.8 1-2.3A7 7 0 0 0 12 2Z"/></symbol>
        <symbol id="i-check-c" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
        <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m4.5 12.5 5 5 10-11"/></symbol>
        <symbol id="i-play" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4.5v15l13-7.5Z"/></symbol>
        <symbol id="i-star" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.9L12 17.8 5.8 21l1.2-6.9-5-4.9 6.9-1Z"/></symbol>
        <symbol id="i-refresh" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.6-6.4M21 3v6h-6"/></symbol>
        <symbol id="i-zap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h8l-1 8 11-13h-8Z"/></symbol>
        <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-3 8-10V5l-8-3-8 3v7c0 7 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></symbol>
        <symbol id="i-target" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.2"/></symbol>
        <symbol id="i-trend-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m3 17 6-6 4 4 8-8"/><path d="M15 7h6v6"/></symbol>
        <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
        <symbol id="i-naira" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 21V3l12 18V3M3.5 9.5h17M3.5 14.5h17"/></symbol>
        <symbol id="i-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M12 12v4"/></symbol>
        <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/></symbol>
        <symbol id="i-sliders" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/></symbol>
        <symbol id="i-flame" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4.4 0 7-2.8 7-6.7 0-3.3-2.2-5.6-3.8-7.3C13.9 6.6 13 5 13 2c-3 2-5 5-5 8-.9-.7-1.6-1.6-2-3-1.3 1.6-2 3.7-2 5.6C4 18.5 7.2 22 12 22Z"/></symbol>
        <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18M8 15v3M13 11v7M18 7v11"/></symbol>
        <symbol id="i-crown" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 8 4.5 3L12 4l5.5 7L22 8l-2 12H4L2 8Z"/></symbol>
      </defs>
    </svg>

    <!-- ═══ 1 · HERO ═══ -->
    <section class="nego-hero" aria-labelledby="nego-title">
      <div class="hero-grid">
        <div>
          <div class="hero-badges">
            <span class="hb hb--premium"><svg aria-hidden="true"><use href="#i-crown"/></svg> Premium</span>
            <span class="hb hb--live"><span class="pulse" aria-hidden="true"></span> AI Recruiter · Online</span>
          </div>
          <p style="font-size:.82rem;font-weight:600;color:rgba(255,255,255,.92);margin-bottom:6px">
            Welcome back<?= isset($candidate['firstName']) ? ', ' . esc($candidate['firstName']) : '' ?> 👋 <span style="opacity:.85;font-weight:500">· Your persuasion score rose <b style="color:#7ce29b">+9 pts</b> across your last 3 negotiations</span>
          </p>
          <h1 id="nego-title">Salary Negotiation <span>Coach</span></h1>
          <p class="hero-sub">Practice the deal before it's real. Negotiate against an AI recruiter that pushes back like the real thing — then get a full report on how you performed.</p>
          <div class="hero-chips">
            <div class="hchip">
              <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-flame"/></svg> Practice Streak</span>
              <div class="hchip-val">2 days <small>· best 4</small></div>
            </div>
            <div class="hchip">
              <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-zap"/></svg> Career XP</span>
              <div class="hchip-val">1,240 XP <small>· 260 to Level 5</small></div>
            </div>
            <div class="hchip">
              <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-target"/></svg> This Week's Goal</span>
              <div class="hchip-val">1 of 2 <small>negotiations done</small></div>
              <div class="goal-track"><div class="goal-fill" style="width:50%"></div></div>
            </div>
          </div>
        </div>
        <div class="hero-orb" role="img" aria-label="Negotiation coach illustration">
          <svg viewBox="0 0 200 200" fill="none" aria-hidden="true">
            <circle class="orb-ring" cx="100" cy="100" r="86" stroke="rgba(255,255,255,.18)" stroke-width="1.5" stroke-dasharray="6 10"/>
            <circle class="orb-ring orb-ring--2" cx="100" cy="100" r="66" stroke="rgba(237,144,32,.5)" stroke-width="1.5" stroke-dasharray="2 12"/>
            <circle class="orb-core" cx="100" cy="100" r="46" fill="rgba(255,255,255,.08)" stroke="rgba(255,255,255,.28)" stroke-width="1.5"/>
            <g class="orb-ring"><circle cx="100" cy="14" r="5" fill="#ED9020"/></g>
            <g class="orb-ring orb-ring--2"><circle cx="100" cy="34" r="3.5" fill="#fff" opacity=".7"/></g>
            <g stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
              <path d="M84 104V86l16 18V86M76 92.5h14M76 98.5h14" fill="none"/>
              <path d="M108 112c3 4 8 6 12 3" fill="none" opacity=".85"/>
            </g>
          </svg>
        </div>
      </div>
    </section>

    <!-- ═══ 2 · TOP STATS ═══ -->
    <section class="stats" aria-label="Your salary position at a glance">
      <svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
        <linearGradient id="sparkfill" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stop-color="#0861A9" stop-opacity=".25"/><stop offset="100%" stop-color="#0861A9" stop-opacity="0"/>
        </linearGradient>
      </defs></svg>
      <div class="stat">
        <button type="button" class="stat-edit" id="edit-offer" aria-label="Add your current offer"><svg aria-hidden="true"><use href="#i-cog"/></svg></button>
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-naira"/></svg></span></div>
        <div class="stat-num stat-num--empty" id="st-offer">Not set yet</div><div class="stat-lbl" id="st-offer-lbl">Current Offer · add it in the form below</div>
      </div>
      <div class="stat" style="--st-bar:var(--brand-dark)">
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-chart"/></svg></span>
          <span class="trend trend--up"><svg aria-hidden="true"><use href="#i-trend-up"/></svg> +6% YoY</span></div>
        <div class="stat-num stat-num--empty" id="mm-value">Not set yet</div><div class="stat-lbl" id="mm-label">Market Median · add a job title below</div>
      </div>
      <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
        <button type="button" class="stat-edit" id="edit-target" aria-label="Add your target salary"><svg aria-hidden="true"><use href="#i-cog"/></svg></button>
        <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-target"/></svg></span></div>
        <div class="stat-num stat-num--empty" id="st-target">Not set yet</div><div class="stat-lbl" id="st-target-lbl">Your Target · add it in the form below</div>
      </div>
      <div class="stat" style="--st-bar:var(--success)" id="stat-readiness">
        <div class="stat-top"><span class="stat-ic" style="background:var(--success-light);color:var(--success)"><svg aria-hidden="true"><use href="#i-shield"/></svg></span>
          <span class="pill pill--muted" id="readiness-pill">New here</span></div>
        <div class="stat-num" style="font-size:1.15rem;margin-top:6px" id="readiness-text">Practice Your First Negotiation</div><div class="stat-lbl" id="readiness-lbl">Negotiation Readiness · appears after your first scored session</div>
        <svg class="spark" viewBox="0 0 120 30" preserveAspectRatio="none" role="img" aria-label="Readiness band progress" id="readiness-spark">
          <rect x="2" y="12" width="36" height="8" rx="4" fill="#e2e8f2" id="spark-1"/>
          <rect x="42" y="12" width="36" height="8" rx="4" fill="#e2e8f2" id="spark-2"/>
          <rect x="82" y="12" width="36" height="8" rx="4" fill="#e2e8f2" id="spark-3"/>
        </svg>
      </div>
    </section>

    <!-- ═══ 3 · HISTORY RAIL + SIMULATOR ═══ -->
    <div class="nego-grid">
      <!-- Rail -->
      <aside class="rail" aria-label="Your negotiation history">
        <section class="card">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-clock"/></svg> Your Negotiations</span>
            <span class="pill pill--muted">Sample data</span></div>
          <div class="card-body">
            <p class="hint" style="margin-bottom:10px">Illustrative examples of what your history will look like — practice a session below to start building your own.</p>
            <div class="rail-tabs" role="tablist" aria-label="History filters">
              <button class="rail-tab" role="tab" id="tab-hist" aria-selected="true" aria-controls="pane-hist">History</button>
              <button class="rail-tab" role="tab" id="tab-saved" aria-selected="false" aria-controls="pane-saved">Saved</button>
              <button class="rail-tab" role="tab" id="tab-fav" aria-selected="false" aria-controls="pane-fav">Favorites</button>
            </div>
            <div id="pane-hist" role="tabpanel" aria-labelledby="tab-hist" style="margin-top:6px">
              <button type="button" class="hist-item" data-load="pm-corp">
                <span class="hi-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span>
                <span class="hi-body"><span class="hi-title">Product Manager · Corporate HR</span><i class="hi-meta">Jul 12 · Hard · 6 rounds</i></span>
                <span class="hi-score">74</span>
              </button>
              <button type="button" class="hist-item" data-load="pm-startup">
                <span class="hi-ic"><svg aria-hidden="true"><use href="#i-zap"/></svg></span>
                <span class="hi-body"><span class="hi-title">Product Manager · Startup Founder</span><i class="hi-meta">Jul 9 · Medium · 5 rounds</i></span>
                <span class="hi-score">68</span>
              </button>
              <button type="button" class="hist-item" data-load="ba-bank">
                <span class="hi-ic"><svg aria-hidden="true"><use href="#i-naira"/></svg></span>
                <span class="hi-body"><span class="hi-title">Business Analyst · Banking</span><i class="hi-meta">Jul 4 · Medium · 7 rounds</i></span>
                <span class="hi-score">61</span>
              </button>
            </div>
            <div id="pane-saved" role="tabpanel" aria-labelledby="tab-saved" hidden style="margin-top:6px">
              <button type="button" class="hist-item" data-load="saved-1">
                <span class="hi-ic"><svg aria-hidden="true"><use href="#i-bookmark"/></svg></span>
                <span class="hi-body"><span class="hi-title">Multinational · Expert difficulty</span><i class="hi-meta">Saved setup · resume any time</i></span>
              </button>
              <button type="button" class="hist-item" data-load="saved-2">
                <span class="hi-ic"><svg aria-hidden="true"><use href="#i-bookmark"/></svg></span>
                <span class="hi-body"><span class="hi-title">Government panel · benefits focus</span><i class="hi-meta">Saved setup · resume any time</i></span>
              </button>
            </div>
            <div id="pane-fav" role="tabpanel" aria-labelledby="tab-fav" hidden style="margin-top:6px">
              <p class="fav-line"><svg aria-hidden="true"><use href="#i-star"/></svg> &#8220;Based on the results I delivered in my current role — a 30% reduction in processing time — I believe &#8358;550,000 reflects the value I bring.&#8221;</p>
              <p class="fav-line"><svg aria-hidden="true"><use href="#i-star"/></svg> &#8220;I&#8217;m flexible on the structure. If base is fixed, could we discuss a performance bonus or an earlier review date?&#8221;</p>
              <p class="fav-line"><svg aria-hidden="true"><use href="#i-star"/></svg> &#8220;Thank you for the offer — I&#8217;m excited about the role. Before I accept, I\u2019d like to discuss the compensation.&#8221;</p>
            </div>
          </div>
        </section>

        <!-- Benchmark -->
        <section class="card">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-chart"/></svg> Salary Benchmark</span></div>
          <div class="card-body">
            <p style="font-size:.76rem;color:var(--text);line-height:1.65" id="bench-role-line">Add a job title in the form to see role-specific benchmarks.</p>
            <div style="display:flex;justify-content:space-between;font-size:.7rem;font-weight:700;color:var(--muted);margin-top:10px"><span id="bench-low">&#8358;—</span><span id="bench-median">Median &#8358;—</span><span id="bench-high">&#8358;—</span></div>
            <div style="position:relative;height:10px;border-radius:20px;background:linear-gradient(90deg,#e2e8f2,#cfe2f2,var(--brand-light));margin-top:6px">
              <span style="position:absolute;top:-4px;width:3px;height:18px;border-radius:2px;background:var(--danger);display:none" id="bench-offer-marker" title="Your current offer"></span>
              <span style="position:absolute;top:-4px;width:3px;height:18px;border-radius:2px;background:var(--accent-dark);display:none" id="bench-target-marker" title="Your target"></span>
            </div>
            <p class="hint" style="margin-top:10px" id="bench-source-line">Add a job title to see the benchmark range.</p>
          </div>
        </section>
      </aside>

      <!-- The Simulator Container -->
      <section class="glass-card" id="simulator" aria-labelledby="sim-title">
        <div class="sim-head">
          <span class="sim-head-ic" aria-hidden="true"><svg><use href="#i-users"/></svg></span>
          <div><h2 id="sim-title">Negotiation Room</h2>
          <p>Set the scenario, negotiate live, get your report. The recruiter adapts to your style choices.</p></div>
        </div>
        <div class="stepper" aria-hidden="true">
          <div class="step" id="stp-1" data-on="1"><span class="step-dot">1</span><span class="stx">Set Up</span></div>
          <div class="step-bar" id="bar-1"></div>
          <div class="step" id="stp-2"><span class="step-dot">2</span><span class="stx">Negotiate</span></div>
          <div class="step-bar" id="bar-2"></div>
          <div class="step" id="stp-3"><span class="step-dot">3</span><span class="stx">Report</span></div>
        </div>
        <div class="sim-body">

          <!-- ── PHASE 1 · SETUP ── -->
          <form class="phase active" id="phase-setup" novalidate>
            <div class="setup-grid">
              <div>
                <label class="lbl" for="n-job">Job Title on the Offer</label>
                <div class="field-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg>
                  <input class="input" id="n-job" type="text" placeholder="e.g. Product Manager" autocomplete="organization-title"></div>
                <p class="hint" id="profile-prefill-note" hidden><svg aria-hidden="true"><use href="#i-user"/></svg> From your profile &mdash; edit if this offer is for a different title.</p>
              </div>
              <div>
                <label class="lbl" for="n-offer">Base Salary Offered <span class="lbl-opt">(&#8358; monthly)</span></label>
                <div class="field-ic"><svg aria-hidden="true"><use href="#i-naira"/></svg>
                  <input class="input" id="n-offer" type="text" inputmode="numeric" placeholder="e.g. 450,000"></div>
              </div>
              <div>
                <label class="lbl" for="n-target">Your Target <span class="lbl-opt">(&#8358; monthly)</span></label>
                <div class="field-ic"><svg aria-hidden="true"><use href="#i-target"/></svg>
                  <input class="input" id="n-target" type="text" inputmode="numeric" placeholder="e.g. 550,000"></div>
                <p class="hint" id="target-hint">Add your offer and target to see how your ask compares.</p>
              </div>
              <div>
                <label class="lbl" for="n-benefits">Benefits on the Table <span class="lbl-opt">(optional)</span></label>
                <input class="input" id="n-benefits" type="text" placeholder="e.g. HMO, pension, remote days">
              </div>

              <fieldset class="cfg-set span2">
                <legend class="lbl">Recruiter Style <span class="lbl-opt">(changes how hard they push back)</span></legend>
                <div class="seg" role="radiogroup" aria-label="Recruiter style">
                  <input type="radio" name="nstyle" id="ns-friendly" value="friendly">
                  <label for="ns-friendly"><svg aria-hidden="true"><use href="#i-users"/></svg> Friendly</label>
                  <input type="radio" name="nstyle" id="ns-corporate" value="corporate" checked>
                  <label for="ns-corporate"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Corporate</label>
                  <input type="radio" name="nstyle" id="ns-strict" value="strict">
                  <label for="ns-strict"><svg aria-hidden="true"><use href="#i-shield"/></svg> Strict</label>
                </div>
              </fieldset>

              <div>
                <label class="lbl" for="n-org">Organisation Type</label>
                <select class="select" id="n-org">
                  <option value="startup">Startup</option>
                  <option value="corporate" selected>Corporate</option>
                  <option value="gov">Government / Public Sector</option>
                  <option value="multi">Multinational</option>
                </select>
              </div>
              <div>
                <label class="lbl" for="n-diff">Difficulty</label>
                <select class="select" id="n-diff">
                  <option value="easy">Easy — recruiter concedes readily</option>
                  <option value="medium" selected>Medium — realistic pushback</option>
                  <option value="hard">Hard — tight budget, firm lines</option>
                  <option value="expert">Expert — trained negotiator</option>
                </select>
              </div>

              <div class="span2">
                <label class="lbl">Give the AI More Context <span class="lbl-opt">(optional — sharpens the recruiter&#8217;s pushback)</span></label>
                <div class="up-row">
                  <button type="button" class="up-btn" id="up-offer"><svg aria-hidden="true"><use href="#i-doc"/></svg> <span>Upload Offer Letter</span></button>
                  <button type="button" class="up-btn" id="up-jd"><svg aria-hidden="true"><use href="#i-doc"/></svg> <span>Upload Job Description</span></button>
                  <input type="file" id="file-offer" accept=".pdf,.doc,.docx" hidden>
                  <input type="file" id="file-jd" accept=".pdf,.doc,.docx" hidden>
                </div>
              </div>

              <div class="span2" style="margin-top:4px">
                <button type="submit" class="btn-launch" id="start-btn"><svg aria-hidden="true"><use href="#i-play"/></svg> <span id="start-txt">Start Negotiation</span></button>
                <p class="launch-note"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Private practice — nothing here is shared with employers.</p>
              </div>
            </div>
          </form>

          <!-- ── PHASE 2 · LIVE NEGOTIATION ── -->
          <div class="phase" id="phase-live">
            <div class="convo-top">
              <span class="rec-ava"><svg aria-hidden="true"><use href="#i-user"/></svg><span class="live" aria-hidden="true"></span></span>
              <div class="convo-id"><b id="live-name">Chioma Nwachukwu</b><i id="live-role">HR Business Partner · Corporate</i></div>
              <div class="convo-meta">
                <span class="style-badge style--difficult" id="style-badge"><svg aria-hidden="true"><use href="#i-shield"/></svg> <span id="style-label">Corporate</span></span>
                <span class="round-pill" id="round-pill">Round 1 of 5</span>
              </div>
            </div>

            <div class="boardroom-grid">
              <div class="transcript-col">
                <div class="transcript-eyebrow"><span>Live Transcript</span><span id="clock-live">00:00</span></div>
                <div class="chat" id="chat" role="log" aria-live="polite" aria-label="Negotiation conversation"></div>
                <div class="sugs" id="sugs" aria-label="Suggested tactics"></div>
                <div class="composer">
                  <label for="composer-input" class="sr-only" style="position:absolute;left:-9999px">Your reply to the recruiter</label>
                  <textarea id="composer-input" placeholder="Type your response — quote figures, justify with results…" rows="2"></textarea>
                  <button type="button" class="send-btn" id="send-btn" aria-label="Send reply"><svg aria-hidden="true"><use href="#i-arrow-r"/></svg></button>
                </div>
                <div class="convo-actions">
                  <button type="button" class="btn btn-outline btn-sm" id="end-btn"><svg aria-hidden="true"><use href="#i-check-c"/></svg> End &amp; Get Report</button>
                  <button type="button" class="btn btn-outline btn-sm" id="restart-btn"><svg aria-hidden="true"><use href="#i-refresh"/></svg> Restart Scenario</button>
                </div>
              </div>

              <!-- Live Intelligence Panel -->
              <aside class="intel-col" aria-label="Live negotiation intelligence">
                <div class="intel-card">
                  <h4><svg aria-hidden="true"><use href="#i-shield"/></svg> Live Read</h4>
                  <div class="live-meter"><div class="lm-top"><span>Your Confidence</span><b id="lm-conf-val">—</b></div><div class="lm-track"><div class="lm-fill" id="lm-conf" style="width:0%"></div></div></div>
                  <div class="live-meter"><div class="lm-top"><span>Persuasion Score</span><b id="lm-pers-val">—</b></div><div class="lm-track"><div class="lm-fill lm-fill--pers" id="lm-pers" style="width:0%"></div></div></div>
                </div>
                <div class="intel-card">
                  <h4><svg aria-hidden="true"><use href="#i-zap"/></svg> Tactics Detected</h4>
                  <div class="tactic-feed" id="tactic-feed"><p class="cc-empty">Tactics will appear as the conversation unfolds.</p></div>
                </div>
                <div class="intel-card">
                  <h4><svg aria-hidden="true"><use href="#i-refresh"/></svg> Concessions Tracker</h4>
                  <div class="cc-list" id="cc-list"><p class="cc-empty">No concessions yet — hold your ground.</p></div>
                </div>
                <div class="intel-card">
                  <h4><svg aria-hidden="true"><use href="#i-mic"/></svg> Tone &amp; Delivery <span class="preview-badge">Preview</span></h4>
                  <div class="tone-grid" id="tone-grid"><p class="hint">Your delivery read will appear after your first reply.</p></div>
                </div>
                <div class="intel-card">
                  <h4><svg aria-hidden="true"><use href="#i-naira"/></svg> Market Range</h4>
                  <p style="font-size:.72rem;color:var(--text); margin-bottom: 0;" id="mr-role-line">Product Manager · Lagos</p>
                  <div style="display:flex;justify-content:space-between;font-size:.64rem;font-weight:700;color:var(--muted);margin-top:8px"><span id="mr-low">&#8358;—</span><span id="mr-high">&#8358;—</span></div>
                  <div style="position:relative;height:8px;border-radius:20px;background:linear-gradient(90deg,#e2e8f2,#cfe2f2,var(--brand-light));margin-top:5px"></div>
                  <span class="conf-badge" id="mr-confidence" style="margin-top:9px">High confidence · 120 data points</span>
                </div>
              </aside>
            </div>
          </div>

          <!-- ── PHASE 3 · PREMIUM REPORT ── -->
          <div class="phase" id="phase-report">
            <div class="score-hero">
              <div class="score-ring">
                <svg viewBox="0 0 96 96" aria-hidden="true"><circle class="track" cx="48" cy="48" r="42"/><circle class="prog" id="score-prog" cx="48" cy="48" r="42"/></svg>
                <span class="num"><span id="score-num">0</span><i>/ 100</i></span>
              </div>
              <div class="score-copy">
                <h3 id="score-title">Negotiation Score</h3>
                <p id="score-sub">Scored from your actual replies — a transparent measure of message quality, justification and composure. Not a prediction of the real outcome.</p>
                <div class="band-strip" aria-hidden="true"><b id="bnd-1"></b><b id="bnd-2"></b><b id="bnd-3"></b></div>
                <span class="band-lbl" id="band-lbl">Outcome band: Holding Your Ground</span>
              </div>
            </div>

            <div class="rep-sec">
              <h3><svg aria-hidden="true"><use href="#i-sliders"/></svg> Skill Breakdown</h3>
              <div class="skillbars" id="skillbars"></div>
            </div>

            <div class="rep-sec">
              <h3><svg aria-hidden="true"><use href="#i-chart"/></svg> Confidence &amp; Persuasion Across the Conversation</h3>
              <svg class="trend-chart" id="trend-chart" viewBox="0 0 460 130" role="img" aria-label="Line chart of confidence and persuasion scores per round">
                <line class="grid-l" x1="34" y1="14" x2="34" y2="104"/><line class="grid-l" x1="34" y1="104" x2="450" y2="104"/>
                <line class="grid-l" x1="34" y1="59" x2="450" y2="59" stroke-dasharray="3 5"/>
                <text x="8" y="18">100</text><text x="14" y="62">50</text><text x="20" y="107">0</text>
                <polyline class="line-conf" id="tl-conf" points=""/>
                <polyline class="line-pers" id="tl-pers" points=""/>
                <g id="tl-labels"></g>
              </svg>
              <div class="legend"><i>Confidence</i><i class="pers">Persuasion</i></div>
            </div>

            <div class="rep-sec">
              <h3><svg aria-hidden="true"><use href="#i-zap"/></svg> Session Intelligence Recap</h3>
              <div class="counter">
                <div class="co-cell"><i>Your Tactics</i><b id="recap-you">0</b><small>Rapport, evidence, reframing</small></div>
                <div class="co-cell"><i>AI Pressure Tactics</i><b id="recap-ai">0</b><small>Challenges, interruptions, resets</small></div>
                <div class="co-cell"><i>Concessions Won</i><b id="recap-conc">0</b><small>Movement you secured from them</small></div>
              </div>
            </div>

            <div class="rep-sec">
              <div class="rep-lists">
                <div class="rep-list rep-list--str"><h4><svg aria-hidden="true"><use href="#i-check-c"/></svg> What Worked</h4><ul id="list-worked"></ul></div>
                <div class="rep-list rep-list--wk"><h4><svg aria-hidden="true"><use href="#i-alert"/></svg> Mistakes to Fix</h4><ul id="list-mistakes"></ul></div>
              </div>
            </div>

            <div class="rep-sec">
              <h3><svg aria-hidden="true"><use href="#i-refresh"/></svg> Say It Better</h3>
              <div id="ba-wrap"></div>
            </div>

            <div class="rep-sec">
              <div class="rec-notes"><b><svg aria-hidden="true"><use href="#i-eye"/></svg> The Recruiter&#8217;s Perspective</b><p id="rec-persp"></p></div>
            </div>

            <div class="rep-sec">
              <h3><svg aria-hidden="true"><use href="#i-naira"/></svg> Suggested Counter Offer</h3>
              <div class="counter">
                <div class="co-cell"><i>Anchor High</i><b id="co-anchor">&#8358;—</b><small>Opens room to concede gracefully</small></div>
                <div class="co-cell"><i>Realistic Ask</i><b id="co-ask">&#8358;—</b><small>Defensible against the market median</small></div>
                <div class="co-cell"><i>Walk-Away Floor</i><b id="co-floor">&#8358;—</b><small>Below this, negotiate benefits instead</small></div>
              </div>
              <p class="hint" style="margin-top:8px">Calculated from your offer, your target and the Lagos market median — a benchmark to negotiate from, not a guarantee.</p>
            </div>

            <div class="rep-sec">
              <h3><svg aria-hidden="true"><use href="#i-target"/></svg> Your Improvement Plan</h3>
              <div class="plan-list" id="plan-list"></div>
            </div>

            <div class="rep-sec">
              <h3><svg aria-hidden="true"><use href="#i-award"/></svg> Achievements</h3>
              <div class="ach-grid" style="margin-top: 10px;">
                <div class="ach" id="ach-1"><svg aria-hidden="true"><use href="#i-award"/></svg><span>Negotiation Master</span></div>
                <div class="ach" id="ach-2"><svg aria-hidden="true"><use href="#i-mic"/></svg><span>Confident Speaker</span></div>
                <div class="ach" id="ach-3"><svg aria-hidden="true"><use href="#i-chat"/></svg><span>Excellent Communicator</span></div>
                <div class="ach" id="ach-4"><svg aria-hidden="true"><use href="#i-naira"/></svg><span>Salary Champion</span></div>
              </div>
            </div>

            <div class="rep-actions">
              <button type="button" class="btn btn-accent" id="again-btn"><svg aria-hidden="true"><use href="#i-play"/></svg> Practice Again</button>
              <button type="button" class="btn btn-outline" id="retry-btn"><svg aria-hidden="true"><use href="#i-refresh"/></svg> Retry Same Scenario</button>
              <button type="button" class="btn btn-outline" id="pdf-btn"><svg aria-hidden="true"><use href="#i-doc"/></svg> Download PDF</button>
              <button type="button" class="btn btn-outline" id="share-btn"><svg aria-hidden="true"><use href="#i-share"/></svg> Share Report</button>
            </div>
          </div>

        </div>
      </section>
    </div>

    <div class="notice notice--info" role="note">
      <svg aria-hidden="true"><use href="#i-info"/></svg>
      <span>Every score here comes from your actual replies in this session. We never estimate your chances of a raise with a real employer — no tool honestly can. The counter-offer figures are market benchmarks to negotiate from.</span>
    </div>

</div>

<!-- mobile sticky CTA -->
<div class="mobile-cta" id="mobile-cta">
  <button type="button" class="btn btn-accent" id="m-start"><svg aria-hidden="true"><use href="#i-play"/></svg> Start Negotiation</button>
</div>

<!-- toast -->
<div class="toast" id="toast" role="status" aria-live="polite"><svg aria-hidden="true"><use href="#i-check-c"/></svg><span id="toast-txt">Ready</span></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function(){
'use strict';
var $ = function(id){ return document.getElementById(id); };
requestAnimationFrame(function(){document.documentElement.classList.add('anim-ready')});

/* ── shell: mobile sticky bar hide-on-scroll ── */
var bar=$('mobile-cta');
if(bar){var lastY=window.scrollY,ticking=false;
  window.addEventListener('scroll',function(){if(ticking)return;ticking=true;
    requestAnimationFrame(function(){var y=window.scrollY,nearBottom=(window.innerHeight+y)>=(document.documentElement.scrollHeight-120);
      if(nearBottom||y<lastY||y<60){bar.classList.remove('hidden')}else if(y>lastY+8){bar.classList.add('hidden')}
      lastY=y;ticking=false;});
  },{passive:true});}

/* ── toast helper ── */
var toastT;
function toast(msg){var t=$('toast');$('toast-txt').textContent=msg;t.classList.add('show');clearTimeout(toastT);toastT=setTimeout(function(){t.classList.remove('show')},3200)}

/* ── phase switcher ── */
var PHASES={setup:'phase-setup',live:'phase-live',report:'phase-report'};
function goPhase(name){
  Object.keys(PHASES).forEach(function(k){$(PHASES[k]).classList.toggle('active',k===name)});
  var onLive=name==='live'||name==='report', onRep=name==='report';
  $('stp-1').dataset.done=onLive?'1':'';$('stp-1').dataset.on=name==='setup'?'1':'';
  $('bar-1').dataset.on=onLive?'1':'';
  $('stp-2').dataset.on=name==='live'?'1':'';$('stp-2').dataset.done=onRep?'1':'';
  $('bar-2').dataset.on=onRep?'1':'';
  $('stp-3').dataset.on=onRep?'1':'';
  var mBtn=$('m-start');
  mBtn.innerHTML=name==='live'
    ? '<svg aria-hidden="true" style="width: 14px; height: 14px; margin-right: 4px;"><use href="#i-check-c"/></svg> End &amp; Get Report'
    : '<svg aria-hidden="true" style="width: 14px; height: 14px; margin-right: 4px;"><use href="#i-play"/></svg> '+(name==='report'?'Practice Again':'Start Negotiation');
  $('simulator').scrollIntoView({behavior:'smooth',block:'start'});
}

/* ── candidate profile prefill ── */
var CANDIDATE_PROFILE=<?= json_encode([
    'firstName' => $candidate['firstName'] ?? '',
    'targetPosition' => $candidate['targetPosition'] ?? ''
]) ?>;
if(CANDIDATE_PROFILE&&CANDIDATE_PROFILE.targetPosition){
  $('n-job').value=CANDIDATE_PROFILE.targetPosition;
  $('profile-prefill-note').hidden=false;
}
$('n-job').addEventListener('input',function(){$('profile-prefill-note').hidden=true;});

/* ── role → market-median lookup ── */
var ROLE_MEDIANS=[
  {re:/product\s*manager|\bpm\b/i,label:'Product Manager',low:380000,median:520000,high:720000},
  {re:/business\s*analyst/i,label:'Business Analyst',low:300000,median:420000,high:600000},
  {re:/data\s*scientist/i,label:'Data Scientist',low:400000,median:550000,high:800000},
  {re:/data\s*analyst/i,label:'Data Analyst',low:280000,median:380000,high:520000},
  {re:/software|developer|engineer|backend|frontend|full[-\s]?stack/i,label:'Software Engineer',low:450000,median:600000,high:900000},
  {re:/ux|ui|product\s*design|designer/i,label:'Designer',low:300000,median:420000,high:600000},
  {re:/project\s*manager/i,label:'Project Manager',low:350000,median:480000,high:650000},
  {re:/marketing/i,label:'Marketing',low:280000,median:400000,high:600000},
  {re:/sales|business\s*development/i,label:'Sales',low:250000,median:380000,high:600000},
  {re:/human\s*resources|\bhr\b|people\s*(ops|operations)/i,label:'HR',low:250000,median:350000,high:500000},
  {re:/customer\s*(service|support)/i,label:'Customer Support',low:180000,median:250000,high:350000},
  {re:/account(ant|ing)|finance/i,label:'Finance',low:280000,median:400000,high:550000}
];
function matchRole(job){
  job=(job||'').trim();
  if(!job)return {matched:false,low:300000,median:450000,high:750000,label:'all roles'};
  for(var i=0;i<ROLE_MEDIANS.length;i++)if(ROLE_MEDIANS[i].re.test(job))return Object.assign({matched:true},ROLE_MEDIANS[i]);
  return {matched:false,low:300000,median:450000,high:750000,label:job};
}
function fmtK(n){return '\u20A6'+Math.round(n/1000)+'k'}

/* ── job title live-tracks Market Median + Salary Benchmark ── */
$('n-job').addEventListener('input',updateJobRefs);
function updateJobRefs(){
  var job=$('n-job').value.trim();
  if(!job){
    $('mm-label').textContent='Market Median · add a job title below';
    $('mm-value').textContent='Not set yet';$('mm-value').classList.add('stat-num--empty');
    $('bench-role-line').textContent='Add a job title in the form to see role-specific benchmarks.';
    $('bench-source-line').textContent='Add a job title to see the benchmark range.';
    $('bench-low').textContent='\u20A6\u2014';$('bench-median').textContent='Median \u20A6\u2014';$('bench-high').textContent='\u20A6\u2014';
    $('bench-offer-marker').style.display='none';$('bench-target-marker').style.display='none';
    return;
  }
  var r=matchRole(job);
  $('mm-value').classList.remove('stat-num--empty');
  $('mm-label').textContent='Market Median · '+job+', Lagos';
  $('mm-value').textContent=fmtNaira(r.median);
  $('bench-role-line').textContent=(r.matched?job:job+' (closest match: general roles)')+' · Lagos · 4\u20137 yrs experience:';
  $('bench-source-line').innerHTML=(r.matched
    ?'Red = your current offer · Orange = your target. Source: JobberRecruit placements + verified listings, Q2 2026.'
    :'No exact match for this title yet \u2014 showing a broad Lagos market range until more listings are tagged.');
  $('bench-low').textContent=fmtK(r.low);
  $('bench-median').textContent='Median '+fmtK(r.median);
  $('bench-high').textContent=fmtK(r.high);
  updateBenchMarkers(r);
}
function updateBenchMarkers(r){
  var offerEl=$('bench-offer-marker'),targetEl=$('bench-target-marker');
  if(!S||!S.offer){offerEl.style.display='none';targetEl.style.display='none';return}
  function pos(v){return Math.max(0,Math.min(100,Math.round((v-r.low)/(r.high-r.low)*100)))+'%'}
  offerEl.style.left=pos(S.offer);offerEl.style.display='block';
  if(S.target){targetEl.style.left=pos(S.target);targetEl.style.display='block'}
}
updateJobRefs();

/* ── ₦ helpers ── */
function parseNaira(s){return +((s||'').replace(/[^\d]/g,''))||0}
function fmtNaira(n){return '\u20A6'+n.toLocaleString('en-NG')}
$('n-offer').addEventListener('input',updateTargetHint);
$('n-target').addEventListener('input',updateTargetHint);
function updateTargetHint(){
  var o=parseNaira($('n-offer').value),t=parseNaira($('n-target').value);
  if(!o||!t){$('target-hint').textContent='Add your offer and target to see how your ask compares.';return}
  var pct=Math.round((t-o)/o*100);
  $('target-hint').textContent=pct<=0?'Your target is at or below the offer — aim higher; the recruiter expects a counter.'
    :pct+'% above the offer — '+(pct<=10?'safe and very winnable.':pct<=25?'ambitious but defensible against the Lagos median.':'a big jump — be ready to justify every naira.');
}
updateTargetHint();

/* ── simulated file uploads ── */
[['up-offer','file-offer','Offer letter'],['up-jd','file-jd','Job description']].forEach(function(cfg){
  var btn=$(cfg[0]),inp=$(cfg[1]);
  btn.addEventListener('click',function(){inp.click()});
  inp.addEventListener('change',function(){
    if(!inp.files.length)return;
    btn.dataset.done='1';
    btn.querySelector('span').textContent=inp.files[0].name.length>26?inp.files[0].name.slice(0,23)+'\u2026':inp.files[0].name;
    btn.querySelector('use').setAttribute('href','#i-check-c');
    toast(cfg[2]+' attached \u2014 saved with this session');
  });
});

/* ── recruiter personas ── */
var STYLE_ID={
  friendly:{name:'Adaeze Okonkwo',role:'Talent Partner'},
  corporate:{name:'Chioma Nwachukwu',role:'HR Business Partner'},
  strict:{name:'Mr. Bankole Adisa',role:'Head of Compensation'}
};
var ORG_LBL={startup:'Startup',corporate:'Corporate',gov:'Government',multi:'Multinational'};
var MAX_ROUNDS=5;
var THRESH={easy:1.5,medium:1.35,hard:1.22,expert:1.12};
var ORG_THRESH_MOD={startup:1.05,corporate:1.0,gov:0.9,multi:1.12};
var ORG_NOTE={gov:' Public-sector bands are set by policy, not by me.',multi:' We do have more flexibility globally than a typical local band.'};
var SETUP_TO_STYLE={friendly:'friendly',corporate:'difficult',strict:'aggressive'};
var STYLE_META={
  friendly:{label:'Friendly',icon:'i-users',cls:'style--friendly'},
  difficult:{label:'Difficult',icon:'i-shield',cls:'style--difficult'},
  aggressive:{label:'Aggressive',icon:'i-alert',cls:'style--aggressive'},
  executive:{label:'Executive',icon:'i-crown',cls:'style--executive'}
};

/* ── AI reaction line bank ── */
var OPENERS={
  friendly:[
    function(c){return 'Great to speak with you! We\u2019re pleased to offer you the '+c.job+' role at '+fmtNaira(c.offer)+' monthly gross, plus '+(c.benefits||'our standard benefits')+'. We think it\u2019s a strong package \u2014 how does it sound?'},
    function(c){return 'Thanks for making time today. For the '+c.job+' role we\u2019re looking at '+fmtNaira(c.offer)+' monthly gross plus '+(c.benefits||'our standard benefits')+'. I\u2019m genuinely keen to make this work \u2014 talk me through your thinking.'}
  ],
  difficult:[
    function(c){return 'Thank you for your time. We\u2019re offering '+fmtNaira(c.offer)+' monthly gross for the '+c.job+' role, plus '+(c.benefits||'our standard benefits')+'. This reflects our approved band for this level.'},
    function(c){return 'Let\u2019s get into it. The approved figure for '+c.job+' is '+fmtNaira(c.offer)+' monthly gross, plus '+(c.benefits||'our standard benefits')+'. I\u2019ll tell you upfront \u2014 that band doesn\u2019t move easily.'}
  ],
  aggressive:[
    function(c){return 'Let\u2019s get straight to it: '+fmtNaira(c.offer)+' monthly gross for the '+c.job+' role, plus '+(c.benefits||'standard benefits')+'. That\u2019s the number. Where are you on it?'},
    function(c){return 'No point dressing this up \u2014 '+fmtNaira(c.offer)+' monthly gross for '+c.job+', plus '+(c.benefits||'standard benefits')+'. Tell me now if that\u2019s workable for you.'}
  ]
};
var LINES={
  interrupt:{
    friendly:[
      function(c){return 'Whoa, let\u2019s pause there \u2014 '+fmtNaira(c.num)+' is quite a jump from our offer, '+c.pctOver+'% above it. I love the confidence, but I can\u2019t justify that internally as it stands. What\u2019s really driving that number?'+c.orgNote},
      function(c){return 'Hold on \u2014 '+fmtNaira(c.num)+' is '+c.pctOver+'% above what we opened with. I don\u2019t want to shut this down, but I need you to help me understand where that figure comes from.'+c.orgNote+(c.marketMedian?' For reference, the Lagos median we work from for this role sits around '+c.marketMedian+'.':'')}
    ],
    difficult:[
      function(c){return 'I need to stop you there. '+fmtNaira(c.num)+' is '+c.pctOver+'% above our offer and outside the approved band for this role. That figure won\u2019t survive the approval committee as-is.'+c.orgNote},
      function(c){return 'That\u2019s a hard stop from me. '+fmtNaira(c.num)+' sits '+c.pctOver+'% above the offer'+(c.marketMedian?', and above the '+c.marketMedian+' median I\u2019m working from':'')+'. I\u2019d need a genuinely strong reason to take that upward.'+c.orgNote}
    ],
    aggressive:[
      function(c){return 'That number isn\u2019t realistic, and I think you know that. '+fmtNaira(c.num)+' is not a serious opening position \u2014 bring me something I can actually work with.'+c.orgNote},
      function(c){return 'I\u2019ll be blunt \u2014 '+fmtNaira(c.num)+' tells me you haven\u2019t looked at the market'+(c.marketMedian?'. The median for this role sits around '+c.marketMedian+', not anywhere near that':'')+'. Try again, and this time ground it in something real.'+c.orgNote}
    ],
    executive:[
      function(c){return 'Let\u2019s be direct: '+fmtNaira(c.num)+' sits outside the market band we\u2019re working from. I\u2019m open to discussing what would justify it, but we need to ground this in evidence.'+c.orgNote},
      function(c){return 'I respect the ask, but '+fmtNaira(c.num)+' isn\u2019t anchored to anything I can defend upward'+(c.marketMedian?' \u2014 the market median I\u2019m working from is closer to '+c.marketMedian:'')+'. Let\u2019s reset around evidence.'+c.orgNote}
    ]
  },
  challenge:{
    friendly:[
      function(c){return 'I hear you, and I want to get you there \u2014 but I\u2019ll need more than that to take to the committee. What specifically backs up '+(c.num?fmtNaira(c.num):'that number')+'?'},
      function(c){return 'I\u2019m on your side here, genuinely \u2014 but \u201Cfeeling underpaid\u201D won\u2019t move the committee. Give me one concrete result and I can push harder for you.'}
    ],
    difficult:[
      function(){return '\u201CI deserve it\u201D won\u2019t move this conversation. Give me a measurable result, a market data point, or a comparable offer \u2014 something concrete.'},
      function(c){return 'I need more than a feeling to work with'+(c.marketMedian?'. The market median I\u2019m benchmarked against is '+c.marketMedian+' \u2014 what makes you different from that baseline?':'. What makes your case different from the average candidate at this level?')}
    ],
    aggressive:[
      function(){return 'That\u2019s not a justification, that\u2019s a feeling. If you want more money, show me the evidence \u2014 right now.'},
      function(c){return 'You\u2019re asking me to move budget on vibes'+(c.marketMedian?', against a '+c.marketMedian+' median':'')+'. I don\u2019t do that. Numbers, results, or we stay exactly where we are.'}
    ],
    executive:[
      function(){return 'I\u2019d like to support that figure, but I need the reasoning behind it \u2014 results, benchmarks, or scope \u2014 before I can take it further.'},
      function(c){return 'Help me build the case for you'+(c.marketMedian?' \u2014 against a '+c.marketMedian+' median':'')+'. What\u2019s the one result that proves you\u2019re above that line?'}
    ]
  },
  followup:{
    friendly:[
      function(){return 'Tell me more \u2014 what would make this offer feel right for you? Is it the base, the benefits, or something else entirely?'},
      function(){return 'I want to understand this properly \u2014 walk me through what matters most to you in this package.'}
    ],
    difficult:[
      function(){return 'Before we go further: what specifically are you optimising for here \u2014 base salary, total package, or growth path?'},
      function(){return 'I need a clearer picture before I can move anything. What\u2019s the actual priority \u2014 the number, or something else?'}
    ],
    aggressive:[
      function(){return 'You\u2019ll need to be more specific. What exactly are you asking for, and why?'},
      function(){return 'Vague doesn\u2019t work in this room. Name the number and the reason, or we\u2019re not making progress.'}
    ],
    executive:[
      function(){return 'Help me understand your priorities \u2014 is this primarily about base compensation, or the broader package?'},
      function(){return 'Let\u2019s get specific so I can actually act on this \u2014 what\u2019s the one thing that would change your decision today?'}
    ],
    repeat:{
      friendly:[function(){return 'I did ask this a moment ago \u2014 no pressure, but I really do need a number or a priority from you to move this forward.'}],
      difficult:[function(){return 'That\u2019s the second time I\u2019ve asked. I can\u2019t take vagueness to the committee \u2014 give me something specific now.'}],
      aggressive:[function(){return 'I already asked. Third time isn\u2019t coming \u2014 give me a number, or we\u2019re done here.'}],
      executive:[function(){return 'I\u2019ll ask once more, directly: what specific figure or term would resolve this for you?'}]
    }
  },
  resetBoundary:{
    friendly:[
      function(){return 'I want this to stay a good conversation \u2014 let\u2019s reset the tone a little and keep working through this together.'},
      function(){return 'Let\u2019s take a breath \u2014 I still want this to work out for you. Let\u2019s pick this back up calmly.'}
    ],
    difficult:[
      function(){return 'Let\u2019s keep this professional. I\u2019m still willing to work with you, but that tone won\u2019t help either of us get to a deal.'},
      function(){return 'I\u2019m going to reset us here. I\u2019m still at the table, but that tone doesn\u2019t move this conversation forward.'}
    ],
    aggressive:[
      function(){return 'I\u2019ll stop you there. That tone isn\u2019t going to get you anywhere in this room \u2014 dial it back if you want this to continue.'},
      function(){return 'That\u2019s not how this works. Bring it down a notch, or this conversation ends here.'}
    ],
    executive:[
      function(){return 'I understand the frustration, but let\u2019s keep this constructive \u2014 that\u2019s how we actually get you a better outcome.'},
      function(){return 'I hear the frustration. Let\u2019s channel it into the case for your number instead \u2014 that\u2019s what actually moves this.'}
    ]
  },
  strongCase:{
    friendly:[
      function(c){return 'That\u2019s genuinely compelling \u2014 real results, clearly stated. Let\u2019s see how far I can move. Suppose we landed around '+fmtNaira(c.mid)+' \u2014 does that feel fair?'},
      function(c){return 'Okay, that actually changes things for me. With that evidence I can go to '+fmtNaira(c.mid)+' \u2014 how does that land?'}
    ],
    difficult:[
      function(c){return 'Alright \u2014 that\u2019s evidence I can actually use. I still can\u2019t reach your target, but '+fmtNaira(c.mid)+' is something I could defend upward. Thoughts?'},
      function(c){return 'That\u2019s a real data point, not a feeling \u2014 I\u2019ll credit that. '+fmtNaira(c.mid)+' is what I can defend with that behind it.'}
    ],
    aggressive:[
      function(c){return 'Fine \u2014 that\u2019s a real number with real proof behind it. I can move to '+fmtNaira(c.mid)+'. That\u2019s as far as I go today.'},
      function(c){return 'Alright, that lands. '+fmtNaira(c.mid)+' \u2014 take it or we\u2019re back to where we started.'}
    ],
    executive:[
      function(c){return 'That\u2019s a well-built case. I\u2019m prepared to move to '+fmtNaira(c.mid)+' on that basis \u2014 a fair reflection of what you\u2019ve shown me.'},
      function(c){return 'Well argued. I can commit to '+fmtNaira(c.mid)+' on the strength of that evidence \u2014 that\u2019s a genuine move, not a token one.'}
    ]
  },
  tradeBenefits:{
    friendly:[
      function(){return 'I like that you\u2019re thinking beyond base salary. Let\u2019s explore that \u2014 a bonus structure, an earlier review, or extra leave could all be on the table.'},
      function(){return 'Now that\u2019s a smart way to approach it. Base isn\u2019t the only lever \u2014 let\u2019s talk bonus, review timing, and leave.'}
    ],
    difficult:[
      function(){return 'Base is tightly banded, but there\u2019s more room on structure. Which matters more to you \u2014 timing of the next review, or a bonus tied to performance?'},
      function(){return 'That\u2019s the right instinct \u2014 base is fixed, but structure has flex. Pick your priority: review date or bonus.'}
    ],
    aggressive:[
      function(){return 'Base isn\u2019t moving much further. If you want more value, it\u2019ll have to come from the structure \u2014 bonus or review timing. Pick one.'},
      function(){return 'Fine, base stays. If you want more out of this, it comes from structure, not the number. Choose.'}
    ],
    executive:[
      function(){return 'That\u2019s a sound approach. Let\u2019s shape the total package \u2014 base, bonus, and review cadence \u2014 rather than fixating on one number.'},
      function(){return 'That\u2019s exactly the right frame. Total package beats a single figure \u2014 let\u2019s design it properly.'}
    ]
  },
  close:{
    friendly:[
      function(){return 'This has been a genuinely good conversation. Here\u2019s where we\u2019ve landed \u2014 I\u2019ll take your case to the committee with a strong recommendation. Ready for your full report?'},
      function(){return 'I\u2019ve enjoyed this one, honestly. I\u2019ll carry your case forward myself. Let\u2019s get you your full report.'}
    ],
    difficult:[
      function(){return 'Alright, let\u2019s wrap here. I\u2019ll formalise what we\u2019ve discussed and take it upward. You held your ground reasonably well today.'},
      function(){return 'That\u2019s where we land for today. I\u2019ll write this up and take it further. Let\u2019s see how you scored.'}
    ],
    aggressive:[
      function(){return 'We\u2019re done for today. I\u2019ll pass this up the chain \u2014 you negotiated against real resistance, which tells me something.'},
      function(){return 'That\u2019s a wrap. I don\u2019t go easy, and you know it \u2014 let\u2019s see what that\u2019s worth in your report.'}
    ],
    executive:[
      function(){return 'Let\u2019s close this out. Based on today\u2019s discussion, I\u2019m confident in recommending a path forward. Well negotiated \u2014 let\u2019s see your full report.'},
      function(){return 'I think we\u2019re at a natural close. I\u2019m comfortable putting my name behind this recommendation. Let\u2019s review how you did.'}
    ]
  }
};
var ACK={
  friendly:['Mm, okay \u2014 ','Right, thank you \u2014 ','I appreciate that \u2014 ','',''],
  difficult:['Understood. ','Right. ','Noted. ','',''],
  aggressive:['Fine. ','Okay. ','',''],
  executive:['Noted. ','I hear that. ','','']
};
var AI_TACTIC={interrupt:'Reality Check',challenge:'Justification Challenge',followup:'Probing Question',resetBoundary:'Boundary Reset',strongCase:'Value Acknowledgment',tradeBenefits:'Package Reframing',close:'Deal Framing'};
var SUGS={
  interrupt:['Walk back to a defensible number','Justify with a specific result','Ask what number they can support'],
  challenge:['Cite a measurable result','Reference the market median','Mention your years of experience'],
  followup:['Clarify: base salary is the priority','Clarify: total package matters most','Ask what flexibility exists'],
  resetBoundary:['Apologise and reset the tone','Restate your ask calmly','Acknowledge their position first'],
  strongCase:['Accept the revised figure','Counter slightly above the midpoint','Ask for a review date on top'],
  tradeBenefits:['Ask for an earlier review date','Request a performance bonus','Ask about extra leave days'],
  close:['End & get my report']
};

function updateStyle(reaction,current,isLast){
  if(isLast)return 'executive';
  switch(reaction){
    case 'resetBoundary':return current==='friendly'?'difficult':'aggressive';
    case 'interrupt':return current==='friendly'?'difficult':(current==='difficult'?'aggressive':current);
    case 'strongCase':return current==='aggressive'?'difficult':(current==='difficult'?'friendly':current);
    default:return current;
  }
}
var S=null;
function pickVariant(arr,key){
  if(!arr||!arr.length)return function(){return ''};
  if(arr.length===1)return arr[0];
  var last=S.lastVariant[key];
  var idx=Math.floor(Math.random()*arr.length);
  if(idx===last)idx=(idx+1)%arr.length;
  S.lastVariant[key]=idx;
  return arr[idx];
}
function pickAck(style){
  var pool=ACK[style]||[''];
  return pool[Math.floor(Math.random()*pool.length)];
}

/* ── conversation state ── */
var clockTimer=null;
function analyse(text){
  var t=text.toLowerCase();
  var numMatch=t.replace(/,/g,'').match(/(\d{5,9})/);
  return {
    len:text.trim().length,
    hasNumber:!!numMatch, num:numMatch?+numMatch[1]:0,
    justified:/(deliver|led|achiev|result|increas|reduc|grew|saved|market|median|benchmark|experience|certif|managed|shipped|revenue|%)/.test(t),
    mentionsBenefits:/(benefit|bonus|review|leave|hmo|pension|remote|allowance|13th|equity|training)/.test(t),
    courteous:/(thank|appreciate|excited|pleasure|grateful|glad)/.test(t),
    aggressive:/(ridiculous|insult|joke|unacceptable|waste)/.test(t),
    accepts:/(accept|agree|works for me|sounds fair|i can accept|i'll take|that works|deal\b|fine with)/.test(t)
  };
}
function addMsg(who,text,opts){
  opts=opts||{};
  var wrap=document.createElement('div');
  wrap.className='msg msg--'+who+(opts.interrupt?' msg--interrupt':'');
  var tag=opts.interrupt?'<span class="interrupt-tag"><svg aria-hidden="true" style="width:10px;height:10px"><use href="#i-alert"/></svg>Interruption</span>':'';
  wrap.innerHTML='<span class="msg-ava" aria-hidden="true">'+(who==='ai'?'<svg style="width:17px;height:17px"><use href="#i-user"/></svg>':'FE')+'</span>'+
    '<div class="bubble">'+tag+text.replace(/</g,'&lt;')+'<time>'+timeHHMM()+'</time></div>';
  $('chat').appendChild(wrap);
  $('chat').scrollTop=$('chat').scrollHeight;
}
function addNudge(text){
  var n=document.createElement('div');n.className='nudge';
  n.innerHTML='<svg aria-hidden="true"><use href="#i-bulb"/></svg> '+text;
  $('chat').appendChild(n);$('chat').scrollTop=$('chat').scrollHeight;
}
function addStyleShiftNotice(newStyle){
  var meta=STYLE_META[newStyle];
  var n=document.createElement('div');n.className='sys-notice';
  n.innerHTML='<svg aria-hidden="true"><use href="#'+meta.icon+'"/></svg> The recruiter shifts to a more '+meta.label.toLowerCase()+' stance';
  $('chat').appendChild(n);$('chat').scrollTop=$('chat').scrollHeight;
  setStyleBadge(newStyle,true);
}
function setStyleBadge(style,flash){
  var meta=STYLE_META[style];var b=$('style-badge');
  b.className='style-badge '+meta.cls+(flash?' flash':'');
  b.querySelector('use').setAttribute('href','#'+meta.icon);
  $('style-label').textContent=meta.label;
  if(flash)setTimeout(function(){b.classList.remove('flash')},650);
}
function aiSay(text,cb,opts){
  opts=opts||{};
  var readPause=opts.interrupt?110+Math.random()*90:280+Math.random()*320;
  setTimeout(function(){
    var t=document.createElement('div');t.className='msg msg--ai typing';
    t.innerHTML='<span class="msg-ava" aria-hidden="true"><svg style="width:17px;height:17px"><use href="#i-user"/></svg></span><div class="bubble"><i></i><i></i><i></i></div>';
    $('chat').appendChild(t);$('chat').scrollTop=$('chat').scrollHeight;
    var base=opts.interrupt?420:850+Math.min(1300,text.length*7);
    var delay=Math.round(base*(0.82+Math.random()*0.36));
    setTimeout(function(){t.remove();addMsg('ai',text,opts);if(cb)cb()},delay);
  },readPause);
}
function renderSugs(list){
  $('sugs').innerHTML=(list||[]).map(function(s){return '<button type="button" class="sug"><svg aria-hidden="true"><use href="#i-bulb"/></svg>'+s+'</button>'}).join('');
}
function updateLiveMeters(conf,pers){
  $('lm-conf').style.width=conf+'%';$('lm-conf-val').textContent=conf;
  $('lm-pers').style.width=pers+'%';$('lm-pers-val').textContent=pers;
}
function renderTone(u){
  var composure=u.aggressive?28:(u.justified?82:56);
  var clarity=Math.max(20,Math.min(95,40+Math.round(u.len/6)));
  var pace=u.len>220?'Rushed':u.len<40?'Brief':'Measured';
  var toneLbl=u.aggressive?'Tense':u.courteous?'Warm':'Neutral';
  $('tone-grid').innerHTML=
    '<div class="tone-row"><div class="t-top"><span>Composure</span><i>'+composure+'%</i></div><div class="t-track"><div class="t-fill" style="width:'+composure+'%"></div></div></div>'+
    '<div class="tone-row"><div class="t-top"><span>Clarity</span><i>'+clarity+'%</i></div><div class="t-track"><div class="t-fill" style="width:'+clarity+'%"></div></div></div>'+
    '<div class="tone-row"><div class="t-top"><span>Pace</span><i>'+pace+'</i></div></div>'+
    '<div class="tone-row"><div class="t-top"><span>Tone</span><i>'+toneLbl+'</i></div></div>'+
    '<p class="hint" style="margin-top:2px">Text-based preview, derived from your message. Voice &amp; Video sessions add real pace, pitch and eye-contact analysis.</p>';
}
function logTactic(who,label){
  if(!label||!S)return;
  S.tacticsCount[who]=(S.tacticsCount[who]||0)+1;
  var feed=$('tactic-feed');var empty=feed.querySelector('.cc-empty');if(empty)empty.remove();
  var row=document.createElement('div');row.className='tactic-row';
  var cls=who==='ai'?'tactic-tag--ai':who==='warn'?'tactic-tag--warn':'tactic-tag--you';
  var whoLbl=who==='ai'?'AI':who==='warn'?'Flag':'You';
  row.innerHTML='<span class="tactic-tag '+cls+'">'+whoLbl+' \u00B7 '+label+'</span><time>'+timeHHMM()+'</time>';
  feed.appendChild(row);feed.scrollTop=feed.scrollHeight;
}
function logConcession(who,text){
  if(!S)return;
  S.concessions.push({who:who,text:text});
  var list=$('cc-list');var empty=list.querySelector('.cc-empty');if(empty)empty.remove();
  var row=document.createElement('div');row.className='cc-item '+who;
  var icon=who==='you'?'i-check-c':'i-arrow-r';
  var label=who==='you'?'You conceded':'They conceded';
  row.innerHTML='<svg aria-hidden="true"><use href="#'+icon+'"/></svg><span><b style="display:block;font-weight:700;color:var(--brand-deep);font-size:.68rem">'+label+'</b>'+text+'</span>';
  list.appendChild(row);
}
$('sugs').addEventListener('click',function(e){
  var b=e.target.closest('.sug');if(!b)return;
  var box=$('composer-input');
  var seeds={
    'Thank them, then open the salary discussion':'Thank you \u2014 I\u2019m excited about the role. Before I accept, I\u2019d like to discuss the base salary.',
    'Anchor above your target':'I appreciate the offer. Based on my research and results, I was expecting something closer to ',
    'Ask how the figure was set':'Could you walk me through how this figure was arrived at for the role?',
    'Walk back to a defensible number':'On reflection, let\u2019s ground this differently \u2014 based on the market and my results, I\u2019d suggest ',
    'Justify with a specific result':'To justify that, in my current role I delivered ',
    'Ask what number they can support':'What figure would you be comfortable defending internally?',
    'Cite a measurable result':'In my current role I delivered ',
    'Reference the market median':'The market median for this role in Lagos is around \u20A6520,000, which is why I\u2019m asking for ',
    'Mention your years of experience':'With my years of direct experience in this exact function, ',
    'Clarify: base salary is the priority':'To be clear, base salary is my main priority here.',
    'Clarify: total package matters most':'I\u2019m looking at the total package \u2014 base, bonus and benefits together.',
    'Ask what flexibility exists':'Where is there flexibility \u2014 base, bonus, or benefits?',
    'Apologise and reset the tone':'I apologise if that came across wrong \u2014 let\u2019s keep this constructive.',
    'Restate your ask calmly':'To restate calmly: based on my results, I believe ',
    'Acknowledge their position first':'I understand budget is a real constraint on your side. Given that, ',
    'Accept the revised figure':'I can accept that figure \u2014 thank you for working with me on this.',
    'Counter slightly above the midpoint':'I appreciate the movement. Could we land slightly higher, closer to ',
    'Ask for a review date on top':'I can work with that if we also agree a review date at 6 months.',
    'Ask for an earlier review date':'If base is fixed, could we bring the first salary review forward to 6 months?',
    'Request a performance bonus':'Could we add a performance bonus tied to agreed milestones?',
    'Ask about extra leave days':'Would additional leave days be possible as part of the package?',
    'End & get my report':'__END__'
  };
  var v=seeds[b.textContent.trim()]||b.textContent.trim();
  if(v==='__END__'){finish();return}
  box.value=v;box.focus();box.setSelectionRange(v.length,v.length);
});

/* ── start session ── */
function startSession(){
  var offer=parseNaira($('n-offer').value),target=parseNaira($('n-target').value);
  var job=$('n-job').value.trim();
  if(!job){$('n-job').focus();toast('Add the job title on the offer');return}
  if(!offer){$('n-offer').focus();toast('Add the base salary offered');return}
  if(!target){$('n-target').focus();toast('Add your target salary');return}
  var btn=$('start-btn');btn.dataset.loading='1';$('start-txt').textContent='Preparing your recruiter\u2026';
  var setupStyle=(document.querySelector('input[name="nstyle"]:checked')||{}).value||'corporate';
  S={
    job:job,offer:offer,target:target,
    benefits:$('n-benefits').value.trim(),
    setupStyle:setupStyle, style:SETUP_TO_STYLE[setupStyle]||'difficult',
    org:$('n-org').value,diff:$('n-diff').value,
    round:0,turns:[],conf:[],pers:[],
    tacticsCount:{ai:0,you:0,warn:0},concessions:[],
    aiConcededBase:false,aiConcededBenefits:false,userAccepted:false,lastAiNumber:0,
    followupCount:0,lastVariant:{},
    startTime:Date.now()
  };
  setTimeout(function(){
    btn.dataset.loading='';$('start-txt').textContent='Start Negotiation';
    var id=STYLE_ID[setupStyle];
    $('live-name').textContent=id.name;
    $('live-role').textContent=id.role+' \u00B7 '+ORG_LBL[S.org];
    $('chat').innerHTML='';$('composer-input').value='';$('composer-input').disabled=false;$('send-btn').disabled=false;
    $('tactic-feed').innerHTML='<p class="cc-empty">Tactics will appear as the conversation unfolds.</p>';
    $('cc-list').innerHTML='<p class="cc-empty">No concessions yet — hold your ground.</p>';
    $('tone-grid').innerHTML='<p class="hint">Your delivery read will appear after your first reply.</p>';
    updateLiveMeters(0,0);$('lm-conf-val').textContent='\u2014';$('lm-pers-val').textContent='\u2014';
    setStyleBadge(S.style,false);
    var pct=Math.round((S.target-S.offer)/S.offer*100);
    $('st-offer').textContent=fmtNaira(S.offer);$('st-offer').classList.remove('stat-num--empty');
    $('st-offer-lbl').textContent='Current Offer · monthly gross';
    $('st-target').textContent=fmtNaira(S.target);$('st-target').classList.remove('stat-num--empty');
    $('st-target-lbl').textContent='Your Target · '+(pct>=0?pct+'% above current':Math.abs(pct)+'% below current');
    $('mr-role-line').textContent=S.job+' \u00B7 Lagos';
    var liveR=matchRole(S.job);
    $('mr-low').textContent=fmtK(liveR.low);$('mr-high').textContent=fmtK(liveR.high);
    $('mr-confidence').textContent=liveR.matched?'High confidence \u00B7 120 data points':'Broad estimate \u00B7 limited matching listings';
    updateJobRefs();
    goPhase('live');
    $('round-pill').textContent='Round 1 of '+MAX_ROUNDS;
    if(clockTimer)clearInterval(clockTimer);
    clockTimer=setInterval(function(){
      var s=Math.floor((Date.now()-S.startTime)/1000),m=Math.floor(s/60);s=s%60;
      $('clock-live').textContent=(m<10?'0':'')+m+':'+(s<10?'0':'')+s;
    },1000);
    var openerPool=OPENERS[S.style]||OPENERS.difficult;
    var openerLine=openerPool[Math.floor(Math.random()*openerPool.length)]({job:S.job,offer:S.offer,target:S.target,benefits:S.benefits});
    aiSay(openerLine,function(){renderSugs(['Thank them, then open the salary discussion','Anchor above your target','Ask how the figure was set'])});
  },1200);
}
$('phase-setup').addEventListener('submit',function(e){e.preventDefault();startSession()});

/* ── conversation turns ── */
function sendTurn(){
  if(!S)return;
  var box=$('composer-input'),text=box.value.trim();
  if(!text){box.focus();return}
  addMsg('me',text);box.value='';
  var u=analyse(text);S.turns.push(u);
  var lenB=Math.min(14,Math.round(u.len/18));
  var conf=Math.max(8,Math.min(96,40+(u.hasNumber?18:0)+(u.courteous?8:0)+lenB-(u.aggressive?25:0)));
  var pers=Math.max(8,Math.min(96,35+(u.justified?25:0)+(u.hasNumber?12:0)+(u.mentionsBenefits?10:0)+Math.round(lenB/2)-(u.aggressive?20:0)));
  S.conf.push(conf);S.pers.push(pers);
  updateLiveMeters(avg(S.conf),avg(S.pers));
  renderTone(u);

  var threshold=S.offer*(THRESH[S.diff]||1.35)*(ORG_THRESH_MOD[S.org]||1);
  var pctOver=u.hasNumber?Math.round((u.num-S.offer)/S.offer*100):0;
  var isLastRound=S.round>=MAX_ROUNDS-2;
  var reaction;
  if(u.aggressive)reaction='resetBoundary';
  else if(isLastRound)reaction='close';
  else if(u.hasNumber&&u.num>threshold)reaction='interrupt';
  else if(u.hasNumber&&!u.justified)reaction='challenge';
  else if(u.mentionsBenefits)reaction='tradeBenefits';
  else if(u.justified&&u.hasNumber)reaction='strongCase';
  else reaction='followup';

  if(u.aggressive)logTactic('warn','Confrontational Tone');
  if(u.hasNumber&&u.justified)logTactic('you','Evidence-Based Anchor');
  if(u.mentionsBenefits)logTactic('you','Package Reframing');
  if(u.courteous)logTactic('you','Rapport Building');

  var prevStyle=S.style;
  S.style=updateStyle(reaction,S.style,reaction==='close');
  var roleInfo=matchRole(S.job);
  var ctx={job:S.job,offer:S.offer,target:S.target,num:u.num,mid:Math.round((S.offer+S.target)/2/1000)*1000,pctOver:pctOver,benefits:S.benefits,orgNote:ORG_NOTE[S.org]||'',marketMedian:roleInfo.matched?fmtNaira(roleInfo.median):''};

  var lineFn,variantKey=reaction+'-'+S.style;
  if(reaction==='followup'){
    S.followupCount=(S.followupCount||0)+1;
    lineFn=(S.followupCount>=2&&LINES.followup.repeat[S.style])?LINES.followup.repeat[S.style][0]:pickVariant(LINES.followup[S.style],variantKey);
  }else{
    lineFn=pickVariant((LINES[reaction]&&LINES[reaction][S.style])||LINES.followup[S.style],variantKey);
  }
  var line=lineFn(ctx);
  if(reaction==='challenge'||reaction==='followup'||reaction==='tradeBenefits'||reaction==='strongCase')line=pickAck(S.style)+line;
  logTactic('ai',AI_TACTIC[reaction]);

  if(reaction==='strongCase'&&!S.aiConcededBase){S.aiConcededBase=true;S.lastAiNumber=ctx.mid;logConcession('them','Moved from '+fmtNaira(S.offer)+' to '+fmtNaira(ctx.mid));}
  if(reaction==='tradeBenefits'&&!S.aiConcededBenefits){S.aiConcededBenefits=true;logConcession('them','Opened flexibility on bonus, review timing and leave structure');}
  if(u.accepts&&S.lastAiNumber&&!S.userAccepted){S.userAccepted=true;logConcession('you','Accepted the revised figure of '+fmtNaira(S.lastAiNumber));}

  if(u.aggressive)addNudge('Careful \u2014 hostile language costs you the room. Firm and courteous wins.');
  else if(reaction==='challenge')addNudge('Tip: attach a result or market figure to your ask \u2014 it scores far higher.');

  S.round++;
  $('round-pill').textContent='Round '+(S.round+1)+' of '+MAX_ROUNDS;
  renderSugs([]);
  var opts=reaction==='interrupt'?{interrupt:true}:{};
  aiSay(line,function(){
    if(reaction==='close'){renderSugs(['End & get my report']);$('composer-input').disabled=true;$('send-btn').disabled=true;}
    else renderSugs(SUGS[reaction]||[]);
  },opts);
  if(prevStyle!==S.style)addStyleShiftNotice(S.style);
}
$('send-btn').addEventListener('click',sendTurn);
$('composer-input').addEventListener('keydown',function(e){if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();sendTurn()}});

/* ── report rendering ── */
function avg(a){return a.length?Math.round(a.reduce(function(x,y){return x+y},0)/a.length):0}
function buildPlan(skills){
  var PLAN_COPY={
    'Confidence':{t:'Build negotiation confidence',d:'Run two more Negotiation Room sessions this week at Medium difficulty \u2014 confidence compounds fastest through repetition.',link:'candidate-salary-negotiation.html',cta:'Practice again'},
    'Persuasiveness':{t:'Sharpen your persuasion',d:'Before your next negotiation, write down three quantified results you can cite on demand.',link:'candidate-resume-builder.html',cta:'Review your resume wins'},
    'Logic':{t:'Structure your justification',d:'Practice the result \u2192 evidence \u2192 ask sequence until it\u2019s automatic \u2014 it\u2019s what separated your strongest reply from your weakest.',link:'candidate-interview-studio.html',cta:'Practice in the Interview Studio'},
    'Salary Justification':{t:'Anchor every figure in evidence',d:'Pull the Lagos market median into your opening ask next time.',link:'candidate-salary-negotiation.html',cta:'Try another negotiation'},
    'Communication':{t:'Tighten your delivery',d:'Your replies ran short on detail \u2014 practice expanding each answer with one concrete example.',link:'candidate-interview-studio.html',cta:'Practice in the Interview Studio'},
    'Professionalism':{t:'Protect your composure under pressure',d:'Hostile language cost you this session \u2014 rehearse firm-but-professional pushback lines before your next Hard or Expert round.',link:'candidate-salary-negotiation.html',cta:'Retry at Hard difficulty'},
    'Emotional Intelligence':{t:'Read the room, then respond',d:'Practice acknowledging the recruiter\u2019s position before countering \u2014 your best moments did exactly that.',link:'candidate-interview-studio.html',cta:'Practice in the Interview Studio'}
  };
  var sorted=Object.keys(skills).sort(function(a,b){return skills[a]-skills[b]});
  var items=sorted.slice(0,2).map(function(k){return PLAN_COPY[k]}).filter(Boolean);
  items.push({t:'Rehearse the counter you didn\u2019t make',d:'Revisit the \u201CSay It Better\u201D rewrite above and practice saying it out loud until it feels natural.',link:'candidate-salary-negotiation.html',cta:'Practice again'});
  return items;
}
function finish(){
  if(!S)return;
  if(!S.turns.length){toast('Reply at least once before ending \u2014 the report scores your replies');return}
  if(clockTimer){clearInterval(clockTimer);clockTimer=null}
  $('composer-input').disabled=true;$('send-btn').disabled=true;
  $('end-btn').disabled=true;$('restart-btn').disabled=true;
  var loadEl=document.createElement('div');
  loadEl.className='report-loading';
  loadEl.setAttribute('role','status');
  loadEl.setAttribute('aria-live','polite');
  loadEl.innerHTML='<span class="spin" aria-hidden="true"></span><b>Compiling your negotiation report\u2026</b><p>Scoring your replies, mapping the conversation, and preparing your improvement plan.</p>';
  $('phase-live').appendChild(loadEl);
  setTimeout(function(){
    loadEl.remove();
    $('end-btn').disabled=false;$('restart-btn').disabled=false;
    renderReport();
  },850+Math.random()*450);
}
function renderReport(){
  var conf=avg(S.conf),pers=avg(S.pers);
  var justRatio=S.turns.filter(function(t){return t.justified}).length/S.turns.length;
  var numRatio=S.turns.filter(function(t){return t.hasNumber}).length/S.turns.length;
  var courtRatio=S.turns.filter(function(t){return t.courteous}).length/S.turns.length;
  var aggr=S.turns.some(function(t){return t.aggressive});
  var skills={
    'Confidence':conf,
    'Persuasiveness':pers,
    'Logic':Math.round(30+justRatio*55+numRatio*15),
    'Salary Justification':Math.round(20+justRatio*45+numRatio*35),
    'Communication':Math.round(45+Math.min(30,avg(S.turns.map(function(t){return Math.min(30,t.len/9)})))+courtRatio*12),
    'Professionalism':aggr?38:Math.round(62+courtRatio*30),
    'Emotional Intelligence':Math.round(40+courtRatio*35+(aggr?-18:12))
  };
  Object.keys(skills).forEach(function(k){skills[k]=Math.max(10,Math.min(98,skills[k]))});
  var total=Math.round(Object.keys(skills).reduce(function(s,k){return s+skills[k]},0)/7);
  goPhase('report');
  $('score-num').textContent=total;
  requestAnimationFrame(function(){$('score-prog').style.strokeDashoffset=264-(264*total/100)});
  var band=total>=75?3:total>=50?2:1;
  var bandNames=['Finding Your Voice','Holding Your Ground','Deal-Ready'];
  for(var i=1;i<=3;i++)$('bnd-'+i).className=i<=band?'on':'';
  $('band-lbl').textContent='Outcome band: '+bandNames[band-1];
  $('readiness-pill').textContent='Band '+band+' of 3';
  $('readiness-pill').className='pill '+(band===3?'pill--brand':'pill--pending');
  $('readiness-text').textContent=bandNames[band-1];
  $('readiness-lbl').textContent='Negotiation Readiness · from your last session';
  for(var si=1;si<=3;si++)$('spark-'+si).setAttribute('fill',si<=band?'#ED9020':'#e2e8f2');
  $('skillbars').innerHTML=Object.keys(skills).map(function(k){
    var v=skills[k];
    return '<div class="sb-row"><div class="top"><span>'+k+'</span><b>'+v+'</b></div><div class="sb-track"><div class="sb-fill'+(v<50?' sb-fill--warn':'')+'" data-w="'+v+'"></div></div></div>';
  }).join('');
  requestAnimationFrame(function(){document.querySelectorAll('.sb-fill').forEach(function(f){f.style.width=f.dataset.w+'%'})});
  var n=S.conf.length,x0=44,x1=440;
  function pts(arr){return arr.map(function(v,i){var x=n===1?x0:(x0+(x1-x0)*i/(n-1));return Math.round(x)+','+Math.round(104-v*0.9)}).join(' ')}
  $('tl-conf').setAttribute('points',pts(S.conf));
  $('tl-pers').setAttribute('points',pts(S.pers));
  $('tl-labels').innerHTML=S.conf.map(function(_,i){var x=n===1?x0:(x0+(x1-x0)*i/(n-1));return '<text x="'+(Math.round(x)-8)+'" y="120">R'+(i+1)+'</text>'}).join('');
  $('recap-you').textContent=S.tacticsCount.you||0;
  $('recap-ai').textContent=S.tacticsCount.ai||0;
  $('recap-conc').textContent=S.concessions.filter(function(c){return c.who==='them'}).length;
  var worked=[],mistakes=[];
  if(numRatio>0)worked.push('You quoted concrete figures \u2014 recruiters can only move on numbers they can defend upward.');
  if(justRatio>=0.5)worked.push('You backed your ask with results and evidence in most replies.');
  if(courtRatio>0)worked.push('You stayed courteous under pushback \u2014 that keeps the deal alive.');
  if(S.turns.some(function(t){return t.mentionsBenefits}))worked.push('You widened the negotiation beyond base \u2014 bonus, review timing and benefits are real money.');
  if(!worked.length)worked.push('You completed the negotiation without conceding immediately \u2014 a real start.');
  if(numRatio===0)mistakes.push('You never named a figure. Without a number, the recruiter negotiates against silence \u2014 and wins.');
  if(justRatio<0.5)mistakes.push('Your asks leaned on feeling over evidence. Attach one measurable result to every figure.');
  if(aggr)mistakes.push('Hostile wording appeared \u2014 it hands the recruiter a reason to end the conversation.');
  if(!S.turns.some(function(t){return t.mentionsBenefits}))mistakes.push('You left benefits untouched. When base is capped, the review date and bonus are where deals are won.');
  if(!mistakes.length)mistakes.push('Push your anchor slightly higher next time \u2014 you conceded room you never had to.');
  $('list-worked').innerHTML=worked.map(function(t){return '<li>'+t+'</li>'}).join('');
  $('list-mistakes').innerHTML=mistakes.map(function(t){return '<li>'+t+'</li>'}).join('');
  var weakIdx=0,weakScore=999;
  S.turns.forEach(function(t,i){var s=(t.justified?1:0)+(t.hasNumber?1:0)+(t.courteous?1:0)-(t.aggressive?2:0);if(s<weakScore){weakScore=s;weakIdx=i}});
  var meBubbles=document.querySelectorAll('#chat .msg--me .bubble');
  var saidRaw=meBubbles[weakIdx]?meBubbles[weakIdx].childNodes[0].textContent:'(your reply)';
  var said=saidRaw.length>170?saidRaw.slice(0,167)+'\u2026':saidRaw;
  var better='Thank you \u2014 I\u2019m genuinely excited about this role. Based on the results I\u2019ve delivered and the Lagos market median of \u20A6520,000 for this position, I believe '+fmtNaira(S.target)+' fairly reflects the value I\u2019ll bring. How much room do we have?';
  $('ba-wrap').innerHTML='<div class="ba">'+
    '<div class="said"><b><svg aria-hidden="true" style="width: 12px; height: 12px; vertical-align:-1px; margin-right: 4px;"><use href="#i-alert"/></svg> What you said (Round '+(weakIdx+1)+')</b>'+said.replace(/</g,'&lt;')+'</div>'+
    '<div class="better"><b><svg aria-hidden="true" style="width: 12px; height: 12px; vertical-align:-1px; margin-right: 4px;"><use href="#i-check"/></svg> A stronger version</b>'+better+'</div></div>';
  var persp = aggr
    ? 'The tone turned combative \u2014 at that point I stop looking for budget and start looking for a reason to move on. Firm is fine; hostile closes doors.'
    : numRatio>0&&justRatio>=0.5
      ? 'This candidate came prepared: real numbers, real results. I\u2019d take their case to the committee with a genuine recommendation \u2014 that\u2019s exactly what preparation buys you.'
      : 'Pleasant, but I never had to defend our figure. A specific counter backed by one strong result would have forced me to check the budget \u2014 and there usually is more budget.';
  $('rec-persp').textContent=persp;
  $('co-anchor').textContent=fmtNaira(Math.round(S.target*1.08/1000)*1000);
  $('co-ask').textContent=fmtNaira(S.target);
  $('co-floor').textContent=fmtNaira(Math.round((S.offer+(S.target-S.offer)*0.4)/1000)*1000);
  $('plan-list').innerHTML=buildPlan(skills).map(function(p,i){
    return '<div class="plan-item"><span class="plan-num">'+(i+1)+'</span><div><b>'+p.t+'</b><p>'+p.d+'</p><a href="'+p.link+'">'+p.cta+' <svg aria-hidden="true" style="width:11px;height:11px"><use href="#i-arrow-r"/></svg></a></div></div>';
  }).join('');
  var checkWon1 = total>=80;
  var checkWon2 = conf>=70;
  var checkWon3 = skills['Communication']>=70;
  var checkWon4 = numRatio>=0.5&&justRatio>=0.5;
  win('ach-1',checkWon1);win('ach-2',checkWon2);win('ach-3',checkWon3);win('ach-4',checkWon4);
  toast('Report ready \u2014 scored from '+S.turns.length+' replies');
}
$('end-btn').addEventListener('click',finish);
$('restart-btn').addEventListener('click',function(){startSession()});
$('again-btn').addEventListener('click',function(){goPhase('setup')});
$('retry-btn').addEventListener('click',function(){startSession()});
$('pdf-btn').addEventListener('click',function(){toast('Generating your PDF report\u2026')});
$('share-btn').addEventListener('click',function(){
  var url=location.href;
  function ok(){toast('Link copied to clipboard')}
  function fail(){
    var ta=document.createElement('textarea');ta.value=url;ta.style.position='fixed';ta.style.opacity='0';
    document.body.appendChild(ta);ta.select();
    try{document.execCommand('copy');ok()}catch(e){toast('Copy this page\u2019s link to share it')}
    ta.remove();
  }
  if(navigator.clipboard&&navigator.clipboard.writeText)navigator.clipboard.writeText(url).then(ok,fail);
  else fail();
});

/* ── rail tabs ── */
[['tab-hist','pane-hist'],['tab-saved','pane-saved'],['tab-fav','pane-fav']].forEach(function(pair){
  $(pair[0]).addEventListener('click',function(){
    ['tab-hist','tab-saved','tab-fav'].forEach(function(t,i){
      var on=t===pair[0];
      $(t).setAttribute('aria-selected',on?'true':'false');
      $(['pane-hist','pane-saved','pane-fav'][i]).hidden=!on;
    });
  });
});
document.querySelectorAll('.hist-item').forEach(function(b){
  b.addEventListener('click',function(){toast('Full session playback is coming soon')});
});

/* ── stat edit buttons scroll to setup fields ── */
[['edit-offer','n-offer'],['edit-target','n-target']].forEach(function(p){
  $(p[0]).addEventListener('click',function(){
    goPhase('setup');
    setTimeout(function(){$(p[1]).focus()},350);
  });
});

/* ── mobile CTA drives current phase ── */
$('m-start').addEventListener('click',function(){
  if($('phase-live').classList.contains('active')){finish();return}
  if($('phase-report').classList.contains('active')){goPhase('setup');return}
  $('simulator').scrollIntoView({behavior:'smooth',block:'start'});
  setTimeout(startSession,420);
});
})();
</script>
<?= $this->endSection() ?>
