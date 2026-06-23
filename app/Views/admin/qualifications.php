<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">
    <div class="page-header-breadcrumb mb-3">
        <div class="page-title">
            <h1 class="page-title fw-medium fs-18 mb-0">Manage Qualifications</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Qualifications</li>
            </ol>
        </div>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openModal()">
                <i class="ti ti-plus me-1"></i>Add Qualification
            </button>
        </div>
    </div>

    <div class="card custom-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($qualifications as $q): ?>
                            <tr>
                                <td><?= $q->order_index ?></td>
                                <td><?= esc($q->name) ?></td>
                                <td>
                                    <?php if ($q->is_active): ?>
                                        <span class="badge bg-success-transparent text-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-transparent text-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($q->created_at)) ?></td>
                                <td>
                                    <div class="btn-list">
                                        <button class="btn btn-sm btn-icon btn-info-light rounded-circle" onclick="openModal(<?= htmlspecialchars(json_encode($q)) ?>)">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-danger-light rounded-circle" onclick="deleteItem(<?= $q->id ?>)">
                                            <i class="ti ti-trash"></i>
                                        </button>
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

<!-- Modal -->
<div class="modal fade" id="qualModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalTitle">Add Qualification</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="qualForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="qual_id">
                    <div class="mb-3">
                        <label class="form-label">Qualification Name</label>
                        <input type="text" class="form-control" name="name" id="qual_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order Index</label>
                        <input type="number" class="form-control" name="order_index" id="qual_order" value="0">
                        <small class="text-muted">Higher numbers appear later in the list.</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="qual_active" value="1" checked>
                        <label class="form-check-label" for="qual_active">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const qualModal = new bootstrap.Modal(document.getElementById('qualModal'));

    function openModal(data = null) {
        const form = document.getElementById('qualForm');
        form.reset();
        document.getElementById('qual_id').value = '';
        document.getElementById('modalTitle').innerText = 'Add Qualification';

        if (data) {
            document.getElementById('qual_id').value = data.id;
            document.getElementById('qual_name').value = data.name;
            document.getElementById('qual_order').value = data.order_index;
            document.getElementById('qual_active').checked = data.is_active == 1;
            document.getElementById('modalTitle').innerText = 'Edit Qualification';
        }
        qualModal.show();
    }

    document.getElementById('qualForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('<?= base_url('admin/qualifications') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                location.reload();
            } else {
                toastr.error(data.message || 'Error saving qualification');
            }
        });
    });

    function deleteItem(id) {
        if (confirm('Are you sure you want to delete this qualification?')) {
            fetch('<?= base_url('admin/qualifications/delete') ?>/' + id, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    location.reload();
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>
