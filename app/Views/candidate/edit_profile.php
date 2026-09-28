<?php $page_title = 'My Profile · Edit'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
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
.cv-card{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);margin-bottom:12px;overflow:hidden;transition:var(--transition)}
.cv-card:hover{box-shadow:0 2px 10px rgba(10,47,87,.07)}
.cv-card[open]{box-shadow:0 2px 12px rgba(10,47,87,.08)}
.cv-card-header{display:flex;align-items:center;gap:12px;padding:16px 20px;cursor:pointer;list-style:none;min-height:52px}
.cv-card-header::-webkit-details-marker{display:none}
.cv-card-title{flex:1;font-family:'Sora',sans-serif;font-weight:700;font-size:.94rem;color:var(--brand-deep);display:flex;align-items:center;gap:9px}
.cv-card-title svg{width:16px;height:16px;color:var(--brand)}
.cv-card-done{font-size:.66rem;font-weight:700;padding:3px 10px;border-radius:20px}
.cv-card-done.complete{background:var(--success-light);color:var(--success)}
.cv-card-done.incomplete{background:var(--accent-light);color:var(--accent-dark)}
.cv-card-done.optional{background:var(--bg);color:var(--muted)}
.cv-chev{width:16px;height:16px;color:var(--muted);transition:transform .22s ease;flex-shrink:0}
.cv-card[open] .cv-chev{transform:rotate(90deg)}
.cv-card-body{padding:4px 20px 20px}
.cv-card-hint{font-size:.76rem;color:var(--muted);margin-bottom:14px;line-height:1.55}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:680px){.form-grid{grid-template-columns:1fr}}
.form-field.full{grid-column:1/-1}
.form-field label{display:block;font-size:.74rem;font-weight:600;color:var(--muted);margin-bottom:6px}
.form-field .input,.form-field .select{width:100%}
.form-actions{display:flex;align-items:center;gap:12px;margin-top:16px}
.autosave-note{font-size:.72rem;color:var(--muted);display:flex;align-items:center;gap:5px}
.autosave-note svg{width:13px;height:13px;color:var(--brand)}
.text-danger{color:var(--danger)}
.progress-bar{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:16px 20px;margin-bottom:4px}
.progress-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.progress-left{display:flex;align-items:center;gap:16px;flex-wrap:wrap;flex:1}
.progress-track{position:relative;width:100%;max-width:320px;min-width:140px;height:8px;background:var(--bg);border-radius:20px;overflow:visible}
.progress-fill{height:100%;border-radius:20px;background:linear-gradient(90deg,var(--brand-dark),var(--brand));transition:width .6s ease}
.milestone-marker{position:absolute;top:-6px;width:20px;height:20px;border-radius:50%;border:2px solid var(--border);background:#fff;transform:translateX(-50%);transition:all .3s ease}
.milestone-marker.achieved{background:var(--success);border-color:var(--success)}
.milestone-marker.next{border-color:var(--accent);border-style:dashed}
.milestone-label{position:absolute;top:-22px;left:50%;transform:translateX(-50%);font-size:.6rem;font-weight:700;white-space:nowrap;color:var(--muted)}
.milestone-marker:nth-of-type(2) .milestone-label{top:18px}
.milestone-marker.achieved .milestone-label{color:var(--success)}
.progress-text{font-family:'Sora',sans-serif;font-weight:800;font-size:.86rem;color:var(--brand-deep)}
.progress-tip{display:flex;align-items:center;gap:6px;font-size:.74rem;color:var(--muted)}
.progress-tip svg{width:14px;height:14px;color:var(--accent)}
.wallet-chip{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border:1.5px solid var(--border);border-radius:9px;background:#fff;font-size:.8rem;font-weight:600;color:var(--brand-deep)}
.wallet-chip svg{width:16px;height:16px;color:var(--brand)}
.wallet-label{color:var(--muted)}
.rep-list{display:flex;flex-direction:column;gap:14px}
.rep-row{background:var(--bg);border:1px solid var(--border);border-radius:12px;padding:16px;position:relative}
.rep-grid{display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr;gap:12px}
@media(max-width:800px){.rep-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.rep-grid{grid-template-columns:1fr}}
.rep-current{display:flex;align-items:center;gap:8px;font-size:.78rem;color:var(--muted);margin:10px 0}
.rep-current input{accent-color:var(--brand)}
.rep-remove{position:absolute;top:12px;right:12px}
.ai-action{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
.ai-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border:1.5px solid var(--brand);border-radius:8px;background:var(--brand-light);color:var(--brand);font-size:.78rem;font-weight:700;cursor:pointer;transition:var(--transition);white-space:nowrap}
.ai-btn:hover{background:var(--brand);color:#fff}
.ai-btn svg{width:14px;height:14px}
.char-count{text-align:right;font-size:.68rem;color:var(--muted);margin-top:4px}
.jr-auto-certs{background:var(--bg);border:1px solid var(--border);border-radius:12px;padding:16px;margin-bottom:14px}
.jr-auto-header{display:flex;align-items:center;gap:8px;font-size:.82rem;font-weight:700;color:var(--brand-deep);margin-bottom:10px}
.jr-auto-header svg{width:16px;height:16px;color:var(--brand)}
.jr-cert-item{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)}
.jr-cert-item:last-child{border-bottom:none}
.jr-cert-icon{width:36px;height:36px;border-radius:10px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.jr-cert-icon svg{width:16px;height:16px}
.jr-cert-body{flex:1;min-width:0}
.jr-cert-body strong{display:block;font-size:.82rem;color:var(--brand-deep)}
.jr-cert-body span{font-size:.72rem;color:var(--muted)}
.jr-verified-tag{display:inline-flex;align-items:center;gap:4px;font-size:.66rem;font-weight:700;padding:3px 8px;border-radius:12px;background:var(--success-light);color:var(--success)}
.jr-verified-tag svg{width:11px;height:11px}
.jr-auto-link{display:inline-flex;align-items:center;gap:4px;font-size:.76rem;font-weight:600;color:var(--brand);margin-top:10px}
.bottom-actions{display:flex;justify-content:flex-end;gap:12px}
.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
.badge-pct{font-size:.68rem;font-weight:700;padding:2px 7px;border-radius:6px;background:var(--brand-light,#E6F0F8);color:var(--brand,#0861A9);margin-left:6px;display:inline-flex;align-items:center}
.badge-priority{font-size:.64rem;font-weight:700;padding:2px 8px;border-radius:20px;background:#fef3c7;color:#92400e;border:1px solid #fde68a;margin-left:6px;display:inline-flex;align-items:center;gap:3px}
.cv-card--prominent{border:2px solid #0861A9!important;box-shadow:0 4px 16px rgba(8,97,169,.12)!important}
.cv-card--prominent .cv-card-header{background:linear-gradient(90deg,#f0f7ff,#ffffff)}
.completion-breakdown{background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:14px 18px;margin-bottom:14px}
.breakdown-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:8px;margin-top:10px}
.breakdown-chip{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--bg);font-size:.76rem;text-decoration:none!important;color:var(--brand-deep);transition:var(--transition);cursor:pointer}
.breakdown-chip:hover{border-color:var(--brand);transform:translateY(-1px)}
.breakdown-chip.is-done{background:#f0fdf4;border-color:#bbf7d0;color:#166534}
.breakdown-chip.is-prominent{background:#f0f7ff;border-color:#93c5fd;color:#0369a1;font-weight:700}
.breakdown-chip.is-prominent.is-done{background:#f0fdf4;border-color:#86efac;color:#15803d}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Retrieve Escrow Wallet balance
$walletModel = new \App\Models\WalletModel();
$wallet = $walletModel->where('user_id', $user->id)->first();
$walletBalance = $wallet ? $wallet->balance : 0;

// Dynamic Profile completion calculation and checklist
$completion = $candidate->getProfileCompletion();
$checklist  = $candidate->getProfileChecklist();

// Section completion status
$basicComplete  = !empty($candidate->full_name) && !empty($candidate->phone) && !empty($candidate->state_id);
$careerComplete = !empty($candidate->job_title) && (!empty($candidateIndustryIds) || !empty($candidate->industry));
$docsComplete   = !empty($candidate->resume);
$summaryDone    = !empty($candidate->bio);
$langsDone      = !empty($candidate->languages);
$portfolioDone  = !empty($candidate->portfolio);
$expDone        = !empty($experiences) || !empty($candidate->experience_years);
$eduDone        = !empty($education) || !empty($candidate->education_level);
$certDone       = !empty($certifications) || !empty($myCerts);
?>

<div class="content">

    <!-- Header -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Profile</h1>
            <p>Update your personal, career, and document information</p>
        </div>
        <div class="page-actions">
            <a href="<?= base_url('candidate/profile') ?>" class="btn btn-outline btn-sm">Back to Profile</a>
        </div>
    </div>

    <!-- PROFILE PROGRESS BANNER -->
    <div class="progress-bar">
        <div class="progress-inner">
            <div class="progress-left">
                <div class="progress-track" aria-hidden="true">
                    <div class="progress-fill" style="width:<?= $completion ?>%;"></div>
                    <div class="milestone-marker <?= $completion >= 80 ? 'achieved' : 'next' ?>" style="left:80%;" title="Earn ₦500 bonus at 80%"><span class="milestone-label">₦500</span></div>
                </div>
                <span class="progress-text"><?= $completion ?>% Completed</span>
                <span class="progress-tip">
                    <svg aria-hidden="true"><use href="#i-zap"/></svg>
                    <span>
                        <?php if ($completion < 80): ?>Reach at least 80% (<?= 80 - $completion ?>% more) to unlock your ₦500 wallet reward
                        <?php else: ?>🎉 ₦500 profile completion incentive unlocked! (Credited to your wallet)
                        <?php endif; ?>
                    </span>
                </span>
            </div>
            <div class="progress-actions">
                <span class="wallet-chip <?= $walletBalance > 0 ? 'has-balance' : '' ?>">
                    <svg aria-hidden="true"><use href="#i-wallet"/></svg>
                    <span class="wallet-label">Wallet:</span>
                    <span>₦<?= number_format($walletBalance, 2) ?></span>
                </span>
            </div>
        </div>
    </div>

    <!-- PROFILE COMPLETION STRUCTURE BREAKDOWN -->
    <div class="completion-breakdown">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
            <div style="font-size:.84rem;font-weight:700;color:var(--brand-deep);display:flex;align-items:center;gap:6px;">
                <svg aria-hidden="true" width="16" height="16" style="color:var(--brand)"><use href="#i-check-c"/></svg>
                Profile Completion Breakdown (100% Total)
            </div>
            <div style="font-size:.76rem;color:var(--muted);">
                Incentive Threshold: <strong style="color:var(--brand-deep)">80%</strong> to earn <strong style="color:var(--success)">₦500 Wallet Reward</strong>
            </div>
        </div>
        <div class="breakdown-grid">
            <?php foreach ($checklist as $item): ?>
                <a href="#<?= esc($item['target_id'] ?? '') ?>" class="breakdown-chip <?= $item['done'] ? 'is-done' : '' ?> <?= !empty($item['is_prominent']) ? 'is-prominent' : '' ?>" onclick="var el=document.getElementById('<?= esc($item['target_id'] ?? '') ?>');if(el){el.open=true;el.scrollIntoView({behavior:'smooth',block:'start'});return false;}">
                    <span style="display:flex;align-items:center;gap:6px;min-width:0;">
                        <svg aria-hidden="true" width="13" height="13" style="flex-shrink:0;color:<?= $item['done'] ? 'var(--success)' : (!empty($item['is_prominent']) ? 'var(--brand)' : 'var(--muted)') ?>"><use href="<?= $item['done'] ? '#i-check-c' : '#i-circle' ?>"/></svg>
                        <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($item['title']) ?></span>
                        <?php if (!empty($item['is_prominent'])): ?>
                            <span style="font-size:.62rem;background:#e0f2fe;color:#0369a1;padding:1px 5px;border-radius:10px;font-weight:700;">★ Priority</span>
                        <?php elseif (!empty($item['optional'])): ?>
                            <span style="font-size:.62rem;color:var(--muted);">(Opt)</span>
                        <?php endif; ?>
                    </span>
                    <strong style="font-size:.74rem;flex-shrink:0;"><?= $item['max_points'] ?>%</strong>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Main form — all sections post to same endpoint -->
    <form action="<?= base_url('candidate/profile/edit') ?>"
          method="POST"
          enctype="multipart/form-data"
          id="editCandidateForm">

        <?= csrf_field() ?>

        <div class="edit-wrap">

            <!-- ══ PROFILE VISIBILITY TOGGLE ══ -->
            <div class="visibility-banner" id="visibility-banner" style="display:flex;align-items:center;gap:14px;padding:14px 20px;background:<?= !empty($candidate->is_visible) ? '#f0fdf4' : '#fef2f2' ?>;border:1px solid <?= !empty($candidate->is_visible) ? '#bbf7d0' : '#fecaca' ?>;border-radius:var(--radius-lg);margin-bottom:12px;transition:background .2s ease,border-color .2s ease;">
                <svg aria-hidden="true" width="20" height="20" id="visibility-banner-icon" style="flex-shrink:0;color:<?= !empty($candidate->is_visible) ? '#16a34a' : '#dc2626' ?>"><use href="#i-eye"/></svg>
                <div style="flex:1">
                    <div style="font-weight:600;font-size:.88rem" id="visibility-banner-status">Profile Visibility: <?= !empty($candidate->is_visible) ? 'Visible to employers' : 'Hidden from search' ?></div>
                    <div style="font-size:.76rem;color:var(--muted)">When OFF, employers cannot find your profile through Candidate Search. You can still apply to jobs.</div>
                </div>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin:0;font-size:.82rem;font-weight:600">
                    <input type="hidden" name="is_visible" id="visibility-hidden-input" value="<?= !empty($candidate->is_visible) ? '1' : '0' ?>">
                    <input type="checkbox" id="visibility-toggle-edit" value="1" <?= !empty($candidate->is_visible) ? 'checked' : '' ?> style="width:18px;height:18px;accent-color:#16a34a">
                    <span id="visibility-toggle-label"><?= !empty($candidate->is_visible) ? 'Visible' : 'Hidden' ?></span>
                </label>
            </div>

            <!-- ══ 1. PERSONAL INFORMATION (15%) ══ -->
            <details class="cv-card <?= $basicComplete ? 'is-complete' : '' ?>" id="sec-personal" open>
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-users"/></svg> Personal Information <span class="badge-pct">15%</span></span>
                    <span class="cv-card-done <?= $basicComplete ? 'complete' : 'incomplete' ?>"><?= $basicComplete ? 'Complete' : 'Incomplete' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Provide your core contact details. Ensure your phone number is correct so recruiters can reach you easily.</div>

                    <!-- Profile photo upload (Headshot) -->
                    <div style="display:flex;gap:16px;align-items:center;margin-bottom:18px;">
                        <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;background:#f5f7fb;display:flex;align-items:center;justify-content:center;border:2px solid var(--border);flex-shrink:0;">
                            <?php
                                $candPic = $candidate->profile_picture ?? '';
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
                            ?>
                            <?php if ($hasCandImg): ?>
                                <img src="<?= esc($candImgSrc) ?>" id="currentProfilePic" alt="Profile" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'; document.getElementById('defaultProfileSvg').style.display='block';">
                                <svg id="defaultProfileSvg" aria-hidden="true" style="display:none;width:36px;height:36px;color:var(--muted);"><use href="#i-users"/></svg>
                            <?php else: ?>
                                <svg id="defaultProfileSvg" aria-hidden="true" style="width:36px;height:36px;color:var(--muted);"><use href="#i-users"/></svg>
                            <?php endif; ?>
                            <img id="profilePreviewImg" style="display:none;width:100%;height:100%;object-fit:cover;" alt="Preview">
                        </div>
                        <div>
                            <label class="btn btn-outline btn-sm" for="profileInput" style="cursor:pointer;margin-bottom:6px;">Upload Profile Photo (Headshot)</label>
                            <input type="file" name="profile_picture" id="profileInput" accept="image/*" class="sr-only">
                            <p style="font-size:.74rem;color:var(--muted);margin:0;">JPEG or PNG · Max 2MB · Square crop recommended · This is your profile headshot, not your CV.</p>
                            <?php if (!empty($candidate->profile_picture)): ?>
                                <label style="display:flex;align-items:center;gap:6px;margin-top:6px;font-size:.78rem;">
                                    <input type="checkbox" name="remove_profile_picture" value="1"> Remove current photo
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-field">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="input" value="<?= old('full_name', $candidate->full_name) ?>" required placeholder="e.g. Adaeze Okonkwo">
                        </div>
                        <div class="form-field">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" class="input" value="<?= old('phone', $candidate->phone) ?>" placeholder="e.g. 08012345678">
                        </div>
                        <div class="form-field">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" class="input" value="<?= old('dob', $candidate->dob) ?>">
                            <span style="font-size:.72rem;color:var(--muted);margin-top:4px;display:block;">🔒 Used for age calculation only — never shown to employers</span>
                        </div>
                        <div class="form-field">
                            <label>Gender</label>
                            <select name="gender" class="select">
                                <option value="">Select gender</option>
                                <option value="male" <?= ($candidate->gender ?? '') == 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= ($candidate->gender ?? '') == 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= ($candidate->gender ?? '') == 'other' ? 'selected' : '' ?>>Prefer not to say</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>State of Residence</label>
                            <select name="state_id" class="select">
                                <option value="">Select State</option>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?= $state->id ?>" <?= ($candidate->state_id ?? '') == $state->id ? 'selected' : '' ?>><?= esc($state->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Local Area / City</label>
                            <input type="text" name="location" class="input" value="<?= old('location', $candidate->location) ?>" placeholder="e.g. Ikeja, Lagos">
                        </div>
                        <div class="form-field">
                            <label>Email Address</label>
                            <input type="email" class="input" value="<?= esc($user->email) ?>" readonly style="background:var(--bg);color:var(--muted);cursor:not-allowed;" title="Email cannot be edited here">
                            <a href="<?= base_url('candidate/settings/security') ?>" style="font-size:.74rem;margin-top:4px;display:inline-block;color:var(--brand);">Change email address →</a>
                        </div>
                        <div class="form-field">
                            <label>Availability</label>
                            <select name="availability" class="select">
                                <option value="">Select</option>
                                <option value="immediately" <?= ($candidate->availability ?? '') == 'immediately' ? 'selected' : '' ?>>Immediately available</option>
                                <option value="1-week" <?= ($candidate->availability ?? '') == '1-week' ? 'selected' : '' ?>>Within 1 week</option>
                                <option value="1-month" <?= ($candidate->availability ?? '') == '1-month' ? 'selected' : '' ?>>Within 1 month</option>
                                <option value="2-months" <?= ($candidate->availability ?? '') == '2-months' ? 'selected' : '' ?>>Within 2–3 months</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <svg aria-hidden="true"><use href="#i-check"/></svg> Save personal info
                        </button>
                        <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Saved when you click Update Profile at the bottom</span>
                    </div>
                </div>
            </details>

            <!-- ══ 2. JOB PREFERENCE (15%) ══ -->
            <details class="cv-card <?= $careerComplete ? 'is-complete' : '' ?>" id="sec-preferences">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-star"/></svg> Job Preference <span class="badge-pct">15%</span></span>
                    <span class="cv-card-done <?= $careerComplete ? 'complete' : 'incomplete' ?>"><?= $careerComplete ? 'Complete' : 'Incomplete' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Tell us what you are looking for. This powers our job-matching engine — the more you fill in, the better your matches.</div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Target Job Title</label>
                            <input type="text" name="job_title" class="input" value="<?= old('job_title', $candidate->job_title) ?>" placeholder="e.g. Senior Software Engineer">
                        </div>
                        <div class="form-field">
                            <label>Preferred Employment Type</label>
                            <select name="employment_type" class="select">
                                <option value="">Select</option>
                                <option value="Full Time"   <?= ($candidate->employment_type ?? '') == 'Full Time'   ? 'selected' : '' ?>>Full-time</option>
                                <option value="Part Time"   <?= ($candidate->employment_type ?? '') == 'Part Time'   ? 'selected' : '' ?>>Part-time</option>
                                <option value="Remote"      <?= ($candidate->employment_type ?? '') == 'Remote'      ? 'selected' : '' ?>>Remote</option>
                                <option value="Contract"    <?= ($candidate->employment_type ?? '') == 'Contract'    ? 'selected' : '' ?>>Contract</option>
                                <option value="Internship"  <?= ($candidate->employment_type ?? '') == 'Internship'  ? 'selected' : '' ?>>Internship</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Years of Experience</label>
                            <input type="number" name="experience_years" class="input" value="<?= old('experience_years', $candidate->experience_years) ?>" placeholder="e.g. 5" min="0" max="50">
                        </div>
                        <div class="form-field">
                            <label>Highest Education Level</label>
                            <select name="education_level" class="select">
                                <option value="">Select level</option>
                                <?php foreach (['High School','Undergraduate','Diploma',"Bachelor's Degree","Master's Degree",'PhD','Professional Certification','Others'] as $lvl): ?>
                                    <option value="<?= $lvl ?>" <?= ($candidate->education_level ?? '') == $lvl ? 'selected' : '' ?>><?= $lvl ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Expected Salary (₦ per month)</label>
                            <input type="number" name="desired_salary" class="input" value="<?= old('desired_salary', $candidate->desired_salary) ?>" placeholder="e.g. 250000">
                        </div>
                        <div class="form-field">
                            <label>Salary Period</label>
                            <select name="salary_type" class="select">
                                <option value="">Select</option>
                                <option value="hourly"  <?= ($candidate->salary_type ?? '') == 'hourly'  ? 'selected' : '' ?>>Hourly</option>
                                <option value="monthly" <?= ($candidate->salary_type ?? '') == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                <option value="yearly"  <?= ($candidate->salary_type ?? '') == 'yearly'  ? 'selected' : '' ?>>Yearly</option>
                            </select>
                        </div>
                        <div class="form-field full">
                            <label>Target Industries (Hold Ctrl/Cmd to select multiple)</label>
                            <select class="select select2" name="industry_ids[]" multiple style="min-height:120px;">
                                <?php foreach ($industries as $industry): ?>
                                    <optgroup label="<?= esc($industry->name) ?>">
                                        <?php foreach ($industry->children as $child): ?>
                                            <option value="<?= $child->id ?>" <?= in_array($child->id, $candidateIndustryIds ?? []) ? 'selected' : '' ?>><?= esc($child->name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ══ 3. RESUME / CV DOCUMENT (12%) ══ -->
            <details class="cv-card cv-card--prominent <?= $docsComplete ? 'is-complete' : '' ?>" id="sec-resume" open>
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-doc"/></svg> Resume (CV Document) <span class="badge-pct">12%</span> <span class="badge-priority">★ Prominent · Priority</span></span>
                    <span class="cv-card-done <?= $docsComplete ? 'complete' : 'incomplete' ?>"><?= $docsComplete ? 'Complete' : 'Incomplete' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Upload your latest resume in PDF or Word format (.pdf, .doc, .docx). Your CV is automatically attached when you apply for jobs on JobberRecruit.</div>
                    <div class="form-grid">
                        <div class="form-field full">
                            <label for="resumeInput" style="font-weight:600;">Upload / Manage Resume (CV Document)</label>
                            <input type="file" name="resume" id="resumeInput" accept=".pdf,.doc,.docx" class="input" style="margin-bottom:8px;">
                            <?php if (!empty($candidate->resume)): ?>
                                <div style="display:flex;align-items:center;gap:12px;margin-top:8px;padding:10px 14px;background:#f0f7ff;border:1px solid #d0e3ff;border-radius:8px;">
                                    <svg aria-hidden="true" width="20" height="20" style="color:var(--brand);"><use href="#i-doc"/></svg>
                                    <span style="font-size:.84rem;font-weight:500;color:var(--text);flex:1;"><?= esc(basename($candidate->resume)) ?></span>
                                    <a href="<?= base_url($candidate->resume) ?>" target="_blank" class="btn btn-outline btn-sm">
                                        <svg aria-hidden="true"><use href="#i-eye"/></svg> View Current Resume
                                    </a>
                                    <label style="display:flex;align-items:center;gap:6px;font-size:.78rem;color:#dc2626;cursor:pointer;margin:0;">
                                        <input type="checkbox" name="remove_resume" value="1"> Remove Resume
                                    </label>
                                </div>
                            <?php endif; ?>
                            <div id="resumePreview" style="display:none;margin-top:8px;">
                                <div style="background:#f5f7fb;border-radius:8px;padding:8px 12px;display:inline-flex;align-items:center;gap:8px;">
                                    <svg aria-hidden="true"><use href="#i-doc"/></svg>
                                    <span id="resumePreviewText" style="font-size:.8rem;font-weight:500;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ══ 4. PROFESSIONAL SUMMARY (10%) ══ -->
            <details class="cv-card <?= $summaryDone ? 'is-complete' : '' ?>" id="sec-summary">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-note"/></svg> Professional Summary <span class="badge-pct">10%</span></span>
                    <span class="cv-card-done <?= $summaryDone ? 'complete' : 'incomplete' ?>"><?= $summaryDone ? 'Complete' : 'Incomplete' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="ai-action">
                        <p class="cv-card-hint" style="margin:12px 0 0;flex:1;">Briefly describe your years of experience, key skills, and your most notable career achievement. Employers read this first — make it count.</p>
                        <button type="button" class="ai-btn" id="aiSummaryBtn" title="Let AI draft a summary">
                            <svg aria-hidden="true"><use href="#i-zap"/></svg> AI generate
                        </button>
                    </div>
                    <div class="form-field" style="margin-top:14px;">
                        <textarea name="bio" id="summaryTextarea" class="input" rows="5" maxlength="600"
                            placeholder="e.g. Results-driven Marketing Manager with 5+ years of experience..."
                            oninput="document.getElementById('summaryCount').textContent=this.value.length"><?= old('bio', $candidate->bio ?? '') ?></textarea>
                        <div class="char-count"><span id="summaryCount"><?= strlen($candidate->bio ?? '') ?></span> / 600</div>
                    </div>
                </div>
            </details>

            <!-- ══ 5. WORK EXPERIENCE (20%) ══ -->
            <details class="cv-card <?= !empty($experiences) ? 'is-complete' : '' ?>" id="sec-experience">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Work Experience <span class="badge-pct">20%</span></span>
                    <span class="cv-card-done <?= !empty($experiences) ? 'complete' : 'incomplete' ?>"><?= !empty($experiences) ? count($experiences) . ' added' : 'Incomplete' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Add your roles, most recent first. This appears on your profile and helps employers understand your background.</div>
                    <div id="exp-list" class="rep-list">
                        <?php foreach (($experiences ?? []) as $xp): ?>
                        <div class="rep-row">
                            <div class="rep-grid">
                                <div class="form-field"><label>Job title</label><input type="text" name="exp_job_title[]" class="input" value="<?= esc($xp->job_title, 'attr') ?>" placeholder="e.g. Accountant"></div>
                                <div class="form-field"><label>Company</label><input type="text" name="exp_company[]" class="input" value="<?= esc($xp->company ?? '', 'attr') ?>" placeholder="e.g. Acme Ltd"></div>
                                <div class="form-field"><label>Location</label><input type="text" name="exp_location[]" class="input" value="<?= esc($xp->location ?? '', 'attr') ?>" placeholder="e.g. Lagos"></div>
                                <div class="form-field"><label>Start</label><input type="month" name="exp_start[]" class="input" value="<?= $xp->start_date ? esc(substr($xp->start_date, 0, 7), 'attr') : '' ?>"></div>
                                <div class="form-field"><label>End</label><input type="month" name="exp_end[]" class="input" value="<?= $xp->end_date ? esc(substr($xp->end_date, 0, 7), 'attr') : '' ?>" <?= !empty($xp->is_current) ? 'disabled' : '' ?>></div>
                            </div>
                            <label class="rep-current"><input type="checkbox" class="exp-current-toggle" <?= !empty($xp->is_current) ? 'checked' : '' ?>> I currently work here</label>
                            <input type="hidden" name="exp_is_current[]" value="<?= !empty($xp->is_current) ? '1' : '0' ?>">
                            <div class="form-field"><label>Description</label><textarea name="exp_description[]" class="input" rows="2" placeholder="What you did / achieved"><?= esc($xp->description ?? '') ?></textarea></div>
                            <button type="button" class="btn btn-outline btn-sm rep-remove"><svg aria-hidden="true"><use href="#i-x"/></svg> Remove</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline btn-sm" id="add-exp"><svg aria-hidden="true"><use href="#i-plus"/></svg> Add work experience</button>
                </div>
            </details>

            <!-- ══ 6. EDUCATION (12%) ══ -->
            <details class="cv-card <?= !empty($education) ? 'is-complete' : '' ?>" id="sec-education">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-grad"/></svg> Education History <span class="badge-pct">12%</span></span>
                    <span class="cv-card-done <?= !empty($education) ? 'complete' : 'incomplete' ?>"><?= !empty($education) ? count($education) . ' added' : 'Incomplete' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Add your qualifications. Degrees, diplomas, and professional qualifications all count.</div>
                    <datalist id="ng-degree-list">
                        <option value="B.Sc. - Bachelor of Science">
                        <option value="B.A. - Bachelor of Arts">
                        <option value="B.Eng. - Bachelor of Engineering">
                        <option value="B.Tech. - Bachelor of Technology">
                        <option value="LL.B - Bachelor of Laws">
                        <option value="MBBS - Medicine & Surgery">
                        <option value="HND - Higher National Diploma">
                        <option value="OND / ND - National Diploma">
                        <option value="NCE - Nigeria Certificate in Education">
                        <option value="M.Sc. - Master of Science">
                        <option value="M.A. - Master of Arts">
                        <option value="MBA - Master of Business Administration">
                        <option value="Ph.D. - Doctorate">
                        <option value="PGD - Postgraduate Diploma">
                        <option value="SSCE / WAEC / NECO">
                        <option value="Professional Certificate">
                    </datalist>
                    <div id="edu-list" class="rep-list">
                        <?php foreach (($education ?? []) as $ed): ?>
                        <div class="rep-row">
                            <div class="rep-grid">
                                <div class="form-field"><label>Qualification / Degree</label><input type="text" name="edu_degree[]" list="ng-degree-list" class="input" value="<?= esc($ed->degree, 'attr') ?>" placeholder="e.g. B.Sc."></div>
                                <div class="form-field"><label>Field of study</label><input type="text" name="edu_field[]" class="input" value="<?= esc($ed->field_of_study ?? '', 'attr') ?>" placeholder="e.g. Accounting"></div>
                                <div class="form-field"><label>School</label><input type="text" name="edu_school[]" class="input" value="<?= esc($ed->school ?? '', 'attr') ?>" placeholder="e.g. University of Lagos"></div>
                                <div class="form-field"><label>Start year</label><input type="text" name="edu_start_year[]" class="input" maxlength="4" value="<?= esc($ed->start_year ?? '', 'attr') ?>" placeholder="2014"></div>
                                <div class="form-field"><label>End year</label><input type="text" name="edu_end_year[]" class="input" maxlength="4" value="<?= esc($ed->end_year ?? '', 'attr') ?>" placeholder="2018"></div>
                                <div class="form-field"><label>Grade (optional)</label><input type="text" name="edu_grade[]" class="input" value="<?= esc($ed->grade ?? '', 'attr') ?>" placeholder="e.g. Second Class Upper"></div>
                            </div>
                            <button type="button" class="btn btn-outline btn-sm rep-remove"><svg aria-hidden="true"><use href="#i-x"/></svg> Remove</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline btn-sm" id="add-edu"><svg aria-hidden="true"><use href="#i-plus"/></svg> Add education</button>
                </div>
            </details>

            <!-- ══ 7. SKILLS (10%) ══ -->
            <details class="cv-card <?= !empty($candidate->skills) ? 'is-complete' : '' ?>" id="sec-skills">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-zap"/></svg> Skills <span class="badge-pct">10%</span></span>
                    <span class="cv-card-done <?= !empty($candidate->skills) ? 'complete' : 'incomplete' ?>"><?= !empty($candidate->skills) ? 'Complete' : 'Incomplete' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Add your key skills, comma-separated. These power our job-matching engine — be specific (e.g. "Python, React, SQL" instead of just "programming").</div>
                    <div class="form-field">
                        <label>Skills (comma-separated)</label>
                        <textarea name="skills" class="input" rows="3" placeholder="e.g. PHP, UI/UX Design, Figma, React, Communication"><?= old('skills', $candidate->skills) ?></textarea>
                    </div>
                    <p style="font-size:.74rem;color:var(--muted);margin-top:8px;">Tip: Add 8–15 specific skills for the best match results.</p>
                </div>
            </details>

            <!-- ══ 8. LICENCES & CERTIFICATIONS (2%) ══ -->
            <details class="cv-card <?= (!empty($certifications) || !empty($myCerts)) ? 'is-complete' : '' ?>" id="sec-certifications">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-award"/></svg> Licences &amp; Certifications <span class="badge-pct">2%</span> <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px;">(Optional)</span></span>
                    <span class="cv-card-done optional">Optional</span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <!-- Auto-synced JR certificates -->
                    <?php
                    $certModel = model(\App\Models\CourseCertificateModel::class);
                    $myCerts   = $certModel->getUserCertificates($user->id);
                    ?>
                    <?php if (!empty($myCerts)): ?>
                    <div class="jr-auto-certs">
                        <div class="jr-auto-header">
                            <svg aria-hidden="true"><use href="#i-award"/></svg>
                            JobberRecruit Verified Certificates
                            <span class="jr-verified-tag"><svg aria-hidden="true"><use href="#i-check"/></svg> Auto-synced</span>
                        </div>
                        <?php foreach ($myCerts as $cert): ?>
                        <div class="jr-cert-item">
                            <div class="jr-cert-icon"><svg aria-hidden="true"><use href="#i-grad"/></svg></div>
                            <div class="jr-cert-body">
                                <strong><?= esc($cert['course_name'] ?? 'Course Certificate') ?></strong>
                                <span>Completed <?= esc(date('d M Y', strtotime($cert['issued_at']))) ?> · Code: <?= esc($cert['certificate_code']) ?> · <a href="<?= base_url('training/certificate/download/' . $cert['id']) ?>" target="_blank">Download</a></span>
                            </div>
                            <span class="jr-verified-tag"><svg aria-hidden="true"><use href="#i-check"/></svg> JR Verified</span>
                        </div>
                        <?php endforeach; ?>
                        <a href="<?= base_url('training') ?>" class="jr-auto-link">Complete more courses to earn certificates →</a>
                    </div>
                    <?php endif; ?>

                    <div class="cv-card-hint" style="margin-top:10px;">Add external licences and certifications (e.g. PMP, ICAN, COREN, AWS, ACCA). JobberRecruit-earned certificates appear above automatically.</div>

                    <!-- External certifications list -->
                    <?php
                    $renderedCerts = !empty($certifications) ? $certifications : [];
                    ?>
                    <div id="cert-list" class="rep-list">
                        <?php foreach ($renderedCerts as $certItem): ?>
                        <div class="rep-row">
                            <div class="rep-grid">
                                <div class="form-field full"><label>Certification Name</label><input type="text" name="cert_name[]" class="input" value="<?= esc($certItem->name, 'attr') ?>" placeholder="e.g. Project Management Professional (PMP)"></div>
                                <div class="form-field"><label>Issuing Organisation</label><input type="text" name="cert_org[]" class="input" value="<?= esc($certItem->issuing_organization ?? '', 'attr') ?>" placeholder="e.g. PMI / ICAN / AWS"></div>
                                <div class="form-field"><label>Credential ID (optional)</label><input type="text" name="cert_id[]" class="input" value="<?= esc($certItem->credential_id ?? '', 'attr') ?>" placeholder="e.g. ABC-12345"></div>
                                <div class="form-field"><label>Credential URL (optional)</label><input type="url" name="cert_url[]" class="input" value="<?= esc($certItem->credential_url ?? '', 'attr') ?>" placeholder="https://"></div>
                                <div class="form-field">
                                    <label>Issue Date</label>
                                    <div style="display:flex;gap:6px;">
                                        <select name="cert_issue_month[]" class="select">
                                            <option value="">Month</option>
                                            <?php foreach (['January','February','March','April','May','June','July','August','September','October','November','December'] as $m): ?>
                                                <option value="<?= $m ?>" <?= ($certItem->issue_month ?? '') == $m ? 'selected' : '' ?>><?= $m ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" name="cert_issue_year[]" class="input" maxlength="4" value="<?= esc($certItem->issue_year ?? '', 'attr') ?>" placeholder="Year">
                                    </div>
                                </div>
                                <div class="form-field full">
                                    <label class="rep-current"><input type="checkbox" class="cert-no-expire-toggle" <?= !empty($certItem->does_not_expire) ? 'checked' : '' ?>> This credential does not expire</label>
                                    <input type="hidden" name="cert_no_expire[]" value="<?= !empty($certItem->does_not_expire) ? '1' : '0' ?>">
                                </div>
                                <div class="form-field cert-expiry-wrap" style="<?= !empty($certItem->does_not_expire) ? 'display:none;' : '' ?>">
                                    <label>Expiry Date</label>
                                    <div style="display:flex;gap:6px;">
                                        <select name="cert_exp_month[]" class="select" <?= !empty($certItem->does_not_expire) ? 'disabled' : '' ?>>
                                            <option value="">Month</option>
                                            <?php foreach (['January','February','March','April','May','June','July','August','September','October','November','December'] as $m): ?>
                                                <option value="<?= $m ?>" <?= ($certItem->expiry_month ?? '') == $m ? 'selected' : '' ?>><?= $m ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" name="cert_exp_year[]" class="input" maxlength="4" value="<?= esc($certItem->expiry_year ?? '', 'attr') ?>" placeholder="Year" <?= !empty($certItem->does_not_expire) ? 'disabled' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline btn-sm rep-remove"><svg aria-hidden="true"><use href="#i-x"/></svg> Remove</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline btn-sm" id="add-cert"><svg aria-hidden="true"><use href="#i-plus"/></svg> Add another certification</button>
                </div>
            </details>

            <!-- ══ 9. PORTFOLIO & WORK SAMPLES (3%) ══ -->
            <details class="cv-card <?= $portfolioDone ? 'is-complete' : '' ?>" id="sec-portfolio">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-globe"/></svg> Portfolio &amp; Work Samples <span class="badge-pct">3%</span> <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px;">(Optional)</span></span>
                    <span class="cv-card-done optional">Optional</span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Add a link to your GitHub, Behance, a published article, or a live project. Portfolios significantly boost employer interest for creative and technical roles.</div>
                    <div class="form-field">
                        <label>Portfolio Website URL</label>
                        <input type="text" name="portfolio" id="portfolioInput" class="input"
                               value="<?= old('portfolio', $candidate->portfolio) ?>" placeholder="https://myportfolio.com">
                        <span style="font-size:.72rem;color:var(--muted);margin-top:4px;display:block;">Include https:// — we'll add it automatically if missing.</span>
                    </div>
                </div>
            </details>

            <!-- ══ 10. LANGUAGES (1%) ══ -->
            <details class="cv-card <?= $langsDone ? 'is-complete' : '' ?>" id="sec-languages">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-chat"/></svg> Languages <span class="badge-pct">1%</span> <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px;">(Optional)</span></span>
                    <span class="cv-card-done optional">Optional</span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">For Nigerian employers, listing Yoruba, Igbo, or Hausa alongside English is often a real advantage — especially for field, sales, and community roles.</div>
                    <div class="form-field">
                        <label>Languages (comma-separated)</label>
                        <input type="text" name="languages" class="input" value="<?= old('languages', $candidate->languages) ?>" placeholder="e.g. English, Yoruba, French">
                    </div>
                </div>
            </details>

            <!-- Templates for cloning new rows -->
            <template id="exp-template">
                <div class="rep-row">
                    <div class="rep-grid">
                        <div class="form-field"><label>Job title</label><input type="text" name="exp_job_title[]" class="input" placeholder="e.g. Accountant"></div>
                        <div class="form-field"><label>Company</label><input type="text" name="exp_company[]" class="input" placeholder="e.g. Acme Ltd"></div>
                        <div class="form-field"><label>Location</label><input type="text" name="exp_location[]" class="input" placeholder="e.g. Lagos"></div>
                        <div class="form-field"><label>Start</label><input type="month" name="exp_start[]" class="input"></div>
                        <div class="form-field"><label>End</label><input type="month" name="exp_end[]" class="input"></div>
                    </div>
                    <label class="rep-current"><input type="checkbox" class="exp-current-toggle"> I currently work here</label>
                    <input type="hidden" name="exp_is_current[]" value="0">
                    <div class="form-field"><label>Description</label><textarea name="exp_description[]" class="input" rows="2" placeholder="What you did / achieved"></textarea></div>
                    <button type="button" class="btn btn-outline btn-sm rep-remove"><svg aria-hidden="true"><use href="#i-x"/></svg> Remove</button>
                </div>
            </template>
            <template id="edu-template">
                <div class="rep-row">
                    <div class="rep-grid">
                        <div class="form-field"><label>Qualification / Degree</label><input type="text" name="edu_degree[]" list="ng-degree-list" class="input" placeholder="e.g. B.Sc."></div>
                        <div class="form-field"><label>Field of study</label><input type="text" name="edu_field[]" class="input" placeholder="e.g. Accounting"></div>
                        <div class="form-field"><label>School</label><input type="text" name="edu_school[]" class="input" placeholder="e.g. University of Lagos"></div>
                        <div class="form-field"><label>Start year</label><input type="text" name="edu_start_year[]" class="input" maxlength="4" placeholder="2014"></div>
                        <div class="form-field"><label>End year</label><input type="text" name="edu_end_year[]" class="input" maxlength="4" placeholder="2018"></div>
                        <div class="form-field"><label>Grade (optional)</label><input type="text" name="edu_grade[]" class="input" placeholder="e.g. Second Class Upper"></div>
                    </div>
                    <button type="button" class="btn btn-outline btn-sm rep-remove"><svg aria-hidden="true"><use href="#i-x"/></svg> Remove</button>
                </div>
            </template>
            <template id="cert-template">
                <div class="rep-row">
                    <div class="rep-grid">
                        <div class="form-field full"><label>Certification Name</label><input type="text" name="cert_name[]" class="input" placeholder="e.g. Project Management Professional (PMP)"></div>
                        <div class="form-field"><label>Issuing Organisation</label><input type="text" name="cert_org[]" class="input" placeholder="e.g. PMI / ICAN / AWS"></div>
                        <div class="form-field"><label>Credential ID (optional)</label><input type="text" name="cert_id[]" class="input" placeholder="e.g. ABC-12345"></div>
                        <div class="form-field"><label>Credential URL (optional)</label><input type="url" name="cert_url[]" class="input" placeholder="https://"></div>
                        <div class="form-field">
                            <label>Issue Date</label>
                            <div style="display:flex;gap:6px;">
                                <select name="cert_issue_month[]" class="select">
                                    <option value="">Month</option>
                                    <option>January</option><option>February</option><option>March</option><option>April</option><option>May</option><option>June</option><option>July</option><option>August</option><option>September</option><option>October</option><option>November</option><option>December</option>
                                </select>
                                <input type="text" name="cert_issue_year[]" class="input" maxlength="4" placeholder="Year">
                            </div>
                        </div>
                        <div class="form-field full">
                            <label class="rep-current"><input type="checkbox" class="cert-no-expire-toggle"> This credential does not expire</label>
                            <input type="hidden" name="cert_no_expire[]" value="0">
                        </div>
                        <div class="form-field cert-expiry-wrap">
                            <label>Expiry Date</label>
                            <div style="display:flex;gap:6px;">
                                <select name="cert_exp_month[]" class="select">
                                    <option value="">Month</option>
                                    <option>January</option><option>February</option><option>March</option><option>April</option><option>May</option><option>June</option><option>July</option><option>August</option><option>September</option><option>October</option><option>November</option><option>December</option>
                                </select>
                                <input type="text" name="cert_exp_year[]" class="input" maxlength="4" placeholder="Year">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline btn-sm rep-remove"><svg aria-hidden="true"><use href="#i-x"/></svg> Remove</button>
                </div>
            </template>

        </div><!-- /edit-wrap -->

        <!-- SAVE BUTTON -->
        <div class="bottom-actions" style="display:flex;justify-content:flex-end;margin-top:24px;gap:12px;">
            <a href="<?= base_url('candidate/profile') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <span class="btn-text">Update Profile</span>
                <span class="spinner d-none" role="status" aria-hidden="true"></span>
            </button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
requestAnimationFrame(function(){document.documentElement.classList.add('anim-ready')});
$(document).ready(function () {

    // ── Select2 for industries ──
    if ($.fn.select2) {
        $('.select2').select2({ placeholder: "Select industries", width: '100%' });
    }

    // ── Profile Picture Preview ──
    $('#profileInput').on('change', function (e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#profilePreviewImg').attr('src', e.target.result).show();
                $('#currentProfilePic').hide();
            };
            reader.readAsDataURL(file);
        } else if (file) {
            toastr.warning("Invalid image file selected.");
            $(this).val('');
        }
    });

    // ── Resume Preview ──
    $('#resumeInput').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            $('#resumePreviewText').text(file.name);
            $('#resumePreview').show();
        } else {
            $('#resumePreview').hide();
        }
    });

    // ── Portfolio URL auto-prefix ──
    $('#portfolioInput').on('blur', function () {
        let val = $(this).val().trim();
        if (val && !/^https?:\/\//i.test(val)) {
            $(this).val('https://' + val);
        }
    });

    // ── AI Summary Generate ──
    $('#aiSummaryBtn').on('click', function () {
        const btn = $(this);
        btn.prop('disabled', true).html('<svg aria-hidden="true"><use href="#i-refresh"/></svg> Generating…');

        const skills = $('textarea[name="skills"]').val();
        const jobTitle = $('input[name="job_title"]').val();
        const experience = $('input[name="experience_years"]').val();

        $.ajax({
            url: '<?= base_url('candidate/resumes/ai/generate-summary') ?>',
            method: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>',
                skills: [skills],
                experiences: [{ position: jobTitle, company: '', description: '' }]
            },
            success: function (res) {
                if (res.summary) {
                    $('#summaryTextarea').val(res.summary);
                    $('#summaryCount').text(res.summary.length);
                    toastr.success('AI summary generated!');
                }
            },
            error: function () {
                toastr.warning('AI generation failed — please try again.');
            },
            complete: function () {
                btn.prop('disabled', false).html('<svg aria-hidden="true"><use href="#i-zap"/></svg> AI generate');
            }
        });
    });

    // ── Instant Profile Visibility Toggle ──
    $('#visibility-toggle-edit').on('change', function () {
        var input = $(this);
        var makeVisible = input.is(':checked') ? 1 : 0;
        input.prop('disabled', true);

        var csrfName = '<?= csrf_token() ?>';
        var csrfVal  = $('input[name="' + csrfName + '"]').val() || '<?= csrf_hash() ?>';

        var postData = { is_visible: makeVisible };
        postData[csrfName] = csrfVal;

        $.ajax({
            url: '<?= base_url('candidate/profile/visibility') ?>',
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: postData,
            success: function (res) {
                if (res && res.success) {
                    if (typeof toastr !== 'undefined') toastr.success(res.message);
                    $('#visibility-hidden-input').val(res.is_visible ? '1' : '0');
                    if (res.is_visible) {
                        $('#visibility-banner').css({'background':'#f0fdf4', 'border-color':'#bbf7d0'});
                        $('#visibility-banner-icon').css('color', '#16a34a');
                        $('#visibility-banner-status').text('Profile Visibility: Visible to employers');
                        $('#visibility-toggle-label').text('Visible');
                    } else {
                        $('#visibility-banner').css({'background':'#fef2f2', 'border-color':'#fecaca'});
                        $('#visibility-banner-icon').css('color', '#dc2626');
                        $('#visibility-banner-status').text('Profile Visibility: Hidden from search');
                        $('#visibility-toggle-label').text('Hidden');
                    }
                    if (res.csrf_token && res.csrf_hash) {
                        $('input[name="' + res.csrf_token + '"]').val(res.csrf_hash);
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
            complete: function () {
                input.prop('disabled', false);
            }
        });
    });

    // ── AJAX Form Submit ──
    $('#editCandidateForm').on('submit', function (e) {
        e.preventDefault();

        const btn  = $('#submitBtn');
        const text = btn.find('.btn-text');
        const spin = btn.find('.spinner');

        // Portfolio URL normalization before submit
        const portfolioInput = $('#portfolioInput');
        let portfolioVal = portfolioInput.val().trim();
        if (portfolioVal && !/^https?:\/\//i.test(portfolioVal)) {
            portfolioInput.val('https://' + portfolioVal);
        }

        btn.prop('disabled', true);
        text.addClass('d-none');
        spin.removeClass('d-none');

        const formData = new FormData(this);

        $.ajax({
            url: this.action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                btn.prop('disabled', false);
                text.removeClass('d-none');
                spin.addClass('d-none');

                if (res.csrf_token && res.csrf_hash) {
                    $('input[name="' + res.csrf_token + '"]').val(res.csrf_hash);
                }

                if (res.status === 'error') {
                    if (res.errors) {
                        toastr.error(Object.values(res.errors).join('<br>'));
                    } else {
                        toastr.error(res.message || 'An error occurred.');
                    }
                } else {
                    toastr.success(res.message || 'Profile updated successfully.');
                    setTimeout(() => { window.location.href = '<?= base_url('candidate/profile') ?>'; }, 1200);
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false);
                text.removeClass('d-none');
                spin.addClass('d-none');

                let msg = 'Something went wrong.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                toastr.error(msg);
            }
        });
    });

    // ── Repeatable Work Experience / Education / Certification rows ──
    function wireRepRow(row) {
        // Work experience current job toggle
        var expToggle = row.querySelector('.exp-current-toggle');
        if (expToggle) {
            var expHidden = row.querySelector('input[name="exp_is_current[]"]');
            var expEndInput = row.querySelector('input[name="exp_end[]"]');
            var syncExp = function () {
                if (expHidden) expHidden.value = expToggle.checked ? '1' : '0';
                if (expEndInput) { expEndInput.disabled = expToggle.checked; if (expToggle.checked) expEndInput.value = ''; }
            };
            expToggle.addEventListener('change', syncExp);
            syncExp();
        }

        // Certification does not expire toggle
        var certToggle = row.querySelector('.cert-no-expire-toggle');
        if (certToggle) {
            var certHidden = row.querySelector('input[name="cert_no_expire[]"]');
            var certExpiryWrap = row.querySelector('.cert-expiry-wrap');
            var certExpirySelect = row.querySelector('select[name="cert_exp_month[]"]');
            var certExpiryYear = row.querySelector('input[name="cert_exp_year[]"]');
            var syncCert = function () {
                if (certHidden) certHidden.value = certToggle.checked ? '1' : '0';
                if (certExpiryWrap) {
                    certExpiryWrap.style.display = certToggle.checked ? 'none' : '';
                }
                if (certExpirySelect) certExpirySelect.disabled = certToggle.checked;
                if (certExpiryYear) certExpiryYear.disabled = certToggle.checked;
            };
            certToggle.addEventListener('change', syncCert);
            syncCert();
        }

        var removeBtn = row.querySelector('.rep-remove');
        if (removeBtn) removeBtn.addEventListener('click', function () { row.remove(); });
    }
    document.querySelectorAll('#exp-list .rep-row, #edu-list .rep-row, #cert-list .rep-row').forEach(wireRepRow);

    function addRepRow(templateId, listId) {
        var tpl = document.getElementById(templateId);
        var list = document.getElementById(listId);
        if (!tpl || !list) return;
        var node = tpl.content.firstElementChild.cloneNode(true);
        list.appendChild(node);
        wireRepRow(node);
    }
    var addExpBtn = document.getElementById('add-exp');
    if (addExpBtn) addExpBtn.addEventListener('click', function () { addRepRow('exp-template', 'exp-list'); });
    var addEduBtn = document.getElementById('add-edu');
    if (addEduBtn) addEduBtn.addEventListener('click', function () { addRepRow('edu-template', 'edu-list'); });
    var addCertBtn = document.getElementById('add-cert');
    if (addCertBtn) addCertBtn.addEventListener('click', function () { addRepRow('cert-template', 'cert-list'); });

});
</script>
<?= $this->endSection() ?>