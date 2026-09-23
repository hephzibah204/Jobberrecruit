<?= $this->extend('admin/layouts/app') ?>
<?= $this->section('section') ?>

<div class="container-fluid page-container main-body-container">

    <div class="page-header-breadcrumb mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-0"><?= isset($test) ? 'Edit Aptitude Test' : 'Create Aptitude Test' ?></h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/aptitude') ?>">Aptitude Tests</a></li>
                    <li class="breadcrumb-item active"><?= isset($test) ? 'Edit' : 'Create' ?></li>
                </ol>
            </div>
            <div>
                <a href="<?= base_url('admin/aptitude') ?>" class="btn btn-secondary btn-wave d-inline-flex align-items-center">
                    <i class="ri-arrow-left-line me-1"></i> Back to Tests
                </a>
            </div>
        </div>
    </div>

    <form method="POST" action="<?= isset($test) ? base_url('admin/aptitude/update/' . (is_array($test) ? $test['id'] : $test->id)) : base_url('admin/aptitude/create') ?>" id="aptitudeTestForm">
        <?= csrf_field() ?>
        <input type="hidden" id="questionsJsonInput" name="questions" value="">

        <div class="row">
            <!-- Left Column: Test Settings -->
            <div class="col-xl-5">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title"><i class="ti ti-settings me-1"></i> Test Parameters</div>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label for="input-title" class="form-label">Test Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="input-title" name="title" value="<?= esc(isset($test) ? (is_array($test) ? $test['title'] : $test->title) : '') ?>" placeholder="e.g. Fullstack Developer Aptitude, Verbal Reasoning" required>
                            </div>
                            <div class="col-12">
                                <label for="input-category" class="form-label">Job Category <span class="text-danger">*</span></label>
                                <select class="form-control" id="input-category" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php 
                                    $categoryModel = new \App\Models\JobCategoryModel();
                                    $categories = $categoryModel->findAll();
                                    $currCat = isset($test) ? (is_array($test) ? $test['category_id'] : $test->category_id) : '';
                                    foreach ($categories as $cat): 
                                        $catId = is_object($cat) ? $cat->id : $cat['id'];
                                        $catName = is_object($cat) ? $cat->name : $cat['name'];
                                    ?>
                                        <option value="<?= $catId ?>" <?= $currCat == $catId ? 'selected' : '' ?>><?= esc($catName) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="input-duration" class="form-label">Duration (Mins)</label>
                                <input type="number" class="form-control" id="input-duration" name="duration_mins" value="<?= esc(isset($test) ? (is_array($test) ? $test['duration_mins'] : $test->duration_mins) : '20') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="input-questions-count" class="form-label">Num Qs</label>
                                <input type="number" class="form-control" id="input-questions-count" name="num_questions" value="<?= esc(isset($test) ? (is_array($test) ? $test['num_questions'] : $test->num_questions) : '5') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="input-threshold" class="form-label">Pass (%)</label>
                                <input type="number" class="form-control" id="input-threshold" name="pass_threshold" value="<?= esc(isset($test) ? (is_array($test) ? $test['pass_threshold'] : $test->pass_threshold) : '50') ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="input-difficulty" class="form-label">Difficulty Level</label>
                                <?php $currDiff = strtolower(isset($test) ? (is_array($test) ? $test['difficulty'] : $test->difficulty) : 'medium'); ?>
                                <select class="form-control" id="input-difficulty" name="difficulty" required>
                                    <option value="easy" <?= $currDiff === 'easy' ? 'selected' : '' ?>>Easy</option>
                                    <option value="medium" <?= $currDiff === 'medium' ? 'selected' : '' ?>>Medium</option>
                                    <option value="hard" <?= $currDiff === 'hard' ? 'selected' : '' ?>>Hard</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="input-description" class="form-label">Description Context</label>
                                <textarea class="form-control" id="input-description" name="description" rows="3" placeholder="Brief scope description..."><?= esc(isset($test) ? (is_array($test) ? $test['description'] : $test->description) : '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-end">
                        <button type="submit" class="btn btn-success btn-lg w-100" id="btnSubmitTest">
                            <i class="ti ti-check me-1"></i> Save Aptitude Test &amp; Questions
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Questions Builder -->
            <div class="col-xl-7">
                <div class="card custom-card">
                    <div class="card-header justify-content-between align-items-center">
                        <div class="card-title"><i class="ti ti-list-check me-1"></i> Test Questions Bank (<span id="questionCountBadge">0</span>)</div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-primary-transparent" id="btnAiGenQuestions" onclick="generateAiAptitudeQuestions()">
                                <i class="ti ti-sparkles me-1"></i> AI Generate Questions
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="openAddQuestionModal()">
                                <i class="ti ti-plus me-1"></i> Add Manual Question
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="aiStatusAlert" class="alert alert-info text-center d-none">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            Gemini AI is generating tailored aptitude assessment questions...
                        </div>

                        <div id="questionsContainer" class="d-flex flex-column gap-3">
                            <div class="text-center text-muted py-5" id="emptyQuestionsState">
                                <i class="ti ti-help-circle fs-1"></i>
                                <p class="mt-2 mb-0">No questions added yet. Click <b>"AI Generate Questions"</b> or <b>"Add Manual Question"</b> to build the assessment for candidates.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal: Add / Edit Question -->
<div class="modal fade" id="questionEditorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionEditorTitle">Add Assessment Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editQuestionIndex" value="-1">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Question Stem / Text <span class="text-danger">*</span></label>
                    <textarea id="modalQBody" class="form-control" rows="2" placeholder="e.g. What will be the output of the following function?"></textarea>
                </div>
                
                <label class="form-label fw-semibold mb-2">Options (Mark the radio button of the correct answer)</label>
                <div class="row g-2 mb-3">
                    <div class="col-12 d-flex align-items-center gap-2">
                        <input class="form-check-input flex-shrink-0" type="radio" name="modalOptCorrect" id="optRadioA" value="0" checked>
                        <span class="badge bg-secondary">A</span>
                        <input type="text" class="form-control" id="modalOptTextA" placeholder="Option A text">
                    </div>
                    <div class="col-12 d-flex align-items-center gap-2">
                        <input class="form-check-input flex-shrink-0" type="radio" name="modalOptCorrect" id="optRadioB" value="1">
                        <span class="badge bg-secondary">B</span>
                        <input type="text" class="form-control" id="modalOptTextB" placeholder="Option B text">
                    </div>
                    <div class="col-12 d-flex align-items-center gap-2">
                        <input class="form-check-input flex-shrink-0" type="radio" name="modalOptCorrect" id="optRadioC" value="2">
                        <span class="badge bg-secondary">C</span>
                        <input type="text" class="form-control" id="modalOptTextC" placeholder="Option C text">
                    </div>
                    <div class="col-12 d-flex align-items-center gap-2">
                        <input class="form-check-input flex-shrink-0" type="radio" name="modalOptCorrect" id="optRadioD" value="3">
                        <span class="badge bg-secondary">D</span>
                        <input type="text" class="form-control" id="modalOptTextD" placeholder="Option D text">
                    </div>
                </div>

                <div>
                    <label class="form-label fw-semibold">Explanation (Optional)</label>
                    <input type="text" id="modalQExplanation" class="form-control" placeholder="Brief explanation of why the correct option is right">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveQuestionFromModal()">Save Question</button>
            </div>
        </div>
    </div>
</div>

<script>
let questionsList = <?= isset($questions) ? json_encode($questions) : '[]' ?>;

document.addEventListener("DOMContentLoaded", function() {
    renderQuestionsList();

    document.getElementById('aptitudeTestForm').addEventListener('submit', function(e) {
        document.getElementById('questionsJsonInput').value = JSON.stringify(questionsList);
    });
});

function renderQuestionsList() {
    const container = document.getElementById('questionsContainer');
    const badge = document.getElementById('questionCountBadge');
    badge.textContent = questionsList.length;

    if (questionsList.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-5" id="emptyQuestionsState">
                <i class="ti ti-help-circle fs-1"></i>
                <p class="mt-2 mb-0">No questions added yet. Click <b>"AI Generate Questions"</b> or <b>"Add Manual Question"</b> to build the assessment for candidates.</p>
            </div>`;
        return;
    }

    let html = '';
    questionsList.forEach((q, idx) => {
        const questionText = q.question || q.body || '';
        html += `<div class="card p-3 bg-light border position-relative mb-2">`;
        html += `<div class="d-flex justify-content-between align-items-start mb-2">`;
        html += `<div class="fw-bold fs-14">${idx + 1}. ${escapeHtml(questionText)}</div>`;
        html += `<div class="btn-group btn-group-sm">`;
        html += `<button type="button" class="btn btn-outline-info" onclick="editQuestion(${idx})" title="Edit Question"><i class="ti ti-edit"></i></button>`;
        html += `<button type="button" class="btn btn-outline-danger" onclick="deleteQuestion(${idx})" title="Delete Question"><i class="ti ti-trash"></i></button>`;
        html += `</div></div>`;

        const options = q.options || [];
        if (options.length > 0) {
            html += `<ul class="list-unstyled mb-1 ms-3">`;
            options.forEach(opt => {
                const optText = opt.text || opt.body || '';
                const isCorrect = opt.is_correct ? '<span class="badge bg-success ms-2">Correct</span>' : '';
                html += `<li class="fs-13 text-muted mb-1">• ${escapeHtml(optText)} ${isCorrect}</li>`;
            });
            html += `</ul>`;
        }

        if (q.explanation) {
            html += `<div class="fs-12 text-info mt-1 ms-3"><i>Explanation: ${escapeHtml(q.explanation)}</i></div>`;
        }

        html += `</div>`;
    });

    container.innerHTML = html;
}

function openAddQuestionModal() {
    document.getElementById('editQuestionIndex').value = "-1";
    document.getElementById('questionEditorTitle').textContent = "Add Assessment Question";
    document.getElementById('modalQBody').value = "";
    document.getElementById('modalOptTextA').value = "";
    document.getElementById('modalOptTextB').value = "";
    document.getElementById('modalOptTextC').value = "";
    document.getElementById('modalOptTextD').value = "";
    document.getElementById('optRadioA').checked = true;
    document.getElementById('modalQExplanation').value = "";

    const modal = new bootstrap.Modal(document.getElementById('questionEditorModal'));
    modal.show();
}

function editQuestion(idx) {
    const q = questionsList[idx];
    if (!q) return;

    document.getElementById('editQuestionIndex').value = idx;
    document.getElementById('questionEditorTitle').textContent = "Edit Assessment Question #" + (idx + 1);
    document.getElementById('modalQBody').value = q.question || q.body || "";
    document.getElementById('modalQExplanation').value = q.explanation || "";

    const options = q.options || [];
    document.getElementById('modalOptTextA').value = options[0] ? (options[0].text || options[0].body || "") : "";
    document.getElementById('modalOptTextB').value = options[1] ? (options[1].text || options[1].body || "") : "";
    document.getElementById('modalOptTextC').value = options[2] ? (options[2].text || options[2].body || "") : "";
    document.getElementById('modalOptTextD').value = options[3] ? (options[3].text || options[3].body || "") : "";

    let correctIdx = 0;
    options.forEach((opt, oIdx) => {
        if (opt.is_correct) correctIdx = oIdx;
    });

    const radios = document.getElementsByName('modalOptCorrect');
    if (radios[correctIdx]) radios[correctIdx].checked = true;

    const modal = new bootstrap.Modal(document.getElementById('questionEditorModal'));
    modal.show();
}

function saveQuestionFromModal() {
    const body = document.getElementById('modalQBody').value.trim();
    if (!body) {
        alert("Please enter the question stem text.");
        return;
    }

    const optA = document.getElementById('modalOptTextA').value.trim();
    const optB = document.getElementById('modalOptTextB').value.trim();
    const optC = document.getElementById('modalOptTextC').value.trim();
    const optD = document.getElementById('modalOptTextD').value.trim();

    if (!optA || !optB) {
        alert("Please enter at least Option A and Option B.");
        return;
    }

    const radios = document.getElementsByName('modalOptCorrect');
    let selectedCorrect = 0;
    for (let i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            selectedCorrect = parseInt(radios[i].value);
            break;
        }
    }

    const rawOpts = [optA, optB, optC, optD].filter(Boolean);
    const options = rawOpts.map((text, idx) => ({
        text: text,
        is_correct: idx === selectedCorrect ? 1 : 0
    }));

    const qObj = {
        question: body,
        body: body,
        explanation: document.getElementById('modalQExplanation').value.trim(),
        options: options
    };

    const idx = parseInt(document.getElementById('editQuestionIndex').value);
    if (idx >= 0 && idx < questionsList.length) {
        questionsList[idx] = qObj;
    } else {
        questionsList.push(qObj);
    }

    renderQuestionsList();
    bootstrap.Modal.getInstance(document.getElementById('questionEditorModal')).hide();
}

function deleteQuestion(idx) {
    if (confirm("Are you sure you want to remove this question?")) {
        questionsList.splice(idx, 1);
        renderQuestionsList();
    }
}

function generateAiAptitudeQuestions() {
    const title = document.getElementById('input-title').value.trim();
    if (!title) {
        alert("Please enter a Test Title first before generating questions with AI.");
        document.getElementById('input-title').focus();
        return;
    }

    const desc = document.getElementById('input-description').value.trim();
    const numQ = document.getElementById('input-questions-count').value || 5;

    const alertBox = document.getElementById('aiStatusAlert');
    const btnGen = document.getElementById('btnAiGenQuestions');
    alertBox.classList.remove('d-none');
    btnGen.disabled = true;

    fetch('<?= base_url("admin/aptitude/ai-generate") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>&title=' + encodeURIComponent(title) + '&description=' + encodeURIComponent(desc) + '&num_questions=' + numQ
    })
    .then(res => res.json())
    .then(data => {
        alertBox.classList.add('d-none');
        btnGen.disabled = false;

        if (data.success && data.questions && data.questions.length > 0) {
            questionsList = data.questions;
            renderQuestionsList();
        } else {
            alert(data.message || "Failed to generate AI questions.");
        }
    })
    .catch(err => {
        alertBox.classList.add('d-none');
        btnGen.disabled = false;
        alert("Network error while generating AI questions.");
    });
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>

<?= $this->endSection() ?>
