<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>

<section class="py-5 bg-light">
    <div class="container">
        <!-- Hero search bar -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <form action="<?= base_url('jobs/search') ?>" method="get" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Keywords</label>
                        <input type="text" name="q" class="form-control" placeholder="Job title, skills…" value="<?= esc($keywords ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Location</label>
                        <select name="state_id" class="form-select">
                            <option value="">All locations</option>
                            <?php foreach ($states as $s): ?>
                                <option value="<?= $s->id ?>"><?= esc($s->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">All categories</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c->id ?>"><?= esc($c->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-4 fw-bold mb-0">
                <?php if (!empty($jobs)): ?>
                    <?= count($jobs) ?> Job<?= count($jobs) !== 1 ? 's' : '' ?> Found
                <?php else: ?>
                    No Jobs Found
                <?php endif; ?>
            </h1>
            <a href="<?= base_url('jobs') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Browse All Jobs
            </a>
        </div>

        <!-- Results grid -->
        <?php if (!empty($jobs)): ?>
            <div class="row g-4">
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body p-4">
                                <div class="d-flex gap-3 mb-3">
                                    <?php if (!empty($job->company_logo)): ?>
                                        <img src="<?= base_url('uploads/' . $job->company_logo) ?>"
                                             alt="<?= esc($job->employer_name ?? '') ?>"
                                             class="rounded" width="48" height="48" style="object-fit:contain;background:#f8f9fa;padding:4px">
                                    <?php else: ?>
                                        <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                                            <i class="bi bi-briefcase text-primary"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1 min-w-0">
                                        <h2 class="fs-6 fw-bold mb-0 text-truncate">
                                            <a href="<?= base_url('jobs/' . $job->id) ?>" class="text-dark text-decoration-none stretched-link">
                                                <?= esc($job->title) ?>
                                            </a>
                                        </h2>
                                        <small class="text-muted"><?= esc($job->employer_name ?? 'Company') ?></small>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <?php if (!empty($job->location)): ?>
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-geo-alt me-1"></i><?= esc($job->location) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($job->job_type)): ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary text-capitalize">
                                            <?= esc(str_replace('-', ' ', $job->job_type)) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($job->work_arrangement)): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success text-capitalize">
                                            <?= esc($job->work_arrangement) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($job->salary_min) || !empty($job->salary_max)): ?>
                                    <p class="small text-success fw-semibold mb-0">
                                        <i class="bi bi-cash-coin me-1"></i>
                                        ₦<?= number_format($job->salary_min ?? 0) ?> – ₦<?= number_format($job->salary_max ?? 0) ?>/month
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-search fs-1 text-muted d-block mb-3 opacity-50"></i>
                <h3 class="fs-5 text-muted">No jobs match your search.</h3>
                <p class="text-muted mb-4">Try different keywords, location, or category.</p>
                <a href="<?= base_url('jobs') ?>" class="btn btn-primary">
                    <i class="bi bi-grid me-2"></i>Browse All Jobs
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
