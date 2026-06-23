<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReorderEducationQualifications extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('qualifications');
        
        $order = [
            'Secondary School/High School' => 1,
            'OND' => 2,
            'HND' => 3,
            'Bachelor\'s Degree' => 4,
            'Master\'s Degree' => 5,
            'MBA' => 6,
            'PhD/Doctorate' => 7
        ];

        // Insert missing ones or update existing ones
        foreach ($order as $name => $index) {
            $existing = $builder->where('name', $name)->get()->getRow();
            if ($existing) {
                $builder->where('id', $existing->id)->update(['order_index' => $index, 'is_active' => 1]);
            } else {
                $builder->insert([
                    'name' => $name,
                    'order_index' => $index,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    public function down()
    {
        // No down migration
    }
}
