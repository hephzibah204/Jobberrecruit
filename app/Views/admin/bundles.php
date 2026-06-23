<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Growth Bundles</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bundles</li>
                </ol>
            </div>

            <button class="btn btn-primary" onclick="openBundleModal()">
                <i class="ti ti-plus"></i> New Bundle
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Bundle Name</th>
                            <th>Code</th>
                            <th>Credits</th>
                            <th>Price</th>
                            <th>Cost per Credit</th>
                            <th>Best Value</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bundles as $bundle): ?>
                            <tr>
                                <td class="fw-semibold"><?= esc($bundle->name) ?></td>
                                <td><code><?= esc($bundle->slug) ?></code></td>
                                <td><?= (int)$bundle->job_credits ?> Posts</td>
                                <td>₦<?= number_format($bundle->price) ?></td>
                                <td>₦<?= number_format($bundle->price_per_credit ?? 0) ?></td>
                                <td>
                                    <?php if ($bundle->is_best_value): ?>
                                        <span class="badge bg-success">Best Value</span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $bundle->is_active ? 'success' : 'danger' ?>-transparent">
                                        <?= $bundle->is_active ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light border"
                                        onclick='openBundleModal(<?= json_encode($bundle) ?>)'>
                                        <i class="ti ti-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- BUNDLE MODAL -->
<div class="modal fade" id="bundleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="bundleForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="bundle_id">

            <div class="modal-header">
                <h5 class="modal-title">Create / Edit Growth Bundle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">

                <div class="col-md-8">
                    <label class="form-label">Bundle Name *</label>
                    <input type="text" name="name" id="bundle_name" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Bundle Code *</label>
                    <input type="text" name="slug" id="bundle_code" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Number of Job Credits *</label>
                    <input type="number" name="job_credits" id="bundle_credits"
                        class="form-control" min="1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price (₦) *</label>
                    <input type="number" name="price" id="bundle_price"
                        class="form-control" min="0" step="0.01" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cost Per Job (₦) *</label>
                    <input type="number" name="price_per_credit" id="price_per_credit"
                        class="form-control" min="0" step="0.01">
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_best_value" id="is_best_value">
                        <label class="form-check-label" for="is_best_value">
                            Mark as Best Value
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="alert alert-warning small">
                        <strong>Note to Admin:</strong><br>
                        Growth Bundles automatically provide:<br>
                        • Instant Approval<br>
                        • Featured Position<br>
                        • Anonymous Posting<br>
                        • Network Blast (for the jobs posted with these credits)
                    </div>
                </div>

                <div class="col-12">
                    <div class="alert alert-info small">
                        <strong>Tip:</strong> You can freely set any price and credit amount.<br>
                        Current suggested pricing (for reference only):<br>
                        • 1 credit → ₦9,000 • 2 credits → ₦15,000<br>
                        • 3 credits → ₦22,500 • 5 credits → ₦35,000 (Best Value)
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="bundleSubmitBtn">Save Bundle</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    toastr.options = {
        closeButton: true,
        progressBar: true
    };

    const bundleModal = new bootstrap.Modal('#bundleModal');

    function openBundleModal(bundle = null) {
        document.getElementById('bundleForm').reset();
        document.getElementById('bundle_id').value = '';

        if (bundle) {
            document.getElementById('bundle_id').value = bundle.id || '';
            document.getElementById('bundle_name').value = bundle.name || '';
            document.getElementById('bundle_code').value = bundle.slug || '';
            document.getElementById('bundle_credits').value = bundle.job_credits || '';
            document.getElementById('bundle_price').value = bundle.price || '';
            document.getElementById('price_per_credit').value = bundle.price_per_credit || '';
            document.getElementById('is_best_value').checked = !!bundle.is_best_value;
        }

        bundleModal.show();
    }

    // Form Submit
    document.getElementById('bundleForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('bundleSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch("<?= base_url('admin/bundles') ?>", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 800);
                } else {
                    toastr.error(res.message || 'Failed to save bundle');
                }
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Save Bundle';
            });
    });
</script>
<?= $this->endSection() ?>