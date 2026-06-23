<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="content">

    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">Application Detail</h4>
            <h6><?= esc($job->title) ?></h6>
        </div>

        <div class="page-btn">
            <a href="<?= site_url('candidate/applications') ?>" class="btn btn-primary">
                <i class="ti ti-arrow-left me-1"></i>Back to Applications
            </a>
        </div>
    </div>

    <div class="row">

        <!-- LEFT SIDEBAR -->
        <div class="col-xl-4 theiaStickySidebar">

            <div class="card rounded-0 border-0">
                <div class="card-header bg-primary rounded-0">
                    <h6 class="mb-0 text-white"><?= esc($job->title) ?></h6>
                </div>

                <div class="card-body">

                    <div class="mb-2">
                        <span class="fw-semibold">Company: </span>
                        <p class="text-dark mb-1"><?= esc($job->company_name ?? 'N/A') ?></p>
                    </div>

                    <div class="mb-2">
                        <span class="fw-semibold">Location: </span>
                        <p class="text-dark mb-1"><?= esc($job->location . ' State'?? 'N/A') ?></p>
                    </div>

                    <div class="mb-2">
                        <span class="fw-semibold">Applied On: </span>
                        <p class="text-dark mb-1"><?= date('M j, Y', strtotime($application->created_at)) ?></p>
                    </div>

                    <div class="mb-2">
                        <span class="fw-semibold">Application Status</span><br>

                        <?php
                        $colors = [
                            'pending' => 'warning',
                            'reviewed' => 'info',
                            'shortlisted' => 'primary',
                            'rejected' => 'danger',
                            'hired' => 'success'
                        ];
                        $color = $colors[strtolower($application->status)] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $color ?> fs-14">
                            <?= ucfirst($application->status) ?>
                        </span>
                    </div>

                    <?php if (!empty($application->status_message)): ?>
                        <div class="alert alert-light border mt-3">
                            <h6 class="fw-semibold">Message from Employer</h6>
                            <p class="mb-0"><?= nl2br(esc($application->status_message)) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($application->cv_path): ?>
                        <a href="<?= base_url($application->cv_path) ?>"
                            download class="btn btn-primary w-100 mt-3">
                            <i class="ti ti-download me-1"></i>Download CV
                        </a>
                    <?php endif; ?>

                </div>
            </div>

        </div>

        <!-- RIGHT CONTENT -->
        <div class="col-xl-8">

            <!-- Cover Letter -->
            <div class="card rounded-0 border-0">
                <div class="card-header bg-light rounded-0">
                    <h6>Cover Letter</h6>
                </div>
                <div class="card-body">
                    <?php if ($application->cover_letter): ?>
                        <p><?= nl2br(esc($application->cover_letter)) ?></p>
                    <?php else: ?>
                        <p class="text-muted">No cover letter provided.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card rounded-0 border-0 mt-3">
                <div class="card-header bg-light rounded-0">
                    <h6>Additional Information</h6>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <p class="fs-13 mb-2">Availability</p>
                            <span class="fw-semibold">
                                <?= esc(ucfirst(str_replace('_', ' ', $application->availability))) ?>
                            </span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="fs-13 mb-2">Salary Expectation</p>
                            <span class="fw-semibold"><?= esc($application->salary_expectation ?: 'Not provided') ?></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <p class="fs-13 mb-2">Work Eligibility</p>
                            <span class="fw-semibold">
                                <?= $application->work_eligibility === 'yes' ? 'Eligible' : 'Requires sponsorship' ?>
                            </span>
                        </div>

                    </div>

                </div>
            </div>

            <!-- References -->
            <div class="card rounded-0 border-0 mt-3">
                <div class="card-header bg-light rounded-0">
                    <h6>References</h6>
                </div>
                <div class="card-body">

                    <?php if (!empty($references)): ?>
                        <?php foreach ($references as $ref): ?>
                            <div class="border rounded p-3 mb-2">
                                <p class="fw-semibold mb-1"><?= esc($ref['name']) ?></p>
                                <p class="text-muted mb-1"><?= esc($ref['title']) ?></p>
                                <p class="text-primary"><?= esc($ref['email']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No references provided.</p>
                    <?php endif; ?>

                </div>
            </div>

        </div>

    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('auth/plugins/theia-sticky-sidebar/ResizeSensor.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') ?>" type="text/javascript"></script>
<?= $this->endSection() ?>