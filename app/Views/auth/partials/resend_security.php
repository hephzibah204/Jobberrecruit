<?php if (env('recaptcha_site_key')): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= esc(env('recaptcha_site_key'), 'attr') ?>"></script>
<?php endif; ?>
<script>
window.requestVerificationResend = function() {
    const key = <?= json_encode((string) env('recaptcha_site_key'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    return new Promise((resolve, reject) => {
        if (!key || typeof grecaptcha === 'undefined') return reject(new Error('Security verification is unavailable. Please refresh and try again.'));
        grecaptcha.ready(() => {
            grecaptcha.execute(key, {action: 'resend_verification'}).then(token => {
                const body = new URLSearchParams();
                body.set('g-recaptcha-response', token);
                body.set(<?= json_encode(csrf_token()) ?>, <?= json_encode(csrf_hash()) ?>);
                fetch(<?= json_encode(base_url('auth/resend-verification')) ?>, {
                    method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body
                }).then(resolve, reject);
            }).catch(reject);
        });
    });
};
</script>
