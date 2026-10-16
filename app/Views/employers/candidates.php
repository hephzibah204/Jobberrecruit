<?php $page_title = 'Candidates Search'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.cand-layout {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 20px;
  align-items: start;
}
@media (max-width: 1024px) {
  .cand-layout { grid-template-columns: 1fr; }
  /* The shared entrance animation retains a transform and stacking context.
     A fixed drawer inside it would sit underneath the page scrim on Safari. */
  .emp-content > .cand-layout {
    animation: none !important;
    -webkit-transform: none !important;
    transform: none !important;
  }
}
/* ══════════════════════════════════════════════════════
   MYJOBMAG-STYLE CANDIDATE CARDS & SEARCH UI
   ══════════════════════════════════════════════════════ */
.cand-results-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
  min-width: 0;
  width: 100%;
}

/* Standalone Candidate Card */
.cand-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 20px 22px;
  box-shadow: 0 1px 3px rgba(10, 47, 87, 0.04);
  transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
  display: flex;
  flex-direction: column;
  gap: 14px;
  position: relative;
}
.cand-card:hover {
  border-color: #cbd5e1;
  box-shadow: 0 6px 18px rgba(10, 47, 87, 0.07);
  transform: translateY(-1px);
}

/* Card Top: Avatar + Identity + Activity Badge */
.cand-card-head {
  display: flex;
  align-items: flex-start;
  gap: 16px;
}
.cc-ava {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: #0A2F57;
  color: #fff;
  font-family: 'Sora', sans-serif;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.95rem;
  flex-shrink: 0;
  object-fit: cover;
}
.cand-card-info {
  flex: 1;
  min-width: 0;
}
.cand-name-row {
  display: flex;
  align-items: center;
  gap: 8px 12px;
  flex-wrap: wrap;
}
.cc-name {
  font-family: 'Sora', sans-serif;
  font-weight: 700;
  font-size: 1.05rem;
  color: #0A2F57;
  margin: 0;
  line-height: 1.3;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.cc-name a {
  color: inherit;
  text-decoration: none;
}
.cc-name a:hover {
  color: #0284c7;
}

/* Activity Badge (MyJobMag signature feature) */
.cand-activity-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 700;
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #a7f3d0;
  white-space: nowrap;
}
.cand-activity-badge .pulse-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #10b981;
}
.cand-activity-badge.active-today {
  background: #ecfdf5;
  color: #065f46;
  border-color: #6ee7b7;
}
.cand-activity-badge.active-fortnight {
  background: #f0fdf4;
  color: #166534;
  border-color: #bbf7d0;
}
.cand-activity-badge.active-month {
  background: #f8fafc;
  color: #475569;
  border-color: #e2e8f0;
}
.cand-activity-badge.active-open {
  background: #f8fafc;
  color: #64748b;
  border-color: #e2e8f0;
}

.cand-unlocked-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 3px 9px;
  border-radius: 999px;
  font-size: 0.7rem;
  font-weight: 700;
  background: #eff6ff;
  color: #1d4ed8;
  border: 1px solid #bfdbfe;
  white-space: nowrap;
}
.cand-unlocked-badge svg {
  width: 12px;
  height: 12px;
}

/* Candidate Role */
.cc-role {
  font-size: 0.92rem;
  font-weight: 600;
  color: #334155;
  margin-top: 4px;
}

/* MyJobMag 3-Part Trust Line: Location | CV Freshness | Profile Completeness */
.cand-trust-line {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  font-size: 0.8rem;
  color: #64748b;
  margin-top: 6px;
  line-height: 1.4;
}
.cand-trust-item {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  white-space: nowrap;
}
.cand-trust-item svg {
  width: 13px;
  height: 13px;
  color: #94a3b8;
  flex-shrink: 0;
}
.cand-trust-sep {
  color: #cbd5e1;
  font-size: 0.75rem;
  user-select: none;
}
.cand-complete-tag {
  color: #059669;
  font-weight: 600;
}
.cand-complete-tag svg {
  color: #059669;
}

/* JobberRecruit Key Criteria Data Bar */
.cand-data-row {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 8px 14px;
  padding: 9px 14px;
  background: #f8fafc;
  border: 1px solid #f1f5f9;
  border-radius: 8px;
  font-size: 0.78rem;
  color: #475569;
}
.cand-data-item {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  white-space: nowrap;
}
.cand-data-item svg {
  width: 13px;
  height: 13px;
  color: #64748b;
  flex-shrink: 0;
}
.cand-data-item b {
  color: #0f172a;
}
.cand-salary-item b {
  color: #0A2F57;
}

/* Skill Chips (Expanded up to 6 chips) */
.cand-skills-row {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  align-items: center;
}
.cand-skill-chip {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 0.74rem;
  font-weight: 500;
  color: #334155;
  white-space: nowrap;
  max-width: 240px;
  overflow: hidden;
  text-overflow: ellipsis;
}
.cand-skill-more {
  display: inline-flex;
  align-items: center;
  padding: 4px 9px;
  background: #ffffff;
  border: 1px dashed #cbd5e1;
  border-radius: 6px;
  font-size: 0.72rem;
  font-weight: 600;
  color: #64748b;
}

/* Action Footer (View, Save, Message) */
.cand-card-foot {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  padding-top: 12px;
  border-top: 1px solid #f1f5f9;
  margin-top: 2px;
}
.cand-btn-view {
  min-height: 40px;
  padding: 8px 20px;
  font-weight: 600;
  font-size: 0.86rem;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  text-decoration: none !important;
}
.cand-btn-save {
  min-height: 40px;
  padding: 8px 16px;
  font-weight: 600;
  font-size: 0.86rem;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background: #ffffff;
  border: 1px solid #d1d5db;
  color: #334155;
  cursor: pointer;
  transition: all 0.15s ease;
}
.cand-btn-save:hover {
  background: #f8fafc;
  border-color: #94a3b8;
  color: #0f172a;
}
.cand-btn-save.is-saved {
  background: #eff6ff;
  border-color: #93c5fd;
  color: #1d4ed8;
}
.cand-btn-save svg {
  width: 14px;
  height: 14px;
}
.cand-btn-msg {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  background: #ffffff;
  color: #64748b;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.15s ease;
}
.cand-btn-msg:hover {
  background: #f1f5f9;
  color: #0A2F57;
  border-color: #cbd5e1;
}
.cand-btn-msg svg {
  width: 16px;
  height: 16px;
}

/* Active Filter Chips Bar */
.active-filter-chips {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  padding: 10px 14px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  margin-top: 6px;
  font-size: 0.8rem;
}
.active-filter-lbl {
  font-weight: 600;
  color: #64748b;
}
.filter-pill-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 3px 10px;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-radius: 20px;
  font-size: 0.76rem;
  font-weight: 600;
  color: #1e293b;
}
.filter-pill-remove {
  color: #94a3b8;
  font-weight: 700;
  font-size: 0.95rem;
  line-height: 1;
  text-decoration: none;
}
.filter-pill-remove:hover {
  color: #ef4444;
}
.filter-clear-link {
  font-size: 0.76rem;
  font-weight: 600;
  color: #ef4444;
  margin-left: auto;
  text-decoration: underline;
}

/* Mobile Layout Stack (MyJobMag experience) */
@media (max-width: 768px) {
  .cand-card {
    padding: 16px;
    gap: 12px;
    border-radius: 12px;
  }
  .cand-card-head {
    gap: 12px;
  }
  .cc-ava {
    width: 44px;
    height: 44px;
    font-size: 0.88rem;
  }
  .cc-name {
    font-size: 0.98rem;
  }
  .cand-trust-line {
    font-size: 0.76rem;
    gap: 6px;
  }
  .cand-card-foot {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 8px;
    padding-top: 10px;
  }
  .cand-btn-view,
  .cand-btn-save {
    width: 100%;
    min-height: 44px;
    font-size: 0.84rem;
    padding: 8px 10px;
  }
  .cand-btn-msg {
    min-width: 44px;
    min-height: 44px;
  }
}
.cand-list-head {
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  gap: 10px !important;
  flex-wrap: wrap !important;
}
.cand-list-head span {
  white-space: nowrap;
}

/* ══════════════════════════════════════════════════════
   MOBILE & iOS SAFARI FILTERS DRAWER HARDENING
   ══════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .filters-card,
  #filters,
  aside#filters.card {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    bottom: 0 !important;
    right: auto !important;
    width: min(320px, 86vw) !important;
    max-width: 86vw !important;
    height: 100% !important;
    height: 100dvh !important;
    z-index: 1300 !important;
    background: #ffffff !important;
    border-radius: 0 !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25) !important;
    padding-bottom: calc(28px + env(safe-area-inset-bottom, 0px)) !important;
    margin: 0 !important;

    /* Off-canvas state (CLOSED) - strictly hidden from view & interaction */
    transform: translateX(-100%) !important;
    -webkit-transform: translateX(-100%) !important;
    visibility: hidden !important;
    pointer-events: none !important;
    transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                -webkit-transform 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                visibility 0s linear 0.28s !important;
  }

  /* When OPEN - slide smoothly into view */
  .filters-card.open,
  #filters.open,
  aside#filters.card.open {
    transform: none !important;
    -webkit-transform: none !important;
    visibility: visible !important;
    pointer-events: auto !important;
    transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                -webkit-transform 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                visibility 0s linear 0s !important;
  }

  .f-close {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    min-height: 44px;
    padding: 10px;
    background: transparent;
    border: none;
    cursor: pointer;
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
  }
  .f-close svg,
  .f-close svg * {
    pointer-events: none;
  }

  #f-open {
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
  }
  #f-open svg,
  #f-open svg * {
    pointer-events: none;
  }

  .f-actions-mobile {
    display: block;
    padding: 14px 18px 20px;
  }
}
@media (min-width: 1025px) {
  .f-actions-mobile {
    display: none;
  }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Query database directly to fetch unlocked IDs for the logged-in employer
$db = \Config\Database::connect();
$unlockedIds = [];
if (!empty($employer->id)) {
    $unlockedIds = array_column(
        $db->table('candidate_unlocks')
           ->select('job_seeker_id')
           ->where('employer_id', $employer->id)
           ->get()
           ->getResultArray(),
        'job_seeker_id'
    );
}

// Fetch wallet balance
$walletRow = $db->table('wallets')->where('user_id', $user->id)->get()->getRow();
$walletBalanceValue = $walletRow ? (float)$walletRow->balance : 0;
$walletBalanceFormatted = '₦' . number_format($walletBalanceValue, 2);
?>

<div class="page-head">
    <div class="page-head-left">
        <h1><svg aria-hidden="true"><use href="#i-search-user"/></svg> Find Candidates</h1>
        <p>Search verified candidates ready to work.</p>
    </div>
</div>

<div class="cand-layout">
    <!-- filters sidebar -->
    <aside class="card filters-card" id="filters" aria-label="Candidate filters">
        <form method="GET" action="<?= current_url() ?>" id="filters-form">
            <div class="f-head">
                <b><svg aria-hidden="true"><use href="#i-filter"/></svg> Filters</b>
                <button type="button" class="f-close" id="f-close" aria-label="Close filters"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
            </div>
            
            <!-- Desired Roles Filter -->
            <?php if (!empty($jobTitleCounts)): ?>
                <div class="f-group">
                    <div class="f-label">Desired role</div>
                    <?php foreach ($jobTitleCounts as $row): ?>
                        <?php if (!empty($row->job_title)): ?>
                            <label class="f-opt">
                                <input type="checkbox" name="job_title[]" value="<?= esc($row->job_title) ?>" 
                                    <?= in_array($row->job_title, (array)request()->getGet('job_title')) ? 'checked' : '' ?>
                                    onchange="this.form.submit()">
                                <?= esc($row->job_title) ?>
                                <span class="n"><?= $row->total ?></span>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Skills Filter (if variable passed) -->
            <?php if (!empty($skills)): ?>
                <div class="f-group">
                    <div class="f-label">Skills</div>
                    <?php foreach ($skills as $skill): ?>
                        <label class="f-opt">
                            <input type="checkbox" name="skill[]" value="<?= esc($skill) ?>"
                                <?= in_array($skill, (array)request()->getGet('skill')) ? 'checked' : '' ?>
                                onchange="this.form.submit()">
                            <?= esc($skill) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- State / Location Filter -->
            <?php if (!empty($states)): ?>
                <div class="f-group">
                    <div class="f-label">State / Region</div>
                    <select name="state_id" class="select w-100 mb-2" onchange="this.form.submit()" style="min-height:38px;font-size:0.82rem;padding:6px 10px;">
                        <option value="">All Nigerian States</option>
                        <?php foreach ($states as $st): ?>
                            <option value="<?= $st->id ?>" <?= (request()->getGet('state_id') == $st->id || request()->getGet('state') == $st->id) ? 'selected' : '' ?>>
                                <?= esc($st->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="f-group">
                    <div class="f-label">City / Town</div>
                    <input type="text" name="city" class="input w-100" placeholder="e.g. Ikeja, Lekki, Wuse" value="<?= esc(request()->getGet('city') ?? '') ?>" style="min-height:38px;font-size:0.82rem;padding:6px 10px;" onchange="this.form.submit()">
                </div>
            <?php endif; ?>

            <!-- Locations Filter (if variable passed) -->
            <?php if (!empty($locations)): ?>
                <div class="f-group">
                    <div class="f-label">Location</div>
                    <?php foreach ($locations as $loc): ?>
                        <label class="f-opt">
                            <input type="checkbox" name="location[]" value="<?= esc($loc) ?>"
                                <?= in_array($loc, (array)request()->getGet('location')) ? 'checked' : '' ?>
                                onchange="this.form.submit()">
                            <?= esc($loc) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Employment type Filter -->
            <?php if (!empty($jobTypeCounts)): ?>
                <div class="f-group">
                    <div class="f-label">Employment type</div>
                    <?php foreach ($jobTypeCounts as $row): ?>
                        <?php if (!empty($row->employment_type)): ?>
                            <label class="f-opt">
                                <input type="checkbox" name="employment_type[]" value="<?= esc($row->employment_type) ?>"
                                    <?= in_array($row->employment_type, (array)request()->getGet('employment_type')) ? 'checked' : '' ?>
                                    onchange="this.form.submit()">
                                <span class="text-capitalize"><?= esc(str_replace('-', ' ', $row->employment_type)) ?></span>
                                <span class="n"><?= $row->total ?></span>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Education Level Filter -->
            <?php if (!empty($educationCounts)): ?>
                <div class="f-group">
                    <div class="f-label">Education level</div>
                    <?php foreach ($educationCounts as $row): ?>
                        <?php if (!empty($row->education_level)): ?>
                            <label class="f-opt">
                                <input type="checkbox" name="education_level[]" value="<?= esc($row->education_level) ?>"
                                    <?= in_array($row->education_level, (array)request()->getGet('education_level')) ? 'checked' : '' ?>
                                    onchange="this.form.submit()">
                                <?= esc($row->education_level) ?>
                                <span class="n"><?= $row->total ?></span>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Activity / Recency Filter -->
            <div class="f-group">
                <div class="f-label">Candidate Activity</div>
                <p class="hint">Based on recent account use or a job application.</p>
                <?php
                $currAct = request()->getGet('activity') ?? 'all';
                $actOptions = [
                    'all' => 'All candidates',
                    'this_week' => 'Active this week',
                    'last_week' => 'Active in last 2 weeks',
                    'this_month' => 'Active this month',
                    'last_3_months' => 'Active in last 3 months',
                    'older' => 'Older / inactive profiles',
                ];
                foreach ($actOptions as $actVal => $actLabel):
                ?>
                    <label class="f-opt">
                        <input type="radio" name="activity" value="<?= esc($actVal) ?>"
                            <?= $currAct === $actVal ? 'checked' : '' ?>
                            onchange="this.form.submit()">
                        <?= esc($actLabel) ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <a href="<?= current_url() ?>" class="emp-btn emp-btn-outline emp-btn-sm f-clear text-center">Clear all filters</a>
            <div class="f-actions-mobile">
                <button type="button" class="emp-btn emp-btn-primary w-100" id="f-apply-btn">View Results</button>
            </div>
        </form>
    </aside>

    <div style="display:flex;flex-direction:column;gap:20px;min-width:0;width:100%">
        <!-- Search Bar Card -->
        <section class="card" aria-label="Candidate search">
            <div class="card-body" style="display:flex;flex-direction:column;gap:12px">
                <!-- These inputs submit into #filters-form (defined in the sidebar) via the form="" attribute,
                     so keyword/sort survive alongside the checkbox filters instead of being two disconnected forms. -->
                <div class="toolbar">
                    <button type="button" class="emp-btn emp-btn-outline emp-btn-sm mob-filter-btn" id="f-open" aria-controls="filters" aria-expanded="false">
                        <svg aria-hidden="true"><use href="#i-filter"/></svg> Filters
                    </button>
                    <div class="search-wrap">
                        <svg aria-hidden="true"><use href="#i-search"/></svg>
                        <input class="input" type="search" name="keyword" form="filters-form" value="<?= esc(request()->getGet('keyword') ?? '') ?>" placeholder="Search by name, skills, or job title…" aria-label="Search candidates">
                    </div>
                    <select class="select" name="sort" form="filters-form" aria-label="Sort candidates">
                        <option value="best_match" <?= request()->getGet('sort') === 'best_match' ? 'selected' : '' ?>>Best match</option>
                        <option value="most_experienced" <?= request()->getGet('sort') === 'most_experienced' ? 'selected' : '' ?>>Most experienced</option>
                        <option value="recently_active" <?= request()->getGet('sort') === 'recently_active' ? 'selected' : '' ?>>Recently active</option>
                    </select>
                    <button type="submit" form="filters-form" class="emp-btn emp-btn-primary">Search</button>
                </div>
                <div class="cand-list-head">
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        <span class="result-count"><b><?= number_format($total ?? 0) ?></b> candidates found</span>
                        <?php if (request()->getGet('view') === 'favorites' || request()->getGet('view') === 'saved'): ?>
                            <span class="pill pill--brand" style="font-size:0.72rem;padding:2px 8px;"><svg aria-hidden="true" style="width:12px;height:12px;fill:currentColor;"><use href="#i-star"/></svg> Shortlisted Only</span>
                        <?php endif; ?>
                    </div>
                    <span style="font-size:.72rem;color:var(--muted);display:inline-flex;align-items:center;gap:6px;white-space:nowrap;flex-shrink:0;">
                        <span class="cc-dot" aria-hidden="true"></span> Open to work
                    </span>
                </div>

                <!-- Active Filter Tags (MyJobMag pattern) -->
                <?php
                $activeFilterPills = [];
                if (!empty(request()->getGet('keyword'))) {
                    $activeFilterPills[] = ['label' => 'Keyword: "' . esc(request()->getGet('keyword')) . '"', 'param' => 'keyword'];
                }
                if (!empty(request()->getGet('job_title'))) {
                    foreach ((array)request()->getGet('job_title') as $jt) {
                        if (!empty($jt)) $activeFilterPills[] = ['label' => esc($jt), 'param' => 'job_title'];
                    }
                }
                if (!empty(request()->getGet('skill'))) {
                    foreach ((array)request()->getGet('skill') as $sk) {
                        if (!empty($sk)) $activeFilterPills[] = ['label' => 'Skill: ' . esc($sk), 'param' => 'skill'];
                    }
                }
                if (!empty(request()->getGet('city'))) {
                    $activeFilterPills[] = ['label' => 'City: ' . esc(request()->getGet('city')), 'param' => 'city'];
                }
                if (!empty(request()->getGet('state_id'))) {
                    $stName = '';
                    if (!empty($states)) {
                        foreach ($states as $st) {
                            if ($st->id == request()->getGet('state_id')) { $stName = $st->name; break; }
                        }
                    }
                    if ($stName) {
                        $activeFilterPills[] = ['label' => esc($stName), 'param' => 'state_id'];
                    }
                }
                if (!empty(request()->getGet('activity')) && request()->getGet('activity') !== 'all') {
                    $actLabels = [
                        'this_week'     => 'Active this week',
                        'last_week'     => 'Active in last 2 weeks',
                        'this_month'    => 'Active this month',
                        'last_3_months' => 'Active in last 3 months',
                    ];
                    $activeFilterPills[] = ['label' => $actLabels[request()->getGet('activity')] ?? 'Active', 'param' => 'activity'];
                }
                ?>
                <?php if (!empty($activeFilterPills)): ?>
                    <div class="active-filter-chips">
                        <span class="active-filter-lbl">Active filters:</span>
                        <?php foreach ($activeFilterPills as $pill): ?>
                            <span class="filter-pill-tag">
                                <?= $pill['label'] ?>
                                <a href="<?= current_url() . '?' . http_build_query(array_diff_key(request()->getGet(), [$pill['param'] => ''])) ?>" class="filter-pill-remove" title="Remove filter">×</a>
                            </span>
                        <?php endforeach; ?>
                        <a href="<?= current_url() ?>" class="filter-clear-link">Clear all</a>
                    </div>
                <?php endif; ?>

                <!-- Pricing Disclosure -->
                <div class="notice notice--info" role="note" style="margin-top:2px">
                    <svg aria-hidden="true"><use href="#i-bulb"/></svg>
                    <span>Searching and <b>messaging candidates is free</b>. Unlocking a candidate's contact details and CV download costs <b>₦5,000 per candidate</b>, charged from your wallet.</span>
                </div>
            </div>
        </section>

        <!-- Candidate Results List (MyJobMag Standalone Cards) -->
        <?php if (!empty($candidates)): ?>
            <div class="cand-results-list" id="cand-results-container" aria-label="Candidate results">
                <?php foreach ($candidates as $c): ?>
                    <?php
                    $initials = '';
                    $words = explode(' ', preg_replace('/\s+/', ' ', trim($c->full_name ?? '')));
                    $initials = count($words) >= 2
                        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                        : strtoupper(substr($c->full_name ?? 'C', 0, 2));
                    
                    $isUnlocked = in_array($c->id, $unlockedIds ?? []) || ($hasUnlimitedAccess ?? false);
                    $isSaved = in_array($c->id, $savedIds ?? []);

                    // 1. MyJobMag Activity Badge calculation
                    $activityLabel = 'Active recently';
                    $activityClass = 'active-recent';
                    $lastAct = !empty($c->last_activity_at) ? strtotime($c->last_activity_at) : (!empty($c->updated_at) ? strtotime($c->updated_at) : 0);
                    if ($lastAct > 0) {
                        $diffDays = (time() - $lastAct) / 86400;
                        if ($diffDays <= 2) {
                            $activityLabel = 'Active today';
                            $activityClass = 'active-today';
                        } elseif ($diffDays <= 7) {
                            $activityLabel = 'Active this week';
                            $activityClass = 'active-week';
                        } elseif ($diffDays <= 14) {
                            $activityLabel = 'Active in last 2 weeks';
                            $activityClass = 'active-fortnight';
                        } elseif ($diffDays <= 30) {
                            $activityLabel = 'Active this month';
                            $activityClass = 'active-month';
                        } else {
                            $activityLabel = 'Open to work';
                            $activityClass = 'active-open';
                        }
                    } else {
                        $activityLabel = 'Active this week';
                        $activityClass = 'active-week';
                    }

                    // 2. Location string
                    $locParts = [];
                    if (!empty($c->city)) $locParts[] = trim($c->city);
                    if (!empty($c->state_name)) $locParts[] = trim($c->state_name);
                    elseif (!empty($c->location)) $locParts[] = trim($c->location);
                    $locationText = !empty($locParts) ? implode(', ', $locParts) : 'Nigeria';

                    // 3. CV Freshness calculation
                    $cvFreshness = 'CV on file';
                    $cvRef = !empty($c->updated_at) ? strtotime($c->updated_at) : (!empty($c->created_at) ? strtotime($c->created_at) : 0);
                    if ($cvRef > 0) {
                        $days = floor((time() - $cvRef) / 86400);
                        if ($days <= 1) {
                            $cvFreshness = 'CV updated today';
                        } elseif ($days < 30) {
                            $cvFreshness = 'CV updated ' . $days . ' days ago';
                        } elseif ($days < 365) {
                            $months = max(1, floor($days / 30));
                            $cvFreshness = 'CV updated ' . $months . ' mo ago';
                        } else {
                            $cvFreshness = 'CV updated recently';
                        }
                    }

                    // 4. Profile completeness
                    $pct = (int)($c->profile_completion ?? 0);
                    if ($pct <= 0) {
                        $checks = [!empty($c->full_name), !empty($c->job_title), !empty($c->skills), !empty($c->education_level), !empty($c->experience_years), !empty($c->resume)];
                        $pct = round((count(array_filter($checks)) / count($checks)) * 100);
                    }
                    $isComplete = $pct >= 80;
                    $completenessText = $isComplete ? 'Profile complete' : $pct . '% complete';

                    // Avatar check
                    $rawPic = $c->profile_picture ?? '';
                    $hasPic = false;
                    $picUrl = '';
                    if (!empty($rawPic)) {
                        if (filter_var($rawPic, FILTER_VALIDATE_URL) || str_starts_with($rawPic, 'http://') || str_starts_with($rawPic, 'https://')) {
                            $hasPic = true;
                            $picUrl = $rawPic;
                        } else {
                            $cleanPath = ltrim($rawPic, '/\\');
                            if (file_exists(FCPATH . $cleanPath)) {
                                $hasPic = true;
                                $picUrl = base_url($cleanPath);
                            } elseif (file_exists(FCPATH . 'uploads/' . $cleanPath)) {
                                $hasPic = true;
                                $picUrl = base_url('uploads/' . $cleanPath);
                            }
                        }
                    }
                    $fallbackAvatar = "https://ui-avatars.com/api/?name=" . urlencode(trim($c->full_name ?? 'Candidate')) . "&background=0A2F57&color=fff&size=128&bold=true";

                    // Skills slice (up to 6 readable chips)
                    $skillsList = array_filter(array_map('trim', explode(',', $c->skills ?? '')));
                    $skillsSlice = array_slice($skillsList, 0, 6);
                    ?>

                    <article class="cand-card" id="cand-item-<?= $c->id ?>">
                        <div class="cand-card-head">
                            <?php if ($hasPic): ?>
                                <img src="<?= esc($picUrl) ?>" alt="<?= esc($c->full_name) ?>" class="cc-ava" onerror="this.onerror=null; this.src='<?= esc($fallbackAvatar) ?>';">
                            <?php else: ?>
                                <span class="cc-ava" aria-hidden="true"><?= esc($initials) ?></span>
                            <?php endif; ?>

                            <div class="cand-card-info">
                                <div class="cand-name-row">
                                    <h3 class="cc-name">
                                        <a href="<?= base_url('employer/candidates/view/' . $c->id) ?>"><?= esc($c->full_name) ?></a>
                                    </h3>
                                    <span class="cand-activity-badge <?= esc($activityClass) ?>">
                                        <span class="pulse-dot"></span> <?= esc($activityLabel) ?>
                                    </span>
                                    <?php if ($isUnlocked): ?>
                                        <span class="cand-unlocked-badge"><svg aria-hidden="true"><use href="#i-check"/></svg> Unlocked</span>
                                    <?php endif; ?>
                                </div>

                                <div class="cc-role"><?= esc($c->job_title ?? 'Professional') ?></div>

                                <!-- MyJobMag 3-Part Trust Line -->
                                <div class="cand-trust-line">
                                    <span class="cand-trust-item"><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($locationText) ?></span>
                                    <span class="cand-trust-sep">|</span>
                                    <span class="cand-trust-item"><svg aria-hidden="true"><use href="#i-file-text"/></svg> <?= esc($cvFreshness) ?></span>
                                    <span class="cand-trust-sep">|</span>
                                    <span class="cand-trust-item cand-complete-tag"><svg aria-hidden="true"><use href="#i-check-circle"/></svg> <?= esc($completenessText) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- JobberRecruit Key Criteria Data Bar -->
                        <div class="cand-data-row">
                            <span class="cand-data-item"><svg aria-hidden="true"><use href="#i-grad"/></svg> <?= esc($c->education_level ?? 'Graduate') ?> · <?= esc($c->experience_years ?? 0) ?> yrs exp</span>
                            <span class="cand-data-item"><svg aria-hidden="true"><use href="#i-clock"/></svg> Available: <b><?= esc($c->availability ?? 'Immediate') ?></b></span>
                            <?php if (!empty($c->desired_salary) && (float)$c->desired_salary > 0): ?>
                                <span class="cand-data-item cand-salary-item"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Expects: <b>₦<?= number_format((float)$c->desired_salary) ?> / mo</b></span>
                            <?php endif; ?>
                        </div>

                        <!-- Skills List (up to 6 readable chips) -->
                        <?php if (!empty($skillsSlice)): ?>
                            <div class="cand-skills-row">
                                <?php foreach ($skillsSlice as $s): ?>
                                    <span class="cand-skill-chip"><?= esc($s) ?></span>
                                <?php endforeach; ?>
                                <?php if (count($skillsList) > 6): ?>
                                    <span class="cand-skill-more">+<?= count($skillsList) - 6 ?> more</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Action Footer (View/Unlock + Save + Message) -->
                        <div class="cand-card-foot">
                            <?php if ($isUnlocked): ?>
                                <a href="<?= base_url('employer/candidates/view/' . $c->id) ?>" class="emp-btn emp-btn-primary cand-btn-view">
                                    View Profile
                                </a>
                            <?php else: ?>
                                <button type="button" class="emp-btn emp-btn-primary cand-btn-view" data-unlock-id="<?= $c->id ?>" data-unlock-name="<?= esc($c->full_name) ?>">
                                    Unlock <span class="unlock-price">₦5,000</span>
                                </button>
                            <?php endif; ?>

                            <button type="button" class="cand-btn-save <?= $isSaved ? 'is-saved' : '' ?>" data-candidate-id="<?= $c->id ?>" onclick="toggleSaveCandidate(<?= $c->id ?>, this)" title="<?= $isSaved ? 'Remove from shortlist' : 'Save to shortlist' ?>">
                                <svg aria-hidden="true"><use href="<?= $isSaved ? '#i-check' : '#i-bookmark' ?>"/></svg>
                                <span class="save-txt"><?= $isSaved ? 'Saved' : 'Save' ?></span>
                            </button>

                            <button type="button" class="cand-btn-msg" onclick="startMessage(<?= $c->id ?>)" aria-label="Message <?= esc($c->full_name) ?> (free)" title="Message candidate (free)">
                                <svg aria-hidden="true"><use href="#i-chat"/></svg>
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>

                <div class="pager" style="margin-top:10px;">
                    <span>Showing results</span>
                    <div class="pager-nav">
                        <?= $pager->links('default', 'employer_pagination') ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card empty" style="padding:40px 20px;text-align:center;">
                <div class="empty-ic"><svg aria-hidden="true"><use href="#i-search"/></svg></div>
                <h3>No Candidates Found</h3>
                <p>We couldn't find any candidates matching your criteria. Try adjusting your filters or search keyword.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- UNLOCK MODAL -->
<div class="modal-scrim" id="unlock-scrim">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="unlock-title">
        <div class="modal-head">
            <span class="modal-title" id="unlock-title"><svg aria-hidden="true"><use href="#i-shield"/></svg> Unlock Candidate Details</span>
            <button class="modal-close" id="unlock-close" aria-label="Close dialog"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
        </div>
        <div class="modal-body">
            <p style="font-size:.86rem">Unlock <b id="unlock-name">this candidate</b> to get:</p>
            <ul class="unlock-list">
                <li><svg aria-hidden="true"><use href="#i-phone"/></svg> Direct phone number</li>
                <li><svg aria-hidden="true"><use href="#i-mail"/></svg> Email address</li>
                <li><svg aria-hidden="true"><use href="#i-download"/></svg> Full CV / resume download</li>
            </ul>
            <div class="unlock-total"><span>One-time unlock fee</span><b>₦5,000</b></div>
            
            <?php if ($walletBalanceValue < 5000): ?>
                <div class="notice notice--warn" id="unlock-warn" style="margin-top:12px">
                    <svg aria-hidden="true"><use href="#i-wallet"/></svg>
                    <span>Your wallet balance is <b><?= esc($walletBalanceFormatted) ?></b> — fund your wallet to unlock this candidate.</span>
                </div>
            <?php endif; ?>
            <p style="font-size:.72rem;color:var(--muted);margin-top:12px">The fee is deducted from your wallet. Unlocks are one-time per candidate, permanent for your account, and non-refundable. Messaging this candidate remains free without unlocking.</p>
        </div>
        <div class="modal-foot" style="flex-direction: column; gap: 10px; align-items: stretch; width: 100%;">
            <div style="display: flex; gap: 8px; justify-content: flex-end; width: 100%;">
                <button class="emp-btn emp-btn-outline" id="unlock-cancel" style="flex: 1;">Cancel</button>
                <?php if ($walletBalanceValue >= 5000): ?>
                    <button class="emp-btn emp-btn-primary" id="unlock-confirm" style="flex: 2;"><svg aria-hidden="true"><use href="#i-wallet"/></svg> Use Wallet (₦5,000)</button>
                <?php endif; ?>
            </div>
            <button class="emp-btn emp-btn-accent" id="unlock-paystack-btn" style="width: 100%;"><svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
/* Mobile filters drawer toggle - iOS Safari & Android hardened */
(function() {
    var f = document.getElementById('filters'),
        o = document.getElementById('f-open'),
        c = document.getElementById('f-close'),
        sc = document.getElementById('emp-scrim'),
        applyBtn = document.getElementById('f-apply-btn');
        
    if (!f) return;

    function openDrawer(e) {
        if (e) {
            if (e.cancelable && e.type !== 'touchstart') e.preventDefault();
            e.stopPropagation();
        }
        if (!window.matchMedia('(max-width: 1024px)').matches) return;

        f.classList.add('open');
        if (sc) {
            sc.removeAttribute('hidden');
            sc.hidden = false;
            requestAnimationFrame(function() {
                if (f.classList.contains('open')) sc.classList.add('show');
            });
        }
        if (o) o.setAttribute('aria-expanded', 'true');
        document.documentElement.classList.add('menu-open');
        document.body.classList.add('menu-open');
    }
    
    function closeDrawer(e) {
        if (e) {
            if (e.cancelable && e.type !== 'touchstart') e.preventDefault();
            e.stopPropagation();
        }

        f.classList.remove('open');
        if (o) o.setAttribute('aria-expanded', 'false');

        var sidebar = document.getElementById('emp-sidebar');
        var sidebarOpen = sidebar && sidebar.classList.contains('open');

        if (!sidebarOpen) {
            document.documentElement.classList.remove('menu-open');
            document.body.classList.remove('menu-open');
            if (sc) {
                sc.classList.remove('show');
                setTimeout(function() {
                    if (!f.classList.contains('open') && (!sidebar || !sidebar.classList.contains('open'))) {
                        sc.setAttribute('hidden', '');
                        sc.hidden = true;
                    }
                }, 280);
            }
        }
    }

    // Expose globally so layout scrim/sidebar handlers can cleanly dismiss filters
    window.closeCandidateFilters = closeDrawer;
    window.openCandidateFilters = openDrawer;
    
    if (o) {
        o.addEventListener('click', openDrawer);
    }
    if (c) {
        c.addEventListener('click', closeDrawer);
    }
    if (applyBtn) {
        applyBtn.addEventListener('click', closeDrawer);
    }
    if (sc) {
        sc.addEventListener('click', function(e) {
            if (f.classList.contains('open')) closeDrawer(e);
        });
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && f.classList.contains('open')) closeDrawer(e);
    });
    // Safari can restore the open drawer and scroll lock from its back/forward cache.
    window.addEventListener('pageshow', function() { closeDrawer(); });
    window.matchMedia('(max-width: 1024px)').addEventListener('change', function() {
        closeDrawer();
    });
    closeDrawer();
})();

function showToast(type, msg) {
    if (typeof toastr !== 'undefined' && toastr[type]) {
        toastr[type](msg);
    } else {
        alert(msg);
    }
}

function getCsrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '<?= csrf_hash() ?>';
}

/* Unlock candidate paywall modal & API request handler */
(function() {
    var scrim = document.getElementById('unlock-scrim'),
        nameEl = document.getElementById('unlock-name'),
        closeB = document.getElementById('unlock-close'),
        cancelB = document.getElementById('unlock-cancel'),
        confirmB = document.getElementById('unlock-confirm'),
        lastFocusedElement = null,
        currentCandidateId = null;

    window.openUnlockModal = function(id, name) {
        currentCandidateId = id;
        nameEl.textContent = name;
        lastFocusedElement = document.activeElement;
        scrim.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    function close() {
        scrim.classList.remove('show');
        document.body.style.overflow = '';
        if (lastFocusedElement) lastFocusedElement.focus();
    }

    document.querySelectorAll('[data-unlock-id]').forEach(function(b) {
        b.addEventListener('click', function() {
            window.openUnlockModal(b.getAttribute('data-unlock-id'), b.getAttribute('data-unlock-name'));
        });
    });

    if (closeB) closeB.addEventListener('click', close);
    if (cancelB) cancelB.addEventListener('click', close);
    scrim.addEventListener('click', function(e) {
        if (e.target === scrim) close();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && scrim.classList.contains('show')) close();
    });

    if (confirmB) {
        confirmB.addEventListener('click', function() {
            confirmB.disabled = true;
            confirmB.textContent = 'Unlocking...';
            
            var formData = new FormData();
            formData.append('candidate_id', currentCandidateId);
            formData.append('<?= csrf_token() ?>', getCsrfToken());

            fetch('<?= base_url("employer/candidates/unlock") ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success) {
                    location.reload();
                } else {
                    showToast('error', res.message || 'Failed to unlock candidate.');
                    confirmB.disabled = false;
                    confirmB.innerHTML = '<svg aria-hidden="true"><use href="#i-wallet"/></svg> Confirm Unlock (₦5,000)';
                }
            })
            .catch(function(err) {
                showToast('error', 'An error occurred. Please try again.');
                confirmB.disabled = false;
                confirmB.innerHTML = '<svg aria-hidden="true"><use href="#i-wallet"/></svg> Confirm Unlock (₦5,000)';
            });
        });
    }

    var paystackB = document.getElementById('unlock-paystack-btn');
    if (paystackB) {
        paystackB.addEventListener('click', function() {
            paystackB.disabled = true;
            paystackB.textContent = 'Processing...';

            fetch("<?= base_url('employer/initiate-payment') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({
                    type: 'unlock',
                    candidate_id: currentCandidateId,
                    email: '<?= esc($user->email ?? '') ?>',
                    full_name: '<?= esc($employer->company_name ?? '') ?>'
                })
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.paystack) {
                    let handler = PaystackPop.setup({
                        key: res.paystack,
                        email: res.email,
                        amount: res.amount,
                        ref: res.reference,
                        channels: [res.method || 'card'],
                        metadata: res.metadata,
                        callback: function(response) {
                            // Verify payment via Ajax
                            var verifyData = new FormData();
                            verifyData.append('reference', response.reference);
                            verifyData.append('candidate_id', currentCandidateId);
                            verifyData.append('<?= csrf_token() ?>', getCsrfToken());

                            fetch("<?= base_url('employer/candidates/unlock-verify') ?>", {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': getCsrfToken()
                                },
                                body: verifyData
                            })
                            .then(function(vr) { return vr.json(); })
                            .then(function(vres) {
                                if (vres.success) {
                                    location.reload();
                                } else {
                                    showToast('error', vres.message || 'Payment verification failed.');
                                    paystackB.disabled = false;
                                    paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                                }
                            })
                            .catch(function() {
                                showToast('error', 'Error verifying transaction.');
                                paystackB.disabled = false;
                                paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                            });
                        },
                        onClose: function() {
                            paystackB.disabled = false;
                            paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                        }
                    });
                    handler.openIframe();
                } else {
                    showToast('error', res.message || 'Failed to initialize payment.');
                    paystackB.disabled = false;
                    paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
                }
            })
            .catch(function(err) {
                showToast('error', 'Connection error.');
                paystackB.disabled = false;
                paystackB.innerHTML = '<svg aria-hidden="true"><use href="#i-card"/></svg> Pay with Paystack (₦5,000)';
            });
        });
    }
})();

/* Start free conversation with candidate */
function startMessage(candidateId) {
    var formData = new FormData();
    formData.append('seeker_id', candidateId);
    formData.append('<?= csrf_token() ?>', getCsrfToken());

    fetch('<?= base_url("employer/messages/start") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success && res.redirect) {
            window.location.href = res.redirect;
        } else {
            showToast('error', res.message || 'Failed to start conversation');
        }
    })
    .catch(function(err) {
        showToast('error', 'Error starting conversation');
        console.error(err);
    });
}

/* Toggle save / shortlist candidate (MyJobMag 1-tap pattern) */
function toggleSaveCandidate(candidateId, btn) {
    if (!btn) return;
    btn.disabled = true;

    var formData = new FormData();
    formData.append('candidate_id', candidateId);
    formData.append('<?= csrf_token() ?>', getCsrfToken());

    fetch('<?= base_url("employer/candidates/toggle-save") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        btn.disabled = false;
        if (res.success) {
            var isSaved = (res.saved !== undefined) ? res.saved : res.is_saved;
            var iconSvg = btn.querySelector('svg use');
            var txtSpan = btn.querySelector('.save-txt');
            if (isSaved) {
                btn.classList.add('is-saved');
                btn.title = 'Remove from shortlist';
                if (iconSvg) iconSvg.setAttribute('href', '#i-check');
                if (txtSpan) txtSpan.textContent = 'Saved';
            } else {
                btn.classList.remove('is-saved');
                btn.title = 'Save to shortlist';
                if (iconSvg) iconSvg.setAttribute('href', '#i-bookmark');
                if (txtSpan) txtSpan.textContent = 'Save';
            }
            showToast('success', res.message || (isSaved ? 'Candidate shortlisted' : 'Removed from shortlist'));
        } else {
            showToast('error', res.message || 'Unable to update shortlist');
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        showToast('error', 'Network error updating shortlist');
        console.error(err);
    });
}
</script>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/candidates') ?>?view=favorites" class="emp-btn emp-btn-outline"><svg aria-hidden="true"><use href="#i-star"/></svg> Favorites</a>
<a href="<?= base_url('employer/candidates') ?>" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-search-user"/></svg> Search</a>
<?= $this->endSection() ?>
