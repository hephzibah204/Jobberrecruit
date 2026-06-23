<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourseCertificates extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'course_id' => ['type' => 'INT', 'unsigned' => true],
            'enrollment_id' => ['type' => 'INT', 'unsigned' => true],
            'certificate_code' => ['type' => 'VARCHAR', 'constraint' => '50', 'unique' => true],
            'issued_at' => ['type' => 'DATETIME'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('enrollment_id', 'course_enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('course_certificates', true);

        // Add completion status and completed_at to course_enrollments
        if (!$this->db->fieldExists('status', 'course_enrollments')) {
            $this->forge->addColumn('course_enrollments', [
                'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'enrolled'],
            ]);
        }
        if (!$this->db->fieldExists('completed_at', 'course_enrollments')) {
            $this->forge->addColumn('course_enrollments', [
                'completed_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
        }
        if (!$this->db->fieldExists('progress', 'course_enrollments')) {
            $this->forge->addColumn('course_enrollments', [
                'progress' => ['type' => 'INT', 'default' => 0],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('course_certificates');
        $this->forge->dropColumn('course_enrollments', ['status', 'completed_at', 'progress']);
    }
}
