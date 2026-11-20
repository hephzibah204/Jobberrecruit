# Bug and workflow review — 16 October 2026

## Scope and confidence

Reviewed the current working tree, including existing uncommitted changes. This is a source-code review of routing, authentication/feature gating, wallet checkout, Paystack integration, CV reviews, and email queues. Findings below are supported by code paths; they have not been exercised against a live database or payment gateway. This is not a claim that every bug in the application has been identified. No application code was changed.

Priority: P1 = security, financial loss, or a broken core workflow; P2 = functional failure or reliability defect.

## P1 findings

### 1. Basic CV uploads can overwrite another user's review

- Evidence: `app/Controllers/ElearningController.php:1648` and `:1689`.
- Trigger: submit a valid CV upload with `plan=basic` and a `review_id` belonging to another account.
- Cause: ownership and paid-status checks execute only for professional/premium plans. The subsequent update accepts any supplied review ID regardless of plan.
- Impact: an authenticated user can replace another user's CV file, contact details, and review status.
- Fix: load and authorize every supplied review ID before moving a file or updating a record. Derive the plan from that record.

### 2. CV payment verification accepts unrelated and replayed payments

- Evidence: `app/Controllers/ElearningController.php:1590–1631`; `app/Database/Migrations/2026-05-22-180000_CreateCvReviews.php:32`.
- Trigger: supply a successful transaction reference from another purchase or account, or visit the verification URL repeatedly.
- Cause: the verifier checks gateway success but does not bind the customer/user, purchase type, expected price/currency, and unique reference to this CV purchase. Missing plan metadata defaults to professional. The migration creates a non-unique reference index.
- Impact: unrelated payments can grant paid reviews, and one payment can create multiple review records.
- Fix: verify the pending order and all payment attributes; fulfill each reference once with a database uniqueness constraint.

### 3. Missing Paystack credentials fabricate successful payments

- Evidence: `app/Services/PaystackService.php:22–35` and `:58–83`.
- Trigger: the payment secret is absent in any environment.
- Cause: initialization returns a simulated checkout URL, and verification returns success with a fixed amount for any reference. There is no development-only guard.
- Impact: a production configuration mistake can turn real purchase flows into unpaid activations. Wallet funding also gets misclassified as a subscription.
- Fix: fail closed when credentials are missing; isolate mocks in explicit test implementations.

### 4. AI subscription gating skips the application's normal candidate account type

- Evidence: `app/Filters/FeatureGateFilter.php:84`; compare `app/Commands/DemoAccounts.php:115` and `app/Controllers/BaseController.php:93`.
- Trigger: a normal `job_seeker` account requests AI tools with paid mode enabled and free mode disabled.
- Cause: the gate tests `user_type === 'candidate'` or `role === 'candidate'`; normal candidate accounts use `job_seeker`.
- Impact: this central paid-mode check fails to enforce the subscription requirement for normal candidate accounts. Individual endpoint checks may limit the exposure.
- Fix: use the canonical account type consistently and test the gate with a real job-seeker entity.

### 5. Subscription upgrade credit can be calculated from a wallet deposit or unrelated purchase

- Evidence: `app/Services/SubscriptionService.php:159–179`.
- Trigger: an active subscriber funds their wallet or buys a course/bundle, then upgrades.
- Cause: proration uses the latest paid payment for the user without linking it to the active subscription.
- Impact: the unrelated payment becomes the subscription's purchase value, producing excessive discounts or incorrect charges.
- Fix: link payments to subscription purchases and derive proration from the active subscription's original value, including any carried credit.

### 6. Wallet checkout permits arbitrary subscription durations

- Evidence: `app/Controllers/WalletController.php:245`, `:293–309`, `:421`; `app/Services/SubscriptionService.php:232–237`.
- Trigger: send zero, negative, or an unsupported duration to employer subscription checkout.
- Cause: duration is cast to an integer but never validated against allowed positive billing periods. Missing tiers fall back to price multiplied by that value; amounts are clamped to zero.
- Impact: invalid requests can deactivate a paid subscription and replace it with a zero-cost, invalid-duration subscription; unsupported periods can bypass intended pricing tiers.
- Fix: validate a positive allowlisted duration before calculating price or changing subscription records.

### 7. Wallet CV checkout takes payment without creating a review entitlement

- Evidence: `app/Controllers/WalletController.php:279–287`, `:348–371`, `:415–416`, `:509–510`.
- Trigger: submit a wallet checkout of type `cv_review` with a nonzero item ID.
- Cause: the wallet is debited and a payment inserted, but fulfillment only writes a log message. No `CvReviewModel` record is created. Without an item ID the request is rejected before reaching the CV branch.
- Impact: payment succeeds without the paid review record required by the upload workflow.
- Fix: create the paid review record in the same transaction and redirect to its upload page; remove the irrelevant item-ID requirement for this type.

### 8. Repeated course or webinar wallet purchases charge without adding an entitlement

- Evidence: `app/Controllers/WalletController.php:348–371`, `:395–396`, `:477–478`.
- Trigger: repeat checkout for an already enrolled course or registered webinar, including after retrying a successful request whose response was lost.
- Cause: debit happens before checking existing enrollment/registration, and every request generates a new payment reference.
- Impact: the user pays again while the existing entitlement is left unchanged.
- Fix: reject already fulfilled purchases before debit and use an idempotency key enforced in the same transaction.

## P2 findings

### 9. Saved-jobs removal points to a missing controller method

- Evidence: `app/Config/Routes.php:94`; `app/Views/candidate/saved_jobs.php:134`. `Home` defines no `unsave_job()`.
- Trigger: click Remove on the candidate's saved-jobs page.
- Impact: the request cannot execute the configured handler and the saved job remains.
- Fix: implement the handler with ownership checks, or update the page and route to an existing compatible handler.

### 10. Campaign queue processing queries a nonexistent column

- Evidence: `app/Controllers/CronController.php:93`; `app/Models/JobQueueModel.php:22–36`; initial migration's `queue_jobs` definition.
- Trigger: call the email-queue endpoint against the checked-in schema.
- Cause: it filters `queue_jobs.type`, but type is inside the JSON payload; the schema has no type column.
- Impact: the endpoint fails instead of processing campaign messages.
- Fix: store and migrate an indexed type column or select using the queue/payload representation actually written by dispatch.

### 11. Unified automation sends delayed messages early and skips retries

- Evidence: `app/Controllers/CronController.php:216–241`; compare `app/Models/JobQueueModel.php:76–81`.
- Trigger: run all automations while pending jobs have a future `available_at`, or while an email has a transient failure.
- Cause: the unified worker selects all pending jobs without an availability filter, and immediately marks failed sends as failed rather than using the retry policy.
- Impact: scheduled messages send early and transient failures permanently lose messages.
- Fix: share one availability-aware processing implementation and retry policy across workers.

### 12. Concurrent queue workers can deliver the same message twice

- Evidence: `app/Controllers/CronController.php:26–36`, `:109`, `:216–219`; `app/Models/JobQueueModel.php:76–81`.
- Trigger: overlapping cron requests read the same pending batch.
- Cause: jobs are selected first and marked processing later without an atomic claim or row lock.
- Impact: both workers can send the same email; a crash after marking processing can also leave a job stranded because pending-only selection cannot reclaim it.
- Fix: atomically claim jobs with ownership/lease expiry and safely reclaim abandoned claims.

### 13. Payment model silently drops subscription and gateway fields

- Evidence: `app/Models/PaymentModel.php:18–33`; callers include `app/Controllers/WalletController.php:363` and `app/Controllers/WebhookController.php:195–208`.
- Trigger: record a payment containing `plan_id`, `channel`, `ip_address`, or `gateway_response`.
- Cause: those fields are omitted from `allowedFields`, so model protection discards them.
- Impact: payment-to-plan linkage and gateway audit details are lost. The legacy employer webhook later expects `payment['plan_id']` to activate the purchased plan.
- Fix: align the payment schema, model fields, and all writers/readers around one payment contract.

### 14. Wallet deposits depend on the browser returning to the callback

- Evidence: `app/Controllers/WalletController.php:141–172`; `app/Controllers/WebhookController.php:128–191`; legacy webhook in `app/Controllers/EmployerController.php:6372`.
- Trigger: a wallet payment succeeds but the customer closes the checkout before returning.
- Cause: wallet credit is implemented in the browser callback. Neither registered webhook handler fulfills `wallet_funding`; the general handler acknowledges it as a non-subscription charge.
- Impact: money is received without the wallet being credited until the callback is revisited or support intervenes.
- Fix: fulfill verified wallet deposits through the signed webhook and reuse the same idempotent fulfillment from the callback.

### 15. Wallet callback references an undefined user type on database failure

- Evidence: `app/Controllers/WalletController.php:178` and `:189`.
- Trigger: the funding transaction reaches the failed-database branch.
- Cause: `$userType` is read before assignment.
- Impact: the error handler can raise an undefined-variable error or send an employer to the candidate wallet.
- Fix: determine the user and redirect target before beginning payment processing.

### 16. Candidate inactivity archiving uses the wrong account type

- Evidence: `app/Controllers/CronController.php:185`; compare `app/Commands/DemoAccounts.php:115`.
- Trigger: run candidate inactivity archiving against normal job-seeker accounts.
- Cause: the update filters `user_type = candidate`, while normal candidate accounts use `job_seeker`.
- Impact: intended candidate accounts are never archived by this operation.
- Fix: use the canonical type and verify inactivity against the intended activity timestamp. Both reported archive counts also currently receive the update success boolean rather than affected-row counts.

## Validation and limits

- PHP syntax checks passed for WalletController, ElearningController, CronController, and SubscriptionService.
- Checked configured route handlers against controller methods; confirmed the saved-jobs removal mismatch against its calling view.
- PHPUnit was attempted twice, including with the PHP executable directly. Both attempts stopped before executing tests: `Cannot open bootstrap script .../system/Test/bootstrap.php`. The file is visible to the filesystem tools, so this is an unresolved local runtime/file-access issue, not evidence that the file is absent.
- No authenticated browser journey, live database reproduction, gateway transaction, concurrent-worker experiment, or external email delivery was performed.
- Public pages, the full admin interface, resume generation, aptitude scoring, mobile layouts, and deployment copies under `auth/app` need additional coverage. Findings here refer to the root `app` tree; deployment entry-point selection has not been verified.

## Recommended order

1. Close CV ownership/payment-verification gaps and remove automatic payment success when credentials are missing.
2. Correct paid feature gating and subscription/payment linkage, then validate checkout inputs and idempotency.
3. Repair wallet fulfillment and webhook reconciliation.
4. Fix saved-job removal and consolidate queue workers around atomic claims, scheduling, and retries.
5. Restore the local test runner and exercise candidate/employer/admin journeys with an isolated test database and test gateway credentials.
