# Additional bug review — 16 October 2026

This review covers additional paths in the current root `app` source, separate from the original 16 findings. No application fixes were made in this review. The workspace is receiving other changes, so findings describe the inspected snapshot. One suspected employer-required flag bypass was corrected in the latest source during review and is deliberately excluded below.

P1 means a security, payment, or assessment-integrity defect. P2 means a functional workflow failure.

## 1. P1 — Assessment scores can be forged, including scores above 100%

**Evidence:** `app/Controllers/AptitudeController.php:575` (`saveAnswer`) and `:604` (`submitTest`).

The answer endpoint accepts any question ID without checking the attempt's assigned question list. Scoring then iterates all stored answers and accepts the first selected option if that option is correct anywhere in the database. It does not verify that the option belongs to the answer's question. The denominator still counts only assigned questions.

**Reproduced:** an isolated two-question practice attempt accepted three answers, all using the correct option from the third, unassigned question. Submission persisted `num_correct=3`, `num_total=2`, and `score_pct=150`. The same grading block serves official assessments, whose scores are subsequently written to candidate results.

**Fix:** validate assigned-question membership and option ownership on save and again when grading. Grade exactly one answer per assigned question, enforce the question's answer type, and reject invalid records rather than merely capping the resulting score.

## 2. P1 — Private employer assessments can be started without an invitation

**Evidence:** `app/Controllers/AptitudeController.php:145` (`launchBySlug`) and `:452` (`startAttempt`); public candidate routes in `app/Config/Routes.php:80` and `:605`.

The catalog hides employer-owned tests, but the start handlers only check the requested slug/ID and active status. They do not require `employer_id` to be null or validate an invitation for an employer-owned test. A logged-in user who knows a private test ID or slug can start it as practice. The result handler reveals correct answers and explanations after practice submission.

**Impact:** private screening material and its answers can be harvested before the invited assessment, and unrelated candidates can access another employer's test.

**Fix:** restrict general start endpoints to global tests. Require an owned, unexpired invitation for every employer-owned test, including practice attempts and result access.

## 3. P1 — Invitation ownership mixes user IDs with job-seeker profile IDs

**Evidence:** `app/Controllers/AptitudeController.php:814–845`; `app/Models/AptitudeTestInvitationModel.php:91–96`; current invitation creation in `app/Controllers/EmployerController.php` writes the candidate's user ID.

An invitation's `candidate_id` is accepted if it matches either the logged-in user's ID or the ID of their `job_seekers` row. These are independent numeric ID spaces. For example, an invitation for user 42 can also match a different user whose job-seeker profile ID is 42. Email mismatch does not veto either numeric match. The handler can then permanently relink the invitation to the wrong user. Candidate invitation listing uses the same ambiguous comparison.

**Impact:** the wrong account can see an invitation in its test centre and potentially take/relink it. This requires an actual ID collision, not just arbitrary knowledge of an invitation token.

**Fix:** use one canonical user foreign key. Migrate legacy profile IDs using unambiguous relationship/email evidence, and require all present ownership fields to agree.

## 4. P1 — Candidate subscription verification trusts a plan selected outside the payment

**Evidence:** `app/Controllers/CandidateSubscriptionController.php:145–160` and `:172–177`; route `candidate/subscription/verify`.

Once Paystack reports success, the callback selects the plan from the session or a query-string `plan_id`. It does not bind that plan to the payment's metadata, customer, paid amount, currency, or the stored pending reference. `activatePlan` only checks that the plan exists; it does not require a candidate plan.

**Impact:** a successful, previously unused transaction reference for another purchase/account can be submitted with a different plan ID. A low-value payment can consequently be applied to a higher-value or non-candidate plan. The payments table's unique reference protects against some replays but does not establish ownership or purchase correctness.

**Fix:** persist a pending candidate-subscription order and fulfill only that verified order, checking customer, account, type, plan, amount, currency, and reference. Reject query-selected fulfillment plans.

## 5. P1 — The separate candidate wallet subscription path inserts the payment twice

**Evidence:** `app/Controllers/CandidateSubscriptionController.php:104–116` and `:189–216`; unique `payments.reference` in the schema.

Wallet checkout first debits the wallet and inserts a payment. It then calls `activatePlan`, which begins a new transaction and attempts to insert a second payment with the same reference. The uniqueness violation fails that activation transaction. The initial wallet debit happened outside it, so activation rollback cannot restore the funds. The caller still returns a success redirect, and `activatePlan` does not propagate its transaction failure to the caller.

**Impact:** this entry point can charge a candidate without activating their subscription, despite the generic wallet endpoint having been fixed earlier.

**Fix:** one transaction must own the debit, one payment record, and activation. Reuse a single candidate-plan purchase service from both entry points and propagate failures.

## 6. P1 — Course verification allows a payment reference to enroll in a different course

**Evidence:** `app/Controllers/ElearningController.php:610–646`; route `training/verify/{courseId}`.

The callback checks transaction success, then enrolls the current user in the course ID supplied by the URL. It never checks the transaction's course metadata, customer/user, expected price, currency, or global consumption of that reference. Its idempotency check is only `(course_id, user_id)`.

**Impact:** a successful payment can be reused for multiple courses or accounts, and a cheaper purchase can unlock a more expensive course. This is independent of the earlier CV-review payment fix.

**Fix:** bind course verification to a stored order, validate the complete payment, and enforce reference uniqueness across fulfillment. The signed webhook and callback should share that implementation.

## 7. P1 — A posted score can issue a course certificate without a graded assessment

**Evidence:** `app/Controllers/ElearningController.php:1069`, `:1103–1126`, and `:1165`; route `POST training/complete/{courseId}`.

The completion handler reads `score` from the request and uses it directly. Any value at least 70 enters certificate issuance; it does not calculate that score from server-stored answers or require a corresponding passed assessment. It also does not reject completion after the advertised three-attempt limit: it increments the counter and continues checking the supplied score.

**Impact:** an enrolled candidate can request completion with a fabricated passing score and obtain a verifiable certificate without demonstrating the required result. Values above 100 are also accepted.

**Fix:** require a server-graded, owned assessment attempt linked to the enrollment, enforce attempt limits server-side, and consume a qualifying pass once when issuing the certificate.

## 8. P2 — Direct application endpoints accept jobs outside the publication/deadline rules

**Evidence:** `app/Controllers/Home.php:1520` (`startApplication`) and `:1894` (`apply_job`); compare job-list queries requiring `jobs.admin_status = approved`.

The application handlers reject a short list of closed status strings but do not positively require a published/approved open job or check its application deadline. The application form loads the job directly by ID. A job that is still marked open while pending moderation, or whose deadline has passed without a status update, passes these eligibility checks.

**Impact:** direct links can expose application details and accept applications before approval or after the advertised deadline, even though the job is absent from public listings. Normal access, CAPTCHA, and form requirements still apply.

**Fix:** share a server-side eligibility check between listing, detail, external-application tracking, and internal submission. Require approved/open status and an unexpired deadline.

## 9. P2 — Subscription lifecycle updates are discarded by model field protection

**Evidence:** `app/Models/UserSubscriptionModel.php:27–45`; `app/Controllers/WebhookController.php` writes `subscription_code`, `authorization`, and `will_not_renew`; `EmployerController::cancelSubscription` expects `paystack_subscription_id` and writes `will_not_renew`.

The subscription model's allowed fields omit the gateway subscription identifiers, authorization, and nonrenewal flag that lifecycle code reads/writes. There is also inconsistent naming between the webhook's `subscription_code` and the cancellation handler's `paystack_subscription_id`.

**Impact:** subscription create/cancel/reactivate events cannot reliably preserve their gateway linkage or scheduled cancellation state. A user can have a subscription that the cancellation page cannot identify or a nonrenewal update that is silently discarded (or rejected as an empty update).

**Fix:** choose one gateway identifier, migrate existing values, align the model and schema, and test create → cancel-at-period-end → reactivate → renewal webhooks together.

## Verification and limits

- Ran `scratch/AdditionalBugProbeTest.php` against an isolated in-memory database using real assessment controller/model methods: **2 tests, 8 assertions passed**. One reproduces the 150% score; the other confirms the latest source rejects the spoofed employer-required flag and therefore that suspicion is not reported as an open defect.
- The probe assertions intentionally describe observed defects and belong outside the regular regression suite. They should become negative/security regression tests when the scoring code is fixed.
- Findings 2–9 are source-confirmed paths, not live production reproductions. No real payments, outgoing messages, or customer data changes were performed.
- Only the audit report and scratch probe were added in this review. Existing application changes from other work were left intact.

Prioritize server-side grading and payment/order binding, followed by private assessment ownership, wallet transaction consolidation, application eligibility, and gateway lifecycle consistency.
