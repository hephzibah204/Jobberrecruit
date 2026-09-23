<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>
img{max-width:100%;height:auto;display:block}
svg{flex-shrink:0}
.container{max-width:1160px;margin:0 auto;padding:0 20px}
.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
.section-label{display:inline-flex;align-items:center;gap:7px;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--brand);background:var(--brand-light);padding:5px 13px;border-radius:20px;margin-bottom:14px}
.section-label svg{width:13px;height:13px}
.section-title{font-size:clamp(1.6rem,2.9vw,2.25rem);font-weight:800;line-height:1.15;margin-bottom:12px}
.section-title span{color:var(--brand)}
.section-sub{color:var(--muted);font-size:.95rem;max-width:560px}

/* ===== CONFIRMATION PAGE ===== */
.conf-hero{background:radial-gradient(ellipse 70% 60% at 80% 10%,rgba(237,144,32,.16) 0%,transparent 55%),linear-gradient(160deg,#0A2F57 0%,#064A85 60%,#0D609E 100%);color:#fff;padding:44px 0 96px;position:relative;overflow:hidden}
.conf-hero .hero-grid{position:absolute;inset:0;pointer-events:none;opacity:.4;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:46px 46px;-webkit-mask-image:radial-gradient(ellipse 90% 80% at 50% 20%,#000 30%,transparent 80%);mask-image:radial-gradient(ellipse 90% 80% at 50% 20%,#000 30%,transparent 80%)}

.breadcrumb{position:relative;z-index:1;display:flex;align-items:center;gap:8px;font-size:.82rem;color:rgba(255,255,255,.7);margin-bottom:28px;flex-wrap:wrap}
.breadcrumb a{color:rgba(255,255,255,.8);text-decoration:none;transition:var(--transition);font-weight:500}
.breadcrumb a:hover{color:#fff;text-decoration:underline}
.breadcrumb svg{width:14px;height:14px;opacity:.7;color:#fff}
.breadcrumb span[aria-current]{color:#fff;font-weight:700}

.conf-head{position:relative;z-index:1;text-align:center;max-width:640px;margin:0 auto}
.conf-tick{width:68px;height:68px;border-radius:50%;background:rgba(22,163,74,.2);border:2px solid rgba(94,233,160,.6);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;animation:tickpop .45s cubic-bezier(.2,.9,.3,1.3) both;box-shadow:0 0 24px rgba(22,163,74,.3)}
.conf-tick svg{width:34px;height:34px;color:#5ee9a0}
@keyframes tickpop{0%{transform:scale(.5);opacity:0}100%{transform:scale(1);opacity:1}}

.conf-head h1{font-size:clamp(1.75rem,3.4vw,2.4rem);font-weight:800;line-height:1.15;margin-bottom:12px;color:#fff}
.conf-head h1 span{color:var(--accent)}
.conf-head p{font-size:.96rem;color:rgba(255,255,255,.8);line-height:1.65}
.conf-head p strong{color:#fff;font-weight:700}

/* main layout */
.conf-main{padding:0 0 72px;margin-top:-60px;position:relative;z-index:5}
.conf-layout{display:grid;grid-template-columns:1.5fr 1fr;gap:24px;align-items:start}

/* ticket card */
.ticket{background:var(--white);border:1px solid rgba(226,232,240,.9);border-radius:18px;box-shadow:0 20px 45px -10px rgba(0,0,0,.15);overflow:hidden}
.ticket-top{background:linear-gradient(135deg,#064A85 0%,#0D609E 100%);color:#fff;padding:24px 26px;position:relative}
.ticket-top::after{content:'';position:absolute;left:0;right:0;bottom:-11px;height:22px;background:radial-gradient(circle 11px at 11px 50%,transparent 11px,var(--white) 11px) repeat-x;background-size:30px 22px;background-position:-4px 0}
.ticket-status{display:inline-flex;align-items:center;gap:6px;background:rgba(94,233,160,.2);color:#5ee9a0;font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;padding:4px 12px;border-radius:20px;margin-bottom:14px;border:1px solid rgba(94,233,160,.3)}
.ticket-title{font-family:'Sora',sans-serif;font-size:1.22rem;font-weight:800;line-height:1.3;margin-bottom:14px;color:#fff}
.ticket-spk{display:flex;align-items:center;gap:12px}
.ticket-av{width:44px;height:44px;border-radius:50%;border:2.5px solid rgba(255,255,255,.4);background:var(--accent);color:var(--brand-deep);font-family:'Sora',sans-serif;font-weight:800;font-size:.9rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ticket-spk-name{font-weight:700;font-size:.9rem;color:#fff}
.ticket-spk-role{font-size:.76rem;color:rgba(255,255,255,.75)}

.ticket-body{padding:28px 24px 24px}
.ticket-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px 20px;margin-bottom:22px}
.tg-item{display:flex;gap:12px;align-items:flex-start}
.tg-ic{width:40px;height:40px;border-radius:10px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.tg-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:2px}
.tg-val{font-size:.88rem;font-weight:600;color:var(--text);line-height:1.4}
.tg-val small{display:block;font-weight:400;color:var(--muted);font-size:.76rem;margin-top:1px}
.prov-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:13px;font-size:.74rem;font-weight:700;background:var(--brand-light);color:var(--brand);margin-top:3px}

/* join link box */
.join-box{background:var(--bg);border:1.5px dashed #cbd5e1;border-radius:12px;padding:16px;margin-bottom:20px}
.join-box-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);margin-bottom:8px;display:flex;align-items:center;gap:6px}
.join-link-row{display:flex;gap:8px;align-items:center}
.join-link-input{flex:1;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-family:'Inter',sans-serif;font-size:.84rem;color:var(--text);background:var(--white);min-width:0;outline:none}
.copy-btn{display:inline-flex;align-items:center;gap:6px;padding:10px 16px;border-radius:8px;background:var(--brand);color:#fff;border:none;font-family:'Inter',sans-serif;font-size:.82rem;font-weight:600;cursor:pointer;transition:var(--transition);white-space:nowrap;flex-shrink:0}
.copy-btn:hover{background:var(--brand-dark)}
.join-hint{font-size:.75rem;color:var(--muted);margin-top:9px;display:flex;align-items:center;gap:6px}

/* action buttons */
.ticket-actions{display:flex;gap:12px;flex-wrap:wrap}

/* countdown strip */
.cd-strip{background:linear-gradient(135deg,#0A2F57 0%,#0D609E 100%);border-radius:14px;padding:20px;text-align:center;color:#fff;box-shadow:0 10px 25px -5px rgba(10,47,87,.25)}
.cd-strip-label{font-size:.74rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.75);margin-bottom:12px}
.cd-strip-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}
.cd-cell{background:rgba(255,255,255,.1);border-radius:10px;padding:10px 4px}
.cd-cell-n{font-family:'Sora',sans-serif;font-size:1.5rem;font-weight:800;line-height:1;color:#fff}
.cd-cell-l{font-size:.62rem;color:rgba(255,255,255,.6);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-top:4px}

/* side cards */
.side-card{background:var(--white);border:1px solid rgba(226,232,240,.9);border-radius:14px;padding:22px;margin-bottom:20px;box-shadow:0 4px 15px -2px rgba(0,0,0,.04)}
.side-card h3{font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;margin-bottom:14px;display:flex;align-items:center;gap:8px;color:var(--text)}
.side-card h3 svg{width:18px;height:18px;color:var(--brand)}
.next-list{list-style:none;display:flex;flex-direction:column;gap:14px;padding:0;margin:0}
.next-list li{display:flex;gap:12px;font-size:.84rem;color:var(--muted);line-height:1.55}
.next-num{width:24px;height:24px;border-radius:50%;background:var(--brand-light);color:var(--brand);font-size:.74rem;font-weight:800;font-family:'Sora',sans-serif;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.next-list li strong{color:var(--text);font-weight:600}

/* reminder toggles */
.rem-row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:11px 0;border-bottom:1px solid var(--border)}
.rem-row:last-child{border-bottom:none;padding-bottom:0}
.rem-info{display:flex;align-items:center;gap:10px}
.rem-ic{width:34px;height:34px;border-radius:9px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.rem-txt strong{display:block;font-size:.84rem;font-weight:600;color:var(--text)}
.rem-txt span{font-size:.74rem;color:var(--muted)}

/* toggle switch */
.switch{position:relative;width:42px;height:24px;flex-shrink:0}
.switch input{opacity:0;width:0;height:0;position:absolute}
.switch-slider{position:absolute;inset:0;background:#cbd5e1;border-radius:24px;cursor:pointer;transition:var(--transition)}
.switch-slider::before{content:'';position:absolute;width:18px;height:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:var(--transition);box-shadow:0 1px 3px rgba(0,0,0,.2)}
.switch input:checked+.switch-slider{background:var(--success)}
.switch input:checked+.switch-slider::before{transform:translateX(18px)}

/* calendar buttons */
.cal-btns{display:flex;flex-direction:column;gap:9px}
.cal-btn{display:flex;align-items:center;gap:10px;padding:11px 14px;border:1px solid var(--border);border-radius:9px;background:var(--white);font-family:'Inter',sans-serif;font-size:.84rem;font-weight:600;color:var(--text);cursor:pointer;transition:var(--transition);text-decoration:none}
.cal-btn:hover{border-color:var(--brand);background:var(--brand-light);text-decoration:none;color:var(--brand)}
.cal-btn svg{width:16px;height:16px;color:var(--brand)}
.cal-btn .chev{margin-left:auto;width:14px;height:14px;color:var(--muted)}

/* recommended */
.reco-section{background:var(--white);padding:60px 0;border-top:1px solid var(--border)}
.reco-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:28px}
.reco-card{display:flex;gap:13px;padding:16px;border:1px solid var(--border);border-radius:12px;transition:var(--transition);text-decoration:none}
.reco-card:hover{border-color:var(--brand);box-shadow:var(--shadow);text-decoration:none;transform:translateY(-2px)}
.reco-thumb{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0}
.reco-cat{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--brand);margin-bottom:3px}
.reco-title{font-family:'Sora',sans-serif;font-size:.88rem;font-weight:700;color:var(--text);line-height:1.3;margin-bottom:5px}
.reco-meta{font-size:.76rem;color:var(--muted)}
.t-blue{background:linear-gradient(135deg,#0A2F57,#0D609E)}
.t-orange{background:linear-gradient(135deg,#0A2F57,#ED9020)}
.t-teal{background:linear-gradient(135deg,#0A2F57,#0891b2)}

/* toast */
.toast-msg{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--brand-deep);color:#fff;padding:12px 22px;border-radius:10px;font-size:.85rem;font-weight:600;box-shadow:0 10px 25px rgba(0,0,0,.3);display:flex;align-items:center;gap:9px;z-index:1200;opacity:0;pointer-events:none;transition:opacity .25s,transform .25s;max-width:90vw}
.toast-msg.show{opacity:1;transform:translateX(-50%) translateY(0)}

@media(max-width:860px){
  .conf-layout{grid-template-columns:1fr}
  .reco-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:580px){
  .ticket-grid{grid-template-columns:1fr}
  .reco-grid{grid-template-columns:1fr}
  .conf-hero{padding:32px 0 84px}
  .reco-section{padding:44px 0}
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$webinarTitle = !empty($webinar->title) ? $webinar->title : 'Free Career Webinar';
$speakerName = !empty($webinar->speaker_name) ? $webinar->speaker_name : 'Industry Guest Speaker';
$speakerRole = 'Industry Guest Speaker · JobberRecruit';
$scheduledTime = !empty($webinar->scheduled_at) ? strtotime($webinar->scheduled_at) : (time() + 86400 * 3);
$dateFormatted = date('l, j F Y', $scheduledTime);
$timeFormatted = date('h:i A', $scheduledTime) . ' WAT';

$initials = '';
$parts = explode(' ', trim($speakerName));
foreach ($parts as $p) {
    if (!empty($p)) $initials .= strtoupper($p[0]);
    if (strlen($initials) >= 2) break;
}
if (empty($initials)) $initials = 'JR';

// Platform Provider
$meetingLink = !empty($webinar->meeting_link) ? strtolower($webinar->meeting_link) : '';
$platformName = 'Google Meet';
if (strpos($meetingLink, 'zoom.us') !== false) $platformName = 'Zoom';
elseif (strpos($meetingLink, 'teams.microsoft.com') !== false) $platformName = 'Microsoft Teams';

// Calendar URL
$calStart = gmdate('Ymd\THis\Z', $scheduledTime);
$calEnd = gmdate('Ymd\THis\Z', $scheduledTime + 5400); // 90 minutes
$googleCalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
    . '&text=' . urlencode($webinarTitle)
    . '&dates=' . $calStart . '/' . $calEnd
    . '&details=' . urlencode('JobberRecruit live webinar with ' . $speakerName . '. Join URL: ' . $join_url)
    . '&location=' . urlencode($platformName);
?>
<main id="main">

<!-- CONFIRMATION HERO -->
<section class="conf-hero">
  <span class="hero-grid" aria-hidden="true"></span>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= base_url('training/webinars') ?>">Webinars</a>
      <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
      <a href="<?= base_url('training/webinars') ?>"><?= esc($webinarTitle) ?></a>
      <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
      <span aria-current="page">Registered</span>
    </nav>
    <div class="conf-head">
      <div class="conf-tick" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></div>
      <h1>You&rsquo;re <span>registered</span>, <?= esc($user_first_name) ?>!</h1>
      <p>Your seat for <strong><?= esc($webinarTitle) ?></strong> is confirmed. We&rsquo;ve emailed your join link to <strong><?= esc($user_email) ?></strong> &mdash; everything you need is below.</p>
    </div>
  </div>
</section>

<!-- MAIN -->
<div class="conf-main"><div class="container">
  <div class="conf-layout">

    <!-- LEFT: ticket -->
    <div>
      <div class="ticket">
        <div class="ticket-top">
          <span class="ticket-status"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;margin-right:4px;"><polyline points="20 6 9 17 4 12"/></svg>Seat confirmed</span>
          <div class="ticket-title"><?= esc($webinarTitle) ?></div>
          <div class="ticket-spk">
            <div class="ticket-av"><?= esc($initials) ?></div>
            <div>
              <div class="ticket-spk-name"><?= esc($speakerName) ?></div>
              <div class="ticket-spk-role"><?= esc($speakerRole) ?></div>
            </div>
          </div>
        </div>
        <div class="ticket-body">
          <div class="ticket-grid">
            <div class="tg-item">
              <div class="tg-ic"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
              <div><div class="tg-label">Date</div><div class="tg-val"><?= esc($dateFormatted) ?></div></div>
            </div>
            <div class="tg-item">
              <div class="tg-ic"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
              <div><div class="tg-label">Time</div><div class="tg-val"><?= esc($timeFormatted) ?><small>Runs ~90 minutes</small></div></div>
            </div>
            <div class="tg-item">
              <div class="tg-ic"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg></div>
              <div><div class="tg-label">Platform</div><div class="tg-val"><span class="prov-pill">&#x25CF; <?= esc($platformName) ?></span></div></div>
            </div>
            <div class="tg-item">
              <div class="tg-ic"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
              <div><div class="tg-label">Your ticket</div><div class="tg-val"><?= esc($ticket_number) ?><small><?= esc($admission_type) ?></small></div></div>
            </div>
          </div>

          <div class="join-box">
            <div class="join-box-label"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;color:var(--brand);margin-right:4px;"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>Your join link</div>
            <div class="join-link-row">
              <input class="join-link-input" id="joinlink" value="<?= esc($join_url) ?>" readonly aria-label="Webinar join link">
              <button class="copy-btn" type="button" onclick="copyJoin()"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;margin-right:4px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>Copy</button>
            </div>
            <div class="join-hint"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;color:var(--accent);margin-right:4px;"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>The link goes live 15 minutes before the session. We&rsquo;ll also email it to you on the day.</div>
          </div>

          <div class="ticket-actions">
            <a href="<?= esc($join_url) ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg" style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:6px;"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>Join webinar</a>
            <button class="btn btn-outline btn-lg" type="button" onclick="shareWebinar()" style="display:inline-flex;align-items:center;justify-content:center;gap:6px;"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>Invite a friend</button>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT: sidebar -->
    <div>
      <!-- countdown -->
      <div style="margin-bottom:20px">
        <div class="cd-strip">
          <div class="cd-strip-label">Webinar Starts In</div>
          <div class="cd-strip-grid">
            <div class="cd-cell"><div class="cd-cell-n" id="cd-d">00</div><div class="cd-cell-l">Days</div></div>
            <div class="cd-cell"><div class="cd-cell-n" id="cd-h">00</div><div class="cd-cell-l">Hrs</div></div>
            <div class="cd-cell"><div class="cd-cell-n" id="cd-m">00</div><div class="cd-cell-l">Min</div></div>
            <div class="cd-cell"><div class="cd-cell-n" id="cd-s">00</div><div class="cd-cell-l">Sec</div></div>
          </div>
        </div>
      </div>

      <!-- add to calendar -->
      <div class="side-card">
        <h3><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Add to your calendar</h3>
        <div class="cal-btns">
          <a class="cal-btn" href="<?= esc($googleCalUrl) ?>" target="_blank" rel="noopener" onclick="showToast('Opening Google Calendar...')">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Google Calendar
            <svg class="chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
          </a>
          <button class="cal-btn" type="button" onclick="downloadICS()">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Apple / Outlook (.ics)
            <svg class="chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
          </button>
        </div>
      </div>

      <!-- reminders -->
      <div class="side-card">
        <h3><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>Reminders</h3>
        <div class="rem-row">
          <div class="rem-info"><div class="rem-ic"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div><div class="rem-txt"><strong>Email</strong><span>24 hours &amp; 1 hour before</span></div></div>
          <label class="switch"><input type="checkbox" checked aria-label="Email reminders"><span class="switch-slider"></span></label>
        </div>
        <div class="rem-row">
          <div class="rem-info"><div class="rem-ic"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></div><div class="rem-txt"><strong>WhatsApp</strong><span>1 hour before start</span></div></div>
          <label class="switch"><input type="checkbox" checked aria-label="WhatsApp reminders"><span class="switch-slider"></span></label>
        </div>
        <div class="rem-row">
          <div class="rem-info"><div class="rem-ic"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div class="rem-txt"><strong>SMS</strong><span>15 minutes before start</span></div></div>
          <label class="switch"><input type="checkbox" aria-label="SMS reminders"><span class="switch-slider"></span></label>
        </div>
      </div>

      <!-- what's next -->
      <div class="side-card">
        <h3><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>What happens next</h3>
        <ul class="next-list">
          <li><span class="next-num">1</span><div><strong>Check your inbox.</strong> A confirmation with your join link is on its way to <?= esc($user_email) ?>.</div></li>
          <li><span class="next-num">2</span><div><strong>We&rsquo;ll remind you.</strong> You&rsquo;ll get a nudge 24 hours and 1 hour before the session.</div></li>
          <li><span class="next-num">3</span><div><strong>Join live.</strong> Tap the join link 15 minutes early to settle in before the Q&amp;A.</div></li>
          <li><span class="next-num">4</span><div><strong>Get the recording.</strong> Can&rsquo;t make it live? We&rsquo;ll email you the replay afterward.</div></li>
        </ul>
      </div>
    </div>

  </div>
</div></div>

<!-- RECOMMENDED -->
<section class="reco-section" aria-labelledby="reco-h">
  <div class="container">
    <div class="section-label"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>You might also like</div>
    <h2 class="section-title" id="reco-h">Keep building <span>momentum</span></h2>
    <p class="section-sub">Based on the session you just registered for, here are more webinars to round out your career growth.</p>
    <div class="reco-grid">
      <?php if (!empty($recommendedWebinars)): ?>
        <?php foreach ($recommendedWebinars as $rw): 
          $rwDate = date('D, j M', strtotime($rw->scheduled_at));
          $rwPrice = ($rw->access_type ?? 'free') === 'paid' ? ('₦' . number_format((float)($rw->price ?? 0))) : 'Free';
        ?>
          <a class="reco-card" href="<?= base_url('training/webinars') ?>">
            <div class="reco-thumb t-blue">
              <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px;"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
            </div>
            <div>
              <div class="reco-cat">Upcoming Webinar</div>
              <div class="reco-title"><?= esc($rw->title) ?></div>
              <div class="reco-meta"><?= esc($rwDate) ?> &middot; <?= esc($rwPrice) ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <a class="reco-card" href="<?= base_url('training/webinars') ?>">
          <div class="reco-thumb t-orange"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/></svg></div>
          <div><div class="reco-cat">All Webinars</div><div class="reco-title">Explore All Live Sessions</div><div class="reco-meta">Every week &middot; Free &amp; Paid</div></div>
        </a>
      <?php endif; ?>
    </div>
  </div>
</section>

<div class="toast-msg" id="toast"><span id="toast-msg">Notification</span></div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/* toast helper */
var toastTimer;
function showToast(msg) {
  var t = document.getElementById('toast');
  if (!t) return;
  document.getElementById('toast-msg').textContent = msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(function() { t.classList.remove('show'); }, 3000);
}

/* countdown */
var targetTime = new Date('<?= date('c', $scheduledTime) ?>');
function updateCountdown() {
  var ids = ['cd-d', 'cd-h', 'cd-m', 'cd-s'];
  var diff = targetTime - new Date();
  if (diff <= 0) {
    ids.forEach(function(id) {
      var el = document.getElementById(id);
      if (el) el.textContent = '00';
    });
    return;
  }
  var v = [
    Math.floor(diff / 864e5),
    Math.floor((diff % 864e5) / 36e5),
    Math.floor((diff % 36e5) / 6e4),
    Math.floor((diff % 6e4) / 1e3)
  ];
  ids.forEach(function(id, i) {
    var el = document.getElementById(id);
    if (el) el.textContent = String(v[i]).padStart(2, '0');
  });
}
updateCountdown();
setInterval(updateCountdown, 1000);

/* copy join link */
function copyJoin() {
  var inp = document.getElementById('joinlink');
  if (!inp) return;
  inp.select();
  inp.setSelectionRange(0, 99999);
  if (navigator.clipboard) {
    navigator.clipboard.writeText(inp.value).then(function() {
      showToast('Join link copied to clipboard.');
    }, function() {
      showToast('Join link copied.');
    });
  } else {
    try { document.execCommand('copy'); } catch(e) {}
    showToast('Join link copied.');
  }
}

/* share webinar */
function shareWebinar() {
  var shareData = {
    title: <?= json_encode($webinarTitle) ?>,
    text: <?= json_encode("I just registered for " . $webinarTitle . " on JobberRecruit — join me!") ?>,
    url: window.location.href
  };
  if (navigator.share) {
    navigator.share(shareData).catch(function() {});
  } else {
    if (navigator.clipboard) navigator.clipboard.writeText(shareData.url);
    showToast('Webinar registration link copied.');
  }
}

/* download ics file */
function downloadICS() {
  var dtStart = '<?= $calStart ?>';
  var dtEnd = '<?= $calEnd ?>';
  var summary = <?= json_encode($webinarTitle) ?>;
  var description = <?= json_encode("JobberRecruit Live Webinar with " . $speakerName . ". Join Link: " . $join_url) ?>;
  var location = <?= json_encode($platformName) ?>;
  
  var ics = [
    'BEGIN:VCALENDAR',
    'VERSION:2.0',
    'PRODID:-//JobberRecruit//Webinars//EN',
    'BEGIN:VEVENT',
    'UID:webinar-' + Date.now() + '@jobberrecruit.com',
    'DTSTAMP:' + dtStart,
    'DTSTART:' + dtStart,
    'DTEND:' + dtEnd,
    'SUMMARY:' + summary,
    'DESCRIPTION:' + description,
    'LOCATION:' + location,
    'BEGIN:VALARM',
    'TRIGGER:-PT1H',
    'ACTION:DISPLAY',
    'DESCRIPTION:Webinar starts in 1 hour',
    'END:VALARM',
    'END:VEVENT',
    'END:VCALENDAR'
  ].join('\r\n');
  
  var blob = new Blob([ics], { type: 'text/calendar;charset=utf-8' });
  var url = URL.createObjectURL(blob);
  var a = document.createElement('a');
  a.href = url;
  a.download = 'jobberrecruit-webinar.ics';
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
  showToast('Calendar file (.ics) downloaded.');
}

/* reminder toggle listeners */
document.querySelectorAll('.switch input').forEach(function(checkbox) {
  checkbox.addEventListener('change', function() {
    showToast(checkbox.checked ? 'Reminder enabled.' : 'Reminder disabled.');
  });
});
</script>
<?= $this->endSection() ?>

