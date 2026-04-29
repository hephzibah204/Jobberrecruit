<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ContactPage",
        "name": "Contact JobberRecruit",
        "description": "Get in touch with JobberRecruit for career guidance, recruitment services, or any inquiries. We're here to help job seekers and employers connect.",
        "url": "<?= current_url() ?>",
        "publisher": {
            "@type": "Organization",
            "name": "JobberRecruit",
            "logo": {
                "@type": "ImageObject",
                "url": "<?= base_url('images/logo.png') ?>"
            },
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+234-123-456-7890",
                "contactType": "customer service",
                "email": "support@jobberrecruit.com",
                "areaServed": "NG",
                "availableLanguage": ["English"]
            },
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "123 Jobber Street",
                "addressLocality": "Lagos",
                "addressCountry": "NG"
            }
        }
    }
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="contact-hero-section py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <div class="row align-items-center min-vh-60">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold text-dark mb-4">Get in <span class="text-gradient-primary">Touch</span></h1>
                <p class="lead text-muted col-lg-10 mx-auto mb-5">
                    Have questions about our platform, need career guidance, or want to explore partnership opportunities?
                    Our team is ready to assist you with personalized support and expert advice.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <a href="#contact-form" class="btn btn-primary btn-lg px-4 scroll-to">
                        <i class="bi bi-envelope-fill me-2"></i>Send Message
                    </a>
                    <a href="#contact-info" class="btn btn-outline-primary btn-lg px-4 scroll-to">
                        <i class="bi bi-telephone-fill me-2"></i>Contact Info
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Elements -->
    <div class="position-absolute top-0 end-0 w-50 h-100 bg-gradient-primary opacity-5 rounded-start-5"></div>
    <div class="position-absolute bottom-0 start-0 w-25 h-25">
        <svg width="100%" height="100%" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="100" cy="100" r="100" fill="url(#paint0_linear)" fill-opacity="0.05" />
            <defs>
                <linearGradient id="paint0_linear" x1="0" y1="0" x2="200" y2="200" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#0D609E" />
                    <stop offset="1" stop-color="#02365eff" />
                </linearGradient>
            </defs>
        </svg>
    </div>
</section>

<!-- Quick Contact Cards -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-4">
                            <i class="bi bi-headset text-white fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">General Inquiries</h5>
                        <p class="text-muted mb-4">
                            Questions about our platform, features, or services.
                        </p>
                        <a href="mailto:info@jobberrecruit.com" class="btn btn-outline-primary">
                            <i class="bi bi-envelope me-2"></i>Email Us
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-4">
                            <i class="bi bi-person-badge text-white fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Career Support</h5>
                        <p class="text-muted mb-4">
                            Need help with job applications or career advice?
                        </p>
                        <a href="mailto:careers@jobberrecruit.com" class="btn btn-outline-success">
                            <i class="bi bi-person-lines-fill me-2"></i>Get Help
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-4">
                            <i class="bi bi-handshake text-white fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Partnerships</h5>
                        <p class="text-muted mb-4">
                            Interested in collaborating or becoming a partner?
                        </p>
                        <a href="mailto:support@jobberrecruit.com" class="btn btn-outline-info">
                            <i class="bi bi-briefcase me-2"></i>Partner With Us
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
<!-- Main Contact Section -->
<section class="py-5" id="contact-form">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="display-6 fw-bold mb-1">Send Us a Message</h2>
                        <p class="text-muted mb-4">We'll respond within 24 hours</p>

                        <form action="<?= base_url('contact-us') ?>" method="POST" id="contactForm" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text"
                                            class="form-control"
                                            id="name"
                                            name="name"
                                            placeholder="John Doe"
                                            required>
                                        <label for="name">Full Name *</label>
                                        <div class="invalid-feedback">
                                            Please enter your full name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email"
                                            class="form-control"
                                            id="email"
                                            name="email"
                                            placeholder="john@example.com"
                                            required>
                                        <label for="email">Email Address *</label>
                                        <div class="invalid-feedback">
                                            Please enter a valid email address.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel"
                                            class="form-control"
                                            id="phone"
                                            name="phone"
                                            placeholder="+234 123 456 7890">
                                        <label for="phone">Phone Number</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="">Select a topic</option>
                                            <option value="General Inquiry">General Inquiry</option>
                                            <option value="Career Support">Career Support</option>
                                            <option value="Technical Support">Technical Support</option>
                                            <option value="Partnership">Partnership</option>
                                            <option value="Feedback">Feedback</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <label for="subject">Subject *</label>
                                        <div class="invalid-feedback">
                                            Please select a subject.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control"
                                            id="message"
                                            name="message"
                                            placeholder="Your message"
                                            style="height: 150px"
                                            required></textarea>
                                        <label for="message">Your Message *</label>
                                        <div class="invalid-feedback">
                                            Please enter your message.
                                        </div>
                                    </div>
                                    <div class="form-text mt-2">
                                        <small id="messageHelp" class="text-muted">
                                            Please provide as much detail as possible so we can better assist you.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                        <label class="form-check-label small" for="agreeTerms">
                                            I agree to the <a href="<?= base_url('privacy-policy') ?>" target="_blank" class="text-decoration-none">Privacy Policy</a> and consent to JobberRecruit contacting me.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                                        <span class="submit-text">
                                            <i class="bi bi-send-fill me-2"></i>Send Message
                                        </span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-5" id="contact-info">
                <div class="sticky-top" style="top: 100px;">
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-body p-5">
                            <h3 class="fw-bold mb-4">Contact Information</h3>

                            <div class="contact-info-item mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="contact-icon bg-primary bg-opacity-10 rounded-2 p-2 me-3">
                                        <i class="bi bi-geo-alt-fill text-white fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Our Office</h6>
                                        <p class="text-muted mb-0">6 Ojulari Rd, Lekki Penninsula II, Lagos 106104, Lagos, Nigeria</p>
                                        <a href="https://maps.google.com/?q=Place,+Ikate+Elegushi,+6+Ojulari+Road,+Lekki+Phase+1,+Lekki+105101,+Lagos,+Nigeria"
                                            target="_blank"
                                            class="text-primary small text-decoration-none">
                                            <i class="bi bi-map me-1"></i>View on Map
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-info-item mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="contact-icon bg-success bg-opacity-10 rounded-2 p-2 me-3">
                                        <i class="bi bi-telephone-fill text-white fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Phone Number</h6>
                                        <p class="text-muted mb-1">+234 901 480 8902</p>
                                        <p class="text-muted small">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-info-item mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="contact-icon bg-info bg-opacity-10 rounded-2 p-2 me-3">
                                        <i class="bi bi-envelope-fill text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Email Address</h6>
                                        <p class="text-muted mb-1">support@jobberrecruit.com</p>
                                        <p class="text-muted small">Response within 24 hours</p>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-info-item">
                                <div class="d-flex align-items-start">
                                    <div class="contact-icon bg-warning bg-opacity-10 rounded-2 p-2 me-3">
                                        <i class="bi bi-clock-fill text-warning fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Working Hours</h6>
                                        <p class="text-muted mb-1">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                        <p class="text-muted small">Saturday: 10:00 AM - 2:00 PM</p>
                                        <p class="text-muted small">Sunday: Closed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Follow Us -->
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <h4 class="fw-bold mb-4">Follow Us</h4>
                            <p class="text-muted mb-4">
                                Stay updated with career tips, job opportunities, and company news.
                            </p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="https://wa.me/message/GZ266BV42CQUK1" class="social-icon whatsapp d-flex align-items-center justify-content-center rounded-3">
                                    <i class="bi bi-whatsapp fs-5"></i>
                                    <span class="ms-2">WhatsApp</span>
                                </a>
                                <a href="https://www.tiktok.com/@jobberecruit" class="social-icon twitter d-flex align-items-center justify-content-center rounded-3">
                                    <i class="bi bi-twitter-x fs-5"></i>
                                    <span class="ms-2">Twitter</span>
                                </a>
                                <a href="https://x.com/jobberrecruit?s=21&t=-feIW_cwkJ1KudODM2mONQ" class="social-icon tiktok d-flex align-items-center justify-content-center rounded-3">
                                    <i class="bi bi-tiktok fs-5"></i>
                                    <span class="ms-2">TikTok</span>
                                </a>
                                <a href="https://www.linkedin.com/company/jobber-recruit/" class="social-icon linkedin d-flex align-items-center justify-content-center rounded-3">
                                    <i class="bi bi-linkedin fs-5"></i>
                                    <span class="ms-2">LinkedIn</span>
                                </a>
                                <a href="https://t.me/jobberecruit" class="social-icon telegram d-flex align-items-center justify-content-center rounded-3">
                                    <i class="bi bi-linkedin fs-5"></i>
                                    <span class="ms-2">Telegram</span>
                                </a>
                                <a href="https://www.instagram.com/jobberrecruit_ltd?igsh=YWFheGE0eDJ6NXh2" class="social-icon instagram d-flex align-items-center justify-content-center rounded-3">
                                    <i class="bi bi-instagram fs-5"></i>
                                    <span class="ms-2">Instagram</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-0">
    <div class="container-fluid px-0">
        <div class="ratio ratio-21x9">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.6639535681725!2d3.4892000759927932!3d6.4371881241582765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bfbbeb9c3b7a1%3A0x82779b36aa70dbfc!2sJobber%20Recruit%20Limited!5e0!3m2!1sen!2sng!4v1766402992204!5m2!1sen!2sng"
                width="100%"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Contact Page Custom Styles */
    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
    }


    /* Hero Section */
    .contact-hero-section {
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

    /* Card Hover Effects */
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

    /* Form Styles */
    .form-floating>.form-control,
    .form-floating>.form-select {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
    }

    .form-floating>label {
        padding: 1rem 0.75rem;
    }

    /* Contact Info Items */
    .contact-info-item {
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f3f5;
    }

    .contact-info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .contact-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Social Icons */
    .social-icon {
        padding: 0.75rem 1.25rem;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        flex: 1;
        min-width: 120px;
    }

    .social-icon:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }

    .social-icon.facebook {
        background: #1877f2;
    }

    .social-icon.twitter {
        background: #000000;
    }

    .social-icon.linkedin {
        background: #0a66c2;
    }

    .social-icon.instagram {
        background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d);
    }

    .social-icon:hover {
        opacity: 0.9;
    }

    /* TikTok, Whatsapp, Telegram */
    .social-icon.tiktok {
        background: #000000;
    }

    .social-icon.whatsapp {
        background: #25d366;
    }

    .social-icon.telegram {
        background: #0088cc;
    }

    /* FAQ Accordion */
    .accordion-button {
        font-weight: 600;
        color: #1a1a1a;
    }

    .accordion-button:not(.collapsed) {
        background-color: rgba(102, 126, 234, 0.1);
        color: #0D609E;
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(102, 126, 234, 0.25);
    }

    /* Map */
    .ratio-21x9 {
        --bs-aspect-ratio: calc(9 / 21 * 100%);
    }

    /* CTA Section */
    .bg-primary {
        background: linear-gradient(135deg, #0D609E 0%, #02365eff 100%) !important;
    }

    /* Background Elements */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #F0890E 0%, #F0890E 100%);
    }

    /* Form Validation */
    .was-validated .form-control:valid,
    .was-validated .form-control:invalid {
        background-position: right calc(0.375em + 0.1875rem) center;
    }

    /* Responsive Design */
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

        .contact-hero-section {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        .social-icon {
            min-width: 100%;
            margin-bottom: 0.5rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .card-body {
            padding: 1.5rem !important;
        }
    }

    /* Loading Spinner */
    .spinner-border.d-none {
        display: none !important;
    }

    /* Scroll Animation */
    .scroll-to {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .scroll-to:hover {
        color: #02365eff !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= getenv('recaptcha_site_key'); ?>"></script>
<script>
    // Form Validation and Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = submitBtn.querySelector('.submit-text');
        const spinner = submitBtn.querySelector('.spinner-border');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            submitBtn.disabled = true;
            submitText.classList.add('d-none');
            spinner.classList.remove('d-none');

            grecaptcha.ready(function() {
                grecaptcha.execute('<?= getenv('recaptcha_site_key'); ?>', {
                    action: 'contact'
                }).then(function(token) {

                    const formData = new FormData(form);
                    formData.append('g-recaptcha-response', token);

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showAlert('success', data.message);
                                form.reset();
                                form.classList.remove('was-validated');
                            } else {
                                if (data.errors) {
                                    Object.values(data.errors).forEach(err => {
                                        showAlert('danger', err);
                                    });
                                } else {
                                    showAlert('danger', data.message);
                                }
                            }
                        })
                        .catch(() => {
                            showAlert('danger', 'Network error. Please try again.');
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitText.classList.remove('d-none');
                            spinner.classList.add('d-none');
                        });
                });
            });
        });

        function showAlert(type, message) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-4`;
            alert.style.zIndex = '1050';
            alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
            document.body.appendChild(alert);

            setTimeout(() => alert.remove(), 5000);
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('.scroll-to').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId.startsWith('#')) {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                } else {
                    window.location.href = targetId;
                }
            });
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const phoneInput = document.getElementById('phone');

        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                let raw = this.value.replace(/[^\d+]/g, '');

                // Normalize Nigerian numbers
                if (raw.startsWith('0')) {
                    raw = '+234' + raw.slice(1);
                } else if (raw.startsWith('234')) {
                    raw = '+234' + raw.slice(3);
                } else if (!raw.startsWith('+234')) {
                    // Allow typing before country code
                    raw = '+234' + raw.replace(/^\+/, '');
                }

                // Remove extra digits beyond +234XXXXXXXXXX
                raw = raw.replace(/^(\+234\d{10}).*$/, '$1');

                this.value = formatNGNumber(raw);
            });
        }

        function formatNGNumber(value) {
            // Format only when complete
            const match = value.match(/^\+234(\d{3})(\d{3})(\d{4})$/);
            if (match) {
                return `+234 ${match[1]} ${match[2]} ${match[3]}`;
            }
            return value;
        }

        // Character counter for message
        const messageTextarea = document.getElementById('message');
        if (messageTextarea) {
            const counter = document.createElement('div');
            counter.className = 'form-text text-end mt-1 small';
            counter.innerHTML = '<span id="charCount">0</span>/1000 characters';
            messageTextarea.parentNode.appendChild(counter);

            messageTextarea.addEventListener('input', function() {
                const charCount = document.getElementById('charCount');
                charCount.textContent = this.value.length;

                if (this.value.length > 1000) {
                    charCount.classList.add('text-danger');
                } else {
                    charCount.classList.remove('text-danger');
                }
            });
        }
    });

    // Google Maps enhancement
    function initMap() {
        // This function would initialize Google Maps if needed
        console.log('Map functionality ready');
    }
</script>
<?= $this->endSection() ?>