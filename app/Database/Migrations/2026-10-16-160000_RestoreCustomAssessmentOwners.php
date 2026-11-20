<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RestoreCustomAssessmentOwners extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('employer_id', 'tests')) return;
        // Older custom tests may predate the ownership column and look global.
        foreach ($this->db->table('tests')->where('employer_id', null)->get()->getResultArray() as $test) {
            $ownerId = null;
            if (preg_match('/^custom-ai-([1-9][0-9]*)-[0-9]+$/D', $test['slug'], $match)) {
                $job = $this->db->table('jobs')->where('id', (int)$match[1])->get()->getRow();
                $ownerId = $job->employer_id ?? null;
            } elseif (preg_match('/-emp([1-9][0-9]*)(?:-[0-9]+)?$/D', $test['slug'], $match)) {
                $ownerId = (int)$match[1];
            }
            if ($ownerId && $this->db->table('employers')->where('id', $ownerId)->countAllResults() > 0) {
                $this->db->table('tests')->where('id', $test['id'])->where('employer_id', null)
                    ->update(['employer_id' => $ownerId]);
            }
        }
    }

    public function down()
    {
        // Preserve restored ownership; clearing it would expose private assessments.
    }
}
