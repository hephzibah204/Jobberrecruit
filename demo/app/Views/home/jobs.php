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
    'name'          => 'Job Listings',
    'description'   => 'Find your next career opportunity from thousands of job listings',
    'url'           => current_url(),
    'numberOfItems' => (int) $total_jobs,
    'itemListElement' => $listItems,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">Find Job In Nigeria</h1>
                <p class="lead text-muted mb-4">
                    Find and apply for the latest Jobs in Nigeria. Browse Full-time, part-time, and remote vacancies in Lagos, Abuja, Portharcourt, and across all 36 states. New listings added daily.
                </p>
            </div>
            <?php if (!empty($auth)): ?>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= base_url('job-alerts') ?>" class="btn btn-outline-primary">
                    <i class="bi bi-bell me-2"></i>Create Job Alert
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-5 mb-lg-0">
                <div class="glass-card sticky-top" style="top: 100px;">
                    <div class="card-header border-0 py-4" style="background: rgba(14, 165, 233, 0.1) !important;">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-funnel me-2"></i>Filters
                            <span class="badge bg-primary ms-2" id="activeFilterCount">0</span>
                        </h5>
                    </div>

                    <div class="card-body">
                        <!-- Keyword Search -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="bi bi-search me-1"></i>Keywords
                            </label>
                            <div class="input-group">
                                <input type="text"
                                    id="searchKeyword"
                                    class="form-control"
                                    placeholder="Job title, skills..."
                                    value="<?= esc($keywords ?? '') ?>">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="bi bi-geo-alt me-1"></i>Location
                            </label>
                            <select id="locationFilter" class="form-select searchable-select">
                                <option value="">All Locations</option>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?= esc($state->id) ?>" <?= $stateId == $state->id ? 'selected' : '' ?>>
                                        <?= esc($state->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Job Type -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="bi bi-briefcase me-1"></i>Job Type
                            </label>
                            <div class="form-check mb-2">
                                <input class="form-check-input job-type-filter" type="checkbox" value="full_time" id="fullTime">
                                <label class="form-check-label" for="fullTime">
                                    Full Time
                                    <span class="text-muted float-end"> (<?= $job_type_counts['full_time'] ?? 0 ?>)</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input job-type-filter" type="checkbox" value="part_time" id="partTime">
                                <label class="form-check-label" for="partTime">
                                    Part Time
                                    <span class="text-muted float-end"> (<?= $job_type_counts['part_time'] ?? 0 ?>)</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input job-type-filter" type="checkbox" value="contract" id="contract">
                                <label class="form-check-label" for="contract">
                                    Contract
                                    <span class="text-muted float-end"> (<?= $job_type_counts['contract'] ?? 0 ?>)</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input job-type-filter" type="checkbox" value="remote" id="remote">
                                <label class="form-check-label" for="remote">
                                    Remote
                                    <span class="text-muted float-end"> (<?= $job_type_counts['remote'] ?? 0 ?>)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Experience Level -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="bi bi-person-badge me-1"></i>Experience
                            </label>
                            <select id="experienceLevel" class="form-select">
                                <option value="">Any Experience</option>
                                <?php foreach ($experience_level_counts as $level => $count): ?>
                                    <option value="<?= esc($level) ?>" <?= $experienceLevel == $level ? 'selected' : '' ?>>
                                        <?= ucfirst(esc($level)) ?> (<?= $count ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Industry -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="bi bi-building me-1"></i>Industry
                            </label>
                            <select id="industryFilter" class="form-select searchable-select">
                                <option value="">All Industries</option>
                                <?php foreach ($industries as $industry):
                                    $count = array_reduce($industry_counts, fn($carry, $item) => $item->id == $industry->id ? $item->job_count : $carry, 0);
                                ?>
                                    <option value="<?= esc($industry->id) ?>" <?= $industryId == $industry->id ? 'selected' : '' ?>>
                                        <?= esc($industry->name) ?> (<?= $count ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Salary Range -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="bi bi-cash-coin me-1"></i>Salary Range
                            </label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number"
                                        id="minSalary"
                                        class="form-control"
                                        placeholder="Min"
                                        value="<?= esc($salaryMin ?? '') ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number"
                                        id="maxSalary"
                                        class="form-control"
                                        placeholder="Max"
                                        value="<?= esc($salaryMax ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="bi bi-sort-down me-1"></i>Sort By
                            </label>
                            <select id="sortBy" class="form-select">
                                <option value="newest" <?= $sort_by == 'newest' ? 'selected' : '' ?>>Newest First</option>
                                <option value="oldest" <?= $sort_by == 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                                <option value="salary_high" <?= $sort_by == 'salary_high' ? 'selected' : '' ?>>Salary (High to Low)</option>
                                <option value="salary_low" <?= $sort_by == 'salary_low' ? 'selected' : '' ?>>Salary (Low to High)</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" id="applyFilters">
                                <i class="bi bi-check-lg me-2"></i>Apply Filters
                            </button>
                            <button class="btn btn-outline-secondary" id="clearFilters">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset All
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Listings -->
            <div class="col-lg-9">
                <!-- Results Header -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-0">
                                    <span id="resultsCount"><?= number_format($total_jobs) ?></span> Jobs Found
                                </h5>
                                <p class="text-muted small mb-0 mt-1" id="filterSummary">
                                    Showing <?= count($jobs) ?> of <?= number_format($total_jobs) ?> results
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary active" id="gridView">
                                        <i class="bi bi-grid"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="listView">
                                        <i class="bi bi-list"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Listings Grid/List -->
                <div id="jobList" class="<?= $view_mode === 'list' ? 'job-list-view' : 'job-grid-view' ?>">
                    <!-- Loading State -->
                    <div id="loadingState" class="d-none">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted">Searching for jobs...</p>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div id="noResults" class="d-none">
                        <div class="text-center py-5">
                            <i class="bi bi-search display-1 text-muted mb-3"></i>
                            <h4 class="fw-bold mb-3">No jobs found</h4>
                            <p class="text-muted mb-4">Try adjusting your filters or search keywords</p>
                            <button class="btn btn-primary" id="resetFiltersBtn">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset Filters
                            </button>
                        </div>
                    </div>

                    <!-- Ad-Res -->
                    <ins class="adsbygoogle"
                        style="display:block"
                        data-ad-client="ca-pub-3464186884176173"
                        data-ad-slot="6229476516"
                        data-ad-format="auto"
                        data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>

                    <!-- Job Cards -->
                    <div class="row g-4" id="jobCardsContainer">
                        <?php if (empty($jobs)): ?>
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="bi bi-search display-1 text-muted mb-3"></i>
                                    <h4 class="fw-bold mb-3">No jobs found</h4>
                                    <p class="text-muted mb-4">Try adjusting your filters or search keywords</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($jobs as $job): ?>
                                <div class="col-12 job-item"
                                    data-title="<?= esc($job->title) ?>"
                                    data-location="<?= esc($job->location) ?>"
                                    data-type="<?= esc($job->job_type) ?>"
                                    data-experience="<?= esc($job->experience_level) ?>"
                                    data-industry="<?= esc($job->industry_name) ?>"
                                    data-salary="<?= esc($job->salary) ?>">
                                     <div class="glass-card h-100 p-4 position-relative">
                                        <?php if ($job->is_featured && $job->featured_until >= date('Y-m-d')) : ?>
                                            <div class="featured-ribbon">
                                                <i class="bi bi-star-fill me-1"></i>Promoted
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="row align-items-center">
                                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                                    <div class="company-logo-container mx-auto">
                                                        <img src="<?= !empty($job->anonymous) || !empty($job->is_anonymous) ? base_url('images/favicon.png') : $job->company_logo ?>"
                                                            alt="<?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Anonymous Employer' : esc($job->employer_name ?? 'Company') ?>"
                                                            class="img-fluid rounded-3"
                                                            loading="lazy">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">

                                                    <!-- Job title -->
                                                    <div class="mb-2">
                                                         <h5 class="fw-bold mb-0">
                                                            <a href="<?= base_url('jobs/' . $job->slug) ?>"
                                                                class="text-decoration-none text-gradient-primary">
                                                                <?= esc($job->title) ?>
                                                            </a>
                                                            <?php if ($job->show_trust_badge): ?>
                                                                <span class="badge-verified ms-2" title="Verified Employer">
                                                                    <i class="bi bi-patch-check-fill"></i>
                                                                </span>
                                                            <?php endif; ?>
                                                        </h5>
                                                    </div>

                                                    <!-- Badges -->
                                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                                        <span class="badge bg-primary text-white">
                                                            <?= ucfirst(str_replace('_', ' ', $job->job_type)) ?>
                                                        </span>

                                                        <?php if ($job->remote_available): ?>
                                                            <span class="badge bg-success text-white">
                                                                <i class="bi bi-laptop me-1"></i>Remote
                                                            </span>
                                                        <?php endif; ?>

                                                        <?php if ($job->is_featured && $job->featured_until >= date('Y-m-d')): ?>
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="bi bi-star-fill me-1"></i>Featured
                                                            </span>
                                                        <?php endif; ?>

                                                        <?php if (!empty($job->is_verified)): ?>
                                                            <span class="badge-verified">
                                                                <i class="bi bi-patch-check-fill"></i> Verified
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Company + Location (start) | Salary (end) -->
                                                    <div class="d-flex justify-content-between align-items-center mb-3">

                                                        <!-- Left side -->
                                                        <div>
                                                    <?php if (!empty($job->anonymous) || !empty($job->is_anonymous)): ?>
                                                        <div class="d-flex align-items-center text-muted mb-1">
                                                            <i class="bi bi-shield-lock me-2"></i>
                                                            <span>Confidential Employer</span>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="d-flex align-items-center text-muted mb-1">
                                                            <i class="bi bi-building me-2"></i>
                                                            <span><?= esc($job->employer_name) ?> </span>
                                                            <span><?php if ($job->show_trust_badge): ?>
                                                                    <img src="<?= base_url('images/badge.svg') ?>"
                                                                        alt="Verified Employer"
                                                                        data-bs-toggle="tooltip"
                                                                        width="16"
                                                                        title="This employer is verified and subscribed to a trusted plan"><?php endif; ?></span>
                                                        </div>
                                                    <?php endif; ?>

                                                        <!-- Right side (Salary) -->
                                                        <div class="text-end">
                                                            <div class="fw-bold text-primary fs-4">
                                                                <?= $job->salary ? esc($job->salary) : 'Negotiable' ?>
                                                            </div>
                                                            <small class="text-muted"><?= esc($job->salary_period) ?></small>
                                                        </div>

                                                    </div>

                                                    <!-- Action buttons -->
                                                    <div class="d-flex flex-column gap-2">
                                                        <a href="<?= base_url('jobs/' . $job->slug) ?>" class="btn btn-primary">
                                                            <i class="bi bi-eye me-2"></i>View Job Details
                                                        </a>

                                                        <?php if ($job->quick_apply): ?>
                                                            <button class="btn btn-outline-primary quick-apply-btn"
                                                                data-job-id="<?= $job->id ?>">
                                                                <i class="bi bi-lightning-fill me-2"></i>Quick Apply
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                    </div>
                </div>
<?php /* Old Card closing fixed */ ?>
<?php if ($job->show_trust_badge): ?>
    <div class="card-footer border-top py-3" style="background: rgba(14, 165, 233, 0.05) !important;">
        <span class="badge-verified">
            <i class="bi bi-shield-check me-1"></i> Trusted & Verified Employer
        </span>
    </div>
<?php endif; ?>
</div>
</div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if ($total_jobs > $per_page): ?>
                    <nav class="mt-5" id="paginationContainer">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="#" data-page="<?= $current_page - 1 ?>">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </a>
                            </li>

                            <?php $total_pages = ceil($total_jobs / $per_page); ?>
                            <?php $start_page = max(1, $current_page - 2); ?>
                            <?php $end_page = min($total_pages, $start_page + 4); ?>

                            <?php if ($start_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="1">1</a>
                                </li>
                                <?php if ($start_page > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <li class="page-item <?= $current_page == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end_page < $total_pages): ?>
                                <?php if ($end_page < $total_pages - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="<?= $total_pages ?>"><?= $total_pages ?></a>
                                </li>
                            <?php endif; ?>

                            <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link" href="#" data-page="<?= $current_page + 1 ?>">
                                    Next <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                        <p class="text-center text-muted small mt-3">
                            Page <?= $current_page ?> of <?= $total_pages ?>
                            (<?= number_format($total_jobs) ?> total jobs)
                        </p>
                    </nav>
                <?php endif; ?>

                <!-- Ad-Res -->
                <ins class="adsbygoogle"
                    style="display:block"
                    data-ad-client="ca-pub-3464186884176173"
                    data-ad-slot="6229476516"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Jobs Page Custom Styles */

    /* Hero Section */
    .bg-light {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    }

    .text-gradient-primary {
        background: linear-gradient(90deg, #005DA8 0%, #005DA8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Cards & Layout */
    .card {
        border-radius: 12px !important;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .sticky-top {
        position: sticky;
        top: 100px;
    }

    /* Company Logo */
    .company-logo-container {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: white;
        border: 1px solid #e9ecef;
        padding: 8px;
    }

    .company-logo-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* View Toggle */
    .btn-group .btn.active {
        background-color: #005DA8;
        color: white;
        border-color: #005DA8;
    }

    /* Badges */
    .badge {
        border-radius: 6px;
        padding: 0.5em 0.75em;
        font-weight: 500;
    }

    .bg-opacity-10 {
        opacity: 0.1;
    }

    /* Job Types in Grid/List */
    .job-grid-view .job-item {
        margin-bottom: 1.5rem;
    }

    .job-list-view .job-item .card {
        margin-bottom: 1rem;
    }

    /* GRID VIEW */
    .job-grid-view .job-item {
        width: 50%;
    }

    @media (max-width: 992px) {
        .job-grid-view .job-item {
            width: 100%;
        }
    }

    /* LIST VIEW */
    .job-list-view .job-item {
        width: 100%;
    }

    /* Filter Count */
    #activeFilterCount {
        font-size: 0.75rem;
        padding: 0.25em 0.5em;
    }

    /* Salary Display */
    .fs-4.text-primary {
        font-weight: 700;
    }

    /* Pagination */
    .pagination .page-item.active .page-link {
        background-color: #005DA8;
        border-color: #005DA8;
        color: #e2e8f0;
    }

    .pagination .page-link {
        color: #005DA8;
        border-radius: 8px;
        margin: 0 0.25rem;
        border: 1px solid #e9ecef;
    }

    .pagination .page-link:hover {
        background-color: #f8f9fa;
        border-color: #005DA8;
    }

    /* Loading State */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .display-5 {
            font-size: 2rem;
        }

        .company-logo-container {
            width: 60px;
            height: 60px;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        .btn {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .sticky-top {
            position: static;
        }
    }

    /* Form Controls */
    .form-select,
    .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #005DA8;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Filter Checkboxes */
    .form-check-input:checked {
        background-color: #005DA8;
        border-color: #005DA8;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
    }

    /* Quick Apply Button */
    .quick-apply-btn:hover {
        background-color: #005DA8;
        color: white;
    }

    /* Bookmark Button */
    .bookmark-btn:hover {
        color: #005DA8;
    }

    .bookmark-btn.active {
        color: #005DA8;
    }

    /* Featured / Promoted Jobs */
    .featured-job .card {
        border: 2px solid rgba(255, 193, 7, 0.4);
        background: linear-gradient(135deg, #fffdf7 0%, #ffffff 100%);
    }

    .featured-ribbon {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #ffc107;
        color: #212529;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        z-index: 10;
    }

    .badge-featured {
        background-color: rgba(255, 193, 7, 0.15);
        color: #b38600;
        border: 1px solid rgba(255, 193, 7, 0.4);
        font-weight: 600;
    }

    /* Force Select2 to behave exactly like Bootstrap input */
    .select2-container--bootstrap-5 {
        width: 100% !important;
    }

    .select2-container--bootstrap-5 .select2-selection {
        height: 38px !important;
        min-height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        background-color: #fff !important;

        /* KEY FIX */
        display: flex !important;
        align-items: center !important;
    }

    /* Ensure selected text stays centered */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        display: flex !important;
        align-items: center !important;
        height: 100% !important;
        padding-left: 12px !important;
        padding-right: 32px !important;
        line-height: normal !important;
    }

    /* Fix arrow alignment */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        top: 0 !important;
        right: 8px !important;
    }

    /* Icon alignment inside selected value */
    .select2-selection__rendered i {
        margin-right: 8px;
        line-height: 1;
    }

    /* Focus state */
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }


    /* Dropdown styling */
    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        z-index: 1060 !important;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown {
        padding: 8px !important;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        padding: 6px 12px !important;
        font-size: 14px !important;
    }

    /* Results styling */
    .select2-container--bootstrap-5 .select2-results__option {
        padding: 8px 12px !important;
        font-size: 14px !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--selected {
        background-color: #0d6efd !important;
        color: white !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #e9ecef !important;
        color: #212529 !important;
    }

    /* Make sure it matches your select styling */
    .select.select2-hidden-accessible {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    /* Fix for icons */
    .select2-selection__rendered i {
        margin-right: 8px !important;
        vertical-align: middle !important;
    }

    /* Match the height with other form controls */
    .select2-container--bootstrap-5+.select2-container--bootstrap-5 {
        margin-top: 0 !important;
    }

    /* Ensure proper alignment in form groups */
    .mb-3 .select2-container--bootstrap-5 {
        margin-bottom: 0 !important;
    }

    /* Clear button styling */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
        margin-right: 25px !important;
        height: 36px !important;
        line-height: 36px !important;
    }

    /* Make sure it doesn't break the layout */
    .select2-container {
        display: block !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.searchable-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: function() {
                return $(this).attr('id') === 'state' ?
                    'Search location…' :
                    'Search industry…';
            },
            allowClear: false
        });
        // Initialize variables
        let currentView = '<?= $view_mode ?? "grid" ?>';
        let currentPage = <?= $current_page ?>;
        let isLoading = false;

        // Helper: Update URL without reload
        function updateURL(params) {
            const url = new URL(window.location);
            Object.keys(params).forEach(key => {
                if (params[key]) {
                    url.searchParams.set(key, params[key]);
                } else {
                    url.searchParams.delete(key);
                }
            });
            history.replaceState(null, '', url);
        }

        // Helper: Get all filters
        function getFilters(page = 1) {
            const jobTypes = [];
            document.querySelectorAll('.job-type-filter:checked').forEach(checkbox => {
                jobTypes.push(checkbox.value);
            });

            return {
                keywords: document.getElementById('searchKeyword').value.trim(),
                state_id: document.getElementById('locationFilter').value,
                job_type: jobTypes.length ? jobTypes.join(',') : '',
                experience_level: document.getElementById('experienceLevel').value,
                industry_id: document.getElementById('industryFilter').value,
                salary_min: document.getElementById('minSalary').value,
                salary_max: document.getElementById('maxSalary').value,
                sort_by: document.getElementById('sortBy').value,
                view_mode: currentView,
                page: page,
                per_page: <?= $per_page ?>
            };
        }

        // Helper: Update active filter count
        function updateFilterCount() {
            const filters = getFilters();
            let count = 0;

            if (filters.keywords) count++;
            if (filters.state_id) count++;
            if (filters.job_type) count++;
            if (filters.experience_level) count++;
            if (filters.industry_id) count++;
            if (filters.salary_min || filters.salary_max) count++;

            document.getElementById('activeFilterCount').textContent = count;
        }

        // Helper: Show loading state
        function showLoading() {
            isLoading = true;
            document.getElementById('loadingState').classList.remove('d-none');
            document.getElementById('jobCardsContainer').classList.add('d-none');
            document.getElementById('noResults').classList.add('d-none');
            document.getElementById('paginationContainer')?.classList.add('d-none');
        }

        // Helper: Hide loading state
        function hideLoading() {
            isLoading = false;
            document.getElementById('loadingState').classList.add('d-none');
            document.getElementById('jobCardsContainer').classList.remove('d-none');
        }

        // Helper: Format number with commas
        function formatNumber(num) {
            return new Intl.NumberFormat().format(num);
        }

        // Function: Fetch jobs via AJAX
        async function fetchJobs(filters, pushState = true) {
            if (isLoading) return;

            showLoading();
            updateFilterCount();

            try {
                const response = await fetch(window.location.pathname + '?' + new URLSearchParams(filters), {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                });

                const data = await response.json();

                if (pushState) {
                    updateURL(filters);
                }

                updateJobList(data);
                currentPage = filters.page;

            } catch (error) {
                console.error('Error fetching jobs:', error);
                toastr.error('Failed to load jobs. Please try again.');
            } finally {
                hideLoading();
            }
        }

        // Function: Update job list with new data
        function updateJobList(data) {
            const container = document.getElementById('jobCardsContainer');
            const resultsCount = document.getElementById('resultsCount');
            const filterSummary = document.getElementById('filterSummary');
            const noResults = document.getElementById('noResults');
            const pagination = document.getElementById('paginationContainer');

            // Update counts
            resultsCount.textContent = formatNumber(data.total_jobs);
            filterSummary.textContent = `Showing ${data.jobs.length} of ${formatNumber(data.total_jobs)} results`;

            // Clear container
            container.innerHTML = '';

            // Handle no results
            if (data.jobs.length === 0) {
                noResults.classList.remove('d-none');
                pagination?.classList.add('d-none');
                return;
            }

            noResults.classList.add('d-none');
            pagination?.classList.remove('d-none');

            // Add job cards
            data.jobs.forEach(job => {
                const jobCard = createJobCard(job);
                container.appendChild(jobCard);
            });

            // Update pagination
            updatePagination(data);
        }

        // Function: Create job card HTML
        function createJobCard(job) {
            const isFeatured = job.is_featured == 1;
            const div = document.createElement('div');
            div.className = `col-12 job-item ${isFeatured ? 'featured-job' : ''}`;
            div.setAttribute('data-featured', isFeatured ? '1' : '0');
            div.setAttribute('data-title', job.title);
            div.setAttribute('data-location', job.location);
            div.setAttribute('data-type', job.job_type);
            div.setAttribute('data-experience', job.experience_level);
            div.setAttribute('data-industry', job.industry_name);
            div.setAttribute('data-salary', job.salary);


            const salary = job.salary ? (job.salary) : 'Negotiable';
            const jobType = job.job_type.replace('_', ' ');
            const today = '<?= date('Y-m-d') ?>';
            const featuredUntil = job.featured_until ? String(job.featured_until).slice(0, 10) : null;
            const isFeaturedActive = Number(job.is_featured) === 1 && featuredUntil && featuredUntil >= today;
            const isAnonymousJob = job.anonymous || job.is_anonymous;
            const companyLogo = isAnonymousJob ? '<?= base_url('images/favicon.png') ?>' : job.company_logo;
            const employerName = isAnonymousJob ? 'Confidential Employer' : job.employer_name;
            const trustBadge = !isAnonymousJob && job.show_trust_badge ? `
                <span><img src="<?= base_url('images/badge.svg') ?>"
                    alt="Verified Employer"
                    data-bs-toggle="tooltip"
                    width="16"
                    title="This employer is verified and subscribed to a trusted plan"></span>` : '';

            div.innerHTML = `
            <div class="glass-card h-100 p-4 position-relative">
                ${isFeaturedActive ? `
                <div class="featured-ribbon">
                    <i class="bi bi-star-fill me-1"></i>Promoted
                </div>
                ` : ''}
                <div>
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <div class="company-logo-container mx-auto">
                                <img src="${companyLogo}" 
                                     alt="${employerName}"
                                     class="img-fluid rounded-3"
                                     loading="lazy">
                            </div>
                        </div>
                        <div class="col-md-12">

    <!-- Job title -->
    <div class="mb-2">
        <h5 class="fw-bold mb-0">
            <a href="${job.detail_url}" class="text-decoration-none">
                ${job.title}
            </a>
            ${job.show_trust_badge ? `
                <span class="badge-verified ms-2" title="Verified Employer">
                    <i class="bi bi-patch-check-fill"></i>
                </span>
            ` : ''}
        </h5>
    </div>

    <!-- Badges -->
    <div class="d-flex flex-wrap gap-2 mb-3">
        <span class="badge bg-primary text-white">
            ${jobType.charAt(0).toUpperCase() + jobType.slice(1)}
        </span>

        ${job.remote_available ? `
        <span class="badge bg-success bg-opacity-10 text-success">
            <i class="bi bi-laptop me-1"></i>Remote
        </span>
        ` : ''}

        ${isFeaturedActive ?
        `<span class="badge bg-warning text-dark">
            <i class="bi bi-star-fill me-1"></i>Featured
        </span>` : ''}
    </div>

    <!-- Company + Location (left) | Salary (right) -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <!-- Left -->
        <div>
            <div class="d-flex align-items-center text-muted mb-1">
                <i class="bi ${isAnonymousJob ? 'bi-shield-lock' : 'bi-building'} me-2"></i>
                <span>${employerName}</span>
            </div>
            <div class="d-flex align-items-center text-muted">
                <i class="bi bi-geo-alt me-2"></i>
                <span>${job.location || 'Remote'}</span>
            </div>
        </div>

        <!-- Right -->
        <div class="text-end">
            <div class="fw-bold text-primary fs-6">
                ${salary}
            </div>
            <small class="text-muted">${job.salary_period || 'Monthly'}</small>
        </div>

    </div>

    <!-- Buttons -->
    <div class="d-flex flex-column gap-2">
        <a href="${job.detail_url}" class="btn btn-primary">
            <i class="bi bi-eye me-2"></i>View Job Details
        </a>

        ${job.quick_apply ? `
        <button class="btn btn-outline-primary quick-apply-btn"
                data-job-id="${job.id}">
            <i class="bi bi-lightning-fill me-2"></i>Quick Apply
        </button>
        ` : ''}
    </div>

</div>
</div>
</div>
                ${job.show_trust_badge ? `
                    <div class="card-footer border-top py-3" style="background: rgba(14, 165, 233, 0.05) !important;">
                        <span class="badge-verified">
                            <i class="bi bi-shield-check me-1"></i> Trusted & Verified Employer
                        </span>
                    </div>
                ` : ''}
            </div>
        `;

            return div;
        }

        function timeAgo(input) {

            if (!input) return '';

            let past;

            // If already a Date object
            if (input instanceof Date) {
                past = input;
            }
            // If timestamp (number)
            else if (typeof input === 'number') {
                past = new Date(input);
            }
            // If string
            else if (typeof input === 'string') {
                const normalized = input
                    .replace(' ', 'T')
                    .replace(/\.\d+$/, 'Z');

                past = new Date(normalized);
            }
            // If serialized DateTime-like object
            else if (typeof input === 'object' && input.date) {
                const normalized = String(input.date)
                    .replace(' ', 'T')
                    .replace(/\.\d+$/, 'Z');
                past = new Date(normalized);
            } else {
                return '';
            }

            if (isNaN(past)) return '';

            const now = new Date();
            const seconds = Math.floor((now - past) / 1000);

            const intervals = [{
                    label: 'year',
                    seconds: 31536000
                },
                {
                    label: 'month',
                    seconds: 2592000
                },
                {
                    label: 'day',
                    seconds: 86400
                },
                {
                    label: 'hour',
                    seconds: 3600
                },
                {
                    label: 'minute',
                    seconds: 60
                }
            ];

            for (const interval of intervals) {
                const count = Math.floor(seconds / interval.seconds);
                if (count >= 1) {
                    return `${count} ${interval.label}${count > 1 ? 's' : ''} ago`;
                }
            }

            return 'just now';
        }

        // Function: Update pagination
        function updatePagination(data) {
            const pagination = document.getElementById('paginationContainer');
            if (!pagination) return;

            const totalPages = Math.ceil(data.total_jobs / data.per_page);
            let paginationHTML = `
            <ul class="pagination justify-content-center">
                <li class="page-item ${data.current_page <= 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${data.current_page - 1}">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                </li>
        `;

            let startPage = Math.max(1, data.current_page - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (startPage > 1) {
                paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="1">1</a>
                </li>
            `;
                if (startPage > 2) {
                    paginationHTML += `
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
                <li class="page-item ${data.current_page == i ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationHTML += `
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `;
                }
                paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
                </li>
            `;
            }

            paginationHTML += `
                <li class="page-item ${data.current_page >= totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${data.current_page + 1}">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
            <p class="text-center text-muted small mt-3">
                Page ${data.current_page} of ${totalPages}
                (${formatNumber(data.total_jobs)} total jobs)
            </p>
        `;

            pagination.innerHTML = paginationHTML;
        }

        // --------------------------------------------------
        // EVENTS
        // --------------------------------------------------

        // Apply filters
        document.getElementById('applyFilters').addEventListener('click', () => {
            fetchJobs(getFilters(1));
        });

        // Reset filters
        document.getElementById('clearFilters').addEventListener('click', () => {
            document.querySelectorAll('input, select').forEach(el => {
                if (el.type === 'checkbox') el.checked = false;
                else el.value = '';
            });
            fetchJobs(getFilters(1));
        });

        document.getElementById('resetFiltersBtn')?.addEventListener('click', () => {
            document.getElementById('clearFilters').click();
        });

        // Pagination click handling (delegated)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('.page-link');
            if (!link || !link.dataset.page) return;

            e.preventDefault();

            const page = parseInt(link.dataset.page);
            if (!isNaN(page)) {
                fetchJobs(getFilters(page));
            }
        });

        // View toggle
        document.getElementById('gridView').addEventListener('click', () => {
            currentView = 'grid';
            document.getElementById('jobList').classList.remove('job-list-view');
            document.getElementById('jobList').classList.add('job-grid-view');
            fetchJobs(getFilters(currentPage));
        });

        document.getElementById('listView').addEventListener('click', () => {
            currentView = 'list';
            document.getElementById('jobList').classList.remove('job-grid-view');
            document.getElementById('jobList').classList.add('job-list-view');
            fetchJobs(getFilters(currentPage));
        });

        // Initial filter count
        updateFilterCount();
    });
</script>
<?= $this->endSection() ?>