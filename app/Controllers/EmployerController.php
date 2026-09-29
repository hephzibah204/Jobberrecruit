<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\EmployerModel;
use App\Models\JobCreditTransactionModel;
use App\Models\EmployerDocumentModel;
use App\Models\WalletTransactionModel;
use App\Models\WalletModel;
use App\Models\IndustryModel;
use App\Models\JobApplicationModel;
use App\Models\JobCategoryModel;
use App\Models\JobClickModel;
use App\Models\JobModel;
use App\Models\PaymentModel;
use App\Models\StateModel;
use App\Models\SubscriptionPlanModel;
use App\Models\UserSubscriptionModel;
use CodeIgniter\Shield\Models\UserModel as ModelsUserModel;
use App\Models\PlanModel;
use App\Models\PlanBundleModel;
use App\Models\JobCreditWalletModel;
use App\Models\JobNotificationModel;
use App\Models\ApplicationNoteModel;
use App\Services\JobCreditService;
use App\Services\CreditService;
use App\Models\JobSeekerModel;
use App\Models\JobSeekerIndustryModel;
use App\Models\CandidateAlertModel;
use DateTime;

class EmployerController extends BaseController
{

    protected $auth;
    protected $config;
    protected $users;
    protected $userModel;
    protected $session;

    protected $paystackSecret;
    protected $paystackCallback;

    public function __construct()
    {
        $this->auth = service('auth');
        $this->config = config('Auth');
        helper(['auth', 'text', 'form', 'url', 'env']);
        $this->users = model(UserModel::class);
        $this->userModel = model(ModelsUserModel::class);
        $this->session = \Config\Services::session();

        $this->paystackSecret = env('paystack_secret_key') ?: (env('PAYSTACK_SECRET_KEY') ?: env('paystack.secret_key'));
        $this->paystackCallback = env('paystack_callback_url') ?: (env('PAYSTACK_CALLBACK_URL') ?: base_url('pricing/verify'));
    }

    /**
     * Check if employer has uploaded CAC document
     */
    private function hasUploadedCACDocument($employerId)
    {
        $documentModel = model(EmployerDocumentModel::class);

        // Check for CAC certificate that is approved or pending
        $cacDocument = $documentModel
            ->where('employer_id', $employerId)
            ->where('document_type', 'cac_certificate')
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        // Also check old verification_doc field for backward compatibility
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->find($employerId);

        return !empty($cacDocument) || !empty($employer->verification_doc);
    }

    /**
     * Check if employer has unlimited access
     */
    protected function hasUnlimitedAccess($employerId)
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->find($employerId);

        if (!$employer) {
            return false;
        }

        return (new CreditService())->hasUnlimitedAccess((int) $employer->user_id);
    }

    public function dashboard()
    {
        $employerModel      = model(EmployerModel::class);
        $jobModel           = model(JobModel::class);
        $appModel           = model(JobApplicationModel::class);

        $user = $this->auth->user();

        if ($user->user_type == 'job_seeker') {
            return redirect()->to('candidate/dashboard');
        }

        // Get logged-in employer profile
        $employer = $employerModel->where('user_id', $user->id)->first();

        // Auto-create basic employer profile record if missing to prevent redirect loops
        if (!$employer) {
            $employerId = $employerModel->insert([
                'user_id'       => $user->id,
                'company_name'  => $user->username ?? 'Company Profile',
                'contact_email' => $user->email ?? null,
            ]);
            $employer = $employerModel->find($employerId);
        }

        // =============================================
        // 🔹 CHECK CAC DOCUMENT STATUS (non-blocking)
        // =============================================
        $hasCACDocument = $this->hasUploadedCACDocument($employer->id);

        // ============================
        // 🔹 DASHBOARD STATISTICS
        // ============================

        // Total Jobs Posted
        $totalJobs = $jobModel->where('employer_id', $employer->id)->countAllResults();

        // Active Jobs
        $activeJobs = $jobModel
            ->where('employer_id', $employer->id)
            ->where('status', 'open')
            ->countAllResults();

        // Total Applicants
        $totalApplicants = $appModel
            ->whereIn('job_id', function ($builder) use ($employer) {
                return $builder->select('id')
                    ->from('jobs')
                    ->where('employer_id', $employer->id);
            })
            ->countAllResults();

        // Total Hires (status = hired)
        $totalHires = $appModel
            ->where('status', 'hired')
            ->whereIn('job_id', function ($builder) use ($employer) {
                return $builder->select('id')
                    ->from('jobs')
                    ->where('employer_id', $employer->id);
            })
            ->countAllResults();

        // ============================
        // 🔹 PENDING APPS (for sidebar badge)
        // ============================
        $pendingApps = $appModel
            ->where('status', 'pending')
            ->whereIn('job_id', function ($builder) use ($employer) {
                return $builder->select('id')->from('jobs')->where('employer_id', $employer->id);
            })
            ->countAllResults();

        // ============================
        // 🔹 WALLET BALANCE
        // ============================
        $walletRow     = model(WalletModel::class)->where('user_id', $user->id)->first();
        $walletBalance = $walletRow ? (float) $walletRow->balance : 0;

        // ============================
        // 🔹 RECENT APPLICATIONS (limit 5)
        // ============================

        $recentApplications = $appModel
            ->select('job_applications.*, jobs.title as job_title, jobs.state_id AS location')
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->where('jobs.employer_id', $employer->id)
            ->orderBy('job_applications.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // ============================
        // 🔹 RECENTLY POSTED JOBS (limit 5)
        // ============================

        $recentJobs = $jobModel
            ->where('employer_id', $employer->id)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // ============================
        // 🔹 TOP CATEGORIES (percentage)
        // ============================

        $categoryCounts = $jobModel
            ->select('job_categories.name, COUNT(*) as total')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->where('employer_id', $employer->id)
            ->groupBy('category_id')
            ->findAll();

        // ============================
        // 🔹 JOBS POSTED CHART (last 7 days)
        // ============================

        $jobsChart = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $jobsChart[$date] = $jobModel
                ->where('employer_id', $employer->id)
                ->where('DATE(created_at)', $date)
                ->countAllResults();
        }

        // ============================
        // 🔹 APPLICATIONS CHART (last 7 days)
        // ============================

        $appsChart = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $appsChart[$date] = $appModel
                ->whereIn('job_id', function ($builder) use ($employer) {
                    return $builder->select('id')->from('jobs')->where('employer_id', $employer->id);
                })
                ->where('DATE(created_at)', $date)
                ->countAllResults();
        }

        // ============================
        // 🔹 HIRING PIPELINE COUNTS
        // ============================
        $shortlisted = $appModel
            ->where('status', 'shortlisted')
            ->whereIn('job_id', function ($builder) use ($employer) {
                return $builder->select('id')->from('jobs')->where('employer_id', $employer->id);
            })
            ->countAllResults();

        $pipeline = [
            'posted'      => $totalJobs,
            'applicants'  => $totalApplicants,
            'shortlisted' => $shortlisted,
            'hired'       => $totalHires,
        ];

        // ============================
        // 🔹 PROFILE COMPLETION (%)
        // ============================
        $profileFields = [
            !empty($employer->company_name),
            !empty($employer->contact_email),
            !empty($employer->company_size),
            !empty($employer->description),
            !empty($employer->website),
            !empty($employer->logo),
            $totalJobs > 0,
            $hasCACDocument,
        ];
        $profileCompletion = (int) round(
            (array_sum(array_map('intval', $profileFields)) / count($profileFields)) * 100
        );

        // ============================
        // 🔹 JOBS CLOSING SOON (within 14 days)
        // ============================
        $closingSoon = $jobModel
            ->select('id, title, application_deadline as deadline')
            ->where('employer_id', $employer->id)
            ->where('status', 'open')
            ->where('application_deadline IS NOT NULL')
            ->where('application_deadline >=', date('Y-m-d'))
            ->where('application_deadline <=', date('Y-m-d', strtotime('+14 days')))
            ->orderBy('application_deadline', 'ASC')
            ->limit(5)
            ->findAll();

        // ============================
        // 🔹 JOB PERFORMANCE INSIGHTS (top 3 jobs by views)
        // ============================
        $jobInsights = $jobModel
            ->select('id, title, views, status')
            ->where('employer_id', $employer->id)
            ->orderBy('views', 'DESC')
            ->limit(3)
            ->findAll();

        // Attach application count to each insight job
        foreach ($jobInsights as &$insightJob) {
            $insightJob->app_count = $appModel
                ->where('job_id', $insightJob->id)
                ->countAllResults();
        }
        unset($insightJob);

        // Matching candidate profiles count (PDF Requirement 9)
        $jobSeekerModel = model(JobSeekerModel::class);
        $activeJobTitles = $jobModel->where('employer_id', $employer->id)->where('status', 'open')->findColumn('title') ?? [];
        $matchingBuilder = $jobSeekerModel->builder();
        $matchingBuilder->where('is_visible', 1);
        if (!empty($activeJobTitles)) {
            $matchingBuilder->groupStart();
            foreach (array_slice($activeJobTitles, 0, 5) as $jt) {
                $matchingBuilder->orLike('job_title', $jt);
            }
            $matchingBuilder->groupEnd();
        }
        $matchingCandidatesCount = $matchingBuilder->countAllResults();
        if ($matchingCandidatesCount === 0) {
            $matchingCandidatesCount = min(5, $jobSeekerModel->where('is_visible', 1)->countAllResults());
        }

        // ============================
        // 🔹 RETURN VIEW
        // ============================

        return view('employers/dashboard', [
            'title'              => 'Dashboard',
            'user'               => $user,
            'employer'           => $employer,
            'hasCACDocument'     => $hasCACDocument,

            // Stats
            'totalJobs'               => $totalJobs,
            'activeJobs'              => $activeJobs,
            'totalApplicants'         => $totalApplicants,
            'totalHires'              => $totalHires,
            'matchingCandidatesCount' => $matchingCandidatesCount,

            // Wallet
            'walletBalance'      => $walletBalance,

            // Sidebar badge
            'pendingApps'        => $pendingApps,

            // Lists
            'recentApplications' => $recentApplications,
            'recentJobs'         => $recentJobs,
            'categoryCounts'     => $categoryCounts,

            // Charts
            'jobsChart'          => $jobsChart,
            'appsChart'          => $appsChart,

            // Pipeline
            'pipeline'           => $pipeline,

            // Profile completion
            'profileCompletion'  => $profileCompletion,

            // Closing soon
            'closingSoon'        => $closingSoon,

            // Job insights
            'jobInsights'        => $jobInsights,
        ]);
    }

    /**
     * Determine if job should be featured based on plan or wallet
     */
    protected function shouldFeatureJob($employerId, $userId, $plan, $creditBalance, $hasUnlimitedAccess): bool
    {
        // Unlimited access users always get featured jobs
        if ($hasUnlimitedAccess) {
            return true;
        }

        // Check if user has an active subscription with featured feature
        if ($plan && $plan->features) {
            $features = is_string($plan->features) ? json_decode($plan->features, true) : $plan->features;
            if (is_object($features)) {
                $features = (array) $features;
            }
            if ((isset($features['featured']) && $features['featured']) ||
                (isset($features['featured_job']) && $features['featured_job']) ||
                (isset($features['unlimited_jobs']) && $features['unlimited_jobs'])) {
                return true;
            }
        }

        // Bundle users: feature if they have 5+ credits
        if ($creditBalance >= 5) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can post anonymously
     */
    protected function canUseAnonymousPosting($plan, $hasUnlimitedAccess): bool
    {
        if ($hasUnlimitedAccess) {
            return true;
        }

        if ($plan && $plan->features) {
            $features = is_string($plan->features) ? json_decode($plan->features, true) : $plan->features;
            if (is_object($features)) {
                $features = (array) $features;
            }
            if ((isset($features['anonymous']) && $features['anonymous']) ||
                (isset($features['anonymous_job']) && $features['anonymous_job'])) {
                return true;
            }
        }

        $user = $this->auth->user();
        if ($user) {
            $creditService = new \App\Services\CreditService();
            $canPerform = $creditService->canPerformAction($user->id, 'post_job');
            if ($canPerform['can']) {
                return true; // Access granted
            }
        }

        return false;
    }

    /**
     * Check if user can use network blast
     */
    protected function canUseNetworkBlast($plan, $hasUnlimitedAccess): bool
    {
        if ($hasUnlimitedAccess) {
            return true;
        }

        if ($plan && $plan->features) {
            $features = is_string($plan->features) ? json_decode($plan->features, true) : $plan->features;
            if (is_object($features)) {
                $features = (array) $features;
            }
            return isset($features['network_blast']) && $features['network_blast'] === true;
        }

        $user = $this->auth->user();

        $creditService = new \App\Services\CreditService();
        $canPerform = $creditService->canPerformAction($user->id, 'post_job');

        if ($canPerform['can']) {
            return true; // Access granted
        }

        return false;
    }

    /**
     * Check if employer can post a job
     * STRICT - Must have credits OR subscription OR unlimited access
     */
    protected function checkJobPostingAccess()
    {
        $user = $this->auth->user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        // 1. Check if employer profile exists
        if (!$employer) {
            return redirect()->to('employer/profile/edit')
                ->with('error', 'Please create your company profile first.');
        }

        // 2. Check if employer profile is complete
        if (!$employer->company_size || !$employer->contact_email) {
            return redirect()->to('employer/profile')
                ->with('error', 'Please complete your company profile before posting a job.');
        }

        // CAC document check is now OPTIONAL - employers can post jobs before verification
        // Verification badge will still work, but upload is not blocking

        $creditService = new \App\Services\CreditService();

        // 4. Check if user can perform action (unlimited OR subscription OR credits)
        $canPerform = $creditService->canPerformAction($user->id, 'post_job');

        if ($canPerform['can']) {
            return true; // Access granted
        }

        // 5. No access → redirect with specific reason
        $reason = $canPerform['reason'] ?? 'You need an active subscription or job credits to post a new job.';

        return redirect()->to(base_url('employer/no-access'))
            ->with('warning', $reason);
    }

    /**
     * Check if user has an active valid subscription
     */
    protected function hasActiveSubscription($userId)
    {
        $subscriptionModel = model(UserSubscriptionModel::class);

        $activeSub = $subscriptionModel
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->first();

        return !empty($activeSub);
    }

    /**
     * No Access Page
     */
    public function no_access()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $planModel         = model(PlanModel::class);

        $userId = auth()->user()->id;
        $employer = $employerModel->where('user_id', $userId)->first();

        if (!$employer) {
            return redirect()->to('employer/dashboard')->with('error', 'Employer profile not found.');
        }

        $creditService = new \App\Services\CreditService();
        $availableCredits = $creditService->getAvailableCredits($user->id);

        $plan = null;
        $subscription = model(UserSubscriptionModel::class)
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->first();

        // Get the single subscription plan
        $subscriptionPlan = $planModel
            ->where('plan_type', 'subscription')
            ->where('is_active', 1)
            ->first();

        return view('employers/no_access', [
            'title'            => 'Access Denied - Post Job',
            'user'             => $this->auth->user(),
            'employer'       => $employer,
            'creditBalance'    => $availableCredits,
            'currentPlan'      => $subscription,
            'subscriptionPlan' => $subscriptionPlan,
            'companyName' => 'JobberRecruit Nigeria',   // or from settings
            'companyAddress' => 'Lagos, Nigeria'
        ]);
    }

    // public function post_job()
    // {
    //     $user = $this->auth->user();
    //     $employerModel = model(EmployerModel::class);
    //     $industryModel = model(IndustryModel::class);
    //     $categoryModel = model(JobCategoryModel::class);
    //     $jobModel      = model(JobModel::class);
    //     $creditService = new \App\Services\CreditService();

    //     $userId = auth()->user()->id;
    //     $employer = $employerModel->where('user_id', $userId)->first();

    //     if (!$employer) {
    //         return redirect()->to('employer/dashboard')->with('error', 'Employer profile not found.');
    //     }

    //     // Profile completeness check
    //     if (!$employer->company_size || !$employer->verification_doc || !$employer->contact_email) {
    //         return redirect()->to('employer/profile')
    //             ->with('error', 'Please complete your employer profile before posting a job.');
    //     }

    //     // This will redirect automatically if access is denied
    //     $access = $this->checkJobPostingAccess();
    //     if ($access !== true) {
    //         return $access;   // redirect response
    //     }

    //     if ($this->request->getMethod() === 'POST') {

    //         /* ====================== VALIDATION ====================== */
    //         $rules = [
    //             'title'               => 'required|min_length[5]|max_length[255]',
    //             'description'         => 'required|min_length[100]',
    //             'job_type'            => 'required|in_list[full-time,part-time,contract,freelance,internship]',
    //             'state_id'            => 'required|is_natural_no_zero',
    //             'location_type'       => 'required|in_list[hybrid,remote,on-site]',
    //             'salary_type'         => 'required|in_list[fixed,range,negotiable]',
    //             'salary_period'       => 'required|in_list[monthly,yearly,hourly]',
    //             'industry_id'         => 'required|is_natural_no_zero',
    //             'category_id'         => 'required|is_natural_no_zero',
    //             'education_level'     => 'required',
    //             'experience_level'    => 'required',
    //             'application_method'  => 'required|in_list[form,whatsapp,email,external]',
    //             'application_access'  => 'required|in_list[guest,authenticated,general]',
    //             'accommodation'       => 'required|in_list[available,not_available]',
    //             'contact_email'       => 'required|valid_email',
    //         ];

    //         // Conditional validation
    //         $method = $this->request->getPost('application_method');
    //         if ($method === 'whatsapp') {
    //             $rules['whatsapp_link'] = 'required|valid_url';
    //         } elseif ($method === 'email') {
    //             $rules['application_email'] = 'required|valid_email';
    //         } elseif ($method === 'external') {
    //             $rules['external_url'] = 'required|valid_url';
    //         }

    //         if (!$this->validate($rules)) {
    //             return $this->response->setJSON([
    //                 'success' => false,
    //                 'message' => 'Validation failed',
    //                 'errors'  => $this->validator->getErrors()
    //             ]);
    //         }

    //         /* ====================== CREDIT + FEATURE CHECK ====================== */
    //         $canPost = $creditService->canPerformAction($userId, 'post_job');

    //         if (!$canPost['can']) {
    //             return $this->response->setJSON([
    //                 'success' => false,
    //                 'message' => $canPost['reason']
    //             ]);
    //         }

    //         /* ====================== PREPARE JOB DATA ====================== */
    //         $postData = $this->request->getPost();
    //         $postData['employer_id'] = $employer->id;
    //         $postData['status']      = 'pending_approval';

    //         // Salary details
    //         if ($postData['salary_type'] !== 'negotiable' && !empty($postData['salary'])) {
    //             $postData['salary_details'] = ucfirst($postData['salary_type']) . ', ' .
    //                 ucfirst($postData['salary_period']) . ': ' .
    //                 $postData['salary'];
    //         } else {
    //             $postData['salary_details'] = 'Negotiable';
    //         }

    //         // Handle application method
    //         $postData['whatsapp_link']   = $method === 'whatsapp' ? trim($postData['whatsapp_link'] ?? '') : null;
    //         $postData['application_email'] = $method === 'email'   ? trim($postData['application_email'] ?? '') : null;
    //         $postData['external_url']    = $method === 'external' ? trim($postData['external_url'] ?? '') : null;

    //         /* ====================== SAVE JOB + DEDUCT CREDIT ====================== */
    //         $db = db_connect();
    //         $db->transStart();

    //         try {
    //             $jobId = $jobModel->insert($postData);

    //             if (!$jobId) {
    //                 throw new \Exception('Failed to create job');
    //             }

    //             // Deduct 1 credit for posting a job
    //             $deductResult = $creditService->deductCredits(
    //                 $userId,
    //                 1,                          // Cost = 1 credit per job post
    //                 (string)$jobId,
    //                 'Posted Job: ' . $postData['title'],
    //                 'post_job'
    //             );

    //             if (!$deductResult['success']) {
    //                 throw new \Exception($deductResult['message']);
    //             }

    //             $db->transComplete();

    //             return $this->response->setJSON([
    //                 'success' => true,
    //                 'message' => 'Job posted successfully!',
    //                 'job_id'  => $jobId
    //             ]);
    //         } catch (\Exception $e) {
    //             $db->transRollback();
    //             log_message('error', 'Job posting failed: ' . $e->getMessage());
    //             return $this->response->setJSON([
    //                 'success' => false,
    //                 'message' => 'Failed to post job. Please try again.'
    //             ]);
    //         }
    //     }

    //     /* ====================== GET: Show Post Job Form ====================== */
    //     $plan = null;
    //     $subscription = model(UserSubscriptionModel::class)
    //         ->where('user_id', $userId)
    //         ->where('is_active', 1)
    //         ->where('ends_at >', date('Y-m-d H:i:s'))
    //         ->first();

    //     if ($subscription) {
    //         $plan = model(PlanModel::class)->find($subscription->plan_id);
    //     }

    //     $creditBalance = $creditService->getAvailableCredits($userId);

    //     $data = [
    //         'title'          => 'Post a Job',
    //         'user'           => $user,
    //         'employer'       => $employer,
    //         'industries'     => $industryModel->findAll(),
    //         'categories'     => $categoryModel->findAll(),
    //         'states'         => model(StateModel::class)->findAll(),
    //         'creditBalance'  => $creditBalance,
    //         'plan'           => $plan ?? (object)['name' => 'Free Plan']
    //     ];

    //     return view('employers/post-job', $data);
    // }

    public function post_job()
    {
        // Auto-run migrations to prevent 'Unknown column' errors if the user forgot to migrate
        try {
            $migrate = \Config\Services::migrations();
            $migrate->latest();
        } catch (\Exception $e) {
            log_message('error', 'Auto-migration failed: ' . $e->getMessage());
        }

        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $industryModel = model(IndustryModel::class);
        $categoryModel = model(JobCategoryModel::class);
        $jobModel = model(JobModel::class);
        $creditService = new \App\Services\CreditService();

        $userId = auth()->user()->id;
        $employer = $employerModel->where('user_id', $userId)->first();

        if (!$employer) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Employer profile not found.',
                    'redirect' => base_url('employer/dashboard')
                ]);
            }
            return redirect()->to('employer/dashboard')->with('error', 'Employer profile not found.');
        }

        // 60% Profile completion gate to post jobs (PDF Requirement)
        $profileFields = [
            !empty($employer->company_name),
            !empty($employer->contact_email),
            !empty($employer->company_size),
            !empty($employer->description),
            !empty($employer->website),
            !empty($employer->logo),
            !empty($employer->company_phone) || !empty($employer->phone),
        ];
        $profileScore = (int) round((array_sum(array_map('intval', $profileFields)) / count($profileFields)) * 100);
        if ($profileScore < 60) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Your company profile is only {$profileScore}% complete. Please complete at least 60% of your company profile before posting a job.",
                    'redirect' => base_url('employer/profile/edit')
                ]);
            }
            return redirect()->to('employer/profile/edit')
                ->with('error', "Your company profile is only {$profileScore}% complete. Please complete at least 60% of your company profile before posting a job.");
        }

        // CAC document check is now OPTIONAL - employers can post jobs before verification

        $isDraft = $this->request->getMethod() === 'POST'
            && $this->request->getPost('submission_type') === 'draft';

        // Saving work must not require a posting credit. Access is enforced only
        // when the employer is opening the publish form or publishing a job.
        if (!$isDraft) {
            $access = $this->checkJobPostingAccess();
            if ($access !== true) {
                if ($this->request->isAJAX()) {
                    $reason = session()->getFlashdata('warning') ?? 'You need an active subscription or job credits to post a new job.';
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $reason,
                        'redirect' => base_url('employer/no-access')
                    ]);
                }
                return $access;
            }
        }

        if ($this->request->getMethod() === 'POST') {

            /* ====================== VALIDATION ====================== */
            $rules = [
                'title' => 'required|min_length[5]|max_length[255]',
                'description' => 'required|min_length[100]',
                'job_type' => 'required|in_list[full-time,part-time,contract,freelance,internship]',
                'state_id' => 'required|is_natural_no_zero',
                'location_type' => 'required|in_list[hybrid,remote,on-site]',
                'salary_type' => 'required|in_list[fixed,range,negotiable]',
                'salary_period' => 'required|in_list[monthly,yearly,hourly]',
                'industry_id' => 'required|is_natural_no_zero',
                'category_id' => 'required|is_natural_no_zero',
                'education_level' => 'required',
                'experience_level' => 'required',
                'application_method' => 'required|in_list[form,whatsapp,email,external]',
                'application_access' => 'required|in_list[guest,authenticated,general]',
                'accommodation' => 'permit_empty',
                'contact_email' => 'required|valid_email',
                'notification_email' => 'permit_empty|valid_email',
            ];

            // Conditional validation
            $method = $this->request->getPost('application_method');
            if ($method === 'whatsapp') {
                $rules['whatsapp_link'] = 'required|valid_url';
            } elseif ($method === 'email') {
                $rules['application_email'] = 'required|valid_email';
            } elseif ($method === 'external') {
                $rules['external_url'] = 'required|valid_url';
            }

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            /* ====================== CHECK ACCESS & FEATURES ====================== */
            $canPost = $isDraft
                ? ['can' => true]
                : $creditService->canPerformAction($userId, 'post_job');

            if (!$canPost['can']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $canPost['reason']
                ]);
            }

            // Get user's current plan and balance
            $currentPlan = $creditService->getCurrentPlan($userId);
            $creditBalance = $creditService->getAvailableCredits($userId);
            $hasUnlimitedAccess = $this->hasUnlimitedAccess($employer->id);
            $subscription = $creditService->getCurrentSubscription($userId);

            // Determine premium features
            $shouldBeFeatured = $this->shouldFeatureJob($employer->id, $userId, $currentPlan, $creditBalance, $hasUnlimitedAccess);
            $canPostAnonymous = $this->canUseAnonymousPosting($currentPlan, $hasUnlimitedAccess);
            $canUseNetworkBlast = $this->canUseNetworkBlast($currentPlan, $hasUnlimitedAccess);

            /* ====================== PREPARE JOB DATA ====================== */
            $allowed = ['title','description','job_type','state_id','city','location_type','salary_type','salary_period','salary','salary_max','industry_id','category_id','education_level','experience_level','application_method','application_access','accommodation','contact_email','contact_phone','notification_email','whatsapp_link','application_email','external_url','external_link','application_deadline','start_date','urgency','show_salary','currency','is_anonymous','network_blast'];
            $postData = $this->request->getPost($allowed);
            $postData['employer_id'] = $employer->id;
            $postData['status'] = $isDraft ? 'draft' : 'pending_approval';
            $postData['admin_status'] = $isDraft ? 'draft' : 'pending';
            $application_deadline = $postData['application_deadline'] ?? null;
            $start_date = $postData['start_date'] ?? null;

            // Set premium features
            $isFeaturedRequested = $this->request->getPost('featured_listing') || $this->request->getPost('is_featured');
            $isFeatured = (!$isDraft && $shouldBeFeatured && ($isFeaturedRequested || $hasUnlimitedAccess));
            $postData['is_featured'] = $isFeatured ? 1 : 0;
            $postData['featured_until'] = $isFeatured ? date('Y-m-d H:i:s', strtotime('+30 days')) : null;
            // Urgent Hiring shares the same plan/credit eligibility as Featured Listing
            $isUrgentRequested = $this->request->getPost('urgent_hiring') || $this->request->getPost('is_urgent');
            $postData['is_urgent'] = (!$isDraft && $shouldBeFeatured && $isUrgentRequested) ? 1 : 0;
            $postData['is_anonymous'] = (!$isDraft && $canPostAnonymous && $this->request->getPost('is_anonymous')) ? 1 : 0;
            $postData['network_blast'] = (!$isDraft && $canUseNetworkBlast) ? 1 : 0;
            $postData['application_deadline'] = $application_deadline ? date('Y-m-d H:i:s', strtotime($application_deadline)) : null;
            $postData['start_date'] = $start_date ? date('Y-m-d H:i:s', strtotime($start_date)) : null;

            // Notification preferences
            $notificationPreferences = [
                'email' => $this->request->getPost('notification_email_toggle') ? true : false,
                'in_app' => $this->request->getPost('notification_in_app') ? true : false,
                'notification_email_address' => $this->request->getPost('notification_email') ?? $employer->contact_email
            ];
            $postData['notification_preferences'] = json_encode($notificationPreferences);

            // Salary details
            if ($postData['salary_type'] !== 'negotiable' && !empty($postData['salary'])) {
                $postData['salary_details'] = ucfirst($postData['salary_type']) . ', ' .
                    ucfirst($postData['salary_period']) . ': ' .
                    $postData['salary'];
            } else {
                $postData['salary_details'] = 'Negotiable';
            }

            // Handle application method
            $postData['whatsapp_link'] = $method === 'whatsapp' ? trim($postData['whatsapp_link'] ?? '') : null;
            $postData['application_email'] = $method === 'email' ? trim($postData['application_email'] ?? '') : null;
            $postData['external_url'] = $method === 'external' ? trim($postData['external_url'] ?? '') : null;

            /* ====================== SAVE JOB + DEDUCT CREDIT ====================== */
            $db = db_connect();
            $db->transStart();

            try {
                $jobId = $jobModel->insert($postData);

                if (!$jobId) {
                    $dbError = $jobModel->db->error();
                    $errorMsg = !empty($dbError['message']) ? $dbError['message'] : json_encode($jobModel->errors());
                    throw new \Exception('Failed to create job: ' . $errorMsg);
                }

                // ---- NEW: Save Pre-screening Questions ----
                $questions = $this->request->getPost('questions');
                if (!empty($questions) && is_array($questions)) {
                    $questionModel = model(\App\Models\JobQuestionModel::class);
                    foreach ($questions as $q) {
                        if (!empty($q['text'])) {
                            $allowedTypes = ['text', 'yes_no', 'multiple_choice', 'select', 'radio', 'checkbox'];
                            $qType = in_array($q['type'] ?? 'text', $allowedTypes) ? $q['type'] : 'text';
                            $qOptions = null;
                            if (!empty($q['options'])) {
                                $qOptions = is_array($q['options'])
                                    ? implode(',', array_filter(array_map('trim', $q['options'])))
                                    : trim($q['options']);
                            }
                            $questionModel->insert([
                                'job_id'        => $jobId,
                                'question_text' => trim($q['text']),
                                'question_type' => $qType,
                                'is_required'   => !empty($q['is_required']) ? 1 : 0,
                                'options'       => $qOptions ?: null,
                            ]);
                        }
                    }
                }

                // Only deduct credits if not unlimited access
                if (!$isDraft && !$hasUnlimitedAccess) {
                    if (isset($canPost['source']) && $canPost['source'] === 'wallet') {
                        // Pay-As-You-Go 10,000 Naira deduction
                        $walletService = new \App\Services\WalletService();
                        $reference     = 'post_wallet_' . $employer->id . '_' . $jobId . '_' . time();
                        $walletService->debit($userId, 10000.00, 'post_job', $reference, $jobId, 'Posted job: ' . $postData['title']);
                    } else {
                        $deductResult = $creditService->deductCredits(
                            $userId,
                            1,
                            (string)$jobId,
                            'Posted Job: ' . $postData['title'],
                            'post_job'
                        );

                        if (!$deductResult['success']) {
                            throw new \Exception($deductResult['message']);
                        }
                    }
                }

                // Create in-app notification for job posting
                if (!$isDraft) {
                    $featuredMessage = $shouldBeFeatured ? " This job is FEATURED and will get priority visibility!" : "";
                    $this->createNotification(
                        $employer->id,
                        $jobId,
                        null,
                        'job_pending',
                        'Job Posted - Pending Review',
                        "Your job '{$postData['title']}' has been submitted for admin review.{$featuredMessage}"
                    );
                }

                $db->transComplete();


                // Send email notifications when job is submitted
                if (!$isDraft) {
                    $emailService = new \App\Services\EmailNotificationService();
                    $jobObj = $jobModel->find($jobId);

                    // 1. Notify Admin that a new job was submitted and awaits review
                    try {
                        $emailService->sendJobPostingAdminAlertEmail($jobObj, $employer);
                    } catch (\Throwable $e) {
                        log_message('error', 'Failed sending job posting admin alert: ' . $e->getMessage());
                    }

                    // 2. Notify Employer if preference enabled
                    if (!empty($notificationPreferences['email'])) {
                        $this->sendJobPostingEmail($employer, $postData['title'], $jobId, $shouldBeFeatured);
                    }
                }

                // Build success message
                $successMessage = $isDraft
                    ? 'Draft saved successfully. You can continue editing it from My Jobs.'
                    : ($hasUnlimitedAccess
                        ? 'Job posted successfully! (Unlimited Access - No credits deducted)'
                        : 'Job posted successfully! 1 credit deducted.');

                if (!$isDraft && $shouldBeFeatured) {
                    $successMessage .= ' ⭐ Your job is FEATURED and will appear at the top of search results!';
                }

                if (!$isDraft && $postData['is_anonymous']) {
                    $successMessage .= ' 🔒 Your company name will be hidden.';
                }

                if (!$isDraft && $postData['network_blast']) {
                    $successMessage .= ' 📢 Network blast has been sent to 115k+ subscribers!';
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => $successMessage,
                    'job_id' => $jobId,
                    'is_draft' => $isDraft,
                    'is_featured' => $shouldBeFeatured,
                    'is_anonymous' => (bool)$postData['is_anonymous'],
                    'network_blast' => (bool)$postData['network_blast']
                ]);
            } catch (\Exception $e) {
                $db->transRollback();
                log_message('error', 'Job posting failed: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to post job. ' . $e->getMessage()
                ]);
            }
        }

        /* ====================== GET: Show Post Job Form ====================== */
        $currentPlan = $creditService->getCurrentPlan($userId);
        $subscription = $creditService->getCurrentSubscription($userId);
        $creditBalance = $creditService->getAvailableCredits($userId);
        $hasUnlimitedAccess = $this->hasUnlimitedAccess($employer->id);
        $creditSummary = $creditService->getCreditSummary($userId);

        // Determine if job will be featured
        $willBeFeatured = $this->shouldFeatureJob($employer->id, $userId, $currentPlan, $creditBalance, $hasUnlimitedAccess);
        $canPostAnonymous = $this->canUseAnonymousPosting($currentPlan, $hasUnlimitedAccess);
        $canUseNetworkBlast = $this->canUseNetworkBlast($currentPlan, $hasUnlimitedAccess);

        $data = [
            'title' => 'Post a Job',
            'user' => $user,
            'employer' => $employer,
            'industries' => $industryModel->findAll(),
            'categories' => $categoryModel->findAll(),
            'states' => model(StateModel::class)->findAll(),
            'creditBalance' => $creditBalance,
            'creditSummary' => $creditSummary,
            'currentPlan' => $currentPlan,
            'plan' => $currentPlan ?? (object)['name' => 'No Active Plan'],
            'subscription' => $subscription,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
            'willBeFeatured' => $willBeFeatured,
            'canPostAnonymous' => $canPostAnonymous,
            'canUseNetworkBlast' => $canUseNetworkBlast,
            'job' => null,
        ];

        return view('employers/post-job', $data);
    }

    /**
     * Create in-app notification
     */
    // protected function createNotification($employerId, $jobId, $applicationId, $type, $title, $message)
    // {
    //     // Only create if in-app notifications are enabled for this job
    //     // You can check notification preferences here if needed

    //     $notificationModel = model(JobNotificationModel::class);

    //     $typeMap = [
    //         'new_application' => 'new_application',
    //         'job_pending' => 'job_pending',
    //         'job_approved' => 'job_approved',
    //         'job_rejected' => 'job_rejected',
    //         'job_expiring' => 'job_expiring'
    //     ];

    //     $mappedType = $typeMap[$type] ?? 'new_application';

    //     // Don't manually set created_at - let the model handle it
    //     // $data = [
    //     //     'employer_id' => $employerId,
    //     //     'job_id' => $jobId,
    //     //     'application_id' => $applicationId,
    //     //     'type' => $mappedType,
    //     //     'title' => $title,
    //     //     'message' => $message,
    //     //     'is_read' => 0
    //     //     // created_at will be set automatically by $useTimestamps
    //     // ];

    //     $data = [
    //         'employer_id' => $employerId,
    //         'job_id' => $jobId,
    //         'type' => $mappedType,
    //         'title' => $title,
    //         'message' => $message,
    //         'is_read' => 0
    //     ];

    //     if ($applicationId !== null) {
    //         $data['application_id'] = $applicationId;
    //     }

    //     try {
    //         return $notificationModel->insert($data);
    //     } catch (\Exception $e) {
    //         log_message('error', 'Failed to create notification: ' . $e->getMessage());
    //         return false;
    //     }
    // }

    protected function createNotification($employerId, $jobId, $applicationId, $type, $title, $message)
    {
        $notificationModel = model(JobNotificationModel::class);

        $typeMap = [
            'new_application' => 'new_application',
            'job_pending' => 'job_pending',
            'job_approved' => 'job_approved',
            'job_rejected' => 'job_rejected',
            'job_expiring' => 'job_expiring'
        ];

        $mappedType = $typeMap[$type] ?? 'new_application';

        $data = [
            'employer_id' => (int) $employerId,
            'job_id' => (int) $jobId,
            'type' => (string) $mappedType,
            'title' => (string) $title,
            'message' => (string) $message,
            'is_read' => 0
        ];

        // Only include if NOT null
        if ($applicationId !== null) {
            $data['application_id'] = (int) $applicationId;
        }

        // 🔥 CRITICAL: ensure associative array
        if (array_keys($data) === range(0, count($data) - 1)) {
            throw new \RuntimeException('Notification data is not associative');
        }

        try {
            return $notificationModel->insert($data);
        } catch (\Throwable $e) {
            log_message('error', 'Notification insert failed: ' . json_encode($data));
            log_message('error', $e->getMessage());
            return false;
        }
    }

    // public function myJobs()
    // {
    //     $user = $this->auth->user();
    //     $employerModel = model(EmployerModel::class);
    //     $employer = $employerModel->where('user_id', $user->id)->first();

    //     if (!$employer) {
    //         return redirect()->to('employer/profile/edit')->with('error', 'Please create your company profile first.');
    //     }

    //     $jobModel = model(JobModel::class);
    //     $categoryModel = model(JobCategoryModel::class);
    //     $industryModel = model(IndustryModel::class);
    //     $subscriptionModel = model(UserSubscriptionModel::class);
    //     $applicationModel = model(JobApplicationModel::class);
    //     $clickModel = model(JobClickModel::class);

    //     $creditWalletModel = model(JobCreditWalletModel::class);

    //     $creditBalance = (int) ($creditWalletModel
    //         ->where('user_id', $user->id)
    //         ->selectSum('credits')
    //         ->get()
    //         ->getRow()
    //         ->credits ?? 0);

    //     $activeSub = $subscriptionModel
    //         ->select('user_subscriptions.*, plans.name AS plan_name, plans.features AS plan_features')
    //         ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
    //         ->where('user_id', $user->id)
    //         ->where('user_subscriptions.is_active', 1)
    //         ->first();

    //     if ($activeSub && !empty($activeSub['plan_features'])) {
    //         $activeSub['features_array'] = json_decode($activeSub['plan_features'], true) ?? [];
    //     } else {
    //         $activeSub['features_array'] = [];
    //     }

    //     $featuredLimit = 0;
    //     $featuredUsed = 0;
    //     $remainingFeatured = 0;

    //     $canFeature = false;
    //     $featuredUsed = 0;

    //     $features = [];

    //     if ($activeSub) {
    //         $features = $activeSub['features_array'];

    //         $canFeature = !empty($features['featured']);

    //         if ($canFeature) {
    //             $featuredUsed = $jobModel
    //                 ->where('employer_id', $employer->id)
    //                 ->where('is_featured', 1)
    //                 ->where('featured_until >', date('Y-m-d H:i:s'))
    //                 ->countAllResults();
    //         }
    //     }

    //     // Basic stats
    //     $totalJobs = $jobModel->where('employer_id', $employer->id)->countAllResults();

    //     $activeJobs = $jobModel
    //         ->where('employer_id', $employer->id)
    //         ->where('status', 'open')
    //         ->countAllResults();

    //     $expiredJobs = $totalJobs - $activeJobs;

    //     // Applications stats
    //     $totalApplications = $applicationModel
    //         ->join('jobs', 'jobs.id = job_applications.job_id')
    //         ->where('jobs.employer_id', $employer->id)
    //         ->countAllResults();

    //     // Views stats
    //     $totalClicks = $clickModel
    //         ->join('jobs', 'jobs.id = job_clicks.job_id')
    //         ->where('jobs.employer_id', $employer->id)
    //         ->countAllResults();

    //     // Monthly data for charts (last 12 months)
    //     $monthlyData = $this->getMonthlyAnalytics($employer->id);

    //     // Top performing jobs (by applications)
    //     $topJobs = $jobModel
    //         ->select('jobs.title, COUNT(job_applications.id) as applications')
    //         ->join('job_applications', 'job_applications.job_id = jobs.id', 'left')
    //         ->where('jobs.employer_id', $employer->id)
    //         ->groupBy('jobs.id')
    //         ->orderBy('applications', 'DESC')
    //         ->limit(5)
    //         ->findAll();

    //     // Subscription info
    //     // $activeSub = $subscriptionModel
    //     //     ->where('user_id', $user->id)
    //     //     ->where('is_active', 1)
    //     //     ->where('end_date >', date('Y-m-d H:i:s'))
    //     //     ->first();

    //     $jobs = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.company_name, employers.logo, employers.is_verified')
    //         ->join('states', 'states.id = jobs.state_id', 'left')
    //         ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
    //         ->join('industries', 'industries.id = jobs.industry_id', 'left')
    //         ->join('employers', 'employers.id = jobs.employer_id')
    //         ->where('jobs.employer_id', $employer->id)
    //         ->orderBy('jobs.created_at', 'DESC')
    //         ->findAll();


    //     // $features = planFeatures($activeSub['features_array']);
    //     foreach($jobs as &$job){
    //         $job->anonymous = (bool) (!empty($features['anonymous'])) && ($job->is_anonymous);
    //     }

    //     $data = [
    //         'title'               => 'My Jobs',
    //         'user'                => $user,
    //         'employer'            => $employer,
    //         'jobs'                => $jobs,
    //         'creditBalance' => $creditBalance,
    //         'canFeature'   => $canFeature,
    //         'featuredUsed' => $featuredUsed,
    //         'features' => $features,
    //         'categories'          => $categoryModel->findAll(),
    //         'industries'          => $industryModel->findAll(),
    //         'remainingFeatured'   => $remainingFeatured,
    //         'featuredLimit'       => $featuredLimit === PHP_INT_MAX ? 'Unlimited' : $featuredLimit,
    //         'featuredUsed'        => $featuredUsed, // optional: show in view

    //         'totalJobs'          => $totalJobs,
    //         'activeJobs'         => $activeJobs,
    //         'expiredJobs'        => $expiredJobs,
    //         'totalApplications'  => $totalApplications,
    //         'totalClicks'         => $totalClicks,
    //         'monthlyData'        => $monthlyData,
    //         'topJobs'            => $topJobs,
    //         'activeSubscription' => $activeSub,
    //         'features'          => $features,
    //     ];

    //     return view('employers/my-jobs', $data);
    // }

    // private function getMonthlyAnalytics($employerId)
    // {
    //     $db = db_connect();
    //     $now = new \DateTime();
    //     $start = (clone $now)->modify('-11 months')->format('Y-m-01');

    //     $monthly = $db->query("
    //     SELECT 
    //         m.month,
    //         COALESCE(j.jobs_posted, 0) AS jobs_posted,
    //         COALESCE(a.applications, 0) AS applications,
    //         COALESCE(c.clicks, 0) AS clicks
    //     FROM (
    //         SELECT DATE_FORMAT(created_at, '%Y-%m') AS month
    //         FROM jobs
    //         WHERE employer_id = ?
    //         AND created_at >= ?
    //         GROUP BY month
    //     ) m
    //     LEFT JOIN (
    //         SELECT 
    //             DATE_FORMAT(created_at, '%Y-%m') AS month,
    //             COUNT(*) AS jobs_posted
    //         FROM jobs
    //         WHERE employer_id = ?
    //         AND created_at >= ?
    //         GROUP BY month
    //     ) j ON j.month = m.month
    //     LEFT JOIN (
    //         SELECT 
    //             DATE_FORMAT(ja.created_at, '%Y-%m') AS month,
    //             COUNT(*) AS applications
    //         FROM job_applications ja
    //         JOIN jobs j2 ON j2.id = ja.job_id
    //         WHERE j2.employer_id = ?
    //         AND ja.created_at >= ?
    //         GROUP BY month
    //     ) a ON a.month = m.month
    //     LEFT JOIN (
    //         SELECT 
    //             DATE_FORMAT(jc.created_at, '%Y-%m') AS month,
    //             COUNT(*) AS clicks
    //         FROM job_clicks jc
    //         JOIN jobs j3 ON j3.id = jc.job_id
    //         WHERE j3.employer_id = ?
    //         AND jc.created_at >= ?
    //         GROUP BY month
    //     ) c ON c.month = m.month
    //     ORDER BY m.month ASC
    // ", [
    //         $employerId,
    //         $start,
    //         $employerId,
    //         $start,
    //         $employerId,
    //         $start,
    //         $employerId,
    //         $start,
    //     ])->getResultArray();

    //     // Fill missing months with 0
    //     $result = [];
    //     for ($i = 0; $i < 12; $i++) {
    //         $month = (clone $now)->modify("-{$i} months")->format('Y-m');
    //         $found = array_filter($monthly, fn($m) => $m['month'] === $month);
    //         $data = $found ? reset($found) : ['month' => $month, 'jobs_posted' => 0, 'applications' => 0, 'clicks' => 0];
    //         $result[] = $data;
    //     }

    //     return array_reverse($result);
    // }

    public function myJobs()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please create your company profile first.');
        }

        $jobModel = model(JobModel::class);
        $categoryModel = model(JobCategoryModel::class);
        $industryModel = model(IndustryModel::class);
        $subscriptionModel = model(UserSubscriptionModel::class);
        $applicationModel = model(JobApplicationModel::class);
        $clickModel = model(JobClickModel::class);
        $creditService = new \App\Services\CreditService();

        // Get credit balance using CreditService
        $creditBalance = $creditService->getAvailableCredits($user->id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);
        $currentPlan = $creditService->getCurrentPlan($user->id);
        $subscription = $creditService->getCurrentSubscription($user->id);

        // Prepare active subscription array for view
        $activeSub = null;
        $features = [];
        $canFeature = false;
        $featuredUsed = 0;

        if ($hasUnlimitedAccess) {
            $canFeature = true;
            $featuredUsed = $jobModel
                ->where('employer_id', $employer->id)
                ->where('is_featured', 1)
                ->where('featured_until >', date('Y-m-d H:i:s'))
                ->countAllResults();

            $activeSub = [
                'plan_name' => 'Unlimited Access',
                'starts_at' => null,
                'ends_at' => $employer->unlimited_until,
                'features_array' => [
                    'featured' => true,
                    'anonymous' => true,
                    'network_blast' => true,
                    'url_redirect' => true,
                    'trust_badge' => true,
                    'priority_support' => true
                ]
            ];
            $features = $activeSub['features_array'];
        } elseif ($subscription && $currentPlan) {
            $features = is_string($currentPlan->features) ? json_decode($currentPlan->features, true) : ($currentPlan->features ?? []);
            if (is_object($features)) {
                $features = (array) $features;
            }
            $canFeature = $features['featured'] ?? false;

            if ($canFeature) {
                $featuredUsed = $jobModel
                    ->where('employer_id', $employer->id)
                    ->where('is_featured', 1)
                    ->where('featured_until >', date('Y-m-d H:i:s'))
                    ->countAllResults();
            }

            $activeSub = [
                'plan_name' => $currentPlan->name,
                'starts_at' => $subscription->starts_at,
                'ends_at' => $subscription->ends_at,
                'features_array' => $features
            ];
        }

        // Basic stats
        $totalJobs = $jobModel->where('employer_id', $employer->id)->countAllResults();

        $activeJobs = $jobModel
            ->where('employer_id', $employer->id)
            ->where('status', 'open')
            ->where('admin_status', 'approved')
            ->countAllResults();

        // Applications stats
        $totalApplications = $applicationModel
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->where('jobs.employer_id', $employer->id)
            ->countAllResults();

        // Views stats — sum the actual page-view counter stored on each job row.
        // (job_clicks counts apply-button clicks, which is a separate metric.)
        $db = \Config\Database::connect();
        $viewsRow     = $db->table('jobs')
            ->selectSum('views')
            ->where('employer_id', $employer->id)
            ->get()->getRow();
        $totalClicks = (int) ($viewsRow->views ?? 0);

        // Monthly data for charts (last 12 months)
        $monthlyData = $this->getMonthlyAnalytics($employer->id);

        // Top performing jobs (by applications)
        $topJobs = $jobModel
            ->select('jobs.title, COUNT(job_applications.id) as applications')
            ->join('job_applications', 'job_applications.job_id = jobs.id', 'left')
            ->where('jobs.employer_id', $employer->id)
            ->groupBy('jobs.id')
            ->orderBy('applications', 'DESC')
            ->limit(5)
            ->findAll();

        // Get all jobs with relations
        $jobs = $jobModel
            ->select('jobs.*, jobs.application_deadline as deadline, job_categories.name as category_name, industries.name as industry_name, states.name as location, COUNT(job_applications.id) as applicants_count')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('job_applications', 'job_applications.job_id = jobs.id', 'left')
            ->where('jobs.employer_id', $employer->id)
            ->groupBy('jobs.id')
            ->orderBy('jobs.created_at', 'DESC')
            ->findAll();

        // Set anonymous flag based on plan features
        foreach ($jobs as &$job) {
            $job->anonymous = ($features['anonymous'] ?? false) && ($job->is_anonymous == 1);
        }

        $data = [
            'title'               => 'My Jobs',
            'user'                => $user,
            'employer'            => $employer,
            'jobs'                => $jobs,
            'creditBalance'       => $creditBalance,
            'hasUnlimitedAccess'  => $hasUnlimitedAccess,
            'canFeature'          => $canFeature,
            'featuredUsed'        => $featuredUsed,
            'features'            => $features,
            'categories'          => $categoryModel->findAll(),
            'industries'          => $industryModel->findAll(),
            'totalJobs'           => $totalJobs,
            'activeJobs'          => $activeJobs,
            'totalApplications'   => $totalApplications,
            'totalClicks'         => $totalClicks,
            'monthlyData'         => $monthlyData,
            'topJobs'             => $topJobs,
            'activeSubscription'  => $activeSub,
            'currentPlan'         => $currentPlan,
            'subscription'        => $subscription,
        ];

        return view('employers/my-jobs', $data);
    }

    /**
     * Get monthly analytics for charts
     */
    private function getMonthlyAnalytics($employerId)
    {
        $db = db_connect();
        $months = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));

            // Jobs posted
            $jobsPosted = $db->table('jobs')
                ->where('employer_id', $employerId)
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                ->countAllResults();

            // Applications
            $applications = $db->table('job_applications')
                ->join('jobs', 'jobs.id = job_applications.job_id')
                ->where('jobs.employer_id', $employerId)
                ->where('DATE_FORMAT(job_applications.created_at, "%Y-%m")', $month)
                ->countAllResults();

            // Views
            $views = $db->table('jobs')
                ->select('COALESCE(SUM(views), 0) as total')
                ->where('employer_id', $employerId)
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                ->get()
                ->getRow();

            $months[] = [
                'month' => $monthName,
                'jobs_posted' => $jobsPosted,
                'applications' => $applications,
                'views' => (int)($views->total ?? 0)
            ];
        }

        return $months;
    }

    /**
     * View single job details
     */
    /**
     * Preview a job on the public candidate-facing page.
     * Verifies the job belongs to this employer, then redirects to the public
     * job detail with ?employer_preview=1 so a preview banner is shown.
     */
    public function previewJob(int $jobId)
    {
        $user          = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer      = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/dashboard')->with('error', 'Employer profile not found.');
        }

        $jobModel = model(JobModel::class);
        $job      = $jobModel->where('id', $jobId)->where('employer_id', $employer->id)->first();

        if (!$job) {
            return redirect()->to('employer/jobs')->with('error', 'Job not found or access denied.');
        }

        $slug      = $job->slug ?? $jobId;
        $targetUrl = base_url('jobs/' . $slug) . '?employer_preview=1';

        return redirect()->to($targetUrl);
    }

    public function viewJob($jobId)
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        $jobModel = model(JobModel::class);
        $applicationModel = model(JobApplicationModel::class);
        $clickModel = model(JobClickModel::class);
        $creditService = new \App\Services\CreditService();

        // Get job details with relations
        $job = $jobModel
            ->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.company_name, employers.logo, employers.is_verified')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id')
            ->where('jobs.id', $jobId)
            ->where('jobs.employer_id', $employer->id)
            ->first();

        if (!$job) {
            return redirect()->to('employer/my-jobs')->with('error', 'Job not found or access denied.');
        }

        // Get applications for this job
        $applications = $applicationModel
            ->select('
        job_applications.*, 
        job_seekers.full_name AS fullname, 
        auth_identities.secret AS email, 
        job_seekers.phone, 
        job_seekers.profile_picture AS avatar, 
        job_seekers.location
    ')
            ->join('job_seekers', 'job_seekers.id = job_applications.job_seeker_id', 'left')
            ->join('users', 'users.id = job_seekers.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id', 'left')
            ->where('job_applications.job_id', $jobId)
            ->orderBy('job_applications.created_at', 'DESC')
            ->findAll();

        // Get application statistics
        $applicationStats = [
            'total' => count($applications),
            'pending' => 0,
            'reviewed' => 0,
            'shortlisted' => 0,
            'rejected' => 0,
            'hired' => 0
        ];

        foreach ($applications as $app) {
            switch ($app->status) {
                case 'pending':
                    $applicationStats['pending']++;
                    break;
                case 'reviewed':
                    $applicationStats['reviewed']++;
                    break;
                case 'shortlisted':
                    $applicationStats['shortlisted']++;
                    break;
                case 'rejected':
                    $applicationStats['rejected']++;
                    break;
                case 'hired':
                    $applicationStats['hired']++;
                    break;
            }
        }

        // Get daily application trend (last 7 days)
        $dailyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $count = $applicationModel
                ->where('job_id', $jobId)
                ->where('DATE(created_at)', $date)
                ->countAllResults();

            $dailyTrend[] = [
                'date' => date('M d', strtotime($date)),
                'count' => $count
            ];
        }

        // Get click/views data
        $totalClicks = $clickModel
            ->where('job_id', $jobId)
            ->countAllResults();

        $dailyClicks = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $count = $clickModel
                ->where('job_id', $jobId)
                ->where('DATE(created_at)', $date)
                ->countAllResults();

            $dailyClicks[] = [
                'date' => date('M d', strtotime($date)),
                'count' => $count
            ];
        }

        // Get credit balance and plan info
        $creditBalance = $creditService->getAvailableCredits($user->id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);
        $currentPlan = $creditService->getCurrentPlan($user->id);

        // Check if job can be featured
        $canFeature = false;
        if ($hasUnlimitedAccess) {
            $canFeature = true;
        } elseif ($currentPlan && $currentPlan->features) {
            $features = is_string($currentPlan->features) ? json_decode($currentPlan->features, true) : ($currentPlan->features ?? []);
            $canFeature = $features['featured'] ?? false;
        }

        $data = [
            'title' => $job->title . ' - Job Details',
            'user' => $user,
            'employer' => $employer,
            'job' => $job,
            'applications' => $applications,
            'applicationStats' => $applicationStats,
            'dailyTrend' => $dailyTrend,
            'dailyClicks' => $dailyClicks,
            'totalClicks' => $totalClicks,
            'creditBalance' => $creditBalance,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
            'canFeature' => $canFeature,
            'currentPlan' => $currentPlan,
        ];

        return view('employers/view-job', $data);
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $applicationId = $this->request->getPost('application_id');
        $status = $this->request->getPost('status');
        $messageToCandidate = $this->request->getPost('message_to_candidate') ?? null;

        $allowedStatuses = ['pending', 'reviewed', 'shortlisted', 'rejected', 'hired'];
        if (!in_array($status, $allowedStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $applicationModel = model(JobApplicationModel::class);
        $application = $applicationModel->find($applicationId);

        if (!$application) {
            return $this->response->setJSON(['success' => false, 'message' => 'Application not found']);
        }

        // Verify ownership
        $jobModel = model(JobModel::class);
        $job = $jobModel->find($application->job_id);

        if (!$job || $job->employer_id != $employer->id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Update status (status_message is shown to the candidate in their portal)
        $applicationModel->update($applicationId, [
            'status'         => $status,
            'status_message' => $messageToCandidate,
            'reviewed_at'    => date('Y-m-d H:i:s')
        ]);

        // Add a note for internal record
        $noteModel = model(ApplicationNoteModel::class);
        $noteModel->addNote(
            $applicationId,
            $employer->id,
            "Status changed to " . ucfirst($status) . ".\nMessage to candidate: " . ($messageToCandidate ?? 'No additional message'),
            $user->id,
            'feedback'
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update application status. Database error.']);
        }

        // Send email notification to job seeker (works for both authenticated and guest)
        $emailSent = false;
        try {
            $emailService = new \App\Services\EmailNotificationService();
            $emailSent = $emailService->sendApplicationStatusEmail(
                $application,
                $status,
                $job->title,
                $employer->company_name,
                $messageToCandidate
            );
        } catch (\Throwable $e) {
            log_message('error', 'Status update notification error: ' . $e->getMessage());
            $emailSent = false;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Application status updated successfully. ' . ($emailSent ? 'The candidate has been notified.' : 'Status saved.'),
            'status'  => $status,
            'email_sent' => $emailSent,
            'is_guest'   => (bool)($application->is_guest ?? false),
            'reload'     => false
        ]);
    }

    /**
     * Employer Aptitude & Screening Tests Bank page with shareable links
     */
    public function aptitudeTests()
    {
        $db = \Config\Database::connect();
        if (!$db->fieldExists('employer_id', 'tests')) {
            $db->query("ALTER TABLE `tests` ADD COLUMN `employer_id` INT UNSIGNED NULL DEFAULT NULL AFTER `id`");
        }

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', auth()->id())->first();
        $employerId = $employer ? $employer->id : 0;

        $tests = $db->table('tests t')
            ->select('t.id, t.employer_id, t.title, t.slug, t.description, t.duration_mins, t.num_questions, t.pass_threshold, t.difficulty, jc.name AS category_name')
            ->join('job_categories jc', 'jc.id = t.category_id', 'left')
            ->where('t.is_active', 1)
            ->groupStart()
                ->where('t.employer_id IS NULL')
                ->orWhere('t.employer_id', $employerId)
            ->groupEnd()
            ->orderBy('(t.employer_id IS NULL)', 'ASC', false)
            ->orderBy('t.title', 'ASC')
            ->get()->getResultArray();

        $categories = $db->table('job_categories')
            ->select('id, name')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        // Read filter params from GET
        $filterJob    = (int) ($this->request->getGet('job_id') ?? 0);
        $filterStatus = trim($this->request->getGet('status') ?? '');
        $searchQuery  = trim($this->request->getGet('search') ?? '');

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', auth()->id())->first();
        $myJobs = [];
        $invitations = [];

        if ($employer) {
            $jobModel = model(JobModel::class);
            $myJobs = $jobModel->where('employer_id', $employer->id)->orderBy('title', 'ASC')->findAll();

            $builder = $db->table('aptitude_test_invitations ati')
                ->select('ati.*, t.title as test_title, t.pass_threshold, t.duration_mins, t.num_questions,
                    ja.first_name, ja.last_name, ja.email as applicant_email, ja.phone as applicant_phone, ja.status as application_status,
                    js.profile_picture as seeker_avatar, js.full_name as seeker_full_name, js.phone as seeker_phone,
                    j.title as job_title, j.id as job_id,
                    ta.status as attempt_status, ta.score_pct, ta.passed, ta.started_at, ta.submitted_at, ta.num_total as total_questions, ta.num_correct as correct_answers')
                ->join('tests t', 't.id = ati.test_id', 'left')
                ->join('job_seekers js', 'js.id = ati.candidate_id OR js.user_id = ati.candidate_id', 'left')
                ->join('job_applications ja', 'ja.id = ati.application_id OR (ja.job_seeker_id = js.id AND ja.job_id = ati.job_id)', 'left')
                ->join('jobs j', 'j.id = ati.job_id', 'left')
                ->join('test_attempts ta', 'ta.id = ati.attempt_id OR (ati.attempt_id IS NULL AND ta.test_id = ati.test_id AND (ta.candidate_id = ati.candidate_id OR (js.user_id IS NOT NULL AND ta.candidate_id = js.user_id)))', 'left')
                ->where('ati.employer_id', $employer->id);

            if ($filterJob > 0) {
                $builder->where('ati.job_id', $filterJob);
            }
            if ($filterStatus !== '') {
                if ($filterStatus === 'completed') {
                    $builder->groupStart()
                        ->where('ta.status', 'submitted')
                        ->orWhere('ta.status', 'completed')
                        ->orWhere('ati.status', 'completed')
                    ->groupEnd();
                } elseif ($filterStatus === 'in_progress') {
                    $builder->where('ta.status', 'in_progress');
                } elseif ($filterStatus === 'expired') {
                    $builder->where('ati.status', 'expired');
                } elseif ($filterStatus === 'pending') {
                    $builder->where('ati.status', 'pending')
                        ->groupStart()
                            ->where('ta.status IS NULL')
                            ->orWhere('ta.status', 'pending')
                        ->groupEnd();
                }
            }
            if ($searchQuery !== '') {
                $builder->groupStart()
                    ->like('ja.first_name', $searchQuery)
                    ->orLike('ja.last_name', $searchQuery)
                    ->orLike('ja.email', $searchQuery)
                    ->orLike('js.full_name', $searchQuery)
                    ->groupEnd();
            }

            $invitations = $builder->orderBy('ati.created_at', 'DESC')->get()->getResultArray();
        }

        $isAttemptCompleted = static function($i) {
            $attStatus = strtolower($i['attempt_status'] ?? '');
            $invStatus = strtolower($i['status'] ?? '');
            return ($attStatus === 'submitted' || $attStatus === 'completed' || $invStatus === 'completed');
        };

        // KPI stats
        $kpiStats = [
            'total_invited' => count($invitations),
            'in_progress'   => count(array_filter($invitations, fn($i) => strtolower($i['attempt_status'] ?? '') === 'in_progress')),
            'completed'     => count(array_filter($invitations, $isAttemptCompleted)),
            'passed'        => count(array_filter($invitations, fn($i) => !empty($i['passed']))),
            'pass_rate'     => 0,
            'avg_score'     => 0,
        ];
        $completed = array_filter($invitations, $isAttemptCompleted);
        if (count($completed) > 0) {
            $kpiStats['pass_rate'] = round(($kpiStats['passed'] / count($completed)) * 100);
            $scores = array_filter(array_column($completed, 'score_pct'), fn($s) => $s !== null && $s !== '');
            $kpiStats['avg_score'] = count($scores) > 0 ? round(array_sum($scores) / count($scores)) : 0;
        }

        return view('employers/aptitude_tests', [
            'title'        => 'Screening & Aptitude Tests',
            'tests'        => $tests,
            'categories'   => $categories,
            'employerId'   => $employerId,
            'myJobs'       => $myJobs,
            'invitations'  => $invitations,
            'filterJob'    => $filterJob,
            'filterStatus' => $filterStatus,
            'searchQuery'  => $searchQuery,
            'kpiStats'     => $kpiStats,
        ]);
    }

    /**
     * Get available active aptitude tests for employer invitation modal
     */
    public function getAvailableAptitudeTests()
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', auth()->id())->first();
        $employerId = $employer ? $employer->id : 0;

        $db = \Config\Database::connect();
        if (!$db->fieldExists('employer_id', 'tests')) {
            $db->query("ALTER TABLE `tests` ADD COLUMN `employer_id` INT UNSIGNED NULL DEFAULT NULL AFTER `id`");
        }

        // Return both global tests AND employer's own custom tests
        $tests = $db->table('tests')
            ->select('id, title, slug, duration_mins, num_questions, pass_threshold, difficulty, employer_id')
            ->where('is_active', 1)
            ->groupStart()
                ->where('employer_id IS NULL')
                ->orWhere('employer_id', $employerId)
            ->groupEnd()
            ->orderBy('(employer_id IS NULL)', 'ASC', false)   // employer tests first
            ->orderBy('title', 'ASC')
            ->get()
            ->getResultObject();

        return $this->response->setJSON(['success' => true, 'tests' => $tests]);
    }

    // ══════════════════════════════════════════════════
    // EMPLOYER CUSTOM TEST MANAGEMENT
    // ══════════════════════════════════════════════════

    /**
     * List employer's own custom tests (JSON)
     */
    public function myTests()
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', auth()->id())->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $db = \Config\Database::connect();
        $tests = $db->table('tests t')
            ->select('t.*, COUNT(q.id) as question_count')
            ->join('questions q', 'q.test_id = t.id', 'left')
            ->where('t.employer_id', $employer->id)
            ->groupBy('t.id')
            ->orderBy('t.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->response->setJSON(['success' => true, 'tests' => $tests]);
    }

    /**
     * Create a custom test for an employer
     */
    public function createCustomTest()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'POST required']);
        }

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', auth()->id())->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $title = trim($this->request->getPost('title') ?? '');
        if (empty($title)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Test title is required']);
        }

        $db = \Config\Database::connect();

        // Unique slug scoped to employer
        $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        if (empty($baseSlug)) $baseSlug = 'custom-test';
        $slug = $baseSlug . '-emp' . $employer->id;
        $counter = 1;
        while ($db->table('tests')->where('slug', $slug)->countAllResults()) {
            $slug = $baseSlug . '-emp' . $employer->id . '-' . $counter++;
        }

        $testId = $db->table('tests')->insert([
            'employer_id'    => $employer->id,
            'category_id'    => (int) ($this->request->getPost('category_id') ?: 1),
            'title'          => $title,
            'slug'           => $slug,
            'description'    => $this->request->getPost('description') ?? '',
            'duration_mins'  => (int) ($this->request->getPost('duration_mins') ?: 20),
            'num_questions'  => (int) ($this->request->getPost('num_questions') ?: 10),
            'pass_threshold' => (int) ($this->request->getPost('pass_threshold') ?: 50),
            'difficulty'     => $this->request->getPost('difficulty') ?: 'intermediate',
            'is_active'      => 1,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        if (!$db->affectedRows()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to create test']);
        }

        $testId = $db->insertID();

        // Save questions if provided
        $questionsRaw = $this->request->getPost('questions');
        if ($questionsRaw) {
            $this->saveEmployerTestQuestions($testId, $questionsRaw);
        }

        return $this->response->setJSON([
            'success' => true,
            'test_id' => $testId,
            'message' => 'Custom test created successfully!'
        ]);
    }

    /**
     * Update an employer's custom test
     */
    public function updateCustomTest($testId)
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', auth()->id())->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $db = \Config\Database::connect();
        $test = $db->table('tests')->where('id', $testId)->where('employer_id', $employer->id)->get()->getRowArray();
        if (!$test) {
            return $this->response->setJSON(['success' => false, 'message' => 'Test not found or access denied']);
        }

        $title = trim($this->request->getPost('title') ?? '') ?: $test['title'];
        $db->table('tests')->where('id', $testId)->update([
            'category_id'    => (int) ($this->request->getPost('category_id') ?: $test['category_id']),
            'title'          => $title,
            'description'    => $this->request->getPost('description') ?? $test['description'],
            'duration_mins'  => (int) ($this->request->getPost('duration_mins') ?: $test['duration_mins']),
            'num_questions'  => (int) ($this->request->getPost('num_questions') ?: $test['num_questions']),
            'pass_threshold' => (int) ($this->request->getPost('pass_threshold') ?: $test['pass_threshold']),
            'difficulty'     => $this->request->getPost('difficulty') ?: $test['difficulty'],
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        $questionsRaw = $this->request->getPost('questions');
        if ($questionsRaw) {
            $this->saveEmployerTestQuestions((int) $testId, $questionsRaw);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Test updated successfully']);
    }

    /**
     * Delete an employer's custom test
     */
    public function deleteCustomTest($testId)
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', auth()->id())->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $db = \Config\Database::connect();
        $test = $db->table('tests')->where('id', $testId)->where('employer_id', $employer->id)->get()->getRowArray();
        if (!$test) {
            return $this->response->setJSON(['success' => false, 'message' => 'Test not found or access denied']);
        }

        // Delete questions and options first
        $questions = $db->table('questions')->where('test_id', $testId)->get()->getResultArray();
        foreach ($questions as $q) {
            $db->table('question_options')->where('question_id', $q['id'])->delete();
        }
        $db->table('questions')->where('test_id', $testId)->delete();
        $db->table('tests')->where('id', $testId)->where('employer_id', $employer->id)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'Test deleted successfully']);
    }

    /**
     * Get questions for an employer's test (for editing)
     */
    public function getCustomTestQuestions($testId)
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', auth()->id())->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $db = \Config\Database::connect();
        $test = $db->table('tests')->where('id', $testId)->where('employer_id', $employer->id)->get()->getRowArray();
        if (!$test) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $questions = $db->table('questions')->where('test_id', $testId)->get()->getResultArray();
        foreach ($questions as &$q) {
            $q['options'] = $db->table('question_options')->where('question_id', $q['id'])->orderBy('sort_order')->get()->getResultArray();
        }

        return $this->response->setJSON(['success' => true, 'test' => $test, 'questions' => $questions]);
    }

    /**
     * AI-generate questions for employer's custom test
     */
    public function aiGenerateTestQuestions()
    {
        $title       = $this->request->getPost('title') ?? 'General Aptitude';
        $description = $this->request->getPost('description') ?? '';
        $numQ        = (int) ($this->request->getPost('num_questions') ?: 5);

        $aiService = new \App\Services\AiService();
        $questions = $aiService->generateCustomAptitudeQuestions($title, $description, $numQ);
        if (empty($questions)) {
            $questions = $aiService->getFallbackCourseTestQuestions($title, $numQ);
        }

        return $this->response->setJSON(['success' => true, 'questions' => $questions]);
    }

    /**
     * Save questions for an employer-owned test
     */
    private function saveEmployerTestQuestions(int $testId, $questionsRaw): int
    {
        $decoded = is_string($questionsRaw) ? json_decode($questionsRaw, true) : $questionsRaw;
        if (!is_array($decoded) || empty($decoded)) return 0;

        $db = \Config\Database::connect();

        // Wipe existing questions for fresh sync
        $existing = $db->table('questions')->where('test_id', $testId)->get()->getResultArray();
        foreach ($existing as $eq) {
            $db->table('question_options')->where('question_id', $eq['id'])->delete();
        }
        $db->table('questions')->where('test_id', $testId)->delete();

        $count = 0;
        foreach ($decoded as $q) {
            $body = trim($q['question'] ?? $q['body'] ?? '');
            if (empty($body)) continue;

            $db->table('questions')->insert([
                'test_id'     => $testId,
                'type'        => 'mcq',
                'body'        => $body,
                'difficulty'  => 'intermediate',
                'explanation' => $q['explanation'] ?? '',
                'points'      => 1,
                'is_active'   => 1,
            ]);
            $qId = $db->insertID();

            foreach ($q['options'] ?? [] as $idx => $opt) {
                $optText   = is_array($opt) ? ($opt['text'] ?? $opt['body'] ?? '') : (string) $opt;
                $isCorrect = is_array($opt) ? (!empty($opt['is_correct']) ? 1 : 0) : 0;
                if (empty($optText)) continue;
                $db->table('question_options')->insert([
                    'question_id' => $qId,
                    'body'        => $optText,
                    'is_correct'  => $isCorrect,
                    'sort_order'  => $idx,
                ]);
            }
            $count++;
        }

        // Keep num_questions in sync
        $db->table('tests')->where('id', $testId)->update(['num_questions' => max(1, $count)]);
        return $count;
    }



    /**
     * Send aptitude test invitation to applicant
     */
    public function inviteToAptitudeTest()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer profile not found']);
        }

        $rawTestId = $this->request->getPost('test_id');
        $applicationId = (int) $this->request->getPost('application_id');
        $daysToComplete = max(1, min(30, (int) ($this->request->getPost('days_to_complete') ?? 7)));
        $customMessage = trim((string) $this->request->getPost('message'));

        $applicationModel = model(JobApplicationModel::class);
        $application = $applicationModel->find($applicationId);
        if (!$application) {
            return $this->response->setJSON(['success' => false, 'message' => 'Application not found']);
        }

        $jobModel = model(JobModel::class);
        $job = $jobModel->find($application->job_id);
        if (!$job || $job->employer_id != $employer->id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();

        if ($rawTestId === 'ai_custom') {
            $reqNumQuestions = max(3, min(50, (int) ($this->request->getPost('num_questions') ?? 5)));
            $reqDuration = max(5, min(120, (int) ($this->request->getPost('duration_mins') ?? 15)));
            $reqDifficulty = in_array($this->request->getPost('difficulty'), ['beginner', 'intermediate', 'advanced']) ? $this->request->getPost('difficulty') : 'intermediate';

            // Use Gemini AI to generate custom questions tailored to this job
            $aiService = new \App\Services\AiService();
            $aiQuestions = $aiService->generateCustomAptitudeQuestions($job->title, $job->description ?? '', $reqNumQuestions, $reqDifficulty);

            if (empty($aiQuestions)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to generate AI custom questions. Please select a preset test or try again.']);
            }

            $testSlug = 'custom-ai-' . $job->id . '-' . time();
            $testTitle = "AI Custom Assessment: " . $job->title;
            $now = date('Y-m-d H:i:s');

            $db->table('tests')->insert([
                'category_id'    => $job->category_id ?? 1,
                'title'          => $testTitle,
                'slug'           => $testSlug,
                'description'    => "AI-generated tailored candidate screening test for " . $job->title,
                'duration_mins'  => $reqDuration,
                'num_questions'  => count($aiQuestions),
                'pass_threshold' => 60,
                'difficulty'     => $reqDifficulty,
                'is_active'      => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            $testId = $db->insertID();

            foreach ($aiQuestions as $qItem) {
                $db->table('questions')->insert([
                    'test_id'     => $testId,
                    'type'        => 'mcq',
                    'body'        => $qItem['question'] ?? 'Question',
                    'difficulty'  => $qItem['difficulty'] ?? 'intermediate',
                    'points'      => 1,
                    'explanation' => $qItem['explanation'] ?? '',
                    'is_active'   => 1,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
                $qId = $db->insertID();

                if (!empty($qItem['options'])) {
                    $sort = 0;
                    foreach ($qItem['options'] as $opt) {
                        $db->table('question_options')->insert([
                            'question_id' => $qId,
                            'body'        => $opt['text'] ?? 'Option',
                            'is_correct'  => !empty($opt['is_correct']) ? 1 : 0,
                            'sort_order'  => $sort++,
                        ]);
                    }
                }
            }

            $test = $db->table('tests')->where('id', $testId)->get()->getRowObject();
        } else {
            $testId = (int) $rawTestId;
            $test = $db->table('tests')->where('id', $testId)->where('is_active', 1)->get()->getRowObject();
        }

        if (!$test) {
            return $this->response->setJSON(['success' => false, 'message' => 'Selected aptitude test is unavailable']);
        }

        $token = bin2hex(random_bytes(24));
        $dueDate = date('Y-m-d H:i:s', strtotime("+{$daysToComplete} days"));

        $candidateUserId = (int) ($application->user_id ?? 0);
        if ($candidateUserId === 0 && !empty($application->job_seeker_id)) {
            $js = $db->table('job_seekers')->select('user_id')->where('id', $application->job_seeker_id)->get()->getRowObject();
            if ($js) {
                $candidateUserId = (int) $js->user_id;
            }
        }

        $candidateName = trim(($application->first_name ?? '') . ' ' . ($application->last_name ?? '')) ?: 'Candidate';
        $candidateEmail = (string) ($application->email ?? '');
        if (empty($candidateEmail) && $candidateUserId > 0) {
            $userModel = model(\App\Models\UserModel::class);
            $candUser = $userModel->find($candidateUserId);
            if ($candUser && !empty($candUser->email)) {
                $candidateEmail = (string) $candUser->email;
            }
        }

        $invitationModel = model(\App\Models\AptitudeTestInvitationModel::class);
        $invitationModel->insert([
            'employer_id'     => $employer->id,
            'candidate_id'    => $candidateUserId,
            'job_id'          => $job->id,
            'application_id'  => $application->id,
            'test_id'         => $test->id,
            'code'            => $token,
            'invitation_code' => $token,
            'email'           => $candidateEmail,
            'message'         => $customMessage,
            'due_date'        => $dueDate,
            'status'          => 'pending',
        ]);

        // Update application status to shortlisted if currently pending/reviewed
        if (in_array($application->status, ['pending', 'reviewed'])) {
            $applicationModel->update($application->id, [
                'status'         => 'shortlisted',
                'status_message' => "Invited to complete Aptitude Test: {$test->title}",
                'reviewed_at'    => date('Y-m-d H:i:s')
            ]);
        }

        // Add application note
        $noteModel = model(ApplicationNoteModel::class);
        $noteModel->addNote(
            $application->id,
            $employer->id,
            "Invited candidate to Aptitude Test: {$test->title} (Due: " . date('F j, Y', strtotime($dueDate)) . ")",
            $user->id,
            'feedback'
        );

        // Send Email Notification
        $invitationUrl = site_url('aptitude/invite/' . $token);
        $emailService = new \App\Services\EmailNotificationService();
        $emailSent = false;
        if (!empty($candidateEmail)) {
            $emailSent = $emailService->sendAptitudeTestInvitationEmail(
                $candidateEmail,
                $candidateName,
                $test->title,
                $employer->company_name,
                $job->title,
                $invitationUrl,
                $dueDate,
                $customMessage
            );
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Aptitude test invitation sent successfully to {$candidateName}!",
            'email_sent' => $emailSent,
            'invitation_url' => $invitationUrl
        ]);
    }

    /**
     * Resend an existing aptitude test invitation email to candidate
     */
    public function resendAptitudeInvite()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $user = $this->auth->user();
        $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer profile not found']);
        }

        $invId = (int) $this->request->getPost('invitation_id');
        $db = \Config\Database::connect();
        $inv = $db->table('aptitude_test_invitations ati')
            ->select('ati.*, t.title as test_title, j.title as job_title, ja.first_name, ja.last_name, ja.email as applicant_email, js.full_name as seeker_full_name')
            ->join('tests t', 't.id = ati.test_id', 'left')
            ->join('jobs j', 'j.id = ati.job_id', 'left')
            ->join('job_seekers js', 'js.id = ati.candidate_id OR js.user_id = ati.candidate_id', 'left')
            ->join('job_applications ja', 'ja.id = ati.application_id OR (ja.job_seeker_id = js.id AND ja.job_id = ati.job_id)', 'left')
            ->where('ati.id', $invId)
            ->where('ati.employer_id', $employer->id)
            ->get()->getRowArray();

        if (!$inv) {
            return $this->response->setJSON(['success' => false, 'message' => 'Assessment invitation not found']);
        }

        $candName = trim(($inv['first_name'] ?? '') . ' ' . ($inv['last_name'] ?? '')) ?: ($inv['seeker_full_name'] ?? 'Candidate');
        $candEmail = $inv['applicant_email'] ?? ($inv['email'] ?? '');
        if (empty($candEmail) && !empty($inv['candidate_id'])) {
            $seeker = $db->table('job_seekers js')
                ->select('u.email')
                ->join('users u', 'u.id = js.user_id', 'left')
                ->where('js.id', $inv['candidate_id'])
                ->orWhere('js.user_id', $inv['candidate_id'])
                ->get()->getRowArray();
            if ($seeker && !empty($seeker['email'])) {
                $candEmail = $seeker['email'];
            }
        }

        $token = ($inv['invitation_code'] ?? '') ?: ($inv['code'] ?? '');
        $invitationUrl = site_url('aptitude/invite/' . $token);

        $emailService = new \App\Services\EmailNotificationService();
        $emailSent = false;
        if (!empty($candEmail)) {
            $emailSent = $emailService->sendAptitudeTestInvitationEmail(
                $candEmail,
                $candName,
                $inv['test_title'] ?? 'Screening Assessment',
                $employer->company_name ?? 'JobberRecruit Employer',
                $inv['job_title'] ?? 'Role Assessment',
                $invitationUrl,
                $inv['due_date'] ?? date('Y-m-d H:i:s', strtotime('+7 days')),
                $inv['message'] ?? ''
            );
        }

        return $this->response->setJSON([
            'success'        => true,
            'message'        => 'Invitation link resent successfully to ' . $candName . '!',
            'invitation_url' => $invitationUrl,
            'email_sent'     => $emailSent
        ]);
    }

    /**
     * Get candidate attempt breakdown & analytics for employer review
     */
    public function getAptitudeAttemptResult($invId)
    {
        $user = $this->auth->user();
        $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer profile not found']);
        }

        $db = \Config\Database::connect();
        $inv = $db->table('aptitude_test_invitations ati')
            ->select('ati.*, t.title as test_title, t.pass_threshold, t.duration_mins, t.num_questions,
                     ja.first_name, ja.last_name, ja.email as applicant_email, ja.status as application_status, ja.id as app_id,
                     js.full_name as seeker_full_name, js.profile_picture as seeker_avatar,
                     j.title as job_title,
                     ta.id as attempt_id, ta.status as attempt_status, ta.score_pct, ta.passed, ta.started_at, ta.submitted_at, ta.num_total, ta.num_correct, ta.question_ids')
            ->join('tests t', 't.id = ati.test_id', 'left')
            ->join('job_seekers js', 'js.id = ati.candidate_id OR js.user_id = ati.candidate_id', 'left')
            ->join('job_applications ja', 'ja.id = ati.application_id OR (ja.job_seeker_id = js.id AND ja.job_id = ati.job_id)', 'left')
            ->join('jobs j', 'j.id = ati.job_id', 'left')
            ->join('test_attempts ta', 'ta.id = ati.attempt_id OR (ati.attempt_id IS NULL AND ta.test_id = ati.test_id AND (ta.candidate_id = ati.candidate_id OR (js.user_id IS NOT NULL AND ta.candidate_id = js.user_id)))', 'left')
            ->where('ati.id', (int) $invId)
            ->where('ati.employer_id', $employer->id)
            ->get()->getRowArray();

        if (!$inv) {
            return $this->response->setJSON(['success' => false, 'message' => 'Assessment invitation not found']);
        }

        $breakdown = [];
        $attemptId = (int) ($inv['attempt_id'] ?? 0);
        if ($attemptId > 0 && !empty($inv['question_ids'])) {
            $questionModel = new \App\Models\QuestionModel();
            $optionModel   = new \App\Models\QuestionOptionModel();
            $answerModel   = new \App\Models\AttemptAnswerModel();

            $answersByQ = [];
            foreach ($answerModel->where('attempt_id', $attemptId)->findAll() as $a) {
                $answersByQ[(int) $a['question_id']] = $a;
            }

            $qIds = json_decode($inv['question_ids'], true) ?: [];
            foreach ($qIds as $idx => $qId) {
                $q = $questionModel->find($qId);
                if (!$q) continue;

                $options = $optionModel->where('question_id', $qId)->orderBy('id', 'ASC')->findAll();
                $selectedIds = [];
                if (isset($answersByQ[$qId]['selected_option_ids'])) {
                    $selectedIds = json_decode($answersByQ[$qId]['selected_option_ids'], true) ?: [];
                }

                $breakdown[] = [
                    'number'      => $idx + 1,
                    'body'        => $q['body'],
                    'explanation' => $q['explanation'] ?? '',
                    'is_correct'  => (bool) ($answersByQ[$qId]['is_correct'] ?? false),
                    'options'     => array_map(static function ($opt) use ($selectedIds) {
                        return [
                            'body'    => $opt['body'],
                            'correct' => (bool) $opt['is_correct'],
                            'chosen'  => in_array((int) $opt['id'], array_map('intval', $selectedIds), true),
                        ];
                    }, $options),
                ];
            }
        }

        $candName = trim(($inv['first_name'] ?? '') . ' ' . ($inv['last_name'] ?? '')) ?: ($inv['seeker_full_name'] ?? 'Candidate');

        return $this->response->setJSON([
            'success'   => true,
            'candidate' => [
                'name'               => $candName,
                'email'              => $inv['applicant_email'] ?? '',
                'avatar'             => $inv['seeker_avatar'] ?? null,
                'application_id'     => $inv['app_id'] ?? 0,
                'application_status' => $inv['application_status'] ?? '',
                'job_title'          => $inv['job_title'] ?? '',
                'test_title'         => $inv['test_title'] ?? '',
            ],
            'attempt'   => [
                'id'              => $inv['attempt_id'],
                'status'          => $inv['attempt_status'] ?? 'pending',
                'score_pct'       => $inv['score_pct'] !== null ? (float) $inv['score_pct'] : null,
                'passed'          => (bool) ($inv['passed'] ?? false),
                'pass_threshold'  => (int) ($inv['pass_threshold'] ?? 50),
                'num_total'       => (int) ($inv['num_total'] ?? $inv['num_questions'] ?? 0),
                'num_correct'     => (int) ($inv['num_correct'] ?? 0),
                'started_at'      => $inv['started_at'] ? date('M j, Y g:ia', strtotime($inv['started_at'])) : null,
                'submitted_at'    => $inv['submitted_at'] ? date('M j, Y g:ia', strtotime($inv['submitted_at'])) : null,
            ],
            'breakdown' => $breakdown
        ]);
    }

    /**
     * Advance candidate hiring stage from aptitude test dashboard
     */
    public function advanceCandidateStage()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $user = $this->auth->user();
        $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer profile not found']);
        }

        $appId = (int) $this->request->getPost('application_id');
        $stage = trim(strtolower($this->request->getPost('stage') ?? ''));
        $notes = trim((string) $this->request->getPost('notes'));

        $allowedStages = ['shortlisted', 'interview', 'hired', 'rejected'];
        if (!in_array($stage, $allowedStages, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid hiring stage specified']);
        }

        $appModel = model(\App\Models\JobApplicationModel::class);
        $app = $appModel->find($appId);
        if (!$app) {
            return $this->response->setJSON(['success' => false, 'message' => 'Application record not found']);
        }

        $job = model(\App\Models\JobModel::class)->find($app->job_id);
        if (!$job || $job->employer_id != $employer->id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $updateData = [
            'status'      => $stage,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ];
        if (!empty($notes)) {
            $updateData['status_message'] = $notes;
        }
        $appModel->update($appId, $updateData);

        // Add application feedback note
        $noteModel = model(\App\Models\ApplicationNoteModel::class);
        $stageLabels = [
            'shortlisted' => 'Shortlisted',
            'interview'   => 'Advanced to Interview Stage',
            'hired'       => 'Marked as Hired',
            'rejected'    => 'Marked as Rejected',
        ];
        $stageLabel = $stageLabels[$stage] ?? ucfirst($stage);
        $noteText = "Recruiter updated stage to: {$stageLabel}" . (!empty($notes) ? " - Note: {$notes}" : "");
        $noteModel->addNote($appId, $employer->id, $noteText, $user->id, 'feedback');

        try {
            $emailService = new \App\Services\EmailNotificationService();
            $emailService->sendApplicationStatusEmail(
                $app,
                $stage,
                $job->title,
                $employer->company_name,
                $notes
            );
        } catch (\Throwable $e) {
            log_message('error', 'Status update notification error from advanceCandidateStage: ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Candidate application successfully updated to {$stageLabel}!"
        ]);
    }

    /**
     * Edit job page
     */
    
    public function repostJob($jobId)
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        $jobModel = model(JobModel::class);
        $industryModel = model(IndustryModel::class);
        $categoryModel = model(JobCategoryModel::class);
        $creditService = new \App\Services\CreditService();

        // Get job and verify ownership
        $job = $jobModel->where('id', $jobId)->where('employer_id', $employer->id)->first();

        if (!$job) {
            return redirect()->to('employer/jobs')->with('error', 'Job not found or access denied.');
        }

        // NO 1-hour restriction here because it's a repost.

        // Get available data for form
        $industries = $industryModel->findAll();
        $categories = $categoryModel->findAll();
        $states = model(StateModel::class)->findAll();

        // Get credit info
        $creditBalance = $creditService->getAvailableCredits($user->id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);
        $currentPlan = $creditService->getCurrentPlan($user->id);

        // Check if user can feature jobs
        $canFeature = false;
        if ($hasUnlimitedAccess) {
            $canFeature = true;
        } elseif ($currentPlan && $currentPlan->features) {
            $features = is_string($currentPlan->features) ? json_decode($currentPlan->features, true) : ($currentPlan->features ?? []);
            $canFeature = $features['featured'] ?? false;
        }

        // Check if user can post anonymously
        $canPostAnonymous = false;
        if ($hasUnlimitedAccess) {
            $canPostAnonymous = true;
        } elseif ($currentPlan && $currentPlan->features) {
            $features = is_string($currentPlan->features) ? json_decode($currentPlan->features, true) : ($currentPlan->features ?? []);
            $canPostAnonymous = $features['anonymous'] ?? false;
        }

        $data = [
            'title' => 'Repost Job - ' . $job->title,
            'user' => $user,
            'employer' => $employer,
            'job' => $job,
            'isRepost' => true,
            'industries' => $industries,
            'categories' => $categories,
            'states' => $states,
            'creditBalance' => $creditBalance,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
            'canFeature' => $canFeature,
            'canPostAnonymous' => $canPostAnonymous,
            'questions' => model(\App\Models\JobQuestionModel::class)->where('job_id', $job->id)->findAll(),
            'currentPlan' => $currentPlan
        ];

        return view('employers/post-job', $data);
    }

    public function editJob($jobId)
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        $jobModel = model(JobModel::class);
        $industryModel = model(IndustryModel::class);
        $categoryModel = model(JobCategoryModel::class);
        $creditService = new \App\Services\CreditService();

        // Get job and verify ownership
        $job = $jobModel->where('id', $jobId)->where('employer_id', $employer->id)->first();

        if (!$job) {
            return redirect()->to('employer/jobs')->with('error', 'Job not found or access denied.');
        }

        // Job editing restriction: only allowed within 1 hour of posting
        $createdAtTime = !empty($job->created_at) ? strtotime($job->created_at) : 0;
        if ($createdAtTime > 0 && ($createdAtTime < (time() - 3600))) {
            return redirect()->to('employer/jobs')->with('error', 'Job editing is only permitted within 1 hour of posting. Please contact JobberRecruit Support via WhatsApp (+2349014808902) or email (support@jobberrecruit.com) to request changes to this listing.');
        }

        // Get available data for form
        $industries = $industryModel->findAll();
        $categories = $categoryModel->findAll();
        $states = model(StateModel::class)->findAll();

        // Get credit info
        $creditBalance = $creditService->getAvailableCredits($user->id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);
        $currentPlan = $creditService->getCurrentPlan($user->id);

        // Check if user can feature jobs
        $canFeature = false;
        if ($hasUnlimitedAccess) {
            $canFeature = true;
        } elseif ($currentPlan && $currentPlan->features) {
            $features = is_string($currentPlan->features) ? json_decode($currentPlan->features, true) : ($currentPlan->features ?? []);
            $canFeature = $features['featured'] ?? false;
        }

        // Check if user can post anonymously
        $canPostAnonymous = false;
        if ($hasUnlimitedAccess) {
            $canPostAnonymous = true;
        } elseif ($currentPlan && $currentPlan->features) {
            $features = is_string($currentPlan->features) ? json_decode($currentPlan->features, true) : ($currentPlan->features ?? []);
            $canPostAnonymous = $features['anonymous'] ?? false;
        }

        $data = [
            'title' => 'Edit Job - ' . $job->title,
            'user' => $user,
            'employer' => $employer,
            'job' => $job,
            'industries' => $industries,
            'categories' => $categories,
            'states' => $states,
            'creditBalance' => $creditBalance,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
            'canFeature' => $canFeature,
            'canPostAnonymous' => $canPostAnonymous,
            'currentPlan' => $currentPlan,
        ];

        return view('employers/post-job', $data);
    }

    /**
     * Update job - AJAX handler
     */
    public function updateJob()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $jobId = $this->request->getPost('job_id');
        $jobModel = model(JobModel::class);
        $job = $jobModel->where('id', $jobId)->where('employer_id', $employer->id)->first();

        if (!$job) {
            return $this->response->setJSON(['success' => false, 'message' => 'Job not found or access denied']);
        }

        // Job editing restriction: only allowed within 1 hour of posting
        $createdAtTime = !empty($job->created_at) ? strtotime($job->created_at) : 0;
        if ($createdAtTime > 0 && ($createdAtTime < (time() - 3600))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Job editing is only permitted within 1 hour of posting. Please contact JobberRecruit Support via WhatsApp (+2349014808902) to request changes.'
            ]);
        }

        // Validation rules
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[100]',
            'job_type' => 'required|in_list[full-time,part-time,contract,freelance,internship]',
            'state_id' => 'required|is_natural_no_zero',
            'location_type' => 'required|in_list[hybrid,remote,on-site]',
            'salary_type' => 'required|in_list[fixed,range,negotiable]',
            'salary_period' => 'required|in_list[monthly,yearly,hourly]',
            'industry_id' => 'required|is_natural_no_zero',
            'category_id' => 'required|is_natural_no_zero',
            'education_level' => 'required',
            'experience_level' => 'required',
            'application_method' => 'required|in_list[form,whatsapp,email,external]',
            'application_access' => 'required|in_list[guest,authenticated,general]',
            'accommodation' => 'permit_empty',
            'contact_email' => 'required|valid_email',
        ];

        // Conditional validation for application method
        $method = $this->request->getPost('application_method');
        if ($method === 'whatsapp') {
            $rules['whatsapp_link'] = 'required|valid_url';
        } elseif ($method === 'email') {
            $rules['application_email'] = 'required|valid_email';
        } elseif ($method === 'external') {
            $rules['external_url'] = 'required|valid_url';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Prepare update data
        $postData = $this->request->getPost();

        // Handle salary details
        if ($postData['salary_type'] !== 'negotiable' && !empty($postData['salary'])) {
            $postData['salary_details'] = ucfirst($postData['salary_type']) . ', ' .
                ucfirst($postData['salary_period']) . ': ' .
                $postData['salary'];
        } else {
            $postData['salary_details'] = 'Negotiable';
        }

        // Handle application method fields
        $postData['whatsapp_link'] = $method === 'whatsapp' ? trim($postData['whatsapp_link'] ?? '') : null;
        $postData['application_email'] = $method === 'email' ? trim($postData['application_email'] ?? '') : null;
        $postData['external_url'] = $method === 'external' ? trim($postData['external_url'] ?? '') : null;

        // Handle featured status (only if user can feature)
        $creditService = new \App\Services\CreditService();
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);
        $currentPlan = $creditService->getCurrentPlan($user->id);

        $canFeature = false;
        if ($hasUnlimitedAccess) {
            $canFeature = true;
        } elseif ($currentPlan && $currentPlan->features) {
            $features = is_string($currentPlan->features) ? json_decode($currentPlan->features, true) : ($currentPlan->features ?? []);
            $canFeature = $features['featured'] ?? false;
        }

        if ($canFeature && $this->request->getPost('is_featured')) {
            $postData['is_featured'] = 1;
            if (!$job->featured_until || strtotime($job->featured_until) <= time()) {
                $postData['featured_until'] = date('Y-m-d H:i:s', strtotime('+30 days'));
            }
        } elseif (!$canFeature) {
            $postData['is_featured'] = 0;
            $postData['featured_until'] = null;
        }

        // Handle anonymous posting
        $canPostAnonymous = false;
        if ($hasUnlimitedAccess) {
            $canPostAnonymous = true;
        } elseif ($currentPlan && $currentPlan->features) {
            $features = is_string($currentPlan->features) ? json_decode($currentPlan->features, true) : ($currentPlan->features ?? []);
            $canPostAnonymous = $features['anonymous'] ?? false;
        }

        $postData['is_anonymous'] = ($canPostAnonymous && $this->request->getPost('is_anonymous')) ? 1 : 0;

        // Update the job
        unset($postData['job_id']);

        if ($jobModel->update($jobId, $postData)) {
            // Create notification for job update
            $this->createNotification(
                $employer->id,
                $jobId,
                null,
                'job_updated',
                'Job Updated',
                "Your job '{$postData['title']}' has been updated successfully."
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Job updated successfully!',
                'redirect' => site_url('employer/jobs/view/' . $jobId)
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update job. Please try again.'
            ]);
        }
    }

    /**
     * Feature a job
     */
    public function featureJob($jobId)
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $jobModel = model(JobModel::class);
        $job = $jobModel->where('id', $jobId)->where('employer_id', $employer->id)->first();

        if (!$job) {
            return $this->response->setJSON(['success' => false, 'message' => 'Job not found']);
        }

        $creditService = new \App\Services\CreditService();
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);
        $currentPlan = $creditService->getCurrentPlan($user->id);

        $canFeature = false;
        if ($hasUnlimitedAccess) {
            $canFeature = true;
        } elseif ($currentPlan && $currentPlan->features) {
            $features = is_string($currentPlan->features) ? json_decode($currentPlan->features, true) : ($currentPlan->features ?? []);
            $canFeature = $features['featured'] ?? false;
        }

        if (!$canFeature) {
            return $this->response->setJSON(['success' => false, 'message' => 'Your plan does not support featured jobs']);
        }

        $isCurrentlyFeatured = $job->is_featured && strtotime($job->featured_until) > time();

        if ($isCurrentlyFeatured) {
            // Unfeature the job
            $jobModel->update($jobId, [
                'is_featured' => 0,
                'featured_until' => null
            ]);
            return $this->response->setJSON(['success' => true, 'message' => 'Job removed from featured']);
        } else {
            // Feature the job for 30 days
            $jobModel->update($jobId, [
                'is_featured' => 1,
                'featured_until' => date('Y-m-d H:i:s', strtotime('+30 days'))
            ]);
            return $this->response->setJSON(['success' => true, 'message' => 'Job featured successfully for 30 days']);
        }
    }

    public function promoteJob($jobId)
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $user = $this->auth->user();
        $jobModel = model(JobModel::class);
        $employerModel = model(EmployerModel::class);

        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Employer profile not found.'
            ]);
        }

        $job = $jobModel
            ->where('id', $jobId)
            ->where('employer_id', $employer->id)
            ->first();

        if (!$job) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Job not found or not yours.'
            ]);
        }

        if ($job->is_featured) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This job is already featured.'
            ]);
        }

        $creditService = new \App\Services\CreditService();
        $hasUnlimited = $creditService->hasUnlimitedAccess($user->id);
        $plan = $creditService->getCurrentPlan($user->id);
        $creditBalance = $creditService->getAvailableCredits($user->id);

        $canFeatureFree = false;
        if ($hasUnlimited) {
            $canFeatureFree = true;
        } elseif ($plan && $plan->features) {
            $features = is_string($plan->features) ? json_decode($plan->features, true) : (array)$plan->features;
            $canFeatureFree = !empty($features['featured']);
        }

        $db = db_connect();

        if ($canFeatureFree) {
            // Free promotion via subscription
            try {
                $db->transStart();
                $jobModel->update($jobId, [
                    'is_featured'    => 1,
                    'featured_until' => date('Y-m-d H:i:s', strtotime('+30 days')),
                    'updated_at'     => date('Y-m-d H:i:s')
                ]);
                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Job promoted successfully using your active subscription.'
                ]);
            } catch (\Throwable $e) {
                log_message('error', 'Promote Job Failed: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred while promoting the job.'
                ]);
            }
        } else {
            // Needs to pay via credits
            if ($creditBalance < 5) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You need an active subscription or at least 5 job credits to feature a job.'
                ]);
            }

            try {
                $db->transStart();

                // Use CreditService to deduct exactly 5 credits
                $deduction = $creditService->deductCredits($user->id, 5, 'promote_' . $jobId . '_' . time(), 'Promote Job: ' . $job->title, 'promote_job');

                if (!$deduction['success']) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $deduction['message']
                    ]);
                }

                $jobModel->update($jobId, [
                    'is_featured'    => 1,
                    'featured_until' => date('Y-m-d H:i:s', strtotime('+30 days')),
                    'updated_at'     => date('Y-m-d H:i:s')
                ]);

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Job promoted successfully. 5 credits used.'
                ]);
            } catch (\Throwable $e) {
                log_message('error', 'Promote Job Failed: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred while promoting the job.'
                ]);
            }
        }
    }

    public function stopFeatured($jobId)
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $user = $this->auth->user();
        $jobModel = model(JobModel::class);
        $employer = model(EmployerModel::class)
            ->where('user_id', $user->id)
            ->first();

        $job = $jobModel
            ->where('id', $jobId)
            ->where('employer_id', $employer->id)
            ->first();

        if (! $job || ! $job->is_featured) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Job is not currently featured.'
            ]);
        }

        $jobModel->update($jobId, [
            'is_featured'     => 0,
            'featured_until' => null,
            'updated_at'     => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Job is no longer featured.'
        ]);
    }

    public function toggleAnonymous($jobId)
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $user = $this->auth->user();
        $jobModel = model(JobModel::class);
        $employerModel = model(EmployerModel::class);
        $subscriptionModel = model(UserSubscriptionModel::class);
        $planModel = model(PlanModel::class);

        $employer = $employerModel
            ->where('user_id', $user->id)
            ->first();

        if (!$employer) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Employer profile not found.'
            ]);
        }

        $job = $jobModel
            ->where('id', $jobId)
            ->where('employer_id', $employer->id)
            ->first();

        if (!$job) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Job not found or not yours.'
            ]);
        }

        // Active subscription
        $activeSub = $subscriptionModel
            ->select('user_subscriptions.*, plans.features')
            ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
            ->where('user_subscriptions.is_active', 1)
            ->where('user_subscriptions.user_id', $user->id)
            ->first();

        if ($activeSub && !empty($activeSub['features'])) {
            $planFeatures = planFeatures(json_decode($activeSub['features'], true));
        }

        if (!$activeSub) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You need an active subscription.'
            ]);
        }

        $plan = $planModel->find($activeSub['plan_id']);
        // $features = $planFeatures['features'];

        if (empty($planFeatures['anonymous'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Your plan does not support anonymous jobs.'
            ]);
        }

        $creditService = new JobCreditService();

        try {
            $db = db_connect();
            $db->transStart();

            // CHARGE ONLY WHEN TURNING ON
            if (! $job->is_anonymous) {
                $creditService->deduct($user->id, $jobId, 5.00, 'Anonymous for ' . $job->title);
            }

            $jobModel->update($jobId, [
                'is_anonymous' => ! $job->is_anonymous,
                'updated_at'   => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $job->is_anonymous
                    ? 'Anonymous posting disabled.'
                    : 'Anonymous posting enabled. 5 credits used.'
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Anonymous Toggle Failed: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



    public function deleteJob($id) 
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        $jobModel = model(JobModel::class);
        $job = $jobModel->find($id);
        if (!$job) {
            return redirect()->to('employer/jobs')->with('error', 'Job not found.');
        }
        if ($job->employer_id !== ($employer->id ?? null)) {
            return redirect()->to('employer/jobs')->with('error', 'You do not have permission to delete this job.');
        }
        $jobModel->delete($id);
        
        return redirect()->to('employer/jobs')->with('success', 'Job permanently deleted successfully.');
    }

    /**
     * Close a job listing so it no longer accepts new applications.
     */
    public function closeJob($id)
    {
        $user     = $this->auth->user();
        $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();

        $jobModel = model(JobModel::class);
        $job      = $jobModel->find($id);

        if (! $job || (int) $job->employer_id !== (int) ($employer->id ?? 0)) {
            return redirect()->to('employer/jobs')->with('error', 'Job not found or access denied.');
        }

        $jobModel->update($id, [
            'status'     => 'closed',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('employer/jobs')->with('success', "Job \"{$job->title}\" has been closed. It will no longer accept new applications, but all existing applications remain accessible.");
    }

    /**
     * Pause (close) or reopen a job. Toggles status open <-> closed.
     */
    public function toggleJobStatus($id)
    {
        return $this->closeJob($id);
    }

    public function processRepostJob($jobId)
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $jobModel = model(JobModel::class);
        $job = $jobModel->where('id', $jobId)->where('employer_id', $employer->id)->first();

        if (!$job) {
            return $this->response->setJSON(['success' => false, 'message' => 'Job not found or access denied']);
        }

        // --- Repost Specific Logic: Check credits and charge ---
        $creditService = new \App\Services\CreditService();
        $hasUnlimitedAccess = $this->hasUnlimitedAccess($employer->id);
        $creditBalance = $creditService->getAvailableCredits($user->id);
        $repostFee = 10000.00; // Pay-as-you-go fee

        $db = db_connect();
        $db->transBegin();

        try {
            if (!$hasUnlimitedAccess) {
                if ($creditBalance > 0) {
                    $reference = 'repost_credit_' . $employer->id . '_' . $jobId . '_' . time();
                    $deduct = $creditService->deductCredits($user->id, 1, $reference, 'Job Repost: ' . $job->title, 'post_job');
                    if (!$deduct['success']) {
                        throw new \RuntimeException($deduct['message'] ?? 'Failed to deduct job credit.');
                    }
                } else {
                    $walletService = new \App\Services\WalletService();
                    $reference     = 'repost_wallet_' . $employer->id . '_' . $jobId . '_' . time();
                    $walletService->debit($user->id, $repostFee, 'job_repost', $reference, $jobId, 'Reposted job: ' . $job->title);
                }
            }

            // Validation rules (Same as updateJob)
            $rules = [
                'title' => 'required|min_length[5]|max_length[255]',
                'description' => 'required|min_length[100]',
                'job_type' => 'required|in_list[full-time,part-time,contract,freelance,internship]',
                'state_id' => 'required|is_natural_no_zero',
                'location_type' => 'required|in_list[hybrid,remote,on-site]',
                'salary_type' => 'required|in_list[fixed,range,negotiable]',
                'salary_period' => 'required|in_list[monthly,yearly,hourly]',
                'industry_id' => 'required|is_natural_no_zero',
                'category_id' => 'required|is_natural_no_zero',
                'education_level' => 'required',
                'experience_level' => 'required',
                'application_method' => 'required|in_list[form,whatsapp,email,external]',
                'application_access' => 'required|in_list[guest,authenticated,general]',
                'accommodation' => 'required|in_list[available,not_available]',
                'contact_email' => 'required|valid_email',
            ];

            $method = $this->request->getPost('application_method');
            if ($method === 'email') {
                $rules['application_email'] = 'required|valid_email';
            } elseif ($method === 'external') {
                $rules['external_url'] = 'required|valid_url';
            }

            if (!$this->validate($rules)) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            // Prepare Update Data
            $allowed = ['title','description','job_type','state_id','city','location_type','salary_type','salary_period','salary','salary_max','industry_id','category_id','education_level','experience_level','application_method','application_access','accommodation','contact_email','contact_phone','notification_email','whatsapp_link','application_email','external_url','external_link'];
            $updateData = $this->request->getPost($allowed);

            if ($updateData['salary_type'] !== 'negotiable' && !empty($updateData['salary'])) {
                $updateData['salary_details'] = ucfirst($updateData['salary_type']) . ', ' . ucfirst($updateData['salary_period']) . ': ' . $updateData['salary'];
            } else {
                $updateData['salary_details'] = 'Negotiable';
            }

            $updateData['whatsapp_link'] = $method === 'whatsapp' ? trim($updateData['whatsapp_link'] ?? '') : null;
            $updateData['application_email'] = $method === 'email' ? trim($updateData['application_email'] ?? '') : null;
            $updateData['external_url'] = $method === 'external' ? trim($updateData['external_url'] ?? '') : null;

            // Reset cycles for repost
            $now = date('Y-m-d H:i:s');
            $newDeadline = date('Y-m-d', strtotime('+30 days'));
            $updateData['status'] = 'open';
            $updateData['created_at'] = $now;
            $updateData['closing_date'] = $newDeadline;
            $updateData['deadline'] = $newDeadline;
            $updateData['application_deadline'] = $newDeadline;
            $updateData['updated_at'] = $now;

            $jobModel->update($jobId, $updateData);

            // Log activity
            model(\App\Models\EmployerActivityModel::class)->insert([
                'employer_id' => $employer->id,
                'activity_type' => 'job_reposted',
                'description' => "Reposted job: {$updateData['title']}",
                'ip_address' => $this->request->getIPAddress()
            ]);

            $db->transCommit();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Job reposted successfully.',
                'redirect' => site_url('employer/jobs')
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Job repost failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred during reposting: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export this employer's jobs as a CSV download.
     */
    public function exportJobs()
    {
        $user     = $this->auth->user();
        $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();

        if (! $employer) {
            return redirect()->to('employer/profile')->with('error', 'Complete your company profile first.');
        }

        $jobs = model(JobModel::class)
            ->where('employer_id', $employer->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $rows   = [];
        $rows[] = ['ID', 'Title', 'Type', 'Status', 'Admin Status', 'Views', 'Deadline', 'Created'];
        foreach ($jobs as $j) {
            $rows[] = [
                $j->id,
                $j->title,
                $j->job_type ?? '',
                $j->status ?? '',
                $j->admin_status ?? '',
                (int) ($j->views ?? 0),
                ! empty($j->application_deadline) ? date('Y-m-d', strtotime($j->application_deadline)) : '',
                ! empty($j->created_at) ? date('Y-m-d H:i', strtotime($j->created_at)) : '',
            ];
        }

        $fh = fopen('php://temp', 'r+');
        foreach ($rows as $r) {
            fputcsv($fh, $r);
        }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        $filename = 'jobs-export-' . date('Y-m-d') . '.csv';
        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    public function applications()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);

        // Get employer profile
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')
                ->with('error', 'Please create your company profile first.');
        }

        $jobModel = model(JobModel::class);
        $applicationModel = model(JobApplicationModel::class);
        $creditService = new \App\Services\CreditService();

        // Fetch all jobs created by this employer
        $jobs = $jobModel
            ->where('employer_id', $employer->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Get job IDs to fetch all applications in a single query
        $jobIds = array_column($jobs, 'id');

        $applications = [];
        if (!empty($jobIds)) {
            $applications = $applicationModel
                ->select('job_applications.*, jobs.title as job_title, jobs.id as job_id')
                ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
                ->whereIn('job_applications.job_id', $jobIds)
                ->orderBy('job_applications.created_at', 'DESC')
                ->findAll();
        }

        // Add application counts to each job
        foreach ($jobs as $job) {
            $job->application_count = $applicationModel
                ->where('job_id', $job->id)
                ->countAllResults();
        }

        // Get credit balance and plan info
        $creditBalance = $creditService->getAvailableCredits($user->id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);
        $currentPlan = $creditService->getCurrentPlan($user->id);

        // Get application statistics
        $stats = [
            'total' => count($applications),
            'pending' => 0,
            'reviewed' => 0,
            'shortlisted' => 0,
            'rejected' => 0,
            'hired' => 0,
        ];

        foreach ($applications as $app) {
            $status = strtolower($app->status);
            if (isset($stats[$status])) {
                $stats[$status]++;
            }
        }

        return view('employers/applications', [
            'title' => 'Applications',
            'user' => $user,
            'employer' => $employer,
            'jobs' => $jobs,
            'applications' => $applications,
            'creditBalance' => $creditBalance,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
            'currentPlan' => $currentPlan,
            'stats' => $stats,
        ]);
    }

    /**
     * Delete application
     */
    public function deleteApplication($id)
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $applicationModel = model(JobApplicationModel::class);
        $application = $applicationModel->find($id);

        if (!$application) {
            return $this->response->setJSON(['success' => false, 'message' => 'Application not found']);
        }

        // Verify ownership through job
        $jobModel = model(JobModel::class);
        $job = $jobModel->find($application->job_id);

        if (!$job || $job->employer_id != $employer->id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $db->table('application_notes')->where('application_id', $id)->delete();
        $db->table('application_status_history')->where('application_id', $id)->delete();
        $db->table('job_application_answers')->where('application_id', $id)->delete();
        $db->table('aptitude_test_invitations')->where('application_id', $id)->delete();

        $applicationModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Application deleted successfully'
        ]);
    }

    /**
     * Export applications to CSV
     */
    public function exportApplications()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->back()->with('error', 'Employer not found');
        }

        $jobModel = model(JobModel::class);
        $applicationModel = model(JobApplicationModel::class);

        $jobIds = array_column($jobModel->where('employer_id', $employer->id)->findAll(), 'id');

        $applications = [];
        if (!empty($jobIds)) {
            $applications = $applicationModel
                ->select('job_applications.*, jobs.title as job_title')
                ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
                ->whereIn('job_applications.job_id', $jobIds)
                ->orderBy('job_applications.created_at', 'DESC')
                ->findAll();
        }

        // Set CSV headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="applications_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Applicant Name', 'Job Title', 'Email', 'Phone', 'Applied Date', 'Status', 'Experience', 'Education']);

        foreach ($applications as $app) {
            fputcsv($output, [
                $app->first_name . ' ' . $app->last_name,
                $app->job_title,
                $app->email,
                $app->phone,
                date('M d, Y', strtotime($app->created_at)),
                ucfirst($app->status),
                $app->experience ?? 'N/A',
                $app->education ?? 'N/A'
            ]);
        }

        fclose($output);
        exit();
    }

    /**
     * View single application details
     */
    public function viewApplication($id)
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        $applicationModel = model(JobApplicationModel::class);
        $application = $applicationModel
            ->select('job_applications.*, jobs.title as job_title, jobs.description as job_description')
            ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
            ->where('job_applications.id', $id)
            ->first();

        if (!$application) {
            return redirect()->to('employer/applications')->with('error', 'Application not found');
        }

        // Verify ownership and fetch job details with location state name
        $jobModel = model(JobModel::class);
        $job = $jobModel
            ->select('jobs.*, states.name as state_name')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->where('jobs.id', $application->job_id)
            ->first();

        if (!$job || $job->employer_id != $employer->id) {
            return redirect()->to('employer/applications')->with('error', 'Unauthorized access');
        }

        // First open marks the application as reviewed so the candidate sees it was looked at
        if ($application->status === 'pending') {
            $applicationModel->update($application->id, [
                'status'      => 'reviewed',
                'reviewed_at' => date('Y-m-d H:i:s'),
            ]);
            $application->status      = 'reviewed';
            $application->reviewed_at = date('Y-m-d H:i:s');
        }

        // Get notes for this application
        $noteModel = model(ApplicationNoteModel::class);
        $notes = $noteModel->where('application_id', $id)->orderBy('created_at', 'DESC')->findAll();

        $creditService = new \App\Services\CreditService();
        $creditBalance = $creditService->getAvailableCredits($user->id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);

        // Get pre-screening answers
        $answerModel = model(\App\Models\ApplicationAnswerModel::class);
        $answers = $answerModel->select('application_answers.*, job_questions.question_text as question, job_questions.question_type as type, job_questions.options')
            ->join('job_questions', 'job_questions.id = application_answers.question_id')
            ->where('application_answers.application_id', $id)
            ->findAll();

        $experience = [];
        $education = [];
        $certifications = [];
        $references = [];
        $jobSeeker = null;

        if (!empty($application->job_seeker_id)) {
            $jobSeeker = model(JobSeekerModel::class)
                ->select('job_seekers.*, states.name as state_name')
                ->join('states', 'states.id = job_seekers.state_id', 'left')
                ->where('job_seekers.id', $application->job_seeker_id)
                ->first();
            $experience = model(\App\Models\JobSeekerExperienceModel::class)->forSeeker((int) $application->job_seeker_id);
            $education = model(\App\Models\JobSeekerEducationModel::class)->forSeeker((int) $application->job_seeker_id);
        } elseif (!empty($application->user_id)) {
            $jobSeeker = model(JobSeekerModel::class)
                ->select('job_seekers.*, states.name as state_name')
                ->join('states', 'states.id = job_seekers.state_id', 'left')
                ->where('job_seekers.user_id', $application->user_id)
                ->first();
            if ($jobSeeker) {
                $experience = model(\App\Models\JobSeekerExperienceModel::class)->forSeeker((int) $jobSeeker->id);
                $education = model(\App\Models\JobSeekerEducationModel::class)->forSeeker((int) $jobSeeker->id);
            }
        }

        $refModel = model(\App\Models\ApplicationReferenceModel::class);
        $references = $refModel->where('application_id', $id)->findAll();

        return view('employers/application_view', [
            'title' => 'Application Details',
            'user' => $user,
            'employer' => $employer,
            'job' => $job,
            'application' => $application,
            'jobSeeker' => $jobSeeker,
            'notes' => $notes,
            'answers' => $answers,
            'experience' => $experience,
            'education' => $education,
            'certifications' => $certifications,
            'references' => $references,
            'creditBalance' => $creditBalance,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
        ]);
    }


    public function profile()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->select('employers.*, states.name as location')
            ->join('states', 'states.id = employers.state_id', 'left')
            ->where('user_id', $user->id)
            ->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please create your company profile first.');
        }

        // Get CAC document from employer_documents table
        $documentModel = model(EmployerDocumentModel::class);
        $cacDocument = $documentModel
            ->where('employer_id', $employer->id)
            ->where('document_type', 'cac_certificate')
            ->first();

        // Convert to array for easier access in view
        $cacDocumentArray = $cacDocument ? (array)$cacDocument : null;

        $hasCACDocument = !empty($cacDocument);

        // CAC document is now OPTIONAL - employers can access profile without uploading
        // if (!$hasCACDocument && !in_array($this->request->getUri()->getSegment(2), ['upload-document', 'process-document-upload'])) {
        //     return redirect()->to('employer/profile/upload-document')
        //         ->with('error', 'Please upload your CAC certificate to continue.');
        // }

        $industryModel = model('App\Models\IndustryModel');
        $stateModel = model('App\Models\StateModel');
        $employerIndustryModel = model('App\Models\EmployerIndustryModel');

        // Fetch parent industries with their children
        $industries = $industryModel->where('parent_id', null)->findAll();
        foreach ($industries as &$industry) {
            $industry->children = $industryModel->where('parent_id', $industry->id)->findAll();
        }

        // Fetch all states
        $states = $stateModel->findAll();

        // Fetch the industries the employer belongs to
        $employerIndustryIds = $employerIndustryModel
            ->where('employer_id', $employer->id)
            ->findColumn('industry_id') ?? [];

        // Fetch employer's industries for display
        $employer->industries = $employerIndustryModel
            ->select('industries.*')
            ->join('industries', 'industries.id = employer_industries.industry_id')
            ->where('employer_industries.employer_id', $employer->id)
            ->findAll();

        $subscriptionModel = model(UserSubscriptionModel::class);
        $activeSub = $subscriptionModel
            ->select('user_subscriptions.*, plans.name AS plan_name, plans.features AS plan_features')
            ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
            ->where('user_id', $user->id)
            ->where('user_subscriptions.is_active', 1)
            ->first();

        if ($activeSub && !empty($activeSub['plan_features'])) {
            $activeSub['features_array'] = json_decode($activeSub['plan_features'], true) ?? [];
        } else {
            $activeSub['features_array'] = [];
        }
        $features = planFeatures($activeSub['features_array']);

        // Check for unlimited access
        $hasUnlimitedAccess = $this->hasUnlimitedAccess($employer->id);

        // Get credit balance
        $creditService = new \App\Services\CreditService();
        $creditBalance = $creditService->getAvailableCredits($user->id);

        $canShowTrustBadge = ($features['trust_badge'] ?? false) && !empty($employer->is_verified);

        // Profile completion % — mirrors dashboard()'s calculation
        $totalJobs = model(JobModel::class)->where('employer_id', $employer->id)->countAllResults();
        $profileFields = [
            !empty($employer->company_name),
            !empty($employer->contact_email),
            !empty($employer->company_size),
            !empty($employer->description),
            !empty($employer->website),
            !empty($employer->logo),
            $totalJobs > 0,
            $hasCACDocument,
        ];
        $profileCompletion = (int) round(
            (array_sum(array_map('intval', $profileFields)) / count($profileFields)) * 100
        );

        $data = [
            'title' => 'Company Profile',
            'user' => $user,
            'employer' => $employer,
            'industries' => $industries,
            'states' => $states,
            'employerIndustryIds' => $employerIndustryIds,
            'canShowTrustBadge' => $canShowTrustBadge,
            'activeSubscription' => $activeSub,
            'hasCACDocument' => $hasCACDocument,
            'cacDocument' => $cacDocumentArray,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
            'creditBalance' => $creditBalance,  // ← Add this
            'profileCompletion' => $profileCompletion,
        ];

        return view('employers/profile', $data);
    }

    // public function profile()
    // {
    //     $user = $this->auth->user();
    //     $employerModel = model(EmployerModel::class);
    //     $employer = $employerModel->select('employers.*, states.name as location')
    //         ->join('states', 'states.id = employers.state_id', 'left')
    //         ->where('user_id', $user->id)
    //         ->first();

    //     if (!$employer) {
    //         return redirect()->to('employer/profile/edit')->with('error', 'Please create your company profile first.');
    //     }

    //     // Check if employer has uploaded CAC document
    //     $hasCACDocument = $this->hasUploadedCACDocument($employer->id);

    //     // If no CAC document and not on upload page, redirect
    //     if (!$hasCACDocument && !in_array($this->request->getUri()->getSegment(2), ['upload-document', 'process-document-upload'])) {
    //         return redirect()->to('employer/upload-document')
    //             ->with('error', 'Please upload your CAC certificate to continue.');
    //     }

    //     // Get CAC document info if exists
    //     $cacDocument = null;
    //     $documentModel = model(EmployerDocumentModel::class);
    //     $cacDocument = $documentModel
    //         ->where('employer_id', $employer->id)
    //         ->where('document_type', 'cac_certificate')
    //         ->first();

    //     $industryModel = model('App\Models\IndustryModel');
    //     $stateModel = model('App\Models\StateModel');
    //     $employerIndustryModel = model('App\Models\EmployerIndustryModel');

    //     // Fetch parent industries with their children
    //     $industries = $industryModel->where('parent_id', null)->findAll();
    //     foreach ($industries as &$industry) {
    //         $industry->children = $industryModel->where('parent_id', $industry->id)->findAll();
    //     }

    //     // Fetch all states
    //     $states = $stateModel->findAll();

    //     // Fetch the industries the employer belongs to
    //     $employerIndustryIds = $employerIndustryModel
    //         ->where('employer_id', $employer->id)
    //         ->findColumn('industry_id');

    //     $subscriptionModel = model(UserSubscriptionModel::class);
    //     $activeSub = $subscriptionModel
    //         ->select('user_subscriptions.*, plans.name AS plan_name, plans.features AS plan_features')
    //         ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
    //         ->where('user_id', $user->id)
    //         ->where('user_subscriptions.is_active', 1)
    //         ->first();

    //     if ($activeSub && !empty($activeSub['plan_features'])) {
    //         $activeSub['features_array'] = json_decode($activeSub['plan_features'], true) ?? [];
    //     } else {
    //         $activeSub['features_array'] = [];
    //     }
    //     $features = planFeatures($activeSub['features_array']);

    //     // Check for unlimited access
    //     $hasUnlimitedAccess = $this->hasUnlimitedAccess($employer->id);

    //     $canShowTrustBadge = ($features['trust_badge'] ?? false) && !empty($employer->is_verified);

    //     $data = [
    //         'title' => 'Company Profile',
    //         'user' => $user,
    //         'employer' => $employer,
    //         'industries' => $industries,
    //         'states' => $states,
    //         'employerIndustryIds' => $employerIndustryIds,
    //         'canShowTrustBadge' => $canShowTrustBadge,
    //         'activeSubscription' => $activeSub,
    //         'hasCACDocument' => $hasCACDocument,
    //         'cacDocument' => $cacDocument,
    //         'hasUnlimitedAccess' => $hasUnlimitedAccess,
    //     ];

    //     return view('employers/profile', $data);
    // }

    public function edit_profile()
    {
        if ($this->request->getMethod() === "POST") {
            $user = $this->auth->user();
            $employerModel = model(EmployerModel::class);
            $employer = $employerModel->where('user_id', $user->id)->first();
            $employerIndustryModel = model('App\Models\EmployerIndustryModel');

            if (!$employer) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Employer not found.'
                ]);
            }

            $section = $this->request->getPost('section'); // e.g. 'identity','contact','about','verify','social'

            // Section-aware validation — only require fields relevant to the section being saved
            $allRules = [
                'identity' => [
                    'company_name'  => 'required|min_length[3]',
                    'company_size'  => 'required',
                    'industry_ids[]' => 'required',
                ],
                'contact' => [
                    'state_id'      => 'required|integer',
                    'contact_name'  => 'required|min_length[3]',
                    'contact_email' => 'required|valid_email',
                    'contact_phone' => 'required|min_length[6]',
                ],
                'about'  => [],
                'verify' => [],
                'social' => [],
            ];

            // If section matches a known section, validate only that section's rules.
            // Otherwise (Save All / unknown), validate all required fields together.
            if ($section && isset($allRules[$section])) {
                $rules = $allRules[$section];
            } else {
                $rules = array_merge(...array_values($allRules));
            }

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $website = trim($this->request->getPost('website'));

            if ($website && !preg_match('#^https?://#i', $website)) {
                $website = 'https://' . $website;
            }

            $benefits = (array) $this->request->getPost('benefits');
            $benefits = array_values(array_filter(array_map('trim', $benefits)));

            $data = [
                'company_name'      => $this->request->getPost('company_name'),
                'state_id'          => $this->request->getPost('state_id'),
                'company_size'      => $this->request->getPost('company_size'),
                'website'           => $website ?: null,
                'description'       => $this->request->getPost('description'),
                'contact_name'      => $this->request->getPost('contact_name'),
                'contact_email'     => $this->request->getPost('contact_email'),
                'contact_phone'     => $this->request->getPost('contact_phone'),
                'company_address'   => trim($this->request->getPost('company_address')),
                'tagline'           => trim((string) $this->request->getPost('tagline')) ?: null,
                'company_type'      => $this->request->getPost('company_type') ?: null,
                'founded_year'      => $this->request->getPost('founded_year') ?: null,
                'remote_policy'     => $this->request->getPost('remote_policy') ?: null,
                'whatsapp'          => trim((string) $this->request->getPost('whatsapp')) ?: null,
                'benefits'          => !empty($benefits) ? json_encode($benefits) : null,
                'hiring_process'    => trim((string) $this->request->getPost('hiring_process')) ?: null,
                'rc_number'         => trim((string) $this->request->getPost('rc_number')) ?: null,
                'linkedin'          => trim((string) $this->request->getPost('linkedin')) ?: null,
                'twitter'           => trim((string) $this->request->getPost('twitter')) ?: null,
                'facebook'          => trim((string) $this->request->getPost('facebook')) ?: null,
                'instagram'         => trim((string) $this->request->getPost('instagram')) ?: null,
            ];

            // === Handle Logo Upload Only ===
            helper(['filesystem', 'form']);

            // Upload directory
            $uploadPath = 'uploads/employers/' . $employer->id . '/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            // Logo upload
            $logoFile = $this->request->getFile('logo');
            if ($logoFile && $logoFile->isValid()) {
                if ($employer->logo && file_exists($employer->logo)) {
                    unlink($employer->logo);
                }
                $newLogoName = $logoFile->getRandomName();
                $logoFile->move($uploadPath, $newLogoName);
                $data['logo'] = 'uploads/employers/' . $employer->id . '/' . $newLogoName;
            } elseif ($this->request->getPost('remove_logo')) {
                if ($employer->logo && file_exists($employer->logo)) {
                    unlink($employer->logo);
                }
                $data['logo'] = null;
            }

            // REMOVED: verification_doc handling entirely

            $db = \Config\Database::connect();
            $db->transStart();

            // === Update Employer record ===
            $employerModel->update($employer->id, $data);

            // === Update Industry relationships ===
            $industryIds = $this->request->getPost('industry_ids[]') ?? [];
            $employerIndustryModel->where('employer_id', $employer->id)->delete();
            foreach ($industryIds as $industryId) {
                $employerIndustryModel->insert([
                    'employer_id' => $employer->id,
                    'industry_id' => $industryId
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update company profile. Database error.'
                ])->setStatusCode(500);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Company profile updated successfully.'
            ])->setStatusCode(200);
        }

        // GET request - show edit form
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please create your company profile first.');
        }

        $industryModel = model('App\Models\IndustryModel');
        $stateModel = model('App\Models\StateModel');
        $employerIndustryModel = model('App\Models\EmployerIndustryModel');

        // Fetch parent industries and children
        $industries = $industryModel->where('parent_id', null)->findAll();
        foreach ($industries as &$industry) {
            $industry->children = $industryModel->where('parent_id', $industry->id)->findAll();
        }

        // Fetch all states
        $states = $stateModel->findAll();

        // Employer's linked industries
        $employerIndustryIds = $employerIndustryModel
            ->where('employer_id', $employer->id)
            ->findColumn('industry_id') ?? [];

        $data = [
            'title' => 'Update Company Profile',
            'user' => $user,
            'employer' => $employer,
            'industries' => $industries,
            'states' => $states,
            'employerIndustryIds' => $employerIndustryIds,
        ];

        return view('employers/profile-edit', $data);
    }

    // public function edit_profile()
    // {
    //     if ($this->request->getMethod() === "POST") {
    //         $user = $this->auth->user();
    //         $employerModel = model(EmployerModel::class);
    //         $employer = $employerModel->where('user_id', $user->id)->first();
    //         $employerIndustryModel = model('App\Models\EmployerIndustryModel');

    //         if (!$employer) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'message' => 'Employer not found.'
    //             ]);
    //         }

    //         $rules = [
    //             'company_name'      => 'required|min_length[3]',
    //             'state_id'          => 'required|integer',
    //             'company_size'      => 'required',
    //             'contact_name'      => 'required|min_length[3]',
    //             'contact_email'     => 'required|valid_email',
    //             'contact_phone'     => 'required|min_length[6]',
    //             'industry_ids'      => 'required',
    //         ];

    //         if (!$this->validate($rules)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'errors' => $this->validator->getErrors()
    //             ]);
    //         }

    //         $website = trim($this->request->getPost('website'));

    //         if ($website && !preg_match('#^https?://#i', $website)) {
    //             $website = 'https://' . $website;
    //         }

    //         $data = [
    //             'company_name'      => $this->request->getPost('company_name'),
    //             'state_id'          => $this->request->getPost('state_id'),
    //             'company_size'      => $this->request->getPost('company_size'),
    //             'website'           => $website ?: null,
    //             'description'       => $this->request->getPost('description'),
    //             'contact_name'      => $this->request->getPost('contact_name'),
    //             'contact_email'     => $this->request->getPost('contact_email'),
    //             'contact_phone'     => $this->request->getPost('contact_phone'),
    //             'company_address'   => trim($this->request->getPost('company_address')),
    //         ];

    //         // === Handle file uploads ===
    //         helper(['filesystem', 'form']);

    //         // Upload directory
    //         $uploadPath = 'uploads/employers/' . $employer->id . '/';
    //         if (!is_dir($uploadPath)) {
    //             mkdir($uploadPath, 0775, true);
    //         }

    //         // Logo upload (keep this)
    //         $logoFile = $this->request->getFile('logo');
    //         if ($logoFile && $logoFile->isValid()) {
    //             if ($employer->logo && file_exists($employer->logo)) {
    //                 unlink($employer->logo);
    //             }
    //             $newLogoName = $logoFile->getRandomName();
    //             $logoFile->move($uploadPath, $newLogoName);
    //             $data['logo'] = 'uploads/employers/' . $employer->id . '/' . $newLogoName;
    //         } elseif ($this->request->getPost('remove_logo')) {
    //             if ($employer->logo && file_exists($employer->logo)) {
    //                 unlink($employer->logo);
    //             }
    //             $data['logo'] = null;
    //         }

    //         // REMOVED: verification_doc upload from here - moved to separate CAC upload

    //         // === Update Employer record ===
    //         $employerModel->update($employer->id, $data);

    //         // === Update Industry relationships ===
    //         $industryIds = $this->request->getVar('industry_ids') ?? [];
    //         $employerIndustryModel->where('employer_id', $employer->id)->delete();
    //         foreach ($industryIds as $industryId) {
    //             $employerIndustryModel->insert([
    //                 'employer_id' => $employer->id,
    //                 'industry_id' => $industryId
    //             ]);
    //         }

    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'message' => 'Company profile updated successfully.'
    //         ])->setStatusCode(200);
    //     }

    //     // GET request - show edit form
    //     $user = $this->auth->user();
    //     $employerModel = model(EmployerModel::class);
    //     $employer = $employerModel->where('user_id', $user->id)->first();

    //     if (!$employer) {
    //         return redirect()->to('employer/profile/edit')->with('error', 'Please create your company profile first.');
    //     }

    //     $industryModel = model('App\Models\IndustryModel');
    //     $stateModel = model('App\Models\StateModel');
    //     $employerIndustryModel = model('App\Models\EmployerIndustryModel');

    //     // Fetch parent industries and children
    //     $industries = $industryModel->where('parent_id', null)->findAll();
    //     foreach ($industries as &$industry) {
    //         $industry->children = $industryModel->where('parent_id', $industry->id)->findAll();
    //     }

    //     // Fetch all states
    //     $states = $stateModel->findAll();

    //     // Employer's linked industries
    //     $employerIndustryIds = $employerIndustryModel
    //         ->where('employer_id', $employer->id)
    //         ->findColumn('industry_id');

    //     $data = [
    //         'title' => 'Update Company Profile',
    //         'user' => $user,
    //         'employer' => $employer,
    //         'industries' => $industries,
    //         'states' => $states,
    //         'employerIndustryIds' => $employerIndustryIds,
    //     ];

    //     return view('employers/profile-edit', $data);
    // }

    /**
     * Show document upload page
     */
    public function upload_document()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile');
        }

        // Check if already has document
        $documentModel = model(EmployerDocumentModel::class);
        $existingDoc = $documentModel
            ->where('employer_id', $employer->id)
            ->where('document_type', 'cac_certificate')
            ->first();

        $hasDocument = !empty($existingDoc);

        $data = [
            'title' => 'Upload CAC Certificate',
            'user' => $user,
            'employer' => $employer,
            'existingDoc' => $existingDoc,
            'hasDocument' => $hasDocument
        ];

        return view('employers/upload_document', $data);
    }

    /**
     * Process document upload
     */
    public function process_document_upload()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->back()->with('error', 'Employer not found');
        }

        if ($this->request->getMethod() === 'POST') {

            $rules = [
                'cac_document' => [
                    'rules' => 'uploaded[cac_document]|max_size[cac_document,5120]|ext_in[cac_document,pdf,jpg,jpeg,png]|mime_in[cac_document,application/pdf,image/jpeg,image/png]',
                    'errors' => [
                        'uploaded' => 'Please upload a file',
                        'max_size' => 'File must not exceed 5MB',
                        'ext_in' => 'Only PDF, JPG, JPEG, PNG allowed',
                        'mime_in' => 'Invalid file type'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()
                    ->with('error', implode(', ', $this->validator->getErrors()))
                    ->withInput();
            }

            $file = $this->request->getFile('cac_document');

            if (!$file || !$file->isValid() || $file->hasMoved()) {
                return redirect()->back()
                    ->with('error', 'Invalid file upload')
                    ->withInput();
            }

            // ✅ ALWAYS extract metadata BEFORE move
            $clientName = $file->getClientName();
            $fileSize   = $file->getSize();
            $mimeType   = $file->getMimeType();
            $extension  = $file->getExtension();

            // Generate unique filename
            $newName = 'cac_' . $employer->id . '_' . time() . '.' . $extension;

            // ✅ Use WRITEPATH (safer than public folder)
            $path = 'uploads/employers/' . $employer->id . '/documents/';

            // Ensure directory exists
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            // Move file
            $file->move($path, $newName);

            // Full stored path
            $storedPath = $path . $newName;

            $documentModel = model(EmployerDocumentModel::class);

            $db = \Config\Database::connect();
            $db->transStart();

            $existingDoc = $documentModel
                ->where('employer_id', $employer->id)
                ->where('document_type', 'cac_certificate')
                ->first();

            if ($existingDoc) {

                // Delete old file safely
                if (!empty($existingDoc->file_path) && file_exists($existingDoc->file_path)) {
                    unlink($existingDoc->file_path);
                }

                $documentModel->update($existingDoc->id, [
                    'file_path'   => $storedPath,
                    'file_name'   => $clientName,
                    'file_size'   => $fileSize,
                    'mime_type'   => $mimeType,
                    'status'      => 'pending',
                    'uploaded_at' => date('Y-m-d H:i:s')
                ]);
            } else {

                $documentModel->insert([
                    'employer_id'   => $employer->id,
                    'document_type' => 'cac_certificate',
                    'file_path'     => $storedPath,
                    'file_name'     => $clientName,
                    'file_size'     => $fileSize,
                    'mime_type'     => $mimeType,
                    'status'        => 'pending',
                    'uploaded_at'   => date('Y-m-d H:i:s')
                ]);
            }

            // Update employer verification status
            $employerModel->update($employer->id, [
                'verification_status' => 'pending',
                'is_verified'         => 0
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Database write error while saving upload record');
            }

            // Send confirmation email to employer and alert to admin
            try {
                $emailService = new \App\Services\EmailNotificationService();
                $emailService->sendEmployerVerificationSubmittedEmail($employer, $user);
                $emailService->sendEmployerVerificationAdminAlertEmail($employer, $user);
            } catch (\Throwable $e) {
                log_message('error', 'Verification document upload email notification error: ' . $e->getMessage());
            }

            return redirect()->to('employer/profile')
                ->with('success', 'CAC certificate uploaded successfully. It will be reviewed by our team.');
        }

        return redirect()->back()->with('error', 'File upload failed');
    }

    /**
     * Display pricing page and ensure user has a default free plan if none assigned.
     */
    /**
     * Display pricing page (Plans + Bundles)
     * Ensure user has starter (free) access via credits, not fake subscriptions
     */
    // public function pricing()
    // {
    //     $user = $this->auth->user();
    //     if (!$user) {
    //         return redirect()->to('/login');
    //     }

    //     $employerModel     = model(EmployerModel::class);
    //     $planModel         = model(PlanModel::class);
    //     $subscriptionModel = model(UserSubscriptionModel::class);
    //     $bundleModel       = model(PlanBundleModel::class);
    //     $creditService     = new \App\Services\CreditService();

    //     $employer = $employerModel->where('user_id', $user->id)->first();

    //     if (!$employer) {
    //         return redirect()->to('employer/profile/edit')
    //             ->with('error', 'Please complete your company profile first.');
    //     }

    //     // Active Subscription
    //     $userSubscription = $subscriptionModel
    //         ->where('user_id', $user->id)
    //         ->where('is_active', 1)
    //         ->where('ends_at >', date('Y-m-d H:i:s'))
    //         ->first();

    //     $currentPlan = $userSubscription
    //         ? $planModel->find($userSubscription->plan_id)
    //         : null;

    //     // All Plans (Starter + Business Pro)
    //     $plans = $planModel
    //         ->whereIn('plan_type', ['starter', 'subscription'])
    //         ->where('is_active', 1)
    //         ->orderBy('price', 'ASC')
    //         ->findAll();

    //     // All Active Bundles
    //     $bundles = $bundleModel
    //         ->where('is_active', 1)
    //         ->orderBy('job_credits', 'ASC')
    //         ->findAll();

    //     // Current Credit Balance
    //     $creditBalance = $creditService->getAvailableCredits($user->id);

    //     // Auto-give Starter credit (one-time only)
    //     if ($creditBalance === 0 && (!$userSubscription)) {
    //         $starter = $planModel->where('code', 'starter')->first();
    //         if ($starter) {
    //             $creditService->addCredits(
    //                 userId: $user->id,
    //                 credits: 1,
    //                 source: 'starter',
    //                 referenceId: $starter->id
    //             );
    //             $creditBalance = 1;
    //         }
    //     }

    //     return view('employers/pricing', [
    //         'title'            => 'Pricing & Plans',
    //         'user'             => $user,
    //         'employer'         => $employer,
    //         'plans'            => $plans,
    //         'bundles'          => $bundles,
    //         'currentPlan'      => $currentPlan,
    //         'userSubscription' => $userSubscription,
    //         'creditBalance'    => $creditBalance,
    //     ]);
    // }

    public function pricing()
    {
        $user = $this->auth->user();
        if (!$user) return redirect()->to('/login');

        $employerModel     = model(EmployerModel::class);
        $planModel         = model(PlanModel::class);
        $subscriptionModel = model(UserSubscriptionModel::class);
        $bundleModel       = model(PlanBundleModel::class);
        $creditService     = new \App\Services\CreditService();
        $subService        = new \App\Services\SubscriptionService();

        $employer = $employerModel->where('user_id', $user->id)->first();
        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        // Active Subscription Details with Proration Metrics
        $activeSubDetails = $subService->getActiveSubscriptionDetails($user->id);

        $userSubscription = $activeSubDetails['has_active'] ? $activeSubDetails['subscription'] : null;
        $currentPlan      = $activeSubDetails['has_active'] ? $activeSubDetails['plan'] : null;

        // Get the single subscription plan
        $subscriptionPlan = $planModel
            ->where('plan_type', 'subscription')
            ->where('is_active', 1)
            ->first();

        $pricingTiers = [];
        if ($subscriptionPlan && $subscriptionPlan->pricing_tiers) {
            $pricingTiers = is_string($subscriptionPlan->pricing_tiers)
                ? json_decode($subscriptionPlan->pricing_tiers, true)
                : $subscriptionPlan->pricing_tiers;
        }

        // Default initial proration info for 1 month selection
        $initialProration = [];
        if ($subscriptionPlan) {
            $initialProration = $subService->calculateUpgradeProration($user->id, $subscriptionPlan->id, 1);
        }

        $bundles = $bundleModel
            ->where('is_active', 1)
            ->orderBy('job_credits', 'ASC')
            ->findAll();

        $creditBalance = $creditService->getAvailableCredits($user->id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);

        $wallet = (new \App\Services\WalletService())->getOrCreateWallet($user->id);
        $walletBalance = (float) ($wallet->balance ?? 0.0);

        return view('employers/pricing', [
            'title'              => 'Pricing & Plans',
            'user'               => $user,
            'employer'           => $employer,
            'currentPlan'        => $currentPlan,
            'userSubscription'   => $userSubscription,
            'activeSubDetails'   => $activeSubDetails,
            'initialProration'   => $initialProration,
            'bundles'            => $bundles,
            'creditBalance'      => $creditBalance,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
            'pricingTiers'       => $pricingTiers,
            'subscriptionPlan'   => $subscriptionPlan,
            'walletBalance'      => $walletBalance,
        ]);
    }

    /**
     * AJAX Endpoint to calculate upgrade proration in real-time
     */
    public function calculateProrationAjax()
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $payload = $this->request->getJSON(true) ?: $this->request->getPost();
        $months  = (int) ($payload['duration_months'] ?? 1);

        $planModel        = model(PlanModel::class);
        $subscriptionPlan = $planModel
            ->where('plan_type', 'subscription')
            ->where('is_active', 1)
            ->first();

        if (!$subscriptionPlan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Subscription plan not found']);
        }

        $subService = new \App\Services\SubscriptionService();
        $proration  = $subService->calculateUpgradeProration($user->id, (int)$subscriptionPlan->id, $months);

        return $this->response->setJSON([
            'success'   => true,
            'proration' => $proration
        ]);
    }

    /**
     * Basic Transaction Details Page
     */
    public function transactions()
    {
        $user = $this->auth->user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();
        
        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        // Wallet transactions are the ledger used by the wallet page and are the
        // source of truth for both funding and spending activity.
        $wallet = (new \App\Services\WalletService())->getOrCreateWallet($user->id);
        $walletRows = model(WalletTransactionModel::class)
            ->where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $transactions = array_map(static function ($transaction): array {
            $type = strtolower((string) ($transaction->type ?? ''));
            return [
                'reference'   => (string) ($transaction->reference ?? ''),
                'description' => (string) ($transaction->description ?? ''),
                'created_at'  => (string) ($transaction->created_at ?? ''),
                'amount'      => (float) ($transaction->amount ?? 0),
                'type'        => $type,
                'status'      => 'completed',
            ];
        }, $walletRows);

        $totalSpent = array_reduce($transactions, static function (float $total, array $transaction): float {
            return $total + ($transaction['type'] === 'debit' ? $transaction['amount'] : 0);
        }, 0.0);

        return view('employers/transactions', [
            'title' => 'Transaction History',
            'user' => $user,
            'employer' => $employer,
            'wallet' => $wallet,
            'transactions' => $transactions,
            'totalSpent' => $totalSpent
        ]);
    }

    public function initiate_payment()
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $payload = $this->request->getJSON(true);

        $type        = $payload['type'] ?? null;
        $bundleId    = $payload['bundle_id'] ?? null;
        $months      = (int)($payload['duration_months'] ?? 0);
        $email       = $payload['email'] ?? $user->email;
        $fullName    = $payload['full_name'] ?? '';
        $phone       = $payload['phone'] ?? '';
        $invoiceNo   = $payload['invoice_number'] ?? ('INV-' . time());

        $paymentMethod = $payload['payment_method'] ?? 'card';

        $planModel   = model(PlanModel::class);
        $bundleModel = model(PlanBundleModel::class);

        $amount = 0;
        $description = '';
        $metadata = [];

        // =========================
        // SUBSCRIPTION
        // =========================
        if ($type === 'subscription') {

            $plan = $planModel
                ->where('plan_type', 'subscription')
                ->where('is_active', 1)
                ->first();

            if (!$plan) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Subscription plan not found'
                ]);
            }

            $tiers = is_string($plan->pricing_tiers)
                ? json_decode($plan->pricing_tiers, true)
                : $plan->pricing_tiers;

            if (!$months || !isset($tiers[$months])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid duration selected'
                ]);
            }

            $subService = new \App\Services\SubscriptionService();
            $proration  = $subService->calculateUpgradeProration($user->id, (int)$plan->id, $months);

            $amount = (float) $proration['net_amount_due'];

            $description = "{$plan->name} ({$months} Month" . ($months > 1 ? 's' : '') . ")";
            if ($proration['proration_discount'] > 0) {
                $description .= " (Prorated credit applied: ₦" . number_format($proration['proration_discount'], 2) . ")";
            }

            $metadata = [
                'type'        => 'subscription',
                'plan_id'     => (int)$plan->id,
                'plan_code'   => $plan->code,
                'months'      => $months,
                'proration'   => $proration,
            ];

            // If zero net amount due because unused balance covers the cost:
            if ($amount <= 0.00) {
                $reference = 'sub_upgrade_zero_' . uniqid();
                $db = db_connect();
                $db->transStart();

                try {
                    $employerModel = model(EmployerModel::class);
                    $employer = $employerModel->where('user_id', $user->id)->first();
                    $subscriptionModel = model(UserSubscriptionModel::class);
                    $paymentModel = model(PaymentModel::class);

                    $paymentId = $paymentModel->insert([
                        'user_id'        => $user->id,
                        'employer_id'    => $employer ? $employer->id : null,
                        'reference'      => $reference,
                        'amount'         => 0.00,
                        'status'         => 'paid',
                        'payment_method' => 'proration_credit',
                        'metadata'       => json_encode($metadata),
                        'paid_at'        => date('Y-m-d H:i:s')
                    ]);

                    // Deactivate old active subscriptions
                    $subscriptionModel->where('user_id', $user->id)->set(['is_active' => 0])->update();

                    $startsAt = date('Y-m-d H:i:s');
                    $endsAt = date('Y-m-d H:i:s', strtotime("+{$months} months"));

                    $subscriptionId = $subscriptionModel->insert([
                        'user_id'    => $user->id,
                        'plan_id'    => $plan->id,
                        'starts_at'  => $startsAt,
                        'ends_at'    => $endsAt,
                        'is_active'  => 1,
                        'auto_renew' => 0
                    ]);

                    $creditService = new \App\Services\CreditService();
                    if ($employer && $creditService->planProvidesUnlimitedPosting($plan)) {
                        $employerModel->update($employer->id, [
                            'unlimited_access' => 1,
                            'unlimited_until'  => $endsAt
                        ]);
                    }

                    if ($plan->monthly_job_credits > 0) {
                        $creditService->addCredits(
                            $user->id,
                            $plan->monthly_job_credits * $months,
                            'subscription',
                            (string)$subscriptionId,
                            $endsAt
                        );
                    }

                    try {
                        (new \App\Services\InvoiceService())->sendSubscriptionInvoice(
                            $user->id,
                            $subscriptionId,
                            $paymentId,
                            0.00,
                            $months,
                            $proration
                        );
                    } catch (\Exception $e) {
                        log_message('error', 'Failed to send zero-amount subscription invoice: ' . $e->getMessage());
                    }

                    $db->transComplete();

                    return $this->response->setJSON([
                        'success'     => true,
                        'zero_amount' => true,
                        'message'     => 'Subscription successfully upgraded using your active subscription balance credit!',
                        'redirect'    => base_url('employer/pricing')
                    ]);
                } catch (\Exception $e) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Upgrade failed: ' . $e->getMessage()
                    ]);
                }
            }
        }

        // =========================
        // BUNDLE
        // =========================
        elseif ($type === 'bundle') {

            $bundle = $bundleModel->find($bundleId);

            if (!$bundle || !$bundle->is_active) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid bundle selected'
                ]);
            }

            $amount = (float)$bundle->price;

            $description = "{$bundle->name} ({$bundle->job_credits} Credits)";

            $metadata = [
                'type'        => 'bundle',
                'bundle_id'   => (int)$bundle->id,
                'bundle_code' => $bundle->code,
                'credits'     => $bundle->job_credits,
            ];
        } elseif ($type === 'unlock') {
            $candidateId = $payload['candidate_id'] ?? null;
            if (!$candidateId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Candidate ID missing'
                ]);
            }
            $amount = 5000.00;
            $description = "Candidate Profile Unlock Fee (ID: " . $candidateId . ")";
            $metadata = [
                'type'         => 'unlock',
                'candidate_id' => (int)$candidateId,
            ];
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid purchase type'
            ]);
        }

        // =========================
        // FINAL PAYLOAD
        // =========================

        $reference = 'REF-' . strtoupper(uniqid());

        $paystackKey = env('paystack_public_key') ?: (env('PAYSTACK_PUBLIC_KEY') ?: env('paystack.public_key'));
        if (empty($paystackKey)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paystack API key is not configured. Please add paystack_public_key and paystack_secret_key to your .env file.'
            ]);
        }

        return $this->response->setJSON([
            'success'     => true,
            'paystack'    => $paystackKey,
            'email'       => $email,
            'amount'      => (int)($amount * 100), // Kobo
            'reference'   => $reference,
            'description' => $description,
            'invoice'     => $invoiceNo,
            'method'      => $paymentMethod,

            'metadata' => [
                'custom_fields' => [
                    [
                        'display_name' => 'Full Name',
                        'variable_name' => 'full_name',
                        'value'        => $fullName,
                    ],
                    [
                        'display_name' => 'Phone',
                        'variable_name' => 'phone',
                        'value'        => $phone,
                    ]
                ],
                'app_data' => $metadata
            ]
        ]);
    }

    // public function verify_payment()
    // {
    //     $user = $this->auth->user();
    //     if (!$user) {
    //         return redirect()->to('/login');
    //     }

    //     $reference = $this->request->getGet('reference');
    //     if (!$reference) {
    //         return redirect()->to('employer/pricing')->with('error', 'Invalid payment reference');
    //     }

    //     // Get employer
    //     $employerModel = model(EmployerModel::class);
    //     $employer = $employerModel->where('user_id', $user->id)->first();
    //     if (!$employer) {
    //         return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
    //     }

    //     // Verify payment with Paystack
    //     $paystack = service('paystack');
    //     $verification = $paystack->verifyTransaction($reference);

    //     if (!$verification['status'] || $verification['data']['status'] !== 'success') {
    //         return redirect()->to('employer/pricing')->with('error', 'Payment verification failed. Please contact support.');
    //     }

    //     $amount = $verification['data']['amount'] / 100; // Convert from kobo
    //     $metadata = $verification['data']['metadata']['app_data'] ?? [];

    //     $type = $metadata['type'] ?? null;
    //     $db = db_connect();
    //     $db->transStart();

    //     try {
    //         // Create payment record
    //         $paymentModel = model(PaymentModel::class);
    //         $paymentId = $paymentModel->insert([
    //             'user_id' => $user->id,
    //             'employer_id' => $employer->id,
    //             'reference' => $reference,
    //             'amount' => $amount,
    //             'status' => 'paid',
    //             'payment_method' => $verification['data']['channel'] ?? 'card',
    //             'metadata' => json_encode($metadata),
    //             'paid_at' => date('Y-m-d H:i:s')
    //         ]);

    //         $creditService = new \App\Services\CreditService();

    //         // Handle SUBSCRIPTION
    //         if ($type === 'subscription') {
    //             $planId = $metadata['plan_id'] ?? null;
    //             $months = $metadata['months'] ?? 1;

    //             if (!$planId) {
    //                 throw new \Exception('Plan ID not found in metadata');
    //             }

    //             $planModel = model(PlanModel::class);
    //             $plan = $planModel->find($planId);

    //             if (!$plan) {
    //                 throw new \Exception('Plan not found');
    //             }

    //             $subscriptionModel = model(UserSubscriptionModel::class);

    //             // Deactivate old subscriptions
    //             $subscriptionModel->where('user_id', $user->id)
    //                 ->set(['is_active' => 0])
    //                 ->update();

    //             // Calculate dates
    //             $startsAt = date('Y-m-d H:i:s');
    //             $endsAt = date('Y-m-d H:i:s', strtotime("+{$months} months"));

    //             // Create new subscription
    //             $subscriptionModel->insert([
    //                 'user_id' => $user->id,
    //                 'plan_id' => $planId,
    //                 'starts_at' => $startsAt,
    //                 'ends_at' => $endsAt,
    //                 'is_active' => 1,
    //                 'auto_renew' => 0
    //             ]);

    //             // Add monthly job credits to wallet (if plan has monthly credits)
    //             // if ($plan->monthly_job_credits > 0) {
    //             //     addCredits(int $userId, int $credits, string $source, int $referenceId, ?string $expiresAt = null) // original from credit service
    //             //     $creditService->addCredits(
    //             //         $user->id,
    //             //         $plan->monthly_job_credits * $months, // Multiply by months
    //             //         'subscription',
    //             //         (string)$subscriptionModel->getInsertID(),
    //             //     );
    //             // }

    //             $message = "Successfully subscribed to {$plan->name} for {$months} month(s)!";
    //         }
    //         // Handle BUNDLE
    //         elseif ($type === 'bundle') {
    //             $bundleId = $metadata['bundle_id'] ?? null;
    //             $credits = $metadata['credits'] ?? 0;

    //             if (!$bundleId) {
    //                 throw new \Exception('Bundle ID not found in metadata');
    //             }

    //             $bundleModel = model(PlanBundleModel::class);
    //             $bundle = $bundleModel->find($bundleId);

    //             if (!$bundle) {
    //                 throw new \Exception('Bundle not found');
    //             }

    //             // Add credits to wallet
    //             $creditService->addCredits(
    //                 $user->id,
    //                 $credits,
    //                 'bundle',
    //                 (string)$bundleId,
    //             );

    //             $message = "Successfully purchased {$bundle->name}! {$credits} job credits added to your account.";
    //         } else {
    //             throw new \Exception('Invalid purchase type');
    //         }

    //         $db->transComplete();

    //         // Redirect to dashboard with success message
    //         return redirect()->to('employer/pricing')
    //             ->with('success', $message . ' Thank you for your payment!');
    //     } catch (\Exception $e) {
    //         $db->transRollback();
    //         log_message('error', 'Payment verification failed: ' . $e->getMessage());

    //         return redirect()->to('employer/pricing')
    //             ->with('error', 'Payment verification failed: ' . $e->getMessage());
    //     }
    // }

    public function verify_payment()
    {
        $user = $this->auth->user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $reference = $this->request->getGet('reference');
        if (!$reference) {
            return redirect()->to('employer/pricing')->with('error', 'Invalid payment reference');
        }

        // Get employer
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();
        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        try {
            // Verify payment with Paystack
            $paystack = service('paystack');
            $verification = $paystack->verify($reference);

            if (!$verification['status'] || $verification['data']['status'] !== 'success') {
                log_message('error', 'Payment verification failed for reference: ' . $reference);
                return redirect()->to('employer/pricing')->with('error', 'Payment verification failed. Please contact support.');
            }

            $amount = $verification['data']['amount'] / 100;
            $rawMeta = $verification['data']['metadata'] ?? [];
            $metadata = $rawMeta['app_data'] ?? $rawMeta;

            $type = $metadata['type'] ?? null;
            $db = db_connect();
            $db->transStart();

            try {
                // Create payment record
                $paymentModel = model(PaymentModel::class);
                
                // Check if payment already processed
                $existingPayment = $paymentModel->where('reference', $reference)->first();
                if ($existingPayment && $existingPayment['status'] === 'paid') {
                    $db->transRollback();
                    return redirect()->to('employer/pricing')->with('success', 'Payment already processed successfully.');
                }
                
                $paymentId = $paymentModel->insert([
                    'user_id' => $user->id,
                    'employer_id' => $employer->id,
                    'reference' => $reference,
                    'amount' => $amount,
                    'status' => 'paid',
                    'payment_method' => $verification['data']['channel'] ?? 'card',
                    'metadata' => json_encode($metadata),
                    'paid_at' => date('Y-m-d H:i:s')
                ]);

                $creditService = new \App\Services\CreditService();
                $invoiceService = new \App\Services\InvoiceService();
                $message = '';
                $emailSent = false;

            // Handle SUBSCRIPTION
            if ($type === 'subscription') {
                $planId = $metadata['plan_id'] ?? null;
                $months = $metadata['months'] ?? 1;

                if (!$planId) {
                    throw new \Exception('Plan ID not found in metadata');
                }

                $planModel = model(PlanModel::class);
                $plan = $planModel->find($planId);

                if (!$plan) {
                    throw new \Exception('Plan not found');
                }

                $subscriptionModel = model(UserSubscriptionModel::class);

                // Deactivate old subscriptions
                $subscriptionModel->where('user_id', $user->id)
                    ->set(['is_active' => 0])
                    ->update();

                // Calculate dates
                $startsAt = date('Y-m-d H:i:s');
                $endsAt = date('Y-m-d H:i:s', strtotime("+{$months} months"));

                // Create new subscription
                $subscriptionId = $subscriptionModel->insert([
                    'user_id' => $user->id,
                    'plan_id' => $planId,
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'is_active' => 1,
                    'auto_renew' => 0
                ]);

                // Update employer unlimited access flag if plan provides unlimited posting
                if ($creditService->planProvidesUnlimitedPosting($plan)) {
                    $employerModel->update($employer->id, [
                        'unlimited_access' => 1,
                        'unlimited_until'  => $endsAt
                    ]);
                }

                // Add monthly job credits to wallet
                if ($plan->monthly_job_credits > 0) {
                    $creditService->addCredits(
                        $user->id,
                        $plan->monthly_job_credits * $months,
                        'subscription',
                        (string)$subscriptionId,
                        $endsAt // Credits expire when subscription ends
                    );
                }

                // Send invoice email
                try {
                    $emailSent = $invoiceService->sendSubscriptionInvoice(
                        $user->id,
                        $subscriptionId,
                        $paymentId,
                        $amount,
                        $months,
                        $metadata['proration'] ?? null
                    );
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send subscription invoice: ' . $e->getMessage());
                }

                $message = "Successfully subscribed to {$plan->name} for {$months} month(s)! An invoice has been sent to your email.";
            }
            // Handle BUNDLE
            elseif ($type === 'bundle') {
                $bundleId = $metadata['bundle_id'] ?? null;
                $credits = $metadata['credits'] ?? 0;

                if (!$bundleId) {
                    throw new \Exception('Bundle ID not found in metadata');
                }

                $bundleModel = model(PlanBundleModel::class);
                $bundle = $bundleModel->find($bundleId);

                if (!$bundle) {
                    throw new \Exception('Bundle not found');
                }

                // Add credits to wallet
                $creditService->addCredits(
                    $user->id,
                    $credits,
                    'bundle',
                    (string)$bundleId,
                    null // No expiry for bundles
                );

                // Send invoice email
                try {
                    $emailSent = $invoiceService->sendBundleInvoice(
                        $user->id,
                        $bundleId,
                        $paymentId,
                        $amount,
                        $credits
                    );
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send bundle invoice: ' . $e->getMessage());
                }

                $message = "Successfully purchased {$bundle->name}! {$credits} job credits added to your account. An invoice has been sent to your email.";
            }
            // Handle UNLOCK (candidate profile)
            elseif ($type === 'unlock') {
                $candidateId = $metadata['candidate_id'] ?? null;

                if (!$candidateId) {
                    throw new \Exception('Candidate ID not found in metadata');
                }

                // Updated unlock handling: ensure only one unlock per candidate
                $existingUnlock = $db->table('candidate_unlocks')
                    ->where('employer_id', $employer->id)
                    ->where('job_seeker_id', $candidateId)
                    ->countAllResults();

                if ($existingUnlock) {
                    $message = 'Candidate already unlocked.';
                    $emailSent = true;
                } else {
                    $db->table('candidate_unlocks')->insert([
                        'employer_id'   => $employer->id,
                        'job_seeker_id' => $candidateId,
                        'created_at'    => date('Y-m-d H:i:s'),
                    ]);
                    $message = 'Candidate profile unlocked successfully!';
                    $emailSent = true;
                }
            } else {
                throw new \Exception('Invalid purchase type');
            }

            $db->transComplete();

                // Add flash message about email status
                if (!$emailSent) {
                    session()->setFlashdata('warning', $message . ' (Invoice email could not be sent, but you can download it from your account)');
                } else {
                    session()->setFlashdata('success', $message);
                }

                // For unlocks, redirect back to the candidate profile so they see unlocked data immediately
                if ($type === 'unlock' && !empty($metadata['candidate_id'])) {
                    return redirect()->to('employer/candidates/view/' . (int)$metadata['candidate_id']);
                }

                return redirect()->to('employer/pricing');
            } catch (\Exception $e) {
                $db->transRollback();
                log_message('error', 'Payment processing failed: ' . $e->getMessage());

                return redirect()->to('employer/pricing')
                    ->with('error', 'Payment processing failed: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            log_message('error', 'Payment verification failed: ' . $e->getMessage());

            return redirect()->to('employer/pricing')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function bundles()
    {
        $user = $this->auth->user();
        if (!$user) {
            return redirect()->to('/login');
        }

        // Ensure employer profile exists
        $employer = model(EmployerModel::class)
            ->where('user_id', $user->id)
            ->first();

        if (!$employer) {
            return redirect()
                ->to('employer/profile/edit')
                ->with('error', 'Please create your company profile first.');
        }

        $planModel        = model(PlanModel::class);
        $subscriptionModel = model(UserSubscriptionModel::class);
        $bundleModel      = model(PlanBundleModel::class);
        $creditWalletModel = model(JobCreditWalletModel::class);

        /* -------------------------------------------------
     * ACTIVE SUBSCRIPTION (if any)
     * ------------------------------------------------- */
        $userPlan = $subscriptionModel
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->orderBy('ends_at', 'DESC')
            ->first();

        /* -------------------------------------------------
     * PLANS (Free + Subscriptions only)
     * ------------------------------------------------- */
        $plans = $planModel
            ->whereIn('billing_type', ['free', 'subscription'])
            ->where('is_active', 1)
            ->orderBy('base_price', 'ASC')
            ->findAll();

        // Get the single subscription plan
        $subscriptionPlan = $planModel
            ->where('plan_type', 'subscription')
            ->where('is_active', 1)
            ->first();

        $pricingTiers = [];
        if ($subscriptionPlan && $subscriptionPlan->pricing_tiers) {
            $pricingTiers = is_string($subscriptionPlan->pricing_tiers)
                ? json_decode($subscriptionPlan->pricing_tiers, true)
                : $subscriptionPlan->pricing_tiers;
        }

        /* -------------------------------------------------
     * JOB CREDIT BALANCE (SOURCE OF TRUTH)
     * ------------------------------------------------- */
        $creditBalance = $creditWalletModel
            ->where('user_id', $user->id)
            ->selectSum('credits')
            ->get()
            ->getRow()->credits ?? 0;

        $hasUnlimitedAccess = (new \App\Services\CreditService())->hasUnlimitedAccess($user->id);

        /* -------------------------------------------------
     * ENSURE STARTER ACCESS (ONE-TIME CREDIT)
     * Skip if employer already has unlimited access
     * ------------------------------------------------- */
        $starterPlan = $planModel
            ->where('code', 'starter')
            ->where('billing_type', 'free')
            ->first();

        if (!$hasUnlimitedAccess && $starterPlan && $creditBalance == 0 && !$userPlan) {
            // Give ONE starter credit only once
            $creditWalletModel->insert([
                'user_id' => $user->id,
                'credits' => 1,
                'source'  => 'free'
            ]);

            $creditBalance = 1;
        }

        /* -------------------------------------------------
     * BUNDLES (PAY-AS-YOU-GO)
     * ------------------------------------------------- */
        $bundles = $bundleModel
            ->where('is_active', 1)
            ->orderBy('price', 'ASC')
            ->findAll();

        $bundleHistory = model(JobCreditTransactionModel::class)
            ->where('user_id', $user->id)
            ->where('type', 'credit')
            ->like('description', 'Bundle', 'both')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $recommendedBundle = (new \App\Services\BundleRecommendationService())
            ->recommend($user->id);

        /* -------------------------------------------------
     * PASS TO VIEW
     * ------------------------------------------------- */
        return view('employers/bundles', [
            'title'              => 'Job Bundles',
            'user'               => $user,
            'employer'           => $employer,
            'plans'              => $plans,
            'bundles'            => $bundles,
            'user_plan'          => $userPlan,
            'creditBalance'      => (int) $creditBalance,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
            'bundleHistory'      => $bundleHistory,
            'recommendedBundle'  => $recommendedBundle,
            'subscriptionPlan'   => $subscriptionPlan,
            'pricingTiers'       => $pricingTiers,
        ]);
    }

    public function checkoutBundle($bundleCode)
    {
        $wallet = model(WalletModel::class)
            ->where('user_id', auth()->id())
            ->first();

        if (!$wallet || $wallet->balance <= 0) {
            return $this->buyBundle($bundleCode); // Paystack only
        }

        return $this->buyBundleHybrid($bundleCode); // Wallet-aware
    }

    public function buyBundle(string $bundleCode)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $user = auth()->user();

        $bundle = model(PlanBundleModel::class)
            ->where('slug', $bundleCode)
            ->where('is_active', 1)
            ->first();

        if (!$bundle) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid bundle'
            ]);
        }

        return $this->response->setJSON([
            'success'     => true,
            'paystack'    => true,
            'public_key'  => env('paystack_public_key') ?: (env('PAYSTACK_PUBLIC_KEY') ?: env('paystack.public_key')),
            'email'       => $user->email,
            'amount'      => (int) ($bundle->price * 100),
            'reference'   => 'bundle_' . uniqid(),
            'metadata'    => [
                'type'      => 'bundle',
                'bundle_id' => $bundle->id,
                'user_id'   => $user->id,
                'wallet_used' => 0
            ]
        ]);
    }

    public function buyBundleHybrid($bundleCode)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $user = auth()->user();

        $bundle = model(PlanBundleModel::class)
            ->where('slug', $bundleCode)
            ->where('is_active', 1)
            ->first();

        if (!$bundle) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid bundle'
            ]);
        }

        $wallet = model(WalletModel::class)
            ->where('user_id', $user->id)
            ->first();

        $walletBalance = (float) ($wallet->balance ?? 0);
        $bundlePrice   = (float) $bundle->price;

        // FULL WALLET PAYMENT
        if ($walletBalance >= $bundlePrice) {

            $reference = 'wallet_bundle_' . uniqid();

            (new \App\Services\WalletService())->debit(
                userId: $user->id,
                amount: $bundlePrice,
                source: 'bundle_purchase',
                reference: $reference,
                sourceId: $bundle->id,
                description: 'Bundle purchase'
            );

            (new \App\Services\BundleService())->credit(
                userId: $user->id,
                bundleId: $bundle->id,
                reference: $reference,
                source: 'wallet'
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Bundle purchased using wallet'
            ]);
        }

        // PARTIAL WALLET → PAYSTACK
        $remaining = $bundlePrice - $walletBalance;

        return $this->response->setJSON([
            'success'     => true,
            'paystack'    => true,
            'public_key'  => env('paystack_public_key') ?: (env('PAYSTACK_PUBLIC_KEY') ?: env('paystack.public_key')),
            'email'       => $user->email,
            'amount'      => (int) ($remaining * 100),
            'reference'   => 'bundle_hybrid_' . uniqid(),
            'metadata'    => [
                'type'        => 'bundle',
                'bundle_id'   => $bundle->id,
                'user_id'     => $user->id,
                'wallet_used' => $walletBalance
            ]
        ]);
    }

    public function verifyPaystackBundlePayment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $reference = $this->request->getPost('reference');

        if (!$reference) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing reference'
            ]);
        }

        $paystack = service('paystack');

        $response = $paystack->verifyPayment($reference);

        if (
            empty($response['status']) ||
            $response['status'] !== true ||
            $response['data']['status'] !== 'success'
        ) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment verification failed'
            ]);
        }

        $data = $response['data'];
        $meta = $data['metadata'];

        // Idempotency guard
        $exists = model(WalletTransactionModel::class)
            ->where('reference', $reference)
            ->countAllResults();

        if ($exists > 0) {
            return $this->response->setJSON([
                'success' => true,
                'verified' => true,
                'message' => 'Payment already processed'
            ]);
        }

        // 🔒 Apply wallet + bundle logic
        (new \App\Services\BundleService())->credit(
            userId: $meta['user_id'],
            bundleId: $meta['bundle_id'],
            reference: $reference,
            source: 'paystack'
        );

        $this->triggerEmployerReferralReward((int) $meta['user_id']);

        // If wallet was partially used
        if (!empty($meta['wallet_used']) && $meta['wallet_used'] > 0) {

            $wallet = model(WalletModel::class)
                ->where('user_id', $meta['user_id'])
                ->first();

            (new \App\Services\WalletService())->debit(
                userId: (int) $meta['user_id'],
                amount: (float) $meta['wallet_used'],
                source: 'bundle_purchase',
                reference: 'wallet_part_' . $reference,
                sourceId: (int) $meta['bundle_id'],
                description: 'Partial bundle payment'
            );
        }

        return $this->response->setJSON([
            'success' => true,
            'verified' => true
        ]);
    }

    private function triggerEmployerReferralReward(int $userId)
    {
        $referralService = new \App\Services\ReferralService();
        $referralService->rewardReferrer($userId, 'payment');
    }

    public function checkoutSubscription(int $planId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $user = auth()->user();

        $plan = model(PlanModel::class)
            ->where('id', $planId)
            ->where('billing_type', 'subscription')
            ->where('is_active', 1)
            ->first();

        if (!$plan || !$plan->paystack_plan_code) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid subscription plan'
            ]);
        }

        return $this->response->setJSON([
            'success'    => true,
            'paystack'   => true,
            'public_key' => env('paystack_public_key'),
            'email'      => $user->email,
            'amount'     => (int) ($plan->price * 100),
            'reference'  => 'sub_' . uniqid(),
            'metadata'   => [
                'type'      => 'subscription',
                'user_id'   => $user->id,
                'plan_id'   => $plan->id
            ],
            'plan' => $plan->paystack_plan_code
        ]);
    }

    public function verifyPaystackSubscription()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $reference = $this->request->getPost('reference');

        if (!$reference) {
            return $this->response->setJSON(['success' => false]);
        }

        $paystack = service('paystack');
        $result   = $paystack->verifyPayment($reference);

        if (
            empty($result['status']) ||
            $result['data']['status'] !== 'success'
        ) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment verification failed'
            ]);
        }

        $data = $result['data'];
        $meta = $data['metadata'];

        if ($meta['type'] !== 'subscription') {
            return $this->response->setJSON(['success' => false]);
        }

        $subscriptionModel = model(UserSubscriptionModel::class);

        $db = \Config\Database::connect();
        $db->transStart();

        // Deactivate existing subscriptions
        $subscriptionModel
            ->where('user_id', $meta['user_id'])
            ->set(['is_active' => 0])
            ->update();

        (new \App\Services\SubscriptionService())->creditMonthly(
            userId: (int) $meta['user_id'],
            planId: (int) $meta['plan_id'],
            reference: 'sub_init_' . $reference,
            source: 'subscription'
        );

        // Activate new subscription
        $subscriptionModel->insert([
            'user_id'                     => $meta['user_id'],
            'plan_id'                     => $meta['plan_id'],
            'paystack_subscription_code'  => $data['subscription']['subscription_code'] ?? null,
            'paystack_email_token'        => $data['subscription']['email_token'] ?? null,
            'starts_at'                   => date('Y-m-d H:i:s'),
            'ends_at'                     => date('Y-m-d H:i:s', strtotime('+30 days')),
            'is_active'                   => 1,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Transaction failed during subscription activation.'
            ]);
        }

        // Reward referrer for employer first payment
        $this->triggerEmployerReferralReward((int) $meta['user_id']);

        return $this->response->setJSON([
            'success'  => true,
            'verified' => true
        ]);
    }


    /**
     * Cancel the current paid subscription via Paystack + mark as will_not_renew
     */
    public function cancelSubscription()
    {
        $user = $this->auth->user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $subModel = model(UserSubscriptionModel::class);
        $currentSub = $subModel->where('user_id', $user->id)
            ->where('is_active', 1)
            ->first();

        if ($currentSub) {
            $currentSub = (object) $currentSub;
        }

        if (!$currentSub || empty($currentSub->paystack_subscription_id)) {
            return redirect()->back()->with('error', 'No active paid subscription to cancel.');
        }

        // Call Paystack to disable/cancel subscription
        $cancelled = $this->deactivatePaystackSubscription($currentSub->paystack_subscription_id);

        if (!$cancelled) {
            return redirect()->back()->with('error', 'Failed to cancel subscription with Paystack. Please try again or contact support.');
        }

        // Mark locally as will_not_renew (access continues until ends_at)
        $subModel->update($currentSub->id, [
            'will_not_renew' => 1,
            'updated_at'     => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('employer/pricing')
            ->with('success', 'Your subscription has been cancelled. You will keep access until ' . date('F j, Y', strtotime($currentSub->ends_at)) . '.');
    }

    /**
     * Reactivate a subscription that was previously cancelled (will_not_renew = 1)
     */
    public function reactivateSubscription()
    {
        $user = $this->auth->user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $subModel = model(UserSubscriptionModel::class);
        $currentSub = $subModel
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->first();

        if ($currentSub) {
            $currentSub = (object) $currentSub;
        }

        if (!$currentSub) {
            return redirect()->back()->with('error', 'No active subscription found.');
        }

        if (empty($currentSub->will_not_renew)) {
            return redirect()->back()->with('info', 'Your subscription is already active and will renew automatically.');
        }

        if (empty($currentSub->paystack_subscription_id)) {
            // Safety fallback
            $subModel->update($currentSub->id, [
                'will_not_renew' => 0,
                'updated_at'     => date('Y-m-d H:i:s')
            ]);

            return redirect()->to('employer/pricing')
                ->with('success', 'Subscription reactivated successfully (no recurring billing was active).');
        }

        // Re-enable on Paystack
        $reactivated = $this->enablePaystackSubscription($currentSub->paystack_subscription_id);

        if (!$reactivated) {
            return redirect()->back()->with('error', 'Failed to reactivate subscription with Paystack. Please contact support.');
        }

        // Clear the cancellation flag
        $subModel->update($currentSub->id, [
            'will_not_renew' => 0,
            'updated_at'     => date('Y-m-d H:i:s')
        ]);

        // Fetch plan name and employer/company name
        $planModel = model(SubscriptionPlanModel::class);
        $plan = $planModel->find($currentSub->plan_id);

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        // Send reactivation confirmation email
        $this->sendSubscriptionReactivatedEmail([
            'user'       => $user,
            'employer'   => $employer,
            'plan'       => $plan,
            'endDate'    => $currentSub->ends_at,
        ]);

        return redirect()->to('employer/pricing')
            ->with('success', 'Your subscription has been successfully reactivated! Monthly billing will resume on ' .
                date('F j, Y', strtotime($currentSub->ends_at)) . '.');
    }

    public function processCheckoutAjax()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $user = $this->auth->user();
        if (! $user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Authentication required'
            ]);
        }

        $planId = (int) $this->request->getPost('plan_id');

        $planModel    = model(SubscriptionPlanModel::class);
        $paymentModel = model(PaymentModel::class);

        $plan = $planModel->find($planId);
        if (! $plan) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid plan selected'
            ]);
        }

        // 🔒 Compute amount on server ONLY
        $amount = $plan->price;

        if ($amount <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid payment amount'
            ]);
        }

        $reference = 'PR-' . strtoupper(bin2hex(random_bytes(6)));

        $paymentModel->insert([
            'user_id'   => $user->id,
            'plan_id'   => $plan->id,
            'reference' => $reference,
            'amount'    => $amount,
            'currency'  => 'NGN',
            'status'    => 'pending',
            'ip_address' => $this->request->getIPAddress(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success'   => true,
            'reference' => $reference,
            'plan'   => $plan->paystack_plan_code,
            'amount'    => (int) ($amount * 100), // kobo
            'email'     => $user->getEmail(),
            'publicKey' => env('paystack_public_key'),
        ]);
    }

    /**
     * POST: initialize Paystack transaction and redirect user
     */
    public function verifyAjax()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $reference = $this->request->getPost('reference');
        if (! $reference) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing payment reference'
            ]);
        }

        $paymentModel = model(PaymentModel::class);
        $payment = $paymentModel->where('reference', $reference)->first();

        if (! $payment || $payment['status'] === 'paid') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid or already processed transaction'
            ]);
        }

        $verify = $this->verifyPaystackTransaction($reference);

        log_message('info', 'Paystack verify: ' . json_encode($verify));

        if (
            empty($verify['data']) ||
            $verify['data']['status'] !== 'success'
        ) {
            $paymentModel->update($payment['id'], [
                'status' => 'failed',
                'gateway_response' => json_encode($verify),
            ]);

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment verification failed'
            ]);
        }

        $amountPaid = $verify['data']['amount'] / 100; // In NGN
        $paidAt = $verify['data']['paid_at'] ?? date('Y-m-d H:i:s');

        $paymentModel->update($payment['id'], [
            'status'          => 'paid',
            'amount_paid'     => $amountPaid,
            'channel'         => $verify['data']['channel'] ?? null,
            'gateway_response' => json_encode($verify),
            'paid_at'         => $paidAt,
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        $this->applyPlanToUser(
            $payment['user_id'],
            $payment['plan_id'],
            $this->getPlanDuration($payment['plan_id']),
            $verify['data']['authorization'] ?? null
        );

        // Fetch required data for the invoice email
        $userModel = model(UserModel::class); // Adjust if your model name is different
        $user = $userModel->find($payment['user_id']);
        $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();

        $planModel = model(SubscriptionPlanModel::class); // Adjust if needed
        $plan = $planModel->find($payment['plan_id']);

        if ($user && $plan) {
            $this->sendSubscriptionInvoiceEmail([
                'user'       => $user,
                'email'      => $user->getEmail(),
                'employer'   => $employer,
                'plan'       => $plan,
                'payment'    => $payment,
                'amountPaid' => $amountPaid,
                'reference'  => $reference,
                'paidAt'     => $paidAt,
                'channel'    => $verify['data']['channel'] ?? 'unknown',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Subscription activated successfully'
        ]);
    }

    private function sendSubscriptionInvoiceEmail(array $data)
    {
        $emailService = \Config\Services::email();

        $emailService->setTo($data['email']);
        $emailService->setFrom('billing@jobberrecruit.com', 'JobberRecruit');
        $emailService->setSubject('Your JobberRecruit Subscription Invoice - ' . $data['reference']);

        $viewData = [
            'fullname'    => $data['employer']->company_name ?? $data['user']->username ?? 'Employer',
            'planName'    => $data['plan']->name,
            'amount'      => number_format($data['amountPaid'], 2),
            'reference'   => $data['reference'],
            'paidAt'      => date('F j, Y \a\t g:i A', strtotime($data['paidAt'])),
            'channel'     => ucfirst($data['channel']),
            'companyAddress' => '6 Ojulari Rd, Lekki Penninsula II, 106104, Lagos, Nigeria',
            'supportEmail'   => 'support@jobberrecruit.com',
            'logoUrl'        => base_url('images/logo-white.png'),
        ];

        $message = view('emails/subscription_invoice', $viewData);

        $emailService->setMessage($message);
        $emailService->setMailType('html');

        $emailService->send();

        // Optional: log if email failed
        if (! $emailService->send()) {
            log_message('error', 'Failed to send invoice email to ' . $data['user']->email . ': ' . print_r($emailService->printDebugger(['headers']), true));
        }
    }

    /**
     * Callback endpoint (GET) - Paystack redirects here after payment attempt
     * We verify the transaction using Paystack verify API and finalize subscription.
     */
    public function verify()
    {
        $user = $this->auth->user();
        // Paystack returns ?reference=xxxx
        $reference = $this->request->getGet('reference');
        if (!$reference) {
            return redirect()->to('employers/pricing')->with('error', 'Payment reference missing.');
        }

        $paymentModel = model(PaymentModel::class);
        $payment = $paymentModel->where('reference', $reference)->first();
        if (!$payment) {
            return redirect()->to('subscription/pricing')->with('error', 'Transaction record not found.');
        }

        // Verify with Paystack
        $verify = $this->verifyPaystackTransaction($reference);
        if (!$verify || empty($verify['data'])) {
            // Mark failed
            $paymentModel->update($payment['id'], [
                'status' => 'failed',
                'gateway_response' => json_encode($verify),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return redirect()->to('employers/pricing')->with('error', 'Unable to verify payment. Contact support.');
        }

        $status = $verify['data']['status']; // 'success' on success
        $amountPaidKobo = $verify['data']['amount'] ?? 0;
        $amountPaid = $amountPaidKobo / 100.0;

        if ($status === 'success') {
            // Update payment record
            $paymentModel->update($payment['id'], [
                'status' => 'paid',
                'amount_paid' => $amountPaid,
                'gateway_response' => json_encode($verify),
                'channel' => $verify['data']['channel'] ?? null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Apply plan to user (idempotent)
            $this->applyPlanToUser($payment['user_id'], $payment['plan_id'], $this->getPlanDuration($payment['plan_id']));

            return redirect()->to('subscription/pricing')->with('success', 'Payment successful. Subscription updated.');
        }

        // Other statuses
        $paymentModel->update($payment['id'], [
            'status' => 'failed',
            'gateway_response' => json_encode($verify),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('employers/pricing')->with('error', 'Payment not successful.');
    }

    /**
     * Webhook endpoint - Paystack server-to-server event.
     * Ensure CSRF is disabled for this route and it's reachable publicly.
     */
    public function webhook()
    {
        // Read raw payload and signature
        $raw = file_get_contents('php://input');
        $signature = $this->request->getServer('HTTP_X_PAYSTACK_SIGNATURE') ?? '';

        // Validate signature using HMAC SHA512 with your secret key
        $secret = $this->paystackSecret;
        $hash = hash_hmac('sha512', $raw, $secret);
        if (!hash_equals($hash, $signature)) {
            // Invalid signature
            return $this->response->setStatusCode(400)->setBody('Invalid signature');
        }

        $payload = json_decode($raw, true);
        if (!$payload || !isset($payload['event'])) {
            return $this->response->setStatusCode(400)->setBody('Bad payload');
        }

        $event = $payload['event'];
        $data = $payload['data'] ?? [];

        // Interested events: charge.success, transaction.success (Paystack uses charge.success for card charges)
        if (in_array($event, ['charge.success', 'transaction.success'])) {
            $reference = $data['reference'] ?? null;
            if ($reference) {
                $paymentModel = model(PaymentModel::class);
                $payment = $paymentModel->where('reference', $reference)->first();

                if ($payment) {
                    // Idempotency: only process if not already paid
                    if ($payment['status'] !== 'paid') {
                        $amountPaid = (isset($data['amount']) ? ($data['amount'] / 100.0) : $payment['amount']);
                        $paymentModel->update($payment['id'], [
                            'status' => 'paid',
                            'amount_paid' => $amountPaid,
                            'gateway_response' => json_encode($data),
                            'channel' => $data['channel'] ?? null,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                        // Apply plan to user
                        $this->applyPlanToUser($payment['user_id'], $payment['plan_id'], $this->getPlanDuration($payment['plan_id']));
                    }
                }
            }
        }

        // Respond quickly to acknowledge the webhook
        return $this->response->setStatusCode(200)->setBody('OK');
    }

    /**
     * Initialize Paystack transaction via API
     * @param array $payload ['email','amount','reference','callback_url']
     * @return array|false
     */
    protected function initPaystackTransaction(array $payload)
    {
        $secret = $this->paystackSecret;
        $url = "https://api.paystack.co/transaction/initialize";

        $body = [
            'email' => $payload['email'],
            'amount' => $payload['amount'], // in kobo
            'reference' => $payload['reference'],
            'callback_url' => $payload['callback_url']
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$secret}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $resp = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            log_message('error', 'Paystack init error: ' . $err);
            return false;
        }

        $decoded = json_decode($resp, true);
        return $decoded;
    }

    /**
     * Verify Paystack transaction via API
     */
    protected function verifyPaystackTransaction(string $reference)
    {
        $secret = $this->paystackSecret;
        $url = "https://api.paystack.co/transaction/verify/" . urlencode($reference);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$secret}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $resp = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            log_message('error', 'Paystack verify error: ' . $err);
            return false;
        }

        return json_decode($resp, true);
    }

    // Helper to get plan duration (in days)
    protected function getPlanDuration($planId)
    {
        $planModel = model(SubscriptionPlanModel::class);
        $plan = $planModel->find($planId);
        return $plan ? (int)$plan->duration : 30;
    }

    // keep applyPlanToUser() as implemented earlier
    protected function applyPlanToUser($userId, $planId, $durationDays = 30, $authorization = null)
    {
        $userPlanModel = model(UserSubscriptionModel::class);

        $now = new \DateTime();
        $start = $now->format('Y-m-d H:i:s');
        $end = (clone $now)->modify("+{$durationDays} days")->format('Y-m-d H:i:s');

        // If existing plan is not free, deactivate it on paystack
        $existingPlan = $userPlanModel->where('user_id', $userId)->where('is_active', 1)->first();
        if ($existingPlan) {
            $existingPlan = (object) $existingPlan;
            $planModel = model(SubscriptionPlanModel::class);
            $planDetail = $planModel->find($existingPlan->plan_id);
            if ($planDetail) {
                $planDetail = (object) $planDetail;
                if ($planDetail->slug !== 'free') {
                    $this->deactivatePaystackSubscription($existingPlan->paystack_subscription_id);
                }
            }
        }

        // Deactivate any existing active subscriptions
        $userPlanModel
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->set(['is_active' => 0])
            ->update();

        // Prepare authorization data to store
        $authData = null;
        if (is_array($authorization) && isset($authorization['authorization_code'])) {
            // Store only essential reusable info
            $authData = json_encode([
                'authorization_code' => $authorization['authorization_code'],
                'bin'                => $authorization['bin'] ?? null,
                'last4'              => $authorization['last4'] ?? null,
                'exp_month'          => $authorization['exp_month'] ?? null,
                'exp_year'           => $authorization['exp_year'] ?? null,
                'card_type'          => $authorization['card_type'] ?? null,
                'bank'               => $authorization['bank'] ?? null,
                'country_code'       => $authorization['country_code'] ?? null,
                'brand'              => $authorization['brand'] ?? null,
                'reusable'           => $authorization['reusable'] ?? true,
                'signature'          => $authorization['signature'] ?? null,
            ]);
        } elseif (is_string($authorization)) {
            // In case you sometimes pass just the code directly
            $authData = $authorization;
        }

        // Insert new subscription
        $inserted = $userPlanModel->insert([
            'user_id'       => $userId,
            'plan_id'       => $planId,
            'starts_at'     => $start,
            'ends_at'       => $end,
            'is_active'     => 1,
            'authorization' => $authData,
            'created_at'    => $start,
            'updated_at'    => $start,
        ]);

        if (!$inserted) {
            log_message('error', 'Failed to insert user subscription: ' . json_encode($userPlanModel->errors()));
        }
    }

    /**
     * Deactivate (cancel) a customer's active subscription on Paystack
     *
     * @param string|null $paystackSubscriptionCode The subscription code from Paystack (e.g., "SUB_xxxx")
     * @return bool True if cancelled successfully or no action needed, false on failure
     */
    protected function deactivatePaystackSubscription(?string $paystackSubscriptionCode): bool
    {
        // If no subscription code exists (e.g., free plan or one-time payment), nothing to do
        if (empty($paystackSubscriptionCode)) {
            return true;
        }

        $secret = $this->paystackSecret;
        $url = "https://api.paystack.co/subscription/{$paystackSubscriptionCode}/disable";

        $payload = [
            'code'  => $paystackSubscriptionCode,
            'token' => $this->getCustomerAuthorizationEmailToken($paystackSubscriptionCode), // Optional but recommended
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$secret}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Recommended for production
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', 'Paystack disable subscription cURL error: ' . $error);
            return false;
        }

        $data = json_decode($response, true);

        log_message('info', "Paystack disable subscription response (HTTP {$httpCode}): " . json_encode($data));

        if ($httpCode === 200 && isset($data['status']) && $data['status'] === true) {
            return true;
        }

        // Paystack sometimes returns 200 even if already cancelled
        if (isset($data['message']) && str_contains(strtolower($data['message']), 'already cancelled')) {
            return true;
        }

        log_message('error', 'Failed to disable Paystack subscription: ' . $response);
        return false;
    }

    /**
     * Helper to retrieve the customer's email token needed for disabling subscription
     * This is optional but increases success rate according to Paystack docs
     */
    private function getCustomerAuthorizationEmailToken(string $subscriptionCode): ?string
    {
        // You have two options:
        // 1. Fetch from your DB if you stored customer email along with subscription
        // 2. Or make an API call to get subscription details

        // Option 2: Quick API call (recommended if you don't store email separately)
        $secret = $this->paystackSecret;
        $url = "https://api.paystack.co/subscription/{$subscriptionCode}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$secret}"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $resp = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($resp, true);

        return $data['data']['customer']['email'] ?? null;
    }

    /**
     * Re-enable a previously disabled Paystack subscription
     *
     * @param string $subscriptionCode Paystack subscription code
     * @return bool
     */
    protected function enablePaystackSubscription(string $subscriptionCode): bool
    {
        $secret = $this->paystackSecret; // or env('PAYSTACK_SECRET_KEY')
        $url = "https://api.paystack.co/subscription/enable";

        $payload = [
            'code'  => $subscriptionCode,
            'token' => $this->getCustomerEmailTokenFromSubscription($subscriptionCode), // optional but recommended
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$secret}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', 'Paystack enable subscription cURL error: ' . $error);
            return false;
        }

        $data = json_decode($response, true);

        log_message('info', "Paystack enable subscription response (HTTP {$httpCode}): " . json_encode($data));

        return $httpCode === 200 && ($data['status'] ?? false) === true;
    }

    /**
     * Optional helper: get customer email from subscription (for token)
     */
    private function getCustomerEmailTokenFromSubscription(string $subscriptionCode): ?string
    {
        $secret = $this->paystackSecret;
        $url = "https://api.paystack.co/subscription/{$subscriptionCode}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$secret}"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $resp = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($resp, true);
        return $data['data']['customer']['email'] ?? null;
    }

    /**
     * Send email confirming that the user has reactivated their subscription
     */
    private function sendSubscriptionReactivatedEmail(array $data)
    {
        $emailService = \Config\Services::email();

        $emailService->setTo($data['user']->email);
        $emailService->setFrom('support@jobberrecruit.com', 'JobberRecruit');
        $emailService->setSubject('Your Subscription Has Been Reactivated!');

        $viewData = [
            'fullname'       => $data['employer']->company_name ?? $data['user']->username ?? 'Employer',
            'planName'       => $data['plan']->name ?? 'Your Plan',
            'nextBillingDate' => date('F j, Y', strtotime($data['endDate'])),
            'companyAddress' => '6 Ojulari Rd, Lekki Penninsula II, 106104, Lagos, Nigeria',
            'supportEmail'   => 'support@jobberrecruit.com',
            'logoUrl'        => base_url('images/logo-white.png'),
        ];

        $message = view('emails/subscription_reactivated', $viewData);

        $emailService->setMessage($message);
        $emailService->setMailType('html');

        if (! $emailService->send()) {
            log_message('error', 'Failed to send reactivation email to ' . $data['user']->email . ': ' . print_r($emailService->printDebugger(['headers']), true));
        } else {
            log_message('info', 'Subscription reactivation email sent to ' . $data['user']->email);
        }
    }

    public function security()
    {
        // $candidateModel = model(EmployerModel::class);
        $user = $this->auth->user();

        // Get candidate profile
        $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();

        return view('employers/security/index', [
            'title' => 'Security Settings',
            'user'  => $this->auth->user(),
            'employer' => $employer,
        ]);
    }

    public function changePassword()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $rules = [
            'current_password'     => 'required',
            'new_password'         => 'required|min_length[8]|strong_password',
            'confirm_new_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $user = $this->auth->user();

        // Verify current password using Shield
        $authenticator = auth()->getAuthenticator();
        if (! $authenticator->check([
            'email'    => $user->email,
            'password' => $this->request->getPost('current_password')
        ])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ]);
        }

        // Change password
        $user->fill(['password' => $this->request->getPost('new_password')]);

        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);
        if ($userModel->save($user)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update password. Please try again.'
        ]);
    }

    /**
     * Deactivate employer account (reversible: hides company and active jobs)
     */
    public function deactivateAccount()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/settings')->with('error', 'Employer profile not found.');
        }

        $db = db_connect();
        $db->transBegin();

        try {
            // Mark employer as inactive
            $employerModel->update($employer->id, ['is_active' => 0, 'updated_at' => date('Y-m-d H:i:s')]);

            // Close/pause all open jobs
            $jobModel = model(JobModel::class);
            $jobModel->where('employer_id', $employer->id)
                ->where('status', 'open')
                ->set(['status' => 'closed', 'updated_at' => date('Y-m-d H:i:s')])
                ->update();

            $db->transCommit();

            auth()->logout();
            return redirect()->to('login')->with('success', 'Your employer account has been deactivated. Your public profile and job listings are hidden. You can log in anytime to reactivate your account.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('employer/settings')->with('error', 'Failed to deactivate account: ' . $e->getMessage());
        }
    }

    /**
     * Delete employer account (permanent)
     */
    public function deleteAccount()
    {
        $user = $this->auth->user();
        $password = $this->request->getPost('confirm_password');

        $authenticator = auth()->getAuthenticator();
        if (!$authenticator->check(['email' => $user->email, 'password' => $password])) {
            return redirect()->to('employer/settings')->with('error', 'Incorrect password. Account deletion cancelled.');
        }

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        $db = \Config\Database::connect();
        $safeDelete = function ($table, $col, $val) use ($db) {
            try {
                if ($db->tableExists($table)) {
                    if (is_array($val)) {
                        $db->table($table)->whereIn($col, $val)->delete();
                    } else {
                        $db->table($table)->where($col, $val)->delete();
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'Employer deleteAccount cleanup failed on ' . $table . ': ' . $e->getMessage());
            }
        };

        if ($employer) {
            $jobModel = model(JobModel::class);
            $jobIds = array_column($jobModel->where('employer_id', $employer->id)->findAll(), 'id');

            if (!empty($jobIds)) {
                $appModel = model(JobApplicationModel::class);
                $appIds = array_column($appModel->whereIn('job_id', $jobIds)->findAll(), 'id');
                if (!empty($appIds)) {
                    foreach (['application_notes', 'application_status_history', 'job_application_answers', 'aptitude_test_invitations'] as $t) {
                        try {
                            if ($db->tableExists($t)) {
                                $db->table($t)->whereIn('application_id', $appIds)->delete();
                            }
                        } catch (\Throwable $e) {}
                    }
                    $appModel->whereIn('job_id', $jobIds)->delete();
                }
                $safeDelete('job_clicks', 'job_id', $jobIds);
                $jobModel->where('employer_id', $employer->id)->delete();
            }

            foreach ([
                'employer_documents' => 'employer_id',
                'candidate_unlocks'  => 'employer_id',
                'candidate_alerts'   => 'employer_id',
                'job_notifications' => 'employer_id',
                'conversations'      => 'employer_id',
            ] as $tbl => $col) {
                $safeDelete($tbl, $col, $employer->id);
            }

            $employerModel->delete($employer->id);
        }

        foreach ([
            'wallets'                 => 'user_id',
            'wallet_transactions'     => 'user_id',
            'job_credit_transactions' => 'user_id',
            'user_subscriptions'      => 'user_id',
            'testimonials'            => 'user_id',
            'password_resets'         => 'user_id',
        ] as $tbl => $col) {
            $safeDelete($tbl, $col, $user->id);
        }

        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);
        $userModel->delete($user->id, true);

        auth()->logout();
        return redirect()->to('/')->with('success', 'Your employer account and all associated data have been permanently deleted.');
    }

    /**
     * Send email notification for job posting
     */
    protected function sendJobPostingEmail($employer, $jobTitle, $jobId)
    {
        try {
            $user = $this->auth ? $this->auth->user() : null;
            $job = (object) [
                'id'       => $jobId,
                'title'    => $jobTitle,
                'location' => 'Nigeria',
            ];

            $emailService = new \App\Services\EmailNotificationService();
            return $emailService->sendJobPostingSubmittedEmail($job, $employer, $user ?: (object)['email' => ($employer->contact_email ?? $employer->company_email)]);
        } catch (\Throwable $e) {
            log_message('error', 'Job posting email failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Notifications page
     */
    public function notifications()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        $notificationModel = model(JobNotificationModel::class);

        // Get paginated notifications
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $notifications = $notificationModel->getNotifications($employer->id, $perPage, $offset);
        $unreadCount = $notificationModel->getUnreadCount($employer->id);
        $totalNotifications = $notificationModel->where('employer_id', $employer->id)->countAllResults();

        // Get counts by type
        $typeCounts = $notificationModel->select('type, COUNT(*) as count')
            ->where('employer_id', $employer->id)
            ->groupBy('type')
            ->findAll();

        $typeStats = [];
        foreach ($typeCounts as $stat) {
            $typeStats[$stat['type']] = $stat['count'];
        }

        $creditService = new \App\Services\CreditService();
        $creditBalance = $creditService->getAvailableCredits($user->id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($user->id);

        $data = [
            'title' => 'Notifications',
            'user' => $user,
            'employer' => $employer,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'totalNotifications' => $totalNotifications,
            'typeStats' => $typeStats,
            'alerts' => $this->buildCandidateAlerts($employer->id),
            'categories' => model(JobCategoryModel::class)->orderBy('name')->findAll(),
            'currentPage' => $page,
            'perPage' => $perPage,
            'creditBalance' => $creditBalance,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
        ];

        return view('employers/notifications', $data);
    }

    /**
     * Resolve the current employer record or return null.
     */
    private function currentEmployer()
    {
        $user = $this->auth->user();
        if (!$user) {
            return null;
        }
        return model(EmployerModel::class)->where('user_id', $user->id)->first();
    }

    /**
     * Load an employer's candidate alerts and enrich each with live
     * matching-candidate data for the notifications view.
     */
    private function buildCandidateAlerts(int $employerId): array
    {
        $alerts = model(CandidateAlertModel::class)->forEmployer($employerId);

        foreach ($alerts as &$alert) {
            $criteria = json_decode($alert['criteria'] ?? '', true) ?: [];
            $alert['criteria'] = $criteria;
            $alert['matches']  = $this->matchingCandidates($criteria, 4);
        }
        unset($alert);

        return $alerts;
    }

    /**
     * Find candidates matching an alert's criteria. Returns a shape the
     * notifications view expects (first_name, last_name, title, experience).
     */
    private function matchingCandidates(array $criteria, int $limit = 4): array
    {
        $filters = [];
        if (!empty($criteria['keyword'])) {
            $filters['keyword'] = $criteria['keyword'];
        }
        if (!empty($criteria['experience'])) {
            $filters['experience_years'] = (int) $criteria['experience'];
        }

        // Nothing to match on → no candidates surfaced.
        if (empty($filters)) {
            return [];
        }

        try {
            $rows = model(JobSeekerModel::class)->getCandidates($filters, $limit) ?? [];
        } catch (\Throwable $e) {
            log_message('error', 'Candidate alert match failed: ' . $e->getMessage());
            return [];
        }

        $matches = [];
        foreach ($rows as $row) {
            $fullName = trim($row->full_name ?? '');
            $parts    = $fullName !== '' ? explode(' ', $fullName, 2) : ['Candidate', ''];
            $matches[] = [
                'first_name' => $parts[0] ?? 'Candidate',
                'last_name'  => $parts[1] ?? '',
                'title'      => $row->job_title ?? 'Candidate',
                'experience' => $row->experience_years ?? 0,
            ];
        }

        return $matches;
    }

    /**
     * Create a candidate alert (POST /employer/candidate-alerts).
     */
    public function createCandidateAlert()
    {
        $employer = $this->currentEmployer();
        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        $name = trim((string) $this->request->getPost('name'));
        if ($name === '') {
            return redirect()->back()->with('error', 'Please give your alert a name.');
        }

        $criteria = [
            'keyword'    => trim((string) $this->request->getPost('keyword')),
            'category'   => trim((string) $this->request->getPost('category')),
            'location'   => trim((string) $this->request->getPost('location')),
            'experience' => trim((string) $this->request->getPost('experience')),
            'education'  => trim((string) $this->request->getPost('education')),
        ];
        // Drop empty criteria for a clean stored payload.
        $criteria = array_filter($criteria, static fn ($v) => $v !== '');

        model(CandidateAlertModel::class)->insert([
            'employer_id'  => $employer->id,
            'name'         => $name,
            'criteria'     => json_encode($criteria),
            'frequency'    => 'daily',
            'email_active' => 1,
            'active'       => 1,
        ]);

        return redirect()->to('employer/notifications')->with('success', 'Alert created successfully.');
    }

    /**
     * Update a candidate alert's settings (AJAX).
     */
    public function updateCandidateAlert($id)
    {
        $employer = $this->currentEmployer();
        $alertModel = model(CandidateAlertModel::class);
        $alert = $alertModel->find((int) $id);

        if (!$employer || !$alert || (int) $alert['employer_id'] !== (int) $employer->id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Alert not found.',
                'csrf'    => csrf_hash(),
            ]);
        }

        $frequency = $this->request->getPost('frequency');
        $allowed   = ['instant', 'daily', 'weekly'];

        $alertModel->update((int) $id, [
            'frequency'    => in_array($frequency, $allowed, true) ? $frequency : 'daily',
            'email_active' => $this->request->getPost('email_active') ? 1 : 0,
            'active'       => $this->request->getPost('active') ? 1 : 0,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alert updated.',
            'csrf'    => csrf_hash(),
        ]);
    }

    /**
     * Delete a candidate alert (AJAX).
     */
    public function deleteCandidateAlert($id)
    {
        $employer = $this->currentEmployer();
        $alertModel = model(CandidateAlertModel::class);
        $alert = $alertModel->find((int) $id);

        if (!$employer || !$alert || (int) $alert['employer_id'] !== (int) $employer->id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Alert not found.',
                'csrf'    => csrf_hash(),
            ]);
        }

        $alertModel->delete((int) $id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alert deleted.',
            'csrf'    => csrf_hash(),
        ]);
    }

    /**
     * Mark notification as read (AJAX)
     */
    public function markNotificationRead()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $notificationId = $this->request->getPost('notification_id');
        $notificationModel = model(JobNotificationModel::class);

        if ($notificationModel->markAsRead($notificationId, $employer->id)) {
            $unreadCount = $notificationModel->getUnreadCount($employer->id);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Marked as read',
                'unreadCount' => $unreadCount
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to mark as read']);
    }

    /**
     * Mark all notifications as read (AJAX)
     */
    public function markAllNotificationsRead()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $notificationModel = model(JobNotificationModel::class);

        if ($notificationModel->markAllAsRead($employer->id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'All notifications marked as read',
                'unreadCount' => 0
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to mark all as read']);
    }

    /**
     * Delete notification (AJAX)
     */
    public function deleteNotification()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $notificationId = $this->request->getPost('notification_id');
        $notificationModel = model(JobNotificationModel::class);

        if ($notificationModel->deleteNotification($notificationId, $employer->id)) {
            $unreadCount = $notificationModel->getUnreadCount($employer->id);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification deleted',
                'unreadCount' => $unreadCount
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete notification']);
    }

    /**
     * Candidates Search (Paid Feature)
     */
    public function candidates()
    {
        $jobSeekerModel = model(JobSeekerModel::class);
        $employer = model(EmployerModel::class)->where('user_id', $this->auth->user()->id)->first();
        $filters = [
            'keyword'          => $this->request->getGet('keyword'),
            'state_id'         => $this->request->getGet('state') ?: $this->request->getGet('state_id'),
            'city'             => $this->request->getGet('city'),
            'location'         => (array) $this->request->getGet('location'),
            'skill'            => (array) $this->request->getGet('skill'),
            'activity'         => $this->request->getGet('activity'),
            'employment_type'  => (array) $this->request->getGet('employment_type'),
            'experience_years' => $this->request->getGet('experience') ?: $this->request->getGet('experience_years'),
            'job_title'        => (array) $this->request->getGet('job_title'),
            'availability'     => (array) $this->request->getGet('availability'),
            'education_level'  => (array) $this->request->getGet('education_level'),
            'sort'             => $this->request->getGet('sort') ?: 'best_match',
        ];

        $candidates = $jobSeekerModel->getCandidates($filters, 20);

        $data = [
            'title'      => 'Find Candidates',
            'user'       => $this->auth->user(),
            'employer'   => $employer,
            'candidates' => $candidates,
            'pager'      => $jobSeekerModel->pager,
            'total'      => $jobSeekerModel->pager ? $jobSeekerModel->pager->getTotal() : count($candidates),
            'hasUnlimitedAccess' => $employer ? $this->hasUnlimitedAccess($employer->id) : false,
            'states'             => model(\App\Models\StateModel::class)->orderBy('name', 'ASC')->findAll(),

            // sidebar counts
            'jobTitleCounts'      => $jobSeekerModel->countByJobTitle(),
            'availabilityCounts' => $jobSeekerModel->countByAvailability(),
            'jobTypeCounts'      => $jobSeekerModel->countByEmploymentType(),
            'educationCounts'    => $jobSeekerModel->countByEducation(),
        ];

        if ($this->request->isAJAX()) {
            return view('employers/partials/candidates_results', $data);
        }

        return view('employers/candidates', $data);
    }

    public function viewCandidate(int $id)
    {
        $jobSeekerModel = model(JobSeekerModel::class);
        $candidate = $jobSeekerModel
            ->select('
                job_seekers.*,
                auth_identities.secret as email,
                states.name AS state_name,
            ')
            ->join('states', 'states.id = job_seekers.state_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = job_seekers.user_id', 'left')
            ->where('job_seekers.id', $id)
            ->first();

        if (!$candidate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Candidate not found');
        }

        $employer = model(EmployerModel::class)->where('user_id', $this->auth->user()->id)->first();
        if (!$employer) {
            return redirect()->to('employer/profile')->with('error', 'Complete your company profile to view candidates.');
        }

        // Check if unlocked
        $db = db_connect();
        $isUnlocked = $db->table('candidate_unlocks')
            ->where('employer_id', $employer->id)
            ->where('job_seeker_id', $id)
            ->countAllResults() > 0;

        // If candidate profile visibility is OFF, candidate is not publicly searchable or discoverable
        if (empty($candidate->is_visible) && !$isUnlocked) {
            // Check if candidate actively applied to one of this employer's jobs
            $hasApplied = $db->table('job_applications')
                ->join('jobs', 'jobs.id = job_applications.job_id')
                ->where('jobs.employer_id', $employer->id)
                ->where('job_applications.job_seeker_id', $id)
                ->countAllResults() > 0;

            if (!$hasApplied) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('This candidate profile is private and not currently visible to employers.');
            }
        }

        $creditService = new CreditService();
        $hasUnlimited = $creditService->hasUnlimitedAccess($this->auth->user()->id);

        // Industries
        $industries = model(JobSeekerIndustryModel::class)
            ->select('industries.name')
            ->join('industries', 'industries.id = job_seeker_industries.industry_id')
            ->where('job_seeker_id', $id)
            ->findAll();

        // Wallet balance
        $walletRow = model(WalletModel::class)->where('user_id', $this->auth->user()->id)->first();
        $walletBalance = $walletRow ? (float) $walletRow->balance : 0;

        return view('employers/candidate-detail', [
            'title'      => $candidate->full_name,
            'user'       => $this->auth->user(),
            'employer'   => $employer,
            'candidate'  => $candidate,
            'experience' => model(\App\Models\JobSeekerExperienceModel::class)->forSeeker((int) $id),
            'education'  => model(\App\Models\JobSeekerEducationModel::class)->forSeeker((int) $id),
            'industries' => $industries,
            'isUnlocked' => $isUnlocked || $hasUnlimited,
            'hasUnlimited' => $hasUnlimited,
            'walletBalance' => $walletBalance
        ]);
    }

    public function downloadCv(int $id)
    {
        $candidateId = $id;
        $employer = model(EmployerModel::class)->where('user_id', $this->auth->user()->id)->first();
        if (!$employer) {
            return redirect()->back()->with('error', 'Employer profile not found.');
        }

        $db = db_connect();

        // Check if unlocked
        $isUnlocked = $db->table('candidate_unlocks')
            ->where('employer_id', $employer->id)
            ->where('job_seeker_id', $candidateId)
            ->countAllResults() > 0;

        $creditService = new \App\Services\CreditService();
        $hasUnlimited = $creditService->hasUnlimitedAccess($this->auth->user()->id);

        if (!$isUnlocked && !$hasUnlimited) {
            return redirect()->back()->with('error', 'You must unlock the candidate profile to download the CV.');
        }

        $candidate = model(JobSeekerModel::class)->find($candidateId);
        if (!$candidate || empty($candidate->resume)) {
            return redirect()->back()->with('error', 'This candidate has not uploaded a CV.');
        }

        return $this->response->download($candidate->resume, null);
    }

    public function unlockCandidate()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $candidateId = (int) $this->request->getPost('candidate_id');
        if (!$candidateId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid candidate ID.']);
        }

        $employer = model(EmployerModel::class)->where('user_id', $this->auth->user()->id)->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer profile not found.']);
        }

        $db        = db_connect();
        $unlockFee = 5000.00;

        // ── IDEMPOTENCY GUARD ───────────────────────────────────────────────
        // Check BEFORE touching the wallet — if already unlocked, return
        // success immediately so the UI refreshes without any new charge.
        $alreadyUnlocked = $db->table('candidate_unlocks')
            ->where('employer_id', $employer->id)
            ->where('job_seeker_id', $candidateId)
            ->countAllResults() > 0;

        if ($alreadyUnlocked) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Candidate profile already unlocked!'
            ]);
        }

        // Unlimited plan bypass (PDF Requirement 4.7)
        $hasUnlimitedAccess = $this->hasUnlimitedAccess($employer->id);
        if ($hasUnlimitedAccess) {
            $db->table('candidate_unlocks')->insert([
                'employer_id'   => $employer->id,
                'job_seeker_id' => $candidateId,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Candidate unlocked successfully (included with your Unlimited Plan).'
            ]);
        }

        // Deterministic reference (no time()) so WalletService's own
        // duplicate-reference check also guards against rapid double-clicks.
        $reference     = 'unlock_' . $employer->id . '_' . $candidateId;
        $walletService = new \App\Services\WalletService();

        $db->transBegin();
        try {
            // Debit wallet first
            $walletService->debit(
                $this->auth->user()->id,
                $unlockFee,
                'candidate_unlock',
                $reference,
                $candidateId,
                'Unlocked candidate profile #' . $candidateId
            );

            // Record the unlock — only reached if debit succeeded
            $db->table('candidate_unlocks')->insert([
                'employer_id'   => $employer->id,
                'job_seeker_id' => $candidateId,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Database error recording unlock.');
            }

            $db->transCommit();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Candidate unlocked successfully'
            ]);
        } catch (\RuntimeException $e) {
            $db->transRollback();
            log_message('error', 'Candidate unlock (wallet) failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => str_contains($e->getMessage(), 'Insufficient')
                    ? 'Insufficient wallet balance. Please fund your wallet with ₦' . number_format($unlockFee, 2) . ' to unlock.'
                    : 'Failed to unlock candidate. Please try again.'
            ]);
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Candidate unlock (wallet) error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.'
            ]);
        }
    }


    public function verifyUnlockAjax()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $reference   = $this->request->getPost('reference');
        $candidateId = (int) $this->request->getPost('candidate_id');

        if (! $reference || ! $candidateId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing reference or candidate ID'
            ]);
        }

        $employer = model(EmployerModel::class)->where('user_id', $this->auth->user()->id)->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer profile not found.']);
        }

        $db = db_connect();

        // ── IDEMPOTENCY GUARD ──────────────────────────────────────────────
        // If the unlock record already exists (e.g. user refreshed or retried)
        // return success immediately — never verify/charge a second time.
        $alreadyUnlocked = $db->table('candidate_unlocks')
            ->where('employer_id', $employer->id)
            ->where('job_seeker_id', $candidateId)
            ->countAllResults() > 0;

        if ($alreadyUnlocked) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Candidate already unlocked.'
            ]);
        }

        // ── Also guard against duplicate Paystack references ───────────────
        $paymentModel    = model(PaymentModel::class);
        $referenceExists = $paymentModel->where('reference', $reference)->countAllResults() > 0;
        if ($referenceExists) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Payment already processed.'
            ]);
        }

        // Verify with Paystack
        $verify = $this->verifyPaystackTransaction($reference);

        if (empty($verify['data']) || $verify['data']['status'] !== 'success') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment verification failed. Please contact support if money was deducted.'
            ]);
        }

        // ── Atomic: record unlock + payment in one transaction ─────────────
        $db->transBegin();
        try {
            $db->table('candidate_unlocks')->insert([
                'employer_id'   => $employer->id,
                'job_seeker_id' => $candidateId,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            $paymentModel->insert([
                'user_id'        => $this->auth->user()->id,
                'employer_id'    => $employer->id,
                'reference'      => $reference,
                'amount'         => $verify['data']['amount'] / 100,
                'status'         => 'paid',
                'payment_method' => $verify['data']['channel'] ?? 'card',
                'metadata'       => json_encode([
                    'type'             => 'unlock',
                    'candidate_id'     => $candidateId,
                    'gateway_response' => $verify,
                ]),
                'paid_at'        => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Database error recording Paystack unlock.');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'verifyUnlockAjax DB error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment received but we could not record your unlock. Please contact support with reference: ' . $reference
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Candidate unlocked successfully'
        ]);
    }

    /**
     * GDPR: Export all employer data
     */
    public function exportData()
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        $data = [
            'account' => [
                'email' => $user->email,
                'username' => $user->username ?? '',
                'created_at' => $user->created_at ?? '',
            ],
            'company' => $employer ? [
                'company_name' => $employer->company_name ?? '',
                'contact_name' => $employer->contact_name ?? '',
                'contact_email' => $employer->contact_email ?? '',
                'description' => $employer->description ?? '',
                'is_verified' => $employer->is_verified ?? 0,
            ] : [],
            'jobs' => model(\App\Models\JobModel::class)
                ->where('employer_id', $employer?->id)
                ->findAll(),
            'subscriptions' => model(\App\Models\UserSubscriptionModel::class)
                ->where('user_id', $user->id)
                ->findAll(),
            'payments' => model(\App\Models\PaymentModel::class)
                ->where('user_id', $user->id)
                ->findAll(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'jobberrecruit_employer_data_export_' . date('Y-m-d') . '.json';

        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($json);
    }

    public function bulkUpdateApplicationStatus()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $ids = $this->request->getPost('ids');
        $status = $this->request->getPost('status');

        if (empty($ids) || empty($status)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
        }

        $allowedStatuses = ['pending', 'reviewed', 'shortlisted', 'rejected', 'hired'];
        if (!in_array($status, $allowedStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $user = $this->auth->user();
        $employerModel = model(\App\Models\EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $applicationModel = model(\App\Models\JobApplicationModel::class);
        $applications = $applicationModel->select('job_applications.id')
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->whereIn('job_applications.id', $ids)
            ->where('jobs.employer_id', $employer->id)
            ->findAll();

        $allowedIds = array_column($applications, 'id');
        if (empty($allowedIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No valid applications found']);
        }

        $applicationModel->whereIn('id', $allowedIds)->set([
            'status'      => $status,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ])->update();

        return $this->response->setJSON(['success' => true, 'message' => 'Applications updated successfully']);
    }

    public function bulkDeleteApplications()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $ids = $this->request->getPost('ids');
        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No applications selected']);
        }

        $user = $this->auth->user();
        $employerModel = model(\App\Models\EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();
        if (!$employer) {
            return $this->response->setJSON(['success' => false, 'message' => 'Employer not found']);
        }

        $applicationModel = model(\App\Models\JobApplicationModel::class);
        $applications = $applicationModel->select('job_applications.id')
            ->join('jobs', 'jobs.id = job_applications.job_id')
            ->whereIn('job_applications.id', $ids)
            ->where('jobs.employer_id', $employer->id)
            ->findAll();

        $allowedIds = array_column($applications, 'id');
        if (empty($allowedIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No valid applications found']);
        }

        $db = \Config\Database::connect();
        $db->table('application_notes')->whereIn('application_id', $allowedIds)->delete();
        $db->table('application_status_history')->whereIn('application_id', $allowedIds)->delete();
        $db->table('job_application_answers')->whereIn('application_id', $allowedIds)->delete();
        $db->table('aptitude_test_invitations')->whereIn('application_id', $allowedIds)->delete();

        $applicationModel->whereIn('id', $allowedIds)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'Applications deleted successfully']);
    }

    public function addApplicationNote()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $applicationId = $this->request->getPost('application_id');
        $note = $this->request->getPost('note');

        if (empty($applicationId) || empty($note)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
        }

        $user = $this->auth->user();
        $type = $this->request->getPost('type') ?? 'internal';
        $employerModel = model(\App\Models\EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        $noteModel = model(\App\Models\ApplicationNoteModel::class);
        $noteId = $noteModel->insert([
            'application_id' => $applicationId,
            'employer_id'    => $employer->id ?? 0,
            'note'           => $note,
            'type'           => $type,
            'created_by'     => $user->id ?? 0
        ]);

        $insertedNote = [
            'id'              => $noteId,
            'application_id'  => $applicationId,
            'note'            => $note,
            'type'            => $type,
            'created_by_name' => $user->fullname ?? $user->username ?? 'Team Member',
            'created_at'      => date('d M, H:i')
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Note added successfully',
            'note'    => $insertedNote
        ]);
    }

    public function deleteApplicationNote($id)
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $noteModel = model(\App\Models\ApplicationNoteModel::class);
        $note = $noteModel->find($id);

        if (!$note) {
            return $this->response->setJSON(['success' => false, 'message' => 'Note not found']);
        }

        $noteModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Note deleted successfully']);
    }

    /**
     * AJAX: Generate job description using AI
     */
    public function generateJobDescription()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Direct access forbidden']);
        }

        $title = $this->request->getPost('title');
        $industry = $this->request->getPost('industry');
        $experience = $this->request->getPost('experience');
        $skills = $this->request->getPost('skills');

        if (empty($title)) {
            return $this->response->setJSON(['error' => 'Job title is required to generate a description.']);
        }

        $generateTemplate = function() use ($title, $industry, $experience, $skills) {
            $cleanTitle = esc($title);
            $cleanIndustry = !empty($industry) ? esc($industry) : 'our dynamic organization';
            $cleanExp = !empty($experience) ? esc($experience) : 'relevant professional';
            $skillList = !empty($skills) ? array_map('trim', explode(',', $skills)) : [];

            $html = "<p><strong>About the Role:</strong></p>\n" .
                    "<p>We are looking for a talented and driven <strong>{$cleanTitle}</strong> to join our team in the {$cleanIndustry} sector. In this role, you will contribute directly to our operational success, collaborate with talented colleagues, and help elevate our standards of excellence.</p>\n" .
                    "<p><strong>Key Responsibilities:</strong></p>\n" .
                    "<ul>\n" .
                    "<li>Oversee and execute day-to-day duties and core deliverables for the {$cleanTitle} position.</li>\n" .
                    "<li>Collaborate closely with internal teams and external stakeholders to achieve business objectives.</li>\n" .
                    "<li>Identify opportunities to improve processes, workflows, and service quality.</li>\n" .
                    "<li>Ensure compliance with company guidelines, industry best practices, and relevant standards.</li>\n" .
                    "<li>Track performance metrics and provide actionable reporting to department leadership.</li>\n" .
                    "</ul>\n" .
                    "<p><strong>Requirements &amp; Qualifications:</strong></p>\n" .
                    "<ul>\n" .
                    "<li>Demonstrated experience ({$cleanExp} level) in a similar capacity or field.</li>\n";

            if (!empty($skillList)) {
                foreach ($skillList as $sk) {
                    if (!empty($sk)) {
                        $html .= "<li>Proficiency in " . esc($sk) . ".</li>\n";
                    }
                }
            } else {
                $html .= "<li>Strong problem-solving, critical thinking, and communication skills.</li>\n" .
                         "<li>Ability to work autonomously as well as collaboratively in a fast-paced environment.</li>\n";
            }

            $html .= "<li>Relevant academic qualification, HND, Bachelor's degree, or equivalent practical experience.</li>\n" .
                     "</ul>\n" .
                     "<p><strong>What We Offer:</strong></p>\n" .
                     "<ul>\n" .
                     "<li>Competitive salary package commensurate with experience.</li>\n" .
                     "<li>Continuous professional development, mentorship, and career growth opportunities.</li>\n" .
                     "<li>Supportive, inclusive, and collaborative work culture.</li>\n" .
                     "</ul>";

            return $html;
        };

        if (empty(env('GEMINI_API_KEY'))) {
            return $this->response->setJSON([
                'status' => 'success',
                'description' => $generateTemplate(),
                'fallback' => true
            ]);
        }

        $prompt = "Write a comprehensive, professional job description for the role of '{$title}'. ";
        if (!empty($industry)) {
            $prompt .= "The company operates in the '{$industry}' industry. ";
        }
        if (!empty($experience)) {
            $prompt .= "The ideal candidate should have '{$experience}' experience level. ";
        }
        if (!empty($skills)) {
            $prompt .= "Key required skills: '{$skills}'. ";
        }
        $prompt .= "\n\nPlease write a professional description structured with these sections:\n" .
                   "1. About the Role\n" .
                   "2. Key Responsibilities (use clean bullet points)\n" .
                   "3. Requirements & Qualifications (use clean bullet points)\n" .
                   "4. What We Offer (use clean bullet points)\n" .
                   "Return only clean HTML (paragraphs and lists). Do not include markdown code block syntax (like ```html). Use standard HTML formatting tags like <p>, <ul>, <li>, <strong>.";

        try {
            $aiService = new \App\Services\AiService();
            $result = $aiService->generate($prompt);

            if (str_starts_with($result, 'Offline Mode Active:') || str_starts_with($result, 'AI Error:')) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'description' => $generateTemplate(),
                    'fallback' => true
                ]);
            }
            
            // Clean markdown code blocks if the AI returned it inside triple backticks
            if (str_starts_with($result, '```')) {
                $result = preg_replace('/^```(?:html)?\s*/i', '', $result);
                $result = preg_replace('/\s*```$/', '', $result);
            }

            return $this->response->setJSON(['status' => 'success', 'description' => trim($result)]);
        } catch (\Throwable $e) {
            log_message('error', 'Employer job AI generation fallback: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'success',
                'description' => $generateTemplate(),
                'fallback' => true
            ]);
        }
    }

    // ------------------------------------------------------------------
    // Extend a job's closing / deadline date (Dashboard "Closing Soon")
    // ------------------------------------------------------------------
    /**
     * Extend a job's closing date by N days (default 30).
     * Accepts optional POST field `days` (int, 7–90).
     * Falls back to GET redirect-back on success so the standard
     * "Closing Soon" Extend button works without JS.
     */
    public function extendJob($jobId)
    {
        $user     = $this->auth->user();
        $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();

        if (! $employer) {
            return redirect()->to('employer/dashboard')->with('error', 'Employer profile not found.');
        }

        $jobModel = model(JobModel::class);
        $job      = $jobModel->find($jobId);

        if (! $job || (int) $job->employer_id !== (int) ($employer->id ?? 0)) {
            return redirect()->to('employer/jobs')->with('error', 'Job not found or access denied.');
        }

        // How many days to extend (1–90, default 30)
        $days = (int) ($this->request->getPost('days') ?? 30);
        $days = max(1, min(90, $days));

        // Current expiry — prefer closing_date, fall back to deadline / application_deadline
        $currentExpiry = $job->closing_date ?? $job->deadline ?? $job->application_deadline ?? null;
        $baseTimestamp = ($currentExpiry && strtotime($currentExpiry) > time())
            ? strtotime($currentExpiry)
            : time();

        $newExpiry = date('Y-m-d', strtotime("+{$days} days", $baseTimestamp));

        // Update whichever column(s) exist — try closing_date first, then deadline
        $updateData = [];
        if (property_exists($job, 'closing_date') || isset($job->closing_date)) {
            $updateData['closing_date'] = $newExpiry;
        }
        if (property_exists($job, 'deadline') || isset($job->deadline)) {
            $updateData['deadline'] = $newExpiry;
        }
        if (property_exists($job, 'application_deadline') || isset($job->application_deadline)) {
            $updateData['application_deadline'] = $newExpiry;
        }

        if (! empty($updateData)) {
            $jobModel->update($jobId, $updateData);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'   => true,
                'message'   => "Job extended by {$days} days. New closing date: {$newExpiry}.",
                'new_date'  => $newExpiry,
            ]);
        }

        return redirect()->back()->with('success', "Job extended by {$days} days. New closing date: " . date('d M Y', strtotime($newExpiry)) . '.');
    }

    // ------------------------------------------------------------------
    // General Settings (Account / notification preferences)
    // ------------------------------------------------------------------
    /**
     * Show the General Settings page.
     * Handles password change and notification preference sub-forms.
     */
    public function settings()
    {
        $user     = $this->auth->user();
        $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();

        $data = [
            'title'    => 'General Settings',
            'user'     => $user,
            'employer' => $employer,
        ];

        return view('employers/settings', $data);
    }
}


