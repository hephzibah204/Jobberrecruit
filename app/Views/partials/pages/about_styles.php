
<style>
/* Hallmark · restyle · jobberrecruit-about · theme: technical-precision */
/* critique: story & mission page; zero AI slop, zero horizontal scroll, solid ink */
    /* =========================================================

   ABOUT PAGE – JOBBER RECRUIT STYLES

   ========================================================= */

    html,

    body {

        max-width: 100%;

        overflow-x: clip;

    }





    /* Hero Section */

    .about-hero-section {

        background: linear-gradient(135deg, #fff8f0 0%, #ffffff 100%);

        position: relative;

        overflow: hidden;

    }



    .about-hero-section::before {

        content: '';

        position: absolute;

        top: -50%;

        right: -50%;

        width: 100%;

        height: 100%;

        background: radial-gradient(circle, rgba(240, 137, 14, 0.05) 0%, transparent 70%);

    }



    .min-vh-70 {

        min-height: 70vh;

    }



    /* Gradient Text */

    .text-gradient-primary { color: var(--brand, #0D609E); font-weight: 700; background: none; -webkit-text-fill-color: initial; }



    /* Cards & Hover Effects */

    .hover-lift {

        transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;

        border: 1px solid rgba(240, 137, 14, 0.1);

    }



    .hover-lift:hover {

        transform: translateY(-8px);

        box-shadow: 0 20px 40px rgba(240, 137, 14, 0.1) !important;

        border-color: rgba(240, 137, 14, 0.2);

    }



    /* Icon Wrapper */

    .icon-wrapper {

        width: 60px;

        height: 60px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 12px;

        transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;

    }



    .card:hover .icon-wrapper {

        transform: scale(1.1) rotate(5deg);

    }



    /* Story Timeline */

    .story-timeline {

        position: relative;

        padding-left: 30px;

    }



    .story-timeline::before {

        content: '';

        position: absolute;

        left: 10px;

        top: 0;

        bottom: 0;

        width: 2px;

        background: linear-gradient(to bottom, var(--accent), var(--brand));

        opacity: 0.3;

    }



    .timeline-item {

        position: relative;

    }



    .timeline-badge {

        position: absolute;

        left: -30px;

        width: 20px;

        height: 20px;

        border-radius: 50%;

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        font-weight: bold;

        font-size: 12px;

    }



    .timeline-content {

        padding-left: 20px;

    }



    /* Platform Icons */

    .platform-icon i {

        font-size: 3rem !important;

    }



    .platform-icon.whatsapp i {

        color: #25D366;

    }



    .platform-icon.telegram i {

        color: #0088cc;

    }



    .platform-icon.instagram i {

        color: #E4405F;

    }



    .platform-icon.twitter i {

        color: #000000;

    }



    /* Counter Animation */

    .counter { font-variant-numeric: tabular-nums; font-weight: 800; color: var(--accent, #F08F1A); }



    /* Primary Color Override */

    .bg-primary {

        background: linear-gradient(135deg, var(--accent) 0%, #e67e00 100%) !important;

    }



    .text-primary {

        color: var(--accent) !important;

    }



    .btn-primary {

        background: var(--accent);

        border-color: var(--accent);

    }



    .btn-primary:hover {

        background: #d87b0d;

        border-color: #d87b0d;

    }



    /* Fade-in Animation */

    .fade-in { opacity: 1;

        transform: translateY(30px);

        transition: opacity 0.6s ease, transform 0.6s ease;

    }



    .fade-in.visible {

        opacity: 1;

        transform: translateY(0);

    }



    /* Blockquote Styling */

    blockquote {

        border-left-color: var(--accent) !important;

    }



    blockquote.border-info {

        border-left-color: var(--brand) !important;

    }



    /* Badge Styles */

    .badge.bg-primary {

        background-color: rgba(240, 137, 14, 0.1) !important;

        color: var(--accent);

    }



    /* Section Spacing */

    section {

        padding: 5rem 0;

    }



    /* =========================================================

   RESPONSIVE ADJUSTMENTS

   ========================================================= */

    @media (max-width: 768px) {

        .display-4 {

            font-size: 2.5rem;

        }



        .display-5 {

            font-size: 2rem;

        }



        .display-6 {

            font-size: 1.75rem;

        }



        .counter { font-variant-numeric: tabular-nums; font-weight: 800; color: var(--accent, #F08F1A); }



        section {

            padding: 3rem 0;

        }

    }



    @media (max-width: 576px) {

        .btn-lg {

            padding: 0.75rem 1.5rem;

            font-size: 1rem;

            width: 100%;

            margin-bottom: 10px;

        }



        .d-flex.gap-3 {

            flex-direction: column;

            align-items: center;

        }

    }


h1, h2, h3, .display-4 { text-wrap: balance; overflow-wrap: break-word; }

/* Hallmark · Gate 26b Tactile micro-depression */
.btn:active, .platform-card:active {
  transform: translateY(1px);
}

/* Hallmark · Gate 27 Prefers Reduced Motion */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
</style>
