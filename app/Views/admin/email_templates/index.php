<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .template-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
    }
    .template-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
    }
    .category-badge {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .variable-tag {
        display: inline-block;
        background: #F1F5F9;
        color: #0D609E;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 4px;
        margin: 2px;
    }
    .btn-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        white-space: nowrap;
    }
    .btn-status-pill.is-active {
        background-color: #ECFDF5;
        color: #059669;
        border: 1px solid #A7F3D0;
    }
    .btn-status-pill.is-active:hover {
        background-color: #D1FAE5;
        color: #047857;
        border-color: #6EE7B7;
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.15);
    }
    .btn-status-pill.is-inactive {
        background-color: #F8FAFC;
        color: #64748B;
        border: 1px solid #CBD5E1;
    }
    .btn-status-pill.is-inactive:hover {
        background-color: #F1F5F9;
        color: #334155;
        border-color: #94A3B8;
        box-shadow: 0 2px 6px rgba(100, 116, 139, 0.15);
    }
    .status-indicator-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .btn-status-pill.is-active .status-indicator-dot {
        background-color: #10B981;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
    }
    .btn-status-pill.is-inactive .status-indicator-dot {
        background-color: #94A3B8;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- PAGE HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Outgoing Email Templates</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Email Templates</li>
                </ol>
            </div>
            <div>
                <span class="badge bg-primary-transparent fs-13 py-2 px-3">
                    <i class="ti ti-mail me-1"></i> <?= $totalCount ?> Total Templates (<?= $activeCount ?> Active)
                </span>
            </div>
        </div>
    </div>

    <?= csrf_field() ?>

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

    <!-- MAIN NEWSLETTER & COMMUNICATION TABS -->
    <ul class="nav nav-tabs nav-tabs-header mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/newsletters') ?>">
                <i class="ti ti-mail me-1"></i> Newsletters &amp; Campaigns
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?= base_url('admin/email-templates') ?>">
                <i class="ti ti-mail-cog me-1"></i> Outgoing Email Templates
                <span class="badge bg-primary ms-1"><?= $totalCount ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/newsletters/subscribers') ?>">
                <i class="ti ti-users me-1"></i> Subscribers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/webinars') ?>">
                <i class="ti ti-video me-1"></i> Webinars
            </a>
        </li>
    </ul>

    <!-- CATEGORY TABS & SEARCH -->
    <div class="card custom-card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <ul class="nav nav-tabs nav-tabs-header border-0 mb-0 flex-wrap">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/email-templates?category=all') ?>" 
                           class="nav-link <?= $selectedCategory === 'all' ? 'active' : '' ?>">
                            All Templates <span class="badge bg-dark ms-1"><?= $totalCount ?></span>
                        </a>
                    </li>
                    <?php foreach ($categories as $catName => $catCount): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/email-templates?category=' . urlencode($catName)) ?>" 
                               class="nav-link <?= $selectedCategory === $catName ? 'active' : '' ?>">
                                <?= esc($catName) ?> <span class="badge bg-secondary ms-1"><?= $catCount ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <form method="GET" action="<?= base_url('admin/email-templates') ?>" class="d-flex gap-2">
                    <?php if ($selectedCategory !== 'all'): ?>
                        <input type="hidden" name="category" value="<?= esc($selectedCategory) ?>">
                    <?php endif; ?>
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search templates..." value="<?= esc($searchQuery) ?>">
                        <button type="submit" class="btn btn-primary"><i class="ti ti-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TEMPLATES LIST TABLE -->
    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">System Email Templates</h5>
            <span class="text-muted fs-13">Click "Edit Template" to modify subject line, greeting style (First Name / Full Name), and content.</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 220px;">Template Name &amp; Key</th>
                            <th style="min-width: 140px;">Category</th>
                            <th style="min-width: 260px;">Default Subject</th>
                            <th style="min-width: 130px;">Greeting Style</th>
                            <th style="min-width: 110px;">Status</th>
                            <th style="min-width: 160px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($templates)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="ti ti-mail-off fs-36 d-block mb-2 text-muted"></i>
                                    No email templates found matching your criteria.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($templates as $tpl): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark fs-14"><?= esc($tpl->name) ?></div>
                                        <div class="fs-12 text-muted font-monospace"><code><?= esc($tpl->template_key) ?></code></div>
                                    </td>
                                    <td>
                                        <?php
                                        $categoryColors = [
                                            'Authentication'          => 'bg-primary-transparent text-primary',
                                            'Applications'            => 'bg-info-transparent text-info',
                                            'Employer Verification'   => 'bg-purple-transparent text-purple',
                                            'Webinars & Courses'      => 'bg-success-transparent text-success',
                                            'Subscriptions & Invoices'=> 'bg-warning-transparent text-warning',
                                            'Support & Inquiries'     => 'bg-teal-transparent text-teal',
                                            'Admin Alerts'            => 'bg-danger-transparent text-danger',
                                        ];
                                        $badgeColor = $categoryColors[$tpl->category] ?? 'bg-secondary-transparent text-secondary';
                                        ?>
                                        <span class="badge <?= $badgeColor ?>"><?= esc($tpl->category) ?></span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 320px;" title="<?= esc($tpl->subject) ?>">
                                            <?= esc($tpl->subject) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($tpl->greeting_type === 'full_name'): ?>
                                            <span class="badge bg-light text-dark border">
                                                <i class="ti ti-user me-1 text-primary"></i> Full Name
                                            </span>
                                        <?php elseif ($tpl->greeting_type === 'custom'): ?>
                                            <span class="badge bg-light text-dark border">
                                                <i class="ti ti-signature me-1 text-muted"></i> Custom / None
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark border">
                                                <i class="ti ti-user-check me-1 text-success"></i> First Name
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" 
                                                class="btn-status-pill <?= $tpl->is_active ? 'is-active' : 'is-inactive' ?>" 
                                                id="status-btn-<?= $tpl->id ?>"
                                                data-id="<?= $tpl->id ?>"
                                                data-active="<?= $tpl->is_active ? 1 : 0 ?>"
                                                onclick="toggleStatusBtn(this)"
                                                title="Click to <?= $tpl->is_active ? 'Deactivate' : 'Activate' ?>">
                                            <span class="status-indicator-dot"></span>
                                            <span class="status-label-text"><?= $tpl->is_active ? 'Active' : 'Inactive' ?></span>
                                        </button>
                                    </td>
                                    <td class="text-end" style="white-space: nowrap;">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="previewEmail(<?= $tpl->id ?>, '<?= esc($tpl->name) ?>')" 
                                                    title="Preview Email">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="openTestModal(<?= $tpl->id ?>, '<?= esc($tpl->name) ?>')" 
                                                    title="Send Test Email">
                                                <i class="ti ti-send"></i>
                                            </button>
                                            <a href="<?= base_url('admin/email-templates/edit/' . $tpl->id) ?>" 
                                               class="btn btn-sm btn-primary d-inline-flex align-items-center" 
                                               style="white-space: nowrap;"
                                               title="Edit Template">
                                                <i class="ti ti-edit me-1"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                <input type="hidden" id="test_template_id" name="template_id">
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Sending a test email will render the template with sample dynamic data (e.g. test names, job titles, links) and deliver it directly to the specified inbox.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Template</label>
                        <input type="text" id="test_template_name" class="form-control" readonly disabled>
                    </div>
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
    function previewEmail(id, title) {
        document.getElementById('previewModalTitle').innerText = 'Preview: ' + title;
        const iframe = document.getElementById('previewIframe');
        iframe.src = '<?= base_url("admin/email-templates/preview") ?>/' + id;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }

    function openTestModal(id, title) {
        document.getElementById('test_template_id').value = id;
        document.getElementById('test_template_name').value = title;
        new bootstrap.Modal(document.getElementById('testEmailModal')).show();
    }

    document.getElementById('testEmailForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('test_template_id').value;
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
            toastr.error('An error occurred. Check mail configuration.');
            console.error(err);
        });
    });

    // Toggle active status button with instant feedback
    function toggleStatusBtn(btn) {
        const id = btn.getAttribute('data-id');
        const currentActive = parseInt(btn.getAttribute('data-active')) === 1;
        const nextActive = currentActive ? 0 : 1;
        const csrfName = '<?= csrf_token() ?>';
        const csrfHash = document.querySelector('input[name="' + csrfName + '"]')?.value || '<?= csrf_hash() ?>';

        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width: 10px; height: 10px;"></span> <span style="font-size: 11px;">Saving...</span>';

        const formData = new FormData();
        formData.append('id', id);
        formData.append('is_active', nextActive);
        formData.append(csrfName, csrfHash);

        fetch('<?= base_url("admin/email-templates/toggle-status") ?>/' + id, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfHash
            },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            if (res.csrf_hash) {
                document.querySelectorAll('input[name="' + csrfName + '"]').forEach(inp => inp.value = res.csrf_hash);
            }
            if (res.success) {
                const isActive = (res.is_active == 1);
                btn.setAttribute('data-active', isActive ? 1 : 0);
                btn.className = 'btn-status-pill ' + (isActive ? 'is-active' : 'is-inactive');
                btn.title = 'Click to ' + (isActive ? 'Deactivate' : 'Activate');
                btn.innerHTML = '<span class="status-indicator-dot"></span><span class="status-label-text">' + (isActive ? 'Active' : 'Inactive') + '</span>';
                toastr.success(res.message || 'Status updated successfully.');
            } else {
                btn.innerHTML = originalHtml;
                toastr.error(res.message || 'Failed to update status.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            toastr.error('Network or server error updating status.');
            console.error(err);
        });
    }
</script>
<?= $this->endSection() ?>