<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid page-container main-body-container">
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
                                    <th>Type</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($courses)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">No courses yet. Use the add course button to create your first course.</td>
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
                                        <td><?= (float)($course->price ?? 0) > 0 ? '₦' . number_format((float)$course->price, 2) : 'Free' ?></td>
                                        <td>
                                            <span class="badge bg-info-transparent"><?= ucfirst((string) ($course->content_source ?? 'none')) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge <?= ($course->item_type ?? 'course') === 'ebook' ? 'bg-success' : 'bg-primary' ?>"><?= ucfirst($course->item_type ?? 'course') ?></span>
                                        </td>
                                        <td><span class="badge bg-primary-transparent"><?= ucfirst((string) ($course->level ?? 'beginner')) ?></span></td>
                                        <td>
                                            <span class="badge <?= !empty($course->is_active) ? 'bg-success-transparent' : 'bg-danger-transparent' ?>">
                                                <?= !empty($course->is_active) ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td><?= !empty($course->is_featured) ? '<span class="badge bg-warning-transparent">Featured</span>' : '<span class="text-muted">No</span>' ?></td>
                                        <td class="text-nowrap">
                                            <a href="<?= base_url('admin/elearning/modules/' . $course->id) ?>" class="btn btn-sm btn-icon btn-secondary-light" title="Manage Modules">
                                                <i class="ti ti-list"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-icon btn-purple-light" onclick="openAiTestModal(<?= $course->id ?>, '<?= esc(addslashes($course->title)) ?>')" title="AI Generate Test">
                                                <i class="ti ti-sparkles"></i>
                                            </button>
                                            <button class="btn btn-sm btn-icon btn-info-light" data-bs-toggle="modal" data-bs-target="#editCourseModal<?= $course->id ?>" title="Edit Course">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <form method="POST" action="<?= base_url('admin/elearning/toggle-status/' . $course->id) ?>" class="d-inline" onsubmit="return confirm('<?= !empty($course->is_active) ? 'Unpublish this course?' : 'Publish this course?' ?>')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-icon <?= !empty($course->is_active) ? 'btn-warning-light' : 'btn-success-light' ?>" title="<?= !empty($course->is_active) ? 'Unpublish' : 'Publish' ?>">
                                                    <i class="ti <?= !empty($course->is_active) ? 'ti-eye-off' : 'ti-eye-check' ?>"></i>
                                                </button>
                                            </form>
                                            <a href="<?= base_url('training/course/' . $course->id) ?>" class="btn btn-sm btn-icon btn-primary-light" target="_blank" title="View Course">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <form method="POST" action="<?= base_url('admin/elearning/delete/' . $course->id) ?>" class="d-inline" onsubmit="return confirm('Delete this course permanently? This cannot be undone.')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-icon btn-danger-light" title="Delete Course">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
            <div class="modal-header flex-shrink-0 bg-light border-bottom">
                <h5 class="modal-title fw-bold" id="addCourseModalLabel"><i class="ti ti-plus-circle text-primary me-2"></i>Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/elearning/save') ?>" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100" style="overflow: hidden; min-height: 0;">
                <?= csrf_field() ?>
                <div class="modal-body" style="overflow-y: auto; flex: 1 1 auto; max-height: calc(90vh - 140px); padding: 1.5rem;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Course Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Fullstack Web Development" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Instructor Name</label>
                            <input type="text" name="instructor" class="form-control" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Price (NGN)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="0.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Item Type</label>
                            <select name="item_type" class="form-select">
                                <option value="course">Course</option>
                                <option value="ebook">eBook (PDF)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Duration</label>
                            <div class="input-group">
                                <input type="number" min="1" step="any" name="duration_value" class="form-control" placeholder="e.g. 5" required>
                                <select name="duration_unit" class="form-select" style="max-width: 105px;">
                                    <option value="Days">Days</option>
                                    <option value="Hours">Hours</option>
                                </select>
                            </div>
                            <div class="form-text text-muted fs-11">Duration in days or hours</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Level</label>
                            <select name="level" class="form-select">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Primary Content Source</label>
                            <select name="content_source" class="form-select">
                                <option value="none">None</option>
                                <option value="youtube">YouTube</option>
                                <option value="upload">Upload</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">YouTube URL</label>
                            <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Course details..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thumbnail</label>
                            <input type="file" name="thumbnail" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Upload Course File</label>
                            <input type="file" name="content_file" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1">
                                <label class="form-check-label fw-semibold">Feature this course on the landing page</label>
                            </div>
                        </div>

                        <!-- Course Modules & Curriculum Builder -->
                        <div class="col-12 mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1 text-primary"><i class="ti ti-books me-1"></i> Course Curriculum Modules</h6>
                                    <div class="text-muted fs-12">Add different chapters, video lessons, or modules for this course.</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addModuleRowToAddCourse()">
                                    <i class="ti ti-plus me-1"></i> Add Module
                                </button>
                            </div>
                            <div id="addCourseModulesContainer" class="d-flex flex-column gap-3">
                                <!-- Default Module 1 -->
                                <div class="card p-3 border bg-light module-row shadow-none mb-0">
                                    <input type="hidden" name="module_ids[]" value="">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold fs-13 text-secondary"><i class="ti ti-video me-1"></i> Module 1</span>
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" onclick="this.closest('.module-row').remove()"><i class="ti ti-trash"></i> Remove</button>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-7">
                                            <label class="form-label fs-12 mb-1">Module Title</label>
                                            <input type="text" name="module_titles[]" class="form-control form-control-sm" placeholder="e.g. Module 1: Introduction &amp; Setup">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fs-12 mb-1">Content Source</label>
                                            <select name="module_sources[]" class="form-select form-select-sm" onchange="toggleModuleYt(this)">
                                                <option value="none">No Video (Text / Overview Only)</option>
                                                <option value="youtube">YouTube Video</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 module-yt-box d-none">
                                            <label class="form-label fs-12 mb-1">YouTube Video URL</label>
                                            <input type="url" name="module_youtube_urls[]" class="form-control form-control-sm" placeholder="https://www.youtube.com/watch?v=...">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fs-12 mb-1">Module Description / Key Notes</label>
                                            <textarea name="module_descriptions[]" class="form-control form-control-sm" rows="2" placeholder="Brief details about what will be covered in this module..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-shrink-0 bg-light border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="ti ti-check me-1"></i> Create Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modals -->
<?php foreach ($courses as $course): ?>
    <div class="modal fade" id="editCourseModal<?= $course->id ?>" tabindex="-1" aria-labelledby="editCourseModalLabel<?= $course->id ?>" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
                <div class="modal-header flex-shrink-0 bg-light border-bottom">
                    <h5 class="modal-title fw-bold" id="editCourseModalLabel<?= $course->id ?>"><i class="ti ti-edit text-info me-2"></i>Edit Course: <?= esc($course->title) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('admin/elearning/save') ?>" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100" style="overflow: hidden; min-height: 0;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $course->id ?>">
                    <div class="modal-body" style="overflow-y: auto; flex: 1 1 auto; max-height: calc(90vh - 140px); padding: 1.5rem;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Course Title</label>
                                <input type="text" name="title" class="form-control" value="<?= esc($course->title) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Instructor Name</label>
                                <input type="text" name="instructor" class="form-control" value="<?= esc($course->instructor) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Price (NGN)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="<?= $course->price ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Item Type</label>
                                <select name="item_type" class="form-select">
                                    <option value="course" <?= ($course->item_type ?? 'course') === 'course' ? 'selected' : '' ?>>Course</option>
                                    <option value="ebook" <?= ($course->item_type ?? 'course') === 'ebook' ? 'selected' : '' ?>>eBook (PDF)</option>
                                </select>
                            </div>
                            <?php
                                $durVal = '';
                                $durUnit = 'Days';
                                if (!empty($course->duration)) {
                                    if (preg_match('/(\d+(?:\.\d+)?)\s*(hour|hours|hr|hrs|h)/i', $course->duration, $m)) {
                                        $durVal = $m[1];
                                        $durUnit = 'Hours';
                                    } elseif (preg_match('/(\d+(?:\.\d+)?)\s*(day|days|d)/i', $course->duration, $m)) {
                                        $durVal = $m[1];
                                        $durUnit = 'Days';
                                    } elseif (preg_match('/(\d+(?:\.\d+)?)\s*(week|weeks|wk|wks|w)/i', $course->duration, $m)) {
                                        $durVal = floatval($m[1]) * 7;
                                        $durUnit = 'Days';
                                    } else {
                                        $durVal = preg_replace('/[^0-9.]/', '', $course->duration);
                                    }
                                }
                            ?>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Duration</label>
                                <div class="input-group">
                                    <input type="number" min="1" step="any" name="duration_value" class="form-control" value="<?= esc($durVal) ?>" placeholder="e.g. 5" required>
                                    <select name="duration_unit" class="form-select" style="max-width: 105px;">
                                        <option value="Days" <?= $durUnit === 'Days' ? 'selected' : '' ?>>Days</option>
                                        <option value="Hours" <?= $durUnit === 'Hours' ? 'selected' : '' ?>>Hours</option>
                                    </select>
                                </div>
                                <div class="form-text text-muted fs-11">Duration in days or hours</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Level</label>
                                <select name="level" class="form-select">
                                    <option value="beginner" <?= $course->level === 'beginner' ? 'selected' : '' ?>>Beginner</option>
                                    <option value="intermediate" <?= $course->level === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                                    <option value="advanced" <?= $course->level === 'advanced' ? 'selected' : '' ?>>Advanced</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Primary Content Source</label>
                                <select name="content_source" class="form-select">
                                    <option value="none" <?= ($course->content_source ?? 'none') === 'none' ? 'selected' : '' ?>>None</option>
                                    <option value="youtube" <?= ($course->content_source ?? 'none') === 'youtube' ? 'selected' : '' ?>>YouTube</option>
                                    <option value="upload" <?= ($course->content_source ?? 'none') === 'upload' ? 'selected' : '' ?>>Upload</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?= !empty($course->is_active) ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= empty($course->is_active) ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">YouTube URL</label>
                                <input type="url" name="youtube_url" class="form-control" value="<?= esc($course->youtube_url ?? '') ?>" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3" required><?= esc($course->description) ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Upload Course File</label>
                                <input type="file" name="content_file" class="form-control">
                                <?php if (! empty($course->content_file)): ?>
                                    <small class="text-muted d-block mt-1">Existing file: <?= esc(basename((string) $course->content_file)) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" <?= !empty($course->is_featured) ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold">Feature this course on the landing page</label>
                                </div>
                            </div>

                            <!-- Course Modules & Curriculum Builder -->
                            <div class="col-12 mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1 text-primary"><i class="ti ti-books me-1"></i> Course Curriculum Modules</h6>
                                        <div class="text-muted fs-12">Manage and edit the modules for this course.</div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addModuleRowToEditCourse(<?= $course->id ?>)">
                                        <i class="ti ti-plus me-1"></i> Add Module
                                    </button>
                                </div>
                                <div id="editCourseModulesContainer<?= $course->id ?>" class="d-flex flex-column gap-3">
                                    <div id="deletedModulesContainer<?= $course->id ?>"></div>
                                    <?php 
                                    $existingMods = $course->modules ?? [];
                                    if (empty($existingMods)):
                                    ?>
                                        <div class="text-center text-muted py-3 bg-light rounded border empty-modules-msg">
                                            <i class="ti ti-video fs-3 d-block mb-1"></i>
                                            No modules added yet. Click <strong>+ Add Module</strong> above to add curriculum chapters.
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($existingMods as $mIdx => $mod): 
                                            $mId = is_object($mod) ? $mod->id : $mod['id'];
                                            $mTitle = is_object($mod) ? $mod->title : $mod['title'];
                                            $mDesc = is_object($mod) ? ($mod->description ?? '') : ($mod['description'] ?? '');
                                            $mSource = is_object($mod) ? ($mod->content_source ?? 'none') : ($mod['content_source'] ?? 'none');
                                            $mYt = is_object($mod) ? ($mod->youtube_url ?? '') : ($mod['youtube_url'] ?? '');
                                        ?>
                                            <div class="card p-3 border bg-light module-row shadow-none mb-0">
                                                <input type="hidden" name="module_ids[]" value="<?= $mId ?>">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-bold fs-13 text-secondary"><i class="ti ti-video me-1"></i> Module <?= $mIdx + 1 ?></span>
                                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" onclick="removeExistingModuleRow(this, <?= $mId ?>, <?= $course->id ?>)"><i class="ti ti-trash"></i> Remove</button>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-md-7">
                                                        <label class="form-label fs-12 mb-1">Module Title</label>
                                                        <input type="text" name="module_titles[]" class="form-control form-control-sm" value="<?= esc($mTitle) ?>" placeholder="Module Title">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label fs-12 mb-1">Content Source</label>
                                                        <select name="module_sources[]" class="form-select form-select-sm" onchange="toggleModuleYt(this)">
                                                            <option value="none" <?= $mSource === 'none' ? 'selected' : '' ?>>No Video (Text / Overview Only)</option>
                                                            <option value="youtube" <?= $mSource === 'youtube' ? 'selected' : '' ?>>YouTube Video</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 module-yt-box <?= $mSource === 'youtube' ? '' : 'd-none' ?>">
                                                        <label class="form-label fs-12 mb-1">YouTube Video URL</label>
                                                        <input type="url" name="module_youtube_urls[]" class="form-control form-control-sm" value="<?= esc($mYt) ?>" placeholder="YouTube Video URL">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fs-12 mb-1">Module Description / Key Notes</label>
                                                        <textarea name="module_descriptions[]" class="form-control form-control-sm" rows="2" placeholder="Module description..."><?= esc($mDesc) ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex-shrink-0 bg-light border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="ti ti-check me-1"></i> Update Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- AI Generate & Custom Question Test Modal -->
<div class="modal fade" id="aiTestModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-transparent">
                <h5 class="modal-title fw-semibold text-primary"><i class="ti ti-sparkles me-2"></i>Course Test Builder &amp; Importer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="aiTestCourseId" value="">
                <div class="alert alert-info d-flex align-items-center mb-3">
                    <i class="ti ti-robot fs-4 me-2"></i>
                    <div>Managing assessment test questions for: <b id="aiTestCourseTitle"></b></div>
                </div>

                <!-- Nav Tabs for AI Generator, Aptitude Bank, and Custom Questions -->
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="tab-ai-gen" data-bs-toggle="tab" data-bs-target="#panel-ai-gen" type="button">
                            <i class="ti ti-wand me-1"></i> AI Generator (Gemini)
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="tab-aptitude-bank" data-bs-toggle="tab" data-bs-target="#panel-aptitude-bank" type="button" onclick="loadAptitudeTests()">
                            <i class="ti ti-database me-1"></i> Import Aptitude Test
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="tab-custom-q" data-bs-toggle="tab" data-bs-target="#panel-custom-q" type="button">
                            <i class="ti ti-plus me-1"></i> Add Manual Question
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab 1: AI Generator -->
                    <div class="tab-pane fade show active" id="panel-ai-gen" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Number of Questions</label>
                                <select id="aiTestNumQuestions" class="form-select">
                                    <option value="5">5 Questions</option>
                                    <option value="10" selected>10 Questions</option>
                                    <option value="20">20 Questions</option>
                                    <option value="30">30 Questions</option>
                                    <option value="40">40 Questions</option>
                                    <option value="50">50 Questions</option>
                                    <option value="75">75 Questions</option>
                                    <option value="100">100 Questions</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="btnGenAiTest" onclick="generateAiTest()">
                                    <i class="ti ti-wand me-1"></i> Generate Test with AI
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Aptitude Bank -->
                    <div class="tab-pane fade" id="panel-aptitude-bank" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Related Aptitude Test</label>
                                <select id="aptitudeTestSelect" class="form-select">
                                    <option value="">Loading aptitude tests...</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Questions Count</label>
                                <select id="aptitudeNumQuestions" class="form-select">
                                    <option value="5">5 Questions</option>
                                    <option value="10" selected>10 Questions</option>
                                    <option value="20">20 Questions</option>
                                    <option value="30">30 Questions</option>
                                    <option value="40">40 Questions</option>
                                    <option value="50">50 Questions</option>
                                    <option value="75">75 Questions</option>
                                    <option value="100">100 Questions</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-secondary w-100" id="btnImportAptitudeTest" onclick="importAptitudeTest()">
                                    <i class="ti ti-download me-1"></i> Import Test
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Custom Question Creator -->
                    <div class="tab-pane fade" id="panel-custom-q" role="tabpanel">
                        <div class="card p-3 border">
                            <div class="mb-2">
                                <label class="form-label fw-semibold">Question Stem / Text</label>
                                <input type="text" id="customQText" class="form-control" placeholder="e.g. Which of the following is a primary key requirement?">
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-text"><input type="radio" name="customCorrect" value="0" checked> A</div>
                                        <input type="text" id="customOptA" class="form-control" placeholder="Option A text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-text"><input type="radio" name="customCorrect" value="1"> B</div>
                                        <input type="text" id="customOptB" class="form-control" placeholder="Option B text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-text"><input type="radio" name="customCorrect" value="2"> C</div>
                                        <input type="text" id="customOptC" class="form-control" placeholder="Option C text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-text"><input type="radio" name="customCorrect" value="3"> D</div>
                                        <input type="text" id="customOptD" class="form-control" placeholder="Option D text">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <input type="text" id="customQExplanation" class="form-control form-control-sm" placeholder="Explanation (optional)">
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addCustomQuestionToTest()">
                                <i class="ti ti-plus me-1"></i> Add Question to Test Preview
                            </button>
                        </div>
                    </div>
                </div>

                <div id="aiTestStatus" class="mt-3 text-center d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted" id="aiTestStatusText">Processing assessment questions...</p>
                </div>

                <div id="aiTestPreview" class="mt-4 d-none">
                    <h6 class="fw-bold mb-3 d-flex justify-content-between align-items-center">
                        <span><i class="ti ti-list-check me-1"></i> Test Questions Preview (<span id="previewQCount">0</span>):</span>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAllPreviewQuestions()"><i class="ti ti-trash me-1"></i>Clear All</button>
                    </h6>
                    <div id="aiTestQuestionsContainer"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success d-none" id="btnSaveAiTest" onclick="saveGeneratedAiTest()">
                    <i class="ti ti-check me-1"></i> Save Test to Course
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentGeneratedQuestions = [];

function openAiTestModal(courseId, courseTitle) {
    document.getElementById('aiTestCourseId').value = courseId;
    document.getElementById('aiTestCourseTitle').textContent = courseTitle;
    document.getElementById('aiTestStatus').classList.add('d-none');
    document.getElementById('aiTestPreview').classList.add('d-none');
    document.getElementById('btnSaveAiTest').classList.add('d-none');
    document.getElementById('aiTestQuestionsContainer').innerHTML = '';
    currentGeneratedQuestions = [];
    var modal = new bootstrap.Modal(document.getElementById('aiTestModal'));
    modal.show();
}

function loadAptitudeTests() {
    var select = document.getElementById('aptitudeTestSelect');
    if (select.children.length > 1 && select.value !== '') return;

    fetch('<?= base_url("admin/elearning/aptitude-tests") ?>')
        .then(res => res.json())
        .then(data => {
            select.innerHTML = '';
            if (data.success && data.tests && data.tests.length > 0) {
                data.tests.forEach(t => {
                    var opt = document.createElement('option');
                    opt.value = t.id;
                    opt.textContent = t.title + ' (' + (t.difficulty || 'Intermediate') + ')';
                    select.appendChild(opt);
                });
            } else {
                select.innerHTML = '<option value="">No active aptitude tests found</option>';
            }
        })
        .catch(err => {
            select.innerHTML = '<option value="">Error loading aptitude tests</option>';
        });
}

function generateAiTest() {
    var courseId = document.getElementById('aiTestCourseId').value;
    var numQuestions = document.getElementById('aiTestNumQuestions').value;
    var btnGen = document.getElementById('btnGenAiTest');
    var statusDiv = document.getElementById('aiTestStatus');
    var previewDiv = document.getElementById('aiTestPreview');
    var container = document.getElementById('aiTestQuestionsContainer');

    btnGen.disabled = true;
    document.getElementById('aiTestStatusText').textContent = 'Gemini AI is crafting tailored assessment questions...';
    statusDiv.classList.remove('d-none');
    previewDiv.classList.add('d-none');

    fetch('<?= base_url("admin/elearning/generate-test") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>&course_id=' + courseId + '&num_questions=' + numQuestions
    })
    .then(res => res.json())
    .then(data => {
        btnGen.disabled = false;
        statusDiv.classList.add('d-none');
        if (data.success && data.questions) {
            renderQuestionsPreview(data.questions);
        } else {
            alert(data.message || 'Failed to generate AI test questions.');
        }
    })
    .catch(err => {
        btnGen.disabled = false;
        statusDiv.classList.add('d-none');
        alert('Network error while generating test questions.');
    });
}

function importAptitudeTest() {
    var courseId = document.getElementById('aiTestCourseId').value;
    var testId = document.getElementById('aptitudeTestSelect').value;
    var numQuestions = document.getElementById('aptitudeNumQuestions').value;
    var btnImp = document.getElementById('btnImportAptitudeTest');
    var statusDiv = document.getElementById('aiTestStatus');
    var previewDiv = document.getElementById('aiTestPreview');

    if (!testId) {
        alert('Please select an Aptitude Test to import questions from.');
        return;
    }

    btnImp.disabled = true;
    document.getElementById('aiTestStatusText').textContent = 'Importing questions from Aptitude Test bank...';
    statusDiv.classList.remove('d-none');
    previewDiv.classList.add('d-none');

    fetch('<?= base_url("admin/elearning/import-aptitude-questions") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>&course_id=' + courseId + '&test_id=' + testId + '&num_questions=' + numQuestions
    })
    .then(res => res.json())
    .then(data => {
        btnImp.disabled = false;
        statusDiv.classList.add('d-none');
        if (data.success && data.questions) {
            renderQuestionsPreview(data.questions);
        } else {
            alert(data.message || 'Failed to import aptitude test questions.');
        }
    })
    .catch(err => {
        btnImp.disabled = false;
        statusDiv.classList.add('d-none');
        alert('Network error while importing aptitude test questions.');
    });
}

function addCustomQuestionToTest() {
    var text = document.getElementById('customQText').value.trim();
    if (!text) {
        alert("Please enter question text.");
        return;
    }
    var optA = document.getElementById('customOptA').value.trim();
    var optB = document.getElementById('customOptB').value.trim();
    var optC = document.getElementById('customOptC').value.trim();
    var optD = document.getElementById('customOptD').value.trim();

    if (!optA || !optB) {
        alert("Please enter at least Option A and Option B.");
        return;
    }

    var radios = document.getElementsByName('customCorrect');
    var correctIdx = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) { correctIdx = parseInt(radios[i].value); break; }
    }

    var rawOpts = [optA, optB, optC, optD].filter(Boolean);
    var options = rawOpts.map((t, idx) => ({
        text: t,
        is_correct: idx === correctIdx ? 1 : 0
    }));

    var qObj = {
        question: text,
        explanation: document.getElementById('customQExplanation').value.trim(),
        options: options
    };

    currentGeneratedQuestions.push(qObj);
    renderQuestionsPreview(currentGeneratedQuestions);

    // Clear form inputs
    document.getElementById('customQText').value = '';
    document.getElementById('customOptA').value = '';
    document.getElementById('customOptB').value = '';
    document.getElementById('customOptC').value = '';
    document.getElementById('customOptD').value = '';
    document.getElementById('customQExplanation').value = '';
}

function removePreviewQuestion(idx) {
    currentGeneratedQuestions.splice(idx, 1);
    renderQuestionsPreview(currentGeneratedQuestions);
}

function clearAllPreviewQuestions() {
    if (confirm("Clear all preview questions?")) {
        currentGeneratedQuestions = [];
        renderQuestionsPreview(currentGeneratedQuestions);
    }
}

function renderQuestionsPreview(questions) {
    currentGeneratedQuestions = questions || [];
    var container = document.getElementById('aiTestQuestionsContainer');
    var previewDiv = document.getElementById('aiTestPreview');
    var countBadge = document.getElementById('previewQCount');
    if (countBadge) countBadge.textContent = currentGeneratedQuestions.length;

    if (currentGeneratedQuestions.length === 0) {
        container.innerHTML = '<p class="text-center text-muted py-3">No questions in preview yet.</p>';
        document.getElementById('btnSaveAiTest').classList.add('d-none');
        previewDiv.classList.remove('d-none');
        return;
    }

    var html = '';
    currentGeneratedQuestions.forEach((q, idx) => {
        html += '<div class="card p-3 mb-2 bg-light border position-relative">';
        html += '<div class="d-flex justify-content-between align-items-start mb-2">';
        html += '<div class="fw-bold">' + (idx + 1) + '. ' + escapeHtml(q.question || q.body || '') + '</div>';
        html += '<button type="button" class="btn btn-sm btn-outline-danger" onclick="removePreviewQuestion(' + idx + ')" title="Remove question"><i class="ti ti-x"></i></button>';
        html += '</div>';

        if (q.options) {
            html += '<ul class="list-unstyled mb-1 ms-3">';
            q.options.forEach(opt => {
                var optText = opt.text || opt.body || '';
                var badge = opt.is_correct ? '<span class="badge bg-success ms-2">Correct Answer</span>' : '';
                html += '<li class="fs-13 text-muted mb-1">• ' + escapeHtml(optText) + ' ' + badge + '</li>';
            });
            html += '</ul>';
        }
        if (q.explanation) {
            html += '<div class="fs-12 text-info mt-1"><i>Explanation: ' + escapeHtml(q.explanation) + '</i></div>';
        }
        html += '</div>';
    });

    container.innerHTML = html;
    previewDiv.classList.remove('d-none');
    document.getElementById('btnSaveAiTest').classList.remove('d-none');
}

function saveGeneratedAiTest() {
    var courseId = document.getElementById('aiTestCourseId').value;
    if (!currentGeneratedQuestions || currentGeneratedQuestions.length === 0) {
        alert("Please generate or add at least one question before saving.");
        return;
    }

    fetch('<?= base_url("admin/elearning/save-test") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>&course_id=' + courseId + '&questions=' + encodeURIComponent(JSON.stringify(currentGeneratedQuestions))
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Test saved to course successfully!');
            bootstrap.Modal.getInstance(document.getElementById('aiTestModal')).hide();
        } else {
            alert(data.message || 'Failed to save test.');
        }
    });
}

function toggleModuleYt(selectElem) {
    var box = selectElem.closest('.row').querySelector('.module-yt-box');
    if (box) {
        if (selectElem.value === 'youtube') {
            box.classList.remove('d-none');
        } else {
            box.classList.add('d-none');
        }
    }
}

function addModuleRowToAddCourse() {
    var container = document.getElementById('addCourseModulesContainer');
    var count = container.querySelectorAll('.module-row').length + 1;
    var html = `
        <div class="card p-3 border bg-light module-row">
            <input type="hidden" name="module_ids[]" value="">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold fs-13 text-secondary">Module ${count}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" onclick="this.closest('.module-row').remove()"><i class="ti ti-trash"></i> Remove</button>
            </div>
            <div class="row g-2">
                <div class="col-md-8">
                    <input type="text" name="module_titles[]" class="form-control form-control-sm" placeholder="Module Title (e.g. Module ${count}: Core Fundamentals)">
                </div>
                <div class="col-md-4">
                    <select name="module_sources[]" class="form-select form-select-sm" onchange="toggleModuleYt(this)">
                        <option value="none">No Video (Text Only)</option>
                        <option value="youtube">YouTube Video</option>
                    </select>
                </div>
                <div class="col-md-12 module-yt-box d-none">
                    <input type="url" name="module_youtube_urls[]" class="form-control form-control-sm" placeholder="YouTube Video URL (https://www.youtube.com/watch?v=...)">
                </div>
                <div class="col-md-12">
                    <textarea name="module_descriptions[]" class="form-control form-control-sm" rows="2" placeholder="Brief description of topics covered in this module..."></textarea>
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
}

function addModuleRowToEditCourse(courseId) {
    var container = document.getElementById('editCourseModulesContainer' + courseId);
    if (!container) return;
    var count = container.querySelectorAll('.module-row').length + 1;
    var html = `
        <div class="card p-3 border bg-light module-row">
            <input type="hidden" name="module_ids[]" value="">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold fs-13 text-secondary">Module ${count}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" onclick="this.closest('.module-row').remove()"><i class="ti ti-trash"></i> Remove</button>
            </div>
            <div class="row g-2">
                <div class="col-md-8">
                    <input type="text" name="module_titles[]" class="form-control form-control-sm" placeholder="Module Title (e.g. Module ${count}: Core Fundamentals)">
                </div>
                <div class="col-md-4">
                    <select name="module_sources[]" class="form-select form-select-sm" onchange="toggleModuleYt(this)">
                        <option value="none">No Video (Text Only)</option>
                        <option value="youtube">YouTube Video</option>
                    </select>
                </div>
                <div class="col-md-12 module-yt-box d-none">
                    <input type="url" name="module_youtube_urls[]" class="form-control form-control-sm" placeholder="YouTube Video URL (https://www.youtube.com/watch?v=...)">
                </div>
                <div class="col-md-12">
                    <textarea name="module_descriptions[]" class="form-control form-control-sm" rows="2" placeholder="Brief description of topics covered in this module..."></textarea>
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
}

function removeExistingModuleRow(btn, modId, courseId) {
    if (confirm("Are you sure you want to remove this module?")) {
        if (modId && courseId) {
            var delContainer = document.getElementById('deletedModulesContainer' + courseId);
            if (delContainer) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_module_ids[]';
                input.value = modId;
                delContainer.appendChild(input);
            }
        }
        var row = btn.closest('.module-row');
        if (row) row.remove();
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>

<?= $this->endSection() ?>
