<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <!-- PAGE HEADER -->
    <div class="page-header tr-header-band">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold text-white">Edit Company Profile</h4>
                <h6 class="text-white-50">Update your company details and logo</h6>
            </div>
        </div>
        <div class="page-btn mt-0">
            <a href="<?= base_url('employer/profile') ?>" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to Profile</a>
        </div>
    </div>

    <!-- STICKY PROGRESS BAR -->
    <div class="sticky-progress-container mb-4">
        <div class="progress-inner">
            <div class="progress-left">
                <div class="progress-track">
                    <div class="progress-fill" id="completionProgressFill" style="width: 0%;"></div>
                </div>
                <span class="progress-text" id="completionProgressText">0% Complete</span>
            </div>
            <span class="progress-tip" id="completionProgressTip"><i data-feather="info" style="width: 14px; height: 14px; vertical-align: middle;"></i> Tip: Keep your profile detailed to attract top talent.</span>
        </div>
    </div>

    <form action="<?= base_url('employer/profile/edit') ?>" method="POST" class="edit-employer-form" enctype="multipart/form-data" id="editEmployerForm">
        <?= csrf_field() ?>

        <div class="row">
            <div class="col-lg-12">
                <!-- Basic Information Card -->
                <div class="cv-card mb-4" id="cardBasicInfo">
                    <div class="cv-card-header">
                        <div class="cv-card-title">
                            <i data-feather="info" class="text-primary me-2"></i>
                            <span>Basic Information</span>
                        </div>
                        <span class="cv-card-done incomplete" id="badgeBasicInfo">Incomplete</span>
                    </div>
                    <div class="cv-card-body">
                        <div class="form-grid">
                            <div class="form-field">
                                <label class="form-label">User ID Reference <span class="opt">(Read-only)</span></label>
                                <input type="text" value="<?= old('user_id', $employer->user_id ?? '') ?>" readonly class="form-control bg-light text-muted">
                            </div>

                            <div class="form-field">
                                <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" value="<?= old('company_name', $employer->company_name ?? '') ?>" required placeholder="e.g. Acme Corporation" class="form-control calculate-progress">
                            </div>
                        </div>

                        <div class="form-grid mt-3">
                            <div class="form-field">
                                <label class="form-label">Industry <span class="text-danger">*</span></label>
                                <select class="select select2-industry calculate-progress" name="industry_ids[]" multiple required id="industrySelect">
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
                                <small class="form-text text-muted">Select one or more industries matching your sector.</small>
                            </div>

                            <div class="form-field">
                                <label class="form-label">Company Size <span class="text-danger">*</span></label>
                                <select class="select calculate-progress" name="company_size" required>
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

                        <div class="form-grid mt-3">
                            <div class="form-field">
                                <label class="form-label">Website URL <span class="opt">(Optional)</span></label>
                                <input type="text" name="website" value="<?= old('website', $employer->website ?? '') ?>" placeholder="https://example.com" class="form-control calculate-progress">
                            </div>

                            <div class="form-field">
                                <label class="form-label">State / Location <span class="text-danger">*</span></label>
                                <select class="select calculate-progress" name="state_id" required>
                                    <option value="" selected disabled>Select State</option>
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?= $state->id ?>" <?= (old('state_id', $employer->state_id ?? '') == $state->id) ? 'selected' : '' ?>>
                                            <?= esc($state->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-field mt-3">
                            <label class="form-label">Company Description <span class="opt">(Detailed background)</span></label>
                            <textarea name="description" rows="4" placeholder="Describe your company's mission, culture, and projects..." class="form-control calculate-progress"><?= old('description', $employer->description ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="cv-card mb-4" id="cardContactInfo">
                    <div class="cv-card-header">
                        <div class="cv-card-title">
                            <i data-feather="mail" class="text-primary me-2"></i>
                            <span>Contact Information</span>
                        </div>
                        <span class="cv-card-done incomplete" id="badgeContactInfo">Incomplete</span>
                    </div>
                    <div class="cv-card-body">
                        <div class="form-grid">
                            <div class="form-field">
                                <label class="form-label">Contact Name <span class="text-danger">*</span></label>
                                <input type="text" name="contact_name" value="<?= old('contact_name', $employer->contact_name ?? '') ?>" required placeholder="e.g. John Doe" class="form-control calculate-progress">
                            </div>

                            <div class="form-field">
                                <label class="form-label">Contact Email <span class="text-danger">*</span></label>
                                <input type="email" name="contact_email" value="<?= old('contact_email', $employer->contact_email ?? '') ?>" required placeholder="e.g. careers@company.com" class="form-control calculate-progress">
                            </div>
                        </div>

                        <div class="form-grid mt-3">
                            <div class="form-field">
                                <label class="form-label">Contact Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="contact_phone" value="<?= old('contact_phone', $employer->contact_phone ?? '') ?>" required placeholder="e.g. +234..." class="form-control calculate-progress">
                            </div>

                            <div class="form-field">
                                <label class="form-label">Company Physical Address <span class="text-danger">*</span></label>
                                <input type="text" name="company_address" value="<?= old('company_address', $employer->company_address ?? '') ?>" required placeholder="e.g. Plot 15, Admiralty Way, Lekki" class="form-control calculate-progress">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branding & Logo Card -->
                <div class="cv-card mb-4" id="cardLogo">
                    <div class="cv-card-header">
                        <div class="cv-card-title">
                            <i data-feather="image" class="text-primary me-2"></i>
                            <span>Branding &amp; Company Logo</span>
                        </div>
                        <span class="cv-card-done incomplete" id="badgeLogo">Incomplete</span>
                    </div>
                    <div class="cv-card-body">
                        <div class="logo-upload">
                            <div class="logo-preview-wrapper text-center">
                                <?php if (!empty($employer->logo)): ?>
                                    <div class="mb-2 position-relative d-inline-block" id="currentLogoContainer">
                                        <img src="<?= base_url($employer->logo) ?>" alt="Company Logo" class="img-thumbnail" style="max-height: 100px;" id="currentLogo">
                                        <div class="form-check mt-2 justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="removeLogo">
                                            <label class="form-check-label text-muted small" for="removeLogo">Remove logo</label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div id="logoPreview" class="mt-2" style="display: none;">
                                    <img id="logoPreviewImg" class="img-thumbnail" style="max-height: 100px;" alt="">
                                </div>
                            </div>

                            <div class="logo-upload-body flex-grow-1">
                                <div class="image-upload-wrapper border rounded-3 p-3 bg-light text-center cursor-pointer" onclick="document.getElementById('logoInput').click()">
                                    <i data-feather="upload-cloud" class="text-muted mb-2" style="width: 32px; height: 32px;"></i>
                                    <p class="mb-1 text-dark fw-semibold small">Click to upload company logo</p>
                                    <p class="text-muted small mb-0">Supported formats: JPG, PNG, GIF (Max 2MB)</p>
                                    <input type="file" name="logo" accept=".jpg,.jpeg,.png,.gif" id="logoInput" class="d-none">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3 small border border-info-20">
                            <i data-feather="info" class="me-2 text-info" style="width: 14px; height: 14px;"></i>
                            <strong>CAC Certificate Verification:</strong> Employers are required to upload business documents to publish premium openings. Access the verification wizard <a href="<?= base_url('employer/profile/upload-document') ?>" class="alert-link fw-bold">here</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-end gap-2 mb-5">
                <a href="<?= base_url('employer/profile') ?>" class="btn btn-secondary px-4">Cancel</a>
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                    <span class="btn-text">Update Profile</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* ═══════════════════════════════════════════════════════════════════
       EMPLOYER PROFILE EDITOR STYLING — Premium Blue & Orange Accents
       ═══════════════════════════════════════════════════════════════════ */
    :root {
        --brand:        #0D609E;
        --brand-dark:   #0A4D7E;
        --brand-deep:   #07304F;
        --brand-light:  #E6F0F9;
        --accent:       #F08F1A;
        --accent-dark:  #C8750E;
        --border:       #e2e8f0;
        --radius:       12px;
        --transition:   .2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .tr-header-band {
        background: linear-gradient(135deg, var(--brand-deep) 0%, var(--brand-dark) 50%, var(--brand) 100%);
        padding: 24px 28px;
        border-radius: 12px;
        margin-bottom: 28px;
        box-shadow: 0 4px 20px rgba(13, 96, 158, 0.15);
    }

    /* Sticky progress tracking */
    .sticky-progress-container {
        position: sticky;
        top: 70px;
        z-index: 900;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 14px 20px;
        box-shadow: 0 4px 14px rgba(13, 96, 158, 0.05);
    }

    .progress-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .progress-left {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 1;
        min-width: 260px;
    }
    .progress-track {
        flex: 1;
        height: 8px;
        background: var(--border);
        border-radius: 20px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--brand), #1a7fd4);
        border-radius: 20px;
        transition: width .4s ease;
    }
    .progress-text {
        font-size: .85rem;
        font-weight: 700;
        color: var(--brand-deep);
        white-space: nowrap;
    }
    .progress-tip {
        font-size: .78rem;
        color: var(--accent-dark);
        font-weight: 600;
    }

    /* Form and card configurations */
    .cv-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-left: 4px solid var(--accent);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 4px 18px rgba(13, 96, 158, 0.04);
        transition: var(--transition);
    }
    .cv-card:hover {
        border-color: var(--brand);
    }
    .cv-card.is-complete {
        border-left-color: var(--brand);
    }

    .cv-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        background: #fafafa;
    }
    .cv-card-title {
        display: flex;
        align-items: center;
        font-family: 'Sora', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--brand-deep);
    }
    .cv-card-done {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding: 3px 9px;
        border-radius: 20px;
    }
    .cv-card-done.complete {
        background: var(--brand-light);
        color: var(--brand);
        border: 1px solid rgba(13, 96, 158, 0.2);
    }
    .cv-card-done.incomplete {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid var(--border);
    }

    .cv-card-body {
        padding: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-label {
        font-size: .85rem;
        font-weight: 600;
        color: var(--brand-deep);
        margin: 0;
    }
    .form-label .opt {
        font-weight: 400;
        color: #64748b;
        font-size: .78rem;
    }

    /* Style improvements for fields */
    .form-control, select {
        min-height: 42px;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 14px;
        font-size: .9rem;
        transition: var(--transition);
        background-color: #f8fafc;
    }
    .form-control:focus, select:focus {
        border-color: var(--brand);
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(13, 96, 158, 0.1);
        outline: none;
    }
    textarea.form-control {
        min-height: 120px;
    }

    /* Custom upload design */
    .cursor-pointer {
        cursor: pointer;
    }
    .image-upload-wrapper {
        border: 2px dashed var(--border) !important;
        transition: var(--transition);
    }
    .image-upload-wrapper:hover {
        border-color: var(--brand) !important;
        background-color: var(--brand-light) !important;
    }

    .logo-upload {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
    }

    .border-info-20 {
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .tr-header-band {
            padding: 18px 20px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const websiteInput = $('input[name="website"]');

    // Feathers icon replace helper
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Input-level website validation on focusout
    websiteInput.on('blur', function() {
        let value = $(this).val().trim();

        if (!value) {
            $(this).removeClass('is-invalid');
            return;
        }

        if (!/^https?:\/\//i.test(value)) {
            value = 'https://' + value;
        }

        try {
            const url = new URL(value);
            const hostname = url.hostname;
            const hasDot = hostname.includes('.');
            const validTld = hostname.split('.').pop().length >= 2;

            if (!hasDot || !validTld) {
                throw new Error('Invalid domain');
            }

            $(this).val(value);
            $(this).removeClass('is-invalid');
        } catch (e) {
            $(this).addClass('is-invalid');
            toastr.error('Please enter a valid website domain.');
        }
    });

    // Logo Upload Previews
    $('#logoInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logoPreview').show();
                $('#logoPreviewImg').attr('src', e.target.result);
                $('#currentLogoContainer').hide();
                calculateFormCompletion();
            };
            reader.readAsDataURL(file);
        } else {
            toastr.warning('Please select a valid image file (JPG, PNG, or GIF).');
            $(this).val('');
        }
    });

    $('#removeLogo').on('change', function() {
        if ($(this).is(':checked')) {
            $('#currentLogoContainer').hide();
            $('#logoPreview').hide();
            $('#logoInput').val('');
            calculateFormCompletion();
        }
    });

    // -------------------------------------------------------------
    // PROGRESS CALCULATION LOGIC
    // -------------------------------------------------------------
    function calculateFormCompletion() {
        const fields = [
            'input[name="company_name"]',
            'select[name="company_size"]',
            'select[name="state_id"]',
            'textarea[name="description"]',
            'input[name="contact_name"]',
            'input[name="contact_email"]',
            'input[name="contact_phone"]',
            'input[name="company_address"]'
        ];

        let completedFields = 0;
        let totalFields = fields.length + 3; // + industries + website + logo

        // Standard fields
        fields.forEach(selector => {
            const el = $(selector);
            if (el.val() && el.val().trim() !== '') {
                completedFields++;
            }
        });

        // Industry (select2 multiple check)
        const indVal = $('#industrySelect').val();
        if (indVal && indVal.length > 0) {
            completedFields++;
        }

        // Optional website field counts as complete if filled
        const webVal = websiteInput.val();
        if (webVal && webVal.trim() !== '') {
            completedFields++;
        }

        // Logo field check
        const hasExistingLogo = $('#currentLogoContainer').is(':visible');
        const hasNewLogo = $('#logoInput').val() !== '';
        if (hasExistingLogo || hasNewLogo) {
            completedFields++;
        }

        const percentage = Math.round((completedFields / totalFields) * 100);

        // Update sticky progress UI
        $('#completionProgressFill').css('width', percentage + '%');
        $('#completionProgressText').text(percentage + '% Complete');

        // Update Card Headers Complete badges
        // 1. Basic Info details
        let basicDone = true;
        ['input[name="company_name"]', 'select[name="company_size"]', 'select[name="state_id"]'].forEach(sel => {
            if (!$(sel).val() || $(sel).val().trim() === '') basicDone = false;
        });
        if (!indVal || indVal.length === 0) basicDone = false;

        const cardBasic = $('#cardBasicInfo');
        const badgeBasic = $('#badgeBasicInfo');
        if (basicDone) {
            cardBasic.addClass('is-complete');
            badgeBasic.text('Complete').removeClass('incomplete').addClass('complete');
        } else {
            cardBasic.removeClass('is-complete');
            badgeBasic.text('Incomplete').removeClass('complete').addClass('incomplete');
        }

        // 2. Contact details
        let contactDone = true;
        ['input[name="contact_name"]', 'input[name="contact_email"]', 'input[name="contact_phone"]', 'input[name="company_address"]'].forEach(sel => {
            if (!$(sel).val() || $(sel).val().trim() === '') contactDone = false;
        });

        const cardContact = $('#cardContactInfo');
        const badgeContact = $('#badgeContactInfo');
        if (contactDone) {
            cardContact.addClass('is-complete');
            badgeContact.text('Complete').removeClass('incomplete').addClass('complete');
        } else {
            cardContact.removeClass('is-complete');
            badgeContact.text('Incomplete').removeClass('complete').addClass('incomplete');
        }

        // 3. Logo details
        const cardLogo = $('#cardLogo');
        const badgeLogo = $('#badgeLogo');
        if (hasExistingLogo || hasNewLogo) {
            cardLogo.addClass('is-complete');
            badgeLogo.text('Complete').removeClass('incomplete').addClass('complete');
        } else {
            cardLogo.removeClass('is-complete');
            badgeLogo.text('Incomplete').removeClass('complete').addClass('incomplete');
        }
    }

    // Trigger calculation on input/change events
    $(document).on('input change', '.calculate-progress, select', function() {
        calculateFormCompletion();
    });

    // Run once on load
    $(document).ready(function() {
        calculateFormCompletion();
    });

    function normalizeAndValidateWebsite(rawValue) {
        let value = rawValue.trim();
        if (!value) return { valid: true, value: '' };

        if (!/^https?:\/\//i.test(value)) {
            value = 'https://' + value;
        }

        try {
            const url = new URL(value);
            const hostname = url.hostname;

            if (!hostname.includes('.')) return { valid: false };
            if (hostname.split('.').pop().length < 2) return { valid: false };

            return { valid: true, value };
        } catch {
            return { valid: false };
        }
    }

    // AJAX Form Submit
    $('#editEmployerForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = $('#submitBtn');
        const btnText = submitBtn.find('.btn-text');
        const spinner = submitBtn.find('.spinner-border');

        submitBtn.prop('disabled', true);
        btnText.addClass('d-none');
        spinner.removeClass('d-none');

        // Website verification
        const websiteResult = normalizeAndValidateWebsite(websiteInput.val());
        if (!websiteResult.valid) {
            toastr.error('Please enter a valid website (e.g. example.com)');
            websiteInput.addClass('is-invalid');
            submitBtn.prop('disabled', false);
            btnText.removeClass('d-none');
            spinner.addClass('d-none');
            return;
        }

        websiteInput.val(websiteResult.value);

        const formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                submitBtn.prop('disabled', false);
                btnText.removeClass('d-none');
                spinner.addClass('d-none');

                toastr.success(response.message);
                setTimeout(function() {
                    window.location.href = '<?= base_url('employer/profile') ?>';
                }, 1000);
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false);
                btnText.removeClass('d-none');
                spinner.addClass('d-none');

                let message = 'An error occurred while updating the profile.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    message = errors.join('<br>');
                }
                toastr.error(message);
            }
        });
    });
</script>
<?= $this->endSection() ?>