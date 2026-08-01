<?php $page_title = 'Job Alerts'; ?>
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
.alerts-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: clamp(14px, 1.8vw, 20px);
    align-items: start;
}
@media (max-width: 1000px) {
    .alerts-grid {
        grid-template-columns: 1fr;
    }
}
.form-stack {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.ic-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 1.5px solid var(--border);
    background: #fff;
    color: var(--muted);
    cursor: pointer;
    transition: var(--transition);
    flex-shrink: 0;
}
.ic-btn:hover { border-color: var(--brand); color: var(--brand); }
.ic-btn svg { width: 15px; height: 15px; }
.ic-btn--danger:hover { border-color: var(--danger); color: var(--danger); }
.switch { position: relative; display: inline-block; width: 46px; height: 26px; flex-shrink: 0; }
.switch input { opacity: 0; width: 0; height: 0; }
.sl { position: absolute; inset: 0; background: var(--border); border-radius: 26px; cursor: pointer; transition: .2s; }
.sl::before { content: ''; position: absolute; width: 20px; height: 20px; left: 3px; bottom: 3px;
  background: #fff; border-radius: 50%; transition: .2s; box-shadow: 0 1px 4px rgba(0,0,0,.18); }
.switch input:checked + .sl { background: var(--brand); }
.switch input:checked + .sl::before { transform: translateX(20px); }
.notice { display: flex; align-items: flex-start; gap: 12px; background: var(--brand-light);
  border: 1px solid #bbd3ef; border-radius: var(--radius-lg); padding: 16px 20px;
  font-size: .84rem; color: var(--brand-deep); }
.notice svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; color: var(--brand); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <div class="page-head">
        <div>
            <h1><svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg> Job Alerts</h1>
            <p>Get notified the moment new jobs match your criteria — never miss an opening.</p>
        </div>
    </div>

    <div class="alerts-grid">
        <!-- Create alert form -->
        <section class="card" aria-label="Create job alert">
            <div class="card-head">
                <span class="card-title">
                    <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-plus"/></svg>
                    Create Job Alert
                </span>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('candidate/alerts/create') ?>" id="alert-form">
                    <?= csrf_field() ?>
                    <div class="form-stack">
                        <div>
                            <label class="lbl" for="ja-kw">Keyword</label>
                            <input class="input" id="ja-kw" name="keyword" type="text" placeholder="e.g. Accountant, remote" required>
                        </div>
                        <div>
                            <label class="lbl" for="ja-loc">Location</label>
                            <select class="select" id="ja-loc" name="location">
                                <option value="">Any location</option>
                                <option>Lagos State</option>
                                <option>Abuja (FCT)</option>
                                <option>Rivers State</option>
                                <option>Ogun State</option>
                                <option>Kano State</option>
                                <option>Remote</option>
                            </select>
                        </div>
                        <div>
                            <label class="lbl" for="ja-freq">Frequency</label>
                            <select class="select" id="ja-freq" name="frequency">
                                <option value="instant">Instant</option>
                                <option value="daily" selected>Daily</option>
                                <option value="weekly">Weekly</option>
                            </select>
                        </div>
                        <div>
                            <label class="lbl" for="ja-time">Delivery time</label>
                            <input class="input" id="ja-time" name="delivery_time" type="time" value="08:00">
                        </div>
                        <div>
                            <label class="lbl" for="ja-ch">Notification channel</label>
                            <select class="select" id="ja-ch" name="channel">
                                <option value="email" selected>Email (default)</option>
                                <option value="inapp">In-app only</option>
                                <option value="both">Email + in-app</option>
                            </select>
                        </div>
                        <button class="btn btn-primary btn-block" type="submit">
                            <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg>
                            Create Alert
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Existing alerts -->
        <section class="card" aria-label="Your alerts">
            <div class="card-head">
                <span class="card-title">
                    <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg>
                    Your Alerts
                </span>
                <?php if (!empty($alerts)): ?>
                <span class="pill pill--reviewed"><?= count($alerts) ?> active</span>
                <?php endif; ?>
            </div>

            <?php if (empty($alerts)): ?>
            <div class="empty">
                <span class="empty-ic"><svg aria-hidden="true" style="width:28px;height:28px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bell"/></svg></span>
                <h3>No alerts created yet</h3>
                <p>Create your first alert and we'll deliver matching jobs to your inbox &mdash; alerts put matching jobs in your inbox the moment they go live.</p>
            </div>
            <?php else: ?>
            <div class="tbl-wrap">
                <table class="tbl" style="min-width:700px;">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Location</th>
                            <th>Frequency</th>
                            <th>Delivery time</th>
                            <th>Channel</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alerts as $alert): ?>
                        <tr>
                            <td><b><?= esc($alert->keyword) ?></b></td>
                            <td><?= esc($alert->location ?: 'Any') ?></td>
                            <td><?= esc(ucfirst($alert->frequency)) ?></td>
                            <td><?= esc($alert->delivery_time ?? '08:00') ?></td>
                            <td><?= esc(ucfirst($alert->channel ?? 'Email')) ?></td>
                            <td>
                                <div style="display:flex;gap:12px;align-items:center;">
                                    <label class="switch" aria-label="Toggle alert <?= esc($alert->keyword) ?>">
                                        <input type="checkbox" class="alert-toggle" data-id="<?= $alert->id ?>" <?= !empty($alert->is_active) ? 'checked' : '' ?>>
                                        <span class="sl"></span>
                                    </label>
                                    <form method="POST" action="<?= base_url('candidate/alerts/delete/' . $alert->id) ?>" onsubmit="return confirm('Delete this alert?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="ic-btn ic-btn--danger" type="submit" aria-label="Delete alert">
                                            <svg aria-hidden="true"><use href="#i-trash"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </section>
    </div>

    <div class="notice" role="note" style="margin-top:20px;">
        <svg aria-hidden="true"><use href="#i-bulb"/></svg>
        <span>Tip: use specific keywords like "Senior Accountant Lagos" for more relevant results. Alerts put matching jobs in your inbox the moment they go live.</span>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// AJAX toggle for alert active/inactive
document.querySelectorAll('.alert-toggle').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        var id = this.dataset.id;
        var active = this.checked ? 1 : 0;
        var self = this;
        self.disabled = true;
        $.ajax({
            url: '<?= base_url('candidate/alerts/toggle') ?>',
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>', id: id, is_active: active },
            success: function(res) {
                if (res && res.success) {
                    if (typeof toastr !== 'undefined') toastr.success(active ? 'Alert activated.' : 'Alert paused.');
                } else {
                    self.checked = !self.checked;
                    if (typeof toastr !== 'undefined') toastr.error('Could not update alert.');
                }
            },
            error: function() {
                self.checked = !self.checked;
                if (typeof toastr !== 'undefined') toastr.error('Network error. Try again.');
            },
            complete: function() { self.disabled = false; }
        });
    });
});
</script>
<?= $this->endSection() ?>
