<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .stats-card {
        transition: all 0.25s ease;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }
    .cursor-pointer {
        cursor: pointer !important;
    }
    .hover-underline:hover {
        text-decoration: underline !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">

    <!-- Start::page-header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Candidate Management</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Candidates</li>
                </ol>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetFilters()">
                    <i class="ti ti-refresh me-1"></i> Reset Filters
                </button>
            </div>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- ===================== KPI METRICS CARDS ===================== -->
    <div class="row g-3 mb-4">
        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card custom-card stats-card bg-primary bg-opacity-10 mb-0" onclick="filterByCard('all')">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted fw-medium d-block mb-1">Total Candidates</span>
                            <h4 class="mb-0 fw-bold text-primary"><?= number_format($candidateStats['total'] ?? 0) ?></h4>
                        </div>
                        <div class="avatar avatar-md bg-primary-transparent rounded-circle">
                            <i class="ti ti-users fs-20 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card custom-card stats-card bg-success bg-opacity-10 mb-0" onclick="filterByCard('visible')">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted fw-medium d-block mb-1">Active & Visible</span>
                            <h4 class="mb-0 fw-bold text-success"><?= number_format($candidateStats['visible'] ?? 0) ?></h4>
                        </div>
                        <div class="avatar avatar-md bg-success-transparent rounded-circle">
                            <i class="ti ti-eye fs-20 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card custom-card stats-card bg-info bg-opacity-10 mb-0" onclick="filterByCard('verified')">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted fw-medium d-block mb-1">Verified Candidates</span>
                            <h4 class="mb-0 fw-bold text-info"><?= number_format($candidateStats['verified'] ?? 0) ?></h4>
                        </div>
                        <div class="avatar avatar-md bg-info-transparent rounded-circle">
                            <i class="ti ti-shield-check fs-20 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card custom-card stats-card bg-purple bg-opacity-10 mb-0" onclick="filterByCard('with_cv')">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted fw-medium d-block mb-1">CV Ready</span>
                            <h4 class="mb-0 fw-bold text-purple"><?= number_format($candidateStats['with_resume'] ?? 0) ?></h4>
                        </div>
                        <div class="avatar avatar-md bg-purple-transparent rounded-circle">
                            <i class="ti ti-file-text fs-20 text-purple"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card custom-card stats-card bg-warning bg-opacity-10 mb-0" onclick="filterByCard('no_cv')">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted fw-medium d-block mb-1">Missing Resume</span>
                            <h4 class="mb-0 fw-bold text-warning"><?= number_format($candidateStats['without_resume'] ?? 0) ?></h4>
                        </div>
                        <div class="avatar avatar-md bg-warning-transparent rounded-circle">
                            <i class="ti ti-file-off fs-20 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card custom-card stats-card bg-teal bg-opacity-10 mb-0">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-muted fw-medium d-block mb-1">Total Applications</span>
                            <h4 class="mb-0 fw-bold text-teal"><?= number_format($candidateStats['total_applications'] ?? 0) ?></h4>
                        </div>
                        <div class="avatar avatar-md bg-teal-transparent rounded-circle">
                            <i class="ti ti-send fs-20 text-teal"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MAIN LAYOUT ===================== -->
    <div class="row">

        <!-- ===================== SIDEBAR FILTERS ===================== -->
        <div class="col-xxl-3 col-xl-4">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Filter by Taxonomy</div>
                    <button type="button" class="btn btn-link btn-sm text-muted p-0 text-decoration-none" onclick="clearTaxonomyFilters()">Clear</button>
                </div>
                <div class="card-body p-0">
                    <!-- Top Roles -->
                    <?php if (!empty($jobTitleCounts)): ?>
                        <div class="p-3 border-bottom">
                            <h6 class="fw-medium fs-13 text-muted mb-2 text-uppercase">Top Roles</h6>
                            <div style="max-height: 220px; overflow-y: auto;">
                                <?php foreach (array_slice($jobTitleCounts, 0, 15) as $row): ?>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input filter-checkbox"
                                            type="checkbox"
                                            data-filter="job_title"
                                            value="<?= esc($row->job_title) ?>">
                                        <label class="form-check-label fs-13">
                                            <?= esc($row->job_title) ?>
                                        </label>
                                        <span class="badge bg-light text-muted float-end fs-11"><?= $row->total ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Job Types -->
                    <?php if (!empty($jobTypeCounts)): ?>
                        <div class="p-3 border-bottom">
                            <h6 class="fw-medium fs-13 text-muted mb-2 text-uppercase">Employment Type</h6>
                            <?php foreach ($jobTypeCounts as $row): ?>
                                <?php if (!empty($row->employment_type)): ?>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input filter-checkbox"
                                            type="checkbox"
                                            data-filter="employment_type"
                                            value="<?= esc($row->employment_type) ?>">
                                        <label class="form-check-label fs-13">
                                            <?= esc($row->employment_type) ?>
                                        </label>
                                        <span class="badge bg-light text-muted float-end fs-11"><?= $row->total ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Education Levels -->
                    <?php if (!empty($educationCounts)): ?>
                        <div class="p-3 border-bottom">
                            <h6 class="fw-medium fs-13 text-muted mb-2 text-uppercase">Education Level</h6>
                            <?php foreach ($educationCounts as $row): ?>
                                <?php if (!empty($row->education_level)): ?>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input filter-checkbox"
                                            type="checkbox"
                                            data-filter="education_level"
                                            value="<?= esc($row->education_level) ?>">
                                        <label class="form-check-label fs-13">
                                            <?= esc($row->education_level) ?>
                                        </label>
                                        <span class="badge bg-light text-muted float-end fs-11"><?= $row->total ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Notice / Availability -->
                    <?php if (!empty($availabilityCounts)): ?>
                        <div class="p-3">
                            <h6 class="fw-medium fs-13 text-muted mb-2 text-uppercase">Availability / Notice</h6>
                            <?php foreach ($availabilityCounts as $row): ?>
                                <?php if (!empty($row->availability)): ?>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input filter-checkbox"
                                            type="checkbox"
                                            data-filter="availability"
                                            value="<?= esc($row->availability) ?>">
                                        <label class="form-check-label fs-13">
                                            <?= esc($row->availability) ?>
                                        </label>
                                        <span class="badge bg-light text-muted float-end fs-11"><?= $row->total ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- ===================== MAIN CONTENT ===================== -->
        <div class="col-xxl-9 col-xl-8">

            <!-- SEARCH & ADVANCED FILTER TOOLBAR -->
            <div class="card custom-card mb-3">
                <div class="card-body p-3">
                    <!-- Search Input -->
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="ri-search-line text-muted"></i>
                        </span>
                        <input
                            type="text"
                            id="keyword-input"
                            class="form-control border-start-0 ps-0"
                            placeholder="Search by candidate name, email, skills, job title, phone...">
                        <button class="btn btn-primary" type="button" onclick="applyFilters(1)">
                            Search
                        </button>
                    </div>

                    <!-- Dropdown Filters Row -->
                    <div class="row g-2">
                        <!-- State Dropdown -->
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <label class="form-label fs-12 text-muted mb-1">Nigerian State</label>
                            <select id="state-select" class="form-select form-select-sm filter-select">
                                <option value="all">All States</option>
                                <?php foreach ($states as $st): ?>
                                    <option value="<?= $st->id ?>"><?= esc($st->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Experience Range -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label fs-12 text-muted mb-1">Experience</label>
                            <select id="experience-select" class="form-select form-select-sm filter-select">
                                <option value="all">Any Experience</option>
                                <option value="0-1">0 - 1 years</option>
                                <option value="1-3">1 - 3 years</option>
                                <option value="3-5">3 - 5 years</option>
                                <option value="5-10">5 - 10 years</option>
                                <option value="10+">10+ years</option>
                            </select>
                        </div>

                        <!-- Resume Status -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label fs-12 text-muted mb-1">Resume / CV</label>
                            <select id="resume-select" class="form-select form-select-sm filter-select">
                                <option value="all">All Profiles</option>
                                <option value="with_cv">Has Resume</option>
                                <option value="no_cv">Missing Resume</option>
                            </select>
                        </div>

                        <!-- Verification Status -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label fs-12 text-muted mb-1">Verification</label>
                            <select id="verification-select" class="form-select form-select-sm filter-select">
                                <option value="all">All Statuses</option>
                                <option value="verified">Verified Only</option>
                                <option value="unverified">Unverified Only</option>
                            </select>
                        </div>

                        <!-- Visibility -->
                        <div class="col-lg-1 col-md-4 col-sm-6">
                            <label class="form-label fs-12 text-muted mb-1">Visibility</label>
                            <select id="visibility-select" class="form-select form-select-sm filter-select">
                                <option value="all">All</option>
                                <option value="visible">Visible</option>
                                <option value="hidden">Hidden</option>
                            </select>
                        </div>

                        <!-- Sorting -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label fs-12 text-muted mb-1">Sort By</label>
                            <select id="sort-select" class="form-select form-select-sm filter-select">
                                <option value="newest">Newest First</option>
                                <option value="most_experienced">Most Experienced</option>
                                <option value="most_applications">Most Applications</option>
                                <option value="recently_active">Recently Active</option>
                                <option value="name_asc">Name (A-Z)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BULK ACTIONS TOOLBAR (Appears when rows selected) -->
            <div id="bulk-action-bar" class="alert alert-primary d-none align-items-center justify-content-between py-2 px-3 mb-3 rounded shadow-sm">
                <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-checkbox fs-18"></i>
                    <span id="selected-count-text" class="fw-semibold">0 candidates selected</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkDeleteCandidates()">
                        <i class="ti ti-trash me-1"></i> Delete Selected
                    </button>
                    <button type="button" class="btn btn-sm btn-light" onclick="deselectAllCandidates()">
                        Cancel
                    </button>
                </div>
            </div>

            <!-- ===================== AJAX RESULTS ===================== -->
            <div id="candidates-results">
                <?= view('admin/partials/candidates_results', [
                    'candidates' => $candidates,
                    'pager'      => $pager,
                    'filters'    => $filters
                ]) ?>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let debounceTimer = null;
    const csrfTokenName = '<?= csrf_token() ?>';
    let csrfTokenHash = '<?= csrf_hash() ?>';

    /* ---------- COLLECT FILTERS ---------- */
    function collectParams(page = 1) {
        const params = new URLSearchParams();
        params.set('page', page);

        const keyword = document.getElementById('keyword-input').value.trim();
        if (keyword) params.set('keyword', keyword);

        const state = document.getElementById('state-select').value;
        if (state && state !== 'all') params.set('state_id', state);

        const exp = document.getElementById('experience-select').value;
        if (exp && exp !== 'all') params.set('experience_years', exp);

        const resume = document.getElementById('resume-select').value;
        if (resume && resume !== 'all') params.set('resume_status', resume);

        const verify = document.getElementById('verification-select').value;
        if (verify && verify !== 'all') params.set('verification_status', verify);

        const visibility = document.getElementById('visibility-select').value;
        if (visibility && visibility !== 'all') params.set('visibility', visibility);

        const sort = document.getElementById('sort-select').value;
        if (sort && sort !== 'newest') params.set('sort', sort);

        // Sidebar Checkbox Taxonomy
        document.querySelectorAll('.filter-checkbox:checked').forEach(el => {
            params.append(el.dataset.filter + '[]', el.value);
        });

        return params;
    }

    /* ---------- LOADING SKELETON ---------- */
    function showSkeleton() {
        document.getElementById('candidates-results').innerHTML = `
            <div class="card custom-card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="placeholder-glow">
                        <div class="placeholder col-12 mb-3 py-2 bg-light"></div>
                        <div class="placeholder col-12 mb-2 bg-light"></div>
                        <div class="placeholder col-12 mb-2 bg-light"></div>
                        <div class="placeholder col-12 mb-2 bg-light"></div>
                        <div class="placeholder col-12 mb-2 bg-light"></div>
                    </div>
                </div>
            </div>
        `;
    }

    /* ---------- APPLY FILTERS ---------- */
    function applyFilters(page = 1, pushState = true) {
        const params = collectParams(page);
        showSkeleton();

        fetch("<?= base_url('admin/candidates') ?>?" + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('candidates-results').innerHTML = html;
            updateBulkDeleteState();
            if (pushState) {
                history.pushState({}, '', '?' + params.toString());
            }
        })
        .catch(err => {
            console.error('Filter error:', err);
            document.getElementById('candidates-results').innerHTML = `
                <div class="alert alert-danger">Error loading candidates. Please refresh.</div>
            `;
        });
    }

    /* ---------- KPI CARD FILTER TRIGGERS ---------- */
    function filterByCard(type) {
        resetFilters(false);
        if (type === 'visible') {
            document.getElementById('visibility-select').value = 'visible';
        } else if (type === 'verified') {
            document.getElementById('verification-select').value = 'verified';
        } else if (type === 'with_cv') {
            document.getElementById('resume-select').value = 'with_cv';
        } else if (type === 'no_cv') {
            document.getElementById('resume-select').value = 'no_cv';
        }
        applyFilters(1);
    }

    function resetFilters(trigger = true) {
        document.getElementById('keyword-input').value = '';
        document.getElementById('state-select').value = 'all';
        document.getElementById('experience-select').value = 'all';
        document.getElementById('resume-select').value = 'all';
        document.getElementById('verification-select').value = 'all';
        document.getElementById('visibility-select').value = 'all';
        document.getElementById('sort-select').value = 'newest';
        document.querySelectorAll('.filter-checkbox').forEach(el => el.checked = false);
        if (trigger) applyFilters(1);
    }

    function clearTaxonomyFilters() {
        document.querySelectorAll('.filter-checkbox').forEach(el => el.checked = false);
        applyFilters(1);
    }

    /* ---------- DEBOUNCED SEARCH & EVENT LISTENERS ---------- */
    document.getElementById('keyword-input').addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => applyFilters(1), 350);
    });

    document.querySelectorAll('.filter-select').forEach(el => {
        el.addEventListener('change', () => applyFilters(1));
    });

    document.querySelectorAll('.filter-checkbox').forEach(el => {
        el.addEventListener('change', () => applyFilters(1));
    });

    /* ---------- AJAX PAGINATION ---------- */
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (!link || link.parentElement.classList.contains('disabled')) return;

        e.preventDefault();
        const page = new URL(link.href).searchParams.get('page') || 1;
        applyFilters(page);
    });

    /* ---------- TOGGLE VERIFICATION ---------- */
    function toggleCandidateVerification(id, btnEl) {
        fetch('<?= base_url("admin/candidates/toggle-verification") ?>/' + id, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                [csrfTokenName]: csrfTokenHash
            }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success(res.message);
                }
                if (res.is_verified) {
                    btnEl.className = 'badge bg-success border-0 cursor-pointer';
                    btnEl.innerHTML = '<i class="ti ti-check me-1"></i> Verified';
                } else {
                    btnEl.className = 'badge bg-secondary border-0 cursor-pointer';
                    btnEl.innerHTML = '<i class="ti ti-shield-off me-1"></i> Unverified';
                }
            } else {
                if (typeof toastr !== 'undefined') toastr.error(res.message || 'Action failed');
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof toastr !== 'undefined') toastr.error('Network error updating verification');
        });
    }

    /* ---------- BULK CHECKBOX LOGIC ---------- */
    function toggleSelectAllCandidates(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.candidate-checkbox');
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        updateBulkDeleteState();
    }

    function deselectAllCandidates() {
        const master = document.getElementById('selectAllCandidates');
        if (master) master.checked = false;
        document.querySelectorAll('.candidate-checkbox').forEach(cb => cb.checked = false);
        updateBulkDeleteState();
    }

    function updateBulkDeleteState() {
        const checked = document.querySelectorAll('.candidate-checkbox:checked');
        const bulkBar = document.getElementById('bulk-action-bar');
        const countText = document.getElementById('selected-count-text');

        if (checked.length > 0) {
            bulkBar.classList.remove('d-none');
            bulkBar.classList.add('d-flex');
            countText.textContent = `${checked.length} candidate${checked.length > 1 ? 's' : ''} selected`;
        } else {
            bulkBar.classList.add('d-none');
            bulkBar.classList.remove('d-flex');
        }
    }

    /* ---------- DELETE CANDIDATE ---------- */
    function deleteCandidate(candidateId) {
        if (!confirm('Are you sure you want to delete this candidate? All applications, saved alerts, and user credentials will be permanently removed.')) {
            return;
        }

        fetch('<?= base_url("admin/candidates/delete") ?>/' + candidateId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                [csrfTokenName]: csrfTokenHash
            }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                if (typeof toastr !== 'undefined') toastr.success(res.message || 'Candidate deleted successfully');
                const row = document.getElementById('candidate-row-' + candidateId);
                if (row) {
                    row.style.transition = 'opacity 0.3s';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 300);
                } else {
                    applyFilters(1);
                }
            } else {
                if (typeof toastr !== 'undefined') toastr.error(res.message || 'Deletion failed');
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof toastr !== 'undefined') toastr.error('Network error deleting candidate');
        });
    }

    /* ---------- BULK DELETE CANDIDATES ---------- */
    function bulkDeleteCandidates() {
        const checked = Array.from(document.querySelectorAll('.candidate-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) return;

        if (!confirm(`Are you sure you want to permanently delete these ${checked.length} selected candidate(s)?`)) {
            return;
        }

        fetch('<?= base_url("admin/candidates/bulk-delete") ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                [csrfTokenName]: csrfTokenHash
            },
            body: JSON.stringify({ ids: checked })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                if (typeof toastr !== 'undefined') toastr.success(res.message || 'Selected candidates deleted successfully');
                deselectAllCandidates();
                applyFilters(1);
            } else {
                if (typeof toastr !== 'undefined') toastr.error(res.message || 'Bulk deletion failed');
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof toastr !== 'undefined') toastr.error('Network error executing bulk delete');
        });
    }
</script>
<?= $this->endSection() ?>