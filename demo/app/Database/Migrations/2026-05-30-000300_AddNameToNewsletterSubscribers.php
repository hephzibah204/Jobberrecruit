<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNameToNewsletterSubscribers extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('newsletter_subscribers') && !$this->db->fieldExists('name', 'newsletter_subscribers')) {
            $fields = [
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'email'
                ]
            ];
            $this->forge->addColumn('newsletter_subscribers', $fields);
        }
    }

    public function down()
    {
        if ($this->db->getPlatform() === 'SQLite3') {
            return;
        }

        if ($this->db->tableExists('newsletter_subscribers') && $this->db->fieldExists('name', 'newsletter_subscribers')) {
            $this->forge->dropColumn('newsletter_subscribers', 'name');
        }
    }
}
