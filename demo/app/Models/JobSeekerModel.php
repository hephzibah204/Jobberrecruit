<?php

namespace App\Models;

use CodeIgniter\Model;

class JobSeekerModel extends Model
{
    protected $table      = 'job_seekers';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\JobSeeker::class;
    protected $allowedFields = [
        'user_id',
        'full_name',
        'profile_picture',
        'dob',
        'gender',
        'phone',
        'location',
        'bio',
        'job_title',
        'employment_type',
        'skills',
        'experience_years',
        'education_level',
        'languages',
        'resume',
        'cover_letter',
        'portfolio',
        'desired_salary',
        'salary_type',
        'availability',
        'state_id',
        'is_verified'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function countByGender()
    {
        return $this->select('gender, COUNT(*) as total')
            ->groupBy('gender')
            ->findAll();
    }

    public function countByGenderForYear(int $year)
    {
        return $this->select('gender, COUNT(*) as total')
            ->where('YEAR(created_at)', $year)
            ->groupBy('gender')
            ->findAll();
    }

    public function countByYearAndGender(int $year): array
    {
        return $this->select('gender, COUNT(*) AS total')
            ->where('YEAR(created_at)', $year)
            ->where('gender IS NOT NULL')
            ->groupBy('gender')
            ->findAll();
    }


    public function countByState()
    {
        return $this->select('state_id, COUNT(*) as total')
            ->groupBy('state_id')
            ->findAll();
    }

    public function countByStateForYear(int $year)
    {
        return $this->select('state_id, COUNT(*) as total')
            ->where('YEAR(created_at)', $year)
            ->groupBy('state_id')
            ->findAll();
    }

    public function getLastCandidates(int $limit = 5)
    {
        return $this->select([
            'job_seekers.id',
            'job_seekers.full_name',
            'job_seekers.employment_type',
            'job_seekers.experience_years',
            'job_seekers.created_at',
            'job_seekers.profile_picture'
        ])
            ->orderBy('job_seekers.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getCandidates(array $filters = [], int $perPage = 20)
    {
        $builder = $this->select([
            'job_seekers.id',
            'job_seekers.full_name',
            'job_seekers.profile_picture',
            'job_seekers.job_title',
            'job_seekers.education_level',
            'job_seekers.skills',
            'job_seekers.employment_type',
            'job_seekers.experience_years',
            'job_seekers.desired_salary',
            'job_seekers.salary_type',
            'job_seekers.availability',
            'states.name AS state_name',
        ])
            ->join('states', 'states.id = job_seekers.state_id', 'left');

        /* Keyword search */
        if (!empty($filters['keyword'])) {
            $builder->groupStart()
                ->like('job_seekers.full_name', $filters['keyword'])
                ->orLike('job_seekers.job_title', $filters['keyword'])
                ->groupEnd();
        }

        /* Location */
        if (!empty($filters['state_id'])) {
            $builder->where('job_seekers.state_id', $filters['state_id']);
        }

        /* Job type */
        if (!empty($filters['employment_type'])) {
            $builder->whereIn('job_seekers.employment_type', (array) $filters['employment_type']);
        }

        /* Experience */
        if (!empty($filters['experience_years'])) {
            $builder->where('job_seekers.experience_years >=', (int)$filters['experience_years']);
        }

        return $builder
            ->orderBy('job_seekers.created_at', 'DESC')
            ->paginate($perPage);
    }

    public function filter(array $filters)
    {
        if (!empty($filters['job_title'])) {
            $this->whereIn('job_title', $filters['job_title']);
        }

        if (!empty($filters['availability'])) {
            $this->whereIn('availability', $filters['availability']);
        }

        if (!empty($filters['employment_type'])) {
            $this->whereIn('employment_type', $filters['employment_type']);
        }

        if (!empty($filters['education_level'])) {
            $this->whereIn('education_level', $filters['education_level']);
        }

        return $this;
    }

    public function countByJobTitle()
    {
        return $this->select('job_title, COUNT(*) AS total')
            ->where('job_title IS NOT NULL')
            ->groupBy('job_title')
            ->orderBy('total', 'DESC')
            ->findAll();
    }

    public function countByEmploymentType()
    {
        return $this->select('employment_type, COUNT(*) AS total')
            ->groupBy('employment_type')
            ->findAll();
    }

    public function countByAvailability()
    {
        return $this->select('availability, COUNT(*) AS total')
            ->groupBy('availability')
            ->findAll();
    }

    public function countByEducation()
    {
        return $this->select('education_level, COUNT(*) AS total')
            ->groupBy('education_level')
            ->findAll();
    }

    public function countBySkills() // skills are separated by comma
    {
        return $this->select('skills, COUNT(*) AS total')
            ->groupBy('skills')
            ->findAll();
    }
}
