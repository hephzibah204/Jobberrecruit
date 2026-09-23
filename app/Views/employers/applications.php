<?php $page_title = 'Applications'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div>
    <h1><svg aria-hidden="true"><use href="#i-doc"/></svg> Applications</h1>
    <p>View and manage applications for your job postings.</p>
  </div>
  <div class="page-actions">
    <button class="emp-btn emp-btn-outline emp-btn-sm" onclick="location.reload();" aria-label="Refresh list">
      <svg aria-hidden="true"><use href="#i-refresh"/></svg> Refresh
    </button>
    <a href="<?= site_url('employer/applications/export') ?>" class="emp-btn emp-btn-primary emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-download"/></svg> Export CSV
    </a>
  </div>
</div>

<!-- Dynamic stats block -->
<section class="stats stats--apps" aria-label="Application statistics">
  <div class="stat">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></span></div>
    <div class="stat-num" id="stat-total"><?= esc($stats['total'] ?? count($applications)) ?></div>
    <div class="stat-lbl">Total</div>
  </div>
  <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-clock"/></svg></span></div>
    <div class="stat-num" id="stat-pending"><?= esc($stats['pending'] ?? 0) ?></div>
    <div class="stat-lbl">Pending</div>
  </div>
  <div class="stat">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-eye"/></svg></span></div>
    <div class="stat-num" id="stat-reviewed"><?= esc($stats['reviewed'] ?? 0) ?></div>
    <div class="stat-lbl">Reviewed</div>
  </div>
  <div class="stat" style="--st-bar:var(--brand-dark)">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-star"/></svg></span></div>
    <div class="stat-num" id="stat-shortlisted"><?= esc($stats['shortlisted'] ?? 0) ?></div>
    <div class="stat-lbl">Shortlisted</div>
  </div>
  <div class="stat" style="--st-bar:var(--danger);--st-icbg:var(--danger-light);--st-ic:var(--danger)">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-x"/></svg></span></div>
    <div class="stat-num" id="stat-rejected"><?= esc($stats['rejected'] ?? 0) ?></div>
    <div class="stat-lbl">Rejected</div>
  </div>
  <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-user-check"/></svg></span></div>
    <div class="stat-num" id="stat-hired"><?= esc($stats['hired'] ?? 0) ?></div>
    <div class="stat-lbl">Hired</div>
  </div>
</section>

<!-- Filter Toolbar & Table -->
<section class="card" aria-label="Applications list">
  <div class="card-head">
    <div class="toolbar" style="flex:1">
      <div class="search-wrap">
        <svg aria-hidden="true"><use href="#i-search"/></svg>
        <input class="input" id="app-search" type="search" placeholder="Search applications…" aria-label="Search applications">
      </div>
      
      <select class="select" id="filter-job" aria-label="Filter by job">
        <option value="all">All jobs</option>
        <?php foreach ($jobs as $job): ?>
          <option value="job-<?= $job->id ?>"><?= esc($job->title) ?> (<?= $job->application_count ?>)</option>
        <?php endforeach; ?>
      </select>

      <select class="select" id="filter-status" aria-label="Filter by status">
        <option value="all">All statuses</option>
        <option value="pending">Pending</option>
        <option value="reviewed">Reviewed</option>
        <option value="shortlisted">Shortlisted</option>
        <option value="rejected">Rejected</option>
        <option value="hired">Hired</option>
      </select>

      <?php if (!empty($applications)): ?>
        <select class="select" id="bulk-actions" aria-label="Bulk actions">
          <option value="">Bulk actions</option>
          <option value="reviewed">Mark as reviewed</option>
          <option value="shortlisted">Shortlist</option>
          <option value="rejected">Reject</option>
          <option value="delete">Delete selected</option>
        </select>
      <?php endif; ?>
    </div>
  </div>

  <div class="tbl-wrap">
    <table class="tbl tbl--apps" id="apps-table">
      <thead>
        <tr>
          <th style="width: 40px; text-align: center; padding-left: 14px;">
            <input type="checkbox" id="select-all" style="cursor: pointer; width: 16px; height: 16px;">
          </th>
          <th>Applicant</th>
          <th>Job Title</th>
          <th>Phone</th>
          <th>Applied On</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($applications)): ?>
          <tr>
            <td colspan="7" class="no-lbl">
              <div class="empty">
                <div class="empty-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div>
                <h3>No applications found</h3>
                <p>You haven't received any applications for your job posts yet.</p>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($applications as $app): ?>
            <?php
            $firstName = $app->first_name ?? '';
            $lastName = $app->last_name ?? '';
            $fullName = trim($firstName . ' ' . $lastName);
            $initials = '';
            if (!empty($firstName) || !empty($lastName)) {
                $initials = strtoupper(substr($firstName, 0, 1) . (strlen($lastName) > 0 ? substr($lastName, 0, 1) : ''));
            } else {
                $initials = 'AP';
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
            <tr data-id="<?= $app->id ?>" data-job="job-<?= $app->job_id ?>" data-status="<?= esc($statusLower) ?>">
              <td class="no-lbl" style="text-align: center; padding-left: 14px;">
                <input type="checkbox" class="app-checkbox" data-id="<?= $app->id ?>" style="cursor: pointer; width: 16px; height: 16px;">
              </td>
              <td class="no-lbl">
                <div class="appl-cell">
                  <span class="ava ava--round" aria-hidden="true"><?= esc($initials) ?></span>
                  <div style="min-width:0">
                    <div class="appl-name">
                      <?= esc($fullName) ?>
                      <?php if (!empty($app->is_guest)): ?>
                        <span class="pill pill--closed" style="font-size: 0.6rem; padding: 2px 6px; margin-left: 4px;">Guest</span>
                      <?php endif; ?>
                    </div>
                    <div class="appl-mail"><?= esc($app->email ?? '') ?></div>
                  </div>
                </div>
              </td>
              <td data-lbl="Job"><?= esc($app->job_title) ?></td>
              <td data-lbl="Phone"><?= esc($app->phone ?? 'N/A') ?></td>
              <td data-lbl="Applied"><?= date('d M Y', strtotime($app->created_at)) ?></td>
              <td data-lbl="Status">
                <span class="pill <?= $statusClass ?>"><?= esc(ucfirst($app->status ?? 'Pending')) ?></span>
              </td>
              <td data-lbl="Actions">
                <div class="row-actions">
                  <button class="ic-btn open-apt-invite-btn" data-id="<?= $app->id ?>" aria-label="Invite to Aptitude Test" title="Invite to Aptitude Test"><svg aria-hidden="true" style="color:#0d6efd;"><use href="#i-bulb"/></svg></button>
                  <a class="ic-btn" href="<?= site_url('employer/applications/view/' . $app->id) ?>" aria-label="View application details" title="View"><svg aria-hidden="true"><use href="#i-eye"/></svg></a>
                  <?php if (!empty($app->cv_path)): ?>
                    <a class="ic-btn" href="<?= base_url($app->cv_path) ?>" download aria-label="Download CV" title="Download CV"><svg aria-hidden="true"><use href="#i-download"/></svg></a>
                  <?php endif; ?>
                  <button class="ic-btn ic-btn--danger delete-single-btn" data-id="<?= $app->id ?>" aria-label="Delete application" title="Delete"><svg aria-hidden="true"><use href="#i-trash"/></svg></button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="pager">
    <span style="display:inline-flex;align-items:center;gap:8px">Per page
      <select class="select" id="per-page" style="min-height:38px;width:auto;font-size:.8rem;padding:6px 32px 6px 12px" aria-label="Entries per page">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
      </select>
    </span>
    <div class="pager-nav" id="pagination-controls">
      <a class="pager-btn" href="#" aria-disabled="true" aria-label="Previous page"><svg style="width:14px;height:14px" aria-hidden="true"><use href="#i-arrow-l"/></svg></a>
      <a class="pager-btn on" href="#" aria-current="page">1</a>
      <a class="pager-btn" href="#" aria-disabled="true" aria-label="Next page"><svg style="width:14px;height:14px" aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
    </div>
  </div>
</section>

<!-- Delete Modal -->
<div class="modal" id="delete-modal" role="dialog" aria-modal="true" aria-labelledby="del-title">
  <div style="background:#fff; border-radius:var(--radius-lg); width:100%; max-width:400px; overflow:hidden; box-shadow:var(--shadow-lg);">
    <div style="padding:18px 20px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
      <h3 id="del-title" style="font-size:1rem; font-weight:700; color:var(--brand-deep); margin:0;">Delete Application</h3>
      <button class="close-modal-btn" style="background:none; border:none; cursor:pointer; color:var(--muted);"><svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg></button>
    </div>
    <div style="padding:20px; color:var(--muted); font-size:.86rem; line-height:1.5;">
      Are you sure you want to delete this application? This action cannot be undone.
    </div>
    <div style="padding:14px 20px; border-top:1px solid var(--border); background:var(--bg); display:flex; justify-content:flex-end; gap:10px;">
      <button class="emp-btn emp-btn-outline emp-btn-sm close-modal-btn">Cancel</button>
      <button class="emp-btn emp-btn-danger emp-btn-sm" id="confirm-delete-btn">Delete</button>
    </div>
  </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal" id="bulk-delete-modal" role="dialog" aria-modal="true" aria-labelledby="bulk-del-title">
  <div style="background:#fff; border-radius:var(--radius-lg); width:100%; max-width:400px; overflow:hidden; box-shadow:var(--shadow-lg);">
    <div style="padding:18px 20px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
      <h3 id="bulk-del-title" style="font-size:1rem; font-weight:700; color:var(--brand-deep); margin:0;">Bulk Delete Applications</h3>
      <button class="close-modal-btn" style="background:none; border:none; cursor:pointer; color:var(--muted);"><svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg></button>
    </div>
    <div style="padding:20px; color:var(--muted); font-size:.86rem; line-height:1.5;">
      Are you sure you want to delete <span id="bulk-count-span">0</span> selected application(s)? This action cannot be undone.
    </div>
    <div style="padding:14px 20px; border-top:1px solid var(--border); background:var(--bg); display:flex; justify-content:flex-end; gap:10px;">
      <button class="emp-btn emp-btn-outline emp-btn-sm close-modal-btn">Cancel</button>
      <button class="emp-btn emp-btn-danger emp-btn-sm" id="confirm-bulk-delete-btn">Delete All</button>
    </div>
  </div>
</div>

<!-- Aptitude Test Invitation Modal -->
<div class="modal" id="aptitude-invite-modal" role="dialog" aria-modal="true" aria-labelledby="apt-invite-title" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:var(--radius-lg, 12px); width:92%; max-width:500px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
    <div style="padding:18px 20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; background:#f8f9fa;">
      <h3 id="apt-invite-title" style="font-size:1.05rem; font-weight:700; color:#0d6efd; margin:0; display:flex; align-items:center; gap:8px;">
        <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg> Invite Candidate to Aptitude Test
      </h3>
      <button class="close-modal-btn" type="button" style="background:none; border:none; cursor:pointer; font-size:1.2rem; color:#6c757d;">&times;</button>
    </div>
    <form id="aptitude-invite-form" style="padding:20px;">
      <input type="hidden" name="application_id" id="invite-app-id" value="">
      
      <div style="margin-bottom:15px;">
        <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.9rem;">Select Aptitude Assessment <span style="color:red">*</span></label>
        <select class="select" name="test_id" id="invite-test-id" required style="width:100%; padding:9px 12px; border-radius:6px; border:1px solid #ccc;">
          <option value="">-- Loading tests... --</option>
        </select>
      </div>

      <div style="margin-bottom:15px;">
        <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.9rem;">Completion Deadline</label>
        <select class="select" name="days_to_complete" id="invite-days" style="width:100%; padding:9px 12px; border-radius:6px; border:1px solid #ccc;">
          <option value="3">3 Days</option>
          <option value="5">5 Days</option>
          <option value="7" selected>7 Days (Standard)</option>
          <option value="14">14 Days</option>
          <option value="30">30 Days</option>
        </select>
      </div>

      <div id="ai-custom-options" style="display: none; background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #e9ecef;">
        <h4 style="font-size: 0.95rem; margin-top: 0; margin-bottom: 12px; color: #495057;">✨ AI Test Settings</h4>
        
        <div style="margin-bottom:12px;">
          <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.85rem;">Number of Questions</label>
          <input type="number" name="num_questions" id="invite-num-questions" class="input" min="3" max="50" value="5" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom:12px;">
          <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.85rem;">Duration (Minutes)</label>
          <input type="number" name="duration_mins" id="invite-duration" class="input" min="5" max="120" value="15" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom:0;">
          <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.85rem;">Difficulty Level</label>
          <select name="difficulty" id="invite-difficulty" class="select" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid #ccc;">
            <option value="beginner">Beginner</option>
            <option value="intermediate" selected>Intermediate</option>
            <option value="advanced">Advanced</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:15px;">
        <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.9rem;">Instructions / Message to Candidate (Optional)</label>
        <textarea class="textarea" name="message" id="invite-message" rows="3" style="width:100%; padding:9px 12px; border-radius:6px; border:1px solid #ccc;" placeholder="e.g. Please complete this assessment before your upcoming interview..."></textarea>
      </div>

      <div style="padding-top:12px; border-top:1px solid #eee; display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" class="emp-btn emp-btn-outline emp-btn-sm close-modal-btn" style="padding:8px 16px;">Cancel</button>
        <button type="submit" class="emp-btn emp-btn-primary emp-btn-sm" id="submit-invite-btn" style="background:#0d6efd; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-weight:600; cursor:pointer;">Send Test Invitation</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '<?= csrf_hash() ?>';
  }

  const searchInput = document.getElementById('app-search');
  const filterJob = document.getElementById('filter-job');
  const filterStatus = document.getElementById('filter-status');
  const bulkActions = document.getElementById('bulk-actions');
  const selectAllCheckbox = document.getElementById('select-all');
  const appCheckboxes = document.querySelectorAll('.app-checkbox');
  const tableRows = document.querySelectorAll('#apps-table tbody tr');

  // Search & Filter Handler
  function filterTable() {
    const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
    const jobFilter = filterJob ? filterJob.value : 'all';
    const statusFilter = filterStatus ? filterStatus.value : 'all';

    tableRows.forEach(row => {
      // Skip empty state row
      if (row.querySelector('.empty')) return;

      const text = row.textContent.toLowerCase();
      const rowJob = row.getAttribute('data-job');
      const rowStatus = row.getAttribute('data-status');

      const matchesSearch = text.includes(searchValue);
      const matchesJob = jobFilter === 'all' || rowJob === jobFilter;
      const matchesStatus = statusFilter === 'all' || rowStatus === statusFilter;

      if (matchesSearch && matchesJob && matchesStatus) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  if (searchInput) searchInput.addEventListener('input', filterTable);
  if (filterJob) filterJob.addEventListener('change', filterTable);
  if (filterStatus) filterStatus.addEventListener('change', filterTable);

  // Select all checkbox functionality
  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
      const visibleCheckboxes = document.querySelectorAll('#apps-table tbody tr:not([style*="display: none"]) .app-checkbox');
      visibleCheckboxes.forEach(cb => {
        cb.checked = selectAllCheckbox.checked;
      });
    });
  }

  // Get selected IDs helper
  function getSelectedIds() {
    const selected = [];
    document.querySelectorAll('.app-checkbox:checked').forEach(cb => {
      selected.push(cb.getAttribute('data-id'));
    });
    return selected;
  }

  // Modal Management Helpers
  function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('show');
  }
  function hideModals() {
    document.querySelectorAll('.modal').forEach(modal => {
      modal.classList.remove('show');
    });
  }

  document.querySelectorAll('.close-modal-btn').forEach(btn => {
    btn.addEventListener('click', hideModals);
  });

  // Single delete configuration
  let singleDeleteId = null;
  document.querySelectorAll('.delete-single-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      singleDeleteId = this.getAttribute('data-id');
      showModal('delete-modal');
    });
  });

  // Confirm Single Delete
  const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
      if (!singleDeleteId) return;
      confirmDeleteBtn.disabled = true;
      confirmDeleteBtn.textContent = 'Deleting...';

      fetch('<?= site_url("employer/applications/delete") ?>/' + singleDeleteId, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: '<?= csrf_token() ?>=' + encodeURIComponent(getCsrfToken())
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          toastr.error(data.message || 'Failed to delete application.');
          confirmDeleteBtn.disabled = false;
          confirmDeleteBtn.textContent = 'Delete';
          hideModals();
        }
      })
      .catch(err => {
        toastr.error('An error occurred. Please try again.');
        confirmDeleteBtn.disabled = false;
        confirmDeleteBtn.textContent = 'Delete';
        hideModals();
      });
    });
  }

  // Bulk actions triggers
  if (bulkActions) {
    bulkActions.addEventListener('change', function() {
      const action = this.value;
      if (!action) return;

      const selectedIds = getSelectedIds();
      if (selectedIds.length === 0) {
        toastr.warning('Please select at least one application.');
        this.value = '';
        return;
      }

      if (action === 'delete') {
        const countSpan = document.getElementById('bulk-count-span');
        if (countSpan) countSpan.textContent = selectedIds.length;
        showModal('bulk-delete-modal');
        this.value = '';
      } else {
        // Bulk status update
        if (confirm(`Update ${selectedIds.length} application(s) status to "${action}"?`)) {
          bulkUpdateStatus(selectedIds, action);
        }
        this.value = '';
      }
    });
  }

  // Bulk update status AJAX
  function bulkUpdateStatus(ids, status) {
    const params = new URLSearchParams();
    ids.forEach(id => params.append('ids[]', id));
    params.append('status', status);
    params.append('<?= csrf_token() ?>', getCsrfToken());

    fetch('<?= site_url("employer/applications/bulk-update-status") ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: params.toString()
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        toastr.error(data.message || 'Failed to update statuses.');
      }
    })
    .catch(err => {
      toastr.error('An error occurred updating statuses.');
    });
  }

  // Confirm Bulk Delete Action
  const confirmBulkDeleteBtn = document.getElementById('confirm-bulk-delete-btn');
  if (confirmBulkDeleteBtn) {
    confirmBulkDeleteBtn.addEventListener('click', function() {
      const selectedIds = getSelectedIds();
      if (selectedIds.length === 0) return;

      confirmBulkDeleteBtn.disabled = true;
      confirmBulkDeleteBtn.textContent = 'Deleting...';

      const params = new URLSearchParams();
      selectedIds.forEach(id => params.append('ids[]', id));
      params.append('<?= csrf_token() ?>', getCsrfToken());

      fetch('<?= site_url("employer/applications/bulk-delete") ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: params.toString()
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          toastr.error(data.message || 'Failed to delete applications.');
          confirmBulkDeleteBtn.disabled = false;
          confirmBulkDeleteBtn.textContent = 'Delete All';
          hideModals();
        }
      })
      .catch(err => {
        toastr.error('An error occurred during deletion.');
        confirmBulkDeleteBtn.disabled = false;
        confirmBulkDeleteBtn.textContent = 'Delete All';
        hideModals();
      });
    });
  }

  // --- Aptitude Test Invitation Handler ---
  const aptModal = document.getElementById('aptitude-invite-modal');
  const aptForm = document.getElementById('aptitude-invite-form');
  const openAptBtns = document.querySelectorAll('.open-apt-invite-btn');

  function openAptModal(appId) {
    if (!aptModal) return;
    document.getElementById('invite-app-id').value = appId;
    
    const testSelect = document.getElementById('invite-test-id');
    testSelect.innerHTML = '<option value="">Loading tests...</option>';

    fetch('<?= site_url("employer/aptitude-tests/list") ?>')
      .then(res => res.json())
      .then(data => {
        if (data.success && data.tests.length) {
          testSelect.innerHTML = '<option value="ai_custom" selected>✨ AI Custom Test (Generated by Gemini AI for this Job)</option>' +
            '<optgroup label="Preset Standard Tests">' +
            data.tests.map(t => `<option value="${t.id}">${t.title} (${t.num_questions} questions · ${t.duration_mins} mins)</option>`).join('') +
            '</optgroup>';
        } else {
          testSelect.innerHTML = '<option value="ai_custom" selected>✨ AI Custom Test (Generated by Gemini AI for this Job)</option>';
        }
      })
      .catch(() => {
        testSelect.innerHTML = '<option value="">Error loading tests</option>';
      });

    // Toggle AI options visibility
    testSelect.addEventListener('change', function() {
      const aiOptions = document.getElementById('ai-custom-options');
      if (this.value === 'ai_custom') {
        aiOptions.style.display = 'block';
      } else {
        aiOptions.style.display = 'none';
      }
    });

    setTimeout(() => {
      testSelect.dispatchEvent(new Event('change'));
    }, 500);

    aptModal.classList.add('show');
    aptModal.style.display = 'flex';
  }

  function closeAptModal() {
    if (!aptModal) return;
    aptModal.classList.remove('show');
    aptModal.style.display = 'none';
  }

  openAptBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const appId = this.getAttribute('data-id');
      openAptModal(appId);
    });
  });

  if (aptModal) {
    aptModal.querySelectorAll('.close-modal-btn').forEach(btn => btn.addEventListener('click', closeAptModal));
  }

  if (aptForm) {
    aptForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const submitBtn = document.getElementById('submit-invite-btn');
      submitBtn.disabled = true;
      submitBtn.innerText = 'Sending Invitation...';

      const formData = new FormData(this);
      formData.append('<?= csrf_token() ?>', getCsrfToken());

      fetch('<?= site_url("employer/applications/invite-test") ?>', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(response => {
        submitBtn.disabled = false;
        submitBtn.innerText = 'Send Test Invitation';
        if (response.success) {
          if (response.invitation_url) {
            if (navigator.clipboard) {
              navigator.clipboard.writeText(response.invitation_url);
              if (typeof toastr !== 'undefined') {
                toastr.info('Test Link copied to clipboard: ' + response.invitation_url, 'Link Copied', {timeOut: 6000});
              }
            }
          }
          if (typeof toastr !== 'undefined') {
            toastr.success(response.message || 'Invitation sent successfully!');
          } else {
            alert(response.message || 'Invitation sent successfully!');
          }
          closeAptModal();
          setTimeout(() => location.reload(), 2000);
        } else {
          if (typeof toastr !== 'undefined') {
            toastr.error(response.message || 'Failed to send invitation');
          } else {
            alert(response.message || 'Failed to send invitation');
          }
        }
      })
      .catch(() => {
        submitBtn.disabled = false;
        submitBtn.innerText = 'Send Test Invitation';
        if (typeof toastr !== 'undefined') {
          toastr.error('Connection error sending invitation');
        } else {
          alert('Connection error sending invitation');
        }
      });
    });
  }
});
</script>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/jobs') ?>" class="emp-btn emp-btn-outline"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> My Jobs</a>
<a href="<?= base_url('employer/candidates') ?>" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-search-user"/></svg> Find Candidates</a>
<?= $this->endSection() ?>
