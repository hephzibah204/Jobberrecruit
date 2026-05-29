<?= $this->extend('templates/base') ?>

<?= $this->section('meta') ?>
<meta property="og:locale" content="en_US">
<meta name="twitter:site" content="@JobberRecruit">
<link rel="alternate" href="<?= current_url() ?>" hreflang="en">
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<!-- HERO SECTION -->
<section class="candidate-hero py-5 text-white" aria-labelledby="hero-heading">
    <div class="container text-center">

        <!-- Lottie Animation -->
        <div class="mb-4" role="presentation">
            <lottie-player
                src="https://assets2.lottiefiles.com/packages/lf20_kyu7xb1v.json"
                background="transparent"
                speed="1"
                style="width: 260px; height: 260px; margin:auto;"
                loop autoplay
                aria-label="Animation showing candidate success">
            </lottie-player>
        </div>

        <h1 id="hero-heading" class="fw-bold display-5 text-white mb-3">Find Your Dream Remote Job Today</h1>
        <p class="lead mt-3 text-white fs-4">
            Join 10,000+ professionals connecting with verified employers for high-quality remote and local opportunities.
        </p>

        <!-- CTA with Schema-friendly markup -->
        <div class="cta-buttons mt-4">
            <a href="<?= base_url('register') ?>" class="btn btn-primary btn-lg px-5 py-3 fw-semibold" aria-label="Create your free candidate account">
                Create Your Free Candidate Profile
                <i class="bi bi-arrow-right ms-2" aria-hidden="true"></i>
            </a>
            <p class="mt-3 text-white-50 small">No credit card required • Free forever for candidates</p>
        </div>

        <!-- Trust indicators -->
        <div class="trust-badges mt-5 d-flex flex-wrap justify-content-center gap-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-shield-check me-2 text-success"></i>
                <span>Verified Employers</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-clock-history me-2 text-info"></i>
                <span>Fast Hiring Process</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-globe me-2 text-warning"></i>
                <span>Global Opportunities</span>
            </div>
        </div>
    </div>
</section>


<!-- VIDEO INTRO SECTION -->
<section class="py-5 bg-white" aria-labelledby="video-heading">
    <div class="container">
        <h2 id="video-heading" class="fw-bold text-center mb-3">How JobberRecruit Helps You Get Hired Faster</h2>
        <p class="text-center text-muted mb-4 fs-5">Watch how thousands of candidates found their dream jobs through our platform</p>

        <div class="ratio ratio-16x9 shadow-lg rounded-4 mx-auto" style="max-width: 900px;" role="region" aria-label="Platform introduction video">
            <iframe
                src="https://www.youtube.com/embed/TXkjkFJHcLM"
                title="How JobberRecruit Works - Candidate Platform Tour"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                class="rounded-4"
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>


<!-- ANIMATED STATS with Schema markup -->
<section class="py-5 bg-light" aria-label="Platform statistics">
    <div class="container">
        <div class="row text-center g-4">

            <div class="col-md-3" itemscope itemtype="https://schema.org/InteractionCounter">
                <h2 class="fw-bold text-primary display-5 counter" data-target="10000" itemprop="userInteractionCount">0</h2>
                <p class="text-muted fs-5" itemprop="name">Active Candidates</p>
            </div>

            <div class="col-md-3" itemscope itemtype="https://schema.org/InteractionCounter">
                <h2 class="fw-bold text-success display-5 counter" data-target="800" itemprop="userInteractionCount">0</h2>
                <p class="text-muted fs-5" itemprop="name">Hiring Companies</p>
            </div>

            <div class="col-md-3" itemscope itemtype="https://schema.org/InteractionCounter">
                <h2 class="fw-bold text-info display-5 counter" data-target="4500" itemprop="userInteractionCount">0</h2>
                <p class="text-muted fs-5" itemprop="name">Jobs Posted</p>
            </div>

            <div class="col-md-3" itemscope itemtype="https://schema.org/InteractionCounter">
                <h2 class="fw-bold text-warning display-5 counter" data-target="96" itemprop="userInteractionCount">0%</h2>
                <p class="text-muted fs-5" itemprop="name">Success Rate</p>
            </div>

        </div>
    </div>
</section>


<!-- BENEFITS SECTION -->
<section class="py-5 bg-white" aria-labelledby="benefits-heading">
    <div class="container">
        <h2 id="benefits-heading" class="fw-bold text-center mb-5">Why Top Professionals Choose JobberRecruit</h2>

        <div class="row g-4 mt-4">

            <div class="col-md-4">
                <div class="feature-box p-4 text-center shadow-sm rounded-3 bg-white h-100">
                    <i class="bi bi-search-heart text-primary display-5 mb-3" aria-hidden="true"></i>
                    <h3 class="h5 fw-semibold mb-3">Smart Job Matching</h3>
                    <p class="text-muted">Our AI-powered matching algorithm connects you with roles that fit your skills, experience, and career goals.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-box p-4 text-center shadow-sm rounded-3 bg-white h-100">
                    <i class="bi bi-shield-lock text-success display-5 mb-3" aria-hidden="true"></i>
                    <h3 class="h5 fw-semibold mb-3">Verified Companies Only</h3>
                    <p class="text-muted">Every employer is thoroughly verified to ensure legitimate opportunities and fair compensation.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-box p-4 text-center shadow-sm rounded-3 bg-white h-100">
                    <i class="bi bi-lightning-charge text-warning display-5 mb-3" aria-hidden="true"></i>
                    <h3 class="h5 fw-semibold mb-3">Fast-Track Hiring</h3>
                    <p class="text-muted">Skip the resume black hole. Get direct access to hiring managers and decision-makers.</p>
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

<!-- HOW IT WORKS with Schema markup -->
<section class="py-5 bg-light" aria-labelledby="process-heading">
    <div class="container">
        <h2 id="process-heading" class="fw-bold text-center mb-5">Get Started in 4 Simple Steps</h2>

        <div class="row g-4">

            <div class="col-md-3" itemscope itemtype="https://schema.org/HowToStep">
                <meta itemprop="position" content="1">
                <div class="step-box p-4 bg-white shadow-sm rounded-3 text-center h-100">
                    <div class="step-circle mx-auto mb-3">1</div>
                    <h3 class="h6 fw-semibold" itemprop="name">Create Profile</h3>
                    <p class="text-muted small" itemprop="text">Sign up and build your professional profile with skills, experience, and portfolio.</p>
                </div>
            </div>

            <div class="col-md-3" itemscope itemtype="https://schema.org/HowToStep">
                <meta itemprop="position" content="2">
                <div class="step-box p-4 bg-white shadow-sm rounded-3 text-center h-100">
                    <div class="step-circle mx-auto mb-3">2</div>
                    <h3 class="h6 fw-semibold" itemprop="name">Skill Verification</h3>
                    <p class="text-muted small" itemprop="text">Take optional skill assessments to validate your expertise and stand out.</p>
                </div>
            </div>

            <div class="col-md-3" itemscope itemtype="https://schema.org/HowToStep">
                <meta itemprop="position" content="3">
                <div class="step-box p-4 bg-white shadow-sm rounded-3 text-center h-100">
                    <div class="step-circle mx-auto mb-3">3</div>
                    <h3 class="h6 fw-semibold" itemprop="name">Get Matched</h3>
                    <p class="text-muted small" itemprop="text">Receive personalized job matches based on your profile and preferences.</p>
                </div>
            </div>

            <div class="col-md-3" itemscope itemtype="https://schema.org/HowToStep">
                <meta itemprop="position" content="4">
                <div class="step-box p-4 bg-white shadow-sm rounded-3 text-center h-100">
                    <div class="step-circle mx-auto mb-3">4</div>
                    <h3 class="h6 fw-semibold" itemprop="name">Interview & Hire</h3>
                    <p class="text-muted small" itemprop="text">Connect directly with employers and land your next role faster.</p>
                </div>
            </div>

        </div>

        <div class="text-center mt-5">
            <a href="<?= base_url('register') ?>" class="btn btn-outline-primary btn-lg px-5">
                Start Free Today
                <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>


<!-- SUCCESS STORIES with Schema markup -->
<section class="py-5 bg-white" aria-labelledby="testimonials-heading">
    <div class="container">
        <h2 id="testimonials-heading" class="fw-bold text-center mb-5">Real Success Stories from Our Candidates</h2>

        <div class="row g-4">

            <div class="col-md-4" itemscope itemtype="https://schema.org/Review">
                <div class="testimonial-box p-4 shadow-sm rounded-3 h-100">
                    <div itemprop="reviewBody">
                        <p class="text-muted">"I landed a remote full-time software engineering role at a Silicon Valley company within 2 weeks of joining JobberRecruit. The process was seamless!"</p>
                    </div>
                    <div class="mt-3 d-flex align-items-center" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <img src="<?= base_url('images/favicon.png') ?>" class="rounded-circle" width="48" alt="Grace Thompson" itemprop="image">
                        <div class="ms-3">
                            <h6 class="mb-0 fw-semibold" itemprop="name">Grace Thompson</h6>
                            <small class="text-muted" itemprop="jobTitle">Senior Software Engineer</small>
                            <meta itemprop="worksFor" content="Tech Startup Inc.">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" itemscope itemtype="https://schema.org/Review">
                <div class="testimonial-box p-4 shadow-sm rounded-3 h-100">
                    <div itemprop="reviewBody">
                        <p class="text-muted">"Received 3 interview invites without applying! The quality of companies on this platform is exceptional compared to other job boards."</p>
                    </div>
                    <div class="mt-3 d-flex align-items-center" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <img src="<?= base_url('images/favicon.png') ?>" class="rounded-circle" width="48" alt="Michael Okoro" itemprop="image">
                        <div class="ms-3">
                            <h6 class="mb-0 fw-semibold" itemprop="name">Michael Okoro</h6>
                            <small class="text-muted" itemprop="jobTitle">Product Designer</small>
                            <meta itemprop="worksFor" content="Design Studio Pro">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" itemscope itemtype="https://schema.org/Review">
                <div class="testimonial-box p-4 shadow-sm rounded-3 h-100">
                    <div itemprop="reviewBody">
                        <p class="text-muted">"Verified employers, transparent salaries, and no ghosting. Found my dream project management role with a 40% salary increase!"</p>
                    </div>
                    <div class="mt-3 d-flex align-items-center" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <img src="<?= base_url('images/favicon.png') ?>" class="rounded-circle" width="48" alt="Samuel Adeyemi" itemprop="image">
                        <div class="ms-3">
                            <h6 class="mb-0 fw-semibold" itemprop="name">Samuel Adeyemi</h6>
                            <small class="text-muted" itemprop="jobTitle">Project Manager</small>
                            <meta itemprop="worksFor" content="Global Tech Corp">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?= $this->endSection() ?>


<?= $this->section('styles') ?>
<style>
    .candidate-hero {
        background: linear-gradient(135deg, #F5A623 0%, #F5A623 50%, #814a07ff 100%);
        position: relative;
        overflow: hidden;
    }

    .candidate-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><path fill="rgba(255,255,255,0.05)" d="M0,0L48,32C96,64,192,128,288,138.7C384,149,480,107,576,112C672,117,768,171,864,192C960,213,1056,203,1152,181.3C1248,160,1344,128,1392,112L1440,96L1440,600L1392,600C1344,600,1248,600,1152,600C1056,600,960,600,864,600C768,600,672,600,576,600C480,600,384,600,288,600C192,600,96,600,48,600L0,600Z"></path></svg>');
        background-size: cover;
    }

    .feature-box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .feature-box:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .testimonial-box {
        border-left: 4px solid #005DA8;
        background: #fff;
        transition: transform 0.3s ease;
    }

    .testimonial-box:hover {
        transform: translateY(-5px);
    }

    .step-circle {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #005DA8 0%, #0b5ed7 100%);
        color: #fff;
        border-radius: 50%;
        font-weight: bold;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    .trust-badges span {
        font-weight: 500;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .candidate-hero h1 {
            font-size: 2.5rem;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }
</style>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    // Animated Counters
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll(".counter");

        counters.forEach(counter => {
            const target = +counter.getAttribute("data-target");
            const suffix = counter.innerText.includes('%') ? '%' : '';
            const duration = 2000; // 2 seconds
            const step = target / (duration / 16); // 60fps

            let current = 0;

            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.innerText = Math.ceil(current) + suffix;
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target + suffix;
                }
            };

            // Start animation when element is in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });

            observer.observe(counter);
        });
    });
</script>
<?= $this->endSection() ?>


<?= $this->section('schema') ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'WebPage',
    'name' => 'Become a Candidate - Find Remote & Local Jobs | JobberRecruit',
    'description' => 'Join 10,000+ professionals finding high-paying remote and local jobs at verified companies. Create your free candidate profile and get matched with premium opportunities.',
    'url'  => current_url(),
    'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url()],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Become a Candidate', 'item' => current_url()],
        ],
    ],
    'mainEntity' => [
        '@type'       => 'HowTo',
        'name'        => 'How to Become a Candidate on JobberRecruit',
        'description' => 'Step-by-step guide to creating a candidate profile and finding jobs',
        'totalTime'   => 'PT15M',
        'estimatedCost' => [
            '@type'    => 'MonetaryAmount',
            'currency' => 'USD',
            'value'    => '0',
        ],
        'step' => [
            ['@type' => 'HowToStep', 'position' => '1', 'name' => 'Create Your Profile', 'text' => 'Sign up and build your professional profile with skills, experience, and portfolio', 'url' => base_url('register')],
            ['@type' => 'HowToStep', 'position' => '2', 'name' => 'Complete Skill Verification', 'text' => 'Take optional skill assessments to validate your expertise'],
            ['@type' => 'HowToStep', 'position' => '3', 'name' => 'Get Job Matches', 'text' => 'Receive personalized job matches based on your profile'],
            ['@type' => 'HowToStep', 'position' => '4', 'name' => 'Interview and Get Hired', 'text' => 'Connect with employers and land your next role'],
        ],
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name'  => 'JobberRecruit',
        'logo'  => [
            '@type'  => 'ImageObject',
            'url'    => base_url('images/logo.png'),
            'width'  => '300',
            'height' => '60',
        ],
        'sameAs' => [
            'https://twitter.com/JobberRecruit',
            'https://linkedin.com/company/jobberrecruit',
            'https://facebook.com/JobberRecruit',
        ],
    ],
    'potentialAction' => [
        '@type'       => 'CreateAccountAction',
        'target'      => base_url('register'),
        'description' => 'Create a free candidate account',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>