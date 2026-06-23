<div class="row g-4">
<?php if (!empty($categories)): foreach ($categories as $category): ?>
    <div class="col-xl-3 col-lg-4 col-md-6">
        <a href="<?= base_url('jobs?category=' . urlencode($category['id'])) ?>" class="card border-0 shadow-sm h-100 rounded-4 text-decoration-none hover-scale" style="background: var(--bg-white);">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(59,130,246,0.1); color: var(--primary-color);">
                    <i class="bi bi-briefcase fs-4"></i>
                </div>
                <div class="ms-3">
                    <h5 class="mb-1 fw-bold text-dark fs-6"><?= esc($category['name']) ?></h5>
                    <p class="mb-0 text-muted small"><?= number_format((int)($category['job_count'] ?? 0)) ?> open roles</p>
                </div>
            </div>
        </a>
    </div>
<?php endforeach; else: ?>
    <div class="col-12 text-center py-5">
        <div class="py-5 bg-light rounded-4">
            <i class="bi bi-folder-x fs-1 text-muted mb-3 d-block"></i>
            <h5 class="fw-bold">No Categories Found</h5>
            <p class="text-muted">Check back later for new updates.</p>
        </div>
    </div>
<?php endif; ?>
</div>
