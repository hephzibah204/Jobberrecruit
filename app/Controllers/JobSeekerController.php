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
            ->where('user_id', $this->auth->user()->id)
            ->countAllResults();

        // Jobs Viewed
        $jobsViewed = $jobClickModel
            ->where('user_id', $this->auth->user()->id)
            ->countAllResults();

        // Candidate industries (pivot) drive recommendations + the match score.
        $industryIds = array_column(
            model(JobSeekerIndustryModel::class)->where('job_seeker_id', $candidate->id)->findAll(),
            'industry_id'
        );
        if (! empty($industryIds)) {
            // Primary industry, used by MatchService for the industry-alignment signal.
            $candidate->industry_id = $industryIds[0];
        }

        // Recommended Jobs — prefer the candidate's industries, fall back to latest openings.
        $jobQuery = $jobModel->orderBy('created_at', 'DESC');
        if (! empty($industryIds)) {
            $jobQuery->whereIn('industry_id', $industryIds);
        }
        $recommendedJobs = $jobQuery->limit(6)->findAll();
        if (empty($recommendedJobs)) {
            $recommendedJobs = $jobModel->orderBy('created_at', 'DESC')->limit(6)->findAll();
        }

        // Attach a real, computed match score to each recommendation.
        $recommendedJobs = (new \App\Services\MatchService())->scoreJobs($candidate, $recommendedJobs);

        // Recent Applications (limit 5)
        $recentApplications = $applicationModel
            ->select('job_applications.*, jobs.title as job_title, employers.company_name')
            ->join('jobs', 'jobs.id = job_applications.job_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('job_applications.job_seeker_id', $candidate->id)
            ->orderBy('job_applications.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // (Optional) recent applications count for the welcome banner
        $recentApplicationsCount = count($recentApplications);

        // Pending count across ALL applications (not just the 5 most recent) for the AI-hero banner
        $pendingApplicationsCount = $applicationModel
            ->where('job_seeker_id', $candidate->id)
            ->where('status', 'pending')
            ->countAllResults();

        // ====== Weekly Chart Data (job clicks per day, Mon → Sun) ======
        $weeklyChartData = $this->getWeeklyJobClicks($this->auth->user()->id);

        // ====== Skill Categories (from candidate profile skills string) ======
        $skillCategories = $this->buildSkillCategories($candidate, $recommendedJobs);

        // Profile Completion
        $profileCompletion = $candidate->getProfileCompletion();
        $this->checkAndRewardProfileCompletion((int) $this->auth->user()->id, $profileCompletion);

        // Aptitude Test Invitations (for Assessment Centre banner / widget)
        $invitationModel = model(\App\Models\AptitudeTestInvitationModel::class);
        $aptitudeInvitations = $invitationModel->getForCandidate((int) $this->auth->user()->id);

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
            'pendingApplicationsCount' => $pendingApplicationsCount,
            'profileCompletion'      => $profileCompletion,
            'profileChecklist'       => $candidate ? $candidate->getProfileChecklist() : [],
            'weeklyChartData'        => $weeklyChartData,
            'skillCategories'        => $skillCategories,
            'aptitudeInvitations'    => $aptitudeInvitations,
        ]);
    }


    public function profile()
    {
        $candidateModel = model(JobSeekerModel::class);

        // Get candidate profile with location
        $candidate = $candidateModel
            ->select('job_seekers.*, states.name as location')
            ->join('states', 'states.id = job_seekers.state_id', 'left')
            ->where('user_id', $this->auth->user()->id)
            ->first();

        // If candidate profile does not exist, redirect to edit profile
        if (!$candidate) {
            return redirect()->to('candidate/profile/edit')
                ->with('error', 'Please create your profile first.');
        }

        // Earned JobberRecruit certificates (auto-attach to profile, like the mockup)
        $certificates = model(\App\Models\CourseCertificateModel::class)
            ->getUserCertificates($this->auth->user()->id);

        // Structured work experience, education history & external certifications
        $experiences            = model(\App\Models\JobSeekerExperienceModel::class)->forSeeker($candidate->id);
        $education              = model(\App\Models\JobSeekerEducationModel::class)->forSeeker($candidate->id);
        $externalCertifications = model(\App\Models\JobSeekerCertificationModel::class)->forSeeker($candidate->id);

        $data = [
            'title'                  => 'Profile',
            'user'                   => $this->auth->user(),
            'candidate'              => $candidate,
            'certificates'           => $certificates,
            'externalCertifications' => $externalCertifications,
            'experiences'            => $experiences,
            'education'              => $education,
        ];

        $this->checkAndRewardProfileCompletion((int) $this->auth->user()->id, $candidate->getProfileCompletion());

        return view('candidate/profile', $data);
    }


    public function edit_profile()
    {
        $user = $this->auth->user();

        // Fetch the candidate record
        $candidateModel = new JobSeekerModel();
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        if (!$candidate) {
            $candidateModel->insert([
                'user_id'   => $user->id,
                'full_name' => $user->username ?? 'Candidate',
            ]);
            $candidate = $candidateModel->where('user_id', $user->id)->first();
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

            // Validation Rules: only full_name is strictly required to identify the candidate
            $rules = [
                'full_name'  => 'required|min_length[2]',
                'phone'      => 'permit_empty|min_length[6]',
                'state_id'   => 'permit_empty|is_natural_no_zero',
                'job_title'  => 'permit_empty|min_length[2]',
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status'     => 'error',
                    'errors'     => $this->validator->getErrors(),
                    'csrf_token' => csrf_token(),
                    'csrf_hash'  => csrf_hash(),
                ]);
            }

            $portfolio = trim((string) $this->request->getPost('portfolio'));
            if ($portfolio && !preg_match('#^https?://#i', $portfolio)) {
                $portfolio = 'https://' . $portfolio;
            }

            // Collect POST Data with safe nulls for empty optional fields
            $stateIdRaw  = $this->request->getPost('state_id');
            $expYearsRaw = $this->request->getPost('experience_years');
            $salaryRaw   = $this->request->getPost('desired_salary');

            $data = [
                'full_name'        => trim((string) $this->request->getPost('full_name')),
                'dob'              => trim((string) $this->request->getPost('dob')) ?: null,
                'gender'           => trim((string) $this->request->getPost('gender')) ?: null,
                'phone'            => trim((string) $this->request->getPost('phone')) ?: null,
                'location'         => trim((string) $this->request->getPost('location')) ?: null,
                'state_id'         => !empty($stateIdRaw) ? (int) $stateIdRaw : null,
                'availability'     => trim((string) $this->request->getPost('availability')) ?: null,
                'job_title'        => trim((string) $this->request->getPost('job_title')) ?: null,
                'employment_type'  => trim((string) $this->request->getPost('employment_type')) ?: null,
                'skills'           => trim((string) $this->request->getPost('skills')) ?: null,
                'experience_years' => ($expYearsRaw !== '' && $expYearsRaw !== null) ? (int) $expYearsRaw : null,
                'education_level'  => trim((string) $this->request->getPost('education_level')) ?: null,
                'languages'        => trim((string) $this->request->getPost('languages')) ?: null,
                'desired_salary'   => ($salaryRaw !== '' && $salaryRaw !== null) ? (float) $salaryRaw : null,
                'salary_type'      => trim((string) $this->request->getPost('salary_type')) ?: null,
                'portfolio'        => $portfolio ?: null,
                'bio'              => trim((string) $this->request->getPost('bio')) ?: null,
            ];

            if ($this->request->getPost('is_visible') !== null) {
                $data['is_visible'] = in_array($this->request->getPost('is_visible'), ['1', 1, 'on', 'true', true], true) ? 1 : 0;
            }

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
            $profileCheck = $this->validateUploadedFile($profileFile, [
                'jpg' => ['image/jpeg'], 'jpeg' => ['image/jpeg'], 'png' => ['image/png'], 'webp' => ['image/webp'],
            ], 2048);
            if ($profileFile && $profileFile->isValid() && !$profileCheck['valid']) {
                return redirect()->back()->withInput()->with('error', $profileCheck['error']);
            }
            if ($profileCheck['valid']) {

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
            $resumeCheck = $this->validateUploadedFile($resumeFile, [
                'pdf' => ['application/pdf'],
                'doc' => ['application/msword'],
                'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
            ], 5120);
            if ($resumeFile && $resumeFile->isValid() && !$resumeCheck['valid']) {
                return redirect()->back()->withInput()->with('error', $resumeCheck['error']);
            }
            if ($resumeCheck['valid']) {

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
            $db = \Config\Database::connect();
            $db->transStart();

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

            /**
             * SYNC WORK EXPERIENCE (delete + reinsert posted rows)
             */
            $normMonth = static fn ($v) => $v ? (preg_match('/^\d{4}-\d{2}$/', $v) ? $v . '-01' : $v) : null;
            $expModel  = model(\App\Models\JobSeekerExperienceModel::class);
            $expModel->where('job_seeker_id', $candidate->id)->delete();
            $expTitles  = (array) ($this->request->getPost('exp_job_title') ?? []);
            $expCompany = (array) ($this->request->getPost('exp_company') ?? []);
            $expLoc     = (array) ($this->request->getPost('exp_location') ?? []);
            $expStart   = (array) ($this->request->getPost('exp_start') ?? []);
            $expEnd     = (array) ($this->request->getPost('exp_end') ?? []);
            $expCurrent = (array) ($this->request->getPost('exp_is_current') ?? []);
            $expDesc    = (array) ($this->request->getPost('exp_description') ?? []);
            foreach ($expTitles as $i => $t) {
                $t = trim((string) $t);
                if ($t === '') continue;
                $isCurrent = in_array($expCurrent[$i] ?? 0, ['1', 1, 'on', 'true', true], true) ? 1 : 0;
                $expModel->insert([
                    'job_seeker_id' => $candidate->id,
                    'job_title'     => $t,
                    'company'       => trim((string) ($expCompany[$i] ?? '')) ?: null,
                    'location'      => trim((string) ($expLoc[$i] ?? '')) ?: null,
                    'start_date'    => $normMonth(trim((string) ($expStart[$i] ?? ''))),
                    'end_date'      => $isCurrent ? null : $normMonth(trim((string) ($expEnd[$i] ?? ''))),
                    'is_current'    => $isCurrent,
                    'description'   => trim((string) ($expDesc[$i] ?? '')) ?: null,
                    'sort_order'    => $i,
                ]);
            }

            /**
             * SYNC EDUCATION (delete + reinsert posted rows)
             */
             $eduModel = model(\App\Models\JobSeekerEducationModel::class);
             $eduModel->where('job_seeker_id', $candidate->id)->delete();
             $eduDegree = (array) ($this->request->getPost('edu_degree') ?? []);
             $eduField  = (array) ($this->request->getPost('edu_field') ?? []);
             $eduSchool = (array) ($this->request->getPost('edu_school') ?? []);
             $eduStart  = (array) ($this->request->getPost('edu_start_year') ?? []);
             $eduEnd    = (array) ($this->request->getPost('edu_end_year') ?? []);
             $eduGrade  = (array) ($this->request->getPost('edu_grade') ?? []);
             foreach ($eduDegree as $i => $d) {
                 $d = trim((string) $d);
                 if ($d === '') continue;
                 $eduModel->insert([
                     'job_seeker_id'  => $candidate->id,
                     'degree'         => $d,
                     'field_of_study' => trim((string) ($eduField[$i] ?? '')) ?: null,
                     'school'         => trim((string) ($eduSchool[$i] ?? '')) ?: null,
                     'start_year'     => trim((string) ($eduStart[$i] ?? '')) ?: null,
                     'end_year'       => trim((string) ($eduEnd[$i] ?? '')) ?: null,
                     'grade'          => trim((string) ($eduGrade[$i] ?? '')) ?: null,
                     'sort_order'     => $i,
                 ]);
             }

            /**
             * SYNC CERTIFICATIONS (delete + reinsert posted rows)
             */
            $certModel = model(\App\Models\JobSeekerCertificationModel::class);
            try {
                $certModel->where('job_seeker_id', $candidate->id)->delete();
                $certNames    = (array) ($this->request->getPost('cert_name') ?? []);
                $certOrgs     = (array) ($this->request->getPost('cert_org') ?? []);
                $certIds      = (array) ($this->request->getPost('cert_id') ?? []);
                $certUrls     = (array) ($this->request->getPost('cert_url') ?? []);
                $certIsMonths = (array) ($this->request->getPost('cert_issue_month') ?? []);
                $certIsYears  = (array) ($this->request->getPost('cert_issue_year') ?? []);
                $certNoExps   = (array) ($this->request->getPost('cert_no_expire') ?? []);
                $certExMonths = (array) ($this->request->getPost('cert_exp_month') ?? []);
                $certExYears  = (array) ($this->request->getPost('cert_exp_year') ?? []);

                foreach ($certNames as $i => $cn) {
                    $cn = trim((string) $cn);
                    if ($cn === '') continue;
                    $noExp = in_array($certNoExps[$i] ?? 0, ['1', 1, 'on', 'true', true], true) ? 1 : 0;
                    $certModel->insert([
                        'job_seeker_id'        => $candidate->id,
                        'name'                 => $cn,
                        'issuing_organization' => trim((string) ($certOrgs[$i] ?? '')) ?: null,
                        'credential_id'        => trim((string) ($certIds[$i] ?? '')) ?: null,
                        'credential_url'       => trim((string) ($certUrls[$i] ?? '')) ?: null,
                        'issue_month'          => trim((string) ($certIsMonths[$i] ?? '')) ?: null,
                        'issue_year'           => trim((string) ($certIsYears[$i] ?? '')) ?: null,
                        'does_not_expire'      => $noExp,
                        'expiry_month'         => $noExp ? null : (trim((string) ($certExMonths[$i] ?? '')) ?: null),
                        'expiry_year'          => $noExp ? null : (trim((string) ($certExYears[$i] ?? '')) ?: null),
                        'sort_order'           => $i,
                    ]);
                }
            } catch (\Throwable $e) {
                log_message('error', 'Error syncing certifications: ' . $e->getMessage());
            }

            // Sync profile_completion column in DB
            $updatedCandidate = $candidateModel->find($candidate->id);
            if ($updatedCandidate) {
                $newCompletion = $updatedCandidate->getProfileCompletion();
                $candidateModel->update($candidate->id, [
                    'profile_completion' => $newCompletion
                ]);
                $this->checkAndRewardProfileCompletion((int) $this->auth->user()->id, $newCompletion);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update profile. Database transaction error.'
                ]);
            }

            return $this->response->setJSON([
                'status'     => 'success',
                'message'    => 'Profile updated successfully.',
                'csrf_token' => csrf_token(),
                'csrf_hash'  => csrf_hash(),
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
            ->findColumn('industry_id') ?? [];

        // Existing structured history for repeatable form sections
        $experiences    = model(\App\Models\JobSeekerExperienceModel::class)->forSeeker($candidate->id);
        $education      = model(\App\Models\JobSeekerEducationModel::class)->forSeeker($candidate->id);
        $certifications = model(\App\Models\JobSeekerCertificationModel::class)->forSeeker($candidate->id);

        return view('candidate/edit_profile', [
            'title'                => 'Edit Profile',
            'user'                 => $user,
            'candidate'            => $candidate,
            'industries'           => $parentIndustries,
            'states'               => $states,
            'candidateIndustryIds' => $candidateIndustryIds,
            'experiences'          => $experiences,
            'education'            => $education,
            'certifications'       => $certifications,
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

        return view('candidate/settings', [
            'title' => 'General Settings',
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
     * Toggle the "visible to employers" flag (AJAX). Backs the profile visibility switch.
     * When hidden, the candidate no longer appears in employer candidate search.
     */
    public function toggleVisibility()
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->response->setJSON([
                'success'    => false,
                'message'    => 'Authentication required.',
                'csrf_token' => csrf_token(),
                'csrf_hash'  => csrf_hash(),
            ])->setStatusCode(401);
        }

        $candidateModel = model(JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();
        if (!$candidate) {
            return $this->response->setJSON([
                'success'    => false,
                'message'    => 'Profile not found.',
                'csrf_token' => csrf_token(),
                'csrf_hash'  => csrf_hash(),
            ])->setStatusCode(404);
        }

        // Use the posted value or JSON payload when present, otherwise flip the current state.
        $json = $this->request->getJSON(true);
        $raw = $this->request->getPost('is_visible');
        if ($raw === null && isset($json['is_visible'])) {
            $raw = $json['is_visible'];
        }

        if ($raw === null) {
            $newValue = $candidate->is_visible ? 0 : 1;
        } else {
            $newValue = in_array($raw, ['1', 1, 'true', true, 'on'], true) ? 1 : 0;
        }

        $candidateModel->update($candidate->id, ['is_visible' => $newValue]);

        return $this->response->setJSON([
            'success'    => true,
            'is_visible' => (int) $newValue,
            'message'    => $newValue
                ? 'Your profile is now visible to employers.'
                : 'Your profile is now hidden from employer search.',
            'csrf_token' => csrf_token(),
            'csrf_hash'  => csrf_hash(),
        ]);
    }

    /**
     * Save per-channel notification preferences (AJAX).
     */
    public function saveNotificationPreferences()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $candidateModel = model(JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $this->auth->user()->id)->first();
        if (! $candidate) {
            return $this->response->setJSON(['success' => false, 'message' => 'Profile not found.']);
        }

        $flag = fn (string $name) => in_array(
            $this->request->getPost($name),
            ['1', 1, 'true', true, 'on'],
            true
        ) ? 1 : 0;

        $candidateModel->update($candidate->id, [
            'notify_job_alerts'          => $flag('notify_job_alerts'),
            'notify_weekly_digest'       => $flag('notify_weekly_digest'),
            'notify_application_updates' => $flag('notify_application_updates'),
            'notify_messages'            => $flag('notify_messages'),
            'notify_marketing'           => $flag('notify_marketing'),
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Notification preferences saved.']);
    }

    /**
     * Permanently delete the candidate's account and all associated data (GDPR erasure).
     * Requires password confirmation. This is irreversible.
     */
    public function deleteAccount()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $user = $this->auth->user();

        // Confirm the password before destroying anything.
        $authenticator = auth()->getAuthenticator();
        if (! $authenticator->check([
            'email'    => $user->email,
            'password' => (string) $this->request->getPost('password'),
        ])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Password is incorrect.']);
        }

        $db = \Config\Database::connect();
        $candidate = model(JobSeekerModel::class)->where('user_id', $user->id)->first();

        // Best-effort deletion of owned rows. Each is guarded so an unexpected
        // schema difference can never block the authoritative user deletion below.
        $safeDelete = static function (string $table, string $column, $value) use ($db): void {
            try {
                if ($db->tableExists($table)) {
                    $db->table($table)->where($column, $value)->delete();
                }
            } catch (\Throwable $e) {
                log_message('error', 'deleteAccount cleanup failed on ' . $table . ': ' . $e->getMessage());
            }
        };

        if ($candidate) {
            $cid = $candidate->id;
            foreach ([
                'job_applications'        => 'job_seeker_id',
                'job_alerts'              => 'job_seeker_id',
                'candidate_notifications' => 'candidate_id',
                'job_seeker_experiences'  => 'job_seeker_id',
                'job_seeker_education'    => 'job_seeker_id',
                'job_seeker_industries'   => 'job_seeker_id',
            ] as $table => $column) {
                $safeDelete($table, $column, $cid);
            }
        }

        // Resume builder children are keyed by resume_id — clear them first.
        try {
            if ($db->tableExists('resumes')) {
                $resumeIds = array_column($db->table('resumes')->select('id')->where('user_id', $user->id)->get()->getResultArray(), 'id');
                if ($resumeIds) {
                    foreach (['resume_autosaves', 'resume_education', 'resume_experiences', 'resume_skills'] as $child) {
                        if ($db->tableExists($child)) {
                            $db->table($child)->whereIn('resume_id', $resumeIds)->delete();
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'deleteAccount resume cleanup failed: ' . $e->getMessage());
        }

        foreach ([
            'saved_jobs'          => 'user_id',
            'job_clicks'          => 'user_id',
            'wallets'             => 'user_id',
            'resumes'             => 'user_id',
            'course_certificates' => 'user_id',
            'course_enrollments'  => 'user_id',
            'job_seekers'         => 'user_id',
        ] as $table => $column) {
            $safeDelete($table, $column, $user->id);
        }

        // Authoritative removal of the identity (purges auth_identities, tokens, etc.).
        model(\CodeIgniter\Shield\Models\UserModel::class)->delete($user->id, true);

        // End the session.
        auth()->logout();
        session()->destroy();

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Your account and all associated data have been permanently deleted.',
            'redirect' => site_url('/'),
        ]);
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

    public function savedJobs()
    {
        $user = $this->auth->user();
        $candidateModel = model(JobSeekerModel::class);
        $savedJobModel = model('App\Models\SavedJobModel');
        $jobModel = model('App\Models\JobModel');

        $candidate = $candidateModel
            ->where('user_id', $user->id)
            ->first();

        if (!$candidate) {
            return redirect()->to('candidate/profile/edit')
                ->with('error', 'Please complete your profile first.');
        }

        $savedJobIds = $savedJobModel
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $jobIds = array_column($savedJobIds, 'job_id');

        $savedJobs = [];
        if (!empty($jobIds)) {
            $savedJobs = $jobModel
                ->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.company_name, employers.logo')
                ->join('states', 'states.id = jobs.state_id', 'left')
                ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
                ->join('industries', 'industries.id = jobs.industry_id', 'left')
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                ->whereIn('jobs.id', $jobIds)
                ->where('jobs.status', 'open')
                ->orderBy('jobs.created_at', 'DESC')
                ->findAll();
        }

        return view('candidate/saved_jobs', [
            'title'     => 'Saved Jobs',
            'user'      => $user,
            'candidate' => $candidate,
            'savedJobs' => $savedJobs,
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

        // Fetch in-app notifications for this candidate
        $candidateNotifModel = model(\App\Models\CandidateNotificationModel::class);
        $inAppNotifications = $candidateNotifModel->getNotifications((int)$candidate->id);

        // Fetch job alerts for this candidate
        $alerts = $alertModel
            ->where('job_seeker_id', $candidate->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // For filter options
        $industries = $industryModel->findAll();
        $categories = $categoryModel->findAll();

        $states = $state->orderBy('name', 'ASC')->findAll();

        // Pre-fill from query params (e.g. from industry/location hub pages)
        $presetKeyword    = $this->request->getGet('industry') ? $industryModel->find($this->request->getGet('industry'))?->name : null;
        $presetLocationId = $this->request->getGet('state');

        return view('candidate/notifications', [
            'title'              => 'Notifications & Job Alerts',
            'user'               => $user,
            'candidate'          => $candidate,
            'inAppNotifications' => $inAppNotifications,
            'alerts'             => $alerts,
            'industries'         => $industries,
            'categories'         => $categories,
            'states'             => $states,
            'presetKeyword'      => $presetKeyword,
            'presetLocationId'   => $presetLocationId
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
            'frequency'     => $this->request->getPost('frequency') ?: 'daily',
            'delivery_time' => $this->request->getPost('delivery_time') ?: '08:00',
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
        $user = $this->auth->user();

        $alert = $alertModel->find($id);

        if (! $alert || $alert->job_seeker_id != $this->getCandidateId($user->id)) {
            return $this->response->setJSON(['success' => false]);
        }

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

    /**
     * Build an array of 7 integers: job-click counts per day (Mon → Sun) for the current week.
     */
    private function getWeeklyJobClicks(int $userId): array
    {
        $db = \Config\Database::connect();

        // Monday of this week 00:00:00
        $weekStart = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $weekEnd   = date('Y-m-d 23:59:59', strtotime('sunday this week'));

        $rows = $db->table('job_clicks')
            ->select('DAYOFWEEK(created_at) as dow, COUNT(*) as cnt')
            ->where('user_id', $userId)
            ->where('created_at >=', $weekStart)
            ->where('created_at <=', $weekEnd)
            ->groupBy('DAYOFWEEK(created_at)')
            ->get()
            ->getResultArray();

        // DAYOFWEEK returns: 1=Sun, 2=Mon, 3=Tue, … 7=Sat
        // We want Mon→Sun (indices 0–6).
        $map = [];
        foreach ($rows as $r) {
            // Convert DAYOFWEEK to 0-based Mon index
            $dow = (int) $r['dow'];           // 1=Sun..7=Sat
            $monIdx = ($dow + 5) % 7;          // Mon=0 … Sun=6
            $map[$monIdx] = (int) $r['cnt'];
        }

        $result = [];
        for ($i = 0; $i < 7; $i++) {
            $result[] = $map[$i] ?? 0;
        }

        return $result;
    }

    /**
     * Parse the candidate's comma-separated skills string and compute a
     * match percentage for each skill based on how often it appears in
     * recommended job listings.
     *
     * Returns an array of objects with →name and →match properties.
     */
    private function buildSkillCategories(object $candidate, array $recommendedJobs): array
    {
        $rawSkills = trim((string) ($candidate->skills ?? ''));
        if ($rawSkills === '') {
            return [];
        }

        // Split on commas, semicolons, or newlines; trim each; deduplicate
        $candidateSkills = array_values(array_unique(
            array_map('trim', preg_split('/[,;\n]+/', $rawSkills))
        ));

        if (empty($candidateSkills)) {
            return [];
        }

        // Build a combined text blob from recommended job titles + descriptions
        $jobText = strtolower(implode(' ', array_map(
            fn($j) => ($j->title ?? '') . ' ' . ($j->description ?? ''),
            $recommendedJobs
        )));

        $jobCount = max(1, count($recommendedJobs));

        $categories = [];
        foreach ($candidateSkills as $skill) {
            $lower = strtolower($skill);
            // Count how many recommended jobs mention this skill
            $matches = 0;
            foreach ($recommendedJobs as $job) {
                $haystack = strtolower(($job->title ?? '') . ' ' . ($job->description ?? '') . ' ' . ($job->skills ?? ''));
                if (str_contains($haystack, $lower)) {
                    $matches++;
                }
            }
            $pct = (int) round(($matches / $jobCount) * 100);

            $obj = new \stdClass();
            $obj->name  = $skill;
            $obj->match = $pct;
            $categories[] = $obj;
        }

        // Sort by match descending, keep top 6
        usort($categories, fn($a, $b) => $b->match <=> $a->match);
        return array_slice($categories, 0, 6);
    }

    /**
     * GDPR: Export all user data
     */
    public function exportData()
    {
        $user = $this->auth->user();
        $candidateModel = model(JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        $data = [
            'account' => [
                'email' => $user->email,
                'username' => $user->username ?? '',
                'created_at' => $user->created_at ?? '',
                'last_active' => $user->last_active ?? '',
            ],
            'profile' => $candidate ? [
                'full_name' => $candidate->full_name ?? '',
                'phone' => $candidate->phone ?? '',
                'dob' => $candidate->dob ?? '',
                'gender' => $candidate->gender ?? '',
                'bio' => $candidate->bio ?? '',
                'skills' => $candidate->skills ?? '',
                'languages' => $candidate->languages ?? '',
                'experience_years' => $candidate->experience_years ?? '',
                'education_level' => $candidate->education_level ?? '',
                'job_title' => $candidate->job_title ?? '',
                'employment_type' => $candidate->employment_type ?? '',
                'location' => $candidate->location ?? '',
                'desired_salary' => $candidate->desired_salary ?? '',
                'salary_type' => $candidate->salary_type ?? '',
                'availability' => $candidate->availability ?? '',
                'portfolio' => $candidate->portfolio ?? '',
                'resume' => $candidate->resume ?? '',
                'is_visible' => $candidate->is_visible ?? '',
                'notification_preferences' => [
                    'job_alerts' => $candidate->notify_job_alerts ?? '',
                    'application_updates' => $candidate->notify_application_updates ?? '',
                    'messages' => $candidate->notify_messages ?? '',
                    'marketing' => $candidate->notify_marketing ?? '',
                ],
            ] : [],
            'work_experience' => $candidate
                ? model(\App\Models\JobSeekerExperienceModel::class)->forSeeker($candidate->id)
                : [],
            'education' => $candidate
                ? model(\App\Models\JobSeekerEducationModel::class)->forSeeker($candidate->id)
                : [],
            'certificates' => model(\App\Models\CourseCertificateModel::class)
                ->getUserCertificates($this->auth->user()->id),
            'applications' => model(\App\Models\JobApplicationModel::class)
                ->where('job_seeker_id', $candidate?->id)
                ->findAll(),
            'saved_jobs' => model(\App\Models\SavedJobModel::class)
                ->where('user_id', $this->auth->user()->id)
                ->findAll(),
            'job_alerts' => model(\App\Models\JobAlertModel::class)
                ->where('job_seeker_id', $candidate?->id)
                ->findAll(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'jobberrecruit_data_export_' . date('Y-m-d') . '.json';

        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($json);
    }

    /**
     * Transaction history for candidates
     */
    public function transactions()
    {
        $user = $this->auth->user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $transactions = [];
        $db = \Config\Database::connect();

        // 1. Get course enrollments with payments
        if ($db->tableExists('course_enrollments') && $db->tableExists('courses')) {
            $enrollments = model(\App\Models\CourseEnrollmentModel::class)
                ->select('course_enrollments.*, courses.title as course_name')
                ->join('courses', 'courses.id = course_enrollments.course_id', 'left')
                ->where('course_enrollments.user_id', $user->id)
                ->orderBy('course_enrollments.created_at', 'DESC')
                ->findAll();

            foreach ($enrollments as $enr) {
                $enrObj = (object)$enr;
                $transactions[] = [
                    'type'        => 'course',
                    'description' => 'Course: ' . ($enrObj->course_name ?? 'Unknown'),
                    'reference'   => $enrObj->payment_reference ?? 'FREE-' . ($enrObj->id ?? ''),
                    'amount'      => (float) ($enrObj->amount ?? 0),
                    'status'      => $enrObj->payment_reference ? 'success' : ($enrObj->amount > 0 ? 'pending' : 'success'),
                    'date'        => $enrObj->created_at ?? '',
                    'created_at'  => $enrObj->created_at ?? '',
                    'icon'        => 'ti ti-book',
                    'receipt_url' => '',
                ];
            }
        }

        // 2. Get subscription payments
        if ($db->tableExists('payments')) {
            $payments = model(\App\Models\PaymentModel::class)
                ->where('user_id', $user->id)
                ->orderBy('paid_at', 'DESC')
                ->findAll();

            foreach ($payments as $pay) {
                $payObj = (object)$pay;
                $metadata = is_string($payObj->metadata) ? json_decode($payObj->metadata, true) : (array)($payObj->metadata ?? []);
                $desc = 'Subscription Payment';
                if (!empty($metadata['plan_id']) && $db->tableExists('plans')) {
                    $plan = model(\App\Models\PlanModel::class)->find($metadata['plan_id']);
                    $desc = 'Subscription: ' . ($plan->name ?? 'Plan #' . $metadata['plan_id']);
                }
                $transactions[] = [
                    'type'        => 'subscription',
                    'description' => $desc,
                    'reference'   => $payObj->reference ?? '',
                    'amount'      => (float) ($payObj->amount ?? 0),
                    'status'      => $payObj->status ?? 'pending',
                    'date'        => $payObj->paid_at ?? $payObj->created_at ?? '',
                    'created_at'  => $payObj->paid_at ?? $payObj->created_at ?? '',
                    'icon'        => 'ti ti-credit-card',
                    'receipt_url' => '',
                ];
            }
        }

        // 3. Get wallet transactions (funding, rewards, etc.)
        if ($db->tableExists('wallets') && $db->tableExists('wallet_transactions')) {
            $walletModel = model(\App\Models\WalletModel::class);
            $wallet = $walletModel->where('user_id', $user->id)->first();
            if ($wallet) {
                $walletObj = (object)$wallet;
                $walletTxns = model(\App\Models\WalletTransactionModel::class)
                    ->where('wallet_id', $walletObj->id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
                foreach ($walletTxns as $wt) {
                    $wtObj = (object)$wt;
                    $transactions[] = [
                        'type'        => $wtObj->type ?? 'wallet',
                        'description' => $wtObj->description ?? 'Wallet Transaction',
                        'reference'   => $wtObj->reference ?? 'WTX-' . ($wtObj->id ?? ''),
                        'amount'      => (float) ($wtObj->amount ?? 0),
                        'status'      => 'success',
                        'date'        => $wtObj->created_at ?? '',
                        'created_at'  => $wtObj->created_at ?? '',
                        'icon'        => 'ti ti-wallet',
                        'receipt_url' => '',
                    ];
                }
            }
        }

        // Sort by date desc
        usort($transactions, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Compute totals safely from completed/success status
        $totalSpent = 0;
        $successCount = 0;
        foreach ($transactions as $t) {
            $statusLow = strtolower($t['status'] ?? '');
            $typeLow   = strtolower($t['type'] ?? '');
            $isSuccess = in_array($statusLow, ['success', 'successful', 'completed', 'credited', 'paid']);
            
            if ($isSuccess) {
                $successCount++;
                // If it is a debit (spent), increment totalSpent. 
                // Wallet credits (e.g. credit/reward) are not "spent".
                if ($typeLow !== 'credit' && !str_contains(strtolower($t['description']), 'reward')) {
                    $totalSpent += $t['amount'];
                }
            }
        }

        return view('candidate/transactions', [
            'title'        => 'Transaction History',
            'transactions' => $transactions,
            'totalSpent'   => $totalSpent,
        ]);
    }

    /**
     * Check profile completion percentage and credit wallet rewards (₦500 at 80% completion threshold).
     */
    protected function checkAndRewardProfileCompletion(int $userId, int $completionPct): void
    {
        if ($userId <= 0) {
            return;
        }

        try {
            $walletService = new \App\Services\WalletService();

            if ($completionPct >= 80) {
                $ref80 = 'profile_reward_80_user_' . $userId;
                $walletService->credit(
                    $userId,
                    500.00,
                    'profile_reward',
                    $ref80,
                    null,
                    '₦500 Profile Completion Incentive (80%+ Completion)'
                );
            }
        } catch (\Throwable $e) {
            log_message('error', 'Profile reward error for user ' . $userId . ': ' . $e->getMessage());
        }
    }
}