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
    'name'          => 'Jobs in Nigeria',
    'description'   => 'Verified job vacancies across Nigeria on JobberRecruit.',
    'url'           => current_url(),
    'numberOfItems' => (int) $total_jobs,
    'itemListElement' => $listItems,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main">

  <!-- PAGE HERO -->
  <section class="jobs-hero" aria-label="Find jobs in Nigeria">
    <span class="jobs-hero-grid" aria-hidden="true"></span>
    <svg class="jobs-hero-motif" viewBox="0 0 400 400" fill="none" aria-hidden="true" focusable="false">
      <defs>
        <radialGradient id="jhMotifGlow" cx="50%" cy="42%" r="55%">
          <stop offset="0%" stop-color="#ED9020" stop-opacity=".22"/>
          <stop offset="100%" stop-color="#ED9020" stop-opacity="0"/>
        </radialGradient>
      </defs>
      <circle cx="200" cy="188" r="150" fill="url(#jhMotifGlow)"/>
      <line x1="262" y1="252" x2="318" y2="312" stroke="#ED9020" stroke-width="26" stroke-linecap="round"/>
      <circle cx="200" cy="190" r="78" fill="none" stroke="#ED9020" stroke-width="26"/>
      <circle cx="200" cy="78" r="30" fill="#ED9020"/>
    </svg>
    <div class="container jobs-hero-inner">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="<?= base_url() ?>">Home</a>
        <svg aria-hidden="true" style="transform:rotate(-90deg); width:13px; height:13px; display:inline-block;"><use href="#i-chev-down"/></svg>
        <span>Find Jobs</span>
      </nav>
      <h1>Find Your Next <em>Job</em> in Nigeria</h1>
      <p>Browse verified jobs in Lagos, Abuja, Port Harcourt and across Nigeria. Filter by location, salary, job type and industry. New roles added daily.</p>

      <div class="search-card">
        <div class="search-field">
          <svg aria-hidden="true" width="17" height="17"><use href="#i-search"/></svg>
          <input type="text" id="searchKeyword" placeholder="Job title, skills..." value="<?= esc($keywords ?? '') ?>">
        </div>
        <div class="search-field">
          <svg aria-hidden="true" width="17" height="17"><use href="#i-pin"/></svg>
          <select id="locationFilter">
            <option value="">All Locations</option>
            <?php foreach ($states as $state): ?>
              <option value="<?= esc($state->id) ?>" <?= $stateId == $state->id ? 'selected' : '' ?>><?= esc($state->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="search-field">
          <svg aria-hidden="true" width="17" height="17"><use href="#i-bag"/></svg>
          <select id="categoryFilterHero">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
              <option value="<?= esc($category->id) ?>" <?= $categoryId == $category->id ? 'selected' : '' ?>><?= esc($category->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button id="applyFiltersHero" class="btn btn-primary" style="background:var(--accent);color:var(--brand-deep);border:none;">
          <svg aria-hidden="true" width="17" height="17"><use href="#i-search"/></svg> Search jobs
        </button>
      </div>

      <div class="trending" style="margin-top: 15px;">
        <strong>Popular:</strong>
        <?php foreach ($states as $state): ?>
          <?php if (in_array($state->name, ['Lagos', 'Abuja', 'Federal Capital Territory', 'Rivers'])): ?>
            <a href="#" class="trending-tag" data-state-id="<?= esc($state->id) ?>"><?= esc($state->name) ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
        <a href="#" class="trending-tag" data-job-type="remote">Remote</a>
      </div>
    </div>
  </section>

  <!-- LAYOUT -->
  <div class="container mt-28">
    <button id="filters-open" class="btn filters-toggle" aria-controls="filters">
      <svg aria-hidden="true" style="width:16px;height:16px"><use href="#i-gear"/></svg> Filters &amp; Sort
    </button>

    <div class="filters-overlay" id="filters-overlay" aria-hidden="true"></div>
    <div class="jobs-layout">

      <!-- FILTER SIDEBAR -->
      <aside class="filters" id="filters" aria-label="Job filters">
        <div class="filters-head">
          <span class="filters-head-title"><svg aria-hidden="true"><use href="#i-gear"/></svg> Filters</span>
          <button class="filters-close ic" id="filters-close" aria-label="Close filters" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.8);width:32px;height:32px;border-radius:8px">
            <svg aria-hidden="true" style="width:18px;height:18px"><use href="#i-x-circle"/></svg>
          </button>
        </div>
        <div class="filters-body">

          <div class="filter-group">
            <label class="filter-label" for="f-keywords"><svg aria-hidden="true"><use href="#i-search"/></svg> Keywords</label>
            <div class="filter-input-wrap">
              <input type="text" id="f-keywords" class="filter-input" placeholder="Job title, skills…" value="<?= esc($keywords ?? '') ?>" autocomplete="off">
            </div>
          </div>

          <div class="filter-group">
            <label class="filter-label" for="f-location"><svg aria-hidden="true"><use href="#i-pin"/></svg> Location</label>
            <select id="f-location" class="filter-select">
              <option value="">All Locations</option>
              <?php foreach ($states as $state): ?>
                <option value="<?= esc($state->id) ?>" <?= $stateId == $state->id ? 'selected' : '' ?>><?= esc($state->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="filter-group">
            <span class="filter-label"><svg aria-hidden="true"><use href="#i-bag"/></svg> Job Type</span>
            <div class="filter-checks">
              <?php 
              $currentJobTypes = !empty($jobType) ? explode(',', $jobType) : [];
              $typesDef = [
                'full-time'  => 'Full Time',
                'part-time'  => 'Part Time',
                'contract'   => 'Contract',
                'freelance'  => 'Freelance',
                'internship' => 'Internship'
              ];
              foreach ($typesDef as $val => $label):
              ?>
                <label class="filter-check">
                  <input type="checkbox" name="jobtype" class="job-type-filter" value="<?= $val ?>" <?= in_array($val, $currentJobTypes) ? 'checked' : '' ?>>
                  <span class="box"><svg aria-hidden="true"><use href="#i-check"/></svg></span> 
                  <?= $label ?> 
                  <span class="ct">(<?= $job_type_counts[$val] ?? 0 ?>)</span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="filter-group">
            <label class="filter-label" for="f-date"><svg aria-hidden="true"><use href="#i-clock"/></svg> Date Posted</label>
            <select id="f-date" class="filter-select">
              <option value="" <?= empty($jobPosted) ? 'selected' : '' ?>>Any time</option>
              <option value="1_day" <?= $jobPosted == '1_day' ? 'selected' : '' ?>>Last 24 hours</option>
              <option value="7_days" <?= $jobPosted == '7_days' ? 'selected' : '' ?>>Last 7 days</option>
              <option value="30_days" <?= $jobPosted == '30_days' ? 'selected' : '' ?>>Last 30 days</option>
            </select>
          </div>

          <div class="filter-group">
            <label class="filter-label" for="f-experience"><svg aria-hidden="true"><use href="#i-chart"/></svg> Experience</label>
            <select id="f-experience" class="filter-select">
              <option value="" <?= empty($experienceLevel) ? 'selected' : '' ?>>Any Experience</option>
              <option value="entry" <?= $experienceLevel == 'entry' ? 'selected' : '' ?>>Entry level</option>
              <option value="mid" <?= $experienceLevel == 'mid' ? 'selected' : '' ?>>Mid level</option>
              <option value="senior" <?= $experienceLevel == 'senior' ? 'selected' : '' ?>>Senior level</option>
              <option value="manager" <?= $experienceLevel == 'manager' ? 'selected' : '' ?>>Manager / Lead</option>
            </select>
          </div>

          <div class="filter-group">
            <label class="filter-label" for="f-industry"><svg aria-hidden="true"><use href="#i-building"/></svg> Industry</label>
            <select id="f-industry" class="filter-select">
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

          <div class="filter-group">
            <span class="filter-label"><svg aria-hidden="true"><use href="#i-coins"/></svg> Salary Range (₦/month)</span>
            <div class="salary-presets" role="group" aria-label="Minimum salary">
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="" <?= empty($salaryMin) ? 'checked' : '' ?>><span>Any</span></label>
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="100000" <?= $salaryMin == 100000 ? 'checked' : '' ?>><span>₦100k+</span></label>
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="250000" <?= $salaryMin == 250000 ? 'checked' : '' ?>><span>₦250k+</span></label>
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="500000" <?= $salaryMin == 500000 ? 'checked' : '' ?>><span>₦500k+</span></label>
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="1000000" <?= $salaryMin == 1000000 ? 'checked' : '' ?>><span>₦1M+</span></label>
            </div>
            <button type="button" class="salary-custom-toggle" id="salary-custom-toggle" aria-expanded="false">Set a custom range</button>
            <div class="filter-range" id="salary-custom" <?= empty($salaryMin) && empty($salaryMax) ? 'hidden' : '' ?>>
              <input type="number" id="minSalary" class="filter-input" placeholder="Min" value="<?= esc($salaryMin ?? '') ?>" inputmode="numeric" aria-label="Minimum salary">
              <span>–</span>
              <input type="number" id="maxSalary" class="filter-input" placeholder="Max" value="<?= esc($salaryMax ?? '') ?>" inputmode="numeric" aria-label="Maximum salary">
            </div>
          </div>

          <div class="filters-actions">
            <button class="btn btn-primary" id="applyFilters"><svg aria-hidden="true"><use href="#i-check"/></svg> Apply Filters</button>
            <button class="btn btn-outline" id="clearFilters"><svg aria-hidden="true"><use href="#i-arrow-up" style="transform:rotate(45deg)"/></svg> Reset all</button>
          </div>

        </div>
      </aside>

      <!-- RESULTS -->
      <section aria-label="Job results">
        <div class="results-toolbar">
          <div class="results-count">
            <strong><?= number_format($total_jobs) ?> Jobs Found</strong>
            <span>Showing <?= count($jobs) ?> of <?= number_format($total_jobs) ?> results</span>
          </div>
          <div class="results-tools">
            <label class="results-sort">
              <span>Sort:</span>
              <select class="filter-select" id="sortBy" aria-label="Sort jobs by">
                <option value="newest" <?= $sort_by == 'newest' ? 'selected' : '' ?>>Newest First</option>
                <option value="oldest" <?= $sort_by == 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                <option value="salary_high" <?= $sort_by == 'salary_high' ? 'selected' : '' ?>>Salary: High to Low</option>
                <option value="salary_low" <?= $sort_by == 'salary_low' ? 'selected' : '' ?>>Salary: Low to High</option>
              </select>
            </label>
            <div class="view-toggle" role="group" aria-label="View mode">
              <button data-view="grid" class="<?= ($view_mode != 'list') ? 'active' : '' ?>" aria-pressed="<?= ($view_mode != 'list') ? 'true' : 'false' ?>" aria-label="Grid view"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></button>
              <button data-view="list" class="<?= ($view_mode == 'list') ? 'active' : '' ?>" aria-pressed="<?= ($view_mode == 'list') ? 'true' : 'false' ?>" aria-label="List view"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
            </div>
          </div>
        </div>

        <div class="jobs-grid <?= ($view_mode == 'list') ? 'is-list' : '' ?>" id="jobCardsContainer">
          <?php if (empty($jobs)): ?>
            <div class="results-empty">
              <div class="ic"><svg aria-hidden="true"><use href="#i-search"/></svg></div>
              <h3>No jobs found</h3>
              <p>Try adjusting your filters or search keywords to see matching opportunities.</p>
              <button class="btn btn-primary" id="resetFiltersBtn"><svg aria-hidden="true"><use href="#i-arrow-up" style="transform:rotate(45deg)"/></svg> Clear filters</button>
            </div>
          <?php else: ?>
            <?php foreach ($jobs as $job): 
              $isFeatured = ($job->is_featured && $job->featured_until >= date('Y-m-d'));
              $isSaved = in_array($job->id, $savedJobIds ?? []);
              $logoInitials = !empty($job->anonymous) || !empty($job->is_anonymous) ? 'CV' : esc(substr($job->employer_name ?? 'C', 0, 2));
            ?>
              <article class="job-card <?= $isFeatured ? 'job-card--featured' : '' ?>" aria-label="<?= esc($job->title) ?>">
                <?php if ($isFeatured): ?>
                  <span class="badge-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
                <?php endif; ?>

                <!-- GRID LAYOUT VIEWPORT -->
                <div class="grid-only" style="display:flex;flex-direction:column;gap:11px">
                  <div class="job-card-top">
                    <div>
                      <h3 class="job-title" title="<?= esc($job->title) ?>">
                        <a href="<?= base_url('jobs/' . $job->slug) ?>"><?= esc($job->title) ?></a>
                      </h3>
                      <div class="job-company">
                        <span class="job-company-name">
                          <?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Confidential Employer' : esc($job->employer_name) ?>
                        </span>
                        <?php if ($job->show_trust_badge): ?>
                          <button type="button" class="verified-check" aria-label="Verified employer — tap for details">
                            <svg aria-hidden="true"><use href="#i-verified-disc"/></svg>
                            <span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span>
                          </button>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="job-logo" aria-hidden="true"><?= $logoInitials ?></div>
                  </div>
                  <div class="job-meta">
                    <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($job->location ?? 'Nigeria') ?></span>
                    <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc(humanize($job->job_type ?? '')) ?></span>
                    <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= date('d M Y', strtotime($job->created_at)) ?></span>
                  </div>
                  <div class="job-salary-row">
                    <span class="job-salary"><?= $job->salary ? esc($job->salary) : 'Negotiable' ?></span>
                  </div>
                  <div class="job-actions">
                    <a href="<?= base_url('jobs/' . $job->slug) ?>" class="btn btn-primary">Quick apply</a>
                    <button class="save-btn" data-job-id="<?= $job->id ?>" aria-label="Save job" data-saved="<?= $isSaved ? 'true' : 'false' ?>">
                      <svg aria-hidden="true"><use href="<?= $isSaved ? '#i-bookmark-fill' : '#i-bookmark' ?>"/></svg> Save
                    </button>
                  </div>
                </div>

                <!-- LIST LAYOUT VIEWPORT -->
                <div class="job-logo" aria-hidden="true"><?= $logoInitials ?></div>
                <div class="list-body">
                  <div class="list-title-row">
                    <h3 class="job-title" title="<?= esc($job->title) ?>">
                      <a href="<?= base_url('jobs/' . $job->slug) ?>"><?= esc($job->title) ?></a>
                    </h3>
                    <?php if ($isFeatured): ?>
                      <span class="list-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Promoted</span>
                    <?php endif; ?>
                  </div>
                  <div class="job-company">
                    <span class="job-company-name">
                      <?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Confidential Employer' : esc($job->employer_name) ?>
                    </span>
                    <?php if ($job->show_trust_badge): ?>
                      <button type="button" class="verified-check" aria-label="Verified employer — tap for details">
                        <svg aria-hidden="true"><use href="#i-verified-disc"/></svg>
                        <span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span>
                      </button>
                    <?php endif; ?>
                  </div>
                  <div class="list-meta">
                    <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($job->location ?? 'Nigeria') ?></span>
                    <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc(humanize($job->job_type ?? '')) ?></span>
                    <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= date('d M Y', strtotime($job->created_at)) ?></span>
                  </div>
                </div>
                <div class="list-action">
                  <div><span class="job-salary"><?= $job->salary ? esc($job->salary) : 'Negotiable' ?></span></div>
                  <div class="list-action-btns">
                    <button class="save-btn" data-job-id="<?= $job->id ?>" aria-label="Save job" data-saved="<?= $isSaved ? 'true' : 'false' ?>">
                      <svg aria-hidden="true"><use href="<?= $isSaved ? '#i-bookmark-fill' : '#i-bookmark' ?>"/></svg> Save
                    </button>
                    <a href="<?= base_url('jobs/' . $job->slug) ?>" class="btn btn-primary btn-sm">Quick apply</a>
                  </div>
                </div>

              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_jobs > $per_page): ?>
          <nav class="pagination" id="paginationContainer" aria-label="Jobs pagination">
            <?php $total_pages = ceil($total_jobs / $per_page); ?>
            <a class="nav-btn <?= $current_page <= 1 ? 'disabled' : '' ?>" href="#" data-page="<?= $current_page - 1 ?>" aria-label="Previous page">
              <svg aria-hidden="true" style="transform:rotate(90deg); width:13px; height:13px;"><use href="#i-chev-down"/></svg>
            </a>
            
            <?php $start_page = max(1, $current_page - 2); ?>
            <?php $end_page = min($total_pages, $start_page + 4); ?>

            <?php if ($start_page > 1): ?>
              <a href="#" data-page="1">1</a>
              <?php if ($start_page > 2): ?><span class="ellipsis">...</span><?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
              <a href="#" class="<?= $current_page == $i ? 'current' : '' ?>" data-page="<?= $i ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($end_page < $total_pages): ?>
              <?php if ($end_page < $total_pages - 1): ?><span class="ellipsis">...</span><?php endif; ?>
              <a href="#" data-page="<?= $total_pages ?>"><?= $total_pages ?></a>
            <?php endif; ?>

            <a class="nav-btn <?= $current_page >= $total_pages ? 'disabled' : '' ?>" href="#" data-page="<?= $current_page + 1 ?>" aria-label="Next page">
              <svg aria-hidden="true" style="transform:rotate(-90deg); width:13px; height:13px;"><use href="#i-chev-down"/></svg>
            </a>
          </nav>
        <?php endif; ?>

      </section>
    </div>
  </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* ── Brand Tokens ── */


/* ── Reset local style boundaries ── */
main, .container { background-color: transparent !important; }

.ic { display: inline-flex; align-items: center; justify-content: center; line-height: 0; }

/* ── Compact page hero ── */
.jobs-hero {
  background:
    radial-gradient(ellipse 60% 70% at 88% 30%, rgba(245,160,32,.16) 0%, transparent 55%),
    linear-gradient(150deg, #0A2F57 0%, #064A85 60%, #0D609E 100%);
  color: var(--white);
  position: relative; overflow: hidden;
  padding: 30px 0;
  padding-top: max(30px, calc(30px + env(safe-area-inset-top, 0px)));
}
.jobs-hero-grid {
  position: absolute; inset: 0; pointer-events: none; opacity: .4;
  background-image:
    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size: 42px 42px;
  -webkit-mask-image: radial-gradient(ellipse 90% 90% at 30% 30%, #000 30%, transparent 85%);
  mask-image: radial-gradient(ellipse 90% 90% at 30% 30%, #000 30%, transparent 85%);
}
.jobs-hero-inner { position: relative; z-index: 1; }
.jobs-hero h1 { font-size: clamp(1.7rem, 3.6vw, 2.3rem); font-weight: 800; line-height: 1.12; margin-bottom: 8px; color: #fff; }
.jobs-hero h1 em { font-style: normal; color: var(--accent); }
.jobs-hero p { font-size: .9rem; opacity: .9; max-width: 560px; margin-bottom: 18px; color: rgba(255,255,255,0.9); }
.jobs-hero .search-card { margin-bottom: 14px; }

/* Motif */
.jobs-hero-motif {
  position: absolute; top: 50%; right: -40px; transform: translateY(-50%);
  width: min(330px, 30vw); height: auto; pointer-events: none; z-index: 0; opacity: .5;
}
@media (max-width: 900px) {
  .jobs-hero-motif { top: -20px; right: -60px; transform: none; width: 200px; opacity: .26; }
}
@media (max-width: 580px) {
  .jobs-hero-motif { width: 150px; right: -54px; opacity: .2; }
}
.jobs-hero .breadcrumb { display: flex; align-items: center; gap: 7px; font-size: .76rem; opacity: .82; margin-bottom: 14px; }
.jobs-hero .breadcrumb a { color: rgba(255,255,255,.82); text-decoration: none; }
.jobs-hero .breadcrumb a:hover { color: var(--white); }
.jobs-hero .breadcrumb svg { width: 13px; height: 13px; opacity: 0.6; color: rgba(255,255,255,0.6); }

/* Search Card Overrides */
.search-card {
  background: var(--white); border-radius: 12px;
  padding: 10px; display: flex; flex-wrap: wrap; gap: 8px;
  box-shadow: var(--shadow-lg); max-width: 820px;
}
.search-field { position: relative; flex: 1 1 150px; display: flex; align-items: center; }
.search-field svg { position: absolute; left: 12px; width: 17px; height: 17px; color: var(--muted); pointer-events: none; }
.search-card input, .search-card select {
  width: 100%; border: 1px solid var(--border); border-radius: 7px;
  padding: 11px 14px 11px 38px; font-family: 'Inter', sans-serif; font-size: 1rem;
  color: var(--text); background: var(--bg); outline: none; appearance: none; -webkit-appearance: none; min-height: 46px;
}
.search-card select { padding-left: 38px; }
.search-card input:focus, .search-card select:focus { border-color: var(--brand); background: var(--white); }
.search-card > button {
  flex: 0 0 auto; padding: 11px 24px; background: var(--accent); color: var(--brand-deep);
  border: none; border-radius: 7px; font-family: 'Inter', sans-serif;
  font-size: 1rem; font-weight: 600; cursor: pointer; transition: var(--transition);
  min-height: 46px; display: inline-flex; align-items: center; gap: 7px;
}
.search-card > button svg { width: 17px; height: 17px; }
.search-card > button:hover { background: var(--accent-dark); }

/* Trending */
.trending { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; font-size: .8rem; }
.trending strong { color: rgba(255,255,255,0.8); }
.trending a {
  background: rgba(255,255,255,.12); color: var(--white);
  padding: 5px 12px; border-radius: 20px; font-weight: 500;
  border: 1px solid rgba(255,255,255,.2); transition: var(--transition);
  min-height: 32px; display: inline-flex; align-items: center; text-decoration: none;
}
.trending a:hover { background: rgba(255,255,255,.26); text-decoration: none; color: #fff; }

/* ── Live Ticker ── */
.ticker {
  position: relative; z-index: 1;
  background: rgba(10,47,87,.55);
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

/* ── Two-column layout ── */
.jobs-layout {
  display: grid; grid-template-columns: 286px 1fr; gap: 28px;
  align-items: start;
  padding: 40px 0 64px;
}

/* ── Filter sidebar ── */
.filters {
  background: var(--white); border: 1px solid var(--border); border-radius: 14px;
  padding: 0; position: sticky; top: 86px; overflow: hidden;
  box-shadow: 0 2px 14px rgba(10,47,87,.06);
}
.filters-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 15px 18px; border-bottom: 2px solid var(--brand);
  background: linear-gradient(180deg, #0A2F57, var(--brand)); color: #fff;
}
.filters-head-title { display: flex; align-items: center; gap: 9px; font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1rem; color: #fff; }
.filters-head-title svg { width: 18px; height: 18px; color: var(--accent); }
.filters-
.filter-group { padding: 16px 0; border-bottom: 1px solid var(--border); }
.filter-group:last-of-type { border-bottom: none; }
.filter-label { display: flex; align-items: center; gap: 7px; font-size: .76rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: var(--text); margin-bottom: 11px; }
.filter-label svg { width: 14px; height: 14px; color: var(--muted); }

.filter-input, .filter-select {
  width: 100%; border: 1px solid var(--border); border-radius: 8px;
  padding: 10px 12px; font-family: 'Inter', sans-serif; font-size: .86rem;
  color: var(--text); background: var(--bg); outline: none; min-height: 42px;
  appearance: none; -webkit-appearance: none;
}
.filter-select {
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235b6577' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'><path d='m6 9 6 6 6-6'/></svg>");
  background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px;
}
.filter-input:focus, .filter-select:focus { border-color: var(--brand); background: var(--white); }

/* Checkbox rows (job type) */
.filter-checks { display: flex; flex-direction: column; gap: 2px; }
.filter-check {
  display: flex; align-items: center; gap: 10px; padding: 7px 8px; border-radius: 8px;
  cursor: pointer; font-size: .85rem; color: var(--text); transition: background var(--transition);
  user-select: none; min-height: 38px;
}
.filter-check:hover { background: var(--bg); }
.filter-check input { position: absolute; opacity: 0; width: 0; height: 0; }
.filter-check .box {
  width: 18px; height: 18px; border-radius: 5px; border: 1.6px solid var(--border);
  display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
  transition: var(--transition); background: var(--white);
}
.filter-check .box svg { width: 12px; height: 12px; color: #fff; opacity: 0; }
.filter-check input:checked + .box { background: var(--brand); border-color: var(--brand); }
.filter-check input:checked + .box svg { opacity: 1; }
.filter-check .ct { margin-left: auto; font-size: .76rem; color: var(--muted); }

/* Salary range */
.filter-range { display: flex; align-items: center; gap: 8px; }
.filter-range .filter-input { padding-left: 12px; }
.filter-range span { color: var(--muted); font-size: .8rem; }

/* Salary preset bands */
.salary-presets { display: flex; flex-wrap: wrap; gap: 6px; }
.salary-preset { position: relative; cursor: pointer; }
.salary-preset input { position: absolute; opacity: 0; width: 0; height: 0; }
.salary-preset span {
  display: inline-flex; align-items: center; justify-content: center;
  padding: 7px 12px; border-radius: 8px; border: 1.5px solid var(--border);
  background: var(--white); font-size: .8rem; font-weight: 600; color: var(--text);
  transition: var(--transition); min-height: 36px;
}
.salary-preset:hover span { border-color: var(--brand); color: var(--brand); }
.salary-preset input:checked + span { background: var(--brand); border-color: var(--brand); color: #fff; }
.salary-custom-toggle {
  background: none; border: none; padding: 8px 2px 0; margin: 0; cursor: pointer;
  font-family: 'Inter', sans-serif; font-size: .78rem; font-weight: 600; color: var(--brand);
  text-align: left; -webkit-tap-highlight-color: transparent;
}
.salary-custom-toggle:hover { text-decoration: underline; }
#salary-custom { margin-top: 10px; }

.filters-actions { display: flex; flex-direction: column; gap: 8px; padding-top: 16px; }
.filters-actions .btn { width: 100%; }

/* Mobile filter toggle */
.filters-toggle { display: none; }

/* ── Results toolbar ── */
.results-toolbar {
  display: flex; align-items: center; justify-content: space-between; gap: 14px;
  flex-wrap: wrap; margin-bottom: 22px;
}
.results-count { font-family: 'Sora', sans-serif; }
.results-count strong { font-size: 1.15rem; font-weight: 800; color: var(--text); }
.results-count span { display: block; font-family: 'Inter', sans-serif; font-size: .8rem; color: var(--muted); margin-top: 2px; }
.results-tools { display: flex; align-items: center; gap: 12px; }
.results-sort { display: flex; align-items: center; gap: 7px; font-size: .8rem; color: var(--muted); }
.results-sort .filter-select { min-height: 38px; padding-top: 7px; padding-bottom: 7px; width: auto; min-width: 140px; }

/* Grid / list view toggle */
.view-toggle { display: inline-flex; background: var(--bg); border: 1px solid var(--border); border-radius: 9px; padding: 3px; }
.view-toggle button {
  width: 36px; height: 32px; border: none; background: none; border-radius: 6px;
  color: var(--muted); cursor: pointer; display: inline-flex; align-items: center; justify-content: center;
  transition: var(--transition);
}
.view-toggle button svg { width: 17px; height: 17px; }
.view-toggle button.active { background: var(--brand); color: #fff; box-shadow: 0 1px 4px rgba(8,97,169,.3); }
.view-toggle button:not(.active):hover { color: var(--text); }

/* ── Job Cards Grid ── */
.jobs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 18px; }
.job-card {
  position: relative;
  background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
  padding: 22px; transition: var(--transition); display: flex; flex-direction: column; gap: 11px;
}
.job-card:hover { box-shadow: var(--shadow-lg); border-color: var(--brand); transform: translateY(-3px); }
.job-card--featured { background: linear-gradient(180deg, #fffaf0, #fff); border-color: rgba(245,160,32,.35); border-left: 3px solid var(--accent); }
.job-card--featured:hover { border-color: var(--accent); }

.badge-featured {
  position: absolute; top: -9px; left: 16px; z-index: 2;
  background: var(--accent); color: var(--brand-deep);
  font-size: .68rem; font-weight: 700; padding: 4px 11px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 5px; letter-spacing: .03em;
  box-shadow: 0 2px 6px rgba(10,47,87,.18);
}
.badge-featured svg { width: 12px; height: 12px; }

.job-card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
.job-logo { width: 44px; height: 44px; border-radius: 9px; background: var(--brand-light); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-weight: 700; font-size: .9rem; color: var(--brand); flex-shrink: 0; }
.job-title { font-size: 1rem; font-weight: 700; overflow-wrap: anywhere; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.job-title a { color: var(--text); text-decoration: none; transition: var(--transition); }
.job-title a:hover { color: var(--brand); text-decoration: underline; }

.job-company { font-size: .82rem; color: var(--muted); display: inline-flex; align-items: center; gap: 5px; max-width: 100%; }
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
.verified-check:hover .verified-tip { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }

.job-meta { display: flex; flex-wrap: wrap; gap: 12px; font-size: .78rem; color: var(--muted); }
.job-meta span { display: inline-flex; align-items: center; gap: 5px; }
.job-meta svg { width: 13px; height: 13px; color: var(--muted); }
.job-salary { font-size: .92rem; font-weight: 700; color: var(--accent-dark); }
.job-salary-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.job-actions { display: flex; gap: 8px; margin-top: 4px; }
.job-actions .btn { flex: 1; padding: 10px; font-size: .82rem; min-height: 44px; }

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

/* Empty state */
.results-empty { text-align: center; padding: 60px 20px; border: 1px dashed var(--border); border-radius: 14px; background: var(--white); width: 100%; grid-column: 1 / -1; }
.results-empty .ic { width: 56px; height: 56px; border-radius: 14px; background: var(--brand-light); color: var(--brand); margin: 0 auto 16px; }
.results-empty .ic svg { width: 26px; height: 26px; }
.results-empty h3 { font-family: 'Sora', sans-serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; }
.results-empty p { font-size: .88rem; color: var(--muted); max-width: 380px; margin: 0 auto 18px; }

/* ── LIST VIEW ── */
.jobs-grid.is-list { grid-template-columns: 1fr; gap: 12px; }
.jobs-grid.is-list .job-card {
  display: grid;
  grid-template-columns: 52px 1fr auto;
  grid-template-areas: "logo body action";
  align-items: center; gap: 18px; padding: 18px 22px;
}
.jobs-grid.is-list .job-card .job-logo { grid-area: logo; width: 52px; height: 52px; }
.jobs-grid.is-list .job-card .list-
.jobs-grid.is-list .job-card .list-action { grid-area: action; display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0; }
.jobs-grid.is-list .job-card .list-action .job-salary-amount { font-size: 1.05rem; }
.jobs-grid.is-list .job-card .list-meta { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; font-size: .78rem; color: var(--muted); }
.jobs-grid.is-list .job-card .list-meta span { display: inline-flex; align-items: center; gap: 5px; }
.jobs-grid.is-list .job-card .list-meta svg { width: 13px; height: 13px; }
.jobs-grid.is-list .job-card .list-title-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.jobs-grid.is-list .job-card .job-title { font-size: 1.02rem; -webkit-line-clamp: 1; }
.jobs-grid.is-list .job-card .badge-featured { position: static; box-shadow: none; }
.jobs-grid.is-list .job-card--featured { padding-top: 18px; border-top: 1px solid rgba(245,160,32,.35); }
.jobs-grid.is-list .job-card > .badge-featured { display: none; }
.jobs-grid.is-list .list-featured {
  display: inline-flex; align-items: center; gap: 4px;
  background: var(--accent); color: var(--brand-deep);
  font-size: .64rem; font-weight: 800; padding: 3px 9px; border-radius: 20px; letter-spacing: .04em;
}
.jobs-grid:not(.is-list) .job-card .list-featured { display: none; }
.jobs-grid.is-list .list-action-btns { display: flex; gap: 8px; }

/* toggling direct columns inside grid vs list items */
.jobs-grid:not(.is-list) .job-card > .job-logo,
.jobs-grid:not(.is-list) .job-card > .list-body,
.jobs-grid:not(.is-list) .job-card > .list-action { display: none !important; }
.jobs-grid.is-list .job-card > .grid-only { display: none !important; }
.jobs-grid.is-list .job-card > .job-logo { display: flex; }
.jobs-grid.is-list .job-card > .list-
.jobs-grid.is-list .job-card > .list-action { display: flex; }

/* ── Pagination ── */
.pagination { display: flex; justify-content: center; align-items: center; gap: 6px; margin-top: 36px; flex-wrap: wrap; }
.pagination a, .pagination span {
  min-width: 40px; height: 40px; padding: 0 12px; border-radius: 9px;
  border: 1px solid var(--border); background: var(--white); color: var(--text);
  display: inline-flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 600;
  transition: var(--transition); text-decoration: none;
}
.pagination a:hover { border-color: var(--brand); color: var(--brand); text-decoration: none; }
.pagination .current { background: var(--brand); border-color: var(--brand); color: #fff; }
.pagination .ellipsis { border: none; background: none; color: var(--muted); min-width: 24px; }
.pagination .nav-btn svg { width: 16px; height: 16px; }
.pagination .disabled { opacity: .4; pointer-events: none; }

/* ── Responsive ── */
@media (max-width: 900px) {
  .jobs-layout { grid-template-columns: 1fr; gap: 0; padding-top: 20px; }
  .filters {
    position: fixed; top: 0; left: 0; bottom: 0; width: min(340px, 86vw); z-index: 1200;
    border-radius: 0; transform: translateX(-100%); transition: transform .26s ease;
    overflow-y: auto; box-shadow: var(--shadow-lg);
  }
  .filters.open { transform: translateX(0); }
  .filters-overlay {
    position: fixed; inset: 0; background: rgba(10,47,87,.5); z-index: 1100;
    opacity: 0; visibility: hidden; transition: opacity .2s; backdrop-filter: blur(2px);
  }
  .filters-overlay.open { opacity: 1; visibility: visible; }
  .filters-toggle {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; margin-bottom: 18px;
    position: sticky; top: 78px; z-index: 500;
    background: var(--brand); color: #fff; border-color: var(--brand);
    box-shadow: 0 4px 14px rgba(8,97,169,.28); font-weight: 700;
  }
  .filters-toggle:hover, .filters-toggle:focus-visible {
    background: var(--accent); border-color: var(--accent); color: var(--brand-deep);
  }
  .filters-toggle svg { color: currentColor; }
  .filters-head .filters-close { display: inline-flex; }
}
@media (min-width: 901px) {
  .filters-head .filters-close { display: none; }
}
@media (max-width: 560px) {
  .results-toolbar { align-items: flex-start; }
  .jobs-grid.is-list .job-card {
    grid-template-columns: 44px 1fr;
    grid-template-areas:
      "logo body"
      "action action";
  }
  .jobs-grid.is-list .job-card .list-action { align-items: stretch; flex-direction: row; justify-content: space-between; margin-top: 4px; }
  .results-sort { display: none; }
}

/* iOS auto-dark defeat */
html, 
.job-card { background: #ffffff; }
.job-card--featured { background: linear-gradient(180deg, #fffaf0, #fff); }
.filters { background: #ffffff; }
.filters-head { background: linear-gradient(180deg, #0A2F57, #0D609E); }
.results-empty { background: #ffffff; }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Filter values submission
$('#applyFilters, #applyFiltersHero').on('click', function() {
  const jobTypes = [];
  $('.job-type-filter:checked').each(function() {
    jobTypes.push($(this).val());
  });

  const salaryPreset = $('input[name="salary-preset-min"]:checked').val();
  const minSalary = $('#minSalary').val() || salaryPreset || '';

  const params = new URLSearchParams();
  const keywordsVal = $('#searchKeyword').val() || $('#f-keywords').val();
  const locationVal = $('#locationFilter').val() || $('#f-location').val();
  const categoryVal = $('#categoryFilterHero').val();
  const industryVal = $('#f-industry').val();
  const maxSalaryVal = $('#maxSalary').val();
  const sortByVal = $('#sortBy').val();
  const jobPostedVal = $('#f-date').val();
  const expLevelVal = $('#f-experience').val();

  if (keywordsVal) params.set('keywords', keywordsVal);
  if (locationVal) params.set('state_id', locationVal);
  if (categoryVal) params.set('category_id', categoryVal);
  if (industryVal) params.set('industry_id', industryVal);
  if (minSalary) params.set('salary_min', minSalary);
  if (maxSalaryVal) params.set('salary_max', maxSalaryVal);
  if (sortByVal) params.set('sort_by', sortByVal);
  if (jobPostedVal) params.set('job_posted', jobPostedVal);
  if (expLevelVal) params.set('experience_level', expLevelVal);
  if (jobTypes.length > 0) params.set('job_type', jobTypes.join(','));

  // Maintain grid/list view status
  const currentView = $('.view-toggle button.active').data('view') || 'grid';
  params.set('view_mode', currentView);

  window.location.href = window.location.pathname + '?' + params.toString();
});

// Clear Filters
$('#clearFilters, #resetFiltersBtn').on('click', function() {
  window.location.href = window.location.pathname;
});

// Trending labels
$('.trending-tag').on('click', function(e) {
  e.preventDefault();
  const stateId = $(this).data('state-id');
  const jobType = $(this).data('job-type');
  
  if (stateId) {
    $('#locationFilter').val(stateId);
  }
  if (jobType === 'remote') {
    $('.job-type-filter').prop('checked', false);
    $('input[value="remote"]').prop('checked', true);
  }
  $('#applyFiltersHero').click();
});

// Pagination clicks
$('#paginationContainer a[data-page]').on('click', function(e) {
  e.preventDefault();
  if ($(this).hasClass('disabled')) return;
  const page = $(this).data('page');
  const url = new URL(window.location);
  url.searchParams.set('page', page);
  window.location.href = url.toString();
});

// Save job toggle
$('.save-btn').on('click', function() {
  const btn = $(this);
  const jobId = btn.data('job-id');
  const isSaved = btn.data('saved') === 'true';

  btn.prop('disabled', true);

  $.ajax({
    url: "<?= site_url('jobs/toggle-save') ?>/" + jobId,
    method: "POST",
    headers: { 'X-CSRF-TOKEN': '<?= csrf_hash() ?>' },
    success: function(r) {
      if (r.success) {
        const saved = r.saved;
        btn.data('saved', saved ? 'true' : 'false');
        btn.html(saved ?
          '<svg aria-hidden="true"><use href="#i-bookmark-fill"/></svg> Saved' :
          '<svg aria-hidden="true"><use href="#i-bookmark"/></svg> Save'
        );
        toastr.success(saved ? 'Job saved' : 'Job removed');
      } else {
        toastr.error(r.message || 'Error occurred');
      }
    },
    complete: function() { btn.prop('disabled', false); },
    error: function() { toastr.error("Network error. Try again."); btn.prop('disabled', false); }
  });
});

// View mode toggling
$('.view-toggle button').on('click', function() {
  const view = $(this).data('view');
  $('.view-toggle button').removeClass('active').attr('aria-pressed', 'false');
  $(this).addClass('active').attr('aria-pressed', 'true');
  
  if (view === 'list') {
    $('#jobCardsContainer').addClass('is-list');
  } else {
    $('#jobCardsContainer').removeClass('is-list');
  }
});

// Custom salary range toggler
$('#salary-custom-toggle').on('click', function() {
  const customDiv = $('#salary-custom');
  const expanded = $(this).attr('aria-expanded') === 'true';
  $(this).attr('aria-expanded', !expanded);
  customDiv.prop('hidden', expanded);
});

// Mobile Filter toggling
$('#filters-open').on('click', function() {
  $('#filters, #filters-overlay').addClass('open');
});
$('#filters-close, #filters-overlay').on('click', function() {
  $('#filters, #filters-overlay').removeClass('open');
});
</script>
<?= $this->endSection() ?>