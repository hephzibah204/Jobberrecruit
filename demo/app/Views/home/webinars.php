<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-primary display-5 mb-2">Career Webinars</h1>
            <p class="lead text-muted">Boost your career with expert-led sessions</p>
        </div>
    </div>

    <!-- Newsletter Subscription Banner -->
    <div class="card custom-card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-md-7 mb-3 mb-md-0">
                    <h5 class="text-white fw-bold mb-2">
                        <i class="ti ti-mail me-2"></i>Subscribe to Career Webinar Newsletter
                    </h5>
                    <p class="text-white-50 mb-0">Get notified about upcoming webinars, career tips, and exclusive events delivered straight to your inbox.</p>
                </div>
                <div class="col-md-5">
                    <form id="webinar-newsletter-form" class="d-flex gap-2">
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email address" required>
                        <button type="submit" class="btn btn-warning btn-lg text-dark fw-bold" id="btn-webinar-subscribe">
                            Subscribe
                        </button>
                    </form>
                    <div id="webinar-newsletter-msg" class="mt-2 small text-white"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if (empty($webinars)): ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="ti ti-video-off fs-64 text-muted"></i>
                </div>
                <h5>No upcoming webinars at the moment.</h5>
                <p class="text-muted">Check back later or subscribe to our newsletter to get notified.</p>
            </div>
        <?php else: ?>
            <?php foreach ($webinars as $webinar): ?>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card custom-card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="avatar avatar-lg bg-primary-transparent me-3">
                                    <i class="ti ti-video fs-24"></i>
                                </span>
                                <div>
                                    <h6 class="fw-bold mb-1"><?= esc($webinar->title) ?></h6>
                                    <p class="text-muted fs-12 mb-0">By <?= esc($webinar->speaker_name) ?></p>
                                </div>
                            </div>
                            <p class="text-muted fs-13 mb-4"><?= esc($webinar->description) ?></p>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="me-4">
                                    <p class="text-muted fs-11 mb-0 text-uppercase">Date & Time</p>
                                    <p class="fw-semibold fs-13 mb-0"><?= date('M d, Y h:i A', strtotime($webinar->scheduled_at)) ?></p>
                                </div>
                                <span class="badge bg-<?= $webinar->status === 'upcoming' ? 'info' : 'success' ?>-transparent">
                                    <?= ucfirst($webinar->status) ?>
                                </span>
                            </div>

                            <div class="d-grid">
                                <?php if (auth()->loggedIn()): ?>
                                    <button class="btn btn-primary btn-register" data-id="<?= $webinar->id ?>">
                                        <i class="ti ti-user-plus me-1"></i> Register Now
                                    </button>
                                <?php else: ?>
                                    <a href="<?= base_url('login') ?>" class="btn btn-outline-primary">
                                        Login to Register
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Webinar registration
    const btns = document.querySelectorAll('.btn-register');
    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            const webinarId = this.getAttribute('data-id');
            const originalHtml = this.innerHTML;
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            fetch('<?= base_url('webinars/register/') ?>' + webinarId, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 201 || !data.error) {
                    toastr.success(data.message || 'Successfully registered!');
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    this.innerHTML = '<i class="ti ti-check me-1"></i> Registered';
                } else {
                    toastr.error(data.messages ? data.messages.error : (data.message || 'An error occurred'));
                    this.disabled = false;
                    this.innerHTML = originalHtml;
                }
            })
            .catch(error => {
                toastr.error('An error occurred. Please try again.');
                this.disabled = false;
                this.innerHTML = originalHtml;
            });
        });
    });

    // Webinar newsletter subscription
    const webinarForm = document.getElementById('webinar-newsletter-form');
    if (webinarForm) {
        webinarForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-webinar-subscribe');
            const msg = document.getElementById('webinar-newsletter-msg');
            const formData = new FormData(webinarForm);
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            fetch('<?= base_url('newsletter/subscribe') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 201 || data.status === 200 || !data.error) {
                    msg.className = 'mt-2 small text-success';
                    msg.innerHTML = '<i class="ti ti-check-circle me-1"></i>' + (data.message || 'Successfully subscribed!');
                    webinarForm.reset();
                } else {
                    msg.className = 'mt-2 small text-warning';
                    msg.innerHTML = data.messages ? data.messages.error : (data.message || 'An error occurred');
                }
            })
            .catch(error => {
                msg.className = 'mt-2 small text-warning';
                msg.innerHTML = 'An error occurred. Please try again.';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Subscribe';
            });
        });
    }
});
</script>
<?= $this->endSection() ?>
