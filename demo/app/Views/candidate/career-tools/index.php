<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">AI Career Tools</h4>
                <h6>Accelerate your career growth with AI-powered professional development.</h6>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Mock Interview -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card custom-card h-100 shadow-sm border-0 transition-all hover-translate">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="avatar avatar-xl bg-primary-transparent">
                            <i class="ti ti-microphone fs-32 text-primary"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">AI Mock Interview</h5>
                    <p class="text-muted fs-14 mb-4">Practice your interview skills with our AI hiring manager. Get real-time feedback and challenging questions.</p>
                    <a href="<?= base_url('candidate/career-tools/mock-interview') ?>" class="btn btn-primary w-100">
                        Start Practice <i class="ti ti-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Salary Negotiation -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card custom-card h-100 shadow-sm border-0 transition-all hover-translate">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="avatar avatar-xl bg-success-transparent">
                            <i class="ti ti-coin fs-32 text-success"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">Salary Negotiation Simulator</h5>
                    <p class="text-muted fs-14 mb-4">Master the art of negotiation. Practice with our AI HR representative to secure the compensation you deserve.</p>
                    <a href="<?= base_url('candidate/career-tools/salary-negotiation') ?>" class="btn btn-success w-100 text-white">
                        Start Simulation <i class="ti ti-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Career Advice -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card custom-card h-100 shadow-sm border-0 transition-all hover-translate">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="avatar avatar-xl bg-info-transparent">
                            <i class="ti ti-sparkles fs-32 text-info"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">Personalized Career Advice</h5>
                    <p class="text-muted fs-14 mb-4">Receive tailored advice based on your profile, skills, and goals to reach the next milestone in your career.</p>
                    <a href="<?= base_url('candidate/career-tools/career-advice') ?>" class="btn btn-info w-100 text-white">
                        Get Advice <i class="ti ti-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-translate:hover {
    transform: translateY(-5px);
}
.avatar-xl {
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}
</style>
<?= $this->endSection() ?>
