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
                <h4 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">AI Mock Interview Control Center</h4>
                <p class="text-muted mb-0">Prepare and practice for your next big career opportunity with our advanced AI interviewer.</p>
            </div>
            <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="ti ti-arrow-left me-1"></i> Back to Tools
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Setup Card -->
        <div class="col-xl-7 mb-4">
            <div class="card premium-glow-card h-100 border-0 shadow-sm">
                <div class="card-header py-4 px-4 text-white position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                    <div class="d-flex align-items-center position-relative z-index-1">
                        <div class="avatar avatar-lg bg-white-transparent rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(255,255,255,0.15);">
                            <i class="ti ti-adjustments-horizontal text-white fs-24 animate-pulse"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-white" style="font-family: 'Outfit', sans-serif;">Session Configuration</h5>
                            <p class="text-white-50 fs-13 mb-0">Customize your dynamic practice parameters to launch the simulator.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="setup-form">
                        <input type="hidden" id="application-id" value="<?= esc((string) ($contextPreset['application_id'] ?? '0')) ?>">
                        
                        <div class="mb-4">
                            <label for="job-title" class="form-label fw-semibold text-dark small uppercase tracking-wider">Target Job Title</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-2 border-end-0 rounded-start-3"><i class="ti ti-briefcase text-muted"></i></span>
                                <input type="text" id="job-title" class="form-control bg-light border-2 border-start-0 rounded-end-3 fs-16" value="<?= esc((string) ($contextPreset['job_title'] ?? '')) ?>" placeholder="e.g. Senior Frontend Architect, Product Operations Lead" required style="transition: all 0.3s;">
                            </div>
                            <div class="form-text text-muted fs-12 mt-2">ResumeAI tailors behavioral & technical questions to mirror this target role exactly.</div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="difficulty" class="form-label fw-semibold text-dark small uppercase tracking-wider">Difficulty Level</label>
                                <select id="difficulty" class="form-select form-select-lg bg-light border-2 rounded-3 fs-15">
                                    <option value="easy">Easy (Friendly & Encouraging)</option>
                                    <option value="medium" selected>Medium (Standard Interview)</option>
                                    <option value="hard">Hard (Challenging & Rigorous)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="question-pack" class="form-label fw-semibold text-dark small uppercase tracking-wider">Question Context Pack</label>
                                <select id="question-pack" class="form-select form-select-lg bg-light border-2 rounded-3 fs-15">
                                    <?php foreach ($questionPackLabels as $value => $label): ?>
                                        <option value="<?= esc($value) ?>"><?= esc($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4 align-items-center">
                            <div class="col-md-6">
                                <label for="interview-mode" class="form-label fw-semibold text-dark small uppercase tracking-wider">Interview Mode</label>
                                <select id="interview-mode" class="form-select form-select-lg bg-light border-2 rounded-3 fs-15">
                                    <option value="chat" selected>Chat-only (Text Responses)</option>
                                    <option value="voice">Voice-enabled (Speak Your Answers)</option>
                                    <option value="video">Interactive Video (Camera Preview)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch pt-md-4">
                                    <input class="form-check-input border-secondary" type="checkbox" role="switch" id="webcam-enabled" checked style="cursor: pointer; width: 42px; height: 21px;">
                                    <label class="form-check-label fw-semibold text-dark ms-2 small uppercase tracking-wider" for="webcam-enabled" style="cursor: pointer; position: relative; top: 2px;">Webcam Preview</label>
                                </div>
                            </div>
                        </div>

                        <?php if (! empty($contextPreset['job_title'])): ?>
                            <div class="alert alert-info border-0 rounded-3 mb-4 p-3 d-flex align-items-start" style="background: rgba(79, 70, 229, 0.07); border-left: 4px solid #4f46e5 !important;">
                                <i class="ti ti-info-circle text-primary fs-20 me-2 mt-1"></i>
                                <div>
                                    <strong class="text-primary d-block mb-1 fs-14">Application-Aware Intelligence</strong>
                                    <span class="fs-13 text-secondary">This practice session is synchronized with details from your recent job application to provide pinpoint preparation.</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 py-3 fw-bold btn-launch-pulse" id="btn-start-session">
                                <i class="ti ti-player-play me-2"></i> Launch Live Simulator Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- History Sidebar -->
        <div class="col-xl-5 mb-4">
            <div class="card premium-glow-card h-100 border-0 shadow-sm">
                <div class="card-header bg-white py-4 px-4 border-bottom border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center" style="font-family: 'Outfit', sans-serif;">
                        <i class="ti ti-history text-indigo me-2"></i> Recent Sessions
                    </h5>
                    <span class="badge bg-indigo bg-opacity-15 text-indigo rounded-pill px-3 py-1.5 fs-11 fw-bold"><?= count($recentSessions) ?> Completed</span>
                </div>
                <div class="card-body p-4">
                    <div id="recent-sessions-list" class="d-grid gap-3" style="max-height: 480px; overflow-y: auto; padding-right: 4px;">
                        <?php if ($recentSessions === []): ?>
                            <div class="text-center py-5 text-muted">
                                <div class="avatar avatar-xxl bg-light text-muted-light rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: rgba(99, 102, 241, 0.05) !important;">
                                    <i class="ti ti-clipboard-list fs-32 text-indigo"></i>
                                </div>
                                <p class="mb-0 fs-14 fw-semibold text-dark">No simulator history</p>
                                <p class="text-muted-50 fs-12 mb-0">Launch your first session to build your interactive history.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentSessions as $session): ?>
                                <div class="session-history-item rounded-3 p-3 transition-all">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <div class="fw-bold text-dark fs-15"><?= esc($session['job_title'] ?: 'Interview Session') ?></div>
                                            <div class="text-secondary fs-13 mt-1.5 d-flex align-items-center gap-1.5 flex-wrap">
                                                <span class="badge bg-light text-dark border px-2.5 py-1" style="font-size: 10px;"><?= esc(ucfirst((string) ($session['difficulty'] ?? 'medium'))) ?></span>
                                                <span class="text-muted fs-12"><i class="ti ti-bookmark me-1"></i><?= esc($questionPackLabels[$session['question_pack'] ?? 'general'] ?? ucfirst((string) ($session['question_pack'] ?? 'General'))) ?></span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="badge bg-success bg-opacity-15 text-success rounded-pill px-3 py-2 fs-13 fw-bold shadow-sm" style="border: 1px solid rgba(25, 135, 84, 0.15);"><i class="ti ti-award me-1"></i><?= esc((string) ($session['overall_score'] ?? 0)) ?>/10</div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-10 mt-3 pt-2 text-muted fs-12">
                                        <span>STAR Score: <strong class="text-indigo"><?= esc((string) ($session['star_average'] ?? 0)) ?>/10</strong></span>
                                        <span><i class="ti ti-calendar me-1"></i><?= esc((string) ($session['created_at'] ?? '')) ?></span>
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
.premium-glow-card {
    background: #ffffff;
    border: 1px solid rgba(99, 102, 241, 0.1) !important;
    box-shadow: 0 8px 32px rgba(99, 102, 241, 0.04), 0 1px 6px rgba(0, 0, 0, 0.02) !important;
    border-radius: 22px !important;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}
.premium-glow-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 48px rgba(99, 102, 241, 0.08), 0 2px 8px rgba(0, 0, 0, 0.02) !important;
}
.premium-glow-card .card-header {
    position: relative;
    overflow: hidden;
}
.premium-glow-card .card-header::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.04);
    border-radius: 50%;
    pointer-events: none;
}
.text-indigo { color: #4f46e5 !important; }
.bg-indigo { background-color: #4f46e5 !important; }
.bg-indigo-soft { background-color: rgba(79, 70, 229, 0.06) !important; }

.animate-pulse {
    animation: pulse-ring 2.5s infinite ease-in-out;
}
@keyframes pulse-ring {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.7; }
}

.btn-launch-pulse {
    background: linear-gradient(135deg, #4f46e5, #3b82f6) !important;
    border: none !important;
    box-shadow: 0 4px 20px rgba(79, 70, 229, 0.3) !important;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.btn-launch-pulse::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.08) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.6s;
}
.btn-launch-pulse:hover::before { transform: translateX(100%); }
.btn-launch-pulse:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(79, 70, 229, 0.4) !important;
}
.btn-launch-pulse:active { transform: translateY(0); }

.form-control-lg, .form-select-lg {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}
.form-control-lg:focus, .form-select-lg:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
}
.form-switch .form-check-input:checked {
    background-color: #4f46e5 !important;
    border-color: #4f46e5 !important;
}

.session-history-item {
    border: 1px solid rgba(0, 0, 0, 0.04);
    background: #fafbfe;
    border-radius: 14px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.session-history-item:hover {
    transform: translateY(-2px);
    border-color: rgba(99, 102, 241, 0.15);
    background: #ffffff;
    box-shadow: 0 8px 24px rgba(99, 102, 241, 0.06);
}

#recent-sessions-list::-webkit-scrollbar { width: 4px; }
#recent-sessions-list::-webkit-scrollbar-track { background: transparent; }
#recent-sessions-list::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.12); border-radius: 4px; }
#recent-sessions-list::-webkit-scrollbar-thumb:hover { background: rgba(99, 102, 241, 0.25); }

@media (max-width: 1199.98px) {
    .page-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }
    .page-header .btn { align-self: flex-start; }
}
@media (max-width: 767.98px) {
    .premium-glow-card .card-header { padding: 1.5rem !important; }
    .premium-glow-card .card-body { padding: 1.5rem !important; }
    .form-select-lg, .form-control-lg { font-size: 0.95rem; padding: 0.7rem; }
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
