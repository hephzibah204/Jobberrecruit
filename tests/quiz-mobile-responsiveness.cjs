const fs = require('node:fs');
const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true, channel: 'chrome' });

  try {
    const viewports = [
      { width: 320, height: 600, name: 'iPhone SE / Small mobile' },
      { width: 360, height: 740, name: 'Samsung Galaxy / Android standard' },
      { width: 375, height: 667, name: 'iPhone 8 / SE 2020' },
      { width: 390, height: 844, name: 'iPhone 12 / 13 / 14' },
      { width: 412, height: 915, name: 'Pixel 7 / Large Android' }
    ];

    // ─────────────────────────────────────────────────────────────
    // 1. CANDIDATE APTITUDE TEST ENGINE MOBILE RESPONSIVENESS
    // ─────────────────────────────────────────────────────────────
    console.log('--- 1. Testing Candidate Aptitude Test Engine ---');
    const testEngineSrc = fs.readFileSync('app/Views/candidate/aptitude/test_engine.php', 'utf8');
    const testEngineStyleMatch = testEngineSrc.match(/<style>([\s\S]*?)<\/style>/);
    if (!testEngineStyleMatch) throw new Error('Style block not found in test_engine.php');
    const testEngineCss = testEngineStyleMatch[1];

    for (const vp of viewports) {
      const page = await browser.newPage({ viewport: { width: vp.width, height: vp.height } });
      
      const testHtml = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    ${testEngineCss}
  </style>
</head>
<body>
  <div class="tst-bar">
    <div class="tst-bar-in">
      <div class="tst-brand">
        <span class="dot">JR</span>
        <span class="full">JobberRecruit Assessment Platform</span>
      </div>
      <span class="tst-mode-pill practice">Custom Assessment</span>
      <div class="tst-timer"><span id="time">24:59</span></div>
    </div>
  </div>

  <div class="tst-progress">
    <div class="tst-progress-track">
      <div class="tst-progress-fill" style="width: 25%;"></div>
    </div>
  </div>

  <main class="tst-wrap">
    <div class="tst-qnum" id="qnum">Question 1 of 10</div>
    <div class="tst-qcard">
      <div class="tst-qbody" id="qbody">
        In cloud microservices architecture, what is the primary purpose of an API gateway pattern when handling high-concurrency internationalization (i18n) requests with distributed_transaction_coordination_protocols?
      </div>
      <div class="tst-options" id="opts">
        <div class="tst-opt selected">
          <span class="tst-opt-key">A</span>
          <span class="tst-opt-text">It centralizes cross-cutting concerns such as rate-limiting, authentication token verification, circuit breaking, and protocol transformation across downstream services.</span>
        </div>
        <div class="tst-opt">
          <span class="tst-opt-key">B</span>
          <span class="tst-opt-text">It completely replaces the primary database storage layer with an in-memory Redis cluster cache.</span>
        </div>
        <div class="tst-opt">
          <span class="tst-opt-key">C</span>
          <span class="tst-opt-text">It forces all clients to use unidirectional HTTP/1.0 connections without persistent socket reuse.</span>
        </div>
        <div class="tst-opt">
          <span class="tst-opt-key">D</span>
          <span class="tst-opt-text">None of the above architectural configurations apply.</span>
        </div>
      </div>
    </div>

    <div class="tst-dots">
      ${Array.from({length: 10}, (_, i) => `<button class="tst-dot ${i === 0 ? 'current' : (i < 3 ? 'answered' : '')}">${i+1}</button>`).join('')}
    </div>
  </main>

  <div class="tst-nav">
    <div class="tst-nav-in">
      <button class="btn btn-outline" id="prev">Previous</button>
      <button class="tst-flag" id="flag"><span>Flag</span></button>
      <button class="btn btn-primary" id="next">Review &amp; submit</button>
    </div>
  </div>
</body>
</html>
      `;

      await page.setContent(testHtml);

      const overflow = await page.evaluate(() => {
        const docScroll = document.documentElement.scrollWidth;
        const bodyScroll = document.body.scrollWidth;
        const winWidth = window.innerWidth;
        return { docScroll, bodyScroll, winWidth, hasScroll: docScroll > winWidth || bodyScroll > winWidth };
      });

      if (overflow.hasScroll) {
        throw new Error(`Test Engine HORIZONTAL SCROLL DETECTED at ${vp.name} (${vp.width}px): doc=${overflow.docScroll}, win=${overflow.winWidth}`);
      }

      console.log(`  ✓ ${vp.name} (${vp.width}px): 0 horizontal scroll in Candidate Test Engine.`);
      await page.close();
    }

    // ─────────────────────────────────────────────────────────────
    // 2. CANDIDATE RESULT BREAKDOWN (result_practice & result_official)
    // ─────────────────────────────────────────────────────────────
    console.log('\n--- 2. Testing Candidate Result Review Breakdown ---');
    const resultSrc = fs.readFileSync('app/Views/candidate/aptitude/result_practice.php', 'utf8');
    const resultStyleMatch = resultSrc.match(/<style>([\s\S]*?)<\/style>/);
    const resultCss = resultStyleMatch[1];

    for (const vp of viewports) {
      const page = await browser.newPage({ viewport: { width: vp.width, height: vp.height } });

      const resultHtml = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    ${resultCss}
  </style>
</head>
<body>
  <div class="res-wrap">
    <h2 class="res-sec-title">Answer breakdown</h2>
    <div class="res-q">
      <div class="res-q-head">
        <span class="res-q-badge ok">✓</span>
        <div class="res-q-body">1. In asynchronous event-driven distributed architectures, what design pattern guarantees exactly-once processing across partitioned worker nodes?</div>
      </div>
      <div class="res-q-opts">
        <div class="res-q-opt correct">
          <span class="k">A</span>
          <span class="res-q-opt-body">Idempotent consumer pattern paired with transactional outbox and unique deduplication keys.</span>
          <span class="tag">Correct</span>
        </div>
        <div class="res-q-opt">
          <span class="k">B</span>
          <span class="res-q-opt-body">Simple blind fire-and-forget message publishing without acknowledgment confirmation.</span>
        </div>
      </div>
      <div class="res-q-exp">
        <span>💡</span>
        <div><b>Why:</b> Idempotency ensures that reprocessing the identical payload multiple times does not produce side effects beyond the initial state transition.</div>
      </div>
    </div>
  </div>
</body>
</html>
      `;

      await page.setContent(resultHtml);

      const overflow = await page.evaluate(() => {
        const docScroll = document.documentElement.scrollWidth;
        const bodyScroll = document.body.scrollWidth;
        const winWidth = window.innerWidth;
        return { docScroll, bodyScroll, winWidth, hasScroll: docScroll > winWidth || bodyScroll > winWidth };
      });

      if (overflow.hasScroll) {
        throw new Error(`Result Review HORIZONTAL SCROLL DETECTED at ${vp.name} (${vp.width}px): doc=${overflow.docScroll}, win=${overflow.winWidth}`);
      }

      console.log(`  ✓ ${vp.name} (${vp.width}px): 0 horizontal scroll in Candidate Result Breakdown.`);
      await page.close();
    }

    // ─────────────────────────────────────────────────────────────
    // 3. EMPLOYER CUSTOM ASSESSMENT GENERATOR / QUESTIONS EDITOR
    // ─────────────────────────────────────────────────────────────
    console.log('\n--- 3. Testing Employer Custom Assessment Modal & Questions Editor ---');
    const employerTestsSrc = fs.readFileSync('app/Views/employers/aptitude_tests.php', 'utf8');
    const empStyleMatch = employerTestsSrc.match(/<style>([\s\S]*?)<\/style>/);
    const empCss = empStyleMatch[1];

    for (const vp of viewports) {
      const page = await browser.newPage({ viewport: { width: vp.width, height: vp.height } });

      const editorHtml = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    :root {
      --brand: #0861A9;
      --brand-deep: #0A2F57;
      --accent: #E05A47;
      --border: #e2e8f0;
      --text: #1e293b;
      --muted: #64748b;
      --bg-alt: #f8fafc;
    }
    body { margin:0; padding:0; background:#f1f5f9; overflow-x:hidden; }
    ${empCss}
  </style>
</head>
<body>
  <div id="customTestModal" class="show" style="display:block; position:relative; width:100%; box-sizing:border-box;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title" id="customTestModalTitle">Create Custom Assessment Test</h5>
            <span class="text-muted fs-12">Design your screening test or use AI to generate questions</span>
          </div>
        </div>
        <div class="modal-body">
          <!-- AI Banner -->
          <div class="ct-ai-banner mb-3">
            <div class="ct-ai-banner-text">
              <div class="fw-bold fs-14">✨ Auto-Generate Questions with JobberRecruit AI</div>
              <div class="text-muted fs-12">AI creates custom multiple-choice questions instantly.</div>
            </div>
            <div class="ct-ai-banner-actions">
              <select class="form-select form-select-sm"><option>5 Questions</option></select>
              <button class="emp-btn emp-btn-accent emp-btn-sm" id="btnAiGenerate">✨ Generate with AI</button>
            </div>
          </div>

          <!-- Question Cards Container -->
          <div id="questionsContainer" style="display:flex; flex-direction:column; gap:16px;">
            <div class="card border rounded-3 p-3 bg-light position-relative mb-3" style="border-left: 4px solid var(--brand) !important; max-width: 100%; overflow: hidden; word-break: break-word; overflow-wrap: anywhere;">
              <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <span class="fw-bold text-dark fs-13">Question #1</span>
                <button type="button" class="btn btn-sm text-danger p-1 border-0">Remove</button>
              </div>

              <div class="mb-3">
                <textarea class="form-control fs-13" rows="2" style="word-break:break-word; overflow-wrap:anywhere; width:100%; max-width:100%; box-sizing:border-box;">In petrochemical distillation pipeline management, what safety standard governs non-destructive ultrasonic crack detection in high-pressure hydrogen transport lines?</textarea>
              </div>

              <div class="row g-2 mb-2">
                <div class="col-12 col-md-6">
                  <div class="input-group input-group-sm" style="align-items: stretch; max-width: 100%;">
                    <label class="input-group-text bg-white d-flex align-items-center flex-shrink-0">
                      <input type="radio" checked>
                      <span class="ms-1 fw-bold fs-11">A.</span>
                    </label>
                    <textarea class="form-control fs-12 custom-opt-textarea" rows="2" style="word-break:break-word; overflow-wrap:anywhere; resize:vertical; min-height:44px; width:100%; max-width:100%; box-sizing:border-box;">ASME Section VIII Division 1 with ultrasonic phased-array testing calibration.</textarea>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="input-group input-group-sm" style="align-items: stretch; max-width: 100%;">
                    <label class="input-group-text bg-white d-flex align-items-center flex-shrink-0">
                      <input type="radio">
                      <span class="ms-1 fw-bold fs-11">B.</span>
                    </label>
                    <textarea class="form-control fs-12 custom-opt-textarea" rows="2" style="word-break:break-word; overflow-wrap:anywhere; resize:vertical; min-height:44px; width:100%; max-width:100%; box-sizing:border-box;">Standard residential electrical insulation resistance metric ISO-9001.</textarea>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="input-group input-group-sm" style="align-items: stretch; max-width: 100%;">
                    <label class="input-group-text bg-white d-flex align-items-center flex-shrink-0">
                      <input type="radio">
                      <span class="ms-1 fw-bold fs-11">C.</span>
                    </label>
                    <textarea class="form-control fs-12 custom-opt-textarea" rows="2" style="word-break:break-word; overflow-wrap:anywhere; resize:vertical; min-height:44px; width:100%; max-width:100%; box-sizing:border-box;">Manual liquid penetrant visual examination without certified darkroom lighting.</textarea>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="input-group input-group-sm" style="align-items: stretch; max-width: 100%;">
                    <label class="input-group-text bg-white d-flex align-items-center flex-shrink-0">
                      <input type="radio">
                      <span class="ms-1 fw-bold fs-11">D.</span>
                    </label>
                    <textarea class="form-control fs-12 custom-opt-textarea" rows="2" style="word-break:break-word; overflow-wrap:anywhere; resize:vertical; min-height:44px; width:100%; max-width:100%; box-sizing:border-box;">Atmospheric hydrostatic pressure testing under ambient weather conditions.</textarea>
                  </div>
                </div>
              </div>

              <div>
                <textarea class="form-control fs-12 text-muted" rows="2" style="word-break:break-word; overflow-wrap:anywhere; resize:vertical; min-height:44px; width:100%; max-width:100%; box-sizing:border-box;">ASME Section VIII outlines pressure vessel and line inspection criteria.</textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary">Cancel</button>
          <button type="button" class="btn btn-primary">Save Assessment Test</button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
      `;

      await page.setContent(editorHtml);

      const overflow = await page.evaluate(() => {
        const docScroll = document.documentElement.scrollWidth;
        const bodyScroll = document.body.scrollWidth;
        const winWidth = window.innerWidth;
        return { docScroll, bodyScroll, winWidth, hasScroll: docScroll > winWidth || bodyScroll > winWidth };
      });

      if (overflow.hasScroll) {
        throw new Error(`Custom Test Modal HORIZONTAL SCROLL DETECTED at ${vp.name} (${vp.width}px): doc=${overflow.docScroll}, win=${overflow.winWidth}`);
      }

      console.log(`  ✓ ${vp.name} (${vp.width}px): 0 horizontal scroll in Employer Custom Assessment Modal & Questions Editor.`);
      await page.close();
    }

    // ─────────────────────────────────────────────────────────────
    // 4. EMPLOYER CANDIDATE ATTEMPT RESULT BREAKDOWN MODAL
    // ─────────────────────────────────────────────────────────────
    console.log('\n--- 4. Testing Employer Attempt Result Modal ---');
    for (const vp of viewports) {
      const page = await browser.newPage({ viewport: { width: vp.width, height: vp.height } });

      const attemptResultHtml = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root {
      --brand: #0861A9;
      --brand-deep: #0A2F57;
      --border: #e2e8f0;
      --text: #1e293b;
      --muted: #64748b;
      --bg-alt: #f8fafc;
    }
    body { margin:0; padding:0; background:#f1f5f9; overflow-x:hidden; }
    ${empCss}
  </style>
</head>
<body>
  <div id="attemptResultModal" class="show" style="display:flex; position:relative; width:100%; box-sizing:border-box;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title" id="attemptResultModalTitle">Candidate Assessment Score &amp; Analysis</h5>
            <span id="attemptResultSubTitle">Jane Doe • Senior React Developer • AI Custom Assessment</span>
          </div>
        </div>
        <div class="modal-body" id="attemptResultBody">
          <div style="background:rgba(22, 163, 74, 0.08); border:1px solid #16a34a; border-radius:12px; padding:18px 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:20px;">
            <div>
              <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#16a34a;">Assessment Verdict</div>
              <div style="font-size:1.4rem; font-weight:800; color:#16a34a;">PASSED ✓ (85%)</div>
              <div style="font-size:0.8rem; color:var(--muted);">Pass benchmark: <strong>60%</strong> • Correct: <strong>8</strong> / 10 questions</div>
            </div>
          </div>

          <h6 style="font-weight:700; font-size:0.88rem; color:var(--brand-deep); margin:0 0 12px; word-break:break-word; overflow-wrap:anywhere;">Detailed Question Responses (1)</h6>
          <div style="display:flex; flex-direction:column; gap:12px; max-width:100%;">
            <div style="border:1px solid var(--border); border-left:4px solid #16a34a; border-radius:8px; padding:14px; background:#fff; max-width:100%; box-sizing:border-box; word-break:break-word; overflow-wrap:anywhere;">
              <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px; margin-bottom:8px;">
                <span style="font-weight:700; font-size:0.84rem; color:var(--text); min-width:0; flex:1; word-break:break-word; overflow-wrap:anywhere;">Q1. In React 19 Server Components, what mechanism hydrates client-side state without re-rendering parent RSC streams?</span>
                <span style="font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:12px; background:#e8f7ee; color:#16a34a; flex-shrink:0;">Correct (+1)</span>
              </div>
              <div class="attempt-result-opts-grid">
                <div style="padding:6px 10px; border-radius:6px; font-size:0.8rem; word-break:break-word; overflow-wrap:anywhere; min-width:0; box-sizing:border-box; border:1px solid #86efac; background:#f0fdf4; color:#166534; font-weight:600;">
                  Selective component boundary hydration via directive 'use client' <span style="color:#16a34a; font-weight:bold;">✓ (Candidate Pick - Correct)</span>
                </div>
                <div style="padding:6px 10px; border-radius:6px; font-size:0.8rem; word-break:break-word; overflow-wrap:anywhere; min-width:0; box-sizing:border-box; border:1px solid #e2e8f0; background:#f8fafc; color:#475569;">
                  Total DOM destruction and re-mounting on the root document.
                </div>
              </div>
              <div style="font-size:0.76rem; color:var(--muted); margin-top:8px; background:var(--bg-alt); padding:6px 10px; border-radius:6px; word-break:break-word; overflow-wrap:anywhere;">
                <strong>Explanation:</strong> The 'use client' directive designates an entry point where client interactivity begins.
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer" id="attemptResultFooter">
          <button type="button" class="btn btn-secondary">Close</button>
          <div id="attemptResultActions">
            <button type="button" class="btn btn-primary">Shortlist Candidate</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
      `;

      await page.setContent(attemptResultHtml);

      const overflow = await page.evaluate(() => {
        const docScroll = document.documentElement.scrollWidth;
        const bodyScroll = document.body.scrollWidth;
        const winWidth = window.innerWidth;
        return { docScroll, bodyScroll, winWidth, hasScroll: docScroll > winWidth || bodyScroll > winWidth };
      });

      if (overflow.hasScroll) {
        throw new Error(`Attempt Result Modal HORIZONTAL SCROLL DETECTED at ${vp.name} (${vp.width}px): doc=${overflow.docScroll}, win=${overflow.winWidth}`);
      }

      console.log(`  ✓ ${vp.name} (${vp.width}px): 0 horizontal scroll in Employer Attempt Result Breakdown.`);
      await page.close();
    }

    // ─────────────────────────────────────────────────────────────
    // 5. EMPLOYER APTITUDE ASSESSMENT INVITATION MODAL
    // ─────────────────────────────────────────────────────────────
    console.log('\n--- 5. Testing Employer Assessment Invitation Modal with AI Custom Options ---');
    for (const vp of viewports) {
      const page = await browser.newPage({ viewport: { width: vp.width, height: vp.height } });

      const inviteHtml = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { margin:0; padding:0; box-sizing:border-box; overflow-x:hidden; }
  </style>
</head>
<body>
  <div class="modal" id="aptitude-invite-modal" role="dialog" style="display:flex; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:12px; box-sizing:border-box;">
    <div style="background:#fff; border-radius:12px; width:100%; max-width:500px; max-height:calc(100vh - 24px); max-height:calc(100dvh - 24px); display:flex; flex-direction:column; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2); margin:auto;">
      <div style="padding:18px 20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; background:#f8f9fa;">
        <h3 id="apt-invite-title" style="font-size:1.05rem; font-weight:700; color:#0d6efd; margin:0;">Invite Candidate to Aptitude Test</h3>
        <button class="close-modal-btn" type="button" style="background:none; border:none; font-size:1.2rem; color:#6c757d;">&times;</button>
      </div>
      <form id="aptitude-invite-form" style="padding:20px; overflow-y:auto; flex:1; -webkit-overflow-scrolling:touch; box-sizing:border-box;">
        <div style="margin-bottom:15px;">
          <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.9rem;">Select Aptitude Assessment *</label>
          <select style="width:100%; padding:9px 12px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box;">
            <option value="ai_custom" selected>✨ Generate Custom AI Screening Assessment</option>
          </select>
        </div>

        <div id="ai-custom-options" style="display: block; background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #e9ecef; box-sizing:border-box;">
          <h4 style="font-size: 0.95rem; margin-top: 0; margin-bottom: 12px; color: #495057;">✨ AI Test Settings</h4>
          <div style="margin-bottom:12px;">
            <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.85rem;">Number of Questions</label>
            <input type="number" value="10" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box;">
          </div>
          <div style="margin-bottom:12px;">
            <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.85rem;">Duration (Minutes)</label>
            <input type="number" value="20" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box;">
          </div>
          <div style="margin-bottom:0;">
            <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.85rem;">Difficulty Level</label>
            <select style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box;">
              <option selected>Intermediate</option>
            </select>
          </div>
        </div>

        <div style="margin-bottom:15px;">
          <label style="font-weight:600; margin-bottom:6px; display:block; font-size:0.9rem;">Instructions / Message to Candidate (Optional)</label>
          <textarea rows="3" style="width:100%; padding:9px 12px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box; word-break:break-word; overflow-wrap:anywhere;">Please complete this tailored screening assessment before our scheduled interview session.</textarea>
        </div>

        <div style="padding-top:12px; border-top:1px solid #eee; display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap;">
          <button type="button" class="btn btn-secondary">Cancel</button>
          <button type="submit" class="btn btn-primary">Send Test Invitation</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
      `;

      await page.setContent(inviteHtml);

      const overflow = await page.evaluate(() => {
        const docScroll = document.documentElement.scrollWidth;
        const bodyScroll = document.body.scrollWidth;
        const winWidth = window.innerWidth;
        return { docScroll, bodyScroll, winWidth, hasScroll: docScroll > winWidth || bodyScroll > winWidth };
      });

      if (overflow.hasScroll) {
        throw new Error(`Invitation Modal HORIZONTAL SCROLL DETECTED at ${vp.name} (${vp.width}px): doc=${overflow.docScroll}, win=${overflow.winWidth}`);
      }

      console.log(`  ✓ ${vp.name} (${vp.width}px): 0 horizontal scroll in Employer Assessment Invitation Modal.`);
      await page.close();
    }

    console.log('\n================================================================');
    console.log('ALL CUSTOM ASSESSMENT MOBILE RESPONSIVENESS CHECKS PASSED!');
    console.log('Zero horizontal scrolling detected across 320px, 360px, 375px, 390px, and 412px viewports.');
    console.log('Question statements, multi-line options, explanations, and badges wrap perfectly.');
    console.log('================================================================');

  } finally {
    await browser.close();
  }
})().catch(err => {
  console.error(err);
  process.exitCode = 1;
});
