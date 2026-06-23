<?php
/**
 * Job card partial — JobberRecruit
 * ---------------------------------------------------------------------------
 * Renders ONE job card. Handles three states from a single $job array:
 *   - featured   (orange "Featured" badge, brand "Quick apply" button)
 *   - standard   (no badge, "Quick apply" button)
 *   - closed     (greyed out, struck-through salary, disabled "Position closed")
 *
 * USAGE (from the homepage view, replacing the 9 hardcoded <article> cards):
 *
 *   <div class="jobs-grid">
 *   <?php foreach ($jobs as $job): ?>
 *     <?= view('partials/_job_card', ['job' => $job]) ?>
 *   <?php endforeach; ?>
 *   </div>
 *
 * EXPECTED $job KEYS (map these to your DB columns in the controller):
 *   slug        string   URL slug, e.g. 'software-engineer-buildng'
 *   title       string   'Software Engineer'
 *   company     string   'BuildNG Technologies'
 *   verified    bool     true => show verified-employer check
 *   logo        string   2-letter monogram, e.g. 'BN' (or '' to derive below)
 *   location    string   'Remote', 'Lagos', etc.
 *   type        string   'Contract', 'Full-time', etc.
 *   posted      string   '2d ago', 'Today', etc. (pre-formatted)
 *   salary      string   '500,000' (naira amount, no symbol — added in markup)
 *   period      string   'month' (defaults to 'month' if absent)
 *   featured    bool     true => Featured badge + featured styling
 *   closed      bool     true => render as a closed position
 *
 * NOTE: esc() escapes all dynamic output (CI4 default HTML context).
 *       The verified tooltip / save-btn markup is identical to the original
 *       template — only the data is now dynamic.
 */

$slug      = $job['slug']      ?? '';
$title     = $job['title']     ?? '';
$company   = $job['company']   ?? '';
$verified  = !empty($job['verified']);
$location  = $job['location']  ?? '';
$type      = $job['type']      ?? '';
$posted    = $job['posted']    ?? '';
$salary    = $job['salary']    ?? '';
$period    = $job['period']    ?? 'month';
$featured  = !empty($job['featured']);
$closed    = !empty($job['closed']);

// Derive a 2-letter monogram from the company name if none supplied.
$logo = $job['logo'] ?? '';
if ($logo === '' && $company !== '') {
    $parts = preg_split('/\s+/', trim($company));
    $logo  = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
}

// Build CSS class list for the <article>.
$cardClasses = 'job-card';
if ($featured) { $cardClasses .= ' job-card--featured'; }
if ($closed)   { $cardClasses .= ' job-card--fired'; }

// Accessible label, matching the original template's pattern.
$ariaLabel = $title . ' at ' . $company . ($closed ? ' — position closed' : '');
?>
<article class="<?= $cardClasses ?>" aria-label="<?= esc($ariaLabel, 'attr') ?>">
  <?php if ($featured): ?>
  <span class="badge-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
  <?php endif; ?>
  <div class="job-card-top">
    <div>
      <h3 class="job-title"><?= esc($title) ?></h3>
      <div class="job-company">
        <span class="job-company-name"><?= esc($company) ?></span>
        <?php if ($verified): ?>
        <button type="button" class="verified-check" aria-label="Verified employer — tap for details"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span></button>
        <?php endif; ?>
      </div>
    </div>
    <div class="job-logo" aria-hidden="true"><?= esc($logo) ?></div>
  </div>
  <div class="job-meta">
    <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($location) ?></span>
    <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc($type) ?></span>
    <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= esc($posted) ?></span>
  </div>
  <div class="job-salary-row">
    <span class="job-salary<?= $closed ? ' job-salary--fired' : '' ?>">&#8358;<?= esc($salary) ?> / <?= esc($period) ?></span>
    <?php if ($closed): ?>
    <span class="badge-fired"><svg aria-hidden="true"><use href="#i-x-circle"/></svg> Position closed</span>
    <?php endif; ?>
  </div>
  <div class="job-actions">
    <?php if ($closed): ?>
    <button class="btn-closed" disabled aria-disabled="true"><svg aria-hidden="true"><use href="#i-x-circle"/></svg> Position closed</button>
    <?php else: ?>
    <a href="/jobs/<?= esc($slug, 'url') ?>" class="btn btn-primary">Quick apply</a>
    <?php endif; ?>
    <button class="save-btn" data-job-id="<?= esc($slug, 'attr') ?>" aria-label="Save job" data-saved="false"><svg aria-hidden="true"><use href="#i-bookmark"/></svg> Save</button>
  </div>
</article>
