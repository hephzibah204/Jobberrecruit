<?php $page_title = 'AI Resume Builder'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --primary-color: #0D609E;
        --primary-color-dark: var(--brand-dark);
        --accent-color: var(--accent);
        --brand:#0861A9;
        --brand-light:#E6F0F8;
        --accent:#ED9020;
        --accent-dark:#C8770E;
        --accent-light:#FDF1E0;
        --success:#16a34a;
        --success-light:#e8f7ee;
        --danger:#dc2626;
        --danger-light:#fdeaea;
        --border:#e2e8f2;
    }

    /* Side-by-side design layout & controls */
    .design-bar {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 10px 14px;
        margin-bottom: 16px;
    }
    .design-bar .lbl {
        margin: 0;
        white-space: nowrap;
    }
    .dens {
        display: flex;
        gap: 4px;
        background: #f5f7fb;
        border: 1px solid var(--border);
        border-radius: 9px;
        padding: 3px;
    }
    .dens button {
        border: none;
        background: transparent;
        font-size: .72rem;
        font-weight: 600;
        color: #5b6577;
        padding: 6px 12px;
        border-radius: 7px;
        cursor: pointer;
        min-height: 32px;
        transition: all 0.2s;
    }
    .dens button.on {
        background: #fff;
        color: var(--brand);
        box-shadow: 0 2px 14px rgba(10,47,87,.08);
    }
    .wm-note {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .68rem;
        font-weight: 600;
        color: #5b6577;
        margin-left: auto;
        white-space: nowrap;
    }
    .wm-note svg {
        width: 13px;
        height: 13px;
        color: var(--brand);
    }

    /* Score Card & Gauge */
    .score-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 15px;
    }
    .score-top {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .gauge {
        position: relative;
        width: 74px;
        height: 74px;
        flex-shrink: 0;
    }
    .gauge svg {
        width: 74px;
        height: 74px;
        transform: rotate(-90deg);
    }
    .gauge circle.t {
        fill: none;
        stroke: #f5f7fb;
        stroke-width: 8;
    }
    .gauge circle.p {
        fill: none;
        stroke: var(--success);
        stroke-width: 8;
        stroke-linecap: round;
        stroke-dasharray: 207;
        transition: stroke-dashoffset .5s ease, stroke .3s;
    }
    .gauge b {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Sora', sans-serif;
        font-weight: 800;
        font-size: 1rem;
        color: #0A2F57;
    }
    .score-info b {
        font-size: .86rem;
        color: #0A2F57;
        display: block;
    }
    .score-info p {
        font-size: .72rem;
        color: #5b6577;
    }
    .score-list {
        list-style: none;
        margin-top: 12px;
        padding-left: 0;
        display: flex;
        flex-direction: column;
        gap: 7px;
    }
    .score-list li {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .76rem;
        color: #141926;
    }
    .score-list li i {
        font-size: 14px;
        flex-shrink: 0;
    }
    .score-list li.ok i {
        color: var(--success);
    }
    .score-list li.no i {
        color: var(--accent-dark);
    }
    .score-list button {
        margin-left: auto;
        border: none;
        background: none;
        color: var(--brand);
        font-weight: 700;
        font-size: .7rem;
        cursor: pointer;
        padding: 4px;
        white-space: nowrap;
    }
    .score-list button:hover {
        text-decoration: underline;
    }

    /* Live Preview Document shell styling */
    .pv-shell {
        background: #e8ecf3;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
        max-height: calc(100vh - 170px);
        overflow-y: auto;
    }
    .doc {
        background: #fff;
        max-width: 100%;
        margin: 0 auto;
        box-shadow: 0 8px 30px rgba(10,47,87,.14);
        padding: 30px;
        font-size: .84rem;
        line-height: 1.62;
        color: #212836;
        position: relative;
        min-height: 842px; /* standard proportion check */
        transition: font-size 0.2s, line-height 0.2s, padding 0.2s;
    }
    .doc h1 {
        font-size: 1.95rem;
        letter-spacing: -.02em;
        line-height: 1.1;
        font-weight: 800;
        margin-bottom: 4px;
    }
    .doc .d-title {
        font-size: .8rem;
        font-weight: 600;
        color: #4b5568;
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: .14em;
    }
    .doc .d-contact {
        font-size: .72rem;
        color: #4b5568;
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }
    .doc .d-contact span {
        display: inline-flex;
        align-items: center;
    }
    .doc .d-contact span + span::before {
        content: '';
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #b7c1cf;
        margin: 0 10px;
        display: inline-block;
    }
    .doc .d-head {
        padding-bottom: 18px;
        margin-bottom: 4px;
    }
    .doc .d-sec {
        margin-top: 22px;
    }
    .doc .d-sec h2 {
        font-size: .72rem;
        letter-spacing: .16em;
        text-transform: uppercase;
        font-weight: 700;
        padding-bottom: 6px;
        margin-bottom: 11px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .doc .d-xp {
        margin-bottom: 14px;
    }
    .doc .d-xp-h {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 12px;
        flex-wrap: wrap;
    }
    .doc .d-xp-h b {
        font-size: .9rem;
        font-weight: 700;
    }
    .doc .d-xp-h i {
        font-style: normal;
        font-size: .7rem;
        font-weight: 600;
        color: #5b6577;
        white-space: nowrap;
        letter-spacing: .03em;
    }
    .doc .d-xp p.co {
        font-size: .76rem;
        font-weight: 500;
        color: #5b6577;
        margin: 1px 0 6px;
    }
    .doc ul {
        padding-left: 17px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-top: 4px;
        margin-bottom: 8px;
    }
    .doc ul li {
        padding-left: 2px;
    }
    .doc .d-skills {
        display: flex;
        gap: 7px 8px;
        flex-wrap: wrap;
        list-style: none;
        padding: 0;
        margin: 4px 0 0;
    }
    .doc .d-skills li {
        font-size: .71rem;
        font-weight: 600;
        border: 1px solid #dbe2ec;
        border-radius: 4px;
        padding: 3px 10px;
        color: #33415c;
        background: #f8fafd;
    }

    /* Metric progress bar styles */
    .met {
        display: flex;
        flex-direction: column;
    }
    .met .met-h {
        display: flex;
        justify-content: space-between;
        font-size: .68rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .met .met-h b {
        color: #0f172a;
        font-family: 'Sora', sans-serif;
    }
    .met .met-t {
        height: 5px;
        border-radius: 20px;
        background: #edf2f7;
        overflow: hidden;
        margin-top: 4px;
    }
    .met .met-f {
        height: 100%;
        border-radius: 20px;
        background: var(--primary-color, #0861a9);
        transition: width .4s ease;
    }
    .met.warn .met-f {
        background: #c8770e;
    }
    .met.bad .met-f {
        background: #ef4444;
    }

    /* ── Watermark: vertical right-margin brand mark ── */
    .doc { position: relative; }
    .doc .wm {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 7px;
        opacity: .5;
        pointer-events: none;
        user-select: none;
    }
    .doc .wm .wm-ic { width: 12px; height: 16px; flex-shrink: 0; }
    .doc .wm .wm-tx {
        writing-mode: vertical-rl;
        font-family: 'Inter', sans-serif;
        font-size: .56rem;
        font-weight: 600;
        letter-spacing: .16em;
        color: #9aa4b5;
        line-height: 1;
    }
    /* Diagonal tile watermark (anti-copy visual signal on preview) */
    .doc.wm-tile {
        background-image: repeating-linear-gradient(
            -30deg,
            transparent 0 78px,
            rgba(8,97,169,.028) 78px 79px
        );
    }
    /* A4 page-break guide line (preview only) */
    .doc.guides:not(.onepage) {
        background-image: repeating-linear-gradient(
            to bottom,
            transparent 0,
            transparent 1074px,
            #f0b35c 1074px,
            #f0b35c 1075px
        );
    }
    @media (max-width:1024px) { .doc.guides { background-image: none !important; } }
    /* Hide page-break guides in print — browser renders real breaks */
    @media print { .doc.guides { background-image: none; } }

    /* ── @media print — full print architecture ── */
    @media print {
        html { font-size: 13pt; }
        body { background: #fff !important; }
        body > * { display: none !important; }
        #print-root { display: block !important; }
        #print-root .doc {
            position: static;
            width: 100%;
            max-width: none;
            box-shadow: none;
            margin: 0;
            padding: 0 9mm 0 0;
            font-size: .84rem;
            line-height: 1.5;
        }
        #print-root .doc.t-exec  { display: grid; padding: 0 9mm 0 0; }
        #print-root .doc.guides  { background-image: none; }
        #print-root .doc .wm     { position: fixed; right: 3.5mm; top: 50%; transform: translateY(-50%); }
        .doc.t-modern::before    { display: block; top: 0; height: 6px; }
        #print-root .doc.t-modern { padding-top: 14px; }
    }

    /* Template: Modern */
    .doc.t-modern {
        font-family: 'Inter', sans-serif;
    }
    .doc.t-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 7px;
        background: linear-gradient(90deg, var(--acc, #0861A9) 45%, var(--acc2, #ED9020));
    }
    .doc.t-modern h1 {
        font-family: 'Sora', sans-serif;
        color: #141c2b;
    }
    .doc.t-modern .d-title {
        color: var(--acc, #0861A9);
    }
    .doc.t-modern .d-head {
        border-bottom: 1px solid #e6ebf2;
    }
    .doc.t-modern .d-sec h2 {
        color: #141c2b;
        border: none;
        border-bottom: 2px solid var(--acc, #0861A9);
        padding-bottom: 3px;
    }

    /* Template: Classic */
    .doc.t-classic, .doc.t-classic h1, .doc.t-classic h2 {
        font-family: Georgia, 'Times New Roman', serif;
    }
    .doc.t-classic .d-head {
        text-align: center;
        border-bottom: 3px double #c8d1dd;
    }
    .doc.t-classic h1 {
        color: var(--acc, #0861A9);
        font-weight: 700;
        letter-spacing: .02em;
        font-size: 2.15rem;
        font-variant: small-caps;
    }
    .doc.t-classic .d-title {
        letter-spacing: .22em;
    }
    .doc.t-classic .d-contact {
        justify-content: center;
    }
    .doc.t-classic .d-sec h2 {
        color: var(--acc, #0861A9);
        border-bottom: none;
        letter-spacing: .24em;
        font-weight: 700;
        justify-content: center;
        font-variant: small-caps;
        font-size: .8rem;
        position: relative;
        padding-bottom: 9px;
    }
    .doc.t-classic .d-sec h2::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: 0;
        width: 46px;
        height: 2px;
        background: var(--acc, #0861A9);
        transform: translateX(-50%);
    }
    .doc.t-classic .d-xp-h b {
        font-style: italic;
        font-weight: 700;
    }
    .doc.t-classic .d-skills {
        gap: 0;
        display: block;
    }
    .doc.t-classic .d-skills li {
        display: inline;
        border: none;
        background: none;
        padding: 0;
        font-size: .8rem;
        font-weight: 400;
        color: #212836;
    }
    .doc.t-classic .d-skills li + li::before {
        content: '  ·  ';
    }

    /* Template: Minimal */
    .doc.t-minimal {
        font-family: 'Inter', sans-serif;
    }
    .doc.t-minimal h1 {
        font-weight: 700;
        color: #171d29;
        letter-spacing: -.03em;
        font-size: 2.05rem;
    }
    .doc.t-minimal .d-head {
        border-bottom: none;
    }
    .doc.t-minimal .d-title {
        color: #6b7688;
    }
    .doc.t-minimal h1 {
        border-bottom: 2px solid var(--acc, #0861A9);
        padding-bottom: 6px;
        display: inline-block;
    }
    .doc.t-minimal .d-sec h2 {
        color: #8a94a6;
        border-bottom: 1px solid #edf0f5;
        font-weight: 700;
        letter-spacing: .2em;
    }
    .doc.t-minimal .d-skills li {
        border: none;
        background: #f3f5f9;
        border-radius: 3px;
    }

    /* Template: Executive */
    .doc.t-exec {
        display: grid;
        grid-template-columns: 32% 68%;
        gap: 0;
        padding: 0;
        overflow: hidden;
    }
    .doc.t-exec .d-head {
        grid-column: 1/-1;
        padding: 30px 30px 18px;
        border-bottom: 3px solid var(--acc, #0861A9);
    }
    .doc.t-exec h1 {
        font-family: 'Sora', sans-serif;
        color: #141c2b;
        font-size: 1.9rem;
    }
    .doc.t-exec .d-title {
        color: var(--acc, #0861A9);
        font-weight: 600;
    }
    .doc.t-exec .exec-side {
        background: #f4f7fb;
        padding: 20px;
    }
    .doc.t-exec .exec-main {
        padding: 20px;
    }
    .doc.t-exec .d-sec {
        margin-top: 0;
        margin-bottom: 18px;
    }
    .doc.t-exec .d-sec h2 {
        font-size: .66rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--acc, #0861A9);
        border: none;
        border-left: 3px solid var(--acc, #0861A9);
        padding: 1px 0 1px 9px;
        margin-bottom: 9px;
    }
    .doc.t-exec .exec-side .d-skills {
        flex-direction: column;
        gap: 6px;
    }
    .doc.t-exec .exec-side .d-skills li {
        width: 100%;
        background: #fff;
        border-color: #dde5ef;
    }

    /* Template: Creative */
    .doc.t-creative {
        font-family: 'Inter', sans-serif;
        padding: 0;
    }
    .doc.t-creative .d-head {
        background: linear-gradient(135deg, #4c1d95, #6d28d9, #7c3aed);
        padding: 26px 30px 22px 30px;
        color: #ffffff !important;
        border-bottom: none;
        margin: 0;
    }
    .doc.t-creative .d-head h1 {
        color: #ffffff !important;
        font-family: 'Sora', sans-serif;
        font-weight: 800;
        margin: 0;
        font-size: 2rem;
    }
    .doc.t-creative .d-head .d-title {
        color: #c4b5fd !important;
        font-weight: 500;
        margin-top: 2px;
        font-size: .95rem;
    }
    .doc.t-creative .d-head .d-contact {
        color: #ddd6fe !important;
        margin-top: 10px;
        display: flex;
        gap: 12px;
    }
    .doc.t-creative .d-head .d-contact span {
        color: #ddd6fe !important;
    }
    .doc.t-creative .d-sec {
        padding: 0 30px;
    }
    .doc.t-creative .d-sec h2 {
        color: #4c1d95;
        border-bottom: 2.5px solid #7c3aed;
        padding-bottom: 3px;
        margin-bottom: 12px;
    }

    /* Spacing & Tile effects */
    .doc.spacing-tight {
        font-size: .72rem;
        line-height: 1.34;
        padding: 20px;
    }
    .doc.spacing-tight h1 { font-size: 1.7rem; }
    .doc.spacing-tight .d-title { font-size: .72rem; margin-top: 1px; }
    .doc.spacing-tight .d-contact { margin-top: 5px; }
    .doc.spacing-tight .d-head { padding-bottom: 7px; }
    .doc.spacing-tight .d-sec { margin-top: 8px; }
    .doc.spacing-tight .d-sec h2 { font-size: .68rem; padding-bottom: 3px; margin-bottom: 5px; }
    .doc.spacing-tight .d-xp { margin-bottom: 5px; }
    .doc.spacing-tight .d-xp-h b { font-size: .8rem; }
    .doc.spacing-tight ul { gap: 1px; }
    .doc.spacing-tight .d-skills li { padding: 1.5px 7px; font-size: .68rem; }

    .doc.spacing-roomy {
        font-size: .84rem;
        line-height: 1.62;
        padding: 30px;
    }

    /* Watermark vertical right column styling */
    .doc .wm {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 7px;
        opacity: .5;
        pointer-events: none;
        user-select: none;
    }
    .doc .wm .wm-ic {
        width: 12px;
        height: 16px;
        flex-shrink: 0;
        fill: #F08F1C;
    }
    .doc .wm .wm-tx {
        writing-mode: vertical-rl;
        font-family: 'Inter', sans-serif;
        font-size: .56rem;
        font-weight: 600;
        letter-spacing: .16em;
        color: #9aa4b5;
        line-height: 1;
    }
    .doc.wm-tile {
        background-image: repeating-linear-gradient(-30deg, transparent 0 78px, rgba(8,97,169,.028) 78px 79px);
    }

    /* Pagebreaks */
    .doc.guides {
        background-image: repeating-linear-gradient(to bottom, transparent 0, transparent 1074px, #f0b35c 1074px, #f0b35c 1075px);
    }
    .pv-hint {
        font-size: .68rem;
        color: var(--muted);
        text-align: center;
        margin-top: 9px;
    }
    .pv-hint b {
        color: var(--accent-dark);
    }

    .builder-step-nav {
        border-right: 1px solid #e9ecef;
    }
    .step-item {
        padding: 12px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        font-weight: 500;
    }
    .step-item:hover {
        background-color: #f8f9fa;
        color: var(--primary-color);
    }
    .step-item.active {
        background-color: var(--primary-color);
        color: white;
    }
    .step-item i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    .ai-assist-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color) 100%);
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .ai-assist-btn:hover {
        transform: scale(1.05);
        color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .ai-assist-btn i {
        margin-right: 5px;
    }
    
    .template-choice {
        transition: all 0.25s ease-in-out;
        background-color: #fff;
    }
    .template-choice:hover {
        transform: translateY(-4px);
        border-color: var(--primary-color) !important;
        box-shadow: 0 6px 15px rgba(13, 96, 158, 0.12) !important;
    }
    .template-choice.active {
        border-color: var(--primary-color) !important;
        border-width: 2px !important;
        box-shadow: 0 6px 15px rgba(13, 96, 158, 0.18) !important;
        background-color: #f8fafc;
    }
    .template-choice.active h6 {
        color: var(--primary-color);
    }
    
    .template-preview {
        box-shadow: inset 0 0 8px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .template-choice:hover .template-preview {
        border-color: #cbd5e1 !important;
    }
    
    @media (min-width: 1200px) {
        .col-xl-2-4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    .download-pdf-btn, .download-docx-btn {
        transition: all 0.2s ease-in-out !important;
    }
    .download-pdf-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 24px rgba(220, 53, 69, 0.25) !important;
    }
    .download-docx-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 24px rgba(13, 96, 158, 0.25) !important;
    }

    .ai-coach-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1050;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color-dark) 100%);
        border: none;
        box-shadow: 0 10px 25px rgba(13, 96, 158, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .ai-coach-fab:hover {
        transform: scale(1.1) translateY(-3px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        background: linear-gradient(135deg, var(--primary-color-dark) 0%, var(--brand-deep) 100%);
    }
    .ai-coach-fab i {
        font-size: 1.6rem;
    }
    .ai-coach-fab .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 3px solid rgba(13, 96, 158, 0.5);
        animation: fab-pulse 2s infinite;
    }
    @keyframes fab-pulse {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 0; }
        100% { transform: scale(0.95); opacity: 0; }
    }

    .custom-coach-offcanvas {
        width: 480px !important;
        background-color: #0f172a;
        color: #f8fafc;
        border-left: 1px solid #1e293b;
        box-shadow: -10px 0 30px rgba(0,0,0,0.25);
    }
    .custom-coach-offcanvas .offcanvas-header {
        background-color: #1e293b;
        border-bottom: 1px solid #334155;
        padding: 1.25rem 1.5rem;
    }
    .custom-coach-offcanvas .offcanvas-title {
        color: #f8fafc;
        font-weight: 700;
    }
    .custom-coach-offcanvas .btn-close {
        filter: invert(1) grayscale(1) brightness(2);
    }
    .coach-chat-container {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .coach-messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        background-color: #0f172a;
    }
    .coach-bubble {
        max-width: 85%;
        padding: 12px 16px;
        border-radius: 16px;
        line-height: 1.5;
        font-size: 0.9rem;
    }
    .coach-bubble.coach {
        background-color: #1e293b;
        background-color: #1e293b;
        border-top-left-radius: 4px;
        color: #cbd5e1;
        align-self: flex-start;
        border: 1px solid #334155;
    }
    /* AI reply card styles for richer HTML returned by the model */
    .coach-bubble.coach .ai-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        border: 1px solid rgba(255,255,255,0.04);
        padding: 12px 14px;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(2,6,23,0.25);
        color: #e6eef8;
    }
    .coach-bubble.coach .ai-card h3 {
        margin: 0 0 6px 0;
        color: var(--primary-color, #0D609E);
        font-size: 1rem;
    }
    .coach-bubble.coach .ai-card p { color: #cbd5e1; margin:0 0 8px 0; }
    .coach-bubble.coach .ai-card ul { padding-left:16px; margin:6px 0; }
    .coach-bubble.coach .ai-skill-badges { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
    .coach-bubble.coach .ai-skill-badges .badge {
        background: rgba(255,255,255,0.04);
        color: #e6eef8;
        padding:4px 8px; border-radius:999px; font-size:0.78rem; border: 1px solid rgba(255,255,255,0.03);
    }
    .coach-bubble.user {
        background: linear-gradient(135deg, #0d609e 0%, #0d609e 100%);
        color: white;
        border-top-right-radius: 4px;
        align-self: flex-end;
    }
    .coach-bubble h1, .coach-bubble h2, .coach-bubble h3 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .coach-bubble ul, .coach-bubble ol {
        padding-left: 1.2rem;
        margin-bottom: 0.5rem;
    }
    .coach-bubble li {
        margin-bottom: 0.25rem;
    }
    .coach-bubble strong {
        color: #818cf8;
        font-weight: 600;
    }
    .coach-input-area {
        background-color: #1e293b;
        border-top: 1px solid #334155;
        padding: 1.25rem;
    }
    .coach-input-group {
        background-color: #0f172a;
        border: 1px solid #334155;
        border-radius: 30px;
        padding: 6px 12px;
        display: flex;
        align-items: center;
    }
    .coach-input-field {
        flex: 1;
        background: transparent;
        border: none;
        color: #f8fafc;
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    .coach-input-field:focus {
        outline: none;
    }
    .coach-send-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color-dark) 100%);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .coach-send-btn:hover {
        transform: scale(1.08);
        background: linear-gradient(135deg, var(--primary-color-dark) 0%, var(--brand-deep) 100%);
    }
    .coach-apply-btn {
        margin-top: 8px;
        background: rgba(13,96,158,0.08);
        border: 1px dashed rgba(13,96,158,0.5);
        color: var(--primary-color);
        font-size: 0.8rem;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
    }
    .coach-apply-btn:hover {
        background: var(--primary-color);
        color: white;
    }
    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 4px 8px;
    }
    .typing-dot {
        width: 8px;
        height: 8px;
        background-color: #94a3b8;
        border-radius: 50%;
        animation: typing-bounce 1.4s infinite ease-in-out both;
    }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes typing-bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* ═══ MOCKUP: rb-bar (sticky toolbar) ═══ */
    .rb-bar{position:sticky;top:0;z-index:900;display:flex;align-items:center;gap:12px;padding:10px 18px;background:rgba(255,255,255,.92);-webkit-backdrop-filter:saturate(180%) blur(10px);backdrop-filter:saturate(180%) blur(10px);border-bottom:1px solid var(--border);flex-wrap:wrap}
    .rb-bar .rb-title{flex:1;min-width:120px;font-family:'Sora',sans-serif;font-weight:800;font-size:1rem;color:var(--brand-deep);display:flex;align-items:center;gap:8px}
    .rb-bar .rb-title input{flex:1;min-width:100px;border:none;background:transparent;font-family:inherit;font-size:inherit;font-weight:inherit;color:inherit;padding:4px 0;border-bottom:2px solid transparent;transition:border-color .15s}
    .rb-bar .rb-title input:focus{outline:none;border-bottom-color:var(--brand)}
    .rb-bar .rb-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
    .rb-bar .rb-actions select{border:1px solid var(--border);border-radius:8px;padding:6px 10px;font-size:.78rem;font-weight:600;background:#fff;color:var(--text);cursor:pointer}
    .rb-bar .rb-chip{display:inline-flex;align-items:center;gap:5px;font-size:.68rem;font-weight:700;padding:5px 11px;border-radius:20px;background:var(--brand-light);color:var(--brand)}
    .rb-bar .rb-chip.green{background:var(--success-light);color:var(--success)}

    /* ═══ MOCKUP: rb-design-bar (top toolbar — distinct from preview inner design-bar) ═══ */
    .rb-design-bar{display:flex;align-items:center;gap:14px;padding:8px 18px;background:#fafbfe;border-bottom:1px solid var(--border);flex-wrap:wrap}
    .rb-design-bar .db-label{font-size:.66rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);white-space:nowrap}
    .rb-design-bar .swatches{display:flex;gap:6px}
    .rb-design-bar .swatch{width:22px;height:22px;border-radius:50%;border:2px solid transparent;cursor:pointer;transition:transform .15s,border-color .15s}
    .rb-design-bar .swatch:hover{transform:scale(1.2)}
    .rb-design-bar .swatch.active{border-color:var(--brand-deep);box-shadow:0 0 0 2px rgba(10,47,87,.2)}
    .rb-design-bar select{border:1px solid var(--border);border-radius:6px;padding:4px 8px;font-size:.72rem;font-weight:600;background:#fff}
    .rb-design-bar .spacing-btns{display:flex;gap:4px}
    .rb-design-bar .spacing-btn{border:1px solid var(--border);border-radius:6px;padding:4px 10px;font-size:.68rem;font-weight:700;cursor:pointer;background:#fff;color:var(--muted);transition:all .15s}
    .rb-design-bar .spacing-btn.active{background:var(--brand);color:#fff;border-color:var(--brand)}
    .rb-design-bar .verified-chip{display:inline-flex;align-items:center;gap:5px;font-size:.62rem;font-weight:700;padding:4px 10px;border-radius:20px;background:var(--success-light);color:var(--success);margin-left:auto}

    /* ═══ MOCKUP: rb-tabs (mobile edit/preview toggle) ═══ */
    .rb-tabs{display:none;gap:4px;padding:6px 18px;background:#fff;border-bottom:1px solid var(--border)}
    .rb-tabs button{flex:1;border:1px solid var(--border);border-radius:8px;padding:8px;font-size:.78rem;font-weight:700;cursor:pointer;background:#fff;color:var(--muted);transition:all .15s}
    .rb-tabs button.active{background:var(--brand);color:#fff;border-color:var(--brand)}
    @media(max-width:992px){.rb-tabs{display:flex}}

    /* ═══ MOCKUP: rb-split grid ═══ */
    .rb-split{display:grid;grid-template-columns:1fr 1fr;gap:0;align-items:start}
    .rb-editor-col{min-width:0;padding:18px;border-right:1px solid var(--border)}
    .rb-preview-col{min-width:0;padding:18px;position:sticky;top:60px;max-height:calc(100vh - 60px);overflow-y:auto}
    @media(max-width:992px){
      .rb-split{grid-template-columns:1fr}
      .rb-editor-col{display:block}
      .rb-preview-col{display:none;position:static;max-height:none}
      .rb-editor-col.preview-active{display:none}
      .rb-preview-col.show-mobile{display:block}
    }

    /* ═══ MOCKUP: ed-sec (accordion sections) ═══ */
    .ed-sec{border:1px solid var(--border);border-radius:12px;background:#fff;margin-bottom:12px;overflow:hidden;transition:box-shadow .2s}
    .ed-sec:hover{box-shadow:0 2px 8px rgba(10,47,87,.06)}
    .ed-head{display:flex;align-items:center;gap:10px;padding:14px 16px;cursor:pointer;user-select:none;transition:background .15s}
    .ed-head:hover{background:var(--bg)}
    .ed-grip{color:var(--muted);display:flex;align-items:center;cursor:grab;flex-shrink:0}
    .ed-grip svg{width:16px;height:16px}
    .ed-title{flex:1;font-size:.88rem;font-weight:700;color:var(--brand-deep);display:flex;align-items:center;gap:8px}
    .ed-title svg{width:15px;height:15px;color:var(--brand)}
    .ed-title .ed-tag{font-size:.6rem;font-weight:700;padding:2px 8px;border-radius:20px;background:var(--brand-light);color:var(--brand);letter-spacing:.04em}
    .ed-chev{color:var(--muted);flex-shrink:0;transition:transform .2s}
    .ed-chevron svg{width:16px;height:16px}
    .ed-sec.open .ed-chevron svg{transform:rotate(180deg)}
    .ed-body{display:none;padding:0 16px 16px}
    .ed-sec.open .ed-body{display:block;animation:ed-fade .2s ease}
    @keyframes ed-fade{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
    .ed-body .custom-card{border:none;margin:0}
    .ed-body .custom-card .card-header{padding:12px 0;border-bottom-color:var(--border)}
    .ed-body .custom-card .card-body{padding:14px 0}
</style>
<?= $this->include('candidate/resume/ai_replies_css') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ═══════ MOCKUP LAYOUT: rb-bar + design-bar + rb-tabs + rb-split ═══════ -->
<div class="rb-bar">
  <div class="rb-title">
    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></svg>
    <input type="text" value="<?= esc($resume->title ?? 'Untitled Resume') ?>" id="resume-name-input" aria-label="Resume name">
  </div>
  <div class="rb-actions">
    <span class="rb-chip green" id="autosave-chip">✓ Saved</span>
    <span class="rb-chip"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/></svg> 1 page</span>
    <select id="template-select-top" aria-label="Template" onchange="selectTemplate(this.value)">
      <option value="t-classic" selected>Classic</option>
      <option value="t-creative">Creative</option>
      <option value="t-exec">Executive</option>
      <option value="t-minimal">Minimal</option>
      <option value="t-modern">Modern</option>
    </select>
    <a href="#" class="btn btn-sm btn-primary" style="font-size:.76rem" onclick="return false;"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/></svg> Word</a>
    <a href="#" class="btn btn-sm btn-outline download-pdf-btn" style="font-size:.76rem" onclick="return false;"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> PDF</a>
  </div>
</div>

<div class="rb-design-bar">
  <span class="db-label">Design</span>
  <div class="swatches">
    <span class="swatch active" style="background:#0861A9" onclick="setAccentColor('#0861A9',this)"></span>
    <span class="swatch" style="background:#0A2F57" onclick="setAccentColor('#0A2F57',this)"></span>
    <span class="swatch" style="background:#16a34a" onclick="setAccentColor('#16a34a',this)"></span>
    <span class="swatch" style="background:#7c3aed" onclick="setAccentColor('#7c3aed',this)"></span>
    <span class="swatch" style="background:#dc2626" onclick="setAccentColor('#dc2626',this)"></span>
    <span class="swatch" style="background:#ED9020" onclick="setAccentColor('#ED9020',this)"></span>
  </div>
  <select id="font-select" aria-label="Font" onchange="setFontFamily(this.value)" style="font-size:.72rem">
    <option value="Inter">Inter</option>
    <option value="Georgia">Georgia</option>
    <option value="Garamond">Garamond</option>
    <option value="Roboto">Roboto</option>
  </select>
  <div class="spacing-btns">
    <button class="spacing-btn active" onclick="setSpacing('roomy',this)">Roomy</button>
    <button class="spacing-btn" onclick="setSpacing('tight',this)">Tight</button>
  </div>
  <span class="verified-chip"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg> Verified by JobberRecruit</span>
</div>

<div class="rb-tabs" role="tablist">
  <button class="active" onclick="switchMobileTab('edit',this)" role="tab">Edit</button>
  <button onclick="switchMobileTab('preview',this)" role="tab">Preview</button>
</div>

<div class="rb-split">
  <div class="rb-editor-col" id="rb-editor-col">
<?php if (!$resume): ?>
<!-- =================== RESUME ONBOARDING GATEWAY MODAL =================== -->
<style>
    .onboarding-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: linear-gradient(135deg, #0f0c29 0%, #1a1040 40%, #0d1b3e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeInOverlay 0.4s ease forwards;
        overflow-y: auto;
        padding: 2rem 1rem;
    }
    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    .onboarding-card-wrap {
        width: 100%;
        max-width: 960px;
    }
    .onboarding-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .onboarding-header .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(13, 96, 158, 0.15);
        border: 1px solid rgba(13, 96, 158, 0.4);
        color: #a5b4fc;
        padding: 5px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        margin-bottom: 1.25rem;
    }
    .onboarding-header h2 {
        font-size: clamp(1.75rem, 4vw, 2.5rem);
        font-weight: 800;
        color: #f8fafc;
        line-height: 1.2;
        margin-bottom: 0.75rem;
    }
    .onboarding-header h2 span {
        background: linear-gradient(90deg, #818cf8, #c084fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .onboarding-header p {
        color: #94a3b8;
        font-size: 1.05rem;
        max-width: 560px;
        margin: 0 auto;
    }
    .ob-options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
        gap: 1.5rem;
    }
    .ob-option-card {
        background: rgba(255,255,255,0.04);
        border: 1.5px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 2rem 1.75rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: block;
        backdrop-filter: blur(10px);
    }
    .ob-option-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--ob-glow);
        opacity: 0;
        transition: opacity 0.3s;
        border-radius: 20px;
    }
    .ob-option-card:hover::before { opacity: 1; }
    .ob-option-card:hover {
        transform: translateY(-6px) scale(1.02);
        border-color: var(--ob-border);
        box-shadow: 0 20px 60px var(--ob-shadow);
    }
    .ob-option-card.ob-scratch {
        --ob-glow: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(168,85,247,0.06) 100%);
        --ob-border: rgba(99,102,241,0.5);
        --ob-shadow: rgba(99,102,241,0.25);
    }
    .ob-option-card.ob-profile {
        --ob-glow: linear-gradient(135deg, rgba(16,185,129,0.08) 0%, rgba(6,182,212,0.06) 100%);
        --ob-border: rgba(16,185,129,0.5);
        --ob-shadow: rgba(16,185,129,0.25);
    }
    .ob-option-card.ob-clone {
        --ob-glow: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(239,68,68,0.06) 100%);
        --ob-border: rgba(245,158,11,0.5);
        --ob-shadow: rgba(245,158,11,0.25);
    }
    .ob-icon-wrap {
        width: 62px;
        height: 62px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        font-size: 1.8rem;
    }
    .ob-scratch .ob-icon-wrap { background: linear-gradient(135deg, #0d609e, #8b5cf6); }
    .ob-profile .ob-icon-wrap { background: linear-gradient(135deg, #10b981, #06b6d4); }
    .ob-clone   .ob-icon-wrap { background: linear-gradient(135deg, #f59e0b, #ef4444); }
    .ob-icon-wrap i { color: white; }
    .ob-option-card h4 {
        color: #f1f5f9;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 0.6rem;
    }
    .ob-option-card p {
        color: #94a3b8;
        font-size: 0.88rem;
        line-height: 1.6;
        margin: 0;
    }
    .ob-badge {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        font-size: 0.7rem;
        padding: 3px 10px;
        border-radius: 50px;
        font-weight: 700;
        letter-spacing: 0.04em;
    }
    .ob-scratch .ob-badge { background: rgba(99,102,241,0.2); color: #818cf8; }
    .ob-profile .ob-badge { background: rgba(16,185,129,0.2); color: #34d399; }
    .ob-clone   .ob-badge { background: rgba(245,158,11,0.2); color: #fbbf24; }
    .ob-arrow {
        margin-top: 1.5rem;
        display: flex;
        align-items: center;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
        transition: color 0.2s, gap 0.2s;
        gap: 6px;
    }
    .ob-option-card:hover .ob-arrow { color: #a5b4fc; gap: 10px; }
    .ob-clone .ob-option-card:hover .ob-arrow { color: #fbbf24; }
    /* Clone picker panel */
    #ob-clone-panel {
        display: none;
        margin-top: 2rem;
        background: rgba(255,255,255,0.04);
        border: 1.5px solid rgba(245,158,11,0.25);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(8px);
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity:0; transform:translateY(-10px); }
        to   { opacity:1; transform:translateY(0); }
    }
    #ob-clone-panel h6 {
        color: #fbbf24;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .clone-resume-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-radius: 12px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 0.75rem;
        transition: all 0.2s;
    }
    .clone-resume-item:hover {
        background: rgba(245,158,11,0.08);
        border-color: rgba(245,158,11,0.3);
    }
    .clone-resume-item .resume-name {
        color: #f1f5f9;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .clone-resume-item .resume-date {
        color: #64748b;
        font-size: 0.78rem;
        margin-top: 2px;
    }
    .clone-resume-item .btn-clone-pick {
        background: linear-gradient(135deg, #f59e0b, #ef4444);
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .clone-resume-item .btn-clone-pick:hover {
        opacity: 0.85;
        transform: scale(1.04);
        color: white;
    }
    .ob-no-resumes {
        text-align: center;
        color: #64748b;
        padding: 1.5rem;
        font-size: 0.9rem;
    }
    /* Profile CV card — disabled state when no file uploaded */
    .ob-option-card.ob-profile-disabled {
        opacity: 0.65;
        cursor: default;
    }
    .ob-option-card.ob-profile-disabled:hover {
        transform: none;
        box-shadow: none;
    }
    .ob-profile-no-cv {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 1rem;
        font-size: 0.8rem;
        color: #ef4444;
        font-weight: 600;
        background: rgba(239,68,68,0.1);
        padding: 5px 12px;
        border-radius: 8px;
        border: 1px solid rgba(239,68,68,0.25);
    }
    .ob-profile-has-cv {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 1rem;
        font-size: 0.8rem;
        color: #34d399;
        font-weight: 600;
        background: rgba(52,211,153,0.08);
        padding: 5px 12px;
        border-radius: 8px;
        border: 1px solid rgba(52,211,153,0.2);
    }
    .ob-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        font-size: 0.85rem;
        text-decoration: none;
        margin-top: 2.5rem;
        transition: color 0.2s;
    }
    .ob-back-link:hover { color: #94a3b8; }
</style>

<div class="onboarding-overlay" id="resumeOnboardingOverlay">
    <div class="onboarding-card-wrap">
        <!-- Header -->
        <div class="onboarding-header">
            <div class="badge-pill">
                <i class="ti ti-sparkles"></i>
                AI Resume Builder
            </div>
            <h2>How would you like to <span>get started?</span></h2>
            <p>Choose the best starting point for your new professional resume.</p>
        </div>

        <!-- Option Cards -->
        <div class="ob-options-grid">

            <!-- Card 1: Start from Scratch -->
            <div class="ob-option-card ob-scratch" id="ob-scratch-card" onclick="startFromScratch()">
                <span class="ob-badge">Quick Start</span>
                <div class="ob-icon-wrap">
                    <i class="ti ti-file-plus"></i>
                </div>
                <h4>Start from Scratch</h4>
                <p>Build a completely new resume with a clean slate. Ideal if you want full creative control from the ground up.</p>
                <div class="ob-arrow">
                    Get started <i class="ti ti-arrow-right"></i>
                </div>
            </div>

            <!-- Card 2: Import from Profile / Uploaded CV -->
            <?php $hasUploadedCv = !empty($candidate?->resume); ?>
            <div class="ob-option-card ob-profile <?= !$hasUploadedCv ? 'ob-profile-disabled' : '' ?>"
                 id="ob-profile-card"
                 <?php if ($hasUploadedCv): ?>onclick="importFromProfile()"<?php endif; ?>>
                <span class="ob-badge">Recommended</span>
                <div class="ob-icon-wrap">
                    <i class="ti ti-cloud-upload"></i>
                </div>
                <h4>Use Uploaded CV</h4>
                <p>Pre-fill your resume automatically using your profile information — skills, job title, education, and bio.</p>
                <?php if ($hasUploadedCv): ?>
                    <div class="ob-profile-has-cv">
                        <i class="ti ti-circle-check"></i> CV on file — ready to import
                    </div>
                <?php else: ?>
                    <div class="ob-profile-no-cv">
                        <i class="ti ti-alert-circle"></i> No CV uploaded yet —
                        <a href="<?= site_url('candidate/profile/edit') ?>" style="color:#f87171; font-weight:700;">Upload in Profile</a>
                    </div>
                <?php endif; ?>
                <div class="ob-arrow">
                    <?= $hasUploadedCv ? 'Import & Continue' : 'Upload first' ?> <i class="ti ti-arrow-right"></i>
                </div>
            </div>

            <!-- Card 3: Clone Existing Resume -->
            <div class="ob-option-card ob-clone" id="ob-clone-card" onclick="toggleClonePanel()">
                <span class="ob-badge">Fast Copy</span>
                <div class="ob-icon-wrap">
                    <i class="ti ti-copy"></i>
                </div>
                <h4>Clone Existing Resume</h4>
                <p>Duplicate one of your saved resumes and tailor it for a new opportunity without starting over.</p>
                <div class="ob-arrow">
                    Choose a resume <i class="ti ti-arrow-right"></i>
                </div>
            </div>
        </div>

        <!-- Clone Picker Panel (hidden by default) -->
        <div id="ob-clone-panel">
            <h6><i class="ti ti-copy"></i> Select a resume to clone</h6>
            <?php if (!empty($allResumes)): ?>
                <?php foreach ($allResumes as $r): ?>
                    <div class="clone-resume-item">
                        <div>
                            <div class="resume-name"><?= esc($r->title) ?></div>
                            <div class="resume-date">Last updated: <?= date('M d, Y', strtotime($r->updated_at)) ?></div>
                        </div>
                        <a href="<?= site_url('candidate/resumes/clone/' . $r->id) ?>" class="btn-clone-pick">
                            <i class="ti ti-copy me-1"></i>Clone This
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ob-no-resumes">
                    <i class="ti ti-file-off" style="font-size:2rem; display:block; margin-bottom:0.5rem; color:#475569;"></i>
                    You don't have any saved resumes to clone yet.
                </div>
            <?php endif; ?>
        </div>

        <!-- Back link -->
        <div class="text-center">
            <a href="<?= site_url('candidate/resumes') ?>" class="ob-back-link">
                <i class="ti ti-arrow-left"></i> Back to My Resumes
            </a>
        </div>
    </div>
</div>

<!-- Hidden form for importing from profile (POST) -->
<form id="import-profile-form" method="POST" action="<?= site_url('candidate/resumes/import-profile') ?>" style="display:none;">
    <?= csrf_field() ?>
</form>

<script>
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
</script>
<!-- =================== END ONBOARDING MODAL =================== -->

<?php endif; ?>

<div class="content">
        <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">AI Resume Builder</h4>
            <h6>Design your professional resume with AI assistance</h6>
        </div>
        <div class="page-btn">
            <!-- Save button moved to bottom of builder for better flow -->
            <button type="button" id="undo-ai-apply" class="btn btn-outline-secondary me-2" title="Undo last AI apply">
                <i class="ti ti-rotate-ccw"></i> Undo AI
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Left Side: Form Controls -->
        <div class="col-lg-5 col-xl-5 mb-4">
            <div class="card custom-card mb-3">
                <div class="card-body p-2">
                    <div class="builder-step-nav d-flex flex-wrap gap-1 border-0 justify-content-between" style="display:none">
                        <div class="step-item active py-2 px-3 mb-0" data-step="info" style="font-size: 0.8rem; flex: 1; text-align: center; justify-content: center;">
                            <i class="ti ti-user me-1"></i> Info
                        </div>
                        <div class="step-item py-2 px-3 mb-0" data-step="experience" style="font-size: 0.8rem; flex: 1; text-align: center; justify-content: center;">
                            <i class="ti ti-briefcase me-1"></i> Exp
                        </div>
                        <div class="step-item py-2 px-3 mb-0" data-step="education" style="font-size: 0.8rem; flex: 1; text-align: center; justify-content: center;">
                            <i class="ti ti-school me-1"></i> Edu
                        </div>
                        <div class="step-item py-2 px-3 mb-0" data-step="skills" style="font-size: 0.8rem; flex: 1; text-align: center; justify-content: center;">
                            <i class="ti ti-tool me-1"></i> Skills
                        </div>
                        <div class="step-item py-2 px-3 mb-0" data-step="summary" style="font-size: 0.8rem; flex: 1; text-align: center; justify-content: center;">
                            <i class="ti ti-file-description me-1"></i> Summary
                        </div>
                        <div class="step-item py-2 px-3 mb-0" data-step="jd-match" style="font-size: 0.8rem; flex: 1; text-align: center; justify-content: center;">
                            <i class="ti ti-target me-1"></i> Tailor
                        </div>
                        <div class="step-item py-2 px-3 mb-0" data-step="templates" style="font-size: 0.8rem; flex: 1; text-align: center; justify-content: center;">
                            <i class="ti ti-layout-template me-1"></i> Layout
                        </div>
                    </div>
                </div>
            </div>
            <form id="resume-form" onsubmit="return false;" class="pb-3 mb-3">
                <input type="hidden" name="id" value="<?= $resume->id ?? '' ?>">
                
                <!-- Step: Basic Information -->
                <div class="ed-sec open" id="sec-info" data-step="info">
                    <div class="ed-head" onclick="toggleEdSec(this)">
                        <span class="ed-grip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0"/></svg></span>
                        <span class="ed-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg> Personal Information <span class="ed-tag">Required</span></span>
                        <span class="ed-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                    <div class="ed-body">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold text-dark">Resume Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="<?= esc($resume->title ?? 'My Professional Resume') ?>" placeholder="e.g. Senior Software Engineer Resume">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" value="<?= esc($resume->full_name ?? $candidate->full_name ?? auth()->user()->username ?? '') ?>" placeholder="Your Full Name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="<?= esc($resume->email ?? auth()->user()->email ?? '') ?>" placeholder="Your Email Address">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="<?= esc($resume->phone ?? $candidate->phone ?? '') ?>" placeholder="e.g. +1 234 567 890">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">Location</label>
                                    <input type="text" name="location" class="form-control" value="<?= esc($resume->location ?? $candidate->location ?? '') ?>" placeholder="e.g. New York, USA">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold text-dark">LinkedIn Profile URL</label>
                                    <input type="text" name="linkedin" class="form-control" value="<?= esc($linkedin ?? '') ?>" placeholder="e.g. https://linkedin.com/in/yourprofile">
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary next-step" data-step-target="experience">Next: Experience <i class="ti ti-arrow-right ms-1"></i></button>
                            </div>
                        </div><!-- /card-body -->
                    </div><!-- /card -->
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec info -->

                <!-- NOTE: Professional Summary is intentionally shown after Experience/Education/Skills in the input flow
                     so AI generation can use the entered data. The summary will still appear at the top in exported resumes. -->

                <!-- Step: Experience -->
                <div class="ed-sec" id="sec-experience" data-step="experience">
                    <div class="ed-head" onclick="toggleEdSec(this)">
                        <span class="ed-grip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0"/></svg></span>
                        <span class="ed-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> Work Experience</span>
                        <span class="ed-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                    <div class="ed-body">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Work Experience</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary add-experience">
                                <i class="ti ti-plus"></i> Add Experience
                            </button>
                        </div>
                        <div class="card-body" id="experience-container">
                            <!-- Loop through and render existing experiences -->
                            <?php if (empty($experiences)): ?>
                                <div class="text-center py-4 text-muted no-items">
                                    <p>No experience added yet. Click "Add Experience" to start.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($experiences as $index => $exp): ?>
                                    <div class="experience-item border rounded p-3 mb-3 position-relative" style="background-color: #fcfcfd;">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Company Name</label>
                                                <input type="text" name="exp_company[]" class="form-control form-control-sm" placeholder="Company Name" value="<?= esc($exp->company ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Job Position</label>
                                                <input type="text" name="exp_position[]" class="form-control form-control-sm" placeholder="Job Position" value="<?= esc($exp->position ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Start Date</label>
                                                <input type="date" name="exp_start_date[]" class="form-control form-control-sm" value="<?= esc($exp->start_date ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3 exp-end-date-col" style="<?= !empty($exp->is_current) ? 'display: none;' : '' ?>">
                                                <label class="form-label small fw-semibold text-muted">End Date</label>
                                                <input type="date" name="exp_end_date[]" class="form-control form-control-sm" value="<?= esc($exp->end_date ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="<?= $index ?>" <?= !empty($exp->is_current) ? 'checked' : '' ?>>
                                                    <label class="form-check-label small fw-semibold text-muted">Currently Work Here</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="small fw-semibold text-muted">Description & Achievements</label>
                                                    <div>
                                                        <button type="button" class="ai-assist-btn improve-desc-ai">
                                                            <i class="ti ti-wand"></i> Improve with AI
                                                        </button>
                                                        <button type="button" class="ai-assist-btn generate-bullets-ai" style="margin-left:8px;">
                                                            <i class="ti ti-list"></i> Generate Bullets
                                                        </button>
                                                    </div>
                                                </div>
                                                <textarea name="exp_description[]" class="form-control form-control-sm" rows="3" placeholder="Describe your responsibilities and achievements..."><?= esc($exp->description ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary prev-step" data-step-target="info"><i class="ti ti-arrow-left me-1"></i> Previous</button>
                                <button type="button" class="btn btn-primary next-step" data-step-target="education">Next: Education <i class="ti ti-arrow-right ms-1"></i></button>
                            </div>
                        </div><!-- /card-body -->
                    </div><!-- /card -->
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec experience -->

                <!-- Step: Education -->
                <div class="ed-sec" id="sec-education" data-step="education">
                    <div class="ed-head" onclick="toggleEdSec(this)">
                        <span class="ed-grip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0"/></svg></span>
                        <span class="ed-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg> Education</span>
                        <span class="ed-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                    <div class="ed-body">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Education</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary add-education">
                                <i class="ti ti-plus"></i> Add Education
                            </button>
                        </div>
                        <div class="card-body" id="education-container">
                            <!-- Loop through and render existing education -->
                            <?php if (empty($education)): ?>
                                <div class="text-center py-4 text-muted no-items">
                                    <p>No education added yet. Click "Add Education" to start.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($education as $edu): ?>
                                    <div class="education-item border rounded p-3 mb-3 position-relative" style="background-color: #fcfcfd;">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">School / University</label>
                                                <input type="text" name="edu_school[]" class="form-control form-control-sm" placeholder="School / University" value="<?= esc($edu->institution ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Degree</label>
                                                <select name="edu_degree[]" class="form-select form-select-sm">
                                                    <option value="">Select Degree</option>
                                                    <option value="High School" <?= ($edu->degree ?? '') === 'High School' ? 'selected' : '' ?>>High School</option>
                                                    <option value="Associate" <?= ($edu->degree ?? '') === 'Associate' ? 'selected' : '' ?>>Associate Degree</option>
                                                    <option value="Bachelor" <?= ($edu->degree ?? '') === 'Bachelor' ? 'selected' : '' ?>>Bachelor's Degree</option>
                                                    <option value="Master" <?= ($edu->degree ?? '') === 'Master' ? 'selected' : '' ?>>Master's Degree</option>
                                                    <option value="PhD" <?= ($edu->degree ?? '') === 'PhD' ? 'selected' : '' ?>>PhD / Doctorate</option>
                                                    <option value="Certificate" <?= ($edu->degree ?? '') === 'Certificate' ? 'selected' : '' ?>>Certificate</option>
                                                    <option value="Other" <?= ($edu->degree ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="form-control form-control-sm" placeholder="Field of Study" value="<?= esc($edu->field_of_study ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Graduation Year</label>
                                                <?php 
                                                    $gradYear = !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '';
                                                ?>
                                                <input type="number" name="edu_year[]" class="form-control form-control-sm" placeholder="Graduation Year" min="1950" max="2030" value="<?= esc($gradYear) ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary prev-step" data-step-target="experience"><i class="ti ti-arrow-left me-1"></i> Previous</button>
                                <button type="button" class="btn btn-primary next-step" data-step-target="skills">Next: Skills <i class="ti ti-arrow-right ms-1"></i></button>
                            </div>
                        </div><!-- /card-body -->
                    </div><!-- /card -->
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec education -->

                <!-- Step: Skills -->
                <div class="ed-sec" id="sec-skills" data-step="skills">
                    <div class="ed-head" onclick="toggleEdSec(this)">
                        <span class="ed-grip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0"/></svg></span>
                        <span class="ed-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h8l-1 8 11-13h-8Z"/></svg> Skills</span>
                        <span class="ed-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                    <div class="ed-body">
                <div class="card custom-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Skills</h5>
                    </div>
                    <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Add Skills (Comma separated)</label>
                                <?php 
                                    $skillsList = [];
                                    if (!empty($skills)) {
                                        foreach ($skills as $skill) {
                                            $skillsList[] = $skill->skill_name;
                                        }
                                    }
                                    $skillsVal = implode(', ', $skillsList);
                                ?>
                                <input type="text" name="skills" class="form-control tags-input" value="<?= esc($skillsVal) ?>" placeholder="e.g. PHP, JavaScript, Project Management">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Certifications (One per line)</label>
                                <textarea name="certs" class="form-control" rows="3" placeholder="e.g. Project Management Professional (PMP)&#10;ICAN Chartered Accountant"><?= esc($certs ?? '') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Languages (Comma separated)</label>
                                <input type="text" name="languages" class="form-control" value="<?= esc($languages ?? '') ?>" placeholder="e.g. English, French, Spanish">
                            </div>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary prev-step" data-step-target="education"><i class="ti ti-arrow-left me-1"></i> Previous</button>
                                <button type="button" class="btn btn-primary next-step" data-step-target="summary">Next: Summary <i class="ti ti-arrow-right ms-1"></i></button>
                            </div>
                        </div><!-- /card-body -->
                    </div><!-- /card -->
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec skills -->

                <!-- Step: Summary (moved after Skills so AI can use experience/education/skills) -->
                <div class="ed-sec" id="sec-summary" data-step="summary">
                    <div class="ed-head" onclick="toggleEdSec(this)">
                        <span class="ed-grip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0"/></svg></span>
                        <span class="ed-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></svg> Professional Summary</span>
                        <span class="ed-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                    <div class="ed-body">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Professional Summary</h5>
                            <button type="button" class="ai-assist-btn" id="generate-summary-ai">
                                <i class="ti ti-sparkles"></i> Generate with AI
                            </button>
                        </div>
                        <div class="card-body">
                            <textarea name="summary" id="resume-summary" class="form-control" rows="6" placeholder="A brief overview of your professional background and key achievements..."><?= esc($resume->summary ?? '') ?></textarea>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary prev-step" data-step-target="skills"><i class="ti ti-arrow-left me-1"></i> Previous</button>
                                <button type="button" class="btn btn-primary next-step" data-step-target="templates">Next: Templates <i class="ti ti-arrow-right ms-1"></i></button>
                            </div>
                        </div><!-- /card-body -->
                    </div><!-- /card -->
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec summary -->

                <!-- Step: Tailor to a Job -->
                <div class="ed-sec" id="sec-jd-match" data-step="jd-match">
                    <div class="ed-head" onclick="toggleEdSec(this)">
                        <span class="ed-grip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0"/></svg></span>
                        <span class="ed-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.2"/></svg> Tailor to Job</span>
                        <span class="ed-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                    <div class="ed-body">
                    <div class="card custom-card">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="ti ti-target text-primary"></i>
                            <h5 class="card-title mb-0">Tailor to a Job</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3" style="font-size: 0.85rem;">Paste a job description and we'll score your resume's keyword match, then surface missing skills you can add with one click.</p>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark" for="job-pick">Pick a JobberRecruit listing</label>
                                <select class="form-select" id="job-pick" aria-label="Tailor to a listed job">
                                    <option value="">— Choose a live job on JobberRecruit —</option>
                                    <option value="senior-accountant">Senior Accountant — Renaissance Africa Energy (Lagos)</option>
                                    <option value="finance-officer">Finance Officer — HR/People & Operations (Lagos)</option>
                                    <option value="grad-trainee">Graduate Trainee — AP Programme 2026 (Nigeria)</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark" for="jd">Or paste any job description</label>
                                <textarea class="form-control" id="jd" rows="5" placeholder="Paste the job advert here and we'll score the match and surface missing keywords…" style="font-size: 0.85rem; resize: vertical;"></textarea>
                            </div>
                            
                            <div id="match-wrap" class="d-none">
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-3" style="background: #f8fafd; border: 1px solid #e2e8f0;">
                                    <div class="gauge position-relative flex-shrink-0" style="width: 64px; height: 64px;">
                                        <svg viewBox="0 0 74 74" style="transform: rotate(-90deg); width: 100%; height: 100%;" aria-hidden="true">
                                            <circle class="t" cx="37" cy="37" r="33" style="fill: none; stroke: #edf2f7; stroke-width: 8;"></circle>
                                            <circle class="p" id="match-p" cx="37" cy="37" r="33" style="fill: none; stroke: var(--primary); stroke-width: 8; stroke-linecap: round; stroke-dasharray: 207; stroke-dashoffset: 207; transition: stroke-dashoffset .4s ease;"></circle>
                                        </svg>
                                        <b id="match-num" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.1rem; font-weight: 700; color: #0a192f; font-family: 'Sora', sans-serif;">0%</b>
                                    </div>
                                    <div>
                                        <b style="font-family: 'Sora', sans-serif; font-size: 0.95rem; color: #0a192f; display: block;">Job Match Score</b>
                                        <p class="mb-0 text-muted" style="font-size: 0.78rem;">Keyword overlap between your resume and the job description.</p>
                                    </div>
                                </div>
                                
                                <div class="mb-2" style="font-size: .68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em;">
                                    Missing Keywords — click to add to Skills
                                </div>
                                <div id="kw-chips" class="d-flex flex-wrap gap-2 mb-3"></div>
                            </div>
                        </div><!-- /card-body -->
                    </div><!-- /card -->
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec jd-match -->

                <!-- Step: Choose World-Class Template -->
                <div class="ed-sec" id="sec-templates" data-step="templates">
                    <div class="ed-head" onclick="toggleEdSec(this)">
                        <span class="ed-grip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0"/></svg></span>
                        <span class="ed-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg> Layout & Template</span>
                        <span class="ed-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                    <div class="ed-body">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Choose Resume Template</h5>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="template_id" id="template_id" value="<?= esc($resume->template_id ?? 'classic') ?>">
                            <div class="row">
                                <!-- Classic Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? 'classic') === 'classic' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="classic">
                                        <div class="template-preview classic-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff;">
                                            <div style="height: 12px; background: #2563eb; width: 100%;"></div>
                                            <div class="p-2 text-start">
                                                <div style="height: 8px; background: #1e40af; width: 60%; margin-bottom: 6px;"></div>
                                                <div style="height: 4px; background: #cbd5e1; width: 80%; margin-bottom: 12px;"></div>
                                                <div style="height: 6px; background: #94a3b8; width: 40%; margin-bottom: 4px;"></div>
                                                <div style="height: 4px; background: #e2e8f0; width: 90%; margin-bottom: 4px;"></div>
                                                <div style="height: 4px; background: #e2e8f0; width: 85%; margin-bottom: 8px;"></div>
                                                <div style="height: 6px; background: #94a3b8; width: 35%; margin-bottom: 4px;"></div>
                                                <div style="height: 4px; background: #e2e8f0; width: 90%; margin-bottom: 4px;"></div>
                                            </div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Classic Professional</h6>
                                        <span class="text-muted small">Traditional & clean layout</span>
                                    </div>
                                </div>
                                
                                <!-- Modern Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? '') === 'modern' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="modern">
                                        <div class="template-preview modern-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff; display: flex;">
                                            <div style="width: 30%; background: #f8fafc; border-right: 1px solid #e2e8f0; height: 100%; padding: 8px 4px; box-sizing: border-box; text-align: left;">
                                                <div style="height: 6px; background: #0a4d7e; width: 80%; margin-bottom: 6px;"></div>
                                                <div style="height: 3px; background: #cbd5e1; width: 90%; margin-bottom: 3px;"></div>
                                                <div style="height: 3px; background: #cbd5e1; width: 70%; margin-bottom: 12px;"></div>
                                                <div style="height: 6px; background: #0a4d7e; width: 80%; margin-bottom: 6px;"></div>
                                                <div style="height: 3px; background: #cbd5e1; width: 85%; margin-bottom: 3px;"></div>
                                                <div style="height: 3px; background: #cbd5e1; width: 75%; margin-bottom: 3px;"></div>
                                            </div>
                                            <div style="width: 70%; padding: 8px; box-sizing: border-box; text-align: left;">
                                                <div style="height: 10px; background: #1e3a8a; width: 70%; margin-bottom: 4px;"></div>
                                                <div style="height: 4px; background: #0a4d7e; width: 40%; margin-bottom: 10px;"></div>
                                                <div style="height: 6px; background: #94a3b8; width: 50%; margin-bottom: 4px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; width: 90%; margin-bottom: 3px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; width: 85%; margin-bottom: 3px;"></div>
                                            </div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Modern & Sleek</h6>
                                        <span class="text-muted small">Elegant double-column</span>
                                    </div>
                                </div>

                                <!-- Creative Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? '') === 'creative' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="creative">
                                        <div class="template-preview creative-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff;">
                                            <div style="background: #6b21a8; padding: 8px; text-align: left; height: 35px; box-sizing: border-box;">
                                                <div style="height: 8px; background: #ffffff; width: 50%; margin-bottom: 4px;"></div>
                                                <div style="height: 3px; background: #e9d5ff; width: 75%;"></div>
                                            </div>
                                            <div class="p-2 text-start">
                                                <div style="height: 6px; background: #6b21a8; width: 35%; margin-bottom: 6px; border-left: 2px solid #a855f7; padding-left: 2px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; width: 90%; margin-bottom: 3px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; width: 85%; margin-bottom: 12px;"></div>
                                                <div style="height: 6px; background: #6b21a8; width: 30%; margin-bottom: 6px; border-left: 2px solid #a855f7; padding-left: 2px;"></div>
                                                <span style="display: inline-block; height: 8px; background: #faf5ff; border: 1px solid #e9d5ff; width: 25%; margin-right: 2px; border-radius: 4px;"></span>
                                                <span style="display: inline-block; height: 8px; background: #faf5ff; border: 1px solid #e9d5ff; width: 20%; margin-right: 2px; border-radius: 4px;"></span>
                                                <span style="display: inline-block; height: 8px; background: #faf5ff; border: 1px solid #e9d5ff; width: 30%; border-radius: 4px;"></span>
                                            </div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Creative & Bold</h6>
                                        <span class="text-muted small">Stunning vibrant banner</span>
                                    </div>
                                </div>

                                <!-- Executive Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? '') === 'executive' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="executive">
                                        <div class="template-preview executive-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff;">
                                            <div class="p-2 text-center" style="border-bottom: 2px double #b45309;">
                                                <div style="height: 10px; background: #1e3a8a; width: 50%; margin: 0 auto 3px auto;"></div>
                                                <div style="height: 4px; background: #b45309; width: 30%; margin: 0 auto;"></div>
                                            </div>
                                            <div class="p-2 text-start">
                                                <div style="height: 5px; background: #1e3a8a; width: 40%; margin-bottom: 6px; border-bottom: 1px solid #1e3a8a;"></div>
                                                <div style="height: 4px; background: #334155; width: 90%; margin-bottom: 3px;"></div>
                                                <div style="height: 4px; background: #334155; width: 85%; margin-bottom: 10px;"></div>
                                                <div style="height: 5px; background: #1e3a8a; width: 45%; margin-bottom: 6px; border-bottom: 1px solid #1e3a8a;"></div>
                                                <div style="height: 4px; background: #334155; width: 88%; margin-bottom: 3px;"></div>
                                            </div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Executive Serif</h6>
                                        <span class="text-muted small">Centred stately design</span>
                                    </div>
                                </div>

                                <!-- Minimalist Template Choice -->
                                <div class="col-xl-2-4 col-lg-4 col-md-6 mb-4">
                                    <div class="template-choice border rounded p-3 text-center cursor-pointer transition-all <?= ($resume->template_id ?? '') === 'minimalist' ? 'active border-primary border-2 shadow-sm' : '' ?>" data-template="minimalist">
                                        <div class="template-preview minimalist-preview mb-2 rounded position-relative" style="height: 150px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff; padding: 12px 10px; box-sizing: border-box; text-align: left;">
                                            <div style="height: 10px; background: #0f172a; width: 40%; margin-bottom: 2px;"></div>
                                            <div style="height: 4px; background: #94a3b8; width: 60%; margin-bottom: 10px;"></div>
                                            <div style="height: 1px; background: #f1f5f9; width: 100%; margin-bottom: 10px;"></div>
                                            <div style="height: 6px; background: #0f172a; width: 25%; margin-bottom: 6px;"></div>
                                            <div style="height: 3px; background: #475569; width: 90%; margin-bottom: 3px;"></div>
                                            <div style="height: 3px; background: #475569; width: 85%; margin-bottom: 8px;"></div>
                                            <div style="height: 6px; background: #0f172a; width: 30%; margin-bottom: 6px;"></div>
                                            <div style="height: 3px; background: #475569; width: 88%; margin-bottom: 3px;"></div>
                                        </div>
                                        <h6 class="fw-semibold mb-1">Minimalist Clean</h6>
                                        <span class="text-muted small">Sophisticated airy space</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-start">
                                <button type="button" class="btn btn-outline-secondary prev-step" data-step-target="summary"><i class="ti ti-arrow-left me-1"></i> Previous</button>
                            </div>
                        </div><!-- /card-body -->
                    </div><!-- /card -->
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec templates -->

                            <!-- Download Section -->
                            <div class="mt-4 pt-4 border-top text-center">
                                <h5 class="fw-bold text-dark mb-2">✨ Ready to apply? Download your resume!</h5>
                                <p class="text-muted small mb-4">Export your freshly built resume in your chosen template style. We save your progress automatically.</p>
                                
                                <div class="d-flex flex-wrap justify-content-center gap-3">
                                    <button type="button" class="btn btn-danger btn-lg px-4 py-3 fw-bold download-pdf-btn d-flex align-items-center shadow-sm transition-all" style="border-radius: 12px; font-size: 0.95rem;">
                                        <i class="ti ti-file-type-pdf me-2 fs-4"></i> Download Professional PDF
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg px-4 py-3 fw-bold download-docx-btn d-flex align-items-center shadow-sm transition-all" style="border-radius: 12px; font-size: 0.95rem; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color-dark) 100%); border: none;">
                                        <i class="ti ti-file-text me-2 fs-4"></i> Download Word (DOCX)
                                    </button>
                                </div>

                                <div class="mt-3 text-center">
                                    <div class="d-flex justify-content-center gap-2 align-items-center">
                                        <button type="button" id="save-resume-btn" class="btn btn-primary px-4 py-2 fw-semibold">
                                        <i class="ti ti-device-floppy me-1"></i> Save Resume
                                        </button>
                                        <button type="button" id="open-revisions-btn" class="btn btn-outline-secondary px-3 py-2" title="Revision History">
                                            <i class="ti ti-history me-1"></i> Revisions
                                        </button>
                                    </div>
                                </div>
                            </div><!-- /Download Section -->
                        </form><!-- /resume-form -->
                    </div><!-- /col-lg-5 -->
                </div><!-- /row -->
            </div><!-- /content -->
        </div><!-- /rb-editor-col -->

        <!-- Right Side: Live Premium Preview -->
        <div class="rb-preview-col" id="rb-preview-col">
            <!-- Customization Bar (hidden — controls moved to top toolbar) -->
            <div class="design-bar shadow-sm" style="display:none">
                <span class="lbl fw-bold text-dark">Template:</span>
                <select class="form-select form-select-sm select border-secondary" id="tpl-select" style="min-width:130px; font-weight: 600;">
                    <option value="t-classic">Classic Professional</option>
                    <option value="t-creative">Creative & Bold</option>
                    <option value="t-exec">Executive Serif</option>
                    <option value="t-minimal">Minimalist Clean</option>
                    <option value="t-modern">Modern & Sleek</option>
                </select>

                <span class="lbl fw-bold text-dark ms-2">Spacing:</span>
                <div class="dens border border-secondary rounded p-1">
                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 border-0 on" id="spacing-roomy-btn" data-spacing="roomy">Roomy</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 border-0" id="spacing-tight-btn" data-spacing="tight">Tight</button>
                </div>

                <div class="wm-note ms-auto">
                    <i class="ti ti-shield-check text-success"></i> Anti-Crop Protection
                </div>
            </div>

            <!-- ATS Score panel -->
            <div class="score-card shadow-sm mb-3" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:20px;">
                <div class="score-top d-flex align-items-center gap-3">
                    <div class="gauge position-relative" id="gauge-wrap" aria-label="ATS score 0 out of 100" style="width: 74px; height: 74px;">
                        <svg viewBox="0 0 74 74" style="transform: rotate(-90deg); width: 100%; height: 100%;">
                            <circle class="t" cx="37" cy="37" r="33" style="fill: none; stroke: #edf2f7; stroke-width: 8;"></circle>
                            <circle class="p" id="gauge-p" cx="37" cy="37" r="33" style="fill: none; stroke: rgb(220, 38, 38); stroke-width: 8; stroke-linecap: round; stroke-dasharray: 207; stroke-dashoffset: 207; transition: stroke-dashoffset 0.4s ease, stroke 0.4s ease;"></circle>
                        </svg>
                        <b id="ats-num" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.4rem; font-weight: 700; color: #0a192f; font-family: 'Sora', sans-serif;">0</b>
                    </div>
                    <div class="score-info">
                        <b style="font-family: 'Sora', sans-serif; font-size: 1.05rem; color: #0a192f; display: block;">Resume Intelligence</b>
                        <p class="mb-0 text-muted" style="font-size: 0.8rem; line-height: 1.4;">ATS readiness plus recruiter-grade writing checks — all recalculated as you type.</p>
                    </div>
                </div>
                
                <!-- 6 Metric Gauges Grid -->
                <div class="met-grid" id="met-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px 15px; margin-top: 18px; border-top: 1px solid #edf2f7; padding-top: 15px;">
                    <!-- Dynamically populated from JS -->
                </div>
                
                <!-- Writing review issues header -->
                <div class="mt-4 pt-3 border-top">
                    <b style="font-family: 'Sora', sans-serif; font-size: 0.9rem; color: #0a192f; display: block; margin-bottom: 8px;">Detailed Audit & Checklist</b>
                    <ul class="score-list" id="ats-list" style="list-style: none; padding-left: 0; margin-bottom: 0;">
                        <!-- Dynamic Checklist items go here -->
                    </ul>
                </div>
            </div>

            <!-- Live Rendering Shell -->
            <div class="pv-shell shadow-sm mb-3">
                <div id="doc" class="doc t-classic spacing-roomy wm-tile guides">
                    <!-- Live Document Compiled Here -->
                </div>
            </div>
            <div class="pv-hint">A4 Page-break guidance shown above. Adjust text spacing to fit exactly <b>1 Page</b>.</div>
        </div><!-- /rb-preview-col -->
    </div><!-- /rb-split -->

<!-- AI Modal Loader -->
<div class="modal fade" id="aiLoaderModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-5 border-0 bg-transparent">
            <div class="spinner mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
            <h4 class="text-white fw-bold">AI is generating content...</h4>
            <p class="text-white-50">Preparing your personalized professional text.</p>
        </div>
    </div>
</div>

<!-- AI Preview Modal -->
<div class="modal fade ai-preview-modal" id="aiPreviewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">AI Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ai-preview-render" id="aiPreviewRender">
        <!-- Rendered AI HTML will appear here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-outline-primary" id="aiCopyPlainBtn">Copy as Plain Text</button>
        <button type="button" class="btn btn-outline-info" id="aiApplyActiveBtn">Apply to Active Field</button>
        <button type="button" class="btn btn-primary" id="aiApplyBtn">Apply to Summary</button>
      </div>
    </div>
  </div>
</div>

<!-- AI Resume Coach FAB Trigger -->
<button type="button" class="ai-coach-fab" data-bs-toggle="offcanvas" data-bs-target="#aiResumeCoachDrawer" aria-controls="aiResumeCoachDrawer" id="open-ai-coach-btn">
    <div class="pulse-ring"></div>
    <i class="ti ti-sparkles"></i>
</button>

<!-- AI Resume Coach Offcanvas Sidebar -->
<div class="offcanvas offcanvas-end custom-coach-offcanvas" tabindex="-1" id="aiResumeCoachDrawer" aria-labelledby="aiResumeCoachDrawerLabel" data-bs-scroll="true" data-bs-backdrop="false">
    <div class="offcanvas-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-md bg-primary-transparent me-2" style="width: 35px; height: 35px; background: rgba(13, 96, 158, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="ti ti-sparkles text-primary fs-18"></i>
            </div>
            <div>
                <h5 class="offcanvas-title mb-0" id="aiResumeCoachDrawerLabel">ResumeAI Coach</h5>
                <span class="badge bg-success bg-opacity-20 text-success fs-10 fw-bold">Active Coaching</span>
            </div>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="coach-chat-container">
        <!-- Messages Log -->
        <div class="coach-messages-area" id="coach-chat-window">
            <div id="coach-chat-messages" class="d-flex flex-column gap-3">
                <!-- Loaded dynamically -->
            </div>
        </div>
        
        <!-- Input Form Area -->
        <div class="coach-input-area">
            <form id="coach-chat-form" onsubmit="return false;">
                <div class="coach-input-group">
                    <input type="text" id="coach-chat-input" class="coach-input-field" placeholder="Type your message to ResumeAI..." autocomplete="off">
                    <button class="coach-send-btn" type="submit" id="btn-coach-send" aria-label="Action">
    <i class="ti ti-send"></i>
</button>
                </div>
            </form>
        </div><!-- /rb-preview-col -->
    </div><!-- /rb-split -->
</div><!-- /rb-layout -->

<?= $this->include('candidate/resume/partials/revisions_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // ═══ MOCKUP: Accordion toggle ═══
    function toggleEdSec(head) {
        var sec = head.closest('.ed-sec');
        if (sec) {
            sec.classList.toggle('open');
        }
    }

    // ═══ MOCKUP: Mobile tabs (edit / preview) ═══
    function switchMobileTab(mode, btn) {
        var editor = document.getElementById('rb-editor-col');
        var preview = document.getElementById('rb-preview-col');
        var tabs = document.querySelectorAll('.rb-tabs button');
        tabs.forEach(function(t) { t.classList.remove('active'); });
        btn.classList.add('active');
        if (mode === 'preview') {
            editor.classList.add('preview-active');
            preview.classList.add('show-mobile');
        } else {
            editor.classList.remove('preview-active');
            preview.classList.remove('show-mobile');
        }
    }

    // ═══ MOCKUP: Design-bar accent color ═══
    function setAccentColor(hex, el) {
        document.documentElement.style.setProperty('--primary-color', hex);
        document.querySelectorAll('.design-bar .swatch').forEach(function(s) { s.classList.remove('active'); });
        if (el) el.classList.add('active');
    }

    // ═══ MOCKUP: Design-bar font family ═══
    function setFontFamily(family) {
        var doc = document.querySelector('.doc');
        if (doc) doc.style.fontFamily = family;
    }

    // ═══ MOCKUP: Design-bar spacing toggle ═══
    function setSpacing(mode, el) {
        document.querySelectorAll('.design-bar .spacing-btn').forEach(function(b) { b.classList.remove('active'); });
        if (el) el.classList.add('active');
        var doc = document.querySelector('.doc');
        if (doc) {
            doc.classList.remove('spacing-roomy', 'spacing-tight');
            doc.classList.add('spacing-' + mode);
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
            return String(str).replace(/[&<>"']/g, function (s) {
                return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s]);
            });
        }

        // ── LIVE PREVIEW GENERATION ──
        function renderLivePreview() {
            var name = $('input[name="full_name"]').val() || '';
            var title = $('input[name="title"]').val() || '';
            var email = $('input[name="email"]').val() || '';
            var phone = $('input[name="phone"]').val() || '';
            var locationStr = $('input[name="location"]').val() || '';
            var summary = $('#resume-summary').val() || '';
            
            // Selected layout & density
            var tpl = $('#tpl-select').val() || 't-classic';
            var spacing = $('#spacing-roomy-btn').hasClass('on') ? 'spacing-roomy' : 'spacing-tight';
            
            // Container update
            var $doc = $('#doc');
            $doc.removeClass().addClass('doc ' + tpl + ' ' + spacing + ' wm-tile guides');
            
            var linkedin = $('input[name="linkedin"]').val() || '';
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
                
                var dates = start + (current ? ' - Present' : (end ? ' - ' + end : ''));
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
            if (educationHtml) {
                html += '<div class="d-sec"><h2>Education</h2>' + educationHtml + '</div>';
            }
            
            // Skills List
            var skillsStr = $('input[name="skills"]').val() || '';
            var skills = skillsStr.split(',').map(s => s.trim()).filter(Boolean);
            if (skills.length) {
                var skillsLi = skills.map(s => '<li>' + escapeHtml(s) + '</li>').join('');
                html += '<div class="d-sec"><h2>Skills</h2><ul class="d-skills">' + skillsLi + '</ul></div>';
            }

            // Certifications List
            var certsStr = $('textarea[name="certs"]').val() || '';
            var certs = certsStr.split('\n').map(s => s.trim()).filter(Boolean);
            if (certs.length) {
                var certsLi = certs.map(c => '<li>' + escapeHtml(c) + '</li>').join('');
                html += '<div class="d-sec"><h2>Certifications</h2><ul class="d-skills">' + certsLi + '</ul></div>';
            }

            // Languages List
            var languagesStr = $('input[name="languages"]').val() || '';
            var languages = languagesStr.split(',').map(s => s.trim()).filter(Boolean);
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

        // ── TAILOR TO JOB — pre-set job descriptions for demo listings ──
        var JOBS = {
            'senior-accountant': "Senior Accountant needed. Responsibilities: month-end close, management reporting, budgeting and forecasting, VAT and PAYE compliance, bank reconciliation, fixed asset management, audit preparation. Requirements: B.Sc Accounting, 4+ years experience, strong Excel, ICAN is an advantage. Tools: accounting software, spreadsheets.",
            'finance-officer': "Finance Officer to support operations. Duties: invoicing, accounts payable, payroll processing, expense reporting, vendor management, compliance with statutory filings, monthly reports for leadership. Skills: accounting, communication, attention to accuracy, Excel, budgeting.",
            'grad-trainee': "Graduate trainee programme. We seek graduates with strong analysis skills, communication, teamwork, willingness to learn, Excel proficiency. Any discipline; finance and accounting graduates encouraged. Training provided in reporting, compliance and operations."
        };

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
            if (val && JOBS[val]) {
                $('#jd').val(JOBS[val]);
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
                <div class="experience-item border rounded p-3 mb-3 position-relative" style="background-color: #fcfcfd; display: none;">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-muted">Company Name</label>
                            <input type="text" name="exp_company[]" class="form-control form-control-sm" placeholder="e.g. Google">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-muted">Job Position</label>
                            <input type="text" name="exp_position[]" class="form-control form-control-sm" placeholder="e.g. Senior Developer">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-semibold text-muted">Start Date</label>
                            <input type="date" name="exp_start_date[]" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4 mb-3 exp-end-date-col">
                            <label class="form-label small fw-semibold text-muted">End Date</label>
                            <input type="date" name="exp_end_date[]" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="${count}">
                                <label class="form-check-label small fw-semibold text-muted">Currently Work Here</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="small fw-semibold text-muted">Description & Achievements</label>
                                <div>
                                    <button type="button" class="ai-assist-btn improve-desc-ai">
                                        <i class="ti ti-wand"></i> Improve with AI
                                    </button>
                                    <button type="button" class="ai-assist-btn generate-bullets-ai" style="margin-left:8px;">
                                        <i class="ti ti-list"></i> Generate Bullets
                                    </button>
                                </div>
                            </div>
                            <textarea name="exp_description[]" class="form-control form-control-sm" rows="3" placeholder="Describe your responsibilities and achievements..."></textarea>
                        </div>
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
                <div class="education-item border rounded p-3 mb-3 position-relative" style="background-color: #fcfcfd; display: none;">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted">School / University</label>
                            <input type="text" name="edu_school[]" class="form-control form-control-sm" placeholder="School / University">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted">Degree</label>
                            <select name="edu_degree[]" class="form-select form-select-sm">
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
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted">Field of Study</label>
                            <input type="text" name="edu_field[]" class="form-control form-control-sm" placeholder="Field of Study">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted">Graduation Year</label>
                            <input type="number" name="edu_year[]" class="form-control form-control-sm" placeholder="Graduation Year" min="1950" max="2030">
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
            });
        });

        // Dynamic change handler for 'Currently Work Here' checkbox
        $(document).on('change', '.exp-current-check', function() {
            const $endDateCol = $(this).closest('.row').find('.exp-end-date-col');
            if ($(this).is(':checked')) {
                $endDateCol.slideUp(200).find('input').val('');
            } else {
                $endDateCol.slideDown(200);
            }
        });

        // Improve Description with AI
        $(document).on('click', '.improve-desc-ai', function() {
            const btn = $(this);
            const textarea = btn.closest('.col-md-12').find('textarea');
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
            const textarea = btn.closest('.col-md-12').find('textarea');
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
                            snap.experiences.forEach(function(e) {
                                const html = `
                                    <div class="experience-item border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Company Name</label>
                                                <input type="text" name="exp_company[]" class="form-control form-control-sm" value="${escapeHtml(e.company || '')}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Job Position</label>
                                                <input type="text" name="exp_position[]" class="form-control form-control-sm" value="${escapeHtml(e.position || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Start Date</label>
                                                <input type="date" name="exp_start_date[]" class="form-control form-control-sm" value="${escapeHtml(e.start_date || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3 exp-end-date-col" style="${e.is_current ? 'display: none;' : ''}">
                                                <label class="form-label small fw-semibold text-muted">End Date</label>
                                                <input type="date" name="exp_end_date[]" class="form-control form-control-sm" value="${escapeHtml(e.end_date || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" ${e.is_current ? 'checked' : ''}>
                                                    <label class="form-check-label small fw-semibold text-muted">Currently Work Here</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="small fw-semibold text-muted">Description & Achievements</label>
                                                    <div>
                                                        <button type="button" class="ai-assist-btn improve-desc-ai">
                                                            <i class="ti ti-wand"></i> Improve with AI
                                                        </button>
                                                        <button type="button" class="ai-assist-btn generate-bullets-ai" style="margin-left:8px;">
                                                            <i class="ti ti-list"></i> Generate Bullets
                                                        </button>
                                                    </div>
                                                </div>
                                                <textarea name="exp_description[]" class="form-control form-control-sm" rows="3" placeholder="Describe your responsibilities and achievements...">${escapeHtml(e.description || '')}</textarea>
                                            </div>
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
                                    <div class="education-item border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">School / University</label>
                                                <input type="text" name="edu_school[]" class="form-control form-control-sm" value="${escapeHtml(ed.institution || '')}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Degree</label>
                                                <select name="edu_degree[]" class="form-select form-select-sm">
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
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="form-control form-control-sm" value="${escapeHtml(ed.field_of_study || '')}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Graduation Year</label>
                                                <input type="number" name="edu_year[]" class="form-control form-control-sm" value="${escapeHtml(ed.graduation_year || '')}" min="1950" max="2030">
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
                            snap.experiences.forEach(function(e) {
                                const html = `
                                    <div class="experience-item border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Company Name</label>
                                                <input type="text" name="exp_company[]" class="form-control form-control-sm" value="${escapeHtml(e.company || '')}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Job Position</label>
                                                <input type="text" name="exp_position[]" class="form-control form-control-sm" value="${escapeHtml(e.position || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small fw-semibold text-muted">Start Date</label>
                                                <input type="date" name="exp_start_date[]" class="form-control form-control-sm" value="${escapeHtml(e.start_date || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3 exp-end-date-col" style="${e.is_current ? 'display: none;' : ''}">
                                                <label class="form-label small fw-semibold text-muted">End Date</label>
                                                <input type="date" name="exp_end_date[]" class="form-control form-control-sm" value="${escapeHtml(e.end_date || '')}">
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" ${e.is_current ? 'checked' : ''}>
                                                    <label class="form-check-label small fw-semibold text-muted">Currently Work Here</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <label class="small fw-semibold text-muted">Description & Achievements</label>
                                                    <div>
                                                        <button type="button" class="ai-assist-btn improve-desc-ai">
                                                            <i class="ti ti-wand"></i> Improve with AI
                                                        </button>
                                                        <button type="button" class="ai-assist-btn generate-bullets-ai" style="margin-left:8px;">
                                                            <i class="ti ti-list"></i> Generate Bullets
                                                        </button>
                                                    </div>
                                                </div>
                                                <textarea name="exp_description[]" class="form-control form-control-sm" rows="3" placeholder="Describe your responsibilities and achievements...">${escapeHtml(e.description || '')}</textarea>
                                            </div>
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
                                    <div class="education-item border rounded p-3 mb-3 position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.8rem;"></button>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">School / University</label>
                                                <input type="text" name="edu_school[]" class="form-control form-control-sm" value="${escapeHtml(ed.institution || '')}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Degree</label>
                                                <select name="edu_degree[]" class="form-select form-select-sm">
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
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="form-control form-control-sm" value="${escapeHtml(ed.field_of_study || '')}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small fw-semibold text-muted">Graduation Year</label>
                                                <input type="number" name="edu_year[]" class="form-control form-control-sm" value="${escapeHtml(ed.graduation_year || '')}" min="1950" max="2030">
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

        // Utility: Extract and clean raw markdown or blockquoted suggestions inside AI messages
        function extractResumeContent(text) {
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
            
            // Strip any remaining markdown markers for clean resume placement
            return extracted
                .replace(/^>\s*/gm, '') // remove leading blockquote carrots
                .replace(/[*#`]/g, '')  // strip asterisks, pound headers, and backticks
                .trim();
        }

        // AI Preview modal actions
        $('#aiApplyBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').html();
            const polished = extractResumeContent(raw);
            // store prev for undo
            const prev = $('#resume-summary').val();
            $('#resume-summary').data('prev', prev);
            $('#resume-summary').val(polished);
            $('#aiPreviewModal').modal('hide');
            toastr.success('Applied AI content to Professional Summary');
        });

        $('#aiCopyPlainBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text();
            const plain = extractResumeContent(raw);
            navigator.clipboard.writeText(plain).then(function() {
                toastr.success('Copied plain text to clipboard');
            }, function() {
                toastr.info('Copy failed — you can manually copy from the preview.');
            });
        });

        // Apply preview content to last-focused input/textarea (or reasonable fallback)
        $('#aiApplyActiveBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text();
            const polished = extractResumeContent(raw);

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                // store previous for undo
                lastFocusedTextarea.data('prev', lastFocusedTextarea.val());
                lastFocusedTextarea.val(polished);
                $('#aiPreviewModal').modal('hide');
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
                toastr.info('No active input was selected. Applied to first work experience description.');
                if (firstExpDesc.is(':visible') && firstExpDesc.offset()) {
                    $('html, body').animate({ scrollTop: firstExpDesc.offset().top - 120 }, 300);
                }
                firstExpDesc.focus();
                return;
            }

            // Otherwise default to summary
            const prev = $('#resume-summary').val();
            $('#resume-summary').data('prev', prev);
            $('#resume-summary').val(polished);
            $('#aiPreviewModal').modal('hide');
            toastr.info('No active input was selected. Applied to Professional Summary.');
            const targetSummary = $("#resume-summary");
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
        setTimeout(function () { window.print(); }, 650);
    });
</script>
<?= $this->endSection() ?>

