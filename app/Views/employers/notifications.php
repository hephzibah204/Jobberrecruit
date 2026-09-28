<?php $page_title = 'Notification Centre'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.nc-wrap { display:grid; grid-template-columns:220px 1fr; gap:20px; align-items:start; }
@media(max-width:700px){.nc-wrap{grid-template-columns:1fr;}.nc-sidebar{display:flex;gap:6px;overflow-x:auto;padding-bottom:4px;}.nc-sidebar .nc-filter-btn{white-space:nowrap;flex-shrink:0;}}
.nc-sidebar{display:flex;flex-direction:column;gap:4px;}
.nc-filter-btn{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:9px;border:none;background:transparent;cursor:pointer;width:100%;text-align:left;font-size:.84rem;font-weight:600;color:var(--muted,#64748b);transition:all .15s ease;}
.nc-filter-btn:hover{background:var(--brand-light,#e6f0f8);color:var(--brand-deep,#0A2F57);}
.nc-filter-btn.active{background:var(--brand-light,#e6f0f8);color:var(--brand,#0861A9);}
.nc-filter-btn svg{width:15px;height:15px;flex-shrink:0;}
.nc-filter-count{margin-left:auto;min-width:20px;height:20px;border-radius:10px;background:var(--brand,#0861A9);color:#fff;font-size:.68rem;font-weight:700;display:flex;align-items:center;justify-content:center;padding:0 5px;}
.nc-filter-count.zero{background:var(--border,#e2e8f0);color:var(--muted);}
.notif-row{display:flex;align-items:flex-start;gap:14px;padding:15px 18px;border-bottom:1px solid var(--border,#edf2f7);transition:background .12s ease;position:relative;}
.notif-row:last-child{border-bottom:none;}
.notif-row:hover{background:#f8fafc;}
.notif-row.unread{background:#f0f7fc;border-left:3px solid var(--brand,#0861A9);}
.notif-icon{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid transparent;}
.notif-icon svg{width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
.notif-icon.color-success{background:#e8fdf2;color:#16a34a;border-color:#bbf7d0;}
.notif-icon.color-danger{background:#fef2f2;color:#dc2626;border-color:#fecaca;}
.notif-icon.color-warning{background:#fffbeb;color:#d97706;border-color:#fde68a;}
.notif-icon.color-info{background:#eff6ff;color:#2563eb;border-color:#bfdbfe;}
.notif-icon.color-accent{background:#fdf4ff;color:#9333ea;border-color:#e9d5ff;}
.notif-icon.color-secondary,.notif-icon.color-muted{background:#f1f5f9;color:#64748b;border-color:#e2e8f0;}
.notif-content{flex:1;min-width:0;}
.notif-title{font-size:.87rem;font-weight:700;color:var(--brand-deep,#0A2F57);margin-bottom:3px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.notif-type-badge{font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:4px;background:var(--brand-light,#e6f0f8);color:var(--brand,#0861A9);}
.notif-new-badge{font-size:.64rem;font-weight:700;padding:2px 6px;border-radius:4px;background:var(--brand,#0861A9);color:#fff;}
.notif-msg{font-size:.82rem;color:#334155;line-height:1.5;margin-bottom:5px;}
.notif-meta{font-size:.73rem;color:var(--muted,#64748b);display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.notif-meta svg{width:11px;height:11px;}
.notif-actions{display:flex;align-items:center;gap:6px;flex-shrink:0;}
.sub-expiry-banner{display:flex;align-items:center;gap:12px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;margin-bottom:16px;font-size:.84rem;}
.sub-expiry-banner svg{flex-shrink:0;width:18px;height:18px;color:#d97706;}
.sub-expiry-banner a{color:var(--brand);font-weight:700;text-decoration:underline;}
.notif-tabs{display:flex;gap:8px;border-bottom:1px solid var(--border,#e2e8f0);margin-bottom:20px;}
.notif-tab{padding:10px 18px;font-size:.88rem;font-weight:700;color:var(--muted,#64748b);border:none;background:none;border-bottom:2px solid transparent;cursor:pointer;display:inline-flex;align-items:center;gap:8px;text-decoration:none;transition:all .15s ease;}
.notif-tab:hover{color:var(--brand-deep,#0A2F57);}
.notif-tab.active{color:var(--brand,#0861A9);border-bottom-color:var(--brand,#0861A9);}
.nc-empty{padding:48px 20px;text-align:center;color:var(--muted);}
.nc-empty svg{width:40px;height:40px;opacity:.3;margin-bottom:12px;}
.nc-empty p{font-size:.85rem;margin-top:6px;}
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
use App\Models\JobNotificationModel;

$subExpiringSoon = false; $subDaysLeft = null;
$subRenewUrl = base_url('employer/subscription');
try {
    $subSvc = new \App\Services\SubscriptionService();
    $subDetails = $subSvc->getActiveSubscriptionDetails($user->id);
    if (!empty($subDetails['ends_at'])) {
        $daysLeft = (int) ceil((strtotime($subDetails['ends_at']) - time()) / 86400);
        if ($daysLeft >= 0 && $daysLeft <= 7) { $subExpiringSoon = true; $subDaysLeft = $daysLeft; }
    }
} catch (\Throwable $e) {}

foreach ($notifications as &$n) {
    $info = JobNotificationModel::getTypeInfo($n['type'] ?? 'system');
    $n['typeInfo'] = $info; $n['category'] = $info['category'] ?? 'system';
}
unset($n);

$catCounts = ['all' => count($notifications)];
foreach ($notifications as $n) { $cat = $n['category']; $catCounts[$cat] = ($catCounts[$cat] ?? 0) + 1; }
$activeAlertCount = count(array_filter($alerts ?? [], fn($a) => !empty($a['active'] ?? true)));
?>

<div class="page-head">
    <div>
        <h1><svg aria-hidden="true"><use href="#i-bell"/></svg> Notification Centre</h1>
        <p>Your central hub for all employer notifications — subscriptions, jobs, applications, messages, and more.</p>
    </div>
    <div class="page-actions">
        <?php if (!empty($unreadCount) && $unreadCount > 0): ?>
            <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" id="btn-mark-all-read" onclick="markAllNotificationsRead()">
                <svg aria-hidden="true" width="14" height="14"><use href="#i-check-c"/></svg> Mark all as read
                <span id="unread-badge" class="pill pill--reviewed" style="margin-left:4px"><?= $unreadCount ?> new</span>
            </button>
        <?php endif; ?>
        <button type="button" class="emp-btn emp-btn-primary emp-btn-sm" onclick="switchMainTab('alerts')">
            <svg aria-hidden="true"><use href="#i-plus"/></svg> Candidate Alerts
        </button>
    </div>
</div>

<nav class="notif-tabs" aria-label="Notification sections">
    <button type="button" class="notif-tab active" id="main-tab-notifications" onclick="switchMainTab('notifications')">
        <svg aria-hidden="true" width="16" height="16"><use href="#i-bell"/></svg> All Notifications
        <?php if (!empty($unreadCount) && $unreadCount > 0): ?>
            <span class="pill pill--reviewed" id="unread-badge-tab"><?= $unreadCount ?> new</span>
        <?php endif; ?>
    </button>
    <button type="button" class="notif-tab" id="main-tab-alerts" onclick="switchMainTab('alerts')">
        <svg aria-hidden="true" width="16" height="16"><use href="#i-users"/></svg> Candidate Alerts
        <span class="pill pill--closed"><?= $activeAlertCount ?> active</span>
    </button>
</nav>

<!-- SECTION 1: NOTIFICATIONS FEED -->
<div id="section-notifications">

<?php if ($subExpiringSoon): ?>
<div class="sub-expiry-banner" role="alert">
    <svg aria-hidden="true"><use href="#i-alert"/></svg>
    <span>Your subscription <?= $subDaysLeft === 0 ? 'expires <strong>today</strong>' : "expires in <strong>{$subDaysLeft} day" . ($subDaysLeft !== 1 ? 's' : '') . "</strong>" ?>.
    <a href="<?= esc($subRenewUrl) ?>">Renew now</a> to avoid any interruption.</span>
</div>
<?php endif; ?>

<div class="nc-wrap">
<!-- Sidebar -->
<nav class="nc-sidebar" aria-label="Filter notifications">
<?php
$filters = [
    'all'          => ['label'=>'All Notifications',    'icon'=>'i-bell'],
    'jobs'         => ['label'=>'Jobs',                 'icon'=>'i-briefcase'],
    'applications' => ['label'=>'Applications',         'icon'=>'i-users'],
    'candidates'   => ['label'=>'Candidates & AI',      'icon'=>'i-star'],
    'payments'     => ['label'=>'Payments & Plans',     'icon'=>'i-check-c'],
    'messages'     => ['label'=>'Messages',             'icon'=>'i-message'],
    'system'       => ['label'=>'Account & System',     'icon'=>'i-settings'],
];
foreach ($filters as $key => $f):
    $cnt = $catCounts[$key] ?? 0;
?>
<button type="button" class="nc-filter-btn <?= $key==='all'?'active':'' ?>" data-filter="<?= $key ?>" onclick="filterNotifs('<?= $key ?>')">
    <svg aria-hidden="true"><use href="#<?= $f['icon'] ?>"/></svg>
    <?= $f['label'] ?>
    <span class="nc-filter-count <?= $cnt===0?'zero':'' ?>"><?= $cnt ?></span>
</button>
<?php endforeach; ?>
</nav>

<!-- Feed -->
<section class="card" aria-label="Notifications Feed">
    <div class="card-head" style="display:flex;justify-content:space-between;align-items:center;">
        <span class="card-title"><svg aria-hidden="true"><use href="#i-bell"/></svg> <span id="feed-title">All Notifications</span></span>
        <div style="font-size:.78rem;color:var(--muted);" id="feed-count-label"><?= count($notifications) ?> notification<?= count($notifications)!==1?'s':'' ?></div>
    </div>
    <div class="card-body" style="padding:0;">
    <?php if (empty($notifications)): ?>
        <div class="nc-empty">
            <svg aria-hidden="true"><use href="#i-bell"/></svg>
            <h3>No notifications yet</h3>
            <p>When you post jobs, receive applications, or have account events, they will appear here.</p>
        </div>
    <?php else: ?>
        <div class="notif-list" id="notif-list">
        <?php foreach ($notifications as $n):
            $isRead  = !empty($n['is_read']);
            $nId     = (int)($n['id'] ?? 0);
            $type    = $n['type'] ?? 'system';
            $info    = $n['typeInfo'];
            $cat     = $n['category'];
            $color   = $info['color'] ?? 'secondary';
            $icon    = $info['icon'] ?? 'i-bell';
            $label   = $info['label'] ?? 'Notification';
            $actionUrl = $n['action_url'] ?? null;
            if (!$actionUrl) {
                if (!empty($n['application_id'])) $actionUrl = base_url('employer/applications/view/'.$n['application_id']);
                elseif (!empty($n['job_id']))      $actionUrl = base_url('employer/jobs/view/'.$n['job_id']);
                elseif (in_array($type,['subscription_expiring','subscription_expired','subscription_renewed','payment_confirmed','payment_failed'])) $actionUrl = base_url('employer/subscription');
                elseif ($type==='new_message')     $actionUrl = base_url('employer/messages');
            }
        ?>
        <div class="notif-row <?= !$isRead?'unread':'' ?>" id="notif-row-<?= $nId ?>" data-category="<?= esc($cat) ?>">
            <div class="notif-icon color-<?= esc($color) ?>" aria-hidden="true"><svg><use href="#<?= esc($icon) ?>"/></svg></div>
            <div class="notif-content">
                <div class="notif-title">
                    <?= esc($n['title'] ?? 'Notification') ?>
                    <?php if (!$isRead): ?><span class="notif-new-badge">NEW</span><?php endif; ?>
                    <span class="notif-type-badge"><?= esc($label) ?></span>
                </div>
                <div class="notif-msg"><?= esc($n['message'] ?? '') ?></div>
                <div class="notif-meta">
                    <svg aria-hidden="true"><use href="#i-clock"/></svg>
                    <?= !empty($n['created_at']) ? date('d M Y &middot; h:i A', strtotime($n['created_at'])) : 'Recently' ?>
                    <?php if (!empty($n['job_title'])): ?>&middot; <strong><?= esc($n['job_title']) ?></strong><?php endif; ?>
                    <?php if (!empty($n['first_name'])): ?>&middot; <?= esc(trim($n['first_name'].' '.($n['last_name']??''))) ?><?php endif; ?>
                </div>
            </div>
            <div class="notif-actions">
                <?php if ($actionUrl): ?>
                    <a href="<?= esc($actionUrl) ?>" class="emp-btn emp-btn-outline emp-btn-sm" style="padding:4px 10px;font-size:.75rem;white-space:nowrap">
                        <?php
                        if (in_array($type,['subscription_expiring','subscription_expired'])) echo 'Renew Plan';
                        elseif (in_array($type,['payment_confirmed','subscription_renewed'])) echo 'View Plan';
                        elseif (!empty($n['application_id'])) echo 'View Application';
                        elseif (!empty($n['job_id']))         echo 'View Job';
                        elseif ($type==='new_message')        echo 'View Messages';
                        else echo 'View';
                        ?>
                    </a>
                <?php endif; ?>
                <?php if (!$isRead): ?>
                    <button type="button" class="emp-btn emp-btn-ghost emp-btn-sm" style="padding:4px 8px;font-size:.75rem;" onclick="markNotificationRead(<?= $nId ?>,this)" title="Mark as read">
                        <svg aria-hidden="true" width="13" height="13"><use href="#i-check"/></svg>
                    </button>
                <?php endif; ?>
                <button type="button" class="emp-btn emp-btn-ghost emp-btn-sm" style="padding:4px 8px;font-size:.75rem;color:var(--muted);" onclick="deleteNotification(<?= $nId ?>,this)" title="Dismiss">
                    <svg aria-hidden="true" width="13" height="13"><use href="#i-x"/></svg>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="nc-empty" id="empty-filtered" style="display:none;">
            <svg aria-hidden="true"><use href="#i-bell"/></svg>
            <h3>No notifications in this category</h3>
            <p>Notifications from this category will appear here when available.</p>
        </div>
        </div>
    <?php endif; ?>
    </div>
</section>
</div><!-- /nc-wrap -->
</div><!-- /section-notifications -->

<!-- SECTION 2: CANDIDATE ALERTS -->
<div id="section-alerts" style="display:none;">
<section class="card" aria-label="Your candidate alerts">
    <div class="card-head">
        <span class="card-title"><svg aria-hidden="true"><use href="#i-bell"/></svg> Candidate Alerts <span class="pill pill--reviewed"><?= $activeAlertCount ?> active</span></span>
    </div>
    <div class="card-body">
    <?php if (empty($alerts)): ?>
        <div class="empty-state">
            <div class="empty-ic"><svg aria-hidden="true"><use href="#i-bell"/></svg></div>
            <h3>Create your first alert</h3>
            <p>Set criteria below to stay updated with candidate matches tailored to your job descriptions.</p>
        </div>
    <?php else: ?>
        <?php foreach ($alerts as $alert):
            $criteria = is_string($alert['criteria']??'') ? json_decode($alert['criteria'],true) : ($alert['criteria']??[]);
            $matches  = $alert['matches'] ?? [];
            $matchesCount = count($matches);
        ?>
        <div class="alert-card" data-id="<?= esc($alert['id']??'') ?>">
            <div class="alert-top">
                <span class="alert-ic" aria-hidden="true"><svg><use href="#i-bell"/></svg></span>
                <div>
                    <div class="alert-name"><?= esc($alert['name']??'Candidate Alert') ?></div>
                    <div class="alert-meta">Created <?= date('d M Y', strtotime($alert['created_at']??'now')) ?></div>
                </div>
                <?php if ($matchesCount>0): ?>
                    <a href="<?= site_url('employer/candidates?'.http_build_query($criteria)) ?>" class="alert-new">
                        <svg aria-hidden="true"><use href="#i-users"/></svg> <?= $matchesCount ?> new <?= $matchesCount===1?'match':'matches' ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="chips alert-chips">
                <?php if (!empty($criteria['keyword']??$criteria['role']??'')): ?><span class="chip"><?= esc($criteria['keyword']??$criteria['role']) ?></span><?php endif; ?>
                <?php if (!empty($criteria['category']??'')): ?><span class="chip"><?= esc($criteria['category']) ?></span><?php endif; ?>
                <?php if (!empty($criteria['location']??'')): ?><span class="chip"><?= esc($criteria['location']) ?></span><?php endif; ?>
                <?php if (!empty($criteria['experience']??'')): ?><span class="chip"><?= esc($criteria['experience']) ?>+ yrs exp</span><?php endif; ?>
                <?php if (!empty($criteria['education']??'')): ?><span class="chip"><?= esc($criteria['education']) ?></span><?php endif; ?>
            </div>
            <?php if (!empty($matches)): ?>
            <div class="match-strip" aria-label="New matching candidates">
                <?php foreach ($matches as $match):
                    $initials = strtoupper(substr($match['first_name']??'C',0,1).substr($match['last_name']??'A',0,1));
                ?>
                <div class="match">
                    <span class="ava ava--round" aria-hidden="true"><?= esc($initials) ?></span>
                    <span><b><?= esc(($match['first_name']??'').' '.($match['last_name']??'')) ?></b>
                    <i><?= esc($match['title']??'Candidate') ?> &middot; <?= esc($match['experience']??'0') ?> yrs</i></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="alert-controls">
                <label class="ctl">Frequency
                    <select class="select frequency-select" aria-label="Frequency for <?= esc($alert['name']??'') ?>">
                        <option value="instant" <?= ($alert['frequency']??'')==='instant'?'selected':'' ?>>Instant</option>
                        <option value="daily"   <?= ($alert['frequency']??'')==='daily'  ?'selected':'' ?>>Daily digest</option>
                        <option value="weekly"  <?= ($alert['frequency']??'')==='weekly' ?'selected':'' ?>>Weekly digest</option>
                    </select>
                </label>
                <label class="ctl">
                    <span class="switch"><input type="checkbox" class="email-toggle" <?= ($alert['email_active']??$alert['email']??true)?'checked':'' ?> aria-label="Email for <?= esc($alert['name']??'') ?>"><span class="sl"></span></span>
                    Email
                </label>
                <label class="ctl">
                    <span class="switch"><input type="checkbox" class="active-toggle" <?= ($alert['active']??true)?'checked':'' ?> aria-label="Active: <?= esc($alert['name']??'') ?>"><span class="sl"></span></span>
                    Active
                </label>
                <button class="ic-btn ic-btn--danger alert-del btn-delete-alert" aria-label="Delete alert: <?= esc($alert['name']??'') ?>" title="Delete alert">
                    <svg aria-hidden="true"><use href="#i-trash"/></svg>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>
</section>

<section class="card" id="new-alert" aria-label="Create a new alert" style="margin-top:20px">
    <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-plus"/></svg> Create a New Alert</span></div>
    <div class="card-body">
        <form action="<?= site_url('employer/candidate-alerts') ?>" method="POST" id="create-alert-form">
            <?= csrf_field() ?>
            <div class="new-alert-grid">
                <div><label class="lbl" for="na-name">Alert Name</label><input class="input" id="na-name" name="name" type="text" placeholder="e.g. Accountants in Lagos" required></div>
                <div><label class="lbl" for="na-role">Role or keywords</label><input class="input" id="na-role" name="keyword" type="text" placeholder="e.g. Accountant, bookkeeping"></div>
                <div><label class="lbl" for="na-category">Category</label>
                    <select class="select" id="na-category" name="category"><option value="">Any Category</option>
                    <?php foreach ($categories as $cat): $catName=is_object($cat)?($cat->name??''):(is_array($cat)?($cat['name']??''):$cat); ?>
                    <option value="<?= esc($catName) ?>"><?= esc($catName) ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div><label class="lbl" for="na-loc">Location</label>
                    <select class="select" id="na-loc" name="location"><option value="">Any location</option>
                    <option value="Lagos">Lagos State</option><option value="Abuja">Abuja (FCT)</option>
                    <option value="Rivers">Rivers State</option><option value="Remote">Remote</option>
                    </select>
                </div>
                <div><label class="lbl" for="na-exp">Min. experience (yrs)</label>
                    <select class="select" id="na-exp" name="experience"><option value="">Any</option>
                    <option value="1">1+ years</option><option value="3">3+ years</option><option value="5">5+ years</option>
                    </select>
                </div>
                <div><label class="lbl" for="na-edu">Education</label>
                    <select class="select" id="na-edu" name="education"><option value="">Any</option>
                    <option value="OND">OND</option><option value="HND">HND</option>
                    <option>Bachelor&apos;s Degree</option><option>Master&apos;s Degree</option><option value="PhD">PhD</option>
                    </select>
                </div>
            </div>
            <div class="na-foot">
                <p>You can fine-tune frequency and channels after creating the alert.</p>
                <button type="submit" class="emp-btn emp-btn-primary"><svg aria-hidden="true"><use href="#i-bell"/></svg> Create Alert</button>
            </div>
        </form>
    </div>
</section>

<div class="notice notice--info" style="margin-top:16px">
    <svg aria-hidden="true"><use href="#i-bulb"/></svg>
    <span>Alerts power your AI Recruiter matches on the dashboard. The more specific your criteria, the better the matches.</span>
</div>
</div><!-- /section-alerts -->

<!-- Delete Alert Confirmation Modal -->
<div class="modal-scrim" id="delete-alert-scrim" style="display:none;position:fixed;inset:0;background:rgba(10,25,45,.55);backdrop-filter:blur(2px);z-index:1400;align-items:center;justify-content:center;padding:24px;">
  <div style="background:#fff;border-radius:16px;width:100%;max-width:420px;overflow:hidden;box-shadow:var(--shadow-lg);" role="dialog" aria-modal="true">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px;border-bottom:1px solid var(--border);">
      <span style="font-family:'Sora',sans-serif;font-weight:800;font-size:1.05rem;color:var(--brand-deep);display:inline-flex;align-items:center;gap:9px;">
        <svg aria-hidden="true" style="width:16px;height:16px;color:var(--danger);"><use href="#i-trash"/></svg> Delete Alert
      </span>
      <button id="del-alert-close" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;border:1.5px solid var(--border);background:#fff;color:var(--muted);cursor:pointer;" aria-label="Close">
        <svg aria-hidden="true" style="width:16px;height:16px;"><use href="#i-x"/></svg>
      </button>
    </div>
    <div style="padding:18px 22px;"><p style="font-size:0.9rem;color:var(--text);line-height:1.6;margin:0;">Are you sure you want to delete this candidate alert? You will no longer receive matching candidate notifications for this search criteria.</p></div>
    <div style="display:flex;justify-content:flex-end;gap:10px;padding:14px 22px;border-top:1px solid var(--border);">
      <button class="emp-btn emp-btn-outline" id="del-alert-cancel">Cancel</button>
      <button class="emp-btn emp-btn-danger" id="del-alert-confirm"><svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Alert</button>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function switchMainTab(tab) {
    if (tab==='notifications') {
        $('#section-notifications').show(); $('#section-alerts').hide();
        $('#main-tab-notifications').addClass('active'); $('#main-tab-alerts').removeClass('active');
    } else {
        $('#section-notifications').hide(); $('#section-alerts').show();
        $('#main-tab-alerts').addClass('active'); $('#main-tab-notifications').removeClass('active');
    }
}

var filterLabels={all:'All Notifications',jobs:'Job Notifications',applications:'Applications',candidates:'Candidates & AI',payments:'Payments & Plans',messages:'Messages',system:'Account & System'};

function filterNotifs(cat) {
    document.querySelectorAll('.nc-filter-btn').forEach(function(btn){ btn.classList.toggle('active',btn.dataset.filter===cat); });
    var rows=document.querySelectorAll('#notif-list .notif-row'); var visible=0;
    rows.forEach(function(row){ var show=cat==='all'||row.dataset.category===cat; row.style.display=show?'':'none'; if(show)visible++; });
    var emptyEl=document.getElementById('empty-filtered'); if(emptyEl) emptyEl.style.display=visible===0?'':'none';
    document.getElementById('feed-title').textContent=filterLabels[cat]||'All Notifications';
    document.getElementById('feed-count-label').textContent=visible+' notification'+(visible!==1?'s':'');
}

function markNotificationRead(id,btn) {
    var csrfToken=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'<?= csrf_hash() ?>';
    var csrfName='<?= csrf_token() ?>'; var data={notification_id:id}; data[csrfName]=csrfToken;
    $.ajax({url:'<?= base_url("employer/notifications/mark-read") ?>',type:'POST',
        headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrfToken},data:data,dataType:'json',
        success:function(res){ if(res.success){ var row=$('#notif-row-'+id); row.removeClass('unread'); row.find('.notif-new-badge').remove(); $(btn).closest('button').remove(); updateUnreadBadge(res.unreadCount); } }
    });
}

function markAllNotificationsRead() {
    var csrfToken=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'<?= csrf_hash() ?>';
    var csrfName='<?= csrf_token() ?>'; var data={}; data[csrfName]=csrfToken;
    $.ajax({url:'<?= base_url("employer/notifications/mark-all-read") ?>',type:'POST',
        headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrfToken},data:data,dataType:'json',
        success:function(res){ if(res.success){ $('.notif-row').removeClass('unread'); $('.notif-new-badge').remove(); updateUnreadBadge(0); if(typeof toastr!=='undefined') toastr.success('All notifications marked as read.'); } }
    });
}

function deleteNotification(id,btn) {
    var csrfToken=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'<?= csrf_hash() ?>';
    var csrfName='<?= csrf_token() ?>'; var data={notification_id:id}; data[csrfName]=csrfToken;
    var row=document.getElementById('notif-row-'+id);
    if(row){row.style.opacity='.4';row.style.pointerEvents='none';}
    $.ajax({url:'<?= base_url("employer/notifications/delete") ?>',type:'POST',
        headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrfToken},data:data,dataType:'json',
        success:function(res){
            if(res.success){
                if(row){row.style.transition='opacity .25s,transform .25s';row.style.opacity='0';row.style.transform='scale(.97)';
                    setTimeout(function(){row.remove();updateUnreadBadge(res.unreadCount);var af=document.querySelector('.nc-filter-btn.active');if(af)filterNotifs(af.dataset.filter||'all');},280);}
            } else { if(row){row.style.opacity='';row.style.pointerEvents='';} if(typeof toastr!=='undefined') toastr.error(res.message||'Failed to dismiss notification.'); }
        },
        error:function(){if(row){row.style.opacity='';row.style.pointerEvents='';}}
    });
}

function updateUnreadBadge(count) {
    if(count!==undefined){
        if(count<=0){$('#unread-badge,#unread-badge-tab').remove();$('#btn-mark-all-read').remove();}
        else{$('#unread-badge').text(count+' new');$('#unread-badge-tab').text(count+' new');}
    }
}

$(document).ready(function(){
    const csrfTokenName='<?= csrf_token() ?>'; let csrfHash='<?= csrf_hash() ?>';
    function getAjaxData(extraData){return Object.assign({[csrfTokenName]:document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||csrfHash},extraData||{});}
    function updateCsrf(h){if(h){csrfHash=h;$('input[name="'+csrfTokenName+'"]').val(h);var m=document.querySelector('meta[name="csrf-token"]');if(m)m.setAttribute('content',h);}}

    $('.frequency-select,.email-toggle,.active-toggle').on('change',function(){
        var card=$(this).closest('.alert-card'); var alertId=card.data('id');
        $.ajax({url:'<?= site_url("employer/candidate-alerts/update") ?>/'+alertId,type:'POST',
            data:getAjaxData({frequency:card.find('.frequency-select').val(),email_active:card.find('.email-toggle').is(':checked')?1:0,active:card.find('.active-toggle').is(':checked')?1:0}),
            dataType:'json',success:function(r){if(r.csrf)updateCsrf(r.csrf);if(!r.success)toastr.error(r.message||'Failed to update.');},
            error:function(){toastr.error('An error occurred.');}
        });
    });

    var scrim=$('#delete-alert-scrim')[0]; var pendingId;
    function closeDelAlert(){scrim.style.display='none';document.body.style.overflow='';pendingId=null;}
    function openDelAlert(id){pendingId=id;scrim.style.display='flex';document.body.style.overflow='hidden';}
    document.getElementById('del-alert-cancel').addEventListener('click',closeDelAlert);
    document.getElementById('del-alert-close').addEventListener('click',closeDelAlert);
    scrim.addEventListener('click',function(e){if(e.target===scrim)closeDelAlert();});
    document.addEventListener('keydown',function(e){if(e.key==='Escape'&&scrim.style.display==='flex')closeDelAlert();});

    document.getElementById('del-alert-confirm').addEventListener('click',function(){
        if(!pendingId)return; var id=pendingId; var btn=this; btn.disabled=true; btn.innerHTML='Deleting...';
        $.ajax({url:'<?= site_url("employer/candidate-alerts/delete") ?>/'+id,type:'POST',data:getAjaxData(),dataType:'json',
            success:function(r){btn.disabled=false;btn.innerHTML='<svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Alert';
                if(r.csrf)updateCsrf(r.csrf);
                if(r.success){closeDelAlert();var c=document.querySelector('.alert-card[data-id="'+id+'"]');
                    if(c){c.style.transition='opacity .3s,transform .3s';c.style.opacity='0';c.style.transform='scale(.96)';
                        setTimeout(function(){c.remove();if(!document.querySelectorAll('.alert-card').length)location.reload();},300);}
                } else {toastr.error(r.message||'Failed to delete.');}
            },
            error:function(){btn.disabled=false;btn.innerHTML='<svg aria-hidden="true"><use href="#i-trash"/></svg> Delete Alert';toastr.error('An error occurred.');}
        });
    });

    document.querySelectorAll('.btn-delete-alert').forEach(function(btn){
        btn.addEventListener('click',function(e){e.preventDefault();var c=btn.closest('.alert-card');if(c)openDelAlert(c.dataset.id);});
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<button type="button" class="emp-btn emp-btn-outline" onclick="switchMainTab('notifications')"><svg aria-hidden="true"><use href="#i-bell"/></svg> Notifications</button>
<button type="button" class="emp-btn emp-btn-accent" onclick="switchMainTab('alerts')"><svg aria-hidden="true"><use href="#i-plus"/></svg> New Alert</button>
<?= $this->endSection() ?>
