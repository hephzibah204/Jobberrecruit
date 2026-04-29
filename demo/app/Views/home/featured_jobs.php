<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>

<section class="featured-header-section py-5 bg-primary text-white">
    <div class="container text-center">
        <h1 class="fw-bold text-white">Featured Job Openings</h1>
        <p class="mt-2 fs-5 text-white">Top curated job opportunities from verified companies</p>
    </div>
</section>

<section class="py-5">
    <div class="container">

        <!-- ================= FILTERS ================= -->
        <div class="card shadow-sm rounded-4 p-4 mb-5">
            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Job Type</label>
                    <select class="form-select" name="type">
                        <option value="">Any Type</option>
                        <option value="full-time">Full Time</option>
                        <option value="part-time">Part Time</option>
                        <option value="contract">Contract</option>
                        <option value="internship">Internship</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Category</label>
                    <select class="form-select" name="category">
                        <option value="">Any Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->id ?>"><?= esc($cat->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Location</label>
                    <select class="form-select" name="state">
                        <option value="">Anywhere</option>
                        <?php foreach ($states as $state): ?>
                            <option value="<?= $state->id ?>"><?= esc($state->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 d-flex justify-content-end mt-2">
                    <button class="btn btn-primary me-2">Apply Filters</button>
                    <a href="<?= base_url('jobs/featured') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>

            </form>
        </div>

        <!-- ================= FEATURED JOB GRID ================= -->
        <div class="row g-4">

            <?php if (!empty($featured_jobs)): ?>
                <?php foreach ($featured_jobs as $job): ?>

                    <?php
                    $typeClass = match (strtolower($job->job_type)) {
                        'full-time' => 'bg-success text-white',
                        'part-time' => 'bg-primary-subtle text-primary',
                        'internship' => 'bg-warning-subtle text-warning',
                        default => 'bg-secondary text-white',
                    };

                    $salaryDisplay =
                        $job->salary_type === 'negotiable'
                        ? 'Negotiable'
                        : ($job->salary . ' / ' . $job->salary_period);
                    ?>

                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="<?= base_url('jobs/' . $job->slug) ?>" class="text-decoration-none">

                            <div class="featured-job-card p-4 bg-white shadow-sm rounded-4 position-relative">

                                <span class="featured-badge">FEATURED</span>

                                <div class="d-flex align-items-center mb-3">
                                    <img src="<?= !empty($job->anonymous) || !empty($job->is_anonymous) ? base_url('images/favicon.png') : ($job->company_logo ? base_url($job->company_logo) : base_url('images/default-company.png')) ?>"
                                        class="company-logo me-3" alt="<?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Anonymous Employer' : esc($job->company_name) ?>">
                                    <div>
                                        <h6 class="fw-semibold mb-1 text-dark"><?= esc($job->title) ?></h6>
                                        <p class="text-muted small mb-0"><?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Confidential Employer' : esc($job->company_name) ?></p>
                                    </div>
                                </div>

                                <span class="badge <?= $typeClass ?> mb-2"><?= strtoupper($job->job_type) ?></span>

                                <p class="text-muted small mb-1">
                                    <i class="bi bi-cash-stack text-success me-1"></i> <?= esc($salaryDisplay) ?>
                                </p>

                                <p class="text-muted small">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    <?= esc($job->state_name ?? 'Anywhere') ?>
                                </p>

                            </div>

                        </a>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>
                <p class="text-center text-muted fs-5">No featured jobs available.</p>
            <?php endif; ?>

        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            <?= $pager->links() ?>
        </div>

    </div>
</section>

<?= $this->endSection() ?>


<?= $this->section('styles') ?>
<style>
    .featured-job-card {
        transition: .25s ease-in-out;
    }

    .featured-job-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
    }

    .company-logo {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
    }

    .featured-badge {
        position: absolute;
        top: -12px;
        right: -12px;
        background: #d63384;
        color: #fff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
<?= $this->endSection() ?>