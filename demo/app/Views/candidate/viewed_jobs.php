<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="content">

    <div class="page-header">
        <h4 class="fw-bold">Recently Viewed Jobs</h4>
        <h6>Jobs you've recently explored</h6>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped" id="viewed-jobs-table">
                    <thead class="thead-light">
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($viewedJobs)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <p class="mb-1">You haven't viewed any jobs recently.</p>
                                    <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm">Browse Jobs</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($viewedJobs as $job): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($job->title) ?></td>
                                    <td><?= esc($job->company_name ?: 'N/A') ?></td>
                                    <td><?= esc($job->location ?: 'N/A') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $job->status === 'open' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($job->status) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('jobs/view/' . $job->id) ?>"
                                            class="btn btn-sm btn-primary">
                                            View Again
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
