<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>
<?php
$savedCvPath = $user ? ($candidate->resume ?? null) : null;
$hasSavedCv = $user && $savedCvPath && file_exists(FCPATH . $savedCvPath);
?>

  <!-- HERO STRIP -->
  <section class="apply-hero" aria-label="Apply for this job">
    <div class="container">
      <nav class="apply-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= base_url() ?>">Home</a>
        <svg aria-hidden="true" style="transform:rotate(-90deg); width: 12px; height: 12px;"><use href="#i-chev-down"/></svg>
        <a href="<?= base_url('jobs') ?>">Find Jobs</a>
        <svg aria-hidden="true" style="transform:rotate(-90deg); width: 12px; height: 12px;"><use href="#i-chev-down"/></svg>
        <a href="<?= base_url('jobs/' . $job->slug) ?>"><?= esc($job->title) ?></a>
        <svg aria-hidden="true" style="transform:rotate(-90deg); width: 12px; height: 12px;"><use href="#i-chev-down"/></svg>
        <span style="opacity:.7">Apply</span>
      </nav>
    </div>
  </section>

  <div class="container">
    <div class="apply-layout">

      <!-- MAIN: job summary + description -->
      <div class="apply-main">

        <div class="apply-jobcard">
          <div class="apply-jobcard-head">
            <div class="apply-jobcard-logo" aria-hidden="true">
              <?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'CV' : esc(substr($job->employer_name ?? 'C', 0, 2)) ?>
            </div>
            <div class="apply-jobcard-body">
              <h1 class="apply-jobcard-title"><?= esc($job->title) ?></h1>
              <div class="apply-jobcard-co">at <strong><?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Confidential Employer' : esc($job->employer_name) ?></strong>
                <?php if (empty($job->anonymous) && empty($job->is_anonymous) && !empty($job->show_trust_badge)): ?>
                  <button type="button" class="verified-check" aria-label="Verified employer"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span></button>
                <?php endif; ?>
              </div>
              <div class="apply-jobcard-badges">
                <span class="detail-badge db-type"><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= ucfirst(esc($job->job_type)) ?></span>
                <?php if ($job->featured): ?>
                  <span class="detail-badge db-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="apply-jobcard-actions">
              <button class="save-btn" id="saveJobBtn" data-job-id="<?= $job->id ?>" aria-label="Save job" data-saved="<?= $isSaved ? 'true' : 'false' ?>">
                <svg aria-hidden="true"><use href="#i-bookmark"/></svg> <span><?= $isSaved ? 'Unsave' : 'Save' ?></span>
              </button>
            </div>
          </div>
        </div>

        <details class="apply-desc" id="apply-desc-details" open>
          <summary class="apply-desc-summary">
            <h2><svg aria-hidden="true"><use href="#i-doc"/></svg> Job description</h2>
            <svg class="apply-desc-chev" aria-hidden="true"><use href="#i-chev-down"/></svg>
            <span class="apply-desc-hint" style="display:none">Tap to view qualifications, responsibilities &amp; pay</span>
          </summary>
          <div class="apply-desc-body text-wrap">
            <div class="text-muted">
              <?= $job->description ? $job->description : '<p>No job description provided.</p>' ?>
            </div>
            <?php if (!empty($job->requirements)): ?>
              <h3>Requirements</h3>
              <div class="text-muted"><?= $job->requirements ?></div>
            <?php endif; ?>
            <?php if (!empty($job->application)): ?>
              <h3>Application Guidelines</h3>
              <div class="text-muted"><?= $job->application ?></div>
            <?php endif; ?>
          </div>
        </details>
      </div>

      <!-- APPLY FORM -->
      <aside>
        <?= form_open_multipart(base_url("job/application/{$job->id}"), [
            'id' => 'jobApplicationForm',
            'class' => 'apply-form-card needs-validation',
            'novalidate' => true
        ], ['job_id' => (string)$job->id]) ?>

          <h2 class="apply-form-title"><svg aria-hidden="true"><use href="#i-send"/></svg> Apply for this position</h2>

          <!-- GUEST NOTICE -->
          <?php if (!$user): ?>
            <div class="guest-notice" id="guest-notice">
              <svg aria-hidden="true"><use href="#i-flag"/></svg>
              <span>You're applying as a guest. <a href="<?= base_url('login') ?>">Log in</a> or <a href="<?= base_url('register') ?>">create an account</a> to save your CV and track applications.</span>
            </div>
          <?php endif; ?>

          <!-- Display Validation Errors -->
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger ajax-alert">
              <strong>Please fix the errors below:</strong>
              <ul>
                <?php foreach ($errors as $error): ?>
                  <li><?= esc($error) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if (!$user): ?>
            <div class="form-row">
              <div class="form-group">
                <label for="first_name">First Name <span class="req">*</span></label>
                <input class="form-input" type="text" id="first_name" name="first_name" placeholder="John" required maxlength="50">
                <div class="invalid-feedback">Please enter your first name.</div>
              </div>
              <div class="form-group">
                <label for="last_name">Last Name <span class="req">*</span></label>
                <input class="form-input" type="text" id="last_name" name="last_name" placeholder="Doe" required maxlength="50">
                <div class="invalid-feedback">Please enter your last name.</div>
              </div>
            </div>

            <div class="form-group">
              <label for="email">Email Address <span class="req">*</span></label>
              <input class="form-input" type="email" id="email" name="email" placeholder="john.doe@example.com" required maxlength="100">
              <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>

            <div class="form-group">
              <label for="phone">Phone Number <span class="req">*</span></label>
              <input class="form-input" type="tel" id="phone" name="phone" placeholder="+234 800 000 0000" required>
              <div class="invalid-feedback">Please enter a valid phone number.</div>
            </div>
          <?php endif; ?>

          <div class="form-group">
            <label for="cover_letter">Cover Letter <span class="opt">(optional)</span> <span class="form-charcount"><span id="cover-count">0</span> / 2000</span></label>
            <textarea class="form-textarea" id="cover_letter" name="cover_letter" maxlength="2000" placeholder="Why are you a great fit for this role? Highlight your relevant experience, skills, and enthusiasm."></textarea>
            <button type="button" class="ai-generate-btn" id="ai-cover-letter-btn" <?= !auth()->loggedIn() ? 'disabled title="Login required"' : '' ?>><svg aria-hidden="true"><use href="#i-bot"/></svg> Generate with AI</button>
            <p class="form-hint">Tailor your message to the job description. Be concise and professional.</p>
          </div>

          <div class="form-group">
            <label for="cv_file">Attach Your CV <span class="req">*</span></label>
            <?php if ($hasSavedCv): ?>
              <div class="radio-group mb-2">
                <label class="radio-option">
                  <input type="radio" name="cv_source" id="use_saved_cv" value="saved" checked>
                  <span>Use my saved CV (<?= esc(basename($savedCvPath)) ?>)</span>
                </label>
                <label class="radio-option">
                  <input type="radio" name="cv_source" id="upload_new_cv" value="upload">
                  <span>Upload a new CV</span>
                </label>
              </div>
              <div id="new_cv_container" style="display: none;">
                <label class="file-upload" for="cv_file" id="f-cv-label">
                  <svg class="file-upload-ic" aria-hidden="true"><use href="#i-doc"/></svg>
                  <div class="file-upload-label">Choose file</div>
                  <div class="file-upload-name" id="cv-filename">No file chosen · Max 5MB — PDF, DOC, DOCX</div>
                  <input type="file" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx">
                </label>
              </div>
            <?php else: ?>
              <label class="file-upload" for="cv_file" id="f-cv-label">
                <svg class="file-upload-ic" aria-hidden="true"><use href="#i-doc"/></svg>
                <div class="file-upload-label">Choose file</div>
                <div class="file-upload-name" id="cv-filename">No file chosen · Max 5MB — PDF, DOC, DOCX</div>
                <input type="file" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx" required>
              </label>
            <?php endif; ?>
            <p class="form-error" id="cv-error" hidden></p>
          </div>

          <div class="form-group">
            <label for="f-linkedin">LinkedIn / Portfolio URL <span class="opt">(optional)</span></label>
            <input class="form-input" type="url" id="f-linkedin" name="linkedin_url" placeholder="https://linkedin.com/in/yourname">
          </div>

          <!-- PRE-SCREENING QUESTIONS (ATS) -->
          <?php if (!empty($questions)): ?>
            <div class="mb-4">
              <h3 style="font-size:1rem;margin-bottom:12px;border-bottom:1px solid var(--border);padding-bottom:6px;">Pre-screening Questions</h3>
              <?php foreach ($questions as $q): ?>
                <div class="form-group">
                  <label class="form-label"><?= esc($q->question_text) ?> <?= $q->is_required ? '<span class="req">*</span>' : '' ?></label>
                  
                  <?php if ($q->question_type === 'text'): ?>
                    <textarea name="answers[<?= $q->id ?>]" class="form-textarea" rows="2" placeholder="Your answer..." <?= $q->is_required ? 'required' : '' ?>></textarea>
                  
                  <?php elseif ($q->question_type === 'yes_no'): ?>
                    <div class="radio-group">
                      <label class="radio-option">
                        <input type="radio" name="answers[<?= $q->id ?>]" value="Yes" id="q-<?= $q->id ?>-yes" <?= $q->is_required ? 'required' : '' ?>>
                        <span>Yes</span>
                      </label>
                      <label class="radio-option">
                        <input type="radio" name="answers[<?= $q->id ?>]" value="No" id="q-<?= $q->id ?>-no">
                        <span>No</span>
                      </label>
                    </div>

                  <?php elseif (in_array($q->question_type, ['select', 'multiple_choice'])): ?>
                    <select name="answers[<?= $q->id ?>]" class="form-select" <?= $q->is_required ? 'required' : '' ?>>
                      <option value="">Select an option</option>
                      <?php 
                        $opts = !empty($q->options) ? $q->options : ($q->options ?? '');
                        foreach (explode(',', $opts) as $option): 
                      ?>
                        <option value="<?= trim(esc($option)) ?>"><?= trim(esc($option)) ?></option>
                      <?php endforeach; ?>
                    </select>

                  <?php elseif (in_array($q->question_type, ['radio'])): ?>
                    <div class="radio-group">
                      <?php foreach (explode(',', $q->options ?? '') as $option): ?>
                        <label class="radio-option">
                          <input type="radio" name="answers[<?= $q->id ?>]" value="<?= trim(esc($option)) ?>" id="q-<?= $q->id ?>-<?= md5(trim($option)) ?>" <?= $q->is_required ? 'required' : '' ?>>
                          <span><?= trim(esc($option)) ?></span>
                        </label>
                      <?php endforeach; ?>
                    </div>

                  <?php elseif ($q->question_type === 'checkbox'): ?>
                    <div class="radio-group">
                      <?php foreach (explode(',', $q->options ?? '') as $option): ?>
                        <label class="radio-option">
                          <input type="checkbox" name="answers[<?= $q->id ?>][]" value="<?= trim(esc($option)) ?>" id="q-<?= $q->id ?>-<?= md5(trim($option)) ?>">
                          <span><?= trim(esc($option)) ?></span>
                        </label>
                      <?php endforeach; ?>
                    </div>

                  <?php else: ?>
                    <textarea name="answers[<?= $q->id ?>]" class="form-textarea" rows="2" placeholder="Your answer..." <?= $q->is_required ? 'required' : '' ?>></textarea>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <details class="ref-disclosure form-group">
            <summary class="ref-summary">
              <span>Professional References <span class="opt">(optional)</span></span>
              <svg class="ref-chev" aria-hidden="true"><use href="#i-chev-down"/></svg>
            </summary>
            <div class="ref-disclosure-body">
              <div id="references-container">
                <div class="reference-row mb-2 p-3 border rounded bg-light">
                  <div class="row g-2">
                    <div class="col-md-4">
                      <input type="text" name="ref_name[]" class="form-input form-control-sm" placeholder="Full Name">
                    </div>
                    <div class="col-md-4">
                      <input type="text" name="ref_title[]" class="form-input form-control-sm" placeholder="Job Title">
                    </div>
                    <div class="col-md-3">
                      <input type="email" name="ref_email[]" class="form-input form-control-sm" placeholder="Email">
                    </div>
                    <div class="col-md-1 text-end">
                      <button type="button" class="ref-remove remove-ref" style="display:none;">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <button type="button" id="add-reference" class="add-ref-btn mt-1">
                <svg aria-hidden="true"><use href="#i-plus"/></svg> Add Reference
              </button>
            </div>
          </details>

          <div class="form-group">
            <label for="availability">When can you start? <span class="req">*</span></label>
            <select name="availability" id="availability" class="form-select" required>
              <option value="">Select availability</option>
              <option value="immediate">Immediately</option>
              <option value="1_week">Within 1 week</option>
              <option value="2_weeks">Within 2 weeks</option>
              <option value="1_month">Within 1 month</option>
              <option value="notice_period">After serving notice period</option>
            </select>
            <div class="invalid-feedback">Please select your availability.</div>
          </div>

          <div class="form-group">
            <label for="salary_expectation">Expected Salary (<?= esc($job->currency ?? 'NGN') ?>) <span class="opt">(optional)</span></label>
            <input type="text" name="salary_expectation" id="salary_expectation" class="form-input" placeholder="e.g., 500,000 - 700,000">
          </div>

          <div class="form-group">
            <label class="form-label fw-semibold">Eligibility to Work <span class="req">*</span></label>
            <div class="radio-group">
              <label class="radio-option">
                <input type="radio" name="work_eligibility" id="eligible_yes" value="yes" required>
                <span>Yes, I am legally authorized to work here</span>
              </label>
              <label class="radio-option">
                <input type="radio" name="work_eligibility" id="eligible_no" value="no">
                <span>No, I would require sponsorship</span>
              </label>
            </div>
          </div>

          <div class="consent-row">
            <input type="checkbox" name="consent" id="consent" required>
            <span>I consent to the processing of my personal data in accordance with the <a href="<?= base_url('privacy-policy') ?>" target="_blank">Privacy Policy</a>.</span>
          </div>

          <?php if (env('recaptcha_site_key')): ?>
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
          <?php endif; ?>

          <button type="submit" id="submitBtn" class="btn btn-primary apply-submit" disabled>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Submit Application <svg aria-hidden="true" style="width:16px;height:16px;margin-left:4px;"><use href="#i-send"/></svg>
          </button>

        <?= form_close() ?>
      </aside>
    </div>
  </div>

  <!-- Reusable SVG icon sprite (defined once, referenced via <use>) -->
  <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
      <symbol id="i-bag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></symbol>
      <symbol id="i-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></symbol>
      <symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></symbol>
      <symbol id="i-bookmark-fill" viewBox="0 0 24 24" fill="currentColor"><path d="M17 3H7a2 2 0 0 0-2 2v16l7-5 7 5V5a2 2 0 0 0-2-2z"/></symbol>
      <symbol id="i-verified-disc" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></symbol>
      <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></symbol>
      <symbol id="i-chev-down" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
      <symbol id="i-send" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></symbol>
      <symbol id="i-flag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></symbol>
      <symbol id="i-bot" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/><line x1="8" y1="16" x2="8.01" y2="16"/><line x1="16" y1="16" x2="16.01" y2="16"/></symbol>
      <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></symbol>
    </defs>
  </svg>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
:root {
  --white: #ffffff;
  --text: var(--text-dark, #1E293B);
  --muted: var(--text-muted, #64748B);
  --border: var(--border-light, #e2e8f0);
  --bg: var(--bg-white, #FFFFFF);
  --transition: all 0.25s ease-in-out;
}

.apply-hero {
  background: linear-gradient(150deg, #0A2F57 0%, #064A85 60%, var(--brand) 100%);
  color: var(--white); padding: 28px 0;
  padding-top: max(28px, calc(28px + env(safe-area-inset-top, 0px)));
}
.apply-breadcrumb { display: flex; align-items: center; gap: 7px; font-size: .76rem; opacity: .85; margin-bottom: 0; flex-wrap: wrap; }
.apply-breadcrumb a { color: rgba(255,255,255,.85); text-decoration: none; }
.apply-breadcrumb a:hover { color: #fff; text-decoration: underline; }
.apply-breadcrumb svg { width: 12px; height: 12px; opacity: .6; color: #fff; }

.apply-layout { display: grid; grid-template-columns: 1fr 460px; gap: 28px; align-items: start; padding: 28px 0 64px; }
.apply-main { min-width: 0; }

/* Job summary card (left) */
.apply-jobcard { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 24px; margin-bottom: 20px; }
.apply-jobcard-head { display: flex; gap: 16px; align-items: flex-start; flex-wrap: wrap; }
.apply-jobcard-logo { width: 58px; height: 58px; border-radius: 12px; background: var(--brand-light); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-weight: 700; color: var(--brand); flex-shrink: 0; }
.apply-jobcard-body { flex: 1; min-width: 220px; }
.apply-jobcard-title { font-family: 'Sora', sans-serif; font-size: 1.2rem; font-weight: 800; line-height: 1.25; margin-bottom: 6px; }
.apply-jobcard-co { font-size: .88rem; color: var(--muted); display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.apply-jobcard-co strong { color: var(--text); font-weight: 600; }
.apply-jobcard-badges { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
.detail-badge {
  display: inline-flex; align-items: center; gap: 5px; font-size: .72rem; font-weight: 700;
  padding: 4px 11px; border-radius: 20px; letter-spacing: .02em;
}
.detail-badge svg { width: 12px; height: 12px; }
.db-type { background: var(--brand-light); color: var(--brand); }
.db-featured { background: var(--accent); color: var(--brand-deep); }
.apply-jobcard-actions { display: flex; gap: 10px; align-self: flex-start; flex-wrap: wrap; }

.save-btn {
  background: none; border: 1.5px solid var(--border); border-radius: 8px;
  padding: 8px 14px; cursor: pointer; color: var(--muted);
  display: inline-flex; align-items: center; gap: 6px; font-size: .82rem;
  font-family: 'Inter', sans-serif; transition: var(--transition);
  min-height: 40px;
}
.save-btn svg { width: 15px; height: 15px; }
.save-btn:hover { border-color: var(--brand); color: var(--brand); }
.save-btn.saved { color: var(--success-color, #10B981); border-color: var(--success-color, #10B981); background: var(--success-light, #ecfdf5); }

/* Description card */
.apply-desc { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 24px; }
.apply-desc-summary { list-style: none; cursor: default; display: flex; align-items: center; justify-content: space-between; }
.apply-desc-summary::-webkit-details-marker { display: none; }
.apply-desc-chev { width: 18px; height: 18px; color: var(--muted); display: none; transition: transform .18s ease; flex-shrink: 0; }
.apply-desc[open] .apply-desc-chev { transform: rotate(180deg); }
.apply-desc-body { margin-top: 14px; }
.apply-desc h2 { font-family: 'Sora', sans-serif; font-size: 1.05rem; font-weight: 700; margin-bottom: 0; display: flex; align-items: center; gap: 8px; }
.apply-desc h2 svg { width: 17px; height: 17px; color: var(--brand); }
.apply-desc h3 { font-family: 'Sora', sans-serif; font-size: .95rem; font-weight: 700; margin: 18px 0 8px; }
.apply-desc h3:first-of-type { margin-top: 0; }
.apply-desc p { font-size: .89rem; line-height: 1.7; color: var(--text); margin-bottom: 10px; }
.apply-desc ul { list-style: none; margin: 0 0 8px; padding: 0; display: flex; flex-direction: column; gap: 8px; }
.apply-desc ul li { display: flex; gap: 9px; font-size: .88rem; line-height: 1.5; }
.apply-desc ul li svg { width: 16px; height: 16px; color: var(--success-color, #10B981); flex-shrink: 0; margin-top: 2px; }

/* ── Apply form (right, sticky) ── */
.apply-form-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 26px; position: sticky; top: 86px; }
.apply-form-title { font-family: 'Sora', sans-serif; font-size: 1.1rem; font-weight: 800; color: var(--brand); margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
.apply-form-title svg { width: 19px; height: 19px; color: var(--accent); }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 0; }
.form-group { margin-bottom: 14px; }
.form-group label { display: block; font-size: .82rem; font-weight: 600; color: var(--text); margin-bottom: 6px; text-align: left; }
.form-group label .req { color: var(--danger-color, #EF4444); }
.form-group label .opt { color: var(--muted); font-weight: 500; font-size: .76rem; }
.form-input, .form-select, .form-textarea {
  width: 100%; border: 1.5px solid var(--border); border-radius: 9px; padding: 10px 12px;
  font-family: 'Inter', sans-serif; font-size: .88rem; color: var(--text); background: #fff;
  transition: var(--transition);
}
.form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px rgba(13,96,158,.12); }
.form-input::placeholder, .form-textarea::placeholder { color: #9aa3b2; }
.form-textarea { resize: vertical; min-height: 120px; line-height: 1.5; }
.form-hint { font-size: .76rem; color: var(--muted); margin-top: 6px; text-align: left; }
.form-error { font-size: .78rem; color: var(--danger-color, #EF4444); margin-top: 7px; display: flex; align-items: center; gap: 6px; font-weight: 600; }
.form-error::before { content: "⚠"; font-size: .85rem; }
.file-upload.has-error { border-color: var(--danger-color, #EF4444); background: #fef2f2; }
.form-charcount { font-size: .74rem; color: var(--muted); float: right; }

.guest-notice {
  display: flex; gap: 10px; align-items: flex-start; background: var(--brand-light);
  border: 1px solid #cfe2f3; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px;
  font-size: .82rem; color: var(--text); line-height: 1.55; text-align: left;
}
.guest-notice svg { width: 16px; height: 16px; color: var(--brand); flex-shrink: 0; margin-top: 2px; }
.guest-notice a { font-weight: 700; color: var(--brand); text-decoration: none; }
.guest-notice a:hover { text-decoration: underline; }

.ai-generate-btn {
  display: inline-flex; align-items: center; gap: 7px; background: var(--brand-light);
  color: var(--brand); border: 1px solid #cfe2f3; border-radius: 8px; padding: 8px 14px;
  font-family: 'Inter', sans-serif; font-weight: 700; font-size: .8rem; cursor: pointer;
  margin-top: 10px; transition: var(--transition);
}
.ai-generate-btn:hover { background: var(--brand); color: #fff; }
.ai-generate-btn svg { width: 15px; height: 15px; }

.file-upload {
  border: 1.5px dashed var(--border); border-radius: 10px; padding: 16px; text-align: center;
  cursor: pointer; transition: var(--transition); background: var(--bg-light, #F8F9FA);
  display: block; width: 100%;
}
.file-upload:hover { border-color: var(--brand); background: var(--brand-light); }
.file-upload input[type="file"] { display: none; }
.file-upload-ic { width: 30px; height: 30px; color: var(--brand); margin: 0 auto 8px; }
.file-upload-label { font-size: .84rem; font-weight: 600; color: var(--brand); }
.file-upload-name { font-size: .78rem; color: var(--muted); margin-top: 4px; }

.ref-row { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 8px; margin-bottom: 10px; align-items: center; }
.ref-row .form-input { margin-bottom: 0; }
.ref-remove { width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border); background: #fff; color: var(--muted); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: var(--transition); flex-shrink: 0; }
.ref-remove:hover { border-color: var(--danger-color, #EF4444); color: var(--danger-color, #EF4444); }
.add-ref-btn {
  display: inline-flex; align-items: center; gap: 6px; background: none; border: 1.5px dashed var(--border);
  border-radius: 8px; padding: 8px 14px; font-family: 'Inter', sans-serif; font-weight: 600; font-size: .82rem;
  color: var(--brand); cursor: pointer; transition: var(--transition); width: 100%; justify-content: center;
}
.add-ref-btn:hover { border-color: var(--brand); background: var(--brand-light); }
.add-ref-btn svg { width: 15px; height: 15px; }

/* References disclosure — collapsed by default */
.ref-disclosure { border: 1px solid var(--border); border-radius: 9px; padding: 0; background: var(--bg-light, #F8F9FA); }
.ref-summary {
  list-style: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between;
  padding: 11px 13px; font-size: .82rem; font-weight: 600; color: var(--text);
}
.ref-summary::-webkit-details-marker { display: none; }
.ref-summary .opt { font-weight: 500; }
.ref-chev { width: 16px; height: 16px; color: var(--muted); transition: transform .18s ease; flex-shrink: 0; }
.ref-disclosure[open] .ref-chev { transform: rotate(180deg); }
.ref-disclosure-body { padding: 4px 13px 14px; }

.radio-group { display: flex; flex-direction: column; gap: 9px; }
.radio-option { display: flex; align-items: flex-start; gap: 9px; font-size: .86rem; cursor: pointer; text-align: left; }
.radio-option input[type="radio"], .radio-option input[type="checkbox"] { margin-top: 4px; accent-color: var(--brand); width: 16px; height: 16px; flex-shrink: 0; }

.consent-row { display: flex; gap: 10px; align-items: flex-start; margin: 18px 0; font-size: .8rem; color: var(--muted); line-height: 1.6; text-align: left; }
.consent-row input[type="checkbox"] { margin-top: 3px; accent-color: var(--brand); width: 16px; height: 16px; flex-shrink: 0; }
.consent-row a { color: var(--brand); font-weight: 600; text-decoration: none; }
.consent-row a:hover { text-decoration: underline; }

.apply-submit { width: 100%; justify-content: center; font-size: .96rem; padding: 13px; }
.apply-submit:disabled { opacity: .55; cursor: not-allowed; }

.verified-check {
  background: none; border: none; padding: 0; margin: 0; cursor: pointer; display: inline-flex; align-items: center; color: var(--brand); position: relative;
}
.verified-check svg { width: 14px; height: 14px; }
.verified-tip {
  position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%) translateY(4px);
  background: #ffffff; color: var(--text); font-size: .72rem; font-weight: 600; line-height: 1.4;
  white-space: nowrap; padding: 7px 11px; border-radius: 8px; border: 1px solid var(--border);
  box-shadow: 0 8px 24px rgba(10,47,87,.16); opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity .16s ease, transform .16s ease; z-index: 40; display: inline-flex; align-items: center; gap: 6px;
}
.verified-tip::after {
  content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
  border: 6px solid transparent; border-top-color: #ffffff; filter: drop-shadow(0 1px 0 var(--border));
}
.verified-check:hover .verified-tip {
  opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0);
}

@media (max-width: 900px) {
  .apply-layout { grid-template-columns: 1fr; gap: 20px; }
  .apply-layout aside { order: -1; }
  .apply-form-card { position: static; }
  .form-row { grid-template-columns: 1fr; gap: 0; }
  .ref-row { grid-template-columns: 1fr; }
  .apply-desc-summary { cursor: pointer; flex-wrap: wrap; }
  .apply-desc-chev { display: block; }
  .apply-desc-hint { display: block !important; width: 100%; font-size: .76rem; color: var(--muted); font-weight: 500; margin-top: 2px; }
  .apply-desc[open] .apply-desc-hint { display: none !important; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha_site_key') ?>"></script>
<script>
    // -------------------------------------------------
    // Bookmark toggle
    // -------------------------------------------------
    $("#saveJobBtn").on("click", function() {
        let btn = $(this);
        let jobId = btn.data("job-id");

        btn.prop("disabled", true).find('span').text("Processing...");

        $.ajax({
            url: "<?= site_url('jobs/toggle-save') ?>/" + jobId,
            method: "POST",
            success: function(response) {
                if (response.success) {
                    btn.toggleClass("saved", response.saved);
                    btn.find('span').text(response.saved ? "Unsave" : "Save");
                } else {
                    toastr.error(response.message);
                }
            },
            complete: function() {
                btn.prop("disabled", false);
            },
            error: function() {
                toastr.error("Network error. Try again.");
                btn.prop("disabled", false);
            }
        });
    });

    const form = document.getElementById('jobApplicationForm');
    const coverLetter = document.getElementById('cover_letter');
    const charCount = document.getElementById('cover-count');
    const cvSourceRadios = document.querySelectorAll('input[name="cv_source"]');
    const newCvContainer = document.getElementById('new_cv_container');
    const cvFileInput = document.getElementById('cv_file');
    const addRefBtn = document.getElementById('add-reference');
    const refContainer = document.getElementById('references-container');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = submitBtn.querySelector('.spinner-border');

    // Cover letter character counter
    if (coverLetter) {
        coverLetter.addEventListener('input', () => {
            charCount.textContent = coverLetter.value.length;
            coverLetter.classList.toggle('is-invalid', coverLetter.value.length > 1900);
        });
    }

    // CV source toggle
    cvSourceRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'upload' && radio.checked) {
                newCvContainer.style.display = 'block';
                cvFileInput.setAttribute('required', 'required');
            } else {
                newCvContainer.style.display = 'none';
                cvFileInput.removeAttribute('required');
            }
        });
    });

    // CV filename updating
    const filenameLabel = document.getElementById('cv-filename');
    if (cvFileInput) {
        cvFileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                filenameLabel.textContent = this.files[0].name;
            } else {
                filenameLabel.textContent = 'No file chosen · Max 5MB — PDF, DOC, DOCX';
            }
        });
    }

    // Add reference row
    addRefBtn?.addEventListener('click', () => {
        const rowCount = refContainer.children.length;
        if (rowCount >= 5) return;

        const newRow = document.createElement('div');
        newRow.className = 'reference-row mb-2 p-3 border rounded bg-light';
        newRow.innerHTML = `
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="ref_name[]" class="form-input form-control-sm" placeholder="Full Name">
                </div>
                <div class="col-md-4">
                    <input type="text" name="ref_title[]" class="form-input form-control-sm" placeholder="Job Title">
                </div>
                <div class="col-md-3">
                    <input type="email" name="ref_email[]" class="form-input form-control-sm" placeholder="Email">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="ref-remove remove-ref">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
                    </button>
                </div>
            </div>`;

        refContainer.appendChild(newRow);
        updateRemoveButtons();
    });

    // Remove reference
    refContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-ref')) {
            e.target.closest('.reference-row').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const rows = refContainer.querySelectorAll('.reference-row');
        rows.forEach((row, i) => {
            const btn = row.querySelector('.remove-ref');
            if(btn) {
                btn.style.display = rows.length > 1 ? 'block' : 'none';
            }
        });
    }
    updateRemoveButtons();

    // Enable submit only when consent is checked
    const consent = document.getElementById('consent');
    if (consent) {
        consent.addEventListener('change', () => {
            submitBtn.disabled = !consent.checked;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!form) return;

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Bootstrap validation
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            try {
                // 🔐 Generate fresh reCAPTCHA token
                let token;
                if (typeof grecaptcha !== 'undefined') {
                    token = await grecaptcha.execute(
                        '<?= env('recaptcha_site_key') ?>', {
                            action: 'apply_job'
                        }
                    );
                } else {
                    token = 'dev-bypass';
                }

                document.getElementById('g-recaptcha-response').value = token;

                const formData = new FormData(form);

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw data;
                }

                // ✅ SUCCESS
                window.location.href = data.redirect;

            } catch (error) {
                submitBtn.disabled = false;
                spinner.classList.add('d-none');

                document.querySelectorAll('.ajax-alert').forEach(el => el.remove());

                const alert = document.createElement('div');
                alert.className = 'alert alert-danger ajax-alert';

                if (error.errors) {
                    alert.innerHTML = `
                    <strong>Please fix the errors below:</strong>
                    <ul>
                        ${Object.values(error.errors).map(e => `<li>${e}</li>`).join('')}
                    </ul>
                `;
                } else {
                    alert.textContent = error.message || 'Submission failed. Please try again.';
                }

                form.prepend(alert);
                window.scrollTo({
                    top: form.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });

        // AI Cover Letter Generator
        const aiCoverBtn = document.getElementById('ai-cover-letter-btn');
        if (aiCoverBtn) {
            aiCoverBtn.addEventListener('click', async function() {
                const btn = this;
                const textarea = document.getElementById('cover_letter');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="ti ti-loader-2 ti-spin me-1"></i>Generating...';

                try {
                    const formData = new FormData();
                    formData.append('job_title', '<?= addslashes($job->title) ?>');
                    formData.append('company_name', '<?= addslashes($job->company_name ?? '') ?>');
                    formData.append('job_description', '<?= addslashes(substr(strip_tags($job->description ?? ''), 0, 2000)) ?>');
                    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                    const response = await fetch('<?= base_url("candidate/resumes/ai/generate-cover-letter") ?>', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.status === 'success' || response.ok) {
                        textarea.value = data.cover_letter || data.data?.cover_letter || '';
                        textarea.dispatchEvent(new Event('input'));
                        toastr.success('Cover letter generated!');
                    } else {
                        toastr.error(data.message || 'Generation failed');
                    }
                } catch (err) {
                    toastr.error('AI generation failed. Please try again.');
                    console.error(err);
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
