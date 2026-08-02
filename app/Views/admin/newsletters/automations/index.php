<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Automated Campaigns</h1>
            <div class="ms-md-1 ms-0">
                <a href="<?= base_url('admin/newsletters/automations/builder') ?>" class="btn btn-primary btn-wave">
                    <i class="ti ti-plus me-1"></i> New Automation
                </a>
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Manage Automations</div>
            </div>
            <div class="card-body">
                <?php if (empty($automations)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="ti ti-robot fs-1"></i>
                        <p class="mt-2">No automations created yet. Build your first drip campaign!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Trigger Event</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($automations as $auto): ?>
                                    <tr>
                                        <td><div class="fw-semibold"><?= esc($auto->name) ?></div></td>
                                        <td><span class="badge bg-primary-transparent"><?= esc($auto->trigger_event) ?></span></td>
                                        <td>
                                            <span class="badge <?= $auto->status === 'active' ? 'bg-success-transparent' : 'bg-warning-transparent' ?>">
                                                <?= ucfirst(esc($auto->status)) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d M Y', strtotime($auto->created_at)) ?></td>
                                        <td>
                                            <a href="<?= base_url("admin/newsletters/automations/builder/{$auto->id}") ?>" class="btn btn-sm btn-icon btn-info-light">
                                                <i class="ti ti-edit"></i>
                                            </a>
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
</div>
<?= $this->endSection() ?>
