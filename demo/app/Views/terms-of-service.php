<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'WebPage',
    'name'     => 'Terms of Service',
    'description' => 'Terms and conditions for using JobberRecruit.com - Understand your rights and responsibilities when using our recruitment platform.',
    'url'      => current_url(),
    'datePublished' => '2025-02-12',
    'dateModified'  => '2025-02-12',
    'publisher' => [
        '@type' => 'Organization',
        'name'  => 'JobberRecruit',
        'logo'  => [
            '@type' => 'ImageObject',
            'url'   => base_url('images/logo.png'),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="terms-hero-section py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <div class="row align-items-center min-vh-60">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold text-dark mb-4">Terms of <span class="text-gradient-primary">Service</span></h1>
                <p class="lead text-muted col-lg-10 mx-auto mb-4">
                    These terms govern your use of JobberRecruit.com. Please read them carefully before accessing our platform.
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <div class="badge bg-light text-dark px-3 py-2">
                        <i class="bi bi-calendar-check me-1"></i> Effective: <strong>February 12, 2025</strong>
                    </div>
                    <div class="badge bg-light text-dark px-3 py-2">
                        <i class="bi bi-clock-history me-1"></i> Last Updated: <strong>February 12, 2025</strong>
                    </div>
                    <button class="badge bg-primary text-white px-3 py-2 border-0" onclick="printTerms()">
                        <i class="bi bi-printer me-1"></i> Print Terms
                    </button>
                </div>

                <div class="alert alert-warning mt-4 col-lg-10 mx-auto">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>
                        <div class="small">
                            <strong>Important:</strong> By accessing or using JobberRecruit.com, you agree to be bound by these Terms of Service.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Elements -->
    <div class="position-absolute top-0 end-0 w-50 h-100 bg-gradient-primary opacity-5 rounded-start-5"></div>
</section>

<!-- Quick Navigation -->
<section class="py-4 bg-light sticky-top" id="terms-nav" style="top: 0; z-index: 1050;">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <i class="bi bi-journal-text me-2 text-primary"></i>
                <span class="fw-semibold">Quick Navigation:</span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="#section-2" class="btn btn-sm btn-outline-primary scroll-to">Use of Services</a>
                <a href="#section-3" class="btn btn-sm btn-outline-primary scroll-to">Liability</a>
                <a href="#section-4" class="btn btn-sm btn-outline-primary scroll-to">Governing Law</a>
                <a href="#acceptance" class="btn btn-sm btn-outline-primary scroll-to">Acceptance</a>
            </div>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="terms-section py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Introduction -->
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4 p-md-5">
                        <p class="lead mb-4">
                            Welcome to JobberRecruit.com. These Terms of Service constitute a binding agreement between
                            Jobber Recruit Ltd ("JobberRecruit", "we", "our", "us") and any individual, organization,
                            or entity using our services ("User", "Client", "Employer", or "Job Seeker").
                        </p>

                        <div class="alert alert-danger">
                            <div class="d-flex">
                                <i class="bi bi-x-circle-fill fs-5 me-3"></i>
                                <div>
                                    <strong>Discontinuation:</strong> If you do not agree with any part of these Terms,
                                    you should discontinue use of the platform immediately.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 1 -->
                <div class="terms-section-card mb-5" id="section-1">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <div class="section-number bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    1
                                </div>
                                <h2 class="fw-bold mb-0 text-white">Definitions</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                For clarity, the following definitions apply throughout these Terms:
                            </p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="definition-card p-3 border rounded">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="bi bi-gear me-2"></i>"Services"
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            All recruitment tools, job listings, alerts, communication systems, and resources provided by JobberRecruit.com
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="definition-card p-3 border rounded">
                                        <h6 class="fw-bold text-success mb-2">
                                            <i class="bi bi-building me-2"></i>"Client"
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Any employer, recruiter, company, or organization using our platform
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="definition-card p-3 border rounded">
                                        <h6 class="fw-bold text-info mb-2">
                                            <i class="bi bi-person me-2"></i>"User"
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Any individual accessing JobberRecruit.com, including job seekers and employers
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="definition-card p-3 border rounded">
                                        <h6 class="fw-bold text-warning mb-2">
                                            <i class="bi bi-file-earmark-text me-2"></i>"Content"
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            All data displayed on the platform including job listings, profiles, articles, and images
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2 -->
                <div class="terms-section-card mb-5" id="section-2">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <div class="section-number bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    2
                                </div>
                                <h2 class="fw-bold mb-0 text-white">Use of Services</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                Our Services aim to streamline the hiring and job-search process in Nigeria. By using the platform, you agree to the following terms:
                            </p>

                            <div class="accordion" id="usageAccordion">
                                <!-- 2.1 Platform Purpose -->
                                <div class="accordion-item border-0 mb-3">
                                    <h3 class="accordion-header">
                                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse21">
                                            <i class="bi bi-bullseye me-3 text-primary"></i>
                                            2.1 Platform Purpose
                                        </button>
                                    </h3>
                                    <div id="collapse21" class="accordion-collapse collapse show" data-bs-parent="#usageAccordion">
                                        <div class="accordion-body">
                                            <p>JobberRecruit.com serves as a medium for employers and job seekers to connect. We do not:</p>
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><i class="bi bi-x-circle-fill text-danger me-2"></i>Participate in recruitment decision-making</li>
                                                <li class="mb-2"><i class="bi bi-x-circle-fill text-danger me-2"></i>Guarantee job placement for any user</li>
                                                <li><i class="bi bi-x-circle-fill text-danger me-2"></i>Independently verify every job listing or employer submission</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2.2 No Assurance of Job Placement -->
                                <div class="accordion-item border-0 mb-3">
                                    <h3 class="accordion-header">
                                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse22">
                                            <i class="bi bi-shield-exclamation me-3 text-warning"></i>
                                            2.2 No Assurance of Job Placement
                                        </button>
                                    </h3>
                                    <div id="collapse22" class="accordion-collapse collapse" data-bs-parent="#usageAccordion">
                                        <div class="accordion-body">
                                            <p>JobberRecruit does not guarantee that:</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul class="mb-0">
                                                        <li>A job seeker will be hired</li>
                                                        <li>An employer will receive a specific number of applications</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul class="mb-0">
                                                        <li>A listed job will result in a successful hire</li>
                                                        <li>Any job application will be reviewed</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2.3 Third-Party Information -->
                                <div class="accordion-item border-0 mb-3">
                                    <h3 class="accordion-header">
                                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse23">
                                            <i class="bi bi-link-45deg me-3 text-info"></i>
                                            2.3 Third-Party Information
                                        </button>
                                    </h3>
                                    <div id="collapse23" class="accordion-collapse collapse" data-bs-parent="#usageAccordion">
                                        <div class="accordion-body">
                                            <p>
                                                Some content originates from third parties such as employers or advertisers.
                                                While we attempt to screen listings, we cannot guarantee the accuracy or authenticity of third-party content.
                                            </p>
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Users should independently verify information before acting on it.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2.4 User Conduct -->
                                <div class="accordion-item border-0 mb-3">
                                    <h3 class="accordion-header">
                                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse26">
                                            <i class="bi bi-person-check me-3 text-success"></i>
                                            2.6 User Conduct
                                        </button>
                                    </h3>
                                    <div id="collapse26" class="accordion-collapse collapse" data-bs-parent="#usageAccordion">
                                        <div class="accordion-body">
                                            <p>Users agree:</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul class="mb-0">
                                                        <li>Not to misuse our Services for unlawful, fraudulent, or malicious purposes</li>
                                                        <li>To submit accurate and truthful information</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul class="mb-0">
                                                        <li>Not to disrupt or compromise system functionality or security</li>
                                                        <li>To comply with all applicable laws and regulations</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3 -->
                <div class="terms-section-card mb-5" id="section-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-danger text-white">
                            <div class="d-flex align-items-center">
                                <div class="section-number bg-white text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    3
                                </div>
                                <h2 class="fw-bold mb-0 text-white">Limitation of Liability</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card h-100 border-danger border-opacity-25">
                                        <div class="card-header bg-danger bg-opacity-10 border-danger border-opacity-25">
                                            <h5 class="fw-bold text-danger mb-0">
                                                <i class="bi bi-exclamation-triangle me-2"></i>General Limitation
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="small text-muted">
                                                To the fullest extent permitted by law, JobberRecruit is not liable for:
                                            </p>
                                            <ul class="list-unstyled small">
                                                <li class="mb-2"><i class="bi bi-dash-circle text-danger me-2"></i>Losses from inability to use Services</li>
                                                <li class="mb-2"><i class="bi bi-dash-circle text-danger me-2"></i>Inaccuracies in job listings</li>
                                                <li class="mb-2"><i class="bi bi-dash-circle text-danger me-2"></i>System downtime or disruptions</li>
                                                <li><i class="bi bi-dash-circle text-danger me-2"></i>Indirect or consequential damages</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card h-100 border-warning border-opacity-25">
                                        <div class="card-header bg-warning bg-opacity-10 border-warning border-opacity-25">
                                            <h5 class="fw-bold text-warning mb-0">
                                                <i class="bi bi-shield-exclamation me-2"></i>Third-Party Content
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="small text-muted">
                                                We are not responsible for:
                                            </p>
                                            <ul class="list-unstyled small">
                                                <li class="mb-2"><i class="bi bi-dash-circle text-warning me-2"></i>Legitimacy of job postings</li>
                                                <li class="mb-2"><i class="bi bi-dash-circle text-warning me-2"></i>Actions of candidates or employers</li>
                                                <li><i class="bi bi-dash-circle text-warning me-2"></i>Losses from third-party information</li>
                                            </ul>
                                            <p class="small text-muted mt-2">
                                                <strong>Note:</strong> Liability is limited to fees paid directly to us within the issue period.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-x-fill fs-5 me-3"></i>
                                            <div>
                                                <strong>Indemnity:</strong> Clients agree to indemnify and hold harmless JobberRecruit
                                                from any claims arising from misuse of Services, violations of these Terms,
                                                or content submitted by the Client.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-heart-pulse-fill fs-5 me-3"></i>
                                            <div>
                                                <strong>Personal Injury Exception:</strong> These Terms do not exclude liability
                                                for proven negligence resulting in death or personal injury.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4 -->
                <div class="terms-section-card mb-5" id="section-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <div class="section-number bg-white text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    4
                                </div>
                                <h2 class="fw-bold mb-0 text-white">Governing Law</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                    <i class="bi bi-globe display-4 text-info"></i>
                                </div>
                                <div class="col-md-10">
                                    <p class="lead mb-3">
                                        These Terms are governed by the laws of the Federal Republic of Nigeria.
                                    </p>
                                    <p class="text-muted">
                                        Any disputes arising from or relating to these Terms shall be resolved in
                                        competent Nigerian courts having jurisdiction over the matter.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 5 & 6 -->
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" id="acceptance">
                            <div class="card-header bg-primary text-white">
                                <h5 class="fw-bold mb-0 text-white">
                                    <i class="bi bi-check-circle me-2"></i>Acceptance of Terms
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-4">
                                    <i class="bi bi-file-earmark-check display-1 text-primary"></i>
                                </div>
                                <p class="mb-4">
                                    By using any part of JobberRecruit.com, you acknowledge that you have read,
                                    understood, and agreed to these Terms of Service.
                                </p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#acceptanceModal">
                                    <i class="bi bi-check-lg me-2"></i>Acknowledge Terms
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="fw-bold mb-0 text-white">
                                    <i class="bi bi-shield-check me-2"></i>Privacy Policy
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-4">
                                    <i class="bi bi-shield-lock display-1 text-success"></i>
                                </div>
                                <p class="mb-4">
                                    For information on how we collect, use, and protect your personal data,
                                    please review our Privacy Policy.
                                </p>
                                <a href="<?= base_url('privacy-policy') ?>" class="btn btn-success">
                                    <i class="bi bi-file-earmark-text me-2"></i>View Privacy Policy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms Update History -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-clock-history me-2"></i>Terms Update History
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Version</th>
                                        <th>Effective Date</th>
                                        <th>Key Changes</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>2.0</strong></td>
                                        <td>February 12, 2025</td>
                                        <td>Major update with enhanced liability clauses and user conduct guidelines</td>
                                        <td><span class="badge bg-success">Current</span></td>
                                    </tr>
                                    <tr>
                                        <td>1.2</td>
                                        <td>October 23, 2024</td>
                                        <td>Updated governing law and dispute resolution sections</td>
                                        <td><span class="badge bg-secondary">Archived</span></td>
                                    </tr>
                                    <tr>
                                        <td>1.1</td>
                                        <td>June 15, 2024</td>
                                        <td>Added GDPR compliance and data protection clauses</td>
                                        <td><span class="badge bg-secondary">Archived</span></td>
                                    </tr>
                                    <tr>
                                        <td>1.0</td>
                                        <td>January 1, 2024</td>
                                        <td>Initial terms of service release</td>
                                        <td><span class="badge bg-secondary">Archived</span></td>
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
                                <i class="bi bi-bookmark-check me-2"></i>Key Sections
                            </h5>
                        </div>
                        <div class="card-body pt-0">
                            <nav class="nav flex-column">
                                <a class="nav-link scroll-to py-2" href="#section-1">
                                    <i class="bi bi-book me-2 text-primary"></i>
                                    <span>Definitions</span>
                                </a>
                                <a class="nav-link scroll-to py-2" href="#section-2">
                                    <i class="bi bi-gear me-2 text-success"></i>
                                    <span>Use of Services</span>
                                </a>
                                <a class="nav-link scroll-to py-2" href="#section-3">
                                    <i class="bi bi-shield-exclamation me-2 text-danger"></i>
                                    <span>Limitation of Liability</span>
                                </a>
                                <a class="nav-link scroll-to py-2" href="#section-4">
                                    <i class="bi bi-globe me-2 text-info"></i>
                                    <span>Governing Law</span>
                                </a>
                                <a class="nav-link scroll-to py-2" href="#acceptance">
                                    <i class="bi bi-check-circle me-2 text-primary"></i>
                                    <span>Acceptance</span>
                                </a>
                            </nav>
                        </div>
                    </div>

                    <!-- Download Section -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="bi bi-file-earmark-pdf display-6 text-danger"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Download Terms</h5>
                            <p class="text-muted small mb-4">
                                Download a PDF version of these Terms of Service for your records.
                            </p>
                            <button class="btn btn-outline-danger w-100 mb-2" onclick="downloadTerms()">
                                <i class="bi bi-download me-2"></i>Download PDF
                            </button>
                            <button class="btn btn-outline-primary w-100" onclick="shareTerms()">
                                <i class="bi bi-share me-2"></i>Share Terms
                            </button>
                        </div>
                    </div>

                    <!-- Need Help? -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pt-4 pb-3">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-question-circle me-2"></i>Need Help Understanding?
                            </h5>
                        </div>
                        <div class="card-body pt-0">
                            <p class="small text-muted mb-3">
                                If you have questions about these terms or need clarification on any section:
                            </p>
                            <a href="<?= base_url('contact-us') ?>" class="btn btn-outline-primary w-100 mb-2">
                                <i class="bi bi-envelope me-1"></i>Contact Legal Team
                            </a>
                            <a href="<?= base_url('faq') ?>" class="btn btn-outline-success w-100">
                                <i class="bi bi-question-lg me-1"></i>Visit FAQ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Acceptance Modal -->
<div class="modal fade" id="acceptanceModal" tabindex="-1" aria-labelledby="acceptanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="acceptanceModalLabel">
                    <i class="bi bi-check-circle text-success me-2"></i>Acknowledge Terms
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="bi bi-file-text display-1 text-primary"></i>
                </div>
                <p class="text-center mb-4">
                    By clicking "I Accept", you acknowledge that you have read, understood,
                    and agree to be bound by the Terms of Service of JobberRecruit.com.
                </p>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="agreeTermsCheck">
                    <label class="form-check-label" for="agreeTermsCheck">
                        I have read and agree to the Terms of Service
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="acceptTermsBtn" disabled onclick="acceptTerms()">
                    <i class="bi bi-check-lg me-2"></i>I Accept
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Terms of Service Custom Styles */

    /* Hero Section */
    .terms-hero-section {
        background: linear-gradient(135deg, #f8fafc 0%, #F5A623 100%);
        position: relative;
        overflow: hidden;
    }

    .min-vh-60 {
        min-height: 60vh;
    }

    .text-gradient-primary {
        background: linear-gradient(90deg, #005DA8 0%, #005DA8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .bg-primary {
        background: linear-gradient(90deg, #005DA8 0%, #005DA8 100%);
    }

    /* Section Cards */
    .terms-section-card .card {
        border-radius: 12px !important;
        overflow: hidden;
    }

    .card-header {
        border-bottom: none !important;
    }

    .section-number {
        font-weight: bold;
        font-size: 1.2rem;
    }

    /* Definition Cards */
    .definition-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .definition-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }

    /* Accordion Styling */
    .accordion-item {
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        margin-bottom: 10px;
    }

    .accordion-button {
        font-weight: 600;
        color: #1a1a1a;
        background-color: white;
        border-radius: 8px !important;
    }

    .accordion-button:not(.collapsed) {
        color: #005DA8;
        background-color: rgba(10, 88, 202, 0.05);
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: #005DA8;
    }

    /* Liability Cards */
    .border-danger.border-opacity-25 {
        border-color: rgba(220, 53, 69, 0.25) !important;
    }

    .border-warning.border-opacity-25 {
        border-color: rgba(255, 193, 7, 0.25) !important;
    }

    /* Quick Navigation */
    #terms-nav {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    /* Sidebar Navigation */
    .nav-link.scroll-to {
        color: #4a5568;
        border-left: 3px solid transparent;
        padding-left: 1rem;
        transition: all 0.3s ease;
    }

    .nav-link.scroll-to:hover {
        color: #005DA8;
        border-left-color: #005DA8;
        background-color: rgba(102, 126, 234, 0.05);
    }

    .nav-link.scroll-to.active {
        color: #005DA8;
        border-left-color: #005DA8;
        background-color: rgba(102, 126, 234, 0.05);
        font-weight: 600;
    }

    /* Table Styling */
    .table th {
        background-color: #f8fafc;
        font-weight: 600;
        border-top: none;
    }

    .table td {
        vertical-align: middle;
    }

    /* Background Elements */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #F5A623 0%, #F5A623 100%);
    }

    /* Print Styles */
    @media print {

        #terms-nav,
        .sticky-top,
        .btn,
        .badge,
        .modal,
        .card:not(.terms-section-card .card) {
            display: none !important;
        }

        .terms-section-card {
            break-inside: avoid;
        }

        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }

        .terms-hero-section {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        #terms-nav {
            position: relative !important;
        }

        #terms-nav .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .sticky-top {
            position: relative !important;
            top: 0 !important;
        }

        .accordion-button {
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
        }
    }

    /* Custom Scrollbar for Sidebar */
    .nav.flex-column::-webkit-scrollbar {
        width: 4px;
    }

    .nav.flex-column::-webkit-scrollbar-track {
        background: #f1f3f5;
    }

    .nav.flex-column::-webkit-scrollbar-thumb {
        background: #adb5bd;
        border-radius: 2px;
    }

    /* Hover Effects */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    /* Animation */
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

    .terms-section-card {
        animation: fadeIn 0.6s ease-out;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for anchor links
        document.querySelectorAll('.scroll-to').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId.startsWith('#')) {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // Highlight current section in sidebar
        const sections = document.querySelectorAll('.terms-section-card');
        const navLinks = document.querySelectorAll('.nav-link.scroll-to');

        function highlightCurrentSection() {
            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;

                if (window.scrollY >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', highlightCurrentSection);
        highlightCurrentSection(); // Initial call

        // Acceptance modal logic
        const agreeTermsCheck = document.getElementById('agreeTermsCheck');
        const acceptTermsBtn = document.getElementById('acceptTermsBtn');

        if (agreeTermsCheck && acceptTermsBtn) {
            agreeTermsCheck.addEventListener('change', function() {
                acceptTermsBtn.disabled = !this.checked;
            });
        }

        // Print terms function
        window.printTerms = function() {
            window.print();
        };

        // Download terms function (simulated)
        window.downloadTerms = function() {
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating PDF...';
            btn.disabled = true;

            // Simulate PDF generation
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>PDF Downloaded';
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-success');

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-danger');

                    // Show success message
                    showAlert('success', 'Terms of Service PDF has been downloaded to your device.');
                }, 2000);
            }, 1500);
        };

        // Share terms function
        window.shareTerms = function() {
            if (navigator.share) {
                navigator.share({
                    title: 'JobberRecruit Terms of Service',
                    text: 'Read the Terms of Service for JobberRecruit.com',
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showAlert('info', 'Link copied to clipboard!');
                });
            }
        };

        // Accept terms function
        window.acceptTerms = function() {
            // Store acceptance in localStorage
            localStorage.setItem('termsAccepted', 'true');
            localStorage.setItem('termsAcceptedDate', new Date().toISOString());
            localStorage.setItem('termsVersion', '2.0');

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('acceptanceModal'));
            modal.hide();

            // Show success message
            showAlert('success', 'Thank you for accepting our Terms of Service!');
        };

        // Show alert function
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed bottom-0 end-0 m-4`;
            alertDiv.style.zIndex = '1050';
            alertDiv.style.maxWidth = '350px';
            alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Check if terms were previously accepted
        function checkTermsAcceptance() {
            const accepted = localStorage.getItem('termsAccepted');
            const acceptedVersion = localStorage.getItem('termsVersion');
            const currentVersion = '2.0';

            if (!accepted || acceptedVersion !== currentVersion) {
                // Show modal if terms haven't been accepted or version changed
                setTimeout(() => {
                    const modal = new bootstrap.Modal(document.getElementById('acceptanceModal'));
                    modal.show();
                }, 3000);
            }
        }

        // Back to top button
        const backToTop = document.createElement('button');
        backToTop.className = 'btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4';
        backToTop.style.zIndex = '1040';
        backToTop.style.width = '50px';
        backToTop.style.height = '50px';
        backToTop.innerHTML = '<i class="bi bi-arrow-up"></i>';
        backToTop.title = 'Back to top';
        backToTop.onclick = () => window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        document.body.appendChild(backToTop);

        // Show/hide back to top button
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.style.display = 'flex';
                backToTop.style.alignItems = 'center';
                backToTop.style.justifyContent = 'center';
            } else {
                backToTop.style.display = 'none';
            }
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Check terms acceptance on page load
        checkTermsAcceptance();
    });

    // Auto-expand first accordion item
    document.addEventListener('DOMContentLoaded', function() {
        const firstAccordion = document.querySelector('.accordion-button');
        if (firstAccordion) {
            firstAccordion.click();
        }
    });
</script>
<?= $this->endSection() ?>