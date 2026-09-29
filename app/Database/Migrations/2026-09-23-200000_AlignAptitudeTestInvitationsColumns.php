<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignAptitudeTestInvitationsColumns extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        if ($db->tableExists('aptitude_test_invitations')) {
            if (!$db->fieldExists('application_id', 'aptitude_test_invitations')) {
                $this->db->query("ALTER TABLE `aptitude_test_invitations` ADD COLUMN `application_id` INT UNSIGNED NULL DEFAULT NULL AFTER `job_id`");
            }

            if (!$db->fieldExists('invitation_code', 'aptitude_test_invitations')) {
                $this->db->query("ALTER TABLE `aptitude_test_invitations` ADD COLUMN `invitation_code` VARCHAR(64) NULL DEFAULT NULL AFTER `candidate_id`");
                // Copy existing values from `code` if present
                if ($db->fieldExists('code', 'aptitude_test_invitations')) {
                    $this->db->query("UPDATE `aptitude_test_invitations` SET `invitation_code` = `code` WHERE `invitation_code` IS NULL AND `code` IS NOT NULL");
                }
            }

            if (!$db->fieldExists('message', 'aptitude_test_invitations')) {
                $this->db->query("ALTER TABLE `aptitude_test_invitations` ADD COLUMN `message` TEXT NULL DEFAULT NULL AFTER `invitation_code`");
            }
        }
    }

    public function down()
    {
        // Non-destructive rollback
    }
}
