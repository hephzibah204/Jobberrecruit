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

    <!-- Applications Table -->
    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table text-nowrap mb-0">
                    <thead>
                        <tr>
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
                                <td colspan="6" class="text-center text-muted py-4">
                                    No applications found
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>

</div>

<?= $this->endSection() ?>