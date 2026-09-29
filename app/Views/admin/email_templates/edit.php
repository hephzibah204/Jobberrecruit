<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .variable-btn {
        font-family: monospace;
        font-size: 12px;
        background: #F1F5F9;
        border: 1px solid #CBD5E1;
        color: #0D609E;
        padding: 4px 8px;
        border-radius: 4px;
        margin: 3px 2px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .variable-btn:hover {
        background: #0D609E;
        color: #ffffff;
        border-color: #0D609E;
    }
    .code-editor-textarea {
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 13.5px;
        line-height: 1.5;
        background: #0F172A;
        color: #E2E8F0;
        border-radius: 8px;
        padding: 16px;
        width: 100%;
        min-height: 440px;
        border: 1px solid #334155;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- PAGE HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Edit Email Template: <?= esc($template->name) ?></h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/email-templates') ?>">Email Templates</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
            <div class="btn-list">
                <button type="button" class="btn btn-outline-info btn-sm" onclick="previewEmail(<?= $template->id ?>, '<?= esc($template->name) ?>')">
                    <i class="ti ti-eye me-1"></i> Preview Template
                </button>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="openTestModal(<?= $template->id ?>, '<?= esc($template->name) ?>')">
                    <i class="ti ti-send me-1"></i> Send Test Email
                </button>
                <a href="<?= base_url('admin/email-templates/reset/' . $template->id) ?>" 
                   onclick="return confirm('Are you sure you want to reset this template to default? Any custom changes will be overwritten.')"
                   class="btn btn-outline-danger btn-sm">
                    <i class="ti ti-rotate-clockwise me-1"></i> Reset to Default
                </a>
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= base_url('admin/email-templates/update/' . $template->id) ?>" id="templateEditForm">
        <?= csrf_field() ?>

        <div class="row">
            <!-- MAIN EDITOR -->
            <div class="col-xl-8">
                <div class="card custom-card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Template Details & Subject</h5>
                        <span class="badge bg-primary-transparent font-monospace"><?= esc($template->template_key) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Email Subject Line *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                    <input type="text" name="subject" id="emailSubjectInput" class="form-control fw-semibold" 
                                           value="<?= esc(old('subject', $template->subject)) ?>" required>
                                </div>
                                <small class="text-muted">You can use dynamic tags in the subject line like <code>{first_name}</code>, <code>{job_title}</code>, <code>{company_name}</code>.</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Greeting Preference *</label>
                                <select name="greeting_type" id="greetingTypeSelect" class="form-select">
                                    <option value="first_name" <?= (old('greeting_type', $template->greeting_type) === 'first_name') ? 'selected' : '' ?>>
                                        👤 Use First Name (e.g. Hello John,)
                                    </option>
                                    <option value="full_name" <?= (old('greeting_type', $template->greeting_type) === 'full_name') ? 'selected' : '' ?>>
                                        👥 Use Full Name (e.g. Hello John Doe,)
                                    </option>
                                    <option value="custom" <?= (old('greeting_type', $template->greeting_type) === 'custom') ? 'selected' : '' ?>>
                                        📝 Custom / Generic (e.g. Hello,)
                                    </option>
                                </select>
                                <small class="text-muted">Controls how <code>{greeting}</code> resolves.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HTML BODY EDITOR -->
                <div class="card custom-card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Email Body (HTML)</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertAtCursor('bodyHtmlTextarea', '{greeting}')">
                                + Insert {greeting}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertAtCursor('bodyHtmlTextarea', '{first_name}')">
                                + Insert {first_name}
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <textarea name="body_html" id="bodyHtmlTextarea" class="code-editor-textarea" required><?= esc(old('body_html', $template->body_html)) ?></textarea>
                    </div>
                </div>

                <!-- PLAIN TEXT FALLBACK -->
                <div class="card custom-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Plain Text Alternative Body (Optional)</h6>
                    </div>
                    <div class="card-body">
                        <textarea name="body_text" id="bodyTextTextarea" rows="6" class="form-control font-monospace" placeholder="Plain text version for non-HTML mail clients..."><?= esc(old('body_text', $template->body_text)) ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="<?= base_url('admin/email-templates') ?>" class="btn btn-light">
                        <i class="ti ti-arrow-left me-1"></i> Back to Templates
                    </a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold fs-15">
                        <i class="ti ti-device-floppy me-1"></i> Save Changes
                    </button>
                </div>
            </div>

            <!-- SIDEBAR: DYNAMIC PLACEHOLDERS & SETTINGS -->
            <div class="col-xl-4">
                <!-- Status card -->
                <div class="card custom-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Status & Category</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Template Category</label>
                            <input type="text" class="form-control" value="<?= esc($template->category) ?>" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Template Status</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_active" id="status_active" value="1" <?= $template->is_active ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold text-success" for="status_active">
                                        <i class="ti ti-circle-check me-1"></i> Active
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_active" id="status_inactive" value="0" <?= !$template->is_active ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold text-muted" for="status_inactive">
                                        <i class="ti ti-circle-x me-1"></i> Inactive
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Variables Helper -->
                <div class="card custom-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="ti ti-variable me-1 text-primary"></i> Available Dynamic Variables</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Click any tag below to copy or insert directly into the template HTML or subject line:
                        </p>

                        <div class="mb-3">
                            <div class="fw-bold fs-12 text-uppercase text-muted mb-2">Name & Greeting Tags</div>
                            <div>
                                <button type="button" class="variable-btn" onclick="insertTag('{greeting}')">
                                    <i class="ti ti-plus"></i> {greeting}
                                </button>
                                <button type="button" class="variable-btn" onclick="insertTag('{first_name}')">
                                    <i class="ti ti-plus"></i> {first_name}
                                </button>
                                <button type="button" class="variable-btn" onclick="insertTag('{last_name}')">
                                    <i class="ti ti-plus"></i> {last_name}
                                </button>
                                <button type="button" class="variable-btn" onclick="insertTag('{full_name}')">
                                    <i class="ti ti-plus"></i> {full_name}
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="fw-bold fs-12 text-uppercase text-muted mb-2">Template Specific Variables</div>
                            <div>
                                <?php foreach ($availableVariables as $tag => $desc): ?>
                                    <?php if (!in_array($tag, ['{first_name}', '{last_name}', '{full_name}', '{greeting}'])): ?>
                                        <div class="mb-2">
                                            <button type="button" class="variable-btn" onclick="insertTag('<?= esc($tag) ?>')">
                                                <i class="ti ti-plus"></i> <?= esc($tag) ?>
                                            </button>
                                            <div class="fs-11 text-muted ms-1"><?= esc($desc) ?></div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="alert alert-info py-2 px-3 fs-12 mb-0">
                            <i class="ti ti-info-circle me-1"></i>
                            <strong>Tip:</strong> If you select <strong>"Use First Name"</strong>, <code>{greeting}</code> automatically renders <em>"Hello John,"</em>. If you select <strong>"Use Full Name"</strong>, it renders <em>"Hello John Doe,"</em>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<!-- PREVIEW MODAL -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalTitle">Email Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="background: #F4F7FB; min-height: 520px;">
                <iframe id="previewIframe" style="width: 100%; height: 550px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close Preview</button>
            </div>
        </div>
    </div>
</div>

<!-- TEST EMAIL MODAL -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="testEmailForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Sends a real test email with your latest saved template content to verify deliverability and inbox layout.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Recipient Email Address *</label>
                        <input type="email" name="test_email" id="test_email" class="form-control" 
                               placeholder="your-email@example.com" required value="<?= esc(auth()->user()->email ?? '') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="btnSendTest">
                        <i class="ti ti-send me-1"></i> Send Test Email Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function insertAtCursor(fieldId, text) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const startPos = field.selectionStart;
        const endPos = field.selectionEnd;
        const scrollTop = field.scrollTop;

        field.value = field.value.substring(0, startPos) + text + field.value.substring(endPos, field.value.length);
        field.focus();
        field.selectionStart = startPos + text.length;
        field.selectionEnd = startPos + text.length;
        field.scrollTop = scrollTop;
    }

    function insertTag(tag) {
        insertAtCursor('bodyHtmlTextarea', tag);
        toastr.info('Inserted ' + tag + ' at cursor');
    }

    function previewEmail(id, title) {
        document.getElementById('previewModalTitle').innerText = 'Preview: ' + title;
        const iframe = document.getElementById('previewIframe');
        iframe.src = '<?= base_url("admin/email-templates/preview") ?>/' + id;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }

    function openTestModal(id, title) {
        new bootstrap.Modal(document.getElementById('testEmailModal')).show();
    }

    document.getElementById('testEmailForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = <?= $template->id ?>;
        const btn = document.getElementById('btnSendTest');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';

        const formData = new FormData(this);

        fetch('<?= base_url("admin/email-templates/send-test") ?>/' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-send me-1"></i> Send Test Email Now';
            if (res.success) {
                toastr.success(res.message);
                bootstrap.Modal.getInstance(document.getElementById('testEmailModal')).hide();
            } else {
                toastr.error(res.message || 'Failed to send email.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-send me-1"></i> Send Test Email Now';
            toastr.error('An error occurred.');
            console.error(err);
        });
    });
</script>
<?= $this->endSection() ?>