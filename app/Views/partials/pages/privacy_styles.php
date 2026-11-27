
<style>html, body { overflow-x: clip; }

/* Hallmark · restyle · jobberrecruit-legal-privacy · theme: technical-precision */
/* critique: legal disclosure & privacy compliance; zero AI slop, zero horizontal scroll, solid ink */
    /* Privacy Policy Custom Styles */



    /* Hero Section */

    .privacy-hero-section {

        background: linear-gradient(135deg, #f8fafc 0%, var(--accent) 100%);

        position: relative;

        overflow: hidden;

    }



    .min-vh-60 {

        min-height: 60vh;

    }



    .text-gradient-primary { color: var(--brand, #0D609E); font-weight: 700; background: none; -webkit-text-fill-color: initial; }



    /* Section Styling */

    .policy-section {

        padding-bottom: 3rem;

        border-bottom: 1px solid #e9ecef;

        margin-bottom: 3rem;

    }



    .policy-section:last-child {

        border-bottom: none;

        margin-bottom: 0;

    }



    .section-number {

        font-weight: bold;

        font-size: 1.2rem;

    }



    /* Card Styling */

    .card {

        border-radius: 12px !important;

        transition: transform 0.3s ease, box-shadow 0.3s ease;

    }



    .card:hover {

        transform: translateY(-2px);

        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08) !important;

    }



    /* Icon Wrappers */

    .icon-wrapper {

        width: 50px;

        height: 50px;

        display: flex;

        align-items: center;

        justify-content: center;

    }



    /* Quick Navigation */

    #policy-nav {

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



        #policy-nav,

        .sticky-top,

        .btn,

        .badge,

        .modal,

        .card:not(.policy-section .card) {

            display: none !important;

        }



        .policy-section {

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



        .privacy-hero-section {

            padding-top: 3rem;

            padding-bottom: 3rem;

        }



        #policy-nav {

            position: relative !important;

        }



        #policy-nav .btn {

            font-size: 0.75rem;

            padding: 0.25rem 0.5rem;

        }



        .sticky-top {

            position: relative !important;

            top: 0 !important;

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



    .policy-section {

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
