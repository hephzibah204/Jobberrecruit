<?= $this->extend('employers/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">

    <!-- Start::page-header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-center justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">Find Candidates</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboards</a></li>
                <li class="breadcrumb-item active" aria-current="page">Candidates</li>
            </ol>
        </div>
    </div>
    <!-- End::page-header -->

    <div class="row">

        <!-- ===================== SIDEBAR FILTERS ===================== -->
        <div class="col-xxl-3">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Filters</div>
                </div>
                <div class="card-body">

                    <h6 class="fw-medium mb-2">Desired Roles</h6>
                    <div class="filter-scroll" style="max-height: 200px; overflow-y: auto;">
                        <?php foreach ($jobTitleCounts as $row): ?>
                            <?php if (!empty($row->job_title)): ?>
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
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <hr>

                    <h6 class="fw-medium mb-2">Employment Type</h6>
                    <?php foreach ($jobTypeCounts as $row): ?>
                        <?php if (!empty($row->employment_type)): ?>
                            <div class="form-check mb-1">
                                <input class="form-check-input filter-input"
                                    type="checkbox"
                                    data-filter="employment_type"
                                    value="<?= esc($row->employment_type) ?>">
                                <label class="form-check-label text-capitalize">
                                    <?= esc(str_replace('-', ' ', $row->employment_type)) ?>
                                </label>
                                <span class="badge bg-light float-end"><?= $row->total ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <hr>

                    <h6 class="fw-medium mb-2">Education Level</h6>
                    <?php foreach ($educationCounts as $row): ?>
                        <?php if (!empty($row->education_level)): ?>
                            <div class="form-check mb-1">
                                <input class="form-check-input filter-input"
                                    type="checkbox"
                                    data-filter="education_level"
                                    value="<?= esc($row->education_level) ?>">
                                <label class="form-check-label">
                                    <?= esc($row->education_level) ?>
                                </label>
                                <span class="badge bg-light float-end"><?= $row->total ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <!-- ===================== MAIN CONTENT ===================== -->
        <div class="col-xxl-9">

            <!-- SEARCH BAR -->
            <div class="card custom-card mb-3">
                <div class="card-body">
                    <div class="input-group">
                        <input
                            type="text"
                            id="keyword-input"
                            class="form-control form-control-lg"
                            placeholder="Search by name, skills, or job title...">
                        <button class="btn btn-lg btn-primary" onclick="applyFilters(1)">
                            <i class="ri-search-line"></i> Search
                        </button>
                    </div>
                    <div class="mt-2 small text-muted">
                        Total Candidates: <span class="fw-bold text-primary"><?= number_format($total) ?></span>
                    </div>
                </div>
            </div>

            <!-- ===================== AJAX RESULTS ===================== -->
            <div id="candidates-results">
                <?= view('employers/partials/candidates_results', [
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
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            ${'<tr><td colspan="6"><div class="placeholder-glow"><span class="placeholder col-12"></span></div></td></tr>'.repeat(5)}
                        </tbody>
                    </table>
                </div>
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
                // Scroll to top of results
                document.getElementById('candidates-results').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
    }

    /* ---------- DEBOUNCED SEARCH ---------- */
    document.getElementById('keyword-input').addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => applyFilters(1), 500);
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
    window.addEventListener('popstate', () => {
        location.reload();
    });

    /* ---------- MESSAGE CANDIDATE ---------- */
    function startMessage(candidateId) {
        const formData = new FormData();
        formData.append('seeker_id', candidateId);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url("employer/messages/start") ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (res.success && res.redirect) {
                window.location.href = res.redirect;
            } else {
                toastr.error(res.message || 'Failed to start conversation');
            }
        })
        .catch(err => {
            toastr.error('Error starting conversation');
            console.error(err);
        });
    }
</script>
<?= $this->endSection() ?>
