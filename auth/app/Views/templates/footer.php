<footer class="footer mt-50">
    <div class="container">
        <div class="row">
            <div class="footer-col-1 col-md-3 col-sm-12 mb-4 mb-md-0 text-center text-md-start">
                <a href="<?= base_url('/') ?>"><img alt="JobberRecruit" src="<?= base_url('assets/imgs/template/logo.png'); ?>" style="max-height: 55px; width: auto; margin-bottom: 15px;"></a>
                <div class="mt-10 mb-20 font-xs color-text-paragraph-2">JobberRecruit is the heart of the design community and the best resource to discover and connect with designers and jobs worldwide.</div>
                <div class="footer-social d-flex gap-2 justify-content-center justify-content-md-start">
                    <a href="https://twitter.com/jobberrecruit" target="_blank" rel="noopener" class="social-icon"><i class="bi bi-twitter"></i></a>
                    <a href="https://linkedin.com/company/jobberrecruit" target="_blank" rel="noopener" class="social-icon"><i class="bi bi-linkedin"></i></a>
                    <a href="https://facebook.com/jobberrecruit" target="_blank" rel="noopener" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="https://instagram.com/jobberrecruit" target="_blank" rel="noopener" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="https://wa.me/234" target="_blank" rel="noopener" class="social-icon"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://telegram.com/jobberrecruit" target="_blank" rel="noopener" class="social-icon"><i class="bi bi-telegram"></i></a>
                </div>
            </div>
            <div class="footer-col-2 col-md-3 col-6 mb-4 mb-md-0">
                <h6 class="mb-20 footer-heading text-white fw-bold">For Job Seekers</h6>
                <ul class="menu-footer">
                    <li><a href="<?= base_url('jobs') ?>">Find a Job</a></li>
                    <li><a href="<?= base_url('training') ?>">Training</a></li>
                    <li><a href="<?= base_url('cv-review') ?>">CV Review</a></li>
                    <li><a href="<?= base_url('webinars') ?>">Webinars</a></li>
                </ul>
            </div>
            <div class="footer-col-3 col-md-3 col-6 mb-4 mb-md-0">
                <h6 class="mb-20 footer-heading text-white fw-bold">Company</h6>
                <ul class="menu-footer">
                    <li><a href="<?= base_url('about-us') ?>">About Us</a></li>
                    <li><a href="<?= base_url('blogs') ?>">Blog</a></li>
                    <li><a href="<?= base_url('contact-us') ?>">Contact</a></li>
                    <li><a href="<?= base_url('employer/pricing') ?>">Pricing</a></li>
                </ul>
            </div>
            <div class="footer-col-5 col-md-3 col-6 mb-4 mb-md-0">
                <h6 class="mb-20 footer-heading text-white fw-bold">Support</h6>
                <ul class="menu-footer">
                    <li><a href="<?= base_url('privacy-policy') ?>">Privacy Policy</a></li>
                    <li><a href="<?= base_url('terms-of-service') ?>">Terms of Service</a></li>
                    <li><a href="<?= base_url('faq') ?>">FAQ</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom mt-50">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="font-xs color-text-paragraph">&copy; <?= date('Y') ?> JobberRecruit. All rights reserved.</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-social d-flex gap-3 justify-content-center justify-content-md-end">
                        <a class="font-xs color-text-paragraph text-decoration-none" href="<?= base_url('privacy-policy') ?>">Privacy Policy</a>
                        <span class="color-text-paragraph-2 font-xs">|</span>
                        <a class="font-xs color-text-paragraph text-decoration-none" href="<?= base_url('terms-of-service') ?>">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Footer & Mobile Optimizations styling overrides */
    .footer {
        background-color: #0c1e35 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 60px;
        color: rgba(255, 255, 255, 0.7);
    }
    .footer .menu-footer {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    .footer .menu-footer li {
        margin-bottom: 10px;
    }
    .footer .menu-footer li a {
        color: rgba(255, 255, 255, 0.65) !important;
        font-size: 13px;
        transition: color 0.25s ease, padding-left 0.25s ease;
        text-decoration: none;
        display: inline-block;
    }
    .footer .menu-footer li a:hover {
        color: #F08F1A !important;
        padding-left: 4px;
    }
    .footer-heading {
        position: relative;
        padding-bottom: 8px;
    }
    .footer-heading::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 30px;
        height: 2px;
        background-color: #F08F1A;
    }
    .footer-social .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        color: rgba(255, 255, 255, 0.8) !important;
        transition: background-color 0.25s ease, transform 0.25s ease;
        text-decoration: none !important;
    }
    .footer-social .social-icon:hover {
        background-color: #0D609E;
        color: #fff !important;
        transform: translateY(-3px);
    }
    @media (max-width: 767.98px) {
        .footer {
            padding-top: 40px;
            padding-bottom: 25px;
        }
        
        /* Left align all columns for clean, professional mobile reading flow */
        .footer-col-1, .footer-col-2, .footer-col-3, .footer-col-5 {
            text-align: left !important;
            margin-bottom: 30px;
        }
        
        /* Make columns full-width stacked on mobile for neat hierarchy */
        .footer-col-2, .footer-col-3, .footer-col-5 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding-left: 15px;
            padding-right: 15px;
            margin-bottom: 25px;
        }

        .footer-heading {
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 15px !important;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: block;
            width: 100%;
        }

        /* Remove the old absolute pseudo line since we now use a clean full-width border-bottom */
        .footer-heading::after {
            display: none !important;
        }

        .footer .menu-footer {
            padding-left: 0;
        }

        .footer .menu-footer li {
            margin-bottom: 12px;
        }

        .footer .menu-footer li a {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75) !important;
            display: block;
            padding: 4px 0;
            transition: all 0.2s ease;
        }

        .footer .menu-footer li a:hover {
            color: #F08F1A !important;
            padding-left: 6px;
        }

        /* Align socials to the left */
        .footer-social {
            justify-content: flex-start !important;
            margin-top: 15px;
        }

        /* Footer bottom styling for mobile */
        .footer-bottom {
            margin-top: 20px !important;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-bottom .row {
            display: flex;
            flex-direction: column-reverse;
            align-items: flex-start !important;
        }

        .footer-bottom .col-md-6 {
            width: 100%;
            text-align: left !important;
            margin-bottom: 15px;
        }

        .footer-bottom .footer-social {
            justify-content: flex-start !important;
            gap: 15px !important;
        }
        
        .footer-bottom .footer-social a {
            font-size: 13px !important;
        }
    }
</style>