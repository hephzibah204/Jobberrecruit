<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Message to display Current Plan and Job Limit left -->

    <!-- Plan & Credits Summary -->
    <div class="row mb-4">
        <div class="col-md-12">
            <?php if ($hasUnlimitedAccess): ?>
                <div class="alert alert-success d-flex justify-content-between align-items-center">
                    <div>
                        <i class="ti ti-infinity fs-4 me-2"></i>
                        <strong>Unlimited Access Plan</strong>
                        <p class="mb-0 small">You have unlimited job postings. No credits will be deducted.</p>
                    </div>
                    <i class="ti ti-crown fs-2 text-warning"></i>
                </div>
            <?php else: ?>
                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Available Job Credits:</strong>
                        <span class="fw-bold ms-2"><?= number_format($creditBalance, 0) ?></span>
                        <p class="mb-0 small">1 credit = 1 job posting</p>
                    </div>
                    <a href="<?= base_url('employer/pricing') ?>" class="btn btn-sm btn-primary">
                        <i class="ti ti-plus-circle me-1"></i> Buy Credits
                    </a>
                </div>

                <?php if ($creditBalance <= 0): ?>
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>No Job Credits Available!</strong>
                        You need credits to post jobs. <a href="<?= base_url('employer/pricing') ?>" class="alert-link">Purchase a bundle</a>
                        or <a href="<?= base_url('employer/pricing') ?>" class="alert-link">subscribe to a plan</a>.
                    </div>
                <?php elseif ($creditBalance <= 2): ?>
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-circle me-2"></i>
                        <strong>Low Credits Warning!</strong>
                        You only have <?= $creditBalance ?> credit(s) left.
                        <a href="<?= base_url('employer/pricing') ?>" class="alert-link">Purchase more credits</a> to continue posting.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    

    <div class="post-wrap">
<form method="POST" class="add-product-form" id="post-job-form" novalidate>
    <?= csrf_field() ?>
<!-- All sections open by default on a fresh post-a-job form -->
<!-- ══ 1. JOB OVERVIEW ══ -->
<details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-overview">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-overview"><svg aria-hidden="true" width="16" height="16"><use href="#i-briefcase"/></svg> Job Overview</h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <div class="form-grid">
      <div class="form-field">
        <label for="job-title">Job title <span class="required-star">*</span></label>
        <input type="text" id="job-title" name="job_title" autocomplete="off" placeholder="e.g. Senior Software Engineer" required maxlength="100" oninput="">
      </div>
      <div class="form-field">
        <label for="job-type">Job type <span class="required-star">*</span></label>
        <select id="job-type" name="job_type" required >
          <option value="">Select job type</option>
          <option>Full-time</option>
          <option>Part-time</option>
          <option>Contract</option>
          <option>Internship</option>
          <option>Temporary</option>
          <option>Volunteer</option>
          <option>Freelance</option>
        </select>
      </div>
      <div class="form-field">
        <label for="job-location">Location <span class="required-star">*</span></label>
        <select id="job-location" name="location" required >
          <option value="">Select state / Remote</option>
          <option>Remote (Nigeria-wide)</option>
          <option>Lagos</option><option>Abuja (FCT)</option><option>Port Harcourt</option>
          <option>Kano</option><option>Ibadan</option><option>Enugu</option><option>Kaduna</option>
          <option>Abeokuta</option><option>Benin City</option><option>Aba</option><option>Jos</option>
          <option>Warri</option><option>Asaba</option><option>Uyo</option><option>Calabar</option>
          <option>Ilorin</option><option>Sokoto</option><option>Maiduguri</option><option>Zaria</option>
          <option>Owerri</option><option>Akure</option><option>Abeokuta</option><option>Ado Ekiti</option>
          <option>Other (specify in description)</option>
        </select>
      </div>
      <div class="form-field">
        <label for="work-style">Work style <span class="required-star">*</span></label>
        <select id="work-style" name="work_style" required onchange="toggleHybridDays(this.value)">
          <option value="">Select work style</option>
          <option>On-site</option>
          <option>Remote</option>
          <option>Hybrid</option>
          <option>Flexible</option>
        </select>
      </div>

      <!-- Shown only when Hybrid is selected -->
      <div class="form-field" id="hybrid-days-wrap" style="display:none">
        <label for="hybrid-days">Days on-site per week <span class="opt">(shown on listing as "Hybrid · X days on-site")</span></label>
        <select id="hybrid-days" name="hybrid_days_onsite" >
          <option value="">Select days on-site</option>
          <option value="1">1 day on-site</option>
          <option value="2">2 days on-site</option>
          <option value="3">3 days on-site</option>
          <option value="4">4 days on-site</option>
          <option value="flex">Flexible — varies by week</option>
          <option value="agreed">As agreed with manager</option>
        </select>
      </div>
      <div class="form-field">
        <label for="industry">Industry <span class="required-star">*</span></label>
        <select id="industry" name="industry" required onchange="updateJobCategories(this.value)">
          <option value="">Select industry</option>
          <option>Accounting / Finance</option><option>Administration / Secretarial</option>
          <option>Agriculture / Agro-Allied</option><option>Aviation / Airline</option>
          <option>Banking / Financial Services</option><option>Building / Construction</option>
          <option>Consulting</option><option>Education / Training</option>
          <option>Energy / Power / Utilities</option><option>Engineering</option>
          <option>Entertainment / Media</option><option>FMCG / Manufacturing</option>
          <option>Government / Public Sector</option><option>Healthcare / Pharmaceutical</option>
          <option>Hotels &amp; Restaurants</option><option>ICT / Telecommunications</option>
          <option>Insurance</option><option>Logistics / Transportation</option>
          <option>Marketing / Advertising</option><option>NGO / Non-profit</option>
          <option>Oil &amp; Gas / Energy</option><option>Real Estate / Property</option>
          <option>Retail / Wholesale</option><option>Security</option><option>Other</option>
        </select>
      </div>
      <div class="form-field">
        <label for="job-category">Job category <span class="required-star">*</span></label>
        <select id="job-category" name="job_category" required>
          <option value="">Select category</option>
          <option>Accounting &amp; Audit</option><option>Administration</option>
          <option>Agriculture</option><option>Architecture</option>
          <option>Business Development</option><option>Civil Engineering</option>
          <option>Customer Service</option><option>Data / Analytics</option>
          <option>Design / Creative</option><option>Digital Marketing</option>
          <option>Education &amp; Training</option><option>Electrical Engineering</option>
          <option>Finance &amp; Investment</option><option>Graduate Trainee</option>
          <option>Health &amp; Safety</option><option>Human Resources</option>
          <option>ICT / Software</option><option>Law / Legal</option>
          <option>Logistics &amp; Supply Chain</option><option>Management / Leadership</option>
          <option>Manufacturing</option><option>Marketing &amp; Communications</option>
          <option>Medical / Healthcare</option><option>Procurement</option>
          <option>Project Management</option><option>Quality Assurance</option>
          <option>Research &amp; Development</option><option>Sales</option>
          <option>Science &amp; Laboratory</option><option>Security</option>
          <option>Social Media</option><option>Technical Support</option>
          <option>Other</option>
        </select>
      </div>

      <div class="form-field">
        <label for="num-vacancies">Number of vacancies <span class="required-star">*</span></label>
        <select id="num-vacancies" name="num_vacancies" required>
          <option value="1" selected>1 opening</option>
          <option value="2">2 openings</option>
          <option value="3">3 openings</option>
          <option value="4">4 openings</option>
          <option value="5">5 openings</option>
          <option value="6-10">6 – 10 openings</option>
          <option value="11-20">11 – 20 openings</option>
          <option value="20+">More than 20 openings</option>
        </select>
      </div>
    </div>
  </div>
</details>

<!-- ══ 2. COMPENSATION ══ -->
<details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-comp">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-comp"><svg aria-hidden="true" width="16" height="16"><use href="#i-naira"/></svg> Compensation</h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <div class="cv-card-hint">Jobs with a displayed salary receive up to 3× more applications. You can show or hide the amount on the listing — the information helps our matching engine regardless.</div>
    <div class="form-grid">
      <div class="form-field full">
        <label>Salary type <span class="required-star">*</span></label>
        <div class="method-group" style="gap:8px">
          <label class="method-pill"><input type="radio" name="salary_type" value="range" onchange="showSalaryFields(this.value)" checked> Salary range</label>
          <label class="method-pill"><input type="radio" name="salary_type" value="fixed" onchange="showSalaryFields(this.value)"> Fixed amount</label>
          <label class="method-pill"><input type="radio" name="salary_type" value="negotiable" onchange="showSalaryFields(this.value)"> Negotiable</label>
          <label class="method-pill"><input type="radio" name="salary_type" value="undisclosed" onchange="showSalaryFields(this.value)"> Undisclosed</label>
        </div>
      </div>

      <!-- Salary range fields (shown by default) -->
      <div class="form-field salary-conditional visible form-grid cols-1" id="salary-range-wrap" style="grid-column:1/-1;grid-template-columns:1fr 1fr;gap:16px;padding:0;border:none;background:none">
        <div class="form-field">
          <label for="salary-min">Minimum salary (₦/month)</label>
          <input type="number" id="salary-min" name="salary_min" autocomplete="off" placeholder="e.g. 200000" min="0" step="1000" oninput="" value="<?= old('salary') ?>">
        </div>
        <div class="form-field">
          <label for="salary-max">Maximum salary (₦/month)</label>
          <input type="number" id="salary-max" name="salary_max" autocomplete="off" placeholder="e.g. 500000" min="0" step="1000" oninput="" value="<?= old('salary_max') ?>">
        </div>
      </div>

      <!-- Fixed amount field (hidden) -->
      <div class="form-field salary-conditional" id="salary-fixed-wrap">
        <label for="salary-fixed">Salary amount (₦/month)</label>
        <input type="number" id="salary-fixed" name="salary_fixed" placeholder="e.g. 350000" min="0" step="1000" oninput="" value="<?= old('salary') ?>">
      </div>

      <div class="form-field" id="salary-period-wrap">
        <label for="salary-period">Pay period</label>
        <select id="salary-period" name="salary_period">
          <option value="monthly" selected>Per month</option>
          <option value="annually" <?= old('salary_period') == 'annually' ? 'selected' : '' ?>>Per annum</option>
          <option value="daily" <?= old('salary_period') == 'daily' ? 'selected' : '' ?>>Per day</option>
          <option value="hourly" <?= old('salary_period') == 'hourly' ? 'selected' : '' ?>>Per hour</option>
        </select>
      </div>

      <div class="form-field" id="salary-display-wrap">
        <label style="display:block;margin-bottom:8px">Salary visibility</label>
        <label class="toggle-wrap" style="cursor:pointer">
          <span class="toggle">
            <input type="checkbox" name="show_salary" id="show-salary" checked >
            <span class="toggle-slider"></span>
          </span>
          <span>
            <span class="toggle-label">Show salary on listing</span>
            <span class="toggle-sub">Candidates see the range — increases quality applications</span>
          </span>
        </label>
      </div>
    </div>
  </div>
</details>

<!-- ══ 3. JOB DESCRIPTION ══ -->
<details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-desc">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-desc"><svg aria-hidden="true" width="16" height="16"><use href="#i-file-text"/></svg> Job Description</h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <div class="ai-action" style="margin-bottom:12px">
      <div>
        <label for="job-desc" style="font-size:.82rem;font-weight:600;color:var(--text)">Job description <span class="required-star">*</span></label>
        <p style="font-size:.78rem;color:var(--muted);margin-top:2px">Be specific — include responsibilities, what success looks like, and who you are looking for. Candidates compare listings side by side.</p>
      </div>
      <button type="button" class="ai-btn" title="AI writes a draft based on your job title, industry and requirements">
        <svg aria-hidden="true" width="16" height="16"><use href="#i-zap"/></svg> AI generate
      </button>
    </div>
    <div class="form-field">
      <textarea id="job-desc" name="description" rows="12" required
        placeholder="About the role:&#10;We are looking for a [Job Title] to join our [team/department]...&#10;&#10;Responsibilities:&#10;• ...&#10;• ...&#10;&#10;Requirements:&#10;• ...&#10;&#10;What we offer:&#10;• ..."><?= old('description') ?></textarea>
      <div class="char-count"><span id="desc-count">0</span> / 5,000 — more detail = better candidate quality</div>
    </div>
  </div>
</details>

<!-- ══ 4. REQUIREMENTS ══ -->
<details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-req">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-req"><svg aria-hidden="true" width="16" height="16"><use href="#i-cap"/></svg> Requirements</h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <div class="form-grid">
      <div class="form-field">
        <label for="min-edu">Minimum education <span class="required-star">*</span></label>
        <select id="min-edu" name="min_education" required>
          <option value="">Select minimum</option>
          <option value="No formal education required" <?= old('education_level') == 'No formal education required' ? 'selected' : '' ?>>No formal education required</option>
          <option>WAEC / SSCE</option>
          <option>OND</option>
          <option>HND</option>
          <option value="Bachelor's Degree" <?= old('education_level') == "Bachelor's Degree" ? 'selected' : '' ?>>B.Sc / B.A / B.Eng</option>
          <option>MBBS / B.Pharm</option>
          <option>PGD</option>
          <option value="Master's Degree" <?= old('education_level') == "Master's Degree" ? 'selected' : '' ?>>M.Sc / MBA / M.A</option>
          <option value="PhD" <?= old('education_level') == 'PhD' ? 'selected' : '' ?>>Ph.D</option>
          <option>Professional Certification</option>
        </select>
      </div>
      <div class="form-field">
        <label for="years-exp">Years of experience <span class="required-star">*</span></label>
        <select id="years-exp" name="experience_level" required>
          <option value="">Select range</option>
          <option value="Entry Level (0-2 years)" <?= old('experience_level') == 'Entry Level (0-2 years)' ? 'selected' : '' ?>>Entry Level (0-2 years)</option>
          <option value="Mid Level (2-5 years)" <?= old('experience_level') == 'Mid Level (2-5 years)' ? 'selected' : '' ?>>Mid Level (2-5 years)</option>
          <option value="Senior Level (5+ years)" <?= old('experience_level') == 'Senior Level (5+ years)' ? 'selected' : '' ?>>Senior Level (5+ years)</option>
          <option value="Executive Level" <?= old('experience_level') == 'Executive Level' ? 'selected' : '' ?>>Executive Level</option>
</select>
      </div>

      <div class="form-field full">
        <label>Required skills <span class="opt">(optional — improves matching)</span></label>
        <div class="tag-input-wrap" id="skill-tag-wrap" onclick="document.getElementById('skill-input').focus()">
          <!-- skill tags rendered here by JS -->
          <input type="text" id="skill-input" class="tag-input" placeholder="Type a skill and press Enter or comma…"
                 onkeydown="handleSkillInput(event)" oninput="this.style.width=(this.value.length*9+140)+'px'">
        </div>
        <input type="hidden" id="skills-hidden" name="skills" value="<?= old('skills') ?>">
        <span style="font-size:.76rem;color:var(--muted);margin-top:5px;display:block">e.g. JavaScript, Project Management, Communication, Excel</span>
      </div>

      <div class="form-field">
        <label for="nysc-required">NYSC status requirement</label>
        <select id="nysc-required" name="nysc_requirement">
          <option value="">No requirement</option>
          <option>NYSC completed required</option>
          <option>NYSC exemption accepted</option>
          <option>NYSC not required</option>
        </select>
      </div>
    </div>
  </div>
</details>

<!-- ══ 5. JOB CONDITIONS ══ -->
<details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-conditions">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-conditions">
      <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
      Job Conditions
    </h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <div class="cv-card-hint">These details appear prominently on the listing and help candidates self-qualify before applying — reducing irrelevant applications significantly.</div>
    <div class="form-grid">

      <div class="form-field">
        <label for="job-schedule">Work schedule</label>
        <select id="job-schedule" name="job_schedule">
          <option value="">Select schedule</option>
          <option>Monday – Friday</option>
          <option>Monday – Saturday</option>
          <option>Tuesday – Saturday</option>
          <option>Wednesday – Sunday</option>
          <option>Monday – Sunday (with days off)</option>
          <option>Rotating shifts</option>
          <option>Weekends only</option>
          <option>Flexible / Remote hours</option>
          <option>Other (specify in description)</option>
        </select>
      </div>

      <div class="form-field">
        <label for="working-hours">Working hours</label>
        <select id="working-hours" name="working_hours">
          <option value="">Select hours</option>
          <option>7:00am – 4:00pm</option>
          <option>8:00am – 5:00pm</option>
          <option>9:00am – 5:00pm</option>
          <option>9:00am – 6:00pm</option>
          <option>10:00am – 6:00pm</option>
          <option>10:00am – 7:00pm</option>
          <option>12:00pm – 8:00pm (split)</option>
          <option>Night shift</option>
          <option>As negotiated</option>
          <option>Other (specify in description)</option>
        </select>
      </div>

      <div class="form-field">
        <label for="accommodation">Accommodation</label>
        <select id="accommodation" name="accommodation">
          <option value="">Not applicable</option>
          <option>Provided (fully covered by employer)</option>
          <option>Provided (cost shared with employee)</option>
          <option>Housing allowance provided instead</option>
          <option>Not provided</option>
        </select>
      </div>

      <div class="form-field">
        <label for="job-gender">Gender requirement <span class="opt">(optional)</span></label>
        <select id="job-gender" name="gender_requirement">
          <option value="">No requirement (recommended)</option>
          <option>Male only</option>
          <option>Female only</option>
          <option>Male preferred</option>
          <option>Female preferred</option>
        </select>
      </div>

      <div class="form-field">
        <label for="age-bracket">Age bracket <span class="opt">(optional)</span></label>
        <select id="age-bracket" name="age_bracket" onchange="toggleAgeCustom(this.value)">
          <option value="">No requirement</option>
          <option>18 – 25 years</option>
          <option>18 – 30 years</option>
          <option>21 – 35 years</option>
          <option>25 – 35 years</option>
          <option>25 – 40 years</option>
          <option>30 – 45 years</option>
          <option>35 – 50 years</option>
          <option value="custom">Specify range</option>
        </select>
      </div>

      <div class="form-field" id="age-custom-wrap" style="display:none">
        <label for="age-custom">Specify age range</label>
        <input type="text" id="age-custom" name="age_bracket_custom" placeholder="e.g. 22 – 45 years">
      </div>

      <div class="form-field">
        <label for="probation">Probation period</label>
        <select id="probation" name="probation_period">
          <option value="">No probation</option>
          <option>1 month</option>
          <option>2 months</option>
          <option value="3m" selected>3 months (standard)</option>
          <option>6 months</option>
          <option>As negotiated</option>
        </select>
      </div>

    </div>

    <div class="info-note" style="margin-top:16px">
      <svg aria-hidden="true" width="16" height="16"><use href="#i-eye"/></svg>
      <span>Gender and age requirements are displayed on the listing as candidate guidance. JobberRecruit recommends leaving these open unless the role has a genuine occupational requirement — broader criteria attract a stronger applicant pool.</span>
    </div>
  </div>
</details>

<!-- ══ 5. BENEFITS & PERKS ══ -->
<details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-benefits">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-benefits"><svg aria-hidden="true" width="16" height="16"><use href="#i-star"/></svg> Benefits &amp; Perks</h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <div class="cv-card-hint">Pre-populated from your <a href="/employer/profile">company profile</a>. Uncheck any that don't apply to this specific role, or add extras. Candidates filter jobs by benefits.</div>
    <div style="margin-bottom:12px">
      <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Health &amp; Insurance</p>
      <div class="pref-pill-group">
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="hmo" checked> HMO / Health Insurance</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="hmo_dependants"> HMO covers dependants</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="life_insurance" checked> Life Insurance</label>
      </div>
    </div>
    <div style="margin-bottom:12px">
      <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Financial</p>
      <div class="pref-pill-group">
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="pension" checked> Contributory Pension (CPS)</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="13th_month"> 13th Month Salary</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="performance_bonus"> Performance Bonus</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="stock_options"> Stock Options / Equity</label>
      </div>
    </div>
    <div style="margin-bottom:12px">
      <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Allowances</p>
      <div class="pref-pill-group">
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="transport" checked> Transport Allowance</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="meal"> Meal Allowance</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="housing"> Housing Allowance</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="airtime"> Airtime / Data</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="company_car"> Company Vehicle</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="relocation"> Relocation Support</label>
      </div>
    </div>
    <div>
      <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Growth &amp; Wellbeing</p>
      <div class="pref-pill-group">
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="training_budget"> Training Budget</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="flex_hours"> Flexible Hours</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="parental_leave"> Paid Parental Leave</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="annual_leave_15"> 15+ Days Annual Leave</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="gym"> Gym / Wellness</label>
        <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="team_retreats"> Team Retreats</label>
      </div>
    </div>
  </div>
</details>

<!-- ══ 6. APPLICATION SETTINGS ══ -->
<details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-app">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-app"><svg aria-hidden="true" width="16" height="16"><use href="#i-settings"/></svg> Application Settings</h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <div class="form-grid">
      <div class="form-field">
        <label for="app-deadline">Application deadline</label>
        <input type="date" id="app-deadline" name="application_deadline" value="<?= old('application_deadline') ?>">
      </div>
      <div class="form-field">
        <label for="start-date">Expected start date</label>
        <input type="date" id="start-date" name="start_date" value="<?= old('start_date') ?>">
      </div>

      <div class="form-field">
        <label for="app-limit">Stop accepting after <span class="opt">(optional)</span></label>
        <select id="app-limit" name="application_limit">
          <option value="">No limit</option>
          <option value="10">10 applications</option>
          <option value="20">20 applications</option>
          <option value="50">50 applications</option>
          <option value="100">100 applications</option>
          <option value="200">200 applications</option>
          <option value="500">500 applications</option>
        </select>
        <span style="font-size:.76rem;color:var(--muted);margin-top:4px;display:block">Listing auto-closes once this number is reached — prevents inbox overflow</span>
      </div>

      <div class="form-field full">
        <label style="margin-bottom:10px;display:block">What candidates must submit</label>
        <div style="display:flex;flex-wrap:wrap;gap:16px">
          <label class="toggle-wrap" style="cursor:pointer">
            <span class="toggle">
              <input type="checkbox" name="require_cv" id="require-cv" checked>
              <span class="toggle-slider"></span>
            </span>
            <span>
              <span class="toggle-label">CV / Resume required</span>
              <span class="toggle-sub">Candidates must upload their CV to apply</span>
            </span>
          </label>
          <label class="toggle-wrap" style="cursor:pointer">
            <span class="toggle">
              <input type="checkbox" name="require_cover_letter" id="require-cover-letter">
              <span class="toggle-slider"></span>
            </span>
            <span>
              <span class="toggle-label">Cover letter required</span>
              <span class="toggle-sub">Candidates must write a cover letter when applying</span>
            </span>
          </label>
        </div>
      </div>

      <div class="form-field full">
        <label>Application method <span class="required-star">*</span></label>
        <div class="method-group" id="app-method-group">
          <label class="method-pill"><input type="radio" name="application_method" value="form" <?= old('application_method', 'form') == 'form' ? 'checked' : '' ?> onchange="toggleMethodDetail(this.value)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></svg>
            JobberRecruit form</label>
          <label class="method-pill"><input type="radio" name="application_method" value="whatsapp" <?= old('application_method') == 'whatsapp' ? 'checked' : '' ?> onchange="toggleMethodDetail(this.value)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.5 3.5A11.9 11.9 0 0 0 12 0C5.5 0 .2 5.3.2 11.8c0 2.1.5 4.1 1.6 5.9L0 24l6.5-1.7c1.7 1 3.6 1.4 5.5 1.4 6.5 0 11.8-5.3 11.8-11.8 0-3.2-1.2-6.1-3.3-8.4Z"/></svg>
            WhatsApp</label>
          <label class="method-pill"><input type="radio" name="application_method" value="email" <?= old('application_method') == 'email' ? 'checked' : '' ?> onchange="toggleMethodDetail(this.value)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
            Email</label>
          <label class="method-pill"><input type="radio" name="application_method" value="external" <?= old('application_method') == 'external' ? 'checked' : '' ?> onchange="toggleMethodDetail(this.value)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            External page</label>
        </div>
        
        <div id="method-detail" style="margin-top:10px;display:none">
          <input type="url" id="method_whatsapp_input" name="whatsapp_link" class="form-control" style="display:none;" placeholder="https://wa.me/2348000000000" value="<?= old('whatsapp_link') ?>">
          <input type="email" id="method_email_input" name="application_email" class="form-control" style="display:none;" placeholder="jobs@company.com" value="<?= old('application_email') ?>">
          <input type="url" id="method_external_input" name="external_url" class="form-control" style="display:none;" placeholder="https://company.com/apply" value="<?= old('external_url') ?>">
        </div>

        <!-- Shown when a non-JobberRecruit method is selected -->
        <div class="info-note" id="ats-unavailable-note" style="display:none;margin-top:10px">
          <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span>Pre-screening questions are only available when candidates apply through the <strong>JobberRecruit form</strong>. They are hidden while another application method is selected.</span>
        </div>
      </div>

      <div class="form-field full">
        <label>Who can apply? <span class="required-star">*</span></label>
        <div class="method-group" style="gap:8px">
          <label class="method-pill"><input type="radio" name="application_access" value="general" <?= old('application_access', 'general') == 'general' ? 'checked' : '' ?>> Anyone (recommended)</label>
          <label class="method-pill"><input type="radio" name="application_access" value="authenticated" <?= old('application_access') == 'authenticated' ? 'checked' : '' ?>> Registered candidates only</label>
          <label class="method-pill"><input type="radio" name="application_access" value="guest" <?= old('application_access') == 'guest' ? 'checked' : '' ?>> Guest Applicants</label>
        </div>
        <span style="font-size:.76rem;color:var(--muted);margin-top:6px;display:block">"Registered only" improves application quality — candidates have a verified profile on JobberRecruit</span>
      </div>

      <!-- Notification preferences -->
      <div class="form-field full">
        <label style="margin-bottom:10px;display:block">Notification preferences</label>
        <div style="display:flex;flex-wrap:wrap;gap:16px">
          <label class="toggle-wrap" style="cursor:pointer">
            <span class="toggle"><input type="checkbox" name="notification_in_app" id="notify-inapp" value="1" <?= old('notification_in_app', 1) ? 'checked' : '' ?>><span class="toggle-slider"></span></span>
            <span>
              <span class="toggle-label">In-app notifications</span>
              <span class="toggle-sub">Alerts in your dashboard when candidates apply</span>
            </span>
          </label>
          <label class="toggle-wrap" style="cursor:pointer">
            <span class="toggle"><input type="checkbox" name="notification_email_toggle" id="notify-email" value="1" <?= old('notification_email_toggle') ? 'checked' : '' ?>><span class="toggle-slider"></span></span>
            <span>
              <span class="toggle-label">Email notifications</span>
              <span class="toggle-sub">Email alert for each new application</span>
            </span>
          </label>
        </div>
        <div class="info-note" style="margin-top:12px">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-eye"/></svg>
          <span>You'll always receive notifications in your dashboard. Email notifications are optional and can be changed at any time.</span>
        </div>
      </div>
    </div>
  </div>
</details>

<!-- ══ 7. LISTING BOOST ══ -->
<details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-boost">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-boost"><svg aria-hidden="true" width="16" height="16"><use href="#i-trending-up"/></svg> Listing Boost <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(optional)</span></h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <div style="display:flex;flex-direction:column;gap:12px">
      <!-- Urgently Hiring -->
      <div class="boost-row urgent">
        <div class="boost-icon red"><svg aria-hidden="true" width="16" height="16"><use href="#i-zap"/></svg></div>
        <div class="boost-body">
          <div class="boost-body-hd">
            <strong>Urgently Hiring</strong>
            <span class="boost-tag plan">Plan required</span>
          </div>
          <p class="boost-body-desc">Adds an urgent badge to your listing and pins it above standard results — significantly increases click-through rate</p>
        </div>
        <label class="toggle" title="Mark as urgently hiring">
          <input type="checkbox" name="urgent_hiring" id="urgent-hiring" data-has-plan="0" onchange="handleUrgentToggle(this)">
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- Featured Listing -->
      <div class="boost-row featured">
        <div class="boost-icon orange"><svg aria-hidden="true" width="16" height="16"><use href="#i-star"/></svg></div>
        <div class="boost-body">
          <div class="boost-body-hd">
            <strong>Featured Listing</strong>
            <span class="boost-tag plan">Plan required</span>
          </div>
          <p class="boost-body-desc">Pins your job to the top of search results and the homepage featured section for <strong>30 days</strong></p>
        </div>
        <label class="toggle" title="Feature this listing">
          <input type="checkbox" name="featured_listing" id="featured-listing" data-has-plan="0" onchange="handleFeaturedToggle(this)">
          <span class="toggle-slider"></span>
        </label>
      </div>
    </div>
  </div>
</details>

<!-- ══ 8. PRE-SCREENING QUESTIONS (ATS) ══ -->
<details class="job-card" id="ats-section" style="margin-bottom:20px" aria-labelledby="h-ats">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-ats"><svg aria-hidden="true" width="16" height="16"><use href="#i-help"/></svg> Pre-screening Questions <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(ATS · optional)</span></h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <div class="cv-card-hint">Candidates answer these questions when applying. Their responses appear on your ATS dashboard alongside their CV — helping you shortlist without reading every application in full.</div>

    <div id="question-list">
      <div class="question-item" id="q-1">
        <div class="question-item-header">
          <span class="question-num">Q1</span>
          <input type="text" name="q_text[]" placeholder="e.g. Do you have experience with Python?" style="flex:1;border:none;background:transparent;font-size:.88rem;font-family:'Inter',sans-serif;outline:none;color:var(--text)">
          <button type="button" class="question-remove" onclick="removeQuestion(this)" aria-label="Remove question"><svg aria-hidden="true" width="16" height="16"><use href="#i-trash"/></svg></button>
        </div>
        <div class="form-grid" style="grid-template-columns:1fr 1fr;gap:10px">
          <div class="form-field">
            <label style="font-size:.76rem">Answer type</label>
            <select name="q_type[]" onchange="toggleMCOptions(this)">
              <option value="yes_no">Yes / No</option>
              <option value="short_text">Short text</option>
              <option value="multiple_choice">Multiple choice</option>
            </select>
          </div>
          <div class="form-field">
            <label style="font-size:.76rem">Required?</label>
            <select name="q_required[]">
              <option value="1">Required</option>
              <option value="0">Optional</option>
            </select>
          </div>
        </div>

        <!-- Multiple choice options — shown when type = Multiple choice -->
        <div class="mc-options-wrap">
          <p class="mc-options-label">Answer options</p>
          <div class="mc-option-rows">
            <div class="mc-option-row">
              <input type="text" name="q_options[]" placeholder="Option 1" aria-label="Option 1">
              <button type="button" class="mc-remove-opt" onclick="removeMCOption(this)" aria-label="Remove option">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
              </button>
            </div>
            <div class="mc-option-row">
              <input type="text" name="q_options[]" placeholder="Option 2" aria-label="Option 2">
              <button type="button" class="mc-remove-opt" onclick="removeMCOption(this)" aria-label="Remove option">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
              </button>
            </div>
          </div>
          <button type="button" class="mc-add-opt" onclick="addMCOption(this)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Add option
          </button>
        </div>
      </div>
    </div>

    <button type="button" class="btn btn-outline btn-sm" onclick="addQuestion()" style="margin-top:6px">
      <svg aria-hidden="true" width="16" height="16"><use href="#i-plus"/></svg> Add screening question
    </button>
  </div>
</details>

<!-- ══ 9. ANONYMOUS POSTING ══ -->
<details class="job-card" style="margin-bottom:20px" aria-labelledby="h-anon">
  <summary class="job-card-header">
    <h2 class="job-card-title" id="h-anon"><svg aria-hidden="true" width="16" height="16"><use href="#i-eye-off"/></svg> Anonymous Posting <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(optional)</span></h2>
    <svg class="job-card-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
  </summary>
  <div class="job-card-body">
    <label class="toggle-wrap" style="cursor:pointer;margin-bottom:12px">
      <span class="toggle"><input type="checkbox" name="is_anonymous" id="post-anon" value="1" <?= old('is_anonymous') ? 'checked' : '' ?> onchange="handleAnonToggle(this)"><span class="toggle-slider"></span></span>
      <span>
        <span class="toggle-label">Post this job anonymously</span>
        <span class="toggle-sub">Your company name and logo will be hidden from the public listing</span>
      </span>
    </label>
    <div class="info-note" id="anon-note" style="display:none">
      <svg aria-hidden="true" width="16" height="16"><use href="#i-eye-off"/></svg>
      <span>Anonymous listings typically receive fewer applications — candidates trust named employers more. Only use this for sensitive replacement hires or confidential projects.</span>
    </div>
  </div>
</details>


</form>
</div><!-- /post-wrap -->
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector(".job-post-form") || document.querySelector("form");
    if(!form || document.querySelector('.wizard-step')) return;
    
    const wrapper = form.querySelector('.card-body') || form;
    const groups = Array.from(wrapper.children).filter(el => el.tagName !== 'BUTTON' && el.tagName !== 'SCRIPT');
    
    if(groups.length > 5) {
        const submitBtn = wrapper.querySelector('button[type="submit"]');
        if(submitBtn) submitBtn.style.display = 'none'; // hide original
        
        const steps = [
            { id: 'step-1', title: '1. Basic Details', items: [] },
            { id: 'step-2', title: '2. Requirements', items: [] },
            { id: 'step-3', title: '3. Finalize', items: [] }
        ];
        
        let currentStep = 0;
        const itemsPerStep = Math.ceil(groups.length / 3);
        
        groups.forEach((el, index) => {
            if(index >= itemsPerStep && index < itemsPerStep * 2) currentStep = 1;
            else if (index >= itemsPerStep * 2) currentStep = 2;
            steps[currentStep].items.push(el);
        });
        
        const wizardContainer = document.createElement('div');
        wizardContainer.className = 'wizard-container mt-4';
        
        const progressHeader = document.createElement('div');
        progressHeader.className = 'd-flex justify-content-between mb-4 border-bottom pb-3';
        steps.forEach((step, idx) => {
            progressHeader.innerHTML += `<div class="wizard-step-indicator fw-bold p-2 ${idx === 0 ? 'text-primary border-bottom border-primary' : 'text-muted'}" id="ind-${idx}">${step.title}</div>`;
        });
        wrapper.insertBefore(progressHeader, wrapper.firstChild);
        
        steps.forEach((step, idx) => {
            const stepPanel = document.createElement('div');
            stepPanel.className = 'wizard-step';
            stepPanel.id = `panel-${idx}`;
            stepPanel.style.display = idx === 0 ? 'block' : 'none';
            
            step.items.forEach(item => stepPanel.appendChild(item));
            
            const btnGroup = document.createElement('div');
            btnGroup.className = 'd-flex justify-content-between mt-5';
            
            if (idx > 0) {
                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.className = 'btn btn-outline-secondary px-4';
                prevBtn.innerText = 'Previous';
                prevBtn.onclick = () => switchStep(idx, idx - 1);
                btnGroup.appendChild(prevBtn);
            } else {
                btnGroup.appendChild(document.createElement('div'));
            }
            
            if (idx < steps.length - 1) {
                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.className = 'btn btn-primary px-5';
                nextBtn.innerText = 'Next';
                nextBtn.onclick = () => switchStep(idx, idx + 1);
                btnGroup.appendChild(nextBtn);
            }
            
            if (idx === steps.length - 1) {
                const finalBtn = document.createElement('button');
                finalBtn.type = 'submit';
                finalBtn.className = 'btn btn-success px-5';
                finalBtn.innerText = 'Post Job';
                btnGroup.appendChild(finalBtn);
            }
            
            stepPanel.appendChild(btnGroup);
            wizardContainer.appendChild(stepPanel);
        });
        
        wrapper.appendChild(wizardContainer);
        
        window.switchStep = function(from, to) {
            document.getElementById(`panel-${from}`).style.display = 'none';
            document.getElementById(`panel-${to}`).style.display = 'block';
            const indFrom = document.getElementById(`ind-${from}`);
            const indTo = document.getElementById(`ind-${to}`);
            indFrom.classList.replace('text-primary', 'text-muted');
            indFrom.classList.remove('border-bottom', 'border-primary');
            indTo.classList.replace('text-muted', 'text-primary');
            indTo.classList.add('border-bottom', 'border-primary');
            window.scrollTo(0, wrapper.offsetTop - 100);
        }
    }
});
</script>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>


/* ══ RESET + TOKENS ══ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  color-scheme: light;
  --brand: #0D609E; --brand-dark: #064A85; --brand-deep: #0A2F57; --brand-light: #E6F0F8;
  --accent: #F08F1A; --accent-dark: #C8770E;
  --text: #141926; --muted: #5b6577; --bg: #f5f7fb; --white: #ffffff;
  --border: #e2e8f2; --success: #16a34a; --danger: #dc2626;
  --radius: 10px; --shadow: 0 2px 14px rgba(10,47,87,.08);
  --shadow-lg: 0 14px 40px rgba(10,47,87,.16); --transition: .18s ease;
}
html { scroll-behavior: smooth; }
body { font-family: 'Inter','Segoe UI',system-ui,sans-serif; background: var(--bg); color: var(--text); font-size: 15px; line-height: 1.7; overflow-x: hidden; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; }
h1,h2,h3,.nav-logo { font-family: 'Sora','Inter',sans-serif; letter-spacing: -.02em; }
a { color: var(--brand); text-decoration: none; }
a:hover { text-decoration: underline; }
img { max-width: 100%; height: auto; display: block; }
svg { flex-shrink: 0; }
.container { max-width: 1160px; margin: 0 auto; padding: 0 20px; }
.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; border-radius: 4px; }

/* ══ BUTTONS (shared with training/homepage) ══ */
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 11px 22px; border-radius: 8px; font-family: 'Inter',sans-serif; font-size: .88rem; font-weight: 600; cursor: pointer; border: 1.5px solid transparent; transition: var(--transition); text-decoration: none; -webkit-tap-highlight-color: transparent; }
.btn svg { width: 16px; height: 16px; }
.btn-primary  { background: var(--brand); color: var(--white); border-color: var(--brand); }
.btn-primary:hover  { background: var(--brand-dark); border-color: var(--brand-dark); text-decoration: none; }
.btn-outline  { background: transparent; color: var(--brand); border-color: var(--border); }
.btn-outline:hover  { background: var(--brand); color: var(--white); border-color: var(--brand); text-decoration: none; }
.btn-accent   { background: var(--accent); color: var(--brand-deep); border-color: var(--accent); }
.btn-accent:hover   { background: var(--accent-dark); border-color: var(--accent-dark); text-decoration: none; }
.btn-danger   { background: #fef2f2; color: var(--danger); border-color: #fecaca; }
.btn-danger:hover   { background: #fee2e2; text-decoration: none; }
.btn-sm  { padding: 7px 14px; font-size: .78rem; }
.btn-lg  { padding: 13px 28px; font-size: .95rem; }

/* ══ NAVBAR ══ */
.navbar { position: sticky; top: 0; z-index: 1000; background: rgba(255,255,255,.92); backdrop-filter: saturate(180%) blur(12px); border-bottom: 1px solid var(--border); box-shadow: 0 1px 6px rgba(10,47,87,.06); padding-top: env(safe-area-inset-top, 0); }
.nav-inner { display: flex; align-items: center; justify-content: space-between; height: 70px; gap: 16px; }
.nav-logo { display: flex; align-items: center; text-decoration: none; flex-shrink: 0; }
.nav-logo img { height: 60px; width: auto; display: block; }
.nav-links { display: flex; align-items: center; gap: 24px; list-style: none; }
.nav-links a { font-size: .85rem; font-weight: 500; color: var(--text); transition: color var(--transition); }
.nav-links a:hover { color: var(--brand); text-decoration: none; }
.nav-dropdown { position: relative; }
.nav-dropdown-toggle { display: inline-flex; align-items: center; gap: 4px; font-family: 'Inter',sans-serif; font-size: .85rem; font-weight: 500; color: var(--text); background: none; border: none; cursor: pointer; padding: 0; transition: color var(--transition); }
.nav-dropdown-toggle:hover, .nav-dropdown-toggle[aria-expanded="true"] { color: var(--brand); }
.nav-caret { width: 13px; height: 13px; transition: transform var(--transition); }
.nav-dropdown-toggle[aria-expanded="true"] .nav-caret { transform: rotate(180deg); }
.nav-dropdown-menu { position: absolute; top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(6px); min-width: 210px; background: var(--white); border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow-lg); padding: 8px; display: flex; flex-direction: column; gap: 2px; opacity: 0; visibility: hidden; pointer-events: none; transition: opacity .16s ease, transform .16s ease; z-index: 60; }
.nav-dropdown:hover .nav-dropdown-menu, .nav-dropdown-toggle[aria-expanded="true"] + .nav-dropdown-menu { opacity: 1; visibility: visible; pointer-events: auto; transform: translateX(-50%) translateY(0); }
.nav-dropdown-menu a { display: block; padding: 9px 12px; border-radius: 8px; font-size: .85rem; font-weight: 500; color: var(--text); white-space: nowrap; transition: background var(--transition), color var(--transition); }
.nav-dropdown-menu a:hover { background: var(--brand-light); color: var(--brand); text-decoration: none; }
.mob-group { display: flex; flex-direction: column; }
.mob-group-label { font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); padding: 10px 20px 4px; }
.mob-group a { padding-left: 34px !important; }
.nav-actions { display: flex; align-items: center; gap: 8px; }
.nav-actions .btn { padding: 8px 16px; font-size: .82rem; }
.hamburger { display: none; background: none; border: none; cursor: pointer; padding: 8px; color: var(--text); line-height: 0; -webkit-tap-highlight-color: transparent; }
.mobile-nav { display: none; flex-direction: column; background: var(--white); border-top: 1px solid var(--border); }
.mobile-nav a { padding: 14px 20px; border-bottom: 1px solid var(--border); font-size: .9rem; font-weight: 500; color: var(--text); min-height: 48px; display: flex; align-items: center; }
.mobile-nav a:hover { background: var(--bg); text-decoration: none; }
.mobile-nav.open { display: flex; }
.mobile-nav-cta { color: var(--brand) !important; font-weight: 700 !important; }

/* ══ STICKY PROGRESS BAR ══ */
.progress-bar { position: sticky; top: 70px; z-index: 900; background: var(--white); border-bottom: 1px solid var(--border); padding: 12px 0; box-shadow: 0 2px 8px rgba(10,47,87,.06); }
.progress-inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.progress-left { display: flex; align-items: center; gap: 16px; flex: 1; min-width: 0; }

/* Progress track — position:relative to hold milestone markers */
.progress-track { flex: 1; max-width: 360px; height: 8px; background: var(--border); border-radius: 20px; position: relative; }
.progress-fill { height: 100%; background: linear-gradient(90deg, var(--brand), #1a7fd4); border-radius: 20px; transition: width .6s ease; }
.progress-text { font-size: .82rem; font-weight: 700; color: var(--text); white-space: nowrap; }
.progress-tip { font-size: .78rem; color: var(--accent-dark); font-weight: 600; white-space: nowrap; display: inline-flex; align-items: center; gap: 5px; }
.progress-tip svg { width: 13px; height: 13px; }
.progress-actions { display: flex; gap: 8px; flex-shrink: 0; }

/* Milestone markers on track — ₦200 at 60%, ₦500 at 80% */
.milestone-marker {
  position: absolute; top: 50%; transform: translate(-50%, -50%);
  width: 14px; height: 14px; border-radius: 50%; z-index: 2;
  background: var(--white); border: 2px solid #c8dff2;
  transition: background .35s ease, border-color .35s ease, box-shadow .35s ease;
}
.milestone-marker.achieved {
  background: var(--accent); border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(237,144,32,.22);
}
.milestone-marker.next { border-color: var(--accent); animation: marker-pulse 1.6s ease infinite; }
@keyframes marker-pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(237,144,32,.4); } 50% { box-shadow: 0 0 0 5px rgba(237,144,32,0); } }
.milestone-label {
  position: absolute; bottom: 14px; left: 50%; transform: translateX(-50%);
  font-size: .6rem; font-weight: 700; white-space: nowrap;
  letter-spacing: .03em; color: #b0bec5; transition: color .35s ease;
}
.milestone-marker.achieved .milestone-label,
.milestone-marker.next    .milestone-label { color: var(--accent-dark); }

/* Wallet balance chip */
.wallet-chip {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--brand-deep); color: var(--white);
  padding: 5px 13px; border-radius: 20px;
  font-size: .78rem; font-weight: 700; flex-shrink: 0;
  cursor: default; user-select: none;
  transition: background .3s ease;
}
.wallet-chip svg { width: 13px; height: 13px; }
.wallet-chip.has-balance { background: linear-gradient(120deg, var(--accent-dark), var(--accent)); color: var(--brand-deep); }

/* Milestone toast notification */
.milestone-toast {
  position: fixed; top: 90px; left: 50%;
  transform: translateX(-50%) translateY(-160px);
  z-index: 1200;
  background: var(--white); border-radius: 16px;
  padding: 18px 20px 18px 18px;
  box-shadow: 0 20px 60px rgba(10,47,87,.22), 0 4px 16px rgba(10,47,87,.1);
  border-left: 5px solid var(--accent);
  display: flex; align-items: center; gap: 14px;
  min-width: 320px; max-width: 480px;
  transition: transform .45s cubic-bezier(.34,1.56,.64,1), opacity .3s ease;
  opacity: 0; pointer-events: none;
}
.milestone-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; pointer-events: auto; }
.toast-emoji { font-size: 2rem; line-height: 1; flex-shrink: 0; }
.toast-body { flex: 1; }
.toast-body strong { display: block; font-size: .95rem; font-weight: 700; color: var(--text); margin-bottom: 3px; }
.toast-body p { font-size: .8rem; color: var(--muted); margin: 0; line-height: 1.5; }
.toast-close {
  background: none; border: none; cursor: pointer; color: var(--muted);
  font-size: 1.3rem; line-height: 1; padding: 4px; border-radius: 6px;
  flex-shrink: 0; align-self: flex-start; transition: var(--transition);
}
.toast-close:hover { background: var(--bg); color: var(--text); }

/* Milestone summary bar — shows both milestones with status */
.milestone-bar {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 16px; background: var(--bg);
  border: 1px solid var(--border); border-radius: var(--radius);
  font-size: .8rem; flex-wrap: wrap;
}
.milestone-item { display: inline-flex; align-items: center; gap: 6px; }
.milestone-item .m-badge {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 10px; border-radius: 20px; font-size: .73rem; font-weight: 700;
}
.milestone-item .m-badge.locked   { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
.milestone-item .m-badge.next     { background: #fff3e0; color: var(--accent-dark); border: 1px solid #fde3bf; }
.milestone-item .m-badge.achieved { background: var(--brand-light); color: var(--brand); border: 1px solid #c8dff2; }
.milestone-divider { color: var(--border); font-size: 1rem; }

@media (max-width: 640px) {
  .milestone-toast { min-width: calc(100vw - 32px); max-width: calc(100vw - 32px); }
  .wallet-chip span.wallet-label { display: none; }
}

/* ══ PAGE LAYOUT ══ */
.profile-page { padding: 36px 0 72px; }
.profile-wrap { max-width: 860px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; }

/* ══ CV SECTION CARDS ══ */
.cv-card { background: var(--white); border: 1px solid var(--border); border-left: 3px solid var(--accent); border-radius: 12px; overflow: hidden; }
.cv-card-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px 16px; gap: 12px; }
.cv-card-title svg { width: 18px; height: 18px; color: var(--accent); }
.cv-card-done { display: inline-flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; padding: 3px 9px; border-radius: 20px; }
.cv-card-done.complete  { background: var(--brand-light); color: var(--brand); border: 1px solid #c8dff2; }
.cv-card-done.incomplete { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
.cv-card-done.optional  { background: var(--brand-light); color: var(--brand); }
/* Border flips from orange → brand blue when a section is saved */
.cv-card.is-complete { border-left-color: var(--brand); }
.cv-card-body { padding: 0 24px 24px; }
.cv-card-hint { font-size: .84rem; color: var(--muted); background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 12px 14px; margin-bottom: 18px; line-height: 1.6; }

/* ══ FORM ELEMENTS ══ */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.form-grid.cols-1 { grid-template-columns: 1fr; }
.form-field { display: flex; flex-direction: column; gap: 5px; }
.form-field.full { grid-column: 1/-1; }
label { font-size: .82rem; font-weight: 600; color: var(--text); }
label .opt { font-weight: 400; color: var(--muted); font-size: .78rem; }
input[type="text"], input[type="email"], input[type="tel"], input[type="url"], input[type="date"], select, textarea {
  width: 100%; border: 1px solid var(--border); border-radius: 8px;
  padding: 10px 14px; font-family: 'Inter',sans-serif; font-size: .9rem; color: var(--text);
  background: var(--bg); outline: none; transition: border-color var(--transition), background var(--transition);
  appearance: none; -webkit-appearance: none; min-height: 42px;
}
input:focus, select:focus, textarea:focus { border-color: var(--brand); background: var(--white); }
select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%235b6577' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m4 6 4 4 4-4'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }
textarea { resize: vertical; min-height: 110px; line-height: 1.65; }
.char-count { font-size: .74rem; color: var(--muted); text-align: right; margin-top: 3px; }
.form-check { display: flex; align-items: center; gap: 9px; font-size: .85rem; color: var(--text); cursor: pointer; }
.form-check input[type="checkbox"] { width: 16px; height: 16px; min-height: 16px; accent-color: var(--brand); cursor: pointer; }

/* ══ EXPERIENCE / EDUCATION ENTRY CARDS ══ */
.entry-list { display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px; }
.entry-item { border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; background: var(--bg); position: relative; }
.entry-item-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 14px; }
.entry-item-title { font-weight: 700; font-size: .9rem; }
.entry-item-sub { font-size: .8rem; color: var(--muted); }
.entry-remove { background: none; border: none; cursor: pointer; color: var(--muted); padding: 4px; border-radius: 6px; line-height: 0; transition: var(--transition); }
.entry-remove:hover { color: var(--danger); background: #fef2f2; }
.entry-remove svg { width: 15px; height: 15px; }

/* ══ SKILLS ══ */
.skill-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 18px; }
.skill-row { display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: center; border: 1px solid var(--border); border-radius: var(--radius); padding: 14px 16px; background: var(--bg); }
.skill-dots { display: flex; gap: 6px; align-items: center; flex-shrink: 0; }
.skill-dot { width: 26px; height: 10px; border-radius: 20px; border: 1.5px solid var(--border); background: var(--white); cursor: pointer; transition: var(--transition); }
.skill-dot.active { background: var(--brand); border-color: var(--brand); }
.skill-dot:hover { border-color: var(--brand); }
.skill-level-label { font-size: .7rem; color: var(--muted); margin-left: 4px; white-space: nowrap; }
.skill-row-inner { display: flex; align-items: center; gap: 14px; }

/* ══ PHOTO UPLOAD ══ */
.photo-upload { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
.photo-preview { width: 88px; height: 88px; border-radius: 50%; border: 2px solid var(--border); background: var(--bg); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
.photo-preview svg { width: 42px; height: 42px; color: var(--muted); }
.photo-preview img { width: 100%; height: 100%; object-fit: cover; }
.photo-upload-body { display: flex; flex-direction: column; gap: 6px; }
.photo-upload-body p { font-size: .8rem; color: var(--muted); }

/* ══ AI BUTTON ══ */
.ai-action { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px; }
.ai-btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 8px; font-size: .82rem; font-weight: 700; background: linear-gradient(135deg, var(--brand-deep), var(--brand)); color: var(--white); border: none; cursor: pointer; transition: var(--transition); }
.ai-btn:hover { opacity: .9; }
.ai-btn svg { width: 15px; height: 15px; }

/* ══ SECTION ACTIONS ══ */
.form-actions { display: flex; align-items: center; gap: 10px; padding-top: 18px; border-top: 1px solid var(--border); margin-top: 20px; flex-wrap: wrap; }
.form-actions .autosave-note { font-size: .76rem; color: var(--muted); margin-left: auto; display: inline-flex; align-items: center; gap: 5px; }
.form-actions .autosave-note svg { width: 13px; height: 13px; color: var(--success); }

/* ══ SOCIAL LINKS ══ */
.social-row { display: grid; grid-template-columns: 160px 1fr auto; gap: 10px; align-items: center; margin-bottom: 10px; }
.social-row button.remove-social { background: none; border: none; cursor: pointer; color: var(--muted); padding: 8px; border-radius: 6px; line-height: 0; }
.social-row button.remove-social:hover { color: var(--danger); }
.social-row button.remove-social svg { width: 16px; height: 16px; }

/* ══ VISIBILITY BANNER ══ */
.visibility-banner { background: var(--brand-light); border: 1px solid #c8dff2; border-radius: 12px; padding: 18px 22px; display: flex; align-items: center; gap: 16px; }
.visibility-banner svg { width: 22px; height: 22px; color: var(--brand); flex-shrink: 0; }
.visibility-banner-text { flex: 1; }
.visibility-banner-text strong { font-size: .9rem; font-weight: 700; color: var(--brand-deep); display: block; margin-bottom: 2px; }
.visibility-banner-text p { font-size: .82rem; color: var(--muted); margin: 0; }

/* ══ AUTO-ADVANCE + PROGRESS ENHANCEMENTS ══ */
/* Pulse on progress bar fill after a save */
@keyframes bar-pulse { 0%,100% { opacity:1; } 50% { opacity:.65; } }
.progress-fill.pulse { animation: bar-pulse .5s ease 2; }

/* Dynamic "+N% if you complete this" tip on each card header */
.cv-gain-tip {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: .68rem; font-weight: 700; letter-spacing: .02em;
  color: var(--accent-dark); background: #fff3e0;
  border: 1px solid #fde3bf; padding: 3px 9px; border-radius: 20px;
  flex-shrink: 0; white-space: nowrap;
}
.cv-gain-tip svg { width: 11px; height: 11px; }

/* "You're up next" highlight ring when auto-advancing */
@keyframes next-glow { 0% { box-shadow: 0 0 0 0 rgba(8,97,169,.35); } 60% { box-shadow: 0 0 0 6px rgba(8,97,169,0); } 100% { box-shadow: none; } }
.cv-card.next-up { animation: next-glow .7s ease forwards; border-color: var(--brand) !important; }
   Each .cv-card is a <details> element; the .cv-card-header is its <summary>.
   Open by default. Chevron rotates 180° when closed. */
.cv-card { cursor: default; }
.cv-card-header {
  list-style: none; cursor: pointer;
  display: flex; align-items: center; gap: 10px;
  padding: 20px 24px 16px; flex-wrap: wrap;
  -webkit-tap-highlight-color: transparent; user-select: none;
}
.cv-card-header::-webkit-details-marker { display: none; }
.cv-card-header::marker { display: none; }
.cv-card-header:hover { background: #fafbfd; }
/* Title takes all available space and shrinks — badges never get squeezed */
.cv-card-title { flex: 1; min-width: 0; display: flex; align-items: center; gap: 10px; font-family: 'Sora',sans-serif; font-size: 1.05rem; font-weight: 700; color: var(--text); }
.cv-card-title svg { width: 18px; height: 18px; color: var(--accent); flex-shrink: 0; }
/* Push the badge cluster to the far right */
.cv-card-done { display: inline-flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; padding: 3px 9px; border-radius: 20px; flex-shrink: 0; white-space: nowrap; margin-left: auto; }
.cv-chev {
  width: 18px; height: 18px; flex-shrink: 0; color: var(--muted);
  transition: transform .22s ease;
}
.cv-card:not([open]) .cv-chev { transform: rotate(-90deg); }
/* Smooth body reveal */
.cv-card .cv-card-body { animation: cv-open .18s ease; }
@keyframes cv-open { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }
@media (prefers-reduced-motion: reduce) { .cv-card .cv-card-body { animation: none; } .cv-chev { transition: none; } }
.bottom-actions { display: flex; align-items: center; justify-content: center; gap: 12px; padding: 24px; border-top: 1px solid var(--border); background: var(--white); border-radius: 12px; }

/* ══ FOOTER (shared) ══ */
.footer { background: #0A2F57; color: rgba(255,255,255,.78); padding: 56px 0 0; padding-bottom: env(safe-area-inset-bottom, 0); }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 36px; margin-bottom: 44px; }
.footer-logo { display: flex; align-items: center; text-decoration: none; margin-bottom: 14px; }
.footer-logo-img { height: 52px; width: auto; }
.footer-brand p { font-size: .83rem; line-height: 1.75; opacity: .78; margin-bottom: 18px; }
.footer-socials { display: flex; gap: 8px; flex-wrap: wrap; }
.footer-socials a { width: 38px; height: 38px; border-radius: 8px; background: rgba(255,255,255,.09); color: var(--white); display: flex; align-items: center; justify-content: center; transition: var(--transition); text-decoration: none; }
.footer-socials a svg { width: 17px; height: 17px; }
.footer-socials a:hover { background: var(--brand); }
.footer-col h3 { font-family: 'Sora',sans-serif; font-size: .78rem; font-weight: 700; color: var(--white); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 15px; }
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.footer-col ul a { font-size: .82rem; color: rgba(255,255,255,.68); transition: var(--transition); min-height: 26px; display: inline-flex; align-items: center; }
.footer-col ul a:hover { color: var(--white); text-decoration: none; }
.footer-bottom { border-top: 1px solid rgba(255,255,255,.1); padding: 18px 0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px; font-size: .78rem; color: rgba(255,255,255,.45); }
.footer-bottom a { color: rgba(255,255,255,.55); }
.footer-bottom a:hover { color: var(--white); }
.footer-links { display: flex; gap: 18px; flex-wrap: wrap; }

/* ══ RESPONSIVE ══ */
@media (max-width: 860px) {
  .nav-links, .nav-actions .btn-outline { display: none; }
  .hamburger { display: block; }
  .footer-grid { grid-template-columns: 1fr 1fr; }
  .form-grid { grid-template-columns: 1fr; }
  .form-grid.cols-3 { grid-template-columns: 1fr 1fr; }
  .social-row { grid-template-columns: 1fr 1fr auto; }
}
@media (max-width: 640px) {
  .progress-inner { flex-direction: column; align-items: flex-start; gap: 10px; }
  .progress-tip { display: none; }
  .footer-grid { grid-template-columns: 1fr; gap: 24px; }
  .footer-bottom { flex-direction: column; text-align: center; }
  .footer-links { justify-content: center; }
  .social-row { grid-template-columns: 1fr; }
  .photo-upload { flex-direction: column; align-items: flex-start; }
  .form-grid.cols-3 { grid-template-columns: 1fr; }
  .skill-row { grid-template-columns: 1fr; }
  .bottom-actions { flex-direction: column; }
}
@media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; } }

/* iOS dark mode defeat */
html, body { background: #f5f7fb; }
.cv-card, .entry-item, .skill-row, .bottom-actions { background: #ffffff; }
.entry-item, .skill-row { background: #f5f7fb; }
.cv-card-title, h2, h3 { color: #141926; }

/* ══ JOB PREFERENCES — pill-style checkbox/radio groups ══ */
.pref-pill-group { display: flex; flex-wrap: wrap; gap: 8px; }
.pref-pill { display: inline-flex; align-items: center; gap: 7px; padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 20px; font-size: .84rem; font-weight: 500; cursor: pointer; transition: var(--transition); user-select: none; background: var(--white); }
.pref-pill input { width: 14px; height: 14px; min-height: 14px; accent-color: var(--brand); cursor: pointer; }
.pref-pill:has(input:checked) { background: var(--brand-light); border-color: var(--brand); color: var(--brand); font-weight: 600; }
.salary-range { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 10px; }
.salary-range span { text-align: center; color: var(--muted); font-size: .85rem; }

/* ══ LANGUAGES ══ */
.lang-row { display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: center; margin-bottom: 10px; }
.lang-row .remove-lang { background: none; border: none; cursor: pointer; color: var(--muted); padding: 8px; border-radius: 6px; line-height: 0; transition: var(--transition); }
.lang-row .remove-lang:hover { color: var(--danger); }
.lang-row .remove-lang svg { width: 16px; height: 16px; }

/* ══ JOBBERRECRUIT AUTO-SYNCED CERTIFICATES ══ */
.jr-auto-certs { margin-bottom: 22px; border: 1px solid var(--brand-light); border-radius: var(--radius); overflow: hidden; }
.jr-auto-header { display: flex; align-items: center; gap: 8px; padding: 11px 16px; background: var(--brand-light); font-size: .8rem; font-weight: 700; color: var(--brand); }
.jr-auto-header svg { width: 14px; height: 14px; }
.jr-auto-empty { padding: 14px 16px; font-size: .83rem; color: var(--muted); border-top: 1px solid var(--brand-light); }
.jr-cert-item { display: flex; align-items: center; gap: 14px; padding: 13px 16px; border-top: 1px solid var(--border); }
.jr-cert-icon { width: 36px; height: 36px; border-radius: 8px; background: var(--brand); color: var(--white); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.jr-cert-icon svg { width: 18px; height: 18px; }
.jr-cert-body { flex: 1; }
.jr-cert-body strong { display: block; font-size: .87rem; font-weight: 700; color: var(--text); }
.jr-cert-body span { font-size: .75rem; color: var(--muted); }
.jr-verified-tag { display: inline-flex; align-items: center; gap: 4px; font-size: .64rem; font-weight: 700; letter-spacing: .03em; text-transform: uppercase; padding: 3px 9px; border-radius: 20px; background: var(--brand); color: var(--white); flex-shrink: 0; }
.jr-verified-tag svg { width: 10px; height: 10px; }
.jr-auto-link { font-size: .78rem; color: var(--brand); font-weight: 600; padding: 10px 16px; display: block; border-top: 1px solid var(--brand-light); text-align: center; }
.jr-auto-link:hover { background: var(--brand-light); text-decoration: none; }

/* ══ REFERENCES ══ */
.ref-toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); margin-bottom: 18px; }
.ref-toggle-row label { font-size: .88rem; font-weight: 600; color: var(--text); cursor: pointer; }
.ref-entry { border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; background: var(--bg); margin-bottom: 14px; position: relative; }
.ref-entry-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.ref-entry-num { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); }

@media (max-width: 640px) {
  .lang-row { grid-template-columns: 1fr 1fr auto; }
  .salary-range { grid-template-columns: 1fr; }
  .salary-range span { display: none; }
}


/* ══ POST A JOB — PAGE SPECIFIC ══ */
.post-wrap { max-width: 900px; margin: 0 auto; padding: 0 20px 100px; }
@media (max-width: 640px) { .post-wrap { padding-bottom: 72px; } }

/* Info / hint note box */
.info-note {
  display: flex; align-items: flex-start; gap: 10px;
  background: var(--brand-light); border: 1px solid #c8dff2;
  border-radius: var(--radius); padding: 12px 14px;
  font-size: .82rem; color: var(--brand-deep); line-height: 1.6;
}
.info-note svg { width: 16px; height: 16px; flex-shrink: 0; color: var(--brand); margin-top: 1px; }

/* Job card — same .cv-card pattern but starts OPEN */
.job-card { background: var(--white); border: 1px solid var(--border); border-left: 3px solid var(--accent); border-radius: 12px; overflow: hidden; }
.job-card[open] .job-card-body { display: block; }
.job-card-header { list-style: none; cursor: pointer; display: flex; align-items: center; gap: 10px; padding: 18px 24px; -webkit-tap-highlight-color: transparent; user-select: none; }
.job-card-header::-webkit-details-marker { display: none; }
.job-card-header::marker { display: none; }
.job-card-header:hover { background: #fafbfd; }
.job-card-title { flex: 1; min-width: 0; display: flex; align-items: center; gap: 10px; font-family: 'Sora','Inter',sans-serif; font-size: 1rem; font-weight: 700; color: var(--text); }
.job-card-title svg { width: 18px; height: 18px; color: var(--accent); flex-shrink: 0; }
.job-card-body { padding: 0 24px 24px; }
.job-card-chev { width: 17px; height: 17px; flex-shrink: 0; color: var(--muted); transition: transform .2s ease; }
.job-card:not([open]) .job-card-chev { transform: rotate(-90deg); }
.required-star { color: #e53e3e; font-size: .8rem; margin-left: 2px; }

/* Toggle switch */
.toggle-wrap { display: flex; align-items: center; gap: 12px; }
.toggle { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; inset: 0; background: var(--border); border-radius: 24px; cursor: pointer; transition: .25s; }
.toggle-slider::before { content: ''; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: var(--white); border-radius: 50%; transition: .25s; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
.toggle input:checked + .toggle-slider { background: var(--brand); }
.toggle input:checked + .toggle-slider::before { transform: translateX(20px); }
.toggle-label { font-size: .88rem; font-weight: 600; color: var(--text); cursor: pointer; }
.toggle-sub { font-size: .76rem; color: var(--muted); display: block; margin-top: 2px; }

/* Skill tag input */
.tag-input-wrap { border: 1px solid var(--border); border-radius: 8px; padding: 8px 10px; background: var(--bg); min-height: 48px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; cursor: text; transition: border-color var(--transition); }
.tag-input-wrap:focus-within { border-color: var(--brand); background: var(--white); }
.skill-tag { display: inline-flex; align-items: center; gap: 5px; background: var(--brand-light); color: var(--brand); font-size: .8rem; font-weight: 600; padding: 4px 10px; border-radius: 20px; }
.skill-tag button { background: none; border: none; cursor: pointer; color: var(--brand); line-height: 0; padding: 0; font-size: 1rem; opacity: .7; }
.skill-tag button:hover { opacity: 1; }
.tag-input { border: none; outline: none; background: transparent; font-family: 'Inter',sans-serif; font-size: .88rem; min-width: 140px; color: var(--text); padding: 2px 4px; }

/* Application method radio pills */
.method-group { display: flex; flex-wrap: wrap; gap: 8px; }
.method-pill { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border: 1.5px solid var(--border); border-radius: 10px; font-size: .85rem; font-weight: 500; cursor: pointer; transition: var(--transition); background: var(--white); }
.method-pill input { accent-color: var(--brand); width: 15px; height: 15px; min-height: 15px; }
.method-pill:has(input:checked) { background: var(--brand-light); border-color: var(--brand); color: var(--brand); font-weight: 600; }

/* Pre-screening question builder */
.question-item { border: 1px solid var(--border); border-radius: var(--radius); padding: 14px 16px; background: var(--bg); margin-bottom: 10px; }
.question-item-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.question-handle { cursor: grab; color: var(--muted); }
.question-handle svg { width: 16px; height: 16px; }
.question-num { font-size: .72rem; font-weight: 700; color: var(--muted); background: var(--border); padding: 2px 7px; border-radius: 20px; }
.question-remove { background: none; border: none; cursor: pointer; color: var(--muted); margin-left: auto; line-height: 0; padding: 4px; border-radius: 6px; }
.question-remove:hover { color: var(--danger); background: #fef2f2; }
.question-remove svg { width: 14px; height: 14px; }

/* ── Multiple-choice options builder ── */
.mc-options-wrap { margin-top: 10px; display: none; border: 1px dashed var(--border); border-radius: 8px; padding: 12px; background: var(--white); }
.mc-options-wrap.visible { display: block; }
.mc-options-label { font-size: .73rem; font-weight: 700; color: var(--muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: .05em; }
.mc-option-row { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.mc-option-row input { flex: 1; border: 1px solid var(--border); border-radius: 6px; padding: 7px 10px; font-size: .84rem; font-family: 'Inter',sans-serif; color: var(--text); background: var(--bg); outline: none; transition: border-color var(--transition); }
.mc-option-row input:focus { border-color: var(--brand); background: var(--white); }
.mc-remove-opt { background: none; border: 1px solid var(--border); border-radius: 6px; width: 28px; height: 28px; cursor: pointer; color: var(--muted); display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: var(--transition); }
.mc-remove-opt:hover { border-color: #b91c1c; color: #b91c1c; background: #fef2f2; }
.mc-remove-opt svg { width: 13px; height: 13px; }
.mc-add-opt { display: inline-flex; align-items: center; gap: 5px; font-size: .78rem; font-weight: 600; color: var(--brand); background: none; border: none; cursor: pointer; padding: 2px 0; margin-top: 4px; }
.mc-add-opt:hover { text-decoration: underline; }
.mc-add-opt svg { width: 13px; height: 13px; }

/* SEO preview card — simulates Google for Jobs */
.google-logo { width: 40px; height: 40px; border-radius: 8px; border: 1px solid #dadce0; display: flex; align-items: center; justify-content: center; background: var(--brand-light); flex-shrink: 0; font-weight: 700; font-size: .8rem; color: var(--brand); }
.google-meta-item svg { width: 14px; height: 14px; color: #70757a; }

/* Salary conditional fields */
.salary-conditional { display: none; }
.salary-conditional.visible { display: grid; }

/* Featured / urgent toggle row */
.boost-row { display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border: 1.5px solid var(--border); border-radius: var(--radius); background: var(--bg); }
.boost-row.featured { border-color: #fde3bf; background: #fff9f0; }
.boost-row.urgent   { border-color: #fecaca; background: #fef9f9; }
.boost-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
.boost-icon.orange { background: #fff3e0; }
.boost-icon.red    { background: #fef2f2; }
.boost-icon svg { width: 18px; height: 18px; }
.boost-icon.orange svg { color: var(--accent); }
.boost-icon.red    svg { color: var(--danger); }
.boost-body { flex: 1; min-width: 0; }
.boost-body-hd { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px; }
.boost-body-hd strong { font-size: .88rem; font-weight: 700; color: var(--text); }
.boost-body-desc { font-size: .78rem; color: var(--muted); line-height: 1.55; }
.boost-tag { display: inline-flex; align-items: center; font-size: .68rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; padding: 2px 9px; border-radius: 20px; flex-shrink: 0; }
.boost-tag.plan { background: var(--brand-light); color: var(--brand); border: 1px solid #c8dff2; }

/* Sticky publish bar */
.publish-bar {
  position: fixed; bottom: 0; left: 0; right: 0; z-index: 900;
  background: var(--white); border-top: 1px solid var(--border);
  box-shadow: 0 -4px 20px rgba(10,47,87,.1);
  padding: 14px 0;
  padding-bottom: calc(14px + env(safe-area-inset-bottom, 0));
  transition: transform .3s ease;
}
.publish-bar.bar-hidden { transform: translateY(100%); }
.publish-bar-inner { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.publish-bar-info { display: flex; flex-direction: column; }
.publish-bar-info strong { font-size: .88rem; font-weight: 700; color: var(--text); }
.publish-bar-info span { font-size: .76rem; color: var(--muted); }
.publish-bar-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

@media (max-width: 640px) {
  /* ── Mobile publish bar: compact single row, no info text ── */
  .publish-bar { padding: 10px 0; padding-bottom: calc(10px + env(safe-area-inset-bottom, 0)); }
  .publish-bar-inner { flex-direction: row; align-items: center; flex-wrap: nowrap; gap: 8px; }
  .publish-bar-info { display: none; }             /* hide "Ready to publish?" text */
  .publish-bar-actions { width: 100%; display: flex; flex-direction: row; gap: 8px; }
  .publish-bar-actions .btn-outline { flex: 0 0 auto; padding: 10px 14px; font-size: .82rem; }
  .publish-bar-actions .btn-accent  { flex: 1; justify-content: center; font-size: .88rem; padding: 11px 16px; }
  .method-group { flex-direction: column; }
  .method-pill { width: 100%; }
  }


/* Skip link (keyboard accessibility) */
.skip-link { position:absolute; top:-50px; left:16px; background:var(--brand); color:var(--white); padding:8px 16px; border-radius:0 0 6px 6px; font-weight:600; z-index:9999; transition:top .2s; }
.skip-link:focus { top:0; }

/* ── Mobile touch targets: Apple HIG minimum 44px ── */
@media (max-width: 900px) {
  .btn    { min-height: 44px; }
  .btn-sm { min-height: 40px; padding: 9px 14px; }
  .btn-lg { min-height: 50px; }
  /* Nav hamburger and save buttons */
  .hamburger { min-height: 44px; min-width: 44px; }
  .save-btn, .btn-report { min-height: 44px; }
}

</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

/* ── Mobile menu ── */
function toggleMenu(btn) {
  var nav = document.getElementById('mob-nav');
  var open = nav.classList.toggle('open');
  btn.setAttribute('aria-expanded', String(open));
}
document.getElementById('mob-nav').addEventListener('click', function(e) {
  if (e.target.tagName === 'A') { this.classList.remove('open'); }
});
document.querySelectorAll('.nav-dropdown-toggle').forEach(function(t) {
  t.addEventListener('click', function(e) {
    e.stopPropagation();
    var open = t.getAttribute('aria-expanded') === 'true';
    document.querySelectorAll('.nav-dropdown-toggle[aria-expanded="true"]').forEach(function(o) { o.setAttribute('aria-expanded','false'); });
    t.setAttribute('aria-expanded', String(!open));
  });
});
document.addEventListener('click', function() {
  document.querySelectorAll('.nav-dropdown-toggle[aria-expanded="true"]').forEach(function(o) { o.setAttribute('aria-expanded','false'); });
});

/* ── Character counter ── */
function updateCount(fieldId, countId, max) {
  var el = document.getElementById(fieldId);
  var counter = document.getElementById(countId);
  if (el && counter) counter.textContent = el.value.length.toLocaleString();
}

/* ── Salary field visibility ── */
function showSalaryFields(type) {
  var rangeWrap  = document.getElementById('salary-range-wrap');
  var fixedWrap  = document.getElementById('salary-fixed-wrap');
  var periodWrap = document.getElementById('salary-period-wrap');
  var displayWrap = document.getElementById('salary-display-wrap');
  if (rangeWrap)  rangeWrap.classList.remove('visible');
  if (fixedWrap)  fixedWrap.classList.remove('visible');
  if (type === 'range') { if (rangeWrap) rangeWrap.classList.add('visible'); }
  if (type === 'fixed') { if (fixedWrap) fixedWrap.classList.add('visible'); }
  var hide = type === 'negotiable' || type === 'undisclosed';
  if (periodWrap)  periodWrap.style.display  = hide ? 'none' : '';
  if (displayWrap) displayWrap.style.display = hide ? 'none' : '';
  
}

/* ── Application method detail field ── */
function toggleMethodDetail(value) {
  var detail   = document.getElementById('method-detail');
  var input    = document.getElementById('method-detail-input');
  var atsNote  = document.getElementById('ats-unavailable-note');
  var atsSection = document.getElementById('ats-section');

  /* Detail input for WhatsApp / Email / External */
  var needsDetail = value === 'whatsapp' || value === 'email' || value === 'external';
  if (detail) detail.style.display = needsDetail ? 'block' : 'none';
  var placeholders = {
    whatsapp: 'WhatsApp number (e.g. 08012345678)',
    email:    'Email address for applications',
    external: 'Full URL of your application page (https://...)'
  };
  if (input && needsDetail) input.placeholder = placeholders[value] || '';

  /* ATS section — only relevant when using the JobberRecruit form */
  var isJR = value === 'jobberrecruit';
  if (atsSection) {
    atsSection.style.display  = isJR ? '' : 'none';
    atsSection.style.opacity  = isJR ? '1' : '0';
    atsSection.style.pointerEvents = isJR ? '' : 'none';
  }
  if (atsNote) atsNote.style.display = isJR ? 'none' : 'flex';
}

/* ── Skill tags ── */
var skills = [];
function handleSkillInput(e) {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    var val = e.target.value.trim().replace(/,$/, '');
    if (val && !skills.includes(val)) addSkill(val);
    e.target.value = '';
  } else if (e.key === 'Backspace' && !e.target.value && skills.length) {
    removeSkill(skills[skills.length - 1]);
  }
}
function addSkill(text) {
  if (!text) return;
  skills.push(text);
  var wrap = document.getElementById('skill-tag-wrap');
  var inp  = document.getElementById('skill-input');
  var tag  = document.createElement('span');
  tag.className = 'skill-tag';
  tag.dataset.skill = text;
  var label = document.createTextNode(text);
  var btn   = document.createElement('button');
  btn.type  = 'button';
  btn.setAttribute('aria-label', 'Remove ' + text);
  btn.textContent = '×';
  btn.addEventListener('click', function(){ removeSkill(text); });
  tag.appendChild(label);
  tag.appendChild(btn);
  wrap.insertBefore(tag, inp);
  document.getElementById('skills-hidden').value = skills.join(',');
}
function removeSkill(text) {
  skills = skills.filter(function(s){ return s !== text; });
  var all = document.querySelectorAll('.skill-tag');
  all.forEach(function(t){ if (t.dataset.skill === text) t.remove(); });
  document.getElementById('skills-hidden').value = skills.join(',');
}

/* ── Pre-screening questions ── */
var qCount = 1;
function addQuestion() {
  qCount++;
  var list = document.getElementById('question-list');
  var div  = document.createElement('div');
  div.className = 'question-item';
  div.id = 'q-' + qCount;
  /* Build question DOM safely */
  var hdr = document.createElement('div');
  hdr.className = 'question-item-header';

  var num = document.createElement('span');
  num.className = 'question-num';
  num.textContent = 'Q' + qCount;

  var qInput = document.createElement('input');
  qInput.type = 'text';
  qInput.name = 'q_text[]';
  qInput.placeholder = 'Type your screening question...';
  qInput.style.cssText = 'flex:1;border:none;background:transparent;font-size:.88rem;font-family:Inter,sans-serif;outline:none;color:var(--text)';

  var removeBtn = document.createElement('button');
  removeBtn.type = 'button';
  removeBtn.className = 'question-remove';
  removeBtn.setAttribute('aria-label', 'Remove');
  removeBtn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>';
  removeBtn.addEventListener('click', function(){ removeQuestion(removeBtn); });

  hdr.appendChild(num);
  hdr.appendChild(qInput);
  hdr.appendChild(removeBtn);

  var grid = document.createElement('div');
  grid.className = 'form-grid';
  grid.style.cssText = 'grid-template-columns:1fr 1fr;gap:10px';

  function makeField(lbl, selName, opts, addOnChange) {
    var ff = document.createElement('div'); ff.className = 'form-field';
    var la = document.createElement('label'); la.style.fontSize = '.76rem'; la.textContent = lbl;
    var sel = document.createElement('select'); sel.name = selName;
    opts.forEach(function(o) {
      var op = document.createElement('option');
      if (typeof o === 'object') { op.value = o.v; op.textContent = o.t; }
      else op.textContent = o;
      sel.appendChild(op);
    });
    if (addOnChange) sel.addEventListener('change', function() { toggleMCOptions(this); });
    ff.appendChild(la); ff.appendChild(sel);
    return ff;
  }
  grid.appendChild(makeField('Answer type', 'q_type[]',    ['Yes / No','Short text','Multiple choice'], true));
  grid.appendChild(makeField('Required?',   'q_required[]', [{v:'1',t:'Required'},{v:'0',t:'Optional'}], false));

  /* Multiple-choice options block */
  var mcWrap = document.createElement('div');
  mcWrap.className = 'mc-options-wrap';
  mcWrap.innerHTML =
    '<p class="mc-options-label">Answer options</p>' +
    '<div class="mc-option-rows">' +
      buildMCOptionRow('Option 1') +
      buildMCOptionRow('Option 2') +
    '</div>' +
    '<button type="button" class="mc-add-opt" onclick="addMCOption(this)">' +
      '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>' +
      ' Add option</button>';

  div.appendChild(hdr);
  div.appendChild(grid);
  div.appendChild(mcWrap);
  list.appendChild(div);

  /* Wire the type select in the new question to toggle MC options */
  var typeSelect = grid.querySelector('select[name="q_type[]"]');
  if (typeSelect) typeSelect.addEventListener('change', function() { toggleMCOptions(this); });
  /* Wire remove buttons inside mc-options */
  mcWrap.querySelectorAll('.mc-remove-opt').forEach(function(btn) {
    btn.addEventListener('click', function() { removeMCOption(btn); });
  });
}
function removeQuestion(btn) {
  btn.closest('.question-item').remove();
}

/* ── Multiple choice helpers ── */
function buildMCOptionRow(placeholder) {
  return '<div class="mc-option-row">' +
    '<input type="text" name="q_options[]" placeholder="' + placeholder + '" aria-label="' + placeholder + '">' +
    '<button type="button" class="mc-remove-opt" onclick="removeMCOption(this)" aria-label="Remove option">' +
      '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>' +
    '</button></div>';
}

function toggleMCOptions(select) {
  var wrap = select.closest('.question-item').querySelector('.mc-options-wrap');
  if (!wrap) return;
  var isMultiple = select.value === 'Multiple choice' || select.value === 'multiple_choice';
  wrap.classList.toggle('visible', isMultiple);
}

function addMCOption(btn) {
  var rows = btn.previousElementSibling; /* .mc-option-rows */
  if (rows.children.length >= 6) return; /* max 6 options */
  var n = rows.children.length + 1;
  rows.insertAdjacentHTML('beforeend', buildMCOptionRow('Option ' + n));
  /* Focus the new input */
  rows.lastElementChild.querySelector('input').focus();
}

function removeMCOption(btn) {
  var rows = btn.closest('.mc-option-rows');
  if (!rows || rows.children.length <= 2) return; /* keep minimum 2 */
  btn.closest('.mc-option-row').remove();
}

/* ── Urgently Hiring — plan required, same redirect pattern ── */
function handleUrgentToggle(cb) {
  if (!cb.checked) return;
  var hasPlan = (cb.dataset.hasPlan === '1');
  if (!hasPlan) {
    cb.checked = false;
    if (confirm('Urgently Hiring requires an active plan.\n\nWould you like to view our plans?')) {
      window.location.href = '/employer/plans?ref=urgent-hiring';
    }
  }
}

/* ── Featured listing — redirects to pricing if no active plan ── */
function handleFeaturedToggle(cb) {
  if (!cb.checked) return; /* unchecking is always fine */
  /*
    Dev: replace HAS_ACTIVE_PLAN with a server-rendered boolean,
    e.g. PHP: var HAS_ACTIVE_PLAN = <?= $employer->has_featured_plan ? 'true' : 'false' ?>;
    Or pass via a data attribute on the checkbox: cb.dataset.hasPlan === '1'
  */
  var hasPlan = (cb.dataset.hasPlan === '1'); /* set data-has-plan="1" server-side when plan is active */
  if (!hasPlan) {
    cb.checked = false; /* revert the toggle */
    if (confirm('Featured Listing requires an active plan.\n\nWould you like to view our plans?')) {
      window.location.href = '/employer/plans?ref=featured-listing';
    }
  }
}

/* ── Anonymous posting ── */
function handleAnonToggle(cb) {
  var note = document.getElementById('anon-note');
  if (note) note.style.display = cb.checked ? 'flex' : 'none';
}



/* ── Form submit ── */
function publishJob(e) {
  e.preventDefault();
  var title = document.getElementById('job-title');
  if (!title || !title.value.trim()) { title.focus(); toastr.error('Please enter a job title.'); return; }
  /* Dev: POST /employer/jobs with FormData */
  toastr.success('Job posted! (backend integration pending)');
}
function saveDraft() {
  /* Dev: POST /employer/jobs/draft */
  var btn = event.target;
  var orig = btn.innerHTML;
  btn.innerHTML = '✓ Draft saved';
  setTimeout(function() { btn.innerHTML = orig; }, 2000);
}

/* ── Hybrid days conditional field ── */
function toggleHybridDays(value) {
  var wrap = document.getElementById('hybrid-days-wrap');
  if (wrap) wrap.style.display = value === 'Hybrid' ? '' : 'none';
  if (value !== 'Hybrid') {
    var sel = document.getElementById('hybrid-days');
    if (sel) sel.selectedIndex = 0;
  }
}

/* ── Age bracket custom field ── */
function toggleAgeCustom(value) {
  var wrap = document.getElementById('age-custom-wrap');
  if (wrap) wrap.style.display = value === 'custom' ? '' : 'none';
}

/* ── Industry → Job Category filter ── */
var INDUSTRY_CATEGORIES = {
  'Accounting / Finance':       ['Accounting & Audit','Finance & Investment','Internal Audit','Tax','Treasury'],
  'Administration / Secretarial':['Administration','Executive Assistant','Office Management','Receptionist','Secretarial'],
  'Agriculture / Agro-Allied':  ['Agriculture','Agronomy','Animal Science','Farm Management','Food Science'],
  'Aviation / Airline':         ['Aviation','Cabin Crew','Ground Operations','Pilot','Aviation Maintenance'],
  'Banking / Financial Services':['Banking Operations','Credit & Risk','Investment Banking','Retail Banking','Trade Finance'],
  'Building / Construction':    ['Architecture','Civil Engineering','Electrical Engineering','Estate Management','Quantity Surveying','Site Management'],
  'Consulting':                 ['Business Consulting','IT Consulting','Management Consulting','Strategy'],
  'Education / Training':       ['Academic Research','Curriculum Development','Education & Training','Lecturing / Teaching','School Administration'],
  'Energy / Power / Utilities': ['Electrical Engineering','Energy Management','Gas & Power','Renewable Energy','Utilities'],
  'Engineering':                ['Chemical Engineering','Civil Engineering','Electrical Engineering','Mechanical Engineering','Petroleum Engineering'],
  'Entertainment / Media':      ['Broadcast','Content Creation','Journalism','Music & Arts','Public Relations','Video Production'],
  'FMCG / Manufacturing':       ['Brand Management','Manufacturing','Production','Quality Assurance','Supply Chain'],
  'Government / Public Sector': ['Civil Service','Compliance & Regulatory','Government Affairs','Policy & Planning','Public Administration'],
  'Healthcare / Pharmaceutical':['Clinical Research','Medical / Healthcare','Nursing','Pharmacy','Public Health'],
  'Hotels & Restaurants':       ['Catering / Chef','Front Desk / Hospitality','Food & Beverage','Hotel Management','Restaurant Operations'],
  'ICT / Telecommunications':   ['Cloud & DevOps','Cybersecurity','Data / Analytics','ICT / Software','Mobile Development','Networking','Product Management','Technical Support','UI/UX Design'],
  'Insurance':                  ['Actuarial','Claims & Underwriting','Insurance Operations','Risk Management'],
  'Logistics / Transportation': ['Courier & Delivery','Fleet Management','Logistics & Supply Chain','Procurement','Warehousing'],
  'Marketing / Advertising':    ['Brand Management','Content Marketing','Digital Marketing','Market Research','Marketing & Communications','Social Media'],
  'NGO / Non-profit':           ['Community Development','Fundraising','Humanitarian Aid','NGO Program Management','Social Work'],
  'Oil & Gas / Energy':         ['Drilling & Production','HSE (Health Safety Environment)','Petroleum Engineering','Reservoir Engineering','Subsea'],
  'Real Estate / Property':     ['Architecture','Estate Agency','Estate Management','Property Development','Quantity Surveying'],
  'Retail / Wholesale':         ['Customer Service','Merchandising','Retail Management','Sales','Visual Merchandising'],
  'Security':                   ['Intelligence & Investigation','Physical Security','Security Management','Surveillance'],
};
var ALL_CATEGORIES = ['Accounting & Audit','Administration','Agriculture','Architecture','Aviation','Banking Operations','Brand Management','Business Consulting','Business Development','Chemical Engineering','Civil Engineering','Clinical Research','Cloud & DevOps','Community Development','Content Creation','Content Marketing','Customer Service','Cybersecurity','Data / Analytics','Digital Marketing','Drilling & Production','Education & Training','Electrical Engineering','Energy Management','Estate Management','Executive Assistant','Finance & Investment','Fleet Management','Food Science','Government Affairs','Graduate Trainee','Health & Safety','Hotel Management','HSE','Human Resources','ICT / Software','Insurance Operations','Internal Audit','Investment Banking','Journalism','Law / Legal','Lecturing / Teaching','Logistics & Supply Chain','Management Consulting','Manufacturing','Market Research','Marketing & Communications','Mechanical Engineering','Medical / Healthcare','Mobile Development','Networking','NGO Program Management','Nursing','Office Management','Petroleum Engineering','Pharmacy','Product Management','Procurement','Production','Project Management','Public Health','Public Relations','Quality Assurance','Quantity Surveying','Research & Development','Restaurant Operations','Retail Management','Risk Management','Sales','School Administration','Security Management','Social Media','Social Work','Strategy','Supply Chain','Technical Support','Treasury','UI/UX Design','Video Production','Warehousing','Other'];

function updateJobCategories(industryValue) {
  var sel = document.getElementById('job-category');
  if (!sel) return;
  var cats = INDUSTRY_CATEGORIES[industryValue] || ALL_CATEGORIES;
  var prev = sel.value;
  sel.innerHTML = '<option value="">Select category</option>';
  cats.forEach(function(cat) {
    var opt = document.createElement('option');
    opt.value = cat; opt.textContent = cat;
    if (cat === prev) opt.selected = true;
    sel.appendChild(opt);
  });
}

/* ── Publish bar: auto-hide on scroll down (mobile), show on scroll up ──
   Behaviour:
   • Scrolling DOWN through the form  → bar slides out of view (frees screen space)
   • Scrolling UP / pausing           → bar slides back in
   • Near page bottom (last 200px)    → always visible (time to act)
   Desktop: bar is always visible (enough screen real estate).
── */
(function() {
  var bar       = document.querySelector('.publish-bar');
  var lastY     = 0;
  var threshold = 40;  /* px scrolled before hiding — prevents jitter */
  var isMobile  = function() { return window.innerWidth <= 640; };

  function update() {
    if (!bar || !isMobile()) { bar && bar.classList.remove('bar-hidden'); return; }
    var y          = window.scrollY;
    var pageBottom = document.documentElement.scrollHeight - window.innerHeight;
    var nearBottom = pageBottom - y < 200;

    if (nearBottom) {
      bar.classList.remove('bar-hidden');            /* always show near bottom */
    } else if (y > lastY + threshold) {
      bar.classList.add('bar-hidden');               /* scrolling down → hide */
    } else if (y < lastY - threshold) {
      bar.classList.remove('bar-hidden');            /* scrolling up → show */
    }
    lastY = y;
  }

  window.addEventListener('scroll', update, { passive: true });
  window.addEventListener('resize', update, { passive: true });
})();

document.addEventListener('DOMContentLoaded', function() {
  var checked = document.querySelector('input[name="app_method"]:checked');
  if (checked) toggleMethodDetail(checked.value);
  /* Wire type selects in pre-existing static questions */
  document.querySelectorAll('#question-list select[name="q_type[]"]').forEach(function(sel) {
    sel.addEventListener('change', function() { toggleMCOptions(this); });
  });
});


// Additional JS to bridge the template interactions with our PHP form inputs
function toggleMethodDetail(val) {
  var detailDiv = document.getElementById('method-detail');
  var w = document.getElementById('method_whatsapp_input');
  var e = document.getElementById('method_email_input');
  var ext = document.getElementById('method_external_input');
  var note = document.getElementById('ats-unavailable-note');
  var atsSection = document.getElementById('ats-section');
  
  if(w) w.style.display = 'none';
  if(e) e.style.display = 'none';
  if(ext) ext.style.display = 'none';
  
  if (val === 'jobberrecruit' || val === 'form') {
    detailDiv.style.display = 'none';
    note.style.display = 'none';
    atsSection.style.display = 'block';
  } else {
    detailDiv.style.display = 'block';
    note.style.display = 'flex';
    atsSection.style.display = 'none';
    
    if(val === 'whatsapp') w.style.display = 'block';
    if(val === 'email') e.style.display = 'block';
    if(val === 'external') ext.style.display = 'block';
  }
}

// Initialize Method Toggle on Load
document.addEventListener('DOMContentLoaded', function() {
    const selectedMethod = document.querySelector('input[name="application_method"]:checked');
    if (selectedMethod) {
        toggleMethodDetail(selectedMethod.value);
    }
    
    // Select2 Initialization
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2').select2();
    }
    
    // CKEditor Initialization
    if (document.querySelector('#job-desc')) {
        ClassicEditor
            .create(document.querySelector('#job-desc'), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ]
            })
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    const data = editor.getData();
                    document.querySelector('#job-desc').value = data;
                    
                    // Simple text-based character count (strips HTML tags)
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data;
                    const text = tempDiv.textContent || tempDiv.innerText || '';
                    const countEl = document.getElementById('desc-count');
                    if(countEl) countEl.textContent = text.length.toLocaleString();
                });
            })
            .catch(error => {
                console.error(error);
            });
    }
});
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<?= $this->endSection() ?>
