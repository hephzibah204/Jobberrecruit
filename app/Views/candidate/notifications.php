<?php $page_title = 'Notifications'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Header Section -->
    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg> Job Alerts</h1>
            <p>Get notified the moment new jobs match your criteria — never miss an opening.</p>
        </div>
    </div>

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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alerts as $alert): ?>
                            <tr>
                                <td><b style="color:var(--brand-deep);"><?= esc($alert->keyword) ?></b></td>
                                <td><?= esc($alert->location_id ? ($stateMap[$alert->location_id] ?? 'Any') : 'Any location') ?></td>
                                <td><span class="pill pill--reviewed"><?= ucfirst($alert->frequency) ?></span></td>
                                <td style="font-size:.8rem;color:var(--muted);"><?= $alert->delivery_time ? date('g:i A', strtotime($alert->delivery_time)) : '—' ?></td>
                                <td><?= ucfirst($alert->channel ?? 'email') ?></td>
                                <td>
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
</script>
<?= $this->endSection() ?>