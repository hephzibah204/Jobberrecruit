<?= $this->extend('layouts/employer') ?>
<?= $this->section('content') ?>

<!-- ══ PAGE HEADER ══ -->
<div class="page-head">
  <div>
    <h1><svg aria-hidden="true"><use href="#i-bulb"/></svg> Screening &amp; Aptitude Tests <span style="font-size:.75rem; font-weight:600; background:var(--brand-light); color:var(--brand); padding:3px 10px; border-radius:20px; margin-left:6px; vertical-align:middle;">Recruiter Suite</span></h1>
    <p>Monitor invited applicants, track real-time test progress, review detailed score breakdowns, and advance candidates through recruitment stages.</p>
  </div>
  <div class="page-actions">
    <button type="button" class="emp-btn emp-btn-accent emp-btn-sm" onclick="openCreateCustomTestModal()">
      <svg aria-hidden="true" width="15" height="15"><use href="#i-plus"/></svg> Create Custom Test
    </button>
    <a href="<?= base_url('employer/applications') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true" width="15" height="15"><use href="#i-search-user"/></svg> All Applications
    </a>
    <button type="button" class="emp-btn emp-btn-primary emp-btn-sm" id="toggleLibraryBtn" onclick="toggleLibrary()">
      <svg aria-hidden="true" width="15" height="15"><use href="#i-books"/></svg>
      Assessment Library &amp; Links (<span id="testLibCount"><?= count($tests ?? []) ?></span>)
    </button>
  </div>
</div>

<!-- ══ KPI STATS ══ -->
<section class="stats" aria-label="Assessment statistics" style="grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));">
  <div class="stat">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-mail"/></svg></span></div>
    <div class="stat-num"><?= number_format($kpiStats['total_invited'] ?? 0) ?></div>
    <div class="stat-lbl">Total Invited</div>
  </div>
  <div class="stat" style="--st-bar:var(--info,#0dcaf0);--st-icbg:rgba(13,202,240,.1);--st-ic:#0dcaf0">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-clock"/></svg></span></div>
    <div class="stat-num"><?= number_format($kpiStats['in_progress'] ?? 0) ?></div>
    <div class="stat-lbl">In Progress</div>
  </div>
  <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></span></div>
    <div class="stat-num"><?= number_format($kpiStats['completed'] ?? 0) ?></div>
    <div class="stat-lbl">Completed</div>
  </div>
  <div class="stat" style="--st-bar:var(--success);--st-icbg:var(--success-light);--st-ic:var(--success)">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-trophy"/></svg></span></div>
    <div class="stat-num"><?= number_format($kpiStats['passed'] ?? 0) ?></div>
    <div class="stat-lbl">Passed</div>
  </div>
  <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-chart-pie"/></svg></span></div>
    <div class="stat-num"><?= number_format($kpiStats['pass_rate'] ?? 0) ?>%</div>
    <div class="stat-lbl">Pass Rate</div>
  </div>
  <div class="stat" style="--st-bar:#6f42c1;--st-icbg:rgba(111,66,193,.12);--st-ic:#6f42c1">
    <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-award"/></svg></span></div>
    <div class="stat-num"><?= number_format($kpiStats['avg_score'] ?? 0) ?>%</div>
    <div class="stat-lbl">Avg Score</div>
  </div>
</section>

<!-- ══ COLLAPSIBLE TEST LIBRARY ══ -->
<div id="testLibrarySection" style="display:none; margin-bottom:24px;">
  <section class="card" aria-label="Assessment Template Library">
    <div class="card-head">
      <div>
        <strong style="font-size:.95rem;">
          <svg aria-hidden="true" width="16" height="16" style="vertical-align:middle; margin-right:6px;"><use href="#i-books"/></svg>
          Assessment Templates &amp; Sharable Links
        </strong>
        <p style="margin:4px 0 0; font-size:.8rem; color:var(--muted);">Copy standalone assessment links for public posting, job boards, or manual sharing.</p>
      </div>
      <div style="display:flex; gap:8px; align-items:center;">
        <button type="button" class="emp-btn emp-btn-accent emp-btn-sm" onclick="openCreateCustomTestModal()">
          <svg aria-hidden="true" width="13" height="13"><use href="#i-plus"/></svg> New Custom Test
        </button>
        <button type="button" class="ic-btn" onclick="toggleLibrary()" aria-label="Close library" title="Close">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-x"/></svg>
        </button>
      </div>
    </div>
    <div style="padding:20px;">
      <?php if (!empty($tests)): ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:16px;">
          <?php foreach ($tests as $t):
            $slug = $t['slug'];
            $desc = $t['description'] ?: 'Standard candidate screening assessment.';
            $officialUrl = base_url('aptitude/' . $slug . '/start?ref=' . ($employerId ?? 0));
            $practiceUrl = base_url('aptitude/' . $slug . '/practice');
            $isCustom = !empty($t['employer_id']) && ($t['employer_id'] == ($employerId ?? 0));
          ?>
            <div style="border:1px solid <?= $isCustom ? 'var(--brand)' : 'var(--border)' ?>; border-radius:var(--radius); background:#fff; padding:16px; display:flex; flex-direction:column; gap:10px; position:relative;">
              <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                <span class="pill" style="background:var(--brand-light); color:var(--brand); font-size:.7rem; padding:3px 10px;"><?= esc($t['category_name'] ?? 'General') ?></span>
                <div style="display:flex; gap:4px; align-items:center;">
                  <?php if ($isCustom): ?>
                    <span class="pill" style="background:rgba(237,144,32,0.15); color:#d97706; font-size:.68rem; padding:2px 8px; font-weight:700;">★ Custom</span>
                  <?php endif; ?>
                  <span class="pill" style="background:var(--bg-alt); color:var(--text-muted); font-size:.7rem; padding:3px 10px;"><?= esc(ucfirst($t['difficulty'] ?? 'medium')) ?></span>
                </div>
              </div>
              <div style="font-weight:700; font-size:.9rem; color:var(--text);"><?= esc($t['title']) ?></div>
              <div style="font-size:.78rem; color:var(--muted); line-height:1.4; flex:1;"><?= esc(character_limiter($desc, 90)) ?></div>
              <div style="display:flex; gap:16px; font-size:.75rem; color:var(--muted); padding:8px 0; border-top:1px solid var(--border);">
                <span><svg aria-hidden="true" width="12" height="12"><use href="#i-clock"/></svg> <?= esc($t['duration_mins'] ?? 20) ?> mins</span>
                <span><svg aria-hidden="true" width="12" height="12"><use href="#i-help-circle"/></svg> <?= esc($t['num_questions'] ?? 10) ?> Qs</span>
                <span><svg aria-hidden="true" width="12" height="12"><use href="#i-target"/></svg> <?= esc($t['pass_threshold'] ?? 50) ?>% Pass</span>
              </div>
              <div style="display:flex; gap:8px;">
                <button type="button" class="emp-btn emp-btn-primary emp-btn-sm" style="flex:1; justify-content:center;"
                        onclick="copyTestLink('<?= esc($officialUrl, 'js') ?>', this)">
                  <svg aria-hidden="true" width="13" height="13"><use href="#i-copy"/></svg> Copy Link
                </button>
                <button type="button" class="ic-btn"
                        onclick="openEmployerShareModal('<?= esc($t['title'], 'js') ?>', '<?= esc($officialUrl, 'js') ?>', '<?= esc($practiceUrl, 'js') ?>')"
                        title="More share options">
                  <svg aria-hidden="true" width="15" height="15"><use href="#i-share"/></svg>
                </button>
                <?php if ($isCustom): ?>
                  <button type="button" class="ic-btn" onclick="openEditCustomTestModal(<?= (int)$t['id'] ?>)" title="Edit custom test">
                    <svg aria-hidden="true" width="14" height="14"><use href="#i-pencil"/></svg>
                  </button>
                  <button type="button" class="ic-btn ic-btn--danger" onclick="deleteCustomTest(<?= (int)$t['id'] ?>, '<?= esc($t['title'], 'js') ?>')" title="Delete custom test">
                    <svg aria-hidden="true" width="14" height="14"><use href="#i-trash"/></svg>
                  </button>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty">
          <div class="empty-ic"><svg aria-hidden="true"><use href="#i-books"/></svg></div>
          <h3>No assessment templates</h3>
          <p>No assessment templates are currently published.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>
</div>

<!-- ══ CANDIDATE ASSESSMENT ROSTER ══ -->
<section class="card" aria-label="Candidate Assessment Roster">
  <div class="card-head">
    <div>
      <strong style="font-size:.95rem; display:flex; align-items:center; gap:8px;">
        <svg aria-hidden="true" width="17" height="17"><use href="#i-users"/></svg>
        Candidate Assessment Roster
        <span class="pill" style="background:var(--bg-alt); color:var(--muted); font-size:.7rem; padding:2px 8px;"><?= count($invitations ?? []) ?></span>
      </strong>
      <p style="margin:4px 0 0; font-size:.8rem; color:var(--muted);">Real-time candidate progress, results, scoring metrics, and hiring stage transitions.</p>
    </div>
    <form method="get" action="<?= base_url('employer/aptitude-tests') ?>" class="toolbar" id="filterForm" style="gap:8px; flex-wrap:wrap; justify-content:flex-end;">
      <div class="search-wrap" style="min-width:160px;">
        <svg aria-hidden="true"><use href="#i-search"/></svg>
        <input class="input" type="search" name="search" placeholder="Search candidate…" value="<?= esc($searchQuery ?? '') ?>" aria-label="Search candidates">
      </div>
      <select class="select" name="job_id" onchange="document.getElementById('filterForm').submit()" aria-label="Filter by job">
        <option value="">All Jobs</option>
        <?php foreach ($myJobs as $mj): ?>
          <option value="<?= $mj->id ?>" <?= (($filterJob ?? 0) == $mj->id) ? 'selected' : '' ?>>
            <?= esc(character_limiter($mj->title, 28)) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <select class="select" name="status" onchange="document.getElementById('filterForm').submit()" aria-label="Filter by status">
        <option value="">All Statuses</option>
        <option value="completed" <?= (($filterStatus ?? '') === 'completed') ? 'selected' : '' ?>>Completed</option>
        <option value="in_progress" <?= (($filterStatus ?? '') === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
        <option value="pending" <?= (($filterStatus ?? '') === 'pending') ? 'selected' : '' ?>>Awaiting Candidate</option>
        <option value="expired" <?= (($filterStatus ?? '') === 'expired') ? 'selected' : '' ?>>Expired</option>
      </select>
      <button type="submit" class="emp-btn emp-btn-secondary emp-btn-sm">Filter</button>
      <?php if (!empty($filterJob) || !empty($filterStatus) || !empty($searchQuery)): ?>
        <a href="<?= base_url('employer/aptitude-tests') ?>" class="ic-btn ic-btn--danger" title="Clear Filters" aria-label="Clear Filters">
          <svg aria-hidden="true" width="14" height="14"><use href="#i-x"/></svg>
        </a>
      <?php endif; ?>
    </form>
  </div>

  <div class="tbl-wrap">
    <?php if (!empty($invitations)): ?>
      <table class="tbl">
        <thead>
          <tr>
            <th>Candidate</th>
            <th>Position &amp; Assessment</th>
            <th style="text-align:center;">Test Status</th>
            <th style="text-align:center;">Score &amp; Verdict</th>
            <th style="text-align:center;">Recommendation</th>
            <th>Timeline</th>
            <th style="text-align:right; padding-right:16px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($invitations as $inv):
            $invId        = (int)$inv['id'];
            $appId        = !empty($inv['app_id']) ? (int)$inv['app_id'] : 0;
            $seekerId     = !empty($inv['candidate_id']) ? (int)$inv['candidate_id'] : 0;
            $candName     = trim(($inv['first_name'] ?? '') . ' ' . ($inv['last_name'] ?? ''));
            if (empty($candName)) $candName = $inv['seeker_full_name'] ?? ('Candidate #' . $invId);
            $candEmail    = $inv['applicant_email'] ?? $inv['email'] ?? '';
            $candPhone    = $inv['applicant_phone'] ?? $inv['seeker_phone'] ?? '';
            $candAvatar   = $inv['seeker_avatar'] ?? null;
            $initials     = strtoupper(substr($candName, 0, 1));

            $attemptStatus = strtolower($inv['attempt_status'] ?? '');
            $invStatus     = strtolower($inv['status'] ?? 'pending');
            $isCompleted   = ($attemptStatus === 'completed' || $attemptStatus === 'submitted');
            $isInProgress  = (!$isCompleted && ($attemptStatus === 'in_progress'));
            $isExpired     = (!$isCompleted && !empty($inv['due_date']) && strtotime($inv['due_date']) < time());

            $score          = $inv['score_pct'] !== null ? (float)$inv['score_pct'] : null;
            $passThreshold  = !empty($inv['pass_threshold']) ? (int)$inv['pass_threshold'] : 50;
            $isPassed       = isset($inv['passed']) ? (bool)$inv['passed'] : ($score !== null && $score >= $passThreshold);
            $totalQ         = (int)($inv['total_questions'] ?? 0);
            $correctQ       = (int)($inv['correct_answers'] ?? 0);

            // Recommendation
            $recClass = 'pill--pending';
            $recText  = 'Pending';
            if ($isCompleted) {
              if ($score >= 85)                { $recClass = 'pill--hired';       $recText = 'Strong Hire ★'; }
              elseif ($score >= 70)            { $recClass = 'pill--shortlisted'; $recText = 'Recommended'; }
              elseif ($score >= $passThreshold){ $recClass = 'pill--reviewed';    $recText = 'Consider'; }
              else                             { $recClass = 'pill--rejected';    $recText = 'Below Benchmark'; }
            } elseif ($isInProgress) {
              $recClass = 'pill--reviewed'; $recText = 'In Progress…';
            } elseif ($isExpired) {
              $recClass = 'pill--closed'; $recText = 'Invite Expired';
            }

            $invToken = $inv['invitation_token'] ?? $inv['code'] ?? '';
            $invUrl   = !empty($invToken) ? base_url('aptitude/invite/' . $invToken) : '';
            $appStage = $inv['application_status'] ?? '';
          ?>
            <tr>
              <!-- Candidate -->
              <td class="no-lbl">
                <div class="appl-cell">
                  <span class="ava ava--round" style="background:#<?= substr(md5($candName), 0, 6) ?>; color:#fff;" aria-hidden="true">
                    <?php if (!empty($candAvatar)): ?>
                      <img src="<?= base_url($candAvatar) ?>" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    <?php else: ?>
                      <?= $initials ?>
                    <?php endif; ?>
                  </span>
                  <div style="min-width:0;">
                    <div class="appl-name">
                      <?php if ($appId > 0): ?>
                        <a href="<?= base_url('employer/applications/view/' . $appId) ?>" style="color:inherit; text-decoration:none;"><?= esc($candName) ?></a>
                      <?php else: ?>
                        <?= esc($candName) ?>
                      <?php endif; ?>
                    </div>
                    <?php if ($candEmail): ?><div class="appl-mail"><?= esc($candEmail) ?></div><?php endif; ?>
                  </div>
                </div>
              </td>

              <!-- Position & Assessment -->
              <td data-lbl="Position">
                <div style="font-weight:600; font-size:.85rem; color:var(--text);"><?= esc($inv['job_title'] ?? 'General Assessment') ?></div>
                <div style="font-size:.75rem; color:var(--brand); margin-top:2px;">
                  <?= esc($inv['test_title'] ?? 'Custom Assessment') ?>
                  <?php if (!empty($inv['duration_mins'])): ?>
                    <span style="color:var(--muted);"> · <?= (int)$inv['duration_mins'] ?> mins</span>
                  <?php endif; ?>
                </div>
              </td>

              <!-- Test Status -->
              <td data-lbl="Status" style="text-align:center;">
                <?php if ($isCompleted): ?>
                  <span class="pill pill--hired" style="font-size:.72rem;">Completed</span>
                <?php elseif ($isInProgress): ?>
                  <span class="pill pill--reviewed" style="font-size:.72rem;">In Progress</span>
                <?php elseif ($isExpired): ?>
                  <span class="pill pill--closed" style="font-size:.72rem;">Expired</span>
                <?php else: ?>
                  <span class="pill pill--pending" style="font-size:.72rem;">Awaiting</span>
                <?php endif; ?>
              </td>

              <!-- Score -->
              <td data-lbl="Score" style="text-align:center;">
                <?php if ($score !== null): ?>
                  <div style="font-weight:700; font-size:1.1rem; color:<?= $isPassed ? 'var(--success)' : 'var(--danger)' ?>;"><?= (int)round($score) ?>%</div>
                  <div style="font-size:.72rem; color:var(--muted);">
                    <?php if ($totalQ > 0): ?><?= $correctQ ?>/<?= $totalQ ?> correct<?php else: ?><?= $isPassed ? 'Passed' : 'Failed' ?><?php endif; ?>
                  </div>
                <?php elseif ($isInProgress): ?>
                  <span style="font-size:.8rem; color:var(--muted);">Testing…</span>
                <?php else: ?>
                  <span style="color:var(--muted);">—</span>
                <?php endif; ?>
              </td>

              <!-- Recommendation -->
              <td data-lbl="Recommendation" style="text-align:center;">
                <span class="pill <?= $recClass ?>" style="font-size:.72rem;"><?= esc($recText) ?></span>
                <?php if (!empty($appStage)): ?>
                  <div style="margin-top:4px; font-size:.68rem; color:var(--muted); text-transform:capitalize;"><?= esc(str_replace('_', ' ', $appStage)) ?></div>
                <?php endif; ?>
              </td>

              <!-- Timeline -->
              <td data-lbl="Timeline">
                <div style="font-size:.75rem; color:var(--muted); line-height:1.6;">
                  <div><strong>Sent:</strong> <?= date('d M Y', strtotime($inv['created_at'])) ?></div>
                  <?php if (!empty($inv['started_at'])): ?>
                    <div><strong>Started:</strong> <?= date('d M Y', strtotime($inv['started_at'])) ?></div>
                  <?php endif; ?>
                  <?php if (!empty($inv['submitted_at'])): ?>
                    <div style="color:var(--success);"><strong>Done:</strong> <?= date('d M Y', strtotime($inv['submitted_at'])) ?></div>
                  <?php elseif (!empty($inv['due_date'])): ?>
                    <div style="color:<?= $isExpired ? 'var(--danger)' : 'var(--muted)' ?>;"><strong>Due:</strong> <?= date('d M Y', strtotime($inv['due_date'])) ?></div>
                  <?php endif; ?>
                </div>
              </td>

              <!-- Actions -->
              <td data-lbl="Actions" style="text-align:right; padding-right:16px;">
                <div class="row-actions" style="justify-content:flex-end;">
                  <?php if ($isCompleted): ?>
                    <button type="button" class="emp-btn emp-btn-primary emp-btn-sm"
                            onclick="viewAttemptResult(<?= $invId ?>)"
                            title="View full score &amp; breakdown">
                      <svg aria-hidden="true" width="13" height="13"><use href="#i-chart-bar"/></svg> Result
                    </button>
                  <?php elseif (!empty($invUrl)): ?>
                    <button type="button" class="ic-btn" onclick="copyTestLink('<?= esc($invUrl, 'js') ?>', this)" title="Copy candidate test link">
                      <svg aria-hidden="true" width="15" height="15"><use href="#i-link"/></svg>
                    </button>
                    <button type="button" class="ic-btn" onclick="resendInvitation(<?= $invId ?>, this)" title="Resend invitation">
                      <svg aria-hidden="true" width="15" height="15"><use href="#i-send"/></svg>
                    </button>
                  <?php endif; ?>

                  <!-- More actions dropdown -->
                  <div style="position:relative; display:inline-block;" class="js-dropdown-wrap">
                    <button type="button" class="ic-btn js-dropdown-toggle" title="More actions" aria-expanded="false">
                      <svg aria-hidden="true" width="15" height="15"><use href="#i-dots-v"/></svg>
                    </button>
                    <ul class="dropdown-menu" style="min-width:200px; right:0; left:auto; display:none;">
                      <?php if ($isCompleted): ?>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewAttemptResult(<?= $invId ?>)">
                          <svg aria-hidden="true" width="14" height="14"><use href="#i-chart-bar"/></svg> View Score &amp; Analysis
                        </a></li>
                      <?php endif; ?>
                      <?php if ($appId > 0): ?>
                        <li><a class="dropdown-item" href="<?= base_url('employer/applications/view/' . $appId) ?>">
                          <svg aria-hidden="true" width="14" height="14"><use href="#i-eye"/></svg> Full Application
                        </a></li>
                        <li><hr style="margin:4px 0; border-color:var(--border);"></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="openAdvanceStageModal(<?= $appId ?>, '<?= esc($candName, 'js') ?>', 'shortlisted')" style="color:var(--success);">
                          <svg aria-hidden="true" width="14" height="14"><use href="#i-check"/></svg> Shortlist
                        </a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="openAdvanceStageModal(<?= $appId ?>, '<?= esc($candName, 'js') ?>', 'interview')">
                          <svg aria-hidden="true" width="14" height="14"><use href="#i-calendar"/></svg> Advance to Interview
                        </a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="openAdvanceStageModal(<?= $appId ?>, '<?= esc($candName, 'js') ?>', 'rejected')" style="color:var(--danger);">
                          <svg aria-hidden="true" width="14" height="14"><use href="#i-x"/></svg> Reject
                        </a></li>
                      <?php endif; ?>
                      <?php if (!empty($candEmail)): ?>
                        <li><hr style="margin:4px 0; border-color:var(--border);"></li>
                        <li><a class="dropdown-item" href="mailto:<?= esc($candEmail) ?>?subject=<?= urlencode('Regarding your assessment for ' . ($inv['job_title'] ?? 'JobberRecruit')) ?>">
                          <svg aria-hidden="true" width="14" height="14"><use href="#i-mail"/></svg> Send Email
                        </a></li>
                      <?php endif; ?>
                      <?php if (!empty($invUrl)): ?>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="copyTestLink('<?= esc($invUrl, 'js') ?>', this)">
                          <svg aria-hidden="true" width="14" height="14"><use href="#i-copy"/></svg> Copy Test Link
                        </a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="empty">
        <div class="empty-ic"><svg aria-hidden="true"><use href="#i-clipboard-list"/></svg></div>
        <h3>No candidate assessment invitations sent yet</h3>
        <p>Screen candidates efficiently by inviting them to aptitude and skill tests directly from the
          <a href="<?= base_url('employer/applications') ?>">Applications Console</a>
          or sharing templates from the <a href="javascript:void(0)" onclick="toggleLibrary()">Assessment Library</a>.</p>
        <a href="<?= base_url('employer/applications') ?>" class="emp-btn emp-btn-primary emp-btn-sm">
          <svg aria-hidden="true" width="16" height="16"><use href="#i-search-user"/></svg> Browse Applicants &amp; Invite
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ══ CUSTOM TEST MODAL STYLES (Self-contained, bulletproof layout) ══ -->
<style>
/* ── Modal Overlay & Centering (Above sidebar, perfectly centered) ── */
#customTestModal {
  position: fixed !important;
  inset: 0 !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  width: 100vw !important;
  height: 100vh !important;
  height: 100dvh !important;
  z-index: 99999 !important;
  background: rgba(10, 25, 45, 0.72) !important;
  margin: 0 !important;
  padding: 24px 16px !important;
  overflow-y: auto !important;
  overflow-x: hidden !important;
  -webkit-overflow-scrolling: touch;
  transform: none !important;
  animation: none !important;
  box-sizing: border-box !important;
}

#customTestModal:not(.show) {
  display: none !important;
}

#customTestModal.show {
  display: flex !important;
  justify-content: center !important;
  align-items: flex-start !important;
}

/* ── Modal Dialog (Centering & Sizing) ── */
#customTestModal .modal-dialog {
  position: relative !important;
  width: 100% !important;
  max-width: 880px !important;
  margin: 1rem auto !important;
  display: flex !important;
  flex-direction: column !important;
  max-height: calc(100vh - 3rem) !important;
  max-height: calc(100dvh - 3rem) !important;
  pointer-events: auto !important;
  transform: none !important;
  align-self: center !important;
  flex-shrink: 0 !important;
}

/* ── Modal Content Container ── */
#customTestModal .modal-content {
  width: 100% !important;
  max-width: 100% !important;
  min-width: 0 !important;
  background: #ffffff !important;
  border-radius: 14px !important;
  box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4), 0 4px 18px rgba(0, 0, 0, 0.12) !important;
  border: 1px solid var(--border, #e2e8f0) !important;
  display: flex !important;
  flex-direction: column !important;
  max-height: calc(100vh - 3.5rem) !important;
  max-height: calc(100dvh - 3.5rem) !important;
  overflow: hidden !important;
  position: relative !important;
}

/* ── Modal Body: Scrollable Content ── */
#customTestModal .modal-body {
  flex: 1 1 auto !important;
  overflow-y: auto !important;
  overflow-x: hidden !important;
  -webkit-overflow-scrolling: touch;
  overscroll-behavior: contain;
  padding: 22px 24px !important;
}

/* ── Grid Layout for Modal (Independent of external Bootstrap grid) ── */
#customTestModal .row {
  display: flex !important;
  flex-wrap: wrap !important;
  margin-right: -8px !important;
  margin-left: -8px !important;
}
#customTestModal .row > [class*="col-"] {
  padding-right: 8px !important;
  padding-left: 8px !important;
  box-sizing: border-box !important;
}
#customTestModal .col-12 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
#customTestModal .col-6  { width: 50% !important; flex: 0 0 50% !important; max-width: 50% !important; }

@media (min-width: 768px) {
  #customTestModal .col-md-8 { width: 66.666667% !important; flex: 0 0 66.666667% !important; max-width: 66.666667% !important; }
  #customTestModal .col-md-4 { width: 33.333333% !important; flex: 0 0 33.333333% !important; max-width: 33.333333% !important; }
  #customTestModal .col-md-6 { width: 50% !important; flex: 0 0 50% !important; max-width: 50% !important; }
}

@media (max-width: 767.98px) {
  #customTestModal .modal-dialog {
    width: 96% !important;
    margin: 0.5rem auto !important;
    max-height: calc(100vh - 1.5rem) !important;
    max-height: calc(100dvh - 1.5rem) !important;
  }
  #customTestModal .col-6 {
    width: 100% !important;
    flex: 0 0 100% !important;
    max-width: 100% !important;
  }
  #customTestModal .modal-body {
    padding: 14px 16px !important;
  }
}

/* ── Form Inputs & Selects ── */
#customTestModal .form-control,
#customTestModal .form-select {
  display: block !important;
  width: 100% !important;
  padding: 9px 12px !important;
  font-size: 0.85rem !important;
  font-family: inherit !important;
  color: #141926 !important;
  background-color: #fff !important;
  border: 1px solid #cbd5e1 !important;
  border-radius: 8px !important;
  box-sizing: border-box !important;
  line-height: 1.5 !important;
  transition: border-color .15s ease, box-shadow .15s ease !important;
}
#customTestModal .form-control:focus,
#customTestModal .form-select:focus {
  border-color: #0861A9 !important;
  outline: 0 !important;
  box-shadow: 0 0 0 3px rgba(8, 97, 169, 0.15) !important;
}
#customTestModal .form-select {
  appearance: auto !important;
  cursor: pointer !important;
}
#customTestModal .form-label {
  display: block !important;
  margin-bottom: 5px !important;
  font-size: 0.76rem !important;
  font-weight: 700 !important;
  color: #0A2F57 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.03em !important;
}

/* ── Input Groups (for answer options radio + text) ── */
#customTestModal .input-group {
  display: flex !important;
  align-items: stretch !important;
  width: 100% !important;
}
#customTestModal .input-group .input-group-text {
  display: inline-flex !important;
  align-items: center !important;
  padding: 6px 10px !important;
  font-size: 0.82rem !important;
  background: #f1f5f9 !important;
  border: 1px solid #cbd5e1 !important;
  border-right: none !important;
  border-radius: 8px 0 0 8px !important;
  white-space: nowrap !important;
}
#customTestModal .input-group .form-control {
  border-radius: 0 8px 8px 0 !important;
}

/* ── Close Button ── */
#customTestModal .btn-close {
  background: none !important;
  border: none !important;
  font-size: 1.6rem !important;
  line-height: 1 !important;
  color: #64748b !important;
  cursor: pointer !important;
  padding: 4px 8px !important;
  border-radius: 6px !important;
  transition: color 0.15s ease, background-color 0.15s ease !important;
}
#customTestModal .btn-close:hover {
  color: #dc2626 !important;
  background: rgba(220, 38, 38, 0.08) !important;
}
#customTestModal .btn-close::before {
  content: "×" !important;
  display: block !important;
}

/* ── AI Generation Banner ── */
.ct-ai-banner {
  background: linear-gradient(135deg, rgba(8,97,169,0.06), rgba(237,144,32,0.08));
  border: 1px solid rgba(8,97,169,0.2);
  border-radius: 8px;
  padding: 14px 16px;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}
.ct-ai-banner-text { flex: 1 1 200px; min-width: 0; }
.ct-ai-banner-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
  flex-wrap: wrap;
}

/* ── Question Cards ── */
#questionsContainer .card {
  border-left: 4px solid var(--brand, #0861A9) !important;
  word-break: break-word;
}

/* ── Desktop Scrollbar Styling ── */
#customTestModal .modal-body::-webkit-scrollbar { width: 6px; }
#customTestModal .modal-body::-webkit-scrollbar-track { background: transparent; }
#customTestModal .modal-body::-webkit-scrollbar-thumb { background: var(--border, #dee2e6); border-radius: 3px; }
</style>

<!-- ══ CREATE / EDIT CUSTOM TEST MODAL ══ -->
<div class="modal fade" id="customTestModal" tabindex="-1" aria-labelledby="customTestModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-sm-down modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom py-3 px-4" style="background:var(--bg-alt); flex-shrink:0;">
        <div style="min-width:0; flex:1;">
          <h5 class="modal-title fw-bold text-dark fs-16" id="customTestModalTitle">Create Custom Assessment Test</h5>
          <span class="text-muted fs-12" id="customTestModalSub">Design your own screening test or use AI to generate questions</span>
        </div>
        <button type="button" class="btn-close ms-3 flex-shrink-0" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="customTestForm" onsubmit="saveCustomTest(event)" style="display:flex; flex-direction:column; min-height:0; flex:1;">
        <div class="modal-body p-3 p-md-4" style="overflow-y:auto; flex:1; min-height:0;">
          <input type="hidden" id="customTestId" value="">

          <!-- Basic Test Details -->
          <div class="row g-3 mb-4">
            <div class="col-12 col-md-8">
              <label class="form-label fw-bold fs-12 text-dark">Test Title <span class="text-danger">*</span></label>
              <input type="text" id="ctTitle" class="form-control fs-13" placeholder="e.g. Senior Frontend React Developer Screening" required>
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label fw-bold fs-12 text-dark">Job Category</label>
              <select id="ctCategoryId" class="form-select fs-13">
                <?php if (!empty($categories)): ?>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="1">General Assessment</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-bold fs-12 text-dark">Description &amp; Candidate Instructions</label>
              <textarea id="ctDescription" class="form-control fs-13" rows="2" placeholder="Briefly explain what skills this test evaluates..."></textarea>
            </div>
            <div class="col-6 col-md-4">
              <label class="form-label fw-bold fs-12 text-dark">Duration (Mins)</label>
              <input type="number" id="ctDurationMins" class="form-control fs-13" value="20" min="5" max="180">
            </div>
            <div class="col-6 col-md-4">
              <label class="form-label fw-bold fs-12 text-dark">Pass Benchmark (%)</label>
              <input type="number" id="ctPassThreshold" class="form-control fs-13" value="60" min="10" max="100">
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label fw-bold fs-12 text-dark">Difficulty Level</label>
              <select id="ctDifficulty" class="form-select fs-13">
                <option value="beginner">Beginner</option>
                <option value="intermediate" selected>Intermediate</option>
                <option value="advanced">Advanced</option>
              </select>
            </div>
          </div>

          <hr style="border-color: var(--border); margin: 20px 0;">

          <!-- AI Generation Banner -->
          <div class="ct-ai-banner mb-4">
            <div class="ct-ai-banner-text">
              <div class="fw-bold text-dark fs-14" style="display:flex; align-items:center; gap:6px;">
                <span style="font-size:1.1rem;">✨</span> Auto-Generate Questions with JobberRecruit AI
              </div>
              <div class="text-muted fs-12 mt-1">Our AI will create custom multiple-choice questions based on your Test Title and Description instantly.</div>
            </div>
            <div class="ct-ai-banner-actions">
              <select id="aiNumQuestions" class="form-select form-select-sm fs-12" style="width: auto; min-width:110px;">
                <option value="5">5 Questions</option>
                <option value="10" selected>10 Questions</option>
                <option value="15">15 Questions</option>
              </select>
              <button type="button" class="emp-btn emp-btn-accent emp-btn-sm" id="btnAiGenerate" onclick="generateAiQuestions()">
                ✨ Generate with AI
              </button>
            </div>
          </div>

          <!-- Questions Editor Header -->
          <div class="d-flex align-items-center justify-content-between mb-3 gap-2">
            <h6 class="fw-bold text-dark m-0 fs-14">
              <svg aria-hidden="true" width="16" height="16" style="vertical-align:middle; margin-right:4px;"><use href="#i-help-circle"/></svg>
              Test Questions (<span id="questionCountLabel">0</span>)
            </h6>
            <button type="button" class="emp-btn emp-btn-outline emp-btn-sm flex-shrink-0" onclick="addQuestionCard()">
              <svg aria-hidden="true" width="13" height="13"><use href="#i-plus"/></svg> Add Question
            </button>
          </div>

          <!-- Questions List Container -->
          <div id="questionsContainer" style="display:flex; flex-direction:column; gap:16px;">
            <div class="text-center py-4 text-muted fs-13" id="noQuestionsNotice" style="border: 2px dashed var(--border); border-radius: 8px;">
              No questions added yet. Click <strong>"✨ Generate with AI"</strong> above or <strong>"Add Question"</strong> to get started.
            </div>
          </div>

        </div>

        <div class="modal-footer bg-light border-top py-2 px-3 px-md-4 d-flex justify-content-between flex-shrink-0">
          <button type="button" class="emp-btn emp-btn-secondary emp-btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="emp-btn emp-btn-primary emp-btn-sm" id="btnSaveCustomTest">
            Save Assessment Test
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ══ SHARE TEST MODAL ══ -->
<div class="modal fade" id="shareTestModal" tabindex="-1" aria-labelledby="shareTestModalTitle" aria-hidden="true" style="position:fixed; inset:0; z-index:99999; background:rgba(10,25,45,0.72); display:none; align-items:center; justify-content:center; padding:16px;">
  <div class="modal-dialog modal-dialog-centered" style="width:100%; max-width:540px; margin:auto; position:relative;">
    <div class="modal-content" style="background:#fff; border-radius:14px; box-shadow:0 25px 70px rgba(0,0,0,0.35); overflow:hidden; border:1px solid var(--border);">
      <div class="modal-header" style="padding:16px 20px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:var(--bg-alt);">
        <div>
          <h5 class="modal-title fw-bold" id="shareTestModalTitle" style="margin:0; font-size:1.02rem; color:var(--brand-deep); display:flex; align-items:center; gap:8px;">
            <svg aria-hidden="true" width="18" height="18" style="color:var(--brand);"><use href="#i-share"/></svg>
            <span id="shareModalTestName">Share Assessment Link</span>
          </h5>
          <span style="font-size:0.78rem; color:var(--muted);">Invite candidates or post test links across recruitment channels</span>
        </div>
        <button type="button" class="btn-close" onclick="closeShareTestModal()" aria-label="Close" style="background:none; border:none; font-size:1.5rem; color:var(--muted); cursor:pointer; line-height:1;">&times;</button>
      </div>

      <div class="modal-body" style="padding:20px; display:flex; flex-direction:column; gap:16px;">
        <!-- Official Test Link -->
        <div>
          <label style="display:block; font-size:0.76rem; font-weight:700; color:var(--brand-deep); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:6px;">
            Official Candidate Test Link (Score Tracked to Your Roster)
          </label>
          <div style="display:flex; gap:8px;">
            <input type="text" id="shareOfficialUrlInput" readonly class="form-control" style="flex:1; font-size:0.84rem; background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:9px 12px; color:#1e293b;" onclick="this.select()">
            <button type="button" class="emp-btn emp-btn-primary emp-btn-sm" style="flex-shrink:0; padding:0 16px;" onclick="copyInputValue('shareOfficialUrlInput', this)">
              <svg aria-hidden="true" width="14" height="14"><use href="#i-copy"/></svg> Copy
            </button>
          </div>
          <span style="font-size:0.74rem; color:var(--muted); margin-top:4px; display:block;">
            Applicants who click this link are scored and automatically reflected in your candidate roster.
          </span>
        </div>

        <!-- Practice Mode Link -->
        <div>
          <label style="display:block; font-size:0.76rem; font-weight:700; color:var(--brand-deep); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:6px;">
            Free Practice Mode Link (Candidate Warmup)
          </label>
          <div style="display:flex; gap:8px;">
            <input type="text" id="sharePracticeUrlInput" readonly class="form-control" style="flex:1; font-size:0.84rem; background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:9px 12px; color:#1e293b;" onclick="this.select()">
            <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" style="flex-shrink:0; padding:0 16px;" onclick="copyInputValue('sharePracticeUrlInput', this)">
              <svg aria-hidden="true" width="14" height="14"><use href="#i-copy"/></svg> Copy
            </button>
          </div>
          <span style="font-size:0.74rem; color:var(--muted); margin-top:4px; display:block;">
            Lets job seekers practice without recording an official attempt on your roster.
          </span>
        </div>

        <!-- Direct Social & Email Share -->
        <div style="border-top:1px solid var(--border); padding-top:16px;">
          <label style="display:block; font-size:0.76rem; font-weight:700; color:var(--brand-deep); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:10px;">
            Quick Share Channels
          </label>
          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a id="shareWhatsappLink" href="#" target="_blank" rel="noopener noreferrer" class="emp-btn emp-btn-sm" style="background:#25D366; color:#fff; border:none; display:inline-flex; align-items:center; gap:6px;">
              <svg aria-hidden="true" width="15" height="15"><use href="#i-whatsapp"/></svg> WhatsApp
            </a>
            <a id="shareEmailLink" href="#" class="emp-btn emp-btn-sm emp-btn-secondary" style="display:inline-flex; align-items:center; gap:6px;">
              <svg aria-hidden="true" width="15" height="15"><use href="#i-mail"/></svg> Email
            </a>
            <button type="button" id="shareNativeBtn" class="emp-btn emp-btn-sm emp-btn-outline" style="display:none; align-items:center; gap:6px;" onclick="nativeShareTest()">
              <svg aria-hidden="true" width="15" height="15"><use href="#i-share"/></svg> Share App...
            </button>
          </div>
        </div>
      </div>

      <div class="modal-footer" style="padding:12px 20px; border-top:1px solid var(--border); background:var(--bg); display:flex; justify-content:flex-end;">
        <button type="button" class="emp-btn emp-btn-secondary emp-btn-sm" onclick="closeShareTestModal()">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ══ CANDIDATE ATTEMPT RESULT MODAL ══ -->
<div class="modal fade" id="attemptResultModal" tabindex="-1" aria-labelledby="attemptResultModalTitle" aria-hidden="true" style="position:fixed; inset:0; z-index:99999; background:rgba(10,25,45,0.75); display:none; align-items:center; justify-content:center; padding:16px; overflow-y:auto;">
  <div class="modal-dialog modal-lg modal-dialog-centered" style="width:100%; max-width:820px; margin:auto; position:relative;">
    <div class="modal-content" style="background:#fff; border-radius:14px; box-shadow:0 25px 70px rgba(0,0,0,0.4); overflow:hidden; border:1px solid var(--border); display:flex; flex-direction:column; max-height:calc(100vh - 3.5rem);">
      <div class="modal-header" style="padding:16px 22px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:var(--bg-alt); flex-shrink:0;">
        <div>
          <h5 class="modal-title fw-bold" id="attemptResultModalTitle" style="margin:0; font-size:1.05rem; color:var(--brand-deep); display:flex; align-items:center; gap:8px;">
            <svg aria-hidden="true" width="18" height="18" style="color:var(--brand);"><use href="#i-chart-bar"/></svg>
            Candidate Assessment Score &amp; Analysis
          </h5>
          <span style="font-size:0.78rem; color:var(--muted);" id="attemptResultSubTitle">Detailed breakdown and response audit</span>
        </div>
        <button type="button" class="btn-close" onclick="closeAttemptResultModal()" aria-label="Close" style="background:none; border:none; font-size:1.5rem; color:var(--muted); cursor:pointer; line-height:1;">&times;</button>
      </div>

      <div class="modal-body" id="attemptResultBody" style="padding:22px; overflow-y:auto; flex:1;">
        <div style="text-align:center; padding:30px; color:var(--muted);">Loading candidate score analysis...</div>
      </div>

      <div class="modal-footer" id="attemptResultFooter" style="padding:12px 22px; border-top:1px solid var(--border); background:var(--bg); display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
        <button type="button" class="emp-btn emp-btn-secondary emp-btn-sm" onclick="closeAttemptResultModal()">Close</button>
        <div id="attemptResultActions" style="display:flex; gap:8px;"></div>
      </div>
    </div>
  </div>
</div>

<!-- ══ ADVANCE STAGE MODAL ══ -->
<div class="modal fade" id="advanceStageModal" tabindex="-1" aria-labelledby="advanceStageModalTitle" aria-hidden="true" style="position:fixed; inset:0; z-index:99999; background:rgba(10,25,45,0.72); display:none; align-items:center; justify-content:center; padding:16px;">
  <div class="modal-dialog modal-dialog-centered" style="width:100%; max-width:480px; margin:auto; position:relative;">
    <div class="modal-content" style="background:#fff; border-radius:14px; box-shadow:0 25px 70px rgba(0,0,0,0.35); overflow:hidden; border:1px solid var(--border);">
      <div class="modal-header" style="padding:16px 20px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:var(--bg-alt);">
        <div>
          <h5 class="modal-title fw-bold" id="advanceStageModalTitle" style="margin:0; font-size:1.02rem; color:var(--brand-deep); display:flex; align-items:center; gap:8px;">
            <svg aria-hidden="true" width="18" height="18" style="color:var(--brand);"><use href="#i-check"/></svg>
            Update Recruitment Stage
          </h5>
          <span style="font-size:0.78rem; color:var(--muted);" id="advanceStageCandName">Advance candidate in pipeline</span>
        </div>
        <button type="button" class="btn-close" onclick="closeAdvanceStageModal()" aria-label="Close" style="background:none; border:none; font-size:1.5rem; color:var(--muted); cursor:pointer; line-height:1;">&times;</button>
      </div>

      <form id="advanceStageForm" onsubmit="confirmAdvanceStage(event)" style="margin:0;">
        <input type="hidden" id="advAppId" value="">
        <div class="modal-body" style="padding:20px; display:flex; flex-direction:column; gap:14px;">
          <div>
            <label style="display:block; font-size:0.76rem; font-weight:700; color:var(--brand-deep); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:6px;">Select New Stage <span style="color:red">*</span></label>
            <select id="advStageSelect" class="form-select" style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid #cbd5e1; font-size:0.85rem;" required>
              <option value="shortlisted">Shortlisted</option>
              <option value="interview">Interview Stage</option>
              <option value="hired">Hired</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>

          <div>
            <label style="display:block; font-size:0.76rem; font-weight:700; color:var(--brand-deep); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:6px;">Recruiter Notes / Feedback (Optional)</label>
            <textarea id="advStageNotes" class="form-control" rows="3" style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid #cbd5e1; font-size:0.85rem;" placeholder="e.g. Scored 85% in React screening. Moving to technical interview..."></textarea>
          </div>
        </div>

        <div class="modal-footer" style="padding:12px 20px; border-top:1px solid var(--border); background:var(--bg); display:flex; justify-content:space-between;">
          <button type="button" class="emp-btn emp-btn-secondary emp-btn-sm" onclick="closeAdvanceStageModal()">Cancel</button>
          <button type="submit" class="emp-btn emp-btn-primary emp-btn-sm" id="btnConfirmAdvanceStage">Update Stage</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Toggle Assessment Library
function toggleLibrary() {
  var el = document.getElementById('testLibrarySection');
  var btn = document.getElementById('toggleLibraryBtn');
  if (el.style.display === 'none') {
    el.style.display = 'block';
    btn.classList.add('emp-btn-secondary');
    btn.classList.remove('emp-btn-primary');
  } else {
    el.style.display = 'none';
    btn.classList.add('emp-btn-primary');
    btn.classList.remove('emp-btn-secondary');
  }
}

// Dropdown toggle handler
document.addEventListener('click', function(e) {
  var toggle = e.target.closest('.js-dropdown-toggle');
  if (toggle) {
    var wrap = toggle.closest('.js-dropdown-wrap');
    var menu = wrap.querySelector('.dropdown-menu');
    var isOpen = menu.style.display === 'block';
    document.querySelectorAll('.dropdown-menu').forEach(function(m){ m.style.display='none'; });
    if (!isOpen) menu.style.display = 'block';
    e.stopPropagation();
    return;
  }
  if (!e.target.closest('.dropdown-menu')) {
    document.querySelectorAll('.dropdown-menu').forEach(function(m){ m.style.display='none'; });
  }
});

// ══════════════════════════════════════════════════
// CUSTOM TEST CREATION & EDITING JS
// ══════════════════════════════════════════════════

var customTestQuestions = [];

function showCustomTestModal() {
  var modalEl = document.getElementById('customTestModal');
  if (!modalEl) return;
  if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
    var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    modal.show();
  } else if (typeof $ !== 'undefined' && $.fn.modal) {
    $(modalEl).modal('show');
  } else {
    modalEl.style.display = 'flex';
    modalEl.classList.add('show');
  }
}

function closeCustomTestModal() {
  var modalEl = document.getElementById('customTestModal');
  if (!modalEl) return;
  if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
    var modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) { modal.hide(); return; }
  }
  if (typeof $ !== 'undefined' && $.fn.modal) {
    $(modalEl).modal('hide');
    return;
  }
  modalEl.classList.remove('show');
  modalEl.style.display = 'none';
  document.querySelectorAll('.modal-backdrop').forEach(function(b) { b.remove(); });
  document.body.classList.remove('modal-open');
}

document.addEventListener('DOMContentLoaded', function() {
  var modalEl = document.getElementById('customTestModal');
  if (!modalEl) return;
  modalEl.addEventListener('click', function(e) {
    if (e.target === modalEl) {
      closeCustomTestModal();
    }
  });
  modalEl.querySelectorAll('.btn-close, [data-bs-dismiss="modal"]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      closeCustomTestModal();
    });
  });
});

function openCreateCustomTestModal() {
  document.getElementById('customTestId').value = '';
  document.getElementById('customTestModalTitle').innerText = 'Create Custom Assessment Test';
  document.getElementById('customTestModalSub').innerText = 'Design your own screening test or use AI to generate questions';
  document.getElementById('ctTitle').value = '';
  document.getElementById('ctDescription').value = '';
  document.getElementById('ctDurationMins').value = 20;
  document.getElementById('ctPassThreshold').value = 60;
  document.getElementById('ctDifficulty').value = 'intermediate';
  customTestQuestions = [];
  renderQuestionCards();

  showCustomTestModal();
}

function openEditCustomTestModal(testId) {
  fetch('<?= base_url("employer/aptitude-tests/questions/") ?>' + testId)
    .then(r => r.json())
    .then(data => {
      if (!data.success) {
        alert(data.message || 'Unable to load test details.');
        return;
      }
      var test = data.test;
      document.getElementById('customTestId').value = test.id;
      document.getElementById('customTestModalTitle').innerText = 'Edit Assessment Test: ' + test.title;
      document.getElementById('customTestModalSub').innerText = 'Modify test parameters or update question items';
      document.getElementById('ctTitle').value = test.title || '';
      document.getElementById('ctCategoryId').value = test.category_id || 1;
      document.getElementById('ctDescription').value = test.description || '';
      document.getElementById('ctDurationMins').value = test.duration_mins || 20;
      document.getElementById('ctPassThreshold').value = test.pass_threshold || 60;
      document.getElementById('ctDifficulty').value = test.difficulty || 'intermediate';

      customTestQuestions = (data.questions || []).map(q => {
        var opts = (q.options || []).map(o => ({
          text: o.body || o.text || '',
          is_correct: o.is_correct ? 1 : 0
        }));

        while (opts.length < 4) {
          opts.push({ text: '', is_correct: 0 });
        }

        return {
          question: q.body || q.question || '',
          explanation: q.explanation || '',
          options: opts
        };
      });

      renderQuestionCards();
      showCustomTestModal();
    })
    .catch(err => alert('Failed to fetch test questions.'));
}

function generateAiQuestions() {
  var title = document.getElementById('ctTitle').value.trim();
  var desc  = document.getElementById('ctDescription').value.trim();
  var numQ  = document.getElementById('aiNumQuestions').value;

  if (!title) {
    alert('Please enter a Test Title before generating questions with AI.');
    document.getElementById('ctTitle').focus();
    return;
  }

  var btn = document.getElementById('btnAiGenerate');
  var origText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '✨ Generating questions with AI...';

  var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
  var csrfName = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || '<?= csrf_token() ?>';

  var formData = new FormData();
  formData.append('title', title);
  formData.append('description', desc);
  formData.append('num_questions', numQ);
  formData.append(csrfName, csrfToken);

  fetch('<?= base_url("employer/aptitude-tests/ai-generate") ?>', {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    btn.disabled = false;
    btn.innerHTML = origText;
    if (data.success && data.questions && data.questions.length > 0) {
      customTestQuestions = data.questions.map(q => {
        var opts = (q.options || []).map(o => ({
          text: typeof o === 'string' ? o : (o.text || o.body || ''),
          is_correct: typeof o === 'object' && o.is_correct ? 1 : 0
        }));
        if (opts.length > 0 && !opts.some(o => o.is_correct)) {
          opts[0].is_correct = 1;
        }
        while (opts.length < 4) {
          opts.push({ text: '', is_correct: 0 });
        }
        return {
          question: q.question || q.body || '',
          explanation: q.explanation || '',
          options: opts
        };
      });
      renderQuestionCards();
    } else {
      alert('Could not generate questions with AI. Please try again or add questions manually.');
    }
  })
  .catch(err => {
    btn.disabled = false;
    btn.innerHTML = origText;
    alert('Network error during AI question generation.');
  });
}

function addQuestionCard() {
  customTestQuestions.push({
    question: '',
    explanation: '',
    options: [
      { text: '', is_correct: 1 },
      { text: '', is_correct: 0 },
      { text: '', is_correct: 0 },
      { text: '', is_correct: 0 }
    ]
  });
  renderQuestionCards();
}

function removeQuestionCard(idx) {
  customTestQuestions.splice(idx, 1);
  renderQuestionCards();
}

function updateQuestionField(idx, field, value) {
  if (customTestQuestions[idx]) {
    customTestQuestions[idx][field] = value;
  }
}

function updateOptionField(qIdx, optIdx, value) {
  if (customTestQuestions[qIdx] && customTestQuestions[qIdx].options[optIdx]) {
    customTestQuestions[qIdx].options[optIdx].text = value;
  }
}

function setCorrectOption(qIdx, optIdx) {
  if (customTestQuestions[qIdx]) {
    customTestQuestions[qIdx].options.forEach((o, i) => {
      o.is_correct = (i === optIdx) ? 1 : 0;
    });
  }
}

function escapeHtml(unsafe) {
  if (unsafe == null) return '';
  return String(unsafe)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function renderQuestionCards() {
  var container = document.getElementById('questionsContainer');
  var countLabel = document.getElementById('questionCountLabel');
  countLabel.innerText = customTestQuestions.length;

  if (customTestQuestions.length === 0) {
    container.innerHTML = `
      <div class="text-center py-4 text-muted fs-13" id="noQuestionsNotice" style="border: 2px dashed var(--border); border-radius: 8px;">
        No questions added yet. Click <strong>"✨ Generate with AI"</strong> above or <strong>"Add Manual Question"</strong> to get started.
      </div>
    `;
    return;
  }

  var html = '';
  customTestQuestions.forEach((q, qIdx) => {
    html += `
      <div class="card border rounded-3 p-3 bg-light position-relative" style="border-left: 4px solid var(--brand) !important;">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fw-bold text-dark fs-13">Question #${qIdx + 1}</span>
          <button type="button" class="btn btn-sm text-danger p-0 border-0" onclick="removeQuestionCard(${qIdx})" title="Remove question">
            <svg aria-hidden="true" width="14" height="14"><use href="#i-trash"/></svg> Remove
          </button>
        </div>

        <div class="mb-3">
          <input type="text" class="form-control fs-13" placeholder="Enter question statement..." value="${escapeHtml(q.question)}" onchange="updateQuestionField(${qIdx}, 'question', this.value)">
        </div>

        <div class="row g-2 mb-2">
    `;

    var letters = ['A', 'B', 'C', 'D'];
    (q.options || []).forEach((opt, optIdx) => {
      var isChecked = opt.is_correct ? 'checked' : '';
      var letter = letters[optIdx] || (optIdx + 1);
      html += `
        <div class="col-12 col-md-6">
          <div class="input-group input-group-sm">
            <div class="input-group-text bg-white">
              <input type="radio" name="correct_opt_${qIdx}" ${isChecked} onchange="setCorrectOption(${qIdx}, ${optIdx})" title="Mark as correct answer">
              <span class="ms-1 fw-bold fs-11">${letter}.</span>
            </div>
            <input type="text" class="form-control fs-12" placeholder="Option ${letter} text..." value="${escapeHtml(opt.text)}" onchange="updateOptionField(${qIdx}, ${optIdx}, this.value)">
          </div>
        </div>
      `;
    });

    html += `
        </div>
        <div>
          <input type="text" class="form-control form-control-sm fs-11 text-muted" placeholder="Optional explanation for correct answer..." value="${escapeHtml(q.explanation)}" onchange="updateQuestionField(${qIdx}, 'explanation', this.value)">
        </div>
      </div>
    `;
  });

  container.innerHTML = html;
}

function saveCustomTest(e) {
  e.preventDefault();
  var testId = document.getElementById('customTestId').value;
  var title  = document.getElementById('ctTitle').value.trim();
  if (!title) {
    alert('Please enter a test title.');
    return;
  }

  var btn = document.getElementById('btnSaveCustomTest');
  var origText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = 'Saving...';

  var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
  var csrfName = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || '<?= csrf_token() ?>';

  var formData = new FormData();
  formData.append('title', title);
  formData.append('category_id', document.getElementById('ctCategoryId').value);
  formData.append('description', document.getElementById('ctDescription').value);
  formData.append('duration_mins', document.getElementById('ctDurationMins').value);
  formData.append('pass_threshold', document.getElementById('ctPassThreshold').value);
  formData.append('difficulty', document.getElementById('ctDifficulty').value);
  formData.append('questions', JSON.stringify(customTestQuestions));
  formData.append(csrfName, csrfToken);

  var url = testId 
    ? '<?= base_url("employer/aptitude-tests/update/") ?>' + testId 
    : '<?= base_url("employer/aptitude-tests/create") ?>';

  fetch(url, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    btn.disabled = false;
    btn.innerHTML = origText;
    if (data.success) {
      alert(data.message || 'Custom test saved successfully!');
      window.location.reload();
    } else {
      alert(data.message || 'Failed to save test.');
    }
  })
  .catch(err => {
    btn.disabled = false;
    btn.innerHTML = origText;
    alert('Network error while saving custom test.');
  });
}

function deleteCustomTest(testId, title) {
  if (!confirm('Are you sure you want to delete custom test "' + title + '"? This will remove all questions associated with it.')) {
    return;
  }

  var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
  var csrfName = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || '<?= csrf_token() ?>';

  var formData = new FormData();
  formData.append(csrfName, csrfToken);

  fetch('<?= base_url("employer/aptitude-tests/delete/") ?>' + testId, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      alert('Custom test deleted successfully!');
      window.location.reload();
    } else {
      alert(data.message || 'Failed to delete test.');
    }
  })
  .catch(err => alert('Network error while deleting test.'));
}

// ══════════════════════════════════════════════════
// COPY LINK & SHARE MODAL SYSTEM
// ══════════════════════════════════════════════════

function copyTestLink(url, btn) {
  if (!url) {
    if (typeof toastr !== 'undefined') toastr.error('No assessment link available to copy');
    else alert('No assessment link available to copy');
    return;
  }

  function showSuccess() {
    if (typeof toastr !== 'undefined') {
      toastr.success('Assessment link copied to clipboard!');
    }
    if (btn) {
      var origHtml = btn.innerHTML;
      btn.innerHTML = '<svg aria-hidden="true" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Copied!';
      btn.style.color = '#16a34a';
      btn.style.borderColor = '#16a34a';
      setTimeout(function() {
        btn.innerHTML = origHtml;
        btn.style.color = '';
        btn.style.borderColor = '';
      }, 2000);
    }
  }

  if (navigator.clipboard && window.isSecureContext) {
    navigator.clipboard.writeText(url).then(showSuccess).catch(function() {
      fallbackCopyText(url, showSuccess);
    });
  } else {
    fallbackCopyText(url, showSuccess);
  }
}

function fallbackCopyText(text, callback) {
  var textArea = document.createElement("textarea");
  textArea.value = text;
  textArea.style.position = "fixed";
  textArea.style.top = "-9999px";
  textArea.style.left = "-9999px";
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();
  try {
    var successful = document.execCommand('copy');
    if (successful && callback) callback();
    else if (typeof toastr !== 'undefined') toastr.success('Assessment link copied to clipboard!');
  } catch (err) {
    prompt('Copy assessment link manually:', text);
  }
  document.body.removeChild(textArea);
}

function copyInputValue(inputId, btn) {
  var input = document.getElementById(inputId);
  if (!input) return;
  input.select();
  copyTestLink(input.value, btn);
}

var currentShareData = { title: '', officialUrl: '', practiceUrl: '' };

function openEmployerShareModal(title, officialUrl, practiceUrl) {
  currentShareData = { title: title, officialUrl: officialUrl, practiceUrl: practiceUrl };

  var nameEl = document.getElementById('shareModalTestName');
  if (nameEl) nameEl.textContent = 'Share: ' + title;

  var officialInput = document.getElementById('shareOfficialUrlInput');
  if (officialInput) officialInput.value = officialUrl;

  var practiceInput = document.getElementById('sharePracticeUrlInput');
  if (practiceInput) practiceInput.value = practiceUrl;

  var shareMsg = 'Hello! You are invited to take the ' + title + ' assessment on JobberRecruit:\n\n' + officialUrl;
  
  var waBtn = document.getElementById('shareWhatsappLink');
  if (waBtn) waBtn.href = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(shareMsg);

  var mailBtn = document.getElementById('shareEmailLink');
  if (mailBtn) mailBtn.href = 'mailto:?subject=' + encodeURIComponent('Aptitude Assessment Invitation: ' + title) + '&body=' + encodeURIComponent(shareMsg);

  var nativeBtn = document.getElementById('shareNativeBtn');
  if (nativeBtn) {
    nativeBtn.style.display = (navigator.share) ? 'inline-flex' : 'none';
  }

  var modalEl = document.getElementById('shareTestModal');
  if (!modalEl) return;
  modalEl.style.display = 'flex';
  modalEl.classList.add('show');
  document.body.classList.add('modal-open');
}

function closeShareTestModal() {
  var modalEl = document.getElementById('shareTestModal');
  if (!modalEl) return;
  modalEl.style.display = 'none';
  modalEl.classList.remove('show');
  document.body.classList.remove('modal-open');
}

function nativeShareTest() {
  if (navigator.share && currentShareData.officialUrl) {
    navigator.share({
      title: currentShareData.title,
      text: 'Take the ' + currentShareData.title + ' assessment on JobberRecruit',
      url: currentShareData.officialUrl
    }).catch(function() {});
  }
}

// ══════════════════════════════════════════════════
// RESEND INVITATION AJAX
// ══════════════════════════════════════════════════

function resendInvitation(invId, btn) {
  if (!invId) return;

  var origHtml = btn ? btn.innerHTML : '';
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = '<svg aria-hidden="true" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>';
  }

  var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
  var csrfName = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || '<?= csrf_token() ?>';

  var formData = new FormData();
  formData.append('invitation_id', invId);
  formData.append(csrfName, csrfToken);

  fetch('<?= base_url("employer/aptitude-tests/resend-invite") ?>', {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    if (btn) {
      btn.disabled = false;
      btn.innerHTML = origHtml;
    }
    if (data.success) {
      if (typeof toastr !== 'undefined') toastr.success(data.message || 'Invitation resent successfully!');
      else alert(data.message || 'Invitation resent successfully!');
    } else {
      if (typeof toastr !== 'undefined') toastr.error(data.message || 'Failed to resend invitation.');
      else alert(data.message || 'Failed to resend invitation.');
    }
  })
  .catch(err => {
    if (btn) {
      btn.disabled = false;
      btn.innerHTML = origHtml;
    }
    if (typeof toastr !== 'undefined') toastr.error('Network error while resending invitation.');
    else alert('Network error while resending invitation.');
  });
}

// ══════════════════════════════════════════════════
// ATTEMPT RESULT MODAL & ANALYSIS
// ══════════════════════════════════════════════════

function viewAttemptResult(invId) {
  var modalEl = document.getElementById('attemptResultModal');
  var bodyEl  = document.getElementById('attemptResultBody');
  var subEl   = document.getElementById('attemptResultSubTitle');
  var actEl   = document.getElementById('attemptResultActions');

  if (!modalEl || !bodyEl) return;

  bodyEl.innerHTML = '<div style="text-align:center; padding:40px; color:var(--muted);"><svg aria-hidden="true" width="28" height="28" style="animation:spin 1s linear infinite;"><use href="#i-loader"/></svg><p style="margin-top:10px;">Loading candidate assessment result...</p></div>';
  actEl.innerHTML = '';
  modalEl.style.display = 'flex';
  modalEl.classList.add('show');
  document.body.classList.add('modal-open');

  fetch('<?= base_url("employer/aptitude-tests/result/") ?>' + invId)
    .then(r => r.json())
    .then(res => {
      if (!res.success) {
        bodyEl.innerHTML = '<div style="text-align:center; padding:30px; color:var(--danger);">' + (res.message || 'Unable to load test result.') + '</div>';
        return;
      }

      var c = res.candidate;
      var a = res.attempt;
      var breakdown = res.breakdown || [];

      subEl.textContent = c.name + ' • ' + (c.job_title || 'General Applicant') + ' • ' + (c.test_title || '');

      var isPassed = a.passed;
      var scoreColor = isPassed ? '#16a34a' : '#dc2626';
      var scoreBg = isPassed ? 'rgba(22, 163, 74, 0.08)' : 'rgba(220, 38, 38, 0.08)';

      var html = `
        <div style="background:${scoreBg}; border:1px solid ${scoreColor}; border-radius:12px; padding:18px 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:20px;">
          <div>
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:${scoreColor}; letter-spacing:0.04em;">Assessment Verdict</div>
            <div style="font-size:1.4rem; font-weight:800; color:${scoreColor}; display:flex; align-items:center; gap:8px;">
              ${isPassed ? 'PASSED ✓' : 'FAILED ✗'}
              <span style="font-size:1.1rem; font-weight:700; color:var(--text); opacity:0.8;">(${a.score_pct ?? 0}%)</span>
            </div>
            <div style="font-size:0.8rem; color:var(--muted); margin-top:2px;">
              Pass benchmark: <strong>${a.pass_threshold}%</strong> • Correct: <strong>${a.num_correct}</strong> / ${a.num_total} questions
            </div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:0.75rem; color:var(--muted);">Submitted Date</div>
            <div style="font-size:0.85rem; font-weight:600; color:var(--brand-deep);">${a.submitted_at || 'In Progress'}</div>
          </div>
        </div>
      `;

      if (breakdown.length > 0) {
        html += '<h6 style="font-weight:700; font-size:0.88rem; color:var(--brand-deep); margin:0 0 12px;">Detailed Question Responses (' + breakdown.length + ')</h6>';
        html += '<div style="display:flex; flex-direction:column; gap:12px;">';

        breakdown.forEach((q, qIdx) => {
          var qBorder = q.is_correct ? '#16a34a' : '#dc2626';
          html += `
            <div style="border:1px solid var(--border); border-left:4px solid ${qBorder}; border-radius:8px; padding:14px; background:#fff;">
              <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px; margin-bottom:8px;">
                <span style="font-weight:700; font-size:0.84rem; color:var(--text);">Q${q.number}. ${escapeHtml(q.body)}</span>
                <span style="font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:12px; background:${q.is_correct ? '#e8f7ee' : '#fdeaea'}; color:${q.is_correct ? '#16a34a' : '#dc2626'}; flex-shrink:0;">
                  ${q.is_correct ? 'Correct (+1)' : 'Incorrect (0)'}
                </span>
              </div>
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
          `;

          (q.options || []).forEach(opt => {
            var optStyle = 'border:1px solid #e2e8f0; background:#f8fafc; color:#475569;';
            var icon = '';
            if (opt.correct) {
              optStyle = 'border:1px solid #86efac; background:#f0fdf4; color:#166534; font-weight:600;';
              icon = ' <span style="color:#16a34a; font-weight:bold;">✓ (Correct)</span>';
            }
            if (opt.chosen && !opt.correct) {
              optStyle = 'border:1px solid #fca5a5; background:#fef2f2; color:#991b1b;';
              icon = ' <span style="color:#dc2626; font-weight:bold;">✗ (Candidate Pick)</span>';
            } else if (opt.chosen && opt.correct) {
              icon = ' <span style="color:#16a34a; font-weight:bold;">✓ (Candidate Pick - Correct)</span>';
            }

            html += `<div style="padding:6px 10px; border-radius:6px; font-size:0.8rem; ${optStyle}">${escapeHtml(opt.body)}${icon}</div>`;
          });

          html += '</div>';

          if (q.explanation) {
            html += `<div style="font-size:0.76rem; color:var(--muted); margin-top:8px; background:var(--bg-alt); padding:6px 10px; border-radius:6px;">
              <strong>Explanation:</strong> ${escapeHtml(q.explanation)}
            </div>`;
          }

          html += '</div>';
        });

        html += '</div>';
      }

      bodyEl.innerHTML = html;

      // Quick action buttons in footer
      if (c.application_id > 0) {
        actEl.innerHTML = `
          <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" style="color:var(--danger); border-color:var(--danger);" onclick="closeAttemptResultModal(); openAdvanceStageModal(${c.application_id}, '${escapeHtml(c.name)}', 'rejected');">
            Reject
          </button>
          <button type="button" class="emp-btn emp-btn-primary emp-btn-sm" onclick="closeAttemptResultModal(); openAdvanceStageModal(${c.application_id}, '${escapeHtml(c.name)}', 'shortlisted');">
            Shortlist Candidate
          </button>
          <button type="button" class="emp-btn emp-btn-accent emp-btn-sm" onclick="closeAttemptResultModal(); openAdvanceStageModal(${c.application_id}, '${escapeHtml(c.name)}', 'interview');">
            Schedule Interview
          </button>
        `;
      }
    })
    .catch(err => {
      bodyEl.innerHTML = '<div style="text-align:center; padding:30px; color:var(--danger);">Error loading attempt result.</div>';
    });
}

function closeAttemptResultModal() {
  var modalEl = document.getElementById('attemptResultModal');
  if (!modalEl) return;
  modalEl.style.display = 'none';
  modalEl.classList.remove('show');
  document.body.classList.remove('modal-open');
}

// ══════════════════════════════════════════════════
// ADVANCE CANDIDATE STAGE MODAL
// ══════════════════════════════════════════════════

function openAdvanceStageModal(appId, candName, defaultStage) {
  var modalEl = document.getElementById('advanceStageModal');
  if (!modalEl) return;

  document.getElementById('advAppId').value = appId;
  document.getElementById('advanceStageCandName').textContent = 'Candidate: ' + candName;
  if (defaultStage) {
    document.getElementById('advStageSelect').value = defaultStage;
  }
  document.getElementById('advStageNotes').value = '';

  modalEl.style.display = 'flex';
  modalEl.classList.add('show');
  document.body.classList.add('modal-open');
}

function closeAdvanceStageModal() {
  var modalEl = document.getElementById('advanceStageModal');
  if (!modalEl) return;
  modalEl.style.display = 'none';
  modalEl.classList.remove('show');
  document.body.classList.remove('modal-open');
}

function confirmAdvanceStage(e) {
  e.preventDefault();
  var appId = document.getElementById('advAppId').value;
  var stage = document.getElementById('advStageSelect').value;
  var notes = document.getElementById('advStageNotes').value;

  var btn = document.getElementById('btnConfirmAdvanceStage');
  var origText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = 'Updating...';

  var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
  var csrfName = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || '<?= csrf_token() ?>';

  var formData = new FormData();
  formData.append('application_id', appId);
  formData.append('stage', stage);
  formData.append('notes', notes);
  formData.append(csrfName, csrfToken);

  fetch('<?= base_url("employer/aptitude-tests/advance-stage") ?>', {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  })
  .then(r => r.json())
  .then(res => {
    btn.disabled = false;
    btn.innerHTML = origText;
    if (res.success) {
      if (typeof toastr !== 'undefined') toastr.success(res.message);
      else alert(res.message);
      closeAdvanceStageModal();
      setTimeout(function() { window.location.reload(); }, 1200);
    } else {
      if (typeof toastr !== 'undefined') toastr.error(res.message || 'Failed to update stage.');
      else alert(res.message || 'Failed to update stage.');
    }
  })
  .catch(err => {
    btn.disabled = false;
    btn.innerHTML = origText;
    if (typeof toastr !== 'undefined') toastr.error('Network error.');
    else alert('Network error.');
  });
}
</script>

<?= $this->endSection() ?>
