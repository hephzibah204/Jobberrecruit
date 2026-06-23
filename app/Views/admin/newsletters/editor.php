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

                <!-- CKEditor Textarea -->
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark">Email Message Body</label>
                    <textarea name="content" id="newsletter-content" class="form-control" placeholder="Compose a beautiful email..."><?= $newsletter ? esc($newsletter->content) : '' ?></textarea>
                </div>
                
                <!-- Variation B CKEditor Textarea (only shown if needed or we could reuse plain textarea, but CKEditor is better) -->
                <div class="mb-3 d-none" id="standard_editor_b_wrapper" >
                    <label class="form-label fw-semibold text-dark">Variation B Email Message Body</label>
                    <textarea name="content_b" id="newsletter-content-b" class="form-control" placeholder="Variation B Content..."><?= $newsletter ? esc($newsletter->content_b) : '' ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="<?= base_url('admin/newsletters') ?>" class="btn btn-light px-4" style="border-radius: 8px;">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 rounded" >Save Campaign</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let editorOptions = {
            height: 400,
            removePlugins: 'exportpdf',
            filebrowserUploadUrl: "<?= base_url('admin/blogs/upload-editor-image-ck4') ?>?_token=<?= csrf_hash() ?>",
            filebrowserUploadMethod: 'form',
            allowedContent: true,
            extraPlugins: 'justify,colorbutton,font,smiley,image2,sourcearea',
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
            removeButtons: 'Save,NewPage,Preview,Print,Templates'
        };

        if (window.CKEDITOR) {
            if (document.getElementById('newsletter-content')) {
                CKEDITOR.replace('newsletter-content', editorOptions);
            }
            if (document.getElementById('newsletter-content-b')) {
                CKEDITOR.replace('newsletter-content-b', editorOptions);
            }
        } else {
            console.error('CKEditor failed to load.');
        }

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                if (window.CKEDITOR) {
                    for (var instanceName in CKEDITOR.instances) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
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
            const standardEditorWrapper = variation === 'A' ? document.getElementById('newsletter-content').parentElement : document.getElementById('standard_editor_b_wrapper');
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
</script>
<?= $this->endSection() ?>
