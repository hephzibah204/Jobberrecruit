<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .builder-step-nav {
        border-right: 1px solid #e9ecef;
    }
    .step-item {
        padding: 12px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    .step-item:hover {
        background-color: #f8f9fa;
    }
    .step-item.active {
        background-color: #0d6efd;
        color: white;
    }
    .step-item i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    .ai-assist-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: transform 0.2s;
    }
    .ai-assist-btn:hover {
        transform: scale(1.05);
        color: white;
    }
    .ai-assist-btn i {
        margin-right: 5px;
    }
    .preview-card {
        position: sticky;
        top: 20px;
        background: white;
        border: 1px solid #dee2e6;
        height: calc(100vh - 150px);
        overflow-y: auto;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">AI Resume Builder</h4>
            <h6>Design your professional resume with AI assistance</h6>
        </div>
        <div class="page-btn">
            <button id="save-resume-btn" class="btn btn-primary">
                <i class="ti ti-device-floppy me-1"></i>Save Resume
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Navigation Sidebar -->
        <div class="col-xl-3 col-lg-4">
            <div class="card custom-card">
                <div class="card-body p-2">
                    <div class="builder-step-nav">
                        <div class="step-item active" data-step="info">
                            <i class="ti ti-user"></i> Basic Information
                        </div>
                        <div class="step-item" data-step="summary">
                            <i class="ti ti-file-description"></i> Professional Summary
                        </div>
                        <div class="step-item" data-step="experience">
                            <i class="ti ti-briefcase"></i> Work Experience
                        </div>
                        <div class="step-item" data-step="education">
                            <i class="ti ti-school"></i> Education
                        </div>
                        <div class="step-item" data-step="skills">
                            <i class="ti ti-tool"></i> Skills
                        </div>
                        <div class="step-item" data-step="templates">
                            <i class="ti ti-layout-template"></i> Choose Template
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Builder Form -->
        <div class="col-xl-9 col-lg-8">
            <form id="resume-form">
                <input type="hidden" name="id" value="<?= $resume->id ?? '' ?>">
                
                <!-- Step: Basic Information -->
                <div class="step-content" id="step-info">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Resume Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="<?= esc($resume->title ?? 'My Professional Resume') ?>" placeholder="e.g. Senior Software Engineer Resume">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" value="<?= esc(auth()->user()->username ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="<?= esc(auth()->user()->email ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step: Summary -->
                <div class="step-content d-none" id="step-summary">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Professional Summary</h5>
                            <button type="button" class="ai-assist-btn" id="generate-summary-ai">
                                <i class="ti ti-sparkles"></i> Generate with AI
                            </button>
                        </div>
                        <div class="card-body">
                            <textarea name="summary" id="resume-summary" class="form-control" rows="6" placeholder="A brief overview of your professional background and key achievements..."><?= esc($resume->summary ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step: Experience -->
                <div class="step-content d-none" id="step-experience">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Work Experience</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary add-experience">
                                <i class="ti ti-plus"></i> Add Experience
                            </button>
                        </div>
                        <div class="card-body" id="experience-container">
                            <!-- Experience Items will be added here -->
                            <div class="text-center py-4 text-muted no-items">
                                <p>No experience added yet. Click "Add Experience" to start.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step: Education -->
                <div class="step-content d-none" id="step-education">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Education</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary add-education">
                                <i class="ti ti-plus"></i> Add Education
                            </button>
                        </div>
                        <div class="card-body" id="education-container">
                            <div class="text-center py-4 text-muted no-items">
                                <p>No education added yet. Click "Add Education" to start.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step: Skills -->
                <div class="step-content d-none" id="step-skills">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Skills</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Add Skills (Comma separated)</label>
                                <input type="text" name="skills" class="form-control tags-input" placeholder="e.g. PHP, JavaScript, Project Management">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step: Templates -->
                <div class="step-content d-none" id="step-templates">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Choose Resume Template</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="template-choice border rounded p-2 text-center active" data-template="classic">
                                        <div class="bg-light mb-2 rounded" style="height: 150px;">Classic</div>
                                        <p class="mb-0">Classic Professional</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="template-choice border rounded p-2 text-center" data-template="modern">
                                        <div class="bg-light mb-2 rounded" style="height: 150px;">Modern</div>
                                        <p class="mb-0">Modern Creative</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AI Modal Loader -->
<div class="modal fade" id="aiLoaderModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-5 border-0 bg-transparent">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
            <h4 class="text-white fw-bold">Gemini is writing...</h4>
            <p class="text-white-50">Crafting your professional content with AI brilliance.</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Step Navigation
        $('.step-item').on('click', function() {
            const step = $(this).data('step');
            $('.step-item').removeClass('active');
            $(this).addClass('active');
            $('.step-content').addClass('d-none');
            $('#step-' + step).removeClass('d-none');
        });

        // AI Summary Generation
        $('#generate-summary-ai').on('click', function() {
            const experiences = []; // Get from form
            const skills = []; // Get from form

            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/generate-summary") ?>',
                type: 'POST',
                data: {
                    experiences: experiences,
                    skills: skills,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.summary) {
                        $('#resume-summary').val(response.summary);
                        toastr.success('Professional summary generated!');
                    }
                    $('#aiLoaderModal').modal('hide');
                },
                error: function() {
                    toastr.error('AI generation failed. Please try again.');
                    $('#aiLoaderModal').modal('hide');
                }
            });
        });

        // Add Experience Item (Dynamic)
        $('.add-experience').on('click', function() {
            $('#experience-container .no-items').hide();
            const html = `
                <div class="experience-item border rounded p-3 mb-3 bg-light-transparent">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <input type="text" name="exp_company[]" class="form-control form-control-sm" placeholder="Company Name">
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="text" name="exp_position[]" class="form-control form-control-sm" placeholder="Job Position">
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="small text-muted">Description</label>
                                <button type="button" class="ai-assist-btn improve-desc-ai">
                                    <i class="ti ti-wand"></i> Improve with AI
                                </button>
                            </div>
                            <textarea name="exp_description[]" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            `;
            $('#experience-container').append(html);
        });

        // Add Education Item (Dynamic)
        $('.add-education').on('click', function() {
            $('#education-container .no-items').hide();
            const html = `
                <div class="education-item border rounded p-3 mb-3 bg-light-transparent">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <input type="text" name="edu_school[]" class="form-control form-control-sm" placeholder="School / University">
                        </div>
                        <div class="col-md-6 mb-2">
                            <select name="edu_degree[]" class="form-select form-select-sm">
                                <option value="">Select Degree</option>
                                <option value="High School">High School</option>
                                <option value="Associate">Associate Degree</option>
                                <option value="Bachelor">Bachelor's Degree</option>
                                <option value="Master">Master's Degree</option>
                                <option value="PhD">PhD / Doctorate</option>
                                <option value="Certificate">Certificate</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="text" name="edu_field[]" class="form-control form-control-sm" placeholder="Field of Study">
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="number" name="edu_year[]" class="form-control form-control-sm" placeholder="Graduation Year" min="1950" max="2030">
                        </div>
                    </div>
                </div>
            `;
            $('#education-container').append(html);
        });

        // Improve Description with AI
        $(document).on('click', '.improve-desc-ai', function() {
            const textarea = $(this).closest('.col-md-12').find('textarea');
            const description = textarea.val();

            if (!description.trim()) {
                toastr.warning('Please enter a description first.');
                return;
            }

            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/improve-description") ?>',
                type: 'POST',
                data: {
                    description: description,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.description) {
                        textarea.val(response.description);
                        toastr.success('Description improved!');
                    }
                    $('#aiLoaderModal').modal('hide');
                },
                error: function() {
                    toastr.error('AI improvement failed.');
                    $('#aiLoaderModal').modal('hide');
                }
            });
        });

        // Save Resume
        $('#save-resume-btn').on('click', function() {
            const formData = $('#resume-form').serialize();
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="ti ti-loader-2 spinner-border spinner-border-sm me-1"></i>Saving...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/save") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Resume saved successfully!');
                    if (response.id) {
                        $('input[name="id"]').val(response.id);
                    }
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>Save Resume');
                },
                error: function() {
                    toastr.error('Failed to save resume.');
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>Save Resume');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
