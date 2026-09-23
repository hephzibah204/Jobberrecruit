<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Newsletter &amp; Webinar Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Admin</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Newsletters</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stat Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-md bg-primary-transparent me-3">
                                <i class="ti ti-mail fs-18"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-semibold"><?= $subscribers ?></h6>
                                <p class="mb-0 text-muted fs-12">Active Subscribers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-md bg-success-transparent me-3">
                                <i class="ti ti-send fs-18"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-semibold"><?= count(array_filter($newsletters, fn($n) => $n->status === 'sent')) ?></h6>
                                <p class="mb-0 text-muted fs-12">Newsletters Sent</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-md bg-warning-transparent me-3">
                                <i class="ti ti-file-text fs-18"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-semibold"><?= count(array_filter($newsletters, fn($n) => $n->status !== 'sent')) ?></h6>
                                <p class="mb-0 text-muted fs-12">Drafts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-md bg-info-transparent me-3">
                                <i class="ti ti-video fs-18"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 fw-semibold"><?= count($webinars) ?></h6>
                                <p class="mb-0 text-muted fs-12">Total Webinars</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs nav-tabs-header mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#newsletters-tab" role="tab">
                    <i class="ti ti-mail me-1"></i> Newsletters &amp; Campaigns
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('admin/email-templates') ?>">
                    <i class="ti ti-mail-cog me-1"></i> Outgoing Email Templates
                    <span class="badge bg-primary ms-1">Editable</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#webinars-tab" role="tab">
                    <i class="ti ti-video me-1"></i> Webinars
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#subscribers-tab" role="tab">
                    <i class="ti ti-users me-1"></i> Subscribers
                    <span class="badge bg-primary ms-1"><?= $subscribers ?></span>
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <!-- ── Newsletters Tab ─────────────────────────────────────────── -->
            <div class="tab-pane active" id="newsletters-tab" role="tabpanel">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">All Newsletters</div>
                        <a href="<?= base_url('admin/newsletters/create') ?>" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Create Newsletter
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th>Campaign / Title</th>
                                        <th>Subject</th>
                                        <th>Sender / Admin</th>
                                        <th>Target Audience</th>
                                        <th>Status</th>
                                        <th>Sent At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($newsletters)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="ti ti-mail-off fs-24 d-block mb-2"></i>
                                                No newsletters yet. <a href="<?= base_url('admin/newsletters/create') ?>">Create your first one</a>.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($newsletters as $newsletter): ?>
                                            <tr>
                                                <td class="fw-semibold">
                                                    <?= esc($newsletter->title) ?>
                                                    <?php if (!empty($newsletter->content)): ?>
                                                        <a href="javascript:void(0)" onclick="previewNewsletter(<?= $newsletter->id ?>)" class="ms-1 fs-12 text-primary" title="View content">
                                                            <i class="ti ti-eye"></i> View Content
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= esc($newsletter->subject ?? '—') ?></td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="ti ti-user me-1"></i><?= esc($newsletter->created_by ?? 'Admin') ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $tg = $newsletter->target_group ?? 'all';
                                                    $tgLabels = [
                                                        'all'                    => '<span class="badge bg-primary-transparent">All Users &amp; Subscribers</span>',
                                                        'candidates'             => '<span class="badge bg-info-transparent">Registered Candidates</span>',
                                                        'employers'              => '<span class="badge bg-warning-transparent">Registered Employers</span>',
                                                        'guest_subscribers'      => '<span class="badge bg-secondary-transparent">Guest Subscribers</span>',
                                                        'registered_subscribers' => '<span class="badge bg-dark-transparent">Registered Subscribers</span>',
                                                        'new_candidates'         => '<span class="badge bg-info-transparent">🆕 New Candidates</span>',
                                                        'new_employers'          => '<span class="badge bg-warning-transparent">🆕 New Employers</span>',
                                                        'subscribers'            => '<span class="badge bg-secondary-transparent">All Subscribers</span>',
                                                        'webinar_registered'     => '<span class="badge bg-success-transparent">🎓 Webinar Registrants</span>',
                                                        'training_registered'    => '<span class="badge bg-purple-transparent">📚 Training Registrants</span>',
                                                    ];
                                                    echo $tgLabels[$tg] ?? '<span class="badge bg-light text-dark">' . esc(ucfirst($tg)) . '</span>';
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($newsletter->status === 'sent'): ?>
                                                        <span class="badge bg-success-transparent text-success"><i class="ti ti-check me-1"></i>Sent</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning-transparent text-warning"><i class="ti ti-clock me-1"></i>Draft</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $newsletter->sent_at ? date('M d, Y H:i', strtotime($newsletter->sent_at)) : '—' ?></td>
                                                <td>
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        <button type="button" class="btn btn-sm btn-primary-light" onclick="previewNewsletter(<?= $newsletter->id ?>)" title="Preview Content">
                                                            <i class="ti ti-eye"></i> View
                                                        </button>
                                                        <?php if ($newsletter->status !== 'sent'): ?>
                                                            <a href="<?= base_url('admin/newsletters/edit/' . $newsletter->id) ?>" class="btn btn-sm btn-info-light" title="Edit">
                                                                <i class="ti ti-edit"></i>
                                                            </a>
                                                            <form action="<?= base_url('admin/newsletters/send/' . $newsletter->id) ?>" method="POST" class="d-inline">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="btn btn-sm btn-success-light"
                                                                    onclick="return confirm('Send this newsletter now to target audience?')"
                                                                    title="Send Now">
                                                                    <i class="ti ti-send"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <!-- Delete always available -->
                                                        <form action="<?= base_url('admin/newsletters/delete/' . $newsletter->id) ?>" method="POST" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="btn btn-sm btn-danger-light"
                                                                onclick="return confirm('Delete this newsletter? This cannot be undone.')"
                                                                title="Delete">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
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

            <!-- ── Webinars Tab ───────────────────────────────────────────── -->
            <div class="tab-pane" id="webinars-tab" role="tabpanel">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">All Webinars</div>
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addWebinar">
                            <i class="ti ti-plus me-1"></i> Add Webinar
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th>Flyer</th>
                                        <th>Title</th>
                                        <th>Speaker</th>
                                        <th>Type &amp; Price</th>
                                        <th>Registered</th>
                                        <th>Scheduled At</th>
                                        <th>Meeting Link</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($webinars)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="ti ti-video-off fs-24 d-block mb-2"></i>
                                                No webinars yet.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($webinars as $webinar): ?>
                                            <tr>
                                                <td>
                                                    <?php if (!empty($webinar->flyer_image)): ?>
                                                        <img src="<?= base_url($webinar->flyer_image) ?>" alt="Flyer" style="width:45px;height:45px;object-fit:cover;" class="rounded border">
                                                    <?php else: ?>
                                                        <span class="avatar avatar-sm bg-light text-muted"><i class="ti ti-photo"></i></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="fw-semibold"><?= esc($webinar->title) ?></td>
                                                <td><?= esc($webinar->speaker_name) ?></td>
                                                <td>
                                                    <?php if (($webinar->access_type ?? 'free') === 'paid'): ?>
                                                        <span class="badge bg-warning-transparent text-warning fw-bold">PAID</span>
                                                        <span class="fs-12 ms-1 fw-semibold">&#x20A6;<?= number_format((float)($webinar->price ?? 0), 2) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success-transparent text-success fw-bold">FREE</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary-transparent text-primary fw-bold fs-12">
                                                        <i class="ti ti-users me-1"></i><?= number_format((int)($webinar->registrants_count ?? 0)) ?>
                                                    </span>
                                                </td>
                                                <td><?= $webinar->scheduled_at ?></td>
                                                <td>
                                                    <?php if ($webinar->meeting_link): ?>
                                                        <a href="<?= esc($webinar->meeting_link) ?>" target="_blank" class="btn btn-xs btn-outline-info btn-sm">
                                                            <i class="ti ti-external-link me-1"></i>Join
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $badgeMap = ['upcoming' => 'info', 'ongoing' => 'primary', 'completed' => 'success', 'cancelled' => 'danger'];
                                                    $badge = $badgeMap[$webinar->status] ?? 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?= $badge ?>-transparent"><?= ucfirst($webinar->status) ?></span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <button class="btn btn-sm btn-info-light"
                                                            onclick="editWebinar(<?= htmlspecialchars(json_encode($webinar)) ?>)"
                                                            title="Edit">
                                                            <i class="ti ti-edit"></i>
                                                        </button>
                                                        <form action="<?= base_url('admin/webinars/delete/' . $webinar->id) ?>" method="POST" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="btn btn-sm btn-danger-light"
                                                                onclick="return confirm('Delete this webinar?')"
                                                                title="Delete">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
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

            <!-- ── Subscribers Tab ────────────────────────────────────────── -->
            <div class="tab-pane" id="subscribers-tab" role="tabpanel">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Newsletter Subscribers (<?= $subscribers ?> active)</div>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/newsletters/subscribers') ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-users me-1"></i> Full Subscriber List
                            </a>
                            <a href="<?= base_url('admin/newsletters/subscribers/export') ?>" class="btn btn-success btn-sm">
                                <i class="ti ti-download me-1"></i> Export CSV
                            </a>
                        </div>
                    </div>
                    <div class="card-body text-center py-5 text-muted">
                        <i class="ti ti-users fs-48 mb-3 d-block opacity-25"></i>
                        <p class="mb-3">You have <strong><?= $subscribers ?></strong> active subscribers.</p>
                        <a href="<?= base_url('admin/newsletters/subscribers') ?>" class="btn btn-primary">
                            <i class="ti ti-users me-1"></i> Manage Subscribers
                        </a>
                        <a href="<?= base_url('admin/newsletters/subscribers/export') ?>" class="btn btn-outline-success ms-2">
                            <i class="ti ti-download me-1"></i> Export CSV
                        </a>
                    </div>
                </div>
            </div>

        </div><!-- /.tab-content -->
    </div>

<!-- ── Add/Edit Webinar Modal ─────────────────────────────────────────────── -->
<div class="modal fade" id="addWebinar" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="<?= base_url('admin/webinars/save') ?>" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100 mb-0">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="webinar_id">
                <div class="modal-header">
                    <h6 class="modal-title" id="webinarModalTitle">Add Webinar</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto;">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="w_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Speaker Name</label>
                        <input type="text" name="speaker_name" id="w_speaker" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Scheduled At</label>
                        <input type="datetime-local" name="scheduled_at" id="w_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Link</label>
                        <input type="url" name="meeting_link" id="w_link" class="form-control" placeholder="https://meet.google.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Training Flyer Image</label>
                        <input type="file" name="flyer_image" id="w_flyer" class="form-control" accept="image/*">
                        <small class="text-muted">Upload webinar poster or banner image (PNG, JPG, WebP)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="w_desc" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Access Type</label>
                            <select name="access_type" id="w_access_type" class="form-select" onchange="togglePriceField(this.value)">
                                <option value="free">Free</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="price_wrap" style="display:none;">
                            <label class="form-label">Price (&#x20A6;)</label>
                            <input type="number" step="0.01" min="0" name="price" id="w_price" class="form-control" placeholder="e.g. 2500.00" value="0.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="w_status" class="form-select">
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Webinar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Newsletter Preview Modal -->
<div class="modal fade" id="previewNewsletterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title fw-bold" id="previewModalTitle">Newsletter Preview</h5>
                    <div class="fs-12 text-muted" id="previewModalSubtitle"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="card border mb-3 bg-light-subtle">
                    <div class="card-body py-2 px-3 fs-13">
                        <div class="row g-2">
                            <div class="col-md-6"><strong>Subject:</strong> <span id="previewModalSubject">—</span></div>
                            <div class="col-md-6"><strong>Audience:</strong> <span id="previewModalAudience" class="badge bg-primary-transparent">—</span></div>
                            <div class="col-md-6"><strong>Sender / Admin:</strong> <span id="previewModalSender">—</span></div>
                            <div class="col-md-6"><strong>Sent At:</strong> <span id="previewModalDate">—</span></div>
                        </div>
                    </div>
                </div>
                <div class="border rounded p-3 bg-white" style="min-height: 250px;">
                    <div id="previewModalContent">
                        <div class="text-center py-4 text-muted"><i class="ti ti-loader animate-spin fs-24"></i> Loading content...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function togglePriceField(type) {
    const wrap = document.getElementById('price_wrap');
    if (wrap) {
        wrap.style.display = type === 'paid' ? 'block' : 'none';
    }
}

function editWebinar(webinar) {
    document.getElementById('webinar_id').value = webinar.id || '';
    document.getElementById('w_title').value = webinar.title || '';
    document.getElementById('w_speaker').value = webinar.speaker_name || '';
    document.getElementById('w_date').value = (webinar.scheduled_at || '').replace(' ', 'T');
    document.getElementById('w_link').value = webinar.meeting_link || '';
    document.getElementById('w_desc').value = webinar.description || '';
    
    const accessType = webinar.access_type || 'free';
    document.getElementById('w_access_type').value = accessType;
    document.getElementById('w_price').value = webinar.price || '0.00';
    togglePriceField(accessType);

    document.getElementById('w_status').value = webinar.status || 'upcoming';
    document.getElementById('webinarModalTitle').innerText = 'Edit Webinar';
    new bootstrap.Modal(document.getElementById('addWebinar')).show();
}

function previewNewsletter(id) {
    const modalEl = document.getElementById('previewNewsletterModal');
    const bsModal = new bootstrap.Modal(modalEl);
    document.getElementById('previewModalContent').innerHTML = '<div class="text-center py-4 text-muted"><i class="ti ti-loader animate-spin fs-24 d-block mb-2"></i> Loading newsletter content...</div>';
    bsModal.show();

    fetch('<?= base_url("admin/newsletters/preview-content") ?>/' + id, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.newsletter) {
            const nl = data.newsletter;
            document.getElementById('previewModalTitle').innerText = nl.title || 'Newsletter';
            document.getElementById('previewModalSubject').innerText = nl.subject || '—';
            document.getElementById('previewModalAudience').innerText = nl.target_group ? nl.target_group.toUpperCase() : 'ALL';
            document.getElementById('previewModalSender').innerText = nl.created_by || 'Admin';
            document.getElementById('previewModalDate').innerText = nl.sent_at || 'Draft (Not sent yet)';
            document.getElementById('previewModalContent').innerHTML = nl.content || '<div class="text-muted text-center">No content available</div>';
        } else {
            document.getElementById('previewModalContent').innerHTML = '<div class="alert alert-danger">Failed to load content.</div>';
        }
    })
    .catch(err => {
        document.getElementById('previewModalContent').innerHTML = '<div class="alert alert-danger">Error fetching content.</div>';
    });
}
</script>

<?= $this->endSection() ?>
