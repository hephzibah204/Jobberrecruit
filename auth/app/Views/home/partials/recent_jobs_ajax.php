<div class="row g-4">
<?php if (!empty($jobs)): foreach (array_slice($jobs, 0, 6) as $job): ?>
    <div class="col-lg-6">
        <a href="<?= base_url('job/view/' . $job->id) ?>" class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-decoration-none hover-scale d-block" style="background: var(--bg-white);">
            <div class="card-body p-4 d-flex flex-column h-100">
                <div class="d-flex justify-content-between mb-3 align-items-start">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center border" style="width: 60px; height: 60px; background: #f8f9fa;">
                            <img src="<?= resolve_image_url($job->company_logo ?? '', 'company', $job->employer_name ?? 'Company') ?>" alt="<?= esc($job->employer_name ?? 'Company') ?> Logo" style="max-width: 80%; max-height: 80%; object-fit: contain;">
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold text-dark fs-5"><?= esc($job->title) ?>
                                <?php if(!empty($job->show_trust_badge)): ?>
                                    <i class="bi bi-check-circle-fill text-primary fs-6 ms-1" title="Verified Employer"></i>
                                <?php endif; ?>
                            </h5>
                            <div class="text-muted small"><?= esc($job->employer_name ?? 'Company') ?></div>
                        </div>
                    </div>
                    <?php if(!empty($job->employment_type)): ?>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-normal">
                            <?= esc($job->employment_type) ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <p class="text-muted small mb-4 line-clamp-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= esc(strip_tags($job->description ?? '')) ?></p>
                
                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                    <div class="text-dark fw-bold">
                        <?php 
                        if (!empty($job->min_salary) && !empty($job->max_salary)) {
                            echo '₦' . number_format($job->min_salary) . ' - ₦' . number_format($job->max_salary);
                        } elseif (!empty($job->min_salary)) {
                            echo '₦' . number_format($job->min_salary);
                        } else {
                            echo 'Confidential';
                        }
                        ?>
                    </div>
                    <div class="text-muted small">
                        <i class="bi bi-geo-alt me-1"></i> <?= esc($job->location ?? 'Nigeria') ?>
                    </div>
                </div>
            </div>
        </a>
    </div>
<?php endforeach; else: ?>
    <div class="col-12 text-center py-5">
        <div class="py-5 bg-light rounded-4">
            <i class="bi bi-briefcase text-muted fs-1 mb-3 d-block"></i>
            <h5 class="fw-bold">No Recent Jobs</h5>
            <p class="text-muted">There are no recent jobs to display at the moment.</p>
        </div>
    </div>
<?php endif; ?>
</div>
