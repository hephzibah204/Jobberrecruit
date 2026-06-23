<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">

<div class="container-fluid page-container main-body-container">

    <!-- HEADER -->
    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between flex-wrap">
            <h1 class="page-title fw-medium fs-18 mb-0">Edit Job</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/jobs') ?>">Jobs</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>

    <!-- FORM -->
    <form id="job-edit-form">
        <?= csrf_field() ?>

        <div class="accordion" id="jobEditAccordion">

            <!-- JOB INFO -->
            <div class="accordion-item mb-3">
                <h2 class="accordion-header">
                    <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#jobInfo">
                        Job Information
                    </button>
                </h2>
                <div id="jobInfo" class="accordion-collapse collapse show">
                    <div class="accordion-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Title *</label>
                                <input type="text" name="title" class="form-control" value="<?= esc($job->title) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Type *</label>
                                <select name="job_type" class="form-select" required>
                                    <?php foreach (['full-time', 'part-time', 'contract', 'freelance', 'internship'] as $t): ?>
                                        <option value="<?= $t ?>" <?= $job->job_type === $t ? 'selected' : '' ?>>
                                            <?= ucwords(str_replace('-', ' ', $t)) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State *</label>
                                <select name="state_id" class="form-select" required>
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?= $state->id ?>" <?= $job->state_id == $state->id ? 'selected' : '' ?>>
                                            <?= esc($state->name) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location Type *</label>
                                <select name="location_type" class="form-select" required>
                                    <option value="hybrid" <?= $job->location_type === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                    <option value="remote" <?= $job->location_type === 'remote' ? 'selected' : '' ?>>Remote</option>
                                    <option value="on-site" <?= $job->location_type === 'on-site' ? 'selected' : '' ?>>On-Site</option>
                                </select>
                            </div>
                        </div>

                        <!-- SALARY -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Salary Type *</label>
                                <select name="salary_type" id="salary_type" class="form-select" onchange="toggleSalary()" required>
                                    <option value="fixed" <?= $job->salary_type === 'fixed' ? 'selected' : '' ?>>Fixed</option>
                                    <option value="range" <?= $job->salary_type === 'range' ? 'selected' : '' ?>>Range</option>
                                    <option value="negotiable" <?= $job->salary_type === 'negotiable' ? 'selected' : '' ?>>Negotiable</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Salary Period *</label>
                                <select name="salary_period" class="form-select" required>
                                    <option value="monthly" <?= $job->salary_period === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                    <option value="yearly" <?= $job->salary_period === 'yearly' ? 'selected' : '' ?>>Yearly</option>
                                    <option value="hourly" <?= $job->salary_period === 'hourly' ? 'selected' : '' ?>>Hourly</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3" id="salary_box">
                            <label class="form-label">Salary</label>
                            <input type="text" name="salary" class="form-control" value="<?= esc($job->salary) ?>">
                        </div>

                        <!-- INDUSTRY + CATEGORY -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Industry *</label>
                                <select name="industry_id" class="form-select" required>
                                    <?php foreach ($industries as $i): ?>
                                        <option value="<?= $i->id ?>" <?= $job->industry_id == $i->id ? 'selected' : '' ?>>
                                            <?= esc($i->name) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-select" required>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c->id ?>" <?= $job->category_id == $c->id ? 'selected' : '' ?>>
                                            <?= esc($c->name) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="accordion-item mb-3">
                <h2 class="accordion-header">
                    <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#jobDesc">
                        Description & Requirements
                    </button>
                </h2>
                <div id="jobDesc" class="accordion-collapse collapse show">
                    <div class="accordion-body">

                        <label class="form-label">Job Description *</label>
                        <div id="desc-editor" style="height:200px;"></div>
                        <input type="hidden" name="description" id="desc-input" value="<?= esc($job->description) ?>">

                        <label class="form-label mt-3">Requirements</label>
                        <div id="req-editor" style="height:150px;"></div>
                        <input type="hidden" name="requirements" id="req-input" value="<?= esc($job->requirements) ?>">

                        <label class="form-label mt-3">Skills</label>
                        <input type="text" name="skills" id="skills-input" class="form-control"
                            value="<?= esc($job->skills) ?>">

                    </div>
                </div>
            </div>

            <!-- APPLICATION -->
            <div class="accordion-item mb-3">
                <h2 class="accordion-header">
                    <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#applicationBox">
                        Application Details
                    </button>
                </h2>
                <div id="applicationBox" class="accordion-collapse collapse show">
                    <div class="accordion-body">

                        <label class="form-label">Application Method *</label>
                        <?php $method = $job->application_method ?>
                        <div class="d-flex gap-3 flex-wrap mb-3">
                            <?php foreach (['form', 'whatsapp', 'email', 'external'] as $m): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="application_method"
                                        value="<?= $m ?>" <?= $method === $m ? 'checked' : '' ?>>
                                    <label class="form-check-label"><?= ucfirst($m) ?></label>
                                </div>
                            <?php endforeach ?>
                        </div>

                        <!-- CONDITIONAL -->
                        <div class="row mt-3">

                            <div class="col-md-6 conditional-field" data-method="whatsapp">
                                <label class="form-label">WhatsApp Link *</label>
                                <input type="url" name="whatsapp_link" class="form-control"
                                    value="<?= esc($job->whatsapp_link) ?>">
                            </div>

                            <div class="col-md-6 conditional-field" data-method="email">
                                <label class="form-label">Application Email *</label>
                                <input type="email" name="application_email" class="form-control"
                                    value="<?= esc($job->application_email) ?>">
                            </div>

                            <div class="col-md-6 conditional-field" data-method="external">
                                <label class="form-label">External URL *</label>
                                <input type="url" name="external_url" class="form-control"
                                    value="<?= esc($job->external_url) ?>">
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="d-flex justify-content-end gap-2 my-3">
            <a href="<?= base_url('admin/jobs') ?>" class="btn btn-light">Cancel</a>
            <button type="submit" id="submitBtn" class="btn btn-primary">Update Job</button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<script>
    toastr.options = {
        closeButton: true,
        progressBar: true
    };

    // QUILL
    const desc = new Quill('#desc-editor', {
        theme: 'snow'
    });
    const req = new Quill('#req-editor', {
        theme: 'snow'
    });
    // const app = new Quill('#app-editor', {
    //     theme: 'snow'
    // });

    desc.root.innerHTML = document.getElementById('desc-input').value;
    req.root.innerHTML = document.getElementById('req-input').value;
    // app.root.innerHTML = document.getElementById('app-input').value;

    desc.on('text-change', () => document.getElementById('desc-input').value = desc.root.innerHTML);
    req.on('text-change', () => document.getElementById('req-input').value = req.root.innerHTML);
    // app.on('text-change', () => document.getElementById('app-input').value = app.root.innerHTML);

    // TAGIFY
    new Tagify(document.getElementById('skills-input'), {
        delimiters: ","
    });

    // SALARY
    function toggleSalary() {
        document.getElementById('salary_box').style.display =
            document.getElementById('salary_type').value === 'negotiable' ? 'none' : 'block';
    }
    toggleSalary();

    // APPLICATION METHOD
    function toggleApplicationMethod() {
        const selected = document.querySelector('input[name="application_method"]:checked')?.value;
        document.querySelectorAll('.conditional-field').forEach(el => {
            const show = el.dataset.method === selected;
            el.style.display = show ? 'block' : 'none';
            const input = el.querySelector('input');
            if (input) input.toggleAttribute('required', show);
        });
    }
    toggleApplicationMethod();
    document.querySelectorAll('input[name="application_method"]').forEach(r =>
        r.addEventListener('change', toggleApplicationMethod)
    );

    // SUBMIT
    document.getElementById('job-edit-form').addEventListener('submit', e => {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Updating...';

        fetch("<?= base_url('admin/jobs/update/' . $job->id) ?>", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(e.target)
            })
            .then(r => r.json())
            .then(res => res.success ? toastr.success(res.message) : toastr.error(res.message))
            .catch(() => toastr.error('Server error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Update Job';
            });
    });
</script>
<?= $this->endSection() ?>