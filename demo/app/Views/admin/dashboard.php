<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<!-- Start::app-content -->
<div class="container-fluid page-container main-body-container">

    <!-- Start::page-header -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex align-center justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">Jobs Dashboard</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboards</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jobs</li>
            </ol>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start:: row-1 -->
    <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1">

        <!-- Total Employers -->
        <div class="col">
            <div class="card custom-card dashboard-main-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="d-block mb-1">Total Employers</span>
                            <h3 class="fw-semibold mb-0">
                                <?= number_format($totalEmployers) ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Candidates -->
        <div class="col">
            <div class="card custom-card dashboard-main-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="d-block mb-1">Total Candidates</span>
                            <h3 class="fw-semibold mb-0">
                                <?= number_format($totalCandidates) ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Jobs -->
        <div class="col">
            <div class="card custom-card dashboard-main-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="d-block mb-1">Total Jobs</span>
                            <h3 class="fw-semibold mb-0">
                                <?= number_format($totalJobs) ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="col">
            <div class="card custom-card dashboard-main-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="d-block mb-1">Total Applications</span>
                            <h3 class="fw-semibold mb-0">
                                <?= number_format($totalApplications) ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Subscribers -->
        <div class="col">
            <div class="card custom-card dashboard-main-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="d-block mb-1">Active Subscribers</span>
                            <h3 class="fw-semibold mb-0">
                                <?= number_format($activeSubscribers) ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End:: row-1 -->

    <!-- Start:: row-2 -->
    <div class="row">
        <div class="col-xxl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Hiring Performance Overview
                    </div>
                </div>
                <div class="card-body">
                    <div id="employees-performance"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Top 5 Employers
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled jobs-recent-activity-list">
                        <?php

                        use CodeIgniter\I18n\Time;

                        if (!empty($topEmployers)) : ?>
                            <?php foreach ($topEmployers as $employer) : ?>
                                <li>
                                    <div class="d-flex align-items-start gap-3 flex-wrap flex-xxl-nowrap">

                                        <!-- Logo -->
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded">
                                                <img
                                                    src="<?= $employer->logo
                                                                ? base_url($employer->logo)
                                                                : base_url('images/favicon.png') ?>"
                                                    alt="<?= esc($employer->company_name) ?>">
                                            </span>
                                        </div>

                                        <!-- Details -->
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="fw-semibold">
                                                    <?= esc($employer->company_name) ?>
                                                </div>

                                                <?php if ($employer->is_verified) : ?>
                                                    <span class="badge bg-success-transparent">Verified</span>
                                                <?php else : ?>
                                                    <span class="badge bg-warning-transparent">Unverified</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="fs-13 description mb-1">
                                                <?= number_format($employer->total_jobs) ?> jobs posted
                                            </div>

                                            <span class="d-block fs-12 text-muted">
                                                Ranked by total jobs
                                            </span>
                                        </div>

                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="text-muted text-center">
                                No employer data available
                            </li>
                        <?php endif; ?>
                    </ul>

                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-6">
            <div class="card custom-card overflow-hidden">
                <div class="card-header">
                    <div class="card-title">
                        Candidates Overview
                    </div>
                </div>
                <div class="card-body">
                    <div id="candidates-overview"></div>
                </div>
                <div class="card-footer p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center gap-2">
                                <div class="lh-1">
                                    <span class="avatar avatar-md  bg-warning-transparent svg-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368">
                                            <rect fill="none" height="24" width="24" />
                                            <path d="M9.5,11c1.93,0,3.5,1.57,3.5,3.5S11.43,18,9.5,18S6,16.43,6,14.5S7.57,11,9.5,11z M9.5,9C6.46,9,4,11.46,4,14.5 S6.46,20,9.5,20s5.5-2.46,5.5-5.5c0-1.16-0.36-2.23-0.97-3.12L18,7.42V10h2V4h-6v2h2.58l-3.97,3.97C11.73,9.36,10.66,9,9.5,9z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <span class="fs-13 d-block">Male</span>
                                    <h5 class="fw-semibold mb-0"><?= number_format($maleCandidates) ?></h5>
                                </div>
                                <div>
                                    <span class="text-success"><i class="ti ti-arrow-narrow-up me-1"></i><?= $candidateYoYByGender['male']['growth'] ?>%</span>
                                    <span class="d-block fs-13 text-muted">This Year</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center gap-2">
                                <div class="lh-1">
                                    <span class="avatar avatar-md bg-primary-transparent svg-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368">
                                            <rect fill="none" height="24" width="24" />
                                            <path d="M17.5,9.5C17.5,6.46,15.04,4,12,4S6.5,6.46,6.5,9.5c0,2.7,1.94,4.93,4.5,5.4V17H9v2h2v2h2v-2h2v-2h-2v-2.1 C15.56,14.43,17.5,12.2,17.5,9.5z M8.5,9.5C8.5,7.57,10.07,6,12,6s3.5,1.57,3.5,3.5S13.93,13,12,13S8.5,11.43,8.5,9.5z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <span class="fs-13 d-block">Female</span>
                                    <h5 class="fw-semibold mb-0"><?= number_format($femaleCandidates) ?></h5>
                                </div>
                                <div>
                                    <span class="text-danger"><i class="ti ti-arrow-narrow-down me-1"></i><?= $candidateYoYByGender['female']['growth'] ?>%</span>
                                    <span class="d-block fs-13 text-muted">This Year</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: row-2 -->

    <!-- Start:: row-3 -->
    <div class="row">
        <div class="col-xxl-6">
            <div class="card custom-card overflow-hidden">
                <div class="card-header">
                    <div class="card-title">
                        Top 5 Job Positions
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Company</th>
                                    <th scope="col">Job Role</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Job Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topJobPositions)) : ?>
                                    <?php foreach ($topJobPositions as $job) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="avatar avatar-sm bg-light avatar-rounded">
                                                        <img
                                                            src="<?= $job->logo
                                                                        ? base_url($job->logo)
                                                                        : base_url('images/favicon.png') ?>"
                                                            alt="<?= esc($job->company_name) ?>">
                                                    </span>
                                                    <span class="fw-medium">
                                                        <?= esc($job->company_name) ?>
                                                    </span>
                                                </div>
                                            </td>

                                            <td><?= esc($job->title) ?></td>

                                            <td>
                                                <span class="text-muted">
                                                    <i class="ti ti-map-pin me-1"></i>
                                                    <?= esc($job->state_name  ?? '—') ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?php
                                                $badgeMap = [
                                                    'fulltime'  => 'bg-primary-transparent',
                                                    'parttime'  => 'bg-success-transparent',
                                                    'intern'    => 'bg-secondary-transparent',
                                                    'freelance' => 'bg-warning-transparent',
                                                    'contract'  => 'bg-info-transparent',
                                                ];

                                                $typeKey = strtolower($job->job_type);
                                                $badge   = $badgeMap[$typeKey] ?? 'bg-light';
                                                ?>
                                                <span class="badge <?= $badge ?> rounded-pill">
                                                    <?= ucfirst($job->job_type) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            No job data available
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Total Applications <span class="badge float-end bg-primary-transparent">
                            <?= number_format($applications['total']) ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="progress progress-md mb-4 mt-2">
                        <div class="progress progress-md mb-4 mt-2">
                            <div class="progress-bar bg-primary"
                                style="width: <?= $applications['percentages']['pending'] ?>%"></div>

                            <div class="progress-bar bg-secondary"
                                style="width: <?= $applications['percentages']['hired'] ?>%"></div>

                            <div class="progress-bar bg-success"
                                style="width: <?= $applications['percentages']['shortlisted'] ?>%"></div>

                            <div class="progress-bar bg-warning"
                                style="width: <?= $applications['percentages']['reviewed'] ?>%"></div>

                            <div class="progress-bar bg-danger"
                                style="width: <?= $applications['percentages']['rejected'] ?>%"></div>
                        </div>
                    </div>
                    <ul class="list-group acquisitions-list mt-1">
                        <li class="list-group-item">
                            Pending
                            <span class="badge float-end bg-warning-transparent">
                                <?= number_format($applications['counts']['pending']) ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            Hired
                            <span class="badge float-end bg-secondary-transparent">
                                <?= number_format($applications['counts']['hired']) ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            Shortlisted
                            <span class="badge float-end bg-success-transparent">
                                <?= number_format($applications['counts']['shortlisted']) ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            Rejected
                            <span class="badge float-end bg-danger-transparent">
                                <?= number_format($applications['counts']['rejected']) ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            Reviewed
                            <span class="badge float-end bg-info-transparent">
                                <?= number_format($applications['counts']['reviewed']) ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header">
                    <div class="card-title">
                        Last 5 Candidates
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">

                        <?php if (!empty($lastCandidates)) : ?>
                            <?php foreach ($lastCandidates as $candidate) : ?>

                                <?php
                                $initials = '';
                                if ($candidate->full_name) {
                                    $names = explode(' ', $candidate->full_name);
                                    $initials = strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
                                }

                                $experienceLabel = $candidate->experience_years > 0
                                    ? '+' . $candidate->experience_years . ' yrs Experience'
                                    : 'Fresher';
                                ?>

                                <li class="list-group-item border-start-0 border-end-0">
                                    <a href="<?= base_url('admin/candidates/' . $candidate->id) ?>">
                                        <div class="d-flex align-items-center flex-wrap">

                                            <!-- Avatar -->
                                            <div class="me-2 lh-1">
                                                <span class="avatar avatar-md avatar-rounded bg-primary-transparent">
                                                    <?= esc($initials) ?>
                                                </span>
                                            </div>

                                            <!-- Candidate Info -->
                                            <div class="flex-fill">
                                                <p class="mb-0 fw-semibold">
                                                    <?= esc($candidate->full_name) ?>
                                                </p>
                                                <p class="fs-12 text-muted mb-0">
                                                    Joined <?= Time::parse($candidate->created_at)->humanize() ?>
                                                </p>
                                            </div>

                                            <!-- Meta -->
                                            <div class="text-end">
                                                <p class="mb-0 fs-12">
                                                    <?= ucfirst($candidate->employment_type ?? '—') ?>
                                                </p>
                                                <span class="badge bg-info-transparent">
                                                    <?= esc($experienceLabel) ?>
                                                </span>
                                            </div>

                                        </div>
                                    </a>
                                </li>

                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="list-group-item text-center text-muted">
                                No recent candidates
                            </li>
                        <?php endif; ?>

                    </ul>

                </div>
            </div>
        </div>
    </div>
    <!-- End:: row-3 -->

    <!-- Start:: row-4 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Recent Job Postings
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="dropdown">
                            <a href="<?= base_url('admin/jobs') ?>" class="btn btn-primary btn-sm btn-wave waves-effect waves-light"> View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Job Title</th>
                                    <th>Department</th>
                                    <th>Company Name</th>
                                    <th>Location</th>
                                    <th>Applications</th>
                                    <th>Status</th>
                                    <th>Posted By</th>
                                    <th>Posted Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentJobs)) : ?>
                                    <?php foreach ($recentJobs as $index => $job) : ?>
                                        <tr>
                                            <td><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></td>

                                            <td><?= esc($job->title) ?></td>

                                            <td><?= esc($job->industry_name ?? '—') ?></td>

                                            <td><?= esc($job->company_name ?? '—') ?></td>

                                            <td><?= esc($job->state_name ?? 'Remote') ?></td>

                                            <td><?= number_format($job->applications_count) ?></td>

                                            <td>
                                                <?php if ($job->status === 'open') : ?>
                                                    <span class="badge bg-success-transparent">Active</span>
                                                <?php else : ?>
                                                    <span class="badge bg-danger-transparent">Closed</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="avatar avatar-xs avatar-rounded bg-primary-transparent">
                                                        <?= strtoupper(substr($job->company_name ?? 'C', 0, 1)) ?>
                                                    </span>
                                                    <span><?= esc($job->company_name ?? '—') ?></span>
                                                </div>
                                            </td>

                                            <td>
                                                <?= \CodeIgniter\I18n\Time::parse($job->created_at)->toDateString() ?>
                                            </td>

                                            <td>
                                                <div class="btn-list">
                                                    <a href="<?= base_url('admin/jobs/edit/' . $job->id) ?>"
                                                        class="btn btn-primary-light btn-icon btn-sm">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <button
                                                        data-id="<?= $job->id ?>"
                                                        class="btn btn-danger-light btn-icon btn-sm btn-delete-job">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            No recent job postings
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="card-footer border-top-0">
                    <div class="d-flex align-items-center flex-wrap">
                        <div> Total <?= count($recentJobs) ?> Entries <i class="bi bi-arrow-right ms-2 fw-semibold"></i> </div>
                        <div class="ms-auto">
                            <nav aria-label="Page navigation" class="pagination-style-2">
                                <ul class="pagination mb-0 flex-wrap">
                                    <li class="page-item">
                                        <a class="page-link text-primary" href="<?= base_url('admin/jobs') ?>">
                                            View All
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: row-4 -->

</div>
<!-- End::app-content -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    /* Candidates Overview */
    const options1 = {
        series: <?= json_encode($candidateChart['series']) ?>,
        labels: <?= json_encode($candidateChart['labels']) ?>,
        chart: {
            height: 236,
            type: 'donut',
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: true,
            position: "bottom",
            markers: {
                size: 5
            }
        },
        stroke: {
            show: true,
            curve: 'smooth',
            lineCap: 'round',
            colors: "#fff",
            width: 0,
            dashArray: 0,
        },
        plotOptions: {
            pie: {
                expandOnClick: false,
                donut: {
                    size: '85%',
                    background: 'transparent',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '20px',
                            color: '#495057',
                            offsetY: -5
                        },
                        value: {
                            show: true,
                            fontSize: '22px',
                            color: undefined,
                            offsetY: 5,
                            fontWeight: 600,
                            formatter: function(val) {
                                return val + "%"
                            }
                        },
                        total: {
                            show: true,
                            showAlways: true,
                            label: 'Total Candidates',
                            fontSize: '14px',
                            fontWeight: 400,
                            color: '#495057',
                        }
                    }
                }
            }
        },


        colors: ["var(--primary-color)", "rgb(253, 175, 34)"],
    };
    const chart1 = new ApexCharts(document.querySelector("#candidates-overview"), options1);
    if (chart1) chart1.render();
    /* Candidates Overview */

    const myElement1 = document.getElementById('recent-jobs');
    if (myElement1) {
        new SimpleBar(myElement1, {
            autoHide: true
        });
    }

    /* Employees Performance */
    const options = {
        series: [{
                name: "Weekly",
                type: "column",
                data: <?= json_encode($employeePerformance['weekly']) ?>,
            },
            {
                name: "Monthly",
                type: "area",
                data: <?= json_encode($employeePerformance['monthly']) ?>,
            },
            {
                name: "Daily",
                type: "line",
                data: <?= json_encode($employeePerformance['daily']) ?>,
            },
        ],
        chart: {
            height: 378,
            type: "line",
            stacked: false,
            toolbar: {
                show: false,
            },
            dropShadow: {
                enabled: true,
                enabledOnSeries: undefined,
                top: 5,
                left: 0,
                blur: 3,
                color: ["transparent", '#000', '#000'],
                opacity: 0.15
            },
        },
        stroke: {
            width: [0, 1, 2],
            curve: ["smooth", "stepline", "smooth"],
        },
        plotOptions: {
            bar: {
                columnWidth: "25%",
                borderRadius: 2,
            },
        },
        colors: ["var(--primary-color)", "#fdaf22", "rgb(255, 73, 205)"],
        fill: {
            opacity: [1, 0.05, 1],
            gradient: {
                inverseColors: false,
                shade: "light",
                type: "vertical",
                opacityFrom: 0.85,
                opacityTo: 0.55,
                stops: [0, 100, 100, 100],
            },
        },
        grid: {
            show: true,
            borderColor: 'rgba(119, 119, 142, 0.08)',
            strokeDashArray: 3,
        },
        legend: {
            show: true,
            position: "top",
            horizontalAlign: "right",
            fontSize: "11px",
            fontFamily: "Helvetica, Arial",
            fontWeight: 600,
            labels: {
                colors: '#74767c',
            },
            markers: {
                size: 4,
                strokeWidth: 0,
                strokeColor: "#fff",
                fillColors: undefined,
                radius: 12,
                customHTML: undefined,
                onClick: undefined,
                offsetX: 0,
                offsetY: 0,
            },
        },
        markers: {
            size: 0,
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            axisBorder: {
                show: false,
                color: 'rgba(119, 119, 142, 0.05)',
            },
            axisTicks: {
                show: false,
                color: 'rgba(119, 119, 142, 0.05)',
            },
            labels: {
                style: {
                    colors: "#8c9097",
                    fontSize: '11px',
                    fontWeight: 600,
                    cssClass: 'apexcharts-xaxis-label',
                },
            }
        },
        yaxis: {
            title: {
                style: {
                    color: '#adb5be',
                    fontSize: '14px',
                    fontWeight: 600,
                    cssClass: 'apexcharts-yaxis-label',
                },
            },
            labels: {
                show: true,
                style: {
                    colors: "#8c9097",
                    fontSize: '11px',
                    fontWeight: 600,
                    cssClass: 'apexcharts-xaxis-label',
                },
            },
            min: 0,
        },
    };
    const chart = new ApexCharts(document.querySelector("#employees-performance"), options);
    if (chart) chart.render();
    /* Employees Performance */
</script>
<?= $this->endSection() ?>