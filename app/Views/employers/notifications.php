<?php $page_title = 'Notifications & Alerts'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.notif-tabs {
  display: flex;
  gap: 8px;
  border-bottom: 1px solid var(--border, #e2e8f0);
  margin-bottom: 20px;
}
.notif-tab {
  padding: 10px 18px;
  font-size: 0.88rem;
  font-weight: 700;
  color: var(--muted, #64748b);
  border: none;
  background: none;
  border-bottom: 2px solid transparent;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  transition: all .15s ease;
}
.notif-tab:hover {
  color: var(--brand-deep, #0A2F57);
}
.notif-tab.active {
  color: var(--brand, #0861A9);
  border-bottom-color: var(--brand, #0861A9);
}
.notif-row {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 16px;
  border-bottom: 1px solid var(--border, #edf2f7);
  transition: background .15s ease;
}
.notif-row:hover { background: #f8fafc; }
.notif-row.unread { background: #f0f7fc; border-left: 3px solid var(--brand, #0861A9); }
.notif-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--brand-light, #E6F0F8);
  color: var(--brand, #0861A9);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.notif-icon svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.notif-content { flex: 1; min-width: 0; }
.notif-title { font-size: 0.88rem; font-weight: 700; color: var(--brand-deep, #0A2F57); margin-bottom: 2px; display: flex; align-items: center; gap: 8px; }
.notif-msg { font-size: 0.82rem; color: #334155; line-height: 1.45; margin-bottom: 4px; }
.notif-time { font-size: 0.74rem; color: var(--muted, #64748b); display: flex; align-items: center; gap: 4px; }
.notif-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-head">
    <div>
        <h1>
            <svg aria-hidden="true"><use href="#i-bell"/></svg> 
            Notifications &amp; Alerts
        </h1>
        <p>Stay informed about your candidate applications, test screening results, and search alerts.</p>
    </div>
    <div class="page-actions">
        <?php if (!empty($unreadCount) && $unreadCount > 0): ?>
            <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" id="btn-mark-all-read" onclick="markAllNotificationsRead()">
                <svg aria-hidden="true" width="14" height="14"><use href="#i-check-c"/></svg> Mark all as read
            </button>
        <?php endif; ?>
        <a href="#new-alert" class="emp-btn emp-btn-primary emp-btn-sm" onclick="switchTab('alerts');">
            <svg aria-hidden="true"><use href="#i-plus"/></svg> New Candidate Alert
        </a>
    </div>
</div>

<nav class="notif-tabs" aria-label="Notification sections">
    <button type="button" class="notif-tab active" id="tab-btn-feed" onclick="switchTab('feed')">
        <svg aria-hidden="true" width="16" height="16"><use href="#i-bell"/></svg> 
        Activity Notifications
        <?php if (!empty($unreadCount) && $unreadCount > 0): ?>
            <span class="pill pill--reviewed" id="unread-badge"><?= $unreadCount ?> new</span>
        <?php endif; ?>
    </button>
    <button type="button" class="notif-tab" id="tab-btn-alerts" onclick="switchTab('alerts')">
        <svg aria-hidden="true" width="16" height="16"><use href="#i-users"/></svg> 
        Candidate Alerts
        <?php $activeAlertCount = count(array_filter($alerts ?? [], fn($a) => !empty($a->active ?? $a['active'] ?? true))); ?>
        <span class="pill pill--closed"><?= $activeAlertCount ?> active</span>
    </button>
</nav>

<!-- ═══ SECTION 1: ACTIVITY NOTIFICATIONS FEED ═══ -->
<div id="section-feed">
    <section class="card" aria-label="Notifications Feed">
        <div class="card-head" style="display:flex; justify-content:space-between; align-items:center;">
            <span class="card-title">
                <svg aria-hidden="true"><use href="#i-bell"/></svg> 
                Notifications Feed
            </span>
            <div style="font-size:0.78rem; color:var(--muted);">
                Showing <?= count($notifications ?? []) ?> notification<?= count($notifications ?? []) === 1 ? '' : 's' ?>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (empty($notifications)): ?>
                <div class="empty-state" style="padding:48px 20px;">
                    <div class="empty-ic">
                        <svg aria-hidden="true"><use href="#i-bell"/></svg>
                    </div>
                    <h3>No notifications yet</h3>
                    <p>When candidates apply to your jobs or screening assessments are completed, updates will appear here.</p>
                </div>
            <?php else: ?>
                <div class="notif-list">
                    <?php foreach ($notifications as $n): 
                        $isRead = !empty($n['is_read']);
                        $nId = (int)($n['id'] ?? 0);
                        $type = $n['type'] ?? 'system';
                    ?>
                        <div class="notif-row <?= !$isRead ? 'unread' : '' ?>" id="notif-row-<?= $nId ?>">
                            <div class="notif-icon">
                                <?php if (str_contains($type, 'application')): ?>
                                    <svg aria-hidden="true"><use href="#i-users"/></svg>
                                <?php elseif (str_contains($type, 'job')): ?>
                                    <svg aria-hidden="true"><use href="#i-briefcase"/></svg>
                                <?php else: ?>
                                    <svg aria-hidden="true"><use href="#i-bell"/></svg>
                                <?php endif; ?>
                            </div>
                            <div class="notif-content">
                                <div class="notif-title">
                                    <?= esc($n['title'] ?? 'Notification') ?>
                                    <?php if (!$isRead): ?>
                                        <span class="badge" style="background:var(--brand);color:#fff;font-size:0.64rem;padding:2px 6px;border-radius:4px;">NEW</span>
                                    <?php endif; ?>
                                </div>
                                <div class="notif-msg"><?= esc($n['message'] ?? '') ?></div>
                                <div class="notif-time">
                                    <svg aria-hidden="true" width="12" height="12"><use href="#i-clock"/></svg>
                                    <?= !empty($n['created_at']) ? date('M d, Y · h:i A', strtotime($n['created_at'])) : 'Recently' ?>
                                    <?php if (!empty($n['job_title'])): ?>
                                        &middot; <span style="font-weight:600;"><?= esc($n['job_title']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="notif-actions">
                                <?php if (!empty($n['application_id'])): ?>
                                    <a href="<?= base_url('employer/applications/view/' . $n['application_id']) ?>" class="emp-btn emp-btn-outline emp-btn-sm" style="padding:4px 10px; font-size:0.75rem;">
                                        View Application
                                    </a>
                                <?php elseif (!empty($n['job_id'])): ?>
                                    <a href="<?= base_url('employer/jobs/view/' . $n['job_id']) ?>" class="emp-btn emp-btn-outline emp-btn-sm" style="padding:4px 10px; font-size:0.75rem;">
                                        View Job
                                    </a>
                                <?php endif; ?>
                                <?php if (!$isRead): ?>
                                    <button type="button" class="emp-btn emp-btn-ghost emp-btn-sm" style="padding:4px 8px; font-size:0.75rem;" onclick="markNotificationRead(<?= $nId ?>, this)" title="Mark as read">
                                        <svg aria-hidden="true" width="13" height="13"><use href="#i-check"/></svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- ═══ SECTION 2: CANDIDATE SEARCH ALERTS ═══ -->
<div id="section-alerts" style="display:none;">
<section class="card" aria-label="Your alerts">
    <div class="card-head">
        <span class="card-title">
            <svg aria-hidden="true"><use href="#i-bell"/></svg> 
            Your Alerts
            <?php $activeAlertCount = count(array_filter($alerts, fn($a) => !empty($a->active ?? $a['active'] ?? true))); ?>
            <span class="pill pill--reviewed">
                <?= $activeAlertCount ?> active
            </span>
        </span>
    </div>
    <div class="card-body">
        <?php if (empty($alerts)): ?>
            <div class="empty-state">
                <div class="empty-ic">
                    <svg aria-hidden="true"><use href="#i-bell"/></svg>
                </div>
                <h3>Create your first alert</h3>
                <p>Set criteria below to stay updated with candidate matches tailored to your job descriptions.</p>
            </div>
        <?php else: ?>
            <?php foreach ($alerts as $alert): 
                $criteria = is_string($alert['criteria'] ?? '') ? json_decode($alert['criteria'], true) : ($alert['criteria'] ?? []);
                $matches = $alert['matches'] ?? [];
                $matchesCount = count($matches);
            ?>
                <div class="alert-card" data-id="<?= esc($alert['id'] ?? '') ?>">
                    <div class="alert-top">
                        <span class="alert-ic" aria-hidden="true">
                            <svg><use href="#i-bell"/></svg>
                        </span>
                        <div>
                            <div class="alert-name"><?= esc($alert['name'] ?? 'Candidate Alert') ?></div>
                            <div class="alert-meta">Created <?= date('d M Y', strtotime($alert['created_at'] ?? 'now')) ?></div>
                        </div>
                        <?php if ($matchesCount > 0): ?>
                            <a href="<?= site_url('employer/candidates?' . http_build_query($criteria)) ?>" class="alert-new">
                                <svg aria-hidden="true"><use href="#i-users"/></svg> 
                                <?= $matchesCount ?> new <?= $matchesCount === 1 ? 'match' : 'matches' ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="chips alert-chips">
                        <?php if (!empty($criteria['keyword'] ?? $criteria['role'] ?? '')): ?>
                            <span class="chip"><?= esc($criteria['keyword'] ?? $criteria['role']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($criteria['category'] ?? '')): ?>
                            <span class="chip"><?= esc($criteria['category']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($criteria['location'] ?? '')): ?>
                            <span class="chip"><?= esc($criteria['location']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($criteria['experience'] ?? '')): ?>
                            <span class="chip"><?= esc($criteria['experience']) ?>+ yrs exp</span>
                        <?php endif; ?>
                        <?php if (!empty($criteria['education'] ?? '')): ?>
                            <span class="chip"><?= esc($criteria['education']) ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($matches)): ?>
                        <div class="match-strip" aria-label="New matching candidates">
                            <?php foreach ($matches as $match): 
                                $initials = strtoupper(substr($match['first_name'] ?? 'C', 0, 1) . substr($match['last_name'] ?? 'A', 0, 1));
                            ?>
                                <div class="match">
                                    <span class="ava ava--round" aria-hidden="true"><?= esc($initials) ?></span>
                                    <span>
                                        <b><?= esc(($match['first_name'] ?? '') . ' ' . ($match['last_name'] ?? '')) ?></b>
                                        <i><?= esc($match['title'] ?? 'Candidate') ?> &middot; <?= esc($match['experience'] ?? '0') ?> yrs</i>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="alert-controls">
                        <label class="ctl">Frequency
                            <select class="select frequency-select" aria-label="Alert frequency for <?= esc($alert['name'] ?? '') ?>">
                                <option value="instant" <?= ($alert['frequency'] ?? '') === 'instant' ? 'selected' : '' ?>>Instant</option>
                                <option value="daily" <?= ($alert['frequency'] ?? '') === 'daily' ? 'selected' : '' ?>>Daily digest</option>
                                <option value="weekly" <?= ($alert['frequency'] ?? '') === 'weekly' ? 'selected' : '' ?>>Weekly digest</option>
                            </select>
                        </label>
                        <label class="ctl">
                            <span class="switch">
                                <input type="checkbox" class="email-toggle" <?= ($alert['email_active'] ?? $alert['email'] ?? true) ? 'checked' : '' ?> aria-label="Email notifications for <?= esc($alert['name'] ?? '') ?>">
                                <span class="sl"></span>
                            </span> 
                            Email
                        </label>
                        <label class="ctl">
                            <span class="switch">
                                <input type="checkbox" class="active-toggle" <?= ($alert['active'] ?? true) ? 'checked' : '' ?> aria-label="Alert active: <?= esc($alert['name'] ?? '') ?>">
                                <span class="sl"></span>
                            </span> 
                            Active
                        </label>
                        <button class="ic-btn ic-btn--danger alert-del btn-delete-alert" aria-label="Delete alert: <?= esc($alert['name'] ?? '') ?>" title="Delete alert">
                            <svg aria-hidden="true"><use href="#i-trash"/></svg>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- create alert · POST /employer/candidate-alerts -->
<section class="card" id="new-alert" aria-label="Create a new alert">
    <div class="card-head">
        <span class="card-title">
            <svg aria-hidden="true"><use href="#i-plus"/></svg> Create a New Alert
        </span>
    </div>
    <div class="card-body">
        <form action="<?= site_url('employer/candidate-alerts') ?>" method="POST" id="create-alert-form">
            <?= csrf_field() ?>
            <div class="new-alert-grid">
                <div>
                    <label class="lbl" for="na-name">Alert Name</label>
                    <input class="input" id="na-name" name="name" type="text" placeholder="e.g. Accountants in Lagos" required>
                </div>
                <div>
                    <label class="lbl" for="na-role">Role or keywords</label>
                    <input class="input" id="na-role" name="keyword" type="text" placeholder="e.g. Accountant, bookkeeping">
                </div>
                <div>
                    <label class="lbl" for="na-category">Category</label>
                    <select class="select" id="na-category" name="category">
                        <option value="">Any Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php $catName = is_object($cat) ? ($cat->name ?? '') : (is_array($cat) ? ($cat['name'] ?? '') : $cat); ?>
                            <option value="<?= esc($catName) ?>"><?= esc($catName) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="lbl" for="na-loc">Location</label>
                    <select class="select" id="na-loc" name="location">
                        <option value="">Any location</option>
                        <option value="Lagos">Lagos State</option>
                        <option value="Abuja">Abuja (FCT)</option>
                        <option value="Rivers">Rivers State</option>
                        <option value="Remote">Remote</option>
                    </select>
                </div>
                <div>
                    <label class="lbl" for="na-exp">Minimum experience (years)</label>
                    <select class="select" id="na-exp" name="experience">
                        <option value="">Any</option>
                        <option value="1">1+ years</option>
                        <option value="3">3+ years</option>
                        <option value="5">5+ years</option>
                    </select>
                </div>
                <div>
                    <label class="lbl" for="na-edu">Education level</label>
                    <select class="select" id="na-edu" name="education">
                        <option value="">Any</option>
                        <option value="OND">OND</option>
                        <option value="HND">HND</option>
                        <option value="Bachelor's Degree">Bachelor's Degree</option>
                        <option value="Master's Degree">Master's Degree</option>
                        <option value="PhD">PhD</option>
                    </select>
                </div>
            </div>
            <div class="na-foot">
                <p>You can fine-tune frequency and channels after creating the alert.</p>
                <button type="submit" class="emp-btn emp-btn-primary">
                    <svg aria-hidden="true"><use href="#i-bell"/></svg> Create Alert
                </button>
            </div>
        </form>
    </div>
</section>

<div class="notice notice--info">
    <svg aria-hidden="true"><use href="#i-bulb"/></svg>
    <span>Alerts power your AI Recruiter matches on the dashboard. The more specific your criteria, the better the matches — you can create as many alerts as you need.</span>
</div>
</div> <!-- /section-alerts -->

<!-- Delete Alert Confirmation Modal -->
<div class="modal-scrim" id="delete-alert-scrim" style="display:none; position:fixed; inset:0; background:rgba(10,25,45,.55); backdrop-filter:blur(2px); z-index:1400; align-items:center; justify-content:center; padding:24px;">
  <div style="background:#fff; border-radius:16px; width:100%; max-width:420px; overflow:hidden; box-shadow:var(--shadow-lg); animation:modal-in .22s ease;" role="dialog" aria-modal="true" aria-labelledby="del-alert-title">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:18px 22px; border-bottom:1px solid var(--border);">
      <span style="font-family:'Sora',sans-serif; font-weight:800; font-size:1.05rem; color:var(--brand-deep); display:inline-flex; align-items:center; gap:9px;">
        <svg aria-hidden="true" style="width:16px;height:16px;color:var(--danger);"><use href="#i-trash"/></svg> Delete Alert
      </span>
      <button id="del-alert-close" style="display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:8px; border:1.5px solid var(--border); background:#fff; color:var(--muted); cursor:pointer; flex-shrink:0;" aria-label="Close dialog">
        <svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg>
      </button>
    </div>
    <div style="padding:18px 22px;">
      <p style="font-size:0.9rem; color:var(--text); line-height:1.6; margin:0;">Are you sure you want to delete this candidate alert? You will no longer receive matching candidate notifications for this search criteria.</p>
    </div>
    <div style="display:flex; justify-content:flex-end; gap:10px; padding:14px 22px; border-top:1px solid var(--border);">
      <button class="emp-btn emp-btn-outline" id="del-alert-cancel">Cancel</button>
      <button class="emp-btn emp-btn-danger" id="del-alert-confirm"><svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Alert</button>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function switchTab(tab) {
    if (tab === 'feed') {
        $('#section-feed').show();
        $('#section-alerts').hide();
        $('#tab-btn-feed').addClass('active');
        $('#tab-btn-alerts').removeClass('active');
    } else {
        $('#section-feed').hide();
        $('#section-alerts').show();
        $('#tab-btn-feed').removeClass('active');
        $('#tab-btn-alerts').addClass('active');
    }
}

function markNotificationRead(id, btn) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
    var csrfName = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || '<?= csrf_token() ?>';
    
    var data = {
        notification_id: id
    };
    data[csrfName] = csrfToken;

    $.ajax({
        url: '<?= base_url("employer/notifications/mark-read") ?>',
        type: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        data: data,
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                var row = $('#notif-row-' + id);
                row.removeClass('unread');
                row.find('.badge').remove();
                if (btn) $(btn).remove();
                if (res.unreadCount !== undefined) {
                    if (res.unreadCount <= 0) {
                        $('#unread-badge').remove();
                        $('#btn-mark-all-read').remove();
                    } else {
                        $('#unread-badge').text(res.unreadCount + ' new');
                    }
                }
            }
        }
    });
}

function markAllNotificationsRead() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= csrf_hash() ?>';
    var csrfName = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || '<?= csrf_token() ?>';
    
    var data = {};
    data[csrfName] = csrfToken;

    $.ajax({
        url: '<?= base_url("employer/notifications/mark-all-read") ?>',
        type: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        data: data,
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                $('.notif-row').removeClass('unread');
                $('.notif-row .badge').remove();
                $('.notif-actions button').remove();
                $('#unread-badge').remove();
                $('#btn-mark-all-read').remove();
                if (typeof toastr !== 'undefined') {
                    toastr.success('All notifications marked as read.');
                }
            }
        }
    });
}

$(document).ready(function() {
    // CSRF Token and Hash
    const csrfTokenName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    function getAjaxData(extraData = {}) {
        var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || csrfHash;
        return {
            [csrfTokenName]: token,
            ...extraData
        };
    }

    function updateCsrf(newHash) {
        if (newHash) {
            csrfHash = newHash;
            $('input[name="' + csrfTokenName + '"]').val(newHash);
            var meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) meta.setAttribute('content', newHash);
        }
    }

    // Update settings (active, email, frequency)
    $('.frequency-select, .email-toggle, .active-toggle').on('change', function() {
        const card = $(this).closest('.alert-card');
        const alertId = card.data('id');
        const frequency = card.find('.frequency-select').val();
        const email = card.find('.email-toggle').is(':checked') ? 1 : 0;
        const active = card.find('.active-toggle').is(':checked') ? 1 : 0;

        $.ajax({
            url: '<?= site_url("employer/candidate-alerts/update") ?>/' + alertId,
            type: 'POST',
            data: getAjaxData({
                frequency: frequency,
                email_active: email,
                active: active
            }),
            dataType: 'json',
            success: function(response) {
                if (response.csrf) updateCsrf(response.csrf);
                if (response.success) {
                    // Option to show a toast or highlight success
                } else {
                    toastr.error(response.message || 'Failed to update alert settings.');
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.');
            }
        });
    });

    // Delete alert
    let deleteAlertScrim, deleteAlertConfirm, deleteAlertCancel, deleteAlertClose, pendingAlertId;

    function initDeleteAlertModal() {
      deleteAlertScrim = document.getElementById('delete-alert-scrim');
      deleteAlertConfirm = document.getElementById('del-alert-confirm');
      deleteAlertCancel = document.getElementById('del-alert-cancel');
      deleteAlertClose = document.getElementById('del-alert-close');

      function closeDelAlert() {
        deleteAlertScrim.style.display = 'none';
        document.body.style.overflow = '';
        pendingAlertId = null;
      }

      function openDelAlert(alertId) {
        pendingAlertId = alertId;
        deleteAlertScrim.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }

      deleteAlertCancel.addEventListener('click', closeDelAlert);
      deleteAlertClose.addEventListener('click', closeDelAlert);
      deleteAlertScrim.addEventListener('click', function(e) { if (e.target === deleteAlertScrim) closeDelAlert(); });
      document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && deleteAlertScrim.style.display === 'flex') closeDelAlert(); });

      deleteAlertConfirm.addEventListener('click', function() {
        if (!pendingAlertId) return;
        const id = pendingAlertId;
        deleteAlertConfirm.disabled = true;
        deleteAlertConfirm.innerHTML = 'Deleting...';

        $.ajax({
            url: '<?= site_url("employer/candidate-alerts/delete") ?>/' + id,
            type: 'POST',
            data: getAjaxData(),
            dataType: 'json',
            success: function(response) {
                deleteAlertConfirm.disabled = false;
                deleteAlertConfirm.innerHTML = '<svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Alert';
                if (response.csrf) updateCsrf(response.csrf);
                if (response.success) {
                    closeDelAlert();
                    const card = document.querySelector('.alert-card[data-id="' + id + '"]');
                    if (card) {
                      card.style.transition = 'opacity .3s, transform .3s';
                      card.style.opacity = '0';
                      card.style.transform = 'scale(.96)';
                      setTimeout(function() {
                        card.remove();
                        if (document.querySelectorAll('.alert-card').length === 0) {
                          location.reload();
                        }
                      }, 300);
                    }
                } else {
                    toastr.error(response.message || 'Failed to delete alert.');
                }
            },
            error: function() {
                deleteAlertConfirm.disabled = false;
                deleteAlertConfirm.innerHTML = '<svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Alert';
                toastr.error('An error occurred. Please try again.');
            }
        });
      });

      // Hook into existing delete buttons
      document.querySelectorAll('.btn-delete-alert').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const card = btn.closest('.alert-card');
          if (!card) return;
          const alertId = card.dataset.id;
          if (!alertId) return;
          openDelAlert(alertId);
        });
      });
    }

    // Re-init on dynamic content changes
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initDeleteAlertModal);
    } else {
      initDeleteAlertModal();
    }

});
</script>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<a href="#new-alert" class="emp-btn emp-btn-outline"><svg aria-hidden="true"><use href="#i-bell"/></svg> View Alerts</a>
<a href="#new-alert" class="emp-btn emp-btn-accent"><svg aria-hidden="true"><use href="#i-plus"/></svg> New Alert</a>
<?= $this->endSection() ?>