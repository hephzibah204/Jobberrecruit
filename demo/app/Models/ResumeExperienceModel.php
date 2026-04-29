<?php
namespace App\Models;
use CodeIgniter\Model;

class ResumeExperienceModel extends Model
{
    protected $table      = 'resume_experiences';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\ResumeExperience::class;
    protected $allowedFields = ['resume_id', 'company', 'position', 'start_date', 'end_date', 'description', 'is_current'];
    protected $useTimestamps = true;
    protected $updatedField = ''; // experiences only have created_at in my migration
}
