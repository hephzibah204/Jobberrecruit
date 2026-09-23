<?php $page_title = 'Settings'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>
<div class="page-head">
  <div class="page-head-left">
    <h1><svg aria-hidden="true"><use href="#i-cog"/></svg> General Settings</h1>
    <p>Manage your account preferences, security, and data.</p>
  </div>
</div>

<div class="duo" style="align-items:start">

  <!-- Account Info -->
  <div style="display:flex;flex-direction:column;gap:clamp(14px,1.8vw,20px)">

    <section class="card" aria-label="Account information">
      <div class="card-head">
        <span class="card-title"><svg aria-hidden="true"><use href="#i-building"/></svg> Account Information</span>
        <a href="<?= base_url('employer/profile/edit') ?>" class="card-link">Edit <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px 24px">
          <div>
            <div style="font-size:.64rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:3px">Company name</div>
            <div style="font-size:.88rem;font-weight:600;color:var(--brand-deep)"><?= esc($employer->company_name ?? '—') ?></div>
          </div>
          <div>
            <div style="font-size:.64rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:3px">Contact email</div>
            <div style="font-size:.88rem;font-weight:600;color:var(--brand-deep)"><?= esc($user->email ?? '—') ?></div>
          </div>
          <div>
            <div style="font-size:.64rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:3px">Account type</div>
            <div style="font-size:.88rem;font-weight:600;color:var(--brand-deep)">Employer</div>
          </div>
          <div>
            <div style="font-size:.64rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:3px">Member since</div>
            <div style="font-size:.88rem;font-weight:600;color:var(--brand-deep)"><?= !empty($user->created_at) ? date('d M Y', strtotime($user->created_at)) : '—' ?></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Settings Quick Links -->
    <section class="card" aria-label="Settings navigation">
      <div class="card-head">
        <span class="card-title"><svg aria-hidden="true"><use href="#i-grid"/></svg> Settings Areas</span>
      </div>
      <div class="card-body" style="padding:0">
        <a href="<?= base_url('employer/profile/edit') ?>" style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);text-decoration:none;transition:var(--transition)" onmouseover="this.style.background='var(--brand-light)'" onmouseout="this.style.background=''">
          <span style="width:40px;height:40px;border-radius:10px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg style="width:18px;height:18px" aria-hidden="true"><use href="#i-building"/></svg></span>
          <div>
            <div style="font-weight:700;font-size:.9rem;color:var(--brand-deep)">Company Profile</div>
            <div style="font-size:.76rem;color:var(--muted)">Update your company information, logo, and description</div>
          </div>
          <svg style="width:15px;height:15px;color:var(--muted);margin-left:auto;flex-shrink:0" aria-hidden="true"><use href="#i-arrow-r"/></svg>
        </a>
        <a href="<?= base_url('employer/settings/security') ?>" style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);text-decoration:none;transition:var(--transition)" onmouseover="this.style.background='var(--brand-light)'" onmouseout="this.style.background=''">
          <span style="width:40px;height:40px;border-radius:10px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg style="width:18px;height:18px" aria-hidden="true"><use href="#i-shield"/></svg></span>
          <div>
            <div style="font-weight:700;font-size:.9rem;color:var(--brand-deep)">Security &amp; Password</div>
            <div style="font-size:.76rem;color:var(--muted)">Change your password and review login security</div>
          </div>
          <svg style="width:15px;height:15px;color:var(--muted);margin-left:auto;flex-shrink:0" aria-hidden="true"><use href="#i-arrow-r"/></svg>
        </a>
        <a href="<?= base_url('employer/notifications') ?>" style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);text-decoration:none;transition:var(--transition)" onmouseover="this.style.background='var(--brand-light)'" onmouseout="this.style.background=''">
          <span style="width:40px;height:40px;border-radius:10px;background:var(--accent-light);color:var(--accent-dark);display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg style="width:18px;height:18px" aria-hidden="true"><use href="#i-bell"/></svg></span>
          <div>
            <div style="font-weight:700;font-size:.9rem;color:var(--brand-deep)">Notifications</div>
            <div style="font-size:.76rem;color:var(--muted)">Manage your email and in-app notification preferences</div>
          </div>
          <svg style="width:15px;height:15px;color:var(--muted);margin-left:auto;flex-shrink:0" aria-hidden="true"><use href="#i-arrow-r"/></svg>
        </a>
        <a href="<?= base_url('employer/pricing') ?>" style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);text-decoration:none;transition:var(--transition)" onmouseover="this.style.background='var(--brand-light)'" onmouseout="this.style.background=''">
          <span style="width:40px;height:40px;border-radius:10px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg style="width:18px;height:18px" aria-hidden="true"><use href="#i-card"/></svg></span>
          <div>
            <div style="font-weight:700;font-size:.9rem;color:var(--brand-deep)">Billing &amp; Plans</div>
            <div style="font-size:.76rem;color:var(--muted)">View your subscription plan and manage billing</div>
          </div>
          <svg style="width:15px;height:15px;color:var(--muted);margin-left:auto;flex-shrink:0" aria-hidden="true"><use href="#i-arrow-r"/></svg>
        </a>
        <a href="<?= base_url('employer/settings/export-data') ?>" style="display:flex;align-items:center;gap:14px;padding:16px 20px;text-decoration:none;transition:var(--transition)" onmouseover="this.style.background='var(--brand-light)'" onmouseout="this.style.background=''">
          <span style="width:40px;height:40px;border-radius:10px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg style="width:18px;height:18px" aria-hidden="true"><use href="#i-download"/></svg></span>
          <div>
            <div style="font-weight:700;font-size:.9rem;color:var(--brand-deep)">Export My Data</div>
            <div style="font-size:.76rem;color:var(--muted)">Download a copy of your account data (GDPR)</div>
          </div>
          <svg style="width:15px;height:15px;color:var(--muted);margin-left:auto;flex-shrink:0" aria-hidden="true"><use href="#i-arrow-r"/></svg>
        </a>
      </div>
    </section>

  </div>

  <!-- Danger Zone -->
  <div style="display:flex;flex-direction:column;gap:clamp(14px,1.8vw,20px)">

    <section class="card" aria-label="Account status">
      <div class="card-head">
        <span class="card-title"><svg aria-hidden="true"><use href="#i-user-check"/></svg> Account Status</span>
      </div>
      <div class="card-body">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
          <span class="pill pill--hired" style="font-size:.74rem">
            <svg aria-hidden="true"><use href="#i-check-c"/></svg> Active
          </span>
          <?php if (!empty($employer->is_verified)): ?>
            <span class="pill pill--reviewed" style="font-size:.74rem">
              <svg aria-hidden="true"><use href="#i-shield"/></svg> Verified
            </span>
          <?php endif; ?>
        </div>
        <p style="font-size:.8rem;color:var(--muted);line-height:1.6">
          Your employer account is active. You can post jobs, review applications, and access all recruiter features.
        </p>
      </div>
    </section>

    <section class="card" aria-label="Danger zone" style="border-color:var(--danger-light)">
      <div class="card-head" style="background:var(--danger-light)">
        <span class="card-title" style="color:var(--danger)"><svg aria-hidden="true"><use href="#i-x"/></svg> Danger Zone</span>
      </div>
      <div class="card-body">
        <p style="font-size:.82rem;color:var(--muted);margin-bottom:16px">
          Closing your account will remove all your job postings and associated data. This action is irreversible.
        </p>
        <a href="<?= base_url('employer/settings/security') ?>" class="emp-btn emp-btn-sm" style="background:#fff;color:var(--danger);border:1.5px solid var(--border);">
          <svg aria-hidden="true"><use href="#i-shield"/></svg> Manage Account Security
        </a>
      </div>
    </section>

  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/profile/edit') ?>" class="emp-btn emp-btn-outline">
    <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-building"/></svg> Company Profile
</a>
<a href="<?= base_url('employer/settings/security') ?>" class="emp-btn emp-btn-accent">
    <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-shield"/></svg> Security Settings
</a>
<?= $this->endSection() ?>
