<?php $auth ??= service('auth'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('jr-theme');
            const preferDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (preferDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-theme-mode', theme);
        })();
    </script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3464186884176173"
        crossorigin="anonymous"></script>
    <meta charset="UTF-8">

    <!-- Mobile & Browser -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- SEO Title -->
    <title><?= esc($title ?? 'Find Jobs in Nigeria | JobberRecruit — Hire Top Talent') ?></title>

    <!-- PWA -->
    <link rel="manifest" href="<?= base_url('manifest.json'); ?>">
    <meta name="theme-color" content="#0D609E">

    <!-- iOS PWA -->
    <link rel="apple-touch-icon" href="<?= base_url('images/pwa/icon-192.png'); ?>">
    <meta name="apple-mobile-web-app-title" content="JobberRecruit">

    <!-- SEO Meta Description -->
    <meta name="description" content="<?= esc($meta_description ?? 'Find verified jobs across Nigeria on JobberRecruit. Browse thousands of opportunities in Lagos, Abuja, Port Harcourt. Post jobs and hire top Nigerian talent.'); ?>">

    <!-- Keywords -->
    <meta name="keywords" content="<?= $keywords ?? 'jobs in Nigeria, African job portal, find jobs, hire talent, recruitment platform, jobber recruit, employment portal'; ?>">

    <meta name="author" content="JobberRecruit">
    <meta name="robots" content="<?= (isset($noindex) && $noindex) ? 'noindex, nofollow' : 'index, follow' ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= current_url(); ?>">

    <!-- Favicons -->
    <link rel="shortcut icon" href="<?= base_url('images/favicon.png'); ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= base_url('images/favicon.png'); ?>">

    <!-- Page-Specific Meta (title, description, OG, etc.) -->
    <?= $this->renderSection('meta'); ?>

    <!-- Google Site Verification -->
    <meta name="google-site-verification" content="7ca31c2813a87974">

    <!-- Theme / App Data -->
    <meta name="application-name" content="JobberRecruit">
    <meta name="theme-color" content="var(--brand)">

    <!-- Preconnect & Prefetch (Performance Boost) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

    <link rel="alternate"
        type="application/rss+xml"
        title="JobberRecruit Blog RSS"
        href="<?= base_url('rss/blog') ?>">

    <!-- Open Graph / Social Preview -->
    <meta property="og:title" content="<?= esc($og_title ?? $title ?? 'JobberRecruit — Hire Top Talent in Nigeria') ?>">
    <meta property="og:description" content="<?= esc($og_description ?? $meta_description ?? 'Find verified jobs across Nigeria on JobberRecruit. Browse thousands of opportunities in Lagos, Abuja, and more.') ?>">
    <meta property="og:type" content="<?= $og_type ?? 'website' ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:site_name" content="JobberRecruit">
    <meta property="og:image" content="<?= $og_image ?? base_url('images/default-og-image.jpg') ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($twitter_title ?? $title ?? 'JobberRecruit') ?>">
    <meta name="twitter:description" content="<?= esc($twitter_description ?? $meta_description ?? 'Find verified jobs across Nigeria on JobberRecruit.') ?>">
    <meta name="twitter:image" content="<?= $og_image ?? base_url('images/default-og-image.jpg') ?>">

    <script type="application/ld+json">
    <?= json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => 'JobberRecruit',
        'url'      => base_url(),
        'logo'     => base_url('images/logo.png'),
        'sameAs'   => [
            'https://www.facebook.com/jobberrecruit',
            'https://www.twitter.com/jobberrecruit',
            'https://www.linkedin.com/company/jobberrecruit',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
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
    <link href="<?= base_url('css/variables.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('css/style.css'); ?>" rel="stylesheet">
    <!-- Midnight Aura Theme -->
    <?php if (isset($activeTheme) && $activeTheme === 'midnight-aura'): ?>
        <link id="midnight-aura-css" href="<?= base_url('css/midnight-aura.css'); ?>" rel="stylesheet">
    <?php endif; ?>
    <link href="<?= base_url('css/bootstrap-icons.css'); ?>" rel="stylesheet">
    <!-- Sora (headings) + Inter (body) — matching reference design -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>

    <!-- JobberRecruit Design System -->
    <link rel="stylesheet" href="<?= base_url('css/jobber-recruit.css') ?>?v=<?= time() ?>">

    <!-- Section 8 — Native Mobile App Feel -->
    <link rel="stylesheet" href="<?= base_url('css/mobile-app.css') ?>">

    <!-- Page Custom Styles -->
    <?= $this->renderSection('styles'); ?>
</head>


<body>
    <!-- Essential SVG sprite for nav icons (chev-down, etc.) -->
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
      <defs>
        <symbol id="i-chev-down" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
        <symbol id="i-arrow-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
      </defs>
    </svg>

<a href="#main-content" class="skip-link" style="position:absolute;top:-50px;left:16px;background:var(--brand);color:#fff;padding:8px 16px;border-radius:0 0 6px 6px;font-weight:600;z-index:9999;transition:top .2s">Skip to main content</a>
<style>.skip-link:focus{top:0!important;outline:3px solid #ED9020;outline-offset:2px}</style>
    <!-- Skip-link target for accessibility (homepage has id="main-content", other pages use fallback) -->
    <div id="main-content" hidden></div>

    <!-- Sticky Navbar (reference design) -->
    <?= $this->include('templates/partials/header') ?>

    <!-- Offline Banner -->
    <div id="network-status" class="alert alert-danger text-center fw-semibold d-none" style="position: fixed; top: 0; left: 0; right: 0; opacity: 0.8; z-index: 9999;">
        <i class="bi bi-wifi-off"></i>
        You are offline. Trying to reconnect...
    </div>

    <div class="main-wrapper">
        <?= $this->renderSection('content'); ?>
    </div>

    <!-- Footer (reference design) -->
    <?= $this->include('templates/partials/footer') ?>
    
    <!-- Mobile Bottom App Navigation -->
    <?= $this->include('partials/mobile_bottom_nav') ?>


    <script src="<?= base_url('js/jquery-3.7.1.min.js'); ?>"></script>
    <script src="<?= base_url('js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('js/toastr.min.js'); ?>"></script>
    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="<?= base_url('js/plugins/select2.min.js'); ?>"></script>
    <!-- Custom -->
    <script src="<?= base_url('js/scripts.js'); ?>"></script>
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
                        $('#network-status').addClass('d-none').show();
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

                // Ping immediately on start to resolve quickly if we are actually online
                pingServer()
                    .done(() => {
                        stopRetry();
                        showOnline();
                    })
                    .fail(() => {
                        showOffline();
                    });

                retryInterval = setInterval(() => {
                    pingServer()
                        .done(() => {
                            stopRetry();
                            showOnline();
                        });
                }, retryDelay);
            }

            function stopRetry() {
                if (retryInterval) {
                    clearInterval(retryInterval);
                    retryInterval = null;
                }
            }

            // Browser native detection
            window.addEventListener('offline', () => {
                showOffline();
                startRetry();
            });

            window.addEventListener('online', () => {
                startRetry(); // Trigger verification ping instead of blindly assuming connection is up
            });

            // Initial check on load
            $(document).ready(function() {
                if (!navigator.onLine || sessionStorage.getItem('isOffline') === '1') {
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
    <!-- Back-to-top button -->
    <button id="btt" aria-label="Back to top" title="Back to top"><svg aria-hidden="true"><use href="#i-arrow-up"/></svg></button>
    <script>
      function toggleMenu(btn) {
        const nav = document.getElementById('mob-nav');
        if (!nav) return;
        const open = nav.classList.toggle('open');
        btn.setAttribute('aria-expanded', String(open));
        document.body.style.overflow = open ? 'hidden' : '';
      }
      document.addEventListener('DOMContentLoaded', function() {
        const mobNav = document.getElementById('mob-nav');
        if (mobNav) {
          mobNav.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
              this.classList.remove('open');
              const hamburger = document.querySelector('.hamburger');
              if (hamburger) hamburger.setAttribute('aria-expanded', 'false');
              document.body.style.overflow = '';
            }
          });
        }
        // Nav dropdown toggle for touch devices
        document.querySelectorAll('.nav-dropdown-toggle').forEach(function(toggle) {
          toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            document.querySelectorAll('.nav-dropdown-toggle[aria-expanded="true"]').forEach(function(t) {
              t.setAttribute('aria-expanded', 'false');
            });
            this.setAttribute('aria-expanded', String(!isOpen));
          });
        });
        document.addEventListener('click', function() {
          document.querySelectorAll('.nav-dropdown-toggle[aria-expanded="true"]').forEach(function(t) {
            t.setAttribute('aria-expanded', 'false');
          });
        });
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
            document.querySelectorAll('.nav-dropdown-toggle[aria-expanded="true"]').forEach(function(t) {
              t.setAttribute('aria-expanded', 'false');
            });
          }
        });
        // Back to top
        var btt = document.getElementById('btt');
        if (btt) {
          window.addEventListener('scroll', function() {
            btt.classList.toggle('show', window.scrollY > 400);
          }, { passive: true });
          btt.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
          });
        }
      });
    </script>
    <?= $this->renderSection('scripts'); ?>
    <script src="<?= base_url('assets/js/mobile-app.js?v=1.0'); ?>"></script>
    <script src="<?= base_url('js/theme-toggle.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/inline-validation.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/interactive-ui.js'); ?>" type="text/javascript"></script>
</body>

</html>
