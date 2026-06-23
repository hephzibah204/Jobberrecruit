<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReferralTermsAcceptedToUsers extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('users') && !$this->db->fieldExists('referral_terms_accepted', 'users')) {
            $fields = [
                'referral_terms_accepted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                ],
            ];

            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users') && $this->db->fieldExists('referral_terms_accepted', 'users')) {
            if ($this->db->getPlatform() !== 'SQLite3') {
                $this->forge->dropColumn('users', 'referral_terms_accepted');
            }
        }
    }
}
