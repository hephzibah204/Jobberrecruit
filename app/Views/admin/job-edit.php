<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between flex-wrap align-items-center">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Edit Job Posting</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/jobs') ?>">Jobs</a></li>
                    <li class="breadcrumb-item active">Edit Job #<?= $job->id ?></li>
                </ol>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/jobs/preview/' . $job->id) ?>" target="_blank" class="btn btn-outline-primary">
                    <i class="ti ti-external-link me-1"></i>Preview Listing
                </a>
                <a href="<?= base_url('admin/jobs/view/' . $job->id) ?>" class="btn btn-light">
                    <i class="ti ti-eye me-1"></i>Admin Details
                </a>
            </div>
        </div>
    </div>

    <!-- FORM -->
    <form id="job-edit-form">
        <?= csrf_field() ?>

        <div class="row">
            <!-- LEFT MAIN COLUMN -->
            <div class="col-lg-8">
                <div class="accordion mb-3" id="jobEditAccordion">

                    <!-- JOB INFO -->
                    <div class="accordion-item mb-3 shadow-sm border-0 rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#jobInfo">
                                <i class="ti ti-briefcase text-primary me-2 fs-18"></i>Job Basic Information
                            </button>
                        </h2>
                        <div id="jobInfo" class="accordion-collapse collapse show">
                            <div class="accordion-body">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Employer / Company *</label>
                                    <select name="employer_id" class="form-select" required>
                                        <?php foreach ($employers as $emp): ?>
                                            <option value="<?= $emp->id ?>" <?= $job->employer_id == $emp->id ? 'selected' : '' ?>>
                                                <?= esc($emp->company_name) ?> (ID: #<?= $emp->id ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Job Title *</label>
                                        <input type="text" name="title" class="form-control" value="<?= esc($job->title) ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Job Type *</label>
                                        <select name="job_type" class="form-select" required>
                                            <?php foreach (['full-time', 'part-time', 'contract', 'freelance', 'internship'] as $t): ?>
                                                <option value="<?= $t ?>" <?= $job->job_type === $t ? 'selected' : '' ?>>
                                                    <?= ucwords(str_replace('-', ' ', $t)) ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">State / Location *</label>
                                        <select name="state_id" class="form-select" required>
                                            <?php foreach ($states as $state): ?>
                                                <option value="<?= $state->id ?>" <?= $job->state_id == $state->id ? 'selected' : '' ?>>
                                                    <?= esc($state->name) ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Location Type *</label>
                                        <select name="location_type" class="form-select" required>
                                            <option value="on-site" <?= $job->location_type === 'on-site' ? 'selected' : '' ?>>On-Site</option>
                                            <option value="hybrid" <?= $job->location_type === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                            <option value="remote" <?= $job->location_type === 'remote' ? 'selected' : '' ?>>Remote</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- SALARY -->
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Salary Type *</label>
                                        <select name="salary_type" id="salary_type" class="form-select" onchange="toggleSalary()" required>
                                            <option value="fixed" <?= $job->salary_type === 'fixed' ? 'selected' : '' ?>>Fixed Amount</option>
                                            <option value="range" <?= $job->salary_type === 'range' ? 'selected' : '' ?>>Salary Range</option>
                                            <option value="negotiable" <?= $job->salary_type === 'negotiable' ? 'selected' : '' ?>>Negotiable</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Salary Period *</label>
                                        <select name="salary_period" class="form-select" required>
                                            <option value="monthly" <?= $job->salary_period === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                            <option value="yearly" <?= $job->salary_period === 'yearly' ? 'selected' : '' ?>>Yearly</option>
                                            <option value="hourly" <?= $job->salary_period === 'hourly' ? 'selected' : '' ?>>Hourly</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3" id="salary_box">
                                        <label class="form-label fw-semibold">Salary Amount (₦)</label>
                                        <input type="text" name="salary" class="form-control" value="<?= esc($job->salary) ?>" placeholder="e.g. 250,000">
                                    </div>
                                </div>

                                <!-- INDUSTRY + CATEGORY -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Industry *</label>
                                        <select name="industry_id" class="form-select" required>
                                            <?php foreach ($industries as $i): ?>
                                                <option value="<?= $i->id ?>" <?= $job->industry_id == $i->id ? 'selected' : '' ?>>
                                                    <?= esc($i->name) ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Category *</label>
                                        <select name="category_id" class="form-select" required>
                                            <?php foreach ($categories as $c): ?>
                                                <option value="<?= $c->id ?>" <?= $job->category_id == $c->id ? 'selected' : '' ?>>
                                                    <?= esc($c->name) ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="accordion-item mb-3 shadow-sm border-0 rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#jobDesc">
                                <i class="ti ti-file-text text-info me-2 fs-18"></i>Description & Requirements
                            </button>
                        </h2>
                        <div id="jobDesc" class="accordion-collapse collapse show">
                            <div class="accordion-body">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Job Description *</label>
                                    <div id="desc-editor" style="height:220px;"></div>
                                    <input type="hidden" name="description" id="desc-input" value="<?= esc($job->description) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold mt-2">Requirements & Qualifications</label>
                                    <div id="req-editor" style="height:180px;"></div>
                                    <input type="hidden" name="requirements" id="req-input" value="<?= esc($job->requirements) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold mt-2">Required Skills (Comma-separated)</label>
                                    <input type="text" name="skills" id="skills-input" class="form-control" value="<?= esc($job->skills) ?>">
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- APPLICATION METHOD -->
                    <div class="accordion-item mb-3 shadow-sm border-0 rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#applicationBox">
                                <i class="ti ti-send text-success me-2 fs-18"></i>Application Instructions & Deadline
                            </button>
                        </h2>
                        <div id="applicationBox" class="accordion-collapse collapse show">
                            <div class="accordion-body">

                                <label class="form-label fw-semibold">Application Method *</label>
                                <?php $method = $job->application_method ?? 'form' ?>
                                <div class="d-flex gap-3 flex-wrap mb-3">
                                    <?php foreach (['form' => 'JobberRecruit Platform Form', 'whatsapp' => 'WhatsApp Link', 'email' => 'Direct Email', 'external' => 'External Website'] as $mKey => $mLabel): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="application_method" value="<?= $mKey ?>" id="method_<?= $mKey ?>" <?= $method === $mKey ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="method_<?= $mKey ?>"><?= $mLabel ?></label>
                                        </div>
                                    <?php endforeach ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 conditional-field" data-method="whatsapp">
                                        <label class="form-label fw-semibold">WhatsApp Link *</label>
                                        <input type="url" name="whatsapp_link" class="form-control" value="<?= esc($job->whatsapp_link) ?>" placeholder="https://wa.me/234...">
                                    </div>

                                    <div class="col-md-6 conditional-field" data-method="email">
                                        <label class="form-label fw-semibold">Application Email *</label>
                                        <input type="email" name="application_email" class="form-control" value="<?= esc($job->application_email) ?>" placeholder="careers@company.com">
                                    </div>

                                    <div class="col-md-6 conditional-field" data-method="external">
                                        <label class="form-label fw-semibold">External Application URL *</label>
                                        <input type="url" name="external_url" class="form-control" value="<?= esc($job->external_url) ?>" placeholder="https://company.com/apply">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Application Deadline</label>
                                        <input type="date" name="application_deadline" class="form-control" value="<?= !empty($job->application_deadline) ? date('Y-m-d', strtotime($job->application_deadline)) : '' ?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT SIDEBAR COLUMN (ADMIN CONTROLS) -->
            <div class="col-lg-4">
                <div class="card custom-card shadow-sm border-0 rounded-3 mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0 fw-bold"><i class="ti ti-shield-check me-2 text-primary"></i>Admin Status & Visibility</h6>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Admin Verification Status *</label>
                            <select name="admin_status" class="form-select fw-bold" required>
                                <option value="pending" class="text-warning" <?= $job->admin_status === 'pending' ? 'selected' : '' ?>>⏳ Pending Review</option>
                                <option value="approved" class="text-success" <?= $job->admin_status === 'approved' ? 'selected' : '' ?>>✓ Approved & Live</option>
                                <option value="rejected" class="text-danger" <?= $job->admin_status === 'rejected' ? 'selected' : '' ?>>✕ Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Public Listing Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="open" <?= $job->status === 'open' ? 'selected' : '' ?>>Open / Active</option>
                                <option value="closed" <?= $job->status === 'closed' ? 'selected' : '' ?>>Closed / Inactive</option>
                                <option value="draft" <?= $job->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                            </select>
                        </div>

                        <hr class="my-3">

                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_verified" value="1" id="is_verified_check" <?= (isset($job->is_verified) && $job->is_verified) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="is_verified_check">
                                    <i class="ti ti-discount-check-filled text-primary me-1"></i> Verified Job Badge
                                </label>
                            </div>
                            <small class="text-muted d-block ms-4">Displays a trust verification badge on job listing.</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured_check" <?= (isset($job->is_featured) && $job->is_featured) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="is_featured_check">
                                    <i class="ti ti-star-filled text-warning me-1"></i> Featured Job Listing
                                </label>
                            </div>
                            <small class="text-muted d-block ms-4">Promotes job to top of search results and homepage.</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_anonymous" value="1" id="is_anonymous_check" <?= (isset($job->is_anonymous) && $job->is_anonymous) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="is_anonymous_check">
                                    <i class="ti ti-eye-off text-secondary me-1"></i> Confidential / Anonymous
                                </label>
                            </div>
                            <small class="text-muted d-block ms-4">Hides employer company name and logo from candidates.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Admin Internal Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="3" placeholder="Optional notes for admin team..."><?= esc($job->admin_notes ?? '') ?></textarea>
                        </div>

                    </div>
                    <div class="card-footer bg-light d-grid gap-2">
                        <input type="hidden" name="action_type" id="action_type" value="save">
                        <?php if (($job->admin_status ?? '') !== 'approved'): ?>
                            <button type="submit" onclick="document.getElementById('action_type').value='save_approve';" id="submitApproveBtn" class="btn btn-success btn-lg fw-bold">
                                <i class="ti ti-check me-1"></i>Save & Approve Job
                            </button>
                        <?php endif; ?>
                        <button type="submit" onclick="document.getElementById('action_type').value='save';" id="submitBtn" class="btn btn-primary btn-lg fw-bold">
                            <i class="ti ti-device-floppy me-1"></i>Save Job Changes
                        </button>
                        <a href="<?= base_url('admin/jobs') ?>" class="btn btn-outline-secondary">
                            Cancel & Return
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>


<script>
    toastr.options = {
        closeButton: true,
        progressBar: true
    };

    // QUILL EDITORS
    const desc = new Quill('#desc-editor', { theme: 'snow' });
    const req = new Quill('#req-editor', { theme: 'snow' });

    function formatForQuill(text) {
        if (!text) return '';
        // If it doesn't already have HTML paragraphs or breaks, it's likely plain text. Convert newlines.
        if (text.indexOf('<p>') === -1 && text.indexOf('<br>') === -1 && text.indexOf('<br/>') === -1) {
            return text.replace(/\n/g, '<br>');
        }
        return text;
    }

    desc.root.innerHTML = formatForQuill(document.getElementById('desc-input').value);
    req.root.innerHTML = formatForQuill(document.getElementById('req-input').value);

    desc.on('text-change', () => document.getElementById('desc-input').value = desc.root.innerHTML);
    req.on('text-change', () => document.getElementById('req-input').value = req.root.innerHTML);

    // TAGIFY FOR SKILLS
    const skillsInput = document.getElementById('skills-input');
    if (skillsInput) {
        new Tagify(skillsInput, { delimiters: "," });
    }

    // SALARY TOGGLE
    function toggleSalary() {
        const type = document.getElementById('salary_type').value;
        document.getElementById('salary_box').style.display = type === 'negotiable' ? 'none' : 'block';
    }
    toggleSalary();

    // APPLICATION METHOD TOGGLE
    function toggleApplicationMethod() {
        const selected = document.querySelector('input[name="application_method"]:checked')?.value;
        document.querySelectorAll('.conditional-field').forEach(el => {
            const show = el.dataset.method === selected;
            el.style.display = show ? 'block' : 'none';
        });
    }
    toggleApplicationMethod();
    document.querySelectorAll('input[name="application_method"]').forEach(r => {
        r.addEventListener('change', toggleApplicationMethod);
    });

    // SUBMIT HANDLER
    document.getElementById('job-edit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        const approveBtn = document.getElementById('submitApproveBtn');
        const isApprove = document.getElementById('action_type').value === 'save_approve';

        if (btn) btn.disabled = true;
        if (approveBtn) approveBtn.disabled = true;

        const targetBtn = isApprove ? approveBtn : btn;
        const originalText = targetBtn ? targetBtn.innerHTML : '';
        if (targetBtn) targetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

        fetch("<?= base_url('admin/jobs/update/' . $job->id) ?>", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new FormData(e.target)
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                toastr.success(res.message);
                setTimeout(() => window.location.href = "<?= base_url('admin/jobs') ?>", 1000);
            } else {
                toastr.error(res.message || 'Failed to update job');
            }
        })
        .catch(() => toastr.error('Server error updating job'))
        .finally(() => {
            if (btn) { btn.disabled = false; }
            if (approveBtn) { approveBtn.disabled = false; }
            if (targetBtn) { targetBtn.innerHTML = originalText; }
        });
    });
</script>
<?= $this->endSection() ?>