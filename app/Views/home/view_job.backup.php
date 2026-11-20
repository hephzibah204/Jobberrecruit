<?= $this->extend('templates/base') ?>
<?= $this->section('schema') ?>
<?php
include_once APPPATH . 'Views/partials/schema/job_posting.php';
$jobSchema = jobPostingSchema($job, base_url());
$jobSchema['identifier'] = [
    '@type' => 'PropertyValue',
    'name'  => 'JobberRecruit',
    'value' => 'JR-' . $job->id,
];
?>
<script type="application/ld+json">
<?= json_encode($jobSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url()],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Jobs', 'item' => base_url('jobs')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $job->title, 'item' => current_url()],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// ── Application method logic ─────────────────────────────────────
$trackUrl = base_url("job/start-application/{$job->id}");
$defaultLabel = 'Apply Now';
$defaultIcon = 'i-send';

switch ($job->application_method ?? 'form') {
    case 'whatsapp':
        $url = esc($job->whatsapp_link, 'url');
        $label = 'Apply via WhatsApp';
        $icon  = 'i-chat';
        $btnBg = '#25D366';
        $target = '_blank';
        break;
    case 'email':
        $email = esc($job->application_email ?? $job->contact_email);
        $subject = rawurlencode("Application: {$job->title}");
        $url = "mailto:{$email}?subject={$subject}";
        $label = 'Apply via Email';
        $icon  = 'i-mail';
        $btnBg = 'var(--brand)';
        $target = '';
        break;
    case 'external':
        $url = esc($job->external_url, 'url');
        $label = 'Apply on External Site';
        $icon  = 'i-rocket';
        $btnBg = 'var(--accent)';
        $target = '_blank';
        break;
    case 'form':
    default:
        $url = '#apply-form-section';
        $label = $defaultLabel;
        $icon  = $defaultIcon;
        $btnBg = 'var(--brand)';
        $target = '';
        $isInlineForm = true;
        break;
}
$targetAttr = $target ? "target='_blank' rel='noopener'" : '';

$coName  = (!empty($job->anonymous) || !empty($job->is_anonymous)) ? 'Confidential Employer' : esc($job->employer_name);
$coLogo  = (!empty($job->anonymous) || !empty($job->is_anonymous)) ? base_url('images/favicon.png') : $job->company_logo;
$initials = '';
foreach (explode(' ', $coName) as $p) { $initials .= substr($p, 0, 1); }
$initials = strtoupper(substr($initials, 0, 2));
$salary  = esc($job->salary_range ?? 'Negotiable');
$salary  = $salary ?: 'Negotiable';

$methodLabel = ucfirst($job->application_method ?? 'form');
switch ($job->application_method ?? 'form') {
    case 'whatsapp': $methodLabel = 'WhatsApp'; break;
    case 'email':    $methodLabel = 'Email'; break;
    case 'external': $methodLabel = 'External Link'; break;
    default:         $methodLabel = 'Application Form'; break;
}
?>
<main id="main-content">
  <section class="job-detail" itemscope itemtype="https://schema.org/JobPosting">
    <span itemprop="hiringOrganization" itemscope itemtype="https://schema.org/Organization" style="display:none">
      <meta itemprop="name" content="<?= esc($coName) ?>">
      <meta itemprop="url" content="<?= current_url() ?>">
    </span>

    <div class="container">
      <!-- Breadcrumbs -->
      <nav class="breadcrumbs" aria-label="Breadcrumb">
        <a href="<?= base_url() ?>">Home</a>
        <span class="bc-sep" aria-hidden="true">/</span>
        <a href="<?= base_url('jobs') ?>">Jobs</a>
        <span class="bc-sep" aria-hidden="true">/</span>
        <span aria-current="page"><?= esc($job->title) ?></span>
      </nav>

      <div class="job-detail-layout">
        <!-- ═══════════ MAIN COLUMN ═══════════ -->
        <div class="job-detail-main">

          <!-- ── Detail Card (header) ── -->
          <div class="detail-card">
            <div class="detail-head">
              <div class="detail-logo"><?= esc($initials) ?></div>
              <div class="detail-head-body">
                <h1 class="detail-title" itemprop="title"><?= esc($job->title) ?></h1>
                <div class="detail-company">
                  by 
                  <?php if (!empty($job->anonymous) || !empty($job->is_anonymous)): ?>
                    <strong>Confidential Employer</strong>
                  <?php else: ?>
                    <a href="<?= base_url('employer/' . $job->employer_id) ?>"><strong><?= esc($job->employer_name) ?></strong></a>
                    <?php if ($job->show_trust_badge): ?>
                      <button type="button" class="verified-check" aria-label="Verified employer" style="display:inline-flex;align-items:center;gap:4px;background:none;border:none;padding:0;cursor:pointer;color:var(--brand);vertical-align:middle;line-height:0;position:relative">
                        <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="verified-tip" role="tooltip" style="position:absolute;bottom:calc(100% + 8px);left:50%;transform:translateX(-50%);background:#fff;color:#141926;font-size:.72rem;font-weight:600;white-space:nowrap;padding:7px 11px;border-radius:8px;border:1px solid #e2e8f2;box-shadow:0 8px 24px rgba(10,47,87,.16);opacity:0;visibility:hidden;pointer-events:none;transition:.16s;z-index:40">Verified employer</span>
                      </button>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
                <div class="detail-badges">
                  <span class="detail-badge db-type"><?= strtoupper(esc($job->job_type)) ?></span>
                  <?php if ($job->featured): ?>
                    <span class="detail-badge db-featured"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></svg> Featured</span>
                  <?php endif; ?>
                  <?php if (!empty($job->is_verified)): ?>
                    <span class="detail-badge db-verified"><svg width="12" height="12" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg> Verified</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="detail-actions">
              <button id="saveJobBtn" data-job-id="<?= $job->id ?>" class="btn btn-outline btn-sm <?= $isSaved ? 'saved' : '' ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="<?= $isSaved ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                <?= $isSaved ? 'Saved' : 'Save' ?>
              </button>
              <button class="btn btn-outline btn-sm" data-bs-toggle="modal" data-bs-target="#reportJobModal">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M10.3 3.3 3.3 10.3a1 1 0 0 0 0 1.4l7 7a1 1 0 0 0 1.4 0l7-7a1 1 0 0 0 0-1.4l-7-7a1 1 0 0 0-1.4 0z"/><circle cx="12" cy="16" r="1"/><path d="M12 8v5"/></svg>
                Report
              </button>
              <?php if ($job->application_method === 'form'): ?>
                <button id="toggleApplyForm" class="btn btn-primary">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                  <?= $label ?>
                </button>
              <?php else: ?>
                <a href="<?= $url ?>" class="btn btn-primary" <?= $targetAttr ?>>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                  <?= $label ?>
                </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- ── Job Description ── -->
          <div class="detail-card">
            <h2 class="detail-section-title">Job Description</h2>
            <div class="detail-body-text" itemprop="description">
              <?= $job->description ?: '<p>No job description provided.</p>' ?>
            </div>

            <?php if (!empty($job->requirements)): ?>
              <h3 class="detail-section-title" style="margin-top:28px">Requirements</h3>
              <div class="detail-body-text"><?= $job->requirements ?></div>
            <?php endif; ?>

            <?php if (!empty($job->application)): ?>
              <h3 class="detail-section-title" style="margin-top:28px">Application Guidelines</h3>
              <div class="detail-body-text"><?= $job->application ?></div>
            <?php endif; ?>
          </div>

          <?php if ($job->application_method === 'form'): ?>
            <div class="detail-card" id="apply-form-section">
                    ?>

                    <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                        <h6 class="fw-semibold mb-3">Apply for this Position</h6>

                        <?= form_open_multipart(base_url("job/application/{$job->id}"), [
                            'id' => 'jobApplicationForm',
                            'class' => 'needs-validation',
                            'novalidate' => true
                        ], ['job_id' => (string)$job->id]) ?>

                        <!-- Display Flash Error (e.g., reCAPTCHA failure) -->
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= esc(session()->getFlashdata('error')) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Display Validation Errors -->
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- GUEST USER NAME & EMAIL (only if not logged in) -->
                        <?php if (!$user): ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" required
                                        placeholder="John" maxlength="50">
                                    <div class="invalid-feedback">Please enter your first name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" required
                                        placeholder="Doe" maxlength="50">
                                    <div class="invalid-feedback">Please enter your last name.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" required
                                    placeholder="john.doe@example.com" maxlength="100">
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" id="phone" class="form-control" required
                                    placeholder="+234 800 000 0000" pattern="[\+]?[0-9\s\-\(\)]{10,20}">
                                <div class="invalid-feedback">Please enter a valid phone number.</div>
                            </div>

                            <div class="alert alert-info small mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                You’re applying as a guest. <a href="<?= base_url('login') ?>" class="alert-link">Log in</a> or
                                <a href="<?= base_url('register') ?>" class="alert-link">create an account</a> to save your CV and track applications.
                            </div>
                        <?php endif; ?>

                        <!-- COVER LETTER -->
                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">
                                Cover Letter <span class="text-muted">(optional)</span>
                                <span id="charCount" class="text-muted small float-end">0 / 2000</span>
                            </label>
                            <div class="d-flex gap-2 mb-2">
                                <textarea name="cover_letter" id="cover_letter" rows="6" class="form-control flex-grow"
                                    placeholder="Why are you a great fit for this role? Highlight your relevant experience, skills, and enthusiasm for <?= esc($job->title) ?> at <?= esc($job->company_name) ?>."
                                    maxlength="2000"></textarea>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="ai-cover-letter-btn" <?= !auth()->loggedIn() ? 'disabled title="Login required"' : '' ?>>
                                <i class="ti ti-sparkles me-1"></i>Generate with AI
                            </button>
                            <div class="form-text">Tailor your message to the job description. Be concise and professional.</div>
                        </div>

                        <!-- PRE-SCREENING QUESTIONS (ATS) -->
                        <?php if (!empty($questions)): ?>
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3 border-bottom pb-2"><i class="ti ti-clipboard-list me-2"></i>Pre-screening Questions</h6>
                                <p class="text-muted small mb-3">Please answer the following questions as part of your application.</p>
                                <?php foreach ($questions as $q): ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?= esc($q->question_text) ?> <?= $q->is_required ? '<span class="text-danger">*</span>' : '' ?></label>
                                        
                                        <?php if ($q->question_type === 'text'): ?>
                                            <textarea name="answers[<?= $q->id ?>]" class="form-control" rows="2" placeholder="Your answer..." <?= $q->is_required ? 'required' : '' ?>></textarea>
                                        
                                        <?php elseif ($q->question_type === 'yes_no'): ?>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="answers[<?= $q->id ?>]" value="Yes" id="q-<?= $q->id ?>-yes" <?= $q->is_required ? 'required' : '' ?>>
                                                    <label class="form-check-label" for="q-<?= $q->id ?>-yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="answers[<?= $q->id ?>]" value="No" id="q-<?= $q->id ?>-no">
                                                    <label class="form-check-label" for="q-<?= $q->id ?>-no">No</label>
                                                </div>
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
                                            <div class="d-flex flex-column gap-2">
                                                <?php foreach (explode(',', $q->options ?? '') as $option): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="answers[<?= $q->id ?>]" value="<?= trim(esc($option)) ?>" id="q-<?= $q->id ?>-<?= md5(trim($option)) ?>" <?= $q->is_required ? 'required' : '' ?>>
                                                        <label class="form-check-label" for="q-<?= $q->id ?>-<?= md5(trim($option)) ?>"><?= trim(esc($option)) ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                        <?php elseif ($q->question_type === 'checkbox'): ?>
                                            <div class="d-flex flex-column gap-2">
                                                <?php foreach (explode(',', $q->options ?? '') as $option): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="answers[<?= $q->id ?>][]" value="<?= trim(esc($option)) ?>" id="q-<?= $q->id ?>-<?= md5(trim($option)) ?>">
                                                        <label class="form-check-label" for="q-<?= $q->id ?>-<?= md5(trim($option)) ?>"><?= trim(esc($option)) ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                        <?php else: ?>
                                            <textarea name="answers[<?= $q->id ?>]" class="form-control" rows="2" placeholder="Your answer..." <?= $q->is_required ? 'required' : '' ?>></textarea>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- CV UPLOAD SECTION -->

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Attach Your CV <span class="text-danger">*</span></label>

                            <?php if ($hasSavedCv): ?>
                                <!-- Logged in + saved CV -->
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="cv_source" id="use_saved_cv" value="saved" checked>
                                    <label class="form-check-label" for="use_saved_cv">
                                        Use my saved CV
                                        <span class="text-muted small d-block">
                                            <?= esc(basename($savedCvPath)) ?>
                                            <em class="text-success">(Uploaded on <?= date('M j, Y', filemtime(FCPATH . $savedCvPath)) ?>)</em>
                                        </span>
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="cv_source" id="upload_new_cv" value="upload">
                                    <label class="form-check-label" for="upload_new_cv">Upload a new CV</label>
                                </div>

                                <div id="new_cv_container" class="mt-2" style="display: none;">
                                    <input type="file" name="cv_file" id="cv_file" class="form-control" accept=".pdf,.doc,.docx">
                                    <div class="form-text">Max 5 MB – PDF, DOC, DOCX</div>
                                </div>

                            <?php else: ?>
                                <!-- Guest or no saved CV -->
                                <input type="file" name="cv_file" id="cv_file" class="form-control" accept=".pdf,.doc,.docx" required>
                                <div class="form-text">Max 5 MB – PDF, DOC, DOCX</div>
                            <?php endif; ?>
                        </div>

                        <!-- PROFESSIONAL REFERENCES (Optional but encouraged) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Professional References <span class="text-muted">(optional)</span></label>
                            <div id="references-container">
                                <div class="reference-row mb-2 p-3 border rounded bg-light">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <input type="text" name="ref_name[]" class="form-control form-control-sm" placeholder="Full Name">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="ref_title[]" class="form-control form-control-sm" placeholder="Job Title">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="email" name="ref_email[]" class="form-control form-control-sm" placeholder="Email">
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-ref" style="display:none;" aria-label="Delete">
    <i class="bi bi-trash"></i>
</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-reference" class="btn btn-sm btn-outline-secondary mt-1">
                                <i class="bi bi-plus"></i> Add Reference
                            </button>
                            <div class="form-text">Provide at least 2 references if possible.</div>
                        </div>

                        <!-- AVAILABILITY & SALARY EXPECTATIONS -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="availability" class="form-label">When can you start? <span class="text-danger">*</span></label>
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

                            <div class="col-md-6">
                                <label for="salary_expectation" class="form-label">Expected Salary (<?= esc($job->currency ?? 'NGN') ?>) <span class="text-muted">(optional)</span></label>
                                <input type="text" name="salary_expectation" id="salary_expectation" class="form-control"
                                    placeholder="e.g., 500,000 - 700,000">
                                <div class="form-text">Provide a range if possible.</div>
                            </div>
                        </div>

                        <!-- ELIGIBILITY TO WORK -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Eligibility to Work in <?= esc($job->location_country ?? 'Nigeria') ?> <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="work_eligibility" id="eligible_yes" value="yes" required>
                                <label class="form-check-label" for="eligible_yes">
                                    Yes, I am legally authorized to work in <?= esc($job->location_country ?? 'Nigeria') ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="work_eligibility" id="eligible_no" value="no">
                                <label class="form-check-label" for="eligible_no">
                                    No, I would require sponsorship
                                </label>
                            </div>
                            <div class="invalid-feedback">Please confirm your eligibility.</div>
                        </div>

                        <!-- DATA CONSENT & SUBMISSION -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="consent" id="consent" required>
                                <label class="form-check-label" for="consent">
                                    I consent to the processing of my personal data in accordance with the
                                    <a href="<?= base_url('privacy-policy') ?>" target="_blank">Privacy Policy</a>.
                                    I understand my application will be retained for future opportunities unless I opt out.
                                </label>
                            </div>
                            <div class="invalid-feedback">You must agree to the privacy terms.</div>
                        </div>

                        <!-- reCAPTCHA v3 (invisible) -->
                        <?php if (env('recaptcha_site_key')): ?>
                            <div class="g-recaptcha-response-wrapper">
                                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                            </div>
                        <?php endif; ?>

                        <!-- SUBMIT BUTTON -->
                        <button type="submit" id="submitBtn" class="btn btn-primary w-100" disabled>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Submit Application <i class="bi bi-send ms-1"></i>
                        </button>

                        <?= form_close() ?>
            </div>
          <?php endif; ?>

          <!-- ── Share Card ── -->
          <div class="detail-card">
            <h2 class="detail-section-title">Share this job</h2>
            <div class="share-links">
              <button class="share-btn" id="copyLink" title="Copy link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.5 1.5"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.5-1.5"/></svg>
                Copy Link
              </button>
              <a href="https://wa.me/?text=<?= urlencode($job->title . ' — ' . current_url()) ?>" class="share-btn" target="_blank" rel="noopener" title="Share on WhatsApp">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.52 3.449C18.24 1.245 15.24 0 12.05 0 5.495 0 .153 5.341.151 11.893c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.896-5.341 11.898-11.893 0-3.181-1.24-6.171-3.428-8.358v.106z"/></svg>
                WhatsApp
              </a>
              <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($job->title) ?>" class="share-btn" target="_blank" rel="noopener" title="Share on X">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932L18.901 1.153z"/></svg>
                X
              </a>
              <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(current_url()) ?>" class="share-btn" target="_blank" rel="noopener" title="Share on LinkedIn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.137 1.445-2.137 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286z"/></svg>
                LinkedIn
              </a>
              <a href="mailto:?subject=Check out this job: <?= urlencode($job->title) ?>&body=<?= urlencode(current_url()) ?>" class="share-btn" title="Share via Email">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                Email
              </a>
            </div>
          </div>

          <!-- ── Inline Application Form ── -->
          <?php if ($job->application_method === 'form'): ?>
            <div id="applyForm" class="detail-card" style="display:none">
              <h2 class="detail-section-title">Apply for this Job</h2>
              <form id="inlineApplyForm">
                <input type="hidden" name="job_id" value="<?= $job->id ?>">
                <div class="form-group" style="margin-bottom:16px">
                  <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">Full Name <span style="color:#b91c1c">*</span></label>
                  <input type="text" name="full_name" required style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;background:var(--bg);color:var(--text)">
                </div>
                <div class="form-group" style="margin-bottom:16px">
                  <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">Email Address <span style="color:#b91c1c">*</span></label>
                  <input type="email" name="email" required style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;background:var(--bg);color:var(--text)">
                </div>
                <div class="form-group" style="margin-bottom:16px">
                  <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">Phone Number</label>
                  <input type="tel" name="phone" style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;background:var(--bg);color:var(--text)">
                </div>
                <div class="form-group" style="margin-bottom:16px">
                  <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">Resume/CV <span style="color:#b91c1c">*</span></label>
                  <input type="file" name="resume" required accept=".pdf,.doc,.docx,.txt" style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;background:var(--bg);color:var(--text)">
                </div>
                <div class="form-group" style="margin-bottom:16px">
                  <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">Cover Letter (Optional)</label>
                  <textarea name="cover_letter" rows="4" placeholder="Tell us why you're interested in this position..." style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;resize:vertical;background:var(--bg);color:var(--text)"></textarea>
                </div>
                <div class="form-group" style="margin-bottom:20px">
                  <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">How did you hear about this job? <span style="color:#b91c1c">*</span></label>
                  <select name="referral" required style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;background:var(--bg);color:var(--text)">
                    <option value="">Select an option</option>
                    <option value="search">Job Search Website</option>
                    <option value="social">Social Media</option>
                    <option value="friend">Friend/Colleague</option>
                    <option value="company">Company Website</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div class="form-actions" style="display:flex;gap:10px;justify-content:flex-end">
                  <button type="button" id="cancelApply" class="btn btn-outline" style="padding:12px 24px;font-size:.88rem">Cancel</button>
                  <button type="submit" id="submitApply" class="btn btn-primary" style="padding:12px 24px;font-size:.88rem">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                    Submit Application
                  </button>
                </div>
              </form>
            </div>
          <?php endif; ?>

          <!-- ── Related Jobs ── -->
          <?php if (!empty($related_jobs)): ?>
            <div class="detail-card">
              <h2 class="detail-section-title">Related Jobs</h2>
              <div class="related-grid">
                <?php foreach ($related_jobs as $related): ?>
                  <a href="<?= base_url('jobs/' . $related->slug) ?>" class="related-card">
                    <div class="related-title"><?= esc($related->title) ?></div>
                    <div class="related-meta">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                      <?= esc($related->location ?? 'Nigeria') ?>
                    </div>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- ═══════════ SIDEBAR ═══════════ -->
        <aside class="job-detail-side">
          <!-- ── Apply Card ── -->
          <div class="side-card">
            <?php if ($job->application_method === 'form'): ?>
              <button id="toggleApplyFormSide" class="btn btn-primary btn-block">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                <?= $label ?>
              </button>
            <?php else: ?>
              <a href="<?= $url ?>" class="btn btn-primary btn-block" <?= $targetAttr ?> style="justify-content:center;width:100%">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                <?= $label ?>
              </a>
            <?php endif; ?>
          </div>

          <!-- ── Job Overview ── -->
          <div class="side-card">
            <h3 class="side-card-title">Job Overview</h3>
            <ul class="overview-list">
              <li>
                <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                <span class="ov-kv"><span class="ov-k">Posted</span><span class="ov-v"><?= esc($job->formatted_created_at) ?></span></span>
              </li>
              <li>
                <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l2 2"/></svg></span>
                <span class="ov-kv"><span class="ov-k">Status</span><span class="ov-v"><?= esc(ucfirst($job->status)) ?></span></span>
              </li>
              <li>
                <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>
                <span class="ov-kv"><span class="ov-k">Job Type</span><span class="ov-v"><?= esc($job->job_type) ?></span></span>
              </li>
              <li>
                <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></span>
                <span class="ov-kv"><span class="ov-k">Location</span><span class="ov-v"><?= esc($job->location ?? 'Nigeria') ?></span></span>
              </li>
              <li>
                <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                <span class="ov-kv"><span class="ov-k">Level</span><span class="ov-v"><?= ucfirst(esc($job->experience_level ?: 'Not specified')) ?></span></span>
              </li>
              <li>
                <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
                <span class="ov-kv"><span class="ov-k">Salary</span><span class="ov-v"><?= $salary ?></span></span>
              </li>
              <li>
                <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 9 12 4 2 9l10 5 10-5Z"/><path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></svg></span>
                <span class="ov-kv"><span class="ov-k">Education</span><span class="ov-v"><?= esc(ucfirst($job->education_level ?: 'Not specified')) ?></span></span>
              </li>
              <li>
                <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.5 5.5A11 11 0 0 1 18.5 5.5"/></svg></span>
                <span class="ov-kv"><span class="ov-k">Accommodation</span><span class="ov-v"><?= $job->accommodation === 'available' ? 'Available' : 'Not Available' ?></span></span>
              </li>
              <li>
                <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                <span class="ov-kv"><span class="ov-k">Apply Via</span><span class="ov-v"><?= $methodLabel ?></span></span>
              </li>
            </ul>
          </div>

          <!-- ── Company Overview ── -->
          <?php if ($job->anonymous === false): ?>
            <div class="side-card">
              <h3 class="side-card-title">About <?= esc($job->employer_name) ?></h3>
              <ul class="overview-list">
                <li>
                  <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></span>
                  <span class="ov-kv"><span class="ov-k">Location</span><span class="ov-v"><?= esc($job->company_address ?? 'Not provided') ?></span></span>
                </li>
                <li>
                  <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.8 12.8 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.8 12.8 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
                  <span class="ov-kv"><span class="ov-k">Phone</span><span class="ov-v"><?= esc($job->company_phone ?? 'Not provided') ?></span></span>
                </li>
                <li>
                  <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></span>
                  <span class="ov-kv"><span class="ov-k">Email</span><span class="ov-v"><?= esc($job->company_email ?? 'Not provided') ?></span></span>
                </li>
                <?php if ($job->company_website): ?>
                <li>
                  <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18Z"/></svg></span>
                  <span class="ov-kv"><span class="ov-k">Website</span><span class="ov-v"><a href="<?= esc($job->company_website) ?>" target="_blank" rel="noopener" style="color:var(--brand)">Visit →</a></span></span>
                </li>
                <?php endif; ?>
                <li>
                  <span class="ov-ic"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>
                  <span class="ov-kv"><span class="ov-k">Open Positions</span><span class="ov-v"><?= $employer_job_count ?></span></span>
                </li>
              </ul>
            </div>
          <?php endif; ?>
        </aside>
      </div>
    </div>
  </section>
</main>

<!-- ── Report Modal ── -->
<div class="modal fade" id="reportJobModal" tabindex="-1" aria-labelledby="reportJobModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border:none;border-radius:12px;overflow:hidden;box-shadow:0 14px 40px rgba(10,47,87,.16)">
      <div class="modal-header" style="background:var(--accent);color:var(--brand-deep);padding:18px 22px">
        <h5 class="modal-title fw-bold" id="reportJobModalLabel">Report this Job</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="report-job-form">
        <input type="hidden" name="job_id" value="<?= $job->id ?>">
        <div class="modal-body" style="padding:22px">
          <p style="color:var(--muted);font-size:.87rem;margin-bottom:16px">Is there something wrong with this job post? Let us know — your report helps keep JobberRecruit safe.</p>
          <div class="form-group" style="margin-bottom:16px">
            <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">Reason <span style="color:#b91c1c">*</span></label>
            <select name="reason" required style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;background:var(--bg);color:var(--text)">
              <option value="">Select a reason</option>
              <option value="scam">It's a scam or fraudulent</option>
              <option value="offensive">Offensive or inappropriate content</option>
              <option value="misleading">Misleading or inaccurate information</option>
              <option value="expired">Job is already expired/filled</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label style="display:block;font-weight:600;margin-bottom:6px;font-size:.88rem">Additional Details (Optional)</label>
            <textarea name="details" rows="4" placeholder="Provide more context…" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:.9rem;resize:vertical"></textarea>
          </div>
        </div>
        <div class="modal-footer" style="padding:14px 22px;border-top:1px solid var(--border);display:flex;gap:8px;justify-content:flex-end">
          <button type="button" class="btn btn-outline" data-bs-dismiss="modal" style="padding:10px 18px;font-size:.85rem">Cancel</button>
          <button type="submit" class="btn btn-accent" id="btn-submit-report" style="padding:10px 22px;font-size:.85rem">Submit Report</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ── Mobile apply bar ── -->
<div class="mob-apply-bar">
  <div class="container">
    <span class="mob-bar-salary"><?= $salary ?></span>
    <a href="<?= $trackUrl ?>" class="btn btn-primary" <?= $targetAttr ?>>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
      <?= $label ?>
    </a>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* ═══════════════════════════════════════════════════════════════════
   JOB DETAIL PAGE — Brand colors: var(--brand) (blue), var(--accent) (orange)
   ═══════════════════════════════════════════════════════════════════ */

/* ── Breadcrumbs ── */
.breadcrumbs { display:flex;align-items:center;gap:6px;flex-wrap:wrap;padding:20px 0;font-size:.82rem;color:var(--muted); }
.breadcrumbs a { color:var(--muted);text-decoration:none;transition:color .18s; }
.breadcrumbs a:hover { color:var(--brand); }
.bc-sep { color:var(--border);font-size:.72rem; }

/* ── Layout ── */
.job-detail { background:var(--bg);padding-bottom:80px; }
.job-detail-layout { display:grid;grid-template-columns:1fr 332px;gap:24px;align-items:start; }
@media (max-width:860px) { .job-detail-layout { grid-template-columns:1fr; } }
.job-detail-main { display:flex;flex-direction:column;gap:18px; }

/* ── Detail Card ── */
.detail-card { background:var(--white);border:1px solid var(--border);border-radius:14px;padding:28px;box-shadow:0 2px 14px rgba(10,47,87,.06); }

/* ── Detail Head ── */
.detail-head { display:flex;gap:18px;align-items:flex-start;margin-bottom:18px; }
.detail-logo { width:56px;height:56px;border-radius:12px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:700;font-size:1.1rem;flex-shrink:0;border:1px solid var(--border); }
.detail-head-body { min-width:0;flex:1; }
.detail-title { font-family:'Sora','Inter',sans-serif;font-size:clamp(1.15rem,2.2vw,1.55rem);font-weight:800;line-height:1.2;letter-spacing:-.02em;margin:0 0 6px;color:var(--text); }
.detail-company { font-size:.85rem;color:var(--muted);display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:8px; }
.detail-company a { color:var(--text);text-decoration:none; }
.detail-company a:hover { color:var(--brand); }
.detail-badges { display:flex;flex-wrap:wrap;gap:6px;margin-top:2px; }
.detail-badge { display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:.68rem;font-weight:700;letter-spacing:.03em; }
.db-type { background:var(--brand-light);color:var(--brand); }
.db-featured { background:var(--accent);color:var(--brand-deep); }
.db-verified { background:#ecfdf5;color:#15803d; }

/* ── Detail Actions ── */
.detail-actions { display:flex;flex-wrap:wrap;gap:8px;padding-top:16px;border-top:1px solid var(--border); }
.detail-actions .btn { padding:9px 16px;font-size:.82rem;min-height:42px; }
.detail-actions .btn-primary { background:var(--brand);color:#fff;border-color:var(--brand); }
.detail-actions .btn-primary:hover { background:var(--brand-dark); }

/* ── Detail Body Text ── */
.detail-section-title { font-family:'Sora','Inter',sans-serif;font-size:1.05rem;font-weight:700;margin:0 0 14px;color:var(--text); }
.detail-body-text { font-size:.9rem;line-height:1.8;color:var(--text);word-break:break-word;overflow-wrap:anywhere; }
.detail-body-text p { margin-bottom:1rem; }
.detail-body-text img { max-width:100%;height:auto;display:block;margin:1rem 0;border-radius:8px; }
.detail-body-text ul, .detail-body-text ol { padding-left:1.5rem;margin-bottom:1rem; }
.detail-body-text li { margin-bottom:.4rem; }
.detail-body-text table { width:100%;border-collapse:collapse;margin:1rem 0;overflow-x:auto;display:block; }
.detail-body-text th, .detail-body-text td { border:1px solid var(--border);padding:.5rem;text-align:left; }
.detail-body-text pre, .detail-body-text code { white-space:pre-wrap;word-break:break-word;background:var(--bg);padding:.6rem;border-radius:8px;display:block;overflow-x:auto;font-size:.85rem; }
.detail-body-text * { max-width:100%!important;box-sizing:border-box; }

/* ── Share Links ── */
.share-links { display:flex;flex-wrap:wrap;gap:8px; }
.share-btn { display:inline-flex;align-items:center;gap:7px;padding:9px 15px;border-radius:8px;border:1px solid var(--border);background:var(--white);color:var(--text);font-family:'Inter',sans-serif;font-size:.82rem;font-weight:500;cursor:pointer;text-decoration:none;transition:.18s ease;min-height:40px; }
.share-btn:hover { border-color:var(--brand);color:var(--brand);background:var(--brand-light);text-decoration:none; }

/* ── Related Jobs ── */
.related-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:10px; }
.related-card { display:block;padding:14px 16px;border:1px solid var(--border);border-radius:10px;text-decoration:none;transition:.18s ease;background:var(--white); }
.related-card:hover { border-color:var(--brand);box-shadow:0 2px 14px rgba(10,47,87,.08);transform:translateY(-2px);text-decoration:none; }
.related-title { font-size:.88rem;font-weight:600;color:var(--text);margin-bottom:4px; }
.related-meta { display:inline-flex;align-items:center;gap:5px;font-size:.76rem;color:var(--muted); }

/* ── Sidebar ── */
.job-detail-side { display:flex;flex-direction:column;gap:18px;position:sticky;top:90px; }
@media (max-width:860px) { .job-detail-side { position:static; } }
.side-card { background:var(--white);border:1px solid var(--border);border-radius:14px;padding:24px;box-shadow:0 2px 14px rgba(10,47,87,.06); }
.side-card-title { font-family:'Sora','Inter',sans-serif;font-size:.92rem;font-weight:700;margin:0 0 16px;color:var(--text); }
.btn-block { display:flex;align-items:center;justify-content:center;width:100%;padding:13px 22px;font-size:.92rem;border-radius:10px; }
.btn-block:hover { text-decoration:none; }

/* ── Overview List ── */
.overview-list { list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:0; }
.overview-list li { display:flex;align-items:flex-start;gap:11px;padding:11px 0;border-bottom:1px solid var(--border); }
.overview-list li:last-child { border-bottom:none;padding-bottom:0; }
.overview-list li:first-child { padding-top:0; }
.ov-ic { width:32px;height:32px;border-radius:8px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0; }
.ov-ic svg { width:17px;height:17px; }
.ov-kv { display:flex;flex-direction:column;gap:1px; }
.ov-k { font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--muted); }
.ov-v { font-size:.85rem;font-weight:600;color:var(--text);word-break:break-word; }

/* ── Verified check (tooltip) ── */
.verified-check .verified-tip { position:absolute;bottom:calc(100% + 8px);left:50%;transform:translateX(-50%) translateY(4px);background:#fff;color:var(--text);font-size:.72rem;font-weight:600;line-height:1.4;white-space:nowrap;padding:7px 11px;border-radius:8px;border:1px solid var(--border);box-shadow:0 8px 24px rgba(10,47,87,.16);opacity:0;visibility:hidden;pointer-events:none;transition:opacity .16s,transform .16s;z-index:40;display:inline-flex;align-items:center;gap:6px; }
.verified-check .verified-tip::after { content:'';position:absolute;top:100%;left:50%;transform:translateX(-50%);border:6px solid transparent;border-top-color:#fff;filter:drop-shadow(0 1px 0 var(--border)); }
.verified-check:hover .verified-tip, .verified-check.open .verified-tip { opacity:1;visibility:visible;transform:translateX(-50%) translateY(0); }

/* ── Mobile apply bar ── */
.mob-apply-bar { display:none;position:fixed;bottom:0;left:0;right:0;z-index:950;background:rgba(255,255,255,.95);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-top:1px solid var(--border);padding:12px 0;padding-bottom:max(12px,calc(12px + env(safe-area-inset-bottom,0px)));box-shadow:0 -4px 20px rgba(10,47,87,.08); }
.mob-apply-bar .container { display:flex;align-items:center;justify-content:space-between;gap:12px; }
.mob-bar-salary { font-family:'Sora','Inter',sans-serif;font-size:.95rem;font-weight:700;color:var(--brand); }
.mob-apply-bar .btn { padding:12px 24px;font-size:.88rem;flex-shrink:0; }
@media (max-width:860px) { .mob-apply-bar { display:block; } .job-detail { padding-bottom:100px; } }

/* ── Save button ── */
.btn-outline.saved { color:var(--success);border-color:var(--success); }
.btn-outline.saved:hover { color:#fff;border-color:var(--success);background:var(--success); }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('copyLink')?.addEventListener('click', () => {
  navigator.clipboard.writeText(window.location.href).then(() => {
    const t = document.createElement('div');
    t.className = 'position-fixed bottom-0 end-0 m-4 p-3 text-white rounded-3 shadow';
    t.style.cssText = 'z-index:2000;background:var(--success,#16a34a);font-size:.85rem;font-weight:600';
    t.textContent = 'Job link copied to clipboard!';
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
  }).catch(() => { toastr.error('Failed to copy link. Try again.'); });
});

$("#saveJobBtn").on("click", function() {
  const btn = $(this);
  const jobId = btn.data("job-id");
  btn.prop("disabled", true).text("Processing…");
  $.ajax({
    url: "<?= site_url('jobs/toggle-save') ?>/" + jobId,
    method: "POST",
    success: function(r) {
      if (r.success) {
        btn.toggleClass("saved", r.saved);
        btn.html((r.saved ? '<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg> Saved' : '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg> Save'));
      } else { toastr.error(r.message); }
    },
    complete: function() { btn.prop("disabled", false); },
    error: function() { toastr.error("Network error. Try again."); btn.prop("disabled", false); }
  });
});

$('#report-job-form').on('submit', function(e) {
  e.preventDefault();
  const btn = $('#btn-submit-report');
  const modal = bootstrap.Modal.getInstance(document.getElementById('reportJobModal'));
  btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
  $.ajax({
    url: '<?= base_url('jobs/report') ?>',
    method: 'POST',
    data: $(this).serialize(),
    success: function(r) { toastr.success(r.message); modal.hide(); $('#report-job-form')[0].reset(); },
    error: function(x) { const r = x.responseJSON; toastr.error(r ? r.messages.error : 'An error occurred'); },
    complete: function() { btn.prop('disabled', false).text('Submit Report'); }
  });
});
</script>

<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha_site_key') ?>"></script>
<script>
    // -------------------------------------------------
    // Copy link toast
    // -------------------------------------------------
    document.getElementById('copyLink')?.addEventListener('click', () => {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 m-4 p-3 bg-success text-white rounded-3 shadow';
            toast.style.zIndex = '2000';
            toast.textContent = 'Job link copied to clipboard!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        });
    });

    // -------------------------------------------------
    // Bookmark toggle
    // -------------------------------------------------
    $("#saveJobBtn").on("click", function() {
        let btn = $(this);
        let jobId = btn.data("job-id");

        btn.prop("disabled", true).text("Processing...");

        $.ajax({
            url: "<?= site_url('jobs/toggle-save') ?>/" + jobId,
            method: "POST",
            success: function(response) {
                if (response.success) {
                    if (response.saved) {
                        btn.removeClass("btn-border").addClass("btn-danger").text("Unsave Job");
                    } else {
                        btn.removeClass("btn-danger").addClass("btn-border").text("Save Job");
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            complete: function() {
                btn.prop("disabled", false);
                btn.removeClass("btn-danger").addClass("btn-border").text("Save Job");
            },
            error: function() {
                toastr.error("Network error. Try again.");
                btn.prop("disabled", false);
            }
        });
    });

    // -------------------------------------------------
    // CV radio → show/hide upload field
    // -------------------------------------------------
    document.querySelectorAll('input[name="cv_source"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const container = document.getElementById('new_cv_container');
            const fileInput = document.getElementById('cv_file');
            if (!container) return;
            if (this.value === 'upload') {
                container.style.display = 'block';
                fileInput.setAttribute('required', 'required');
            } else {
                container.style.display = 'none';
                fileInput.removeAttribute('required');
            }
        });
    });

    const form = document.getElementById('jobApplicationForm');
    const coverLetter = document.getElementById('cover_letter');
    const charCount = document.getElementById('charCount');
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
            charCount.textContent = `${coverLetter.value.length} / 2000`;
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

    // Add reference row
    addRefBtn?.addEventListener('click', () => {
        const rowCount = refContainer.children.length;
        if (rowCount >= 5) return;

        const newRow = document.createElement('div');
        newRow.className = 'reference-row mb-2 p-3 border rounded bg-light';
        newRow.innerHTML = `
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="ref_name[]" class="form-control form-control-sm" placeholder="Full Name">
                </div>
                <div class="col-md-4">
                    <input type="text" name="ref_title[]" class="form-control form-control-sm" placeholder="Job Title">
                </div>
                <div class="col-md-3">
                    <input type="email" name="ref_email[]" class="form-control form-control-sm" placeholder="Email">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-ref" aria-label="Delete">
    <i class="bi bi-trash"></i>
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
            btn.style.display = rows.length > 1 ? 'block' : 'none';
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

        const form = document.getElementById('jobApplicationForm');
        if (!form) return;

        const submitBtn = document.getElementById('submitBtn');
        const spinner = submitBtn.querySelector('.spinner-border');
        const consent = document.getElementById('consent');

        // Enable submit only when consent is checked
        if (consent) {
            consent.addEventListener('change', () => {
                submitBtn.disabled = !consent.checked;
            });
        }

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
                    alert.textContent = 'Submission failed. Please try again.';
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
                    formData.append('job_description', '<?= addslashes(substr($job->description ?? '', 0, 2000)) ?>');
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

    // ── Inline Application Form ────────────────────────────────
    const toggleApplyForms = [
      document.getElementById('toggleApplyForm'),
      document.getElementById('toggleApplyFormSide')
    ].filter(Boolean);

    toggleApplyForms.forEach(btn => {
      if (!btn) return;

      btn.addEventListener('click', function() {
        const form = document.getElementById('applyForm');
        if (!form) return;

        if (form.style.display === 'none' || !form.style.display) {
          form.style.display = 'block';
          form.style.animation = 'slideDown 0.3s ease-out';
          this.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 7l-7 7-7-7"/></svg> Close';
        } else {
          form.style.display = 'none';
          form.style.animation = 'slideUp 0.3s ease-in';
          this.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg> Apply Now';
        }
      });
    });

    const cancelApply = document.getElementById('cancelApply');
    if (cancelApply) {
      cancelApply.addEventListener('click', function() {
        const form = document.getElementById('applyForm');
        const buttons = [document.getElementById('toggleApplyForm'), document.getElementById('toggleApplyFormSide')].filter(Boolean);

        if (form) {
          form.style.display = 'none';
          form.style.animation = 'slideUp 0.3s ease-in';
        }
        buttons.forEach(btn => {
          if (btn) {
            btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg> Apply Now';
          }
        });
      });
    }

    const inlineApplyForm = document.getElementById('inlineApplyForm');
    if (inlineApplyForm) {
      inlineApplyForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitApply');
        if (!submitBtn) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Submitting...';

        const formData = new FormData(this);
        const formContainer = document.getElementById('applyForm');

        try {
          const response = await fetch('<?= base_url('jobs/apply') ?>', {
            method: 'POST',
            body: formData
          });

          const data = await response.json();

          if (!response.ok) {
            throw data;
          }

          // Success
          if (data.redirect) {
            window.location.href = data.redirect;
          } else {
            toastr.success(data.message || 'Application submitted successfully!');
            if (formContainer) {
              formContainer.style.display = 'none';
              formContainer.style.animation = 'slideUp 0.3s ease-in';
            }
            toggleApplyForms.forEach(btn => {
              if (btn) {
                btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg> Applied';
              }
            });
            this.reset();
          }
        } catch (error) {
          console.error('Application error:', error);

          let errorMessage = 'Failed to submit application. Please try again.';
          if (error.errors) {
            errorMessage = '<strong>Please fix the errors below:</strong><ul>' +
              Object.values(error.errors).map(e => `<li>${e}</li>`).join('') +
              '</ul>';
          } else if (error.message) {
            errorMessage = error.message;
          }

          const alert = document.createElement('div');
          alert.className = 'alert alert-danger';
          alert.style.cssText = 'margin: 16px 0; padding: 12px 16px; border-radius: 8px;';
          alert.innerHTML = errorMessage;

          this.insertBefore(alert, this.firstChild);

          window.scrollTo({
            top: this.offsetTop - 100,
            behavior: 'smooth'
          });
        } finally {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg> Submit Application';
          }
        }
      });
    }

    // Add CSS animations for form slide
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
      }
      @keyframes slideUp {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
      }
    `;
    document.head.appendChild(style);
</script>


<?= $this->endSection() ?>
