<?php

namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table      = 'jobs';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\Job::class;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $skipValidation = true;
    protected $allowedFields = [
        'employer_id',
        'title',
        'slug',
        'description',
        'job_type',
        'state_id',
        'location_type',
        'show_salary',
        'salary_type',
        'salary_period',
        'salary',
        'salary_details', // Computed: e.g., "Fixed, Monthly: ₦500,000"
        'industry_id',
        'category_id',
        'education_level',
        'experience_level',
        'experience',
        'skills',
        'requirements',
        'application_method',
        'application_access',
        'whatsapp_link',
        'application_email',
        'external_url',
        'application_deadline',
        'start_date',
        'contact_email',
        'contact_phone',
        'application',
        // 'featured',
        'featured_until',
        'status',
        'is_featured',
        'is_urgent',
        'is_anonymous',
        'network_blast',
        'views',
        'accommodation',
        'notification_preferences',
        'admin_status',
        'admin_reviewed_at',
        'admin_notes',
        'is_verified'
    ];

    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (!isset($data['data']['title'])) {
            return $data;
        }

        $title = $data['data']['title'];
        $location = 'nigeria';

        $stateId = $data['data']['state_id'] ?? null;
        if (!$stateId && !empty($data['id'])) {
            $existingJob = $this->find(is_array($data['id']) ? $data['id'][0] : $data['id']);
            $stateId = $existingJob->state_id ?? null;
        }

        if ($stateId) {
            $stateModel = model('StateModel');
            $state = $stateModel->find($stateId);
            if ($state) {
                $stateNameLower = strtolower(trim($state->name));
                if ($stateNameLower === 'rivers') {
                    $location = 'port-harcourt';
                } elseif (in_array($stateNameLower, ['fct', 'federal capital territory'])) {
                    $location = 'abuja';
                } else {
                    $location = strtolower(trim($state->name));
                }
            }
        }

        // Pre-clean title and strip filler words
        $t = ' ' . strtolower($title) . ' ';
        $fillerWords = [' at ', ' in ', ' the ', ' for ', ' of ', ' a ', ' and ', ' & '];
        $t = str_replace($fillerWords, ' ', $t);

        // Strip special characters: & / \ . , ( ) ' " | – — : # ? !
        $t = str_replace(['&', '/', '\\', '.', ',', '(', ')', "'", '"', '|', '–', '—', ':', '#', '?', '!'], '', $t);

        // Replace spaces with hyphens
        $t = preg_replace('/\s+/', '-', trim($t));
        $locClean = preg_replace('/\s+/', '-', strtolower(trim($location)));

        // Avoid duplicating location if title already ends with the city/state name
        if (!str_ends_with($t, '-' . $locClean) && $t !== $locClean) {
            $slug = $t . '-' . $locClean;
        } else {
            $slug = $t;
        }

        // Clean up: remove non-alphanumeric characters except hyphens
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

        // Remove multiple hyphens
        $slug = preg_replace('/-+/', '-', $slug);

        // Trim hyphens
        $slug = trim($slug, '-');

        // Max 75 characters
        $slug = substr($slug, 0, 75);
        $slug = trim($slug, '-');

        // Ensure uniqueness
        $originalSlug = $slug;
        $count = 1;
        $currentId = is_array($data['id'] ?? null) ? ($data['id'][0] ?? 0) : ($data['id'] ?? 0);
        
        while ($this->db->table($this->table)->where('slug', $slug)->where('id !=', $currentId)->countAllResults() > 0) {
            $suffix = '-' . (++$count);
            $slug = substr($originalSlug, 0, 75 - strlen($suffix)) . $suffix;
        }

        $data['data']['slug'] = $slug;

        return $data;
    }


    /**
     * Fetch jobs matching alert preferences
     */
    public function getMatchingJobs(
        ?string $keyword,
        ?int $locationId,
        int $candidateId,
        ?string $since = null
    ): array {
        $builder = $this->select('jobs.*');

        // Keyword search
        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('title', $keyword)
                ->orLike('description', $keyword)
                ->groupEnd();
        }

        // Location filter
        if (!empty($locationId)) {
            $builder->where('state_id', $locationId);
        }

        // Candidate industry preferences
        $industryModel = model('JobSeekerIndustryModel');
        $industries = $industryModel
            ->where('job_seeker_id', $candidateId)
            ->findAll();

        if ($industries) {
            $ids = array_column($industries, 'industry_id');
            $builder->whereIn('industry_id', $ids);
        }

        // Only new jobs since last alert
        if ($since) {
            $builder->where('jobs.created_at >', $since);
        }

        // Only open jobs
        $builder->where('jobs.status', 'open');

        return $builder
            ->orderBy('jobs.created_at', 'DESC')
            ->limit(20)
            ->findAll();
    }

    public function getTopJobPositions(int $limit = 5)
    {
        return $this->select([
            'jobs.id',
            'jobs.title',
            'jobs.job_type',
            'employers.company_name',
            'employers.logo',
            'states.name AS state_name',
            'COUNT(job_applications.id) AS total_applications'
        ])
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_applications', 'job_applications.job_id = jobs.id', 'left')
            ->where('jobs.status', 'open')
            ->groupBy('jobs.id')
            ->orderBy('total_applications', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getRecentJobs(int $limit = 6)
    {
        return $this->select([
            'jobs.id',
            'jobs.title',
            'jobs.status',
            'jobs.created_at',
            'employers.company_name',
            'industries.name AS industry_name',
            'states.name AS state_name',
            'COUNT(job_applications.id) AS applications_count'
        ])
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_applications', 'job_applications.job_id = jobs.id', 'left')
            ->groupBy('jobs.id')
            ->orderBy('jobs.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
