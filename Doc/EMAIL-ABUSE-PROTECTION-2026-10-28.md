# Email abuse protection rollout

Changes are local until deployed. No real emails were sent by the regression tests.

## Deploy in this order

1. Back up the production database, enter maintenance mode, and deploy the application files, including the new migration.
2. Run `php spark migrate` before reopening registration or running email jobs. The new `email_suppressions` table is required by registration and the shared sender. Do not deploy the sender without this migration.
3. Configure the existing reCAPTCHA v3 `recaptcha_site_key` and `recaptcha_secret_key` on the server. The key must allow the hostname in `app.baseURL`. Registration now fails closed even if `CI_ENVIRONMENT` is `development`; there is no public development bypass. Verification resend also requires reCAPTCHA.
4. In Mailtrap, create an Email Sending webhook for `https://jobberrecruit.com/webhooks/mailtrap`. Select **JSON** batches and the **Bounce** and **Spam complaint** events. Configure both sending streams if both are used. Store that webhook's signing secret in the server environment as `MAILTRAP_WEBHOOK_SECRET`. Never put it in a URL, source control, or the browser. The endpoint rejects missing or invalid signatures.
5. Run Mailtrap's webhook test. Check that a correctly signed JSON batch returns HTTP 200. A permanent bounce, spam complaint, or unsubscribe must create a suppression. A soft bounce must not suppress the address.
6. Export confirmed hard-bounced addresses from the existing Mailtrap logs/suppression list into a text file with one address per line. Run `php spark email:suppress /private/path/confirmed-hard-bounces.txt`. Do not include temporary bounces, delivery delays, or sender configuration failures.
7. Keep the existing email queue worker/cron running. Permanent failures go straight to `failed`; temporary delivery failures retain the existing capped retry/backoff behavior. Already-queued mail is checked against suppressions before delivery.

## Implemented controls

- Registration requires server-verified CAPTCHA success, score of at least 0.5, the `register` action, and the configured application hostname. Missing keys, bad tokens, provider errors, and the previous `dev-bypass` token are rejected.
- The default Shield registration route is excluded so it cannot bypass the custom protected route. New accounts must verify their address before signing in. A short-lived server-side pending-verification session supports resending without exposing an arbitrary-email resend endpoint.
- Registration has an offscreen honeypot plus IP and normalized-address limits. Current token-bucket limits are five signup attempts per IP per hour and two per email address per day, in addition to the route limit.
- Verification resend is POST-only with CSRF and CAPTCHA action `resend_verification`. Verification emails from login/registration share limits of 20 per IP per hour and five per account per hour, plus a 60-second token cooldown. CAPTCHA/client failures never submit a bypass value.
- Newsletter signup is rate limited and refuses suppressed addresses.
- Addresses from signed permanent-bounce, spam, and unsubscribe events are normalized and stored under a unique address hash. Unsubscribe events also deactivate any matching local newsletter subscription. Replayed events do not create duplicate suppression rows. Delivery and soft-bounce events never remove suppressions.
- All shared email senders, API failover, and queued delivery check suppression. Definitive single-recipient SMTP invalid-address responses are suppressed. Generic sender/authentication failures do not suppress recipients.
- Mailtrap permanent request errors stop automatic retry. Rate limits and temporary service failures remain retryable. Permanent failures are not retried through another transport.

## Validation

Run:

```text
php tests/email-abuse-protection.php
node tests/auth-email-security.cjs
php tests/auth-security-routes.php
php tests/employer-email-delivery.php
```

Tests use isolated SQLite databases and mocked network delivery. Production CAPTCHA keys, Mailtrap webhook registration, DNS reputation, and live inbox delivery require production verification after deployment.

References: https://docs.mailtrap.io/email-api-smtp/advanced/webhooks and https://developers.google.com/recaptcha/docs/v3

## Draft reply to Mailtrap (send after production validation)

Thank you for flagging the high hard-bounce rate. We have deployed server-validated CAPTCHA and throttling on account registration and verification resend, removed our public development bypass, and added a signup honeypot. Verification resend now requires POST, CSRF validation, and CAPTCHA. We have also enabled permanent-bounce/spam suppression and stopped retries to permanently rejected recipients while retaining retries for temporary failures. We have imported our confirmed historical hard bounces and will monitor delivery and bounce rates. Please review our sending status and let us know if any further action is needed.

This is a draft only. Do not claim deployment, import, or monitoring has happened until those steps have actually been completed.
