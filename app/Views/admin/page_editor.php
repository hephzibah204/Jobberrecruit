<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .code-editor-textarea {
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 13.5px;
        line-height: 1.5;
        background: #0F172A;
        color: #E2E8F0;
        border-radius: 8px;
        padding: 16px;
        width: 100%;
        min-height: 440px;
        border: 1px solid #334155;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- PAGE HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0"><?= $page ? 'Edit Page: ' . esc($page->title) : 'New Page' ?></h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/pages') ?>">Pages</a></li>
                    <li class="breadcrumb-item active"><?= $page ? 'Edit' : 'New' ?></li>
                </ol>
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= base_url('admin/pages/save') ?>" id="pageForm">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $page ? (int) $page->id : '' ?>">

        <div class="row">
            <!-- MAIN EDITOR -->
            <div class="col-xl-8">
                <div class="card custom-card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Page Content</h5>
                        <?php if ($page): ?>
                            <span class="badge bg-primary-transparent font-monospace"><?= esc($page->slug) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-bold">Content (HTML) *</label>
                        <textarea name="content" id="pageContentTextarea" class="code-editor-textarea" required><?= esc(old('content', $page->content ?? '')) ?></textarea>
                        <small class="text-muted">The content is rendered as HTML inside the site layout, exactly as written. The About, Terms and Privacy pages keep their page styles and scripts; only this body content is replaced.</small>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR SETTINGS -->
            <div class="col-xl-4">
                <div class="card custom-card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Page Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Page Title *</label>
                            <input type="text" name="title" class="form-control" maxlength="255"
                                   value="<?= esc(old('title', $page->title ?? '')) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Slug *</label>
                            <input type="text" name="slug" class="form-control font-monospace" maxlength="100"
                                   value="<?= esc(old('slug', $page->slug ?? '')) ?>" <?= $page ? 'readonly' : 'required' ?>>
                            <?php if ($page): ?>
                                <small class="text-muted">The slug cannot be changed: the built-in pages are matched by it, and public links use it.</small>
                            <?php else: ?>
                                <small class="text-muted">Lowercase letters, numbers, dashes and underscores. The page will be published at <code>/page/&lt;slug&gt;</code>. The About, Terms and Privacy pages keep their existing web addresses.</small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status *</label>
                            <select name="is_active" class="form-select">
                                <option value="1" <?= (old('is_active', $page->is_active ?? 1) == 1) ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= (old('is_active', $page->is_active ?? 1) == 0) ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <small class="text-muted">An inactive built-in page falls back to its built-in content.</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="pageSubmitBtn">
                                <i class="ti ti-device-floppy me-1"></i> Save Page
                            </button>
                        </div>
                    </div>
                </div>
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

    document.getElementById('pageForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('pageSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch("<?= base_url('admin/pages/save') ?>", {
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
                    setTimeout(() => location.href = "<?= base_url('admin/pages') ?>", 700);
                } else {
                    toastr.error(res.message || 'Save failed');
                }
            })
            .catch(() => toastr.error('Server error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Save Page';
            });
    });
</script>

<?= $this->endSection() ?>
