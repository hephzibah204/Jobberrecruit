<?php if (!empty($categories)): foreach (array_slice($categories, 0, 8) as $cat): ?>
<div class="col-lg-3 col-md-4 col-sm-6">
    <a href="<?= site_url('jobs?category=' . esc($cat->id)) ?>" class="cat-card touch-scale">
        <div class="cat-icon mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle">
            <i class="<?= esc($cat->icon ?? 'bi bi-briefcase') ?> fs-3 text-primary"></i>
        </div>
        <h5 class="fw-bold mb-1" style="color: var(--text-main); font-size: 16px;"><?= esc($cat->name) ?></h5>
        <p class="mb-0 text-muted" style="font-size: 13px;"><?= esc($cat->job_count) ?> jobs</p>
    </a>
</div>
<?php endforeach; else: ?>
    <div class="col-12 text-center py-5">
        <p class="text-muted">No categories found.</p>
    </div>
<?php endif; ?>
