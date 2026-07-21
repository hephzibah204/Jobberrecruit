<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header Section -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bookmark"/></svg> Saved Jobs</h1>
            <p>Jobs you've saved for later review</p>
        </div>
    </div>

    <!-- Alert Notices -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="notice notice--info" style="margin-bottom: 16px;">
            <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-check"/></svg>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="notice notice--danger" style="margin-bottom: 16px; background:#fdf2f2; border-color:#fde8e8; color:#f05252;">
            <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-x"/></svg>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <!-- Saved Jobs Card -->
    <section class="card" aria-label="Saved Jobs list">
        <div class="card-head">
            <span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bookmark"/></svg> Saved Jobs</span>
        </div>
        <?php if (empty($savedJobs)): ?>
        <div class="empty">
            <span class="empty-ic"><svg aria-hidden="true" style="width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bookmark"/></svg></span>
            <h3>No saved jobs yet</h3>
            <p>When you save a job, it will appear here so you can apply later.</p>
            <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm">Browse Jobs</a>
        </div>
        <?php else: ?>
        <div class="tbl-wrap">
            <table class="tbl" id="saved-jobs-table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Category</th>
                        <th>Posted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($savedJobs as $job): ?>
                        <tr>
                            <td><b style="color:var(--brand-deep);"><?= esc($job->title) ?></b></td>
                            <td><?= esc($job->company_name ?: 'N/A') ?></td>
                            <td><?= esc($job->location ?: 'N/A') ?></td>
                            <td><?= esc($job->category_name ?: 'N/A') ?></td>
                            <td style="font-size:.8rem;color:var(--muted);"><?= date('d M Y', strtotime($job->created_at)) ?></td>
                            <td>
                                <div style="display:flex; gap:8px;">
                                    <a href="<?= site_url('job/view/' . $job->id) ?>" class="btn btn-primary btn-sm">
                                        <svg aria-hidden="true" style="width:12px;height:12px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-eye"/></svg> View
                                    </a>
                                    <button type="button" class="btn btn-outline btn-sm unsave-job" data-job-id="<?= $job->id ?>">
                                        <svg aria-hidden="true" style="width:12px;height:12px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-trash"/></svg> Unsave
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>

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
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success !== false) {
                    this.closest('tr').remove();
                } else {
                    toastr.error(data.message || 'Failed to unsave job.');
                }
            })
            .catch(() => toastr.error('Network error. Please try again.'));
        });
    });
</script>
<?= $this->endSection() ?>

