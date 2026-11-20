<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
<?= json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'ContactPage',
    'name'        => 'Contact JobberRecruit',
    'description' => "Get in touch with JobberRecruit for career guidance, employer recruitment support, or partnership inquiries. We're here to help job seekers and employers across Nigeria.",
    'url'         => current_url(),
    'publisher'   => [
        '@type' => 'Organization',
        'name'  => 'JobberRecruit',
        'logo'  => [
            '@type' => 'ImageObject',
            'url'   => base_url('images/logo.png'),
        ],
        'contactPoint' => [
            '@type'             => 'ContactPoint',
            'telephone'         => '+234-901-480-8902',
            'contactType'       => 'customer service',
            'email'             => 'support@jobberrecruit.com',
            'areaServed'        => 'NG',
            'availableLanguage' => ['English'],
        ],
        'address' => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '6 Ojulari Rd, Lekki Peninsula II',
            'addressLocality' => 'Lagos',
            'postalCode'      => '106104',
            'addressCountry'  => 'NG',
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ══ HERO SECTION ══ -->
<section class="jr-contact-hero">
    <div class="jr-contact-hero__glow"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="jr-contact-hero__inner">
            <span class="jr-contact-badge">
                <span class="jr-contact-badge__dot"></span>
                Support &amp; Partnerships · Lagos, Nigeria
            </span>
            <h1 class="jr-contact-hero__title">
                Let’s Help You <span class="jr-text-accent">Hire Faster</span> or Land Your Next Role
            </h1>
            <p class="jr-contact-hero__sub">
                Have a question about posting jobs, verifying your company, career tools, or enterprise partnerships? Our Lagos support team responds within 24 hours.
            </p>

            <div class="jr-contact-hero__actions">
                <a href="#contact-form" class="btn btn-accent btn-lg scroll-to">
                    <i class="bi bi-send-fill"></i> Send a Message
                </a>
                <a href="https://wa.me/message/GZ266BV42CQUK1" target="_blank" rel="noopener noreferrer" class="btn jr-btn-ghost-light btn-lg">
                    <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                </a>
            </div>

            <div class="jr-contact-metrics">
                <div class="jr-contact-metric">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span><strong>&lt; 24 Hours</strong> Avg. Response Time</span>
                </div>
                <div class="jr-contact-metric">
                    <i class="bi bi-clock-history"></i>
                    <span><strong>Mon – Sat</strong> Dedicated Support</span>
                </div>
                <div class="jr-contact-metric">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span><strong>Lekki, Lagos</strong> Nigeria HQ</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ DIRECT SUPPORT CHANNELS ══ -->
<section class="jr-contact-channels">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="jr-channel-card">
                    <div class="jr-channel-card__icon jr-icon-brand">
                        <i class="bi bi-headset"></i>
                    </div>
                    <span class="jr-channel-card__tag">General Help</span>
                    <h3>General Inquiries</h3>
                    <p>Questions about how JobberRecruit works, account setup, or platform features.</p>
                    <a href="mailto:info@jobberrecruit.com" class="jr-channel-card__link">
                        <span>info@jobberrecruit.com</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="jr-channel-card">
                    <div class="jr-channel-card__icon jr-icon-success">
                        <i class="bi bi-person-Check-fill bi-person-badge"></i>
                    </div>
                    <span class="jr-channel-card__tag">For Job Seekers</span>
                    <h3>Candidate &amp; Career Support</h3>
                    <p>Need assistance with job applications, CV reviews, aptitude tests, or certificates?</p>
                    <a href="mailto:careers@jobberrecruit.com" class="jr-channel-card__link">
                        <span>careers@jobberrecruit.com</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="jr-channel-card">
                    <div class="jr-channel-card__icon jr-icon-accent">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <span class="jr-channel-card__tag">For Employers</span>
                    <h3>Employers &amp; Partnerships</h3>
                    <p>Hiring at scale, company verification (CAC), billing, or strategic collaborations.</p>
                    <a href="mailto:support@jobberrecruit.com" class="jr-channel-card__link">
                        <span>support@jobberrecruit.com</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ad-Res -->
<div class="container my-3">
    <ins class="adsbygoogle"
        style="display:block"
        data-ad-client="ca-pub-3464186884176173"
        data-ad-slot="6229476516"
        data-ad-format="auto"
        data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>

<!-- ══ MAIN CONTACT FORM & INFO ══ -->
<section class="jr-contact-main" id="contact-form">
    <div class="container">
        <div class="row g-4 g-lg-5 align-items-start">

            <!-- Left Column: Contact Form -->
            <div class="col-lg-7">
                <div class="jr-form-card">
                    <div class="jr-form-card__header">
                        <span class="section-label">
                            <i class="bi bi-envelope-paper-fill"></i> Direct Message
                        </span>
                        <h2>Send Us a Message</h2>
                        <p>Fill out the form below and a member of our team will get back to you within one business day.</p>
                    </div>

                    <!-- Inline status banner -->
                    <div id="contactAlertBox" class="jr-form-alert d-none" role="alert"></div>

                    <form action="<?= base_url('contact-us') ?>" method="POST" id="contactForm" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <!-- Quick Topic Pills -->
                        <div class="mb-4">
                            <label class="jr-field-label">What can we help you with?</label>
                            <div class="jr-topic-pills" role="group" aria-label="Select a topic">
                                <button type="button" class="jr-topic-pill active" data-topic="General Inquiry">General Inquiry</button>
                                <button type="button" class="jr-topic-pill" data-topic="Career Support">Candidate Support</button>
                                <button type="button" class="jr-topic-pill" data-topic="Technical Support">Employer / Hiring</button>
                                <button type="button" class="jr-topic-pill" data-topic="Partnership">Partnership</button>
                                <button type="button" class="jr-topic-pill" data-topic="Feedback">Feedback</button>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="jr-field">
                                    <label for="name" class="jr-field-label">Full Name <span class="text-danger">*</span></label>
                                    <div class="jr-input-wrap">
                                        <i class="bi bi-person jr-input-icon"></i>
                                        <input type="text"
                                            class="form-control jr-input"
                                            id="name"
                                            name="name"
                                            placeholder="e.g. Chinedu Okafor"
                                            required
                                            minlength="3">
                                        <div class="invalid-feedback">Please enter your full name (at least 3 characters).</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="jr-field">
                                    <label for="email" class="jr-field-label">Email Address <span class="text-danger">*</span></label>
                                    <div class="jr-input-wrap">
                                        <i class="bi bi-envelope jr-input-icon"></i>
                                        <input type="email"
                                            class="form-control jr-input"
                                            id="email"
                                            name="email"
                                            placeholder="you@company.com"
                                            required>
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="jr-field">
                                    <label for="phone" class="jr-field-label">Phone / WhatsApp <span class="jr-optional">(Optional)</span></label>
                                    <div class="jr-input-wrap">
                                        <i class="bi bi-telephone jr-input-icon"></i>
                                        <input type="tel"
                                            class="form-control jr-input"
                                            id="phone"
                                            name="phone"
                                            placeholder="+234 901 480 8902">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="jr-field">
                                    <label for="subject" class="jr-field-label">Subject <span class="text-danger">*</span></label>
                                    <div class="jr-input-wrap">
                                        <i class="bi bi-tag jr-input-icon"></i>
                                        <select class="form-select jr-input" id="subject" name="subject" required>
                                            <option value="General Inquiry" selected>General Inquiry</option>
                                            <option value="Career Support">Career Support</option>
                                            <option value="Technical Support">Technical Support</option>
                                            <option value="Partnership">Partnership</option>
                                            <option value="Feedback">Feedback</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a subject.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="jr-field">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="message" class="jr-field-label mb-0">Your Message <span class="text-danger">*</span></label>
                                        <span class="jr-char-counter"><span id="charCount">0</span> / 1000</span>
                                    </div>
                                    <textarea class="form-control jr-textarea mt-2"
                                        id="message"
                                        name="message"
                                        rows="5"
                                        placeholder="Tell us how we can help you..."
                                        required
                                        minlength="10"></textarea>
                                    <div class="invalid-feedback">Please enter a message of at least 10 characters.</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check jr-check">
                                    <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                    <label class="form-check-label" for="agreeTerms">
                                        I agree to the <a href="<?= base_url('privacy-policy') ?>" target="_blank" rel="noopener">Privacy Policy</a> and consent to JobberRecruit contacting me regarding my inquiry.
                                    </label>
                                    <div class="invalid-feedback">You must agree before submitting.</div>
                                </div>
                            </div>

                            <div class="col-12 pt-2">
                                <button type="submit" class="btn btn-primary btn-lg w-100 jr-submit-btn" id="submitBtn">
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

            <!-- Right Column: Contact Details & Socials -->
            <div class="col-lg-5" id="contact-info">
                <div class="jr-contact-sidebar">

                    <!-- Office & Direct Contact Card -->
                    <div class="jr-info-card">
                        <h3>Contact Information</h3>
                        <p class="jr-info-card__sub">Reach out directly via phone, email, or visit our Lagos office during business hours.</p>

                        <div class="jr-info-list">
                            <div class="jr-info-item">
                                <div class="jr-info-item__icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="jr-info-item__body">
                                    <h6>Lagos Office</h6>
                                    <p>6 Ojulari Rd, Lekki Peninsula II, Lagos 106104, Lagos, Nigeria</p>
                                    <a href="https://maps.google.com/?q=Place,+Ikate+Elegushi,+6+Ojulari+Road,+Lekki+Phase+1,+Lekki+105101,+Lagos,+Nigeria"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="jr-info-link">
                                        <i class="bi bi-map"></i> Open in Google Maps
                                    </a>
                                </div>
                            </div>

                            <div class="jr-info-item">
                                <div class="jr-info-item__icon">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div class="jr-info-item__body">
                                    <h6>Phone &amp; WhatsApp</h6>
                                    <p><a href="tel:+2349014808902" class="jr-plain-link">+234 901 480 8902</a></p>
                                    <span class="jr-info-meta">Mon – Fri, 9:00 AM – 6:00 PM WAT</span>
                                </div>
                            </div>

                            <div class="jr-info-item">
                                <div class="jr-info-item__icon">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <div class="jr-info-item__body">
                                    <h6>Email Support</h6>
                                    <p><a href="mailto:support@jobberrecruit.com" class="jr-plain-link">support@jobberrecruit.com</a></p>
                                    <span class="jr-info-meta">Guaranteed reply within 24 hours</span>
                                </div>
                            </div>

                            <div class="jr-info-item">
                                <div class="jr-info-item__icon">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="jr-info-item__body">
                                    <h6>Working Hours</h6>
                                    <p>Monday – Friday: 9:00 AM – 6:00 PM</p>
                                    <span class="jr-info-meta">Saturday: 10:00 AM – 2:00 PM · Sunday: Closed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Card -->
                    <div class="jr-info-card">
                        <h4>Connect With Us</h4>
                        <p class="jr-info-card__sub">Follow JobberRecruit for daily verified job alerts, career insights, and hiring updates.</p>

                        <div class="jr-social-grid">
                            <a href="https://wa.me/message/GZ266BV42CQUK1" target="_blank" rel="noopener noreferrer" class="jr-social-btn jr-social--whatsapp">
                                <i class="bi bi-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>
                            <a href="https://x.com/jobberrecruit?s=21&t=-feIW_cwkJ1KudODM2mONQ" target="_blank" rel="noopener noreferrer" class="jr-social-btn jr-social--x">
                                <i class="bi bi-twitter-x"></i>
                                <span>X (Twitter)</span>
                            </a>
                            <a href="https://www.linkedin.com/company/jobber-recruit/" target="_blank" rel="noopener noreferrer" class="jr-social-btn jr-social--linkedin">
                                <i class="bi bi-linkedin"></i>
                                <span>LinkedIn</span>
                            </a>
                            <a href="https://www.instagram.com/jobberrecruit_ltd?igsh=YWFheGE0eDJ6NXh2" target="_blank" rel="noopener noreferrer" class="jr-social-btn jr-social--instagram">
                                <i class="bi bi-instagram"></i>
                                <span>Instagram</span>
                            </a>
                            <a href="https://www.tiktok.com/@jobberecruit" target="_blank" rel="noopener noreferrer" class="jr-social-btn jr-social--tiktok">
                                <i class="bi bi-tiktok"></i>
                                <span>TikTok</span>
                            </a>
                            <a href="https://t.me/jobberecruit" target="_blank" rel="noopener noreferrer" class="jr-social-btn jr-social--telegram">
                                <i class="bi bi-telegram"></i>
                                <span>Telegram</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- ══ FREQUENTLY ASKED QUESTIONS ══ -->
<section class="jr-contact-faq">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">
                <i class="bi bi-question-circle-fill"></i> Quick Answers
            </span>
            <h2 class="section-title">Frequently Asked <span>Questions</span></h2>
            <p class="section-sub mx-auto">Need an immediate answer? Check our most common inquiries from candidates and employers.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion jr-accordion" id="contactFaqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="faqHeadingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
                                How long does employer account &amp; CAC verification take?
                            </button>
                        </h3>
                        <div id="faqCollapseOne" class="accordion-collapse collapse show" aria-labelledby="faqHeadingOne" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body">
                                Employer verification typically takes less than 24 business hours once your RC Number or CAC documentation is submitted from your Employer Dashboard. Need urgent review? Send us a message above with your company name.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header" id="faqHeadingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                                How do I request a company name change on my employer profile?
                            </button>
                        </h3>
                        <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body">
                                To protect candidates from impersonation, company names are locked once set. Select <strong>Technical Support</strong> in the contact form above and include your registered email and updated CAC name to have our team update it for you.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header" id="faqHeadingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                                Is applying for jobs on JobberRecruit free for candidates?
                            </button>
                        </h3>
                        <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree" data-bs-parent="#contactFaqAccordion">
                            <div class="accordion-body">
                                Yes! Creating a candidate profile, uploading your CV, and applying to verified jobs on JobberRecruit is 100% free. We also offer optional premium career tools, courses, and professional CV reviews.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ MAP SECTION ══ -->
<section class="jr-contact-map">
    <div class="container">
        <div class="jr-map-frame">
            <iframe
                title="Jobber Recruit Limited Office Location in Lekki, Lagos"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.6639535681725!2d3.4892000759927932!3d6.4371881241582765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bfbbeb9c3b7a1%3A0x82779b36aa70dbfc!2sJobber%20Recruit%20Limited!5e0!3m2!1sen!2sng!4v1766402992204!5m2!1sen!2sng"
                width="100%"
                height="420"
                style="border:0; display:block;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>html, body { overflow-x: clip; }

/* Hallmark · restyle · jobberrecruit-contact · theme: technical-precision */
/* critique: contact & support hub; zero AI slop, non-reflowing focus rings, accessible contrast */
    /* ── Contact Page — JobberRecruit Design System ── */
    .jr-contact-hero {
        position: relative;
        background: linear-gradient(135deg, var(--brand-deep, #0A2F57) 0%, var(--brand, #0861A9) 100%);
        color: #ffffff;
        padding: 76px 0 110px;
        overflow: hidden;
    }

    .jr-contact-hero__glow {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 82% 20%, rgba(237, 144, 32, 0.20) 0%, transparent 45%),
            radial-gradient(circle at 15% 85%, rgba(255, 255, 255, 0.08) 0%, transparent 40%);
        pointer-events: none;
    }

    .jr-contact-hero__inner {
        max-width: 760px;
        margin: 0 auto;
        text-align: center;
    }

    .jr-contact-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.03em;
        margin-bottom: 20px;
    }

    .jr-contact-badge__dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #4ade80;
        box-shadow: 0 0 0 4px rgba(74, 222, 128, 0.22);
    }

    .jr-contact-hero__title {
        font-family: 'Sora', 'Inter', sans-serif;
        font-size: clamp(2rem, 4.2vw, 3.1rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.025em;
        color: #ffffff;
        margin-bottom: 18px;
     text-wrap: balance; overflow-wrap: break-word; }

    .jr-text-accent {
        color: var(--accent, #ED9020);
    }

    .jr-contact-hero__sub {
        font-size: 1.05rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.86);
        max-width: 640px;
        margin: 0 auto 28px;
    }

    .jr-contact-hero__actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px;
        margin-bottom: 36px;
    }

    .jr-btn-ghost-light {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff !important;
        border: 1.5px solid rgba(255, 255, 255, 0.3);
        padding: 13px 26px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.18s ease;
    }

    .jr-btn-ghost-light:hover {
        background: rgba(255, 255, 255, 0.22);
        border-color: #ffffff;
        color: #ffffff !important;
        text-decoration: none;
    }

    .jr-contact-metrics {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px 28px;
        padding-top: 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.14);
        font-size: 0.86rem;
        color: rgba(255, 255, 255, 0.85);
    }

    .jr-contact-metric {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .jr-contact-metric i {
        color: var(--accent, #ED9020);
        font-size: 1rem;
    }

    /* ── Support Channel Cards ── */
    .jr-contact-channels {
        margin-top: -58px;
        padding-bottom: 32px;
        position: relative;
        z-index: 3;
    }

    .jr-channel-card {
        background: #ffffff;
        border: 1px solid var(--border, #e2e8f2);
        border-radius: 14px;
        padding: 28px 26px;
        height: 100%;
        box-shadow: 0 10px 30px rgba(10, 47, 87, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        display: flex;
        flex-direction: column;
    }

    .jr-channel-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 38px rgba(10, 47, 87, 0.13);
        border-color: #cbd5e1;
    }

    .jr-channel-card__icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 16px;
    }

    .jr-icon-brand {
        background: var(--brand-light, #E6F0F8);
        color: var(--brand, #0861A9);
    }

    .jr-icon-success {
        background: #dcfce7;
        color: #15803d;
    }

    .jr-icon-accent {
        background: #fef3c7;
        color: #b45309;
    }

    .jr-channel-card__tag {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted, #5b6577);
        margin-bottom: 6px;
    }

    .jr-channel-card h3 {
        font-size: 1.18rem;
        font-weight: 700;
        color: var(--text, #141926);
        margin-bottom: 8px;
    }

    .jr-channel-card p {
        font-size: 0.9rem;
        color: var(--muted, #5b6577);
        margin-bottom: 18px;
        flex: 1;
    }

    .jr-channel-card__link {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        border-top: 1px solid var(--border, #e2e8f2);
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--brand, #0861A9);
        text-decoration: none;
    }

    .jr-channel-card__link:hover {
        color: var(--brand-dark, #064A85);
        text-decoration: none;
    }

    /* ── Main Contact Form & Info ── */
    .jr-contact-main {
        padding: 40px 0 64px;
    }

    .jr-form-card {
        background: #ffffff;
        border: 1px solid var(--border, #e2e8f2);
        border-radius: 16px;
        padding: 38px 36px;
        box-shadow: 0 4px 20px rgba(10, 47, 87, 0.06);
    }

    .jr-form-card__header {
        margin-bottom: 26px;
    }

    .jr-form-card__header h2 {
        font-size: clamp(1.5rem, 2.5vw, 1.95rem);
        font-weight: 800;
        color: var(--text, #141926);
        margin-bottom: 6px;
    }

    .jr-form-card__header p {
        color: var(--muted, #5b6577);
        font-size: 0.94rem;
        margin: 0;
    }

    /* Topic Pills */
    .jr-topic-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .jr-topic-pill {
        background: var(--bg, #f5f7fb);
        border: 1.5px solid var(--border, #e2e8f2);
        color: var(--muted, #5b6577);
        border-radius: 999px;
        padding: 7px 15px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.16s ease;
    }

    .jr-topic-pill:hover {
        border-color: var(--brand, #0861A9);
        color: var(--brand, #0861A9);
    }

    .jr-topic-pill.active {
        background: var(--brand, #0861A9);
        border-color: var(--brand, #0861A9);
        color: #ffffff;
    }

    /* Inputs */
    .jr-field-label {
        display: block;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--text, #141926);
        margin-bottom: 7px;
    }

    .jr-optional {
        font-weight: 400;
        color: var(--muted, #5b6577);
        font-size: 0.78rem;
    }

    .jr-input-wrap {
        position: relative;
    }

    .jr-input-icon {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: var(--muted, #5b6577);
        font-size: 1rem;
        pointer-events: none;
        z-index: 2;
    }

    .jr-input {
        padding: 11px 14px 11px 40px !important;
        border-radius: 10px !important;
        border: 1.5px solid var(--border, #e2e8f2) !important;
        font-size: 0.92rem !important;
        color: var(--text, #141926) !important;
        background-color: #ffffff !important;
        min-height: 46px;
        transition: border-color 0.16s ease, box-shadow 0.16s ease;
    }

    .jr-textarea {
        padding: 12px 14px !important;
        border-radius: 10px !important;
        border: 1.5px solid var(--border, #e2e8f2) !important;
        font-size: 0.92rem !important;
        color: var(--text, #141926) !important;
        background-color: #ffffff !important;
        transition: border-color 0.16s ease, box-shadow 0.16s ease;
    }

    .jr-input:focus,
    .jr-textarea:focus {
        border-color: var(--brand, #0861A9) !important;
        box-shadow: 0 0 0 3px rgba(8, 97, 169, 0.12) !important;
        outline: none;
    }

    .jr-char-counter {
        font-size: 0.78rem;
        color: var(--muted, #5b6577);
        font-weight: 500;
    }

    .jr-check .form-check-label {
        font-size: 0.86rem;
        color: var(--muted, #5b6577);
    }

    .jr-submit-btn {
        min-height: 50px;
        border-radius: 10px;
        font-weight: 700;
    }

    .jr-form-alert {
        padding: 14px 16px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .jr-form-alert.is-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .jr-form-alert.is-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    /* ── Right Sidebar Info Cards ── */
    .jr-contact-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
        position: sticky;
        top: 96px;
    }

    .jr-info-card {
        background: #ffffff;
        border: 1px solid var(--border, #e2e8f2);
        border-radius: 16px;
        padding: 32px 28px;
        box-shadow: 0 4px 20px rgba(10, 47, 87, 0.06);
    }

    .jr-info-card h3,
    .jr-info-card h4 {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text, #141926);
        margin-bottom: 6px;
    }

    .jr-info-card__sub {
        font-size: 0.88rem;
        color: var(--muted, #5b6577);
        margin-bottom: 22px;
    }

    .jr-info-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .jr-info-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f1f5f9;
    }

    .jr-info-item:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .jr-info-item__icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: var(--brand-light, #E6F0F8);
        color: var(--brand, #0861A9);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .jr-info-item__body h6 {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text, #141926);
        margin-bottom: 3px;
    }

    .jr-info-item__body p {
        font-size: 0.9rem;
        color: var(--text, #141926);
        margin-bottom: 3px;
    }

    .jr-plain-link {
        color: var(--text, #141926);
        font-weight: 600;
        text-decoration: none;
    }

    .jr-plain-link:hover {
        color: var(--brand, #0861A9);
    }

    .jr-info-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--brand, #0861A9);
    }

    .jr-info-meta {
        display: block;
        font-size: 0.8rem;
        color: var(--muted, #5b6577);
    }

    /* Social Grid */
    .jr-social-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .jr-social-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        border-radius: 10px;
        font-size: 0.86rem;
        font-weight: 600;
        color: #ffffff !important;
        text-decoration: none !important;
        transition: transform 0.16s ease, opacity 0.16s ease;
    }

    .jr-social-btn:hover {
        transform: translateY(-2px);
        opacity: 0.92;
    }

    .jr-social--whatsapp  { background: #16a34a; }
    .jr-social--x         { background: #0f172a; }
    .jr-social--linkedin  { background: #0a66c2; }
    .jr-social--instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
    .jr-social--tiktok    { background: #18181b; }
    .jr-social--telegram  { background: #0284c7; }

    /* ── FAQ Section ── */
    .jr-contact-faq {
        padding: 56px 0;
        background: #ffffff;
        border-top: 1px solid var(--border, #e2e8f2);
        border-bottom: 1px solid var(--border, #e2e8f2);
    }

    .jr-accordion .accordion-item {
        border: 1px solid var(--border, #e2e8f2);
        border-radius: 12px !important;
        margin-bottom: 12px;
        overflow: hidden;
    }

    .jr-accordion .accordion-button {
        font-family: 'Sora', 'Inter', sans-serif;
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--text, #141926);
        padding: 18px 22px;
        background: #ffffff;
        box-shadow: none !important;
    }

    .jr-accordion .accordion-button:not(.collapsed) {
        color: var(--brand, #0861A9);
        background: var(--brand-light, #E6F0F8);
    }

    .jr-accordion .accordion-body {
        padding: 18px 22px;
        color: var(--muted, #5b6577);
        font-size: 0.92rem;
        line-height: 1.7;
    }

    /* ── Map Section ── */
    .jr-contact-map {
        padding: 56px 0 72px;
    }

    .jr-map-frame {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border, #e2e8f2);
        box-shadow: 0 8px 28px rgba(10, 47, 87, 0.08);
    }

    @media (max-width: 767.98px) {
        .jr-contact-hero {
            padding: 52px 0 88px;
        }

        .jr-form-card,
        .jr-info-card {
            padding: 24px 20px;
        }

        .jr-social-grid {
            grid-template-columns: 1fr;
        }
    }

/* Hallmark · Gate 26b Tactile micro-depression */
.jr-topic-pill:active, .jr-btn-ghost-light:active, .jr-social-btn:active, .jr-btn-primary:active {
  transform: translateY(1px);
}

/* Hallmark · Gate 27 Prefers Reduced Motion */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha_site_key'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = submitBtn.querySelector('.submit-text');
        const spinner = submitBtn.querySelector('.spinner-border');
        const subjectSelect = document.getElementById('subject');
        const alertBox = document.getElementById('contactAlertBox');

        // Topic pills sync with Subject dropdown
        const topicPills = document.querySelectorAll('.jr-topic-pill');
        topicPills.forEach(pill => {
            pill.addEventListener('click', function() {
                topicPills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                if (subjectSelect) {
                    subjectSelect.value = this.getAttribute('data-topic');
                }
            });
        });

        if (subjectSelect) {
            subjectSelect.addEventListener('change', function() {
                topicPills.forEach(p => {
                    p.classList.toggle('active', p.getAttribute('data-topic') === this.value);
                });
            });
        }

        function showAlert(type, message) {
            if (alertBox) {
                alertBox.className = 'jr-form-alert ' + (type === 'success' ? 'is-success' : 'is-error');
                alertBox.innerHTML = (type === 'success'
                    ? '<i class="bi bi-check-circle-fill me-2"></i>'
                    : '<i class="bi bi-exclamation-triangle-fill me-2"></i>') + message;
            }
            if (typeof toastr !== 'undefined') {
                type === 'success' ? toastr.success(message) : toastr.error(message);
            }
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            submitBtn.disabled = true;
            submitText.classList.add('d-none');
            spinner.classList.remove('d-none');

            function doContactSubmit(token) {
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
                            showAlert('success', data.message || 'Thank you! Your message has been sent.');
                            form.reset();
                            form.classList.remove('was-validated');
                            const charCount = document.getElementById('charCount');
                            if (charCount) charCount.textContent = '0';
                        } else {
                            if (data.errors) {
                                showAlert('danger', Object.values(data.errors).join('<br>'));
                            } else {
                                showAlert('danger', data.message || 'Please check your input and try again.');
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
            }

            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.ready(function() {
                    grecaptcha.execute('<?= env('recaptcha_site_key'); ?>', {
                        action: 'contact'
                    }).then(function(token) {
                        doContactSubmit(token);
                    });
                });
            } else {
                doContactSubmit('dev-bypass');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('.scroll-to').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId && targetId.startsWith('#')) {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // Nigerian phone formatter
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                let raw = this.value.replace(/[^\d+]/g, '');
                if (!raw) {
                    this.value = '';
                    return;
                }
                if (raw.startsWith('0')) {
                    raw = '+234' + raw.slice(1);
                } else if (raw.startsWith('234')) {
                    raw = '+234' + raw.slice(3);
                } else if (!raw.startsWith('+234')) {
                    raw = '+234' + raw.replace(/^\+/, '');
                }
                raw = raw.replace(/^(\+234\d{10}).*$/, '$1');
                const match = raw.match(/^\+234(\d{3})(\d{3})(\d{4})$/);
                this.value = match ? `+234 ${match[1]} ${match[2]} ${match[3]}` : raw;
            });
        }

        // Character counter for message
        const messageTextarea = document.getElementById('message');
        if (messageTextarea) {
            messageTextarea.addEventListener('input', function() {
                const charCount = document.getElementById('charCount');
                if (!charCount) return;
                charCount.textContent = this.value.length;
                charCount.classList.toggle('text-danger', this.value.length > 1000);
            });
        }
    });
</script>
<?= $this->endSection() ?>