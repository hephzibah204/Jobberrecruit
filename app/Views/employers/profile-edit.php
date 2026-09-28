<?php $page_title = 'Company Profile · Edit'; ?>
<?= $this->extend('layouts/employer') ?>



<?= $this->section('content') ?>
<div class="page-head">
  <div class="page-head-left">
    <h1><svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Company Profile</h1>
    <p>This information appears on all your job listings and your employer profile page.</p>
  </div>
  <div class="page-actions">
    <a href="<?= base_url('employer/profile') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to Profile
    </a>
  </div>
</div>

<!-- STICKY PROGRESS BAR -->
<div class="progress-bar">
  <div class="progress-inner">
    <div class="progress-left">
      <div class="progress-track">
        <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
      </div>
      <span class="progress-text" id="progress-pct">0% Completed</span>
    </div>
    <div class="progress-tip">
      <svg aria-hidden="true"><use href="#i-bulb"/></svg>
      <span id="progress-tip-text"></span>
    </div>
  </div>
</div>

<div class="profile-page">
  <form id="editEmployerForm" action="<?= base_url('employer/profile/edit') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="profile-wrap">

      <!-- ══ 1. COMPANY IDENTITY ══ -->
      <details class="cv-card" aria-labelledby="h-identity" open>
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-identity">
            <svg aria-hidden="true"><use href="#i-building"/></svg> Company Identity
          </h2>
          <span class="cv-card-done incomplete">Incomplete</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">
          <div class="cv-card-hint">Your company name and logo appear on every job listing and search result. Candidates make instant decisions based on these.</div>

          <!-- Company Logo Upload -->
          <div class="logo-upload">
            <?php
            $hasLogo = false;
            $logoSrc = '';
            if (!empty($employer->logo)) {
                if (filter_var($employer->logo, FILTER_VALIDATE_URL) || str_starts_with($employer->logo, 'http')) {
                    $hasLogo = true;
                    $logoSrc = $employer->logo;
                } elseif (file_exists(FCPATH . $employer->logo)) {
                    $hasLogo = true;
                    $logoSrc = base_url($employer->logo);
                }
            }
            ?>
            <label for="logo-input" class="logo-preview" id="logo-preview" title="Click to upload company logo">
              <?php if ($hasLogo): ?>
                <img src="<?= esc($logoSrc) ?>" alt="Company logo preview">
              <?php else: ?>
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <?php endif; ?>
              <div class="logo-overlay"><svg aria-hidden="true"><use href="#i-download"/></svg></div>
            </label>
            <input type="file" id="logo-input" name="logo" accept="image/png,image/jpeg,image/svg+xml,image/webp" class="sr-only" onchange="previewLogo(this)">
            <div class="logo-upload-body">
              <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" onclick="document.getElementById('logo-input').click()">
                <svg aria-hidden="true"><use href="#i-download"/></svg> Upload logo
              </button>
              <p>PNG, JPG, or SVG · Square recommended · Max 2MB</p>
              <p>Shown on job listings, search results, and your profile page</p>
            </div>
          </div>

          <div class="form-grid">
            <div class="form-field full">
              <label for="company-name">Company name <span class="opt">(contact us to change after setting)</span></label>
              <input type="text" id="company-name" name="company_name" autocomplete="organization" placeholder="e.g. Dangote Group Plc" value="<?= esc($employer->company_name ?? '') ?>">
              <span style="font-size:.76rem;color:var(--muted);margin-top:4px;display:block">
                🔒 Once set, <a href="<?= base_url('contact-us') ?>">contact us</a> to request a name change
              </span>
            </div>
            <div class="form-field full">
              <label for="company-tagline">Company tagline <span class="opt">(optional — shown below your name on listings)</span></label>
              <input type="text" id="company-tagline" name="tagline" autocomplete="off" placeholder="e.g. Africa's leading technology platform" maxlength="120" value="<?= esc($employer->tagline ?? '') ?>">
            </div>
            <div class="form-field full">
              <label>Industry <span class="required-star">*</span></label>
              <div class="ms-dropdown" id="industry-dropdown">
                <button type="button" class="ms-trigger" id="industry-trigger" aria-haspopup="listbox" aria-expanded="false">
                  <span class="ms-trigger-label placeholder" id="industry-trigger-label">Select industries…</span>
                  <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="ms-panel" id="industry-panel" role="listbox" aria-multiselectable="true">
                  <?php if (!empty($industries)): ?>
                    <?php foreach ($industries as $parentInd): ?>
                      <?php if (!empty($parentInd->children)): ?>
                        <div class="ms-group-label"><?= esc($parentInd->name) ?></div>
                        <?php foreach ($parentInd->children as $childInd): ?>
                          <label class="ms-option">
                            <input type="checkbox" name="industry_ids[]" value="<?= $childInd->id ?>" <?= in_array($childInd->id, $employerIndustryIds ?? []) ? 'checked' : '' ?>>
                            <?= esc($childInd->name) ?>
                          </label>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <label class="ms-option">
                          <input type="checkbox" name="industry_ids[]" value="<?= $parentInd->id ?>" <?= in_array($parentInd->id, $employerIndustryIds ?? []) ? 'checked' : '' ?>>
                          <?= esc($parentInd->name) ?>
                        </label>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="form-field">
              <label for="company-type">Company type</label>
              <select id="company-type" name="company_type">
                <option value="">Select type</option>
                <?php
                $types = ['Startup', 'SME (Small & Medium Enterprise)', 'Large Corporation', 'Multinational', 'Government Agency / Parastatal', 'NGO / Non-profit', 'Recruiting / Staffing Firm', 'Cooperative', 'Other'];
                foreach ($types as $type):
                  $sel = (isset($employer->company_type) && $employer->company_type == $type) ? 'selected' : '';
                ?>
                  <option value="<?= esc($type) ?>" <?= $sel ?>><?= esc($type) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-field">
              <label for="founded-year">Year founded <span class="opt">(optional)</span></label>
              <select id="founded-year" name="founded_year">
                <option value="">Select year</option>
                <?php
                $currYear = (int) date('Y');
                for ($y = $currYear; $y >= 1950; $y--):
                  $sel = (isset($employer->founded_year) && $employer->founded_year == $y) ? 'selected' : '';
                ?>
                  <option value="<?= $y ?>" <?= $sel ?>><?= $y ?></option>
                <?php endfor; ?>
                <option value="Before 1950" <?= (isset($employer->founded_year) && $employer->founded_year == 'Before 1950') ? 'selected' : '' ?>>Before 1950</option>
              </select>
            </div>
            <div class="form-field">
              <label for="num-employees">Number of employees <span class="required-star">*</span></label>
              <select id="num-employees" name="company_size" required>
                <option value="">Select range</option>
                <?php
                $ranges = ['1–5', '6–10', '11–50', '51–200', '201–500', '501–1,000', '1,001–5,000', '5,000+'];
                foreach ($ranges as $range):
                  $sel = (isset($employer->company_size) && $employer->company_size == $range) ? 'selected' : '';
                ?>
                  <option value="<?= esc($range) ?>" <?= $sel ?>><?= esc($range) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-field full">
              <label>Company-wide remote work policy</label>
              <p style="font-size:.8rem;color:var(--muted);margin-bottom:10px">This appears on your employer profile and gives candidates an immediate sense of your working culture.</p>
              <div class="pref-pill-group">
                <?php
                $policies = [
                  'fully_remote' => 'Fully remote',
                  'hybrid' => 'Hybrid',
                  'fully_onsite' => 'Fully on-site',
                  'flexible_by_role' => 'Flexible by role',
                  'not_specified' => 'Prefer not to specify'
                ];
                $currentPolicy = $employer->remote_policy ?? 'not_specified';
                foreach ($policies as $val => $lbl):
                ?>
                  <label class="pref-pill">
                    <input type="radio" name="remote_policy" value="<?= esc($val) ?>" <?= ($currentPolicy == $val) ? 'checked' : '' ?>>
                    <?= esc($lbl) ?>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('identity', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Save identity
            </button>
            <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Changes save automatically</span>
          </div>
        </div>
      </details>

      <!-- ══ 2. CONTACT & LOCATION ══ -->
      <details class="cv-card" aria-labelledby="h-contact">
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-contact">
            <svg aria-hidden="true"><use href="#i-phone"/></svg> Contact &amp; Location
          </h2>
          <span class="cv-card-done incomplete">Incomplete</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">
          <div class="cv-card-hint">Candidates verify companies are real and legitimate using this information. A complete contact section increases trust.</div>
          <div class="form-grid">
            <div class="form-field">
              <label for="contact-name">Contact person name <span class="required-star">*</span></label>
              <input type="text" id="contact-name" name="contact_name" placeholder="e.g. Jane Doe" value="<?= esc($employer->contact_name ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="contact-phone">Contact phone <span class="required-star">*</span></label>
              <input type="tel" id="contact-phone" name="contact_phone" placeholder="e.g. 07038399120" value="<?= esc($employer->contact_phone ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="contact-email">Contact email <span class="required-star">*</span></label>
              <input type="email" id="contact-email" name="contact_email" placeholder="e.g. hr@company.com" value="<?= esc($employer->contact_email ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="company-website">Website</label>
              <input type="url" id="company-website" name="website" placeholder="https://yourcompany.com" value="<?= esc($employer->website ?? '') ?>">
            </div>
            <div class="form-field full">
              <label for="company-address">Office address</label>
              <input type="text" id="company-address" name="company_address" placeholder="e.g. 3rd Floor, 123 Broad Street, Lagos Island" value="<?= esc($employer->company_address ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="company-state">State <span class="required-star">*</span></label>
              <select id="company-state" name="state_id">
                <option value="">Select state</option>
                <?php if (isset($states) && is_array($states)): ?>
                  <?php foreach ($states as $s): ?>
                    <option value="<?= $s->id ?>" <?= (isset($employer->state_id) && $employer->state_id == $s->id) ? 'selected' : '' ?>><?= esc($s->name) ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('contact', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Save contact info
            </button>
            <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Changes save automatically</span>
          </div>
        </div>
      </details>

      <!-- ══ 3. ABOUT THE COMPANY ══ -->
      <details class="cv-card" aria-labelledby="h-about">
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-about">
            <svg aria-hidden="true"><use href="#i-briefcase"/></svg> About the Company
          </h2>
          <span class="cv-card-done incomplete">Incomplete</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">
          <div class="cv-card-hint">Candidates read your company description before deciding whether to apply. A compelling description significantly improves application quality.</div>
          <div class="form-grid">
            <div class="form-field full">
              <label for="company-desc">Company description</label>
              <textarea id="company-desc" name="description" rows="6" maxlength="1200"
                placeholder="Describe what your company does, your mission, values, and what makes it a great place to work."
                oninput="updateCount('company-desc','desc-count',1200)"><?= esc($employer->description ?? '') ?></textarea>
              <div class="char-count"><span id="desc-count"><?= strlen($employer->description ?? '') ?></span> / 1,200</div>
            </div>
            <div class="form-field full">
              <label>Benefits &amp; perks</label>
              <p style="font-size:.8rem;color:var(--muted);margin-bottom:12px">Select what your company offers. Candidates filter and compare employers by these.</p>

              <?php
              $selectedBenefits = [];
              if (isset($employer->benefits)) {
                  if (is_array($employer->benefits)) {
                      $selectedBenefits = $employer->benefits;
                  } elseif (is_string($employer->benefits)) {
                      $decoded = json_decode($employer->benefits, true);
                      if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                          $selectedBenefits = $decoded;
                      } else {
                          $selectedBenefits = explode(',', $employer->benefits);
                      }
                  }
              }
              $selectedBenefits = array_map('trim', $selectedBenefits);
              ?>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Health &amp; Insurance</p>
              <div class="pref-pill-group" style="margin-bottom:14px">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="hmo" <?= in_array('hmo', $selectedBenefits) ? 'checked' : '' ?>> HMO / Health Insurance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="hmo_dependants" <?= in_array('hmo_dependants', $selectedBenefits) ? 'checked' : '' ?>> HMO covers dependants</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="life_insurance" <?= in_array('life_insurance', $selectedBenefits) ? 'checked' : '' ?>> Life Insurance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="dental_vision" <?= in_array('dental_vision', $selectedBenefits) ? 'checked' : '' ?>> Dental &amp; Vision</label>
              </div>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Financial</p>
              <div class="pref-pill-group" style="margin-bottom:14px">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="pension" <?= in_array('pension', $selectedBenefits) ? 'checked' : '' ?>> Contributory Pension (CPS)</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="13th_month" <?= in_array('13th_month', $selectedBenefits) ? 'checked' : '' ?>> 13th Month Salary</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="performance_bonus" <?= in_array('performance_bonus', $selectedBenefits) ? 'checked' : '' ?>> Performance Bonus</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="profit_sharing" <?= in_array('profit_sharing', $selectedBenefits) ? 'checked' : '' ?>> Profit Sharing</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="stock_options" <?= in_array('stock_options', $selectedBenefits) ? 'checked' : '' ?>> Stock Options / Equity</label>
              </div>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Allowances</p>
              <div class="pref-pill-group" style="margin-bottom:14px">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="transport" <?= in_array('transport', $selectedBenefits) ? 'checked' : '' ?>> Transport Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="housing" <?= in_array('housing', $selectedBenefits) ? 'checked' : '' ?>> Housing Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="meal" <?= in_array('meal', $selectedBenefits) ? 'checked' : '' ?>> Meal Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="airtime" <?= in_array('airtime', $selectedBenefits) ? 'checked' : '' ?>> Airtime / Data Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="company_car" <?= in_array('company_car', $selectedBenefits) ? 'checked' : '' ?>> Company Car / Vehicle</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="relocation" <?= in_array('relocation', $selectedBenefits) ? 'checked' : '' ?>> Relocation Support</label>
              </div>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Leave &amp; Time</p>
              <div class="pref-pill-group" style="margin-bottom:14px">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="annual_leave_15" <?= in_array('annual_leave_15', $selectedBenefits) ? 'checked' : '' ?>> Annual Leave 15+ days</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="annual_leave_21" <?= in_array('annual_leave_21', $selectedBenefits) ? 'checked' : '' ?>> Annual Leave 21+ days</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="flex_hours" <?= in_array('flex_hours', $selectedBenefits) ? 'checked' : '' ?>> Flexible Working Hours</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="parental_leave" <?= in_array('parental_leave', $selectedBenefits) ? 'checked' : '' ?>> Paid Parental Leave</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="sabbatical" <?= in_array('sabbatical', $selectedBenefits) ? 'checked' : '' ?>> Sabbatical Leave</label>
              </div>

              <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Growth &amp; Wellbeing</p>
              <div class="pref-pill-group">
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="training_budget" <?= in_array('training_budget', $selectedBenefits) ? 'checked' : '' ?>> Training &amp; Development Budget</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="mentorship" <?= in_array('mentorship', $selectedBenefits) ? 'checked' : '' ?>> Mentorship Programme</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="gym" <?= in_array('gym', $selectedBenefits) ? 'checked' : '' ?>> Gym / Wellness Allowance</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="team_retreats" <?= in_array('team_retreats', $selectedBenefits) ? 'checked' : '' ?>> Team Retreats / Offsites</label>
                <label class="pref-pill"><input type="checkbox" name="benefits[]" value="paid_certifications" <?= in_array('paid_certifications', $selectedBenefits) ? 'checked' : '' ?>> Paid Certifications</label>
              </div>
            </div>

            <div class="form-field full">
              <label for="hiring-process">Our hiring process <span class="opt">(optional — shown on your public profile)</span></label>
              <textarea id="hiring-process" name="hiring_process" rows="3"
                placeholder="e.g. Stage 1: CV review (5 days). Stage 2: 30-min video interview. Stage 3: Technical assessment."
                oninput="updateCount('hiring-process','hiring-count',500)"><?= esc($employer->hiring_process ?? '') ?></textarea>
              <div class="char-count"><span id="hiring-count"><?= strlen($employer->hiring_process ?? '') ?></span> / 500</div>
              <span style="font-size:.76rem;color:var(--muted);margin-top:4px;display:block">Transparency here improves your application completion rate.</span>
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('about', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Save description
            </button>
            <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Changes save automatically</span>
          </div>
        </div>
      </details>

      <!-- ══ 4. VERIFICATION — VERIFIED EMPLOYER BADGE ══ -->
      <details class="cv-card" aria-labelledby="h-verify">
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-verify">
            <svg aria-hidden="true"><use href="#i-shield"/></svg> Verification
            <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(unlocks Verified Employer badge)</span>
          </h2>
          <span class="cv-card-done incomplete">Incomplete</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">

          <!-- Verified Employer Badge Preview -->
          <div class="verified-banner">
            <svg aria-hidden="true"><use href="#i-shield"/></svg>
            <div class="verified-banner-body">
              <strong>Verified Employer Badge</strong>
              <p>Once verified, a ✓ Verified badge appears on all your job listings. Candidates trust verified employers and are 3× more likely to apply.</p>
            </div>
            <span class="verified-tag">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Verified
            </span>
          </div>

          <div class="form-grid">
            <div class="form-field">
              <label for="rc-number">RC Number / CAC Registration number</label>
              <input type="text" id="rc-number" name="rc_number" autocomplete="off" placeholder="e.g. RC123456" value="<?= esc($employer->rc_number ?? '') ?>">
              <span style="font-size:.76rem;color:var(--muted);margin-top:4px;display:block">Your Corporate Affairs Commission (CAC) registration number. We cross-reference this with the CAC public register.</span>
            </div>

            <!-- CAC Document Upload lives on its own dedicated flow (handles validation, storage, admin review) -->
            <div class="form-field full">
              <label>CAC Certificate / Incorporation document</label>
              <a href="<?= base_url('employer/profile/upload-document') ?>" class="emp-btn emp-btn-outline emp-btn-block">
                <svg aria-hidden="true"><use href="#i-download"/></svg> Upload / manage CAC document
              </a>
              <span style="font-size:.76rem;color:var(--muted);margin-top:6px;display:block">
                🔒 Uploaded documents are reviewed only by the JobberRecruit verification team — never shared with candidates.
              </span>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('verify', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Save RC number
            </button>
            <span class="autosave-note"><svg aria-hidden="true"><use href="#i-clock"/></svg> Verification takes up to 24 hours</span>
          </div>
        </div>
      </details>

      <!-- ══ 5. SOCIAL PROFILES ══ -->
      <details class="cv-card" aria-labelledby="h-social">
        <summary class="cv-card-header">
          <h2 class="cv-card-title" id="h-social">
            <svg aria-hidden="true"><use href="#i-globe"/></svg> Social Profiles
            <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(optional)</span>
          </h2>
          <span class="cv-card-done optional">Optional</span>
          <svg class="cv-chev" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="cv-card-body">
          <div class="cv-card-hint">Candidates research your company on social media before applying. Adding your social links builds credibility.</div>
          <div class="form-grid cols-1">
            <div class="form-field">
              <label for="social-linkedin">
                LinkedIn company page
              </label>
              <input type="url" id="social-linkedin" name="linkedin" placeholder="https://linkedin.com/company/yourcompany" value="<?= esc($employer->linkedin ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="social-twitter">
                Twitter / X
              </label>
              <input type="url" id="social-twitter" name="twitter" placeholder="https://x.com/yourcompany" value="<?= esc($employer->twitter ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="social-facebook">
                Facebook page
              </label>
              <input type="url" id="social-facebook" name="facebook" placeholder="https://facebook.com/yourcompany" value="<?= esc($employer->facebook ?? '') ?>">
            </div>
            <div class="form-field">
              <label for="social-instagram">
                Instagram
              </label>
              <input type="url" id="social-instagram" name="instagram" placeholder="https://instagram.com/yourcompany" value="<?= esc($employer->instagram ?? '') ?>">
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="emp-btn emp-btn-primary" onclick="saveSection('social', event)">
              <svg aria-hidden="true"><use href="#i-check"/></svg> Save social profiles
            </button>
          </div>
        </div>
      </details>

      <!-- BOTTOM ACTIONS -->
      <div class="bottom-actions">
        <a href="<?= base_url('employer/profile') ?>" class="emp-btn emp-btn-outline emp-btn-lg">
          <svg aria-hidden="true"><use href="#i-eye"/></svg> Preview public profile
        </a>
        <button type="button" class="emp-btn emp-btn-primary emp-btn-lg" onclick="saveAllSections(event)">
          <svg aria-hidden="true"><use href="#i-check"/></svg> Save all changes
        </button>
      </div>

    </div>
  </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/profile') ?>" class="emp-btn emp-btn-outline">
  <svg aria-hidden="true"><use href="#i-eye"/></svg> Preview
</a>
<button type="button" class="emp-btn emp-btn-primary" onclick="saveAllSections(event)">
  <svg aria-hidden="true"><use href="#i-check"/></svg> Save All
</button>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/* ── Logo preview ── */
function previewLogo(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var preview = document.getElementById('logo-preview');
    preview.innerHTML = '<img src="' + e.target.result + '" alt="Company logo preview"><div class="logo-overlay"><svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>';
  };
  reader.readAsDataURL(input.files[0]);
}

/* ── Character counter ── */
function updateCount(fieldId, countId, max) {
  var el = document.getElementById(fieldId);
  var counter = document.getElementById(countId);
  if (el && counter) counter.textContent = el.value.length;
}

/* ── Section ordering, weights and tips ── */
var SECTION_ORDER = ['identity','contact','about','verify','social'];
var sectionWeights = { identity:25, contact:20, about:20, verify:20, social:15 };
var SECTION_TIPS = {
  identity: 'Add your logo & company name for +25% — shown on every listing',
  contact:  'Add contact details for +20% — candidates verify you are legitimate',
  about:    'Add a company description for +20% — candidates read this before applying',
  verify:   'Add RC number for +20% — unlocks the Verified Employer badge',
  social:   'Add social profiles for +15% — builds trust with senior candidates'
};

var completedSections = {
    identity: <?= (!empty($employer->company_name) && !empty($employerIndustryIds) && !empty($employer->company_size)) ? 'true' : 'false' ?>,
    contact: <?= (!empty($employer->contact_name) && !empty($employer->contact_phone) && !empty($employer->contact_email) && !empty($employer->company_address) && !empty($employer->state_id)) ? 'true' : 'false' ?>,
    about: <?= (!empty($employer->description)) ? 'true' : 'false' ?>,
    verify: <?= (!empty($employer->rc_number) || !empty($employer->is_verified)) ? 'true' : 'false' ?>,
    social: <?= (!empty($employer->linkedin) || !empty($employer->twitter) || !empty($employer->facebook) || !empty($employer->instagram)) ? 'true' : 'false' ?>
};

/* ── Inject gain-tip badges on load ── */
function injectGainTips() {
  SECTION_ORDER.forEach(function(key) {
    var card = document.querySelector('[aria-labelledby="h-' + key + '"]');
    if (!card) return;
    var summary = card.querySelector('.cv-card-header');
    var chev = summary.querySelector('.cv-chev');
    var gain = sectionWeights[key] || 0;
    var tip = document.createElement('span');
    tip.className = 'cv-gain-tip';
    tip.id = 'gain-tip-' + key;
    tip.title = SECTION_TIPS[key];
    tip.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor" width="11" height="11" aria-hidden="true"><path d="M13 2 4.1 13H11l-2 9 10.9-11H13l2-9z"/></svg> +' + gain + '%';
    summary.insertBefore(tip, chev);
  });
}

/* ── Update sticky bar tip ── */
function updateBarTip() {
  var el = document.getElementById('progress-tip-text');
  if (!el) return;
  for (var i = 0; i < SECTION_ORDER.length; i++) {
    var k = SECTION_ORDER[i];
    if (!completedSections[k]) { el.textContent = SECTION_TIPS[k]; return; }
  }
  el.textContent = 'Profile complete — ready to attract top candidates!';
}

/* ── Auto-advance to next incomplete section ── */
function advanceToNext(currentKey) {
  var idx = SECTION_ORDER.indexOf(currentKey);
  for (var i = idx + 1; i < SECTION_ORDER.length; i++) {
    var nextKey = SECTION_ORDER[i];
    if (completedSections[nextKey]) continue;
    var nextCard = document.querySelector('[aria-labelledby="h-' + nextKey + '"]');
    if (!nextCard) continue;
    nextCard.setAttribute('open', '');
    nextCard.classList.add('next-up');
    setTimeout(function(c) { return function() { c.classList.remove('next-up'); }; }(nextCard), 900);
    setTimeout(function(c) {
      return function() {
        var top = c.getBoundingClientRect().top + window.scrollY - 140;
        window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
      };
    }(nextCard), 120);
    return;
  }
  var bottom = document.querySelector('.bottom-actions');
  if (bottom) setTimeout(function() { bottom.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 120);
}

/* ── Progress pulse ── */
function pulseBar() {
  var fill = document.getElementById('progress-fill');
  if (!fill) return;
  fill.classList.remove('pulse');
  void fill.offsetWidth;
  fill.classList.add('pulse');
  setTimeout(function() { fill.classList.remove('pulse'); }, 1100);
}

/* ── Main save handler ── */
  function saveSection(key, event, btn) {
    var e = event || window.event;
    btn = btn || (e ? (e.currentTarget || e.target.closest('button')) : null);
  if (!btn) return;
  var orig = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';

  var form = document.getElementById('editEmployerForm');
  var formData = new FormData(form);
  formData.append('section', key);

  $.ajax({
      url: form.getAttribute('action'),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
          'X-Requested-With': 'XMLHttpRequest'
      },
      success: function(response) {
          btn.disabled = false;
          // If the server returned status:'error' inside a 200 response, treat it as failure
          if (response && response.status === 'error') {
              btn.innerHTML = orig;
              var errMsg = response.message || 'Validation failed. Please check all required fields.';
              if (response.errors) {
                  var errList = Object.values(response.errors).join('<br>');
                  errMsg = errList;
              }
              if (typeof toastr !== 'undefined') {
                  toastr.error(errMsg);
              } else {
                  alert(errMsg);
              }
              return;
          }
          completedSections[key] = true;
          var card = btn.closest('.cv-card');
          if (card) card.classList.add('is-complete');
          if (card) {
              var badge = card.querySelector('.cv-card-done');
              if (badge) {
                  badge.className = 'cv-card-done complete';
                  badge.innerHTML = '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Done';
              }
          }
          var gainTip = document.getElementById('gain-tip-' + key);
          if (gainTip) gainTip.style.display = 'none';

          updateProgress(); pulseBar(); updateBarTip(); advanceToNext(key);

          btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Saved';
          btn.style.background = 'var(--brand-dark)'; btn.style.borderColor = 'var(--brand-dark)';
          setTimeout(function() { btn.innerHTML = orig; btn.style.background = ''; btn.style.borderColor = ''; }, 2200);
          
          if (typeof toastr !== 'undefined') {
              toastr.success(response.message || 'Section saved successfully.');
          }
      },
      error: function(xhr) {
          btn.disabled = false;
          btn.innerHTML = orig;
          var message = 'An error occurred while saving.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
              message = xhr.responseJSON.message;
          }
          if (typeof toastr !== 'undefined') {
              toastr.error(message);
          } else {
              alert(message);
          }
      }
  });
}

/* ── Save all ── */
  function saveAllSections(event, btn) {
    var e = event || window.event;
    btn = btn || (e ? (e.currentTarget || e.target.closest('button')) : null);
  if (!btn) return;
  var orig = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving all...';

  var form = document.getElementById('editEmployerForm');
  var formData = new FormData(form);

  $.ajax({
      url: form.getAttribute('action'),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
          'X-Requested-With': 'XMLHttpRequest'
      },
      success: function(response) {
          btn.disabled = false;
          btn.innerHTML = orig;
          if (response && response.status === 'error') {
              var errMsg = response.message || 'Validation failed. Please check all required fields.';
              if (response.errors) {
                  var errList = Object.values(response.errors).join('<br>');
                  errMsg = errList;
              }
              if (typeof toastr !== 'undefined') {
                  toastr.error(errMsg);
              } else {
                  alert(errMsg);
              }
              return;
          }
          SECTION_ORDER.forEach(function(k) {
              completedSections[k] = true;
              var card = document.querySelector('[aria-labelledby="h-' + k + '"]');
              if (card) {
                  card.classList.add('is-complete');
                  var badge = card.querySelector('.cv-card-done');
                  if (badge) { badge.className = 'cv-card-done complete'; badge.innerHTML = '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Done'; }
                  var tip = document.getElementById('gain-tip-' + k);
                  if (tip) tip.style.display = 'none';
              }
          });
          updateProgress(); pulseBar(); updateBarTip();
          if (typeof toastr !== 'undefined') {
              toastr.success(response.message || 'All sections saved.');
              setTimeout(function() {
                  window.location.href = '<?= base_url('employer/profile') ?>';
              }, 1000);
          } else {
              window.location.href = '<?= base_url('employer/profile') ?>';
          }
      },
      error: function(xhr) {
          btn.disabled = false;
          btn.innerHTML = orig;
          var message = 'An error occurred while saving.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
              message = xhr.responseJSON.message;
          }
          if (typeof toastr !== 'undefined') {
              toastr.error(message);
          } else {
              alert(message);
          }
      }
  });
}

/* ── Update progress bar ── */
function updateProgress() {
  var total = 0;
  Object.keys(completedSections).forEach(function(k) { 
      if (completedSections[k] === true) {
          total += sectionWeights[k] || 0; 
      }
  });
  total = Math.min(total, 100);
  var fill = document.getElementById('progress-fill');
  var pct = document.getElementById('progress-pct');
  if (fill) fill.style.width = total + '%';
  if (pct) pct.textContent = total + '% Completed';
}

document.addEventListener('DOMContentLoaded', function() {
  injectGainTips();
  
  // Set initial complete states in UI
  SECTION_ORDER.forEach(function(k) {
      if (completedSections[k]) {
          const card = document.querySelector('[aria-labelledby="h-' + k + '"]');
          if (card) {
              card.classList.add('is-complete');
              const badge = card.querySelector('.cv-card-done');
              if (badge) {
                  badge.className = 'cv-card-done complete';
                  badge.innerHTML = '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Done';
              }
              const tip = document.getElementById('gain-tip-' + k);
              if (tip) tip.style.display = 'none';
          }
      }
  });

  updateProgress();
  updateBarTip();

  // Industry multi-select dropdown
  (function() {
    const dropdown = document.getElementById('industry-dropdown');
    if (!dropdown) return;
    const trigger = document.getElementById('industry-trigger');
    const label = document.getElementById('industry-trigger-label');
    const checkboxes = dropdown.querySelectorAll('input[name="industry_ids[]"]');

    function refreshLabel() {
      const checked = [...checkboxes].filter(function(c) { return c.checked; });
      if (checked.length === 0) {
        label.textContent = 'Select industries…';
        label.classList.add('placeholder');
      } else if (checked.length <= 2) {
        label.textContent = checked.map(function(c) {
          return c.closest('.ms-option').textContent.trim();
        }).join(', ');
        label.classList.remove('placeholder');
      } else {
        label.textContent = checked.length + ' industries selected';
        label.classList.remove('placeholder');
      }
    }

    trigger.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = dropdown.classList.toggle('open');
      trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    checkboxes.forEach(function(cb) {
      cb.addEventListener('change', refreshLabel);
    });

    document.addEventListener('click', function(e) {
      if (!dropdown.contains(e.target)) {
        dropdown.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && dropdown.classList.contains('open')) {
        dropdown.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
        trigger.focus();
      }
    });

    refreshLabel();
  })();
});
</script>
<?= $this->endSection() ?>