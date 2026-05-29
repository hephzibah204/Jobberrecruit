<?= $this->extend('templates/base') ?>
<?= $this->section('schema') ?>
<?php
include_once APPPATH . 'Views/partials/schema/job_posting.php';
$jobSchema = jobPostingSchema($job, base_url());
$jobSchema['identifier'] = [
    '@type' => 'PropertyValue',
    'name'  => 'JobberRecruit',
    'value' => 'JR-' . $job->id,
];
?>
<script type="application/ld+json">
<?= json_encode($jobSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>

<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url()],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Jobs', 'item' => base_url('jobs')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $job->title, 'item' => current_url()],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
$applyBtn = '';
$stickyBtn = '';

// Default: internal form
// $defaultUrl = base_url('apply/' . $job->id);
$trackUrl = base_url("job/start-application/{$job->id}");
$defaultLabel = 'Apply Now';
$defaultIcon = 'bi-arrow-right';

switch ($job->application_method ?? 'form') {
    case 'whatsapp':
        $url = esc($job->whatsapp_link, 'url');
        $label = 'Apply via WhatsApp';
        $icon = 'bi-whatsapp';
        $btnClass = 'btn-success';
        $target = '_blank';
        break;

    case 'email':
        $email = esc($job->application_email ?? $job->contact_email);
        $subject = rawurlencode("Application: {$job->title}");
        $url = "mailto:{$email}?subject={$subject}";
        $label = 'Apply via Email';
        $icon = 'bi-envelope';
        $btnClass = 'btn-info';
        $target = '';
        break;

    case 'external':
        $url = esc($job->external_url, 'url');
        $label = 'Apply on External Site';
        $icon = 'bi-box-arrow-up-right';
        $btnClass = 'btn-warning';
        $target = '_blank';
        break;

    case 'form':
    default:
        $url = $trackUrl;
        $label = $defaultLabel;
        $icon = $defaultIcon;
        $btnClass = 'btn-primary';
        $target = '';
        break;
}
$targetAttr = $target ? "target='_blank' rel='noopener'" : '';
// Top header button
$applyBtn = <<<HTML
<a href="{$trackUrl}" class="btn {$btnClass} btn-sm" {$targetAttr}>
    {$label} <i class="bi {$icon} ms-1"></i>
</a>
HTML;

$stickyBtn = <<<HTML
<a href="{$trackUrl}" class="btn {$btnClass} btn-lg shadow" {$targetAttr}>
    {$label} <i class="bi {$icon} ms-1"></i>
</a>
HTML;
?>
<section class="job-details-section py-5 bg-light">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('jobs') ?>">Jobs</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= esc($job->title) ?></li>
            </ol>
        </nav>
        <div class="row g-4">
            <!-- ===== LEFT COLUMN: Job Description ===== -->
            <div class="col-lg-8">
                <div class="glass-card p-4 mb-4 position-relative">
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?= !empty($job->anonymous) || !empty($job->is_anonymous) ? base_url('images/favicon.png') : $job->company_logo ?>" alt="<?= !empty($job->anonymous) || !empty($job->is_anonymous) ? 'Anonymous Employer' : esc($job->employer_name) ?> Logo" class="rounded me-3" width="80" height="80" style="object-fit: cover; border: 1px solid var(--border);">
                        <div>
                            <h1 class="fw-bold mb-1 text-gradient h3"><?= esc($job->title) ?></h1>
                            <p class="mb-0 text-muted fs-6">
                                 by <?php if (!empty($job->anonymous) || !empty($job->is_anonymous)): ?>
                                    <span class="fw-semibold">Confidential Employer</span>
                                <?php else: ?>
                                    <a href="<?= base_url('employer/' . $job->employer_id) ?>" class="text-decoration-none">
                                        <span class="fw-semibold"><?= esc($job->employer_name) ?></span>
                                        <?php if ($job->show_trust_badge): ?>
                                            <span class="badge-verified ms-1" title="Verified Employer">
                                                <i class="bi bi-patch-check-fill"></i>
                                            </span>
                                        <?php endif; ?>
                                    </a>
                                <?php endif; ?>
                                <span class="badge bg-success-subtle text-success fw-medium ms-2"><?= strtoupper(esc($job->job_type)) ?></span>
                                <?php if ($job->featured): ?>
                                    <span class="badge bg-primary-subtle text-primary fw-medium ms-1">Featured</span>
                                <?php endif; ?>
                                <?php if (!empty($job->is_verified)): ?>
                                    <span class="badge-verified ms-1">
                                        <i class="bi bi-patch-check-fill"></i> Verified Listing
                                    </span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <!-- TOP HEADER BUTTON -->
                    <div class="d-flex justify-content-end gap-2">
                        <button
                            id="saveJobBtn"
                            data-job-id="<?= $job->id ?>"
                            class="btn <?= $isSaved ? 'btn-danger' : 'btn-border' ?>">
                            <?= $isSaved ? 'Unsave Job' : 'Save Job' ?>
                        </button>
                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reportJobModal">
                            <i class="bi bi-exclamation-triangle me-1"></i> Report
                        </button>
                        <?= $applyBtn ?>
                    </div>
                </div>

                <!-- Report Job Modal -->
                <div class="modal fade" id="reportJobModal" tabindex="-1" aria-labelledby="reportJobModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title fw-bold" id="reportJobModalLabel">Report this Job</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="report-job-form">
                                <input type="hidden" name="job_id" value="<?= $job->id ?>">
                                <div class="modal-body">
                                    <p class="text-muted small mb-3">Is there something wrong with this job post? Let us know. Your report helps keep JobberRecruit safe.</p>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Reason for reporting</label>
                                        <select name="reason" class="form-select" required>
                                            <option value="">Select a reason</option>
                                            <option value="scam">It's a scam or fraudulent</option>
                                            <option value="offensive">Offensive or inappropriate content</option>
                                            <option value="misleading">Misleading or inaccurate information</option>
                                            <option value="expired">Job is already expired/filled</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Additional Details (Optional)</label>
                                        <textarea name="details" class="form-control" rows="3" placeholder="Provide more context..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-warning px-4" id="btn-submit-report">Submit Report</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="glass-card p-4 mb-4 text-wrap">
                    <!-- Job Description -->
                    <h5 class="fw-semibold mb-3">Job Description</h5>
                    <div class="text-muted">
                        <?= $job->description ? $job->description : '<p>No job description provided.</p>' ?>
                    </div>

                    <!-- Requirements -->
                    <?php if (!empty($job->requirements)): ?>
                        <h6 class="fw-semibold mt-4 mb-3">Requirements</h6>
                        <div class="text-muted">
                            <?= $job->requirements ?>
                        </div>
                    <?php endif; ?>

                    <!-- Application Guidelines -->
                    <?php if (!empty($job->application)): ?>
                        <h6 class="fw-semibold mt-4 mb-3">Application Guidelines</h6>
                        <div class="text-muted">
                            <?= $job->application ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($job->application_method === 'whatsapp' && !empty($job->whatsapp_link)): ?>
                    <p class="mt-3 mb-0">
                        <a href="<?= $trackUrl ?>" target="_blank" class="btn btn-success btn-sm">
                            <i class="bi bi-whatsapp"></i> Apply via WhatsApp
                        </a>
                    </p>
                <?php elseif ($job->application_method === 'email' && !empty($job->application_email)): ?>
                    <p class="mt-3 mb-0">
                        <a href="<?= $trackUrl ?>" class="btn btn-info btn-sm">
                            <i class="bi bi-envelope"></i> Send Email
                        </a>
                    </p>
                <?php elseif ($job->application_method === 'external' && !empty($job->external_url)): ?>
                    <p class="mt-3 mb-0">
                        <a href="<?= $trackUrl ?>" target="_blank" class="btn btn-warning btn-sm">
                            <i class="bi bi-box-arrow-up-right"></i> Apply Externally
                        </a>
                    </p>
                <?php endif; ?>

                <!-- Related Jobs -->
                <?php if (!empty($related_jobs)): ?>
                    <div class="glass-card p-4">
                        <h5 class="fw-semibold mb-3">Related Jobs</h5>
                        <div class="row g-3">
                            <?php foreach ($related_jobs as $related): ?>
                                <div class="col-md-6">
                                    <a href="<?= base_url('jobs/' . $related->slug) ?>" class="text-decoration-none">
                                        <div class="job-card p-3 bg-light rounded-3 transition-all">
                                            <h6 class="fw-semibold mb-1 fs-6"><?= esc($related->title) ?></h6>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-geo-alt me-1"></i><?= esc($related->location) ?>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ===== RIGHT COLUMN: Sidebar ===== -->
            <div class="col-lg-4">
                <!-- Job Overview -->
                <div class="glass-card p-4 mb-4">
                    <h6 class="fw-semibold mb-3">Job Overview</h6>
                    <ul class="list-unstyled fs-6 text-muted">
                        <li class="mb-3">
                            <i class="bi bi-calendar me-2 text-primary"></i>
                            <strong>Posted:</strong> <?= esc($job->formatted_created_at) ?>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-clock me-2 text-primary"></i>
                            <strong>Status:</strong> <?= esc(ucfirst($job->status)) ?>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-briefcase me-2 text-primary"></i>
                            <strong>Level:</strong> <?= ucfirst(esc($job->experience_level)) ?>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-cash me-2 text-primary"></i>
                            <strong>Salary:</strong> <?= esc($job->salary_range) ?>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-mortarboard me-2 text-primary"></i>
                            <strong>Education:</strong> <?= esc(ucfirst($job->education_level) ?? 'Not specified') ?>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-building me-2 text-primary"></i>
                            <strong>Accommodation:</strong> <?= esc($job->accommodation === 'available' ? 'Available' : 'Not Available') ?>
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-send me-2 text-primary"></i>
                            <strong>Apply Via:</strong>
                            <?php
                            $methodLabel = ucfirst($job->application_method ?? 'form');
                            switch ($job->application_method ?? 'form') {
                                case 'whatsapp':
                                    $methodLabel = 'WhatsApp';
                                    break;
                                case 'email':
                                    $methodLabel = 'Email';
                                    break;
                                case 'external':
                                    $methodLabel = 'External Link';
                                    break;
                                case 'form':
                                default:
                                    $methodLabel = 'Application Form';
                                    break;
                            }
                            echo $methodLabel;
                            ?>
                        </li>
                    </ul>
                </div>

                <?php if ($job->anonymous === false): ?>
                    <!-- Company Overview -->
                    <div class="glass-card p-4 mb-4">
                        <h6 class="fw-semibold mb-3">About <?= esc($job->employer_name) ?></h6>
                        <ul class="list-unstyled fs-6 text-muted">
                            <li class="mb-3">
                                <i class="bi bi-geo-alt me-2 text-primary"></i>
                                <strong>Location:</strong> <?= esc($job->company_address ?? 'Not provided') ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-telephone me-2 text-primary"></i>
                                <strong>Phone:</strong> <?= esc($job->company_phone ?? 'Not provided') ?>
                            </li>
                            <li class="mb-3">
                                <i class="bi bi-envelope me-2 text-primary"></i>
                                <strong>Email:</strong> <?= esc($job->company_email ?? 'Not provided') ?>
                            </li>
                            <?php if ($job->company_website): ?>
                                <li class="mb-3">
                                    <i class="bi bi-globe me-2 text-primary"></i>
                                    <strong>Website:</strong> <a href="<?= esc($job->company_website) ?>" target="_blank" class="text-primary"><?= esc($job->company_website) ?></a>
                                </li>
                            <?php endif; ?>
                            <li class="mb-0">
                                <i class="bi bi-briefcase me-2 text-primary"></i>
                                <strong>Open Positions:</strong> <?= $employer_job_count ?>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Share Job -->
                <div class="glass-card p-4">
                    <h6 class="fw-semibold mb-3">Share this Job</h6>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <a class="btn btn-outline-secondary btn-sm" id="copyLink" title="Copy Job Link">
                            <i class="bi bi-link-45deg"></i> Copy Link
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(current_url()) ?>" class="btn btn-sm" style="background: #0A66C2; color: white; border: none;" target="_blank" title="Share on LinkedIn">
                            <i class="bi bi-linkedin"></i> LinkedIn
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" class="btn btn-sm" style="background: #1877F2; color: white; border: none;" target="_blank" title="Share on Facebook">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($job->title) ?>" class="btn btn-sm" style="background: #000000; color: white; border: none;" target="_blank" title="Share on X">
                            <i class="bi bi-twitter-x"></i> X
                        </a>
                        <a href="mailto:?subject=Check out this job: <?= urlencode($job->title) ?>&body=<?= urlencode(current_url()) ?>" class="btn btn-outline-secondary btn-sm" title="Share via Email">
                            <i class="bi bi-envelope"></i> Email
                        </a>
                    </div>
                </div>
            </div>

            <!-- STICKY BUTTON (bottom-right) -->
            <div class="sticky-apply-btn position-fixed bottom-0 end-0 m-4 d-none d-lg-block">
                <?= $stickyBtn ?>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .job-details-section {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .job-header {
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }

    .job-header:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .job-description {
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }

    .job-description .text-muted {
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .job-description img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1rem 0;
        border-radius: 6px;
    }

    .job-description table {
        width: 100%;
        border-collapse: collapse;
        overflow-x: auto;
        display: block;
    }

    .job-description table td,
    .job-description table th {
        border: 1px solid #dee2e6;
        padding: 0.5rem;
    }

    .job-description pre,
    .job-description code {
        white-space: pre-wrap;
        word-break: break-word;
        background: #f8f9fa;
        padding: 0.5rem;
        border-radius: 6px;
        display: block;
        overflow-x: auto;
    }

    .job-description p {
        margin-bottom: 1rem;
    }

    .job-card {
        transition: all 0.3s ease;
    }

    .job-card:hover {
        transform: translateY(-3px);
        background-color: #f1f3f5 !important;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .shadow-sm {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .sticky-apply-btn {
        z-index: 1000;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-2px);
    }

    .bookmark-btn:hover i {
        color: #007bff;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .job-description * {
        max-width: 100% !important;
        box-sizing: border-box;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Copy job link to clipboard with toast notification
    document.getElementById('copyLink')?.addEventListener('click', () => {
        navigator.clipboard.writeText(window.location.href).then(() => {
            // Create toast notification
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 m-4 p-3 bg-success text-white rounded-3 shadow';
            toast.style.zIndex = '2000';
            toast.textContent = 'Job link copied to clipboard!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }).catch(() => {
            toastr.error('Failed to copy link. Please try again.');
        });
    });

    $("#saveJobBtn").on("click", function() {
        let btn = $(this);
        let jobId = btn.data("job-id");

        btn.prop("disabled", true).text("Processing...");

        $.ajax({
            url: "<?= site_url('jobs/toggle-save') ?>/" + jobId,
            method: "POST",
            success: function(response) {
                if (response.success) {
                    if (response.saved) {
                        btn.removeClass("btn-border").addClass("btn-danger").text("Unsave Job");
                    } else {
                        btn.removeClass("btn-danger").addClass("btn-border").text("Save Job");
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            complete: function() {
                btn.prop("disabled", false);
                btn.removeClass("btn-danger").addClass("btn-border").text("Save Job");
            },
            error: function() {
                toastr.error("Network error. Try again.");
                btn.prop("disabled", false);
            }
        });
    });

    $('#report-job-form').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btn-submit-report');
        const modal = bootstrap.Modal.getInstance(document.getElementById('reportJobModal'));
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
        
        $.ajax({
            url: '<?= base_url('jobs/report') ?>',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                toastr.success(response.message);
                modal.hide();
                $('#report-job-form')[0].reset();
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response ? response.messages.error : 'An error occurred');
            },
            complete: function() {
                btn.prop('disabled', false).text('Submit Report');
            }
        });
    });
</script>
<?= $this->endSection() ?>