<?= $this->extend('layouts/app') ?>
<?= $this->section('styles') ?>
<style>
    .notification-item {
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
        cursor: pointer;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-unread {
        background-color: #f0f7ff;
        border-left-color: #0d6efd;
    }

    .notification-read {
        opacity: 0.75;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .notification-icon i {
        font-size: 20px;
    }

    .type-badge {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 20px;
    }

    .filter-btn.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Notifications</h4>
                <h6>View and manage your notifications</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="location.reload()">
                    <i class="ti ti-refresh"></i>
                </a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read" id="mark-all-read">
                    <i class="ti ti-mail-opened"></i>
                </a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="<?= site_url('employer/dashboard') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card custom-card bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Notifications</h6>
                            <h4 class="mb-0"><?= number_format($totalNotifications) ?></h4>
                        </div>
                        <div class="avatar bg-primary-transparent">
                            <i class="ti ti-bell fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Unread</h6>
                            <h4 class="mb-0 text-warning" id="unread-count"><?= number_format($unreadCount) ?></h4>
                        </div>
                        <div class="avatar bg-warning-transparent">
                            <i class="ti ti-mail fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">New Applications</h6>
                            <h4 class="mb-0"><?= number_format($typeStats['new_application'] ?? 0) ?></h4>
                        </div>
                        <div class="avatar bg-success-transparent">
                            <i class="ti ti-user-check fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Job Updates</h6>
                            <h4 class="mb-0"><?= number_format(($typeStats['job_approved'] ?? 0) + ($typeStats['job_rejected'] ?? 0) + ($typeStats['job_pending'] ?? 0)) ?></h4>
                        </div>
                        <div class="avatar bg-info-transparent">
                            <i class="ti ti-briefcase fs-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card custom-card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Notifications</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-5">
                    <i class="ti ti-bell-off fs-48 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">No notifications yet</h5>
                    <p class="text-muted">When you receive notifications, they will appear here.</p>
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush" id="notifications-list">
                    <?php foreach ($notifications as $notification): ?>
                        <?php $typeInfo = \App\Models\JobNotificationModel::getTypeInfo($notification->type); ?>
                        <div class="list-group-item notification-item <?= $notification->is_read ? 'notification-read' : 'notification-unread' ?>" data-id="<?= $notification->id ?>">
                            <div class="d-flex align-items-start gap-3">
                                <div class="notification-icon bg-<?= $typeInfo['color'] ?>-transparent">
                                    <i class="ti <?= $typeInfo['icon'] ?> text-<?= $typeInfo['color'] ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                        <div>
                                            <h6 class="mb-1">
                                                <?= esc($notification->title) ?>
                                                <?php if (!$notification->is_read): ?>
                                                    <span class="badge bg-primary ms-2">New</span>
                                                <?php endif; ?>
                                            </h6>
                                            <p class="mb-1 text-muted small"><?= esc($notification->message) ?></p>
                                            <?php if ($notification->job_title): ?>
                                                <span class="type-badge bg-light text-dark border">
                                                    <i class="ti ti-briefcase me-1"></i><?= esc($notification->job_title) ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($notification->first_name): ?>
                                                <span class="type-badge bg-light text-dark border">
                                                    <i class="ti ti-user me-1"></i><?= esc($notification->first_name . ' ' . $notification->last_name) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted"><?= time_elapsed_string($notification->created_at) ?></small>
                                            <div class="mt-2">
                                                <?php if (!$notification->is_read): ?>
                                                    <button class="btn btn-sm btn-link text-primary mark-read-btn" data-id="<?= $notification->id ?>">
                                                        <i class="ti ti-check"></i> Mark as read
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-link text-danger delete-notification-btn" data-id="<?= $notification->id ?>">
                                                    <i class="ti ti-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php if ($totalNotifications > $perPage): ?>
            <div class="card-footer">
                <?= $pager ?? '' ?>
            </div>
        <?php endif; ?>
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

    // Mark single notification as read
    $('.mark-read-btn').on('click', function(e) {
        e.stopPropagation();
        const btn = $(this);
        const notificationId = btn.data('id');
        const notificationItem = btn.closest('.notification-item');

        $.ajax({
            url: '<?= site_url("employer/notifications/mark-read") ?>',
            type: 'POST',
            data: {
                notification_id: notificationId,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    notificationItem.removeClass('notification-unread').addClass('notification-read');
                    btn.remove();

                    // Update unread count
                    $('#unread-count').text(response.unreadCount);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Failed to mark notification as read');
            }
        });
    });

    // Mark all notifications as read
    $('#mark-all-read').on('click', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= site_url("employer/notifications/mark-all-read") ?>',
            type: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('.notification-item').removeClass('notification-unread').addClass('notification-read');
                    $('.mark-read-btn').remove();
                    $('#unread-count').text('0');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Failed to mark all as read');
            }
        });
    });

    // Delete notification
    $('.delete-notification-btn').on('click', function(e) {
        e.stopPropagation();
        const btn = $(this);
        const notificationId = btn.data('id');
        const notificationItem = btn.closest('.notification-item');

        if (confirm('Are you sure you want to delete this notification?')) {
            $.ajax({
                url: '<?= site_url("employer/notifications/delete") ?>',
                type: 'POST',
                data: {
                    notification_id: notificationId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        notificationItem.fadeOut(300, function() {
                            $(this).remove();
                            $('#unread-count').text(response.unreadCount);

                            // Show empty state if no notifications
                            if ($('.notification-item').length === 0) {
                                location.reload();
                            }
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Failed to delete notification');
                }
            });
        }
    });

    // Helper function for time elapsed
    function time_elapsed_string($datetime, $full = false) {
        // This is handled server-side, but keeping for reference
    }
</script>
<?= $this->endSection() ?>