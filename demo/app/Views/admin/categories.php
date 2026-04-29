<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Job Categories Management</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" onclick="openCategoryModal()">
                    <i class="ti ti-plus"></i> Add Category
                </button>
            </div>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded bg-primary-transparent me-3">
                            <i class="ti ti-category fs-20 text-primary"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-12">Total Categories</p>
                            <h4 class="mb-0 fw-semibold"><?= count($categories) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded bg-success-transparent me-3">
                            <i class="ti ti-check fs-20 text-success"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-12">Active Categories</p>
                            <h4 class="mb-0 fw-semibold">
                                <?= count(array_filter($categories, fn($c) => $c->is_active)) ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded bg-info-transparent me-3">
                            <i class="ti ti-briefcase fs-20 text-info"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-12">Total Jobs</p>
                            <h4 class="mb-0 fw-semibold">
                                <?= array_sum(array_column($categories, 'job_count')) ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BULK ACTIONS -->
    <div class="card custom-card mb-3">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" style="width: 140px;" id="bulkAction">
                        <option value="">Bulk Actions</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button class="btn btn-sm btn-primary" onclick="applyBulkAction()">Apply</button>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Search categories..."
                        id="searchInput" onkeyup="searchTable()" style="width: 200px;">
                    <select class="form-select form-select-sm" style="width: 120px;" id="filterStatus" onchange="filterByStatus()">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table text-nowrap mb-0" id="categoriesTable">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Category</th>
                            <th>Slug</th>
                            <th>Jobs</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr data-status="<?= $category->is_active ? 'active' : 'inactive' ?>">
                                <td>
                                    <input type="checkbox" class="form-check-input category-checkbox"
                                        value="<?= $category->id ?>">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm avatar-rounded bg-primary-transparent me-2">
                                            <i class="ti ti-category fs-16"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= esc($category->name) ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <code class="fs-12"><?= esc($category->slug) ?></code>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-briefcase fs-16 text-primary me-1"></i>
                                        <span><?= $category->job_count ?? 0 ?></span>
                                    </div>
                                </td>

                                <td>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input status-toggle"
                                            type="checkbox"
                                            data-id="<?= $category->id ?>"
                                            <?= $category->is_active ? 'checked' : '' ?>
                                            onchange="toggleStatus(this)">
                                    </div>
                                </td>

                                <td>
                                    <div class="fs-12"><?= date('M d, Y', strtotime($category->created_at)) ?></div>
                                    <div class="fs-11 text-muted"><?= date('h:i A', strtotime($category->created_at)) ?></div>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-light border"
                                            onclick='openCategoryModal(<?= json_encode($category) ?>)'
                                            title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-danger border"
                                            onclick="deleteCategory(<?= $category->id ?>)"
                                            title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

                <?php if (empty($categories)): ?>
                    <div class="text-center py-5">
                        <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                            <i class="ti ti-category-off fs-24 text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-2">No categories found</h5>
                        <p class="text-muted mb-0">Add your first job category to get started</p>
                        <button class="btn btn-primary mt-3" onclick="openCategoryModal()">
                            <i class="ti ti-plus me-1"></i> Add Category
                        </button>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <!-- PAGINATION -->
        <?php if (!empty($categories)): ?>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted fs-12">
                        Showing <?= count($categories) ?> categories
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>

<!-- CATEGORY MODAL -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="categoryForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="category_id">

            <div class="modal-header">
                <h5 class="modal-title">Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" id="category_name" class="form-control" required>
                    <div class="form-text">e.g., Information Technology, Healthcare, Finance</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug *</label>
                    <input type="text" name="slug" id="category_slug" class="form-control" required>
                    <div class="form-text">URL-friendly version of the name (e.g., information-technology)</div>
                </div>

                <div class="mb-0">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="is_active" id="category_is_active" value="1" checked>
                        <label class="form-check-label" for="category_is_active">Active</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit" id="categorySubmitBtn">Save Category</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const categoryModal = new bootstrap.Modal('#categoryModal');

    // Auto-generate slug from name
    document.getElementById('category_name').addEventListener('input', function() {
        const slugInput = document.getElementById('category_slug');
        const categoryId = document.getElementById('category_id').value;

        // Only auto-generate if slug is empty or we're creating new category
        if (!categoryId && (!slugInput.value || slugInput.value.trim() === '')) {
            const name = this.value.trim();
            if (name) {
                // Convert to URL-friendly slug
                let slug = name.toLowerCase()
                    .replace(/[^\w\s-]/g, '') // Remove special chars
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/--+/g, '-') // Replace multiple hyphens with single
                    .trim();

                slugInput.value = slug;
            }
        }
    });

    // Open category modal
    function openCategoryModal(category = null) {
        document.getElementById('categoryForm').reset();
        document.getElementById('category_id').value = '';

        if (category) {
            document.getElementById('category_id').value = category.id;
            document.getElementById('category_name').value = category.name;
            document.getElementById('category_slug').value = category.slug;

            const activeCheckbox = document.getElementById('category_is_active');
            if (activeCheckbox) {
                activeCheckbox.checked = category.is_active == 1;
            }
        }

        categoryModal.show();

        // Focus on name field
        setTimeout(() => {
            document.getElementById('category_name').focus();
        }, 300);
    }

    // Handle category form submission
    document.getElementById('categoryForm').addEventListener('submit', e => {
        e.preventDefault();
        e.stopPropagation();
        const btn = document.getElementById('categorySubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

        fetch("<?= base_url('admin/categories/save') ?>", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(e.target)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    categoryModal.hide();
                    setTimeout(() => location.reload(), 800);
                } else {
                    toastr.error(res.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while saving');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Save Category';
            });
    });

    // Delete category
    function deleteCategory(id) {
        if (!confirm('Are you sure you want to delete this category?\n\nThis action cannot be undone.')) {
            return;
        }

        fetch(`<?= base_url('admin/categories/delete') ?>/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="<?= csrf_token() ?>"]').value
                }
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 800);
                } else {
                    toastr.error(res.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while deleting');
            });
    }

    // Toggle status
    function toggleStatus(checkbox) {
        const id = checkbox.getAttribute('data-id');
        const isActive = checkbox.checked ? 1 : 0;

        fetch(`<?= base_url('admin/categories/toggleStatus') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="<?= csrf_token() ?>"]').value
                },
                body: JSON.stringify({
                    id: id,
                    is_active: isActive
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                    checkbox.checked = !checkbox.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while updating status');
                checkbox.checked = !checkbox.checked;
            });
    }

    // Bulk actions
    function applyBulkAction() {
        const action = document.getElementById('bulkAction').value;
        if (!action) {
            toastr.warning('Please select an action');
            return;
        }

        const checkboxes = document.querySelectorAll('.category-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);

        if (ids.length === 0) {
            toastr.warning('Please select at least one category');
            return;
        }

        if (action === 'delete' && !confirm(`Are you sure you want to delete ${ids.length} category(s)?`)) {
            return;
        }

        fetch(`<?= base_url('admin/categories/bulkAction') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="<?= csrf_token() ?>"]').value
                },
                body: JSON.stringify({
                    action: action,
                    ids: ids
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 800);
                } else {
                    toastr.error(res.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred');
            });
    }

    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.category-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Search table
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#categoriesTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }

    // Filter by status
    function filterByStatus() {
        const status = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('#categoriesTable tbody tr');

        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (!status || rowStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Categories specific styles */
    .avatar-rounded {
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }

    .bg-primary-transparent {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .bg-success-transparent {
        background-color: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .bg-info-transparent {
        background-color: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }

    .bg-warning-transparent {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    /* Form switch */
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .form-switch .form-check-input:checked {
        background-color: #22c55e;
        border-color: #22c55e;
    }

    /* Table row hover */
    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.02);
    }

    /* Bulk actions area */
    #bulkAction,
    #filterStatus {
        max-width: 200px;
    }

    /* Empty state */
    .text-center.py-5 .avatar-xl {
        width: 80px;
        height: 80px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #f8fafc;
        border-radius: 50%;
    }

    /* Modal animations */
    .modal.fade .modal-dialog {
        transform: translateY(-50px);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translateY(0);
    }

    /* Slug styling */
    code {
        background-color: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        color: #334155;
    }
</style>
<?= $this->endSection() ?>