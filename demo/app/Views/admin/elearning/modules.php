<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Manage Modules: <?= esc($course->title) ?></h1>
            <div class="ms-md-1 ms-0">
                <a href="<?= base_url('admin/elearning') ?>" class="btn btn-light btn-wave me-2">
                    <i class="ti ti-arrow-left me-1"></i> Back to Courses
                </a>
                <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                    <i class="ti ti-plus me-1"></i> Add New Module
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Course Modules / Videos</div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($modules)): ?>
                            <div class="text-center text-muted py-5">
                                <i class="ti ti-video fs-1"></i>
                                <p class="mt-2">No modules yet. Click "Add New Module" to create your curriculum.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-nowrap table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Title</th>
                                            <th>Content Source</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($modules as $module): ?>
                                            <tr>
                                                <td><?= esc($module->order_index) ?></td>
                                                <td>
                                                    <div class="fw-semibold"><?= esc($module->title) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info-transparent"><?= ucfirst((string) ($module->content_source ?? 'none')) ?></span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-icon btn-info-light" data-bs-toggle="modal" data-bs-target="#editModuleModal<?= $module->id ?>" title="Edit Module">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <form action="<?= base_url('admin/elearning/modules/delete/' . $module->id) ?>" method="POST" class="d-inline">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-icon btn-danger-light" onclick="return confirm('Are you sure you want to delete this module?')" title="Delete Module">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Edit Module Modal -->
                                            <div class="modal fade" id="editModuleModal<?= $module->id ?>" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Module</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="<?= base_url('admin/elearning/modules/save') ?>" method="POST" enctype="multipart/form-data">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="id" value="<?= $module->id ?>">
                                                            <input type="hidden" name="course_id" value="<?= $course->id ?>">
                                                            
                                                            <div class="modal-body">
                                                                <div class="row g-3">
                                                                    <div class="col-md-8">
                                                                        <label class="form-label">Module Title</label>
                                                                        <input type="text" name="title" class="form-control" value="<?= esc($module->title) ?>" required>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Order Index</label>
                                                                        <input type="number" name="order_index" class="form-control" value="<?= $module->order_index ?>" required>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Content Source</label>
                                                                        <select name="content_source" class="form-select">
                                                                            <option value="none" <?= $module->content_source === 'none' ? 'selected' : '' ?>>None</option>
                                                                            <option value="youtube" <?= $module->content_source === 'youtube' ? 'selected' : '' ?>>YouTube</option>
                                                                            <option value="upload" <?= $module->content_source === 'upload' ? 'selected' : '' ?>>Upload Video/File</option>
                                                                            <option value="text" <?= $module->content_source === 'text' ? 'selected' : '' ?>>Text Only</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <label class="form-label">YouTube URL</label>
                                                                        <input type="url" name="youtube_url" class="form-control" value="<?= esc($module->youtube_url) ?>" placeholder="https://www.youtube.com/watch?v=...">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label class="form-label">Description (Optional)</label>
                                                                        <textarea name="description" class="form-control" rows="3"><?= esc($module->description) ?></textarea>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label class="form-label">Upload Content File (Video or PDF)</label>
                                                                        <input type="file" name="content_file" class="form-control">
                                                                        <?php if (!empty($module->content_file)): ?>
                                                                            <small class="text-muted d-block mt-1">Existing file: <?= esc(basename((string) $module->content_file)) ?></small>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Update Module</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Module Modal -->
<div class="modal fade" id="addModuleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/elearning/modules/save') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="course_id" value="<?= $course->id ?>">
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Module Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Introduction to CSS" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Order Index</label>
                            <input type="number" name="order_index" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Content Source</label>
                            <select name="content_source" class="form-select">
                                <option value="none">None</option>
                                <option value="youtube">YouTube</option>
                                <option value="upload">Upload Video/File</option>
                                <option value="text">Text Only</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">YouTube URL</label>
                            <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Module description..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Upload Content File (Video or PDF)</label>
                            <input type="file" name="content_file" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Module</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
