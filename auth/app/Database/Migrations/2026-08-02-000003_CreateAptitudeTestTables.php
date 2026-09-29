<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Ports the aptitude-test schema (previously only in scratch/root-dev-scripts/aptitude_schema.sql,
 * which is never run automatically) into the canonical migration path so `php spark migrate`
 * actually creates these tables on a fresh environment. AptitudeController and its models
 * (TestModel, TestAttemptModel, QuestionModel, QuestionOptionModel, AttemptAnswerModel,
 * CandidateTestResultModel) all depend on these tables existing.
 */
class CreateAptitudeTestTables extends Migration
{
    public function up()
    {
        // PHPUnit runs migrations against SQLite. Keep the production MySQL
        // schema below, but use equivalent portable DDL for the test database.
        if ($this->db->DBDriver === 'SQLite3') {
            $statements = [
                "CREATE TABLE IF NOT EXISTS tests (id INTEGER PRIMARY KEY AUTOINCREMENT, category_id INTEGER NOT NULL, title VARCHAR(150) NOT NULL, slug VARCHAR(170) UNIQUE NOT NULL, description TEXT, duration_mins INTEGER NOT NULL DEFAULT 20, num_questions INTEGER NOT NULL DEFAULT 20, pass_threshold INTEGER NOT NULL DEFAULT 60, difficulty VARCHAR(20) DEFAULT 'intermediate', is_active INTEGER DEFAULT 1, created_at DATETIME, updated_at DATETIME, FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE CASCADE)",
                "CREATE TABLE IF NOT EXISTS questions (id INTEGER PRIMARY KEY AUTOINCREMENT, test_id INTEGER NOT NULL, type VARCHAR(20) DEFAULT 'mcq', body TEXT NOT NULL, difficulty VARCHAR(20) DEFAULT 'intermediate', points INTEGER DEFAULT 1, explanation TEXT, is_active INTEGER DEFAULT 1, created_at DATETIME, updated_at DATETIME, FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE)",
                "CREATE TABLE IF NOT EXISTS question_options (id INTEGER PRIMARY KEY AUTOINCREMENT, question_id INTEGER NOT NULL, body VARCHAR(500) NOT NULL, is_correct INTEGER DEFAULT 0, sort_order INTEGER DEFAULT 0, FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE)",
                "CREATE TABLE IF NOT EXISTS test_attempts (id INTEGER PRIMARY KEY AUTOINCREMENT, candidate_id INTEGER NOT NULL, test_id INTEGER NOT NULL, mode VARCHAR(20) NOT NULL, status VARCHAR(20) DEFAULT 'in_progress', score_pct DECIMAL(5,2), passed INTEGER, num_correct INTEGER, num_total INTEGER, started_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, submitted_at DATETIME, question_ids TEXT, flagged INTEGER DEFAULT 0, employer_required INTEGER DEFAULT 0, job_id INTEGER, FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE)",
                "CREATE TABLE IF NOT EXISTS attempt_answers (id INTEGER PRIMARY KEY AUTOINCREMENT, attempt_id INTEGER NOT NULL, question_id INTEGER NOT NULL, selected_option_ids TEXT, is_correct INTEGER, FOREIGN KEY (attempt_id) REFERENCES test_attempts(id) ON DELETE CASCADE, FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE)",
                "CREATE TABLE IF NOT EXISTS candidate_test_results (id INTEGER PRIMARY KEY AUTOINCREMENT, candidate_id INTEGER NOT NULL, test_id INTEGER NOT NULL, best_score DECIMAL(5,2), passed INTEGER, attempt_id INTEGER, show_on_profile INTEGER DEFAULT 1, is_public INTEGER DEFAULT 0, achieved_at DATETIME, UNIQUE (candidate_id, test_id), FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE)",
            ];

            foreach ($statements as $statement) {
                $this->db->query($statement);
            }

            return;
        }

        $this->db->query("CREATE TABLE IF NOT EXISTS tests (
            id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            category_id     INT(10) UNSIGNED NOT NULL,
            title           VARCHAR(150) NOT NULL,
            slug            VARCHAR(170) UNIQUE NOT NULL,
            description     TEXT,
            duration_mins   INT NOT NULL DEFAULT 20,
            num_questions   INT NOT NULL DEFAULT 20,
            pass_threshold  INT NOT NULL DEFAULT 60,
            difficulty      ENUM('beginner','intermediate','advanced') DEFAULT 'intermediate',
            is_active       TINYINT(1) DEFAULT 1,
            created_at      DATETIME, updated_at DATETIME,
            FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE IF NOT EXISTS questions (
            id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            test_id       INT UNSIGNED NOT NULL,
            type          ENUM('mcq','true_false','multi_select') DEFAULT 'mcq',
            body          TEXT NOT NULL,
            difficulty    ENUM('beginner','intermediate','advanced') DEFAULT 'intermediate',
            points        INT DEFAULT 1,
            explanation   TEXT,
            is_active     TINYINT(1) DEFAULT 1,
            created_at    DATETIME, updated_at DATETIME,
            FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE IF NOT EXISTS question_options (
            id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            question_id   INT UNSIGNED NOT NULL,
            body          VARCHAR(500) NOT NULL,
            is_correct    TINYINT(1) DEFAULT 0,
            sort_order    INT DEFAULT 0,
            FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE IF NOT EXISTS test_attempts (
            id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            candidate_id    INT(11) UNSIGNED NOT NULL,
            test_id         INT UNSIGNED NOT NULL,
            mode            ENUM('practice','official') NOT NULL,
            status          ENUM('in_progress','submitted','expired','abandoned') DEFAULT 'in_progress',
            score_pct       DECIMAL(5,2),
            passed          TINYINT(1),
            num_correct     INT, num_total INT,
            started_at      DATETIME NOT NULL,
            expires_at      DATETIME NOT NULL,
            submitted_at    DATETIME,
            question_ids    JSON,
            flagged         TINYINT(1) DEFAULT 0,
            employer_required TINYINT(1) DEFAULT 0,
            job_id          INT UNSIGNED NULL,
            FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE IF NOT EXISTS attempt_answers (
            id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            attempt_id          INT UNSIGNED NOT NULL,
            question_id         INT UNSIGNED NOT NULL,
            selected_option_ids JSON,
            is_correct          TINYINT(1),
            FOREIGN KEY (attempt_id) REFERENCES test_attempts(id) ON DELETE CASCADE,
            FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE IF NOT EXISTS candidate_test_results (
            id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            candidate_id    INT(11) UNSIGNED NOT NULL,
            test_id         INT UNSIGNED NOT NULL,
            best_score      DECIMAL(5,2),
            passed          TINYINT(1),
            attempt_id      INT UNSIGNED,
            show_on_profile TINYINT(1) DEFAULT 1,
            is_public       TINYINT(1) DEFAULT 0,
            achieved_at     DATETIME,
            UNIQUE KEY uniq_cand_test (candidate_id, test_id),
            FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function down()
    {
        $this->forge->dropTable('candidate_test_results', true);
        $this->forge->dropTable('attempt_answers', true);
        $this->forge->dropTable('test_attempts', true);
        $this->forge->dropTable('question_options', true);
        $this->forge->dropTable('questions', true);
        $this->forge->dropTable('tests', true);
    }
}
