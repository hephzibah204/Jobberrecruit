<?php $page_title = 'AI Career Tools'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
/* tools grid — matching the candidate-career-tools mockup */
.tools {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: clamp(12px, 1.6vw, 18px);
}
@media (max-width: 1000px) {
    .tools { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 640px) {
    .tools { grid-template-columns: 1fr; }
}

.tool {
    display: flex;
    flex-direction: column;
    padding: 22px;
    border-radius: var(--radius-lg);
    transition: var(--transition);
}
.tool:hover {
    box-shadow: var(--shadow);
    transform: translateY(-2px);
}

.tool-ic {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-bottom: 16px;
}
.tool-ic svg {
    width: 22px;
    height: 22px;
}

.tool-tag {
    align-self: flex-start;
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 20px;
    background: var(--accent-light);
    color: var(--accent-dark);
    margin-bottom: 10px;
}

.tool h2 {
    font-family: 'Sora', sans-serif;
    font-size: 1rem;
    font-weight: 800;
    color: var(--brand-deep);
    margin-bottom: 7px;
}

.tool p {
    font-size: .82rem;
    color: var(--muted);
    line-height: 1.65;
    flex: 1;
    margin-bottom: 16px;
}

.tool .btn {
    margin-top: auto;
}

/* notice */
.notice--info {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: .78rem;
    border-radius: 10px;
    padding: 12px 14px;
    border: 1px solid;
    background: var(--brand-light);
    border-color: #cfe2f2;
    color: var(--brand-dark);
}
.notice--info svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
    margin-top: 2px;
}

/* footer */
.dash-foot {
    border-top: 1px solid var(--border);
    background: #fff;
    padding: 16px clamp(16px, 3vw, 32px);
    padding-bottom: max(16px, env(safe-area-inset-bottom, 0));
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    font-size: .74rem;
    color: var(--muted);
}
.dash-foot nav {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}
.dash-foot a {
    color: var(--muted);
    font-weight: 500;
}
.dash-foot a:hover {
    color: var(--brand);
    text-decoration: none;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg> AI Career Tools</h1>
            <p>Accelerate your career growth with AI-powered professional development.</p>
        </div>
    </div>

    <!-- Tool cards -->
    <div class="tools">

        <section class="card tool" aria-label="AI mock interview">
            <span class="tool-ic" style="background:var(--brand-light);color:var(--brand)">
                <svg aria-hidden="true"><use href="#i-mic"/></svg>
            </span>
            <span class="tool-tag">Most popular</span>
            <h2>AI Mock Interview</h2>
            <p>Practice with our AI hiring manager. Get real-time feedback, challenging questions, and a confidence score after every session.</p>
            <a href="<?= base_url('candidate/career-tools/mock-interview') ?>" class="btn btn-primary btn-block">
                Start Practice <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-r"/></svg>
            </a>
        </section>

        <section class="card tool" aria-label="Salary negotiation simulator">
            <span class="tool-ic" style="background:var(--accent-light);color:var(--accent-dark)">
                <svg aria-hidden="true"><use href="#i-naira"/></svg>
            </span>
            <h2>Salary Negotiation Simulator</h2>
            <p>Master the art of negotiation. Practice with our AI HR representative and learn to secure the compensation you deserve.</p>
            <a href="<?= base_url('candidate/career-tools/salary-negotiation') ?>" class="btn btn-primary btn-block">
                Start Simulation <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-r"/></svg>
            </a>
        </section>

        <section class="card tool" aria-label="Personalized career advice">
            <span class="tool-ic" style="background:var(--brand-light);color:var(--brand-dark)">
                <svg aria-hidden="true"><use href="#i-bulb"/></svg>
            </span>
            <h2>Personalized Career Advice</h2>
            <p>Receive tailored advice based on your profile, skills, and goals — a clear plan to reach the next milestone in your career.</p>
            <a href="<?= base_url('candidate/career-tools/career-advice') ?>" class="btn btn-primary btn-block">
                Get Advice <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-r"/></svg>
            </a>
        </section>

    </div>

    <!-- Tip: run a mock interview before your next real one -->
    <div class="notice notice--info">
        <svg aria-hidden="true"><use href="#i-bulb"/></svg>
        <span>Tip: run a mock interview before your next real one — candidates who practise at least twice report noticeably higher confidence.</span>
    </div>

</div>

<!-- Footer -->
<footer class="dash-foot">
    <span>&copy; 2026 JobberRecruit &middot; Jobber Recruit Ltd</span>
    <nav aria-label="Footer links">
        <a href="<?= base_url('help') ?>">Help Centre</a>
        <a href="<?= base_url('privacy-policy') ?>">Privacy</a>
        <a href="<?= base_url('terms-of-service') ?>">Terms</a>
        <a href="<?= base_url('contact') ?>">Contact</a>
    </nav>
</footer>
<?= $this->endSection() ?>