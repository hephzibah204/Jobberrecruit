<style>
/* Centralized AI reply styling shared across builder and resume templates */

/* UI classes (builder only, harmless for Dompdf) */
.ai-preview-modal .modal-content { border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0; }
.ai-preview-render { padding: 18px 20px; background: #ffffff; color: #0f172a; max-height: 62vh; overflow-y: auto; }
.coach-apply-btn { cursor: pointer; transition: all 0.15s ease; }

/* Cross-template AI card for the builder UI */
.ai-card { background: #ffffff; border: 1px solid #e2e8f0; padding: 14px 16px; border-radius: 10px; margin-bottom: 12px; }
.ai-skill-badges { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }

/* ══════════ AI COACH ADVISORY CARDS & APPLY SUGGESTION ══════════ */
.coach-advice-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: 4px solid var(--brand, #0861A9);
    border-radius: 10px;
    padding: 14px 16px;
    margin: 8px 0;
    color: #1e293b;
    font-size: 0.84rem;
    line-height: 1.55;
    box-shadow: 0 2px 8px rgba(10, 47, 87, 0.04);
}
.coach-advice-card p {
    margin-bottom: 8px;
}
.coach-advice-card blockquote {
    background: #f8fafc;
    border-left: 3px solid var(--accent, #ED9020);
    padding: 8px 12px;
    margin: 8px 0 10px;
    border-radius: 4px;
    font-style: italic;
    color: #0f172a;
    font-size: 0.85rem;
}
.coach-advice-card .advice-step-tag {
    display: inline-block;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 2px 6px;
    border-radius: 4px;
    margin-right: 4px;
}
.advice-step-tag.issue { background: #fee2e2; color: #dc2626; }
.advice-step-tag.rec { background: #fef3c7; color: #d97706; }
.advice-step-tag.prop { background: #dcfce7; color: #16a34a; }

.coach-review-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.coach-review-card:hover {
    box-shadow: 0 4px 12px rgba(10, 47, 87, 0.08);
}
.coach-review-card .issue-badge {
    font-size: 0.82rem;
    font-weight: 600;
}
.coach-review-card .rec-text {
    font-size: 0.8rem;
    color: #475569;
}
.coach-review-card .proposed-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 0.82rem;
    color: #0f172a;
}

/* ══════════ AI TAILOR COMPARISON MODAL CARDS ══════════ */
.tailor-diff-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
}
.tailor-diff-card .diff-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    padding-bottom: 6px;
    border-bottom: 1px solid #f1f5f9;
}
.tailor-diff-card .diff-section-title {
    font-size: 0.86rem;
    font-weight: 700;
    color: var(--brand-deep, #0A2F57);
}
.tailor-diff-card .diff-body {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    font-size: 0.8rem;
}
@media (max-width: 768px) {
    .tailor-diff-card .diff-body { grid-template-columns: 1fr; }
}
.tailor-diff-card .diff-col {
    padding: 8px 10px;
    border-radius: 6px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}
.tailor-diff-card .diff-col.proposed {
    background: #f0fdf4;
    border-color: #bbf7d0;
}
.tailor-diff-card .diff-col-label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 4px;
    color: #64748b;
}
.tailor-diff-card .diff-col.proposed .diff-col-label {
    color: #16a34a;
}

/* Dompdf-friendly global rules for AI HTML fragments embedded in resumes */
img { max-width: 100%; height: auto; }
table { width: 100%; border-collapse: collapse; }
td, th { padding: 4px 6px; vertical-align: top; }

/* Prevent page breaks inside summary, experience items, and tables */
.premium-summary, .experience-item, .exp-item, .edu-item, .education-item { page-break-inside: avoid; }

/* Print-specific overrides for Dompdf */
@media print {
    .ai-preview-modal, .ai-preview-render, .coach-apply-btn, .coach-advice-card, .coach-review-card, .tailor-diff-card { display: none; }
    .ai-card { border: 1px solid #e2e8f0; background: #fafafa; }
}

/* ══════════ RECRUITER VIEW (from candidate-AI Resume Builder.html) ══════════ */
.scan-box {
    border: 1.5px dashed var(--accent, #ED9020);
    border-radius: 10px;
    background: var(--accent-light, #FDF1E0);
    padding: 12px 14px;
    margin-top: 10px;
}
.scan-box .sb-t {
    font-size: .64rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--accent-dark, #C8770E);
    margin-bottom: 7px;
}
.scan-box b.nm {
    font-family: 'Sora', sans-serif;
    font-size: .94rem;
    color: var(--brand-deep, #0A2F57);
    display: block;
}
.scan-box p {
    font-size: .74rem;
    color: var(--text, #1e293b);
    margin: 3px 0 0 0;
}
.rev-verdict {
    background: linear-gradient(135deg, var(--brand-light, #E6F0F8), #ffffff);
    border: 1px solid var(--border, #e2e8f0);
    border-left: 3px solid var(--brand, #0861A9);
    border-radius: 12px;
    padding: 15px 17px;
    margin-top: 12px;
}
.rev-head {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 10px;
}
.rev-av {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: var(--brand, #0861A9);
    color: #ffffff;
    font-family: 'Sora', sans-serif;
    font-weight: 800;
    font-size: .74rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: .02em;
}
.rev-head b {
    display: block;
    font-size: .82rem;
    color: var(--brand-deep, #0A2F57);
    font-family: 'Sora', sans-serif;
}
.rev-head i {
    font-style: normal;
    font-size: .68rem;
    color: var(--muted, #64748b);
}
.rev-body {
    font-size: .83rem;
    line-height: 1.6;
    color: var(--text, #1e293b);
    font-style: italic;
    margin: 0;
}
.readiness {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: 'Sora', sans-serif;
    font-weight: 800;
    font-size: .8rem;
    padding: 6px 13px;
    border-radius: 20px;
    margin-top: 12px;
}
.readiness.strong {
    background: #e6f7ee;
    color: #1f9d55;
}
.readiness.moderate {
    background: var(--accent-light, #FDF1E0);
    color: var(--accent-dark, #C8770E);
}
.readiness.weak {
    background: #fde8e8;
    color: #c0392b;
}
.rc-group {
    margin-top: 12px;
}
.rc-gt {
    font-size: .66rem;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: 6px;
}
.rc-line {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    font-size: .76rem;
    color: var(--text, #1e293b);
    margin-bottom: 5px;
    line-height: 1.45;
}
.rc-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 6px;
}
.rc-fix-btn {
    margin-left: auto;
    border: none;
    background: none;
    color: var(--brand, #0861A9);
    font-weight: 700;
    font-size: .7rem;
    cursor: pointer;
    padding: 0 4px;
    white-space: nowrap;
}
.rc-fix-btn:hover {
    text-decoration: underline;
}
.rc-note {
    font-size: .7rem;
    color: var(--muted, #64748b);
    margin-top: 12px;
    line-height: 1.5;
}
</style>
