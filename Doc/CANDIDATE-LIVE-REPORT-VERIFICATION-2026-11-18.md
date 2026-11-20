# Candidate dashboard report verification — 18 November 2026

Scope: review against the current working tree, isolated SQLite integration tests, and Chromium fixtures. Existing unrelated edits were retained. No deployment or live account reconciliation was performed.

## Confirmed defects corrected

- Resume Builder: unescaped font name broke JavaScript parsing. Fixed parsing, duplicate Auto-fill click handling, PDF/Word default anchor navigation before save, export menu handlers, and explicit download URL separator.
- Settings: unclosed submit listener/conditional broke the page script. Preferences now have an explicit POST action even when JavaScript fails.
- Profile: removed an orphaned experience fragment outside its template. Failed profile/experience/education writes now return an error and roll back instead of silently reporting success.
- Applications: guest-only rejection redirects to job detail instead of the referrer; empty/internal method values resolve to the form. Removed fabricated pagination.
- Aptitude: optional referral-column absence no longer crashes practice starts. Failed attempt creation returns a safe message.
- Purchases: paid course enrolments retain the charged amount inside the purchase transaction. Receipt and acknowledgement amounts/channels come from the owner's payment order, not query parameters or current catalogue prices.
- Transactions: normalised array/object access, authenticated wallet lookup, successful-only totals, wallet funding treated as a credit, and duplicate representations collapsed by reference with payment records preferred.
- Courses: completion labels follow recorded certification status; descriptions render readable text; technical video-source labels replaced; unsupported study-time and accuracy values removed. YouTube iframe now explicitly sends the origin referrer.
- Career advice: removed invented completed milestones, nonexistent course recommendations, and canned replies quoting contradictory scores.
- Dashboard: removed nonexistent suggested course. Mock interview pace label now identifies elapsed response pace rather than speech speed.
- Salary sorting: explicit salary order takes precedence over featured ranking, with unknown/zero salaries last.
- Webinars: removed the unsupported weekly-live-session promise.

## Verified existing behaviour

Chromium fixtures at 390px confirm Unsave and alert Delete submit after accepting their confirmation dialogs. These handlers did not reproduce as dead controls in the current source. Existing AI checks cover authenticated/CSRF requests, session persistence retry, stable completion keys, transcripts, report printing, and resume rollback. These are local checks, not successful live AI provider calls.

## Remaining deployment/data verification

- Deploy and retest authenticated application, resume, salary negotiation, interview, profile, course purchase, and settings flows against the live host.
- Confirm production environment configuration and pending database migrations on the host. Local exception handling does not establish that production debug mode is disabled.
- Reconcile the reported historical purchase reference against payment and wallet records before considering any balance correction. No historical amounts were invented or retroactively debited.
- Verify live duplicate job records/counts, stale employer application statuses and notifications, invalid job locations/categories/external URLs, assessment categories, certificate QR delivery, ad-gate behaviour, and genuine device layouts. These depend on deployed data/assets or browser state and are not certified by source inspection.
- YouTube playback needs a live-host check; the iframe referrer correction addresses an identifiable configuration issue but cannot establish that the video owner permits embedding.
- Existing duplicate resumes and the test account's artefacts were retained.

## Validation

Run integration tests with the isolated bootstrap and exclude the network group; run candidate-dashboard-regression.cjs, ai-tools-client.cjs, ai-tools-regression.php, profile-date-validation.php, assessment-start-validation.php, and receipt-render.php. The isolated bootstrap deliberately does not load the local environment file.

## Follow-up finishing-run fixes

- B10/B11: saved certification month names are accepted by date validation; profile_completion is writable by the model. Real migrated-database controller tests verify education and certification persist together, and invalid changes retain prior records. Certification expiry inputs retain row alignment when a preceding credential does not expire.
- B12: test_questions is now writable by CourseModel. Generated, imported, and manually edited question banks share validation and checked persistence. Round-trip tests publish a bank and earn one certificate by passing it; malformed banks cannot overwrite it and unpublished banks cannot issue certificates or consume attempts. Existing discarded question banks must be republished by an administrator after deployment.
- Course listings now show Assessment Pending at 100% lesson progress until certificate completion is recorded. Unpublished assessments are identified before candidates enter an empty test.
- Job alerts can be edited using the existing form, with cancel/reset and ownership-checked updates. Frequency, time, location and channel are validated. Tests reject updates to another candidate's alert.
- Both application handlers now share method normalisation, preventing legacy internal values from bouncing between handlers.
- Aptitude average scores render their final values immediately instead of displaying conflicting animation intermediates.
- Premium FAQ now explains that access depends on the selected plan's listed features.

These changes remain local. No live account balance, course content, subscription, or test artefacts were changed.

Follow-up verification: 77 integration tests (215 assertions) and 2 application eligibility tests (14 assertions) passed; classroom assessment, 390px candidate browser regression, and date validation checks passed. Network-dependent tests were excluded.

## Seventeen-item dashboard audit follow-up

| Finding | Current result |
| --- | --- |
| 1. Incomplete profile access | Earlier completion redirect already removed; accounts without any profile row now also receive the dashboard and checklist. |
| 2. Notification column | Layout uses candidate_id through the model's unread and recent-notification methods. |
| 3. Recommendation location | Existing states join and location fallback verified. |
| 4. Recommendation eligibility | Existing open/approved/deadline filters verified against live, closed, expired and unapproved fixtures. The project's active job status is open. |
| 5. Invitation badge | Uses the same application-linked recipient resolution and pending rules as the dashboard. Optional legacy email/code columns are handled without unknown-column errors. |
| 6. Main landmark | Child content is a closed div; the layout provides the sole main landmark and main-content ID. |
| 7. Empty greeting | Whitespace-only names fall back to Professional. |
| 8-9. Donut math | Normalised SVG path length is 100; the total painted arc equals the displayed overall percentage, including 0%, 10% and 100%. |
| 10. Bar labels | Peak 1/2 values already scale to the full chart height, so the reported low-count scenario did not reproduce. Zero values no longer draw artificial four-pixel bars; short-bar labels have a safe outside position and contrasting colour. |
| 11-12. Profile ring | Relative positioning centers the label; SVG starts at twelve o'clock. |
| 13. Invitation overflow | Existing wrapping verified with long unbroken titles at 320px and 390px. |
| 14. Checklist deep links | Arrival hashes and subsequent hash changes open the target profile accordion. |
| 15. View database fallback | Removed the duplicate checklist fallback and its queries; controller/entity checklist remains authoritative. |
| 16. Fake course suggestion | Replaced with an honest training catalogue link and neutral copy. |
| 17. Application status colours | Reviewed/viewed, shortlisted, interview, hired and rejected have explicit styles. |

New validation covers actual rendered dashboard HTML at 320/390/768/1440px, chart arcs, landmarks, greeting, badge styles, profile-ring alignment, action containment, and deep-link behaviour. Isolated controller/model checks cover dashboard access without a profile, recommendation filtering/location, notification identity and application-linked invitation counts. Changes remain undeployed.
