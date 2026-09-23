<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/employer-public-profile.css') ?>">
<style>
/* Any specific overrides can go here */
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main">

<!-- ══ HERO ══ -->
<div class="ep-hero">
  <div class="container">
    <div class="ep-hero-inner">
      <div class="ep-logo" aria-hidden="true">
        <?php if (!empty($company->logo)): ?>
          <img src="<?= resolve_image_url($company->logo, 'company', $company->company_name) ?>" alt="<?= esc($company->company_name) ?>">
        <?php else: ?>
          <?php 
            $initials = '';
            foreach (explode(' ', $company->company_name) as $p) { $initials .= substr($p, 0, 1); }
            $initials = strtoupper(substr($initials, 0, 2));
            echo esc($initials);
          ?>
        <?php endif; ?>
      </div>
      <div class="ep-hero-body">
        <h1 class="ep-name">
          <?= esc($company->company_name) ?>
          <?php if ($company->is_verified == 1): ?>
            <span class="badge-verified" aria-label="Verified employer">
              <svg aria-hidden="true"><use href="#i-shield"/></svg> Verified
            </span>
          <?php endif; ?>
        </h1>
        <p class="ep-tagline"><?= esc($company->tagline) ?: 'Building the payments infrastructure that powers businesses across Africa' ?></p>
        <div class="ep-pills">
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-chip"/></svg> <?= esc($company->industry ?? "Not specified") ?></span>
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($company->location) ?> State</span>
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-users"/></svg> <?= esc($company->company_size) ?: '201–500' ?> employees</span>
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-globe"/></svg> <?= esc($company->remote_policy) ?: 'Hybrid' ?></span>
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= count($openJobs) ?> open <?= count($openJobs) === 1 ? 'role' : 'roles' ?></span>
        </div>
        <div class="ep-hero-actions">
          <a href="#open-roles" class="btn btn-accent">View <?= count($openJobs) ?> open <?= count($openJobs) === 1 ? 'role' : 'roles' ?></a>
          <?php if (!empty($company->website)): ?>
            <a href="<?= esc(strpos($company->website, 'http') === 0 ? $company->website : 'https://' . $company->website) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-white btn-sm">
              <svg aria-hidden="true" width="14" height="14"><use href="#i-globe"/></svg> <?= esc(parse_url(strpos($company->website, 'http') === 0 ? $company->website : 'https://' . $company->website, PHP_URL_HOST) ?: $company->website) ?>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab nav -->
  <div class="ep-tabs">
    <div class="container">
      <ul class="ep-tab-list" role="tablist">
        <li><a href="#about" class="active">About</a></li>
        <?php if (!empty($openJobs)): ?>
          <li><a href="#open-roles">Jobs (<?= count($openJobs) ?>)</a></li>
        <?php endif; ?>
        <?php if (!empty($company->benefits)): ?>
          <li><a href="#benefits">Benefits</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>

<!-- ══ MAIN LAYOUT ══ -->
<div class="container ep-layout">

  <!-- LEFT: main content -->
  <div class="ep-main">

    <!-- Verified badge -->
    <?php if ($company->is_verified == 1): ?>
      <div class="ep-verified-bar">
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
        <span><strong>Verified employer</strong> — <?= esc($company->company_name) ?>'s CAC registration and business documents have been verified by the JobberRecruit team.</span>
      </div>
    <?php endif; ?>

    <!-- About -->
    <div class="ep-card" id="about">
      <h2 class="ep-card-title"><svg aria-hidden="true"><use href="#i-building"/></svg> About <?= esc($company->company_name) ?></h2>
      <div style="font-size:.9rem;line-height:1.8;color:var(--text)">
        <?php if (!empty($company->description)): ?>
          <?= nl2br(esc($company->description)) ?>
        <?php else: ?>
          <p><?= esc($company->company_name) ?> is a leading organization in the <?= esc(!empty($industries) ? implode(', ', array_column($industries, 'name')) : 'specified') ?> sector. They are dedicated to delivering top-tier services and building long-term value for their clients.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Benefits & Perks -->
    <?php if (!empty($company->benefits)): ?>
      <div class="ep-card" id="benefits">
        <h2 class="ep-card-title"><svg aria-hidden="true"><use href="#i-gift"/></svg> Benefits &amp; Perks</h2>
        <div class="ep-benefits">
          <?php 
            $bList = is_string($company->benefits) ? explode("\n", $company->benefits) : [];
            foreach ($bList as $b):
              $b = trim($b);
              if (empty($b)) continue;
          ?>
            <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> <?= esc($b) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Hiring process -->
    <?php if (!empty($company->hiring_process)): ?>
      <div class="ep-card">
        <h2 class="ep-card-title"><svg aria-hidden="true"><use href="#i-chip"/></svg> Our Hiring Process</h2>
        <div class="ep-process-text" style="font-size:.9rem;line-height:1.8;color:var(--text)">
          <?= nl2br(esc($company->hiring_process)) ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Open roles -->
    <div class="ep-card" id="open-roles">
      <h2 class="ep-card-title"><svg aria-hidden="true"><use href="#i-bag"/></svg> Open Roles <span style="font-size:.8rem;font-weight:400;color:var(--muted);margin-left:4px">(<?= count($openJobs) ?> <?= count($openJobs) === 1 ? 'position' : 'positions' ?>)</span></h2>
      <div style="display:flex;flex-direction:column;gap:10px">
        <?php if (!empty($openJobs)): ?>
          <?php foreach ($openJobs as $job): ?>
            <a href="<?= base_url('jobs/' . $job->slug) ?>" class="ep-job-card">
              <div class="ep-job-ic"><svg aria-hidden="true"><use href="#i-chip"/></svg></div>
              <div class="ep-job-body">
                <div class="ep-job-title"><?= esc($job->title) ?></div>
                <div class="ep-job-meta">
                  <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($job->location) ?></span>
                  <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc(ucfirst($job->job_type)) ?></span>
                  <span><svg aria-hidden="true"><use href="#i-globe"/></svg> <?= esc(ucfirst($job->location_type)) ?></span>
                  <span><svg aria-hidden="true"><use href="#i-coins"/></svg> <?= esc($job->salary) ?: 'Negotiable' ?></span>
                </div>
              </div>
              <?php if ($job->urgency === 'urgent'): ?>
                <span class="ep-job-badge urgent">Urgently hiring</span>
              <?php else: ?>
                <span class="ep-job-badge open">Open</span>
              <?php endif; ?>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color:var(--muted);text-align:center;padding:24px 0">No open roles currently posted by this company.</p>
        <?php endif; ?>
      </div>
    </div>

  </div><!-- /ep-main -->

  <!-- RIGHT: sidebar -->
  <aside class="ep-aside">

    <!-- Quick facts -->
    <div class="ep-card">
      <h2 class="ep-card-title"><svg aria-hidden="true"><use href="#i-building"/></svg> Company overview</h2>
      <ul class="ep-facts">
        <li>
          <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-chip"/></svg></span>
          <span class="ep-fact-kv"><span class="ep-fact-k">Industry</span><span class="ep-fact-v"><?= esc(!empty($industries) ? implode(', ', array_column($industries, 'name')) : "Not specified") ?></span></span>
        </li>
        <li>
          <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></span>
          <span class="ep-fact-kv"><span class="ep-fact-k">Company size</span><span class="ep-fact-v"><?= esc($company->company_size) ?: '201–500' ?> employees</span></span>
        </li>
        <?php if (!empty($company->founded_year)): ?>
          <li>
            <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-clock"/></svg></span>
            <span class="ep-fact-kv"><span class="ep-fact-k">Founded</span><span class="ep-fact-v"><?= esc($company->founded_year) ?></span></span>
          </li>
        <?php endif; ?>
        <li>
          <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-pin"/></svg></span>
          <span class="ep-fact-kv"><span class="ep-fact-k">Headquarters</span><span class="ep-fact-v"><?= esc($company->location) ?> State</span></span>
        </li>
        <li>
          <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-globe"/></svg></span>
          <span class="ep-fact-kv"><span class="ep-fact-k">Remote policy</span><span class="ep-fact-v"><?= esc($company->remote_policy) ?: 'Hybrid (flexible by role)' ?></span></span>
        </li>
        <?php if (!empty($company->website)): ?>
          <li>
            <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-globe"/></svg></span>
            <span class="ep-fact-kv">
              <span class="ep-fact-k">Website</span>
              <span class="ep-fact-v">
                <a href="<?= esc(strpos($company->website, 'http') === 0 ? $company->website : 'https://' . $company->website) ?>" target="_blank" rel="noopener" style="color:var(--brand)">
                  <?= esc(parse_url(strpos($company->website, 'http') === 0 ? $company->website : 'https://' . $company->website, PHP_URL_HOST) ?: $company->website) ?>
                </a>
              </span>
            </span>
          </li>
        <?php endif; ?>
      </ul>

      <!-- Social links -->
      <?php if (!empty($company->linkedin) || !empty($company->twitter) || !empty($company->facebook) || !empty($company->instagram)): ?>
        <div class="ep-socials">
          <?php if (!empty($company->linkedin)): ?>
            <a href="<?= esc($company->linkedin) ?>" target="_blank" rel="noopener noreferrer" class="ep-social-link">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color:#0077b5" aria-hidden="true"><path d="M20.4 20.5h-3.6V15c0-1.3 0-3-1.9-3s-2.1 1.4-2.1 2.9v5.6H9.4V9h3.4v1.6h.1c.5-.9 1.6-1.9 3.4-1.9 3.6 0 4.3 2.4 4.3 5.5v6.3ZM5.3 7.4A2.1 2.1 0 1 1 5.3 3a2.1 2.1 0 0 1 0 4.4Zm1.8 13.1H3.5V9h3.6v11.5Z"/></svg>
              LinkedIn
            </a>
          <?php endif; ?>
          <?php if (!empty($company->twitter)): ?>
            <a href="<?= esc($company->twitter) ?>" target="_blank" rel="noopener noreferrer" class="ep-social-link">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color:#1da1f2" aria-hidden="true"><path d="M18.9 1.2h3.7l-8 9.2 9.4 12.4h-7.4l-5.8-7.6-6.6 7.6H.5l8.6-9.8L0 1.2h7.6l5.2 6.9 6.1-6.9Zm-1.3 19.5h2L6.4 3.2H4.3l13.3 17.5Z"/></svg>
              Twitter
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- CTA card -->
    <?php if (!empty($openJobs)): ?>
      <div class="ep-card" style="background:var(--brand-light);border-color:#c8dff2;text-align:center">
        <p style="font-size:.88rem;color:var(--brand-deep);font-weight:600;margin-bottom:4px"><?= count($openJobs) ?> open <?= count($openJobs) === 1 ? 'position' : 'positions' ?> at <?= esc($company->company_name) ?></p>
        <p style="font-size:.8rem;color:var(--muted);margin-bottom:14px">Be the first to apply — most roles close within 30 days</p>
        <a href="#open-roles" class="btn btn-primary" style="width:100%;justify-content:center">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-bag"/></svg> Browse open roles
        </a>
      </div>
    <?php endif; ?>

  </aside>
</div><!-- /ep-layout -->

</main>
<?= $this->endSection() ?>
