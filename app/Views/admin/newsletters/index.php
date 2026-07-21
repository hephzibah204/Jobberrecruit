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
                    <i class="ti ti-mail me-1"></i> Newsletters
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
                                        <th>Title</th>
                                        <th>Subject</th>
                                        <th>Target Group</th>
                                        <th>Status</th>
                                        <th>Sent At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($newsletters)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="ti ti-mail-off fs-24 d-block mb-2"></i>
                                                No newsletters yet. <a href="<?= base_url('admin/newsletters/create') ?>">Create your first one</a>.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($newsletters as $newsletter): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= esc($newsletter->title) ?></td>
                                                <td><?= esc($newsletter->subject ?? '—') ?></td>
                                                <td>
                                                    <?php
                                                    $tg = $newsletter->target_group ?? 'all';
                                                    $tgLabels = ['all' => 'Everyone', 'employers' => 'Employers', 'candidates' => 'Candidates'];
                                                    echo esc($tgLabels[$tg] ?? ucfirst($tg));
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($newsletter->status === 'sent'): ?>
                                                        <span class="badge bg-success-transparent">Sent</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning-transparent">Draft</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $newsletter->sent_at ?? '—' ?></td>
                                                <td>
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        <?php if ($newsletter->status !== 'sent'): ?>
                                                            <a href="<?= base_url('admin/newsletters/edit/' . $newsletter->id) ?>" class="btn btn-sm btn-info-light" title="Edit">
                                                                <i class="ti ti-edit"></i>
                                                            </a>
                                                            <form action="<?= base_url('admin/newsletters/send/' . $newsletter->id) ?>" method="POST" class="d-inline">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="btn btn-sm btn-success-light"
                                                                    onclick="return confirm('Send this newsletter to all <?= $subscribers ?> active subscribers?')"
                                                                    title="Send Now">
                                                                    <i class="ti ti-send"></i>
                                                                </button>
                                                            </form>
                                                        <?php else: ?>
                                                            <span class="text-muted small">Sent <?= $newsletter->sent_at ? date('d M Y', strtotime($newsletter->sent_at)) : '' ?></span>
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
                                        <th>Title</th>
                                        <th>Speaker</th>
                                        <th>Scheduled At</th>
                                        <th>Meeting Link</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($webinars)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="ti ti-video-off fs-24 d-block mb-2"></i>
                                                No webinars yet.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($webinars as $webinar): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= esc($webinar->title) ?></td>
                                                <td><?= esc($webinar->speaker_name) ?></td>
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
    <div class="modal-dialog">
        <form action="<?= base_url('admin/webinars/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="webinar_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="webinarModalTitle">Add Webinar</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                        <label class="form-label">Description</label>
                        <textarea name="description" id="w_desc" class="form-control" rows="3"></textarea>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Webinar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editWebinar(webinar) {
    document.getElementById('webinar_id').value = webinar.id || '';
    document.getElementById('w_title').value = webinar.title || '';
    document.getElementById('w_speaker').value = webinar.speaker_name || '';
    document.getElementById('w_date').value = (webinar.scheduled_at || '').replace(' ', 'T');
    document.getElementById('w_link').value = webinar.meeting_link || '';
    document.getElementById('w_desc').value = webinar.description || '';
    document.getElementById('w_status').value = webinar.status || 'upcoming';
    document.getElementById('webinarModalTitle').innerText = 'Edit Webinar';
    new bootstrap.Modal(document.getElementById('addWebinar')).show();
}
</script>

<?= $this->endSection() ?>
