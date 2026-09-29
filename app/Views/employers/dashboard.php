<?php $page_title = ''; ?>
<?= $this->extend('layouts/employer') ?>



<?= $this->section('content') ?>
<?php
// Resolve Time of Day Greeting
$hour = (int) date('G');
if ($hour < 12) {
    $greeting = 'Good morning';
} elseif ($hour < 17) {
    $greeting = 'Good afternoon';
} else {
    $greeting = 'Good evening';
}

// SVG Charts - Server-Side Calculations
$jobsMax = !empty($jobsChart) ? max($jobsChart) : 0;
$jobsLimit = $jobsMax > 0 ? $jobsMax : 1;

$appsMax = !empty($appsChart) ? max($appsChart) : 0;
$appsLimit = $appsMax > 0 ? $appsMax : 1;

// Pipeline metrics normalization
$pipeJobs = (int) ($pipeline['posted'] ?? $pipeline['jobs'] ?? $pipeline['jobs_posted'] ?? $totalJobs ?? 0);
$pipeApplicants = (int) ($pipeline['applicants'] ?? $pipeline['applications'] ?? $totalApplicants ?? 0);
$pipeShortlisted = (int) ($pipeline['shortlisted'] ?? 0);
$pipeHired = (int) ($pipeline['hired'] ?? $totalHires ?? 0);

$maxPipeline = max($pipeJobs, $pipeApplicants, $pipeShortlisted, $pipeHired, 1);
$widthJobs = $pipeJobs > 0 ? max(2, min(100, round(($pipeJobs / $maxPipeline) * 100))) : 0;
$widthApplicants = $pipeApplicants > 0 ? max(2, min(100, round(($pipeApplicants / $maxPipeline) * 100))) : 0;
$widthShortlisted = $pipeShortlisted > 0 ? max(2, min(100, round(($pipeShortlisted / $maxPipeline) * 100))) : 0;
$widthHired = $pipeHired > 0 ? max(2, min(100, round(($pipeHired / $maxPipeline) * 100))) : 0;

// Profile Completion dashoffset
$profilePct = (int) ($profileCompletion ?? 75);
$dashOffset = 264 * (1 - ($profilePct / 100));

// Profile Checklist dynamic resolver
$taskDetails = !empty($employer->company_name) && (!empty($employer->description) || !empty($employer->company_address) || !empty($employer->website));
$taskContact = !empty($employer->contact_email) || !empty($employer->contact_phone) || !empty($employer->company_email) || !empty($employer->company_phone);
$taskJob = $totalJobs > 0;
$taskLogo = !empty($employer->logo);
?>

<!-- greeting -->
<div class="greet-row">
  <div class="greet">
    <h1><?= esc($greeting) ?>, <?= esc($employer->company_name ?? 'Employer') ?> 👋</h1>
    <p>You have <b><?= number_format($totalApplicants) ?> new application<?= $totalApplicants == 1 ? '' : 's' ?></b> waiting for review today.</p>
  </div>
  <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
    <a href="https://wa.me/2349014808902?text=Hello%20JobberRecruit%20Support%2C%20I%20am%20an%20employer%20and%20need%20assistance." target="_blank" rel="noopener noreferrer" class="emp-btn emp-btn-sm" style="background:#25D366;color:#ffffff !important;border:none;border-radius:24px;padding:8px 16px;font-weight:700;display:inline-flex;align-items:center;gap:8px;box-shadow:0 3px 10px rgba(37,211,102,0.35);text-decoration:none;" title="Chat directly with official JobberRecruit Support on WhatsApp">
      <svg aria-hidden="true" style="width:18px;height:18px;fill:currentColor;"><use href="#i-whatsapp"/></svg> Contact Us on WhatsApp
    </a>
    <span class="greet-date"><svg aria-hidden="true"><use href="#i-calendar"/></svg> <?= date('l, d F Y') ?></span>
  </div>
</div>

<!-- CAC Document verification alert -->
<?php if (isset($hasCACDocument) && !$hasCACDocument): ?>
  <div class="notice notice--info d-flex align-items-center mb-4" style="background: var(--brand-light); border: 1px solid var(--border); padding: 15px; border-radius: var(--radius);" role="alert">
      <svg aria-hidden="true" style="width: 20px; height: 20px; margin-right: 10px; color: var(--brand); flex-shrink: 0;"><use href="#i-bulb"/></svg>
      <div class="text-main">
          <strong>Recommendation:</strong> Upload your CAC certificate to get a verified badge and increase trust with job seekers.
          <a href="<?= base_url('employer/profile/upload-document') ?>" class="alert-link text-primary text-decoration-underline ms-2" style="color: var(--brand); font-weight: 600;">Upload now</a>
      </div>
  </div>
<?php endif; ?>

<!-- AI recruiter hero (signature) -->
<section class="ai-hero" aria-labelledby="ai-title">
  <div class="ai-hero-grid">
    <div>
      <span class="ai-badge"><span class="pulse" aria-hidden="true"></span> JobberRecruit AI Recruiter · Active</span>
      <h2 id="ai-title">Find the perfect match, <span>faster.</span></h2>
      <a href="<?= base_url('employer/candidates') ?>" class="ai-sub text-white text-decoration-none" style="display:inline-flex; align-items:center; gap:8px; cursor:pointer; color:#fff;" title="View matching candidate profiles">
        <span class="ai-avatars" aria-hidden="true"><span>AO</span><span>CN</span><span>FE</span><span class="more">+1</span></span>
        <span><b style="text-decoration:underline;"><?= number_format($matchingCandidatesCount ?? 5) ?> new matching profile<?= ($matchingCandidatesCount ?? 5) == 1 ? '' : 's' ?></b> found for your open roles today &rarr;</span>
      </a>
    </div>
    <div class="ai-actions">
      <!-- ORANGE = the single conversion CTA on this page -->
      <a href="<?= base_url('employer/jobs/create') ?>" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-plus"/></svg> Post New Job</a>
      <a href="<?= base_url('employer/applications') ?>" class="emp-btn emp-btn-ghost-w"><svg aria-hidden="true"><use href="#i-users"/></svg> View Applicants</a>
      <a href="<?= base_url('recruitment') ?>" class="emp-btn emp-btn-ghost-w"><svg aria-hidden="true"><use href="#i-spark"/></svg> Let Us Help You Hire</a>
    </div>
  </div>
</section>

<!-- KPI stats -->
<section class="stats" aria-label="Key statistics">
  <div class="stat stat--jobs">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span>
      <span class="trend trend--up"><svg aria-hidden="true"><use href="#i-arrow-up"/></svg> +<?= max(0, (int)($totalJobs ?? 0)) ?> total</span>
    </div>
    <div class="stat-num"><?= number_format($totalJobs ?? 0) ?></div>
    <div class="stat-lbl">Total Jobs Posted</div>
    <svg class="stat-spark" viewBox="0 0 74 26" aria-hidden="true"><polyline points="2,20 14,17 26,18 38,12 50,13 62,7 72,4" fill="none" stroke="#0861A9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </div>
  <div class="stat stat--active">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-spark"/></svg></span>
      <span class="trend trend--flat"><?= ($activeJobs ?? 0) === ($totalJobs ?? 0) && ($totalJobs ?? 0) > 0 ? 'All live' : (($activeJobs ?? 0) . ' active') ?></span>
    </div>
    <div class="stat-num"><?= number_format($activeJobs ?? 0) ?></div>
    <div class="stat-lbl">Active Jobs</div>
    <svg class="stat-spark" viewBox="0 0 74 26" aria-hidden="true"><polyline points="2,16 14,16 26,12 38,12 50,8 62,8 72,5" fill="none" stroke="#ED9020" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </div>
  <div class="stat stat--apps">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-users"/></svg></span>
      <span class="trend trend--up"><svg aria-hidden="true"><use href="#i-arrow-up"/></svg> +<?= max(0, (int)($totalApplicants ?? 0)) ?> applicants</span>
    </div>
    <div class="stat-num"><?= number_format($totalApplicants ?? 0) ?></div>
    <div class="stat-lbl">Total Applicants</div>
    <svg class="stat-spark" viewBox="0 0 74 26" aria-hidden="true"><polyline points="2,22 14,22 26,18 38,19 50,14 62,10 72,6" fill="none" stroke="#064A85" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
  </div>
  <div class="stat stat--hires">
    <div class="stat-top">
      <span class="stat-ic"><svg aria-hidden="true"><use href="#i-user-check"/></svg></span>
      <span class="trend trend--flat"><?= ($totalHires ?? 0) > 0 ? ($totalHires . ' hired') : 'Awaiting first hire' ?></span>
    </div>
    <div class="stat-num"><?= number_format($totalHires ?? 0) ?></div>
    <div class="stat-lbl">Total Hires</div>
  </div>
</section>

<!-- charts -->
<section class="charts" aria-label="Seven day activity">
  <!-- Jobs Posted Chart -->
  <div class="card">
    <div class="card-head">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-chart"/></svg> Jobs Posted <span style="font-weight:500;color:var(--muted);font-size:.76rem">· Last 7 days</span></span>
      <span class="chart-legend"><i style="background:var(--brand)"></i> Jobs</span>
    </div>
    <div class="card-body">
      <svg class="bar-chart" viewBox="0 0 560 140" role="img" aria-label="Bar chart: jobs posted per day for the last 7 days.">
        <line class="grid-line" x1="30" y1="8"  x2="552" y2="8"/>
        <line class="grid-line" x1="30" y1="56" x2="552" y2="56"/>
        <line class="grid-line" x1="30" y1="104" x2="552" y2="104"/>
        <text class="axis-lbl" x="24" y="12"  text-anchor="end"><?= esc($jobsMax) ?></text>
        <text class="axis-lbl" x="24" y="60"  text-anchor="end"><?= esc(round($jobsMax / 2, 1)) ?></text>
        <text class="axis-lbl" x="24" y="108" text-anchor="end">0</text>
        
        <?php
        $i = 0;
        foreach ($jobsChart as $day => $val):
            $x = 44 + ($i * 74);
            if ($val > 0):
                $h = (int) round(($val / $jobsLimit) * 96);
                $y = 104 - $h;
                ?>
                <rect class="bar" x="<?= $x ?>" y="<?= $y ?>" width="34" height="<?= $h ?>" fill="#0861A9">
                  <title><?= esc($day) ?> · <?= $val ?> <?= $val == 1 ? 'job' : 'jobs' ?></title>
                </rect>
                <text class="val-lbl" x="<?= $x + 17 ?>" y="<?= $y - 6 ?>" text-anchor="middle"><?= $val ?></text>
            <?php else: ?>
                <rect class="bar bar--zero" x="<?= $x ?>" y="100" width="34" height="4">
                  <title><?= esc($day) ?> · 0 jobs</title>
                </rect>
            <?php endif;
            $i++;
        endforeach;
        ?>
        
        <?php
        $i = 0;
        foreach ($jobsChart as $day => $val):
            $xLabel = 61 + ($i * 74);
            $shortDay = explode(' ', $day)[0] ?? $day;
            ?>
            <text class="axis-lbl" x="<?= $xLabel ?>" y="124" text-anchor="middle"><?= esc($shortDay) ?></text>
            <?php
            $i++;
        endforeach;
        ?>
      </svg>
    </div>
  </div>

  <!-- Applications Received Chart -->
  <div class="card">
    <div class="card-head">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-doc"/></svg> Applications Received <span style="font-weight:500;color:var(--muted);font-size:.76rem">· Last 7 days</span></span>
      <span class="chart-legend"><i style="background:var(--accent)"></i> Applications</span>
    </div>
    <div class="card-body">
      <svg class="bar-chart" viewBox="0 0 560 140" role="img" aria-label="Bar chart: applications received per day for the last 7 days.">
        <line class="grid-line" x1="30" y1="8"  x2="552" y2="8"/>
        <line class="grid-line" x1="30" y1="56" x2="552" y2="56"/>
        <line class="grid-line" x1="30" y1="104" x2="552" y2="104"/>
        <text class="axis-lbl" x="24" y="12"  text-anchor="end"><?= esc($appsMax) ?></text>
        <text class="axis-lbl" x="24" y="60"  text-anchor="end"><?= esc(round($appsMax / 2, 1)) ?></text>
        <text class="axis-lbl" x="24" y="108" text-anchor="end">0</text>
        
        <?php
        $i = 0;
        foreach ($appsChart as $day => $val):
            $x = 44 + ($i * 74);
            if ($val > 0):
                $h = (int) round(($val / $appsLimit) * 96);
                $y = 104 - $h;
                ?>
                <rect class="bar" x="<?= $x ?>" y="<?= $y ?>" width="34" height="<?= $h ?>" fill="#ED9020">
                  <title><?= esc($day) ?> · <?= $val ?> applications</title>
                </rect>
                <text class="val-lbl" x="<?= $x + 17 ?>" y="<?= $y - 6 ?>" text-anchor="middle"><?= $val ?></text>
            <?php else: ?>
                <rect class="bar bar--zero" x="<?= $x ?>" y="100" width="34" height="4">
                  <title><?= esc($day) ?> · 0</title>
                </rect>
            <?php endif;
            $i++;
        endforeach;
        ?>

        <?php
        $i = 0;
        foreach ($appsChart as $day => $val):
            $xLabel = 61 + ($i * 74);
            $shortDay = explode(' ', $day)[0] ?? $day;
            ?>
            <text class="axis-lbl" x="<?= $xLabel ?>" y="124" text-anchor="middle"><?= esc($shortDay) ?></text>
            <?php
            $i++;
        endforeach;
        ?>
      </svg>
      <p class="chart-note"><svg aria-hidden="true"><use href="#i-bulb"/></svg> Quiet week? Featured job posts get up to 5× more applications. <a href="<?= base_url('employer/pricing') ?>">Boost a job</a></p>
    </div>
  </div>
</section>

<!-- insights + closing soon -->
<section class="insight-row" aria-label="Job performance and expiry">
  <!-- Job Performance Insights -->
  <div class="card">
    <div class="card-head">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-chart"/></svg> Job Performance Insights</span>
      <a href="<?= base_url('employer/jobs') ?>" class="card-link">All jobs <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
    </div>
    <div class="card-body">
      <?php if (empty($jobInsights)): ?>
        <p class="text-muted text-center py-4 mb-0">No performance insights available.</p>
      <?php else: ?>
        <?php foreach ($jobInsights as $insight): ?>
          <?php
          $views = (int) ($insight->views ?? 0);
          $apps = (int) ($insight->app_count ?? $insight->applications ?? $insight->applicants_count ?? 0);
          $rate = $views > 0 ? ($apps / $views) * 100 : 0;
          $rateFormatted = number_format($rate, 1) . '%';
          
          $barWidth = $views > 0 ? min(round(($rate / 5) * 100), 100) : 2;
          $isGood = $rate >= 1.2;
          ?>
          <div class="ins-item">
            <div class="ins-title" title="<?= esc($insight->title ?? '') ?>"><?= esc($insight->title ?? '') ?></div>
            <div class="ins-nums"><b><?= number_format($views) ?></b> views &middot; <b><?= number_format($apps) ?></b> applications</div>
            <div class="ins-bar-row">
              <div class="ins-track">
                <div class="ins-fill" style="width: <?= $barWidth ?>%; background: <?= $isGood ? 'linear-gradient(90deg,#16a34a,#4ade80)' : 'var(--border)' ?>;"></div>
              </div>
              <span class="ins-rate"><?= $rateFormatted ?></span>
            </div>
            <div class="ins-hint">
              <?php if ($isGood): ?>
                <span class="ins-tip ins-tip--good"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Converting above the platform benchmark — no action needed</span>
              <?php else: ?>
                <span class="ins-tip"><svg aria-hidden="true"><use href="#i-bulb"/></svg> High views, lower applications — add a salary range or clarify benefits</span>
                <a href="<?= base_url('employer/jobs/edit/' . ($insight->id ?? '')) ?>" class="emp-btn emp-btn-outline emp-btn-sm">Review job</a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
      <p class="ins-bench"><i aria-hidden="true"></i> Benchmark: similar jobs convert about 12 applications per 1,000 views (1.2%)</p>
    </div>
  </div>

  <!-- Closing Soon -->
  <div class="card">
    <div class="card-head">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-clock"/></svg> Closing Soon</span>
      <span class="pill pill--pending"><?= count($closingSoon ?? []) ?> <?= count($closingSoon ?? []) === 1 ? 'job' : 'jobs' ?></span>
    </div>
    <div class="card-body">
      <?php if (empty($closingSoon)): ?>
        <p class="text-muted text-center py-4 mb-0">No jobs closing soon.</p>
      <?php else: ?>
        <?php foreach ($closingSoon as $job): ?>
          <?php
          $closingField = $job->closing_date ?? $job->expires_at ?? $job->deadline ?? null;
          $closingDate = $closingField ? strtotime($closingField) : time();
          $daysLeft = round(($closingDate - time()) / 86400);
          $isCritical = $daysLeft <= 7;
          $jobId = $job->id ?? '';
          ?>
          <div class="close-item">
            <span class="close-ic <?= $isCritical ? 'close-ic--warn' : 'close-ic--ok' ?>" aria-hidden="true">
              <svg><use href="#i-clock"/></svg>
            </span>
            <div class="close-info">
              <div class="close-title" title="<?= esc($job->title ?? '') ?>"><?= esc($job->title ?? '') ?></div>
              <div class="close-sub">
                Closes <?= date('d M', $closingDate) ?> &middot; 
                <?php if ($daysLeft > 0): ?>
                  <b>in <?= $daysLeft ?> <?= $daysLeft == 1 ? 'day' : 'days' ?></b>
                <?php else: ?>
                  <b>closes today</b>
                <?php endif; ?>
              </div>
            </div>
            <form action="<?= base_url('employer/jobs/extend/' . $jobId) ?>" method="post" style="display:inline" onsubmit="return confirm('Extend this job by 30 days?');">
              <?= csrf_field() ?>
              <input type="hidden" name="days" value="30">
              <button type="submit" class="emp-btn emp-btn-outline emp-btn-sm">Extend</button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
      <p style="font-size:.72rem;color:var(--muted);margin-top:12px">Jobs expire automatically at their closing date. Extending keeps your listing live without reposting.</p>
    </div>
  </div>
</section>

<!-- three-column insight row -->
<section class="tri" aria-label="Recruitment insights">
  <!-- Top Job Categories -->
  <div class="card">
    <div class="card-head">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-tag"/></svg> Top Job Categories</span>
    </div>
    <div class="card-body">
      <?php if (empty($categoryCounts)): ?>
        <p class="text-muted text-center py-4 mb-0">No categories recorded.</p>
      <?php else: ?>
        <ul class="cat-list">
          <?php
          $catColors = ['#0861A9', '#ED9020', '#064A85', '#1d6fb8', '#C8770E', '#0A2F57', '#5b6577'];
          $totalJobsCount = max(1, $totalJobs);
          $i = 0;
          foreach ($categoryCounts as $cat):
            $catName = $cat->name ?? 'Other';
            $catJobs = (int) ($cat->total ?? 0);
            $catShare = min(100, round(($catJobs / $totalJobsCount) * 100));
            $color = $catColors[$i % count($catColors)];
            $i++;
            ?>
            <li class="cat-item">
              <div class="cat-row">
                <span class="cat-name">
                  <i class="cat-dot" style="background: <?= $color ?>"></i>
                  <span><?= esc($catName) ?></span>
                </span>
                <span class="cat-jobs"><?= $catJobs ?> <?= $catJobs == 1 ? 'job' : 'jobs' ?></span>
              </div>
              <div class="cat-track">
                <div class="cat-fill" style="width: <?= $catShare ?>%; background: <?= $color ?>"></div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>

  <!-- Recent Applications -->
  <div class="card">
    <div class="card-head">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-clock"/></svg> Recent Applications</span>
      <a href="<?= base_url('employer/applications') ?>" class="card-link">View all <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
    </div>
    <div class="card-body">
      <?php if (empty($recentApplications)): ?>
        <p class="text-muted text-center py-4 mb-0">No applications received yet.</p>
      <?php else: ?>
        <ul class="app-list">
          <?php
          foreach ($recentApplications as $app):
            $title = $app->job_title ?? 'Job';
            $words = explode(' ', preg_replace('/\s+/', ' ', trim($title)));
            $initials = count($words) >= 2
                ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                : strtoupper(substr($title, 0, 2));
                
            $status = strtolower($app->status ?? 'pending');
            $pillClass = 'pill--pending';
            if ($status === 'reviewed') {
                $pillClass = 'pill--reviewed';
            } elseif ($status === 'shortlisted') {
                $pillClass = 'pill--shortlisted';
            } elseif (in_array($status, ['hired', 'open', 'active', 'success'])) {
                $pillClass = 'pill--hired';
            } elseif (in_array($status, ['rejected', 'closed', 'expired'])) {
                $pillClass = 'pill--rejected';
            }
            ?>
            <li class="app-item">
              <span class="app-ava" aria-hidden="true"><?= esc($initials) ?></span>
              <div class="app-info">
                <div class="app-name"><?= esc($title) ?></div>
                <div class="app-meta">
                  <svg aria-hidden="true"><use href="#i-calendar"/></svg>
                  Applied <?= date('d M Y', strtotime($app->created_at ?? 'now')) ?>
                </div>
              </div>
              <span class="pill <?= $pillClass ?>"><?= ucfirst($status) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>

  <!-- Recently Posted Jobs -->
  <div class="card">
    <div class="card-head">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Recently Posted Jobs</span>
      <a href="<?= base_url('employer/jobs') ?>" class="card-link">View all <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
    </div>
    <div class="card-body">
      <?php if (empty($recentJobs)): ?>
        <p class="text-muted text-center py-4 mb-0">No jobs posted yet.</p>
      <?php else: ?>
        <ul class="job-list">
          <?php
          foreach ($recentJobs as $job):
            $jobId = $job->id ?? '';
            $jobTitle = $job->title ?? '';
            $jobViews = $job->views ?? 0;
            $postedDate = strtotime($job->created_at ?? 'now');
            ?>
            <li class="job-item">
              <span class="job-ic" aria-hidden="true"><svg><use href="#i-briefcase"/></svg></span>
              <div class="job-info">
                <div class="job-title">
                  <a href="<?= base_url('employer/jobs/view/' . $jobId) ?>"><?= esc($jobTitle) ?></a>
                </div>
                <div class="job-sub">
                  <svg aria-hidden="true"><use href="#i-clock"/></svg>
                  Posted <?= date('d M Y', $postedDate) ?>
                </div>
              </div>
              <div class="job-views">
                <b><?= number_format($jobViews) ?></b>
                <i>views</i>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- pipeline + profile completion -->
<section class="duo" aria-label="Pipeline and profile health">
  <!-- Hiring Pipeline -->
  <div class="card">
    <div class="card-head">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-funnel"/></svg> Hiring Pipeline</span>
      <a href="<?= base_url('employer/applications') ?>" class="card-link">Manage <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a>
    </div>
    <div class="card-body">
      <div class="pipe">
        <div class="pipe-stage">
          <span class="pipe-lbl"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Jobs posted</span>
          <div class="pipe-track"><div class="pipe-fill" style="width: <?= $widthJobs ?>%; background: linear-gradient(90deg,#0A2F57,#0861A9)"></div></div>
          <span class="pipe-num"><?= $pipeJobs ?></span>
        </div>
        <div class="pipe-stage">
          <span class="pipe-lbl"><svg aria-hidden="true"><use href="#i-users"/></svg> Applicants</span>
          <div class="pipe-track"><div class="pipe-fill" style="width: <?= $widthApplicants ?>%; background: linear-gradient(90deg,#064A85,#1d6fb8)"></div></div>
          <span class="pipe-num"><?= $pipeApplicants ?></span>
        </div>
        <div class="pipe-stage">
          <span class="pipe-lbl"><svg aria-hidden="true"><use href="#i-doc"/></svg> Shortlisted</span>
          <div class="pipe-track"><div class="pipe-fill" style="width: <?= $widthShortlisted ?>%; background: linear-gradient(90deg,#ED9020,#f0a94d)"></div></div>
          <span class="pipe-num"><?= $pipeShortlisted ?></span>
        </div>
        <div class="pipe-stage">
          <span class="pipe-lbl"><svg aria-hidden="true"><use href="#i-user-check"/></svg> Hired</span>
          <div class="pipe-track"><div class="pipe-fill" style="width: <?= $widthHired ?>%; background: <?= $pipeHired > 0 ? 'linear-gradient(90deg,#16a34a,#4ade80)' : 'var(--border)' ?>;"></div></div>
          <span class="pipe-num"><?= $pipeHired ?></span>
        </div>
      </div>
      <p class="pipe-hint">
        <svg aria-hidden="true"><use href="#i-bulb"/></svg>
        Candidates who hear back within 48 hours are far more likely to accept an offer.
      </p>
    </div>
  </div>

  <!-- Company Profile Strength -->
  <div class="card">
    <div class="card-head" style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-building"/></svg> Company Profile Strength</span>
      <a href="<?= base_url('employer/profile/edit') ?>" class="card-link" style="font-size:.78rem;font-weight:600;color:var(--brand);display:inline-flex;align-items:center;gap:4px;">
        Edit Profile &rarr;
      </a>
    </div>
    <div class="card-body">
      <div class="pf">
        <a href="<?= base_url('employer/profile/edit') ?>" class="pf-ring" role="img" aria-label="Profile <?= $profilePct ?> percent complete — Click to edit profile" title="Click to complete your profile" style="text-decoration:none;cursor:pointer;">
          <svg viewBox="0 0 96 96" aria-hidden="true">
            <circle class="track" cx="48" cy="48" r="42"/>
            <circle class="prog"  cx="48" cy="48" r="42" style="stroke-dashoffset: <?= $dashOffset ?>;"/>
          </svg>
          <span class="pct"><?= $profilePct ?>%</span>
        </a>
        <ul class="pf-tasks">
          <li class="pf-task <?= $taskDetails ? 'done' : 'todo' ?>">
            <svg aria-hidden="true"><use href="#<?= $taskDetails ? 'i-check-c' : 'i-circle' ?>"/></svg>
            <?php if ($taskDetails): ?>
              <span>Company details added</span>
            <?php else: ?>
              <a href="<?= base_url('employer/profile/edit') ?>">Add company details &rarr;</a>
            <?php endif; ?>
          </li>
          <li class="pf-task <?= $taskContact ? 'done' : 'todo' ?>">
            <svg aria-hidden="true"><use href="#<?= $taskContact ? 'i-check-c' : 'i-circle' ?>"/></svg>
            <?php if ($taskContact): ?>
              <span>Contact info verified</span>
            <?php else: ?>
              <a href="<?= base_url('employer/profile/edit') ?>">Provide contact info &rarr;</a>
            <?php endif; ?>
          </li>
          <li class="pf-task <?= $taskJob ? 'done' : 'todo' ?>">
            <svg aria-hidden="true"><use href="#<?= $taskJob ? 'i-check-c' : 'i-circle' ?>"/></svg>
            <?php if ($taskJob): ?>
              <span>First job posted</span>
            <?php else: ?>
              <a href="<?= base_url('employer/jobs/create') ?>">Post your first job &rarr;</a>
            <?php endif; ?>
          </li>
          <li class="pf-task <?= $taskLogo ? 'done' : 'todo' ?>">
            <svg aria-hidden="true"><use href="#<?= $taskLogo ? 'i-check-c' : 'i-circle' ?>"/></svg>
            <?php if ($taskLogo): ?>
              <span>Company logo uploaded</span>
            <?php else: ?>
              <a href="<?= base_url('employer/profile/edit') ?>">Upload company logo &rarr;</a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- Dedicated WhatsApp Support Banner -->
<section class="card" style="border: 1px solid #bbf7d0; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); margin-bottom: 24px; border-radius: var(--radius-lg); padding: 20px 24px;" aria-label="Official WhatsApp Support">
  <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; align-items: center; gap: 16px;">
      <div style="width: 50px; height: 50px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);">
        <svg style="width: 26px; height: 26px; fill: currentColor;" aria-hidden="true"><use href="#i-whatsapp"/></svg>
      </div>
      <div>
        <h3 style="font-size: 1.05rem; font-weight: 800; color: #14532d; margin: 0 0 4px 0; font-family: 'Sora', sans-serif;">Need Help Hiring or Managing Your Jobs?</h3>
        <p style="font-size: 0.85rem; color: #166534; margin: 0; line-height: 1.45;">
          Connect directly with your dedicated JobberRecruit Account Manager on WhatsApp. Fast response times for job posting approvals, applicant screening, and technical support.
        </p>
      </div>
    </div>
    <a href="https://wa.me/2349014808902?text=Hello%20JobberRecruit%20Support%2C%20I%20am%20an%20employer%20and%20need%20assistance." target="_blank" rel="noopener noreferrer" class="emp-btn" style="background: #25D366; color: #ffffff !important; border: none; font-weight: 700; padding: 12px 22px; border-radius: 30px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(37, 211, 102, 0.45); white-space: nowrap;">
      <svg style="width: 20px; height: 20px; fill: currentColor;" aria-hidden="true"><use href="#i-whatsapp"/></svg>
      Chat with Support (+234 901 480 8902)
    </a>
  </div>
</section>

<!-- onboarded hires --><?php
$hiredApps = [];
if (!empty($recentApplications)) {
    foreach ($recentApplications as $app) {
        $status = strtolower($app->status ?? '');
        if ($status === 'hired') {
            $hiredApps[] = $app;
        }
    }
}
?>
<section class="card" aria-label="Recent onboarded hires">
  <div class="card-head">
    <span class="card-title"><svg aria-hidden="true"><use href="#i-user-check"/></svg> Recent Onboarded Hires</span>
  </div>
  <?php if (empty($hiredApps)): ?>
    <div class="empty">
      <span class="empty-ic"><svg aria-hidden="true"><use href="#i-user-check"/></svg></span>
      <h3>No onboarded hires yet</h3>
      <p>Your first hire will appear here. Review your <?= number_format($totalApplicants) ?> pending applications, or let our recruitment team shortlist candidates for you.</p>
      <a href="<?= base_url('employer/applications') ?>" class="emp-btn emp-btn-primary emp-btn-sm"><svg aria-hidden="true"><use href="#i-users"/></svg> Review Applications</a>
    </div>
  <?php else: ?>
    <div class="card-body">
      <ul class="app-list">
        <?php foreach ($hiredApps as $app): ?>
          <?php
          $firstName = $app->first_name ?? '';
          $lastName = $app->last_name ?? '';
          $fullName = trim($firstName . ' ' . $lastName);
          if (empty($fullName)) {
              $fullName = 'Candidate';
          }
          $jobTitle = $app->job_title ?? 'Job';
          ?>
          <li class="app-item">
            <span class="app-ava" aria-hidden="true"><svg aria-hidden="true"><use href="#i-user-check"/></svg></span>
            <div class="app-info">
              <div class="app-name"><?= esc($fullName) ?></div>
              <div class="app-meta">Hired for: <?= esc($jobTitle) ?></div>
            </div>
            <span class="pill pill--hired">Onboarded</span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</section>



<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="<?= base_url('employer/applications') ?>" class="emp-btn emp-btn-outline"><svg aria-hidden="true"><use href="#i-users"/></svg> Applicants</a>
<a href="<?= base_url('employer/jobs/create') ?>" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-plus"/></svg> Post New Job</a>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Page level dynamic scripts can go here if needed -->
<?= $this->endSection() ?>