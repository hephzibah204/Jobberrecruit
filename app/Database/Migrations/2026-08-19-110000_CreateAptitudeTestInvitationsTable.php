<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAptitudeTestInvitationsTable extends Migration
{
    public function up()
    {
        if ($this->db->DBDriver === 'SQLite3') {
            $this->db->query("CREATE TABLE IF NOT EXISTS aptitude_test_invitations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                employer_id INTEGER NOT NULL,
                candidate_id INTEGER NOT NULL,
                job_id INTEGER NULL,
                application_id INTEGER NULL,
                test_id INTEGER NOT NULL,
                invitation_code VARCHAR(64) UNIQUE NOT NULL,
                message TEXT NULL,
                due_date DATETIME NULL,
                status VARCHAR(20) DEFAULT 'pending',
                attempt_id INTEGER NULL,
                created_at DATETIME,
                updated_at DATETIME
            )");
            return;
        }

        $this->db->query("CREATE TABLE IF NOT EXISTS aptitude_test_invitations (
            id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            employer_id       INT UNSIGNED NOT NULL,
            candidate_id      INT UNSIGNED NOT NULL,
            job_id            INT UNSIGNED NULL,
            application_id    INT UNSIGNED NULL,
            test_id           INT UNSIGNED NOT NULL,
            invitation_code   VARCHAR(64) UNIQUE NOT NULL,
            message           TEXT NULL,
            due_date          DATETIME NULL,
            status            ENUM('pending','completed','expired') DEFAULT 'pending',
            attempt_id        INT UNSIGNED NULL,
            created_at        DATETIME,
            updated_at        DATETIME,
            KEY idx_inv_emp (employer_id),
            KEY idx_inv_cand (candidate_id),
            KEY idx_inv_app (application_id),
            KEY idx_inv_code (invitation_code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function down()
    {
        $this->forge->dropTable('aptitude_test_invitations', true);
    }
}
