<!-- Native App Splash Screen -->
<div id="jr-splash-screen" class="jr-splash-screen">
    <div class="jr-splash-content">
        <img src="<?= base_url('images/pwa/icon-192.png'); ?>" alt="JobberRecruit Logo" class="jr-splash-logo">
        <div class="jr-splash-spinner mt-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>

<style>
/* Splash Screen Styles */
.jr-splash-screen {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--bg-white, #ffffff);
    z-index: 99999;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: opacity 0.4s ease-out, visibility 0.4s ease-out;
}

.jr-splash-content {
    text-align: center;
    animation: jr-pulse 2s infinite;
}

.jr-splash-logo {
    width: 100px;
    height: auto;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(13, 96, 158, 0.2);
}

@keyframes jr-pulse {
    0% { transform: scale(0.95); }
    50% { transform: scale(1.05); }
    100% { transform: scale(0.95); }
}

[data-theme="dark"] .jr-splash-screen {
    background-color: var(--bg-dark, #0f172a);
}

body.splash-hidden .jr-splash-screen {
    opacity: 0;
    visibility: hidden;
}
</style>

<script>
    // Hide splash screen when the window finishes loading
    window.addEventListener('load', function() {
        // Small delay to ensure smooth transition
        setTimeout(function() {
            document.body.classList.add('splash-hidden');
            // Remove from DOM after transition completes to free memory
            setTimeout(() => {
                const splash = document.getElementById('jr-splash-screen');
                if (splash) splash.remove();
            }, 400);
        }, 300);
    });
    
    // Fallback just in case load event fails or is very slow
    setTimeout(function() {
        if (!document.body.classList.contains('splash-hidden')) {
            document.body.classList.add('splash-hidden');
        }
    }, 3000);
</script>
