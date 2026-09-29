<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApplicationIdToMockInterviewSessions extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('mock_interview_sessions')) {
            return;
        }

        if (! $this->db->fieldExists('application_id', 'mock_interview_sessions')) {
            $this->forge->addColumn('mock_interview_sessions', [
                'application_id' => [
                    'type'     => 'INT',
                    'unsigned' => true,
                    'null'     => true,
                    'after'    => 'id',
                ],
            ]);
        }

        $indexes = $this->db->getIndexData('mock_interview_sessions');
        if (! array_key_exists('mock_interview_sessions_application_id_idx', $indexes)) {
            $this->db->query('CREATE INDEX mock_interview_sessions_application_id_idx ON mock_interview_sessions (application_id)');
        }
    }

    public function down()
    {
        if (! $this->db->tableExists('mock_interview_sessions')) {
            return;
        }

        $indexes = $this->db->getIndexData('mock_interview_sessions');
        if (array_key_exists('mock_interview_sessions_application_id_idx', $indexes)) {
            $platform = strtolower($this->db->DBDriver);
            $dropSql = $platform === 'sqlite3'
                ? 'DROP INDEX mock_interview_sessions_application_id_idx'
                : 'DROP INDEX mock_interview_sessions_application_id_idx ON mock_interview_sessions';

            $this->db->query($dropSql);
        }

        if ($this->db->fieldExists('application_id', 'mock_interview_sessions')) {
            $this->forge->dropColumn('mock_interview_sessions', 'application_id');
        }
    }
}
