<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- Page Header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">Job Applications</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Applications</li>
            </ol>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success py-2"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger py-2"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- Status Summary -->
    <div class="row mb-4">
        <?php foreach ($statusCounts as $row): ?>
            <div class="col-md-3">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <h6 class="mb-1 text-muted"><?= ucfirst($row->status) ?></h6>
                        <h4 class="fw-semibold"><?= number_format($row->total) ?></h4>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <!-- Bulk Actions + Applications Table -->
    <form method="post" id="applicationsBulkForm">
        <?= csrf_field() ?>

        <div class="card custom-card mb-4">
            <div class="card-body py-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="fw-semibold"><i class="ti ti-checks me-1"></i>Bulk actions</span>
                    <select name="status" class="form-select w-auto" aria-label="Bulk status">
                        <option value="pending">Pending</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="shortlisted">Shortlisted</option>
                        <option value="rejected">Rejected</option>
                        <option value="hired">Hired</option>
                        <option value="withdrawn">Withdrawn</option>
                    </select>
                    <button type="submit" class="btn btn-primary" formaction="<?= base_url('admin/applications/bulk-update-status') ?>">
                        Apply to selected
                    </button>
                    <button type="submit" class="btn btn-danger" id="bulkDeleteBtn" formaction="<?= base_url('admin/applications/bulk-delete') ?>">
                        <i class="ti ti-trash"></i> Delete selected
                    </button>
                    <span class="text-muted ms-auto" id="bulkSelectedCount">0 selected</span>
                </div>
            </div>
        </div>

    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th style="width: 40px;" class="text-center">
                                <input type="checkbox" id="selectAllApplications" aria-label="Select all applications">
                            </th>
                            <th>Applicant</th>
                            <th>Job</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($applications): ?>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="app-checkbox" name="ids[]" value="<?= $app->id ?>" aria-label="Select application">
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            <?= esc($app->first_name . ' ' . $app->last_name) ?>
                                        </div>
                                        <div class="text-muted fs-12">
                                            <?= esc($app->email) ?>
                                        </div>
                                    </td>

                                    <td><?= esc($app->job_title ?? '—') ?></td>
                                    <td><?= esc($app->company_name ?? '—') ?></td>

                                    <td>
                                        <span class="badge bg-<?= match ($app->status) {
                                                                    'hired' => 'success',
                                                                    'shortlisted' => 'info',
                                                                    'rejected' => 'danger',
                                                                    'reviewed' => 'warning',
                                                                    default => 'secondary'
                                                                } ?>-transparent">
                                            <?= ucfirst($app->status) ?>
                                        </span>
                                    </td>

                                    <td><?= date('M d, Y', strtotime($app->created_at)) ?></td>

                                    <td class="text-center">
                                        <a href="<?= base_url('admin/applications/view/' . $app->id) ?>"
                                            class="btn btn-sm btn-light border">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No applications found
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </form>

    <!-- Pagination -->
    <div class="mt-3">
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        const selectAll = document.getElementById('selectAllApplications');
        const form = document.getElementById('applicationsBulkForm');
        const countLabel = document.getElementById('bulkSelectedCount');
        const deleteBtn = document.getElementById('bulkDeleteBtn');

        function checkedBoxes() {
            return document.querySelectorAll('.app-checkbox:checked');
        }

        function refreshCount() {
            const n = checkedBoxes().length;
            if (countLabel) {
                countLabel.textContent = n + ' selected';
            }
            if (selectAll) {
                const all = document.querySelectorAll('.app-checkbox');
                selectAll.checked = all.length > 0 && n === all.length;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.app-checkbox').forEach(function(cb) {
                    cb.checked = selectAll.checked;
                });
                refreshCount();
            });
        }

        document.querySelectorAll('.app-checkbox').forEach(function(cb) {
            cb.addEventListener('change', refreshCount);
        });
        refreshCount();

        if (form) {
            form.addEventListener('submit', function(e) {
                if (checkedBoxes().length === 0) {
                    e.preventDefault();
                    alert('Please select at least one application.');
                }
            });
        }

        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                const n = checkedBoxes().length;
                if (n === 0) {
                    return; // the submit guard reports it
                }
                if (!confirm('Delete ' + n + ' selected application(s)? This cannot be undone.')) {
                    e.preventDefault();
                }
            });
        }
    })();
</script>
<?= $this->endSection() ?>