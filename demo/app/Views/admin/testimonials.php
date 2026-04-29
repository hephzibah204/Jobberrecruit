<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .testimonial-message {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .rating-stars {
        color: #f5c518;
        font-size: 14px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Testimonials</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Testimonials</li>
                </ol>
            </div>

            <button class="btn btn-primary" onclick="openTestimonialModal()">
                <i class="ti ti-plus"></i> New Testimonial
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card custom-card">
        <div class="card-body p-0 mb-2">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($testimonials)): ?>
                            <?php foreach ($testimonials as $testimonial): ?>
                                <tr>
                                    <td>
                                        <div class="fw-medium"><?= esc($testimonial->name) ?></div>
                                        <div class="text-muted small testimonial-message">
                                            <?= esc(character_limiter($testimonial->message, 90)) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <?= esc($testimonial->role ?? '-') ?>
                                        <?php if ($testimonial->company): ?>
                                            <div class="text-muted small">
                                                <?= esc($testimonial->company) ?>
                                            </div>
                                        <?php endif ?>
                                    </td>

                                    <td>
                                        <span class="rating-stars">
                                            <?= str_repeat('★', (int)$testimonial->rating) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= $testimonial->status === 'active' ? 'success' : 'secondary' ?>-transparent">
                                            <?= ucfirst($testimonial->status) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= $testimonial->is_featured ? 'Yes' : 'No' ?>
                                    </td>

                                    <td><?= date('M d, Y', strtotime($testimonial->created_at)) ?></td>

                                    <td class="text-center">
                                        <button
                                            class="btn btn-sm btn-light border me-1"
                                            onclick="openTestimonialModal(<?= esc(json_encode($testimonial), 'attr') ?>)">
                                            <i class="ti ti-edit"></i>
                                        </button>

                                        <button
                                            class="btn btn-sm btn-danger border"
                                            onclick="deleteTestimonial(<?= $testimonial->id ?>)">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No testimonials available
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

<!-- MODAL -->
<div class="modal fade" id="testimonialModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="testimonialForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="testimonial_id">

            <div class="modal-header">
                <h5 class="modal-title">Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control" name="name" id="testimonial_name" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" name="role" id="testimonial_role">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Company</label>
                    <input type="text" class="form-control" name="company" id="testimonial_company">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Rating *</label>
                    <select class="form-select" name="rating" id="testimonial_rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>"><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
                        <?php endfor ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status *</label>
                    <select class="form-select" name="status" id="testimonial_status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Featured</label>
                    <select class="form-select" name="is_featured" id="testimonial_featured">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Avatar</label>
                    <input type="file" class="form-control" name="avatar" accept="image/*">

                    <input type="hidden" name="existing_avatar" id="existing_avatar">

                    <div id="avatarPreview" class="mt-2 d-none">
                        <img src="" class="img-thumbnail" style="max-height:120px;">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Message *</label>
                    <textarea class="form-control" name="message" id="testimonial_message" rows="4" required></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="testimonialSubmitBtn">
                    Save Testimonial
                </button>
            </div>
        </form>
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

    const testimonialModal = new bootstrap.Modal('#testimonialModal');

    function openTestimonialModal(data = null) {
        document.getElementById('testimonialForm').reset();
        document.getElementById('testimonial_id').value = '';

        if (data) {
            Object.keys(data).forEach(key => {
                const el = document.getElementById(`testimonial_${key}`);
                if (el) el.value = data[key];
            });
        }

        testimonialModal.show();
    }

    document.getElementById('testimonialForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('testimonialSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        if (data.avatar) {
            document.getElementById('existing_avatar').value = data.avatar;
            document.querySelector('#avatarPreview img').src = data.avatar;
            document.getElementById('avatarPreview').classList.remove('d-none');
        }

        fetch("<?= base_url('admin/testimonials/save') ?>", {
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
                btn.innerHTML = 'Save Testimonial';
            });
    });

    function deleteTestimonial(id) {
        if (!confirm('Delete this testimonial?')) return;

        fetch(`<?= base_url('admin/testimonials/delete') ?>/${id}`, {
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

    document.querySelector('input[name="avatar"]').addEventListener('change', function() {
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
            document.querySelector('#avatarPreview img').src = e.target.result;
            document.getElementById('avatarPreview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>

<?= $this->endSection() ?>