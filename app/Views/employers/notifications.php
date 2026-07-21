<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.alert-card {
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px 18px;
    background: #fff;
    transition: var(--transition);
}
.alert-card:hover {
    box-shadow: var(--shadow);
}
.alert-card + .alert-card {
    margin-top: 14px;
}
.alert-top {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}
.alert-ic {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--brand-light);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.alert-ic svg {
    width: 18px;
    height: 18px;
}
.alert-name {
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    font-size: .92rem;
    color: var(--brand-deep);
}
.alert-meta {
    font-size: .72rem;
    color: var(--muted);
    margin-top: 1px;
}
.alert-new {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .74rem;
    font-weight: 700;
    color: var(--accent-dark);
    background: var(--accent-light);
    border-radius: 20px;
    padding: 5px 13px;
    text-decoration: none;
}
.alert-new:hover {
    background: #f8e3c4;
    text-decoration: none;
}
.alert-new svg {
    width: 12px;
    height: 12px;
}
.alert-chips {
    margin: 12px 0 14px;
}
.alert-controls {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    padding-top: 13px;
    border-top: 1px dashed var(--border);
}
.alert-controls .select {
    min-height: 38px;
    width: auto;
    font-size: .8rem;
    padding-top: 6px;
    padding-bottom: 6px;
}
.ctl {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .78rem;
    font-weight: 600;
    color: var(--text);
}
.switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 22px;
    flex-shrink: 0;
}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch .sl {
    position: absolute;
    inset: 0;
    background: var(--border);
    border-radius: 20px;
    transition: var(--transition);
    cursor: pointer;
}
.switch .sl::before {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #fff;
    left: 3px;
    top: 3px;
    transition: var(--transition);
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.switch input:checked + .sl {
    background: var(--brand);
}
.switch input:checked + .sl::before {
    transform: translateX(18px);
}
.switch input:focus-visible + .sl {
    outline: 3px solid var(--accent);
    outline-offset: 2px;
}
.alert-del {
    margin-left: auto;
}
.match-strip {
    display: flex;
    gap: 9px;
    margin-top: 13px;
    overflow-x: auto;
    padding-bottom: 2px;
    -webkit-overflow-scrolling: touch;
}
.match {
    display: flex;
    align-items: center;
    gap: 9px;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 8px 12px;
    flex-shrink: 0;
    background: var(--bg);
}
.match .ava {
    width: 32px;
    height: 32px;
    font-size: .66rem;
    border-radius: 50%;
}
.match b {
    font-size: .76rem;
    color: var(--brand-deep);
    display: block;
    line-height: 1.3;
    white-space: nowrap;
}
.match i {
    font-style: normal;
    font-size: .64rem;
    color: var(--muted);
    white-space: nowrap;
}
.new-alert-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
}
@media (max-width: 1200px) {
    .new-alert-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (max-width: 768px) {
    .new-alert-grid {
        grid-template-columns: 1fr 1fr;
    }
}
@media (max-width: 480px) {
    .new-alert-grid {
        grid-template-columns: 1fr;
    }
}
.na-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 16px;
}
.na-foot p {
    font-size: .74rem;
    color: var(--muted);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-head">
    <div class="page-head-left">
        <h1>
            <svg aria-hidden="true"><use href="#i-bell"/></svg> 
            Candidate Alerts
        </h1>
        <p>Save search criteria once — we'll notify you when matching candidates join or update their profiles.</p>
    </div>
    <div class="page-actions">
        <a href="#new-alert" class="emp-btn emp-btn-primary emp-btn-sm">
            <svg aria-hidden="true"><use href="#i-plus"/></svg> New Alert
        </a>
    </div>
</div>

<section class="card" aria-label="Your alerts">
    <div class="card-head">
        <span class="card-title">
            <svg aria-hidden="true"><use href="#i-bell"/></svg> 
            Your Alerts 
            <span class="pill pill--reviewed">
                <?= count($alerts) ?> <?= count($alerts) === 1 ? 'alert' : 'alerts' ?>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // CSRF Token and Hash
    const csrfTokenName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    function getAjaxData(extraData = {}) {
        return {
            [csrfTokenName]: csrfHash,
            ...extraData
        };
    }

    function updateCsrf(newHash) {
        if (newHash) {
            csrfHash = newHash;
            $('input[name="' + csrfTokenName + '"]').val(newHash);
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
                    alert(response.message || 'Failed to update alert settings.');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Delete alert
    $('.btn-delete-alert').on('click', function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete this alert?')) return;

        const card = $(this).closest('.alert-card');
        const alertId = card.data('id');

        $.ajax({
            url: '<?= site_url("employer/candidate-alerts/delete") ?>/' + alertId,
            type: 'POST',
            data: getAjaxData(),
            dataType: 'json',
            success: function(response) {
                if (response.csrf) updateCsrf(response.csrf);
                if (response.success) {
                    card.fadeOut(300, function() {
                        $(this).remove();
                        if ($('.alert-card').length === 0) {
                            location.reload();
                        }
                    });
                } else {
                    alert(response.message || 'Failed to delete alert.');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>