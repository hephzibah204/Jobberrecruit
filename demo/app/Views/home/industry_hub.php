<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<?php
include_once APPPATH . 'Views/partials/schema/job_posting.php';
$listItems = [];
foreach ($jobs as $index => $j) {
    $item = jobPostingSchema($j, base_url());
    $listItems[] = [
        '@type'    => 'ListItem',
        'position' => $index + 1,
        'item'     => $item,
    ];
}
?>
<script type="application/ld+json">
<?= json_encode([
    '@context'      => 'https://schema.org',
    '@type'         => 'ItemList',
    'name'          => $title ?? 'Industry Job Listings',
    'description'   => $meta_description ?? 'Browse job listings by industry',
    'url'           => current_url(),
    'numberOfItems' => (int) $total_jobs,
    'itemListElement' => $listItems,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ============================================================
     HERO SECTION
============================================================ -->
<section class="industry-hero py-5" style="background: linear-gradient(135deg, #002855 0%, #005DA8 50%, #001a3b 100%); position: relative; overflow: hidden;">
    <!-- Decorative blobs -->
    <div style="position:absolute;top:-60px;right:-60px;width:300px;height:300px;border-radius:50%;background:rgba(245, 166, 35, 0.1);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-80px;left:-40px;width:200px;height:200px;border-radius:50%;background:rgba(0, 93, 168, 0.1);pointer-events:none;"></div>

    <div class="container position-relative" style="z-index:2;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="background:transparent;">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-decoration-none" style="color: #8ac4ff;"><i class="bi bi-house me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('jobs') ?>" class="text-decoration-none" style="color: #8ac4ff;">All Jobs</a></li>
                <li class="breadcrumb-item active text-white-50"><?= esc($industry->name) ?> Jobs</li>
            </ol>
        </nav>

        <div class="row align-items-center gy-4">
            <div class="col-lg-8">
                <!-- Industry badge -->
                <div class="mb-3">
                    <span class="badge px-3 py-2 fs-13 fw-medium" style="background: rgba(245, 166, 35, 0.15); color: #F5A623; border: 1px solid rgba(245, 166, 35, 0.3);">
                        <i class="bi bi-grid-fill me-1"></i>Industry Category
                    </span>
                </div>

                <h1 class="display-5 fw-bold text-white lh-sm mb-3">
                    <?= esc($industry->seo_h1 ?? ($industry->name . ' Jobs in Nigeria')) ?>
                </h1>

                <p class="lead mb-4" style="color:rgba(255,255,255,0.75); max-width:680px;">
                    <?= esc($meta_description) ?>
                </p>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2 text-white">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:rgba(245, 166, 35, 0.15);border:1px solid rgba(245, 166, 35, 0.3);">
                            <i class="bi bi-briefcase fs-5" style="color:#F5A623;"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5"><?= number_format($total_jobs) ?></div>
                            <div class="text-white-50 small">Open Roles</div>
                        </div>
                    </div>
                    <a href="<?= base_url('jobs?' . (!empty($is_category) ? 'category_id=' : 'industry_id=') . $industry->id) ?>" class="btn btn-lg rounded-pill px-4 text-white border-0" style="background: linear-gradient(135deg, #F5A623, #d48b11);">
                        <i class="bi bi-search me-2"></i>Browse All Jobs
                    </a>
                    <?php if (!empty($auth)): ?>
                    <a href="<?= base_url('job-alerts?industry=' . $industry->id) ?>" class="btn btn-outline-light rounded-pill px-4">
                        <i class="bi bi-bell me-2"></i>Set Job Alert
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Stat cards column -->
            <div class="col-lg-4">
                <div class="glass-card p-4 text-white" style="background: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important;">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-bar-chart-fill fs-4 me-2" style="color:#F5A623;"></i>
                        <span class="fw-bold">Sector Insights</span>
                    </div>
                    <div class="row g-3 text-center">
                        <div class="col-12">
                            <div class="fw-bold fs-3" style="color:#F5A623;"><?= number_format($total_jobs) ?>+</div>
                            <div class="text-white-50 small">Live Opportunities</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     SEO DESCRIPTION SECTION
============================================================ -->
<?php if (!empty($industry->description)): ?>
<section class="py-4" style="background: #f8fafc;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card p-4 p-md-5 border-start border-4" style="border-left-color: #005DA8 !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(0, 93, 168, 0.1);border:2px solid rgba(0, 93, 168, 0.3);">
                                <i class="bi bi-info-circle fs-5" style="color:#005DA8;"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="fw-bold fs-5 mb-2 text-main">Careers in <?= esc($industry->name) ?></h2>
                            <p class="text-muted mb-0 lh-lg"><?= esc($industry->description) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     LATEST JOBS GRID
============================================================ -->
<section class="py-5">
    <div class="container">
        <div class="section-header mb-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="display-6 fw-bold mb-2 text-gradient">Latest Jobs in <?= esc($industry->name) ?></h2>
                    <p class="text-muted mb-0">
                        Showing <?= count($jobs) ?> of <?= number_format($total_jobs) ?> open vacancies
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="<?= base_url('jobs?' . (!empty($is_category) ? 'category_id=' : 'industry_id=') . $industry->id) ?>" class="btn btn-outline-primary rounded-pill">
                        View All <?= number_format($total_jobs) ?> Jobs <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <?php if (!empty($jobs)): ?>
        <div class="row g-4">
            <?php foreach ($jobs as $job): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="glass-card p-4 h-100 position-relative hover-lift">
                    <!-- Promoted ribbon -->
                    <?php if (!empty($job->is_featured)): ?>
                    <div class="featured-ribbon">
                        <span><i class="bi bi-star-fill me-1"></i>Featured</span>
                    </div>
                    <?php endif; ?>

                    <!-- Company logo + name -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <?php if (!empty($job->company_logo) && empty($job->is_anonymous)): ?>
                        <img src="<?= base_url($job->company_logo) ?>" alt="<?= esc($job->employer_name) ?>"
                             class="rounded" style="width:48px;height:48px;object-fit:contain;border:1px solid var(--border);">
                        <?php else: ?>
                        <div class="rounded d-flex align-items-center justify-content-center"
                             style="width:48px;height:48px;background:rgba(14,165,233,0.1);border:1px solid rgba(14,165,233,0.2);">
                            <i class="bi bi-building text-primary fs-4"></i>
                        </div>
                        <?php endif; ?>
                        <div class="overflow-hidden">
                            <div class="fw-semibold text-main text-truncate">
                                <?= !empty($job->is_anonymous) ? 'Confidential' : esc($job->employer_name ?? 'Employer') ?>
                            </div>
                            <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i><?= esc($job->location ?? 'Nigeria') ?></div>
                        </div>
                    </div>

                    <!-- Job title -->
                    <h5 class="fw-bold mb-2">
                        <a href="<?= base_url('jobs/' . ($job->slug ?? $job->id)) ?>" class="text-decoration-none text-main hover-primary">
                            <?= esc($job->title) ?>
                        </a>
                    </h5>

                    <!-- Tags -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php if (!empty($job->job_type)): ?>
                        <span class="badge bg-primary-transparent text-primary"><?= esc($job->job_type) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($job->industry_name)): ?>
                        <span class="badge bg-success-transparent text-success"><?= esc($job->industry_name) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($job->experience_level)): ?>
                        <span class="badge bg-info-transparent text-info"><?= esc($job->experience_level) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Salary + deadline -->
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top" style="border-color:var(--border) !important;">
                        <div class="text-success fw-medium small">
                            <?php if (!empty($job->salary)): ?>
                                <?php if (is_numeric($job->salary)): ?>
                                    ₦<?= number_format((float)$job->salary) ?><?= (!empty($job->max_salary) && is_numeric($job->max_salary)) ? '–₦' . number_format((float)$job->max_salary) : '' ?>
                                <?php else: ?>
                                    <?= esc($job->salary) ?><?= !empty($job->max_salary) ? '–' . esc($job->max_salary) : '' ?>
                                <?php endif; ?>
                                <?= !empty($job->salary_period) ? '<span class="text-muted">/' . esc($job->salary_period) . '</span>' : '' ?>
                            <?php else: ?>
                                <span class="text-muted">Salary: Negotiable</span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= base_url('jobs/' . ($job->slug ?? $job->id)) ?>" class="btn btn-sm btn-primary rounded-pill px-3">
                            Apply <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Load more CTA -->
        <?php if ($total_jobs > 12): ?>
        <div class="text-center mt-5">
            <a href="<?= base_url('jobs?' . (!empty($is_category) ? 'category_id=' : 'industry_id=') . $industry->id) ?>" class="btn btn-outline-primary btn-lg rounded-pill px-5">
                <i class="bi bi-grid me-2"></i>View All <?= number_format($total_jobs) ?> Jobs in <?= esc($industry->name) ?>
            </a>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-briefcase" style="font-size:4rem;color:var(--border);"></i>
            </div>
            <h4 class="fw-bold text-main mb-2">No Open Jobs Yet in <?= esc($industry->name) ?></h4>
            <p class="text-muted mb-4">Be the first to know when new vacancies are posted.</p>
            <a href="<?= base_url('jobs') ?>" class="btn btn-primary rounded-pill px-4">Browse All Jobs</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ============================================================
     BROWSE OTHER CATEGORIES
============================================================ -->
<section class="py-5" style="background: #f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-gradient mb-2">Explore More Sectors</h2>
            <p class="text-muted">Find opportunities across other top industries</p>
        </div>
        <div class="row g-3">
            <?php foreach ($all_industries as $i):
                $iSlug = !empty($i->slug) ? $i->slug : strtolower(str_replace([' ', '&'], ['-', 'and'], $i->name));
                $isActive = $i->id === $industry->id;
            ?>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="<?= base_url(esc($iSlug) . '-jobs') ?>"
                   class="d-block text-decoration-none rounded-3 py-2 px-3 text-center fw-medium small transition-all
                          <?= $isActive ? 'text-white' : 'glass-card text-main hover-lift' ?>"
                   style="<?= $isActive ? 'background: linear-gradient(135deg, #005DA8, #004a87); border:none;' : '' ?>">
                    <i class="bi bi-<?= $isActive ? 'check2-circle' : 'grid' ?> me-1 <?= $isActive ? '' : 'text-primary' ?>"></i>
                    <?= esc($i->name) ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.featured-ribbon {
    position: absolute;
    top: 0;
    right: 0;
    overflow: hidden;
    width: 90px;
    height: 90px;
    pointer-events: none;
}
.featured-ribbon span {
    position: absolute;
    top: 18px;
    right: -20px;
    display: block;
    width: 110px;
    padding: 4px 0;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    text-align: center;
    transform: rotate(45deg);
    letter-spacing: 0.5px;
}
.hover-primary:hover { color: var(--primary) !important; }
.transition-all { transition: all 0.2s ease; }
</style>

<?= $this->endSection() ?>
