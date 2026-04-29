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

            <button class="btn btn-primary" onclick="openBlogModal()">
                <i class="ti ti-plus"></i> Create Blog
            </button>
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
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light border me-1 edit-blog-btn"
                                            onclick="openBlogModal(<?= esc(json_encode($blog), 'attr') ?>)"
                                            data-blog='<?= esc(json_encode($blog), 'attr') ?>'>
                                            <i class="ti ti-edit"></i>
                                        </button>

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

<!-- BLOG MODAL -->
<div class="modal fade" id="blogModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form class="modal-content" id="blogForm" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="blog_id">
            <input type="hidden" name="existing_thumbnail" id="existing_thumbnail">

            <div class="modal-header">
                <h5 class="modal-title">Blog Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">

                <!-- Title -->
                <div class="col-12">
                    <label class="form-label">Title *</label>
                    <input type="text" class="form-control" name="title" id="blog_title"
                        required oninput="generateSlug(this.value)">
                </div>

                <!-- Slug -->
                <div class="col-12">
                    <label class="form-label">Slug *</label>
                    <input type="text" class="form-control" name="slug" id="blog_slug" required>
                </div>

                <!-- Thumbnail -->
                <div class="col-12">
                    <label class="form-label">Thumbnail</label>
                    <input type="file" class="form-control" name="thumbnail" accept="image/*">

                    <div id="thumbnailPreview" class="mt-2 d-none">
                        <img src="" class="img-thumbnail" style="max-height:120px;">
                    </div>
                </div>

                <!-- Excerpt -->
                <div class="col-12">
                    <label class="form-label">Excerpt</label>
                    <textarea class="form-control" name="excerpt" id="blog_excerpt" rows="2"></textarea>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select class="form-select" name="status" id="blog_status">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>

                <!-- SEO -->
                <div class="col-12">
                    <label class="form-label">Meta Title</label>
                    <input type="text" class="form-control" name="meta_title" id="blog_meta_title">
                </div>

                <div class="col-12">
                    <label class="form-label">Meta Description</label>
                    <textarea class="form-control" name="meta_description"
                        id="blog_meta_description" rows="2"></textarea>
                </div>

                <!-- Content -->
                <div class="col-12">
                    <label class="form-label">Content *</label>
                    <div id="blog_quill" class="form-control blog-editor"></div>
                    <input type="hidden" name="content" id="blog_content">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="blogSubmitBtn">Save Blog</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<!-- QUILL -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right'
    };

    const blogModal = new bootstrap.Modal('#blogModal');

    const quill = new Quill('#blog_quill', {
        theme: 'snow',
        placeholder: 'Write blog content here...',
        modules: {
            toolbar: {
                container: [
                    [{
                        header: [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['link', 'image', 'blockquote', 'code-block'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['clean']
                ],
                handlers: {
                    image: imageHandler
                }
            }
        }
    });

    quill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById('blog_content').value = quill.root.innerHTML;
    });

    function openBlogModal(blog = null) {
        document.getElementById('blogForm').reset();
        quill.setText('');

        document.getElementById('blog_id').value = '';
        document.getElementById('existing_thumbnail').value = '';
        document.getElementById('thumbnailPreview').classList.add('d-none');

        if (blog) {
            document.getElementById('blog_id').value = blog.id;
            document.getElementById('blog_title').value = blog.title;
            document.getElementById('blog_slug').value = blog.slug;
            document.getElementById('blog_excerpt').value = blog.excerpt ?? '';
            document.getElementById('blog_status').value = blog.status;
            document.getElementById('blog_meta_title').value = blog.meta_title ?? '';
            document.getElementById('blog_meta_description').value = blog.meta_description ?? '';
            quill.root.innerHTML = blog.content;

            if (blog.thumbnail) {
                document.getElementById('existing_thumbnail').value = blog.thumbnail;
                document.querySelector('#thumbnailPreview img').src = blog.thumbnail;
                document.getElementById('thumbnailPreview').classList.remove('d-none');
            }
        }

        blogModal.show();
    }

    function generateSlug(text) {
        document.getElementById('blog_slug').value = text
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }

    document.getElementById('blogForm').addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('blog_content').value = quill.root.innerHTML;

        const btn = document.getElementById('blogSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch("<?= base_url('admin/blogs/save') ?>", {
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
                    setTimeout(() => location.reload(), 700);
                } else {
                    toastr.error(res.message || 'Save failed');
                }
            })
            .catch(() => toastr.error('Server error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Save Blog';
            });
    });

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

    function imageHandler() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.click();

        input.onchange = async () => {
            const file = input.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                toastr.error('Only images allowed');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                toastr.error('Image must be less than 2MB');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            try {
                const res = await fetch("<?= base_url('admin/blogs/upload-editor-image') ?>", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    const range = quill.getSelection();
                    quill.insertEmbed(range.index, 'image', data.url);
                } else {
                    toastr.error(data.message);
                }
            } catch {
                toastr.error('Upload failed');
            }
        };
    }

    let slugTimeout = null;

    document.getElementById('blog_slug').addEventListener('input', function() {
        clearTimeout(slugTimeout);

        slugTimeout = setTimeout(() => {
            const slugInput = this;
            const id = document.getElementById('blog_id').value;

            fetch("<?= base_url('admin/blogs/check-slug') ?>", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        slug: slugInput.value,
                        id
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (!res.valid && res.slug) {
                        slugInput.value = res.slug;
                        toastr.info('Slug already exists. Updated automatically.');
                    }

                    slugInput.classList.toggle('is-invalid', !res.valid);
                });
        }, 400);
    });

    document.getElementById('blog_title').addEventListener('blur', function() {
        const titleInput = this;
        const id = document.getElementById('blog_id').value;

        fetch("<?= base_url('admin/blogs/check-title') ?>", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    title: titleInput.value,
                    id
                })
            })
            .then(res => res.json())
            .then(res => {
                if (!res.valid && res.title) {
                    titleInput.value = res.title;
                    generateSlug(res.title);
                    toastr.info('Title already exists. Updated automatically.');
                }
            });
    });

    const thumbnailInput = document.querySelector('input[name="thumbnail"]');
    const previewWrapper = document.getElementById('thumbnailPreview');
    const previewImg = previewWrapper.querySelector('img');

    thumbnailInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            toastr.error('Invalid image file');
            this.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            toastr.error('Image must be less than 2MB');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            previewWrapper.classList.remove('d-none');
        };

        reader.readAsDataURL(file);
    });
</script>

<?= $this->endSection() ?>