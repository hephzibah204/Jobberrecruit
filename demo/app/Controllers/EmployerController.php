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

        $this->paystackSecret = env('paystack_secret_key');
        $this->paystackCallback = env('paystack_callback_url') ?: base_url('pricing/verify');
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

        // Check if employer has unlimited access and it hasn't expired
        if ($employer->unlimited_access == 1) {
            if (empty($employer->unlimited_until) || strtotime($employer->unlimited_until) > time()) {
                return true;
            }
        }

        // Check if parent employer has unlimited access (for team/agency accounts)
        if (!empty($employer->parent_employer_id)) {
            return $this->hasUnlimitedAccess($employer->parent_employer_id);
        }

        return false;
    }

    public function dashboard()
    {
        $employerModel      = model(EmployerModel::class);
        $jobModel           = model(JobModel::class);
        $appModel           = model(JobApplicationModel::class);

        // Get logged-in employer
        $employer = $employerModel->where('user_id', $this->auth->user()->id)->first();

        $user = $this->auth->user();
        if($user->user_type == 'job_seeker') {
            return redirect()->to('candidate/dashboard');
        }

        if (!$employer) {
            return redirect()->to('employer/profile');
        }

        // =============================================
        // 🔹 CHECK CAC DOCUMENT STATUS (non-blocking)
        // =============================================
        $hasCACDocument = $this->hasUploadedCACDocument($employer->id);

        // Required fields validation
        if (
            empty($employer->company_size) ||
            empty($employer->contact_email)
        ) {
            return redirect()->to('employer/profile');
        }

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
        // 🔹 RETURN VIEW
        // ============================

        return view('employers/dashboard', [
            'title'             => 'Dashboard',
            'user'              => $this->auth->user(),
            'employer'          => $employer,
            'hasCACDocument'    => $hasCACDocument,

            // Stats
            'totalJobs'         => $totalJobs,
            'activeJobs'        => $activeJobs,
            'totalApplicants'   => $totalApplicants,
            'totalHires'        => $totalHires,

            // Lists
            'recentApplications' => $recentApplications,
            'recentJobs'        => $recentJobs,
            'categoryCounts'    => $categoryCounts,

            // Charts
            'jobsChart'         => $jobsChart,
            'appsChart'         => $appsChart,
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
            if (isset($features['featured']) && $features['featured'] === true) {
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
            return isset($features['anonymous']) && $features['anonymous'] === true;
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
     * Check if user can use network blast
     */
    protected function canUseNetworkBlast($plan, $hasUnlimitedAccess): bool
    {
        if ($hasUnlimitedAccess) {
            return true;
        }

        if ($plan && $plan->features) {
            $features = is_string($plan->features) ? json_decode($plan->features, true) : $plan->features;
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
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $industryModel = model(IndustryModel::class);
        $categoryModel = model(JobCategoryModel::class);
        $jobModel = model(JobModel::class);
        $creditService = new \App\Services\CreditService();

        $userId = auth()->user()->id;
        $employer = $employerModel->where('user_id', $userId)->first();

        if (!$employer) {
            return redirect()->to('employer/dashboard')->with('error', 'Employer profile not found.');
        }

        // Profile completeness check
        if (!$employer->company_size || !$employer->contact_email) {
            return redirect()->to('employer/profile')
                ->with('error', 'Please complete your employer profile before posting a job.');
        }

        // CAC document check is now OPTIONAL - employers can post jobs before verification

        // This will redirect automatically if access is denied
        $access = $this->checkJobPostingAccess();
        if ($access !== true) {
            return $access;
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
                'accommodation' => 'required|in_list[available,not_available]',
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
            $canPost = $creditService->canPerformAction($userId, 'post_job');

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
            $allowed = ['title','description','job_type','state_id','city','location_type','salary_type','salary_period','salary','salary_max','industry_id','category_id','education_level','experience_level','application_method','application_access','accommodation','contact_email','notification_email','whatsapp_link','application_email','external_url','external_link','application_deadline','start_date','urgency','show_salary','currency','is_anonymous','network_blast'];
            $postData = $this->request->getPost($allowed);
            $postData['employer_id'] = $employer->id;
            $postData['status'] = 'pending_approval';
            $postData['admin_status'] = 'pending';
            $application_deadline = $postData['application_deadline'] ?? null;
            $start_date = $postData['start_date'] ?? null;

            // Set premium features
            $postData['is_featured'] = $shouldBeFeatured ? 1 : 0;
            $postData['featured_until'] = $shouldBeFeatured ? date('Y-m-d H:i:s', strtotime('+30 days')) : null;
            $postData['is_anonymous'] = ($canPostAnonymous && $this->request->getPost('is_anonymous')) ? 1 : 0;
            $postData['network_blast'] = $canUseNetworkBlast ? 1 : 0;
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
                    throw new \Exception('Failed to create job');
                }

                // ---- NEW: Save Pre-screening Questions ----
                $questions = $this->request->getPost('questions');
                if (!empty($questions) && is_array($questions)) {
                    $questionModel = model(\App\Models\JobQuestionModel::class);
                    foreach ($questions as $q) {
                        if (!empty($q['text'])) {
                            $allowedTypes = ['text', 'yes_no', 'multiple_choice', 'select', 'radio', 'checkbox'];
                            $qType = in_array($q['type'] ?? 'text', $allowedTypes) ? $q['type'] : 'text';
                            $questionModel->insert([
                                'job_id'        => $jobId,
                                'question_text' => trim($q['text']),
                                'question_type' => $qType,
                                'is_required'   => isset($q['is_required']) ? 1 : 0,
                                'options'       => !empty($q['options']) ? trim($q['options']) : null,
                            ]);
                        }
                    }
                }

                // Only deduct credits if not unlimited access
                if (!$hasUnlimitedAccess) {
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

                // Create in-app notification for job posting
                $featuredMessage = $shouldBeFeatured ? " This job is FEATURED and will get priority visibility!" : "";
                $this->createNotification(
                    $employer->id,
                    $jobId,
                    null,
                    'job_pending',
                    'Job Posted - Pending Review',
                    "Your job '{$postData['title']}' has been submitted for admin review.{$featuredMessage}"
                );

                $db->transComplete();


                // Send email notification if enabled
                if ($notificationPreferences['email']) {
                    $this->sendJobPostingEmail($employer, $postData['title'], $jobId, $shouldBeFeatured);
                }

                // Build success message
                $successMessage = $hasUnlimitedAccess
                    ? 'Job posted successfully! (Unlimited Access - No credits deducted)'
                    : 'Job posted successfully! 1 credit deducted.';

                if ($shouldBeFeatured) {
                    $successMessage .= ' ⭐ Your job is FEATURED and will appear at the top of search results!';
                }

                if ($postData['is_anonymous']) {
                    $successMessage .= ' 🔒 Your company name will be hidden.';
                }

                if ($postData['network_blast']) {
                    $successMessage .= ' 📢 Network blast has been sent to 115k+ subscribers!';
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => $successMessage,
                    'job_id' => $jobId,
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

        // Views stats
        $totalClicks = $clickModel
            ->join('jobs', 'jobs.id = job_clicks.job_id')
            ->where('jobs.employer_id', $employer->id)
            ->countAllResults();

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
            ->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->where('jobs.employer_id', $employer->id)
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

        // Update status
        $applicationModel->update($applicationId, [
            'status' => $status,
            'reviewed_at' => date('Y-m-d H:i:s')
        ]);

        // Send email notification to job seeker (works for both authenticated and guest)
        $emailService = new \App\Services\EmailNotificationService();
        $emailSent = $emailService->sendApplicationStatusEmail(
            $application,
            $status,
            $job->title,
            $employer->company_name,
            $messageToCandidate
        );

        // Add a note for internal record
        $noteModel = model(ApplicationNoteModel::class);
        $noteModel->addNote(
            $applicationId,
            $employer->id,
            "Status changed to " . ucfirst($status) . ".\nMessage to candidate: " . ($messageToCandidate ?? 'No additional message'),
            $user->id,
            'feedback'
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Application status updated successfully. ' . ($emailSent ? 'The candidate has been notified.' : 'Failed to send email notification.'),
            'status' => $status,
            'email_sent' => $emailSent,
            'is_guest' => (bool)$application->is_guest,
            'reload' => false
        ]);
    }

    /**
     * Edit job page
     */
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
            return redirect()->to('employer/my-jobs')->with('error', 'Job not found or access denied.');
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

        return view('employers/edit-job', $data);
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
            'accommodation' => 'required|in_list[available,not_available]',
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
        $subscriptionModel = model(UserSubscriptionModel::class);
        $planModel = model(PlanModel::class);

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

        if (empty($planFeatures['featured'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Your plan does not support featured jobs.'
            ]);
        }

        $creditService = new JobCreditService();

        try {
            // 🔐 Atomic operation
            $db = db_connect();
            $db->transStart();

            // Deduct 5 job credit
            $creditService->deduct($user->id, $jobId, 5.00, 'Promote Job: ' . $job->title);

            // Promote job
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
                'message' => $e->getMessage()
            ]);
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

    public function job_detail($id)
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()
                ->to('employer/profile/edit')
                ->with('error', 'Please create your company profile first.');
        }

        $jobModel = model(JobModel::class);
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

        $featuredLimit = 0;
        $featuredUsed = 0;
        $remainingFeatured = 0;

        $canFeature = false;
        $featuredUsed = 0;

        $features = [];

        if ($activeSub) {
            $features = $activeSub['features_array'];

            $canFeature = !empty($features['featured']);

            if ($canFeature) {
                $featuredUsed = $jobModel
                    ->where('employer_id', $employer->id)
                    ->where('is_featured', 1)
                    ->where('featured_until >', date('Y-m-d H:i:s'))
                    ->countAllResults();
            }
        }

        $job = $jobModel
            ->select("
            jobs.*,
            job_categories.name AS category_name,
            industries.name AS industry_name,
            states.name AS location
        ")
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->where('jobs.id', $id)
            ->where('jobs.employer_id', $employer->id)
            ->first();

        if (!$job) {
            return redirect()
                ->to('employer/jobs')
                ->with('error', 'Job not found.');
        }

        $features = planFeatures($activeSub['features_array']);

        $creditWalletModel = model(JobCreditWalletModel::class);

        $creditBalance = (int) ($creditWalletModel
            ->where('user_id', $user->id)
            ->selectSum('credits')
            ->get()
            ->getRow()
            ->credits ?? 0);

        // Models
        $applicationModel = model(JobApplicationModel::class);
        $clickModel = model(JobClickModel::class);

        $data = [
            'title'             => 'Job Details',
            'user'              => $user,
            'employer'          => $employer,
            'job'               => $job,
            'features'          => $features,
            'creditBalance' => $creditBalance,
            'activeSubscription' => $activeSub,

            // Analytics
            'applicationCount'  => $applicationModel->where('job_id', $id)->countAllResults(),
            'totalClicks'       => $clickModel->totalClicks($id),

            // Extra: Make application method fields directly available to the view
            'applicationMethod' => $job->application_method,
            'accessType'        => $job->application_access,
            'whatsappLink'      => $job->whatsapp_link,
            'applicationEmail'  => $job->application_email,
            'externalUrl'       => $job->external_url
        ];

        return view('employers/job-detail', $data);
    }


    public function edit_job($id)
    {
        // Models
        $employerModel = new EmployerModel();
        $industryModel = new IndustryModel();
        $categoryModel = new JobCategoryModel();
        $jobModel      = new JobModel();

        /* -----------------------------------------------------------------
     * 1. Employer & job ownership check
     * ----------------------------------------------------------------- */
        $employer = $employerModel->where('user_id', auth()->user()->id)->first();
        if (!$employer) {
            return redirect()->to('employer/profile')->with('error', 'Employer profile not found.');
        }

        $job = $jobModel->find($id);
        if (!$job || $job->employer_id !== $employer->id) {
            return redirect()->to('employer/jobs')->with('error', 'Job not found or you do not own it.');
        }

        /* -----------------------------------------------------------------
     * 2. POST — update
     * ----------------------------------------------------------------- */
        if ($this->request->getMethod() === 'POST') {

            /* ---------------------------------------------------------
         * Base validation rules
         * --------------------------------------------------------- */
            $rules = [
                'title'              => 'required|min_length[3]',
                'description'        => 'required|min_length[50]',
                'job_type'           => 'required|in_list[full-time,part-time,contract,freelance,internship]',
                'state_id'           => 'required|is_natural_no_zero',
                'location_type'      => 'required|in_list[hybrid,remote,on-site]',

                'salary_type'        => 'required|in_list[fixed,range,negotiable]',
                'salary_period'      => 'required|in_list[monthly,yearly,hourly]',
                'salary'             => 'permit_empty|min_length[1]|regex_match[/^(?:₦|N)?\s?\d{1,3}(?:,\d{3})*(?:\s?-\s?(?:₦|N)?\s?\d{1,3}(?:,\d{3})*)?$/u]',

                'industry_id'        => 'required|is_natural_no_zero',
                'category_id'        => 'required|is_natural_no_zero',
                'education_level'    => 'required',
                'experience_level'   => 'required',

                'skills'             => 'permit_empty|string',
                'requirements'       => 'permit_empty|string',

                'application_deadline' => 'permit_empty|valid_date[Y-m-d]',
                'start_date'         => 'permit_empty|valid_date[Y-m-d]',

                'contact_email'      => 'required|valid_email',
                'contact_phone'      => 'permit_empty|regex_match[/^(?:\+?[1-9]\d{1,14}|0\d{9,10})$/]',

                'application'        => 'permit_empty|string',
                'application_method' => 'required|in_list[form,whatsapp,email,external]',
                'application_access' => 'required|in_list[guest,authenticated,general]',
            ];

            $postData = $this->request->getPost();
            $method   = $postData['application_method'] ?? $job->application_method;

            /* ---------------------------------------------------------
         * Extract conditional inputs BEFORE clearing anything
         * --------------------------------------------------------- */
            $whatsappInput       = $this->request->getPost('whatsapp_link');
            $applicationEmailInput = $this->request->getPost('application_email');
            $externalUrlInput    = $this->request->getPost('external_url');

            /* ---------------------------------------------------------
         * Add conditional validation rules
         * --------------------------------------------------------- */
            if ($method === 'whatsapp') {
                $rules['whatsapp_link'] = 'required|valid_url';
            } elseif ($method === 'email') {
                $rules['application_email'] = 'required|valid_email';
            } elseif ($method === 'external') {
                $rules['external_url'] = 'required|valid_url';
            }

            /* ---------------------------------------------------------
         * Validate request
         * --------------------------------------------------------- */
            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $this->validator->getErrors()
                ]);
            }

            /* ---------------------------------------------------------
         * Prepare clean update data
         * --------------------------------------------------------- */
            $postData['employer_id'] = $employer->id;
            $postData['status']      = $job->status;

            // Salary details
            if ($postData['salary_type'] !== 'negotiable' && !empty($postData['salary'])) {
                $postData['salary_details'] =
                    ucfirst($postData['salary_type']) . ', ' .
                    ucfirst($postData['salary_period']) . ': ' .
                    $postData['salary'];
            } else {
                $postData['salary_details'] = 'Negotiable';
            }

            /* ---------------------------------------------------------
         * Remove all method fields, then set only the relevant one
         * --------------------------------------------------------- */
            $postData['whatsapp_link']     = null;
            $postData['application_email'] = null;
            $postData['external_url']      = null;

            switch ($method) {
                case 'whatsapp':
                    $postData['whatsapp_link'] = trim($whatsappInput);
                    break;

                case 'email':
                    $postData['application_email'] = trim($applicationEmailInput);
                    break;

                case 'external':
                    $postData['external_url'] = trim($externalUrlInput);
                    break;

                case 'form':
                default:
                    // No extra fields
                    break;
            }

            $postData['application_method'] = $method;

            /* ---------------------------------------------------------
         * Update Job
         * --------------------------------------------------------- */
            if ($jobModel->update($id, $postData)) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Job updated successfully.'
                ]);
            }

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to update job.'
            ]);
        }

        /* -----------------------------------------------------------------
     * 3. GET – show edit form
     * ----------------------------------------------------------------- */
        $data = [
            'title'      => 'Edit Job',
            'user'       => auth()->user(),
            'employer'   => $employer,
            'job'        => $job,
            'industries' => $industryModel->findAll(),
            'categories' => $categoryModel->findAll(),
            'states'     => (new StateModel())->findAll(),
        ];

        return view('employers/edit-job', $data);
    }


    public function deleteJob($id) 
    {
        $user = $this->auth->user();
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        $jobModel = model(JobModel::class);
        $job = $jobModel->find($id);
        if (!$job) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Job not found'
            ]);
        }
        if ($job->employer_id !== ($employer->id ?? null)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You do not have permission to delete this job'
            ]);
        }
        $jobModel->delete($id);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Job deleted successfully.'
        ]);
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
     * Update application status via AJAX
     */
    public function updateApplicationStatus2()
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

        $allowedStatuses = ['pending', 'reviewed', 'shortlisted', 'rejected', 'hired'];
        if (!in_array($status, $allowedStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $applicationModel = model(JobApplicationModel::class);
        $application = $applicationModel->find($applicationId);

        if (!$application) {
            return $this->response->setJSON(['success' => false, 'message' => 'Application not found']);
        }

        // Verify ownership through job
        $jobModel = model(JobModel::class);
        $job = $jobModel->find($application->job_id);

        if (!$job || $job->employer_id != $employer->id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $applicationModel->update($applicationId, [
            'status' => $status,
            'reviewed_at' => date('Y-m-d H:i:s')
        ]);

        // Create notification for job seeker (optional)
        // $this->sendStatusUpdateEmail($application, $status);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Application status updated successfully',
            'status' => $status
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

        // Verify ownership
        $jobModel = model(JobModel::class);
        $job = $jobModel->find($application->job_id);

        if (!$job || $job->employer_id != $employer->id) {
            return redirect()->to('employer/applications')->with('error', 'Unauthorized access');
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

        return view('employers/application_view', [
            'title' => 'Application Details',
            'user' => $user,
            'employer' => $employer,
            'application' => $application,
            'notes' => $notes,
            'answers' => $answers,
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

            $rules = [
                'company_name'      => 'required|min_length[3]',
                'state_id'          => 'required|integer',
                'company_size'      => 'required',
                'contact_name'      => 'required|min_length[3]',
                'contact_email'     => 'required|valid_email',
                'contact_phone'     => 'required|min_length[6]',
                'industry_ids'      => 'required',
            ];

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

            // === Update Employer record ===
            $employerModel->update($employer->id, $data);

            // === Update Industry relationships ===
            $industryIds = $this->request->getVar('industry_ids') ?? [];
            $employerIndustryModel->where('employer_id', $employer->id)->delete();
            foreach ($industryIds as $industryId) {
                $employerIndustryModel->insert([
                    'employer_id' => $employer->id,
                    'industry_id' => $industryId
                ]);
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

        $employer = $employerModel->where('user_id', $user->id)->first();
        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please complete your company profile first.');
        }

        // Active Subscription
        $userSubscription = $subscriptionModel
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->first();

        $currentPlan = $userSubscription ? $planModel->find($userSubscription->plan_id) : null;

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

        $bundles = $bundleModel
            ->where('is_active', 1)
            ->orderBy('job_credits', 'ASC')
            ->findAll();

        $creditBalance = $creditService->getAvailableCredits($user->id);

        return view('employers/pricing', [
            'title'            => 'Pricing & Plans',
            'user'           => $user,
            'employer'         => $employer,
            'currentPlan'      => $currentPlan,
            'bundles'          => $bundles,
            'creditBalance'    => $creditBalance,
            'pricingTiers'     => $pricingTiers,           // ← Important
            'subscriptionPlan' => $subscriptionPlan        // optional
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

        $paymentModel = model(PaymentModel::class);
        
        // Get all payments for this employer
        $transactions = $paymentModel
            ->where('employer_id', $employer->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Calculate total spent
        $totalSpent = array_sum(array_column($transactions, 'amount'));

        return view('employers/transactions', [
            'title' => 'Transaction History',
            'user' => $user,
            'employer' => $employer,
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

            $amount = (float)$tiers[$months];

            $description = "{$plan->name} ({$months} Month" . ($months > 1 ? 's' : '') . ")";

            $metadata = [
                'type'        => 'subscription',
                'plan_id'     => (int)$plan->id,
                'plan_code'  => $plan->code,
                'months'      => $months,
            ];
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

        return $this->response->setJSON([
            'success'     => true,
            'paystack'  => env('paystack_public_key'),
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
            $verification = $paystack->verifyTransaction($reference);

            if (!$verification['status'] || $verification['data']['status'] !== 'success') {
                log_message('error', 'Payment verification failed for reference: ' . $reference);
                return redirect()->to('employer/pricing')->with('error', 'Payment verification failed. Please contact support.');
            }

            $amount = $verification['data']['amount'] / 100;
            $metadata = $verification['data']['metadata']['app_data'] ?? [];

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
                        $months
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
        $bundleModel      = model(BundlePackageModel::class);
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
            ->orderBy('price', 'ASC')
            ->findAll();

        /* -------------------------------------------------
     * JOB CREDIT BALANCE (SOURCE OF TRUTH)
     * ------------------------------------------------- */
        $creditBalance = $creditWalletModel
            ->where('user_id', $user->id)
            ->selectSum('credits')
            ->get()
            ->getRow()->credits ?? 0;

        /* -------------------------------------------------
     * ENSURE STARTER ACCESS (ONE-TIME CREDIT)
     * ------------------------------------------------- */
        $starterPlan = $planModel
            ->where('code', 'starter')
            ->where('billing_type', 'free')
            ->first();

        if ($starterPlan && $creditBalance == 0 && !$userPlan) {
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
            'title'          => 'Job Bundles',
            'user'           => $user,
            'employer'       => $employer,
            'plans'          => $plans,
            'bundles'        => $bundles,
            'user_plan'      => $userPlan,
            'creditBalance'  => (int) $creditBalance,
            'bundleHistory' => $bundleHistory,
            'recommendedBundle' => $recommendedBundle,
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

        $bundle = model(BundlePackageModel::class)
            ->where('code', $bundleCode)
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
            'public_key'  => env('paystack_public_key'),
            'email'       => $user->email,
            'amount'      => (int) ($bundle['price'] * 100),
            'reference'   => 'bundle_' . uniqid(),
            'metadata'    => [
                'type'      => 'bundle',
                'bundle_id' => $bundle['id'],
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

        $bundle = model(BundlePackageModel::class)
            ->where('code', $bundleCode)
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
        $bundlePrice   = (float) $bundle['price'];

        // FULL WALLET PAYMENT
        if ($walletBalance >= $bundlePrice) {

            $reference = 'wallet_bundle_' . uniqid();

            (new \App\Services\WalletService())->debit(
                userId: $user->id,
                amount: $bundlePrice,
                source: 'bundle_purchase',
                reference: $reference,
                sourceId: $bundle['id'],
                description: 'Bundle purchase'
            );

            (new \App\Services\BundleService())->credit(
                userId: $user->id,
                bundleId: $bundle['id'],
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
            'public_key'  => env('paystack_public_key'),
            'email'       => $user->email,
            'amount'      => (int) ($remaining * 100),
            'reference'   => 'bundle_hybrid_' . uniqid(),
            'metadata'    => [
                'type'        => 'bundle',
                'bundle_id'   => $bundle['id'],
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

        // Reward referrer for employer first payment
        $this->triggerEmployerReferralReward((int) $meta['user_id']);

        return $this->response->setJSON([
            'success'  => true,
            'verified' => true
        ]);
    }


    public function subscribe($planId)
    {
        $plan = model(PlanModel::class)->find($planId);

        $paystack = service('paystack');

        $response = $paystack->initialize([
            'email' => auth()->user()->email,
            'amount' => $plan->price * 100,
            'plan'  => $plan->paystack_plan_code,
            'metadata' => [
                'type'      => 'subscription',
                'user_id'   => auth()->user()->id,
                'plan_code' => $plan->paystack_plan_code
            ]
        ]);

        return redirect()->to($response['data']['authorization_url']);
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

        if (!$currentSub || empty($currentSub->paystack_subscription_id)) {
            return redirect()->back()->with('error', 'No active paid subscription to cancel.');
        }

        // Call Paystack to disable/cancel subscription
        $cancelled = $this->deactivatePaystackSubscription($currentSub->paystack_subscription_id);

        if (!$cancelled) {
            return redirect()->back()->with('error', 'Failed to cancel subscription with Paystack. Please try again or contact support.');
        }

        // Mark locally as will_not_renew (access continues until end_date)
        $subModel->update($currentSub->id, [
            'will_not_renew' => 1,
            'updated_at'     => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('employer/pricing')
            ->with('success', 'Your subscription has been cancelled. You will keep access until ' . date('F j, Y', strtotime($currentSub->end_date)) . '.');
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
            'endDate'    => $currentSub->end_date,
        ]);

        return redirect()->to('employer/pricing')
            ->with('success', 'Your subscription has been successfully reactivated! Monthly billing will resume on ' .
                date('F j, Y', strtotime($currentSub->end_date)) . '.');
    }

    /**
     * Checkout page — calculates prorated upgrade cost + handles billing cycle.
     */
    public function checkout($planSlug = null)
    {
        $user = $this->auth->user();
        if (!$user) return redirect()->to('/login');

        $billingCycle = $this->request->getGet('billing_cycle') ?? 'monthly';

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        if (!$employer) {
            return redirect()->to('employer/profile/edit')->with('error', 'Please create your company profile first.');
        }

        $planModel = model(SubscriptionPlanModel::class);
        $userPlanModel = model(UserSubscriptionModel::class);

        $plan = $planModel->where('slug', $planSlug)->first();
        if (!$plan) {
            return redirect()->back()->with('error', 'Plan not found.');
        }

        $currentSub = $userPlanModel->where('user_id', $user->id)->first();

        // FREE PLAN → assign instantly
        if ($plan->slug === 'free' && (float)$plan->price == 0) {
            $this->applyPlanToUser($user->id, $plan->id, $plan->duration);
            return redirect()->to('employer/pricing')->with('success', 'Free plan applied.');
        }

        // ENTERPRISE PLAN → no payment, contact sales instead
        if ($plan->slug === 'enterprise') {
            return view('employers/checkout', [
                'user' => $user,
                'employer' => $employer,
                'title' => 'Checkout Subscription for ' . $plan->name,
                'plan' => $plan,
                'billingCycle' => $billingCycle,
                'message' => 'This plan requires a custom quote. Please contact sales.'
            ]);
        }

        // Determine new plan amount based on billing cycle
        if ($billingCycle === 'yearly') {
            $newPlanPrice = $plan->price * 12 * 0.85; // yearly billing
        } else {
            $newPlanPrice = $plan->price; // monthly
        }

        // If no current subscription → charge full amount
        if (!$currentSub) {
            return view('employers/checkout', [
                'user' => $user,
                'employer' => $employer,
                'title' => 'Checkout Subscription for ' . $plan->name,
                'plan' => $plan,
                'billingCycle' => $billingCycle,
                'upgradeCost' => round($newPlanPrice, 2),
                'explain' => 'Full price (no existing subscription).'
            ]);
        }

        // If same plan chosen → no upgrade needed
        if ($currentSub->plan_id == $plan->id) {
            return redirect()->to('subscription/pricing')->with('info', 'You are already on this plan.');
        }

        // Calculate prorated credit
        $oldPlan = $planModel->find($currentSub->plan_id);

        $today = new DateTime();
        $end = new DateTime($currentSub->end_date);
        $daysLeft = (int)$today->diff($end)->format('%a');
        if ($daysLeft < 0) $daysLeft = 0;

        $oldDuration = max($oldPlan->duration, 1); // avoid division by zero
        $dailyOld = (float)$oldPlan->price / $oldDuration;
        $remainingCredit = $daysLeft * $dailyOld;

        // Compute upgrade cost
        $rawUpgradeCost = $newPlanPrice - $remainingCredit;
        $upgradeCost = max($rawUpgradeCost, 0);

        $explain = "Prorated: {$daysLeft} day(s) left. Remaining credit: ₦" . number_format($remainingCredit, 2);

        return view('employers/checkout', [
            'user' => $user,
            'employer' => $employer,
            'title' => 'Checkout Subscription for ' . $plan->name,
            'plan' => $plan,
            'billingCycle' => $billingCycle,
            'currentSub' => $currentSub,
            'upgradeCost' => round($upgradeCost, 2),
            'explain' => $explain
        ]);
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

    public function verifyPayment(string $reference)
    {
        $paystack = service('paystack');
        $wallet   = service('wallet');

        $response = $paystack->verifyTransaction($reference);

        if (! $response['status']) {
            throw new \RuntimeException('Payment verification failed');
        }

        $data = $response['data'];

        $paymentModel = model(PaymentModel::class);
        $payment = $paymentModel->where('reference', $reference)->first();

        if ($payment['status'] === 'paid') {
            return;
        }

        $wallet->credit(
            $payment['user_id'],
            $payment['amount_paid'],
            'wallet_funding',
            $reference,
            null,
            'Wallet funding via Paystack'
        );

        $paymentModel->update($payment['id'], [
            'status' => 'paid',
            'gateway_response' => json_encode($data),
        ]);
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
        if ($existingPlan && $existingPlan->plan_slug !== 'free') {
            $this->deactivatePaystackSubscription($existingPlan->paystack_subscription_id);
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
            'start_date'    => $start,
            'end_date'      => $end,
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
     * Send email notification for job posting
     */
    protected function sendJobPostingEmail($employer, $jobTitle, $jobId)
    {
        try {
            $email = \Config\Services::email();

            // Configure email
            $email->setTo($employer->contact_email ?? $employer->company_email);
            $email->setSubject("Job Posted: {$jobTitle} - Pending Review");
            $email->setMailType('html');

            $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f8f9fa; }
                .button { display: inline-block; background: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 10px; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Job Posted Successfully!</h2>
                </div>
                <div class='content'>
                    <p>Hello <strong>" . htmlspecialchars($employer->company_name) . "</strong>,</p>
                    <p>Your job \"<strong>" . htmlspecialchars($jobTitle) . "</strong>\" has been submitted successfully and is pending admin review.</p>
                    <p>You will be notified once it's approved and live on our platform.</p>
                    <p>
                        <a href='" . base_url("employer/jobs/view/{$jobId}") . "' class='button'>View Job Details</a>
                    </p>
                    <p>Thank you for using Jobber Recruit!</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " Jobber Recruit. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";

            $email->setMessage($message);

            if (!$email->send()) {
                log_message('error', 'Failed to send job posting email: ' . $email->printDebugger(['headers']));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Email sending failed: ' . $e->getMessage());
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
            $typeStats[$stat->type] = $stat->count;
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
            'currentPage' => $page,
            'perPage' => $perPage,
            'creditBalance' => $creditBalance,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
        ];

        return view('employers/notifications', $data);
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
        $filters = [
            'keyword'          => $this->request->getGet('keyword'),
            'state_id'         => $this->request->getGet('state'),
            'employment_type'  => $this->request->getGet('job_type'),
            'experience_years' => $this->request->getGet('experience'),
            'job_title'        => (array) $this->request->getGet('job_title'),
            'availability'     => (array) $this->request->getGet('availability'),
            'employment_type'  => (array) $this->request->getGet('employment_type'),
            'education_level'  => (array) $this->request->getGet('education_level'),
        ];

        $candidates = $jobSeekerModel->getCandidates($filters, 20);

        $data = [
            'title'      => 'Find Candidates',
            'user'       => $this->auth->user(),
            'employer'   => model(EmployerModel::class)->where('user_id', $this->auth->user()->id)->first(),
            'candidates' => $candidates,
            'pager'      => $jobSeekerModel->pager,
            'total'      => $jobSeekerModel->pager->getTotal(),

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

    public function filterCandidates()
    {
        $jobSeekerModel = model(JobSeekerModel::class);
        $filters = $this->request->getGet();

        $candidates = $jobSeekerModel->getCandidates($filters, 10);

        return view('employers/partials/candidates_table', [
            'candidates' => $candidates,
            'pager'      => $jobSeekerModel->pager,
        ]);
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
        
        // Check if unlocked
        $db = db_connect();
        $isUnlocked = $db->table('candidate_unlocks')
            ->where('employer_id', $employer->id)
            ->where('job_seeker_id', $id)
            ->countAllResults() > 0;

        $creditService = new CreditService();
        $hasUnlimited = $creditService->hasUnlimitedAccess($this->auth->user()->id);

        // Industries
        $industries = model(JobSeekerIndustryModel::class)
            ->select('industries.name')
            ->join('industries', 'industries.id = job_seeker_industries.industry_id')
            ->where('job_seeker_id', $id)
            ->findAll();

        return view('employers/candidate-detail', [
            'title'      => $candidate->full_name,
            'user'       => $this->auth->user(),
            'employer'   => $employer,
            'candidate'  => $candidate,
            'industries' => $industries,
            'isUnlocked' => $isUnlocked || $hasUnlimited,
            'hasUnlimited' => $hasUnlimited
        ]);
    }

    public function unlockCandidate()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $candidateId = $this->request->getPost('candidate_id');
        $employer = model(EmployerModel::class)->where('user_id', $this->auth->user()->id)->first();

        $creditService = new CreditService();
        
        // Deduct 1 credit for unlocking a candidate
        $result = $creditService->deductCredits(
            $this->auth->user()->id,
            1,
            'unlock_' . $candidateId,
            'Unlocked candidate: ' . $candidateId,
            'unlock_candidate'
        );

        if ($result['success']) {
            $db = db_connect();
            $db->table('candidate_unlocks')->insert([
                'employer_id' => $employer->id,
                'job_seeker_id' => $candidateId,
                'unlocked_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Candidate unlocked successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => $result['message']
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

        $applicationModel->whereIn('id', $allowedIds)->set(['status' => $status])->update();

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
        $employerModel = model(\App\Models\EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();

        $noteModel = model(\App\Models\ApplicationNoteModel::class);
        $noteModel->insert([
            'application_id' => $applicationId,
            'employer_id' => $employer->id ?? 0,
            'note' => $note,
            'created_by' => $user->id ?? 0
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Note added successfully']);
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
}
