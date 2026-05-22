<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><i class="ti ti-mail me-2"></i>Newsletter Subscribers</h4>
            <h6>Manage newsletter subscribers</h6>
        </div>
        <div class="page-btn">
            <a href="<?= base_url('admin/newsletters/subscribers/export') ?>" class="btn btn-success me-2">
                <i class="ti ti-download me-1"></i>Export Emails
            </a>
            <a href="<?= base_url('admin/newsletters') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Subscribers (<?= count($subscribers) ?>)</h5>
        </div>
        <div class="card-body p-0">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success m-3"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <?php if (empty($subscribers)): ?>
                <div class="text-center py-5">
                    <i class="ti ti-mail-off fs-64 text-muted mb-3"></i>
                    <h5 class="text-muted">No subscribers yet</h5>
                    <p class="text-muted">Subscribers will appear here when they sign up.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>User ID</th>
                                <th>Subscribed At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($subscribers as $subscriber): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= esc($subscriber->email) ?></div>
                                    </td>
                                    <td>
                                        <?php if ($subscriber->user_id): ?>
                                            <span class="badge bg-info">User #<?= $subscriber->user_id ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Guest</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($subscriber->created_at ?? 'now')) ?></td>
                                    <td>
                                        <?php if ($subscriber->is_active): ?>
                                            <span class="badge bg-success-transparent text-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-transparent text-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" action="<?= base_url('admin/newsletters/subscribers/delete/' . $subscriber->id) ?>" class="d-inline" onsubmit="return confirm('Delete this subscriber?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
