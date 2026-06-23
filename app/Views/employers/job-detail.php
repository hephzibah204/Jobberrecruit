<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Viewing Job:</h4>
                <h6><?= esc($job->title) ?></h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="<?= site_url('employer/jobs') ?>" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>My Jobs</a>
        </div>
    </div>

    <div class="row">
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>
                <strong>Available Job Credits:</strong>
                <?= number_format($creditBalance, 0) ?>
            </div>
            <a href="<?= base_url('employer/bundles') ?>" class="btn btn-sm btn-primary">
                Buy Credits
            </a>
        </div>
        <div class="col-xl-4 theiaStickySidebar">
            <div class="card rounded-0 border-0">
                <div class="card-header rounded-0 bg-primary d-flex align-items-center">
                    <span class="avatar avatar-xl avatar-rounded flex-shrink-0 border border-white border-3 me-3">
                        <img src="<?= !empty($employer->logo)
                                        ? base_url($employer->logo)
                                        : base_url('images/default-company.png') ?>" alt="<?= esc($employer->company_name ?? 'Company') ?>" class="Logo">
                    </span>
                    <div class="me-3">
                        <h6 class="text-white mb-1"><?= esc($employer->company_name ?? 'Company Name') ?></h6>
                        <span class="badge bg-purple-transparent text-purple"><?= esc($job->job_type) ?></span>
                    </div>
                    <div>
                        <a href="<?= site_url("employer/jobs/edit/{$job->id}") ?>" class="btn btn-white">Edit Job</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="d-inline-flex align-items-center">
                            <i class="ti ti-id me-2"></i>
                            Job ID
                        </span>
                        <p class="text-dark">JOB-<?= str_pad($job->id, 4, '0', STR_PAD_LEFT) ?></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="d-inline-flex align-items-center">
                            <i class="ti ti-calendar me-2"></i>
                            Posted Date
                        </span>
                        <p class="text-dark"><?= date('M j, Y', strtotime($job->created_at)) ?></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="d-inline-flex align-items-center">
                            <i class="ti ti-calendar-check me-2"></i>
                            Application Deadline
                        </span>
                        <p class="text-dark">
                            <?php if (!empty($job->application_deadline) && $job->application_deadline !== '0000-00-00'): ?>
                                <?= date('M j, Y', strtotime($job->application_deadline)) ?>
                            <?php else: ?>
                                Not specified
                            <?php endif; ?>
                        </p>

                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="d-inline-flex align-items-center">
                            <i class="ti ti-eye me-2"></i>
                            Views
                        </span>
                        <p class="text-dark"><?= $job->views ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card rounded-0 border-0 mt-3">
                <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                    <h6>Job Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="d-inline-flex align-items-center">
                            <i class="ti ti-briefcase me-2 text-primary"></i>
                            Applications
                        </span>
                        <span class="badge bg-primary"><?= $applicationCount ?></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="d-inline-flex align-items-center">
                            <i class="ti ti-click me-2 text-success"></i>
                            Total Clicks
                        </span>
                        <span class="badge bg-success"><?= $totalClicks ?></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="d-inline-flex align-items-center">
                            <i class="ti ti-star me-2 text-warning"></i>
                            Status
                        </span>
                        <span class="badge <?= $job->status === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= ucfirst($job->status) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="card mt-4 p-2">
                <div class="card-header">
                    <h6 class="mb-0">Job Features</h6>
                </div>
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Featured Job</span>
                            <?php if ($job->is_featured): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Not Featured</span>
                            <?php endif; ?>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Anonymous Post?</span>
                            <?php if ($job->is_anonymous): ?>
                                <span class="badge bg-success">Yes</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                            <?php endif; ?>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Current Plan</span>
                            <?php if ($activeSubscription): ?>
                                <span class="badge bg-success"><?= $activeSubscription['plan_name'] ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No Plan Yet.</span>
                            <?php endif; ?>
                        </li>

                        <?php if ($job->is_featured && $job->featured_until): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Featured Until</span>
                                <span class="text-muted">
                                    <?= date('M d, Y', strtotime($job->featured_until)) ?>
                                </span>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>

                <?php if (!empty($features['featured']) && !$job->is_featured): ?>
                    <button class="btn btn-primary btn-md mt-3"
                        data-bs-toggle="modal"
                        data-id="<?= $job->id ?>"
                        data-title="<?= esc($job->title, 'attr') ?>"
                        data-status="<?= $job->is_featured ? 'on' : 'off' ?>"
                        data-bs-target="#promoteModal">
                        <i class="ti ti-star"></i> Promote Job
                    </button>
                <?php endif; ?>

                <?php if ($job->is_featured): ?>
                    <button class="btn btn-outline-danger btn-md mt-2 stop-featured-btn"
                        data-id="<?= $job->id ?>"
                        data-title="<?= esc($job->title, 'attr') ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#stopFeaturedModal">
                        <i class="ti ti-star-off"></i> Stop Featuring
                    </button>
                <?php endif; ?>

                <?php if (!empty($features['anonymous'])): ?>
                    <button class="btn btn-secondary btn-md mt-2 toggle-anonymous-btn"
                        data-id="<?= $job->id ?>"
                        data-title="<?= esc($job->title, 'attr') ?>"
                        data-status="<?= $job->is_anonymous ? 'on' : 'off' ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#anonymousModal">
                        <?= $job->is_anonymous ? 'Disable Anonymous' : 'Enable Anonymous' ?>
                    </button>
                <?php endif; ?>

            </div>
        </div>

        <div class="col-xl-8">
            <!-- Basic Job Information -->
            <div class="card rounded-0 border-0">
                <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                    <h6>Job Information</h6>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Job Title</p>
                                <span class="text-gray-900 fs-13 fw-semibold"><?= esc($job->title) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Job Type</p>
                                <span class="text-gray-900 fs-13"><?= esc(ucfirst($job->job_type)) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Location Type</p>
                                <span class="text-gray-900 fs-13"><?= esc(ucfirst(str_replace('_', ' ', $job->location_type))) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Location</p>
                                <span class="text-gray-900 fs-13"><?= esc($job->location ?? 'Not specified') ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Category</p>
                                <span class="text-gray-900 fs-13"><?= esc($job->category_name ?? 'Not specified') ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Industry</p>
                                <span class="text-gray-900 fs-13"><?= esc($job->industry_name ?? 'Not specified') ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Experience Level</p>
                                <span class="text-gray-900 fs-13"><?= esc(ucfirst($job->experience_level)) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Education Level</p>
                                <span class="text-gray-900 fs-13"><?= esc(ucfirst(str_replace('_', ' ', $job->education_level))) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Information -->
            <div class="card rounded-0 border-0 mt-3">
                <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                    <h6>Salary Information</h6>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Salary Type</p>
                                <span class="text-gray-900 fs-13"><?= esc(ucfirst($job->salary_type)) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Salary Period</p>
                                <span class="text-gray-900 fs-13"><?= esc(ucfirst($job->salary_period)) ?></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Salary</p>
                                <span class="text-gray-900 fs-13 fw-semibold">
                                    <?php if ($job->salary): ?>
                                        <?= ($job->salary) ?>
                                    <?php else: ?>
                                        Not specified
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Description -->
            <div class="card rounded-0 border-0 mt-3">
                <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                    <h6>Job Description</h6>
                </div>
                <div class="card-body">
                    <p><?= nl2br(($job->description)) ?></p>
                </div>
            </div>

            <!-- Requirements -->
            <div class="card rounded-0 border-0 mt-3">
                <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                    <h6>Requirements</h6>
                </div>
                <div class="card-body">
                    <?php if ($job->requirements): ?>
                        <p><?= nl2br(($job->requirements)) ?></p>
                    <?php else: ?>
                        <p class="text-muted">No specific requirements listed.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Skills -->
            <div class="card rounded-0 border-0 mt-3">
                <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                    <h6>Required Skills</h6>
                </div>
                <div class="card-body">
                    <?php if ($job->skills): ?>
                        <?php
                        $skills = explode(',', $job->skills);
                        foreach ($skills as $skill):
                            $skill = trim($skill);
                            if (!empty($skill)):
                        ?>
                                <span class="badge bg-primary-transparent text-primary me-2 mb-2"><?= esc($skill) ?></span>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    <?php else: ?>
                        <p class="text-muted">No specific skills listed.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Application Information -->
            <div class="card rounded-0 border-0 mt-3">
                <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                    <h6>Application Information</h6>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="fs-13 mb-2">Application Method</p>
                                <span class="text-gray-900 fs-13"><?= esc(ucfirst($job->application_method)) ?></span>
                            </div>
                        </div>
                        <?php if ($job->application_method === 'email' && $job->application_email): ?>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <p class="fs-13 mb-2">Application Email</p>
                                    <span class="text-gray-900 fs-13"><?= esc($job->application_email) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($job->application_method === 'external' && $job->external_url): ?>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <p class="fs-13 mb-2">External URL</p>
                                    <span class="text-gray-900 fs-13"><?= esc($job->external_url) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($job->application_method === 'whatsapp' && $job->whatsapp_link): ?>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <p class="fs-13 mb-2">WhatsApp Link</p>
                                    <span class="text-gray-900 fs-13"><?= ($job->whatsapp_link) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="stopFeaturedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h5 class="modal-title">Stop Featured Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center py-4">
                <i class="ti ti-alert-triangle text-danger fs-1 mb-3"></i>

                <h6>
                    Stop featuring "<span id="stopFeaturedJobTitle"></span>"?
                </h6>

                <p class="text-muted mt-2">
                    This job will immediately lose its featured visibility.<br>
                    <strong>Job credits will not be refunded.</strong>
                </p>
            </div>

            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button" class="btn btn-danger" id="confirmStopFeaturedBtn">
                    <span class="btn-text">Yes, Stop Featuring</span>
                    <span class="spinner-border spinner-border-sm d-none ms-2"></span>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Promote Confirmation Modal -->
<div class="modal fade" id="promoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Promote Job as Featured</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="ti ti-star text-warning fs-1 mb-3"></i>
                <h6>Promote "<span id="promoteJobTitle"></span>"?</h6>
                <p class="text-muted">
                    This job will be highlighted as <strong>Featured</strong> for 30 days.<br>
                    It will appear at the top of search results and gain more visibility.
                </p>
                <small class="text-danger">
                    This action will deduct <strong>5 Job Credits</strong>.
                </small>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmPromoteBtn">
                    <span class="btn-text">Yes, Promote Job</span>
                    <span class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="anonymousModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Anonymous Job Posting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center py-4">
                <i class="ti ti-eye-off text-secondary fs-1 mb-3"></i>

                <h6 id="anonymousModalTitle"></h6>

                <p class="text-muted" id="anonymousModalText"></p>

                <small class="text-danger d-none" id="anonymousCostNote">
                    This action will deduct <strong>5 Job Credits</strong>.
                </small>
            </div>

            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button" class="btn btn-primary" id="confirmAnonymousBtn">
                    <span class="btn-text">Confirm</span>
                    <span class="spinner-border spinner-border-sm d-none ms-2"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Sticky Sidebar JS -->
<script src="<?= base_url('auth/plugins/theia-sticky-sidebar/ResizeSensor.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') ?>" type="text/javascript"></script>
<script>
    // Initialize toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000
    };

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Promote job
    let jobToPromote = null;

    $(document).on('show.bs.modal', '#promoteModal', function(event) {
        const button = $(event.relatedTarget);
        jobToPromote = {
            id: button.data('id'),
            title: button.data('title')
        };
        $('#promoteJobTitle').text(jobToPromote.title);
    });

    let jobToStopFeature = null;

    // Open modal and bind job data
    $(document).on('show.bs.modal', '#stopFeaturedModal', function(event) {
        const button = $(event.relatedTarget);

        jobToStopFeature = {
            id: button.data('id'),
            title: button.data('title')
        };

        $('#stopFeaturedJobTitle').text(jobToStopFeature.title);
    });

    // Confirm stop featured
    $('#confirmStopFeaturedBtn').on('click', function() {
        const btn = $(this);

        btn.prop('disabled', true);
        btn.find('.btn-text').addClass('d-none');
        btn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: '<?= site_url('employer/jobs/stop-featured') ?>/' + jobToStopFeature.id,
            type: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(res) {

                if (res.success) {
                    toastr.success(res.message || 'Job is no longer featured.');

                    $('#stopFeaturedModal').modal('hide');

                    // Optional: reload to update UI + counts
                    setTimeout(() => location.reload(), 1200);
                } else {
                    toastr.error(res.message || 'Unable to stop featuring job.');
                }
            },
            error: function() {
                toastr.error('Network error. Please try again.');
            },
            complete: function() {
                btn.prop('disabled', false);
                btn.find('.btn-text').removeClass('d-none');
                btn.find('.spinner-border').addClass('d-none');
            }
        });
    });

    $('#confirmPromoteBtn').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();

        // Loading state
        btn.prop('disabled', true)
            .find('.btn-text').addClass('d-none')
            .end()
            .find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: '<?= site_url('employer/jobs/promote') ?>/' + jobToPromote.id,
            type: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message || 'Job promoted successfully!');

                    // Replace promote button with Featured badge
                    $(`button.promote-job-btn[data-id="${jobToPromote.id}"]`)
                        .replaceWith('<span class="badge bg-success px-3 py-2"><i class="ti ti-star me-1"></i> Featured</span>');

                    $('#promoteModal').modal('hide');

                    // Optional: refresh page to update remaining slots count
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(res.message || 'Failed to promote job.');
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.');
            },
            complete: function() {
                btn.prop('disabled', false)
                    .html(originalText);
            }
        });
    });

    // Anonymous toggle
    let jobToToggleAnonymous = null;

    $(document).on('show.bs.modal', '#anonymousModal', function(event) {
        const button = $(event.relatedTarget);

        jobToToggleAnonymous = {
            id: button.data('id'),
            title: button.data('title'),
            status: button.data('status') // on | off
        };

        const enabling = jobToToggleAnonymous.status === 'off';

        $('#anonymousModalTitle').text(
            enabling ?
            `Make "${jobToToggleAnonymous.title}" Anonymous?` :
            `Disable Anonymous for "${jobToToggleAnonymous.title}"?`
        );

        $('#anonymousModalText').text(
            enabling ?
            'Your company name and logo will be hidden from candidates.' :
            'Your company name and logo will be visible again.'
        );

        $('#anonymousCostNote').toggleClass('d-none', !enabling);
    });

    $('#confirmAnonymousBtn').on('click', function() {
        const btn = $(this);

        btn.prop('disabled', true)
            .find('.btn-text').addClass('d-none')
            .end()
            .find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: '<?= site_url('employer/jobs/toggle-anonymous') ?>/' + jobToToggleAnonymous.id,
            type: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message || 'Anonymous status updated');

                    $('#anonymousModal').modal('hide');

                    // Refresh page to reflect changes
                    setTimeout(() => location.reload(), 1200);
                } else {
                    toastr.error(res.message || 'Action failed');
                }
            },
            error: function() {
                toastr.error('Network error. Please try again.');
            },
            complete: function() {
                btn.prop('disabled', false)
                    .find('.btn-text').removeClass('d-none')
                    .end()
                    .find('.spinner-border').addClass('d-none');
            }
        });
    });
</script>
<?= $this->endSection() ?>