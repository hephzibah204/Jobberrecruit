# Additional workflow fixes — 16 October 2026

All nine findings in `ADDITIONAL-BUG-AUDIT-2026-10-16.md` have code fixes in the root application. Existing unrelated working-tree changes were preserved. Nothing was deployed or committed.

## Changes

1. **Assessment score forgery:** answer writes validate assigned questions and option ownership. Grading iterates the assigned question set, revalidates old answer records, and compares the selected options with that question's correct options. Answer writes and final submission serialize for the candidate. Expired attempts cannot accept new answers, and a submitted attempt cannot be submitted again.
2. **Private assessments:** general slug/API start endpoints only launch global tests. Existing private attempts require a linked invitation belonging to the candidate before questions, engine pages, results, or answer/submission endpoints can be used.
3. **Invitation ownership:** candidate IDs are treated as user IDs, not interchangeable profile IDs. Listing and acceptance use the canonical relationship and reject conflicting recipient emails. The migration repairs legacy profile IDs only when a unique email identity supports the mapping. Concurrent acceptance by the same user is serialized.
4. **Candidate payment substitution:** candidate subscription initialization creates a stored payment order. Fulfillment binds user, customer email, type, reference, amount, currency, and plan to that order. The callback no longer chooses its activation plan from a query parameter or session value.
5. **Candidate wallet double payment insertion:** the candidate checkout and generic wallet endpoint share one transaction for the debit, one payment record, and subscription activation. The candidate form submits an idempotency key so retrying the same checkout returns its original result.
6. **Course payment replay/substitution:** course checkout, signed webhooks, and browser callbacks share order-bound fulfillment. A callback cannot change the course or account on an order, and callback/webhook replay does not create another enrollment. Course wallet checkout uses the same transactional purchase service.
7. **Forged course certificates:** the browser sends answers, not an authoritative score. The server grades the configured course questions, records the question/answer evidence, enforces three attempts, and issues a certificate in the same transaction as a passing submission. Request keys protect submission retries. The classroom no longer embeds correct-answer flags or unlocks a certificate after a failed request. Unconfigured assessments cannot issue certificates. Public assessment version hashes exclude the answer key.
8. **Application eligibility:** internal applications and external application tracking require approved, open jobs with an unexpired application deadline. Public detail pages hide unapproved/draft jobs, and approved job listing queries also filter expired application deadlines.
9. **Gateway subscription lifecycle:** model fields, schema, callbacks, and cancellation/reactivation handlers now use `subscription_code`, `email_token`, `authorization`, and `will_not_renew`. Legacy gateway identifiers are copied into the canonical field where possible. The webhook resolves email through authentication identities and handles the model's array return type. Cancellation uses the documented disable endpoint and subscription email token.

Course enrollment/completion notifications remain best-effort after successful transactions. Failures in those notifications do not roll back paid access or certificates. Top-level purchase transactions can recover from a previous rollback on a reused connection; nested failures remain failures.

## Verification

- Complete offline PHP suite: **88 tests, 258 assertions, all passed**.
- Added integration coverage for assessment rejection and valid grading, private-test access, canonical invitation ownership, lifecycle field persistence, order-bound course/candidate purchases, payment retry behavior, server-side course grading, attempt exhaustion, and certificate issuance.
- Added application status/deadline checks.
- The offline Chrome behavioral test passes for network failure, failing grades, retry exhaustion, and a verified passing response. It runs the real classroom submission script in an isolated fixture.
- PHP syntax checks passed for all 17 application/migration/view files changed or added for these fixes. The focused diff whitespace check passed.
- The final PHP suite excludes the existing network-dependent image download test. No live payment or outgoing customer email was used to verify these changes.

## Deployment requirements

Apply `app/Database/Migrations/2026-10-16-170000_SecureAssessmentAndSubscriptionWorkflows.php` before releasing the updated application. It:

- Adds canonical subscription gateway fields and copies supported legacy values.
- Adds course assessment configuration/count/score columns if missing.
- Creates `course_assessment_submissions` for grading evidence and request deduplication.
- Repairs supported legacy invitation ownership records.

The migration was exercised through the isolated test database, not applied to the application or production database. Earlier queue/custom-assessment migrations remain separately applicable if not already installed. Duplicate deployment trees under `auth/app` and FTP/archive copies were not edited.

Administrators must configure real questions and correct options for courses that previously relied on the generic fallback quiz. Assessments support one selected option per course question. Existing misleading scores/certificates, unmatched historical payments, and ambiguous legacy ownership data still require data review; this patch does not guess missing financial facts or revoke customer records automatically.

The gateway cancellation correction follows [Paystack's subscription API documentation](https://paystack.com/docs/api/subscription/). Live gateway test-mode verification is still required during release.
