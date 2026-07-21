<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.stats--apps {
  grid-template-columns: repeat(6, 1fr);
}
@media (max-width: 1250px) {
  .stats--apps {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (max-width: 560px) {
  .stats--apps {
    grid-template-columns: repeat(2, 1fr);
  }
}
.appl-cell {
  display: flex;
  align-items: center;
  gap: 11px;
  min-width: 0;
}
.appl-name {
  font-weight: 700;
  font-size: .85rem;
  color: var(--brand-deep);
}
.appl-mail {
  font-size: .72rem;
  color: var(--muted);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  max-width: 220px;
}
@media (max-width: 860px) {
  .tbl--apps {
    min-width: 0;
  }
  .tbl--apps thead {
    display: none;
  }
  .tbl--apps tr {
    display: block;
    border-bottom: 1px solid var(--border);
    padding: 14px 4px;
  }
  .tbl--apps td {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: none;
    padding: 5px 0;
    gap: 10px;
  }
  .tbl--apps td::before {
    content: attr(data-lbl);
    font-size: .66rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--muted);
  }
  .tbl--apps td.no-lbl::before {
    display: none;
  }
  .appl-mail {
    max-width: 55vw;
  }
}

/* Modal Styling */
.modal {
  position: fixed;
  inset: 0;
  z-index: 1050;
  display: none;
  align-items: center;
  justify-content: center;
  background: rgba(10, 25, 45, 0.5);
  backdrop-filter: blur(4px);
  padding: 16px;
}
.modal.show {
  display: flex;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head-left">
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

      <select class="select" id="bulk-actions" aria-label="Bulk actions">
        <option value="">Bulk actions</option>
        <option value="reviewed">Mark as reviewed</option>
        <option value="shortlisted">Shortlist</option>
        <option value="rejected">Reject</option>
        <option value="delete">Delete selected</option>
      </select>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert(data.message || 'Failed to delete application.');
          confirmDeleteBtn.disabled = false;
          confirmDeleteBtn.textContent = 'Delete';
          hideModals();
        }
      })
      .catch(err => {
        alert('An error occurred. Please try again.');
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
        alert('Please select at least one application.');
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
    params.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

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
        alert(data.message || 'Failed to update statuses.');
      }
    })
    .catch(err => {
      alert('An error occurred updating statuses.');
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
      params.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

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
          alert(data.message || 'Failed to delete applications.');
          confirmBulkDeleteBtn.disabled = false;
          confirmBulkDeleteBtn.textContent = 'Delete All';
          hideModals();
        }
      })
      .catch(err => {
        alert('An error occurred during deletion.');
        confirmBulkDeleteBtn.disabled = false;
        confirmBulkDeleteBtn.textContent = 'Delete All';
        hideModals();
      });
    });
  }
});
</script>
<?= $this->endSection() ?>