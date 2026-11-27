<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Database Backups</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Database Backups</li>
                </ol>
            </div>

            <form method="POST" action="<?= base_url('admin/backups/create') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-database-export me-1"></i> Create Backup
                </button>
            </form>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success py-2"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger py-2"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="alert alert-info py-2">
        Backups are full copies of the database (structure and data), stored on the server in
        writable/backups and downloadable only from this page. To restore one, download it and
        import it through phpMyAdmin. There is deliberately no one-click restore here, so a
        backup can never overwrite the live database by accident.
    </div>

    <!-- TABLE -->
    <div class="card custom-card">
        <div class="card-body p-0 mb-2">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Created</th>
                            <th>Size</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($backups)): ?>
                            <?php foreach ($backups as $backup): ?>
                                <tr>
                                    <td><?= esc($backup['filename']) ?></td>
                                    <td><?= esc($backup['created_at']) ?></td>
                                    <td><?= esc($backup['size_human']) ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/backups/download/' . $backup['filename']) ?>" class="btn btn-sm btn-primary">
                                            <i class="ti ti-download"></i> Download
                                        </a>
                                        <form method="POST" action="<?= base_url('admin/backups/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this backup file?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="filename" value="<?= esc($backup['filename']) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">No backups yet. Create the first one with the button above.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
