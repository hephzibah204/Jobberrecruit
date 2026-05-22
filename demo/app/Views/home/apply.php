<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>
<?php
// ---------------------------------------------------------------------
// 1. Build the *Apply* button(s) – same logic as original file
// ---------------------------------------------------------------------
$trackUrl = base_url("job/start-application/{$job->id}");
$defaultLabel = 'Apply Now';
$defaultIcon = 'bi-arrow-right';

switch ($job->application_method ?? 'form') {
    case 'whatsapp':
        $url = esc($job->whatsapp_link, 'url');
        $label = 'Apply via WhatsApp';
        $icon = 'bi-whatsapp';
        $btnClass = 'btn-success';
        $target = '_blank';
        break;

    case 'email':
        $email = esc($job->application_email ?? $job->contact_email);
        $subject = rawurlencode("Application: {$job->title}");
        $url = "mailto:{$email}?subject={$subject}";
        $label = 'Apply via Email';
        $icon = 'bi-envelope';
        $btnClass = 'btn-info';
        $target = '';
        break;

    case 'external':
        $url = esc($job->external_url, 'url');
        $label = 'Apply on External Site';
        $icon = 'bi-box-arrow-up-right';
        $btnClass = 'btn-warning';
        $target = '_blank';
        break;

    case 'form':
    default:
        $url = $trackUrl;
        $label = $defaultLabel;
        $icon = $defaultIcon;
        $btnClass = 'btn-primary';
        $target = '';
        break;
}
$targetAttr = $target ? "target='_blank' rel='noopener'" : '';

// Top-header button (kept for all methods)
$applyBtn = <<<HTML
<a href="{$url}" class="btn {$btnClass} btn-sm" {$targetAttr}>
    {$label} <i class="bi {$icon} ms-1"></i>
</a>
HTML;

// Sticky button – **only** for the internal form (we’ll replace it with a “Submit” button later)
$stickyBtn = ($job->application_method === 'form')
    ? '<button type="submit" form="jobApplicationForm" class="btn btn-primary btn-lg shadow">Submit Application <i class="bi bi-send ms-1"></i></button>'
    : <<<HTML
<a href="{$url}" class="btn {$btnClass} btn-lg shadow" {$targetAttr}>
    {$label} <i class="bi {$icon} ms-1"></i>
</a>
HTML;
?>
<section class="job-details-section py-5 bg-light">
    <div class="container">
        <div class="row g-4">

            <!-- ===== LEFT COLUMN: Job Description (unchanged) ===== -->
            <div class="col-lg-8 order-2 order-lg-1">
                <div class="job-header bg-white p-4 rounded-3 shadow-sm mb-4 position-relative">
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?= !empty($job->anonymous) || !empty($job->is_anonymous) ? base_url('images/favicon.png') : $job->company_logo ?>" alt="<?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Anonymous Employer' : esc($job->employer_name) ?> Logo"
                            class="rounded me-3" width="80" height="80" style="object-fit: cover;">
                        <div>
                            <h3 class="fw-bold mb-1"><?= esc($job->title) ?></h3>
                            <p class="mb-0 text-muted fs-6">
                                by <?php if (!empty($job->anonymous) || !empty($job->is_anonymous)): ?>
                                    <span class="fw-semibold text-dark">Confidential Employer</span>
                                <?php else: ?>
                                    <a href="<?= base_url('employer/' . $job->employer_id) ?>"><span class="fw-semibold text-dark"><?= esc($job->employer_name) ?></span>
                                        <span><?php if ($job->show_trust_badge): ?>
                                                <img src="<?= base_url('images/badge.svg') ?>"
                                                    data-bs-toggle="tooltip"
                                                    width="16"
                                                    title="This employer is verified and subscribed to a trusted plan"><?php endif; ?></span></a>
                                <?php endif; ?>
                                <span class="badge bg-success-subtle text-success fw-medium ms-2"><?= strtoupper(esc($job->job_type)) ?></span>
                                <?php if ($job->featured): ?>
                                    <span class="badge bg-primary-subtle text-primary fw-medium ms-1">Featured</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <!-- TOP HEADER BUTTON -->
                    <div class="d-flex justify-content-end gap-2">
                        <button
                            id="saveJobBtn"
                            data-job-id="<?= $job->id ?>"
                            class="btn <?= $isSaved ? 'btn-danger' : 'btn-border' ?>">
                            <?= $isSaved ? 'Unsave Job' : 'Save Job' ?>
                        </button>
                        <?= $applyBtn ?>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="job-description bg-white p-4 rounded-3 shadow-sm mb-4 text-wrap">
                    <h5 class="fw-semibold mb-3">Job Description</h5>
                    <div class="text-muted">
                        <?= $job->description ? $job->description : '<p>No job description provided.</p>' ?>
                    </div>

                    <?php if (!empty($job->requirements)): ?>
                        <h6 class="fw-semibold mt-4 mb-3">Requirements</h6>
                        <div class="text-muted"><?= $job->requirements ?></div>
                    <?php endif; ?>

                    <?php if (!empty($job->application)): ?>
                        <h6 class="fw-semibold mt-4 mb-3">Application Guidelines</h6>
                        <div class="text-muted"><?= $job->application ?></div>
                    <?php endif; ?>
                </div>

                <!-- Related Jobs (unchanged) -->
                <?php if (!empty($related_jobs)): ?>
                    <div class="related-jobs bg-white p-4 rounded-3 shadow-sm">
                        <h5 class="fw-semibold mb-3">Related Jobs</h5>
                        <div class="row g-3">
                            <?php foreach ($related_jobs as $related): ?>
                                <div class="col-md-6">
                                    <a href="<?= base_url('job/view/' . $related->id) ?>" class="text-decoration-none">
                                        <div class="job-card p-3 bg-light rounded-3 transition-all">
                                            <h6 class="fw-semibold mb-1 fs-6"><?= esc($related->title) ?></h6>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-geo-alt me-1"></i><?= esc($related->location) ?>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ===== RIGHT COLUMN: Application Form (only for internal form) ===== -->
            <div class="col-lg-4 order-1 order-lg-2">
                <?php if ($job->application_method === 'form'): ?>
                    <!-- -------------------------------------------------
                         APPLICATION FORM
                         ------------------------------------------------- -->
                    <?php
                    /**
                     * Enhanced Job Application Form with Full Real-World Features
                     * 
                     * This version includes:
                     * 1. Complete form with all standard job application fields
                     * 2. Smart CV handling (saved + upload)
                     * 3. Optional cover letter with character counter
                     * 4. Full name & contact info (for guest users)
                     * 5. Professional references section
                     * 6. Availability & salary expectations
                     * 7. Eligibility to work confirmation
                     * 8. Consent & data privacy acknowledgment
                     * 9. reCAPTCHA v3 integration (optional)
                     * 10. Client-side validation with visual feedback
                     * 11. Accessible, responsive design
                     * 
                     * Assumptions:
                     * - CodeIgniter 4 framework
                     * - Bootstrap 5 + Bootstrap Icons
                     * - reCAPTCHA v3 site key configured in .env
                     * - File uploads stored in writable/uploads/cv/
                     */

                    // $user = auth()->user();
                    // $loggedIn = auth()->loggedIn();
                    $savedCvPath = $user ? ($candidate->resume ?? null) : null;
                    $hasSavedCv = $user && $savedCvPath && file_exists(FCPATH . $savedCvPath);
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
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-ref" style="display:none;">
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
                <?php else: ?>
                    <!-- -------------------------------------------------
                         ORIGINAL SIDEBAR (Job Overview / Company / Share)
                         ------------------------------------------------- -->
                    <!-- Job Overview -->
                    <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                        <h6 class="fw-semibold mb-3">Job Overview</h6>
                        <ul class="list-unstyled fs-6 text-muted">
                            <li class="mb-3"><i class="bi bi-calendar me-2 text-primary"></i><strong>Posted:</strong> <?= esc($job->formatted_created_at) ?></li>
                            <li class="mb-3"><i class="bi bi-clock me-2 text-primary"></i><strong>Status:</strong> <?= esc(ucfirst($job->status)) ?></li>
                            <li class="mb-3"><i class="bi bi-briefcase me-2 text-primary"></i><strong>Level:</strong> <?= ucfirst(esc($job->experience_level)) ?></li>
                            <li class="mb-3"><i class="bi bi-cash me-2 text-primary"></i><strong>Salary:</strong> <?= esc($job->salary_range) ?></li>
                            <li class="mb-3"><i class="bi bi-building me-2 text-primary"></i><strong>Accommodation:</strong> <?= esc($job->accommodation === 'available' ? 'Available' : 'Not Available') ?></li>
                            <li class="mb-3"><i class="bi bi-mortarboard me-2 text-primary"></i><strong>Education:</strong> <?= esc(ucfirst($job->education_level) ?? 'Not specified') ?></li>
                            <li class="mb-0"><i class="bi bi-send me-2 text-primary"></i><strong>Apply Via:</strong> <?= ucfirst($job->application_method ?? 'form') ?></li>
                        </ul>
                    </div>

                    <?php if($job->anonymous === false): ?>
                    <!-- Company Overview -->
                    <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                        <h6 class="fw-semibold mb-3">About <?= esc($job->employer_name) ?></h6>
                        <ul class="list-unstyled fs-6 text-muted">
                            <li class="mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i><strong>Location:</strong> <?= esc($job->company_address ?? 'Not provided') ?></li>
                            <li class="mb-3"><i class="bi bi-telephone me-2 text-primary"></i><strong>Phone:</strong> <?= esc($job->company_phone ?? 'Not provided') ?></li>
                            <li class="mb-3"><i class="bi bi-envelope me-2 text-primary"></i><strong>Email:</strong> <?= esc($job->company_email ?? 'Not provided') ?></li>
                            <?php if ($job->company_website): ?>
                                <li class="mb-3"><i class="bi bi-globe me-2 text-primary"></i><strong>Website:</strong> <a href="<?= esc($job->company_website) ?>" target="_blank" class="text-primary"><?= esc($job->company_website) ?></a></li>
                            <?php endif; ?>
                            <li class="mb-0"><i class="bi bi-briefcase me-2 text-primary"></i><strong>Open Positions:</strong> <?= $employer_job_count ?></li>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <!-- Share Job -->
                    <div class="bg-white p-4 rounded-3 shadow-sm">
                        <h6 class="fw-semibold mb-3">Share this Job</h6>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <button class="btn btn-outline-secondary btn-sm" id="copyLink" title="Copy Job Link"><i class="bi bi-link-45deg"></i> Copy Link</button>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(current_url()) ?>" class="btn btn-sm" style="background: #0A66C2; color: white; border: none;" target="_blank"><i class="bi bi-linkedin"></i> LinkedIn</a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" class="btn btn-sm" style="background: #1877F2; color: white; border: none;" target="_blank"><i class="bi bi-facebook"></i> Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($job->title) ?>" class="btn btn-sm" style="background: #000000; color: white; border: none;" target="_blank"><i class="bi bi-twitter-x"></i> X</a>
                            <a href="mailto:?subject=Check out this job: <?= urlencode($job->title) ?>&body=<?= urlencode(current_url()) ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-envelope"></i> Email</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- STICKY BUTTON (bottom-right) -->
            <!-- <div class="sticky-apply-btn position-fixed bottom-0 end-0 m-4 d-none d-lg-block">
                <?= $stickyBtn ?>
            </div> -->
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .job-details-section {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .job-header {
        transition: all .3s ease;
        border: 1px solid #dee2e6;
    }

    .job-header:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, .1);
    }

    .job-description {
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }

    .job-description .text-muted {
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .job-description img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1rem 0;
        border-radius: 6px;
    }

    .job-description table {
        width: 100%;
        border-collapse: collapse;
        overflow-x: auto;
        display: block;
    }

    .job-description table td,
    .job-description table th {
        border: 1px solid #dee2e6;
        padding: .5rem;
    }

    .job-description pre,
    .job-description code {
        white-space: pre-wrap;
        word-break: break-word;
        background: #f8f9fa;
        padding: .5rem;
        border-radius: 6px;
        display: block;
        overflow-x: auto;
    }

    .job-card {
        transition: all .3s ease;
    }

    .job-card:hover {
        transform: translateY(-3px);
        background-color: #f1f3f5 !important;
    }

    .btn-sm {
        padding: .5rem 1rem;
        font-size: .9rem;
    }

    .shadow-sm {
        box-shadow: 0 4px 12px rgba(0, 0, 0, .05) !important;
    }

    .sticky-apply-btn {
        z-index: 1000;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: all .3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-2px);
    }

    .bookmark-btn:hover i {
        color: #007bff;
    }

    .transition-all {
        transition: all .3s ease;
    }

    .job-description * {
        max-width: 100% !important;
        box-sizing: border-box;
    }

    .reference-row {
        transition: all 0.2s;
    }

    .reference-row:hover {
        background-color: #f8f9fa;
    }

    #charCount {
        font-size: 0.875rem;
    }

    .was-validated .form-control:invalid,
    .was-validated .form-select:invalid {
        border-color: #dc3545;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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
                    <button type="button" class="btn btn-sm btn-outline-danger remove-ref">
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
                const token = await grecaptcha.execute(
                    '<?= env('recaptcha_site_key') ?>', {
                        action: 'apply_job'
                    }
                );

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
</script>

<?= $this->endSection() ?>
