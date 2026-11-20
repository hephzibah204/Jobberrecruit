<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddAiCompletionKeys extends Migration
{
    public function up()
    {
        // Older installations created this table from a manual SQL file.
        if (!$this->db->tableExists('salary_negotiation_sessions')) {
            $fields = [
                'id'=>['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
                'user_id'=>['type'=>'INT','unsigned'=>true],
                'created_at'=>['type'=>'DATETIME','null'=>true],
            ];
            foreach (['job_title','base_salary_offered','target_salary','final_salary','recruiter_style','difficulty','outcome'] as $field) $fields[$field] = ['type'=>'VARCHAR','constraint'=>255,'default'=>''];
            foreach (['rounds_completed','confidence_score','persuasion_score','overall_score'] as $field) $fields[$field] = ['type'=>'INT','default'=>0];
            foreach (['transcript_json','evaluation_json'] as $field) $fields[$field] = ['type'=>'TEXT','null'=>true];
            $this->forge->addField($fields);
            $this->forge->addKey('id', true);
            $this->forge->createTable('salary_negotiation_sessions', true);
        }
        foreach (['mock_interview_sessions','salary_negotiation_sessions'] as $table) {
            if (!$this->db->fieldExists('completion_key', $table)) {
                $this->forge->addColumn($table, ['completion_key'=>['type'=>'VARCHAR','constraint'=>64,'null'=>true]]);
                $this->forge->addUniqueKey(['user_id','completion_key'], $table . '_completion_unique');
                $this->forge->processIndexes($table);
            }
        }
    }
    public function down()
    {
        foreach (['mock_interview_sessions','salary_negotiation_sessions'] as $table) {
            $this->forge->dropKey($table, $table . '_completion_unique');
            $this->forge->dropColumn($table, 'completion_key');
        }
    }
}
