<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
$recentSessions = $recentSessions ?? [];
$contextPreset = $contextPreset ?? [];
$questionPackLabels = [
    'general' => 'General',
    'engineering' => 'Engineering',
    'product' => 'Product',
    'sales' => 'Sales',
    'marketing' => 'Marketing',
    'support' => 'Support',
    'operations' => 'Operations',
];
?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">AI Mock Interview</h4>
                <h6>Practice interviewing for your dream role.</h6>
            </div>
        </div>
        <div class="page-btn">
            <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Tools
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card custom-card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-primary py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-white-transparent me-3">
                            <i class="ti ti-user-check text-white fs-20"></i>
                        </div>
                        <div>
                            <h6 class="text-white fw-bold mb-0">Hiring Manager AI</h6>
                            <p class="text-white-50 fs-12 mb-0">Chat-First Mock Interview With Voice Option</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-4 border-bottom bg-light-subtle">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="job-title" class="form-label fw-semibold">Job Title</label>
                                <input type="text" id="job-title" class="form-control" value="<?= esc((string) ($contextPreset['job_title'] ?? '')) ?>" placeholder="e.g. Product Manager, Frontend Developer">
                                <small class="text-muted d-block mt-2">Set the role once, then start the interview in chat style. Voice remains optional.</small>
                            </div>
                            <div class="col-md-3">
                                <label for="difficulty" class="form-label fw-semibold">Difficulty</label>
                                <select id="difficulty" class="form-select">
                                    <option value="easy">Easy</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="question-pack" class="form-label fw-semibold">Question Pack</label>
                                <select id="question-pack" class="form-select">
                                    <?php foreach ($questionPackLabels as $value => $label): ?>
                                        <option value="<?= esc($value) ?>"><?= esc($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="interview-mode" class="form-label fw-semibold">Interview Style</label>
                                <select id="interview-mode" class="form-select">
                                    <option value="chat" selected>Chat Interview</option>
                                    <option value="voice">Voice Interview</option>
                                    <option value="video">Video-Style Practice</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch pt-4">
                                    <input class="form-check-input" type="checkbox" role="switch" id="webcam-enabled">
                                    <label class="form-check-label fw-semibold" for="webcam-enabled">Enable webcam preview</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary w-100" id="btn-begin">
                                    <i class="ti ti-player-play me-1"></i> Begin Interview
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 pt-4 pb-2 border-bottom">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Interview Status</div>
                                    <div class="voice-panel-value" id="voice-status">Waiting to start</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Microphone</div>
                                    <div class="voice-panel-value" id="mic-status">Idle</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Voice Support</div>
                                    <div class="voice-panel-value" id="speech-support">Checking...</div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 mb-0" id="voice-help">
                            The interview runs in chat style by default. You can switch to voice mode and the system will prefer a Nigerian English voice when available.
                        </div>
                    </div>
                    <?php if (! empty($contextPreset)): ?>
                        <div class="alert alert-success mx-4 mt-4 mb-0">
                            <strong>Application-aware practice:</strong> This session uses your applied job details, submitted CV record, cover letter, and candidate profile as the interview yardstick.
                        </div>
                    <?php endif; ?>
                    <div class="px-4 pt-4 pb-2 border-bottom bg-body-tertiary">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-danger" id="btn-listen" disabled>
                                <i class="ti ti-microphone me-1"></i> Start Answer
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="btn-stop" disabled>
                                <i class="ti ti-player-stop me-1"></i> Stop Recording
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="btn-replay" disabled>
                                <i class="ti ti-volume me-1"></i> Replay Question
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btn-skip" disabled>
                                <i class="ti ti-player-track-next me-1"></i> Next Question
                            </button>
                            <button type="button" class="btn btn-outline-success" id="btn-end" disabled>
                                <i class="ti ti-rosette-discount-check me-1"></i> End And Score
                            </button>
                            <button type="button" class="btn btn-outline-dark" id="btn-download" disabled>
                                <i class="ti ti-download me-1"></i> Download Transcript
                            </button>
                        </div>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="auto-listen-toggle" checked>
                            <label class="form-check-label" for="auto-listen-toggle">Auto-start listening after each AI question</label>
                        </div>
                        <div class="mt-3">
                            <label for="live-transcript" class="form-label fw-semibold">Live Transcript</label>
                            <textarea id="live-transcript" class="form-control" rows="4" placeholder="Your spoken answer will appear here..." readonly></textarea>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-bottom">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Questions Asked</div>
                                    <div class="voice-panel-value" id="question-count">0</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Answers Given</div>
                                    <div class="voice-panel-value" id="answer-count">0</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Session Time</div>
                                    <div class="voice-panel-value" id="session-duration">00:00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-bottom bg-light-subtle">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Latest STAR Score</div>
                                    <div class="voice-panel-value" id="latest-star-score">0/10</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Situation</div>
                                    <div class="voice-panel-value" id="star-situation">0</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Task</div>
                                    <div class="voice-panel-value" id="star-task">0</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Action</div>
                                    <div class="voice-panel-value" id="star-action">0</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Result</div>
                                    <div class="voice-panel-value" id="star-result">0</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Focus Area</div>
                                    <div class="voice-panel-value" id="star-focus-area">Waiting</div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">STAR Coaching Tip</div>
                                    <div class="voice-panel-value fs-14" id="star-tip">Answer a question to receive structured STAR coaching.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-container p-0" style="height: 420px; overflow-y: auto;" id="chat-window">
                        <div class="p-4" id="chat-messages"></div>
                    </div>
                    <div class="p-4 border-top d-none" id="evaluation-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Interview Scorecard</h5>
                                <p class="text-muted mb-0">AI review of your mock interview session.</p>
                            </div>
                            <span class="badge bg-success fs-14" id="overall-score-badge">0/10</span>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Communication</div>
                                    <div class="voice-panel-value" id="communication-score">0/10</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Confidence</div>
                                    <div class="voice-panel-value" id="confidence-score">0/10</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">Relevance</div>
                                    <div class="voice-panel-value" id="relevance-score">0/10</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="voice-panel h-100">
                                    <div class="voice-panel-label">STAR Average</div>
                                    <div class="voice-panel-value" id="star-average-score">0/10</div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-primary" id="evaluation-summary"></div>
                        <div class="alert alert-secondary d-none" id="star-summary"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <h6 class="fw-semibold">Strengths</h6>
                                <ul class="mb-0 ps-3" id="strengths-list"></ul>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-semibold">Improvements</h6>
                                <ul class="mb-0 ps-3" id="improvements-list"></ul>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-semibold">Next Steps</h6>
                                <ul class="mb-0 ps-3" id="next-steps-list"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top p-3">
                    <form id="chat-form">
                        <label for="chat-input" class="form-label fw-semibold">Fallback Text Answer</label>
                        <div class="input-group">
                            <input type="text" id="chat-input" class="form-control border-0 bg-light" placeholder="Type your answer here if you prefer not to speak..." autocomplete="off">
                            <button class="btn btn-primary" type="submit" id="btn-send">
                                <i class="ti ti-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card custom-card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Video Practice Mode</h6>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-4x3 rounded overflow-hidden bg-dark-subtle d-flex align-items-center justify-content-center mb-3 position-relative">
                        <video id="webcam-preview" class="w-100 h-100 object-fit-cover d-none" autoplay playsinline muted></video>
                        <div id="webcam-placeholder" class="text-center p-4">
                            <i class="ti ti-video fs-32 text-muted d-block mb-2"></i>
                            <div class="text-muted">Webcam preview is off</div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" id="btn-camera">
                            <i class="ti ti-camera me-1"></i> Start Camera
                        </button>
                    </div>
                    <p class="text-muted fs-13 mb-0 mt-3" id="webcam-note">Turn on the webcam for a more realistic video-style interview practice session.</p>
                </div>
            </div>

            <div class="card custom-card border-0 shadow-sm overflow-hidden">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Recent Saved Sessions</h6>
                </div>
                <div class="card-body">
                    <div id="recent-sessions-list" class="d-grid gap-3">
                        <?php if ($recentSessions === []): ?>
                            <div class="text-muted fs-14" id="recent-sessions-empty">No saved sessions yet. Finish an interview to build your practice history.</div>
                        <?php else: ?>
                            <?php foreach ($recentSessions as $session): ?>
                                <div class="border rounded-3 p-3 session-history-item">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <div class="fw-semibold"><?= esc($session['job_title'] ?: 'Interview Session') ?></div>
                                            <div class="text-muted fs-13">
                                                <?= esc(ucfirst((string) ($session['difficulty'] ?? 'medium'))) ?>
                                                ·
                                                <?= esc($questionPackLabels[$session['question_pack'] ?? 'general'] ?? ucfirst((string) ($session['question_pack'] ?? 'General'))) ?>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary"><?= esc((string) ($session['overall_score'] ?? 0)) ?>/10</span>
                                    </div>
                                    <div class="text-muted fs-12 mt-2">
                                        STAR <?= esc((string) ($session['star_average'] ?? 0)) ?>/10
                                        ·
                                        <?= esc((string) ($session['created_at'] ?? '')) ?>
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
.chat-container::-webkit-scrollbar {
    width: 5px;
}
.chat-container::-webkit-scrollbar-thumb {
    background: #e0e0e0;
    border-radius: 10px;
}
.voice-panel {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    padding: 14px 16px;
    background: #fff;
}
.voice-panel-label {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #6c757d;
    margin-bottom: 6px;
}
.voice-panel-value {
    font-size: 15px;
    font-weight: 600;
}
.message-content {
    max-width: 85%;
    font-size: 14px;
    line-height: 1.6;
}
.user-message .d-flex {
    flex-direction: row-reverse;
}
.user-message .message-content {
    background-color: #007bff !important;
    color: white;
    margin-right: 0;
    margin-left: 10px;
}
.user-message .avatar {
    margin-right: 0 !important;
    margin-left: 8px;
}
.voice-answer {
    border-left: 4px solid #dc3545;
}
.voice-question {
    border-left: 4px solid #0d6efd;
}
.session-history-item {
    background: rgba(255, 255, 255, 0.85);
}
.object-fit-cover {
    object-fit: cover;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contextPreset = <?= json_encode($contextPreset, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const applicationId = Number(contextPreset.application_id || 0);
    const initialPrompt = contextPreset.job_title
        ? `Hello! I am your JobberRecruit AI interviewer for the ${contextPreset.job_title} role. Press Begin Interview to start a job-aware practice session.`
        : "Hello! I am your JobberRecruit AI interviewer. Tell me the job title you want to practice for, then press Begin Interview.";
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
    const btnCamera = document.getElementById('btn-camera');
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
    const recentSessionsList = document.getElementById('recent-sessions-list');
    const webcamPreview = document.getElementById('webcam-preview');
    const webcamPlaceholder = document.getElementById('webcam-placeholder');
    const webcamNote = document.getElementById('webcam-note');

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

        btnBegin.disabled = interviewStarted;
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
        div.className = `chat-message ${role === 'user' ? 'user-message' : 'model-message'} mb-4`;

        const isUser = role === 'user';
        const icon = isUser ? 'ti-user' : 'ti-user-check';
        const bg = isUser ? 'bg-primary' : 'bg-primary-transparent';
        const messageClass = isUser && mode === 'voice' ? 'voice-answer' : (!isUser ? 'voice-question' : '');
        const badge = mode === 'voice'
            ? '<span class="badge bg-danger-transparent mb-2">Voice Answer</span>'
            : (mode === 'system' ? '<span class="badge bg-info-transparent mb-2">System</span>' : '');

        div.innerHTML = `
            <div class="d-flex">
                <div class="avatar avatar-sm ${bg} me-2 flex-shrink-0">
                    <i class="ti ${icon}"></i>
                </div>
                <div class="message-content p-3 bg-light rounded-3 shadow-xs ${messageClass}">
                    ${badge}
                    ${message.replace(/\n/g, '<br>')}
                </div>
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
        starSituation.textContent = String(breakdown.situation || 0);
        starTask.textContent = String(breakdown.task || 0);
        starAction.textContent = String(breakdown.action || 0);
        starResult.textContent = String(breakdown.result || 0);
        starFocusArea.textContent = data.focus_area || 'Waiting';
        starTip.textContent = data.star_tip || 'Answer a question to receive structured STAR coaching.';
    }

    function buildTranscriptText() {
        const options = getInterviewOptions();
        const lines = [
            'JobberRecruit Mock Interview Transcript',
            `Job Title: ${jobTitle || jobTitleInput.value.trim() || 'Not set'}`,
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

    function renderRecentSession(session) {
        const emptyState = document.getElementById('recent-sessions-empty');
        if (emptyState) {
            emptyState.remove();
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'border rounded-3 p-3 session-history-item';
        wrapper.innerHTML = `
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <div class="fw-semibold">${session.job_title || 'Interview Session'}</div>
                    <div class="text-muted fs-13">
                        ${session.difficulty ? session.difficulty.charAt(0).toUpperCase() + session.difficulty.slice(1) : 'Medium'}
                        ·
                        ${packLabels[session.question_pack] || session.question_pack || 'General'}
                    </div>
                </div>
                <span class="badge bg-primary">${session.overall_score || 0}/10</span>
            </div>
            <div class="text-muted fs-12 mt-2">
                STAR ${session.star_average || 0}/10
                ·
                ${session.created_at || 'Just now'}
            </div>
        `;

        recentSessionsList.prepend(wrapper);
    }

    async function toggleCamera(forceOn = null) {
        const shouldEnable = forceOn !== null ? forceOn : !mediaStream;

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            toastr.warning('Camera preview is not supported in this browser.');
            return;
        }

        if (!shouldEnable && mediaStream) {
            mediaStream.getTracks().forEach((track) => track.stop());
            mediaStream = null;
            webcamPreview.srcObject = null;
            webcamPreview.classList.add('d-none');
            webcamPlaceholder.classList.remove('d-none');
            btnCamera.innerHTML = '<i class="ti ti-camera me-1"></i> Start Camera';
            webcamNote.textContent = 'Webcam preview is off.';
            return;
        }

        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            webcamPreview.srcObject = mediaStream;
            webcamPreview.classList.remove('d-none');
            webcamPlaceholder.classList.add('d-none');
            btnCamera.innerHTML = '<i class="ti ti-camera-off me-1"></i> Stop Camera';
            webcamNote.textContent = 'Camera preview is active for video-style practice.';
        } catch (error) {
            toastr.error('Unable to access your camera.');
            webcamNote.textContent = 'Camera access was denied or unavailable.';
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
        btnReplay.disabled = true;
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
            evaluationPanel.classList.remove('d-none');
            overallScoreBadge.textContent = `${data.overall_score || 0}/10`;
            communicationScore.textContent = `${data.communication_score || 0}/10`;
            confidenceScore.textContent = `${data.confidence_score || 0}/10`;
            relevanceScore.textContent = `${data.relevance_score || 0}/10`;
            starAverageScore.textContent = `${data.star_average || 0}/10`;
            evaluationSummary.textContent = data.summary || 'Your interview summary is unavailable right now.';
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
            appendMessage('model', 'Your mock interview is complete. Review your scorecard below and use the next steps to improve before your real interview.', 'system');
            if (data.saved_session) {
                renderRecentSession(data.saved_session);
                toastr.success('Interview session saved to your history.');
            }
        })
        .catch(() => {
            toastr.error('Could not generate the final evaluation.');
            setVoiceStatus('Evaluation failed');
        })
        .finally(() => {
            btnEnd.innerHTML = '<i class="ti ti-rosette-discount-check me-1"></i> End And Score';
            updateButtons();
        });
    }

    function beginInterview() {
        const selectedTitle = jobTitleInput.value.trim();
        if (!selectedTitle) {
            toastr.warning('Please enter a job title first.');
            jobTitleInput.focus();
            return;
        }

        jobTitle = selectedTitle;
        interviewStarted = true;
        isEvaluating = false;
        questionCount = 0;
        answerCount = 0;
        currentQuestion = '';
        history = [];
        liveTranscript.value = '';
        chatMessages.innerHTML = '';
        evaluationPanel.classList.add('d-none');
        starSummary.classList.add('d-none');
        updateStarPanel({});
        renderSessionStats();
        startDurationTimer();
        appendMessage('user', `I want to practice for the ${jobTitle} role. Difficulty: ${difficultySelect.value}. Pack: ${packLabels[questionPackSelect.value] || questionPackSelect.value}.`, 'system');

        const openingQuestion = applicationId > 0
            ? `Great. We are now starting your ${difficultySelect.value} ${packLabels[questionPackSelect.value] || questionPackSelect.value} mock interview for the ${jobTitle} position. I will use your application context as the yardstick. First question: Tell me about yourself and why you fit this role.`
            : `Great. We are now starting your ${difficultySelect.value} ${packLabels[questionPackSelect.value] || questionPackSelect.value} mock interview for the ${jobTitle} position. First question: Tell me about yourself and why you want this role.`;
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

        if (!interviewStarted) {
            jobTitleInput.value = msg;
            chatInput.value = '';
            beginInterview();
            return;
        }

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
    btnCamera.addEventListener('click', () => toggleCamera());
    webcamEnabledToggle.addEventListener('change', function() {
        if (!this.checked && mediaStream) {
            toggleCamera(false);
        } else if (this.checked && interviewModeSelect.value === 'video') {
            webcamNote.textContent = 'Video-style mode selected. You can start the camera when ready.';
        }
    });
    interviewModeSelect.addEventListener('change', function() {
        webcamNote.textContent = this.value === 'video'
            ? 'Video-style practice selected. Turn on the webcam for the most realistic experience.'
            : this.value === 'voice'
                ? 'Voice interview selected. Nigerian English voice is preferred when your browser provides one.'
                : 'Chat interview selected. Webcam preview is optional.';
        updateButtons();
    });

    appendMessage('model', initialPrompt, 'system');

    speechSupport.textContent = recognitionSupported
        ? (synthesisSupported ? 'Mic + speaker ready' : 'Mic only')
        : 'Voice input unavailable';

    if (jobTitle) {
        jobTitleInput.value = jobTitle;
    }

    if (!recognitionSupported) {
        setMicStatus('Not supported');
        setVoiceStatus('Use text fallback');
        document.getElementById('voice-help').classList.remove('alert-info');
        document.getElementById('voice-help').classList.add('alert-warning');
        document.getElementById('voice-help').textContent = 'This browser does not support voice recognition here. You can still run the mock interview by typing your answers below.';
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
