<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Job Fraud/Scam Reports</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Admin</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">All Reports</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Employer</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Date Reported</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reports as $report): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url('jobs/view/' . $report->job_id) ?>" target="_blank" class="fw-semibold text-primary">
                                                    <?= esc($report->job_title) ?>
                                                </a>
                                            </td>
                                            <td><?= esc($report->company_name) ?></td>
                                            <td>
                                                <span class="d-block fw-semibold"><?= ucfirst(esc($report->reason)) ?></span>
                                                <small class="text-muted"><?= esc($report->details) ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                $badgeClass = 'bg-warning-transparent';
                                                if ($report->status === 'acted') $badgeClass = 'bg-danger-transparent';
                                                if ($report->status === 'dismissed') $badgeClass = 'bg-success-transparent';
                                                if ($report->status === 'reviewed') $badgeClass = 'bg-info-transparent';
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= ucfirst($report->status) ?></span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($report->created_at)) ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="<?= base_url('admin/reports/update-status') ?>" method="POST">
                                                                <?= csrf_field() ?>
                                                                <input type="hidden" name="id" value="<?= $report->id ?>">
                                                                <input type="hidden" name="status" value="reviewed">
                                                                <button type="submit" class="dropdown-item">Mark as Reviewed</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="<?= base_url('admin/reports/update-status') ?>" method="POST">
                                                                <?= csrf_field() ?>
                                                                <input type="hidden" name="id" value="<?= $report->id ?>">
                                                                <input type="hidden" name="status" value="dismissed">
                                                                <button type="submit" class="dropdown-item">Dismiss Report</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="<?= base_url('admin/reports/update-status') ?>" method="POST">
                                                                <?= csrf_field() ?>
                                                                <input type="hidden" name="id" value="<?= $report->id ?>">
                                                                <input type="hidden" name="status" value="acted">
                                                                <button type="submit" class="dropdown-item text-danger">Take Action (Deactivate Job)</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
