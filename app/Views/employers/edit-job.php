<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>
<div class="page-hd">
  <div class="page-hd-left">
    <h1><svg aria-hidden="true" width="22" height="22"><use href="#i-edit"/></svg> Edit Job</h1>
    <p>Update your job posting details to keep it accurate and attract the best candidates.</p>
  </div>
  <div class="page-actions">
    <a href="<?= site_url('employer/jobs/view/' . $job->id) ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true" width="16" height="16"><use href="#i-arrow-l"/></svg> Back to Details
    </a>
  </div>
</div>

<?php if (session()->has('errors')): ?>
  <div class="notice notice--warn" role="alert" style="margin-bottom:20px;">
    <ul style="margin:0; padding-left:20px;">
      <?php foreach (session('errors') as $error): ?>
        <li><?= esc($error) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<!-- plan/credits banner -->
<?php if ($hasUnlimitedAccess): ?>
  <div class="notice notice--info" role="status" style="align-items:center; margin-bottom: 20px;">
    <svg aria-hidden="true"><use href="#i-zap"/></svg>
    <span><b>Unlimited Access Plan</b> — you have unlimited job postings. No credits will be deducted for edits.</span>
  </div>
<?php else: ?>
  <div class="notice notice--info" role="status" style="align-items:center; margin-bottom: 20px;">
    <svg aria-hidden="true"><use href="#i-wallet"/></svg>
    <span><b>Available Job Credits:</b> <b><?= number_format($creditBalance, 0) ?></b> (1 credit = 1 job posting)</span>
  </div>
  <?php if ($creditBalance <= 0): ?>
    <div class="notice notice--warn" role="status" style="align-items:center; margin-bottom: 20px;">
      <svg aria-hidden="true"><use href="#i-x"/></svg>
      <span><b>No Job Credits Available!</b> You need credits to update or post jobs. <a href="<?= base_url('employer/pricing') ?>">Purchase credits</a>.</span>
    </div>
  <?php endif; ?>
<?php endif; ?>

<div class="post-wrap">
  <form id="edit-job-form" class="edit-job-form" method="POST" action="<?= site_url('employer/jobs/update') ?>" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="job_id" value="<?= esc($job->id) ?>">

    <!-- ══ 1. JOB OVERVIEW ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-overview">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-overview">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-briefcase"/></svg> Job Overview
        </h2>
        <svg class="job-card-chev" width="17" height="17"><use href="#i-arrow-up"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="form-grid">
          <div class="form-field">
            <label for="job-title">Job title <span class="required-star">*</span></label>
            <input type="text" id="job-title" name="title" value="<?= esc(old('title', $job->title)) ?>" autocomplete="off" placeholder="e.g. Senior Software Engineer" required maxlength="100">
          </div>
          <div class="form-field">
            <label for="job-type">Job type <span class="required-star">*</span></label>
            <select id="job-type" name="job_type" required>
              <option value="">Select job type</option>
              <option value="full-time" <?= old('job_type', $job->job_type) === 'full-time' ? 'selected' : '' ?>>Full-time</option>
              <option value="part-time" <?= old('job_type', $job->job_type) === 'part-time' ? 'selected' : '' ?>>Part-time</option>
              <option value="contract" <?= old('job_type', $job->job_type) === 'contract' ? 'selected' : '' ?>>Contract</option>
              <option value="internship" <?= old('job_type', $job->job_type) === 'internship' ? 'selected' : '' ?>>Internship</option>
              <option value="freelance" <?= old('job_type', $job->job_type) === 'freelance' ? 'selected' : '' ?>>Freelance</option>
            </select>
          </div>
          <div class="form-field">
            <label for="job-location">Location <span class="required-star">*</span></label>
            <select id="job-location" class="location-select" name="state_id" required>
              <option value="">Select state / Remote</option>
              <?php foreach ($states as $state): ?>
                <option value="<?= $state->id ?>" <?= old('state_id', $job->state_id) == $state->id ? 'selected' : '' ?>><?= esc($state->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-field">
            <label for="work-style">Work style <span class="required-star">*</span></label>
            <select id="work-style" name="location_type" required>
              <option value="">Select work style</option>
              <option value="on-site" <?= old('location_type', $job->location_type) === 'on-site' ? 'selected' : '' ?>>On-site</option>
              <option value="remote" <?= old('location_type', $job->location_type) === 'remote' ? 'selected' : '' ?>>Remote</option>
              <option value="hybrid" <?= old('location_type', $job->location_type) === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
            </select>
          </div>
          <div class="form-field">
            <label for="industry">Industry <span class="required-star">*</span></label>
            <select id="industry" class="industry-select" name="industry_id" required>
              <option value="">Select industry</option>
              <?php foreach ($industries as $industry): ?>
                <option value="<?= $industry->id ?>" <?= old('industry_id', $job->industry_id) == $industry->id ? 'selected' : '' ?>><?= esc($industry->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-field">
            <label for="job-category">Job category <span class="required-star">*</span></label>
            <select id="job-category" class="category-select" name="category_id" required>
              <option value="">Select category</option>
              <?php foreach ($categories as $category): ?>
                <option value="<?= $category->id ?>" <?= old('category_id', $job->category_id) == $category->id ? 'selected' : '' ?>><?= esc($category->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 2. COMPENSATION ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-comp">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-comp">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-wallet"/></svg> Compensation
        </h2>
        <svg class="job-card-chev" width="17" height="17"><use href="#i-arrow-up"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="cv-card-hint">Jobs with a displayed salary receive up to 3× more applications. You can show or hide the amount on the listing.</div>
        <div class="form-grid">
          <div class="form-field full">
            <label>Salary type <span class="required-star">*</span></label>
            <div class="method-group" style="gap:8px">
              <label class="method-pill"><input type="radio" name="salary_type" value="range" onchange="showSalaryFields(this.value)" <?= old('salary_type', $job->salary_type) === 'range' ? 'checked' : '' ?>> Salary range</label>
              <label class="method-pill"><input type="radio" name="salary_type" value="fixed" onchange="showSalaryFields(this.value)" <?= old('salary_type', $job->salary_type) === 'fixed' ? 'checked' : '' ?>> Fixed amount</label>
              <label class="method-pill"><input type="radio" name="salary_type" value="negotiable" onchange="showSalaryFields(this.value)" <?= old('salary_type', $job->salary_type) === 'negotiable' ? 'checked' : '' ?>> Negotiable / Undisclosed</label>
            </div>
          </div>

          <!-- Salary range / Fixed fields -->
          <div class="form-field salary-conditional visible form-grid cols-1" id="salary-range-wrap" style="grid-column:1/-1; padding:0; border:none; background:none;">
            <div class="form-field">
              <label for="salary-input">Salary amount or range <span class="required-star">*</span></label>
              <input type="text" id="salary-input" name="salary" value="<?= esc(old('salary', $job->salary)) ?>" placeholder="e.g. 200,000 - 400,000 or 500,000">
            </div>
          </div>

          <div class="form-field" id="salary-period-wrap" style="<?= old('salary_type', $job->salary_type) === 'negotiable' ? 'display:none' : '' ?>">
            <label for="salary-period">Pay period</label>
            <select id="salary-period" name="salary_period">
              <option value="monthly" <?= old('salary_period', $job->salary_period) === 'monthly' ? 'selected' : '' ?>>Per month</option>
              <option value="yearly" <?= old('salary_period', $job->salary_period) === 'yearly' ? 'selected' : '' ?>>Per annum</option>
              <option value="hourly" <?= old('salary_period', $job->salary_period) === 'hourly' ? 'selected' : '' ?>>Per hour</option>
            </select>
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 3. JOB DESCRIPTION ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-desc">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-desc">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-note"/></svg> Job Description
        </h2>
        <svg class="job-card-chev" width="17" height="17"><use href="#i-arrow-up"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="form-field">
          <label for="job-desc">Job description <span class="required-star">*</span></label>
          <div id="description-editor" style="height: 250px; background: #fff; border: 1px solid var(--border); border-radius: 8px;"></div>
          <input type="hidden" name="description" id="description-input" value="<?= esc(old('description', $job->description)) ?>" required>
        </div>
      </div>
    </details>

    <!-- ══ 4. REQUIREMENTS ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-req">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-req">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-spark"/></svg> Requirements
        </h2>
        <svg class="job-card-chev" width="17" height="17"><use href="#i-arrow-up"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="form-grid">
          <div class="form-field">
            <label for="min-edu">Minimum education <span class="required-star">*</span></label>
            <select id="min-edu" name="education_level" required>
              <option value="">Select minimum</option>
              <option value="High School" <?= old('education_level', $job->education_level) === 'High School' ? 'selected' : '' ?>>High School / WAEC / SSCE</option>
              <option value="Associate Degree" <?= old('education_level', $job->education_level) === 'Associate Degree' ? 'selected' : '' ?>>OND / Associate Degree</option>
              <option value="Bachelor's Degree" <?= old('education_level', $job->education_level) === "Bachelor's Degree" ? 'selected' : '' ?>>Bachelor's Degree (B.Sc / B.A / B.Eng)</option>
              <option value="Master's Degree" <?= old('education_level', $job->education_level) === "Master's Degree" ? 'selected' : '' ?>>Master's Degree (M.Sc / MBA / M.A)</option>
              <option value="PhD" <?= old('education_level', $job->education_level) === 'PhD' ? 'selected' : '' ?>>PhD / Ph.D</option>
            </select>
          </div>
          <div class="form-field">
            <label for="years-exp">Years of experience <span class="required-star">*</span></label>
            <select id="years-exp" name="experience_level" required>
              <option value="">Select range</option>
              <option value="Entry Level (0-2 years)" <?= old('experience_level', $job->experience_level) === 'Entry Level (0-2 years)' ? 'selected' : '' ?>>Entry Level (0-2 years)</option>
              <option value="Mid Level (2-5 years)" <?= old('experience_level', $job->experience_level) === 'Mid Level (2-5 years)' ? 'selected' : '' ?>>Mid Level (2-5 years)</option>
              <option value="Senior Level (5+ years)" <?= old('experience_level', $job->experience_level) === 'Senior Level (5+ years)' ? 'selected' : '' ?>>Senior Level (5+ years)</option>
              <option value="Executive Level" <?= old('experience_level', $job->experience_level) === 'Executive Level' ? 'selected' : '' ?>>Executive Level</option>
            </select>
          </div>
          <div class="form-field full">
            <label for="skills">Required skills <span class="opt">(separate with commas)</span></label>
            <input type="text" id="skills" name="skills" class="form-control" placeholder="e.g. JavaScript, Project Management, Excel" value="<?= esc(old('skills', $job->skills)) ?>">
          </div>
          <div class="form-field full">
            <label>Additional Requirements</label>
            <div id="requirements-editor" style="height: 180px; background: #fff; border: 1px solid var(--border); border-radius: 8px;"></div>
            <input type="hidden" name="requirements" id="requirements-input" value="<?= esc(old('requirements', $job->requirements)) ?>">
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 5. JOB CONDITIONS ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-conditions">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-conditions">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-calendar"/></svg> Job Conditions
        </h2>
        <svg class="job-card-chev" width="17" height="17"><use href="#i-arrow-up"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="form-grid">
          <div class="form-field">
            <label for="accommodation">Accommodation <span class="required-star">*</span></label>
            <select id="accommodation" name="accommodation" required>
              <option value="">Select Accommodation</option>
              <option value="available" <?= old('accommodation', $job->accommodation) === 'available' ? 'selected' : '' ?>>Available</option>
              <option value="not_available" <?= old('accommodation', $job->accommodation) === 'not_available' ? 'selected' : '' ?>>Not Available</option>
            </select>
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 6. APPLICATION SETTINGS ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-app">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-app">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-cog"/></svg> Application Settings
        </h2>
        <svg class="job-card-chev" width="17" height="17"><use href="#i-arrow-up"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="form-grid">
          <div class="form-field">
            <label for="app-deadline">Application deadline</label>
            <input type="date" id="app-deadline" name="application_deadline" value="<?= esc(old('application_deadline', $job->application_deadline)) ?>">
          </div>
          <div class="form-field">
            <label for="start-date">Expected start date</label>
            <input type="date" id="start-date" name="start_date" value="<?= esc(old('start_date', $job->start_date)) ?>">
          </div>
          <div class="form-field">
            <label for="contact-email">Contact Email <span class="required-star">*</span></label>
            <input type="email" id="contact-email" name="contact_email" value="<?= esc(old('contact_email', $job->contact_email)) ?>" required>
          </div>
          <div class="form-field">
            <label for="contact-phone">Contact Phone</label>
            <input type="text" id="contact-phone" name="contact_phone" value="<?= esc(old('contact_phone', $job->contact_phone)) ?>">
          </div>

          <div class="form-field full">
            <label>Application method <span class="required-star">*</span></label>
            <div class="method-group">
              <label class="method-pill">
                <input type="radio" name="application_method" value="form" <?= old('application_method', $job->application_method) === 'form' ? 'checked' : '' ?>>
                <svg width="16" height="16" aria-hidden="true"><use href="#i-doc"/></svg> JobberRecruit form
              </label>
              <label class="method-pill">
                <input type="radio" name="application_method" value="whatsapp" <?= old('application_method', $job->application_method) === 'whatsapp' ? 'checked' : '' ?>>
                <svg width="16" height="16" aria-hidden="true"><use href="#i-phone"/></svg> WhatsApp
              </label>
              <label class="method-pill">
                <input type="radio" name="application_method" value="email" <?= old('application_method', $job->application_method) === 'email' ? 'checked' : '' ?>>
                <svg width="16" height="16" aria-hidden="true"><use href="#i-mail"/></svg> Email
              </label>
              <label class="method-pill">
                <input type="radio" name="application_method" value="external" <?= old('application_method', $job->application_method) === 'external' ? 'checked' : '' ?>>
                <svg width="16" height="16" aria-hidden="true"><use href="#i-link"/></svg> External page
              </label>
            </div>

            <div id="method-detail" style="margin-top:10px; display:none;">
              <input type="text" id="method-detail-input" name="method_detail_placeholder" placeholder="">
            </div>
            
            <div id="whatsapp-field-wrap" class="conditional-method-field" style="margin-top: 10px; display: <?= old('application_method', $job->application_method) === 'whatsapp' ? 'block' : 'none' ?>;">
              <label for="whatsapp_link">WhatsApp Link <span class="required-star">*</span></label>
              <input type="url" name="whatsapp_link" id="whatsapp_link" class="input" placeholder="https://wa.me/2348000000000" value="<?= esc(old('whatsapp_link', $job->whatsapp_link)) ?>">
            </div>

            <div id="email-field-wrap" class="conditional-method-field" style="margin-top: 10px; display: <?= old('application_method', $job->application_method) === 'email' ? 'block' : 'none' ?>;">
              <label for="application_email">Application Email <span class="required-star">*</span></label>
              <input type="email" name="application_email" id="application_email" class="input" placeholder="jobs@company.com" value="<?= esc(old('application_email', $job->application_email)) ?>">
            </div>

            <div id="external-field-wrap" class="conditional-method-field" style="margin-top: 10px; display: <?= old('application_method', $job->application_method) === 'external' ? 'block' : 'none' ?>;">
              <label for="external_url">External Application URL <span class="required-star">*</span></label>
              <input type="url" name="external_url" id="external_url" class="input" placeholder="https://company.com/apply" value="<?= esc(old('external_url', $job->external_url)) ?>">
            </div>
          </div>

          <div class="form-field full">
            <label>Who can apply? <span class="required-star">*</span></label>
            <div class="method-group" style="gap:8px">
              <label class="method-pill"><input type="radio" name="application_access" value="general" <?= old('application_access', $job->application_access) === 'general' ? 'checked' : '' ?>> Anyone (recommended)</label>
              <label class="method-pill"><input type="radio" name="application_access" value="authenticated" <?= old('application_access', $job->application_access) === 'authenticated' ? 'checked' : '' ?>> Registered candidates only</label>
              <label class="method-pill"><input type="radio" name="application_access" value="guest" <?= old('application_access', $job->application_access) === 'guest' ? 'checked' : '' ?>> Guest Applicants Only</label>
            </div>
          </div>

          <div class="form-field full">
            <label>Application Instructions</label>
            <div id="application-instructions-editor" style="height: 150px; background: #fff; border: 1px solid var(--border); border-radius: 8px;"></div>
            <input type="hidden" name="application" id="application-input" value="<?= esc(old('application', $job->application)) ?>">
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 7. PREMIUM FEATURES ══ -->
    <?php if ($canFeature || $canPostAnonymous): ?>
      <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-boost">
        <summary class="job-card-header">
          <h2 class="job-card-title" id="h-boost">
            <svg aria-hidden="true" width="16" height="16"><use href="#i-star"/></svg> Premium Features
          </h2>
          <svg class="job-card-chev" width="17" height="17"><use href="#i-arrow-up"/></svg>
        </summary>
        <div class="job-card-body">
          <div style="display:flex; flex-direction:column; gap:12px">
            <?php if ($canFeature): ?>
              <div class="boost-row featured">
                <div class="boost-icon orange"><svg aria-hidden="true" width="16" height="16"><use href="#i-star"/></svg></div>
                <div class="boost-body">
                  <div class="boost-body-hd">
                    <strong>Featured Listing</strong>
                    <span class="boost-tag plan">Premium Benefit</span>
                  </div>
                  <p class="boost-body-desc">Pins your job to the top of search results and the homepage featured section.</p>
                </div>
                <label class="toggle" title="Feature this listing">
                  <input type="checkbox" name="is_featured" id="is-featured" value="1" <?= $job->is_featured ? 'checked' : '' ?>>
                  <span class="toggle-slider"></span>
                </label>
              </div>
            <?php endif; ?>

            <?php if ($canPostAnonymous): ?>
              <div class="boost-row urgent">
                <div class="boost-icon red"><svg aria-hidden="true" width="16" height="16"><use href="#i-zap"/></svg></div>
                <div class="boost-body">
                  <div class="boost-body-hd">
                    <strong>Post Anonymously</strong>
                    <span class="boost-tag plan">Premium Benefit</span>
                  </div>
                  <p class="boost-body-desc">Hides your company logo and name from the public job post.</p>
                </div>
                <label class="toggle" title="Post anonymously">
                  <input type="checkbox" name="is_anonymous" id="is-anonymous" value="1" <?= $job->is_anonymous ? 'checked' : '' ?>>
                  <span class="toggle-slider"></span>
                </label>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </details>
    <?php endif; ?>

    <!-- ══ 8. NOTIFICATION PREFERENCES ══ -->
    <?php
      $notificationPrefs = is_string($job->notification_preferences) ? json_decode($job->notification_preferences, true) : ($job->notification_preferences ?? []);
      $emailEnabled = $notificationPrefs['email'] ?? false;
      $inAppEnabled = $notificationPrefs['in_app'] ?? true;
      $notificationEmail = $notificationPrefs['notification_email_address'] ?? ($employer->contact_email ?? '');
    ?>
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-notifs">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-notifs">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-bell"/></svg> Notification Preferences
        </h2>
        <svg class="job-card-chev" width="17" height="17"><use href="#i-arrow-up"/></svg>
      </summary>
      <div class="job-card-body">
        <div style="display:flex; flex-direction:column; gap:16px">
          <label class="toggle-wrap" style="cursor:pointer">
            <span class="toggle">
              <input type="checkbox" name="notification_in_app" id="notificationInApp" value="1" <?= $inAppEnabled ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </span>
            <span>
              <span class="toggle-label">In-app notifications</span>
              <span class="toggle-sub">Alerts in your dashboard when candidates apply</span>
            </span>
          </label>

          <label class="toggle-wrap" style="cursor:pointer">
            <span class="toggle">
              <input type="checkbox" name="notification_email_toggle" id="notificationEmailToggle" value="1" <?= $emailEnabled ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </span>
            <span>
              <span class="toggle-label">Email notifications</span>
              <span class="toggle-sub">Receive email alerts for new applications</span>
            </span>
          </label>

          <div id="notificationEmailField" class="form-field" style="display: <?= $emailEnabled ? 'block' : 'none' ?>; margin-top: 10px;">
            <label for="notification_email">Notification Email Address</label>
            <input type="email" name="notification_email" id="notification_email" class="form-control" placeholder="<?= esc($employer->contact_email ?? '') ?>" value="<?= esc($notificationEmail) ?>">
            <small class="text-muted">Leave empty to use your company email</small>
          </div>
        </div>
      </div>
    </details>
  </form>
</div>

<div class="publish-bar" role="complementary" aria-label="Publish actions">
  <div class="container publish-bar-inner">
    <div class="publish-bar-info">
      <strong>Ready to update?</strong>
      <span>Your changes will go live immediately after saving</span>
    </div>
    <div class="publish-bar-actions">
      <a href="<?= site_url('employer/jobs/view/' . $job->id) ?>" class="emp-btn emp-btn-outline">Cancel</a>
      <button type="submit" class="emp-btn emp-btn-accent" form="edit-job-form" id="updateJobBtn">
        <svg aria-hidden="true" width="16" height="16"><use href="#i-check"/></svg> Update Job
      </button>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= site_url('employer/jobs/view/' . $job->id) ?>" class="emp-btn emp-btn-outline">Cancel</a>
<button type="submit" class="emp-btn emp-btn-accent" form="edit-job-form">Update Job</button>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
  .select2-container--bootstrap-5 {
    width: 100% !important;
  }
  .select2-container--bootstrap-5 .select2-selection {
    height: 44px !important;
    min-height: 44px !important;
    border: 1.5px solid var(--border) !important;
    border-radius: 9px !important;
    background-color: #fff !important;
    display: flex !important;
    align-items: center !important;
  }
  .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    display: flex !important;
    align-items: center !important;
    height: 100% !important;
    padding-left: 14px !important;
    color: var(--text) !important;
  }
  .select2-container--bootstrap-5.select2-container--focus .select2-selection,
  .select2-container--bootstrap-5.select2-container--open .select2-selection {
    border-color: var(--brand) !important;
    box-shadow: 0 0 0 3px rgba(8,97,169,.12) !important;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
  $(document).ready(function() {
    // Initialize Select2
    $('.location-select, .industry-select, .category-select').select2({
      theme: 'bootstrap-5',
      placeholder: 'Search or select...',
      allowClear: true,
      width: '100%'
    });

    // Toggle notification email field
    const emailToggle = document.getElementById('notificationEmailToggle');
    const emailField = document.getElementById('notificationEmailField');
    if (emailToggle) {
      emailToggle.addEventListener('change', function() {
        emailField.style.display = this.checked ? 'block' : 'none';
      });
    }

    // Initialize Quill editors
    const descriptionEditor = new Quill('#description-editor', {
      theme: 'snow',
      modules: {
        toolbar: [
          [{ 'header': [false, 1, 2, 3] }],
          ['bold', 'italic', 'underline'],
          ['link', 'blockquote'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          ['clean']
        ]
      },
      placeholder: 'Write your job description here...'
    });

    const requirementsEditor = new Quill('#requirements-editor', {
      theme: 'snow',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          ['link', 'blockquote'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          ['clean']
        ]
      },
      placeholder: 'Be specific about the requirements...'
    });

    const applicationInstructionsEditor = new Quill('#application-instructions-editor', {
      theme: 'snow',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          ['link', 'blockquote'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          ['clean']
        ]
      },
      placeholder: 'Be specific about the application process...'
    });

    // Set initial content
    if (document.getElementById('description-input').value) {
      descriptionEditor.root.innerHTML = document.getElementById('description-input').value;
    }
    if (document.getElementById('requirements-input').value) {
      requirementsEditor.root.innerHTML = document.getElementById('requirements-input').value;
    }
    if (document.getElementById('application-input').value) {
      applicationInstructionsEditor.root.innerHTML = document.getElementById('application-input').value;
    }

    // Update hidden inputs on text change
    descriptionEditor.on('text-change', function() {
      document.getElementById('description-input').value = descriptionEditor.root.innerHTML;
    });
    requirementsEditor.on('text-change', function() {
      document.getElementById('requirements-input').value = requirementsEditor.root.innerHTML;
    });
    applicationInstructionsEditor.on('text-change', function() {
      document.getElementById('application-input').value = applicationInstructionsEditor.root.innerHTML;
    });

    // Toggle application method fields
    function toggleApplicationMethod() {
      const method = document.querySelector('input[name="application_method"]:checked')?.value || 'form';
      
      // Hide all first
      document.getElementById('whatsapp-field-wrap').style.display = 'none';
      document.getElementById('email-field-wrap').style.display = 'none';
      document.getElementById('external-field-wrap').style.display = 'none';

      // Remove required attribute from all conditional inputs
      document.getElementById('whatsapp_link').removeAttribute('required');
      document.getElementById('application_email').removeAttribute('required');
      document.getElementById('external_url').removeAttribute('required');

      if (method === 'whatsapp') {
        document.getElementById('whatsapp-field-wrap').style.display = 'block';
        document.getElementById('whatsapp_link').setAttribute('required', 'required');
      } else if (method === 'email') {
        document.getElementById('email-field-wrap').style.display = 'block';
        document.getElementById('application_email').setAttribute('required', 'required');
      } else if (method === 'external') {
        document.getElementById('external-field-wrap').style.display = 'block';
        document.getElementById('external_url').setAttribute('required', 'required');
      }
    }

    document.querySelectorAll('input[name="application_method"]').forEach(radio => {
      radio.addEventListener('change', toggleApplicationMethod);
    });
    toggleApplicationMethod();

    // Toggle salary input visibility
    window.showSalaryFields = function(type) {
      const periodWrap = document.getElementById('salary-period-wrap');
      const salaryInputWrap = document.getElementById('salary-range-wrap');
      const salaryInput = document.getElementById('salary-input');

      if (type === 'negotiable') {
        periodWrap.style.display = 'none';
        salaryInputWrap.style.display = 'none';
        salaryInput.removeAttribute('required');
      } else {
        periodWrap.style.display = 'block';
        salaryInputWrap.style.display = 'block';
        salaryInput.setAttribute('required', 'required');
        salaryInput.placeholder = type === 'fixed' ? 'e.g. 500,000' : 'e.g. 200,000 - 400,000';
      }
    };

    // Form submission via AJAX
    $('#edit-job-form').on('submit', function(e) {
      e.preventDefault();

      const form = $(this);
      const submitBtn = $('#updateJobBtn');

      submitBtn.prop('disabled', true);
      submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

      $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            toastr.success(response.message);
            setTimeout(() => {
              window.location.href = response.redirect || '<?= site_url('employer/jobs/view/' . $job->id) ?>';
            }, 1500);
          } else {
            toastr.error(response.message || 'Failed to update job');
            if (response.errors) {
              $.each(response.errors, function(field, error) {
                toastr.error(error);
              });
            }
            submitBtn.prop('disabled', false);
            submitBtn.html('<svg aria-hidden="true" width="16" height="16"><use href="#i-check"/></svg> Update Job');
          }
        },
        error: function(xhr) {
          submitBtn.prop('disabled', false);
          submitBtn.html('<svg aria-hidden="true" width="16" height="16"><use href="#i-check"/></svg> Update Job');
          let message = 'An error occurred while updating the job.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
          }
          toastr.error(message);
        }
      });
    });
  });
</script>
<?= $this->endSection() ?>