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
