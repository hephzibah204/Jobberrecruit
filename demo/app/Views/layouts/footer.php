<footer class="footer mt-auto border-top bg-white">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <img src="<?= base_url('assets/imgs/template/logo.png'); ?>" alt="Logo" class="mb-3" style="max-height: 40px;">
                <p class="text-muted">JobberRecruit is your ultimate destination for career growth and recruitment excellence. We connect top talent with the best employers.</p>
                <div class="social-links mt-3">
                    <a href="https://facebook.com/jobberrecruit" target="_blank" rel="noopener" class="me-2 text-primary"><i class="ti ti-brand-facebook fs-20"></i></a>
                    <a href="https://twitter.com/jobberrecruit" target="_blank" rel="noopener" class="me-2 text-primary"><i class="ti ti-brand-twitter fs-20"></i></a>
                    <a href="https://linkedin.com/company/jobberrecruit" target="_blank" rel="noopener" class="me-2 text-primary"><i class="ti ti-brand-linkedin fs-20"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= base_url('jobs') ?>" class="text-muted">Browse Jobs</a></li>
                    <li><a href="<?= base_url('jobs') ?>" class="text-muted">Browse by Location</a></li>
                    <li><a href="<?= base_url('jobs') ?>" class="text-muted">Browse by Category</a></li>
                    <li><a href="<?= base_url('about-us') ?>" class="text-muted">About Us</a></li>
                    <li><a href="<?= base_url('contact-us') ?>" class="text-muted">Contact</a></li>
                    <li><a href="<?= base_url('faq') ?>" class="text-muted">FAQ</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h6 class="fw-bold mb-3">Resources</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= base_url('blog') ?>" class="text-muted">Career Blog</a></li>
                    <li><a href="<?= base_url('privacy-policy') ?>" class="text-muted">Privacy Policy</a></li>
                    <li><a href="<?= base_url('terms-and-conditions') ?>" class="text-muted">Terms of Service</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-4">
                <h6 class="fw-bold mb-3">Subscribe to Newsletter</h6>
                <p class="text-muted small">Stay updated with the latest job opportunities and career advice.</p>
                <form id="newsletter-form" class="mt-3">
                    <div class="input-group">
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        <button class="btn btn-primary" type="submit" id="btn-subscribe">
                            <i class="ti ti-send"></i>
                        </button>
                    </div>
                </form>
                <div id="newsletter-msg" class="mt-2 small"></div>
            </div>
        </div>
    </div>
    <div class="border-top p-3 bg-light">
        <div class="container d-sm-flex align-items-center justify-content-between">
            <p class="mb-0 text-muted">&copy; <?= date('Y') ?> JobberRecruit. All Rights Reserved.</p>
            <p class="mb-0">Powered by <a href="https://bitbiz.ng" class="text-primary fw-semibold">BITBIZ</a></p>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-subscribe');
            const msg = document.getElementById('newsletter-msg');
            const formData = new FormData(form);
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            fetch('<?= base_url('newsletter/subscribe') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 201 || data.status === 200 || !data.error) {
                    msg.className = 'mt-2 small text-success';
                    msg.innerHTML = data.message || 'Successfully subscribed!';
                    form.reset();
                } else {
                    msg.className = 'mt-2 small text-danger';
                    msg.innerHTML = data.messages ? data.messages.error : (data.message || 'An error occurred');
                }
            })
            .catch(error => {
                msg.className = 'mt-2 small text-danger';
                msg.innerHTML = 'An error occurred. Please try again.';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-send"></i>';
            });
        });
    }
});
</script>