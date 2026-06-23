<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('section') ?>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Inter:wght@400;600;700&family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
    .editor-container {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }
    .certificate-preview-wrap {
        flex: 1;
        background: #f0f0f0;
        padding: 40px;
        display: flex;
        justify-content: center;
        overflow: auto;
        min-height: 600px;
    }
    .cert-canvas {
        width: 842px; /* A4 Landscape at 72dpi, will scale for high res */
        height: 595px;
        background: #fff;
        position: relative;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border: 1px solid #ddd;
    }
    .cert-element {
        position: absolute;
        cursor: move;
        user-select: none;
        border: 1px dashed transparent;
    }
    .cert-element:hover {
        border-color: var(--primary-color);
    }
    .cert-element.active {
        border-color: var(--secondary-color);
        background: rgba(245, 166, 35, 0.05);
    }
    .controls-panel {
        width: 350px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .control-group {
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .control-group:last-child {
        border-bottom: none;
    }
    .control-label {
        font-weight: bold;
        display: block;
        margin-bottom: 10px;
        font-size: 14px;
        color: #333;
    }
    .property-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .property-row label {
        font-size: 12px;
        width: 80px;
        margin-bottom: 0;
    }
    .property-row input {
        flex: 1;
    }
</style>

<div class="container-fluid">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Certificate Super-Editor</h1>
            <div class="ms-md-1 ms-0">
                <a href="<?= base_url('admin/elearning') ?>" class="btn btn-light">Back to Courses</a>
                <button type="button" class="btn btn-primary" id="saveTemplateBtn">Save Configuration</button>
            </div>
        </div>

        <div class="alert alert-info">
            <strong>Superpower Enabled:</strong> Drag elements to reposition. Use the right panel to toggle visibility, change colors, and adjust sizes. 
            <span class="badge bg-warning text-dark ms-2">Paid Course Only</span>
        </div>

        <div class="editor-container">
            <div class="certificate-preview-wrap">
                <div class="cert-canvas" id="certCanvas" style="border: <?= !empty($template['background_image']) ? 'none' : '15px solid '.($template['primary_color'] ?? 'var(--primary-color)') ?>; <?php if (!empty($template['background_image'])): ?>background-image: url('<?= base_url($template['background_image']) ?>'); background-size: cover;<?php endif; ?>">
                    <div class="cert-border-inner" id="certBorderInner" style="position: absolute; top: 5px; left: 5px; right: 5px; bottom: 5px; border: <?= !empty($template['background_image']) ? 'none' : '2px solid '.($template['secondary_color'] ?? 'var(--secondary-color)') ?>;"></div>
                    
                    <div class="cert-element text-center" id="el-logo" data-id="logo" style="top: 40px; left: 371px; width: 100px;">
                        <img src="<?= base_url('auth/img/logo.png') ?>" style="width: 100%;">
                    </div>
                    
                    <div class="cert-element text-center" id="el-title" data-id="title" style="top: 130px; left: 280px; width: 280px; font-size: 40px; font-weight: bold; color: <?= $template['primary_color'] ?? 'var(--primary-color)' ?>; text-transform: uppercase;">
                        Certificate
                    </div>
                    
                    <div class="cert-element text-center" id="el-subtitle" data-id="subtitle" style="top: 185px; left: 310px; width: 220px; font-size: 18px; color: #666;">
                        OF COURSE COMPLETION
                    </div>
                    
                    <div class="cert-element text-center" id="el-recipient_name" data-id="recipient_name" style="top: 250px; left: 200px; width: 440px; font-size: 36px; font-weight: bold; border-bottom: 2px solid <?= $template['secondary_color'] ?? 'var(--secondary-color)' ?>;">
                        John Doe (Recipient)
                    </div>
                    
                    <div class="cert-element text-center" id="el-course_title" data-id="course_title" style="top: 330px; left: 200px; width: 440px; font-size: 22px; color: <?= $template['primary_color'] ?? 'var(--primary-color)' ?>; font-weight: bold;">
                        Course Title Here
                    </div>
                    
                    <div class="cert-element text-center" id="el-date_issued" data-id="date_issued" style="top: 420px; left: 150px; width: 150px; font-size: 14px;">
                        October 24, 2024
                    </div>
                    
                    <div class="cert-element" id="el-qr_code" data-id="qr_code" style="top: 480px; left: 50px;">
                        <div class="text-center" style="width: 80px; height: 80px; background: #000; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px">
                            QR CODE<br>VERIFICATION
                        </div>
                    </div>
                    
                    <div class="cert-element text-center" id="el-signature" data-id="signature" style="top: 380px; left: 550px; width: 150px;">
                        <div class="text-center">
                            <img src="<?= base_url(setting('Elearning.certificate_signature') ?: 'images/jobber.png') ?>" style="width: 120px; max-height: 60px;">
                            <div style="border-top: 1px solid #ccc; width: 150px; margin-top: 5px;"></div>
                            <div style="font-size: 12px;">Authorized Signature</div>
                        </div>
                    </div>

                    <div class="cert-element text-center" id="el-certificate_code" data-id="certificate_code" style="top: 540px; left: 320px; width: 200px; font-size: 10px; color: #999;">
                        CERT-XXXX-XXXX-XXXX
                    </div>
                </div>
            </div>

            <div class="controls-panel">
                <form id="templateForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="course_id" value="<?= $course['id'] ?? '' ?>">
                    <input type="hidden" id="layout_json" name="layout_json">

                    <div class="control-group">
                        <label class="control-label">Template Mode</label>
                        <select name="template_mode" id="templateMode" class="form-select form-select-sm mb-2">
                            <option value="builder" <?= ($template['template_mode'] ?? 'builder') === 'builder' || ($template['template_mode'] ?? '') === 'image' ? 'selected' : '' ?>>Visual Drag-Drop (Default & JPEG Templates)</option>
                            <option value="html" <?= ($template['template_mode'] ?? '') === 'html' ? 'selected' : '' ?>>Advanced HTML/CSS Code</option>
                        </select>
                        <p class="text-muted fs-11" id="modeDescription">Visual builder is active.</p>
                    </div>

                    <div id="htmlEditorSection" style="display: <?= ($template['template_mode'] ?? '') === 'html' ? 'block' : 'none' ?>;">
                        <div class="control-group">
                            <div class="alert alert-warning p-2 fs-11 mb-3">
                                <h6 class="fw-bold mb-1"><i class="ti ti-info-circle me-1"></i>HTML Mode Guidelines:</h6>
                                <ul class="mb-0 ps-3">
                                    <li><strong>Engine:</strong> Uses Dompdf. No <b>Flexbox</b> or <b>Grid</b> support. Use <b>Tables</b> or <b>Absolute Positioning</b>.</li>
                                    <li><strong>Dimensions:</strong> Design for <b>A4 Landscape</b> (approx. 1120px x 790px).</li>
                                    <li><strong>Styles:</strong> Inline CSS is most reliable. Avoid external CSS files.</li>
                                    <li><strong>Fonts:</strong> Standard fonts (Helvetica, Arial, Times) work best. Google Fonts require full URLs.</li>
                                    <li><strong>Images:</strong> Always use <code><?= base_url() ?></code> for image paths.</li>
                                </ul>
                            </div>
                            
                            <label class="control-label">Custom HTML Code</label>
                            <textarea name="custom_html" id="custom_html" class="form-control fs-12 font-monospace" rows="15" placeholder="<html>...</html>"><?= esc($template['custom_html'] ?? '') ?></textarea>
                            <div class="mt-2 bg-light p-2 rounded border">
                                <label class="fs-11 fw-bold d-block mb-1 text-primary">Dynamic Placeholders:</label>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-white text-dark border" title="Student's Full Name">{{name}}</span>
                                    <span class="badge bg-white text-dark border" title="Course Title">{{course}}</span>
                                    <span class="badge bg-white text-dark border" title="Issue Date">{{date}}</span>
                                    <span class="badge bg-white text-dark border" title="Unique Cert ID">{{code}}</span>
                                    <span class="badge bg-white text-dark border" title="Verification QR Code (img tag)">{{qr_code}}</span>
                                    <span class="badge bg-white text-dark border" title="Authorized Signature (img tag)">{{signature}}</span>
                                </div>
                                <p class="text-muted fs-10 mt-1 mb-0 italic">Example: &lt;h1&gt;Congratulations, {{name}}!&lt;/h1&gt;</p>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-info w-100" id="previewHtmlBtn" data-bs-toggle="modal" data-bs-target="#htmlPreviewModal">
                                    <i class="ti ti-eye me-1"></i> Live Preview
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="builderControls" style="display: <?= ($template['template_mode'] ?? 'builder') === 'builder' ? 'block' : 'none' ?>;">
                        <div class="control-group">
                            <label class="control-label">Global Theme</label>
                        <div class="property-row">
                            <label>Primary</label>
                            <input type="color" name="primary_color" value="<?= $template['primary_color'] ?? 'var(--primary-color)' ?>" class="form-control form-control-sm theme-trigger" data-type="primary">
                        </div>
                        <div class="property-row">
                            <label>Secondary</label>
                            <input type="color" name="secondary_color" value="<?= $template['secondary_color'] ?? 'var(--secondary-color)' ?>" class="form-control form-control-sm theme-trigger" data-type="secondary">
                        </div>
                    </div>

                    <div id="elementControls">
                        <label class="control-label">Element Properties</label>
                        <p class="text-muted fs-12">Click an element on the certificate to edit its specific properties.</p>
                        
                        <div id="activeElementProps" class="d-none">
                            <div class="property-row">
                                <label>Visible</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="prop-visible">
                                </div>
                            </div>
                            <div class="property-row">
                                <label>Font Family</label>
                                <select id="prop-fontFamily" class="form-select form-select-sm">
                                    <option value="Helvetica">Helvetica (Default)</option>
                                    <option value="'Inter', sans-serif">Inter</option>
                                    <option value="'Roboto', sans-serif">Roboto</option>
                                    <option value="'Playfair Display', serif">Playfair Display</option>
                                    <option value="'Montserrat', sans-serif">Montserrat</option>
                                    <option value="'Dancing Script', cursive">Dancing Script</option>
                                </select>
                            </div>
                            <div class="property-row">
                                <label>Font Size</label>
                                <input type="number" id="prop-fontSize" class="form-control form-control-sm">
                            </div>
                            <div class="property-row">
                                <label>Width</label>
                                <input type="number" id="prop-width" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="control-group mt-4">
                        <label class="control-label">Background Image (JPEG Template)</label>
                        <input type="file" name="background_image" id="bgImageInput" class="form-control form-control-sm" accept="image/*">
                        <p class="text-muted fs-10 mt-1">Recommended: 3508 x 2480 pixels (A4 @ 300DPI). Uploading an image disables default borders.</p>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-danger w-100" id="resetLayoutBtn">Reset to Default Layout</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- HTML Preview Modal -->
<div class="modal fade" id="htmlPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">HTML Certificate Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="background: #e9ecef; overflow: auto; display: flex; justify-content: center; align-items: center; min-height: 600px;">
                <iframe id="htmlPreviewFrame" style="width: 1122px; height: 793px; border: 1px solid #ccc; background: #fff; box-shadow: 0 0 15px rgba(0,0,0,0.2);"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
    if (window.CKEDITOR) {
        CKEDITOR.replace('custom_html', {
            height: 400,
            fullPage: true,
            allowedContent: true,
            extraPlugins: 'sourcearea',
            removeButtons: ''
        });
    }
    const layout = <?= $template['layout_json'] ?? '{}' ?>;
    const canvas = document.getElementById('certCanvas');
    let activeElement = null;

    // Toggle Editor Sections
    document.getElementById('templateMode').addEventListener('change', function() {
        const mode = this.value;
        const htmlSection = document.getElementById('htmlEditorSection');
        const builderControls = document.getElementById('builderControls');
        const modeDesc = document.getElementById('modeDescription');
        
        if (mode === 'html') {
            htmlSection.style.display = 'block';
            builderControls.style.display = 'none';
            modeDesc.innerText = 'Advanced HTML mode active.';
        } else {
            htmlSection.style.display = 'none';
            builderControls.style.display = 'block';
            modeDesc.innerText = 'Visual builder active (drag elements over background).';
        }
    });

    // Apply initial layout
    Object.keys(layout).forEach(id => {
        const el = document.getElementById('el-' + id);
        if (el) {
            const props = layout[id];
            if (props.top) el.style.top = props.top;
            if (props.left) el.style.left = props.left;
            if (props.transform) el.style.transform = props.transform;
            if (props.font_size) el.style.fontSize = props.font_size;
            if (props.font_family) el.style.fontFamily = props.font_family;
            if (props.width) {
                const img = el.querySelector('img');
                if (img) img.style.width = props.width;
                else el.style.width = props.width;
            }
            if (props.visible === false) el.style.display = 'none';
        }
    });

    // Make elements draggable
    interact('.cert-element').draggable({
        listeners: {
            move(event) {
                const target = event.target;
                
                // Get current top/left
                let currentLeft = parseFloat(target.style.left) || 0;
                let currentTop = parseFloat(target.style.top) || 0;
                
                // Calculate new position
                let newLeft = currentLeft + event.dx;
                let newTop = currentTop + event.dy;
                
                // Apply absolute position instead of transform
                target.style.left = newLeft + 'px';
                target.style.top = newTop + 'px';
            },
            end(event) {
                // Finalize position, already updated during move
                const target = event.target;
                target.style.transform = 'none';
                target.setAttribute('data-x', 0);
                target.setAttribute('data-y', 0);
            }
        }
    });

    // Handle element selection
    document.querySelectorAll('.cert-element').forEach(el => {
        el.addEventListener('click', (e) => {
            document.querySelectorAll('.cert-element').forEach(i => i.classList.remove('active'));
            el.classList.add('active');
            activeElement = el;
            showProps(el);
            e.stopPropagation();
        });
    });

    // Preview Background Image
    document.getElementById('bgImageInput').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                canvas.style.backgroundImage = 'url(' + e.target.result + ')';
                canvas.style.backgroundSize = '100% 100%';
                canvas.style.border = 'none';
                document.getElementById('certBorderInner').style.border = 'none';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Reset Default Layout
    document.getElementById('resetLayoutBtn').addEventListener('click', function() {
        if(!confirm('Reset all elements to their default positions? This cannot be undone until saved.')) return;
        
        const defaultPositions = {
            'logo': { top: '40px', left: '371px' },
            'title': { top: '130px', left: '280px' },
            'subtitle': { top: '185px', left: '310px' },
            'recipient_name': { top: '250px', left: '200px' },
            'course_title': { top: '330px', left: '200px' },
            'date_issued': { top: '420px', left: '150px' },
            'qr_code': { top: '480px', left: '50px' },
            'signature': { top: '380px', left: '550px' },
            'certificate_code': { top: '540px', left: '320px' }
        };

        Object.keys(defaultPositions).forEach(id => {
            const el = document.getElementById('el-' + id);
            if (el) {
                el.style.top = defaultPositions[id].top;
                el.style.left = defaultPositions[id].left;
                el.style.transform = 'none';
                el.setAttribute('data-x', 0);
                el.setAttribute('data-y', 0);
            }
        });
    });

    // Preview HTML Mode
    document.getElementById('previewHtmlBtn').addEventListener('click', function() {
        let htmlContent = '';
        if (window.CKEDITOR && CKEDITOR.instances['custom_html']) {
            htmlContent = CKEDITOR.instances['custom_html'].getData();
        } else {
            htmlContent = document.getElementById('custom_html').value;
        }
        const dummyData = {
            '{{name}}': 'John Doe',
            '{{course}}': 'Advanced PHP Masterclass',
            '{{date}}': 'October 24, 2024',
            '{{code}}': 'CERT-1234-ABCD-5678',
            '{{qr_code}}': '<div class="text-center" style="display:inline-block; width:100px; height:100px; background:#000; color:#fff; line-height:100px; font-size:12px">QR_CODE</div>',
            '{{signature}}': '<div class="text-center" style="display:inline-block; width:150px; height:60px; border-bottom:1px solid #000; line-height:60px; font-family:cursive">Jane Smith</div>'
        };

        // Replace placeholders
        for (const [key, value] of Object.entries(dummyData)) {
            htmlContent = htmlContent.split(key).join(value);
        }

        const iframe = document.getElementById('htmlPreviewFrame');
        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write(htmlContent);
        doc.close();
    });

    function showProps(el) {
        const propsDiv = document.getElementById('activeElementProps');
        propsDiv.style.display = 'block';
        
        document.getElementById('prop-visible').checked = el.style.display !== 'none';
        document.getElementById('prop-fontSize').value = parseInt(window.getComputedStyle(el).fontSize) || 0;
        document.getElementById('prop-width').value = parseInt(window.getComputedStyle(el).width) || 0;
        
        const currentFont = window.getComputedStyle(el).fontFamily;
        const fontSelect = document.getElementById('prop-fontFamily');
        // Simple matching logic
        if (currentFont.includes('Inter')) fontSelect.value = "'Inter', sans-serif";
        else if (currentFont.includes('Roboto')) fontSelect.value = "'Roboto', sans-serif";
        else if (currentFont.includes('Playfair')) fontSelect.value = "'Playfair Display', serif";
        else if (currentFont.includes('Montserrat')) fontSelect.value = "'Montserrat', sans-serif";
        else if (currentFont.includes('Dancing Script')) fontSelect.value = "'Dancing Script', cursive";
        else fontSelect.value = "Helvetica";
    }

    // Update props in real-time
    document.getElementById('prop-visible').addEventListener('change', (e) => {
        if (activeElement) activeElement.style.display = e.target.checked ? 'block' : 'none';
    });

    document.getElementById('prop-fontSize').addEventListener('input', (e) => {
        if (activeElement) activeElement.style.fontSize = e.target.value + 'px';
    });

    document.getElementById('prop-fontFamily').addEventListener('change', (e) => {
        if (activeElement) activeElement.style.fontFamily = e.target.value;
    });

    document.getElementById('prop-width').addEventListener('input', (e) => {
        if (activeElement) {
            const img = activeElement.querySelector('img');
            if (img) img.style.width = e.target.value + 'px';
            else activeElement.style.width = e.target.value + 'px';
        }
    });

    // Theme triggers
    document.querySelectorAll('.theme-trigger').forEach(input => {
        input.addEventListener('input', (e) => {
            const val = e.target.value;
            if (e.target.dataset.type === 'primary') {
                canvas.style.borderColor = val;
                document.getElementById('el-title').style.color = val;
                document.getElementById('el-course_title').style.color = val;
            } else {
                canvas.querySelector('.cert-border-inner').style.borderColor = val;
                document.getElementById('el-recipient_name').style.borderColor = val;
            }
        });
    });

    // Save configuration
    document.getElementById('saveTemplateBtn').addEventListener('click', () => {
        const finalLayout = {};
        document.querySelectorAll('.cert-element').forEach(el => {
            const id = el.dataset.id;
            const img = el.querySelector('img');
            finalLayout[id] = {
                top: el.style.top,
                left: el.style.left,
                font_size: el.style.fontSize,
                font_family: el.style.fontFamily,
                width: img ? img.style.width : el.style.width,
                visible: el.style.display !== 'none'
            };
        });

        document.getElementById('layout_json').value = JSON.stringify(finalLayout);
        
        if (window.CKEDITOR && CKEDITOR.instances['custom_html']) {
            CKEDITOR.instances['custom_html'].updateElement();
        }

        const formData = new FormData(document.getElementById('templateForm'));
        fetch('<?= base_url('admin/elearning/save-certificate-template') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) toastr.info('Certificate template saved!');
            else toastr.error('Error: ' + data.message);
        });
    });
</script>
<?= $this->endSection() ?>
