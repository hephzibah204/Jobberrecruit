<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">
                <a href="<?= base_url(auth()->user()->user_type === 'employer' ? 'employer/messages' : 'candidate/messages') ?>" class="text-muted me-2">
                    <i data-feather="arrow-left"></i>
                </a>
                Messages
            </h4>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center bg-light">
            <?php
                $user = auth()->user();
                if ($user->user_type === 'employer') {
                    $name = $otherParty->full_name ?? 'Candidate';
                    $avatar = $otherParty->profile_photo ?? base_url('images/default-avatar.png');
                } else {
                    $name = $otherParty->company_name ?? 'Employer';
                    $avatar = $otherParty->logo ?? base_url('images/favicon.png');
                }
            ?>
            <img src="<?= $avatar ?>" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="">
            <div>
                <h6 class="mb-0 fw-semibold"><?= esc($name) ?></h6>
                <?php if (!empty($conversation['job_id'])): ?>
                    <small class="text-muted">Regarding: <?= esc($conversation['job_title'] ?? 'a job') ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;" id="messageContainer">
            <?php if (empty($messages)): ?>
                <div class="text-center py-4">
                    <p class="text-muted">No messages yet. Start the conversation!</p>
                </div>
            <?php else: ?>
                <div class="p-3">
                    <?php foreach ($messages as $msg): ?>
                        <?php $isOwn = $msg['sender_id'] == $user->id; ?>
                        <div class="d-flex mb-3 <?= $isOwn ? 'justify-content-end' : '' ?>">
                            <div class="message-bubble p-2 px-3 rounded <?= $isOwn ? 'bg-primary text-white' : 'bg-light' ?>" style="max-width: 70%;">
                                <p class="mb-1 small"><?= nl2br(esc($msg['message'])) ?></p>
                                <small class="<?= $isOwn ? 'text-white-50' : 'text-muted' ?>" style="font-size: 0.7rem;">
                                    <?= date('g:i A', strtotime($msg['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="card-footer">
            <form id="messageForm" class="d-flex gap-2">
                <input type="hidden" name="conversation_id" value="<?= $conversation['id'] ?>">
                <input type="hidden" name="recipient_id" value="<?= $user->user_type === 'employer' ? $conversation['job_seeker_id'] : $conversation['employer_id'] ?>">
                <input type="text" id="messageInput" class="form-control" placeholder="Type a message..." required autocomplete="off">
                <button type="submit" class="btn btn-primary" id="sendBtn"><i data-feather="send"></i></button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messageContainer');
    if (container) container.scrollTop = container.scrollHeight;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const csrfHeader = 'X-CSRF-TOKEN';

    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const msg = input.value.trim();
        if (!msg) return;

        sendBtn.disabled = true;

        const formData = new FormData();
        formData.append('message', msg);
        formData.append('recipient_id', this.querySelector('[name="recipient_id"]').value);
        formData.append('job_id', '<?= $conversation['job_id'] ?? 0 ?>');

        const sendUrl = '<?= base_url(auth()->user()->user_type === "employer" ? "employer/messages/send" : "candidate/messages/send") ?>';

        fetch(sendUrl, {
            method: 'POST',
            headers: {
                [csrfHeader]: csrfToken
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success || data.status === 'success') {
                const container = document.getElementById('messageContainer');
                const div = document.createElement('div');
                div.className = 'd-flex mb-3 justify-content-end';
                div.innerHTML = `<div class="message-bubble p-2 px-3 rounded bg-primary text-white" style="max-width: 70%;">
                    <p class="mb-1 small">${msg.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>')}</p>
                    <small class="text-white-50" style="font-size: 0.7rem;">Just now</small>
                </div>`;
                container.appendChild(div);
                container.scrollTop = container.scrollHeight;
                input.value = '';
            } else {
                alert(data.message || 'Failed to send message');
            }
            sendBtn.disabled = false;
        })
        .catch(err => {
            console.error('Error sending message:', err);
            alert('Network error. Please try again.');
            sendBtn.disabled = false;
        });
    });

    if (container) container.scrollTop = container.scrollHeight;
});
</script>
<?= $this->endSection() ?>
