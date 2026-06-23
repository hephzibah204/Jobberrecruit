<div id="cookie-consent-banner" class="position-fixed bottom-0 start-0 end-0 p-3 d-none" style="z-index: 9999; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(12px); border-top: 1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <p class="mb-0 text-muted small">
                We use cookies to enhance your experience. By continuing to use JobberRecruit, you agree to our
                <a href="<?= base_url('privacy-policy') ?>" class="text-primary">Privacy Policy</a>.
            </p>
            <div class="d-flex gap-2 flex-shrink-0">
                <button id="cookie-accept" class="btn btn-primary btn-sm px-4">Accept</button>
                <button id="cookie-decline" class="btn btn-outline-secondary btn-sm px-3">Decline</button>
            </div>
        </div>
    </div>
    <script>
    (function() {
        var banner = document.getElementById('cookie-consent-banner');
        if (!localStorage.getItem('cookie_consent')) {
            banner.classList.remove('d-none');
        }
        document.getElementById('cookie-accept').addEventListener('click', function() {
            localStorage.setItem('cookie_consent', 'accepted');
            banner.classList.add('d-none');
        });
        document.getElementById('cookie-decline').addEventListener('click', function() {
            localStorage.setItem('cookie_consent', 'declined');
            banner.classList.add('d-none');
        });
    })();
    </script>
</div>
