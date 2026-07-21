<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
    /* Quill editor sizing */
    .blog-editor {
        min-height: 260px;
    }

    .blog-editor .ql-editor {
        min-height: 260px;
        max-height: 420px;
        overflow-y: auto;
    }

    /* Blog table excerpt clamp */
    .blog-excerpt {
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
                <h1 class="page-title fw-medium fs-18 mb-0">Blog</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Blog</li>
                </ol>
            </div>

            <a href="<?= base_url('admin/blogs/create') ?>" class="btn btn-primary">
                <i class="ti ti-plus"></i> Create Blog
            </a>
        </div>
    </div>

    <!-- BLOG TABLE -->
    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($blogs)): ?>
                            <?php foreach ($blogs as $blog): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">
                                                <?= esc($blog->title) ?>
                                            </span>
                                            <span class="text-muted small mt-1 blog-excerpt">
                                                <?= esc(character_limiter(strip_tags($blog->excerpt), 100)) ?>
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= $blog->status === 'published' ? 'success' : 'secondary' ?>-transparent">
                                            <?= ucfirst($blog->status) ?>
                                        </span>
                                    </td>

                                    <td><?= date('M d, Y', strtotime($blog->created_at)) ?></td>

                                    <td class="text-center">
                                        <a href="<?= base_url('admin/blogs/edit/' . $blog->id) ?>" class="btn btn-sm btn-light border me-1">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <button
                                            class="btn btn-sm btn-danger border"
                                            onclick="deleteBlog(<?= $blog->id ?>)">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No blogs created yet
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
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

    function deleteBlog(id) {
        if (!confirm('Delete this blog post?')) return;

        fetch(`<?= base_url('admin/blogs/delete') ?>/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
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