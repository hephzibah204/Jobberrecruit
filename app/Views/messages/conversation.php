<?php $page_title = 'Messages'; ?>
<?= $this->extend(auth()->user()->user_type === 'employer' ? 'layouts/employer' : 'layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* ── Inbox Split Layout styling with active conversation ── */
.inbox {
    display: grid;
    grid-template-columns: 320px 1fr;
    border-radius: 14px;
    border: 1px solid var(--border);
    background: var(--card);
    overflow: hidden;
    min-height: 580px;
    margin-top: 20px;
}
.conv-pane {
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    background: #fdfdfe;
}
.conv-search {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
}
.search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.search-wrap svg {
    position: absolute;
    left: 12px;
    width: 14px;
    height: 14px;
    color: var(--muted);
    fill: none;
    stroke: currentColor;
    stroke-width: 2.2;
}
.search-wrap .input {
    padding-left: 36px;
    height: 38px;
    font-size: .84rem;
}
.conv-list {
    list-style: none;
    padding: 0;
    margin: 0;
    overflow-y: auto;
    flex: 1;
}
.conv-list li {
    border-bottom: 1px solid var(--border);
}
.conv {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 16px 18px;
    border: none;
    background: transparent;
    text-align: left;
    cursor: pointer;
    transition: background .15s;
    text-decoration: none;
}
.conv:hover {
    background: #fafbfd;
}
.conv.on {
    background: rgba(var(--brand-rgb),.05);
}
.ava--round {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: var(--brand-light);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Sora', sans-serif;
    font-weight: 800;
    font-size: .86rem;
    flex-shrink: 0;
    border: 1px solid var(--border);
}
.conv-body {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.conv-name {
    font-size: .88rem;
    font-weight: 700;
    color: var(--brand-deep);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.conv-prev {
    font-size: .78rem;
    color: var(--muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.conv-side {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
    font-size: .7rem;
    color: var(--muted);
}
.thread {
    display: flex;
    flex-direction: column;
    background: var(--bg);
    min-width: 0;
}
.thread-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    background: #fff;
    border-bottom: 1px solid var(--border);
}
.thread-back {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--muted);
    padding: 6px;
    border-radius: 50%;
}
.thread-back:hover {
    background: var(--bg);
}
.thread-back svg {
    width: 16px;
    height: 16px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2.2;
}
.thread-name {
    font-family: 'Sora', sans-serif;
    font-weight: 800;
    font-size: .94rem;
    color: var(--brand-deep);
}
.thread-sub {
    font-size: .74rem;
    color: var(--muted);
    margin-top: 1px;
}
.thread-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    background: #f8fafc;
    max-height: 440px;
}
.day-divider {
    align-self: center;
    font-size: .7rem;
    font-weight: 700;
    color: var(--muted);
    background: var(--border);
    padding: 3px 10px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: .02em;
}
.bubble {
    max-width: 75%;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: .84rem;
    line-height: 1.5;
    position: relative;
    word-break: break-word;
}
.bubble time {
    display: block;
    font-size: .64rem;
    opacity: .7;
    margin-top: 4px;
    text-align: right;
}
.bubble--in {
    align-self: flex-start;
    background: #fff;
    color: var(--text);
    border: 1px solid var(--border);
    border-top-left-radius: 2px;
}
.bubble--out {
    align-self: flex-end;
    background: var(--brand);
    color: #fff;
    border-top-right-radius: 2px;
}
.composer {
    display: flex;
    gap: 10px;
    padding: 14px 20px;
    background: #fff;
    border-top: 1px solid var(--border);
    align-items: center;
}
.composer .input {
    flex: 1;
    min-height: 40px;
}
.composer .btn {
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}
.composer .btn svg {
    width: 16px;
    height: 16px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2.2;
}
@media(max-width: 768px) {
    .inbox { grid-template-columns: 1fr; }
    .inbox.chat-open .conv-pane { display: none; }
    .inbox.chat-open .thread { display: flex; }
    .inbox:not(.chat-open) .thread { display: none; }
    .thread-back { display: inline-flex; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// We reload seeker conversations in full split view wrapper context
$db = \Config\Database::connect();
if (auth()->user()->user_type === 'employer') {
    $myEmployer = $db->table('employers')->where('user_id', auth()->id())->get()->getRow();
    $convsList = $myEmployer ? (new \App\Models\ConversationModel())->getConversationsForEmployer($myEmployer->id) : [];
} else {
    $convsList = (new \App\Models\ConversationModel())->getConversationsForSeeker(auth()->id());
}
?>
<div class="content">
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-chat"/></svg> Messages</h1>
            <?php if (auth()->user()->user_type === 'employer'): ?>
              <p>Communicate with candidates directly on the platform.</p>
            <?php else: ?>
              <p>Chat with employers directly — replying quickly improves your chances.</p>
            <?php endif; ?>
        </div>
    </div>

    <section class="card inbox chat-open" id="inbox" aria-label="Inbox">
        
        <!-- Left: Conversations List -->
        <div class="conv-pane">
            <div class="conv-search">
                <div class="search-wrap">
                    <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input class="input" type="search" id="convSearchInput" placeholder="Search conversations…" aria-label="Search conversations">
                </div>
            </div>
            
            <ul class="conv-list" id="conversationsContainer">
                <?php foreach ($convsList as $c): ?>
                    <?php
                        $user = auth()->user();
                        $isEmp = $user->user_type === 'employer';
                        $cName = $isEmp ? ($c['seeker_name'] ?? 'Candidate') : ($c['company_name'] ?? 'Employer');
                        $cLogo = $isEmp ? ($c['profile_photo'] ?? '') : ($c['employer_logo'] ?? '');
                        
                        $cUrl = $isEmp 
                            ? base_url('employer/messages/conversation/' . $c['id'])
                            : base_url('candidate/messages/conversation/' . $c['id']);
                        
                        $isCurrent = (int)$c['id'] === (int)$conversation['id'];
                        
                        $cInitials = '';
                        $cWords = explode(' ', preg_replace('/\s+/', ' ', trim($cName)));
                        if (count($cWords) >= 2) {
                            $cInitials = strtoupper(substr($cWords[0], 0, 1) . substr($cWords[1], 0, 1));
                        } else {
                            $cInitials = strtoupper(substr($cName, 0, 2));
                        }
                    ?>
                    <li>
                        <a href="<?= $cUrl ?>" class="conv <?= $isCurrent ? 'on' : '' ?>">
                            <?php if (!empty($cLogo) && (filter_var($cLogo, FILTER_VALIDATE_URL) || file_exists(FCPATH . $cLogo))): ?>
                                <img src="<?= filter_var($cLogo, FILTER_VALIDATE_URL) ? $cLogo : base_url($cLogo) ?>" class="ava--round" style="object-fit: cover;" alt="">
                            <?php else: ?>
                                <span class="ava--round"><?= esc($cInitials) ?></span>
                            <?php endif; ?>
                            <span class="conv-body">
                                <span class="conv-name"><?= esc($cName) ?></span>
                                <span class="conv-prev"><?= esc($c['last_message'] ?? '') ?></span>
                            </span>
                            <span class="conv-side">
                                <span class="conv-time"><?= isset($c['last_message_at']) ? date('M j', strtotime($c['last_message_at'])) : '' ?></span>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Right: Active Thread Workspace -->
        <?php
            $user = auth()->user();
            if ($user->user_type === 'employer') {
                $name = $otherParty->full_name ?? 'Candidate';
                $avatar = $otherParty->profile_photo ?? '';
                $subtext = 'Candidate Profile';
            } else {
                $name = $otherParty->company_name ?? 'Employer';
                $avatar = $otherParty->logo ?? '';
                $subtext = 'Verified Employer';
            }

            $hasAvatar = false;
            $avatarUrl = '';
            if (!empty($avatar)) {
                if (filter_var($avatar, FILTER_VALIDATE_URL) || str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
                    $avatarUrl = $avatar;
                    $hasAvatar = true;
                } elseif (file_exists(FCPATH . $avatar)) {
                    $avatarUrl = base_url($avatar);
                    $hasAvatar = true;
                }
            }

            $initials = '';
            $words = explode(' ', preg_replace('/\s+/', ' ', trim($name)));
            if (count($words) >= 2) {
                $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
            } else {
                $initials = strtoupper(substr($name, 0, 2));
            }
        ?>
        <div class="thread" aria-label="Conversation with <?= esc($name) ?>">
            <div class="thread-head">
                <a href="<?= base_url($user->user_type === 'employer' ? 'employer/messages' : 'candidate/messages') ?>" class="thread-back" id="thread-back" aria-label="Back to conversations">
                    <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                </a>
                <?php if ($hasAvatar): ?>
                    <img src="<?= $avatarUrl ?>" class="ava--round" style="width:40px; height:40px; object-fit: cover;" alt="">
                <?php else: ?>
                    <span class="ava--round" style="width:40px; height:40px; font-size:0.75rem;">
                        <?= esc($initials) ?>
                    </span>
                <?php endif; ?>
                <div>
                    <div class="thread-name"><?= esc($name) ?></div>
                    <div class="thread-sub">
                        <?php if (!empty($conversation['job_id'])): ?>
                            Regarding: <?= esc($conversation['job_title'] ?? 'Job Application') ?>
                        <?php else: ?>
                            <?= $subtext ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="thread-scroll" id="messageContainer">
                <span class="day-divider">Message History</span>
                
                <?php if (empty($messages)): ?>
                    <div style="text-align: center; color: var(--muted); font-size: .84rem; padding: 20px;">
                        No messages yet. Start the conversation!
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <?php $isOwn = $msg['sender_id'] == $user->id; ?>
                        <div class="bubble <?= $isOwn ? 'bubble--out' : 'bubble--in' ?>">
                            <?= nl2br(esc($msg['message'])) ?>
                            <time><?= date('g:i A', strtotime($msg['created_at'])) ?></time>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="composer">
                <form id="messageForm" style="display: flex; gap: 10px; width: 100%; align-items: center; margin: 0;">
                    <input type="hidden" name="recipient_id" value="<?= $user->user_type === 'employer' ? $conversation['job_seeker_id'] : $conversation['employer_id'] ?>">
                    <input type="hidden" name="job_id" value="<?= $conversation['job_id'] ?? 0 ?>">
                    <input type="text" id="messageInput" class="input" placeholder="Type a message…" required autocomplete="off" aria-label="Type a message">
                    <button type="submit" class="btn btn-primary" id="sendBtn" aria-label="Send message">
                        <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </button>
                </form>
            </div>
        </div>

    </section>

    <div class="notice notice--info" style="margin-top: 20px;">
        <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg>
        <?php if (auth()->user()->user_type === 'employer'): ?>
          <span>Only candidates who applied to your jobs or whose profile you unlocked can be messaged. Never request payment details in chat.</span>
        <?php else: ?>
          <span>Only verified employers you've applied to or who found your profile can message you. Never share payment details in chat — legitimate employers will not ask you to pay for a job.</span>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var container = document.getElementById('messageContainer');
    if (container) container.scrollTop = container.scrollHeight;

    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    var csrfHeader = 'X-CSRF-TOKEN';

    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var input = document.getElementById('messageInput');
        var sendBtn = document.getElementById('sendBtn');
        var msg = input.value.trim();
        if (!msg) return;

        sendBtn.disabled = true;

        var formData = new FormData();
        formData.append('message', msg);
        formData.append('recipient_id', this.querySelector('[name="recipient_id"]').value);
        formData.append('job_id', this.querySelector('[name="job_id"]').value);
        formData.append('<?= csrf_token() ?>', csrfToken);

        var sendUrl = '<?= base_url(auth()->user()->user_type === "employer" ? "employer/messages/send" : "candidate/messages/send") ?>';

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
                var div = document.createElement('div');
                div.className = 'bubble bubble--out';
                div.innerHTML = `${msg.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>')}
                    <time>Just now</time>`;
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

    // ── Local search filtering of conversation items ──
    var searchInput = document.getElementById('convSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var query = this.value.toLowerCase().trim();
            document.querySelectorAll('#conversationsContainer li').forEach(function(li) {
                var name = li.querySelector('.conv-name').textContent.toLowerCase();
                var prev = li.querySelector('.conv-prev').textContent.toLowerCase();
                if (name.includes(query) || prev.includes(query)) {
                    li.style.display = '';
                } else {
                    li.style.display = 'none';
                }
            });
        });
    }
});
</script>
<?= $this->endSection() ?>
