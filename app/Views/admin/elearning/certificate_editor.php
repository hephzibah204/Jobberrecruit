<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
<style>
/* ── Editor shell ───────────────────────────────────────── */
.cert-editor-wrap{display:flex;gap:22px;margin-top:18px;align-items:flex-start}
.cert-preview-pane{flex:1;min-width:0;background:#e9eef4;border-radius:12px;padding:28px;display:flex;flex-direction:column;align-items:center;overflow-x:auto}
.cert-preview-scaler{transform-origin:top center;width:1056px}
.cert-controls-pane{width:340px;flex-shrink:0;background:#fff;border-radius:12px;box-shadow:0 0 18px rgba(0,0,0,.06);padding:22px;max-height:calc(100vh - 140px);overflow-y:auto}
.ctrl-section{margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #eef0f4}
.ctrl-section:last-child{border-bottom:none}
.ctrl-label{font-weight:700;font-size:.78rem;letter-spacing:.08em;text-transform:uppercase;color:#5b6577;display:block;margin-bottom:10px}
.ctrl-row{display:flex;align-items:center;gap:10px;margin-bottom:8px}
.ctrl-row label{font-size:.82rem;color:#333;min-width:90px}
.ctrl-row input[type=color]{width:44px;height:32px;border:1px solid #ddd;border-radius:6px;cursor:pointer;padding:2px}
.ctrl-row select,.ctrl-row input[type=text]{flex:1;font-size:.82rem}
.ctrl-toggle{display:flex;align-items:center;gap:10px;margin-bottom:8px;font-size:.83rem;color:#333}

/* ── Premium certificate (mirrors course_certificate.php) ── */
:root{
  --navy:#0A2F57;--brand:#0D609E;--brand-dark:#064A85;--accent:#ED9020;
  --gold:#C9A24B;--gold-light:#E4C878;--ink:#15233a;--muted:#5b6577;
  --line:#d9e2ee;--paper:#ffffff;
}
.certificate{
  position:relative;width:1056px;max-width:100%;aspect-ratio:1056/748;
  background:#fdfbf4 url('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/4QAC/9sAhAAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQyAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL') center/cover no-repeat;
  box-shadow:0 24px 70px rgba(10,47,87,.28);overflow:hidden;border-radius:4px;
}
.cert-frame{position:absolute;inset:18px;border:2px solid var(--navy)}
.cert-frame::before{content:"";position:absolute;inset:6px;border:1px solid var(--gold)}
.cert-texture-overlay{position:absolute;inset:0;background:rgba(253,251,244,.66);pointer-events:none;z-index:0}
.cert-paper-grain{position:absolute;inset:0;z-index:0;pointer-events:none;opacity:.5;mix-blend-mode:multiply;
  background-image:repeating-linear-gradient(0deg,rgba(120,100,60,.025) 0px,transparent 1px,transparent 2px,rgba(120,100,60,.025) 3px),repeating-linear-gradient(90deg,rgba(120,100,60,.03) 0px,transparent 1px,transparent 2px,rgba(120,100,60,.03) 3px);
  background-size:3px 3px,3px 3px}
.cert-vignette{position:absolute;inset:0;z-index:1;pointer-events:none;
  background:radial-gradient(120% 120% at 50% 45%,transparent 60%,rgba(120,95,45,.06) 88%,rgba(90,70,30,.11) 100%)}
.cert-guilloche{position:absolute;inset:0;opacity:1;pointer-events:none;overflow:hidden}
.cert-guilloche svg{position:absolute;inset:0;width:100%;height:100%;opacity:.06}
.cert-edgeline{position:absolute;inset:30px;pointer-events:none;z-index:1;border:1px solid transparent;
  background:repeating-linear-gradient(90deg,var(--gold) 0 6px,transparent 6px 12px) top/100% 1px no-repeat,
             repeating-linear-gradient(90deg,var(--gold) 0 6px,transparent 6px 12px) bottom/100% 1px no-repeat,
             repeating-linear-gradient(0deg,var(--gold) 0 6px,transparent 6px 12px) left/1px 100% no-repeat,
             repeating-linear-gradient(0deg,var(--gold) 0 6px,transparent 6px 12px) right/1px 100% no-repeat;opacity:.5}
.cert-watermark{position:absolute;top:38%;left:50%;transform:translate(-50%,-50%);width:380px;height:380px;opacity:.04;pointer-events:none}
.cert-watermark img{width:100%;height:100%;object-fit:contain}
.cert-corner{position:absolute;width:72px;height:72px;border:0 solid var(--accent)}
.cc-tl{top:22px;left:22px;border-top-width:4px;border-left-width:4px}
.cc-tr{top:22px;right:22px;border-top-width:4px;border-right-width:4px}
.cc-bl{bottom:22px;left:22px;border-bottom-width:4px;border-left-width:4px}
.cc-br{bottom:22px;right:22px;border-bottom-width:4px;border-right-width:4px}

.cert-inner{position:relative;z-index:2;height:100%;padding:52px 64px 44px;display:flex;flex-direction:column;text-align:center}
.cert-top{display:flex;align-items:center;justify-content:center;gap:13px;margin-bottom:6px}
.cert-logo-real{height:48px;width:auto;max-width:260px;object-fit:contain}
.cert-type-ribbon{display:inline-flex;align-self:center;align-items:center;gap:7px;margin:12px auto 0;font-size:.72rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--brand);background:#eef5fb;border:1px solid #d2e4f3;border-radius:30px;padding:6px 18px}
.cert-title{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:800;color:var(--navy);letter-spacing:.01em;margin:18px 0 14px;line-height:1;position:relative;display:inline-block;align-self:center}
.cert-title::after{content:"";position:absolute;left:18%;right:18%;bottom:-6px;height:2px;background:linear-gradient(90deg,transparent,var(--gold) 30%,var(--gold) 70%,transparent)}
.cert-sub{font-size:.78rem;letter-spacing:.32em;text-transform:uppercase;color:var(--muted);font-weight:600}
.cert-divider{position:relative;width:160px;height:14px;margin:16px auto;display:flex;align-items:center;justify-content:center}
.cert-divider::before,.cert-divider::after{content:"";position:absolute;top:50%;height:1.5px;width:62px;background:linear-gradient(90deg,transparent,var(--gold))}
.cert-divider::before{left:0}.cert-divider::after{right:0;background:linear-gradient(270deg,transparent,var(--gold))}
.cert-divider i{width:9px;height:9px;background:linear-gradient(135deg,var(--gold-light),var(--gold));transform:rotate(45deg);box-shadow:0 1px 2px rgba(140,106,34,.5)}
.cert-name{font-family:'Playfair Display',serif;font-size:3.1rem;font-weight:800;color:var(--navy);line-height:1.04;margin-bottom:8px;letter-spacing:.005em}
.cert-name-rule{width:340px;max-width:70%;height:1px;background:var(--line);margin:4px auto 18px}
.cert-statement{font-size:.9rem;color:var(--muted);line-height:1.6;max-width:680px;margin:0 auto 10px}
.cert-course-wrap{margin:0 auto 4px;position:relative;display:inline-block}
.cert-course{font-family:'Playfair Display',serif;font-size:1.18rem;font-style:italic;font-weight:600;color:var(--brand-dark);max-width:760px;line-height:1.3;letter-spacing:.01em}
.cert-course-wrap::after{content:"";display:block;width:80%;margin:7px auto 0;height:1px;background:linear-gradient(90deg,transparent,var(--gold) 25%,var(--gold) 75%,transparent)}
.cert-meta{display:flex;justify-content:center;gap:40px;margin:20px auto 0;flex-wrap:wrap}
.cert-meta-item{text-align:center}
.cert-meta-item .lbl{font-size:.62rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:3px}
.cert-meta-item .val{font-family:'Sora',sans-serif;font-size:.92rem;font-weight:700;color:var(--navy)}
.cert-bottom{margin-top:auto;display:flex;align-items:flex-end;justify-content:space-between;gap:24px;padding:20px 40px 0}
.cert-sign{text-align:center;min-width:220px}
.cert-sign-img{display:block;height:64px;width:auto;max-width:220px;margin:0 auto -2px;object-fit:contain}
.cert-sign-line{height:1px;background:var(--ink);opacity:.35;margin-bottom:8px;width:200px;margin-left:auto;margin-right:auto}
.cert-sign-role{font-size:.64rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);font-weight:700}
.cert-seal{position:relative;width:118px;height:118px;display:flex;align-items:center;justify-content:center;filter:drop-shadow(0 6px 16px rgba(140,106,34,.45))}
.cert-seal svg{width:100%;height:100%}
.cert-verify{position:relative;z-index:2;display:flex;align-items:center;justify-content:center;gap:20px;margin-top:18px;padding:10px 22px 6px;border-top:1px solid rgba(201,162,75,.35)}
.cert-verify::before{content:"";position:absolute;top:0;left:10%;right:10%;height:1px;background:linear-gradient(90deg,transparent,var(--gold) 30%,var(--gold) 70%,transparent)}
.cert-qr-box{width:68px;height:68px;flex-shrink:0;padding:5px;border:1.5px solid rgba(10,47,87,.25);border-radius:6px;background:#fff;display:flex;align-items:center;justify-content:center}
.cert-qr-box svg{width:100%;height:100%}
.cert-verify-text{text-align:left}
.cert-verify-text .vt-lbl{font-size:.58rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);font-weight:700;margin-bottom:1px}
.cert-verify-text .vt-id{font-family:'Sora',sans-serif;font-size:.88rem;font-weight:800;color:var(--navy);letter-spacing:.02em}
.cert-verify-text .vt-url{font-size:.68rem;color:var(--brand);font-weight:600;margin-top:1px}
.cert-verify-badge{display:inline-flex;align-items:center;gap:5px;font-size:.65rem;font-weight:700;color:var(--navy);background:linear-gradient(135deg,rgba(228,200,120,.25),rgba(201,162,75,.15));border:1px solid rgba(201,162,75,.5);border-radius:20px;padding:4px 11px}
</style>

<div class="container-fluid">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Certificate Template Editor</h1>
        <div class="ms-md-1 ms-0 d-flex gap-2">
            <a href="<?= base_url('admin/elearning') ?>" class="btn btn-light">← Back to Courses</a>
            <button type="button" class="btn btn-primary" id="saveTemplateBtn">
                <i class="ti ti-device-floppy me-1"></i> Save Template
            </button>
        </div>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="alert alert-info py-2 fs-13">
        <strong>Live Preview:</strong> Changes to colors and settings update the preview instantly. The preview matches exactly what your students will receive.
    </div>

    <div class="cert-editor-wrap">
        <!-- ═══ LIVE CERTIFICATE PREVIEW ═══ -->
        <div class="cert-preview-pane">
            <p class="text-muted fs-12 mb-3 text-center">Scaled preview — actual certificate is A4 landscape</p>
            <div class="cert-preview-scaler" id="certPreviewScaler">

                <!-- SVG symbol defs needed by cert-type-ribbon icon -->
                <svg width="0" height="0" style="position:absolute" aria-hidden="true">
                  <defs>
                    <symbol id="i-cap-prev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 9 12 4 2 9l10 5 10-5Z"/><path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/>
                    </symbol>
                    <symbol id="i-shield-prev" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                    </symbol>
                  </defs>
                </svg>

                <div class="certificate" id="certPreview">
                  <div class="cert-texture-overlay"></div>
                  <div class="cert-paper-grain"></div>
                  <div class="cert-vignette"></div>
                  <div class="cert-guilloche">
                    <svg viewBox="0 0 1056 748" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                      <g fill="none" stroke="#0A2F57" stroke-width="0.6">
                        <ellipse cx="528" cy="374" rx="60" ry="42"/><ellipse cx="528" cy="374" rx="90" ry="63"/><ellipse cx="528" cy="374" rx="120" ry="85"/>
                        <ellipse cx="528" cy="374" rx="150" ry="106"/><ellipse cx="528" cy="374" rx="180" ry="127"/><ellipse cx="528" cy="374" rx="210" ry="148"/>
                        <ellipse cx="528" cy="374" rx="240" ry="169"/><ellipse cx="528" cy="374" rx="270" ry="191"/><ellipse cx="528" cy="374" rx="300" ry="212"/>
                        <ellipse cx="528" cy="374" rx="330" ry="233"/><ellipse cx="528" cy="374" rx="360" ry="254"/><ellipse cx="528" cy="374" rx="390" ry="276"/>
                        <ellipse cx="528" cy="374" rx="420" ry="297"/><ellipse cx="528" cy="374" rx="450" ry="318"/><ellipse cx="528" cy="374" rx="480" ry="339"/>
                        <ellipse cx="528" cy="374" rx="510" ry="360"/><ellipse cx="528" cy="374" rx="540" ry="382"/>
                      </g>
                      <g fill="none" stroke="#0D609E" stroke-width="0.5" opacity="0.8">
                        <path d="M0,120 C120,100 240,140 360,120 C480,100 600,140 720,120 C840,100 960,140 1056,120"/>
                        <path d="M0,150 C120,130 240,170 360,150 C480,130 600,170 720,150 C840,130 960,170 1056,150"/>
                        <path d="M0,598 C130,576 260,620 390,598 C520,576 650,620 780,598 C910,576 990,620 1056,603"/>
                        <path d="M0,626 C130,604 260,648 390,626 C520,604 650,648 780,626 C910,604 990,648 1056,631"/>
                      </g>
                      <g fill="none" stroke="#C9A24B" stroke-width="0.6" opacity="0.9">
                        <circle cx="80" cy="80" r="18"/><circle cx="80" cy="80" r="28"/><circle cx="80" cy="80" r="38"/>
                        <line x1="80" y1="52" x2="80" y2="108"/><line x1="52" y1="80" x2="108" y2="80"/>
                        <line x1="59" y1="59" x2="101" y2="101"/><line x1="101" y1="59" x2="59" y2="101"/>
                        <circle cx="976" cy="80" r="18"/><circle cx="976" cy="80" r="28"/><circle cx="976" cy="80" r="38"/>
                        <line x1="976" y1="52" x2="976" y2="108"/><line x1="948" y1="80" x2="1004" y2="80"/>
                        <circle cx="80" cy="668" r="18"/><circle cx="80" cy="668" r="28"/><circle cx="80" cy="668" r="38"/>
                        <line x1="80" y1="640" x2="80" y2="696"/><line x1="52" y1="668" x2="108" y2="668"/>
                        <circle cx="976" cy="668" r="18"/><circle cx="976" cy="668" r="28"/><circle cx="976" cy="668" r="38"/>
                        <line x1="976" y1="640" x2="976" y2="696"/><line x1="948" y1="668" x2="1004" y2="668"/>
                      </g>
                    </svg>
                  </div>
                  <div class="cert-edgeline"></div>
                  <div class="cert-watermark"><img src="<?= base_url('auth/img/logo.png') ?>" alt=""></div>
                  <div class="cert-frame"></div>
                  <span class="cert-corner cc-tl"></span><span class="cert-corner cc-tr"></span>
                  <span class="cert-corner cc-bl"></span><span class="cert-corner cc-br"></span>

                  <div class="cert-inner">
                    <div class="cert-top">
                      <img src="<?= base_url('auth/img/logo.png') ?>" alt="JobberRecruit" class="cert-logo-real">
                    </div>
                    <span class="cert-type-ribbon" id="prev-ribbon">
                      <svg aria-hidden="true"><use href="#i-cap-prev"/></svg>
                      <span id="prev-ribbon-text">Professional Training Programme</span>
                    </span>

                    <h2 class="cert-title" id="prev-title">Certificate of Completion</h2>
                    <div class="cert-sub">This certifies that</div>

                    <div class="cert-divider"><i></i></div>

                    <div class="cert-name" id="prev-name">Adebayo Martins</div>
                    <div class="cert-name-rule"></div>

                    <p class="cert-statement" id="prev-statement">has successfully completed all requirements of the professional training programme</p>
                    <div class="cert-course-wrap">
                      <div class="cert-course" id="prev-course">Mastering the ATS: Build a CV That Gets Interviews</div>
                    </div>

                    <div class="cert-meta">
                      <div class="cert-meta-item">
                        <div class="lbl">Date Issued</div>
                        <div class="val"><?= date('j F Y') ?></div>
                      </div>
                      <div class="cert-meta-item" id="prev-duration-wrap">
                        <div class="lbl">Duration</div>
                        <div class="val" id="prev-duration">6 Modules · 8 Hours</div>
                      </div>
                    </div>

                    <div class="cert-bottom">
                      <div class="cert-seal" aria-hidden="false">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                          <defs>
                            <radialGradient id="gR" cx="50%" cy="35%" r="70%"><stop offset="0%" stop-color="#F4DD94"/><stop offset="35%" stop-color="#E4C878"/><stop offset="65%" stop-color="#C9A24B"/><stop offset="100%" stop-color="#9A7728"/></radialGradient>
                            <radialGradient id="gR2" cx="50%" cy="40%" r="65%"><stop offset="0%" stop-color="#E4C878"/><stop offset="50%" stop-color="#C9A24B"/><stop offset="100%" stop-color="#B8902F"/></radialGradient>
                            <radialGradient id="gN" cx="50%" cy="32%" r="75%"><stop offset="0%" stop-color="#14416F"/><stop offset="70%" stop-color="#0A2F57"/><stop offset="100%" stop-color="#072343"/></radialGradient>
                            <path id="tAT" d="M 100,100 m -62,0 a 62,62 0 1,1 124,0" fill="none"/>
                            <path id="tAB" d="M 40,100 a 60,60 0 0,0 120,0" fill="none"/>
                          </defs>
                          <g fill="url(#gR2)">
                            <circle cx="190" cy="100" r="7"/><circle cx="188.63" cy="115.63" r="7"/><circle cx="184.57" cy="130.78" r="7"/>
                            <circle cx="177.94" cy="145" r="7"/><circle cx="168.94" cy="157.85" r="7"/><circle cx="100" cy="10" r="7"/>
                            <circle cx="10" cy="100" r="7"/><circle cx="100" cy="190" r="7"/>
                          </g>
                          <circle cx="100" cy="100" r="90" fill="url(#gR)" stroke="#9A7728" stroke-width="0.5"/>
                          <circle cx="100" cy="100" r="80" fill="url(#gR2)"/>
                          <circle cx="100" cy="100" r="72" fill="url(#gN)"/>
                          <circle cx="100" cy="100" r="72" fill="none" stroke="#E4C878" stroke-width="1.2"/>
                          <circle cx="100" cy="100" r="48" fill="none" stroke="#E4C878" stroke-width="1" opacity="0.85"/>
                          <text fill="#E4C878" font-family="Sora,sans-serif" font-weight="700" font-size="9.5" letter-spacing="1.8">
                            <textPath href="#tAT" startOffset="50%" text-anchor="middle">JOBBERRECRUIT</textPath>
                          </text>
                          <text fill="#E4C878" font-family="Sora,sans-serif" font-weight="600" font-size="9.5" letter-spacing="3">
                            <textPath href="#tAB" startOffset="50%" text-anchor="middle">• CERTIFIED •</textPath>
                          </text>
                          <circle cx="100" cy="100" r="46" fill="url(#gN)"/>
                          <g transform="translate(100,100) scale(0.05) translate(-462,-634)">
                            <path d="M292.08 333.8c-199.04,44 -324.72,241.02 -280.72,440.05 44,199.04 241.02,324.72 440.05,280.72 50.72,-11.21 96.67,-32.38 136.27,-60.97l259.28 271.83 74.82 -70.68 -259.88 -272.45c65.88,-83.89 95.06,-195.51 70.24,-307.79 -44,-199.03 -241.02,-324.72 -440.05,-280.72zm23.48 106.2c-140.39,31.03 -229.04,169.99 -198,310.38 31.03,140.39 169.99,229.04 310.38,198.01 140.39,-31.04 229.04,-170 198,-310.39 -31.03,-140.39 -169.99,-229.04 -310.38,-198z" fill="#E4C878"/>
                            <path d="M372.31 0c76.1,0 137.78,61.69 137.78,137.79 0,76.1 -61.69,137.78 -137.78,137.78 -76.09,0 -137.78,-61.69 -137.78,-137.78 0,-76.1 61.69,-137.79 137.78,-137.79z" fill="#E4C878"/>
                          </g>
                        </svg>
                      </div>

                      <div class="cert-sign" id="prev-sign-wrap">
                        <?php if (setting('Elearning.certificate_signature')): ?>
                          <img src="<?= base_url(setting('Elearning.certificate_signature')) ?>" class="cert-sign-img" alt="Signature">
                        <?php endif; ?>
                        <div class="cert-sign-line"></div>
                        <div class="cert-sign-role">Authorized Signature</div>
                        <div style="font-size:.62rem;letter-spacing:.06em;text-transform:uppercase;color:var(--navy);font-weight:700;margin-top:2px;opacity:.7">JobberRecruit</div>
                      </div>
                    </div>

                    <div class="cert-verify" id="prev-verify-wrap">
                      <div class="cert-qr-box">
                        <svg viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                          <rect width="80" height="80" fill="#fff"/>
                          <rect x="5" y="5" width="30" height="30" fill="none" stroke="#0A2F57" stroke-width="4"/>
                          <rect x="13" y="13" width="14" height="14" fill="#0A2F57"/>
                          <rect x="45" y="5" width="30" height="30" fill="none" stroke="#0A2F57" stroke-width="4"/>
                          <rect x="53" y="13" width="14" height="14" fill="#0A2F57"/>
                          <rect x="5" y="45" width="30" height="30" fill="none" stroke="#0A2F57" stroke-width="4"/>
                          <rect x="13" y="53" width="14" height="14" fill="#0A2F57"/>
                          <rect x="45" y="45" width="8" height="8" fill="#0A2F57"/>
                          <rect x="57" y="45" width="8" height="8" fill="#0A2F57"/>
                          <rect x="45" y="57" width="8" height="8" fill="#0A2F57"/>
                          <rect x="57" y="57" width="8" height="8" fill="#0A2F57"/>
                        </svg>
                      </div>
                      <div class="cert-verify-text">
                        <div class="vt-lbl">Certificate ID</div>
                        <div class="vt-id">JR-CV-<?= date('Y') ?>-XXXXXXXX</div>
                        <div class="vt-url"><?= base_url('verify/JR-CV-' . date('Y') . '-XXXXXXXX') ?></div>
                      </div>
                      <div class="cert-verify-badge">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                        Verified Authentic
                      </div>
                    </div>
                  </div><!-- cert-inner -->
                </div><!-- .certificate -->
            </div><!-- scaler -->
        </div><!-- preview-pane -->

        <!-- ═══ CONTROLS PANEL ═══ -->
        <div class="cert-controls-pane">
            <form id="templateForm" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="layout_json" id="layout_json" value="{}">

                <!-- Course selector -->
                <div class="ctrl-section">
                    <span class="ctrl-label">Apply Template To</span>
                    <select name="course_id" id="courseSelect" class="form-select form-select-sm">
                        <option value="">Global Default (all courses)</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($template['course_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                <?= esc($c['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-muted fs-11 mt-1 mb-0">Course-specific overrides the global default.</p>
                </div>

                <!-- Template Mode -->
                <div class="ctrl-section">
                    <span class="ctrl-label">Template Mode</span>
                    <select name="template_mode" id="templateMode" class="form-select form-select-sm mb-2">
                        <option value="builder" <?= ($template['template_mode'] ?? 'builder') === 'builder' ? 'selected' : '' ?>>Premium Design (Default)</option>
                        <option value="html" <?= ($template['template_mode'] ?? '') === 'html' ? 'selected' : '' ?>>Advanced HTML/CSS</option>
                    </select>
                </div>

                <!-- Certificate Type -->
                <div class="ctrl-section" id="builderControls" style="display:<?= ($template['template_mode'] ?? 'builder') !== 'html' ? 'block' : 'none' ?>">
                    <span class="ctrl-label">Certificate Type</span>
                    <select name="cert_type" id="certType" class="form-select form-select-sm mb-2">
                        <option value="training">Professional Training Programme</option>
                        <option value="webinar">Professional Webinar</option>
                        <option value="course">Course Completion</option>
                    </select>

                    <span class="ctrl-label mt-3">Brand Colors</span>
                    <div class="ctrl-row">
                        <label>Primary</label>
                        <input type="color" name="primary_color" id="primaryColor" value="<?= $template['primary_color'] ?? '#0D609E' ?>" class="form-control form-control-sm p-0" style="width:44px;height:32px">
                        <small class="text-muted">Borders, titles</small>
                    </div>
                    <div class="ctrl-row">
                        <label>Secondary</label>
                        <input type="color" name="secondary_color" id="secondaryColor" value="<?= $template['secondary_color'] ?? '#F3921D' ?>" class="form-control form-control-sm p-0" style="width:44px;height:32px">
                        <small class="text-muted">Accents</small>
                    </div>

                    <span class="ctrl-label mt-3">Visibility</span>
                    <div class="ctrl-toggle">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="showQr" name="show_qr_code" value="1" <?= ($template['show_qr_code'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="showQr">QR Verification footer</label>
                        </div>
                    </div>
                    <div class="ctrl-toggle">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="showSig" name="show_signature" value="1" <?= ($template['show_signature'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="showSig">Signature block</label>
                        </div>
                    </div>

                    <span class="ctrl-label mt-3">Background Image <small class="text-muted fw-normal">(optional override)</small></span>
                    <input type="file" name="background_image" id="bgImageInput" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
                    <p class="text-muted fs-10 mt-1">Recommended: 3508 × 2480 px (A4 @ 300 DPI). Uploading replaces the default paper texture.</p>
                    <?php if (!empty($template['background_image'])): ?>
                        <p class="fs-11 mt-1"><span class="badge bg-success">Current BG:</span> <?= esc(basename($template['background_image'])) ?></p>
                    <?php endif; ?>
                </div>

                <!-- HTML Mode -->
                <div id="htmlEditorSection" style="display:<?= ($template['template_mode'] ?? '') === 'html' ? 'block' : 'none' ?>">
                    <div class="ctrl-section">
                        <div class="alert alert-warning p-2 fs-11 mb-3">
                            <strong>HTML Mode Tips:</strong><br>
                            Engine: Dompdf — avoid Flexbox/Grid; use Tables or absolute positioning.<br>
                            Dimensions: A4 Landscape ≈ 1120 × 794 px.<br>
                            Use <code>base_url()</code> for image paths.
                        </div>
                        <label class="ctrl-label">Custom HTML</label>
                        <textarea name="custom_html" id="custom_html" class="form-control fs-12 font-monospace" rows="16"><?= esc($template['custom_html'] ?? '') ?></textarea>
                        <div class="mt-2 bg-light p-2 rounded border">
                            <label class="fs-11 fw-bold d-block mb-1 text-primary">Dynamic Placeholders:</label>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-white text-dark border" title="Student's full name">{{name}}</span>
                                <span class="badge bg-white text-dark border" title="Course title">{{course}}</span>
                                <span class="badge bg-white text-dark border" title="Issue date">{{date}}</span>
                                <span class="badge bg-white text-dark border" title="Certificate code">{{code}}</span>
                                <span class="badge bg-white text-dark border" title="QR code img tag">{{qr_code}}</span>
                                <span class="badge bg-white text-dark border" title="Signature img tag">{{signature}}</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-info w-100 mt-2" id="previewHtmlBtn">
                            <i class="ti ti-eye me-1"></i> Live HTML Preview
                        </button>
                    </div>
                </div>

                <!-- Additional text -->
                <div class="ctrl-section">
                    <label class="ctrl-label">Additional Text / Notes <small class="text-muted fw-normal">(optional footer line)</small></label>
                    <input type="text" name="additional_text" class="form-control form-control-sm" value="<?= esc($template['additional_text'] ?? '') ?>" placeholder="e.g. Accredited by IBMEC Nigeria">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- HTML Preview Modal -->
<div class="modal fade" id="htmlPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">HTML Certificate Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="background:#e9ecef;display:flex;justify-content:center;align-items:center;min-height:600px">
                <iframe id="htmlPreviewFrame" style="width:1122px;height:793px;border:1px solid #ccc;background:#fff;box-shadow:0 0 15px rgba(0,0,0,.2)"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
// ── Scale preview to fit available width ──────────────────
function scalePreview() {
    const scaler = document.getElementById('certPreviewScaler');
    const pane   = scaler.parentElement;
    const avail  = pane.clientWidth - 56; // padding
    const scale  = Math.min(1, avail / 1056);
    scaler.style.transform = `scale(${scale})`;
    scaler.style.marginBottom = ((748 * scale) - 748) + 'px';
}
window.addEventListener('resize', scalePreview);
scalePreview();

// ── Mode toggle ───────────────────────────────────────────
document.getElementById('templateMode').addEventListener('change', function() {
    const isHtml = this.value === 'html';
    document.getElementById('htmlEditorSection').style.display = isHtml ? 'block' : 'none';
    document.getElementById('builderControls').style.display   = isHtml ? 'none'  : 'block';
});

// ── Certificate type → ribbon + statement ─────────────────
const typeData = {
    training: { ribbon: 'Professional Training Programme', statement: 'has successfully completed all requirements of the professional training programme' },
    webinar:  { ribbon: 'Professional Webinar',            statement: 'attended and successfully completed the professional webinar' },
    course:   { ribbon: 'Course Completion',               statement: 'has successfully completed the online course' },
};
document.getElementById('certType').addEventListener('change', function() {
    const d = typeData[this.value] || typeData.training;
    document.getElementById('prev-ribbon-text').textContent  = d.ribbon;
    document.getElementById('prev-statement').textContent    = d.statement;
});

// ── Color pickers → live CSS vars ────────────────────────
document.getElementById('primaryColor').addEventListener('input', function() {
    document.documentElement.style.setProperty('--brand', this.value);
    document.documentElement.style.setProperty('--navy',  this.value);
});
document.getElementById('secondaryColor').addEventListener('input', function() {
    document.documentElement.style.setProperty('--accent', this.value);
});

// ── Visibility toggles ────────────────────────────────────
document.getElementById('showQr').addEventListener('change', function() {
    document.getElementById('prev-verify-wrap').style.display = this.checked ? '' : 'none';
});
document.getElementById('showSig').addEventListener('change', function() {
    document.getElementById('prev-sign-wrap').style.display = this.checked ? '' : 'none';
});
// Apply initial visibility
if (!document.getElementById('showQr').checked) document.getElementById('prev-verify-wrap').style.display = 'none';
if (!document.getElementById('showSig').checked) document.getElementById('prev-sign-wrap').style.display = 'none';

// ── Background image preview ──────────────────────────────
document.getElementById('bgImageInput').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('certPreview').style.backgroundImage = `url(${e.target.result})`;
            document.getElementById('certPreview').style.backgroundSize  = 'cover';
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// ── HTML preview modal ────────────────────────────────────
document.getElementById('previewHtmlBtn').addEventListener('click', function() {
    let html = document.getElementById('custom_html').value;
    const dummy = {
        '{{name}}':      'Adebayo Martins',
        '{{course}}':    'Mastering the ATS: Build a CV That Gets Interviews',
        '{{date}}':      '<?= date('j F Y') ?>',
        '{{code}}':      'JR-CV-<?= date('Y') ?>-PREVIEW',
        '{{qr_code}}':   '<div style="display:inline-block;width:80px;height:80px;background:#0A2F57;border-radius:4px"></div>',
        '{{signature}}': '<div style="display:inline-block;width:150px;height:50px;border-bottom:1px solid #333;font-style:italic;line-height:50px;font-family:cursive">Jane Smith</div>',
    };
    for (const [k, v] of Object.entries(dummy)) html = html.split(k).join(v);
    const iframe = document.getElementById('htmlPreviewFrame');
    const doc = iframe.contentWindow.document;
    doc.open(); doc.write(html); doc.close();
    new bootstrap.Modal(document.getElementById('htmlPreviewModal')).show();
});

// ── Save ──────────────────────────────────────────────────
document.getElementById('saveTemplateBtn').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving…';

    const formData = new FormData(document.getElementById('templateForm'));

    fetch('<?= base_url('admin/elearning/save-certificate-template') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Save Template';
        if (data.success) {
            toastr.success(data.message || 'Certificate template saved!');
        } else {
            toastr.error(data.message || 'Save failed.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Save Template';
        toastr.error('Network error — please try again.');
    });
});
</script>
<?= $this->endSection() ?>
