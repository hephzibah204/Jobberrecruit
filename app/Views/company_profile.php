<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>

<!-- ======================= COMPANY HEADER ======================= -->
<section class="company-header-section py-5" style="background: linear-gradient(135deg, var(--midnight-bg) 0%, var(--surface) 100%); border-bottom: 1px solid var(--border);">
    <div class="container d-flex align-items-center gap-4">
        <img src="<?= base_url($company->logo ?? 'uploads/default-logo.png') ?>"
            class="rounded-4 shadow-lg"
            width="120" height="120" style="object-fit:cover; border: 2px solid var(--border);">
        <div>
            <h2 class="fw-bold mb-1 text-gradient"><?= esc($company->company_name) ?></h2>
            <p class="mb-0 text-muted">
                Company Size: <span class="text-main fw-medium"><?= esc($company->company_size ?: 'Not Specified') ?></span>
                <?php if ($company->show_trust_badge): ?>
                    <span class="badge-verified ms-2" title="Verified Employer">
                        <i class="bi bi-patch-check-fill"></i> Verified
                    </span>
                <?php endif; ?>
            </p>
        </div>
    </div>
</section>

<!-- ======================= MAIN CONTENT ======================= -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">

            <!-- ======================= LEFT COLUMN ======================= -->
            <div class="col-lg-8">

                <!-- ABOUT COMPANY -->
                <div class="glass-card p-4 mb-4">
                    <h4 class="fw-semibold mb-3">About the Company</h4>
                    <p class="text-muted">
                        <?= $company->description ?: 'No company description available.' ?>
                    </p>

                    <?php if (!empty($industries)) : ?>
                        <h6 class="fw-semibold mb-3 mt-4">Industries</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($industries as $ind): ?>
                                <span class="badge bg-secondary-subtle text-dark px-3 py-2">
                                    <?= esc($ind->name) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ======================= OPEN POSITIONS ======================= -->
                <div class="glass-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-semibold">Open Positions</h4>
                        <?php if (!empty($openJobs)): ?>
                            <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                <?= count($openJobs) ?> Jobs Available
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($openJobs)): ?>

                        <div class="text-center py-5">
                            <img src="<?= base_url('icons/empty_jobs.svg') ?>" width="120" class="mb-3 opacity-75">
                            <p class="text-muted">No open positions at this time.</p>
                        </div>

                    <?php else: ?>

                        <div class="job-list-wrapper">

                            <?php foreach ($openJobs as $job): ?>
                                <a href="<?= base_url('jobs/' . $job->slug) ?>" class="job-card-link text-decoration-none">
                                    <div class="job-card-enhanced p-4 mb-3 rounded-3 bg-light shadow-sm">

                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="fw-bold text-dark mb-1">
                                                <?= esc($job->title) ?>
                                            </h5>

                                            <span class="badge bg-primary-subtle text-primary">
                                                <?= strtoupper($job->job_type) ?>
                                            </span>
                                        </div>

                                        <div class="mb-2">
                                            <i class="bi bi-geo-alt text-primary me-1"></i>
                                            <span class="text-muted"><?= esc($job->location . ' State' ?? 'Location Not Available') ?></span>
                                        </div>

                                        <?php
                                        // Display salary nicely
                                        if ($job->salary_type === 'negotiable') {
                                            $salaryDisplay = "Negotiable";
                                        } elseif (strpos($job->salary, '-') !== false) {
                                            $salaryDisplay = $job->salary;
                                        } else {
                                            $salaryDisplay = $job->salary;
                                        }
                                        ?>

                                        <div class="mb-3">
                                            <i class="bi bi-cash-stack text-success me-1"></i>
                                            <strong><?= $salaryDisplay ?></strong>
                                            <span class="text-muted"> / <?= esc($job->salary_period) ?></span>
                                        </div>

                                        <p class="text-muted small mb-0" style="line-height: 1.4;">
                                            <?= character_limiter(strip_tags($job->description), 120) ?>
                                        </p>
                                    </div>
                                </a>
                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>
                </div>

            </div>

            <!-- ======================= RIGHT COLUMN ======================= -->
            <div class="col-lg-4">
 
                <!-- Company Information -->
                <div class="glass-card p-4 mb-4">
                    <h6 class="fw-semibold mb-3">Company Information</h6>
                    <ul class="list-unstyled fs-6 text-muted">

                        <li class="mb-3">
                            <i class="bi bi-geo-alt text-primary me-2"></i>
                            <strong>Location:</strong>
                            <?= esc($company->company_address . ',' . $company->location . ' State' ?: 'Not Provided') ?>
                        </li>

                        <li class="mb-3">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <strong>Email:</strong>
                            <?= esc($company->contact_email ?: 'Not Provided') ?>
                        </li>

                        <li class="mb-3">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <strong>Phone:</strong>
                            <?= esc($company->contact_phone ?: 'Not Provided') ?>
                        </li>

                        <?php if (!empty($company->website)): ?>
                            <li class="mb-3">
                                <i class="bi bi-globe text-primary me-2"></i>
                                <strong>Website:</strong>
                                <a href="<?= esc($company->website) ?>" target="_blank" class="text-primary">
                                    <?= esc($company->website) ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="mb-0">
                            <i class="bi bi-patch-check text-primary me-2"></i>
                            <strong>Verification:</strong>
                            <?= $company->is_verified ? '<span class="text-success">Verified</span>' : 'Not Verified' ?>
                        </li>

                    </ul>
                </div>

                <?php if (!empty($company->website)): ?>
                    <!-- Visit Website CTA -->
                    <div class="glass-card p-4 text-center">
                        <a href="<?= esc($company->website) ?>" target="_blank"
                            class="btn btn-primary w-100 btn-lg">
                            Visit Company Website <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<!-- CTA Button (Desktop Only) -->
<?php if (!empty($openJobs)): ?>
    <div class="position-fixed bottom-0 end-0 m-4 d-none d-lg-block">
        <a href="#"
            class="btn btn-primary btn-lg shadow">
            View Open Positions <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .company-logo {
        border: 3px solid rgba(255, 255, 255, 0.4);
    }

    /* Enhanced Job Card */
    .job-card-enhanced {
        border: 1px solid #e5e7eb;
        background: #f8f9fc;
        transition: all .25s ease-in-out;
        cursor: pointer;
    }

    .job-card-enhanced:hover {
        background: #eef2f7 !important;
        transform: translateY(-4px);
        border-color: #d0d7e2;
    }

    .job-card-link:hover {
        text-decoration: none !important;
    }
</style>
<?= $this->endSection() ?>