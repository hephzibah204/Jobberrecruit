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
      <div class="ep-logo" aria-hidden="true">PS</div>
      <div class="ep-hero-body">
        <h1 class="ep-name">
          <?= esc($company->company_name) ?>
          <span class="badge-verified" aria-label="Verified employer">
            <svg aria-hidden="true"><use href="#i-shield"/></svg> Verified
          </span>
        </h1>
        <p class="ep-tagline">Building the payments infrastructure that powers businesses across Africa</p>
        <div class="ep-pills">
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-chip"/></svg> <?= esc($company->industry ?? "Not specified") ?></span>
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($company->location) ?> State</span>
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-users"/></svg> 201–500 employees</span>
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-globe"/></svg> Hybrid</span>
          <span class="ep-pill"><svg aria-hidden="true"><use href="#i-bag"/></svg> 8 open roles</span>
        </div>
        <div class="ep-hero-actions">
          <a href="#open-roles" class="btn btn-accent">View 8 open roles</a>
          <a href="https://paystack.com" target="_blank" rel="noopener noreferrer" class="btn btn-white btn-sm">
            <svg aria-hidden="true" width="14" height="14"><use href="#i-globe"/></svg> paystack.com
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab nav -->
  <div class="ep-tabs">
    <div class="container">
      <ul class="ep-tab-list" role="tablist">
        <li><a href="#about"      class="active">About</a></li>
        <li><a href="#open-roles">Jobs (8)</a></li>
        <li><a href="#benefits">Benefits</a></li>
      </ul>
    </div>
  </div>
</div>

<!-- ══ MAIN LAYOUT ══ -->
<div class="container ep-layout">

  <!-- LEFT: main content -->
  <div class="ep-main">

    <!-- Verified badge -->
    <div class="ep-verified-bar">
      <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
      <span><strong>Verified employer</strong> — <?= esc($company->company_name) ?>'s CAC registration and business documents have been verified by the JobberRecruit team.</span>
    </div>

    <!-- About -->
    <div class="ep-card" id="about">
      <h2 class="ep-card-title"><svg aria-hidden="true"><use href="#i-building"/></svg> About <?= esc($company->company_name) ?></h2>
      <p style="font-size:.9rem;line-height:1.8;color:var(--text)">
        <?= esc($company->company_name) ?> is a financial technology company building modern payment infrastructure for businesses in Africa. Founded in 2015 and acquired by Stripe in 2020, <?= esc($company->company_name) ?> helps businesses in Nigeria, Ghana, South Africa, and Kenya process payments securely and reliably.
      </p>
      <p style="font-size:.9rem;line-height:1.8;color:var(--text);margin-top:12px">
        From small startups to large enterprises, over 200,000 businesses use <?= esc($company->company_name) ?> to accept payments online and offline, manage subscriptions, and expand across borders. Our team is passionate about making commerce accessible to every African business.
      </p>
    </div>

    <!-- Benefits & Perks -->
    <div class="ep-card" id="benefits">
      <h2 class="ep-card-title"><svg aria-hidden="true"><use href="#i-gift"/></svg> Benefits &amp; Perks</h2>
      <div class="ep-benefits">
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> HMO / Health Insurance</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> HMO covers dependants</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> Life Insurance</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> Contributory Pension (CPS)</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> 13th Month Salary</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> Performance Bonus</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> Transport Allowance</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> Training &amp; Development Budget</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> Flexible Working Hours</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> Paid Parental Leave</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> Team Retreats</span>
        <span class="ep-benefit-pill"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> Stock Options / Equity</span>
      </div>
    </div>

    <!-- Hiring process -->
    <div class="ep-card">
      <h2 class="ep-card-title"><svg aria-hidden="true"><use href="#i-chip"/></svg> Our Hiring Process</h2>
      <p class="ep-process-text">
        <strong>Stage 1:</strong> CV review — we review every application within 7 days.<br>
        <strong>Stage 2:</strong> 30-minute video call with a member of the People team.<br>
        <strong>Stage 3:</strong> Technical or skills assessment (role-specific, ~2 hours).<br>
        <strong>Stage 4:</strong> Final panel interview with the hiring manager and team lead.<br>
        <strong>Offer:</strong> We aim to give a decision within 10 business days of your first interview.
      </p>
    </div>

    <!-- Open roles -->
    <div class="ep-card" id="open-roles">
      <h2 class="ep-card-title"><svg aria-hidden="true"><use href="#i-bag"/></svg> Open Roles <span style="font-size:.8rem;font-weight:400;color:var(--muted);margin-left:4px">(8 positions)</span></h2>
      <div style="display:flex;flex-direction:column;gap:10px">

        <a href="/jobs/product-designer-paystack-lagos" class="ep-job-card">
          <div class="ep-job-ic"><svg aria-hidden="true"><use href="#i-chip"/></svg></div>
          <div class="ep-job-body">
            <div class="ep-job-title">Product Designer</div>
            <div class="ep-job-meta">
              <span><svg aria-hidden="true"><use href="#i-pin"/></svg> Lagos</span>
              <span><svg aria-hidden="true"><use href="#i-bag"/></svg> Full-time</span>
              <span><svg aria-hidden="true"><use href="#i-globe"/></svg> Hybrid</span>
              <span><svg aria-hidden="true"><use href="#i-coins"/></svg> Negotiable</span>
            </div>
          </div>
          <span class="ep-job-badge urgent">Urgently hiring</span>
        </a>

        <a href="/jobs/backend-engineer-paystack" class="ep-job-card">
          <div class="ep-job-ic"><svg aria-hidden="true"><use href="#i-chip"/></svg></div>
          <div class="ep-job-body">
            <div class="ep-job-title">Backend Engineer (Node.js)</div>
            <div class="ep-job-meta">
              <span><svg aria-hidden="true"><use href="#i-pin"/></svg> Lagos</span>
              <span><svg aria-hidden="true"><use href="#i-bag"/></svg> Full-time</span>
              <span><svg aria-hidden="true"><use href="#i-globe"/></svg> Hybrid · 3 days on-site</span>
            </div>
          </div>
          <span class="ep-job-badge open">Open</span>
        </a>

        <a href="/jobs/data-analyst-paystack" class="ep-job-card">
          <div class="ep-job-ic"><svg aria-hidden="true"><use href="#i-chart"/></svg></div>
          <div class="ep-job-body">
            <div class="ep-job-title">Data Analyst</div>
            <div class="ep-job-meta">
              <span><svg aria-hidden="true"><use href="#i-pin"/></svg> Lagos</span>
              <span><svg aria-hidden="true"><use href="#i-bag"/></svg> Full-time</span>
              <span><svg aria-hidden="true"><use href="#i-globe"/></svg> Remote</span>
              <span><svg aria-hidden="true"><use href="#i-coins"/></svg> ₦400,000 – ₦600,000/mo</span>
            </div>
          </div>
          <span class="ep-job-badge open">Open</span>
        </a>

        <a href="/jobs/customer-success-paystack" class="ep-job-card">
          <div class="ep-job-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></div>
          <div class="ep-job-body">
            <div class="ep-job-title">Customer Success Manager</div>
            <div class="ep-job-meta">
              <span><svg aria-hidden="true"><use href="#i-pin"/></svg> Lagos</span>
              <span><svg aria-hidden="true"><use href="#i-bag"/></svg> Full-time</span>
              <span><svg aria-hidden="true"><use href="#i-globe"/></svg> On-site</span>
            </div>
          </div>
          <span class="ep-job-badge open">Open</span>
        </a>

        <a href="/jobs?company=paystack" class="btn btn-outline" style="text-align:center;justify-content:center;margin-top:6px">
          View all 8 open roles at <?= esc($company->company_name) ?> →
        </a>
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
          <span class="ep-fact-kv"><span class="ep-fact-k">Industry</span><span class="ep-fact-v"><?= esc($company->industry ?? "Not specified") ?></span></span>
        </li>
        <li>
          <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></span>
          <span class="ep-fact-kv"><span class="ep-fact-k">Company size</span><span class="ep-fact-v">201–500 employees</span></span>
        </li>
        <li>
          <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-clock"/></svg></span>
          <span class="ep-fact-kv"><span class="ep-fact-k">Founded</span><span class="ep-fact-v">2015</span></span>
        </li>
        <li>
          <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-pin"/></svg></span>
          <span class="ep-fact-kv"><span class="ep-fact-k">Headquarters</span><span class="ep-fact-v"><?= esc($company->location) ?> State</span></span>
        </li>
        <li>
          <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-globe"/></svg></span>
          <span class="ep-fact-kv"><span class="ep-fact-k">Remote policy</span><span class="ep-fact-v">Hybrid (flexible by role)</span></span>
        </li>
        <li>
          <span class="ep-fact-ic"><svg aria-hidden="true"><use href="#i-globe"/></svg></span>
          <span class="ep-fact-kv"><span class="ep-fact-k">Website</span><span class="ep-fact-v"><a href="https://paystack.com" target="_blank" rel="noopener" style="color:var(--brand)">paystack.com</a></span></span>
        </li>
      </ul>

      <!-- Social links -->
      <div class="ep-socials">
        <a href="https://linkedin.com/company/paystack" target="_blank" rel="noopener noreferrer" class="ep-social-link">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color:#0077b5" aria-hidden="true"><path d="M20.4 20.5h-3.6V15c0-1.3 0-3-1.9-3s-2.1 1.4-2.1 2.9v5.6H9.4V9h3.4v1.6h.1c.5-.9 1.6-1.9 3.4-1.9 3.6 0 4.3 2.4 4.3 5.5v6.3ZM5.3 7.4A2.1 2.1 0 1 1 5.3 3a2.1 2.1 0 0 1 0 4.4Zm1.8 13.1H3.5V9h3.6v11.5Z"/></svg>
          LinkedIn
        </a>
        <a href="https://twitter.com/paystack" target="_blank" rel="noopener noreferrer" class="ep-social-link">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color:#1da1f2" aria-hidden="true"><path d="M18.9 1.2h3.7l-8 9.2 9.4 12.4h-7.4l-5.8-7.6-6.6 7.6H.5l8.6-9.8L0 1.2h7.6l5.2 6.9 6.1-6.9Zm-1.3 19.5h2L6.4 3.2H4.3l13.3 17.5Z"/></svg>
          Twitter
        </a>
      </div>
    </div>

    <!-- CTA card -->
    <div class="ep-card" style="background:var(--brand-light);border-color:#c8dff2;text-align:center">
      <p style="font-size:.88rem;color:var(--brand-deep);font-weight:600;margin-bottom:4px">8 open positions at <?= esc($company->company_name) ?></p>
      <p style="font-size:.8rem;color:var(--muted);margin-bottom:14px">Be the first to apply — most roles close within 30 days</p>
      <a href="#open-roles" class="btn btn-primary" style="width:100%;justify-content:center">
        <svg aria-hidden="true" width="16" height="16"><use href="#i-bag"/></svg> Browse open roles
      </a>
    </div>

  </aside>
</div><!-- /ep-layout -->

</main>
<?= $this->endSection() ?>
