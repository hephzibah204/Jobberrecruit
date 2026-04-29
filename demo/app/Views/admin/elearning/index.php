<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">E-Learning Management</h1>
            <div class="ms-md-1 ms-0">
                <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    <i class="ti ti-plus me-1"></i> Add New Course
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Manage Course Catalog</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Instructor</th>
                                        <th>Price</th>
                                        <th>Source</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($courses)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-5">No courses yet. Use the add course button to create your first course.</td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-sm me-2">
                                                        <img src="<?= $course->thumbnail ? base_url($course->thumbnail) : 'https://placehold.co/100x100?text=Course' ?>" alt="">
                                                    </span>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($course->title) ?></div>
                                                        <div class="text-muted fs-12"><?= esc($course->duration ?: 'Self-paced') ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= esc($course->instructor) ?></td>
                                            <td><?= $course->price > 0 ? '₦' . number_format($course->price, 2) : 'Free' ?></td>
                                            <td>
                                                <span class="badge bg-info-transparent"><?= ucfirst((string) ($course->content_source ?? 'none')) ?></span>
                                            </td>
                                            <td><span class="badge bg-primary-transparent"><?= ucfirst((string) ($course->level ?? 'beginner')) ?></span></td>
                                            <td>
                                                <span class="badge <?= !empty($course->is_active) ? 'bg-success-transparent' : 'bg-danger-transparent' ?>">
                                                    <?= !empty($course->is_active) ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td><?= !empty($course->is_featured) ? '<span class="badge bg-warning-transparent">Featured</span>' : '<span class="text-muted">No</span>' ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-icon btn-info-light" data-bs-toggle="modal" data-bs-target="#editCourseModal<?= $course->id ?>">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <a href="<?= base_url('training/course/' . $course->id) ?>" class="btn btn-sm btn-icon btn-primary-light" target="_blank">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="editCourseModal<?= $course->id ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Course</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="<?= base_url('admin/elearning/save') ?>" method="POST" enctype="multipart/form-data">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="id" value="<?= $course->id ?>">
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Course Title</label>
                                                                    <input type="text" name="title" class="form-control" value="<?= esc($course->title) ?>" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Instructor Name</label>
                                                                    <input type="text" name="instructor" class="form-control" value="<?= esc($course->instructor) ?>" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Price (NGN)</label>
                                                                    <input type="number" step="0.01" name="price" class="form-control" value="<?= $course->price ?>" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Duration</label>
                                                                    <input type="text" name="duration" class="form-control" value="<?= esc($course->duration) ?>" placeholder="e.g. 4 Weeks" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Level</label>
                                                                    <select name="level" class="form-select">
                                                                        <option value="beginner" <?= $course->level === 'beginner' ? 'selected' : '' ?>>Beginner</option>
                                                                        <option value="intermediate" <?= $course->level === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                                                                        <option value="advanced" <?= $course->level === 'advanced' ? 'selected' : '' ?>>Advanced</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Content Source</label>
                                                                    <select name="content_source" class="form-select">
                                                                        <option value="none" <?= ($course->content_source ?? 'none') === 'none' ? 'selected' : '' ?>>None</option>
                                                                        <option value="youtube" <?= ($course->content_source ?? 'none') === 'youtube' ? 'selected' : '' ?>>YouTube</option>
                                                                        <option value="upload" <?= ($course->content_source ?? 'none') === 'upload' ? 'selected' : '' ?>>Upload</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <label class="form-label">YouTube URL</label>
                                                                    <input type="url" name="youtube_url" class="form-control" value="<?= esc($course->youtube_url ?? '') ?>" placeholder="https://www.youtube.com/watch?v=...">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label class="form-label">Description</label>
                                                                    <textarea name="description" class="form-control" rows="4" required><?= esc($course->description) ?></textarea>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Thumbnail</label>
                                                                    <input type="file" name="thumbnail" class="form-control">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Upload Course File</label>
                                                                    <input type="file" name="content_file" class="form-control">
                                                                    <?php if (! empty($course->content_file)): ?>
                                                                        <small class="text-muted d-block mt-1">Existing file: <?= esc(basename((string) $course->content_file)) ?></small>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Status</label>
                                                                    <select name="status" class="form-select">
                                                                        <option value="active" <?= !empty($course->is_active) ? 'selected' : '' ?>>Active</option>
                                                                        <option value="inactive" <?= empty($course->is_active) ? 'selected' : '' ?>>Inactive</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" <?= !empty($course->is_featured) ? 'checked' : '' ?>>
                                                                        <label class="form-check-label">Feature this course on the landing page</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update Course</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/elearning/save') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Course Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Fullstack Web Dev" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Instructor Name</label>
                            <input type="text" name="instructor" class="form-control" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price (NGN)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="0.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duration</label>
                            <input type="text" name="duration" class="form-control" placeholder="e.g. 4 Weeks" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-select">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Content Source</label>
                                            <select name="content_source" class="form-select">
                                                <option value="none">None</option>
                                                <option value="youtube">YouTube</option>
                                                <option value="upload">Upload</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">YouTube URL</label>
                                            <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Course details..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Thumbnail</label>
                            <input type="file" name="thumbnail" class="form-control">
                        </div>
                        <div class="col-md-6">
                                            <label class="form-label">Upload Course File</label>
                                            <input type="file" name="content_file" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_featured" value="1">
                                                <label class="form-check-label">Feature this course</label>
                                            </div>
                                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Course</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
