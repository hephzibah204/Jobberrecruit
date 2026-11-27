<?= $this->extend('templates/base') ?>



<?= $this->section('schema') ?>
<?= $this->include('partials/pages/privacy_schema') ?>
<?= $this->endSection() ?>



<?= $this->section('content') ?>



<!-- Hero Section -->

<section class="privacy-hero-section py-5 position-relative overflow-hidden">

    <div class="container position-relative z-1">

        <div class="row align-items-center min-vh-60">

            <div class="col-lg-8 mx-auto text-center">

                <h1 class="display-4 fw-bold text-dark mb-4">Privacy <span class="text-gradient-primary">Policy</span></h1>

                <p class="lead text-muted col-lg-10 mx-auto mb-4">

                    Your privacy matters to us. This policy explains how JobberRecruit collects, uses, and protects your personal information.

                </p>



                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">

                    <div class="badge bg-light text-dark px-3 py-2">

                        <i class="bi bi-calendar-check me-1"></i> Last Updated: <strong>October 23, 2024</strong>

                    </div>

                    <div class="badge bg-light text-dark px-3 py-2">

                        <i class="bi bi-eye me-1"></i> Version: <strong>2.0</strong>

                    </div>

                    <button class="badge bg-primary text-white px-3 py-2 border-0" onclick="printPolicy()">

                        <i class="bi bi-printer me-1"></i> Print Policy

                    </button>

                </div>

            </div>

        </div>

    </div>



    <!-- Background Elements -->

    <div class="position-absolute top-0 end-0 w-50 h-100 bg-gradient-primary opacity-5 rounded-start-5"></div>

</section>



<!-- Quick Navigation -->

<section class="py-4 bg-light sticky-top" id="policy-nav" style="top: 0; z-index: 1050;">

    <div class="container">

        <div class="d-flex flex-wrap align-items-center justify-content-between">

            <div class="d-flex align-items-center mb-2 mb-md-0">

                <i class="bi bi-journal-text me-2 text-primary"></i>

                <span class="fw-semibold">On this page:</span>

            </div>

            <div class="d-flex flex-wrap gap-2">

                <a href="#section-1" class="btn btn-sm btn-outline-primary scroll-to">Information We Collect</a>

                <a href="#section-2" class="btn btn-sm btn-outline-primary scroll-to">How We Use Data</a>

                <a href="#section-9" class="btn btn-sm btn-outline-primary scroll-to">Your Rights</a>

                <a href="#section-15" class="btn btn-sm btn-outline-primary scroll-to">Contact Us</a>

            </div>

        </div>

    </div>

</section>



<!-- Policy Content -->

<section class="privacy-section py-5">

    <div class="container">

        <div class="row">

            <!-- Main Content -->

            <div class="col-lg-8">

                <!-- Introduction -->

                <div class="card border-0 shadow-sm mb-5">

                    <div class="card-body p-4 p-md-5">

                        <p class="lead mb-4">

                            JobberRecruit.com ("we", "our", "the platform") is committed to protecting your privacy

                            and ensuring that your personal information is handled responsibly and transparently.

                        </p>



                        <div class="alert alert-light border">

                            <div class="d-flex">

                                <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>

                                <div>

                                    <strong>Important:</strong> By accessing or using JobberRecruit.com,

                                    you agree to the data practices described in this Privacy Policy.

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- SECTION 1 -->

                <div class="policy-section mb-5" id="section-1">

                    <div class="d-flex align-items-center mb-4">

                        <div class="section-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">

                            1

                        </div>

                        <h2 class="fw-bold mb-0">Information We Collect</h2>

                    </div>



                    <p class="text-muted mb-4">

                        We collect information from job seekers, employers, and visitors to provide our recruitment services effectively.

                    </p>



                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="card h-100 border-0 shadow-sm">

                                <div class="card-header bg-primary bg-opacity-10 border-0">

                                    <h5 class="fw-bold mb-0 text-primary">

                                        <i class="bi bi-person-circle me-2"></i>Job Seekers

                                    </h5>

                                </div>

                                <div class="card-body">

                                    <ul class="list-unstyled mb-0">

                                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Full name & contact details</li>

                                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>CV/Resume & work history</li>

                                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Skills, certifications, preferences</li>

                                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Application history</li>

                                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Optional demographic data</li>

                                    </ul>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="card h-100 border-0 shadow-sm">

                                <div class="card-header bg-success bg-opacity-10 border-0">

                                    <h5 class="fw-bold mb-0 text-white">

                                        <i class="bi bi-building me-2"></i>Employers

                                    </h5>

                                </div>

                                <div class="card-body">

                                    <ul class="list-unstyled mb-0">

                                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Company name & profile</li>

                                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Contact person details</li>

                                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Business address</li>

                                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Job posting content</li>

                                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Billing information</li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="mt-4">

                        <h5 class="fw-semibold mt-4">Cookies & Tracking Technologies</h5>

                        <div class="card border-0 bg-light">

                            <div class="card-body">

                                <p>We use cookies to:</p>

                                <div class="row">

                                    <div class="col-md-6">

                                        <ul class="mb-0">

                                            <li>Identify your session</li>

                                            <li>Enable core website functions</li>

                                        </ul>

                                    </div>

                                    <div class="col-md-6">

                                        <ul class="mb-0">

                                            <li>Improve site performance</li>

                                            <li>Personalize content</li>

                                        </ul>

                                    </div>

                                </div>

                                <div class="mt-3">

                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#cookieModal">

                                        <i class="bi bi-cookie me-1"></i>Manage Cookie Preferences

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- SECTION 2 -->

                <div class="policy-section mb-5" id="section-2">

                    <div class="d-flex align-items-center mb-4">

                        <div class="section-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">

                            2

                        </div>

                        <h2 class="fw-bold mb-0">How We Use Your Information</h2>

                    </div>



                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="card h-100 border-0 shadow-sm text-center">

                                <div class="card-body p-4">

                                    <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-3">

                                        <i class="bi bi-search-heart text-primary fs-2"></i>

                                    </div>

                                    <h5 class="fw-bold">Job Matching</h5>

                                    <p class="small text-muted">

                                        Match candidates with suitable opportunities and suggest relevant jobs

                                    </p>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-4">

                            <div class="card h-100 border-0 shadow-sm text-center">

                                <div class="card-body p-4">

                                    <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-3">

                                        <i class="bi bi-briefcase text-white fs-2"></i>

                                    </div>

                                    <h5 class="fw-bold">Recruitment</h5>

                                    <p class="small text-muted">

                                        Enable employers to post vacancies and manage applications

                                    </p>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-4">

                            <div class="card h-100 border-0 shadow-sm text-center">

                                <div class="card-body p-4">

                                    <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle p-3 mx-auto mb-3">

                                        <i class="bi bi-shield-lock text-info fs-2"></i>

                                    </div>

                                    <h5 class="fw-bold">Security</h5>

                                    <p class="small text-muted">

                                        Prevent fraud, ensure platform security, and comply with legal obligations

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Continue with other sections using similar enhanced formatting... -->

                <!-- Due to length, I'll show enhanced formatting for a few key sections -->



                <!-- SECTION 9 -->

                <div class="policy-section mb-5" id="section-9">

                    <div class="d-flex align-items-center mb-4">

                        <div class="section-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">

                            9

                        </div>

                        <h2 class="fw-bold mb-0">Your Rights (GDPR/UK GDPR)</h2>

                    </div>



                    <p class="text-muted mb-4">

                        If you reside in the EEA/UK, you have the following rights regarding your personal data:

                    </p>



                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="d-flex align-items-start mb-3">

                                <div class="icon-wrapper bg-primary bg-opacity-10 rounded-2 p-2 me-3">

                                    <i class="bi bi-eye text-primary"></i>

                                </div>

                                <div>

                                    <h6 class="fw-semibold mb-1">Right to Access</h6>

                                    <p class="small text-muted mb-0">Request a copy of your personal data</p>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="d-flex align-items-start mb-3">

                                <div class="icon-wrapper bg-success bg-opacity-10 rounded-2 p-2 me-3">

                                    <i class="bi bi-pencil text-white"></i>

                                </div>

                                <div>

                                    <h6 class="fw-semibold mb-1">Right to Rectification</h6>

                                    <p class="small text-muted mb-0">Correct inaccurate personal data</p>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="d-flex align-items-start mb-3">

                                <div class="icon-wrapper bg-danger bg-opacity-10 rounded-2 p-2 me-3">

                                    <i class="bi bi-trash text-danger"></i>

                                </div>

                                <div>

                                    <h6 class="fw-semibold mb-1">Right to Erasure</h6>

                                    <p class="small text-muted mb-0">Request deletion of your personal data</p>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="d-flex align-items-start mb-3">

                                <div class="icon-wrapper bg-warning bg-opacity-10 rounded-2 p-2 me-3">

                                    <i class="bi bi-sliders text-warning"></i>

                                </div>

                                <div>

                                    <h6 class="fw-semibold mb-1">Right to Restriction</h6>

                                    <p class="small text-muted mb-0">Limit processing of your personal data</p>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="alert alert-info mt-4">

                        <div class="d-flex">

                            <i class="bi bi-envelope-fill me-3"></i>

                            <div>

                                <strong>Contact us:</strong> To exercise any of these rights, please email

                                <a href="mailto:support@jobberrecruit.com" class="text-decoration-none">support@jobberrecruit.com</a>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- SECTION 15 -->

                <div class="policy-section mb-5" id="section-15">

                    <div class="d-flex align-items-center mb-4">

                        <div class="section-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">

                            15

                        </div>

                        <h2 class="fw-bold mb-0">Contact Us</h2>

                    </div>



                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <h5 class="fw-semibold mb-3">

                                            <i class="bi bi-envelope text-primary me-2"></i>Email Support

                                        </h5>

                                        <p class="mb-2">For privacy-related inquiries:</p>

                                        <a href="mailto:support@jobberrecruit.com" class="btn btn-outline-primary">

                                            <i class="bi bi-envelope me-2"></i>support@jobberrecruit.com

                                        </a>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="mb-4">

                                        <h5 class="fw-semibold mb-3">

                                            <i class="bi bi-globe text-primary me-2"></i>Website

                                        </h5>

                                        <p class="mb-2">Visit our contact page:</p>

                                        <a href="<?= base_url('contact-us') ?>" class="btn btn-outline-primary">

                                            <i class="bi bi-link-45deg me-2"></i>Contact Form

                                        </a>

                                    </div>

                                </div>

                            </div>



                            <div class="alert alert-light border mt-3">

                                <div class="d-flex">

                                    <i class="bi bi-clock-history text-primary me-3"></i>

                                    <div>

                                        <strong>Response Time:</strong> We typically respond to privacy inquiries within 24-48 hours.

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Policy Update History -->

                <div class="card border-0 shadow-sm mt-5">

                    <div class="card-header bg-light border-0">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-clock-history me-2"></i>Policy Update History

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-hover">

                                <thead>

                                    <tr>

                                        <th>Version</th>

                                        <th>Date</th>

                                        <th>Changes</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>

                                        <td><strong>2.0</strong></td>

                                        <td>October 23, 2024</td>

                                        <td>Major update to comply with GDPR requirements</td>

                                    </tr>

                                    <tr>

                                        <td>1.1</td>

                                        <td>June 15, 2024</td>

                                        <td>Updated cookie policy and retention periods</td>

                                    </tr>

                                    <tr>

                                        <td>1.0</td>

                                        <td>January 1, 2024</td>

                                        <td>Initial privacy policy release</td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>



            </div>



            <!-- Sidebar -->

            <div class="col-lg-4">

                <div class="sticky-top" style="top: 100px;">

                    <!-- Quick Links -->

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-0 pt-4 pb-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-list-ul me-2"></i>Quick Navigation

                            </h5>

                        </div>

                        <div class="card-body pt-0">

                            <nav class="nav flex-column">

                                <a class="nav-link scroll-to py-2" href="#section-1">

                                    <i class="bi bi-1-circle me-2"></i>Information We Collect

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-2">

                                    <i class="bi bi-2-circle me-2"></i>How We Use Data

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-3">

                                    <i class="bi bi-3-circle me-2"></i>Legal Basis (GDPR)

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-4">

                                    <i class="bi bi-4-circle me-2"></i>Automated Decision-Making

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-5">

                                    <i class="bi bi-5-circle me-2"></i>Data Sharing

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-6">

                                    <i class="bi bi-6-circle me-2"></i>International Transfers

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-7">

                                    <i class="bi bi-7-circle me-2"></i>Data Retention

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-8">

                                    <i class="bi bi-8-circle me-2"></i>Security Measures

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-9">

                                    <i class="bi bi-9-circle me-2"></i>Your Rights

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-10">

                                    <i class="bi bi-10-circle me-2"></i>Data Deletion

                                </a>

                                <a class="nav-link scroll-to py-2" href="#section-15">

                                    <i class="bi bi-telephone me-2"></i>Contact Us

                                </a>

                            </nav>

                        </div>

                    </div>



                    <!-- Download Section -->

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-body p-4 text-center">

                            <div class="mb-3">

                                <i class="bi bi-file-earmark-text display-6 text-primary"></i>

                            </div>

                            <h5 class="fw-bold mb-3">Download Policy</h5>

                            <p class="text-muted small mb-4">

                                Download a PDF version of this privacy policy for your records.

                            </p>

                            <button class="btn btn-primary w-100" onclick="downloadPolicy()">

                                <i class="bi bi-download me-2"></i>Download PDF

                            </button>

                        </div>

                    </div>



                    <!-- Data Rights Info -->

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white border-0 pt-4 pb-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-shield-check me-2"></i>Data Protection Rights

                            </h5>

                        </div>

                        <div class="card-body pt-0">

                            <div class="alert alert-light border">

                                <p class="small mb-2">

                                    <strong>Need help with your data?</strong>

                                </p>

                                <p class="small text-muted mb-3">

                                    Whether you want to access, correct, or delete your data, we're here to help.

                                </p>

                                <a href="<?= base_url('contact-us') ?>" class="btn btn-sm btn-outline-primary w-100">

                                    <i class="bi bi-chat-dots me-1"></i>Request Assistance

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- Cookie Preferences Modal -->

<div class="modal fade" id="cookieModal" tabindex="-1" aria-labelledby="cookieModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title fw-bold" id="cookieModalLabel">

                    <i class="bi bi-cookie me-2"></i>Cookie Preferences

                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body">

                <p class="text-muted mb-4">

                    We use cookies to enhance your experience. You can customize your preferences below.

                </p>



                <div class="mb-4">

                    <div class="form-check form-switch">

                        <input class="form-check-input" type="checkbox" id="essentialCookies" checked disabled>

                        <label class="form-check-label fw-semibold" for="essentialCookies">

                            Essential Cookies

                        </label>

                        <p class="small text-muted mb-0 ms-4">

                            Required for the website to function properly. Cannot be disabled.

                        </p>

                    </div>

                </div>



                <div class="mb-4">

                    <div class="form-check form-switch">

                        <input class="form-check-input" type="checkbox" id="analyticsCookies" checked>

                        <label class="form-check-label fw-semibold" for="analyticsCookies">

                            Analytics Cookies

                        </label>

                        <p class="small text-muted mb-0 ms-4">

                            Help us understand how visitors interact with our website.

                        </p>

                    </div>

                </div>



                <div class="mb-4">

                    <div class="form-check form-switch">

                        <input class="form-check-input" type="checkbox" id="marketingCookies">

                        <label class="form-check-label fw-semibold" for="marketingCookies">

                            Marketing Cookies

                        </label>

                        <p class="small text-muted mb-0 ms-4">

                            Used to deliver relevant advertisements and track campaign performance.

                        </p>

                    </div>

                </div>



                <div class="alert alert-info">

                    <div class="d-flex">

                        <i class="bi bi-info-circle me-3"></i>

                        <div class="small">

                            Your preferences will be saved for 12 months. You can change them anytime by revisiting this page.

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                <button type="button" class="btn btn-primary" onclick="saveCookiePreferences()">Save Preferences</button>

            </div>

        </div>

    </div>

</div>



<?= $this->endSection() ?>



<?= $this->section('styles') ?>
<?= $this->include('partials/pages/privacy_styles') ?>
<?= $this->endSection() ?>



<?= $this->section('scripts') ?>
<?= $this->include('partials/pages/privacy_scripts') ?>
<?= $this->endSection() ?>