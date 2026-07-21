<?= $this->extend('layouts/app') ?>

<?= $this->section('page_title') ?>Aptitude Tests<?= $this->endSection() ?>

<?php
/* ── helpers for this view ── */
$skillIcons = [
    'numerical' => 'apt-calc', 'verbal' => 'apt-type', 'logical' => 'apt-branch',
    'abstract' => 'apt-shapes', 'ict' => 'apt-monitor', 'general-aptitude' => 'apt-bulb',
];
$fmtDate = static function ($dt) {
    if (!$dt) return '';
    $ts = strtotime($dt);
    $today = strtotime('today');
    if (date('Y-m-d', $ts) === date('Y-m-d', $today)) return 'Today, ' . date('g:i A', $ts);
    if (date('Y-m-d', $ts) === date('Y-m-d', $today - 86400)) return 'Yesterday';
    return date('M j', $ts);
};
$scoreClass = static fn ($p) => $p >= 75 ? 'hi' : ($p >= 50 ? 'mid' : 'low');

$summary     = $summary     ?? ['testsTaken' => 0, 'avgScore' => 0, 'streak' => 0, 'byCategory' => [], 'trend' => []];
$skillTests  = $skillTests  ?? [];
$roleTests   = $roleTests   ?? [];
$history     = $history     ?? [];
$leaderboard = $leaderboard ?? [];

/* distinct role fields for the filter bar */
$roleFields = [];
foreach ($roleTests as $rt) {
    if ($rt['category_slug'] && !isset($roleFields[$rt['category_slug']])) {
        $roleFields[$rt['category_slug']] = $rt['category_name'];
    }
}

/* achievement flags derived from real data */
$allBests = [];
foreach (array_merge($skillTests, $roleTests) as $t) {
    if (!empty($t['best'])) $allBests[] = $t['best'];
}
$anyScore80 = false; $anyScore90 = false; $anyVerified = false;
foreach ($allBests as $b) {
    if ((float) $b['best'] >= 80) $anyScore80 = true;
    if ((float) $b['best'] >= 90) $anyScore90 = true;
    if (!empty($b['passed'])) $anyVerified = true;
}
$skillsAttempted = 0;
foreach ($skillTests as $t) { if (!empty($t['best'])) $skillsAttempted++; }
$ach = [
    ['First Test',   $summary['testsTaken'] >= 1, 'apt-play',   'Completed your first assessment'],
    ['3-Day Streak', $summary['streak'] >= 3,     'apt-flame',  'Practised three days running'],
    ['Score 80+',    $anyScore80,                 'apt-star',   'Scored 80% or more in a test'],
    ['All-Rounder',  $skillsAttempted >= 6,       'apt-pie',    'Attempted every skill category'],
    ['Score 90+',    $anyScore90,                 'apt-star',   'Scored 90% or more in a test'],
    ['7-Day Streak', $summary['streak'] >= 7,     'apt-flame',  'Reached a 7-day practice streak'],
    ['Verified',     $anyVerified,                'apt-shield', 'Passed an official assessment'],
];
$achWon = count(array_filter($ach, static fn ($a) => $a[1]));

$flashError = session()->getFlashdata('error');
?>

<?= $this->section('styles') ?>
<style>
.apt-page{
  --brand:#0861A9;--brand-dark:#064A85;--brand-deep:#0A2F57;--brand-light:#E6F0F8;
  --accent:#ED9020;--accent-dark:#C8770E;--accent-light:#FDF1E0;
  --text:#141926;--muted:#5b6577;--bg:#f5f7fb;--white:#fff;--border:#e2e8f2;
  --success:#16a34a;--success-light:#e8f7ee;--danger:#dc2626;--danger-light:#fdeaea;
  --radius:10px;--radius-lg:14px;--shadow:0 2px 14px rgba(10,47,87,.08);--shadow-lg:0 14px 40px rgba(10,47,87,.16);--transition:.18s ease;
  display:flex;flex-direction:column;gap:clamp(16px,2.2vw,24px);padding:clamp(16px,3vw,30px);color:var(--text);font-family:'Inter','Segoe UI',system-ui,sans-serif;max-width:1240px;margin:0 auto;width:100%;
}
.apt-page *{box-sizing:border-box}
.apt-page h1,.apt-page h2,.apt-page h3{font-family:'Sora','Inter',sans-serif;letter-spacing:-.02em}
.apt-page a{text-decoration:none;color:var(--brand)}
.apt-page svg{display:block}

/* flash */
.apt-flash{display:flex;align-items:center;gap:9px;background:var(--danger-light);border:1px solid #f5c2c2;color:#b42318;font-size:.85rem;font-weight:500;padding:12px 16px;border-radius:12px}
.apt-flash svg{width:17px;height:17px;flex-shrink:0}

/* buttons */
.apt-page .btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:11px 20px;border-radius:8px;font-size:.86rem;font-weight:600;cursor:pointer;border:1.5px solid transparent;transition:var(--transition);text-decoration:none;min-height:44px;white-space:nowrap}
.apt-page .btn svg{width:16px;height:16px;flex-shrink:0}
.apt-page .btn-primary{background:var(--brand);color:#fff;border-color:var(--brand)}
.apt-page .btn-primary:hover{background:var(--brand-dark);border-color:var(--brand-dark)}
.apt-page .btn-outline{background:var(--white);color:var(--brand);border-color:var(--border)}
.apt-page .btn-outline:hover{background:var(--brand);color:#fff;border-color:var(--brand)}
.apt-page .btn-accent{background:var(--accent);color:var(--brand-deep);border-color:var(--accent)}
.apt-page .btn-accent:hover{background:var(--accent-dark);border-color:var(--accent-dark);color:var(--brand-deep)}
.apt-page .btn-sm{padding:8px 14px;font-size:.78rem;min-height:38px}
.pill{display:inline-flex;align-items:center;gap:5px;font-size:.66rem;font-weight:700;padding:4px 11px;border-radius:20px;letter-spacing:.02em;white-space:nowrap}
.pill svg{width:11px;height:11px}
.pill--pending{background:var(--accent-light);color:var(--accent-dark)}
.pill--brand{background:var(--brand-light);color:var(--brand)}
.pill--success{background:var(--success-light);color:var(--success)}
.pill--muted{background:var(--bg);color:var(--muted)}

/* cards + section heads */
.card{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden}
.card-head{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:16px 20px;border-bottom:1px solid var(--border);flex-wrap:wrap}
.card-title{display:inline-flex;align-items:center;gap:9px;font-family:'Sora',sans-serif;font-weight:700;font-size:.94rem;color:var(--brand-deep)}
.card-title svg{width:16px;height:16px;color:var(--brand)}
.card-body{padding:18px 20px}
.card-link{font-size:.76rem;font-weight:600;color:var(--brand);display:inline-flex;align-items:center;gap:4px}
.card-link svg{width:13px;height:13px}
.sec-head{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px}
.sec-head h2{font-size:clamp(1.05rem,2vw,1.3rem);font-weight:800;color:var(--brand-deep);display:flex;align-items:center;gap:10px}
.sec-head h2 svg{width:20px;height:20px;color:var(--brand)}
.sec-head p{font-size:.8rem;color:var(--muted);margin-top:2px}

/* hero */
.apt-hero{position:relative;overflow:hidden;border-radius:var(--radius-lg);color:#fff;padding:clamp(22px,3.2vw,34px);background:radial-gradient(ellipse 60% 90% at 88% 8%,rgba(237,144,32,.22) 0%,transparent 55%),linear-gradient(150deg,#0A2F57 0%,#064A85 55%,#0861A9 100%);box-shadow:var(--shadow)}
.apt-hero::before{content:'';position:absolute;inset:0;pointer-events:none;opacity:.5;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:44px 44px;-webkit-mask-image:radial-gradient(ellipse 90% 90% at 70% 20%,#000 25%,transparent 80%);mask-image:radial-gradient(ellipse 90% 90% at 70% 20%,#000 25%,transparent 80%)}
.hero-grid{position:relative;display:grid;grid-template-columns:1.35fr 1fr;gap:clamp(20px,3vw,36px);align-items:stretch}
@media(max-width:960px){.hero-grid{grid-template-columns:1fr}}
.hb{display:inline-flex;align-items:center;gap:7px;font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:5px 13px;border-radius:20px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.25);color:rgba(255,255,255,.9);margin-bottom:14px}
.hb svg{width:12px;height:12px;color:var(--accent)}
.apt-hero h1{font-size:clamp(1.5rem,3.1vw,2.15rem);font-weight:800;line-height:1.15;margin-bottom:9px;color:#fff}
.apt-hero h1 span{color:var(--accent)}
.hero-sub{font-size:.9rem;color:rgba(255,255,255,.85);max-width:520px;margin-bottom:20px}
.hero-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;max-width:540px}
@media(max-width:560px){.hero-stats{grid-template-columns:1fr 1fr}}
.hstat{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.16);border-radius:12px;padding:12px 14px;backdrop-filter:blur(8px)}
.hstat i{font-style:normal;font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.55);display:flex;align-items:center;gap:6px}
.hstat i svg{width:12px;height:12px;color:var(--accent)}
.hstat b{font-family:'Sora',sans-serif;font-weight:800;font-size:1.15rem;display:block;margin-top:3px;color:#fff}
.hstat b small{font-family:'Inter',sans-serif;font-weight:500;font-size:.66rem;color:rgba(255,255,255,.6)}
.entry-col{display:flex;flex-direction:column;gap:12px;position:relative}
.entry{position:relative;border-radius:14px;padding:17px 18px;display:flex;align-items:center;gap:14px;overflow:hidden;transition:transform .18s ease,box-shadow .18s ease}
.entry:hover{transform:translateY(-2px)}
.entry-ic{width:46px;height:46px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.entry-ic svg{width:21px;height:21px}
.entry-info{flex:1;min-width:0}
.entry-info b{display:block;font-family:'Sora',sans-serif;font-weight:800;font-size:.98rem;line-height:1.3}
.entry-info p{font-size:.74rem;line-height:1.5;margin-top:2px}
.entry-arrow{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:transform .18s ease}
.entry:hover .entry-arrow{transform:translateX(3px)}
.entry-arrow svg{width:15px;height:15px}
.entry--practice{background:rgba(255,255,255,.96);border:1px solid rgba(255,255,255,.4);box-shadow:0 8px 24px rgba(6,25,48,.25)}
.entry--practice .entry-ic{background:var(--brand-light);color:var(--brand)}
.entry--practice b{color:var(--brand-deep)}.entry--practice p{color:var(--muted)}
.entry--practice .entry-arrow{background:var(--brand-light);color:var(--brand)}
.entry--official{background:linear-gradient(120deg,var(--accent) 0%,#f2a437 60%,var(--accent) 100%);border:1.5px solid var(--accent-dark);box-shadow:0 10px 26px rgba(237,144,32,.4);padding-top:34px}
.entry--official .entry-ic{background:rgba(10,47,87,.14);color:var(--brand-deep)}
.entry--official b,.entry--official p{color:var(--brand-deep)}.entry--official p{opacity:.85}
.entry--official .entry-arrow{background:rgba(10,47,87,.14);color:var(--brand-deep)}
.entry-tag{position:absolute;top:10px;right:12px;font-size:.6rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;background:var(--brand-deep);color:#fff;border-radius:20px;padding:4px 11px}

/* daily */
.daily{display:grid;grid-template-columns:auto 1fr auto;gap:18px;align-items:center;background:linear-gradient(135deg,#fdf6ea,#fff 55%);border:1px solid #f3d9ae;border-radius:var(--radius-lg);padding:18px 20px;position:relative;overflow:hidden}
.daily::after{content:'';position:absolute;top:-40px;right:-40px;width:150px;height:150px;border-radius:50%;background:radial-gradient(circle,rgba(237,144,32,.16),transparent 70%)}
@media(max-width:760px){.daily{grid-template-columns:auto 1fr}.daily .btn{grid-column:1/-1}}
.daily-ic{width:54px;height:54px;border-radius:15px;background:linear-gradient(135deg,var(--accent),var(--accent-dark));color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 18px rgba(237,144,32,.35)}
.daily-ic svg{width:24px;height:24px}
.daily-info b{display:flex;align-items:center;gap:9px;font-family:'Sora',sans-serif;font-weight:800;font-size:1rem;color:var(--brand-deep);flex-wrap:wrap}
.daily-info p{font-size:.78rem;color:var(--muted);margin-top:3px}
.daily-count{font-family:'Sora',sans-serif;font-weight:700;color:var(--accent-dark)}
.daily .btn{position:relative;z-index:1}

/* filters */
.filters{display:flex;gap:7px;flex-wrap:wrap}
.fchip{padding:8px 15px;min-height:40px;border:1.5px solid var(--border);border-radius:20px;background:#fff;font-size:.74rem;font-weight:600;color:var(--muted);cursor:pointer;transition:var(--transition)}
.fchip:hover{border-color:var(--brand);color:var(--brand)}
.fchip[aria-pressed="true"]{background:var(--brand-light);border-color:var(--brand);color:var(--brand)}

/* role / track cards */
.trk-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(10px,1.4vw,16px)}
@media(max-width:1100px){.trk-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:620px){.trk-grid{grid-template-columns:1fr}}
.trk{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:18px;transition:var(--transition);display:flex;flex-direction:column;gap:11px}
.trk:hover{box-shadow:var(--shadow);transform:translateY(-3px);border-color:#cfe2f2}
.role-tags{display:flex;gap:7px;flex-wrap:wrap}
.role-name{font-family:'Sora',sans-serif;font-weight:700;font-size:.94rem;color:var(--brand-deep);display:block}
.role-desc{font-size:.74rem;color:var(--muted);line-height:1.55;margin-top:3px}
.role-status{display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;margin-top:auto}
.role-status i{font-style:normal;font-size:.66rem;font-weight:600;color:var(--muted)}
.role-actions{display:flex;gap:8px}
.role-actions .btn{flex:1}

/* skill category cards */
.cat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(10px,1.4vw,16px)}
@media(max-width:1100px){.cat-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:620px){.cat-grid{grid-template-columns:1fr}}
.cat{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:18px 18px 16px;display:flex;flex-direction:column;gap:12px;transition:var(--transition)}
.cat:hover{box-shadow:var(--shadow);transform:translateY(-3px);border-color:#cfe2f2}
.cat-top{display:flex;align-items:center;gap:12px}
.cat-ic{width:42px;height:42px;border-radius:12px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:transform .25s ease}
.cat:hover .cat-ic{transform:scale(1.1) rotate(-5deg)}
.cat-ic svg{width:19px;height:19px}
.cat-top b{font-family:'Sora',sans-serif;font-weight:700;font-size:.92rem;color:var(--brand-deep);display:block}
.cat-top i{font-style:normal;font-size:.68rem;color:var(--muted)}
.cat-best{margin-left:auto;text-align:right;flex-shrink:0}
.cat-best b{font-family:'Sora',sans-serif;font-weight:800;font-size:1rem;color:var(--brand)}
.cat-best i{font-style:normal;font-size:.58rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:block}
.lvls{display:flex;gap:6px}
.lvls button{flex:1;padding:7px 6px;min-height:36px;border:1.5px solid var(--border);border-radius:8px;background:#fff;font-size:.68rem;font-weight:700;color:var(--muted);cursor:pointer;transition:var(--transition)}
.lvls button:hover{border-color:var(--brand);color:var(--brand)}
.lvls button[aria-pressed="true"]{background:var(--brand-light);border-color:var(--brand);color:var(--brand)}
.cat-foot{display:flex;gap:8px}
.cat-foot .btn{flex:1}

/* analytics */
.ana-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.4fr);gap:clamp(14px,1.8vw,20px);align-items:stretch}
@media(max-width:980px){.ana-grid{grid-template-columns:1fr}}
.ring-wrap{display:flex;flex-direction:column;align-items:center;gap:10px;padding:6px 0}
.big-ring{position:relative;width:150px;height:150px}
.big-ring svg{width:150px;height:150px;transform:rotate(-90deg)}
.big-ring .track{fill:none;stroke:var(--bg);stroke-width:12}
.big-ring .prog{fill:none;stroke:url(#ringgrad);stroke-width:12;stroke-linecap:round;stroke-dasharray:408;stroke-dashoffset:408;transition:stroke-dashoffset 1.1s cubic-bezier(.2,.8,.3,1)}
.big-ring .num{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:2rem;color:var(--brand-deep);line-height:1}
.big-ring .num i{font-style:normal;font-size:.6rem;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-top:3px}
.ring-note{font-size:.76rem;color:var(--muted);text-align:center;max-width:250px}
.cat-bars{display:flex;flex-direction:column;gap:12px;margin-top:14px}
.cbar i{font-style:normal;display:flex;justify-content:space-between;font-size:.74rem;font-weight:600;color:var(--brand-deep);margin-bottom:5px}
.cbar i small{color:var(--muted);font-weight:500}
.cbar .track{height:9px;border-radius:20px;background:var(--bg);overflow:hidden}
.cbar .fill{height:100%;border-radius:20px;background:linear-gradient(90deg,var(--brand-dark),var(--brand));width:0;transition:width 1s cubic-bezier(.2,.8,.3,1)}
.cbar.weak .fill{background:linear-gradient(90deg,var(--accent-dark),var(--accent))}
.trend-svg{width:100%;height:auto}
.trend-svg .grid-l{stroke:var(--border);stroke-width:1}
.trend-svg .lbl{font-family:'Inter',sans-serif;font-size:9.5px;font-weight:600;fill:var(--muted)}
.trend-svg .line{fill:none;stroke:var(--brand);stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round}
.trend-svg .area{fill:url(#trendfill);opacity:.6}
.trend-svg .pt{fill:#fff;stroke:var(--brand);stroke-width:2.5}
.trend-svg .pt--hi{fill:var(--accent);stroke:var(--accent-dark)}

/* history */
.hist-list{display:flex;flex-direction:column}
.hist{display:grid;grid-template-columns:auto 1fr auto auto;gap:13px;align-items:center;padding:13px 0;border-bottom:1px solid var(--border)}
.hist:last-child{border-bottom:none}
@media(max-width:640px){.hist{grid-template-columns:auto 1fr auto}.hist .hist-open{display:none}}
.hist-ic{width:40px;height:40px;border-radius:11px;background:var(--bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--brand);flex-shrink:0}
.hist-ic svg{width:16px;height:16px}
.hist-info b{display:block;font-size:.82rem;font-weight:600;color:var(--brand-deep);line-height:1.35}
.hist-info i{font-style:normal;font-size:.68rem;color:var(--muted)}
.score-pill{font-family:'Sora',sans-serif;font-weight:800;font-size:.86rem;padding:6px 13px;border-radius:20px;text-align:center;min-width:62px}
.score-pill.hi{background:var(--success-light);color:var(--success)}
.score-pill.mid{background:var(--brand-light);color:var(--brand)}
.score-pill.low{background:var(--accent-light);color:var(--accent-dark)}

/* empty state */
.empty{display:none;flex-direction:column;align-items:center;text-align:center;gap:10px;padding:30px 16px}
.empty.show{display:flex}
.empty-ic{width:58px;height:58px;border-radius:16px;background:var(--accent-light);color:var(--accent-dark);display:flex;align-items:center;justify-content:center}
.empty-ic svg{width:26px;height:26px}
.empty h3{font-size:.92rem;font-weight:700;color:var(--brand-deep)}
.empty p{font-size:.78rem;color:var(--muted);max-width:360px}

/* streak · badges · leaderboard */
.tri-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr) minmax(0,1.15fr);gap:clamp(14px,1.8vw,20px);align-items:start}
@media(max-width:1100px){.tri-grid{grid-template-columns:1fr 1fr}}
@media(max-width:760px){.tri-grid{grid-template-columns:1fr}}
.streak-big{display:flex;align-items:center;gap:13px}
.streak-big .flame{width:50px;height:50px;border-radius:14px;background:linear-gradient(135deg,var(--accent),var(--accent-dark));color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 7px 16px rgba(237,144,32,.35)}
.streak-big .flame svg{width:24px;height:24px}
.streak-big b{font-family:'Sora',sans-serif;font-weight:800;font-size:1.5rem;color:var(--brand-deep);line-height:1}
.streak-big i{font-style:normal;font-size:.72rem;color:var(--muted);display:block}
.week-dots{display:flex;justify-content:space-between;gap:6px;margin:14px 0 12px}
.wd{display:flex;flex-direction:column;align-items:center;gap:6px;flex:1}
.wd i{font-style:normal;font-size:.6rem;font-weight:700;color:var(--muted);text-transform:uppercase}
.wd b{width:32px;height:32px;border-radius:50%;border:2px solid var(--border);display:flex;align-items:center;justify-content:center;background:#fff;color:var(--muted)}
.wd b svg{width:14px;height:14px}
.wd.done b{background:var(--accent);border-color:var(--accent-dark);color:var(--brand-deep)}
.wd.today b{border-color:var(--accent);border-style:dashed;color:var(--accent-dark)}
.ach-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:9px}
.ach{aspect-ratio:1;border:1.5px solid var(--border);border-radius:12px;background:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;padding:6px;transition:var(--transition);text-align:center}
.ach:hover{transform:translateY(-2px);box-shadow:var(--shadow)}
.ach svg{width:20px;height:20px}
.ach span{font-size:.56rem;font-weight:700;color:var(--muted);line-height:1.25}
.ach--won{border-color:#f3d9ae;background:linear-gradient(160deg,#fdf6ea,#fff)}
.ach--won svg{color:var(--accent-dark)}.ach--won span{color:var(--accent-dark)}
.ach--locked{opacity:.5}.ach--locked svg{color:var(--muted)}
.lb-list{display:flex;flex-direction:column}
.lb{display:grid;grid-template-columns:34px 1fr auto;gap:11px;align-items:center;padding:9px 10px;border-radius:10px}
.lb .rank{font-family:'Sora',sans-serif;font-weight:800;font-size:.86rem;color:var(--muted);text-align:center}
.lb:nth-child(-n+3) .rank{color:var(--accent-dark)}
.lb-who{display:flex;align-items:center;gap:9px;min-width:0}
.lb-ava{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--brand-deep),var(--brand));color:#fff;font-family:'Sora',sans-serif;font-weight:700;font-size:.64rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.lb-who b{font-size:.8rem;font-weight:600;color:var(--brand-deep);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.lb-who i{font-style:normal;font-size:.64rem;color:var(--muted);display:block}
.lb .pts{font-family:'Sora',sans-serif;font-weight:800;font-size:.84rem;color:var(--brand)}
.lb--me{background:var(--brand-light);border:1px solid #cfe2f2}
.lb--me .lb-ava{background:linear-gradient(135deg,var(--accent),var(--accent-dark));color:var(--brand-deep)}
.lb-note{font-size:.66rem;color:var(--muted);margin-top:10px;display:flex;gap:6px;align-items:flex-start}
.lb-note svg{width:12px;height:12px;flex-shrink:0;margin-top:2px;color:var(--brand)}

/* official strip */
.official-strip{display:grid;grid-template-columns:1fr auto;gap:18px;align-items:center;border-radius:var(--radius-lg);padding:clamp(18px,2.6vw,26px);color:#fff;position:relative;overflow:hidden;background:radial-gradient(ellipse 50% 100% at 90% 50%,rgba(237,144,32,.25) 0%,transparent 60%),linear-gradient(135deg,#0A2F57,#064A85)}
@media(max-width:760px){.official-strip{grid-template-columns:1fr}}
.official-strip h2{font-size:clamp(1.05rem,2.2vw,1.35rem);font-weight:800;margin-bottom:7px;color:#fff}
.official-strip p{font-size:.82rem;color:rgba(255,255,255,.85);max-width:560px}
.official-strip ul{list-style:none;display:flex;gap:14px;flex-wrap:wrap;margin-top:12px;padding:0}
.official-strip li{display:inline-flex;align-items:center;gap:7px;font-size:.74rem;font-weight:600;color:rgba(255,255,255,.92)}
.official-strip li svg{width:14px;height:14px;color:var(--accent)}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- apt icon sprite (apt- prefixed to avoid collision with the layout's icons) -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><defs>
<symbol id="apt-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3Z"/></symbol>
<symbol id="apt-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M8 15v3M13 10v8M18 6v12"/></symbol>
<symbol id="apt-flame" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2s5 4 5 9a5 5 0 0 1-10 0c0-1.5.6-2.8 1.4-3.8.4 1.8 1.6 2.3 2.6 1.3.9-.9.5-2.4 0-3.5-.3-.7-.3-1.4 0-2 0-.6.5-1 1-1Z"/></symbol>
<symbol id="apt-play" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4l14 8-14 8V4Z"/></symbol>
<symbol id="apt-arrow-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
<symbol id="apt-target" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4"/></symbol>
<symbol id="apt-zap" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z"/></symbol>
<symbol id="apt-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M8.2 13 7 22l5-3 5 3-1.2-9"/></symbol>
<symbol id="apt-check-c" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8.5 12l2.5 2.5 4.5-5"/></symbol>
<symbol id="apt-pie" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a9 9 0 1 0 9 9h-9Z"/><path d="M12 3v9"/></symbol>
<symbol id="apt-calc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><path d="M8 6h8M8 10h.01M12 10h.01M16 10h.01M8 14h.01M12 14h.01M16 14h.01M8 18h4"/></symbol>
<symbol id="apt-type" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></symbol>
<symbol id="apt-branch" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="8" r="3"/><path d="M6 9v6M18 11a6 6 0 0 1-6 6H9"/></symbol>
<symbol id="apt-shapes" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l4 7H8l4-7Z"/><rect x="3" y="13" width="7" height="7" rx="1"/><circle cx="17" cy="16.5" r="3.5"/></symbol>
<symbol id="apt-monitor" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></symbol>
<symbol id="apt-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12c.5.5 1 1.2 1 2h6c0-.8.5-1.5 1-2a7 7 0 0 0-4-12Z"/></symbol>
<symbol id="apt-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
<symbol id="apt-trend-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l6-6 4 4 8-8M15 7h6v6"/></symbol>
<symbol id="apt-crown" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18h20M4 18l-2-9 6 5 4-8 4 8 6-5-2 9"/></symbol>
<symbol id="apt-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></symbol>
<symbol id="apt-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
<symbol id="apt-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></symbol>
<symbol id="apt-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3 6.3 6.9.6-5.2 4.5 1.6 6.7L12 16.9 5.7 20.1l1.6-6.7L2.1 8.9l6.9-.6z"/></symbol>
<symbol id="apt-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
</defs></svg>

<div class="apt-page">

<?php if ($flashError): ?>
<div class="apt-flash"><svg aria-hidden="true"><use href="#apt-info"/></svg><span><?= esc($flashError) ?></span></div>
<?php endif; ?>

<!-- ═══ HERO + ENTRY POINTS ═══ -->
<section class="apt-hero" aria-labelledby="apt-title">
  <div class="hero-grid">
    <div>
      <span class="hb"><svg aria-hidden="true"><use href="#apt-shield"/></svg> Employer-standard assessments</span>
      <h1 id="apt-title">Aptitude Test <span>Hub</span></h1>
      <p class="hero-sub">Practice the exact test formats Nigerian employers use to screen candidates — then take a verified official assessment when you're ready.</p>
      <div class="hero-stats">
        <div class="hstat"><i><svg aria-hidden="true"><use href="#apt-check-c"/></svg> Tests taken</i><b><span class="countup" data-to="<?= (int) $summary['testsTaken'] ?>">0</span></b></div>
        <div class="hstat"><i><svg aria-hidden="true"><use href="#apt-chart"/></svg> Average score</i><b><span class="countup" data-to="<?= (int) $summary['avgScore'] ?>">0</span>%</b></div>
        <div class="hstat"><i><svg aria-hidden="true"><use href="#apt-flame"/></svg> Practice streak</i><b><span class="countup" data-to="<?= (int) $summary['streak'] ?>">0</span> <small>days</small></b></div>
      </div>
    </div>
    <div class="entry-col">
      <a class="entry entry--practice" href="<?= base_url('aptitude/general-aptitude/practice') ?>">
        <span class="entry-ic" aria-hidden="true"><svg><use href="#apt-play"/></svg></span>
        <span class="entry-info"><b>Practice Test</b><p>Free, unlimited. Full answer breakdown after every attempt.</p></span>
        <span class="entry-arrow" aria-hidden="true"><svg><use href="#apt-arrow-r"/></svg></span>
      </a>
      <a class="entry entry--official" href="#role-title">
        <span class="entry-tag">Verified</span>
        <span class="entry-ic" aria-hidden="true"><svg><use href="#apt-shield"/></svg></span>
        <span class="entry-info"><b>Official Assessment</b><p>Pick your role below — proctored, anti-cheat, shareable to employers.</p></span>
        <span class="entry-arrow" aria-hidden="true"><svg><use href="#apt-arrow-r"/></svg></span>
      </a>
    </div>
  </div>
</section>

<!-- ═══ DAILY CHALLENGE ═══ -->
<section class="daily" aria-labelledby="daily-title">
  <span class="daily-ic" aria-hidden="true"><svg><use href="#apt-target"/></svg></span>
  <div class="daily-info">
    <b id="daily-title">Daily Challenge <span class="pill pill--pending"><svg aria-hidden="true"><use href="#apt-flame"/></svg> Keeps your streak</span></b>
    <p>10 mixed questions, once a day. Today's set is live — <span class="daily-count" id="daily-count">resets in 00:00:00</span>.</p>
  </div>
  <a href="<?= base_url('aptitude/daily') ?>" class="btn btn-primary"><svg aria-hidden="true"><use href="#apt-zap"/></svg> Take Today's Challenge</a>
</section>

<!-- ═══ ROLE-BASED ASSESSMENTS ═══ -->
<section aria-labelledby="role-title">
  <div class="sec-head">
    <div><h2 id="role-title"><svg aria-hidden="true"><use href="#apt-award"/></svg> Assessments by role</h2>
    <p>Filter by your field, then practice or take an official assessment.</p></div>
  </div>
  <?php if ($roleTests): ?>
  <div class="filters" role="group" aria-label="Filter by field" style="margin-bottom:14px">
    <button type="button" class="fchip" data-field="all" aria-pressed="true">All</button>
    <?php foreach ($roleFields as $slug => $name): ?>
      <button type="button" class="fchip" data-field="<?= esc($slug, 'attr') ?>" aria-pressed="false"><?= esc($name) ?></button>
    <?php endforeach; ?>
  </div>
  <div class="trk-grid" id="role-grid">
    <?php foreach ($roleTests as $t): ?>
    <div class="role-card trk" data-field="<?= esc($t['category_slug'], 'attr') ?>">
      <div class="role-tags">
        <span class="pill pill--brand"><?= esc(ucfirst($t['difficulty'])) ?></span>
        <span class="pill pill--muted"><?= esc($t['category_name']) ?></span>
      </div>
      <div>
        <b class="role-name"><?= esc($t['title']) ?></b>
        <p class="role-desc"><?= esc($t['description']) ?></p>
      </div>
      <div class="role-status">
        <?php if (!empty($t['best'])): ?>
          <span class="pill pill--success"><svg aria-hidden="true"><use href="#apt-check-c"/></svg> Best <?= (int) round($t['best']['best']) ?>%<?= !empty($t['best']['passed']) ? ' · Passed' : '' ?></span>
        <?php else: ?>
          <span class="pill pill--muted">Not attempted yet</span>
        <?php endif; ?>
        <i><?= (int) $t['num_questions'] ?> questions · <?= (int) $t['duration_mins'] ?> mins</i>
      </div>
      <div class="role-actions">
        <a class="btn btn-outline btn-sm" href="<?= base_url('aptitude/' . $t['slug'] . '/practice') ?>"><svg aria-hidden="true"><use href="#apt-play"/></svg> Practice</a>
        <a class="btn btn-accent btn-sm" href="<?= base_url('aptitude/' . $t['slug'] . '/start') ?>"><svg aria-hidden="true"><use href="#apt-shield"/></svg> Take Test</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="empty" id="role-empty">
    <span class="empty-ic"><svg aria-hidden="true"><use href="#apt-award"/></svg></span>
    <h3>No assessments in this field yet</h3>
    <p>We're adding more role-based tests regularly. Try Skill Practice below to sharpen the fundamentals in the meantime.</p>
  </div>
  <?php else: ?>
  <div class="empty show">
    <span class="empty-ic"><svg aria-hidden="true"><use href="#apt-award"/></svg></span>
    <h3>Assessments are on the way</h3>
    <p>Role-based assessments haven't been published yet. Check back soon.</p>
  </div>
  <?php endif; ?>
</section>

<!-- ═══ SKILL PRACTICE ═══ -->
<?php if ($skillTests): ?>
<section aria-labelledby="cat-title">
  <div class="sec-head">
    <div><h2 id="cat-title"><svg aria-hidden="true"><use href="#apt-pie"/></svg> Skill practice — the training ground</h2>
    <p>Free, unlimited drills in the core abilities every role test draws on. Pick a difficulty and start.</p></div>
  </div>
  <div class="cat-grid">
    <?php foreach ($skillTests as $t):
      $icon = $skillIcons[$t['slug']] ?? 'apt-bulb';
      $defaultLevel = $t['difficulty'] ?: 'intermediate';
    ?>
    <div class="cat" data-slug="<?= esc($t['slug'], 'attr') ?>">
      <div class="cat-top">
        <span class="cat-ic" aria-hidden="true"><svg><use href="#<?= $icon ?>"/></svg></span>
        <div><b><?= esc($t['title']) ?></b><i><?= esc($t['description']) ?></i></div>
        <?php if (!empty($t['best'])): ?><div class="cat-best"><b><?= (int) round($t['best']['best']) ?>%</b><i>Best</i></div><?php endif; ?>
      </div>
      <div class="lvls" role="group" aria-label="<?= esc($t['title'], 'attr') ?> difficulty">
        <?php foreach (['beginner', 'intermediate', 'advanced'] as $lvl): ?>
          <button type="button" aria-pressed="<?= $lvl === $defaultLevel ? 'true' : 'false' ?>"><?= ucfirst($lvl) ?></button>
        <?php endforeach; ?>
      </div>
      <div class="cat-foot"><a class="btn btn-primary btn-sm start-link" href="<?= base_url('aptitude/' . $t['slug'] . '/practice?level=' . $defaultLevel) ?>"><svg aria-hidden="true"><use href="#apt-play"/></svg> Start Practice</a></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ═══ ANALYTICS + TREND ═══ -->
<?php if ($summary['testsTaken'] > 0): ?>
<?php
  $ringOffset = 408 * (1 - $summary['avgScore'] / 100);
  $bars = array_slice($summary['byCategory'], 0, 6);
  // trend path
  $trend = $summary['trend'];
  $pts = [];
  foreach ($trend as $i => $v) {
      if ($v === null) continue;
      $x = 60 + $i * 68;
      $y = 210 - ($v / 100) * 180;
      $pts[] = [$x, round($y, 1), $v];
  }
?>
<div class="ana-grid">
  <section class="card" aria-labelledby="ana-title">
    <div class="card-head"><span class="card-title" id="ana-title"><svg aria-hidden="true"><use href="#apt-chart"/></svg> Score Analytics</span>
      <span class="pill pill--muted">From your <?= (int) $summary['testsTaken'] ?> attempt<?= $summary['testsTaken'] === 1 ? '' : 's' ?></span></div>
    <div class="card-body">
      <div class="ring-wrap">
        <div class="big-ring" role="img" aria-label="Overall average score <?= (int) $summary['avgScore'] ?> percent">
          <svg viewBox="0 0 150 150" aria-hidden="true">
            <defs><linearGradient id="ringgrad" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#064A85"/><stop offset="100%" stop-color="#0861A9"/></linearGradient></defs>
            <circle class="track" cx="75" cy="75" r="65"/>
            <circle class="prog" id="ring-prog" cx="75" cy="75" r="65" data-offset="<?= round($ringOffset, 1) ?>"/>
          </svg>
          <span class="num"><span class="countup" data-to="<?= (int) $summary['avgScore'] ?>">0</span>%<i>Overall avg</i></span>
        </div>
        <p class="ring-note">Averaged across every scored attempt — practice and official count equally here.</p>
      </div>
      <?php if ($bars): ?>
      <div class="cat-bars">
        <?php foreach ($bars as $b): ?>
        <div class="cbar<?= $b['score'] < 70 ? ' weak' : '' ?>"><i><?= esc($b['label']) ?> <small><?= (int) $b['score'] ?>%</small></i><div class="track"><div class="fill" data-w="<?= (int) $b['score'] ?>%"></div></div></div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="card" aria-labelledby="trend-title">
    <div class="card-head"><span class="card-title" id="trend-title"><svg aria-hidden="true"><use href="#apt-trend-up"/></svg> Progress Over Time</span>
      <span class="pill pill--muted">Weekly average</span></div>
    <div class="card-body">
      <?php if (count($pts) >= 2): ?>
      <svg class="trend-svg" viewBox="0 0 560 250" role="img" aria-label="Weekly average score trend">
        <defs><linearGradient id="trendfill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#0861A9" stop-opacity=".22"/><stop offset="100%" stop-color="#0861A9" stop-opacity="0"/></linearGradient></defs>
        <g><line class="grid-l" x1="46" y1="30" x2="540" y2="30"/><line class="grid-l" x1="46" y1="75" x2="540" y2="75"/><line class="grid-l" x1="46" y1="120" x2="540" y2="120"/><line class="grid-l" x1="46" y1="165" x2="540" y2="165"/><line class="grid-l" x1="46" y1="210" x2="540" y2="210"/></g>
        <g><text class="lbl" x="38" y="34" text-anchor="end">100</text><text class="lbl" x="38" y="124" text-anchor="end">50</text><text class="lbl" x="38" y="214" text-anchor="end">0</text></g>
        <?php
          $line = 'M' . implode(' L', array_map(fn ($p) => $p[0] . ' ' . $p[1], $pts));
          $area = $line . ' L' . end($pts)[0] . ' 210 L' . $pts[0][0] . ' 210 Z';
        ?>
        <path class="area" d="<?= $area ?>"/>
        <path class="line" d="<?= $line ?>"/>
        <?php foreach ($pts as $i => $p): ?>
          <circle class="pt<?= $i === count($pts) - 1 ? ' pt--hi' : '' ?>" cx="<?= $p[0] ?>" cy="<?= $p[1] ?>" r="<?= $i === count($pts) - 1 ? 5 : 4 ?>"/>
        <?php endforeach; ?>
      </svg>
      <?php else: ?>
      <div class="empty show"><span class="empty-ic"><svg aria-hidden="true"><use href="#apt-trend-up"/></svg></span><h3>Not enough data yet</h3><p>Take tests across a few weeks and your progress trend will appear here.</p></div>
      <?php endif; ?>
    </div>
  </section>
</div>
<?php endif; ?>

<!-- ═══ HISTORY ═══ -->
<section class="card" aria-labelledby="hist-title">
  <div class="card-head">
    <span class="card-title" id="hist-title"><svg aria-hidden="true"><use href="#apt-clock"/></svg> Assessment History</span>
    <?php if ($history): ?>
    <div class="filters" role="group" aria-label="Filter history">
      <button type="button" class="fchip" data-filter="all" aria-pressed="true">All</button>
      <button type="button" class="fchip" data-filter="practice" aria-pressed="false">Practice</button>
      <button type="button" class="fchip" data-filter="official" aria-pressed="false">Official</button>
    </div>
    <?php endif; ?>
  </div>
  <div class="card-body">
    <?php if ($history): ?>
    <div class="hist-list" id="hist-list">
      <?php foreach ($history as $h):
        $pct = (int) round($h['score_pct']);
        $isOfficial = $h['mode'] === 'official';
      ?>
      <a class="hist" data-type="<?= esc($h['mode'], 'attr') ?>" href="<?= base_url('aptitude/result/' . $h['id']) ?>">
        <span class="hist-ic" aria-hidden="true"><svg><use href="#<?= $isOfficial ? 'apt-award' : 'apt-monitor' ?>"/></svg></span>
        <span class="hist-info"><b><?= esc($h['title']) ?></b><i><?= $isOfficial ? 'Official · Verified' : 'Practice' ?> · <?= (int) $h['num_total'] ?> questions · <?= esc($fmtDate($h['submitted_at'])) ?></i></span>
        <span class="score-pill <?= $scoreClass($pct) ?>"><?= $pct ?>%</span>
        <span class="hist-open card-link">View <svg aria-hidden="true"><use href="#apt-arrow-r"/></svg></span>
      </a>
      <?php endforeach; ?>
    </div>
    <div class="empty" id="hist-empty">
      <span class="empty-ic"><svg aria-hidden="true"><use href="#apt-shield"/></svg></span>
      <h3>Nothing matches this filter</h3>
      <p>Take an assessment of this type and it will show up here.</p>
    </div>
    <?php else: ?>
    <div class="empty show">
      <span class="empty-ic"><svg aria-hidden="true"><use href="#apt-clock"/></svg></span>
      <h3>No attempts yet</h3>
      <p>Your practice and official assessment history will appear here once you take your first test.</p>
      <a href="<?= base_url('aptitude/general-aptitude/practice') ?>" class="btn btn-primary btn-sm"><svg aria-hidden="true"><use href="#apt-play"/></svg> Start a Practice Test</a>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══ STREAK · BADGES · LEADERBOARD ═══ -->
<div class="tri-grid">
  <section class="card" aria-labelledby="streak-title">
    <div class="card-head"><span class="card-title" id="streak-title"><svg aria-hidden="true"><use href="#apt-flame"/></svg> Practice Streak</span></div>
    <div class="card-body">
      <div class="streak-big">
        <span class="flame" aria-hidden="true"><svg><use href="#apt-flame"/></svg></span>
        <div><b><?= (int) $summary['streak'] ?> day<?= $summary['streak'] === 1 ? '' : 's' ?></b><i>Keep practising to grow it</i></div>
      </div>
      <div class="week-dots" id="week-dots" aria-label="This week's practice days"></div>
      <p style="font-size:.76rem;color:var(--muted)">One practice test or daily challenge a day keeps the streak alive.</p>
    </div>
  </section>

  <section class="card" aria-labelledby="badge-title">
    <div class="card-head"><span class="card-title" id="badge-title"><svg aria-hidden="true"><use href="#apt-award"/></svg> Achievements</span>
      <span class="pill pill--brand"><?= $achWon ?> of <?= count($ach) ?></span></div>
    <div class="card-body">
      <div class="ach-grid">
        <?php foreach ($ach as $a): ?>
        <div class="ach <?= $a[1] ? 'ach--won' : 'ach--locked' ?>" title="<?= $a[1] ? esc($a[0]) . ' — ' . esc($a[3]) : 'Locked — ' . esc($a[3]) ?>">
          <svg aria-hidden="true"><use href="#<?= $a[1] ? $a[2] : 'apt-lock' ?>"/></svg><span><?= esc($a[0]) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="card" aria-labelledby="lb-title">
    <div class="card-head"><span class="card-title" id="lb-title"><svg aria-hidden="true"><use href="#apt-crown"/></svg> Weekly Leaderboard</span>
      <span class="pill pill--pending">Resets Sunday</span></div>
    <div class="card-body">
      <?php if ($leaderboard): ?>
      <div class="lb-list">
        <?php foreach ($leaderboard as $row):
          $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $row['name']), 0, 2)) ?: 'JR';
        ?>
        <div class="lb<?= $row['isMe'] ? ' lb--me' : '' ?>"><span class="rank"><?= (int) $row['rank'] ?></span><span class="lb-who"><span class="lb-ava"><?= esc($initials) ?></span><span><b><?= esc($row['name']) ?></b><i><?= (int) $row['tests'] ?> tests this week</i></span></span><span class="pts"><?= number_format($row['points']) ?> pts</span></div>
        <?php endforeach; ?>
      </div>
      <p class="lb-note"><svg aria-hidden="true"><use href="#apt-info"/></svg> Points come from completed tests and scores. Only first name and last initial are shown.</p>
      <?php else: ?>
      <div class="empty show"><span class="empty-ic"><svg aria-hidden="true"><use href="#apt-crown"/></svg></span><h3>No scores this week</h3><p>Be the first on the board — take a test to earn points this week.</p></div>
      <?php endif; ?>
    </div>
  </section>
</div>

<!-- ═══ OFFICIAL DIFFERENTIATOR ═══ -->
<section class="official-strip" aria-labelledby="off-title">
  <div>
    <h2 id="off-title">Ready to make it count?</h2>
    <p>Official assessments run under exam conditions — tab-switch detection and a verified result you can attach to job applications on JobberRecruit.</p>
    <ul>
      <li><svg aria-hidden="true"><use href="#apt-shield"/></svg> Proctored &amp; anti-cheat</li>
      <li><svg aria-hidden="true"><use href="#apt-check-c"/></svg> Verified result</li>
      <li><svg aria-hidden="true"><use href="#apt-eye"/></svg> Visible to employers you apply to</li>
    </ul>
  </div>
  <a href="<?= base_url('aptitude/general-aptitude/start') ?>" class="btn btn-accent" style="min-height:52px;font-size:.95rem"><svg aria-hidden="true"><use href="#apt-shield"/></svg> Take Official Assessment</a>
</section>

</div><!-- /.apt-page -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function(){
  'use strict';
  var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* count-up numbers */
  document.querySelectorAll('.apt-page .countup').forEach(function(el){
    var to = parseInt(el.dataset.to,10)||0;
    if(reduced){el.textContent=to;return;}
    var t0=null,dur=900;
    (function step(ts){if(!t0)t0=ts;var p=Math.min(1,(ts-t0)/dur);el.textContent=Math.round(to*(1-Math.pow(1-p,3)));if(p<1)requestAnimationFrame(step);})(performance.now());
  });

  /* ring + bars animation */
  requestAnimationFrame(function(){requestAnimationFrame(function(){
    var ring=document.getElementById('ring-prog');
    if(ring)ring.style.strokeDashoffset=ring.dataset.offset;
    document.querySelectorAll('.apt-page .cbar .fill').forEach(function(f){f.style.width=f.dataset.w;});
  });});

  /* daily countdown to local midnight */
  function pad(n){return (n<10?'0':'')+n;}
  function tick(){
    var now=new Date(),mid=new Date(now);mid.setHours(24,0,0,0);
    var s=Math.max(0,Math.floor((mid-now)/1000));
    var el=document.getElementById('daily-count');
    if(el)el.textContent='resets in '+pad(Math.floor(s/3600))+':'+pad(Math.floor(s%3600/60))+':'+pad(s%60);
  }
  tick();setInterval(tick,1000);

  /* week dots — last 7 days, today marked (no fake ticks) */
  var wd=document.getElementById('week-dots');
  if(wd){
    var labels=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],html='';
    for(var i=6;i>=0;i--){
      var d=new Date();d.setDate(d.getDate()-i);
      var isToday=i===0;
      html+='<div class="wd'+(isToday?' today':'')+'"><i>'+labels[d.getDay()]+'</i><b>'+(isToday?'·':'')+'</b></div>';
    }
    wd.innerHTML=html;
  }

  /* skill difficulty pills rewrite the practice link's ?level= */
  document.querySelectorAll('.apt-page .cat').forEach(function(card){
    var slug=card.dataset.slug,link=card.querySelector('.start-link');
    card.querySelectorAll('.lvls button').forEach(function(b){
      b.addEventListener('click',function(){
        card.querySelectorAll('.lvls button').forEach(function(x){x.setAttribute('aria-pressed','false');});
        this.setAttribute('aria-pressed','true');
        var level=this.textContent.trim().toLowerCase();
        if(link)link.setAttribute('href','<?= base_url('aptitude') ?>/'+slug+'/practice?level='+level);
      });
    });
  });

  /* role field filter */
  var roleCards=document.querySelectorAll('#role-grid .role-card'),roleEmpty=document.getElementById('role-empty');
  document.querySelectorAll('.fchip[data-field]').forEach(function(chip){
    chip.addEventListener('click',function(){
      document.querySelectorAll('.fchip[data-field]').forEach(function(c){c.setAttribute('aria-pressed','false');});
      this.setAttribute('aria-pressed','true');
      var f=this.dataset.field,visible=0;
      roleCards.forEach(function(c){var show=f==='all'||c.dataset.field===f;c.style.display=show?'':'none';if(show)visible++;});
      if(roleEmpty)roleEmpty.classList.toggle('show',visible===0);
    });
  });

  /* history type filter */
  var rows=document.querySelectorAll('#hist-list .hist'),histEmpty=document.getElementById('hist-empty');
  document.querySelectorAll('.fchip[data-filter]').forEach(function(chip){
    chip.addEventListener('click',function(){
      document.querySelectorAll('.fchip[data-filter]').forEach(function(c){c.setAttribute('aria-pressed','false');});
      this.setAttribute('aria-pressed','true');
      var f=this.dataset.filter,visible=0;
      rows.forEach(function(r){var show=f==='all'||r.dataset.type===f;r.style.display=show?'':'none';if(show)visible++;});
      if(histEmpty)histEmpty.classList.toggle('show',visible===0);
    });
  });
})();
</script>
<?= $this->endSection() ?>
