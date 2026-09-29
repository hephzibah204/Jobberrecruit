# cPanel Deployment Guide — demo.jobberrecruit.com

Updated: 2026-07-20 (use `scratch/jobberrecruit-deploy-20260720.zip` — supersedes the 07-07 zip)

## New in the 2026-07-20 package

- **Application status tracking**: employers opening an application auto-marks it
  "Reviewed"; candidates see a progress timeline (Submitted → Viewed → Shortlisted →
  Outcome) plus the employer's message on `candidate/applications/view/{id}`.
- **DB change required** — run ONE of these after uploading:
  - SSH: `php spark migrate`
  - or phpMyAdmin: `ALTER TABLE job_applications ADD COLUMN reviewed_at DATETIME NULL AFTER status_message;`
- **Google Jobs structured data fixed**: salary parsing ("₦150,000" no longer read as 150),
  `unitText` normalized to MONTH/YEAR/…, TELECOMMUTE only on remote jobs, deadline fallback.
  After deploying, test a job URL in https://search.google.com/test/rich-results and submit
  the sitemap in Google Search Console.
- **robots.txt**: `/auth/` asset subfolders un-blocked so Googlebot can render pages.

## What broke on the server

The site failed with `Failed opening required '...phpunit/.../Functions.php'` because the
server's `vendor/composer/` autoloader was generated **with dev dependencies** but the
`vendor/phpunit` folder was never uploaded. A second, hidden blocker: the old
`composer.lock` contained `symfony/process v8.1.0` which requires **PHP ≥ 8.4**, while the
server runs **PHP 8.2** — even with phpunit fixed, `platform_check.php` would have thrown
a 500. Both are fixed in this package.

## Deploy steps

1. **Upload** `scratch/jobberrecruit-deploy-20260707.zip` to cPanel File Manager, into
   `/home/jobbcfsf/demo.jobberrecruit.com/`.
2. **Delete the old `vendor` folder on the server first** (File Manager → select `vendor` →
   Delete). Old dev-autoloader files must not survive.
3. **Extract** the zip in place. It contains: `app/`, `vendor/`, `index.php`, `.htaccess`,
   `spark`, `preload.php`, `composer.json`, `composer.lock`, `.env.production.example`.
4. **Configure `.env`**: copy `.env.production.example` to `.env` on the server and fill in:
   - real cPanel DB name/user/password
   - SMTP password
   - `PAYSTACK_SECRET_KEY = sk_live_...` (webhook signature verification is skipped without it!)
5. **PHP version**: in cPanel → MultiPHP Manager, ensure the domain uses **PHP 8.2 or 8.3**.
6. **Writable folder**: ensure `writable/` exists on the server with subfolders
   `cache/`, `logs/`, `session/`, `uploads/`, `debugbar/`, `temp/` — all writable (755 usually
   suffices under suPHP/LSAPI; use 775 only if needed).
7. **Delete leftovers on the server** (they are junk/dangerous in the webroot):
   `debug_*.php`, `test_*.php`, `query_*.php`, `check_*.php`, `*.sql`, `*.py`, `dump*.html`,
   `writable_old/`, `node_modules/`, `chrome-profile/`, `scratch/`.
8. Visit the site. If you see a blank page, check `writable/logs/` for the newest log file.

## Never do again

- Never upload a `vendor/` folder produced by plain `composer install` (it includes dev
  packages). Always build with `composer install --no-dev --optimize-autoloader`.
- Never leave `CI_ENVIRONMENT = development` on the server — it prints stack traces with
  paths/credentials to visitors.
- Never leave one-off debug scripts in the webroot; keep them in `scratch/` (now blocked
  by `.htaccess` as well).

## Certificate PDFs on shared hosting

`spatie/browsershot` needs Node.js + Chrome, which shared cPanel hosting does not have.
`ElearningController::downloadCertificate()` now falls back to Dompdf automatically.
Expect slightly simpler certificate rendering on the server; check
`app/Views/certificates/course_certificate.php` uses Dompdf-friendly CSS (avoid flexbox/grid;
use tables and absolute positioning).
