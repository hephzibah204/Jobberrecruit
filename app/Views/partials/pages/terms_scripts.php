
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

        const sections = document.querySelectorAll('.terms-section-card');

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

                }

            });

        }



        window.addEventListener('scroll', highlightCurrentSection);

        highlightCurrentSection(); // Initial call



        // Acceptance modal logic

        const agreeTermsCheck = document.getElementById('agreeTermsCheck');

        const acceptTermsBtn = document.getElementById('acceptTermsBtn');



        if (agreeTermsCheck && acceptTermsBtn) {

            agreeTermsCheck.addEventListener('change', function() {

                acceptTermsBtn.disabled = !this.checked;

            });

        }



        // Print terms function

        window.printTerms = function() {

            window.print();

        };



        // Download terms function (simulated)

        window.downloadTerms = function() {

            const btn = event.target.closest('button');

            const originalText = btn.innerHTML;



            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating PDF...';

            btn.disabled = true;



            // Simulate PDF generation

            setTimeout(() => {

                btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>PDF Downloaded';

                btn.classList.remove('btn-outline-danger');

                btn.classList.add('btn-success');



                setTimeout(() => {

                    btn.innerHTML = originalText;

                    btn.disabled = false;

                    btn.classList.remove('btn-success');

                    btn.classList.add('btn-outline-danger');



                    // Show success message

                    showAlert('success', 'Terms of Service PDF has been downloaded to your device.');

                }, 2000);

            }, 1500);

        };



        // Share terms function

        window.shareTerms = function() {

            if (navigator.share) {

                navigator.share({

                    title: 'JobberRecruit Terms of Service',

                    text: 'Read the Terms of Service for JobberRecruit.com',

                    url: window.location.href

                });

            } else {

                // Fallback for browsers that don't support Web Share API

                navigator.clipboard.writeText(window.location.href).then(() => {

                    showAlert('info', 'Link copied to clipboard!');

                });

            }

        };



        // Accept terms function

        window.acceptTerms = function() {

            // Store acceptance in localStorage

            localStorage.setItem('termsAccepted', 'true');

            localStorage.setItem('termsAcceptedDate', new Date().toISOString());

            localStorage.setItem('termsVersion', '2.0');



            // Close modal

            const modal = bootstrap.Modal.getInstance(document.getElementById('acceptanceModal'));

            modal.hide();



            // Show success message

            showAlert('success', 'Thank you for accepting our Terms of Service!');

        };



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



        // Check if terms were previously accepted

        function checkTermsAcceptance() {

            const accepted = localStorage.getItem('termsAccepted');

            const acceptedVersion = localStorage.getItem('termsVersion');

            const currentVersion = '2.0';



            if (!accepted || acceptedVersion !== currentVersion) {

                // Show modal if terms haven't been accepted or version changed

                setTimeout(() => {

                    const modal = new bootstrap.Modal(document.getElementById('acceptanceModal'));

                    modal.show();

                }, 3000);

            }

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



        // Initialize tooltips

        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

        tooltipTriggerList.map(function(tooltipTriggerEl) {

            return new bootstrap.Tooltip(tooltipTriggerEl);

        });



        // Check terms acceptance on page load

        checkTermsAcceptance();

    });



    // Auto-expand first accordion item

    document.addEventListener('DOMContentLoaded', function() {

        const firstAccordion = document.querySelector('.accordion-button');

        if (firstAccordion) {

            firstAccordion.click();

        }

    });

</script>
