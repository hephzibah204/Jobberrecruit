<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .page-excerpt {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Pages</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Pages</li>
                </ol>
            </div>

            <a href="<?= base_url('admin/pages/create') ?>" class="btn btn-primary">
                <i class="ti ti-plus"></i> New Page
            </a>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card custom-card">
        <div class="card-body p-0 mb-2">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Page</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pages)): ?>
                            <?php
                            // The built-in pages keep their long-standing public
                            // URLs; pages created here are served at /page/<slug>.
                            $pageUrls = [
                                'about'   => 'about-us',
                                'terms'   => 'terms-and-conditions',
                                'privacy' => 'privacy-policy',
                            ];
                            ?>
                            <?php foreach ($pages as $page): ?>
                                <tr>
                                    <td>
                                        <div class="fw-medium"><?= esc($page->title) ?></div>
                                        <div class="text-muted small page-excerpt">
                                            <?= esc(character_limiter(strip_tags($page->content), 90)) ?>
                                        </div>
                                    </td>

                                    <td><code><?= esc($page->slug) ?></code></td>

                                    <td>
                                        <span class="badge bg-<?= $page->is_active ? 'success' : 'secondary' ?>-transparent">
                                            <?= $page->is_active ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>

                                    <td><?= $page->updated_at ? date('M d, Y', strtotime($page->updated_at)) : '-' ?></td>

                                    <td class="text-center">
                                        <a
                                            href="<?= base_url($pageUrls[$page->slug] ?? 'page/' . $page->slug) ?>"
                                            target="_blank"
                                            class="btn btn-sm btn-light border me-1">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        <a
                                            href="<?= base_url('admin/pages/edit/' . $page->id) ?>"
                                            class="btn btn-sm btn-light border me-1">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <button
                                            class="btn btn-sm btn-danger border"
                                            onclick="deletePage(<?= $page->id ?>)">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No pages available. The About, Terms and Privacy pages are showing their built-in content until pages are added here.
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right'
    };

    function deletePage(id) {
        if (!confirm('Delete this page? Built-in pages will fall back to their built-in content.')) return;

        fetch(`<?= base_url('admin/pages/delete') ?>/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 600);
                } else {
                    toastr.error(res.message);
                }
            })
            .catch(() => toastr.error('Server error'));
    }
</script>

<?= $this->endSection() ?>
