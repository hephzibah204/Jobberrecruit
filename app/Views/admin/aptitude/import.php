<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0">Import Questions</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/aptitude') ?>">Aptitude Tests</a></li>
                    <li class="breadcrumb-item active">Import</li>
                </ol>
            </div>
            <div>
                <a href="<?= base_url('admin/aptitude') ?>" class="btn btn-secondary btn-wave d-inline-flex align-items-center">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Upload Questions CSV</div>
                </div>
                <form class="card-body" method="POST" action="<?= base_url('admin/aptitude/import/' . $test_id) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="csv-file" class="form-label">Choose CSV File</label>
                        <input class="form-control" type="file" id="csv-file" name="csv_file" accept=".csv" required>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-upload-2-line me-1"></i> Import Questions
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title text-warning">CSV Format & Guidelines</div>
                </div>
                <div class="card-body">
                    <p class="mb-2">Your CSV file <strong>MUST</strong> have a header row and follow the exact column order below:</p>
                    <ol class="list-group list-group-numbered mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">type</div>
                                <code>single</code> or <code>multiple</code>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">body</div>
                                The text of the question.
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">option_a</div>
                                Content for Option A.
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">option_b</div>
                                Content for Option B.
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">option_c</div>
                                Content for Option C.
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">option_d</div>
                                Content for Option D.
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">correct_letter</div>
                                Correct choice: <code>A</code>, <code>B</code>, <code>C</code>, or <code>D</code>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">difficulty</div>
                                <code>easy</code>, <code>medium</code>, or <code>hard</code>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">explanation</div>
                                Detailed explanation for the correct answer.
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
