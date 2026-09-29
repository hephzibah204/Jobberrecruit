<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFlyerAndCountsToWebinars extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = [];

        if (!$db->fieldExists('flyer_image', 'webinars')) {
            $fields['flyer_image'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'meeting_link',
            ];
        }

        if (!$db->fieldExists('registrants_count', 'webinars')) {
            $fields['registrants_count'] = [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'price',
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('webinars', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('webinars', ['flyer_image', 'registrants_count']);
    }
}
