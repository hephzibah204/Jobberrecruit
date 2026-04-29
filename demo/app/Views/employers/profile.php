<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Company Profile</h4>
                <h6>View and manage your company profile</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="location.reload()"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="<?= base_url('employer/dashboard') ?>" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to Dashboard</a>
        </div>
    </div>

    <div class="row">
        <!-- Profile Overview Card -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="profile-image mb-3">
                        <?php if (!empty($employer->logo)): ?>
                            <img src="<?= base_url($employer->logo) ?>" alt="Company Logo" class="rounded-circle" width="100" height="120">
                        <?php else: ?>
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i data-feather="building" class="text-muted" style="width: 60px; height: 60px;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h4 class="mb-1"><?= esc($employer->company_name ?? 'Not Set') ?>
                        <span><?php if ($canShowTrustBadge ?? false): ?>
                                <img src="<?= base_url('images/badge.svg') ?>"
                                    data-bs-toggle="tooltip"
                                    width="20"
                                    title="This employer is verified and subscribed to a trusted plan">
                            <?php endif; ?></span>
                    </h4>
                    <p class="text-muted mb-3"><?= esc($employer->contact_email ?? $user->email) ?></p>

                    <div class="verification-badge mb-3">
                        <?php if ($employer->is_verified ?? false): ?>
                            <span class="badge bg-success">
                                <i data-feather="shield" class="me-1"></i>
                                Verified
                            </span>
                        <?php elseif ($hasCACDocument && $cacDocument && ($cacDocument['status'] ?? $cacDocument->status ?? '') == 'pending'): ?>
                            <span class="badge bg-warning">
                                <i data-feather="clock" class="me-1"></i>
                                Pending Review
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">
                                <i data-feather="alert-circle" class="me-1"></i>
                                Not Verified
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="<?= base_url('employer/profile/edit') ?>" class="btn btn-primary">
                            <i data-feather="edit" class="me-1"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Plan & Credits Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Account Summary</h5>
                </div>
                <div class="card-body">
                    <?php if ($hasUnlimitedAccess ?? false): ?>
                        <div class="text-center mb-3">
                            <div class="alert alert-success mb-0">
                                <i data-feather="infinity" class="me-2" style="width: 24px; height: 24px;"></i>
                                <strong>Unlimited Access Plan</strong>
                                <p class="mb-0 small">Enterprise account with unlimited job postings</p>
                                <?php if (!empty($employer->unlimited_until)): ?>
                                    <hr class="my-2">
                                    <small>Valid until: <?= date('M d, Y', strtotime($employer->unlimited_until)) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Current Subscription Plan -->
                        <div class="mb-3">
                            <label class="form-label text-muted">Current Plan</label>
                            <?php
                            // Handle both object and array for activeSubscription
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
                                <h5 class="mb-1"><?= esc($planName) ?></h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Expires: <?= date('M d, Y', strtotime($planEndsAt)) ?>
                                    </small>
                                    <a href="<?= base_url('employer/pricing') ?>" class="btn btn-sm btn-outline-primary">
                                        Renew
                                    </a>
                                </div>
                                <?php if (!empty($planFeatures)): ?>
                                    <div class="mt-2">
                                        <?php foreach ($planFeatures as $feature => $enabled): ?>
                                            <?php if ($enabled): ?>
                                                <span class="badge bg-primary-transparent me-1 mb-1">
                                                    <?= ucwords(str_replace('_', ' ', $feature)) ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">No active subscription</p>
                                <a href="<?= base_url('employer/pricing') ?>" class="btn btn-sm btn-primary mt-2">
                                    Subscribe Now
                                </a>
                            <?php endif; ?>
                        </div>

                        <hr>

                        <!-- Wallet/Credits -->
                        <div>
                            <label class="form-label text-muted">Job Credits</label>
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="mb-0 text-primary"><?= number_format($creditBalance ?? 0) ?></h3>
                                <a href="<?= base_url('employer/pricing') ?>" class="btn btn-sm btn-success">
                                    <i data-feather="plus-circle"></i> Buy Credits
                                </a>
                            </div>
                            <small class="text-muted">Credit does not expire.</small>

                            <?php if (($creditBalance ?? 0) == 0 && !$hasActivePlan): ?>
                                <div class="alert alert-warning mt-2 mb-0">
                                    <i data-feather="alert-triangle" class="me-1"></i>
                                    No credits available. Please subscribe or purchase credits to post jobs.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Profile Completion -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Profile Completion</h5>
                </div>
                <div class="card-body">
                    <?php
                    $fields = ['company_name', 'company_size', 'website', 'state_id', 'description', 'contact_name', 'contact_email', 'contact_phone'];
                    $completed = 0;
                    foreach ($fields as $f) {
                        if (!empty($employer->$f)) $completed++;
                    }
                    // Check industry links
                    $industryCount = isset($employer->industries) ? count($employer->industries) : 0;
                    if ($industryCount > 0) $completed++;

                    // Check CAC document
                    if ($hasCACDocument ?? false) $completed++;

                    $totalFields = count($fields) + 2;
                    $completion = round(($completed / $totalFields) * 100);
                    ?>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: <?= $completion ?>%;" role="progressbar"></div>
                    </div>
                    <p class="text-center mb-0"><?= $completion ?>% Complete</p>
                    <small class="text-muted text-center d-block"><?= $completed ?> of <?= $totalFields ?> fields filled</small>

                    <?php if (!($hasCACDocument ?? false)): ?>
                        <div class="alert alert-info mt-3 mb-0">
                            <i data-feather="info" class="me-1"></i>
                            Complete your profile by uploading CAC certificate.
                            <a href="<?= base_url('employer/profile/upload-document') ?>" class="alert-link">Upload now</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Company Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Company Name</label>
                            <p class="mb-0"><?= esc($employer->company_name ?? 'Not Set') ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Industries</label>
                            <p class="mb-0">
                                <?php if (!empty($employer->industries)): ?>
                                    <?php foreach ($employer->industries as $ind): ?>
                                        <span class="badge bg-light text-dark border me-1"><?= esc($ind->name) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    Not Set
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Company Size</label>
                            <p class="mb-0"><?= esc($employer->company_size ?? 'Not Set') ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Website</label>
                            <p class="mb-0">
                                <?php if (!empty($employer->website)): ?>
                                    <a href="<?= esc($employer->website) ?>" target="_blank"><?= esc($employer->website) ?></a>
                                <?php else: ?>
                                    Not Set
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">State</label>
                            <p class="mb-0">
                                <?= esc($employer->location ? $employer->location . ' State' : 'Not Set') ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">User ID</label>
                            <p class="mb-0"><?= esc($employer->user_id) ?></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Description</label>
                        <div class="border rounded p-3 bg-light">
                            <?= !empty($employer->description)
                                ? nl2br(esc($employer->description))
                                : '<p class="text-muted mb-0">No description provided</p>' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Contact Name</label>
                            <p class="mb-0"><?= esc($employer->contact_name ?? 'Not Set') ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Contact Email</label>
                            <p class="mb-0">
                                <?php if (!empty($employer->contact_email)): ?>
                                    <a href="mailto:<?= esc($employer->contact_email) ?>"><?= esc($employer->contact_email) ?></a>
                                <?php else: ?>
                                    Not Set
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Contact Phone</label>
                            <p class="mb-0">
                                <?php if (!empty($employer->contact_phone)): ?>
                                    <a href="tel:<?= esc($employer->contact_phone) ?>"><?= esc($employer->contact_phone) ?></a>
                                <?php else: ?>
                                    Not Set
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Company Address</label>
                            <p class="mb-0"><?= esc($employer->company_address ?? 'Not Set') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CAC Certificate Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">CAC Certificate Verification</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <label class="form-label text-muted">CAC Certificate</label>
                            <p class="mb-0">
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
                                    <a href="<?= base_url($cacFilePath) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i data-feather="file-text" class="me-1"></i> View CAC Certificate
                                    </a>
                                    <span class="badge bg-<?= $cacStatus == 'approved' ? 'success' : ($cacStatus == 'pending' ? 'warning' : 'danger') ?> ms-2">
                                        <?= ucfirst($cacStatus) ?>
                                    </span>
                                    <?php if ($cacStatus == 'rejected' && !empty($employer->rejection_reason)): ?>
                            <div class="alert alert-danger mt-2 mb-0">
                                <strong>Rejection Reason:</strong> <?= esc($employer->rejection_reason) ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="text-danger">
                            <i data-feather="alert-circle" class="me-1"></i>
                            No CAC certificate uploaded
                        </span>
                    <?php endif; ?>
                    </p>
                    <small class="text-muted d-block mt-2">
                        CAC certificate is required for business verification
                    </small>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if (!($hasCACDocument ?? false)): ?>
                                <a href="<?= base_url('employer/profile/upload-document') ?>" class="btn btn-primary btn-sm">
                                    <i data-feather="upload" class="me-1"></i> Upload CAC Certificate
                                </a>
                            <?php elseif ($cacDocument && ($cacStatus ?? '') == 'rejected'): ?>
                                <a href="<?= base_url('employer/profile/upload-document') ?>" class="btn btn-warning btn-sm">
                                    <i data-feather="refresh-cw" class="me-1"></i> Re-upload Certificate
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