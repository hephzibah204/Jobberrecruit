<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <!-- PAGE HEADER -->
    <div class="page-header tr-header-band">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold text-white">Company Profile</h4>
                <h6 class="text-white-50">View and manage your company profile details</h6>
            </div>
        </div>
        <div class="page-btn mt-0">
            <a href="<?= base_url('employer/dashboard') ?>" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to Dashboard</a>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Column: Overview, Plan & Progress -->
        <div class="col-lg-4 col-md-12">
            <!-- Profile Overview Card -->
            <div class="cv-card mb-4 is-complete">
                <div class="cv-card-body text-center pt-4">
                    <div class="profile-image mb-3 d-flex justify-content-center">
                        <?php if (!empty($employer->logo)): ?>
                            <img src="<?= base_url($employer->logo) ?>" alt="Company Logo" class="rounded-3 border" style="width: 100px; height: 100px; object-fit: contain; padding: 4px; background: #fff;">
                        <?php else: ?>
                            <div class="rounded-3 bg-light d-inline-flex align-items-center justify-content-center border" style="width: 100px; height: 100px;">
                                <i data-feather="building" class="text-muted" style="width: 48px; height: 48px;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h4 class="mb-1 text-dark fw-bold d-flex align-items-center justify-content-center gap-1">
                        <?= esc($employer->company_name ?? 'Not Set') ?>
                        <?php if ($canShowTrustBadge ?? false): ?>
                            <img src="<?= base_url('images/badge.svg') ?>"
                                 data-bs-toggle="tooltip"
                                 width="20"
                                 title="This employer is verified and subscribed to a trusted plan">
                        <?php endif; ?>
                    </h4>
                    
                    <p class="text-muted mb-3 small"><?= esc($employer->contact_email ?? $user->email) ?></p>

                    <div class="verification-badge mb-3">
                        <?php if ($employer->is_verified ?? false): ?>
                            <span class="badge bg-success-transparent text-success border border-success-20 px-3 py-2 rounded-pill">
                                <i data-feather="shield" class="me-1" style="width: 14px; height: 14px;"></i>
                                Verified
                            </span>
                        <?php elseif ($hasCACDocument && $cacDocument && (($cacDocument['status'] ?? $cacDocument->status ?? '') == 'pending')): ?>
                            <span class="badge bg-warning-transparent text-warning border border-warning-20 px-3 py-2 rounded-pill">
                                <i data-feather="clock" class="me-1" style="width: 14px; height: 14px;"></i>
                                Pending Review
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary-transparent text-muted border px-3 py-2 rounded-pill">
                                <i data-feather="alert-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                                Not Verified
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="<?= base_url('employer/profile/edit') ?>" class="btn btn-primary w-100">
                            <i data-feather="edit" class="me-2" style="width: 16px; height: 16px;"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Completion Progress Card -->
            <div class="cv-card mb-4 is-complete">
                <div class="cv-card-header">
                    <div class="cv-card-title">
                        <i data-feather="check-circle" class="text-primary me-2"></i>
                        <span>Profile Completion</span>
                    </div>
                </div>
                <div class="cv-card-body">
                    <?php
                    $fields = ['company_name', 'company_size', 'website', 'state_id', 'description', 'contact_name', 'contact_email', 'contact_phone'];
                    $completed = 0;
                    foreach ($fields as $f) {
                        if (!empty($employer->$f)) $completed++;
                    }
                    $industryCount = isset($employer->industries) ? count($employer->industries) : 0;
                    if ($industryCount > 0) $completed++;
                    if ($hasCACDocument ?? false) $completed++;

                    $totalFields = count($fields) + 2;
                    $completion = round(($completed / $totalFields) * 100);
                    ?>
                    <div class="progress mb-2" style="height: 8px; border-radius: 20px; background: #e2e8f0;">
                        <div class="progress-bar" style="width: <?= $completion ?>%; background: linear-gradient(90deg, var(--brand), #1a7fd4); border-radius: 20px;" role="progressbar"></div>
                    </div>
                    <p class="text-center mb-0 fw-bold text-dark"><?= $completion ?>% Complete</p>
                    <small class="text-muted text-center d-block"><?= $completed ?> of <?= $totalFields ?> fields completed</small>

                    <?php if (!($hasCACDocument ?? false)): ?>
                        <div class="alert alert-warning mt-3 mb-0 small border border-warning-20">
                            <i data-feather="info" class="me-1 text-warning" style="width: 14px; height: 14px;"></i>
                            Upload your CAC certificate to complete verification.
                            <a href="<?= base_url('employer/profile/upload-document') ?>" class="alert-link text-warning fw-bold">Upload now</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Account Summary Card -->
            <div class="cv-card mb-4 is-complete">
                <div class="cv-card-header">
                    <div class="cv-card-title">
                        <i data-feather="award" class="text-primary me-2"></i>
                        <span>Account Summary</span>
                    </div>
                </div>
                <div class="cv-card-body">
                    <?php if ($hasUnlimitedAccess ?? false): ?>
                        <div class="text-center mb-3">
                            <div class="alert alert-success border border-success-20 mb-0">
                                <i data-feather="infinity" class="me-2 text-success" style="width: 24px; height: 24px;"></i>
                                <strong class="text-success d-block mt-1">Unlimited Access Plan</strong>
                                <p class="mb-0 small text-muted">Enterprise account with unlimited job postings</p>
                                <?php if (!empty($employer->unlimited_until)): ?>
                                    <hr class="my-2 opacity-10">
                                    <small class="text-dark fw-semibold">Valid until: <?= date('M d, Y', strtotime($employer->unlimited_until)) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Current Subscription Plan -->
                        <div class="mb-3">
                            <label class="form-label text-muted small uppercase fw-bold">Current Plan</label>
                            <?php
                            $hasActivePlan = false;
                            $planName = '';
                            $planEndsAt = '';
                            $planFeatures = [];

                            if (!empty($activeSubscription)) {
                                if (is_object($activeSubscription)) {
                                    $hasActivePlan = !empty($activeSubscription->plan_name);
                                    $planName = $activeSubscription->plan_name ?? '';
                                    $planEndsAt = $activeSubscription->ends_at ?? '';
                                    $planFeatures = $activeSubscription->features_array ?? [];
                                } else {
                                    $hasActivePlan = !empty($activeSubscription['plan_name']);
                                    $planName = $activeSubscription['plan_name'] ?? '';
                                    $planEndsAt = $activeSubscription['ends_at'] ?? '';
                                    $planFeatures = $activeSubscription['features_array'] ?? [];
                                }
                            }
                            ?>

                            <?php if ($hasActivePlan): ?>
                                <h5 class="mb-1 text-dark fw-bold"><?= esc($planName) ?></h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Expires: <?= date('M d, Y', strtotime($planEndsAt)) ?>
                                    </small>
                                    <a href="<?= base_url('employer/pricing') ?>" class="btn btn-sm btn-outline-primary py-1">
                                        Renew
                                    </a>
                                </div>
                                <?php if (!empty($planFeatures)): ?>
                                    <div class="mt-2">
                                        <?php foreach ($planFeatures as $feature => $enabled): ?>
                                            <?php if ($enabled): ?>
                                                <span class="badge bg-light text-primary border me-1 mb-1 small">
                                                    <?= ucwords(str_replace('_', ' ', $feature)) ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-muted small mb-0">No active subscription plan</p>
                                <a href="<?= base_url('employer/pricing') ?>" class="btn btn-accent btn-sm mt-2">
                                    Subscribe Now
                                </a>
                            <?php endif; ?>
                        </div>

                        <hr class="opacity-10 my-3">

                        <!-- Job Credits -->
                        <div>
                            <label class="form-label text-muted small uppercase fw-bold">Job Posting Credits</label>
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="mb-0 text-primary fw-bold"><?= number_format($creditBalance ?? 0) ?></h3>
                                <a href="<?= base_url('employer/pricing') ?>" class="btn btn-sm btn-outline-success py-1">
                                    <i data-feather="plus-circle" class="me-1" style="width: 14px; height: 14px;"></i> Buy Credits
                                </a>
                            </div>
                            <small class="text-muted small d-block mt-1">Credits do not expire.</small>

                            <?php if (($creditBalance ?? 0) == 0 && !$hasActivePlan): ?>
                                <div class="alert alert-warning mt-2 mb-0 small border border-warning-20">
                                    <i data-feather="alert-triangle" class="me-1 text-warning" style="width: 14px; height: 14px;"></i>
                                    Please subscribe or purchase credits to post job openings.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Details Column -->
        <div class="col-lg-8 col-md-12">
            <!-- Company Information Card -->
            <div class="cv-card mb-4 is-complete">
                <div class="cv-card-header">
                    <div class="cv-card-title">
                        <i data-feather="building" class="text-primary me-2"></i>
                        <span>Company Information</span>
                    </div>
                </div>
                <div class="cv-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">Company Name</label>
                            <p class="mb-0 fw-bold text-dark"><?= esc($employer->company_name ?? 'Not Set') ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">Industries</label>
                            <p class="mb-0">
                                <?php if (!empty($employer->industries)): ?>
                                    <?php foreach ($employer->industries as $ind): ?>
                                        <span class="badge bg-light text-dark border me-1 my-1"><?= esc($ind->name) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted small">Not Set</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">Company Size</label>
                            <p class="mb-0 fw-bold text-dark"><?= esc($employer->company_size ?? 'Not Set') ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">Website</label>
                            <p class="mb-0">
                                <?php if (!empty($employer->website)): ?>
                                    <a href="<?= esc($employer->website) ?>" target="_blank" class="fw-bold text-primary"><?= esc($employer->website) ?> <i data-feather="external-link" style="width: 12px; height: 12px; vertical-align: middle;"></i></a>
                                <?php else: ?>
                                    <span class="text-muted small">Not Set</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">State / Location</label>
                            <p class="mb-0 fw-bold text-dark">
                                <?= esc($employer->location ? $employer->location . ' State' : 'Not Set') ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">User ID Reference</label>
                            <p class="mb-0 text-muted small code"><?= esc($employer->user_id) ?></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small uppercase">Company Description</label>
                        <div class="border rounded p-3 bg-light text-dark description-box" style="line-height: 1.6;">
                            <?= !empty($employer->description)
                                ? nl2br(esc($employer->description))
                                : '<p class="text-muted mb-0 small">No description provided</p>' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="cv-card mb-4 is-complete">
                <div class="cv-card-header">
                    <div class="cv-card-title">
                        <i data-feather="mail" class="text-primary me-2"></i>
                        <span>Contact Information</span>
                    </div>
                </div>
                <div class="cv-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">Contact Person Name</label>
                            <p class="mb-0 fw-bold text-dark"><?= esc($employer->contact_name ?? 'Not Set') ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">Contact Email Address</label>
                            <p class="mb-0">
                                <?php if (!empty($employer->contact_email)): ?>
                                    <a href="mailto:<?= esc($employer->contact_email) ?>" class="fw-bold text-primary"><?= esc($employer->contact_email) ?></a>
                                <?php else: ?>
                                    <span class="text-muted small">Not Set</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">Contact Phone Number</label>
                            <p class="mb-0">
                                <?php if (!empty($employer->contact_phone)): ?>
                                    <a href="tel:<?= esc($employer->contact_phone) ?>" class="fw-bold text-dark"><?= esc($employer->contact_phone) ?></a>
                                <?php else: ?>
                                    <span class="text-muted small">Not Set</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small uppercase">Company Physical Address</label>
                            <p class="mb-0 fw-bold text-dark"><?= esc($employer->company_address ?? 'Not Set') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CAC Document Verification Card -->
            <div class="cv-card mb-4 is-complete">
                <div class="cv-card-header">
                    <div class="cv-card-title">
                        <i data-feather="shield-check" class="text-primary me-2"></i>
                        <span>CAC Certificate Verification</span>
                    </div>
                </div>
                <div class="cv-card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <label class="form-label text-muted small uppercase">CAC Certificate Document</label>
                            <p class="mb-0 mt-1">
                                <?php
                                $cacFilePath = '';
                                $cacStatus = '';
                                if ($hasCACDocument && $cacDocument):
                                    if (is_array($cacDocument)) {
                                        $cacFilePath = $cacDocument['file_path'] ?? '';
                                        $cacStatus = $cacDocument['status'] ?? '';
                                    } else {
                                        $cacFilePath = $cacDocument->file_path ?? '';
                                        $cacStatus = $cacDocument->status ?? '';
                                    }
                                ?>
                                    <a href="<?= base_url($cacFilePath) ?>" target="_blank" class="btn btn-outline-primary btn-sm my-1">
                                        <i data-feather="file-text" class="me-1" style="width: 14px; height: 14px;"></i> View Uploaded CAC Certificate
                                    </a>
                                    <span class="badge bg-<?= $cacStatus == 'approved' ? 'success' : ($cacStatus == 'pending' ? 'warning' : 'danger') ?>-transparent text-<?= $cacStatus == 'approved' ? 'success' : ($cacStatus == 'pending' ? 'warning' : 'danger') ?> border border-20 ms-2 py-1 px-2 rounded-pill small">
                                        <?= ucfirst($cacStatus) ?>
                                    </span>
                                    <?php if ($cacStatus == 'rejected' && !empty($employer->rejection_reason)): ?>
                                        <div class="alert alert-danger mt-2 mb-0 small border border-danger-20">
                                            <strong>Rejection Reason:</strong> <?= esc($employer->rejection_reason) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-danger small fw-bold">
                                        <i data-feather="alert-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                                        No CAC certificate uploaded
                                    </span>
                                <?php endif; ?>
                            </p>
                            <small class="text-muted d-block mt-2">
                                CAC business registration certificate is mandatory for verification and premium postings.
                            </small>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if (!($hasCACDocument ?? false)): ?>
                                <a href="<?= base_url('employer/profile/upload-document') ?>" class="btn btn-primary btn-sm">
                                    <i data-feather="upload" class="me-1" style="width: 14px; height: 14px;"></i> Upload CAC File
                                </a>
                            <?php elseif ($cacDocument && ($cacStatus ?? '') == 'rejected'): ?>
                                <a href="<?= base_url('employer/profile/upload-document') ?>" class="btn btn-warning btn-sm">
                                    <i data-feather="refresh-cw" class="me-1" style="width: 14px; height: 14px;"></i> Re-upload Document
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
        if (typeof feather !== 'undefined') feather.replace();
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* ═══════════════════════════════════════════════════════════════════
       EMPLOYER PROFILE STYLING — Premium Blue & Orange Accents
       ═══════════════════════════════════════════════════════════════════ */
    :root {
        --brand:        #0D609E;
        --brand-dark:   #0A4D7E;
        --brand-deep:   #07304F;
        --brand-light:  #E6F0F9;
        --accent:       #F08F1A;
        --accent-dark:  #C8750E;
        --border:       #e2e8f0;
        --radius:       12px;
        --transition:   .2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Gradients and header tags */
    .tr-header-band {
        background: linear-gradient(135deg, var(--brand-deep) 0%, var(--brand-dark) 50%, var(--brand) 100%);
        padding: 24px 28px;
        border-radius: 12px;
        margin-bottom: 28px;
        box-shadow: 0 4px 20px rgba(13, 96, 158, 0.15);
    }

    /* Premium CV section card overlays */
    .cv-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-left: 4px solid var(--accent);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 4px 18px rgba(13, 96, 158, 0.04);
        transition: var(--transition);
    }
    .cv-card:hover {
        box-shadow: 0 8px 30px rgba(13, 96, 158, 0.08);
        border-color: var(--brand);
        border-left-color: var(--brand);
    }
    .cv-card.is-complete {
        border-left-color: var(--brand);
    }

    .cv-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--border);
        background: #fafafa;
    }
    .cv-card-title {
        display: flex;
        align-items: center;
        font-family: 'Sora', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--brand-deep);
    }
    .cv-card-title svg {
        color: var(--brand);
    }

    .cv-card-body {
        padding: 24px;
    }

    /* Clean badge definitions */
    .badge.bg-success-transparent, .bg-success-transparent {
        background: #ecfdf5 !important;
        color: #166534 !important;
    }
    .badge.bg-warning-transparent, .bg-warning-transparent {
        background: #fff7ed !important;
        color: #7c2d12 !important;
    }
    .badge.bg-secondary-transparent, .bg-secondary-transparent {
        background: #f1f5f9 !important;
        color: #334155 !important;
    }
    .badge.bg-danger-transparent, .bg-danger-transparent {
        background: #fef2f2 !important;
        color: #b91c1c !important;
    }
    .border-success-20 { border-color: rgba(22, 101, 52, 0.2) !important; }
    .border-warning-20 { border-color: rgba(124, 45, 18, 0.2) !important; }
    .border-danger-20 { border-color: rgba(185, 28, 28, 0.2) !important; }

    .description-box {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        font-size: 0.9rem;
    }

    .code {
        font-family: monospace;
        background: #f1f5f9;
        padding: 3px 6px;
        border-radius: 4px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .tr-header-band {
            padding: 18px 20px;
        }
    }
</style>
<?= $this->endSection() ?>