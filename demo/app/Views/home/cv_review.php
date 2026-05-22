<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>
<section class="page-hero">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-8">
                <span class="eyebrow">Professional Services</span>
                <h1>Get Your CV Reviewed by Industry Experts</h1>
                <p class="hero-lead">Upload your CV and receive detailed feedback from professional recruiters to improve your chances of landing interviews.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-60">
    <div class="container">
        <div class="row g-4">
            <!-- Upload Form -->
            <div class="col-lg-7">
                <div class="content-card">
                    <h2>Upload Your CV</h2>
                    <p class="text-muted mb-4">Our expert reviewers will analyze your CV and provide actionable feedback within 48 hours.</p>

                    <form id="cv-review-form" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">CV File <span class="text-danger">*</span></label>
                            <input type="file" name="cv_file" id="cv_file" class="form-control" accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Accepted formats: PDF, DOC, DOCX (Max 5MB)</small>
                            <div id="file-error" class="text-danger mt-2 small" style="display: none;"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">What would you like feedback on?</label>
                            <textarea name="feedback_request" class="form-control" rows="4" placeholder="e.g., I'm applying for software engineering roles. Please focus on my technical skills section and overall formatting..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg" id="btn-submit-cv">
                            <i class="ti ti-upload me-2"></i>Submit CV for Review
                        </button>
                    </form>

                    <div id="cv-upload-msg" class="mt-4" style="display: none;"></div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-5">
                <div class="content-card bg-light">
                    <h3>How It Works</h3>
                    <div class="d-flex mb-3">
                        <div class="avatar bg-primary-transparent me-3">
                            <i class="ti ti-upload text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">1. Upload Your CV</h6>
                            <p class="text-muted small mb-0">Submit your CV in PDF, DOC, or DOCX format.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="avatar bg-success-transparent me-3">
                            <i class="ti ti-search text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">2. Expert Review</h6>
                            <p class="text-muted small mb-0">Our professional recruiters review your CV thoroughly.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="avatar bg-info-transparent me-3">
                            <i class="ti ti-mail text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">3. Receive Feedback</h6>
                            <p class="text-muted small mb-0">Get detailed feedback via email within 48 hours.</p>
                        </div>
                    </div>
                </div>

                <div class="content-card mt-4">
                    <h4>What We Review</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>CV structure and formatting</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Professional summary</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Work experience descriptions</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Skills presentation</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>ATS compatibility</li>
                        <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Overall impact and clarity</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-box mt-60 mb-60 bg-light">
    <div class="container">
        <div class="text-center">
            <h2>Other Training Resources</h2>
            <p class="text-muted">Explore our complete learning marketplace</p>
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="content-card h-100">
                        <i class="ti ti-book display-4 text-primary mb-3"></i>
                        <h5>Online Courses</h5>
                        <p class="text-muted">Job-ready training and career development courses.</p>
                        <a href="<?= base_url('training') ?>" class="btn btn-outline-primary">Browse Courses</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="content-card h-100">
                        <i class="ti ti-video display-4 text-success mb-3"></i>
                        <h5>Career Webinars</h5>
                        <p class="text-muted">Live sessions with industry experts and recruiters.</p>
                        <a href="<?= base_url('webinars') ?>" class="btn btn-outline-success">View Webinars</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="content-card h-100">
                        <i class="ti ti-file-download display-4 text-info mb-3"></i>
                        <h5>Downloadable Guides</h5>
                        <p class="text-muted">Free PDF guides, templates, and career resources.</p>
                        <a href="<?= base_url('training') ?>" class="btn btn-outline-info">Get Resources</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('cv-review-form');
    const fileInput = document.getElementById('cv_file');
    const fileError = document.getElementById('file-error');
    const submitBtn = document.getElementById('btn-submit-cv');
    const msgDiv = document.getElementById('cv-upload-msg');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        fileError.style.display = 'none';
        msgDiv.style.display = 'none';

        const file = fileInput.files[0];
        if (!file) {
            fileError.textContent = 'Please select a CV file to upload';
            fileError.style.display = 'block';
            return;
        }

        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            fileError.textContent = 'Only PDF, DOC, and DOCX files are allowed';
            fileError.style.display = 'block';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            fileError.textContent = 'File size must be less than 5MB';
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
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.status === 200) {
                msgDiv.className = 'alert alert-success mt-4';
                msgDiv.innerHTML = '<i class="ti ti-check-circle me-2"></i>' + (data.message || 'CV uploaded successfully!');
                msgDiv.style.display = 'block';
                form.reset();
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
            submitBtn.innerHTML = '<i class="ti ti-upload me-2"></i>Submit CV for Review';
        });
    });
});
</script>
<?= $this->endSection() ?>
