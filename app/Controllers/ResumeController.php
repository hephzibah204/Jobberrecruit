<?php

namespace App\Controllers;

use App\Models\ResumeModel;
use App\Models\ResumeExperienceModel;
use App\Models\ResumeEducationModel;
use App\Models\ResumeSkillModel;
use App\Services\AiService;
use CodeIgniter\API\ResponseTrait;

class ResumeController extends BaseController
{
    use ResponseTrait;

    protected $resumeModel;
    protected $experienceModel;
    protected $educationModel;
    protected $skillModel;
    protected $aiService;
    protected $autosaveModel;

    public function __construct()
    {
        $this->resumeModel = model(ResumeModel::class);
        $this->experienceModel = model(ResumeExperienceModel::class);
        $this->educationModel = model(ResumeEducationModel::class);
        $this->skillModel = model(ResumeSkillModel::class);
        $this->aiService = new AiService();
        $this->autosaveModel = model(\App\Models\ResumeAutosaveModel::class);
    }

    /**
     * Proxy an external AI-provided image URL through the server, validate and store.
     * Expects JSON body { origin_url: string }
     * Also processes pending queue entries if origin_url matches one.
     */
    public function proxyAiImage()
    {
        $origin = $this->request->getJSON(true)['origin_url'] ?? $this->request->getPost('origin_url');
        if (empty($origin) || !filter_var($origin, FILTER_VALIDATE_URL)) {
            return $this->fail('origin_url is required and must be a valid URL', 400);
        }

        if (stripos($origin, 'https://') !== 0) {
            return $this->fail('Only https URLs are allowed for proxied images', 400);
        }

        $model = model(\App\Models\AiImageModel::class);
        $existing = $model->findByOriginUrl($origin);

        // If already completed, return existing proxied URL
        if ($existing && $existing->status === 'completed' && $existing->proxied_path) {
            return $this->respond(['url' => base_url($existing->proxied_path)]);
        }

        // Download, validate, store (shared logic used by spark command too)
        $result = $this->downloadAndStoreImage($origin, $model, $existing);
        if (isset($result['error'])) {
            return $this->fail($result['error'], 400);
        }

        return $this->respondCreated(['url' => $result['url']]);
    }

    /**
     * Download and proxy an image from an external URL.
     * Shared between the HTTP endpoint and the async queue processor.
     *
     * @return array{url?: string, error?: string}
     */
    public function downloadAndStoreImage(string $origin, \App\Models\AiImageModel $model, ?object $existingRow = null): array
    {
        $ch = curl_init($origin);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $data = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$data) {
            $msg = 'Failed to download image: ' . ($err ?: 'no data');
            if ($existingRow) {
                $model->markFailed($existingRow->id, $msg);
            }
            return ['error' => $msg];
        }

        $size = strlen($data);
        if ($size > 2 * 1024 * 1024) {
            $msg = 'Image exceeds maximum allowed size of 2MB';
            if ($existingRow) {
                $model->markFailed($existingRow->id, $msg);
            }
            return ['error' => $msg];
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($data);
        $allowed = ['image/jpeg','image/png','image/webp'];
        if (!in_array($mime, $allowed, true)) {
            $msg = 'Unsupported image MIME type';
            if ($existingRow) {
                $model->markFailed($existingRow->id, $msg);
            }
            return ['error' => $msg];
        }

        $checksum = hash('sha256', $data);

        // Deduplicate by checksum across all records
        $dup = $model->where('checksum', $checksum)->where('status', 'completed')->first();
        if ($dup && $dup->proxied_path) {
            // Update the pending row to reuse the same file
            if ($existingRow && $existingRow->id !== $dup->id) {
                $model->markCompleted($existingRow->id, $dup->proxied_path, $checksum, $mime, $size);
            }
            return ['url' => base_url($dup->proxied_path)];
        }

        $ext = $mime === 'image/png' ? 'png' : ($mime === 'image/webp' ? 'webp' : 'jpg');
        $dir = FCPATH . 'uploads/ai-images/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = $checksum . '.' . $ext;
        $path = $dir . $filename;
        file_put_contents($path, $data);
        $publicPath = 'uploads/ai-images/' . $filename;

        if ($existingRow) {
            $model->markCompleted($existingRow->id, $publicPath, $checksum, $mime, $size);
        } else {
            $model->insert([
                'origin_url' => $origin,
                'proxied_path' => $publicPath,
                'checksum' => $checksum,
                'mime' => $mime,
                'size' => $size,
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s'),
                'processed_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return ['url' => base_url($publicPath)];
    }

    /**
     * List all resumes for the current user
     */
    public function index()
    {
        $user = auth()->user();
        $resumes = $this->resumeModel->where('user_id', $user->id)->findAll();

        return view('candidate/resume/index', [
            'title' => 'My Resumes',
            'resumes' => $resumes
        ]);
    }

    /**
     * Show the resume builder
     */
    public function build($id = null)
    {
        $user = auth()->user();
        $resume = null;
        $experiences = [];
        $education = [];
        $skills = [];

        if ($id) {
            $resume = $this->resumeModel->where('user_id', $user->id)->find($id);
            if (!$resume) {
                return redirect()->to('candidate/resumes')->with('error', 'Resume not found');
            }
            $experiences = $this->experienceModel->where('resume_id', $id)->findAll();
            $education = $this->educationModel->where('resume_id', $id)->findAll();
            $skills = $this->skillModel->where('resume_id', $id)->findAll();
        }

        $candidate = model(\App\Models\JobSeekerModel::class)->where('user_id', $user->id)->first();

        // Auto-fill from candidate profile for new resumes or when sections are empty
        if ($candidate) {
            if (!$id) {
                $resume = (object)[
                    'id'          => null,
                    'title'       => !empty($candidate->job_title) ? $candidate->job_title . ' Resume' : 'My Professional Resume',
                    'full_name'   => $candidate->full_name ?? '',
                    'email'       => $user->email ?? '',
                    'phone'       => $candidate->phone ?? '',
                    'location'    => $candidate->location ?? '',
                    'summary'     => $candidate->bio ?? '',
                    'template_id' => 'classic',
                    'ai_optimization_meta' => null,
                ];
            } else if ($resume && empty($resume->summary) && !empty($candidate->bio)) {
                $resume->summary = $candidate->bio;
            }

            // Auto-pull work experience from candidate profile if empty
            if (empty($experiences)) {
                $expModel = new \App\Models\JobSeekerExperienceModel();
                $profileExps = $expModel->forSeeker((int) $candidate->id);
                if (!empty($profileExps)) {
                    foreach ($profileExps as $pe) {
                        $experiences[] = (object)[
                            'company'     => $pe->company ?? '',
                            'position'    => $pe->job_title ?? '',
                            'description' => $pe->description ?? '',
                            'start_date'  => $pe->start_date ?? null,
                            'end_date'    => $pe->end_date ?? null,
                            'is_current'  => (int) ($pe->is_current ?? 0),
                        ];
                    }
                }
            }

            // Auto-pull education from candidate profile if empty
            if (empty($education)) {
                $eduModel = new \App\Models\JobSeekerEducationModel();
                $profileEdus = $eduModel->forSeeker((int) $candidate->id);
                if (!empty($profileEdus)) {
                    foreach ($profileEdus as $pedu) {
                        $gradDate = !empty($pedu->end_year) ? $pedu->end_year . '-12-31' : null;
                        $education[] = (object)[
                            'institution'     => $pedu->school ?? '',
                            'degree'          => $pedu->degree ?? '',
                            'field_of_study'  => $pedu->field_of_study ?? '',
                            'graduation_date' => $gradDate,
                        ];
                    }
                }
            }

            // Auto-pull skills from candidate profile if empty
            if (empty($skills) && !empty($candidate->skills)) {
                $skillsArr = is_array($candidate->skills) ? $candidate->skills : array_filter(array_map('trim', explode(',', $candidate->skills)));
                foreach ($skillsArr as $sk) {
                    $skName = trim(is_array($sk) ? ($sk['value'] ?? $sk['skill_name'] ?? '') : $sk);
                    if ($skName) {
                        $skills[] = (object)[
                            'skill_name'        => $skName,
                            'proficiency_level' => 'intermediate'
                        ];
                    }
                }
            }
        }

        // 1. Identify jobs relevant to the candidate/profile
        $jobModel = model(\App\Models\JobModel::class);
        $industryIds = array_column(
            model(\App\Models\JobSeekerIndustryModel::class)->where('job_seeker_id', $candidate->id)->findAll(),
            'industry_id'
        );
        if (!empty($industryIds)) {
            $candidate->industry_id = $industryIds[0];
        }

        $jobQuery = $jobModel->select(['jobs.id', 'jobs.title', 'jobs.description', 'jobs.skills', 'jobs.requirements', 'jobs.industry_id', 'employers.company_name'])
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('jobs.status', 'open')
            ->orderBy('jobs.created_at', 'DESC');
        
        if (!empty($industryIds)) {
            $jobQuery->whereIn('jobs.industry_id', $industryIds);
        }
        
        $tailorJobs = $jobQuery->limit(20)->findAll();
        
        if (empty($tailorJobs)) {
            $tailorJobs = $jobModel->select(['jobs.id', 'jobs.title', 'jobs.description', 'jobs.skills', 'jobs.requirements', 'jobs.industry_id', 'employers.company_name'])
                ->join('employers', 'employers.id = jobs.employer_id', 'left')
                ->where('jobs.status', 'open')
                ->orderBy('jobs.created_at', 'DESC')
                ->limit(20)
                ->findAll();
        }

        // Score jobs based on match and sort by highest match
        $tailorJobs = (new \App\Services\MatchService())->scoreJobs($candidate, $tailorJobs);
        usort($tailorJobs, fn($a, $b) => ($b->match_score ?? 0) <=> ($a->match_score ?? 0));
        
        // Take top 15 most relevant
        $tailorJobs = array_slice($tailorJobs, 0, 15);

        $allResumesQuery = $this->resumeModel->where('user_id', $user->id);
        if ($id) {
            $allResumesQuery->where('id !=', $id);
        }
        $allResumes = $allResumesQuery->orderBy('updated_at', 'DESC')->findAll();

        $linkedin = $candidate?->linkedin_url ?? '';
        $certs = '';
        $languages = '';
        if ($resume && !empty($resume->ai_optimization_meta)) {
            $meta = json_decode($resume->ai_optimization_meta, true);
            if (is_array($meta)) {
                $linkedin = $meta['linkedin'] ?? $linkedin;
                $certs = $meta['certs'] ?? '';
                $languages = $meta['languages'] ?? '';
            }
        }

        return view('candidate/resume/builder', [
            'title'      => $resume ? 'Edit Resume' : 'Create Resume',
            'resume'     => $resume,
            'experiences'=> $experiences,
            'education'  => $education,
            'skills'     => $skills,
            'candidate'  => $candidate,
            'allResumes' => $allResumes,
            'tailorJobs' => $tailorJobs,
            'linkedin'   => $linkedin,
            'certs'      => $certs,
            'languages'  => $languages,
        ]);
    }

    /**
     * Import candidate profile data into a new resume and redirect to builder
     */
    public function importFromProfile()
    {
        $user = auth()->user();
        $candidateModel = model(\App\Models\JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        if (!$candidate) {
            return redirect()->to('candidate/resumes/build')->with('error', 'Profile not found. Please complete your profile first.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Create a new resume pre-seeded from profile
        $resumeId = $this->resumeModel->insert([
            'user_id'     => $user->id,
            'title'       => ($candidate->job_title ?? 'My') . ' Resume',
            'summary'     => $candidate->bio ?? '',
            'template_id' => 'classic',
        ]);

        if (!$resumeId) {
            $db->transRollback();
            return redirect()->to('candidate/resumes/build')->with('error', 'Could not create resume. Please try again.');
        }

        // Seed skills from profile (comma-separated)
        if (!empty($candidate->skills)) {
            foreach (explode(',', $candidate->skills) as $skillName) {
                $skillName = trim($skillName);
                if ($skillName) {
                    $this->skillModel->insert([
                        'resume_id'        => $resumeId,
                        'skill_name'       => $skillName,
                        'proficiency_level'=> 'intermediate',
                    ]);
                }
            }
        }

        // Seed full education history from profile
        $eduModel = new \App\Models\JobSeekerEducationModel();
        $profileEdus = $eduModel->forSeeker((int) $candidate->id);
        if (!empty($profileEdus)) {
            foreach ($profileEdus as $edu) {
                $gradDate = null;
                if (!empty($edu->end_year)) {
                    $gradDate = $edu->end_year . '-12-31';
                }
                $this->educationModel->insert([
                    'resume_id'       => $resumeId,
                    'institution'     => $edu->school ?? '',
                    'degree'          => $edu->degree ?? '',
                    'field_of_study'  => $edu->field_of_study ?? '',
                    'graduation_date' => $gradDate,
                ]);
            }
        }

        // Seed full work experience history from profile
        $expModel = new \App\Models\JobSeekerExperienceModel();
        $profileExps = $expModel->forSeeker((int) $candidate->id);
        if (!empty($profileExps)) {
            foreach ($profileExps as $exp) {
                $this->experienceModel->insert([
                    'resume_id'   => $resumeId,
                    'company'     => $exp->company ?? '',
                    'position'    => $exp->job_title ?? '',
                    'description' => $exp->description ?? '',
                    'start_date'  => $exp->start_date ?? null,
                    'end_date'    => $exp->end_date ?? null,
                    'is_current'  => (int) ($exp->is_current ?? 0),
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('candidate/resumes/build')->with('error', 'Failed to create resume from profile. Database error.');
        }

        return redirect()->to('candidate/resumes/build/' . $resumeId)
            ->with('success', 'Resume created from your profile! Complete the remaining details below.');
    }

    /**
     * Get Candidate Profile JSON for 1-click Auto-Fill in Resume Builder
     */
    public function getProfileData()
    {
        $user = auth()->user();
        $candidateModel = model(\App\Models\JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        if (!$candidate) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No profile found. Please complete your profile first.'
            ]);
        }

        // Experiences
        $expModel = new \App\Models\JobSeekerExperienceModel();
        $experiences = $expModel->forSeeker((int) $candidate->id);

        // Education
        $eduModel = new \App\Models\JobSeekerEducationModel();
        $education = $eduModel->forSeeker((int) $candidate->id);

        // Skills
        $skills = [];
        if (!empty($candidate->skills)) {
            $skillsArr = is_array($candidate->skills) ? $candidate->skills : array_filter(array_map('trim', explode(',', $candidate->skills)));
            foreach ($skillsArr as $sk) {
                $skName = trim(is_array($sk) ? ($sk['value'] ?? $sk['skill_name'] ?? '') : $sk);
                if ($skName) {
                    $skills[] = $skName;
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => [
                'full_name'   => $candidate->full_name ?? '',
                'email'       => $user->email ?? '',
                'phone'       => $candidate->phone ?? '',
                'location'    => $candidate->location ?? '',
                'job_title'   => $candidate->job_title ?? '',
                'bio'         => $candidate->bio ?? '',
                'skills'      => $skills,
                'experiences' => $experiences,
                'education'   => $education,
            ]
        ]);
    }

    /**
     * Clone an existing resume into a new copy and redirect to builder
     */
    public function cloneResume($id)
    {
        $user = auth()->user();
        $source = $this->resumeModel->where('user_id', $user->id)->find($id);

        if (!$source) {
            return redirect()->to('candidate/resumes')->with('error', 'Resume not found.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Clone the parent resume record
        $newResumeId = $this->resumeModel->insert([
            'user_id'     => $user->id,
            'title'       => 'Copy of ' . $source->title,
            'summary'     => $source->summary,
            'template_id' => $source->template_id,
        ]);

        if (!$newResumeId) {
            $db->transRollback();
            return redirect()->to('candidate/resumes')->with('error', 'Could not clone resume. Please try again.');
        }

        // Clone experiences
        $srcExps = $this->experienceModel->where('resume_id', $id)->findAll();
        foreach ($srcExps as $exp) {
            $this->experienceModel->insert([
                'resume_id'   => $newResumeId,
                'company'     => $exp->company,
                'position'    => $exp->position,
                'description' => $exp->description,
                'start_date'  => $exp->start_date,
                'end_date'    => $exp->end_date,
                'is_current'  => $exp->is_current,
            ]);
        }

        // Clone education
        $srcEdus = $this->educationModel->where('resume_id', $id)->findAll();
        foreach ($srcEdus as $edu) {
            $this->educationModel->insert([
                'resume_id'       => $newResumeId,
                'institution'     => $edu->institution,
                'degree'          => $edu->degree,
                'field_of_study'  => $edu->field_of_study,
                'graduation_date' => $edu->graduation_date,
            ]);
        }

        // Clone skills
        $srcSkills = $this->skillModel->where('resume_id', $id)->findAll();
        foreach ($srcSkills as $skill) {
            $this->skillModel->insert([
                'resume_id'         => $newResumeId,
                'skill_name'        => $skill->skill_name,
                'proficiency_level' => $skill->proficiency_level,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('candidate/resumes')->with('error', 'Failed to clone resume. Database error.');
        }

        return redirect()->to('candidate/resumes/build/' . $newResumeId)
            ->with('success', 'Resume cloned successfully! You can now edit your copy.');
    }

    /**
     * AJAX: Generate professional summary
     */
    public function generateSummary()
    {
        $experiences = $this->request->getPost('experiences') ?? [];
        $education = $this->request->getPost('education') ?? [];
        $skills = $this->request->getPost('skills') ?? [];

        // Build readable arrays for the AI prompt
        $expStrings = [];
        if (!empty($experiences) && is_array($experiences)) {
            foreach ($experiences as $e) {
                $company = trim($e['company'] ?? '');
                $position = trim($e['position'] ?? '');
                $desc = trim($e['description'] ?? '');
                $parts = [];
                if ($position) $parts[] = $position;
                if ($company) $parts[] = 'at ' . $company;
                if ($desc) $parts[] = '(' . substr($desc, 0, 150) . ')';
                if (!empty($parts)) $expStrings[] = implode(' ', $parts);
            }
        }

        $eduStrings = [];
        if (!empty($education) && is_array($education)) {
            foreach ($education as $ed) {
                $school = trim($ed['school'] ?? '');
                $degree = trim($ed['degree'] ?? '');
                $field = trim($ed['field'] ?? '');
                $parts = [];
                if ($degree) $parts[] = $degree;
                if ($field) $parts[] = $field;
                if ($school) $parts[] = 'at ' . $school;
                if (!empty($parts)) $eduStrings[] = implode(' ', $parts);
            }
        }

        $currentSummary = trim($this->request->getPost('current_summary') ?? '');
        if (!empty($currentSummary)) {
            $improved = $this->aiService->improveSummary($currentSummary, $skills);
            if (!empty($improved)) {
                $improved = $this->cleanAiOutput($improved);
                return $this->respond(['summary' => $improved]);
            }
        }

        if (empty($expStrings) && empty($skills) && empty($eduStrings)) {
            $user = auth()->user();
            $seeker = model(\App\Models\JobSeekerModel::class)->where('user_id', $user->id ?? 0)->first();
            $roleTitle = $seeker?->job_title ?? 'Professional';
            $summary = "Results-driven {$roleTitle} with a strong track record of success, proactive problem-solving, and cross-functional team leadership. Committed to driving operational excellence, continuous learning, and delivering measurable business impact.";
            return $this->respond(['summary' => $summary]);
        }

        $summary = $this->aiService->generateProfessionalSummary($expStrings, $skills, $eduStrings);
        return $this->respond(['summary' => $summary]);
    }

    /**
     * AJAX: Generate bullets for a specific experience using AI
     */
    public function generateBullets()
    {
        $description = $this->request->getPost('description')
            ?? $this->request->getVar('description')
            ?? ($this->request->getJSON(true)['description'] ?? '');
        $jobTitle = $this->request->getPost('job_title')
            ?? $this->request->getVar('job_title')
            ?? ($this->request->getJSON(true)['job_title'] ?? '');

        if (empty($description)) {
            return $this->fail('Description is required to generate bullets.');
        }

        $bullets = $this->aiService->generateBullets($description, $jobTitle);
        $bullets = $this->cleanAiOutput($bullets);

        return $this->respond(['bullets' => $bullets]);
    }

    /**
     * AJAX: Improve description / achievement
     */
    public function improveDescription()
    {
        $description = $this->request->getPost('description')
            ?? $this->request->getVar('description')
            ?? ($this->request->getJSON(true)['description'] ?? '');
        $jobTitle = $this->request->getPost('job_title')
            ?? $this->request->getVar('job_title')
            ?? ($this->request->getJSON(true)['job_title'] ?? '');

        if (empty($description)) {
            return $this->fail('Description cannot be empty.');
        }

        $improved = $this->aiService->improveDescription($description, $jobTitle);
        if (empty($improved)) {
            $improved = $description;
        } else {
            $improved = $this->cleanAiOutput($improved);
        }

        return $this->respond(['description' => $improved]);
    }

    /**
     * Clean AI output of conversational preamble or surrounding quotes
     */
    protected function cleanAiOutput(string $text): string
    {
        $clean = trim($text);
        $clean = preg_replace('/^(?:Here(?:\s+is|\'s)?\s+(?:an?\s+)?(?:improved|strengthened|professional|suggested)?\s*[^:\n]+:\s*)/i', '', $clean);
        $clean = preg_replace('/^(?:This is how to improve[^:\n]+:\s*)/i', '', $clean);
        $clean = preg_replace('/^(?:Option\s+\d+:\s*)/i', '', $clean);
        return trim($clean, " \t\n\r\0\x0B\"'`");
    }

    /**
     * AJAX: Generate cover letter
     */
    public function generateCoverLetter()
    {
        $jobTitle = $this->request->getPost('job_title');
        $companyName = $this->request->getPost('company_name');
        $jobDescription = $this->request->getPost('job_description');

        if (empty($jobTitle)) {
            return $this->fail('Job title is required.');
        }

        $user = auth()->user();
        $candidateModel = model(\App\Models\JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        $params = [
            'job_title' => $jobTitle,
            'company_name' => $companyName ?? '',
            'job_description' => $jobDescription ?? '',
            'candidate_name' => $candidate?->full_name ?? '',
            'candidate_skills' => $candidate?->skills ?? '',
            'candidate_experience' => $candidate?->experience_years ?? '',
            'candidate_education' => $candidate?->education_level ?? '',
        ];

        $coverLetter = $this->aiService->generateCoverLetter($params);
        return $this->respond(['cover_letter' => $coverLetter]);
    }

    /**
     * AJAX: Save generated cover letter to JobSeeker profile
     */
    public function saveCoverLetter()
    {
        $user = auth()->user();
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }
        
        $text = $this->request->getPost('cover_letter');
        if (empty($text)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cover letter text is required']);
        }
        
        $candidateModel = new \App\Models\JobSeekerModel();
        $candidate = $candidateModel->where('user_id', $user->id)->first();
        
        if ($candidate) {
            $candidateModel->update($candidate->id, ['cover_letter' => $text]);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Cover letter saved to profile']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Profile not found']);
    }

    /**
     * AJAX: Parse uploaded CV file (.pdf, .docx, .doc, .txt) and extract structured data
     */
    public function parseCvFile()
    {
        $file = $this->request->getFile('cv_file');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please upload a valid CV file (.pdf, .docx, .doc, or .txt).'
            ]);
        }

        $ext = strtolower($file->getClientExtension());
        $text = '';
        if ($ext === 'txt') {
            $text = (string) file_get_contents($file->getTempName());
        } elseif ($ext === 'docx') {
            $zip = new \ZipArchive();
            if ($zip->open($file->getTempName()) === true) {
                if (($index = $zip->locateName('word/document.xml')) !== false) {
                    $xml = $zip->getFromIndex($index);
                    $text = strip_tags($xml);
                }
                $zip->close();
            }
        } elseif ($ext === 'pdf') {
            $content = (string) file_get_contents($file->getTempName());
            preg_match_all('/BT[\s\S]*?ET/m', $content, $matches);
            if (!empty($matches[0])) {
                $text = strip_tags(implode(' ', $matches[0]));
            }
            if (empty($text) || strlen($text) < 50) {
                $text = preg_replace('/[^\x20-\x7E\r\n\t]/', ' ', $content);
            }
        } else {
            $text = (string) file_get_contents($file->getTempName());
        }

        $prompt = "You are an expert resume parser. Extract structured information from the following CV text.\n"
            . "Return a valid JSON object ONLY with the exact keys:\n"
            . "{\n"
            . '  "full_name": "...",' . "\n"
            . '  "job_title": "...",' . "\n"
            . '  "email": "...",' . "\n"
            . '  "phone": "...",' . "\n"
            . '  "location": "...",' . "\n"
            . '  "linkedin": "...",' . "\n"
            . '  "summary": "...",' . "\n"
            . '  "skills": ["Skill 1", "Skill 2"],' . "\n"
            . '  "experiences": [{"position": "...", "company": "...", "start_date": "YYYY-MM-DD", "end_date": "YYYY-MM-DD", "is_current": false, "description": "..."}],' . "\n"
            . '  "education": [{"school": "...", "degree": "...", "field": "...", "year": "YYYY"}],' . "\n"
            . '  "certifications": "...",' . "\n"
            . '  "languages": "...",' . "\n"
            . '  "portfolio": "..."' . "\n"
            . "}\n"
            . "Keep the summary under 60 words. For missing fields, output empty string (or empty array for arrays).\n\n"
            . "CV TEXT:\n" . substr($text, 0, 15000);

        try {
            $aiRaw = $this->aiService->generate($prompt);
            if (preg_match('/\{[\s\S]*\}/', $aiRaw, $matches)) {
                $parsed = json_decode($matches[0], true);
                if (is_array($parsed)) {
                    return $this->response->setJSON([
                        'success'       => true,
                        'data'          => $parsed,
                        'original_text' => substr($text, 0, 8000), // for the #orig-txt textarea
                        'raw_text'      => $text
                    ]);
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'CV parsing error: ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Could not automatically extract all CV fields. Please verify or fill in remaining sections.'
        ]);
    }

    /**
     * AJAX: Autosave resume draft
     */
    public function autosave()
    {
        $user = auth()->user();
        $resumeId = $this->request->getPost('id') ?: null;
        // Accept structured JSON snapshot if provided, otherwise fallback to legacy payload
        $snapshot = $this->request->getPost('snapshot');
        $payload = $snapshot ?: ($this->request->getPost('payload') ?: null);
        $metadata = $this->request->getPost('metadata') ? json_encode($this->request->getPost('metadata')) : null;

        if (empty($payload)) {
            return $this->fail('Payload is required for autosave.');
        }

        $data = [
            'resume_id' => $resumeId,
            'user_id' => $user->id,
            'payload' => $payload,
            'metadata' => $metadata,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insertId = $this->autosaveModel->insert($data);

        // Keep only last 20 autosaves per resume/user
        if ($resumeId) {
            $rows = $this->autosaveModel->where('resume_id', $resumeId)->where('user_id', $user->id)->orderBy('created_at', 'DESC')->findAll(50);
            if (count($rows) > 20) {
                $toDelete = array_slice($rows, 20);
                foreach ($toDelete as $r) { $this->autosaveModel->delete($r->id); }
            }
        }

        return $this->respondCreated(['autosave_id' => $insertId, 'timestamp' => date('c')]);
    }

    public function listAutosaves($resumeId)
    {
        $user = auth()->user();
        $rows = $this->autosaveModel->where('user_id', $user->id)->where('resume_id', $resumeId)->orderBy('created_at', 'DESC')->findAll(20);
        $out = [];
        foreach ($rows as $r) {
            $parsed = json_decode($r->payload, true);
            $previewSummary = '';
            $exps = [];
            if (is_array($parsed)) {
                $previewSummary = isset($parsed['summary']) ? mb_substr($parsed['summary'], 0, 200) : '';
                if (!empty($parsed['experiences']) && is_array($parsed['experiences'])) {
                    foreach ($parsed['experiences'] as $e) {
                        $exps[] = [
                            'position' => $e['position'] ?? '',
                            'company' => $e['company'] ?? ''
                        ];
                    }
                }
            }

            $out[] = [
                'id' => $r->id,
                'created_at' => $r->created_at,
                'payload' => $r->payload,
                'preview' => [
                    'summary' => $previewSummary,
                    'experiences' => $exps
                ]
            ];
        }

        return $this->respond(['autosaves' => $out]);
    }

    public function restoreAutosave($resumeId)
    {
        $user = auth()->user();
        $autosaveId = $this->request->getPost('autosave_id');
        if (empty($autosaveId)) return $this->fail('autosave_id is required', 400);
        $row = $this->autosaveModel->where('id', $autosaveId)->where('user_id', $user->id)->first();
        if (!$row) return $this->failNotFound('Autosave not found');

        // Decode payload JSON to structured object for client convenience
        $decoded = json_decode($row->payload, true) ?: null;
        return $this->respond(['payload' => $decoded, 'metadata' => $row->metadata, 'created_at' => $row->created_at]);
    }

    /**
     * AJAX: Chat with ResumeAI Coach
     */
    public function chat()
    {
        $message = $this->request->getPost('message');
        $historyJson = $this->request->getPost('history') ?? '[]';
        $history = json_decode($historyJson, true) ?? [];

        if (empty($message)) {
            return $this->fail('Message cannot be empty.');
        }

        $user = auth()->user();
        $candidateModel = model(\App\Models\JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        // Build a detailed coaching context based on active profile
        $context = "You are ResumeAI, an expert career advisor, recruiter, and professional resume consultant guiding candidate '{$candidateName}'. ";
        if ($targetRole) {
            $context .= "Target role: {$targetRole}. ";
        }
        if ($skills) {
            $context .= "Skills: {$skills}. ";
        }
        if ($experienceYears) {
            $context .= "Experience level: {$experienceYears} years. ";
        }
        $context .= "CRITICAL ADVISORY PROTOCOL: You must act as a trusted career coach and advisor. NEVER automatically modify the document or assume the candidate wants changes applied without review. ";
        $context .= "When you identify an issue or suggest a rewrite: ";
        $context .= "(1) Explain the issue (why the existing wording or structure is weak/problematic), ";
        $context .= "(2) Make a concrete recommendation based on executive resume & STAR standards, ";
        $context .= "(3) Show the proposed improvement clearly (in a clean blockquote or bullet), and ";
        $context .= "(4) Prompt the candidate to review and decide whether to apply it using the 'Apply Suggestion' button. ";
        $context .= "Keep your tone encouraging, executive, and results-oriented.";

        $reply = $this->aiService->getChatResponse($message, $history, $context);

        return $this->respond([
            'reply' => $reply
        ]);
    }

    /**
     * Save resume data
     */
    public function save()
    {
        $user = auth()->user();
        $title = $this->request->getPost('title');
        if (empty(trim($title ?? ''))) {
            $title = 'My Professional Resume';
        }

        $id = $this->request->getPost('id') ?: null;
        $metaData = [];
        if ($id) {
            $existing = $this->resumeModel->where('user_id', $user->id)->find($id);
            if ($existing && !empty($existing->ai_optimization_meta)) {
                $metaData = json_decode($existing->ai_optimization_meta, true) ?: [];
            }
        }
        $metaData['linkedin'] = $this->request->getPost('linkedin') ?? '';
        $metaData['certs'] = $this->request->getPost('certs') ?? '';
        $metaData['languages'] = $this->request->getPost('languages') ?? '';

        $resumeData = [
            'user_id' => $user->id,
            'title' => $title,
            'summary' => $this->request->getPost('summary'),
            'template_id' => $this->request->getPost('template_id') ?? 'classic',
            'ai_optimization_meta' => json_encode($metaData)
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        if ($id) {
            $existing = $this->resumeModel->where('user_id', $user->id)->find($id);
            if (!$existing) {
                $db->transRollback();
                return $this->fail('Resume not found', 404);
            }
            $this->resumeModel->update($id, $resumeData);
            $resumeId = $id;
        } else {
            $resumeId = $this->resumeModel->insert($resumeData);
        }

        if (!$resumeId) {
            $db->transRollback();
            return $this->fail('Failed to save resume metadata', 500);
        }

        // The builder's header fields (name/phone/location) aren't resume-specific columns —
        // they mirror the candidate's profile, which is what PDF/DOCX export actually reads.
        // Keep them in sync so edits made here aren't silently discarded.
        $profileUpdate = array_filter([
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'phone'     => trim((string) $this->request->getPost('phone')),
            'location'  => trim((string) $this->request->getPost('location')),
        ], static fn($v) => $v !== '');
        if (!empty($profileUpdate)) {
            model(\App\Models\JobSeekerModel::class)->where('user_id', $user->id)->set($profileUpdate)->update();
        }

        // Handle Experiences
        $this->experienceModel->where('resume_id', $resumeId)->delete();
        $expCompanies = $this->request->getPost('exp_company') ?? [];
        $expPositions = $this->request->getPost('exp_position') ?? [];
        $expDescriptions = $this->request->getPost('exp_description') ?? [];
        $expStartDates = $this->request->getPost('exp_start_date') ?? [];
        $expEndDates = $this->request->getPost('exp_end_date') ?? [];
        $expCurrent = $this->request->getPost('exp_current') ?? [];

        foreach ($expCompanies as $index => $company) {
            if (empty($company)) continue;
            $this->experienceModel->insert([
                'resume_id' => $resumeId,
                'company' => $company,
                'position' => $expPositions[$index] ?? '',
                'description' => $expDescriptions[$index] ?? '',
                'start_date' => !empty($expStartDates[$index]) ? $expStartDates[$index] : date('Y-m-d'),
                'end_date' => !empty($expEndDates[$index]) ? $expEndDates[$index] : null,
                'is_current' => in_array($index, $expCurrent) ? 1 : 0,
            ]);
        }

        // Handle Education (Correct mapping to allowed database fields)
        $this->educationModel->where('resume_id', $resumeId)->delete();
        $eduSchools = $this->request->getPost('edu_school') ?? [];
        $eduDegrees = $this->request->getPost('edu_degree') ?? [];
        $eduFields = $this->request->getPost('edu_field') ?? [];
        $eduYears = $this->request->getPost('edu_year') ?? [];

        foreach ($eduSchools as $index => $school) {
            if (empty($school)) continue;
            $this->educationModel->insert([
                'resume_id' => $resumeId,
                'institution' => $school,
                'degree' => $eduDegrees[$index] ?? '',
                'field_of_study' => $eduFields[$index] ?? '',
                'graduation_date' => !empty($eduYears[$index]) ? $eduYears[$index] . '-01-01' : null,
            ]);
        }

        // Handle Skills (comma separated)
        $this->skillModel->where('resume_id', $resumeId)->delete();
        $skillsText = $this->request->getPost('skills');
        if (!empty($skillsText)) {
            $skillsArray = explode(',', $skillsText);
            foreach ($skillsArray as $skill) {
                $skill = trim($skill);
                if (empty($skill)) continue;
                $this->skillModel->insert([
                    'resume_id' => $resumeId,
                    'skill_name' => $skill,
                    'proficiency_level' => 'intermediate'
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->fail('Failed to save resume. Database transaction error.', 500);
        }

        return $this->respondCreated(['id' => $resumeId, 'message' => 'Resume saved successfully']);
    }

    /**
     * Download Resume as PDF
     */
    public function download($id)
    {
        $user = auth()->user();
        $resume = $this->resumeModel->where('user_id', $user->id)->find($id);

        if (!$resume) {
            return redirect()->to('candidate/resumes')->with('error', 'Resume not found');
        }

        // Fetch candidate/jobseeker profile details to inject correct contact information
        $candidateModel = model(\App\Models\JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        // Inject profile details dynamically so the templates can render real user info
        $resume->full_name = $candidate?->full_name ?? $user->username ?? 'CANDIDATE NAME';
        $resume->email = $user->email ?? '';
        $resume->phone = $candidate?->phone ?? '';
        $resume->location = $candidate?->location ?? '';

        // Inject metadata onto the resume entity
        $linkedin = '';
        $certs = '';
        $languages = '';
        if (!empty($resume->ai_optimization_meta)) {
            $meta = json_decode($resume->ai_optimization_meta, true);
            if (is_array($meta)) {
                $linkedin = $meta['linkedin'] ?? '';
                $certs = $meta['certs'] ?? '';
                $languages = $meta['languages'] ?? '';
            }
        }
        $resume->linkedin = $linkedin;
        $resume->certs = $certs;
        $resume->languages = $languages;

        $experiences = $this->experienceModel->where('resume_id', $id)->findAll();
        $education = $this->educationModel->where('resume_id', $id)->findAll();
        $skills = $this->skillModel->where('resume_id', $id)->findAll();

        $html = view('candidate/resume/templates/' . ($resume->template_id ?? 'classic'), [
            'resume' => $resume,
            'experiences' => $experiences,
            'education' => $education,
            'skills' => $skills
        ]);

        $tempPath = WRITEPATH . 'temp/';
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }
        $pdfPath = $tempPath . 'resume-' . $id . '-' . time() . '.pdf';

        try {
            // Use Enterprise PDF generator (Gotenberg) via PdfService
            \App\Services\PdfService::generateFromHtml($html, $pdfPath, 'portrait');
        } catch (\Throwable $e) {
            log_message('error', 'Enterprise PDF generation failed: ' . $e->getMessage());
            throw new \Exception('Enterprise PDF failed: ' . $e->getMessage());
        }

        $cleanTitle = url_title($resume->title ?: 'Resume') ?: 'Resume';
        return $this->response->download($pdfPath, null)
            ->setFileName($cleanTitle . ".pdf");
    }

    /**
     * Download Resume as Word Document (.doc / .docx)
     */
    public function downloadDocx($id)
    {
        $user = auth()->user();
        $resume = $this->resumeModel->where('user_id', $user->id)->find($id);

        if (!$resume) {
            return redirect()->to('candidate/resumes')->with('error', 'Resume not found');
        }

        // Fetch candidate/jobseeker profile details to inject correct contact information
        $candidateModel = model(\App\Models\JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();

        // Inject profile details dynamically so the templates can render real user info
        $resume->full_name = $candidate?->full_name ?? $user->username ?? 'CANDIDATE NAME';
        $resume->email = $user->email ?? '';
        $resume->phone = $candidate?->phone ?? '';
        $resume->location = $candidate?->location ?? '';

        // Inject metadata onto the resume entity
        $linkedin = '';
        $certs = '';
        $languages = '';
        if (!empty($resume->ai_optimization_meta)) {
            $meta = json_decode($resume->ai_optimization_meta, true);
            if (is_array($meta)) {
                $linkedin = $meta['linkedin'] ?? '';
                $certs = $meta['certs'] ?? '';
                $languages = $meta['languages'] ?? '';
            }
        }
        $resume->linkedin = $linkedin;
        $resume->certs = $certs;
        $resume->languages = $languages;

        $experiences = $this->experienceModel->where('resume_id', $id)->findAll();
        $education = $this->educationModel->where('resume_id', $id)->findAll();
        $skills = $this->skillModel->where('resume_id', $id)->findAll();

        $html = view('candidate/resume/templates/' . ($resume->template_id ?? 'classic'), [
            'resume' => $resume,
            'experiences' => $experiences,
            'education' => $education,
            'skills' => $skills
        ]);

        $filename = url_title($resume->title) . ".doc";
        
        header("Content-Type: application/vnd.ms-word; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        
        echo "
        <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
        <head>
            <title>" . esc($resume->title) . "</title>
            <style>
                body { font-family: 'Arial', sans-serif; line-height: 1.5; color: #333333; }
                h1, h2, h3, h4 { color: #1e3a8a; }
                table { width: 100%; border-collapse: collapse; }
                td { padding: 4px; }
            </style>
        </head>
        <body>
            {$html}
        </body>
        </html>";
        exit();
    }

    /**
     * Download Resume as Plain Text (.txt / ATS Friendly)
     */
    public function downloadTxt($id)
    {
        $user = auth()->user();
        $resume = $this->resumeModel->where('user_id', $user->id)->find($id);

        if (!$resume) {
            return redirect()->to('candidate/resumes')->with('error', 'Resume not found');
        }

        $candidate = model(\App\Models\JobSeekerModel::class)->where('user_id', $user->id)->first();
        $experiences = $this->experienceModel->where('resume_id', $id)->findAll();
        $education = $this->educationModel->where('resume_id', $id)->findAll();
        $skills = $this->skillModel->where('resume_id', $id)->findAll();

        $fullName = strtoupper($candidate?->full_name ?? $user->username ?? 'CANDIDATE NAME');
        $email = $user->email ?? '';
        $phone = $candidate?->phone ?? '';
        $location = $candidate?->location ?? '';

        $lines = [];
        $lines[] = $fullName;
        $contact = array_filter([$email, $phone, $location]);
        if (!empty($contact)) {
            $lines[] = implode(' | ', $contact);
        }
        $lines[] = str_repeat('=', 60);
        $lines[] = "";

        if (!empty($resume->summary)) {
            $lines[] = "PROFESSIONAL SUMMARY";
            $lines[] = str_repeat('-', 30);
            $lines[] = wordwrap(strip_tags($resume->summary), 75);
            $lines[] = "";
        }

        if (!empty($experiences)) {
            $lines[] = "WORK EXPERIENCE";
            $lines[] = str_repeat('-', 30);
            foreach ($experiences as $exp) {
                $pos = $exp->position ?? 'Role';
                $co  = $exp->company ?? '';
                $dates = ($exp->start_date ? date('M Y', strtotime($exp->start_date)) : '') . ' - ' . ($exp->is_current ? 'Present' : ($exp->end_date ? date('M Y', strtotime($exp->end_date)) : ''));
                $lines[] = "{$pos}" . ($co ? " | {$co}" : "") . ($dates ? " ({$dates})" : "");
                if (!empty($exp->description)) {
                    $descLines = explode("\n", strip_tags($exp->description));
                    foreach ($descLines as $dl) {
                        $dl = trim($dl);
                        if ($dl) {
                            $lines[] = "  • " . wordwrap($dl, 70, "\n    ");
                        }
                    }
                }
                $lines[] = "";
            }
        }

        if (!empty($education)) {
            $lines[] = "EDUCATION";
            $lines[] = str_repeat('-', 30);
            foreach ($education as $edu) {
                $deg = $edu->degree ?? '';
                $field = $edu->field_of_study ?? '';
                $inst = $edu->institution ?? '';
                $year = !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '';
                $degLine = $deg . ($field ? " in {$field}" : "");
                $lines[] = "{$degLine}" . ($inst ? " | {$inst}" : "") . ($year ? " ({$year})" : "");
            }
            $lines[] = "";
        }

        if (!empty($skills)) {
            $lines[] = "SKILLS & EXPERTISE";
            $lines[] = str_repeat('-', 30);
            $skillNames = array_map(fn($s) => $s->skill_name, $skills);
            $lines[] = implode(', ', $skillNames);
            $lines[] = "";
        }

        $lines[] = str_repeat('-', 60);
        $lines[] = "Crafted with JobberRecruit · " . base_url();

        $txtContent = implode("\r\n", $lines);
        $filename = url_title($resume->title) . ".txt";

        return $this->response
            ->setHeader('Content-Type', 'text/plain; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($txtContent);
    }

    /**
     * Download Resume as JSON (JSON Resume Standard)
     */
    public function downloadJson($id)
    {
        $user = auth()->user();
        $resume = $this->resumeModel->where('user_id', $user->id)->find($id);

        if (!$resume) {
            return redirect()->to('candidate/resumes')->with('error', 'Resume not found');
        }

        $candidate = model(\App\Models\JobSeekerModel::class)->where('user_id', $user->id)->first();
        $experiences = $this->experienceModel->where('resume_id', $id)->findAll();
        $education = $this->educationModel->where('resume_id', $id)->findAll();
        $skills = $this->skillModel->where('resume_id', $id)->findAll();

        $meta = !empty($resume->ai_optimization_meta) ? json_decode($resume->ai_optimization_meta, true) : [];

        $jsonResume = [
            '$schema' => 'https://raw.githubusercontent.com/jsonresume/resume-schema/v1.0.0/schema.json',
            'basics' => [
                'name'     => $candidate?->full_name ?? $user->username ?? 'Candidate',
                'label'    => $candidate?->job_title ?? $resume->title ?? '',
                'email'    => $user->email ?? '',
                'phone'    => $candidate?->phone ?? '',
                'summary'  => strip_tags($resume->summary ?? ''),
                'location' => [
                    'city'        => $candidate?->location ?? '',
                    'countryCode' => 'NG',
                ],
                'profiles' => !empty($meta['linkedin']) ? [
                    [
                        'network' => 'LinkedIn',
                        'url'     => $meta['linkedin'],
                    ]
                ] : [],
            ],
            'work' => array_map(function($e) {
                return [
                    'name'       => $e->company ?? '',
                    'position'   => $e->position ?? '',
                    'startDate'  => $e->start_date ?? '',
                    'endDate'    => $e->is_current ? '' : ($e->end_date ?? ''),
                    'summary'    => strip_tags($e->description ?? ''),
                    'highlights' => array_filter(array_map('trim', explode("\n", strip_tags($e->description ?? '')))),
                ];
            }, $experiences),
            'education' => array_map(function($ed) {
                return [
                    'institution' => $ed->institution ?? '',
                    'area'        => $ed->field_of_study ?? '',
                    'studyType'   => $ed->degree ?? '',
                    'endDate'     => $ed->graduation_date ?? '',
                ];
            }, $education),
            'skills' => [
                [
                    'name'     => 'Skills',
                    'keywords' => array_map(fn($s) => $s->skill_name, $skills),
                ]
            ],
            'meta' => [
                'canonical' => base_url('candidate/resumes/build/' . $id),
                'version'   => 'v1.0.0',
                'lastModified' => $resume->updated_at ?? date('c'),
            ]
        ];

        $filename = url_title($resume->title) . ".json";

        return $this->response
            ->setHeader('Content-Type', 'application/json; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(json_encode($jsonResume, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    public function delete($id)
    {
        $user = auth()->user();
        if (!$user) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
            }
            return redirect()->to('login');
        }

        $resume = $this->resumeModel->where('id', $id)->where('user_id', $user->id)->first();
        if (!$resume) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Resume not found or access denied'])->setStatusCode(404);
            }
            return redirect()->to('candidate/resumes')->with('error', 'Resume not found.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Clean up related sub-items before deleting parent
        $this->experienceModel->where('resume_id', $id)->delete();
        $this->educationModel->where('resume_id', $id)->delete();
        $this->skillModel->where('resume_id', $id)->delete();
        $this->resumeModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Could not delete resume. Please try again.'])->setStatusCode(500);
            }
            return redirect()->to('candidate/resumes')->with('error', 'Could not delete resume.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Resume deleted successfully']);
        }
        return redirect()->to('candidate/resumes')->with('success', 'Resume deleted successfully.');
    }

    /**
     * AJAX: Generate specific AI outputs with tailored alternatives (Headline, Pitch, About, Bio)
     * Strictly grounded in candidate's actual facts. Never fabricates information.
     */
    public function generateAiOutput()
    {
        $type = strtolower(trim($this->request->getPost('type') ?? ''));
        if (!in_array($type, ['headline', 'pitch', 'about', 'bio'])) {
            return $this->fail('Invalid output type requested. Please choose Headline, Pitch, About, or Bio.');
        }

        $user = auth()->user();
        $candidateModel = model(\App\Models\JobSeekerModel::class);
        $candidate = $user ? $candidateModel->where('user_id', $user->id)->first() : null;

        // Extract input fields with fallback to candidate profile
        $fullName = trim($this->request->getPost('full_name') ?? $this->request->getPost('name') ?? $candidate?->full_name ?? $user?->username ?? 'Candidate');
        $rawTitle = trim($this->request->getPost('title') ?? '');
        $jobTitle = preg_replace('/\s+Resume$/i', '', $rawTitle);
        if (empty($jobTitle)) {
            $jobTitle = $candidate?->job_title ?? '';
        }
        $summary = trim($this->request->getPost('summary') ?? $candidate?->bio ?? '');
        $location = trim($this->request->getPost('location') ?? $candidate?->location ?? '');
        $certs = trim($this->request->getPost('certs') ?? '');

        // Experiences
        $experiences = $this->request->getPost('experience') ?? [];
        if (empty($experiences) && $candidate) {
            $expModel = new \App\Models\JobSeekerExperienceModel();
            $dbExps = $expModel->forSeeker((int) $candidate->id);
            if (!empty($dbExps)) {
                foreach ($dbExps as $de) {
                    $experiences[] = [
                        'position'    => $de->job_title ?? '',
                        'company'     => $de->company ?? '',
                        'description' => $de->description ?? '',
                        'start_date'  => $de->start_date ?? '',
                        'end_date'    => $de->end_date ?? '',
                        'is_current'  => $de->is_current ?? 0,
                    ];
                }
            }
        }

        // Education
        $education = $this->request->getPost('education') ?? [];
        if (empty($education) && $candidate) {
            $eduModel = new \App\Models\JobSeekerEducationModel();
            $dbEdus = $eduModel->forSeeker((int) $candidate->id);
            if (!empty($dbEdus)) {
                foreach ($dbEdus as $de) {
                    $education[] = [
                        'school' => $de->school ?? '',
                        'degree' => $de->degree ?? '',
                        'field'  => $de->field_of_study ?? '',
                        'year'   => $de->end_year ?? '',
                    ];
                }
            }
        }

        // Skills
        $rawSkills = $this->request->getPost('skills') ?? [];
        $skills = [];
        if (is_array($rawSkills)) {
            $skills = array_filter(array_map('trim', $rawSkills));
        } elseif (is_string($rawSkills)) {
            $skills = array_filter(array_map('trim', explode(',', $rawSkills)));
        }
        if (empty($skills) && !empty($candidate?->skills)) {
            $skills = is_array($candidate->skills) ? $candidate->skills : array_filter(array_map('trim', explode(',', $candidate->skills)));
        }

        // If jobTitle is still empty, derive from most recent experience
        if (empty($jobTitle) && !empty($experiences)) {
            foreach ($experiences as $exp) {
                if (!empty($exp['position'])) {
                    $jobTitle = trim($exp['position']);
                    break;
                }
            }
        }

        if (empty($jobTitle)) {
            $jobTitle = 'Professional';
        }

        // Check if there is enough information to generate outputs
        if (empty($jobTitle) && empty($experiences) && empty($skills) && empty($summary)) {
            return $this->fail('Please provide a Job Title, Skills, or Work Experience to generate outputs.');
        }

        // Prepare human-readable context
        $expDescriptions = [];
        $companies = [];
        $positions = [];
        foreach ($experiences as $exp) {
            $pos = trim($exp['position'] ?? $exp['role'] ?? '');
            $comp = trim($exp['company'] ?? '');
            $desc = trim($exp['description'] ?? $exp['bullets'] ?? '');
            if ($pos) $positions[] = $pos;
            if ($comp) $companies[] = $comp;
            if ($pos || $comp || $desc) {
                $line = ($pos ? $pos : 'Role') . ($comp ? " at {$comp}" : '');
                if ($desc) {
                    $line .= ": " . mb_substr($desc, 0, 180);
                }
                $expDescriptions[] = $line;
            }
        }

        $eduDescriptions = [];
        foreach ($education as $edu) {
            $sch = trim($edu['school'] ?? $edu['institution'] ?? '');
            $deg = trim($edu['degree'] ?? '');
            $fld = trim($edu['field'] ?? $edu['field_of_study'] ?? '');
            if ($sch || $deg || $fld) {
                $eduDescriptions[] = trim(($deg ? "{$deg} " : "") . ($fld ? "in {$fld} " : "") . ($sch ? "from {$sch}" : ""));
            }
        }

        // Build facts payload
        $facts = [
            "Candidate Name: " . $fullName,
            "Target/Current Role: " . $jobTitle,
        ];
        if (!empty($skills)) {
            $facts[] = "Skills: " . implode(', ', array_slice($skills, 0, 15));
        }
        if (!empty($expDescriptions)) {
            $facts[] = "Work Experience:\n- " . implode("\n- ", array_slice($expDescriptions, 0, 5));
        }
        if (!empty($eduDescriptions)) {
            $facts[] = "Education: " . implode('; ', array_slice($eduDescriptions, 0, 3));
        }
        if (!empty($certs)) {
            $facts[] = "Certifications: " . $certs;
        }
        if (!empty($summary)) {
            $facts[] = "Existing Summary/Bio: " . mb_substr($summary, 0, 300);
        }
        if (!empty($location)) {
            $facts[] = "Location: " . $location;
        }

        $factsText = implode("\n", $facts);

        $typeMeta = [
            'headline' => [
                'title' => 'Professional Headline',
                'count' => 3,
                'instructions' => "Generate 3 distinct, high-impact professional headline options for resume headers or LinkedIn.\n" .
                                  "Option 1: Core Specialization (Role | Top Skills)\n" .
                                  "Option 2: Value Proposition (Results-Driven Role focused on Impact)\n" .
                                  "Option 3: Domain & Expertise Forward (Role | Key Methodologies/Tools)",
            ],
            'pitch' => [
                'title' => 'Elevator Pitch',
                'count' => 3,
                'instructions' => "Generate 3 distinct elevator pitch options (30-60 seconds spoken):\n" .
                                  "Option 1: Concise 30-Second Interview Introduction (Who I am, key experience, core strengths)\n" .
                                  "Option 2: Impact & Achievement-Focused Pitch (Emphasizing track record and problem-solving)\n" .
                                  "Option 3: Networking & Conversational Pitch (Engaging, forward-looking overview)",
            ],
            'about' => [
                'title' => 'LinkedIn About Summary',
                'count' => 2,
                'instructions' => "Generate 2 distinct LinkedIn About section options written in compelling 1st-person ('I'):\n" .
                                  "Option 1: Comprehensive Narrative (Story, background, core expertise, achievements, and call to connect)\n" .
                                  "Option 2: Executive Bulleted Summary (Direct, high-impact intro with bulleted key competencies and areas of expertise)",
            ],
            'bio' => [
                'title' => 'Executive Bio',
                'count' => 2,
                'instructions' => "Generate 2 distinct professional executive bio options written in formal 3rd-person ('{$fullName}'):\n" .
                                  "Option 1: Full Executive Bio (Comprehensive career trajectory, leadership, key achievements, and credentials)\n" .
                                  "Option 2: Crisp Speaker/Short Bio (1-2 punchy paragraphs for conference introductions or proposal bios)",
            ],
        ];

        $currentMeta = $typeMeta[$type];

        $prompt = "You are an elite career strategist. Based STRICTLY on the candidate's verified resume facts below, generate {$currentMeta['count']} useful, tailored alternatives for: {$currentMeta['title']}.\n\n";
        $prompt .= "STRICT GROUNDING RULES:\n";
        $prompt .= "1. ONLY use facts, skills, job titles, and experiences listed below. Do NOT invent new companies, statistics, certifications, degrees, or tools.\n";
        $prompt .= "2. The outputs must remain 100% relevant and truthful to the candidate.\n";
        $prompt .= "3. Respond in strict JSON format matching this schema:\n";
        $prompt .= "{\n";
        $prompt .= '  "title": "' . $currentMeta['title'] . "\",\n";
        $prompt .= '  "alternatives": [' . "\n";
        $prompt .= '    { "label": "Option 1: ...", "text": "..." },' . "\n";
        $prompt .= '    { "label": "Option 2: ...", "text": "..." }' . "\n";
        $prompt .= "  ],\n";
        $prompt .= '  "primary": "..."' . "\n";
        $prompt .= "}\n\n";
        $prompt .= "SPECIFIC FORMATTING:\n" . $currentMeta['instructions'] . "\n\n";
        $prompt .= "CANDIDATE FACTS:\n" . $factsText;

        $alternatives = [];
        $primaryOutput = '';

        try {
            $aiRaw = $this->aiService->generate($prompt, 'fast');
            if (!empty($aiRaw)) {
                $cleanJson = trim($aiRaw);
                if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $cleanJson, $m)) {
                    $cleanJson = trim($m[1]);
                }
                $decoded = json_decode($cleanJson, true);
                if (is_array($decoded) && !empty($decoded['alternatives'])) {
                    foreach ($decoded['alternatives'] as $alt) {
                        if (!empty($alt['text'])) {
                            $alternatives[] = [
                                'label' => trim($alt['label'] ?? 'Alternative'),
                                'text'  => trim($alt['text']),
                            ];
                        }
                    }
                    $primaryOutput = trim($decoded['primary'] ?? ($alternatives[0]['text'] ?? ''));
                } elseif (is_string($aiRaw) && strlen(trim($aiRaw)) > 10) {
                    if (stripos($aiRaw, 'Personalized Career Growth') === false && stripos($aiRaw, 'Step 1: Skill Refinement') === false) {
                        $primaryOutput = trim($aiRaw);
                        $alternatives[] = [
                            'label' => 'AI Generated Option',
                            'text'  => $primaryOutput,
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'generateAiOutput AI call error: ' . $e->getMessage());
        }

        // Deterministic Fallback Generator if AI failed or returned invalid response
        if (empty($alternatives)) {
            $fallback = $this->buildFallbackAiOutputs($type, [
                'full_name'   => $fullName,
                'title'       => $jobTitle,
                'skills'      => $skills,
                'experiences' => $experiences,
                'education'   => $education,
                'certs'       => $certs,
                'summary'     => $summary,
                'location'    => $location,
            ]);
            $alternatives = $fallback['alternatives'];
            $primaryOutput = $fallback['primary'];
        }

        return $this->respond([
            'status'       => 'success',
            'type'         => $type,
            'title'        => $currentMeta['title'],
            'alternatives' => $alternatives,
            'output'       => $primaryOutput,
        ]);
    }

    /**
     * Deterministic, 100% truthful fallback generator for More AI Outputs
     * Strictly uses only facts provided by the candidate. No hallucinated data.
     */
    protected function buildFallbackAiOutputs(string $type, array $facts): array
    {
        $name = !empty($facts['full_name']) ? $facts['full_name'] : 'Candidate';
        $title = !empty($facts['title']) ? $facts['title'] : 'Professional';
        $skills = !empty($facts['skills']) && is_array($facts['skills']) ? array_values($facts['skills']) : [];
        $experiences = !empty($facts['experiences']) && is_array($facts['experiences']) ? $facts['experiences'] : [];
        $education = !empty($facts['education']) && is_array($facts['education']) ? $facts['education'] : [];
        $certs = !empty($facts['certs']) ? $facts['certs'] : '';
        $summary = !empty($facts['summary']) ? $facts['summary'] : '';

        // Extract most recent company & position
        $latestExp = !empty($experiences[0]) ? $experiences[0] : [];
        $latestCompany = trim($latestExp['company'] ?? '');
        $latestPosition = trim($latestExp['position'] ?? $latestExp['role'] ?? '');
        $primaryTitle = !empty($title) && $title !== 'Professional' ? $title : (!empty($latestPosition) ? $latestPosition : 'Professional');

        // Top skills string
        $topSkills = array_slice($skills, 0, 4);
        $skillsListStr = !empty($topSkills) ? implode(', ', $topSkills) : '';
        $skillsPiped = !empty($topSkills) ? implode(' | ', $topSkills) : '';

        $alternatives = [];

        switch ($type) {
            case 'headline':
                // Option 1: Core Specialization
                $alt1 = $primaryTitle . (!empty($skillsPiped) ? " | {$skillsPiped}" : "");
                // Option 2: Value Proposition
                $alt2 = "Results-Driven {$primaryTitle}" . (!empty($skillsListStr) ? " with demonstrated expertise in {$skillsListStr}" : " focused on operational excellence and measurable impact");
                // Option 3: Experience Forward
                $alt3 = $primaryTitle . (!empty($latestCompany) ? " at {$latestCompany}" : "") . (!empty($topSkills) ? " • " . implode(' • ', array_slice($topSkills, 0, 3)) : "");

                $alternatives = [
                    ['label' => 'Option 1: Core Specialization', 'text' => $alt1],
                    ['label' => 'Option 2: Value Proposition', 'text' => $alt2],
                    ['label' => 'Option 3: Experience Forward', 'text' => $alt3],
                ];
                break;

            case 'pitch':
                // Option 1: 30-Second Interview Intro
                $p1 = "Hello, I'm {$name}, a {$primaryTitle}" . (!empty($latestCompany) ? " with experience at {$latestCompany}" : "") . ". ";
                if (!empty($skillsListStr)) {
                    $p1 .= "My core competencies include {$skillsListStr}. ";
                }
                $p1 .= "Throughout my career, I've prioritized delivering high-quality work, solving operational bottlenecks, and collaborating across teams to achieve key milestones. I'm excited about the opportunity to bring these strengths to high-impact initiatives.";

                // Option 2: Impact & Achievement Focused
                $p2 = "As a {$primaryTitle}, my track record centers on turning goals into measurable results. ";
                if (!empty($latestCompany)) {
                    $p2 .= "In my work at {$latestCompany}, ";
                }
                if (!empty($skillsListStr)) {
                    $p2 .= "I leverage strengths in {$skillsListStr} to drive continuous efficiency. ";
                } else {
                    $p2 .= "I apply structured problem solving and technical excellence to every project. ";
                }
                $p2 .= "I look forward to contributing this proven dedication to dynamic teams.";

                // Option 3: Networking & Conversational
                $p3 = "I'm a {$primaryTitle} passionate about " . (!empty($topSkills[0]) ? $topSkills[0] : "building innovative solutions") . (!empty($topSkills[1]) ? " and {$topSkills[1]}" : "") . ". ";
                $p3 .= "Whether streamlining existing processes or tackling ambitious project goals, I focus on creating sustainable value and staying ahead of industry best practices.";

                $alternatives = [
                    ['label' => 'Option 1: 30-Second Interview Intro', 'text' => $p1],
                    ['label' => 'Option 2: Impact & Achievement Focused', 'text' => $p2],
                    ['label' => 'Option 3: Networking & Conversational', 'text' => $p3],
                ];
                break;

            case 'about':
                // Option 1: Comprehensive First-Person Narrative
                $a1 = "I am a results-oriented {$primaryTitle}" . (!empty($latestCompany) ? " with hands-on experience at {$latestCompany}" : "") . ".\n\n";
                if (!empty($summary)) {
                    $a1 .= $summary . "\n\n";
                }
                if (!empty($skills)) {
                    $a1 .= "Key Areas of Expertise:\n";
                    foreach (array_slice($skills, 0, 6) as $sk) {
                        $a1 .= "• " . $sk . "\n";
                    }
                    $a1 .= "\n";
                }
                $a1 .= "I am constantly seeking opportunities to drive efficiency, mentor colleagues, and deliver excellence. Let's connect!";

                // Option 2: Executive Bulleted Summary
                $a2 = "Experienced {$primaryTitle} committed to continuous innovation and operational success.\n\n";
                $a2 .= "Core Strengths:\n";
                if (!empty($topSkills)) {
                    $a2 .= "• Technical Proficiencies: " . implode(', ', $topSkills) . "\n";
                }
                if (!empty($latestCompany)) {
                    $a2 .= "• Professional Track Record: Proven contributions at {$latestCompany}\n";
                }
                $a2 .= "• Cross-Functional Collaboration & Strategic Execution\n";
                $a2 .= "• Quality Assurance & Problem Resolution\n\n";
                $a2 .= "Open to discussing leadership, technical opportunities, and strategic collaborations.";

                $alternatives = [
                    ['label' => 'Option 1: First-Person Story', 'text' => trim($a1)],
                    ['label' => 'Option 2: Executive Bulleted Summary', 'text' => trim($a2)],
                ];
                break;

            case 'bio':
                // Option 1: Full Executive Bio (3rd Person)
                $b1 = "{$name} is a dedicated {$primaryTitle} with a distinguished background in the industry. ";
                if (!empty($latestCompany)) {
                    $b1 .= "Having served as {$primaryTitle} at {$latestCompany}, {$name} has demonstrated consistent capability in driving operational progress and technical excellence. ";
                }
                if (!empty($skillsListStr)) {
                    $b1 .= "Areas of core proficiency include {$skillsListStr}. ";
                }
                if (!empty($education[0])) {
                    $deg = trim($education[0]['degree'] ?? '');
                    $sch = trim($education[0]['school'] ?? $education[0]['institution'] ?? '');
                    if ($deg || $sch) {
                        $b1 .= "{$name} holds " . ($deg ? "a {$deg}" : "credentials") . ($sch ? " from {$sch}" : "") . ". ";
                    }
                }
                $b1 .= "Recognized for collaborative leadership and systematic problem solving, {$name} continues to deliver measurable impact across key business objectives.";

                // Option 2: Short / Speaker Bio
                $b2 = "{$name} is an experienced {$primaryTitle}" . (!empty($latestCompany) ? " (formerly at {$latestCompany})" : "") . " specializing in " . (!empty($skillsListStr) ? $skillsListStr : "strategic delivery and professional excellence") . ". ";
                $b2 .= "Known for a practical, results-first approach, {$name} combines deep technical domain expertise with effective team communication.";

                $alternatives = [
                    ['label' => 'Option 1: Full Executive Bio (3rd Person)', 'text' => $b1],
                    ['label' => 'Option 2: Short / Speaker Bio', 'text' => $b2],
                ];
                break;
        }

        return [
            'alternatives' => $alternatives,
            'primary'      => $alternatives[0]['text'] ?? '',
        ];
    }

    /**
     * AJAX: Generate a writing review
     */
    public function generateWritingReview()
    {
        $experiences = $this->request->getPost('experience') ?? [];
        $summary = $this->request->getPost('summary') ?? '';

        $context = "";
        if (!empty($summary)) $context .= "Professional Summary:\n" . $summary . "\n\n";
        if (!empty($experiences) && is_array($experiences)) {
            foreach ($experiences as $idx => $e) {
                if (!empty($e['description'])) {
                    $pos = $e['position'] ?? ('Experience #' . ($idx + 1));
                    $context .= "Role ({$pos}):\n" . $e['description'] . "\n\n";
                }
            }
        }

        if (empty(trim($context))) {
            return $this->fail('Please provide a summary or experience to review.');
        }

        $prompt = "You are ResumeAI, an expert career advisor and executive resume reviewer. Review the following text for writing style, clichés, passive voice, weak opening verbs, and missing quantifiable metrics.\n\n";
        $prompt .= "CRITICAL ADVISORY RULE: For every identified issue, you MUST follow this strict 4-step coaching schema:\n";
        $prompt .= "1. Explain the Issue: Specify the exact phrase/sentence and explain why it weakens the candidate's CV.\n";
        $prompt .= "2. Make a Recommendation: Explain the executive writing principle (STAR method, active voice, quantifiable impact) to solve it.\n";
        $prompt .= "3. Show Proposed Improvement: Provide the exact, polished rewritten sentence or bullet.\n";
        $prompt .= "4. Decision: State that the candidate can review and apply the improvement.\n\n";
        $prompt .= "Format your response as clean HTML using `<div class=\"coach-review-card mb-3 p-3 border rounded bg-white shadow-sm\">` containing:\n";
        $prompt .= "- `<div class=\"issue-badge text-danger fw-bold fs-13 mb-1\"><i class=\"ti ti-alert-triangle me-1\"></i> <strong>Issue:</strong> [Explanation]</div>`\n";
        $prompt .= "- `<div class=\"rec-text text-muted fs-13 mb-2\"><i class=\"ti ti-bulb text-warning me-1\"></i> <strong>Coach Recommendation:</strong> [Recommendation]</div>`\n";
        $prompt .= "- `<div class=\"proposed-box p-2 bg-light border rounded fs-13 text-dark mb-2\"><strong>Proposed Improvement:</strong><br><span class=\"proposed-text\">[Polished Text]</span></div>`\n";
        $prompt .= "- `<button type=\"button\" class=\"btn btn-xs btn-primary apply-coach-suggestion\" data-suggestion=\"[Escaped Polished Text]\"><i class=\"ti ti-check me-1\"></i> Apply Suggestion</button>`\n";
        $prompt .= "</div>\n";
        $prompt .= "Do not include markdown fences or scripts. Return ONLY the HTML cards.\n\nText to analyze:\n" . $context;

        try {
            $output = $this->aiService->generate($prompt);
            $cleanOutput = is_string($output) ? $output : ($output['content'] ?? $output['text'] ?? '');
            if (empty(trim($cleanOutput))) {
                $cleanOutput = "<div class='alert alert-info'><i class='ti ti-circle-check me-1'></i> Your writing is clean! No major clichés or passive phrasing detected.</div>";
            }
            return $this->respond(['review' => $cleanOutput]);
        } catch (\Throwable $e) {
            return $this->respond(['review' => "<div class='alert alert-warning'>Writing analysis temporarily unavailable. Please try again.</div>"]);
        }
    }

    /**
     * AJAX: Generate Recruiter View (Matching reference HTML: 6-second scan, verdict, readiness, 4 framed observations)
     */
    public function generateRecruiterView()
    {
        $this->response = $this->response ?? service('response');
        $request = $this->request ?? service('request');
        $name = trim($request->getPost('name') ?? $request->getPost('full_name') ?? '');
        if (empty($name) && function_exists('auth') && auth()->user()) {
            $name = auth()->user()->username ?? 'Candidate';
        }
        if (empty($name)) {
            $name = 'Candidate';
        }

        $title = trim($request->getPost('title') ?? 'Professional');
        $summary = trim($request->getPost('summary') ?? '');
        $rawSkills = $request->getPost('skills') ?? [];
        if (is_string($rawSkills)) {
            $skills = array_filter(array_map('trim', explode(',', $rawSkills)));
        } elseif (is_array($rawSkills)) {
            $skills = array_values(array_filter(array_map('trim', $rawSkills)));
        } else {
            $skills = [];
        }

        $certs = trim($request->getPost('certs') ?? '');
        $location = trim($request->getPost('location') ?? '');
        $linkedin = trim($request->getPost('linkedin') ?? '');
        $education = $request->getPost('education') ?? [];
        $experiences = $request->getPost('experience') ?? [];
        $jobDescription = trim($request->getPost('job_description') ?? '');

        // Extract bullets and clean experiences
        $allBullets = [];
        $firstExp = !empty($experiences[0]) && is_array($experiences[0]) ? $experiences[0] : null;
        if (!empty($experiences) && is_array($experiences)) {
            foreach ($experiences as $exp) {
                if (!is_array($exp)) continue;
                $rawDesc = $exp['bullets'] ?? $exp['description'] ?? '';
                if (!empty($rawDesc)) {
                    $lines = preg_split('/\r\n|\r|\n/', $rawDesc);
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (!empty($line)) {
                            $allBullets[] = $line;
                        }
                    }
                }
            }
        }

        $fullText = strtolower($summary . ' ' . implode(' ', $allBullets));

        // 1. Human writing / Clichés analysis
        $cliches = [
            "results-driven", "results driven", "highly motivated", "dynamic professional",
            "proven track record", "passionate professional", "detail-oriented", "detail oriented",
            "team player", "self-starter", "self starter", "go-getter", "go getter",
            "hardworking individual", "think outside the box", "synergy"
        ];
        $clicheHits = 0;
        foreach ($cliches as $c) {
            if (strpos($fullText, $c) !== false) {
                $clicheHits++;
            }
        }

        $leads = [];
        $repCount = 0;
        foreach ($allBullets as $b) {
            $words = preg_split('/\s+/', trim($b));
            if (!empty($words[0])) {
                $v = strtolower($words[0]);
                $leads[$v] = ($leads[$v] ?? 0) + 1;
            }
        }
        foreach ($leads as $v => $cnt) {
            if ($cnt >= 3) {
                $repCount++;
            }
        }
        $humanScore = max(0, 100 - ($clicheHits * 18) - ($repCount * 12));

        // 2. Action verbs metric
        $strongVerbs = [
            "led", "built", "cut", "grew", "launched", "delivered", "reduced", "improved",
            "designed", "owned", "negotiated", "recovered", "streamlined", "automated",
            "prepared", "produced", "rebuilt", "cleared", "processed", "reconciled",
            "managed", "implemented", "run", "ran", "handle", "handled", "maintain",
            "maintained", "supported", "created", "trained"
        ];
        $strongVerbHits = 0;
        foreach ($allBullets as $b) {
            $words = preg_split('/\s+/', trim($b));
            if (!empty($words[0]) && in_array(strtolower($words[0]), $strongVerbs)) {
                $strongVerbHits++;
            }
        }
        $totalBullets = count($allBullets);
        $verbsScore = $totalBullets > 0 ? (int)round(($strongVerbHits / $totalBullets) * 100) : 40;

        // 3. Impact metric (numbers or impact words)
        $impactWords = ["cut", "reduced", "grew", "saved", "improved", "increased", "delivered", "cleared", "recovered", "shortened", "eliminated", "doubled"];
        $impactHits = 0;
        foreach ($allBullets as $b) {
            $low = strtolower($b);
            $hasNumber = preg_match('/\d/', $b);
            $hasImpactWord = false;
            foreach ($impactWords as $w) {
                if (strpos($low, $w) !== false) {
                    $hasImpactWord = true;
                    break;
                }
            }
            if ($hasNumber || $hasImpactWord) {
                $impactHits++;
            }
        }
        $impactScore = $totalBullets > 0 ? (int)round(($impactHits / $totalBullets) * 100) : 35;

        // 4. Readability: bullets within 4–24 words
        $readHits = 0;
        foreach ($allBullets as $b) {
            $wc = count(preg_split('/\s+/', trim($b)));
            if ($wc >= 4 && $wc <= 24) {
                $readHits++;
            }
        }
        $readScore = $totalBullets > 0 ? (int)round(($readHits / $totalBullets) * 100) : 50;

        // 5. Keyword Coverage vs JD
        $coverScore = null;
        if (strlen($jobDescription) >= 50) {
            $jdWords = preg_split('/[^a-zA-Z0-9]+/', strtolower($jobDescription));
            $stopwords = ['with','this','that','from','have','more','will','your','about','their','what','some','when','make','than','also','been','then','into','work','years','must','role','team','well','such','take','help'];
            $kws = [];
            foreach ($jdWords as $w) {
                if (strlen($w) >= 4 && !in_array($w, $stopwords) && !is_numeric($w)) {
                    $kws[$w] = true;
                }
            }
            $kwList = array_keys($kws);
            if (!empty($kwList)) {
                $kwHits = 0;
                foreach ($kwList as $kw) {
                    if (strpos($fullText, $kw) !== false) {
                        $kwHits++;
                    }
                }
                $coverScore = (int)round(($kwHits / count($kwList)) * 100);
            }
        }

        // Composite recruiter score
        $parts = [$humanScore, $verbsScore, $impactScore, $readScore];
        if ($coverScore !== null) {
            $parts[] = $coverScore;
        }
        $recruiterScore = (int)round(array_sum($parts) / count($parts));

        // Readiness Band
        if ($recruiterScore >= 75) {
            $band = ['strong', 'Strong — likely to earn a full read'];
        } elseif ($recruiterScore >= 55) {
            $band = ['moderate', 'Moderate — fixable gaps below'];
        } else {
            $band = ['weak', 'Needs work before sending'];
        }

        // Natural Language Recruiter Verdict
        $firstRole = '';
        if (!empty($firstExp)) {
            $firstRole = !empty($firstExp['role']) ? $firstExp['role'] : (!empty($firstExp['position']) ? $firstExp['position'] : '');
        }
        if (empty($firstRole)) {
            $firstRole = $title;
        }
        $topSkill = !empty($skills[0]) ? $skills[0] : '';
        $field = $title;

        $verdictParts = [];
        if (!empty($topSkill)) {
            $verdictParts[] = "Your " . strtolower($field) . " experience comes through clearly, and " . strtolower($topSkill) . " is a genuine strength on the page.";
        } else {
            $verdictParts[] = "Your " . strtolower($field) . " experience comes through clearly on the page.";
        }

        if ($impactScore < 55) {
            $verdictParts[] = "That said, your bullets describe responsibilities more than outcomes — I can see what you were tasked with, but not the results you produced.";
        } else {
            $verdictParts[] = "Your bullets lean on real outcomes rather than just duties, which is exactly what I look for.";
        }

        // Specific cert found
        $profBodies = ['ICAN', 'ACCA', 'CIPM', 'COREN', 'NIM', 'PMP', 'CFA'];
        $certFound = '';
        foreach ($profBodies as $pb) {
            if (stripos($certs, $pb) !== false) {
                $certFound = $pb;
                break;
            }
        }

        if ($coverScore !== null && $coverScore < 60) {
            $verdictParts[] = "Against the job you're targeting, you're also missing some of the language the role asks for — mirror more of its wording so search filters surface you.";
        }

        $asks = [];
        if ($impactScore < 55) {
            $asks[] = "quantify a few achievements";
        }
        if ($certFound) {
            $asks[] = "move your {$certFound} qualification higher on the page";
        }
        if (empty($linkedin)) {
            $asks[] = "add your LinkedIn";
        }

        if ($recruiterScore >= 70) {
            $verdictLine = "On balance I'd shortlist you for an interview";
        } elseif ($recruiterScore >= 55) {
            $verdictLine = "I'd likely shortlist you";
        } else {
            $verdictLine = "I'm not quite there yet";
        }

        if (!empty($asks)) {
            $askText = count($asks) === 1 ? $asks[0] : implode(', ', array_slice($asks, 0, -1)) . ' and ' . end($asks);
            $verdictParts[] = $verdictLine . ", but I'd recommend you " . $askText . " before you send it out.";
        } else {
            $verdictParts[] = $verdictLine . " — this reads as application-ready.";
        }

        $verdict = implode(' ', $verdictParts);

        // 4 framed recruiter observation groups
        $stands = [];
        if ($verbsScore >= 65) {
            $stands[] = ['text' => "Strong action verbs — it's clear what you actually did."];
        }
        if (!empty(trim($certs))) {
            $stands[] = ['text' => "Certifications with codes read as verifiable and credible."];
        }
        if ($readScore >= 70) {
            $stands[] = ['text' => "Bullets are the right length — easy to skim in seconds."];
        }
        if (!empty($firstExp['dates']) && preg_match('/present|current/i', $firstExp['dates'])) {
            $stands[] = ['text' => "Currently employed — reads as active and in-demand."];
        }
        if (empty($stands)) {
            $stands[] = ['text' => "The layout is clean and the sections are where I expect them."];
        }

        $weakens = [];
        if ($impactScore < 50) {
            $weakens[] = [
                'text' => "Almost no numbers — I can't gauge the scale of what you handled.",
                'fix' => 'experience'
            ];
        }
        if ($humanScore < 80) {
            $weakens[] = [
                'text' => "Some generic wording makes you blur into other candidates.",
                'fix' => 'summary'
            ];
        }
        $summaryWordCount = !empty($summary) ? count(preg_split('/\s+/', $summary)) : 0;
        if ($summaryWordCount > 60) {
            $weakens[] = [
                'text' => "The summary is long; I may not reach the end of it.",
                'fix' => 'summary'
            ];
        }
        if (empty($weakens)) {
            $weakens[] = ['text' => "Little to flag — a measurable win or two would add even more weight."];
        }

        $reject = [];
        $gaps = false;
        $years = [];
        foreach ($experiences as $x) {
            if (!is_array($x)) continue;
            $d = $x['dates'] ?? ($x['start_date'] ?? '');
            if (preg_match('/(\d{4}).+?(\d{4}|present)/i', $d, $m)) {
                $y1 = (int)$m[1];
                $y2 = preg_match('/present/i', $m[2]) ? (int)date('Y') : (int)$m[2];
                $years[] = [$y1, $y2];
            }
        }
        usort($years, function($a, $b) { return $a[0] - $b[0]; });
        for ($i = 1; $i < count($years); $i++) {
            if ($years[$i][0] - $years[$i-1][1] >= 2) {
                $gaps = true;
                break;
            }
        }
        if ($gaps) {
            $reject[] = [
                'text' => "An unexplained multi-year gap — I'd want a one-line reason before shortlisting.",
                'fix' => 'experience'
            ];
        }
        $hasSubstantiveExp = false;
        foreach ($experiences as $x) {
            if (!is_array($x)) continue;
            $role = trim($x['role'] ?? $x['position'] ?? '');
            $desc = trim($x['bullets'] ?? $x['description'] ?? '');
            if (!empty($role) || !empty($desc)) {
                $hasSubstantiveExp = true;
                break;
            }
        }
        if (!$hasSubstantiveExp) {
            $reject[] = [
                'text' => "No substantive experience shown — nothing to assess against the role.",
                'fix' => 'experience'
            ];
        }
        if (empty($reject)) {
            $reject[] = ['text' => "Nothing here would auto-reject you — no red flags on a first pass."];
        }

        $improve = [];
        if ($impactScore < 60) {
            $improve[] = [
                'text' => "Add one measurable result per role before you send this.",
                'fix' => 'experience'
            ];
        }
        if ($coverScore !== null && $coverScore < 60) {
            $improve[] = [
                'text' => "Mirror more of the job description's language — you're missing key terms.",
                'fix' => 'skills'
            ];
        }
        if (empty($linkedin)) {
            $improve[] = [
                'text' => "Add your LinkedIn URL — I'll look you up anyway.",
                'fix' => 'contact'
            ];
        }
        if (empty($improve)) {
            $improve[] = ['text' => "It's application-ready; tailor the top skills to each specific role."];
        }

        // Build 6-second scan elements
        $expScan = '';
        if (!empty($firstExp)) {
            $r = $firstExp['role'] ?? $firstExp['position'] ?? '';
            $c = $firstExp['company'] ?? '';
            $d = $firstExp['dates'] ?? '';
            $expScan = $r;
            if (!empty($c)) {
                $expScan .= (!empty($expScan) ? ', ' : '') . $c;
            }
            if (!empty($d)) {
                $expScan .= (!empty($expScan) ? ' (' . $d . ')' : $d);
            }
        }

        $topSkillsStr = implode(' · ', array_slice($skills, 0, 4));

        $eduScan = '';
        if (!empty($education) && is_array($education)) {
            $firstEdu = $education[0];
            if (is_array($firstEdu)) {
                $deg = $firstEdu['degree'] ?? '';
                $fld = $firstEdu['field'] ?? '';
                $sch = $firstEdu['school'] ?? '';
                $yr = $firstEdu['year'] ?? '';
                $degField = trim($deg . (!empty($fld) ? ' in ' . $fld : ''));
                $eduScan = $degField;
                if (!empty($sch)) {
                    $eduScan .= (!empty($eduScan) ? ' — ' : '') . $sch;
                }
                if (!empty($yr)) {
                    $eduScan .= ' (' . $yr . ')';
                }
            } elseif (is_string($firstEdu)) {
                $eduScan = $firstEdu;
            }
        }
        if (empty($eduScan)) {
            $eduScan = 'Education details';
        }

        $blockHtml = function($blockTitle, $items, $color) {
            $h = '<div class="rc-group"><div class="rc-gt" style="color:' . esc($color) . '">' . esc($blockTitle) . '</div>';
            foreach ($items as $it) {
                $h .= '<div class="rc-line">';
                $h .= '<span class="rc-dot" style="background:' . esc($color) . '"></span>';
                $h .= '<span>' . esc($it['text']) . '</span>';
                if (!empty($it['fix'])) {
                    $h .= '<button type="button" class="rc-fix-btn" data-fix="' . esc($it['fix']) . '">Fix</button>';
                }
                $h .= '</div>';
            }
            $h .= '</div>';
            return $h;
        };

        // Construct HTML response exactly matching candidate-AI Resume Builder.html
        $html = '<div class="scan-box"><div class="sb-t">The 6-second scan — what a recruiter sees first</div>'
            . '<b class="nm">' . esc($name) . ' — ' . esc($title) . '</b>'
            . (!empty($expScan) ? '<p>' . esc($expScan) . '</p>' : '')
            . (!empty($topSkillsStr) ? '<p>' . esc($topSkillsStr) . '</p>' : '')
            . '<p>' . esc($eduScan) . '</p></div>'
            . '<div class="rev-verdict"><div class="rev-head"><span class="rev-av" aria-hidden="true">JR</span><div><b>Recruiter Review</b><i>A JobberRecruit recruiter\'s read of your CV</i></div></div><p class="rev-body">&ldquo;' . esc($verdict) . '&rdquo;</p></div>'
            . '<span class="readiness ' . esc($band[0]) . '"><svg style="width:13px;height:13px" aria-hidden="true"><use href="#i-users"/></svg> Shortlist readiness: ' . esc($band[1]) . '</span>'
            . $blockHtml("What immediately stands out", $stands, "#1f9d55")
            . $blockHtml("What weakens confidence", $weakens, "#C8770E")
            . $blockHtml("What could cause rejection", $reject, "#c0392b")
            . $blockHtml("Improve before applying", $improve, "#0861A9")
            . '<p class="rc-note">A recruiter\'s realistic first-pass read — not a score or prediction. A strong match to the specific job matters just as much.</p>';

        return $this->respond(['recruiter_view' => $html]);
    }

    /**
     * AJAX: Generate Career Tools (Interview Questions / Salary Negotiation)
     */
    public function generateCareerTools()
    {
        $toolType = $this->request->getPost('tool_type') ?? 'interview'; // 'interview' or 'salary'
        $industry = $this->request->getPost('industry') ?? 'general';
        $title = $this->request->getPost('title') ?? 'Professional';

        $prompt = "You are a career coach for the {$industry} industry. The candidate is a {$title}.\n";
        
        if ($toolType === 'interview') {
            $prompt .= "Generate 3 highly tailored, challenging interview questions for this role in this industry. ";
            $prompt .= "Format as a simple HTML list (<ul><li>...</li></ul>). No markdown.";
        } else {
            $prompt .= "Provide 3 key salary negotiation talking points or strategies for this role in this industry based on current market trends. ";
            $prompt .= "Format as a simple HTML list (<ul><li>...</li></ul>). No markdown.";
        }

        $output = $this->aiService->generate($prompt);
        return $this->respond(['output' => $output]);
    }

    /**
     * AI Tailor Resume — adjust resume content to match a specific job description with advisory review
     */
    public function tailorResume()
    {
        $userId = auth()->id();
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $resumeJson = $this->request->getPost('resume_json');
        $jobDescription = $this->request->getPost('job_description');

        if (empty($resumeJson) || empty($jobDescription)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Resume data and job description are required.']);
        }

        $aiService = new \App\Services\AiService();

        $prompt = "You are ResumeAI, an expert executive recruiter and career coach.
Analyze the candidate's resume JSON against the target job description and tailor the resume content truthfully (no fabricated companies, dates, or false credentials).
Adjust the title, summary, skills, and work experience bullet points to match the keywords and requirements of the job.

Resume JSON:
{$resumeJson}

Target Job Description:
{$jobDescription}

Return a valid JSON object with the following schema:
{
  \"match_score\": 85,
  \"match_overview\": \"Summary explaining alignment strengths, keyword coverage, and areas enhanced.\",
  \"tailored\": {
    \"title\": \"Tailored Job Title / Resume Title\",
    \"summary\": \"Tailored 2-3 sentence executive summary aligned with target keywords.\",
    \"skills\": [\"Skill1\", \"Skill2\", \"Skill3\"],
    \"experiences\": [
      {
        \"company\": \"Company Name\",
        \"position\": \"Position Title\",
        \"start_date\": \"YYYY-MM-DD\",
        \"end_date\": \"YYYY-MM-DD\",
        \"is_current\": 0,
        \"description\": \"Tailored STAR bullet points focusing on relevant outcomes.\"
      }
    ]
  },
  \"recommendations\": [
    {
      \"section\": \"Professional Summary\",
      \"issue\": \"Original summary lacked core keyword alignment with the target job requirements.\",
      \"recommendation\": \"Reframed to highlight specific experience relevant to the role.\",
      \"current\": \"[Original Summary Snippet]\",
      \"proposed\": \"[Proposed Summary]\",
      \"target\": \"summary\"
    },
    {
      \"section\": \"Skills Alignment\",
      \"issue\": \"Missing high-frequency ATS skills requested in the job description.\",
      \"recommendation\": \"Added and prioritized target technical and functional skills.\",
      \"current\": \"[Original Skills]\",
      \"proposed\": \"[Proposed Skills]\",
      \"target\": \"skills\"
    }
  ]
}
Return ONLY valid JSON. No markdown fences or commentary.";

        try {
            $result = $aiService->generate($prompt);
            $tailoredStr = is_string($result) ? $result : ($result['content'] ?? $result['text'] ?? json_encode($result));

            // Clean markdown fences if present
            if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $tailoredStr, $m)) {
                $tailoredStr = trim($m[1]);
            }

            $decoded = json_decode($tailoredStr, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                // Fallback: try raw json_decode on the original string
                $decoded = json_decode(trim($tailoredStr), true);
            }

            if (!is_array($decoded) || (empty($decoded['tailored']) && empty($decoded['title']))) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'AI returned an unparseable response. Please try again.']);
            }

            // Normalise structure if AI returned flat tailored object
            if (empty($decoded['tailored']) && (!empty($decoded['title']) || !empty($decoded['summary']))) {
                $rawTailored = $decoded;
                $decoded = [
                    'match_score' => 85,
                    'match_overview' => 'Tailored resume content generated based on job description keywords.',
                    'tailored' => $rawTailored,
                    'recommendations' => []
                ];
            }

            return $this->response->setJSON([
                'status'          => 'success',
                'match_score'     => $decoded['match_score'] ?? 85,
                'match_overview'  => $decoded['match_overview'] ?? 'Your resume has been tailored to emphasize relevant experience and keywords from the job description.',
                'tailored'        => $decoded['tailored'] ?? [],
                'recommendations' => $decoded['recommendations'] ?? []
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'AI tailor resume error: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'AI service error. Please try again.']);
        }
    }
}
