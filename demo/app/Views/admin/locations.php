<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Locations Management</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Locations</li>
                </ol>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" onclick="openLocationModal()">
                    <i class="ti ti-plus"></i> Add Location
                </button>
            </div>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="row">
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded bg-primary-transparent me-3">
                            <i class="ti ti-map-pin fs-20 text-primary"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-12">Total Locations</p>
                            <h4 class="mb-0 fw-semibold"><?= count($states) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded bg-success-transparent me-3">
                            <i class="ti ti-check fs-20 text-success"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-12">Active Locations</p>
                            <h4 class="mb-0 fw-semibold">
                                <?= count(array_filter($states, fn($s) => $s->is_active)) ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded bg-info-transparent me-3">
                            <i class="ti ti-building-community fs-20 text-info"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-12">Total Regions</p>
                            <h4 class="mb-0 fw-semibold"><?= count($regions) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded bg-warning-transparent me-3">
                            <i class="ti ti-building-castle fs-20 text-warning"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-12">With Capital</p>
                            <h4 class="mb-0 fw-semibold">
                                <?= count(array_filter($states, fn($s) => !empty($s->capital))) ?>
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
                    <input type="text" class="form-control form-control-sm" placeholder="Search locations..." 
                        id="searchInput" onkeyup="searchTable()" style="width: 200px;">
                    <select class="form-select form-select-sm" style="width: 120px;" id="filterRegion" onchange="filterByRegion()">
                        <option value="">All Regions</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?= esc($region->region) ?>"><?= esc($region->region) ?></option>
                        <?php endforeach ?>
                    </select>
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
                <table class="table text-nowrap mb-0" id="locationsTable">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Location</th>
                            <th>Capital</th>
                            <th>Region</th>
                            <th>Employers</th>
                            <th>Jobs</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($states as $state): ?>
                            <tr data-region="<?= esc($state->region) ?>" data-status="<?= $state->is_active ? 'active' : 'inactive' ?>">
                                <td>
                                    <input type="checkbox" class="form-check-input location-checkbox" 
                                        value="<?= $state->id ?>">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm avatar-rounded bg-primary-transparent me-2">
                                            <i class="ti ti-map-pin fs-16"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= esc($state->name) ?></div>
                                            <?php if ($state->region): ?>
                                                <div class="fs-12 text-muted"><?= esc($state->region) ?></div>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <?php if ($state->capital): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-building-castle fs-16 text-warning me-1"></i>
                                            <span><?= esc($state->capital) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif ?>
                                </td>
                                
                                <td>
                                    <?php if ($state->region): ?>
                                        <span class="badge bg-info-transparent"><?= esc($state->region) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif ?>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-building fs-16 text-success me-1"></i>
                                        <span><?= $state->employer_count ?? 0 ?></span>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-briefcase fs-16 text-primary me-1"></i>
                                        <span><?= $state->job_count ?? 0 ?></span>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input status-toggle" 
                                            type="checkbox" 
                                            data-id="<?= $state->id ?>"
                                            <?= $state->is_active ? 'checked' : '' ?>
                                            onchange="toggleStatus(this)">
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="fs-12"><?= date('M d, Y', strtotime($state->created_at)) ?></div>
                                    <div class="fs-11 text-muted"><?= date('h:i A', strtotime($state->created_at)) ?></div>
                                </td>
                                
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-light border"
                                            onclick='openLocationModal(<?= json_encode($state) ?>)'
                                            title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        
                                        <button class="btn btn-sm btn-danger border"
                                            onclick="deleteLocation(<?= $state->id ?>)"
                                            title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                
                <?php if (empty($states)): ?>
                    <div class="text-center py-5">
                        <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                            <i class="ti ti-map-pin-off fs-24 text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-2">No locations found</h5>
                        <p class="text-muted mb-0">Add your first location to get started</p>
                        <button class="btn btn-primary mt-3" onclick="openLocationModal()">
                            <i class="ti ti-plus me-1"></i> Add Location
                        </button>
                    </div>
                <?php endif ?>
            </div>
        </div>
        
        <!-- PAGINATION (if needed) -->
        <?php if (!empty($states)): ?>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted fs-12">
                        Showing <?= count($states) ?> locations
                    </div>
                    <!-- Add pagination here if you implement it -->
                </div>
            </div>
        <?php endif ?>
    </div>
</div>

<!-- LOCATION MODAL -->
<div class="modal fade" id="locationModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="locationForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="location_id">

            <div class="modal-header">
                <h5 class="modal-title">Location Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Location Name *</label>
                    <input type="text" name="name" id="location_name" class="form-control" required>
                    <div class="form-text">e.g., Lagos, Abuja, Rivers</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Capital City</label>
                    <input type="text" name="capital" id="location_capital" class="form-control">
                    <div class="form-text">Optional: Capital city of this location</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Region</label>
                    <input type="text" name="region" id="location_region" class="form-control" 
                        list="regionsList">
                    <datalist id="regionsList">
                        <?php foreach ($regions as $region): ?>
                            <option value="<?= esc($region->region) ?>">
                        <?php endforeach ?>
                    </datalist>
                    <div class="form-text">e.g., South West, North Central, South South</div>
                </div>

                <div class="mb-0">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="is_active" id="location_is_active" value="1" checked>
                        <label class="form-check-label" for="location_is_active">Active</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit" id="locationSubmitBtn">Save Location</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

    const locationModal = new bootstrap.Modal('#locationModal');

    // Open location modal
    function openLocationModal(state = null) {
        document.getElementById('locationForm').reset();
        document.getElementById('location_id').value = '';
        
        if (state) {
            document.getElementById('location_id').value = state.id;
            document.getElementById('location_name').value = state.name;
            document.getElementById('location_capital').value = state.capital || '';
            document.getElementById('location_region').value = state.region || '';
            
            const activeCheckbox = document.getElementById('location_is_active');
            if (activeCheckbox) {
                activeCheckbox.checked = state.is_active == 1;
            }
        }
        
        locationModal.show();
        
        // Focus on name field
        setTimeout(() => {
            document.getElementById('location_name').focus();
        }, 300);
    }

    // Handle location form submission
    document.getElementById('locationForm').addEventListener('submit', e => {
        e.preventDefault();
        e.stopPropagation();
        const btn = document.getElementById('locationSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

        fetch("<?= base_url('admin/locations/save') ?>", {
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
                    locationModal.hide();
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
                btn.innerHTML = 'Save Location';
            });
    });

    // Delete location
    function deleteLocation(id) {
        if (!confirm('Are you sure you want to delete this location?\n\nThis action cannot be undone.')) {
            return;
        }

        fetch(`<?= base_url('admin/locations/delete') ?>/${id}`, {
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
        
        fetch(`<?= base_url('admin/locations/toggleStatus') ?>`, {
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
                    // Revert checkbox if failed
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
        
        const checkboxes = document.querySelectorAll('.location-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            toastr.warning('Please select at least one location');
            return;
        }
        
        if (action === 'delete' && !confirm(`Are you sure you want to delete ${ids.length} location(s)?`)) {
            return;
        }
        
        fetch(`<?= base_url('admin/locations/bulkAction') ?>`, {
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
        const checkboxes = document.querySelectorAll('.location-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Search table
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#locationsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }

    // Filter by region
    function filterByRegion() {
        const region = document.getElementById('filterRegion').value;
        const rows = document.querySelectorAll('#locationsTable tbody tr');
        
        rows.forEach(row => {
            const rowRegion = row.getAttribute('data-region');
            if (!region || rowRegion === region) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Filter by status
    function filterByStatus() {
        const status = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('#locationsTable tbody tr');
        
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
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Locations specific styles */
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
#bulkAction, #filterRegion, #filterStatus {
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

/* Data list styling */
datalist option {
    padding: 5px;
}
</style>
<?= $this->endSection() ?>