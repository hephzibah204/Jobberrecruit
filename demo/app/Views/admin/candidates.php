<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">

    <!-- Start::page-header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-center justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">Candidates</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboards</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Jobs</a></li>
                <li class="breadcrumb-item active" aria-current="page">Candidates</li>
            </ol>
        </div>
    </div>
    <!-- End::page-header -->

    <div class="row">

        <!-- ===================== SIDEBAR FILTERS ===================== -->
        <div class="col-xxl-3">
            <div class="card custom-card">
                <div class="card-body">

                    <h6 class="fw-medium mb-2">Categories</h6>
                    <?php foreach ($jobTitleCounts as $row): ?>
                        <div class="form-check mb-1">
                            <input class="form-check-input filter-input"
                                type="checkbox"
                                data-filter="job_title"
                                value="<?= esc($row->job_title) ?>">
                            <label class="form-check-label">
                                <?= esc($row->job_title) ?>
                            </label>
                            <span class="badge bg-light float-end"><?= $row->total ?></span>
                        </div>
                    <?php endforeach; ?>

                    <hr>

                    <h6 class="fw-medium mb-2">Job Type</h6>
                    <?php foreach ($jobTypeCounts as $row): ?>
                        <div class="form-check mb-1">
                            <input class="form-check-input filter-input"
                                type="checkbox"
                                data-filter="employment_type"
                                value="<?= esc($row->employment_type) ?>">
                            <label class="form-check-label">
                                <?= esc($row->employment_type) ?>
                            </label>
                            <span class="badge bg-light float-end"><?= $row->total ?></span>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <!-- ===================== MAIN CONTENT ===================== -->
        <div class="col-xxl-9">

            <!-- SEARCH FORM (NEVER AJAX-REPLACED) -->
            <div class="card custom-card mb-3">
                <div class="card-body">
                    <div class="input-group">
                        <input
                            type="text"
                            id="keyword-input"
                            class="form-control form-control-lg"
                            placeholder="Search candidates...">
                        <button class="btn btn-lg btn-primary">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===================== AJAX RESULTS ===================== -->
            <div id="candidates-results">
                <?= view('admin/partials/candidates_results', [
                    'candidates' => $candidates,
                    'pager' => $pager
                ]) ?>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let debounceTimer = null;

    /* ---------- COLLECT FILTERS ---------- */
    function collectParams(page = 1) {
        const params = new URLSearchParams();
        params.set('page', page);

        const keyword = document.getElementById('keyword-input').value;
        if (keyword) params.set('keyword', keyword);

        document.querySelectorAll('.filter-input:checked').forEach(el => {
            params.append(el.dataset.filter + '[]', el.value);
        });

        return params;
    }

    /* ---------- LOADING SKELETON ---------- */
    function showSkeleton() {
        document.getElementById('candidates-results').innerHTML = `
        <div class="card">
            <div class="card-body">
                ${'<div class="placeholder-glow mb-2"><span class="placeholder col-12"></span></div>'.repeat(6)}
            </div>
        </div>
    `;
    }

    /* ---------- APPLY FILTERS ---------- */
    function applyFilters(page = 1, pushState = true) {
        const params = collectParams(page);
        showSkeleton();

        fetch("<?= current_url() ?>?" + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('candidates-results').innerHTML = html;
                if (pushState) {
                    history.pushState({}, '', '?' + params.toString());
                }
            });
    }

    /* ---------- DEBOUNCED SEARCH ---------- */
    document.getElementById('keyword-input').addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => applyFilters(1), 400);
    });

    /* ---------- FILTER CHECKBOXES ---------- */
    document.querySelectorAll('.filter-input').forEach(el => {
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

    /* ---------- RESTORE STATE ---------- */
    window.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(location.search);

        if (params.get('keyword')) {
            document.getElementById('keyword-input').value = params.get('keyword');
        }

        document.querySelectorAll('.filter-input').forEach(el => {
            if (params.getAll(el.dataset.filter + '[]').includes(el.value)) {
                el.checked = true;
            }
        });
    });

    /* ---------- DELETE CANDIDATE ---------- */
    function deleteCandidate(candidateId) {
        if (confirm('Are you sure you want to delete this candidate? This action cannot be undone. All related applications and alerts will be permanently deleted.')) {
            fetch('<?= base_url("admin/candidates/delete") ?>/' + candidateId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        toastr.success(res.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(res.message);
                    }
                })
                .catch(err => {
                    toastr.error('Error deleting candidate');
                    console.error(err);
                });
        }
    }
</script>
<?= $this->endSection() ?>