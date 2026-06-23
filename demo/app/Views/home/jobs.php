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
<main id="main-content">
  <section class="jobs-hero" aria-label="Find jobs in Nigeria">
    <span class="jobs-hero-grid" aria-hidden="true"></span>
    <svg class="jobs-hero-motif" viewBox="0 0 400 400" fill="none" aria-hidden="true" focusable="false">
      <defs>
        <radialGradient id="jhMotifGlow" cx="50%" cy="42%" r="55%">
          <stop offset="0%" stop-color="var(--accent)" stop-opacity=".22"/>
          <stop offset="100%" stop-color="var(--accent)" stop-opacity="0"/>
        </radialGradient>
      </defs>
      <circle cx="200" cy="188" r="150" fill="url(#jhMotifGlow)"/>
      <line x1="262" y1="252" x2="318" y2="312" stroke="var(--accent)" stroke-width="26" stroke-linecap="round"/>
      <circle cx="200" cy="190" r="78" fill="none" stroke="var(--accent)" stroke-width="26"/>
      <circle cx="200" cy="78" r="30" fill="var(--accent)"/>
    </svg>
    <div class="container jobs-hero-inner">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="<?= base_url() ?>" style="color:rgba(255,255,255,.8);">Home</a>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="transform:rotate(-90deg);margin:0 4px;color:rgba(255,255,255,.5);"><path d="m6 9 6 6 6-6"/></svg>
        <span style="color:#fff;font-weight:500;">Find Jobs</span>
      </nav>
      <h1 class="jobs-hero-title">Find Your Next <em>Job</em> in Nigeria</h1>
      <p class="jobs-hero-sub" style="color: #ffffff;">Browse full-time, part-time, and remote vacancies in Lagos, Abuja, Port Harcourt and across all 36 states. New listings added daily.</p>

      <div class="search-card">
        <div class="search-field">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input type="text" id="searchKeyword" placeholder="Job title, skills..." value="<?= esc($keywords ?? '') ?>">
        </div>
        <div class="search-field">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <select id="locationFilter">
            <option value="">All Locations</option>
            <?php foreach ($states as $state): ?>
              <option value="<?= esc($state->id) ?>" <?= $stateId == $state->id ? 'selected' : '' ?>><?= esc($state->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="search-field">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
          <select id="categoryFilterHero">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
              <option value="<?= esc($category->id) ?>" <?= $categoryId == $category->id ? 'selected' : '' ?>><?= esc($category->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button id="applyFiltersHero" class="btn-primary" style="background:var(--accent);color:var(--brand-deep);border:none;">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg> Search jobs
        </button>
      </div>

      <div class="jobs-trending">
        <strong>Popular:</strong>
        <?php 
        foreach ($states as $state) {
            if (in_array($state->name, ['Lagos', 'Abuja', 'Federal Capital Territory', 'Rivers'])) {
                ?>
                <a href="#" class="trending-tag" data-state-id="<?= esc($state->id) ?>"><?= esc($state->name) ?></a>
                <?php
            }
        }
        ?>
        <a href="#" class="trending-tag" data-job-type="remote">Remote</a>
      </div>
    </div>
  </section>

  <!-- SIGNATURE: LIVE JOB TICKER -->
  <div class="ticker" role="marquee" aria-live="off">
    <div class="ticker-label" style="background:var(--accent);color:var(--brand-deep);">
      <span class="ticker-dot" aria-hidden="true"></span> Live vacancies
    </div>
    <div class="ticker-viewport">
      <div class="ticker-track">
        <?php if (!empty($jobs)): ?>
          <?php foreach (array_slice($jobs, 0, 5) as $job): ?>
            <a href="<?= base_url('jobs/' . $job->slug) ?>" class="ticker-item">
              <span class="ticker-role"><?= esc($job->title) ?></span>
              <span class="ticker-co"><?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Confidential' : esc($job->employer_name) ?></span>
              <span class="ticker-loc"><svg aria-hidden="true" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg> <?= esc($job->location ?? 'Nigeria') ?></span>
              <span class="ticker-new" style="background:var(--accent);color:var(--brand-deep);">NEW</span>
            </a>
          <?php endforeach; ?>
          <!-- Duplicate to make scrolling loop smooth -->
          <?php foreach (array_slice($jobs, 0, 5) as $job): ?>
            <a href="<?= base_url('jobs/' . $job->slug) ?>" class="ticker-item">
              <span class="ticker-role"><?= esc($job->title) ?></span>
              <span class="ticker-co"><?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Confidential' : esc($job->employer_name) ?></span>
              <span class="ticker-loc"><svg aria-hidden="true" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg> <?= esc($job->location ?? 'Nigeria') ?></span>
              <span class="ticker-new" style="background:var(--accent);color:var(--brand-deep);">NEW</span>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

    <!-- Main Content -->
    <div class="jobs-content">
      <div class="container">
        <div class="jobs-layout">
          <!-- Sidebar Filters -->
          <aside class="jobs-sidebar">
            <div class="jobs-filters-card">
              <div class="jobs-filters-header">
                <span class="jobs-filters-count" id="activeFilterCount">0</span>
                <h5 class="fw-bold mb-0">
                  <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 3H2a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h20a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM8 21V4"/><path d="M2 10h20"/><path d="M2 14h20"/><path d="M12 18h8"/><path d="M12 6h8"/></svg>
                  Filters
                </h5>
              </div>

              <div class="jobs-filters-body">
                <!-- Job Type -->
                <div class="mb-4">
                  <label class="form-label fw-semibold mb-2">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    Job Type
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

                <!-- Industry -->
                <div class="mb-4">
                  <label class="form-label fw-semibold mb-2">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 21h18M5 21V7l8-4 8 4v14M8 21v-4h8v4"/></svg>
                    Industry
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
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Salary Range
                  </label>
                  <div class="row g-2">
                    <div class="col-6">
                      <input type="number" id="minSalary" class="form-control" placeholder="Min" value="<?= esc($salaryMin ?? '') ?>">
                    </div>
                    <div class="col-6">
                      <input type="number" id="maxSalary" class="form-control" placeholder="Max" value="<?= esc($salaryMax ?? '') ?>">
                    </div>
                  </div>
                </div>

                <!-- Sort Options -->
                <div class="mb-4">
                  <label class="form-label fw-semibold mb-2">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 6h18M7 12h10M10 18h4"/></svg>
                    Sort By
                  </label>
                  <select id="sortBy" class="form-select">
                    <option value="newest" <?= $sort_by == 'newest' ? 'selected' : '' ?>>Newest First</option>
                    <option value="oldest" <?= $sort_by == 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                    <option value="salary_high" <?= $sort_by == 'salary_high' ? 'selected' : '' ?>>Salary (High to Low)</option>
                    <option value="salary_low" <?= $sort_by == 'salary_low' ? 'selected' : '' ?>>Salary (Low to High)</option>
                  </select>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 mt-4">
                  <button class="btn btn-primary" id="applyFilters">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                    Apply Filters
                  </button>
                  <button class="btn btn-outline-secondary" id="clearFilters">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                    Reset All
                  </button>
                </div>
              </div>
            </div>
          </aside>

          <!-- Job Listings -->
          <div class="jobs-main">
            <!-- Results Header -->
            <div class="jobs-results-header">
              <div>
                <h5 class="fw-bold mb-0">
                  <span id="resultsCount"><?= number_format($total_jobs) ?></span> Jobs Found
                </h5>
                <p class="text-muted small mb-0 mt-1" id="filterSummary">
                  Showing <?= count($jobs) ?> of <?= number_format($total_jobs) ?> results
                </p>
              </div>
            </div>

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
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <h4 class="fw-bold mb-3">No jobs found</h4>
                <p class="text-muted mb-4">Try adjusting your filters or search keywords</p>
                <button class="btn btn-primary" id="resetFiltersBtn">
                  <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                  Reset Filters
                </button>
              </div>
            </div>


            <!-- Job Cards Grid -->
            <div class="jobs-grid" id="jobCardsContainer">
              <?php if (empty($jobs)): ?>
                <!-- No results handled by noResults div -->
              <?php else: ?>
                <?php foreach ($jobs as $job): ?>
                  <div class="job-card"
                    data-title="<?= esc($job->title) ?>"
                    data-location="<?= esc($job->location) ?>"
                    data-type="<?= esc($job->job_type) ?>"
                    data-experience="<?= esc($job->experience_level) ?>"
                    data-industry="<?= esc($job->industry_name) ?>"
                    data-salary="<?= esc($job->salary) ?>">
                    
                    <?php if ($job->is_featured && $job->featured_until >= date('Y-m-d')): ?>
                      <div class="badge-featured">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></svg>
                        Featured
                      </div>
                    <?php endif; ?>

                    <div class="job-card-top">
                      <div class="job-logo">
                        <?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'CV' : esc(substr($job->employer_name ?? 'C', 0, 2)) ?>
                      </div>
                      <div class="job-title">
                        <a href="<?= base_url('jobs/' . $job->slug) ?>" class="text-decoration-none">
                          <?= esc($job->title) ?>
                        </a>
                      </div>
                    </div>

                    <div class="job-company">
                      <span class="job-company-name">
                        <?php if (!empty($job->anonymous) || !empty($job->is_anonymous)): ?>
                          Confidential Employer
                        <?php else: ?>
                          <?= esc($job->employer_name) ?>
                        <?php endif; ?>
                      </span>
                      <?php if ($job->show_trust_badge): ?>
                        <button type="button" class="verified-check" aria-label="Verified employer">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                          <span class="verified-tip">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <strong>Verified</strong> employer
                          </span>
                        </button>
                      <?php endif; ?>
                    </div>

                    <div class="job-meta">
                      <span>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?= esc($job->location ?? 'Nigeria') ?>
                      </span>
                      <?php if ($job->remote_available): ?>
                        <span class="badge-verified">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                          Remote
                        </span>
                      <?php endif; ?>
                    </div>

                    <div class="job-salary-row">
                      <span class="job-salary">
                        <?= $job->salary ? esc($job->salary) : 'Negotiable' ?>
                      </span>
                      <span class="text-muted small"><?= esc($job->salary_period ?? 'monthly') ?></span>
                    </div>

                    <div class="job-actions">
                      <a href="<?= base_url('jobs/' . $job->slug) ?>" class="btn btn-primary">
                        View Details
                      </a>
                      <?php $isSaved = in_array($job->id, $savedJobIds ?? []); ?>
                      <button class="save-btn" data-job-id="<?= $job->id ?>" data-saved="<?= $isSaved ? 'true' : 'false' ?>">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                        Save
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_jobs > $per_page): ?>
              <nav class="mt-5 jobs-pagination">
                <ul class="pagination justify-content-center">
                  <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="#" data-page="<?= $current_page - 1 ?>">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                      Previous
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
                      Next
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                  </li>
                </ul>
                <p class="text-center text-muted small mt-3">
                  Page <?= $current_page ?> of <?= $total_pages ?>
                  (<?= number_format($total_jobs) ?> total jobs)
                </p>
              </nav>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>

<style>
:root {
  --white: #ffffff;
  --text: var(--text-dark, #1E293B);
  --muted: var(--text-muted, #64748B);
  --border: var(--border-light, #e2e8f0);
  --bg: var(--bg-white, #FFFFFF);
  --transition: all 0.25s ease-in-out;
}

/* ═══════════════════════════════════════════════════════════════════
   JOBS LISTING PAGE — Brand colors: var(--brand) (blue), var(--accent) (orange)
   ═══════════════════════════════════════════════════════════════════ */

/* ── Hero Section ── */
.jobs-hero {
  background:
    radial-gradient(ellipse 70% 60% at 82% 20%, rgba(240, 143, 26, 0.16) 0%, transparent 55%),
    radial-gradient(ellipse 80% 70% at 10% 90%, rgba(13, 96, 158, 0.34) 0%, transparent 55%),
    linear-gradient(160deg, var(--brand-deep) 0%, var(--brand-dark) 55%, var(--brand) 100%);
  color: var(--white);
  padding: 64px 0 54px;
  position: relative; overflow: hidden;
}
.jobs-hero-grid {
  position: absolute; inset: 0; pointer-events: none; opacity: .5;
  background-image:
    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size: 46px 46px;
  -webkit-mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%);
          mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, #000 30%, transparent 80%);
}
.jobs-hero-motif {
  position: absolute; top: 46%; right: -50px; transform: translateY(-50%);
  width: min(500px, 44vw); height: auto; pointer-events: none; z-index: 0;
  opacity: .55;
}
.jobs-hero-motif circle, .jobs-hero-motif line {
  transform-origin: center;
}
@media (max-width: 900px) {
  .jobs-hero-motif {
    top: -30px; right: -70px; transform: none;
    width: 240px; opacity: .28;
  }
}
@media (max-width: 580px) {
  .jobs-hero-motif { top: -24px; right: -80px; width: 190px; opacity: .22; }
}
.jobs-hero-inner { position: relative; z-index: 1; }

.jobs-hero-title {
  font-size: clamp(1.9rem, 4.8vw, 3.1rem);
  font-weight: 800; line-height: 1.15; margin-bottom: 16px;
}
.jobs-hero-title em { font-style: normal; color: var(--accent); }
.jobs-hero-sub {
  font-size: 1rem; opacity: .9; max-width: 560px; margin-bottom: 28px;
}

/* ── Search Card ── */
.search-card {
  background: var(--white); border-radius: 12px;
  padding: 10px; display: flex; flex-wrap: wrap; gap: 8px;
  box-shadow: 0 14px 40px rgba(10,47,87,.16); max-width: 820px;
}
.search-field {
  position: relative; flex: 1 1 150px; display: flex; align-items: center;
}
.search-field svg {
  position: absolute; left: 12px; width: 17px; height: 17px; color: var(--muted); pointer-events: none;
}
.search-card input, .search-card select {
  width: 100%; border: 1px solid var(--border); border-radius: 7px;
  padding: 11px 14px 11px 38px; font-family: 'Inter', sans-serif; font-size: 1rem;
  color: var(--text); background: var(--bg); outline: none; appearance: none; -webkit-appearance: none; min-height: 46px;
}
.search-card select { padding-left: 38px; }
.search-card input:focus, .search-card select:focus {
  border-color: var(--brand); background: var(--white);
}
.search-card > button {
  flex: 0 0 auto; padding: 11px 24px; background: var(--accent); color: var(--brand-deep);
  border: none; border-radius: 7px; font-family: 'Inter', sans-serif;
  font-size: 1rem; font-weight: 600; cursor: pointer; transition: var(--transition);
  min-height: 46px; display: inline-flex; align-items: center; gap: 7px;
}
.search-card > button svg { width: 17px; height: 17px; }
.search-card > button:hover { background: var(--accent-dark); }

.jobs-trending {
  display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 18px; font-size: .8rem;
}
.jobs-trending strong { opacity: .8; letter-spacing: .04em; }
.jobs-trending a {
  background: rgba(255,255,255,.12); color: var(--white);
  padding: 5px 12px; border-radius: 20px; font-weight: 500;
  border: 1px solid rgba(255,255,255,.2); transition: var(--transition);
  min-height: 32px; display: inline-flex; align-items: center;
}
.jobs-trending a:hover { background: rgba(255,255,255,.26); text-decoration: none; }

/* ── Ticker Section ── */
.ticker {
  position: relative; z-index: 1;
  background: rgba(7, 48, 79, 0.7);
  border-top: 1px solid rgba(255,255,255,.12);
  backdrop-filter: blur(6px);
  overflow: hidden; display: flex; align-items: stretch;
}
.ticker-label {
  flex-shrink: 0; display: flex; align-items: center; gap: 8px;
  background: var(--accent); color: var(--brand-deep);
  font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
  padding: 0 16px; z-index: 2;
}
.ticker-dot { width: 9px; height: 9px; border-radius: 50%; background: #fff; box-shadow: 0 0 0 1.5px rgba(10,47,87,.55); animation: pulse 1.5s ease-in-out infinite; }
@keyframes pulse { 0%,100% { transform: scale(1); opacity: 1; } 50% { transform: scale(.72); opacity: .7; } }
.ticker-viewport { flex: 1; overflow: hidden; position: relative; -webkit-mask-image: linear-gradient(90deg, transparent, #000 4%, #000 96%, transparent); mask-image: linear-gradient(90deg, transparent, #000 4%, #000 96%, transparent); }
.ticker-track { display: inline-flex; align-items: center; white-space: nowrap; padding: 12px 0; will-change: transform; animation: ticker-scroll 48s linear infinite; }
.ticker-viewport:hover .ticker-track { animation-play-state: paused; }
@keyframes ticker-scroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.ticker-item {
  display: inline-flex; align-items: center; gap: 8px;
  color: rgba(255,255,255,.92); font-size: .82rem; padding: 0 22px;
  border-right: 1px solid rgba(255,255,255,.1); text-decoration: none;
}
.ticker-item:hover { text-decoration: none; color: var(--white); }
.ticker-item:hover .ticker-role { color: var(--accent); }
.ticker-role { font-weight: 600; }
.ticker-co { opacity: .65; }
.ticker-loc { display: inline-flex; align-items: center; gap: 4px; opacity: .65; font-size: .78rem; }
.ticker-loc svg { width: 11px; height: 11px; }
.ticker-new { background: var(--accent); color: var(--brand-deep); font-size: .64rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; letter-spacing: .04em; }

/* ── Layout ── */
.jobs-section { padding-bottom: 80px; }
.jobs-content { padding: 40px 0 80px; }
.jobs-layout {
  display: grid; grid-template-columns: 260px 1fr; gap: 24px;
  align-items: start;
}
@media (max-width: 992px) { .jobs-layout { grid-template-columns: 1fr; } }

/* ── Filters Card ── */
.jobs-filters-card {
  background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 24px;
  box-shadow: 0 2px 14px rgba(10,47,87,.06);
  position: sticky; top: 100px;
}
.jobs-filters-header {
  display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
}
.jobs-filters-count {
  font-size: .72rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--brand);
  background: var(--brand-light); padding: 3px 8px; border-radius: 12px;
}
.jobs-filters-header h5 {
  font-size: .92rem; font-weight: 700; margin: 0;
  display: flex; align-items: center; gap: 8px;
}
.jobs-filters-header h5 svg { width: 17px; height: 17px; color: var(--brand); }

.jobs-filters-body { display: flex; flex-direction: column; gap: 20px; }

/* ── Results Header ── */
.jobs-results-header {
  display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;
  margin-bottom: 24px;
}

/* ── Job Cards Grid ── */
.jobs-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 18px;
}

.job-card {
  position: relative;
  background: var(--white); border: 1px solid var(--border); border-radius: 10px;
  padding: 22px; transition: var(--transition); display: flex; flex-direction: column; gap: 11px;
}
.job-card:hover {
  box-shadow: 0 14px 40px rgba(10,47,87,.16); border-color: var(--brand); transform: translateY(-3px);
}
.job-card--featured { border-left: 3px solid var(--accent); }

.badge-featured {
  position: absolute; top: -9px; left: 16px; z-index: 2;
  background: var(--accent); color: var(--brand-deep);
  font-size: .68rem; font-weight: 700; padding: 4px 11px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 5px; letter-spacing: .03em;
  box-shadow: 0 2px 6px rgba(10,47,87,.18);
}
.badge-featured svg { width: 12px; height: 12px; }

.job-card-top {
  display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;
}
.job-logo {
  width: 44px; height: 44px; border-radius: 9px; background: var(--brand-light);
  color: var(--brand); display: flex; align-items: center; justify-content: center;
  font-family: 'Sora', sans-serif; font-weight: 700; font-size: .9rem; flex-shrink: 0;
  border: 1px solid var(--border);
}
.job-title {
  font-size: 1rem; font-weight: 700; overflow-wrap: anywhere; word-break: break-word;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.job-title a { color: var(--text); text-decoration: none; transition: var(--transition); }
.job-title a:hover { color: var(--brand); }

.job-company {
  font-size: .82rem; color: var(--muted); display: inline-flex; align-items: center; gap: 5px; max-width: 100%;
}
.job-company-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0; }

.verified-check {
  position: relative; display: inline-flex; align-items: center; justify-content: center;
  color: var(--brand); flex-shrink: 0; background: none; border: none; padding: 0 0 0 1px;
  margin: 0; cursor: pointer; line-height: 0; vertical-align: middle;
}
.verified-check svg { width: 14px; height: 14px; pointer-events: none; }
.verified-check:hover { color: var(--brand-dark); }
.verified-tip {
  position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%) translateY(4px);
  background: #ffffff; color: var(--text); font-size: .72rem; font-weight: 600; line-height: 1.4;
  white-space: nowrap; padding: 7px 11px; border-radius: 8px; border: 1px solid var(--border);
  box-shadow: 0 8px 24px rgba(10,47,87,.16); opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity .16s ease, transform .16s ease; z-index: 40; display: inline-flex; align-items: center; gap: 6px;
}
.verified-tip::after {
  content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
  border: 6px solid transparent; border-top-color: #ffffff; filter: drop-shadow(0 1px 0 var(--border));
}
.verified-tip svg { width: 13px; height: 13px; color: var(--success); }
.verified-tip strong { font-weight: 700; }
.verified-check.open .verified-tip, .verified-check:hover .verified-tip {
  opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0);
}

.job-meta {
  display: flex; flex-wrap: wrap; gap: 12px; font-size: .78rem; color: var(--muted);
}
.job-meta span { display: inline-flex; align-items: center; gap: 5px; }
.job-meta svg { width: 13px; height: 13px; color: var(--muted); }
.job-meta .badge-verified {
  background: var(--brand-light); color: var(--brand); padding: 2px 8px; border-radius: 12px;
}

.job-salary-row {
  display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
}
.job-salary {
  font-size: .92rem; font-weight: 700; color: var(--accent-dark);
}

.job-actions {
  display: flex; gap: 8px; margin-top: 4px;
}
.job-actions .btn { flex: 1; padding: 10px; font-size: .82rem; min-height: 44px; }
.btn-primary {
  background: var(--brand); color: var(--white); border-color: var(--brand);
  transition: var(--transition);
}
.btn-primary:hover { background: var(--brand-dark); border-color: var(--brand-dark); text-decoration: none; }

.save-btn {
  background: none; border: 1.5px solid var(--border); border-radius: 8px;
  padding: 10px 13px; cursor: pointer; color: var(--muted);
  display: inline-flex; align-items: center; gap: 6px; font-size: .82rem;
  font-family: 'Inter', sans-serif; transition: var(--transition);
  min-height: 44px;
}
.save-btn svg { width: 15px; height: 15px; }
.save-btn:hover { border-color: var(--brand); color: var(--brand); }
.save-btn[data-saved="true"] { color: var(--success); border-color: var(--success); }

/* ── Pagination ── */
.jobs-pagination .page-item.active .page-link {
  background-color: var(--brand); border-color: var(--brand); color: var(--white);
}
.jobs-pagination .page-link {
  color: var(--brand); border-radius: 8px; margin: 0 0.25rem; border: 1px solid var(--border);
}
.jobs-pagination .page-link:hover { background-color: var(--brand-light); border-color: var(--brand); }

/* ── Loading State ── */
.spinner-border { width: 3rem; height: 3rem; }

/* ── Mobile ── */
@media (max-width: 768px) {
  .jobs-filters-card { position: static; }
  .jobs-grid { grid-template-columns: 1fr; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Apply filters
$('#applyFilters, #applyFiltersHero').on('click', function() {
  const filters = {
    keywords: $('#searchKeyword').val(),
    location: $('#locationFilter').val(),
    category: $('#categoryFilterHero').val(),
    industry: $('#industryFilter').val(),
    minSalary: $('#minSalary').val(),
    maxSalary: $('#maxSalary').val(),
    sort: $('#sortBy').val(),
    jobTypes: []
  };

  $('.job-type-filter:checked').each(function() {
    filters.jobTypes.push($(this).val());
  });

  const url = new URL(window.location.origin + window.location.pathname);
  if (filters.keywords) url.searchParams.set('keywords', filters.keywords);
  if (filters.location) url.searchParams.set('state_id', filters.location);
  if (filters.category) url.searchParams.set('category_id', filters.category);
  if (filters.industry) url.searchParams.set('industry_id', filters.industry);
  if (filters.minSalary) url.searchParams.set('salary_min', filters.minSalary);
  if (filters.maxSalary) url.searchParams.set('salary_max', filters.maxSalary);
  if (filters.sort) url.searchParams.set('sort_by', filters.sort);

  if (filters.jobTypes.length > 0) {
    url.searchParams.set('job_type', filters.jobTypes.join(','));
  }

  window.location.href = url.toString();
});

// Reset filters
$('#clearFilters').on('click', function() {
  const url = new URL(window.location.origin + window.location.pathname);
  window.location.href = url.toString();
});

// Trending tags click handler
$('.trending-tag').on('click', function(e) {
  e.preventDefault();
  const stateId = $(this).data('state-id');
  const jobType = $(this).data('job-type');
  
  if (stateId) {
    $('#locationFilter').val(stateId);
  }
  if (jobType === 'remote') {
    // Clear other job types and check remote if it exists
    $('.job-type-filter').prop('checked', false);
    $('#remote').prop('checked', true);
  }
  
  $('#applyFiltersHero').click();
});

// Reset filters button
$('#resetFiltersBtn').on('click', function() {
  $('#clearFilters').click();
});

// Pagination
$('#paginationContainer a[data-page]').on('click', function(e) {
  e.preventDefault();
  const page = $(this).data('page');
  const url = new URL(window.location);
  url.searchParams.set('page', page);
  window.location.href = url.toString();
});

// Save job
$('.save-btn').on('click', function() {
  const btn = $(this);
  const jobId = btn.data('job-id');
  const isSaved = btn.data('saved') === 'true';

  btn.prop('disabled', true).text('Processing…');

  $.ajax({
    url: "<?= site_url('jobs/toggle-save') ?>/" + jobId,
    method: "POST",
    success: function(r) {
      if (r.success) {
        const saved = r.saved;
        btn.data('saved', saved ? 'true' : 'false');
        btn.toggleClass('saved', saved);
        btn.html(saved ?
          '<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg> Saved' :
          '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg> Save'
        );
      } else { toastr.error(r.message); }
    },
    complete: function() { btn.prop('disabled', false); },
    error: function() { toastr.error("Network error. Try again."); btn.prop('disabled', false); }
  });
});
</script>
<?= $this->endSection() ?>