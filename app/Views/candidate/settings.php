<?php $page_title = 'General Settings'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
@keyframes rise{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
html.anim-ready .content>*{animation:rise .34s ease both}
html.anim-ready .content>*:nth-child(2){animation-delay:.05s}
html.anim-ready .content>*:nth-child(3){animation-delay:.1s}
html.anim-ready .content>*:nth-child(4){animation-delay:.15s}
html.anim-ready .content>*:nth-child(5){animation-delay:.2s}
html.anim-ready .content>*:nth-child(n+6){animation-delay:.24s}
.card{box-shadow:0 1px 3px rgba(10,47,87,.06);transition:var(--transition)}
.card:hover{box-shadow:0 2px 10px rgba(10,47,87,.07)}
.btn{transition:transform .12s cubic-bezier(.2,.8,.2,1),box-shadow .18s ease,background-color .18s ease,border-color .18s ease}
.btn:active{transform:scale(.97)}
.btn:not(:disabled):hover{transform:translateY(-1px)}
.btn:not(:disabled):active{transform:translateY(0) scale(.97)}
@media(prefers-reduced-motion:reduce){.btn{transition:background-color .12s ease,border-color .12s ease!important}.btn:active,.btn:hover{transform:none!important}}
</style>
<style>
/* ── Settings layout ──────────────────────────────────────────── */
.set-wrap { display: flex; flex-direction: column; gap: 24px; max-width: 680px; }

/* Card shell */
.set-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 26px 28px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}
.set-card h3 {
    font-family: 'Sora', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--brand-deep);
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.set-card h3 svg { width: 18px; height: 18px; color: var(--brand); }
.set-sub { font-size: .8rem; color: var(--muted); margin: 0; }

/* Form fields */
.set-field { display: flex; flex-direction: column; gap: 6px; }
.set-field label { font-size: .74rem; font-weight: 600; color: var(--muted); }
.set-field input {
    width: 100%;
    padding: 10px 44px 10px 14px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-size: 15px;
    background: #fff;
    transition: var(--transition);
}
.set-field input:focus {
    outline: none;
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(8, 97, 169, .12);
}

/* Show / hide password toggle */
.pw-wrap { position: relative; }
.toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: var(--muted);
    padding: 4px;
    line-height: 0;
}
.toggle-password svg { width: 16px; height: 16px; }

/* Strength bar */
.set-strength {
    height: 5px;
    background: var(--border);
    border-radius: 4px;
    overflow: hidden;
    margin-top: 6px;
}
.set-strength-fill {
    height: 100%;
    width: 0;
    border-radius: 4px;
    transition: width .3s ease, background .3s ease;
}
.set-hint { font-size: .72rem; color: var(--muted); margin-top: 4px; }

/* Notification toggle rows */
.toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid var(--border);
}
.toggle-row:last-of-type { border-bottom: none; }
.tr-label b  { display: block; font-size: .86rem; font-weight: 600; color: var(--brand-deep); }
.tr-label i  { font-style: normal; font-size: .74rem; color: var(--muted); }

/* iOS-style toggle switch */
.switch { position: relative; display: inline-block; width: 46px; height: 26px; flex-shrink: 0; }
.switch input { opacity: 0; width: 0; height: 0; }
.sl {
    position: absolute;
    inset: 0;
    background: var(--border);
    border-radius: 26px;
    cursor: pointer;
    transition: .2s;
}
.sl::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .2s;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .18);
}
.switch input:checked + .sl { background: var(--brand); }
.switch input:checked + .sl::before { transform: translateX(20px); }

/* Danger zone */
.danger-card { border-color: #fca5a5; }
.danger-card h3          { color: var(--danger); }
.danger-card h3 svg      { color: var(--danger); }
.btn-danger              { background: var(--danger); color: #fff; border-color: var(--danger); }
.btn-danger:hover        { background: #b91c1c; border-color: #b91c1c; }

/* Delete confirmation modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(10, 25, 45, .55);
    backdrop-filter: blur(2px);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .2s ease;
}
.modal-overlay.show { opacity: 1; pointer-events: all; }
.modal {
    background: #fff;
    border-radius: var(--radius-lg);
    padding: 32px 28px;
    max-width: 440px;
    width: 100%;
    box-shadow: var(--shadow-lg);
}
.m-ic {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: var(--danger-light);
    color: var(--danger);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}
.m-ic svg { width: 26px; height: 26px; }
.modal h3   { font-family: 'Sora', sans-serif; font-weight: 700; color: var(--brand-deep); margin-bottom: 10px; }
.modal p    { font-size: .84rem; color: var(--muted); margin-bottom: 20px; }
.m-confirm label { font-size: .74rem; font-weight: 600; color: var(--muted); display: block; margin-bottom: 6px; }
.m-confirm input {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-size: 15px;
    margin-bottom: 20px;
}
.m-confirm input:focus { outline: none; border-color: var(--danger); }
.modal-actions { display: flex; gap: 10px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">

    <!-- Page header -->
    <div class="page-head">
        <div>
            <h1>
                <svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;">
                    <use href="#i-cog"/>
                </svg>
                General Settings
            </h1>
            <p>Manage your password, notification preferences, and account.</p>
        </div>
    </div>

    <div class="set-wrap" style="margin-top: 20px;">

        <!-- SECTION 1 · CHANGE PASSWORD -->
        <section class="set-card" aria-labelledby="pw-heading">
            <h3 id="pw-heading">
                <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2">
                    <use href="#i-shield"/>
                </svg>
                Change password
            </h3>
            <p class="set-sub">Use a strong password you don't use anywhere else.</p>

            <form id="set-pw-form" method="post"
                  action="<?= base_url('candidate/settings/security/change-password') ?>"
                  novalidate>
                <?= csrf_field() ?>

                <!-- Current password -->
                <div class="set-field">
                    <label for="set-current-pw">
                        Current password <span style="color:var(--danger)">*</span>
                    </label>
                    <div class="pw-wrap">
                        <input type="password"
                               id="set-current-pw"
                               name="current_password"
                               autocomplete="current-password"
                               required
                               placeholder="Enter your current password">
                        <button type="button"
                                class="toggle-password"
                                aria-label="Toggle current password visibility"
                                data-target="set-current-pw">
                            <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2">
                                <use href="#i-eye"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- New password + strength bar -->
                <div class="set-field">
                    <label for="set-new-pw">
                        New password <span style="color:var(--danger)">*</span>
                    </label>
                    <div class="pw-wrap">
                        <input type="password"
                               id="set-new-pw"
                               name="new_password"
                               autocomplete="new-password"
                               required
                               placeholder="At least 8 characters">
                        <button type="button"
                                class="toggle-password"
                                aria-label="Toggle new password visibility"
                                data-target="set-new-pw">
                            <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2">
                                <use href="#i-eye"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Live strength indicator -->
                    <div class="set-strength" role="presentation" aria-hidden="true">
                        <div class="set-strength-fill" id="set-strength-fill"></div>
                    </div>
                    <p class="set-hint" id="set-strength-label" aria-live="polite"></p>
                    <p class="set-hint">Must be at least 8 characters with letters, numbers &amp; symbols.</p>
                </div>

                <!-- Confirm new password + match feedback -->
                <div class="set-field">
                    <label for="set-confirm-pw">
                        Confirm new password <span style="color:var(--danger)">*</span>
                    </label>
                    <div class="pw-wrap">
                        <input type="password"
                               id="set-confirm-pw"
                               name="confirm_new_password"
                               autocomplete="new-password"
                               required
                               placeholder="Re-enter new password">
                        <button type="button"
                                class="toggle-password"
                                aria-label="Toggle confirm password visibility"
                                data-target="set-confirm-pw">
                            <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2">
                                <use href="#i-eye"/>
                            </svg>
                        </button>
                    </div>
                    <p class="set-hint" id="set-match-hint" aria-live="polite"></p>
                </div>

                <div style="display:flex; justify-content:flex-end; margin-top:4px;">
                    <button type="submit" class="btn btn-primary" id="set-pw-btn">
                        <span class="btn-text">Update password</span>
                        <span class="spinner d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </section>


        <!-- SECTION 2 · NOTIFICATIONS -->
        <section class="set-card" aria-labelledby="notif-heading">
            <h3 id="notif-heading">
                <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2">
                    <use href="#i-bell"/>
                </svg>
                Notifications
            </h3>
            <p class="set-sub">Choose which emails and alerts you'd like to receive.</p>

            <form id="set-notif-form" novalidate>
                <?= csrf_field() ?>

                <!-- Job alerts -->
                <div class="toggle-row">
                    <div class="tr-label">
                        <b>Job alerts</b>
                        <i>New jobs matching your saved searches</i>
                    </div>
                    <label class="switch" aria-label="Toggle job alerts">
                        <input type="checkbox"
                               name="notify_job_alerts"
                               id="notif-job-alerts"
                               <?= ($candidate->notify_job_alerts ?? 1) ? 'checked' : '' ?>>
                        <span class="sl"></span>
                    </label>
                </div>

                <!-- Application updates -->
                <div class="toggle-row">
                    <div class="tr-label">
                        <b>Application updates</b>
                        <i>When an employer views or responds</i>
                    </div>
                    <label class="switch" aria-label="Toggle application updates">
                        <input type="checkbox"
                               name="notify_application_updates"
                               id="notif-app-updates"
                               <?= ($candidate->notify_application_updates ?? 1) ? 'checked' : '' ?>>
                        <span class="sl"></span>
                    </label>
                </div>

                <!-- Messages / Chat notifications -->
                <div class="toggle-row">
                    <div class="tr-label">
                        <b>Messages &amp; Chat</b>
                        <i>When an employer sends you a message</i>
                    </div>
                    <label class="switch" aria-label="Toggle chat notifications">
                        <input type="checkbox"
                               name="notify_messages"
                               id="notif-messages"
                               <?= ($candidate->notify_messages ?? 1) ? 'checked' : '' ?>>
                        <span class="sl"></span>
                    </label>
                </div>

                <!-- Marketing / Product news -->
                <div class="toggle-row">
                    <div class="tr-label">
                        <b>Product news &amp; marketing</b>
                        <i>Occasional updates about JobberRecruit</i>
                    </div>
                    <label class="switch" aria-label="Toggle product news notifications">
                        <input type="checkbox"
                               name="notify_marketing"
                               id="notif-product"
                               <?= ($candidate->notify_marketing ?? 0) ? 'checked' : '' ?>>
                        <span class="sl"></span>
                    </label>
                </div>

                <div style="display:flex; justify-content:flex-end; margin-top:4px;">
                    <button type="submit" class="btn btn-primary" id="set-notif-btn">
                        <span class="btn-text">Save preferences</span>
                        <span class="spinner d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </section>


        <!-- SECTION 3 · DANGER ZONE -->
        <section class="set-card danger-card" aria-labelledby="danger-heading">
            <h3 id="danger-heading">
                <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2">
                    <use href="#i-trash"/>
                </svg>
                Delete account
            </h3>
            <p class="set-sub">
                Permanently delete your account and all associated data — applications, saved jobs,
                alerts, resumes, certificates and wallet.
                <strong style="color:var(--danger);">This cannot be undone.</strong>
            </p>
            <div>
                <button type="button" class="btn btn-danger" id="open-delete">
                    <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;">
                        <use href="#i-trash"/>
                    </svg>
                    Delete my account
                </button>
            </div>
        </section>

    </div><!-- /.set-wrap -->

</div><!-- /.content -->


<!-- DELETE ACCOUNT MODAL -->
<div class="modal-overlay"
     id="delete-modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-title">

    <div class="modal" role="document">

        <!-- Icon -->
        <div class="m-ic" aria-hidden="true">
            <svg fill="none" stroke="currentColor" stroke-width="2">
                <use href="#i-trash"/>
            </svg>
        </div>

        <h3 id="modal-title">Delete your account?</h3>
        <p>
            This will permanently erase your account and all associated data.
            To confirm, type <strong>DELETE</strong> in the box below.
        </p>

        <form id="delete-account-form"
              method="post"
              action="<?= base_url('candidate/settings/delete-account') ?>"
              novalidate>
            <?= csrf_field() ?>

            <div class="m-confirm" style="margin-bottom: 15px;">
                <label for="delete-password-confirm">Confirm your password <span style="color:var(--danger)">*</span></label>
                <input type="password"
                       id="delete-password-confirm"
                       name="password"
                       required
                       placeholder="Enter your current password"
                       style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 9px; font-size: 15px; margin-bottom: 0;">
            </div>

            <div class="m-confirm">
                <label for="delete-confirm-input">Type <b>DELETE</b> to confirm</label>
                <input type="text"
                       id="delete-confirm-input"
                       name="confirm_phrase"
                       autocomplete="off"
                       placeholder="DELETE"
                       spellcheck="false"
                       style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 9px; font-size: 15px; margin-bottom: 0;">
            </div>

            <div class="modal-actions" style="margin-top: 20px;">
                <button type="button"
                        class="btn btn-outline"
                        id="cancel-delete"
                        style="flex:1;">
                    Cancel
                </button>
                <button type="submit"
                        class="btn btn-danger"
                        id="confirm-delete"
                        disabled
                        style="flex:1;">
                    Delete permanently
                </button>
            </div>
        </form>

    </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
requestAnimationFrame(function(){document.documentElement.classList.add('anim-ready')});
(function () {
    'use strict';

    /* ── 1. SHOW / HIDE PASSWORD TOGGLES ─────────────────────── */
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = btn.dataset.target;
            var input    = document.getElementById(targetId);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });


    /* ── 2. PASSWORD STRENGTH METER ───────────────────────────── */
    var newPwInput    = document.getElementById('set-new-pw');
    var strengthFill  = document.getElementById('set-strength-fill');
    var strengthLabel = document.getElementById('set-strength-label');

    var strengthLevels = [
        { label: '',            color: '',          width: '0%'   },
        { label: 'Weak',        color: '#ef4444',   width: '25%'  },
        { label: 'Fair',        color: '#f97316',   width: '50%'  },
        { label: 'Good',        color: '#eab308',   width: '75%'  },
        { label: 'Strong',      color: '#22c55e',   width: '100%' },
    ];

    function calcStrength(pw) {
        if (!pw) return 0;
        var score = 0;
        if (pw.length >= 8)                       score++;
        if (/[a-z]/.test(pw) && /[A-Z]/.test(pw)) score++;
        if (/\d/.test(pw))                         score++;
        if (/[^a-zA-Z0-9]/.test(pw))              score++;
        return score;                             // 0-4
    }

    if (newPwInput && strengthFill && strengthLabel) {
        newPwInput.addEventListener('input', function () {
            var score  = calcStrength(newPwInput.value);
            var level  = strengthLevels[score];
            strengthFill.style.width      = level.width;
            strengthFill.style.background = level.color;
            strengthLabel.textContent     = level.label
                ? 'Password strength: ' + level.label
                : '';
        });
    }


    /* ── 3. CONFIRM PASSWORD MATCH FEEDBACK ───────────────────── */
    var confirmPwInput = document.getElementById('set-confirm-pw');
    var matchHint      = document.getElementById('set-match-hint');

    if (confirmPwInput && matchHint && newPwInput) {
        confirmPwInput.addEventListener('input', function () {
            if (!confirmPwInput.value) {
                matchHint.textContent = '';
                matchHint.style.color = '';
                return;
            }
            if (confirmPwInput.value === newPwInput.value) {
                matchHint.textContent = '✓ Passwords match';
                matchHint.style.color = 'var(--success)';
            } else {
                matchHint.textContent = '✗ Passwords do not match';
                matchHint.style.color = 'var(--danger)';
            }
        });
    }


    /* ── 4. CHANGE PASSWORD FORM — AJAX SUBMIT ────────────────── */
    var pwForm = document.getElementById('set-pw-form');
    var pwBtn  = document.getElementById('set-pw-btn');

    if (pwForm && pwBtn) {
        pwForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Basic client-side guard
            if (newPwInput.value && confirmPwInput.value &&
                newPwInput.value !== confirmPwInput.value) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning('Passwords do not match.');
                }
                confirmPwInput.focus();
                return;
            }

            var btnText = pwBtn.querySelector('.btn-text');
            var spinner = pwBtn.querySelector('.spinner');
            pwBtn.disabled = true;
            if (btnText) btnText.classList.add('d-none');
            if (spinner) spinner.classList.remove('d-none');

            var data = new FormData(pwForm);

            fetch('<?= base_url('candidate/settings/password') ?>', {
                method : 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body   : data
            })
            .then(function (res) { return res.json(); })
            .then(function (res) {
                if (res && res.success) {
                    if (typeof toastr !== 'undefined') toastr.success(res.message || 'Password updated successfully.');
                    pwForm.reset();
                    // Reset strength bar
                    if (strengthFill)  { strengthFill.style.width = '0'; strengthFill.style.background = ''; }
                    if (strengthLabel) { strengthLabel.textContent = ''; }
                    if (matchHint)     { matchHint.textContent = ''; }
                } else {
                    if (typeof toastr !== 'undefined') toastr.error(res.message || 'Could not update password.');
                    if (res && res.errors) {
                        Object.values(res.errors).forEach(function (msg) {
                            if (typeof toastr !== 'undefined') toastr.warning(msg);
                        });
                    }
                }
            })
            .catch(function () {
                if (typeof toastr !== 'undefined') toastr.error('Network error. Please try again.');
            })
            .finally(function () {
                pwBtn.disabled = false;
                if (btnText) btnText.classList.remove('d-none');
                if (spinner) spinner.classList.add('d-none');
            });
        });
    }


    /* ── 5. NOTIFICATIONS FORM — AJAX SUBMIT ──────────────────── */
    var notifForm = document.getElementById('set-notif-form');
    var notifBtn  = document.getElementById('set-notif-btn');

    if (notifForm && notifBtn) {
        notifForm.addEventListener('submit', function (e) {
            e.preventDefault();

            var btnText = notifBtn.querySelector('.btn-text');
            var spinner = notifBtn.querySelector('.spinner');
            notifBtn.disabled = true;
            if (btnText) btnText.classList.add('d-none');
            if (spinner) spinner.classList.remove('d-none');

            // Build payload — send unchecked boxes explicitly as 0
            var payload = {};
            // Include CSRF token
            var csrfInput = notifForm.querySelector('input[name="<?= csrf_token() ?>"]');
            if (csrfInput) payload[csrfInput.name] = csrfInput.value;

            notifForm.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
                payload[cb.name] = cb.checked ? 1 : 0;
            });

            fetch('<?= base_url('candidate/settings/notifications') ?>', {
                method : 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type'    : 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(payload).toString()
            })
            .then(function (res) { return res.json(); })
            .then(function (res) {
                if (res && res.success) {
                    if (typeof toastr !== 'undefined') toastr.success(res.message || 'Preferences saved.');
                } else {
                    if (typeof toastr !== 'undefined') toastr.error((res && res.message) || 'Could not save preferences.');
                }
            })
            .catch(function () {
                if (typeof toastr !== 'undefined') toastr.error('Network error. Please try again.');
            })
            .finally(function () {
                notifBtn.disabled = false;
                if (btnText) btnText.classList.remove('d-none');
                if (spinner) spinner.classList.add('d-none');
            });
        });
    }


    /* ── 6. DELETE ACCOUNT MODAL ──────────────────────────────── */
    var overlay       = document.getElementById('delete-modal');
    var openBtn       = document.getElementById('open-delete');
    var cancelBtn     = document.getElementById('cancel-delete');
    var confirmInput  = document.getElementById('delete-confirm-input');
    var confirmBtn    = document.getElementById('confirm-delete');
    var deleteForm    = document.getElementById('delete-account-form');

    function openDeleteModal() {
        if (!overlay) return;
        overlay.classList.add('show');
        if (confirmInput) { confirmInput.value = ''; confirmInput.focus(); }
        if (confirmBtn)   { confirmBtn.disabled = true; }
    }

    function closeDeleteModal() {
        if (!overlay) return;
        overlay.classList.remove('show');
        if (confirmInput) confirmInput.value = '';
        if (confirmBtn)   confirmBtn.disabled = true;
    }

    if (openBtn)  openBtn.addEventListener('click', openDeleteModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeDeleteModal);

    // Close on backdrop click
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeDeleteModal();
        });
    }

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay && overlay.classList.contains('show')) {
            closeDeleteModal();
        }
    });

    // Unlock confirm button only when user types DELETE exactly
    if (confirmInput && confirmBtn) {
        confirmInput.addEventListener('input', function () {
            confirmBtn.disabled = confirmInput.value.trim().toUpperCase() !== 'DELETE';
        });
    }

    // Submit delete account form via AJAX
    if (deleteForm && confirmBtn) {
        deleteForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (confirmInput && confirmInput.value.trim().toUpperCase() !== 'DELETE') return;

            confirmBtn.disabled    = true;
            confirmBtn.textContent = 'Deleting…';

            var data = new FormData(deleteForm);

            fetch('<?= base_url('candidate/settings/delete-account') ?>', {
                method : 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body   : data
            })
            .then(function (res) { return res.json(); })
            .then(function (res) {
                if (res && res.success) {
                    if (typeof toastr !== 'undefined') toastr.success(res.message || 'Account deleted.');
                    setTimeout(function () {
                        window.location.href = res.redirect || '<?= base_url('/') ?>';
                    }, 1400);
                } else {
                    if (typeof toastr !== 'undefined') toastr.error((res && res.message) || 'Could not delete account.');
                    confirmBtn.disabled    = false;
                    confirmBtn.textContent = 'Delete permanently';
                }
            })
            .catch(function () {
                if (typeof toastr !== 'undefined') toastr.error('Network error. Please try again.');
                confirmBtn.disabled    = false;
                confirmBtn.textContent = 'Delete permanently';
            });
        });
    }

})();
</script>
<?= $this->endSection() ?>
