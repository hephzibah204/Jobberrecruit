<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">Application Details</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/applications') ?>">Applications</a></li>
                <li class="breadcrumb-item active">View</li>
            </ol>
        </div>
    </div>

    <div class="row">

        <!-- Applicant Info -->
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">
                        <?= esc($application->first_name . ' ' . $application->last_name) ?>
                    </h5>

                    <p><strong>Email:</strong> <?= esc($application->email) ?></p>
                    <p><strong>Phone:</strong> <?= esc($application->phone) ?></p>
                    <p><strong>Availability:</strong> <?= esc($application->availability) ?></p>
                    <p><strong>Salary Expectation:</strong> <?= esc($application->salary_expectation ?: '—') ?></p>

                    <hr>

                    <h6 class="fw-semibold">Cover Letter</h6>
                    <p class="text-muted">
                        <?= nl2br(esc($application->cover_letter ?? '—')) ?>
                    </p>

                    <?php if (!empty($answers)): ?>
                        <hr>
                        <h6 class="fw-semibold mb-3">Pre-screening Answers</h6>
                        <?php foreach ($answers as $ans): ?>
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-1"><?= esc($ans->question) ?></label>
                                <div class="p-2 bg-light rounded border-start border-3 border-primary">
                                    <p class="mb-0 fs-13">
                                        <?php if ($ans->type === 'checkbox'): ?>
                                            <?php 
                                                $vals = explode(', ', $ans->answer);
                                                foreach($vals as $v):
                                            ?>
                                                <span class="badge bg-primary me-1"><?= esc($v) ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?= nl2br(esc($ans->answer)) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Job Info -->
        <div class="col-xl-4">
            <div class="card custom-card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Job Information</h6>

                    <p><strong>Job Title:</strong> <?= esc($application->job_title) ?></p>
                    <p><strong>Company:</strong> <?= esc($application->company_name) ?></p>
                    <p>
                        <strong>Status:</strong>
                        <span class="badge bg-primary-transparent">
                            <?= ucfirst($application->status) ?>
                        </span>
                    </p>

                    <?php if ($application->cv_path): ?>
                        <a download href="<?= base_url($application->cv_path) ?>"
                            target="_blank"
                            class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-download me-1"></i> Download CV
                        </a>
                    <?php endif ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>