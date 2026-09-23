<?php $page_title = 'Company Profile'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.prof-grid {
  display: grid;
  grid-template-columns: 320px minmax(0, 1fr);
  gap: 20px;
  align-items: start;
  width: 100%;
}
.prof-col {
  display: flex;
  flex-direction: column;
  gap: 14px;
  min-width: 0;
}
.prof-col .card {
  margin: 0 !important;
  box-shadow: 0 1px 3px rgba(10, 47, 87, 0.04);
}
.prof-col .card-head {
  padding: 10px 16px;
  background: #ffffff;
  border-bottom: 1px solid #edf2f7;
}
.prof-col .card-head .card-title {
  font-size: 0.88rem;
  font-weight: 700;
}
.prof-col .card-body {
  padding: 12px 16px;
}
.id-card {
  text-align: center;
  padding: 18px 16px 14px !important;
}
.id-logo {
  width: 80px !important;
  height: 80px !important;
  border-radius: 50% !important;
  margin: 0 auto 8px !important;
  padding: 6px !important;
  border: 3px solid var(--brand-light, #E6F0F8) !important;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff;
}
.id-name {
  font-size: 1.05rem !important;
  font-weight: 800;
  color: var(--brand-deep, #0A2F57);
  margin-bottom: 2px;
}
.id-mail {
  font-size: 0.78rem !important;
  color: var(--muted, #64748b) !important;
  margin: 2px 0 8px !important;
}
.id-actions {
  margin-top: 10px !important;
}
.pf {
  display: flex;
  align-items: center;
  gap: 12px;
}
.pf-ring {
  width: 48px;
  height: 48px;
  flex-shrink: 0;
}
.pf-ring svg {
  width: 48px;
  height: 48px;
}
.pf-body b {
  font-size: 0.84rem;
  display: block;
}
.pf-body p {
  font-size: 0.75rem;
  margin: 0;
  color: var(--muted, #64748b);
  line-height: 1.35;
}
.plan-panel {
  padding: 12px 14px;
  border-radius: 8px;
}
.plan-panel b {
  font-size: 0.92rem;
}
.plan-panel p {
  font-size: 0.78rem;
  margin-bottom: 8px;
}
.info-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 8px 12px;
}
.info-item {
  background: #f8fafc;
  border: 1px solid #edf2f7;
  border-radius: 8px;
  padding: 8px 12px;
  min-width: 0;
}
.info-lbl {
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: #64748b;
  margin-bottom: 2px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.info-lbl svg {
  width: 13px;
  height: 13px;
  color: var(--brand, #0861A9);
  flex-shrink: 0;
}
.info-val {
  font-size: 0.86rem;
  font-weight: 600;
  color: var(--brand-deep, #0A2F57);
  overflow-wrap: anywhere;
}
.info-desc {
  grid-column: 1 / -1;
  background: #f8fafc;
  border: 1px solid #edf2f7;
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 0.84rem;
  line-height: 1.55;
  color: #334155;
}
.chips {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}
.chip {
  display: inline-flex;
  align-items: center;
  padding: 2px 8px;
  background: #e2e8f0;
  border-radius: 4px;
  font-size: 0.72rem;
  font-weight: 600;
  color: #334155;
}
.cac-row {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  align-items: center;
}
@media (max-width: 991px) {
  .prof-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
  .info-grid {
    grid-template-columns: 1fr;
    gap: 8px;
  }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Resolve display name and initials
$displayName = !empty($employer->company_name) ? $employer->company_name : 'Not Set';
$words = explode(' ', preg_replace('/\s+/', ' ', trim($displayName)));
$initials = count($words) >= 2
    ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
    : strtoupper(substr($displayName, 0, 2));

// Logo path resolution
$logoPath = $employer->logo ?? '';
$hasLogo = false;
if ($logoPath) {
    if (filter_var($logoPath, FILTER_VALIDATE_URL) || str_starts_with($logoPath, 'http')) {
        $hasLogo = true;
    } elseif (file_exists(FCPATH . $logoPath)) {
        $hasLogo = true;
    }
}
$logoSrc = $hasLogo
    ? ((str_starts_with($logoPath, 'http')) ? $logoPath : base_url($logoPath))
    : null;

// Profile completion percentage and dashoffset
$pct = $profileCompletion ?? 0;
$dashoffset = round(239 * (1 - ($pct / 100)));

// CAC Document status resolution
$cacFilePath = '';
$cacStatus = '';
if (($hasCACDocument ?? false) && ($cacDocument ?? false)) {
    if (is_array($cacDocument)) {
        $cacFilePath = $cacDocument['file_path'] ?? '';
        $cacStatus = $cacDocument['status'] ?? '';
    } else {
        $cacFilePath = $cacDocument->file_path ?? '';
        $cacStatus = $cacDocument->status ?? '';
    }
}
?>

<div class="page-head" style="margin-bottom: 0;">
  <div class="page-head-left">
    <h1><svg aria-hidden="true" width="22" height="22"><use href="#i-building"/></svg> Company Profile</h1>
    <p>This information appears on your job listings and public employer page.</p>
  </div>
  <div class="page-actions">
    <?php
      $companySlug = !empty($employer->company_name) ? url_title($employer->company_name, '-', true) : ($employer->id ?? '');
    ?>
    <a href="<?= base_url('employer/' . esc($companySlug)) ?>" class="emp-btn emp-btn-outline emp-btn-sm" target="_blank"><svg aria-hidden="true" width="14" height="14"><use href="#i-eye"/></svg> View Public Profile</a>
    <a href="<?= base_url('employer/profile/edit') ?>" class="emp-btn emp-btn-primary emp-btn-sm"><svg aria-hidden="true" width="14" height="14"><use href="#i-edit"/></svg> Edit Profile</a>
  </div>
</div>

<div class="prof-grid">
  <div class="prof-col">
    <!-- Identity card -->
    <section class="card id-card" aria-label="Company identity">
      <div class="id-logo">
        <?php if ($logoSrc): ?>
          <img src="<?= esc($logoSrc) ?>" alt="<?= esc($displayName) ?>" class="id-logo-img" style="width:100%;height:100%;object-fit:contain;">
        <?php else: ?>
          <span class="id-logo-text" style="font-size:1.5rem;font-weight:800;color:var(--brand);"><?= esc($initials) ?></span>
        <?php endif; ?>
      </div>
      <div class="id-name"><?= esc($displayName) ?></div>
      <?php if (!empty($employer->tagline)): ?>
        <div class="id-mail" style="font-style:italic"><?= esc($employer->tagline) ?></div>
      <?php endif; ?>
      <div class="id-mail"><?= esc($employer->contact_email ?? '') ?></div>
      <?php if ($canShowTrustBadge ?? false): ?>
        <span class="badge-verified" style="font-size:0.68rem;padding:3px 10px;"><svg aria-hidden="true" width="12" height="12"><use href="#i-shield"/></svg> Verified Employer</span>
      <?php endif; ?>
      <div class="id-actions">
        <a href="<?= base_url('employer/profile/edit') ?>" class="emp-btn emp-btn-outline emp-btn-sm emp-btn-block"><svg aria-hidden="true" width="14" height="14"><use href="#i-edit"/></svg> Edit Profile</a>
      </div>
    </section>

    <!-- Profile completion -->
    <section class="card" aria-label="Profile completion">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true" width="15" height="15"><use href="#i-check-c"/></svg> Profile Completion</span></div>
      <div class="card-body">
        <div class="pf">
          <div class="pf-ring" role="img" aria-label="Profile <?= esc($pct) ?> percent complete" style="position:relative;display:flex;align-items:center;justify-content:center;">
            <svg viewBox="0 0 88 88" aria-hidden="true">
              <circle class="track" cx="44" cy="44" r="38" style="fill:none;stroke:#e2e8f0;stroke-width:8;"/>
              <circle class="prog" cx="44" cy="44" r="38" style="fill:none;stroke:var(--brand);stroke-width:8;stroke-linecap:round;stroke-dasharray:239;stroke-dashoffset: <?= $dashoffset ?>;"/>
            </svg>
            <span class="pct" style="font-size:0.75rem;font-weight:700;position:absolute;"><?= esc($pct) ?>%</span>
          </div>
          <div class="pf-body">
            <b>Profile <?= $pct == 100 ? 'complete' : 'incomplete' ?></b>
            <p>Complete profiles get more applications on every listing.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Account summary -->
    <section class="card" aria-label="Account summary">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true" width="15" height="15"><use href="#i-star"/></svg> Account Summary</span></div>
      <div class="card-body">
        <div class="plan-panel">
          <?php if ($hasUnlimitedAccess ?? false): ?>
            <b>Unlimited Access Plan</b>
            <p>Enterprise account with unlimited job postings</p>
            <?php if (!empty($employer->unlimited_until)): ?>
              <p style="font-size:.7rem;margin-top:-4px;margin-bottom:8px;opacity:0.85;">Valid until: <?= date('M d, Y', strtotime($employer->unlimited_until)) ?></p>
            <?php endif; ?>
            <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm" style="width:100%;margin-top:4px;">Manage Plan</a>
          <?php else: ?>
            <?php
            $hasActivePlan = false;
            $planName = '';
            $planEndsAt = '';
            if (!empty($activeSubscription)) {
                if (is_object($activeSubscription)) {
                    $hasActivePlan = !empty($activeSubscription->plan_name);
                    $planName = $activeSubscription->plan_name ?? '';
                    $planEndsAt = $activeSubscription->ends_at ?? '';
                } else {
                    $hasActivePlan = !empty($activeSubscription['plan_name']);
                    $planName = $activeSubscription['plan_name'] ?? '';
                    $planEndsAt = $activeSubscription['ends_at'] ?? '';
                }
            }
            ?>
            <?php if ($hasActivePlan): ?>
              <b><?= esc($planName) ?></b>
              <p>Active Subscription Plan</p>
              <p style="font-size:.7rem;margin-top:-4px;margin-bottom:8px;opacity:0.85;">Expires: <?= date('M d, Y', strtotime($planEndsAt)) ?></p>
              <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm" style="width:100%;margin-top:4px;">Renew / Upgrade</a>
            <?php else: ?>
              <b>No Active Plan</b>
              <p>Job Credits: <?= number_format($creditBalance ?? 0) ?></p>
              <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm" style="width:100%;margin-top:4px;">Get a Plan</a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </div>

  <div class="prof-col">
    <!-- Company info -->
    <section class="card" aria-label="Company information">
      <div class="card-head">
        <span class="card-title"><svg aria-hidden="true" width="15" height="15"><use href="#i-building"/></svg> Company Information</span>
        <a href="<?= base_url('employer/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true" width="13" height="13"><use href="#i-arrow-r"/></svg></a>
      </div>
      <div class="card-body">
        <div class="info-grid">
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-building"/></svg> Company name</div>
            <div class="info-val"><?= esc($employer->company_name ?? 'Not Set') ?></div>
          </div>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-briefcase"/></svg> Industries</div>
            <div class="chips" style="margin-top:2px">
              <?php if (!empty($employer->industries)): ?>
                <?php foreach ($employer->industries as $ind): ?>
                  <span class="chip"><?= esc($ind->name) ?></span>
                <?php endforeach; ?>
              <?php else: ?>
                <span class="chip">Not Set</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-users"/></svg> Company size</div>
            <div class="info-val"><?= esc($employer->company_size ?? 'Not Set') ?></div>
          </div>
          <?php if (!empty($employer->company_type)): ?>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-briefcase"/></svg> Company type</div>
            <div class="info-val"><?= esc($employer->company_type) ?></div>
          </div>
          <?php endif; ?>
          <?php if (!empty($employer->founded_year)): ?>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-calendar"/></svg> Founded</div>
            <div class="info-val"><?= esc($employer->founded_year) ?></div>
          </div>
          <?php endif; ?>
          <?php if (!empty($employer->remote_policy)): ?>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-globe"/></svg> Remote policy</div>
            <div class="info-val"><?= esc(ucwords(str_replace('_', ' ', $employer->remote_policy))) ?></div>
          </div>
          <?php endif; ?>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-link"/></svg> Website</div>
            <div class="info-val">
              <?php if (!empty($employer->website)): ?>
                <a href="<?= esc($employer->website) ?>" target="_blank" rel="noopener"><?= esc($employer->website) ?></a>
              <?php else: ?>
                Not Set
              <?php endif; ?>
            </div>
          </div>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-calendar"/></svg> State / Location</div>
            <div class="info-val"><?= esc($employer->location ? $employer->location . ' State' : 'Not Set') ?></div>
          </div>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-doc"/></svg> User ID reference</div>
            <div class="info-val"><?= esc($employer->user_id ?? '') ?></div>
          </div>
          <div class="info-desc">
            <div class="info-lbl" style="margin-bottom:4px"><svg aria-hidden="true" width="13" height="13"><use href="#i-note"/></svg> Company description</div>
            <?= !empty($employer->description) ? nl2br(esc($employer->description)) : 'No description provided.' ?>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact info -->
    <section class="card" aria-label="Contact information">
      <div class="card-head">
        <span class="card-title"><svg aria-hidden="true" width="15" height="15"><use href="#i-mail"/></svg> Contact Information</span>
        <a href="<?= base_url('employer/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true" width="13" height="13"><use href="#i-arrow-r"/></svg></a>
      </div>
      <div class="card-body">
        <div class="info-grid">
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-users"/></svg> Contact person</div>
            <div class="info-val"><?= esc($employer->contact_name ?? 'Not Set') ?></div>
          </div>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-mail"/></svg> Contact email</div>
            <div class="info-val">
              <?php if (!empty($employer->contact_email)): ?>
                <a href="mailto:<?= esc($employer->contact_email) ?>"><?= esc($employer->contact_email) ?></a>
              <?php else: ?>
                Not Set
              <?php endif; ?>
            </div>
          </div>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-phone"/></svg> Phone number</div>
            <div class="info-val">
              <?php if (!empty($employer->contact_phone)): ?>
                <a href="tel:<?= esc($employer->contact_phone) ?>"><?= esc($employer->contact_phone) ?></a>
              <?php else: ?>
                Not Set
              <?php endif; ?>
            </div>
          </div>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-building"/></svg> Physical address</div>
            <div class="info-val"><?= esc($employer->company_address ?? 'Not Set') ?></div>
          </div>
          <?php if (!empty($employer->whatsapp)): ?>
          <div class="info-item">
            <div class="info-lbl"><svg aria-hidden="true" width="13" height="13"><use href="#i-whatsapp"/></svg> WhatsApp</div>
            <div class="info-val"><?= esc($employer->whatsapp) ?></div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <?php
    $socialLinks = array_filter([
        'linkedin'  => $employer->linkedin ?? null,
        'twitter'   => $employer->twitter ?? null,
        'facebook'  => $employer->facebook ?? null,
        'instagram' => $employer->instagram ?? null,
    ]);
    $benefitsList = [];
    if (!empty($employer->benefits)) {
        $decoded = json_decode($employer->benefits, true);
        $benefitsList = is_array($decoded) ? $decoded : [];
    }
    ?>
    <?php if (!empty($benefitsList) || !empty($employer->hiring_process) || !empty($socialLinks)): ?>
    <!-- Culture, benefits & social -->
    <section class="card" aria-label="Culture and social profiles">
      <div class="card-head">
        <span class="card-title"><svg aria-hidden="true" width="15" height="15"><use href="#i-star"/></svg> Culture &amp; Social</span>
        <a href="<?= base_url('employer/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true" width="13" height="13"><use href="#i-arrow-r"/></svg></a>
      </div>
      <div class="card-body">
        <?php if (!empty($benefitsList)): ?>
          <div class="info-lbl" style="margin-bottom:4px"><svg aria-hidden="true" width="13" height="13"><use href="#i-star"/></svg> Benefits &amp; perks</div>
          <div class="chips" style="margin-bottom:12px">
            <?php foreach ($benefitsList as $b): ?>
              <span class="chip"><?= esc(ucwords(str_replace('_', ' ', $b))) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($employer->hiring_process)): ?>
          <div class="info-lbl" style="margin-bottom:4px"><svg aria-hidden="true" width="13" height="13"><use href="#i-note"/></svg> Hiring process</div>
          <p style="margin-bottom:12px;font-size:0.84rem;line-height:1.5;"><?= nl2br(esc($employer->hiring_process)) ?></p>
        <?php endif; ?>
        <?php if (!empty($socialLinks)): ?>
          <div class="info-lbl" style="margin-bottom:6px"><svg aria-hidden="true" width="13" height="13"><use href="#i-globe"/></svg> Social profiles</div>
          <div style="display:flex;gap:6px;flex-wrap:wrap">
            <?php foreach ($socialLinks as $platform => $url): ?>
              <a href="<?= esc($url) ?>" target="_blank" rel="noopener" class="emp-btn emp-btn-outline emp-btn-sm" style="padding:4px 10px;font-size:0.75rem;">
                <svg aria-hidden="true" width="13" height="13"><use href="#i-<?= $platform === 'twitter' ? 'x-social' : $platform ?>"/></svg> <?= esc(ucfirst($platform)) ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
    <?php endif; ?>

    <!-- CAC verification -->
    <section class="card" aria-label="CAC certificate verification">
      <div class="card-head">
        <span class="card-title"><svg aria-hidden="true" width="15" height="15"><use href="#i-shield"/></svg> CAC Certificate Verification</span>
        <?php if ($cacStatus === 'approved'): ?>
          <span class="pill pill--reviewed"><svg aria-hidden="true" width="11" height="11"><use href="#i-check"/></svg> Approved</span>
        <?php elseif ($cacStatus === 'pending'): ?>
          <span class="pill pill--pending"><svg aria-hidden="true" width="11" height="11"><use href="#i-clock"/></svg> Under Review</span>
        <?php elseif ($cacStatus === 'rejected'): ?>
          <span class="pill pill--rejected"><svg aria-hidden="true" width="11" height="11"><use href="#i-x"/></svg> Rejected</span>
        <?php else: ?>
          <span class="pill pill--closed"><svg aria-hidden="true" width="11" height="11"><use href="#i-x"/></svg> Missing</span>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <div class="cac-row">
          <?php if (!empty($cacFilePath)): ?>
            <a href="<?= base_url(esc($cacFilePath)) ?>" target="_blank" class="emp-btn emp-btn-outline emp-btn-sm"><svg aria-hidden="true" width="14" height="14"><use href="#i-doc"/></svg> View Uploaded CAC Certificate</a>
          <?php endif; ?>
          <a href="<?= base_url('employer/profile/upload-document') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
            <svg aria-hidden="true" width="14" height="14"><use href="#i-refresh"/></svg> <?= empty($cacStatus) ? 'Upload Document' : 'Replace Document' ?>
          </a>
        </div>
        <?php if ($cacStatus === 'approved'): ?>
          <p style="font-size:.78rem;color:var(--muted);margin-top:8px;margin-bottom:0;">Your CAC business registration certificate is verified. Verification is mandatory for the Verified Employer badge and premium postings.</p>
        <?php elseif ($cacStatus === 'pending'): ?>
          <p style="font-size:.78rem;color:var(--muted);margin-top:8px;margin-bottom:0;">Your CAC business registration certificate is currently under review by our admin team.</p>
        <?php elseif ($cacStatus === 'rejected'): ?>
          <p style="font-size:.78rem;color:var(--muted);margin-top:8px;margin-bottom:0;">Your CAC document was rejected. Please upload a valid CAC certificate.
            <?php if (!empty($employer->rejection_reason)): ?>
              <br><strong class="text-danger">Reason:</strong> <?= esc($employer->rejection_reason) ?>
            <?php endif; ?>
          </p>
        <?php else: ?>
          <p style="font-size:.78rem;color:var(--muted);margin-top:8px;margin-bottom:0;">Upload your CAC business registration certificate to get verified. Verification is mandatory for the Verified Employer badge and premium postings.</p>
        <?php endif; ?>
      </div>
    </section>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/' . esc($employer->user_id ?? '')) ?>" class="emp-btn emp-btn-outline emp-btn-sm"><svg aria-hidden="true" width="14" height="14"><use href="#i-eye"/></svg> View Public Profile</a>
<a href="<?= base_url('employer/profile/edit') ?>" class="emp-btn emp-btn-primary emp-btn-sm"><svg aria-hidden="true" width="14" height="14"><use href="#i-edit"/></svg> Edit Profile</a>
<?= $this->endSection() ?>

