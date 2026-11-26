<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Audit Logs</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Audit Logs</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="card custom-card mb-3">
        <div class="card-body">
            <form method="GET" action="<?= base_url('admin/audit-logs') ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Admin</label>
                        <select name="user_id" class="form-select">
                            <option value="">All Admins</option>
                            <?php foreach ($admins as $adminUser): ?>
                                <option value="<?= (int) $adminUser->id ?>" <?= ($filters['user_id'] ?? 0) == $adminUser->id ? 'selected' : '' ?>>
                                    <?= esc($adminUser->email ?: ('Admin #' . $adminUser->id)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Action</label>
                        <select name="action" class="form-select">
                            <option value="">All Actions</option>
                            <?php foreach ($actions as $actionRow): ?>
                                <option value="<?= esc($actionRow->action) ?>" <?= ($filters['action'] ?? '') === $actionRow->action ? 'selected' : '' ?>>
                                    <?= esc($actionRow->action) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">From</label>
                        <input type="date" name="date_from" class="form-control" value="<?= esc($filters['date_from'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">To</label>
                        <input type="date" name="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-filter me-1"></i> Filter
                        </button>
                        <a href="<?= base_url('admin/audit-logs') ?>" class="btn btn-light">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card custom-card">
        <div class="card-body p-0 mb-2">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Admin</th>
                            <th>Action</th>
                            <th>Resource</th>
                            <th>IP Address</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td class="text-nowrap">
                                        <?= !empty($log->created_at) ? esc(date('M j, Y g:ia', strtotime($log->created_at))) : '—' ?>
                                    </td>
                                    <td>
                                        <?= esc($log->admin_email ?: ($log->user_id ? 'User #' . $log->user_id : 'System')) ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-transparent text-primary"><?= esc($log->action) ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($log->resource)): ?>
                                            <?= esc($log->resource) ?><?= !empty($log->resource_id) ? ' #' . (int) $log->resource_id : '' ?>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($log->ip_address ?: '—') ?></td>
                                    <td class="small text-muted">
                                        <?php
                                        $detailText = '';
                                        if (!empty($log->details)) {
                                            $decoded = json_decode((string) $log->details, true);
                                            if (is_array($decoded)) {
                                                $pairs = [];
                                                foreach ($decoded as $k => $v) {
                                                    $pairs[] = $k . ': ' . (is_array($v) ? implode(', ', $v) : $v);
                                                }
                                                $detailText = implode(' · ', $pairs);
                                            } else {
                                                $detailText = (string) $log->details;
                                            }
                                        }
                                        ?>
                                        <?= $detailText !== '' ? esc(character_limiter($detailText, 120)) : '—' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No audit log entries found. Admin actions such as job approvals, deletions and settings changes will appear here.
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>

</div>

<?= $this->endSection() ?>
