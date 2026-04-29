<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Post a Job</h4>
                <h6>Create new job posting</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="<?= site_url('employer/dashboard') ?>" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to Dashboard</a>
        </div>
    </div>

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

    <form method="POST" class="add-product-form">
        <?= csrf_field() ?>
        <div class="add-product">
            <div class="accordions-items-seperate" id="accordionSpacingExample">
                <!-- Job Information -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingSpacingOne">
                        <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingOne" aria-expanded="true" aria-controls="SpacingOne">
                            <div class="d-flex align-items-center justify-content-between flex-fill">
                                <h5 class="d-flex align-items-center"><i data-feather="info" class="text-primary me-2"></i><span>Job Information</span></h5>
                            </div>
                        </div>
                    </h2>
                    <div id="SpacingOne" class="accordion-collapse collapse show" aria-labelledby="headingSpacingOne">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Job Title<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="title" class="form-control" value="<?= old('title') ?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Job Type<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="job_type" required>
                                            <option value="">Select</option>
                                            <option value="full-time" <?= old('job_type') == 'full-time' ? 'selected' : '' ?>>Full Time</option>
                                            <option value="part-time" <?= old('job_type') == 'part-time' ? 'selected' : '' ?>>Part Time</option>
                                            <option value="contract" <?= old('job_type') == 'contract' ? 'selected' : '' ?>>Contract</option>
                                            <option value="freelance" <?= old('job_type') == 'freelance' ? 'selected' : '' ?>>Freelance</option>
                                            <option value="internship" <?= old('job_type') == 'internship' ? 'selected' : '' ?>>Internship</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Location<span class="text-danger ms-1">*</span></label>
                                        <select class="select location-select" name="state_id" required>
                                            <option value="" selected disabled>Select</option>
                                            <?php foreach ($states as $state): ?>
                                                <option value="<?= $state->id ?>" <?= old('state_id') == $state->id ? 'selected' : '' ?>>
                                                    <?= $state->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Location Type<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="location_type" required>
                                            <option value="" selected disabled>Select</option>
                                            <option value="hybrid" <?= old('location_type') == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                            <option value="remote" <?= old('location_type') == 'remote' ? 'selected' : '' ?>>Remote</option>
                                            <option value="on-site" <?= old('location_type') == 'on-site' ? 'selected' : '' ?>>On-Site</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Salary Type<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="salary_type" id="salary_type" onchange="toggleSalaryInput()" required>
                                            <option value="" selected disabled>Select</option>
                                            <option value="fixed" <?= old('salary_type') == 'fixed' ? 'selected' : '' ?>>Fixed Salary</option>
                                            <option value="range" <?= old('salary_type') == 'range' ? 'selected' : '' ?>>Salary Range</option>
                                            <option value="negotiable" <?= old('salary_type') == 'negotiable' ? 'selected' : '' ?>>Negotiable / Not Disclosed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Salary Period<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="salary_period" id="salary_period" required>
                                            <option value="" selected disabled>Select Period</option>
                                            <option value="monthly" <?= old('salary_period') == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                            <option value="yearly" <?= old('salary_period') == 'yearly' ? 'selected' : '' ?>>Yearly</option>
                                            <option value="hourly" <?= old('salary_period') == 'hourly' ? 'selected' : '' ?>>Hourly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3" id="salary_input_container">
                                        <label class="form-label">Salary</label>
                                        <input type="text" name="salary" id="salary_input" class="form-control" placeholder="e.g., ₦500,000 or ₦250,000 - ₦750,000" value="<?= old('salary') ?>">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Accommodation<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="accommodation" id="accommodation" required>
                                            <option value="" selected disabled>Select Accommodation</option>
                                            <option value="available" <?= old('accommodation') == 'available' ? 'selected' : '' ?>>Available</option>
                                            <option value="not_available" <?= old('accommodation') == 'not_available' ? 'selected' : '' ?>>Not Available</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <div class="add-newplus">
                                            <label class="form-label">Industry<span class="text-danger ms-1">*</span></label>
                                        </div>
                                        <select class="select industry-select" name="industry_id" required>
                                            <option value="" selected disabled>Select</option>
                                            <?php foreach ($industries as $industry): ?>
                                                <option value="<?= $industry->id ?>" <?= old('industry_id') == $industry->id ? 'selected' : '' ?>>
                                                    <?= $industry->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Job Category<span class="text-danger ms-1">*</span></label>
                                        <select class="select category-select" name="category_id" required>
                                            <option value="" selected disabled>Select</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category->id ?>" <?= old('category_id') == $category->id ? 'selected' : '' ?>>
                                                    <?= $category->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Job Description -->
                            <div class="col-lg-12">
                                <div class="summer-description-box">
                                    <label class="form-label">Job Description<span class="text-danger ms-1">*</span></label>
                                    <div id="description-editor" style="height: 200px;"></div>
                                    <input type="hidden" name="description" id="description-input" value="<?= esc(old('description')) ?>" required>
                                    <p class="fs-14 mt-1">Be specific about the role and requirements</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requirements & Qualifications -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingSpacingTwo">
                        <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingTwo" aria-expanded="true" aria-controls="SpacingTwo">
                            <div class="d-flex align-items-center justify-content-between flex-fill">
                                <h5 class="d-flex align-items-center"><i data-feather="life-buoy" class="text-primary me-2"></i><span>Requirements & Qualifications</span></h5>
                            </div>
                        </div>
                    </h2>
                    <div id="SpacingTwo" class="accordion-collapse collapse show" aria-labelledby="headingSpacingTwo">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Minimum Education<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="education_level" required>
                                            <option value="" selected disabled>Select</option>
                                            <option value="High School" <?= old('education_level') == 'High School' ? 'selected' : '' ?>>High School</option>
                                            <option value="Associate Degree" <?= old('education_level') == 'Associate Degree' ? 'selected' : '' ?>>Associate Degree</option>
                                            <option value="Bachelor's Degree" <?= old('education_level') == 'Bachelor\'s Degree' ? 'selected' : '' ?>>Bachelor's Degree</option>
                                            <option value="Master's Degree" <?= old('education_level') == 'Master\'s Degree' ? 'selected' : '' ?>>Master's Degree</option>
                                            <option value="PhD" <?= old('education_level') == 'PhD' ? 'selected' : '' ?>>PhD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Years of Experience<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="experience_level" required>
                                            <option value="" selected disabled>Select</option>
                                            <option value="Entry Level (0-2 years)" <?= old('experience_level') == 'Entry Level (0-2 years)' ? 'selected' : '' ?>>Entry Level (0-2 years)</option>
                                            <option value="Mid Level (2-5 years)" <?= old('experience_level') == 'Mid Level (2-5 years)' ? 'selected' : '' ?>>Mid Level (2-5 years)</option>
                                            <option value="Senior Level (5+ years)" <?= old('experience_level') == 'Senior Level (5+ years)' ? 'selected' : '' ?>>Senior Level (5+ years)</option>
                                            <option value="Executive Level" <?= old('experience_level') == 'Executive Level' ? 'selected' : '' ?>>Executive Level</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Required Skills</label>
                                        <input type="text" name="skills" class="form-control" placeholder="e.g., JavaScript, Project Management, Communication" value="<?= old('skills') ?>">
                                        <p class="fs-14 mt-1">Separate skills with commas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Additional Requirements</label>
                                        <div id="requirements-editor" style="height: 150px;"></div>
                                        <input type="hidden" name="requirements" id="requirements-input" value="<?= esc(old('requirements')) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Details -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingSpacingThree">
                        <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingThree" aria-expanded="true" aria-controls="SpacingThree">
                            <div class="d-flex align-items-center justify-content-between flex-fill">
                                <h5 class="d-flex align-items-center"><i data-feather="clipboard" class="text-primary me-2"></i><span>Application Details</span></h5>
                            </div>
                        </div>
                    </h2>
                    <div id="SpacingThree" class="accordion-collapse collapse show" aria-labelledby="headingSpacingThree">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Application Deadline</label>
                                        <div class="input-groupicon calender-input">
                                            <i data-feather="calendar" class="info-img"></i>
                                            <input type="date" name="application_deadline" class="form-control" value="<?= old('application_deadline') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Expected Start Date</label>
                                        <div class="input-groupicon calender-input">
                                            <i data-feather="calendar" class="info-img"></i>
                                            <input type="date" name="start_date" class="form-control" value="<?= old('start_date') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Contact Email<span class="text-danger ms-1">*</span></label>
                                        <input type="email" name="contact_email" class="form-control" value="<?= old('contact_email', $employer->contact_email) ?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Contact Phone</label>
                                        <input type="text" name="contact_phone" class="form-control" value="<?= old('contact_phone', $employer->contact_phone) ?>">
                                    </div>
                                </div>
                            </div>
                            <!-- ==== INSIDE the “Application Details” accordion ==== -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Application Method <span class="text-danger ms-1">*</span></label>

                                        <div class="d-flex flex-wrap gap-3 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="application_method" id="method_form" value="form"
                                                    <?= old('application_method', 'form') === 'form' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="method_form">Application Form (JobberRecruit)</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="application_method" id="method_whatsapp" value="whatsapp"
                                                    <?= old('application_method') === 'whatsapp' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="method_whatsapp">WhatsApp</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="application_method" id="method_email" value="email"
                                                    <?= old('application_method') === 'email' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="method_email">Email</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="application_method" id="method_external" value="external"
                                                    <?= old('application_method') === 'external' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="method_external">External Page</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ---- Conditional fields (hidden by default) ---- -->
                            <div class="row" id="conditional-fields">
                                <!-- WhatsApp -->
                                <div class="col-sm-6 col-12 conditional-field" data-method="whatsapp" style="display:none;">
                                    <div class="mb-3">
                                        <label class="form-label">WhatsApp Link <span class="text-danger ms-1">*</span></label>
                                        <input type="url" name="whatsapp_link" class="form-control"
                                            placeholder="https://wa.me/2348000000000" value="<?= old('whatsapp_link') ?>">
                                        <small class="text-muted">Full WhatsApp URL (including https://)</small>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-sm-6 col-12 conditional-field" data-method="email" style="display:none;">
                                    <div class="mb-3">
                                        <label class="form-label">Application Email <span class="text-danger ms-1">*</span></label>
                                        <input type="email" name="application_email" class="form-control"
                                            placeholder="jobs@company.com" value="<?= old('application_email') ?>">
                                    </div>
                                </div>

                                <!-- External Page -->
                                <div class="col-sm-6 col-12 conditional-field" data-method="external" style="display:none;">
                                    <div class="mb-3">
                                        <label class="form-label">External Application URL <span class="text-danger ms-1">*</span></label>
                                        <input type="url" name="external_url" class="form-control"
                                            placeholder="https://company.com/apply" value="<?= old('external_url') ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Application Access Type -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Who Can Apply? <span class="text-danger ms-1">*</span></label>

                                        <div class="d-flex flex-wrap gap-3 mt-2">

                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="radio"
                                                    name="application_access"
                                                    id="access_general"
                                                    value="general"
                                                    <?= old('application_access', 'general') === 'general' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="access_general">General (Anyone can apply)</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="radio"
                                                    name="application_access"
                                                    id="access_authenticated"
                                                    value="authenticated"
                                                    <?= old('application_access') === 'authenticated' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="access_authenticated">Authenticated Candidates Only</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="radio"
                                                    name="application_access"
                                                    id="access_guest"
                                                    value="guest"
                                                    <?= old('application_access') === 'guest' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="access_guest">Guest Applicants Only</label>
                                            </div>

                                        </div>

                                        <small class="text-muted">
                                            Control who is eligible to apply for this job.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Application Instructions</label>
                                        <div id="application-instructions-editor" style="height: 150px;"></div>
                                        <input type="hidden" name="application" id="application-input" value="<?= esc(old('application_instructions')) ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Preferences Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-semibold mb-3">
                                                <i class="ti ti-bell me-2"></i>Notification Preferences
                                            </h6>
                                            <p class="text-muted small mb-3">
                                                Choose how you want to be notified when candidates apply for this job.
                                            </p>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="notificationInApp" name="notification_in_app" value="1" checked>
                                                        <label class="form-check-label" for="notificationInApp">
                                                            <i class="ti ti-bell-ringing text-primary me-1"></i>
                                                            In-App Notifications
                                                            <small class="text-muted d-block">Receive notifications in your dashboard</small>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="notificationEmailToggle" name="notification_email_toggle" value="1">
                                                        <label class="form-check-label" for="notificationEmailToggle">
                                                            <i class="ti ti-mail text-primary me-1"></i>
                                                            Email Notifications
                                                            <small class="text-muted d-block">Receive email alerts for new applications</small>
                                                        </label>
                                                    </div>

                                                    <div id="notificationEmailField" style="display: none;">
                                                        <label class="form-label">Notification Email Address</label>
                                                        <input type="email" name="notification_email" class="form-control"
                                                            placeholder="<?= esc($employer->contact_email) ?>"
                                                            value="<?= esc($employer->contact_email) ?>">
                                                        <small class="text-muted">Leave empty to use your company email</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="alert alert-info mt-2 mb-0">
                                                <i class="ti ti-info-circle me-2"></i>
                                                <strong>Note:</strong> You'll always receive notifications in your dashboard.
                                                Email notifications are optional and can be enabled per job posting.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pre-screening Questions (ATS) -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingQuestions">
                        <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#collapseQuestions" aria-expanded="false" aria-controls="collapseQuestions">
                            <div class="d-flex align-items-center justify-content-between flex-fill">
                                <h5 class="d-flex align-items-center"><i data-feather="help-circle" class="text-primary me-2"></i><span>Pre-screening Questions (ATS)</span></h5>
                            </div>
                        </div>
                    </h2>
                    <div id="collapseQuestions" class="accordion-collapse collapse" aria-labelledby="headingQuestions">
                        <div class="accordion-body border-top">
                            <p class="text-muted small mb-3">Add questions to pre-screen candidates. Their answers will be shown alongside their applications.</p>
                            
                            <div id="questions-container">
                                <!-- Questions will be added here -->
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-question-btn">
                                <i class="ti ti-plus me-1"></i> Add Question
                            </button>
                        </div>
                    </div>
                </div>

                <?php if (!empty($canPostAnonymous)): ?>
                    <div class="accordion-item border mb-4">
                        <h2 class="accordion-header" id="headingSpacingAnonymous">
                            <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingAnonymous" aria-expanded="true" aria-controls="SpacingAnonymous">
                                <div class="d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-flex align-items-center"><i data-feather="eye-off" class="text-primary me-2"></i><span>Anonymous Posting</span></h5>
                                </div>
                            </div>
                        </h2>
                        <div id="SpacingAnonymous" class="accordion-collapse collapse show" aria-labelledby="headingSpacingAnonymous">
                            <div class="accordion-body border-top">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_anonymous" id="is_anonymous" value="1" <?= old('is_anonymous') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="is_anonymous">
                                                Post this job anonymously
                                            </label>
                                        </div>
                                        <p class="text-muted small mb-0">When enabled, the employer name and logo will be hidden from public listings.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Company Information -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingSpacingFour">
                        <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingFour" aria-expanded="true" aria-controls="SpacingFour">
                            <div class="d-flex align-items-center justify-content-between flex-fill">
                                <h5 class="d-flex align-items-center"><i data-feather="briefcase" class="text-primary me-2"></i><span>Company Information</span></h5>
                            </div>
                        </div>
                    </h2>
                    <div id="SpacingFour" class="accordion-collapse collapse show" aria-labelledby="headingSpacingFour">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" class="form-control" value="<?= esc($employer->company_name) ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Company Size</label>
                                        <input type="text" class="form-control" value="<?= esc($employer->company_size) ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Company Description</label>
                                        <textarea class="form-control" rows="4" readonly><?= esc($employer->description) ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i data-feather="info" class="me-2"></i>
                                    <span>This information is pulled from your employer profile. To update, please edit your profile.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-end mb-4">
                <a href="<?= site_url('employer/dashboard') ?>" class="btn btn-secondary me-2">Cancel</a>
                <div class="d-flex align-items-center gap-2">
                    <!-- Job count indicator -->
                    <div class="text-muted" style="font-size: 0.9rem;">
                        <i class="bi bi-briefcase me-1"></i>
                        <span id="jobCounter"><?= $creditBalance ?></span> Job Credits Available
                        <?php if ($creditBalance <= 0): ?>
                            <span class="badge bg-danger ms-2">No Credits</span>
                        <?php endif; ?>
                    </div>

                    <!-- Post Job Button -->
                    <button type="submit"
                        class="btn btn-primary px-4"
                        id="postJobBtn"
                        <?= $creditBalance <= 0 ? 'disabled' : '' ?>
                        <?= $creditBalance <= 0 ? 'title="You have no job credits left. Buy a bundle or subscribe to a plan to continue."' : '' ?>>
                        <i class="bi bi-check-circle me-1"></i>
                        Post Job
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Load Select2 CSS & JS -->

<style>
    /* Force Select2 to behave exactly like Bootstrap input */
    .select2-container--bootstrap-5 {
        width: 100% !important;
    }

    .select2-container--bootstrap-5 .select2-selection {
        height: 38px !important;
        min-height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        background-color: #fff !important;

        /* KEY FIX */
        display: flex !important;
        align-items: center !important;
    }

    /* Ensure selected text stays centered */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        display: flex !important;
        align-items: center !important;
        height: 100% !important;
        padding-left: 12px !important;
        padding-right: 32px !important;
        line-height: normal !important;
    }

    /* Fix arrow alignment */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        top: 0 !important;
        right: 8px !important;
    }

    /* Icon alignment inside selected value */
    .select2-selection__rendered i {
        margin-right: 8px;
        line-height: 1;
    }

    /* Focus state */
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }


    /* Dropdown styling */
    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        z-index: 1060 !important;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown {
        padding: 8px !important;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        padding: 6px 12px !important;
        font-size: 14px !important;
    }

    /* Results styling */
    .select2-container--bootstrap-5 .select2-results__option {
        padding: 8px 12px !important;
        font-size: 14px !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--selected {
        background-color: #0d6efd !important;
        color: white !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #e9ecef !important;
        color: #212529 !important;
    }

    /* Make sure it matches your select styling */
    .select.select2-hidden-accessible {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    /* Fix for icons */
    .select2-selection__rendered i {
        margin-right: 8px !important;
        vertical-align: middle !important;
    }

    /* Match the height with other form controls */
    .select2-container--bootstrap-5+.select2-container--bootstrap-5 {
        margin-top: 0 !important;
    }

    /* Ensure proper alignment in form groups */
    .mb-3 .select2-container--bootstrap-5 {
        margin-bottom: 0 !important;
    }

    /* Clear button styling */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
        margin-right: 25px !important;
        height: 36px !important;
        line-height: 36px !important;
    }

    /* Make sure it doesn't break the layout */
    .select2-container {
        display: block !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Configure toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000
    };

    // Toggle notification email field
    const emailToggle = document.getElementById('notificationEmailToggle');
    const emailField = document.getElementById('notificationEmailField');

    if (emailToggle) {
        emailToggle.addEventListener('change', function() {
            emailField.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Global variable to store Quill instances
    let quillInstances = {};

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Set minimum date for deadline and start date to today
    const today = new Date().toISOString().split('T')[0];
    const deadlineField = document.querySelector('input[name="application_deadline"]');
    const startDateField = document.querySelector('input[name="start_date"]');

    if (deadlineField) {
        deadlineField.min = today;
    }
    if (startDateField) {
        startDateField.min = today;
    }

    // Initialize Select2 for searchable dropdowns
    $(document).ready(function() {
        // Location (State) select with search
        $('.location-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search or select a state...',
            allowClear: true,
            width: '100%',
            dropdownParent: $('.location-select').closest('.mb-3'),
            language: {
                noResults: function() {
                    return "No states found";
                },
                searching: function() {
                    return "Searching...";
                }
            },
            templateResult: function(state) {
                if (!state.id) {
                    return state.text;
                }
                return $('<span><i class="bi bi-geo-alt me-2"></i>' + state.text + '</span>');
            },
            templateSelection: function(state) {
                if (!state.id) {
                    return $('<span>' + state.text + '</span>');
                }
                return $('<span><i class="bi bi-geo-alt me-2"></i>' + state.text + '</span>');
            }
        });

        // Industry select with search
        $('.industry-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search or select an industry...',
            allowClear: true,
            width: '100%',
            dropdownParent: $('.industry-select').closest('.mb-3'),
            language: {
                noResults: function() {
                    return "No industries found";
                }
            },
            templateResult: function(industry) {
                if (!industry.id) {
                    return industry.text;
                }
                return $('<span><i class="bi bi-building me-2"></i>' + industry.text + '</span>');
            },
            templateSelection: function(industry) {
                if (!industry.id) {
                    return $('<span>' + industry.text + '</span>');
                }
                return $('<span><i class="bi bi-building me-2"></i>' + industry.text + '</span>');
            }
        });

        // Category select with search
        $('.category-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search or select a category...',
            allowClear: true,
            width: '100%',
            dropdownParent: $('.category-select').closest('.mb-3'),
            language: {
                noResults: function() {
                    return "No categories found";
                }
            },
            templateResult: function(category) {
                if (!category.id) {
                    return category.text;
                }
                return $('<span><i class="bi bi-tag me-2"></i>' + category.text + '</span>');
            },
            templateSelection: function(category) {
                if (!category.id) {
                    return $('<span>' + category.text + '</span>');
                }
                return $('<span><i class="bi bi-tag me-2"></i>' + category.text + '</span>');
            }
        });

        // Handle existing values (from old input)
        const locationVal = "<?= old('state_id') ?>";
        if (locationVal) {
            $('.location-select').val(locationVal).trigger('change');
        }

        const industryVal = "<?= old('industry_id') ?>";
        if (industryVal) {
            $('.industry-select').val(industryVal).trigger('change');
        }

        const categoryVal = "<?= old('category_id') ?>";
        if (categoryVal) {
            $('.category-select').val(categoryVal).trigger('change');
        }
    });

    // Initialize Quill editors with simplified toolbar to avoid header duplication
    const descriptionEditor = new Quill('#description-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{
                    'header': [false, 1, 2, 3]
                }], // Simplified header options
                ['bold', 'italic', 'underline'],
                ['link', 'blockquote'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
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
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
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
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                ['clean']
            ]
        },
        placeholder: 'Be specific about the application process...'
    });

    // Store Quill instances
    quillInstances = {
        description: descriptionEditor,
        requirements: requirementsEditor,
        application: applicationInstructionsEditor
    };

    // Set initial content if there's old input
    if (document.getElementById('description-input').value) {
        descriptionEditor.root.innerHTML = document.getElementById('description-input').value;
    }

    if (document.getElementById('requirements-input').value) {
        requirementsEditor.root.innerHTML = document.getElementById('requirements-input').value;
    }

    if (document.getElementById('application-input').value) {
        applicationInstructionsEditor.root.innerHTML = document.getElementById('application-input').value;
    }

    // Update hidden inputs when Quill content changes
    Object.entries(quillInstances).forEach(([name, editor]) => {
        editor.on('text-change', function() {
            const input = document.getElementById(`${name}-input`);
            if (input) {
                input.value = editor.root.innerHTML;
            } else {
                console.error(`Hidden input with ID '${name}-input' not found.`);
            }
        });
    });

    /* --------------------------------------------------------------
   Application Method – show the correct extra field
   -------------------------------------------------------------- */
    function toggleApplicationMethod() {
        const method = document.querySelector('input[name="application_method"]:checked')?.value || 'form';
        document.querySelectorAll('.conditional-field').forEach(el => {
            el.style.display = (el.dataset.method === method) ? 'block' : 'none';
            // remove required when hidden
            const input = el.querySelector('input');
            if (input) {
                if (el.dataset.method === method) {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                    input.value = ''; // clear old value
                }
            }
        });
    }

    // run on page load (preserves old input after validation error)
    document.addEventListener('DOMContentLoaded', toggleApplicationMethod);

    // run when any radio changes
    document.querySelectorAll('input[name="application_method"]').forEach(radio => {
        radio.addEventListener('change', toggleApplicationMethod);
    });

    // Initialize salary input based on salary type
    toggleSalaryInput();

    // AJAX Form submission handler
    const form = document.querySelector('.add-product-form');
    const submitBtn = form.querySelector('button[type="submit"]');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Update all hidden inputs with current Quill content
            Object.entries(quillInstances).forEach(([name, editor]) => {
                document.getElementById(`${name}-input`).value = editor.root.innerHTML;
            });

            // ---- NEW: make sure the selected method has a value ----
            const method = document.querySelector('input[name="application_method"]:checked')?.value;
            if (!method) {
                toastr.error('Please select an Application Method.');
                e.preventDefault();
                return false;
            }

            // Validate application access type
            const accessType = document.querySelector('input[name="application_access"]:checked');
            if (!accessType) {
                toastr.error('Please select who can apply for this job.');
                return false;
            }

            if (['whatsapp', 'email', 'external'].includes(method)) {
                const field = document.querySelector(`input[name="${method === 'whatsapp' ? 'whatsapp_link' :
                                                         method === 'email' ? 'application_email' :
                                                         'external_url'}"]`);
                if (!field || !field.value.trim()) {
                    toastr.error(`Please fill the ${method} field.`);
                    field?.focus();
                    e.preventDefault();
                    return false;
                }
            }

            // Validate required rich text fields
            const descriptionContent = descriptionEditor.getText().trim();
            if (!descriptionContent || descriptionContent === 'Write your job description here...') {
                toastr.error('Job Description is required.');
                descriptionEditor.focus();
                return false;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Posting Job...';

            // Prepare form data
            const formData = new FormData(form);

            // Make AJAX request
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Reset loading state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Post Job';

                    // Check response (assuming response is JSON with status and message)
                    if (response.success) {
                        toastr.success(response.message || 'Job posted successfully!');
                        // Reset form
                        form.reset();
                        Object.values(quillInstances).forEach(editor => editor.setText(''));
                        // Optionally redirect to dashboard
                        setTimeout(() => {
                            window.location.href = '<?= site_url('employer/jobs') ?>';
                        }, 2000);
                    } else {
                        if (response.errors) {
                            Object.entries(response.errors).forEach(([field, error]) => {
                                // const input = form.querySelector(`[name="${field}"]`);
                                // if (input) {
                                //     input.classList.add('is-invalid');
                                //     input.nextElementSibling.textContent = error;
                                // }
                                toastr.error(error);
                            });
                        } else {
                            toastr.error(response.message || 'Failed to post job.');
                        }
                    }
                },
                error: function(xhr) {
                    // Reset loading state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Post Job';

                    // Handle errors
                    let errorMessage = 'An error occurred while posting the job.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                }
            });
        });
    }


    // Function to toggle salary input based on salary type
    function toggleSalaryInput() {
        const salaryType = document.getElementById('salary_type').value;
        const salaryInputContainer = document.getElementById('salary_input_container');
        const salaryInput = document.getElementById('salary_input');

        if (salaryType === 'negotiable') {
            salaryInputContainer.style.display = 'none';
            salaryInput.value = '';
            salaryInput.removeAttribute('required');
        } else {
            salaryInputContainer.style.display = 'block';
            if (salaryType === 'fixed') {
                salaryInput.placeholder = 'e.g., ₦500,000';
            } else if (salaryType === 'range') {
                salaryInput.placeholder = 'e.g., ₦250,000 - ₦750,000';
            }
            salaryInput.setAttribute('required', 'required');
        }
    }

    // --- Pre-screening Questions Logic ---
    let questionCount = 0;
    const questionsContainer = document.getElementById('questions-container');
    const addQuestionBtn = document.getElementById('add-question-btn');

    addQuestionBtn.addEventListener('click', function() {
        questionCount++;
        const questionHtml = `
            <div class="question-item border p-3 mb-3 rounded position-relative" id="question-${questionCount}">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-question" data-id="${questionCount}"></button>
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Question Text</label>
                            <input type="text" name="questions[${questionCount}][text]" class="form-control" placeholder="e.g., How many years of experience do you have with PHP?" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="questions[${questionCount}][type]" class="form-select question-type" data-id="${questionCount}">
                                <option value="text">Text</option>
                                <option value="select">Multiple Choice (Select One)</option>
                                <option value="radio">Radio Buttons</option>
                                <option value="checkbox">Checkboxes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row options-container" id="options-container-${questionCount}" style="display: none;">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Options (Comma separated)</label>
                            <input type="text" name="questions[${questionCount}][options]" class="form-control" placeholder="e.g., Yes, No, Maybe">
                        </div>
                    </div>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="questions[${questionCount}][is_required]" value="1" checked id="req-${questionCount}">
                    <label class="form-check-label" for="req-${questionCount}">Required</label>
                </div>
            </div>
        `;
        questionsContainer.insertAdjacentHTML('beforeend', questionHtml);
        feather.replace();
    });

    // Handle remove question and type change
    questionsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-question')) {
            const id = e.target.getAttribute('data-id');
            document.getElementById(`question-${id}`).remove();
        }
    });

    questionsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('question-type')) {
            const id = e.target.getAttribute('data-id');
            const optionsContainer = document.getElementById(`options-container-${id}`);
            if (['select', 'radio', 'checkbox'].includes(e.target.value)) {
                optionsContainer.style.display = 'block';
                optionsContainer.querySelector('input').setAttribute('required', 'required');
            } else {
                optionsContainer.style.display = 'none';
                optionsContainer.querySelector('input').removeAttribute('required');
            }
        }
    });

</script>
<?= $this->endSection() ?>