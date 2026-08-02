<?php
namespace App\Models;
use CodeIgniter\Model;

class ResumeModel extends Model
{
    protected $table      = 'resumes';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\Resume::class;
    protected $allowedFields = ['user_id', 'title', 'summary', 'template_id', 'ai_optimization_meta', 'target_job_description'];
    protected $useTimestamps = true;
}
