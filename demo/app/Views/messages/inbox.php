<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold"><i data-feather="message-square" class="me-2"></i>Messages</h4>
            <h6>Communicate with candidates and employers</h6>
        </div>
        <?php if ($unreadCount > 0): ?>
            <span class="badge bg-danger"><?= $unreadCount ?> unread</span>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($conversations)): ?>
                <div class="text-center py-5">
                    <i data-feather="inbox" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                    <h5 class="text-muted">No messages yet</h5>
                    <p class="text-muted">Start a conversation by unlocking a candidate's profile.</p>
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($conversations as $conv): ?>
                        <?php
                            $user = auth()->user();
                            if ($user->user_type === 'employer') {
                                $name = $conv['seeker_name'] ?? 'Unknown';
                                $avatar = $conv['profile_photo'] ?? base_url('images/default-avatar.png');
                                $subtitle = $conv['job_title'] ?? '';
                            } else {
                                $name = $conv['company_name'] ?? 'Unknown';
                                $avatar = $conv['employer_logo'] ?? base_url('images/favicon.png');
                                $subtitle = $conv['job_title'] ?? '';
                            }
                            $lastMsg = $conv['last_message'] ?? '';
                            $lastAt = isset($conv['last_message_at']) ? date('M j, g:i A', strtotime($conv['last_message_at'])) : '';
                            $convUrl = $user->user_type === 'employer'
                                ? base_url('employer/messages/conversation/' . $conv['id'])
                                : base_url('candidate/messages/conversation/' . $conv['id']);
                        ?>
                        <a href="<?= $convUrl ?>" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                            <img src="<?= $avatar ?>" class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;" alt="">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-semibold"><?= esc($name) ?></h6>
                                    <small class="text-muted"><?= $lastAt ?></small>
                                </div>
                                <?php if ($subtitle): ?>
                                    <small class="text-primary"><?= esc($subtitle) ?></small>
                                <?php endif; ?>
                                <p class="text-muted mb-0 small text-truncate"><?= esc($lastMsg) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
