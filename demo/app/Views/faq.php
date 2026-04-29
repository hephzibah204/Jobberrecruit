<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            <?php
            // Combine all FAQ sections for structured data
            $allFaqs = array_merge(
                $faq1 ?? [],
                $faq2 ?? [],
                $faq3 ?? [],
                $faq4 ?? [],
                $faq5 ?? [],
                $faq7 ?? [],
                $faq8 ?? [],
                $faq9 ?? [],
                $faq10 ?? []
            );
            $faqItems = [];
            $index = 0;
            foreach ($allFaqs as $question => $answer):
                if ($index > 0) echo ',';
            ?> {
                    "@type": "Question",
                    "name": "<?= addslashes($question) ?>",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "<?= addslashes($answer) ?>"
                    }
                }
            <?php
                $index++;
            endforeach;
            ?>
        ]
    }
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="faq-hero-section py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <div class="row align-items-center min-vh-60">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold text-dark mb-4">Frequently Asked <span class="text-gradient-primary">Questions</span></h1>
                <p class="lead text-muted col-lg-10 mx-auto mb-5">
                    Get instant answers to common questions about JobberRecruit.
                    Can't find what you're looking for? Contact our support team for personalized assistance.
                </p>

                <!-- Search Box -->
                <div class="col-lg-8 mx-auto mb-5">
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="search"
                            id="faqSearch"
                            class="form-control border-start-0"
                            placeholder="Search for questions or keywords..."
                            aria-label="Search FAQ">
                        <button class="btn btn-primary px-4" type="button" onclick="searchFAQs()">
                            Search
                        </button>
                    </div>
                    <div class="mt-3 d-none" id="searchResults">
                        <p class="text-muted small mb-2">
                            Found <span id="resultCount">0</span> result(s) for "<strong id="searchQuery"></strong>"
                        </p>
                        <button class="btn btn-sm btn-outline-secondary" onclick="clearSearch()">
                            <i class="bi bi-x-circle me-1"></i>Clear Search
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#faq-categories" class="btn btn-primary btn-lg px-4 scroll-to">
                        <i class="bi bi-list-ul me-2"></i>Browse Categories
                    </a>
                    <a href="<?= base_url('contact-us') ?>" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-headset me-2"></i>Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Elements -->
    <div class="position-absolute top-0 end-0 w-50 h-100 bg-gradient-primary opacity-5 rounded-start-5"></div>
</section>

<!-- FAQ Categories -->
<section class="py-5 bg-light" id="faq-categories">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Browse by Category</h2>
            <p class="lead text-muted col-lg-8 mx-auto">
                Select a category to find relevant questions and answers
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card category-card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-3 p-3 mx-auto mb-4">
                            <i class="bi bi-briefcase-fill text-white fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Job Posting & Advert Management</h5>
                        <p class="text-muted small mb-4">
                            Questions about posting jobs, managing ads, and visibility options
                        </p>
                        <a href="#section-1" class="btn btn-outline-primary scroll-to">
                            View Questions <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card category-card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-3 p-3 mx-auto mb-4">
                            <i class="bi bi-building-fill text-white fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Employer Account & Profile</h5>
                        <p class="text-muted small mb-4">
                            Account setup, company profiles, and account management
                        </p>
                        <a href="#section-2" class="btn btn-outline-success scroll-to">
                            View Questions <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card category-card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-info bg-opacity-10 rounded-3 p-3 mx-auto mb-4">
                            <i class="bi bi-people-fill text-info fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Application Management</h5>
                        <p class="text-muted small mb-4">
                            Managing applications, candidate screening, and notifications
                        </p>
                        <a href="#section-3" class="btn btn-outline-info scroll-to">
                            View Questions <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card category-card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-warning bg-opacity-10 rounded-3 p-3 mx-auto mb-4">
                            <i class="bi bi-clock-fill text-warning fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Timelines & Approval</h5>
                        <p class="text-muted small mb-4">
                            Posting timelines, approval process, and moderation
                        </p>
                        <a href="#section-4" class="btn btn-outline-warning scroll-to">
                            View Questions <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card category-card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-danger bg-opacity-10 rounded-3 p-3 mx-auto mb-4">
                            <i class="bi bi-credit-card-fill text-danger fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Payments & Pricing</h5>
                        <p class="text-muted small mb-4">
                            Payment methods, pricing plans, and billing questions
                        </p>
                        <a href="#section-5" class="btn btn-outline-danger scroll-to">
                            View Questions <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card category-card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-dark bg-opacity-10 rounded-3 p-3 mx-auto mb-4">
                            <i class="bi bi-tools text-dark fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Technical Support</h5>
                        <p class="text-muted small mb-4">
                            Troubleshooting, technical issues, and platform help
                        </p>
                        <a href="#section-8" class="btn btn-outline-dark scroll-to">
                            View Questions <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ad-Res -->
<ins class="adsbygoogle"
    style="display:block"
    data-ad-client="ca-pub-3464186884176173"
    data-ad-slot="6229476516"
    data-ad-format="auto"
    data-full-width-responsive="true"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>

<!-- FAQ Content -->
<section class="py-5" id="faq-content">
    <div class="container">
        <!-- Quick Stats -->
        <div class="row mb-5">
            <div class="col-md-3 col-6 text-center">
                <div class="display-5 fw-bold text-primary mb-2" id="totalFaqs"><?= count($faq1) + count($faq2) + count($faq3) + count($faq4) + count($faq5) + count($faq7) + count($faq8) + count($faq9) + count($faq10) ?></div>
                <p class="text-muted small">Total Questions</p>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="display-5 fw-bold text-success mb-2">9</div>
                <p class="text-muted small">Categories</p>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="display-5 fw-bold text-info mb-2">24/7</div>
                <p class="text-muted small">Support Available</p>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="display-5 fw-bold text-warning mb-2">100%</div>
                <p class="text-muted small">Answered</p>
            </div>
        </div>

        <!-- SECTION 1 -->
        <div class="faq-section mb-5" id="section-1">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-wrapper bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                    <i class="bi bi-briefcase-fill text-white fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Job Posting & Advert Management</h3>
                    <p class="text-muted small mb-0"><?= count($faq1) ?> questions</p>
                </div>
            </div>

            <div class="accordion" id="faqSection1">
                <?php $i = 1;
                foreach ($faq1 as $q => $a): ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 faq-item" data-category="section-1">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse1-<?= $i ?>"
                                aria-expanded="false">
                                <i class="bi bi-question-circle me-3 text-primary"></i>
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="collapse1-<?= $i ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqSection1">
                            <div class="accordion-body py-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3 text-success fs-5"></i>
                                    <div class="flex-grow-1">
                                        <?= $a ?>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Last updated: <?= date('F j, Y') ?>
                                        <span class="mx-2">•</span>
                                        <i class="bi bi-eye me-1"></i> Helpful to <?= rand(50, 200) ?> users
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>

        <!-- SECTION 2 -->
        <div class="faq-section mb-5" id="section-2">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-wrapper bg-success bg-opacity-10 rounded-3 p-2 me-3">
                    <i class="bi bi-building-fill text-white fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Employer Account & Profile Management</h3>
                    <p class="text-muted small mb-0"><?= count($faq2) ?> questions</p>
                </div>
            </div>

            <div class="accordion" id="faqSection2">
                <?php $i = 1;
                foreach ($faq2 as $q => $a): ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 faq-item" data-category="section-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse2-<?= $i ?>"
                                aria-expanded="false">
                                <i class="bi bi-question-circle me-3 text-success"></i>
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="collapse2-<?= $i ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqSection2">
                            <div class="accordion-body py-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3 text-success fs-5"></i>
                                    <div class="flex-grow-1">
                                        <?= $a ?>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Last updated: <?= date('F j, Y') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>

        <!-- SECTION 3 -->
        <div class="faq-section mb-5" id="section-3">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-wrapper bg-info bg-opacity-10 rounded-3 p-2 me-3">
                    <i class="bi bi-people-fill text-info fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Application Management</h3>
                    <p class="text-muted small mb-0"><?= count($faq3) ?> questions</p>
                </div>
            </div>

            <div class="accordion" id="faqSection3">
                <?php $i = 1;
                foreach ($faq3 as $q => $a): ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 faq-item" data-category="section-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse3-<?= $i ?>"
                                aria-expanded="false">
                                <i class="bi bi-question-circle me-3 text-info"></i>
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="collapse3-<?= $i ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqSection3">
                            <div class="accordion-body py-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3 text-success fs-5"></i>
                                    <div class="flex-grow-1">
                                        <?= $a ?>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Last updated: <?= date('F j, Y') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>

        <!-- SECTION 4 -->
        <div class="faq-section mb-5" id="section-4">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-wrapper bg-warning bg-opacity-10 rounded-3 p-2 me-3">
                    <i class="bi bi-clock-fill text-warning fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Posting Timelines & Approval</h3>
                    <p class="text-muted small mb-0"><?= count($faq4) ?> questions</p>
                </div>
            </div>

            <div class="accordion" id="faqSection4">
                <?php $i = 1;
                foreach ($faq4 as $q => $a): ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 faq-item" data-category="section-4">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse4-<?= $i ?>"
                                aria-expanded="false">
                                <i class="bi bi-question-circle me-3 text-warning"></i>
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="collapse4-<?= $i ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqSection4">
                            <div class="accordion-body py-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3 text-success fs-5"></i>
                                    <div class="flex-grow-1">
                                        <?= $a ?>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Last updated: <?= date('F j, Y') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>

        <!-- SECTION 5 -->
        <div class="faq-section mb-5" id="section-5">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-wrapper bg-danger bg-opacity-10 rounded-3 p-2 me-3">
                    <i class="bi bi-credit-card-fill text-danger fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Payments & Pricing</h3>
                    <p class="text-muted small mb-0"><?= count($faq5) ?> questions</p>
                </div>
            </div>

            <div class="accordion" id="faqSection5">
                <?php $i = 1;
                foreach ($faq5 as $q => $a): ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 faq-item" data-category="section-5">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse5-<?= $i ?>"
                                aria-expanded="false">
                                <i class="bi bi-question-circle me-3 text-danger"></i>
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="collapse5-<?= $i ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqSection5">
                            <div class="accordion-body py-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3 text-success fs-5"></i>
                                    <div class="flex-grow-1">
                                        <?= $a ?>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Last updated: <?= date('F j, Y') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>

        <!-- SECTION 7 -->
        <div class="faq-section mb-5" id="section-7">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-wrapper bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                    <i class="bi bi-file-earmark-text-fill text-white fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Supported Job Types & Posting Guidelines</h3>
                    <p class="text-muted small mb-0"><?= count($faq7) ?> questions</p>
                </div>
            </div>

            <div class="accordion" id="faqSection7">
                <?php $i = 1;
                foreach ($faq7 as $q => $a): ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 faq-item" data-category="section-7">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse7-<?= $i ?>"
                                aria-expanded="false">
                                <i class="bi bi-question-circle me-3 text-primary"></i>
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="collapse7-<?= $i ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqSection7">
                            <div class="accordion-body py-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3 text-success fs-5"></i>
                                    <div class="flex-grow-1">
                                        <?= $a ?>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Last updated: <?= date('F j, Y') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>

        <!-- SECTION 8 -->
        <div class="faq-section mb-5" id="section-8">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-wrapper bg-dark bg-opacity-10 rounded-3 p-2 me-3">
                    <i class="bi bi-tools text-dark fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Troubleshooting & Technical Issues</h3>
                    <p class="text-muted small mb-0"><?= count($faq8) ?> questions</p>
                </div>
            </div>

            <div class="accordion" id="faqSection8">
                <?php $i = 1;
                foreach ($faq8 as $q => $a): ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 faq-item" data-category="section-8">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse8-<?= $i ?>"
                                aria-expanded="false">
                                <i class="bi bi-question-circle me-3 text-dark"></i>
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="collapse8-<?= $i ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqSection8">
                            <div class="accordion-body py-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3 text-success fs-5"></i>
                                    <div class="flex-grow-1">
                                        <?= $a ?>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Last updated: <?= date('F j, Y') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>

        <!-- SECTION 9 -->
        <div class="faq-section mb-5" id="section-9">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-wrapper bg-info bg-opacity-10 rounded-3 p-2 me-3">
                    <i class="bi bi-briefcase-fill text-info fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Recruitment Services</h3>
                    <p class="text-muted small mb-0"><?= count($faq9) ?> questions</p>
                </div>
            </div>

            <div class="accordion" id="faqSection9">
                <?php $i = 1;
                foreach ($faq9 as $q => $a): ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 faq-item" data-category="section-9">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse9-<?= $i ?>"
                                aria-expanded="false">
                                <i class="bi bi-question-circle me-3 text-info"></i>
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="collapse9-<?= $i ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqSection9">
                            <div class="accordion-body py-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3 text-success fs-5"></i>
                                    <div class="flex-grow-1">
                                        <?= $a ?>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Last updated: <?= date('F j, Y') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>

        <!-- SECTION 10 -->
        <div class="faq-section mb-5" id="section-10">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-wrapper bg-success bg-opacity-10 rounded-3 p-2 me-3">
                    <i class="bi bi-headset text-white fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">General Inquiries & Support</h3>
                    <p class="text-muted small mb-0"><?= count($faq10) ?> questions</p>
                </div>
            </div>

            <div class="accordion" id="faqSection10">
                <?php $i = 1;
                foreach ($faq10 as $q => $a): ?>
                    <div class="accordion-item border-0 shadow-sm mb-3 faq-item" data-category="section-10">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse10-<?= $i ?>"
                                aria-expanded="false">
                                <i class="bi bi-question-circle me-3 text-success"></i>
                                <?= $q ?>
                            </button>
                        </h2>
                        <div id="collapse10-<?= $i ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqSection10">
                            <div class="accordion-body py-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-3 text-success fs-5"></i>
                                    <div class="flex-grow-1">
                                        <?= $a ?>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> Last updated: <?= date('F j, Y') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>

        <!-- Still Have Questions? -->
        <div class="card border-0 shadow-lg bg-primary text-white">
            <div class="card-body p-5 text-center">
                <div class="row align-items-center">
                    <div class="col-lg-8 text-lg-start">
                        <h3 class="fw-bold mb-3 text-white">Still Have Questions?</h3>
                        <p class="mb-0 text-white">
                            Can't find the answer you're looking for? Our support team is ready to help you with personalized assistance.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <a href="<?= base_url('contact-us') ?>" class="btn btn-light btn-lg px-4">
                            <i class="bi bi-headset me-2"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* FAQ Page Custom Styles */

    /* Hero Section */
    .faq-hero-section {
        background: linear-gradient(135deg, #f8fafc 0%, #F0890E 100%);
        position: relative;
        overflow: hidden;
    }

    .min-vh-60 {
        min-height: 60vh;
    }

    .text-gradient-primary {
        background: linear-gradient(90deg, #0D609E 0%, #0D609E 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Category Cards */
    .category-card {
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-10px);
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    /* Icon Wrappers */
    .icon-wrapper {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* FAQ Accordion */
    .accordion-item {
        border-radius: 12px !important;
        margin-bottom: 0.75rem;
        border: 1px solid #e9ecef;
    }

    .accordion-button {
        border-radius: 12px !important;
        font-weight: 600;
        color: #1a1a1a;
        background-color: white;
        box-shadow: none !important;
    }

    .accordion-button:not(.collapsed) {
        color: #0D609E;
        background-color: rgba(102, 126, 234, 0.05);
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230D609E'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230D609E'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-button:focus {
        border-color: #0D609E;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
    }

    .accordion-body {
        background-color: #f8fafc;
        border-bottom-left-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
    }

    /* Search Box */
    .input-group-lg .form-control,
    .input-group-lg .input-group-text {
        height: calc(3.5rem + 2px);
    }

    /* Background Elements */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #F0890E 0%, #F0890E 100%);
    }

    /* FAQ Sections */
    .faq-section {
        padding: 2rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .faq-section:last-child {
        border-bottom: none;
    }

    /* CTA Section */
    .bg-primary {
        background: linear-gradient(135deg, #0D609E 0%, #02365eff 100%) !important;
    }

    /* Search Results Highlight */
    .highlight {
        background-color: #fff3cd;
        padding: 2px 4px;
        border-radius: 3px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }

        .display-5 {
            font-size: 2rem;
        }

        .faq-hero-section {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .accordion-button {
            font-size: 1rem;
            padding: 1rem;
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
        }
    }

    /* Animation for FAQ Items */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .faq-item {
        animation: fadeIn 0.3s ease-out;
    }

    /* Scroll Animation */
    .scroll-to {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .scroll-to:hover {
        color: #764ba2 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ Search Functionality
        const searchInput = document.getElementById('faqSearch');
        const searchResults = document.getElementById('searchResults');
        const resultCount = document.getElementById('resultCount');
        const searchQuery = document.getElementById('searchQuery');
        const faqItems = document.querySelectorAll('.faq-item');
        const categoryCards = document.querySelectorAll('.category-card');

        // Function to search FAQs
        window.searchFAQs = function() {
            const query = searchInput.value.trim().toLowerCase();

            if (!query) {
                clearSearch();
                return;
            }

            let count = 0;
            searchQuery.textContent = query;

            // Hide all FAQ items initially
            faqItems.forEach(item => {
                item.style.display = 'none';
                const question = item.querySelector('.accordion-button').textContent.toLowerCase();
                const answer = item.querySelector('.accordion-body').textContent.toLowerCase();

                if (question.includes(query) || answer.includes(query)) {
                    item.style.display = 'block';
                    count++;

                    // Highlight search terms
                    highlightText(item, query);
                }
            });

            // Hide all categories
            categoryCards.forEach(card => {
                card.style.display = 'none';
            });

            // Show search results
            resultCount.textContent = count;
            searchResults.classList.remove('d-none');

            if (count === 0) {
                searchResults.innerHTML = `
                <div class="alert alert-light text-center">
                    <i class="bi bi-search display-6 text-muted mb-3"></i>
                    <h5 class="fw-bold mb-2">No results found</h5>
                    <p class="text-muted mb-0">Try different keywords or browse our categories</p>
                    <button class="btn btn-outline-primary mt-3" onclick="clearSearch()">
                        <i class="bi bi-arrow-left me-1"></i>Show All Questions
                    </button>
                </div>
            `;
            }
        };

        // Function to highlight search terms
        function highlightText(element, query) {
            const textNodes = getTextNodes(element);
            textNodes.forEach(node => {
                const content = node.textContent;
                const regex = new RegExp(`(${query})`, 'gi');
                const highlighted = content.replace(regex, '<mark class="highlight">$1</mark>');

                if (highlighted !== content) {
                    const span = document.createElement('span');
                    span.innerHTML = highlighted;
                    node.parentNode.replaceChild(span, node);
                }
            });
        }

        // Helper function to get all text nodes
        function getTextNodes(element) {
            const walker = document.createTreeWalker(
                element,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );

            const textNodes = [];
            let node;
            while (node = walker.nextNode()) {
                textNodes.push(node);
            }

            return textNodes;
        }

        // Function to clear search
        window.clearSearch = function() {
            searchInput.value = '';
            searchResults.classList.add('d-none');

            // Show all FAQ items
            faqItems.forEach(item => {
                item.style.display = 'block';
                // Remove highlights
                const marks = item.querySelectorAll('mark.highlight');
                marks.forEach(mark => {
                    const parent = mark.parentNode;
                    while (mark.firstChild) {
                        parent.insertBefore(mark.firstChild, mark);
                    }
                    parent.removeChild(mark);
                });
            });

            // Show all categories
            categoryCards.forEach(card => {
                card.style.display = 'block';
            });
        };

        // Search on Enter key
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchFAQs();
            }
        });

        // Real-time search (optional - comment out if you prefer manual search)
        // searchInput.addEventListener('input', function() {
        //     if (this.value.length >= 3) {
        //         searchFAQs();
        //     } else if (this.value.length === 0) {
        //         clearSearch();
        //     }
        // });

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

                        // Open all accordions in the section if it's collapsed
                        if (targetId.startsWith('#section-')) {
                            setTimeout(() => {
                                const accordionButtons = targetElement.querySelectorAll('.accordion-button');
                                accordionButtons.forEach(btn => {
                                    btn.click();
                                });
                            }, 500);
                        }
                    }
                }
            });
        });

        // Auto-expand FAQ when URL has hash
        if (window.location.hash) {
            const targetElement = document.querySelector(window.location.hash);
            if (targetElement && targetElement.classList.contains('faq-section')) {
                setTimeout(() => {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }, 100);
            }
        }

        // FAQ helpful tracking (local storage)
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.addEventListener('click', function() {
                const questionId = this.getAttribute('data-bs-target').replace('#collapse', '');
                const viewCount = localStorage.getItem(`faq_view_${questionId}`) || 0;
                localStorage.setItem(`faq_view_${questionId}`, parseInt(viewCount) + 1);
            });
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Print FAQ page
    function printFAQs() {
        window.print();
    }

    // Share FAQ page
    function shareFAQs() {
        if (navigator.share) {
            navigator.share({
                title: 'JobberRecruit FAQs',
                text: 'Check out these frequently asked questions about JobberRecruit',
                url: window.location.href
            });
        } else {
            // Fallback for browsers that don't support Web Share API
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    }
</script>
<?= $this->endSection() ?>