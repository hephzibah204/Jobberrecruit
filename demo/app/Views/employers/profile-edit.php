<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Edit Company Profile</h4>
                <h6>Update your company information</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="<?= base_url('employer/profile') ?>" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to Company Profile</a>
        </div>
    </div>

    <form action="<?= base_url('employer/profile/update/' . $employer->id) ?>" method="POST" class="edit-employer-form" enctype="multipart/form-data" id="editEmployerForm">
        <?= csrf_field() ?>

        <div class="add-employer">
            <div class="accordions-items-seperate" id="accordionSpacingExample">
                <!-- Basic Information Section -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingSpacingOne">
                        <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingOne" aria-expanded="true" aria-controls="SpacingOne">
                            <div class="d-flex align-items-center justify-content-between flex-fill">
                                <h5 class="d-flex align-items-center"><i data-feather="info" class="text-primary me-2"></i><span>Basic Information</span></h5>
                            </div>
                        </div>
                    </h2>
                    <div id="SpacingOne" class="accordion-collapse collapse show" aria-labelledby="headingSpacingOne">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">User ID<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control" value="<?= old('user_id', $employer->user_id ?? '') ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Company Name<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control" name="company_name" value="<?= old('company_name', $employer->company_name ?? '') ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Industry<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="industry_ids[]" multiple required>
                                            <?php foreach ($industries as $industry): ?>
                                                <optgroup label="<?= esc($industry->name) ?>">
                                                    <?php foreach ($industry->children as $child): ?>
                                                        <option value="<?= $child->id ?>"
                                                            <?= in_array($child->id, old('industry_ids', $employerIndustryIds ?? [])) ? 'selected' : '' ?>>
                                                            <?= esc($child->name) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="form-text text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple industries</small>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Company Size<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="company_size" required>
                                            <option value="" selected disabled>Select Size</option>
                                            <option value="1-10" <?= (old('company_size', $employer->company_size ?? '') == '1-10') ? 'selected' : '' ?>>1-10 employees</option>
                                            <option value="11-50" <?= (old('company_size', $employer->company_size ?? '') == '11-50') ? 'selected' : '' ?>>11-50 employees</option>
                                            <option value="51-200" <?= (old('company_size', $employer->company_size ?? '') == '51-200') ? 'selected' : '' ?>>51-200 employees</option>
                                            <option value="201-500" <?= (old('company_size', $employer->company_size ?? '') == '201-500') ? 'selected' : '' ?>>201-500 employees</option>
                                            <option value="501-1000" <?= (old('company_size', $employer->company_size ?? '') == '501-1000') ? 'selected' : '' ?>>501-1000 employees</option>
                                            <option value="1000+" <?= (old('company_size', $employer->company_size ?? '') == '1000+') ? 'selected' : '' ?>>1000+ employees</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Website</label>
                                        <input type="text" class="form-control" name="website" value="<?= old('website', $employer->website ?? '') ?>" placeholder="https://example.com">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">State<span class="text-danger ms-1">*</span></label>
                                        <select class="select" name="state_id" required>
                                            <option value="" selected disabled>Select State</option>
                                            <?php foreach ($states as $state): ?>
                                                <option value="<?= $state->id ?>" <?= (old('state_id', $employer->state_id ?? '') == $state->id) ? 'selected' : '' ?>>
                                                    <?= esc($state->name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Company Description</label>
                                        <textarea class="form-control" name="description" rows="4" placeholder="Describe your company..."><?= old('description', $employer->description ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingSpacingTwo">
                        <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingTwo" aria-expanded="true" aria-controls="SpacingTwo">
                            <div class="d-flex align-items-center justify-content-between flex-fill">
                                <h5 class="d-flex align-items-center"><i data-feather="user" class="text-primary me-2"></i><span>Contact Information</span></h5>
                            </div>
                        </div>
                    </h2>
                    <div id="SpacingTwo" class="accordion-collapse collapse show" aria-labelledby="headingSpacingTwo">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Contact Name<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control" name="contact_name" value="<?= old('contact_name', $employer->contact_name ?? '') ?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Contact Email<span class="text-danger ms-1">*</span></label>
                                        <input type="email" class="form-control" name="contact_email" value="<?= old('contact_email', $employer->contact_email ?? '') ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Contact Phone<span class="text-danger ms-1">*</span></label>
                                        <input type="tel" class="form-control" name="contact_phone" value="<?= old('contact_phone', $employer->contact_phone ?? '') ?>" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Company Address<span class="text-danger ms-1">*</span></label>
                                        <input type="tel" class="form-control" name="company_address" value="<?= old('company_address', $employer->company_address ?? '') ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Files Section -->
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingSpacingThree">
                        <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingThree" aria-expanded="true" aria-controls="SpacingThree">
                            <div class="d-flex align-items-center justify-content-between flex-fill">
                                <h5 class="d-flex align-items-center"><i data-feather="file" class="text-primary me-2"></i><span>Company Logo</span></h5>
                            </div>
                        </div>
                    </h2>
                    <div id="SpacingThree" class="accordion-collapse collapse show" aria-labelledby="headingSpacingThree">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Company Logo</label>
                                        <?php if (!empty($employer->logo)): ?>
                                            <div class="mb-2">
                                                <img src="<?= base_url($employer->logo) ?>" alt="Company Logo" class="img-thumbnail" style="max-height: 100px;" id="currentLogo">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="removeLogo">
                                                    <label class="form-check-label" for="removeLogo">
                                                        Remove current logo
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="image-upload image-upload-two">
                                            <input type="file" name="logo" accept=".jpg,.jpeg,.png,.gif" id="logoInput">
                                            <div class="image-uploads">
                                                <i data-feather="image" class="plus-down-add me-0"></i>
                                                <h4>Upload Logo</h4>
                                            </div>
                                        </div>
                                        <div id="logoPreview" class="mt-2" style="display: none;">
                                            <img id="logoPreviewImg" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                        <small class="form-text text-muted">Upload your company logo (JPG, PNG, or GIF)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3">
                                <i data-feather="info" class="me-2"></i>
                                <strong>Note:</strong> CAC Certificate is now required for verification.
                                Please upload your CAC certificate <a href="<?= base_url('employer/upload-document') ?>">here</a> if you haven't already.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-end mb-4">
                <a href="<?= base_url('employer/profile') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">Update Profile</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // -----------------------------------------
    // Website auto-prefix + strict validation
    // -----------------------------------------
    const websiteInput = $('input[name="website"]');

    websiteInput.on('blur', function() {
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
            toastr.error('Please enter a valid website (e.g. example.com)');
        }
    });

    // Logo Preview
    $('#logoInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logoPreview').show();
                $('#logoPreviewImg').attr('src', e.target.result);
                // Hide current logo if new one is selected
                $('#currentLogo').parent().hide();
            };
            reader.readAsDataURL(file);
        } else {
            toastr.warning('Please select a valid image file (JPG, PNG, or GIF).');
            $(this).val('');
        }
    });

    // Document Preview
    $('#docInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileType = file.type;
            $('#docPreview').show();
            if (fileType === 'application/pdf') {
                $('#docPreviewImg').hide();
                $('#docPreviewType').text('PDF file selected: ' + file.name).show();
                $('#docPreviewType').html('<i data-feather="file-text" class="me-1"></i> PDF: ' + file.name);
            } else if (fileType.startsWith('image/')) {
                $('#docPreviewType').hide();
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#docPreviewImg').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            } else {
                toastr.warning('Please select a valid file (PDF, JPG, PNG).');
                $(this).val('');
                $('#docPreview').hide();
            }
        }
    });

    // Remove logo checkbox handler
    $('#removeLogo').on('change', function() {
        if ($(this).is(':checked')) {
            $('#currentLogo').parent().hide();
            $('#logoPreview').hide();
            $('#logoInput').val('');
        }
    });

    // Remove doc checkbox handler
    $('#removeDoc').on('change', function() {
        if ($(this).is(':checked')) {
            $('#docPreview').hide();
            $('#docInput').val('');
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

    // AJAX Form Submission
    $('#editEmployerForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = $('#submitBtn');
        const btnText = submitBtn.find('.btn-text');
        const spinner = submitBtn.find('.spinner-border');

        // Show loading state
        submitBtn.prop('disabled', true);
        btnText.addClass('d-none');
        spinner.removeClass('d-none');

        // ✅ STRICT website validation
        const websiteResult = normalizeAndValidateWebsite(websiteInput.val());

        if (!websiteResult.valid) {
            toastr.error('Please enter a valid website (e.g. example.com)');
            websiteInput.addClass('is-invalid');

            submitBtn.prop('disabled', false);
            btnText.removeClass('d-none');
            spinner.addClass('d-none');
            return;
        }

        // Set normalized value
        websiteInput.val(websiteResult.value);

        // Create FormData
        const formData = new FormData(this);

        $.ajax({
            url: form.action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Hide loading state
                submitBtn.prop('disabled', false);
                btnText.removeClass('d-none');
                spinner.addClass('d-none');

                toastr.success(response.message);
                // Redirect to profile page
                window.location.href = '<?= base_url('employer/profile') ?>';
            },
            error: function(xhr) {
                // Hide loading state
                submitBtn.prop('disabled', false);
                btnText.removeClass('d-none');
                spinner.addClass('d-none');

                let message = 'An error occurred while updating the profile.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    message = errors.join('<br>');
                }
                toastr.error(message);
            }
        });
    });
</script>
<?= $this->endSection() ?>