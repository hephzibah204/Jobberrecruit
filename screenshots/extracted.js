
    function startFromScratch() {
        // Close the overlay and let the builder load normally
        document.getElementById('resumeOnboardingOverlay').style.animation = 'fadeOutOverlay 0.3s ease forwards';
        setTimeout(() => {
            document.getElementById('resumeOnboardingOverlay').remove();
        }, 300);
    }

    function importFromProfile() {
        const card = document.getElementById('ob-profile-card');
        card.innerHTML = '<div style="text-align:center;padding:2rem;"><div class="spinner" role="status"></div><p style="color:#94a3b8;margin-top:1rem;font-size:0.9rem;">Creating your resume from profile...</p></div>';
        document.getElementById('import-profile-form').submit();
    }

    function toggleClonePanel() {
        const panel = document.getElementById('ob-clone-panel');
        const isVisible = panel.style.display === 'block';
        panel.style.display = isVisible ? 'none' : 'block';
        document.getElementById('ob-clone-card').style.borderColor = isVisible ? '' : 'rgba(245,158,11,0.5)';
    }

    // Add fade-out keyframe dynamically
    const style = document.createElement('style');
    style.textContent = '@keyframes fadeOutOverlay { from { opacity:1; } to { opacity:0; } }';
    document.head.appendChild(style);


    // ═══ MOCKUP: Accordion toggle ═══
    function toggleEdSec(head) {
        var sec = head.closest('.ed-sec');
        if (sec) {
            sec.classList.toggle('open');
        }
    }

    // ═══ MOCKUP: Mobile tab switcher ═══
    function switchMobileTab(mode, el) {
        document.querySelectorAll('.rb-tab').forEach(function(b) {
            b.classList.remove('on', 'active');
            b.setAttribute('aria-selected', 'false');
        });
        if (el) {
            el.classList.add('on', 'active');
            el.setAttribute('aria-selected', 'true');
        }
        var split = document.getElementById('rb-split') || document.querySelector('.rb-split');
        if (split) {
            split.className = 'rb-split tab-' + (mode === 'edit' ? 'edit' : 'view');
        }
    }

    // ═══ MOCKUP: Design-bar accent color ═══
    function setAccentColor(acc, acc2, el) {
        if (typeof acc2 === 'object' || acc2 instanceof HTMLElement) {
            el = acc2;
            acc2 = acc;
        }
        document.querySelectorAll('.swatches .sw').forEach(function(s) { s.classList.remove('active', 'on'); });
        if (el) el.classList.add('active', 'on');
        var doc = document.querySelector('#doc');
        if (doc) {
            doc.style.setProperty('--acc', acc);
            doc.style.setProperty('--acc2', acc2 || acc);
        }
    }

    // ═══ MOCKUP: Design-bar font family ═══
    function setFontFamily(fontClass) {
        var doc = document.querySelector('#doc');
        if (doc) {
            doc.classList.remove('f-serif', 'f-clean');
            if (fontClass) doc.classList.add(fontClass);
        }
    }

    // ═══ MOCKUP: Select template from topbar ═══
    window.selectTemplate = function(val) {
        $('#tpl-select').val(val).trigger('change');
    };

    // ═══ MOCKUP: Design-bar spacing toggle ═══
    function setSpacing(mode, el) {
        document.querySelectorAll('.dens button').forEach(function(b) { b.classList.remove('active', 'on'); });
        if (el) el.classList.add('active', 'on');
        var doc = document.querySelector('#doc');
        if (doc) {
            doc.classList.remove('spacing-roomy', 'spacing-tight');
            doc.classList.add('spacing-' + (mode === 'tight' || mode === true ? 'tight' : 'roomy'));
        }
    }

    // ═══ MOCKUP: Accordion open on next/prev click ═══
    function openEdSec(step) {
        var sec = document.querySelector('.ed-sec[data-step="' + step + '"]');
        if (sec && !sec.classList.contains('open')) {
            sec.classList.add('open');
            sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    $(document).ready(function() {
        // Utility: escape HTML for safe insertion into preview
        function escapeHtml(str) {
            return String(str || '').replace(/[&<>"']/g, function (s) {
                return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s]);
            });
        }
        window.escapeHtml = escapeHtml;
        if (typeof renderLivePreview === 'function') {
            renderLivePreview();
        }
        if (typeof refreshAts === 'function') {
            refreshAts();
        }

        // Matches PHP's date('M Y', strtotime($date)) used by the download templates
        function formatMonthYear(dateStr) {
            if (!dateStr) return '';
            var d = new Date(dateStr + 'T00:00:00');
            if (isNaN(d.getTime())) return dateStr;
            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return months[d.getMonth()] + ' ' + d.getFullYear();
        }

        // --- NEW AI AJAX HANDLERS ---
        const gatherResumeData = () => {
            let data = new FormData(document.getElementById('resume-form'));
            let exp = [];
            $('.ed-item[data-type="experience"]').each(function() {
                exp.push({
                    position: $(this).find('input[name="exp_position[]"]').val(),
                    company: $(this).find('input[name="exp_company[]"]').val(),
                    description: $(this).find('textarea[name="exp_desc[]"]').val()
                });
            });
            let skills = [];
            $('#skills-container input').each(function() {
                if($(this).val()) skills.push($(this).val());
            });
            
            return {
                title: $('input[name="title"]').val(),
                summary: $('#resume-summary').val(),
                experience: exp,
                skills: skills
            };
        };

        // More AI Outputs
        $('[data-out]').on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let type = btn.data('out');
            let data = gatherResumeData();
            data.type = type;
            
            let originalText = btn.html();
            btn.html('<i class="ti ti-loader fa-spin"></i> Generating...').prop('disabled', true);
            
            $.post('/candidate/resumes/ai/generate-output', data, function(res) {
                $('#out-wrap').removeClass('d-none');
                $('#out-txt').val(res.output);
                btn.html(originalText).prop('disabled', false);
            }).fail(function(err) {
                alert(err.responseJSON?.message || 'Failed to generate output.');
                btn.html(originalText).prop('disabled', false);
            });
        });

        // Career Tools
        $('[data-career]').on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let toolType = btn.data('career');
            let data = gatherResumeData();
            data.tool_type = toolType;
            data.industry = $('#industry-pick').val() || 'general';
            
            let originalText = btn.html();
            btn.html('<i class="ti ti-loader fa-spin"></i> Loading...').prop('disabled', true);
            
            $.post('/candidate/resumes/ai/career-tools', data, function(res) {
                $('#career-out').removeClass('d-none').html(res.output);
                btn.html(originalText).prop('disabled', false);
            }).fail(function(err) {
                alert(err.responseJSON?.message || 'Failed to load career tools.');
                btn.html(originalText).prop('disabled', false);
            });
        });

        // Writing Review trigger (could be on section open)
        $('#sec-review .ed-head').on('click', function() {
            let sec = $(this).closest('.ed-sec');
            if (sec.hasClass('open') && $('#issues').is(':empty')) {
                $('#issues').html('<p class="text-muted"><i class="ti ti-loader fa-spin"></i> Analyzing writing style...</p>');
                $.post('/candidate/resumes/ai/writing-review', gatherResumeData(), function(res) {
                    $('#issues').html(res.review);
                }).fail(function() {
                    $('#issues').html('<p class="text-danger">Failed to analyze.</p>');
                });
            }
        });

        // Recruiter View trigger
        $('#sec-recruiter .ed-head').on('click', function() {
            let sec = $(this).closest('.ed-sec');
            if (sec.hasClass('open') && $('#recruiter-body').is(':empty')) {
                $('#recruiter-body').html('<p class="text-muted"><i class="ti ti-loader fa-spin"></i> Evaluating ATS score...</p>');
                $.post('/candidate/resumes/ai/recruiter-view', gatherResumeData(), function(res) {
                    $('#recruiter-body').html(res.recruiter_view);
                }).fail(function() {
                    $('#recruiter-body').html('<p class="text-danger">Failed to evaluate.</p>');
                });
            }
        });

        // Import CV basic handler
        $('#cv-file').on('change', function(e) {
            let file = e.target.files[0];
            if (!file) return;
            $('#import-note').removeClass('d-none').html('<p class="text-primary"><i class="ti ti-loader fa-spin"></i> Uploading and extracting CV...</p>');
            
            let fd = new FormData();
            fd.append('cv', file);
            // This endpoint would normally parse the PDF/Doc. For now, we mock success.
            setTimeout(() => {
                $('#import-note').addClass('d-none');
                $('#import-orig').removeClass('d-none');
                $('#dz-name').text(file.name);
            }, 1500);
        });

        // Version History (Local Snapshots)
        function takeSnapshot() {
            let data = gatherResumeData();
            let snapshots = JSON.parse(localStorage.getItem('resume_snapshots_' + $('input[name="id"]').val()) || '[]');
            let time = new Date().toLocaleTimeString();
            snapshots.push({ time: time, data: data });
            if (snapshots.length > 10) snapshots.shift(); // Keep last 10
            localStorage.setItem('resume_snapshots_' + $('input[name="id"]').val(), JSON.stringify(snapshots));
            renderSnapshots(snapshots);
        }

        function renderSnapshots(snapshots) {
            let html = '<p class="text-muted mb-3" style="font-size:.75rem;">Snapshots are captured automatically as you edit.</p>';
            if (snapshots.length === 0) {
                html += '<p class="text-muted fst-italic">No snapshots yet.</p>';
            } else {
                html += '<div class="list-group list-group-flush">';
                snapshots.reverse().forEach((s, i) => {
                    html += `<button type="button" class="list-group-item list-group-item-action py-2 px-1" style="font-size:0.85rem;">
                                <i class="ti ti-clock me-2 text-primary"></i> Snapshot at ${s.time}
                             </button>`;
                });
                html += '</div>';
            }
            $('#hist-list').html(html);
        }

        // Trigger snapshot periodically if changes were made, or just when the section opens
        $('#sec-history .ed-head').on('click', function() {
            let sec = $(this).closest('.ed-sec');
            if (sec.hasClass('open')) {
                takeSnapshot();
            }
        });

        // ── LIVE PREVIEW GENERATION ──
        function renderLivePreview() {
            var rawName = $('input[name="full_name"]').val();
            var rawTitle = $('input[name="title"]').val();
            var rawEmail = $('input[name="email"]').val();
            var rawPhone = $('input[name="phone"]').val();
            var rawLoc = $('input[name="location"]').val();
            var rawLinkedin = $('input[name="linkedin"]').val();
            var rawSummary = $('#resume-summary').val();

            var name = (rawName && rawName.trim()) ? rawName : 'Franklin Ezirim';
            var title = (rawTitle && rawTitle.trim()) ? rawTitle : 'Accountant';
            var email = (rawEmail && rawEmail.trim()) ? rawEmail : 'Ezirim.c.franklin@gmail.com';
            var phone = (rawPhone && rawPhone.trim()) ? rawPhone : '0703 839 9120';
            var locationStr = (rawLoc && rawLoc.trim()) ? rawLoc : 'Lagos, Nigeria';
            var linkedin = (rawLinkedin && rawLinkedin.trim()) ? rawLinkedin : 'linkedin.com/in/franklin-ezirim';
            var summary = (rawSummary && rawSummary.trim()) ? rawSummary : 'Accountant with 5 years of experience across bookkeeping, reconciliation and management reporting. Comfortable with Nigerian tax compliance and month-end close.';
            
            // Selected layout & density
            var tpl = $('#template-select-top').val() || $('#tpl-select').val() || 't-modern';
            if (!tpl.startsWith('t-')) {
                var tplMap = { 'classic':'t-classic', 'modern':'t-modern', 'creative':'t-creative', 'executive':'t-exec', 'minimalist':'t-minimal' };
                tpl = tplMap[tpl] || ('t-' + tpl);
            }
            var spacing = $('#spacing-roomy-btn').hasClass('on') ? 'spacing-roomy' : 'spacing-tight';
            
            // Container update
            var $doc = $('#doc');
            $doc.removeClass().addClass('doc ' + tpl + ' ' + spacing + ' wm-tile guides');
            
            var contactHtml = '';
            if (email) contactHtml += '<span>' + escapeHtml(email) + '</span>';
            if (phone) contactHtml += '<span>' + escapeHtml(phone) + '</span>';
            if (locationStr) contactHtml += '<span>' + escapeHtml(locationStr) + '</span>';
            if (linkedin) contactHtml += '<span>' + escapeHtml(linkedin.replace(/^https?:\/\/(www\.)?/, '')) + '</span>';
            
            var html = '<header class="d-head"><h1>' + escapeHtml(name) + '</h1>';
            if (title) html += '<div class="d-title">' + escapeHtml(title) + '</div>';
            if (contactHtml) html += '<div class="d-contact">' + contactHtml + '</div>';
            html += '</header>';
            
            // Professional Summary
            if (summary.trim()) {
                html += '<div class="d-sec"><h2>Professional Summary</h2><p>' + escapeHtml(summary).replace(/\n/g, '<br>') + '</p></div>';
            }
            
            // Experience List
            var experienceHtml = '';
            $('.experience-item').each(function() {
                var role = $(this).find('input[name="exp_position[]"]').val() || '';
                var company = $(this).find('input[name="exp_company[]"]').val() || '';
                var start = $(this).find('input[name="exp_start_date[]"]').val() || '';
                var end = $(this).find('input[name="exp_end_date[]"]').val() || '';
                var current = $(this).find('.exp-current-check').is(':checked');
                var desc = $(this).find('textarea[name="exp_description[]"]').val() || '';
                
                var dates = formatMonthYear(start) + ' – ' + (current ? 'Present' : (end ? formatMonthYear(end) : ''));
                if (role || company || desc) {
                    var bulletPoints = desc.split('\n').map(s => s.trim()).filter(Boolean);
                    var bulletsUl = '';
                    if (bulletPoints.length) {
                        bulletsUl = '<ul>' + bulletPoints.map(b => '<li>' + escapeHtml(b) + '</li>').join('') + '</ul>';
                    }
                    experienceHtml += '<div class="d-xp"><div class="d-xp-h"><b>' + escapeHtml(role) + '</b><i>' + escapeHtml(dates) + '</i></div>' +
                        (company ? '<p class="co">' + escapeHtml(company) + '</p>' : '') + bulletsUl + '</div>';
                }
            });

            // Demo experience fallback if user has no entries yet
            if (!experienceHtml) {
                experienceHtml = '<div class="d-xp"><div class="d-xp-h"><b>Accountant</b><i>2021 – Present</i></div><p class="co">Mid-sized services firm, Lagos</p><ul><li>Handle month-end close, VAT and PAYE filings</li><li>Prepare monthly management reports</li><li>Maintain fixed asset register</li></ul></div>' +
                    '<div class="d-xp"><div class="d-xp-h"><b>Junior Accountant</b><i>2019 – 2021</i></div><p class="co">Lagos</p><ul><li>Reconciled bank statements</li><li>Processed invoices and accounts payable for 40+ vendors</li><li>Supported annual audit preparation</li></ul></div>';
            }
            if (experienceHtml) {
                html += '<div class="d-sec"><h2>Work Experience</h2>' + experienceHtml + '</div>';
            }
            
            // Education List
            var educationHtml = '';
            $('.education-item').each(function() {
                var school = $(this).find('input[name="edu_school[]"]').val() || '';
                var degree = $(this).find('select[name="edu_degree[]"]').val() || '';
                var field = $(this).find('input[name="edu_field[]"]').val() || '';
                var year = $(this).find('input[name="edu_year[]"]').val() || '';
                
                if (school || degree || field) {
                    educationHtml += '<div class="d-xp"><div class="d-xp-h"><b>' + escapeHtml((degree ? degree + ' in ' : '') + field) + '</b><i>' + escapeHtml(year) + '</i></div>' +
                        '<p class="co">' + escapeHtml(school) + '</p></div>';
                }
            });

            // Demo education fallback
            if (!educationHtml) {
                educationHtml = '<div class="d-xp"><div class="d-xp-h"><b>B.Sc. Accounting</b><i>2014 – 2018</i></div></div>';
            }
            if (educationHtml) {
                html += '<div class="d-sec"><h2>Education</h2>' + educationHtml + '</div>';
            }
            
            // Skills List
            var skillsStr = $('textarea[name="skills"], input[name="skills"]').val() || '';
            var skills = skillsStr.split(',').map(s => s.trim()).filter(Boolean);
            if (!skills.length) {
                skills = ['Accounting', 'Bank Reconciliation', 'Management Reporting', 'VAT & PAYE Compliance'];
            }
            if (skills.length) {
                var skillsLi = skills.map(s => '<li>' + escapeHtml(s) + '</li>').join('');
                html += '<div class="d-sec"><h2>Skills</h2><ul class="d-skills">' + skillsLi + '</ul></div>';
            }

            // Certifications List
            var certsStr = $('textarea[name="certs"]').val() || '';
            var certs = certsStr.split('\n').map(s => s.trim()).filter(Boolean);
            if (!certs.length) {
                certs = [
                    'Social Media Marketing Masterclass — JobberRecruit, 2026 (CERT-40315B08AE40)',
                    'Introduction to Agile and Scrum — JobberRecruit, 2026 (CERT-323E73EAA068)'
                ];
            }
            if (certs.length) {
                var certsLi = certs.map(c => '<li>' + escapeHtml(c) + '</li>').join('');
                html += '<div class="d-sec"><h2>Certifications</h2><ul class="d-skills">' + certsLi + '</ul></div>';
            }

            // Languages List
            var languagesStr = $('input[name="languages"]').val() || '';
            var languages = languagesStr.split(',').map(s => s.trim()).filter(Boolean);
            if (!languages.length) {
                languages = ['English (Fluent)', 'Igbo (Native)'];
            }
            if (languages.length) {
                var languagesLi = languages.map(l => '<li>' + escapeHtml(l) + '</li>').join('');
                html += '<div class="d-sec"><h2>Languages</h2><ul class="d-skills">' + languagesLi + '</ul></div>';
            }
            
            // Watermark anti-crop element
            html += '<div class="wm" aria-hidden="true">' +
                '<svg class="wm-ic" viewBox="0 0 925.5 1269.15"><use href="#jr-mark"/></svg>' +
                '<span class="wm-tx">www.JobberRecruit.com</span>' +
                '</div>';
                
            $doc.html(html);
            
            // Adjust layout for Executive template
            if (tpl === 't-exec') {
                var head = $doc.find('.d-head')[0];
                var wm = $doc.find('.wm')[0];
                var secs = $doc.find('.d-sec').toArray();
                var sideKeys = ["Certifications", "Skills", "Languages"];
                
                var side = document.createElement("div"); side.className = "exec-side";
                var main = document.createElement("div"); main.className = "exec-main";
                
                secs.forEach(function(sec) {
                    var titleText = $(sec).find('h2').text() || '';
                    if (sideKeys.indexOf(titleText.trim()) > -1) {
                        side.appendChild(sec);
                    } else {
                        main.appendChild(sec);
                    }
                });
                
                $doc.html('');
                if (head) $doc.append(head);
                $doc.append(side); $doc.append(main);
                if (wm) $doc.append(wm);
            }
            
            // Check fit pages
            var scrollHeight = $doc[0].scrollHeight;
            var maxOnePageHeight = 1074;
            var fitDot = $('.fit-dot');
            if (scrollHeight > maxOnePageHeight) {
                fitDot.removeClass('ok').addClass('over');
                $('.pv-hint').html('Currently spanning multiple pages. Switch Spacing to <b>Tight</b> or shorten text.');
            } else {
                fitDot.removeClass('over').addClass('ok');
                $('.pv-hint').html('Perfect! Fits cleanly on <b>1 Page</b>.');
            }
        }
        
        var STOP = "the and for with our you your this that will have has are was were from into able out not can may all any per who what when they them their its it's more than been being to of in on at as by an or a is be we do if so".split(" ");
        var GENERIC = ("finance financial expense expenses reporting compliance vendor vendors monthly "
          + "leadership statutory filings operations operation duties duty support seeking seek strong "
          + "willingness encouraged provided discipline graduate graduates trainee programme role roles "
          + "team teams company companies business businesses department departments process processes "
          + "environment environments candidate candidates requirement requirements responsibility "
          + "responsibilities experience experienced years year work working ability abilities knowledge "
          + "understanding including related general overall various multiple wide range level high "
          + "excellent good great proven demonstrated across throughout applicants applicant apply "
          + "position positions salary benefits location office hours schedule").split(" ");

        function explicitSkillList(txt) {
            var m = String(txt).match(/(?:skills|requirements|competencies)\s*[:\-]\s*([^.]+)\./i);
            if (!m) return [];
            return m[1].split(/,|;|\u2022|\u00b7/).map(function(s){ return s.trim().toLowerCase(); })
                .filter(function(s){ return s.length > 2 && s.length < 40; });
        }

        function skillBigrams(txt) {
            var words = String(txt).toLowerCase().replace(/[^a-z\s-]/g," ").split(/\s+/).filter(Boolean);
            var out = {};
            for (var i = 0; i < words.length - 1; i++){
                var a = words[i], b = words[i+1];
                if (a.length > 3 && b.length > 3 && STOP.indexOf(a) === -1 && STOP.indexOf(b) === -1
                    && GENERIC.indexOf(a) === -1 && GENERIC.indexOf(b) === -1){
                    var phrase = a + " " + b;
                    out[phrase] = (out[phrase]||0) + 1;
                }
            }
            return Object.keys(out).sort(function(x,y){return out[y]-out[x]});
        }

        function keywords(txt) {
            var explicit = explicitSkillList(txt);
            if (explicit.length) return explicit.slice(0, 14);

            var bigrams = skillBigrams(txt).slice(0, 8);

            var f = {};
            String(txt).toLowerCase().replace(/[^a-z\s-]/g," ").split(/\s+/).forEach(function(w){
                if (w.length > 3 && STOP.indexOf(w) === -1 && GENERIC.indexOf(w) === -1) f[w] = (f[w]||0)+1;
            });
            var singles = Object.keys(f).sort(function(a,b){return f[b]-f[a]});

            var combined = bigrams.concat(singles).slice(0, 14);
            return combined;
        }

        // ── ATS SCAN CHECKLIST & INTELLIGENCE ENGINE ──
        var SYN = {
            reconciliation:["reconcile","reconciled","reconciling","bank reconciliation"],
            accounting:["accounts","accountant","bookkeeping","ledger"],
            reporting:["reports","management accounts","financial reporting"],
            payroll:["paye","salaries","wages"], tax:["vat","paye","firs","taxation","filings"],
            excel:["spreadsheet","spreadsheets","microsoft excel"],
            audit:["auditing","audits","auditor"], budgeting:["budget","budgets","forecasting"],
            compliance:["regulatory","statutory","filings","firs","lirs"], invoicing:["invoices","billing","receivables"],
            nysc:["national youth service","corps member","youth service"],
            ican:["chartered accountant","aca","icaen"], acca:["chartered certified accountant"],
            qualification:["b.sc","bsc","hnd","ond","degree","certified"],
            payables:["payable","vendors","suppliers"], leadership:["led","managed","supervised","mentored"],
            communication:["stakeholder","presented","liaised"], analysis:["analysed","analyzed","analytical","insights"]
        };
        var CLICHES = ["results-driven","results driven","highly motivated","dynamic professional","proven track record","passionate professional","detail-oriented","detail oriented","team player","self-starter","self starter","go-getter","go getter","hardworking individual","think outside the box","synergy"];
        var STRONG_VERBS = ["led","built","cut","grew","launched","delivered","reduced","improved","designed","owned","negotiated","recovered","streamlined","automated","prepared","produced","rebuilt","cleared","processed","reconciled","managed","implemented","run","ran","handle","handled","maintain","maintained","supported","created","trained"];
        var IMPACT_WORDS = ["cut","reduced","grew","saved","improved","increased","delivered","cleared","recovered","shortened","eliminated","doubled"];
        
        function pct(n, d) { return d ? Math.round(n / d * 100) : 0; }
        
        function semHit(kw, txt) {
            if (txt.indexOf(kw) > -1) return true;
            if (SYN[kw]) {
                for (var i = 0; i < SYN[kw].length; i++) {
                    if (txt.indexOf(SYN[kw][i]) > -1) return true;
                }
            }
            for (var base in SYN) {
                if (SYN[base].indexOf(kw) > -1 && (txt.indexOf(base) > -1 || SYN[base].some(s => txt.indexOf(s) > -1))) return true;
            }
            return false;
        }

        function metBar(label, val) {
            var cls = val === null ? "" : (val >= 70 ? "" : (val >= 45 ? " warn" : " bad"));
            return '<div class="met' + cls + '"><div class="met-h"><span>' + label + '</span><b>' + (val === null ? "—" : val + "%") + '</b></div>'
                + '<div class="met-t"><div class="met-f" style="width:' + (val || 0) + '%"></div></div></div>';
        }

        function refreshAts() {
            var name = $('input[name="full_name"]').val() || '';
            var email = $('input[name="email"]').val() || '';
            var phone = $('input[name="phone"]').val() || '';
            var locationStr = $('input[name="location"]').val() || '';
            var linkedin = $('input[name="linkedin"]').val() || '';
            var summary = $('#resume-summary').val() || '';
            var certsStr = $('textarea[name="certs"]').val() || '';
            var languagesStr = $('input[name="languages"]').val() || '';
            var skillsStr = $('input[name="skills"]').val() || '';
            
            var skills = skillsStr.split(',').map(s => s.trim()).filter(Boolean);
            
            var experiences = [];
            $('.experience-item').each(function() {
                experiences.push({
                    role: $(this).find('input[name="exp_position[]"]').val() || '',
                    company: $(this).find('input[name="exp_company[]"]').val() || '',
                    start_date: $(this).find('input[name="exp_start_date[]"]').val() || '',
                    end_date: $(this).find('input[name="exp_end_date[]"]').val() || '',
                    is_current: $(this).find('.exp-current-check').is(':checked'),
                    bullets: $(this).find('textarea[name="exp_description[]"]').val() || ''
                });
            });

            var education = [];
            $('.education-item').each(function() {
                education.push({
                    school: $(this).find('input[name="edu_school[]"]').val() || '',
                    degree: $(this).find('select[name="edu_degree[]"]').val() || '',
                    field: $(this).find('input[name="edu_field[]"]').val() || '',
                    year: $(this).find('input[name="edu_year[]"]').val() || ''
                });
            });

            var allB = experiences.map(x => x.bullets).join('\n');
            var bullets = allB.split('\n').map(s => s.trim()).filter(Boolean);
            var txt = (summary + ' ' + allB + ' ' + skills.join(' ') + ' ' + certsStr).toLowerCase();

            // Perform 17 checklist audits
            var checks = [];

            // 1. Contact details complete
            var okContact = !!(name && email && phone && locationStr);
            checks.push({ ok: okContact, pts: 15, label: "Contact details complete", section: "info" });

            // 2. Summary length (35-120 words)
            var sw = summary.trim().split(/\s+/).filter(Boolean).length;
            checks.push({ ok: sw >= 35 && sw <= 120, pts: 15, label: "Summary is 35–120 words (" + sw + ")", section: "summary" });

            // 3. Achievements include numbers
            var hasNumbers = /\d/.test(allB);
            checks.push({ ok: hasNumbers, pts: 15, label: "Achievements include numbers", section: "experience" });

            // 4. 5+ achievement bullets
            checks.push({ ok: bullets.length >= 5, pts: 10, label: "5+ achievement bullets listed", section: "experience" });

            // 5. 6+ skills listed
            checks.push({ ok: skills.length >= 6, pts: 15, label: "6+ skills listed", section: "skills" });

            // 6. Education included
            var hasEdu = education.some(e => e.school.trim());
            checks.push({ ok: hasEdu, pts: 10, label: "Education included", section: "education" });

            // 7. Certifications included
            checks.push({ ok: certsStr.trim().length > 0, pts: 10, label: "Certifications included", section: "skills" });

            // 8. Substantive content length
            var totalContentWords = (summary + ' ' + allB).trim().split(/\s+/).filter(Boolean).length;
            checks.push({ ok: totalContentWords >= 120, pts: 10, label: "Enough content to rank (>120 words)", section: "experience" });

            // Calculate ATS Score
            var atsScoreValue = checks.reduce((a, c) => a + (c.ok ? c.pts : 0), 0);

            // 9. Missing LinkedIn URL
            checks.push({ ok: linkedin.trim().length > 0, pts: 0, label: "LinkedIn profile link added", section: "info" });

            // 10. Missing Location
            checks.push({ ok: locationStr.trim().length > 0, pts: 0, label: "Location city added", section: "info" });

            // 11. Overlong summary
            checks.push({ ok: sw <= 60, pts: 0, label: "Summary is concise (<=60 words)", section: "summary" });

            // 12. Filler words
            var FILLER = ["very","really","various","several","successfully","effectively","in order to","a number of","responsible for"];
            var fhit = FILLER.filter(f => txt.indexOf(f) > -1);
            checks.push({ ok: fhit.length === 0, pts: 0, label: "No generic filler words used", section: "experience" });

            // 13. Buzzword overload
            var BUZZ = ["synergy","leverage","spearheaded","utilize","utilized","facilitate","streamline"];
            var bz = BUZZ.filter(w => txt.indexOf(w) > -1);
            checks.push({ ok: bz.length < 2, pts: 0, label: "No corporate buzzword overload", section: "experience" });

            // 14. Consistent Date Format (Always consistent since forms enforce date picker)
            checks.push({ ok: true, pts: 0, label: "Consistent date formats", section: "experience" });

            // 15. Capitalization of bullets
            var lowerStart = bullets.filter(x => { var c=x.trim()[0]; return c && c===c.toLowerCase() && c!==c.toUpperCase(); }).length;
            checks.push({ ok: lowerStart === 0, pts: 0, label: "All bullets capitalized", section: "experience" });

            // 16. Punctuation consistency
            var withDot = bullets.filter(x => /[.]$/.test(x.trim())).length;
            var okPunct = (bullets.length < 3 || withDot === 0 || withDot === bullets.length);
            checks.push({ ok: okPunct, pts: 0, label: "Consistent bullet punctuation", section: "experience" });

            // 17. Current role tense consistency
            var okTense = true;
            if (experiences.length >= 2) {
                var cur0 = experiences[0];
                var isCurrent = cur0.is_current || /present|current/i.test(cur0.end_date);
                var pastVerbs = /(ed|led|built|ran|made|kept)\b/i;
                if (isCurrent && cur0.bullets.trim() && cur0.bullets.split('\n').filter(Boolean).every(l => pastVerbs.test(l.trim().split(/\s+/)[0] || ""))) {
                    okTense = false;
                }
            }
            checks.push({ ok: okTense, pts: 0, label: "Current role uses present tense", section: "experience" });

            // Calculate Intel sub-metrics
            var cl = CLICHES.filter(c => txt.indexOf(c) > -1);
            var leads = {};
            var rep = [];
            bullets.forEach(x => { var v = x.toLowerCase().split(/\s+/)[0]; leads[v] = (leads[v] || 0) + 1; });
            for (var v in leads) {
                if (leads[v] >= 3) rep.push(v);
            }
            var human = Math.max(0, 100 - cl.length * 18 - rep.length * 12);

            var av = bullets.filter(x => STRONG_VERBS.indexOf(x.toLowerCase().split(/\s+/)[0]) > -1);
            var verbs = pct(av.length, bullets.length);

            var imp = bullets.filter(x => { var l = x.toLowerCase(); return /\d/.test(x) || IMPACT_WORDS.some(w => l.indexOf(w) > -1); });
            var impact = pct(imp.length, bullets.length);

            var rd = bullets.filter(x => { var w = x.split(/\s+/).length; return w >= 4 && w <= 24; });
            var read = pct(rd.length, bullets.length);

            var cover = null;
            var jd = $('#jd').length ? $('#jd').val().trim() : '';
            if (jd.length >= 60) {
                var kws = keywords(jd);
                cover = pct(kws.filter(k => semHit(k, txt)).length, kws.length);
            }

            var parts = [atsScoreValue, human, verbs, impact, read];
            if (cover !== null) parts.push(cover);
            var recruiterScore = Math.round(parts.reduce((a, x) => a + x, 0) / parts.length);

            // Update gauges & checklist UI
            $('#ats-num').text(atsScoreValue);
            var g = $('#gauge-p');
            if (g.length) {
                g.css('stroke-dashoffset', 207 * (1 - atsScoreValue / 100));
                g.css('stroke', atsScoreValue >= 75 ? 'var(--success)' : (atsScoreValue >= 50 ? 'var(--accent)' : 'var(--danger)'));
            }

            // Render 6 metrics grid
            $('#met-grid').html(
                metBar("Recruiter Score", recruiterScore) +
                metBar("Human Writing", human) +
                metBar("Impact", impact) +
                metBar("Action Verbs", verbs) +
                metBar("Readability", read) +
                (cover !== null ? metBar("Keyword Coverage", cover) : "")
            );

            // Render Checklist items
            var listHtml = '';
            checks.forEach(function(c) {
                var icon = c.ok ? 'ti-circle-check-filled text-success' : 'ti-circle text-muted';
                var btn = c.ok ? '' : '<button type="button" class="fix-ats btn-link text-decoration-none border-0 bg-transparent text-primary ms-auto" data-target="' + c.section + '" style="font-size: 0.74rem; font-weight:600;">Fix</button>';
                listHtml += '<li class="' + (c.ok ? 'ok' : 'no') + ' d-flex align-items-center mb-2" style="font-size: 0.8rem;"><i class="ti ' + icon + ' me-2 fs-5"></i><span>' + c.label + '</span>' + btn + '</li>';
            });
            $('#ats-list').html(listHtml);

            $('.fix-ats').on('click', function() {
                var target = $(this).data('target');
                openEdSec(target);
            });
        }

        // Input change listeners
        $(document).on('input change keyup', '#resume-form input, #resume-form textarea, #resume-form select', function() {
            renderLivePreview();
            refreshAts();
        });

        function resumeTextForMatch() {
            var summary = $('#resume-summary').val() || '';
            var skills = ($('input[name="skills"]').val() || '').split(',').map(s => s.trim()).filter(Boolean);
            var certs = $('textarea[name="certs"]').val() || '';
            var allBullets = [];
            $('textarea[name="exp_description[]"]').each(function() { allBullets.push($(this).val() || ''); });
            return (summary + ' ' + allBullets.join(' ') + ' ' + skills.join(' ') + ' ' + certs).toLowerCase();
        }

        function runMatch() {
            var jd = $('#jd').val().trim();
            var matchWrap = $('#match-wrap');
            if (jd.length < 60) { matchWrap.addClass('d-none'); refreshAts(); return; }
            matchWrap.removeClass('d-none');
            var kws = keywords(jd);
            var rt = resumeTextForMatch();
            var hit = kws.filter(k => semHit(k, rt));
            var score = kws.length ? Math.round(hit.length / kws.length * 100) : 0;
            $('#match-num').text(score + '%');
            $('#match-p').css('stroke-dashoffset', 207 * (1 - score / 100));
            var miss = kws.filter(k => !semHit(k, rt)).slice(0, 8);
            $('#kw-chips').html(miss.length
                ? miss.map(k => '<button class="btn btn-sm rounded-pill kw-chip" data-kw="' + k + '" style="border:1.5px solid var(--primary,#0861a9);color:var(--primary,#0861a9);background:#f0f6ff;font-size:.74rem;padding:3px 12px;transition:all .2s;">+ ' + k + '</button>').join('')
                : '<span class="text-success fw-semibold" style="font-size:.78rem;">Great coverage — no obvious keyword gaps.</span>');
            // keyword chip click-to-add
            $('.kw-chip').off('click').on('click', function() {
                var kw = $(this).data('kw');
                var pretty = kw.replace(/\b\w/g, c => c.toUpperCase());
                var skillsInput = $('input[name="skills"]');
                var current = skillsInput.val().split(',').map(s => s.trim()).filter(Boolean);
                if (current.indexOf(pretty) === -1) {
                    current.push(pretty);
                    skillsInput.val(current.join(', '));
                }
                $(this).css({background:'#d1fae5', borderColor:'#10b981', color:'#065f46'}).text('✓ ' + pretty).prop('disabled', true);
                renderLivePreview(); refreshAts(); runMatch();
            });
            // also re-run refreshAts so cover metric updates
            refreshAts();
        }

        // JD textarea auto-run match
        $('#jd').on('input', function() {
            clearTimeout(window._jdTimer);
            window._jdTimer = setTimeout(runMatch, 400);
        });

        // Job picker pre-fill JD
        $('#job-pick').on('change', function() {
            var val = $(this).val();
            var desc = $(this).find('option:selected').data('desc');
            if (val && desc) {
                $('#jd').val(desc);
                runMatch();
            } else {
                $('#jd').val('');
                $('#match-wrap').addClass('d-none');
            }
        });

        // Template Selection mapping
        var tplMap = {
            'classic': 't-classic',
            'modern': 't-modern',
            'creative': 't-creative',
            'executive': 't-exec',
            'minimalist': 't-minimal'
        };
        var revTplMap = {
            't-classic': 'classic',
            't-modern': 'modern',
            't-creative': 'creative',
            't-exec': 'executive',
            't-minimal': 'minimalist'
        };

        // Template Selection changes
        $('#tpl-select').on('change', function() {
            var selected = $(this).val();
            var rawTpl = revTplMap[selected] || selected.replace('t-', '');
            $('#template_id').val(rawTpl);
            $('#template-select-top').val(selected);
            // trigger active template card highlight
            $('.template-choice').removeClass('active border-primary border-2 shadow-sm');
            $('.template-choice[data-template="' + rawTpl + '"]').addClass('active border-primary border-2 shadow-sm');
            renderLivePreview();
        });

        $('.template-choice').on('click', function() {
            var tpl = $(this).data('template');
            var selectVal = tplMap[tpl] || 't-' + tpl;
            $('#tpl-select').val(selectVal).trigger('change');
        });

        // Spacing Selection changes
        $('#spacing-roomy-btn, #spacing-tight-btn').on('click', function() {
            $('#spacing-roomy-btn, #spacing-tight-btn').removeClass('on');
            $(this).addClass('on');
            renderLivePreview();
        });

        // Trigger on load
        setTimeout(function() {
            var dbTpl = $('#template_id').val() || 'classic';
            var selectVal = tplMap[dbTpl] || 't-' + dbTpl;
            $('#tpl-select').val(selectVal).trigger('change');
            renderLivePreview();
            refreshAts();
        }, 300);

        // Step Navigation (accordion-aware)
        $('.step-item').on('click', function() {
            const step = $(this).data('step');
            $('.step-item').removeClass('active');
            $(this).addClass('active');
            // Open the target accordion section
            openEdSec(step);
            // On mobile, scroll to the form so the user sees it
            if (window.innerWidth < 992) {
                const formEl = $('#resume-form');
                if (formEl.length && formEl.is(':visible') && formEl.offset()) {
                    const formOffset = formEl.offset().top - 80;
                    window.scrollTo({ top: formOffset, behavior: 'smooth' });
                }
            }
        });

        // Next/Prev Buttons Navigation
        $(document).on('click', '.next-step, .prev-step', function(e) {
            e.preventDefault();
            const target = $(this).data('step-target');
            
            if (target === 'finish') {
                // Focus on download buttons or trigger save
                $('#save-resume-btn').trigger('click');
                const saveBtn = $('#save-resume-btn');
                if (saveBtn.length && saveBtn.is(':visible') && saveBtn.offset()) {
                    const formOffset = saveBtn.offset().top - 150;
                    window.scrollTo({ top: formOffset, behavior: 'smooth' });
                }
                return;
            }
            
            // Direct state update
            $('.step-item').removeClass('active');
            $('.step-item[data-step="' + target + '"]').addClass('active');

            $('.step-content').addClass('d-none');
            $('#step-' + target).removeClass('d-none');

            // Scroll to form to avoid showing the top sidebar again on mobile
            const formEl = $('#resume-form');
            if (formEl.length && formEl.is(':visible') && formEl.offset()) {
                const formOffset = formEl.offset().top - 80;
                window.scrollTo({ top: formOffset, behavior: 'smooth' });
            }
        });

        // Click Event for Template Choice
        $(document).on('click', '.template-choice', function() {
            $('.template-choice').removeClass('active');
            $(this).addClass('active');
            $('#template_id').val($(this).data('template'));
        });

        // AI Summary Generation
        $('#generate-summary-ai').on('click', function() {
            const btn = $(this);
            const experiences = [];
            const education = [];
            const skills = $('input[name="skills"]').val();

            // Extract experience details from inputs to build rich prompt
            $('.experience-item').each(function() {
                const company = $(this).find('input[name="exp_company[]"]').val();
                const position = $(this).find('input[name="exp_position[]"]').val();
                const desc = $(this).find('textarea[name="exp_description[]"]').val();
                if (company || position) {
                    experiences.push({ company, position, description: desc });
                }
            });

            // Collect education entries
            $('.education-item').each(function() {
                const school = $(this).find('input[name="edu_school[]"]').val();
                const degree = $(this).find('select[name="edu_degree[]"]').val();
                const field = $(this).find('input[name="edu_field[]"]').val();
                if (school || degree) {
                    education.push({ school, degree, field });
                }
            });

            if (experiences.length === 0 && !skills) {
                toastr.warning('Please add some experience or skills so AI can write a personalized summary.');
                return;
            }

            btn.prop('disabled', true);
            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/generate-summary") ?>',
                type: 'POST',
                data: {
                    experiences: experiences,
                    education: education,
                    skills: skills,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.summary) {
                        // Show preview modal with sanitized HTML (server already sanitized)
                        $('#aiPreviewRender').html(response.summary);
                        $('#aiPreviewModal').modal('show');
                        // store raw in the preview container for apply action
                        $('#aiPreviewRender').data('raw', response.summary);
                    } else {
                        toastr.error('AI returned no content.');
                    }
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                },
                error: function() {
                    toastr.error('AI generation failed. Please try again.');
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                }
            });
        });

        // Add Experience Item (Dynamic)
        $('.add-experience').on('click', function() {
            $('#experience-container .no-items').hide();
            
            // Generate next available index for exp_current value tracking
            const count = $('.experience-item').length;
            
            const html = `
                <div class="xp-entry position-relative experience-item" style="display: none;">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                    <div class="row2">
                        <div>
                            <label class="lbl">Role</label>
                            <input type="text" name="exp_position[]" class="input" placeholder="Job Position">
                        </div>
                        <div>
                            <label class="lbl">Dates (Start - End)</label>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <input type="date" name="exp_start_date[]" class="input" style="padding-left:4px; padding-right:4px;">
                                <span class="exp-end-date-col">-</span>
                                <input type="date" name="exp_end_date[]" class="input exp-end-date-col" style="padding-left:4px; padding-right:4px;">
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="flex:1;">
                            <label class="lbl">Company</label>
                            <input type="text" name="exp_company[]" class="input" placeholder="Company Name">
                        </div>
                        <div class="form-check" style="margin-left:15px; margin-top:20px;">
                            <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="${count}" id="exp_current_new_${count}">
                            <label class="form-check-label lbl" for="exp_current_new_${count}" style="cursor:pointer; display:inline-block; margin-left:4px; margin-bottom:0;">Current</label>
                        </div>
                    </div>
                    <label class="lbl">Achievements — one per line</label>
                    <textarea name="exp_description[]" class="input" rows="4" placeholder="Describe your responsibilities and achievements..."></textarea>
                    <div class="ai-row">
                        <button type="button" class="btn-ai improve-desc-ai"><svg aria-hidden="true"><use href="#i-zap"/></svg> Strengthen achievements</button>
                        <button type="button" class="btn-ai generate-bullets-ai"><svg aria-hidden="true"><use href="#i-edit"/></svg> Generate Bullets</button>
                    </div>
                </div>
            `;
            
            const $newItem = $(html);
            $('#experience-container').append($newItem);
            $newItem.slideDown(200);
        });

        // Add Education Item (Dynamic)
        $('.add-education').on('click', function() {
            $('#education-container .no-items').hide();
            
            const html = `
                <div class="xp-entry position-relative education-item" style="display: none;">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                    <div class="row2">
                        <div>
                            <label class="lbl">School / University</label>
                            <input type="text" name="edu_school[]" class="input" placeholder="School / University">
                        </div>
                        <div>
                            <label class="lbl">Degree</label>
                            <select name="edu_degree[]" class="input select">
                                <option value="">Select Degree</option>
                                <option value="High School">High School</option>
                                <option value="Associate">Associate Degree</option>
                                <option value="Bachelor">Bachelor's Degree</option>
                                <option value="Master">Master's Degree</option>
                                <option value="PhD">PhD / Doctorate</option>
                                <option value="Certificate">Certificate</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row2" style="margin-top: 10px;">
                        <div>
                            <label class="lbl">Field of Study</label>
                            <input type="text" name="edu_field[]" class="input" placeholder="Field of Study">
                        </div>
                        <div>
                            <label class="lbl">Graduation Year</label>
                            <input type="text" name="edu_year[]" class="input" placeholder="YYYY">
                        </div>
                    </div>
                </div>
            `;
            
            const $newItem = $(html);
            $('#education-container').append($newItem);
            $newItem.slideDown(200);
        });

        // Dynamic deletion handler for items
        $(document).on('click', '.remove-item-btn', function() {
            const $item = $(this).closest('.experience-item, .education-item');
            const $container = $item.parent();
            
            $item.fadeOut(250, function() {
                $item.remove();
                if ($container.find('.experience-item, .education-item').length === 0) {
                    $container.find('.no-items').fadeIn(200);
                }
                // Re-index exp_current[] values so they still match each row's position —
                // save() matches checked values against the submitted exp_company[] array index.
                $('#experience-container .exp-current-check').each(function(idx) {
                    $(this).val(idx);
                });
            });
        });

        // Dynamic change handler for 'Currently Work Here' checkbox
        $(document).on('change', '.exp-current-check', function() {
            const $endDateCol = $(this).closest('.experience-item').find('.exp-end-date-col');
            if ($(this).is(':checked')) {
                $endDateCol.slideUp(200).find('input').val('');
            } else {
                $endDateCol.slideDown(200);
            }
        });

        // Improve Description with AI
        $(document).on('click', '.improve-desc-ai', function() {
            const btn = $(this);
            const textarea = btn.closest('.xp-entry, .experience-item').find('textarea[name="exp_description[]"]');
            if (textarea.length) {
                lastFocusedTextarea = textarea;
            }
            const description = textarea.length ? textarea.val() : '';

            if (!description || !description.trim()) {
                toastr.warning('Please enter a description first.');
                return;
            }

            btn.prop('disabled', true);
            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/improve-description") ?>',
                type: 'POST',
                data: {
                    description: description,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.description) {
                        // Show preview modal with suggested bullets or description
                        $('#aiPreviewRender').html(response.description.replace(/\n/g, '<br>'));
                        $('#aiPreviewRender').data('raw', response.description);
                        $('#aiPreviewModal').modal('show');
                    }
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                },
                error: function() {
                    toastr.error('AI improvement failed.');
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                }
            });
        });

        // Generate bullets for experience
        $(document).on('click', '.generate-bullets-ai', function() {
            const btn = $(this);
            const textarea = btn.closest('.xp-entry, .experience-item').find('textarea[name="exp_description[]"]');
            if (textarea.length) {
                lastFocusedTextarea = textarea;
            }
            const description = textarea.length ? textarea.val() : '';
            const position = btn.closest('.experience-item').find('input[name="exp_position[]"]').val() || '';

            if (!description || !description.trim()) {
                toastr.warning('Please enter an experience description first.');
                return;
            }

            btn.prop('disabled', true);
            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/generate-bullets") ?>',
                type: 'POST',
                data: {
                    description: description,
                    job_title: position,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.bullets) {
                        // Show preview modal with bullets
                        // convert newlines to <li> list for better UX
                        const bulletsHtml = response.bullets.split(/\r?\n/).filter(Boolean).map(b => '<li>' + escapeHtml(b.trim()) + '</li>').join('');
                        const html = '<div class="ai-card"><h3>Suggested Bullets</h3><ul>' + bulletsHtml + '</ul></div>';
                        $('#aiPreviewRender').html(html);
                        $('#aiPreviewRender').data('raw', response.bullets);
                        $('#aiPreviewModal').modal('show');
                    }
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                },
                error: function() {
                    toastr.error('Failed to generate bullets.');
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                }
            });
        });

        // Save Resume
        $('#save-resume-btn').on('click', function() {
            // Clear temporary undo data on save
            $('#resume-summary').removeData('prev');
            $('textarea[name="exp_description[]"]').each(function() { $(this).removeData('prev'); });

            // Dynamically set checkbox values to their actual array index prior to serialization
            $('.experience-item').each(function(index) {
                $(this).find('.exp-current-check').val(index);
            });

            const formData = $('#resume-form').serialize();
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner spinner-sm"></span> Saving...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/save") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Resume saved successfully!');
                    if (response.id) {
                        $('input[name="id"]').val(response.id);
                    }
                    // If this save was kicked off by a restore+save, create a snapshot autosave for history
                    if (window.restoreSavePending) {
                        try {
                            // Convert to structured snapshot similar to doAutosave
                            const snapshot = { experiences: [], education: [] };
                            snapshot.id = $('input[name="id"]').val() || null;
                            snapshot.title = $('input[name="title"]').val() || '';
                            snapshot.summary = $('#resume-summary').val() || '';
                            snapshot.template_id = $('#template_id').val() || 'classic';
                            snapshot.skills = $('input[name="skills"]').val() || '';
                            snapshot.linkedin = $('input[name="linkedin"]').val() || '';
                            snapshot.certs = $('textarea[name="certs"]').val() || '';
                            snapshot.languages = $('input[name="languages"]').val() || '';

                            $('.experience-item').each(function() {
                                snapshot.experiences.push({
                                    company: $(this).find('input[name="exp_company[]"]').val() || '',
                                    position: $(this).find('input[name="exp_position[]"]').val() || '',
                                    description: $(this).find('textarea[name="exp_description[]"]').val() || '',
                                    start_date: $(this).find('input[name="exp_start_date[]"]').val() || '',
                                    end_date: $(this).find('input[name="exp_end_date[]"]').val() || '',
                                    is_current: $(this).find('.exp-current-check').is(':checked') ? 1 : 0
                                });
                            });

                            $('.education-item').each(function() {
                                snapshot.education.push({
                                    institution: $(this).find('input[name="edu_school[]"]').val() || '',
                                    degree: $(this).find('select[name="edu_degree[]"]').val() || '',
                                    field_of_study: $(this).find('input[name="edu_field[]"]').val() || '',
                                    graduation_year: $(this).find('input[name="edu_year[]"]').val() || ''
                                });
                            });

                            $.ajax({
                                url: '<?= site_url("candidate/resumes/autosave") ?>',
                                type: 'POST',
                                data: { snapshot: JSON.stringify(snapshot), id: snapshot.id, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' }
                            });
                        } catch (e) {
                            // ignore autosave snapshot errors
                        }
                        window.restoreSavePending = false;
                        // Visual confirmation for restore+save
                        toastr.success('Revision restored and saved successfully.');
                    }
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>Save Resume');
                },
                error: function() {
                    toastr.error('Failed to save resume.');
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>Save Resume');
                }
            });
        });

        // Autosave: debounce per-field and periodic full autosave
        let autosaveTimer = null;
        let debounceTimers = new Map();
        const AUTOSAVE_INTERVAL = 30000; // 30s
        const FIELD_DEBOUNCE = 500; // 500ms

        function scheduleAutosave() {
            if (autosaveTimer) clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(doAutosave, AUTOSAVE_INTERVAL);
        }

        function doAutosave() {
            const form = $('#resume-form');
            // Build structured snapshot JSON from current form state
            const snapshot = {
                id: $('input[name="id"]').val() || null,
                title: $('input[name="title"]').val() || '',
                summary: $('#resume-summary').val() || '',
                template_id: $('#template_id').val() || 'classic',
                experiences: [],
                education: [],
                skills: $('input[name="skills"]').val() || '',
                linkedin: $('input[name="linkedin"]').val() || '',
                certs: $('textarea[name="certs"]').val() || '',
                languages: $('input[name="languages"]').val() || ''
            };

            $('.experience-item').each(function() {
                snapshot.experiences.push({
                    company: $(this).find('input[name="exp_company[]"]').val() || '',
                    position: $(this).find('input[name="exp_position[]"]').val() || '',
                    description: $(this).find('textarea[name="exp_description[]"]').val() || '',
                    start_date: $(this).find('input[name="exp_start_date[]"]').val() || '',
                    end_date: $(this).find('input[name="exp_end_date[]"]').val() || '',
                    is_current: $(this).find('.exp-current-check').is(':checked') ? 1 : 0
                });
            });

            $('.education-item').each(function() {
                snapshot.education.push({
                    institution: $(this).find('input[name="edu_school[]"]').val() || '',
                    degree: $(this).find('select[name="edu_degree[]"]').val() || '',
                    field_of_study: $(this).find('input[name="edu_field[]"]').val() || '',
                    graduation_year: $(this).find('input[name="edu_year[]"]').val() || ''
                });
            });

            $.ajax({
                url: '<?= site_url("candidate/resumes/autosave") ?>',
                type: 'POST',
                data: {
                    snapshot: JSON.stringify(snapshot),
                    id: $('input[name="id"]').val(),
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(resp) {
                    if (resp.id) {
                        $('input[name="id"]').val(resp.id);
                    }
                    const ts = new Date().toLocaleTimeString();
                    $('#autosave-indicator').remove();
                    
                    const header = $('.page-title').length ? $('.page-title') : $('.page-header');
                    if (header.length) {
                        header.append('<span id="autosave-indicator" class="text-muted ms-3" style="font-size:12px;">Autosaved at ' + ts + '</span>');
                    }
                }
            });
        }

        // Track per-field changes
        $(document).on('input change', '#resume-form input, #resume-form textarea, #resume-form select', function() {
            const el = this;
            // Generate a unique reference using index suffix to avoid debounce key collisions in arrays
            const name = $(el).attr('name') || '';
            let key = el; // default to element DOM reference
            if (name.includes('[]')) {
                const index = $('[name="' + name + '"]').index(el);
                key = name + '_' + index;
            } else if ($(el).attr('id')) {
                key = $(el).attr('id');
            }
            if (debounceTimers.has(key)) clearTimeout(debounceTimers.get(key));
            debounceTimers.set(key, setTimeout(function() {
                scheduleAutosave();
                debounceTimers.delete(key);
            }, FIELD_DEBOUNCE));
        });

        // Also autosave on page unload
        $(window).on('beforeunload', function() {
            // synchronous navigator sendBeacon unavailable for form data; attempt quick ajax
            navigator.sendBeacon && navigator.sendBeacon('<?= site_url("candidate/resumes/autosave") ?>', new URLSearchParams({
                id: $('input[name="id"]').val() || '',
                payload: $('#resume-form').serialize() || ''
            }));
        });

        // Utility escapeHtml is defined earlier; ensure it's available for template building

        // Revision History UI: open modal and load recent autosaves
        $('#open-revisions-btn').on('click', function() {
            const resumeId = $('input[name="id"]').val();
            if (!resumeId) {
                toastr.info('Please save your resume once to enable revisions.');
                return;
            }

            $('#revisions-list').html('<div class="text-muted">Loading revisions...</div>');
            $('#revisionsModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/") ?>' + resumeId + '/autosaves',
                type: 'GET',
                success: function(resp) {
                    if (!resp.autosaves || resp.autosaves.length === 0) {
                        $('#revisions-list').html('<div class="text-muted">No revisions found.</div>');
                        return;
                    }

                    const items = resp.autosaves.map(function(a) {
                        const created = new Date(a.created_at).toLocaleString();
                        const summ = a.preview && a.preview.summary ? a.preview.summary : '';
                        const exps = a.preview && a.preview.experiences ? a.preview.experiences.map(e => (e.position || '') + (e.company ? ' at ' + e.company : '')).join('; ') : '';
                        const previewHtml = '<div class="fw-semibold">' + created + '</div>' +
                            (summ ? '<div class="text-muted small mt-1">' + escapeHtml(summ) + '</div>' : '') +
                            (exps ? '<div class="text-muted small mt-1"><strong>Experiences:</strong> ' + escapeHtml(exps) + '</div>' : '');

                        return `<div class="revision-item border rounded p-2 mb-2 d-flex justify-content-between align-items-start">
                            <div style="max-width: 75%;">
                                ${previewHtml}
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary restore-autosave-btn" data-id="${a.id}">Restore</button>
                                <button class="btn btn-sm btn-primary restore-save-autosave-btn" data-id="${a.id}">Restore & Save</button>
                            </div>
                        </div>`;
                    }).join('');

                    $('#revisions-list').html(items);
                },
                error: function() {
                    $('#revisions-list').html('<div class="text-danger">Failed to load revisions.</div>');
                }
            });
        });

        // Restore autosave from revisions modal (structured snapshot restore)
        $(document).on('click', '.restore-autosave-btn', function() {
            const autosaveId = $(this).data('id');
            const resumeId = $('input[name="id"]').val();
            if (!resumeId) return;

            const btn = $(this);
            btn.prop('disabled', true).text('Restoring...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/") ?>' + resumeId + '/restore-autosave',
                type: 'POST',
                data: {
                    autosave_id: autosaveId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(resp) {
                    if (resp.payload) {
                        // load structured JSON snapshot into form and reconstruct repeated groups
                        const snap = resp.payload;
                        if (snap.title !== undefined) $('input[name="title"]').val(snap.title);
                        if (snap.summary !== undefined) $('#resume-summary').val(snap.summary);
                        if (snap.template_id !== undefined) $('#template_id').val(snap.template_id);
                        if (snap.skills !== undefined) $('input[name="skills"]').val(snap.skills);
                        if (snap.linkedin !== undefined) $('input[name="linkedin"]').val(snap.linkedin);
                        if (snap.certs !== undefined) $('textarea[name="certs"]').val(snap.certs);
                        if (snap.languages !== undefined) $('input[name="languages"]').val(snap.languages);

                        // Rebuild experiences section
                        const $expContainer = $('#experience-container');
                        $expContainer.find('.experience-item').remove();
                        if (Array.isArray(snap.experiences)) {
                            snap.experiences.forEach(function(e, idx) {
                                const html = `
                                    <div class="xp-entry position-relative experience-item">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                        <div class="row2">
                                            <div>
                                                <label class="lbl">Role</label>
                                                <input type="text" name="exp_position[]" class="input" placeholder="Job Position" value="${escapeHtml(e.position || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Dates (Start - End)</label>
                                                <div style="display:flex; gap:6px; align-items:center;">
                                                    <input type="date" name="exp_start_date[]" class="input" value="${escapeHtml(e.start_date || '')}" style="padding-left:4px; padding-right:4px;">
                                                    <span class="exp-end-date-col" style="${e.is_current ? 'display: none;' : ''}">-</span>
                                                    <input type="date" name="exp_end_date[]" class="input exp-end-date-col" value="${escapeHtml(e.end_date || '')}" style="${e.is_current ? 'display: none;' : ''} padding-left:4px; padding-right:4px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display:flex; justify-content:space-between; align-items:center;">
                                            <div style="flex:1;">
                                                <label class="lbl">Company</label>
                                                <input type="text" name="exp_company[]" class="input" placeholder="Company Name" value="${escapeHtml(e.company || '')}">
                                            </div>
                                            <div class="form-check" style="margin-left:15px; margin-top:20px;">
                                                <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="${idx}" ${e.is_current ? 'checked' : ''} id="exp_current_${idx}">
                                                <label class="form-check-label lbl" for="exp_current_${idx}" style="cursor:pointer; display:inline-block; margin-left:4px; margin-bottom:0;">Current</label>
                                            </div>
                                        </div>
                                        <label class="lbl">Achievements — one per line</label>
                                        <textarea name="exp_description[]" class="input" rows="4" placeholder="Describe your responsibilities and achievements...">${escapeHtml(e.description || '')}</textarea>
                                        <div class="ai-row">
                                            <button type="button" class="btn-ai improve-desc-ai"><svg aria-hidden="true"><use href="#i-zap"/></svg> Strengthen achievements</button>
                                            <button type="button" class="btn-ai generate-bullets-ai"><svg aria-hidden="true"><use href="#i-edit"/></svg> Generate Bullets</button>
                                        </div>
                                    </div>
                                `;
                                $expContainer.append(html);
                            });
                        }

                        // Rebuild education section
                        const $eduContainer = $('#education-container');
                        $eduContainer.find('.education-item').remove();
                        if (Array.isArray(snap.education)) {
                            snap.education.forEach(function(ed) {
                                const html = `
                                    <div class="xp-entry position-relative education-item">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                        <div class="row2">
                                            <div>
                                                <label class="lbl">School / University</label>
                                                <input type="text" name="edu_school[]" class="input" placeholder="School / University" value="${escapeHtml(ed.institution || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Degree</label>
                                                <select name="edu_degree[]" class="input select">
                                                    <option value="">Select Degree</option>
                                                    <option value="High School" ${ed.degree === 'High School' ? 'selected' : ''}>High School</option>
                                                    <option value="Associate" ${ed.degree === 'Associate' ? 'selected' : ''}>Associate Degree</option>
                                                    <option value="Bachelor" ${ed.degree === 'Bachelor' ? 'selected' : ''}>Bachelor's Degree</option>
                                                    <option value="Master" ${ed.degree === 'Master' ? 'selected' : ''}>Master's Degree</option>
                                                    <option value="PhD" ${ed.degree === 'PhD' ? 'selected' : ''}>PhD / Doctorate</option>
                                                    <option value="Certificate" ${ed.degree === 'Certificate' ? 'selected' : ''}>Certificate</option>
                                                    <option value="Other" ${ed.degree === 'Other' ? 'selected' : ''}>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row2" style="margin-top: 10px;">
                                            <div>
                                                <label class="lbl">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="input" placeholder="Field of Study" value="${escapeHtml(ed.field_of_study || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Graduation Year</label>
                                                <input type="text" name="edu_year[]" class="input" placeholder="YYYY" value="${escapeHtml(ed.graduation_year || '')}">
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $eduContainer.append(html);
                            });
                        }

                        toastr.success('Revision restored into the form. Please review changes and Save to persist.');
                        $('#revisionsModal').modal('hide');
                    } else {
                        toastr.error('Invalid autosave payload');
                    }
                },
                error: function() {
                    toastr.error('Failed to restore revision.');
                    btn.prop('disabled', false).text('Restore');
                }
            });
        });

        // Restore & Save action
        $(document).on('click', '.restore-save-autosave-btn', function() {
            const autosaveId = $(this).data('id');
            const resumeId = $('input[name="id"]').val();
            if (!resumeId) return;

            const btn = $(this);
            btn.prop('disabled', true).text('Restoring...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/") ?>' + resumeId + '/restore-autosave',
                type: 'POST',
                data: {
                    autosave_id: autosaveId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(resp) {
                    if (resp.payload) {
                        const snap = resp.payload;
                        if (snap.title !== undefined) $('input[name="title"]').val(snap.title);
                        if (snap.summary !== undefined) $('#resume-summary').val(snap.summary);
                        if (snap.template_id !== undefined) $('#template_id').val(snap.template_id);
                        if (snap.skills !== undefined) $('input[name="skills"]').val(snap.skills);

                        // Rebuild experiences and education same as restore
                        const $expContainer = $('#experience-container');
                        $expContainer.find('.experience-item').remove();
                        if (Array.isArray(snap.experiences)) {
                            snap.experiences.forEach(function(e, idx) {
                                const html = `
                                    <div class="xp-entry position-relative experience-item">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                        <div class="row2">
                                            <div>
                                                <label class="lbl">Role</label>
                                                <input type="text" name="exp_position[]" class="input" placeholder="Job Position" value="${escapeHtml(e.position || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Dates (Start - End)</label>
                                                <div style="display:flex; gap:6px; align-items:center;">
                                                    <input type="date" name="exp_start_date[]" class="input" value="${escapeHtml(e.start_date || '')}" style="padding-left:4px; padding-right:4px;">
                                                    <span class="exp-end-date-col" style="${e.is_current ? 'display: none;' : ''}">-</span>
                                                    <input type="date" name="exp_end_date[]" class="input exp-end-date-col" value="${escapeHtml(e.end_date || '')}" style="${e.is_current ? 'display: none;' : ''} padding-left:4px; padding-right:4px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display:flex; justify-content:space-between; align-items:center;">
                                            <div style="flex:1;">
                                                <label class="lbl">Company</label>
                                                <input type="text" name="exp_company[]" class="input" placeholder="Company Name" value="${escapeHtml(e.company || '')}">
                                            </div>
                                            <div class="form-check" style="margin-left:15px; margin-top:20px;">
                                                <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="${idx}" ${e.is_current ? 'checked' : ''} id="exp_current_${idx}">
                                                <label class="form-check-label lbl" for="exp_current_${idx}" style="cursor:pointer; display:inline-block; margin-left:4px; margin-bottom:0;">Current</label>
                                            </div>
                                        </div>
                                        <label class="lbl">Achievements — one per line</label>
                                        <textarea name="exp_description[]" class="input" rows="4" placeholder="Describe your responsibilities and achievements...">${escapeHtml(e.description || '')}</textarea>
                                        <div class="ai-row">
                                            <button type="button" class="btn-ai improve-desc-ai"><svg aria-hidden="true"><use href="#i-zap"/></svg> Strengthen achievements</button>
                                            <button type="button" class="btn-ai generate-bullets-ai"><svg aria-hidden="true"><use href="#i-edit"/></svg> Generate Bullets</button>
                                        </div>
                                    </div>
                                `;
                                $expContainer.append(html);
                            });
                        }

                        const $eduContainer = $('#education-container');
                        $eduContainer.find('.education-item').remove();
                        if (Array.isArray(snap.education)) {
                            snap.education.forEach(function(ed) {
                                const html = `
                                    <div class="xp-entry position-relative education-item">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                        <div class="row2">
                                            <div>
                                                <label class="lbl">School / University</label>
                                                <input type="text" name="edu_school[]" class="input" placeholder="School / University" value="${escapeHtml(ed.institution || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Degree</label>
                                                <select name="edu_degree[]" class="input select">
                                                    <option value="">Select Degree</option>
                                                    <option value="High School" ${ed.degree === 'High School' ? 'selected' : ''}>High School</option>
                                                    <option value="Associate" ${ed.degree === 'Associate' ? 'selected' : ''}>Associate Degree</option>
                                                    <option value="Bachelor" ${ed.degree === 'Bachelor' ? 'selected' : ''}>Bachelor's Degree</option>
                                                    <option value="Master" ${ed.degree === 'Master' ? 'selected' : ''}>Master's Degree</option>
                                                    <option value="PhD" ${ed.degree === 'PhD' ? 'selected' : ''}>PhD / Doctorate</option>
                                                    <option value="Certificate" ${ed.degree === 'Certificate' ? 'selected' : ''}>Certificate</option>
                                                    <option value="Other" ${ed.degree === 'Other' ? 'selected' : ''}>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row2" style="margin-top: 10px;">
                                            <div>
                                                <label class="lbl">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="input" placeholder="Field of Study" value="${escapeHtml(ed.field_of_study || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Graduation Year</label>
                                                <input type="text" name="edu_year[]" class="input" placeholder="YYYY" value="${escapeHtml(ed.graduation_year || '')}">
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $eduContainer.append(html);
                            });
                        }

                        // After rebuilding, trigger save (and snapshot)
                        window.restoreSavePending = true;
                        $('#save-resume-btn').trigger('click');
                        $('#revisionsModal').modal('hide');
                    } else {
                        toastr.error('Invalid autosave payload');
                        btn.prop('disabled', false).text('Restore & Save');
                    }
                },
                error: function() {
                    toastr.error('Failed to restore revision.');
                    btn.prop('disabled', false).text('Restore & Save');
                }
            });
        });

        // Helper: Save resume before download
        function saveResumeBeforeDownload(onSuccess) {
            $('.experience-item').each(function(index) {
                $(this).find('.exp-current-check').val(index);
            });

            const formData = $('#resume-form').serialize();

            $.ajax({
                url: '<?= site_url("candidate/resumes/save") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.id) {
                        $('input[name="id"]').val(response.id);
                        onSuccess(response.id);
                    } else {
                        const existingId = $('input[name="id"]').val();
                        if (existingId) {
                            onSuccess(existingId);
                        } else {
                            toastr.error('Could not determine resume ID for download.');
                        }
                    }
                },
                error: function() {
                    toastr.error('Failed to save latest changes. Trying to download anyway...');
                    const existingId = $('input[name="id"]').val();
                    if (existingId) {
                        onSuccess(existingId);
                    } else {
                        toastr.error('Please save your resume first.');
                    }
                }
            });
        }

        // PDF Download Click Handler
        $(document).on('click', '.download-pdf-btn', function() {
            const btn = $(this);
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner spinner-sm"></span> Preparing PDF...');
            
            saveResumeBeforeDownload(function(id) {
                btn.prop('disabled', false).html(originalHtml);
                window.location.href = '<?= site_url("candidate/resumes/download/") ?>' + id;
            });
        });

        // DOCX Download Click Handler
        $(document).on('click', '.download-docx-btn', function() {
            const btn = $(this);
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner spinner-sm"></span> Preparing Word...');
            
            saveResumeBeforeDownload(function(id) {
                btn.prop('disabled', false).html(originalHtml);
                window.location.href = '<?= site_url("candidate/resumes/download-docx/") ?>' + id;
            });
        });

        // ==========================================
        // AI RESUME COACH DRAWER INTEGRATION
        // ==========================================
        let coachHistory = [];
        let lastFocusedTextarea = null;

        // Keep track of focused inputs in the builder form to paste content
        $(document).on('focus', '#resume-form textarea, #resume-form input[type="text"]', function() {
            lastFocusedTextarea = $(this);
        });

        // Toggle / show coach offcanvas event
        $('#aiResumeCoachDrawer').on('shown.bs.offcanvas', function () {
            if ($('#coach-chat-messages').children().length === 0) {
                // Seed initial message from AI Coach (plain text, no markdown)
                showCoachMessage('coach', 'Hello, I am ResumeAI, your resume consultant. To get started, what is your target role and industry?');
            }
        });

        // Submit message form
        $('#coach-chat-form').on('submit', function(e) {
            e.preventDefault();
            sendCoachMessage();
        });

        function sendCoachMessage() {
            const inputField = $('#coach-chat-input');
            const message = inputField.val().trim();
            if (!message) return;

            // Append user bubble
            showCoachMessage('user', message);
            inputField.val('');

            // Append typing indicator
            showTypingIndicator();

            // Send AJAX request
            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/chat") ?>',
                type: 'POST',
                data: {
                    message: message,
                    history: JSON.stringify(coachHistory),
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    removeTypingIndicator();
                    if (response.reply) {
                        showCoachMessage('coach', response.reply);
                        // Save in local history array
                        coachHistory.push({sender: 'user', message: message});
                        coachHistory.push({sender: 'model', message: response.reply});
                    } else {
                        showCoachMessage('coach', 'I experienced an issue parsing the coaching response. Let\'s continue our session.');
                    }
                },
                error: function() {
                    removeTypingIndicator();
                    showCoachMessage('coach', 'Sorry, I am having trouble connecting right now. Let\'s continue.');
                }
            });
        }

        function showCoachMessage(sender, text) {
            const container = $('#coach-chat-messages');
            
            // Helper to escape HTML for user messages
            function escapeHtml(str) {
                return String(str).replace(/[&<>"']/g, function (s) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s]);
                });
            }

            // For coach messages we accept simple HTML from the server (server sanitizes). For user messages escape HTML.
            let formattedText;
            if (sender === 'coach') {
                // preserve simple HTML returned by server; normalize line endings
                formattedText = String(text).replace(/\r/g, '');
            } else {
                formattedText = escapeHtml(text).replace(/\n\n/g, '<br><br>').replace(/\n/g, '<br>');
            }

            const bubbleId = 'bubble-' + Date.now();
            let html = `
                <div class="coach-bubble ${sender}" id="${bubbleId}">
                    <div>${formattedText}</div>
            `;

            // If coach, add action pasting toolbar helpers
            if (sender === 'coach') {
                html += `
                    <div class="mt-2 d-flex flex-wrap gap-1 border-top border-secondary border-opacity-10 pt-2">
                        <button type="button" class="coach-apply-btn apply-to-summary-btn" data-text-id="${bubbleId}-text" style="font-size: 10px; padding: 3px 8px; border-radius: 12px;">
                            <i class="ti ti-blockquote me-1"></i> Apply to Summary
                        </button>
                        <button type="button" class="coach-apply-btn apply-to-active-btn" data-text-id="${bubbleId}-text" style="font-size: 10px; padding: 3px 8px; border-radius: 12px;">
                            <i class="ti ti-edit me-1"></i> Apply to Active Field
                        </button>
                    </div>
                `;
            }

            html += `</div>`;
            container.append(html);
            
            // Store raw text in a hidden element inside the bubble for precise extraction
            if (sender === 'coach') {
                $(`#${bubbleId}`).append(`<div id="${bubbleId}-text" style="display:none;"></div>`);
                // Use jQuery data to keep raw payload (may include HTML)
                $(`#${bubbleId}-text`).data('raw', text);
            }

            // Scroll chat to bottom
            const chatWindow = document.getElementById('coach-chat-window');
            if (chatWindow) {
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }
        }

        function showTypingIndicator() {
            removeTypingIndicator();
            const container = $('#coach-chat-messages');
            const html = `
                <div class="coach-bubble coach typing-indicator-bubble align-self-start" id="coach-typing-indicator" style="background-color: #1e293b; border: 1px solid #334155; border-top-left-radius: 4px; max-width: 85%;">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            `;
            container.append(html);
            const chatWindow = document.getElementById('coach-chat-window');
            if (chatWindow) {
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }
        }

        function removeTypingIndicator() {
            $('#coach-typing-indicator').remove();
        }

        // Apply to Professional Summary action handler
        $(document).on('click', '.apply-to-summary-btn', function() {
            const textId = $(this).data('text-id');
            const $hidden = $('#' + textId);
            const rawText = $hidden.length && $hidden.data('raw') ? $hidden.data('raw') : $hidden.text();
            const polishedText = extractResumeContent(rawText);

            // Store previous value for undo
            const prev = $('#resume-summary').val();
            $('#resume-summary').data('prev', prev);

            $('#resume-summary').val(polishedText);
            toastr.success('Applied to Professional Summary!');
            
            // Scroll to the professional summary element
            const targetEl = $("#resume-summary");
            if (targetEl.length && targetEl.is(':visible') && targetEl.offset()) {
                $('html, body').animate({
                    scrollTop: targetEl.offset().top - 120
                }, 300);
            }
        });

        // Apply to Active/Last Focused text input or textarea
        $(document).on('click', '.apply-to-active-btn', function() {
            const textId = $(this).data('text-id');
            const $hidden = $('#' + textId);
            const rawText = $hidden.length && $hidden.data('raw') ? $hidden.data('raw') : $hidden.text();
            const polishedText = extractResumeContent(rawText);

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                // store previous for undo
                lastFocusedTextarea.data('prev', lastFocusedTextarea.val());
                lastFocusedTextarea.val(polishedText);
                toastr.success('Applied to the active input field!');
                
                // Focus it and flash it
                lastFocusedTextarea.focus();
                lastFocusedTextarea.css('border-color', '#0d609e');
                setTimeout(function() {
                    lastFocusedTextarea.css('border-color', '');
                }, 1000);
            } else {
                // Fallback to first work experience description block
                const firstExpDesc = $('textarea[name="exp_description[]"]').first();
                if (firstExpDesc.length > 0) {
                    // store previous for undo
                    firstExpDesc.data('prev', firstExpDesc.val());
                    firstExpDesc.val(polishedText);
                    toastr.info('No active input was selected. Applied to first work experience description.');
                    
                    if (firstExpDesc.is(':visible') && firstExpDesc.offset()) {
                        $('html, body').animate({
                            scrollTop: firstExpDesc.offset().top - 120
                        }, 300);
                    }
                    firstExpDesc.focus();
                } else {
                    // Otherwise default to summary
                    const targetSummary = $('#resume-summary');
                    targetSummary.val(polishedText);
                    toastr.info('No active input was selected. Applied to Professional Summary.');
                    
                    if (targetSummary.is(':visible') && targetSummary.offset()) {
                        $('html, body').animate({
                            scrollTop: targetSummary.offset().top - 120
                        }, 300);
                    }
                }
            }
        });

        // Utility: Extract and clean raw markdown, HTML tags or blockquoted suggestions inside AI messages
        function extractResumeContent(text) {
            if (!text || typeof text !== 'string') {
                if (text === null || text === undefined) return '';
                text = String(text);
            }
            let extracted = text;
            
            // 1. Extract content from code block if present
            const codeBlockRegex = /```(?:[a-zA-Z]+)?\n([\s\S]+?)\n```/;
            const codeMatch = text.match(codeBlockRegex);
            if (codeMatch && codeMatch[1]) {
                extracted = codeMatch[1];
            } else {
                // 2. Extract blockquote block if present
                const quoteRegex = /(?:^|\n)>\s*([\s\S]+?)(?:\n\n|\n$|$)/;
                const quoteMatch = text.match(quoteRegex);
                if (quoteMatch && quoteMatch[1]) {
                    extracted = quoteMatch[1];
                }
            }
            
            // Strip any remaining HTML tags and markdown markers for clean resume placement
            return extracted
                .replace(/<[^>]*>/g, '') // strip HTML tags
                .replace(/^>\s*/gm, '')  // remove leading blockquote carrots
                .replace(/[*#`]/g, '')   // strip asterisks, pound headers, and backticks
                .trim();
        }

        // AI Preview modal actions
        $('#aiApplyBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text() || $('#aiPreviewRender').html() || '';
            const polished = extractResumeContent(raw);
            const target = $('#resume-summary');
            if (target.length) {
                target.data('prev', target.val());
                target.val(polished);
                // Scroll to summary section if hidden/collapsed
                const secSummary = $('#sec-summary');
                if (secSummary.length && !secSummary.hasClass('open')) {
                    secSummary.addClass('open');
                }
            }
            $('#aiPreviewModal').modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('overflow', '');
            toastr.success('Applied AI content to Professional Summary');
        });

        $('#aiCopyPlainBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text() || '';
            const plain = extractResumeContent(raw);
            navigator.clipboard.writeText(plain).then(function() {
                toastr.success('Copied plain text to clipboard');
            }, function() {
                toastr.info('Copy failed — you can manually copy from the preview.');
            });
        });

        // Apply preview content to last-focused input/textarea (or reasonable fallback)
        $('#aiApplyActiveBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text() || '';
            const polished = extractResumeContent(raw);

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                // store previous for undo
                lastFocusedTextarea.data('prev', lastFocusedTextarea.val());
                lastFocusedTextarea.val(polished);
                $('#aiPreviewModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
                toastr.success('Applied to the active input field!');
                lastFocusedTextarea.focus();
                lastFocusedTextarea.css('border-color', '#0d609e');
                setTimeout(function() { lastFocusedTextarea.css('border-color', ''); }, 1000);
                return;
            }

            // Fallback to first work experience description
            const firstExpDesc = $('textarea[name="exp_description[]"]').first();
            if (firstExpDesc.length > 0) {
                firstExpDesc.data('prev', firstExpDesc.val());
                firstExpDesc.val(polished);
                $('#aiPreviewModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
                toastr.info('No active input was selected. Applied to first work experience description.');
                if (firstExpDesc.is(':visible') && firstExpDesc.offset()) {
                    $('html, body').animate({ scrollTop: firstExpDesc.offset().top - 120 }, 300);
                }
                firstExpDesc.focus();
                return;
            }

            // Otherwise default to summary
            const targetSummary = $('#resume-summary');
            targetSummary.data('prev', targetSummary.val());
            targetSummary.val(polished);
            $('#aiPreviewModal').modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('overflow', '');
            toastr.info('No active input was selected. Applied to Professional Summary.');
            if (targetSummary.length && targetSummary.is(':visible') && targetSummary.offset()) {
                $('html, body').animate({ scrollTop: targetSummary.offset().top - 120 }, 300);
            }
        });

        // Undo last AI apply for summary or focused field
        $(document).on('click', '#undo-ai-apply', function() {
            const $summary = $('#resume-summary');
            const prev = $summary.data('prev');
            if (typeof prev !== 'undefined') {
                $summary.val(prev);
                $summary.removeData('prev');
                toastr.success('Undo applied');
                return;
            }

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                const prevField = lastFocusedTextarea.data('prev');
                if (typeof prevField !== 'undefined') {
                    lastFocusedTextarea.val(prevField);
                    lastFocusedTextarea.removeData('prev');
                    toastr.success('Undo applied to active field');
                    return;
                }
            }

            toastr.info('Nothing to undo');
        });

    // ── PRINT ARCHITECTURE ──
    // beforeprint moves #doc out of the preview wrappers (which may have
    // transforms / overflow: hidden that clip the printed output).
    // afterprint restores the DOM to its live state.
    var _printRoot = document.createElement('div');
    _printRoot.id = 'print-root';
    _printRoot.style.display = 'none';
    document.body.appendChild(_printRoot);
    var _docHome = null;

    function _toPrintRoot() {
        var doc = document.getElementById('doc');
        if (doc && doc.parentElement !== _printRoot) {
            _docHome = doc.parentElement;
            _printRoot.appendChild(doc);
        }
    }
    function _fromPrintRoot() {
        var doc = document.getElementById('doc');
        if (_docHome && doc && doc.parentElement === _printRoot) {
            _docHome.appendChild(doc);
            _docHome = null;
        }
    }

    window.addEventListener('beforeprint', function () {
        _toPrintRoot();
        window._prevDocTitle = document.title;
        // Use candidate's full name as the print-dialog/PDF filename
        var nameInput = document.querySelector('input[name="full_name"]');
        if (nameInput && nameInput.value.trim()) {
            document.title = nameInput.value.trim() + ' — Resume';
        }
    });
    window.addEventListener('afterprint', function () {
        _fromPrintRoot();
        if (window._prevDocTitle) { document.title = window._prevDocTitle; }
    });

    // Download-as-PDF shortcut via browser print dialog
    $(document).on('click', '.btn-print-pdf', function () {
        // Brief toast advising how to save cleanly
        if (typeof toastr !== 'undefined') {
            toastr.info(
                'In the print dialog: set <b>Destination → Save as PDF</b>, ' +
                'open <b>More settings</b> and untick <b>Headers and footers</b>.',
                'Saving as PDF', { timeOut: 6000, extendedTimeOut: 2000 }
            );
        }
    // ── 1-CLICK AUTO-FILL FROM CANDIDATE PROFILE ──
    $(document).on('click', '#btn-autofill-profile', function() {
        var $btn = $(this);
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Filling...');

        $.ajax({
            url: '<?= base_url('candidate/resumes/profile-data') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success && res.data) {
                    var d = res.data;
                    if (d.full_name) $('input[name="full_name"]').val(d.full_name);
                    if (d.email) $('input[name="email"]').val(d.email);
                    if (d.phone) $('input[name="phone"]').val(d.phone);
                    if (d.location) $('input[name="location"]').val(d.location);
                    if (d.job_title && !$('input[name="title"]').val()) {
                        $('input[name="title"]').val(d.job_title + ' Resume');
                    }
                    if (d.bio) {
                        $('textarea[name="summary"]').val(d.bio);
                        if (window.summaryQuill) {
                            window.summaryQuill.root.innerHTML = d.bio;
                        }
                    }

                    // Auto-fill skills
                    if (d.skills && d.skills.length > 0) {
                        d.skills.forEach(function(sk) {
                            if (typeof addSkillItem === 'function') {
                                addSkillItem(sk);
                            }
                        });
                    }

                    // Auto-fill experiences if container is empty
                    if (d.experiences && d.experiences.length > 0) {
                        d.experiences.forEach(function(exp) {
                            if (typeof addExperienceItem === 'function') {
                                addExperienceItem(exp.company, exp.job_title || exp.position, exp.start_date, exp.end_date, exp.description, exp.is_current);
                            }
                        });
                    }

                    // Auto-fill education if container is empty
                    if (d.education && d.education.length > 0) {
                        d.education.forEach(function(edu) {
                            if (typeof addEducationItem === 'function') {
                                addEducationItem(edu.school || edu.institution, edu.degree, edu.field_of_study, edu.end_year ? edu.end_year + '-12-31' : null);
                            }
                        });
                    }

                    if (typeof updatePreview === 'function') updatePreview();
                    if (typeof renderPreview === 'function') renderPreview();
                    if (typeof toastr !== 'undefined') toastr.success('CV auto-filled with your profile info!');
                } else {
                    if (typeof toastr !== 'undefined') toastr.warning(res.message || 'Could not fetch profile info.');
                }
            },
            error: function() {
                if (typeof toastr !== 'undefined') toastr.error('Server error pulling profile data.');
            },
            complete: function() {
                $btn.prop('disabled', false).html(origHtml);
            }
        });
    });
});
});
