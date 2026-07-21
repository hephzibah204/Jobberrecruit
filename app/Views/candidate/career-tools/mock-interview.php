<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* ── Mock Interview Control Center Styles ── */
.studio-hero {
    background: linear-gradient(135deg, var(--brand-deep), #0d1b30);
    border-radius: 14px;
    padding: clamp(24px, 4vw, 40px);
    color: #fff;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.hero-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 24px;
    align-items: center;
}
.hero-badges {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}
.hb {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    text-transform: uppercase;
}
.hb--premium {
    background: #ffd700;
    color: #000;
}
.hb--live {
    background: rgba(255,255,255,.1);
    color: #fff;
}
.hb--live .pulse {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    display: inline-block;
    animation: pulse-ring 1.5s infinite;
}
.studio-hero h1 {
    font-family: 'Sora', sans-serif;
    font-weight: 800;
    font-size: clamp(1.8rem, 4vw, 2.5rem);
    color: #fff;
    margin-bottom: 10px;
}
.studio-hero h1 span {
    color: var(--accent);
}
.hero-sub {
    font-size: .94rem;
    color: rgba(255,255,255,.8);
    max-width: 540px;
    line-height: 1.5;
}
.hero-chips {
    display: flex;
    gap: 12px;
    margin-top: 20px;
    flex-wrap: wrap;
}
.hchip {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 10px;
    padding: 10px 14px;
    min-width: 140px;
}
.hchip-lbl {
    font-size: .68rem;
    color: rgba(255,255,255,.6);
    display: flex;
    align-items: center;
    gap: 4px;
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 4px;
}
.hchip-lbl svg {
    width: 12px;
    height: 12px;
}
.hchip-val {
    font-size: .9rem;
    font-weight: 700;
}
.hchip-val small {
    font-weight: 400;
    opacity: .7;
    font-size: .74rem;
}
.hero-orb {
    display: flex;
    justify-content: center;
    align-items: center;
}
.hero-orb svg {
    width: 180px;
    height: 180px;
}
.orb-ring {
    transform-origin: center;
    animation: rotate-ring 12s linear infinite;
}
.orb-ring--2 {
    animation: rotate-ring-reverse 8s linear infinite;
}
@keyframes rotate-ring {
    to { transform: rotate(360deg); }
}
@keyframes rotate-ring-reverse {
    to { transform: rotate(-360deg); }
}
@keyframes pulse-ring {
    0% { transform: scale(0.95); opacity: 0.5; }
    50% { transform: scale(1.15); opacity: 1; }
    100% { transform: scale(0.95); opacity: 0.5; }
}

.studio-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 24px;
    margin-top: 20px;
}
.recruiter-card .rec-top {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}
.rec-ava {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.rec-ava svg {
    width: 22px;
    height: 22px;
}
.rec-ava .live {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: #10b981;
    border-radius: 50%;
    border: 2px solid #fff;
}
.rec-id b {
    display: block;
    font-size: .9rem;
    color: var(--brand-deep);
}
.rec-id i {
    font-style: normal;
    font-size: .74rem;
    color: var(--muted);
}
.rec-quote {
    font-style: italic;
    font-size: .82rem;
    color: var(--text);
    background: var(--bg);
    padding: 12px 14px;
    border-radius: 10px;
    margin-bottom: 14px;
    line-height: 1.4;
}
.rec-tips {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.rec-tips li {
    display: flex;
    gap: 8px;
    font-size: .78rem;
    color: var(--muted);
    line-height: 1.4;
}
.rec-tips li svg {
    width: 14px;
    height: 14px;
    color: var(--accent-dark);
    flex-shrink: 0;
    margin-top: 2px;
}
.ach-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.ach {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 8px 4px;
    border-radius: 8px;
    background: var(--bg);
    border: 1px solid var(--border);
    transition: all .2s;
}
.ach svg {
    width: 18px;
    height: 18px;
    margin-bottom: 4px;
}
.ach span {
    font-size: .62rem;
    font-weight: 700;
    color: var(--muted);
    display: block;
}
.ach--won {
    background: rgba(var(--brand-rgb),.04);
    border-color: var(--brand-light);
}
.ach--won svg {
    color: var(--brand);
}
.ach--won span {
    color: var(--brand-deep);
}
.ach--locked {
    opacity: 0.5;
}
.ach--locked svg {
    color: var(--muted);
}
.benefits {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-top: 14px;
}
.ben {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px;
}
.ben-ic {
    width: 32px;
    height: 32px;
    background: var(--brand-light);
    color: var(--brand);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}
.ben-ic svg {
    width: 16px;
    height: 16px;
}
.ben h3 {
    font-family: 'Sora', sans-serif;
    font-size: .88rem;
    font-weight: 700;
    color: var(--brand-deep);
    margin-bottom: 6px;
}
.ben p {
    font-size: .78rem;
    color: var(--muted);
    line-height: 1.45;
    margin: 0;
}
.session-history-item {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 14px;
    transition: all .15s;
}
.session-history-item:hover {
    border-color: var(--brand);
    background: #fff;
}
@media (max-width: 992px) {
    .hero-grid { grid-template-columns: 1fr; }
    .hero-orb { display: none; }
    .studio-grid { grid-template-columns: 1fr; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$recentSessions = $recentSessions ?? [];
$contextPreset = $contextPreset ?? [];
$questionPackLabels = [
    'general' => 'General / Behavioral',
    'engineering' => 'Engineering & Tech',
    'product' => 'Product Management',
    'sales' => 'Sales & Development',
    'marketing' => 'Marketing & Growth',
    'support' => 'Customer Success',
    'operations' => 'Operations & Strategy',
];
?>
<div class="content">

    <!-- Hero Showcase Panel -->
    <section class="studio-hero" aria-labelledby="studio-title">
        <div class="hero-grid">
            <div>
                <div class="hero-badges">
                    <span class="hb hb--premium"><svg style="width:12px;height:12px;fill:currentColor;stroke:none;" aria-hidden="true"><use href="#i-crown"/></svg> Premium</span>
                    <span class="hb hb--live"><span class="pulse" aria-hidden="true"></span> AI Interviewer · Online</span>
                </div>
                <h1 id="studio-title">AI Interview <span>Studio</span></h1>
                <p class="hero-sub">Practice realistic interviews powered by AI and get evaluated the way real hiring managers would score you.</p>
                <div class="hero-chips">
                    <div class="hchip">
                        <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-flame"/></svg> Practice Streak</span>
                        <div class="hchip-val">3 days <small>· best 5</small></div>
                    </div>
                    <div class="hchip">
                        <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-zap"/></svg> Career XP</span>
                        <div class="hchip-val">1,240 XP <small>· Level 4</small></div>
                    </div>
                </div>
            </div>
            <!-- AI animation orb wrapper -->
            <div class="hero-orb" role="img" aria-label="AI interviewer illustration">
                <svg viewBox="0 0 200 200" fill="none" aria-hidden="true">
                    <circle class="orb-ring" cx="100" cy="100" r="86" stroke="rgba(255,255,255,.18)" stroke-width="1.5" stroke-dasharray="6 10"/>
                    <circle class="orb-ring orb-ring--2" cx="100" cy="100" r="66" stroke="rgba(237,144,32,.5)" stroke-width="1.5" stroke-dasharray="2 12"/>
                    <circle cx="100" cy="100" r="46" fill="rgba(255,255,255,.08)" stroke="rgba(255,255,255,.28)" stroke-width="1.5"/>
                    <g stroke="#fff" stroke-width="2.4" stroke-linecap="round">
                        <rect x="91" y="76" width="18" height="32" rx="9" fill="rgba(255,255,255,.16)"/>
                        <path d="M79 100a21 21 0 0 0 42 0M100 121v11M89 134h22" fill="none"/>
                    </g>
                </svg>
            </div>
        </div>
    </section>

    <!-- Stats row -->
    <section class="stats" aria-label="Your interview performance" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="stat">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-message-sq"/></svg></span>
            </div>
            <div class="stat-num"><?= count($recentSessions) ?></div>
            <div class="stat-lbl">Completed Interviews</div>
        </div>
        <div class="stat" style="--st-bar:var(--brand-dark)">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-chart"/></svg></span>
            </div>
            <div class="stat-num">
                <?php
                    $avgScore = 0;
                    if (count($recentSessions) > 0) {
                        $scores = array_column($recentSessions, 'overall_score');
                        $avgScore = round(array_sum($scores) / count($recentSessions), 1);
                    }
                    echo $avgScore > 0 ? $avgScore . '<span style="font-size:.6em">/10</span>' : 'N/A';
                ?>
            </div>
            <div class="stat-lbl">Average Score</div>
        </div>
        <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
            <div class="stat-top">
                <span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-shield"/></svg></span>
            </div>
            <div class="stat-num" style="font-size:1.15rem;margin-top:6px;">Warming Up</div>
            <div class="stat-lbl">Hiring Readiness</div>
        </div>
    </section>

    <!-- Main Grid: setup and side panels -->
    <div class="studio-grid">
        
        <!-- Left: Setup Card -->
        <section class="card" aria-label="Session Configuration" style="padding: 24px;">
            <h3 style="font-family:'Sora',sans-serif; font-size:1.15rem; font-weight:800; color:var(--brand-deep); margin-bottom:4px; display:flex; align-items:center; gap:8px;">
                <svg aria-hidden="true" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-sliders"/></svg> Session Configuration
            </h3>
            <p style="font-size:0.8rem; color:var(--muted); margin-bottom:20px;">Customize your practice parameters to launch the simulator.</p>

            <form id="setup-form" style="display:flex; flex-direction:column; gap:16px;">
                <input type="hidden" id="application-id" value="<?= esc((string) ($contextPreset['application_id'] ?? '0')) ?>">

                <div class="form-field">
                    <label class="lbl">Target Job Title</label>
                    <input type="text" id="job-title" class="input" value="<?= esc((string) ($contextPreset['job_title'] ?? '')) ?>" placeholder="e.g. Senior Frontend Architect, Product Manager" required>
                    <div style="font-size:0.74rem; color:var(--muted); margin-top:4px;">We'll tailor behavioral &amp; technical questions to mirror this role.</div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-field">
                        <label class="lbl">Difficulty Level</label>
                        <select id="difficulty" class="select">
                            <option value="easy">Easy (Warm-up)</option>
                            <option value="medium" selected>Medium (Standard)</option>
                            <option value="hard">Hard (Panel Pressure)</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label class="lbl">Question Context Pack</label>
                        <select id="question-pack" class="select">
                            <?php foreach ($questionPackLabels as $value => $label): ?>
                                <option value="<?= esc($value) ?>"><?= esc($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-field">
                        <label class="lbl">Interview Mode</label>
                        <select id="interview-mode" class="select">
                            <option value="chat" selected>Chat-only (Text Responses)</option>
                            <option value="voice">Voice-enabled (Speak Answers)</option>
                        </select>
                    </div>
                </div>

                <?php if (!empty($contextPreset['job_title'])): ?>
                    <div class="notice notice--info">
                        <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg>
                        <span><strong>Application-Aware Intelligence</strong>: This session is synchronized with details from your recent job application.</span>
                    </div>
                <?php endif; ?>

                <div style="margin-top: 10px;">
                    <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-start-session">
                        <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;margin-right:6px;display:inline-block;vertical-align:middle;"><use href="#i-play"/></svg> Start AI Interview
                    </button>
                </div>
            </form>
        </section>

        <!-- Right Panels: recruiter card, achievements and history -->
        <div style="display:flex; flex-direction:column; gap:20px;">
            
            <!-- AI Recruiter Profile -->
            <section class="card recruiter-card" style="padding:18px;">
                <div class="rec-top">
                    <span class="rec-ava" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8"><circle cx="12" cy="8.4" r="3.6"/><path d="M5 20a7.5 7.5 0 0 1 14 0"/></svg>
                        <span class="live"></span>
                    </span>
                    <div class="rec-id"><b>Chioma Nwachukwu</b><i>AI Recruiter Profile</i></div>
                </div>
                <p class="rec-quote">"I'm here to understand not just what you've done, but how you work with people and handle real workplace situations."</p>
                <ul class="rec-tips">
                    <li><svg aria-hidden="true"><use href="#i-bulb"/></svg> Answer with real workplace examples — HR panels value evidence over general statements.</li>
                    <li><svg aria-hidden="true"><use href="#i-bulb"/></svg> Show self-awareness, not just achievements — HR notices how you reflect on situations.</li>
                </ul>
            </section>

            <!-- Achievements grid panel -->
            <section class="card" style="padding:18px;">
                <h3 style="font-family:'Sora',sans-serif; font-size:0.94rem; font-weight:800; color:var(--brand-deep); margin-bottom:12px; display:flex; align-items:center; justify-content:space-between;">
                    <span>Achievements</span>
                    <span class="pill pill--brand">3 of 8</span>
                </h3>
                <div class="ach-grid">
                    <div class="ach ach--won" title="Completed first mock interview"><svg aria-hidden="true"><use href="#i-play"/></svg><span>First Session</span></div>
                    <div class="ach ach--won" title="Practiced 3 days in a row"><svg aria-hidden="true"><use href="#i-flame"/></svg><span>3-Day Streak</span></div>
                    <div class="ach ach--won" title="Score 7.5 or above"><svg aria-hidden="true"><use href="#i-star"/></svg><span>Score 7.5+</span></div>
                    <div class="ach ach--locked" title="Score 8.5 or above"><svg aria-hidden="true"><use href="#i-lock"/></svg><span>Score 8.5+</span></div>
                </div>
            </section>

            <!-- History Sidebar -->
            <section class="card" aria-label="Recent Sessions" style="padding: 18px;">
                <h3 style="font-family:'Sora',sans-serif; font-size:0.94rem; font-weight:800; color:var(--brand-deep); margin-bottom:4px; display:flex; align-items:center; gap:8px;">
                    <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-clock"/></svg> Recent Sessions
                </h3>
                <p style="font-size:0.76rem; color:var(--muted); margin-bottom:16px;"><?= count($recentSessions) ?> Scored Sessions Completed</p>

                <div id="recent-sessions-list" style="display:flex; flex-direction:column; gap:10px; max-height:240px; overflow-y:auto;">
                    <?php if (empty($recentSessions)): ?>
                        <div style="text-align:center; padding:20px 10px; color:var(--muted);">
                            <svg aria-hidden="true" style="width:24px;height:24px;color:var(--border);margin-bottom:8px;fill:none;stroke:currentColor;stroke-width:2;display:block;margin-left:auto;margin-right:auto;"><use href="#i-chat"/></svg>
                            <p style="font-size:0.78rem; margin:0;">Launch your first session to build your interactive history.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentSessions as $session): ?>
                            <div class="session-history-item">
                                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                                    <div style="min-width: 0;">
                                        <strong style="font-size:0.8rem; color:var(--brand-deep); display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= esc($session['job_title'] ?: 'Interview Session') ?></strong>
                                        <div style="font-size:0.7rem; color:var(--muted); margin-top:2px; display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                                            <span class="pill pill--closed" style="font-size:0.56rem; padding:1px 4px;"><?= esc(ucfirst((string) ($session['difficulty'] ?? 'medium'))) ?></span>
                                            <span><?= esc($questionPackLabels[$session['question_pack'] ?? 'general'] ?? ucfirst((string) ($session['question_pack'] ?? 'General'))) ?></span>
                                        </div>
                                    </div>
                                    <div style="text-align:right;">
                                        <span class="pill pill--success" style="font-weight:700; font-size:0.72rem; padding:2px 8px;">
                                            <?= esc((string) ($session['overall_score'] ?? 0)) ?>/10
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

        </div>
    </div>

    <!-- Why candidates get hired benefits showcase block -->
    <section aria-labelledby="ben-title" style="margin-top: 32px;">
        <div style="margin-bottom:12px">
            <h2 id="ben-title" style="font-family:'Sora',sans-serif; font-size:1.1rem; font-weight:800; color:var(--brand-deep); display:flex; align-items:center; gap:8px;">
                <svg aria-hidden="true" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-star"/></svg>
                Why candidates get hired faster with the Studio
            </h2>
            <p style="font-size: 0.8rem; color: var(--muted); margin: 2px 0 0;">Every session is scored the way a real Nigerian recruiter would score you.</p>
        </div>
        <div class="benefits">
            <div class="ben">
                <span class="ben-ic" aria-hidden="true"><svg><use href="#i-infinity"/></svg></span>
                <h3>Practice Unlimited</h3>
                <p>No caps, no daily limits. Rehearse the same tough question until your answer lands — at 6 AM or midnight.</p>
            </div>
            <div class="ben">
                <span class="ben-ic" aria-hidden="true"><svg><use href="#i-message-sq"/></svg></span>
                <h3>Real Recruiter Questions</h3>
                <p>Question banks built from real interviews at Nigerian banks, multinationals, startups and public-sector panels.</p>
            </div>
            <div class="ben">
                <span class="ben-ic" aria-hidden="true"><svg><use href="#i-scan"/></svg></span>
                <h3>ATS Evaluation</h3>
                <p>Your answers are checked against the keywords the role demands — the same way applicant tracking systems screen you.</p>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#setup-form').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#btn-start-session');
            btn.prop('disabled', true).text('Preparing Session...');

            const jobTitle = $('#job-title').val().trim();
            const difficulty = $('#difficulty').val();
            const questionPack = $('#question-pack').val();
            const mode = $('#interview-mode').val();
            const appId = $('#application-id').val();

            if (!jobTitle) {
                toastr.error('Please enter a target job title.');
                btn.prop('disabled', false).text('Start AI Interview');
                return;
            }

            const params = new URLSearchParams({
                job_title: jobTitle,
                difficulty: difficulty,
                question_pack: questionPack,
                interview_mode: mode,
                application_id: appId || '0'
            });
            toastr.success('Session prepared! Launching…');
            window.location.href = '<?= base_url('candidate/career-tools/mock-interview/start') ?>?' + params.toString();
        });
    });
</script>
<?= $this->endSection() ?>
