
<style>html, body { overflow-x: clip; }

/* Hallmark · restyle · jobberrecruit-legal-terms · theme: technical-precision */
/* critique: legal disclosure & privacy compliance; zero AI slop, zero horizontal scroll, solid ink */
    /* Terms of Service Custom Styles */



    /* Hero Section */

    .terms-hero-section {

        background: linear-gradient(135deg, #f8fafc 0%, var(--accent) 100%);

        position: relative;

        overflow: hidden;

    }



    .min-vh-60 {

        min-height: 60vh;

    }



    .text-gradient-primary { color: var(--brand, #0D609E); font-weight: 700; background: none; -webkit-text-fill-color: initial; }



    .bg-primary {

        background: linear-gradient(90deg, var(--brand) 0%, var(--brand) 100%);

    }



    /* Section Cards */

    .terms-section-card .card {

        border-radius: 12px !important;

        overflow: hidden;

    }



    .card-header {

        border-bottom: none !important;

    }



    .section-number {

        font-weight: bold;

        font-size: 1.2rem;

    }



    /* Definition Cards */

    .definition-card {

        transition: transform 0.3s ease, box-shadow 0.3s ease;

        height: 100%;

    }



    .definition-card:hover {

        transform: translateY(-5px);

        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;

    }



    /* Accordion Styling */

    .accordion-item {

        border: 1px solid #e9ecef !important;

        border-radius: 8px !important;

        margin-bottom: 10px;

    }



    .accordion-button {

        font-weight: 600;

        color: #1a1a1a;

        background-color: white;

        border-radius: 8px !important;

    }



    .accordion-button:not(.collapsed) {

        color: var(--brand);

        background-color: rgba(10, 88, 202, 0.05);

        box-shadow: none;

    }



    .accordion-button:focus {

        box-shadow: none;

        border-color: var(--brand);

    }



    /* Liability Cards */

    .border-danger.border-opacity-25 {

        border-color: rgba(220, 53, 69, 0.25) !important;

    }



    .border-warning.border-opacity-25 {

        border-color: rgba(255, 193, 7, 0.25) !important;

    }



    /* Quick Navigation */

    #terms-nav {

        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);

    }



    /* Sidebar Navigation */

    .nav-link.scroll-to {

        color: #4a5568;

        border-left: 3px solid transparent;

        padding-left: 1rem;

        transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;

    }



    .nav-link.scroll-to:hover {

        color: var(--brand);

        border-left-color: var(--brand);

        background-color: rgba(102, 126, 234, 0.05);

    }



    .nav-link.scroll-to.active {

        color: var(--brand);

        border-left-color: var(--brand);

        background-color: rgba(102, 126, 234, 0.05);

        font-weight: 600;

    }



    /* Table Styling */

    .table th {

        background-color: #f8fafc;

        font-weight: 600;

        border-top: none;

    }



    .table td {

        vertical-align: middle;

    }



    /* Background Elements */

    .bg-gradient-primary {

        background: linear-gradient(135deg, var(--accent) 0%, var(--accent) 100%);

    }



    /* Print Styles */

    @media print {



        #terms-nav,

        .sticky-top,

        .btn,

        .badge,

        .modal,

        .card:not(.terms-section-card .card) {

            display: none !important;

        }



        .terms-section-card {

            break-inside: avoid;

        }



        .card {

            border: 1px solid #dee2e6 !important;

            box-shadow: none !important;

        }

    }



    /* Responsive Design */

    @media (max-width: 768px) {

        .display-4 {

            font-size: 2.5rem;

        }



        .terms-hero-section {

            padding-top: 3rem;

            padding-bottom: 3rem;

        }



        #terms-nav {

            position: relative !important;

        }



        #terms-nav .btn {

            font-size: 0.75rem;

            padding: 0.25rem 0.5rem;

        }



        .sticky-top {

            position: relative !important;

            top: 0 !important;

        }



        .accordion-button {

            font-size: 0.9rem;

            padding: 0.75rem 1rem;

        }

    }



    /* Custom Scrollbar for Sidebar */

    .nav.flex-column::-webkit-scrollbar {

        width: 4px;

    }



    .nav.flex-column::-webkit-scrollbar-track {

        background: #f1f3f5;

    }



    .nav.flex-column::-webkit-scrollbar-thumb {

        background: #adb5bd;

        border-radius: 2px;

    }



    /* Hover Effects */

    .hover-lift {

        transition: transform 0.3s ease, box-shadow 0.3s ease;

    }



    .hover-lift:hover {

        transform: translateY(-5px);

        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;

    }



    /* Animation */

    @keyframes fadeIn {

        from {

            opacity: 0;

            transform: translateY(20px);

        }



        to {

            opacity: 1;

            transform: translateY(0);

        }

    }



    .terms-section-card {

        animation: fadeIn 0.6s ease-out;

    }


h1, h2, h3, .display-4, .display-5 { text-wrap: balance; overflow-wrap: break-word; }

/* Hallmark · Gate 26b Tactile micro-depression */
.btn:active, .legal-nav-link:active {
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
