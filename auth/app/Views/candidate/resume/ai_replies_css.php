<style>
/* Centralized AI reply styling shared across builder and resume templates */

/* UI classes (builder only, harmless for Dompdf) */
.ai-preview-modal .modal-content { border-radius: 12px; overflow: hidden; }
.ai-preview-render { padding: 16px; background: #ffffff; color: #0f172a; max-height: 60vh; overflow:auto; }
.coach-apply-btn { cursor:pointer; }

/* Cross-template AI card for the builder UI */
.ai-card { background: #ffffff; background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border: 1px solid rgba(0,0,0,0.04); padding: 10px 12px; border-radius: 8px; }

.ai-skill-badges { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }

/* Dompdf-friendly global rules for AI HTML fragments embedded in resumes */
img { max-width: 100%; height: auto; }
table { width: 100%; border-collapse: collapse; }
td, th { padding: 4px 6px; vertical-align: top; }

/* Prevent page breaks inside summary, experience items, and tables */
.premium-summary, .experience-item, .exp-item, .edu-item, .education-item { page-break-inside: avoid; }

/* Print-specific overrides for Dompdf */
@media print {
    .ai-preview-modal, .ai-preview-render, .coach-apply-btn { display: none; }
    .ai-card { border: 1px solid #e2e8f0; background: #fafafa; }
}
</style>
