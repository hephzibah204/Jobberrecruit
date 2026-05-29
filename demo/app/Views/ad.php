<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'WebPage',
    'name'     => 'Post a Job & Hire Fast | Affordable Recruitment Plans',
    'description' => 'Post your job vacancy on JobberRecruit today. Choose from affordable Pay-As-You-Go or Unlimited plans. Get 3x visibility and screen candidates instantly.',
    'publisher' => [
        '@type' => 'Organization',
        'name'  => 'Jobber Recruit LTD',
        'url'   => base_url(),
        'logo'  => base_url('images/logo.png'),
    ],
    'mainEntity' => [
        '@type'       => 'Service',
        'name'        => 'Job Posting Service',
        'description' => 'Premium job posting platform with advanced screening and distribution tools',
        'offers'      => [
            '@type'       => 'AggregateOffer',
            'offerCount'  => '2',
            'offers'      => [
                ['@type' => 'Offer', 'name' => 'Pay-As-You-Go Plan', 'description' => 'Pay per job posting with premium features'],
                ['@type' => 'Offer', 'name' => 'Unlimited Plan', 'description' => 'Unlimited job postings for growing companies'],
            ],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="job-posting-hero py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-white mb-4">
                    Post a Job. <span class="text-warning">Find Talent.</span> Done.
                </h1>
                <p class="lead text-light mb-4">
                    Stop sifting through clutter. Start interviewing.
                    At Jobber Recruit LTD, we give you access to a massive pool of pre-verified candidates. Whether you need one staff member or an entire team, our platform puts you in control.
                </p>

                <div class="benefits-grid mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <div class="benefit-icon bg-warning rounded-circle p-2 me-3">
                            <i class="bi bi-lightning-charge-fill text-dark fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-white">Slash Hiring Time</h5>
                            <p class="opacity-90 mb-0 text-white">Go from "Post" to "Interview" in record time</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="benefit-icon bg-success rounded-circle p-2 me-3">
                            <i class="bi bi-graph-up-arrow text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-white">Boost Quality</h5>
                            <p class="opacity-90 mb-0 text-white">Target the top 10% of talent, not just the available ones</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="benefit-icon bg-primary rounded-circle p-2 me-3">
                            <i class="bi bi-cash text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-white">Cut Costs</h5>
                            <p class="opacity-90 mb-0 text-white">Premium recruitment tools at a fraction of the agency cost</p>
                        </div>
                    </div>
                </div>

                <a href="<?= base_url('register') ?>" class="btn btn-warning btn-lg px-5">
                    <i class="bi bi-plus-circle me-2"></i>Post Your First Job Now
                </a>
            </div>

            <div class="col-lg-6 d-none d-lg-block">
                <div class="split-screen-visual position-relative">
                    <!-- Stress Side -->
                    <div class="stress-side card border-0 shadow-lg mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="stress-icon bg-white bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-emoji-frown fs-1 text-danger"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold mb-1 text-dark">Traditional Hiring</h5>
                                    <p class="mb-0 text-muted">Sifting through 100+ resumes manually</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success Side -->
                    <div class="success-side card border-0 shadow-lg">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="success-icon bg-white bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-emoji-smile fs-1 text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold mb-1 text-dark">JobberRecruit Hiring</h5>
                                    <p class="mb-0 text-muted">Pre-screened candidates in your dashboard</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-90"></div>
    <div class="position-absolute top-0 start-0 w-100 h-100">
        <img src="<?= base_url('images/hiring-process.webp') ?>"
            alt="Hiring process comparison"
            class="w-100 h-100 object-fit-cover"
            loading="eager">
    </div>
</section>

<!-- Why Post with Jobber Recruit -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">More Than Just a Job Board</h2>
            <p class="lead text-muted col-lg-8 mx-auto">
                When you pay for a posting on Jobber Recruit LTD, you unlock a suite of premium hiring tools
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="feature-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-eye-fill text-primary fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">3x Visibility Boost</h5>
                        <p class="text-muted small mb-0">
                            Your ad doesn't just sit there. We feature it on our homepage and push it through our premium channels to ensure the right eyes see it.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-rocket-takeoff-fill text-success fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">60-Minute Approval</h5>
                        <p class="text-muted small mb-0">
                            Time is money. On weekdays, your job goes live within an hour of posting.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-share-fill text-info fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Smart Distribution</h5>
                        <p class="text-muted small mb-0">
                            We don't just wait for applicants. We actively distribute your ad to targeted professional communities and networks unavailable to standard users.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-shield-fill text-warning fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Complete Privacy</h5>
                        <p class="text-muted small mb-0">
                            Hiring to replace current staff? Our Anonymity Feature lets you hide your company name and details completely.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-filter-circle-fill text-danger fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Advanced Screening</h5>
                        <p class="text-muted small mb-0">
                            Stop reading irrelevant CVs. Use our custom assessment filters to automatically rank candidates based on your specific questions.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-speedometer2 text-primary fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Dashboard Control</h5>
                        <p class="text-muted small mb-0">
                            Say goodbye to cluttered email. Manage, sort, filter, and shortlist all applications from your dedicated Employer Dashboard.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-headset text-success fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Dedicated Support</h5>
                        <p class="text-muted small mb-0">
                            You are never alone. Every premium poster gets a dedicated Account Officer to optimize your ad for the best results.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-instagram text-danger fs-1"></i>
                            <i class="bi bi-twitter-x text-dark ms-1 fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Social Dominance</h5>
                        <p class="text-muted small mb-0">
                            Your vacancy is showcased on our high-traffic Instagram and Twitter (X) pages, putting your brand in front of thousands instantly.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-whatsapp text-success fs-1"></i>
                            <i class="bi bi-telegram text-info ms-1 fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Direct Community Blasts</h5>
                        <p class="text-muted small mb-0">
                            We push your job directly into our exclusive WhatsApp and Telegram groups for instant visibility on candidates' phones.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Collaborator Ecosystem -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="collaborator-card card border-0 bg-primary text-white shadow-lg">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-people-fill fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">The Collaborator Ecosystem</h4>
                        <p class="mb-0">
                            We have established partnerships with other influencers and career platforms. When we post, they repost—amplifying your reach exponentially beyond our own audience.
                        </p>
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

<!-- Social Proof & Trust -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4">Join the Smartest Hiring Teams</h3>
                <p class="lead text-muted mb-4">
                    Over 160 companies—from agile SMEs to major Multinationals—trust Jobber Recruit LTD every month to build their teams.
                </p>

                <!-- Testimonial -->
                <div class="testimonial-card card border-0 shadow-sm bg-primary bg-opacity-5">
                    <div class="card-body p-4">
                        <div class="quote-icon mb-3">
                            <i class="bi bi-quote text-secondary fs-1"></i>
                        </div>
                        <p class="fst-italic text-white mb-4">
                            "The process was seamless. I created an account, funded my wallet, and posted my ad in under 10 minutes. The quality of candidates was exactly what we needed."
                        </p>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-circle text-white fs-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold text-light mb-0">HR Director</h6>
                                <small class="text-white">Leading FMCG Company</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h4 class="fw-bold text-center text-primary mb-4">Trusted By Leading Companies</h4>

                        <!-- Company Logos Grid -->
                        <div class="row row-cols-3 g-4">
                            <div class="col">
                                <div class="company-logo bg-white p-4 rounded shadow-sm d-flex align-items-center justify-content-center">
                                    <div class="text-center fw-bold text-primary">MTN</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="company-logo bg-white p-4 rounded shadow-sm d-flex align-items-center justify-content-center">
                                    <div class="text-center fw-bold text-primary">Union Bank</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="company-logo bg-white p-4 rounded shadow-sm d-flex align-items-center justify-content-center">
                                    <div class="text-center fw-bold text-primary">TotalEnergies</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="company-logo bg-white p-4 rounded shadow-sm d-flex align-items-center justify-content-center">
                                    <div class="text-center fw-bold text-primary">SME 1</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="company-logo bg-white p-4 rounded shadow-sm d-flex align-items-center justify-content-center">
                                    <div class="text-center fw-bold text-primary">SME 2</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="company-logo bg-white p-4 rounded shadow-sm d-flex align-items-center justify-content-center">
                                    <div class="text-center fw-bold text-primary">SME 3</div>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Stats -->
                        <div class="row mt-4 pt-4 border-top">
                            <div class="col-6 text-center">
                                <h3 class="fw-bold text-primary mb-1">160+</h3>
                                <p class="text-muted small mb-0">Companies</p>
                            </div>
                            <div class="col-6 text-center">
                                <h3 class="fw-bold text-success mb-1">95%</h3>
                                <p class="text-muted small mb-0">Satisfaction Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Ready to Find Your Next Great Hire?</h2>
            <p class="lead text-muted col-lg-8 mx-auto">
                It takes less than 10 minutes to get started
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-3">1</div>
                    <h4 class="fw-bold mb-3">Create Account</h4>
                    <p class="text-muted">
                        Sign up as an employer and complete your company profile in minutes.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-3">2</div>
                    <h4 class="fw-bold mb-3">Make Payment</h4>
                    <p class="text-muted">
                        Choose your plan and make secure payment through our platform.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step-card text-center p-4">
                    <div class="step-number mb-3">3</div>
                    <h4 class="fw-bold mb-3">Post Job</h4>
                    <p class="text-muted">
                        Publish your job and watch the applications roll in from qualified candidates.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="<?= base_url('register') ?>" class="btn btn-primary btn-lg px-5 py-3">
                <i class="bi bi-rocket-takeoff me-2"></i>GET STARTED NOW
            </a>
            <p class="text-muted mt-3">
                Have questions before you start? Email our support team at
                <a href="mailto:support@jobberrecruit.com" class="text-primary fw-bold">support@jobberrecruit.com</a>
            </p>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
    }

    /* Hero Section */
    .job-posting-hero {
        background: linear-gradient(#005DA8b3, #005DA8b3),
            url('<?= base_url('images/hiring-process.webp') ?>');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .min-vh-80 {
        min-height: 80vh;
    }

    .split-screen-visual {
        position: relative;
        padding-left: 20px;
    }

    .stress-side {
        position: relative;
        border-left: 4px solid #dc3545;
        margin-left: auto;
        max-width: 90%;
    }

    .success-side {
        position: relative;
        border-left: 4px solid #198754;
        margin-right: auto;
        max-width: 90%;
    }

    .stress-side::before {
        content: '❌';
        position: absolute;
        left: -25px;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .success-side::before {
        content: '✅';
        position: absolute;
        right: -25px;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Benefit Icons */
    .benefit-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Feature Cards */
    .feature-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(240, 137, 14, 0.1) !important;
        border-color: #F5A623 !important;
    }

    .feature-icon i {
        font-size: 2.5rem;
    }

    /* Collaborator Card */
    .collaborator-card {
        background: linear-gradient(135deg, #F5A623, #e67e00) !important;
        transition: all 0.3s ease;
    }

    .collaborator-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(240, 137, 14, 0.2) !important;
    }

    /* Testimonial */
    .testimonial-card {
        border-left: 4px solid #F5A623;
    }

    .quote-icon {
        opacity: 0.2;
    }

    /* Company Logos */
    .company-logo {
        height: 100px;
        transition: all 0.3s ease;
        border: 1px solid rgba(13, 96, 158, 0.1);
    }

    .company-logo:hover {
        transform: scale(1.05);
        border-color: #F5A623;
        box-shadow: 0 5px 15px rgba(240, 137, 14, 0.1);
    }

    /* Step Cards */
    .step-card {
        position: relative;
        background: white;
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .step-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    }

    .step-number {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #F5A623, #e67e00);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0 auto;
        position: relative;
    }

    .step-number::after {
        content: '';
        position: absolute;
        width: 70px;
        height: 70px;
        border: 2px dashed #F5A623;
        border-radius: 50%;
        opacity: 0.3;
    }

    /* Hover Effects */
    .hover-lift {
        transition: all 0.3s ease;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }

        .display-5 {
            font-size: 2rem;
        }

        .split-screen-visual {
            padding-left: 0;
            margin-top: 2rem;
        }

        .stress-side::before,
        .success-side::before {
            display: none;
        }

        .company-logo {
            height: 80px;
        }
    }

    @media (max-width: 576px) {
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .step-card {
            margin-bottom: 1rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to feature cards on scroll
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe elements
        document.querySelectorAll('.feature-card, .step-card').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>