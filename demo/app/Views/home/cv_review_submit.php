<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>
<style>
    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 48px 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #f8fafc;
    }
    .upload-area:hover, .upload-area.dragover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .upload-area i { font-size: 3rem; color: #3b82f6; margin-bottom: 16px; }
    .upload-area h5 { font-weight: 600; margin-bottom: 8px; }
    .upload-area p { color: #64748b; margin-bottom: 0; font-size: 0.9rem; }
    .upload-area .formats { color: #94a3b8; font-size: 0.8rem; margin-top: 8px; }
    .file-preview {
        display: none;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 16px;
    }
    .file-preview.show { display: flex; align-items: center; gap: 14px; }
    .file-preview i { font-size: 2rem; color: #22c55e; }
    .file-preview .file-name { font-weight: 600; font-size: 0.95rem; }
    .file-preview .file-size { font-size: 0.8rem; color: #64748b; }
    .step-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: #fff;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    @media (max-width: 768px) {
        .upload-area { padding: 32px 16px; }
    }
</style>

<section class="section-box mt-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span class="badge bg-primary-transparent text-primary px-3 py-2 mb-3">
                        <i class="ti ti-upload me-1"></i> Submit Your CV
                    </span>
                    <h1 class="fw-bold mb-2">Upload Your CV for Professional Review</h1>
                    <p class="text-muted" style="max-width:600px;margin:0 auto;">
                        Fill in your details, select a review package, and our experts will get back to you with actionable feedback.
                    </p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="ti ti-circle-x me-2"></i><?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="ti ti-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <form id="cv-review-form" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="review_id" id="review_id" value="<?= $reviewId ?? '' ?>">

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="step-badge">1</span>
                                <h5 class="fw-bold mb-0">Select Your Package</h5>
                            </div>
                            <div class="mb-4">
                                <select name="plan" id="plan-select" class="form-select form-select-lg">
                                    <option value="basic" <?= ($preselectedPlan ?? '') === 'basic' ? 'selected' : '' ?>>Basic Review (Free)</option>
                                    <option value="professional" <?= ($preselectedPlan ?? '') === 'professional' ? 'selected' : '' ?>>Professional Review (&#8358;<?= number_format($planPrices['professional'], 0) ?>)</option>
                                    <option value="premium" <?= ($preselectedPlan ?? '') === 'premium' ? 'selected' : '' ?>>Premium Review (&#8358;<?= number_format($planPrices['premium'], 0) ?>)</option>
                                </select>
                                <?php if ($reviewId): ?>
                                <div class="mt-2 small text-success">
                                    <i class="ti ti-check-circle me-1"></i>Payment completed! Please upload your CV.
                                </div>
                                <?php endif; ?>
                                <div id="payment-status" class="mt-2 small" style="display:none;"></div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="step-badge">2</span>
                                <h5 class="fw-bold mb-0">Your Information</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control form-control-lg" placeholder="e.g. John Doe" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg" placeholder="e.g. +234 800 000 0000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Target Industry <span class="text-danger">*</span></label>
                                    <select name="industry" class="form-select form-select-lg" required>
                                        <option value="">Select Industry</option>
                                        <option value="technology">Technology</option>
                                        <option value="finance">Finance & Banking</option>
                                        <option value="healthcare">Healthcare</option>
                                        <option value="education">Education</option>
                                        <option value="marketing">Marketing & Media</option>
                                        <option value="sales">Sales & Retail</option>
                                        <option value="government">Government & Public Sector</option>
                                        <option value="consulting">Consulting</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Target Role / Job Title</label>
                                    <input type="text" name="target_role" class="form-control form-control-lg" placeholder="e.g. Senior Product Manager">
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="step-badge">3</span>
                                <h5 class="fw-bold mb-0">Upload Your CV</h5>
                            </div>

                            <div class="mb-4">
                                <div class="upload-area" id="upload-area">
                                    <i class="ti ti-cloud-upload"></i>
                                    <h5>Drag & drop your CV here</h5>
                                    <p>or click to browse files</p>
                                    <div class="formats">Supported formats: PDF, DOC, DOCX (Max 5MB)</div>
                                    <input type="file" name="cv_file" id="cv_file" class="d-none" accept=".pdf,.doc,.docx" required>
                                </div>
                                <div id="file-error" class="text-danger mt-2 small" style="display: none;"></div>
                                <div class="file-preview" id="file-preview">
                                    <i class="ti ti-file-text"></i>
                                    <div>
                                        <div class="file-name" id="file-name"></div>
                                        <div class="file-size" id="file-size"></div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="remove-file">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">What specific feedback are you looking for?</label>
                                <textarea name="feedback_request" class="form-control" rows="4" placeholder="e.g. I'm applying for senior product manager roles in fintech. Please focus on my achievements section, ATS optimization, and overall impact..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3" id="btn-submit-cv">
                                <i class="ti ti-send me-2"></i>Submit for Review
                            </button>
                        </form>
                        <div id="cv-upload-msg" class="mt-4" style="display: none;"></div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="<?= base_url('cv-review') ?>" class="text-muted">
                        <i class="ti ti-arrow-left me-1"></i>Back to CV Review overview
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('cv-review-form');
    const fileInput = document.getElementById('cv_file');
    const uploadArea = document.getElementById('upload-area');
    const fileError = document.getElementById('file-error');
    const submitBtn = document.getElementById('btn-submit-cv');
    const msgDiv = document.getElementById('cv-upload-msg');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');

    uploadArea.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect();
        }
    });

    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (!file) return;
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            fileError.textContent = 'Only PDF, DOC, and DOCX files are allowed';
            fileError.style.display = 'block';
            fileInput.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            fileError.textContent = 'File size must be less than 5MB';
            fileError.style.display = 'block';
            fileInput.value = '';
            return;
        }
        fileError.style.display = 'none';
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        filePreview.classList.add('show');
    }

    removeFileBtn.addEventListener('click', () => {
        fileInput.value = '';
        filePreview.classList.remove('show');
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        fileError.style.display = 'none';
        msgDiv.style.display = 'none';

        const plan = document.getElementById('plan-select').value;

        if (plan !== 'basic' && !document.getElementById('review_id').value) {
            fileError.textContent = 'Please complete payment for the ' + plan + ' plan first by selecting the plan and completing payment.';
            fileError.style.display = 'block';
            return;
        }

        const file = fileInput.files[0];
        if (!file) {
            fileError.textContent = 'Please select a CV file to upload';
            fileError.style.display = 'block';
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

        const formData = new FormData(form);

        fetch('<?= base_url('cv-review/upload') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                msgDiv.className = 'alert alert-success mt-4';
                msgDiv.innerHTML = '<i class="ti ti-check-circle me-2"></i>' + (data.message || 'CV uploaded successfully! We\'ll review it shortly.');
                msgDiv.style.display = 'block';
                form.reset();
                filePreview.classList.remove('show');
                document.getElementById('review_id').value = '';
                setTimeout(() => { window.location.href = '<?= base_url('cv-review') ?>'; }, 3000);
            } else {
                fileError.textContent = data.message || 'Upload failed. Please try again.';
                fileError.style.display = 'block';
            }
        })
        .catch(error => {
            fileError.textContent = 'An error occurred. Please try again.';
            fileError.style.display = 'block';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-send me-2"></i>Submit for Review';
        });
    });

    let currentReviewId = <?= isset($reviewId) && $reviewId ? (int)$reviewId : 'null' ?>;
    if (currentReviewId) {
        document.getElementById('review_id').value = currentReviewId;
        document.getElementById('payment-status').style.display = 'block';
        document.getElementById('payment-status').className = 'mt-2 small text-success';
        document.getElementById('payment-status').innerHTML = '<i class="ti ti-check-circle me-1"></i>Payment completed! Please upload your CV.';
    }

    const planPrices = <?= json_encode($planPrices) ?>;
    document.querySelectorAll('.choose-plan').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const plan = this.dataset.plan;
            const price = planPrices[plan] || 0;
            document.getElementById('plan-select').value = plan;

            if (plan === 'basic') {
                return;
            }

            const btnEl = this;
            btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Redirecting...';
            btnEl.style.pointerEvents = 'none';

            fetch('<?= base_url('cv-review/pay') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: 'plan=' + plan + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
            })
            .then(res => res.json())
            .then(data => {
                if (data.authorization_url) {
                    window.location.href = data.authorization_url;
                } else {
                    toastr.error(data.message || 'Payment initiation failed');
                    btnEl.innerHTML = '<i class="ti ti-crown me-1"></i>Pay &#8358;' + parseInt(price).toLocaleString();
                    btnEl.style.pointerEvents = 'auto';
                }
            })
            .catch(() => {
                toastr.error('Connection error. Please try again.');
                btnEl.innerHTML = '<i class="ti ti-crown me-1"></i>Pay &#8358;' + parseInt(price).toLocaleString();
                btnEl.style.pointerEvents = 'auto';
            });
        });
    });
});
</script>
<?= $this->endSection() ?>
