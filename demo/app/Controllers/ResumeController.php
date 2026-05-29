<?php

namespace App\Controllers;

use App\Models\ResumeModel;
use App\Models\ResumeExperienceModel;
use App\Models\ResumeEducationModel;
use App\Models\ResumeSkillModel;
use App\Services\AiService;
use CodeIgniter\API\ResponseTrait;
use Dompdf\Dompdf;
use Dompdf\Options;

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
            return $this->fail('origin_url is required and must be a valid URL');
        }

        if (stripos($origin, 'https://') !== 0) {
            return $this->fail('Only https URLs are allowed for proxied images');
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
            return $this->fail($result['error']);
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

        // Fetch candidate profile and existing resumes for the onboarding modal
        $candidateModel = model(\App\Models\JobSeekerModel::class);
        $candidate = $candidateModel->where('user_id', $user->id)->first();
        $allResumes = $this->resumeModel->where('user_id', $user->id)->orderBy('updated_at', 'DESC')->findAll();

        return view('candidate/resume/builder', [
            'title'      => $resume ? 'Edit Resume' : 'Create Resume',
            'resume'     => $resume,
            'experiences'=> $experiences,
            'education'  => $education,
            'skills'     => $skills,
            'candidate'  => $candidate,
            'allResumes' => $allResumes,
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

        // Create a new resume pre-seeded from profile
        $resumeId = $this->resumeModel->insert([
            'user_id'     => $user->id,
            'title'       => ($candidate->job_title ?? 'My') . ' Resume',
            'summary'     => $candidate->bio ?? '',
            'template_id' => 'classic',
        ]);

        if (!$resumeId) {
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

        // Seed one education row from profile if education_level is set
        if (!empty($candidate->education_level)) {
            $this->educationModel->insert([
                'resume_id'       => $resumeId,
                'institution'     => '',
                'degree'          => $candidate->education_level,
                'field_of_study'  => '',
                'graduation_date' => null,
            ]);
        }

        return redirect()->to('candidate/resumes/build/' . $resumeId)
            ->with('success', 'Resume created from your profile! Complete the remaining details below.');
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

        // Clone the parent resume record
        $newResumeId = $this->resumeModel->insert([
            'user_id'     => $user->id,
            'title'       => 'Copy of ' . $source->title,
            'summary'     => $source->summary,
            'template_id' => $source->template_id,
        ]);

        if (!$newResumeId) {
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

        if (empty($expStrings) && empty($skills) && empty($eduStrings)) {
            return $this->fail('Please provide some experience, education, or skills to generate a summary.');
        }

        $summary = $this->aiService->generateProfessionalSummary($expStrings, $skills, $eduStrings);
        return $this->respond(['summary' => $summary]);
    }

    /**
     * AJAX: Generate bullets for a specific experience using AI
     */
    public function generateBullets()
    {
        $description = $this->request->getPost('description');
        $jobTitle = $this->request->getPost('job_title') ?? '';

        if (empty($description)) {
            return $this->fail('Description is required to generate bullets.');
        }

        // Build a prompt context to improve bullet generation
        $context = '';
        if ($jobTitle) {
            $context .= "Job Title: {$jobTitle}\n";
        }

        $prompt = "Generate 3-5 concise, achievement-oriented resume bullet points based on the following experience description. Use action verbs, quantify results when possible, and keep each bullet under 20 words. Return bullets separated by newline characters.\n\n";
        $prompt .= $context . "\n" . $description;

        $bullets = $this->aiService->generate($prompt);

        return $this->respond(['bullets' => $bullets]);
    }

    /**
     * AJAX: Improve description
     */
    public function improveDescription()
    {
        $description = $this->request->getPost('description');
        if (empty($description)) {
            return $this->fail('Description cannot be empty.');
        }

        $improved = $this->aiService->improveDescription($description);
        return $this->respond(['description' => $improved]);
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
        if (empty($autosaveId)) return $this->fail('autosave_id is required');
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
        $candidateName = $candidate?->full_name ?? 'Candidate';
        $skills = $candidate?->skills ?? '';
        $targetRole = $candidate?->job_title ?? '';
        $experienceYears = $candidate?->experience_years ?? '';

        $context = "You are ResumeAI, an expert resume consultant and professional career coach guiding candidate '{$candidateName}'. ";
        if ($targetRole) {
            $context .= "Target role: {$targetRole}. ";
        }
        if ($skills) {
            $context .= "Skills: {$skills}. ";
        }
        if ($experienceYears) {
            $context .= "Experience level: {$experienceYears} years. ";
        }
        $context .= "Strictly follow the 6-stage professional coaching workflow: 1. Intake, 2. Section-by-Section, 3. Job Experience STAR, 4. Professional Summary, 5. ATS Optimization, 6. Final Review. ";
        $context .= "NEVER give generic samples. Present ONE polished, tailored output at a time. Speak in a coaching, results-oriented, and direct tone.";

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
        $id = $this->request->getPost('id');

        $resumeData = [
            'user_id' => $user->id,
            'title' => $this->request->getPost('title'),
            'summary' => $this->request->getPost('summary'),
            'template_id' => $this->request->getPost('template_id') ?? 'classic'
        ];

        if ($id) {
            $existing = $this->resumeModel->where('user_id', $user->id)->find($id);
            if (!$existing) {
                return $this->fail('Resume not found', 404);
            }
            $this->resumeModel->update($id, $resumeData);
            $resumeId = $id;
        } else {
            $resumeId = $this->resumeModel->insert($resumeData);
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
                'start_date' => $expStartDates[$index] ?? date('Y-m-d'),
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

        $experiences = $this->experienceModel->where('resume_id', $id)->findAll();
        $education = $this->educationModel->where('resume_id', $id)->findAll();
        $skills = $this->skillModel->where('resume_id', $id)->findAll();

        $html = view('candidate/resume/templates/' . ($resume->template_id ?? 'classic'), [
            'resume' => $resume,
            'experiences' => $experiences,
            'education' => $education,
            'skills' => $skills
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream(url_title($resume->title) . ".pdf", ["Attachment" => true]);
        exit();
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
}
