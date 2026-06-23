# Implementation Plan: New Reference Templates

**Date:** 2026-06-19
**Brand Colors:** #0D609E (primary blue), #F08F1A (accent orange)

---

## Overview

11 new HTML reference templates have been added to `Doc/UI and UX Doc/jobberrecruit HTML Files/`. These templates cover additional pages beyond the job and training pages already refactored.

---

## New Templates Analyzed

### 1. **jobberrecruit-job-apply.html** (Application Form Page)
**Purpose:** Dedicated job application page for submitting applications.

**Key Features:**
- Dark gradient hero with job title and company info
- Progress tracking for application steps
- Multi-section form (personal info, experience, skills, documents)
- Upload fields for CV and supporting documents
- Real-time validation feedback
- Success/error toasts
- Sticky progress bar showing completion percentage
- Milestone markers (₦200, ₦500) for paid plans

**Implementation Priority:** HIGH
**Estimated Effort:** 4-6 hours

---

### 2. **candidate-profile.html** (Candidate Profile Builder)
**Purpose:** Multi-step profile creation and editing for job seekers.

**Key Features:**
- Sticky progress bar (top: 70px) with milestone tracking
- Wallet balance chip showing credits
- Wallet credit milestones (₦200 at 60%, ₦500 at 80%)
- CV section cards with status indicators (complete/incomplete/optional)
- Dynamic form sections for:
  - Personal Information
  - Education
  - Work Experience
  - Skills (with proficiency levels)
  - Cover Letter
  - Portfolio/Links
- Milestone toast notifications
- Progress bar animations

**Implementation Priority:** HIGH
**Estimated Effort:** 6-8 hours

---

### 3. **employer-profile.html** (Employer Dashboard)
**Purpose:** Company profile management for employers.

**Key Features:**
- Same progress bar pattern as candidate profile
- Company information sections
- Job posting management interface
- Credit balance display
- Progress tracking for profile completion
- Form elements for company details
- Skills section with proficiency levels

**Implementation Priority:** HIGH
**Estimated Effort:** 5-7 hours

---

### 4. **employer-public-profile.html** (Public Company Profile)
**Purpose:** Public-facing company profile viewed by job seekers.

**Key Features:**
- Company branding with logo and banner
- Company overview and description
- Open positions section
- Featured jobs carousel
- Company statistics (jobs posted, applications received)
- Trust badges and verification indicators
- Contact information
- Social media links

**Implementation Priority:** MEDIUM
**Estimated Effort:** 4-5 hours

---

### 5. **cv-preview.html** (CV Preview)
**Purpose:** Real-time CV preview for candidates.

**Key Features:**
- Live CV preview as user types
- Professional CV template
- Editable sections (personal info, experience, education, skills)
- Preview with different view modes (print/export)
- Download/export options
- Template selection

**Implementation Priority:** MEDIUM
**Estimated Effort:** 3-4 hours

---

### 6. **post-a-job.html** (Job Posting Form)
**Purpose:** Form for employers to post new jobs.

**Key Features:**
- Form validation with real-time feedback
- Job details section (title, description, requirements)
- Location and salary fields
- Application method selection
- Company information
- Pricing tiers and credit deduction
- Preview and submit functionality

**Implementation Priority:** MEDIUM
**Estimated Effort:** 4-5 hours

---

### 7. **jobberrecruit-job-detail-confidential.html** (Confidential Job Detail)
**Purpose:** Job detail page for anonymous/secret employers.

**Key Features:**
- Same design as jobberrecruit-job-detail-named.html
- "Confidential Employer" branding
- No company logo display
- Alternative contact methods
- Privacy-focused UI elements

**Implementation Priority:** LOW
**Estimated Effort:** 2-3 hours

---

### 8. **jobberrecruit-job-detail-named.html** (Named Job Detail)
**Purpose:** Standard job detail page for non-anonymous employers.

**Key Features:**
- Hero section with job title and company info
- Company logo display
- Verified employer badges
- Job description, requirements, application guidelines
- Salary and location information
- Apply button with method selection
- Related jobs section
- Share functionality

**Implementation Priority:** LOW (Already partially done with view_job.php)
**Estimated Effort:** 2-3 hours

---

### 9. **jobberrecruit-category-oil-gas.html** (Category Landing Page)
**Purpose:** Specialized landing page for Oil & Gas industry.

**Key Features:**
- Industry-specific hero section
- Featured jobs in the category
- Industry statistics
- Benefits of working in Oil & Gas
- Related industries section
- Call-to-action for job seekers

**Implementation Priority:** MEDIUM
**Estimated Effort:** 3-4 hours

---

### 10. **jobberrecruit-location-lagos.html** (Location Landing Page)
**Purpose:** Specialized landing page for Lagos, Nigeria.

**Key Features:**
- Location-specific hero section
- Job count for Lagos
- Popular employers in Lagos
- Local job categories
- Cost of living information
- Transportation tips
- Related locations

**Implementation Priority:** MEDIUM
**Estimated Effort:** 3-4 hours

---

### 11. **jobberrecruit-job-apply.html** (Alternative Application Form)
**Purpose:** Alternative design for job application form (different layout).

**Key Features:**
- Similar functionality to jobberrecruit-job-apply.html
- Different visual layout
- Alternative form structure

**Implementation Priority:** LOW
**Estimated Effort:** 2-3 hours

---

## Implementation Phases

### Phase 1: High-Priority Pages (3-4 hours/day for 2 days)

**Day 1:**
1. **jobberrecruit-job-apply.html** → `home/apply_job.php`
   - Implement dark gradient hero
   - Add progress tracking bar
   - Create multi-section form structure
   - Add file upload functionality
   - Implement milestone notifications
   - Use brand colors

2. **candidate-profile.html** → `candidate/profile.php`
   - Implement sticky progress bar (top: 70px)
   - Create wallet balance chip
   - Build CV section cards with status indicators
   - Implement form grid layouts
   - Add skills proficiency selector
   - Add milestone toast notifications

**Day 2:**
3. **employer-profile.html** → `employer/dashboard.php`
   - Implement same progress bar pattern
   - Create company information forms
   - Add job posting management interface
   - Build skills section with proficiency levels
   - Add credit balance display

4. **employer-public-profile.html** → `employer/profile.php`
   - Implement public company profile layout
   - Add company branding section
   - Create open positions section
   - Add company statistics
   - Implement trust badges

---

### Phase 2: Medium-Priority Pages (3 hours/day for 2 days)

**Day 3:**
5. **post-a-job.html** → `employer/post-job.php`
   - Implement form validation
   - Create job details section
   - Add pricing tiers and credit calculation
   - Implement preview functionality
   - Add location/salary fields

6. **jobberrecruit-category-oil-gas.html** → `home/category.php` (oil-gas)
   - Create industry-specific hero
   - Add featured jobs grid
   - Build industry statistics section
   - Implement benefits/CTA section

7. **jobberrecruit-location-lagos.html** → `home/location.php` (lagos)
   - Create location-specific hero
   - Add job count display
   - Implement popular employers section
   - Add location-specific features

---

### Phase 3: Low-Priority Pages (2 hours/day for 1 day)

**Day 4:**
8. **jobberrecruit-job-detail-confidential.html** → Update `view_job.php`
   - Add confidential employer handling
   - Implement "Confidential Employer" branding
   - Adjust privacy-focused UI elements

9. **jobberrecruit-job-detail-named.html** → Update `view_job.php`
   - Add company logo display
   - Implement verified employer badges
   - Enhance company information section

10. **cv-preview.html** → `candidate/cv-preview.php`
    - Implement live preview functionality
    - Create CV template rendering
    - Add export/download options

11. **jobberrecruit-job-apply.html** → Update `apply_job.php`
    - Implement alternative form layout
    - Add progress tracking
    - Add file upload functionality

---

## Design Patterns to Apply

### 1. Navigation
- Sticky navbar with blur effect
- Logo on left, nav links in center, actions on right
- Mobile hamburger menu
- Dropdown menus for categories

### 2. Hero Sections
- Dark gradient background (same as homepage)
- Radial gradient accents
- Grid pattern overlay
- Section labels with icons

### 3. Progress Bars
- Sticky position (top: 70px or top: 86px for detail pages)
- Progress track with gradient fill
- Milestone markers with pulse animation
- Wallet balance chip
- Toast notifications for milestones

### 4. Cards
- White cards with border and shadow
- Border-left accent color (orange for featured)
- Rounded corners (10px)
- Hover lift effect

### 5. Forms
- Grid layouts (2 columns, 3 columns, 1 column)
- Form fields with focus states
- Character count for textareas
- File upload with preview
- Validation feedback

### 6. Badges
- Circular checkmark for verified
- Orange for featured
- Green for complete/in-progress
- Red for errors

### 7. Buttons
- Primary: brand blue with white text
- Accent: orange with dark text
- Outline: border with brand text
- Closed: grayed out with "x" icon

### 8. Typography
- Headings: Sora font (500/600/700/800)
- Body: Inter font (.82rem - .95rem)
- Small text: .72rem for labels
- Letter-spacing: -.02em for headings

### 9. Icons
- Use SVG sprite (inline)
- 13px, 15px, 16px, 18px sizes
- Consistent stroke width (1.5px - 2px)

### 10. Spacing
- Container: max-width 1160px, 20px padding
- Sections: 76px padding
- Card padding: 20px - 30px
- Gap between elements: 8px - 18px

---

## Code Quality Requirements

### 1. Brand Colors
- Use CSS custom properties: `--brand`, `--brand-dark`, `--brand-deep`, `--brand-light`
- Accent color: `--accent`, `--accent-dark`
- All hex values must match brand colors exactly

### 2. CSS Organization
- Use BEM-like naming (e.g., `.progress-bar`, `.progress-track`, `.milestone-marker`)
- Group related styles by section
- Use consistent spacing tokens
- Add comments for complex sections

### 3. JavaScript
- Use vanilla JavaScript (no frameworks)
- Event delegation where appropriate
- Add error handling
- Implement loading states

### 4. PHP Integration
- Keep all existing PHP logic intact
- Use CodeIgniter view helpers (`base_url()`, `site_url()`, `esc()`)
- Maintain database queries
- Preserve session handling

### 5. Responsive Design
- Mobile-first approach
- Breakpoints: 580px, 768px, 900px, 1160px
- Flexible grids (1fr, 1fr 1fr, etc.)
- Touch-friendly targets (min 44px)

---

## Testing Checklist

### Functional Testing
- [ ] All forms submit correctly
- [ ] File uploads work properly
- [ ] Progress bars update correctly
- [ ] Milestone notifications appear
- [ ] Navigation works on all devices
- [ ] Forms validate inputs
- [ ] Success/error messages display

### Visual Testing
- [ ] All brand colors match specification
- [ ] No hardcoded hex values from reference
- [ ] SVG icons display correctly
- [ ] Responsive layouts work
- [ ] Animations are smooth
- [ ] Forms are accessible

### Performance Testing
- [ ] Page load times < 2 seconds
- [ ] No console errors
- [ ] No layout shifts
- [ ] Images are optimized

---

## Estimated Total Effort

- **Phase 1:** 10-11 hours (2 days)
- **Phase 2:** 10-11 hours (2 days)
- **Phase 3:** 8-10 hours (1 day)
- **Total:** 28-32 hours (4-5 days)

---

## Notes

1. **Priority Reordering:** Adjust priority based on actual page usage statistics
2. **Code Reuse:** Share CSS classes between similar pages (e.g., progress bars)
3. **Incremental Testing:** Test each page immediately after implementation
4. **User Feedback:** Gather feedback during implementation to adjust design
5. **Documentation:** Keep detailed comments in code for future maintenance

---

## Next Steps

1. Confirm implementation order with user
2. Start Phase 1, Day 1: jobberrecruit-job-apply.html
3. Review and refine as needed
4. Move to next pages in priority order
5. Test all refactored pages together
6. Final polish and optimization