<?= $this->extend('templates/base') ?>
<?= $this->section('homepage_design') ?>reference<?= $this->endSection() ?>

<?= $this->section('schema') ?>
<?php
$sectorName = $industry->name;
$listItems = [];
if (!empty($jobs)) {
    foreach ($jobs as $index => $job) {
        $listItems[] = [
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'url'      => base_url('jobs/' . (($job->slug ?? '') ?: $job->id)),
        ];
    }
}
$graph = [
    [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url()],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'All Jobs', 'item' => base_url('jobs')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $sectorName . ' Jobs', 'item' => current_url()],
        ],
    ],
];
if (!empty($listItems)) {
    $graph[] = [
        '@type'           => 'ItemList',
        'name'            => $sectorName . ' Jobs in Nigeria',
        'numberOfItems'   => count($listItems),
        'itemListElement' => $listItems,
    ];
}
?>
<script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/category-hub.css') ?>?v=<?= @filemtime(FCPATH . 'css/category-hub.css') ?: time() ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$sectorName = $industry->name;
$slugKey = (!empty($industry->slug) ? $industry->slug : $industry->id);
$browseUrl = base_url('jobs?' . (!empty($is_category) ? 'category=' : 'industry=') . esc($slugKey));
$alertUrl = base_url('job-alerts?' . (!empty($is_category) ? 'category=' : 'industry=') . esc($slugKey));
$loggedIn = $auth->loggedIn();
$savedJobIds = array_map('intval', $savedJobIds ?? []);

if (!empty(trim($industry->description ?? ''))) {
    $seoParagraphs = preg_split('/\R\s*\R/', trim($industry->description));
} else {
    $seoParagraphs = [
        'Explore verified ' . $sectorName . ' sector jobs from leading companies and employers across Nigeria. Whether you are an experienced professional or a fresh graduate looking to advance your career, find verified roles matched to your experience level.',
        'Browse opportunities across top firms, multinationals, and indigenous organizations — including roles across Lagos, Abuja, Port Harcourt, Warri, and nationwide locations with competitive compensation and verified benefits.',
    ];
}
?>
<main id="main-content">
  <section class="land-hero" aria-label="<?= esc($sectorName . ' Jobs in Nigeria') ?>">
    <span class="land-hero-grid" aria-hidden="true"></span>
    <div class="container"><div class="land-hero-inner">
      <div class="land-hero-main">
        <nav class="land-breadcrumb" aria-label="Breadcrumb">
          <a href="<?= base_url() ?>">Home</a>
          <svg aria-hidden="true" style="transform:rotate(-90deg)"><use href="#i-chev-down"/></svg>
          <a href="<?= base_url('jobs') ?>">All Jobs</a>
          <svg aria-hidden="true" style="transform:rotate(-90deg)"><use href="#i-chev-down"/></svg>
          <span style="opacity:.7"><?= esc($sectorName) ?> Jobs</span>
        </nav>
        <span class="land-tag"><svg aria-hidden="true"><use href="#i-bag"/></svg> Industry Category</span>
        <h1><?php
          $rawH1 = !empty($industry->seo_h1) ? $industry->seo_h1 : (!empty($seo_h1) ? $seo_h1 : ($sectorName . ' Jobs in Nigeria — Browse Verified Vacancies'));
          if (stripos($rawH1, 'Nigeria') !== false) {
              echo preg_replace('/(Nigeria)/i', '<em>$1</em>', esc($rawH1));
          } else {
              echo esc($rawH1);
          }
        ?></h1>
        <p class="land-hero-sub"><?= esc($meta_description) ?></p>
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
        <div class="land-stat-title"><svg aria-hidden="true"><use href="#i-chart"/></svg> Sector Insights</div>
        <div class="land-stat-row">
          <div class="land-stat-item">
            <div class="ls-n"><?= number_format($total_jobs) ?><?= $total_jobs > 0 ? '+' : '' ?></div>
            <div class="ls-l">Live opportunities</div>
          </div>
        </div>
      </div>
    </div></div>
  </section>

  <section class="seo-block" aria-label="<?= esc('About careers in ' . $sectorName) ?>">
    <div class="container"><div class="seo-card">
      <span class="seo-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
      <div class="seo-body">
        <h2>Careers in <?= esc($sectorName) ?></h2>
        <?php foreach ($seoParagraphs as $paragraph): ?>
          <p><?= esc($paragraph) ?></p>
        <?php endforeach; ?>
      </div>
    </div></div>
  </section>

  <section class="land-jobs" aria-labelledby="land-jobs-h">
    <div class="container">
      <div class="land-jobs-head">
        <div>
          <h2 id="land-jobs-h">Latest jobs in <span><?= esc($sectorName) ?></span></h2>
          <?php if ($total_jobs > 0): ?>
            <p class="land-jobs-count">Showing <?= count($jobs) ?> of <?= number_format($total_jobs) ?> open vacancies</p>
          <?php else: ?>
            <p class="land-jobs-count">Showing 0 of 0 open vacancies</p>
          <?php endif; ?>
        </div>
        <?php if ($total_jobs > 0): ?>
          <a href="<?= esc($browseUrl) ?>" class="land-viewall">View all <?= number_format($total_jobs) ?> jobs <svg aria-hidden="true" style="transform:rotate(-90deg)"><use href="#i-chev-down"/></svg></a>
        <?php else: ?>
          <a href="<?= base_url('jobs') ?>" class="land-viewall">Browse all jobs <svg aria-hidden="true" style="transform:rotate(-90deg)"><use href="#i-chev-down"/></svg></a>
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
          $salary = $job->min_salary ?? $job->salary ?? null;
          $expLevel = !empty($job->experience_level) ? $job->experience_level : '';
          $jobType = !empty($job->employment_type) ? $job->employment_type : (!empty($job->job_type) ? $job->job_type : 'Full-time');
          $location = !empty($job->state_name) ? $job->state_name : 'Nigeria';
        ?>
        <article class="job-card <?= !empty($job->is_featured) ? 'job-card--featured' : '' ?>" aria-label="<?= esc($job->title) ?>">
          <?php if (!empty($job->is_featured)): ?>
            <span class="badge-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
          <?php endif; ?>
          <div class="job-card-top">
            <div>
              <h3 class="job-title" title="<?= esc($job->title) ?>"><a href="<?= esc($jobUrl) ?>"><?= esc($job->title) ?></a></h3>
              <div class="job-company">
                <span class="job-company-name"><?= esc($company) ?></span>
                <?php if (!$anonymous && !empty($job->is_verified)): ?>
                  <button type="button" class="verified-check" aria-label="Verified employer — tap for details">
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
              <?php elseif ($logo): ?>
                <img src="<?= esc($logo) ?>" alt="<?= esc($company) ?> logo" loading="lazy">
              <?php else: ?>
                <?= esc(strtoupper(mb_substr($company, 0, 2))) ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="job-meta">
            <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($location) ?></span>
            <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc(ucfirst($jobType)) ?></span>
            <?php if (!empty($expLevel)): ?>
              <span><svg aria-hidden="true"><use href="#i-chart"/></svg> <?= esc($expLevel) ?></span>
            <?php endif; ?>
          </div>
          <div class="job-salary-row">
            <span class="job-salary">
              <?php if ($salary && is_numeric($salary)): ?>₦<?= number_format((float) $salary) ?><?php if (!empty($job->max_salary) && is_numeric($job->max_salary)): ?> – ₦<?= number_format((float) $job->max_salary) ?><?php endif; ?><?= !empty($job->salary_period) ? ' / ' . esc($job->salary_period) : '' ?>
              <?php else: ?><?= esc($salary ?: 'Negotiable') ?><?php endif; ?>
            </span>
          </div>
          <div class="job-actions">
            <a href="<?= esc($jobUrl) ?>" class="btn btn-primary">Quick apply</a>
            <button type="button" class="save-btn" data-job-id="<?= (int) $job->id ?>" aria-label="<?= $saved ? 'Job saved — click to remove' : 'Save job' ?>" data-saved="<?= $saved ? 'true' : 'false' ?>">
              <svg aria-hidden="true"><use href="<?= $saved ? '#i-bookmark-fill' : '#i-bookmark' ?>"/></svg> <?= $saved ? 'Saved' : 'Save' ?>
            </button>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
        <div class="land-empty">
          <span class="land-empty-ic"><svg aria-hidden="true"><use href="#i-bag"/></svg></span>
          <h3>No live <?= esc($sectorName) ?> vacancies right now</h3>
          <p>New roles are added daily. Set a free job alert and we'll email you the moment a <?= esc($sectorName) ?> job is posted — or explore other sectors below.</p>
          <div class="land-empty-actions">
            <a href="<?= esc($alertUrl) ?>" class="btn btn-primary"><svg aria-hidden="true"><use href="#i-bell"/></svg> Set a job alert</a>
            <a href="<?= base_url('jobs') ?>" class="btn btn-outline">Browse all jobs</a>
          </div>
        </div>
      <?php endif; ?>
      <p class="job-save-feedback" role="status" aria-live="polite"></p>
    </div>
  </section>

  <!-- AD: between the jobs grid and the cross-links — a natural content break. -->
  <aside class="container" aria-label="Advertisement" style="padding-top:8px;padding-bottom:8px">
    <div class="ad-unit">
      <ins class="adsbygoogle"
           style="display:block"
           data-ad-client="ca-pub-3464186884176173"
           data-ad-slot="6229476516"
           data-ad-format="auto"
           data-full-width-responsive="true"></ins>
      <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>
  </aside>

  <section class="crosslink" aria-labelledby="cross-h"><div class="container">
    <div class="crosslink-head">
      <h2 id="cross-h">Explore more <span>sectors</span></h2>
      <p>Find opportunities across other top industries</p>
    </div>
    <div class="crosslink-grid">
      <?php foreach ($all_industries as $sector):
        $sectorSlug = ($sector->slug ?? '') ?: url_title($sector->name, '-', true);
        $current = (int) $sector->id === (int) $industry->id || $sectorSlug === ($industry->slug ?? '');
      ?>
        <a href="<?= esc(base_url($sectorSlug . '-jobs')) ?>" class="<?= $current ? 'is-current' : '' ?>" <?= $current ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc($sector->name) ?>
        </a>
      <?php endforeach; ?>
      <a href="<?= base_url('jobs/nigeria-nationwide') ?>" class="<?= (uri_string() === 'jobs/nigeria-nationwide') ? 'is-current' : '' ?>" <?= (uri_string() === 'jobs/nigeria-nationwide') ? 'aria-current="page"' : '' ?>>
        <svg aria-hidden="true"><use href="#i-pin"/></svg> Nationwide Jobs
      </a>
    </div>
  </div></section>
</main>
<button id="btt" aria-label="Back to top" title="Back to top"><svg aria-hidden="true"><use href="#i-arrow-up"/></svg></button>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
  try { (window.adsbygoogle = window.adsbygoogle || []).push({}); } catch (_) {}

  /* ── Save job ────────────────────────────────────────────────────────── */
  const BM  = '<svg aria-hidden="true"><use href="#i-bookmark"/></svg> Save';
  const BMF = '<svg aria-hidden="true"><use href="#i-bookmark-fill"/></svg> Saved';

  function isLoggedIn() {
    return !!(window.JR_USER && window.JR_USER.loggedIn);
  }

  function renderSaveBtn(btn, saved) {
    btn.dataset.saved = String(saved);
    btn.innerHTML = saved ? BMF : BM;
    btn.setAttribute('aria-label', saved ? 'Job saved — click to remove' : 'Save job');
  }

  function redirectToLogin(jobId) {
    const here = window.location.pathname + window.location.search;
    const ret  = encodeURIComponent(here + (here.includes('?') ? '&' : '?') + 'save=' + encodeURIComponent(jobId));
    window.location.href = '<?= base_url('login') ?>?redirect=' + ret;
  }

  function persistSave(jobId, save) {
    const csrfName = (window.JR_USER && window.JR_USER.csrfName) ? window.JR_USER.csrfName : 'csrf_test_name';
    const csrfHash = (window.JR_USER && window.JR_USER.csrfHash) ? window.JR_USER.csrfHash : '';
    const body = new FormData();
    body.append('job_id', jobId);
    body.append('action', save ? 'save' : 'unsave');
    if (csrfHash) body.append(csrfName, csrfHash);

    return fetch('<?= base_url('jobs/toggle-save') ?>/' + jobId, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: body
    }).then(async r => {
      const data = await r.json().catch(() => ({}));
      if (data.csrfHash && window.JR_USER) {
        window.JR_USER.csrfHash = data.csrfHash;
      }
      return r.ok && data.success !== false ? data : Promise.reject(r.status);
    });
  }

  document.querySelectorAll('.save-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const jobId = btn.dataset.jobId || '';
      if (!isLoggedIn()) {
        redirectToLogin(jobId);
        return;
      }
      const wasSaved = btn.dataset.saved === 'true';
      const next = !wasSaved;
      renderSaveBtn(btn, next);
      btn.disabled = true;

      persistSave(jobId, next)
        .then(() => { btn.disabled = false; })
        .catch(() => {
          renderSaveBtn(btn, wasSaved);
          btn.disabled = false;
          btn.setAttribute('aria-label', 'Could not save — try again');
        });
    });
  });

  (function resumePendingSave() {
    if (!isLoggedIn()) return;
    const pending = new URLSearchParams(window.location.search).get('save');
    if (!pending) return;
    const btn = document.querySelector('.save-btn[data-job-id="' + pending + '"]');
    if (btn && btn.dataset.saved !== 'true') {
      renderSaveBtn(btn, true);
      persistSave(pending, true).catch(() => renderSaveBtn(btn, false));
    }
  })();

  /* ── Verified employer tooltip ── */
  document.querySelectorAll('.verified-check').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const wasOpen = btn.classList.contains('open');
      document.querySelectorAll('.verified-check.open').forEach(o => o.classList.remove('open'));
      if (!wasOpen) btn.classList.add('open');
    });
  });
  document.addEventListener('click', () => {
    document.querySelectorAll('.verified-check.open').forEach(o => o.classList.remove('open'));
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') document.querySelectorAll('.verified-check.open').forEach(o => o.classList.remove('open'));
  });
})();
</script>
<?= $this->endSection() ?>
