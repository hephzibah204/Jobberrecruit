<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<div class="container-fluid p-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1 fw-bold text-dark"><?= esc($title) ?></h2>
            <p class="text-muted mb-0 fs-13">Compose and configure your email campaign.</p>
        </div>
        <div>
            <a href="<?= base_url('admin/newsletters') ?>" class="btn btn-light shadow-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Campaigns
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="<?= base_url('admin/newsletters/save') ?>" method="POST" id="newsletterForm">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_newsletter_id" value="<?= $newsletter ? $newsletter->id : '' ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">Internal Campaign Name</label>
                        <input type="text" name="title" id="n_title" class="form-control rounded" placeholder="e.g. June Career Tips" required value="<?= $newsletter ? esc($newsletter->title) : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">Email Subject Line</label>
                        <input type="text" name="subject" id="n_subject" class="form-control rounded" placeholder="What users see in inbox..." required value="<?= $newsletter ? esc($newsletter->subject) : '' ?>">
                    </div>
                </div>

                <!-- Target Audience Group Selection -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark d-block mb-2">Target Audience Segment</label>
                    <div class="row g-2">
                        <?php $tg = $newsletter ? $newsletter->target_group : 'all'; ?>
                        <div class="col-md-3 col-6">
                            <input type="radio" class="btn-check" name="target_group" id="tg_all" value="all" <?= $tg == 'all' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary w-100 py-3 text-start transition-all rounded-md" for="tg_all" style="border: 1px solid #dee2e6">
                                <div class="fw-bold fs-13">All</div>
                            </label>
                        </div>
                        <div class="col-md-3 col-6">
                            <input type="radio" class="btn-check" name="target_group" id="tg_candidates" value="candidates" <?= $tg == 'candidates' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-info w-100 py-3 text-start transition-all rounded-md" for="tg_candidates" style="border: 1px solid #dee2e6">
                                <div class="fw-bold fs-13">Candidates</div>
                            </label>
                        </div>
                        <div class="col-md-3 col-6">
                            <input type="radio" class="btn-check" name="target_group" id="tg_employers" value="employers" <?= $tg == 'employers' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-warning w-100 py-3 text-start transition-all rounded-md" for="tg_employers" style="border: 1px solid #dee2e6">
                                <div class="fw-bold fs-13">Employers</div>
                            </label>
                        </div>
                        <div class="col-md-3 col-6">
                            <input type="radio" class="btn-check" name="target_group" id="tg_subscribers" value="subscribers" <?= $tg == 'subscribers' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-secondary w-100 py-3 text-start transition-all rounded-md" for="tg_subscribers" style="border: 1px solid #dee2e6">
                                <div class="fw-bold fs-13">Subscribers</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">Target Industries (Optional)</label>
                        <?php 
                            $selectedInds = [];
                            if ($newsletter && $newsletter->target_industries) {
                                $selectedInds = json_decode($newsletter->target_industries, true) ?: [];
                            }
                        ?>
                        <select name="target_industries[]" id="n_industries" class="form-select select2-page" multiple>
                            <?php foreach($industries as $ind): ?>
                                <option value="<?= $ind->id ?>" <?= in_array($ind->id, $selectedInds) ? 'selected' : '' ?>><?= esc($ind->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Leave empty for all industries</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-dark">Schedule Blast (Optional)</label>
                        <?php
                            $sched = '';
                            if ($newsletter && $newsletter->scheduled_at) {
                                $sched = str_replace(' ', 'T', $newsletter->scheduled_at);
                            }
                        ?>
                        <input type="datetime-local" name="scheduled_at" id="n_scheduled" class="form-control rounded" value="<?= $sched ?>">
                        <small class="text-muted">Leave empty to send immediately</small>
                    </div>
                </div>

                <div class="mb-4 p-3 bg-light rounded-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Campaign Template Mode (A)</label>
                            <?php $modeA = $newsletter ? ($newsletter->template_mode ?: 'standard') : 'standard'; ?>
                            <select name="template_mode" id="n_template_mode" class="form-select form-select-sm" onchange="toggleNewsletterMode(this.value, 'A')">
                                <option value="standard" <?= $modeA == 'standard' ? 'selected' : '' ?>>Standard (Themed Content)</option>
                                <option value="html" <?= $modeA == 'html' ? 'selected' : '' ?>>Advanced HTML (Raw Code)</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-none" id="mode_b_container" >
                            <label class="form-label fw-semibold text-dark">Campaign Template Mode (B)</label>
                            <?php $modeB = $newsletter ? ($newsletter->template_mode_b ?: 'standard') : 'standard'; ?>
                            <select name="template_mode_b" id="n_template_mode_b" class="form-select form-select-sm" onchange="toggleNewsletterMode(this.value, 'B')">
                                <option value="standard" <?= $modeB == 'standard' ? 'selected' : '' ?>>Standard (Themed Content)</option>
                                <option value="html" <?= $modeB == 'html' ? 'selected' : '' ?>>Advanced HTML (Raw Code)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="html_editor_a" class="mb-3 d-none">
                    <div class="alert alert-info p-2 fs-11 mb-3">
                        <h6 class="fw-bold mb-1"><i class="ti ti-mail-forward me-1"></i>Email HTML Guidelines:</h6>
                        <ul class="mb-0 ps-3">
                            <li><strong>Compatibility:</strong> Use <b>Tables</b> for layout. Flexbox/Grid have poor support in Outlook/Gmail.</li>
                            <li><strong>CSS:</strong> Use <b>Inline Styles</b> (e.g., <code>&lt;td style="..."&gt;</code>). Internal <code>&lt;style&gt;</code> blocks are often stripped.</li>
                            <li><strong>Images:</strong> Use absolute URLs (including <code>https://</code>).</li>
                            <li><strong>Compliance:</strong> You <u>must</u> include the <code>{{unsub_link}}</code> to avoid being marked as spam.</li>
                        </ul>
                    </div>
                    <label class="form-label fw-semibold text-dark">Variation A HTML Code</label>
                    <textarea name="custom_html" id="n_custom_html" class="form-control font-monospace fs-12" rows="10" placeholder="<html>...</html>"><?= $newsletter ? esc($newsletter->custom_html) : '' ?></textarea>
                    <div class="mt-2 bg-light p-2 rounded border">
                        <label class="fs-11 fw-bold d-block mb-1 text-primary">Email Placeholders:</label>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-white text-dark border" title="Recipient Full Name">{{name}}</span>
                            <span class="badge bg-white text-dark border" title="Recipient Email">{{email}}</span>
                            <span class="badge bg-white text-dark border" title="Today's Date">{{date}}</span>
                            <span class="badge bg-white text-danger border" title="MANDATORY: Unsubscribe URL">{{unsub_link}}</span>
                        </div>
                    </div>
                </div>

                <div id="html_editor_b" class="mb-3 d-none">
                    <label class="form-label fw-semibold text-dark">Variation B HTML Code</label>
                    <textarea name="custom_html_b" id="n_custom_html_b" class="form-control font-monospace fs-12" rows="10" placeholder="<html>...</html>"><?= $newsletter ? esc($newsletter->custom_html_b) : '' ?></textarea>
                    <small class="text-muted">Placeholders: {{name}}, {{email}}, {{unsub_link}}</small>
                </div>

                <!-- A/B Testing Section -->
                <?php 
                    $testSplit = $newsletter ? (int)$newsletter->test_split_percent : 0; 
                    $isAb = $testSplit > 0;
                ?>
                <div class="mb-4 p-3 bg-light rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 text-primary"><i class="ti ti-test-pipe me-1"></i> A/B Testing</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_ab_test" onchange="toggleABTesting(this.checked)" <?= $isAb ? 'checked' : '' ?>>
                            <label class="form-check-label fs-12" for="enable_ab_test">Enable A/B Test</label>
                        </div>
                    </div>
                    <div id="ab_testing_fields" class="<?= $isAb ? '' : 'd-none' ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold">Variation B Subject</label>
                                <input type="text" name="subject_b" id="n_subject_b" class="form-control form-control-sm" placeholder="Subject for Variation B" value="<?= $newsletter ? esc($newsletter->subject_b) : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-semibold">Test Split Percentage</label>
                                <select name="test_split_percent" id="n_test_split" class="form-select form-select-sm">
                                    <option value="0" <?= $testSplit == 0 ? 'selected' : '' ?>>Disabled</option>
                                    <option value="10" <?= $testSplit == 10 ? 'selected' : '' ?>>10% (5% A / 5% B)</option>
                                    <option value="20" <?= $testSplit == 20 ? 'selected' : '' ?>>20% (10% A / 10% B)</option>
                                    <option value="30" <?= $testSplit == 30 ? 'selected' : '' ?>>30% (15% A / 15% B)</option>
                                    <option value="50" <?= $testSplit == 50 ? 'selected' : '' ?>>50% (25% A / 25% B)</option>
                                    <option value="100" <?= $testSplit == 100 ? 'selected' : '' ?>>100% (50% A / 50% B)</option>
                                </select>
                                <small class="text-muted fs-10">Percent of audience to include in test</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GrapesJS Editor -->
                <div class="mb-3" id="standard_editor_a_wrapper">
                    <label class="form-label fw-semibold text-dark">Email Message Body</label>
                    <div id="gjs" style="height: 600px; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden;"></div>
                    <textarea name="content" id="newsletter-content" class="d-none"><?= $newsletter ? esc($newsletter->content) : '' ?></textarea>
                </div>
                
                <!-- Variation B GrapesJS Editor -->
                <div class="mb-3 d-none" id="standard_editor_b_wrapper" >
                    <label class="form-label fw-semibold text-dark">Variation B Email Message Body</label>
                    <div id="gjs-b" style="height: 600px; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden;"></div>
                    <textarea name="content_b" id="newsletter-content-b" class="d-none"><?= $newsletter ? esc($newsletter->content_b) : '' ?></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div>
                        <button type="button" class="btn btn-outline-info" onclick="loadTemplatesModal()">
                            <i class="ti ti-layout me-1"></i> Choose Template
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary px-3" onclick="saveAsTemplate()" id="btn-save-template">Save as Template</button>
                        <a href="<?= base_url('admin/newsletters') ?>" class="btn btn-light px-4" style="border-radius: 8px;">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 rounded" >Save Campaign</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Templates Modal -->
<div class="modal fade" id="templatesModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Template Library</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="templatesList">
        <div class="text-center py-4"><i class="ti ti-loader fa-spin"></i> Loading templates...</div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">
<script src="https://unpkg.com/grapesjs"></script>
<script src="https://unpkg.com/grapesjs-preset-newsletter"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editorConfig = {
            container: '#gjs',
            plugins: ['grapesjs-preset-newsletter'],
            pluginsOpts: {
                'grapesjs-preset-newsletter': {
                    modalTitleImport: 'Import template',
                }
            },
            storageManager: false, // Handled manually
            components: document.getElementById('newsletter-content').value || ''
        };
        const editorA = grapesjs.init(editorConfig);

        const editorConfigB = Object.assign({}, editorConfig, {
            container: '#gjs-b',
            components: document.getElementById('newsletter-content-b').value || ''
        });
        const editorB = grapesjs.init(editorConfigB);

        // Make editorA available globally for the template functions
        window.editorA = editorA;

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const isHtmlA = document.getElementById('n_template_mode').value === 'html';
                const isHtmlB = document.getElementById('n_template_mode_b').value === 'html';
                
                if (!isHtmlA) {
                    document.getElementById('newsletter-content').value = editorA.runCommand('gjs-get-inlined-html');
                }
                if (!isHtmlB && document.getElementById('enable_ab_test').checked) {
                    document.getElementById('newsletter-content-b').value = editorB.runCommand('gjs-get-inlined-html');
                }
            });
        }

        // Initialize Select2
        $('.select2-page').select2({
            placeholder: 'All Industries',
            width: '100%'
        });

        window.toggleABTesting = function(enabled) {
            const fields = document.getElementById('ab_testing_fields');
            const modeB = document.getElementById('mode_b_container');
            const standardB = document.getElementById('standard_editor_b_wrapper');
            const templateModeB = document.getElementById('n_template_mode_b').value;

            if (enabled) {
                fields.classList.remove('d-none');
                modeB.style.display = 'block';
                if (templateModeB === 'standard') {
                    standardB.style.display = 'block';
                }
            } else {
                fields.classList.add('d-none');
                modeB.style.display = 'none';
                standardB.style.display = 'none';
                document.getElementById('n_test_split').value = '0';
            }
        };

        window.toggleNewsletterMode = function(mode, variation) {
            const standardEditorWrapper = variation === 'A' ? document.getElementById('standard_editor_a_wrapper') : document.getElementById('standard_editor_b_wrapper');
            const htmlEditor = variation === 'A' ? document.getElementById('html_editor_a') : document.getElementById('html_editor_b');
            const isABEnabled = document.getElementById('enable_ab_test').checked;

            if (mode === 'html') {
                htmlEditor.classList.remove('d-none');
                if (standardEditorWrapper) standardEditorWrapper.style.display = 'none';
            } else {
                htmlEditor.classList.add('d-none');
                if (variation === 'A') {
                    if (standardEditorWrapper) standardEditorWrapper.style.display = 'block';
                } else {
                    if (isABEnabled && standardEditorWrapper) standardEditorWrapper.style.display = 'block';
                }
            }
        };

        // Initialize UI state based on existing data
        toggleABTesting(<?= $isAb ? 'true' : 'false' ?>);
        toggleNewsletterMode('<?= $modeA ?>', 'A');
        toggleNewsletterMode('<?= $modeB ?>', 'B');
    });

    function saveAsTemplate() {
        const name = prompt("Enter a name for this template:");
        if (!name) return;
        
        const html_content = window.editorA.runCommand('gjs-get-inlined-html');
        
        const formData = new FormData();
        formData.append('name', name);
        formData.append('html_content', html_content);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url("admin/newsletters/templates/store") ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
            } else {
                alert(data.message || 'Error saving template');
            }
        });
    }

    function loadTemplatesModal() {
        const modal = new bootstrap.Modal(document.getElementById('templatesModal'));
        modal.show();
        
        fetch('<?= base_url("admin/newsletters/templates/list") ?>')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('templatesList');
            if (data.templates && data.templates.length > 0) {
                let html = '<div class="row g-3">';
                data.templates.forEach(t => {
                    html += `
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="ti ti-mail-opened text-primary mb-2" style="font-size: 2rem;"></i>
                                <h6 class="fw-bold">${t.name}</h6>
                                <p class="fs-12 text-muted mb-3">Created: ${t.created_at.substring(0,10)}</p>
                                <button class="btn btn-sm btn-outline-primary" onclick="loadTemplateIntoEditor(${t.id})">Use Template</button>
                            </div>
                        </div>
                    </div>`;
                });
                html += '</div>';
                container.innerHTML = html;
                
                // Store templates in window for easy loading
                window.availableTemplates = data.templates;
            } else {
                container.innerHTML = '<div class="text-center py-4 text-muted">No templates saved yet. Design an email and click "Save as Template"!</div>';
            }
        });
    }

    function loadTemplateIntoEditor(id) {
        const template = window.availableTemplates.find(t => t.id == id);
        if (template) {
            window.editorA.setComponents(template.html_content);
            const modal = bootstrap.Modal.getInstance(document.getElementById('templatesModal'));
            modal.hide();
        }
    }
</script>
<?= $this->endSection() ?>
