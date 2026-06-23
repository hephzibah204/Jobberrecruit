(function() {
    function initThemeToggle() {
        const themeToggleBtns = document.querySelectorAll('.theme-toggle');
        
        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-theme-mode', theme);
            try {
                localStorage.setItem('jr-theme', theme);
            } catch (e) {}
            
            // Update icons
            themeToggleBtns.forEach(btn => {
                const icon = btn.querySelector('i');
                const svg = btn.querySelector('svg');
                if (icon) {
                    if (theme === 'dark') {
                        if (icon.classList.contains('ti-moon') || icon.classList.contains('ti-sun')) {
                            icon.classList.remove('ti-moon');
                            icon.classList.add('ti-sun');
                        }
                        if (icon.classList.contains('bi-moon') || icon.classList.contains('bi-sun')) {
                            icon.classList.remove('bi-moon');
                            icon.classList.add('bi-sun');
                        }
                    } else {
                        if (icon.classList.contains('ti-moon') || icon.classList.contains('ti-sun')) {
                            icon.classList.remove('ti-sun');
                            icon.classList.add('ti-moon');
                        }
                        if (icon.classList.contains('bi-moon') || icon.classList.contains('bi-sun')) {
                            icon.classList.remove('bi-sun');
                            icon.classList.add('bi-moon');
                        }
                    }
                }
                if (svg) {
                    if (theme === 'dark') {
                        // Sun icon
                        svg.innerHTML = '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>';
                    } else {
                        // Moon icon
                        svg.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>';
                    }
                }
            });
        }

        themeToggleBtns.forEach(btn => {
            // Remove any existing listeners by cloning and replacing (optional, but good for safety)
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const currentTheme = document.documentElement.getAttribute('data-theme');
                setTheme(currentTheme === 'dark' ? 'light' : 'dark');
            });
        });

        // Ensure icon matches current theme on load
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        setTheme(currentTheme);
    }

    // Run immediately if DOM is already parsed, otherwise wait for DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initThemeToggle);
    } else {
        initThemeToggle();
    }
})();
