<?php $page_title = 'Job Details'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head-left">
    <h1><svg aria-hidden="true"><use href="#i-briefcase"/></svg> <?= esc($job->title) ?></h1>
    <p>Manage and track applications for this job position.</p>
  </div>
  <div class="page-actions">
    <a href="<?= site_url('employer/jobs') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to Jobs
    </a>
    <a href="<?= site_url("employer/jobs/edit/{$job->id}") ?>" class="emp-btn emp-btn-primary emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Job
    </a>
  </div>
</div>

<!-- Alerts & Credit Balance -->
<div class="notice notice--info" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
  <div style="display: flex; gap: 8px; align-items: center;">
    <svg aria-hidden="true"><use href="#i-wallet"/></svg>
    <span><strong>Available Job Credits:</strong> <?= number_format($creditBalance ?? 0, 0) ?></span>
  </div>
  <a href="<?= base_url('employer/bundles') ?>" class="emp-btn emp-btn-primary emp-btn-sm" style="min-height: auto; padding: 6px 12px;">
    Buy Credits
  </a>
</div>

<div class="job-detail-grid">
  <!-- Main Job details & applications -->
  <div class="main-column">
    
    <!-- Job Content Details -->
    <div class="card detail-card">
      <div class="card-head">
        <h2 class="card-title"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Job Information</h2>
      </div>
      <div class="card-body">
        <div style="margin-bottom: 20px;">
          <h3 style="font-size: 1.15rem; color: var(--brand-deep); font-weight: 700; margin-bottom: 6px;"><?= esc($job->title) ?></h3>
          <p style="color: var(--muted); font-size: 0.88rem; display: flex; gap: 12px; flex-wrap: wrap;">
            <span><strong style="color: var(--text);">Category:</strong> <?= esc($job->category_name ?? 'N/A') ?></span>
            <span>•</span>
            <span><strong style="color: var(--text);">Industry:</strong> <?= esc($job->industry_name ?? 'N/A') ?></span>
          </p>
        </div>

        <div style="margin-bottom: 20px;">
          <h4 style="font-size: 0.94rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 6px;">Job Description</h4>
          <div style="font-size: 0.88rem; color: var(--text); line-height: 1.6; white-space: pre-line;">
            <?= esc($job->description) ?>
          </div>
        </div>

        <?php if (!empty($job->requirements)): ?>
          <div style="margin-bottom: 20px;">
            <h4 style="font-size: 0.94rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 6px;">Requirements</h4>
            <div style="font-size: 0.88rem; color: var(--text); line-height: 1.6; white-space: pre-line;">
              <?= esc($job->requirements) ?>
            </div>
          </div>
        <?php endif; ?>

        <?php if (!empty($job->skills)): ?>
          <div style="margin-bottom: 10px;">
            <h4 style="font-size: 0.94rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 8px;">Required Skills</h4>
            <div class="chips">
              <?php foreach (explode(',', $job->skills) as $skill): ?>
                <?php if (trim($skill)): ?>
                  <span class="chip"><?= esc(trim($skill)) ?></span>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Applications Table -->
    <div class="card">
      <div class="card-head">
        <h2 class="card-title"><svg aria-hidden="true"><use href="#i-users"/></svg> Applications (<?= count($applications ?? []) ?>)</h2>
      </div>
      <div class="tbl-wrap">
        <table class="tbl tbl--apps">
          <thead>
            <tr>
              <th>Applicant</th>
              <th>Phone</th>
              <th>Applied On</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($applications)): ?>
              <tr>
                <td colspan="5" class="no-lbl">
                  <div class="empty">
                    <div class="empty-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div>
                    <h3>No applications yet</h3>
                    <p>No candidates have applied for this job posting yet.</p>
                  </div>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($applications as $app): ?>
                <?php
                $fullName = $app->fullname ?? 'Candidate';
                $initials = '';
                $nameParts = explode(' ', trim($fullName));
                if (count($nameParts) >= 2) {
                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                } else {
                    $initials = strtoupper(substr($fullName, 0, 2));
                }

                // Map status classes
                $statusClass = 'pill--pending';
                $statusLower = strtolower(trim($app->status ?? 'pending'));
                if ($statusLower === 'reviewed') {
                    $statusClass = 'pill--reviewed';
                } elseif ($statusLower === 'shortlisted') {
                    $statusClass = 'pill--shortlisted';
                } elseif ($statusLower === 'hired' || $statusLower === 'open' || $statusLower === 'active' || $statusLower === 'success') {
                    $statusClass = 'pill--hired';
                } elseif ($statusLower === 'rejected' || $statusLower === 'closed' || $statusLower === 'expired') {
                    $statusClass = 'pill--rejected';
                }
                ?>
                <tr>
                  <td class="no-lbl">
                    <div class="appl-cell">
                      <span class="ava ava--round" aria-hidden="true"><?= esc($initials) ?></span>
                      <div style="min-width:0">
                        <div class="appl-name"><?= esc($fullName) ?></div>
                        <div class="appl-mail"><?= esc($app->email ?? 'N/A') ?></div>
                      </div>
                    </div>
                  </td>
                  <td data-lbl="Phone"><?= esc($app->phone ?? 'N/A') ?></td>
                  <td data-lbl="Applied"><?= date('d M Y', strtotime($app->created_at)) ?></td>
                  <td data-lbl="Status">
                    <span class="pill <?= $statusClass ?>"><?= esc(ucfirst($app->status ?? 'Pending')) ?></span>
                  </td>
                  <td data-lbl="Actions">
                    <div class="row-actions">
                      <a class="ic-btn" href="<?= site_url('employer/applications/view/' . $app->id) ?>" aria-label="View application" title="View Application">
                        <svg aria-hidden="true"><use href="#i-eye"/></svg>
                      </a>
                      <?php if (!empty($app->cv_path)): ?>
                        <a class="ic-btn" href="<?= base_url($app->cv_path) ?>" download aria-label="Download CV" title="Download CV">
                          <svg aria-hidden="true"><use href="#i-download"/></svg>
                        </a>
                      <?php endif; ?>
                      <button class="ic-btn ic-btn--danger delete-single-btn" data-id="<?= $app->id ?>" aria-label="Delete application" title="Delete">
                        <svg aria-hidden="true"><use href="#i-trash"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <!-- Sidebar Column (Job stats & configs) -->
  <div class="side-column">
    
    <!-- Quick Stats -->
    <div class="card detail-card">
      <div class="card-head">
        <h2 class="card-title"><svg aria-hidden="true"><use href="#i-grid"/></svg> Stats &amp; Actions</h2>
      </div>
      <div class="card-body">
        <div class="detail-item">
          <span class="detail-label">Status</span>
          <span class="detail-val">
            <?php
            $jobStatus = strtolower($job->status);
            $jobPill = 'pill--closed';
            if ($jobStatus === 'active' || $jobStatus === 'open') {
                $jobPill = 'pill--open';
            } elseif ($jobStatus === 'pending') {
                $jobPill = 'pill--pending';
            }
            ?>
            <span class="pill <?= $jobPill ?>"><?= esc(ucfirst($job->status)) ?></span>
          </span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Views</span>
          <span class="detail-val"><?= number_format($job->views ?? 0) ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Clicks</span>
          <span class="detail-val"><?= number_format($totalClicks ?? 0) ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Applicants</span>
          <span class="detail-val"><?= count($applications ?? []) ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Posted Date</span>
          <span class="detail-val"><?= date('d M Y', strtotime($job->created_at)) ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Deadline</span>
          <span class="detail-val">
            <?= (!empty($job->application_deadline) && $job->application_deadline !== '0000-00-00') ? date('d M Y', strtotime($job->application_deadline)) : 'Open ended' ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Job Features & Promotion -->
    <div class="card detail-card">
      <div class="card-head">
        <h2 class="card-title"><svg aria-hidden="true"><use href="#i-star"/></svg> Job Features</h2>
      </div>
      <div class="card-body">
        <div class="detail-item">
          <span class="detail-label">Featured Post</span>
          <span class="detail-val">
            <?php if ($job->is_featured && strtotime($job->featured_until ?? '') > time()): ?>
              <span class="pill pill--open">Active</span>
            <?php else: ?>
              <span class="pill pill--closed">Inactive</span>
            <?php endif; ?>
          </span>
        </div>

        <div class="detail-item">
          <span class="detail-label">Anonymous</span>
          <span class="detail-val">
            <?php if ($job->is_anonymous): ?>
              <span class="pill pill--reviewed">Yes</span>
            <?php else: ?>
              <span class="pill pill--closed">No</span>
            <?php endif; ?>
          </span>
        </div>

        <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 8px;">
          <?php if (!$job->is_featured || strtotime($job->featured_until ?? '') <= time()): ?>
            <button class="emp-btn emp-btn-accent emp-btn-block btn-promote"
                    data-id="<?= $job->id ?>"
                    data-title="<?= esc($job->title, 'attr') ?>">
              <svg aria-hidden="true"><use href="#i-star"/></svg> Promote Job
            </button>
          <?php else: ?>
            <div style="font-size: 0.72rem; color: var(--muted); margin-bottom: 4px; text-align: center;">
              Featured until: <strong><?= date('M d, Y', strtotime($job->featured_until)) ?></strong>
            </div>
            <button class="emp-btn emp-btn-outline emp-btn-danger emp-btn-block btn-stop-featured"
                    data-id="<?= $job->id ?>"
                    data-title="<?= esc($job->title, 'attr') ?>">
              <svg aria-hidden="true"><use href="#i-trash"/></svg> Stop Featuring
            </button>
          <?php endif; ?>

          <button class="emp-btn emp-btn-outline emp-btn-block btn-toggle-anonymous"
                  data-id="<?= $job->id ?>"
                  data-title="<?= esc($job->title, 'attr') ?>"
                  data-status="<?= $job->is_anonymous ? 'on' : 'off' ?>">
            <svg aria-hidden="true"><use href="#i-eye"/></svg> <?= $job->is_anonymous ? 'Disable Anonymous' : 'Enable Anonymous' ?>
          </button>
        </div>
      </div>
    </div>

    <!-- Specifications -->
    <div class="card detail-card">
      <div class="card-head">
        <h2 class="card-title"><svg aria-hidden="true"><use href="#i-cog"/></svg> Specifications</h2>
      </div>
      <div class="card-body">
        <div class="detail-item">
          <span class="detail-label">Job Type</span>
          <span class="detail-val"><?= esc(ucfirst($job->job_type)) ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Location Type</span>
          <span class="detail-val"><?= esc(ucfirst(str_replace('_', ' ', $job->location_type))) ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Location</span>
          <span class="detail-val"><?= esc($job->location ?? 'N/A') ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Experience</span>
          <span class="detail-val"><?= esc(ucfirst($job->experience_level)) ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Education</span>
          <span class="detail-val"><?= esc(ucfirst(str_replace('_', ' ', $job->education_level))) ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Salary Period</span>
          <span class="detail-val"><?= esc(ucfirst($job->salary_period)) ?></span>
        </div>
        <div class="detail-item" style="border-bottom: none;">
          <span class="detail-label">Salary</span>
          <span class="detail-val fw-semibold"><?= $job->salary ? esc($job->salary) : 'Not specified' ?></span>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- ==================== MODALS ==================== -->

<!-- Promote Modal -->
<div class="modal" id="promoteModal" role="dialog" aria-modal="true" aria-labelledby="promote-title">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="promote-title" style="font-size:1.1rem; font-weight:700; color:var(--brand-deep); margin:0;">Promote Job as Featured</h3>
      <button class="close-modal-btn" style="background:none; border:none; cursor:pointer; color:var(--muted);"><svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg></button>
    </div>
    <div class="modal-body" style="text-align: center; color: var(--muted); font-size: .88rem; line-height: 1.5;">
      <svg aria-hidden="true" style="width: 48px; height: 48px; color: var(--accent); margin: 0 auto 12px; display: block;"><use href="#i-star"/></svg>
      <h4 style="color: var(--brand-deep); font-weight: 700; margin-bottom: 8px;">Promote "<span id="promoteJobTitle"></span>"?</h4>
      <p>This job will be highlighted as <strong>Featured</strong> for 30 days. It will appear at the top of search results and gain more visibility.</p>
      <div style="color: var(--danger); font-weight: 600; margin-top: 10px; font-size: 0.8rem;">
        This action will deduct <strong>5 Job Credits</strong>.
      </div>
    </div>
    <div class="modal-footer">
      <button class="emp-btn emp-btn-outline emp-btn-sm close-modal-btn">Cancel</button>
      <button class="emp-btn emp-btn-accent emp-btn-sm" id="confirmPromoteBtn">Yes, Promote Job</button>
    </div>
  </div>
</div>

<!-- Stop Featured Modal -->
<div class="modal" id="stopFeaturedModal" role="dialog" aria-modal="true" aria-labelledby="stop-featured-title">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="stop-featured-title" style="font-size:1.1rem; font-weight:700; color:var(--brand-deep); margin:0;">Stop Featuring Job</h3>
      <button class="close-modal-btn" style="background:none; border:none; cursor:pointer; color:var(--muted);"><svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg></button>
    </div>
    <div class="modal-body" style="text-align: center; color: var(--muted); font-size: .88rem; line-height: 1.5;">
      <svg aria-hidden="true" style="width: 48px; height: 48px; color: var(--danger); margin: 0 auto 12px; display: block;"><use href="#i-trash"/></svg>
      <h4 style="color: var(--brand-deep); font-weight: 700; margin-bottom: 8px;">Stop featuring "<span id="stopFeaturedJobTitle"></span>"?</h4>
      <p>This job will immediately lose its featured visibility. <br><strong>Job credits will not be refunded.</strong></p>
    </div>
    <div class="modal-footer">
      <button class="emp-btn emp-btn-outline emp-btn-sm close-modal-btn">Cancel</button>
      <button class="emp-btn emp-btn-danger emp-btn-sm" id="confirmStopFeaturedBtn">Yes, Stop Featuring</button>
    </div>
  </div>
</div>

<!-- Anonymous Modal -->
<div class="modal" id="anonymousModal" role="dialog" aria-modal="true" aria-labelledby="anon-title">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="anon-title" style="font-size:1.1rem; font-weight:700; color:var(--brand-deep); margin:0;">Anonymous Job Posting</h3>
      <button class="close-modal-btn" style="background:none; border:none; cursor:pointer; color:var(--muted);"><svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg></button>
    </div>
    <div class="modal-body" style="text-align: center; color: var(--muted); font-size: .88rem; line-height: 1.5;">
      <svg aria-hidden="true" style="width: 48px; height: 48px; color: var(--brand); margin: 0 auto 12px; display: block;"><use href="#i-eye"/></svg>
      <h4 style="color: var(--brand-deep); font-weight: 700; margin-bottom: 8px;" id="anonymousModalTitle">Toggle Anonymous Status</h4>
      <p id="anonymousModalText"></p>
      <div style="color: var(--danger); font-weight: 600; margin-top: 10px; font-size: 0.8rem;" id="anonymousCostNote" class="d-none">
        This action will deduct <strong>5 Job Credits</strong>.
      </div>
    </div>
    <div class="modal-footer">
      <button class="emp-btn emp-btn-outline emp-btn-sm close-modal-btn">Cancel</button>
      <button class="emp-btn emp-btn-primary emp-btn-sm" id="confirmAnonymousBtn">Confirm</button>
    </div>
  </div>
</div>

<!-- Delete Application Modal -->
<div class="modal" id="delete-modal" role="dialog" aria-modal="true" aria-labelledby="del-title">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="del-title" style="font-size:1.1rem; font-weight:700; color:var(--brand-deep); margin:0;">Delete Application</h3>
      <button class="close-modal-btn" style="background:none; border:none; cursor:pointer; color:var(--muted);"><svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg></button>
    </div>
    <div class="modal-body" style="color:var(--muted); font-size:.88rem; line-height:1.5;">
      Are you sure you want to delete this application? This action cannot be undone.
    </div>
    <div class="modal-footer">
      <button class="emp-btn emp-btn-outline emp-btn-sm close-modal-btn">Cancel</button>
      <button class="emp-btn emp-btn-danger emp-btn-sm" id="confirm-delete-btn">Delete</button>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= site_url("employer/jobs/edit/{$job->id}") ?>" class="emp-btn emp-btn-primary">
  <svg aria-hidden="true"><use href="#i-edit"/></svg> Edit Job
</a>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Modal handlers
  function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('show');
  }
  
  function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove('show');
  }

  document.querySelectorAll('.close-modal-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const modal = this.closest('.modal');
      if (modal) modal.classList.remove('show');
    });
  });

  // Promote job click
  let jobToPromote = null;
  document.querySelectorAll('.btn-promote').forEach(btn => {
    btn.addEventListener('click', function() {
      jobToPromote = {
        id: this.dataset.id,
        title: this.dataset.title
      };
      document.getElementById('promoteJobTitle').textContent = jobToPromote.title;
      showModal('promoteModal');
    });
  });

  document.getElementById('confirmPromoteBtn')?.addEventListener('click', function() {
    if (!jobToPromote) return;
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Processing...';

    fetch('<?= site_url('employer/jobs/promote') ?>/' + jobToPromote.id, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        toastr.error(res.message || 'Failed to promote job.');
        btn.disabled = false;
        btn.textContent = 'Yes, Promote Job';
      }
    })
    .catch(() => {
      toastr.error('Network error. Please try again.');
      btn.disabled = false;
      btn.textContent = 'Yes, Promote Job';
    });
  });

  // Stop featured click
  let jobToStopFeature = null;
  document.querySelectorAll('.btn-stop-featured').forEach(btn => {
    btn.addEventListener('click', function() {
      jobToStopFeature = {
        id: this.dataset.id,
        title: this.dataset.title
      };
      document.getElementById('stopFeaturedJobTitle').textContent = jobToStopFeature.title;
      showModal('stopFeaturedModal');
    });
  });

  document.getElementById('confirmStopFeaturedBtn')?.addEventListener('click', function() {
    if (!jobToStopFeature) return;
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Processing...';

    fetch('<?= site_url('employer/jobs/stop-featured') ?>/' + jobToStopFeature.id, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        toastr.error(res.message || 'Failed to stop featuring job.');
        btn.disabled = false;
        btn.textContent = 'Yes, Stop Featuring';
      }
    })
    .catch(() => {
      toastr.error('Network error. Please try again.');
      btn.disabled = false;
      btn.textContent = 'Yes, Stop Featuring';
    });
  });

  // Anonymous Toggle
  let jobToToggleAnonymous = null;
  document.querySelectorAll('.btn-toggle-anonymous').forEach(btn => {
    btn.addEventListener('click', function() {
      jobToToggleAnonymous = {
        id: this.dataset.id,
        title: this.dataset.title,
        status: this.dataset.status
      };
      const enabling = jobToToggleAnonymous.status === 'off';
      document.getElementById('anonymousModalTitle').textContent = enabling ? 
        `Make "${jobToToggleAnonymous.title}" Anonymous?` : 
        `Disable Anonymous for "${jobToToggleAnonymous.title}"?`;
      document.getElementById('anonymousModalText').textContent = enabling ? 
        'Your company name and logo will be hidden from candidates.' : 
        'Your company name and logo will be visible again.';
      
      const costNote = document.getElementById('anonymousCostNote');
      if (enabling) {
        costNote.classList.remove('d-none');
      } else {
        costNote.classList.add('d-none');
      }
      showModal('anonymousModal');
    });
  });

  document.getElementById('confirmAnonymousBtn')?.addEventListener('click', function() {
    if (!jobToToggleAnonymous) return;
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Processing...';

    fetch('<?= site_url('employer/jobs/toggle-anonymous') ?>/' + jobToToggleAnonymous.id, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        toastr.error(res.message || 'Failed to update anonymous status.');
        btn.disabled = false;
        btn.textContent = 'Confirm';
      }
    })
    .catch(() => {
      toastr.error('Network error. Please try again.');
      btn.disabled = false;
      btn.textContent = 'Confirm';
    });
  });

  // Delete Single Application
  let appToDelete = null;
  document.querySelectorAll('.delete-single-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      appToDelete = this.dataset.id;
      showModal('delete-modal');
    });
  });

  document.getElementById('confirm-delete-btn')?.addEventListener('click', function() {
    if (!appToDelete) return;
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Deleting...';

    fetch('<?= site_url('employer/applications/delete') ?>/' + appToDelete, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        toastr.error(res.message || 'Failed to delete application.');
        btn.disabled = false;
        btn.textContent = 'Delete';
      }
    })
    .catch(() => {
      toastr.error('Network error. Please try again.');
      btn.disabled = false;
      btn.textContent = 'Delete';
    });
  });
});
</script>
<?= $this->endSection() ?>