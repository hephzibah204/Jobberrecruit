<?php if (!empty($jobs)): foreach (array_slice($jobs, 0, 6) as $job): ?>
<div class="col-lg-4 col-md-6">
    <a href="<?= site_url('jobs/' . esc($job->slug)) ?>" class="rj-card touch-scale">
        <div class="d-flex justify-content-end mb-3">
            <div style="width: 40px; height: 40px; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #fff; border: 1px solid #eee;">
                <img src="<?= resolve_image_url($job->company_logo ?? '', 'company', $job->employer_name ?? 'Company') ?>" alt="<?= esc($job->employer_name ?? 'Company') ?> Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
        </div>
        <div class="rj-card-title fw-bold" style="font-size: 18px; color: var(--text-main); margin-bottom: 8px;">
            <?= esc($job->title) ?>
        </div>
        <div class="rj-card-company fw-semibold" style="font-size: 14px; color: var(--primary-color); margin-bottom: 15px; display: flex; align-items: center;">
            <?= esc($job->employer_name ?? 'Confidential') ?>
            <?php if(!empty($job->show_trust_badge)): ?>
                <i class="bi bi-check-circle-fill text-primary fs-6 ms-1" title="Verified Employer"></i>
            <?php endif; ?>
        </div>
        <div class="rj-card-meta d-flex gap-3 text-muted">
            <span><i class="bi bi-geo-alt"></i> <?= esc($job->location ?? 'Nigeria') ?></span>
            <span><i class="bi bi-briefcase"></i> <?= esc($job->employment_type ?? 'Full-time') ?></span>
            <span><i class="bi bi-clock"></i> <?= time_ago($job->created_at) ?></span>
        </div>
        <div class="rj-salary mt-auto pt-3">
            <?php 
            if (!empty($job->min_salary) && !empty($job->max_salary)): 
                echo '<span class="fw-bold" style="color: var(--success-color);">₦' . number_format($job->min_salary) . ' - ₦' . number_format($job->max_salary) . '</span> / month';
            else:
                echo '<span class="fw-bold text-muted">Salary Undisclosed</span>';
            endif;
            ?>
        </div>
    </a>
</div>
<?php endforeach; else: ?>
    <div class="col-12 text-center py-5">
        <img src="<?= base_url('images/illustrations/no-data.svg') ?>" alt="No jobs" style="width: 150px; opacity: 0.5;" onerror="this.style.display='none'">
        <h5 class="mt-3 text-muted">No jobs available right now</h5>
        <p class="text-muted">Please check back later or adjust your search.</p>
    </div>
<?php endif; ?>
