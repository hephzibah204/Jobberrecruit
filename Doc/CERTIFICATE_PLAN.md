# Certificate Management Improvement Plan (JobberRecruit)

## 1. Overview
Transform the current certificate system into a "Superpower" engine that supports multiple output formats (PDF, JPEG, HTML) with zero-effort automation for students and admins.

## 2. Core Features

### 2.1 Unified Generation Engine
*   **PDF Mode (Standard):** Uses `Dompdf` for high-fidelity A4 landscape certificates.
*   **JPEG Mode (New):** Uses PHP's `GD` library to overlay dynamic text and images (QR, Signatures) directly onto a high-resolution JPG background. This ensures the output is a single, social-media-friendly image file.
*   **HTML Mode (Advanced):** Allows full custom CSS/HTML design for unique branding.

### 2.2 The "Smart Match" Data Mapping
The system will automatically inject and position the following data points regardless of the template mode:
*   `{{name}}` -> Student Full Name (as per their profile or certificate settings).
*   `{{course}}` -> Full Course Title.
*   `{{date}}` -> Date of Issuance (localized).
*   `{{code}}` -> Unique Serial Number (e.g., `CERT-A1B2-C3D4`).
*   `{{qr_code}}` -> A scannable QR code linking to the verification portal.
*   `{{signature}}` -> The digital signature of the authorized trainer.

### 2.3 Visual "Super-Editor" Enhancements
*   **Live Iframe Preview:** Real-time rendering as you edit HTML/CSS or drag elements in the builder.
*   **Coordinate-Based Image Layering:** For JPEG templates, admins can click on the background image to "set" the position for Name, Course, etc.
*   **Font Selection:** Support for Google Fonts or uploaded custom TTF files for JPEG rendering.

### 2.4 Simplified Process & Ease of Use
*   **One-Click Default:** A professionally designed "JobberRecruit Default" template applied to all courses by default.
*   **Template Library:** Save and reuse templates across different course categories (e.g., "Tech Courses", "Soft Skills").
*   **Direct Download Buttons:** Students see "Download PDF" and "Download JPG" buttons in their dashboard.

## 3. Technical Implementation Strategy

### Phase 1: The "JPEG Renderer"
*   Implement a `CertificateGenerator` utility class.
*   Use `imagecreatefromjpeg()` to load backgrounds.
*   Use `imagettftext()` for high-quality typography.
*   Use `imagecopy()` to merge QR codes and Signatures.

### Phase 2: Live Preview Upgrade
*   Add a "Preview" button in the Admin Editor that opens a modal with a real-time render using the admin's own name as a test.
*   Implement `html2canvas` for quick frontend image generation during the design phase.

### Phase 3: Verification Portal
*   Create a public-facing `/verify/{code}` page.
*   Display certificate details, student name, and a "Verify Authenticity" badge.
*   Allow employers to download the original certificate directly from the verification page.

## 4. Proposed Database Updates
*   Enhance `certificate_templates` table to store font preferences and high-res background paths.
*   Add `font_family` and `font_size` to the `layout_json`.

---
*Plan drafted by Gemini CLI - June 2026*
