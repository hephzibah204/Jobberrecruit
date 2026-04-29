<?php $auth ??= service('auth'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3464186884176173"
        crossorigin="anonymous"></script>
    <meta charset="UTF-8">

    <!-- Mobile & Browser -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- SEO Title -->
    <title><?= esc($title ?? 'Find Jobs in Nigeria | JobberRecruit — Hire Top Talent') ?></title>

    <!-- PWA -->
    <link rel="manifest" href="<?= base_url('manifest.json'); ?>">
    <meta name="theme-color" content="#fff8f0">

    <!-- iOS PWA -->
    <link rel="apple-touch-icon" href="<?= base_url('images/pwa/icon-192.png'); ?>">
    <meta name="apple-mobile-web-app-title" content="JobberRecruit">

    <!-- SEO Meta Description -->
    <meta name="description" content="<?= esc($meta_description ?? 'Find verified jobs across Nigeria on JobberRecruit. Browse thousands of opportunities in Lagos, Abuja, Port Harcourt and more. Employers can post jobs and hire top Nigerian talent today.'); ?>">

    <!-- Keywords -->
    <meta name="keywords" content="<?= $keywords ?? 'jobs in Nigeria, African job portal, find jobs, hire talent, recruitment platform, jobber recruit, employment portal'; ?>">

    <meta name="author" content="JobberRecruit">
    <meta name="robots" content="<?= (isset($noindex) && $noindex) ? 'noindex, nofollow' : 'index, follow' ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= current_url(); ?>">

    <!-- Favicons -->
    <link rel="shortcut icon" href="<?= base_url('images/favicon.png'); ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= base_url('images/favicon.png'); ?>">

    <!-- Google Site Verification -->
    <meta name="google-site-verification" content="7ca31c2813a87974">

    <!-- Theme / App Data -->
    <meta name="application-name" content="JobberRecruit">
    <meta name="theme-color" content="#0D609E">

    <!-- Preconnect & Prefetch (Performance Boost) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

    <link rel="alternate"
        type="application/rss+xml"
        title="JobberRecruit Blog RSS"
        href="<?= base_url('blog/rss') ?>">

    <!-- Open Graph / Social Preview -->
    <meta property="og:title" content="<?= esc($og_title ?? $title ?? 'JobberRecruit — Hire Top Talent in Nigeria') ?>">
    <meta property="og:description" content="<?= esc($og_description ?? $meta_description ?? 'Find verified jobs across Nigeria on JobberRecruit. Browse thousands of opportunities in Lagos, Abuja, and more.') ?>">
    <meta property="og:type" content="<?= $og_type ?? 'website' ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:site_name" content="JobberRecruit">
    <meta property="og:image" content="<?= $og_image ?? base_url('images/og-image-main.png') ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($twitter_title ?? $title ?? 'JobberRecruit') ?>">
    <meta name="twitter:description" content="<?= esc($twitter_description ?? $meta_description ?? 'Find verified jobs across Nigeria on JobberRecruit.') ?>">
    <meta name="twitter:image" content="<?= $og_image ?? base_url('images/og-image-main.png') ?>">

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "JobberRecruit",
            "url": "<?= base_url() ?>",
            "logo": "<?= base_url('images/logo.png') ?>",
            "sameAs": [
                "https://www.facebook.com/jobberrecruit",
                "https://www.twitter.com/jobberrecruit",
                "https://www.linkedin.com/company/jobberrecruit"
            ]

        }
    </script>

    <!-- Page-Specific Schema (Jobs, Blog, etc.) -->
    <?= $this->renderSection('schema'); ?>

    <!-- Styles -->
    <link href="<?= base_url('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('css/toastr.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <link href="<?= base_url('css/plugins/select2.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('css/style.css'); ?>" rel="stylesheet">
    <!-- Midnight Aura Theme -->
    <?php if (isset($activeTheme) && $activeTheme === 'midnight-aura'): ?>
        <link id="midnight-aura-css" href="<?= base_url('css/midnight-aura.css'); ?>" rel="stylesheet">
    <?php endif; ?>
    <link href="<?= base_url('css/bootstrap-icons.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Page Custom Styles -->
    <?= $this->renderSection('styles'); ?>
</head>


<body>
    <!-- Top Navigation -->
    <div class="top-nav">
        <div class="container d-flex justify-content-between align-items-center flex-wrap py-2">
            <ul class="nav small-nav mb-0">
                <li class="nav-item"><a href="<?= base_url('blog') ?>" class="nav-link">Blog</a></li>
                <li class="nav-item"><a href="<?= base_url('about-us') ?>" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="<?= base_url('contact-us') ?>" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="<?= base_url('faq') ?>" class="nav-link">FAQs</a></li>
            </ul>
            <div class="d-flex align-items-center">
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <nav class="navbar navbar-expand-lg py-3">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand brand-logo" href="<?= base_url(); ?>">
                    <img src="<?= base_url('images/logo.png') ?>" alt="Jobber Recruit Logo" class="img-fluid">
                </a>
                <!-- Toggler for Mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Navigation Menu -->
                    <ul class="navbar-nav ms-auto text-center">
                        <li class="nav-item">
                            <a class="nav-link <?= (uri_string() == '/' || uri_string() == '') ? 'active' : '' ?>" href="<?= base_url('/') ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (uri_string() == 'jobs') ? 'active' : '' ?>" href="<?= base_url('jobs') ?>">Find Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (uri_string() == 'recruitment') ? 'active' : '' ?>" href="<?= base_url('recruitment') ?>">Recruitment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (uri_string() == 'job-ads') ? 'active' : '' ?>" href="<?= base_url('job-ads') ?>">Job Ads</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Employers
                            </a>
                            <ul class="dropdown-menu text-center">
                                <li><a class="dropdown-item" href="<?= base_url('employer/post-job') ?>">Post Job</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('employer/jobs') ?>">Manage Jobs</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (uri_string() == 'candidates') ? 'active' : '' ?>" href="<?= base_url('candidates') ?>">Candidates</a>
                        </li>
                        <li class="nav-item d-block d-lg-none">
                            <a class="nav-link <?= (uri_string() == 'about-us') ? 'active' : '' ?>" href="<?= base_url('about-us') ?>">About Us</a>
                        </li>
                        <li class="nav-item d-block d-lg-none">
                            <a class="nav-link <?= (uri_string() == 'blog') ? 'active' : '' ?>" href="<?= base_url('blog') ?>">Blog</a>
                        </li>
                        <li class="nav-item d-block d-lg-none">
                            <a class="nav-link" <?= (uri_string() == 'contact-us') ? 'active' : '' ?> href="<?= base_url('contact-us') ?>">Contact Us</a>
                        </li>
                    </ul>
                    <!-- Buttons -->
                    <div class="d-flex header-buttons ms-3">
                        <button
                            id="pwa-install-btn"
                            onclick="installPWA()"
                            class="btn btn-secondary d-lg-block d-none">
                            Install App
                        </button>
                        <?php if (!$auth->user()) : ?>
                            <a href="<?= base_url('login') ?>" class="btn btn-outline-primary me-2">Sign In</a>
                            <a href="<?= base_url('register') ?>" class="btn btn-primary">Register</a>
                        <?php else : ?>
                            <?php if ($auth->user()->user_type == 'employer'): ?>
                                <a href="<?= base_url('employer/dashboard') ?>" class="btn btn-primary">Dashboard</a>
                            <?php elseif ($auth->user()->user_type == 'job_seeker'): ?>
                                <a href="<?= base_url('candidate/dashboard') ?>" class="btn btn-primary">Dashboard</a>
                            <?php else: ?>
                                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">Dashboard</a>
                            <?php endif; ?>
                            <a href="<?= base_url('logout') ?>" class="btn btn-outline-primary me-2">Logout</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Offline Banner -->
    <div id="network-status" class="alert alert-danger text-center fw-semibold d-none" style="position: fixed; top: 0; left: 0; right: 0; opacity: 0.8; z-index: 9999;">
        <i class="bi bi-wifi-off"></i>
        You are offline. Trying to reconnect...
    </div>

    <div class="main-wrapper">
        <?= $this->renderSection('content'); ?>
    </div>

    <?php if (uri_string() !== 'training'): ?>
        <!-- Call to Action Section -->
        <section class="cta-section py-5">
            <div class="container text-center">
                <h2 class="fw-semibold mb-3">Join JobberRecruit Today</h2>
                <p class="text-muted mb-4 col-md-8 mx-auto">
                    Whether you’re a job seeker searching for your next career move or an employer looking to hire exceptional talent, JobberRecruit provides the tools and support you need to succeed.
                </p>
                <div class="row g-4">
                    <!-- Candidate Card -->
                    <div class="col-12 col-md-6">
                        <div class="become-card candidate-card position-relative text-white">
                            <div class="content pe-md-5">
                                <h4 class="fw-semibold text-light mb-2">Become a Candidate</h4>
                                <p class="text-light mb-4">
                                    Take the next step in your career with confidence. Create your profile, explore verified job opportunities, and connect with employers actively searching for talent like yours.
                                </p>
                                <a href="<?= base_url('register') ?>" class="btn flat-btn btn-primary text-light">
                                    Register Now <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                            <img
                                src="<?= base_url('images/hero-banner.png'); ?>"
                                alt="Candidate"
                                class="card-image d-none d-md-block" />
                        </div>
                    </div>

                    <!-- Employer Card -->
                    <div class="col-12 col-md-6">
                        <div class="become-card employer-card position-relative text-white">
                            <div class="content pe-md-5">
                                <h4 class="fw-semibold mb-2">Become an Employer</h4>
                                <p class="text-light mb-4">
                                    Find the right talent faster. Post job openings, access a pool of qualified candidates, and streamline your recruitment process with ease.
                                </p>
                                <a href="<?= base_url('register') ?>" class="btn flat-btn btn-light text-primary">
                                    Register Now <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                            <img
                                src="<?= base_url('images/hero-banner.png'); ?>"
                                alt="Employer"
                                class="card-image d-none d-md-block" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END CALL TO ACTION -->
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer text-light pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row gy-4">
                <!-- Logo + Contact -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-brand mb-4">
                        <a href="<?= base_url(); ?>">
                            <img src="<?= base_url('images/logo-white.png') ?>" alt="Jobber Recruit Logo" class="img-fluid">
                        </a>
                    </div>
                    <p class="mb-2 small text-light">
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:+2349014808902" class="footer-link">+234 901 480 8902</a>
                    </p>
                    <p class="small text-light">
                        6 Ojulari Rd, Lekki Penninsula II, 106104, Lagos, Nigeria
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-semibold mb-4">Quick Links</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?= base_url('about-us') ?>">About</a></li>
                        <li><a href="<?= base_url('contact-us') ?>">Contact</a></li>
                        <!-- <li><a href="<?= base_url('pricing') ?>">Pricing</a></li> -->
                        <li><a href="<?= base_url('blog') ?>">Blog</a></li>
                    </ul>
                </div>

                <!-- Candidate -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-semibold mb-4">Candidate</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?= base_url('jobs') ?>">Browse Jobs</a></li>
                        <li><a href="<?= base_url('candidates') ?>">Talents</a></li>
                    </ul>
                </div>

                <!-- Employers -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-semibold mb-4">Employers</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?= base_url('employer/post-job') ?>">Post a Job</a></li>
                        <li><a href="<?= base_url('employer/applications') ?>">Applications</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-4">Support</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?= base_url('faq') ?>">FAQs</a></li>
                        <li><a href="<?= base_url('privacy-policy') ?>">Privacy Policy</a></li>
                        <li><a href="<?= base_url('terms-and-conditions') ?>">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>

            <hr class="footer-divider my-4">

            <!-- SEO Hubs (Internal Linking Engine) -->
            <div class="row gy-4 mb-4">
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3 text-light">Top Job Locations</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= base_url('jobs-in-lagos') ?>" class="badge bg-dark-subtle text-light text-decoration-none">Jobs in Lagos</a>
                        <a href="<?= base_url('jobs-in-abuja') ?>" class="badge bg-dark-subtle text-light text-decoration-none">Jobs in Abuja</a>
                        <a href="<?= base_url('jobs-in-rivers') ?>" class="badge bg-dark-subtle text-light text-decoration-none">Jobs in Port Harcourt</a>
                        <a href="<?= base_url('jobs-in-kano') ?>" class="badge bg-dark-subtle text-light text-decoration-none">Jobs in Kano</a>
                        <a href="<?= base_url('jobs-in-oyo') ?>" class="badge bg-dark-subtle text-light text-decoration-none">Jobs in Ibadan</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3 text-light">Popular Categories</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= base_url('it-software-jobs') ?>" class="badge bg-dark-subtle text-light text-decoration-none">IT & Software</a>
                        <a href="<?= base_url('banking-finance-jobs') ?>" class="badge bg-dark-subtle text-light text-decoration-none">Banking & Finance</a>
                        <a href="<?= base_url('healthcare-jobs') ?>" class="badge bg-dark-subtle text-light text-decoration-none">Healthcare</a>
                        <a href="<?= base_url('marketing-sales-jobs') ?>" class="badge bg-dark-subtle text-light text-decoration-none">Marketing & Sales</a>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="small mb-2 mb-md-0 text-light">
                    © <?= date('Y') ?> <span class="fw-semibold">JobberRecruit</span> - Job Portal. All rights reserved.
                </p>
                <!-- Built by -->
                <p class="small mb-2 mb-md-0 text-light">
                    Built with <i class="bi bi-heart-fill text-danger"></i> by <a href="https://bitbiz.ng" class="text-light">BITBIZ NIG LIMITED</a>
                </p>
                <div class="footer-socials">
                    <a href="https://wa.me/message/GZ266BV42CQUK1" class="me-3"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://www.tiktok.com/@jobberecruit" class="me-3"><i class="bi bi-tiktok"></i></a>
                    <a href="https://www.linkedin.com/company/jobber-recruit/" class="me-3"><i class="bi bi-linkedin"></i></a>
                    <a href="https://www.instagram.com/jobberrecruit_ltd?igsh=YWFheGE0eDJ6NXh2" class="me-3"><i class="bi bi-instagram"></i></a>
                    <a href="https://t.me/jobberecruit" class="me-3"><i class="bi bi-telegram"></i></a>
                    <a href="https://x.com/jobberrecruit?s=21&t=-feIW_cwkJ1KudODM2mONQ"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
        </div>
    </footer>


    <script src="<?= base_url('js/jquery-3.7.1.min.js'); ?>" defer></script>
    <script src="<?= base_url('js/bootstrap.bundle.min.js'); ?>" defer></script>
    <script src="<?= base_url('js/toastr.min.js'); ?>" defer></script>
    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" defer></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    <script src="<?= base_url('js/plugins/select2.min.js'); ?>" defer></script>
    <!-- Custom -->
    <script src="<?= base_url('js/scripts.js'); ?>" defer></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?= base_url("sw.js") ?>', {
                        scope: '/'
                    })
                    .then(reg => console.log('Service Worker registered', reg))
                    .catch(err => console.log('Service Worker failed', err));
            });
        }

        let deferredPrompt;

        function installPWA() {
            if (!deferredPrompt) return;

            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(choice => {
                if (choice.outcome === 'accepted') {
                    console.log('User accepted install');
                }
                deferredPrompt = null;
            });
        }

        $(document).ready(function() {
            if (sessionStorage.getItem('isOffline') === '1' || !navigator.onLine) {
                showOffline();
                startRetry();
            }
        });

        (function($) {
            let retryInterval = null;
            const retryDelay = 5000; // 5 seconds

            function showOffline() {
                sessionStorage.setItem('isOffline', '1');

                $('#network-status')
                    .removeClass('d-none alert-success')
                    .addClass('alert-warning')
                    .text("You’re offline. Trying to reconnect…");
            }

            function showOnline() {
                sessionStorage.removeItem('isOffline');

                $('#network-status')
                    .removeClass('alert-warning')
                    .addClass('alert-success')
                    .text("Back online")
                    .fadeIn();

                setTimeout(() => {
                    $('#network-status').fadeOut(() => {
                        $(this).addClass('d-none').show();
                    });
                }, 2000);
            }

            function pingServer() {
                return $.ajax({
                    url: "/ping", // lightweight endpoint
                    method: "GET",
                    cache: false,
                    timeout: 3000
                });
            }

            function startRetry() {
                if (retryInterval) return;

                retryInterval = setInterval(() => {
                    if (!navigator.onLine) return;

                    pingServer()
                        .done(() => {
                            stopRetry();
                            showOnline();
                        });
                }, retryDelay);
            }

            function stopRetry() {
                clearInterval(retryInterval);
                retryInterval = null;
            }

            // Browser native detection
            window.addEventListener('offline', () => {
                showOffline();
                startRetry();
            });

            window.addEventListener('online', () => {
                stopRetry();
                showOnline();
            });

            // Initial check (important)
            $(document).ready(function() {
                if (!navigator.onLine) {
                    showOffline();
                    startRetry();
                }
            });

        })(jQuery);
    </script>
    <!-- PWA Installation Banner -->
    <div id="pwaInstallBanner" class="pwa-install-banner" style="display: none;">
        <button class="pwa-close" onclick="closePWABanner()">&times;</button>
        <div class="d-flex align-items-center">
            <img src="<?= base_url('images/pwa/icon-192.png'); ?>" alt="JobberRecruit" class="pwa-logo">
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold">Install JobberRecruit</h6>
                <p class="small mb-0 opacity-75" id="pwaInstruction">
                    Tap <i class="bi bi-share mx-1"></i> then "Add to Home Screen"
                </p>
            </div>
            <button id="androidInstallBtn" class="btn btn-primary btn-sm rounded-pill d-none">Install</button>
        </div>
    </div>

    <script>
        // PWA Installation Logic
        const pwaBanner = document.getElementById('pwaInstallBanner');
        
        const pwaBannerSessionKey = 'pwaBannerShown';
        const androidBtn = document.getElementById('androidInstallBtn');
        const instruction = document.getElementById('pwaInstruction');

        function isIOS() {
            return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        }

        function isMobile() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && window.innerWidth < 768;
        }

        function showPWABanner() {
            if (!pwaBanner || !isMobile() || sessionStorage.getItem(pwaBannerSessionKey)) return;

            pwaBanner.style.display = 'block';
            setTimeout(() => pwaBanner.classList.add('active'), 100);
            sessionStorage.setItem(pwaBannerSessionKey, 'true');
        }

        function closePWABanner() {
            if (!pwaBanner) return;
            pwaBanner.classList.remove('active');
            pwaBanner.style.display = 'none';
            sessionStorage.setItem(pwaBannerSessionKey, 'true');
        }

        if (!isMobile()) {
            pwaBanner?.remove();
        } else if (isIOS()) {
            instruction.innerHTML = 'Tap <svg class="ios-share-icon" viewBox="0 0 50 50" fill="white"><path d="M30.3,13.5L25,8.2l-5.3,5.3c-0.4,0.4-1,0.4-1.4,0s-0.4-1,0-1.4l6-6c0.4-0.4,1-0.4,1.4,0l6,6c0.4,0.4,0.4,1,0,1.4 C31.3,13.9,30.7,13.9,30.3,13.5z"/><path d="M24,7.3v18.7c0,0.6,0.4,1,1,1s1-0.4,1-1V7.3"/><path d="M35,14h-4c-0.6,0-1,0.4-1,1s0.4,1,1,1h4c1.1,0,2,0.9,2,2v18c0,1.1-0.9,2-2,2H15c-1.1,0-2-0.9-2-2V18c0-1.1,0.9-2,2-2h4 c0.6,0,1-0.4,1-1s-0.4-1-1-1h-4c-2.2,0-4,1.8-4,4v18c0,2.2,1.8,4,4,4h20c2.2,0,4-1.8,4-4V18C39,15.8,37.2,14,35,14z"/></svg> then "Add to Home Screen"';
            setTimeout(showPWABanner, 3000);
        }

        // Handle Android/Chrome
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            document.getElementById('pwa-install-btn')?.classList.remove('d-none');
            instruction.innerText = 'Add JobberRecruit to your home screen for a better experience.';
            androidBtn.classList.remove('d-none');
            setTimeout(showPWABanner, 3000);
        });

        androidBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
                closePWABanner();
            }
        });

        // Hide if already installed
        window.addEventListener('appinstalled', () => {
            closePWABanner();
        });
    </script>
    <?= $this->renderSection('scripts'); ?>
</body>

</html>
