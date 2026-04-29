<?= $this->extend('layouts/app') ?>
<?= $this->section('styles') ?>
<style>
    .note-card {
        transition: all 0.2s ease;
        border-left: 4px solid #0d6efd;
    }

    .note-card:hover {
        background-color: #f8f9fa;
    }

    .note-internal {
        border-left-color: #6c757d;
    }

    .note-feedback {
        border-left-color: #28a745;
    }

    .note-reminder {
        border-left-color: #ffc107;
    }

    .status-badge {
        font-size: 0.8rem;
        padding: 5px 12px;
    }

    .guest-badge {
        background-color: #6c757d;
        color: white;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 8px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Application Details</h4>
                <h6>Review candidate application</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="location.reload()">
                    <i class="ti ti-refresh"></i>
                </a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">
                    <i class="ti ti-chevron-up"></i>
                </a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="<?= site_url('employer/applications') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Applications
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Application Info -->
        <div class="col-xl-8">
            <!-- Candidate Info Card -->
            <div class="card custom-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Candidate Information
                        <?php if ($application->is_guest): ?>
                            <span class="guest-badge">
                                <i class="ti ti-user-off me-1"></i>Guest Applicant
                            </span>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase">Full Name</label>
                                <p class="fw-semibold mb-0"><?= esc($application->first_name . ' ' . $application->last_name) ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase">Email Address</label>
                                <p class="mb-0">
                                    <a href="mailto:<?= esc($application->email) ?>"><?= esc($application->email) ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase">Phone Number</label>
                                <p class="mb-0">
                                    <a href="tel:<?= esc($application->phone) ?>"><?= esc($application->phone) ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase">Applied For</label>
                                <p class="mb-0"><?= esc($application->job_title) ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase">Applied Date</label>
                                <p class="mb-0"><?= date('F d, Y H:i', strtotime($application->created_at)) ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase">Status</label>
                                <div>
                                    <select class="form-select form-select-sm status-select" style="width: 150px;" data-id="<?= $application->id ?>">
                                        <option value="pending" <?= $application->status == 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                                        <option value="reviewed" <?= $application->status == 'reviewed' ? 'selected' : '' ?>>👁️ Reviewed</option>
                                        <option value="shortlisted" <?= $application->status == 'shortlisted' ? 'selected' : '' ?>>⭐ Shortlisted</option>
                                        <option value="rejected" <?= $application->status == 'rejected' ? 'selected' : '' ?>>❌ Rejected</option>
                                        <option value="hired" <?= $application->status == 'hired' ? 'selected' : '' ?>>✅ Hired</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($application->cv_path): ?>
                        <div class="mt-3">
                            <a href="<?= base_url($application->cv_path) ?>" class="btn btn-outline-primary" download>
                                <i class="ti ti-download me-1"></i>Download CV/Resume
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pre-screening Answers (ATS) -->
            <?php if (!empty($answers)): ?>
                <div class="card custom-card mb-4 border-primary">
                    <div class="card-header bg-primary-transparent">
                        <h5 class="card-title mb-0"><i class="ti ti-help-circle me-1"></i>Pre-screening Questionnaire Answers</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($answers as $ans): ?>
                            <div class="mb-4 last-child-mb-0">
                                <label class="text-muted small text-uppercase d-block mb-1"><?= esc($ans->question) ?></label>
                                <div class="p-3 bg-light rounded border-start border-4 border-primary">
                                    <p class="mb-0 fw-semibold text-dark">
                                        <?php if ($ans->type === 'checkbox'): ?>
                                            <?php 
                                                $vals = explode(', ', $ans->answer);
                                                foreach($vals as $v):
                                            ?>
                                                <span class="badge bg-primary me-1 mb-1"><?= esc($v) ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?= nl2br(esc($ans->answer)) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Cover Letter / Message Card -->

            <?php if ($application->cover_letter): ?>
                <div class="card custom-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Cover Letter / Message</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?= nl2br(esc($application->cover_letter)) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Experience & Education -->
            <div class="card custom-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Experience & Education</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase">Years of Experience</label>
                            <p class="mb-3"><?= esc($application->experience ?? 'Not specified') ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase">Education Level</label>
                            <p class="mb-3"><?= esc($application->education ?? 'Not specified') ?></p>
                        </div>
                    </div>
                    <?php if ($application->skills): ?>
                        <div>
                            <label class="text-muted small text-uppercase">Skills</label>
                            <p><?= esc($application->skills) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Job Details Card -->
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Job Details</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-semibold"><?= esc($application->job_title) ?></h6>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Job Description</label>
                        <p><?= html_entity_decode(esc($application->job_description)) ?></p>
                    </div>
                    <?php if ($application->job_requirements): ?>
                        <div>
                            <label class="text-muted small text-uppercase">Requirements</label>
                            <p><?= html_entity_decode(esc($application->job_requirements)) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column - Notes & Actions -->
        <div class="col-xl-4">
            <!-- Actions Card -->
            <div class="card custom-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-success quick-action" data-status="shortlisted">
                            <i class="ti ti-star me-1"></i> Shortlist Candidate
                        </button>
                        <button class="btn btn-info quick-action" data-status="reviewed">
                            <i class="ti ti-eye me-1"></i> Mark as Reviewed
                        </button>
                        <button class="btn btn-danger quick-action" data-status="rejected">
                            <i class="ti ti-x me-1"></i> Reject Candidate
                        </button>
                        <button class="btn btn-primary quick-action" data-status="hired">
                            <i class="ti ti-user-check me-1"></i> Mark as Hired
                        </button>
                        <hr>
                        <a href="mailto:<?= esc($application->email) ?>" class="btn btn-outline-primary">
                            <i class="ti ti-mail me-1"></i> Send Email
                        </a>
                        <?php if ($application->cv_path): ?>
                            <a href="<?= base_url($application->cv_path) ?>" class="btn btn-outline-secondary" download>
                                <i class="ti ti-download me-1"></i> Download CV
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-notes me-1"></i> Notes
                        <span class="badge bg-primary ms-2" id="note-count"><?= count($notes) ?></span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3" id="notes-list" style="max-height: 400px; overflow-y: auto;">
                        <?php if (empty($notes)): ?>
                            <p class="text-muted text-center py-3">No notes yet. Add a note to keep track of this candidate.</p>
                        <?php else: ?>
                            <?php foreach ($notes as $note): ?>
                                <div class="note-card card mb-2 note-<?= $note->type ?>">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <p class="mb-1"><?= nl2br(esc($note->note)) ?></p>
                                                <small class="text-muted">
                                                    <i class="ti ti-user me-1"></i><?= esc($note->created_by_name ?? 'System') ?>
                                                    <i class="ti ti-clock ms-2 me-1"></i><?= date('M d, Y H:i', strtotime($note->created_at)) ?>
                                                </small>
                                            </div>
                                            <button class="btn btn-sm btn-link text-danger delete-note" data-id="<?= $note->id ?>" style="padding: 0;">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="border-top p-3">
                        <div class="mb-2">
                            <select id="note-type" class="form-select form-select-sm mb-2">
                                <option value="internal">📝 Internal Note</option>
                                <option value="feedback">💬 Feedback</option>
                                <option value="reminder">⏰ Reminder</option>
                            </select>
                            <textarea id="note-text" class="form-control" rows="3" placeholder="Write a note..."></textarea>
                        </div>
                        <button class="btn btn-primary btn-sm w-100" id="add-note-btn">
                            <i class="ti ti-plus me-1"></i> Add Note
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalTitle">Update Application Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pendingStatus" value="">

                <div class="alert alert-info mb-3">
                    <i class="ti ti-info-circle me-2"></i>
                    <strong>Note:</strong> The candidate will receive an email with your message below.
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Message to Candidate <span class="text-danger">*</span></label>
                    <textarea id="statusMessage" class="form-control" rows="5"
                        placeholder="Write a personalized message to the candidate explaining the status update..."></textarea>
                    <small class="text-muted">This message will be included in the email sent to the candidate.</small>
                </div>

                <div class="mb-3" id="guest-warning" style="display: none;">
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>Guest Applicant:</strong> This candidate applied as a guest. They will receive this update via email only since they don't have an account.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Send & Update Status</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 4000
    };

    let pendingStatus = null;
    let isInitialLoad = true;

    $(document).ready(function() {
        // Set initial status value and prevent change event on load
        const statusSelect = $('.status-select');
        if (statusSelect.length) {
            statusSelect.data('original-status', statusSelect.val());

            setTimeout(() => {
                isInitialLoad = false;
            }, 100);
        }

        // Attach note delete events
        attachDeleteEvent();
    });

    // Get default message for status
    function getDefaultMessageForStatus(status) {
        const messages = {
            'shortlisted': "Congratulations! We are pleased to inform you that you have been shortlisted for this position.\n\nOur recruitment team will contact you shortly to schedule an interview.\n\nThank you for your interest in joining our team.",
            'reviewed': "Thank you for your application. We have reviewed your profile and qualifications.\n\nWe will get back to you shortly with an update.\n\nBest regards,\nHiring Team",
            'rejected': "Thank you for your interest in this position.\n\nAfter careful review of all applications, we regret to inform you that we have decided to move forward with other candidates whose qualifications more closely match our current needs.\n\nWe wish you the best in your job search.",
            'hired': "Congratulations! We are delighted to inform you that you have been selected for this position.\n\nOur HR team will contact you shortly with the offer letter and onboarding details.\n\nWelcome to the team!"
        };
        return messages[status] || "Your application status has been updated. Please check your email for details.";
    }

    // Update application status with message
    function updateStatusWithMessage(status, message) {
        const statusSelect = $('.status-select');
        const originalStatus = statusSelect.data('original-status');

        statusSelect.prop('disabled', true);

        $.ajax({
            url: '<?= site_url("employer/applications/update-status") ?>',
            type: 'POST',
            data: {
                application_id: <?= $application->id ?>,
                status: status,
                message_to_candidate: message,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    statusSelect.val(status);
                    statusSelect.data('original-status', status);
                    statusSelect.find('option').removeAttr('selected');
                    statusSelect.find('option[value="' + status + '"]').attr('selected', 'selected');

                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message);
                    statusSelect.val(originalStatus);
                }
                statusSelect.prop('disabled', false);
            },
            error: function() {
                toastr.error('Failed to update status');
                statusSelect.val(originalStatus);
                statusSelect.prop('disabled', false);
            }
        });
    }

    // Quick action buttons click handler
    $('.quick-action').on('click', function(e) {
        e.preventDefault();
        pendingStatus = $(this).data('status');

        const statusText = $(this).text().trim();
        const isGuest = <?= $application->is_guest ?? 0 ?>;

        $('#statusModalTitle').text(statusText);
        $('#statusMessage').val(getDefaultMessageForStatus(pendingStatus));

        if (isGuest) {
            $('#guest-warning').show();
        } else {
            $('#guest-warning').hide();
        }

        $('#statusUpdateModal').modal('show');
    });

    // Confirm status update button
    $('#confirmStatusUpdate').on('click', function() {
        const message = $('#statusMessage').val().trim();

        if (!message) {
            toastr.warning('Please enter a message for the candidate');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');

        updateStatusWithMessage(pendingStatus, message);

        setTimeout(() => {
            btn.prop('disabled', false).html('Send & Update Status');
            $('#statusUpdateModal').modal('hide');
        }, 2000);
    });

    // Status select change - show modal for all changes
    $('.status-select').on('change', function() {
        if (isInitialLoad) {
            return;
        }

        const status = $(this).val();
        const isGuest = <?= $application->is_guest ?? 0 ?>;

        pendingStatus = status;

        const statusText = $(this).find('option:selected').text().trim();
        $('#statusModalTitle').text(statusText);
        $('#statusMessage').val(getDefaultMessageForStatus(status));

        if (isGuest) {
            $('#guest-warning').show();
        } else {
            $('#guest-warning').hide();
        }

        $('#statusUpdateModal').modal('show');

        // Revert select value until confirmed
        $(this).val($(this).data('original-status'));
    });

    // Add note
    $('#add-note-btn').on('click', function() {
        const note = $('#note-text').val();
        const type = $('#note-type').val();

        if (!note.trim()) {
            toastr.warning('Please enter a note');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Adding...');

        $.ajax({
            url: '<?= site_url("employer/applications/add-note") ?>',
            type: 'POST',
            data: {
                application_id: <?= $application->id ?>,
                note: note,
                type: type,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);

                    const noteHtml = `
                        <div class="note-card card mb-2 note-${type}">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <p class="mb-1">${escapeHtml(response.note.note)}</p>
                                        <small class="text-muted">
                                            <i class="ti ti-user me-1"></i>${escapeHtml(response.note.created_by_name)}
                                            <i class="ti ti-clock ms-2 me-1"></i>${response.note.created_at}
                                        </small>
                                    </div>
                                    <button class="btn btn-sm btn-link text-danger delete-note" data-id="${response.note.id}" style="padding: 0;">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    const notesList = $('#notes-list');
                    if (notesList.find('.text-muted').length) {
                        notesList.empty();
                    }
                    notesList.prepend(noteHtml);
                    $('#note-text').val('');
                    $('#note-count').text(parseInt($('#note-count').text()) + 1);

                    attachDeleteEvent();
                } else {
                    toastr.error(response.message);
                }
                btn.prop('disabled', false).html('<i class="ti ti-plus me-1"></i> Add Note');
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="ti ti-plus me-1"></i> Add Note');
                toastr.error('Failed to add note');
            }
        });
    });

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Delete note
    function attachDeleteEvent() {
        $('.delete-note').off('click').on('click', function(e) {
            e.preventDefault();
            const noteId = $(this).data('id');
            const noteCard = $(this).closest('.note-card');

            if (confirm('Delete this note?')) {
                const btn = $(this);
                const originalIcon = btn.html();
                btn.html('<i class="ti ti-loader spinner-border spinner-border-sm"></i>').prop('disabled', true);

                $.ajax({
                    url: '<?= site_url("employer/applications/delete-note") ?>/' + noteId,
                    type: 'POST',
                    data: {
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            noteCard.fadeOut(300, function() {
                                $(this).remove();
                                $('#note-count').text(parseInt($('#note-count').text()) - 1);
                                toastr.success(response.message);

                                if ($('#notes-list .note-card').length === 0) {
                                    $('#notes-list').html('<p class="text-muted text-center py-3">No notes yet. Add a note to keep track of this candidate.</p>');
                                }
                            });
                        } else {
                            toastr.error(response.message);
                        }
                        btn.html(originalIcon).prop('disabled', false);
                    },
                    error: function() {
                        btn.html(originalIcon).prop('disabled', false);
                        toastr.error('Failed to delete note');
                    }
                });
            }
        });
    }

    // Enter key to submit note (Ctrl+Enter)
    $('#note-text').on('keydown', function(e) {
        if (e.ctrlKey && e.keyCode === 13) {
            e.preventDefault();
            $('#add-note-btn').click();
        }
    });
</script>
<?= $this->endSection() ?>