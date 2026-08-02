<?php
$page_title = 'AI Career Coach';

// PHP variables — mapped from controller naming conventions
$name           = $name           ?? $firstName ?? 'Candidate';
$career_health  = $career_health  ?? $careerHealth ?? 74;
$profile_completion = $profile_completion ?? $profileCompletion ?? 82;
$market_readiness   = $market_readiness   ?? 'In Motion';
$coaching_streak    = $coaching_streak    ?? $streak ?? 4;
$streak_best        = $streak_best        ?? $streak ?? 4;
$today_done         = $today_done         ?? 2;
$today_total        = $today_total        ?? 5;
$today_minutes_left = $today_minutes_left ?? 45;

// Six career health scores (0-100)
$scores = $scores ?? [
    ['label' => 'Resume Strength',    'value' => 82, 'source' => 'from your resume',   'tone' => 'good'],
    ['label' => 'Interview Readiness','value' => 68, 'source' => 'from 12 scored sessions','tone' => ''],
    ['label' => 'Technical Skills',   'value' => 71, 'source' => 'from aptitude tests', 'tone' => ''],
    ['label' => 'Market Position',    'value' => 58, 'source' => 'from job applications','tone' => 'warn'],
    ['label' => 'Online Presence',    'value' => 45, 'source' => 'from profile data',   'tone' => 'warn'],
    ['label' => 'Soft Skills',        'value' => 76, 'source' => 'from session feedback','tone' => 'good'],
];

// Roadmap milestones by timeframe
$roadmap = $roadmap ?? [
    '30' => [
        ['icon' => 'i-book',    'title' => 'Close Core Technical Gaps',          'desc' => 'Dedicate 3 evenings a week to product analytics catalog modules.', 'pill' => 'Study Goal', 'pill_class' => 'pill--brand'],
        ['icon' => 'i-edit',    'title' => 'Rewrite LinkedIn headline & About',  'desc' => 'Reflect your quantified accomplishments to clear the recruiter screening filter.', 'pill' => 'Profile', 'pill_class' => 'pill--pending'],
    ],
    '60' => [
        ['icon' => 'i-award',   'title' => 'Book Scrum / Product Owner certification', 'desc' => 'Schedule the exam; a fixed date ensures focused study cycles.', 'pill' => 'Credential', 'pill_class' => 'pill--brand'],
    ],
    '90' => [
        ['icon' => 'i-zap',     'title' => 'Ship one data-led project',          'desc' => 'Utilize new analytical query skills on a real production decision.', 'pill' => 'Project', 'pill_class' => 'pill--brand'],
    ],
    '6m' => [
        ['icon' => 'i-users',   'title' => 'Mentor one junior or intern',        'desc' => 'Grows leadership validation points and provides reference examples for reviews.', 'pill' => 'Leadership', 'pill_class' => 'pill--pending'],
    ],
    '12m' => [
        ['icon' => 'i-target',  'title' => 'Comp review against senior bands',   'desc' => 'Align your compensation with verified senior Lagos benchmarks.', 'pill' => 'Milestone', 'pill_class' => 'pill--muted'],
    ],
];

// Gap analysis
$gaps = $gaps ?? [
    'technical' => ['SQL Databases', 'A/B Testing'],
    'soft'      => ['Executive Communication', 'Mentorship'],
    'demand'    => 74,
];

// Today's career plan tasks
$today_plan = $today_plan ?? [
    ['text' => 'Complete an Interview Practice session', 'mins' => 15, 'done' => true],
    ['text' => 'Improve one leadership-style answer',   'mins' => 10, 'done' => true],
    ['text' => 'Finish the Product Analytics module',    'mins' => 20, 'done' => false],
    ['text' => 'Optimise your CV summary',               'mins' => 15, 'done' => false],
    ['text' => 'Apply to 3 tagged roles',                 'mins' => 10, 'done' => false],
];

// AI Coach greeting
$_hour = (int) date('G');
$_greeting_time = $_hour < 12 ? 'Good morning' : ($_hour < 17 ? 'Good afternoon' : 'Good evening');
$coach_greeting = $coach_greeting ?? "{$_greeting_time}, {$name}. 👋 I noticed you've been active this week — your confidence score is improving. Based on your profile, I'd recommend focusing on your weakest areas to strengthen your market position.";
?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
/* ═══ PAGE: AI CAREER COACH ═══ */
.coach-hero{position:relative;overflow:hidden;border-radius:var(--radius-lg);color:#fff;padding:clamp(22px,3.2vw,34px);
  background:radial-gradient(ellipse 60% 90% at 88% 8%,rgba(237,144,32,.22) 0%,transparent 55%),linear-gradient(150deg,#0A2F57 0%,#064A85 55%,#0861A9 100%);box-shadow:var(--shadow)}
.coach-hero::before{content:'';position:absolute;inset:0;pointer-events:none;opacity:.5;
  background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);
  background-size:42px 42px;-webkit-mask-image:radial-gradient(ellipse 90% 80% at 80% 0%,#000 30%,transparent 80%);mask-image:radial-gradient(ellipse 90% 80% at 80% 0%,#000 30%,transparent 80%)}
.coach-hero .hero-grid{position:relative;display:grid;grid-template-columns:minmax(0,1fr) auto;gap:clamp(16px,3vw,40px);align-items:center}
@media(max-width:820px){.coach-hero .hero-grid{grid-template-columns:1fr}}
.hero-badges{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
.hb{display:inline-flex;align-items:center;gap:6px;font-size:.66rem;font-weight:700;letter-spacing:.04em;padding:5px 12px;border-radius:20px}
.hb svg{width:12px;height:12px}
.hb--premium{background:rgba(237,144,32,.18);border:1px solid rgba(237,144,32,.45);color:#ffd9a8}
.hb--live{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.22);color:#d8ecff}
.pulse{width:7px;height:7px;border-radius:50%;background:#4ade80;box-shadow:0 0 0 0 rgba(74,222,128,.6);animation:pulse 1.8s infinite}
@keyframes pulse{0%{box-shadow:0 0 0 0 rgba(74,222,128,.55)}70%{box-shadow:0 0 0 8px rgba(74,222,128,0)}100%{box-shadow:0 0 0 0 rgba(74,222,128,0)}}
.coach-hero h1{font-size:clamp(1.5rem,3.4vw,2.15rem);font-weight:800;line-height:1.15;margin:2px 0 8px;color:#fff}
.coach-hero h1 span{color:var(--accent)}
.hero-sub{font-size:clamp(.84rem,1.4vw,.95rem);color:rgba(255,255,255,.82);max-width:560px;line-height:1.65}
.hero-chips{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
.hchip{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.16);border-radius:12px;padding:10px 14px;min-width:150px}
.hchip-lbl{display:inline-flex;align-items:center;gap:6px;font-size:.62rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.6)}
.hchip-lbl svg{width:12px;height:12px;color:var(--accent)}
.hchip-val{font-family:'Sora',sans-serif;font-weight:800;font-size:1.02rem;margin-top:3px}
.hchip-val small{font-size:.66rem;font-weight:600;color:rgba(255,255,255,.55)}
.goal-track{height:5px;border-radius:20px;background:rgba(255,255,255,.15);overflow:hidden;margin-top:7px}
.goal-fill{height:100%;border-radius:20px;background:linear-gradient(90deg,var(--accent),#ffc069);transition:width .6s ease}
.hero-ring-wrap{display:flex;flex-direction:column;align-items:center;gap:8px}
@media(max-width:820px){.hero-ring-wrap{display:none}}
.hero-ring{position:relative;width:132px;height:132px}
.hero-ring svg{width:132px;height:132px;transform:rotate(-90deg)}
.hero-ring .track{fill:none;stroke:rgba(255,255,255,.14);stroke-width:10}
.hero-ring .prog{fill:none;stroke:url(#ringGrad);stroke-width:10;stroke-linecap:round;stroke-dasharray:365;stroke-dashoffset:365;transition:stroke-dashoffset 1.1s ease .3s}
.hero-ring .num{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:1.9rem;line-height:1}
.hero-ring .num i{font-style:normal;font-size:.58rem;font-weight:700;letter-spacing:.08em;color:rgba(255,255,255,.6);text-transform:uppercase;margin-top:3px}
.hero-ring-cap{font-size:.66rem;font-weight:600;color:rgba(255,255,255,.6);text-align:center;max-width:150px;line-height:1.5}

/* ═══ AI COACH CARD ═══ */
.ai-coach-card{position:relative;overflow:hidden;border-radius:var(--radius-lg);
  background:linear-gradient(160deg,rgba(255,255,255,.94),rgba(244,249,254,.92));
  border:1px solid rgba(226,232,242,.9);-webkit-backdrop-filter:blur(14px);backdrop-filter:blur(14px);
  box-shadow:0 10px 32px rgba(10,47,87,.1);padding:clamp(18px,2.6vw,26px)}
.ai-coach-card::before{content:'';position:absolute;top:-90px;right:-70px;width:260px;height:260px;border-radius:50%;pointer-events:none;
  background:radial-gradient(circle,rgba(237,144,32,.14),transparent 70%);animation:floatBlob 9s ease-in-out infinite}
.ai-coach-card::after{content:'';position:absolute;bottom:-100px;left:-60px;width:220px;height:220px;border-radius:50%;pointer-events:none;
  background:radial-gradient(circle,rgba(8,97,169,.09),transparent 70%);animation:floatBlob 11s ease-in-out infinite reverse}
@keyframes floatBlob{0%,100%{transform:translate(0,0)}50%{transform:translate(-14px,12px)}}
.coach-top{position:relative;display:flex;align-items:center;gap:13px;margin-bottom:14px}
.coach-ava{position:relative;width:46px;height:46px;border-radius:14px;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#fff;
  background:linear-gradient(135deg,var(--brand-deep),var(--brand));box-shadow:0 8px 20px rgba(8,97,169,.3)}
.coach-ava svg{width:21px;height:21px}
.coach-live-dot{position:absolute;bottom:-2px;right:-2px;width:12px;height:12px;border-radius:50%;background:#4ade80;border:2.5px solid #fff;
  box-shadow:0 0 0 0 rgba(74,222,128,.6);animation:pulse 1.8s infinite}
.coach-id{flex:1;min-width:0}
.coach-id b{display:block;font-family:'Sora',sans-serif;font-weight:800;font-size:1rem;color:var(--brand-deep)}
.coach-id i{font-style:normal;font-size:.72rem;color:var(--muted)}
.coach-voice-btn{position:relative;display:inline-flex;align-items:center;gap:6px;border:1.5px solid var(--border);background:#fff;border-radius:20px;
  padding:8px 13px;font-size:.7rem;font-weight:700;color:var(--muted);cursor:pointer;transition:var(--transition);flex-shrink:0}
.coach-voice-btn:hover{border-color:var(--brand);color:var(--brand)}
.coach-voice-btn svg{width:13px;height:13px}
.soon-badge{font-size:.56rem;font-weight:800;letter-spacing:.04em;padding:2px 7px;border-radius:20px;background:var(--accent-light);color:var(--accent-dark)}
.coach-typing{display:inline-flex;gap:5px;align-items:center;padding:12px 0 4px}
.coach-typing i{width:8px;height:8px;border-radius:50%;background:#a8bdd2;animation:tpDot 1.2s infinite}
.coach-typing i:nth-child(2){animation-delay:.18s}.coach-typing i:nth-child(3){animation-delay:.36s}
@keyframes tpDot{0%,60%,100%{transform:translateY(0);opacity:.5}30%{transform:translateY(-5px);opacity:1}}
.coach-msg{position:relative;animation:rise .35s ease}
.coach-msg p{font-size:.86rem;line-height:1.7;color:var(--text);margin-bottom:7px}
.coach-msg p:last-child{margin-bottom:0}
.coach-msg b{color:var(--brand-deep);font-weight:700}
.coach-chips{position:relative;display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}
.coach-chip{display:inline-flex;align-items:center;gap:6px;padding:9px 15px;min-height:38px;border-radius:20px;border:1.5px solid var(--border);
  background:#fff;font-size:.74rem;font-weight:600;color:var(--brand-deep);cursor:pointer;transition:var(--transition);font-family:inherit}
.coach-chip:hover{border-color:var(--brand);background:var(--brand-light);color:var(--brand);transform:translateY(-1px)}
.coach-reply{position:relative;margin-top:12px;padding:13px 15px;border-radius:12px;background:var(--brand-light);border:1px solid #cfe2f2;
  font-size:.82rem;line-height:1.65;color:var(--brand-deep);animation:rise .3s ease}
.coach-reply:empty{display:none}

/* ═══ TODAY'S CAREER PLAN ═══ */
.today-plan-card{position:relative;overflow:hidden;border-radius:var(--radius-lg);background:#fff;border:1px solid var(--border);
  box-shadow:var(--shadow);padding:clamp(18px,2.6vw,24px)}
.tp-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap}
.tp-eyebrow{font-size:.64rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--accent-dark)}
.tp-head h2{font-size:clamp(1.05rem,2vw,1.25rem);font-weight:800;color:var(--brand-deep);margin-top:2px}
.tp-streak{display:inline-flex;align-items:center;gap:7px;padding:8px 14px;border-radius:20px;background:linear-gradient(120deg,#fff4e6,#ffe8cc);
  border:1px solid #f3d9ae;font-size:.78rem;font-weight:700;color:var(--accent-dark)}
.tp-streak svg{width:15px;height:15px;color:#f2833d}
.tp-streak b{font-family:'Sora',sans-serif;font-size:.94rem}
.tp-progress-row{display:flex;align-items:center;gap:16px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border)}
.tp-ring{position:relative;width:64px;height:64px;flex-shrink:0}
.tp-ring svg{width:64px;height:64px;transform:rotate(-90deg)}
.tp-ring .track{fill:none;stroke:var(--bg);stroke-width:6}
.tp-ring .prog{fill:none;stroke:var(--accent);stroke-width:6;stroke-linecap:round;stroke-dasharray:163;stroke-dashoffset:163;transition:stroke-dashoffset .7s ease .1s}
.tp-ring .num{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:.86rem;color:var(--brand-deep)}
.tp-meta b{font-family:'Sora',sans-serif;font-weight:800;color:var(--brand-deep);font-size:.94rem}
.tp-meta{font-size:.82rem;color:var(--text)}
.tp-time{color:var(--muted)}
.tp-list{display:flex;flex-direction:column;gap:2px;margin-bottom:16px}
.tp-item{display:flex;align-items:center;gap:11px;padding:10px 4px;border-radius:9px;cursor:pointer;transition:var(--transition);-webkit-tap-highlight-color:transparent;position:relative}
.tp-item:hover{background:var(--bg)}
.tp-item input{position:absolute;opacity:0;width:22px;height:22px;cursor:pointer}
.tp-check{position:relative;width:22px;height:22px;border-radius:7px;border:2px solid var(--border);flex-shrink:0;transition:var(--transition);display:flex;align-items:center;justify-content:center}
.tp-item input:checked ~ .tp-check{background:var(--success);border-color:var(--success)}
.tp-check::after{content:'';width:11px;height:7px;border-left:2.5px solid #fff;border-bottom:2.5px solid #fff;transform:rotate(-45deg) translateY(-1px);opacity:0;transition:opacity .15s ease}
.tp-item input:checked ~ .tp-check::after{opacity:1}
.tp-txt{flex:1;font-size:.82rem;color:var(--text);display:flex;justify-content:space-between;gap:10px;align-items:baseline}
.tp-item input:checked ~ .tp-check ~ .tp-txt{color:var(--muted);text-decoration:line-through}
.tp-txt i{font-style:normal;font-size:.68rem;color:var(--muted);flex-shrink:0}
.tp-week-strip{display:flex;gap:6px;justify-content:space-between}
.tp-day{width:32px;height:32px;border-radius:9px;background:var(--bg);border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;
  font-size:.68rem;font-weight:700;color:var(--muted)}
.tp-day.done{background:var(--success-light);border-color:#bfe3cc;color:var(--success)}
.tp-day.today{border-color:var(--accent);color:var(--accent-dark);box-shadow:0 0 0 3px var(--accent-light)}
.tp-celebrate{display:none;align-items:center;gap:8px;margin-top:14px;padding:11px 14px;border-radius:10px;background:var(--success-light);
  color:var(--success);font-size:.8rem;font-weight:700}
.tp-celebrate.show{display:flex;animation:rise .3s ease}
.tp-celebrate svg{width:15px;height:15px;flex-shrink:0}

/* ═══ SCORE METERS ═══ */
.meters{display:grid;grid-template-columns:repeat(4,1fr);gap:clamp(9px,1.2vw,14px)}
@media(max-width:1100px){.meters{grid-template-columns:repeat(3,1fr)}}
@media(max-width:600px){.meters{grid-template-columns:repeat(2,1fr)}}
.meter{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:14px 15px 12px;transition:var(--transition);position:relative;overflow:hidden}
.meter:hover{box-shadow:var(--shadow);transform:translateY(-2px)}
.meter i{font-style:normal;display:flex;align-items:center;gap:6px;font-size:.62rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted)}
.meter i svg{width:12px;height:12px;color:var(--brand)}
.meter b{display:block;font-family:'Sora',sans-serif;font-weight:800;font-size:1.3rem;color:var(--brand-deep);margin-top:4px}
.meter b small{font-size:.58em;color:var(--muted);font-weight:700}
.meter .m-track{height:6px;border-radius:20px;background:var(--bg);overflow:hidden;margin-top:8px}
.meter .m-fill{height:100%;border-radius:20px;background:linear-gradient(90deg,var(--brand-dark),var(--brand));width:0;transition:width .9s ease .2s}
.meter[data-tone="warn"] .m-fill{background:linear-gradient(90deg,var(--accent-dark),var(--accent))}
.meter[data-tone="good"] .m-fill{background:linear-gradient(90deg,#12813b,var(--success))}
.meter .m-src{font-size:.58rem;color:#8fa0b8;margin-top:6px;display:block}

/* ═══ COACH GRID ═══ */
.coach-grid{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(0,1fr);gap:clamp(14px,1.8vw,22px);align-items:start}
@media(max-width:1180px){.coach-grid{grid-template-columns:1fr}}
.col{display:flex;flex-direction:column;gap:clamp(14px,1.8vw,22px);min-width:0}

/* ═══ EXPANDABLE ANALYSIS ═══ */
.ax{border:1px solid var(--border);border-radius:12px;background:#fff;margin-bottom:10px;overflow:hidden;transition:var(--transition)}
.ax:last-child{margin-bottom:0}
.ax[open]{box-shadow:var(--shadow);border-color:#cfe2f2}
.ax summary{display:flex;align-items:center;gap:12px;padding:14px 16px;cursor:pointer;list-style:none;-webkit-tap-highlight-color:transparent;min-height:52px}
.ax summary::-webkit-details-marker{display:none}
.ax-ic{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:var(--brand-light);color:var(--brand)}
.ax-ic svg{width:16px;height:16px}
.ax--risk .ax-ic{background:var(--danger-light);color:var(--danger)}
.ax--warn .ax-ic{background:var(--accent-light);color:var(--accent-dark)}
.ax--good .ax-ic{background:var(--success-light);color:var(--success)}
.ax-title{flex:1;min-width:0}
.ax-title b{display:block;font-size:.84rem;font-weight:700;color:var(--brand-deep)}
.ax-title i{font-style:normal;font-size:.68rem;color:var(--muted)}
.ax-chev{width:16px;height:16px;color:var(--muted);transition:transform .22s ease;flex-shrink:0}
.ax[open] .ax-chev{transform:rotate(90deg)}
.ax-body{padding:2px 16px 15px 66px;font-size:.78rem;color:var(--text);line-height:1.65}
@media(max-width:560px){.ax-body{padding-left:16px}}
.ax-body ul{list-style:none;display:flex;flex-direction:column;gap:7px;margin-top:4px}
.ax-body li{display:flex;gap:8px;line-height:1.55}
.ax-body li::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--brand);flex-shrink:0;margin-top:8px}
.ax--risk .ax-body li::before{background:var(--danger)}
.ax--warn .ax-body li::before{background:var(--accent)}
.ax--good .ax-body li::before{background:var(--success)}

/* ═══ ROADMAP (redesigned) ═══ */
.rm-tabs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:16px}
.rm-tab{border:1.5px solid var(--border);background:#fff;border-radius:20px;padding:8px 16px;min-height:40px;font-family:'Inter',sans-serif;font-size:.74rem;font-weight:700;color:var(--muted);cursor:pointer;transition:var(--transition)}
.rm-tab[aria-selected="true"]{border-color:var(--brand);background:var(--brand);color:#fff}
.rm-tab:hover:not([aria-selected="true"]){border-color:var(--brand);color:var(--brand)}
.rm-pane{display:none}
.rm-pane.active{display:block;animation:rise .28s ease}
.mile{display:flex;gap:13px;padding:13px 0;border-bottom:1px solid var(--border)}
.mile:last-child{border-bottom:none}
.mile-ic{width:38px;height:38px;border-radius:10px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.mile-ic svg{width:15px;height:15px}
.mile b{display:block;font-size:.8rem;font-weight:700;color:var(--brand-deep)}
.mile p{font-size:.74rem;color:var(--muted);line-height:1.55;margin-top:2px}
.mile .pill{margin-top:6px}

/* ═══ GAP ANALYSIS (redesigned) ═══ */
.gap-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px 22px}
@media(max-width:680px){.gap-grid{grid-template-columns:1fr}}
.gap-block h4{display:flex;align-items:center;gap:7px;font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
.gap-block h4 svg{width:13px;height:13px;color:var(--accent-dark)}
.gchips{display:flex;gap:7px;flex-wrap:wrap}
.gchip{display:inline-flex;align-items:center;gap:6px;font-size:.7rem;font-weight:600;padding:6px 12px;border-radius:20px;background:#fff;border:1.5px solid var(--border);color:var(--brand-deep)}
.gchip em{font-style:normal;font-size:.58rem;font-weight:700;padding:2px 7px;border-radius:20px;background:var(--accent-light);color:var(--accent-dark)}
.gchip em.hot{background:var(--danger-light);color:var(--danger)}
.demand .top{display:flex;justify-content:space-between;font-size:.72rem;font-weight:600;color:var(--brand-deep);margin-bottom:5px}
.demand .top b{font-family:'Sora',sans-serif;color:var(--brand)}

/* ═══ GROWTH SIMULATOR ═══ */
.sim-skills{display:flex;flex-direction:column;gap:8px;margin-bottom:14px}
.sim-skill{display:flex;align-items:center;gap:11px;padding:11px 13px;border:1.5px solid var(--border);border-radius:11px;background:#fff;cursor:pointer;transition:var(--transition);-webkit-tap-highlight-color:transparent}
.sim-skill:hover{border-color:var(--brand)}
.sim-skill input{width:19px;height:19px;accent-color:var(--brand);flex-shrink:0;cursor:pointer}
.sim-skill b{font-size:.78rem;font-weight:700;color:var(--brand-deep);display:block}
.sim-skill i{font-style:normal;font-size:.64rem;color:var(--muted)}
.sim-skill .pill{margin-left:auto;flex-shrink:0}
.sim-out{border:1px dashed #cfe2f2;background:linear-gradient(135deg,#f4f9fe,#fdf8f0);border-radius:12px;padding:14px 16px}
.sim-out-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
@media(max-width:560px){.sim-out-grid{grid-template-columns:1fr}}
.so-cell{background:#fff;border:1px solid var(--border);border-radius:10px;padding:11px 13px}
.so-cell i{font-style:normal;font-size:.6rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);display:block}
.so-cell b{font-family:'Sora',sans-serif;font-weight:800;font-size:.98rem;color:var(--brand-deep)}

/* ═══ WEEKLY PLAN ═══ */
.week{display:flex;flex-direction:column}
.day{display:flex;align-items:flex-start;gap:11px;padding:10px 0;border-bottom:1px solid var(--border)}
.day:last-child{border-bottom:none}
.day input{width:19px;height:19px;accent-color:var(--brand);margin-top:2px;flex-shrink:0;cursor:pointer}
.day .d-name{width:38px;flex-shrink:0;font-family:'Sora',sans-serif;font-weight:800;font-size:.68rem;color:var(--brand);letter-spacing:.04em;padding-top:3px}
.day label{flex:1;font-size:.76rem;color:var(--text);line-height:1.5;cursor:pointer}
.day input:checked ~ label{color:var(--muted);text-decoration:line-through}
.week-track{height:7px;border-radius:20px;background:var(--bg);overflow:hidden;margin-top:12px}
.week-fill{height:100%;border-radius:20px;background:linear-gradient(90deg,var(--brand-dark),var(--brand));transition:width .4s ease}

/* ═══ AI CONFIDENCE METER ═══ */
.conf-meter{display:flex;align-items:center;gap:14px}
.conf-ring{position:relative;width:70px;height:70px;flex-shrink:0}
.conf-ring svg{width:70px;height:70px;transform:rotate(-90deg)}
.conf-ring .track{fill:none;stroke:var(--bg);stroke-width:7}
.conf-ring .prog{fill:none;stroke:var(--accent);stroke-width:7;stroke-linecap:round;stroke-dasharray:189;stroke-dashoffset:189;transition:stroke-dashoffset 1s ease .3s}
.conf-ring .num{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:1rem;color:var(--brand-deep)}
.conf-meter p{font-size:.74rem;color:var(--muted);line-height:1.6}

/* ═══ COURSES ═══ */
.courses{display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(10px,1.4vw,16px)}
@media(max-width:1000px){.courses{grid-template-columns:1fr}}
.course{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;transition:var(--transition);display:flex;flex-direction:column}
.course:hover{box-shadow:var(--shadow);transform:translateY(-3px);border-color:#cfe2f2}
.course-top{height:78px;position:relative;background:radial-gradient(ellipse 70% 120% at 85% 0%,rgba(237,144,32,.28) 0%,transparent 55%),linear-gradient(140deg,#0A2F57,#0861A9);display:flex;align-items:center;padding:0 16px;color:#fff}
.course-top svg{width:26px;height:26px}
.course-top .pill{position:absolute;top:10px;right:12px}
.course-body{padding:14px 16px 16px;display:flex;flex-direction:column;gap:8px;flex:1}
.course-body h3{font-size:.86rem;font-weight:700;color:var(--brand-deep);line-height:1.4}
.course-meta{display:flex;gap:12px;flex-wrap:wrap;font-size:.66rem;font-weight:600;color:var(--muted)}
.course-meta i{font-style:normal;display:inline-flex;align-items:center;gap:5px}
.course-meta svg{width:12px;height:12px;color:var(--brand)}
.course-impact{font-size:.72rem;color:var(--text);line-height:1.55;background:var(--bg);border:1px solid var(--border);border-radius:9px;padding:8px 11px}
.course .btn{margin-top:auto}

/* ═══ BENEFITS ═══ */
.benefits{display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(10px,1.4vw,16px)}
@media(max-width:1000px){.benefits{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.benefits{grid-template-columns:1fr}}
.ben{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:19px 19px 17px;transition:var(--transition);position:relative;overflow:hidden}
.ben:hover{box-shadow:var(--shadow);transform:translateY(-3px);border-color:#cfe2f2}
.ben-ic{width:42px;height:42px;border-radius:12px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;margin-bottom:12px;transition:transform .25s ease}
.ben:hover .ben-ic{transform:scale(1.1) rotate(-5deg)}
.ben-ic svg{width:19px;height:19px}
.ben--accent .ben-ic{background:var(--accent-light);color:var(--accent-dark)}
.ben h3{font-size:.9rem;font-weight:700;color:var(--brand-deep);margin-bottom:5px}
.ben p{font-size:.76rem;color:var(--muted);line-height:1.6}

/* ═══ ACHIEVEMENT TIMELINE ═══ */
.ach-timeline{display:flex;flex-direction:column}
.at-item{display:flex;gap:12px;padding-bottom:16px;position:relative}
.at-item::before{content:'';position:absolute;left:5px;top:16px;bottom:-4px;width:1.5px;background:var(--border)}
.at-item:last-child::before{display:none}
.at-item:last-child{padding-bottom:0}
.at-dot{width:11px;height:11px;border-radius:50%;background:var(--brand);border:2px solid var(--brand-light);flex-shrink:0;margin-top:3px;position:relative;z-index:1}
.at-item:first-child .at-dot{background:var(--accent);border-color:var(--accent-light)}
.at-item b{display:block;font-size:.72rem;font-weight:700;color:var(--brand-deep)}
.at-item p{font-size:.78rem;color:var(--text);line-height:1.55;margin-top:2px}

/* ═══ ACTION BAR ═══ */
.action-bar{display:flex;gap:10px;flex-wrap:wrap;justify-content:center;padding:18px;border:1px solid var(--border);border-radius:var(--radius-lg);background:#fff}
.btn-generate{position:relative;overflow:hidden;background:linear-gradient(120deg,var(--accent) 0%,#f2a437 55%,var(--accent) 100%);color:var(--brand-deep);border:1.5px solid var(--accent-dark);box-shadow:0 6px 18px rgba(237,144,32,.32);font-family:'Sora',sans-serif;font-weight:700}
.btn-generate:hover{background:linear-gradient(120deg,var(--accent-dark),var(--accent));color:var(--brand-deep);border-color:var(--accent-dark)}
.btn-generate::after{content:'';position:absolute;top:0;bottom:0;left:-70%;width:45%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.55),transparent);transform:skewX(-18deg);animation:shine 3.2s ease-in-out infinite}
@keyframes shine{0%,55%{left:-70%}85%,100%{left:130%}}

/* ═══ ADVICE CONTENT ═══ */
.advice-content{line-height:1.8}
.advice-content h1,.advice-content h2,.advice-content h3{color:var(--brand-deep);font-family:'Sora',sans-serif;margin-top:1.5rem;margin-bottom:1rem;font-weight:800}
.advice-content h1{font-size:1.5rem}.advice-content h2{font-size:1.3rem}.advice-content h3{font-size:1.15rem}
.advice-content p{margin-bottom:1.2rem;font-size:.86rem;color:var(--text)}
.advice-content ul,.advice-content ol{margin-bottom:1.5rem;padding-left:1.5rem}
.advice-content li{margin-bottom:.5rem;font-size:.84rem}
.advice-content strong{color:var(--brand-deep);font-weight:700}

/* ═══ SECTION HEADS ═══ */
.sec-head{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px}
.sec-head h2{font-size:clamp(1.05rem,2vw,1.3rem);font-weight:800;color:var(--brand-deep);display:flex;align-items:center;gap:10px}
.sec-head h2 svg{width:20px;height:20px;color:var(--brand)}
.sec-head p{font-size:.8rem;color:var(--muted);margin-top:2px}

/* ═══ PILLS ═══ */
.pill{display:inline-flex;align-items:center;gap:5px;font-size:.66rem;font-weight:700;padding:4px 11px;border-radius:20px;letter-spacing:.02em;white-space:nowrap}
.pill svg{width:11px;height:11px}
.pill--brand{background:var(--brand-light);color:var(--brand)}
.pill--pending{background:var(--accent-light);color:var(--accent-dark)}
.pill--success{background:var(--success-light);color:var(--success)}
.pill--muted{background:var(--bg);color:var(--muted)}

/* ═══ SKELETON LOADING ═══ */
@keyframes shimmer{0%{background-position:-320px 0}100%{background-position:320px 0}}
.skel{border-radius:8px;background:linear-gradient(90deg,#eef2f8 25%,#f7f9fd 40%,#eef2f8 55%);background-size:640px 100%;animation:shimmer 1.3s linear infinite}
.gen-veil{position:absolute;inset:0;background:rgba(255,255,255,.72);-webkit-backdrop-filter:blur(2px);backdrop-filter:blur(2px);display:flex;align-items:center;justify-content:center;z-index:5;border-radius:inherit;opacity:0;visibility:hidden;transition:opacity .2s ease}
.gen-veil.show{opacity:1;visibility:visible}
.gen-veil span{display:inline-flex;align-items:center;gap:10px;font-size:.8rem;font-weight:700;color:var(--brand-deep);background:#fff;border:1px solid var(--border);border-radius:12px;padding:12px 18px;box-shadow:var(--shadow)}
.spin{width:16px;height:16px;border:2.5px solid var(--brand-light);border-top-color:var(--brand);border-radius:50%;animation:spin .8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}

/* ═══ TOAST ═══ */
.toast{position:fixed;bottom:24px;left:50%;transform:translate(-50%,20px);z-index:1400;display:flex;align-items:center;gap:10px;background:var(--brand-deep);color:#fff;font-size:.82rem;font-weight:600;padding:13px 20px;border-radius:12px;box-shadow:var(--shadow-lg);opacity:0;visibility:hidden;transition:opacity .25s ease,transform .25s ease,visibility .25s;max-width:min(420px,calc(100vw - 32px))}
.toast.show{opacity:1;visibility:visible;transform:translate(-50%,0)}
.toast svg{width:17px;height:17px;color:var(--accent);flex-shrink:0}

/* ═══ FAB ═══ */
.fab-wrap{position:fixed;right:22px;bottom:26px;z-index:60;display:flex;flex-direction:column;align-items:flex-end;gap:10px}
@media(max-width:760px){.fab-wrap{display:none}}
.fab-menu{display:flex;flex-direction:column;gap:8px;align-items:flex-end}
.fab-item{display:inline-flex;align-items:center;gap:8px;padding:11px 16px;border-radius:24px;background:#fff;border:1px solid var(--border);
  box-shadow:0 6px 18px rgba(10,47,87,.14);font-size:.78rem;font-weight:700;color:var(--brand-deep);text-decoration:none;cursor:pointer;
  animation:fabIn .22s ease backwards;white-space:nowrap;font-family:inherit}
.fab-item:nth-child(1){animation-delay:.02s}.fab-item:nth-child(2){animation-delay:.06s}.fab-item:nth-child(3){animation-delay:.1s}
@keyframes fabIn{from{opacity:0;transform:translateY(8px) scale(.94)}to{opacity:1;transform:translateY(0) scale(1)}}
.fab-item:hover{border-color:var(--brand);color:var(--brand);transform:translateX(-2px)}
.fab-item svg{width:14px;height:14px;color:var(--brand)}
.fab-btn{width:56px;height:56px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;
  background:linear-gradient(135deg,var(--brand-deep),var(--brand));color:#fff;box-shadow:0 10px 26px rgba(8,97,169,.4);transition:var(--transition)}
.fab-btn svg{width:22px;height:22px;transition:transform .25s ease}
.fab-btn:hover{transform:translateY(-2px) scale(1.04)}
.fab-btn[aria-expanded="true"] svg{transform:rotate(45deg)}
.fab-glow{position:absolute;inset:-6px;border-radius:50%;background:radial-gradient(circle,rgba(237,144,32,.35),transparent 70%);z-index:-1;animation:pulse 2.6s infinite;pointer-events:none}

/* ═══ MOBILE CTA ═══ */
.mobile-cta{position:fixed;left:0;right:0;bottom:0;z-index:950;display:none;gap:10px;padding:10px clamp(14px,4vw,18px);padding-bottom:max(10px,env(safe-area-inset-bottom,0));background:rgba(255,255,255,.94);-webkit-backdrop-filter:saturate(180%) blur(12px);backdrop-filter:saturate(180%) blur(12px);border-top:1px solid var(--border);transition:transform .28s ease}
.mobile-cta.hidden{transform:translateY(110%)}
.mobile-cta .btn{flex:1}
@media(max-width:1024px){.mobile-cta{display:flex}}

/* ═══ BASE CARD / BTN (shared by layout, defined here as safety net) ═══ */
.card{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden}
.card-head{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:16px 20px;border-bottom:1px solid var(--border);flex-wrap:wrap}
.card-title{display:inline-flex;align-items:center;gap:9px;font-family:'Sora',sans-serif;font-weight:700;font-size:.94rem;color:var(--brand-deep)}
.card-title svg{width:16px;height:16px;color:var(--brand)}
.card-body{padding:18px 20px}
.card-link{font-size:.76rem;font-weight:600;color:var(--brand);display:inline-flex;align-items:center;gap:4px;padding:8px 10px;margin:-8px -10px;border-radius:8px}
.card-link:hover{background:var(--brand-light);text-decoration:none}
.card-link svg{width:13px;height:13px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:11px 20px;border-radius:8px;font-family:'Inter',sans-serif;font-size:.86rem;font-weight:600;cursor:pointer;border:1.5px solid transparent;transition:var(--transition);text-decoration:none;-webkit-tap-highlight-color:transparent;touch-action:manipulation;min-height:44px;white-space:nowrap}
.btn:active{transform:scale(.97)}
.btn svg{width:16px;height:16px;flex-shrink:0}
.btn-primary{background:var(--brand);color:#fff;border-color:var(--brand)}
.btn-primary:hover{background:var(--brand-dark);border-color:var(--brand-dark)}
.btn-outline{background:var(--white);color:var(--brand);border-color:var(--border)}
.btn-outline:hover{background:var(--brand);color:#fff;border-color:var(--brand)}
.btn-accent{background:var(--accent);color:var(--brand-deep);border-color:var(--accent)}
.btn-accent:hover{background:var(--accent-dark);border-color:var(--accent-dark);color:var(--brand-deep)}
.btn-ghost-w{background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.28)}
.btn-ghost-w:hover{background:rgba(255,255,255,.2)}
.btn-sm{padding:8px 14px;font-size:.78rem;min-height:38px}
.btn-lg{padding:14px 32px;font-size:.95rem}
.btn-block{width:100%}
.glow-hover{transition:var(--transition)}
.glow-hover:hover{box-shadow:0 10px 28px rgba(8,97,169,.16);transform:translateY(-3px)}
.ic-btn{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;border:1.5px solid var(--border);background:#fff;color:var(--muted);cursor:pointer;transition:var(--transition);flex-shrink:0}
.ic-btn:hover{border-color:var(--brand);color:var(--brand)}
.ic-btn svg{width:15px;height:15px}

/* ═══ RISE ANIMATION ═══ */
@keyframes rise{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
html.anim-ready .content>*{animation:rise .34s ease}
html.anim-ready .content>*:nth-child(2){animation-delay:.05s}
html.anim-ready .content>*:nth-child(3){animation-delay:.1s}
html.anim-ready .content>*:nth-child(4){animation-delay:.15s}
html.anim-ready .content>*:nth-child(5){animation-delay:.2s}
html.anim-ready .content>*:nth-child(n+6){animation-delay:.24s}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- SVG gradient defs -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
  <linearGradient id="ringGrad" x1="0" y1="0" x2="1" y2="1">
    <stop offset="0%" stop-color="#ED9020"/><stop offset="100%" stop-color="#ffc069"/>
  </linearGradient>
  <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></symbol>
</defs></svg>

<!-- ═══ 1 · HERO ═══ -->
<section class="coach-hero" aria-labelledby="coach-title">
  <div class="hero-grid">
    <div>
      <div class="hero-badges">
        <span class="hb hb--premium"><svg aria-hidden="true"><use href="#i-crown"/></svg> Premium</span>
        <span class="hb hb--live"><span class="pulse" aria-hidden="true"></span> Coach refreshed</span>
      </div>
      <h1 id="coach-title">AI Career <span>Coach</span></h1>
      <p class="hero-sub">Your personal career strategist. It reads your resume, sessions and the Nigerian job market — then tells you exactly what to do next, week by week.</p>
      <div class="hero-chips">
        <div class="hchip">
          <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-user"/></svg> Profile Completion</span>
          <div class="hchip-val"><?= (int)$profile_completion ?>% <small>· complete your profile for better matches</small></div>
          <div class="goal-track"><div class="goal-fill" style="width:<?= (int)$profile_completion ?>%"></div></div>
        </div>
        <div class="hchip">
          <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Job Market Readiness</span>
          <div class="hchip-val"><?= esc($market_readiness) ?></div>
        </div>
        <div class="hchip">
          <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-flame"/></svg> Coaching Streak</span>
          <div class="hchip-val"><?= (int)$coaching_streak ?> weeks <small>· longest yet</small></div>
        </div>
      </div>
    </div>
    <div class="hero-ring-wrap">
      <div class="hero-ring">
        <svg viewBox="0 0 132 132" aria-hidden="true"><circle class="track" cx="66" cy="66" r="58"/><circle class="prog" id="health-prog" cx="66" cy="66" r="58" data-target="<?= (int)$career_health ?>" style="stroke-dashoffset:<?= 365 - round((int)$career_health / 100 * 365) ?>"/></svg>
        <span class="num"><span id="health-num"><?= (int)$career_health ?></span><i>Career Health</i></span>
      </div>
      <p class="hero-ring-cap">Weighted from your six scores below — updated with every session.</p>
    </div>
  </div>
</section>

<!-- ═══ 1b · AI COACH CARD ═══ -->
<section class="ai-coach-card glow-hover" aria-labelledby="coach-panel-title">
  <div class="coach-top">
    <span class="coach-ava"><svg aria-hidden="true"><use href="#i-user"/></svg><span class="coach-live-dot" aria-hidden="true"></span></span>
    <div class="coach-id"><b id="coach-panel-title">Your AI Coach</b><i>Personalised to your last 90 days</i></div>
    <button type="button" class="coach-voice-btn" id="coach-voice-btn"><svg aria-hidden="true"><use href="#i-mic"/></svg> Voice <span class="soon-badge">Soon</span></button>
  </div>
  <div class="coach-typing" id="coach-typing" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="coach-msg" id="coach-msg" hidden>
    <?= $coach_greeting ?>
  </div>
  <div class="coach-chips" id="coach-chips" hidden>
    <button type="button" class="coach-chip" data-q="focus"><svg aria-hidden="true" style="width:11px;height:11px"><use href="#i-target"/></svg> What should I focus on?</button>
    <button type="button" class="coach-chip" data-q="progress"><svg aria-hidden="true" style="width:11px;height:11px"><use href="#i-trend-up"/></svg> How am I progressing?</button>
    <button type="button" class="coach-chip" data-q="blocking"><svg aria-hidden="true" style="width:11px;height:11px"><use href="#i-alert"/></svg> What's blocking my promotion?</button>
  </div>
  <div class="coach-reply" id="coach-reply"></div>
</section>

<!-- ═══ 1c · TODAY'S CAREER PLAN ═══ -->
<section class="today-plan-card" aria-labelledby="today-plan-title">
  <div class="tp-head">
    <div><span class="tp-eyebrow" id="tp-day-label">Today</span><h2 id="today-plan-title">Today's Career Plan</h2></div>
    <div class="tp-streak"><svg aria-hidden="true"><use href="#i-flame"/></svg><b id="tp-streak-num"><?= (int)$coaching_streak ?></b><span>&nbsp;day streak</span></div>
  </div>
  <div class="tp-progress-row">
    <div class="tp-ring">
      <svg viewBox="0 0 64 64" aria-hidden="true"><circle class="track" cx="32" cy="32" r="26"/><circle class="prog" id="tp-prog" cx="32" cy="32" r="26"/></svg>
      <span class="num" id="tp-pct"><?= $today_total > 0 ? round($today_done / $today_total * 100) : 0 ?>%</span>
    </div>
    <div class="tp-meta"><b id="tp-done-count"><?= (int)$today_done ?> of <?= (int)$today_total ?></b> tasks done <span class="tp-time">· ~<span id="tp-time-left"><?= (int)$today_minutes_left ?></span> min left today</span></div>
  </div>
  <div class="tp-list" id="tp-list">
    <?php foreach ($today_plan as $i => $task): ?>
    <label class="tp-item">
      <input type="checkbox" data-min="<?= (int)($task['mins'] ?? 10) ?>" <?= !empty($task['done']) ? 'checked' : '' ?>>
      <span class="tp-check"></span>
      <span class="tp-txt"><?= esc($task['text'] ?? '') ?><i><?= (int)($task['mins'] ?? 0) ?> min</i></span>
    </label>
    <?php endforeach; ?>
  </div>
  <div class="tp-week-strip" aria-label="This week at a glance" id="tp-week-strip"></div>
  <div class="tp-celebrate" id="tp-celebrate">
    <svg aria-hidden="true"><use href="#i-check-c"/></svg> All done for today — great work! Come back tomorrow to keep the streak.
  </div>
</section>

<!-- ═══ 2 · SCORE METERS ═══ -->
<div class="meters" id="meters">
  <?php
  $meter_icons = ['i-doc','i-zap','i-book','i-briefcase','i-globe','i-users'];
  $meter_colors = ['--st-bar:#0861A9','--st-bar:#7c3aed','--st-bar:#16a34a','--st-bar:#ED9020','--st-bar:#0891b2','--st-bar:#dc2626'];
  foreach ($scores as $idx => $score):
    $tone = $score['tone'] ?? '';
    $barStyle = $meter_colors[$idx % count($meter_colors)];
  ?>
  <div class="meter" data-tone="<?= esc($tone) ?>" style="<?= $barStyle ?>">
    <i><svg aria-hidden="true"><use href="#<?= $meter_icons[$idx % count($meter_icons)] ?>"/></svg> <?= esc($score['label'] ?? '') ?></i>
    <b><?= (int)($score['value'] ?? 0) ?><small>/100</small></b>
    <div class="m-track"><div class="m-fill" data-w="<?= (int)($score['value'] ?? 0) ?>%"></div></div>
    <span class="m-src"><?= esc($score['source'] ?? '') ?></span>
  </div>
  <?php endforeach; ?>
</div>

<!-- ═══ 3 · COACH GRID (Roadmap + Analysis) ═══ -->
<div class="coach-grid" style="margin-top:var(--clamp-gap,22px)">

  <!-- LEFT COLUMN -->
  <div class="col">

    <!-- Smart Roadmap -->
    <section class="card" aria-labelledby="roadmap-title">
      <div class="card-head"><span class="card-title" id="roadmap-title"><svg aria-hidden="true"><use href="#i-calendar"/></svg> Smart Roadmap</span></div>
      <div class="card-body">
        <div class="rm-tabs" role="tablist">
          <?php foreach ($roadmap as $days => $miles): ?>
          <button type="button" class="rm-tab" role="tab" aria-selected="<?= $days === '30' ? 'true' : 'false' ?>" data-pane="rm-<?= esc($days) ?>"><?= esc($days) ?> Days</button>
          <?php endforeach; ?>
        </div>
        <?php foreach ($roadmap as $days => $miles): ?>
        <div class="rm-pane <?= $days === '30' ? 'active' : '' ?>" id="rm-<?= esc($days) ?>" role="tabpanel">
          <?php foreach ($miles as $m): ?>
          <div class="mile">
            <span class="mile-ic"><svg aria-hidden="true"><use href="#<?= $m['icon'] ?? 'i-check' ?>"/></svg></span>
            <div>
              <b><?= esc($m['title'] ?? '') ?></b>
              <p><?= esc($m['desc'] ?? '') ?></p>
              <span class="pill <?= esc($m['pill_class'] ?? 'pill--brand') ?>"><?= esc($m['pill'] ?? '') ?></span>
            </div>
          </div>
          <?php endforeach; ?>
          <?php if (empty($miles)): ?>
          <p style="font-size:.78rem;color:var(--muted);padding:12px 0;text-align:center">No milestones for this timeframe yet. Generate new advice to populate.</p>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Market Gap Analysis -->
    <section class="card" aria-labelledby="gap-title">
      <div class="card-head"><span class="card-title" id="gap-title"><svg aria-hidden="true"><use href="#i-chart"/></svg> Market Gap Analysis</span></div>
      <div class="card-body">
        <div class="gap-grid">
          <div class="gap-block">
            <h4><svg aria-hidden="true"><use href="#i-check-c"/></svg> Technical Gaps</h4>
            <div class="gchips">
              <?php foreach (($gaps['technical'] ?? []) as $g): ?>
              <span class="gchip"><?= esc($g) ?> <em class="hot">High</em></span>
              <?php endforeach; ?>
              <?php if (empty($gaps['technical'])): ?>
              <span class="gchip" style="opacity:.5">No gaps detected</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="gap-block">
            <h4><svg aria-hidden="true"><use href="#i-users"/></svg> Soft Gaps</h4>
            <div class="gchips">
              <?php foreach (($gaps['soft'] ?? []) as $g): ?>
              <span class="gchip"><?= esc($g) ?></span>
              <?php endforeach; ?>
              <?php if (empty($gaps['soft'])): ?>
              <span class="gchip" style="opacity:.5">No gaps detected</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="gap-block" style="grid-column:1/-1">
            <h4><svg aria-hidden="true"><use href="#i-trend-up"/></svg> Market Demand</h4>
            <div class="demand">
              <div class="top"><span>Openings vs Candidates</span><b><?= (int)($gaps['demand'] ?? 74) ?>%</b></div>
              <div class="m-track"><div class="m-fill" style="width:<?= (int)($gaps['demand'] ?? 74) ?>%;background:linear-gradient(90deg,var(--brand-dark),var(--brand))"></div></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- AI Confidence Meter -->
    <section class="card" aria-labelledby="conf-title">
      <div class="card-head"><span class="card-title" id="conf-title"><svg aria-hidden="true"><use href="#i-shield"/></svg> AI Confidence</span></div>
      <div class="card-body">
        <div class="conf-meter">
          <div class="conf-ring">
            <svg viewBox="0 0 70 70" aria-hidden="true"><circle class="track" cx="35" cy="35" r="30"/><circle class="prog" id="conf-prog" cx="35" cy="35" r="30" data-target="82"/></svg>
            <span class="num" id="conf-num">82%</span>
          </div>
          <p>Based on the consistency of your sessions, resume completeness, and aptitude performance. Higher confidence = your profile is more attractive to recruiters.</p>
        </div>
      </div>
    </section>

    <!-- AI Advice Playbook -->
    <section class="card" aria-label="Career Coach advice playbook" style="position:relative">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-book"/></svg> Your Career Playbook</span>
        <button type="button" class="btn btn-outline btn-sm" id="btn-print"><svg aria-hidden="true"><use href="#i-download"/></svg> Print</button>
      </div>
      <div class="card-body">
        <div class="advice-content"><?= $advice ?></div>
      </div>
      <div class="gen-veil" id="gen-veil"><span><span class="spin"></span> Regenerating your playbook…</span></div>
    </section>
  </div>

  <!-- RIGHT COLUMN -->
  <div class="col">

    <!-- Growth Simulator -->
    <section class="card" aria-labelledby="sim-title">
      <div class="card-head"><span class="card-title" id="sim-title"><svg aria-hidden="true"><use href="#i-sliders"/></svg> Growth Simulator</span></div>
      <div class="card-body">
        <p style="font-size:.74rem;color:var(--muted);margin-bottom:12px">Select skills to see a market benchmark scenario — <strong>not a personal prediction</strong>.</p>
        <div class="sim-skills" id="sim-skills">
          <label class="sim-skill"><input type="checkbox" value="sql" checked><b>SQL & Databases</b><i>High demand</i><span class="pill pill--brand">+12%</span></label>
          <label class="sim-skill"><input type="checkbox" value="analytics"><b>Product Analytics</b><i>Medium demand</i><span class="pill pill--pending">+8%</span></label>
          <label class="sim-skill"><input type="checkbox" value="leadership"><b>Leadership</b><i>Growing</i><span class="pill pill--success">+15%</span></label>
          <label class="sim-skill"><input type="checkbox" value="scrum"><b>Scrum / Agile</b><i>Steady</i><span class="pill pill--brand">+6%</span></label>
        </div>
        <div class="sim-out" id="sim-out">
          <div class="sim-out-grid">
            <div class="so-cell"><i>Market salary range</i><b id="sim-salary">₦3.2M – ₦5.8M</b></div>
            <div class="so-cell"><i>Readiness band</i><b id="sim-band">In Motion</b></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Weekly Plan -->
    <section class="card" aria-labelledby="week-title">
      <div class="card-head"><span class="card-title" id="week-title"><svg aria-hidden="true"><use href="#i-calendar"/></svg> Weekly Plan</span>
        <span class="pill pill--brand" id="week-pct">0%</span>
      </div>
      <div class="card-body">
        <div class="week" id="week-list">
          <div class="day"><input type="checkbox" checked><span class="d-name">MON</span><label>Complete Interview Practice session</label></div>
          <div class="day"><input type="checkbox" checked><span class="d-name">TUE</span><label>Rewrite LinkedIn headline & About section</label></div>
          <div class="day"><input type="checkbox"><span class="d-name">WED</span><label>Finish Product Analytics module</label></div>
          <div class="day"><input type="checkbox"><span class="d-name">THU</span><label>Optimise CV summary with quantified results</label></div>
          <div class="day"><input type="checkbox"><span class="d-name">FRI</span><label>Apply to 3 tagged roles</label></div>
          <div class="day"><input type="checkbox"><span class="d-name">SAT</span><label>Review and update portfolio / GitHub</label></div>
        </div>
        <div class="week-track"><div class="week-fill" id="week-fill" style="width:33%"></div></div>
      </div>
    </section>

    <!-- Recommended Courses -->
    <section class="card" aria-labelledby="courses-title">
      <div class="card-head"><span class="card-title" id="courses-title"><svg aria-hidden="true"><use href="#i-book"/></svg> Recommended Courses</span></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:12px">
        <div class="course" style="display:flex;flex-direction:row;align-items:center;gap:14px;padding:14px 16px">
          <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--brand-deep),var(--brand));display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff"><svg aria-hidden="true"><use href="#i-chart"/></svg></div>
          <div style="flex:1;min-width:0">
            <b style="font-size:.82rem;font-weight:700;color:var(--brand-deep)">Product Analytics Mastery</b>
            <p style="font-size:.68rem;color:var(--muted);margin-top:2px">8 modules · 4.5 hours</p>
          </div>
          <a href="<?= base_url('training') ?>" class="btn btn-sm btn-outline" style="flex-shrink:0">Start</a>
        </div>
        <div class="course" style="display:flex;flex-direction:row;align-items:center;gap:14px;padding:14px 16px">
          <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--accent-dark),var(--accent));display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff"><svg aria-hidden="true"><use href="#i-shield"/></svg></div>
          <div style="flex:1;min-width:0">
            <b style="font-size:.82rem;font-weight:700;color:var(--brand-deep)">Agile & Scrum Fundamentals</b>
            <p style="font-size:.68rem;color:var(--muted);margin-top:2px">6 modules · 3.2 hours</p>
          </div>
          <a href="<?= base_url('training') ?>" class="btn btn-sm btn-outline" style="flex-shrink:0">Start</a>
        </div>
        <a href="<?= base_url('training') ?>" class="card-link" style="justify-content:center;margin-top:4px">Browse all courses <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
      </div>
    </section>

    <!-- Career Benefits -->
    <section class="card" aria-labelledby="ben-title">
      <div class="card-head"><span class="card-title" id="ben-title"><svg aria-hidden="true"><use href="#i-star"/></svg> Coach Benefits</span></div>
      <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div class="ben" style="padding:14px">
          <div class="ben-ic" style="width:34px;height:34px;margin-bottom:8px"><svg aria-hidden="true"><use href="#i-zap"/></svg></div>
          <h3 style="font-size:.78rem">Personalised Insights</h3>
          <p style="font-size:.68rem">AI reads your data and gives tailored advice.</p>
        </div>
        <div class="ben ben--accent" style="padding:14px">
          <div class="ben-ic" style="width:34px;height:34px;margin-bottom:8px"><svg aria-hidden="true"><use href="#i-target"/></svg></div>
          <h3 style="font-size:.78rem">Market Benchmarking</h3>
          <p style="font-size:.68rem">See where you stand vs. the Nigerian market.</p>
        </div>
        <div class="ben" style="padding:14px">
          <div class="ben-ic" style="width:34px;height:34px;margin-bottom:8px"><svg aria-hidden="true"><use href="#i-calendar"/></svg></div>
          <h3 style="font-size:.78rem">Weekly Action Plans</h3>
          <p style="font-size:.68rem">Concrete tasks to advance your career each week.</p>
        </div>
        <div class="ben ben--accent" style="padding:14px">
          <div class="ben-ic" style="width:34px;height:34px;margin-bottom:8px"><svg aria-hidden="true"><use href="#i-trend-up"/></svg></div>
          <h3 style="font-size:.78rem">Progress Tracking</h3>
          <p style="font-size:.68rem">Watch your career health score grow over time.</p>
        </div>
      </div>
    </section>

    <!-- Achievement Timeline -->
    <section class="card" aria-labelledby="ach-title">
      <div class="card-head"><span class="card-title" id="ach-title"><svg aria-hidden="true"><use href="#i-award"/></svg> Your Journey</span></div>
      <div class="card-body">
        <div class="ach-timeline">
          <div class="at-item"><span class="at-dot"></span><div><b>Profile completed to 82%</b><p>Added skills, bio, and career preferences.</p></div></div>
          <div class="at-item"><span class="at-dot"></span><div><b>First interview simulation completed</b><p>Scored 72% — behavioural section needs work.</p></div></div>
          <div class="at-item"><span class="at-dot"></span><div><b>Resume strengthened</b><p>AI-optimised summary added to your CV.</p></div></div>
          <div class="at-item"><span class="at-dot"></span><div><b>4-week coaching streak</b><p>Longest streak yet — keep it going!</p></div></div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- ═══ ACTION BAR ═══ -->
<div class="action-bar" style="margin-top:22px">
  <button type="button" class="btn btn-generate btn-lg" id="btn-generate">
    <svg aria-hidden="true"><use href="#i-refresh"/></svg> Generate New Advice
  </button>
  <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-outline">
    <svg aria-hidden="true"><use href="#i-arrow-r"/></svg> Back to Tools
  </a>
</div>

<!-- ═══ TOAST ═══ -->
<div class="toast" id="toast" role="alert" aria-live="polite"><svg aria-hidden="true"><use href="#i-check-c"/></svg><span id="toast-txt"></span></div>

<!-- ═══ FAB ═══ -->
<div class="fab-wrap" id="fab-wrap">
  <div class="fab-menu" id="fab-menu" hidden>
    <a href="<?= base_url('candidate/career-tools') ?>" class="fab-item"><svg aria-hidden="true"><use href="#i-zap"/></svg> Career Tools</a>
    <button type="button" class="fab-item" id="fab-generate"><svg aria-hidden="true"><use href="#i-refresh"/></svg> Regenerate</button>
    <button type="button" class="fab-item" id="fab-print"><svg aria-hidden="true"><use href="#i-download"/></svg> Print Playbook</button>
  </div>
  <div style="position:relative">
    <button type="button" class="fab-btn" id="fab-toggle" aria-label="Quick actions" aria-expanded="false"><svg aria-hidden="true"><use href="#i-plus"/></svg></button>
    <div class="fab-glow"></div>
  </div>
</div>

<!-- ═══ MOBILE CTA ═══ -->
<div class="mobile-cta" id="mobile-cta">
  <button type="button" class="btn btn-accent" id="mobi-generate"><svg aria-hidden="true"><use href="#i-refresh"/></svg> Generate New Advice</button>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function(){
  'use strict';
  var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ── content rise animation ── */
  requestAnimationFrame(function(){document.documentElement.classList.add('anim-ready')});

  /* ── hero ring animation ── */
  requestAnimationFrame(function(){requestAnimationFrame(function(){
    var hp=document.getElementById('health-prog');
    if(hp){var t=parseInt(hp.dataset.target,10)||0;hp.style.strokeDashoffset=365-(t/100)*365;}
    document.querySelectorAll('.m-fill[data-w]').forEach(function(f){f.style.width=f.dataset.w;});
    var cp=document.getElementById('conf-prog');
    if(cp){var ct=parseInt(cp.dataset.target,10)||0;cp.style.strokeDashoffset=189-(ct/100)*189;}
    var tp=document.getElementById('tp-prog');
    if(tp){var done=document.querySelectorAll('#tp-list input:checked').length,total=document.querySelectorAll('#tp-list input').length;
      tp.style.strokeDashoffset=163-(total?done/total:0)*163;}
  })});

  /* ── coach card typing → message reveal ── */
  setTimeout(function(){
    var typing=document.getElementById('coach-typing'),msg=document.getElementById('coach-msg'),chips=document.getElementById('coach-chips');
    if(typing)typing.style.display='none';
    if(msg)msg.hidden=false;
    if(chips)chips.hidden=false;
  },1800);

  /* ── coach chip quick replies ── */
  var coachReplies={
    focus:'Based on your scores, <b>Online Presence</b> (45/100) and <b>Market Position</b> (58/100) are your weakest areas. I recommend updating your LinkedIn this week and applying to 3 roles that match your skills.',
    progress:'Your career health is at <b><?= (int)$career_health ?>/100</b>. You\'ve improved +11% in interview readiness this month. Your strongest area is Resume Strength at 82%. Keep the momentum!',
    blocking:'Your <b>Online Presence</b> score (45/100) may be limiting recruiter visibility. Add a professional headline, complete your work history, and get 2+ recommendations to move the needle.'
  };
  document.querySelectorAll('.coach-chip').forEach(function(chip){
    chip.addEventListener('click',function(){
      var q=this.dataset.key||this.dataset.q,reply=document.getElementById('coach-reply');
      if(reply&&coachReplies[q]){reply.innerHTML=coachReplies[q];}
    });
  });

  /* ── today plan checkboxes ── */
  function updateTodayPlan(){
    var checks=document.querySelectorAll('#tp-list input[type="checkbox"]');
    var done=0;checks.forEach(function(c){if(c.checked)done++;});
    var total=checks.length;
    var pct=total?Math.round(done/total*100):0;
    var pctEl=document.getElementById('tp-pct'),countEl=document.getElementById('tp-done-count');
    var tp=document.getElementById('tp-prog');
    if(pctEl)pctEl.textContent=pct+'%';
    if(countEl)countEl.textContent=done+' of '+total;
    if(tp)tp.style.strokeDashoffset=163-(total?done/total:0)*163;
    var celebrate=document.getElementById('tp-celebrate');
    if(celebrate)celebrate.classList.toggle('show',done===total&&total>0);
  }
  document.querySelectorAll('#tp-list input[type="checkbox"]').forEach(function(c){
    c.addEventListener('change',updateTodayPlan);
  });

  /* ── week dots for today plan ── */
  var ws=document.getElementById('tp-week-strip');
  if(ws){
    var labels=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    var html='',now=new Date(),todayIdx=now.getDay();
    for(var i=0;i<7;i++){
      var cls='tp-day';
      if(i===todayIdx)cls+=' today';
      else if(i<todayIdx)cls+=' done';
      html+='<span class="'+cls+'">'+labels[i].charAt(0)+'</span>';
    }
    ws.innerHTML=html;
  }

  /* ── roadmap tabs ── */
  document.querySelectorAll('.rm-tab').forEach(function(tab){
    tab.addEventListener('click',function(){
      document.querySelectorAll('.rm-tab').forEach(function(t){t.setAttribute('aria-selected','false');});
      this.setAttribute('aria-selected','true');
      var paneId=this.dataset.pane;
      document.querySelectorAll('.rm-pane').forEach(function(p){p.classList.remove('active');});
      var pane=document.getElementById(paneId);
      if(pane)pane.classList.add('active');
    });
  });

  /* ── weekly plan progress ── */
  function updateWeekPlan(){
    var checks=document.querySelectorAll('#week-list input[type="checkbox"]');
    var done=0;checks.forEach(function(c){if(c.checked)done++;});
    var total=checks.length;
    var pctEl=document.getElementById('week-pct'),fill=document.getElementById('week-fill');
    var pct=total?Math.round(done/total*100):0;
    if(pctEl)pctEl.textContent=pct+'%';
    if(fill)fill.style.width=pct+'%';
  }
  document.querySelectorAll('#week-list input[type="checkbox"]').forEach(function(c){
    c.addEventListener('change',updateWeekPlan);
  });

  /* ── growth simulator ── */
  var simData={sql:{salary:'₦3.2M – ₦5.8M',band:'In Motion'},analytics:{salary:'₦3.5M – ₦6.2M',band:'In Motion'},leadership:{salary:'₦4.1M – ₦7.5M',band:'Getting Set'},scrum:{salary:'₦3.0M – ₦5.4M',band:'In Motion'}};
  function updateSim(){
    var sel=[];document.querySelectorAll('#sim-skills input:checked').forEach(function(c){sel.push(c.value);});
    var salEl=document.getElementById('sim-salary'),bandEl=document.getElementById('sim-band');
    if(sel.length===0){if(salEl)salEl.textContent='Select skills above';if(bandEl)bandEl.textContent='—';return;}
    var last=sel[sel.length-1],d=simData[last]||{salary:'₦3.2M – ₦5.8M',band:'In Motion'};
    if(salEl)salEl.textContent=d.salary;
    if(bandEl)bandEl.textContent=d.band;
  }
  document.querySelectorAll('#sim-skills input').forEach(function(c){c.addEventListener('change',updateSim);});

  /* ── FAB menu ── */
  var fabBtn=document.getElementById('fab-toggle'),fabMenu=document.getElementById('fab-menu');
  if(fabBtn&&fabMenu){
    fabBtn.addEventListener('click',function(){
      var expanded=this.getAttribute('aria-expanded')==='true';
      this.setAttribute('aria-expanded',String(!expanded));
      fabMenu.hidden=expanded;
    });
    document.addEventListener('click',function(e){
      if(!e.target.closest('#fab-wrap')){fabBtn.setAttribute('aria-expanded','false');fabMenu.hidden=true;}
    });
  }

  /* ── print ── */
  var printBtn=document.getElementById('btn-print'),fabPrint=document.getElementById('fab-print');
  function doPrint(){window.print();}
  if(printBtn)printBtn.addEventListener('click',doPrint);
  if(fabPrint)fabPrint.addEventListener('click',doPrint);

  /* ── generate new advice (POST) ── */
  function doGenerate(){
    var veil=document.getElementById('gen-veil');
    if(veil)veil.classList.add('show');
    fetch('<?= base_url("candidate/coach/generate") ?>',{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest','Content-Type':'application/x-www-form-urlencoded'}})
    .then(function(r){return r.json();})
    .then(function(d){
      if(veil)veil.classList.remove('show');
      if(d.success){toast('New advice generated! Reloading…');setTimeout(function(){location.reload();},1200);}
      else{toast(d.message||'Generation failed. Please try again.');}
    })
    .catch(function(){if(veil)veil.classList.remove('show');toast('Network error. Please try again.');});
  }
  var genBtn=document.getElementById('btn-generate'),mobiGen=document.getElementById('mobi-generate'),fabGen=document.getElementById('fab-generate');
  if(genBtn)genBtn.addEventListener('click',doGenerate);
  if(mobiGen)mobiGen.addEventListener('click',doGenerate);
  if(fabGen)fabGen.addEventListener('click',doGenerate);

  /* ── toast ── */
  function toast(msg){
    var t=document.getElementById('toast'),txt=document.getElementById('toast-txt');
    if(t&&txt){txt.textContent=msg;t.classList.add('show');setTimeout(function(){t.classList.remove('show');},4000);}
  }
})();
</script>
<?= $this->endSection() ?>
