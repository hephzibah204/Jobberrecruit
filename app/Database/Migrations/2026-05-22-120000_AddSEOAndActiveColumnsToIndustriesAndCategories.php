<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSEOAndActiveColumnsToIndustriesAndCategories extends Migration
{
    public function up()
    {
        // Add columns to industries safely and idempotently
        if (!$this->db->fieldExists('is_active', 'industries')) {
            $this->forge->addColumn('industries', ['is_active' => ['type' => 'TINYINT', 'default' => 1]]);
        }
        if (!$this->db->fieldExists('description', 'industries')) {
            $this->forge->addColumn('industries', ['description' => ['type' => 'TEXT', 'null' => true]]);
        }
        if (!$this->db->fieldExists('meta_description', 'industries')) {
            $this->forge->addColumn('industries', ['meta_description' => ['type' => 'TEXT', 'null' => true]]);
        }
        if (!$this->db->fieldExists('seo_h1', 'industries')) {
            $this->forge->addColumn('industries', ['seo_h1' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true]]);
        }

        // Add columns to job_categories safely and idempotently
        if (!$this->db->fieldExists('is_active', 'job_categories')) {
            $this->forge->addColumn('job_categories', ['is_active' => ['type' => 'TINYINT', 'default' => 1]]);
        }
    }

    public function down()
    {
        foreach (['is_active', 'description', 'meta_description', 'seo_h1'] as $col) {
            if ($this->db->fieldExists($col, 'industries')) {
                $this->forge->dropColumn('industries', $col);
            }
        }
        if ($this->db->fieldExists('is_active', 'job_categories')) {
            $this->forge->dropColumn('job_categories', 'is_active');
        }
    }
}
