<?php $page_title = 'Application Details'; ?>
<?= $this->extend('layouts/employer') ?>

<?php
$fullName = trim(($application->first_name ?? '') . ' ' . ($application->last_name ?? ''));
if (empty($fullName) && !empty($jobSeeker->full_name)) {
    $fullName = $jobSeeker->full_name;
} elseif (empty($fullName) && !empty($applicant->first_name)) {
    $fullName = trim(($applicant->first_name ?? '') . ' ' . ($applicant->last_name ?? ''));
}
if (empty($fullName)) {
    $fullName = 'Guest Applicant';
}

$initials = '';
$parts = explode(' ', $fullName);
$initials = strtoupper(substr($parts[0] ?? 'G', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));

$appliedDate = !empty($application->created_at) ? date('d M Y, H:i', strtotime($application->created_at)) : 'N/A';

// Candidate Title
$candidateTitle = !empty($jobSeeker->job_title) ? $jobSeeker->job_title : (!empty($application->current_company) ? $application->current_company : (!empty($application->job_title) ? 'Applicant for ' . $application->job_title : 'Candidate'));

// Candidate Location Resolution
$candCity = !empty($jobSeeker->city) ? trim($jobSeeker->city) : '';
$candState = !empty($jobSeeker->state_name) ? trim($jobSeeker->state_name) : (!empty($jobSeeker->location) ? trim($jobSeeker->location) : (!empty($application->location) ? trim($application->location) : ''));
if ($candCity && $candState) {
    $candLocation = $candCity . ', ' . $candState;
} elseif ($candState) {
    $candLocation = $candState;
} elseif ($candCity) {
    $candLocation = $candCity;
} else {
    $candLocation = 'Location not specified';
}

// Job Location & Type
$jobStateName = !empty($job->job_state_name) ? trim($job->job_state_name) : (!empty($job->state_name) ? trim($job->state_name) : (!empty($job->location) ? trim($job->location) : 'Nigeria'));
$jobLocationType = !empty($job->location_type) ? strtolower(trim($job->location_type)) : 'on-site';

// Location Match Analysis for Recruiters
$isRemote = in_array($jobLocationType, ['remote', 'work from home', 'telecommute']);
$isSameState = false;
if (!$isRemote && !empty($candState) && !empty($jobStateName) && $candLocation !== 'Location not specified') {
    $cStateNorm = strtolower(preg_replace('/[^a-z0-9]/', '', $candState));
    $jStateNorm = strtolower(preg_replace('/[^a-z0-9]/', '', $jobStateName));
    if (strpos($cStateNorm, $jStateNorm) !== false || strpos($jStateNorm, $cStateNorm) !== false) {
        $isSameState = true;
    }
}

// Suitability status styling
if ($isRemote) {
    $locTheme = '#059669'; // Green
    $locBg = '#ecfdf5';
    $locBorder = '#a7f3d0';
    $locBadge = '✓ 100% Location Suitable (Remote Role)';
    $locAssessment = 'This role offers <strong>Remote</strong> work flexibility. Candidate’s location in <strong>' . esc($candLocation) . '</strong> satisfies all location requirements with no physical commute needed.';
} elseif ($isSameState) {
    $locTheme = '#059669'; // Green
    $locBg = '#ecfdf5';
    $locBorder = '#a7f3d0';
    $locBadge = '✓ Location Match (Same State)';
    $locAssessment = 'Candidate is based in <strong>' . esc($candLocation) . '</strong>, matching the job location (<strong>' . esc($jobStateName) . '</strong>). Optimal for on-site attendance or hybrid schedules.';
} elseif ($candLocation !== 'Location not specified') {
    $locTheme = '#d97706'; // Amber
    $locBg = '#fffbeb';
    $locBorder = '#fde68a';
    $locBadge = '⚠ Relocation / Commute Consideration';
    $locAssessment = 'Candidate is currently located in <strong>' . esc($candLocation) . '</strong>, while this position is <strong>' . ucfirst(esc($jobLocationType)) . ' in ' . esc($jobStateName) . '</strong>. Consider confirming candidate’s relocation willingness during your initial screening.';
} else {
    $locTheme = '#6b7280'; // Slate
    $locBg = '#f8fafc';
    $locBorder = '#e2e8f0';
    $locBadge = 'ℹ Location Verification Needed';
    $locAssessment = 'Candidate has not specified their current city or state. Verify candidate residency during initial screening.';
}

// Contact info
$candEmail = $application->email ?? $jobSeeker->email ?? $applicant->email ?? 'Not specified';
$candPhone = $application->phone ?? $jobSeeker->phone ?? $applicant->phone ?? 'Not specified';
$candPhoneClean = preg_replace('/[^0-9]/', '', (string)$candPhone);
if (strlen($candPhoneClean) === 11 && substr($candPhoneClean, 0, 1) === '0') {
    $candPhoneClean = '234' . substr($candPhoneClean, 1);
}

// Experience & Qualifications
$candExp = $application->experience ?? $jobSeeker->experience_years ?? 'Not specified';
if (is_numeric($candExp)) {
    $candExp = $candExp . ($candExp == 1 ? ' year' : ' years');
}
$candEdu = $application->education ?? $jobSeeker->education_level ?? 'Not specified';

// CV Path
$cvPath = !empty($application->cv_path) ? $application->cv_path : (!empty($jobSeeker->resume) ? $jobSeeker->resume : null);

// Salary Expectation & Availability & Work Eligibility
$candSalary = !empty($application->salary_expectation) ? $application->salary_expectation : (!empty($jobSeeker->desired_salary) ? '₦' . number_format((float)$jobSeeker->desired_salary) : 'Negotiable');
$candAvailability = !empty($application->availability) ? ucfirst(str_replace('_', ' ', $application->availability)) : (!empty($jobSeeker->availability) ? ucfirst(str_replace('_', ' ', $jobSeeker->availability)) : 'Not specified');
$candEligibility = !empty($application->work_eligibility) ? (strtolower($application->work_eligibility) === 'yes' ? 'Eligible to work in Nigeria' : (strtolower($application->work_eligibility) === 'no' ? 'Requires visa / sponsorship' : ucfirst($application->work_eligibility))) : 'Eligible to work';
?>

<?= $this->section('content') ?>
<div class="page-head">
  <div class="page-head-left">
    <h1><svg aria-hidden="true"><use href="#i-doc"/></svg> Application Details</h1>
    <p>Review candidate qualifications, location suitability, experience, and application information.</p>
  </div>
  <div class="page-actions">
    <a href="<?= base_url('employer/applications') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to Applications
    </a>
  </div>
</div>

<div class="detail-grid">
  <div style="display:flex;flex-direction:column;gap:clamp(14px,1.8vw,20px)">

    <!-- Prominent Location Suitability Assessment Card -->
    <section class="card" aria-label="Candidate Location Suitability" style="border: 1.5px solid <?= $locBorder ?>; box-shadow: 0 4px 18px rgba(0,0,0,0.04); overflow: hidden;">
      <div style="height: 4px; background: <?= $locTheme ?>; width: 100%;"></div>
      <div class="card-body" style="padding: clamp(16px, 2vw, 22px);">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: <?= $locBg ?>; color: <?= $locTheme ?>; display: inline-flex; align-items: center; justify-content: center;">
              <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
              <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--brand-deep, #0a2f57);">
                Location Suitability &amp; Screening
              </h3>
              <p style="margin: 2px 0 0; font-size: 0.8rem; color: var(--muted, #64748b);">
                Compare candidate location with job work arrangement
              </p>
            </div>
          </div>
          <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; background: <?= $locBg ?>; color: <?= $locTheme ?>; border: 1px solid <?= $locBorder ?>;">
            <?= $locBadge ?>
          </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px; padding: 14px 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
          <div>
            <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px; margin-bottom: 4px;">
              Candidate's Location
            </div>
            <div style="font-size: 1rem; font-weight: 800; color: var(--brand-deep, #0a2f57); display: flex; align-items: center; gap: 6px;">
              <svg style="width: 17px; height: 17px; color: <?= $locTheme ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <?= esc($candLocation) ?>
            </div>
          </div>
          <div>
            <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px; margin-bottom: 4px;">
              Job Work Location &amp; Mode
            </div>
            <div style="font-size: 1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
              <svg style="width: 17px; height: 17px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              <?= esc($jobStateName) ?> &bull; <?= ucfirst(esc($jobLocationType)) ?>
            </div>
          </div>
        </div>

        <p style="margin: 12px 0 0; font-size: 0.88rem; color: #334155; line-height: 1.55;">
          <?= $locAssessment ?>
        </p>
      </div>
    </section>

    <!-- Candidate Core Profile Card -->
    <section class="card" aria-label="Candidate information">
      <div class="card-body">
        <div class="cand-head">
          <span class="ava" aria-hidden="true"><?= esc($initials) ?></span>
          <div style="flex:1;">
            <h2>
              <?= esc($fullName) ?>
              <?php if (!empty($jobSeeker->is_verified)): ?>
                <span class="pill pill--reviewed" style="background:#e0f2fe; color:#0369a1; border-color:#bae6fd; margin-left: 6px;" title="Verified Candidate Profile">✓ Verified</span>
              <?php endif; ?>
              <?php if (!empty($application->is_guest)): ?>
                <span class="pill pill--closed" style="margin-left: 6px;">Guest Applicant</span>
              <?php endif; ?>
            </h2>
            <div style="font-size:0.96rem; font-weight:700; color:var(--brand); margin-top:2px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
              <span><?= esc($candidateTitle) ?></span>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:8px;">
              <span class="pill" style="background:#f1f5f9; color:#334155; font-weight:600; display:inline-flex; align-items:center; gap:5px;">
                <svg style="width:13px;height:13px;color:<?= $locTheme ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <?= esc($candLocation) ?>
              </span>
              <span class="pill" style="background:#f1f5f9; color:#334155; font-weight:600; display:inline-flex; align-items:center; gap:5px;">
                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <?= esc($candExp) ?> Experience
              </span>
              <span class="pill" style="background:#f1f5f9; color:#334155; font-weight:600; display:inline-flex; align-items:center; gap:5px;">
                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                <?= esc($candEdu) ?>
              </span>
            </div>
            <p style="margin-top:6px; font-size:0.82rem; color:var(--muted);">Applied for <b><?= esc($application->job_title ?? '') ?></b> &middot; <?= esc($appliedDate) ?></p>
          </div>
          <div class="status-select" style="display:flex; align-items:flex-end; gap:10px;">
            <div>
              <label class="lbl" for="status" style="margin:0">Status</label>
              <select class="select" id="status" aria-label="Application status">
                <option value="pending" <?= ($application->status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="reviewed" <?= ($application->status ?? '') === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                <option value="shortlisted" <?= ($application->status ?? '') === 'shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                <option value="rejected" <?= ($application->status ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                <option value="hired" <?= ($application->status ?? '') === 'hired' ? 'selected' : '' ?>>Hired</option>
              </select>
            </div>
            <button type="button" class="emp-btn emp-btn-primary emp-btn-sm open-apt-invite-btn" data-id="<?= $application->id ?>" style="display:inline-flex; align-items:center; gap:6px; height:38px;">
              <svg aria-hidden="true" style="width:15px;height:15px;"><use href="#i-bulb"/></svg> Invite to Aptitude Test
            </button>
          </div>
        </div>

        <!-- Key Recruiter Data Points Grid -->
        <div class="info-grid" style="margin-top:20px;">
          <div>
            <div class="info-lbl"><svg style="width:14px;height:14px;vertical-align:-2px;margin-right:3px" aria-hidden="true"><use href="#i-globe"/></svg> Candidate Location</div>
            <div class="info-val" style="font-weight:700; color:var(--brand-deep)"><?= esc($candLocation) ?></div>
          </div>
          <div>
            <div class="info-lbl">Current Professional Title</div>
            <div class="info-val" style="font-weight:600; color:var(--text);"><?= esc($candidateTitle) ?></div>
          </div>
          <div>
            <div class="info-lbl">Total Work Experience</div>
            <div class="info-val"><?= esc($candExp) ?></div>
          </div>
          <div>
            <div class="info-lbl">Highest Qualification</div>
            <div class="info-val"><?= esc($candEdu) ?></div>
          </div>
          <div>
            <div class="info-lbl">Email Address</div>
            <div class="info-val"><a href="mailto:<?= esc($candEmail) ?>"><?= esc($candEmail) ?></a></div>
          </div>
          <div>
            <div class="info-lbl">Phone Number</div>
            <div class="info-val">
              <a href="tel:<?= esc($candPhone) ?>"><?= esc($candPhone) ?></a>
              <?php if (!empty($candPhoneClean)): ?>
                &middot; <a href="https://wa.me/<?= esc($candPhoneClean) ?>" target="_blank" rel="noopener" style="color:#16a34a; font-weight:600; font-size:0.8rem; text-decoration:none;">WhatsApp</a>
              <?php endif; ?>
            </div>
          </div>
          <div>
            <div class="info-lbl">Salary Expectation</div>
            <div class="info-val" style="font-weight:600; color:#059669;"><?= esc($candSalary) ?></div>
          </div>
          <div>
            <div class="info-lbl">Availability / Start Date</div>
            <div class="info-val"><?= esc($candAvailability) ?></div>
          </div>
          <div>
            <div class="info-lbl">Work Eligibility</div>
            <div class="info-val"><?= esc($candEligibility) ?></div>
          </div>
          <?php if (!empty($jobSeeker->portfolio)): ?>
            <div>
              <div class="info-lbl">Portfolio / Website</div>
              <div class="info-val"><a href="<?= esc($jobSeeker->portfolio) ?>" target="_blank" rel="noopener" style="word-break:break-all;"><?= esc($jobSeeker->portfolio) ?> &nearr;</a></div>
            </div>
          <?php endif; ?>
        </div>

        <?php 
        $skills_list = [];
        if (!empty($application->skills)) {
            $skills_list = array_filter(array_map('trim', explode(',', $application->skills)));
        } elseif (!empty($jobSeeker->skills)) {
            $skills_list = array_filter(array_map('trim', explode(',', $jobSeeker->skills)));
        } elseif (!empty($resume) && !empty($resume->skills)) {
            if (is_array($resume->skills)) {
                $skills_list = $resume->skills;
            } else {
                $skills_list = array_filter(array_map('trim', explode(',', $resume->skills)));
            }
        }
        if (!empty($skills_list)):
        ?>
          <div style="margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--border);">
            <div class="info-lbl" style="font-weight:700; color:var(--brand-deep); margin-bottom:8px;">
              Skills &amp; Competencies (<?= count($skills_list) ?>)
            </div>
            <div class="chips" style="gap:8px;">
              <?php foreach ($skills_list as $sk): ?>
                <?php $sk_val = is_object($sk) ? ($sk->skill_name ?? '') : $sk; ?>
                <?php if (!empty($sk_val)): ?>
                  <span class="chip" style="font-size:0.85rem; padding:5px 12px; background:var(--brand-light, #eff6ff); color:var(--brand, #0d6efd); border-color:#bfdbfe; font-weight:600;"><?= esc($sk_val) ?></span>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Professional Summary & Bio (if available) -->
    <?php if (!empty($jobSeeker->bio)): ?>
      <section class="card" aria-label="Professional Summary">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-user-check"/></svg> Professional Summary &amp; Bio
          </span>
        </div>
        <div class="card-body">
          <p style="font-size:0.92rem; line-height:1.65; color:var(--text); margin:0;">
            <?= nl2br(esc($jobSeeker->bio)) ?>
          </p>
        </div>
      </section>
    <?php endif; ?>

    <!-- Pre-screening Answers (ATS) -->
    <?php if (!empty($answers)): ?>
      <section class="card" aria-label="Pre-screening Questionnaire Answers">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-bulb"/></svg> Pre-screening Answers
          </span>
        </div>
        <div class="card-body">
          <?php foreach ($answers as $ans): ?>
            <div style="margin-bottom: 20px; &:last-child { margin-bottom: 0; }">
              <div class="info-lbl"><?= esc($ans->question) ?></div>
              <div style="padding: 12px; background: var(--bg); border-left: 4px solid var(--brand); border-radius: 6px; margin-top: 6px;">
                <p style="font-weight: 600; margin: 0; font-size: 0.88rem;">
                  <?php if (($ans->type ?? '') === 'checkbox'): ?>
                    <?php 
                      $vals = explode(', ', $ans->answer);
                      foreach($vals as $v):
                    ?>
                      <span class="chip" style="background: var(--brand-light); color: var(--brand); border-color: var(--brand-light); display: inline-block; margin-right: 4px; margin-bottom: 4px;"><?= esc($v) ?></span>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <?= nl2br(esc($ans->answer)) ?>
                  <?php endif; ?>
                </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Cover Letter / Message -->
    <?php if (!empty($application->cover_letter)): ?>
      <section class="card" aria-label="Cover letter">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-mail"/></svg> Cover Letter / Message
          </span>
        </div>
        <div class="card-body">
          <p class="cover"><?= nl2br(esc($application->cover_letter)) ?></p>
        </div>
      </section>
    <?php endif; ?>

    <!-- Detailed Experience List (if available) -->
    <?php if (!empty($experience) && is_iterable($experience)): ?>
      <section class="card" aria-label="Work Experience">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-briefcase"/></svg> Detailed Work Experience
          </span>
        </div>
        <div class="card-body" style="display:flex; flex-direction:column; gap:16px;">
          <?php foreach ($experience as $exp): ?>
            <div style="border-bottom: 1px solid var(--border); padding-bottom: 14px; &:last-child { border-bottom: none; padding-bottom: 0; }">
              <h4 style="font-weight:700; color:var(--brand-deep); font-size:0.94rem; margin-bottom:2px;"><?= esc($exp->job_title ?? 'Position') ?></h4>
              <p style="font-size:0.8rem; color:var(--muted); margin-bottom:8px;">
                <b><?= esc($exp->company ?? 'Company') ?></b><?= !empty($exp->location) ? ' &middot; ' . esc($exp->location) : '' ?> &middot;
                <?= !empty($exp->start_date) ? date('M Y', strtotime($exp->start_date)) : '' ?> &ndash;
                <?= !empty($exp->is_current) ? 'Present' : (!empty($exp->end_date) ? date('M Y', strtotime($exp->end_date)) : '') ?>
              </p>
              <?php if (!empty($exp->description)): ?>
                <p style="font-size:0.84rem; color:var(--text);"><?= nl2br(esc($exp->description)) ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Detailed Education List (if available) -->
    <?php if (!empty($education) && is_iterable($education)): ?>
      <section class="card" aria-label="Education History">
        <div class="card-head">
          <span class="card-title">
            <svg aria-hidden="true"><use href="#i-grad"/></svg> Detailed Education
          </span>
        </div>
        <div class="card-body" style="display:flex; flex-direction:column; gap:16px;">
          <?php foreach ($education as $edu): ?>
            <div style="border-bottom: 1px solid var(--border); padding-bottom: 14px; &:last-child { border-bottom: none; padding-bottom: 0; }">
              <h4 style="font-weight:700; color:var(--brand-deep); font-size:0.94rem; margin-bottom:2px;">
                <?= esc($edu->degree ?? 'Degree') ?><?= !empty($edu->field_of_study) ? ' in ' . esc($edu->field_of_study) : '' ?>
              </h4>
              <p style="font-size:0.8rem; color:var(--muted); margin:0;">
                <b><?= esc($edu->school ?? 'Institution') ?></b> &middot;
                <?= esc($edu->start_year ?? '') ?> &ndash; <?= esc($edu->end_year ?? 'Completed') ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Professional Certifications (if available) -->
    <?php if (!empty($certifications) && is_iterable($certifications)): ?>
      <section class="card" aria-label="Certifications">
        <div class="card-head">
          <span class="card-title">
            <svg style="width:16px;height:16px;vertical-align:-2px;margin-right:4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg> Certifications &amp; Licenses
          </span>
        </div>
        <div class="card-body" style="display:flex; flex-direction:column; gap:14px;">
          <?php foreach ($certifications as $cert): ?>
            <div style="border-bottom: 1px solid var(--border); padding-bottom: 12px; &:last-child { border-bottom: none; padding-bottom: 0; }">
              <h4 style="font-weight:700; color:var(--brand-deep); font-size:0.92rem; margin-bottom:2px;">
                <?= esc(is_object($cert) ? ($cert->name ?? $cert->title ?? 'Certificate') : ($cert['name'] ?? $cert['title'] ?? 'Certificate')) ?>
              </h4>
              <p style="font-size:0.8rem; color:var(--muted); margin:0;">
                <b><?= esc(is_object($cert) ? ($cert->issuing_organization ?? $cert->organization ?? '') : ($cert['issuing_organization'] ?? $cert['organization'] ?? '')) ?></b>
                <?php $certYear = is_object($cert) ? ($cert->issue_year ?? $cert->issue_date ?? '') : ($cert['issue_year'] ?? $cert['issue_date'] ?? ''); ?>
                <?= !empty($certYear) ? ' &middot; ' . esc($certYear) : '' ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Professional References (if available) -->
    <?php if (!empty($references) && is_iterable($references)): ?>
      <section class="card" aria-label="Professional References">
        <div class="card-head">
          <span class="card-title">
            <svg style="width:16px;height:16px;vertical-align:-2px;margin-right:4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg> Professional References
          </span>
        </div>
        <div class="card-body" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
          <?php foreach ($references as $ref): ?>
            <div style="padding:12px 14px; border:1px solid var(--border); border-radius:8px; background:var(--bg);">
              <div style="font-weight:700; font-size:0.9rem; color:var(--brand-deep);"><?= esc($ref->name) ?></div>
              <?php if (!empty($ref->title)): ?>
                <div style="font-size:0.82rem; color:var(--muted); margin-top:2px;"><?= esc($ref->title) ?></div>
              <?php endif; ?>
              <?php if (!empty($ref->email)): ?>
                <div style="font-size:0.82rem; margin-top:6px;"><a href="mailto:<?= esc($ref->email) ?>" style="color:var(--brand);"><?= esc($ref->email) ?></a></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Job Applied For Details -->
    <section class="card" aria-label="Job details">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-briefcase"/></svg> Job Applied For
        </span>
        <?php if (!empty($application->job_id)): ?>
          <a href="<?= base_url('jobs/' . $application->job_id) ?>" class="card-link" target="_blank">
            View job post <svg aria-hidden="true"><use href="#i-arrow-r"/></svg>
          </a>
        <?php endif; ?>
      </div>
      <div class="card-body jd">
        <b style="font-family:'Sora',sans-serif;color:var(--brand-deep);font-size:1rem;display:block;margin-bottom:12px;">
          <?= esc($application->job_title ?? '') ?>
        </b>
        <?php if (!empty($application->job_description)): ?>
          <h4>Description</h4>
          <div><?= html_entity_decode(esc($application->job_description)) ?></div>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <div class="sticky-col">
    <!-- Quick Actions -->
    <section class="card" aria-label="Quick actions">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-zap"/></svg> Quick Actions
        </span>
      </div>
      <div class="card-body">
        <div class="qa-stack">
          <button class="emp-btn emp-btn-primary emp-btn-block" data-status="shortlisted"><svg aria-hidden="true"><use href="#i-star"/></svg> Shortlist Candidate</button>
          <button class="emp-btn emp-btn-outline emp-btn-block" data-status="reviewed"><svg aria-hidden="true"><use href="#i-eye"/></svg> Mark as Reviewed</button>
          <button class="emp-btn emp-btn-outline emp-btn-block" data-status="hired"><svg aria-hidden="true"><use href="#i-user-check"/></svg> Mark as Hired</button>
          <button class="emp-btn emp-btn-danger emp-btn-block" data-status="rejected"><svg aria-hidden="true"><use href="#i-x"/></svg> Reject Candidate</button>
        </div>
        <p class="qa-note">The candidate is notified by email when the status changes.</p>
        <hr style="border:none;border-top:1px solid var(--border);margin:14px 0">
        <div class="qa-stack">
          <a href="mailto:<?= esc($candEmail) ?>" class="emp-btn emp-btn-outline emp-btn-block emp-btn-sm"><svg aria-hidden="true"><use href="#i-mail"/></svg> Send Email</a>
          <?php if (!empty($cvPath)): ?>
            <a href="<?= base_url($cvPath) ?>" target="_blank" rel="noopener" class="emp-btn emp-btn-primary emp-btn-block emp-btn-sm" style="display:inline-flex; align-items:center; justify-content:center; gap:6px;"><svg aria-hidden="true"><use href="#i-eye"/></svg> View / Preview CV</a>
            <a href="<?= base_url($cvPath) ?>" class="emp-btn emp-btn-outline emp-btn-block emp-btn-sm" download style="display:inline-flex; align-items:center; justify-content:center; gap:6px;"><svg aria-hidden="true"><use href="#i-download"/></svg> Download CV/Resume</a>
          <?php endif; ?>
          <?php if (!empty($candPhoneClean)): ?>
            <a href="https://wa.me/<?= esc($candPhoneClean) ?>" target="_blank" rel="noopener" class="emp-btn emp-btn-outline emp-btn-block emp-btn-sm" style="border-color:#22c55e; color:#15803d; font-weight:600; display:inline-flex; align-items:center; justify-content:center; gap:6px;">
              <svg style="width:14px;height:14px;fill:currentColor;" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.77.813 2.796.813 3.179 0 5.766-2.587 5.766-5.767 0-3.18-2.587-5.768-5.766-5.768zm0 10.378c-.896 0-1.637-.253-2.383-.695l-.171-.102-1.764.463.471-1.72-.112-.178c-.487-.775-.745-1.503-.745-2.38 0-2.543 2.069-4.612 4.614-4.612 2.544 0 4.612 2.069 4.612 4.612 0 2.544-2.068 4.612-4.612 4.612z"/></svg> WhatsApp Candidate
            </a>
          <?php endif; ?>
          <?php if (!empty($application->job_seeker_id)): ?>
            <a href="<?= base_url('employer/messages') ?>?candidate=<?= esc($application->job_seeker_id) ?>" class="emp-btn emp-btn-outline emp-btn-block emp-btn-sm"><svg aria-hidden="true"><use href="#i-chat"/></svg> Message Candidate</a>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Notes Card -->
    <section class="card" aria-label="Internal notes">
      <div class="card-head">
        <span class="card-title">
          <svg aria-hidden="true"><use href="#i-note"/></svg> Notes 
          <span class="pill pill--reviewed" id="note-count"><?= count($notes ?? []) ?></span>
        </span>
      </div>
      <div class="card-body">
        <p style="font-size:.78rem;color:var(--muted);text-align:center; <?= !empty($notes) ? 'display:none;' : '' ?>" id="note-empty">
          No notes yet. Notes are only visible to your team.
        </p>
        <div id="note-list" style="display:flex;flex-direction:column;gap:8px;margin-bottom:12px;">
          <?php if (!empty($notes)): ?>
              <?php foreach ($notes as $n): $note = (object) $n; ?>
                <div class="note-item" data-id="<?= $note->id ?>">
                  <div style="font-weight: 500; margin-bottom: 4px;"><?= nl2br(esc($note->note)) ?></div>
                  <div style="font-size: 0.68rem; color: var(--muted); display: flex; justify-content: space-between; align-items: center;">
                    <span>By <?= esc($note->created_by_name ?? 'System') ?> &middot; <?= date('d M, H:i', strtotime($note->created_at)) ?></span>
                    <a href="#" class="delete-note text-danger" data-id="<?= $note->id ?>" style="display: inline-flex; align-items: center;" aria-label="Delete">
                      <svg style="width:12px; height:12px;"><use href="#i-trash"/></svg>
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="note-input">
          <textarea class="input" id="note-text" rows="3" placeholder="Write an internal note…" aria-label="Write a note"></textarea>
          <select id="note-type" class="select" style="min-height:36px; font-size:.8rem; padding: 6px 12px; margin-bottom: 4px;">
            <option value="internal">📝 Internal Note</option>
            <option value="feedback">💬 Feedback</option>
            <option value="reminder">⏰ Reminder</option>
          </select>
          <button class="emp-btn emp-btn-primary emp-btn-sm" id="note-add"><svg aria-hidden="true"><use href="#i-plus"/></svg> Add Note</button>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Modal update screen -->
<div class="modal-scrim" id="modal-scrim">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="modal-head">
      <span class="modal-title" id="modal-title"><svg aria-hidden="true"><use href="#i-x" id="modal-title-icon"/></svg> <span id="modal-title-text">Update Status</span></span>
      <button class="modal-close" id="modal-close" aria-label="Close dialog"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
    </div>
    <div class="modal-body">
      <div class="notice notice--info"><svg aria-hidden="true"><use href="#i-bulb"/></svg>
        <span><b>Note:</b> the candidate will receive an email with your message below.</span></div>
      <label class="lbl" for="modal-msg" style="margin-top:12px; display:block;">Message to candidate <span class="req" aria-hidden="true">*</span></label>
      <textarea class="input" id="modal-msg" rows="6" required aria-describedby="modal-hint"></textarea>
      <p class="modal-hint" id="modal-hint">This message will be included in the email sent to the candidate.</p>
    </div>
    <div class="modal-foot">
      <button class="emp-btn emp-btn-outline" id="modal-cancel">Cancel</button>
      <button class="emp-btn emp-btn-primary" id="modal-send"><svg aria-hidden="true"><use href="#i-send"/></svg> Send &amp; Update Status</button>
    </div>
  </div>
</div>

<div class="toast" id="toast" role="status" aria-live="polite"><svg aria-hidden="true"><use href="#i-check-c"/></svg><span id="toast-text"></span></div>

<!-- Delete Note Confirmation Modal -->
<div class="modal-scrim" id="delete-note-scrim">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="del-note-title">
    <div class="modal-head">
      <span class="modal-title" id="del-note-title"><svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Note</span>
      <button class="modal-close" id="del-note-close" aria-label="Close dialog"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
    </div>
    <div class="modal-body">
      <p style="font-size:0.9rem; color:var(--text); line-height:1.6;">Are you sure you want to delete this internal note? This action cannot be undone.</p>
    </div>
    <div class="modal-foot">
      <button class="emp-btn emp-btn-outline" id="del-note-cancel">Cancel</button>
      <button class="emp-btn emp-btn-danger" id="del-note-confirm"><svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Note</button>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<button class="emp-btn emp-btn-primary" data-status="shortlisted"><svg aria-hidden="true"><use href="#i-star"/></svg> Shortlist</button>
<button class="emp-btn emp-btn-outline" data-status="rejected"><svg aria-hidden="true"><use href="#i-x"/></svg> Reject</button>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/* Notes and status update controllers */
(function(){
  'use strict';

  var noteText = document.getElementById('note-text'),
      noteType = document.getElementById('note-type'),
      noteAdd = document.getElementById('note-add'),
      noteList = document.getElementById('note-list'),
      noteCount = document.getElementById('note-count'),
      noteEmpty = document.getElementById('note-empty');

  // Add Note
  noteAdd.addEventListener('click', function() {
    var val = noteText.value.trim();
    var type = noteType.value;
    if (!val) return;

    noteAdd.disabled = true;
    noteAdd.innerHTML = 'Adding...';

    var data = new URLSearchParams();
    data.append('application_id', '<?= $application->id ?>');
    data.append('note', val);
    data.append('type', type);
    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= site_url("employer/applications/add-note") ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      noteAdd.disabled = false;
      noteAdd.innerHTML = '<svg aria-hidden="true"><use href="#i-plus"/></svg> Add Note';

      if (response.success) {
        showToast('Note added successfully.');
        noteText.value = '';

        var div = document.createElement('div');
        div.className = 'note-item';
        div.setAttribute('data-id', response.note.id);
        div.innerHTML = '<div style="font-weight: 500; margin-bottom: 4px;">' + escapeHtml(response.note.note) + '</div>' +
                        '<div style="font-size: 0.68rem; color: var(--muted); display: flex; justify-content: space-between; align-items: center;">' +
                          '<span>By ' + escapeHtml(response.note.created_by_name || 'System') + ' &middot; ' + response.note.created_at + '</span>' +
                          '<a href="#" class="delete-note text-danger" data-id="' + response.note.id + '" style="display: inline-flex; align-items: center;" aria-label="Delete">' +
                            '<svg style="width:12px; height:12px;"><use href="#i-trash"/></svg>' +
                          '</a>' +
                        '</div>';
        
        noteList.insertBefore(div, noteList.firstChild);
        noteEmpty.style.display = 'none';
        noteCount.textContent = parseInt(noteCount.textContent || 0) + 1;
        attachDeleteHandler(div.querySelector('.delete-note'));
      } else {
        toastr.error(response.message || 'Failed to add note');
      }
    })
    .catch(function(err) {
      noteAdd.disabled = false;
      noteAdd.innerHTML = '<svg aria-hidden="true"><use href="#i-plus"/></svg> Add Note';
      toastr.error('Error connecting to the server');
    });
  });

  // Delete note modal state
  var deleteNoteScrim = document.getElementById('delete-note-scrim'),
      deleteNoteConfirm = document.getElementById('del-note-confirm'),
      deleteNoteCancel = document.getElementById('del-note-cancel'),
      deleteNoteClose = document.getElementById('del-note-close'),
      pendingDeleteId = null;

  function closeDeleteModal() {
    deleteNoteScrim.classList.remove('show');
    document.body.style.overflow = '';
    pendingDeleteId = null;
  }

  function openDeleteModal(noteId) {
    pendingDeleteId = noteId;
    deleteNoteScrim.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  deleteNoteCancel.addEventListener('click', closeDeleteModal);
  deleteNoteClose.addEventListener('click', closeDeleteModal);
  deleteNoteScrim.addEventListener('click', function(e) { if (e.target === deleteNoteScrim) closeDeleteModal(); });

  deleteNoteConfirm.addEventListener('click', function() {
    if (!pendingDeleteId) return;
    var id = pendingDeleteId;
    deleteNoteConfirm.disabled = true;
    deleteNoteConfirm.innerHTML = 'Deleting...';

    var data = new URLSearchParams();
    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= site_url("employer/applications/delete-note") ?>/' + id, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      deleteNoteConfirm.disabled = false;
      deleteNoteConfirm.innerHTML = '<svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Note';
      if (response.success) {
        closeDeleteModal();
        showToast('Note deleted.');
        var item = document.querySelector('.note-item[data-id="' + id + '"]');
        if (item) {
          item.remove();
        }
        var count = Math.max(0, parseInt(noteCount.textContent || 0) - 1);
        noteCount.textContent = count;
        if (count === 0) {
          noteEmpty.style.display = 'block';
        }
      } else {
        toastr.error(response.message || 'Failed to delete note');
      }
    })
    .catch(function() {
      deleteNoteConfirm.disabled = false;
      deleteNoteConfirm.innerHTML = '<svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Note';
      toastr.error('Error connecting to server');
    });
  });

  // Attach delete notes
  function attachDeleteHandler(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var id = btn.getAttribute('data-id');
      if (!id) return;
      openDeleteModal(id);
    });
  }

  document.querySelectorAll('.delete-note').forEach(attachDeleteHandler);

  function escapeHtml(text) {
    if (!text) return '';
    var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
  }

  /* Status Modal Updates */
  var scrim = document.getElementById('modal-scrim'),
      titleT = document.getElementById('modal-title-text'),
      titleWrap = document.querySelector('.modal-title'),
      titleIcon = document.getElementById('modal-title-icon'),
      msg = document.getElementById('modal-msg'),
      closeB = document.getElementById('modal-close'),
      cancelB = document.getElementById('modal-cancel'),
      sendB = document.getElementById('modal-send'),
      statusSel = document.getElementById('status'),
      toast = document.getElementById('toast'),
      toastText = document.getElementById('toast-text'),
      lastFocus = null, current = null;

  var CFG = {
    shortlisted: {
      title: 'Shortlist Candidate',
      icon: '#i-star',
      cls: 't-shortlist',
      tpl: 'Congratulations! After reviewing your application, we are pleased to inform you that you have been shortlisted for the position.\n\nWe will contact you shortly with details of the next stage of the process.'
    },
    reviewed: {
      title: 'Mark as Reviewed',
      icon: '#i-eye',
      cls: 't-reviewed',
      tpl: 'Thank you for applying.\n\nThis is to confirm that your application has been received and reviewed by our team. We will be in touch regarding the next steps.'
    },
    hired: {
      title: 'Mark as Hired',
      icon: '#i-user-check',
      cls: 't-hired',
      tpl: 'Congratulations! We are delighted to inform you that you have been selected for the position.\n\nOur team will contact you shortly with your offer details and onboarding information.'
    },
    rejected: {
      title: 'Reject Candidate',
      icon: '#i-x',
      cls: 't-reject',
      tpl: 'Thank you for your interest in this position.\n\nAfter careful review of all applications, we regret to inform you that we have decided to move forward with other candidates whose qualifications more closely match our current needs.\n\nWe encourage you to apply for future openings that match your skills.'
    }
  };

  function openModal(status) {
    var c = CFG[status.toLowerCase()];
    if (!c) return;
    current = status.toLowerCase();
    lastFocus = document.activeElement;
    titleT.textContent = c.title;
    titleIcon.setAttribute('href', c.icon);
    titleWrap.className = 'modal-title ' + c.cls;
    msg.value = c.tpl;
    scrim.classList.add('show');
    document.body.style.overflow = 'hidden';
    setTimeout(function() { msg.focus(); msg.setSelectionRange(0, 0); }, 60);
  }

  function closeModal() {
    scrim.classList.remove('show');
    document.body.style.overflow = '';
    current = null;
    if (lastFocus) lastFocus.focus();
  }

  function showToast(t) {
    toastText.textContent = t;
    toast.classList.add('show');
    clearTimeout(showToast._t);
    showToast._t = setTimeout(function() { toast.classList.remove('show'); }, 3200);
  }

  /* Quick action buttons listener */
  document.querySelectorAll('[data-status]').forEach(function(b) {
    b.addEventListener('click', function() {
      openModal(b.getAttribute('data-status'));
    });
  });

  /* Status dropdown select listener */
  var prevStatus = statusSel.value;
  statusSel.addEventListener('change', function() {
    if (statusSel.value === 'pending') {
      // Direct status update for pending status since it has no template
      updateStatusDirectly('pending');
      prevStatus = 'pending';
      return;
    }
    openModal(statusSel.value);
    statusSel.value = prevStatus; // Revert select value until confirmed
  });

  function updateStatusDirectly(status) {
    var data = new URLSearchParams();
    data.append('application_id', '<?= $application->id ?>');
    data.append('status', status);
    data.append('message_to_candidate', 'Your application status has been updated.');
    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= site_url("employer/applications/update-status") ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      if (response.success) {
        showToast('Status updated to Pending.');
        setTimeout(function() { location.reload(); }, 1200);
      } else {
        toastr.error(response.message || 'Failed to update status');
      }
    });
  }

  sendB.addEventListener('click', function() {
    var messageVal = msg.value.trim();
    if (!messageVal) {
      msg.focus();
      return;
    }

    sendB.disabled = true;
    sendB.innerHTML = 'Updating...';

    var data = new URLSearchParams();
    data.append('application_id', '<?= $application->id ?>');
    data.append('status', current);
    data.append('message_to_candidate', messageVal);
    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= site_url("employer/applications/update-status") ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      sendB.disabled = false;
      sendB.innerHTML = '<svg aria-hidden="true"><use href="#i-send"/></svg> Send &amp; Update Status';
      if (response.success) {
        statusSel.value = current;
        prevStatus = current;
        showToast('Status updated to ' + current + ' — email sent.');
        closeModal();
        setTimeout(function() { location.reload(); }, 1200);
      } else {
        toastr.error(response.message || 'Failed to update status');
      }
    })
    .catch(function() {
      sendB.disabled = false;
      sendB.innerHTML = '<svg aria-hidden="true"><use href="#i-send"/></svg> Send &amp; Update Status';
      toastr.error('Error updating status');
    });
  });

  cancelB.addEventListener('click', closeModal);
  closeB.addEventListener('click', closeModal);
  scrim.addEventListener('click', function(e) { if (e.target === scrim) closeModal(); });
  document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && scrim.classList.contains('show')) closeModal(); });

  // --- Aptitude Test Invitation Handler ---
  const aptModal = document.getElementById('aptitude-invite-modal');
  const aptForm = document.getElementById('aptitude-invite-form');
  const openAptBtns = document.querySelectorAll('.open-apt-invite-btn');

  function openAptModal(appId) {
    if (!aptModal) return;
    document.getElementById('invite-app-id').value = appId;
    
    // Load available tests via API
    const testSelect = document.getElementById('invite-test-id');
    testSelect.innerHTML = '<option value="">Loading tests...</option>';

    fetch('<?= site_url("employer/aptitude-tests/list") ?>')
      .then(res => res.json())
      .then(data => {
        if (data.success && data.tests.length) {
          testSelect.innerHTML = '<option value="ai_custom" selected>✨ AI Custom Test (Generated by Gemini AI for this Job)</option>' +
            '<optgroup label="Preset Standard Tests">' +
            data.tests.map(t => `<option value="${t.id}">${t.title} (${t.num_questions} questions · ${t.duration_mins} mins)</option>`).join('') +
            '</optgroup>';
        } else {
          testSelect.innerHTML = '<option value="ai_custom" selected>✨ AI Custom Test (Generated by Gemini AI for this Job)</option>';
        }
      })
      .catch(() => {
        testSelect.innerHTML = '<option value="">Error loading tests</option>';
      });

    // Toggle AI options visibility
    testSelect.addEventListener('change', function() {
      const aiOptions = document.getElementById('ai-custom-options');
      if (this.value === 'ai_custom') {
        aiOptions.style.display = 'block';
      } else {
        aiOptions.style.display = 'none';
      }
    });

    setTimeout(() => {
      testSelect.dispatchEvent(new Event('change'));
    }, 500);

    aptModal.classList.add('show');
    aptModal.style.display = 'flex';
  }

  function closeAptModal() {
    if (!aptModal) return;
    aptModal.classList.remove('show');
    aptModal.style.display = 'none';
  }

  openAptBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const appId = this.getAttribute('data-id');
      openAptModal(appId);
    });
  });

  if (aptModal) {
    aptModal.querySelectorAll('.close-modal-btn').forEach(btn => btn.addEventListener('click', closeAptModal));
  }

  if (aptForm) {
    aptForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const submitBtn = document.getElementById('submit-invite-btn');
      submitBtn.disabled = true;
      submitBtn.innerText = 'Sending Invitation...';

      const formData = new FormData(this);
      const csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '<?= csrf_hash() ?>';
      formData.append('<?= csrf_token() ?>', csrf);

      fetch('<?= site_url("employer/applications/invite-test") ?>', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(response => {
        submitBtn.disabled = false;
        submitBtn.innerText = 'Send Test Invitation';
        if (response.success) {
          showToast(response.message || 'Invitation sent successfully!');
          closeAptModal();
          setTimeout(() => location.reload(), 1200);
        } else {
          toastr.error(response.message || 'Failed to send invitation');
        }
      })
      .catch(() => {
        submitBtn.disabled = false;
        submitBtn.innerText = 'Send Test Invitation';
        toastr.error('Connection error sending invitation');
      });
    });
  }
})();
</script>

<!-- Aptitude Test Invitation Modal -->
<div class="modal" id="aptitude-invite-modal" role="dialog" aria-modal="true" aria-labelledby="apt-invite-title" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:var(--radius-lg, 12px); width:92%; max-width:500px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
    <div style="padding:18px 20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; background:#f8f9fa;">
      <h3 id="apt-invite-title" style="font-size:1.05rem; font-weight:700; color:#0d6efd; margin:0; display:flex; align-items:center; gap:8px;">
        <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg> Invite Candidate to Aptitude Test
      </h3>
      <button class="close-modal-btn" type="button" style="background:none; border:none; cursor:pointer; font-size:1.2rem; color:#6c757d;">&times;</button>
    </div>
    <form id="aptitude-invite-form" style="padding:20px;">
      <input type="hidden" name="application_id" id="invite-app-id" value="">
      
      <div style="margin-bottom:15px;">
        <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.9rem;">Select Aptitude Assessment <span style="color:red">*</span></label>
        <select class="select" name="test_id" id="invite-test-id" required style="width:100%; padding:9px 12px; border-radius:6px; border:1px solid #ccc;">
          <option value="">-- Loading tests... --</option>
        </select>
      </div>

      <div style="margin-bottom:15px;">
        <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.9rem;">Completion Deadline</label>
        <select class="select" name="days_to_complete" id="invite-days" style="width:100%; padding:9px 12px; border-radius:6px; border:1px solid #ccc;">
          <option value="3">3 Days</option>
          <option value="5">5 Days</option>
          <option value="7" selected>7 Days (Standard)</option>
          <option value="14">14 Days</option>
          <option value="30">30 Days</option>
        </select>
      </div>

      <div id="ai-custom-options" style="display: none; background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #e9ecef;">
        <h4 style="font-size: 0.95rem; margin-top: 0; margin-bottom: 12px; color: #495057;">✨ AI Test Settings</h4>
        
        <div style="margin-bottom:12px;">
          <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.85rem;">Number of Questions</label>
          <input type="number" name="num_questions" id="invite-num-questions" class="input" min="3" max="50" value="5" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom:12px;">
          <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.85rem;">Duration (Minutes)</label>
          <input type="number" name="duration_mins" id="invite-duration" class="input" min="5" max="120" value="15" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom:0;">
          <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.85rem;">Difficulty Level</label>
          <select name="difficulty" id="invite-difficulty" class="select" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid #ccc;">
            <option value="beginner">Beginner</option>
            <option value="intermediate" selected>Intermediate</option>
            <option value="advanced">Advanced</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:15px;">
        <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.9rem;">Instructions / Message to Candidate (Optional)</label>
        <textarea class="textarea" name="message" id="invite-message" rows="3" style="width:100%; padding:9px 12px; border-radius:6px; border:1px solid #ccc;" placeholder="e.g. Please complete this assessment before your upcoming interview..."></textarea>
      </div>

      <div style="padding-top:12px; border-top:1px solid #eee; display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" class="emp-btn emp-btn-outline emp-btn-sm close-modal-btn" style="padding:8px 16px;">Cancel</button>
        <button type="submit" class="emp-btn emp-btn-primary emp-btn-sm" id="submit-invite-btn" style="background:#0d6efd; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-weight:600; cursor:pointer;">Send Test Invitation</button>
      </div>
    </form>
  </div>
</div>
<?php $this->endSection() ?>