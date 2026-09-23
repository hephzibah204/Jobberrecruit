<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\BlogModel;
use App\Models\CountryModel;
use App\Models\EmployerIndustryModel;
use App\Models\EmployerModel;
use App\Models\IndustryModel;
use App\Models\JobApplicationModel;
use App\Models\JobCategoryModel;
use App\Models\JobClickModel;
use App\Models\JobModel;
use App\Models\JobSeekerModel;
use App\Models\StateModel;
use App\Models\SubscriptionPlanModel;
use App\Models\TestimonialModel;
use App\Models\UserModel;
use App\Models\PlanBundleModel;
use App\Models\PlanModel;
use App\Models\EmployerDocumentModel;
use App\Models\UserSubscriptionModel;
use App\Models\JobNotificationModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Exceptions\ValidationException;
use PaystackService;

class AdminController extends BaseController
{
    protected $auth;
    protected $config;
    protected $users;            // App\Models\UserModel
    protected $userProvider;     // Shield user provider (model)
    protected $userModel;        // Shield UserModel alias
    protected $session;
    protected $jobSeekerModel;
    protected $employerModel;
    protected $jobModel;
    protected $jobApplicationModel;
    protected $subscriptionModel;
    protected $adminModel;
    protected $admin;
    protected $employerIndustryModel;
    protected $industryModel;
    protected $countryModel;
    protected $stateModel;
    protected $categoryModel;

    public function __construct()
    {
        helper(['auth', 'text', 'form', 'url', 'env', 'date', 'image']);

        $this->auth           = service('auth');
        $this->config         = config('Auth');
        // $this->users          = model(UserModel::class);
        $this->userProvider   = model(setting('Auth.userProvider'));
        // $this->userModel      = model(ShieldUserModel::class);
        $this->session        = service('session');
        $this->jobSeekerModel = model(JobSeekerModel::class);
        $this->employerModel  = model(EmployerModel::class);
        $this->jobModel             = new JobModel();
        $this->jobApplicationModel  = new JobApplicationModel();
        $this->subscriptionModel    = new UserSubscriptionModel();
        $this->adminModel       = model(AdminModel::class);
        $this->employerIndustryModel = new EmployerIndustryModel();
        $this->industryModel         = new IndustryModel();
        $this->categoryModel             = new JobCategoryModel();

        $this->countryModel = new CountryModel();
        $this->stateModel = new StateModel();


        if ($this->auth->user()) {
            $this->admin = $this->adminModel->where('user_id', $this->auth->user()->id)->first();
        }
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            try {
                $email = trim((string) $this->request->getPost('email'));
                $password = (string) $this->request->getPost('password');

                // 1) Hard reset any previous session
                if ($this->auth->loggedIn()) {
                    $this->auth->logout();
                }
                session()->regenerate(true);

                // 2) Resolve user & identity from DB
                $db = db_connect();
                $identity = $db->table('auth_identities')
                    ->select('user_id, secret2')
                    ->where('type', 'email_password')
                    ->where('secret', $email)
                    ->get()
                    ->getRow();

                $userModel = model(UserModel::class);
                $user = $identity ? $userModel->find((int) $identity->user_id) : null;

                if (!$identity || !$user) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid administrator credentials.'
                    ])->setStatusCode(401);
                }

                // 3) Verify password
                if (!service('passwords')->verify($password, (string) $identity->secret2)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid administrator credentials.'
                    ])->setStatusCode(401);
                }

                // 4) Ensure admin user_type
                if ($user->user_type !== 'admin') {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Unauthorized access: administrator permissions required.'
                    ])->setStatusCode(403);
                }

                // 5) Log in admin
                $this->auth->login($user, true);

                $redirect = session()->get('admin_redirect') ?? base_url('admin/dashboard');
                session()->remove('admin_redirect');

                return $this->response->setJSON([
                    'success'  => true,
                    'message'  => 'Welcome back!',
                    'redirect' => $redirect
                ]);
            } catch (\Throwable $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Authentication error: ' . $e->getMessage()
                ])->setStatusCode(500);
            }
        }

        // Already logged in admin? Resume
        if (auth()->loggedIn() && auth()->user()->user_type === 'admin') {
            return redirect()->to(
                session()->get('admin_redirect') ?? base_url('admin/dashboard')
            );
        }

        $data = [
            'title' => 'Login'
        ];
        return view('admin/login', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }

    public function index()
    {

        if ($this->auth->user()->user_type !== 'admin') {
            return redirect()->to('/');
        }
        helper('date');

        $currentYear  = (int) date('Y');
        $previousYear = $currentYear - 1;

        $currentStats  = $this->jobSeekerModel->countByYearAndGender($currentYear);
        $previousStats = $this->jobSeekerModel->countByYearAndGender($previousYear);

        $genderStats = $this->jobSeekerModel->countByGender();

        $maleCount = 0;
        $femaleCount = 0;

        foreach ($genderStats as $row) {
            if (strtolower($row->gender) === 'male') {
                $maleCount = (int) $row->total;
            }
            if (strtolower($row->gender) === 'female') {
                $femaleCount = (int) $row->total;
            }
        }

        $current  = $this->normalizeGenderStats($currentStats);
        $previous = $this->normalizeGenderStats($previousStats);

        /* Growth calculation */
        $genderGrowth = [];

        foreach ($current as $gender => $count) {
            $prev = $previous[$gender];

            $growth = 0;
            if ($prev > 0) {
                $growth = (($count - $prev) / $prev) * 100;
            }

            $genderGrowth[$gender] = [
                'current'  => $count,
                'previous' => $prev,
                'growth'   => round($growth, 2)
            ];
        }

        $applicationStats = $this->jobApplicationModel->countByStatus();

        /* Normalize statuses */
        $statuses = [
            'pending'      => 0,
            'hired'        => 0,
            'shortlisted'  => 0,
            'rejected'     => 0,
            'reviewed'     => 0,
        ];

        $totalApplications = 0;

        foreach ($applicationStats as $row) {
            $key = strtolower($row->status);
            if (array_key_exists($key, $statuses)) {
                $statuses[$key] = (int) $row->total;
                $totalApplications += (int) $row->total;
            }
        }

        /* Calculate percentages */
        $percentages = [];
        foreach ($statuses as $key => $count) {
            $percentages[$key] = $totalApplications > 0
                ? round(($count / $totalApplications) * 100)
                : 0;
        }

        $year = date('Y');

        $stats = $this->jobApplicationModel->performanceStats($year);

        $months = array_fill(1, 12, 0);

        $daily   = $months;
        $weekly  = $months;
        $monthly = $months;

        foreach ($stats['daily'] as $row) {
            $daily[(int)$row->m] = (int)$row->total;
        }
        foreach ($stats['weekly'] as $row) {
            $weekly[(int)$row->m] = (int)$row->total;
        }
        foreach ($stats['monthly'] as $row) {
            $monthly[(int)$row->m] = (int)$row->total;
        }

        // Items awaiting an admin decision, surfaced as the "Needs attention" queue
        $db = \Config\Database::connect();
        $docSubmittedSql = '(EXISTS (SELECT 1 FROM employer_documents WHERE employer_documents.employer_id = employers.id) OR (employers.verification_doc IS NOT NULL AND employers.verification_doc != "") OR (employers.verification_documents IS NOT NULL AND employers.verification_documents != "" AND employers.verification_documents != "[]"))';
        $pendingEmployersCount = (int) $db->table('employers')
            ->where('verification_status', 'pending')
            ->where($docSubmittedSql, null, false)
            ->countAllResults();
        $pendingJobsCount = (int) $this->jobModel->where('admin_status', 'pending')->countAllResults();

        $attention = [
            'jobs' => [
                'label' => 'Jobs awaiting approval',
                'count' => $pendingJobsCount,
                'url'   => base_url('admin/jobs?status=pending_approval'),
                'icon'  => 'ti-briefcase',
            ],
            'employers' => [
                'label' => 'Employers awaiting verification',
                'count' => $pendingEmployersCount,
                'url'   => base_url('admin/employers?status=pending'),
                'icon'  => 'ti-building',
            ],
            'reports' => [
                'label' => 'Job reports to resolve',
                'count' => (int) $db->table('job_reports')->where('status', 'pending')->countAllResults(),
                'url'   => base_url('admin/reports'),
                'icon'  => 'ti-flag',
            ],
            'cv_reviews' => [
                'label' => 'CV reviews in queue',
                'count' => (int) $db->table('cv_reviews')->where('status', 'pending')->countAllResults(),
                'url'   => base_url('admin/cv-reviews'),
                'icon'  => 'ti-file-text',
            ],
            'testimonials' => [
                'label' => 'Testimonials to moderate',
                'count' => (int) $db->table('testimonials')->where('status', 'pending')->countAllResults(),
                'url'   => base_url('admin/testimonials'),
                'icon'  => 'ti-message-star',
            ],
        ];

        $data = [
            'title' => 'Dashboard',
            'user' => $this->auth->user(),
            'admin' => $this->admin,
            'attention' => $attention,
            'totalEmployers'    => $this->employerModel->countAllResults(),
            'totalCandidates'   => $this->jobSeekerModel->countAllResults(),
            'totalJobs'         => $this->jobModel->countAllResults(),
            'totalApplications' => $this->jobApplicationModel->countAllResults(),
            'activeSubscribers' => $this->subscriptionModel
                ->select('user_subscriptions.*, plans.name, plans.slug')
                ->join('plans', 'plans.id = user_subscriptions.plan_id')
                ->where('user_subscriptions.is_active', 1)
                ->countAllResults(),
            'topEmployers'  => $this->employerModel->getTopEmployers(5),
            'maleCandidates' => $maleCount,
            'femaleCandidates' => $femaleCount,
            'candidateChart' => [
                'labels' => ['Male', 'Female'],
                'series' => [$maleCount, $femaleCount],
            ],
            'candidateYoYByGender' => $genderGrowth,
            'topJobPositions' => $this->jobModel->getTopJobPositions(5),
            'applications' => [
                'total'       => $totalApplications,
                'counts'      => $statuses,
                'percentages' => $percentages,
            ],
            'lastCandidates' => $this->jobSeekerModel->getLastCandidates(5),
            'recentJobs' => $this->jobModel->getRecentJobs(10),
            'employeePerformance' => [
                'daily'   => array_values($daily),
                'weekly'  => array_values($weekly),
                'monthly' => array_values($monthly),
            ],
            'userGrowth' => (function() use ($year) {
                $db = db_connect();
                $userStats = $db->table('users')
                    ->select('MONTH(created_at) AS month, user_type, COUNT(id) AS total')
                    ->where('YEAR(created_at)', $year)
                    ->groupBy('MONTH(created_at), user_type')
                    ->get()->getResultArray();

                $monthlyCandidates = array_fill(1, 12, 0);
                $monthlyEmployers = array_fill(1, 12, 0);
                foreach ($userStats as $row) {
                    $m = (int)$row['month'];
                    if (($row['user_type'] ?? '') === 'candidate') {
                        $monthlyCandidates[$m] = (int)$row['total'];
                    } elseif (($row['user_type'] ?? '') === 'employer') {
                        $monthlyEmployers[$m] = (int)$row['total'];
                    }
                }
                return [
                    'candidates' => array_values($monthlyCandidates),
                    'employers'  => array_values($monthlyEmployers),
                ];
            })(),
            'transactionGrowth' => (function() use ($year) {
                $db = db_connect();
                $paymentStats = $db->table('payments')
                    ->select('MONTH(created_at) AS month, SUM(amount_paid) AS total')
                    ->where('YEAR(created_at)', $year)
                    ->where('status', 'paid')
                    ->groupBy('MONTH(created_at)')
                    ->get()->getResultArray();

                $monthlyTransactions = array_fill(1, 12, 0);
                foreach ($paymentStats as $row) {
                    $monthlyTransactions[(int)$row['month']] = (float)$row['total'];
                }
                return array_values($monthlyTransactions);
            })(),
        ];
        return view('admin/dashboard', $data);
    }

    /**
     * Global admin search (header search bar) — returns grouped JSON results.
     */
    public function globalSearch()
    {
        $q = trim((string) $this->request->getGet('q'));
        if (mb_strlen($q) < 2) {
            return $this->response->setJSON(['results' => []]);
        }

        $db = \Config\Database::connect();

        $jobs = $db->table('jobs')
            ->select('jobs.id, jobs.title, jobs.status, employers.company_name')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->like('jobs.title', $q)
            ->orderBy('jobs.created_at', 'DESC')
            ->get(5)->getResult();

        $candidates = $db->table('job_seekers')
            ->select("job_seekers.id, job_seekers.full_name, ai.secret AS email")
            ->join("auth_identities ai", "ai.user_id = job_seekers.user_id AND ai.type = 'email_password'", 'left')
            ->groupStart()->like('job_seekers.full_name', $q)->orLike('ai.secret', $q)->groupEnd()
            ->orderBy('job_seekers.id', 'DESC')
            ->get(5)->getResult();

        $employers = $db->table('employers')
            ->select("employers.id, employers.company_name, ai.secret AS email")
            ->join("auth_identities ai", "ai.user_id = employers.user_id AND ai.type = 'email_password'", 'left')
            ->groupStart()->like('employers.company_name', $q)->orLike('ai.secret', $q)->groupEnd()
            ->orderBy('employers.id', 'DESC')
            ->get(5)->getResult();

        $results = [];
        foreach ($jobs as $j) {
            $results[] = [
                'group' => 'Jobs',
                'label' => $j->title,
                'sub'   => trim(($j->company_name ?? '') . ($j->status ? " · {$j->status}" : '')),
                'url'   => base_url('admin/jobs/view/' . $j->id),
            ];
        }
        foreach ($candidates as $c) {
            $results[] = [
                'group' => 'Candidates',
                'label' => $c->full_name ?: ($c->email ?? 'Candidate #' . $c->id),
                'sub'   => $c->email ?? '',
                'url'   => base_url('admin/candidates/view/' . $c->id),
            ];
        }
        foreach ($employers as $e) {
            $results[] = [
                'group' => 'Employers',
                'label' => $e->company_name ?: ($e->email ?? 'Employer #' . $e->id),
                'sub'   => $e->email ?? '',
                'url'   => base_url('admin/employers/view/' . $e->id),
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }

    /**
     * Toggle global theme setting
     */
    public function toggleTheme()
    {
        $themePath = WRITEPATH . 'theme_setting.json';
        $currentTheme = 'default';
        
        if (file_exists($themePath)) {
            $data = json_decode(file_get_contents($themePath), true);
            $currentTheme = $data['theme'] ?? 'default';
        }
        
        $newTheme = ($currentTheme === 'midnight-aura') ? 'default' : 'midnight-aura';
        
        file_put_contents($themePath, json_encode(['theme' => $newTheme]));
        
        return $this->response->setJSON([
            'success' => true,
            'theme' => $newTheme,
            'message' => 'Site theme updated to ' . $newTheme
        ]);
    }

    public function profile()
    {
        return view('admin/profile', [
            'title' => 'My Profile',
            'user'  => $this->auth->user(),
            'admin' => $this->admin, // your current admin entity
        ]);
    }

    public function updateProfile()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $adminModel = model(AdminModel::class);
        $updated = $adminModel->update($this->admin->id, [
            'full_name' => trim($this->request->getPost('full_name')),
        ]);

        if ($updated) {
            // Refresh session admin data
            $this->admin = $adminModel->find($this->admin->id);
            $this->auth->getAuthenticator()->getSession()->set('admin', $this->admin);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update profile'
        ]);
    }

    public function changePassword()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]|strong_password',
            'confirm_password' => 'required|matches[new_password]',
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
        if (! $this->auth->getAuthenticator()->check([
            'password' => $this->request->getPost('current_password')
        ])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Current password is incorrect'
            ]);
        }

        // Update password via Shield
        $user->fill(['password' => $this->request->getPost('new_password')]);
        $userModel = model(UserModel::class);
        $saved = $userModel->save($user);

        if ($saved) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to change password'
        ]);
    }

    // Candidates
    public function candidates()
    {
        $filters = [
            'keyword'             => $this->request->getGet('keyword'),
            'state_id'            => $this->request->getGet('state') ?? $this->request->getGet('state_id'),
            'employment_type'     => $this->request->getGet('employment_type') ?? $this->request->getGet('job_type'),
            'experience_years'    => $this->request->getGet('experience') ?? $this->request->getGet('experience_years'),
            'job_title'           => $this->request->getGet('job_title'),
            'availability'        => $this->request->getGet('availability'),
            'education_level'     => $this->request->getGet('education_level'),
            'resume_status'       => $this->request->getGet('resume_status') ?? 'all',
            'verification_status' => $this->request->getGet('verification_status') ?? $this->request->getGet('status') ?? 'all',
            'visibility'          => $this->request->getGet('visibility') ?? 'all',
            'sort'                => $this->request->getGet('sort') ?? 'newest',
        ];

        $candidates = $this->jobSeekerModel->getCandidates($filters, 20, true);
        $candidateStats = $this->jobSeekerModel->getCandidateStats();
        $states = $this->stateModel->orderBy('name', 'ASC')->findAll();

        $data = [
            'title'               => 'Candidate Management',
            'user'                => $this->auth->user(),
            'admin'               => $this->admin,
            'candidates'          => $candidates,
            'pager'               => $this->jobSeekerModel->pager,
            'total'               => $this->jobSeekerModel->pager->getTotal(),
            'candidateStats'      => $candidateStats,
            'states'              => $states,
            'filters'             => $filters,
            'jobTitleCounts'      => $this->jobSeekerModel->countByJobTitle(),
            'availabilityCounts'  => $this->jobSeekerModel->countByAvailability(),
            'jobTypeCounts'       => $this->jobSeekerModel->countByEmploymentType(),
            'educationCounts'     => $this->jobSeekerModel->countByEducation(),
        ];

        if ($this->request->isAJAX()) {
            return view('admin/partials/candidates_results', [
                'candidates' => $candidates,
                'pager'      => $this->jobSeekerModel->pager,
                'filters'    => $filters,
            ]);
        }

        return view('admin/candidates', $data);
    }

    public function filterCandidates()
    {
        return $this->candidates();
    }

    /**
     * Toggle candidate verification status
     */
    public function toggleCandidateVerification($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Method not allowed']);
        }

        $candidate = $this->jobSeekerModel->find($id);
        if (!$candidate) {
            return $this->response->setJSON(['success' => false, 'message' => 'Candidate not found']);
        }

        $newStatus = empty($candidate->is_verified) ? 1 : 0;
        $this->jobSeekerModel->update($id, ['is_verified' => $newStatus]);

        return $this->response->setJSON([
            'success'     => true,
            'is_verified' => $newStatus,
            'message'     => $newStatus ? 'Candidate verified successfully' : 'Candidate unverified'
        ]);
    }

    // Single Candidate
    public function viewCandidate(int $id)
    {
        $candidate = $this->jobSeekerModel
            ->select('
                job_seekers.*,
                auth_identities.secret as email,
                states.name AS state_name,
                users.username,
                users.active as user_active
            ')
            ->join('users', 'users.id = job_seekers.user_id', 'left')
            ->join('states', 'states.id = job_seekers.state_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = job_seekers.user_id AND auth_identities.type = "email_password"', 'left')
            ->where('job_seekers.id', $id)
            ->first();

        if (!$candidate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Candidate not found');
        }

        // Industries
        $industries = model('JobSeekerIndustryModel')
            ->select('industries.name')
            ->join('industries', 'industries.id = job_seeker_industries.industry_id')
            ->where('job_seeker_id', $id)
            ->findAll();

        // Applications
        $db = \Config\Database::connect();
        $applications = [];
        if ($db->tableExists('job_applications')) {
            $applications = $db->table('job_applications')
                ->select('job_applications.*, jobs.title as job_title, employers.company_name')
                ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                ->where('job_applications.job_seeker_id', $id)
                ->orderBy('job_applications.created_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        // Course enrollments
        $courses = [];
        if ($db->tableExists('course_enrollments')) {
            $courses = $db->table('course_enrollments')
                ->select('course_enrollments.*, courses.title as course_title')
                ->join('courses', 'courses.id = course_enrollments.course_id', 'left')
                ->where('course_enrollments.user_id', $candidate->user_id)
                ->orderBy('course_enrollments.created_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        return view('admin/candidate', [
            'title'        => $candidate->full_name,
            'user'         => $this->auth->user(),
            'admin'        => $this->admin,
            'candidate'    => $candidate,
            'industries'   => $industries,
            'applications' => $applications,
            'courses'      => $courses,
        ]);
    }

    public function shortlistCandidate($id)
    {
        model('CandidateShortlistModel')->insert([
            'admin_id'      => $this->auth->user()->id,
            'job_seeker_id' => $id
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function downloadCv($id)
    {
        $candidate = $this->jobSeekerModel->find($id);
        if (!$candidate || empty($candidate->resume)) {
            return redirect()->back()->with('error', 'Resume not available for this candidate');
        }

        $path = FCPATH . ltrim($candidate->resume, '/');
        if (file_exists($path)) {
            return $this->response->download($path, null);
        }

        if (str_starts_with($candidate->resume, 'http://') || str_starts_with($candidate->resume, 'https://')) {
            return redirect()->to($candidate->resume);
        }

        return redirect()->back()->with('error', 'Resume file not found');
    }

    // Employers
    public function employers()
    {
        $employerModel = model(\App\Models\EmployerModel::class);
        $verificationStatus = $this->request->getGet('status') ?? 'all';
        $docSubmittedSql = '(EXISTS (SELECT 1 FROM employer_documents WHERE employer_documents.employer_id = employers.id) OR (employers.verification_doc IS NOT NULL AND employers.verification_doc != "") OR (employers.verification_documents IS NOT NULL AND employers.verification_documents != "" AND employers.verification_documents != "[]"))';

        // Build query with filters
        $builder = $employerModel
            ->select([
                'employers.*',
                'MAX(states.name) AS state_name',
                'COALESCE(MAX(CAST(auth_identities.secret AS CHAR)), MAX(CAST(employers.contact_email AS CHAR))) AS email',
                'MAX(users.username) AS username',
                'GROUP_CONCAT(DISTINCT industries.name SEPARATOR ", ") AS industries',
                'COUNT(DISTINCT jobs.id) AS total_jobs',
                'COUNT(DISTINCT employer_documents.id) AS submitted_docs_count'
            ])
            ->join('users', 'users.id = employers.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = employers.user_id', 'left')
            ->join('states', 'states.id = employers.state_id', 'left')
            ->join('employer_industries', 'employer_industries.employer_id = employers.id', 'left')
            ->join('industries', 'industries.id = employer_industries.industry_id', 'left')
            ->join('jobs', 'jobs.employer_id = employers.id', 'left')
            ->join('employer_documents', 'employer_documents.employer_id = employers.id', 'left')
            ->groupBy('employers.id');

        // Apply verification status filter
        if ($verificationStatus === 'pending') {
            $builder->where('employers.verification_status', 'pending')
                    ->where($docSubmittedSql, null, false);
        } elseif ($verificationStatus === 'unverified' || $verificationStatus === 'no_documents') {
            $builder->where('(employers.verification_status = "unverified" OR (employers.verification_status = "pending" AND NOT ' . $docSubmittedSql . '))', null, false)
                    ->where('employers.verification_status !=', 'verified')
                    ->where('employers.verification_status !=', 'rejected');
        } elseif ($verificationStatus !== 'all') {
            $builder->where('employers.verification_status', $verificationStatus);
        }

        $employers = $builder->paginate(20);

        if ($this->request->isAJAX()) {
            return view('admin/partials/employers_results', [
                'employers' => $employers,
                'pager'     => $employerModel->pager
            ]);
        }

        // Get verification stats
        $verificationStats = $employerModel->getVerificationStats();

        $data = [
            'title'             => 'Employers',
            'user'              => $this->auth->user(),
            'admin'             => $this->admin,
            'employers'         => $employers,
            'pager'             => $employerModel->pager,
            'total'             => $employerModel->pager->getTotal(),
            'industryCounts'    => $this->industryCounts(),
            'verificationStats' => $verificationStats,
            'currentStatus'     => $verificationStatus
        ];

        return view('admin/employers', $data);
    }
    
    /**
     * Sidebar industry counts (cached)
     */
    protected function industryCounts(): array
    {
        return $this->industryModel
            ->select('industries.id, industries.name, COUNT(ei.employer_id) AS total')
            ->join('employer_industries ei', 'ei.industry_id = industries.id', 'left')
            ->groupBy('industries.id')
            ->orderBy('industries.name', 'ASC')
            ->findAll();
    }

    // Add AJAX filter for employers
    public function filterEmployers()
    {
        $employerModel = model(\App\Models\EmployerModel::class);

        $industries = $this->request->getGet('industries') ?? [];
        $verificationStatus = $this->request->getGet('status') ?? 'all';
        $page = $this->request->getGet('page') ?? 1;
        $docSubmittedSql = '(EXISTS (SELECT 1 FROM employer_documents WHERE employer_documents.employer_id = employers.id) OR (employers.verification_doc IS NOT NULL AND employers.verification_doc != "") OR (employers.verification_documents IS NOT NULL AND employers.verification_documents != "" AND employers.verification_documents != "[]"))';

        $builder = $employerModel
            ->select([
                'employers.*',
                'MAX(states.name) AS state_name',
                'COALESCE(MAX(CAST(auth_identities.secret AS CHAR)), MAX(CAST(employers.contact_email AS CHAR))) AS email',
                'GROUP_CONCAT(DISTINCT industries.name SEPARATOR ", ") AS industries',
                'COUNT(DISTINCT jobs.id) AS total_jobs',
                'COUNT(DISTINCT employer_documents.id) AS submitted_docs_count'
            ])
            ->join('users', 'users.id = employers.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = employers.user_id', 'left')
            ->join('states', 'states.id = employers.state_id', 'left')
            ->join('employer_industries', 'employer_industries.employer_id = employers.id', 'left')
            ->join('industries', 'industries.id = employer_industries.industry_id', 'left')
            ->join('jobs', 'jobs.employer_id = employers.id', 'left')
            ->join('employer_documents', 'employer_documents.employer_id = employers.id', 'left')
            ->groupBy('employers.id');

        // Apply verification status filter
        if ($verificationStatus === 'pending') {
            $builder->where('employers.verification_status', 'pending')
                    ->where($docSubmittedSql, null, false);
        } elseif ($verificationStatus === 'unverified' || $verificationStatus === 'no_documents') {
            $builder->where('(employers.verification_status = "unverified" OR (employers.verification_status = "pending" AND NOT ' . $docSubmittedSql . '))', null, false)
                    ->where('employers.verification_status !=', 'verified')
                    ->where('employers.verification_status !=', 'rejected');
        } elseif ($verificationStatus !== 'all') {
            $builder->where('employers.verification_status', $verificationStatus);
        }

        // Apply industry filter
        if (!empty($industries)) {
            $builder->whereIn('employer_industries.industry_id', $industries);
        }

        $employers = $builder->paginate(20, 'default', $page);

        return view('admin/partials/employers_results', [
            'employers' => $employers,
            'pager' => $employerModel->pager
        ]);
    }

    // Add verification action methods
    public function verifyEmployer($employerId)
    {
        if ($this->request->getMethod() === 'POST') {
            $employerModel = model(EmployerModel::class);
            $notes = $this->request->getPost('notes');

            if ($employerModel->verifyEmployer($employerId, $this->admin->id, $notes)) {
                // Send approval email
                try {
                    $employer = $employerModel->find($employerId);
                    if ($employer) {
                        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);
                        $user = $userModel->find($employer->user_id);
                        
                        if ($user) {
                            $email = \Config\Services::email();
                            $email->setTo($user->email);
                            $email->setSubject('Your Employer Account Has Been Verified - JobberRecruit');
                            
                            $emailData = [
                                'company_name' => $employer->company_name,
                                'contact_name' => $employer->contact_person ?? $user->username,
                                'verification_date' => date('Y-m-d H:i:s')
                            ];
                            
                            $email->setMessage(view('emails/verification_approved', $emailData));
                            $email->send();
                        }
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send verification approval email: ' . $e->getMessage());
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Employer verified successfully'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to verify employer'
        ]);
    }

    public function rejectEmployer($employerId)
    {
        if ($this->request->getMethod() === 'POST') {
            $employerModel = model(EmployerModel::class);
            $reason = $this->request->getPost('reason');

            if ($employerModel->rejectEmployer($employerId, $this->admin->id, $reason)) {
                // Send rejection email
                try {
                    $employer = $employerModel->find($employerId);
                    if ($employer) {
                        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);
                        $user = $userModel->find($employer->user_id);
                        
                        if ($user) {
                            $email = \Config\Services::email();
                            $email->setTo($user->email);
                            $email->setSubject('Verification Update - JobberRecruit');
                            
                            $emailData = [
                                'company_name' => $employer->company_name,
                                'contact_name' => $employer->contact_person ?? $user->username,
                                'review_date' => date('Y-m-d H:i:s'),
                                'rejection_reason' => $reason ?? 'Documents did not meet our verification requirements.'
                            ];
                            
                            $email->setMessage(view('emails/verification_rejected', $emailData));
                            $email->send();
                        }
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send verification rejection email: ' . $e->getMessage());
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Employer rejected'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to reject employer'
        ]);
    }

    public function viewEmployerDocuments($employerId)
    {
        $documentModel = model(EmployerDocumentModel::class);
        $documents = $documentModel->where('employer_id', $employerId)->findAll();

        return view('admin/partials/employer_documents', ['documents' => $documents]);
    }

    /**
     * Verify a document (AJAX)
     */
    public function verifyDocument($documentId)
    {
        if ($this->request->getMethod() === 'POST') {
            $documentModel = model(EmployerDocumentModel::class);
            $document = $documentModel->find($documentId);

            if (!$document) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Document not found'
                ]);
            }

            // Update document status
            $documentModel->update($documentId, [
                'status' => 'approved',
                'verified_at' => date('Y-m-d H:i:s'),
                'verified_by' => $this->admin->id
            ]);

            // Check if employer has all documents approved
            $employerModel = model(EmployerModel::class);
            $pendingDocs = $documentModel
                ->where('employer_id', $document['employer_id'])
                ->where('status', 'pending')
                ->countAllResults();

            // If no pending documents, mark employer as verified
            if ($pendingDocs == 0) {
                $employerModel->update($document['employer_id'], [
                    'verification_status' => 'verified',
                    'is_verified' => 1,
                    'verified_at' => date('Y-m-d H:i:s'),
                    'verified_by' => $this->admin->id
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Document verified successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }

    /**
     * Reject a document (AJAX)
     */
    public function rejectDocument($documentId)
    {
        if ($this->request->getMethod() === 'POST') {
            $documentModel = model(EmployerDocumentModel::class);
            $document = $documentModel->find($documentId);

            if (!$document) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Document not found'
                ]);
            }

            // Get rejection reason from request
            $data = $this->request->getJSON();
            $reason = $data->reason ?? null;

            // Update document status
            $documentModel->update($documentId, [
                'status' => 'rejected',
                'verified_at' => date('Y-m-d H:i:s'),
                'verified_by' => $this->admin->id
            ]);

            // Update employer verification status
            $employerModel = model(EmployerModel::class);
            $employerModel->update($document['employer_id'], [
                'verification_status' => 'rejected',
                'rejection_reason' => $reason,
                'is_verified' => 0
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Document rejected'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }


    public function viewEmployer(int $id)
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel
            ->select([
                'employers.*',
                'states.name AS state_name',
                'COUNT(DISTINCT jobs.id) AS total_jobs'
            ])
            ->join('states', 'states.id = employers.state_id', 'left')
            ->join('jobs', 'jobs.employer_id = employers.id', 'left')
            ->where('employers.id', $id)
            ->groupBy('employers.id')
            ->first();

        if (! $employer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get CAC document
        $documentModel = model(EmployerDocumentModel::class);
        $cacDocument = $documentModel
            ->where('employer_id', $id)
            ->where('document_type', 'cac_certificate')
            ->first();

        // Industries (pivot)
        $industries = model(EmployerIndustryModel::class)
            ->select('industries.name')
            ->join('industries', 'industries.id = employer_industries.industry_id')
            ->where('employer_industries.employer_id', $id)
            ->findAll();

        // Recent Jobs
        $jobs = model(JobModel::class)
            ->where('employer_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        // Check unlimited access
        $hasUnlimitedAccess = $this->hasUnlimitedAccess($employer->id);

        return view('admin/employer', [
            'title'      => $employer->company_name,
            'user'       => $this->auth->user(),
            'admin'      => $this->admin,
            'employer'   => $employer,
            'industries' => $industries,
            'jobs'       => $jobs,
            'cacDocument' => $cacDocument,
            'hasUnlimitedAccess' => $hasUnlimitedAccess,
        ]);
    }

    /**
     * Check unlimited access (helper)
     */
    private function hasUnlimitedAccess($employerId)
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->find($employerId);

        if (!$employer) {
            return false;
        }

        if ($employer->unlimited_access == 1) {
            if (empty($employer->unlimited_until) || strtotime($employer->unlimited_until) > time()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Toggle unlimited access for employer
     */
    public function toggleUnlimitedAccess()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getJSON();
            $employerId = $data->employer_id;
            $enabled = $data->enabled;

            $employerModel = model(EmployerModel::class);
            $employerModel->update($employerId, [
                'unlimited_access' => $enabled ? 1 : 0
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => $enabled ? 'Unlimited access granted' : 'Unlimited access revoked'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid request'
        ]);
    }

    /**
     * Update unlimited access expiry
     */
    public function updateUnlimitedExpiry()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getJSON();
            $employerId = $data->employer_id;
            $unlimitedUntil = $data->unlimited_until;

            $employerModel = model(EmployerModel::class);
            $employerModel->update($employerId, [
                'unlimited_until' => $unlimitedUntil ?: null
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Expiry date updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid request'
        ]);
    }

    public function applications()
    {
        $applications = $this->jobApplicationModel
            ->select([
                'job_applications.*',
                'jobs.title AS job_title',
                'employers.company_name',
            ])
            ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->orderBy('job_applications.created_at', 'DESC')
            ->paginate(20);

        return view('admin/applications', [
            'title'        => 'Job Applications',
            'user'         => $this->auth->user(),
            'admin'        => $this->admin,
            'applications' => $applications,
            'pager'        => $this->jobApplicationModel->pager,
            'statusCounts' => $this->jobApplicationModel->countByStatus(),
        ]);
    }

    /**
     * Single application view
     */
    public function viewApplication(int $id)
    {
        $application = $this->jobApplicationModel
            ->select([
                'job_applications.*',
                'jobs.title AS job_title',
                'employers.company_name',
            ])
            ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('job_applications.id', $id)
            ->first();

        if (! $application) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get pre-screening answers
        $answerModel = model(\App\Models\ApplicationAnswerModel::class);
        $answers = $answerModel->select('application_answers.*, job_questions.question_text as question, job_questions.question_type as type, job_questions.options')
            ->join('job_questions', 'job_questions.id = application_answers.question_id')
            ->where('application_answers.application_id', $id)
            ->findAll();

        return view('admin/application', [
            'title'       => 'Application Details',
            'user'        => $this->auth->user(),
            'admin'       => $this->admin,
            'application' => $application,
            'answers'     => $answers,
        ]);
    }

    public function locations()
    {

        if($this->request->getMethod() === 'POST'){
            $id = $this->request->getPost('id');
            $data = [
                'name' => trim($this->request->getPost('name')),
                'capital' => trim($this->request->getPost('capital')),
                'region' => $this->request->getPost('region'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            // Validate
            if (!$this->validateData($data, $this->stateModel->validationRules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => implode('<br>', $this->validator->getErrors())
                ]);
            }

            try {
                if ($id) {
                    if ($this->stateModel->update($id, $data)) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Location updated successfully'
                        ]);
                    }
                } else {
                    if ($this->stateModel->insert($data)) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Location added successfully'
                        ]);
                    }
                }

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save location'
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
        }

        return view('admin/locations', [
            'title' => 'Location Management',
            'user'  => $this->auth->user(),
            'admin' => $this->admin,
            'states' => $this->stateModel->getStatesWithStats(),
            'regions' => $this->stateModel->getRegions()
        ]);
    }

    public function deleteLocation($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Check if location has employers
        $employerCount = $this->stateModel->db->table('employers')
            ->where('state_id', $id)
            ->countAllResults();

        // Check if location has jobs
        $jobCount = $this->stateModel->db->table('jobs')
            ->where('state_id', $id)
            ->countAllResults();

        if ($employerCount > 0 || $jobCount > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete location that has employers or jobs'
            ]);
        }

        if ($this->stateModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Location deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete location'
        ]);
    }

    public function toggleLocationStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $id = $this->request->getVar('id');
        $isActive = $this->request->getVar('is_active');

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid location ID'
            ]);
        }

        $data = ['is_active' => $isActive ? 1 : 0];

        if ($this->stateModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update status'
        ]);
    }

    public function locationBulkAction()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $action = $this->request->getVar('action');
        $ids = $this->request->getVar('ids');

        if (empty($ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No locations selected'
            ]);
        }

        switch ($action) {
            case 'activate':
                $data = ['is_active' => 1];
                $message = 'locations activated';
                break;

            case 'deactivate':
                $data = ['is_active' => 0];
                $message = 'locations deactivated';
                break;

            case 'delete':
                // Check if any location has employers or jobs
                foreach ($ids as $id) {
                    $employerCount = $this->stateModel->db->table('employers')
                        ->where('state_id', $id)
                        ->countAllResults();

                    if ($employerCount > 0) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Cannot delete locations that have employers'
                        ]);
                    }
                }

                $this->stateModel->delete($ids);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Selected locations deleted'
                ]);

            default:
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }

        $this->stateModel->update($ids, $data);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Selected ' . $message
        ]);
    }

    public function categories()
    {
        if($this->request->getMethod() === 'POST'){
            $id = $this->request->getPost('id');
            $data = [
                'name' => trim($this->request->getPost('name')),
                'slug' => trim($this->request->getPost('slug')),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            // Generate slug if empty
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = $this->categoryModel->generateSlug($data['name']);
            }

            // Validate
            if (!$this->validateData($data, $this->categoryModel->validationRules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => implode('<br>', $this->validator->getErrors())
                ]);
            }

            try {
                if ($id) {
                    if ($this->categoryModel->update($id, $data)) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Category updated successfully'
                        ]);
                    }
                } else {
                    if ($this->categoryModel->insert($data)) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Category added successfully'
                        ]);
                    }
                }

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save category'
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
        }
        return view('admin/categories', [
            'title' => 'Categories',
            'user'  => $this->auth->user(),
            'admin' => $this->admin,
            'categories' => $this->categoryModel->getCategoriesWithStats()
        ]);
    }

    public function deleteCategory($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Check if category has jobs
        $jobCount = $this->categoryModel->db->table('jobs')
            ->where('category_id', $id)
            ->countAllResults();

        // Check if category has employers
        $employerCount = $this->categoryModel->db->table('employers')
            ->where('category_id', $id)
            ->countAllResults();

        if ($jobCount > 0 || $employerCount > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete category that has jobs or employers'
            ]);
        }

        if ($this->categoryModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete category'
        ]);
    }

    public function toggleCategoryStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $id = $this->request->getVar('id');
        $isActive = $this->request->getVar('is_active');

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid category ID'
            ]);
        }

        $data = ['is_active' => $isActive ? 1 : 0];

        if ($this->categoryModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update status'
        ]);
    }

    public function categoryBulkAction()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $action = $this->request->getVar('action');
        $ids = $this->request->getVar('ids');

        if (empty($ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No categories selected'
            ]);
        }

        switch ($action) {
            case 'activate':
                $data = ['is_active' => 1];
                $message = 'Categories activated';
                break;

            case 'deactivate':
                $data = ['is_active' => 0];
                $message = 'Categories deactivated';
                break;

            case 'delete':
                // Check if any category has jobs or employers
                foreach ($ids as $id) {
                    $jobCount = $this->categoryModel->db->table('jobs')
                        ->where('category_id', $id)
                        ->countAllResults();

                    if ($jobCount > 0) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Cannot delete categories that have jobs'
                        ]);
                    }
                }

                $this->categoryModel->delete($ids);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Selected categories deleted'
                ]);

            default:
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }

        $this->categoryModel->update($ids, $data);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Selected ' . $message
        ]);
    }

    public function industries()
    {
        if($this->request->getMethod() === 'POST') {
            $id = $this->request->getPost('id');
            $data = [
                'name' => trim($this->request->getPost('name')),
                'slug' => trim($this->request->getPost('slug')),
                'parent_id' => $this->request->getPost('parent_id') ?: null,
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            // Generate slug if empty
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = $this->industryModel->generateSlug($data['name']);
            }

            // Validate
            if (!$this->validateData($data, $this->industryModel->validationRules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => implode('<br>', $this->validator->getErrors())
                ]);
            }

            // Check for self-parenting
            if ($id && $data['parent_id'] == $id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot set industry as its own parent'
                ]);
            }

            try {
                if ($id) {
                    if ($this->industryModel->update($id, $data)) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Industry updated successfully'
                        ]);
                    }
                } else {
                    if ($this->industryModel->insert($data)) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Industry added successfully'
                        ]);
                    }
                }

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save industry'
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
        }
        return view('admin/industries', [
            'title' => 'Industries Management',
            'user'  => $this->auth->user(),
            'admin' => $this->admin,
            'industries' => $this->industryModel->getIndustriesWithStats(),
            'parentIndustries' => $this->industryModel->getParentIndustries()
        ]);
    }

    public function deleteIndustry($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Check if industry has children
        $childCount = $this->industryModel->where('parent_id', $id)->countAllResults();

        if ($childCount > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete industry that has sub-industries. Please delete or reassign sub-industries first.'
            ]);
        }

        // Check if industry has employers
        $employerCount = $this->industryModel->db->table('employer_industries')
            ->where('industry_id', $id)
            ->countAllResults();

        // Check if industry has job seekers
        $jobSeekerCount = $this->industryModel->db->table('job_seeker_industries')
            ->where('industry_id', $id)
            ->countAllResults();

        if ($employerCount > 0 || $jobSeekerCount > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete industry that has employers or job seekers'
            ]);
        }

        if ($this->industryModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Industry deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete industry'
        ]);
    }

    public function toggleIndustryStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $id = $this->request->getVar('id');
        $isActive = $this->request->getVar('is_active');

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid industry ID'
            ]);
        }

        $data = ['is_active' => $isActive ? 1 : 0];

        if ($this->industryModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update status'
        ]);
    }

    public function industryBulkAction()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $action = $this->request->getVar('action');
        $ids = $this->request->getVar('ids');

        if (empty($ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No industries selected'
            ]);
        }

        switch ($action) {
            case 'activate':
                $data = ['is_active' => 1];
                $message = 'Industries activated';
                break;

            case 'deactivate':
                $data = ['is_active' => 0];
                $message = 'Industries deactivated';
                break;

            case 'delete':
                // Check if any industry has children
                foreach ($ids as $id) {
                    $childCount = $this->industryModel->where('parent_id', $id)->countAllResults();
                    if ($childCount > 0) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Cannot delete industries that have sub-industries'
                        ]);
                    }
                }

                $this->industryModel->delete($ids);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Selected industries deleted'
                ]);

            default:
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }

        $this->industryModel->update($ids, $data);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Selected ' . $message
        ]);
    }

    /**
     * Jobs listing page
     */
    public function jobs()
    {
        $status = $this->request->getGet('status');

        $stats = [
            'total'            => $this->jobModel->countAll(),
            'open'             => $this->jobModel->where('status', 'open')->where('admin_status', 'approved')->countAllResults(),
            'pending_approval' => $this->jobModel->where('admin_status', 'pending')->countAllResults(),
            'closed'           => $this->jobModel->where('status', 'closed')->countAllResults(),
            'rejected'         => $this->jobModel->where('admin_status', 'rejected')->countAllResults(),
            'deleted'          => $this->jobModel->where('status', 'deleted')->countAllResults(),
        ];

        $builder = $this->jobModel
            ->select([
                'jobs.*',
                'employers.company_name',
                'states.name AS state_name',
                'COUNT(job_applications.id) AS applications_count'
            ])
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_applications', 'job_applications.job_id = jobs.id', 'left')
            ->groupBy('jobs.id');

        if ($status) {
            if ($status === 'pending_approval' || $status === 'pending') {
                $builder->where('jobs.admin_status', 'pending');
            } elseif ($status === 'rejected') {
                $builder->where('jobs.admin_status', 'rejected');
            } elseif ($status === 'open' || $status === 'active') {
                $builder->where('jobs.status', 'open')->where('jobs.admin_status', 'approved');
            } elseif ($status === 'closed') {
                $builder->where('jobs.status', 'closed');
            } elseif ($status === 'deleted') {
                $builder->where('jobs.status', 'deleted');
            } else {
                $builder->where('jobs.status', $status);
            }
        }

        $jobs = $builder->orderBy('jobs.created_at', 'DESC')->paginate(20);

        return view('admin/jobs', [
            'title'          => 'Jobs',
            'user'           => $this->auth->user(),
            'admin'          => $this->admin,
            'jobs'           => $jobs,
            'pager'          => $this->jobModel->pager,
            'stats'          => $stats,
            'selectedStatus' => $status ?? '',
        ]);
    }

    /**
     * Update job status (admin action)
     */
    public function updateStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $jobModel = new JobModel();
        $job = $jobModel->find($id);

        if (!$job) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Job not found'
            ]);
        }

        $newStatus = $this->request->getPost('status');
        $currentStatus = $job->status;
        $currentAdminStatus = $job->admin_status;

        // Define allowed transitions
        $allowedTransitions = [
            'pending_approval' => ['open', 'rejected', 'closed'],
            'open'             => ['closed', 'pending_approval'],
            'closed'           => ['open', 'pending_approval'],
            'rejected'         => ['open', 'pending_approval', 'closed'],
        ];

        // Handle admin status transitions
        if ($currentAdminStatus === 'pending') {
            if ($newStatus === 'open') {
                $updateData = [
                    'status' => 'open',
                    'admin_status' => 'approved',
                    'admin_reviewed_at' => date('Y-m-d H:i:s')
                ];
            } elseif ($newStatus === 'rejected') {
                $updateData = [
                    'status' => 'closed',
                    'admin_status' => 'rejected',
                    'admin_reviewed_at' => date('Y-m-d H:i:s')
                ];
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid status transition for pending job'
                ]);
            }
        } else {
            // Regular status transition
            if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid status transition'
                ]);
            }

            $updateData = ['status' => $newStatus];

            // If reopening a rejected job, reset admin status to approved
            if ($currentAdminStatus === 'rejected' && $newStatus === 'open') {
                $updateData['admin_status'] = 'approved';
            }
        }

        $jobModel->update($id, $updateData);

        // Send notification to employer about status change
        $this->sendJobStatusNotification($job, $newStatus);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Job status updated successfully'
        ]);
    }

    /**
     * Send job status notification to employer
     */
    private function sendJobStatusNotification($job, $newStatus)
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->find($job->employer_id);

        if (!$employer) return;

        // Create in-app notification
        $notificationModel = model(JobNotificationModel::class);

        $statusMessages = [
            'open' => ['title' => 'Job Approved', 'message' => "Your job '{$job->title}' has been approved and is now live."],
            'rejected' => ['title' => 'Job Rejected', 'message' => "Your job '{$job->title}' was not approved. Please check your email for details."],
            'closed' => ['title' => 'Job Closed', 'message' => "Your job '{$job->title}' has been closed by admin."]
        ];

        if (isset($statusMessages[$newStatus])) {
            $notificationModel->createNotification(
                $job->employer_id,
                $newStatus === 'open' ? 'job_approved' : ($newStatus === 'rejected' ? 'job_rejected' : 'job_closed'),
                $statusMessages[$newStatus]['title'],
                $statusMessages[$newStatus]['message'],
                $job->id
            );
        }

        // Send email notifications
        $userModel = model(UserModel::class);
        $user = $userModel->find($employer->user_id);
        $recipientEmail = $employer->contact_email ?? ($user->email ?? null);

        if ($recipientEmail) {
            $emailService = service('mailer');
            if ($newStatus === 'open') {
                $emailService->sendTemplate(
                    $recipientEmail,
                    'Job Approved - ' . $job->title,
                    'emails/job_approved',
                    [
                        'employer_name' => $employer->company_name ?? 'Employer',
                        'job_title'     => $job->title,
                        'job_url'       => base_url('jobs/' . $job->id),
                        'platform_name' => config('App')->appName ?? 'JobberRecruit'
                    ]
                );
            } elseif ($newStatus === 'rejected') {
                $emailService->sendTemplate(
                    $recipientEmail,
                    'Job Update - ' . $job->title,
                    'emails/job_rejected',
                    [
                        'employer_name' => $employer->company_name ?? 'Employer',
                        'job_title'     => $job->title,
                        'reason'        => 'Your job posting did not meet our guidelines. Please contact support for more information.',
                        'platform_name' => config('App')->appName ?? 'JobberRecruit'
                    ]
                );
            }
        }
    }

    /**
     * Filter jobs via AJAX
     */
    public function filterJobs()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $status    = $this->request->getGet('status');
        $fromDate  = $this->request->getGet('from');
        $toDate    = $this->request->getGet('to');
        $page      = $this->request->getGet('page') ?? 1;

        $builder = $this->jobModel
            ->select('jobs.*, employers.company_name, states.name AS state_name,
              COUNT(job_applications.id) AS applications_count')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_applications', 'job_applications.job_id = jobs.id', 'left')
            ->groupBy('jobs.id');

        // Filter by status (can be regular status or admin_status)
        if ($status) {
            if ($status === 'pending_approval' || $status === 'pending') {
                $builder->where('jobs.admin_status', 'pending');
            } elseif ($status === 'rejected') {
                $builder->where('jobs.admin_status', 'rejected');
            } elseif ($status === 'open' || $status === 'active') {
                $builder->where('jobs.status', 'open')->where('jobs.admin_status', 'approved');
            } elseif ($status === 'closed') {
                $builder->where('jobs.status', 'closed');
            } elseif ($status === 'deleted') {
                $builder->where('jobs.status', 'deleted');
            } else {
                $builder->where('jobs.status', $status);
            }
        }

        if ($fromDate && $toDate) {
            $builder->where('DATE(jobs.created_at) >=', $fromDate)
                ->where('DATE(jobs.created_at) <=', $toDate);
        }

        $jobs = $builder->paginate(20, 'default', $page);

        return view('admin/partials/jobs_results', [
            'jobs'  => $jobs,
            'pager' => $this->jobModel->pager,
        ]);
    }

    /**
     * Delete job (AJAX)
     */
    public function deleteJob()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $jobId = $this->request->getPost('job_id');
        $job = $this->jobModel->find($jobId);

        if (!$job) {
            return $this->response->setJSON(['success' => false, 'message' => 'Job not found']);
        }

        // Delete associated applications first
        $applicationModel = model(JobApplicationModel::class);
        $applicationModel->where('job_id', $jobId)->delete();

        // Delete job
        $this->jobModel->delete($jobId);

        return $this->response->setJSON(['success' => true, 'message' => 'Job deleted successfully']);
    }


    public function performanceChart($jobId)
    {
        $stats = model('JobApplicationModel')
            ->select('MONTH(created_at) m, COUNT(*) total')
            ->where('job_id', $jobId)
            ->groupBy('MONTH(created_at)')
            ->findAll();

        return $this->response->setJSON($stats);
    }


    /**
     * Single Job View
     */
    // public function viewJob(int $id)
    // {
    //     $job = $this->jobModel
    //         ->select([
    //             'jobs.*',
    //             'employers.company_name',
    //             'states.name AS state_name'
    //         ])
    //         ->join('employers', 'employers.id = jobs.employer_id', 'left')
    //         ->join('states', 'states.id = jobs.state_id', 'left')
    //         ->where('jobs.id', $id)
    //         ->first();

    //     if (! $job) {
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    //     }

    //     $applicationModel = model(JobApplicationModel::class);

    //     $applications = $applicationModel
    //         ->where('job_id', $id)
    //         ->orderBy('created_at', 'DESC')
    //         ->findAll();

    //     $applicationsCount = count($applications);

    //     // BASIC ANALYTICS
    //     $clickModel = model(JobClickModel::class);

    //     $analytics = [
    //         'views'        => $job->views ?? 0,
    //         'applications' => $applicationsCount,
    //         'clicks'       => $clickModel->totalClicks($job->id),
    //         'conversion'   => $job->views
    //             ? round(($applicationsCount / $job->views) * 100, 2)
    //             : 0,
    //     ];

    //     return view('admin/job', [
    //         'title' => 'Job Details',
    //         'user'  => $this->auth->user(),
    //         'admin' => $this->admin,
    //         'job'   => $job,
    //         'applications'       => $applications,
    //         'applicationsCount'  => $applicationsCount,
    //         'analytics'          => $analytics,
    //     ]);
    // }

    /**
     * Single Job View with Approval Actions
     */
    public function viewJob(int $id)
    {
        $job = $this->jobModel
            ->select([
                'jobs.*',
                'employers.company_name',
                'employers.id as employer_id',
                'states.name AS state_name'
            ])
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->where('jobs.id', $id)
            ->first();

        if (! $job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $applicationModel = model(JobApplicationModel::class);
        $applications = $applicationModel
            ->where('job_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $applicationsCount = count($applications);

        // Basic Analytics
        $clickModel = model(JobClickModel::class);
        $analytics = [
            'views'        => $job->views ?? 0,
            'applications' => $applicationsCount,
            'clicks'       => $clickModel->totalClicks($job->id),
            'conversion'   => $job->views ? round(($applicationsCount / $job->views) * 100, 2) : 0,
        ];

        // Get daily analytics for chart
        $dailyAnalytics = $this->getJobDailyAnalytics($id);

        return view('admin/job', [
            'title'          => 'Job Details',
            'user'           => $this->auth->user(),
            'admin'          => $this->admin,
            'job'            => $job,
            'applications'   => $applications,
            'applicationsCount' => $applicationsCount,
            'analytics'      => $analytics,
            'dailyAnalytics' => $dailyAnalytics,
        ]);
    }

    /**
     * Approve Job (AJAX)
     */
    public function approveJob()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $jobId = $this->request->getPost('job_id');
        $job = $this->jobModel->find($jobId);

        if (!$job) {
            return $this->response->setJSON(['success' => false, 'message' => 'Job not found']);
        }

        // Update job status
        $this->jobModel->update($jobId, [
            'admin_status' => 'approved',
            'admin_reviewed_at' => date('Y-m-d H:i:s'),
            'status' => 'open'
        ]);

        // Get employer info
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->find($job->employer_id);

        // Get credit service and employer statistics
        $creditService = new \App\Services\CreditService();
        $creditBalance = $creditService->getAvailableCredits($employer->user_id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($employer->user_id);
        $currentPlan = $creditService->getCurrentPlan($employer->user_id);

        // Get job statistics for this employer
        $jobModel = model(JobModel::class);
        $totalJobsPosted = $jobModel->where('employer_id', $employer->id)->countAllResults();
        $pendingJobs = $jobModel->where('employer_id', $employer->id)
            ->where('admin_status', 'pending')
            ->countAllResults();
        $approvedJobs = $jobModel->where('employer_id', $employer->id)
            ->where('admin_status', 'approved')
            ->countAllResults();

        // Get active subscription info
        $subscriptionModel = model(UserSubscriptionModel::class);
        $activeSubscription = $subscriptionModel
            ->where('user_id', $employer->user_id)
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->first();

        $planName = null;
        $subscriptionEndsAt = null;
        if ($activeSubscription) {
            $planModel = model(PlanModel::class);
            $plan = $planModel->find($activeSubscription->plan_id);
            $planName = $plan ? $plan->name : null;
            $subscriptionEndsAt = $activeSubscription->ends_at;
        }

        // Create notification for employer
        $notificationModel = model(JobNotificationModel::class);
        $notificationModel->createNotification(
            $job->employer_id,
            'job_approved',
            'Job Approved - ' . $job->title,
            "Your job '{$job->title}' has been approved and is now live on our platform.",
            $jobId
        );

        // Send email notification to employer
        $emailService = service('mailer');

        // Determine notification email address (preference or fallback)
        $jobPreferences = is_string($job->notification_preferences)
            ? json_decode($job->notification_preferences, true)
            : ($job->notification_preferences ?? []);

        $notificationEmail = $jobPreferences['notification_email_address'] ?? $employer->contact_email;

        if ($notificationEmail) {
            $emailService->sendTemplate(
                $notificationEmail,
                'Job Approved - ' . $job->title,
                'emails/job_approved',
                [
                    'employer_name' => $employer->company_name,
                    'job_title' => $job->title,
                    'job_url' => base_url('jobs/' . ($job->slug ?? $job->id)),
                    'credits_balance' => $creditBalance,
                    'has_unlimited' => $hasUnlimitedAccess,
                    'plan_name' => $planName,
                    'total_posted' => $totalJobsPosted,
                    'pending_jobs' => $pendingJobs,
                    'approved_jobs' => $approvedJobs,
                    'platform_name' => config('App')->appName ?? 'JobberRecruit'
                ]
            );
        }

        // Dispatch immediate job alerts to matching candidates
        try {
            (new \App\Services\JobAlertService())->sendImmediateMatchAlerts((int) $jobId);
        } catch (\Throwable $e) {
            log_message('error', 'Immediate job alert dispatch failed for job #' . $jobId . ': ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Job approved successfully'
        ]);
    }

    /**
     * Bulk Approve Jobs (AJAX)
     */
    public function bulkApproveJobs()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $jobIds = $this->request->getPost('job_ids');
        if (empty($jobIds) || !is_array($jobIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No jobs selected']);
        }

        $approvedCount = 0;
        $now = date('Y-m-d H:i:s');
        $notificationModel = model(JobNotificationModel::class);

        foreach ($jobIds as $jobId) {
            $job = $this->jobModel->find((int)$jobId);
            if (!$job) continue;

            $this->jobModel->update($job->id, [
                'admin_status'      => 'approved',
                'admin_reviewed_at' => $now,
                'status'            => 'open'
            ]);

            $notificationModel->createNotification(
                $job->employer_id,
                'job_approved',
                'Job Approved - ' . $job->title,
                "Your job '{$job->title}' has been approved and is now live on our platform.",
                $job->id
            );

            // Dispatch immediate job alerts to matching candidates
            try {
                (new \App\Services\JobAlertService())->sendImmediateMatchAlerts((int) $job->id);
            } catch (\Throwable $e) {
                log_message('error', 'Immediate job alert dispatch failed for job #' . $job->id . ': ' . $e->getMessage());
            }

            $approvedCount++;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "{$approvedCount} job(s) approved successfully!"
        ]);
    }

    /**
     * Bulk Reject Jobs (AJAX)
     */
    public function bulkRejectJobs()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $jobIds = $this->request->getPost('job_ids');
        $reason = trim((string)($this->request->getPost('reason') ?? 'Does not meet platform posting guidelines.'));

        if (empty($jobIds) || !is_array($jobIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No jobs selected']);
        }

        $rejectedCount = 0;
        $now = date('Y-m-d H:i:s');
        $notificationModel = model(JobNotificationModel::class);

        foreach ($jobIds as $jobId) {
            $job = $this->jobModel->find((int)$jobId);
            if (!$job) continue;

            $this->jobModel->update($job->id, [
                'admin_status'      => 'rejected',
                'admin_reviewed_at' => $now,
                'status'            => 'closed'
            ]);

            $notificationModel->createNotification(
                $job->employer_id,
                'job_rejected',
                'Job Rejection Notice - ' . $job->title,
                "Your job '{$job->title}' was rejected. Reason: " . substr($reason, 0, 100),
                $job->id
            );

            $rejectedCount++;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "{$rejectedCount} job(s) rejected."
        ]);
    }

    /**
     * Unapprove Job (AJAX)
     */
    public function unapproveJob()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $jobId = $this->request->getPost('job_id');
        $job = $this->jobModel->find($jobId);

        if (!$job) {
            return $this->response->setJSON(['success' => false, 'message' => 'Job not found']);
        }

        // Update job status to pending & closed
        $this->jobModel->update($jobId, [
            'admin_status'      => 'pending',
            'admin_reviewed_at' => date('Y-m-d H:i:s'),
            'status'            => 'closed'
        ]);

        // Send notification to employer
        $notificationModel = model(JobNotificationModel::class);
        $notificationModel->createNotification(
            $job->employer_id,
            'job_unapproved',
            'Job Status Updated - ' . $job->title,
            "Your job '{$job->title}' status has been set back to pending review by an administrator.",
            $jobId
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Job unapproved successfully'
        ]);
    }

    /**
     * Bulk Unapprove Jobs (AJAX)
     */
    public function bulkUnapproveJobs()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $jobIds = $this->request->getPost('job_ids');
        if (empty($jobIds) || !is_array($jobIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No jobs selected']);
        }

        $unapprovedCount = 0;
        $now = date('Y-m-d H:i:s');
        $notificationModel = model(JobNotificationModel::class);

        foreach ($jobIds as $jobId) {
            $job = $this->jobModel->find((int)$jobId);
            if (!$job) continue;

            $this->jobModel->update($job->id, [
                'admin_status'      => 'pending',
                'admin_reviewed_at' => $now,
                'status'            => 'closed'
            ]);

            $notificationModel->createNotification(
                $job->employer_id,
                'job_unapproved',
                'Job Status Updated - ' . $job->title,
                "Your job '{$job->title}' status has been set back to pending review by an administrator.",
                $job->id
            );

            $unapprovedCount++;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "{$unapprovedCount} job(s) unapproved successfully!"
        ]);
    }

    /**
     * Preview Job on Candidate/Public Frontend (Before & After Live)
     */
    public function previewJob(int $id)
    {
        $job = $this->jobModel->find($id);
        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $targetUrl = base_url('jobs/' . ($job->slug ?? $job->id)) . '?admin_preview=1';
        return redirect()->to($targetUrl);
    }

    /**
     * Verify Job (AJAX)
     */
    public function verifyJob()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $jobId = $this->request->getPost('job_id');
        $this->jobModel->update($jobId, ['is_verified' => 1]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Job marked as verified'
        ]);
    }

    /**
     * Unverify Job (AJAX)
     */
    public function unverifyJob()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $jobId = $this->request->getPost('job_id');
        $this->jobModel->update($jobId, ['is_verified' => 0]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Job verification removed'
        ]);
    }

    /**
     * Reject Job (AJAX)
     */
    public function rejectJob()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $jobId = $this->request->getPost('job_id');
        $reason = $this->request->getPost('reason');

        if (empty($reason)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please provide a rejection reason']);
        }

        $job = $this->jobModel->find($jobId);

        if (!$job) {
            return $this->response->setJSON(['success' => false, 'message' => 'Job not found']);
        }

        // Get employer info
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->find($job->employer_id);

        // Get job statistics for this employer
        $jobModel = model(JobModel::class);
        $totalJobsPosted = $jobModel->where('employer_id', $employer->id)->countAllResults();
        $pendingJobs = $jobModel->where('employer_id', $employer->id)
            ->where('admin_status', 'pending')
            ->countAllResults();
        $approvedJobs = $jobModel->where('employer_id', $employer->id)
            ->where('admin_status', 'approved')
            ->countAllResults();
        $rejectedJobs = $jobModel->where('employer_id', $employer->id)
            ->where('admin_status', 'rejected')
            ->countAllResults();

        // Get credit service and employer statistics
        $creditService = new \App\Services\CreditService();
        $creditBalance = $creditService->getAvailableCredits($employer->user_id);
        $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($employer->user_id);
        $currentPlan = $creditService->getCurrentPlan($employer->user_id);

        // Get active subscription info
        $subscriptionModel = model(UserSubscriptionModel::class);
        $activeSubscription = $subscriptionModel
            ->where('user_id', $employer->user_id)
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->first();

        $planName = null;
        $subscriptionEndsAt = null;
        if ($activeSubscription) {
            $planModel = model(PlanModel::class);
            $plan = $planModel->find($activeSubscription->plan_id);
            $planName = $plan ? $plan->name : null;
            $subscriptionEndsAt = $activeSubscription->ends_at;
        }

        // Update job status
        $this->jobModel->update($jobId, [
            'admin_status' => 'rejected',
            'admin_reviewed_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $reason,
            'status' => 'closed'
        ]);

        // Create in-app notification for employer
        $notificationModel = model(JobNotificationModel::class);
        $notificationModel->createNotification(
            $job->employer_id,
            'job_rejected',
            'Job Update - ' . $job->title,
            "Your job '{$job->title}' was not approved. Reason: " . substr($reason, 0, 100),
            $jobId
        );

        // Send email notification to employer
        $emailService = service('mailer');

        // Determine notification email address (preference or fallback)
        $jobPreferences = is_string($job->notification_preferences)
            ? json_decode($job->notification_preferences, true)
            : ($job->notification_preferences ?? []);

        $notificationEmail = $jobPreferences['notification_email_address'] ?? $employer->contact_email;

        $emailService->sendTemplate(
            $notificationEmail,
            'Job Update - ' . $job->title,
            'emails/job_rejected',
            [
                'employer_name' => $employer->company_name,
                'employer_logo' => $employer->logo,
                'job_title' => $job->title,
                'job_id' => $jobId,
                'job_created_at' => date('M d, Y', strtotime($job->created_at)),
                'rejection_reason' => $reason,
                'platform_name' => config('App')->appName ?? 'JobberRecruit',
                'edit_job_url' => base_url('employer/jobs/edit/' . $jobId),
                'dashboard_url' => base_url('employer/dashboard'),
                'jobs_url' => base_url('employer/jobs'),
                'pricing_url' => base_url('employer/pricing'),
                'support_url' => base_url('contact-us'),

                // Account information
                'credit_balance' => $creditBalance,
                'has_unlimited_access' => $hasUnlimitedAccess,
                'current_plan' => $planName ?? ($hasUnlimitedAccess ? 'Unlimited Access' : 'No Active Plan'),
                'subscription_ends_at' => $subscriptionEndsAt,

                // Job statistics
                'total_jobs_posted' => $totalJobsPosted,
                'pending_jobs' => $pendingJobs,
                'approved_jobs' => $approvedJobs,
                'rejected_jobs' => $rejectedJobs,
                'current_job_status' => 'rejected'
            ]
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Job rejected successfully',
            'status' => 'rejected'
        ]);
    }

    /**
     * Get job daily analytics for chart
     */
    private function getJobDailyAnalytics($jobId)
    {
        $db = db_connect();
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $label = date('M d', strtotime($date));

            // Count applications for this day
            $applications = $db->table('job_applications')
                ->where('job_id', $jobId)
                ->where('DATE(created_at)', $date)
                ->countAllResults();

            // Count views for this day (from job_clicks table)
            $views = $db->table('job_clicks')
                ->where('job_id', $jobId)
                ->where('DATE(created_at)', $date)
                ->countAllResults();

            $data[] = [
                'date' => $label,
                'applications' => $applications,
                'views' => $views
            ];
        }

        return $data;
    }

    public function editJob(int $id)
    {
        $job = $this->jobModel->find($id);

        if (! $job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/job-edit', [
            'title'      => 'Edit Job Post',
            'job'        => $job,
            'employers'  => model('EmployerModel')->orderBy('company_name', 'ASC')->findAll(),
            'states'     => model('StateModel')->findAll(),
            'industries' => model('IndustryModel')->findAll(),
            'categories' => model('JobCategoryModel')->findAll(),
            'user'       => $this->auth->user(),
            'admin'      => $this->admin,
        ]);
    }

    public function updateJob(int $id)
    {
        $job = $this->jobModel->find($id);
        if (!$job) {
            return $this->response->setJSON(['success' => false, 'message' => 'Job not found']);
        }

        $postData = $this->request->getPost();

        // Parse Tagify JSON skills if present
        if (isset($postData['skills']) && is_string($postData['skills']) && strpos($postData['skills'], '[{') !== false) {
            $decoded = json_decode($postData['skills'], true);
            if (is_array($decoded)) {
                $postData['skills'] = implode(', ', array_filter(array_column($decoded, 'value')));
            }
        }

        $actionType = $postData['action_type'] ?? 'save';
        $wasAlreadyApproved = ($job->admin_status === 'approved');

        if ($actionType === 'save_approve') {
            $postData['admin_status'] = 'approved';
            $postData['status']       = 'open';
        }

        // Auto update admin_reviewed_at if status updated
        if (isset($postData['admin_status']) && $postData['admin_status'] !== $job->admin_status) {
            $postData['admin_reviewed_at'] = date('Y-m-d H:i:s');
            if ($postData['admin_status'] === 'approved') {
                $postData['status'] = 'open';
            }
        }

        unset($postData['action_type']);
        $this->jobModel->update($id, $postData);

        // If job was just approved during edit, trigger approval notifications
        if (!$wasAlreadyApproved && isset($postData['admin_status']) && $postData['admin_status'] === 'approved') {
            try {
                $employerModel = model(EmployerModel::class);
                $employer = $employerModel->find($job->employer_id);

                if ($employer) {
                    $notificationModel = model(JobNotificationModel::class);
                    $notificationModel->createNotification(
                        $job->employer_id,
                        'job_approved',
                        'Job Approved - ' . ($postData['title'] ?? $job->title),
                        "Your job '" . ($postData['title'] ?? $job->title) . "' has been approved and is now live on our platform.",
                        $id
                    );

                    $creditService = new \App\Services\CreditService();
                    $creditBalance = $creditService->getAvailableCredits($employer->user_id);
                    $hasUnlimitedAccess = $creditService->hasUnlimitedAccess($employer->user_id);

                    $jobPreferences = is_string($job->notification_preferences)
                        ? json_decode($job->notification_preferences, true)
                        : ($job->notification_preferences ?? []);
                    $notificationEmail = $jobPreferences['notification_email_address'] ?? $employer->contact_email;

                    if ($notificationEmail) {
                        $emailService = service('mailer');
                        $emailService->sendTemplate(
                            $notificationEmail,
                            'Job Approved - ' . ($postData['title'] ?? $job->title),
                            'emails/job_approved',
                            [
                                'employer_name' => $employer->company_name,
                                'job_title'     => $postData['title'] ?? $job->title,
                                'job_url'       => base_url('jobs/' . ($job->slug ?? $job->id)),
                                'credits_balance' => $creditBalance,
                                'has_unlimited' => $hasUnlimitedAccess,
                                'platform_name' => config('App')->appName ?? 'JobberRecruit'
                            ]
                        );
                    }
                }
            } catch (\Throwable $ex) {
                log_message('error', 'Error sending job approval email during edit: ' . $ex->getMessage());
            }
        }

        $msg = ($actionType === 'save_approve' || (isset($postData['admin_status']) && $postData['admin_status'] === 'approved')) 
            ? 'Job saved and approved successfully!' 
            : 'Job updated successfully';

        return $this->response->setJSON([
            'success' => true,
            'message' => $msg
        ]);
    }

    public function updateApplicationStatus()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $data = $this->request->getJSON(true);

        model(\App\Models\JobApplicationModel::class)
            ->update($data['id'], [
                'status' => $data['status'],
            ]);

        return $this->response->setJSON(['success' => true]);
    }

    // Add to your existing admin controller
    public function grantUnlimitedAccess()
    {
        if ($this->request->getMethod() === 'POST') {
            $employerId = $this->request->getPost('employer_id');
            $unlimitedUntil = $this->request->getPost('unlimited_until');

            $employerModel = model(EmployerModel::class);
            $employer = $employerModel->find($employerId);

            if (!$employer) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Employer not found'
                ]);
            }

            $data = [
                'unlimited_access' => 1,
                'unlimited_until' => $unlimitedUntil ?: null
            ];

            $employerModel->update($employerId, $data);

            // Log the action
            log_message('info', "Unlimited access granted to employer {$employer->company_name} (ID: {$employerId})");

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Unlimited access granted successfully'
            ]);
        }
    }

    public function revokeUnlimitedAccess()
    {
        $employerId = $this->request->getJSON()->employer_id;

        $employerModel = model(EmployerModel::class);
        $employerModel->update($employerId, [
            'unlimited_access' => 0,
            'unlimited_until' => null
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Unlimited access revoked'
        ]);
    }

    public function plans()
    {
        $planModel = model(PlanModel::class);

        if ($this->request->getMethod() === 'POST') {
            $id = $this->request->getPost('id');
            $name = trim($this->request->getPost('name'));
            $code = trim($this->request->getPost('code')) ?: strtolower(str_replace(' ', '_', $name));
            $planType = $this->request->getPost('plan_type') ?: 'employer';
            $basePrice = (float) ($this->request->getPost('base_price') ?? 0);
            $monthlyCredits = (int) ($this->request->getPost('monthly_job_credits') ?? 0);
            $duration = (int) ($this->request->getPost('duration') ?? 30);

            if ($planType === 'candidate') {
                $candidateFeaturesRaw = $this->request->getPost('candidate_features') ?? '';
                $candidateFeaturesList = array_filter(array_map('trim', explode(',', $candidateFeaturesRaw)));
                $features = [];
                foreach ($candidateFeaturesList as $f) {
                    $features[$f] = true;
                }
            } else {
                $features = [
                    'featured' => (bool) $this->request->getPost('feat_featured'),
                    'network_blast' => (bool) $this->request->getPost('feat_network_blast'),
                    'anonymous' => (bool) $this->request->getPost('feat_anonymous'),
                    'trust_badge' => (bool) $this->request->getPost('feat_trust_badge'),
                    'priority_support' => (bool) $this->request->getPost('feat_priority_support'),
                    'url_redirect' => (bool) $this->request->getPost('feat_url_redirect'),
                    'ai_resume' => (bool) $this->request->getPost('feat_ai_resume'),
                    'ai_cover_letter' => (bool) $this->request->getPost('feat_ai_cover_letter'),
                    'ai_career_tools' => (bool) $this->request->getPost('feat_ai_career_tools'),
                    'unlimited_applications' => (bool) $this->request->getPost('feat_unlimited_applications'),
                    'candidate_messaging' => (bool) $this->request->getPost('feat_candidate_messaging'),
                    'profile_highlight' => (bool) $this->request->getPost('feat_profile_highlight'),
                    'unlimited_job_postings' => (bool) $this->request->getPost('feat_unlimited_job_postings'),
                ];
            }

            $data = [
                'name' => $name,
                'code' => $code,
                'plan_type' => $planType,
                'base_price' => $basePrice,
                'monthly_job_credits' => $monthlyCredits,
                'features' => json_encode($features),
                'is_active' => (int) ($this->request->getPost('is_active') ?? 1),
            ];

            if ($id) {
                $planModel->update($id, $data);
            } else {
                $planModel->insert($data);
            }

            return redirect()->back()->with('success', 'Plan saved successfully');
        }

        $subscriptionModel = model(UserSubscriptionModel::class);
        $employerModel = model(EmployerModel::class);
        $candidateModel = model(JobSeekerModel::class);
        $bundleModel = model(PlanBundleModel::class);

        $employerPlans = $planModel->where('plan_type', 'employer')->orderBy('base_price', 'ASC')->findAll();
        
        // Auto-seed default candidate search plans if empty
        if (empty($employerPlans)) {
            $defaultSearchPlans = [
                ['name' => 'Monthly Search Plan', 'code' => 'monthly_search', 'base_price' => 150000.00, 'billing_type' => 'subscription', 'plan_type' => 'employer', 'monthly_job_credits' => 10, 'features' => json_encode(['candidate_search_credits' => 10, 'duration_months' => 1, 'candidate_messaging' => true, 'priority_support' => true]), 'is_active' => 1],
                ['name' => 'Quarterly Search Plan', 'code' => 'quarterly_search', 'base_price' => 250000.00, 'billing_type' => 'subscription', 'plan_type' => 'employer', 'monthly_job_credits' => 35, 'features' => json_encode(['candidate_search_credits' => 35, 'duration_months' => 3, 'candidate_messaging' => true, 'priority_support' => true]), 'is_active' => 1],
                ['name' => 'Semi-Annual Search Plan', 'code' => 'semi_annual_search', 'base_price' => 450000.00, 'billing_type' => 'subscription', 'plan_type' => 'employer', 'monthly_job_credits' => 80, 'features' => json_encode(['candidate_search_credits' => 80, 'duration_months' => 6, 'candidate_messaging' => true, 'priority_support' => true]), 'is_active' => 1],
                ['name' => 'Annual Search Plan', 'code' => 'annual_search', 'base_price' => 750000.00, 'billing_type' => 'subscription', 'plan_type' => 'employer', 'monthly_job_credits' => 180, 'features' => json_encode(['candidate_search_credits' => 180, 'duration_months' => 12, 'candidate_messaging' => true, 'priority_support' => true]), 'is_active' => 1],
            ];
            foreach ($defaultSearchPlans as $dsp) {
                try {
                    $planModel->insert($dsp);
                } catch (\Throwable $e) {}
            }
            $employerPlans = $planModel->where('plan_type', 'employer')->orderBy('base_price', 'ASC')->findAll();
        }
        $candidatePlans = $planModel->where('plan_type', 'candidate')->orderBy('base_price', 'ASC')->findAll();
        $bundles = $bundleModel->orderBy('job_credits', 'ASC')->findAll();

        $subscriptions = $subscriptionModel
            ->asObject()
            ->select('user_subscriptions.*, plans.name AS plan_name, plans.plan_type, employers.id AS employer_id, employers.company_name, employers.user_id AS emp_user_id, job_seekers.id AS candidate_id, job_seekers.full_name AS candidate_name, job_seekers.user_id AS cand_user_id, users.username, users.first_name, users.last_name, users.user_type, auth_identities.secret AS user_email')
            ->join('plans', 'plans.id = user_subscriptions.plan_id', 'left')
            ->join('employers', 'employers.user_id = user_subscriptions.user_id', 'left')
            ->join('job_seekers', 'job_seekers.user_id = user_subscriptions.user_id', 'left')
            ->join('users', 'users.id = user_subscriptions.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = user_subscriptions.user_id', 'left')
            ->orderBy('user_subscriptions.ends_at', 'ASC')
            ->findAll();

        $planStats = $subscriptionModel
            ->select('plans.name, plans.plan_type, COUNT(user_subscriptions.id) AS total')
            ->join('plans', 'plans.id = user_subscriptions.plan_id')
            ->groupBy('plans.id')
            ->findAll();

        $db = db_connect();
        $year = date('Y');
        $revenue = $db->table('payments')
            ->select('MONTH(created_at) AS month, SUM(amount_paid) AS total')
            ->where('YEAR(created_at)', $year)
            ->where('status', 'paid')
            ->groupBy('MONTH(created_at)')
            ->get()->getResultArray();

        $monthlyRevenue = array_fill(1, 12, 0);
        foreach ($revenue as $row) {
            $monthlyRevenue[(int)$row['month']] = (float)$row['total'];
        }

        $employersWithUnlimited = $employerModel
            ->where('unlimited_access', 1)
            ->findAll();

        $allEmployers = $employerModel->findAll();
        $allCandidates = $candidateModel->findAll();

        $isFreeMode = is_site_free_mode();

        return view('admin/plans', [
            'title' => 'Plans & Subscriptions',
            'user' => $this->auth->user(),
            'admin' => $this->admin,
            'employerPlans' => $employerPlans,
            'candidatePlans' => $candidatePlans,
            'bundles' => $bundles,
            'subscriptions' => $subscriptions,
            'planStats' => $planStats,
            'monthlyRevenue' => $monthlyRevenue,
            'employersWithUnlimited' => $employersWithUnlimited,
            'allEmployers' => $allEmployers,
            'allCandidates' => $allCandidates,
            'isFreeMode' => $isFreeMode,
        ]);
    }

    public function deletePlan($id)
    {
        $planModel = model(PlanModel::class);
        $plan = $planModel->find($id);

        if (!$plan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Plan not found']);
        }

        $activeSubs = model(UserSubscriptionModel::class)
            ->where('plan_id', $id)
            ->where('is_active', 1)
            ->countAllResults();

        if ($activeSubs > 0) {
            return $this->response->setJSON(['success' => false, 'message' => "Cannot delete: {$activeSubs} active subscriptions"]);
        }

        $planModel->delete($id);
        return $this->response->setJSON(['success' => true, 'message' => 'Plan deleted']);
    }

    public function toggleFreeMode()
    {
        if (!auth()->user()->inGroup('admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $current = is_site_free_mode();
        $newValue = !$current;

        set_site_setting('site_free_mode', $newValue ? 'true' : 'false');

        return $this->response->setJSON([
            'success'    => true,
            'isFreeMode' => $newValue,
            'message'    => "Site Free Access " . ($newValue ? 'enabled' : 'disabled') . ' (Saved to Database Configuration)'
        ]);
    }

    public function features()
    {
        $data = [
            'title'                     => 'Feature Management',
            'feature_webinars'          => get_site_setting('feature_webinars', true),
            'feature_elearning'         => get_site_setting('feature_elearning', true),
            'feature_ai_resume'         => get_site_setting('feature_ai_resume', true),
            'feature_ai_career_tools'   => get_site_setting('feature_ai_career_tools', true),
            'ai_tools_paid_mode'        => is_ai_tools_paid_mode(),
            'feature_messaging'         => get_site_setting('feature_messaging', true),
            'feature_referrals'         => get_site_setting('feature_referrals', true),
            'email_use_queue'           => get_site_setting('email_use_queue', true),
            'site_free_mode'            => is_site_free_mode(),
            'cv_review_pro_price'       => (int) get_site_setting('cv_review_pro_price', 15000),
            'cv_review_prem_price'      => (int) get_site_setting('cv_review_prem_price', 30000),
            'cv_review_mode'            => get_site_setting('cv_review_mode', 'semi'),
        ];

        return view('admin/feature_settings', $data);
    }

    public function saveFeatures()
    {
        $keys = [
            'feature_webinars',
            'feature_elearning',
            'feature_ai_resume',
            'feature_ai_career_tools',
            'ai_tools_paid_mode',
            'feature_messaging',
            'feature_referrals',
            'email_use_queue',
            'site_free_mode',
            'cv_review_pro_price',
            'cv_review_prem_price',
            'cv_review_mode',
        ];

        foreach ($keys as $key) {
            if ($key === 'cv_review_mode') {
                $val = $this->request->getPost($key) === 'auto' ? 'auto' : 'semi';
            } elseif ($key === 'cv_review_pro_price' || $key === 'cv_review_prem_price') {
                $val = (int)$this->request->getPost($key);
            } else {
                $val = $this->request->getPost($key) ? 'true' : 'false';
            }
            set_site_setting($key, (string)$val);
        }

        return redirect()->back()->with('success', 'Feature settings updated and saved to Database Configuration.');
    }

    /**
     * Chatbot Settings Page
     */
    public function chatbotSettings()
    {
        $data = [
            'title' => 'Chatbot Management',
            'chatbot_enabled' => get_site_setting('chatbot_enabled', true),
            'chatbot_welcome_message' => get_site_setting('chatbot_welcome_message', 'Hello! How can I help you today?'),
            'chatbot_suggestions' => get_site_setting('chatbot_suggestions', 'Browse Jobs,Post a Job,Resume Builder,Career Advice'),
        ];

        return view('admin/chatbot_settings', $data);
    }

    /**
     * Save Chatbot Settings
     */
    public function saveChatbotSettings()
    {
        $updates = [
            'chatbot_enabled' => $this->request->getPost('chatbot_enabled') ? 'true' : 'false',
            'chatbot_welcome_message' => trim($this->request->getPost('chatbot_welcome_message') ?? 'Hello! How can I help you today?'),
            'chatbot_suggestions' => trim($this->request->getPost('chatbot_suggestions') ?? 'Browse Jobs,Post a Job,Resume Builder,Career Advice'),
        ];

        foreach ($updates as $key => $value) {
            set_site_setting($key, (string)$value);
        }

        return redirect()->back()->with('success', 'Chatbot settings updated and saved to Database Configuration.');
    }


    public function bundles()
    {
        $bundleModel = model(PlanBundleModel::class);

        if ($this->request->getMethod() === 'POST') {

            $id       = $this->request->getPost('id');
            $name     = trim($this->request->getPost('name'));
            // $code     = trim($this->request->getPost('code'));
            $slug     = trim($this->request->getPost('slug'));
            $credits  = (int) $this->request->getPost('job_credits');   // Changed to match your model
            $price    = (float) $this->request->getPost('price');
            $cost     = (float) $this->request->getPost('price_per_credit');
            $isBest   = (bool) $this->request->getPost('is_best_value');

            // Basic validation
            if ($credits <= 0 || $price <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Credits and Price must be greater than zero.'
                ]);
            }

            if (empty($name) || empty($slug)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Name and Code are required.'
                ]);
            }

            if ($id) {
                $data = [
                    'name'            => $name,
                    'slug'            => $slug ?: strtolower(str_replace(' ', '-', $name)),
                    // 'code'            => $code,
                    'price'           => $price,
                    'job_credits'     => $credits,
                    'price_per_credit' => $cost ? round($cost, 2) : round($price / $credits, 2),
                    'is_best_value'   => $isBest,
                    'is_active'       => 1
                ];
                $bundleModel->update($id, $data);
            } else {
                $data = [
                    'name'            => $name,
                    'slug'            => $slug ?: strtolower(str_replace(' ', '-', $name)),
                    // 'code'            => $code,
                    'price'           => $price,
                    'job_credits'     => $credits,
                    'price_per_credit' => $cost ? round($cost, 2) : round($price / $credits, 2),
                    'is_best_value'   => $isBest,
                    'is_active'       => 1
                ];
                $bundleModel->insert($data);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Bundle saved successfully'
            ]);
        }

        /* -------------------------------------------------
     * GET - Show Bundles Page
     * ------------------------------------------------- */
        return redirect()->to(base_url('admin/plans#bundleTab'));
    }

    public function assignPlan()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $userId = $this->request->getPost('user_id') ?: $this->request->getPost('employer_id');
        $startsAt = $this->request->getPost('starts_at') ?: $this->request->getPost('start_date');
        $endsAt = $this->request->getPost('ends_at') ?: $this->request->getPost('end_date');

        if (!$userId || !$startsAt) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID and Start Date are required'
            ]);
        }

        $planModel = model(PlanModel::class);
        $subModel  = model(UserSubscriptionModel::class);

        $plan = $planModel->find($this->request->getPost('plan_id'));
        if (!$plan) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Plan not found'
            ]);
        }

        // 🚫 Block paid plans
        if (($plan->base_price ?? 0) > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Paid plans must be purchased via Paystack'
            ]);
        }

        // Deactivate existing subscription
        $subModel->where('user_id', $userId)
            ->set(['is_active' => 0])
            ->update();

        // Assign new subscription
        $subModel->insert([
            'user_id'    => $userId,
            'plan_id'    => $plan->id,
            'starts_at'  => $startsAt,
            'ends_at'    => $endsAt ?: null,
            'is_active'  => 1,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Subscription assigned successfully'
        ]);
    }

    // Blogs
    public function blogs()
    {
        helper('text');

        $blogModel = new BlogModel();
        $data = [
            'title' => 'Blogs',
            'user' => $this->auth->user(),
            'admin' => $this->admin,
            'blogs' => $blogModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('admin/blogs', $data);
    }

    public function createBlog()
    {
        $data = [
            'title' => 'Create Blog',
            'user' => $this->auth->user(),
            'admin' => $this->admin,
            'blog' => null
        ];
        return view('admin/blog_editor', $data);
    }

    public function editBlog($id)
    {
        $model = new BlogModel();
        $blog = $model->find($id);
        if (!$blog) {
            return redirect()->to('admin/blogs')->with('error', 'Blog not found');
        }
        $data = [
            'title' => 'Edit Blog',
            'user' => $this->auth->user(),
            'admin' => $this->admin,
            'blog' => $blog
        ];
        return view('admin/blog_editor', $data);
    }

    public function saveBlog()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        // -------------------------------------------------
        // 1. Validate thumbnail
        // -------------------------------------------------
        $rules = [
            'thumbnail' => [
                'label' => 'Thumbnail',
                'rules' => 'if_exist|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png,image/webp]|max_size[thumbnail,2048]',
            ],
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $this->validator->getError('thumbnail'),
            ]);
        }

        $model = new BlogModel();
        $allowed = ['id', 'title', 'slug', 'content', 'excerpt', 'meta_title', 'meta_description', 'meta_keywords', 'status', 'category_id', 'tags', 'is_featured', 'existing_thumbnail'];
        $data  = $this->request->getPost($allowed);
        $data['admin_id'] = $this->admin->id;

        // -------------------------------------------------
        // 2. SLUG UNIQUENESS (AUTO-APPEND)
        // -------------------------------------------------
        $baseSlug = $data['slug'];
        $slug     = $baseSlug;
        $counter  = 0;

        while (
            $model->where('slug', $slug)
            ->where('id !=', $data['id'] ?? 0)
            ->countAllResults() > 0
        ) {
            $counter++;
            $slug = $baseSlug . '-' . substr(bin2hex(random_bytes(3)), 0, 5);
        }

        $data['slug'] = $slug;

        // -------------------------------------------------
        // 3. Thumbnail Upload
        // -------------------------------------------------
        $file         = $this->request->getFile('thumbnail');
        $oldThumbnail = $data['existing_thumbnail'] ?? null;

        if ($file && $file->isValid() && ! $file->hasMoved()) {

            // ---------------- CLOUDINARY (PRIMARY) ----------------
            if (
                env('CLOUDINARY_NAME') &&
                env('CLOUDINARY_API_KEY') &&
                env('CLOUDINARY_API_SECRET')
            ) {
                $cloudinary = new \Cloudinary\Cloudinary([
                    'cloud' => [
                        'cloud_name' => env('CLOUDINARY_NAME'),
                        'api_key'    => env('CLOUDINARY_API_KEY'),
                        'api_secret' => env('CLOUDINARY_API_SECRET'),
                    ],
                ]);

                $upload = $cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'blogs',
                        'resource_type' => 'image',
                        'format' => 'webp',
                        'quality' => 'auto',
                        'verify' => false
                    ]
                );

                $data['thumbnail'] = $upload['secure_url'];
            } else {
                // ---------------- LOCAL FALLBACK ----------------
                $uploadPath = FCPATH . 'uploads/blogs';
                if (! is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);

                $fullPath = $uploadPath . '/' . $newName;
                $webpPath = $this->convertToWebP($fullPath);

                $data['thumbnail'] = base_url(
                    'uploads/blogs/' . basename($webpPath ?? $newName)
                );
            }

            // -------------------------------------------------
            // 4. Delete old LOCAL thumbnail only
            // -------------------------------------------------
            if (
                $oldThumbnail &&
                str_contains($oldThumbnail, base_url('uploads/blogs'))
            ) {
                $oldPath = FCPATH . str_replace(base_url('/'), '', $oldThumbnail);
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
        } else {
            $data['thumbnail'] = $oldThumbnail ?? null;
        }

        unset($data['existing_thumbnail']);

        if (
            ($data['status'] ?? 'draft') === 'draft' &&
            empty($data['preview_token'])
        ) {
            $data['preview_token'] = bin2hex(random_bytes(24));
        }

        // -------------------------------------------------
        // 5. Insert or Update
        // -------------------------------------------------
        if (! empty($data['id'])) {
            $model->update($data['id'], $data);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Blog updated successfully',
            ]);
        }

        $model->insert($data);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Blog created successfully',
        ]);
    }

    public function previewBlog($token)
    {
        $blog = (new BlogModel())
            ->where('preview_token', $token)
            ->first();

        if (! $blog) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('blog-post', [
            'blog'      => $blog,
            'isPreview' => true,
        ]);
    }

    public function deleteBlog($id)
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        $model = new BlogModel();
        $blog  = $model->find($id);

        if (! $blog) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Blog not found',
            ]);
        }

        // Delete local thumbnail only
        if (
            ! empty($blog->thumbnail) &&
            str_contains($blog->thumbnail, base_url('uploads/blogs'))
        ) {
            $path = FCPATH . str_replace(base_url('/'), '', $blog->thumbnail);
            if (is_file($path)) {
                unlink($path);
            }
        }

        $model->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Blog deleted successfully',
        ]);
    }

    public function uploadEditorImage()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        $file = $this->request->getFile('image');

        if (! $file || ! $file->isValid() || ! $file->isImage()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid image upload',
            ]);
        }

        if ($file->getSizeByUnit('mb') > 2) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Image size must not exceed 2MB',
            ]);
        }

        // ---------------- CLOUDINARY ----------------
        if (
            env('CLOUDINARY_NAME') &&
            env('CLOUDINARY_API_KEY') &&
            env('CLOUDINARY_API_SECRET')
        ) {
            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            $upload = $cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'blogs/editor',
                    'resource_type' => 'image',
                    'quality' => 'auto',
                    'format' => 'webp',
                ]
            );

            $url = $upload['secure_url'];
        } else {
            // Local fallback
            $path = FCPATH . 'uploads/blogs/editor';
            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $name = $file->getRandomName();
            $file->move($path, $name);
            $url = base_url('uploads/blogs/editor/' . $name);
        }

        return $this->response->setJSON([
            'success' => true,
            'url'     => $url,
        ]);
     }

    public function uploadEditorImageCk4()
    {
        $file = $this->request->getFile('upload'); // CKEditor sends file as 'upload'
        if (!$file || !$file->isValid() || !$file->isImage()) {
            $funcNum = $this->request->getGet('CKEditorFuncNum');
            return $this->response->setBody("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '', 'Invalid image upload');</script>");
        }

        if ($file->getSizeByUnit('mb') > 2) {
            $funcNum = $this->request->getGet('CKEditorFuncNum');
            return $this->response->setBody("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '', 'Image size must not exceed 2MB');</script>");
        }

        // ---------------- CLOUDINARY ----------------
        if (
            env('CLOUDINARY_NAME') &&
            env('CLOUDINARY_API_KEY') &&
            env('CLOUDINARY_API_SECRET')
        ) {
            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            $upload = $cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'blogs/editor',
                    'resource_type' => 'image',
                    'quality' => 'auto',
                    'format' => 'webp',
                ]
            );

            $url = $upload['secure_url'];
        } else {
            // Local fallback
            $path = FCPATH . 'uploads/blogs/editor';
            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $name = $file->getRandomName();
            $file->move($path, $name);
            $url = base_url('uploads/blogs/editor/' . $name);
        }

        $funcNum = $this->request->getGet('CKEditorFuncNum');
        return $this->response->setBody("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', 'Image uploaded successfully');</script>");
    }

    /**
     * Generate a full blog post draft using AI (two-step).
     * Step 1 – metadata JSON (title, slug, excerpt, meta fields).
     * Step 2 – HTML body content as plain text (avoids JSON-encoding HTML).
     * Accepts POST: prompt (string), tone (string).
     * Returns JSON: { success, title, slug, excerpt, content, meta_title, meta_description }
     */
    public function aiGenerateBlog()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $prompt = trim((string) ($this->request->getPost('prompt') ?? ''));
        $tone   = trim((string) ($this->request->getPost('tone')   ?? 'professional'));

        if (empty($prompt)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please provide a topic or prompt for the blog post.',
            ]);
        }

        $aiService = new \App\Services\AiService();

        // ── STEP 1: Generate metadata only (small, clean JSON) ──────────────
        $metaPrompt  = "You are an SEO and content expert. Given the blog topic below, produce ONLY a raw JSON object (no markdown fences, no extra text) with exactly these keys:\n";
        $metaPrompt .= '{"title":"","slug":"","excerpt":"","meta_title":"","meta_description":""}' . "\n\n";
        $metaPrompt .= "Rules:\n";
        $metaPrompt .= "- title: compelling blog title, max 70 characters\n";
        $metaPrompt .= "- slug: lowercase, hyphens only, max 80 characters\n";
        $metaPrompt .= "- excerpt: 1-2 sentence teaser, max 180 characters, plain text\n";
        $metaPrompt .= "- meta_title: SEO title, max 60 characters\n";
        $metaPrompt .= "- meta_description: SEO description, max 160 characters, plain text\n";
        $metaPrompt .= "- Tone: {$tone}\n";
        $metaPrompt .= "Topic: {$prompt}";

        $metaRaw = $aiService->generate($metaPrompt);

        // Parse the metadata JSON
        $meta = null;
        if (is_string($metaRaw)) {
            // Strip any markdown code fences
            $cleaned = preg_replace('/^```(?:json)?\s*/i', '', trim($metaRaw));
            $cleaned = preg_replace('/\s*```\s*$/i', '', trim($cleaned));
            $meta    = json_decode(trim($cleaned), true);

            // Fallback: extract first {...} object from response
            if (! is_array($meta)) {
                if (preg_match('/\{[^{}]*\}/s', $cleaned, $m)) {
                    $meta = json_decode($m[0], true);
                }
            }
        }

        if (! is_array($meta) || empty($meta['title'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'AI could not generate blog metadata. Please try a different topic.',
            ]);
        }

        // Sanitise metadata fields
        $title           = trim(strip_tags((string) ($meta['title']            ?? '')));
        $slug            = trim(strip_tags((string) ($meta['slug']             ?? '')));
        $excerpt         = trim(strip_tags((string) ($meta['excerpt']          ?? '')));
        $metaTitle       = trim(strip_tags((string) ($meta['meta_title']       ?? $title)));
        $metaDescription = trim(strip_tags((string) ($meta['meta_description'] ?? $excerpt)));

        // Auto-generate slug from title if empty
        if (empty($slug) && ! empty($title)) {
            $slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($title)), '-');
        }

        // ── STEP 2: Generate the full HTML body separately ───────────────────
        $bodyPrompt  = "You are a professional content writer. Write a complete blog post body in clean HTML.\n";
        $bodyPrompt .= "Topic: {$prompt}\n";
        $bodyPrompt .= "Title: {$title}\n";
        $bodyPrompt .= "Tone: {$tone}\n\n";
        $bodyPrompt .= "Requirements:\n";
        $bodyPrompt .= "- Output ONLY the HTML body — no <html>, <head>, or <body> tags\n";
        $bodyPrompt .= "- Use h2, h3, p, ul, ol, li, strong, em tags only\n";
        $bodyPrompt .= "- No inline styles, no class attributes, no script or style tags\n";
        $bodyPrompt .= "- Aim for 600–900 words, engaging and well-structured\n";
        $bodyPrompt .= "- Start directly with an <h2> or <p> — no introduction like 'Here is your blog post:'";

        $content = $aiService->generate($bodyPrompt);

        // Strip any accidental markdown fences from the HTML body
        if (is_string($content)) {
            $content = preg_replace('/^```(?:html)?\s*/i', '', trim($content));
            $content = preg_replace('/\s*```\s*$/i', '', trim($content));
            $content = trim($content);
        }

        if (empty($content) || ! is_string($content)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'AI could not generate the blog body. Metadata was created — please try again.',
            ]);
        }

        return $this->response->setJSON([
            'success'          => true,
            'title'            => $title,
            'slug'             => $slug,
            'excerpt'          => $excerpt,
            'content'          => $content,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDescription,
        ]);
    }

    /**
     * Generate an image using AI or fallback service based on a prompt.
     * Returns JSON with 'success' and 'url' fields.
     */
    public function aiGenerateImage()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $prompt = trim((string) ($this->request->getPost('prompt') ?? ''));
        $style  = trim((string) ($this->request->getPost('style') ?? 'photo'));

        if (empty($prompt)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please provide an image description.'
            ]);
        }

        // Try Gemini image generation if key is set (placeholder – actual endpoint may differ)
        $geminiKey = env('GEMINI_API_KEY');
        if ($geminiKey) {
            try {
                $client = \Config\Services::curlrequest();
                $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=" . $geminiKey;
                $payload = [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => "Generate a $style image URL for the following description: $prompt. Respond ONLY with a direct HTTPS image URL."]
                            ]
                        ]
                    ]
                ];
                $response = $client->post($url, [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json'    => $payload
                ]);
                if ($response->getStatusCode() === 200) {
                    $data = json_decode($response->getBody(), true);
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    $imageUrl = trim($text);
                    if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                        return $this->response->setJSON([
                            'success' => true,
                            'url'     => $imageUrl
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // fall back to Unsplash
            }
        }

        // Fallback: Unsplash random image based on search terms extracted from prompt
        $search = urlencode($prompt);
        $unsplashUrl = "https://source.unsplash.com/800x600/?" . $search;
        return $this->response->setJSON([
            'success' => true,
            'url'     => $unsplashUrl
        ]);
    }


    public function checkTitle()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $title = trim($this->request->getPost('title'));
        $id    = (int) ($this->request->getPost('id') ?? 0);

        if ($title === '') {
            return $this->response->setJSON(['valid' => false]);
        }

        $blogModel = new BlogModel();

        $baseTitle = $title;
        $counter   = 1;

        while (
            $blogModel
            ->where('title', $title)
            ->where('id !=', $id)
            ->countAllResults() > 0
        ) {
            $title = $baseTitle . ' (' . $counter++ . ')';
        }

        return $this->response->setJSON([
            'valid' => $title === $baseTitle,
            'title' => $title,
        ]);
    }

    public function checkSlug()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $slug = trim($this->request->getPost('slug'));
        $id   = (int) ($this->request->getPost('id') ?? 0);

        if ($slug === '') {
            return $this->response->setJSON(['valid' => false]);
        }

        $blogModel = new BlogModel();

        $exists = $blogModel
            ->where('slug', $slug)
            ->where('id !=', $id)
            ->countAllResults() > 0;

        if (! $exists) {
            return $this->response->setJSON([
                'valid' => true,
                'slug'  => $slug,
            ]);
        }

        // Append random characters until unique
        do {
            $newSlug = $slug . '-' . substr(bin2hex(random_bytes(3)), 0, 6);
            $exists = $blogModel
                ->where('slug', $newSlug)
                ->where('id !=', $id)
                ->countAllResults() > 0;
        } while ($exists);

        return $this->response->setJSON([
            'valid' => false,
            'slug'  => $newSlug,
        ]);
    }

    private function convertToWebP(string $sourcePath): ?string
    {
        $info = getimagesize($sourcePath);
        if (! $info) return null;

        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            default:
                return null;
        }

        $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $sourcePath);
        imagewebp($image, $webpPath, 80);
        imagedestroy($image);

        unlink($sourcePath);

        return $webpPath;
    }

    /* Normalize results */
    private function normalizeGenderStats(array $rows): array
    {
        $data = ['male' => 0, 'female' => 0];

        foreach ($rows as $row) {
            $key = strtolower($row->gender);
            if (isset($data[$key])) {
                $data[$key] = (int) $row->total;
            }
        }
        return $data;
    }

    private function paystackPost($endpoint, $data)
    {
        $key = env("paystack_secret_key");

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paystack.co" . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $key",
                "Content-Type: application/json"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data)
        ]);

        $res = curl_exec($curl);
        curl_close($curl);

        return json_decode($res);
    }

    // Testimonials
    public function testimonials()
    {
        $perPage = 20; // adjust as needed

        $testimonialModel = model('TestimonialModel');

        $data = [
            'title'        => 'Testimonials',
            'user'         => $this->auth->user(),
            'admin'        => $this->admin,
            'testimonials' => $testimonialModel
                ->orderBy('created_at', 'DESC')
                ->paginate($perPage),
            'pager'        => $testimonialModel->pager,
        ];
        return view('admin/testimonials', $data);
    }

    public function saveTestimonial()
    {
        if (! $this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        // -------------------------------------------------
        // 1. Validate Avatar
        // -------------------------------------------------
        $rules = [
            'avatar' => [
                'label' => 'Avatar',
                'rules' => 'if_exist|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]',
            ],
            'name'    => 'required|min_length[2]|max_length[150]',
            'message' => 'required|min_length[5]',
            'rating'  => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
            'status'  => 'required|in_list[active,inactive]',
            'is_featured' => 'permit_empty|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $this->validator->getErrors()),
            ]);
        }

        $model = new TestimonialModel();
        $data  = $this->request->getPost();

        // -------------------------------------------------
        // 2. Avatar Upload
        // -------------------------------------------------
        $file      = $this->request->getFile('avatar');
        $oldAvatar = $data['existing_avatar'] ?? null;

        if ($file && $file->isValid() && ! $file->hasMoved()) {

            // ---------------- CLOUDINARY (PRIMARY) ----------------
            if (
                env('CLOUDINARY_NAME') &&
                env('CLOUDINARY_API_KEY') &&
                env('CLOUDINARY_API_SECRET')
            ) {
                $cloudinary = new \Cloudinary\Cloudinary([
                    'cloud' => [
                        'cloud_name' => env('CLOUDINARY_NAME'),
                        'api_key'    => env('CLOUDINARY_API_KEY'),
                        'api_secret' => env('CLOUDINARY_API_SECRET'),
                    ],
                ]);

                $upload = $cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'folder'        => 'testimonials',
                        'resource_type' => 'image',

                        // --- AUTO CROP TO SQUARE ---
                        'aspect_ratio'  => '1:1',
                        'crop'          => 'fill',
                        'gravity'       => 'face',

                        // --- OPTIMIZATION ---
                        'format'        => 'webp',
                        'quality'       => 'auto',
                        'fetch_format'  => 'auto',

                        'verify'        => false,
                    ]
                );

                $data['avatar'] = $upload['secure_url'];
            } else {
                // ---------------- LOCAL FALLBACK ----------------
                $uploadPath = FCPATH . 'uploads/testimonials';
                if (! is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);

                $data['avatar'] = base_url('uploads/testimonials/' . $newName);
            }

            // -------------------------------------------------
            // 3. Delete old LOCAL avatar only
            // -------------------------------------------------
            if (
                $oldAvatar &&
                str_contains($oldAvatar, base_url('uploads/testimonials'))
            ) {
                $oldPath = FCPATH . str_replace(base_url('/'), '', $oldAvatar);
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
        } else {
            $data['avatar'] = $oldAvatar ?? null;
        }

        unset($data['existing_avatar']);

        // -------------------------------------------------
        // 4. Normalize Fields
        // -------------------------------------------------
        $payload = [
            'name'        => trim($data['name']),
            'role'        => trim($data['role'] ?? ''),
            'company'     => trim($data['company'] ?? ''),
            'message'     => trim($data['message']),
            'rating'      => (int) $data['rating'],
            'status'      => $data['status'],
            'is_featured' => (int) ($data['is_featured'] ?? 0),
            'avatar'      => $data['avatar'],
        ];

        // -------------------------------------------------
        // 5. Insert or Update
        // -------------------------------------------------
        if (! empty($data['id'])) {
            $model->update($data['id'], $payload);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Testimonial updated successfully',
            ]);
        }

        $model->insert($payload);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Testimonial created successfully',
        ]);
    }

    public function deleteTestimonial($id)
    {
        if (! $this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        $model = new TestimonialModel();
        $testimonial = $model->find($id);

        if (! $testimonial) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Testimonial not found',
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Testimonial deleted successfully',
        ]);
    }

    /**
     * Delete a single candidate
     */
    public function deleteCandidate($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $candidate = $this->jobSeekerModel->find($id);

        if (!$candidate) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Candidate not found'
            ]);
        }

        // Delete related data first
        try {
            // Delete from job_seeker_industries
            $this->jobSeekerModel->db->table('job_seeker_industries')
                ->where('job_seeker_id', $id)
                ->delete();

            // Delete from job_applications
            $this->jobSeekerModel->db->table('job_applications')
                ->where('job_seeker_id', $id)
                ->delete();

            // Delete from job_alerts
            $this->jobSeekerModel->db->table('job_alerts')
                ->where('job_seeker_id', $id)
                ->delete();

            // Delete from candidate_shortlist
            $this->jobSeekerModel->db->table('candidate_shortlists')
                ->where('job_seeker_id', $id)
                ->delete();

            // Delete the candidate
            $this->jobSeekerModel->delete($id);

            // Delete the user account if exists
            if ($candidate->user_id) {
                $this->jobSeekerModel->db->table('users')
                    ->where('id', $candidate->user_id)
                    ->delete();

                $this->jobSeekerModel->db->table('auth_identities')
                    ->where('user_id', $candidate->user_id)
                    ->delete();
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Candidate deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete candidate: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk delete candidates
     */
    public function bulkDeleteCandidates()
    {
        if (!$this->request->isAJAX() || $this->request->getMethod() !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $ids = $this->request->getJSON()->ids ?? [];

        if (empty($ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No candidates selected'
            ]);
        }

        try {
            foreach ($ids as $id) {
                $candidate = $this->jobSeekerModel->find($id);
                
                if ($candidate) {
                    // Delete related data
                    $this->jobSeekerModel->db->table('job_seeker_industries')
                        ->where('job_seeker_id', $id)
                        ->delete();

                    $this->jobSeekerModel->db->table('job_applications')
                        ->where('job_seeker_id', $id)
                        ->delete();

                    $this->jobSeekerModel->db->table('job_alerts')
                        ->where('job_seeker_id', $id)
                        ->delete();

                    $this->jobSeekerModel->db->table('candidate_shortlists')
                        ->where('job_seeker_id', $id)
                        ->delete();

                    // Delete the candidate
                    $this->jobSeekerModel->delete($id);

                    // Delete user account
                    if ($candidate->user_id) {
                        $this->jobSeekerModel->db->table('users')
                            ->where('id', $candidate->user_id)
                            ->delete();

                        $this->jobSeekerModel->db->table('auth_identities')
                            ->where('user_id', $candidate->user_id)
                            ->delete();
                    }
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => count($ids) . ' candidate(s) deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete candidates: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete a single employer
     */
    public function deleteEmployer($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $employer = $this->employerModel->find($id);

        if (!$employer) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Employer not found'
            ]);
        }

        try {
            // Delete related data first
            $this->employerModel->db->table('jobs')
                ->where('employer_id', $id)
                ->delete();

            $this->employerModel->db->table('employer_industries')
                ->where('employer_id', $id)
                ->delete();

            $this->employerModel->db->table('employer_documents')
                ->where('employer_id', $id)
                ->delete();

            // $this->employerModel->db->table('job_applications')
            //     ->where('employer_id', $id)
            //     ->delete();

            $this->employerModel->db->table('user_subscriptions')
                ->where('user_id', $employer->user_id)
                ->delete();

            // Delete the employer
            $this->employerModel->delete($id);

            // Delete the user account if exists
            if ($employer->user_id) {
                $this->employerModel->db->table('users')
                    ->where('id', $employer->user_id)
                    ->delete();

                $this->employerModel->db->table('auth_identities')
                    ->where('user_id', $employer->user_id)
                    ->delete();
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Employer deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete employer: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk delete employers
     */
    public function bulkDeleteEmployers()
    {
        if (!$this->request->isAJAX() || $this->request->getMethod() !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $ids = $this->request->getJSON()->ids ?? [];

        if (empty($ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No employers selected'
            ]);
        }

        try {
            foreach ($ids as $id) {
                $employer = $this->employerModel->find($id);
                
                if ($employer) {
                    // Delete related data
                    $this->employerModel->db->table('jobs')
                        ->where('employer_id', $id)
                        ->delete();

                    $this->employerModel->db->table('employer_industries')
                        ->where('employer_id', $id)
                        ->delete();

                    $this->employerModel->db->table('employer_documents')
                        ->where('employer_id', $id)
                        ->delete();

                    $this->employerModel->db->table('job_applications')
                        ->where('employer_id', $id)
                        ->delete();

                    $this->employerModel->db->table('user_subscriptions')
                        ->where('user_id', $employer->user_id)
                        ->delete();

                    // Delete the employer
                    $this->employerModel->delete($id);

                    // Delete user account
                    if ($employer->user_id) {
                        $this->employerModel->db->table('users')
                            ->where('id', $employer->user_id)
                            ->delete();

                        $this->employerModel->db->table('auth_identities')
                            ->where('user_id', $employer->user_id)
                            ->delete();
                    }
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => count($ids) . ' employer(s) deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete employers: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * CV Review Management
     */
    public function cvReviews()
    {
        $reviewModel = model(\App\Models\CvReviewModel::class);
        $reviews = $reviewModel->orderBy('created_at', 'DESC')->findAll();

        return view('admin/cv_reviews', [
            'title'   => 'CV Review Management',
            'reviews' => $reviews,
        ]);
    }

    public function cvReviewDetail($id)
    {
        $reviewModel = model(\App\Models\CvReviewModel::class);
        $review = $reviewModel->find($id);

        if (!$review) {
            return redirect()->to('admin/cv-reviews')->with('error', 'CV Review not found.');
        }

        return view('admin/cv_review_detail', [
            'title'  => 'CV Review #' . $id,
            'review' => $review,
        ]);
    }

    public function cvReviewDownload($id)
    {
        $reviewModel = model(\App\Models\CvReviewModel::class);
        $review = $reviewModel->find($id);

        $filePathVal = is_array($review) ? ($review['file_path'] ?? null) : ($review->file_path ?? null);
        if (!$review || !$filePathVal) {
            return redirect()->to('admin/cv-reviews')->with('error', 'File not found.');
        }

        $filePath = FCPATH . $filePathVal;
        if (!is_file($filePath)) {
            return redirect()->to('admin/cv-reviews')->with('error', 'File does not exist on disk.');
        }

        return $this->response->download($filePath, null);
    }

    public function cvReviewAiGenerate($id)
    {
        $reviewModel = model(\App\Models\CvReviewModel::class);
        $review = $reviewModel->find($id);

        if (!$review) {
            return redirect()->to('admin/cv-reviews')->with('error', 'CV Review not found.');
        }

        $filePathVal = is_array($review) ? ($review['file_path'] ?? '') : ($review->file_path ?? '');
        $filePath = FCPATH . $filePathVal;
        $cvContent = '';

        if ($filePathVal && is_file($filePath)) {
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $cvContent = file_get_contents($filePath);

            if (in_array($ext, ['doc', 'docx']) && $cvContent === false) {
                $cvContent = '[Binary document format — unable to extract text. Please review manually.]';
            }
        }

        if (empty($cvContent)) {
            $cvContent = '[No CV file content available — review based on candidate info]';
        }

        $fullNameVal = is_array($review) ? ($review['full_name'] ?? 'Candidate') : ($review->full_name ?? 'Candidate');
        $targetRoleVal = is_array($review) ? ($review['target_role'] ?? '') : ($review->target_role ?? '');
        $industryVal = is_array($review) ? ($review['industry'] ?? '') : ($review->industry ?? '');
        $feedbackRequestVal = is_array($review) ? ($review['feedback_request'] ?? '') : ($review->feedback_request ?? '');
        $planVal = is_array($review) ? ($review['plan'] ?? 'basic') : ($review->plan ?? 'basic');

        $aiService = new \App\Services\AiService();
        $aiReview = $aiService->generateCvReview([
            'full_name'        => $fullNameVal,
            'target_role'      => $targetRoleVal,
            'industry'         => $industryVal,
            'feedback_request' => $feedbackRequestVal,
            'cv_content'       => $cvContent,
            'plan'             => $planVal,
        ]);

        $reviewModel->update($id, [
            'ai_review'   => $aiReview,
            'admin_id'    => auth()->id(),
            'status'      => 'in_review',
            'review_mode' => 'semi',
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('admin/cv-reviews/view/' . $id)
            ->with('success', 'AI review generated successfully.');
    }

    public function cvReviewMarkInReview($id)
    {
        model(\App\Models\CvReviewModel::class)->update($id, [
            'status' => 'in_review',
        ]);

        return redirect()->to('admin/cv-reviews/view/' . $id)
            ->with('success', 'Review marked as in progress.');
    }

    public function cvReviewComplete($id)
    {
        $reviewModel = model(\App\Models\CvReviewModel::class);
        $reviewModel->update($id, [
            'status' => 'completed',
        ]);

        $review = $reviewModel->find($id);
        if ($review && !empty($review['email'])) {
            $emailService = service('mailer');
            $emailService->sendTemplate(
                $review['email'],
                'Your CV Review is Ready!',
                'emails/cv_review_completed',
                [
                    'user_name' => $review['full_name'] ?? 'Candidate',
                ]
            );
        }

        return redirect()->to('admin/cv-reviews/view/' . $id)
            ->with('success', 'Review marked as completed.');
    }

    public function cvReviewSaveNotes($id)
    {
        $notes = $this->request->getPost('admin_notes');
        model(\App\Models\CvReviewModel::class)->update($id, [
            'admin_notes' => $notes,
        ]);

        return redirect()->to('admin/cv-reviews/view/' . $id)
            ->with('success', 'Notes saved successfully.');
    }

    public function cvReviewDeliver($id)
    {
        model(\App\Models\CvReviewModel::class)->update($id, [
            'feedback_delivered' => 1,
            'delivered_at'       => date('Y-m-d H:i:s'),
            'status'             => 'completed',
        ]);

        return redirect()->to('admin/cv-reviews/view/' . $id)
            ->with('success', 'Feedback marked as delivered. The candidate has been notified.');
    }
}
