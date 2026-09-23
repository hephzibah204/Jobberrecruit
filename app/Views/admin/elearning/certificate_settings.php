<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Certificate Settings &amp; Live Preview</h1>
            <p class="text-muted fs-13 mb-0">Manage authorized signatures, official seals, and inspect live certificate previews.</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="<?= base_url('admin/elearning/certificates/editor') ?>" class="btn btn-outline-secondary btn-wave d-inline-flex align-items-center">
                <i class="ti ti-layout-grid me-1"></i> Open Template Editor
            </a>
            <button type="button" class="btn btn-primary btn-wave d-inline-flex align-items-center" onclick="openFullscreenCertModal()">
                <i class="ti ti-maximize me-1"></i> Fullscreen Preview
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-1"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Upload Form Column -->
        <div class="col-xl-5">
            <div class="card custom-card shadow-sm border-0">
                <div class="card-header justify-content-between">
                    <div class="card-title fw-bold"><i class="ti ti-upload me-2 text-primary"></i>Upload Signature &amp; Stamp</div>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/elearning/certificates/settings/save') ?>" method="POST" enctype="multipart/form-data" id="certSettingsForm">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Authorized Signature Image</label>
                            <?php if (setting('Elearning.certificate_signature')): ?>
                                <div class="mb-2 p-2 bg-light rounded text-center border">
                                    <img src="<?= base_url(setting('Elearning.certificate_signature')) ?>" style="max-height: 70px; object-fit: contain;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="certificate_signature" id="sigFileInput" accept="image/png, image/jpeg">
                            <small class="text-muted fs-12 mt-1 d-block"><i class="ti ti-info-circle me-1"></i>Recommended format: Transparent PNG (approx. 400x150px).</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Official Seal / Stamp Image</label>
                            <?php if (setting('Elearning.certificate_stamp')): ?>
                                <div class="mb-2 p-2 bg-light rounded text-center border">
                                    <img src="<?= base_url(setting('Elearning.certificate_stamp')) ?>" style="max-height: 70px; object-fit: contain;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="certificate_stamp" id="stampFileInput" accept="image/png, image/jpeg">
                            <small class="text-muted fs-12 mt-1 d-block"><i class="ti ti-info-circle me-1"></i>Recommended format: Transparent PNG emblem (approx. 200x200px).</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ti ti-device-floppy me-1"></i> Save Certificate Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions Box -->
            <div class="card custom-card shadow-sm border-0 mt-3">
                <div class="card-header">
                    <div class="card-title fw-bold fs-14"><i class="ti ti-help-circle me-2 text-info"></i>Dynamic Generation Notes</div>
                </div>
                <div class="card-body fs-13 text-muted">
                    <ul class="ps-3 mb-0" style="line-height: 1.6;">
                        <li>System certificates feature JobberRecruit brand styling, course metadata, and verification QR codes.</li>
                        <li>Uploaded signatures &amp; stamps automatically display on all generated student PDFs.</li>
                        <li>Selecting new images will instantly update the <strong>Live Certificate Preview</strong> on the right.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Live Preview Column -->
        <div class="col-xl-7">
            <div class="card custom-card shadow-sm border-0">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title fw-bold"><i class="ti ti-eye me-2 text-success"></i>Live Certificate Preview</div>
                    <span class="badge bg-success-subtle text-success fw-semibold"><i class="ti ti-circle-filled fs-8 me-1"></i>Real-time Sync</span>
                </div>
                <div class="card-body p-4 bg-light-subtle">
                    <!-- Certificate Replica Canvas Container -->
                    <div id="certPreviewCanvas" class="cert-preview-frame" style="background:#ffffff; border: 10px solid #0D609E; border-radius: 8px; position: relative; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); font-family: 'Inter', sans-serif;">
                        <div style="border: 2px solid #F3921D; padding: 25px 20px; text-align: center; position: relative; background: #ffffff;">
                            
                            <!-- Header Logo & Badge -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div style="font-weight: 800; font-size: 20px; color: #0D609E; letter-spacing: -0.5px;">
                                    Jobber<span style="color: #F3921D;">Recruit</span>
                                </div>
                                <span class="badge bg-primary text-white fw-bold fs-10 px-2 py-1">VERIFIED ACADEMY</span>
                            </div>

                            <h6 class="text-uppercase text-muted fw-bold fs-11 mb-1" style="letter-spacing: 2px;">Course Completion Certificate</h6>
                            <h3 class="fw-bold mb-2" style="color: #0D609E; font-size: 20px; letter-spacing: 0.5px;">CERTIFICATE OF ACHIEVEMENT</h3>
                            <p class="text-muted fs-11 mb-1">PROUDLY PRESENTED TO</p>

                            <h2 class="fw-bold mb-2" style="color: #15233a; font-size: 24px; border-bottom: 2px solid #F3921D; display: inline-block; padding-bottom: 4px;">
                                Candidate Full Name
                            </h2>

                            <p class="text-muted fs-11 mb-1">For successfully completing the training program and demonstrating competence in</p>
                            <h5 class="fw-semibold mb-4 fs-15" style="color: #0D609E;">
                                Professional Skill &amp; Career Assessment Course
                            </h5>

                            <!-- Footer Metadata, Signature & Stamp Grid -->
                            <div class="row align-items-end pt-3 border-top" style="border-color: #e9ecef !important;">
                                <div class="col-4 text-start">
                                    <span class="d-block text-muted fs-10">Issue Date: <strong><?= date('F j, Y') ?></strong></span>
                                    <span class="d-block text-muted fs-10">Verification Code: <strong>CERT-2026-PREVIEW</strong></span>
                                </div>

                                <!-- Signature Column -->
                                <div class="col-4 text-center">
                                    <div id="previewSignatureContainer" class="mb-1" style="min-height: 48px; display: flex; align-items: center; justify-content: center;">
                                        <?php if (setting('Elearning.certificate_signature')): ?>
                                            <img id="previewSignatureImg" src="<?= base_url(setting('Elearning.certificate_signature')) ?>" alt="Signature" style="max-height: 48px; max-width: 140px; object-fit: contain;">
                                        <?php else: ?>
                                            <img id="previewSignatureImg" src="" alt="Signature" style="max-height: 48px; max-width: 140px; object-fit: contain; display:none;">
                                            <span id="noSigPlaceholder" class="text-muted fs-10 fst-italic">[ Signature Pending ]</span>
                                        <?php endif; ?>
                                    </div>
                                    <div style="border-top: 1px dashed #aaa; width: 85%; margin: 0 auto; padding-top: 2px;">
                                        <small class="fw-semibold text-dark fs-10 d-block">Authorized Director</small>
                                    </div>
                                </div>

                                <!-- Stamp & QR Column -->
                                <div class="col-4 text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                        <div id="previewStampContainer">
                                            <?php if (setting('Elearning.certificate_stamp')): ?>
                                                <img id="previewStampImg" src="<?= base_url(setting('Elearning.certificate_stamp')) ?>" alt="Stamp" style="max-height: 48px; max-width: 55px; object-fit: contain;">
                                            <?php else: ?>
                                                <img id="previewStampImg" src="" alt="Stamp" style="max-height: 48px; max-width: 55px; object-fit: contain; display:none;">
                                                <span id="noStampPlaceholder" class="text-muted fs-10 fst-italic">[ Seal Pending ]</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="qr-placeholder p-1 bg-light border rounded text-center" style="width: 42px; height: 42px;">
                                            <i class="ti ti-qrcode fs-24 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Certificate Preview Modal -->
<div class="modal fade" id="fullscreenCertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold fs-16 text-dark"><i class="ti ti-certificate me-2 text-primary"></i>Fullscreen Certificate Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center bg-dark-subtle">
                <div id="modalCertTarget" style="max-width: 900px; margin: 0 auto;">
                    <!-- Cloned preview element rendered here -->
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close Preview</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var sigInput = document.getElementById('sigFileInput');
    var stampInput = document.getElementById('stampFileInput');

    if (sigInput) {
        sigInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    var img = document.getElementById('previewSignatureImg');
                    var placeholder = document.getElementById('noSigPlaceholder');
                    if (img) {
                        img.src = evt.target.result;
                        img.style.display = 'inline-block';
                    }
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (stampInput) {
        stampInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    var img = document.getElementById('previewStampImg');
                    var placeholder = document.getElementById('noStampPlaceholder');
                    if (img) {
                        img.src = evt.target.result;
                        img.style.display = 'inline-block';
                    }
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

function openFullscreenCertModal() {
    var sourceCanvas = document.getElementById('certPreviewCanvas');
    var targetDiv = document.getElementById('modalCertTarget');
    if (sourceCanvas && targetDiv) {
        targetDiv.innerHTML = sourceCanvas.outerHTML;
    }
    var modal = new bootstrap.Modal(document.getElementById('fullscreenCertModal'));
    modal.show();
}
</script>

<?= $this->endSection() ?>