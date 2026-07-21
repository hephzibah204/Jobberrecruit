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

<!-- Hero -->
<section class="rs-hero position-relative overflow-hidden">
    <span class="gridbg" aria-hidden="true"></span>
    <div class="container position-relative z-1 pt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white text-opacity-75">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Recruitment services</li>
            </ol>
        </nav>
        <div class="rs-hero-inner">
            <div>
                <div class="rs-eyebrow"><svg aria-hidden="true" width="14" height="14"><use href="#i-handshake"/></svg>Done-for-you recruitment</div>
                <h1>Transform your business with <em>precision hiring</em></h1>
                <p class="lede">Stop searching. Start hiring. We don't just fill vacancies — we analyse your business DNA to find the talent that drives profit and culture.</p>
                <div class="rs-hero-actions">
                    <a href="#inquiry" class="btn btn-warning btn-lg"><svg aria-hidden="true" width="16" height="16"><use href="#i-search"/></svg>Request candidate quote</a>
                    <a href="#services" class="btn btn-outline-light btn-lg">View our services</a>
                </div>
            </div>
            <div class="rs-hero-visual">
                <div class="quick-card shadow-lg">
                    <h3 class="fw-bold mb-3">Quick candidate quote</h3>
                    <form action="<?= base_url('submit-recruitment-inquiry') ?>" method="POST" id="quickRecruitmentForm">
                        <div class="quick-form-group mb-2">
                            <input type="text" name="fullName" placeholder="Your full name" required>
                        </div>
                        <div class="quick-form-group mb-2">
                            <input type="text" name="companyName" placeholder="Company name" required>
                        </div>
                        <div class="quick-form-group mb-2">
                            <input type="email" name="email" placeholder="Work email" required>
                        </div>
                        <div class="quick-form-group mb-2">
                            <input type="tel" name="phone" placeholder="Phone number" required>
                        </div>
                        <div class="quick-form-group mb-3">
                            <input type="text" name="role" placeholder="Role to hire (e.g. Accountant)" required>
                        </div>
                        <input type="hidden" name="experience" value="mid">
                        <input type="hidden" name="message" value="Quick quote request submitted from the recruitment page header form.">
                        <button type="submit" class="btn quick-btn w-100">Get Free Candidate Quote &#x2192;</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Philosophy -->
<section class="sec tint" aria-labelledby="phil-h">
    <div class="container">
        <div class="sec-head">
            <div class="section-label mx-auto"><svg aria-hidden="true" width="13" height="13"><use href="#i-spark"/></svg>Recruitment reimagined</div>
            <h2 class="section-title" id="phil-h">A résumé tells only <span>half the story</span></h2>
            <p>While other agencies match keywords, we match potential to purpose. A "Marketing Manager" in a startup needs a different mindset than one in a multinational — so we dig into your culture to design a strategy that fits.</p>
        </div>
        <div class="phil-grid">
            <div class="phil"><div class="phil-ic"><svg aria-hidden="true" width="34" height="34"><use href="#i-search"/></svg></div><h3>Understanding</h3><p>We dig into your organisational culture to understand what makes your team tick and what drives your success.</p></div>
            <div class="phil"><div class="phil-ic"><svg aria-hidden="true" width="34" height="34"><use href="#i-chip"/></svg></div><h3>Matching</h3><p>We analyse both skills and mindset to find candidates who fit your unique requirements and company culture.</p></div>
            <div class="phil"><div class="phil-ic"><svg aria-hidden="true" width="34" height="34"><use href="#i-chart"/></svg></div><h3>Success</h3><p>We measure success by your satisfaction and the long-term performance of every candidate we place.</p></div>
        </div>
    </div>
</section>

<!-- Digital Reach / Advantage -->
<section class="sec white" aria-labelledby="adv-h">
    <div class="container">
        <div class="adv-grid">
            <div class="adv-left">
                <div class="section-label"><svg aria-hidden="true" width="13" height="13"><use href="#i-globe"/></svg>360-degree digital reach</div>
                <h2 id="adv-h">We don't rely on luck. We rely on reach.</h2>
                <p>JobberRecruit leverages a massive digital ecosystem to put your role in front of the right people — including the passive talent your competitors can't see.</p>
                <div class="adv-reach">
                    <div class="adv-reach-item"><span class="adv-reach-ic ari-1"><svg aria-hidden="true" width="21" height="21"><use href="#i-users"/></svg></span><span class="adv-reach-tx"><strong>Active social communities</strong><span>Thousands of members in our niche WhatsApp and Telegram groups.</span></span></div>
                    <div class="adv-reach-item"><span class="adv-reach-ic ari-2"><svg aria-hidden="true" width="21" height="21"><use href="#i-mega"/></svg></span><span class="adv-reach-tx"><strong>Social media authority</strong><span>A commanding presence on X (Twitter) and Instagram.</span></span></div>
                    <div class="adv-reach-item"><span class="adv-reach-ic ari-3"><svg aria-hidden="true" width="21" height="21"><use href="#i-share-nodes"/></svg></span><span class="adv-reach-tx"><strong>Strategic alliances</strong><span>A network of digital collaborators who amplify your job alerts.</span></span></div>
                </div>
            </div>
            <div class="adv-card">
                <h3>The JobberRecruit advantage</h3>
                <p class="intro">Why do leading Nigerian companies trust us with their most valuable asset — their people?</p>
                <div class="adv-point"><span class="adv-point-ic"><svg aria-hidden="true" width="20" height="20"><use href="#i-users"/></svg></span><div><h4>Unrivalled talent pool</h4><p>We use a proprietary database and an aggressive offline network to access passive candidates you won't find on LinkedIn.</p></div></div>
                <div class="adv-point"><span class="adv-point-ic"><svg aria-hidden="true" width="20" height="20"><use href="#i-search"/></svg></span><div><h4>Bespoke headhunting</h4><p>We're not CV shufflers. We're talent architects who study your vision and culture to ensure every candidate is a long-term fit.</p></div></div>
                <div class="adv-point"><span class="adv-point-ic"><svg aria-hidden="true" width="20" height="20"><use href="#i-chart"/></svg></span><div><h4>ROI-focused results</h4><p>Bad hires are expensive. Our rigorous vetting lowers staff turnover, saving you money on training and re-hiring.</p></div></div>
                <a href="#inquiry" class="btn btn-primary w-100">Contact us</a>
            </div>
        </div>
    </div>
</section>

<!-- AdSense -->
<ins class="adsbygoogle"
    style="display:block"
    data-ad-client="ca-pub-3464186884176173"
    data-ad-slot="6229476516"
    data-ad-format="auto"
    data-full-width-responsive="true"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>

<!-- Services -->
<section class="sec tint" id="services" aria-labelledby="svc-h">
    <div class="container">
        <div class="sec-head">
            <div class="section-label mx-auto"><svg aria-hidden="true" width="13" height="13"><use href="#i-building"/></svg>What we do</div>
            <h2 class="section-title" id="svc-h">Comprehensive <span>staffing solutions</span></h2>
            <p>As a premier recruitment company in Nigeria, we offer tailored solutions across the private, public and NGO sectors.</p>
        </div>
        <div class="svc-grid">
            <div class="svc-card card border-0 shadow-sm">
                <div class="svc-top"><span class="svc-ic si-exec"><svg aria-hidden="true" width="28" height="28"><use href="#i-cap"/></svg></span><h3>Executive Search &amp; Headhunting</h3></div>
                <div class="svc-body">
                    <p>Hiring leadership requires discretion and deep market insight. We specialise in identifying high-impact leaders who are often not actively looking for a job.</p>
                    <p class="svc-meta-label">Roles we fill</p>
                    <div class="svc-tags"><span class="svc-tag">CEO</span><span class="svc-tag">CFO</span><span class="svc-tag">COO</span><span class="svc-tag">CTO</span><span class="svc-tag">Head of People</span><span class="svc-tag">VP Sales</span><span class="svc-tag">Directors</span></div>
                    <div class="svc-promise"><strong>What we do:</strong> we map the market to find the "hidden 10%" of top-tier talent.</div>
                    <a href="#inquiry" class="btn btn-outline-primary">Request a quote</a>
                </div>
            </div>

            <div class="svc-card card border-0 shadow-sm">
                <div class="svc-top"><span class="svc-ic si-mid"><svg aria-hidden="true" width="28" height="28"><use href="#i-gear"/></svg></span><h3>Mid-Level Management Recruitment</h3></div>
                <div class="svc-body">
                    <p>Mid-level managers are the engine room of your organisation. Without strong operational leadership, growth stalls. We source multi-skilled managers with verified track records of stability and performance.</p>
                    <div class="svc-promise"><strong>Our promise:</strong> we focus on candidates with high emotional intelligence and operational expertise to keep your day-to-day thriving.</div>
                    <a href="#inquiry" class="btn btn-outline-primary">Request a quote</a>
                </div>
            </div>

            <div class="svc-card card border-0 shadow-sm">
                <div class="svc-top"><span class="svc-ic si-grad"><svg aria-hidden="true" width="28" height="28"><use href="#i-cap"/></svg></span><h3>Graduate &amp; Entry-Level Talent</h3></div>
                <div class="svc-body">
                    <p>Harness the energy of the future. We identify high-potential Gen-Z talent characterised by digital savviness, adaptability and high IQ.</p>
                    <p class="svc-meta-label">The process</p>
                    <div class="svc-promise">We use aptitude tests, group assessments and behavioural interviews to filter thousands of applicants down to the top 1% ready to help your organisation win.</div>
                    <a href="#inquiry" class="btn btn-outline-primary">Request a quote</a>
                </div>
            </div>

            <div class="svc-card card border-0 shadow-sm">
                <div class="svc-top"><span class="svc-ic si-bpo"><svg aria-hidden="true" width="28" height="28"><use href="#i-bag"/></svg></span><h3>Business Process Outsourcing (BPO)</h3></div>
                <div class="svc-body">
                    <p>Reduce your overhead and liability. Our outsourcing service handles payroll, taxes and labour-law compliance, freeing you to focus on your core business.</p>
                    <p class="svc-meta-label">Common roles</p>
                    <div class="svc-tags"><span class="svc-tag">Call Centre Agents</span><span class="svc-tag">Data Entry</span><span class="svc-tag">Sales Reps</span><span class="svc-tag">IT Support</span><span class="svc-tag">Developers</span></div>
                    <div class="svc-promise"><strong>Perfect for:</strong> contract staff, remote teams, seasonal hires and temporary projects.</div>
                    <a href="#inquiry" class="btn btn-outline-primary">Request a quote</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Process Timeline -->
<section class="sec white" aria-labelledby="proc-h">
    <div class="container">
        <div class="sec-head">
            <div class="section-label mx-auto"><svg aria-hidden="true" width="13" height="13"><use href="#i-sliders"/></svg>How we work</div>
            <h2 class="section-title" id="proc-h">From brief to <span>great hire</span></h2>
            <p>A clear, transparent process that keeps you informed at every step.</p>
        </div>
        <div class="proc-grid">
            <div class="proc-step" data-n="1"><div class="proc-card card border-0 shadow-sm"><h3>Discovery</h3><p>We meet to understand your role, culture, and what success looks like for the hire.</p></div></div>
            <div class="proc-step" data-n="2"><div class="proc-card card border-0 shadow-sm"><h3>Search &amp; headhunt</h3><p>We map the market and reach active and passive candidates through our network.</p></div></div>
            <div class="proc-step" data-n="3"><div class="proc-card card border-0 shadow-sm"><h3>Vet &amp; shortlist</h3><p>We screen, assess and verify, then present a tight shortlist of the best fits.</p></div></div>
            <div class="proc-step" data-n="4"><div class="proc-card card border-0 shadow-sm"><h3>Place &amp; support</h3><p>We support interviews, offers and onboarding — and check in after placement.</p></div></div>
        </div>
    </div>
</section>

<!-- Inquiry Form -->
<section class="sec tint" id="inquiry" aria-labelledby="inq-h">
    <div class="container">
        <div class="inq-grid">
            <div class="inq-left">
                <div class="section-label"><svg aria-hidden="true" width="13" height="13"><use href="#i-handshake"/></svg>Ready to hire? Let's talk</div>
                <h2 id="inq-h">Tell us who you're looking for, and we'll do the rest</h2>
                <p>Share your hiring needs and our team will get back to you with a tailored candidate quote — usually within one business day.</p>
                <div class="inq-trust">
                    <div class="inq-trust-item"><svg aria-hidden="true" width="18" height="18"><use href="#i-check"/></svg>No charge to request a quote</div>
                    <div class="inq-trust-item"><svg aria-hidden="true" width="18" height="18"><use href="#i-check"/></svg>Shortlists in 24–48 hrs for urgent roles</div>
                    <div class="inq-trust-item"><svg aria-hidden="true" width="18" height="18"><use href="#i-check"/></svg>95% client retention rate</div>
                    <div class="inq-trust-item"><svg aria-hidden="true" width="18" height="18"><use href="#i-check"/></svg>Your details stay confidential</div>
                </div>
            </div>

            <div class="inq-card card border-0 shadow-lg">
                <form action="<?= base_url('submit-recruitment-inquiry') ?>" method="POST" id="recruitmentForm">
                    <div class="inq-form">
                        <div class="inq-field"><label for="fullName">Full name <span class="req">*</span></label><input type="text" id="fullName" name="fullName" placeholder="Your name" required></div>
                        <div class="inq-field"><label for="companyName">Company name <span class="req">*</span></label><input type="text" id="companyName" name="companyName" placeholder="Your company" required></div>
                        <div class="inq-field"><label for="email">Email address <span class="req">*</span></label><input type="email" id="email" name="email" placeholder="you@company.com" required></div>
                        <div class="inq-field"><label for="phone">Phone number <span class="req">*</span></label><input type="tel" id="phone" name="phone" placeholder="+234 800 000 0000" required></div>
                        <div class="inq-field"><label for="role">Role to hire <span class="req">*</span></label><input type="text" id="role" name="role" placeholder="e.g. Digital Marketer, Accountant" required></div>
                        <div class="inq-field"><label for="experience">Required experience level <span class="req">*</span></label>
                            <select id="experience" name="experience" required>
                                <option value="" disabled selected>Select level…</option>
                                <option value="entry">Entry Level</option>
                                <option value="mid">Mid Level</option>
                                <option value="senior">Senior Level</option>
                                <option value="executive">Executive / C-suite</option>
                            </select>
                        </div>
                        <div class="inq-field"><label for="budget">Budget / salary range</label><input type="text" id="budget" name="budget" placeholder="e.g. ₦200,000 – ₦300,000"></div>
                        <div class="inq-field"><label for="schedule">Working schedule</label><input type="text" id="schedule" name="schedule" placeholder="e.g. Mon–Fri, or remote"></div>
                        <div class="inq-field full"><label for="location">Location</label>
                            <select id="location" name="location">
                                <option value="" disabled selected>Select state…</option>
                                <option value="Lagos">Lagos</option>
                                <option value="Abuja">Abuja (FCT)</option>
                                <option value="Rivers">Rivers</option>
                                <option value="Oyo">Oyo</option>
                                <option value="Kano">Kano</option>
                                <option value="Edo">Edo</option>
                                <option value="Delta">Delta</option>
                                <option value="Remote">Remote</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="inq-field full"><label for="message">How can we help? <span class="req">*</span></label><textarea id="message" name="message" rows="4" placeholder="Tell us about your hiring needs…" required></textarea></div>
                        <label class="inq-consent"><input type="checkbox" id="terms" name="terms" required> I agree to receive recruitment updates and accept JobberRecruit's <a href="<?= base_url('terms') ?>">Terms of Service</a> and <a href="<?= base_url('privacy-policy') ?>">Privacy Policy</a>.</label>
                        <div class="inq-submit"><button type="submit" class="btn btn-primary btn-lg w-100"><svg aria-hidden="true" width="16" height="16"><use href="#i-mail"/></svg>Submit inquiry</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Dual CTA -->
<section class="sec white" aria-labelledby="dual-h">
    <div class="container">
        <div class="sec-head">
            <div class="section-label mx-auto"><svg aria-hidden="true" width="13" height="13"><use href="#i-shield"/></svg>Hire with more confidence</div>
            <h2 class="section-title" id="dual-h">Prefer to <span>do it yourself?</span></h2>
            <p>Turn interest into qualified applications with flexible hiring tools, employer branding and faster screening workflows.</p>
        </div>
        <div class="dual">
            <div class="dual-card accent card border-0"><h3>Post your next role</h3><p>Publish openings, manage applicants and keep your hiring pipeline moving from one dashboard.</p><a href="<?= base_url('post-a-job') ?>" class="btn" style="background:var(--brand-deep);color:#fff;border-color:var(--brand-deep)">Post a job &rarr;</a></div>
            <div class="dual-card blue card border-0"><h3>Compare hiring plans</h3><p>Choose the pricing option that matches your team size, hiring urgency and visibility needs.</p><a href="<?= base_url('employers#pricing') ?>" class="btn btn-warning">See pricing &rarr;</a></div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="sec tint" aria-labelledby="faq-h">
    <div class="container">
        <div class="sec-head"><div class="section-label mx-auto"><svg aria-hidden="true" width="13" height="13"><use href="#i-bulb"/></svg>Questions &amp; answers</div><h2 class="section-title" id="faq-h">Recruitment services <span>FAQ</span></h2></div>
        <div class="faq-wrap">
            <div class="faq-item card border-0 shadow-sm"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">How is this different from posting a job myself? <svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Posting a job is self-serve advertising. Our recruitment service is fully done-for-you: we headhunt, screen and shortlist candidates on your behalf — including passive talent who aren't actively job-hunting and won't be reached by a job ad alone.</div></div></div>
            <div class="faq-item card border-0 shadow-sm"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">How fast can you fill a role? <svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">For urgent roles we typically present a qualified shortlist within 24 to 48 hours. Timelines for senior and executive searches vary with the seniority and specialism of the role.</div></div></div>
            <div class="faq-item card border-0 shadow-sm"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">What types of roles do you recruit for? <svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">We cover executive search and headhunting, mid-level management, graduate and entry-level talent, and business process outsourcing for contract, remote and seasonal teams.</div></div></div>
            <div class="faq-item card border-0 shadow-sm"><button class="faq-q" aria-expanded="false" onclick="toggleFaq(this)">How much do recruitment services cost? <svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><div class="faq-a"><div class="faq-a-in">Fees depend on the role's seniority, urgency and volume. Request a candidate quote and our team will send tailored pricing — there's no charge to request a quote.</div></div></div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    html, body { max-width: 100%; overflow-x: hidden; }

    /* ── Hero ── */
    .rs-hero {
        background: radial-gradient(ellipse 60% 50% at 85% 20%, rgba(237,144,32,.18) 0%, transparent 55%),
                    radial-gradient(ellipse 70% 60% at 5% 95%, rgba(13,96,158,.35) 0%, transparent 55%),
                    linear-gradient(155deg, var(--brand-deep) 0%, var(--brand-deep) 40%, var(--brand-dark) 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .rs-hero .gridbg {
        position: absolute; inset: 0; opacity: .3; pointer-events: none;
        background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
        background-size: 48px 48px;
        -webkit-mask-image: radial-gradient(ellipse 70% 80% at 30% 40%, #000 30%, transparent 80%);
        mask-image: radial-gradient(ellipse 70% 80% at 30% 40%, #000 30%, transparent 80%);
    }
    .rs-hero-inner {
        position: relative; z-index: 1;
        display: grid; grid-template-columns: 1.1fr .9fr; gap: 48px; align-items: center;
        padding: 48px 0 64px;
    }
    .rs-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2);
        border-radius: 20px; padding: 6px 15px; color: rgba(255,255,255,.92); margin-bottom: 20px;
    }
    .rs-eyebrow svg { color: var(--accent); }
    .rs-hero h1 {
        font-size: clamp(2rem, 4.5vw, 3.4rem);
        font-weight: 800; line-height: 1.06; letter-spacing: -.025em; margin-bottom: 18px;
    }
    .rs-hero h1 em { font-style: normal; color: var(--accent); display: block; }
    .rs-hero .lede {
        font-size: 1.05rem; color: rgba(255,255,255,.76); line-height: 1.65;
        max-width: 500px; margin-bottom: 30px;
    }
    .rs-hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
    .rs-hero-visual { position: relative; }

    .quick-card {
        background: rgba(255, 255, 255, 0.07) !important;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-radius: 20px;
        padding: 26px;
        color: #fff;
    }
    .quick-card h3 {
        font-family: 'Sora', sans-serif;
        color: #fff !important;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 18px;
        letter-spacing: -0.01em;
        text-transform: none;
    }
    .quick-form-group {
        position: relative;
    }
    .quick-form-group input {
        width: 100%;
        box-sizing: border-box;
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1.5px solid rgba(255, 255, 255, 0.15) !important;
        border-radius: 10px;
        padding: 9px 14px;
        color: #fff !important;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        transition: all 0.2s ease;
    }
    .quick-form-group input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    .quick-form-group input:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--accent);
        outline: none;
        box-shadow: 0 0 0 3px rgba(240, 143, 26, 0.25);
    }
    .quick-btn {
        background: var(--accent) !important;
        color: var(--brand-deep) !important;
        font-family: 'Sora', sans-serif;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        padding: 11px;
        font-size: 0.92rem;
        transition: all 0.2s ease;
    }
    .quick-btn:hover {
        background: #f0a942 !important;
        transform: translateY(-1px);
    }

    /* ── Sections ── */
    .sec { padding: 72px 0; }
    .sec.tint { background: #f5f7fb; }
    .sec.white { background: #fff; }
    .sec-head { text-align: center; max-width: 660px; margin: 0 auto 46px; }
    .section-title {
        font-size: clamp(1.6rem, 3vw, 2.3rem); font-weight: 800; line-height: 1.15; margin-bottom: 12px; color: #141926;
    }
    .section-title span { color: var(--brand); }
    .sec-head p { color: #5b6577; font-size: .95rem; line-height: 1.65; }

    .section-label {
        display: inline-flex; align-items: center; gap: 7px;
        font-size: .72rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        color: var(--brand); background: var(--brand-light); padding: 5px 13px;
        border-radius: 20px; margin-bottom: 14px;
    }

    /* ── Philosophy ── */
    .phil-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; margin-top: 8px; }
    .phil { text-align: center; padding: 8px; }
    .phil-ic {
        width: 72px; height: 72px; border-radius: 20px;
        background: linear-gradient(135deg, var(--accent), #f0a942);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 18px; box-shadow: 0 10px 26px rgba(237,144,32,.3);
    }
    .phil-ic svg { color: #fff; }
    .phil h3 { font-size: 1.15rem; font-weight: 700; color: var(--brand); margin-bottom: 9px; }
    .phil p { font-size: .88rem; color: #5b6577; line-height: 1.62; max-width: 300px; margin: 0 auto; }

    /* ── Advantage ── */
    .adv-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; }
    .adv-left h2 { font-size: clamp(1.6rem, 2.8vw, 2.2rem); font-weight: 800; line-height: 1.16; margin-bottom: 14px; }
    .adv-left > p { color: #5b6577; font-size: .95rem; line-height: 1.65; margin-bottom: 24px; }
    .adv-reach { display: flex; flex-direction: column; gap: 14px; }
    .adv-reach-item { display: flex; gap: 14px; align-items: flex-start; }
    .adv-reach-ic { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ari-1 { background: #dcfce7; color: #16a34a; }
    .ari-2 { background: var(--brand-light); color: var(--brand); }
    .ari-3 { background: #f3e8ff; color: #7c3aed; }
    .adv-reach-tx strong { display: block; font-size: .95rem; font-weight: 700; color: #141926; margin-bottom: 2px; }
    .adv-reach-tx span { font-size: .84rem; color: #5b6577; line-height: 1.5; }
    .adv-card {
        background: #fff; border: 1px solid #e2e8f2; border-radius: 18px; padding: 34px;
        box-shadow: 0 14px 40px rgba(10,47,87,.12);
    }
    .adv-card h3 { font-size: 1.2rem; font-weight: 800; margin-bottom: 6px; }
    .adv-card > p.intro { font-size: .88rem; color: #5b6577; margin-bottom: 22px; line-height: 1.55; }
    .adv-point { display: flex; gap: 13px; padding: 16px 0; }
    .adv-point + .adv-point { border-top: 1px solid #e2e8f2; }
    .adv-point-ic { width: 40px; height: 40px; border-radius: 11px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .adv-point h4 { font-size: .95rem; font-weight: 700; color: var(--brand); margin-bottom: 3px; }
    .adv-point p { font-size: .84rem; color: #5b6577; line-height: 1.55; }

    /* ── Services ── */
    .svc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .svc-card { border-radius: 16px; overflow: hidden; transition: all .18s ease; display: flex; flex-direction: column; }
    .svc-card:hover { box-shadow: 0 14px 40px rgba(10,47,87,.16) !important; transform: translateY(-4px); }
    .svc-top { display: flex; gap: 18px; padding: 28px 28px 0; }
    .svc-ic { width: 58px; height: 58px; border-radius: 15px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .si-exec { background: var(--brand-light); color: var(--brand); }
    .si-mid { background: #e0f2fe; color: #0891b2; }
    .si-grad { background: #dcfce7; color: #16a34a; }
    .si-bpo { background: #fef3c7; color: #C8770E; }
    .svc-top h3 { font-size: 1.2rem; font-weight: 800; line-height: 1.2; }
    .svc-body { padding: 18px 28px 28px; display: flex; flex-direction: column; flex: 1; }
    .svc-body > p { font-size: .88rem; color: #5b6577; line-height: 1.62; margin-bottom: 16px; }
    .svc-meta-label { font-size: .74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--brand); margin: 0 0 8px; }
    .svc-tags { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 18px; }
    .svc-tag { font-size: .72rem; font-weight: 600; color: var(--brand-deep); background: #f5f7fb; border: 1px solid #e2e8f2; padding: 5px 11px; border-radius: 16px; }
    .svc-promise { background: var(--brand-light); border-radius: 10px; padding: 13px 15px; font-size: .83rem; color: var(--brand-dark); line-height: 1.55; margin-bottom: 18px; }
    .svc-promise strong { color: var(--brand); }
    .svc-card .btn { width: 100%; justify-content: center; margin-top: auto; }

    /* ── Process Timeline ── */
    .proc-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    .proc-step { position: relative; padding-top: 20px; }
    .proc-step::before {
        content: attr(data-n); position: absolute; top: 0; left: 0;
        width: 34px; height: 34px; border-radius: 50%; background: var(--brand);
        color: #fff; font-weight: 800; font-size: .9rem;
        display: flex; align-items: center; justify-content: center;
    }
    .proc-step::after {
        content: ''; position: absolute; top: 16px; left: 40px; right: -9px;
        height: 2px; background: #e2e8f2;
    }
    .proc-step:last-child::after { display: none; }
    .proc-card { border-radius: 13px; padding: 22px 20px; margin-top: 8px; height: 100%; }
    .proc-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 7px; }
    .proc-card p { font-size: .83rem; color: #5b6577; line-height: 1.58; }

    /* ── Inquiry Form ── */
    .inq-grid { display: grid; grid-template-columns: .85fr 1.15fr; gap: 30px; align-items: start; }
    .inq-left { position: sticky; top: 90px; }
    .inq-left .section-label { margin-bottom: 14px; }
    .inq-left h2 { font-size: clamp(1.5rem, 2.6vw, 2.1rem); font-weight: 800; line-height: 1.18; margin-bottom: 14px; }
    .inq-left > p { color: #5b6577; font-size: .93rem; line-height: 1.65; margin-bottom: 24px; }
    .inq-trust { display: flex; flex-direction: column; gap: 13px; }
    .inq-trust-item { display: flex; align-items: center; gap: 11px; font-size: .88rem; }
    .inq-trust-item svg { color: #16a34a; flex-shrink: 0; }
    .inq-card { border-radius: 18px; padding: 32px; }
    .inq-form { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .inq-field { display: flex; flex-direction: column; }
    .inq-field.full { grid-column: 1 / -1; }
    .inq-field label { font-size: .8rem; font-weight: 600; color: #141926; margin-bottom: 6px; }
    .inq-field label .req { color: #dc2626; }
    .inq-field input, .inq-field select, .inq-field textarea {
        border: 1.5px solid #e2e8f2; border-radius: 9px; padding: 11px 13px;
        font-size: .88rem; color: #141926; background: #fff; outline: none;
        min-height: 44px; width: 100%;
    }
    .inq-field textarea { min-height: 96px; resize: vertical; }
    .inq-field input:focus, .inq-field select:focus, .inq-field textarea:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(13,96,158,.15); }
    .inq-consent { grid-column: 1 / -1; display: flex; gap: 9px; align-items: flex-start; font-size: .78rem; color: #5b6577; line-height: 1.5; }
    .inq-consent input { width: 17px; height: 17px; margin-top: 2px; flex-shrink: 0; accent-color: var(--brand); }
    .inq-submit { grid-column: 1 / -1; }
    .inq-submit .btn { width: 100%; justify-content: center; min-height: 48px; }
    .inq-submit .btn:hover { background: var(--accent); border-color: var(--accent); color: var(--brand-deep); }

    /* ── Dual CTA ── */
    .dual { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; }
    .dual-card { border-radius: 16px; padding: 40px 36px; position: relative; overflow: hidden; }
    .dual-card.accent { background: linear-gradient(135deg, var(--accent), #e8851a); color: var(--brand-deep); }
    .dual-card.blue { background: linear-gradient(135deg, var(--brand-deep), var(--brand)); color: #fff; }
    .dual-card h3 { font-size: 1.4rem; font-weight: 800; margin-bottom: 10px; }
    .dual-card p { font-size: .92rem; line-height: 1.55; margin-bottom: 22px; }
    .dual-card.accent p { color: rgba(10,47,87,.8); }
    .dual-card.blue p { color: rgba(255,255,255,.78); }

    /* ── FAQ ── */
    .faq-wrap { max-width: 760px; margin: 0 auto; }
    .faq-item { border-radius: 12px; margin-bottom: 12px; overflow: hidden; }
    .faq-q {
        width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 12px;
        padding: 18px 22px; background: none; border: none; cursor: pointer;
        font-weight: 700; font-size: .95rem; text-align: left; line-height: 1.4;
    }
    .faq-q svg { flex-shrink: 0; transition: transform .2s; color: var(--brand); }
    .faq-item.open .faq-q svg { transform: rotate(45deg); }
    .faq-a { max-height: 0; overflow: hidden; transition: max-height .26s ease; }
    .faq-a-in { padding: 0 22px 18px; font-size: .88rem; color: #5b6577; line-height: 1.7; }
    .faq-item.open .faq-a { max-height: 280px; }

    /* ── Form overrides ── */
    .form-control:focus, .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 0.25rem rgba(240, 137, 14, 0.25);
    }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .rs-hero-inner { grid-template-columns: 1fr; gap: 36px; padding: 36px 0 48px; }
        .rs-hero-visual { max-width: 420px; }
        .phil-grid { grid-template-columns: 1fr; }
        .adv-grid, .svc-grid, .inq-grid, .dual { grid-template-columns: 1fr; }
        .proc-grid { grid-template-columns: 1fr 1fr; }
        .proc-step::after { display: none; }
        .inq-left { position: static; }
    }
    @media (max-width: 768px) {
        .proc-grid { grid-template-columns: 1fr; }
        .adv-card, .inq-card, .dual-card { padding: 26px 22px; }
        .svc-top { padding: 24px 22px 0; }
        .svc-body { padding: 16px 22px 24px; }
    }
    @media (max-width: 580px) {
        .sec { padding: 48px 0; }
        .inq-form { grid-template-columns: 1fr; }
        input, select, textarea { font-size: 16px !important; }
    }

    /* ── Animation ── */
    .service-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .hover-lift {
        transition: all 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(240,137,14,0.1) !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleFaq(btn) {
    var item = btn.parentElement;
    var open = item.classList.toggle('open');
    btn.setAttribute('aria-expanded', String(open));
}

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                var target = document.querySelector(href);
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Quick Form submission handling
    var quickRecruitmentForm = document.getElementById('quickRecruitmentForm');
    if (quickRecruitmentForm) {
        quickRecruitmentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            var requiredFields = this.querySelectorAll('[required]');
            var isValid = true;

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (isValid) {
                var submitBtn = this.querySelector('button[type="submit"]');
                var originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg aria-hidden="true" width="16" height="16" style="display:inline-block;vertical-align:middle;margin-right:6px"><use href="#i-clock"/></svg>Processing...';
                submitBtn.disabled = true;

                var formData = new FormData(quickRecruitmentForm);

                fetch('<?= base_url('submit-recruitment-inquiry') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message);
                        } else {
                            alert(data.message);
                        }
                        quickRecruitmentForm.reset();
                    } else {
                        alert(data.message || 'An error occurred. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            }
        });
    }

    // Form submission handling
    var recruitmentForm = document.getElementById('recruitmentForm');
    if (recruitmentForm) {
        recruitmentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            var requiredFields = this.querySelectorAll('[required]');
            var isValid = true;

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (isValid) {
                var submitBtn = this.querySelector('button[type="submit"]');
                var originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg aria-hidden="true" width="16" height="16" style="display:inline-block;vertical-align:middle;margin-right:6px"><use href="#i-clock"/></svg>Processing...';
                submitBtn.disabled = true;

                var formData = new FormData(recruitmentForm);

                fetch('<?= base_url('submit-recruitment-inquiry') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message);
                        } else {
                            alert(data.message);
                        }
                        recruitmentForm.reset();
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        alert(data.message || 'An error occurred. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            }
        });
    }

    // Scroll-triggered animations
    var observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    };

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.service-card, .svc-card, .proc-card').forEach(function(el) {
        observer.observe(el);
    });
});
</script>
<?= $this->endSection() ?>
