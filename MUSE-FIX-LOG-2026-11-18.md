# JobberRecruit — Fix Log, 2026-11-18

Fixes applied to the PC working tree (`C:\Users\hephz\Documents\CODEBASE\Jobberrecruit`) in the
`sandbox copy of the exact source you FTP-deployed`, then copied back. Your pre-fix versions of
every changed file are backed up on the PC under `muse-fix-backup-2026-11-18\` (same relative paths).
All 20 changed files pass `php -l`. Detailed per-area notes: `jobberrecruit-fix/notes/` in Muse's workspace.

## What to deploy
FTP the 20 files listed at the bottom (paths relative to the project root) to the server, overwriting.

## Root causes and fixes, by area

### AI Career Tools
- **Salary Simulator deadlock** — the view called a JS helper `timeHHMM()` that was defined nowhere.
  Every message render threw, after the composer was disabled → counter-offer vanished, no reply,
  "End" refused. Defined the helper; hardened the send flow so a render error can never lock the composer.
- **Mock Interview "Unknown column 'completion_key'"** — the column comes from migration
  `2026-11-02-200000_AddAiCompletionKeys.php`, which never ran on production MySQL (FTP deploys don't
  run migrations), while your SQLite test DB always had it. The dedupe queries ran unguarded and the raw
  SQL error reached the user. Code now checks the column exists before using it, catches/logs failures,
  and never surfaces raw DB errors. Saving works with or without the migration.
- **Career Coach contradictions** — "AI Confidence 82%" was hardcoded in the view; "Average practice
  score 75%" was an experience-based estimate shown with zero sessions; readiness used inflated score
  floors; the demand % was literally career-health + 10; the streak (days) was labelled "weeks".
  All figures are now computed from real data or shown as "not enough data yet"; canned answers are
  built from the scores actually on the page.

### Jobs listing (`Home.php` only)
- **Duplicate cards + wrong total** — the listing used a raw 4-way JOIN with no de-duplication
  (fan-out rows rendered as repeated jobs; the total counted the same inflated rows). Set is now
  deduplicated by job ID and the total derives from the distinct set.
- **Filter counts (32 ≠ 27)** — facet counts ran unfiltered queries over the whole `jobs` table
  (closed/draft/expired included). They now use the listing's exact visibility rules.
- **Search ignored** — controller read only `keywords`; the hero form submits `q`, links use `search`.
  All three now resolve to the filter and the box repopulates.
- **Jobs Viewed 0 → 254** — genuine detail views were never logged to `job_clicks`; the only writer
  was `startApplication()`, inserting on every hit (the apply redirect loop minted hundreds in one day).
  Views now log once per job per hour under the existing cooldown; the apply endpoint is gated by the
  same cooldown. Historical bogus rows remain in the DB (see Owner actions).

### Courses & money
- **₦0.00 / "Free" / wrong-channel course record** — the current wallet purchase flow (one locked
  transaction: debit + enrollment at course price + payment row with `payment_method='wallet'`) cannot
  produce that state; the broken record (22:22 WAT) predates your 23:21 fix upload and the retest made
  no new course purchase (cheapest was above the ₦10,000 approval cap). Display defects fixed anyway:
  My Courses now derives Paid/Free from the course price (the ₦8,500 course renders "₦8,500.00 / Paid"),
  and the acknowledgement page now honours the `?reference=` its redirect carries. A fresh purchase at
  retest will verify recording end-to-end.
- **₦15,000 ledger gap** — data-only: the wallet ledger reconciles exactly
  (₦500,000 + ₦500 − ₦2,000 − ₦22,000 = ₦476,500). The Transactions page counted an HR-course
  enrollment (₦15,000, no payment row, no wallet debit) as spend. `transactions()` now counts only
  payment/debit-backed enrollments as spend; `receipt()` no longer invents "Paystack / Card" — channel
  comes from the actual payment record or shows "Not recorded".
- **B12 Final Assessment** — availability = a `test_questions` bank passing validation; the Figma
  course has none in production data (admin must publish one — code can't). Fixed around it:
  completion status is now consistent (certificate-holders = Completed everywhere), the 100% badge
  says "Assessment Pending" only when a valid bank exists (otherwise "Lessons Complete"), and the
  classroom copy no longer claims an assessment is coming when none exists.
- **Course video** — `getYoutubeEmbedUrl()` returned null for `/embed/`, `/shorts/`, `/live/`, `/v/`,
  nocookie URLs and bare IDs (what admins paste from YouTube's embed dialog). Parser rewritten and
  verified 13/13. The Figma Lesson 1 video itself is refused by YouTube (deleted/private/embed-disabled)
  — the admin must replace that video.
- **Bonus** — `PaymentFulfillmentService` used `insert()`'s return as a record ID; switched to
  `getInsertID()` for enrollments, subscriptions and CV reviews (CV checkout could redirect to review_id=1).
- Removed a hardcoded fake "Suggested Course" card with an invented "3x visibility" claim from My Courses.

### Candidate core
- **B11 Certification save** — every layer (fields, model, migration, date helper) checks out against
  the working Education/Experience paths. The real defect: the delete+reinsert sync ran
  *unconditionally* — any profile submission without cert fields silently wiped stored certs while
  reporting success, and insert failures swallowed the real DB error. Sync now runs only when cert
  fields were posted, and failures log the model's actual errors. If it still reproduces after deploy,
  the log will show the real MySQL error; remaining suspect is production schema drift on
  `job_seeker_certifications`.
- **Profile Visibility toggle** — the endpoint ignored `update()`'s result and reported success with
  the *intended* value, so a silent write failure looked like success. It now re-reads the row, reports
  the stored value, and fails honestly on mismatch; the settings page sets the checkbox from the
  server's returned value.
- **Unsave / Alert Delete dead** — both were gated by the native `confirm()` dialog (which automated
  browsers auto-dismiss, and which is fragile generally); the endpoints were sound. Replaced with a
  two-step in-page confirmation ("click again to confirm") in Saved Jobs, Notifications, and the
  Job Alerts page; Unsave also now sends CSRF in the body and shows a success toast.
- **Application status mismatch** — `applications.php` labelled both `reviewed` and `shortlisted` as
  "Shortlisted" while the dashboard printed the raw status ("Reviewed"). One canonical label map
  (pending→Pending Review, reviewed→Reviewed, shortlisted→Shortlisted, …) is now applied in the
  dashboard, applications list and application detail views. The "Pending Review" *notification* is a
  historical event record from when the application was pending — not a live status, left as-is.
- **Resume Builder** — (a/b) the Personal Info section claimed `aria-expanded=true` but lacked the
  `open` class, and a main-script failure could strand all sections: fixed, plus a fallback toggler.
  (c) real crash: `renderLivePreview()` referenced `cfg` inside its own initializer — any unmapped
  template key killed the preview. (d) Tailor: `AiService::generate()` capped output at 1,500 tokens,
  truncating the resume JSON mid-stream; the cap is now a parameter and Tailor requests 4,000, plus
  JSON-extraction recovery. (e) PDF export depended on Gotenberg/Browsershot, which don't exist on
  cPanel — added a Dompdf fallback (needs the package, see Owner actions). (f) Tailor dropdown
  duplicates = duplicate `jobs` rows, same data issue as /jobs. (g) two minting paths created a new
  resume on every save/click with no dedupe — both now reuse an identical existing resume.
  (h) Auto-fill set field values without firing events, so autosave never ran — it now routes through
  the normal Save action.

## Owner actions (server / data — code cannot do these)
1. **Run `php spark migrate` on the server once** — applies `AddAiCompletionKeys` (and any other
  pending migrations). The code tolerates its absence, but completion-key duplicate protection only
  works once the column exists.
2. **Set `CI_ENVIRONMENT = production`** in the server `.env` — production currently serves raw DB
  errors and stack traces to visitors (seen live twice). This closes that whole class of leak.
3. **Add `dompdf/dompdf`** to production (composer require, or upload its vendor folder) — activates
  the resume PDF fallback. Until then PDF export cannot work on cPanel.
4. **Figma course**: publish a final-assessment question bank (admin), and replace the Lesson 1
  YouTube video (YouTube refuses the current one).
5. **Data decisions**: the historical ₦0 SMM record and the unbacked ₦15,000 HR enrollment are data
  artefacts — reconcile manually or leave; no retroactive debit was invented. Optionally delete the
  bogus `job_clicks` rows minted by the apply-loop (the displayed Jobs Viewed total won't drop
  otherwise); a diagnostic query is in the jobs-listing notes.
6. If B11 still fails after deploy, check the server error log — it now records the real MySQL error —
  and verify the `job_seeker_certifications` table shape against the migration.

## Changed files (20)
```
app/Controllers/CareerToolsController.php
app/Controllers/ElearningController.php
app/Controllers/Home.php
app/Controllers/JobSeekerController.php
app/Controllers/ResumeController.php
app/Services/AiService.php
app/Services/PaymentFulfillmentService.php
app/Views/candidate/application_view.php
app/Views/candidate/applications.php
app/Views/candidate/classroom.php
app/Views/candidate/dashboard.php
app/Views/candidate/job_alerts.php
app/Views/candidate/my_courses.php
app/Views/candidate/notifications.php
app/Views/candidate/saved_jobs.php
app/Views/candidate/settings.php
app/Views/candidate/career-tools/career-advice.php
app/Views/candidate/career-tools/mock-interview-session.php
app/Views/candidate/career-tools/salary-negotiation.php
app/Views/candidate/resume/builder.php
```
