<?php $page_title = 'My Profile'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
/* ═══ Animations ═══ */
@keyframes rise{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
html.anim-ready .content>*{animation:rise .34s ease both}
html.anim-ready .content>*:nth-child(2){animation-delay:.05s}
html.anim-ready .content>*:nth-child(3){animation-delay:.1s}
html.anim-ready .content>*:nth-child(4){animation-delay:.15s}
html.anim-ready .content>*:nth-child(5){animation-delay:.2s}
html.anim-ready .content>*:nth-child(n+6){animation-delay:.24s}
.card{box-shadow:0 1px 3px rgba(10,47,87,.06);transition:var(--transition)}
.card:hover{box-shadow:0 2px 10px rgba(10,47,87,.07)}
.btn{transition:transform .12s cubic-bezier(.2,.8,.2,1),box-shadow .18s ease,background-color .18s ease,border-color .18s ease}
.btn:active{transform:scale(.97)}
.btn:not(:disabled):hover{transform:translateY(-1px)}
.btn:not(:disabled):active{transform:translateY(0) scale(.97)}
@media(prefers-reduced-motion:reduce){.btn{transition:background-color .12s ease,border-color .12s ease!important}.btn:active,.btn:hover{transform:none!important}}

/* ═══ Toggle switch ═══ */
.switch{position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0}
.switch input{opacity:0;width:0;height:0}
.sl{position:absolute;cursor:pointer;inset:0;background:var(--border);border-radius:24px;transition:.3s}
.sl::before{content:'';position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s}
.switch input:checked+.sl{background:var(--brand)}
.switch input:checked+.sl::before{transform:translateX(20px)}
.switch input:focus-visible+.sl{outline:3px solid var(--accent);outline-offset:2px}

/* ═══ Page-head mobile stacking ═══ */
@media(max-width:560px){
  .page-head{flex-direction:column;align-items:flex-start!important;gap:12px}
  .page-head .page-actions{width:100%}
  .page-head .page-actions .btn{width:100%;justify-content:center}
}

/* ═══ Card head stacks on small screens ═══ */
@media(max-width:640px){
  .card-head{flex-direction:column;align-items:flex-start;gap:8px}
  .card-head > a,.card-head > span{padding:0;margin:0}
}

/* ═══ Profile grid: 2-col desktop, 1-col mobile ═══ */
.prof-grid{display:grid;grid-template-columns:300px minmax(0,1fr);gap:clamp(14px,1.8vw,20px);align-items:start}
@media(max-width:960px){.prof-grid{grid-template-columns:minmax(0,1fr)}}
.prof-col{display:flex;flex-direction:column;gap:clamp(14px,1.8vw,20px);min-width:0}
.prof-sticky{position:sticky;top:90px}
@media(max-width:960px){.prof-sticky{position:static}}

/* ═══ ID card (avatar + name) ═══ */
.id-card{text-align:center;padding:24px}
.id-ava{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--brand-deep),var(--brand));color:#fff;font-family:'Sora',sans-serif;font-weight:800;font-size:1.8rem;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;position:relative;overflow:hidden;flex-shrink:0}
.id-ava img{width:100%;height:100%;border-radius:50%;object-fit:cover;display:block}
.id-ava .dot{position:absolute;bottom:2px;right:2px;width:14px;height:14px;border-radius:50%;background:var(--success);border:3px solid #fff}
.id-name{font-family:'Sora',sans-serif;font-weight:800;font-size:1.1rem;color:var(--brand-deep);overflow-wrap:anywhere;word-break:break-word}
.id-mail{font-size:.78rem;color:var(--muted);margin-top:2px;overflow-wrap:anywhere;word-break:break-word}
.id-badges{display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin:12px 0}
.id-actions{margin-top:12px}

/* ═══ Profile-completion ring + body ═══ */
.pf{display:flex;gap:14px;align-items:center;min-width:0}
.pf-ring{position:relative;width:80px;height:80px;flex-shrink:0}
.pf-ring svg{width:80px;height:80px;transform:rotate(-90deg)}
.pf-ring .track{fill:none;stroke:var(--bg);stroke-width:8}
.pf-ring .prog{fill:none;stroke:var(--brand);stroke-width:8;stroke-linecap:round;stroke-dasharray:239;transition:stroke-dashoffset .6s cubic-bezier(.4,0,.2,1)}
.pf-ring .pct{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:.95rem;color:var(--brand-deep)}
.pf-body{flex:1;min-width:0}
.pf-body b{display:block;font-size:.86rem;color:var(--brand-deep)}
.pf-body p{font-size:.76rem;color:var(--muted);line-height:1.6;margin-top:4px;overflow-wrap:anywhere}
.pf-body .pill-row{display:flex;gap:6px;flex-wrap:wrap;margin-top:7px}
/* Stack ring above body on very narrow screens */
@media(max-width:380px){
  .pf{flex-direction:column;align-items:flex-start;gap:12px}
  .pf-body{width:100%}
}

/* ═══ Info grid (Personal / Career) ═══ */
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px 24px}
@media(max-width:560px){.info-grid{grid-template-columns:1fr}}
.info-full{grid-column:1/-1}
.info-lbl{display:flex;align-items:center;gap:6px;font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:4px}
.info-lbl svg{width:13px;height:13px;color:var(--brand);flex-shrink:0}
.info-val{font-size:.86rem;color:var(--brand-deep);font-weight:500;overflow-wrap:anywhere;word-break:break-word}
.priv-note{display:flex;align-items:flex-start;gap:6px;font-size:.64rem;color:var(--muted);margin-top:4px;line-height:1.5}
.priv-note svg{width:11px;height:11px;color:var(--brand);flex-shrink:0;margin-top:2px}

/* ═══ Skills / Languages chips ═══ */
.chips{display:flex;flex-wrap:wrap;gap:6px;min-width:0}
.chip{overflow-wrap:anywhere;word-break:break-word}

/* ═══ Experience / Education / Cert rows ═══ */
.xp{display:flex;gap:12px;padding:14px 0;border-bottom:1px solid var(--border);min-width:0}
.xp:last-child{border-bottom:none;padding-bottom:2px}
.xp:first-child{padding-top:2px}
.xp-ic{width:38px;height:38px;border-radius:10px;background:var(--bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--brand);flex-shrink:0}
.xp-ic svg{width:16px;height:16px}
.xp > div{flex:1;min-width:0}
.xp b{display:block;font-size:.84rem;color:var(--brand-deep);line-height:1.35;overflow-wrap:anywhere;word-break:break-word}
.xp i{font-style:normal;font-size:.72rem;color:var(--muted);display:block;margin-top:2px;overflow-wrap:anywhere;word-break:break-word}
.xp p{font-size:.74rem;color:var(--text);line-height:1.55;margin-top:4px;overflow-wrap:anywhere;word-break:break-word}

/* ═══ Documents row (CV) ═══ */
.doc-row{display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid var(--border);min-width:0}
.doc-row:last-child{border-bottom:none}
.doc-ic{width:44px;height:44px;border-radius:12px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.doc-ic svg{width:20px;height:20px}
.doc-info{flex:1;min-width:160px}
.doc-info b{display:block;font-size:.84rem;color:var(--brand-deep);overflow-wrap:anywhere;word-break:break-word}
.doc-info i{font-style:normal;font-size:.72rem;color:var(--muted)}
.doc-actions{display:flex;gap:8px;flex-shrink:0;flex-wrap:wrap}
@media(max-width:480px){
  .doc-row{flex-direction:column;align-items:flex-start;gap:10px}
  .doc-actions{width:100%}
  .doc-actions .btn{flex:1;min-width:0;justify-content:center}
}

/* ═══ Duo 2-col (Languages + Preferences) ═══ */
.duo2{display:grid;grid-template-columns:1fr 1fr;gap:clamp(14px,1.8vw,20px)}
@media(max-width:760px){.duo2{grid-template-columns:1fr}}
.duo2 > section{min-width:0}

/* ═══ Visibility toggle row ═══ */
.vis-row{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.vis-row p{font-size:.82rem;color:var(--muted);margin:0;flex:1;min-width:200px;overflow-wrap:anywhere}
@media(max-width:480px){
  .vis-row{flex-direction:column;align-items:flex-start;gap:10px}
  .vis-row p{min-width:0}
}

/* ═══ Preferences / inner card text wrap ═══ */
.card-body > div{overflow-wrap:anywhere;word-break:break-word}
.card-body > div a{overflow-wrap:anywhere;word-break:break-all}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Retrieve Escrow Wallet balance
$walletModel = new \App\Models\WalletModel();
$wallet = $walletModel->where('user_id', $user->id)->first();
$walletBalance = $wallet ? $wallet->balance : 0;

// Dynamic Profile completion calculation (unified via JobSeeker entity)
$completion = method_exists($candidate, 'getProfileCompletion') ? $candidate->getProfileCompletion() : 0;
?>

<div class="content">
    
    <!-- Breadcrumbs / Top header style -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true"><use href="#i-users"/></svg> My Profile</h1>
            <p>View and manage your job seeker profile</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('candidate/profile/edit') ?>" class="btn btn-primary btn-sm" style="background:#0861A9;color:#fff;border-color:#0861A9">
                <svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Profile
            </a>
        </div>
    </div>

    <!-- The 2-column Layout Grid -->
    <div class="prof-grid">
      <!-- ══ LEFT COLUMN ══ -->
      <div class="prof-col prof-sticky">
        <section class="card id-card" aria-label="Candidate ID">
          <div class="id-ava">
            <?php
                $candPic = $candidate->profile_picture ?? '';
                $candName = $candidate->full_name ?? 'Candidate';
                $hasCandImg = false;
                $candImgSrc = '';
                if (!empty($candPic)) {
                    if (filter_var($candPic, FILTER_VALIDATE_URL) || str_starts_with($candPic, 'http://') || str_starts_with($candPic, 'https://')) {
                        $hasCandImg = true;
                        $candImgSrc = $candPic;
                    } else {
                        $cleanCandPic = ltrim($candPic, '/\\');
                        if (file_exists(FCPATH . $cleanCandPic)) {
                            $hasCandImg = true;
                            $candImgSrc = base_url($cleanCandPic);
                        } elseif (file_exists(FCPATH . 'uploads/' . $cleanCandPic)) {
                            $hasCandImg = true;
                            $candImgSrc = base_url('uploads/' . $cleanCandPic);
                        }
                    }
                }
                $candInitials = strtoupper(substr($candName, 0, 1));
                $fallbackAvatar = "https://ui-avatars.com/api/?name=" . urlencode(trim($candName)) . "&background=0A2F57&color=fff&size=128&bold=true";
            ?>
            <?php if ($hasCandImg): ?>
                <img src="<?= esc($candImgSrc) ?>" alt="<?= esc($candName) ?>" style="width:100%;height:100%;border-radius:50%;object-fit:cover;" onerror="this.onerror=null; this.src='<?= esc($fallbackAvatar) ?>';">
            <?php else: ?>
                <?= esc($candInitials) ?>
            <?php endif; ?>
            <span class="dot" aria-hidden="true"></span>
          </div>
          <h2 class="id-name"><?= esc($candidate->full_name ?? 'Not Set') ?></h2>
          <p class="id-mail"><?= esc($user->email) ?></p>
          <div class="id-badges">
            <?php if (!empty($candidate->resume)): ?>
                <span class="pill pill--success"><svg aria-hidden="true"><use href="#i-check"/></svg> CV Uploaded</span>
            <?php else: ?>
                <span class="pill pill--pending"><svg aria-hidden="true"><use href="#i-x"/></svg> No CV</span>
            <?php endif; ?>
            <?php if (!empty($candidate->is_open_to_work)): ?>
                <span class="pill pill--immediate">Open to work</span>
            <?php endif; ?>
          </div>
          <div class="id-actions">
            <a href="<?= base_url('candidate/profile/edit') ?>" class="btn btn-primary btn-sm btn-block" style="background:#0861A9;color:#fff;border-color:#0861A9"><svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Profile</a>
          </div>
        </section>

        <!-- Profile completion card -->
        <section class="card" aria-label="Profile completion">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Profile Completion</span></div>
          <div class="card-body">
            <div class="pf">
              <div class="pf-ring" role="img" aria-label="Profile <?= $completion ?> percent complete">
                <svg viewBox="0 0 88 88" aria-hidden="true"><circle class="track" cx="44" cy="44" r="38"/><circle class="prog" cx="44" cy="44" r="38" stroke-dashoffset="<?= 239 * (1 - $completion/100) ?>"/></svg>
                <span class="pct"><?= $completion ?>%</span>
              </div>
              <div class="pf-body">
                <b><?= $completion >= 100 ? 'Fully Complete' : ($completion >= 80 ? 'Almost Complete' : ($completion >= 50 ? 'Partially Complete' : 'Incomplete Profile')) ?></b>
                <p>
                    <?php if ($completion < 100):
                        // Identify missing items including work experience and education
                        $expCountCheck = model(\App\Models\JobSeekerExperienceModel::class)->where('job_seeker_id', $candidate->id)->countAllResults();
                        $eduCountCheck = model(\App\Models\JobSeekerEducationModel::class)->where('job_seeker_id', $candidate->id)->countAllResults();

                        $missingHints = [];
                        if (empty($candidate->full_name))       $missingHints[] = 'add your full name';
                        if (empty($candidate->dob))             $missingHints[] = 'add your date of birth';
                        if (empty($candidate->gender))          $missingHints[] = 'set your gender';
                        if (empty($candidate->phone))           $missingHints[] = 'add a phone number';
                        if (empty($candidate->location))        $missingHints[] = 'add your location';
                        if (empty($candidate->job_title))       $missingHints[] = 'add your job title';
                        if (empty($candidate->employment_type)) $missingHints[] = 'set your employment type';
                        if (empty($candidate->skills))          $missingHints[] = 'add your skills';
                        if ($expCountCheck === 0)               $missingHints[] = 'add at least one work experience entry';
                        if ($eduCountCheck === 0)               $missingHints[] = 'add at least one education entry';
                        if (empty($candidate->resume))          $missingHints[] = '<a href="' . base_url('candidate/profile/edit') . '">upload your CV</a>';
                        $nextStep = !empty($missingHints) ? ucfirst($missingHints[0]) . ' to move to the next milestone.' : 'Almost there!';
                    ?>
                        Next step: <?= $nextStep ?> Complete profiles rank higher in employer searches and unlock up to &#8358;500 in wallet rewards.
                    <?php else: ?>
                        Excellent! Your profile is complete and optimised for employer searches.
                    <?php endif; ?>
                </p>
                <p style="margin-top:7px;display:flex;gap:6px;flex-wrap:wrap">
                    <span class="pill <?= $completion >= 80 ? 'pill--success' : 'pill--pending' ?>"><svg aria-hidden="true"><use href="#i-check"/></svg> &#8358;500 bonus at 80% completion</span>
                </p>
              </div>
            </div>
          </div>
        </section>

        <!-- Profile visibility -->
        <section class="card" aria-label="Profile visibility">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-eye"/></svg> Profile Visibility</span></div>
          <div class="card-body">
            <div class="vis-row" style="display:flex;align-items:center;justify-content:space-between;gap:14px;">
              <p id="vis-text" style="font-size:.82rem;color:var(--muted);margin:0;">
                <?php if (!empty($candidate->is_visible)): ?>
                  <b style="color:var(--brand-deep)">Visible to employers.</b> Verified employers can find you in candidate search and invite you to roles.
                <?php else: ?>
                  <b style="color:var(--brand-deep)">Hidden.</b> You won't appear in employer candidate search until you turn this back on.
                <?php endif; ?>
              </p>
              <label class="switch" style="flex-shrink:0;">
                <input type="checkbox" id="visibility-toggle" <?= !empty($candidate->is_visible) ? 'checked' : '' ?> aria-label="Profile visible to employers">
                <span class="sl"></span>
              </label>
            </div>
          </div>
        </section>

        <!-- Wallet card -->
        <section class="card" aria-label="Wallet">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Wallet Balance</span></div>
          <div class="card-body">
            <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:1.4rem;color:var(--brand-deep);margin-bottom:10px;">&#8358;<?= number_format($walletBalance, 2) ?></h3>
            <a href="<?= base_url('candidate/wallet') ?>" class="btn btn-outline btn-sm btn-block"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Go to Wallet</a>
          </div>
        </section>
      </div>

      <!-- ══ RIGHT COLUMN ══ -->
      <div class="prof-col">
        <!-- Personal Info -->
        <section class="card" aria-label="Personal information">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-users"/></svg> Personal Information</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <div class="info-grid">
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-users"/></svg> Full name</div><div class="info-val"><?= esc($candidate->full_name ?? 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-users"/></svg> Gender</div><div class="info-val"><?= esc(ucfirst($candidate->gender ?? 'Not Set')) ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-calendar"/></svg> Date of birth</div><div class="info-val"><?= esc(!empty($candidate->dob) ? date('d M Y', strtotime($candidate->dob)) : 'Not Set') ?></div>
                <div class="priv-note"><svg aria-hidden="true"><use href="#i-shield"/></svg> Private — used for age verification, never shown to employers</div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-phone"/></svg> Phone</div><div class="info-val"><?= esc($candidate->phone ?? 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-building"/></svg> Location</div><div class="info-val"><?= esc(!empty($candidate->location) ? $candidate->location . ' State' : 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-doc"/></svg> User ID</div><div class="info-val"><?= esc($candidate->user_id) ?></div></div>
            </div>
          </div>
        </section>

        <!-- Career Info -->
        <section class="card" aria-label="Career information">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Career Information</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <div class="info-grid">
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Job title</div><div class="info-val"><?= esc($candidate->job_title ?? 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-clock"/></svg> Employment type</div><div class="info-val"><?= esc($candidate->employment_type ?? 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-chart"/></svg> Experience</div><div class="info-val"><?= esc(!empty($candidate->experience_years) ? $candidate->experience_years . ' years' : 'Not Set') ?></div></div>
              <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-grad"/></svg> Education level</div><div class="info-val"><?= esc($candidate->education_level ?? 'Not Set') ?></div></div>
              <div class="info-full"><div class="info-lbl"><svg aria-hidden="true"><use href="#i-zap"/></svg> Skills</div>
                <div class="chips" style="margin-top:4px">
                  <?php if (!empty($candidate->skills)): ?>
                      <?php 
                      $skillsArr = array_map('trim', explode(',', $candidate->skills));
                      foreach ($skillsArr as $skill): 
                      ?>
                          <span class="chip"><?= esc($skill) ?></span>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <span class="text-muted small">No skills added</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Professional Summary -->
        <section class="card" aria-label="Professional summary">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-note"/></svg> Professional Summary</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <p style="font-size:.86rem;line-height:1.75">
                <?= !empty($candidate->bio) ? nl2br(esc($candidate->bio)) : 'No professional summary added.' ?>
            </p>
          </div>
        </section>

        <!-- Work Experience -->
        <section class="card" aria-label="Work experience">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Work Experience</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <?php if (!empty($experiences)): ?>
                <?php foreach ($experiences as $xp): ?>
                    <?php
                    $start = !empty($xp->start_date) ? date('M Y', strtotime($xp->start_date)) : '';
                    $end   = !empty($xp->is_current) ? 'present' : (!empty($xp->end_date) ? date('M Y', strtotime($xp->end_date)) : '');
                    $range = trim($start . ($start && $end ? ' – ' : '') . $end);
                    ?>
                    <div class="xp"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span>
                      <div><b><?= esc($xp->job_title) ?></b><i><?= esc(trim(($xp->company ?? '') . (!empty($xp->location) ? ' · ' . $xp->location : '') . ($range ? ' · ' . $range : ''), ' ·')) ?></i>
                        <?php if (!empty($xp->description)): ?><p><?= nl2br(esc($xp->description)) ?></p><?php endif; ?>
                      </div></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="xp" style="border:none;padding:2px 0"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span>
                  <div><b>No work experience added</b><i><a href="<?= base_url('candidate/profile/edit') ?>">Add your work history</a> so employers can see your background.</i></div></div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Education -->
        <section class="card" aria-label="Education">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-grad"/></svg> Education</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <?php if (!empty($education)): ?>
                <?php foreach ($education as $ed): ?>
                    <?php $yr = trim(($ed->start_year ?? '') . (($ed->start_year && $ed->end_year) ? ' – ' : '') . ($ed->end_year ?? '')); ?>
                    <div class="xp"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-grad"/></svg></span>
                      <div><b><?= esc($ed->degree) ?><?= !empty($ed->field_of_study) ? ' — ' . esc($ed->field_of_study) : '' ?></b><i><?= esc(trim(($ed->school ?? '') . ($yr ? ' · ' . $yr : '') . (!empty($ed->grade) ? ' · ' . $ed->grade : ''), ' ·')) ?></i></div></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="xp" style="border:none;padding:2px 0"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-grad"/></svg></span>
                  <div><b>No education added</b><i><a href="<?= base_url('candidate/profile/edit') ?>">Add your qualifications</a> to strengthen your profile.</i></div></div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Certifications (Auto-attached JR certificates & External Certifications) -->
        <section class="card" aria-label="Certifications">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-award"/></svg> Licences &amp; Certifications</span>
            <a href="<?= base_url('candidate/profile/edit') ?>" class="card-link">Manage <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
          <div class="card-body">
            <?php 
            $hasAnyCerts = !empty($certificates) || !empty($externalCertifications);
            ?>
            <?php if (!empty($certificates)): ?>
                <?php foreach ($certificates as $cert): ?>
                    <div class="xp"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-award"/></svg></span>
                      <div><b><?= esc($cert['course_name'] ?? 'Course Certificate') ?></b><i>JobberRecruit Verified · Completed <?= esc(date('M Y', strtotime($cert['issued_at']))) ?> · Code: <?= esc($cert['certificate_code']) ?> · <a href="<?= base_url('training/certificate/download/' . $cert['id']) ?>" target="_blank">Download</a></i></div></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($externalCertifications)): ?>
                <?php foreach ($externalCertifications as $extCert): ?>
                    <?php
                    $issueStr = trim(($extCert->issue_month ?? '') . ' ' . ($extCert->issue_year ?? ''));
                    $expStr   = !empty($extCert->does_not_expire) ? 'No Expiry' : trim(($extCert->expiry_month ?? '') . ' ' . ($extCert->expiry_year ?? ''));
                    $metaParts = [];
                    if (!empty($extCert->issuing_organization)) $metaParts[] = $extCert->issuing_organization;
                    if ($issueStr) $metaParts[] = 'Issued ' . $issueStr;
                    if ($expStr) $metaParts[] = $expStr;
                    if (!empty($extCert->credential_id)) $metaParts[] = 'ID: ' . $extCert->credential_id;
                    ?>
                    <div class="xp"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-award"/></svg></span>
                      <div>
                        <b><?= esc($extCert->name) ?></b>
                        <i><?= esc(implode(' · ', $metaParts)) ?>
                          <?php if (!empty($extCert->credential_url)): ?>
                            · <a href="<?= esc($extCert->credential_url, 'attr') ?>" target="_blank" rel="noopener">Verify Credential →</a>
                          <?php endif; ?>
                        </i>
                      </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!$hasAnyCerts): ?>
                <div class="xp" style="border:none;padding:2px 0"><span class="xp-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-award"/></svg></span>
                  <div><b>No licences or certifications added yet</b><i><a href="<?= base_url('candidate/profile/edit') ?>">Add external certifications</a> or complete courses in <a href="<?= base_url('training') ?>">Training</a> to boost your profile strength.</i></div></div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Languages & Job Preferences -->
        <div class="duo2">
          <section class="card" aria-label="Languages" style="min-width:0">
            <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-chat"/></svg> Languages</span></div>
            <div class="card-body">
              <div class="chips">
                <?php if (!empty($candidate->languages)): ?>
                    <?php 
                    $langs = array_map('trim', explode(',', $candidate->languages));
                    foreach ($langs as $lang):
                    ?>
                        <span class="chip"><?= esc($lang) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="text-muted small">Not Set</span>
                <?php endif; ?>
              </div>
            </div>
          </section>
          
          <section class="card" aria-label="Job Preferences" style="min-width:0">
            <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-star"/></svg> Preferences</span></div>
            <div class="card-body">
              <div style="font-size:.84rem;line-height:1.6;">
                <div><span class="text-muted">Salary:</span> <b><?= !empty($candidate->desired_salary) ? '&#8358;' . number_format($candidate->desired_salary) . ' / ' . esc($candidate->salary_type ?? 'monthly') : 'Negotiable' ?></b></div>
                <div><span class="text-muted">Availability:</span> <b><?= esc($candidate->availability ?? 'Not Set') ?></b></div>
                <?php if (!empty($candidate->preferred_location)): ?>
                <div><span class="text-muted">Preferred location:</span> <b><?= esc($candidate->preferred_location) ?></b></div>
                <?php endif; ?>
                <?php if (!empty($candidate->portfolio)): ?>
                <div><span class="text-muted">Portfolio:</span> <b><a href="<?= esc($candidate->portfolio, 'attr') ?>" target="_blank" rel="noopener noreferrer">View portfolio</a></b></div>
                <?php endif; ?>
              </div>
            </div>
          </section>
        </div>

        <!-- Documents / CV -->
        <section class="card" aria-label="Documents">
          <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-doc"/></svg> Documents</span>
            <span class="pill <?= !empty($candidate->resume) ? 'pill--success' : 'pill--pending' ?>"><svg aria-hidden="true"><use href="#i-check"/></svg> <?= !empty($candidate->resume) ? 'Uploaded' : 'Pending' ?></span></div>
          <div class="card-body">
            <div class="doc-row">
              <span class="doc-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
              <div class="doc-info">
                <b>Resume / CV</b>
                <i><?= !empty($candidate->resume) ? 'Uploaded CV File' : 'No CV file uploaded yet' ?></i>
              </div>
              <div class="doc-actions">
                <?php if (!empty($candidate->resume)): ?>
                    <a href="<?= base_url($candidate->resume) ?>" target="_blank" class="btn btn-outline btn-sm"><svg aria-hidden="true"><use href="#i-eye"/></svg> View Resume</a>
                <?php endif; ?>
                <a href="<?= base_url('candidate/profile/edit') ?>" class="btn btn-outline btn-sm"><svg aria-hidden="true"><use href="#i-refresh"/></svg> Upload/Replace</a>
              </div>
            </div>
            <p style="font-size:.74rem;color:var(--muted);margin-top:12px">Your CV is shared with an employer when you apply, or when a verified employer unlocks your profile from candidate search.</p>
          </div>
        </section>
      </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
requestAnimationFrame(function(){document.documentElement.classList.add('anim-ready')});
$(function () {
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $('#visibility-toggle').on('change', function () {
        var input = $(this);
        var makeVisible = input.is(':checked') ? 1 : 0;
        input.prop('disabled', true);

        var data = { is_visible: makeVisible };
        data[csrfName] = csrfHash;

        $.ajax({
            url: '<?= base_url('candidate/profile/visibility') ?>',
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: data,
            success: function (res) {
                if (res.csrf_token && res.csrf_hash) {
                    csrfName = res.csrf_token;
                    csrfHash = res.csrf_hash;
                }
                if (res && res.success) {
                    if (typeof toastr !== 'undefined') toastr.success(res.message);
                    if (res.is_visible) {
                        $('#vis-text').html('<b style="color:var(--brand-deep)">Visible to employers.</b> Verified employers can find you in candidate search and invite you to roles.');
                    } else {
                        $('#vis-text').html('<b style="color:var(--brand-deep)">Hidden.</b> You won\'t appear in employer candidate search until you turn this back on.');
                    }
                } else {
                    input.prop('checked', !makeVisible);
                    if (typeof toastr !== 'undefined') toastr.error((res && res.message) || 'Could not update visibility.');
                }
            },
            error: function () {
                input.prop('checked', !makeVisible);
                if (typeof toastr !== 'undefined') toastr.error('Network error. Please try again.');
            },
            complete: function () { input.prop('disabled', false); }
        });
    });
});
</script>
<?= $this->endSection() ?>

