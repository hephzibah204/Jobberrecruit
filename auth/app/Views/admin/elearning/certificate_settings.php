<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Certificate Settings</h1>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Upload Signature & Stamp</div>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/elearning/certificates/settings/save') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="mb-4">
                                <label class="form-label">Authorized Signature</label>
                                <?php if (setting('Elearning.certificate_signature')): ?>
                                    <div class="mb-2">
                                        <img src="<?= base_url(setting('Elearning.certificate_signature')) ?>" style="max-height: 80px; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="certificate_signature" accept="image/png, image/jpeg">
                                <small class="text-muted">Upload a transparent PNG for best results.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Official Stamp</label>
                                <?php if (setting('Elearning.certificate_stamp')): ?>
                                    <div class="mb-2">
                                        <img src="<?= base_url(setting('Elearning.certificate_stamp')) ?>" style="max-height: 80px; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="certificate_stamp" accept="image/png, image/jpeg">
                                <small class="text-muted">Upload a transparent PNG for best results.</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Certificate Design Instructions</div>
                    </div>
                    <div class="card-body">
                        <p>The system-generated certificate has been upgraded to automatically match the <strong>JobberRecruit brand colors</strong> (Blue and Orange) and include the official logo.</p>
                        <p>When a candidate completes a course:</p>
                        <ul>
                            <li>The system checks if a <strong>Manual Certificate</strong> has been uploaded for that specific completion.</li>
                            <li>If a manual certificate exists, the user downloads that exact PDF.</li>
                            <li>If not, the system generates a dynamic PDF featuring the user's name, course title, completion date, and the signature/stamp provided on this page.</li>
                        </ul>
                        <p>To manually override a certificate for a specific user, go to the <strong>Issued Certificates</strong> tab and upload a custom PDF.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>