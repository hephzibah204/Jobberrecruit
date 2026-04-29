<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Career Webinars</h4>
                <h6>Boost your career with expert-led sessions</h6>
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
});
</script>
<?= $this->endSection() ?>
