<?= $this->extend('templates/base') ?>
<?= $this->section('homepage_design') ?>reference<?= $this->endSection() ?>

<?= $this->section('schema') ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "@id": "<?= base_url() ?>#website",
      "url": "<?= base_url() ?>",
      "name": "JobberRecruit",
      "description": "Nigeria's trusted job platform. AI career tools, training & instant certification.",
      "inLanguage": "en-NG",
      "publisher": { "@id": "<?= base_url() ?>#organization" },
      "potentialAction": {
        "@type": "SearchAction",
        "target": { "@type": "EntryPoint", "urlTemplate": "<?= base_url('jobs') ?>?q={search_term_string}" },
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "Organization",
      "@id": "<?= base_url() ?>#organization",
      "name": "JobberRecruit",
      "url": "<?= base_url() ?>",
      "logo": { "@type": "ImageObject", "url": "<?= base_url('images/logo.png') ?>", "width": 232, "height": 60 },
      "sameAs": [
        "https://twitter.com/jobberrecruit",
        "https://www.linkedin.com/company/jobberrecruit",
        "https://www.facebook.com/jobberrecruit",
        "https://www.instagram.com/jobberrecruit"
      ],
      "contactPoint": { "@type": "ContactPoint", "contactType": "customer support", "url": "<?= base_url('contact-us') ?>", "areaServed": "NG", "availableLanguage": "English" }
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        { "@type": "Question", "name": "Is JobberRecruit free to use for job seekers?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Creating an account, searching for jobs, setting up job alerts, and applying to listings are all completely free for job seekers on JobberRecruit." } },
        { "@type": "Question", "name": "How do I search for jobs in Lagos, Abuja, or Port Harcourt?", "acceptedAnswer": { "@type": "Answer", "text": "Use the search bar on the homepage. Select your preferred city from the Location dropdown, enter a job title or keyword, and click Search jobs." } },
        { "@type": "Question", "name": "What is the AI Resume Builder?", "acceptedAnswer": { "@type": "Answer", "text": "The AI Resume Builder helps you create a professional, ATS-optimised resume in minutes, tailored to specific job roles and industries in Nigeria." } },
        { "@type": "Question", "name": "Can employers post jobs for free on JobberRecruit?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Employers can post their first job listing for free. Premium plans offer additional visibility and featured placements. Access to the verified candidate database is a separate paid product and is not included in premium plans." } },
        { "@type": "Question", "name": "How does the JobberRecruit referral programme work?", "acceptedAnswer": { "@type": "Answer", "text": "Share your unique referral link. When someone you refer makes their first qualifying payment, you instantly receive 10% commission added to your JobberRecruit wallet. Wallet funds are non-withdrawable but can be spent on JobberRecruit services." } }
      ]
    }
  ]
}
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/homepage.css') ?>?v=<?= @filemtime(FCPATH . 'css/homepage.css') ?: time() ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main-content">


  <!-- ========== 1. HERO ========== -->
  <section class="hero" aria-label="Find jobs in Nigeria">
    <span class="hero-grid-bg" aria-hidden="true"></span>
    <svg class="hero-motif" viewBox="0 0 400 400" fill="none" aria-hidden="true" focusable="false">
      <defs>
        <radialGradient id="motifGlow" cx="50%" cy="42%" r="55%"><stop offset="0%" stop-color="var(--accent)" stop-opacity=".22"/><stop offset="100%" stop-color="var(--accent)" stop-opacity="0"/></radialGradient>
        <clipPath id="ringClip"><circle cx="200" cy="190" r="92"/></clipPath>
      </defs>
      <circle cx="200" cy="188" r="150" fill="url(#motifGlow)"/>
      <g clip-path="url(#ringClip)">
        <g class="scan">
          <rect x="108" y="98" width="184" height="184" fill="none"/>
          <line x1="200" y1="190" x2="200" y2="98" stroke="var(--accent)" stroke-width="3" stroke-opacity=".5">
            <animateTransform attributeName="transform" type="rotate" from="0 200 190" to="360 200 190" dur="6s" repeatCount="indefinite"/>
          </line>
        </g>
        <line x1="120" y1="190" x2="280" y2="190" stroke="#fff" stroke-opacity=".06" stroke-width="1"/>
        <line x1="200" y1="110" x2="200" y2="270" stroke="#fff" stroke-opacity=".06" stroke-width="1"/>
      </g>
      <line class="handle" x1="262" y1="252" x2="318" y2="312" stroke="var(--accent)" stroke-width="26" stroke-linecap="round"/>
      <circle class="ring" cx="200" cy="190" r="78" fill="none" stroke="var(--accent)" stroke-width="26"/>
      <circle class="head" cx="200" cy="78" r="30" fill="var(--accent)"/>
    </svg>
    <div class="container hero-inner">
      <p class="hero-tag"><svg aria-hidden="true"><use href="#i-shield"/></svg> Verified job platform · Nigeria</p>
      <div class="hero-tabs" role="group" aria-label="Select your role">
        <button class="active" onclick="switchHero(this,'seeker')" aria-pressed="true"><svg aria-hidden="true"><use href="#i-search"/></svg> Job seeker</button>
        <button onclick="switchHero(this,'employer')" aria-pressed="false"><svg aria-hidden="true"><use href="#i-building"/></svg> Employer</button>
      </div>
      
      <div id="hero-seeker">
        <h1>Find jobs, learn skills<br>&amp; <em>grow your career</em></h1>
        <p class="hero-sub">Search verified jobs, build AI-powered resumes, practise interviews, earn professional certificates, and connect with top employers across Nigeria.</p>
        <form class="search-card" action="<?= base_url('jobs') ?>" method="get" role="search" aria-label="Job search form">
          <label for="q" class="sr-only">Job title or keyword</label>
          <div class="search-field"><svg aria-hidden="true"><use href="#i-search"/></svg><input id="q" type="search" name="q" placeholder="e.g. Software Engineer" autocomplete="off" value="<?= esc($q) ?>"></div>
          <label for="loc" class="sr-only">Location</label>
          <div class="search-field"><svg aria-hidden="true"><use href="#i-pin"/></svg>
            <select id="loc" name="state_id">
              <option value="">All locations</option>
              <?php foreach ($states as $s): ?>
                <option value="<?= $s->id ?>"><?= esc($s->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <label for="cat" class="sr-only">Category</label>
          <div class="search-field"><svg aria-hidden="true"><use href="#i-bag"/></svg>
            <select id="cat" name="category_id">
              <option value="">All categories</option>
              <?php foreach ($categories as $c): ?>
                <option value="<?= $c->id ?>"><?= esc($c->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit"><svg aria-hidden="true"><use href="#i-search"/></svg> Search jobs</button>
        </form>
        <div class="trending" aria-label="Trending searches">
          <strong>TRENDING:</strong>
          <a href="<?= base_url('jobs?work_arrangement=remote') ?>">Remote</a>
          <a href="<?= base_url('jobs?q=software+developer') ?>">Software Developer</a>
          <a href="<?= base_url('jobs?q=marketing') ?>">Marketing</a>
          <a href="<?= base_url('jobs?q=data+analyst') ?>">Data Analyst</a>
          <a href="<?= base_url('jobs?q=finance') ?>">Finance</a>
          <a href="<?= base_url('jobs?q=oil+and+gas') ?>">Oil &amp; Gas</a>
        </div>
        <div class="hero-pills" aria-label="Quick access">
          <a href="<?= base_url('candidate/resumes/build') ?>"><svg aria-hidden="true"><use href="#i-bot"/></svg> AI resume builder</a>
          <a href="<?= base_url('cv-review') ?>"><svg aria-hidden="true"><use href="#i-doc"/></svg> CV revamp</a>
          <a href="<?= base_url('training') ?>"><svg aria-hidden="true"><use href="#i-cap"/></svg> Instant certificates</a>
          <a href="<?= base_url('candidate/career-tools/mock-interview') ?>"><svg aria-hidden="true"><use href="#i-mic"/></svg> AI mock interview</a>
          <a href="<?= base_url('job-alerts') ?>"><svg aria-hidden="true"><use href="#i-bell"/></svg> Job alerts</a>
          <a href="#referral"><svg aria-hidden="true"><use href="#i-gift"/></svg> Referral rewards</a>
        </div>
      </div>
      
      <div id="hero-employer" hidden>
        <h2 class="hero-employer-h2">Hire top Nigerian talent — <em>fast</em></h2>
        <p class="hero-sub">Post your vacancy free and reach verified, pre-screened candidates across Lagos, Abuja, Port Harcourt, and every state in Nigeria.</p>
        <div class="hero-trust"><span><svg aria-hidden="true"><use href="#i-check"/></svg> Verified candidate pool</span><span><svg aria-hidden="true"><use href="#i-check"/></svg> Post your first job free</span><span><svg aria-hidden="true"><use href="#i-check"/></svg> Smart recruitment dashboard</span></div>
        <div class="hero-employer-actions">
          <a href="<?= base_url('employer/post-job') ?>" class="btn btn-accent"><svg aria-hidden="true"><use href="#i-edit"/></svg> Post a job free</a>
          <a href="<?= base_url('contact-us') ?>" class="btn btn-white">Let us help you recruit</a>
        </div>
        <div class="hero-pills" aria-label="Employer quick access">
          <a href="<?= base_url('job-ads') ?>"><svg aria-hidden="true"><use href="#i-bag"/></svg> View pricing plans</a>
          <a href="<?= base_url('candidates') ?>"><svg aria-hidden="true"><use href="#i-users"/></svg> Browse candidate database</a>
          <a href="<?= base_url('employer/post-job') ?>"><svg aria-hidden="true"><use href="#i-doc"/></svg> Job posting guide</a>
          <a href="#referral"><svg aria-hidden="true"><use href="#i-gift"/></svg> Referral rewards</a>
        </div>
      </div>
    </div>
    
    <!-- Ticker Track with Dynamic Duplicated Jobs for Seamless Scrolling -->
    <div class="ticker" aria-label="Latest jobs posted">
      <div class="ticker-label"><span class="ticker-dot" aria-hidden="true"></span><span>Just posted</span></div>
      <div class="ticker-viewport">
        <div class="ticker-track" id="ticker-track" aria-hidden="true">
          <?php
          if (!empty($jobs)) {
              $tickerJobs = array_merge($jobs, $jobs);
              foreach ($tickerJobs as $j):
                  $isNew = (strtotime($j->created_at) > strtotime('-3 days'));
                  $coName = !empty($j->anonymous) ? 'Confidential Employer' : ($j->employer_name ?? 'Company');
                  $coLogo = !empty($j->anonymous) ? base_url('images/favicon.png') : resolve_image_url($j->company_logo ?? '', 'company', $coName);
                  ?>
                  <a class="ticker-item" href="<?= base_url('job/view/' . $j->id) ?>">
                    <?php if ($isNew): ?><span class="ticker-new">NEW</span><?php endif; ?>
                    <span class="ticker-role"><?= esc($j->title) ?></span>
                    <span class="ticker-co">· <?= esc($coName) ?></span>
                    <span class="ticker-loc"><svg aria-hidden="true"><use href="#i-pin"/></svg><?= esc($j->location ?? 'Nigeria') ?></span>
                  </a>
                  <?php
              endforeach;
          }
          ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== 2. RECENT JOBS GRID ========== -->
  <section class="section" id="jobs" aria-labelledby="jobs-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-star"/></svg> Hand-picked opportunities</p>
      <div class="jobs-header">
        <h2 class="section-title" id="jobs-h">Recent jobs in <span>Nigeria</span></h2>
        <div class="jobs-header-cta">
          <span class="jobs-total">Showing <strong><?= min(count($jobs), 8) ?></strong> of <strong class="live-count" data-count="<?= $activeJobsCount ?? 0 ?>" id="jobs-total-count"><?= number_format($activeJobsCount ?? 0) ?></strong>+ live jobs</span>
          <a href="<?= base_url('jobs/featured') ?>" class="btn btn-outline">See more featured jobs →</a>
        </div>
      </div>
      <p class="section-sub">Top companies are actively hiring. Don't miss your window.</p>
      
      <div class="jobs-grid">
        <?php if (!empty($jobs)): ?>
          <?php foreach (array_slice($jobs, 0, 8) as $job): 
              $isFeatured = !empty($job->is_featured);
              $coName = !empty($job->anonymous) ? 'Confidential Employer' : ($job->employer_name ?? 'Company');
              $coLogo = !empty($job->anonymous) ? base_url('images/favicon.png') : resolve_image_url($job->company_logo ?? '', 'company', $coName);
              $timeAgo = time_ago($job->created_at);
              $isSaved = in_array((int)$job->id, $savedJobIds ?? []);
          ?>
            <article class="job-card <?= $isFeatured ? 'job-card--featured' : '' ?>" aria-label="<?= esc($job->title) ?> – Jobs in Nigeria">
              <?php if ($isFeatured): ?>
                <span class="badge-featured"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
              <?php endif; ?>
              <div class="job-card-top">
                <div>
                  <h3 class="job-title" title="<?= esc($job->title) ?>"><?= esc($job->title) ?></h3>
                  <div class="job-company">
                    <span class="job-company-name"><?= esc($coName) ?></span>
                    <?php if (!empty($job->show_trust_badge) && empty($job->anonymous)): ?>
                      <button type="button" class="verified-check" aria-label="Verified employer — tap for details">
                        <svg aria-hidden="true"><use href="#i-verified-disc"/></svg>
                        <span class="verified-tip" role="tooltip"><svg aria-hidden="true"><use href="#i-verified-disc"/></svg><strong>Verified employer</strong></span>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="job-logo" aria-hidden="true">
                  <?php if (!empty($coLogo)): ?>
                    <img src="<?= $coLogo ?>" alt="<?= esc($coName) ?> logo" loading="lazy">
                  <?php else: ?>
                    <?= esc(substr($coName, 0, 2)) ?>
                  <?php endif; ?>
                </div>
              </div>
              <div class="job-meta">
                <span><svg aria-hidden="true"><use href="#i-pin"/></svg> <?= esc($job->location ?? 'Nigeria') ?></span>
                <span><svg aria-hidden="true"><use href="#i-bag"/></svg> <?= esc($job->job_type ?? 'Full-time') ?></span>
                <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= $timeAgo ?></span>
              </div>
              <div class="job-salary-row">
                <span class="job-salary">
                  <?php 
                  if (!empty($job->salary_details)) {
                      echo esc($job->salary_details);
                  } elseif (!empty($job->salary)) {
                      echo '₦' . number_format((float) $job->salary);
                  } elseif (!empty($job->min_salary) && !empty($job->max_salary)) {
                      echo '₦' . number_format($job->min_salary) . ' - ₦' . number_format($job->max_salary);
                  } elseif (!empty($job->min_salary)) {
                      echo '₦' . number_format($job->min_salary);
                  } else {
                      echo 'Negotiable';
                  }
                  ?>
                </span>
              </div>
              <div class="job-actions">
                <a href="<?= base_url('job/view/' . $job->id) ?>" class="btn btn-primary">Quick apply</a>
                <button class="save-btn" data-job-id="<?= $job->id ?>" aria-label="<?= $isSaved ? 'Job saved — click to remove' : 'Save job' ?>" data-saved="<?= $isSaved ? 'true' : 'false' ?>">
                  <svg aria-hidden="true"><use href="<?= $isSaved ? '#i-bookmark-fill' : '#i-bookmark' ?>"/></svg> <?= $isSaved ? 'Saved' : 'Save' ?>
                </button>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="jobs-empty">
            <h5 class="text-muted">No recent jobs available at the moment.</h5>
          </div>
        <?php endif; ?>
      </div>
      <div class="jobs-more">
        <a href="<?= base_url('jobs/featured') ?>" class="btn btn-primary">See more featured jobs →</a>
      </div>
    </div>
  </section>

  <!-- Google AdSense Unit (Reference Layout) -->
  <aside class="ad-section" aria-label="Advertisement">
    <div class="ad-slot">
      <div class="ad-unit">
        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-3464186884176173" data-ad-slot="6229476516" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <script>
          try { (adsbygoogle = window.adsbygoogle || []).push({}); } catch (e) {}
        </script>
      </div>
    </div>
  </aside>

  <!-- ========== 3. BROWSE BY CATEGORY ========== -->
  <section class="section hiw-bg" id="categories" aria-labelledby="cat-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-bag"/></svg> Find your field</p>
      <div class="jobs-header">
        <h2 class="section-title" id="cat-h">Browse jobs by <span>category</span></h2>
        <a href="<?= base_url('jobs') ?>" class="btn btn-outline">All categories →</a>
      </div>
      <p class="section-sub">Explore opportunities across every industry in Nigeria — from technology to oil &amp; gas, healthcare, and beyond.</p>
      
      <div class="cat-grid">
        <?php
        $svgMap = [
            'Technology' => '#i-chip',
            'Finance' => '#i-bank',
            'Banking' => '#i-bank',
            'Marketing' => '#i-mega',
            'Engineering' => '#i-gear',
            'Oil & Gas' => '#i-drop',
            'Oil and Gas' => '#i-drop',
            'Healthcare' => '#i-heart-pulse',
            'Medical' => '#i-heart-pulse',
            'Education' => '#i-book',
            'Sales' => '#i-handshake'
        ];
        if (!empty($categories)): ?>
          <?php foreach (array_slice($categories, 0, 8) as $cat): 
              $svgIcon = '#i-bag'; // Fallback
              foreach ($svgMap as $key => $val) {
                  if (stripos($cat->name, $key) !== false) {
                      $svgIcon = $val;
                      break;
                  }
              }
          ?>
            <a href="<?= base_url('jobs?category_id=' . $cat->id) ?>" class="cat-card">
              <div class="cat-icon"><svg aria-hidden="true"><use href="<?= $svgIcon ?>"/></svg></div>
              <h3 class="cat-name"><?= esc($cat->name) ?></h3>
              <div class="cat-count"><span class="live-count" data-count="<?= $cat->job_count ?? 0 ?>"><?= number_format($cat->job_count ?? 0) ?></span> open roles</div>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ========== 4. BROWSE BY LOCATION ========== -->
  <section class="section" id="locations" aria-labelledby="loc-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-pin"/></svg> Across Nigeria</p>
      <h2 class="section-title" id="loc-h">Browse jobs by <span>location</span></h2>
      <p class="section-sub">Jobs in top cities and states across Nigeria — or work remotely from anywhere.</p>
      
      <div class="loc-grid">
        <?php if (!empty($top_locations)): ?>
          <?php foreach (array_slice($top_locations, 0, 6) as $loc): 
              $locName = $loc->name;
              $svgIcon = '#i-building'; // Default
              if (stripos($locName, 'Lagos') !== false) $svgIcon = '#i-building';
              elseif (stripos($locName, 'Abuja') !== false || stripos($locName, 'FCT') !== false) $svgIcon = '#i-building';
              elseif (stripos($locName, 'Harcourt') !== false) $svgIcon = '#i-drop';
          ?>
            <a href="<?= esc($loc->url) ?>" class="loc-card">
              <div class="loc-ic"><svg aria-hidden="true"><use href="<?= $svgIcon ?>"/></svg></div>
              <h3 class="loc-name"><?= esc($loc->name) ?></h3>
              <div class="loc-count"><span class="live-count" data-count="<?= $loc->job_count ?? 0 ?>"><?= number_format($loc->job_count ?? 0) ?></span> jobs</div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <a href="<?= base_url('jobs?location=lagos') ?>" class="loc-card"><div class="loc-ic"><svg aria-hidden="true"><use href="#i-building"/></svg></div><h3 class="loc-name">Lagos</h3><div class="loc-count"><span class="live-count" data-count="4820">4,820</span> jobs</div></a>
          <a href="<?= base_url('jobs?location=abuja') ?>" class="loc-card"><div class="loc-ic"><svg aria-hidden="true"><use href="#i-building"/></svg></div><h3 class="loc-name">Abuja (FCT)</h3><div class="loc-count"><span class="live-count" data-count="2340">2,340</span> jobs</div></a>
          <a href="<?= base_url('jobs?location=port-harcourt') ?>" class="loc-card"><div class="loc-ic"><svg aria-hidden="true"><use href="#i-drop"/></svg></div><h3 class="loc-name">Port Harcourt</h3><div class="loc-count"><span class="live-count" data-count="1560">1,560</span> jobs</div></a>
          <a href="<?= base_url('jobs?location=kano') ?>" class="loc-card"><div class="loc-ic"><svg aria-hidden="true"><use href="#i-building"/></svg></div><h3 class="loc-name">Kano</h3><div class="loc-count"><span class="live-count" data-count="680">680</span> jobs</div></a>
          <a href="<?= base_url('jobs?location=ibadan') ?>" class="loc-card"><div class="loc-ic"><svg aria-hidden="true"><use href="#i-building"/></svg></div><h3 class="loc-name">Ibadan</h3><div class="loc-count"><span class="live-count" data-count="520">520</span> jobs</div></a>
          <a href="<?= base_url('jobs?location=enugu') ?>" class="loc-card"><div class="loc-ic"><svg aria-hidden="true"><use href="#i-building"/></svg></div><h3 class="loc-name">Enugu</h3><div class="loc-count"><span class="live-count" data-count="340">340</span> jobs</div></a>
        <?php endif; ?>
        <a href="<?= base_url('jobs?location=remote') ?>" class="loc-card">
          <div class="loc-ic"><svg aria-hidden="true"><use href="#i-globe"/></svg></div>
          <h3 class="loc-name">Remote</h3>
          <div class="loc-count"><span class="live-count" data-count="<?= $remoteJobsCount ?? 0 ?>"><?= number_format($remoteJobsCount ?? 0) ?></span> jobs</div>
        </a>
        <a href="<?= base_url('jobs') ?>" class="loc-card featured">
          <div class="loc-ic"><svg aria-hidden="true"><use href="#i-pin"/></svg></div>
          <h3 class="loc-name">All 36 states</h3>
          <div class="loc-count">View all locations</div>
        </a>
      </div>
    </div>
  </section>

  <!-- ========== 5. HOW IT WORKS ========== -->
  <section class="section hiw-bg" id="how-it-works" aria-labelledby="hiw-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-rocket"/></svg> How it works</p>
      <h2 class="section-title" id="hiw-h">Start your job search <span>in minutes</span></h2>
      <p class="section-sub">Four simple steps from sign-up to getting hired — no confusion, no waiting.</p>
      <div class="steps-grid">
        <div class="step-card"><div class="step-num" aria-hidden="true"></div><div class="step-ic"><svg aria-hidden="true"><use href="#i-edit"/></svg></div><div class="step-title">Create a free account</div><p class="step-desc">Sign up in under 60 seconds. No card needed. Instant access to verified jobs across Nigeria.</p></div>
        <div class="step-card"><div class="step-num" aria-hidden="true"></div><div class="step-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div><div class="step-title">Build your AI resume</div><p class="step-desc">Use the AI resume builder to create an ATS-optimised resume tailored to your target roles.</p></div>
        <div class="step-card"><div class="step-num" aria-hidden="true"></div><div class="step-ic"><svg aria-hidden="true"><use href="#i-search"/></svg></div><div class="step-title">Search &amp; apply</div><p class="step-desc">Filter by location, salary, category, or type. Apply to verified employers with a single click.</p></div>
        <div class="step-card"><div class="step-num" aria-hidden="true"></div><div class="step-ic"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></div><div class="step-title">Get hired</div><p class="step-desc">Practise with AI mock interviews, track your applications, and land your next role faster.</p></div>
      </div>
    </div>
  </section>

  <!-- ========== 6. FEATURED COURSES (DYNAMIC UPSKILL TEASER) ========== -->
  <section class="section training-bg" id="training" aria-labelledby="train-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-cap"/></svg> Upskill &amp; certify</p>
      <div class="jobs-header">
        <h2 class="section-title" id="train-h">Learn new skills &amp; earn <span>instant certificates</span></h2>
        <a href="<?= base_url('training') ?>" class="btn btn-outline">Browse courses →</a>
      </div>
      <p class="section-sub">Industry-relevant courses — paid courses come with an employer-recognised certificate. Learn at your own pace.</p>
      
      <div class="course-grid">
        <?php if (!empty($featured_courses)): ?>
          <?php foreach ($featured_courses as $c): 
              $isPaid = ($c->price > 0);
              $colorClasses = ['thumb-blue', 'thumb-purple', 'thumb-green', 'thumb-orange'];
              $gradientClass = $colorClasses[$c->id % count($colorClasses)];
              $imgUrl = !empty($c->thumbnail) ? (preg_match('#^https?://#i', $c->thumbnail) ? $c->thumbnail : base_url($c->thumbnail)) : '';
          ?>
            <div class="course-card">
              <span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span>
              <div class="course-thumb <?= empty($imgUrl) ? $gradientClass : '' ?>">
                <?php if (!empty($imgUrl)): ?>
                  <img class="thumb-img" src="<?= esc($imgUrl) ?>" alt="<?= esc($c->title) ?> course thumbnail" loading="lazy">
                <?php else: ?>
                  <svg aria-hidden="true"><use href="#i-book"/></svg>
                <?php endif; ?>
              </div>
              <div class="course-body">
                <h3 class="course-title"><?= esc($c->title) ?></h3>
                <div class="course-meta">
                  <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= esc($c->duration) ?></span>
                  <span>
                    <?php if ($isPaid): ?>
                      <svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate
                    <?php else: ?>
                      <svg aria-hidden="true"><use href="#i-book"/></svg> <?= esc($c->level ?? 'Beginner') ?>
                    <?php endif; ?>
                  </span>
                </div>
              </div>
              <div class="course-footer">
                <?php if ($isPaid): ?>
                  <span class="course-cert"><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span>
                <?php endif; ?>
                <div class="course-price-row">
                  <span class="course-price <?= $isPaid ? 'course-price--paid' : 'course-price--free' ?>">
                    <?= $isPaid ? '₦' . number_format($c->price) : 'Free' ?>
                  </span>
                  <a href="<?= base_url('training/course/' . (int) $c->id) ?>" class="btn btn-primary btn-sm">
                    <?= $isPaid ? 'Enrol now' : 'Enrol free' ?>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="course-card"><span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span><div class="course-thumb"><img class="thumb-img" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=240&fit=crop" alt="Data Analysis with Excel &amp; Python course thumbnail" loading="lazy"></div><div class="course-body"><h3 class="course-title">Data Analysis with Excel &amp; Python</h3><div class="course-meta"><span><svg aria-hidden="true"><use href="#i-clock"/></svg> 8 hours</span><span><svg aria-hidden="true"><use href="#i-book"/></svg> Beginner</span></div></div><div class="course-footer"><div class="course-price-row"><span class="course-price course-price--free">Free</span><a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm">Enrol free</a></div></div></div>
          <div class="course-card"><span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span><div class="course-thumb thumb-purple"><svg aria-hidden="true"><use href="#i-mega"/></svg></div><div class="course-body"><h3 class="course-title">Digital Marketing Fundamentals</h3><div class="course-meta"><span><svg aria-hidden="true"><use href="#i-clock"/></svg> 6 hours</span><span><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span></div></div><div class="course-footer"><span class="course-cert"><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span><div class="course-price-row"><span class="course-price course-price--paid">₦15,000</span><a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm">Enrol now</a></div></div></div>
          <div class="course-card"><span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span><div class="course-thumb thumb-green"><svg aria-hidden="true"><use href="#i-bag"/></svg></div><div class="course-body"><h3 class="course-title">Project Management Essentials</h3><div class="course-meta"><span><svg aria-hidden="true"><use href="#i-clock"/></svg> 10 hours</span><span><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span></div></div><div class="course-footer"><span class="course-cert"><svg aria-hidden="true"><use href="#i-cap"/></svg> Certificate</span><div class="course-price-row"><span class="course-price course-price--paid">₦25,000</span><a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm">Enrol now</a></div></div></div>
          <div class="course-card"><span class="course-featured-badge"><svg aria-hidden="true"><use href="#i-star"/></svg> Featured</span><div class="course-thumb thumb-orange"><svg aria-hidden="true"><use href="#i-lock"/></svg></div><div class="course-body"><h3 class="course-title">Cybersecurity for Beginners</h3><div class="course-meta"><span><svg aria-hidden="true"><use href="#i-clock"/></svg> 12 hours</span><span><svg aria-hidden="true"><use href="#i-book"/></svg> Beginner</span></div></div><div class="course-footer"><div class="course-price-row"><span class="course-price course-price--free">Free</span><a href="<?= base_url('training') ?>" class="btn btn-primary btn-sm">Enrol free</a></div></div></div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ========== 7. SUCCESS STORIES (DYNAMIC TESTIMONIALS) ========== -->
  <section class="section" id="testimonials" aria-labelledby="testi-h">
    <div class="container">
      <p class="section-label"><svg aria-hidden="true"><use href="#i-star"/></svg> Success stories</p>
      <h2 class="section-title" id="testi-h">Nigerians are <span>getting hired</span></h2>
      <p class="section-sub">Real people, real careers. Here's what they're saying about JobberRecruit.</p>
      
      <div class="testi-grid">
        <!-- Hardcoded testimonials from the reference design — always shown first -->
        <article class="testi-card"><div class="testi-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div><blockquote class="testi-text">"I had been job hunting for 8 months with no luck. I used the AI resume builder on JobberRecruit and got two interview calls within a week. I accepted an offer 3 weeks later!"</blockquote><div class="testi-author"><div class="testi-avatar" aria-hidden="true">AO</div><div><div class="testi-name">Adesola Okafor</div><div class="testi-role">Software Engineer · Lagos</div></div></div></article>
        <article class="testi-card"><div class="testi-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div><blockquote class="testi-text">"The AI mock interview feature is genuinely brilliant. It asked me the exact types of questions I faced in my real interview. I walked in so much more confident."</blockquote><div class="testi-author"><div class="testi-avatar" aria-hidden="true">CE</div><div><div class="testi-name">Chioma Ezenwachi</div><div class="testi-role">Finance Analyst · Abuja</div></div></div></article>
        <article class="testi-card"><div class="testi-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div><blockquote class="testi-text">"I earned my Digital Marketing certificate on JobberRecruit in 3 days. My employer specifically mentioned it during onboarding. Absolutely worth it."</blockquote><div class="testi-author"><div class="testi-avatar" aria-hidden="true">TI</div><div><div class="testi-name">Tunde Idowu</div><div class="testi-role">Marketing Manager · Port Harcourt</div></div></div></article>
        <article class="testi-card"><div class="testi-stars" aria-label="5 out of 5 stars"><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg><svg aria-hidden="true"><use href="#i-star"/></svg></div><blockquote class="testi-text">"I found my dream job through JobberRecruit in less than two weeks. The job alerts matched my skills perfectly and the application process was seamless."</blockquote><div class="testi-author"><div class="testi-avatar" aria-hidden="true">FK</div><div><div class="testi-name">Fatima Kabir</div><div class="testi-role">Data Analyst · Kano</div></div></div></article>
        <!-- Dynamic DB testimonials appended after hardcoded ones -->
        <?php if (!empty($testimonials)): ?>
          <?php foreach (array_slice($testimonials, 0, 10) as $t): 
              // Skip dummy entries
              $skipNames = ['john doe', 'james smith'];
              if (in_array(strtolower(trim($t->name ?? '')), $skipNames)) continue;
              $initials = '';
              $parts = explode(' ', $t->name ?? '');
              foreach ($parts as $p) {
                  $initials .= substr($p, 0, 1);
              }
              $initials = strtoupper(substr($initials, 0, 2));
          ?>
            <article class="testi-card">
              <div class="testi-stars" aria-label="5 out of 5 stars">
                <?php for ($i=0; $i<5; $i++): ?><svg aria-hidden="true"><use href="#i-star"/></svg><?php endfor; ?>
              </div>
              <blockquote class="testi-text">"<?= esc($t->testimonial) ?>"</blockquote>
              <div class="testi-author">
                <div class="testi-avatar" aria-hidden="true"><?= esc($initials) ?></div>
                <div>
                  <div class="testi-name"><?= esc($t->name) ?></div>
                  <div class="testi-role"><?= esc($t->role ?? 'Jobber') ?></div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ========== 8. NEWSLETTER ========== -->
  <section class="newsletter-band" id="newsletter" aria-labelledby="news-h">
    <div class="container newsletter-inner">
      <div class="newsletter-text">
        <p class="section-label"><svg aria-hidden="true"><use href="#i-mail"/></svg> Newsletter</p>
        <h2 class="newsletter-title" id="news-h">Career tips &amp; job market insights, <span>straight to your inbox</span></h2>
        <p class="newsletter-sub">Weekly advice on landing roles, salary trends, and hiring in Nigeria. No account needed — unsubscribe anytime.</p>
      </div>
      <form class="newsletter-form" action="<?= base_url('newsletter/subscribe') ?>" method="post" aria-label="Newsletter signup">
        <?= csrf_field() ?>
        <label for="news-email" class="sr-only">Email address</label>
        <div class="newsletter-field">
          <svg aria-hidden="true"><use href="#i-mail"/></svg>
          <input id="news-email" type="email" name="email" placeholder="Enter your email" required autocomplete="email">
        </div>
        <button type="submit" class="btn btn-primary">Subscribe</button>
        <p class="newsletter-feedback" role="status" aria-live="polite" hidden></p>
      </form>
    </div>
  </section>

  <!-- ========== 9. REFERRAL BAND ========== -->
  <section class="section" id="referral" aria-labelledby="ref-h">
    <div class="container">
      <div class="referral-band">
        <div class="referral-band-text">
          <div class="ref-ic ref-ic--lg"><svg aria-hidden="true"><use href="#i-gift"/></svg></div>
          <div>
            <h2 class="referral-band-title" id="ref-h">Refer &amp; earn <span>10% commission</span> — no limit</h2>
            <p class="referral-band-sub">Sign up, share your link, and earn 10% every time someone you refer makes their first payment. Earnings go to your wallet and can be spent on JobberRecruit services (wallet funds are non-withdrawable).</p>
          </div>
        </div>
        <a href="<?= base_url('register') ?>" class="btn btn-accent referral-band-cta"><svg aria-hidden="true"><use href="#i-gift"/></svg> Start earning</a>
      </div>
    </div>
  </section>

  <!-- ========== 10. FAQ ========== -->
  <section class="section faq-bg" id="faq" aria-labelledby="faq-h">
    <div class="container">
      <p class="section-label text-center" style="margin-left:auto;margin-right:auto"><svg aria-hidden="true"><use href="#i-bulb"/></svg> Got questions?</p>
      <h2 class="section-title text-center" id="faq-h">Frequently asked <span>questions</span></h2>
      <div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">Is JobberRecruit free to use for job seekers?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">Yes. Creating an account, searching for jobs, setting up job alerts, and applying to listings are all completely free for job seekers on JobberRecruit.</p></div></details>
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">How do I search for jobs in Lagos, Abuja, or Port Harcourt?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">Use the search bar on the homepage. Select your preferred city from the Location dropdown, enter a job title or keyword, and click Search jobs. You can also browse by location directly from the Locations section.</p></div></details>
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">What is the AI Resume Builder?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">The AI Resume Builder is a smart tool that helps you create a professional, ATS-optimised resume in minutes. It tailors your resume to specific job roles and industries in Nigeria, helping you pass automated screening and stand out to employers.</p></div></details>
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">Can employers post jobs for free on JobberRecruit?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">Yes. Employers can post their first job listing for free. Premium plans offer additional visibility and featured placements. Access to the verified candidate database is a separate paid product and is not included in premium plans. Visit our Pricing page for full details.</p></div></details>
        <details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"><summary itemprop="name">How does the JobberRecruit referral programme work?<svg class="faq-chev" aria-hidden="true"><use href="#i-chev-down"/></svg></summary><div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><p itemprop="text">Share your unique referral link with friends, candidates, or employers. When someone you referred makes their first qualifying payment, you instantly receive 10% commission added to your JobberRecruit wallet. There is no limit on referrals. Please note: all wallet funds — whether earned through referrals or added by you — are non-withdrawable and non-refundable. Wallet balance cannot be cashed out, but can be used to pay for JobberRecruit services such as job postings, premium plans, and certificates.</p></div></details>
      </div>
      <div class="faq-more"><a href="<?= base_url('faq') ?>" class="btn btn-outline">See all FAQs →</a></div>
    </div>
  </section>

  <!-- ========== 11. DUAL CALL TO ACTION ========== -->
  <section class="section" aria-label="Call to action">
    <div class="container">
      <div class="dual-cta">
        <div class="cta-panel blue">
          <div class="cta-ic"><svg aria-hidden="true"><use href="#i-bag"/></svg></div>
          <h2>Ready to find your next job?</h2>
          <p>Join the growing community of Nigerians using JobberRecruit to land great roles faster.</p>
          <ul class="cta-list" aria-label="Job seeker benefits">
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> AI resume builder</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> AI mock interviews</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Personalised career advice</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Smart job alerts</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Training &amp; certificates</li>
          </ul>
          <a href="<?= base_url('register') ?>" class="btn btn-accent">Create free account →</a>
        </div>
        <div class="cta-panel light">
          <div class="cta-ic"><svg aria-hidden="true"><use href="#i-rocket"/></svg></div>
          <h2>Looking to hire? Post a job free</h2>
          <p>Post your vacancy free and reach verified candidates across Nigeria.</p>
          <ul class="cta-list" aria-label="Employer benefits">
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Post your first job free</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Recruitment dashboard</li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Smart application management</li>
            <li class="cta-list-paid"><svg aria-hidden="true"><use href="#i-check"/></svg> Verified candidate database <span class="cta-tag">Paid</span></li>
            <li><svg aria-hidden="true"><use href="#i-check"/></svg> Referral rewards</li>
          </ul>
          <a href="<?= base_url('employer/post-job') ?>" class="btn btn-primary">Post a job free →</a>
        </div>
      </div>
    </div>
  </section>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
window.JR_USER = {
  loggedIn: <?= json_encode($auth->loggedIn()) ?>
};

function switchHero(btn, mode) {
  document.querySelectorAll('.hero-tabs button').forEach(b => {
    b.classList.remove('active');
    b.setAttribute('aria-pressed', 'false');
  });
  btn.classList.add('active');
  btn.setAttribute('aria-pressed', 'true');
  const seeker = document.getElementById('hero-seeker');
  const employer = document.getElementById('hero-employer');
  seeker.hidden = (mode !== 'seeker');
  employer.hidden = (mode !== 'employer');
  const shown = (mode === 'seeker') ? seeker : employer;
  shown.style.animation = 'none';
  void shown.offsetWidth;
  shown.style.animation = '';
}

document.querySelectorAll('.verified-check').forEach(btn => {
  btn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    const wasOpen = btn.classList.contains('open');
    document.querySelectorAll('.verified-check.open').forEach(o => o.classList.remove('open'));
    if (!wasOpen) btn.classList.add('open');
  });
});
document.addEventListener('click', () => {
  document.querySelectorAll('.verified-check.open').forEach(o => o.classList.remove('open'));
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.verified-check.open').forEach(o => o.classList.remove('open'));
  }
});

const BM = '<svg aria-hidden="true"><use href="#i-bookmark"/></svg> Save';
const BMF = '<svg aria-hidden="true"><use href="#i-bookmark-fill"/></svg> Saved';

document.querySelector('.newsletter-form').addEventListener('submit', async event => {
  event.preventDefault();
  const form = event.currentTarget;
  const button = form.querySelector('button[type="submit"]');
  const feedback = form.querySelector('.newsletter-feedback');
  button.disabled = true;
  feedback.hidden = false;
  feedback.textContent = 'Subscribing…';
  try {
    const response = await fetch(form.action, {
      method: 'POST', body: new FormData(form),
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await response.json();
    const message = data.message || (data.messages && data.messages.error);
    feedback.textContent = message || (response.ok ? 'You are subscribed!' : 'Unable to subscribe. Please try again.');
    if (response.ok) form.reset();
  } catch (error) {
    feedback.textContent = 'Unable to subscribe. Please check your connection and try again.';
  } finally {
    button.disabled = false;
  }
});

function isLoggedIn() {
  return !!(window.JR_USER && window.JR_USER.loggedIn);
}
function renderSaveBtn(btn, saved) {
  btn.dataset.saved = String(saved);
  btn.innerHTML = saved ? BMF : BM;
  btn.setAttribute('aria-label', saved ? 'Job saved — click to remove' : 'Save job');
}
function redirectToLogin(jobId) {
  const here = window.location.pathname + window.location.search;
  const ret = encodeURIComponent(here + (here.includes('?') ? '&' : '?') + 'save=' + encodeURIComponent(jobId));
  window.location.href = '/login?redirect=' + ret;
}
function persistSave(jobId, save) {
  return fetch('<?= base_url('jobs/toggle-save') ?>/' + jobId, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': <?= json_encode(csrf_hash()) ?>
    }
  }).then(r => r.ok ? r.json() : Promise.reject(r.status));
}

document.querySelectorAll('.save-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const jobId = btn.dataset.jobId || '';
    if (!isLoggedIn()) {
      redirectToLogin(jobId);
      return;
    }
    const wasSaved = btn.dataset.saved === 'true';
    const next = !wasSaved;
    renderSaveBtn(btn, next);
    btn.disabled = true;
    persistSave(jobId, next).then(data => {
      btn.disabled = false;
      if (data && data.success) {
        renderSaveBtn(btn, data.saved);
      } else {
        renderSaveBtn(btn, wasSaved);
      }
    }).catch(() => {
      renderSaveBtn(btn, wasSaved);
      btn.disabled = false;
      btn.setAttribute('aria-label', 'Could not save — try again');
    });
  });
});

(function resumePendingSave() {
  if (!isLoggedIn()) return;
  const pending = new URLSearchParams(window.location.search).get('save');
  if (!pending || !/^\d+$/.test(pending)) return;
  const btn = document.querySelector('.save-btn[data-job-id="' + pending + '"]');
  if (btn && btn.dataset.saved !== 'true') {
    renderSaveBtn(btn, true);
    persistSave(pending, true).catch(() => renderSaveBtn(btn, false));
  }
})();

// Counts and ticker entries come from the live server-rendered data.
document.querySelectorAll('.live-count[data-count]').forEach(el => {
  el.textContent = Number(el.dataset.count).toLocaleString('en-NG');
});
</script>

<!-- SVG Icon Sprites -->
<svg xmlns="http://www.w3.org/2000/svg" style="display:none">
  <symbol id="i-rocket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M5 13c-1.5.5-3 2.5-3 5 2.5 0 4.5-1.5 5-3"/><path d="M13 7a8 8 0 0 1 7-4 8 8 0 0 1-4 7l-4 3-2-2Z"/><path d="m9 11-3 3 4 4 3-3M15 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/>
  </symbol>
  <symbol id="i-bag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
  </symbol>
  <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="20 6 9 17 4 12"/>
  </symbol>
  <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
  </symbol>
</svg>

<?= $this->endSection() ?>
