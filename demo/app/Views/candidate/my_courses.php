<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold"><i class="ti ti-book me-2"></i>My Courses</h4>
            <h6>Track your enrolled courses and progress</h6>
        </div>
        <div class="page-btn">
            <a href="<?= base_url('training') ?>" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Browse Courses
            </a>
        </div>
    </div>

    <?php if (empty($enrollments)): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="ti ti-book-off text-muted mb-3" style="width: 64px; height: 64px;"></i>
                <h5 class="text-muted">No enrolled courses yet</h5>
                <p class="text-muted mb-4">Start your learning journey by enrolling in a course.</p>
                <a href="<?= base_url('training') ?>" class="btn btn-primary btn-lg">
                    <i class="ti ti-plus me-2"></i>Explore Courses
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($enrollments as $enrollment): ?>
                <?php
                $isCompleted = $enrollment->status === 'completed';
                $isPaid = (float) ($enrollment->amount ?? 0) > 0;
                ?>
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-img-top position-relative" style="height: 160px; overflow: hidden; background: #f0f4ff;">
                            <?php if ($enrollment->thumbnail): ?>
                                <img src="<?= base_url($enrollment->thumbnail) ?>" alt="<?= esc($enrollment->course_title) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center h-100 text-primary">
                                    <i class="ti ti-book" style="font-size: 48px;"></i>
                                </div>
                            <?php endif; ?>
                            <span class="position-absolute top-0 end-0 m-2 badge bg-<?= $isCompleted ? 'success' : ($enrollment->status === 'enrolled' ? 'primary' : 'secondary') ?>">
                                <?= ucfirst($enrollment->status) ?>
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold mb-2 text-truncate"><?= esc($enrollment->course_title) ?></h6>
                            <div class="d-flex align-items-center gap-3 mb-2 small text-muted">
                                <span><i class="ti ti-user me-1"></i><?= esc($enrollment->instructor ?: 'JobberRecruit') ?></span>
                                <span><i class="ti ti-clock me-1"></i><?= esc($enrollment->duration ?: 'Self-paced') ?></span>
                            </div>
                            <?php if ($enrollment->progress !== null): ?>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Progress</span>
                                        <span><?= (int) $enrollment->progress ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-<?= $isCompleted ? 'success' : 'primary' ?>" role="progressbar" style="width: <?= (int) $enrollment->progress ?>%"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($isPaid): ?>
                                <div class="small text-muted mb-2">
                                    <i class="ti ti-credit-card me-1"></i>₦<?= number_format((float) $enrollment->amount, 2) ?>
                                    <?php if ($enrollment->payment_reference): ?>
                                        <span class="text-success ms-1">Paid</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="mt-auto d-flex gap-2">
                                <?php if ($isCompleted): ?>
                                    <a href="<?= base_url('training/certificates') ?>" class="btn btn-outline-success btn-sm flex-grow-1">
                                        <i class="ti ti-award me-1"></i>View Certificate
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('training/course/' . $enrollment->course_id) ?>" class="btn btn-primary btn-sm flex-grow-1">
                                        <i class="ti ti-player-play me-1"></i>Continue
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
