<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNotificationAndReminderFields extends Migration
{
    public function up()
    {
        // 1. Make application_id nullable in candidate_notifications table
        if ($this->db->tableExists('candidate_notifications')) {
            if ($this->db->fieldExists('application_id', 'candidate_notifications')) {
                $this->forge->modifyColumn('candidate_notifications', [
                    'application_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true]
                ]);
            }
        }

        // 2. Add reminder flags to webinar_registrations table
        if ($this->db->tableExists('webinar_registrations')) {
            $fieldsToAdd = [];
            if (!$this->db->fieldExists('reminder_sent_24h', 'webinar_registrations')) {
                $fieldsToAdd['reminder_sent_24h'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
            }
            if (!$this->db->fieldExists('reminder_sent_1h', 'webinar_registrations')) {
                $fieldsToAdd['reminder_sent_1h'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
            }
            if (!empty($fieldsToAdd)) {
                $this->forge->addColumn('webinar_registrations', $fieldsToAdd);
            }
        }

        // 3. Add expiration reminder flags to user_subscriptions table
        if ($this->db->tableExists('user_subscriptions')) {
            $fieldsToAdd = [];
            if (!$this->db->fieldExists('expiry_reminder_3d_sent', 'user_subscriptions')) {
                $fieldsToAdd['expiry_reminder_3d_sent'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
            }
            if (!$this->db->fieldExists('expiry_reminder_1d_sent', 'user_subscriptions')) {
                $fieldsToAdd['expiry_reminder_1d_sent'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
            }
            if (!$this->db->fieldExists('expired_notice_sent', 'user_subscriptions')) {
                $fieldsToAdd['expired_notice_sent'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
            }
            if (!empty($fieldsToAdd)) {
                $this->forge->addColumn('user_subscriptions', $fieldsToAdd);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('webinar_registrations')) {
            if ($this->db->fieldExists('reminder_sent_24h', 'webinar_registrations')) {
                $this->forge->dropColumn('webinar_registrations', ['reminder_sent_24h', 'reminder_sent_1h']);
            }
        }

        if ($this->db->tableExists('user_subscriptions')) {
            if ($this->db->fieldExists('expiry_reminder_3d_sent', 'user_subscriptions')) {
                $this->forge->dropColumn('user_subscriptions', ['expiry_reminder_3d_sent', 'expiry_reminder_1d_sent', 'expired_notice_sent']);
            }
        }
    }
}
