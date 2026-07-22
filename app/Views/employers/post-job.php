<?= $this->extend('layouts/employer') ?>

<?= $this->section('styles') ?>
<style>
.progress-bar { position: sticky; top: 70px; z-index: 900; background: var(--white); border-bottom: 1px solid var(--border); padding: 12px 0; box-shadow: 0 2px 8px rgba(10,47,87,.06); }
.progress-inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.progress-left { display: flex; align-items: center; gap: 16px; flex: 1; min-width: 0; }
.progress-track { flex: 1; max-width: 360px; height: 8px; background: var(--border); border-radius: 20px; position: relative; }
.progress-fill { height: 100%; background: linear-gradient(90deg, var(--brand), #1a7fd4); border-radius: 20px; transition: width .6s ease; }
.progress-text { font-size: .82rem; font-weight: 700; color: var(--text); white-space: nowrap; }
.progress-tip { font-size: .78rem; color: var(--accent-dark); font-weight: 600; white-space: nowrap; display: inline-flex; align-items: center; gap: 5px; }
.progress-tip svg { width: 13px; height: 13px; }
.progress-actions { display: flex; gap: 8px; flex-shrink: 0; }
.milestone-marker { position: absolute; top: 50%; transform: translate(-50%, -50%); width: 14px; height: 14px; border-radius: 50%; z-index: 2; background: var(--white); border: 2px solid #c8dff2; transition: background .35s ease, border-color .35s ease, box-shadow .35s ease; }
.milestone-marker.achieved { background: var(--accent); border-color: var(--accent); box-shadow: 0 0 0 3px rgba(237,144,32,.22); }
.milestone-marker.next { border-color: var(--accent); animation: marker-pulse 1.6s ease infinite; }
@keyframes marker-pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(237,144,32,.4); } 50% { box-shadow: 0 0 0 5px rgba(237,144,32,0); } }
.milestone-label { position: absolute; bottom: 14px; left: 50%; transform: translateX(-50%); font-size: .6rem; font-weight: 700; white-space: nowrap; letter-spacing: .03em; color: #b0bec5; transition: color .35s ease; }
.milestone-marker.achieved .milestone-label, .milestone-marker.next .milestone-label { color: var(--accent-dark); }
.wallet-chip { display: inline-flex; align-items: center; gap: 6px; background: var(--brand-deep); color: var(--white); padding: 5px 13px; border-radius: 20px; font-size: .78rem; font-weight: 700; flex-shrink: 0; cursor: default; user-select: none; transition: background .3s ease; }
.wallet-chip svg { width: 13px; height: 13px; }
.wallet-chip.has-balance { background: linear-gradient(120deg, var(--accent-dark), var(--accent)); color: var(--brand-deep); }
.milestone-toast { position: fixed; top: 90px; left: 50%; transform: translateX(-50%) translateY(-160px); z-index: 1200; background: var(--white); border-radius: 16px; padding: 18px 20px 18px 18px; box-shadow: 0 20px 60px rgba(10,47,87,.22), 0 4px 16px rgba(10,47,87,.1); border-left: 5px solid var(--accent); display: flex; align-items: center; gap: 14px; min-width: 320px; max-width: 480px; transition: transform .45s cubic-bezier(.34,1.56,.64,1), opacity .3s ease; opacity: 0; pointer-events: none; }
.milestone-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; pointer-events: auto; }
.toast-emoji { font-size: 2rem; line-height: 1; flex-shrink: 0; }
.toast-body { flex: 1; }
.toast-body strong { display: block; font-size: .95rem; font-weight: 700; color: var(--text); margin-bottom: 3px; }
.toast-body p { font-size: .8rem; color: var(--muted); margin: 0; line-height: 1.5; }
.toast-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1.3rem; line-height: 1; padding: 4px; border-radius: 6px; flex-shrink: 0; align-self: flex-start; transition: var(--transition); }
.toast-close:hover { background: var(--bg); color: var(--text); }
.milestone-bar { display: flex; align-items: center; gap: 10px; padding: 10px 16px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); font-size: .8rem; flex-wrap: wrap; }
.milestone-item { display: inline-flex; align-items: center; gap: 6px; }
.milestone-item .m-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: .73rem; font-weight: 700; }
.milestone-item .m-badge.locked { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
.milestone-item .m-badge.next { background: #fff3e0; color: var(--accent-dark); border: 1px solid #fde3bf; }
.milestone-item .m-badge.achieved { background: var(--brand-light); color: var(--brand); border: 1px solid #c8dff2; }
.milestone-divider { color: var(--border); font-size: 1rem; }
@media (max-width: 640px) {
  .milestone-toast { min-width: calc(100vw - 32px); max-width: calc(100vw - 32px); }
  .wallet-chip span.wallet-label { display: none; }
}
.profile-page { padding: 36px 0 72px; }
.profile-wrap { max-width: 860px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; }
.cv-card { background: var(--white); border: 1px solid var(--border); border-left: 3px solid var(--accent); border-radius: 12px; overflow: hidden; }
.cv-card-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px 16px; gap: 12px; }
.cv-card-title svg { width: 18px; height: 18px; color: var(--accent); }
.cv-card-done { display: inline-flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; padding: 3px 9px; border-radius: 20px; }
.cv-card-done.complete { background: var(--brand-light); color: var(--brand); border: 1px solid #c8dff2; }
.cv-card-done.incomplete { background: var(--bg); color: var(--muted); border: 1px solid var(--border); }
.cv-card-done.optional { background: var(--brand-light); color: var(--brand); }
.cv-card.is-complete { border-left-color: var(--brand); }
.cv-card-body { padding: 0 24px 24px; }
.cv-card-hint { font-size: .84rem; color: var(--muted); background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 12px 14px; margin-bottom: 18px; line-height: 1.6; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.form-grid.cols-1 { grid-template-columns: 1fr; }
.form-field { display: flex; flex-direction: column; gap: 5px; }
.form-field.full { grid-column: 1/-1; }
label { font-size: .82rem; font-weight: 600; color: var(--text); }
label .opt { font-weight: 400; color: var(--muted); font-size: .78rem; }
input[type="text"], input[type="email"], input[type="tel"], input[type="url"], input[type="date"], input[type="number"], select, textarea {
  width: 100%; border: 1px solid var(--border); border-radius: 8px;
  padding: 10px 14px; font-family: 'Inter',sans-serif; font-size: .9rem; color: var(--text);
  background: var(--bg); outline: none; transition: border-color var(--transition), background var(--transition);
  appearance: none; -webkit-appearance: none; min-height: 42px;
}
input:focus, select:focus, textarea:focus { border-color: var(--brand); background: var(--white); }
select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%235b6577' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m4 6 4 4 4-4'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }
textarea { resize: vertical; min-height: 110px; line-height: 1.65; }
.char-count { font-size: .74rem; color: var(--muted); text-align: right; margin-top: 3px; }
.form-check { display: flex; align-items: center; gap: 9px; font-size: .85rem; color: var(--text); cursor: pointer; }
.form-check input[type="checkbox"] { width: 16px; height: 16px; min-height: 16px; accent-color: var(--brand); cursor: pointer; }
.entry-list { display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px; }
.entry-item { border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; background: var(--bg); position: relative; }
.entry-item-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 14px; }
.entry-item-title { font-weight: 700; font-size: .9rem; }
.entry-item-sub { font-size: .8rem; color: var(--muted); }
.entry-remove { background: none; border: none; cursor: pointer; color: var(--muted); padding: 4px; border-radius: 6px; line-height: 0; transition: var(--transition); }
.entry-remove:hover { color: var(--danger); background: #fef2f2; }
.entry-remove svg { width: 15px; height: 15px; }
.skill-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 18px; }
.skill-row { display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: center; border: 1px solid var(--border); border-radius: var(--radius); padding: 14px 16px; background: var(--bg); }
.skill-dots { display: flex; gap: 6px; align-items: center; flex-shrink: 0; }
.skill-dot { width: 26px; height: 10px; border-radius: 20px; border: 1.5px solid var(--border); background: var(--white); cursor: pointer; transition: var(--transition); }
.skill-dot.active { background: var(--brand); border-color: var(--brand); }
.skill-dot:hover { border-color: var(--brand); }
.skill-level-label { font-size: .7rem; color: var(--muted); margin-left: 4px; white-space: nowrap; }
.skill-row-inner { display: flex; align-items: center; gap: 14px; }
.photo-upload { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
.photo-preview { width: 88px; height: 88px; border-radius: 50%; border: 2px solid var(--border); background: var(--bg); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
.photo-preview svg { width: 42px; height: 42px; color: var(--muted); }
.photo-preview img { width: 100%; height: 100%; object-fit: cover; }
.photo-upload-body { display: flex; flex-direction: column; gap: 6px; }
.photo-upload-body p { font-size: .8rem; color: var(--muted); }
.ai-action { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px; }
.ai-btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 8px; font-size: .82rem; font-weight: 700; background: linear-gradient(135deg, var(--brand-deep), var(--brand)); color: var(--white); border: none; cursor: pointer; transition: var(--transition); }
.ai-btn:hover { opacity: .9; }
.ai-btn svg { width: 15px; height: 15px; }
.form-actions { display: flex; align-items: center; gap: 10px; padding-top: 18px; border-top: 1px solid var(--border); margin-top: 20px; flex-wrap: wrap; }
.form-actions .autosave-note { font-size: .76rem; color: var(--muted); margin-left: auto; display: inline-flex; align-items: center; gap: 5px; }
.form-actions .autosave-note svg { width: 13px; height: 13px; color: var(--success); }
.social-row { display: grid; grid-template-columns: 160px 1fr auto; gap: 10px; align-items: center; margin-bottom: 10px; }
.social-row button.remove-social { background: none; border: none; cursor: pointer; color: var(--muted); padding: 8px; border-radius: 6px; line-height: 0; }
.social-row button.remove-social:hover { color: var(--danger); }
.social-row button.remove-social svg { width: 16px; height: 16px; }
.visibility-banner { background: var(--brand-light); border: 1px solid #c8dff2; border-radius: 12px; padding: 18px 22px; display: flex; align-items: center; gap: 16px; }
.visibility-banner svg { width: 22px; height: 22px; color: var(--brand); flex-shrink: 0; }
.visibility-banner-text { flex: 1; }
.visibility-banner-text strong { font-size: .9rem; font-weight: 700; color: var(--brand-deep); display: block; margin-bottom: 2px; }
.visibility-banner-text p { font-size: .82rem; color: var(--muted); margin: 0; }
@keyframes bar-pulse { 0%,100% { opacity:1; } 50% { opacity:.65; } }
.progress-fill.pulse { animation: bar-pulse .5s ease 2; }
.cv-gain-tip { display: inline-flex; align-items: center; gap: 4px; font-size: .68rem; font-weight: 700; letter-spacing: .02em; color: var(--accent-dark); background: #fff3e0; border: 1px solid #fde3bf; padding: 3px 9px; border-radius: 20px; flex-shrink: 0; white-space: nowrap; }
.cv-gain-tip svg { width: 11px; height: 11px; }
@keyframes next-glow { 0% { box-shadow: 0 0 0 0 rgba(8,97,169,.35); } 60% { box-shadow: 0 0 0 6px rgba(8,97,169,0); } 100% { box-shadow: none; } }
.cv-card.next-up { animation: next-glow .7s ease forwards; border-color: var(--brand) !important; }
.cv-card { cursor: default; }
.cv-card-header { list-style: none; cursor: pointer; display: flex; align-items: center; gap: 10px; padding: 20px 24px 16px; flex-wrap: wrap; -webkit-tap-highlight-color: transparent; user-select: none; }
.cv-card-header::-webkit-details-marker { display: none; }
.cv-card-header::marker { display: none; }
.cv-card-header:hover { background: #fafbfd; }
.cv-card-title { flex: 1; min-width: 0; display: flex; align-items: center; gap: 10px; font-family: 'Sora',sans-serif; font-size: 1.05rem; font-weight: 700; color: var(--text); }
.cv-card-title svg { width: 18px; height: 18px; color: var(--accent); flex-shrink: 0; }
.cv-card-done { display: inline-flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; padding: 3px 9px; border-radius: 20px; flex-shrink: 0; white-space: nowrap; margin-left: auto; }
.cv-chev { width: 18px; height: 18px; flex-shrink: 0; color: var(--muted); transition: transform .22s ease; }
.cv-card:not([open]) .cv-chev { transform: rotate(-90deg); }
.cv-card .cv-card-body { animation: cv-open .18s ease; }
@keyframes cv-open { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }
@media (prefers-reduced-motion: reduce) { .cv-card .cv-card-body { animation: none; } .cv-chev { transition: none; } }
.bottom-actions { display: flex; align-items: center; justify-content: center; gap: 12px; padding: 24px; border-top: 1px solid var(--border); background: var(--white); border-radius: 12px; }
@media (max-width: 860px) {
  .form-grid { grid-template-columns: 1fr; }
  .form-grid.cols-3 { grid-template-columns: 1fr 1fr; }
  .social-row { grid-template-columns: 1fr 1fr auto; }
}
@media (max-width: 640px) {
  .progress-inner { flex-direction: column; align-items: flex-start; gap: 10px; }
  .progress-tip { display: none; }
  .social-row { grid-template-columns: 1fr; }
  .photo-upload { flex-direction: column; align-items: flex-start; }
  .form-grid.cols-3 { grid-template-columns: 1fr; }
  .skill-row { grid-template-columns: 1fr; }
  .bottom-actions { flex-direction: column; }
  #salary-range-wrap, .form-grid { grid-template-columns: 1fr !important; }
}
.cv-card, .entry-item, .skill-row, .bottom-actions { background: #ffffff; }
.entry-item, .skill-row { background: #f5f7fb; }
.cv-card-title, h2, h3 { color: #141926; }
.pref-pill-group { display: flex; flex-wrap: wrap; gap: 8px; }
.pref-pill { display: inline-flex; align-items: center; gap: 7px; padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 20px; font-size: .84rem; font-weight: 500; cursor: pointer; transition: var(--transition); user-select: none; background: var(--white); }
.pref-pill input { width: 14px; height: 14px; min-height: 14px; accent-color: var(--brand); cursor: pointer; }
.pref-pill:has(input:checked) { background: var(--brand-light); border-color: var(--brand); color: var(--brand); font-weight: 600; }
.salary-range { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 10px; }
.salary-range span { text-align: center; color: var(--muted); font-size: .85rem; }
.lang-row { display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: center; margin-bottom: 10px; }
.lang-row .remove-lang { background: none; border: none; cursor: pointer; color: var(--muted); padding: 8px; border-radius: 6px; line-height: 0; transition: var(--transition); }
.lang-row .remove-lang:hover { color: var(--danger); }
.lang-row .remove-lang svg { width: 16px; height: 16px; }
.jr-auto-certs { margin-bottom: 22px; border: 1px solid var(--brand-light); border-radius: var(--radius); overflow: hidden; }
.jr-auto-header { display: flex; align-items: center; gap: 8px; padding: 11px 16px; background: var(--brand-light); font-size: .8rem; font-weight: 700; color: var(--brand); }
.jr-auto-header svg { width: 14px; height: 14px; }
.jr-auto-empty { padding: 14px 16px; font-size: .83rem; color: var(--muted); border-top: 1px solid var(--brand-light); }
.jr-cert-item { display: flex; align-items: center; gap: 14px; padding: 13px 16px; border-top: 1px solid var(--border); }
.jr-cert-icon { width: 36px; height: 36px; border-radius: 8px; background: var(--brand); color: var(--white); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.jr-cert-icon svg { width: 18px; height: 18px; }
.jr-cert-body { flex: 1; }
.jr-cert-body strong { display: block; font-size: .87rem; font-weight: 700; color: var(--text); }
.jr-cert-body span { font-size: .75rem; color: var(--muted); }
.jr-verified-tag { display: inline-flex; align-items: center; gap: 4px; font-size: .64rem; font-weight: 700; letter-spacing: .03em; text-transform: uppercase; padding: 3px 9px; border-radius: 20px; background: var(--brand); color: var(--white); flex-shrink: 0; }
.jr-verified-tag svg { width: 10px; height: 10px; }
.jr-auto-link { font-size: .78rem; color: var(--brand); font-weight: 600; padding: 10px 16px; display: block; border-top: 1px solid var(--brand-light); text-align: center; }
.jr-auto-link:hover { background: var(--brand-light); text-decoration: none; }
.ref-toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); margin-bottom: 18px; }
.ref-toggle-row label { font-size: .88rem; font-weight: 600; color: var(--text); cursor: pointer; }
.ref-entry { border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; background: var(--bg); margin-bottom: 14px; position: relative; }
.ref-entry-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.ref-entry-num { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); }
@media (max-width: 640px) {
  .lang-row { grid-template-columns: 1fr 1fr auto; }
  .salary-range { grid-template-columns: 1fr; }
  .salary-range span { display: none; }
}
.post-wrap { max-width: 900px; margin: 0 auto; padding: 0 20px 100px; }
@media (max-width: 640px) { .post-wrap { padding-bottom: 72px; } }
.info-note { display: flex; align-items: flex-start; gap: 10px; background: var(--brand-light); border: 1px solid #c8dff2; border-radius: var(--radius); padding: 12px 14px; font-size: .82rem; color: var(--brand-deep); line-height: 1.6; }
.info-note svg { width: 16px; height: 16px; flex-shrink: 0; color: var(--brand); margin-top: 1px; }
.job-card { background: var(--white); border: 1px solid var(--border); border-left: 3px solid var(--accent); border-radius: 12px; overflow: hidden; }
.job-card[open] .job-card-body { display: block; }
.job-card-header { list-style: none; cursor: pointer; display: flex; align-items: center; gap: 10px; padding: 18px 24px; -webkit-tap-highlight-color: transparent; user-select: none; }
.job-card-header::-webkit-details-marker { display: none; }
.job-card-header::marker { display: none; }
.job-card-header:hover { background: #fafbfd; }
.job-card-title { flex: 1; min-width: 0; display: flex; align-items: center; gap: 10px; font-family: 'Sora','Inter',sans-serif; font-size: 1rem; font-weight: 700; color: var(--text); }
.job-card-title svg { width: 18px; height: 18px; color: var(--accent); flex-shrink: 0; }
.job-card-body { padding: 0 24px 24px; }
.job-card-chev { width: 17px; height: 17px; flex-shrink: 0; color: var(--muted); transition: transform .2s ease; }
.job-card:not([open]) .job-card-chev { transform: rotate(-90deg); }
.required-star { color: #e53e3e; font-size: .8rem; margin-left: 2px; }
.toggle-wrap { display: flex; align-items: center; gap: 12px; }
.toggle { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; inset: 0; background: var(--border); border-radius: 24px; cursor: pointer; transition: .25s; }
.toggle-slider::before { content: ''; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: var(--white); border-radius: 50%; transition: .25s; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
.toggle input:checked + .toggle-slider { background: var(--brand); }
.toggle input:checked + .toggle-slider::before { transform: translateX(20px); }
.toggle-label { font-size: .88rem; font-weight: 600; color: var(--text); cursor: pointer; }
.toggle-sub { font-size: .76rem; color: var(--muted); display: block; margin-top: 2px; }
.tag-input-wrap { border: 1px solid var(--border); border-radius: 8px; padding: 8px 10px; background: var(--bg); min-height: 48px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; cursor: text; transition: border-color var(--transition); }
.tag-input-wrap:focus-within { border-color: var(--brand); background: var(--white); }
.skill-tag { display: inline-flex; align-items: center; gap: 5px; background: var(--brand-light); color: var(--brand); font-size: .8rem; font-weight: 600; padding: 4px 10px; border-radius: 20px; }
.skill-tag button { background: none; border: none; cursor: pointer; color: var(--brand); line-height: 0; padding: 0; font-size: 1rem; opacity: .7; }
.skill-tag button:hover { opacity: 1; }
.tag-input { border: none; outline: none; background: transparent; font-family: 'Inter',sans-serif; font-size: .88rem; min-width: 140px; color: var(--text); padding: 2px 4px; }
.method-group { display: flex; flex-wrap: wrap; gap: 8px; }
.method-pill { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border: 1.5px solid var(--border); border-radius: 10px; font-size: .85rem; font-weight: 500; cursor: pointer; transition: var(--transition); background: var(--white); }
.method-pill input { accent-color: var(--brand); width: 15px; height: 15px; min-height: 15px; }
.method-pill:has(input:checked) { background: var(--brand-light); border-color: var(--brand); color: var(--brand); font-weight: 600; }
.question-item { border: 1px solid var(--border); border-radius: var(--radius); padding: 14px 16px; background: var(--bg); margin-bottom: 10px; }
.question-item-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.question-handle { cursor: grab; color: var(--muted); }
.question-handle svg { width: 16px; height: 16px; }
.question-num { font-size: .72rem; font-weight: 700; color: var(--muted); background: var(--border); padding: 2px 7px; border-radius: 20px; }
.question-remove { background: none; border: none; cursor: pointer; color: var(--muted); margin-left: auto; line-height: 0; padding: 4px; border-radius: 6px; }
.question-remove:hover { color: var(--danger); background: #fef2f2; }
.question-remove svg { width: 14px; height: 14px; }
.mc-options-wrap { margin-top: 10px; display: none; border: 1px dashed var(--border); border-radius: 8px; padding: 12px; background: var(--white); }
.mc-options-wrap.visible { display: block; }
.mc-options-label { font-size: .73rem; font-weight: 700; color: var(--muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: .05em; }
.mc-option-row { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.mc-option-row input { flex: 1; border: 1px solid var(--border); border-radius: 6px; padding: 7px 10px; font-size: .84rem; font-family: 'Inter',sans-serif; color: var(--text); background: var(--bg); outline: none; transition: border-color var(--transition); }
.mc-option-row input:focus { border-color: var(--brand); background: var(--white); }
.mc-remove-opt { background: none; border: 1px solid var(--border); border-radius: 6px; width: 28px; height: 28px; cursor: pointer; color: var(--muted); display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: var(--transition); }
.mc-remove-opt:hover { border-color: #b91c1c; color: #b91c1c; background: #fef2f2; }
.mc-remove-opt svg { width: 13px; height: 13px; }
.mc-add-opt { display: inline-flex; align-items: center; gap: 5px; font-size: .78rem; font-weight: 600; color: var(--brand); background: none; border: none; cursor: pointer; padding: 2px 0; margin-top: 4px; }
.mc-add-opt:hover { text-decoration: underline; }
.mc-add-opt svg { width: 13px; height: 13px; }
.google-logo { width: 40px; height: 40px; border-radius: 8px; border: 1px solid #dadce0; display: flex; align-items: center; justify-content: center; background: var(--brand-light); flex-shrink: 0; font-weight: 700; font-size: .8rem; color: var(--brand); }
.google-meta-item svg { width: 14px; height: 14px; color: #70757a; }
.salary-conditional { display: none; }
.salary-conditional.visible { display: grid; }
.boost-row { display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border: 1.5px solid var(--border); border-radius: var(--radius); background: var(--bg); }
.boost-row.featured { border-color: #fde3bf; background: #fff9f0; }
.boost-row.urgent { border-color: #fecaca; background: #fef9f9; }
.boost-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
.boost-icon.orange { background: #fff3e0; }
.boost-icon.red { background: #fef2f2; }
.boost-icon svg { width: 18px; height: 18px; }
.boost-icon.orange svg { color: var(--accent); }
.boost-icon.red svg { color: var(--danger); }
.boost-body { flex: 1; min-width: 0; }
.boost-body-hd { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px; }
.boost-body-hd strong { font-size: .88rem; font-weight: 700; color: var(--text); }
.boost-body-desc { font-size: .78rem; color: var(--muted); line-height: 1.55; }
.boost-tag { display: inline-flex; align-items: center; font-size: .68rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; padding: 2px 9px; border-radius: 20px; flex-shrink: 0; }
.boost-tag.plan { background: var(--brand-light); color: var(--brand); border: 1px solid #c8dff2; }
.publish-bar { position: fixed; bottom: 0; left: var(--sidebar-w); right: 0; z-index: 1000; background: var(--white); border-top: 1px solid var(--border); box-shadow: 0 -4px 20px rgba(10,47,87,.15); padding: 14px 0; padding-bottom: calc(14px + env(safe-area-inset-bottom, 0)); transition: transform .3s ease; }
.publish-bar.bar-hidden { transform: translateY(110%); }
.publish-bar-inner { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 0 clamp(16px,3vw,32px); }
.publish-bar-info { display: flex; flex-direction: column; }
.publish-bar-info strong { font-size: .88rem; font-weight: 700; color: var(--text); }
.publish-bar-info span { font-size: .76rem; color: var(--muted); }
.publish-bar-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
@media (max-width: 1024px) {
  .publish-bar { display: none !important; }
}
@media (max-width: 640px) {
  .publish-bar { display: none !important; }
}
@media (max-width: 900px) {
  .save-btn, .btn-report { min-height: 44px; }
}
.content { padding-bottom: 140px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$isEdit = isset($job) && !empty($job);
$val = function($field, $default = '') use ($job, $isEdit) {
    if (session()->has('errors') || old($field) !== null) {
        return old($field);
    }
    if ($isEdit) {
        if (is_array($job)) {
            return $job[$field] ?? $default;
        } elseif (is_object($job)) {
            return $job->$field ?? $default;
        }
    }
    return $default;
};

$hasBenefit = function($value) use ($job, $isEdit) {
    if (session()->has('errors') || old('job_benefits') !== null) {
        $benefits = old('job_benefits') ?? [];
        return in_array($value, $benefits);
    }
    if ($isEdit) {
        $benefits = [];
        if (is_array($job)) {
            $benefits = $job['job_benefits'] ?? [];
        } elseif (is_object($job)) {
            $benefits = $job->job_benefits ?? [];
        }
        if (is_string($benefits)) {
            if (strpos($benefits, '[') === 0) {
                $benefits = json_decode($benefits, true) ?? [];
            } else {
                $benefits = explode(',', $benefits);
            }
        }
        $benefits = array_map('trim', (array)$benefits);
        return in_array($value, $benefits);
    }
    return false;
};
?>

<div class="page-head">
  <div class="page-head-left">
    <h1><svg aria-hidden="true"><use href="#i-plus"/></svg> <?= $isEdit ? 'Edit Job Listing' : 'Post a New Job' ?></h1>
    <p>Reach 115k+ candidates. Detailed listings get significantly more quality applications.</p>
  </div>
  <div class="page-actions">
    <a href="<?= base_url('employer/jobs') ?>" class="emp-btn emp-btn-outline emp-btn-sm">
      <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to My Jobs
    </a>
  </div>
</div>

<?php if (session()->has('errors')): ?>
    <div class="notice notice--warn" role="alert" style="margin-bottom: 20px; flex-direction: column; align-items: flex-start; gap: 8px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <svg aria-hidden="true" style="width:16px; height:16px; color:var(--danger);"><use href="#i-x"/></svg>
            <strong>Please fix the following errors:</strong>
        </div>
        <ul style="margin-left: 24px; font-size: 0.8rem; line-height: 1.5; margin-bottom: 0;">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Plan & Credits Summary -->
<?php if (isset($jobCredits) && $jobCredits === 'unlimited'): ?>
    <div class="notice notice--info" role="status" style="align-items:center; margin-bottom:20px;">
      <svg aria-hidden="true"><use href="#i-zap"/></svg>
      <span><b>Unlimited Access Plan</b> — you have unlimited job postings. No credits will be deducted.</span>
    </div>
<?php else: ?>
    <?php $credits = isset($jobCredits) ? (int)$jobCredits : 0; ?>
    <?php if ($credits <= 0): ?>
        <div class="notice notice--warn" role="status" style="align-items:center; margin-bottom:20px;">
          <svg aria-hidden="true"><use href="#i-zap"/></svg>
          <span><b>No Job Credits Available!</b> You need credits to post jobs. <a href="<?= base_url('employer/pricing') ?>" style="font-weight:700;text-decoration:underline">Purchase a bundle</a> or subscribe to a plan.</span>
        </div>
    <?php else: ?>
        <div class="notice notice--info" role="status" style="align-items:center; margin-bottom:20px;">
          <svg aria-hidden="true"><use href="#i-zap"/></svg>
          <span><b>Available Job Credits: <?= esc($credits) ?></b> — 1 credit will be used to post this job. <a href="<?= base_url('employer/pricing') ?>" style="font-weight:700;text-decoration:underline">Buy more credits</a>.</span>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="post-wrap">
  <form id="post-job-form" method="POST" action="<?= $isEdit ? base_url('employer/jobs/edit/' . (is_array($job) ? $job['id'] : $job->id)) : base_url('employer/jobs/post') ?>" novalidate>
    <?= csrf_field() ?>
    
    <!-- ══ 1. JOB OVERVIEW ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-overview">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-overview"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Job Overview</h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="form-grid">
          <div class="form-field">
            <label for="job-title">Job title <span class="required-star">*</span></label>
            <input type="text" id="job-title" name="title" autocomplete="off" placeholder="e.g. Senior Software Engineer" required maxlength="100" value="<?= esc($val('title')) ?>">
          </div>
          <div class="form-field">
            <label for="job-type">Job type <span class="required-star">*</span></label>
            <select id="job-type" name="job_type" required>
              <option value="">Select job type</option>
              <option value="full-time" <?= $val('job_type') == 'full-time' ? 'selected' : '' ?>>Full-time</option>
              <option value="part-time" <?= $val('job_type') == 'part-time' ? 'selected' : '' ?>>Part-time</option>
              <option value="contract" <?= $val('job_type') == 'contract' ? 'selected' : '' ?>>Contract</option>
              <option value="internship" <?= $val('job_type') == 'internship' ? 'selected' : '' ?>>Internship</option>
              <option value="temporary" <?= $val('job_type') == 'temporary' ? 'selected' : '' ?>>Temporary</option>
              <option value="volunteer" <?= $val('job_type') == 'volunteer' ? 'selected' : '' ?>>Volunteer</option>
              <option value="freelance" <?= $val('job_type') == 'freelance' ? 'selected' : '' ?>>Freelance</option>
            </select>
          </div>
          <div class="form-field">
            <label for="job-location">Location <span class="required-star">*</span></label>
            <select id="job-location" name="state_id" required>
              <option value="">Select state / Remote</option>
              <option value="remote" <?= $val('state_id') == 'remote' ? 'selected' : '' ?>>Remote (Nigeria-wide)</option>
              <?php foreach ($states as $state): 
                $stateId = is_object($state) ? $state->id : ($state['id'] ?? '');
                $stateName = is_object($state) ? $state->name : ($state['name'] ?? '');
              ?>
                <option value="<?= $stateId ?>" <?= $val('state_id') == $stateId ? 'selected' : '' ?>><?= esc($stateName) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-field">
            <label for="work-style">Work style <span class="required-star">*</span></label>
            <select id="work-style" name="location_type" required onchange="toggleHybridDays(this.value)">
              <option value="">Select work style</option>
              <option value="on-site" <?= $val('location_type') == 'on-site' ? 'selected' : '' ?>>On-site</option>
              <option value="remote" <?= $val('location_type') == 'remote' ? 'selected' : '' ?>>Remote</option>
              <option value="hybrid" <?= $val('location_type') == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
            </select>
          </div>

          <!-- Shown only when Hybrid is selected -->
          <div class="form-field" id="hybrid-days-wrap" style="display:none">
            <label for="hybrid-days">Days on-site per week <span class="opt">(shown on listing as "Hybrid · X days on-site")</span></label>
            <select id="hybrid-days" name="hybrid_days_onsite">
              <option value="">Select days on-site</option>
              <option value="1" <?= $val('hybrid_days_onsite') == '1' ? 'selected' : '' ?>>1 day on-site</option>
              <option value="2" <?= $val('hybrid_days_onsite') == '2' ? 'selected' : '' ?>>2 days on-site</option>
              <option value="3" <?= $val('hybrid_days_onsite') == '3' ? 'selected' : '' ?>>3 days on-site</option>
              <option value="4" <?= $val('hybrid_days_onsite') == '4' ? 'selected' : '' ?>>4 days on-site</option>
              <option value="flex" <?= $val('hybrid_days_onsite') == 'flex' ? 'selected' : '' ?>>Flexible — varies by week</option>
              <option value="agreed" <?= $val('hybrid_days_onsite') == 'agreed' ? 'selected' : '' ?>>As agreed with manager</option>
            </select>
          </div>
          <div class="form-field">
            <label for="industry">Industry <span class="required-star">*</span></label>
            <select id="industry" name="industry_id" required onchange="updateJobCategories(this.value)">
              <option value="">Select industry</option>
              <?php foreach ($industries as $ind): 
                $indId = is_object($ind) ? $ind->id : ($ind['id'] ?? '');
                $indName = is_object($ind) ? $ind->name : ($ind['name'] ?? '');
              ?>
                <option value="<?= $indId ?>" <?= $val('industry_id') == $indId ? 'selected' : '' ?>><?= esc($indName) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-field">
            <label for="job-category">Job category <span class="required-star">*</span></label>
            <select id="job-category" name="category_id" required>
              <option value="">Select category</option>
              <?php foreach ($categories as $cat): 
                $catId = is_object($cat) ? $cat->id : ($cat['id'] ?? '');
                $catName = is_object($cat) ? $cat->name : ($cat['name'] ?? '');
              ?>
                <option value="<?= $catId ?>" <?= $val('category_id') == $catId ? 'selected' : '' ?>><?= esc($catName) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-field">
            <label for="num-vacancies">Number of vacancies <span class="required-star">*</span></label>
            <select id="num-vacancies" name="num_vacancies" required>
              <option value="1" <?= $val('num_vacancies', '1') == '1' ? 'selected' : '' ?>>1 opening</option>
              <option value="2" <?= $val('num_vacancies') == '2' ? 'selected' : '' ?>>2 openings</option>
              <option value="3" <?= $val('num_vacancies') == '3' ? 'selected' : '' ?>>3 openings</option>
              <option value="4" <?= $val('num_vacancies') == '4' ? 'selected' : '' ?>>4 openings</option>
              <option value="5" <?= $val('num_vacancies') == '5' ? 'selected' : '' ?>>5 openings</option>
              <option value="6-10" <?= $val('num_vacancies') == '6-10' ? 'selected' : '' ?>>6 – 10 openings</option>
              <option value="11-20" <?= $val('num_vacancies') == '11-20' ? 'selected' : '' ?>>11 – 20 openings</option>
              <option value="20+" <?= $val('num_vacancies') == '20+' ? 'selected' : '' ?>>More than 20 openings</option>
            </select>
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 2. COMPENSATION ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-comp">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-comp"><svg aria-hidden="true"><use href="#i-naira"/></svg> Compensation</h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="cv-card-hint">Jobs with a displayed salary receive up to 3× more applications. You can show or hide the amount on the listing — the information helps our matching engine regardless.</div>
        <div class="form-grid">
          <div class="form-field full">
            <label>Salary type <span class="required-star">*</span></label>
            <div class="method-group" style="gap:8px">
              <label class="method-pill"><input type="radio" name="salary_type" value="range" onchange="showSalaryFields(this.value)" <?= $val('salary_type', 'range') == 'range' ? 'checked' : '' ?>> Salary range</label>
              <label class="method-pill"><input type="radio" name="salary_type" value="fixed" onchange="showSalaryFields(this.value)" <?= $val('salary_type') == 'fixed' ? 'checked' : '' ?>> Fixed amount</label>
              <label class="method-pill"><input type="radio" name="salary_type" value="negotiable" onchange="showSalaryFields(this.value)" <?= $val('salary_type') == 'negotiable' ? 'checked' : '' ?>> Negotiable</label>
              <label class="method-pill"><input type="radio" name="salary_type" value="undisclosed" onchange="showSalaryFields(this.value)" <?= $val('salary_type') == 'undisclosed' ? 'checked' : '' ?>> Undisclosed</label>
            </div>
          </div>

          <!-- Salary range fields (shown by default) -->
          <div class="form-field salary-conditional visible form-grid cols-1" id="salary-range-wrap" style="grid-column:1/-1;grid-template-columns:1fr 1fr;gap:16px;padding:0;border:none;background:none">
            <div class="form-field">
              <label for="salary-min">Minimum salary (₦/month)</label>
              <input type="number" id="salary-min" name="salary_min" autocomplete="off" placeholder="e.g. 200000" min="0" step="1000" value="<?= esc($val('salary_min')) ?>">
            </div>
            <div class="form-field">
              <label for="salary-max">Maximum salary (₦/month)</label>
              <input type="number" id="salary-max" name="salary_max" autocomplete="off" placeholder="e.g. 500000" min="0" step="1000" value="<?= esc($val('salary_max')) ?>">
            </div>
          </div>

          <!-- Fixed amount field (hidden) -->
          <div class="form-field salary-conditional" id="salary-fixed-wrap">
            <label for="salary-fixed">Salary amount (₦/month)</label>
            <input type="number" id="salary-fixed" name="salary_fixed" placeholder="e.g. 350000" min="0" step="1000" value="<?= esc($val('salary_fixed')) ?>">
          </div>

          <div class="form-field" id="salary-period-wrap">
            <label for="salary-period">Pay period</label>
            <select id="salary-period" name="salary_period">
              <option value="monthly" <?= $val('salary_period', 'monthly') == 'monthly' ? 'selected' : '' ?>>Per month</option>
              <option value="annually" <?= $val('salary_period') == 'annually' ? 'selected' : '' ?>>Per annum</option>
              <option value="daily" <?= $val('salary_period') == 'daily' ? 'selected' : '' ?>>Per day</option>
              <option value="hourly" <?= $val('salary_period') == 'hourly' ? 'selected' : '' ?>>Per hour</option>
            </select>
          </div>

          <div class="form-field" id="salary-display-wrap">
            <label style="display:block;margin-bottom:8px">Salary visibility</label>
            <label class="toggle-wrap" style="cursor:pointer">
              <span class="toggle">
                <input type="checkbox" name="show_salary" id="show-salary" value="1" <?= $val('show_salary', 1) ? 'checked' : '' ?>>
                <span class="toggle-slider"></span>
              </span>
              <span>
                <span class="toggle-label">Show salary on listing</span>
                <span class="toggle-sub">Candidates see the range — increases quality applications</span>
              </span>
            </label>
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 3. JOB DESCRIPTION ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-desc">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-desc"><svg aria-hidden="true"><use href="#i-file-text"/></svg> Job Description</h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="ai-action" style="margin-bottom:12px">
          <div>
            <label for="job-desc" style="font-size:.82rem;font-weight:600;color:var(--text)">Job description <span class="required-star">*</span></label>
            <p style="font-size:.78rem;color:var(--muted);margin-top:2px">Be specific — include responsibilities, what success looks like, and who you are looking for. Candidates compare listings side by side.</p>
          </div>
          <button type="button" class="ai-btn" title="AI writes a draft based on your job title, industry and requirements">
            <svg aria-hidden="true"><use href="#i-zap"/></svg> AI generate
          </button>
        </div>
        <div class="form-field">
          <textarea id="job-desc" name="description" rows="12" required maxlength="5000"
            placeholder="About the role:&#10;We are looking for a [Job Title] to join our [team/department]...&#10;&#10;Responsibilities:&#10;• ...&#10;• ...&#10;&#10;Requirements:&#10;• ...&#10;&#10;What we offer:&#10;• ..."
            oninput="updateCount('job-desc','desc-count',5000)"><?= esc($val('description')) ?></textarea>
          <div class="char-count"><span id="desc-count">0</span> / 5,000 — more detail = better candidate quality</div>
        </div>
      </div>
    </details>

    <!-- ══ 4. REQUIREMENTS ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-req">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-req"><svg aria-hidden="true"><use href="#i-cap"/></svg> Requirements</h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="form-grid">
          <div class="form-field">
            <label for="min-edu">Minimum education <span class="required-star">*</span></label>
            <select id="min-edu" name="min_education" required>
              <option value="">Select minimum</option>
              <option value="No formal education required" <?= $val('min_education') == 'No formal education required' ? 'selected' : '' ?>>No formal education required</option>
              <option value="WAEC / SSCE" <?= $val('min_education') == 'WAEC / SSCE' ? 'selected' : '' ?>>WAEC / SSCE</option>
              <option value="OND" <?= $val('min_education') == 'OND' ? 'selected' : '' ?>>OND</option>
              <option value="HND" <?= $val('min_education') == 'HND' ? 'selected' : '' ?>>HND</option>
              <option value="Bachelor's Degree" <?= ($val('min_education') == "Bachelor's Degree" || $val('min_education') == 'B.Sc / B.A / B.Eng') ? 'selected' : '' ?>>B.Sc / B.A / B.Eng</option>
              <option value="MBBS / B.Pharm" <?= $val('min_education') == 'MBBS / B.Pharm' ? 'selected' : '' ?>>MBBS / B.Pharm</option>
              <option value="PGD" <?= $val('min_education') == 'PGD' ? 'selected' : '' ?>>PGD</option>
              <option value="Master's Degree" <?= ($val('min_education') == "Master's Degree" || $val('min_education') == 'M.Sc / MBA / M.A') ? 'selected' : '' ?>>M.Sc / MBA / M.A</option>
              <option value="PhD" <?= ($val('min_education') == 'PhD' || $val('min_education') == 'Ph.D') ? 'selected' : '' ?>>Ph.D</option>
              <option value="Professional Certification" <?= $val('min_education') == 'Professional Certification' ? 'selected' : '' ?>>Professional Certification</option>
            </select>
          </div>
          <div class="form-field">
            <label for="years-exp">Years of experience <span class="required-star">*</span></label>
            <select id="years-exp" name="experience_level" required>
              <option value="">Select range</option>
              <option value="Entry Level (0-2 years)" <?= ($val('experience_level') == 'Entry Level (0-2 years)' || $val('experience_level') == 'No experience (Fresh graduate / Entry level)') ? 'selected' : '' ?>>No experience (Fresh graduate / Entry level)</option>
              <option value="Less than 1 year" <?= $val('experience_level') == 'Less than 1 year' ? 'selected' : '' ?>>Less than 1 year</option>
              <option value="1-2 years" <?= ($val('experience_level') == '1-2 years' || $val('experience_level') == '1–2 years') ? 'selected' : '' ?>>1–2 years</option>
              <option value="Mid Level (2-5 years)" <?= ($val('experience_level') == 'Mid Level (2-5 years)' || $val('experience_level') == '3–5 years') ? 'selected' : '' ?>>3–5 years</option>
              <option value="5-7 years" <?= ($val('experience_level') == '5-7 years' || $val('experience_level') == '5–7 years') ? 'selected' : '' ?>>5–7 years</option>
              <option value="Senior Level (5+ years)" <?= ($val('experience_level') == 'Senior Level (5+ years)' || $val('experience_level') == '7–10 years' || $val('experience_level') == '10+ years') ? 'selected' : '' ?>>5+ years</option>
              <option value="Executive Level" <?= $val('experience_level') == 'Executive Level' ? 'selected' : '' ?>>Executive Level</option>
            </select>
          </div>

          <div class="form-field full">
            <label>Required skills <span class="opt">(optional — improves matching)</span></label>
            <div class="tag-input-wrap" id="skill-tag-wrap" onclick="document.getElementById('skill-input').focus()">
              <!-- skill tags rendered here by JS -->
              <input type="text" id="skill-input" class="tag-input" placeholder="Type a skill and press Enter or comma…"
                     onkeydown="handleSkillInput(event)" oninput="this.style.width=(this.value.length*9+140)+'px'">
            </div>
            <input type="hidden" id="skills-hidden" name="skills" value="<?= esc($val('skills')) ?>">
            <span style="font-size:.76rem;color:var(--muted);margin-top:5px;display:block">e.g. JavaScript, Project Management, Communication, Excel</span>
          </div>

          <div class="form-field">
            <label for="nysc-required">NYSC status requirement</label>
            <select id="nysc-required" name="nysc_requirement">
              <option value="">No requirement</option>
              <option value="NYSC completed required" <?= $val('nysc_requirement') == 'NYSC completed required' ? 'selected' : '' ?>>NYSC completed required</option>
              <option value="NYSC exemption accepted" <?= $val('nysc_requirement') == 'NYSC exemption accepted' ? 'selected' : '' ?>>NYSC exemption accepted</option>
              <option value="NYSC not required" <?= $val('nysc_requirement') == 'NYSC not required' ? 'selected' : '' ?>>NYSC not required</option>
            </select>
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 5. JOB CONDITIONS ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-conditions">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-conditions">
          <svg aria-hidden="true"><use href="#i-calendar"/></svg>
          Job Conditions
        </h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="cv-card-hint">These details appear prominently on the listing and help candidates self-qualify before applying — reducing irrelevant applications significantly.</div>
        <div class="form-grid">
          <div class="form-field">
            <label for="job-schedule">Work schedule</label>
            <select id="job-schedule" name="job_schedule">
              <option value="">Select schedule</option>
              <option value="Monday – Friday" <?= $val('job_schedule') == 'Monday – Friday' ? 'selected' : '' ?>>Monday – Friday</option>
              <option value="Monday – Saturday" <?= $val('job_schedule') == 'Monday – Saturday' ? 'selected' : '' ?>>Monday – Saturday</option>
              <option value="Tuesday – Saturday" <?= $val('job_schedule') == 'Tuesday – Saturday' ? 'selected' : '' ?>>Tuesday – Saturday</option>
              <option value="Wednesday – Sunday" <?= $val('job_schedule') == 'Wednesday – Sunday' ? 'selected' : '' ?>>Wednesday – Sunday</option>
              <option value="Monday – Sunday (with days off)" <?= $val('job_schedule') == 'Monday – Sunday (with days off)' ? 'selected' : '' ?>>Monday – Sunday (with days off)</option>
              <option value="Rotating shifts" <?= $val('job_schedule') == 'Rotating shifts' ? 'selected' : '' ?>>Rotating shifts</option>
              <option value="Weekends only" <?= $val('job_schedule') == 'Weekends only' ? 'selected' : '' ?>>Weekends only</option>
              <option value="Flexible / Remote hours" <?= $val('job_schedule') == 'Flexible / Remote hours' ? 'selected' : '' ?>>Flexible / Remote hours</option>
              <option value="Other (specify in description)" <?= $val('job_schedule') == 'Other (specify in description)' ? 'selected' : '' ?>>Other (specify in description)</option>
            </select>
          </div>

          <div class="form-field">
            <label for="working-hours">Working hours</label>
            <select id="working-hours" name="working_hours">
              <option value="">Select hours</option>
              <option value="7:00am – 4:00pm" <?= $val('working_hours') == '7:00am – 4:00pm' ? 'selected' : '' ?>>7:00am – 4:00pm</option>
              <option value="8:00am – 5:00pm" <?= $val('working_hours') == '8:00am – 5:00pm' ? 'selected' : '' ?>>8:00am – 5:00pm</option>
              <option value="9:00am – 5:00pm" <?= $val('working_hours') == '9:00am – 5:00pm' ? 'selected' : '' ?>>9:00am – 5:00pm</option>
              <option value="9:00am – 6:00pm" <?= $val('working_hours') == '9:00am – 6:00pm' ? 'selected' : '' ?>>9:00am – 6:00pm</option>
              <option value="10:00am – 6:00pm" <?= $val('working_hours') == '10:00am – 6:00pm' ? 'selected' : '' ?>>10:00am – 6:00pm</option>
              <option value="10:00am – 7:00pm" <?= $val('working_hours') == '10:00am – 7:00pm' ? 'selected' : '' ?>>10:00am – 7:00pm</option>
              <option value="12:00pm – 8:00pm (split)" <?= $val('working_hours') == '12:00pm – 8:00pm (split)' ? 'selected' : '' ?>>12:00pm – 8:00pm (split)</option>
              <option value="Night shift" <?= $val('working_hours') == 'Night shift' ? 'selected' : '' ?>>Night shift</option>
              <option value="As negotiated" <?= $val('working_hours') == 'As negotiated' ? 'selected' : '' ?>>As negotiated</option>
              <option value="Other (specify in description)" <?= $val('working_hours') == 'Other (specify in description)' ? 'selected' : '' ?>>Other (specify in description)</option>
            </select>
          </div>

          <div class="form-field">
            <label for="accommodation">Accommodation</label>
            <select id="accommodation" name="accommodation">
              <option value="">Not applicable</option>
              <option value="Provided (fully covered by employer)" <?= $val('accommodation') == 'Provided (fully covered by employer)' ? 'selected' : '' ?>>Provided (fully covered by employer)</option>
              <option value="Provided (cost shared with employee)" <?= $val('accommodation') == 'Provided (cost shared with employee)' ? 'selected' : '' ?>>Provided (cost shared with employee)</option>
              <option value="Housing allowance provided instead" <?= $val('accommodation') == 'Housing allowance provided instead' ? 'selected' : '' ?>>Housing allowance provided instead</option>
              <option value="Not provided" <?= $val('accommodation') == 'Not provided' ? 'selected' : '' ?>>Not provided</option>
            </select>
          </div>

          <div class="form-field">
            <label for="job-gender">Gender requirement <span class="opt">(optional)</span></label>
            <select id="job-gender" name="gender_requirement">
              <option value="">No requirement (recommended)</option>
              <option value="Male only" <?= $val('gender_requirement') == 'Male only' ? 'selected' : '' ?>>Male only</option>
              <option value="Female only" <?= $val('gender_requirement') == 'Female only' ? 'selected' : '' ?>>Female only</option>
              <option value="Male preferred" <?= $val('gender_requirement') == 'Male preferred' ? 'selected' : '' ?>>Male preferred</option>
              <option value="Female preferred" <?= $val('gender_requirement') == 'Female preferred' ? 'selected' : '' ?>>Female preferred</option>
            </select>
          </div>

          <div class="form-field">
            <label for="age-bracket">Age bracket <span class="opt">(optional)</span></label>
            <select id="age-bracket" name="age_bracket" onchange="toggleAgeCustom(this.value)">
              <option value="">No requirement</option>
              <option value="18 – 25 years" <?= $val('age_bracket') == '18 – 25 years' ? 'selected' : '' ?>>18 – 25 years</option>
              <option value="18 – 30 years" <?= $val('age_bracket') == '18 – 30 years' ? 'selected' : '' ?>>18 – 30 years</option>
              <option value="21 – 35 years" <?= $val('age_bracket') == '21 – 35 years' ? 'selected' : '' ?>>21 – 35 years</option>
              <option value="25 – 35 years" <?= $val('age_bracket') == '25 – 35 years' ? 'selected' : '' ?>>25 – 35 years</option>
              <option value="25 – 40 years" <?= $val('age_bracket') == '25 – 40 years' ? 'selected' : '' ?>>25 – 40 years</option>
              <option value="30 – 45 years" <?= $val('age_bracket') == '30 – 45 years' ? 'selected' : '' ?>>30 – 45 years</option>
              <option value="35 – 50 years" <?= $val('age_bracket') == '35 – 50 years' ? 'selected' : '' ?>>35 – 50 years</option>
              <option value="custom" <?= ($val('age_bracket') == 'custom' || $val('age_bracket_custom')) ? 'selected' : '' ?>>Specify range</option>
            </select>
          </div>

          <div class="form-field" id="age-custom-wrap" style="display:none">
            <label for="age-custom">Specify age range</label>
            <input type="text" id="age-custom" name="age_bracket_custom" placeholder="e.g. 22 – 45 years" value="<?= esc($val('age_bracket_custom')) ?>">
          </div>

          <div class="form-field">
            <label for="probation">Probation period</label>
            <select id="probation" name="probation_period">
              <option value="">No probation</option>
              <option value="1 month" <?= $val('probation_period') == '1 month' ? 'selected' : '' ?>>1 month</option>
              <option value="2 months" <?= $val('probation_period') == '2 months' ? 'selected' : '' ?>>2 months</option>
              <option value="3m" <?= $val('probation_period', '3m') == '3m' ? 'selected' : '' ?>>3 months (standard)</option>
              <option value="6 months" <?= $val('probation_period') == '6 months' ? 'selected' : '' ?>>6 months</option>
              <option value="As negotiated" <?= $val('probation_period') == 'As negotiated' ? 'selected' : '' ?>>As negotiated</option>
            </select>
          </div>
        </div>

        <div class="info-note" style="margin-top:16px">
          <svg aria-hidden="true"><use href="#i-eye"/></svg>
          <span>Gender and age requirements are displayed on the listing as candidate guidance. JobberRecruit recommends leaving these open unless the role has a genuine occupational requirement — broader criteria attract a stronger applicant pool.</span>
        </div>
      </div>
    </details>

    <!-- ══ 6. BENEFITS & PERKS ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-benefits">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-benefits"><svg aria-hidden="true"><use href="#i-star"/></svg> Benefits &amp; Perks</h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="cv-card-hint">Pre-populated from your company profile. Uncheck any that don't apply to this specific role, or add extras. Candidates filter jobs by benefits.</div>
        
        <div style="margin-bottom:12px">
          <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Health &amp; Insurance</p>
          <div class="pref-pill-group">
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="hmo" <?= $hasBenefit('hmo') ? 'checked' : '' ?>> HMO / Health Insurance</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="hmo_dependants" <?= $hasBenefit('hmo_dependants') ? 'checked' : '' ?>> HMO covers dependants</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="life_insurance" <?= $hasBenefit('life_insurance') ? 'checked' : '' ?>> Life Insurance</label>
          </div>
        </div>
        
        <div style="margin-bottom:12px">
          <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Financial</p>
          <div class="pref-pill-group">
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="pension" <?= $hasBenefit('pension') ? 'checked' : '' ?>> Contributory Pension (CPS)</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="13th_month" <?= $hasBenefit('13th_month') ? 'checked' : '' ?>> 13th Month Salary</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="performance_bonus" <?= $hasBenefit('performance_bonus') ? 'checked' : '' ?>> Performance Bonus</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="stock_options" <?= $hasBenefit('stock_options') ? 'checked' : '' ?>> Stock Options / Equity</label>
          </div>
        </div>
        
        <div style="margin-bottom:12px">
          <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Allowances</p>
          <div class="pref-pill-group">
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="transport" <?= $hasBenefit('transport') ? 'checked' : '' ?>> Transport Allowance</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="meal" <?= $hasBenefit('meal') ? 'checked' : '' ?>> Meal Allowance</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="housing" <?= $hasBenefit('housing') ? 'checked' : '' ?>> Housing Allowance</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="airtime" <?= $hasBenefit('airtime') ? 'checked' : '' ?>> Airtime / Data</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="company_car" <?= $hasBenefit('company_car') ? 'checked' : '' ?>> Company Vehicle</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="relocation" <?= $hasBenefit('relocation') ? 'checked' : '' ?>> Relocation Support</label>
          </div>
        </div>
        
        <div>
          <p style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Growth &amp; Wellbeing</p>
          <div class="pref-pill-group">
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="training_budget" <?= $hasBenefit('training_budget') ? 'checked' : '' ?>> Training Budget</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="flex_hours" <?= $hasBenefit('flex_hours') ? 'checked' : '' ?>> Flexible Hours</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="parental_leave" <?= $hasBenefit('parental_leave') ? 'checked' : '' ?>> Paid Parental Leave</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="annual_leave_15" <?= $hasBenefit('annual_leave_15') ? 'checked' : '' ?>> 15+ Days Annual Leave</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="gym" <?= $hasBenefit('gym') ? 'checked' : '' ?>> Gym / Wellness</label>
            <label class="pref-pill"><input type="checkbox" name="job_benefits[]" value="team_retreats" <?= $hasBenefit('team_retreats') ? 'checked' : '' ?>> Team Retreats</label>
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 7. APPLICATION SETTINGS ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-app">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-app"><svg aria-hidden="true"><use href="#i-settings"/></svg> Application Settings</h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="form-grid">
          <div class="form-field">
            <label for="app-deadline">Application deadline</label>
            <input type="date" id="app-deadline" name="application_deadline" value="<?= esc($val('application_deadline')) ?>">
          </div>
          <div class="form-field">
            <label for="start-date">Expected start date</label>
            <input type="date" id="start-date" name="start_date" value="<?= esc($val('start_date')) ?>">
          </div>

          <div class="form-field">
            <label for="app-limit">Stop accepting after <span class="opt">(optional)</span></label>
            <select id="app-limit" name="application_limit">
              <option value="">No limit</option>
              <option value="10" <?= $val('application_limit') == '10' ? 'selected' : '' ?>>10 applications</option>
              <option value="20" <?= $val('application_limit') == '20' ? 'selected' : '' ?>>20 applications</option>
              <option value="50" <?= $val('application_limit') == '50' ? 'selected' : '' ?>>50 applications</option>
              <option value="100" <?= $val('application_limit') == '100' ? 'selected' : '' ?>>100 applications</option>
              <option value="200" <?= $val('application_limit') == '200' ? 'selected' : '' ?>>200 applications</option>
              <option value="500" <?= $val('application_limit') == '500' ? 'selected' : '' ?>>500 applications</option>
            </select>
            <span style="font-size:.76rem;color:var(--muted);margin-top:4px;display:block">Listing auto-closes once this number is reached — prevents inbox overflow</span>
          </div>

          <div class="form-field full">
            <label style="margin-bottom:10px;display:block">What candidates must submit</label>
            <div style="display:flex;flex-wrap:wrap;gap:16px">
              <label class="toggle-wrap" style="cursor:pointer">
                <span class="toggle">
                  <input type="checkbox" name="require_cv" id="require-cv" value="1" <?= $val('require_cv', 1) ? 'checked' : '' ?>>
                  <span class="toggle-slider"></span>
                </span>
                <span>
                  <span class="toggle-label">CV / Resume required</span>
                  <span class="toggle-sub">Candidates must upload their CV to apply</span>
                </span>
              </label>
              <label class="toggle-wrap" style="cursor:pointer">
                <span class="toggle">
                  <input type="checkbox" name="require_cover_letter" id="require-cover-letter" value="1" <?= $val('require_cover_letter', 0) ? 'checked' : '' ?>>
                  <span class="toggle-slider"></span>
                </span>
                <span>
                  <span class="toggle-label">Cover letter required</span>
                  <span class="toggle-sub">Candidates must write a cover letter when applying</span>
                </span>
              </label>
            </div>
          </div>

          <div class="form-field full">
            <label>Application method <span class="required-star">*</span></label>
            <div class="method-group" id="app-method-group">
              <label class="method-pill"><input type="radio" name="application_method" value="form" <?= $val('application_method', 'form') == 'form' ? 'checked' : '' ?> onchange="toggleMethodDetail(this.value)">
                <svg width="16" height="16" aria-hidden="true"><use href="#i-chat"/></svg>
                JobberRecruit form</label>
              <label class="method-pill"><input type="radio" name="application_method" value="whatsapp" <?= $val('application_method') == 'whatsapp' ? 'checked' : '' ?> onchange="toggleMethodDetail(this.value)">
                <svg width="16" height="16" aria-hidden="true"><use href="#i-whatsapp"/></svg>
                WhatsApp</label>
              <label class="method-pill"><input type="radio" name="application_method" value="email" <?= $val('application_method') == 'email' ? 'checked' : '' ?> onchange="toggleMethodDetail(this.value)">
                <svg width="16" height="16" aria-hidden="true"><use href="#i-mail"/></svg>
                Email</label>
              <label class="method-pill"><input type="radio" name="application_method" value="external" <?= $val('application_method') == 'external' ? 'checked' : '' ?> onchange="toggleMethodDetail(this.value)">
                <svg width="16" height="16" aria-hidden="true"><use href="#i-link"/></svg>
                External page</label>
            </div>
            
            <div id="method-detail" style="margin-top:10px;display:none">
              <input type="url" id="method_whatsapp_input" name="whatsapp_link" style="display:none;" placeholder="https://wa.me/2348000000000" value="<?= esc($val('whatsapp_link')) ?>">
              <input type="email" id="method_email_input" name="application_email" style="display:none;" placeholder="jobs@company.com" value="<?= esc($val('application_email')) ?>">
              <input type="url" id="method_external_input" name="external_url" style="display:none;" placeholder="https://company.com/apply" value="<?= esc($val('external_url')) ?>">
            </div>

            <!-- Shown when a non-JobberRecruit method is selected -->
            <div class="info-note" id="ats-unavailable-note" style="display:none;margin-top:10px">
              <svg aria-hidden="true"><use href="#i-eye"/></svg>
              <span>Pre-screening questions are only available when candidates apply through the <strong>JobberRecruit form</strong>. They are hidden while another application method is selected.</span>
            </div>
          </div>

          <div class="form-field full">
            <label>Who can apply? <span class="required-star">*</span></label>
            <div class="method-group" style="gap:8px">
              <label class="method-pill"><input type="radio" name="application_access" value="general" <?= $val('application_access', 'general') == 'general' ? 'checked' : '' ?>> Anyone (recommended)</label>
              <label class="method-pill"><input type="radio" name="application_access" value="authenticated" <?= $val('application_access') == 'authenticated' ? 'checked' : '' ?>> Registered candidates only</label>
              <label class="method-pill"><input type="radio" name="application_access" value="guest" <?= $val('application_access') == 'guest' ? 'checked' : '' ?>> Guest Applicants</label>
            </div>
            <span style="font-size:.76rem;color:var(--muted);margin-top:6px;display:block">"Registered only" improves application quality — candidates have a verified profile on JobberRecruit</span>
          </div>

          <!-- Notification preferences -->
          <div class="form-field full">
            <label style="margin-bottom:10px;display:block">Notification preferences</label>
            <div style="display:flex;flex-wrap:wrap;gap:16px">
              <label class="toggle-wrap" style="cursor:pointer">
                <span class="toggle"><input type="checkbox" name="notification_in_app" id="notify-inapp" value="1" <?= $val('notification_in_app', 1) ? 'checked' : '' ?>><span class="toggle-slider"></span></span>
                <span>
                  <span class="toggle-label">In-app notifications</span>
                  <span class="toggle-sub">Alerts in your dashboard when candidates apply</span>
                </span>
              </label>
              <label class="toggle-wrap" style="cursor:pointer">
                <span class="toggle"><input type="checkbox" name="notification_email_toggle" id="notify-email" value="1" <?= $val('notification_email_toggle', 0) ? 'checked' : '' ?>><span class="toggle-slider"></span></span>
                <span>
                  <span class="toggle-label">Email notifications</span>
                  <span class="toggle-sub">Email alert for each new application</span>
                </span>
              </label>
            </div>
            <div class="info-note" style="margin-top:12px">
              <svg aria-hidden="true"><use href="#i-eye"/></svg>
              <span>You'll always receive notifications in your dashboard. Email notifications are optional and can be changed at any time.</span>
            </div>
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 8. LISTING BOOST ══ -->
    <details class="job-card" open style="margin-bottom:20px" aria-labelledby="h-boost">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-boost"><svg aria-hidden="true"><use href="#i-trending-up"/></svg> Listing Boost <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(optional)</span></h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <div style="display:flex;flex-direction:column;gap:12px">
          <!-- Urgently Hiring -->
          <div class="boost-row urgent">
            <div class="boost-icon red"><svg aria-hidden="true"><use href="#i-zap"/></svg></div>
            <div class="boost-body">
              <div class="boost-body-hd">
                <strong>Urgently Hiring</strong>
                <span class="boost-tag plan">Plan required</span>
              </div>
              <p class="boost-body-desc">Adds an urgent badge to your listing and pins it above standard results — significantly increases click-through rate</p>
            </div>
            <label class="toggle" title="Mark as urgently hiring">
              <input type="checkbox" name="urgent_hiring" id="urgent-hiring" value="1" data-has-plan="0" onchange="handleUrgentToggle(this)" <?= $val('urgent_hiring') ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </label>
          </div>

          <!-- Featured Listing -->
          <div class="boost-row featured">
            <div class="boost-icon orange"><svg aria-hidden="true"><use href="#i-star"/></svg></div>
            <div class="boost-body">
              <div class="boost-body-hd">
                <strong>Featured Listing</strong>
                <span class="boost-tag plan">Plan required</span>
              </div>
              <p class="boost-body-desc">Pins your job to the top of search results and the homepage featured section for <strong>30 days</strong></p>
            </div>
            <label class="toggle" title="Feature this listing">
              <input type="checkbox" name="featured_listing" id="featured-listing" value="1" data-has-plan="0" onchange="handleFeaturedToggle(this)" <?= $val('featured_listing') ? 'checked' : '' ?>>
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>
      </div>
    </details>

    <!-- ══ 9. PRE-SCREENING QUESTIONS (ATS) ══ -->
    <details class="job-card" id="ats-section" style="margin-bottom:20px" aria-labelledby="h-ats">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-ats"><svg aria-hidden="true"><use href="#i-help"/></svg> Pre-screening Questions <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(ATS · optional)</span></h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <div class="cv-card-hint">Candidates answer these questions when applying. Their responses appear on your ATS dashboard alongside their CV — helping you shortlist without reading every application in full.</div>

        <div id="question-list">
          <div class="question-item" id="q-1">
            <div class="question-item-header">
              <span class="question-num">Q1</span>
              <input type="text" name="q_text[]" placeholder="e.g. Do you have experience with Python?" style="flex:1;border:none;background:transparent;font-size:.88rem;font-family:'Inter',sans-serif;outline:none;color:var(--text)">
              <button type="button" class="question-remove" onclick="removeQuestion(this)" aria-label="Remove question"><svg aria-hidden="true"><use href="#i-trash"/></svg></button>
            </div>
            <div class="form-grid" style="grid-template-columns:1fr 1fr;gap:10px">
              <div class="form-field">
                <label style="font-size:.76rem">Answer type</label>
                <select name="q_type[]" onchange="toggleMCOptions(this)">
                  <option value="yes_no">Yes / No</option>
                  <option value="short_text">Short text</option>
                  <option value="multiple_choice">Multiple choice</option>
                </select>
              </div>
              <div class="form-field">
                <label style="font-size:.76rem">Required?</label>
                <select name="q_required[]">
                  <option value="1">Required</option>
                  <option value="0">Optional</option>
                </select>
              </div>
            </div>

            <!-- Multiple choice options — shown when type = Multiple choice -->
            <div class="mc-options-wrap">
              <p class="mc-options-label">Answer options</p>
              <div class="mc-option-rows">
                <div class="mc-option-row">
                  <input type="text" name="q_options[]" placeholder="Option 1" aria-label="Option 1">
                  <button type="button" class="mc-remove-opt" onclick="removeMCOption(this)" aria-label="Remove option">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><use href="#i-x"/></svg>
                  </button>
                </div>
                <div class="mc-option-row">
                  <input type="text" name="q_options[]" placeholder="Option 2" aria-label="Option 2">
                  <button type="button" class="mc-remove-opt" onclick="removeMCOption(this)" aria-label="Remove option">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><use href="#i-x"/></svg>
                  </button>
                </div>
              </div>
              <button type="button" class="mc-add-opt" onclick="addMCOption(this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#i-plus"/></svg>
                Add option
              </button>
            </div>
          </div>
        </div>

        <button type="button" class="emp-btn emp-btn-outline emp-btn-sm" onclick="addQuestion()" style="margin-top:6px">
          <svg aria-hidden="true"><use href="#i-plus"/></svg> Add screening question
        </button>
      </div>
    </details>

    <!-- ══ 10. ANONYMOUS POSTING ══ -->
    <details class="job-card" style="margin-bottom:20px" aria-labelledby="h-anon">
      <summary class="job-card-header">
        <h2 class="job-card-title" id="h-anon"><svg aria-hidden="true"><use href="#i-eye-off"/></svg> Anonymous Posting <span style="font-size:.72rem;font-weight:400;color:var(--muted);margin-left:6px">(optional)</span></h2>
        <svg class="job-card-chev" width="17" height="17" aria-hidden="true"><use href="#i-chev-down"/></svg>
      </summary>
      <div class="job-card-body">
        <label class="toggle-wrap" style="cursor:pointer;margin-bottom:12px">
          <span class="toggle"><input type="checkbox" name="is_anonymous" id="post-anon" value="1" onchange="handleAnonToggle(this)" <?= ($val('is_anonymous') || $val('anonymous')) ? 'checked' : '' ?>><span class="toggle-slider"></span></span>
          <span>
            <span class="toggle-label">Post this job anonymously</span>
            <span class="toggle-sub">Your company name and logo will be hidden from the public listing</span>
          </span>
        </label>
        <div class="info-note" id="anon-note" style="display:none">
          <svg aria-hidden="true"><use href="#i-eye-off"/></svg>
          <span>Anonymous listings typically receive fewer applications — candidates trust named employers more. Only use this for sensitive replacement hires or confidential projects.</span>
        </div>
      </div>
    </details>
  </form>
  
  <!-- Floating Publish Bar -->
  <div class="publish-bar">
    <div class="publish-bar-inner">
      <div class="publish-bar-info">
        <strong>Ready to post?</strong>
        <span>Make sure all required fields marked with * are filled.</span>
      </div>
      <div class="publish-bar-actions">
        <button type="button" class="emp-btn emp-btn-outline btn-outline" onclick="saveDraft()">Save as draft</button>
        <button type="submit" class="emp-btn emp-btn-accent btn-accent" form="post-job-form" onclick="publishJob(event)">
          <svg aria-hidden="true" style="width:16px; height:16px; fill:currentColor"><use href="#i-send"/></svg> Publish job
        </button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('mobile_cta') ?>
<button type="button" class="emp-btn emp-btn-outline" onclick="saveDraft()">Save as draft</button>
<button type="submit" class="emp-btn emp-btn-accent" form="post-job-form" onclick="publishJob(event)">
  <svg aria-hidden="true" style="width:16px; height:16px;"><use href="#i-send"/></svg> Publish job
</button>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/* ── Character counter ── */
function updateCount(fieldId, countId, max) {
  var el = document.getElementById(fieldId);
  var counter = document.getElementById(countId);
  if (el && counter) counter.textContent = el.value.length.toLocaleString();
}

/* ── Salary field visibility ── */
function showSalaryFields(type) {
  var rangeWrap  = document.getElementById('salary-range-wrap');
  var fixedWrap  = document.getElementById('salary-fixed-wrap');
  var periodWrap = document.getElementById('salary-period-wrap');
  var displayWrap = document.getElementById('salary-display-wrap');
  if (rangeWrap)  rangeWrap.classList.remove('visible');
  if (fixedWrap)  fixedWrap.classList.remove('visible');
  if (type === 'range') { if (rangeWrap) rangeWrap.classList.add('visible'); }
  if (type === 'fixed') { if (fixedWrap) fixedWrap.classList.add('visible'); }
  var hide = type === 'negotiable' || type === 'undisclosed';
  if (periodWrap)  periodWrap.style.display  = hide ? 'none' : '';
  if (displayWrap) displayWrap.style.display = hide ? 'none' : '';
}

/* ── Application method detail field ── */
function toggleMethodDetail(value) {
  var detail   = document.getElementById('method-detail');
  var atsNote  = document.getElementById('ats-unavailable-note');
  var atsSection = document.getElementById('ats-section');

  var whatsappInput = document.getElementById('method_whatsapp_input');
  var emailInput = document.getElementById('method_email_input');
  var externalInput = document.getElementById('method_external_input');

  if (whatsappInput) whatsappInput.style.display = 'none';
  if (emailInput) emailInput.style.display = 'none';
  if (externalInput) externalInput.style.display = 'none';

  var needsDetail = value === 'whatsapp' || value === 'email' || value === 'external';
  if (detail) {
    detail.style.display = needsDetail ? 'block' : 'none';
  }

  if (needsDetail) {
    if (value === 'whatsapp' && whatsappInput) whatsappInput.style.display = 'block';
    if (value === 'email' && emailInput) emailInput.style.display = 'block';
    if (value === 'external' && externalInput) externalInput.style.display = 'block';
  }

  /* ATS section — only relevant when using the JobberRecruit form */
  var isJR = value === 'form' || value === 'jobberrecruit';
  if (atsSection) {
    atsSection.style.display  = isJR ? '' : 'none';
    atsSection.style.opacity  = isJR ? '1' : '0';
    atsSection.style.pointerEvents = isJR ? '' : 'none';
  }
  if (atsNote) atsNote.style.display = isJR ? 'none' : 'flex';
}

/* ── Skill tags ── */
var skills = [];
function handleSkillInput(e) {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    var val = e.target.value.trim().replace(/,$/, '');
    if (val && !skills.includes(val)) addSkill(val);
    e.target.value = '';
  } else if (e.key === 'Backspace' && !e.target.value && skills.length) {
    removeSkill(skills[skills.length - 1]);
  }
}
function addSkill(text) {
  if (!text) return;
  skills.push(text);
  var wrap = document.getElementById('skill-tag-wrap');
  var inp  = document.getElementById('skill-input');
  var tag  = document.createElement('span');
  tag.className = 'skill-tag';
  tag.dataset.skill = text;
  var label = document.createTextNode(text);
  var btn   = document.createElement('button');
  btn.type  = 'button';
  btn.setAttribute('aria-label', 'Remove ' + text);
  btn.textContent = '×';
  btn.addEventListener('click', function(){ removeSkill(text); });
  tag.appendChild(label);
  tag.appendChild(btn);
  wrap.insertBefore(tag, inp);
  document.getElementById('skills-hidden').value = skills.join(',');
}
function removeSkill(text) {
  skills = skills.filter(function(s){ return s !== text; });
  var all = document.querySelectorAll('.skill-tag');
  all.forEach(function(t){ if (t.dataset.skill === text) t.remove(); });
  document.getElementById('skills-hidden').value = skills.join(',');
}

/* ── Pre-screening questions ── */
var qCount = 1;
function addQuestion() {
  qCount++;
  var list = document.getElementById('question-list');
  var div  = document.createElement('div');
  div.className = 'question-item';
  div.id = 'q-' + qCount;
  
  var hdr = document.createElement('div');
  hdr.className = 'question-item-header';

  var num = document.createElement('span');
  num.className = 'question-num';
  num.textContent = 'Q' + qCount;

  var qInput = document.createElement('input');
  qInput.type = 'text';
  qInput.name = 'q_text[]';
  qInput.placeholder = 'Type your screening question...';
  qInput.style.cssText = 'flex:1;border:none;background:transparent;font-size:.88rem;font-family:Inter,sans-serif;outline:none;color:var(--text)';

  var removeBtn = document.createElement('button');
  removeBtn.type = 'button';
  removeBtn.className = 'question-remove';
  removeBtn.setAttribute('aria-label', 'Remove');
  removeBtn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>';
  removeBtn.addEventListener('click', function(){ removeQuestion(removeBtn); });

  hdr.appendChild(num);
  hdr.appendChild(qInput);
  hdr.appendChild(removeBtn);

  var grid = document.createElement('div');
  grid.className = 'form-grid';
  grid.style.cssText = 'grid-template-columns:1fr 1fr;gap:10px';

  function makeField(lbl, selName, opts, addOnChange) {
    var ff = document.createElement('div'); ff.className = 'form-field';
    var la = document.createElement('label'); la.style.fontSize = '.76rem'; la.textContent = lbl;
    var sel = document.createElement('select'); sel.name = selName;
    opts.forEach(function(o) {
      var op = document.createElement('option');
      if (typeof o === 'object') { op.value = o.v; op.textContent = o.t; }
      else op.textContent = o;
      sel.appendChild(op);
    });
    if (addOnChange) sel.addEventListener('change', function() { toggleMCOptions(this); });
    ff.appendChild(la); ff.appendChild(sel);
    return ff;
  }
  grid.appendChild(makeField('Answer type', 'q_type[]', ['Yes / No','Short text','Multiple choice'], true));
  grid.appendChild(makeField('Required?',   'q_required[]', [{v:'1',t:'Required'},{v:'0',t:'Optional'}], false));

  var mcWrap = document.createElement('div');
  mcWrap.className = 'mc-options-wrap';
  mcWrap.innerHTML =
    '<p class="mc-options-label">Answer options</p>' +
    '<div class="mc-option-rows">' +
      buildMCOptionRow('Option 1') +
      buildMCOptionRow('Option 2') +
    '</div>' +
    '<button type="button" class="mc-add-opt" onclick="addMCOption(this)">' +
      '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>' +
      ' Add option</button>';

  div.appendChild(hdr);
  div.appendChild(grid);
  div.appendChild(mcWrap);
  list.appendChild(div);

  var typeSelect = grid.querySelector('select[name="q_type[]"]');
  if (typeSelect) typeSelect.addEventListener('change', function() { toggleMCOptions(this); });
  mcWrap.querySelectorAll('.mc-remove-opt').forEach(function(btn) {
    btn.addEventListener('click', function() { removeMCOption(btn); });
  });
}
function removeQuestion(btn) {
  btn.closest('.question-item').remove();
}

function buildMCOptionRow(placeholder) {
  return '<div class="mc-option-row">' +
    '<input type="text" name="q_options[]" placeholder="' + placeholder + '" aria-label="' + placeholder + '">' +
    '<button type="button" class="mc-remove-opt" onclick="removeMCOption(this)" aria-label="Remove option">' +
      '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>' +
    '</button></div>';
}

function toggleMCOptions(select) {
  var wrap = select.closest('.question-item').querySelector('.mc-options-wrap');
  if (!wrap) return;
  var isMultiple = select.value === 'Multiple choice' || select.value === 'multiple_choice';
  wrap.classList.toggle('visible', isMultiple);
}

function addMCOption(btn) {
  var rows = btn.previousElementSibling;
  if (rows.children.length >= 6) return;
  var n = rows.children.length + 1;
  rows.insertAdjacentHTML('beforeend', buildMCOptionRow('Option ' + n));
  rows.lastElementChild.querySelector('input').focus();
}

function removeMCOption(btn) {
  var rows = btn.closest('.mc-option-rows');
  if (!rows || rows.children.length <= 2) return;
  btn.closest('.mc-option-row').remove();
}

/* ── Urgently Hiring — plan required ── */
function handleUrgentToggle(cb) {
  if (!cb.checked) return;
  var hasPlan = (cb.dataset.hasPlan === '1');
  if (!hasPlan) {
    cb.checked = false;
    if (confirm('Urgently Hiring requires an active plan.\n\nWould you like to view our plans?')) {
      window.location.href = '<?= base_url("employer/pricing?ref=urgent-hiring") ?>';
    }
  }
}

/* ── Featured listing — pricing redirect ── */
function handleFeaturedToggle(cb) {
  if (!cb.checked) return;
  var hasPlan = (cb.dataset.hasPlan === '1');
  if (!hasPlan) {
    cb.checked = false;
    if (confirm('Featured Listing requires an active plan.\n\nWould you like to view our plans?')) {
      window.location.href = '<?= base_url("employer/pricing?ref=featured-listing") ?>';
    }
  }
}

/* ── Anonymous posting ── */
function handleAnonToggle(cb) {
  var note = document.getElementById('anon-note');
  if (note) note.style.display = cb.checked ? 'flex' : 'none';
}

/* ── Form submit ── */
function publishJob(e) {
  var title = document.getElementById('job-title');
  if (!title || !title.value.trim()) { 
    e.preventDefault();
    title.focus(); 
    alert('Please enter a job title.'); 
    return; 
  }
  document.getElementById('post-job-form').submit();
}

function saveDraft() {
  var form = document.getElementById('post-job-form');
  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'status';
  input.value = 'draft';
  form.appendChild(input);
  form.submit();
}

/* ── Hybrid days conditional field ── */
function toggleHybridDays(value) {
  var wrap = document.getElementById('hybrid-days-wrap');
  if (wrap) wrap.style.display = value === 'hybrid' || value === 'Hybrid' ? '' : 'none';
  if (value !== 'hybrid' && value !== 'Hybrid') {
    var sel = document.getElementById('hybrid-days');
    if (sel) sel.selectedIndex = 0;
  }
}

/* ── Age bracket custom field ── */
function toggleAgeCustom(value) {
  var wrap = document.getElementById('age-custom-wrap');
  if (wrap) wrap.style.display = value === 'custom' ? '' : 'none';
}

/* ── Industry → Job Category filter ── */
var ALL_CATEGORIES = [
  <?php foreach ($categories as $cat): ?>
    {
      id: "<?= is_object($cat) ? $cat->id : $cat['id'] ?>",
      name: "<?= esc(is_object($cat) ? $cat->name : $cat['name']) ?>",
      industry_id: "<?= is_object($cat) ? ($cat->industry_id ?? '') : ($cat['industry_id'] ?? '') ?>"
    },
  <?php endforeach; ?>
];

function updateJobCategories(industryId) {
  var sel = document.getElementById('job-category');
  if (!sel) return;
  var prev = sel.value;
  sel.innerHTML = '<option value="">Select category</option>';
  
  var filtered = ALL_CATEGORIES.filter(function(cat) {
    return !industryId || !cat.industry_id || cat.industry_id == industryId;
  });

  filtered.forEach(function(cat) {
    var opt = document.createElement('option');
    opt.value = cat.id; opt.textContent = cat.name;
    if (cat.id == prev) opt.selected = true;
    sel.appendChild(opt);
  });
}

/* ── Publish bar: auto-hide on scroll down (mobile) ── */
(function() {
  var bar       = document.querySelector('.publish-bar');
  var lastY     = 0;
  var threshold = 40;
  var isMobile  = function() { return window.innerWidth <= 640; };

  function update() {
    if (!bar || !isMobile()) { bar && bar.classList.remove('bar-hidden'); return; }
    var y          = window.scrollY;
    var pageBottom = document.documentElement.scrollHeight - window.innerHeight;
    var nearBottom = pageBottom - y < 200;

    if (nearBottom) {
      bar.classList.remove('bar-hidden');
    } else if (y > lastY + threshold) {
      bar.classList.add('bar-hidden');
    } else if (y < lastY - threshold) {
      bar.classList.remove('bar-hidden');
    }
    lastY = y;
  }

  window.addEventListener('scroll', update, { passive: true });
  window.addEventListener('resize', update, { passive: true });
})();

document.addEventListener('DOMContentLoaded', function() {
  var checked = document.querySelector('input[name="application_method"]:checked');
  if (checked) toggleMethodDetail(checked.value);
  
  // Initialize existing skills from input value
  var skillHidden = document.getElementById('skills-hidden');
  if (skillHidden && skillHidden.value) {
    var existing = skillHidden.value.split(',');
    existing.forEach(function(s) {
      if (s.trim()) addSkill(s.trim());
    });
  }

  // Set initial age custom view
  var ageBracket = document.getElementById('age-bracket');
  if (ageBracket) toggleAgeCustom(ageBracket.value);

  // Set initial hybrid days view
  var workStyle = document.getElementById('work-style');
  if (workStyle) toggleHybridDays(workStyle.value);
});
</script>
<?= $this->endSection() ?>
