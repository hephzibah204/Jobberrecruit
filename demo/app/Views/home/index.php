<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "JobberRecruit",
        "url": "<?= base_url() ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": {
                "@type": "EntryPoint",
                "urlTemplate": "<?= base_url('jobs') ?>?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        },
        "description": "Find your dream job with JobberRecruit - Nigeria's leading job portal connecting job seekers with top employers."
    }
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section py-5 position-relative overflow-hidden" style="background: var(--midnight-bg) !important;">
    <div class="container position-relative z-1">
        <div class="row align-items-center min-vh-80">
            <!-- Text Content -->
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="animate-fade-in">
                    <!-- Trust Badge -->
                    <div class="d-flex align-items-center gap-3 mb-4 d-none d-md-flex d-lg-flex">
                        <div class="trust-badge bg-light-primary rounded-pill px-3 py-2">
                            <i class="bi bi-shield-check me-2 text-primary"></i>
                            <span class="fw-medium">Trusted by 38,000+ Professionals</span>
                        </div>
                        <div class="trust-badge bg-light-success rounded-pill px-3 py-2">
                            <i class="bi bi-award me-2 text-success"></i>
                            <span class="fw-medium">Award Winning Platform</span>
                        </div>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="display-4 fw-bold mb-4 text-main">
                        Find Your Dream Job in
                        <span class="text-gradient">Nigeria</span>
                    </h1>

                    <!-- Subheading -->
                    <p class="lead text-muted mb-5">
                        Connect with top employers, discover career opportunities that match your skills,
                        and take the next step in your professional journey.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-search me-2"></i>Find Jobs
                        </a>
                        <a href="<?= base_url('register') ?>" class="btn btn-outline-primary btn-lg px-4">
                            <i class="bi bi-person-plus me-2"></i>Register Free
                        </a>
                    </div>

                    <!-- Quick Stats -->
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <div class="stat-item">
                            <div class="stat-number fw-bold text-primary">95%</div>
                            <div class="stat-label text-muted small">Success Rate</div>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <div class="stat-number fw-bold text-success">24h</div>
                            <div class="stat-label text-muted small">Quick Apply</div>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <div class="stat-number fw-bold text-warning">1-Click</div>
                            <div class="stat-label text-muted small">Easy Application</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Content - Right Side -->
            <div class="col-lg-5">
                <div class="position-relative animate-slide-in-right">
                    <!-- Main Search Card -->
                    <div class="glass-card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-header py-3" style="background: rgba(14, 165, 233, 0.1) !important;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-search fs-4 me-2"></i>
                                <h5 class="fw-bold mb-0 text-white">Search Jobs</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form class="row g-3" method="GET" action="<?= base_url('jobs') ?>" id="job-search-form">
                                <!-- Keyword -->
                                <div class="col-12">
                                    <label class="form-label fw-medium mb-2">
                                        <i class="bi bi-briefcase me-1" aria-hidden="true"></i>Job Title or Keyword
                                    </label>
                                    <div class="input-group">
                                        <input type="text"
                                            name="q"
                                            class="form-control"
                                            placeholder="e.g. Software Engineer, Marketing Manager"
                                            value="<?= esc($q ?? '') ?>">
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="col-12">
                                    <label class="form-label fw-medium mb-2">
                                        <i class="bi bi-geo-alt me-1"></i>Location
                                    </label>
                                    <select class="form-select searchable-select" name="state_id" id="state">
                                        <option value="">All Locations</option>
                                        <?php foreach ($states as $state): ?>
                                            <option value="<?= esc($state->id) ?>"><?= esc($state->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Category -->
                                <div class="col-12">
                                    <label class="form-label fw-medium mb-2">
                                        <i class="bi bi-grid me-1"></i>Category
                                    </label>
                                    <select class="form-select searchable-select" name="industry_id" id="industry">
                                        <option value="">All Categories</option>
                                        <?php foreach ($industries as $industry): ?>
                                            <option value="<?= esc($industry->id) ?>"><?= esc($industry->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Job Type -->
                                <div class="col-12">
                                    <fieldset>
                                        <legend class="form-label fw-medium mb-2">
                                            <i class="bi bi-clock me-1" aria-hidden="true"></i>Job Type
                                        </legend>
                                        <div class="d-flex flex-wrap gap-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="job_type[]" value="full_time" id="fullTime">
                                                <label class="form-check-label small" for="fullTime">Full Time</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="job_type[]" value="part_time" id="partTime">
                                                <label class="form-check-label small" for="partTime">Part Time</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="job_type[]" value="remote" id="remote">
                                                <label class="form-check-label small" for="remote">Remote</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="job_type[]" value="contract" id="contract">
                                                <label class="form-check-label small" for="contract">Contract</label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <!-- Search Button -->
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary w-100 btn-lg" id="search-btn" aria-label="Search jobs">
                                        <i class="bi bi-search me-2" aria-hidden="true"></i>Search Verified Jobs
                                    </button>
                                </div>
                            </form>

                            <!-- Quick Links -->
                            <div class="mt-4 pt-3 border-top">
                                <p class="small text-muted mb-2">Popular searches:</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="<?= base_url('jobs?q=software+developer') ?>" class="badge bg-light text-dark text-decoration-none small">
                                        Software Developer
                                    </a>
                                    <a href="<?= base_url('jobs?q=data+analyst') ?>" class="badge bg-light text-dark text-decoration-none small">
                                        Data Analyst
                                    </a>
                                    <a href="<?= base_url('jobs?q=project+manager') ?>" class="badge bg-light text-dark text-decoration-none small">
                                        Project Manager
                                    </a>
                                    <a href="<?= base_url('jobs?q=marketing') ?>" class="badge bg-light text-dark text-decoration-none small">
                                        Marketing
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Testimonial -->
                    <?php if (!empty($testimonials) && count($testimonials) > 0):
                        $t = $testimonials[array_rand($testimonials)]; ?>
                        <div class="position-absolute bottom-0 end-0 mb-3 me-3 d-none d-lg-block">
                            <div class="card border-0 shadow-sm testimonial-card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <img src="<?= esc($t->avatar ?? base_url('images/default-company.png')) ?>"
                                                class="rounded-circle"
                                                width="40"
                                                height="40"
                                                alt="<?= esc($t->name ?? 'Job Seeker') ?>"
                                                loading="lazy"
                                                onerror="this.src='<?= base_url('images/default-company.png') ?>'">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="small mb-2">
                                                <i class="bi bi-quote text-primary" aria-hidden="true"></i>
                                                <?= esc($t->content ?? 'Found a great job through this platform!') ?>
                                            </p>
                                            <div class="d-flex align-items-center">
                                                <div class="stars small">
                                                    <i class="bi bi-star-fill text-warning" aria-hidden="true"></i>
                                                    <i class="bi bi-star-fill text-warning" aria-hidden="true"></i>
                                                    <i class="bi bi-star-fill text-warning" aria-hidden="true"></i>
                                                    <i class="bi bi-star-fill text-warning" aria-hidden="true"></i>
                                                    <i class="bi bi-star-fill text-warning" aria-hidden="true"></i>
                                                </div>
                                                <span class="text-muted small ms-2">- <?= esc($t->name ?? 'Job Seeker') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Pattern -->
    <div class="position-absolute bottom-0 start-0 w-100 h-50 bg-light-primary opacity-10"></div>
    <div class="position-absolute top-0 end-0 w-25 h-25 opacity-10">
        <svg width="100%" height="100%" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 0C44.8 0 0 44.8 0 100s44.8 100 100 100 100-44.8 100-100S155.2 0 100 0zm0 180c-44.2 0-80-35.8-80-80s35.8-80 80-80 80 35.8 80 80-35.8 80-80 80z" fill="#0D609E" />
        </svg>
    </div>
</section>

<?php if (false): ?>
<!-- Global Stats Bar -->
<section class="py-4 border-top border-bottom" style="background: rgba(14, 165, 233, 0.05) !important; border-color: var(--border) !important;">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="counter text-primary display-6 mb-1" data-count="<?= esc($activeJobsCount ?? 0) ?>"><?= number_format($activeJobsCount ?? 0) ?></div>
                <div class="text-muted small fw-medium text-uppercase">Active Jobs</div>
            </div>
            <div class="col-6 col-md-3 border-start-md border-secondary-subtle">
                <div class="counter text-success display-6 mb-1" data-count="<?= esc($verifiedEmployersCount ?? 0) ?>"><?= number_format($verifiedEmployersCount ?? 0) ?></div>
                <div class="text-muted small fw-medium text-uppercase">Verified Employers</div>
            </div>
            <div class="col-6 col-md-3 border-start-md border-secondary-subtle">
                <div class="counter text-info display-6 mb-1" data-count="<?= esc($monthlyApplicantsCount ?? 0) ?>"><?= number_format($monthlyApplicantsCount ?? 0) ?></div>
                <div class="text-muted small fw-medium text-uppercase">Monthly Applicants</div>
            </div>
            <div class="col-6 col-md-3 border-start-md border-secondary-subtle">
                <div class="text-warning display-6 mb-1"><span class="counter d-inline-block" data-count="<?= esc($placementSuccess ?? 95) ?>"><?= number_format($placementSuccess ?? 95) ?></span>%</div>
                <div class="text-muted small fw-medium text-uppercase">Placement Success</div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Ad-Res -->
<ins class="adsbygoogle"
    style="display:block"
    data-ad-client="ca-pub-3464186884176173"
    data-ad-slot="6229476516"
    data-ad-format="auto"
    data-full-width-responsive="true"></ins>
<script>
    if (window.adsbygoogle && document.querySelector('.adsbygoogle')) {
        try { (adsbygoogle = window.adsbygoogle || []).push({}); } catch (e) { console.warn('Adsense push error'); }
    }
</script>

<!-- Featured Jobs -->
<section class="py-5">
    <div class="container">
        <div class="section-header mb-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="display-5 fw-bold mb-3 text-gradient">Recent Jobs</h2>
                    <p class="lead text-muted">
                        Hand-picked opportunities from top companies
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="<?= base_url('jobs') ?>" class="btn btn-outline-primary btn-lg rounded-pill">
                        All Featured Jobs <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="glass-card p-4 h-100 position-relative">
                                <!-- Featured Badge -->
                                <?php if ($job->is_featured ?? false): ?>
                                    <div class="position-absolute top-0 start-0 ms-3">
                                        <span class="badge bg-warning">
                                            <i class="bi bi-star-fill me-1"></i>Featured
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- Job Title & Save -->
                                 <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="fw-bold mb-0">
                                        <a href="<?= base_url('jobs/' . $job->slug) ?>" class="text-decoration-none">
                                            <?= esc($job->title) ?>
                                        </a>
                                        <?php if ($job->show_trust_badge): ?>
                                            <span class="badge-verified ms-2" title="Verified Employer">
                                                <i class="bi bi-patch-check-fill"></i>
                                            </span>
                                        <?php endif; ?>
                                    </h5>
                                </div>

                                <!-- Job Type & Salary -->
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <?php
                                    $typeClass = match (strtolower($job->job_type)) {
                                        'full_time', 'full-time' => 'bg-success text-white',
                                        'part_time', 'part-time' => 'bg-success-subtle text-success',
                                        'contract' => 'bg-info-subtle text-info',
                                        'internship' => 'bg-warning-subtle text-warning',
                                        'remote' => 'bg-primary-subtle text-primary',
                                        default => 'bg-secondary text-white'
                                    };
                                    ?>
                                    <span class="badge <?= $typeClass ?>">
                                        <?= ucwords(str_replace('_', ' ', $job->job_type)) ?>
                                    </span>
                                    <span class="text-muted small">
                                        <i class="bi bi-cash-stack me-1"></i>
                                        <?= $job->salary ?? 'Negotiable' ?>/<?= $job->salary_period ?>
                                    </span>
                                </div>

                                <!-- Company Info -->
                                <?php if (!empty($job->anonymous) || !empty($job->is_anonymous)): ?>
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="company-logo-container me-3">
                                            <img src="<?= base_url('images/favicon.png') ?>"
                                                alt="Anonymous Employer"
                                                class="img-fluid rounded-2"
                                                loading="lazy">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-semibold mb-0">Confidential Employer</h6>
                                            <p class="text-muted small mb-0">Anonymous job posting</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="company-logo-container me-3">
                                            <img src="<?= esc($job->company_logo) ?>"
                                                alt="<?= esc($job->industry_name ?? 'Company') ?>"
                                                class="img-fluid rounded-2"
                                                loading="lazy"
                                                onerror="this.src='<?= base_url('images/default-company.png') ?>'">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-semibold mb-0"><?= esc($job->industry_name ?? 'Unknown Company') ?></h6>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                <?= esc($job->location ?? 'Location not specified') ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Apply Button -->
                                <a href="<?= base_url('jobs/' . $job->slug) ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-send me-2"></i>Apply Now
                                </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="display-1 text-muted mb-3">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <h4 class="fw-bold mb-3">No jobs available</h4>
                    <p class="text-muted mb-4">Check back soon for new opportunities</p>
                    <a href="<?= base_url('jobs') ?>" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Browse All Jobs
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Discovery Phase: Companies & Categories -->
<section class="py-5" style="background: var(--midnight-bg) !important;">
    <div class="container">
        <!-- Top Hiring Companies -->
        <?php if (false): ?>
        <div class="section-header mb-5 border-0">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="display-5 fw-bold mb-3 text-gradient">Top Hiring Companies</h2>
                    <p class="lead text-muted">Join these leading organizations</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="<?= base_url('jobs') ?>" class="btn btn-outline-primary btn-lg rounded-pill">
                        View All <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <?php if (!empty($top_companies)): ?>
                <?php foreach ($top_companies as $company): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="glass-card h-100 hover-lift border-0">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="company-logo-container me-3 flex-shrink-0 shadow-sm" style="background: white;">
                                        <img src="<?= esc($company->logo) ?>" alt="<?= esc($company->company_name) ?>" class="img-fluid rounded-3" loading="lazy" onerror="this.src='<?= base_url('images/default-company.png') ?>'">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-1 text-main"><?= esc($company->company_name) ?>
                                            <?php if ($company->show_trust_badge): ?>
                                                <i class="bi bi-patch-check-fill text-primary ms-1"></i>
                                            <?php endif; ?>
                                        </h5>
                                        <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i><?= esc($company->location ?? 'Multiple Locations') ?></p>
                                        <span class="text-primary small fw-bold"><?= esc($company->formatted_count) ?> Openings</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Popular Categories -->
        <div class="section-header mb-5 border-0 pt-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="display-5 fw-bold mb-3 text-gradient">Browse by Category</h2>
                    <p class="lead text-muted">Find jobs in your field of expertise</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <?php if (!empty($categories)): ?>
                <?php foreach (array_slice($categories, 0, 8) as $index => $category): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="<?= esc($category->url) ?>" class="text-decoration-none">
                            <div class="glass-card p-4 text-center h-100 hover-lift">
                                <div class="icon-wrapper rounded-circle p-3 mx-auto mb-4" style="background: rgba(14, 165, 233, 0.15); border: 1px solid rgba(14, 165, 233, 0.3); width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                    <i class="<?= $category->icon ?? 'bi-grid' ?> text-white fs-2" style="text-shadow: 0 0 15px rgba(14, 165, 233, 0.8);"></i>
                                </div>
                                <h6 class="fw-bold text-main mb-2"><?= esc($category->name) ?></h6>
                                <p class="text-muted small mb-0"><?= esc($category->formatted_count) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Top Locations -->
        <div class="section-header mb-5 border-0 pt-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="display-5 fw-bold mb-3 text-gradient">Browse by Location</h2>
                    <p class="lead text-muted">Find jobs in top cities and regions</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <?php
            $locationItems = !empty($top_locations) ? $top_locations : array_slice((array)$states, 0, 8);
            foreach ($locationItems as $location):
                if (isset($location->url)) {
                    $locUrl   = $location->url;
                    $locCount = $location->formatted_count;
                } else {
                    $slugPart = !empty($location->slug) ? $location->slug : strtolower(str_replace(' ', '-', $location->name)) . '-state';
                    $locUrl   = base_url('jobs-in-' . $slugPart);
                    $locCount = 'View jobs';
                }
            ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?= esc($locUrl) ?>" class="text-decoration-none">
                        <div class="glass-card p-4 text-center h-100 hover-lift">
                            <div class="icon-wrapper rounded-circle p-3 mx-auto mb-4" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-geo-alt text-success fs-2" style="text-shadow: 0 0 15px rgba(16, 185, 129, 0.8);"></i>
                            </div>
                            <h6 class="fw-bold text-main mb-2"><?= esc($location->name) ?></h6>
                            <p class="text-muted small mb-0"><?= esc($locCount) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Educational Phase: How It Works -->
<section class="py-5" style="background: #f8fafc !important;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3 text-gradient">How JobberRecruit Works</h2>
            <p class="lead text-muted col-lg-8 mx-auto">Four simple steps to land your dream job</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-6 col-lg-3">
                <div class="glass-card h-100 border-0 hover-lift">
                    <div class="card-body p-4">
                        <i class="bi bi-person-plus text-primary fs-1 mb-4 d-block"></i>
                        <h5 class="fw-bold mb-3 text-main">Create Account</h5>
                        <p class="text-muted mb-0 small">Sign up in seconds with your email or social account</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="glass-card h-100 border-0 hover-lift">
                    <div class="card-body p-4">
                        <i class="bi bi-cloud-arrow-up text-success fs-1 mb-4 d-block"></i>
                        <h5 class="fw-bold mb-3 text-main">Upload CV</h5>
                        <p class="text-muted mb-0 small">Upload your resume or build one with our CV builder</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="glass-card h-100 border-0 hover-lift">
                    <div class="card-body p-4">
                        <i class="bi bi-search text-info fs-1 mb-4 d-block"></i>
                        <h5 class="fw-bold mb-3 text-main">Find Jobs</h5>
                        <p class="text-muted mb-0 small">Browse thousands of opportunities matching your profile</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="glass-card h-100 border-0 hover-lift">
                    <div class="card-body p-4">
                        <i class="bi bi-send-check text-warning fs-1 mb-4 d-block"></i>
                        <h5 class="fw-bold mb-3 text-main">Apply & Track</h5>
                        <p class="text-muted mb-0 small">Apply with one click and track your applications</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Conversion Phase: Employer CTA -->
<section class="employer-cta-section py-5 overflow-hidden position-relative">
    <!-- Decorative blobs -->
    <div class="cta-blob cta-blob-1"></div>
    <div class="cta-blob cta-blob-2"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start mb-5 mb-lg-0">
                <span class="cta-eyebrow d-inline-block mb-3">For Employers</span>
                <h2 class="display-4 fw-bold text-white mb-4">Hire the Best Talent <span class="cta-highlight">in Minutes</span></h2>
                <p class="lead mb-0" style="color: rgba(255,255,255,0.75);">
                    Post your job for free and reach thousands of verified candidates across Nigeria.
                    Start building your dream team today.
                </p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <div class="d-flex align-items-center gap-2 cta-stat">
                        <i class="bi bi-people-fill text-warning fs-5"></i>
                        <span style="color:rgba(255,255,255,0.85);">1.2M+ Candidates</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 cta-stat">
                        <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
                        <span style="color:rgba(255,255,255,0.85);">Hire in 24 Hours</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 cta-stat">
                        <i class="bi bi-shield-check-fill text-warning fs-5"></i>
                        <span style="color:rgba(255,255,255,0.85);">Verified Profiles</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="cta-action-card">
                    <div class="mb-4">
                        <i class="bi bi-megaphone-fill" style="font-size: 3rem; color: #facc15;"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-3">Ready to Hire?</h4>
                    <p class="mb-4" style="color:rgba(255,255,255,0.7); font-size: 0.9rem;">Join 38,000+ employers who've found their perfect hire on JobberRecruit.</p>
                    <a href="<?= base_url('employer/post-job') ?>" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill shadow-lg mb-3">
                        <i class="bi bi-megaphone me-2"></i>Post a Job Free
                    </a>
                    <a href="<?= base_url('pricing') ?>" class="btn btn-outline-light btn-sm w-100 rounded-pill">
                        View Pricing Plans
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Homepage Custom Styles */

    .hero-section {
        background: var(--midnight-bg) !important;
        position: relative;
        overflow: hidden;
    }

    .min-vh-80 {
        min-height: 80vh;
    }

    .text-gradient-primary {
        background: linear-gradient(90deg, #0D609E 0%, #0D609E 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Trust Badges */
    .trust-badge {
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .trust-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .bg-light-primary {
        background-color: rgba(14, 165, 233, 0.1);
    }

    .bg-light-success {
        background-color: rgba(34, 197, 94, 0.1);
    }

    /* Stats */
    .stat-item {
        text-align: center;
        min-width: 80px;
    }

    .stat-number {
        font-size: 1.5rem;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .stat-divider {
        width: 1px;
        height: 40px;
        background-color: #e9ecef;
        align-self: center;
    }

    /* Search Form */
    .form-label {
        color: #0D609E;
        font-size: 0.9rem;
    }

    .form-select,
    .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #0D609E;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Job Type Checkboxes */
    .form-check {
        margin-bottom: 0;
    }

    .form-check-input:checked {
        background-color: #0D609E;
        border-color: #0D609E;
    }

    /* Testimonial Card */
    .testimonial-card {
        width: 280px;
        animation: float 6s ease-in-out infinite;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .stars {
        color: #ffc107;
        font-size: 0.875rem;
    }

    /* Stats Bar */
    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .counter {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(90deg, #1a202c, #0D609E);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Animations */
    .animate-fade-in {
        animation: fadeIn 1s ease-out;
    }

    .animate-slide-in-right {
        animation: slideInRight 1s ease-out;
    }

    .animate-fade-in-delay {
        animation: fadeIn 1s ease-out 0.5s both;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.25rem;
        }

        .min-vh-80 {
            min-height: auto;
        }

        .hero-section {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        .trust-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .counter {
            font-size: 2rem;
        }

        .testimonial-card {
            display: none;
        }
    }

    /* Quick Links Badges */

    /* Background Elements */
    .opacity-10 {
        opacity: 0.1;
    }

    .card-header.bg-primary {
        background: linear-gradient(135deg, #0D609E 0%, #0D609E 100%) !important;
    }

    /* Input Group Styling */
    .input-group:focus-within {
        border-radius: 8px;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Floating Stats */
    .floating-stat {
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Cards */
    .card {
        border-radius: 12px !important;
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    /* Step Numbers */
    .step-number {
        color: #0D609E;
        font-size: 3rem;
        font-weight: 800;
        opacity: 0.1;
        position: absolute;
        top: -20px;
        left: 20px;
    }

    /* Company Logos */
    .company-logo-container {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        border: 1px solid #e9ecef;
    }

    .company-logo-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Testimonials */
    .testimonial-text {
        font-style: italic;
        line-height: 1.7;
    }

    /* Section Headers */
    .section-header {
        border-bottom: 2px solid #f1f3f5;
        padding-bottom: 1.5rem;
    }

    /* Counter Animation */

    /* CTA Section */
    .bg-primary {
        background: linear-gradient(135deg, #0D609E 0%, #0D609E 100%) !important;
    }

    /* Background Elements */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0D609E 0%, #0D609E 100%);
    }

    /* Icon Wrappers */
    .icon-wrapper {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .display-3 {
            font-size: 2.5rem;
        }

        .display-5 {
            font-size: 2rem;
        }

        .display-6 {
            font-size: 1.75rem;
        }

        .counter {
            font-size: 2.5rem;
        }

        .hero-section {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
    }

    /* Skeleton Animation */
    .placeholder-glow .placeholder {
        animation: placeholder-glow 2s ease-in-out infinite;
    }

    @keyframes placeholder-glow {

        0%,
        100% {
            opacity: 0.6;
        }

        50% {
            opacity: 1;
        }
    }

    /* Save Job Button */
    .save-job-btn {
        transition: all 0.3s ease;
    }

    .save-job-btn:hover {
        transform: scale(1.1);
    }

    /* Quick Search Tags */
    .badge.bg-light:hover {
        background-color: #0D609E !important;
        color: white !important;
    }

    /* Force Select2 to behave exactly like Bootstrap input */
    .select2-container--bootstrap-5 {
        width: 100% !important;
    }

    .select2-container--bootstrap-5 .select2-selection {
        height: 38px !important;
        min-height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        background-color: #fff !important;

        /* KEY FIX */
        display: flex !important;
        align-items: center !important;
    }

    /* Ensure selected text stays centered */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        display: flex !important;
        align-items: center !important;
        height: 100% !important;
        padding-left: 12px !important;
        padding-right: 32px !important;
        line-height: normal !important;
    }

    /* Fix arrow alignment */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        top: 0 !important;
        right: 8px !important;
    }

    /* Icon alignment inside selected value */
    .select2-selection__rendered i {
        margin-right: 8px;
        line-height: 1;
    }

    /* Focus state */
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }


    /* Dropdown styling */
    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        z-index: 1060 !important;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown {
        padding: 8px !important;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        padding: 6px 12px !important;
        font-size: 14px !important;
    }

    /* Results styling */
    .select2-container--bootstrap-5 .select2-results__option {
        padding: 8px 12px !important;
        font-size: 14px !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--selected {
        background-color: #0d6efd !important;
        color: white !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #e9ecef !important;
        color: #212529 !important;
    }

    /* Make sure it matches your select styling */
    .select.select2-hidden-accessible {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    /* Fix for icons */
    .select2-selection__rendered i {
        margin-right: 8px !important;
        vertical-align: middle !important;
    }

    /* Match the height with other form controls */
    .select2-container--bootstrap-5+.select2-container--bootstrap-5 {
        margin-top: 0 !important;
    }

    /* Ensure proper alignment in form groups */
    .mb-3 .select2-container--bootstrap-5 {
        margin-bottom: 0 !important;
    }

    /* Clear button styling */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
        margin-right: 25px !important;
        height: 36px !important;
        line-height: 36px !important;
    }

    /* Make sure it doesn't break the layout */
    .select2-container {
        display: block !important;
    }

    /* =============================================
       HOW IT WORKS — Light Section
    ============================================= */
    /* Clean white/light bg, original step card look */

    /* =============================================
       EMPLOYER CTA SECTION — Vibrant Redesign
    ============================================= */
    .employer-cta-section {
        background: linear-gradient(135deg, #0c4a6e 0%, #0D609E 40%, #312e81 100%) !important;
        padding: 80px 0;
        position: relative;
    }

    /* Decorative animated blobs */
    .cta-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(70px);
        opacity: 0.25;
        pointer-events: none;
        animation: blobFloat 8s ease-in-out infinite;
    }

    .cta-blob-1 {
        width: 420px;
        height: 420px;
        background: radial-gradient(circle, #38bdf8, transparent);
        top: -120px;
        left: -80px;
        animation-delay: 0s;
    }

    .cta-blob-2 {
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, #818cf8, transparent);
        bottom: -80px;
        right: -60px;
        animation-delay: 4s;
    }

    @keyframes blobFloat {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33%       { transform: translate(20px, -30px) scale(1.05); }
        66%       { transform: translate(-15px, 15px) scale(0.97); }
    }

    /* Eyebrow label */
    .cta-eyebrow {
        background: rgba(56, 189, 248, 0.2);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.4);
        border-radius: 50px;
        padding: 6px 18px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    /* Animated highlight on heading */
    .cta-highlight {
        background: linear-gradient(90deg, #facc15, #fb923c);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Stats pills */
    .cta-stat {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 50px;
        padding: 6px 14px;
        backdrop-filter: blur(6px);
        transition: background 0.3s;
    }

    .cta-stat:hover {
        background: rgba(255,255,255,0.15);
    }

    /* Glassmorphic action card */
    .cta-action-card {
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 24px;
        backdrop-filter: blur(20px);
        padding: 40px 36px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .cta-action-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 35px 60px rgba(0,0,0,0.4);
    }

    /* Warning CTA button pulse */
    .employer-cta-section .btn-warning {
        background: linear-gradient(135deg, #facc15, #f59e0b) !important;
        border: none;
        color: #1a1a1a !important;
        font-size: 1rem;
        letter-spacing: 0.02em;
        box-shadow: 0 8px 24px rgba(250, 204, 21, 0.35);
        transition: all 0.3s ease;
    }

    .employer-cta-section .btn-warning:hover {
        background: linear-gradient(135deg, #fde047, #facc15) !important;
        box-shadow: 0 12px 32px rgba(250, 204, 21, 0.5);
        transform: translateY(-2px);
    }

    .employer-cta-section .btn-outline-light {
        border-color: rgba(255,255,255,0.4);
        color: rgba(255,255,255,0.75);
        transition: all 0.3s ease;
    }

    .employer-cta-section .btn-outline-light:hover {
        background: rgba(255,255,255,0.12);
        border-color: rgba(255,255,255,0.7);
        color: #fff;
    }

    @media (max-width: 991px) {
        .employer-cta-section { padding: 60px 0; }
        .cta-action-card { padding: 28px 20px; }
        .cta-blob { display: none; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter animation — reads target from data-count, always starts from 0
        const counters = document.querySelectorAll('.counter');

        function animateCounter(el) {
            const raw = el.getAttribute('data-count');
            const target = parseInt(String(raw).replace(/,/g, ''), 10) || 0;
            const duration = 1800; // ms
            const startTime = performance.now();

            el.innerText = '0';

            function step(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                // Ease-out cubic
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(eased * target);
                el.innerText = current.toLocaleString();
                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    el.innerText = target.toLocaleString();
                }
            }

            requestAnimationFrame(step);
        }

        // Intersection Observer — fires when counter scrolls into view
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        counters.forEach(counter => {
            observer.observe(counter);
        });

        // Initialize searchable dropdowns
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.searchable-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: function() {
                    return $(this).attr('id') === 'state' ?
                        'Search location…' :
                        'Search industry…';
                },
                allowClear: false
            });
        }

        // Form submission with loading state
        const form = document.getElementById('job-search-form');
        const searchBtn = document.getElementById('search-btn');

        if (form && searchBtn) {
            form.addEventListener('submit', function(event) {
                searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Searching...';
                searchBtn.disabled = true;

                // Remove disabled state after form submits
                setTimeout(() => {
                    searchBtn.disabled = false;
                    searchBtn.innerHTML = '<i class="bi bi-search me-2" aria-hidden="true"></i>Search Verified Jobs';
                }, 2000);
            });
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Quick links badges hover effect
        document.querySelectorAll('.badge.bg-light').forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Hide skeleton and show content
        setTimeout(() => {
            const skeleton = document.getElementById('vacancySkeleton');
            const grid = document.getElementById('vacanciesGrid');

            if (skeleton) skeleton.style.display = 'none';
            if (grid) {
                grid.classList.remove('d-none');
                grid.classList.add('animate-fade-in');
            }
        }, 1000);

        // Save job functionality
        document.querySelectorAll('.save-job-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const jobId = this.getAttribute('data-job-id');
                const icon = this.querySelector('i');

                const isSaved = icon.classList.contains('bi-bookmark-fill');
                const csrfName = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || 'X-CSRF-TOKEN';
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                fetch('<?= base_url('jobs/toggle-save') ?>/' + jobId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        [csrfName]: csrfToken
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.saved) {
                        icon.classList.remove('bi-bookmark');
                        icon.classList.add('bi-bookmark-fill', 'text-primary');
                        showToast(data.message || 'Job saved to favorites', 'success');
                    } else if (data.success && !data.saved) {
                        icon.classList.remove('bi-bookmark-fill', 'text-primary');
                        icon.classList.add('bi-bookmark');
                        showToast(data.message || 'Job removed from favorites', 'info');
                    } else {
                        showToast(data.message || 'An error occurred', 'danger');
                    }
                })
                .catch(() => {
                    // Revert icon on failure
                    if (isSaved) {
                        icon.classList.remove('bi-bookmark-fill', 'text-primary');
                        icon.classList.add('bi-bookmark');
                    } else {
                        icon.classList.remove('bi-bookmark');
                        icon.classList.add('bi-bookmark-fill', 'text-primary');
                    }
                    showToast('Could not save job. Please try again.', 'danger');
                });
            });
        });

        // Show toast function
        function showToast(message, type = 'info') {
            const existing = document.querySelector('.jr-toast');
            if (existing) existing.remove();

            const colors = { success: '#10b981', danger: '#ef4444', info: '#3b82f6', warning: '#f59e0b' };
            const toast = document.createElement('div');
            toast.className = 'jr-toast';
            toast.innerHTML = message;
            Object.assign(toast.style, {
                position: 'fixed', bottom: '24px', right: '24px', zIndex: '99999',
                background: colors[type] || '#3b82f6', color: '#fff',
                padding: '12px 24px', borderRadius: '8px', fontSize: '14px',
                fontWeight: '500', boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                opacity: '0', transform: 'translateY(12px)',
                transition: 'opacity 0.3s, transform 0.3s', maxWidth: '360px'
            });
            document.body.appendChild(toast);
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(12px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Parallax effect for hero section (throttled)
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    const scrolled = window.pageYOffset;
                    const heroSection = document.querySelector('.hero-section');
                    if (heroSection) {
                        heroSection.style.transform = `translateY(${scrolled * 0.05}px)`;
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    });
</script>
<?= $this->endSection() ?>
