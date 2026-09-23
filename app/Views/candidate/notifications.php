<?php $page_title = 'Notifications'; ?>
<?= $this->extend('layouts/app') ?>

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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header Section -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg> Notifications & Job Alerts</h1>
            <p>Stay updated on webinar reminders, course enrollments, job application updates, and custom alerts.</p>
        </div>
    </div>

    <!-- Recent Activity Notifications Feed -->
    <section class="card mb-4" style="margin-bottom: 24px;">
        <div class="card-head" style="display:flex; justify-content:space-between; align-items:center;">
            <span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg> Activity Notifications</span>
            <?php if (!empty($inAppNotifications)): ?>
                <span class="pill pill--reviewed"><?= count($inAppNotifications) ?> Notification<?= count($inAppNotifications) > 1 ? 's' : '' ?></span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (!empty($inAppNotifications)): ?>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <?php foreach ($inAppNotifications as $notif): ?>
                        <div style="padding: 14px 16px; border: 1px solid var(--border-c); border-radius: 8px; background-color: <?= empty($notif['is_read']) ? '#f0f7ff' : '#ffffff' ?>; display: flex; align-items: flex-start; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #e0e7ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: bold;">
                                🔔
                            </div>
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <h4 style="margin: 0; font-size: 0.95rem; font-weight: 600; color: #1e293b;"><?= esc($notif['title']) ?></h4>
                                    <span style="font-size: 0.78rem; color: #94a3b8;"><?= date('M j, Y g:i A', strtotime($notif['created_at'])) ?></span>
                                </div>
                                <p style="margin: 0; font-size: 0.88rem; color: #475569; line-height: 1.5;"><?= nl2br(esc($notif['message'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 0.9rem;">
                    No activity notifications yet. Webinar reminders, course updates, and application status changes will appear here.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <div class="alerts-grid">

        <!-- Left: Create Alert -->
        <section class="card" aria-label="Create job alert">
            <div class="card-head"><span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-plus"/></svg> Create Job Alert</span></div>
            <div class="card-body">
                <form id="alert-form" style="display:flex; flex-direction:column; gap:14px;">
                    <?= csrf_field() ?>
                    <div class="form-field">
                        <label class="lbl" for="ja-kw">Keyword</label>
                        <input type="text" id="ja-kw" name="keyword" class="input" placeholder="e.g. Accountant, remote" value="<?= esc($presetKeyword ?? '') ?>">
                    </div>
                    <div class="form-field">
                        <label class="lbl" for="ja-loc">Location</label>
                        <select id="ja-loc" name="location_id" class="select" required>
                            <option value="">Any location</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= $state->id ?>" <?= ($presetLocationId ?? '') == $state->id ? 'selected' : '' ?>><?= esc($state->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="lbl" for="ja-freq">Frequency</label>
                        <select id="ja-freq" name="frequency" class="select">
                            <option value="instant">Instant</option>
                            <option value="daily" selected>Daily</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="lbl" for="ja-time">Delivery time</label>
                        <input id="ja-time" type="time" name="delivery_time" class="input" value="08:00">
                    </div>
                    <div class="form-field">
                        <label class="lbl" for="ja-ch">Notification channel</label>
                        <select id="ja-ch" name="channel" class="select">
                            <option value="email">Email (default)</option>
                            <option value="inapp">In-app only</option>
                            <option value="both">Email + in-app</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg> Create Alert
                    </button>
                </form>
            </div>
        </section>

        <!-- Right: Existing Alerts -->
        <section class="card" aria-label="Your alerts">
            <div class="card-head"><span class="card-title"><svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg> Your Alerts</span></div>
            <?php if (!empty($alerts) && count($alerts) > 0): ?>
            <?php
                // Map state id -> name so alerts show "Lagos State", not a raw numeric id
                $stateMap = [];
                foreach (($states ?? []) as $st) { $stateMap[$st->id] = $st->name; }
            ?>
            <div class="tbl-wrap">
                <table class="tbl" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Location</th>
                            <th>Frequency</th>
                            <th>Time</th>
                            <th>Channel</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alerts as $alert): ?>
                            <?php
                                $isSnoozed = !empty($alert->snooze_until) && strtotime($alert->snooze_until) > time();
                                $isPaused = !empty($alert->is_paused);
                            ?>
                            <tr>
                                <td><b style="color:var(--brand-deep);"><?= esc($alert->keyword) ?></b></td>
                                <td><?= esc($alert->location_id ? ($stateMap[$alert->location_id] ?? 'Any') : 'Any location') ?></td>
                                <td><span class="pill pill--reviewed"><?= ucfirst($alert->frequency) ?></span></td>
                                <td style="font-size:.8rem;color:var(--muted);"><?= $alert->delivery_time ? date('g:i A', strtotime($alert->delivery_time)) : '—' ?></td>
                                <td><?= ucfirst($alert->channel ?? 'email') ?></td>
                                <td>
                                    <?php if ($isSnoozed): ?>
                                        <span class="pill pill--pending">Snoozed until <?= date('M j', strtotime($alert->snooze_until)) ?></span>
                                    <?php elseif ($isPaused): ?>
                                        <span class="pill pill--closed">Paused</span>
                                    <?php else: ?>
                                        <span class="pill pill--hired">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space:nowrap">
                                    <?php if ($isPaused || $isSnoozed): ?>
                                        <button class="btn btn-outline btn-sm resume-alert" data-id="<?= $alert->id ?>" title="Resume alert">
                                            <svg aria-hidden="true" style="width:12px;height:12px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-play"/></svg> Resume
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline btn-sm pause-alert" data-id="<?= $alert->id ?>" title="Pause alert">
                                            <svg aria-hidden="true" style="width:12px;height:12px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-pause"/></svg> Pause
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-outline btn-sm delete-alert" data-id="<?= $alert->id ?>">
                                        <svg aria-hidden="true" style="width:12px;height:12px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-trash"/></svg> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty">
                <span class="empty-ic"><svg aria-hidden="true" style="width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg></span>
                <h3>No alerts created yet</h3>
                <p>Create your first alert and we'll deliver matching jobs to your inbox the moment they go live.</p>
            </div>
            <?php endif; ?>
        </section>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('#alert-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('candidate/alerts/save') ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success("Job alert created successfully");
                    setTimeout(() => location.reload(), 800);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error("Network error");
            }
        });
    });

    // Delete alert
    $('.delete-alert').on('click', function() {
        let id = $(this).data('id');

        if (!confirm("Delete this alert?")) return;

        $.post("<?= site_url('candidate/alerts/delete') ?>/" + id, {
            <?= csrf_token() ?>: "<?= csrf_hash() ?>"
        }, function(response) {
            if (response.success) {
                toastr.success("Alert deleted");
                setTimeout(() => location.reload(), 800);
            } else {
                toastr.error("Unable to delete alert");
            }
        });
    });

    // Pause alert
    $('.pause-alert').on('click', function() {
        let id = $(this).data('id');
        $.post("<?= site_url('candidate/alerts/pause') ?>/" + id, {
            <?= csrf_token() ?>: "<?= csrf_hash() ?>"
        }, function(response) {
            if (response.success) {
                toastr.success("Alert paused");
                setTimeout(() => location.reload(), 800);
            } else {
                toastr.error("Unable to pause alert");
            }
        });
    });

    // Resume alert
    $('.resume-alert').on('click', function() {
        let id = $(this).data('id');
        $.post("<?= site_url('candidate/alerts/resume') ?>/" + id, {
            <?= csrf_token() ?>: "<?= csrf_hash() ?>"
        }, function(response) {
            if (response.success) {
                toastr.success("Alert resumed");
                setTimeout(() => location.reload(), 800);
            } else {
                toastr.error("Unable to resume alert");
            }
        });
    });
</script>
<?= $this->endSection() ?>