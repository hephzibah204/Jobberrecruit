<?php
namespace App\Models;
use CodeIgniter\Model;

class ResumeSkillModel extends Model
{
    protected $table      = 'resume_skills';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\ResumeSkill::class;
    protected $allowedFields = ['resume_id', 'skill_name', 'proficiency_level'];
    protected $useTimestamps = true;
    protected $updatedField = '';
}
