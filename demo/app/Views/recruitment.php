<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Service',
    'name'     => 'Recruitment Agency Services in Nigeria',
    'description' => 'Professional recruitment and staffing solutions connecting Nigerian businesses with top executive, mid-level, and graduate talent.',
    'provider' => [
        '@type' => 'Organization',
        'name'  => 'Jobber Recruit LTD',
        'url'   => base_url(),
        'logo'  => base_url('images/logo.png'),
    ],
    'serviceType' => [
        'Executive Search',
        'Management Recruitment',
        'Graduate Recruitment',
        'Business Process Outsourcing',
    ],
    'areaServed' => [
        '@type' => 'Country',
        'name'  => 'Nigeria',
    ],
    'hasOfferCatalog' => [
        '@type' => 'OfferCatalog',
        'name'  => 'Recruitment Services',
        'itemListElement' => [
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Executive Search']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Mid-Level Management Recruitment']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Graduate Recruitment']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Business Process Outsourcing']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'HR Consulting']],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="recruitment-hero py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-white mb-4">
                    Transform Your Business with <span class="text-warning">Precision Hiring</span>
                </h1>
                <p class="lead text-light mb-4">
                    Stop searching. Start hiring. At JobberRecruit, we don't just fill vacancies; we analyse your business DNA to find the talent that drives profit and culture.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#services" class="btn btn-warning btn-lg px-4">
                        <i class="bi bi-people-fill me-2"></i>View Our Services
                    </a>
                    <a href="#quote-form" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-chat-quote me-2"></i>Request Candidate Quote
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="position-relative">
                    <div class="floating-card card border-0 shadow-lg bg-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-primary mb-3">Quick Hire Request</h5>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-clock-history text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Average Time-to-Hire</h6>
                                    <p class="text-muted small mb-0">24-48 hours for urgent roles</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-check-circle text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Candidate Success Rate</h6>
                                    <p class="text-muted small mb-0">95% client retention</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-70"></div>
    <div class="position-absolute top-0 start-0 w-100 h-100">
        <img src="<?= base_url('images/office.avif') ?>"
            alt="Professional team collaborating in Lagos office"
            class="w-100 h-100 object-fit-cover"
            loading="eager">
    </div>
</section>

<!-- Our Philosophy -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-4">Recruitment Reimagined</h2>
                <p class="lead text-muted mb-5">
                    We believe that a resume tells only half the story. While other agencies match keywords, JobberRecruit matches potential to purpose. We understand that a "Marketing Manager" in a startup requires a different mindset than one in a multinational.
                </p>
                <p class="text-muted">
                    We dig deep into your organizational culture to design a recruitment strategy that fits you.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="philosophy-icon mb-4">
                        <i class="bi bi-search text-white fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Understanding</h4>
                    <p class="text-muted">
                        We dig deep into your organizational culture to understand what makes your team tick and what drives your success.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="philosophy-icon mb-4">
                        <i class="bi bi-puzzle text-white fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Matching</h4>
                    <p class="text-muted">
                        We analyze both skills and mindset to find candidates who fit your unique requirements and company culture.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="philosophy-icon mb-4">
                        <i class="bi bi-handshake text-white fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Success</h4>
                    <p class="text-muted">
                        We measure success by your satisfaction and the long-term performance of the candidates we place.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Partner With Us -->
<section class="py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-4">360-Degree Digital Reach</h2>
                <p class="lead text-muted mb-4">
                    We don't rely on luck. We rely on reach. JobberRecruit leverages a massive digital ecosystem comprising:
                </p>

                <div class="digital-reach-grid mb-4">
                    <div class="reach-item d-flex align-items-center mb-3">
                        <div class="reach-icon bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-whatsapp text-success fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Active Social Communities</h5>
                            <p class="text-muted small mb-0">Thousands of members in our niche WhatsApp and Telegram groups</p>
                        </div>
                    </div>

                    <div class="reach-item d-flex align-items-center mb-3">
                        <div class="reach-icon bg-info bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-twitter-x text-info fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Social Media Authority</h5>
                            <p class="text-muted small mb-0">A commanding presence on X (Twitter) and Instagram</p>
                        </div>
                    </div>

                    <div class="reach-item d-flex align-items-center">
                        <div class="reach-icon bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-share text-primary fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Strategic Alliances</h5>
                            <p class="text-muted small mb-0">A network of digital collaborators who amplify our job alerts</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h3 class="fw-bold text-primary mb-4">The Jobber Advantage</h3>
                        <p class="text-muted mb-4">
                            Why do leading Nigerian companies trust us with their most valuable asset—their people?
                        </p>

                        <div class="advantage-item mb-4">
                            <h5 class="fw-bold mb-2">
                                <i class="bi bi-database text-primary me-2"></i>
                                Unrivalled Talent Pool
                            </h5>
                            <p class="text-muted mb-0">
                                We don't just rely on job boards. We utilize a proprietary database and an aggressive offline network to access passive candidates you won't find on LinkedIn.
                            </p>
                        </div>

                        <div class="advantage-item mb-4">
                            <h5 class="fw-bold mb-2">
                                <i class="bi bi-person-badge text-primary me-2"></i>
                                Bespoke Headhunting
                            </h5>
                            <p class="text-muted mb-0">
                                We are not CV shufflers. We are talent architects. We study your vision and culture to ensure every candidate is a long-term fit.
                            </p>
                        </div>

                        <div class="advantage-item">
                            <h5 class="fw-bold mb-2">
                                <i class="bi bi-cash-stack text-primary me-2"></i>
                                ROI-Focused Results
                            </h5>
                            <p class="text-muted mb-0">
                                Bad hires are expensive. Our rigorous vetting process ensures a lower staff turnover rate, saving you money on training and re-hiring.
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <a href="#quote-form" class="btn btn-primary">
                                <i class="bi bi-telephone me-2"></i>Contact Us
                            </a>
                        </div>
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

<!-- Core Services -->
<section id="services" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Comprehensive Staffing Solutions</h2>
            <p class="lead text-muted col-lg-8 mx-auto">
                As a premier recruitment company in Nigeria, we offer tailored solutions across the private, public, and NGO sectors.
            </p>
        </div>

        <div class="row g-4">
            <!-- Executive Search -->
            <div class="col-lg-6">
                <div class="card service-card h-100 border-0 shadow-sm hover-lift">
                    <div class="row g-0 h-100">
                        <div class="col-md-4 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="bi bi-trophy-fill text-primary fs-1"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-3">Executive Search & Headhunting</h4>
                                <p class="text-muted mb-3">
                                    Hiring leadership requires discretion and deep market insight. We specialize in identifying high-impact leaders who are often not actively looking for a job.
                                </p>
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-2">What we do:</h6>
                                    <p class="text-muted small mb-3">
                                        We map the market to find the "Hidden 10%" of top-tier talent.
                                    </p>
                                    <h6 class="fw-bold mb-2">Roles we fill:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">CEO</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">CFO</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">COO</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">CTO</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">Head of People</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">VP of Sales</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">Directors</span>
                                    </div>
                                </div>
                                <a href="#quote-form" class="btn btn-primary">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mid-Level Management -->
            <div class="col-lg-6">
                <div class="card service-card h-100 border-0 shadow-sm hover-lift">
                    <div class="row g-0 h-100">
                        <div class="col-md-4 bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="bi bi-gear-fill text-info fs-1"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-3">Mid-Level Management Recruitment</h4>
                                <p class="text-muted mb-3">
                                    Mid-level managers are the engine room of your organization. Without strong operational leadership, growth stalls. We source multi-skilled managers with verified track records of stability and performance.
                                </p>
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-2">Our Promise:</h6>
                                    <p class="text-muted small mb-3">
                                        We focus on candidates with high emotional intelligence and operational expertise to ensure your day-to-day operations thrive.
                                    </p>
                                </div>
                                <a href="#quote-form" class="btn btn-info">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graduate Talent -->
            <div class="col-lg-6">
                <div class="card service-card h-100 border-0 shadow-sm hover-lift">
                    <div class="row g-0 h-100">
                        <div class="col-md-4 bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="bi bi-mortarboard-fill text-success fs-1"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-3">Graduate & Entry-Level Talent</h4>
                                <p class="text-muted mb-3">
                                    Harness the energy of the future. We identify high-potential Gen-Z talent characterized by digital savviness, adaptability, and high IQ.
                                </p>
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-2">The Process:</h6>
                                    <p class="text-muted small mb-3">
                                        We utilize aptitude tests, group assessments, and behavioral interviews to filter thousands of applicants down to the top 1% who are ready to help your organization win.
                                    </p>
                                </div>
                                <a href="#quote-form" class="btn btn-success">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BPO -->
            <div class="col-lg-6">
                <div class="card service-card h-100 border-0 shadow-sm hover-lift">
                    <div class="row g-0 h-100">
                        <div class="col-md-4 bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="bi bi-briefcase-fill text-warning fs-1"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-3">Business Process Outsourcing (BPO)</h4>
                                <p class="text-muted mb-3">
                                    Reduce your overhead and liability. Our outsourcing service handles the payroll, taxes, and labour law compliance, allowing you to focus on your core business.
                                </p>
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-2">Perfect for:</h6>
                                    <p class="text-muted small mb-3">
                                        Contract staff, remote teams, seasonal hires, and temporary projects.
                                    </p>
                                    <h6 class="fw-bold mb-2">Common Roles:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-warning bg-opacity-10 text-dark">Call Centre Agents</span>
                                        <span class="badge bg-warning bg-opacity-10 text-dark">Data Entry</span>
                                        <span class="badge bg-warning bg-opacity-10 text-dark">Sales Representatives</span>
                                        <span class="badge bg-warning bg-opacity-10 text-dark">IT Support</span>
                                        <span class="badge bg-warning bg-opacity-10 text-dark">Developers</span>
                                    </div>
                                </div>
                                <a href="#quote-form" class="btn btn-warning">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section id="quote-form" class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="position-relative">
                    <div class="card border-0 shadow-lg bg-white">
                        <div class="card-body p-4 text-center">
                            <h3 class="fw-bold text-primary mb-3">Let's Build Your Dream Team</h3>
                            <div class="mb-4">
                                <img src="<?= base_url('images/logo.png') ?>" alt="Let's Build Your Dream Team" class="img-fluid rounded shadow">
                            </div>
                            <p class="text-muted mb-0">
                                With deep industry expertise, Jobber Recruit LTD has the tools and knowledge to handle your staffing needs efficiently.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h3 class="fw-bold text-primary mb-4">Ready to Hire? Let's Talk.</h3>
                        <p class="text-muted mb-4">
                            Tell us who you are looking for, and we will do the rest.
                        </p>

                        <form action="<?= base_url('submit-recruitment-inquiry') ?>" method="POST" id="recruitmentForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="fullName" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="fullName" name="fullName" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="companyName" class="form-label">Company Name *</label>
                                    <input type="text" class="form-control" id="companyName" name="companyName" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="role" class="form-label">Role to Hire *</label>
                                    <input type="text" class="form-control" id="role" name="role" placeholder="e.g., Digital Marketer, Accountant" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="experience" class="form-label">Required Experience Level *</label>
                                    <select class="form-select" id="experience" name="experience" required>
                                        <option value="">Select level</option>
                                        <option value="entry">Entry Level</option>
                                        <option value="mid">Mid Level</option>
                                        <option value="senior">Senior Level</option>
                                        <option value="executive">Executive</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="budget" class="form-label">Budget/Salary Range</label>
                                    <input type="text" class="form-control" id="budget" name="budget" placeholder="e.g., ₦200,000 - ₦300,000">
                                </div>

                                <div class="col-md-6">
                                    <label for="schedule" class="form-label">Working Schedule</label>
                                    <input type="text" class="form-control" id="schedule" name="schedule" placeholder="e.g., Mon to Fri or Mon to Sat or Remote or Hybrid">
                                </div>

                                <div class="col-12">
                                    <label for="location" class="form-label">Location *</label>
                                    <select class="form-select" id="location" name="location" required>
                                        <option value="">Select State</option>
                                        <option value="Lagos">Lagos</option>
                                        <option value="Abuja">Abuja</option>
                                        <option value="Rivers">Rivers</option>
                                        <option value="Oyo">Oyo</option>
                                        <option value="Kano">Kano</option>
                                        <option value="Edo">Edo</option>
                                        <option value="Delta">Delta</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label">How can we help? *</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" placeholder="Tell us about your hiring needs..." required></textarea>
                                </div>

                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label small text-muted" for="terms">
                                            I agree to receive recruitment updates and agree to JobberRecruit's Terms of Service and Privacy Policy.
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-send me-2"></i>Submit Inquiry
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
    .recruitment-hero {
        background: linear-gradient(#005DA8b3, #005DA8b3),
            url('<?= base_url('images/office-team.jpg') ?>');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .min-vh-80 {
        min-height: 80vh;
    }

    .floating-card {
        position: absolute;
        right: -30px;
        bottom: -30px;
        animation: float 3s ease-in-out infinite;
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

    /* Philosophy Icons */
    .philosophy-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #F5A623, rgba(240, 137, 14, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .philosophy-icon i {
        font-size: 2.5rem;
        color: white;
    }

    /* Digital Reach Grid */
    .digital-reach-grid .reach-icon {
        transition: transform 0.3s ease;
    }

    .digital-reach-grid .reach-item:hover .reach-icon {
        transform: rotate(10deg) scale(1.1);
    }

    /* Service Cards */
    .service-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }

    /* Form Styling */
    .form-control:focus,
    .form-select:focus {
        border-color: #F5A623;
        box-shadow: 0 0 0 0.25rem rgba(240, 137, 14, 0.25);
    }

    /* Hover Effects */
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(240, 137, 14, 0.1) !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }

        .display-5 {
            font-size: 2rem;
        }

        .floating-card {
            position: absolute;
            right: 0;
            bottom: 0;
            margin-top: 2rem;
        }

        .service-card {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Form submission handling
        const recruitmentForm = document.getElementById('recruitmentForm');
        if (recruitmentForm) {
            recruitmentForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Basic validation
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (isValid) {
                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
                    submitBtn.disabled = true;

                    // Simulate API call (replace with actual AJAX call)
                    setTimeout(() => {
                        // In production, use fetch or XMLHttpRequest here
                        alert('Thank you for your inquiry! We will contact you within 24 hours.');
                        recruitmentForm.reset();
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        // Scroll to top
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }, 1500);
                }
            });
        }

        // Add animation to service cards on scroll
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
        document.querySelectorAll('.service-card').forEach(el => {
            observer.observe(el);
        });
    });
</script>
<?= $this->endSection() ?>