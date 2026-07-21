<?php
$dir = 'demo/app/Views/home/';

$hero = <<<'EOD'
<style>
    .hero-redesign {
        background: var(--primary-color);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .badge-verified {
        background: rgba(255,255,255,0.1);
        color: var(--secondary-color);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(255,255,255,0.15);
    }
    .hero-redesign h1 {
        color: #fff;
        font-size: 54px;
        font-weight: 700 !important;
        line-height: 1.1;
        margin-bottom: 20px;
    }
    .hero-redesign h1 span {
        color: var(--secondary-color);
    }
    .hero-subtitle {
        color: #cbd5e1;
        font-size: 18px;
        max-width: 600px;
        margin-bottom: 40px;
        line-height: 1.6;
    }
    .hero-search-box {
        background: #fff;
        padding: 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        max-width: 900px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        margin-bottom: 30px;
    }
    .hero-search-box .form-group {
        flex: 1;
        position: relative;
        border-right: 1px solid #e2e8f0;
        padding: 0 15px;
    }
    .hero-search-box .form-group:last-of-type { border-right: none; }
    .hero-search-box .form-control {
        border: none;
        box-shadow: none;
        padding-left: 30px;
        font-size: 15px;
    }
    .hero-search-box .search-icon {
        position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;
    }
    .hero-search-box .btn-search {
        background: var(--secondary-color);
        color: #000;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 8px;
        border: none;
    }
    .hero-ticker {
        background: var(--secondary-color);
        padding: 10px 0;
        font-size: 14px;
        font-weight: 500;
    }
    .hero-ticker .badge-new {
        background: #000;
        color: var(--secondary-color);
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        margin-right: 15px;
    }
    .user-type-toggle {
        background: #fff;
        border-radius: 30px;
        padding: 4px;
        display: inline-flex;
        border: 1px solid rgba(255,255,255,0.2);
    }
</style>

<section class="hero-redesign">
    <div class="container relative z-index-1">
        <div class="d-flex align-items-center mb-4">
            <div class="badge-verified me-3 mb-0">
                <i class="bi bi-shield-check me-2"></i> VERIFIED JOB PLATFORM - NIGERIA
            </div>
            <div class="user-type-toggle">
                <a href="#" class="btn btn-primary rounded-pill px-4 btn-sm fw-bold border-0"><i class="bi bi-search me-1"></i> Job seeker</a>
                <a href="#" class="btn btn-transparent rounded-pill px-4 btn-sm text-dark fw-bold border-0"><i class="bi bi-building me-1"></i> Employer</a>
            </div>
        </div>
        
        <h1>Find jobs, learn skills<br>& <span>grow your career</span></h1>
        <p class="hero-subtitle">Search verified jobs, build AI-powered resumes, practise interviews, earn professional certificates, and connect with top employers across Nigeria.</p>
        
        <form class="hero-search-box" action="<?= site_url('jobs/search') ?>" method="GET">
            <div class="form-group">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="q" class="form-control" placeholder="e.g. Software Eng">
            </div>
            <div class="form-group">
                <i class="bi bi-geo-alt search-icon"></i>
                <select name="state_id" class="form-control">
                    <option value="">All locations</option>
                </select>
            </div>
            <div class="form-group">
                <i class="bi bi-briefcase search-icon"></i>
                <select name="industry_id" class="form-control">
                    <option value="">All categories</option>
                </select>
            </div>
            <button type="submit" class="btn btn-search"><i class="bi bi-search me-2"></i> Search jobs</button>
        </form>
        
        <div class="d-flex align-items-center mt-4">
            <span class="text-white-50 me-3" style="font-size: 13px; font-weight: 600; letter-spacing: 1px;">TRENDING:</span>
            <div class="d-flex gap-2 flex-wrap">
                <a href="#" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.1); border-radius: 20px;">Remote</a>
                <a href="#" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.1); border-radius: 20px;">Software Developer</a>
                <a href="#" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.1); border-radius: 20px;">Marketing</a>
                <a href="#" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.1); border-radius: 20px;">Data Analyst</a>
                <a href="#" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.1); border-radius: 20px;">Finance</a>
                <a href="#" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.1); border-radius: 20px;">Oil & Gas</a>
            </div>
        </div>
    </div>
</section>
<div class="hero-ticker border-top border-bottom">
    <div class="container d-flex align-items-center overflow-hidden whitespace-nowrap">
        <span class="badge-new"><i class="bi bi-lightning-fill text-warning"></i> JUST POSTED</span>
        <marquee scrollamount="5" scrolldelay="50" style="color: #000; font-weight: 500;">
            <span class="me-5"><span class="text-muted fw-bold me-2" style="font-size:10px;">NEW</span> Frontend Developer (React) &bull; <i class="bi bi-geo-alt"></i> Adamawa</span>
            <span class="me-5"><span class="text-muted fw-bold me-2" style="font-size:10px;">NEW</span> Mobile App Developer (Flutter) &bull; <i class="bi bi-geo-alt"></i> Abia</span>
            <span class="me-5"><span class="text-muted fw-bold me-2" style="font-size:10px;">NEW</span> Data Analyst &bull; <i class="bi bi-geo-alt"></i> Adamawa</span>
            <span class="me-5"><span class="text-muted fw-bold me-2" style="font-size:10px;">NEW</span> Rider Operations Associate &bull; <i class="bi bi-geo-alt"></i> Abia</span>
        </marquee>
    </div>
</div>
EOD;
file_put_contents($dir . 'hero.php', $hero);


$platform = <<<'EOD'
<style>
    .ai-features-section {
        padding: 80px 0;
        background: var(--bg-light);
    }
    .ai-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        height: 100%;
        border: 1px solid #e2e8f0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .ai-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }
    .ai-icon-box {
        width: 50px;
        height: 50px;
        background: var(--info-light);
        color: var(--primary-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }
    .ai-card h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--text-dark);
    }
    .ai-card p {
        color: var(--text-muted);
        font-size: 15px;
        margin-bottom: 25px;
        line-height: 1.6;
    }
    .badge-ai {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }
</style>

<section class="ai-features-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Supercharge your job search with AI</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Leverage our cutting-edge AI tools to stand out to employers and land your dream job faster.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="ai-card">
                    <span class="badge-ai"><i class="bi bi-robot me-1"></i> AI-powered</span>
                    <div class="ai-icon-box">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h3>AI Resume Builder</h3>
                    <p>Create a powerful, ATS-optimised resume in minutes. Tailored to specific job descriptions and Nigerian employers.</p>
                    <a href="<?= site_url('ai-resume') ?>" class="btn btn-outline-primary w-100 fw-bold">Build my resume &rarr;</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ai-card">
                    <span class="badge-ai"><i class="bi bi-robot me-1"></i> AI-powered</span>
                    <div class="ai-icon-box">
                        <i class="bi bi-mic"></i>
                    </div>
                    <h3>AI Mock Interview</h3>
                    <p>Practise with a realistic AI interviewer. Get role-specific questions and instant feedback on your answers.</p>
                    <a href="<?= site_url('mock-interview') ?>" class="btn btn-outline-primary w-100 fw-bold">Start practising &rarr;</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ai-card">
                    <span class="badge-ai"><i class="bi bi-robot me-1"></i> AI-powered</span>
                    <div class="ai-icon-box">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <h3>Personalised Advice</h3>
                    <p>Guidance tailored to your experience, industry, and goals — from salary negotiation to promotion strategies.</p>
                    <a href="<?= site_url('career-advice') ?>" class="btn btn-outline-primary w-100 fw-bold">Get advice &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>
EOD;
file_put_contents($dir . 'platform_features.php', $platform);


$recent = <<<'EOD'
<style>
    .recent-jobs-section {
        padding: 80px 0;
        background: #fff;
    }
    .rj-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
    }
    .rj-header h2 {
        font-size: 32px;
        font-weight: 700 !important;
        margin-bottom: 10px;
    }
    .rj-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        transition: 0.3s;
        height: 100%;
        background: #fff;
    }
    .rj-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .rj-card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
    }
    .rj-card-meta {
        color: var(--text-muted);
        font-size: 14px;
        margin-bottom: 20px;
    }
    .rj-card-meta i {
        margin-right: 5px;
    }
    .rj-salary {
        color: var(--secondary-color);
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 20px;
    }
</style>

<section class="recent-jobs-section">
    <div class="container">
        <div class="rj-header">
            <div>
                <span class="badge bg-light text-dark mb-2 border px-3 py-2 rounded-pill fw-bold" style="font-size:11px; letter-spacing:1px;">
                    <i class="bi bi-star-fill text-warning me-1"></i> HAND-PICKED OPPORTUNITIES
                </span>
                <h2 class="text-primary">Recent jobs in Nigeria</h2>
                <p class="text-muted mb-0">Top companies are actively hiring. Don't miss your window.</p>
            </div>
            <div class="d-none d-md-flex align-items-center">
                <span class="text-muted me-3">Showing 6 of 4,200+ live jobs</span>
                <a href="<?= site_url('jobs') ?>" class="btn btn-outline-dark rounded-pill fw-bold px-4">All featured jobs &rarr;</a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Job 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="rj-card d-flex flex-column">
                    <div class="d-flex justify-content-end mb-3">
                        <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 8px;"></div>
                    </div>
                    <div class="rj-card-title">Senior Non-Key Expert - ICT / Software Specialist <i class="bi bi-check-circle-fill text-primary fs-6 ms-1"></i></div>
                    <div class="rj-card-meta d-flex gap-3 text-muted">
                        <span><i class="bi bi-geo-alt"></i> Abia</span>
                        <span><i class="bi bi-briefcase"></i> Full-time</span>
                        <span><i class="bi bi-clock"></i> 1 month ago</span>
                    </div>
                    <div class="rj-salary">₦750,000 - ₦1,200,000 <span class="text-muted fw-normal" style="font-size:13px;">/ monthly</span></div>
                    <div class="mt-auto d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1 fw-bold py-2">Quick apply</button>
                        <button class="btn btn-outline-secondary px-3"><i class="bi bi-bookmark"></i> Save</button>
                    </div>
                </div>
            </div>
            
            <!-- Job 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="rj-card d-flex flex-column">
                    <div class="d-flex justify-content-end mb-3">
                        <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 8px;"></div>
                    </div>
                    <div class="rj-card-title">Frontend Developer (React) <i class="bi bi-check-circle-fill text-primary fs-6 ms-1"></i></div>
                    <div class="rj-card-meta d-flex gap-3 text-muted">
                        <span><i class="bi bi-geo-alt"></i> Adamawa</span>
                        <span><i class="bi bi-briefcase"></i> Part-time</span>
                        <span><i class="bi bi-clock"></i> 1 month ago</span>
                    </div>
                    <div class="rj-salary">₦300,000 - ₦500,000 <span class="text-muted fw-normal" style="font-size:13px;">/ monthly</span></div>
                    <div class="mt-auto d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1 fw-bold py-2">Quick apply</button>
                        <button class="btn btn-outline-secondary px-3"><i class="bi bi-bookmark"></i> Save</button>
                    </div>
                </div>
            </div>

            <!-- Job 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="rj-card d-flex flex-column">
                    <div class="d-flex justify-content-end mb-3">
                        <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 8px;"></div>
                    </div>
                    <div class="rj-card-title">Mobile App Developer (Flutter) <i class="bi bi-check-circle-fill text-primary fs-6 ms-1"></i></div>
                    <div class="rj-card-meta d-flex gap-3 text-muted">
                        <span><i class="bi bi-geo-alt"></i> Abia</span>
                        <span><i class="bi bi-briefcase"></i> Contract</span>
                        <span><i class="bi bi-clock"></i> 1 month ago</span>
                    </div>
                    <div class="rj-salary">₦400,000 <span class="text-muted fw-normal" style="font-size:13px;">/ monthly</span></div>
                    <div class="mt-auto d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1 fw-bold py-2">Quick apply</button>
                        <button class="btn btn-outline-secondary px-3"><i class="bi bi-bookmark"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
EOD;
file_put_contents($dir . 'recent_jobs.php', $recent);

$categories = <<<'EOD'
<style>
    .cat-section { padding: 60px 0; background: #fff; }
    .cat-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        background: #fff;
        transition: 0.3s;
        display: block;
        text-decoration: none;
    }
    .cat-card:hover { border-color: var(--primary-color); transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .cat-icon {
        width: 60px; height: 60px;
        background: var(--info-light);
        color: var(--primary-color);
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 24px; margin-bottom: 15px;
    }
    .cat-card h5 { color: var(--text-dark); font-size: 16px; font-weight: 700; margin-bottom: 8px; }
    .cat-card p { color: var(--text-muted); font-size: 13px; margin: 0; }
</style>
<section class="cat-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-light text-dark mb-2 border px-3 py-2 rounded-pill fw-bold" style="font-size:11px; letter-spacing:1px;">
                    <i class="bi bi-envelope text-muted me-1"></i> FIND YOUR FIELD
                </span>
                <h2 class="text-primary">Browse jobs by category</h2>
                <p class="text-muted mb-0" style="max-width: 500px;">Explore opportunities across every industry in Nigeria — from technology to oil & gas, healthcare, and beyond.</p>
            </div>
            <a href="<?= site_url('jobs') ?>" class="btn btn-outline-dark rounded-pill fw-bold px-4">All categories &rarr;</a>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-bank"></i></div><h5>Finance & Banking</h5><p>1 open roles</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-heart-pulse"></i></div><h5>Healthcare & Medical</h5><p>1 open roles</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-book"></i></div><h5>Education & Training</h5><p>1 open roles</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-laptop"></i></div><h5>Information Technology</h5><p>0 open roles</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-gear"></i></div><h5>Manufacturing & Engineering</h5><p>0 open roles</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-briefcase"></i></div><h5>Retail & E-Commerce</h5><p>0 open roles</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-briefcase"></i></div><h5>Hospitality & Tourism</h5><p>0 open roles</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-briefcase"></i></div><h5>Construction & Real Estate</h5><p>0 open roles</p></a></div>
        </div>
    </div>
</section>
EOD;
file_put_contents($dir . 'categories.php', $categories);

$locations = <<<'EOD'
<section class="cat-section">
    <div class="container text-center mb-5">
        <span class="badge bg-light text-dark mb-2 border px-3 py-2 rounded-pill fw-bold" style="font-size:11px; letter-spacing:1px;">
            <i class="bi bi-geo-alt text-muted me-1"></i> ACROSS NIGERIA
        </span>
        <h2 class="text-primary">Browse jobs by location</h2>
        <p class="text-muted mx-auto" style="max-width: 500px;">Jobs in top cities and states across Nigeria — or work remotely from anywhere.</p>
    </div>
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-building"></i></div><h5>Lagos</h5><p>4,423 jobs</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-building"></i></div><h5>Abuja (FCT)</h5><p>4,796 jobs</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-droplet"></i></div><h5>Port Harcourt</h5><p>843 jobs</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-shop"></i></div><h5>Kano</h5><p>1,413 jobs</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-building"></i></div><h5>Ibadan</h5><p>3,229 jobs</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-building"></i></div><h5>Enugu</h5><p>3,809 jobs</p></a></div>
            <div class="col-md-3 col-sm-6"><a href="#" class="cat-card"><div class="cat-icon"><i class="bi bi-globe"></i></div><h5>Remote</h5><p>439 jobs</p></a></div>
            <div class="col-md-3 col-sm-6">
                <a href="<?= site_url('jobs') ?>" class="cat-card text-white" style="background: var(--primary-color); border-color: var(--primary-color);">
                    <div class="cat-icon text-white" style="background: rgba(255,255,255,0.1);"><i class="bi bi-geo-alt"></i></div>
                    <h5 class="text-white">All 36 states</h5><p class="text-white-50">View all locations</p>
                </a>
            </div>
        </div>
    </div>
</section>
EOD;
file_put_contents($dir . 'locations.php', $locations);

echo "Restored perfectly!";
