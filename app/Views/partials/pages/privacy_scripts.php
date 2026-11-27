
<script>

    document.addEventListener('DOMContentLoaded', function() {

        // Smooth scroll for anchor links

        document.querySelectorAll('.scroll-to').forEach(link => {

            link.addEventListener('click', function(e) {

                e.preventDefault();

                const targetId = this.getAttribute('href');

                if (targetId.startsWith('#')) {

                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {

                        window.scrollTo({

                            top: targetElement.offsetTop - 100,

                            behavior: 'smooth'

                        });

                    }

                }

            });

        });



        // Highlight current section in sidebar

        const sections = document.querySelectorAll('.policy-section');

        const navLinks = document.querySelectorAll('.nav-link.scroll-to');



        function highlightCurrentSection() {

            let current = '';



            sections.forEach(section => {

                const sectionTop = section.offsetTop;

                const sectionHeight = section.clientHeight;



                if (window.scrollY >= (sectionTop - 200)) {

                    current = section.getAttribute('id');

                }

            });



            navLinks.forEach(link => {

                link.classList.remove('active');

                if (link.getAttribute('href') === `#${current}`) {

                    link.classList.add('active');

                    link.style.borderLeftColor = 'var(--brand)';

                    link.style.backgroundColor = 'rgba(102, 126, 234, 0.05)';

                }

            });

        }



        window.addEventListener('scroll', highlightCurrentSection);

        highlightCurrentSection(); // Initial call



        // Print policy function

        window.printPolicy = function() {

            window.print();

        };



        // Download policy function (simulated)

        window.downloadPolicy = function() {

            const btn = event.target.closest('button');

            const originalText = btn.innerHTML;



            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating PDF...';

            btn.disabled = true;



            // Simulate PDF generation

            setTimeout(() => {

                btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>PDF Downloaded';

                btn.classList.remove('btn-primary');

                btn.classList.add('btn-success');



                setTimeout(() => {

                    btn.innerHTML = originalText;

                    btn.disabled = false;

                    btn.classList.remove('btn-success');

                    btn.classList.add('btn-primary');



                    // Show success message

                    showAlert('success', 'Privacy policy PDF has been downloaded to your device.');

                }, 2000);

            }, 1500);

        };



        // Cookie preferences

        function saveCookiePreferences() {

            const analytics = document.getElementById('analyticsCookies').checked;

            const marketing = document.getElementById('marketingCookies').checked;



            // Save to localStorage

            localStorage.setItem('cookiePreferences', JSON.stringify({

                analytics: analytics,

                marketing: marketing,

                timestamp: new Date().toISOString()

            }));



            // Close modal

            const modal = bootstrap.Modal.getInstance(document.getElementById('cookieModal'));

            modal.hide();



            // Show success message

            showAlert('success', 'Your cookie preferences have been saved.');

        }



        // Load saved cookie preferences

        function loadCookiePreferences() {

            const saved = localStorage.getItem('cookiePreferences');

            if (saved) {

                const preferences = JSON.parse(saved);

                document.getElementById('analyticsCookies').checked = preferences.analytics;

                document.getElementById('marketingCookies').checked = preferences.marketing;

            }

        }



        // Show alert function

        function showAlert(type, message) {

            const alertDiv = document.createElement('div');

            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed bottom-0 end-0 m-4`;

            alertDiv.style.zIndex = '1050';

            alertDiv.style.maxWidth = '350px';

            alertDiv.innerHTML = `

            ${message}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        `;

            document.body.appendChild(alertDiv);



            setTimeout(() => {

                alertDiv.remove();

            }, 5000);

        }



        // Load preferences on page load

        loadCookiePreferences();



        // Auto-open modal if no preferences set

        if (!localStorage.getItem('cookiePreferences')) {

            setTimeout(() => {

                const modal = new bootstrap.Modal(document.getElementById('cookieModal'));

                modal.show();

            }, 2000);

        }



        // Back to top button

        const backToTop = document.createElement('button');

        backToTop.className = 'btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4';

        backToTop.style.zIndex = '1040';

        backToTop.style.width = '50px';

        backToTop.style.height = '50px';

        backToTop.innerHTML = '<i class="bi bi-arrow-up"></i>';

        backToTop.title = 'Back to top';

        backToTop.onclick = () => window.scrollTo({

            top: 0,

            behavior: 'smooth'

        });

        document.body.appendChild(backToTop);



        // Show/hide back to top button

        window.addEventListener('scroll', () => {

            if (window.scrollY > 300) {

                backToTop.style.display = 'flex';

                backToTop.style.alignItems = 'center';

                backToTop.style.justifyContent = 'center';

            } else {

                backToTop.style.display = 'none';

            }

        });

    });



    // Share policy function

    function sharePolicy() {

        if (navigator.share) {

            navigator.share({

                title: 'JobberRecruit Privacy Policy',

                text: 'Read our privacy policy to understand how we protect your data.',

                url: window.location.href

            });

        } else {

            // Fallback for browsers that don't support Web Share API

            navigator.clipboard.writeText(window.location.href).then(() => {

                toastr.success('Link copied to clipboard!');

            });

        }

    }

</script>
