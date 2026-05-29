<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .builder-step-nav {
        border-right: 1px solid #e9ecef;
    }
    .step-item {
        padding: 12px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        font-weight: 500;
    }
    .step-item:hover {
        background-color: #f8f9fa;
        color: var(--primary-color);
    }
    .step-item.active {
        background-color: var(--primary-color);
        color: white;
    }
    .step-item i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    .ai-assist-btn {
        /* Use site brand primary color for AI buttons */
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color) 100%);
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .ai-assist-btn:hover {
        transform: scale(1.05);
        color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .ai-assist-btn i {
        margin-right: 5px;
    }
    
    /* Interactive template choice styling */
    .template-choice {
        transition: all 0.25s ease-in-out;
        background-color: #fff;
    }
    .template-choice:hover {
        transform: translateY(-4px);
        border-color: #0d6efd !important;
        box-shadow: 0 6px 15px rgba(13, 110, 253, 0.12) !important;
    }
    .template-choice.active {
        border-color: #0d6efd !important;
        border-width: 2px !important;
        box-shadow: 0 6px 15px rgba(13, 110, 253, 0.18) !important;
        background-color: #f8fafc;
    }
    .template-choice.active h6 {
        color: #0d6efd;
    }
    
    .template-preview {
        box-shadow: inset 0 0 8px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .template-choice:hover .template-preview {
        border-color: #cbd5e1 !important;
    }
    
    @media (min-width: 1200px) {
        .col-xl-2-4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    /* Export Buttons Hover effects */
    .download-pdf-btn, .download-docx-btn {
        transition: all 0.2s ease-in-out !important;
    }
    .download-pdf-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 24px rgba(220, 53, 69, 0.25) !important;
    }
    .download-docx-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 24px rgba(13, 110, 253, 0.25) !important;
    }

    /* AI Coach Floating Button */
    .ai-coach-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1050;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        /* brand primary gradient */
        background: linear-gradient(135deg, var(--primary-color) 0%, #0b5ed7 100%);
        border: none;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .ai-coach-fab:hover {
        transform: scale(1.1) translateY(-3px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        background: linear-gradient(135deg, #0b5ed7 0%, #084c9a 100%);
    }
    .ai-coach-fab i {
        font-size: 1.6rem;
    }
    .ai-coach-fab .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 3px solid rgba(99, 102, 241, 0.5);
        animation: fab-pulse 2s infinite;
    }

    @keyframes fab-pulse {
        0% {
            transform: scale(0.95);
            opacity: 0.8;
        }
        50% {
            transform: scale(1.3);
            opacity: 0;
        }
        100% {
            transform: scale(0.95);
            opacity: 0;
        }
    }

    /* Offcanvas Styling */
    .custom-coach-offcanvas {
        width: 480px !important;
        background-color: #0f172a; /* Premium dark theme */
        color: #f8fafc;
        border-left: 1px solid #1e293b;
        box-shadow: -10px 0 30px rgba(0,0,0,0.25);
    }
    .custom-coach-offcanvas .offcanvas-header {
        background-color: #1e293b;
        border-bottom: 1px solid #334155;
        padding: 1.25rem 1.5rem;
    }
    .custom-coach-offcanvas .offcanvas-title {
        color: #f8fafc;
        font-weight: 700;
    }
    .custom-coach-offcanvas .btn-close {
        filter: invert(1) grayscale(1) brightness(2);
    }
    .coach-chat-container {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .coach-messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        background-color: #0f172a;
    }
    .coach-bubble {
        max-width: 85%;
        padding: 12px 16px;
        border-radius: 16px;
        line-height: 1.5;
        font-size: 0.9rem;
    }
    .coach-bubble.coach {
        background-color: #1e293b;
        border-top-left-radius: 4px;
        color: #cbd5e1;
        align-self: flex-start;
        border: 1px solid #334155;
    }
    /* AI reply card styles for richer HTML returned by the model */
    .coach-bubble.coach .ai-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        border: 1px solid rgba(255,255,255,0.04);
        padding: 12px 14px;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(2,6,23,0.25);
        color: #e6eef8;
    }
    .coach-bubble.coach .ai-card h3 {
        margin: 0 0 6px 0;
        color: var(--primary-color, #0d6efd);
        font-size: 1rem;
    }
    .coach-bubble.coach .ai-card p { color: #cbd5e1; margin:0 0 8px 0; }
    .coach-bubble.coach .ai-card ul { padding-left:16px; margin:6px 0; }
    .coach-bubble.coach .ai-skill-badges { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
    .coach-bubble.coach .ai-skill-badges .badge {
        background: rgba(255,255,255,0.04);
        color: #e6eef8;
        padding:4px 8px; border-radius:999px; font-size:0.78rem; border: 1px solid rgba(255,255,255,0.03);
    }
    .coach-bubble.user {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border-top-right-radius: 4px;
        align-self: flex-end;
    }
    .coach-bubble h1, .coach-bubble h2, .coach-bubble h3 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .coach-bubble ul, .coach-bubble ol {
        padding-left: 1.2rem;
        margin-bottom: 0.5rem;
    }
    .coach-bubble li {
        margin-bottom: 0.25rem;
    }
    .coach-bubble strong {
        color: #818cf8;
        font-weight: 600;
    }
    .coach-input-area {
        background-color: #1e293b;
        border-top: 1px solid #334155;
        padding: 1.25rem;
    }
    .coach-input-group {
        background-color: #0f172a;
        border: 1px solid #334155;
        border-radius: 30px;
        padding: 6px 12px;
        display: flex;
        align-items: center;
    }
    .coach-input-field {
        flex: 1;
        background: transparent;
        border: none;
        color: #f8fafc;
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    .coach-input-field:focus {
        outline: none;
    }
    .coach-send-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0b5ed7 100%);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .coach-send-btn:hover {
        transform: scale(1.08);
        background: linear-gradient(135deg, #0b5ed7 0%, #084c9a 100%);
    }
    .coach-apply-btn {
        margin-top: 8px;
        background: rgba(11,94,215,0.08);
        border: 1px dashed rgba(11,94,215,0.5);
        color: var(--primary-color);
        font-size: 0.8rem;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
    }
    .coach-apply-btn:hover {
        background: var(--primary-color);
        color: white;
    }
    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 4px 8px;
    }
    .typing-dot {
        width: 8px;
        height: 8px;
        background-color: #94a3b8;
        border-radius: 50%;
        animation: typing-bounce 1.4s infinite ease-in-out both;
    }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes typing-bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
</style>
<?= $this->include('candidate/resume/ai_replies_css') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (!$resume): ?>
<!-- =================== RESUME ONBOARDING GATEWAY MODAL =================== -->
<style>
    .onboarding-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: linear-gradient(135deg, #0f0c29 0%, #1a1040 40%, #0d1b3e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeInOverlay 0.4s ease forwards;
        overflow-y: auto;
        padding: 2rem 1rem;
    }
    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    .onboarding-card-wrap {
        width: 100%;
        max-width: 960px;
    }
    .onboarding-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .onboarding-header .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(99, 102, 241, 0.15);
        border: 1px solid rgba(99, 102, 241, 0.4);
        color: #a5b4fc;
        padding: 5px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        margin-bottom: 1.25rem;
    }
    .onboarding-header h2 {
        font-size: clamp(1.75rem, 4vw, 2.5rem);
        font-weight: 800;
        color: #f8fafc;
        line-height: 1.2;
        margin-bottom: 0.75rem;
    }
    .onboarding-header h2 span {
        background: linear-gradient(90deg, #818cf8, #c084fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .onboarding-header p {
        color: #94a3b8;
        font-size: 1.05rem;
        max-width: 560px;
        margin: 0 auto;
    }
    .ob-options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
        gap: 1.5rem;
    }
    .ob-option-card {
        background: rgba(255,255,255,0.04);
        border: 1.5px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 2rem 1.75rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: block;
        backdrop-filter: blur(10px);
    }
    .ob-option-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--ob-glow);
        opacity: 0;
        transition: opacity 0.3s;
        border-radius: 20px;
    }
    .ob-option-card:hover::before { opacity: 1; }
    .ob-option-card:hover {
        transform: translateY(-6px) scale(1.02);
        border-color: var(--ob-border);
        box-shadow: 0 20px 60px var(--ob-shadow);
    }
    .ob-option-card.ob-scratch {
        --ob-glow: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(168,85,247,0.06) 100%);
        --ob-border: rgba(99,102,241,0.5);
        --ob-shadow: rgba(99,102,241,0.25);
    }
    .ob-option-card.ob-profile {
        --ob-glow: linear-gradient(135deg, rgba(16,185,129,0.08) 0%, rgba(6,182,212,0.06) 100%);
        --ob-border: rgba(16,185,129,0.5);
        --ob-shadow: rgba(16,185,129,0.25);
    }
    .ob-option-card.ob-clone {
        --ob-glow: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(239,68,68,0.06) 100%);
        --ob-border: rgba(245,158,11,0.5);
        --ob-shadow: rgba(245,158,11,0.25);
    }
    .ob-icon-wrap {
        width: 62px;
        height: 62px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        font-size: 1.8rem;
    }
    .ob-scratch .ob-icon-wrap { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .ob-profile .ob-icon-wrap { background: linear-gradient(135deg, #10b981, #06b6d4); }
    .ob-clone   .ob-icon-wrap { background: linear-gradient(135deg, #f59e0b, #ef4444); }
    .ob-icon-wrap i { color: white; }
    .ob-option-card h4 {
        color: #f1f5f9;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 0.6rem;
    }
    .ob-option-card p {
        color: #94a3b8;
        font-size: 0.88rem;
        line-height: 1.6;
        margin: 0;
    }
    .ob-badge {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        font-size: 0.7rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 700;
        letter-spacing: 0.04em;
    }
    .ob-scratch .ob-badge { background: rgba(99,102,241,0.2); color: #818cf8; }
    .ob-profile .ob-badge { background: rgba(16,185,129,0.2); color: #34d399; }
    .ob-clone   .ob-badge { background: rgba(245,158,11,0.2); color: #fbbf24; }
    .ob-arrow {
        margin-top: 1.5rem;
        display: flex;
        align-items: center;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
        transition: color 0.2s, gap 0.2s;
        gap: 6px;
    }
    .ob-option-card:hover .ob-arrow { color: #a5b4fc; gap: 10px; }
    .ob-clone .ob-option-card:hover .ob-arrow { color: #fbbf24; }
    /* Clone picker panel */
    #ob-clone-panel {
        display: none;
        margin-top: 2rem;
        background: rgba(255,255,255,0.04);
        border: 1.5px solid rgba(245,158,11,0.25);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(8px);
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity:0; transform:translateY(-10px); }
        to   { opacity:1; transform:translateY(0); }
    }
    #ob-clone-panel h6 {
        color: #fbbf24;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .clone-resume-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-radius: 12px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 0.75rem;
        transition: all 0.2s;
    }
    .clone-resume-item:hover {
        background: rgba(245,158,11,0.08);
        border-color: rgba(245,158,11,0.3);
    }
    .clone-resume-item .resume-name {
        color: #f1f5f9;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .clone-resume-item .resume-date {
        color: #64748b;
        font-size: 0.78rem;
        margin-top: 2px;
    }
    .clone-resume-item .btn-clone-pick {
        background: linear-gradient(135deg, #f59e0b, #ef4444);
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .clone-resume-item .btn-clone-pick:hover {
        opacity: 0.85;
        transform: scale(1.04);
        color: white;
    }
    .ob-no-resumes {
        text-align: center;
        color: #64748b;
        padding: 1.5rem;
        font-size: 0.9rem;
    }
    /* Profile CV card — disabled state when no file uploaded */
    .ob-option-card.ob-profile-disabled {
        opacity: 0.65;
        cursor: default;
    }
    .ob-option-card.ob-profile-disabled:hover {
        transform: none;
        box-shadow: none;
    }
    .ob-profile-no-cv {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 1rem;
        font-size: 0.8rem;
        color: #ef4444;
        font-weight: 600;
        background: rgba(239,68,68,0.1);
        padding: 5px 12px;
        border-radius: 8px;
        border: 1px solid rgba(239,68,68,0.25);
    }
    .ob-profile-has-cv {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 1rem;
        font-size: 0.8rem;
        color: #34d399;
        font-weight: 600;
        background: rgba(52,211,153,0.08);
        padding: 5px 12px;
        border-radius: 8px;
        border: 1px solid rgba(52,211,153,0.2);
    }
    .ob-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        font-size: 0.85rem;
        text-decoration: none;
        margin-top: 2.5rem;
        transition: color 0.2s;
    }
    .ob-back-link:hover { color: #94a3b8; }
</style>

<div class="onboarding-overlay" id="resumeOnboardingOverlay">
    <div class="onboarding-card-wrap">
        <!-- Header -->
        <div class="onboarding-header">
            <div class="badge-pill">
                <i class="ti ti-sparkles"></i>
                AI Resume Builder
            </div>
            <h2>How would you like to <span>get started?</span></h2>
            <p>Choose the best starting point for your new professional resume.</p>
        </div>

        <!-- Option Cards -->
        <div class="ob-options-grid">

            <!-- Card 1: Start from Scratch -->
            <div class="ob-option-card ob-scratch" id="ob-scratch-card" onclick="startFromScratch()">
                <span class="ob-badge">Quick Start</span>
                <div class="ob-icon-wrap">
                    <i class="ti ti-file-plus"></i>
                </div>
                <h4>Start from Scratch</h4>
                <p>Build a completely new resume with a clean slate. Ideal if you want full creative control from the ground up.</p>
                <div class="ob-arrow">
                    Get started <i class="ti ti-arrow-right"></i>
                </div>
            </div>

            <!-- Card 2: Import from Profile / Uploaded CV -->
            <?php $hasUploadedCv = !empty($candidate?->resume); ?>
            <div class="ob-option-card ob-profile <?= !$hasUploadedCv ? 'ob-profile-disabled' : '' ?>"
                 id="ob-profile-card"
                 <?php if ($hasUploadedCv): ?>onclick="importFromProfile()"<?php endif; ?>>
                <span class="ob-badge">Recommended</span>
                <div class="ob-icon-wrap">
                    <i class="ti ti-cloud-upload"></i>
                </div>
                <h4>Use Uploaded CV</h4>
                <p>Pre-fill your resume automatically using your profile information — skills, job title, education, and bio.</p>
                <?php if ($hasUploadedCv): ?>
                    <div class="ob-profile-has-cv">
                        <i class="ti ti-circle-check"></i> CV on file — ready to import
                    </div>
                <?php else: ?>
                    <div class="ob-profile-no-cv">
                        <i class="ti ti-alert-circle"></i> No CV uploaded yet —
                        <a href="<?= site_url('candidate/profile/edit') ?>" style="color:#f87171; font-weight:700;">Upload in Profile</a>
                    </div>
                <?php endif; ?>
                <div class="ob-arrow">
                    <?= $hasUploadedCv ? 'Import & Continue' : 'Upload first' ?> <i class="ti ti-arrow-right"></i>
                </div>
            </div>

            <!-- Card 3: Clone Existing Resume -->
            <div class="ob-option-card ob-clone" id="ob-clone-card" onclick="toggleClonePanel()">
                <span class="ob-badge">Fast Copy</span>
                <div class="ob-icon-wrap">
                    <i class="ti ti-copy"></i>
                </div>
                <h4>Clone Existing Resume</h4>
                <p>Duplicate one of your saved resumes and tailor it for a new opportunity without starting over.</p>
                <div class="ob-arrow">
                    Choose a resume <i class="ti ti-arrow-right"></i>
                </div>
            </div>
        </div>

        <!-- Clone Picker Panel (hidden by default) -->
        <div id="ob-clone-panel">
            <h6><i class="ti ti-copy"></i> Select a resume to clone</h6>
            <?php if (!empty($allResumes)): ?>
                <?php foreach ($allResumes as $r): ?>
                    <div class="clone-resume-item">
                        <div>
                            <div class="resume-name"><?= esc($r->title) ?></div>
                            <div class="resume-date">Last updated: <?= date('M d, Y', strtotime($r->updated_at)) ?></div>
                        </div>
                        <a href="<?= site_url('candidate/resumes/clone/' . $r->id) ?>" class="btn-clone-pick">
                            <i class="ti ti-copy me-1"></i>Clone This
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ob-no-resumes">
                    <i class="ti ti-file-off" style="font-size:2rem; display:block; margin-bottom:0.5rem; color:#475569;"></i>
                    You don't have any saved resumes to clone yet.
                </div>
            <?php endif; ?>
        </div>

        <!-- Back link -->
        <div class="text-center">
            <a href="<?= site_url('candidate/resumes') ?>" class="ob-back-link">
                <i class="ti ti-arrow-left"></i> Back to My Resumes
            </a>
        </div>
    </div>
</div>

<!-- Hidden form for importing from profile (POST) -->
<form id="import-profile-form" method="POST" action="<?= site_url('candidate/resumes/import-profile') ?>" style="display:none;">
    <?= csrf_field() ?>
</form>

<script>
    function startFromScratch() {
        // Close the overlay and let the builder load normally
        document.getElementById('resumeOnboardingOverlay').style.animation = 'fadeOutOverlay 0.3s ease forwards';
        setTimeout(() => {
            document.getElementById('resumeOnboardingOverlay').remove();
        }, 300);
    }

    function importFromProfile() {
        const card = document.getElementById('ob-profile-card');
        card.innerHTML = '<div style="text-align:center;padding:2rem;"><div class="spinner-border text-success" role="status"></div><p style="color:#94a3b8;margin-top:1rem;font-size:0.9rem;">Creating your resume from profile...</p></div>';
        document.getElementById('import-profile-form').submit();
    }

    function toggleClonePanel() {
        const panel = document.getElementById('ob-clone-panel');
        const isVisible = panel.style.display === 'block';
        panel.style.display = isVisible ? 'none' : 'block';
        document.getElementById('ob-clone-card').style.borderColor = isVisible ? '' : 'rgba(245,158,11,0.5)';
    }

    // Add fade-out keyframe dynamically
    const style = document.createElement('style');
    style.textContent = '@keyframes fadeOutOverlay { from { opacity:1; } to { opacity:0; } }';
    document.head.appendChild(style);
</script>
<!-- =================== END ONBOARDING MODAL =================== -->

<?php endif; ?>

<div class="content">
        <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">AI Resume Builder</h4>
            <h6>Design your professional resume with AI assistance</h6>
        </div>
        <div class="page-btn">
            <!-- Save button moved to bottom of builder for better flow -->
            <button type="button" id="undo-ai-apply" class="btn btn-outline-secondary me-2" title="Undo last AI apply">
                <i class="ti ti-rotate-ccw"></i> Undo AI
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Navigation Sidebar -->
        <div class="col-xl-3 col-lg-4 mb-4">
            <div class="card custom-card">
                <div class="card-body p-2">
                    <div class="builder-step-nav">
                        <div class="step-item active" data-step="info">
                            <i class="ti ti-user"></i> Basic Information
                        </div>
                        <div class="step-item" data-step="experience">
                            <i class="ti ti-briefcase"></i> Work Experience
                        </div>
                        <div class="step-item" data-step="education">
                            <i class="ti ti-school"></i> Education
                        </div>
                        <div class="step-item" data-step="skills">
                            <i class="ti ti-tool"></i> Skills
                        </div>
                        <div class="step-item" data-step="summary">
                            <i class="ti ti-file-description"></i> Professional Summary
                        </div>
                        <div class="step-item" data-step="templates">
                            <i class="ti ti-layout-template"></i> Choose Template
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Builder Form -->
        <div class="col-xl-9 col-lg-8">
            <form id="resume-form" onsubmit="return false;">
                <input type="hidden" name="id" value="<?= $resume->id ?? '' ?>">
                
                <!-- Step: Basic Information -->
                <div class="step-content" id="step-info">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold text-dark">Resume Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="<?= esc($resume->title ?? 'My Professional Resume') ?>" placeholder="e.g. Senior Software Engineer Resume">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" value="<?= esc(auth()->user()->username ?? '') ?>" placeholder="Your Full Name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="<?= esc(auth()->user()->email ?? '') ?>" placeholder="Your Email Address">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="e.g. +1 234 567 890">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">Location</label>
                                    <input type="text" name="location" class="form-control" placeholder="e.g. New York, USA">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NOTE: Professional Summary is intentionally shown after Experience/Education/Skills in the input flow
                     so AI generation can use the entered data. The summary will still appear at the top in exported resumes. -->

                <!-- Step: Experience -->
                <div class="step-content d-none" id="step-experience">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Work Experience</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary add-experience">
                                <i class="ti ti-plus"></i> Add Experience
                            </button>
                        </div>
                        <div class="card-body" id="experience-container">
                            <!-- Loop through and render existing experiences -->
                            <?php if (empty($experiences)): ?>
                                <div class="text-center py-4 text-muted no-items">
                                    <p>No experience added yet. Click "Add Experience" to start.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($experiences as $index => $exp): ?>
                                    <div class="experience-item border rounded p-3 mb-3 position-relative" style="background-color: #fcfcfd;">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Company Name</label>
                                                <input type="text" name="exp_company[]" class="form-control form-control-sm" placeholder="Company Name" value="<?= esc($exp->company ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Job Position</label>
                                                <input type="text" name="exp_position[]" class="form-control form-control-sm" placeholder="Job Position" value="<?= esc($exp->position ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Start Date</label>
                                                <input type="date" name="exp_start_date[]" class="form-control form-control-sm" value="<?= esc($exp->start_date ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3 exp-end-date-col" style="<?= !empty($exp->is_current) ? 'display: none;' : '' ?>">
                                                <label class="form-label small fw-semibold text-muted">End Date</label>
                                                <input type="date" name="exp_end_date[]" class="form-control form-control-sm" value="<?= esc($exp->end_date ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="<?= $index ?>" <?= !empty($exp->is_current) ? 'checked' : '' ?>>
                                                    <label class="form-check-label small fw-semibold text-muted">Currently Work Here</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="small fw-semibold text-muted">Description & Achievements</label>
                                                    <div>
                                                        <button type="button" class="ai-assist-btn improve-desc-ai">
                                                            <i class="ti ti-wand"></i> Improve with AI
                                                        </button>
                                                        <button type="button" class="ai-assist-btn generate-bullets-ai" style="margin-left:8px;">
                                                            <i class="ti ti-list"></i> Generate Bullets
                                                        </button>
                                                    </div>
                                                </div>
                                                <textarea name="exp_description[]" class="form-control form-control-sm" rows="3" placeholder="Describe your responsibilities and achievements..."><?= esc($exp->description ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Step: Education -->
                <div class="step-content d-none" id="step-education">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Education</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary add-education">
                                <i class="ti ti-plus"></i> Add Education
                            </button>
                        </div>
                        <div class="card-body" id="education-container">
                            <!-- Loop through and render existing education -->
                            <?php if (empty($education)): ?>
                                <div class="text-center py-4 text-muted no-items">
                                    <p>No education added yet. Click "Add Education" to start.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($education as $edu): ?>
                                    <div class="education-item border rounded p-3 mb-3 position-relative" style="background-color: #fcfcfd;">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">School / University</label>
                                                <input type="text" name="edu_school[]" class="form-control form-control-sm" placeholder="School / University" value="<?= esc($edu->institution ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Degree</label>
                                                <select name="edu_degree[]" class="form-select form-select-sm">
                                                    <option value="">Select Degree</option>
                                                    <option value="High School" <?= ($edu->degree ?? '') === 'High School' ? 'selected' : '' ?>>High School</option>
                                                    <option value="Associate" <?= ($edu->degree ?? '') === 'Associate' ? 'selected' : '' ?>>Associate Degree</option>
                                                    <option value="Bachelor" <?= ($edu->degree ?? '') === 'Bachelor' ? 'selected' : '' ?>>Bachelor's Degree</option>
                                                    <option value="Master" <?= ($edu->degree ?? '') === 'Master' ? 'selected' : '' ?>>Master's Degree</option>
                                                    <option value="PhD" <?= ($edu->degree ?? '') === 'PhD' ? 'selected' : '' ?>>PhD / Doctorate</option>
                                                    <option value="Certificate" <?= ($edu->degree ?? '') === 'Certificate' ? 'selected' : '' ?>>Certificate</option>
                                                    <option value="Other" <?= ($edu->degree ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="form-control form-control-sm" placeholder="Field of Study" value="<?= esc($edu->field_of_study ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Graduation Year</label>
                                                <?php 
                                                    $gradYear = !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '';
                                                ?>
                                                <input type="number" name="edu_year[]" class="form-control form-control-sm" placeholder="Graduation Year" min="1950" max="2030" value="<?= esc($gradYear) ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Step: Skills -->
                <div class="step-content d-none" id="step-skills">
                <div class="card custom-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Skills</h5>
                    </div>
                    <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Add Skills (Comma separated)</label>
                                <?php 
                                    $skillsList = [];
                                    if (!empty($skills)) {
                                        foreach ($skills as $skill) {
                                            $skillsList[] = $skill->skill_name;
                                        }
                                    }
                                    $skillsVal = implode(', ', $skillsList);
                                ?>
                                <input type="text" name="skills" class="form-control tags-input" value="<?= esc($skillsVal) ?>" placeholder="e.g. PHP, JavaScript, Project Management">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step: Summary (moved after Skills so AI can use experience/education/skills) -->
                <div class="step-content d-none" id="step-summary">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Professional Summary</h5>
                            <button type="button" class="ai-assist-btn" id="generate-summary-ai">
                                <i class="ti ti-sparkles"></i> Generate with AI
                            </button>
                        </div>
                        <div class="card-body">
                            <textarea name="summary" id="resume-summary" class="form-control" rows="6" placeholder="A brief overview of your professional background and key achievements..."><?= esc($resume->summary ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step: Choose World-Class Template -->
                <div class="step-content d-none" id="step-templates">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Choose Resume Template</h5>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="template_id" id="template_id" value="<?= esc($resume->template_id ?? 'classic') ?>">
                            <div class="row">
                                <!-- Classic Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? 'classic') === 'classic' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="classic">
                                        <div class="template-preview classic-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff;">
                                            <div style="height: 12px; background: #2563eb; width: 100%;"></div>
                                            <div class="p-2 text-start">
                                                <div style="height: 8px; background: #1e40af; width: 60%; margin-bottom: 6px;"></div>
                                                <div style="height: 4px; background: #cbd5e1; width: 80%; margin-bottom: 12px;"></div>
                                                <div style="height: 6px; background: #94a3b8; width: 40%; margin-bottom: 4px;"></div>
                                                <div style="height: 4px; background: #e2e8f0; width: 90%; margin-bottom: 4px;"></div>
                                                <div style="height: 4px; background: #e2e8f0; width: 85%; margin-bottom: 8px;"></div>
                                                <div style="height: 6px; background: #94a3b8; width: 35%; margin-bottom: 4px;"></div>
                                                <div style="height: 4px; background: #e2e8f0; width: 90%; margin-bottom: 4px;"></div>
                                            </div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Classic Professional</h6>
                                        <span class="text-muted small">Traditional & clean layout</span>
                                    </div>
                                </div>
                                
                                <!-- Modern Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? '') === 'modern' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="modern">
                                        <div class="template-preview modern-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff; display: flex;">
                                            <div style="width: 30%; background: #f8fafc; border-right: 1px solid #e2e8f0; height: 100%; padding: 8px 4px; box-sizing: border-box; text-align: left;">
                                                <div style="height: 6px; background: #3b82f6; width: 80%; margin-bottom: 6px;"></div>
                                                <div style="height: 3px; background: #cbd5e1; width: 90%; margin-bottom: 3px;"></div>
                                                <div style="height: 3px; background: #cbd5e1; width: 70%; margin-bottom: 12px;"></div>
                                                <div style="height: 6px; background: #3b82f6; width: 80%; margin-bottom: 6px;"></div>
                                                <div style="height: 3px; background: #cbd5e1; width: 85%; margin-bottom: 3px;"></div>
                                                <div style="height: 3px; background: #cbd5e1; width: 75%; margin-bottom: 3px;"></div>
                                            </div>
                                            <div style="width: 70%; padding: 8px; box-sizing: border-box; text-align: left;">
                                                <div style="height: 10px; background: #1e3a8a; width: 70%; margin-bottom: 4px;"></div>
                                                <div style="height: 4px; background: #3b82f6; width: 40%; margin-bottom: 10px;"></div>
                                                <div style="height: 6px; background: #94a3b8; width: 50%; margin-bottom: 4px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; width: 90%; margin-bottom: 3px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; width: 85%; margin-bottom: 3px;"></div>
                                            </div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Modern & Sleek</h6>
                                        <span class="text-muted small">Elegant double-column</span>
                                    </div>
                                </div>

                                <!-- Creative Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? '') === 'creative' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="creative">
                                        <div class="template-preview creative-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff;">
                                            <div style="background: #6b21a8; padding: 8px; text-align: left; height: 35px; box-sizing: border-box;">
                                                <div style="height: 8px; background: #ffffff; width: 50%; margin-bottom: 4px;"></div>
                                                <div style="height: 3px; background: #e9d5ff; width: 75%;"></div>
                                            </div>
                                            <div class="p-2 text-start">
                                                <div style="height: 6px; background: #6b21a8; width: 35%; margin-bottom: 6px; border-left: 2px solid #a855f7; padding-left: 2px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; width: 90%; margin-bottom: 3px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; width: 85%; margin-bottom: 12px;"></div>
                                                <div style="height: 6px; background: #6b21a8; width: 30%; margin-bottom: 6px; border-left: 2px solid #a855f7; padding-left: 2px;"></div>
                                                <span style="display: inline-block; height: 8px; background: #faf5ff; border: 1px solid #e9d5ff; width: 25%; margin-right: 2px; border-radius: 4px;"></span>
                                                <span style="display: inline-block; height: 8px; background: #faf5ff; border: 1px solid #e9d5ff; width: 20%; margin-right: 2px; border-radius: 4px;"></span>
                                                <span style="display: inline-block; height: 8px; background: #faf5ff; border: 1px solid #e9d5ff; width: 30%; border-radius: 4px;"></span>
                                            </div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Creative & Bold</h6>
                                        <span class="text-muted small">Stunning vibrant banner</span>
                                    </div>
                                </div>

                                <!-- Executive Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? '') === 'executive' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="executive">
                                        <div class="template-preview executive-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff;">
                                            <div class="p-2 text-center" style="border-bottom: 2px double #b45309;">
                                                <div style="height: 10px; background: #1e3a8a; width: 50%; margin: 0 auto 3px auto;"></div>
                                                <div style="height: 4px; background: #b45309; width: 30%; margin: 0 auto;"></div>
                                            </div>
                                            <div class="p-2 text-start">
                                                <div style="height: 5px; background: #1e3a8a; width: 40%; margin-bottom: 6px; border-bottom: 1px solid #1e3a8a;"></div>
                                                <div style="height: 4px; background: #334155; width: 90%; margin-bottom: 3px;"></div>
                                                <div style="height: 4px; background: #334155; width: 85%; margin-bottom: 10px;"></div>
                                                <div style="height: 5px; background: #1e3a8a; width: 45%; margin-bottom: 6px; border-bottom: 1px solid #1e3a8a;"></div>
                                                <div style="height: 4px; background: #334155; width: 88%; margin-bottom: 3px;"></div>
                                            </div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Executive Serif</h6>
                                        <span class="text-muted small">Centred stately design</span>
                                    </div>
                                </div>

                                <!-- Minimalist Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? '') === 'minimalist' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="minimalist">
                                        <div class="template-preview minimalist-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff; padding: 12px 10px; box-sizing: border-box; text-align: left;">
                                            <div style="height: 10px; background: #0f172a; width: 40%; margin-bottom: 2px;"></div>
                                            <div style="height: 4px; background: #94a3b8; width: 60%; margin-bottom: 10px;"></div>
                                            <div style="height: 1px; background: #f1f5f9; width: 100%; margin-bottom: 10px;"></div>
                                            <div style="height: 6px; background: #0f172a; width: 25%; margin-bottom: 6px;"></div>
                                            <div style="height: 3px; background: #475569; width: 90%; margin-bottom: 3px;"></div>
                                            <div style="height: 3px; background: #475569; width: 85%; margin-bottom: 8px;"></div>
                                            <div style="height: 6px; background: #0f172a; width: 30%; margin-bottom: 6px;"></div>
                                            <div style="height: 3px; background: #475569; width: 88%; margin-bottom: 3px;"></div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Minimalist Clean</h6>
                                        <span class="text-muted small">Sophisticated airy space</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Download Section -->
                            <div class="mt-4 pt-4 border-top text-center">
                                <h5 class="fw-bold text-dark mb-2">✨ Ready to apply? Download your resume!</h5>
                                <p class="text-muted small mb-4">Export your freshly built resume in your chosen template style. We save your progress automatically.</p>
                                
                                <div class="d-flex flex-wrap justify-content-center gap-3">
                                    <button type="button" class="btn btn-danger btn-lg px-4 py-3 fw-bold download-pdf-btn d-flex align-items-center shadow-sm transition-all" style="border-radius: 12px; font-size: 0.95rem;">
                                        <i class="ti ti-file-type-pdf me-2 fs-4"></i> Download Professional PDF
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg px-4 py-3 fw-bold download-docx-btn d-flex align-items-center shadow-sm transition-all" style="border-radius: 12px; font-size: 0.95rem; background: linear-gradient(135deg, var(--primary-color) 0%, #0b5ed7 100%); border: none;">
                                        <i class="ti ti-file-text me-2 fs-4"></i> Download Word (DOCX)
                                    </button>
                                </div>

                                <div class="mt-3 text-center">
                                    <div class="d-flex justify-content-center gap-2 align-items-center">
                                        <button id="save-resume-btn" class="btn btn-primary px-4 py-2 fw-semibold">
                                        <i class="ti ti-device-floppy me-1"></i> Save Resume
                                        </button>
                                        <button id="open-revisions-btn" class="btn btn-outline-secondary px-3 py-2" title="Revision History">
                                            <i class="ti ti-history me-1"></i> Revisions
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AI Modal Loader -->
<div class="modal fade" id="aiLoaderModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-5 border-0 bg-transparent">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
            <h4 class="text-white fw-bold">AI is generating content...</h4>
            <p class="text-white-50">Preparing your personalized professional text.</p>
        </div>
    </div>
</div>

<!-- AI Preview Modal -->
<div class="modal fade ai-preview-modal" id="aiPreviewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">AI Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ai-preview-render" id="aiPreviewRender">
        <!-- Rendered AI HTML will appear here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-outline-primary" id="aiCopyPlainBtn">Copy as Plain Text</button>
        <button type="button" class="btn btn-outline-info" id="aiApplyActiveBtn">Apply to Active Field</button>
        <button type="button" class="btn btn-primary" id="aiApplyBtn">Apply to Summary</button>
      </div>
    </div>
  </div>
</div>

<!-- AI Resume Coach FAB Trigger -->
<button type="button" class="ai-coach-fab" data-bs-toggle="offcanvas" data-bs-target="#aiResumeCoachDrawer" aria-controls="aiResumeCoachDrawer" id="open-ai-coach-btn">
    <div class="pulse-ring"></div>
    <i class="ti ti-sparkles"></i>
</button>

<!-- AI Resume Coach Offcanvas Sidebar -->
<div class="offcanvas offcanvas-end custom-coach-offcanvas" tabindex="-1" id="aiResumeCoachDrawer" aria-labelledby="aiResumeCoachDrawerLabel" data-bs-scroll="true" data-bs-backdrop="false">
    <div class="offcanvas-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-md bg-primary-transparent me-2" style="width: 35px; height: 35px; background: rgba(99, 102, 241, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="ti ti-sparkles text-primary fs-18"></i>
            </div>
            <div>
                <h5 class="offcanvas-title mb-0" id="aiResumeCoachDrawerLabel">ResumeAI Coach</h5>
                <span class="badge bg-success bg-opacity-20 text-success fs-10 fw-bold">Active Coaching</span>
            </div>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="coach-chat-container">
        <!-- Messages Log -->
        <div class="coach-messages-area" id="coach-chat-window">
            <div id="coach-chat-messages" class="d-flex flex-column gap-3">
                <!-- Loaded dynamically -->
            </div>
        </div>
        
        <!-- Input Form Area -->
        <div class="coach-input-area">
            <form id="coach-chat-form" onsubmit="return false;">
                <div class="coach-input-group">
                    <input type="text" id="coach-chat-input" class="coach-input-field" placeholder="Type your message to ResumeAI..." autocomplete="off">
                    <button class="coach-send-btn" type="submit" id="btn-coach-send">
                        <i class="ti ti-send"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->include('candidate/resume/partials/revisions_modal') ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Utility: escape HTML for safe insertion into preview
        function escapeHtml(str) {
            return String(str).replace(/[&<>"']/g, function (s) {
                return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s]);
            });
        }
        // Step Navigation
        $('.step-item').on('click', function() {
            const step = $(this).data('step');
            $('.step-item').removeClass('active');
            $(this).addClass('active');
            $('.step-content').addClass('d-none');
            $('#step-' + step).removeClass('d-none');
        });

        // Click Event for Template Choice
        $(document).on('click', '.template-choice', function() {
            $('.template-choice').removeClass('active');
            $(this).addClass('active');
            $('#template_id').val($(this).data('template'));
        });

        // AI Summary Generation
        $('#generate-summary-ai').on('click', function() {
            const experiences = [];
            const education = [];
            const skills = $('input[name="skills"]').val();

            // Extract experience details from inputs to build rich prompt
            $('.experience-item').each(function() {
                const company = $(this).find('input[name="exp_company[]"]').val();
                const position = $(this).find('input[name="exp_position[]"]').val();
                const desc = $(this).find('textarea[name="exp_description[]"]').val();
                if (company || position) {
                    experiences.push({ company, position, description: desc });
                }
            });

            // Collect education entries
            $('.education-item').each(function() {
                const school = $(this).find('input[name="edu_school[]"]').val();
                const degree = $(this).find('select[name="edu_degree[]"]').val();
                const field = $(this).find('input[name="edu_field[]"]').val();
                if (school || degree) {
                    education.push({ school, degree, field });
                }
            });

            if (experiences.length === 0 && !skills) {
                toastr.warning('Please add some experience or skills so AI can write a personalized summary.');
                return;
            }

            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/generate-summary") ?>',
                type: 'POST',
                data: {
                    experiences: experiences,
                    education: education,
                    skills: skills,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.summary) {
                        // Show preview modal with sanitized HTML (server already sanitized)
                        $('#aiPreviewRender').html(response.summary);
                        $('#aiPreviewModal').modal('show');
                        // store raw in the preview container for apply action
                        $('#aiPreviewRender').data('raw', response.summary);
                    } else {
                        toastr.error('AI returned no content.');
                    }
                    $('#aiLoaderModal').modal('hide');
                },
                error: function() {
                    toastr.error('AI generation failed. Please try again.');
                    $('#aiLoaderModal').modal('hide');
                }
            });
        });

        // Add Experience Item (Dynamic)
            $('.add-experience').on('click', function() {
            $('#experience-container .no-items').hide();
            
            // Generate next available index for exp_current value tracking
            const count = $('.experience-item').length;
            
            const html = `
                <div class="experience-item border rounded p-3 mb-3 position-relative" style="background-color: #fcfcfd; display: none;">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-muted">Company Name</label>
                            <input type="text" name="exp_company[]" class="form-control form-control-sm" placeholder="e.g. Google">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-muted">Job Position</label>
                            <input type="text" name="exp_position[]" class="form-control form-control-sm" placeholder="e.g. Senior Developer">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold text-muted">Start Date</label>
                            <input type="date" name="exp_start_date[]" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4 mb-3 exp-end-date-col">
                            <label class="form-label small fw-semibold text-muted">End Date</label>
                            <input type="date" name="exp_end_date[]" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="${count}">
                                <label class="form-check-label small fw-semibold text-muted">Currently Work Here</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="small fw-semibold text-muted">Description & Achievements</label>
                                <div>
                                    <button type="button" class="ai-assist-btn improve-desc-ai">
                                        <i class="ti ti-wand"></i> Improve with AI
                                    </button>
                                    <button type="button" class="ai-assist-btn generate-bullets-ai" style="margin-left:8px;">
                                        <i class="ti ti-list"></i> Generate Bullets
                                    </button>
                                </div>
                            </div>
                            <textarea name="exp_description[]" class="form-control form-control-sm" rows="3" placeholder="Describe your responsibilities and achievements..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            
            const $newItem = $(html);
            $('#experience-container').append($newItem);
            $newItem.slideDown(200);
        });

        // Add Education Item (Dynamic)
        $('.add-education').on('click', function() {
            $('#education-container .no-items').hide();
            
            const html = `
                <div class="education-item border rounded p-3 mb-3 position-relative" style="background-color: #fcfcfd; display: none;">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted">School / University</label>
                            <input type="text" name="edu_school[]" class="form-control form-control-sm" placeholder="School / University">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted">Degree</label>
                            <select name="edu_degree[]" class="form-select form-select-sm">
                                <option value="">Select Degree</option>
                                <option value="High School">High School</option>
                                <option value="Associate">Associate Degree</option>
                                <option value="Bachelor">Bachelor's Degree</option>
                                <option value="Master">Master's Degree</option>
                                <option value="PhD">PhD / Doctorate</option>
                                <option value="Certificate">Certificate</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted">Field of Study</label>
                            <input type="text" name="edu_field[]" class="form-control form-control-sm" placeholder="Field of Study">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted">Graduation Year</label>
                            <input type="number" name="edu_year[]" class="form-control form-control-sm" placeholder="Graduation Year" min="1950" max="2030">
                        </div>
                    </div>
                </div>
            `;
            
            const $newItem = $(html);
            $('#education-container').append($newItem);
            $newItem.slideDown(200);
        });

        // Dynamic deletion handler for items
        $(document).on('click', '.remove-item-btn', function() {
            const $item = $(this).closest('.experience-item, .education-item');
            const $container = $item.parent();
            
            $item.fadeOut(250, function() {
                $item.remove();
                if ($container.find('.experience-item, .education-item').length === 0) {
                    $container.find('.no-items').fadeIn(200);
                }
            });
        });

        // Dynamic change handler for 'Currently Work Here' checkbox
        $(document).on('change', '.exp-current-check', function() {
            const $endDateCol = $(this).closest('.row').find('.exp-end-date-col');
            if ($(this).is(':checked')) {
                $endDateCol.slideUp(200).find('input').val('');
            } else {
                $endDateCol.slideDown(200);
            }
        });

        // Improve Description with AI
        $(document).on('click', '.improve-desc-ai', function() {
            const textarea = $(this).closest('.col-md-12').find('textarea');
            const description = textarea.val();

            if (!description.trim()) {
                toastr.warning('Please enter a description first.');
                return;
            }

            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/improve-description") ?>',
                type: 'POST',
                data: {
                    description: description,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.description) {
                        // Show preview modal with suggested bullets or description
                        $('#aiPreviewRender').html(response.description.replace(/\n/g, '<br>'));
                        $('#aiPreviewRender').data('raw', response.description);
                        $('#aiPreviewModal').modal('show');
                    }
                    $('#aiLoaderModal').modal('hide');
                },
                error: function() {
                    toastr.error('AI improvement failed.');
                    $('#aiLoaderModal').modal('hide');
                }
            });
        });

        // Generate bullets for experience
        $(document).on('click', '.generate-bullets-ai', function() {
            const textarea = $(this).closest('.col-md-12').find('textarea');
            const description = textarea.val();
            const position = $(this).closest('.experience-item').find('input[name="exp_position[]"]').val() || '';

            if (!description.trim()) {
                toastr.warning('Please enter an experience description first.');
                return;
            }

            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/generate-bullets") ?>',
                type: 'POST',
                data: {
                    description: description,
                    job_title: position,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.bullets) {
                        // Show preview modal with bullets
                        // convert newlines to <li> list for better UX
                        const bulletsHtml = response.bullets.split(/\r?\n/).filter(Boolean).map(b => '<li>' + escapeHtml(b.trim()) + '</li>').join('');
                        const html = '<div class="ai-card"><h3>Suggested Bullets</h3><ul>' + bulletsHtml + '</ul></div>';
                        $('#aiPreviewRender').html(html);
                        $('#aiPreviewRender').data('raw', response.bullets);
                        $('#aiPreviewModal').modal('show');
                    }
                    $('#aiLoaderModal').modal('hide');
                },
                error: function() {
                    toastr.error('Failed to generate bullets.');
                    $('#aiLoaderModal').modal('hide');
                }
            });
        });

        // Save Resume
        $('#save-resume-btn').on('click', function() {
            // Clear temporary undo data on save
            $('#resume-summary').removeData('prev');
            $('textarea[name="exp_description[]"]').each(function() { $(this).removeData('prev'); });

            // Dynamically set checkbox values to their actual array index prior to serialization
            $('.experience-item').each(function(index) {
                $(this).find('.exp-current-check').val(index);
            });

            const formData = $('#resume-form').serialize();
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="ti ti-loader-2 spinner-border spinner-border-sm me-1"></i>Saving...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/save") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Resume saved successfully!');
                    if (response.id) {
                        $('input[name="id"]').val(response.id);
                    }
                    // If this save was kicked off by a restore+save, create a snapshot autosave for history
                    if (window.restoreSavePending) {
                        try {
                            // Post a snapshot to autosave endpoint
                            const snapshotPayload = $('#resume-form').serializeArray();
                            // Convert to structured snapshot similar to doAutosave
                            const snapshot = { experiences: [], education: [] };
                            snapshot.id = $('input[name="id"]').val() || null;
                            snapshot.title = $('input[name="title"]').val() || '';
                            snapshot.summary = $('#resume-summary').val() || '';
                            snapshot.template_id = $('#template_id').val() || 'classic';
                            snapshot.skills = $('input[name="skills"]').val() || '';

                            $('.experience-item').each(function() {
                                snapshot.experiences.push({
                                    company: $(this).find('input[name="exp_company[]"]').val() || '',
                                    position: $(this).find('input[name="exp_position[]"]').val() || '',
                                    description: $(this).find('textarea[name="exp_description[]"]').val() || '',
                                    start_date: $(this).find('input[name="exp_start_date[]"]').val() || '',
                                    end_date: $(this).find('input[name="exp_end_date[]"]').val() || '',
                                    is_current: $(this).find('.exp-current-check').is(':checked') ? 1 : 0
                                });
                            });

                            $('.education-item').each(function() {
                                snapshot.education.push({
                                    institution: $(this).find('input[name="edu_school[]"]').val() || '',
                                    degree: $(this).find('select[name="edu_degree[]"]').val() || '',
                                    field_of_study: $(this).find('input[name="edu_field[]"]').val() || '',
                                    graduation_year: $(this).find('input[name="edu_year[]"]').val() || ''
                                });
                            });

                            $.ajax({
                                url: '<?= site_url("candidate/resumes/autosave") ?>',
                                type: 'POST',
                                data: { snapshot: JSON.stringify(snapshot), id: snapshot.id, <?= csrf_token() ?>: '<?= csrf_hash() ?>' }
                            });
                        } catch (e) {
                            // ignore autosave snapshot errors
                            console.error('snapshot error', e);
                        }
                        window.restoreSavePending = false;
                        // Visual confirmation for restore+save
                        toastr.success('Revision restored and saved successfully.');
                    }
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>Save Resume');
                },
                error: function() {
                    toastr.error('Failed to save resume.');
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>Save Resume');
                }
            });
        });

        // Autosave: debounce per-field and periodic full autosave
        let autosaveTimer = null;
        let debounceTimers = new Map();
        const AUTOSAVE_INTERVAL = 30000; // 30s
        const FIELD_DEBOUNCE = 500; // 500ms

        function scheduleAutosave() {
            if (autosaveTimer) clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(doAutosave, AUTOSAVE_INTERVAL);
        }

        function doAutosave() {
            const form = $('#resume-form');
            // Build structured snapshot JSON from current form state
            const snapshot = {
                id: $('input[name="id"]').val() || null,
                title: $('input[name="title"]').val() || '',
                summary: $('#resume-summary').val() || '',
                template_id: $('#template_id').val() || 'classic',
                experiences: [],
                education: [],
                skills: $('input[name="skills"]').val() || ''
            };

            $('.experience-item').each(function() {
                snapshot.experiences.push({
                    company: $(this).find('input[name="exp_company[]"]').val() || '',
                    position: $(this).find('input[name="exp_position[]"]').val() || '',
                    description: $(this).find('textarea[name="exp_description[]"]').val() || '',
                    start_date: $(this).find('input[name="exp_start_date[]"]').val() || '',
                    end_date: $(this).find('input[name="exp_end_date[]"]').val() || '',
                    is_current: $(this).find('.exp-current-check').is(':checked') ? 1 : 0
                });
            });

            $('.education-item').each(function() {
                snapshot.education.push({
                    institution: $(this).find('input[name="edu_school[]"]').val() || '',
                    degree: $(this).find('select[name="edu_degree[]"]').val() || '',
                    field_of_study: $(this).find('input[name="edu_field[]"]').val() || '',
                    graduation_year: $(this).find('input[name="edu_year[]"]').val() || ''
                });
            });

            $.ajax({
                url: '<?= site_url("candidate/resumes/autosave") ?>',
                type: 'POST',
                data: {
                    snapshot: JSON.stringify(snapshot),
                    id: $('input[name="id"]').val(),
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(resp) {
                    const ts = new Date().toLocaleTimeString();
                    $('#autosave-indicator').remove();
                    $('.builder-header').append('<span id="autosave-indicator" class="text-muted ms-3" style="font-size:12px;">Autosaved at ' + ts + '</span>');
                }
            });
        }

        // Track per-field changes
        $(document).on('input change', '#resume-form input, #resume-form textarea, #resume-form select', function() {
            const el = this;
            const key = $(el).attr('name') || $(el).attr('id') || Date.now();
            if (debounceTimers.has(key)) clearTimeout(debounceTimers.get(key));
            debounceTimers.set(key, setTimeout(function() {
                scheduleAutosave();
                debounceTimers.delete(key);
            }, FIELD_DEBOUNCE));
        });

        // Also autosave on page unload
        $(window).on('beforeunload', function() {
            // synchronous navigator sendBeacon unavailable for form data; attempt quick ajax
            navigator.sendBeacon && navigator.sendBeacon('<?= site_url("candidate/resumes/autosave") ?>', new URLSearchParams({
                id: $('input[name="id"]').val() || '',
                payload: $('#resume-form').serialize() || ''
            }));
        });

        // Utility escapeHtml is defined earlier; ensure it's available for template building

        // Revision History UI: open modal and load recent autosaves
        $('#open-revisions-btn').on('click', function() {
            const resumeId = $('input[name="id"]').val();
            if (!resumeId) {
                toastr.info('Please save your resume once to enable revisions.');
                return;
            }

            $('#revisions-list').html('<div class="text-muted">Loading revisions...</div>');
            $('#revisionsModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/") ?>' + resumeId + '/autosaves',
                type: 'GET',
                success: function(resp) {
                    if (!resp.autosaves || resp.autosaves.length === 0) {
                        $('#revisions-list').html('<div class="text-muted">No revisions found.</div>');
                        return;
                    }

                    const items = resp.autosaves.map(function(a) {
                        const created = new Date(a.created_at).toLocaleString();
                        const summ = a.preview && a.preview.summary ? a.preview.summary : '';
                        const exps = a.preview && a.preview.experiences ? a.preview.experiences.map(e => (e.position || '') + (e.company ? ' at ' + e.company : '')).join('; ') : '';
                        const previewHtml = '<div class="fw-semibold">' + created + '</div>' +
                            (summ ? '<div class="text-muted small mt-1">' + escapeHtml(summ) + '</div>' : '') +
                            (exps ? '<div class="text-muted small mt-1"><strong>Experiences:</strong> ' + escapeHtml(exps) + '</div>' : '');

                        return `<div class="revision-item border rounded p-2 mb-2 d-flex justify-content-between align-items-start">
                            <div style="max-width: 75%;">
                                ${previewHtml}
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary restore-autosave-btn" data-id="${a.id}">Restore</button>
                                <button class="btn btn-sm btn-primary restore-save-autosave-btn" data-id="${a.id}">Restore & Save</button>
                            </div>
                        </div>`;
                    }).join('');

                    $('#revisions-list').html(items);
                },
                error: function() {
                    $('#revisions-list').html('<div class="text-danger">Failed to load revisions.</div>');
                }
            });
        });

        // Restore autosave from revisions modal (structured snapshot restore)
        $(document).on('click', '.restore-autosave-btn', function() {
            const autosaveId = $(this).data('id');
            const resumeId = $('input[name="id"]').val();
            if (!resumeId) return;

            const btn = $(this);
            btn.prop('disabled', true).text('Restoring...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/") ?>' + resumeId + '/restore-autosave',
                type: 'POST',
                data: {
                    autosave_id: autosaveId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(resp) {
                    if (resp.payload) {
                        // load structured JSON snapshot into form and reconstruct repeated groups
                        const snap = resp.payload;
                        if (snap.title !== undefined) $('input[name="title"]').val(snap.title);
                        if (snap.summary !== undefined) $('#resume-summary').val(snap.summary);
                        if (snap.template_id !== undefined) $('#template_id').val(snap.template_id);
                        if (snap.skills !== undefined) $('input[name="skills"]').val(snap.skills);

                        // Rebuild experiences section
                        const $expContainer = $('#experience-container');
                        $expContainer.find('.experience-item').remove();
                        if (Array.isArray(snap.experiences)) {
                            snap.experiences.forEach(function(e) {
                                const html = `
                                    <div class="experience-item border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Company Name</label>
                                                <input type="text" name="exp_company[]" class="form-control form-control-sm" value="${escapeHtml(e.company || '')}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Job Position</label>
                                                <input type="text" name="exp_position[]" class="form-control form-control-sm" value="${escapeHtml(e.position || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Start Date</label>
                                                <input type="date" name="exp_start_date[]" class="form-control form-control-sm" value="${escapeHtml(e.start_date || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3 exp-end-date-col">
                                                <label class="form-label small fw-semibold text-muted">End Date</label>
                                                <input type="date" name="exp_end_date[]" class="form-control form-control-sm" value="${escapeHtml(e.end_date || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" ${e.is_current ? 'checked' : ''}>
                                                    <label class="form-check-label small fw-semibold text-muted">Currently Work Here</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="small fw-semibold text-muted">Description & Achievements</label>
                                                    <div>
                                                        <button type="button" class="ai-assist-btn improve-desc-ai">
                                                            <i class="ti ti-wand"></i> Improve with AI
                                                        </button>
                                                        <button type="button" class="ai-assist-btn generate-bullets-ai" style="margin-left:8px;">
                                                            <i class="ti ti-list"></i> Generate Bullets
                                                        </button>
                                                    </div>
                                                </div>
                                                <textarea name="exp_description[]" class="form-control form-control-sm" rows="3" placeholder="Describe your responsibilities and achievements...">${escapeHtml(e.description || '')}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $expContainer.append(html);
                            });
                        }

                        // Rebuild education section
                        const $eduContainer = $('#education-container');
                        $eduContainer.find('.education-item').remove();
                        if (Array.isArray(snap.education)) {
                            snap.education.forEach(function(ed) {
                                const html = `
                                    <div class="education-item border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">School / University</label>
                                                <input type="text" name="edu_school[]" class="form-control form-control-sm" value="${escapeHtml(ed.institution || '')}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Degree</label>
                                                <select name="edu_degree[]" class="form-select form-select-sm">
                                                    <option value="">Select Degree</option>
                                                    <option value="High School" ${ed.degree === 'High School' ? 'selected' : ''}>High School</option>
                                                    <option value="Associate" ${ed.degree === 'Associate' ? 'selected' : ''}>Associate Degree</option>
                                                    <option value="Bachelor" ${ed.degree === 'Bachelor' ? 'selected' : ''}>Bachelor's Degree</option>
                                                    <option value="Master" ${ed.degree === 'Master' ? 'selected' : ''}>Master's Degree</option>
                                                    <option value="PhD" ${ed.degree === 'PhD' ? 'selected' : ''}>PhD / Doctorate</option>
                                                    <option value="Certificate" ${ed.degree === 'Certificate' ? 'selected' : ''}>Certificate</option>
                                                    <option value="Other" ${ed.degree === 'Other' ? 'selected' : ''}>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="form-control form-control-sm" value="${escapeHtml(ed.field_of_study || '')}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Graduation Year</label>
                                                <input type="number" name="edu_year[]" class="form-control form-control-sm" value="${escapeHtml(ed.graduation_year || '')}" min="1950" max="2030">
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $eduContainer.append(html);
                            });
                        }

                        toastr.success('Revision restored into the form. Please review changes and Save to persist.');
                        $('#revisionsModal').modal('hide');
                    } else {
                        toastr.error('Invalid autosave payload');
                    }
                },
                error: function() {
                    toastr.error('Failed to restore revision.');
                    btn.prop('disabled', false).text('Restore');
                }
            });
        });

        // Restore & Save action
        $(document).on('click', '.restore-save-autosave-btn', function() {
            const autosaveId = $(this).data('id');
            const resumeId = $('input[name="id"]').val();
            if (!resumeId) return;

            const btn = $(this);
            btn.prop('disabled', true).text('Restoring...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/") ?>' + resumeId + '/restore-autosave',
                type: 'POST',
                data: {
                    autosave_id: autosaveId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(resp) {
                    if (resp.payload) {
                        const snap = resp.payload;
                        if (snap.title !== undefined) $('input[name="title"]').val(snap.title);
                        if (snap.summary !== undefined) $('#resume-summary').val(snap.summary);
                        if (snap.template_id !== undefined) $('#template_id').val(snap.template_id);
                        if (snap.skills !== undefined) $('input[name="skills"]').val(snap.skills);

                        // Rebuild experiences and education same as restore
                        const $expContainer = $('#experience-container');
                        $expContainer.find('.experience-item').remove();
                        if (Array.isArray(snap.experiences)) {
                            snap.experiences.forEach(function(e) {
                                const html = `
                                    <div class="experience-item border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Company Name</label>
                                                <input type="text" name="exp_company[]" class="form-control form-control-sm" value="${escapeHtml(e.company || '')}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Job Position</label>
                                                <input type="text" name="exp_position[]" class="form-control form-control-sm" value="${escapeHtml(e.position || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Start Date</label>
                                                <input type="date" name="exp_start_date[]" class="form-control form-control-sm" value="${escapeHtml(e.start_date || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3 exp-end-date-col">
                                                <label class="form-label small fw-semibold text-muted">End Date</label>
                                                <input type="date" name="exp_end_date[]" class="form-control form-control-sm" value="${escapeHtml(e.end_date || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" ${e.is_current ? 'checked' : ''}>
                                                    <label class="form-check-label small fw-semibold text-muted">Currently Work Here</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="small fw-semibold text-muted">Description & Achievements</label>
                                                    <div>
                                                        <button type="button" class="ai-assist-btn improve-desc-ai">
                                                            <i class="ti ti-wand"></i> Improve with AI
                                                        </button>
                                                        <button type="button" class="ai-assist-btn generate-bullets-ai" style="margin-left:8px;">
                                                            <i class="ti ti-list"></i> Generate Bullets
                                                        </button>
                                                    </div>
                                                </div>
                                                <textarea name="exp_description[]" class="form-control form-control-sm" rows="3" placeholder="Describe your responsibilities and achievements...">${escapeHtml(e.description || '')}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $expContainer.append(html);
                            });
                        }

                        const $eduContainer = $('#education-container');
                        $eduContainer.find('.education-item').remove();
                        if (Array.isArray(snap.education)) {
                            snap.education.forEach(function(ed) {
                                const html = `
                                    <div class="education-item border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">School / University</label>
                                                <input type="text" name="edu_school[]" class="form-control form-control-sm" value="${escapeHtml(ed.institution || '')}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Degree</label>
                                                <select name="edu_degree[]" class="form-select form-select-sm">
                                                    <option value="">Select Degree</option>
                                                    <option value="High School" ${ed.degree === 'High School' ? 'selected' : ''}>High School</option>
                                                    <option value="Associate" ${ed.degree === 'Associate' ? 'selected' : ''}>Associate Degree</option>
                                                    <option value="Bachelor" ${ed.degree === 'Bachelor' ? 'selected' : ''}>Bachelor's Degree</option>
                                                    <option value="Master" ${ed.degree === 'Master' ? 'selected' : ''}>Master's Degree</option>
                                                    <option value="PhD" ${ed.degree === 'PhD' ? 'selected' : ''}>PhD / Doctorate</option>
                                                    <option value="Certificate" ${ed.degree === 'Certificate' ? 'selected' : ''}>Certificate</option>
                                                    <option value="Other" ${ed.degree === 'Other' ? 'selected' : ''}>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="form-control form-control-sm" value="${escapeHtml(ed.field_of_study || '')}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Graduation Year</label>
                                                <input type="number" name="edu_year[]" class="form-control form-control-sm" value="${escapeHtml(ed.graduation_year || '')}" min="1950" max="2030">
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $eduContainer.append(html);
                            });
                        }

                        // After rebuilding, trigger save (and snapshot)
                        $('#revisionsModal').modal('hide');
                        // Flag to let save flow create a snapshot
                        window.restoreSavePending = true;
                        // Trigger save - save handler will check restoreSavePending
                        $('#save-resume-btn').trigger('click');
                    } else {
                        toastr.error('Invalid autosave payload');
                        btn.prop('disabled', false).text('Restore');
                    }
                },
                error: function() {
                    toastr.error('Failed to restore revision.');
                    btn.prop('disabled', false).text('Restore');
                }
            });
        });

        // Helper: Save resume before download
        function saveResumeBeforeDownload(onSuccess) {
            $('.experience-item').each(function(index) {
                $(this).find('.exp-current-check').val(index);
            });

            const formData = $('#resume-form').serialize();

            $.ajax({
                url: '<?= site_url("candidate/resumes/save") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.id) {
                        $('input[name="id"]').val(response.id);
                        onSuccess(response.id);
                    } else {
                        const existingId = $('input[name="id"]').val();
                        if (existingId) {
                            onSuccess(existingId);
                        } else {
                            toastr.error('Could not determine resume ID for download.');
                        }
                    }
                },
                error: function() {
                    toastr.error('Failed to save latest changes. Trying to download anyway...');
                    const existingId = $('input[name="id"]').val();
                    if (existingId) {
                        onSuccess(existingId);
                    } else {
                        toastr.error('Please save your resume first.');
                    }
                }
            });
        }

        // PDF Download Click Handler
        $(document).on('click', '.download-pdf-btn', function() {
            const btn = $(this);
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="ti ti-loader-2 spinner-border spinner-border-sm me-2"></i>Preparing PDF...');
            
            saveResumeBeforeDownload(function(id) {
                btn.prop('disabled', false).html(originalHtml);
                window.location.href = '<?= site_url("candidate/resumes/download/") ?>' + id;
            });
        });

        // DOCX Download Click Handler
        $(document).on('click', '.download-docx-btn', function() {
            const btn = $(this);
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="ti ti-loader-2 spinner-border spinner-border-sm me-2"></i>Preparing Word...');
            
            saveResumeBeforeDownload(function(id) {
                btn.prop('disabled', false).html(originalHtml);
                window.location.href = '<?= site_url("candidate/resumes/download-docx/") ?>' + id;
            });
        });

        // ==========================================
        // AI RESUME COACH DRAWER INTEGRATION
        // ==========================================
        let coachHistory = [];
        let lastFocusedTextarea = null;

        // Keep track of focused inputs in the builder form to paste content
        $(document).on('focus', '#resume-form textarea, #resume-form input[type="text"]', function() {
            lastFocusedTextarea = $(this);
        });

        // Toggle / show coach offcanvas event
        $('#aiResumeCoachDrawer').on('shown.bs.offcanvas', function () {
            if ($('#coach-chat-messages').children().length === 0) {
                // Seed initial message from AI Coach (plain text, no markdown)
                showCoachMessage('coach', 'Hello, I am ResumeAI, your resume consultant. To get started, what is your target role and industry?');
            }
        });

        // Submit message form
        $('#coach-chat-form').on('submit', function(e) {
            e.preventDefault();
            sendCoachMessage();
        });

        function sendCoachMessage() {
            const inputField = $('#coach-chat-input');
            const message = inputField.val().trim();
            if (!message) return;

            // Append user bubble
            showCoachMessage('user', message);
            inputField.val('');

            // Append typing indicator
            showTypingIndicator();

            // Send AJAX request
            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/chat") ?>',
                type: 'POST',
                data: {
                    message: message,
                    history: JSON.stringify(coachHistory),
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    removeTypingIndicator();
                    if (response.reply) {
                        showCoachMessage('coach', response.reply);
                        // Save in local history array
                        coachHistory.push({sender: 'user', message: message});
                        coachHistory.push({sender: 'model', message: response.reply});
                    } else {
                        showCoachMessage('coach', 'I experienced an issue parsing the coaching response. Let\'s continue our session.');
                    }
                },
                error: function() {
                    removeTypingIndicator();
                    showCoachMessage('coach', 'Sorry, I am having trouble connecting right now. Let\'s continue.');
                }
            });
        }

        function showCoachMessage(sender, text) {
            const container = $('#coach-chat-messages');
            
            // Helper to escape HTML for user messages
            function escapeHtml(str) {
                return String(str).replace(/[&<>"']/g, function (s) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s]);
                });
            }

            // For coach messages we accept simple HTML from the server (server sanitizes). For user messages escape HTML.
            let formattedText;
            if (sender === 'coach') {
                // preserve simple HTML returned by server; normalize line endings
                formattedText = String(text).replace(/\r/g, '');
            } else {
                formattedText = escapeHtml(text).replace(/\n\n/g, '<br><br>').replace(/\n/g, '<br>');
            }

            const bubbleId = 'bubble-' + Date.now();
            let html = `
                <div class="coach-bubble ${sender}" id="${bubbleId}">
                    <div>${formattedText}</div>
            `;

            // If coach, add action pasting toolbar helpers
            if (sender === 'coach') {
                html += `
                    <div class="mt-2 d-flex flex-wrap gap-1 border-top border-secondary border-opacity-10 pt-2">
                        <button type="button" class="coach-apply-btn apply-to-summary-btn" data-text-id="${bubbleId}-text" style="font-size: 10px; padding: 3px 8px; border-radius: 12px;">
                            <i class="ti ti-blockquote me-1"></i> Apply to Summary
                        </button>
                        <button type="button" class="coach-apply-btn apply-to-active-btn" data-text-id="${bubbleId}-text" style="font-size: 10px; padding: 3px 8px; border-radius: 12px;">
                            <i class="ti ti-edit me-1"></i> Apply to Active Field
                        </button>
                    </div>
                `;
            }

            html += `</div>`;
            container.append(html);
            
            // Store raw text in a hidden element inside the bubble for precise extraction
            if (sender === 'coach') {
                $(`#${bubbleId}`).append(`<div id="${bubbleId}-text" style="display:none;"></div>`);
                // Use jQuery data to keep raw payload (may include HTML)
                $(`#${bubbleId}-text`).data('raw', text);
            }

            // Scroll chat to bottom
            const chatWindow = document.getElementById('coach-chat-window');
            if (chatWindow) {
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }
        }

        function showTypingIndicator() {
            removeTypingIndicator();
            const container = $('#coach-chat-messages');
            const html = `
                <div class="coach-bubble coach typing-indicator-bubble align-self-start" id="coach-typing-indicator" style="background-color: #1e293b; border: 1px solid #334155; border-top-left-radius: 4px; max-width: 85%;">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            `;
            container.append(html);
            const chatWindow = document.getElementById('coach-chat-window');
            if (chatWindow) {
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }
        }

        function removeTypingIndicator() {
            $('#coach-typing-indicator').remove();
        }

        // Apply to Professional Summary action handler
        $(document).on('click', '.apply-to-summary-btn', function() {
            const textId = $(this).data('text-id');
            const $hidden = $('#' + textId);
            const rawText = $hidden.length && $hidden.data('raw') ? $hidden.data('raw') : $hidden.text();
            const polishedText = extractResumeContent(rawText);

            // Store previous value for undo
            const prev = $('#resume-summary').val();
            $('#resume-summary').data('prev', prev);

            $('#resume-summary').val(polishedText);
            toastr.success('Applied to Professional Summary!');
            
            // Scroll to the professional summary element
            $('html, body').animate({
                scrollTop: $("#resume-summary").offset().top - 120
            }, 300);
        });

        // Apply to Active/Last Focused text input or textarea
        $(document).on('click', '.apply-to-active-btn', function() {
            const textId = $(this).data('text-id');
            const $hidden = $('#' + textId);
            const rawText = $hidden.length && $hidden.data('raw') ? $hidden.data('raw') : $hidden.text();
            const polishedText = extractResumeContent(rawText);

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                // store previous for undo
                lastFocusedTextarea.data('prev', lastFocusedTextarea.val());
                lastFocusedTextarea.val(polishedText);
                toastr.success('Applied to the active input field!');
                
                // Focus it and flash it
                lastFocusedTextarea.focus();
                lastFocusedTextarea.css('border-color', '#6366f1');
                setTimeout(function() {
                    lastFocusedTextarea.css('border-color', '');
                }, 1000);
            } else {
                // Fallback to first work experience description block
                const firstExpDesc = $('textarea[name="exp_description[]"]').first();
                if (firstExpDesc.length > 0) {
                    // store previous for undo
                    firstExpDesc.data('prev', firstExpDesc.val());
                    firstExpDesc.val(polishedText);
                    toastr.info('No active input was selected. Applied to first work experience description.');
                    
                    $('html, body').animate({
                        scrollTop: firstExpDesc.offset().top - 120
                    }, 300);
                    firstExpDesc.focus();
                } else {
                    // Otherwise default to summary
                    $('#resume-summary').val(polishedText);
                    toastr.info('No active input was selected. Applied to Professional Summary.');
                    
                    $('html, body').animate({
                        scrollTop: $("#resume-summary").offset().top - 120
                    }, 300);
                }
            }
        });

        // Utility: Extract and clean raw markdown or blockquoted suggestions inside AI messages
        function extractResumeContent(text) {
            let extracted = text;
            
            // 1. Extract content from code block if present
            const codeBlockRegex = /```(?:[a-zA-Z]+)?\n([\s\S]+?)\n```/;
            const codeMatch = text.match(codeBlockRegex);
            if (codeMatch && codeMatch[1]) {
                extracted = codeMatch[1];
            } else {
                // 2. Extract blockquote block if present
                const quoteRegex = /(?:^|\n)>\s*([\s\S]+?)(?:\n\n|\n$|$)/;
                const quoteMatch = text.match(quoteRegex);
                if (quoteMatch && quoteMatch[1]) {
                    extracted = quoteMatch[1];
                }
            }
            
            // Strip any remaining markdown markers for clean resume placement
            return extracted
                .replace(/^>\s*/gm, '') // remove leading blockquote carrots
                .replace(/[*#`]/g, '')  // strip asterisks, pound headers, and backticks
                .trim();
        }

        // AI Preview modal actions
        $('#aiApplyBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').html();
            const polished = extractResumeContent(raw);
            // store prev for undo
            const prev = $('#resume-summary').val();
            $('#resume-summary').data('prev', prev);
            $('#resume-summary').val(polished);
            $('#aiPreviewModal').modal('hide');
            toastr.success('Applied AI content to Professional Summary');
        });

        $('#aiCopyPlainBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text();
            const plain = extractResumeContent(raw);
            navigator.clipboard.writeText(plain).then(function() {
                toastr.success('Copied plain text to clipboard');
            }, function() {
                toastr.info('Copy failed — you can manually copy from the preview.');
            });
        });

        // Apply preview content to last-focused input/textarea (or reasonable fallback)
        $('#aiApplyActiveBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text();
            const polished = extractResumeContent(raw);

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                // store previous for undo
                lastFocusedTextarea.data('prev', lastFocusedTextarea.val());
                lastFocusedTextarea.val(polished);
                $('#aiPreviewModal').modal('hide');
                toastr.success('Applied to the active input field!');
                lastFocusedTextarea.focus();
                lastFocusedTextarea.css('border-color', '#6366f1');
                setTimeout(function() { lastFocusedTextarea.css('border-color', ''); }, 1000);
                return;
            }

            // Fallback to first work experience description
            const firstExpDesc = $('textarea[name="exp_description[]"]').first();
            if (firstExpDesc.length > 0) {
                firstExpDesc.data('prev', firstExpDesc.val());
                firstExpDesc.val(polished);
                $('#aiPreviewModal').modal('hide');
                toastr.info('No active input was selected. Applied to first work experience description.');
                $('html, body').animate({ scrollTop: firstExpDesc.offset().top - 120 }, 300);
                firstExpDesc.focus();
                return;
            }

            // Otherwise default to summary
            const prev = $('#resume-summary').val();
            $('#resume-summary').data('prev', prev);
            $('#resume-summary').val(polished);
            $('#aiPreviewModal').modal('hide');
            toastr.info('No active input was selected. Applied to Professional Summary.');
            $('html, body').animate({ scrollTop: $("#resume-summary").offset().top - 120 }, 300);
        });

        // Undo last AI apply for summary or focused field
        $(document).on('click', '#undo-ai-apply', function() {
            const $summary = $('#resume-summary');
            const prev = $summary.data('prev');
            if (typeof prev !== 'undefined') {
                $summary.val(prev);
                $summary.removeData('prev');
                toastr.success('Undo applied');
                return;
            }

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                const prevField = lastFocusedTextarea.data('prev');
                if (typeof prevField !== 'undefined') {
                    lastFocusedTextarea.val(prevField);
                    lastFocusedTextarea.removeData('prev');
                    toastr.success('Undo applied to active field');
                    return;
                }
            }

            toastr.info('Nothing to undo');
        });
    });
</script>
<?= $this->endSection() ?>
