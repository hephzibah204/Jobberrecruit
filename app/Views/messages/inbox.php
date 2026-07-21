<?= $this->extend(auth()->user()->user_type === 'employer' ? 'layouts/employer' : 'layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* ── Inbox Split Layout styling ── */
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
}
.thread-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    flex: 1;
    padding: 32px;
    color: var(--muted);
    background: #fafbfd;
}
.thread-empty svg {
    width: 42px;
    height: 42px;
    color: var(--border);
    margin-bottom: 12px;
}
@media(max-width: 768px) {
    .inbox { grid-template-columns: 1fr; }
    .thread { display: none; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-chat"/></svg> Messages</h1>
            <p>Chat with employers directly — replying quickly improves your chances.</p>
        </div>
        <?php if ($unreadCount > 0): ?>
            <span class="pill pill--rejected"><?= $unreadCount ?> unread</span>
        <?php endif; ?>
    </div>

    <section class="inbox" id="inbox" aria-label="Inbox">
        
        <!-- Left: Conversations List -->
        <div class="conv-pane">
            <div class="conv-search">
                <div class="search-wrap">
                    <svg aria-hidden="true" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input class="input" type="search" id="convSearchInput" placeholder="Search conversations…" aria-label="Search conversations">
                </div>
            </div>
            
            <ul class="conv-list" id="conversationsContainer">
                <?php if (empty($conversations)): ?>
                    <div style="padding: 36px 18px; text-align: center; color: var(--muted); font-size: .84rem;">
                        <svg aria-hidden="true" style="width:28px;height:28px;margin-bottom:8px;color:var(--border);"><use href="#i-chat"/></svg>
                        <p style="margin:0;">No messages yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($conversations as $conv): ?>
                        <?php
                            $user = auth()->user();
                            if ($user->user_type === 'employer') {
                                $name = $conv['seeker_name'] ?? 'Candidate';
                                $rawAvatar = $conv['profile_photo'] ?? '';
                            } else {
                                $name = $conv['company_name'] ?? 'Employer';
                                $rawAvatar = $conv['employer_logo'] ?? '';
                            }

                            $hasAvatar = false;
                            $avatarUrl = '';
                            if (!empty($rawAvatar)) {
                                if (filter_var($rawAvatar, FILTER_VALIDATE_URL) || str_starts_with($rawAvatar, 'http://') || str_starts_with($rawAvatar, 'https://')) {
                                    $avatarUrl = $rawAvatar;
                                    $hasAvatar = true;
                                } elseif (file_exists(FCPATH . $rawAvatar)) {
                                    $avatarUrl = base_url($rawAvatar);
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

                            $lastMsg = $conv['last_message'] ?? '';
                            $lastAt = isset($conv['last_message_at']) ? date('M j', strtotime($conv['last_message_at'])) : '';
                            $convUrl = $user->user_type === 'employer'
                                ? base_url('employer/messages/conversation/' . $conv['id'])
                                : base_url('candidate/messages/conversation/' . $conv['id']);
                        ?>
                        <li>
                            <a href="<?= $convUrl ?>" class="conv">
                                <?php if ($hasAvatar): ?>
                                    <img src="<?= $avatarUrl ?>" class="ava--round" style="object-fit: cover;" alt="">
                                <?php else: ?>
                                    <span class="ava--round">
                                        <?= esc($initials) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="conv-body">
                                    <span class="conv-name"><?= esc($name) ?></span>
                                    <span class="conv-prev"><?= esc($lastMsg) ?></span>
                                </span>
                                <span class="conv-side">
                                    <span class="conv-time"><?= $lastAt ?></span>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Right: Thread Workspace Empty view -->
        <div class="thread" aria-label="Conversation detail">
            <div class="thread-empty">
                <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <h3>Select a conversation</h3>
                <p>Choose an employer from the list on the left to read and send messages.</p>
            </div>
        </div>

    </section>

    <div class="notice notice--info" style="margin-top: 20px;">
        <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg>
        <span>Only verified employers you've applied to or who found your profile can message you. Never share payment details in chat — legitimate employers will not ask you to pay for a job.</span>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // ── Local search filtering of conversation items ──
    $('#convSearchInput').on('input', function() {
        var query = $(this).val().toLowerCase().trim();
        $('#conversationsContainer li').each(function() {
            var name = $(this).find('.conv-name').text().toLowerCase();
            var prev = $(this).find('.conv-prev').text().toLowerCase();
            if (name.includes(query) || prev.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
