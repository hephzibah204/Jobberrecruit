<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <div class="greet-row">
      <div class="greet">
        <?php
          $hourOfDay = (int) date('G');
          $greeting  = $hourOfDay < 12 ? 'Good morning' : ($hourOfDay < 17 ? 'Good afternoon' : 'Good evening');
        ?>
        <h1><?= $greeting ?>, <?= esc(explode(' ', $candidate->full_name)[0] ?? $candidate->full_name) ?> 👋</h1>
        <p>You have submitted <span class="text-primary fw-bold"><?= $totalApplications ?></span> applications in total.</p>
      </div>
      <span class="greet-date"><svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-calendar"/></svg> <?= date('l, d F Y') ?></span>
    </div>

    <!-- AI engine hero -->
    <section class="ai-hero" aria-labelledby="ai-title">
      <div class="ai-hero-grid">
        <div>
          <span class="ai-badge"><span class="pulse" aria-hidden="true"></span> JobberRecruit AI Engine · Active</span>
          <h2 id="ai-title">Let's accelerate your <span>career path.</span></h2>
          <p class="ai-sub">You have <?= $recentApplicationsCount ?? 0 ?> new updates and recommendations waiting for your action.</p>
        </div>
        <div class="ai-actions">
          <a href="<?= base_url('jobs') ?>" class="btn btn-accent"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-search"/></svg> Browse Jobs</a>
          <a href="<?= base_url('candidate/applications') ?>" class="btn btn-ghost-w"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg> My Applications</a>
          <?php if (env('feature_ai_career_tools', 'true') == 'true'): ?>
          <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-ghost-w"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg> AI Career Tools</a>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- KPIs stats grid -->
    <section class="stats" aria-label="Your activity" style="display:grid">
      <div class="stat"><div class="stat-top"><span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg></span></div><div class="stat-num"><?= $totalApplications ?></div><div class="stat-lbl">Total Applications</div></div>
      <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)"><div class="stat-top"><span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bookmark"/></svg></span></div><div class="stat-num"><?= $savedJobs ?></div><div class="stat-lbl">Saved Jobs</div></div>
      <div class="stat" style="--st-bar:var(--brand-dark)"><div class="stat-top"><span class="stat-ic"><svg aria-hidden="true" style="width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-eye"/></svg></span></div><div class="stat-num"><?= $jobsViewed ?></div><div class="stat-lbl">Jobs Viewed</div></div>
      <div class="stat" style="--st-bar:var(--success)">
        <div class="stat-top">
          <span class="ring-sm" role="img" aria-label="Profile <?= $profileCompletion ?> percent complete">
            <svg viewBox="0 0 52 52" aria-hidden="true"><circle class="track" cx="26" cy="26" r="22"/><circle class="prog" cx="26" cy="26" r="22" stroke-dashoffset="<?= 138 - (138 * $profileCompletion / 100) ?>"/></svg>
            <span class="pct"><?= $profileCompletion ?>%</span>
          </span>
        </div>
        <div class="stat-num" style="font-size:1.05rem;margin-top:2px"><?= $profileCompletion == 100 ? 'Completed' : '&#8358;500 waiting' ?></div>
        <div class="stat-lbl">Profile Completion</div>
      </div>
    </section>

    <!-- weekly engagement + skill match -->
    <section class="mid" aria-label="Engagement and skill match">
      <div class="card">
        <div class="card-head"><span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-chart"/></svg> Your Week at a Glance</span>
          <span style="font-size:.72rem;color:var(--muted);font-weight:500">Jobs viewed · last 7 days</span></div>
        <div class="card-body">
          <?php
          $weeklyActivity = $weeklyChartData ?? [1, 2, 4, 3, 5, 8, 0];
          $maxVal = max(1, max($weeklyActivity));
          $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
          ?>
          <svg class="bar-chart" viewBox="0 0 560 140" role="img" aria-label="Bar chart of jobs viewed this week.">
            <line class="grid-line" x1="30" y1="8" x2="552" y2="8"/><line class="grid-line" x1="30" y1="56" x2="552" y2="56"/><line class="grid-line" x1="30" y1="104" x2="552" y2="104"/>
            <text class="axis-lbl" x="24" y="12" text-anchor="end"><?= $maxVal ?></text><text class="axis-lbl" x="24" y="60" text-anchor="end"><?= round($maxVal/2) ?></text><text class="axis-lbl" x="24" y="108" text-anchor="end">0</text>
            
            <?php foreach ($weeklyActivity as $i => $val): 
                $h = round($val / $maxVal * 96);
                $y = 104 - $h;
                $x = 44 + ($i * 74);
                $isMax = ($val === max($weeklyActivity) && $val > 0);
            ?>
              <rect x="<?= $x ?>" y="<?= $y ?>" width="34" height="<?= max(4, $h) ?>" rx="5" fill="<?= $isMax ? '#ED9020' : '#0861A9' ?>"><title><?= $days[$i] ?> · <?= $val ?></title></rect>
              <?php if ($isMax): ?>
                <text class="val-lbl" x="<?= $x + 17 ?>" y="<?= $y + 12 ?>" text-anchor="middle" fill="#fff"><?= $val ?></text>
              <?php endif; ?>
            <?php endforeach; ?>
            
            <?php foreach ($days as $i => $day): ?>
              <text class="axis-lbl" x="<?= 61 + ($i * 74) ?>" y="124" text-anchor="middle"><?= $day ?></text>
            <?php endforeach; ?>
          </svg>
          <p style="font-size:.74rem;color:var(--muted);margin-top:10px;display:flex;gap:6px;align-items:center"><svg style="width:13px;height:13px;color:var(--accent-dark);fill:none;stroke:currentColor;stroke-width:2;" aria-hidden="true"><use href="#i-bulb"/></svg> The most active candidates are first in line when new jobs drop.</p>
        </div>
      </div>

      <div class="card">
        <div class="card-head"><span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-zap"/></svg> Skill Match Hub</span></div>
        <div class="card-body">
          <div class="donut-wrap">
            <div class="donut" role="img" aria-label="Overall match rate 85 percent">
              <svg viewBox="0 0 132 132" aria-hidden="true">
                <circle cx="66" cy="66" r="51" stroke="#f5f7fb"/>
                <circle cx="66" cy="66" r="51" stroke="#0861A9" stroke-dasharray="128 320" stroke-dashoffset="0" stroke-linecap="butt"/>
                <circle cx="66" cy="66" r="51" stroke="#16a34a" stroke-dasharray="64 320" stroke-dashoffset="-128"/>
                <circle cx="66" cy="66" r="51" stroke="#ED9020" stroke-dasharray="48 320" stroke-dashoffset="-192"/>
                <circle cx="66" cy="66" r="51" stroke="#8b5cf6" stroke-dasharray="32 320" stroke-dashoffset="-240"/>
              </svg>
              <span class="c"><b>85%</b><i>Match rate</i></span>
            </div>
            <ul class="leg">
              <li><i style="background:#0861A9"></i>Tech skills</li>
              <li><i style="background:#16a34a"></i>Soft skills</li>
              <li><i style="background:#ED9020"></i>Domain knowledge</li>
              <li><i style="background:#8b5cf6"></i>Other matches</li>
            </ul>
          </div>
          <div class="mcat" style="margin-top:18px">
            <?php if (!empty($skillCategories)): ?>
                <?php foreach ($skillCategories as $cat): ?>
                    <div>
                      <div class="mcat-row"><span><?= esc($cat->name) ?></span><b><?= $cat->match ?>%</b></div>
                      <div class="mcat-track"><div class="mcat-fill" style="width:<?= $cat->match ?>%;background:#0861A9"></div></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div>
                  <div class="mcat-row"><span>Software Development</span><b>85%</b></div>
                  <div class="mcat-track"><div class="mcat-fill" style="width:85%;background:#0861A9"></div></div>
                </div>
                <div>
                  <div class="mcat-row"><span>UI/UX Product Design</span><b>75%</b></div>
                  <div class="mcat-track"><div class="mcat-fill" style="width:75%;background:#064A85"></div></div>
                </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- picks · profile tasks · learning -->
    <section class="tri" aria-label="Recommendations and next steps">
      <div class="card">
        <div class="card-head"><span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-check-c"/></svg> Finish Your Profile</span>
          <span class="pill <?= $profileCompletion == 100 ? 'pill--success' : 'pill--pending' ?>"><?= $profileCompletion == 100 ? 'Completed' : 'Incomplete' ?></span></div>
        <div class="card-body">
          <div class="task <?= !empty($candidate->full_name) && !empty($candidate->phone) ? 'done' : 'todo' ?>">
            <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="<?= !empty($candidate->full_name) && !empty($candidate->phone) ? '#i-check-c' : '#i-circle' ?>"/></svg> Personal details added
          </div>
          <div class="task <?= !empty($candidate->resume) ? 'done' : 'todo' ?>">
            <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="<?= !empty($candidate->resume) ? '#i-check-c' : '#i-circle' ?>"/></svg> CV uploaded
          </div>
          <div class="task <?= !empty($candidate->skills) ? 'done' : 'todo' ?>">
            <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="<?= !empty($candidate->skills) ? '#i-check-c' : '#i-circle' ?>"/></svg> Skills &amp; preferences set
          </div>
          <div class="task <?= !empty($candidate->photo) ? 'done' : 'todo' ?>">
            <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="<?= !empty($candidate->photo) ? '#i-check-c' : '#i-circle' ?>"/></svg> 
            <a href="<?= base_url('candidate/profile/edit') ?>">Upload a profile photo</a>
          </div>
          <p style="font-size:.72rem;color:var(--muted);margin-top:10px">&#8358;200 earned at the 60% milestone. Finish all steps to reach 100% and earn <b style="color:var(--accent-dark)">&#8358;500 more</b> — credited straight to your wallet.</p>
        </div>
      </div>

      <div class="card">
        <div class="card-head"><span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-star"/></svg> Today's Picks for You</span>
          <a href="<?= base_url('jobs') ?>" class="card-link">All jobs <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-r"/></svg></a></div>
        <div class="card-body">
          <?php if (empty($recommendedJobs)): ?>
              <p class="text-muted text-center py-4 mb-0">No job matches found for your current profile. Update your preferences to see recommendations.</p>
          <?php else: ?>
              <?php foreach ($recommendedJobs as $job): ?>
                  <div class="pick">
                    <span class="pick-ic" aria-hidden="true"><svg style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-briefcase"/></svg></span>
                    <div class="pick-info">
                      <div class="pick-title"><a href="<?= base_url('job/view/' . $job->id) ?>"><?= esc($job->title) ?></a></div>
                      <div class="pick-sub"><svg aria-hidden="true" style="width:11px;height:11px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-clock"/></svg> Posted <?= date('d M', strtotime($job->created_at)) ?> · <?= esc($job->location) ?></div>
                    </div>
                    <span class="match-badge">85% match</span>
                  </div>
              <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="card">
        <div class="card-head"><span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-book"/></svg> Keep Learning</span>
          <a href="<?= base_url('candidate/my-courses') ?>" class="card-link">My courses <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-r"/></svg></a></div>
        <div class="card-body">
          <div class="learn-strip">
            <span class="pick-ic" aria-hidden="true" style="width:44px;height:44px"><svg style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-award"/></svg></span>
            <div class="grow"><b>Certificates &amp; Badges 🎉</b>
              <p>Acquire career ready skills</p></div>
          </div>
          <hr style="border:none;border-top:1px solid var(--border);margin:14px 0">
          <b style="font-size:.82rem;color:var(--brand-deep)">Suggested next course</b>
          <p style="font-size:.76rem;color:var(--muted);margin:3px 0 10px">Customer Service Excellence — pairs well with your skill profile.</p>
          <a href="<?= base_url('training') ?>" class="btn btn-outline btn-sm btn-block">Browse Training Catalog</a>
        </div>
      </div>
    </section>

    <!-- recent applications -->
    <section class="card" aria-label="Recent applications">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-clock"/></svg> Recent Applications</span>
        <a href="<?= base_url('candidate/applications') ?>" class="card-link">View all <svg aria-hidden="true" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-r"/></svg></a></div>
      
      <?php if (empty($recentApplications)): ?>
          <div class="empty">
            <span class="empty-ic"><svg aria-hidden="true" style="width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-doc"/></svg></span>
            <h3>You haven't applied to any jobs yet</h3>
            <p>Your applications and their status will appear here. Start with today's matches — it takes about 2 minutes to apply.</p>
            <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-search"/></svg> Browse Jobs</a>
          </div>
      <?php else: ?>
          <div class="card-body p-0">
            <div class="tbl-wrap">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Job Title</th>
                    <th>Applied On</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recentApplications as $app): ?>
                    <tr>
                      <td><b><?= esc($app->job_title) ?></b></td>
                      <td><?= date('M d, Y', strtotime($app->created_at)) ?></td>
                      <td>
                        <span class="pill <?= $app->status == 'hired' || $app->status == 'accepted' ? 'pill--hired' : ($app->status == 'rejected' ? 'pill--rejected' : 'pill--pending') ?>">
                          <?= ucfirst($app->status) ?>
                        </span>
                      </td>
                      <td><a href="<?= base_url('candidate/applications/view/' . $app->id) ?>" class="btn btn-outline btn-sm">View Details</a></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
      <?php endif; ?>
    </section>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- No extra chart JS scripts needed, using clean server-rendered SVG charts -->
<?= $this->endSection() ?>