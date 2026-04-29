<?= $this->extend('templates/app') ?>

<?= $this->section('styles') ?>
<style>
    img.company-logo {
        width: 50px;
        height: 50px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section-box-2">
    <div class="container">
        <div class="banner-hero banner-single banner-single-bg">
            <div class="block-banner text-center">
                <h3 class="wow animate__animated animate__fadeInUp">
                    <span class="color-brand-2"><?= $total_jobs ?></span> Jobs Available Now
                </h3>
                <div class="font-sm color-text-paragraph-2 mt-10 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    Find the perfect job that matches your skills and preferences.
                </div>
                <div class="form-find text-start mt-40 wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                    <form method="GET" action="<?= base_url('jobs/search') ?>" id="job-search-form">
                        <div class="box-industry">
                            <select class="form-input mr-10 select-active input-industry" name="industry_id">
                                <option value="">Select Industry</option>
                                <?php foreach ($industries as $industry): ?>
                                    <option value="<?= esc($industry->id) ?>" <?= $industry->id == $industryId ? 'selected' : '' ?>>
                                        <?= esc($industry->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <select class="form-input mr-10 select-active" name="state_id">
                            <option value="">Select Location</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= esc($state->id) ?>" <?= $state->id == $stateId ? 'selected' : '' ?>>
                                    <?= esc($state->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select class="form-input mr-10 select-active" name="experience_level">
                            <option value="">Select Experience Level</option>
                            <option value="entry" <?= $experienceLevel == 'entry' ? 'selected' : '' ?>>Entry Level</option>
                            <option value="mid" <?= $experienceLevel == 'mid' ? 'selected' : '' ?>>Mid Level</option>
                            <option value="senior" <?= $experienceLevel == 'senior' ? 'selected' : '' ?>>Senior Level</option>
                            <option value="executive" <?= $experienceLevel == 'executive' ? 'selected' : '' ?>>Executive</option>
                        </select>
                        <button type="submit" class="btn btn-default btn-find font-sm" id="search-btn">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-box mt-30">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9 col-md-12 col-sm-12 col-12 float-right">
                <div class="content-page">
                    <div class="box-filters-job">
                        <div class="row">
                            <div class="col-xl-6 col-lg-5">
                                <span class="text-small text-showing">
                                    Showing <strong><?= ($current_page - 1) * $per_page + 1 ?>-<?= min($current_page * $per_page, $total_jobs) ?></strong> of <strong><?= $total_jobs ?></strong> jobs
                                </span>
                            </div>
                            <div class="col-xl-6 col-lg-7 text-lg-end mt-sm-15">
                                <div class="display-flex2">
                                    <div class="box-border mr-10"><span class="text-sortby">Show:</span>
                                        <div class="dropdown dropdown-sort">
                                            <button class="btn dropdown-toggle" id="dropdownSort" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span><?= $per_page ?></span><i class="fi-rr-angle-small-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownSort">
                                                <li><a class="dropdown-item" href="#" data-per-page="10">10</a></li>
                                                <li><a class="dropdown-item" href="#" data-per-page="12">12</a></li>
                                                <li><a class="dropdown-item" href="#" data-per-page="20">20</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="box-border"><span class="text-sortby">Sort by:</span>
                                        <div class="dropdown dropdown-sort">
                                            <button class="btn dropdown-toggle" id="dropdownSort2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span><?= $sort_by == 'newest' ? 'Newest Post' : ($sort_by == 'oldest' ? 'Oldest Post' : 'Rating Post') ?></span><i class="fi-rr-angle-small-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownSort2">
                                                <li><a class="dropdown-item" href="#" data-sort-by="newest">Newest Post</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort-by="oldest">Oldest Post</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort-by="rating">Rating Post</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="job-list">
                        <?php if (empty($jobs)): ?>
                            <div class="col-12 text-center">
                                <p>No jobs found. Try adjusting your search criteria.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($jobs as $job): ?>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                    <div class="card-grid-2 hover-up">
                                        <div class="card-grid-2-image-left">
                                            <span class="flash <?= $job->job_type == 'freelance' ? 'bg-green' : '' ?>">
                                                <span><?= ucfirst($job->job_type) ?></span>
                                            </span>
                                            <div class="image-box">
                                                <img src="<?= $job->company_logo ? base_url($job->company_logo) : base_url($website_logo) ?>" alt="jobberRecruit" class="img-fluid company-logo">
                                            </div>
                                            <div class="right-info">
                                                <a class="name-job" href="<?= site_url('company/' . $job->employer_id) ?>"><?= esc($job->employer_name) ?></a>
                                                <span class="location-small"><?= esc($job->location) ?: 'Remote' ?></span>
                                            </div>
                                        </div>
                                        <div class="card-block-info">
                                            <h6><a href="<?= site_url('jobs/view/' . $job->id) ?>"><?= esc($job->title) ?></a></h6>
                                            <div class="mt-5">
                                                <span class="card-briefcase"><?= ucfirst($job->job_type) ?></span>
                                                <span class="card-time"><?= humanize_time($job->created_at) ?></span>
                                            </div>
                                            <p class="font-sm color-text-paragraph mt-15">
                                                <?= esc(strip_tags(substr($job->description, 0, 100))) . '...' ?>
                                            </p>
                                            <div class="mt-30">
                                                <?php if (!empty($job->skills)): ?>
                                                    <?php foreach (explode(',', $job->skills) as $skill): ?>
                                                        <a class="btn btn-grey-small mr-5" href="<?= site_url('jobs?skill=' . urlencode(trim($skill))) ?>"><?= esc(trim($skill)) ?></a>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-2-bottom mt-30">
                                                <div class="row">
                                                    <div class="col-lg-7 col-7">
                                                        <span class="card-text-price"><?= esc($job->salary) ?: 'Negotiable' ?></span>
                                                        <span class="text-muted"><?= $job->salary ? '/' . esc($job->salary_period) : '' ?></span>
                                                    </div>
                                                    <div class="col-lg-5 col-5 text-end">
                                                        <div class="btn btn-apply-now" data-bs-toggle="modal" data-bs-target="#ModalApplyJobForm">Apply now</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="paginations">
                        <ul class="pager">
                            <li><a class="pager-prev" href="#" <?= $current_page <= 1 ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>></a></li>
                            <?php for ($i = 1; $i <= ceil($total_jobs / $per_page); $i++): ?>
                                <li><a class="pager-number <?= $i == $current_page ? 'active' : '' ?>" href="#" data-page="<?= $i ?>"><?= $i ?></a></li>
                            <?php endfor; ?>
                            <li><a class="pager-next" href="#" <?= $current_page >= ceil($total_jobs / $per_page) ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                <div class="sidebar-shadow none-shadow mb-30">
                    <div class="sidebar-filters">
                        <div class="filter-block head-border mb-30">
                            <h5>Advance Filter <a class="link-reset" href="<?= site_url('jobs') ?>">Reset</a></h5>
                        </div>

                        <!-- Location Filter -->
                        <div class="filter-block mb-30">
                            <div class="form-group select-style select-style-icon">
                                <select class="form-control form-icons select-active" name="state_id_filter" id="state_id_filter">
                                    <option value="">Select Location</option>
                                    <?php if (!empty($states)): ?>
                                        <?php foreach ($states as $state): ?>
                                            <option value="<?= esc($state->id) ?>" <?= ($stateId ?? null) == $state->id ? 'selected' : '' ?>>
                                                <?= esc($state->name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <i class="fi-rr-marker"></i>
                            </div>
                        </div>

                        <!-- Industry Filter -->
                        <div class="filter-block mb-20">
                            <h5 class="medium-heading mb-15">Industry</h5>
                            <div class="form-group">
                                <ul class="list-checkbox">
                                    <li>
                                        <label class="cb-container">
                                            <input type="checkbox" name="industry_id_filter" value="all" <?= empty($industryId) ? 'checked' : '' ?>>
                                            <span class="text-small">All</span><span class="checkmark"></span>
                                        </label>
                                        <span class="number-item"><?= $total_jobs ?? 0 ?></span>
                                    </li>
                                    <?php if (!empty($industry_counts)): ?>
                                        <?php foreach ($industry_counts as $industry): ?>
                                            <li>
                                                <label class="cb-container">
                                                    <input type="checkbox" name="industry_id_filter" value="<?= esc($industry->id) ?>" <?= ($industryId ?? null) == $industry->id ? 'checked' : '' ?>>
                                                    <span class="text-small"><?= esc($industry->name) ?></span><span class="checkmark"></span>
                                                </label>
                                                <span class="number-item"><?= $industry->job_count ?? 0 ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Salary Range Filter -->
                        <div class="filter-block mb-20">
                            <h5 class="medium-heading mb-25">Salary Range</h5>
                            <div class="list-checkbox pb-20">
                                <div class="row position-relative mt-10 mb-20">
                                    <div class="col-sm-12 box-slider-range">
                                        <div id="slider-range"></div>
                                    </div>
                                    <div class="box-input-money">
                                        <input class="input-disabled form-control min-value-money" type="text" name="min-value-money" disabled value="₦<?= $salaryMin ?? 0 ?>">
                                        <input class="form-control min-value" type="hidden" name="salary_min" value="<?= $salaryMin ?? 0 ?>">
                                        <input class="form-control max-value" type="hidden" name="salary_max" value="<?= $salaryMax ?? 15000 ?>">
                                    </div>
                                </div>
                                <div class="box-number-money">
                                    <div class="row mt-30">
                                        <div class="col-sm-6 col-6"><span class="font-sm color-brand-1">₦0</span></div>
                                        <div class="col-sm-6 col-6 text-end"><span class="font-sm color-brand-1">₦500</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-20">
                                <ul class="list-checkbox">
                                    <?php
                                    $salaryRanges = [
                                        ['min' => 0, 'max' => 0, 'label' => 'All', 'count' => $total_jobs ?? 0],
                                        ['min' => 0, 'max' => 20, 'label' => '₦0k - ₦20k', 'count' => 56],
                                        ['min' => 20, 'max' => 40, 'label' => '₦20k - ₦40k', 'count' => 37],
                                        ['min' => 40, 'max' => 60, 'label' => '₦40k - ₦60k', 'count' => 75],
                                        ['min' => 60, 'max' => 80, 'label' => '₦60k - ₦80k', 'count' => 98],
                                        ['min' => 80, 'max' => 100, 'label' => '₦80k - ₦100k', 'count' => 14],
                                        ['min' => 100, 'max' => 200, 'label' => '₦100k - ₦200k', 'count' => 25]
                                    ];
                                    foreach ($salaryRanges as $range):
                                    ?>
                                        <li>
                                            <label class="cb-container">
                                                <input type="checkbox" name="salary_range" value="<?= $range['min'] ?>-<?= $range['max'] ?>"
                                                    <?= (($salaryMin ?? 0) == $range['min'] && ($salaryMax ?? 15000) == $range['max']) ? 'checked' : '' ?>>
                                                <span class="text-small"><?= $range['label'] ?></span><span class="checkmark"></span>
                                            </label>
                                            <span class="number-item"><?= $range['count'] ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Popular Keyword Filter -->
                        <div class="filter-block mb-30">
                            <h5 class="medium-heading mb-10">Popular Keyword</h5>
                            <div class="form-group">
                                <ul class="list-checkbox">
                                    <?php if (!empty($keyword_counts)): ?>
                                        <?php foreach ($keyword_counts as $keyword => $count): ?>
                                            <li>
                                                <label class="cb-container">
                                                    <input type="checkbox" name="keywords" value="<?= esc($keyword) ?>"
                                                        <?= in_array($keyword, (array)($keywords ?? [])) ? 'checked' : '' ?>>
                                                    <span class="text-small"><?= esc($keyword) ?></span><span class="checkmark"></span>
                                                </label>
                                                <span class="number-item"><?= $count ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Position Filter -->
                        <div class="filter-block mb-30">
                            <h5 class="medium-heading mb-10">Position</h5>
                            <div class="form-group">
                                <ul class="list-checkbox">
                                    <?php if (!empty($position_counts)): ?>
                                        <?php foreach ($position_counts as $position => $count): ?>
                                            <li>
                                                <label class="cb-container">
                                                    <input type="checkbox" name="position" value="<?= esc(strtolower($position)) ?>"
                                                        <?= ($positionss ?? '') == strtolower($position) ? 'checked' : '' ?>>
                                                    <span class="text-small"><?= esc($position) ?></span><span class="checkmark"></span>
                                                </label>
                                                <span class="number-item"><?= $count ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Experience Level Filter -->
                        <div class="filter-block mb-30">
                            <h5 class="medium-heading mb-10">Experience Level</h5>
                            <div class="form-group">
                                <ul class="list-checkbox">
                                    <?php if (!empty($experience_level_counts)): ?>
                                        <?php foreach ($experience_level_counts as $level => $count): ?>
                                            <li>
                                                <label class="cb-container">
                                                    <input type="checkbox" name="experience_level_filter" value="<?= esc($level) ?>"
                                                        <?= ($experienceLevel ?? '') == $level ? 'checked' : '' ?>>
                                                    <span class="text-small"><?= ucfirst(str_replace('_', ' ', $level)) ?></span><span class="checkmark"></span>
                                                </label>
                                                <span class="number-item"><?= $count ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Onsite/Remote Filter -->
                        <div class="filter-block mb-30">
                            <h5 class="medium-heading mb-10">Onsite/Remote</h5>
                            <div class="form-group">
                                <ul class="list-checkbox">
                                    <?php if (!empty($work_arrangement_counts)): ?>
                                        <?php foreach ($work_arrangement_counts as $arrangement => $count): ?>
                                            <li>
                                                <label class="cb-container">
                                                    <input type="checkbox" name="work_arrangement" value="<?= esc($arrangement) ?>"
                                                        <?= ($workArrangement ?? '') == $arrangement ? 'checked' : '' ?>>
                                                    <span class="text-small"><?= ucfirst($arrangement) ?></span><span class="checkmark"></span>
                                                </label>
                                                <span class="number-item"><?= $count ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Job Posted Filter -->
                        <div class="filter-block mb-30">
                            <h5 class="medium-heading mb-10">Job Posted</h5>
                            <div class="form-group">
                                <ul class="list-checkbox">
                                    <li>
                                        <label class="cb-container">
                                            <input type="checkbox" name="job_posted" value="all" <?= empty($jobPosted) ? 'checked' : '' ?>>
                                            <span class="text-small">All</span><span class="checkmark"></span>
                                        </label>
                                        <span class="number-item"><?= $total_jobs ?? 0 ?></span>
                                    </li>
                                    <?php if (!empty($job_posted_counts)): ?>
                                        <?php foreach (['1_day' => '1 day', '7_days' => '7 days', '30_days' => '30 days'] as $value => $label): ?>
                                            <li>
                                                <label class="cb-container">
                                                    <input type="checkbox" name="job_posted" value="<?= $value ?>"
                                                        <?= ($jobPosted ?? '') == $value ? 'checked' : '' ?>>
                                                    <span class="text-small"><?= $label ?></span><span class="checkmark"></span>
                                                </label>
                                                <span class="number-item"><?= $job_posted_counts[$value] ?? 0 ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Job Type Filter -->
                        <div class="filter-block mb-20">
                            <h5 class="medium-heading mb-15">Job Type</h5>
                            <div class="form-group">
                                <ul class="list-checkbox">
                                    <?php if (!empty($job_type_counts)): ?>
                                        <?php foreach ($job_type_counts as $type => $count): ?>
                                            <li>
                                                <label class="cb-container">
                                                    <input type="checkbox" name="job_type" value="<?= esc($type) ?>"
                                                        <?= ($jobType ?? '') == $type ? 'checked' : '' ?>>
                                                    <span class="text-small"><?= ucfirst(str_replace('time', ' Time', $type)) ?></span><span class="checkmark"></span>
                                                </label>
                                                <span class="number-item"><?= $count ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Initialize toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000
    };

    // Humanize time function
    function humanizeTime(date) {
        const now = new Date();
        const diff = Math.floor((now - new Date(date)) / 1000);
        if (diff < 60) return `${diff} secs ago`;
        if (diff < 3600) return `${Math.floor(diff / 60)} mins ago`;
        if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;
        return `${Math.floor(diff / 86400)} days ago`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2
        $('.select-active').select2({
            width: '100%',
            minimumResultsForSearch: 10
        });

        // Initialize jQuery UI Slider
        $('#slider-range').slider({
            range: true,
            min: 0,
            max: 500,
            values: [<?= $salaryMin ?? 5000 ?>, <?= $salaryMax ?? 15000 ?>],
            slide: function(event, ui) {
                $('.min-value-money').val('₦' + ui.values[0]);
                $('.min-value').val(ui.values[0]);
                $('.max-value').val(ui.values[1]);
            },
            stop: function(event, ui) {
                updateJobList();
            }
        });
        $('.min-value-money').val('₦' + $('#slider-range').slider('values', 0));

        // Form submission
        const form = document.getElementById('job-search-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            updateJobList();
        });

        // Sidebar filters, pagination, and sorting
        document.querySelectorAll('.list-checkbox input, [name="state_id_filter"], .dropdown-item, .pager-number').forEach(element => {
            element.addEventListener('change', updateJobList);
            element.addEventListener('click', function(e) {
                if (this.tagName === 'A') {
                    e.preventDefault();
                    if (this.dataset.perPage) {
                        document.querySelector('#dropdownSort span').textContent = this.textContent;
                    }
                    if (this.dataset.sortBy) {
                        document.querySelector('#dropdownSort2 span').textContent = this.textContent;
                    }
                    updateJobList();
                }
            });
        });

        function updateJobList() {
            const params = new URLSearchParams();
            const industryId = document.querySelector('[name="industry_id"]').value;
            const stateId = document.querySelector('[name="state_id"]').value;
            const experienceLevel = document.querySelector('[name="experience_level"]').value;
            const salaryMin = document.querySelector('[name="salary_min"]').value;
            const salaryMax = document.querySelector('[name="salary_max"]').value;
            const keywords = Array.from(document.querySelectorAll('[name="keywords"]:checked')).map(cb => cb.value);
            const position = Array.from(document.querySelectorAll('[name="position"]:checked')).map(cb => cb.value)[0];
            const workArrangement = Array.from(document.querySelectorAll('[name="work_arrangement"]:checked')).map(cb => cb.value)[0];
            const jobType = Array.from(document.querySelectorAll('[name="job_type"]:checked')).map(cb => cb.value)[0];
            const jobPosted = Array.from(document.querySelectorAll('[name="job_posted"]:checked')).map(cb => cb.value)[0];
            const perPage = document.querySelector('#dropdownSort span')?.textContent || '12';
            const sortBy = document.querySelector('#dropdownSort2 span')?.textContent.toLowerCase().replace(' post', '') || 'newest';
            const page = document.querySelector('.pager-number.active')?.dataset.page || '1';

            if (industryId) params.append('industry_id', industryId);
            if (stateId) params.append('state_id', stateId);
            if (experienceLevel) params.append('experience_level', experienceLevel);
            if (salaryMin) params.append('salary_min', salaryMin);
            if (salaryMax) params.append('salary_max', salaryMax);
            if (keywords.length) params.append('keywords', keywords.join(','));
            if (position) params.append('position', position);
            if (workArrangement) params.append('work_arrangement', workArrangement);
            if (jobType) params.append('job_type', jobType);
            if (jobPosted && jobPosted !== 'all') params.append('job_posted', jobPosted);
            params.append('per_page', perPage);
            params.append('sort_by', sortBy);
            params.append('page', page);

            const searchBtn = document.getElementById('search-btn');
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Searching...';
            searchBtn.setAttribute('disabled', 'disabled');

            fetch(`<?= site_url('jobs') ?>?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    searchBtn.innerHTML = 'Search';
                    searchBtn.removeAttribute('disabled');
                    if (data.status === 'success') {
                        const jobList = document.getElementById('job-list');
                        jobList.innerHTML = '';
                        if (data.jobs.length === 0) {
                            jobList.innerHTML = '<div class="col-12 text-center"><p>No jobs found. Try adjusting your search criteria.</p></div>';
                        } else {
                            data.jobs.forEach(job => {
                                const jobCard = `
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                    <div class="card-grid-2 hover-up">
                                        <div class="card-grid-2-image-left">
                                            <span class="flash ${job.job_type === 'freelance' ? 'bg-green' : ''}">
                                                <span>${job.job_type.charAt(0).toUpperCase() + job.job_type.slice(1)}</span>
                                            </span>
                                            <div class="image-box">
                                                <img src="${job.company_logo ? '<?= base_url() ?>' + job.company_logo : '<?= base_url($website_logo) ?>'}" alt="JobberRecruit" class="img-fluid company-logo">
                                            </div>
                                            <div class="right-info">
                                                <a class="name-job" href="<?= site_url('company') ?>/${job.employer_id}">${job.employer_name}</a>
                                                <span class="location-small">${job.location || 'Remote'}</span>
                                            </div>
                                        </div>
                                        <div class="card-block-info">
                                            <h6><a href="<?= site_url('jobs/view') ?>/${job.id}">${job.title}</a></h6>
                                            <div class="mt-5">
                                                <span class="card-briefcase">${job.job_type.charAt(0).toUpperCase() + job.job_type.slice(1)}</span>
                                                <span class="card-time">${humanizeTime(job.created_at)}</span>
                                            </div>
                                            <p class="font-sm color-text-paragraph mt-15">${job.description.substring(0, 100)}...</p>
                                            <div class="mt-30">
                                                ${job.skills ? job.skills.split(',').map(skill => `<a class="btn btn-grey-small mr-5" href="<?= site_url('jobs?skill=') ?>${encodeURIComponent(skill.trim())}">${skill.trim()}</a>`).join('') : ''}
                                            </div>
                                            <div class="card-2-bottom mt-30">
                                                <div class="row">
                                                    <div class="col-lg-7 col-7">
                                                        <span class="card-text-price">${job.salary || 'Negotiable'}</span>
                                                        <span class="text-muted">${job.salary ? '/' + job.salary_period : ''}</span>
                                                    </div>
                                                    <div class="col-lg-5 col-5 text-end">
                                                        <div class="btn btn-apply-now" data-bs-toggle="modal" data-bs-target="#ModalApplyJobForm">Apply now</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                                jobList.insertAdjacentHTML('beforeend', jobCard);
                            });
                        }
                        // Update pagination
                        const totalPages = Math.ceil(data.total_jobs / parseInt(perPage));
                        const pager = document.querySelector('.paginations .pager');
                        pager.innerHTML = `
                        <li><a class="pager-prev" href="#" ${parseInt(page) <= 1 ? 'style="pointer-events: none; opacity: 0.5;"' : ''}></a></li>
                        ${Array.from({length: totalPages}, (_, i) => ` <
                            li > < a class = "pager-number ${i + 1 == page ? 'active' : ''}"
                        href = "#"
                        data - page = "${i + 1}" > $ {
                                i + 1
                            } < /a></li >
                            `).join('')}
                        <li><a class="pager-next" href="#" ${parseInt(page) >= totalPages ? 'style="pointer-events: none; opacity: 0.5;"' : ''}></a></li>
                    `;
                        // Update showing text
                        document.querySelector('.text-showing').innerHTML = `Showing <strong>${(parseInt(page) - 1) * parseInt(perPage) + 1}-${Math.min(parseInt(page) * parseInt(perPage), data.total_jobs)}</strong> of <strong>${data.total_jobs}</strong> jobs`;
                        toastr.success('Jobs updated successfully!');
                    } else {
                        toastr.error('No jobs found.');
                    }
                })
                .catch(error => {
                    searchBtn.innerHTML = 'Search';
                    searchBtn.removeAttribute('disabled');
                    toastr.error('Failed to load jobs.');
                    console.error(error);
                });
        }
    });
</script>
<?= $this->endSection() ?>