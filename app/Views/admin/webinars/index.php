<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-1">Webinar Classes &amp; Live Training</h1>
            <span class="text-muted fs-12">Manage scheduled masterclasses, preview live meeting links, and monitor attendee registrations.</span>
        </div>
        <div class="ms-md-1 ms-0 mt-md-0 mt-2 d-flex gap-2">
            <a href="<?= base_url('webinars') ?>" target="_blank" class="btn btn-outline-primary btn-wave">
                <i class="ti ti-external-link me-1"></i> Public Webinars Page
            </a>
            <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#addWebinarModal">
                <i class="ti ti-plus me-1"></i> Schedule Webinar
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Alerts -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check me-1"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-1"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">All Scheduled Webinars &amp; Classes</div>
                    <div class="text-muted fs-12">Total Webinars: <span class="fw-bold text-primary"><?= count($webinars ?? []) ?></span></div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Webinar &amp; Flyer</th>
                                    <th>Speaker / Host</th>
                                    <th>Date &amp; Time</th>
                                    <th>Meeting Link &amp; Provider</th>
                                    <th>Attendees</th>
                                    <th>Access / Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($webinars)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="ti ti-device-desktop fs-1 d-block mb-2 text-secondary"></i>
                                            <p class="mb-2 fw-medium">No webinars scheduled yet.</p>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addWebinarModal">
                                                <i class="ti ti-plus me-1"></i> Schedule Your First Webinar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($webinars as $webinar): ?>
                                    <?php 
                                        $link = (string)($webinar->meeting_link ?? '');
                                        $provider = 'Meeting Link';
                                        $providerIcon = 'ti-link';
                                        $badgeClass = 'bg-light text-dark';
                                        
                                        if (stripos($link, 'zoom.us') !== false) {
                                            $provider = 'Zoom';
                                            $providerIcon = 'ti-video';
                                            $badgeClass = 'bg-primary-transparent text-primary';
                                        } elseif (stripos($link, 'meet.google.com') !== false) {
                                            $provider = 'Google Meet';
                                            $providerIcon = 'ti-brand-google';
                                            $badgeClass = 'bg-success-transparent text-success';
                                        } elseif (stripos($link, 'teams.microsoft.com') !== false) {
                                            $provider = 'MS Teams';
                                            $providerIcon = 'ti-brand-windows';
                                            $badgeClass = 'bg-info-transparent text-info';
                                        }

                                        $regCount = (int)($webinar->registrations_count ?? $webinar->registrants_count ?? 0);
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($webinar->flyer_image)): ?>
                                                    <img src="<?= base_url($webinar->flyer_image) ?>" class="rounded border flex-shrink-0" style="width: 52px; height: 40px; object-fit: cover;" alt="Flyer">
                                                <?php else: ?>
                                                    <div class="rounded bg-light text-muted d-flex align-items-center justify-content-center flex-shrink-0 border" style="width: 52px; height: 40px;">
                                                        <i class="ti ti-photo fs-5"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <a href="javascript:void(0);" onclick="previewWebinar(<?= htmlspecialchars(json_encode($webinar)) ?>, <?= $regCount ?>)" class="fw-semibold fs-14 text-dark hover-primary d-block">
                                                        <?= esc($webinar->title) ?>
                                                    </a>
                                                    <div class="text-muted fs-12 text-truncate" style="max-width: 280px;"><?= esc($webinar->description) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm bg-primary-transparent text-primary fw-bold me-2">
                                                    <?= strtoupper(substr((string)esc($webinar->speaker_name), 0, 2)) ?>
                                                </span>
                                                <div>
                                                    <div class="fw-medium fs-13"><?= esc($webinar->speaker_name) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold fs-13"><?= date('M j, Y', strtotime($webinar->scheduled_at)) ?></div>
                                            <div class="text-muted fs-11"><?= date('h:i A', strtotime($webinar->scheduled_at)) ?></div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="badge <?= $badgeClass ?> fs-11 px-2 py-1">
                                                        <i class="ti <?= $providerIcon ?> me-1"></i><?= $provider ?>
                                                    </span>
                                                    <button type="button" class="btn btn-xs btn-outline-secondary p-1 border-0" onclick="copyMeetingLink('<?= esc(addslashes($link)) ?>')" title="Copy Meeting URL">
                                                        <i class="ti ti-copy fs-13"></i>
                                                    </button>
                                                </div>
                                                <?php if (!empty($link)): ?>
                                                    <a href="<?= esc($link) ?>" target="_blank" class="fs-12 text-primary text-truncate d-inline-block fw-medium" style="max-width: 170px;" title="<?= esc($link) ?>">
                                                        <i class="ti ti-external-link me-1"></i>Preview Link
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted fs-11">No link provided</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1" onclick="viewAttendees(<?= $webinar->id ?>, '<?= esc(addslashes($webinar->title)) ?>', '<?= date('M j, Y - h:i A', strtotime($webinar->scheduled_at)) ?>')">
                                                <i class="ti ti-users"></i>
                                                <span class="fw-bold"><?= $regCount ?></span> Attendees
                                            </button>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <div>
                                                    <?php if (($webinar->access_type ?? 'free') === 'paid'): ?>
                                                        <span class="badge bg-warning-transparent text-warning fw-semibold">₦<?= number_format((float)($webinar->price ?? 0), 2) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success-transparent text-success fw-semibold">Free</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <?php 
                                                    $statusClass = 'bg-info-transparent text-info';
                                                    if ($webinar->status === 'completed') $statusClass = 'bg-success-transparent text-success';
                                                    if ($webinar->status === 'ongoing') $statusClass = 'bg-warning-transparent text-warning';
                                                    if ($webinar->status === 'cancelled') $statusClass = 'bg-danger-transparent text-danger';
                                                    ?>
                                                    <span class="badge <?= $statusClass ?> fs-11">
                                                        <?= ucfirst($webinar->status) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-list">
                                                <button class="btn btn-sm btn-icon btn-primary-light" onclick="previewWebinar(<?= htmlspecialchars(json_encode($webinar)) ?>, <?= $regCount ?>)" title="Preview Webinar Details &amp; Link">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-secondary-light" onclick="viewAttendees(<?= $webinar->id ?>, '<?= esc(addslashes($webinar->title)) ?>', '<?= date('M j, Y - h:i A', strtotime($webinar->scheduled_at)) ?>')" title="View Attendees List">
                                                    <i class="ti ti-users"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-info-light" onclick="editWebinar(<?= htmlspecialchars(json_encode($webinar)) ?>)" title="Edit Webinar">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <?php if (!empty($link)): ?>
                                                    <a href="<?= esc($link) ?>" class="btn btn-sm btn-icon btn-success-light" target="_blank" title="Launch / Join Meeting">
                                                        <i class="ti ti-video"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <form action="<?= base_url('admin/webinars/delete/' . $webinar->id) ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this webinar?')">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-icon btn-danger-light" title="Delete Webinar" aria-label="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
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

<!-- Modal 1: Preview Webinar Details & Meeting Link -->
<div class="modal fade" id="webinarPreviewModal" tabindex="-1" aria-labelledby="webinarPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light border-bottom">
                <h6 class="modal-title fw-bold" id="webinarPreviewModalLabel"><i class="ti ti-presentation text-primary me-2"></i>Webinar Preview &amp; Meeting Link</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="overflow-y: auto;">
                <!-- Flyer preview if available -->
                <div id="prev_flyer_wrap" class="mb-3 text-center d-none">
                    <img src="" id="prev_flyer_img" class="rounded border shadow-sm w-100" style="max-height: 220px; object-fit: cover;" alt="Webinar Flyer">
                </div>

                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark" id="prev_title"></h4>
                        <div class="text-muted fs-13 d-flex align-items-center gap-3 flex-wrap">
                            <span><i class="ti ti-user text-primary me-1"></i> Hosted by: <strong id="prev_speaker"></strong></span>
                            <span><i class="ti ti-calendar text-primary me-1"></i> <span id="prev_date"></span></span>
                        </div>
                    </div>
                    <div>
                        <span id="prev_status_badge" class="badge bg-info-transparent fs-12"></span>
                    </div>
                </div>

                <!-- Meeting Link Box -->
                <div class="card border border-primary bg-primary-transparent p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold fs-13 text-primary"><i class="ti ti-video me-1"></i> Live Meeting Join Link</span>
                        <span id="prev_provider_badge" class="badge bg-primary text-white"></span>
                    </div>
                    <div class="input-group">
                        <input type="text" id="prev_meeting_link" class="form-control bg-white" readonly>
                        <button type="button" class="btn btn-outline-primary" onclick="copyMeetingLinkFromInput('prev_meeting_link')">
                            <i class="ti ti-copy me-1"></i> Copy Link
                        </button>
                        <a href="#" id="prev_join_btn" target="_blank" class="btn btn-primary">
                            <i class="ti ti-external-link me-1"></i> Open Meeting Link
                        </a>
                    </div>
                    <small class="text-muted mt-1 d-block fs-11">This link is sent to registered candidates upon confirmation and reminder emails.</small>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <h6 class="fw-bold fs-13 text-secondary mb-1">Webinar Overview / Topics</h6>
                    <p class="text-muted fs-13 mb-0" id="prev_desc" style="white-space: pre-line;"></p>
                </div>

                <!-- Quick Stats / Attendees shortcut -->
                <div class="row g-2 pt-2 border-top">
                    <div class="col-md-6">
                        <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-11 text-muted d-block">Access Type</span>
                                <strong id="prev_access"></strong>
                            </div>
                            <i class="ti ti-ticket fs-3 text-secondary"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-11 text-muted d-block">Registered Attendees</span>
                                <strong id="prev_attendees_count">0 Candidates</strong>
                            </div>
                            <button type="button" id="prev_view_attendees_btn" class="btn btn-xs btn-primary">
                                <i class="ti ti-users me-1"></i> View List
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <a href="<?= base_url('webinars') ?>" target="_blank" class="btn btn-outline-secondary">
                    <i class="ti ti-world me-1"></i> View Public Listing
                </a>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Webinar Attendees & Registrations Roster -->
<div class="modal fade" id="webinarAttendeesModal" tabindex="-1" aria-labelledby="webinarAttendeesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="modal-title fw-bold" id="webinarAttendeesModalLabel"><i class="ti ti-users text-primary me-2"></i>Webinar Attendees Roster</h6>
                    <div class="text-muted fs-12" id="att_modal_subtitle">Loading webinar info...</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="att_export_btn" class="btn btn-sm btn-success">
                        <i class="ti ti-download me-1"></i> Export Attendees (CSV)
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-4" style="overflow-y: auto;">
                <!-- Search & Counter Bar -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary fs-13 px-3 py-2" id="att_count_badge">0 Total Registered</span>
                    </div>
                    <div style="min-width: 250px;">
                        <input type="text" id="att_search_input" class="form-control form-control-sm" placeholder="Search attendees by name, email, phone..." onkeyup="filterAttendeesTable()">
                    </div>
                </div>

                <div id="att_loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted fs-13 mt-2">Fetching registered candidates...</p>
                </div>

                <div id="att_empty" class="text-center py-5 d-none">
                    <i class="ti ti-user-x fs-1 text-muted d-block mb-2"></i>
                    <h6 class="text-dark fw-semibold">No registered attendees yet</h6>
                    <p class="text-muted fs-13 mb-0">When candidates register for this webinar, their contact info and details will appear here.</p>
                </div>

                <div id="att_table_wrap" class="table-responsive d-none">
                    <table class="table text-nowrap table-hover align-middle border" id="att_table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Candidate / User</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Registration Date</th>
                            </tr>
                        </thead>
                        <tbody id="att_table_body">
                            <!-- Injected by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: Add / Edit Webinar Modal -->
<div class="modal fade" id="addWebinarModal" tabindex="-1" aria-labelledby="addWebinarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
            <form action="<?= base_url('admin/webinars/save') ?>" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100 mb-0" style="overflow: hidden; min-height: 0;">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="webinar_id" value="">
                <div class="modal-header flex-shrink-0 bg-light border-bottom">
                    <h6 class="modal-title fw-bold" id="webinarModalTitle"><i class="ti ti-calendar-plus text-primary me-2"></i>Schedule New Webinar Class</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="overflow-y: auto; flex: 1 1 auto; max-height: calc(90vh - 140px);">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Webinar / Class Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="w_title" class="form-control" placeholder="e.g. Masterclass on Technical Interview Preparation" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Featured Image / Flyer</label>
                            <input type="file" name="flyer_image" id="w_flyer" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1">Upload a promotional featured banner/flyer (PNG, JPG, WEBP).</small>
                            <div id="w_flyer_preview_box" class="mt-2 d-none">
                                <span class="fs-12 text-muted d-block mb-1">Current Flyer:</span>
                                <img src="" id="w_flyer_img" class="rounded border" style="max-height: 120px; max-width: 100%; object-fit: cover;">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Speaker / Host Name <span class="text-danger">*</span></label>
                            <input type="text" name="speaker_name" id="w_speaker" class="form-control" placeholder="e.g. Dr. Jane Smith" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Scheduled Date &amp; Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="scheduled_at" id="w_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Access Type</label>
                            <select name="access_type" id="w_access_type" class="form-select" onchange="togglePriceField(this.value)">
                                <option value="free">Free Access</option>
                                <option value="paid">Paid Access</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="price_wrap" style="display:none;">
                            <label class="form-label fw-semibold">Price (&#x20A6;) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="price" id="w_price" class="form-control" placeholder="e.g. 2500.00" value="0.00">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" id="w_status" class="form-select">
                                <option value="upcoming">Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Meeting URL (Zoom / Google Meet / Teams) <span class="text-danger">*</span></label>
                            <input type="url" name="meeting_link" id="w_link" class="form-control" placeholder="https://zoom.us/j/... or https://meet.google.com/..." required>
                            <small class="text-muted d-block mt-1">Paste the full join link from Zoom, Google Meet, or Microsoft Teams.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Short Description / Agenda</label>
                            <textarea name="description" id="w_desc" class="form-control" rows="3" placeholder="Brief outline of the webinar topics and learning outcomes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-shrink-0 bg-light border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="saveWebinarBtn"><i class="ti ti-check me-1"></i> Schedule Class</button>
                </div>
            </form>
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

function copyMeetingLink(link) {
    if (!link) {
        alert('No meeting link available to copy.');
        return;
    }
    navigator.clipboard.writeText(link).then(function() {
        alert('Meeting link copied to clipboard:\n' + link);
    }).catch(function() {
        prompt('Copy the meeting link manually:', link);
    });
}

function copyMeetingLinkFromInput(inputId) {
    var input = document.getElementById(inputId);
    if (input && input.value) {
        navigator.clipboard.writeText(input.value).then(function() {
            alert('Meeting link copied to clipboard:\n' + input.value);
        }).catch(function() {
            prompt('Copy the meeting link manually:', input.value);
        });
    }
}

function previewWebinar(webinar, regCount) {
    document.getElementById('prev_title').innerText = webinar.title || 'Untitled Webinar';
    document.getElementById('prev_speaker').innerText = webinar.speaker_name || 'N/A';
    document.getElementById('prev_date').innerText = webinar.scheduled_at ? new Date(webinar.scheduled_at).toLocaleString() : 'N/A';
    document.getElementById('prev_desc').innerText = webinar.description || 'No description provided.';
    
    var link = webinar.meeting_link || '';
    document.getElementById('prev_meeting_link').value = link;
    var joinBtn = document.getElementById('prev_join_btn');
    if (link) {
        joinBtn.href = link;
        joinBtn.classList.remove('disabled');
    } else {
        joinBtn.href = '#';
        joinBtn.classList.add('disabled');
    }

    var provider = 'External Link';
    if (link.toLowerCase().includes('zoom.us')) provider = 'Zoom Meeting';
    else if (link.toLowerCase().includes('meet.google.com')) provider = 'Google Meet';
    else if (link.toLowerCase().includes('teams.microsoft.com')) provider = 'MS Teams';
    document.getElementById('prev_provider_badge').innerText = provider;

    var status = webinar.status || 'upcoming';
    var statusBadge = document.getElementById('prev_status_badge');
    statusBadge.innerText = status.toUpperCase();

    var accessType = webinar.access_type === 'paid' ? '₦' + parseFloat(webinar.price || 0).toLocaleString(undefined, {minimumFractionDigits: 2}) + ' (Paid)' : 'Free Access';
    document.getElementById('prev_access').innerText = accessType;

    var count = regCount !== undefined ? regCount : (webinar.registrations_count || 0);
    document.getElementById('prev_attendees_count').innerText = count + ' Candidate' + (count === 1 ? '' : 's');

    var viewAttendeesBtn = document.getElementById('prev_view_attendees_btn');
    viewAttendeesBtn.onclick = function() {
        bootstrap.Modal.getInstance(document.getElementById('webinarPreviewModal')).hide();
        viewAttendees(webinar.id, webinar.title, webinar.scheduled_at);
    };

    var flyerWrap = document.getElementById('prev_flyer_wrap');
    var flyerImg = document.getElementById('prev_flyer_img');
    if (webinar.flyer_image) {
        flyerImg.src = '<?= base_url() ?>' + webinar.flyer_image;
        flyerWrap.classList.remove('d-none');
    } else {
        flyerWrap.classList.add('d-none');
    }

    var previewModal = new bootstrap.Modal(document.getElementById('webinarPreviewModal'));
    previewModal.show();
}

var currentAttendeesList = [];

function viewAttendees(webinarId, title, scheduledAt) {
    document.getElementById('att_modal_subtitle').innerText = title + ' (' + scheduledAt + ')';
    document.getElementById('att_export_btn').href = '<?= base_url("admin/webinars/export-attendees") ?>/' + webinarId;
    
    document.getElementById('att_loading').classList.remove('d-none');
    document.getElementById('att_empty').classList.add('d-none');
    document.getElementById('att_table_wrap').classList.add('d-none');
    document.getElementById('att_search_input').value = '';

    var modal = new bootstrap.Modal(document.getElementById('webinarAttendeesModal'));
    modal.show();

    fetch('<?= base_url("admin/webinars/attendees") ?>/' + webinarId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('att_loading').classList.add('d-none');
        if (data.success && data.attendees && data.attendees.length > 0) {
            currentAttendeesList = data.attendees;
            document.getElementById('att_count_badge').innerText = data.attendees.length + ' Total Registered';
            renderAttendeesTable(data.attendees);
            document.getElementById('att_table_wrap').classList.remove('d-none');
        } else {
            currentAttendeesList = [];
            document.getElementById('att_count_badge').innerText = '0 Total Registered';
            document.getElementById('att_empty').classList.remove('d-none');
        }
    })
    .catch(err => {
        document.getElementById('att_loading').classList.add('d-none');
        document.getElementById('att_empty').classList.remove('d-none');
        document.getElementById('att_empty').querySelector('p').innerText = 'Error loading attendees.';
    });
}

function renderAttendeesTable(attendees) {
    var tbody = document.getElementById('att_table_body');
    var html = '';
    attendees.forEach((att, idx) => {
        var avatar = att.avatar ? `<img src="${att.avatar}" class="avatar avatar-sm rounded-circle me-2" alt="">` : `<span class="avatar avatar-sm rounded-circle bg-primary-transparent text-primary fw-bold me-2">${escapeHtml(att.full_name.substring(0, 2).toUpperCase())}</span>`;
        var regDate = att.registered_at ? new Date(att.registered_at).toLocaleString() : 'N/A';

        html += `<tr>
            <td class="text-muted fs-12">${idx + 1}</td>
            <td>
                <div class="d-flex align-items-center">
                    ${avatar}
                    <div>
                        <div class="fw-semibold fs-13 text-dark">${escapeHtml(att.full_name)}</div>
                        <div class="text-muted fs-11">${att.user_type ? capitalize(att.user_type) : 'User'}</div>
                    </div>
                </div>
            </td>
            <td>
                <span class="fs-13 text-dark"><i class="ti ti-mail text-muted me-1"></i>${escapeHtml(att.email || 'N/A')}</span>
            </td>
            <td>
                <span class="fs-13 text-dark"><i class="ti ti-phone text-muted me-1"></i>${escapeHtml(att.phone || 'N/A')}</span>
            </td>
            <td>
                <span class="text-muted fs-12"><i class="ti ti-clock me-1"></i>${regDate}</span>
            </td>
        </tr>`;
    });
    tbody.innerHTML = html;
}

function filterAttendeesTable() {
    var term = document.getElementById('att_search_input').value.toLowerCase().trim();
    if (!term) {
        renderAttendeesTable(currentAttendeesList);
        return;
    }
    var filtered = currentAttendeesList.filter(a => {
        return (a.full_name && a.full_name.toLowerCase().includes(term)) ||
               (a.email && a.email.toLowerCase().includes(term)) ||
               (a.phone && a.phone.toLowerCase().includes(term));
    });
    renderAttendeesTable(filtered);
}

function resetWebinarModal() {
    document.getElementById('webinar_id').value = '';
    document.getElementById('w_title').value = '';
    document.getElementById('w_speaker').value = '';
    document.getElementById('w_date').value = '';
    document.getElementById('w_link').value = '';
    document.getElementById('w_desc').value = '';
    document.getElementById('w_status').value = 'upcoming';
    document.getElementById('w_access_type').value = 'free';
    document.getElementById('w_price').value = '0.00';
    togglePriceField('free');
    
    document.getElementById('w_flyer').value = '';
    const previewBox = document.getElementById('w_flyer_preview_box');
    if (previewBox) previewBox.classList.add('d-none');
    const previewImg = document.getElementById('w_flyer_img');
    if (previewImg) previewImg.src = '';

    document.getElementById('webinarModalTitle').innerText = 'Schedule New Webinar Class';
    document.getElementById('saveWebinarBtn').innerText = 'Schedule Class';
}

function editWebinar(webinar) {
    document.getElementById('webinar_id').value = webinar.id || '';
    document.getElementById('w_title').value = webinar.title || '';
    document.getElementById('w_speaker').value = webinar.speaker_name || '';
    document.getElementById('w_date').value = (webinar.scheduled_at || '').replace(' ', 'T').substring(0, 16);
    document.getElementById('w_link').value = webinar.meeting_link || '';
    document.getElementById('w_desc').value = webinar.description || '';
    document.getElementById('w_status').value = webinar.status || 'upcoming';
    
    const accessType = webinar.access_type || 'free';
    document.getElementById('w_access_type').value = accessType;
    document.getElementById('w_price').value = webinar.price || '0.00';
    togglePriceField(accessType);
    
    const previewBox = document.getElementById('w_flyer_preview_box');
    const previewImg = document.getElementById('w_flyer_img');
    if (webinar.flyer_image) {
        previewImg.src = '<?= base_url() ?>' + webinar.flyer_image;
        previewBox.classList.remove('d-none');
    } else {
        previewBox.classList.add('d-none');
    }

    document.getElementById('webinarModalTitle').innerText = 'Edit Webinar Class Details';
    document.getElementById('saveWebinarBtn').innerText = 'Update Class';
    
    var editModal = new bootstrap.Modal(document.getElementById('addWebinarModal'));
    editModal.show();
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function capitalize(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

document.addEventListener("DOMContentLoaded", function() {
    const webinarModalEl = document.getElementById('addWebinarModal');
    if (webinarModalEl) {
        webinarModalEl.addEventListener('show.bs.modal', function(event) {
            if (event.relatedTarget && !event.relatedTarget.hasAttribute('onclick')) {
                resetWebinarModal();
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('create') === '1') {
        resetWebinarModal();
        var addModal = new bootstrap.Modal(document.getElementById('addWebinarModal'));
        addModal.show();
    }
});
</script>
<?= $this->endSection() ?>
