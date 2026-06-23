<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .ck-editor__editable_inline {
        min-height: 400px;
        max-height: 600px;
        overflow-y: auto;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0"><?= $blog ? 'Edit Blog' : 'Create Blog' ?></h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/blogs') ?>">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $blog ? 'Edit' : 'Create' ?></li>
                </ol>
            </div>
            <a href="<?= base_url('admin/blogs') ?>" class="btn btn-light"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>

    <form id="blogForm" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="blog_id" value="<?= $blog ? $blog->id : '' ?>">
        <input type="hidden" name="existing_thumbnail" id="existing_thumbnail" value="<?= $blog ? esc($blog->thumbnail, 'attr') : '' ?>">

        <div class="row g-4">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <input type="text" class="form-control form-control-lg border-0 fw-bold fs-3 px-0 pb-2 border-bottom rounded-0" name="title" id="blog_title"
                                placeholder="Add title" required oninput="generateSlug(this.value)" value="<?= $blog ? esc($blog->title, 'attr') : '' ?>" style="box-shadow: none;">
                        </div>
                        <div class="mb-0">
                            <textarea name="content" id="blog_content" class="form-control"><?= $blog ? esc($blog->content) : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold mb-0">Search Engine Optimization</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Meta Title</label>
                            <input type="text" class="form-control" name="meta_title" id="blog_meta_title" value="<?= $blog ? esc($blog->meta_title ?? '', 'attr') : '' ?>">
                        </div>
                        <div>
                            <label class="form-label text-muted small fw-bold">Meta Description</label>
                            <textarea class="form-control" name="meta_description" id="blog_meta_description" rows="3"><?= $blog ? esc($blog->meta_description ?? '') : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Area -->
            <div class="col-lg-4">
                <!-- Publish Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-3">
                        <h6 class="fw-bold mb-0">Publish</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Status *</label>
                            <select class="form-select" name="status" id="blog_status">
                                <option value="draft" <?= ($blog && $blog->status == 'draft') ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= ($blog && $blog->status == 'published') ? 'selected' : '' ?>>Published</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">URL Slug *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small px-2">/</span>
                                <input type="text" class="form-control" name="slug" id="blog_slug" required value="<?= $blog ? esc($blog->slug, 'attr') : '' ?>">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 pt-3 mt-2 border-top">
                            <a href="<?= base_url('admin/blogs') ?>" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="blogSubmitBtn">Save Post</button>
                        </div>
                    </div>
                </div>

                <!-- Categories & Tags Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-3">
                        <h6 class="fw-bold mb-0">Tags</h6>
                    </div>
                    <div class="card-body p-3">
                        <input type="text" class="form-control" name="tags" id="blog_tags" placeholder="e.g., job search, career" value="<?= $blog ? esc($blog->tags ?? '', 'attr') : '' ?>">
                        <div class="form-text mt-2 small">Separate tags with commas</div>
                    </div>
                </div>

                <!-- Featured Image Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-3">
                        <h6 class="fw-bold mb-0">Featured Image</h6>
                    </div>
                    <div class="card-body p-3 text-center">
                        <input type="file" class="d-none" name="thumbnail" id="thumbnail_input" accept="image/*">
                        <div id="thumbnailPreview" class="mb-3 <?= $blog && $blog->thumbnail ? '' : 'd-none' ?>">
                            <img src="<?= $blog && $blog->thumbnail ? esc($blog->thumbnail, 'attr') : '' ?>" class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; max-height: 200px;">
                        </div>
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="document.getElementById('thumbnail_input').click()" style="border-style: dashed;">
                            <i class="ti ti-upload me-2"></i> <?= $blog && $blog->thumbnail ? 'Change' : 'Set' ?> featured image
                        </button>
                    </div>
                </div>

                <!-- Excerpt Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-3">
                        <h6 class="fw-bold mb-0">Excerpt</h6>
                    </div>
                    <div class="card-body p-3">
                        <textarea class="form-control" name="excerpt" id="blog_excerpt" rows="4" placeholder="Write an excerpt (optional)"><?= $blog ? esc($blog->excerpt ?? '') : '' ?></textarea>
                        <div class="form-text mt-2 small">Excerpts are optional hand-crafted summaries of your content.</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
    // Toastr configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right'
    };

    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('blog_content')) return;

        if (window.CKEDITOR) {
            CKEDITOR.replace('blog_content', {
                height: 400,
                removePlugins: 'exportpdf',
                filebrowserUploadUrl: "<?= base_url('admin/blogs/upload-editor-image-ck4') ?>?_token=<?= csrf_hash() ?>",
                filebrowserUploadMethod: 'form',
                // Enable all features and layout options
                allowedContent: true,
                extraPlugins: 'justify,colorbutton,font,smiley,image2,sourcearea,sourcedialog',
                toolbarGroups: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    '/',
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                    '/',
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    { name: 'about', groups: [ 'about' ] }
                ],
                removeButtons: 'Source,Save,NewPage,Preview,Print,Templates'
            });
        } else {
            console.error('CKEditor failed to load.');
        }
    });

    function generateSlug(text) {
        const isEdit = document.getElementById('blog_id').value !== '';
        if (isEdit) return; // don't override slug if editing
        document.getElementById('blog_slug').value = text
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }

    document.getElementById('blogForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (window.CKEDITOR) {
            for (var instanceName in CKEDITOR.instances) {
                CKEDITOR.instances[instanceName].updateElement();
            }
        }

        const btn = document.getElementById('blogSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch("<?= base_url('admin/blogs/save') ?>", {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(this)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(() => window.location.href = "<?= base_url('admin/blogs') ?>", 700);
                } else {
                    toastr.error(res.message || 'Save failed');
                    btn.disabled = false;
                    btn.innerHTML = 'Save Post';
                }
            })
            .catch(() => {
                toastr.error('Server error');
                btn.disabled = false;
                btn.innerHTML = 'Save Post';
            });
    });

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
                    body: new URLSearchParams({ slug: slugInput.value, id })
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
        if (id) return; // Don't check title if editing

        fetch("<?= base_url('admin/blogs/check-title') ?>", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ title: titleInput.value, id })
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
            document.querySelector('#thumbnail_input + div + button').innerHTML = '<i class="ti ti-upload me-2"></i> Change featured image';
        };
        reader.readAsDataURL(file);
    });
</script>
<?= $this->endSection() ?>
