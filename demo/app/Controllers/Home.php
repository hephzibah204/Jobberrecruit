<?php

namespace App\Controllers;

use App\Models\BlogModel;
use App\Models\EmployerIndustryModel;
use App\Models\EmployerModel;
use App\Models\IndustryModel;
use App\Models\JobAlertModel;
use App\Models\JobCategoryModel;
use App\Models\JobClickModel;
use App\Models\UserSubscriptionModel;
use App\Models\JobModel;
use App\Models\JobSeekerModel;
use App\Models\StateModel; // assuming you have a Country model
use App\Models\TestimonialModel;
use App\Models\UserModel;
use CodeIgniter\Throttle\Throttler;
use CodeIgniter\Shield\Models\UserModel as ModelsUserModel;

class Home extends BaseController
{
    protected $auth;
    protected $config;
    protected $users;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->auth = service('auth');
        $this->config = config('Auth');
        helper(['auth', 'text', 'form', 'url', 'env', 'category', 'time']);
        $this->users = model(UserModel::class);
        $this->userModel = model(ModelsUserModel::class);
        $this->session = \Config\Services::session();
    }
    public function home()
    {
        $industryModel = new IndustryModel();
        $stateModel = new StateModel();
        $categoryModel = new JobCategoryModel();
        $jobModel = new JobModel();
        $cache = \Config\Services::cache();

        // foreach ($activeSubs as $sub) {
        //     $plan = $sub->getPlan();

        //     (new JobCreditWalletModel())
        //         ->where('user_id', $sub->user_id)
        //         ->where('source', 'subscription')
        //         ->set(['credits' => $plan->monthly_job_credits])
        //         ->update();
        // }

        // Cache key for popular vacancies
        $popularVacanciesCacheKey = 'popular_vacancies';
        $popular_vacancies = $cache->get($popularVacanciesCacheKey);

        if (!$popular_vacancies) {
            // Fetch top 12 positions with job counts
            $popular_vacancies = $jobModel
                ->select([
                    'jobs.title',
                    'COUNT(jobs.id) as job_count',
                    'MAX(employers.company_name) as company_name',
                    'MAX(employers.logo) as company_logo'
                ])
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                ->groupBy('jobs.title')
                ->orderBy('job_count', 'DESC')
                ->findAll(12);

            foreach ($popular_vacancies as &$vacancy) {
                $vacancy->formatted_count = number_format($vacancy->job_count) . ' Open Positions';
            }

            $cache->save($popularVacanciesCacheKey, $popular_vacancies, 60); // 1 minutes
        }

        // Bind URLs dynamically at runtime
        foreach ($popular_vacancies as &$vacancy) {
            $vacancy->url = base_url('jobs?title=' . urlencode($vacancy->title));
        }

        // Fetch categories with job counts (top 12 for popular categories)
        $categoriesCacheKey = 'categories_with_counts';
        $categories = $cache->get($categoriesCacheKey);

        if (!$categories) {
            // $categories = $categoryModel
            //     ->select('job_categories.*, COUNT(jobs.id) as job_count')
            //     ->join('jobs', 'jobs.category_id = job_categories.id', 'left')
            //     ->where('job_categories.parent_id', null)
            //     ->groupBy('job_categories.id')
            //     ->orderBy('job_count', 'DESC')
            //     ->findAll(12);
            $categories = $industryModel
                ->select('industries.*, COUNT(jobs.id) as job_count')
                ->join('jobs', 'jobs.industry_id = industries.id', 'left')
                ->where('industries.parent_id', null)
                ->groupBy('industries.id')
                ->orderBy('job_count', 'DESC')
                ->findAll(12);

            // Map icons to categories (example mapping, adjust as needed)
            $iconMap = [
                'Agriculture' => 'bi-tree',
                'Education' => 'bi-mortarboard',
                'Media' => 'bi-broadcast-pin',
                'Healthcare' => 'bi-heart-pulse',
                'Medical' => 'bi-heart-pulse',
                'Construction' => 'bi-building',
                'Real Estate' => 'bi-building',
                'Arts' => 'bi-palette',
                'Entertainment' => 'bi-palette',
                'Finance' => 'bi-bank',
                'Banking' => 'bi-bank',
                'Hospitality' => 'bi-geo-alt',
                'Tourism' => 'bi-geo-alt'
            ];

            foreach ($categories as &$category) {
                $category->formatted_count = number_format($category->job_count) . ' Open Position' . ($category->job_count !== 1 ? 's' : '');
                
                // Flexible matching
                $category->icon = 'bi-briefcase'; // Default
                foreach ($iconMap as $key => $icon) {
                    if (stripos($category->name, $key) !== false) {
                        $category->icon = $icon;
                        break;
                    }
                }
            }

            $cache->save($categoriesCacheKey, $categories, 3600);
        }

        // Bind URLs dynamically at runtime
        foreach ($categories as &$category) {
            $category->url = base_url(($category->slug ?? url_title($category->name, '-', true)) . '-jobs');
        }

        // Fetch recent jobs (limit to 18)
        $jobsCacheKey = 'recent_jobs';
        $jobs = $cache->get($jobsCacheKey);

        if (!$jobs) {
            $jobs = $jobModel
                ->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.user_id as employer_user_id, employers.logo as company_logo, employers.is_verified')
                ->where('status', 'open')
                ->join('states', 'states.id = jobs.state_id', 'left')
                ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
                ->join('industries', 'industries.id = jobs.industry_id', 'left')
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                // ->orderBy('jobs.created_at', 'DESC')
                ->orderBy('jobs.is_featured', 'DESC')->orderBy('jobs.featured_until', 'DESC')
                ->findAll(18);

            $cache->save($jobsCacheKey, $jobs, 3600);
        }

        $employerUserIds = [];
        foreach ($jobs as $job) {
            if (!empty($job->employer_user_id)) {
                $employerUserIds[] = $job->employer_user_id;
            }
        }

        $activeSubsMap = [];
        if (!empty($employerUserIds)) {
            $subscriptionModel = model(UserSubscriptionModel::class);
            $activeSubs = $subscriptionModel
                ->select('user_subscriptions.user_id, plans.features')
                ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
                ->where('user_subscriptions.is_active', 1)
                ->whereIn('user_subscriptions.user_id', array_unique($employerUserIds))
                ->findAll();

            foreach ($activeSubs as $sub) {
                $userId = is_array($sub) ? $sub['user_id'] : $sub->user_id;
                $features = is_array($sub) ? $sub['features'] : $sub->features;
                $activeSubsMap[$userId] = $features;
            }
        }

        foreach ($jobs as &$job) {
            $planFeatures = [];
            $featuresJson = $activeSubsMap[$job->employer_user_id] ?? null;

            if ($featuresJson && !empty($featuresJson)) {
                $planFeatures = planFeatures(json_decode($featuresJson, true));
            }

            // TRUST BADGE
            $job->show_trust_badge =
                !empty($planFeatures['trust_badge']) && ($job->is_verified == 1);

            $job->anonymous = !empty($planFeatures['anonymous']) && ($job->is_anonymous);

            // ANONYMOUS POSTING
            if (!empty($planFeatures['anonymous']) && ($job->is_anonymous)) {
                $job->employer_name = 'Confidential Employer';
                $job->company_logo  = base_url('images/favicon.png');
            }
        }

        $featuredJobsCacheKey = 'featured_jobs';
        $featured_jobs = $cache->get($featuredJobsCacheKey);
        if (!$featured_jobs) {
            $featured_jobs = $jobModel
                ->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.logo as company_logo')
                ->join('states', 'states.id = jobs.state_id', 'left')
                ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
                ->join('industries', 'industries.id = jobs.industry_id', 'left')
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                ->where('status', 'open')
                ->where('jobs.is_featured', 1)
                ->orderBy('jobs.created_at', 'DESC')
                ->findAll(18);
            $cache->save($featuredJobsCacheKey, $featured_jobs, 60);
        }

        // Fetch Top Companies
        $topCompaniesCacheKey = 'top_companies';
        $top_companies = $cache->get($topCompaniesCacheKey);

        if (!$top_companies) {
            $top_companies = $jobModel
                ->select('employers.id, employers.company_name, employers.user_id as employer_user_id, employers.logo, employers.state_id, COUNT(jobs.id) as job_count, states.name as location, employers.is_verified')
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                ->join('states', 'states.id = employers.state_id', 'left')
                ->where('is_anonymous', '0')
                ->groupBy('employers.id')
                ->orderBy('job_count', 'DESC')
                ->limit(18)
                ->findAll();

            $companyUserIds = [];
            foreach ($top_companies as $company) {
                if (!empty($company->employer_user_id)) {
                    $companyUserIds[] = $company->employer_user_id;
                }
            }

            $activeSubsMapComp = [];
            if (!empty($companyUserIds)) {
                $subscriptionModel = model(UserSubscriptionModel::class);
                $activeSubsComp = $subscriptionModel
                    ->select('user_subscriptions.user_id, plans.features')
                    ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
                    ->where('user_subscriptions.is_active', 1)
                    ->whereIn('user_subscriptions.user_id', array_unique($companyUserIds))
                    ->findAll();

                foreach ($activeSubsComp as $sub) {
                    $userId = is_array($sub) ? $sub['user_id'] : $sub->user_id;
                    $features = is_array($sub) ? $sub['features'] : $sub->features;
                    $activeSubsMapComp[$userId] = $features;
                }
            }

            foreach ($top_companies as &$company) {
                $planFeatures = [];
                $featuresJson = $activeSubsMapComp[$company->employer_user_id] ?? null;

                if ($featuresJson && !empty($featuresJson)) {
                    $planFeatures = planFeatures(json_decode($featuresJson, true));
                }

                $company->logo = $company->logo ? base_url($company->logo) : base_url('images/default-company.png');
                $company->url = base_url('employer/' . urlencode($company->id));
                $company->formatted_count = number_format($company->job_count) . ' Open Position' . ($company->job_count != 1 ? 's' : '');
                
                // TRUST BADGE
                $company->show_trust_badge =
                    !empty($planFeatures['trust_badge']) && ($company->is_verified == 1);
            }

            $cache->save($topCompaniesCacheKey, $top_companies, 60);
        }


        // Fetch industries and states
        $industriesCacheKey = 'industries';
        $industries = $cache->get($industriesCacheKey) ?: $industryModel->orderBy('name', 'ASC')->findAll();
        $cache->save($industriesCacheKey, $industries, 60);

        $statesCacheKey = 'states';
        $states = $cache->get($statesCacheKey) ?: $stateModel->orderBy('name', 'ASC')->findAll();
        $cache->save($statesCacheKey, $states, 60);

        // Top Locations with open job counts
        $topLocationsCacheKey = 'top_locations_home_v2';
        $top_locations = $cache->get($topLocationsCacheKey);
        if (!$top_locations) {
            $db = db_connect();
            $top_locations = $db->table('states')
                ->select('states.id, states.name, states.slug, COUNT(jobs.id) as job_count')
                ->join('jobs', 'jobs.state_id = states.id AND jobs.status = "open"', 'left')
                ->groupBy('states.id')
                ->orderBy('job_count', 'DESC')
                ->limit(8)
                ->get()
                ->getResultObject();

            foreach ($top_locations as $loc) {
                $loc->formatted_count = number_format((int)$loc->job_count) . ' job' . ((int)$loc->job_count !== 1 ? 's' : '');
            }
            $cache->save($topLocationsCacheKey, $top_locations, 3600);
        }

        // Bind URLs dynamically at runtime
        foreach ($top_locations as $loc) {
            $loc->url = base_url('jobs-in-' . ($loc->slug ?? strtolower(str_replace(' ', '-', $loc->name)) . '-state'));
        }

        $employerModel = new \App\Models\EmployerModel();
        $jobAppModel = new \App\Models\JobApplicationModel();

        $activeJobsCount = $jobModel->where('status', 'open')->countAllResults();

        $verifiedEmployersCount = $employerModel->groupStart()
            ->where('verification_status', 'verified')
            ->orWhere('is_verified', 1)
            ->groupEnd()
            ->countAllResults();

        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        $monthlyApplicantsCount = $jobAppModel->where('created_at >=', $thirtyDaysAgo)->countAllResults();

        $totalApps = $jobAppModel->countAllResults();
        $hiredApps = $jobAppModel->where('status', 'hired')->countAllResults();
        $placementSuccess = $totalApps > 0 ? round(($hiredApps / $totalApps) * 100) : 95;

        $data = [
            'title' => 'Find Jobs in Nigeria | JobberRecruit — Hire Top Talent',
            'meta_description' => 'Find verified jobs across Nigeria on JobberRecruit. Browse thousands of opportunities in Lagos, Abuja, Port Harcourt and more. Employers can post jobs and hire top Nigerian talent today.',
            'og_title' => 'JobberRecruit — Nigeria\'s Leading Job Portal',
            'og_description' => 'Find verified jobs and hire top talent across Nigeria. Browse thousands of opportunities in Lagos, Abuja, and more.',
            'og_image' => base_url('images/default-og-image.jpg'),
            'auth' => $this->auth,
            'categories' => $categories,
            'jobs' => $jobs,
            'industries' => $industries,
            'states' => $states,
            'top_locations' => $top_locations,
            'top_companies' => $top_companies,
            'featured_jobs' => $featured_jobs,
            'popular_vacancies' => $popular_vacancies,
            'website_logo' => 'assets/imgs/page/homepage4/banner.png',
            'activeJobsCount' => $activeJobsCount,
            'verifiedEmployersCount' => $verifiedEmployersCount,
            'monthlyApplicantsCount' => $monthlyApplicantsCount,
            'placementSuccess' => $placementSuccess,
            'testimonials' => model(\App\Models\TestimonialModel::class)->where('status', 'active')->orderBy('created_at', 'DESC')->findAll(),
            'q' => $this->request->getGet('q') ?? ''
        ];

        return view('home/index', $data);
    }

    public function location_hub($slug)
    {
        $stateModel = model(StateModel::class);
        $jobModel   = new \App\Models\JobModel();

        // --- 1. Resolve state by slug (most reliable) ---
        $state = $stateModel->where('slug', $slug)->first();

        // Fallback: strip "-state" suffix and try by name
        if (!$state) {
            $stateName = ucwords(str_replace('-', ' ', preg_replace('/-state$/', '', $slug)));
            $state = $stateModel->where('name', $stateName)->first()
                ?? $stateModel->like('name', $stateName, 'none')->first();
        }

        // Fallback: handle "fct-abuja", "abuja", etc. → search for Abuja/FCT
        if (!$state && (str_contains($slug, 'fct') || str_contains($slug, 'abuja'))) {
            $state = $stateModel->groupStart()
                ->like('name', 'FCT', 'none')
                ->orLike('name', 'Abuja', 'none')
                ->orLike('name', 'Federal Capital', 'after')
                ->groupEnd()
                ->first();
        }

        if (!$state) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Location not found: $slug");
        }

        // --- 2. Fetch jobs for this state (latest 12) ---
        $db       = db_connect();
        $jobs     = $db->table('jobs')
            ->select('jobs.*, employers.company_name AS employer_name, employers.logo AS company_logo,
                      industries.name AS industry_name, states.name AS state_name')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->where('jobs.state_id', $state->id)
            ->where('jobs.status', 'open')
            ->orderBy('jobs.created_at', 'DESC')
            ->limit(12)
            ->get()
            ->getResultObject();

        $totalJobs = $db->table('jobs')
            ->where('state_id', $state->id)
            ->where('status', 'open')
            ->countAllResults();

        // --- 3. Fetch all active states for the sidebar/related links ---
        $allStates = $stateModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        // --- 4. Build SEO fields ---
            $seoH1   = !empty($state->seo_h1)
            ? $state->seo_h1
            : 'Jobs in ' . $state->name . ', Nigeria — Find Verified Vacancies';
        $seoMeta = !empty($state->meta_description)
            ? $state->meta_description
            : 'Browse verified jobs in ' . $state->name . '. Apply to the latest vacancies from top employers in Nigeria.';

        return view('home/location_hub', [
            'title'            => $seoH1,
            'meta_description' => $seoMeta,
            'og_title'         => $seoH1,
            'og_description'   => $seoMeta,
            'og_image'         => base_url('images/default-og-image.jpg'),
            'state'            => $state,
            'jobs'             => $jobs,
            'total_jobs'       => $totalJobs,
            'all_states'       => $allStates,
            'auth'             => $this->auth,
        ]);
    }

    public function industry_hub($slug)
    {
        $industryModel = model(IndustryModel::class);
        $categoryModel = model(JobCategoryModel::class);

        // Strip the trailing "-jobs" that the route captures
        $industrySlug = preg_replace('/-jobs$/', '', $slug);

        // Fuzzy matches / aliases for common footer links
        $aliases = [
            'it-software'     => 'information-technology',
            'banking-finance' => 'finance-banking',
            'healthcare'      => 'healthcare-medical',
            'marketing-sales' => 'marketing-sales'
        ];

        if (isset($aliases[$industrySlug])) {
            $industrySlug = $aliases[$industrySlug];
        }
        if (isset($aliases[$slug])) {
            $slug = $aliases[$slug];
        }

        $isCategory = false;
        // Lookup by slug (primary: Industry)
        $industry = $industryModel->where('slug', $industrySlug)->first()
            ?? $industryModel->where('slug', $slug)->first();

        // Secondary: Job Category
        if (!$industry) {
            $category = $categoryModel->where('slug', $industrySlug)->first()
                ?? $categoryModel->where('slug', $slug)->first();
            if ($category) {
                $isCategory = true;
                // Duck-type Category as Industry for view compatibility
                $industry = (object)[
                    'id'               => $category->id,
                    'name'             => $category->name,
                    'slug'             => $category->slug,
                    'created_at'       => $category->created_at ?? null,
                    'updated_at'       => $category->updated_at ?? null,
                    'is_active'        => $category->is_active ?? 1,
                    'description'      => $category->description ?? null,
                    'meta_description' => $category->meta_description ?? null,
                    'seo_h1'           => $category->seo_h1 ?? null,
                ];
            }
        }

        if (!$industry) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Category or Industry not found: $slug");
        }

        // Fetch latest 12 open jobs for this industry or category
        $db      = db_connect();
        $builder = $db->table('jobs')
            ->select('jobs.*, employers.company_name AS employer_name, employers.logo AS company_logo,
                      states.name AS state_name')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->join('states',    'states.id    = jobs.state_id',    'left');

        if ($isCategory) {
            $builder->select('job_categories.name AS industry_name')
                ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
                ->where('jobs.category_id', $industry->id);
        } else {
            $builder->select('industries.name AS industry_name')
                ->join('industries', 'industries.id = jobs.industry_id', 'left')
                ->where('jobs.industry_id', $industry->id);
        }

        $jobs = $builder->where('jobs.status', 'open')
            ->orderBy('jobs.is_featured', 'DESC')
            ->orderBy('jobs.created_at',  'DESC')
            ->limit(12)
            ->get()
            ->getResultObject();

        $totalJobs = $db->table('jobs')
            ->where($isCategory ? 'category_id' : 'industry_id', $industry->id)
            ->where('status', 'open')
            ->countAllResults();

        // Fetch sibling industries for the "Browse other categories" section
        $allIndustries = $industryModel
            ->where('is_active', 1)
            ->where('parent_id', null)
            ->orderBy('name', 'ASC')
            ->findAll();

        // Build SEO fields
        $seoH1   = !empty($industry->seo_h1)
            ? $industry->seo_h1
            : $industry->name . ' Jobs in Nigeria';
        $seoMeta = !empty($industry->meta_description)
            ? $industry->meta_description
            : 'Browse verified ' . $industry->name . ' jobs in Nigeria. Apply to the latest vacancies from top employers today.';

        return view('home/industry_hub', [
            'title'            => $seoH1,
            'meta_description' => $seoMeta,
            'og_title'         => $seoH1,
            'og_description'   => $seoMeta,
            'og_image'         => base_url('images/default-og-image.jpg'),
            'industry'         => $industry,
            'jobs'             => $jobs,
            'total_jobs'       => $totalJobs,
            'all_industries'   => $allIndustries,
            'auth'             => $this->auth,
            'is_category'      => $isCategory,
        ]);
    }

    public function jobs($overrideTitle = null, $overrideMeta = null)
    {
        // We need to use humanize() in frontend
        helper('text');
        $jobModel = model(JobModel::class);
        $categoryModel = model(JobCategoryModel::class);
        $industryModel = model(IndustryModel::class);
        $stateModel = model(StateModel::class);

        // Get query parameters with sanitization
        $industryId = $this->request->getGet('industry_id') ? (int)$this->request->getGet('industry_id') : null;
        $categoryId = $this->request->getGet('category_id') ? (int)$this->request->getGet('category_id') : null;
        $stateId = $this->request->getGet('state_id') ? (int)$this->request->getGet('state_id') : null;
        $experienceLevel = $this->request->getGet('experience_level') ? htmlspecialchars(trim($this->request->getGet('experience_level')), ENT_QUOTES, 'UTF-8') : null;
        $salaryMin = $this->request->getGet('salary_min') ? (float)$this->request->getGet('salary_min') : null;
        $salaryMax = $this->request->getGet('salary_max') ? (float)$this->request->getGet('salary_max') : null;
        $keywords = $this->request->getGet('keywords') ? htmlspecialchars(trim($this->request->getGet('keywords')), ENT_QUOTES, 'UTF-8') : null;
        $position = $this->request->getGet('position') ? htmlspecialchars(trim($this->request->getGet('position')), ENT_QUOTES, 'UTF-8') : null;
        $workArrangement = $this->request->getGet('work_arrangement') ? htmlspecialchars(trim($this->request->getGet('work_arrangement')), ENT_QUOTES, 'UTF-8') : null;
        $jobTypeRaw = $this->request->getGet('job_type');
        if (is_array($jobTypeRaw)) {
            $validTypes = ['full-time','part-time','contract','freelance','internship'];
            $jobType = !empty($jobTypeRaw) ? implode(',', array_intersect($jobTypeRaw, $validTypes)) : null;
        } else {
            $jobType = $jobTypeRaw ? htmlspecialchars(trim($jobTypeRaw), ENT_QUOTES, 'UTF-8') : null;
        }
        $jobPosted = $this->request->getGet('job_posted') ? htmlspecialchars(trim($this->request->getGet('job_posted')), ENT_QUOTES, 'UTF-8') : null;
        $sortBy = $this->request->getGet('sort_by') ?? 'newest';
        $perPage = (int)($this->request->getGet('per_page') ?? 20);
        $page = (int)($this->request->getGet('page') ?? 1);
        $viewMode = $this->request->getGet('view_mode') ?? 'grid';
        $offset = max(0, ($page - 1) * $perPage);
        if ($perPage <= 0) {
            $perPage = 12;
        }

        // Build base query
        $query = $jobModel->select(
            'jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, 
            employers.is_verified, employers.company_name as employer_name, employers.logo as company_logo, employers.user_id as employer_user_id')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('status', 'open');

        $selectedIndustryName = null;
        $selectedStateName = null;

        // Apply filters
        if ($industryId) {
            $query->where('jobs.industry_id', $industryId);
            $ind = $industryModel->find($industryId);
            if ($ind) $selectedIndustryName = $ind->name;
        }
        if ($categoryId) {
            $query->where('jobs.category_id', $categoryId);
            $cat = $categoryModel->find($categoryId);
            if ($cat) $selectedIndustryName = $cat->name;
        }
        if ($stateId) {
            $query->where('jobs.state_id', $stateId);
            $st = $stateModel->find($stateId);
            if ($st) $selectedStateName = $st->name;
        }
        if ($experienceLevel) {
            $query->where('jobs.experience_level', $experienceLevel);
        }
        if ($salaryMin !== null) {
            $query->where('jobs.salary >=', $salaryMin);
        }
        if ($salaryMax !== null) {
            $query->where('jobs.salary <=', $salaryMax);
        }
        if ($keywords) {
            $query->groupStart()
                ->like('jobs.title', "%{$keywords}%")
                ->orLike('jobs.skills', "%{$keywords}%")
                ->orLike('jobs.description', "%{$keywords}%")
                ->groupEnd();
        }
        if ($position) {
            $query->where('jobs.position', $position);
        }
        if ($workArrangement) {
            $query->where('jobs.work_arrangement', $workArrangement);
        }
        if ($jobType) {
            $types = explode(',', $jobType);
            count($types) > 1 ? $query->whereIn('jobs.job_type', $types) : $query->where('jobs.job_type', $jobType);
        }
        if ($jobPosted) {
            $date = new \DateTime();
            switch ($jobPosted) {
                case '1_day':
                    $date->modify('-1 day');
                    break;
                case '7_days':
                    $date->modify('-7 days');
                    break;
                case '30_days':
                    $date->modify('-30 days');
                    break;
                default:
                    $date = null;
            }
            if ($date) {
                $query->where('jobs.created_at >=', $date->format('Y-m-d H:i:s'));
            }
        }

        // Always prioritize promoted (featured) jobs
        $query->orderBy('jobs.is_featured', 'DESC')->orderBy('jobs.featured_until', 'DESC');

        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('jobs.created_at', 'ASC');
                break;
            case 'salary_high':
                $query->orderBy('jobs.salary', 'DESC');
                break;

            case 'salary_low':
                $query->orderBy('jobs.salary', 'ASC');
                break;

            case 'rating':
                $query->orderBy('jobs.rating', 'DESC');
                break;

            default:
                $query->orderBy('jobs.created_at', 'DESC');
        }

        // Get total count for pagination
        $totalJobs = $query->countAllResults(false);

        // Get paginated results
        $jobs = $query->findAll($perPage, $offset);

        $planFeatures = [];

        // Optimized: Get all employer IDs first to fetch subscriptions in one batch
        $employerUserIds = array_unique(array_column($jobs, 'employer_user_id'));
        $planFeaturesMap = [];

        if (!empty($employerUserIds)) {
            $subscriptionModel = model(UserSubscriptionModel::class);
            $subscriptions = $subscriptionModel
                ->select('user_subscriptions.user_id, plans.features')
                ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
                ->where('user_subscriptions.is_active', 1)
                ->whereIn('user_subscriptions.user_id', $employerUserIds)
                ->findAll();

            foreach ($subscriptions as $sub) {
                if (!empty($sub['features'])) {
                    $planFeaturesMap[$sub['user_id']] = planFeatures(json_decode($sub['features'], true));
                }
            }
        }

        foreach ($jobs as &$job) {
            $planFeatures = $planFeaturesMap[$job->employer_user_id] ?? [];

            // TRUST BADGE
            $job->show_trust_badge = !empty($planFeatures['trust_badge']) && ($job->is_verified == 1);
            
            $job->anonymous = !empty($planFeatures['anonymous']) && ($job->is_anonymous);

            // ANONYMOUS POSTING
            if ($job->anonymous) {
                $job->employer_name = 'Confidential Employer';
                $job->company_logo  = base_url('images/favicon.png');
            }
        }

        // Dynamic counts for filters (computed without user filters for global availability)
        $industryCounts = $jobModel->select('industries.id, industries.name, COUNT(jobs.id) as job_count')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->groupBy('industries.id')
            ->findAll();

        $jobTypeCountsRaw = $jobModel->select('job_type, COUNT(id) as count')
            ->groupBy('job_type')
            ->findAll();
        $job_type_counts = [];
        foreach ($jobTypeCountsRaw as $row) {
            $job_type_counts[$row->job_type] = $row->count;
        }

        $experienceLevelCountsRaw = $jobModel->select('experience_level, COUNT(id) as count')
            ->groupBy('experience_level')
            ->findAll();
        $experience_level_counts = [];
        foreach ($experienceLevelCountsRaw as $row) {
            $experience_level_counts[$row->experience_level] = $row->count;
        }
        // For job posted counts (example dynamic computation)
        $jobPostedCounts = [
            '1_day' => $jobModel->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 day')))->countAllResults(),
            '7_days' => $jobModel->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))->countAllResults(),
            '30_days' => $jobModel->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))->countAllResults(),
        ];

        // Optimized: Removed heavy skill parsing from all jobs. 
        // We can use a static or cached list of top keywords instead.
        $keywordCounts = [];

        // Position counts (dynamic)
        $positionCountsRaw = $jobModel->builder()->select('title, COUNT(id) as count')
            ->groupBy('title')
            ->get()
            ->getResult();
        $position_counts = [];
        foreach ($positionCountsRaw as $row) {
            $position_counts[$row->title ?? 'Unknown'] = $row->count;
        }

        // Set page title based on filters
        $title = $overrideTitle ?? (($industryId || $categoryId || $stateId || $experienceLevel || $salaryMin !== null || $salaryMax !== null || $keywords || $position || $workArrangement || $jobType || $jobPosted)
            ? 'Filtered Job Search Results | JobberRecruit'
            : 'Browse Jobs in Nigeria — JobberRecruit');

        $data = [
            'title' => $title,
            'meta_description' => $overrideMeta ?? 'Browse and apply for the latest job opportunities across Nigeria. Verified vacancies from top employers in Lagos, Abuja, and more.',
            'og_title' => $title,
            'og_description' => $overrideMeta ?? 'Browse and apply for the latest job opportunities across Nigeria. Verified vacancies from top employers in Lagos, Abuja, and more.',
            'og_image' => base_url('images/default-og-image.jpg'),
            'noindex' => empty($jobs),
            'auth' => $this->auth,
            'jobs' => $jobs,
            'total_jobs' => $totalJobs,
            'categories' => $categoryModel->where('parent_id', null)->findAll(),
            'industries' => $industryModel->orderBy('name', 'ASC')->findAll(),
            'states' => $stateModel->orderBy('name', 'ASC')->findAll(),
            'website_logo' => 'assets/imgs/logo.png',
            'industry_counts' => $industryCounts,
            'keyword_counts' => $keywordCounts,
            'position_counts' => $position_counts,
            'experience_level_counts' => $experience_level_counts,
            // 'work_arrangement_counts' => $work_arrangement_counts,
            'job_type_counts' => $job_type_counts,
            'job_posted_counts' => $jobPostedCounts,
            'current_page' => $page,
            'per_page' => $perPage,
            'sort_by' => $sortBy,
            'industryId' => $industryId,
            'categoryId' => $categoryId,
            'stateId' => $stateId,
            'selectedIndustryName' => $selectedIndustryName,
            'selectedStateName' => $selectedStateName,
            'experienceLevel' => $experienceLevel,
            'salaryMin' => $salaryMin,
            'salaryMax' => $salaryMax,
            'keywords' => $keywords,
            'position' => $position, // Fixed typo from 'positionss'
            // 'workArrangement' => $workArrangement,
            'jobType' => $jobType,
            'jobPosted' => $jobPosted,
            'view_mode' => $viewMode,
        ];

        if ($this->request->isAJAX()) {
            // Prepare jobs with detail_url for JS
            foreach ($data['jobs'] as &$job) {
                $job->detail_url = base_url('job/view/' . $job->id);
                // $job->company_logo = base_url($job->company_logo ?? 'images/favicon.png');
                if ($job->created_at instanceof \DateTimeInterface) {
                    $job->created_at = $job->created_at->format('Y-m-d H:i:s');
                } elseif (is_object($job->created_at) && method_exists($job->created_at, 'toDateTimeString')) {
                    $job->created_at = $job->created_at->toDateTimeString();
                } elseif (is_object($job->created_at) && isset($job->created_at->date)) {
                    $job->created_at = $job->created_at->date;
                }
            }
            return $this->response->setJSON([
                'status' => 'success',
                'jobs' => $data['jobs'],
                'total_jobs' => $totalJobs,
                'current_page' => $page,
                'per_page' => $perPage
            ]);
        }

        return view('home/jobs', $data);
    }

    public function toggleSave($jobId)
    {
        if (!$this->auth->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to save jobs.'
            ]);
        }

        $candidateModel = model(JobSeekerModel::class);
        $savedJobModel  = model('App\Models\SavedJobModel');

        $candidate = $candidateModel
            ->where('user_id', $this->auth->user()->id)
            ->first();

        if (!$candidate) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Candidate profile not found.'
            ]);
        }

        // Toggle logic
        if ($savedJobModel->isSaved($candidate->id, $jobId)) {
            $savedJobModel->removeJob($candidate->id, $jobId);
            return $this->response->setJSON([
                'success' => true,
                'saved' => false,
                'message' => 'Job removed from saved list.'
            ]);
        } else {
            $savedJobModel->saveJob($candidate->id, $jobId);
            return $this->response->setJSON([
                'success' => true,
                'saved' => true,
                'message' => 'Job saved successfully.'
            ]);
        }
    }


    public function view_job($jobId)
    {
        $jobModel = model(JobModel::class);

        // SEO: Handle 301 Redirects from old ID-based URLs to new slugs
        if (is_numeric($jobId)) {
            $basicJob = $jobModel->find($jobId);
            if ($basicJob && !empty($basicJob->slug)) {
                return redirect()->to(base_url('jobs/' . $basicJob->slug), 301);
            }
        }

        $categoryModel = model(JobCategoryModel::class);
        $industryModel = model(IndustryModel::class);
        $stateModel = model(StateModel::class);

        // Detect if job is saved by the candidate
        $savedJobModel = model('App\Models\SavedJobModel');
        $isSaved = false;


        // Cache models for 1 hour to reduce database load
        $cache = \Config\Services::cache();
        $cacheKey = "job_{$jobId}_details_new";
        // $job = $cache->get($cacheKey);

        // if (!$job) {
        // Fetch the job with related data
        $query = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, 
            states.name as location, employers.user_id as employer_user_id, employers.company_name as employer_name, employers.logo as company_logo, employers.company_address as company_address, employers.contact_phone as company_phone, employers.contact_email as company_email, employers.website as company_website, employers.is_verified')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left');

        if (is_numeric($jobId)) {
            $query->where('jobs.id', $jobId);
        } else {
            $query->where('jobs.slug', $jobId);
        }

        $job = $query->first();

        if (!$job) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Job not found');
        }

        $jobIdNum = is_numeric($jobId) ? $jobId : $job->id;
        $jobModel->set('views', 'views+1', false)->where('id', $jobIdNum)->update();

        $job->formatted_created_at = date('d M, Y', strtotime($job->created_at));
        $job->formatted_expiry = $job->expiry_date ? date('d M, Y', strtotime($job->expiry_date)) : 'N/A';
        $job->salary_range = $job->salary ? $job->salary . ($job->salary_max ? ' - ' . $job->salary_max : '') . ' / ' . $job->salary_period : 'Negotiable';
        $subscriptionModel = model(UserSubscriptionModel::class);

        $activeSub = $subscriptionModel
            ->select('plans.features')
            ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
            ->where('user_subscriptions.is_active', 1)
            ->where('user_subscriptions.user_id', $job->employer_user_id)
            ->first();

        $planFeatures = [];
        if ($activeSub && !empty($activeSub['features'])) {
            $planFeatures = planFeatures(json_decode($activeSub['features'], true));
        }

        $job->show_trust_badge =
            !empty($planFeatures['trust_badge']) && ($job->is_verified == 1);

        $job->anonymous = (bool) (!empty($planFeatures['anonymous'])) && ($job->is_anonymous);

        if (!empty($planFeatures['anonymous']) && ($job->is_anonymous)) {
            $job->employer_name = 'Confidential Employer';
            $job->company_logo  = base_url('images/favicon.png');
        } else {
            if (!empty($job->company_logo)) {
                $job->company_logo = base_url($job->company_logo);
            } else {
                $job->company_logo = null;
            }
        }

        // Normalize logo URL
        // if (!empty($job->company_logo)) {
        //     $job->company_logo = !empty($job->company_logo)
        //         ? base_url($job->company_logo)
        //         : base_url('images/favicon.png');
        // }

        //     $cache->save($cacheKey, $job, 300); // Cache for 5 minutes
        // }
        // $job = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.company_name as employer_name, employers.logo as company_logo, employers.company_address as company_address, employers.contact_phone as company_phone, employers.contact_email as company_email, employers.website as company_website')
        //     ->join('states', 'states.id = jobs.state_id', 'left')
        //     ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
        //     ->join('industries', 'industries.id = jobs.industry_id', 'left')
        //     ->join('employers', 'employers.id = jobs.employer_id', 'left')
        //     ->where('jobs.id', $jobId)
        //     ->first();

        // Let's increase the views count
        // if ($job) {
        //     $jobModel->set('views', 'views+1', false)->where('id', $jobId)->update();
        // } else {
        //     log_message('error', "Job ID {$jobId} not found");
        //     throw new \CodeIgniter\Exceptions\PageNotFoundException('Job not found');
        // }

        if ($this->auth->loggedIn()) {
            $candidate = model(JobSeekerModel::class)
                ->where('user_id', $this->auth->user()->id)
                ->first();

            if ($candidate) {
                $isSaved = $savedJobModel->isSaved($candidate->id, $job->id);
            }
        }

        // Fetch related jobs (based on category or industry, excluding current job)
        $relatedJobsCacheKey = "related_jobs_{$jobId}";
        $related_jobs = $cache->get($relatedJobsCacheKey);

        if (!$related_jobs) {
            $related_jobs = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.user_id as employer_user_id, employers.company_name as employer_name, employers.logo as company_logo')
                ->join('states', 'states.id = jobs.state_id', 'left')
                ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
                ->join('industries', 'industries.id = jobs.industry_id', 'left')
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                ->groupStart()
                ->where('jobs.category_id', $job->category_id)
                ->orWhere('jobs.industry_id', $job->industry_id)
                ->groupEnd()
                ->where('jobs.id !=', $job->id)
                ->orderBy('jobs.created_at', 'DESC')
                ->findAll(4);

            $cache->save($relatedJobsCacheKey, $related_jobs, 3600);
        }

        // Fetch featured jobs
        $featuredJobsCacheKey = 'featured_jobs';
        $featured_jobs = $cache->get($featuredJobsCacheKey);

        if (!$featured_jobs) {
            $featured_jobs = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.company_name as employer_name, employers.logo as company_logo')
                ->join('states', 'states.id = jobs.state_id', 'left')
                ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
                ->join('industries', 'industries.id = jobs.industry_id', 'left')
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                ->where('jobs.is_featured', 1)
                ->where('jobs.featured_until >=', date('Y-m-d H:i:s'))
                ->orderBy('jobs.created_at', 'DESC')
                ->findAll(4);

            $cache->save($featuredJobsCacheKey, $featured_jobs, 3600);
        }

        // Count open jobs for the employer
        $employerJobCountCacheKey = "employer_jobs_{$job->employer_id}";
        $employer_job_count = $cache->get($employerJobCountCacheKey);

        if ($employer_job_count === null) {
            $employer_job_count = $jobModel->where('employer_id', $job->employer_id)->countAllResults();
            $cache->save($employerJobCountCacheKey, $employer_job_count, 3600);
        }

        // Clean description for meta tags
        $cleanDescription = strip_tags($job->description);
        $metaDescription = mb_substr($cleanDescription, 0, 160) . '...';

        $data = [
            'title' => esc($job->title) . ' in ' . esc($job->location ?? 'Nigeria') . ' — JobberRecruit',
            'meta_description' => $metaDescription,
            'og_title' => esc($job->title) . ' — Apply Now on JobberRecruit',
            'og_description' => $metaDescription,
            'og_image' => !empty($job->company_logo) ? $job->company_logo : base_url('images/default-og-image.jpg'),
            'noindex' => ($job->expiry_date && strtotime($job->expiry_date) < time()),
            'auth' => $this->auth,
            'job' => $job,
            'related_jobs' => $related_jobs,
            'featured_jobs' => $featured_jobs,
            'employer_job_count' => $employer_job_count,
            'isSaved' => $isSaved,
            'website_logo' => 'assets/imgs/logo.png',
        ];

        return view('home/view_job', $data);
    }

    public function featuredJobs()
    {
        $jobModel = new JobModel();
        $stateModel = new StateModel();
        $industryModel = new IndustryModel();
        $categoryModel = new JobCategoryModel();

        // Filters
        $jobType = $this->request->getGet('type');
        $stateId = $this->request->getGet('state');
        $categoryId = $this->request->getGet('category');

        $query = $jobModel
            ->select('jobs.*, employers.company_name, employers.logo as company_logo, 
                  states.name as state_name, industries.name as industry_name, 
                  job_categories.name as category_name')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->where('jobs.is_featured', 1)
            ->where('jobs.status', 'open');

        if ($jobType) $query->where('jobs.job_type', $jobType);
        if ($stateId) $query->where('jobs.state_id', $stateId);
        if ($categoryId) $query->where('jobs.category_id', $categoryId);

        $featured_jobs = $query->orderBy('jobs.created_at', 'DESC')->paginate(20);
        $pager = $jobModel->pager;

        return view('home/featured_jobs', [
            'title'           => 'Featured Jobs | JobberRecruit',
            'meta_description' => 'Browse featured job openings from verified employers across Nigeria. Apply to top opportunities in Lagos, Abuja, Port Harcourt and more.',
            'og_title'        => 'Featured Jobs — JobberRecruit',
            'og_description'  => 'Browse featured job openings from verified employers across Nigeria.',
            'og_image'        => base_url('images/default-og-image.jpg'),
            'auth' => $this->auth,
            'featured_jobs' => $featured_jobs,
            'pager'         => $pager,
            'states'        => $stateModel->findAll(),
            'industries'    => $industryModel->findAll(),
            'categories'    => $categoryModel->findAll(),
        ]);
    }


    public function index()
    {
        $industryModel = new IndustryModel();
        $stateModel  = new StateModel();
        $categoryModel = new JobCategoryModel();
        $jobModel = new JobModel();

        // Get categories with job counts in a single query
        $categories = $categoryModel
            ->select('job_categories.*, COUNT(jobs.id) as job_count')
            ->join('jobs', 'jobs.category_id = job_categories.id', 'left')
            ->where('job_categories.parent_id', null)
            ->groupBy('job_categories.id')
            ->orderBy('job_categories.name', 'ASC')
            ->findAll();

        $jobs = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.logo as company_logo')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->findAll();

        $data = [
            'title' => 'Home',
            'meta_description' => 'Find verified jobs across Nigeria on JobberRecruit. Browse thousands of opportunities in Lagos, Abuja, Port Harcourt.',
            'og_title' => 'JobberRecruit — Nigeria\'s Leading Job Portal',
            'og_description' => 'Find verified jobs and hire top talent across Nigeria.',
            'og_image' => base_url('images/default-og-image.jpg'),
            'auth' => $this->auth,
            'categories' => $categories,
            'jobs' => $jobs,
            'industries' => $industryModel->orderBy('name', 'ASC')->findAll(),
            'states'  => $stateModel->orderBy('name', 'ASC')->findAll(),
            'website_logo' => 'assets/imgs/page/homepage4/banner.png'
        ];

        return view('home', $data);
    }

    public function moreJobs()
    {
        $categoryId = $this->request->getGet('category_id');
        $offset = (int)$this->request->getGet('offset');
        $limit = (int)$this->request->getGet('limit', 6);

        $jobModel = model(JobModel::class);
        $jobs = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.logo as company_logo, employers.is_verified, employers.user_id as employer_user_id')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('jobs.category_id', $categoryId)
            ->where('jobs.status', 'open')
            ->orderBy('jobs.is_featured', 'DESC')
            ->orderBy('jobs.featured_until', 'DESC')
            ->findAll($limit, $offset);

        $subscriptionModel = model(UserSubscriptionModel::class);
        foreach ($jobs as &$job) {
            $activeSub = $subscriptionModel
                ->select('plans.features')
                ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
                ->where('user_subscriptions.is_active', 1)
                ->where('user_subscriptions.user_id', $job->employer_user_id)
                ->first();

            $planFeatures = [];
            if ($activeSub && !empty($activeSub['features'])) {
                $planFeatures = planFeatures(json_decode($activeSub['features'], true));
            }

            // TRUST BADGE
            $job->show_trust_badge = !empty($planFeatures['trust_badge']) && ($job->is_verified == 1);
            $job->anonymous = !empty($planFeatures['anonymous']) && ($job->is_anonymous);

            if ($job->anonymous) {
                $job->company_name = 'Confidential Employer';
                $job->company_logo = base_url('images/favicon.png');
            } else {
                $job->company_logo = $job->company_logo ? base_url($job->company_logo) : base_url('images/default-company.png');
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'jobs' => $jobs
        ]);
    }

    public function findJob()
    {
        $jobModel = model(JobModel::class);
        $categoryModel = model(JobCategoryModel::class);
        $industryModel = model(IndustryModel::class);
        $stateModel = model(StateModel::class);

        // Get query parameters
        $industryId = $this->request->getGet('industry_id');
        $stateId = $this->request->getGet('state_id');
        $experienceLevel = $this->request->getGet('experience_level');
        $salaryMin = $this->request->getGet('salary_min');
        $salaryMax = $this->request->getGet('salary_max');
        $keywords = htmlspecialchars(trim($this->request->getGet('keywords') ?? ''), ENT_QUOTES, 'UTF-8');
        $position = $this->request->getGet('position');
        $workArrangement = $this->request->getGet('work_arrangement');
        $jobTypeRaw = $this->request->getGet('job_type');
        if (is_array($jobTypeRaw)) {
            $validTypes = ['full-time','part-time','contract','freelance','internship'];
            $jobType = !empty($jobTypeRaw) ? implode(',', array_intersect($jobTypeRaw, $validTypes)) : null;
        } else {
            $jobType = $jobTypeRaw ? htmlspecialchars(trim($jobTypeRaw), ENT_QUOTES, 'UTF-8') : null;
        }
        $jobPosted = $this->request->getGet('job_posted');
        $sortBy = $this->request->getGet('sort_by') ?? 'newest';
        $perPage = (int)($this->request->getGet('per_page') ?? 12);
        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = max($perPage, 1);
        $page = max($page, 1);
        $offset = ($page - 1) * $perPage;

        // Build query
        $query = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.company_name as employer_name, employers.logo as company_logo')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left');

        // Apply filters
        if ($industryId) {
            $query->where('jobs.industry_id', $industryId);
        }
        if ($stateId) {
            $query->where('jobs.state_id', $stateId);
        }
        if ($experienceLevel) {
            $query->where('jobs.experience_level', $experienceLevel);
        }
        if ($salaryMin !== null) {
            $query->where('jobs.salary >=', $salaryMin);
        }
        if ($salaryMax !== null) {
            $query->where('jobs.salary <=', $salaryMax);
        }
        if ($keywords) {
            $query->groupStart()
                ->like('jobs.title', $keywords)
                ->orLike('jobs.skills', $keywords)
                ->orLike('jobs.description', $keywords)
                ->groupEnd();
        }
        if ($position) {
            $query->where('jobs.position', $position);
        }
        if ($workArrangement) {
            $query->where('jobs.work_arrangement', $workArrangement);
        }
        if ($jobType) {
            $types = explode(',', $jobType);
            count($types) > 1 ? $query->whereIn('jobs.job_type', $types) : $query->where('jobs.job_type', $jobType);
        }
        if ($jobPosted) {
            switch ($jobPosted) {
                case '1_day':
                    $query->where('jobs.created_at >=', date('Y-m-d H:i:s', strtotime('-1 day')));
                    break;
                case '7_days':
                    $query->where('jobs.created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')));
                    break;
                case '30_days':
                    $query->where('jobs.created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')));
                    break;
            }
        }

        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('jobs.created_at', 'ASC');
                break;
            case 'rating':
                $query->orderBy('jobs.rating', 'DESC');
                break;
            default:
                $query->orderBy('jobs.created_at', 'DESC');
        }

        // Get total count for pagination
        $totalJobs = $query->countAllResults(false);
        $jobs = $query->findAll($perPage, $offset);

        // Industry counts for sidebar
        $industryCounts = $jobModel->select('industries.id, industries.name, COUNT(jobs.id) as job_count')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->groupBy('industries.id')
            ->findAll();

        // Keyword counts (assuming skills are comma-separated)
        $keywordCounts = [];
        $allJobs = $jobModel->findAll();
        $keywordsList = ['Software', 'Developer', 'Web']; // Let's extract from all the jobs but prevents duplicates
        foreach ($keywordsList as $keyword) {
            $count = 0;
            foreach ($allJobs as $job) {
                if (stripos($job->skills, $keyword) !== false || stripos($job->title, $keyword) !== false) {
                    $count++;
                }
            }
            $keywordCounts[$keyword] = $count;
        }

        // Position and other counts (static for simplicity, adjust if dynamic)
        $positionCounts = ['Senior' => 12, 'Junior' => 35, 'Fresher' => 56];
        $experienceLevelCounts = [
            'internship' => 56,
            'entry' => 87,
            'associate' => 24,
            'mid' => 45,
            'director' => 76,
            'executive' => 89
        ];
        $workArrangementCounts = ['onsite' => 12, 'remote' => 65, 'hybrid' => 58];
        $jobTypeCounts = ['fulltime' => 25, 'parttime' => 64, 'remote' => 78, 'freelance' => 97];
        $jobPostedCounts = ['1_day' => 65, '7_days' => 24, '30_days' => 56];

        if ($industryId || $stateId || $experienceLevel || $salaryMin !== null || $salaryMax !== null || $keywords || $position || $workArrangement || $jobType || $jobPosted) {
            $title = 'Filtered Job Search Results';
        } else {
            $title = 'Find a Job';
        }

        $data = [
            'title' => $title,
            'auth' => $this->auth,
            'jobs' => $jobs,
            'total_jobs' => $totalJobs,
            'categories' => $categoryModel->where('parent_id', null)->findAll(),
            'industries' => $industryModel->orderBy('name', 'ASC')->findAll(),
            'states' => $stateModel->orderBy('name', 'ASC')->findAll(),
            'website_logo' => 'assets/imgs/logo.png',
            'industry_counts' => $industryCounts,
            'keyword_counts' => $keywordCounts,
            'position_counts' => $positionCounts,
            'experience_level_counts' => $experienceLevelCounts,
            'work_arrangement_counts' => $workArrangementCounts,
            'job_type_counts' => $jobTypeCounts,
            'job_posted_counts' => $jobPostedCounts,
            'current_page' => $page,
            'per_page' => $perPage,
            'sort_by' => $sortBy,
            'industryId' => $industryId ?? null,
            'stateId' => $stateId ?? null,
            'experienceLevel' => $experienceLevel ?? null,
            'salaryMin' => $salaryMin ?? null,
            'salaryMax' => $salaryMax ?? null,
            'keywords' => $keywords ?? null,
            'positionss' => $position ?? null,
            'workArrangement' => $workArrangement ?? null,
            'jobType' => $jobType ?? null,
            'jobPosted' => $jobPosted ?? null
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'jobs' => $jobs,
                'total_jobs' => $totalJobs,
                'current_page' => $page
            ]);
        }

        return view('find-jobs', $data);
    }

    public function jobDetails($jobId)
    {
        $jobModel = model(JobModel::class);
        $categoryModel = model(JobCategoryModel::class);
        $industryModel = model(IndustryModel::class);
        $stateModel = model(StateModel::class);

        // Fetch the job with related data
        $job = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, 
            employers.user_id as employer_user_id, employers.company_name as employer_name, employers.logo as company_logo, employers.company_address as company_address, employers.contact_phone as company_phone, employers.contact_email as company_email')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('jobs.id', $jobId)
            ->first();

        if (!$job) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Job not found');
        }

        // Fetch similar jobs (based on category or industry)
        $similar_jobs = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.company_name as employer_name, employers.logo as company_logo')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('jobs.category_id', $job->category_id)
            ->where('jobs.id !=', $job->id)
            ->orderBy('jobs.created_at', 'DESC')
            ->findAll(5);

        // Fetch featured jobs
        $featured_jobs = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.company_name as employer_name, employers.logo as company_logo')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('jobs.is_featured', 1) // Assuming a is_featured column in jobs table
            ->orderBy('jobs.created_at', 'DESC')
            ->findAll(4);

        // Count open jobs for the employer
        $employer_job_count = $jobModel->where('employer_id', $job->employer_id)->countAllResults();

        $subscriptionModel = model(UserSubscriptionModel::class);

        $activeSub = $subscriptionModel
            ->select('plans.features')
            ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
            ->where('user_subscriptions.is_active', 1)
            ->where('user_subscriptions.user_id', $job->employer_user_id)
            ->first();

        if ($activeSub && !empty($activeSub['features'])) {
            $planFeatures = planFeatures(json_decode($activeSub['features'], true));
        }

        // TRUST BADGE
        $job->show_trust_badge =
            !empty($planFeatures['trust_badge']) && ($job->is_verified == 1);

        $job->isAnonymous = !empty($planFeatures['anonymous']) && ($job->is_anonymous);

        // ANONYMOUS POSTING
        if (!empty($planFeatures['anonymous']) && ($job->is_anonymous)) {
            $job->employer_name = 'Confidential Employer';
            $job->company_logo  = null;
        }

        // Normalize logo URL
        if (!empty($job->company_logo)) {
            $job->company_logo = !empty($job->company_logo)
                ? base_url($job->company_logo)
                : base_url('images/favicon.png');
        }

        $data = [
            'title' => esc($job->title),
            'auth' => $this->auth,
            'job' => $job,
            'similar_jobs' => $similar_jobs,
            'featured_jobs' => $featured_jobs,
            'employer_job_count' => $employer_job_count,
            'website_logo' => 'assets/imgs/logo.png',
        ];

        return view('job-details', $data);
    }

    public function startApplication($jobId)
    {
        $jobModel = new \App\Models\JobModel();
        $job = $jobModel->find($jobId);

        if (!$job) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Job not found');
        }

        $method = $job->application_method ?? 'form';
        $redirectUrl = base_url('job/application/' . $jobId);

        switch ($method) {
            case 'whatsapp':
                $redirectUrl = $job->whatsapp_link;
                break;
            case 'email':
                $email = $job->application_email ?? $job->contact_email;
                $subject = rawurlencode("Application: {$job->title}");
                $redirectUrl = "mailto:{$email}?subject={$subject}";
                break;
            case 'external':
                $url = $job->external_url;
                if ($url && preg_match('#^https?://#i', $url)) {
                    $redirectUrl = $url;
                }
                break;
        }

        $clickModel = new JobClickModel();
        $clickUserId = $this->auth->loggedIn() ? $this->auth->user()->id : null;
        $clickModel->logClick($jobId, $method, $clickUserId);

        return redirect()->to($redirectUrl);
    }

    // public function apply_job($jobId)
    // {
    //     $jobModel          = model(JobModel::class);
    //     $applicationModel  = model(\App\Models\JobApplicationModel::class);
    //     $userModel         = model(\App\Models\UserModel::class);
    //     $candidateModel    = model(\App\Models\JobSeekerModel::class);

    //     $job = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, 
    //         employers.user_id as employer_user_id, employers.is_verified, employers.company_name as employer_name, employers.logo as company_logo, employers.company_address as company_address, employers.contact_phone as company_phone, employers.contact_email as company_email, employers.website as company_website')
    //         ->join('states', 'states.id = jobs.state_id', 'left')
    //         ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
    //         ->join('industries', 'industries.id = jobs.industry_id', 'left')
    //         ->join('employers', 'employers.id = jobs.employer_id', 'left')
    //         ->where('jobs.id', $jobId)
    //         ->first();
    //     if (!$job) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Job not found');
    //     }

    //     if ($job->application_access === 'authenticated' && !auth()->user()) {
    //         return redirect()->to('/login')->with('error', 'You must log in to apply for this job.');
    //     }

    //     if ($job->application_access === 'guest' && auth()->user()) {
    //         return redirect()->back()->with('info', 'This job is only open to guest applicants.');
    //     }

    //     $subscriptionModel = model(UserSubscriptionModel::class);

    //     $activeSub = $subscriptionModel
    //         ->select('plans.features')
    //         ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
    //         ->where('user_subscriptions.is_active', 1)
    //         ->where('user_subscriptions.user_id', $job->employer_user_id)
    //         ->first();

    //     if ($activeSub && !empty($activeSub['features'])) {
    //         $planFeatures = planFeatures(json_decode($activeSub['features'], true));
    //     }

    //     // TRUST BADGE
    //     $job->show_trust_badge =
    //         !empty($planFeatures['trust_badge']) && ($job->is_verified == 1);

    //     $job->anonymous = (bool) (!empty($planFeatures['anonymous'])) && ($job->is_anonymous);

    //     // ANONYMOUS POSTING
    //     if (!empty($planFeatures['anonymous']) && ($job->is_anonymous)) {
    //         $job->employer_name = 'Confidential Employer';
    //         $job->company_logo  = base_url('images/favicon.png');
    //     }

    //     // Normalize logo URL
    //     // if (!empty($job->company_logo)) {
    //     //     $job->company_logo = !empty($job->company_logo)
    //     //         ? base_url($job->company_logo)
    //     //         : base_url('images/favicon.png');
    //     // }


    //     // ---------------------------------------------------
    //     //   PROCESS SUBMISSION
    //     // ---------------------------------------------------
    //     if ($this->request->getMethod() === 'POST') {

    //         $user       = auth()->user();
    //         $loggedIn   = auth()->loggedIn();

    //         $coverLetter     = $this->request->getPost('cover_letter');
    //         $cvSource        = $this->request->getPost('cv_source');   // saved | upload | null
    //         $referencesNames = $this->request->getPost('ref_name');
    //         $referencesTitles = $this->request->getPost('ref_title');
    //         $referencesEmails = $this->request->getPost('ref_email');

    //         // ---------------------------------------------------
    //         // VALIDATE GOOGLE RECAPTCHA v3
    //         // ---------------------------------------------------
    //         $recaptchaSecret = env('recaptcha_secret_key');
    //         $recaptchaResponse = $this->request->getPost('g-recaptcha-response');

    //         if ($recaptchaSecret && $recaptchaResponse) {

    //             $verifyURL = "https://www.google.com/recaptcha/api/siteverify";

    //             $response = file_get_contents($verifyURL . "?secret=" . $recaptchaSecret . "&response=" . $recaptchaResponse);
    //             $captchaResult = json_decode($response, true);

    //             if (!$captchaResult['success'] || $captchaResult['score'] < 0.5) {
    //                 return $this->response->setJSON([
    //                     'status' => 'error',
    //                     'message' => 'reCAPTCHA verification failed. Please try again.'
    //                 ]);
    //             }
    //         } else {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'reCAPTCHA verification is required.'
    //             ]);
    //         }

    //         // Check if the user email and the job exists... Then return error that they have applied before.
    //         $application = $applicationModel->where('email', $this->request->getPost('email'))->where('job_id', $jobId)->first();
    //         if ($application) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'You have already applied for this job.'
    //             ]);
    //         }



    //         $cvPath = null;

    //         // ---------------------------------------------------
    //         // 1. HANDLE CV UPLOAD LOGIC
    //         // ---------------------------------------------------
    //         $cvFile = $this->request->getFile('cv_file');

    //         if ($cvFile && $cvFile->isValid()) {

    //             // Upload new CV
    //             $cvName = $cvFile->getRandomName();
    //             $cvFile->move('uploads/resume/', $cvName);
    //             $cvPath = 'uploads/resume/' . $cvName;

    //             // If logged in → overwrite CV in profile
    //             if ($loggedIn) {
    //                 $candidate = $candidateModel->where('user_id', $user->id)->first();
    //                 $candidateModel->update($candidate->id, [
    //                     'resume' => $cvPath
    //                 ]);
    //             }
    //         } elseif ($loggedIn && $cvSource === 'saved') {

    //             // Use saved CV from profile
    //             $candidate = $candidateModel->where('user_id', $user->id)->first();
    //             $cvPath = $candidate->resume;
    //         } else {

    //             // Guest MUST upload CV
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Please upload your CV to proceed with the application.'
    //             ]);
    //         }

    //         // Safety check
    //         if (!$cvPath) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'CV is required to proceed with the application.'
    //             ]);
    //         }

    //         // ---------------------------------------------------
    //         // 2. SAVE APPLICATION
    //         // ---------------------------------------------------
    //         if($loggedIn) {
    //             // Split the candidate name
    //             $first_name = explode(' ', $candidate->full_name)[0];
    //             $last_name = explode(' ', $candidate->full_name)[1];
    //         }

    //         $applicationId = $applicationModel->insert([
    //             'job_id'              => $jobId,
    //             'job_seeker_id'       => $loggedIn ? $candidate->id : null,

    //             // Guest fields
    //             'first_name'          => $loggedIn ? $first_name : $this->request->getPost('first_name'),
    //             'last_name'           => $loggedIn ? $last_name : $this->request->getPost('last_name'),
    //             'email'               => $loggedIn ? $user->email : $this->request->getPost('email'),
    //             'phone'               => $loggedIn ? $candidate->phone : $this->request->getPost('phone'),

    //             // CV + Letter
    //             'cv_path'             => $cvPath,
    //             'cover_letter'        => $coverLetter,

    //             // Extra fields
    //             'availability'        => $this->request->getPost('availability'),
    //             'salary_expectation'  => $this->request->getPost('salary_expectation'),
    //             'work_eligibility'    => $this->request->getPost('work_eligibility'),
    //             'consent'             => $this->request->getPost('consent') ? 1 : 0,

    //             'status'              => 'pending',
    //             'created_at'          => date('Y-m-d H:i:s'),
    //         ]);

    //         if (!empty($referencesNames)) {
    //             $refModel = model(\App\Models\ApplicationReferenceModel::class);

    //             foreach ($referencesNames as $i => $name) {
    //                 if (trim($name) === '') continue;

    //                 $refModel->insert([
    //                     'application_id' => $applicationId,
    //                     'name'           => $name,
    //                     'title'          => $referencesTitles[$i] ?? null,
    //                     'email'          => $referencesEmails[$i] ?? null,
    //                 ]);
    //             }
    //         }

    //         // ---------------------------------------------------
    //         // 3. SAVE REFERENCES (OPTIONAL)
    //         // ---------------------------------------------------
    //         // if (!empty($referencesNames)) {
    //         //     $refModel = model(ApplicationReferenceModel::class);

    //         //     foreach ($referencesNames as $i => $name) {
    //         //         if (!$name) continue;

    //         //         $refModel->insert([
    //         //             'application_id' => $applicationId,
    //         //             'name'           => $name,
    //         //             'title'          => $referencesTitles[$i] ?? null,
    //         //             'email'          => $referencesEmails[$i] ?? null,
    //         //         ]);
    //         //     }
    //         // }

    //         // ---------------------------------------------------
    //         // 4. SEND CONFIRMATION EMAIL TO CANDIDATE
    //         // ---------------------------------------------------
    //         $emailService = service('mailer');

    //         $candidateEmail = $loggedIn ? $user->email : $this->request->getPost('email');
    //         $candidateName  = $loggedIn
    //             ? $candidate->full_name
    //             : $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name');

    //         $emailService->sendTemplate(
    //             $candidateEmail,
    //             'Application Received – ' . $job->title,
    //             'emails/job_application_confirmation',
    //             [
    //                 'candidate_name' => $candidateName,
    //                 'job_title'      => $job->title,
    //                 'employer_name'  => $job->employer_name,
    //                 'job_location'   => $job->location,
    //                 'platform_name'  => config('App')->appName ?? 'JobberRecruit',
    //             ],
    //             $job->company_email // reply-to (optional)
    //         );

    //         $emailService->clear();

    //         $emailService2 = service('mailer');

    //         // Add Delay
    //         sleep(5);

    //         // Employer email fallback logic
    //         $employerEmail = $job->company_email ?? null;

    //         if ($employerEmail) {

    //             $emailService2->sendTemplate(
    //                 $employerEmail,
    //                 'New Application for ' . $job->title,
    //                 'emails/employer_new_application',
    //                 [
    //                     'employer_name'     => $job->employer_name,
    //                     'job_title'         => $job->title,
    //                     'candidate_name'    => $candidateName,
    //                     'candidate_email'   => $candidateEmail,
    //                     'candidate_phone'   => $loggedIn
    //                         ? $candidate->phone
    //                         : $this->request->getPost('phone'),

    //                     'availability'      => $this->request->getPost('availability'),
    //                     'salary_expectation' => $this->request->getPost('salary_expectation'),
    //                     'applied_at'        => date('d M Y, H:i'),
    //                     'dashboard_url'     => base_url('employer/applications/' . $jobId),
    //                     'platform_name'     => config('App')->appName ?? 'JobberRecruit',
    //                 ],
    //                 $candidateEmail // reply-to candidate
    //             );
    //         }


    //         // Return redirect
    //         // return redirect()->to('job/applied/' . $jobId)->with('success', 'Application submitted successfully.');
    //         return $this->response->setJSON([
    //             'status'  => 'success',
    //             'message' => 'Application submitted successfully.',
    //             'redirect' => base_url('job/applied/' . $jobId),
    //         ]);

    //         // return $this->response->setJSON([
    //         //     'status'  => 'success',
    //         //     'message' => 'Application submitted successfully.',
    //         // ]);
    //     }

    //     // ---------------------------------------------------
    //     // (GET) LOAD APPLICATION PAGE
    //     // ---------------------------------------------------

    //     // Detect Saved Job
    //     $savedJobModel = model('App\Models\SavedJobModel');
    //     $isSaved       = false;

    //     if (auth()->loggedIn()) {
    //         $candidate = $candidateModel->where('user_id', auth()->user()->id)->first();
    //         if ($candidate) {
    //             $isSaved = $savedJobModel->isSaved($candidate->id, $jobId);
    //         }
    //     }

    //     return view('home/apply', [
    //         'title'   => 'Apply for ' . esc($job->title),
    //         'auth'    => auth(),
    //         'user'    => auth()->user(),
    //         'job'     => $job,
    //         'isSaved' => $isSaved,
    //         'candidate' => $candidate ?? null,
    //     ]);
    // }

    public function apply_job($jobId)
    {
        $jobModel          = model(JobModel::class);
        $applicationModel  = model(\App\Models\JobApplicationModel::class);
        $userModel         = model(\App\Models\UserModel::class);
        $candidateModel    = model(\App\Models\JobSeekerModel::class);

        $job = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, 
        employers.user_id as employer_user_id, employers.is_verified, employers.company_name as employer_name, employers.logo as company_logo, employers.company_address as company_address, employers.contact_phone as company_phone, employers.contact_email as company_email, employers.website as company_website')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('jobs.id', $jobId)
            ->first();

        if (!$job) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Job not found');
        }

        if ($job->application_access === 'authenticated' && !auth()->user()) {
            return redirect()->to('/login')->with('error', 'You must log in to apply for this job.');
        }

        if ($job->application_access === 'guest' && auth()->user()) {
            return redirect()->back()->with('info', 'This job is only open to guest applicants.');
        }

        $subscriptionModel = model(UserSubscriptionModel::class);

        $activeSub = $subscriptionModel
            ->select('plans.features')
            ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
            ->where('user_subscriptions.is_active', 1)
            ->where('user_subscriptions.user_id', $job->employer_user_id)
            ->first();

        if ($activeSub && !empty($activeSub['features'])) {
            $planFeatures = planFeatures(json_decode($activeSub['features'], true));
        }

        // TRUST BADGE
        $job->show_trust_badge = !empty($planFeatures['trust_badge']) && ($job->is_verified == 1);

        $job->anonymous = (bool) (!empty($planFeatures['anonymous'])) && ($job->is_anonymous);

        // ANONYMOUS POSTING
        if (!empty($planFeatures['anonymous']) && ($job->is_anonymous)) {
            $job->employer_name = 'Confidential Employer';
            $job->company_logo  = base_url('images/favicon.png');
        }

        // Process Submission
        if ($this->request->getMethod() === 'POST') {

            $user       = auth()->user();
            $loggedIn   = auth()->loggedIn();

            $coverLetter     = $this->request->getPost('cover_letter');
            $cvSource        = $this->request->getPost('cv_source');
            $referencesNames = $this->request->getPost('ref_name');
            $referencesTitles = $this->request->getPost('ref_title');
            $referencesEmails = $this->request->getPost('ref_email');

            // Validate Google reCAPTCHA v3 (skip in development)
            if (ENVIRONMENT !== 'development') {
                $recaptchaSecret = env('recaptcha_secret_key');
                $recaptchaResponse = $this->request->getPost('g-recaptcha-response');

                if ($recaptchaSecret && $recaptchaResponse) {
                    $verifyURL = "https://www.google.com/recaptcha/api/siteverify";
                    $response = file_get_contents($verifyURL . "?secret=" . $recaptchaSecret . "&response=" . $recaptchaResponse);
                    $captchaResult = json_decode($response, true);

                    if (!$captchaResult['success'] || $captchaResult['score'] < 0.5) {
                        return $this->response->setJSON([
                            'status' => 'error',
                            'message' => 'reCAPTCHA verification failed. Please try again.'
                        ]);
                    }
                } else {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'reCAPTCHA verification is required.'
                    ]);
                }
            }

            // Check if already applied (use logged-in user's email when available)
            $applicantEmail = $loggedIn ? $user->email : $this->request->getPost('email');
            $existingApplication = $applicationModel->where('email', $applicantEmail)->where('job_id', $jobId)->first();
            if ($existingApplication) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'You have already applied for this job.'
                ]);
            }

            $cvPath = null;

            // Handle CV Upload Logic
            $cvFile = $this->request->getFile('cv_file');

            if ($cvFile && $cvFile->isValid()) {
                $allowedMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
                if (!in_array($cvFile->getMime(), $allowedMimes)) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Invalid file type. Only PDF, DOC, DOCX, JPG, and PNG files are allowed.'
                    ]);
                }
                if ($cvFile->getSizeByUnit('mb') > 10) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'File size exceeds the 10MB limit.'
                    ]);
                }
                $cvName = $cvFile->getRandomName();
                $cvFile->move('uploads/resume/', $cvName);
                $cvPath = 'uploads/resume/' . $cvName;

                if ($loggedIn) {
                    $candidate = $candidateModel->where('user_id', $user->id)->first();
                    $candidateModel->update($candidate->id, ['resume' => $cvPath]);
                }
            } elseif ($loggedIn && $cvSource === 'saved') {
                $candidate = $candidateModel->where('user_id', $user->id)->first();
                $cvPath = $candidate->resume;
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please upload your CV to proceed with the application.'
                ]);
            }

            if (!$cvPath) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'CV is required to proceed with the application.'
                ]);
            }

            // Save Application
            if ($loggedIn) {
                $candidate = $candidateModel->where('user_id', $user->id)->first();
                $first_name = explode(' ', $candidate->full_name)[0];
                $last_name = isset(explode(' ', $candidate->full_name)[1]) ? explode(' ', $candidate->full_name)[1] : '';
            }

            $applicationId = $applicationModel->insert([
                'job_id'              => $jobId,
                'job_seeker_id'       => $loggedIn ? $candidate->id : null,
                'is_guest'            => !$loggedIn ? 1 : 0,
                'first_name'          => $loggedIn ? $first_name : $this->request->getPost('first_name'),
                'last_name'           => $loggedIn ? $last_name : $this->request->getPost('last_name'),
                'email'               => $loggedIn ? $user->email : $this->request->getPost('email'),
                'phone'               => $loggedIn ? $candidate->phone : $this->request->getPost('phone'),
                'cv_path'             => $cvPath,
                'cover_letter'        => $coverLetter,
                'availability'        => $this->request->getPost('availability'),
                'salary_expectation'  => $this->request->getPost('salary_expectation'),
                'work_eligibility'    => $this->request->getPost('work_eligibility'),
                'consent'             => $this->request->getPost('consent') ? 1 : 0,
                'status'              => 'pending',
                'created_at'          => date('Y-m-d H:i:s'),
            ]);

            // ---- Save Pre-screening Answers ----
            $answers = $this->request->getPost('answers');
            if (!empty($answers) && is_array($answers)) {
                $answerModel = model(\App\Models\ApplicationAnswerModel::class);
                foreach ($answers as $questionId => $answer) {
                    if (is_array($answer)) {
                        $answer = implode(', ', $answer);
                    }
                    $answerModel->insert([
                        'application_id' => $applicationId,
                        'question_id'    => (int) $questionId,
                        'answer'         => trim($answer),
                    ]);
                }
            }

            // Save References
            if (!empty($referencesNames)) {

                $refModel = model(\App\Models\ApplicationReferenceModel::class);
                foreach ($referencesNames as $i => $name) {
                    if (trim($name) === '') continue;
                    $refModel->insert([
                        'application_id' => $applicationId,
                        'name'           => $name,
                        'title'          => $referencesTitles[$i] ?? null,
                        'email'          => $referencesEmails[$i] ?? null,
                    ]);
                }
            }

            // Get employer
            $employerModel = model(EmployerModel::class);
            $employer = $employerModel->find($job->employer_id);

            // =============================================
            // CHECK NOTIFICATION PREFERENCES
            // =============================================
            $notificationPreferences = [];
            if ($job->notification_preferences) {
                $notificationPreferences = is_string($job->notification_preferences)
                    ? json_decode($job->notification_preferences, true)
                    : $job->notification_preferences;
            }

            $sendEmailNotification = $notificationPreferences['email'] ?? false;
            $sendInAppNotification = $notificationPreferences['in_app'] ?? true;
            $notificationEmail = $notificationPreferences['notification_email_address'] ?? $employer->contact_email ?? $job->company_email;

            // Send Confirmation Email to Candidate (Always sent)
            $emailService = service('mailer');
            $candidateEmail = $loggedIn ? $user->email : $this->request->getPost('email');
            $candidateName  = $loggedIn
                ? $candidate->full_name
                : $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name');

            $emailService->sendTemplate(
                $candidateEmail,
                'Application Received – ' . $job->title,
                'emails/job_application_confirmation',
                [
                    'candidate_name' => $candidateName,
                    'job_title'      => $job->title,
                    'employer_name'  => $job->employer_name,
                    'job_location'   => $job->location,
                    'platform_name'  => config('App')->appName ?? 'JobberRecruit',
                ],
                $job->company_email
            );

            $emailService->clear();

            // =============================================
            // SEND NOTIFICATION TO EMPLOYER (Based on preferences)
            // =============================================

            // Send Email Notification if enabled
            if ($sendEmailNotification && $notificationEmail) {
                sleep(2); // Small delay to avoid email conflicts

                $emailService2 = service('mailer');
                $emailService2->sendTemplate(
                    $notificationEmail,
                    'New Application for ' . $job->title,
                    'emails/employer_new_application',
                    [
                        'employer_name'      => $job->employer_name,
                        'job_title'          => $job->title,
                        'candidate_name'     => $candidateName,
                        'candidate_email'    => $candidateEmail,
                        'candidate_phone'    => $loggedIn ? ($candidate->phone ?? 'N/A') : $this->request->getPost('phone'),
                        'availability'       => $this->request->getPost('availability'),
                        'salary_expectation' => $this->request->getPost('salary_expectation'),
                        'applied_at'         => date('d M Y, H:i'),
                        'dashboard_url'      => base_url('employer/applications/view/' . $applicationId),
                        'platform_name'      => config('App')->appName ?? 'JobberRecruit',
                    ],
                    $candidateEmail
                );
            }

            // Send In-App Notification if enabled
            if ($sendInAppNotification) {
                $notificationModel = model(\App\Models\JobNotificationModel::class);
                $notificationModel->createNotification(
                    $job->employer_id,
                    'new_application',
                    'New Application Received',
                    "{$candidateName} has applied for the position of {$job->title}.",
                    $jobId,
                    $applicationId
                );
            }

            // Return response
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Application submitted successfully.',
                'redirect' => base_url('job/applied/' . $jobId),
            ]);
        }

        // GET: Load Application Page
        $savedJobModel = model('App\Models\SavedJobModel');
        $questionModel = model(\App\Models\JobQuestionModel::class);
        $isSaved = false;
        $candidate = null;

        if (auth()->loggedIn()) {
            $candidate = $candidateModel->where('user_id', auth()->user()->id)->first();
            if ($candidate) {
                $isSaved = $savedJobModel->isSaved($candidate->id, $jobId);
            }
        }

        $questions = $questionModel->where('job_id', $job->id)->findAll();

        return view('home/apply', [
            'title'   => 'Apply for ' . esc($job->title),
            'auth'    => auth(),
            'user'    => auth()->user(),
            'job'     => $job,
            'isSaved' => $isSaved,
            'candidate' => $candidate ?? null,
            'questions' => $questions
        ]);

    }

    public function applied($jobId)
    {
        $jobModel = model(JobModel::class);

        $job = $jobModel
            ->select('jobs.*, employers.company_name, employers.logo as company_logo')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->find($jobId);

        if (!$job) {
            return redirect()->to('jobs')->with('error', 'Job not found.');
        }

        $similarJobs = $jobModel
            ->where('industry_id', $job->industry_id)
            ->where('jobs.id !=', $jobId)
            ->limit(4)
            ->findAll();

        return view('home/application_success', [
            'title' => 'Application Successful',
            'auth'    => auth(),
            'user'    => auth()->user(),
            'job' => $job,
            'similarJobs' => $similarJobs
        ]);
    }

    public function search()
    {
        $industryId = $this->request->getGet('industry_id');
        $stateId = $this->request->getGet('state_id');
        $experienceLevel = $this->request->getGet('experience_level');

        $jobModel = model(JobModel::class);
        $query = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.logo as company_logo')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left');

        if ($industryId) {
            $query->where('jobs.industry_id', $industryId);
        }
        if ($stateId) {
            $query->where('jobs.state_id', $stateId);
        }
        if ($experienceLevel) {
            $query->where('jobs.experience_level', $experienceLevel);
        }

        $jobs = $query->findAll();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'jobs' => $jobs
            ]);
        }

        $data = [
            'title' => 'Job Search Results',
            'jobs' => $jobs,
            'categories' => model(JobCategoryModel::class)->where('parent_id', null)->findAll(),
            'industries' => model(IndustryModel::class)->orderBy('name', 'ASC')->findAll(),
            'states' => model(StateModel::class)->orderBy('name', 'ASC')->findAll(),
            'website_logo' => 'assets/imgs/logo.png'
        ];

        return view('jobs/search_results', $data);
    }

    public function aboutUs()
    {
        $data = [
            'title' => 'About JobberRecruit — Nigeria\'s Job Platform',
            'meta_description' => 'JobberRecruit is Nigeria\'s modern job platform helping job seekers find verified opportunities and enabling employers to hire top talent easily.',
            'og_title' => 'About JobberRecruit — Nigeria\'s Job Platform',
            'og_description' => 'Learn about JobberRecruit — Nigeria\'s modern job platform connecting job seekers with verified opportunities.',
            'og_image' => base_url('images/default-og-image.jpg'),
            'auth' => $this->auth,
            'testimonials' => model(TestimonialModel::class)->where('status', 'active')->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('about-us', $data);
    }

    public function contactUs()
    {
        if ($this->request->getMethod() === 'POST') {

            // ------------------------------------
            // RATE LIMITING
            // ------------------------------------
            $throttler = service('throttler');

            if ($throttler->check('contact', 5, 300) === false) {
                return $this->response->setStatusCode(429)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Too many messages. Please wait a few minutes and try again.',
                    ]);
            }

            // ------------------------------------
            // reCAPTCHA v3 VALIDATION (skip in development)
            // ------------------------------------
            if (ENVIRONMENT !== 'development') {
                $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
                $recaptchaSecret  = env('recaptcha_secret_key');

                if (! $recaptchaResponse) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'reCAPTCHA verification failed.',
                    ]);
                }

                $verify = file_get_contents(
                    'https://www.google.com/recaptcha/api/siteverify?' .
                        http_build_query([
                            'secret'   => $recaptchaSecret,
                            'response' => $recaptchaResponse,
                            'remoteip' => $this->request->getIPAddress(),
                        ])
                );

                $captcha = json_decode($verify, true);

                if (
                    empty($captcha['success']) ||
                    $captcha['score'] < 0.5 ||
                    ($captcha['action'] ?? '') !== 'contact'
                ) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Suspicious activity detected. Please try again.',
                    ]);
                }
            }

            // -----------------------------
            // Validate input
            // -----------------------------
            $rules = [
                'name'    => 'required|min_length[3]',
                'email'   => 'required|valid_email',
                'subject' => 'required',
                'message' => 'required|min_length[10]',
            ];

            if (! $this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please correct the errors and try again.'
                ]);
            }

            $name    = $this->request->getPost('name');
            $email   = $this->request->getPost('email');
            $phone   = $this->request->getPost('phone');
            $subject = $this->request->getPost('subject');
            $message = $this->request->getPost('message');

            $mailer = service('mailer');

            // -----------------------------
            // 1. Send message to support
            // -----------------------------
            $adminMessage = view('emails/contact_admin', [
                'name'    => $name,
                'email'   => $email,
                'phone'   => $phone,
                'subject' => $subject,
                'message' => $message,
            ]);

            $mailer->clear();
            $mailer->setTo('support@jobberrecruit.com');
            $mailer->setReplyTo($email, $name);
            $mailer->setSubject('[Contact] ' . $subject);
            $mailer->setMessage($adminMessage);
            $mailer->setMailType('html');

            if (! $mailer->send()) {
                log_message('error', 'Contact form email failed: ' . print_r($mailer->printDebugger(), true));

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unable to send message. Please try again later.',
                ]);
            }

            // -----------------------------
            // 2. Auto-reply to user
            // -----------------------------
            $userMessage = view('emails/contact_autoreply', [
                'name' => $name,
            ]);

            // Add Delay
            sleep(5);

            $mailer->clear();
            $mailer->setTo($email);
            $mailer->setSubject('We received your message');
            $mailer->setMessage($userMessage);
            $mailer->setMailType('html');
            $mailer->send(); // silent fail is OK

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Message sent successfully. We will contact you within 24 hours.',
            ]);
        }

        $data = [
            'title' => 'Contact JobberRecruit — Lagos, Nigeria',
            'meta_description' => 'Get in touch with JobberRecruit. Our office is located at 6 Ojulari Rd, Lekki Peninsula II, Lagos. Contact us for recruitment services and job inquiries.',
            'og_title' => 'Contact JobberRecruit — Lagos, Nigeria',
            'og_description' => 'Get in touch with JobberRecruit for recruitment services and job inquiries.',
            'og_image' => base_url('images/default-og-image.jpg'),
            'auth' => $this->auth,
        ];

        return view('contact-us', $data);
    }

    public function blogs()
    {
        $q = trim($this->request->getGet('q'));
        $sort = $this->request->getGet('sort') ?? 'newest';
        $page = $this->request->getGet('page') ?? 1;

        $blogModel = model(BlogModel::class);

        // Main query builder
        $builder = $blogModel
            ->where('status', 'published');

        // Apply search
        if ($q) {
            $builder->groupStart()
                ->like('title', $q)
                ->orLike('excerpt', $q)
                ->orLike('content', $q)
                ->groupEnd();
        }

        // Apply sorting
        switch ($sort) {
            case 'popular':
                $builder->orderBy('views', 'DESC');
                break;
            case 'oldest':
                $builder->orderBy('created_at', 'ASC');
                break;
            case 'newest':
            default:
                $builder->orderBy('created_at', 'DESC');
                break;
        }

        // Get popular posts (always get most viewed, not affected by search)
        $popularPosts = $blogModel
            ->where('status', 'published')
            ->orderBy('views', 'DESC')
            ->limit(5)
            ->find();

        // Get total stats for hero section
        $totalPosts = $blogModel->where('status', 'published')->countAllResults();
        $totalViews = $blogModel->where('status', 'published')->selectSum('views')->get()->getRow()->views ?? 0;

        // Paginate results - CORRECT USAGE
        $perPage = 9;

        // Option 1: Simple paginate with default settings
        $blogs = $builder->paginate($perPage, 'default', $page);
        $pager = $blogModel->pager;

        // Option 2: Or you can use this alternative
        // $blogs = $builder->paginate($perPage, 'group', $page);
        // $pager = $builder->pager;

        // Prepare meta data
        $metaTitle = $q ? "Search results for '{$q}' | JobberRecruit Blog" : 'Career Tips & Job News Nigeria — JobberRecruit Blog';
        $metaDescription = $q
            ? "Search results for '{$q}' - Find career tips, job search advice, and hiring insights."
            : 'Expert career tips, job search strategies, interview guides, CV advice, and hiring insights from the JobberRecruit Blog.';

        $data = [
            'title' => $metaTitle,
            'auth' => $this->auth,
            'blogs' => $blogs,
            'pager' => $pager,
            'q' => $q,
            'sort' => $sort,
            'popularPosts' => $popularPosts,
            'totalPosts' => $totalPosts,
            'totalViews' => $totalViews,
            'meta_description' => $metaDescription,
            'canonical' => base_url('blog'),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Blog', 'url' => base_url('blog')]
            ]
        ];

        // Add Open Graph data
        $data['og_image'] = base_url('images/logo.png');
        $data['og_type'] = 'website';

        return view('blog', $data);
    }

    public function blogPost($slug)
    {
        $blogModel = model(BlogModel::class);

        $blog = $blogModel
            ->where('status', 'published')
            ->groupStart()
            ->where('slug', $slug)
            ->orWhere('preview_token', $slug)
            ->groupEnd()
            ->first();

        if (!$blog) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Canonical redirect
        if ($slug !== $blog->slug) {
            return redirect()->to(base_url('blog/' . $blog->slug), 301);
        }

        // Calculate reading time
        $plainText = strip_tags($blog->content);
        $wordCount = str_word_count($plainText);
        $readingTime = max(1, ceil($wordCount / 200));

        // Increment views
        $blogModel->increment('views', 1, ['id' => $blog->id]);

        // Get related posts based on tags
        $relatedPosts = [];
        if (!empty($blog->slug)) {
            // Extract the most important word from title
            $titleWords = explode(' ', strtolower($blog->title));
            $stopWords = ['how', 'to', 'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by'];
            $keywords = array_diff($titleWords, $stopWords);
            $mainKeyword = !empty($keywords) ? reset($keywords) : '';

            // Try to find posts with similar titles
            $relatedPosts = $blogModel
                ->where('status', 'published')
                ->where('id !=', $blog->id);

            if (!empty($mainKeyword) && strlen($mainKeyword) > 3) {
                $relatedPosts = $relatedPosts
                    ->groupStart()
                    ->like('title', $mainKeyword)
                    ->orLike('content', $mainKeyword)
                    ->groupEnd();
            }

            $relatedPosts = $relatedPosts
                ->orderBy('views', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->limit(3)
                ->find();

            // Fallback: If no matches or not enough, get most viewed posts
            if (count($relatedPosts) < 3) {

                $relatedIds = array_column($relatedPosts, 'id');

                $fallbackQuery = $blogModel
                    ->where('status', 'published')
                    ->where('id !=', $blog->id);

                if (!empty($relatedIds)) {
                    $fallbackQuery->whereNotIn('id', $relatedIds);
                }

                $fallbackPosts = $fallbackQuery
                    ->orderBy('views', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->limit(3 - count($relatedPosts))
                    ->find();

                $relatedPosts = array_merge($relatedPosts, $fallbackPosts);
            }
        }

        // Meta description
        $metaDescription = $blog->meta_description ??
            $blog->excerpt ??
            substr(strip_tags($blog->content), 0, 155);

        // Open Graph image
        $ogImage = $blog->thumbnail ?? base_url('images/og-default.jpg');

        // Prepare data
        $data = [
            'title' => ($blog->meta_title ?? $blog->title) . ' | JobberRecruit Blog',
            'auth'  => $this->auth,
            'blog' => $blog,
            'readingTime' => $readingTime,
            'related_posts' => $relatedPosts,
            'meta_description' => $metaDescription,
            'og_title' => $blog->title,
            'og_description' => $metaDescription,
            'og_image' => $ogImage,
            'og_type' => 'article',
            'canonical' => base_url('blog/' . $blog->slug),
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => base_url()],
                ['name' => 'Blog', 'url' => base_url('blog')],
                ['name' => $blog->title, 'url' => current_url()]
            ]
        ];

        // Set Last-Modified header for caching
        $lastModified = strtotime($blog->updated_at ?? $blog->created_at);
        $this->response->setLastModified($lastModified);

        return view('blog-post', $data);
    }

    public function rss()
    {
        $posts = model(BlogModel::class)
            ->where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->limit(20)
            ->findAll();

        return response()
            ->setHeader('Content-Type', 'application/rss+xml; charset=UTF-8')
            ->setBody(view('rss', [
                'posts' => $posts
            ]));
    }

    // FAQ Page
    public function faq()
    {
        $data = [
            'title' => 'Frequently Asked Questions — JobberRecruit Nigeria',
            'meta_description' => 'Find answers to common questions about job posting, application management, and recruitment services on JobberRecruit Nigeria.',
            'og_title' => 'FAQ — JobberRecruit Nigeria',
            'og_description' => 'Find answers to common questions about job posting, applications, and recruitment on JobberRecruit.',
            'og_image' => base_url('images/default-og-image.jpg'),
            'auth'  => $this->auth,

            // --------------------------------------------------
            // SECTION 1: Job Posting & Advert Management
            // --------------------------------------------------
            'faq1' => [
                "What is the difference between a free job posting and a paid job posting?" =>
                "Free postings allow you to advertise vacancies and receive applications via email. Featured postings offer higher visibility, priority placement, and an application dashboard.",

                "How long will my job advert stay on JobberRecruit.com?" =>
                "Job ads remain permanently on our platform. Once filled, we mark the job as closed and disable applications.",

                "Can I edit my job advert after posting?" =>
                "You can edit responsibilities and requirements, but the job title and company name cannot be modified after approval.",

                "Can I hide my company name on job ads?" =>
                "Yes. Featured postings allow the option to advertise anonymously.",

                "Can I repost or duplicate an old job advert?" =>
                "Yes. You can duplicate past ads from your dashboard, update the details, and repost.",

                "I have filled the role, but the advert is still running. What do I do?" =>
                "Set the job to 'expired' from your dashboard. Expired posts cannot be reactivated.",

                "Why can't I find my job advert on the website?" =>
                "It may still be under review or awaiting approval. Contact support for confirmation.",

                "Is it possible to remove a job post completely?" =>
                "Job posts cannot be deleted permanently, but they can be marked as expired or filled.",

                "How do I attract the best candidates?" =>
                "Use a clear job title, detailed job description, and accurate requirements to attract suitable applicants."
            ],

            // --------------------------------------------------
            // SECTION 2: Employer Account & Profile Management
            // --------------------------------------------------
            'faq2' => [
                "How do I create an employer account?" =>
                "Register on JobberRecruit.com as an employer. Once approved, you can start posting jobs.",

                "Can I update my company information?" =>
                "You can update most company details from your dashboard. Changing the company name requires support assistance.",

                "Can multiple companies be managed under one account?" =>
                "No. Each company must create and manage its own account.",

                "Do I need an account before posting jobs?" =>
                "Yes. An employer account is required to post jobs and manage applications."
            ],

            // --------------------------------------------------
            // SECTION 3: Application Management
            // --------------------------------------------------
            'faq3' => [
                "Where will I receive applications?" =>
                "Free job posts send applications directly to your email. Featured job posts store applications in your dashboard.",

                "Can I get email or SMS alerts when candidates apply?" =>
                "Yes. Notification preferences can be enabled from your dashboard.",

                "How long are candidate applications stored?" =>
                "Applications remain accessible as long as your employer account is active.",

                "How can I reduce irrelevant applications?" =>
                "Provide clear requirements, accurate job descriptions, and specify experience levels.",

                "Does JobberRecruit verify candidates?" =>
                "We screen for profile completeness, but full background checks are only available through paid recruitment services."
            ],

            // --------------------------------------------------
            // SECTION 4: Posting Timelines & Approval
            // --------------------------------------------------
            'faq4' => [
                "How long does it take for my job to go live?" =>
                "Paid jobs go live within 6 hours on weekdays. Free jobs may take up to 24 hours.",

                "Are all job postings reviewed before publication?" =>
                "Yes. Every job post is manually reviewed before being published.",

                "Why was my job post declined?" =>
                "It may violate posting guidelines or lack required information."
            ],

            // --------------------------------------------------
            // SECTION 5: Payments & Pricing
            // --------------------------------------------------
            'faq5' => [
                "What payment methods are accepted for featured postings?" =>
                "We accept debit cards, bank transfers, and direct deposits.",

                "How do I upgrade a free posting to a featured posting?" =>
                "Go to your dashboard, select the job post, and choose the upgrade option.",

                "Do you offer discounts for bulk or recurring postings?" =>
                "Yes. Please contact our sales team for customized pricing plans."
            ],

            // --------------------------------------------------
            // SECTION 7: Supported Job Types & Posting Guidelines
            // --------------------------------------------------
            'faq7' => [
                "What types of jobs can I post?" =>
                "Full-time, part-time, remote, contract, and freelance jobs across all industries.",

                "Can international employers post jobs?" =>
                "Yes. International employers can post jobs after successful verification.",

                "What happens if my job violates posting rules?" =>
                "The job may be declined or suspended, and our support team will contact you."
            ],

            // --------------------------------------------------
            // SECTION 8: Troubleshooting & Technical Issues
            // --------------------------------------------------
            'faq8' => [
                "I paid for my job post but it's not live yet. Why?" =>
                "Paid jobs may take up to 6 hours on weekdays. Contact support if it exceeds this timeframe.",

                "I cannot find some dashboard features." =>
                "Try clearing your browser cache or logging in again. Contact support if the issue persists.",

                "How do I report suspicious applicants or activity?" =>
                "Send full details to our support team immediately for investigation."
            ],

            // --------------------------------------------------
            // SECTION 9: Recruitment Services
            // --------------------------------------------------
            'faq9' => [
                "Does JobberRecruit offer recruitment support?" =>
                "Yes. We provide CV screening, candidate shortlisting, and interview coordination.",

                "Can JobberRecruit manage my entire recruitment process?" =>
                "Yes. We offer full recruitment outsourcing services for employers."
            ],

            // --------------------------------------------------
            // SECTION 10: General Inquiries & Support
            // --------------------------------------------------
            'faq10' => [
                "My question is not listed here. What should I do?" =>
                "Please contact our support team. We are always ready to assist you.",

                "How do I reach the JobberRecruit support team?" =>
                "You can reach us via email, phone, or through the support section in your dashboard."
            ],
            'og_title' => 'Frequently Asked Questions — JobberRecruit Nigeria',
            'og_description' => 'Find answers to common questions about job posting and recruitment in Nigeria on JobberRecruit.',
            'og_image' => base_url('images/default-og-image.jpg'),
        ];

        return view('faq', $data);
    }

    // Privacy Policy
    public function privacyPolicy()
    {
        $data = [
            'title' => 'Privacy Policy',
            'meta_description' => 'Our privacy policy explains how we collect and use your data on JobberRecruit.',
            'og_title' => 'Privacy Policy — JobberRecruit',
            'og_image' => base_url('images/default-og-image.jpg'),
            'auth' => $this->auth,
        ];
        return view('privacy-policy', $data);
    }

    // Terms of Service
    public function termsOfService()
    {
        $data = [
            'title' => 'Terms of Service',
            'meta_description' => 'Read our terms of service for using the JobberRecruit platform.',
            'og_title' => 'Terms of Service — JobberRecruit',
            'og_image' => base_url('images/default-og-image.jpg'),
            'auth' => $this->auth,
        ];
        return view('terms-of-service', $data);
    }

    public function viewCompany($id)
    {
        $employerModel = new EmployerModel();
        $jobsModel = new JobModel();
        $industryMap = new EmployerIndustryModel();
        $industryModel = new IndustryModel();

        $company = $employerModel
            ->select('employers.*, states.name as location')
            ->where('employers.id', $id)
            ->join('states', 'states.id = employers.state_id', 'left')
            ->first();

        if (!$company) {
            return redirect()->to('/')->with('error', 'Company not found.');
        }

        $subscriptionModel = model(UserSubscriptionModel::class);

        $activeSub = $subscriptionModel
            ->select('plans.features')
            ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
            ->where('user_subscriptions.is_active', 1)
            ->where('user_subscriptions.user_id', $company->user_id)
            ->first();

        $planFeatures = [];
        if ($activeSub && !empty($activeSub['features'])) {
            $planFeatures = planFeatures(json_decode($activeSub['features'], true));
        }

        $company->show_trust_badge =
            !empty($planFeatures['trust_badge']) && ($company->is_verified == 1);

        // Load industries
        $industryIDs = $industryMap->where('employer_id', $id)->findColumn('industry_id') ?? [];
        $industries = $industryModel->whereIn('id', $industryIDs)->findAll();

        // Jobs by employer
        $openJobs = $jobsModel
            ->select('jobs.*, states.name as location, employers.user_id as employer_user_id, employers.company_name, employers.logo, employers.is_verified')
            ->where('employer_id', $id)
            ->where('status', 'open')
            ->where('is_anonymous', '0')
            ->join('employers', 'employers.id = jobs.employer_id')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        foreach ($openJobs as &$job) {
            $job->anonymous = !empty($planFeatures['anonymous']) && ($job->is_anonymous);

            if (!empty($planFeatures['anonymous']) && ($job->is_anonymous)) {
                $job->employer_name = 'Confidential Employer';
                $job->company_logo  = base_url('images/favicon.png');
            }
        }

        return view('company_profile', [
            'title' => $company->company_name ?? 'Company Profile',
            'meta_description' => 'View the profile and open jobs for ' . ($company->company_name ?? 'this company') . ' on JobberRecruit.',
            'og_title' => ($company->company_name ?? 'Company Profile') . ' — JobberRecruit',
            'og_description' => 'View the profile and open jobs for ' . ($company->company_name ?? 'this company') . ' on JobberRecruit.',
            'og_image' => !empty($company->logo) ? base_url($company->logo) : base_url('images/default-og-image.jpg'),
            'auth' => $this->auth,
            'company'    => $company,
            'industries' => $industries,
            'openJobs'   => $openJobs,
        ]);
    }

    public function talents()
    {
        $data = [
            'title'            => 'Become a Candidate - Find Remote & Local Jobs | JobberRecruit',
            'meta_description' => 'Join 10,000+ professionals finding high-paying remote and local jobs at verified companies. Create your free candidate profile and get matched with premium opportunities in tech, design, marketing, and more.',
            'og_title'         => 'Become a Candidate | Find Premium Remote & Local Jobs',
            'og_description'   => 'Join top talents connecting with verified companies worldwide. Get matched with high-quality remote and local opportunities.',
            'og_image'         => base_url('assets/og-candidate-cover.jpg'),
            'keywords'         => 'remote jobs, job seekers, candidates, talent platform, hire developers, global opportunities, tech jobs, remote work, career opportunities, verified employers',
            'auth' => $this->auth,
        ];
        return view('talents', $data);
    }



    public function trackOpen($alertId)
    {
        $alertModel = model(JobAlertModel::class);
        $alertModel->where('id', $alertId)->increment('opens');
    }

    public function trackClick($alertId, $jobId)
    {
        $alertModel = model(JobAlertModel::class);
        $alertModel->where('id', $alertId)->increment('clicks');

        return redirect()->to('jobs/view/' . $jobId);
    }

    public function recruitment()
    {
        $data = [
            'title' => 'Recruitment Services in Nigeria — JobberRecruit',
            'meta_description' => 'Hire top talent in Nigeria with JobberRecruit. We provide professional recruitment services, candidate screening, and job advertising solutions.',
            'og_title' => 'Professional Recruitment Services — JobberRecruit',
            'og_description' => 'Hire top talent in Nigeria with JobberRecruit. We provide professional recruitment services and job advertising solutions.',
            'og_image' => base_url('images/default-og-image.jpg'),
            'auth' => $this->auth,
        ];
        return view('recruitment', $data);
    }

    public function adPage()
    {
        $data = [
            'title' => 'Job Posting Service | Post a Job in Nigeria — JobberRecruit',
            'meta_description' => 'Post your job vacancy on JobberRecruit and reach thousands of qualified candidates in Nigeria. Fast, effective, and affordable job advertising.',
            'auth' => $this->auth,
        ];
        return view('ad', $data);
    }
}
