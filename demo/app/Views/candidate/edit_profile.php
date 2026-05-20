<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Edit Candidate Profile</h4>
                <h6>Update your personal, career and document information</h6>
            </div>
        </div>

        <ul class="table-top-head">
            <li><a title="Refresh" onclick="location.reload()"><i class="ti ti-refresh"></i></a></li>
            <li><a title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a></li>
        </ul>

        <div class="page-btn mt-0">
            <a href="<?= base_url('candidate/profile') ?>" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-2"></i>Back to Profile
            </a>
        </div>
    </div>

    <form action="<?= base_url('candidate/profile/edit') ?>"
        method="POST"
        enctype="multipart/form-data"
        id="editCandidateForm">

        <?= csrf_field() ?>

        <div class="add-candidate">
            <div class="accordions-items-seperate">

                <!-- BASIC INFORMATION -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header">
                        <div class="accordion-button collapsed bg-white"
                            data-bs-toggle="collapse" data-bs-target="#BasicInfo">
                            <h5><i data-feather="user" class="text-primary me-2"></i> Basic Information</h5>
                        </div>
                    </h2>

                    <div id="BasicInfo" class="accordion-collapse collapse show">
                        <div class="accordion-body border-top">

                            <div class="row">
                                <!-- User ID -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">User ID</label>
                                    <input type="text" class="form-control" value="<?= $candidate->user_id ?>" readonly>
                                </div>

                                <!-- Full Name -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Full Name<span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control"
                                        value="<?= old('full_name', $candidate->full_name) ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- DOB -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control"
                                        value="<?= old('dob', $candidate->dob) ?>">
                                </div>

                                <!-- Gender -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="select">
                                        <option value="" selected disabled>Select</option>
                                        <option value="male" <?= $candidate->gender == 'male' ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= $candidate->gender == 'female' ? 'selected' : '' ?>>Female</option>
                                        <option value="other" <?= $candidate->gender == 'other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                            </div>

                            <div class="row">
                                <!-- Phone -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="<?= old('phone', $candidate->phone) ?>">
                                </div>

                                <!-- Location -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="state_id" class="form-control"
                                        value="<?= old('location', $candidate->location) ?>">
                                </div>
                            </div>

                            <div class="row">
                                <!-- State -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">State</label>
                                    <select name="state_id" class="select">
                                        <option value="">Select State</option>

                                        <?php foreach ($states as $state): ?>
                                            <option value="<?= $state->id ?>"
                                                <?= $candidate->state_id == $state->id ? 'selected' : '' ?>>
                                                <?= esc($state->name) ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>

                                <!-- Availability -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Availability</label>
                                    <select name="availability" class="select">
                                        <option value="" disabled selected>Select</option>
                                        <option value="immediately" <?= $candidate->availability == 'immediately' ? 'selected' : '' ?>>Immediately</option>
                                        <option value="1-week" <?= $candidate->availability == '1-week' ? 'selected' : '' ?>>1 Week</option>
                                        <option value="1-month" <?= $candidate->availability == '1-month' ? 'selected' : '' ?>>1 Month</option>
                                        <option value="2-months" <?= $candidate->availability == '2-months' ? 'selected' : '' ?>>2 Months</option>
                                    </select>
                                </div>

                            </div>

                            <!-- Bio -->
                            <div class="mb-3">
                                <label class="form-label">Short Bio / Summary</label>
                                <textarea name="description" class="form-control" rows="4"><?= old('description', $candidate->description ?? '') ?></textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- CAREER INFORMATION -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header">
                        <div class="accordion-button collapsed bg-white"
                            data-bs-toggle="collapse" data-bs-target="#CareerInfo">
                            <h5><i data-feather="briefcase" class="text-primary me-2"></i> Career Information</h5>
                        </div>
                    </h2>

                    <div id="CareerInfo" class="accordion-collapse collapse show">
                        <div class="accordion-body border-top">

                            <div class="row">
                                <!-- Job Title -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Job Title<span class="text-danger">*</span></label>
                                    <input type="text" name="job_title" class="form-control"
                                        value="<?= old('job_title', $candidate->job_title) ?>" required>
                                </div>

                                <!-- Employment Type -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Employment Type</label>
                                    <select name="employment_type" class="select">
                                        <option value="">Select</option>
                                        <option value="Full Time" <?= $candidate->employment_type == 'Full Time' ? 'selected' : '' ?>>Full Time</option>
                                        <option value="Part Time" <?= $candidate->employment_type == 'Part Time' ? 'selected' : '' ?>>Part Time</option>
                                        <option value="Remote" <?= $candidate->employment_type == 'Remote' ? 'selected' : '' ?>>Remote</option>
                                        <option value="Contract" <?= $candidate->employment_type == 'Contract' ? 'selected' : '' ?>>Contract</option>
                                        <option value="Internship" <?= $candidate->employment_type == 'Internship' ? 'selected' : '' ?>>Internship</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Experience -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Experience (Years)</label>
                                    <input type="number" name="experience_years" class="form-control"
                                        value="<?= old('experience_years', $candidate->experience_years) ?>">
                                </div>

                                <!-- Education -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Education Level</label>
                                    <select name="education_level" class="select">
                                        <option value="" disabled selected>Select education level</option>

                                        <option value="High School"
                                            <?= $candidate->education_level == 'High School' ? 'selected' : '' ?>>
                                            High School
                                        </option>

                                        <option value="Undergraduate"
                                            <?= $candidate->education_level == 'Undergraduate' ? 'selected' : '' ?>>
                                            Undergraduate
                                        </option>

                                        <option value="Diploma"
                                            <?= $candidate->education_level == 'Diploma' ? 'selected' : '' ?>>
                                            Diploma
                                        </option>

                                        <option value="Bachelor's Degree"
                                            <?= $candidate->education_level == "Bachelor's Degree" ? 'selected' : '' ?>>
                                            Bachelor's Degree
                                        </option>

                                        <option value="Master's Degree"
                                            <?= $candidate->education_level == "Master's Degree" ? 'selected' : '' ?>>
                                            Master's Degree
                                        </option>

                                        <option value="PhD"
                                            <?= $candidate->education_level == 'PhD' ? 'selected' : '' ?>>
                                            PhD
                                        </option>

                                        <option value="Professional Certification"
                                            <?= $candidate->education_level == 'Professional Certification' ? 'selected' : '' ?>>
                                            Professional Certification
                                        </option>

                                        <option value="Others"
                                            <?= $candidate->education_level == 'Others' ? 'selected' : '' ?>>
                                            Others
                                        </option>

                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Desired Salary -->
                                <div class="col-sm-6 mb-3">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label">Desired Salary (Amount)</label>
                                            <input type="number" name="desired_salary" class="form-control"
                                                value="<?= old('desired_salary', $candidate->desired_salary) ?>"
                                                placeholder="e.g 250000">
                                        </div>

                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label">Salary Type</label>
                                            <select name="salary_type" class="select">
                                                <option value="" disabled selected>Select</option>

                                                <option value="hourly" <?= ($candidate->salary_type ?? '') == 'hourly' ? 'selected' : '' ?>>
                                                    Hourly
                                                </option>

                                                <option value="monthly" <?= ($candidate->salary_type ?? '') == 'monthly' ? 'selected' : '' ?>>
                                                    Monthly
                                                </option>

                                                <option value="yearly" <?= ($candidate->salary_type ?? '') == 'yearly' ? 'selected' : '' ?>>
                                                    Yearly
                                                </option>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Languages -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Languages</label>
                                    <input type="text" name="languages" class="form-control"
                                        value="<?= old('languages', $candidate->languages) ?>"
                                        placeholder="English, French...">
                                </div>
                            </div>

                            <!-- Skills -->
                            <div class="mb-3">
                                <label class="form-label">Skills</label>
                                <textarea name="skills" class="form-control" rows="3"
                                    placeholder="e.g PHP, UI/UX, Figma, React..."><?= old('skills', $candidate->skills) ?></textarea>
                            </div>

                            <!-- Portfolio -->
                            <div class="mb-3">
                                <label class="form-label">Portfolio Link</label>
                                <input type="text" name="portfolio" class="form-control"
                                    value="<?= old('portfolio', $candidate->portfolio) ?>"
                                    placeholder="https://portfolio.com">
                            </div>

                            <!-- Industries -->
                            <div class="mb-3">
                                <label class="form-label">Industries (You can select multiple)<span class="text-danger">*</span></label>
                                <select class="select" name="industry_ids[]" multiple required>
                                    <?php foreach ($industries as $industry): ?>
                                        <optgroup label="<?= esc($industry->name) ?>">
                                            <?php foreach ($industry->children as $child): ?>
                                                <option value="<?= $child->id ?>"
                                                    <?= in_array($child->id, $candidateIndustryIds ?? []) ? 'selected' : '' ?>>
                                                    <?= esc($child->name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>


                                <small class="form-text text-muted">
                                    Hold CTRL (Windows) or CMD (Mac) to select multiple industries.
                                </small>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- DOCUMENTS -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header">
                        <div class="accordion-button collapsed bg-white"
                            data-bs-toggle="collapse" data-bs-target="#Files">
                            <h5><i data-feather="file" class="text-primary me-2"></i>Profile Picture & Resume</h5>
                        </div>
                    </h2>

                    <div id="Files" class="accordion-collapse collapse show">
                        <div class="accordion-body border-top">

                            <div class="row">

                                <!-- PROFILE PICTURE -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Profile Picture</label>

                                    <?php if (!empty($candidate->profile_picture)): ?>
                                        <div class="mb-2">
                                            <img src="<?= base_url($candidate->profile_picture) ?>"
                                                class="img-thumbnail"
                                                style="max-height: 120px;"
                                                id="currentProfilePic">

                                            <div class="form-check mt-2">
                                                <input type="checkbox" name="remove_profile_picture"
                                                    id="removePic" value="1">
                                                <label for="removePic">Remove current picture</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Upload -->
                                    <input type="file" name="profile_picture" id="profileInput"
                                        accept="image/*" class="form-control">

                                    <!-- Preview -->
                                    <div id="profilePreview" class="mt-2" style="display:none;">
                                        <img id="profilePreviewImg" class="img-thumbnail"
                                            style="max-height:120px;">
                                    </div>
                                </div>

                                <!-- RESUME -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">Resume / CV</label>

                                    <?php if (!empty($candidate->resume)): ?>
                                        <div class="mb-2">
                                            <a href="<?= base_url($candidate->resume) ?>" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i data-feather="file-text" class="me-1"></i>View Resume
                                            </a>
                                            <div class="form-check mt-2">
                                                <input type="checkbox" name="remove_resume" id="removeResume" value="1">
                                                <label for="removeResume">Remove resume</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Upload -->
                                    <input type="file" name="resume" id="resumeInput"
                                        accept=".pdf,.doc,.docx"
                                        class="form-control">

                                    <!-- Preview -->
                                    <div id="resumePreview" class="mt-2" style="display:none;">
                                        <div class="border rounded p-2 d-inline-block">
                                            <i data-feather="file-text"></i>
                                            <span id="resumePreviewText" class="ms-2 small"></span>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- SAVE BUTTON -->
        <div class="d-flex justify-content-end mb-4">
            <a href="<?= base_url('candidate/profile') ?>" class="btn btn-secondary me-2">Cancel</a>

            <button type="submit" class="btn btn-primary" id="submitBtn">
                <span class="btn-text">Update Profile</span>
                <span class="spinner-border spinner-border-sm d-none"></span>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<!-- JAVASCRIPT -->
<?= $this->section('scripts') ?>

<script>
    $(document).ready(function() {

        // -----------------------------------------
        // Portfolio auto-prefix + strict validation
        // -----------------------------------------
        const portfolioInput = $('input[name="portfolio"]');

        portfolioInput.on('blur', function() {
            let value = $(this).val().trim();

            if (!value) {
                $(this).removeClass('is-invalid');
                return; // optional field
            }

            // Auto prepend https://
            if (!/^https?:\/\//i.test(value)) {
                value = 'https://' + value;
            }

            try {
                const url = new URL(value);
                const hostname = url.hostname;

                // ❗ Enforce real domain rules
                const hasDot = hostname.includes('.');
                const validTld = hostname.split('.').pop().length >= 2;

                if (!hasDot || !validTld) {
                    throw new Error('Invalid domain');
                }

                // ✅ Passed all checks
                $(this).val(value);
                $(this).removeClass('is-invalid');

            } catch (e) {
                $(this).addClass('is-invalid');
                toastr.error('Please enter a valid portfolio url (e.g. example.com)');
            }
        });

        function normalizeAndValidateWebsite(rawValue) {
            let value = rawValue.trim();

            if (!value) return {
                valid: true,
                value: ''
            }; // optional

            if (!/^https?:\/\//i.test(value)) {
                value = 'https://' + value;
            }

            try {
                const url = new URL(value);
                const hostname = url.hostname;

                // Enforce public domain rules
                if (!hostname.includes('.')) return {
                    valid: false
                };
                if (hostname.split('.').pop().length < 2) return {
                    valid: false
                };

                return {
                    valid: true,
                    value
                };
            } catch {
                return {
                    valid: false
                };
            }
        }



        /* -----------------------------------
         * PROFILE PICTURE PREVIEW
         * ----------------------------------- */
        $('#profileInput').on('change', function(e) {
            const file = e.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#profilePreview').show();
                    $('#profilePreviewImg').attr('src', e.target.result);
                    $('#currentProfilePic').hide();
                };
                reader.readAsDataURL(file);
            } else {
                toastr.warning("Invalid image file selected.");
                $(this).val('');
            }
        });

        /* -----------------------------------
         * RESUME PREVIEW
         * ----------------------------------- */
        $('#resumeInput').on('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                $('#resumePreview').show();
                $('#resumePreviewText').text(file.name);
            } else {
                $('#resumePreview').hide();
            }
        });

        /* -----------------------------------
         * AJAX SUBMIT
         * ----------------------------------- */
        $('#editCandidateForm').on('submit', function(e) {
            e.preventDefault();

            let btn = $('#submitBtn'),
                text = btn.find('.btn-text'),
                spin = btn.find('.spinner-border');

            btn.prop('disabled', true);
            text.addClass('d-none');
            spin.removeClass('d-none');

             // Validate portfolio before submit
             // ✅ STRICT website validation
             const portfolioResult = normalizeAndValidateWebsite(portfolioInput.val());

             if (!portfolioResult.valid) {
                 toastr.error('Please enter a valid portfolio URL.');
                 portfolioInput.addClass('is-invalid');
                 btn.prop('disabled', false);
                 text.removeClass('d-none');
                 spin.addClass('d-none');
                 return;
             }

            // Set normalized value
            portfolioInput.val(portfolioResult.value);
            const formData = new FormData(this);

            $.ajax({
                url: this.action,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(res) {
                    if (res.status === 'error') {
                        btn.prop('disabled', false);
                        text.removeClass('d-none');
                        spin.addClass('d-none');
                        toastr.error(res.message);
                        return;
                    } else {
                        btn.prop('disabled', false);
                        text.removeClass('d-none');
                        spin.addClass('d-none');
                        toastr.success(res.message);
                        window.location.href = "<?= base_url('candidate/profile') ?>";
                    }
                    // toastr.success("Profile updated successfully!");
                    // window.location.href = "<?= base_url('candidate/profile') ?>";
                },

                error: function(xhr) {
                    btn.prop('disabled', false);
                    text.removeClass('d-none');
                    spin.addClass('d-none');

                    let msg = "Something went wrong.";

                    if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join("<br>");
                    }

                    toastr.error(msg);
                }
            });
        });

    });
</script>

<?= $this->endSection() ?>