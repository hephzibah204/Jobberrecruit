<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Create Aptitude Test</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/aptitude') ?>">Aptitude Tests</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
            <div>
                <a href="<?= base_url('admin/aptitude') ?>" class="btn btn-secondary btn-wave d-inline-flex align-items-center">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Test Parameters</div>
        </div>
        <form class="card-body" method="POST" action="<?= base_url('admin/aptitude/create') ?>">
            <?= csrf_field() ?>
            <div class="row gy-3">
                <div class="col-xl-6">
                    <label for="input-title" class="form-label">Test Title</label>
                    <input type="text" class="form-control" id="input-title" name="title" placeholder="e.g. Cognitive Ability, Front-end Developer" required>
                </div>
                <div class="col-xl-6">
                    <label for="input-category" class="form-label">Job Category</label>
                    <select class="form-control" id="input-category" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php 
                        $categoryModel = new \App\Models\JobCategoryModel();
                        $categories = $categoryModel->findAll();
                        foreach ($categories as $cat): 
                        ?>
                            <option value="<?= $cat['id'] ?? $cat->id ?>"><?= esc($cat['name'] ?? $cat->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-xl-4">
                    <label for="input-duration" class="form-label">Duration (Minutes)</label>
                    <input type="number" class="form-control" id="input-duration" name="duration_mins" value="30" required>
                </div>
                <div class="col-xl-4">
                    <label for="input-questions" class="form-label">Number of Questions</label>
                    <input type="number" class="form-control" id="input-questions" name="num_questions" value="20" required>
                </div>
                <div class="col-xl-4">
                    <label for="input-threshold" class="form-label">Pass Threshold (%)</label>
                    <input type="number" class="form-control" id="input-threshold" name="pass_threshold" value="50" required>
                </div>
                <div class="col-xl-6">
                    <label for="input-difficulty" class="form-label">Difficulty Level</label>
                    <select class="form-control" id="input-difficulty" name="difficulty" required>
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div class="col-xl-12">
                    <label for="input-description" class="form-label">Description</label>
                    <textarea class="form-control" id="input-description" name="description" rows="3" placeholder="Brief summary of the test scope..." required></textarea>
                </div>
                <div class="col-xl-12 mt-4">
                    <button type="submit" class="btn btn-primary">Save Test Template</button>
                </div>
            </div>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
