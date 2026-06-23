<?php
namespace App\Models;
use CodeIgniter\Model;

class ResumeEducationModel extends Model
{
    protected $table      = 'resume_education';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\ResumeEducation::class;
    protected $allowedFields = ['resume_id', 'institution', 'degree', 'field_of_study', 'graduation_date'];
    protected $useTimestamps = true;
    protected $updatedField = '';
}
