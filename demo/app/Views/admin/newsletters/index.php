<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Newsletter & Webinar Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Admin</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Newsletters</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-xl-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="avatar avatar-md bg-primary-transparent">
                                    <i class="ti ti-mail fs-18"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">Subscribers</h6>
                                <p class="mb-0 text-muted fs-12"><?= $subscribers ?> active</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <ul class="nav nav-tabs nav-tabs-header mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#newsletters-tab" role="tab">Newsletters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#webinars-tab" role="tab">Webinars</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Newsletters Tab -->
                    <div class="tab-pane active" id="newsletters-tab" role="tabpanel">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title">All Newsletters</div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNewsletter">
                                    <i class="ti ti-plus me-1"></i> Create Newsletter
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-nowrap table-hover">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Sent At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($newsletters as $newsletter): ?>
                                                <tr>
                                                    <td><?= esc($newsletter->title) ?></td>
                                                    <td>
                                                        <?php if ($newsletter->status === 'sent'): ?>
                                                            <span class="badge bg-success-transparent">Sent</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning-transparent">Draft</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $newsletter->sent_at ?? '—' ?></td>
                                                    <td>
                                                        <?php if ($newsletter->status !== 'sent'): ?>
                                                            <form action="<?= base_url('admin/newsletters/send/' . $newsletter->id) ?>" method="POST" class="d-inline">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="btn btn-sm btn-success-light" onclick="return confirm('Send this newsletter to all subscribers?')">
                                                                    <i class="ti ti-send"></i> Send
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Webinars Tab -->
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
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($webinars as $webinar): ?>
                                                <tr>
                                                    <td><?= esc($webinar->title) ?></td>
                                                    <td><?= esc($webinar->speaker_name) ?></td>
                                                    <td><?= $webinar->scheduled_at ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $webinar->status === 'upcoming' ? 'info' : ($webinar->status === 'completed' ? 'success' : 'danger') ?>-transparent">
                                                            <?= ucfirst($webinar->status) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info-light" onclick="editWebinar(<?= htmlspecialchars(json_encode($webinar)) ?>)">
                                                            <i class="ti ti-edit"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Newsletter Modal -->
<div class="modal fade" id="addNewsletter" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('admin/newsletters/save') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Create Newsletter</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content (HTML allowed)</label>
                        <textarea name="content" class="form-control" rows="10" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Draft</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Webinar Modal -->
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
                        <input type="url" name="meeting_link" id="w_link" class="form-control" required>
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
    document.getElementById('webinar_id').value = webinar.id;
    document.getElementById('w_title').value = webinar.title;
    document.getElementById('w_speaker').value = webinar.speaker_name;
    document.getElementById('w_date').value = webinar.scheduled_at.replace(' ', 'T');
    document.getElementById('w_link').value = webinar.meeting_link;
    document.getElementById('w_desc').value = webinar.description;
    document.getElementById('w_status').value = webinar.status;
    document.getElementById('webinarModalTitle').innerText = 'Edit Webinar';
    new bootstrap.Modal(document.getElementById('addWebinar')).show();
}
</script>

<?= $this->endSection() ?>
