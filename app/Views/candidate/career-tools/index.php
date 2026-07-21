<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg> AI Career Tools</h1>
            <p>Accelerate your career growth with AI-powered professional development.</p>
        </div>
    </div>

    <div class="tools">
        <!-- Mock Interview -->
        <section class="card tool" aria-label="AI mock interview">
            <span class="tool-ic" style="background:var(--brand-light);color:var(--brand);"><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-mic"/></svg></span>
            <span class="tool-tag">Most popular</span>
            <h2>AI Mock Interview</h2>
            <p>Practice with our AI hiring manager. Get real-time feedback, challenging questions, and a confidence score after every session.</p>
            <a href="<?= base_url('candidate/career-tools/mock-interview') ?>" class="btn btn-primary btn-block">
                Start Practice <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-r"/></svg>
            </a>
        </section>

        <!-- Salary Negotiation -->
        <section class="card tool" aria-label="Salary negotiation simulator">
            <span class="tool-ic" style="background:var(--accent-light);color:var(--accent-dark);"><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg></span>
            <h2>Salary Negotiation Simulator</h2>
            <p>Master the art of negotiation. Practice with our AI HR representative and learn to secure the compensation you deserve.</p>
            <a href="<?= base_url('candidate/career-tools/salary-negotiation') ?>" class="btn btn-primary btn-block">
                Start Simulation <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-r"/></svg>
            </a>
        </section>

        <!-- Career Advice -->
        <section class="card tool" aria-label="Personalized career advice">
            <span class="tool-ic" style="background:var(--brand-light);color:var(--brand-dark);"><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg></span>
            <h2>Personalized Career Advice</h2>
            <p>Receive tailored advice based on your profile, skills, and goals — a clear plan to reach the next milestone in your career.</p>
            <a href="<?= base_url('candidate/career-tools/career-advice') ?>" class="btn btn-primary btn-block">
                Get Advice <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-r"/></svg>
            </a>
        </section>
    </div>

    <div class="notice notice--info" style="margin-top: 20px;">
        <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg>
        <span>Tip: run a mock interview before your next real one — candidates who practice at least twice report noticeably higher confidence.</span>
    </div>
</div>
<?= $this->endSection() ?>

