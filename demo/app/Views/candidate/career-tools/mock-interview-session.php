<?= $this->extend('layouts/minimal') ?>

<?= $this->section('styles') ?>
<style>
    body {
        background-color: #0f172a;
        color: #f8fafc;
        font-family: 'Outfit', sans-serif;
        overflow-x: hidden;
    }
    
    .session-header {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 100;
    }

    .glass-card {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
    }

    .chat-area {
        height: calc(100vh - 280px);
        overflow-y: auto;
        padding-right: 8px;
    }

    .chat-area::-webkit-scrollbar {
        width: 6px;
    }
    .chat-area::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
    }
    .chat-area::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .bubble {
        max-width: 80%;
        border-radius: 16px;
        padding: 14px 18px;
        font-size: 15px;
        line-height: 1.6;
    }

    .bubble-model {
        background: rgba(51, 65, 85, 0.5);
        color: #f1f5f9;
        border-bottom-left-radius: 4px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .bubble-user {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #ffffff;
        border-bottom-right-radius: 4px;
    }

    .voice-indicator-active {
        animation: pulse 1.5s infinite ease-in-out;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
    }

    .star-pill {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        padding: 10px;
        transition: all 0.3s ease;
    }

    .star-pill.active {
        background: rgba(59, 130, 246, 0.1);
        border-color: rgba(59, 130, 246, 0.3);
    }

    /* Video area styles */
    .video-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    @media (min-width: 992px) {
        .video-grid.two-cols {
            grid-template-columns: 1fr 1fr;
        }
    }

    .video-box {
        position: relative;
        background: #020617;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 16/9;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .video-label {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 4px;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .waveform-bar {
        display: inline-block;
        width: 3px;
        height: 15px;
        background-color: #3b82f6;
        margin: 0 1px;
        animation: wave 1.2s infinite ease-in-out;
    }

    @keyframes wave {
        0%, 100% { height: 5px; }
        50% { height: 25px; }
    }

    /* Overlay styles */
    .start-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: #090d16;
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 767.98px) {
        .chat-area {
            height: calc(100vh - 320px) !important;
        }
        .bubble {
            max-width: 90% !important;
            font-size: 14px !important;
            padding: 12px 14px !important;
        }
        .session-header {
            padding: 0.75rem 1rem !important;
        }
        .session-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.75rem;
        }
        .start-overlay .glass-card {
            width: 90% !important;
            margin: 0 1rem;
        }
        .avatar-xxl {
            width: 60px !important;
            height: 60px !important;
            font-size: 28px !important;
        }
    }

    @media (max-width: 575.98px) {
        .chat-area {
            height: calc(100vh - 350px) !important;
        }
        .session-header h5 {
            font-size: 1rem;
        }
        .btn-lg {
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$contextPreset = $contextPreset ?? [];
?>

<!-- Startup Ready Overlay -->
<div class="start-overlay" id="ready-overlay">
    <div class="text-center max-w-md p-5 glass-card" style="width: 480px;">
        <div class="avatar avatar-xxl bg-primary-transparent mb-4 mx-auto" style="width: 80px; height: 80px; font-size: 36px; display: flex; align-items: center; justify-content: center; background: rgba(59, 130, 246, 0.15); border-radius: 50%;">
            <i class="ti ti-microphone text-primary"></i>
        </div>
        <h3 class="fw-bold mb-2">Live Interview Session</h3>
        <p class="text-muted mb-4 fs-14">
            Practice mode is set for <strong class="text-white"><?= esc((string) ($contextPreset['job_title'] ?? 'Role')) ?></strong>.<br>
            Make sure your microphone/camera are ready.
        </p>
        <div class="d-grid">
            <button type="button" class="btn btn-primary btn-lg py-3 fw-bold rounded-3" id="btn-begin">
                <i class="ti ti-player-play me-2"></i> Enter Interview Room
            </button>
        </div>
    </div>
</div>

<!-- Main UI -->
<div class="d-flex flex-column" style="min-height: 100vh;">
    <!-- Top Header -->
    <header class="session-header py-3 px-4 sticky-top">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-danger rounded-pill px-3 py-2 d-flex align-items-center gap-1.5" style="font-size: 13px;">
                        <span class="spinner-grow spinner-grow-sm text-white" role="status" style="width: 8px; height: 8px;"></span> Live Session
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-white"><?= esc((string) ($contextPreset['job_title'] ?? 'Mock Interview')) ?></h5>
                        <small class="text-muted-light">Difficulty: <strong class="text-white"><?= esc(ucfirst((string) ($contextPreset['difficulty'] ?? 'medium'))) ?></strong></small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4">
                    <div class="text-center bg-dark px-3 py-1.5 rounded-3 border border-secondary border-opacity-10">
                        <small class="text-muted d-block fs-11 uppercase">Elapsed Time</small>
                        <span class="fw-bold text-white fs-15" id="session-duration">00:00</span>
                    </div>
                    <div class="text-center bg-dark px-3 py-1.5 rounded-3 border border-secondary border-opacity-10">
                        <small class="text-muted d-block fs-11 uppercase">Questions</small>
                        <span class="fw-bold text-white fs-15" id="question-count">0</span>
                    </div>
                    <div class="text-center bg-dark px-3 py-1.5 rounded-3 border border-secondary border-opacity-10">
                        <small class="text-muted d-block fs-11 uppercase">Answers</small>
                        <span class="fw-bold text-white fs-15" id="answer-count">0</span>
                    </div>
                    <button class="btn btn-outline-danger btn-md fw-bold px-3 py-2" onclick="window.close();">
                        <i class="ti ti-logout me-1"></i> Exit Session
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Workspace -->
    <main class="flex-grow-1 p-4 d-flex align-items-stretch">
        <div class="container-fluid">
            <div class="row h-100">
                <!-- Left Main Panel (Chat & Videos) -->
                <div class="col-lg-8 d-flex flex-column mb-4 mb-lg-0">
                    <!-- Video Feeds Section (Shows only if enabled) -->
                    <div class="video-grid <?= ($contextPreset['interview_mode'] ?? 'chat') === 'video' ? 'two-cols' : '' ?>" id="video-area">
                        <!-- AI Interviewer Visualizer Card -->
                        <div class="video-box" id="ai-interviewer-card">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <div class="avatar avatar-xxl bg-primary-transparent mb-3" style="width: 70px; height: 70px; background: rgba(59, 130, 246, 0.1); margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                        <i class="ti ti-user-check text-primary fs-32"></i>
                                    </div>
                                    <h6 class="text-muted-light mb-2">Hiring Manager AI</h6>
                                    <div class="d-flex align-items-center justify-content-center" id="voice-waves" style="height: 30px; display: none !important;">
                                        <div class="waveform-bar" style="animation-delay: 0.1s;"></div>
                                        <div class="waveform-bar" style="animation-delay: 0.3s;"></div>
                                        <div class="waveform-bar" style="animation-delay: 0.5s;"></div>
                                        <div class="waveform-bar" style="animation-delay: 0.2s;"></div>
                                        <div class="waveform-bar" style="animation-delay: 0.4s;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="video-label">
                                <i class="ti ti-user-check text-primary"></i> Interviewer (AI)
                            </div>
                        </div>

                        <!-- User Camera Box -->
                        <div class="video-box <?= ($contextPreset['webcam_enabled'] ?? false) ? '' : 'd-none' ?>" id="user-camera-card">
                            <video id="webcam-preview" class="w-100 h-100 object-fit-cover" autoplay playsinline muted></video>
                            <div id="webcam-placeholder" class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                <i class="ti ti-video-off fs-32 mb-2 text-danger"></i>
                                <span>Camera access denied</span>
                            </div>
                            <div class="video-label">
                                <i class="ti ti-video text-success"></i> Candidate Preview
                            </div>
                        </div>
                    </div>

                    <!-- Chat Log Card -->
                    <div class="card glass-card flex-grow-1 d-flex flex-column overflow-hidden mb-3">
                        <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                            <!-- Message list -->
                            <div class="chat-area" id="chat-window">
                                <div id="chat-messages" class="d-flex flex-column gap-3">
                                    <!-- Messages load dynamically -->
                                </div>
                            </div>
                            
                            <!-- Transcript Area (For Voice Input Speech Preview) -->
                            <div class="mt-3 p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-10 rounded-3 d-none" id="transcript-preview-box">
                                <label class="fs-12 text-muted uppercase fw-semibold mb-1">Live Voice Speech Preview</label>
                                <textarea id="live-transcript" class="form-control bg-transparent text-white border-0 p-0 fs-14" rows="2" placeholder="Start speaking to record your response..." readonly></textarea>
                            </div>
                        </div>
                        
                        <!-- Control Bar/Input Card Footer -->
                        <div class="card-footer border-top border-secondary border-opacity-10 p-3 bg-dark bg-opacity-30">
                            <!-- Voice Controls (if not standard chat) -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="px-3 py-1.5 bg-dark rounded-3 border border-secondary border-opacity-10 d-flex align-items-center gap-2">
                                        <small class="text-muted fs-12">Voice Status:</small>
                                        <span class="fw-bold text-success fs-13" id="voice-status">Idle</span>
                                    </div>
                                    <div class="px-3 py-1.5 bg-dark rounded-3 border border-secondary border-opacity-10 d-flex align-items-center gap-2">
                                        <small class="text-muted fs-12">Mic:</small>
                                        <span class="fw-bold text-info fs-13" id="mic-status">Ready</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-danger px-3" id="btn-listen" disabled>
                                        <i class="ti ti-microphone me-1"></i> Speak Answer
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" id="btn-stop" disabled>
                                        <i class="ti ti-player-stop me-1"></i> Stop
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" id="btn-replay" disabled>
                                        <i class="ti ti-volume me-1"></i> Replay Q
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="btn-skip" disabled>
                                        <i class="ti ti-player-track-next me-1"></i> Skip
                                    </button>
                                    <button type="button" class="btn btn-success fw-bold px-3" id="btn-end" disabled>
                                        <i class="ti ti-rosette-discount-check me-1"></i> End & Evaluate
                                    </button>
                                </div>
                            </div>

                            <!-- Text Fallback Input Form -->
                            <form id="chat-form">
                                <div class="input-group">
                                    <input type="text" id="chat-input" class="form-control bg-dark border-secondary border-opacity-20 text-white rounded-3-start py-3" placeholder="Type your answer here..." autocomplete="off">
                                    <button class="btn btn-primary px-4 rounded-3-end" type="submit" id="btn-send">
                                        <i class="ti ti-send fs-18"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Coaching & Scorecard Panel -->
                <div class="col-lg-4 d-flex flex-column">
                    <!-- Real-Time Coaching Card -->
                    <div class="card glass-card mb-4 flex-grow-1 overflow-hidden d-flex flex-column">
                        <div class="card-header border-bottom border-secondary border-opacity-10 py-3 px-4 bg-dark bg-opacity-20">
                            <h6 class="fw-bold mb-0 text-white"><i class="ti ti-bulb text-warning me-1"></i> Live STAR Coaching</h6>
                        </div>
                        <div class="card-body p-4 overflow-y-auto" style="height: calc(100vh - 280px);">
                            <div class="text-center py-3 border-bottom border-secondary border-opacity-10 mb-4">
                                <small class="text-muted d-block uppercase fs-11">Current Answer STAR Score</small>
                                <h2 class="fw-bold text-success mt-1 mb-0" id="latest-star-score">0/10</h2>
                            </div>

                            <div class="d-grid gap-3 mb-4">
                                <div class="star-pill" id="star-pill-situation">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold fs-13">Situation</span>
                                        <span class="badge bg-secondary rounded-pill" id="star-situation">0</span>
                                    </div>
                                    <small class="text-muted fs-11 d-block">Detail the context/background of your story.</small>
                                </div>
                                <div class="star-pill" id="star-pill-task">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold fs-13">Task</span>
                                        <span class="badge bg-secondary rounded-pill" id="star-task">0</span>
                                    </div>
                                    <small class="text-muted fs-11 d-block">Define your specific responsibilities or goal.</small>
                                </div>
                                <div class="star-pill" id="star-pill-action">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold fs-13">Action</span>
                                        <span class="badge bg-secondary rounded-pill" id="star-action">0</span>
                                    </div>
                                    <small class="text-muted fs-11 d-block">Explain the exact steps you took to solve it.</small>
                                </div>
                                <div class="star-pill" id="star-pill-result">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold fs-13">Result</span>
                                        <span class="badge bg-secondary rounded-pill" id="star-result">0</span>
                                    </div>
                                    <small class="text-muted fs-11 d-block">Detail the metrics, outcomes, and achievements.</small>
                                </div>
                            </div>

                            <div class="border-top border-secondary border-opacity-10 pt-3">
                                <label class="fs-12 text-muted uppercase fw-semibold mb-2">Focus Area</label>
                                <div class="alert alert-dark border-0 rounded-3 p-2.5 fs-13 mb-3 text-white" id="star-focus-area">
                                    Waiting for answer...
                                </div>

                                <label class="fs-12 text-muted uppercase fw-semibold mb-2">STAR Coaching Tip</label>
                                <p class="text-muted-light fs-13 mb-0" id="star-tip">
                                    Provide answers in structured STAR format (Situation, Task, Action, Result) to receive real-time coaching suggestions here.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Final Scorecard Card (Visible at the End) -->
                    <div class="card glass-card border-success border-2 d-none flex-grow-1 overflow-hidden d-flex flex-column" id="evaluation-panel">
                        <div class="card-header bg-success bg-opacity-10 border-bottom border-success border-opacity-20 py-3 px-4 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-white"><i class="ti ti-rosette-discount-check text-success me-1"></i> Interview Result</h6>
                            <span class="badge bg-success fs-14 px-3 py-1.5" id="overall-score-badge">0/10</span>
                        </div>
                        <div class="card-body p-4 overflow-y-auto" style="height: calc(100vh - 280px);">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-dark rounded border border-secondary border-opacity-10 text-center">
                                        <small class="text-muted d-block fs-11">Communication</small>
                                        <span class="fw-bold text-white fs-14" id="communication-score">0/10</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-dark rounded border border-secondary border-opacity-10 text-center">
                                        <small class="text-muted d-block fs-11">Confidence</small>
                                        <span class="fw-bold text-white fs-14" id="confidence-score">0/10</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-dark rounded border border-secondary border-opacity-10 text-center">
                                        <small class="text-muted d-block fs-11">Relevance</small>
                                        <span class="fw-bold text-white fs-14" id="relevance-score">0/10</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-dark rounded border border-secondary border-opacity-10 text-center">
                                        <small class="text-muted d-block fs-11">STAR Avg</small>
                                        <span class="fw-bold text-white fs-14" id="star-average-score">0/10</span>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-primary bg-primary bg-opacity-10 border-0 text-white fs-13 mb-3" id="evaluation-summary"></div>
                            <div class="alert alert-secondary bg-dark border-0 text-white fs-13 mb-3 d-none" id="star-summary"></div>

                            <div class="mb-3">
                                <h6 class="fw-semibold text-success fs-14 mb-1">Strengths</h6>
                                <ul class="mb-0 ps-3 text-muted-light fs-13" id="strengths-list"></ul>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-semibold text-warning fs-14 mb-1">Areas of Improvement</h6>
                                <ul class="mb-0 ps-3 text-muted-light fs-13" id="improvements-list"></ul>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-semibold text-info fs-14 mb-1">Next Steps</h6>
                                <ul class="mb-0 ps-3 text-muted-light fs-13" id="next-steps-list"></ul>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="button" class="btn btn-primary" id="btn-download">
                                    <i class="ti ti-download me-1"></i> Download Transcript
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Configuration Form mapping for script (hidden) -->
<div style="display:none;">
    <input type="text" id="job-title" value="<?= esc((string) ($contextPreset['job_title'] ?? '')) ?>">
    <select id="difficulty"><option value="<?= esc((string) ($contextPreset['difficulty'] ?? 'medium')) ?>" selected></option></select>
    <select id="question-pack"><option value="<?= esc((string) ($contextPreset['question_pack'] ?? 'general')) ?>" selected></option></select>
    <select id="interview-mode"><option value="<?= esc((string) ($contextPreset['interview_mode'] ?? 'chat')) ?>" selected></option></select>
    <input type="checkbox" id="webcam-enabled" <?= ($contextPreset['webcam_enabled'] ?? false) ? 'checked' : '' ?>>
    <input type="checkbox" id="auto-listen-toggle" checked>
    <div id="speech-support"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contextPreset = <?= json_encode($contextPreset, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const applicationId = Number(contextPreset.application_id || 0);
    const initialPrompt = contextPreset.job_title
        ? `Hello! I am your JobberRecruit AI interviewer for the ${contextPreset.job_title} role. Click Enter Interview Room to begin your live mock interview practice.`
        : "Hello! I am your JobberRecruit AI interviewer. Let's start the mock interview.";

    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatWindow = document.getElementById('chat-window');
    const chatMessages = document.getElementById('chat-messages');
    const btnSend = document.getElementById('btn-send');
    const btnBegin = document.getElementById('btn-begin');
    const btnListen = document.getElementById('btn-listen');
    const btnStop = document.getElementById('btn-stop');
    const btnReplay = document.getElementById('btn-replay');
    const btnSkip = document.getElementById('btn-skip');
    const btnEnd = document.getElementById('btn-end');
    const btnDownload = document.getElementById('btn-download');
    const jobTitleInput = document.getElementById('job-title');
    const difficultySelect = document.getElementById('difficulty');
    const questionPackSelect = document.getElementById('question-pack');
    const interviewModeSelect = document.getElementById('interview-mode');
    const webcamEnabledToggle = document.getElementById('webcam-enabled');
    const liveTranscript = document.getElementById('live-transcript');
    const voiceStatus = document.getElementById('voice-status');
    const micStatus = document.getElementById('mic-status');
    const speechSupport = document.getElementById('speech-support');
    const autoListenToggle = document.getElementById('auto-listen-toggle');
    const questionCountEl = document.getElementById('question-count');
    const answerCountEl = document.getElementById('answer-count');
    const sessionDurationEl = document.getElementById('session-duration');
    const evaluationPanel = document.getElementById('evaluation-panel');
    const overallScoreBadge = document.getElementById('overall-score-badge');
    const communicationScore = document.getElementById('communication-score');
    const confidenceScore = document.getElementById('confidence-score');
    const relevanceScore = document.getElementById('relevance-score');
    const starAverageScore = document.getElementById('star-average-score');
    const latestStarScore = document.getElementById('latest-star-score');
    const starSituation = document.getElementById('star-situation');
    const starTask = document.getElementById('star-task');
    const starAction = document.getElementById('star-action');
    const starResult = document.getElementById('star-result');
    const starFocusArea = document.getElementById('star-focus-area');
    const starTip = document.getElementById('star-tip');
    const starSummary = document.getElementById('star-summary');
    const evaluationSummary = document.getElementById('evaluation-summary');
    const strengthsList = document.getElementById('strengths-list');
    const improvementsList = document.getElementById('improvements-list');
    const nextStepsList = document.getElementById('next-steps-list');
    const webcamPreview = document.getElementById('webcam-preview');
    const webcamPlaceholder = document.getElementById('webcam-placeholder');
    const voiceWaves = document.getElementById('voice-waves');
    const transcriptPreviewBox = document.getElementById('transcript-preview-box');

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognitionSupported = !!SpeechRecognition;
    const synthesisSupported = 'speechSynthesis' in window;
    const packLabels = {
        general: 'General',
        engineering: 'Engineering',
        product: 'Product',
        sales: 'Sales',
        marketing: 'Marketing',
        support: 'Support',
        operations: 'Operations'
    };

    let history = [];
    let jobTitle = contextPreset.job_title || '';
    let currentQuestion = '';
    let interviewStarted = false;
    let isListening = false;
    let isEvaluating = false;
    let awaitingAnswer = false;
    let recognition = null;
    let finalTranscript = '';
    let questionCount = 0;
    let answerCount = 0;
    let sessionStartedAt = null;
    let durationTimer = null;
    let mediaStream = null;

    function setVoiceStatus(text) {
        voiceStatus.textContent = text;
        if (text === 'Interviewer is speaking' && voiceWaves) {
            voiceWaves.style.setProperty('display', 'flex', 'important');
        } else if (voiceWaves) {
            voiceWaves.style.setProperty('display', 'none', 'important');
        }
    }

    function setMicStatus(text) {
        micStatus.textContent = text;
    }

    function isVoiceMode() {
        return interviewModeSelect.value !== 'chat';
    }

    function updateButtons() {
        const activeInterview = interviewStarted && !isEvaluating;
        const voiceMode = isVoiceMode();

        btnListen.disabled = !activeInterview || !voiceMode || !recognitionSupported || isListening;
        btnStop.disabled = !voiceMode || !isListening;
        btnReplay.disabled = !voiceMode || !interviewStarted || !currentQuestion;
        btnSkip.disabled = !activeInterview;
        btnEnd.disabled = !activeInterview || answerCount === 0;
        btnDownload.disabled = history.length === 0;
    }

    function getInterviewOptions() {
        return {
            difficulty: difficultySelect.value,
            questionPack: questionPackSelect.value,
            interviewMode: interviewModeSelect.value,
            webcamEnabled: webcamEnabledToggle.checked,
        };
    }

    function renderSessionStats() {
        questionCountEl.textContent = String(questionCount);
        answerCountEl.textContent = String(answerCount);
    }

    function formatDuration(totalSeconds) {
        const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');
        return `${minutes}:${seconds}`;
    }

    function startDurationTimer() {
        sessionStartedAt = Date.now();
        sessionDurationEl.textContent = '00:00';

        if (durationTimer) {
            clearInterval(durationTimer);
        }

        durationTimer = setInterval(() => {
            const elapsedSeconds = Math.floor((Date.now() - sessionStartedAt) / 1000);
            sessionDurationEl.textContent = formatDuration(elapsedSeconds);
        }, 1000);
    }

    function stopDurationTimer() {
        if (durationTimer) {
            clearInterval(durationTimer);
            durationTimer = null;
        }
    }

    function getDurationSeconds() {
        if (!sessionStartedAt) {
            return 0;
        }

        return Math.max(0, Math.floor((Date.now() - sessionStartedAt) / 1000));
    }

    function stripMarkdown(text) {
        return text
            .replace(/\*\*(.*?)\*\*/g, '$1')
            .replace(/`(.*?)`/g, '$1')
            .replace(/\[(.*?)\]\(.*?\)/g, '$1')
            .replace(/\n+/g, ' ')
            .trim();
    }

    function pickNigerianVoice() {
        const voices = window.speechSynthesis.getVoices();
        const preferred = voices.find((voice) => /en-ng|nigeria/i.test(`${voice.lang} ${voice.name}`));
        if (preferred) return preferred;

        return voices.find((voice) => /en-gb|uk/i.test(`${voice.lang} ${voice.name}`)) || voices.find((voice) => /en/i.test(voice.lang)) || null;
    }

    function speakText(text) {
        if (!synthesisSupported || !isVoiceMode()) return;

        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(stripMarkdown(text));
        const preferredVoice = pickNigerianVoice();
        if (preferredVoice) {
            utterance.voice = preferredVoice;
            utterance.lang = preferredVoice.lang;
        } else {
            utterance.lang = 'en-NG';
        }
        utterance.rate = 1;
        utterance.pitch = 1;
        utterance.onstart = () => setVoiceStatus('Interviewer is speaking');
        utterance.onend = () => {
            if (!isListening) {
                setVoiceStatus('Waiting for your answer');
            }

            if (isVoiceMode() && awaitingAnswer && autoListenToggle.checked && recognitionSupported && interviewStarted && !isListening && !isEvaluating) {
                setTimeout(() => {
                    if (awaitingAnswer && !isListening && recognition) {
                        recognition.start();
                    }
                }, 600);
            }
        };
        window.speechSynthesis.speak(utterance);
    }

    function appendMessage(role, message, mode = 'text') {
        const div = document.createElement('div');
        const isUser = role === 'user';
        
        div.className = `d-flex w-100 ${isUser ? 'justify-content-end' : 'justify-content-start'} mb-3`;

        const name = isUser ? 'You' : 'AI Interviewer';
        const bubbleClass = isUser ? 'bubble-user' : 'bubble-model';
        const badge = mode === 'voice'
            ? '<span class="badge bg-warning bg-opacity-20 text-warning mb-1.5 d-block" style="width:fit-content; font-size:10px;">Voice Answer</span>'
            : (mode === 'system' ? '<span class="badge bg-info bg-opacity-20 text-info mb-1.5 d-block" style="width:fit-content; font-size:10px;">System</span>' : '');

        div.innerHTML = `
            <div class="bubble ${bubbleClass}">
                <div class="fw-bold mb-1 fs-12 text-white-50">${name}</div>
                ${badge}
                <div>${message.replace(/\n/g, '<br>')}</div>
            </div>
        `;

        chatMessages.appendChild(div);
        chatWindow.scrollTop = chatWindow.scrollHeight;

        if (role === 'model') {
            currentQuestion = message;

            if (interviewStarted && message !== initialPrompt) {
                questionCount++;
                awaitingAnswer = true;
                renderSessionStats();
            }
        }

        if (role === 'user' && interviewStarted && mode !== 'system') {
            answerCount++;
            renderSessionStats();
        }

        if (message !== initialPrompt) {
            history.push({sender: role, message: message});
        }
    }

    function updateStarPanel(data = {}) {
        const breakdown = data.star_breakdown || {};
        latestStarScore.textContent = `${data.star_score || 0}/10`;
        
        const updatePill = (id, val) => {
            const el = document.getElementById('star-' + id);
            const pill = document.getElementById('star-pill-' + id);
            if (el) el.textContent = String(val);
            if (pill) {
                if (val > 0) {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            }
        };

        updatePill('situation', breakdown.situation || 0);
        updatePill('task', breakdown.task || 0);
        updatePill('action', breakdown.action || 0);
        updatePill('result', breakdown.result || 0);

        starFocusArea.textContent = data.focus_area || 'Waiting';
        starTip.textContent = data.star_tip || 'Answer a question to receive structured STAR coaching.';
    }

    function buildTranscriptText() {
        const options = getInterviewOptions();
        const lines = [
            'JobberRecruit Mock Interview Transcript',
            `Job Title: ${jobTitle || 'Not set'}`,
            `Difficulty: ${options.difficulty}`,
            `Question Pack: ${packLabels[options.questionPack] || options.questionPack}`,
            `Interview Style: ${options.interviewMode}`,
            `Webcam Preview: ${options.webcamEnabled ? 'On' : 'Off'}`,
            `Duration: ${sessionDurationEl.textContent}`,
            '',
        ];

        history.forEach((entry) => {
            const speaker = entry.sender === 'user' ? 'Candidate' : 'Interviewer';
            lines.push(`${speaker}: ${entry.message}`);
        });

        return lines.join('\n');
    }

    function downloadTranscript() {
        if (history.length === 0) {
            toastr.info('No transcript available yet.');
            return;
        }

        const blob = new Blob([buildTranscriptText()], { type: 'text/plain;charset=utf-8' });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        const safeTitle = (jobTitle || 'mock-interview').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

        link.href = url;
        link.download = `${safeTitle || 'mock-interview'}-transcript.txt`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }

    async function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            return;
        }

        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            webcamPreview.srcObject = mediaStream;
            webcamPreview.classList.remove('d-none');
            webcamPlaceholder.classList.add('d-none');
        } catch (error) {
            console.error('Webcam failed', error);
            webcamPreview.classList.add('d-none');
            webcamPlaceholder.classList.remove('d-none');
        }
    }

    function sendAnswer(message, mode = 'text') {
        if (!interviewStarted || !message.trim()) return;

        awaitingAnswer = false;
        appendMessage('user', message, mode);
        setVoiceStatus('Thinking about your answer');

        btnSend.disabled = true;
        btnListen.disabled = true;
        btnStop.disabled = true;
        btnSkip.disabled = true;
        btnSend.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        const formData = new FormData();
        formData.append('type', 'interview');
        formData.append('message', message);
        formData.append('history', JSON.stringify(history));
        formData.append('extra', jobTitle);
        formData.append('difficulty', difficultySelect.value);
        formData.append('questionPack', questionPackSelect.value);
        formData.append('interviewMode', interviewModeSelect.value);
        formData.append('webcamEnabled', webcamEnabledToggle.checked ? '1' : '0');
        formData.append('applicationId', String(applicationId));

        fetch('<?= base_url('candidate/career-tools/send-message') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            appendMessage('model', data.message);
            updateStarPanel(data);
            if (isVoiceMode()) {
                speakText(data.message);
            }
        })
        .catch(() => {
            toastr.error('Connection error. Please try again.');
            setVoiceStatus('Connection issue');
        })
        .finally(() => {
            btnSend.disabled = false;
            btnSend.innerHTML = '<i class="ti ti-send"></i>';
            updateButtons();
        });
    }

    function renderList(target, items, emptyText) {
        target.innerHTML = '';

        if (!items.length) {
            const li = document.createElement('li');
            li.textContent = emptyText;
            target.appendChild(li);
            return;
        }

        items.forEach((item) => {
            const li = document.createElement('li');
            li.className = 'mb-1';
            li.textContent = item;
            target.appendChild(li);
        });
    }

    function endInterview() {
        if (!interviewStarted || isEvaluating || answerCount === 0) return;

        isEvaluating = true;
        interviewStarted = false;
        awaitingAnswer = false;
        stopDurationTimer();
        window.speechSynthesis.cancel();

        if (recognition && isListening) {
            recognition.stop();
        }

        setVoiceStatus('Generating final evaluation');
        setMicStatus('Idle');
        updateButtons();
        btnEnd.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        const formData = new FormData();
        formData.append('history', JSON.stringify(history));
        formData.append('jobTitle', jobTitle);
        formData.append('difficulty', difficultySelect.value);
        formData.append('questionPack', questionPackSelect.value);
        formData.append('interviewMode', interviewModeSelect.value);
        formData.append('webcamEnabled', webcamEnabledToggle.checked ? '1' : '0');
        formData.append('durationSeconds', String(getDurationSeconds()));
        formData.append('applicationId', String(applicationId));

        fetch('<?= base_url('candidate/career-tools/evaluate-interview') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide coaching panel & show results
            document.getElementById('evaluation-panel').parentNode.classList.add('col-lg-4');
            document.getElementById('evaluation-panel').classList.remove('d-none');
            
            // Fill scores
            overallScoreBadge.textContent = `${data.overall_score || 0}/10`;
            communicationScore.textContent = `${data.communication_score || 0}/10`;
            confidenceScore.textContent = `${data.confidence_score || 0}/10`;
            relevanceScore.textContent = `${data.relevance_score || 0}/10`;
            starAverageScore.textContent = `${data.star_average || 0}/10`;
            evaluationSummary.textContent = data.summary || 'Your interview summary is unavailable.';
            
            if (data.star_summary) {
                starSummary.classList.remove('d-none');
                starSummary.textContent = data.star_summary;
            } else {
                starSummary.classList.add('d-none');
            }
            
            renderList(strengthsList, data.strengths || [], 'No strengths returned.');
            renderList(improvementsList, data.improvements || [], 'No improvements returned.');
            renderList(nextStepsList, data.next_steps || [], 'No next steps returned.');
            
            setVoiceStatus('Interview complete');
            appendMessage('model', 'Your mock interview is complete. Review your scorecard on the right side of your screen.', 'system');
            
            toastr.success('Evaluation generated successfully.');
        })
        .catch(() => {
            toastr.error('Could not generate the final evaluation.');
            setVoiceStatus('Evaluation failed');
        })
        .finally(() => {
            btnEnd.innerHTML = '<i class="ti ti-rosette-discount-check me-1"></i> End & Evaluate';
            updateButtons();
        });
    }

    function beginInterview() {
        // Hide ready overlay
        document.getElementById('ready-overlay').style.display = 'none';

        interviewStarted = true;
        isEvaluating = false;
        questionCount = 0;
        answerCount = 0;
        currentQuestion = '';
        history = [];
        liveTranscript.value = '';
        chatMessages.innerHTML = '';
        
        updateStarPanel({});
        renderSessionStats();
        startDurationTimer();

        // Start camera if needed
        if (webcamEnabledToggle.checked) {
            startCamera();
        }

        if (isVoiceMode() && recognitionSupported) {
            transcriptPreviewBox.classList.remove('d-none');
        }

        appendMessage('user', `Starting mock interview for ${jobTitle}. Mode: ${interviewModeSelect.value}. Difficulty: ${difficultySelect.value}.`, 'system');

        const openingQuestion = applicationId > 0
            ? `Great. We are now starting your ${difficultySelect.value} mock interview for the ${jobTitle} position. I will use your job application details and CV context as the yardstick. Let's start: Please introduce yourself and highlight why you are a great fit for this role.`
            : `Great. We are now starting your ${difficultySelect.value} mock interview for the ${jobTitle} position. First question: Could you please introduce yourself and tell me why you want this role?`;

        appendMessage('model', openingQuestion);
        if (isVoiceMode()) {
            speakText(openingQuestion);
        }
        setVoiceStatus('Interview in progress');
        updateButtons();
    }

    if (recognitionSupported) {
        recognition = new SpeechRecognition();
        recognition.lang = 'en-NG';
        recognition.continuous = true;
        recognition.interimResults = true;

        recognition.onstart = () => {
            isListening = true;
            finalTranscript = '';
            liveTranscript.value = '';
            setMicStatus('Listening');
            setVoiceStatus('Recording your answer');
            updateButtons();
        };

        recognition.onresult = (event) => {
            let interimTranscript = '';

            for (let i = event.resultIndex; i < event.results.length; i++) {
                const transcript = event.results[i][0].transcript;
                if (event.results[i].isFinal) {
                    finalTranscript += transcript + ' ';
                } else {
                    interimTranscript += transcript;
                }
            }

            liveTranscript.value = `${finalTranscript}${interimTranscript}`.trim();
        };

        recognition.onerror = (event) => {
            isListening = false;
            setMicStatus('Microphone error');
            setVoiceStatus(`Voice error: ${event.error}`);
            updateButtons();
        };

        recognition.onend = () => {
            const transcript = liveTranscript.value.trim();
            isListening = false;
            setMicStatus('Idle');
            updateButtons();

            if (transcript) {
                sendAnswer(transcript, 'voice');
                liveTranscript.value = '';
            } else if (interviewStarted) {
                setVoiceStatus('No speech detected');
            }
        };
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const msg = chatInput.value.trim();
        if (!msg) return;

        chatInput.value = '';
        sendAnswer(msg, 'text');
    });

    btnBegin.addEventListener('click', beginInterview);

    btnListen.addEventListener('click', function() {
        if (!recognition || !interviewStarted || !isVoiceMode()) return;

        window.speechSynthesis.cancel();
        recognition.start();
    });

    btnStop.addEventListener('click', function() {
        if (!recognition || !isListening) return;
        recognition.stop();
    });

    btnReplay.addEventListener('click', function() {
        if (!currentQuestion || !isVoiceMode()) return;
        speakText(currentQuestion);
    });

    btnSkip.addEventListener('click', function() {
        if (!interviewStarted) return;
        sendAnswer('Please ask me the next interview question.', 'system');
    });

    btnEnd.addEventListener('click', endInterview);
    btnDownload.addEventListener('click', downloadTranscript);

    // Initial setup
    appendMessage('model', initialPrompt, 'system');

    speechSupport.textContent = recognitionSupported
        ? (synthesisSupported ? 'Mic + speaker ready' : 'Mic only')
        : 'Voice input unavailable';

    if (!recognitionSupported) {
        setMicStatus('Not supported');
        setVoiceStatus('Use text fallback');
        toastr.warning('Speech recognition is not supported in this browser. Please use text responses.');
    } else {
        setMicStatus('Ready');
        setVoiceStatus('Waiting to start');
    }

    updateButtons();
    renderSessionStats();
    updateStarPanel({});
});
</script>
<?= $this->endSection() ?>
