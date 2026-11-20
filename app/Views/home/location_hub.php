<?php
$state = \App\Services\RemoteLandingPage::prepare($state);
$isRemote = ($state->slug ?? '') === 'remote';
if (in_array($state->slug ?? '', ['remote', 'nigeria-nationwide'], true)) {
    $meta_description = $state->meta_description;
    $seo_h1 = $state->seo_h1;
    $location_label = $state->name;
}
?>
<?= $this->extend('templates/base') ?>
<?= $this->section('homepage_design') ?>reference<?= $this->endSection() ?>

<?= $this->section('meta') ?>
<?php if (!$isRemote): ?>
<meta name="geo.region" content="<?= esc($geo_region ?? 'NG') ?>">
<meta name="geo.placename" content="<?= esc($state->name) ?>">
<?php endif; ?>
<meta property="og:locale" content="en_NG">
<?= $this->endSection() ?>

<?= $this->section('schema') ?>
<?php
$locationLabel = $location_label ?? (preg_match('/state|fct|abuja/i', $state->name) ? $state->name : $state->name . ' State');
$breadcrumbLabel = in_array($state->slug, ['remote', 'nigeria-nationwide'], true)
    ? $state->name
    : (str_ends_with(strtolower($locationLabel), 'jobs') ? $locationLabel : $locationLabel . ' Jobs');

$itemListName = ($state->slug === 'remote')
    ? 'Remote Jobs in Nigeria'
    : (($state->slug === 'nigeria-nationwide') ? 'Nationwide Jobs in Nigeria' : 'Jobs in ' . $locationLabel);
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "<?= base_url() ?>" },
        { "@type": "ListItem", "position": 2, "name": "All Jobs", "item": "<?= base_url('jobs') ?>" },
        { "@type": "ListItem", "position": 3, "name": "<?= esc($breadcrumbLabel) ?>", "item": "<?= esc($canonical_url ?? current_url()) ?>" }
      ]
    }<?php if (!empty($jobs) && count($jobs) > 0): ?>,
    {
      "@type": "ItemList",
      "name": "<?= esc($itemListName) ?>",
      "numberOfItems": <?= (int) $total_jobs ?>,
      "itemListElement": [
        <?php foreach ($jobs as $idx => $j):
          $jobUrl = base_url('jobs/' . (($j->slug ?? '') ?: $j->id));
        ?><?= $idx > 0 ? ',' : '' ?>{
          "@type": "ListItem",
          "position": <?= $idx + 1 ?>,
          "url": "<?= esc($jobUrl) ?>"
        }<?php endforeach; ?>
      ]
    }<?php endif; ?>
  ]
}
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/location-hub.css') ?>?v=<?= filemtime(FCPATH . 'css/location-hub.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$locationName = $state->name;
$locationLabel = $location_label ?? (preg_match('/state|fct|abuja/i', $locationName) ? $locationName : $locationName . ' State');

// Formulate H1 heading with <em> highlight (matching mockup)
$heading = trim($seo_h1 ?? ($state->seo_h1 ?? '')) ?: 'Jobs in ' . $locationLabel . ', Nigeria — Verified Vacancies';
$heading = str_replace(' — Find Verified Vacancies', ' — Verified Vacancies', $heading);
$heading = preg_replace('/\s*\|\s*JobberRecruit$/i', '', $heading);

$highlightLabel = stripos($heading, $locationLabel) !== false ? $locationLabel : $locationName;
$headingParts = preg_split('/(' . preg_quote($highlightLabel, '/') . ')/iu', $heading, 2, PREG_SPLIT_DELIM_CAPTURE);

// Multi-paragraph description split
$descriptionParagraphs = preg_split('/\R\s*\R/', trim($state->description ?? '') ?: $meta_description);
if (count($descriptionParagraphs) === 1) {
    $sentences = preg_split('/(?<=[.!?])\s+(?=[A-Z])/', $descriptionParagraphs[0]);
    if (count($sentences) > 2) {
        $descriptionParagraphs = [
            implode(' ', array_slice($sentences, 0, 2)),
            implode(' ', array_slice($sentences, 2)),
        ];
    }
}

$heroDescription = $meta_description;
if (strcasecmp($locationName, 'Lagos') === 0 || strcasecmp($locationName, 'Lagos State') === 0) {
    $heroDescription = 'Browse verified jobs in Lagos State. Find roles in fintech, banking, FMCG, oil & gas and more. Filter by salary, experience, and location.';
}

if ($state->slug === 'remote') {
    $browseUrl = base_url('jobs?location=remote');
    $alertUrl = base_url('job-alerts?work_arrangement=remote');
    $aboutMarketTitle = 'About remote work in Nigeria';
} elseif ($state->slug === 'nigeria-nationwide') {
    $browseUrl = base_url('jobs');
    $alertUrl = base_url('job-alerts');
    $aboutMarketTitle = 'About nationwide jobs in Nigeria';
} else {
    $browseUrl = base_url('jobs?state_id=' . (int) $state->id);
    $alertUrl = base_url('job-alerts?state=' . (int) $state->id);
    $aboutMarketTitle = 'About the job market in ' . $locationName;
}
$loggedIn = $auth->loggedIn();
$savedJobIds = array_map('intval', $savedJobIds ?? []);
?>
<main id="main-content">
  <section class="land-hero" aria-label="<?= esc($isRemote ? 'Remote jobs' : 'Jobs in ' . $locationLabel) ?>">
    <span class="land-hero-grid" aria-hidden="true"></span>
    <div class="container">
      <div class="land-hero-inner">
        <div class="land-hero-main">
          <nav class="land-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= base_url() ?>">Home</a>
            <svg aria-hidden="true" style="transform:rotate(-90deg)"><use href="#i-chev-down"/></svg>
            <a href="<?= base_url('jobs') ?>">All Jobs</a>
            <svg aria-hidden="true" style="transform:rotate(-90deg)"><use href="#i-chev-down"/></svg>
            <span style="opacity:.7"><?= esc($breadcrumbLabel) ?></span>
          </nav>
          <span class="land-tag">
            <svg aria-hidden="true"><use href="#i-pin"/></svg>
            <?= esc(($state->region ?? '') ?: 'Nigeria') ?><?= !empty($state->capital) ? ' · Capital: ' . esc($state->capital) : '' ?>
          </span>
          <h1><?= esc($headingParts[0] ?? 'Jobs in ') ?><?php if (isset($headingParts[1])): ?><em><?= esc($headingParts[1]) ?></em><?= esc($headingParts[2] ?? '') ?><?php endif; ?></h1>
          <p class="land-hero-sub"><?= esc($heroDescription) ?></p>
          <div class="land-hero-actions">
            <div class="land-open-count">
              <span class="lc-ic"><svg aria-hidden="true"><use href="#i-bag"/></svg></span>
              <span><span class="lc-n"><?= number_format($total_jobs) ?></span><br><span class="lc-l">Open roles</span></span>
            </div>
            <a href="<?= esc($browseUrl) ?>" class="btn btn-white btn-lg"><svg aria-hidden="true"><use href="#i-search"/></svg> Browse all jobs</a>
            <a href="<?= esc($alertUrl) ?>" class="land-hero-alert"><svg aria-hidden="true"><use href="#i-bell"/></svg> Set job alert</a>
          </div>
        </div>
        <div class="land-stat">
          <div class="land-stat-title"><svg aria-hidden="true"><use href="#i-chart"/></svg> About <?= esc($locationName) ?></div>
          <div class="land-stat-row">
            <div class="land-stat-item"><div class="ls-n"><?= number_format($total_jobs) ?><?= $total_jobs > 0 ? '+' : '' ?></div><div class="ls-l">Live jobs</div></div>
            <?php if (!empty($state->capital)): ?>
            <div class="land-stat-item"><div class="ls-n alt"><?= esc($state->capital) ?></div><div class="ls-l">Capital city</div></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="seo-block" aria-label="<?= esc($aboutMarketTitle) ?>">
    <div class="container">
      <div class="seo-card">
        <span class="seo-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
        <div class="seo-body">
          <h2><?= esc($aboutMarketTitle) ?></h2>
          <?php foreach ($descriptionParagraphs as $paragraph): ?>
            <p><?= esc($paragraph) ?></p>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="land-jobs" aria-labelledby="land-jobs-h">
    <div class="container">
      <div class="land-jobs-head">
        <div>
          <?php if ($state->slug === 'remote'): ?>
          <h2 id="land-jobs-h">Latest <span>remote jobs</span> in Nigeria</h2>
          <?php elseif ($state->slug === 'nigeria-nationwide'): ?>
          <h2 id="land-jobs-h">Latest <span>nationwide jobs</span> in Nigeria</h2>
          <?php else: ?>
          <h2 id="land-jobs-h">Latest jobs in <span><?= esc($locationName) ?></span></h2>
          <?php endif; ?>
          <p class="land-jobs-count">Showing <?= count($jobs) ?> of <?= number_format($total_jobs) ?> open vacancies</p>
        </div>
        <?php if ($total_jobs > 0): ?>
        <a href="<?= esc($browseUrl) ?>" class="land-viewall">View all <?= number_format($total_jobs) ?> jobs <svg aria-hidden="true" style="transform:rotate(-90deg)"><use href="#i-chev-down"/></svg></a>
        <?php endif; ?>
      </div>

      <?php if (!empty($jobs)): ?>
      <div class="jobs-grid" style="margin-top:24px">
        <?php foreach ($jobs as $job): helper('public_job'); $job = public_job($job);
          $anonymous = !empty($job->is_anonymous) || !empty($job->is_confidential) || !empty($job->anonymous);
          $company = $anonymous ? 'Confidential Employer' : ($job->employer_name ?? 'Employer');
          $logo = $anonymous ? '' : resolve_image_url($job->company_logo ?? '', 'company', $company);
          $jobUrl = base_url('jobs/' . (($job->slug ?? '') ?: $job->id));
          $saved = in_array((int) $job->id, $savedJobIds, true);
          $initials = $anonymous ? 'CE' : mb_strtoupper(implode('', array_map(static fn ($word) => mb_substr($word, 0, 1), array_slice(preg_split('/\s+/', trim($company)), 0, 2))));

          // Salary formatting matching mockup
          $salary = $job->salary_details ?? $job->salary ?? $job->min_salary ?? null;
          if (!empty($job->salary_details)) {
              $salaryDisplay = $job->salary_details;
          } elseif (!empty($salary) && is_numeric($salary)) {
              $salaryDisplay = '₦' . number_format((float) $salary);
              if (!empty($job->max_salary) && is_numeric($job->max_salary)) {
                  $salaryDisplay .= ' – ₦' . number_format((float) $job->max_salary);
              }
              if (!empty($job->salary_period)) {
                  $salaryDisplay .= ' / ' . $job->salary_period;
              }
          } elseif (!empty($salary)) {
              $salaryDisplay = $salary;
          } else {
              $salaryDisplay = 'Negotiable';
          }

          // Experience formatting
          $experience = trim($job->experience_level ?? '');
          $experience = ['entry' => 'Entry Level', 'mid' => 'Mid Level', 'senior' => 'Senior Level', 'executive' => 'Executive'][$experience] ?? $experience;
          $expDisplay = !empty($experience) ? preg_replace('/(\d)\s*-\s*(\d)/', '$1–$2', str_replace(['years', 'year'], 'yrs', $experience)) : '';

          // Employment type formatting
          $jobTypeDisplay = ucwords(str_replace(['_', '-'], ' ', $job->employment_type ?? $job->job_type ?? 'Full-time'));
          if (strtolower($jobTypeDisplay) === 'full time') $jobTypeDisplay = 'Full-time';
          if (strtolower($jobTypeDisplay) === 'part time') $jobTypeDisplay = 'Part-time';
        ?>
        <article class="job-card <?= !empty($job->is_featured) ? 'job-card--featured' : '' ?>" aria-label="<?= esc($job->title) ?>">
          <?php if (!empty($job->is_featured)): ?>
            <span class="badge-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
          <?php endif; ?>
          <div class="job-card-top">
            <div>
              <h3 class="job-title" title="<?= esc($job->title) ?>">
                <a href="<?= esc($jobUrl) ?>"><?= esc($job->title) ?></a>
              </h3>
              <div class="job-company">
                <span class="job-company-name"><?= esc($company) ?></span>
                <?php if (!$anonymous && !empty($job->is_verified)): ?>
                  <button type="button" class="verified-check" aria-label="Verified employer — tap for details" aria-expanded="false">
                    <svg aria-hidden="true"><use href="#i-verified-disc"/></svg>
                    <span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span>
                  </button>
                <?php endif; ?>
              </div>
              <?php if (!$anonymous && !empty($job->employer_tagline)): ?><div class="job-tagline" style="margin-top:2px;color:var(--muted);font-size:.75rem;line-height:1.35;"><?= esc($job->employer_tagline) ?></div><?php endif; ?>
            </div>
            <div class="job-logo<?= $anonymous ? ' is-confidential' : '' ?>" aria-hidden="true">
              <?php if ($anonymous): ?>
                <svg aria-hidden="true" width="20" height="20" style="color:var(--muted);"><use href="#i-lock"/></svg>
              <?php elseif (!empty($logo)): ?>
                <img src="<?= esc($logo) ?>" alt="<?= esc($company) ?> logo" loading="lazy">
              <?php else: ?>
                <?= esc($initials) ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="job-meta">
            <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($job->state_name ?? $locationName) ?></span>
            <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc($jobTypeDisplay) ?></span>
            <?php if (!empty($expDisplay)): ?>
              <span><svg aria-hidden="true"><use href="#i-chart"/></svg> <?= esc($expDisplay) ?></span>
            <?php endif; ?>
          </div>
          <div class="job-salary-row">
            <span class="job-salary"><?= esc($salaryDisplay) ?></span>
          </div>
          <div class="job-actions">
            <a href="<?= esc($jobUrl) ?>" class="btn btn-primary">Quick apply</a>
            <?php if ($loggedIn): ?>
              <form class="job-save-form" action="<?= base_url('jobs/toggle-save/' . (int) $job->id) ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="save-btn" data-job-id="<?= (int) $job->id ?>" data-saved="<?= $saved ? 'true' : 'false' ?>" aria-pressed="<?= $saved ? 'true' : 'false' ?>" aria-label="<?= $saved ? 'Job saved — click to remove' : 'Save job' ?>">
                  <svg aria-hidden="true"><use href="<?= $saved ? '#i-bookmark-fill' : '#i-bookmark' ?>"/></svg> <?= $saved ? 'Saved' : 'Save' ?>
                </button>
              </form>
            <?php else: ?>
              <a class="save-btn" href="<?= esc(base_url('login?redirect=' . rawurlencode(current_url() . '?save=' . (int) $job->id))) ?>" aria-label="Save job">
                <svg aria-hidden="true"><use href="#i-bookmark"/></svg> Save
              </a>
            <?php endif; ?>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="land-empty">
        <span class="land-empty-ic"><svg aria-hidden="true"><use href="#i-bag"/></svg></span>
        <?php if ($isRemote): ?>
        <h3>No live remote vacancies right now</h3>
        <p>Set a free job alert to hear when new remote opportunities are posted, or browse all jobs.</p>
        <?php else: ?>
        <h3>No live vacancies in <?= esc($locationName) ?> right now</h3>
        <p>New roles are added daily. Set a free job alert and we'll email you the moment a <?= esc($locationName) ?> job is posted — or browse nearby states below.</p>
        <?php endif; ?>
        <div class="land-empty-actions">
          <a href="<?= esc($alertUrl) ?>" class="btn btn-primary"><svg aria-hidden="true"><use href="#i-bell"/></svg> Set a job alert</a>
          <a href="<?= base_url('jobs') ?>" class="btn btn-outline">Browse all jobs</a>
        </div>
      </div>
      <?php endif; ?>
      <p class="job-save-feedback sr-only" role="status" aria-live="polite"></p>
    </div>
  </section>

  <aside class="container" aria-label="Advertisement" style="padding-top:8px;padding-bottom:8px">
    <div class="ad-unit">
      <ins class="adsbygoogle"
           style="display:block"
           data-ad-client="ca-pub-3464186884176173"
           data-ad-slot="6229476516"
           data-ad-format="auto"
           data-full-width-responsive="true"></ins>
    </div>
  </aside>

  <section class="crosslink" aria-labelledby="cross-h">
    <div class="container">
      <div class="crosslink-head">
        <h2 id="cross-h">Browse jobs by <span>state</span></h2>
        <p>Find opportunities across all 36 states, the FCT, nationwide, and remote roles</p>
      </div>
      <div class="crosslink-grid">
        <?php foreach ($all_states as $s):
          $stateSlug = ($s->slug ?? '') ?: strtolower(str_replace(' ', '-', $s->name)) . '-state';
          if (in_array($stateSlug, ['remote', 'nigeria-nationwide', 'nationwide'], true)) continue;
          $current = (int) $s->id === (int) ($state->id ?? 0) && ($state->slug ?? '') === $stateSlug;
          $linkUrl = base_url('jobs-in-' . $stateSlug);
        ?>
          <a href="<?= esc($linkUrl) ?>" class="<?= $current ? 'is-current' : '' ?>" <?= $current ? 'aria-current="page"' : '' ?>>
            <svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($s->name) ?>
          </a>
        <?php endforeach; ?>
        <a href="<?= base_url('jobs/remote') ?>" class="<?= (($state->slug ?? '') === 'remote' || uri_string() === 'jobs/remote') ? 'is-current' : '' ?>" <?= (($state->slug ?? '') === 'remote' || uri_string() === 'jobs/remote') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-globe"/></svg> Remote Jobs
        </a>
        <a href="<?= base_url('jobs/nigeria-nationwide') ?>" class="<?= (($state->slug ?? '') === 'nigeria-nationwide' || uri_string() === 'jobs/nigeria-nationwide') ? 'is-current' : '' ?>" <?= (($state->slug ?? '') === 'nigeria-nationwide' || uri_string() === 'jobs/nigeria-nationwide') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-pin"/></svg> Nationwide Jobs
        </a>
      </div>
    </div>
  </section>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
  try { (window.adsbygoogle = window.adsbygoogle || []).push({}); } catch (_) {}

  // Verified employer tooltip (click/tap to toggle; hover handled by CSS; click-outside & Escape close)
  document.querySelectorAll('.verified-check').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const wasOpen = btn.classList.contains('open');
      document.querySelectorAll('.verified-check.open').forEach(o => {
        o.classList.remove('open');
        o.setAttribute('aria-expanded', 'false');
      });
      if (!wasOpen) {
        btn.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });

  document.addEventListener('click', () => {
    document.querySelectorAll('.verified-check.open').forEach(o => {
      o.classList.remove('open');
      o.setAttribute('aria-expanded', 'false');
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.verified-check.open').forEach(o => {
        o.classList.remove('open');
        o.setAttribute('aria-expanded', 'false');
      });
    }
  });

  // Save Job handling with optimistic UI and CSRF token propagation
  const forms = [...document.querySelectorAll('.job-save-form')];
  forms.forEach(form => {
    form.addEventListener('submit', async event => {
      event.preventDefault();
      const button = form.querySelector('.save-btn');
      if (!button) return;
      const feedback = document.querySelector('.job-save-feedback');
      const wasSaved = button.dataset.saved === 'true';
      const nextSaved = !wasSaved;

      // Optimistic update
      button.dataset.saved = String(nextSaved);
      button.setAttribute('aria-pressed', String(nextSaved));
      button.setAttribute('aria-label', nextSaved ? 'Job saved — click to remove' : 'Save job');
      button.innerHTML = '<svg aria-hidden="true"><use href="#i-bookmark' + (nextSaved ? '-fill' : '') + '"/></svg> ' + (nextSaved ? 'Saved' : 'Save');
      button.disabled = true;

      const body = new FormData(form);
      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.csrfHash) {
          forms.forEach(f => {
            const csrfInput = f.querySelector('input[type="hidden"]');
            if (csrfInput) csrfInput.value = data.csrfHash;
          });
        }
        if (!response.ok || !data.success) {
          throw new Error('Save failed');
        }
        if (feedback) {
          feedback.textContent = nextSaved ? 'Job saved.' : 'Job removed from saved jobs.';
        }
      } catch (err) {
        // Revert on error
        button.dataset.saved = String(wasSaved);
        button.setAttribute('aria-pressed', String(wasSaved));
        button.setAttribute('aria-label', wasSaved ? 'Job saved — click to remove' : 'Save job');
        button.innerHTML = '<svg aria-hidden="true"><use href="#i-bookmark' + (wasSaved ? '-fill' : '') + '"/></svg> ' + (wasSaved ? 'Saved' : 'Save');
        if (feedback) feedback.textContent = 'Could not save this job. Please try again.';
      } finally {
        button.disabled = false;
      }
    });
  });

  // Auto-resume save if returning from login with ?save=<job_id>
  (function resumePendingSave() {
    if (!window.JR_USER || !window.JR_USER.loggedIn) return;
    const pending = new URLSearchParams(window.location.search).get('save');
    if (!pending) return;
    const form = document.querySelector('.job-save-form button[data-job-id="' + pending + '"]')?.closest('form');
    if (form) {
      const btn = form.querySelector('.save-btn');
      if (btn && btn.dataset.saved !== 'true') {
        form.requestSubmit();
      }
    }
  })();
})();
</script>
<?= $this->endSection() ?>
