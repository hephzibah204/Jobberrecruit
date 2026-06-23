<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --brand: #0D609E;
        --brand-dark: #0A4D7E;
        --brand-deep: #07304F;
        --brand-light: #E6F0F9;
        --accent: #F08F1A;
        --accent-dark: #C8750E;
        --accent-light: #FFF8F0;
        --white: #ffffff;
        --border: rgba(13, 96, 158, 0.15);
    }

    [data-theme="dark"] {
        --brand-light: rgba(13, 96, 158, 0.15);
        --accent-light: rgba(240, 143, 26, 0.1);
        --white: #0F172A;
        --border: rgba(255, 255, 255, 0.1);
    }

    /* ══ STICKY / RELATIVE PROGRESS BAR ══ */
    .profile-progress-bar {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
    }
    .progress-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }
    .progress-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
        min-width: 280px;
    }
    .progress-track {
        flex: 1;
        max-width: 360px;
        height: 10px;
        background: var(--border-light);
        border-radius: 20px;
        position: relative;
    }
    [data-theme="dark"] .progress-track {
        background: rgba(255, 255, 255, 0.05);
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--brand), #2575fc);
        border-radius: 20px;
        transition: width .6s ease;
    }
    .progress-text {
        font-size: .88rem;
        font-weight: 700;
        color: var(--text-dark);
        white-space: nowrap;
    }
    .progress-tip {
        font-size: .8rem;
        color: var(--accent-dark);
        font-weight: 600;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .progress-tip svg {
        width: 14px;
        height: 14px;
    }
    .progress-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    /* Milestone markers on track */
    .milestone-marker {
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 16px;
        height: 16px;
        border-radius: 50%;
        z-index: 2;
        background: var(--bg-white);
        border: 2px solid #c8dff2;
        transition: background .35s ease, border-color .35s ease, box-shadow .35s ease;
    }
    .milestone-marker.achieved {
        background: var(--accent);
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(240, 143, 26, 0.22);
    }
    .milestone-marker.next {
        border-color: var(--accent);
        animation: marker-pulse 1.6s ease infinite;
    }
    @keyframes marker-pulse {
        0%,100% { box-shadow: 0 0 0 0 rgba(240, 143, 26, 0.4); }
        50% { box-shadow: 0 0 0 6px rgba(240, 143, 26, 0); }
    }
    .milestone-label {
        position: absolute;
        bottom: 18px;
        left: 50%;
        transform: translateX(-50%);
        font-size: .65rem;
        font-weight: 700;
        white-space: nowrap;
        letter-spacing: .03em;
        color: var(--text-muted);
        transition: color .35s ease;
    }
    .milestone-marker.achieved .milestone-label,
    .milestone-marker.next .milestone-label {
        color: var(--accent-dark);
    }

    /* Wallet balance chip */
    .wallet-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--brand-deep);
        color: #ffffff;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: .82rem;
        font-weight: 700;
        cursor: default;
        user-select: none;
    }
    .wallet-chip svg {
        width: 14px;
        height: 14px;
    }
    .wallet-chip.has-balance {
        background: linear-gradient(120deg, var(--accent-dark), var(--accent));
        color: var(--brand-deep);
    }

    /* ══ PROFILE ACCORDION CARDS ══ */
    .cv-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-left: 4px solid var(--accent);
        border-radius: 12px;
        margin-bottom: 24px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: border-color 0.3s ease;
    }
    .cv-card-header {
        padding: 20px 24px;
        background: var(--bg-white);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }
    .cv-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .cv-card-title i {
        color: var(--brand);
    }
    .cv-card-body {
        padding: 24px;
    }
    .cv-card-hint {
        font-size: .84rem;
        color: var(--text-muted);
        background: var(--brand-light);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    /* Section status badges */
    .cv-card-done {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding: 3px 9px;
        border-radius: 20px;
        flex-shrink: 0;
        white-space: nowrap;
        margin-left: auto;
        margin-right: 12px;
    }
    .cv-card-done.complete  {
        background: var(--brand-light);
        color: var(--brand);
        border: 1px solid #c8dff2;
    }
    .cv-card-done.incomplete {
        background: rgba(240, 143, 26, 0.1);
        color: var(--accent-dark);
        border: 1px solid rgba(240, 143, 26, 0.2);
    }
    .cv-card-done.optional  {
        background: var(--bg-light);
        color: var(--text-muted);
        border: 1px solid var(--border);
    }
    /* Border flips from orange → brand blue when a section is complete */
    .cv-card.is-complete {
        border-left-color: var(--brand);
    }

    .form-control, .form-select, select {
        border: 1px solid var(--border);
        background-color: var(--bg-white);
        color: var(--text-dark);
        border-radius: 8px;
        padding: 10px 14px;
        font-size: .9rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus, .form-select:focus, select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(13, 96, 158, 0.15);
        background-color: var(--bg-white);
        color: var(--text-dark);
    }

    .form-label {
        font-weight: 600;
        font-size: .85rem;
        color: var(--text-dark);
        margin-bottom: 6px;
    }

    .text-danger {
        color: var(--danger-color) !important;
    }

    /* Photo Upload Grid */
    .photo-upload-container {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .photo-preview-wrapper {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 2px solid var(--brand);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-light);
    }
    .photo-preview-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
$fields = [
    'full_name',
    'dob',
    'gender',
    'phone',
    'location',
    'job_title',
    'employment_type',
    'skills',
    'experience_years',
    'education_level',
];

$completed = 0;
foreach ($fields as $f) {
    if (!empty($candidate->$f)) $completed++;
}
if (!empty($candidate->resume)) $completed++;
$totalFields = count($fields) + 1;
$completion = round(($completed / $totalFields) * 100);

// Calculate section-by-section completion status
$basicComplete = !empty($candidate->full_name) && !empty($candidate->phone) && !empty($candidate->state_id);
$careerComplete = !empty($candidate->job_title) && !empty($candidateIndustryIds);
$docsComplete = !empty($candidate->resume);
?>

<div class="content">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Edit Candidate Profile</h4>
                <h6>Update your personal, career, and document information</h6>
            </div>
        </div>
        <div class="page-btn mt-0">
            <a href="<?= base_url('candidate/profile') ?>" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-2"></i>Back to Profile
            </a>
        </div>
    </div>

    <!-- PROFILE PROGRESS & WALLET BANNER -->
    <div class="profile-progress-bar">
        <div class="progress-inner">
            <div class="progress-left">
                <div class="progress-track" aria-hidden="true">
                    <div class="progress-fill" style="width: <?= $completion ?>%;"></div>
                    <div class="milestone-marker <?= $completion >= 60 ? 'achieved' : 'next' ?>" style="left: 60%;" title="Earn ₦200 at 60%">
                        <span class="milestone-label">₦200</span>
                    </div>
                    <div class="milestone-marker <?= $completion >= 80 ? 'achieved' : ($completion >= 60 ? 'next' : '') ?>" style="left: 80%;" title="Earn ₦500 at 80%">
                        <span class="milestone-label">₦500</span>
                    </div>
                </div>
                <span class="progress-text"><?= $completion ?>% Completed</span>
                <span class="progress-tip">
                    <i data-feather="zap"></i>
                    <span>
                        <?php if ($completion < 60): ?>
                            Complete <?= 60 - $completion ?>% more to unlock ₦200
                        <?php elseif ($completion < 80): ?>
                            Nice! Complete <?= 80 - $completion ?>% more to unlock ₦500
                        <?php else: ?>
                            All profile milestones achieved!
                        <?php endif; ?>
                    </span>
                </span>
            </div>
            <div class="progress-actions">
                <span class="wallet-chip <?= $walletBalance > 0 ? 'has-balance' : '' ?>">
                    <i data-feather="credit-card"></i>
                    <span class="wallet-label">Wallet:</span>
                    <span>₦<?= number_format($walletBalance, 2) ?></span>
                </span>
            </div>
        </div>
    </div>

    <form action="<?= base_url('candidate/profile/edit') ?>"
        method="POST"
        enctype="multipart/form-data"
        id="editCandidateForm">

        <?= csrf_field() ?>

        <div class="add-candidate">
            <!-- 1. BASIC INFORMATION -->
            <div class="cv-card <?= $basicComplete ? 'is-complete' : '' ?>">
                <div class="cv-card-header" data-bs-toggle="collapse" data-bs-target="#BasicInfo" aria-expanded="true">
                    <h5 class="cv-card-title"><i data-feather="user"></i> Basic Information</h5>
                    <div class="d-flex align-items-center gap-2">
                        <?php if ($basicComplete): ?>
                            <span class="cv-card-done complete"><i data-feather="check" style="width: 10px; height: 10px;"></i> Complete</span>
                        <?php else: ?>
                            <span class="cv-card-done incomplete">Incomplete</span>
                        <?php endif; ?>
                        <i data-feather="chevron-down" class="cv-chev"></i>
                    </div>
                </div>
                <div id="BasicInfo" class="collapse show">
                    <div class="cv-card-body">
                        <div class="cv-card-hint">
                            Provide your core contact details. Ensure your phone number is correct so recruiters can reach you easily.
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">User ID</label>
                                <input type="text" class="form-control" value="<?= esc($candidate->user_id) ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name<span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control"
                                    value="<?= old('full_name', $candidate->full_name) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control"
                                    value="<?= old('dob', $candidate->dob) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="" selected disabled>Select</option>
                                    <option value="male" <?= $candidate->gender == 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= $candidate->gender == 'female' ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= $candidate->gender == 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number<span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control"
                                    value="<?= old('phone', $candidate->phone) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State of Residence<span class="text-danger">*</span></label>
                                <select name="state_id" class="form-select" required>
                                    <option value="">Select State</option>
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?= $state->id ?>"
                                            <?= $candidate->state_id == $state->id ? 'selected' : '' ?>>
                                            <?= esc($state->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State Area / Detailed Address</label>
                                <input type="text" name="location" class="form-control"
                                    value="<?= old('location', $candidate->location) ?>" placeholder="e.g. Ikeja, Lagos">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Availability</label>
                                <select name="availability" class="form-select">
                                    <option value="" disabled selected>Select</option>
                                    <option value="immediately" <?= $candidate->availability == 'immediately' ? 'selected' : '' ?>>Immediately</option>
                                    <option value="1-week" <?= $candidate->availability == '1-week' ? 'selected' : '' ?>>1 Week</option>
                                    <option value="1-month" <?= $candidate->availability == '1-month' ? 'selected' : '' ?>>1 Month</option>
                                    <option value="2-months" <?= $candidate->availability == '2-months' ? 'selected' : '' ?>>2 Months</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Short Bio / Professional Summary</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Summarize your career, years of experience, and key value..."><?= old('description', $candidate->description ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. CAREER INFORMATION -->
            <div class="cv-card <?= $careerComplete ? 'is-complete' : '' ?>">
                <div class="cv-card-header" data-bs-toggle="collapse" data-bs-target="#CareerInfo" aria-expanded="true">
                    <h5 class="cv-card-title"><i data-feather="briefcase"></i> Career & Professional Details</h5>
                    <div class="d-flex align-items-center gap-2">
                        <?php if ($careerComplete): ?>
                            <span class="cv-card-done complete"><i data-feather="check" style="width: 10px; height: 10px;"></i> Complete</span>
                        <?php else: ?>
                            <span class="cv-card-done incomplete">Incomplete</span>
                        <?php endif; ?>
                        <i data-feather="chevron-down" class="cv-chev"></i>
                    </div>
                </div>
                <div id="CareerInfo" class="collapse show">
                    <div class="cv-card-body">
                        <div class="cv-card-hint">
                            Highlight your professional background, target job title, target industries, and skills below.
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Target Job Title<span class="text-danger">*</span></label>
                                <input type="text" name="job_title" class="form-control"
                                    value="<?= old('job_title', $candidate->job_title) ?>" placeholder="e.g. Senior Software Engineer" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preferred Employment Type</label>
                                <select name="employment_type" class="form-select">
                                    <option value="">Select</option>
                                    <option value="Full Time" <?= $candidate->employment_type == 'Full Time' ? 'selected' : '' ?>>Full Time</option>
                                    <option value="Part Time" <?= $candidate->employment_type == 'Part Time' ? 'selected' : '' ?>>Part Time</option>
                                    <option value="Remote" <?= $candidate->employment_type == 'Remote' ? 'selected' : '' ?>>Remote</option>
                                    <option value="Contract" <?= $candidate->employment_type == 'Contract' ? 'selected' : '' ?>>Contract</option>
                                    <option value="Internship" <?= $candidate->employment_type == 'Internship' ? 'selected' : '' ?>>Internship</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Years of Experience</label>
                                <input type="number" name="experience_years" class="form-control"
                                    value="<?= old('experience_years', $candidate->experience_years) ?>" placeholder="e.g. 5">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Highest Education Level</label>
                                <select name="education_level" class="form-select">
                                    <option value="" disabled selected>Select level</option>
                                    <option value="High School" <?= $candidate->education_level == 'High School' ? 'selected' : '' ?>>High School</option>
                                    <option value="Undergraduate" <?= $candidate->education_level == 'Undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
                                    <option value="Diploma" <?= $candidate->education_level == 'Diploma' ? 'selected' : '' ?>>Diploma</option>
                                    <option value="Bachelor's Degree" <?= $candidate->education_level == "Bachelor's Degree" ? 'selected' : '' ?>>Bachelor's Degree</option>
                                    <option value="Master's Degree" <?= $candidate->education_level == "Master's Degree" ? 'selected' : '' ?>>Master's Degree</option>
                                    <option value="PhD" <?= $candidate->education_level == 'PhD' ? 'selected' : '' ?>>PhD</option>
                                    <option value="Professional Certification" <?= $candidate->education_level == 'Professional Certification' ? 'selected' : '' ?>>Professional Certification</option>
                                    <option value="Others" <?= $candidate->education_level == 'Others' ? 'selected' : '' ?>>Others</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Desired Salary (Amount in ₦)</label>
                                <input type="number" name="desired_salary" class="form-control"
                                    value="<?= old('desired_salary', $candidate->desired_salary) ?>" placeholder="e.g. 250000">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Salary Period</label>
                                <select name="salary_type" class="form-select">
                                    <option value="" disabled selected>Select</option>
                                    <option value="hourly" <?= ($candidate->salary_type ?? '') == 'hourly' ? 'selected' : '' ?>>Hourly</option>
                                    <option value="monthly" <?= ($candidate->salary_type ?? '') == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                    <option value="yearly" <?= ($candidate->salary_type ?? '') == 'yearly' ? 'selected' : '' ?>>Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Languages (Comma separated)</label>
                                <input type="text" name="languages" class="form-control"
                                    value="<?= old('languages', $candidate->languages) ?>" placeholder="English, Yoruba, French">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Portfolio Website URL</label>
                                <input type="text" name="portfolio" class="form-control"
                                    value="<?= old('portfolio', $candidate->portfolio) ?>" placeholder="https://myportfolio.com">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Skills (Comma separated list)</label>
                                <textarea name="skills" class="form-control" rows="3"
                                    placeholder="e.g. PHP, UI/UX Design, Figma, React, Communication"><?= old('skills', $candidate->skills) ?></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Target Industries (Hold Ctrl/Cmd to select multiple)<span class="text-danger">*</span></label>
                                <select class="form-select select2" name="industry_ids[]" multiple required style="min-height: 120px;">
                                    <?php foreach ($industries as $industry): ?>
                                        <optgroup label="<?= esc($industry->name) ?>">
                                            <?php foreach ($industry->children as $child): ?>
                                                <option value="<?= $child->id ?>"
                                                    <?= in_array($child->id, $candidateIndustryIds ?? []) ? 'selected' : '' ?>>
                                                    <?= esc($child->name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. DOCUMENTS & UPLOADS -->
            <div class="cv-card <?= $docsComplete ? 'is-complete' : '' ?>">
                <div class="cv-card-header" data-bs-toggle="collapse" data-bs-target="#Files" aria-expanded="true">
                    <h5 class="cv-card-title"><i data-feather="file"></i> Profile Picture & Resume</h5>
                    <div class="d-flex align-items-center gap-2">
                        <?php if ($docsComplete): ?>
                            <span class="cv-card-done complete"><i data-feather="check" style="width: 10px; height: 10px;"></i> Complete</span>
                        <?php else: ?>
                            <span class="cv-card-done optional">Optional</span>
                        <?php endif; ?>
                        <i data-feather="chevron-down" class="cv-chev"></i>
                    </div>
                </div>
                <div id="Files" class="collapse show">
                    <div class="cv-card-body">
                        <div class="cv-card-hint">
                            Upload a professional headshot and your latest resume in PDF or Word format.
                        </div>
                        <div class="row">
                            <!-- PROFILE PICTURE -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Profile Picture</label>
                                <div class="photo-upload-container mb-3">
                                    <div class="photo-preview-wrapper">
                                        <?php if (!empty($candidate->profile_picture)): ?>
                                            <img src="<?= base_url($candidate->profile_picture) ?>" id="currentProfilePic" alt="Profile pic">
                                        <?php else: ?>
                                            <i data-feather="user" class="text-muted" style="width: 40px; height: 40px;"></i>
                                        <?php endif; ?>
                                        <img id="profilePreviewImg" style="display: none; width: 100%; height: 100%; object-fit: cover;" alt="">
                                    </div>
                                    <div>
                                        <input type="file" name="profile_picture" id="profileInput" accept="image/*" class="form-control mb-2">
                                        <?php if (!empty($candidate->profile_picture)): ?>
                                            <div class="form-check">
                                                <input type="checkbox" name="remove_profile_picture" id="removePic" class="form-check-input" value="1">
                                                <label for="removePic" class="form-check-label text-muted small">Remove current picture</label>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- RESUME -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Resume / CV Document</label>
                                <div class="mb-3">
                                    <input type="file" name="resume" id="resumeInput" accept=".pdf,.doc,.docx" class="form-control mb-2">
                                    <?php if (!empty($candidate->resume)): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="<?= base_url($candidate->resume) ?>" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2">
                                                <i data-feather="file-text" class="me-1" style="width: 14px; height: 14px;"></i> View Current CV
                                            </a>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" name="remove_resume" id="removeResume" class="form-check-input" value="1">
                                                <label for="removeResume" class="form-check-label text-muted small">Remove CV</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div id="resumePreview" style="display: none;" class="mt-2">
                                    <div class="border rounded p-2 d-inline-flex align-items-center">
                                        <i data-feather="file" class="text-primary me-2"></i>
                                        <span id="resumePreviewText" class="small"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SAVE BUTTON -->
        <div class="d-flex justify-content-end mb-5 gap-3">
            <a href="<?= base_url('candidate/profile') ?>" class="btn btn-secondary px-4 py-2">Cancel</a>
            <button type="submit" class="btn btn-primary px-4 py-2" id="submitBtn">
                <span class="btn-text">Update Profile</span>
                <span class="spinner-border spinner-border-sm d-none"></span>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Initialize Select2 if exists
        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: "Select industries",
                width: '100%'
            });
        }

        // Portfolio auto-prefix + validation
        const portfolioInput = $('input[name="portfolio"]');
        portfolioInput.on('blur', function() {
            let value = $(this).val().trim();
            if (!value) {
                $(this).removeClass('is-invalid');
                return;
            }
            if (!/^https?:\/\//i.test(value)) {
                value = 'https://' + value;
            }
            try {
                const url = new URL(value);
                const hostname = url.hostname;
                if (!hostname.includes('.') || hostname.split('.').pop().length < 2) {
                    throw new Error('Invalid domain');
                }
                $(this).val(value);
                $(this).removeClass('is-invalid');
            } catch (e) {
                $(this).addClass('is-invalid');
                toastr.error('Please enter a valid portfolio URL (e.g. example.com)');
            }
        });

        // Profile Picture Preview
        $('#profileInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#profilePreviewImg').attr('src', e.target.result).show();
                    $('#currentProfilePic').hide();
                };
                reader.readAsDataURL(file);
            } else if (file) {
                toastr.warning("Invalid image file selected.");
                $(this).val('');
            }
        });

        // Resume Preview
        $('#resumeInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                $('#resumePreviewText').text(file.name);
                $('#resumePreview').show();
            } else {
                $('#resumePreview').hide();
            }
        });

        // Ajax form submit
        $('#editCandidateForm').on('submit', function(e) {
            e.preventDefault();

            let btn = $('#submitBtn'),
                text = btn.find('.btn-text'),
                spin = btn.find('.spinner-border');

            btn.prop('disabled', true);
            text.addClass('d-none');
            spin.removeClass('d-none');

            // Website Normalization/Validation
            let portfolioVal = portfolioInput.val().trim();
            if (portfolioVal) {
                if (!/^https?:\/\//i.test(portfolioVal)) {
                    portfolioVal = 'https://' + portfolioVal;
                }
                try {
                    const url = new URL(portfolioVal);
                    if (!url.hostname.includes('.') || url.hostname.split('.').pop().length < 2) {
                        throw new Error();
                    }
                    portfolioInput.val(portfolioVal);
                } catch {
                    toastr.error('Please enter a valid portfolio URL.');
                    portfolioInput.addClass('is-invalid');
                    btn.prop('disabled', false);
                    text.removeClass('d-none');
                    spin.addClass('d-none');
                    return;
                }
            }

            const formData = new FormData(this);
            $.ajax({
                url: this.action,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    btn.prop('disabled', false);
                    text.removeClass('d-none');
                    spin.addClass('d-none');

                    if (res.status === 'error') {
                        toastr.error(res.message);
                    } else {
                        toastr.success(res.message);
                        window.location.href = "<?= base_url('candidate/profile') ?>";
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false);
                    text.removeClass('d-none');
                    spin.addClass('d-none');

                    let msg = "Something went wrong.";
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join("<br>");
                    }
                    toastr.error(msg);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>