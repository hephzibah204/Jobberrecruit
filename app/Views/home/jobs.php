<?= $this->extend('templates/base') ?>
<?= $this->section('homepage_design') ?>reference<?= $this->endSection() ?>

<?= $this->section('meta') ?>
<?php
$pageParam = (int) ($current_page ?? 1);
$canonicalUrl = $pageParam > 1 ? base_url('jobs') . '?page=' . $pageParam : base_url('jobs');
?>
<link rel="canonical" href="<?= esc($canonicalUrl, 'attr') ?>">
<link rel="alternate" hreflang="en-NG" href="<?= esc($canonicalUrl, 'attr') ?>">
<link rel="alternate" hreflang="x-default" href="<?= esc(base_url('jobs'), 'attr') ?>">
<meta name="geo.region" content="NG">
<meta name="geo.placename" content="Nigeria">
<?= $this->endSection() ?>

<?= $this->section('schema') ?>
<?php
$listItems = [];
$baseOffset = max(0, (($current_page ?? 1) - 1) * ($per_page ?? 24));
foreach ($jobs as $index => $j) {
    $listItems[] = [
        '@type'    => 'ListItem',
        'position' => $baseOffset + $index + 1,
        'url'      => esc(format_job_url($j)),
        'name'     => esc($j->title),
    ];
}
$itemListData = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url()],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Find Jobs', 'item' => base_url('jobs')],
            ],
        ],
        [
            '@type'           => 'ItemList',
            'name'            => 'Jobs in Nigeria',
            'description'     => 'Verified job vacancies across Nigeria on JobberRecruit.',
            'numberOfItems'   => (int) $total_jobs,
            'itemListOrder'   => 'https://schema.org/ItemListOrderDescending',
            'itemListElement' => $listItems,
        ],
    ],
];
?>
<script type="application/ld+json">
<?= json_encode($itemListData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main-content">

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
      <p>Browse full-time, part-time, and remote vacancies in Lagos, Abuja, Port Harcourt and across all 36 states. New listings added daily.</p>

      <form class="search-card" role="search" aria-label="Job search" action="<?= base_url('jobs') ?>" method="get">
        <label for="searchKeyword" class="sr-only">Job title or keyword</label>
        <div class="search-field">
          <svg aria-hidden="true" width="17" height="17"><use href="#i-search"/></svg>
          <input type="search" id="searchKeyword" name="keywords" placeholder="e.g. Software Engineer" value="<?= esc($keywords ?? '') ?>" autocomplete="off">
        </div>
        <label for="locationFilter" class="sr-only">Location</label>
        <div class="search-field">
          <svg aria-hidden="true" width="17" height="17"><use href="#i-pin"/></svg>
          <select id="locationFilter" name="state_id" aria-label="Location">
            <option value="">All Locations</option>
            <option value="remote" <?= ($stateId === 'remote' || ($workArrangement ?? '') === 'remote' || ($selectedStateName ?? '') === 'Remote') ? 'selected' : '' ?>>Remote Jobs</option>
            <option value="nationwide" <?= ($stateId === 'nationwide' || ($selectedStateName ?? '') === 'Nationwide') ? 'selected' : '' ?>>Nationwide Jobs</option>
            <?php foreach ($states as $state): ?>
              <?php if (in_array(strtolower($state->name), ['remote', 'nationwide'], true)) continue; ?>
              <option value="<?= esc($state->id) ?>" <?= (string)($stateId ?? '') === (string)$state->id ? 'selected' : '' ?>><?= esc($state->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <label for="categoryFilterHero" class="sr-only">Category</label>
        <div class="search-field">
          <svg aria-hidden="true" width="17" height="17"><use href="#i-bag"/></svg>
          <select id="categoryFilterHero" name="category_id" aria-label="Category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
              <option value="<?= esc($category->id) ?>" <?= (string)($categoryId ?? '') === (string)$category->id ? 'selected' : '' ?>><?= esc($category->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button id="applyFiltersHero" type="submit">
          <svg aria-hidden="true" width="17" height="17"><use href="#i-search"/></svg> Search jobs
        </button>
      </form>

      <div class="jobs-hero-actions jobs-alert">
        <a class="jobs-hero-alert" href="<?= base_url('job-alerts') ?>"><svg aria-hidden="true"><use href="#i-bell"/></svg> Create a job alert for this search</a>
      </div>
    </div>
  </section>

  <!-- LAYOUT -->
  <div class="container">
    <button id="filters-open" class="btn filters-toggle" aria-controls="filters" aria-expanded="false">
      <svg aria-hidden="true" style="width:16px;height:16px"><use href="#i-gear"/></svg> Filters &amp; Sort <span class="toggle-count" id="toggle-count" hidden>0</span>
    </button>

    <div class="filters-overlay" id="filters-overlay" aria-hidden="true"></div>
    <div class="jobs-layout">

      <!-- FILTER SIDEBAR -->
      <aside class="filters" id="filters" aria-label="Job filters">
        <div class="filters-head">
          <span class="filters-head-title"><svg aria-hidden="true"><use href="#i-gear"/></svg> Filters <span class="filters-count" id="filters-count" hidden>0</span></span>
          <button class="filters-close ic" id="filters-close" aria-label="Close filters" style="background:none;border:none;cursor:pointer;color:#fff;width:32px;height:32px;border-radius:8px">
            <svg aria-hidden="true" style="width:18px;height:18px"><use href="#i-x-circle"/></svg>
          </button>
        </div>
        <div class="filters-body">

          <div class="filter-group">
            <label class="filter-label" for="f-keywords"><svg aria-hidden="true"><use href="#i-search"/></svg> Keywords</label>
            <div class="filter-input-wrap">
              <svg aria-hidden="true"><use href="#i-search"/></svg>
              <input type="text" id="f-keywords" class="filter-input" placeholder="Job title, skills…" value="<?= esc($keywords ?? '') ?>" autocomplete="off">
              <button type="button" class="filter-clear" id="f-keywords-clear" aria-label="Clear keywords" style="display:none"><svg aria-hidden="true"><use href="#i-x-circle"/></svg></button>
            </div>
          </div>

          <div class="filter-group">
            <label class="filter-label" for="f-location"><svg aria-hidden="true"><use href="#i-pin"/></svg> Location</label>
            <select id="f-location" class="filter-select">
              <option value="">All Locations</option>
              <option value="remote" <?= ($stateId === 'remote' || ($workArrangement ?? '') === 'remote' || ($selectedStateName ?? '') === 'Remote') ? 'selected' : '' ?>>Remote Jobs</option>
              <option value="nationwide" <?= ($stateId === 'nationwide' || ($selectedStateName ?? '') === 'Nationwide') ? 'selected' : '' ?>>Nationwide Jobs</option>
              <?php foreach ($states as $state): ?>
                <?php if (in_array(strtolower($state->name), ['remote', 'nationwide'], true)) continue; ?>
                <option value="<?= esc($state->id) ?>" <?= (string)($stateId ?? '') === (string)$state->id ? 'selected' : '' ?>><?= esc($state->name) ?></option>
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
            <span class="filter-label"><svg aria-hidden="true"><use href="#i-globe"/></svg> Work arrangement</span>
            <label class="filter-check">
              <input type="checkbox" id="remoteFilter" value="remote" <?= ($workArrangement ?? '') === 'remote' ? 'checked' : '' ?>>
              <span class="box"><svg aria-hidden="true"><use href="#i-check"/></svg></span> Remote
            </label>
          </div>
          <div class="filter-group">
            <label class="filter-label" for="f-date"><svg aria-hidden="true"><use href="#i-clock"/></svg> Date Posted</label>
            <select id="f-date" class="filter-select">
              <option value="" <?= empty($jobPosted) ? 'selected' : '' ?>>Any time</option>
              <option value="1" <?= in_array($jobPosted, ['1', '1_day']) ? 'selected' : '' ?>>Last 24 hours</option>
              <option value="3" <?= in_array($jobPosted, ['3', '3_days']) ? 'selected' : '' ?>>Last 3 days</option>
              <option value="7" <?= in_array($jobPosted, ['7', '7_days']) ? 'selected' : '' ?>>Last 7 days</option>
              <option value="30" <?= in_array($jobPosted, ['30', '30_days']) ? 'selected' : '' ?>>Last 30 days</option>
            </select>
          </div>

          <div class="filter-group">
            <label class="filter-label" for="f-experience"><svg aria-hidden="true"><use href="#i-chart"/></svg> Experience</label>
            <select id="f-experience" class="filter-select">
              <option value="" <?= empty($experienceLevel) || $experienceLevel === 'any' ? 'selected' : '' ?>>Any Experience</option>
              <option value="entry" <?= $experienceLevel === 'entry' ? 'selected' : '' ?>>Entry level</option>
              <option value="mid" <?= $experienceLevel === 'mid' ? 'selected' : '' ?>>Mid level</option>
              <option value="senior" <?= $experienceLevel === 'senior' ? 'selected' : '' ?>>Senior level</option>
              <option value="lead" <?= in_array($experienceLevel, ['lead', 'manager']) ? 'selected' : '' ?>>Lead / Manager</option>
            </select>
          </div>

          <div class="filter-group">
            <label class="filter-label" for="f-industry"><svg aria-hidden="true"><use href="#i-building"/></svg> Industry</label>
            <select id="f-industry" class="filter-select">
              <option value="">All Industries</option>
              <?php foreach ($industries as $industry):
                $count = 0;
                foreach ($industry_counts as $ic) {
                  if ((is_object($ic) ? $ic->id : $ic['id']) == $industry->id) {
                    $count = is_object($ic) ? $ic->job_count : $ic['job_count'];
                    break;
                  }
                }
              ?>
                <option value="<?= esc($industry->id) ?>" <?= (string)($industryId ?? '') === (string)$industry->id ? 'selected' : '' ?>>
                  <?= esc($industry->name) ?> (<?= $count ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="filter-group">
            <span class="filter-label"><svg aria-hidden="true"><use href="#i-coins"/></svg> Salary Range (₦/month)</span>
            <div class="salary-presets" role="group" aria-label="Minimum salary">
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="" <?= empty($salaryMin) ? 'checked' : '' ?>><span>Any</span></label>
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="100000" <?= (string)($salaryMin ?? '') === '100000' ? 'checked' : '' ?>><span>₦100k+</span></label>
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="250000" <?= (string)($salaryMin ?? '') === '250000' ? 'checked' : '' ?>><span>₦250k+</span></label>
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="500000" <?= (string)($salaryMin ?? '') === '500000' ? 'checked' : '' ?>><span>₦500k+</span></label>
              <label class="salary-preset"><input type="radio" name="salary-preset-min" value="1000000" <?= (string)($salaryMin ?? '') === '1000000' ? 'checked' : '' ?>><span>₦1M+</span></label>
            </div>
            <button type="button" class="salary-custom-toggle" id="salary-custom-toggle" aria-expanded="false">Set a custom range</button>
            <div class="filter-range" id="salary-custom" <?= empty($salaryMin) && empty($salaryMax) ? 'hidden' : '' ?>>
              <input type="number" id="minSalary" class="filter-input" placeholder="Min" value="<?= esc($salaryMin ?? '') ?>" inputmode="numeric" aria-label="Minimum salary">
              <span>–</span>
              <input type="number" id="maxSalary" class="filter-input" placeholder="Max" value="<?= esc($salaryMax ?? '') ?>" inputmode="numeric" aria-label="Maximum salary">
            </div>
          </div>

          <div class="filters-actions">
            <button class="btn btn-primary" id="applyFilters"><svg aria-hidden="true"><use href="#i-check"/></svg> Show <span id="apply-count"><?= number_format($total_jobs) ?></span> jobs</button>
            <button class="btn btn-outline" id="clearFilters" type="reset"><svg aria-hidden="true"><use href="#i-arrow-up" style="transform:rotate(45deg)"/></svg> Reset all</button>
          </div>

        </div>
      </aside>

      <!-- RESULTS -->
      <section aria-label="Job results">
        <div class="active-filters" id="active-filters" hidden></div>
        <div class="results-toolbar">
          <div class="results-count">
            <strong><?= number_format($total_jobs) ?> Jobs Found</strong>
            <span>Showing <?= count($jobs) ? number_format(($current_page - 1) * $per_page + 1) : 0 ?>–<?= count($jobs) ? number_format(min(($current_page - 1) * $per_page + count($jobs), $total_jobs)) : 0 ?> of <?= number_format($total_jobs) ?> results</span>
          </div>
          <div class="results-tools">
            <label class="results-sort">
              <span>Sort:</span>
              <select class="filter-select" id="sortBy" aria-label="Sort jobs by">
                <option value="newest" <?= ($sort_by ?? 'newest') == 'newest' ? 'selected' : '' ?>>Newest First</option>
                <option value="oldest" <?= ($sort_by ?? '') == 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                <option value="salary_high" <?= ($sort_by ?? '') == 'salary_high' ? 'selected' : '' ?>>Salary: High to Low</option>
                <option value="salary_low" <?= ($sort_by ?? '') == 'salary_low' ? 'selected' : '' ?>>Salary: Low to High</option>
                <option value="relevance" <?= ($sort_by ?? '') == 'relevance' ? 'selected' : '' ?>>Most Relevant</option>
              </select>
            </label>
            <div class="view-toggle" role="group" aria-label="View mode">
              <button data-view="grid" class="<?= (($view_mode ?? 'grid') !== 'list') ? 'active' : '' ?>" aria-pressed="<?= (($view_mode ?? 'grid') !== 'list') ? 'true' : 'false' ?>" aria-label="Grid view"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></button>
              <button data-view="list" class="<?= (($view_mode ?? 'grid') === 'list') ? 'active' : '' ?>" aria-pressed="<?= (($view_mode ?? 'grid') === 'list') ? 'true' : 'false' ?>" aria-label="List view"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
            </div>
          </div>
        </div>

        <div class="jobs-grid <?= (($view_mode ?? 'grid') === 'list') ? 'is-list' : '' ?>" id="jobCardsContainer">
          <?php if (empty($jobs)): ?>
            <div class="results-empty">
              <div class="ic"><svg aria-hidden="true"><use href="#i-search"/></svg></div>
              <h3>No jobs found</h3>
              <p>Try adjusting your filters or search keywords to see matching opportunities.</p>
              <button class="btn btn-primary" id="resetFiltersBtn"><svg aria-hidden="true"><use href="#i-arrow-up" style="transform:rotate(45deg)"/></svg> Clear filters</button>
            </div>
          <?php else: ?>
            <?php
            $featuredCount = 0;
            foreach ($jobs as $cj) {
                if (!empty($cj->is_featured) && (empty($cj->featured_until) || $cj->featured_until === '0000-00-00' || $cj->featured_until >= date('Y-m-d'))) {
                    $featuredCount++;
                }
            }
            $adInserted = false;
            ?>
            <?php foreach ($jobs as $index => $job): helper('public_job'); $job = public_job($job); 
              $isFeatured = (!empty($job->is_featured) && (empty($job->featured_until) || $job->featured_until === '0000-00-00' || $job->featured_until >= date('Y-m-d')));
              $isClosed = (($job->status ?? '') === 'closed' || (!empty($job->application_deadline) && $job->application_deadline !== '0000-00-00' && $job->application_deadline < date('Y-m-d')));
              $isSaved = in_array($job->id, $savedJobIds ?? []);
              
              $isConfidential = !empty($job->is_confidential) || !empty($job->anonymous) || !empty($job->is_anonymous);
              $companyName = $isConfidential ? 'Confidential Employer' : ($job->employer_name ?? 'Company');
              $companyTagline = $isConfidential ? '' : trim((string) ($job->employer_tagline ?? ''));
              $isVerified = !$isConfidential && (!empty($job->is_verified) || !empty($job->show_trust_badge));

              $companyLogo = null;
              if (!$isConfidential && !empty($job->company_logo)) {
                  $rawLogo = trim((string) $job->company_logo);
                  if (str_starts_with($rawLogo, 'http://') || str_starts_with($rawLogo, 'https://')) {
                      $companyLogo = $rawLogo;
                  } elseif (file_exists(FCPATH . ltrim($rawLogo, '/\\'))) {
                      $companyLogo = base_url(ltrim($rawLogo, '/\\'));
                  } elseif (file_exists(FCPATH . 'uploads/' . ltrim($rawLogo, '/\\'))) {
                      $companyLogo = base_url('uploads/' . ltrim($rawLogo, '/\\'));
                  } else {
                      $companyLogo = base_url(ltrim($rawLogo, '/\\'));
                  }
              }

              $parts = preg_split('/\s+/', trim($companyName));
              $logoInitials = strtoupper(substr($parts[0] ?? 'C', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
              if (empty($logoInitials)) { $logoInitials = $isConfidential ? 'CE' : 'JR'; }

              $jobUrl = esc(format_job_url($job), 'attr');
              $isExternal = ($job->application_method ?? '') === 'external';

              // Friendly time string matching mockup (e.g. 2d ago, today)
              $postedText = '';
              if (!empty($job->created_at)) {
                  if (function_exists('humanize_time')) {
                      $postedText = humanize_time($job->created_at);
                  } elseif (function_exists('time_ago')) {
                      $postedText = time_ago($job->created_at);
                  } else {
                      $postedText = date('d M Y', strtotime($job->created_at));
                  }
              }

              $salaryText = !empty($job->salary_details) 
                  ? $job->salary_details 
                  : (!empty($job->salary) ? '₦' . number_format((float) $job->salary) . ' / month' : 'Negotiable');

              $cardClasses = 'job-card' . ($isFeatured ? ' job-card--featured' : '') . ($isClosed ? ' job-card--fired' : '');
              $ariaLabel = esc($job->title) . ' at ' . esc($companyName) . ($isClosed ? ' — position closed' : '');
            ?>
              <article class="<?= $cardClasses ?>" aria-label="<?= $ariaLabel ?>">
                <?php if ($isFeatured): ?>
                  <span class="badge-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
                <?php endif; ?>

                <!-- GRID LAYOUT VIEWPORT -->
                <div class="grid-only" style="display:flex;flex-direction:column;gap:11px">
                  <div class="job-card-top">
                    <div>
                      <h3 class="job-title" title="<?= esc($job->title) ?>">
                        <a href="<?= $jobUrl ?>"><?= esc($job->title) ?></a>
                      </h3>
                      <div class="job-company">
                        <span class="job-company-name"><?= esc($companyName) ?></span>
                        <?php if ($isVerified): ?>
                          <button type="button" class="verified-check" aria-label="Verified employer — tap for details">
                            <svg aria-hidden="true"><use href="#i-verified-disc"/></svg>
                            <span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span>
                          </button>
                        <?php endif; ?>
                      </div>
                      <?php if ($companyTagline !== ''): ?><div class="job-tagline"><?= esc($companyTagline) ?></div><?php endif; ?>
                    </div>
                    <div class="job-logo<?= $isConfidential ? ' is-confidential' : (empty($companyLogo) ? ' is-initials' : '') ?>" aria-hidden="true">
                      <?php if ($isConfidential): ?>
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                      <?php elseif (!empty($companyLogo)): ?>
                        <img src="<?= esc($companyLogo, 'attr') ?>" alt="<?= esc($companyName) ?> logo" loading="lazy" onerror="this.onerror=null;this.parentElement.classList.add('is-initials');this.replaceWith(document.createTextNode('<?= esc($logoInitials) ?>'));">
                      <?php else: ?>
                        <?= esc($logoInitials) ?>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="job-meta">
                    <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($job->location ?? 'Nigeria') ?></span>
                    <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc(humanize($job->job_type ?? '')) ?></span>
                    <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= esc($isClosed ? 'Closed' : $postedText) ?></span>
                  </div>
                  <div class="job-salary-row">
                    <span class="job-salary<?= $isClosed ? ' job-salary--fired' : '' ?>"><?= esc($salaryText) ?></span>
                    <?php if ($isClosed): ?>
                      <span class="badge-fired"><svg aria-hidden="true"><use href="#i-x-circle"/></svg> Position closed</span>
                    <?php endif; ?>
                  </div>
                  <div class="job-actions">
                    <?php if ($isClosed): ?>
                      <button class="btn-closed" disabled aria-disabled="true"><svg aria-hidden="true"><use href="#i-x-circle"/></svg> Position closed</button>
                    <?php else: ?>
                      <a href="<?= $jobUrl ?>" class="btn btn-primary">Quick apply</a>
                    <?php endif; ?>
                    <button class="save-btn" data-job-id="<?= $job->id ?>" aria-label="<?= $isSaved ? 'Job saved — click to remove' : 'Save job' ?>" data-saved="<?= $isSaved ? 'true' : 'false' ?>">
                      <svg aria-hidden="true"><use href="<?= $isSaved ? '#i-bookmark-fill' : '#i-bookmark' ?>"/></svg> <?= $isSaved ? 'Saved' : 'Save' ?>
                    </button>
                  </div>
                </div>

                <!-- LIST LAYOUT VIEWPORT -->
                <div class="job-logo<?= $isConfidential ? ' is-confidential' : (empty($companyLogo) ? ' is-initials' : '') ?>" aria-hidden="true">
                  <?php if ($isConfidential): ?>
                    <svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                  <?php elseif (!empty($companyLogo)): ?>
                    <img src="<?= esc($companyLogo, 'attr') ?>" alt="<?= esc($companyName) ?> logo" loading="lazy" onerror="this.onerror=null;this.parentElement.classList.add('is-initials');this.replaceWith(document.createTextNode('<?= esc($logoInitials) ?>'));">
                  <?php else: ?>
                    <?= esc($logoInitials) ?>
                  <?php endif; ?>
                </div>
                <div class="list-body">
                  <div class="list-title-row">
                    <h3 class="job-title" title="<?= esc($job->title) ?>">
                      <a href="<?= $jobUrl ?>"><?= esc($job->title) ?></a>
                    </h3>
                    <?php if ($isClosed): ?>
                      <span class="badge-fired"><svg aria-hidden="true"><use href="#i-x-circle"/></svg> Position closed</span>
                    <?php elseif ($isFeatured): ?>
                      <span class="badge-promoted"><svg aria-hidden="true"><use href="#i-star"/></svg> Promoted</span>
                    <?php endif; ?>
                  </div>
                  <div class="job-company">
                    <span class="job-company-name"><?= esc($companyName) ?></span>
                    <?php if ($isVerified): ?>
                      <button type="button" class="verified-check" aria-label="Verified employer — tap for details">
                        <svg aria-hidden="true"><use href="#i-verified-disc"/></svg>
                        <span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span>
                      </button>
                    <?php endif; ?>
                  </div>
                  <?php if ($companyTagline !== ''): ?><div class="job-tagline"><?= esc($companyTagline) ?></div><?php endif; ?>
                  <div class="list-meta">
                    <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($job->location ?? 'Nigeria') ?></span>
                    <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc(humanize($job->job_type ?? '')) ?></span>
                    <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= esc($isClosed ? 'Closed' : $postedText) ?></span>
                  </div>
                </div>
                <div class="list-action">
                  <div><span class="job-salary<?= $isClosed ? ' job-salary--fired' : '' ?>"><?= esc($salaryText) ?></span></div>
                  <div class="list-action-btns">
                    <button class="save-btn" data-job-id="<?= $job->id ?>" aria-label="<?= $isSaved ? 'Job saved — click to remove' : 'Save job' ?>" data-saved="<?= $isSaved ? 'true' : 'false' ?>">
                      <svg aria-hidden="true"><use href="<?= $isSaved ? '#i-bookmark-fill' : '#i-bookmark' ?>"/></svg> <?= $isSaved ? 'Saved' : 'Save' ?>
                    </button>
                    <?php if ($isClosed): ?>
                      <button class="btn-closed" disabled aria-disabled="true"><svg aria-hidden="true"><use href="#i-x-circle"/></svg> Position closed</button>
                    <?php else: ?>
                      <a href="<?= $jobUrl ?>" class="btn btn-primary btn-sm">Quick apply</a>
                    <?php endif; ?>
                  </div>
                </div>

              </article>

              <!-- In-feed advertisement placed after featured boundary (matching mockup 8-featured layout) -->
              <?php if (!$adInserted && ($index + 1 === $featuredCount || ($featuredCount === 0 && ($index === 7 || $index === count($jobs) - 1)))): 
                $adInserted = true;
              ?>
                <div class="results-ad" aria-label="Advertisement">
                  <div class="ad-unit">
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-3464186884176173"
                         data-ad-slot="6229476516"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                  </div>
                </div>
              <?php endif; ?>

            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_jobs > $per_page): ?>
          <nav class="pagination" id="paginationContainer" aria-label="Job results pages">
            <?php 
              $total_pages = (int) ceil($total_jobs / $per_page); 
              $prevPage = max(1, $current_page - 1);
              $nextPage = min($total_pages, $current_page + 1);
              $pageBase = (uri_string() === 'job' ? 'job' : 'jobs');
              $prevUrl = base_url($pageBase) . '?' . http_build_query(array_merge($_GET, ['page' => $prevPage]));
              $nextUrl = base_url($pageBase) . '?' . http_build_query(array_merge($_GET, ['page' => $nextPage]));

              $window = 2;
              $start_page = max(1, $current_page - $window);
              $end_page = min($total_pages, $current_page + $window);
              if ($current_page <= $window) {
                  $end_page = min($total_pages, 1 + ($window * 2));
              }
              if ($current_page > $total_pages - $window) {
                  $start_page = max(1, $total_pages - ($window * 2));
              }
            ?>
            <?php if ($current_page <= 1): ?>
              <span class="nav-btn disabled" aria-label="Previous page">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
              </span>
            <?php else: ?>
              <a class="nav-btn" href="<?= esc($prevUrl) ?>" rel="prev" data-page="<?= $prevPage ?>" aria-label="Previous page">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
              </a>
            <?php endif; ?>
            
            <?php if ($start_page > 1): ?>
              <a href="<?= esc(base_url($pageBase) . '?' . http_build_query(array_merge($_GET, ['page' => 1]))) ?>" data-page="1">1</a>
              <?php if ($start_page > 2): ?><span class="ellipsis">…</span><?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
              <?php if ($current_page == $i): ?>
                <span class="current" aria-current="page"><?= $i ?></span>
              <?php else: ?>
                <a href="<?= esc(base_url($pageBase) . '?' . http_build_query(array_merge($_GET, ['page' => $i]))) ?>" data-page="<?= $i ?>"><?= $i ?></a>
              <?php endif; ?>
            <?php endfor; ?>

            <?php if ($end_page < $total_pages): ?>
              <?php if ($end_page < $total_pages - 1): ?><span class="ellipsis">…</span><?php endif; ?>
              <a href="<?= esc(base_url($pageBase) . '?' . http_build_query(array_merge($_GET, ['page' => $total_pages]))) ?>" data-page="<?= $total_pages ?>"><?= $total_pages ?></a>
            <?php endif; ?>

            <?php if ($current_page >= $total_pages): ?>
              <span class="nav-btn disabled" aria-label="Next page">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
              </span>
            <?php else: ?>
              <a class="nav-btn" href="<?= esc($nextUrl) ?>" rel="next" data-page="<?= $nextPage ?>" aria-label="Next page">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
              </a>
            <?php endif; ?>
          </nav>
        <?php endif; ?>

      </section>
    </div>
  </div>

  <!-- MOVE FROM BROWSING TO ACTION -->
  <section class="section" aria-label="Get started">
    <div class="container">
      <h2 class="section-title text-center" style="margin-left:auto;margin-right:auto">Move from browsing to <span>action</span></h2>
      <p class="section-sub text-center" style="margin:0 auto 8px">You're already exploring opportunities. Create your profile, apply faster, and stay visible to employers looking for your skills.</p>
      <div class="dual-cta mt-28">
        <div class="cta-panel" style="background:linear-gradient(150deg,#ED9020,#C8770E);color:#fff">
          <div class="cta-ic" style="background:rgba(255,255,255,.18);color:#fff"><svg aria-hidden="true"><use href="#i-users"/></svg></div>
          <h2>Create a candidate profile</h2>
          <p style="opacity:.92">Save your CV, track applications, and unlock application-aware mock interviews tailored to the jobs you pursue.</p>
          <a href="<?= base_url('register?type=candidate') ?>" class="btn btn-white">Start as candidate →</a>
        </div>
        <div class="cta-panel blue">
          <div class="cta-ic"><svg aria-hidden="true"><use href="#i-rocket"/></svg></div>
          <h2>Need better applicants?</h2>
          <p>Post roles, manage applications, and reach qualified candidates from one recruitment dashboard.</p>
          <a href="<?= base_url('job-ads') ?>" class="btn btn-accent">View employer plans →</a>
        </div>
      </div>
    </div>
  </section>
</main>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Hallmark · restyle · jobberrecruit-jobs-index · theme: technical-precision */
/* critique: job search & filter directory; zero AI slop, zero horizontal scroll, non-reflowing focus rings */
/* Shared navigation/footer from the reference; independent of legacy themes. */
.navbar {
  position: sticky; top: 0; z-index: 1000;
  background: rgba(255,255,255,.92); backdrop-filter: saturate(180%) blur(12px);
  border-bottom: 1px solid var(--border);
  box-shadow: 0 1px 6px rgba(10,47,87,.06);
  padding-top: env(safe-area-inset-top, 0);
}
.nav-inner { display: flex; align-items: center; justify-content: space-between; height: 70px; gap: 16px; }
.nav-logo { display: flex; align-items: center; text-decoration: none; flex-shrink: 0; }
.nav-logo img { height: 60px; width: auto; display: block; }
.nav-links { display: flex; align-items: center; gap: 24px; list-style: none; }
.nav-links a { font-size: .85rem; font-weight: 500; color: var(--text); transition: color var(--transition); }
.nav-links a:hover { color: var(--brand); text-decoration: none; }
/* Nav dropdowns */
.nav-dropdown { position: relative; }
.nav-dropdown-toggle {
  display: inline-flex; align-items: center; gap: 4px;
  font-family: 'Inter', sans-serif; font-size: .85rem; font-weight: 500; color: var(--text);
  background: none; border: none; cursor: pointer; padding: 0;
  transition: color var(--transition);
}
.nav-dropdown-toggle:hover, .nav-dropdown-toggle[aria-expanded="true"] { color: var(--brand); }
.nav-caret { width: 13px; height: 13px; transition: transform var(--transition); }
.nav-dropdown-toggle[aria-expanded="true"] .nav-caret { transform: rotate(180deg); }
.nav-dropdown-menu {
  position: absolute; top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(6px);
  min-width: 210px; background: var(--white); border: none;
  border-radius: 12px; box-shadow: 0 14px 40px rgba(10,47,87,.16);
  padding: 8px; display: flex; flex-direction: column; gap: 2px;
  opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity .16s ease, transform .16s ease; z-index: 60;
}
.nav-dropdown-menu::before { content: ''; position: absolute; bottom: 100%; left: 0; right: 0; height: 12px; }
.nav-dropdown:hover .nav-dropdown-menu,
.nav-dropdown-toggle[aria-expanded="true"] + .nav-dropdown-menu {
  opacity: 1; visibility: visible; pointer-events: auto; transform: translateX(-50%) translateY(0);
}
.nav-dropdown-menu a {
  display: block; padding: 9px 12px; border-radius: 8px;
  font-size: .85rem; font-weight: 500; color: var(--text); white-space: nowrap;
  transition: background var(--transition), color var(--transition);
}
.nav-dropdown-menu a:hover { background: var(--brand-light); color: var(--brand); text-decoration: none; }
/* Mobile nav groups */
.mob-group { display: flex; flex-direction: column; }
.mob-group-label { font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); padding: 4px 0; margin-top: 6px; }
.mob-group a { padding-left: 14px; }
.nav-actions { display: flex; align-items: center; gap: 8px; }
.nav-actions .btn { padding: 8px 16px; font-size: .82rem; }
.nav-actions .btn-primary:hover,
.nav-actions .btn-primary:focus-visible { background: var(--accent); border-color: var(--accent); color: var(--brand-deep); }
.hamburger { display: none; background: none; border: none; cursor: pointer; padding: 8px; color: var(--text); line-height: 0; -webkit-tap-highlight-color: transparent; touch-action: manipulation; }
.mobile-nav { display: none; flex-direction: column; background: var(--white); border-top: 1px solid var(--border); padding-bottom: env(safe-area-inset-bottom, 0); }
.mobile-nav a { padding: 14px 20px; border-bottom: 1px solid var(--border); font-size: .9rem; font-weight: 500; color: var(--text); min-height: 48px; display: flex; align-items: center; }
.mobile-nav a:hover { background: var(--bg); text-decoration: none; }
.mobile-nav.open { display: flex; }
.mobile-nav-cta { color: var(--brand) !important; font-weight: 700 !important; }

/* ══ HERO ══ */

.footer { background: #0A2F57; color: rgba(255,255,255,.78); padding: 56px 0 0; padding-bottom: env(safe-area-inset-bottom, 0); }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 36px; margin-bottom: 44px; }
.footer-logo { display: flex; align-items: center; text-decoration: none; margin-bottom: 14px; }
.footer-logo-img { height: 52px; width: auto; }
.footer-brand p { font-size: .83rem; line-height: 1.75; opacity: .78; margin-bottom: 18px; }
.footer-socials { display: flex; gap: 8px; flex-wrap: wrap; }
.footer-socials a { width: 38px; height: 38px; border-radius: 8px; background: rgba(255,255,255,.09); color: var(--white); display: flex; align-items: center; justify-content: center; transition: var(--transition); text-decoration: none; }
.footer-socials a svg { width: 17px; height: 17px; }
.footer-socials a:hover { background: var(--brand); }
.footer-col h3 { font-family: 'Sora', sans-serif; font-size: .78rem; font-weight: 700; color: var(--white); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 15px; }
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.footer-col ul a { font-size: .82rem; color: rgba(255,255,255,.68); transition: var(--transition); min-height: 26px; display: inline-flex; align-items: center; }
.footer-col ul a:hover { color: var(--white); text-decoration: none; }
.footer-bottom { border-top: 1px solid rgba(255,255,255,.1); padding: 18px 0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px; font-size: .78rem; color: rgba(255,255,255,.45); }
.footer-bottom a { color: rgba(255,255,255,.55); }
.footer-bottom a:hover { color: var(--white); }
.footer-links { display: flex; gap: 18px; flex-wrap: wrap; }

/* Back to top */
#btt { position: fixed; bottom: 24px; right: 24px; bottom: max(24px, calc(24px + env(safe-area-inset-bottom, 0px))); width: 46px; height: 46px; border-radius: 50%; background: var(--brand); color: var(--white); border: none; cursor: pointer; box-shadow: var(--shadow-lg); display: none; align-items: center; justify-content: center; z-index: 900; transition: var(--transition); -webkit-tap-highlight-color: transparent; }
#btt svg { width: 20px; height: 20px; }
#btt.show { display: flex; }
#btt:hover { background: var(--brand-dark); }


@media (max-width: 860px) { .nav-links, .nav-actions .btn-outline { display: none; } .hamburger { display: block; } .footer-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 580px) { .footer-grid { grid-template-columns: 1fr; gap: 24px; } .footer-bottom { flex-direction: column; text-align: center; } .footer-links { justify-content: center; } .nav-logo img { max-width: 150px; height: auto !important; } }

/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ── Brand Tokens ── */
:root {
  color-scheme: light;
  --brand:        #0861A9;
  --brand-dark:   #064A85;
  --brand-deep:   #0A2F57;
  --brand-light:  #E6F0F8;
  --accent:       #ED9020;
  --accent-dark:  #C8770E;
  --text:         #141926;
  --muted:        #5b6577;
  --bg:           #f5f7fb;
  --white:        #ffffff;
  --border:       #e2e8f2;
  --success:      #16a34a;
  --radius:       10px;
  --shadow:       0 2px 14px rgba(10,47,87,.08);
  --shadow-lg:    0 14px 40px rgba(10,47,87,.16);
  --transition:   .18s ease;
}

html { scroll-behavior: smooth; overflow-x: clip; overflow-x: clip; }
body {
  font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
  background: var(--bg);
  color: var(--text);
  font-size: 15px;
  line-height: 1.7;
  overflow-x: clip;
  overflow-x: clip;
  -webkit-font-smoothing: antialiased;
  -webkit-text-size-adjust: 100%;
}
h1, h2, h3, .nav-logo, .display { font-family: 'Sora', 'Inter', sans-serif; letter-spacing: -.02em;  text-wrap: balance; overflow-wrap: break-word; }
a { color: var(--brand); text-decoration: none; }
a:hover { text-decoration: underline; }
img { max-width: 100%; height: auto; display: block; }
svg { flex-shrink: 0; }

/* ── Utility ── */
.container { max-width: 1160px; margin: 0 auto; padding: 0 20px; }
.section   { padding: 76px 0; }
.sr-only   { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }

.text-center   { text-align: center; }
.mb-4  { margin-bottom: 4px; }
.mt-24 { margin-top: 24px; }
.mt-28 { margin-top: 28px; }

.ic { display: inline-flex; align-items: center; justify-content: center; line-height: 0; }

.section-label {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .72rem; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: var(--brand);
  background: var(--brand-light); padding: 5px 13px;
  border-radius: 20px; margin-bottom: 14px;
}
.section-label svg { width: 13px; height: 13px; }
.section-title {
  font-size: clamp(1.6rem, 2.9vw, 2.25rem);
  font-weight: 800; line-height: 1.15; margin-bottom: 12px;
}
.section-title span { color: var(--brand); }
.section-sub { color: var(--muted); font-size: .95rem; max-width: 560px; }

/* Buttons */
.btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 7px;
  padding: 11px 22px; border-radius: 8px;
  font-family: 'Inter', sans-serif; font-size: .88rem; font-weight: 600;
  cursor: pointer; border: 1.5px solid transparent;
  transition: var(--transition); text-decoration: none;
  -webkit-tap-highlight-color: transparent; touch-action: manipulation;
}
.btn svg { width: 16px; height: 16px; }
.btn-primary  { background: var(--brand);  color: var(--white); border-color: var(--brand); }
.btn-primary:hover  { background: var(--brand-dark); border-color: var(--brand-dark); text-decoration: none; }
.btn-outline  { background: transparent; color: var(--brand); border-color: var(--border); }
.btn-outline:hover  { background: var(--brand); color: var(--white); border-color: var(--brand); text-decoration: none; }
.btn-accent   { background: var(--accent); color: var(--brand-deep); border-color: var(--accent); }
.btn-accent:hover { background: #b45309; border-color: #b45309; color: #ffffff !important; text-decoration: none; }
.btn-white    { background: var(--white); color: var(--brand); border-color: var(--white); }
.btn-white:hover    { background: var(--brand-light); text-decoration: none; }
.btn-sm       { padding: 8px 14px; font-size: .78rem; }
.btn-lg       { padding: 14px 32px; font-size: .95rem; }

.btn-closed {
  display: inline-flex; align-items: center; justify-content: center; gap: 6px;
  flex: 1; padding: 10px; font-size: .82rem; min-height: 44px;
  border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 600;
  border: 1.5px solid var(--border); color: var(--muted);
  background: var(--bg); cursor: not-allowed;
}
.btn-closed svg { width: 14px; height: 14px; }

.badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
.badge svg { width: 12px; height: 12px; }
.badge-blue  { background: var(--brand-light); color: var(--brand); }
.badge-featured {
  background: var(--accent); color: var(--brand-deep);
  font-size: .68rem; font-weight: 700; padding: 4px 11px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 5px; letter-spacing: .03em;
}
.badge-featured svg { width: 12px; height: 12px; }

/* Closed position card */
.job-card--fired { opacity: .85; }
.job-card--fired .job-title { color: var(--muted); }
.job-salary--fired { color: var(--muted); text-decoration: line-through; }
.badge-fired {
  background: #fdecec; color: #b91c1c;
  font-size: .68rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 5px;
}
.badge-fired svg { width: 11px; height: 11px; }

.badge-verified {
  background: var(--brand); color: #fff;
  font-size: .68rem; font-weight: 700; padding: 3px 10px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 5px;
  border: 1px solid var(--brand);
}
.badge-verified svg { width: 12px; height: 12px; }

/* Search card */
.search-card {
  background: var(--white); border-radius: 12px;
  padding: 10px; display: flex; flex-wrap: wrap; gap: 8px;
  box-shadow: var(--shadow-lg); max-width: 820px;
}
.search-field { position: relative; flex: 1 1 150px; display: flex; align-items: center; }
.search-field svg { position: absolute; left: 12px; width: 17px; height: 17px; color: var(--muted); pointer-events: none; }
.search-card input,
.search-card select {
  width: 100%; border: 1px solid var(--border); border-radius: 7px;
  padding: 11px 14px 11px 38px; font-family: 'Inter', sans-serif; font-size: 1rem;
  color: var(--text); background: var(--bg); outline: none;
  appearance: none; -webkit-appearance: none; min-height: 46px;
}
.search-card select {
  padding-left: 38px;
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235b6577' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'><path d='m6 9 6 6 6-6'/></svg>");
  background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px;
}
.search-card input:focus,
.search-card select:focus { outline: 2px solid var(--ring, #0861A9); outline-offset: 1px; border-color: var(--brand); background: var(--white); }
.search-card > button {
  flex: 0 0 auto; padding: 11px 24px; background: var(--accent); color: var(--brand-deep);
  border: none; border-radius: 7px; font-family: 'Inter', sans-serif;
  font-size: 1rem; font-weight: 600; cursor: pointer; transition: var(--transition);
  min-height: 46px; display: inline-flex; align-items: center; gap: 7px;
  -webkit-tap-highlight-color: transparent; touch-action: manipulation;
}
.search-card > button svg { width: 17px; height: 17px; }
.search-card > button:hover { background: var(--accent-dark); }

/* ══ JOBS PAGE COMPACT HERO ══ */
.jobs-hero {
  background:
    radial-gradient(ellipse 60% 70% at 88% 30%, rgba(245,160,32,.16) 0%, transparent 55%),
    linear-gradient(150deg, #0A2F57 0%, #064A85 60%, #0861A9 100%);
  color: var(--white);
  position: relative; overflow: hidden; overflow: clip; contain: paint;
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
.jobs-hero p { font-size: .9rem; opacity: .9; max-width: 560px; margin-bottom: 18px; }
.jobs-hero .search-card { margin-bottom: 14px; max-width: 820px; }
.jobs-hero-actions { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.jobs-hero-alert {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: .84rem; font-weight: 600; color: var(--white);
  background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.22);
  padding: 9px 16px; border-radius: 8px; transition: var(--transition);
  min-height: 40px;
}
.jobs-hero-alert:hover { background: rgba(255,255,255,.2); text-decoration: none; color: #fff; }
.jobs-hero-alert svg { width: 16px; height: 16px; color: var(--accent); }
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
.jobs-hero .breadcrumb a { color: rgba(255,255,255,.82); }
.jobs-hero .breadcrumb a:hover { color: var(--white); }
.jobs-hero .breadcrumb svg { width: 13px; height: 13px; opacity: .6; }

/* ── Two-column layout ── */
.jobs-layout {
  display: grid; grid-template-columns: 286px 1fr; gap: 28px;
  align-items: start;
  padding: 40px 0 64px;
}
.jobs-layout > section { min-width: 0; }

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
.filters-count { background: var(--accent); color: var(--brand-deep); font-size: .68rem; font-weight: 800; min-width: 20px; height: 20px; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; padding: 0 6px; }
.filters-body { padding: 6px 18px 18px; }
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
.filter-input:focus, .filter-select:focus { outline: 2px solid var(--ring, #0861A9); outline-offset: 1px; border-color: var(--brand); background: var(--white); }
.filter-input-wrap { position: relative; display: flex; align-items: center; }
.filter-input-wrap svg { position: absolute; left: 11px; width: 15px; height: 15px; color: var(--muted); pointer-events: none; }
.filter-input-wrap .filter-input { padding-left: 34px; }
.filter-input-wrap .filter-clear {
  position: absolute; right: 8px; width: 24px; height: 24px; border: none; background: none;
  color: var(--muted); cursor: pointer; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;
}
.filter-input-wrap .filter-clear:hover { background: var(--bg); color: var(--text); }
.filter-input-wrap .filter-clear svg { position: static; width: 14px; height: 14px; }

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
.filter-check input:focus-visible + .box { outline: 3px solid var(--accent); outline-offset: 2px; }
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
.salary-preset input:focus-visible + span { outline: 3px solid var(--accent); outline-offset: 2px; }
.salary-custom-toggle {
  background: none; border: none; padding: 8px 2px 0; margin: 0; cursor: pointer;
  font-family: 'Inter', sans-serif; font-size: .78rem; font-weight: 600; color: var(--brand);
  text-align: left; -webkit-tap-highlight-color: transparent;
}
.salary-custom-toggle:hover { text-decoration: underline; }
#salary-custom { margin-top: 10px; }

.filters-actions { display: flex; flex-direction: column; gap: 8px; padding-top: 16px; }
.filters-actions .btn { width: 100%; }

/* Mobile filter toggle (hidden on desktop) */
.filters-toggle { display: none; }

/* ── Results column ── */
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
  transition: var(--transition); -webkit-tap-highlight-color: transparent;
}
.view-toggle button svg { width: 17px; height: 17px; }
.view-toggle button.active { background: var(--brand); color: #fff; box-shadow: 0 1px 4px rgba(8,97,169,.3); }
.view-toggle button:not(.active):hover { color: var(--text); }

/* Active filter chips */
.active-filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; }
.filter-chip {
  display: inline-flex; align-items: center; gap: 6px; background: var(--brand-light);
  color: var(--brand); font-size: .78rem; font-weight: 600; padding: 5px 8px 5px 12px;
  border-radius: 20px; border: 1px solid rgba(8,97,169,.18);
  font-family: inherit; cursor: pointer;
}
.filter-chip button { width: 18px; height: 18px; border: none; background: rgba(8,97,169,.14); color: var(--brand); border-radius: 50%; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
.filter-chip button svg { width: 11px; height: 11px; }
.filter-chip button:hover { background: var(--brand); color: #fff; }
.active-filters .clear-all { font-size: .78rem; font-weight: 600; color: var(--muted); background: none; border: none; cursor: pointer; padding: 5px 8px; }
.active-filters .clear-all:hover { color: var(--brand); text-decoration: underline; }

/* Promoted badge (sits with featured corner ribbon) */
.badge-promoted {
  background: var(--accent); color: var(--brand-deep);
  font-size: .64rem; font-weight: 800; padding: 3px 9px; border-radius: 20px;
  display: inline-flex; align-items: center; gap: 4px; letter-spacing: .04em;
}
.badge-promoted svg { width: 11px; height: 11px; }

/* ══ JOBS GRID & CARDS ══ */
.jobs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px; margin-top: 0; }
.job-card {
  position: relative;
  background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
  padding: 22px; transition: var(--transition); display: flex; flex-direction: column; gap: 11px;
}
.job-card:hover { box-shadow: var(--shadow-lg); border-color: var(--brand); transform: translateY(-3px); }
.job-card--featured {
  background: #fffaf0;
  border: 1.5px solid rgba(237, 144, 32, 0.4);
  box-shadow: 0 4px 16px rgba(237, 144, 32, 0.08);
}
.job-card--featured:hover { border-color: var(--accent); }
.job-card .badge-featured {
  position: absolute; top: -9px; left: 16px; z-index: 2;
  box-shadow: 0 2px 6px rgba(10,47,87,.18);
}
.job-card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
.job-card-top > div:first-child { min-width: 0; }
.job-logo { width: 44px; height: 44px; border-radius: 9px; background: #ffffff; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-weight: 700; font-size: .9rem; color: var(--brand); flex-shrink: 0; overflow: hidden; box-shadow: 0 1px 3px rgba(10,47,87,.04); }
.job-logo.is-initials { background: var(--brand-light); }
.job-logo.is-confidential { background: #f1f5f9; border-color: #cbd5e1; }
.job-logo img { width: 100%; height: 100%; object-fit: contain; padding: 4px; border-radius: inherit; }
.job-title { font-size: 1rem; font-weight: 700; overflow-wrap: anywhere; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.job-title a { color: inherit; }
.job-company { font-size: .82rem; color: var(--muted); display: inline-flex; align-items: center; gap: 5px; max-width: 100%; }
.job-company-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0; }
.job-tagline { margin-top: 2px; color: var(--muted); font-size: .75rem; line-height: 1.35; }

.verified-check {
  position: relative;
  display: inline-flex; align-items: center; justify-content: center;
  color: var(--brand); flex-shrink: 0;
  background: none; border: none; padding: 0 0 0 1px; margin: 0;
  cursor: pointer; line-height: 0; vertical-align: middle;
  -webkit-tap-highlight-color: transparent;
}
.verified-check svg { width: 14px; height: 14px; pointer-events: none; }
.verified-check:hover { color: var(--brand-dark); }
.verified-tip {
  position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%) translateY(4px);
  background: #ffffff; color: var(--text);
  font-size: .72rem; font-weight: 600; line-height: 1.4; letter-spacing: 0;
  white-space: nowrap; padding: 7px 11px; border-radius: 8px;
  border: 1px solid var(--border);
  box-shadow: 0 8px 24px rgba(10,47,87,.16);
  opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity .16s ease, transform .16s ease; z-index: 40;
  display: inline-flex; align-items: center; gap: 6px;
}
.verified-tip::after {
  content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
  border: 6px solid transparent; border-top-color: #ffffff;
  filter: drop-shadow(0 1px 0 var(--border));
}
.verified-tip svg { width: 13px; height: 13px; color: var(--success); }
.verified-tip strong { font-weight: 700; }
.verified-check.open .verified-tip,
.verified-check:hover .verified-tip {
  opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0);
}

.job-meta { display: flex; flex-wrap: wrap; gap: 12px; font-size: .78rem; color: var(--muted); }
.job-meta span { display: inline-flex; align-items: center; gap: 5px; }
.job-meta svg { width: 13px; height: 13px; color: var(--muted); }
.job-salary { font-size: .92rem; font-weight: 700; color: var(--accent-dark); }
.job-salary-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.job-actions { display: flex; gap: 8px; margin-top: 4px; }
.job-actions .btn { flex: 1; padding: 10px; font-size: .82rem; min-height: 44px; }
.save-btn { background: none; border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 13px; cursor: pointer; color: var(--muted); display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; font-family: 'Inter', sans-serif; transition: var(--transition); min-height: 44px; -webkit-tap-highlight-color: transparent; touch-action: manipulation; }
.save-btn svg { width: 15px; height: 15px; }
.save-btn:hover { border-color: var(--brand); color: var(--brand); }
.save-btn[data-saved="true"] { color: var(--success); border-color: var(--success); }

/* ── LIST VIEW ── */
.grid-only { flex: 1; }
.jobs-grid.is-list { grid-template-columns: 1fr; gap: 12px; }
.jobs-grid.is-list .job-card {
  display: grid;
  grid-template-columns: 52px 1fr auto;
  grid-template-areas: "logo body action";
  align-items: center; gap: 18px; padding: 18px 22px;
}
.jobs-grid.is-list .job-card .job-logo { grid-area: logo; width: 52px; height: 52px; }
.jobs-grid.is-list .job-card .list-body { grid-area: body; min-width: 0; display: flex; flex-direction: column; gap: 6px; }
.jobs-grid.is-list .job-card .list-action { grid-area: action; display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0; }
.jobs-grid.is-list .job-card .list-action .job-salary { font-size: 1.05rem; }
.jobs-grid.is-list .job-card .list-meta { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; font-size: .78rem; color: var(--muted); }
.jobs-grid.is-list .job-card .list-meta span { display: inline-flex; align-items: center; gap: 5px; }
.jobs-grid.is-list .job-card .list-meta svg { width: 13px; height: 13px; }
.jobs-grid.is-list .job-card .list-title-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.jobs-grid.is-list .job-card .job-title { font-size: 1.02rem; -webkit-line-clamp: 1; }
.jobs-grid.is-list .job-card .badge-featured { display: none; }
.jobs-grid.is-list .job-card--featured { padding-top: 18px; border-top: 1px solid rgba(245,160,32,.35); }
.jobs-grid.is-list .list-action-btns { display: flex; gap: 8px; }

/* Hide list/grid pieces based on mode */
.jobs-grid:not(.is-list) .job-card > .job-logo,
.jobs-grid:not(.is-list) .job-card > .list-body,
.jobs-grid:not(.is-list) .job-card > .list-action { display: none !important; }
.jobs-grid.is-list .job-card > .grid-only { display: none !important; }
.jobs-grid.is-list .job-card > .job-logo { display: flex; }
.jobs-grid.is-list .job-card > .list-body { display: flex; }
.jobs-grid.is-list .job-card > .list-action { display: flex; }

/* Empty state */
.results-empty { text-align: center; padding: 60px 20px; border: 1px dashed var(--border); border-radius: 14px; background: var(--white); grid-column: 1 / -1; }
.results-empty .ic { width: 56px; height: 56px; border-radius: 14px; background: var(--brand-light); color: var(--brand); margin: 0 auto 16px; }
.results-empty .ic svg { width: 26px; height: 26px; }
.results-empty h3 { font-family: 'Sora', sans-serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; }
.results-empty p { font-size: .88rem; color: var(--muted); max-width: 380px; margin: 0 auto 18px; }

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

/* ── Ad units ── */
.ad-unit {
  background: #f0f3f8; border: 1px solid var(--border); border-radius: 12px;
  padding: 12px; text-align: center; overflow: hidden; min-height: 250px;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
}
.ad-unit::before {
  content: "Advertisement"; display: block;
  font-size: .64rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
  color: var(--muted); margin-bottom: 8px;
}
.ad-unit .adsbygoogle { width: 100%; display: block; }
.filters-ad { margin-top: 18px; }
.results-ad { grid-column: 1 / -1; margin: 4px 0; }
.results-ad .ad-unit { min-height: 120px; }

/* ══ DUAL CTA ══ */
.dual-cta { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.cta-panel { border-radius: 12px; padding: 44px 32px; }
.cta-panel.blue { background: linear-gradient(150deg, #0A2F57, var(--brand)); color: var(--white); }
.cta-panel.light { background: var(--white); color: var(--text); border: 1px solid var(--border); }
.cta-ic { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
.cta-panel.blue .cta-ic { background: rgba(255,255,255,.14); color: var(--white); }
.cta-panel.light .cta-ic { background: var(--brand-light); color: var(--brand); }
.cta-ic svg { width: 25px; height: 25px; }
.cta-panel h2 { font-size: 1.35rem; font-weight: 700; margin-bottom: 10px; color: #fff; }
.cta-panel p { font-size: .87rem; margin-bottom: 22px; }
.cta-panel.blue p { opacity: .86; }
.cta-panel.light p { color: var(--muted); }

/* ── Responsive ── */
@media (max-width: 900px) {
  .nav-links, .nav-actions .btn-outline { display: none !important; }
  .hamburger { display: block !important; }
  .jobs-layout { grid-template-columns: 1fr; gap: 0; padding-top: 20px; }
  .filters {
    position: fixed; top: 0; left: 0; bottom: 0; width: min(340px, 86vw); z-index: 1200;
    border-radius: 0; transform: translateX(-100%); transition: transform .26s ease;
    overflow-y: auto; box-shadow: var(--shadow-lg);
  }
  .filters.open { transform: translateX(0); }
  .filters-overlay {
    position: fixed; inset: 0; background: rgba(10,47,87,.5); z-index: 1100;
    opacity: 0; visibility: hidden; transition: opacity .2s; backdrop-filter: none !important; -webkit-backdrop-filter: none !important;
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
  .filters-toggle .toggle-count {
    background: var(--accent); color: var(--brand-deep);
    font-size: .68rem; font-weight: 800; min-width: 19px; height: 19px;
    border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; padding: 0 5px;
  }
  .filters-toggle:hover .toggle-count, .filters-toggle:focus-visible .toggle-count {
    background: var(--brand-deep); color: #fff;
  }
  .filters-head .filters-close { display: inline-flex; }
  .dual-cta { grid-template-columns: 1fr; }
}
@media (min-width: 901px) {
  .filters-head .filters-close { display: none; }
}
@media (max-width: 580px) {
  .section { padding: 54px 0; }
  .container { padding: 0 16px; }
  .search-card { flex-direction: column; }
  .search-field { flex: 1 1 auto; width: 100%; }
  .search-card > button { width: 100%; justify-content: center; }
  .jobs-grid { grid-template-columns: 1fr; }
  .cta-panel { padding: 30px 22px; }
}
@media (max-width: 560px) {
  .jobs-hero-inner { flex-direction: column; align-items: flex-start; }
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
@media (max-width: 380px) {
  .container { padding: 0 12px; }
  .salary-presets { gap: 4px; }
  .salary-preset span { padding: 6px 8px; font-size: .75rem; min-height: 32px; }
  .job-actions { flex-wrap: wrap; }
  .job-actions .btn { min-width: 0; }
  .filters { width: min(300px, 92vw); }
}

/* ════════════════════════════════════════════════════════════════════
   iOS / mobile AUTO-DARK DEFEAT
   ════════════════════════════════════════════════════════════════════ */
html, body { background: #f5f7fb; }
main, .section, .jobs-layout, .container,
.results-toolbar, .results-count, .pagination {
  background-color: transparent;
}
.section, main { background-color: #f5f7fb; }
.results-count strong, .section-title, .job-title, .cta-panel.light h2 { color: #141926; }
.section-title span { color: #0861A9; }
.results-count span, .section-sub { color: #5b6577; }
.job-card { background: #ffffff; }
.job-card--featured { background: linear-gradient(180deg, #fffaf0, #fff); }
.filters { background: #ffffff; }
.filters-head { background: linear-gradient(180deg, #0A2F57, #0861A9); }
.results-empty { background: #ffffff; }

[hidden], .d-none { display: none !important; }
body.jobs-filters-open { overflow: hidden; }
.filters-close { color: #fff !important; }
.jobs-alert { margin-top: 14px; }


.filters-count, .results-count span, .filters-toggle .toggle-count { font-variant-numeric: tabular-nums; }

/* Hallmark · Gate 26b Tactile micro-depression */
.btn:active, .view-toggle button:active, .filter-chip button:active, .salary-preset:active {
  transform: translateY(1px);
}

/* Hallmark · Gate 27 Prefers Reduced Motion */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
  const one = selector => document.querySelector(selector);
  const all = selector => [...document.querySelectorAll(selector)];
  const value = id => one('#' + id)?.value || '';
  const filters = one('#filters');
  one('#salary-custom-toggle').setAttribute('aria-expanded', String(!one('#salary-custom').hidden));
  const opener = one('#filters-open');
  let previousFocus;

  function drawer(open) {
    filters.classList.toggle('open', open);
    one('#filters-overlay').classList.toggle('open', open);
    opener.setAttribute('aria-expanded', String(open));
    document.body.classList.toggle('jobs-filters-open', open);
    if (matchMedia('(max-width:900px)').matches) filters.inert = !open;
    if (open) { previousFocus = document.activeElement; one('#filters-close').focus(); }
    else if (previousFocus) { previousFocus.focus(); previousFocus = null; }
  }

  function breakpoint() {
    drawer(false);
    filters.inert = matchMedia('(max-width:900px)').matches;
  }
  breakpoint();
  matchMedia('(max-width:900px)').addEventListener('change', breakpoint);
  opener.addEventListener('click', () => drawer(true));
  all('#filters-close, #filters-overlay').forEach(el => el.addEventListener('click', () => drawer(false)));

  document.addEventListener('keydown', event => {
    if (!filters.classList.contains('open')) return;
    if (event.key === 'Escape') drawer(false);
    if (event.key === 'Tab') {
      const nodes = all('#filters button, #filters input, #filters select, #filters a').filter(el => !el.disabled && el.getClientRects().length);
      if (event.shiftKey && document.activeElement === nodes[0]) { event.preventDefault(); nodes.at(-1).focus(); }
      else if (!event.shiftKey && document.activeElement === nodes.at(-1)) { event.preventDefault(); nodes[0].focus(); }
    }
  });

  // Keyword clear button
  const kwInput = one('#f-keywords');
  const kwClear = one('#f-keywords-clear');
  function syncKwClear() {
    if (kwClear && kwInput) {
      kwClear.style.display = kwInput.value ? 'inline-flex' : 'none';
    }
  }
  if (kwClear && kwInput) {
    kwClear.addEventListener('click', () => {
      kwInput.value = '';
      kwInput.focus();
      syncKwClear();
      kwInput.dispatchEvent(new Event('input'));
    });
    kwInput.addEventListener('input', syncKwClear);
    syncKwClear();
  }

  // Synchronize Hero and Sidebar inputs
  [['searchKeyword', 'f-keywords'], ['locationFilter', 'f-location']].forEach(([a, b]) => {
    [a, b].forEach((id, i) => {
      const targetEl = one('#' + id);
      if (targetEl) {
        targetEl.addEventListener('input', event => {
          const counterpart = one('#' + (i ? a : b));
          if (counterpart) {
            counterpart.value = event.target.value;
            if (counterpart.id === 'f-keywords') syncKwClear();
          }
          recountFilters();
        });
      }
    });
  });

  // Recount active filters badge
  function recountFilters() {
    let n = 0;
    all('.job-type-filter:checked').forEach(() => n++);
    if (one('#remoteFilter').checked) n++;
    const kw = value('f-keywords');
    if (kw.trim()) n++;
    const loc = value('f-location');
    if (loc && loc !== 'all') n++;
    const cat = value('categoryFilterHero');
    if (cat && cat !== 'all') n++;
    const ind = value('f-industry');
    if (ind && ind !== 'all') n++;
    const date = value('f-date');
    if (date) n++;
    const exp = value('f-experience');
    if (exp && exp !== 'any') n++;
    const salPreset = one('input[name="salary-preset-min"]:checked')?.value;
    const minSal = value('minSalary');
    const maxSal = value('maxSalary');
    if (salPreset || minSal || maxSal) n++;

    const badge = one('#filters-count');
    if (badge) {
      badge.textContent = n;
      badge.hidden = !n;
    }
    const toggleBadge = one('#toggle-count');
    if (toggleBadge) {
      toggleBadge.textContent = n;
      toggleBadge.hidden = !n;
    }
  }

  all('#filters input, #filters select, #categoryFilterHero').forEach(el => {
    el.addEventListener('change', recountFilters);
    el.addEventListener('input', recountFilters);
  });
  recountFilters();

  function submit(event) {
    event.preventDefault();
    const params = new URLSearchParams(location.search);
    ['page', 'q'].forEach(key => params.delete(key));
    const fields = {
      keywords: value('f-keywords'),
      state_id: value('f-location'),
      category_id: value('categoryFilterHero'),
      industry_id: value('f-industry'),
      salary_min: value('minSalary') || one('input[name="salary-preset-min"]:checked')?.value || '',
      salary_max: value('maxSalary'),
      sort_by: value('sortBy'),
      job_posted: value('f-date'),
      experience_level: value('f-experience'),
      job_type: all('.job-type-filter:checked').map(el => el.value).join(','),
      work_arrangement: one('#remoteFilter').checked ? 'remote' : '',
      view_mode: one('.view-toggle button.active')?.dataset.view || 'grid'
    };
    Object.entries(fields).forEach(([key, val]) => val ? params.set(key, val) : params.delete(key));
    location.href = location.pathname + '?' + params;
  }

  one('form[role="search"]').addEventListener('submit', submit);
  one('#applyFilters').addEventListener('click', submit);
  one('#sortBy').addEventListener('change', submit);
  all('#clearFilters, #resetFiltersBtn').forEach(el => el.addEventListener('click', () => { location.href = location.pathname; }));

  all('.salary-preset input').forEach(el => el.addEventListener('change', () => {
    one('#minSalary').value = el.value;
    if (el.value) {
      one('#maxSalary').value = '';
    }
  }));

  one('#salary-custom-toggle').addEventListener('click', event => {
    const expanded = event.currentTarget.getAttribute('aria-expanded') !== 'true';
    event.currentTarget.setAttribute('aria-expanded', String(expanded));
    event.currentTarget.textContent = expanded ? 'Hide custom range' : 'Set a custom range';
    one('#salary-custom').hidden = !expanded;
  });

  all('.view-toggle button').forEach(btn => btn.addEventListener('click', () => {
    all('.view-toggle button').forEach(el => { el.classList.toggle('active', el === btn); el.setAttribute('aria-pressed', String(el === btn)); });
    one('#jobCardsContainer').classList.toggle('is-list', btn.dataset.view === 'list');
    const url = new URL(location.href);
    url.searchParams.set('view_mode', btn.dataset.view);
    history.replaceState(null, '', url);
  }));

  all('#paginationContainer a[data-page]').forEach(link => {
    const url = new URL(location.href);
    url.searchParams.set('page', link.dataset.page);
    link.href = url;
    if (link.classList.contains('disabled')) {
      link.setAttribute('aria-disabled', 'true');
      link.addEventListener('click', e => e.preventDefault());
    }
  });

  // Verified check popup
  all('.verified-check').forEach(btn => btn.addEventListener('click', event => {
    event.stopPropagation();
    const wasOpen = btn.classList.contains('open');
    all('.verified-check.open').forEach(el => el.classList.remove('open'));
    if (!wasOpen) btn.classList.add('open');
  }));
  document.addEventListener('click', () => all('.verified-check.open').forEach(el => el.classList.remove('open')));
  document.addEventListener('keydown', event => { if (event.key === 'Escape') all('.verified-check.open').forEach(el => el.classList.remove('open')); });

  // Save job toggle
  all('.save-btn').forEach(btn => btn.addEventListener('click', async () => {
    if (!<?= json_encode($auth->loggedIn()) ?>) {
      const here = window.location.pathname + window.location.search;
      const ret = encodeURIComponent(here + (here.includes('?') ? '&' : '?') + 'save=' + encodeURIComponent(btn.dataset.jobId));
      location.href = <?= json_encode(base_url('login')) ?> + '?redirect=' + ret;
      return;
    }
    const buttons = all('.save-btn').filter(el => el.dataset.jobId === btn.dataset.jobId);
    buttons.forEach(el => el.disabled = true);
    try {
      const response = await fetch(<?= json_encode(base_url('jobs/toggle-save')) ?> + '/' + btn.dataset.jobId, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': <?= json_encode(csrf_hash()) ?> }
      });
      const data = await response.json();
      if (!response.ok || !data.success) throw Error(data.message || 'Unable to save this job.');
      buttons.forEach(el => {
        el.dataset.saved = String(data.saved);
        el.innerHTML = '<svg aria-hidden="true"><use href="#i-bookmark' + (data.saved ? '-fill' : '') + '"/></svg> ' + (data.saved ? 'Saved' : 'Save');
        el.setAttribute('aria-label', data.saved ? 'Job saved — click to remove' : 'Save job');
      });
    } catch (error) {
      btn.setAttribute('aria-label', error.message + ' Please try again.');
    } finally {
      buttons.forEach(el => el.disabled = false);
    }
  }));

  // Active filter chips
  const chips = one('#active-filters');
  const labels = {
    keywords: 'Keywords',
    q: 'Keywords',
    state_id: 'Location',
    location: 'Location',
    category_id: 'Category',
    category: 'Category',
    industry_id: 'Industry',
    job_type: 'Job type',
    salary_min: 'Min salary',
    salary_max: 'Max salary',
    job_posted: 'Posted',
    experience_level: 'Experience',
    work_arrangement: 'Work arrangement'
  };
  const selections = {
    state_id: 'f-location',
    location: 'f-location',
    category_id: 'categoryFilterHero',
    industry_id: 'f-industry',
    job_posted: 'f-date',
    experience_level: 'f-experience'
  };

  new URLSearchParams(location.search).forEach((val, key) => {
    if (!val || !labels[key]) return;
    const chip = document.createElement('span');
    chip.className = 'filter-chip';
    const select = selections[key] && one('#' + selections[key]);
    const displayVal = select?.selectedOptions[0]?.textContent.trim() || val;
    chip.textContent = labels[key] + ': ' + displayVal + ' ';
    const remove = document.createElement('button');
    remove.type = 'button';
    remove.setAttribute('aria-label', 'Remove ' + labels[key]);
    remove.textContent = '×';
    chip.append(remove);
    chip.querySelector('button').addEventListener('click', () => {
      const url = new URL(location.href);
      url.searchParams.delete(key);
      url.searchParams.delete('page');
      location.href = url;
    });
    chips.append(chip);
  });

  if (chips.children.length) {
    const clearAll = document.createElement('button');
    clearAll.className = 'clear-all';
    clearAll.textContent = 'Clear all';
    clearAll.addEventListener('click', () => { location.href = location.pathname; });
    chips.appendChild(clearAll);
    chips.removeAttribute('hidden');
  } else {
    chips.setAttribute('hidden', '');
  }
})();
</script>
<?= $this->endSection() ?>
