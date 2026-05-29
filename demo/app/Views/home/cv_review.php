<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>
<style>
    .cv-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .cv-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .cv-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .cv-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(59,130,246,0.15);
        border: 1px solid rgba(59,130,246,0.3);
        color: #93c5fd;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        backdrop-filter: blur(10px);
        margin-bottom: 24px;
    }
    .cv-hero h1 {
        font-size: 3.2rem;
        font-weight: 800;
        line-height: 1.15;
        color: #fff;
        margin-bottom: 20px;
    }
    .cv-hero h1 span {
        background: linear-gradient(135deg, #60a5fa, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .cv-hero p {
        font-size: 1.15rem;
        color: #94a3b8;
        max-width: 600px;
        line-height: 1.7;
    }
    .stat-card {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        padding: 28px 24px;
        text-align: center;
        backdrop-filter: blur(10px);
        transition: transform 0.3s, border-color 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        border-color: rgba(59,130,246,0.4);
    }
    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #60a5fa, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .stat-label {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-top: 4px;
    }
    .section-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .section-header h2 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
    }
    .section-header p {
        color: #64748b;
        font-size: 1.05rem;
        max-width: 600px;
        margin: 0 auto;
    }
    .process-card {
        text-align: center;
        padding: 36px 24px;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
        position: relative;
    }
    .process-card:hover {
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
        transform: translateY(-4px);
    }
    .process-step {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .process-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.6rem;
    }
    .process-card h5 {
        font-weight: 600;
        margin-bottom: 8px;
        color: #0f172a;
    }
    .process-card p {
        color: #64748b;
        font-size: 0.92rem;
        margin: 0;
    }
    .pricing-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 36px 28px;
        transition: all 0.3s;
        position: relative;
        height: 100%;
    }
    .pricing-card:hover {
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
        transform: translateY(-4px);
    }
    .pricing-card.popular {
        border-color: #3b82f6;
        box-shadow: 0 0 0 1px #3b82f6, 0 20px 60px rgba(59,130,246,0.1);
    }
    .popular-badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: #fff;
        padding: 4px 20px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .pricing-card .price {
        font-size: 2.8rem;
        font-weight: 800;
        color: #0f172a;
    }
    .pricing-card .price span {
        font-size: 1rem;
        font-weight: 400;
        color: #64748b;
    }
    .pricing-card ul {
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }
    .pricing-card ul li {
        padding: 8px 0;
        color: #475569;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pricing-card ul li i {
        color: #22c55e;
        font-size: 1.1rem;
    }
    .expert-card {
        text-align: center;
        padding: 32px 20px;
        border-radius: 16px;
        background: #fff;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }
    .expert-card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
        transform: translateY(-4px);
    }
    .expert-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dbeafe, #e0e7ff);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 2rem;
        color: #3b82f6;
        font-weight: 700;
    }
    .expert-card h5 {
        font-weight: 600;
        margin-bottom: 4px;
    }
    .expert-card .role {
        color: #3b82f6;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 10px;
    }
    .expert-card p {
        color: #64748b;
        font-size: 0.88rem;
        margin: 0;
    }
    .testimonial-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 32px;
        transition: all 0.3s;
    }
    .testimonial-card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
    }
    .testimonial-card .stars {
        color: #f59e0b;
        margin-bottom: 12px;
    }
    .testimonial-card blockquote {
        font-style: italic;
        color: #334155;
        line-height: 1.7;
        margin-bottom: 16px;
        font-size: 0.95rem;
    }
    .testimonial-card .author {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .testimonial-card .author .avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #64748b;
    }
    .testimonial-card .author .name {
        font-weight: 600;
        font-size: 0.9rem;
    }
    .testimonial-card .author .title {
        font-size: 0.8rem;
        color: #64748b;
    }
    .review-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .review-item:hover {
        background: #f1f5f9;
    }
    .review-item i {
        font-size: 1.3rem;
        color: #3b82f6;
        margin-top: 2px;
    }
    .review-item h6 {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 4px;
    }
    .review-item p {
        color: #64748b;
        font-size: 0.85rem;
        margin: 0;
        line-height: 1.5;
    }
    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 48px 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #f8fafc;
    }
    .upload-area:hover, .upload-area.dragover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .upload-area i {
        font-size: 3rem;
        color: #3b82f6;
        margin-bottom: 16px;
    }
    .upload-area h5 {
        font-weight: 600;
        margin-bottom: 8px;
    }
    .upload-area p {
        color: #64748b;
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    .upload-area .formats {
        color: #94a3b8;
        font-size: 0.8rem;
        margin-top: 8px;
    }
    .faq-item {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 8px;
        overflow: hidden;
    }
    .faq-question {
        padding: 18px 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: #0f172a;
        transition: background 0.2s;
        background: #fff;
    }
    .faq-question:hover {
        background: #f8fafc;
    }
    .faq-question i {
        transition: transform 0.3s;
        color: #64748b;
    }
    .faq-question.active i {
        transform: rotate(180deg);
    }
    .faq-answer {
        padding: 0 20px 18px;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.7;
        display: none;
    }
    .faq-answer.show {
        display: block;
    }
    .cta-section {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border-radius: 24px;
        padding: 64px 48px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }
    .cta-section h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 12px;
    }
    .cta-section p {
        color: #94a3b8;
        font-size: 1.05rem;
        max-width: 550px;
        margin: 0 auto 28px;
    }
    .file-preview {
        display: none;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 16px;
    }
    .file-preview.show {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .file-preview i {
        font-size: 2rem;
        color: #22c55e;
    }
    .file-preview .file-name {
        font-weight: 600;
        font-size: 0.95rem;
    }
    .file-preview .file-size {
        font-size: 0.8rem;
        color: #64748b;
    }
    @media (max-width: 768px) {
        .cv-hero h1 { font-size: 2rem; }
        .cta-section { padding: 40px 24px; }
    }
</style>

<section class="cv-hero">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="cv-hero-badge">
                    <i class="ti ti-certificate"></i>
                    Trusted by 10,000+ Professionals
                </div>
                <h1>Professional <span>CV Review</span> Service</h1>
                <p>Get your CV reviewed by certified HR professionals and recruitment experts. Receive actionable feedback to land more interviews at top companies.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="<?= base_url($isLoggedIn ? 'cv-review/submit' : 'login?redirect=cv-review/submit') ?>" class="btn btn-primary btn-lg px-4 py-3">
                        <i class="ti ti-upload me-2"></i>Get Your CV Reviewed
                    </a>
                    <a href="#how-it-works" class="btn btn-outline-light btn-lg px-4 py-3">
                        <i class="ti ti-info-circle me-2"></i>How It Works
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-number">10K+</div>
                            <div class="stat-label">CVs Reviewed</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-number">4.9/5</div>
                            <div class="stat-label">Client Rating</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-number">48hr</div>
                            <div class="stat-label">Avg. Turnaround</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-number">78%</div>
                            <div class="stat-label">Interview Success</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-60" id="how-it-works">
    <div class="container">
        <div class="section-header">
            <span class="badge bg-primary-transparent text-primary px-3 py-2 mb-3">How It Works</span>
            <h2>Your CV Review Journey</h2>
            <p>Four simple steps to a standout CV that gets you noticed by recruiters and hiring managers.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="process-card">
                    <div class="process-step">1</div>
                    <div class="process-icon" style="background:#eff6ff;color:#3b82f6;">
                        <i class="ti ti-upload"></i>
                    </div>
                    <h5>Upload Your CV</h5>
                    <p>Submit your CV in PDF, DOC, or DOCX format. No sign-up required for a quick review.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-card">
                    <div class="process-step">2</div>
                    <div class="process-icon" style="background:#f0fdf4;color:#22c55e;">
                        <i class="ti ti-search"></i>
                    </div>
                    <h5>Expert Analysis</h5>
                    <p>A certified reviewer evaluates your CV against industry standards and ATS systems.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-card">
                    <div class="process-step">3</div>
                    <div class="process-icon" style="background:#fef2f2;color:#ef4444;">
                        <i class="ti ti-file-text"></i>
                    </div>
                    <h5>Detailed Report</h5>
                    <p>Receive a comprehensive report with actionable recommendations and before/after examples.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-card">
                    <div class="process-step">4</div>
                    <div class="process-icon" style="background:#faf5ff;color:#a855f7;">
                        <i class="ti ti-trending-up"></i>
                    </div>
                    <h5>Land Interviews</h5>
                    <p>Apply with confidence using your optimized CV that stands out to recruiters.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-60">
    <div class="container">
        <div class="section-header">
            <span class="badge bg-success-transparent text-success px-3 py-2 mb-3">Pricing</span>
            <h2>Choose Your Review Package</h2>
            <p>Select the level of feedback that matches your career goals and budget.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h5 class="fw-bold mb-1">Basic Review</h5>
                    <p style="color:#64748b;font-size:0.9rem;margin-bottom:16px;">AI-powered instant feedback</p>
                    <div class="price">Free <span></span></div>
                    <ul>
                        <li><i class="ti ti-check"></i> ATS Compatibility Scan</li>
                        <li><i class="ti ti-check"></i> Structure & Formatting Check</li>
                        <li><i class="ti ti-check"></i> Keyword Analysis</li>
                        <li><i class="ti ti-check"></i> 48-hour turnaround</li>
                        <li><i class="ti ti-check"></i> Email summary report</li>
                    </ul>
                    <a href="<?= base_url($isLoggedIn ? 'cv-review/submit' : 'login?redirect=cv-review/submit') ?>" class="btn btn-outline-primary w-100 py-2">Get Free Review</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card popular">
                    <div class="popular-badge">Most Popular</div>
                    <h5 class="fw-bold mb-1">Professional Review</h5>
                    <p style="color:#64748b;font-size:0.9rem;margin-bottom:16px;">Expert human + AI analysis</p>
                    <div class="price">&#8358;<?= number_format($planPrices['professional'], 0) ?> <span>/review</span></div>
                    <ul>
                        <li><i class="ti ti-check"></i> Everything in Basic</li>
                        <li><i class="ti ti-check"></i> HR Expert Review</li>
                        <li><i class="ti ti-check"></i> Line-by-line Feedback</li>
                        <li><i class="ti ti-check"></i> Rewritten Sections</li>
                        <li><i class="ti ti-check"></i> Cover Letter Tips</li>
                        <li><i class="ti ti-check"></i> 24-hour turnaround</li>
                    </ul>
                    <a href="#" class="btn btn-primary w-100 py-2 choose-plan" data-plan="professional" data-price="15000">Choose Professional</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h5 class="fw-bold mb-1">Premium Review</h5>
                    <p style="color:#64748b;font-size:0.9rem;margin-bottom:16px;">Full career document overhaul</p>
                    <div class="price">&#8358;<?= number_format($planPrices['premium'], 0) ?> <span>/review</span></div>
                    <ul>
                        <li><i class="ti ti-check"></i> Everything in Professional</li>
                        <li><i class="ti ti-check"></i> Full CV Rewrite</li>
                        <li><i class="ti ti-check"></i> LinkedIn Profile Review</li>
                        <li><i class="ti ti-check"></i> Cover Letter Writing</li>
                        <li><i class="ti ti-check"></i> 1-on-1 Consultation Call</li>
                        <li><i class="ti ti-check"></i> 12-hour turnaround</li>
                    </ul>
                    <a href="#" class="btn btn-outline-primary w-100 py-2 choose-plan" data-plan="premium" data-price="30000">Choose Premium</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-60 bg-light py-5">
    <div class="container">
        <div class="section-header">
            <span class="badge bg-info-transparent text-info px-3 py-2 mb-3">What We Review</span>
            <h2>Comprehensive CV Analysis</h2>
            <p>Every CV undergoes a thorough multi-point inspection across these key areas.</p>
        </div>
        <div class="row g-3">
            <div class="col-md-6 col-lg-4">
                <div class="review-item">
                    <i class="ti ti-layout"></i>
                    <div>
                        <h6>Structure & Formatting</h6>
                        <p>Layout, section ordering, spacing, font consistency, and page balance.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="review-item">
                    <i class="ti ti-user"></i>
                    <div>
                        <h6>Professional Summary</h6>
                        <p>Impact, clarity, keyword optimization, and alignment with target roles.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="review-item">
                    <i class="ti ti-briefcase"></i>
                    <div>
                        <h6>Work Experience</h6>
                        <p>Action verbs, quantified achievements, relevance, and career progression.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="review-item">
                    <i class="ti ti-tools"></i>
                    <div>
                        <h6>Skills Presentation</h6>
                        <p>Relevance, categorization, proficiency indicators, and keyword density.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="review-item">
                    <i class="ti ti-device-analytics"></i>
                    <div>
                        <h6>ATS Compatibility</h6>
                        <p>Parseability, keyword match rate, file format, and section header recognition.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="review-item">
                    <i class="ti ti-rocket"></i>
                    <div>
                        <h6>Overall Impact</h6>
                        <p>First impression, uniqueness, value proposition, and call to action.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-60">
    <div class="container">
        <div class="section-header">
            <span class="badge bg-warning-transparent text-warning px-3 py-2 mb-3">Our Experts</span>
            <h2>Meet Your Reviewers</h2>
            <p>Certified HR professionals and recruitment specialists with years of industry experience.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="expert-card">
                    <div class="expert-avatar">SA</div>
                    <h5>Sarah Adeyemi</h5>
                    <div class="role">Senior HR Specialist</div>
                    <p>12+ years in talent acquisition across banking, tech, and FMCG sectors.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="expert-card">
                    <div class="expert-avatar">TO</div>
                    <h5>Tunde Okafor</h5>
                    <div class="role">Recruitment Lead</div>
                    <p>Former agency recruiter with 5000+ CVs reviewed for top-tier firms.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="expert-card">
                    <div class="expert-avatar">CO</div>
                    <h5>Chioma Obi</h5>
                    <div class="role">Career Coach</div>
                    <p>Certified career development professional and ATS optimization expert.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="expert-card">
                    <div class="expert-avatar">KM</div>
                    <h5>Kunle Martins</h5>
                    <div class="role">Talent Director</div>
                    <p>10+ years leading recruitment for multinational corporations across Africa.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-60 bg-light py-5">
    <div class="container">
        <div class="section-header">
            <span class="badge bg-primary-transparent text-primary px-3 py-2 mb-3">Testimonials</span>
            <h2>What Our Clients Say</h2>
            <p>Hear from professionals who transformed their job search with our CV review service.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                    </div>
                    <blockquote>"The CV review was eye-opening. I had no idea my CV wasn't ATS-friendly. After implementing the changes, I got 3 interview invites in the first week!"</blockquote>
                    <div class="author">
                        <div class="avatar">AM</div>
                        <div>
                            <div class="name">Adebayo Martins</div>
                            <div class="title">Software Engineer, Access Bank</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                    </div>
                    <blockquote>"Worth every penny! The detailed line-by-line feedback helped me completely rewrite my CV. Landed my dream job within 3 weeks of the premium review."</blockquote>
                    <div class="author">
                        <div class="avatar">FO</div>
                        <div>
                            <div class="name">Folake Ogunlesi</div>
                            <div class="title">Marketing Manager, MTN Nigeria</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                        <i class="ti ti-star-filled"></i>
                    </div>
                    <blockquote>"The free basic review already gave me so much value. I upgraded to professional and the before/after comparison was incredible. Highly recommended!"</blockquote>
                    <div class="author">
                        <div class="avatar">CE</div>
                        <div>
                            <div class="name">Chidi Eze</div>
                            <div class="title">Data Analyst, PwC</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-60" id="upload-section">
    <div class="container">
        <?php if (!$isLoggedIn): ?>
        <div class="text-center py-5">
            <div class="card shadow-sm border-0 rounded-4 p-5 mx-auto" style="max-width:520px;">
                <i class="ti ti-lock" style="font-size:3rem;color:#3b82f6;margin-bottom:16px;display:block;"></i>
                <h3 class="fw-bold mb-2">Sign In Required</h3>
                <p class="text-muted mb-4">You need to be signed in to submit your CV for review. Already have an account? Sign in below.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?= base_url('login?redirect=cv-review') ?>" class="btn btn-primary btn-lg px-5">
                        <i class="ti ti-login me-2"></i>Sign In
                    </a>
                    <a href="<?= base_url('register?redirect=cv-review') ?>" class="btn btn-outline-primary btn-lg px-5">
                        <i class="ti ti-user-plus me-2"></i>Register
                    </a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="row g-5 align-items-start">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h3 class="fw-bold mb-1">Upload Your CV for Review</h3>
                        <p class="text-muted mb-0">Fill in the details and our experts will get back to you within 48 hours.</p>
                    </div>
                    <div class="card-body p-4">
                        <form id="cv-review-form" enctype="multipart/form-data">
                            <input type="hidden" name="review_id" id="review_id" value="">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Select Review Package <span class="text-danger">*</span></label>
                                <select name="plan" id="plan-select" class="form-select">
                                    <option value="basic">Basic Review (Free)</option>
                                    <option value="professional">Professional Review (&#8358;<?= number_format($planPrices['professional'], 0) ?>)</option>
                                    <option value="premium">Premium Review (&#8358;<?= number_format($planPrices['premium'], 0) ?>)</option>
                                </select>
                                <div id="payment-status" class="mt-2 small" style="display:none;"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control form-control-lg" placeholder="e.g. John Doe" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="e.g. john@example.com" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg" placeholder="e.g. +234 800 000 0000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Target Industry <span class="text-danger">*</span></label>
                                    <select name="industry" class="form-select form-select-lg">
                                        <option value="">Select Industry</option>
                                        <option value="technology">Technology</option>
                                        <option value="finance">Finance & Banking</option>
                                        <option value="healthcare">Healthcare</option>
                                        <option value="education">Education</option>
                                        <option value="marketing">Marketing & Media</option>
                                        <option value="sales">Sales & Retail</option>
                                        <option value="government">Government & Public Sector</option>
                                        <option value="consulting">Consulting</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Upload CV File <span class="text-danger">*</span></label>
                                <div class="upload-area" id="upload-area">
                                    <i class="ti ti-cloud-upload"></i>
                                    <h5>Drag & drop your CV here</h5>
                                    <p>or click to browse files</p>
                                    <div class="formats">Supported formats: PDF, DOC, DOCX (Max 5MB)</div>
                                    <input type="file" name="cv_file" id="cv_file" class="d-none" accept=".pdf,.doc,.docx" required>
                                </div>
                                <div id="file-error" class="text-danger mt-2 small" style="display: none;"></div>
                                <div class="file-preview" id="file-preview">
                                    <i class="ti ti-file-text"></i>
                                    <div>
                                        <div class="file-name" id="file-name"></div>
                                        <div class="file-size" id="file-size"></div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="remove-file">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">What specific feedback are you looking for?</label>
                                <textarea name="feedback_request" class="form-control" rows="4" placeholder="e.g. I'm applying for senior product manager roles in fintech. Please focus on my achievements section, ATS optimization, and overall impact..."></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Target Role / Job Title</label>
                                <input type="text" name="target_role" class="form-control form-control-lg" placeholder="e.g. Senior Product Manager">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3" id="btn-submit-cv">
                                <i class="ti ti-send me-2"></i>Submit for Review
                            </button>
                        </form>
                        <div id="cv-upload-msg" class="mt-4" style="display: none;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="ti ti-shield-check text-primary me-2"></i>
                            Why Choose Our Service?
                        </h5>
                        <ul class="list-unstyled">
                            <li class="d-flex gap-3 mb-3">
                                <i class="ti ti-circle-check text-success fs-5 mt-1"></i>
                                <div>
                                    <strong>Certified Reviewers</strong>
                                    <p class="text-muted small mb-0">All reviewers are HR-certified professionals with real recruitment experience.</p>
                                </div>
                            </li>
                            <li class="d-flex gap-3 mb-3">
                                <i class="ti ti-circle-check text-success fs-5 mt-1"></i>
                                <div>
                                    <strong>ATS-Optimized</strong>
                                    <p class="text-muted small mb-0">We ensure your CV passes Applicant Tracking Systems used by 95% of large employers.</p>
                                </div>
                            </li>
                            <li class="d-flex gap-3 mb-3">
                                <i class="ti ti-circle-check text-success fs-5 mt-1"></i>
                                <div>
                                    <strong>48hr Guarantee</strong>
                                    <p class="text-muted small mb-0">Receive your detailed review report within 48 hours, often sooner.</p>
                                </div>
                            </li>
                            <li class="d-flex gap-3 mb-3">
                                <i class="ti ti-circle-check text-success fs-5 mt-1"></i>
                                <div>
                                    <strong>Confidential & Secure</strong>
                                    <p class="text-muted small mb-0">Your documents are encrypted and never shared with third parties.</p>
                                </div>
                            </li>
                            <li class="d-flex gap-3">
                                <i class="ti ti-circle-check text-success fs-5 mt-1"></i>
                                <div>
                                    <strong>Satisfaction Guaranteed</strong>
                                    <p class="text-muted small mb-0">Not happy? We'll re-review your CV for free until you're satisfied.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card shadow-sm border-0 rounded-4" style="background: linear-gradient(135deg, #0f172a, #1e293b);">
                    <div class="card-body p-4 text-center">
                        <i class="ti ti-message-chat" style="font-size:2.5rem;color:#60a5fa;margin-bottom:12px;display:block;"></i>
                        <h5 class="text-white fw-bold">Have Questions?</h5>
                        <p style="color:#94a3b8;font-size:0.9rem;">Our team is ready to help you choose the right review package.</p>
                        <a href="<?= base_url('contact-us') ?>" class="btn btn-outline-light px-4">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="section-box mt-60 mb-60">
    <div class="container">
        <div class="section-header">
            <span class="badge bg-secondary-transparent text-secondary px-3 py-2 mb-3">FAQ</span>
            <h2>Frequently Asked Questions</h2>
            <p>Everything you need to know about our CV review service.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        How long does a CV review take?
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">Basic reviews are processed within 48 hours. Professional reviews are completed within 24 hours, and Premium reviews have a 12-hour turnaround. We also offer express options for urgent requests.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        What file formats do you accept?
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">We accept PDF, DOC, and DOCX file formats. Maximum file size is 5MB. For best results, we recommend uploading a PDF version of your CV.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        What's the difference between Basic and Professional review?
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">The Basic review provides an AI-powered analysis covering ATS compatibility, structure, and keywords. The Professional review includes a full human expert analysis with line-by-line feedback, rewritten sections, and personalized recommendations tailored to your target role.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Will my CV be kept confidential?
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">Absolutely. We take your privacy seriously. All uploaded documents are encrypted during transmission and storage. Your CV is only accessible to your assigned reviewer and is never shared with third parties. You can request deletion of your documents at any time.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        What if I'm not satisfied with the review?
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">Your satisfaction is our priority. If you're not happy with your review, we'll re-review your CV for free and provide additional recommendations. Our premium package includes a 1-on-1 consultation call to address all your concerns.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Do you review CVs for specific industries?
                        <i class="ti ti-chevron-down"></i>
                    </div>
                    <div class="faq-answer">Yes! Our team of experts covers a wide range of industries including Technology, Finance, Healthcare, Education, Marketing, Sales, Government, and Consulting. When you upload your CV, simply select your target industry so we can match you with the most relevant reviewer.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mb-60">
    <div class="container">
        <div class="cta-section">
            <h2>Ready to Transform Your CV?</h2>
            <p>Join thousands of professionals who landed their dream jobs after a professional CV review. Start your journey today.</p>
            <a href="<?= base_url($isLoggedIn ? 'cv-review/submit' : 'login?redirect=cv-review/submit') ?>" class="btn btn-primary btn-lg px-5 py-3">
                <i class="ti ti-upload me-2"></i>Get Your CV Reviewed
            </a>
        </div>
    </div>
</section>

<script>
function toggleFaq(element) {
    element.classList.toggle('active');
    const answer = element.nextElementSibling;
    answer.classList.toggle('show');
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('cv-review-form');
    const fileInput = document.getElementById('cv_file');
    const uploadArea = document.getElementById('upload-area');
    const fileError = document.getElementById('file-error');
    const submitBtn = document.getElementById('btn-submit-cv');
    const msgDiv = document.getElementById('cv-upload-msg');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');

    uploadArea.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect();
        }
    });

    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (!file) return;
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            fileError.textContent = 'Only PDF, DOC, and DOCX files are allowed';
            fileError.style.display = 'block';
            fileInput.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            fileError.textContent = 'File size must be less than 5MB';
            fileError.style.display = 'block';
            fileInput.value = '';
            return;
        }
        fileError.style.display = 'none';
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        filePreview.classList.add('show');
    }

    removeFileBtn.addEventListener('click', () => {
        fileInput.value = '';
        filePreview.classList.remove('show');
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        fileError.style.display = 'none';
        msgDiv.style.display = 'none';

        const plan = document.getElementById('plan-select').value;

        if (plan !== 'basic' && !document.getElementById('review_id').value) {
            fileError.textContent = 'Please complete payment for the ' + plan + ' plan first by clicking the pricing button above.';
            fileError.style.display = 'block';
            return;
        }

        const file = fileInput.files[0];
        if (!file) {
            fileError.textContent = 'Please select a CV file to upload';
            fileError.style.display = 'block';
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

        const formData = new FormData(form);

        fetch('<?= base_url('cv-review/upload') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.status === 200) {
                msgDiv.className = 'alert alert-success mt-4';
                msgDiv.innerHTML = '<i class="ti ti-check-circle me-2"></i>' + (data.message || 'CV uploaded successfully!');
                msgDiv.style.display = 'block';
                form.reset();
                filePreview.classList.remove('show');
                document.getElementById('review_id').value = '';
                document.getElementById('payment-status').style.display = 'none';
            } else {
                fileError.textContent = data.message || 'Upload failed. Please try again.';
                fileError.style.display = 'block';
            }
        })
        .catch(error => {
            fileError.textContent = 'An error occurred. Please try again.';
            fileError.style.display = 'block';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-send me-2"></i>Submit for Review';
        });
    });

    let currentReviewId = <?= isset($reviewId) && $reviewId ? (int)$reviewId : 'null' ?>;
    if (currentReviewId) {
        document.getElementById('review_id').value = currentReviewId;
        document.getElementById('payment-status').style.display = 'block';
        document.getElementById('payment-status').className = 'mt-2 small text-success';
        document.getElementById('payment-status').innerHTML = '<i class="ti ti-check-circle me-1"></i>Payment completed! Please upload your CV.';
    }

    // Plan prices from PHP (configurable via admin)
    const planPrices = <?= json_encode($planPrices) ?>;

    document.querySelectorAll('.choose-plan').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const plan = this.dataset.plan;
            const price = planPrices[plan] || 0;
            document.getElementById('plan-select').value = plan;

            if (plan === 'basic') {
                document.getElementById('upload-section').scrollIntoView({ behavior: 'smooth' });
                return;
            }

            <?php if (!$isLoggedIn): ?>
            window.location.href = '<?= base_url('login?redirect=cv-review&plan=') ?>' + plan;
            return;
            <?php else: ?>
            // Initiate Paystack payment
            const btnEl = this;
            btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Redirecting...';
            btnEl.style.pointerEvents = 'none';

            fetch('<?= base_url('cv-review/pay') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: 'plan=' + plan + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
            })
            .then(res => res.json())
            .then(data => {
                if (data.authorization_url) {
                    window.location.href = data.authorization_url;
                } else {
                    toastr.error(data.message || 'Payment initiation failed');
                    btnEl.innerHTML = '<i class="ti ti-crown me-1"></i>Pay &#8358;' + parseInt(price).toLocaleString();
                    btnEl.style.pointerEvents = 'auto';
                }
            })
            .catch(() => {
                toastr.error('Connection error. Please try again.');
                btnEl.innerHTML = '<i class="ti ti-crown me-1"></i>Pay &#8358;' + parseInt(price).toLocaleString();
                btnEl.style.pointerEvents = 'auto';
            });
            <?php endif; ?>
        });
    });
});
</script>
<?= $this->endSection() ?>
