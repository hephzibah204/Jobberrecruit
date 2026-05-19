<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="content">

    <div class="page-header">
        <h4 class="fw-bold">Saved Jobs</h4>
        <h6>Jobs you've saved for later review</h6>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped" id="saved-jobs-table">
                    <thead class="thead-light">
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Category</th>
                            <th>Posted</th>
                            <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($savedJobs)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <p class="mb-1">No saved jobs yet.</p>
                                    <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm">Browse Jobs</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($savedJobs as $job): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($job->title) ?></td>
                                    <td><?= esc($job->company_name ?: 'N/A') ?></td>
                                    <td><?= esc($job->location ?: 'N/A') ?></td>
                                    <td><?= esc($job->category_name ?: 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($job->created_at)) ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= site_url('job/view/' . $job->id) ?>"
                                                class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger unsave-job"
                                                data-job-id="<?= $job->id ?>">
                                                Unsave
                                            </button>
                                        </div>
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

<?= $this->section('scripts') ?>
<script>
    document.querySelectorAll('.unsave-job').forEach(btn => {
        btn.addEventListener('click', function() {
            const jobId = this.dataset.jobId;
            if (!confirm('Remove this job from saved list?')) return;

            fetch('<?= base_url('job/unsave') ?>/' + jobId, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success !== false) {
                    this.closest('tr').remove();
                } else {
                    alert(data.message || 'Failed to unsave job.');
                }
            })
            .catch(() => alert('Network error. Please try again.'));
        });
    });
</script>
<?= $this->endSection() ?>
