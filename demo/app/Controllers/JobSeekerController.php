<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\EmployerModel;
use App\Models\JobSeekerModel;
use App\Entities\User;
use App\Entities\Employer;
use App\Entities\JobSeeker;
use App\Models\IndustryModel;
use App\Models\JobAlertModel;
use App\Models\JobApplicationModel;
use App\Models\JobCategoryModel;
use App\Models\JobClickModel;
use App\Models\JobModel;
use App\Models\JobSeekerIndustryModel;
use App\Models\StateModel;
use CodeIgniter\Shield\Models\UserModel as ModelsUserModel;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Authentication\Auth;

class JobSeekerController extends BaseController
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
        helper(['auth', 'text', 'form', 'url', 'env']);
        $this->users = model(UserModel::class);
        $this->userModel = model(ModelsUserModel::class);
        $this->session = \Config\Services::session();
    }

    public function dashboard()
    {
        $candidateModel     = model(JobSeekerModel::class);
        $jobClickModel      = model(JobClickModel::class);
        $applicationModel   = model('App\Models\JobApplicationModel');
        $savedJobModel      = model('App\Models\SavedJobModel');
        $jobModel           = model('App\Models\JobModel');

        // Log current date and time
        $date = date('Y-m-d H:i:s');
        log_message('info', "Job Seeker Dashboard: {$date}");

        $candidate = $candidateModel
            ->where('user_id', $this->auth->user()->id)
            ->first();

        if (!$candidate) {
            return redirect()->to('candidate/profile');
        }

        // Required fields check
        if (
            empty($candidate->full_name) ||
            empty($candidate->phone) ||
            empty($candidate->job_title) ||
            empty($candidate->skills) ||
            empty($candidate->resume)
        ) {
            return redirect()->to('candidate/profile');
        }

        // ====== Dashboard Statistics ======

        // Total Applications
        $totalApplications = $applicationModel
            ->where('job_seeker_id', $candidate->id)
            ->countAllResults();

        // Saved Jobs
        $savedJobs = $savedJobModel
            ->where('job_seeker_id', $candidate->id)
            ->countAllResults();

        // Jobs Viewed
        $jobsViewed = $jobClickModel
            ->where('user_id', $this->auth->user()->id)
            ->countAllResults();

        // Recommended Jobs (simple example: by industry_id)
        $recommendedJobs = $jobModel
            ->where('industry_id', $candidate->industry_id ?? null)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Recent Applications (limit 5)
        $recentApplications = $applicationModel
            ->where('job_seeker_id', $candidate->id)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Latest Jobs (for the "Latest Jobs" section in the view)
        $latestJobs = $jobModel
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // (Optional) recent applications count for the welcome banner
        $recentApplicationsCount = count($recentApplications);

        // Profile Completion
        $fields = [
            'full_name',
            'dob',
            'gender',
            'phone',
            'location',
            'job_title',
            'employment_type',
            'skills',
            'education_level',
            'languages',
            'resume',
            'availability'
        ];

        $completed = 0;
        foreach ($fields as $f) {
            if (!empty($candidate->$f)) {
                $completed++;
            }
        }

        $profileCompletion = round(($completed / count($fields)) * 100);

        return view('candidate/dashboard', [
            'title'                  => 'Dashboard',
            'user'                   => $this->auth->user(),
            'candidate'              => $candidate,
            'totalApplications'      => $totalApplications,
            'savedJobs'              => $savedJobs,
            'jobsViewed'             => $jobsViewed,
            'recommendedJobs'        => $recommendedJobs,
            'recentApplications'     => $recentApplications,
            'recentApplicationsCount' => $recentApplicationsCount,
            'latestJobs'             => $latestJobs,
            'profileCompletion'      => $profileCompletion,
        ]);
    }


    public function profile()
    {
        $candidateModel = model(JobSeekerModel::class);

        // Get candidate profile
        $candidate = $candidateModel
        ->select('job_seekers.*, states.name as location')
        ->join('states', 'states.id = job_seekers.state_id', 'left')
            ->where('user_id', $this->auth->user()->id)
            ->first();

        $data = [
            'title'     => 'Profile',
            'user'      => $this->auth->user(),
            'candidate' => $candidate
        ];

        return view('candidate/profile', $data);
    }


    public function edit_profile()
    {
        $user = $this->auth->user();

        // Fetch the candidate record
        $candidateModel = new JobSeekerModel();
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        if (!$candidate) {
            return redirect()->to('candidate/profile/edit')->with('error', 'Please create your profile first.');
        }

        // If POST, handle update immediately
        if ($this->request->getMethod() === "POST") {
            $user = $this->auth->user();

            $candidateModel = new JobSeekerModel();
            $candidateIndustryModel = new JobSeekerIndustryModel();

            // Fetch candidate
            $candidate = $candidateModel->where('user_id', $user->id)->first();

            if (!$candidate) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Candidate profile not found.'
                ]);
            }

            // Validation Rules
            $rules = [
                'full_name'         => 'required|min_length[3]',
                'phone'             => 'required|min_length[6]',
                'state_id'          => 'required|integer',
                'job_title'         => 'required|min_length[2]',
                'industry_ids'      => 'required',
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $portfolio = trim($this->request->getPost('portfolio'));

            if ($portfolio && !preg_match('#^https?://#i', $portfolio)) {
                $portfolio = 'https://' . $portfolio;
            }


            // Collect POST Data
            $data = [
                'full_name'         => trim($this->request->getPost('full_name')),
                'dob'               => trim($this->request->getPost('dob')),
                'gender'            => trim($this->request->getPost('gender')),
                'phone'             => trim($this->request->getPost('phone')),
                'location'          => trim($this->request->getPost('location')),
                'state_id'          => trim($this->request->getPost('state_id')),
                'availability'      => trim($this->request->getPost('availability')),
                'job_title'         => trim($this->request->getPost('job_title')),
                'employment_type'   => trim($this->request->getPost('employment_type')),
                'skills'            => trim($this->request->getPost('skills')),
                'experience_years'  => trim($this->request->getPost('experience_years')),
                'education_level'   => trim($this->request->getPost('education_level')),
                'languages'         => trim($this->request->getPost('languages')),
                'desired_salary'    => trim($this->request->getPost('desired_salary')),
                'salary_type'       => trim($this->request->getPost('salary_type')),
                'portfolio'         => $portfolio ?? null,
                'description'       => trim($this->request->getPost('description'))
            ];

            helper(['filesystem', 'form']);

            // File Upload Directory
            $uploadPath = 'uploads/candidates/' . $candidate->id . '/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            /**
             * PROFILE PICTURE UPLOAD
             */
            $profileFile = $this->request->getFile('profile_picture');
            if ($profileFile && $profileFile->isValid()) {

                if ($candidate->profile_picture && file_exists($candidate->profile_picture)) {
                    unlink($candidate->profile_picture);
                }

                $newName = $profileFile->getRandomName();
                $profileFile->move($uploadPath, $newName);

                $data['profile_picture'] = $uploadPath . $newName;
            } elseif ($this->request->getPost('remove_profile_picture')) {

                if ($candidate->profile_picture && file_exists($candidate->profile_picture)) {
                    unlink($candidate->profile_picture);
                }
                $data['profile_picture'] = null;
            }


            /**
             * RESUME UPLOAD
             */
            $resumeFile = $this->request->getFile('resume');
            if ($resumeFile && $resumeFile->isValid()) {

                if ($candidate->resume && file_exists($candidate->resume)) {
                    unlink($candidate->resume);
                }

                $resumeName = $resumeFile->getRandomName();
                $resumeFile->move($uploadPath, $resumeName);

                $data['resume'] = $uploadPath . $resumeName;
            } elseif ($this->request->getPost('remove_resume')) {

                if ($candidate->resume && file_exists($candidate->resume)) {
                    unlink($candidate->resume);
                }
                $data['resume'] = null;
            }

            /**
             * UPDATE CANDIDATE
             */
            $candidateModel->update($candidate->id, $data);

            /**
             * UPDATE INDUSTRIES
             */
            $industryIds = $this->request->getVar('industry_ids') ?? [];
            $candidateIndustryModel->where('job_seeker_id', $candidate->id)->delete();

            foreach ($industryIds as $industryId) {
                $candidateIndustryModel->insert([
                    'job_seeker_id' => $candidate->id,
                    'industry_id'   => $industryId
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Profile updated successfully.'
            ])->setStatusCode(200);
        }

        // Load industries (parent + children)
        $industryModel = new IndustryModel();
        $parentIndustries = $industryModel->where('parent_id', null)->findAll();

        foreach ($parentIndustries as &$parent) {
            $parent->children = $industryModel
                ->where('parent_id', $parent->id)
                ->findAll();
        }

        // Load states
        $states = (new StateModel())->findAll();

        // Load candidate industry IDs
        $candidateIndustryIds = (new JobSeekerIndustryModel())
            ->where('job_seeker_id', $candidate->id)
            ->findColumn('industry_id');

        return view('candidate/edit_profile', [
            'title' => 'Edit Profile',
            'user' => $user,
            'candidate' => $candidate,
            'industries' => $parentIndustries,
            'states' => $states,
            'candidateIndustryIds' => $candidateIndustryIds
        ]);
    }

    public function security()
    {
        $candidateModel = model(JobSeekerModel::class);

        // Get candidate profile
        $candidate = $candidateModel
            ->select('job_seekers.*, states.name as location')
            ->join('states', 'states.id = job_seekers.state_id', 'left')
            ->where('user_id', $this->auth->user()->id)
            ->first();

        return view('candidate/security/index', [
            'title' => 'Security Settings',
            'user'  => $this->auth->user(),
            'candidate' => $candidate,
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
     * AJAX Profile Update Handler
     */
    public function update_profile()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request.'
            ]);
        }

        $user = $this->auth->user();

        $candidateModel = new JobSeekerModel();
        $candidateIndustryModel = new JobSeekerIndustryModel();

        // Fetch candidate
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        if (!$candidate) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Candidate profile not found.'
            ]);
        }

        // Validation Rules
        $rules = [
            'full_name'         => 'required|min_length[3]',
            'phone'             => 'required|min_length[6]',
            'state_id'          => 'required|integer',
            'job_title'         => 'required|min_length[2]',
            'industry_ids'      => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Collect POST Data
        $data = [
            'full_name'         => $this->request->getPost('full_name'),
            'dob'               => $this->request->getPost('dob'),
            'gender'            => $this->request->getPost('gender'),
            'phone'             => $this->request->getPost('phone'),
            'location'          => $this->request->getPost('location'),
            'state_id'          => $this->request->getPost('state_id'),
            'availability'      => $this->request->getPost('availability'),
            'job_title'         => $this->request->getPost('job_title'),
            'employment_type'   => $this->request->getPost('employment_type'),
            'skills'            => $this->request->getPost('skills'),
            'experience_years'  => $this->request->getPost('experience_years'),
            'education_level'   => $this->request->getPost('education_level'),
            'languages'         => $this->request->getPost('languages'),
            'desired_salary'    => $this->request->getPost('desired_salary'),
            'salary_type'       => $this->request->getPost('salary_type'),
            'portfolio'         => $this->request->getPost('portfolio'),
            'description'       => $this->request->getPost('description')
        ];

        helper(['filesystem', 'form']);

        // File Upload Directory
        $uploadPath = 'uploads/candidates/' . $candidate->id . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        /**
         * PROFILE PICTURE UPLOAD
         */
        $profileFile = $this->request->getFile('profile_picture');
        if ($profileFile && $profileFile->isValid()) {

            if ($candidate->profile_picture && file_exists($candidate->profile_picture)) {
                unlink($candidate->profile_picture);
            }

            $newName = $profileFile->getRandomName();
            $profileFile->move($uploadPath, $newName);

            $data['profile_picture'] = $uploadPath . $newName;

        } elseif ($this->request->getPost('remove_profile_picture')) {

            if ($candidate->profile_picture && file_exists($candidate->profile_picture)) {
                unlink($candidate->profile_picture);
            }
            $data['profile_picture'] = null;
        }


        /**
         * RESUME UPLOAD
         */
        $resumeFile = $this->request->getFile('resume');
        if ($resumeFile && $resumeFile->isValid()) {

            if ($candidate->resume && file_exists($candidate->resume)) {
                unlink($candidate->resume);
            }

            $resumeName = $resumeFile->getRandomName();
            $resumeFile->move($uploadPath, $resumeName);

            $data['resume'] = $uploadPath . $resumeName;

        } elseif ($this->request->getPost('remove_resume')) {

            if ($candidate->resume && file_exists($candidate->resume)) {
                unlink($candidate->resume);
            }
            $data['resume'] = null;
        }

        log_message('info', json_encode($data));

        /**
         * UPDATE CANDIDATE
         */
        $candidateModel->update($candidate->id, $data);

        /**
         * UPDATE INDUSTRIES
         */
        $industryIds = $this->request->getVar('industry_ids') ?? [];
        $candidateIndustryModel->where('job_seeker_id', $candidate->id)->delete();

        foreach ($industryIds as $industryId) {
            $candidateIndustryModel->insert([
                'job_seeker_id' => $candidate->id,
                'industry_id'   => $industryId
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Profile updated successfully.'
        ])->setStatusCode(200);
    }

    public function applications()
    {
        $user = $this->auth->user();

        $candidateModel = model(JobSeekerModel::class);
        $applicationModel = model(JobApplicationModel::class);
        $jobModel = model(JobModel::class);

        // Candidate profile
        $candidate = $candidateModel
            ->where('user_id', $user->id)
            ->first();

        if (!$candidate) {
            return redirect()->to('candidate/profile/edit')
                ->with('error', 'Please complete your profile first.');
        }

        // Fetch all applications made by this candidate
        $applications = $applicationModel
            ->select('job_applications.*, jobs.title as job_title, employers.company_name, jobs.id as job_id')
            ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('job_applications.job_seeker_id', $candidate->id)
            ->orderBy('job_applications.created_at', 'DESC')
            ->findAll();

        $data = [
            'title'       => 'My Applications',
            'user'        => $user,
            'candidate'   => $candidate,
            'applications' => $applications
        ];

        return view('candidate/applications', $data);
    }

    public function viewApplication($id)
    {
        $user = $this->auth->user();

        $candidateModel = model(JobSeekerModel::class);
        $applicationModel = model(JobApplicationModel::class);
        $jobModel = model(JobModel::class);

        // Candidate profile
        $candidate = $candidateModel
            ->where('user_id', $user->id)
            ->first();

        if (!$candidate) {
            return redirect()->to('candidate/profile/edit')->with('error', 'Complete your profile first.');
        }

        // Application
        $application = $applicationModel
            ->where('id', $id)
            ->where('job_seeker_id', $candidate->id)
            ->first();

        if (!$application) {
            return redirect()->to('candidate/applications')->with('error', 'Application not found.');
        }

        // Job details
        $job = $jobModel
            ->select('jobs.*, job_categories.name as category_name, employers.company_name as company_name, industries.name as industry_name, states.name as location')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->where('jobs.id', $application->job_id)
            ->first();

        // Decode references
        $references = [];
        if (!empty($application->references)) {
            $references = json_decode($application->references, true);
            if (!is_array($references)) {
                $references = [];
            }
        }

        return view('candidate/application_view', [
            'title'       => 'Application Details',
            'user'        => $user,
            'candidate'   => $candidate,
            'application' => $application,
            'job'         => $job,
            'references'  => $references
        ]);
    }

    public function notifications()
    {
        $user = $this->auth->user();

        $candidateModel = model(JobSeekerModel::class);
        $alertModel = model(JobAlertModel::class);
        $state = model(StateModel::class);
        $industryModel = model(IndustryModel::class);
        $categoryModel = model(JobCategoryModel::class);

        // Candidate profile
        $candidate = $candidateModel
            ->where('user_id', $user->id)
            ->first();

        if (!$candidate) {
            return redirect()->to('candidate/profile/edit')->with('error', 'Complete your profile first.');
        }

        // Fetch job alerts for this candidate
        $alerts = $alertModel
            ->where('job_seeker_id', $candidate->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // For filter options
        $industries = $industryModel->findAll();
        $categories = $categoryModel->findAll();

        $states = $state->orderBy('name', 'ASC')->findAll();

        return view('candidate/notifications', [
            'title'      => 'Job Alerts',
            'user'       => $user,
            'candidate'  => $candidate,
            'alerts'     => $alerts,
            'industries' => $industries,
            'categories' => $categories,
            'states'     => $states
        ]);
    }

    public function saveAlert()
    {
        $alertModel = model(JobAlertModel::class);
        $user = $this->auth->user();

        $candidateModel = model(JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        if (!$candidate) {
            return $this->response->setJSON(['success' => false, 'message' => 'Candidate not found.']);
        }

        $data = [
            'job_seeker_id' => $candidate->id,
            'keyword'       => $this->request->getPost('keyword'),
            'location_id'   => $this->request->getPost('location_id'),
            'frequency'     => $this->request->getPost('frequency'),
            'delivery_time' => $this->request->getPost('delivery_time'),
            'channel'       => $this->request->getPost('channel') ?? 'email',
        ];

        if ($alertModel->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Alert created successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Unable to save alert.']);
    }

    public function deleteAlert($id)
    {
        $alertModel = model(JobAlertModel::class);

        if ($alertModel->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false]);
    }

    public function pauseAlert($id)
    {
        $alertModel = model(JobAlertModel::class);
        $user = $this->auth->user();

        $alert = $alertModel->find($id);

        if (! $alert || $alert->job_seeker_id != $this->getCandidateId($user->id)) {
            return $this->response->setJSON(['success' => false]);
        }

        $alertModel->update($id, [
            'is_paused' => 1,
            'snooze_until' => null,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alert paused successfully.'
        ]);
    }

    public function resumeAlert($id)
    {
        $alertModel = model(JobAlertModel::class);
        $user = $this->auth->user();

        $alert = $alertModel->find($id);

        if (! $alert || $alert->job_seeker_id != $this->getCandidateId($user->id)) {
            return $this->response->setJSON(['success' => false]);
        }

        $alertModel->update($id, [
            'is_paused' => 0,
            'snooze_until' => null,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alert resumed successfully.'
        ]);
    }

    public function snoozeAlert($id)
    {
        $days = (int) $this->request->getPost('days'); // e.g. 1, 7, 30
        $user = $this->auth->user();

        if ($days <= 0) {
            return $this->response->setJSON(['success' => false]);
        }

        $alertModel = model(JobAlertModel::class);
        $alert = $alertModel->find($id);

        if (! $alert || $alert->job_seeker_id != $this->getCandidateId($user->id)) {
            return $this->response->setJSON(['success' => false]);
        }

        $alertModel->update($id, [
            'is_paused' => 1,
            'snooze_until' => date('Y-m-d H:i:s', strtotime("+{$days} days")),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => "Alert snoozed for {$days} days."
        ]);
    }

    private function getCandidateId(int $userId): ?int
    {
        $candidateModel = model(JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $userId)->first();

        return $candidate ? $candidate->id : null;
    }
}