<?php
/**
 * INTEGRATION SAMPLE — JobberRecruit job cards
 * ===========================================================================
 * Two parts:
 *   (A) Controller: build the $jobs array (here: hardcoded to mirror the
 *       template; in production replace with your model query).
 *   (B) View: the foreach loop that replaces the 9 hardcoded <article> blocks.
 * ===========================================================================
 */


/* ───────────────────────────────────────────────────────────────────────
 * (A) CONTROLLER — app/Controllers/Home.php (excerpt)
 * ─────────────────────────────────────────────────────────────────────── */

public function index()
{
    // PRODUCTION: replace this block with a model call, e.g.
    //   $jobs = $this->jobModel->getHomepageJobs(9);
    // Each row should expose the keys consumed by partials/_job_card.php.
    $jobs = [
        ['slug' => 'software-engineer-buildng',   'title' => 'Software Engineer',  'company' => 'BuildNG Technologies', 'logo' => 'BN', 'location' => 'Remote',        'type' => 'Contract',  'posted' => '2d ago', 'salary' => '500,000', 'verified' => true, 'featured' => true],
        ['slug' => 'marketing-manager-nexus',      'title' => 'Marketing Manager',  'company' => 'Nexus Brands Africa',  'logo' => 'NB', 'location' => 'Port Harcourt', 'type' => 'Full-time', 'posted' => '2d ago', 'salary' => '220,000', 'verified' => true, 'featured' => true],
        ['slug' => 'finance-officer-greenfield',   'title' => 'Finance Officer',    'company' => 'Greenfield PLC',       'logo' => 'GP', 'location' => 'Lagos',         'type' => 'Full-time', 'posted' => '3d ago', 'salary' => '180,000', 'verified' => true],
        ['slug' => 'hse-engineer-petrocom',        'title' => 'HSE Engineer',       'company' => 'PetroCom Nigeria',     'logo' => 'PC', 'location' => 'Port Harcourt', 'type' => 'Full-time', 'posted' => '4d ago', 'salary' => '420,000', 'verified' => true],
        ['slug' => 'frontend-developer-paystack',  'title' => 'Frontend Developer', 'company' => 'Paystack',             'logo' => 'PS', 'location' => 'Lagos',         'type' => 'Full-time', 'posted' => '1d ago', 'salary' => '650,000', 'verified' => true],
        ['slug' => 'registered-nurse-lifebridge',  'title' => 'Registered Nurse',   'company' => 'LifeBridge Hospital',  'logo' => 'LB', 'location' => 'Ibadan',        'type' => 'Full-time', 'posted' => '3d ago', 'salary' => '190,000', 'verified' => true],
        ['slug' => 'sales-executive-tridel',       'title' => 'Sales Executive',    'company' => 'Tridel Distribution',  'logo' => 'TD', 'location' => 'Abuja',         'type' => 'Full-time', 'posted' => '4d ago', 'salary' => '150,000', 'verified' => true],
        ['slug' => 'product-designer-techlagos-limited', 'title' => 'Product Designer', 'company' => 'TechLagos Limited', 'logo' => 'TL', 'location' => 'Lagos Island',  'type' => 'Full-time', 'posted' => 'Today',  'salary' => '350,000', 'verified' => true, 'closed' => true],
        ['slug' => 'data-analyst-finserve-nigeria',      'title' => 'Data Analyst',     'company' => 'FinServe Nigeria',  'logo' => 'FN', 'location' => 'Abuja',         'type' => 'Full-time', 'posted' => '1d ago', 'salary' => '280,000', 'verified' => true, 'closed' => true],
    ];

    return view('home', ['jobs' => $jobs]);
}


/* ───────────────────────────────────────────────────────────────────────
 * (B) VIEW — app/Views/home.php (the jobs-grid block)
 *
 * Replace the entire run of 9 hardcoded <article class="job-card"> ... </article>
 * elements (between <div class="jobs-grid"> and its closing </div>) with:
 * ─────────────────────────────────────────────────────────────────────── */
?>
<div class="jobs-grid">
<?php foreach ($jobs as $job): ?>
  <?= view('partials/_job_card', ['job' => $job]) ?>
<?php endforeach; ?>
</div>
