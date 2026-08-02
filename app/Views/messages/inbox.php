<?php $page_title = 'Messages'; ?>
<?= $this->extend(auth()->user()->user_type === 'employer' ? 'layouts/employer' : 'layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* Premium Polish Layer */
:root{
  --shadow-xs:0 1px 3px rgba(10,47,87,.06);
  --shadow-sm:0 2px 10px rgba(10,47,87,.07);
  --shadow-md:0 6px 24px rgba(10,47,87,.10);
  --shadow-lg-p:0 16px 44px rgba(10,47,87,.16);
  --border-c:#e2e8f2;
}
.card,.dash-card,.set-card,.plan,.info-card,.job-card,.les-detail,.cur-card,
.at-card,.faq-item,.modal,.q-block{
  box-shadow:var(--shadow-xs);
  border-color:var(--border-c);
}
.card:hover,.dash-card:hover,.job-card:hover,.cs-tool:hover,.res-item:hover{
  box-shadow:var(--shadow-sm);
}
.modal{box-shadow:var(--shadow-lg-p)}
.btn,.sb-link,.at-pal,.at-opt,.q-opt,.cs-tool,.job-card,.ach,.tpl-swatch,
.les,.res-item,.faq-item,.plan .btn,.icon-btn{
  transition:transform .12s cubic-bezier(.2,.8,.2,1),
             box-shadow .18s ease,
             background-color .18s ease,
             border-color .18s ease,
             opacity .18s ease;
}
.btn:active,.at-pal:active,.at-opt:active,.q-opt:active,.cs-tool:active,
.les:active,.res-item:active{
  transform:scale(.97);
}
.btn:not(:disabled):hover{transform:translateY(-1px)}
.btn:not(:disabled):active{transform:translateY(0) scale(.97)}
@media(prefers-reduced-motion:reduce){
  .btn,.sb-link,.at-pal,.at-opt,.q-opt,.cs-tool,.job-card,.ach,.les,.res-item{
    transition:background-color .12s ease,border-color .12s ease!important;
  }
  .btn:active,.btn:hover{transform:none!important}
}
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
.conv-unread {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 18px;
    height: 18px;
    padding: 0 5px;
    border-radius: 10px;
    background: var(--accent);
    color: var(--brand-deep);
    font-size: .6rem;
    font-weight: 700;
    line-height: 1;
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
            <?php if (auth()->user()->user_type === 'employer'): ?>
              <p>Communicate with candidates directly on the platform.</p>
            <?php else: ?>
              <p>Chat with employers directly — replying quickly improves your chances.</p>
            <?php endif; ?>
        </div>
        <?php if ($unreadCount > 0): ?>
            <span class="pill pill--rejected"><?= $unreadCount ?> unread</span>
        <?php endif; ?>
    </div>

    <section class="card inbox" id="inbox" aria-label="Inbox">
        
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
                                    <?php if (!empty($conv['unread_count'])): ?>
                                        <span class="conv-unread"><?= (int)$conv['unread_count'] ?></span>
                                    <?php endif; ?>
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
                <?php if (auth()->user()->user_type === 'employer'): ?>
                  <p>Choose a candidate from the list on the left to read and send messages.</p>
                <?php else: ?>
                  <p>Choose an employer from the list on the left to read and send messages.</p>
                <?php endif; ?>
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
