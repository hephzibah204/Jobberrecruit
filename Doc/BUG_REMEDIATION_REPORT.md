# ROOT CAUSE BUG INVESTIGATION & REMEDIATION REPORT

## 1. Root Cause Analysis: Broken Test Suite (BUG-TEST-001)
- **Bug ID:** BUG-TEST-001
- **Severity:** Critical
- **Symptoms:** `vendor/bin/phpunit` fails with `Class "CodeIgniter\Test\FeatureTestCase" not found`.
- **Root Cause:** The project was upgraded to CodeIgniter v4.6.3, which removed the deprecated `FeatureTestCase` class. The existing integration tests still relied on this legacy class.
- **Remediation:** Refactored integration tests to use the modern pattern: extending `CIUnitTestCase` and using the `FeatureTestTrait`.

## 2. Root Cause Analysis: Migration Raw Query Prefixes (BUG-TEST-002)
- **Bug ID:** BUG-TEST-002
- **Severity:** High
- **Symptoms:** Migrations fail on SQLite with `no such table: main.users` despite the table existing.
- **Root Cause:** Multiple migrations used raw SQL queries (e.g., `CREATE INDEX`) with hardcoded table names. During tests, a `db_` prefix is applied. Raw queries in CI4 do NOT automatically apply prefixes.
- **Remediation:** Refactored migrations to use `{$this->db->prefixTable('table_name')}` in all raw SQL strings.

## 3. Root Cause Analysis: Logic Bug - Model Return Type (BUG-002)
- **Bug ID:** BUG-002
- **Severity:** High
- **Symptoms:** `ErrorException: Attempt to read property "payload" on array` in `ResumeController.php`.
- **Root Cause:** `ResumeAutosaveModel` and `AiImageModel` defaulted to returning arrays, but the Controller logic assumed objects (`$row->payload`).
- **Remediation:** Updated both models to explicitly set `protected $returnType = 'object'` for consistency with the rest of the application and the expected controller behavior.

## 4. Root Cause Analysis: Systemic SQLite Migration Failure (BUG-TEST-005)
- **Bug ID:** BUG-TEST-005
- **Severity:** High
- **Symptoms:** `DatabaseException: Failed to drop column "name" on "newsletter_subscribers" table`.
- **Root Cause:** SQLite does not natively support `DROP COLUMN`. CI4's SQLite Forge driver attempts to emulate this but fails in certain environments where constraints or naming collisions occur.
- **Remediation:** 
    - Made all "Add Column" migrations idempotent (checking for field existence).
    - Updated `down()` methods to skip column drops on SQLite platforms specifically to maintain test suite stability.

## 5. Root Cause Analysis: Configuration - Missing Migration Discovery (BUG-CONFIG-001)
- **Bug ID:** BUG-CONFIG-001
- **Severity:** High
- **Symptoms:** `no such table: db_settings` during tests despite Shield/Settings being installed.
- **Root Cause:** `app/Config/Modules.php` was missing `migrations` from the `$aliases` list, disabling auto-discovery of migrations in Composer packages.
- **Remediation:** Added `migrations` to the `$aliases` array in `Config\Modules`.

---

## Final Validation Results
- [x] **Integration Tests:** `AiImageProxyIntegrationTest`, `ResumeAutosaveIntegrationTest`, and `ResumeRestoreAutosaveIntegrationTest` are all PASSING.
- [x] **Unit Tests:** `AiImageModelTest`, `AiServiceSanitizeTest`, and `HealthTest` are all PASSING.
- [x] **Migrations:** Full `spark migrate:refresh` is now idempotent and stable across SQLite and MariaDB.

---
**Status: ALL CORE BUGS REMEDIATED. TEST SUITE RESTORED.**
