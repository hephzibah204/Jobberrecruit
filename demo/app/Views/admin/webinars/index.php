<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Webinar Classes Management</h1>
            <div class="ms-md-1 ms-0">
                <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#addWebinarModal">
                    <i class="ti ti-plus me-1"></i> Schedule Webinar
                </button>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Alerts -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">All Scheduled Webinars & Classes</div>
                        <div class="text-muted fs-12">Manage live training sessions linking to Zoom, Google Meet, or Microsoft Teams.</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th>Webinar Title</th>
                                        <th>Speaker / Host</th>
                                        <th>Date & Time</th>
                                        <th>Meeting Provider</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($webinars)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">
                                                No webinars scheduled yet. Click <strong>Schedule Webinar</strong> to create one.
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach ($webinars as $webinar): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold fs-14"><?= esc($webinar->title) ?></div>
                                                <div class="text-muted fs-12 text-wrap" style="max-width: 300px;"><?= esc($webinar->description) ?></div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-sm bg-primary-transparent me-2">
                                                        <?= strtoupper(substr(esc($webinar->speaker_name), 0, 2)) ?>
                                                    </span>
                                                    <div><?= esc($webinar->speaker_name) ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?= date('F j, Y', strtotime($webinar->scheduled_at)) ?></div>
                                                <div class="text-muted fs-12"><?= date('h:i A', strtotime($webinar->scheduled_at)) ?></div>
                                            </td>
                                            <td>
                                                <?php 
                                                $link = esc($webinar->meeting_link);
                                                $provider = 'External Link';
                                                $badgeClass = 'bg-light text-dark';
                                                
                                                if (stripos($link, 'zoom.us') !== false) {
                                                    $provider = 'Zoom';
                                                    $badgeClass = 'bg-primary-transparent';
                                                } elseif (stripos($link, 'meet.google.com') !== false) {
                                                    $provider = 'Google Meet';
                                                    $badgeClass = 'bg-success-transparent';
                                                } elseif (stripos($link, 'teams.microsoft.com') !== false) {
                                                    $provider = 'MS Teams';
                                                    $badgeClass = 'bg-info-transparent';
                                                }
                                                ?>
                                                <span class="badge <?= $badgeClass ?> fs-12">
                                                    <i class="ti ti-link me-1"></i><?= $provider ?>
                                                </span>
                                                <a href="<?= $link ?>" target="_blank" class="d-block fs-11 text-truncate text-primary mt-1" style="max-width: 150px;" title="<?= $link ?>">
                                                    Join Session
                                                </a>
                                            </td>
                                            <td>
                                                <?php 
                                                $statusClass = 'bg-info-transparent';
                                                if ($webinar->status === 'completed') $statusClass = 'bg-success-transparent';
                                                if ($webinar->status === 'ongoing') $statusClass = 'bg-warning-transparent';
                                                if ($webinar->status === 'cancelled') $statusClass = 'bg-danger-transparent';
                                                ?>
                                                <span class="badge <?= $statusClass ?>">
                                                    <?= ucfirst($webinar->status) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-icon btn-info-light me-1" onclick="editWebinar(<?= htmlspecialchars(json_encode($webinar)) ?>)" title="Edit Webinar">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <a href="<?= $link ?>" class="btn btn-sm btn-icon btn-primary-light" target="_blank" title="Join Meeting">
                                                    <i class="ti ti-video"></i>
                                                </a>
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

<!-- Add/Schedule Webinar Modal -->
<div class="modal fade" id="addWebinarModal" tabindex="-1" aria-labelledby="addWebinarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('admin/webinars/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="webinar_id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="webinarModalTitle">Schedule New Webinar Class</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Webinar / Class Title</label>
                            <input type="text" name="title" id="w_title" class="form-control" placeholder="e.g. Masterclass on Interview Preparation" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Speaker / Host Name</label>
                            <input type="text" name="speaker_name" id="w_speaker" class="form-control" placeholder="e.g. Dr. Jane Smith" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Scheduled Date & Time</label>
                            <input type="datetime-local" name="scheduled_at" id="w_date" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Meeting URL (Zoom / Google Meet / Teams)</label>
                            <input type="url" name="meeting_link" id="w_link" class="form-control" placeholder="https://zoom.us/j/... or https://meet.google.com/..." required>
                            <small class="text-muted d-block mt-1">Paste the full join link from Zoom or Google Meet. The platform will automatically identify the provider badge.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Short Description</label>
                            <textarea name="description" id="w_desc" class="form-control" rows="3" placeholder="Brief outline of the webinar topics and learning outcomes..."></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Status</label>
                            <select name="status" id="w_status" class="form-select">
                                <option value="upcoming">Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveWebinarBtn">Schedule Class</button>
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
    document.getElementById('w_date').value = webinar.scheduled_at.replace(' ', 'T').substring(0, 16);
    document.getElementById('w_link').value = webinar.meeting_link;
    document.getElementById('w_desc').value = webinar.description;
    document.getElementById('w_status').value = webinar.status;
    
    document.getElementById('webinarModalTitle').innerText = 'Edit Webinar Class Details';
    document.getElementById('saveWebinarBtn').innerText = 'Update Class';
    
    var editModal = new bootstrap.Modal(document.getElementById('addWebinarModal'));
    editModal.show();
}

// Automatically trigger modal open if URL has '?create=1'
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('create') === '1') {
        var addModal = new bootstrap.Modal(document.getElementById('addWebinarModal'));
        addModal.show();
    }
});
</script>
<?= $this->endSection() ?>
