<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>

<section class="py-5 bg-light min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white shadow-sm p-4 p-lg-5 text-center rounded-4">

                    <div class="mb-4">
                        <img src="<?= base_url('images/success-check.svg') ?>"
                            alt="Success" width="110" class="mb-3">
                        <h1 class="fw-bold mb-2 text-success h2">Application Submitted!</h1>
                        <p class="text-muted fs-6">
                            Your application for the position of
                            <strong><?= esc($job->title) ?></strong>
                            at <strong><?= esc($job->company_name) ?></strong>
                            has been received.
                        </p>
                    </div>

                    <!-- JOB SUMMARY BOX -->
                    <div class="p-3 bg-light rounded-3 border mb-4 text-start d-flex align-items-center">
                        <img src="<?= base_url($job->company_logo ?: 'images/default-company.png') ?>"
                            alt="Logo" class="rounded me-3"
                            width="60" height="60" style="object-fit:cover">

                        <div>
                            <h5 class="fw-semibold mb-1"><?= esc($job->title) ?></h5>
                            <p class="mb-0 text-muted small">
                                <i class="bi bi-building me-1"></i><?= esc($job->company_name) ?>
                            </p>
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="d-grid gap-2">
                        <?php if ($auth->loggedIn()): ?>
                            <a href="<?= base_url('candidate/applications') ?>"
                            class="btn btn-primary btn-lg">
                            Track My Application
                        </a>
                        <?php endif; ?>

                        <a href="<?= base_url('jobs') ?>"
                            class="btn btn-outline-secondary btn-lg">
                            Browse Other Jobs
                        </a>
                    </div>

                </div>

                <!-- RELATED JOBS -->
                <?php if (!empty($similarJobs)): ?>
                    <div class="mt-5">
                        <h4 class="fw-bold mb-3">Similar Jobs You Might Like</h4>
                        <div class="row g-3">
                            <?php foreach ($similarJobs as $j): ?>
                                <div class="col-md-6">
                                    <a href="<?= base_url('jobs/' . $j->slug) ?>" class="text-decoration-none">
                                        <div class="p-3 bg-white border rounded-3 shadow-sm h-100">
                                            <h6 class="fw-semibold mb-1"><?= esc($j->title) ?></h6>
                                            <p class="text-muted small mb-1">
                                                <i class="bi bi-building me-1"></i><?= esc($j->company_name) ?>
                                            </p>
                                            <span class="badge bg-success-subtle text-success">
                                                <?= ucfirst($j->job_type) ?>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>