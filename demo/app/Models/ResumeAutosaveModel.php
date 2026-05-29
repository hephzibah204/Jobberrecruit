<?php
namespace App\Models;

use CodeIgniter\Model;

class ResumeAutosaveModel extends Model
{
    protected $table = 'resume_autosaves';
    protected $primaryKey = 'id';
    protected $allowedFields = ['resume_id','user_id','payload','metadata','created_at'];
    protected $useTimestamps = false;

    // Helper to insert a JSON snapshot safely
    public function insertSnapshot($data)
    {
        if (isset($data['snapshot']) && is_array($data['snapshot'])) {
            $data['payload'] = json_encode($data['snapshot']);
            unset($data['snapshot']);
        }
        return $this->insert($data);
    }
}
