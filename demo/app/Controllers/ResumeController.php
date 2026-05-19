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

    public function __construct()
    {
        $this->resumeModel = model(ResumeModel::class);
        $this->experienceModel = model(ResumeExperienceModel::class);
        $this->educationModel = model(ResumeEducationModel::class);
        $this->skillModel = model(ResumeSkillModel::class);
        $this->aiService = new AiService();
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

        return view('candidate/resume/builder', [
            'title' => $resume ? 'Edit Resume' : 'Create Resume',
            'resume' => $resume,
            'experiences' => $experiences,
            'education' => $education,
            'skills' => $skills
        ]);
    }

    /**
     * AJAX: Generate professional summary
     */
    public function generateSummary()
    {
        $experiences = $this->request->getPost('experiences') ?? [];
        $skills = $this->request->getPost('skills') ?? [];

        if (empty($experiences) && empty($skills)) {
            return $this->fail('Please provide some experience or skills to generate a summary.');
        }

        $summary = $this->aiService->generateProfessionalSummary($experiences, $skills);
        return $this->respond(['summary' => $summary]);
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

        // Handle Education
        $this->educationModel->where('resume_id', $resumeId)->delete();
        $eduSchools = $this->request->getPost('edu_school') ?? [];
        $eduDegrees = $this->request->getPost('edu_degree') ?? [];
        $eduFields = $this->request->getPost('edu_field') ?? [];
        $eduYears = $this->request->getPost('edu_year') ?? [];

        foreach ($eduSchools as $index => $school) {
            if (empty($school)) continue;
            $this->educationModel->insert([
                'resume_id' => $resumeId,
                'school' => $school,
                'degree' => $eduDegrees[$index] ?? '',
                'field_of_study' => $eduFields[$index] ?? '',
                'graduation_year' => $eduYears[$index] ?? null,
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
}
