<?php $page_title = 'Company Profile'; ?>
<?= $this->extend('layouts/employer') ?>

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

<div class="page-head">
  <div class="page-head-left">
    <h1><svg aria-hidden="true"><use href="#i-building"/></svg> Company Profile</h1>
    <p>This information appears on your job listings and public employer page.</p>
  </div>
  <div class="page-actions">
    <a href="<?= base_url('employer/' . esc($employer->user_id ?? '')) ?>" class="emp-btn emp-btn-outline emp-btn-sm"><svg aria-hidden="true"><use href="#i-eye"/></svg> View Public Profile</a>
    <a href="<?= base_url('employer/profile/edit') ?>" class="emp-btn emp-btn-primary emp-btn-sm"><svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Profile</a>
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
          <span class="id-logo-text"><?= esc($initials) ?></span>
        <?php endif; ?>
      </div>
      <div class="id-name"><?= esc($displayName) ?></div>
      <div class="id-mail"><?= esc($employer->contact_email ?? '') ?></div>
      <?php if ($employer->is_verified ?? false): ?>
        <span class="badge-verified"><svg aria-hidden="true"><use href="#i-shield"/></svg> Verified Employer</span>
      <?php endif; ?>
      <div class="id-actions">
        <a href="<?= base_url('employer/profile/edit') ?>" class="emp-btn emp-btn-outline emp-btn-sm emp-btn-block"><svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Profile</a>
      </div>
    </section>

    <!-- Profile completion -->
    <section class="card" aria-label="Profile completion">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Profile Completion</span></div>
      <div class="card-body">
        <div class="pf">
          <div class="pf-ring" role="img" aria-label="Profile <?= esc($pct) ?> percent complete">
            <svg viewBox="0 0 88 88" aria-hidden="true">
              <circle class="track" cx="44" cy="44" r="38"/>
              <circle class="prog" cx="44" cy="44" r="38" style="stroke-dashoffset: <?= $dashoffset ?>;"/>
            </svg>
            <span class="pct"><?= esc($pct) ?>%</span>
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
      <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-star"/></svg> Account Summary</span></div>
      <div class="card-body">
        <div class="plan-panel">
          <?php if ($hasUnlimitedAccess ?? false): ?>
            <b>Unlimited Access Plan</b>
            <p>Enterprise account with unlimited job postings</p>
            <?php if (!empty($employer->unlimited_until)): ?>
              <p style="font-size:.7rem;margin-top:-6px;margin-bottom:8px;opacity:0.85;">Valid until: <?= date('M d, Y', strtotime($employer->unlimited_until)) ?></p>
            <?php endif; ?>
            <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm">Manage Plan</a>
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
              <p style="font-size:.7rem;margin-top:-6px;margin-bottom:8px;opacity:0.85;">Expires: <?= date('M d, Y', strtotime($planEndsAt)) ?></p>
              <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm">Renew / Upgrade</a>
            <?php else: ?>
              <b>No Active Plan</b>
              <p>Job Credits: <?= number_format($creditBalance ?? 0) ?></p>
              <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm">Get a Plan</a>
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
        <span class="card-title"><svg aria-hidden="true"><use href="#i-building"/></svg> Company Information</span>
        <a href="<?= base_url('employer/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
      </div>
      <div class="card-body">
        <div class="info-grid">
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-building"/></svg> Company name</div><div class="info-val"><?= esc($employer->company_name ?? 'Not Set') ?></div></div>
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Industries</div>
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
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-users"/></svg> Company size</div><div class="info-val"><?= esc($employer->company_size ?? 'Not Set') ?></div></div>
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-link"/></svg> Website</div>
            <div class="info-val">
              <?php if (!empty($employer->website)): ?>
                <a href="<?= esc($employer->website) ?>" target="_blank" rel="noopener"><?= esc($employer->website) ?></a>
              <?php else: ?>
                Not Set
              <?php endif; ?>
            </div>
          </div>
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-calendar"/></svg> State / Location</div><div class="info-val"><?= esc($employer->location ? $employer->location . ' State' : 'Not Set') ?></div></div>
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-doc"/></svg> User ID reference</div><div class="info-val"><?= esc($employer->user_id ?? '') ?></div></div>
          <div class="info-desc">
            <div class="info-lbl" style="margin-bottom:6px"><svg aria-hidden="true"><use href="#i-note"/></svg> Company description</div>
            <?= !empty($employer->description) ? nl2br(esc($employer->description)) : 'No description provided.' ?>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact info -->
    <section class="card" aria-label="Contact information">
      <div class="card-head">
        <span class="card-title"><svg aria-hidden="true"><use href="#i-mail"/></svg> Contact Information</span>
        <a href="<?= base_url('employer/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
      </div>
      <div class="card-body">
        <div class="info-grid">
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-users"/></svg> Contact person</div><div class="info-val"><?= esc($employer->contact_name ?? 'Not Set') ?></div></div>
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-mail"/></svg> Contact email</div>
            <div class="info-val">
              <?php if (!empty($employer->contact_email)): ?>
                <a href="mailto:<?= esc($employer->contact_email) ?>"><?= esc($employer->contact_email) ?></a>
              <?php else: ?>
                Not Set
              <?php endif; ?>
            </div>
          </div>
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-phone"/></svg> Phone number</div>
            <div class="info-val">
              <?php if (!empty($employer->contact_phone)): ?>
                <a href="tel:<?= esc($employer->contact_phone) ?>"><?= esc($employer->contact_phone) ?></a>
              <?php else: ?>
                Not Set
              <?php endif; ?>
            </div>
          </div>
          <div><div class="info-lbl"><svg aria-hidden="true"><use href="#i-building"/></svg> Physical address</div><div class="info-val"><?= esc($employer->company_address ?? 'Not Set') ?></div></div>
        </div>
      </div>
    </section>

    <!-- CAC verification -->
    <section class="card" aria-label="CAC certificate verification">
      <div class="card-head">
        <span class="card-title"><svg aria-hidden="true"><use href="#i-shield"/></svg> CAC Certificate Verification</span>
        <?php if ($cacStatus === 'approved'): ?>
          <span class="pill pill--reviewed"><svg aria-hidden="true"><use href="#i-check"/></svg> Approved</span>
        <?php elseif ($cacStatus === 'pending'): ?>
          <span class="pill pill--pending"><svg aria-hidden="true"><use href="#i-clock"/></svg> Under Review</span>
        <?php elseif ($cacStatus === 'rejected'): ?>
          <span class="pill pill--rejected"><svg aria-hidden="true"><use href="#i-x"/></svg> Rejected</span>
        <?php else: ?>
          <span class="pill pill--closed"><svg aria-hidden="true"><use href="#i-x"/></svg> Missing</span>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <div class="cac-row">
          <?php if (!empty($cacFilePath)): ?>
            <a href="<?= base_url(esc($cacFilePath)) ?>" target="_blank" class="emp-btn emp-btn-outline emp-btn-sm"><svg aria-hidden="true"><use href="#i-doc"/></svg> View Uploaded CAC Certificate</a>
          <?php endif; ?>
          <a href="<?= base_url('employer/profile/upload-document') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
            <svg aria-hidden="true"><use href="#i-refresh"/></svg> <?= empty($cacStatus) ? 'Upload Document' : 'Replace Document' ?>
          </a>
        </div>
        <?php if ($cacStatus === 'approved'): ?>
          <p style="font-size:.78rem;color:var(--muted);margin-top:12px">Your CAC business registration certificate is verified. Verification is mandatory for the Verified Employer badge and premium postings.</p>
        <?php elseif ($cacStatus === 'pending'): ?>
          <p style="font-size:.78rem;color:var(--muted);margin-top:12px">Your CAC business registration certificate is currently under review by our admin team.</p>
        <?php elseif ($cacStatus === 'rejected'): ?>
          <p style="font-size:.78rem;color:var(--muted);margin-top:12px">Your CAC document was rejected. Please upload a valid CAC certificate.
            <?php if (!empty($employer->rejection_reason)): ?>
              <br><strong class="text-danger">Reason:</strong> <?= esc($employer->rejection_reason) ?>
            <?php endif; ?>
          </p>
        <?php else: ?>
          <p style="font-size:.78rem;color:var(--muted);margin-top:12px">Upload your CAC business registration certificate to get verified. Verification is mandatory for the Verified Employer badge and premium postings.</p>
        <?php endif; ?>
      </div>
    </section>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/' . esc($employer->user_id ?? '')) ?>" class="emp-btn emp-btn-outline emp-btn-sm"><svg aria-hidden="true"><use href="#i-eye"/></svg> View Public Profile</a>
<a href="<?= base_url('employer/profile/edit') ?>" class="emp-btn emp-btn-primary emp-btn-sm"><svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Profile</a>
<?= $this->endSection() ?>

