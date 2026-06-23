<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0 shadow-sm p-5" style="border-radius: 20px;">
                    <div class="mb-4">
                        <?php if (($type ?? 'success') === 'success'): ?>
                            <i class="ti ti-circle-check-filled text-success" style="font-size: 80px;"></i>
                        <?php else: ?>
                            <i class="ti ti-alert-circle-filled text-warning" style="font-size: 80px;"></i>
                        <?php endif; ?>
                    </div>
                    <h2 class="fw-bold text-dark mb-3"><?= esc($title ?? 'Success') ?></h2>
                    <p class="text-muted fs-16 mb-4"><?= esc($message ?? 'Action completed successfully.') ?></p>
                    <a href="<?= base_url() ?>" class="btn btn-primary btn-lg px-5" style="border-radius: 10px;">Return to Home</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
