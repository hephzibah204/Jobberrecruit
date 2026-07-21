<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* ── Profile-Edit page additional styles ── */
.edit-wrap { display: flex; flex-direction: column; gap: 20px; margin-top: 20px; }
.cv-card { border: 1px solid var(--border); border-radius: 14px; background: var(--card); overflow: hidden; transition: box-shadow .2s; }
.cv-card:focus-within { box-shadow: 0 0 0 3px rgba(var(--brand-rgb),.12); }
.cv-card.is-complete { border-color: #22c55e55; }
.cv-card-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 22px; cursor: pointer; list-style: none; user-select: none; }
.cv-card-header::-webkit-details-marker, .cv-card-header::marker { display: none; }
.cv-card-header:hover { background: #fafbfd; }
.cv-card-title { display: inline-flex; align-items: center; gap: 8px; font-family:'Sora',sans-serif; font-weight: 700; font-size: .94rem; color: var(--brand-deep); }
.cv-card-title svg { width:18px; height:18px; flex-shrink:0; }
.cv-card-done { font-size: .72rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
.cv-card-done.complete   { background: #dcfce7; color: #166534; }
.cv-card-done.incomplete { background: #fef9c3; color: #92400e; }
.cv-card-done.optional   { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
.cv-chev { width:18px; height:18px; fill:none; stroke:currentColor; stroke-width:2.2; transition: transform .25s; flex-shrink:0; color: var(--muted); }
details[open] .cv-chev { transform: rotate(90deg); }
.cv-card-body { padding: 6px 22px 22px; border-top: 1px solid var(--border); }
.cv-card-hint { font-size: .82rem; color: var(--muted); margin-bottom: 16px; margin-top: 12px; line-height: 1.6; }
.form-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--border); }
.autosave-note { font-size: .76rem; color: var(--muted); display: inline-flex; align-items: center; gap: 5px; }
.autosave-note svg { width:13px; height:13px; fill:none; stroke:currentColor; stroke-width:2; }
.ai-action { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
.ai-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; border: none; background: linear-gradient(135deg, var(--brand), #7c3aed); color: #fff; font-size: .78rem; font-weight: 700; cursor: pointer; white-space: nowrap; flex-shrink: 0; }
.ai-btn svg { width:13px; height:13px; fill:none; stroke:currentColor; stroke-width:2; }
.ai-btn:hover { opacity: .88; }
.char-count { font-size: .74rem; color: var(--muted); text-align: right; margin-top: 4px; }
.pref-pill-group { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px; }
.pref-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border: 1.5px solid var(--border); border-radius: 20px; cursor: pointer; font-size: .82rem; transition: all .15s; }
.pref-pill input { accent-color: var(--brand); }
.pref-pill:has(input:checked) { border-color: var(--brand); background: rgba(var(--brand-rgb),.06); color: var(--brand); }
.salary-range { display: flex; align-items: center; gap: 10px; }
.salary-range select { flex: 1; }
.salary-range span { color: var(--muted); font-size: .82rem; }
.skill-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 12px; }
.skill-row { display: grid; grid-template-columns: 1fr auto; gap: 10px; align-items: center; }
.skill-row-inner { display: flex; align-items: center; gap: 8px; }
.skill-dots { display: flex; gap: 4px; }
.skill-dot { width: 14px; height: 14px; border-radius: 50%; border: 2px solid var(--border); background: transparent; cursor: pointer; transition: all .15s; }
.skill-dot.active { background: var(--brand); border-color: var(--brand); }
.skill-level-label { font-size: .72rem; color: var(--muted); white-space: nowrap; }
.entry-remove { background: none; border: none; cursor: pointer; color: var(--muted); padding: 6px; border-radius: 6px; line-height: 0; }
.entry-remove:hover { color: #ef4444; }
.lang-row { display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: center; margin-bottom: 10px; }
.social-row { display: grid; grid-template-columns: 160px 1fr auto; gap: 10px; align-items: center; margin-bottom: 10px; }
.remove-lang, .remove-social { background: none; border: none; cursor: pointer; color: var(--muted); padding: 8px; border-radius: 6px; line-height: 0; }
.remove-lang:hover, .remove-social:hover { color: #ef4444; }
.ref-entry { background: var(--bg); border-radius: 10px; padding: 16px; margin-bottom: 14px; }
.ref-entry-header { font-size: .82rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 12px; }
.ref-toggle-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; font-size: .84rem; }
.jr-auto-certs { background: linear-gradient(135deg, #eff6ff, #f0fdf4); border: 1px solid #bfdbfe; border-radius: 10px; padding: 14px 18px; margin-bottom: 16px; }
.jr-auto-header { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: .84rem; color: var(--brand-deep); margin-bottom: 12px; }
.jr-auto-header svg { width:16px; height:16px; fill:none; stroke:currentColor; stroke-width:2; }
.jr-cert-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #bfdbfe44; }
.jr-cert-item:last-of-type { border-bottom: none; }
.jr-cert-icon { width: 36px; height: 36px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.jr-cert-icon svg { width:16px; height:16px; fill:none; stroke:#3b82f6; stroke-width:2; }
.jr-cert-body { flex: 1; }
.jr-cert-body strong { display: block; font-size: .84rem; color: var(--brand-deep); }
.jr-cert-body span { font-size: .74rem; color: var(--muted); }
.jr-verified-tag { display: inline-flex; align-items: center; gap: 4px; font-size: .68rem; font-weight: 700; color: #166534; background: #dcfce7; border-radius: 20px; padding: 2px 8px; }
.jr-verified-tag svg { width:10px; height:10px; fill:none; stroke:currentColor; stroke-width:2.5; }
.jr-auto-link { display: block; font-size: .76rem; color: var(--brand); margin-top: 10px; }
@media (max-width: 600px) {
  .lang-row { grid-template-columns: 1fr 1fr auto; }
  .social-row { grid-template-columns: 1fr; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Retrieve Escrow Wallet balance
$walletModel = new \App\Models\WalletModel();
$wallet = $walletModel->where('user_id', $user->id)->first();
$walletBalance = $wallet ? $wallet->balance : 0;

// Dynamic Profile completion calculation
$fields = ['full_name','dob','gender','phone','location','job_title','employment_type','skills','experience_years','education_level'];
$completed = 0;
foreach ($fields as $f) {
    if (!empty($candidate->$f)) $completed++;
}
if (!empty($candidate->resume)) $completed++;
$totalFields = count($fields) + 1;
$completion = round(($completed / $totalFields) * 100);

// Section completion status
$basicComplete  = !empty($candidate->full_name) && !empty($candidate->phone) && !empty($candidate->state_id);
$careerComplete = !empty($candidate->job_title) && !empty($candidateIndustryIds);
$docsComplete   = !empty($candidate->resume);
$summaryDone    = !empty($candidate->description);
$langsDone      = !empty($candidate->languages);
$portfolioDone  = !empty($candidate->portfolio);
?>

<div class="content">

    <!-- Header -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-edit"/></svg> Edit Profile</h1>
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
                    <div class="milestone-marker <?= $completion >= 60 ? 'achieved' : 'next' ?>" style="left:60%;" title="Earn ₦200 at 60%"><span class="milestone-label">₦200</span></div>
                    <div class="milestone-marker <?= $completion >= 80 ? 'achieved' : ($completion >= 60 ? 'next' : '') ?>" style="left:80%;" title="Earn ₦500 at 80%"><span class="milestone-label">₦500</span></div>
                </div>
                <span class="progress-text"><?= $completion ?>% Completed</span>
                <span class="progress-tip">
                    <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg>
                    <span>
                        <?php if ($completion < 60): ?>Complete <?= 60 - $completion ?>% more to unlock ₦200
                        <?php elseif ($completion < 80): ?>Complete <?= 80 - $completion ?>% more to unlock ₦500
                        <?php else: ?>All profile milestones achieved!
                        <?php endif; ?>
                    </span>
                </span>
            </div>
            <div class="progress-actions">
                <span class="wallet-chip <?= $walletBalance > 0 ? 'has-balance' : '' ?>">
                    <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg>
                    <span class="wallet-label">Wallet:</span>
                    <span>₦<?= number_format($walletBalance, 2) ?></span>
                </span>
            </div>
        </div>
    </div>

    <!-- Main form — all sections post to same endpoint -->
    <form action="<?= base_url('candidate/profile/edit') ?>"
          method="POST"
          enctype="multipart/form-data"
          id="editCandidateForm">

        <?= csrf_field() ?>

        <div class="edit-wrap">

            <!-- ══ 1. PERSONAL INFORMATION ══ -->
            <details class="cv-card <?= $basicComplete ? 'is-complete' : '' ?>" open>
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-users"/></svg> Personal Information</span>
                    <span class="cv-card-done <?= $basicComplete ? 'complete' : 'incomplete' ?>"><?= $basicComplete ? 'Complete' : 'Incomplete' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Provide your core contact details. Ensure your phone number is correct so recruiters can reach you easily.</div>

                    <!-- Profile photo upload -->
                    <div style="display:flex;gap:16px;align-items:center;margin-bottom:18px;">
                        <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;background:#f5f7fb;display:flex;align-items:center;justify-content:center;border:2px solid var(--border);flex-shrink:0;">
                            <?php if (!empty($candidate->profile_picture)): ?>
                                <img src="<?= base_url($candidate->profile_picture) ?>" id="currentProfilePic" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
                            <?php else: ?>
                                <svg aria-hidden="true" style="width:30px;height:30px;color:var(--muted);fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-users"/></svg>
                            <?php endif; ?>
                            <img id="profilePreviewImg" style="display:none;width:100%;height:100%;object-fit:cover;" alt="">
                        </div>
                        <div>
                            <label class="btn btn-outline btn-sm" for="profileInput" style="cursor:pointer;margin-bottom:6px;">Upload photo</label>
                            <input type="file" name="profile_picture" id="profileInput" accept="image/*" class="sr-only">
                            <p style="font-size:.74rem;color:var(--muted);margin:0;">JPEG or PNG · Max 2MB · Square crop recommended</p>
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
                            <label>Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="input" value="<?= old('phone', $candidate->phone) ?>" required placeholder="e.g. 08012345678">
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
                            <label>State of Residence <span class="text-danger">*</span></label>
                            <select name="state_id" class="select" required>
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
                            <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-check"/></svg> Save personal info
                        </button>
                        <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Saved when you click Update Profile at the bottom</span>
                    </div>
                </div>
            </details>

            <!-- ══ 2. JOB PREFERENCES ══ -->
            <details class="cv-card <?= $careerComplete ? 'is-complete' : '' ?>">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-star"/></svg> Job Preferences &amp; Career</span>
                    <span class="cv-card-done <?= $careerComplete ? 'complete' : 'incomplete' ?>"><?= $careerComplete ? 'Complete' : 'Incomplete' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Tell us what you are looking for. This powers our job-matching engine — the more you fill in, the better your matches.</div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Target Job Title <span class="text-danger">*</span></label>
                            <input type="text" name="job_title" class="input" value="<?= old('job_title', $candidate->job_title) ?>" placeholder="e.g. Senior Software Engineer" required>
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
                            <label>Target Industries (Hold Ctrl/Cmd to select multiple) <span class="text-danger">*</span></label>
                            <select class="select select2" name="industry_ids[]" multiple required style="min-height:120px;">
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

            <!-- ══ 3. PROFESSIONAL SUMMARY ══ -->
            <details class="cv-card <?= $summaryDone ? 'is-complete' : '' ?>">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-note"/></svg> Professional Summary</span>
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
                        <textarea name="description" id="summaryTextarea" class="input" rows="5" maxlength="600"
                            placeholder="e.g. Results-driven Marketing Manager with 5+ years of experience..."
                            oninput="document.getElementById('summaryCount').textContent=this.value.length"><?= old('description', $candidate->description ?? '') ?></textarea>
                        <div class="char-count"><span id="summaryCount"><?= strlen($candidate->description ?? '') ?></span> / 600</div>
                    </div>
                </div>
            </details>

            <!-- ══ 4. SKILLS ══ -->
            <details class="cv-card <?= !empty($candidate->skills) ? 'is-complete' : '' ?>">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-zap"/></svg> Skills</span>
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

            <!-- ══ 5. LANGUAGES ══ -->
            <details class="cv-card <?= $langsDone ? 'is-complete' : '' ?>">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-chat"/></svg> Languages</span>
                    <span class="cv-card-done <?= $langsDone ? 'complete' : 'incomplete' ?>"><?= $langsDone ? 'Complete' : 'Incomplete' ?></span>
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

            <!-- ══ 6. PORTFOLIO &amp; WORK SAMPLES ══ -->
            <details class="cv-card <?= $portfolioDone ? 'is-complete' : '' ?>">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-globe"/></svg> Portfolio &amp; Work Samples <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px;">(optional)</span></span>
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

            <!-- ══ 7. CERTIFICATIONS (auto-sync from training) ══ -->
            <details class="cv-card">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-award"/></svg> Licences &amp; Certifications <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px;">(optional)</span></span>
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
                    <?php else: ?>
                    <div class="jr-auto-certs">
                        <div class="jr-auto-header"><svg aria-hidden="true"><use href="#i-award"/></svg> JobberRecruit Verified Certificates</div>
                        <p style="font-size:.82rem;color:var(--muted);">No certificates yet. <a href="<?= base_url('training') ?>">Complete a course</a> to earn a verifiable certificate that automatically appears on your profile and CV.</p>
                    </div>
                    <?php endif; ?>
                    <div class="cv-card-hint">External licences and certifications (e.g. PMP, ICAN, COREN) strengthen your profile for senior roles. <a href="<?= base_url('training') ?>">Browse courses →</a></div>
                </div>
            </details>

            <!-- ══ 8. DOCUMENTS — CV Upload ══ -->
            <details class="cv-card <?= $docsComplete ? 'is-complete' : '' ?>">
                <summary class="cv-card-header">
                    <span class="cv-card-title"><svg aria-hidden="true"><use href="#i-doc"/></svg> Profile Picture &amp; Resume</span>
                    <span class="cv-card-done <?= $docsComplete ? 'complete' : 'optional' ?>"><?= $docsComplete ? 'Complete' : 'Optional' ?></span>
                    <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </summary>
                <div class="cv-card-body">
                    <div class="cv-card-hint">Upload your latest resume in PDF or Word format. Your CV is shared with employers when you apply.</div>
                    <div class="form-grid">
                        <!-- RESUME -->
                        <div class="form-field">
                            <label>Resume / CV Document</label>
                            <input type="file" name="resume" id="resumeInput" accept=".pdf,.doc,.docx" class="input" style="margin-bottom:8px;">
                            <?php if (!empty($candidate->resume)): ?>
                                <div style="display:flex;align-items:center;gap:10px;margin-top:6px;">
                                    <a href="<?= base_url($candidate->resume) ?>" target="_blank" class="btn btn-outline btn-sm">
                                        <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-eye"/></svg> View Current CV
                                    </a>
                                    <label style="display:flex;align-items:center;gap:6px;font-size:.78rem;">
                                        <input type="checkbox" name="remove_resume" value="1"> Remove CV
                                    </label>
                                </div>
                            <?php endif; ?>
                            <div id="resumePreview" style="display:none;margin-top:8px;">
                                <div style="background:#f5f7fb;border-radius:8px;padding:8px 12px;display:inline-flex;align-items:center;gap:8px;">
                                    <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:var(--brand);stroke-width:2;"><use href="#i-doc"/></svg>
                                    <span id="resumePreviewText" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </details>

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
        btn.prop('disabled', true).html('<svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-refresh"/></svg> Generating…');

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
                btn.prop('disabled', false).html('<svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg> AI generate');
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

});
</script>
<?= $this->endSection() ?>