<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1 fw-bold text-dark">Audience Segments</h2>
            <p class="text-muted mb-0 fs-13">Create dynamic rules to target specific user cohorts.</p>
        </div>
        <div>
            <a href="<?= base_url('admin/newsletters/segments/create') ?>" class="btn btn-primary shadow-sm">
                <i class="ti ti-plus me-1"></i> Build New Segment
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <?php if (empty($segments)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-users-group text-muted opacity-50" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">No Segments Found</h5>
                    <p class="text-muted mb-4">You haven't built any custom audience segments yet. Start targeting smarter!</p>
                    <a href="<?= base_url('admin/newsletters/segments/create') ?>" class="btn btn-primary">Create First Segment</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 rounded-start">Segment Name</th>
                                <th class="border-0">Rule Type</th>
                                <th class="border-0">Est. Audience Size</th>
                                <th class="border-0">Last Synced</th>
                                <th class="border-0 text-end rounded-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($segments as $segment): ?>
                            <tr>
                                <td>
                                    <h6 class="mb-1 fw-bold"><?= esc($segment->name) ?></h6>
                                    <small class="text-muted"><?= esc($segment->description) ?></small>
                                </td>
                                <td><span class="badge bg-secondary"><?= esc($segment->type) ?></span></td>
                                <td><span class="fw-bold text-dark"><?= number_format($segment->user_count) ?></span></td>
                                <td class="text-muted fs-13"><?= $segment->last_synced_at ? date('M d, Y', strtotime($segment->last_synced_at)) : 'Never' ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light border" onclick="alert('Edit query coming soon');"><i class="ti ti-pencil"></i></button>
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
