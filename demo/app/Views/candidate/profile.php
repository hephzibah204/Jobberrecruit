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

    /* ══ PROFILE CARDS ══ */
    .profile-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        margin-bottom: 24px;
    }
    .profile-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .profile-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .profile-card-title i {
        color: var(--brand);
    }
    .profile-card-body {
        padding: 24px;
    }

    /* Details styling */
    .info-label {
        font-size: .8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 4px;
        font-weight: 600;
    }
    .info-value {
        font-size: .95rem;
        color: var(--text-dark);
        font-weight: 500;
        margin-bottom: 16px;
    }

    .badge-tag {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .badge-tag-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        border-color: rgba(16, 185, 129, 0.2);
    }
    .badge-tag-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
        border-color: rgba(245, 158, 11, 0.2);
    }
    .badge-tag-brand {
        background: var(--brand-light);
        color: var(--brand);
        border-color: var(--border);
    }

    /* Skills Cloud */
    .skills-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .skill-badge {
        background: var(--bg-light);
        border: 1px solid var(--border-light);
        color: var(--text-dark);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .skill-badge:hover {
        border-color: var(--brand);
        background: var(--brand-light);
        color: var(--brand);
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
?>

<div class="content">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold text-gradient">Candidate Profile</h4>
                <h6 class="text-muted">View and manage your job seeker profile</h6>
            </div>
        </div>
        <div class="page-btn mt-0">
            <a href="<?= base_url('candidate/dashboard') ?>" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- STICKY PROFILE COMPLETION / TOASTER BAR -->
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

    <div class="row">
        <!-- Profile Overview (Left) -->
        <div class="col-lg-4 col-md-12">
            <div class="profile-card text-center">
                <div class="profile-card-body py-5">
                    <!-- Profile Image -->
                    <div class="profile-image mb-4">
                        <?php if (!empty($candidate->profile_picture)): ?>
                            <img src="<?= base_url($candidate->profile_picture) ?>"
                                alt="Profile Photo"
                                class="rounded-circle shadow-lg img-thumbnail"
                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid var(--brand);">
                        <?php else: ?>
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center shadow-sm"
                                style="width: 120px; height: 120px; border: 3px solid var(--border);">
                                <i data-feather="user" class="text-muted" style="width: 50px; height: 50px;"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h4 class="mb-1 text-gradient fw-bold"><?= esc($candidate->full_name ?? 'Not Set') ?></h4>
                    <p class="text-muted mb-4"><?= esc($user->email) ?></p>

                    <!-- Resume Badge -->
                    <div class="mb-4">
                        <?php if (!empty($candidate->resume)): ?>
                            <span class="badge-tag badge-tag-success">
                                <i data-feather="check-circle" class="me-1" style="width: 14px; height: 14px;"></i> Resume Uploaded
                            </span>
                        <?php else: ?>
                            <span class="badge-tag badge-tag-warning">
                                <i data-feather="alert-triangle" class="me-1" style="width: 14px; height: 14px;"></i> No Resume Uploaded
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2 px-3">
                        <a href="<?= base_url('candidate/profile/edit') ?>" class="btn btn-primary rounded-pill">
                            <i data-feather="edit-2" class="me-2" style="width: 16px; height: 16px;"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details (Right) -->
        <div class="col-lg-8 col-md-12">
            <!-- Personal Info Card -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5 class="profile-card-title"><i data-feather="user"></i> Personal Information</h5>
                </div>
                <div class="profile-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">Full Name</div>
                            <div class="info-value"><?= esc($candidate->full_name ?? 'Not Set') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Gender</div>
                            <div class="info-value"><?= esc(ucfirst($candidate->gender ?? 'Not Set')) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Date of Birth</div>
                            <div class="info-value"><?= esc(!empty($candidate->dob) ? date('M d, Y', strtotime($candidate->dob)) : 'Not Set') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">
                                <?php if (!empty($candidate->phone)): ?>
                                    <a href="tel:<?= esc($candidate->phone) ?>" class="text-decoration-none text-brand">
                                        <?= esc($candidate->phone) ?>
                                    </a>
                                <?php else: ?>
                                    Not Set
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Location (State)</div>
                            <div class="info-value"><?= esc(!empty($candidate->location) ? $candidate->location . ' State' : 'Not Set') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">User ID</div>
                            <div class="info-value"><?= esc($candidate->user_id) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Career Info Card -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5 class="profile-card-title"><i data-feather="briefcase"></i> Career & Professional Details</h5>
                </div>
                <div class="profile-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">Job Title</div>
                            <div class="info-value"><?= esc($candidate->job_title ?? 'Not Set') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Employment Type</div>
                            <div class="info-value"><?= esc($candidate->employment_type ?? 'Not Set') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Experience (Years)</div>
                            <div class="info-value"><?= esc(!empty($candidate->experience_years) ? $candidate->experience_years . ' Years' : 'Not Set') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Education Level</div>
                            <div class="info-value"><?= esc($candidate->education_level ?? 'Not Set') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Languages</div>
                            <div class="info-value"><?= esc($candidate->languages ?? 'Not Set') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Desired Salary</div>
                            <div class="info-value">
                                <?php if (!empty($candidate->desired_salary)): ?>
                                    ₦<?= number_format($candidate->desired_salary) ?> <span class="text-muted small">/ <?= esc($candidate->salary_type ?? 'monthly') ?></span>
                                <?php else: ?>
                                    Not Set
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="info-label">Portfolio Website</div>
                            <div class="info-value">
                                <?php if (!empty($candidate->portfolio)): ?>
                                    <a href="<?= esc($candidate->portfolio) ?>" target="_blank" class="text-brand text-decoration-none">
                                        <i data-feather="external-link" class="me-1" style="width: 14px; height: 14px;"></i> <?= esc($candidate->portfolio) ?>
                                    </a>
                                <?php else: ?>
                                    Not Set
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="info-label">Skills</div>
                            <div class="skills-cloud">
                                <?php if (!empty($candidate->skills)): ?>
                                    <?php 
                                    $skillsArr = array_map('trim', explode(',', $candidate->skills));
                                    foreach ($skillsArr as $skill): 
                                    ?>
                                        <span class="skill-badge"><?= esc($skill) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted small">No skills added</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="info-label">Professional Summary</div>
                            <div class="info-value mb-0" style="line-height: 1.6;">
                                <?= !empty($candidate->description) ? nl2br(esc($candidate->description)) : 'No professional summary added.' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5 class="profile-card-title"><i data-feather="file-text"></i> Documents</h5>
                </div>
                <div class="profile-card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i data-feather="file" class="text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Resume / Curriculum Vitae</h6>
                                <p class="text-muted small mb-0">
                                    <?php if (!empty($candidate->resume)): ?>
                                        PDF/Word document format
                                    <?php else: ?>
                                        No CV file uploaded yet.
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <?php if (!empty($candidate->resume)): ?>
                                <a href="<?= base_url($candidate->resume) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i data-feather="eye" class="me-1" style="width: 14px; height: 14px;"></i> View Resume
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('candidate/profile/edit') ?>" class="btn btn-warning btn-sm text-white">
                                    <i data-feather="upload" class="me-1" style="width: 14px; height: 14px;"></i> Upload Resume
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
<?= $this->endSection() ?>