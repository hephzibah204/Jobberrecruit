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
        'is_verified',
        'is_visible',
        'notify_job_alerts',
        'notify_application_updates',
        'notify_messages',
        'notify_marketing',
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
            ->join('users', 'users.id = job_seekers.user_id', 'left')
            ->join('employers', 'employers.user_id = job_seekers.user_id', 'left')
            ->where('employers.id IS NULL')
            ->where('(users.user_type != "employer" AND users.user_type != "admin" AND users.user_type != "superadmin" OR users.user_type IS NULL)')
            ->where('job_seekers.is_visible', 1)
            ->orderBy('job_seekers.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getCandidates(array $filters = [], int $perPage = 20, bool $isAdmin = false)
    {
        $builder = $this->select([
            'job_seekers.*',
            'MAX(states.name) AS state_name',
            'MAX(auth_identities.secret) AS email',
            'MAX(users.username) AS username',
            'MAX(users.active) AS user_active',
            'COUNT(DISTINCT job_applications.id) AS total_applications',
            'COUNT(DISTINCT course_enrollments.id) AS total_courses'
        ])
            ->join('users', 'users.id = job_seekers.user_id', 'left')
            ->join('employers', 'employers.user_id = job_seekers.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = job_seekers.user_id AND auth_identities.type = "email_password"', 'left')
            ->join('states', 'states.id = job_seekers.state_id', 'left')
            ->join('job_applications', 'job_applications.job_seeker_id = job_seekers.id', 'left')
            ->join('course_enrollments', 'course_enrollments.user_id = job_seekers.user_id', 'left')
            ->where('employers.id IS NULL')
            ->where('(users.user_type != "employer" AND users.user_type != "admin" AND users.user_type != "superadmin" OR users.user_type IS NULL)')
            ->groupBy('job_seekers.id');

        // Only enforce is_visible if not admin or if explicitly requested
        if (!$isAdmin) {
            $builder->where('job_seekers.is_visible', 1);
            $builder->where('job_seekers.full_name IS NOT NULL AND job_seekers.full_name != ""', null, false);
            // Candidate eligibility (PDF Requirement 4.4): filter out incomplete profiles
            $builder->where('(job_seekers.profile_completion >= 50 OR (job_seekers.resume IS NOT NULL AND job_seekers.resume != "") OR (job_seekers.phone IS NOT NULL AND job_seekers.job_title IS NOT NULL))', null, false);
        } else {
            if (!empty($filters['visibility']) && $filters['visibility'] !== 'all') {
                if ($filters['visibility'] === 'visible') {
                    $builder->where('job_seekers.is_visible', 1);
                } elseif ($filters['visibility'] === 'hidden') {
                    $builder->where('job_seekers.is_visible', 0);
                }
            }
        }

        /* Keyword search */
        if (!empty($filters['keyword'])) {
            $kw = trim($filters['keyword']);
            $builder->groupStart()
                ->like('job_seekers.full_name', $kw)
                ->orLike('job_seekers.job_title', $kw)
                ->orLike('job_seekers.skills', $kw)
                ->orLike('job_seekers.phone', $kw)
                ->orLike('job_seekers.location', $kw)
                ->orLike('auth_identities.secret', $kw)
                ->orLike('users.username', $kw)
                ->groupEnd();
        }

        /* Skill filter */
        if (!empty($filters['skill'])) {
            $skills = array_filter((array)$filters['skill']);
            if (!empty($skills)) {
                $builder->groupStart();
                foreach ($skills as $sk) {
                    if (!empty(trim($sk))) {
                        $builder->orLike('job_seekers.skills', trim($sk));
                    }
                }
                $builder->groupEnd();
            }
        }

        /* State/Location / City (PDF Requirement 4.5) */
        if (!empty($filters['state_id']) && $filters['state_id'] !== 'all') {
            $builder->where('job_seekers.state_id', (int)$filters['state_id']);
        }
        if (!empty($filters['city']) && $filters['city'] !== 'all') {
            $cityVal = trim($filters['city']);
            $builder->groupStart()
                ->like('job_seekers.city', $cityVal)
                ->orLike('job_seekers.location', $cityVal)
                ->groupEnd();
        }
        if (!empty($filters['location']) && $filters['location'] !== 'all') {
            if (is_array($filters['location'])) {
                $builder->groupStart();
                foreach ($filters['location'] as $loc) {
                    $builder->orLike('job_seekers.location', $loc);
                }
                $builder->groupEnd();
            } else {
                $builder->like('job_seekers.location', $filters['location']);
            }
        }

        /* Activity / Visibility filter */
        if (!empty($filters['activity']) && $filters['activity'] !== 'all') {
            if ($filters['activity'] === 'this_week') {
                $builder->where('job_seekers.updated_at >=', date('Y-m-d H:i:s', strtotime('-7 days')));
            } elseif ($filters['activity'] === 'last_week') {
                $builder->where('job_seekers.updated_at >=', date('Y-m-d H:i:s', strtotime('-14 days')));
            } elseif ($filters['activity'] === 'this_month') {
                $builder->where('job_seekers.updated_at >=', date('Y-m-d H:i:s', strtotime('-30 days')));
            } elseif ($filters['activity'] === 'last_3_months') {
                $builder->where('job_seekers.updated_at >=', date('Y-m-d H:i:s', strtotime('-90 days')));
            } elseif ($filters['activity'] === 'older') {
                $builder->where('job_seekers.updated_at <', date('Y-m-d H:i:s', strtotime('-90 days')));
            }
        }

        /* Job type / Employment type */
        if (!empty($filters['employment_type']) && $filters['employment_type'] !== 'all') {
            if (is_array($filters['employment_type'])) {
                $builder->whereIn('job_seekers.employment_type', $filters['employment_type']);
            } else {
                $builder->where('job_seekers.employment_type', $filters['employment_type']);
            }
        }

        /* Experience Years */
        if (!empty($filters['experience_years']) && $filters['experience_years'] !== 'all') {
            $exp = $filters['experience_years'];
            if ($exp === '0-1') {
                $builder->where('job_seekers.experience_years <=', 1);
            } elseif ($exp === '1-3') {
                $builder->where('job_seekers.experience_years >=', 1)->where('job_seekers.experience_years <=', 3);
            } elseif ($exp === '3-5') {
                $builder->where('job_seekers.experience_years >=', 3)->where('job_seekers.experience_years <=', 5);
            } elseif ($exp === '5-10') {
                $builder->where('job_seekers.experience_years >=', 5)->where('job_seekers.experience_years <=', 10);
            } elseif ($exp === '10+') {
                $builder->where('job_seekers.experience_years >=', 10);
            } elseif (is_numeric($exp)) {
                $builder->where('job_seekers.experience_years >=', (int)$exp);
            }
        }

        /* Desired role / job title */
        if (!empty($filters['job_title']) && $filters['job_title'] !== 'all') {
            if (is_array($filters['job_title'])) {
                $builder->whereIn('job_seekers.job_title', $filters['job_title']);
            } else {
                $builder->where('job_seekers.job_title', $filters['job_title']);
            }
        }

        /* Education level */
        if (!empty($filters['education_level']) && $filters['education_level'] !== 'all') {
            if (is_array($filters['education_level'])) {
                $builder->whereIn('job_seekers.education_level', $filters['education_level']);
            } else {
                $builder->where('job_seekers.education_level', $filters['education_level']);
            }
        }

        /* Availability */
        if (!empty($filters['availability']) && $filters['availability'] !== 'all') {
            if (is_array($filters['availability'])) {
                $builder->whereIn('job_seekers.availability', $filters['availability']);
            } else {
                $builder->where('job_seekers.availability', $filters['availability']);
            }
        }

        /* Resume / CV filter */
        if (!empty($filters['resume_status']) && $filters['resume_status'] !== 'all') {
            if ($filters['resume_status'] === 'with_cv') {
                $builder->where('job_seekers.resume IS NOT NULL AND job_seekers.resume != ""', null, false);
            } elseif ($filters['resume_status'] === 'no_cv') {
                $builder->where('(job_seekers.resume IS NULL OR job_seekers.resume = "")', null, false);
            }
        }

        /* Verification filter */
        if (!empty($filters['verification_status']) && $filters['verification_status'] !== 'all') {
            if ($filters['verification_status'] === 'verified') {
                $builder->where('job_seekers.is_verified', 1);
            } elseif ($filters['verification_status'] === 'unverified') {
                $builder->where('(job_seekers.is_verified = 0 OR job_seekers.is_verified IS NULL)', null, false);
            }
        }

        switch ($filters['sort'] ?? 'best_match') {
            case 'most_experienced':
                $builder->orderBy('job_seekers.experience_years', 'DESC');
                break;
            case 'most_applications':
                $builder->orderBy('total_applications', 'DESC');
                break;
            case 'recently_active':
                $builder->orderBy('job_seekers.updated_at', 'DESC');
                break;
            case 'name_asc':
                $builder->orderBy('job_seekers.full_name', 'ASC');
                break;
            case 'newest':
                $builder->orderBy('job_seekers.created_at', 'DESC');
                break;
            case 'best_match':
            default:
                // Candidate Ranking Priority (PDF Requirement 4.2):
                // 1. Mostly/completely completed profile
                // 2. Uploaded CV
                // 3. Most recent login/activity
                $builder->orderBy('COALESCE(job_seekers.profile_completion, 0)', 'DESC');
                $builder->orderBy('(CASE WHEN job_seekers.resume IS NOT NULL AND job_seekers.resume != "" THEN 1 ELSE 0 END)', 'DESC', false);
                $builder->orderBy('(CASE WHEN job_seekers.phone IS NOT NULL AND job_seekers.job_title IS NOT NULL AND job_seekers.skills IS NOT NULL THEN 1 ELSE 0 END)', 'DESC', false);
                $builder->orderBy('job_seekers.updated_at', 'DESC');
                $builder->orderBy('job_seekers.created_at', 'DESC');
        }

        return $builder->paginate($perPage);
    }

    public function getCandidateStats(): array
    {
        $db = \Config\Database::connect();
        
        $total = $this->countAllResults(false);
        
        $visibleBuilder = clone $this->builder();
        $visible = $visibleBuilder->where('is_visible', 1)->countAllResults();
        
        $verifiedBuilder = clone $this->builder();
        $verified = $verifiedBuilder->where('is_verified', 1)->countAllResults();
        
        $resumeBuilder = clone $this->builder();
        $withResume = $resumeBuilder->where('resume IS NOT NULL AND resume != ""')->countAllResults();
        
        $totalApplications = 0;
        if ($db->tableExists('job_applications')) {
            $totalApplications = $db->table('job_applications')->countAllResults();
        }

        return [
            'total'               => $total,
            'visible'             => $visible,
            'verified'            => $verified,
            'with_resume'         => $withResume,
            'without_resume'      => max(0, $total - $withResume),
            'total_applications'  => $totalApplications,
        ];
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
        return $this->select('job_seekers.job_title, COUNT(*) AS total')
            ->join('users', 'users.id = job_seekers.user_id', 'left')
            ->join('employers', 'employers.user_id = job_seekers.user_id', 'left')
            ->where('employers.id IS NULL')
            ->where('(users.user_type != "employer" AND users.user_type != "admin" AND users.user_type != "superadmin" OR users.user_type IS NULL)')
            ->where('job_seekers.is_visible', 1)
            ->where('job_seekers.job_title IS NOT NULL')
            ->where('job_seekers.job_title !=', '')
            ->groupBy('job_seekers.job_title')
            ->orderBy('total', 'DESC')
            ->findAll();
    }

    public function countByEmploymentType()
    {
        return $this->select('job_seekers.employment_type, COUNT(*) AS total')
            ->join('users', 'users.id = job_seekers.user_id', 'left')
            ->join('employers', 'employers.user_id = job_seekers.user_id', 'left')
            ->where('employers.id IS NULL')
            ->where('(users.user_type != "employer" AND users.user_type != "admin" AND users.user_type != "superadmin" OR users.user_type IS NULL)')
            ->where('job_seekers.is_visible', 1)
            ->where('job_seekers.employment_type IS NOT NULL')
            ->groupBy('job_seekers.employment_type')
            ->findAll();
    }

    public function countByAvailability()
    {
        return $this->select('job_seekers.availability, COUNT(*) AS total')
            ->join('users', 'users.id = job_seekers.user_id', 'left')
            ->join('employers', 'employers.user_id = job_seekers.user_id', 'left')
            ->where('employers.id IS NULL')
            ->where('(users.user_type != "employer" AND users.user_type != "admin" AND users.user_type != "superadmin" OR users.user_type IS NULL)')
            ->where('job_seekers.is_visible', 1)
            ->where('job_seekers.availability IS NOT NULL')
            ->groupBy('job_seekers.availability')
            ->findAll();
    }

    public function countByEducation()
    {
        return $this->select('job_seekers.education_level, COUNT(*) AS total')
            ->join('users', 'users.id = job_seekers.user_id', 'left')
            ->join('employers', 'employers.user_id = job_seekers.user_id', 'left')
            ->where('employers.id IS NULL')
            ->where('(users.user_type != "employer" AND users.user_type != "admin" AND users.user_type != "superadmin" OR users.user_type IS NULL)')
            ->where('job_seekers.is_visible', 1)
            ->where('job_seekers.education_level IS NOT NULL')
            ->groupBy('job_seekers.education_level')
            ->findAll();
    }

    public function countBySkills()
    {
        return $this->select('job_seekers.skills, COUNT(*) AS total')
            ->join('users', 'users.id = job_seekers.user_id', 'left')
            ->join('employers', 'employers.user_id = job_seekers.user_id', 'left')
            ->where('employers.id IS NULL')
            ->where('(users.user_type != "employer" AND users.user_type != "admin" AND users.user_type != "superadmin" OR users.user_type IS NULL)')
            ->where('job_seekers.is_visible', 1)
            ->where('job_seekers.skills IS NOT NULL')
            ->groupBy('job_seekers.skills')
            ->findAll();
    }
}
