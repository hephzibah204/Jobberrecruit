<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="content">

    <div class="page-header">
        <h4 class="fw-bold">My Applications</h4>
        <h6>Track the status of all your job applications</h6>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped" id="applications-table">
                    <thead class="thead-light">
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>

                            <?php
                            $statusColors = [
                                'pending'     => 'warning',
                                'reviewed'    => 'info',
                                'shortlisted' => 'primary',
                                'rejected'    => 'danger',
                                'hired'       => 'success'
                            ];
                            $color = $statusColors[strtolower($app->status)] ?? 'secondary';
                            ?>

                            <tr>
                                <td class="fw-semibold"><?= esc($app->job_title) ?></td>

                                <td><?= esc($app->company_name ?: 'N/A') ?></td>

                                <td>
                                    <span class="badge bg-<?= $color ?>">
                                        <?= ucfirst($app->status) ?>
                                    </span>
                                </td>

                                <td><?= date('M d, Y', strtotime($app->created_at)) ?></td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <a href="<?= site_url('job/view/' . $app->job_id) ?>"
                                            class="btn btn-sm btn-white border">
                                            View Job
                                        </a>

                                        <a href="<?= site_url('candidate/applications/view/' . $app->id) ?>"
                                            class="btn btn-sm btn-primary">
                                            View Details
                                        </a>

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

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    $('#applications-table').DataTable({
        order: [
            [3, 'desc']
        ],
        pageLength: 10
    });
</script>
<?= $this->endSection() ?>