<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
$recentSessions = $recentSessions ?? [];
$contextPreset = $contextPreset ?? [];
$questionPackLabels = [
    'general' => 'General / Behavioral',
    'engineering' => 'Engineering & Tech',
    'product' => 'Product Management',
    'sales' => 'Sales & Business Development',
    'marketing' => 'Marketing & Growth',
    'support' => 'Customer Success & Support',
    'operations' => 'Operations & Strategy',
];
?>
<div class="content">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div>
                <h4 class="fw-bold text-dark mb-1">AI Mock Interview</h4>
                <p class="text-muted mb-0">Prepare and practice for your next big career opportunity with our advanced AI interviewer.</p>
            </div>
            <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Tools
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Setup Card -->
        <div class="col-xl-7 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-gradient-primary py-4 px-4 text-white position-relative" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                    <div class="d-flex align-items-center position-relative z-index-1">
                        <div class="avatar avatar-lg bg-white-transparent rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(255,255,255,0.15);">
                            <i class="ti ti-user-check text-white fs-24"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-white">Interview Configuration</h5>
                            <p class="text-white-50 fs-13 mb-0">Configure your session parameters to start practicing.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="setup-form">
                        <input type="hidden" id="application-id" value="<?= esc((string) ($contextPreset['application_id'] ?? '0')) ?>">
                        
                        <div class="mb-4">
                            <label for="job-title" class="form-label fw-semibold text-dark">Target Job Title</label>
                            <input type="text" id="job-title" class="form-control form-control-lg rounded-3 border-2" value="<?= esc((string) ($contextPreset['job_title'] ?? '')) ?>" placeholder="e.g. Frontend Engineer, Marketing Lead" required>
                            <div class="form-text text-muted">The AI interviewer adjusts its questions specifically to match this role's standard requirements.</div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="difficulty" class="form-label fw-semibold text-dark">Difficulty Level</label>
                                <select id="difficulty" class="form-select form-select-lg rounded-3">
                                    <option value="easy">Easy (Friendly & Encouraging)</option>
                                    <option value="medium" selected>Medium (Standard Interview)</option>
                                    <option value="hard">Hard (Challenging & Rigorous)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="question-pack" class="form-label fw-semibold text-dark">Question Context Pack</label>
                                <select id="question-pack" class="form-select form-select-lg rounded-3">
                                    <?php foreach ($questionPackLabels as $value => $label): ?>
                                        <option value="<?= esc($value) ?>"><?= esc($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4 align-items-center">
                            <div class="col-md-6">
                                <label for="interview-mode" class="form-label fw-semibold text-dark">Interview Mode</label>
                                <select id="interview-mode" class="form-select form-select-lg rounded-3">
                                    <option value="chat" selected>Chat-only (Text Responses)</option>
                                    <option value="voice">Voice-enabled (Speak Your Answers)</option>
                                    <option value="video">Interactive Video (Camera Preview)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch pt-md-4">
                                    <input class="form-check-input border-secondary" type="checkbox" role="switch" id="webcam-enabled" checked>
                                    <label class="form-check-label fw-semibold text-dark ms-2" for="webcam-enabled">Show Webcam Preview</label>
                                </div>
                            </div>
                        </div>

                        <?php if (! empty($contextPreset['job_title'])): ?>
                            <div class="alert alert-info border-0 rounded-3 mb-4 p-3 d-flex align-items-start" style="background: rgba(13, 110, 253, 0.08);">
                                <i class="ti ti-info-circle text-primary fs-20 me-2 mt-1"></i>
                                <div>
                                    <strong class="text-primary d-block mb-1">Application-Aware Practice</strong>
                                    <span class="fs-13 text-secondary">This session is pre-configured with details from your recent application, allowing targeted practice for that specific job posting.</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 py-3 fw-bold" id="btn-start-session" style="box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);">
                                <i class="ti ti-player-play me-2"></i> Launch Live Interview Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- History Sidebar -->
        <div class="col-xl-5 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
                        <i class="ti ti-history text-secondary me-2"></i> Recent Sessions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div id="recent-sessions-list" class="d-grid gap-3">
                        <?php if ($recentSessions === []): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="ti ti-clipboard-list fs-36 text-muted-light d-block mb-3"></i>
                                <p class="mb-0 fs-14">No practice sessions found.</p>
                                <p class="text-muted-50 fs-12 mb-0">Launch your first session to build your interview history.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentSessions as $session): ?>
                                <div class="border border-light rounded-3 p-3 bg-light-subtle transition-all hover-translate-y">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <div class="fw-bold text-dark fs-15"><?= esc($session['job_title'] ?: 'Interview Session') ?></div>
                                            <div class="text-secondary fs-13 mt-1">
                                                <span class="badge bg-light text-dark border me-2"><?= esc(ucfirst((string) ($session['difficulty'] ?? 'medium'))) ?></span>
                                                <span class="text-muted"><?= esc($questionPackLabels[$session['question_pack'] ?? 'general'] ?? ucfirst((string) ($session['question_pack'] ?? 'General'))) ?></span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success rounded-pill px-3 py-2 fs-13"><?= esc((string) ($session['overall_score'] ?? 0)) ?>/10</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top mt-3 pt-2 text-muted fs-12">
                                        <span>STAR Score: <strong class="text-dark"><?= esc((string) ($session['star_average'] ?? 0)) ?>/10</strong></span>
                                        <span><?= esc((string) ($session['created_at'] ?? '')) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-translate-y {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-translate-y:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}
.bg-white-transparent {
    background: rgba(255, 255, 255, 0.15);
}
.text-muted-light {
    color: #cbd5e1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const setupForm = document.getElementById('setup-form');
    
    setupForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const jobTitle = document.getElementById('job-title').value.trim();
        if (!jobTitle) {
            toastr.error('Please enter a target job title.');
            return;
        }
        
        const difficulty = document.getElementById('difficulty').value;
        const questionPack = document.getElementById('question-pack').value;
        const interviewMode = document.getElementById('interview-mode').value;
        const webcamEnabled = document.getElementById('webcam-enabled').checked ? '1' : '0';
        const applicationId = document.getElementById('application-id').value;
        
        // Build url parameters
        const params = new URLSearchParams({
            job_title: jobTitle,
            difficulty: difficulty,
            question_pack: questionPack,
            interview_mode: interviewMode,
            webcam_enabled: webcamEnabled,
            application_id: applicationId
        });
        
        const targetUrl = '<?= base_url('candidate/career-tools/mock-interview/start') ?>?' + params.toString();
        
        // Open the interview session in a new distraction-free tab
        window.open(targetUrl, '_blank');
    });
});
</script>
