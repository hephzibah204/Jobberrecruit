<?php $page_title = 'Upload Document'; ?>
<?= $this->extend('layouts/employer') ?>

<?= $this->section('content') ?>
<div class="page-head">
    <div class="page-head-left">
        <h1>Upload CAC Certificate</h1>
        <p>Verify your company registration documents with Corporate Affairs Commission</p>
    </div>
</div>

<div class="duo" style="grid-template-columns: 1.2fr 1fr;">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title">
                <svg aria-hidden="true"><use href="#i-shield"/></svg>
                CAC Document
            </h2>
        </div>
        <div class="card-body">
            <?php if (session('error')): ?>
                <div class="info-banner danger mb-4">
                    <svg aria-hidden="true"><use href="#i-bulb"/></svg>
                    <div class="info-banner-body">
                        <strong>Error:</strong> <?= esc(session('error')) ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($hasDocument) && $hasDocument && isset($existingDoc) && $existingDoc): ?>
                <div class="info-banner warning mb-4">
                    <svg aria-hidden="true"><use href="#i-bulb"/></svg>
                    <div class="info-banner-body">
                        <strong>Existing Document:</strong> You have already uploaded a CAC certificate.
                        <?php if ($existingDoc->status == 'pending'): ?>
                            <span class="pill pill--pending">Pending Review</span>
                        <?php elseif ($existingDoc->status == 'approved'): ?>
                            <span class="pill pill--success">Approved</span>
                        <?php elseif ($existingDoc->status == 'rejected'): ?>
                            <span class="pill pill--rejected">Rejected</span>
                        <?php endif; ?>
                        <br>
                        <a href="<?= base_url($existingDoc->file_path) ?>" target="_blank" class="emp-btn emp-btn-outline emp-btn-sm mt-3">
                            <svg aria-hidden="true"><use href="#i-eye"/></svg> View Current Document
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('employer/profile/process-document-upload') ?>"
                  method="POST"
                  enctype="multipart/form-data"
                  class="form-section-body"
                  style="padding:0;">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">CAC Certificate <span class="req">*</span></label>
                    <input type="file"
                           name="cac_document"
                           class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png"
                           required>
                    <small class="form-hint">
                        Accepted formats: PDF, JPG, JPEG, PNG (Max size: 5MB)
                    </small>
                    <?php if (session('errors.cac_document')): ?>
                        <div style="color:var(--danger); font-size:0.75rem; margin-top:4px;">
                            <?= esc(session('errors.cac_document')) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group" style="flex-direction:row; align-items:flex-start; gap:10px; margin-top:8px;">
                    <input type="checkbox" id="confirm" required style="margin-top:4px; width:16px; height:16px;">
                    <label for="confirm" class="form-label" style="font-weight:500; font-size:0.8rem; cursor:pointer;">
                        I confirm that the uploaded document is authentic and belongs to my company.
                    </label>
                </div>

                <div style="display:flex; flex-direction:column; gap:10px; margin-top:16px;">
                    <button type="submit" class="emp-btn emp-btn-primary emp-btn-block">
                        <svg aria-hidden="true"><use href="#i-plus"/></svg>
                        <?= (isset($hasDocument) && $hasDocument) ? 'Replace Certificate' : 'Upload Certificate' ?>
                    </button>
                    <a href="<?= base_url('employer/profile') ?>" class="emp-btn emp-btn-outline emp-btn-block" style="text-align:center;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body empty-state" style="padding: 32px 20px;">
            <div class="empty-ic" style="background: var(--brand-light); color: var(--brand);">
                <svg aria-hidden="true" style="width:24px; height:24px;"><use href="#i-bulb"/></svg>
            </div>
            <h3>Why do we need this?</h3>
            <p>To verify your business legitimacy, we require your CAC (Corporate Affairs Commission) certificate. This helps us maintain a trusted marketplace for job seekers and employers.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>