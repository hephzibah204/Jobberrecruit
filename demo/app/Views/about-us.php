<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "AboutPage",
        "name": "About Jobber Recruit LTD",
        "description": "Redefining recruitment in Nigeria through a massive digital talent ecosystem that connects ambition with opportunity faster than anyone else.",
        "url": "<?= current_url() ?>",
        "publisher": {
            "@type": "Organization",
            "name": "Jobber Recruit LTD",
            "alternateName": "JobberRecruit",
            "logo": {
                "@type": "ImageObject",
                "url": "<?= base_url('images/logo.png') ?>"
            },
            "foundingDate": "2023",
            "address": {
                "@type": "PostalAddress",
                "addressCountry": "NG"
            },
            "founder": "Jobber Recruit Team"
        },
        "mainEntity": {
            "@type": "Organization",
            "name": "Jobber Recruit LTD",
            "description": "A digital-first recruitment agency transforming how talent connects with opportunity across Nigeria through social media networks and digital ecosystems.",
            "url": "<?= base_url() ?>",
            "founder": "Jobber Recruit Team",
            "foundingDate": "2023",
            "numberOfEmployees": "50+",
            "owns": {
                "@type": "WebSite",
                "name": "JobberRecruit Platform",
                "url": "<?= base_url() ?>"
            }
        }
    }
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="about-hero-section py-5 position-relative overflow-hidden fade-in">
    <div class="container position-relative z-1">
        <div class="row align-items-center min-vh-70">
            <div class="col-lg-8 mx-auto text-center">
                <div class="mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-white border border-primary border-opacity-25  px-3 py-3 mb-3 d-inline-block">
                        Redefining Recruitment in Nigeria
                    </span>
                    <h1 class="display-4 fw-bold text-primary mb-4">
                        We Don't Just Fill Seats.<br>
                        <span class="text-gradient-primary">We Fuel Growth.</span>
                    </h1>
                    <p class="lead text-dark mb-4">
                        At Jobber Recruit LTD, we believe that the difference between a good company and a great one is simple: <strong>The People.</strong>
                    </p>
                    <p class="text-muted mb-5">
                        Finding those people has changed. The old ways don't work in today's fast-paced economy. You need a partner who moves as fast as the market. <strong>You need a partner who is plugged in.</strong>
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="<?= base_url('contact-us') ?>" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-rocket-takeoff me-2"></i>Partner With Us
                        </a>
                        <a href="<?= base_url('jobs') ?>" class="btn btn-outline-primary btn-lg px-4">
                            <i class="bi bi-search me-2"></i>Explore Jobs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Elements -->
    <div class="position-absolute top-0 end-0 w-50 h-100 bg-gradient-primary opacity-5 rounded-start-5"></div>
    <div class="position-absolute bottom-0 start-0">
        <svg width="300" height="300" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="150" cy="150" r="150" fill="url(#paint0_linear)" fill-opacity="0.05" />
            <defs>
                <linearGradient id="paint0_linear" x1="0" y1="0" x2="300" y2="300" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#F0890E" />
                    <stop offset="1" stop-color="#F0890E" />
                </linearGradient>
            </defs>
        </svg>
    </div>
</section>

<!-- Our Unique Mission & Vision -->
<section class="py-5 fade-in">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 hover-lift" style="border-left: 4px solid #F0890E;">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                <i class="bi bi-rocket-fill text-white fs-2"></i>
                            </div>
                            <h3 class="mb-0">Our Mission</h3>
                        </div>
                        <blockquote class="blockquote border-start border-3 border-primary ps-4 mb-4">
                            <p class="fs-5 fst-italic text-dark">
                                "To accelerate business performance by instantly synchronizing the right talent with the right opportunity through an unrivaled digital ecosystem and precise human insight."
                            </p>
                        </blockquote>
                        <div class="mt-4">
                            <h5 class="fw-semibold mb-3">What This Means:</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-2"><i class="bi bi-lightning-fill text-warning me-2"></i>Instant talent-opportunity matching</li>
                                <li class="mb-2"><i class="bi bi-lightning-fill text-warning me-2"></i>Digital ecosystem-driven recruitment</li>
                                <li class="mb-2"><i class="bi bi-lightning-fill text-warning me-2"></i>Business performance acceleration</li>
                                <li class="mb-2"><i class="bi bi-lightning-fill text-warning me-2"></i>Precision through human expertise</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 hover-lift" style="border-left: 4px solid #0D609E;">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-info bg-opacity-10 rounded-3 p-3 me-3">
                                <i class="bi bi-eye-fill text-info fs-2"></i>
                            </div>
                            <h3 class="mb-0">Our Vision</h3>
                        </div>
                        <blockquote class="blockquote border-start border-3 border-info ps-4 mb-4">
                            <p class="fs-5 fst-italic text-dark">
                                "To become Nigeria's most dynamic talent engine, creating a future where no potential goes wasted and no business goal is stalled by a lack of quality personnel."
                            </p>
                        </blockquote>
                        <div class="mt-4">
                            <h5 class="fw-semibold mb-3">The Future We're Building:</h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-2"><i class="bi bi-star-fill text-warning me-2"></i>Nigeria's #1 talent engine</li>
                                <li class="mb-2"><i class="bi bi-star-fill text-warning me-2"></i>Zero wasted potential</li>
                                <li class="mb-2"><i class="bi bi-star-fill text-warning me-2"></i>Zero business delays from hiring</li>
                                <li class="mb-2"><i class="bi bi-star-fill text-warning me-2"></i>A dynamic, responsive ecosystem</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- The Jobber Story -->
<section class="py-5 bg-light fade-in">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h2 class="display-5 fw-bold mb-4">Why We Exist</h2>
                <div class="story-timeline">
                    <div class="timeline-item mb-4">
                        <div class="timeline-badge bg-primary">!</div>
                        <div class="timeline-content">
                            <h5 class="fw-bold mb-2">The Frustration We Saw</h5>
                            <p class="text-muted mb-0">
                                Brilliant candidates lost in social media noise. Great companies struggling to find talent in wrong places.
                            </p>
                        </div>
                    </div>
                    <div class="timeline-item mb-4">
                        <div class="timeline-badge bg-warning">!</div>
                        <div class="timeline-content">
                            <h5 class="fw-bold mb-2">The Realization</h5>
                            <p class="text-muted mb-0">
                                Recruitment isn't just about reviewing CVs; it's about <strong>Reach and Resonance</strong>.
                            </p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-badge bg-success">!</div>
                        <div class="timeline-content">
                            <h5 class="fw-bold mb-2">Our Solution</h5>
                            <p class="text-muted mb-0">
                                We built a recruitment agency that functions like a modern media powerhouse. We don't just "post" jobs; we <strong>broadcast</strong> them.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h3 class="fw-bold mb-4">Our Digital-First Approach</h3>
                        <p class="text-muted mb-4">
                            By combining traditional HR expertise with a massive footprint on social platforms and digital networks, we bridge the gap between talent and employers faster than anyone else.
                        </p>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Traditional HR Expertise</h6>
                                <p class="text-muted small mb-0">Decades of recruitment knowledge</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Massive Digital Footprint</h6>
                                <p class="text-muted small mb-0">Thousands of active users across platforms</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Unbeatable Speed</h6>
                                <p class="text-muted small mb-0">Faster connections, quicker hires</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What Makes Us Different -->
<section class="py-5 fade-in">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">What Makes Us Different?</h2>
            <p class="lead text-muted col-lg-8 mx-auto">
                We're not your traditional recruitment agency. Here's why:
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-4">
                            <i class="bi bi-megaphone-fill text-white fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Viral Reach</h5>
                        <p class="text-muted mb-0">
                            Most agencies have a database. We have a community. Our network reaches passive candidates others can't find.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-4">
                            <i class="bi bi-bullseye text-white fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Precision, Not Volume</h5>
                        <p class="text-muted mb-0">
                            We don't flood your inbox. We filter, interview, and assess. Every candidate is a potential missing piece.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle p-3 mx-auto mb-4">
                            <i class="bi bi-person-heart text-info fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Personal Approach</h5>
                        <p class="text-muted mb-0">
                            Candidates are people, not numbers. Clients are partners, not paychecks. We understand culture.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle p-3 mx-auto mb-4">
                            <i class="bi bi-lightning-charge-fill text-warning fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Agility</h5>
                        <p class="text-muted mb-0">
                            Business moves fast. So do we. We dramatically cut down "Time-to-Hire" to keep your operations running smoothly.
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

<!-- The Ecosystem -->
<section class="py-5 bg-light fade-in">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Powered by a Massive Digital Network</h2>
            <p class="lead text-muted col-lg-8 mx-auto">
                We are more than a website. When you hire with us, you aren't just hiring a recruiter. You are hiring a broadcasting network.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 bg-white shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="platform-icon whatsapp mb-3">
                            <i class="bi bi-whatsapp fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">WhatsApp</h5>
                        <p class="text-muted small mb-0">High-volume groups with thousands of active job seekers</p>
                        <div class="mt-3">
                            <span class="badge bg-success bg-opacity-10 text-white">Direct Access</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 bg-white shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="platform-icon telegram mb-3">
                            <i class="bi bi-telegram fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Telegram</h5>
                        <p class="text-muted small mb-0">Active communities and broadcast channels</p>
                        <div class="mt-3">
                            <span class="badge bg-info bg-opacity-10 text-info">Real-time Updates</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 bg-white shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="platform-icon instagram mb-3">
                            <i class="bi bi-instagram fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Instagram</h5>
                        <p class="text-muted small mb-0">Dominant presence reaching young professionals</p>
                        <div class="mt-3">
                            <span class="badge bg-danger bg-opacity-10 text-danger">Visual Impact</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 bg-white shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="platform-icon twitter mb-3">
                            <i class="bi bi-twitter-x fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Twitter (X)</h5>
                        <p class="text-muted small mb-0">Strategic partnerships and industry influence</p>
                        <div class="mt-3">
                            <span class="badge bg-dark bg-opacity-10 text-dark">Industry Authority</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 bg-primary text-white shadow-lg">
                    <div class="card-body p-4 text-center">
                        <h4 class="fw-bold mb-3">Plus: A Network of Strategic Collaborators</h4>
                        <p class="mb-0 opacity-90">
                            We've built relationships with industry partners, universities, and professional associations to amplify our reach even further.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Impact -->
<section class="py-5 fade-in">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Our Impact Across Nigeria</h2>
            <p class="lead text-muted col-lg-8 mx-auto">
                Bridging talent with opportunity faster than traditional methods
            </p>
        </div>

        <div class="row g-4">
            <div class="col-6 col-md-3">
                <div class="text-center">
                    <div class="counter display-4 fw-bold text-primary mb-2" data-count="50000">0</div>
                    <h6 class="text-muted">Active Community Members</h6>
                    <p class="small text-muted mt-2">Across WhatsApp & Telegram groups</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-center">
                    <div class="counter display-4 fw-bold text-success mb-2" data-count="85">0</div>
                    <h6 class="text-muted">Faster Hiring</h6>
                    <p class="small text-muted mt-2">Compared to traditional agencies</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-center">
                    <div class="counter display-4 fw-bold text-info mb-2" data-count="95">0</div>
                    <h6 class="text-muted">Client Retention</h6>
                    <p class="small text-muted mt-2">Companies that partner with us stay</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-center">
                    <div class="counter display-4 fw-bold text-warning mb-2" data-count="24">0</div>
                    <h6 class="text-muted">Hour Response Time</h6>
                    <p class="small text-muted mt-2">Average time to first candidate</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white fade-in">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-6 fw-bold mb-4">Ready to Experience Modern Recruitment?</h2>
                <p class="lead mb-5 opacity-90">
                    Stop using outdated methods. Partner with an agency that moves at the speed of today's market.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="<?= base_url('recruitment') ?>" class="btn btn-light btn-lg px-5">
                        <i class="bi bi-building me-2"></i>I Need Talent
                    </a>
                    <a href="<?= base_url('contact-us') ?>" class="btn btn-outline-light btn-lg px-5">
                        <i class="bi bi-chat-dots me-2"></i>Book a Consultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* =========================================================
   ABOUT PAGE – JOBBER RECRUIT STYLES
   ========================================================= */
    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
    }


    /* Hero Section */
    .about-hero-section {
        background: linear-gradient(135deg, #fff8f0 0%, #ffffff 100%);
        position: relative;
        overflow: hidden;
    }

    .about-hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(240, 137, 14, 0.05) 0%, transparent 70%);
    }

    .min-vh-70 {
        min-height: 70vh;
    }

    /* Gradient Text */
    .text-gradient-primary {
        background: linear-gradient(90deg, #F0890E 0%, #e67e00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Cards & Hover Effects */
    .hover-lift {
        transition: all 0.3s ease;
        border: 1px solid rgba(240, 137, 14, 0.1);
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(240, 137, 14, 0.1) !important;
        border-color: rgba(240, 137, 14, 0.2);
    }

    /* Icon Wrapper */
    .icon-wrapper {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .card:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    /* Story Timeline */
    .story-timeline {
        position: relative;
        padding-left: 30px;
    }

    .story-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #F0890E, #0D609E);
        opacity: 0.3;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-badge {
        position: absolute;
        left: -30px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 12px;
    }

    .timeline-content {
        padding-left: 20px;
    }

    /* Platform Icons */
    .platform-icon i {
        font-size: 3rem !important;
    }

    .platform-icon.whatsapp i {
        color: #25D366;
    }

    .platform-icon.telegram i {
        color: #0088cc;
    }

    .platform-icon.instagram i {
        color: #E4405F;
    }

    .platform-icon.twitter i {
        color: #000000;
    }

    /* Counter Animation */
    .counter {
        font-size: 3.5rem;
        font-weight: 700;
        background: linear-gradient(90deg, #F0890E, #0D609E);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Primary Color Override */
    .bg-primary {
        background: linear-gradient(135deg, #F0890E 0%, #e67e00 100%) !important;
    }

    .text-primary {
        color: #F0890E !important;
    }

    .btn-primary {
        background: #F0890E;
        border-color: #F0890E;
    }

    .btn-primary:hover {
        background: #d87b0d;
        border-color: #d87b0d;
    }

    /* Fade-in Animation */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Blockquote Styling */
    blockquote {
        border-left-color: #F0890E !important;
    }

    blockquote.border-info {
        border-left-color: #0D609E !important;
    }

    /* Badge Styles */
    .badge.bg-primary {
        background-color: rgba(240, 137, 14, 0.1) !important;
        color: #F0890E;
    }

    /* Section Spacing */
    section {
        padding: 5rem 0;
    }

    /* =========================================================
   RESPONSIVE ADJUSTMENTS
   ========================================================= */
    @media (max-width: 768px) {
        .display-4 {
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

        section {
            padding: 3rem 0;
        }
    }

    @media (max-width: 576px) {
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            width: 100%;
            margin-bottom: 10px;
        }

        .d-flex.gap-3 {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter Animation
        const counters = document.querySelectorAll('.counter');
        const speed = 200;

        const animateCounter = (counter) => {
            const target = +counter.getAttribute('data-count');
            const count = +counter.innerText.replace(/,/g, '');
            const increment = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + increment).toLocaleString();
                setTimeout(() => animateCounter(counter), 1);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };

        // Intersection Observer for counters and fade-in
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('counter')) {
                        entry.target.innerText = '0';
                        animateCounter(entry.target);
                    }
                    if (entry.target.classList.contains('fade-in')) {
                        entry.target.classList.add('visible');
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });

        // Observe all counters and fade-in elements
        counters.forEach(counter => observer.observe(counter));
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

        // Add platform icon hover effects
        document.querySelectorAll('.platform-icon').forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
            });
            icon.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });
</script>
<?= $this->endSection() ?>