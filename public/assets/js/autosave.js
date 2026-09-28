/**
 * JobberRecruit Universal Autosave
 * Periodically saves form data to local storage.
 * To use: add class "autosave-form" to any <form>.
 */
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.autosave-form, form#post-job-form, form#blogForm');
    if (forms.length === 0) return;

    forms.forEach(form => {
        const formId = form.id || 'unnamed-form';
        // Unique key for the page + form
        const autosaveKey = `jr_draft_${window.location.pathname}_${formId}`;

        // Expose global clear function
        window.clearAutosave = function() {
            localStorage.removeItem(autosaveKey);
        };

        function getFormData() {
            let data = {};
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                // ignore sensitive or file fields
                if (key.includes('csrf') || key.includes('password') || key.includes('file') || key.includes('image')) continue;
                
                if (data[key]) {
                    if (!Array.isArray(data[key])) {
                        data[key] = [data[key]];
                    }
                    data[key].push(value);
                } else {
                    data[key] = value;
                }
            }
            // CKEditor 4 support
            if (typeof CKEDITOR !== 'undefined') {
                for (let instance in CKEDITOR.instances) {
                    const inst = CKEDITOR.instances[instance];
                    if (inst && inst.element && inst.element.$ && inst.element.$.form === form) {
                        data[instance] = inst.getData();
                    }
                }
            }
            return data;
        }

        function restoreFormData(data) {
            for (let key in data) {
                const elements = form.querySelectorAll(`[name="${key}"]`);
                if (!elements.length) continue;
                
                const val = data[key];
                
                if (elements[0].type === 'radio' || elements[0].type === 'checkbox') {
                    elements.forEach(el => {
                        if (Array.isArray(val)) {
                            el.checked = val.includes(el.value);
                        } else {
                            el.checked = (el.value === val);
                        }
                    });
                } else {
                    elements[0].value = val;
                    // Try to trigger formatting/counting scripts
                    elements[0].dispatchEvent(new Event('input', { bubbles: true }));
                }
            }

            // Restore CKEditor
            if (typeof CKEDITOR !== 'undefined') {
                for (let key in data) {
                    if (CKEDITOR.instances[key]) {
                        CKEDITOR.instances[key].setData(data[key]);
                    }
                }
            }
        }

        // 1. Check for existing draft on load
        const savedRaw = localStorage.getItem(autosaveKey);
        if (savedRaw) {
            try {
                const savedData = JSON.parse(savedRaw);
                // Only restore if it's not editing an existing populated record
                // (Very basic heuristic: if title is empty, it's new)
                const titleField = form.querySelector('[name="title"]');
                let shouldAsk = true;
                if (titleField && titleField.value.trim().length > 0) {
                    shouldAsk = false; // Already editing something, don't overwrite
                }

                if (shouldAsk && Object.keys(savedData).length > 0) {
                    if (confirm('We found an unsaved draft on your device. Do you want to restore it?')) {
                        if (typeof CKEDITOR !== 'undefined') {
                            CKEDITOR.on('instanceReady', function() {
                                restoreFormData(savedData);
                            });
                        }
                        restoreFormData(savedData);
                        if (typeof toastr !== 'undefined') toastr.success('Draft restored successfully.');
                    } else {
                        localStorage.removeItem(autosaveKey);
                    }
                }
            } catch(e) {}
        }

        // 2. Start Autosaving
        setInterval(() => {
            const data = getFormData();
            // Basic check if form is practically empty
            const rawStr = Object.values(data).join('').trim();
            if (rawStr.length < 5) return;
            
            localStorage.setItem(autosaveKey, JSON.stringify(data));
            
            // Show subtle indicator
            let indicator = document.getElementById(`autosave-indicator-${formId}`);
            if (!indicator) {
                const submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('.btn-accent');
                if (submitBtn && submitBtn.parentNode) {
                    indicator = document.createElement('span');
                    indicator.id = `autosave-indicator-${formId}`;
                    indicator.style.cssText = 'font-size:11px; color:#6c757d; margin-left:15px; display:inline-flex; align-items:center; gap:4px;';
                    submitBtn.parentNode.insertBefore(indicator, submitBtn.nextSibling);
                }
            }
            if (indicator) {
                const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});
                indicator.innerHTML = `<svg style="width:12px;height:12px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Draft saved ${time}`;
            }
        }, 5000); // 5 seconds

        // 3. Clear on standard HTML submit
        form.addEventListener('submit', () => {
            // We only clear if they haven't explicitly blocked it.
            // For AJAX forms, this fires BEFORE the success callback, so if AJAX fails, draft is lost.
            // Therefore, we won't clear it here. We rely on window.clearAutosave() in the AJAX success callback.
        });
    });
});
