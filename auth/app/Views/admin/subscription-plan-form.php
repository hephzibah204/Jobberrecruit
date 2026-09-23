<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">
                    <?= isset($plan) ? 'Edit Subscription Plan' : 'Create Subscription Plan' ?>
                </h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/subscriptions') ?>">Subscriptions</a></li>
                    <li class="breadcrumb-item active">
                        <?= isset($plan) ? 'Edit' : 'Create' ?>
                    </li>
                </ol>
            </div>

            <a href="<?= base_url('admin/subscriptions') ?>" class="btn btn-light">
                Back
            </a>
        </div>
    </div>

    <!-- FORM -->
    <form id="plan-form">
        <?= csrf_field() ?>

        <div class="card custom-card">
            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Plan Name *</label>
                        <input type="text" name="name" class="form-control"
                            value="<?= esc($plan->name ?? '') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Slug *</label>
                        <input type="text" name="slug" class="form-control"
                            value="<?= esc($plan->slug ?? '') ?>"
                            <?= isset($plan) ? 'readonly' : '' ?>
                            required>
                        <small class="text-muted">e.g. free, basic, premium</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Price (₦)</label>
                        <input type="number" name="price" class="form-control"
                            value="<?= esc($plan->price ?? 0) ?>">
                        <small class="text-muted">
                            0 = Free or Custom
                        </small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Duration (Days) *</label>
                        <input type="number" name="duration" class="form-control"
                            value="<?= esc($plan->duration ?? 30) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Job Limit</label>
                        <input type="number" name="job_limit" class="form-control"
                            value="<?= esc($plan->job_limit ?? '') ?>">
                        <small class="text-muted">Leave empty for unlimited</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Featured Job Limit</label>
                        <input type="number" name="featured_limit" class="form-control"
                            value="<?= esc($plan->featured_limit ?? '') ?>">
                        <small class="text-muted">Leave empty for unlimited</small>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= esc($plan->description ?? '') ?></textarea>
                    </div>

                </div>

            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <?= isset($plan) ? 'Update Plan' : 'Create Plan' ?>
                </button>
            </div>
        </div>
    </form>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right'
    };

    document.getElementById('plan-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch("<?= isset($plan)
                    ? base_url('admin/subscriptions/update/' . $plan->id)
                    : base_url('admin/subscriptions/store') ?>", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(this)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => {
                        window.location.href = "<?= base_url('admin/subscriptions') ?>";
                    }, 1200);
                } else {
                    toastr.error(res.message || 'Operation failed');
                }
            })
            .catch(() => toastr.error('Server error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Save';
            });
    });
</script>
<?= $this->endSection() ?>