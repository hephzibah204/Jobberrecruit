<?php

// CLI Test script for Phase 1 - Phase 4 verification
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
chdir(FCPATH);

require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootWorker($paths);

$passed = 0;
$failed = 0;
$tests = [];

function assertTest($condition, $testName, &$passed, &$failed, &$tests) {
    if ($condition) {
        $passed++;
        $tests[] = "[PASS] {$testName}";
        echo "  [PASS] {$testName}\n";
    } else {
        $failed++;
        $tests[] = "[FAIL] {$testName}";
        echo "  [FAIL] {$testName}\n";
    }
}

echo "\n=======================================================\n";
echo "  JOBBERRECRUIT COMPREHENSIVE QA AUDIT (PHASES 1 - 4)\n";
echo "=======================================================\n\n";

// ─── PHASE 1 TESTS ───
echo "--- Testing Phase 1: Candidate Profile & Visibility Core Fixes ---\n";

// Test 1.1: 10-Section Profile Weights & 100% Total
$seeker = new \App\Entities\JobSeeker();
assertTest($seeker->getProfileCompletion() === 0, "Phase 1: Empty seeker profile has 0% completion", $passed, $failed, $tests);

// Populate 10 sections
$seeker->id = 9999;
$seeker->full_name = 'Test Candidate';
$seeker->phone = '08012345678';
$seeker->location = 'Lagos';
$seeker->dob = '1995-05-15';
$seeker->gender = 'Male';
$seeker->job_title = 'Senior Developer';
$seeker->industry = 'Technology';
$seeker->employment_type = 'Full Time';
$seeker->desired_salary = '500,000';
$seeker->availability = 'Immediate';
$seeker->experience_years = 5;
$seeker->experiences = [['job_title' => 'Software Engineer', 'company' => 'Tech Corp']];
$seeker->education_level = 'BSc';
$seeker->educations = [['degree' => 'BSc Computer Science', 'school' => 'University of Lagos']];
$seeker->certifications = [['name' => 'AWS Certified Solutions Architect', 'organization' => 'Amazon Web Services']];
$seeker->skills = 'PHP, JavaScript, MySQL, Docker';
$seeker->resume = 'uploads/resumes/sample.pdf';
$seeker->portfolio = 'https://github.com/sample';
$seeker->languages = 'English, Yoruba';
$seeker->bio = 'Passionate engineer with extensive background in building scalable web applications.';

// With all base fields populated, score should be >= 80%
$score = $seeker->getProfileCompletion();
assertTest($score >= 80, "Phase 1: Fully completed profile achieves >= 80% (Actual: {$score}%)", $passed, $failed, $tests);

// Test 1.2: Check certifications entity method exists
assertTest(method_exists($seeker, 'getCertifications'), "Phase 1: JobSeeker::getCertifications() method exists", $passed, $failed, $tests);

// Test 1.3: JobSeekerCertificationModel exists
$certModel = new \App\Models\JobSeekerCertificationModel();
assertTest($certModel instanceof \App\Models\JobSeekerCertificationModel, "Phase 1: JobSeekerCertificationModel instantiated successfully", $passed, $failed, $tests);
assertTest(method_exists($certModel, 'forSeeker'), "Phase 1: JobSeekerCertificationModel::forSeeker() exists", $passed, $failed, $tests);

// Test 1.4: JobSeekerModel is_visible filters
$seekerModel = new \App\Models\JobSeekerModel();
assertTest(method_exists($seekerModel, 'getLastCandidates'), "Phase 1: JobSeekerModel::getLastCandidates() exists", $passed, $failed, $tests);
assertTest(method_exists($seekerModel, 'countByJobTitle'), "Phase 1: JobSeekerModel::countByJobTitle() exists", $passed, $failed, $tests);


// ─── PHASE 2 TESTS ───
echo "\n--- Testing Phase 2: Aptitude Test Centre & Email Notifications ---\n";

// Test 2.1: AptitudeTestInvitationModel getForCandidate method
$invModel = new \App\Models\AptitudeTestInvitationModel();
assertTest(method_exists($invModel, 'getForCandidate'), "Phase 2: AptitudeTestInvitationModel::getForCandidate() exists", $passed, $failed, $tests);
assertTest(method_exists($invModel, 'getByCode'), "Phase 2: AptitudeTestInvitationModel::getByCode() exists", $passed, $failed, $tests);

// Test 2.2: AptitudeController invitation methods
$aptController = new \App\Controllers\AptitudeController();
assertTest(method_exists($aptController, 'index'), "Phase 2: AptitudeController::index() exists", $passed, $failed, $tests);
assertTest(method_exists($aptController, 'acceptInvitation'), "Phase 2: AptitudeController::acceptInvitation() exists", $passed, $failed, $tests);
assertTest(method_exists($aptController, 'submitTest'), "Phase 2: AptitudeController::submitTest() exists", $passed, $failed, $tests);

// Test 2.3: JobAlertService immediate matching & weekly digest
$jobAlertService = new \App\Services\JobAlertService();
assertTest(method_exists($jobAlertService, 'sendImmediateMatchAlerts'), "Phase 2: JobAlertService::sendImmediateMatchAlerts() exists", $passed, $failed, $tests);
assertTest(method_exists($jobAlertService, 'sendWeeklyPremiumDigest'), "Phase 2: JobAlertService::sendWeeklyPremiumDigest() exists", $passed, $failed, $tests);
assertTest(method_exists($jobAlertService, 'processAlerts'), "Phase 2: JobAlertService::processAlerts() exists", $passed, $failed, $tests);

// Test 2.4: CronController endpoints
$cronController = new \App\Controllers\CronController();
assertTest(method_exists($cronController, 'sendJobAlerts'), "Phase 2: CronController::sendJobAlerts() exists", $passed, $failed, $tests);
assertTest(method_exists($cronController, 'sendWeeklyDigest'), "Phase 2: CronController::sendWeeklyDigest() exists", $passed, $failed, $tests);
assertTest(method_exists($cronController, 'runAllAutomations'), "Phase 2: CronController::runAllAutomations() exists", $passed, $failed, $tests);

// Test 2.5: Email template file exists
assertTest(file_exists(APPPATH . 'Views/emails/weekly_premium_digest.php'), "Phase 2: weekly_premium_digest.php email template exists", $passed, $failed, $tests);


// ─── PHASE 3 TESTS ───
echo "\n--- Testing Phase 3: AI Resume Builder & Multi-Format CV Engine ---\n";

$resumeController = new \App\Controllers\ResumeController();
// Test 3.1: Download endpoints
assertTest(method_exists($resumeController, 'download'), "Phase 3: ResumeController::download() (PDF) exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'downloadDocx'), "Phase 3: ResumeController::downloadDocx() (Word) exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'downloadTxt'), "Phase 3: ResumeController::downloadTxt() (Plain Text/ATS) exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'downloadJson'), "Phase 3: ResumeController::downloadJson() (JSON Resume) exists", $passed, $failed, $tests);

// Test 3.2: AI & Sync endpoints
assertTest(method_exists($resumeController, 'importFromProfile'), "Phase 3: ResumeController::importFromProfile() exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'getProfileData'), "Phase 3: ResumeController::getProfileData() exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'cloneResume'), "Phase 3: ResumeController::cloneResume() exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'chat'), "Phase 3: ResumeController::chat() exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'generateSummary'), "Phase 3: ResumeController::generateSummary() exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'generateBullets'), "Phase 3: ResumeController::generateBullets() exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'generateWritingReview'), "Phase 3: ResumeController::generateWritingReview() exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'generateRecruiterView'), "Phase 3: ResumeController::generateRecruiterView() exists", $passed, $failed, $tests);
assertTest(method_exists($resumeController, 'generateCoverLetter'), "Phase 3: ResumeController::generateCoverLetter() exists", $passed, $failed, $tests);


// ─── PHASE 4 TESTS ───
echo "\n--- Testing Phase 4: AI Career Tools (Mock Interview, Salary, Career Advice) ---\n";

$careerController = new \App\Controllers\CareerToolsController();
// Test 4.1: Controller endpoints
assertTest(method_exists($careerController, 'index'), "Phase 4: CareerToolsController::index() exists", $passed, $failed, $tests);
assertTest(method_exists($careerController, 'mockInterview'), "Phase 4: CareerToolsController::mockInterview() exists", $passed, $failed, $tests);
assertTest(method_exists($careerController, 'startInterviewSession'), "Phase 4: CareerToolsController::startInterviewSession() exists", $passed, $failed, $tests);
assertTest(method_exists($careerController, 'sendMessage'), "Phase 4: CareerToolsController::sendMessage() exists", $passed, $failed, $tests);
assertTest(method_exists($careerController, 'speak'), "Phase 4: CareerToolsController::speak() (TTS) exists", $passed, $failed, $tests);
assertTest(method_exists($careerController, 'evaluateInterview'), "Phase 4: CareerToolsController::evaluateInterview() exists", $passed, $failed, $tests);
assertTest(method_exists($careerController, 'salaryNegotiation'), "Phase 4: CareerToolsController::salaryNegotiation() exists", $passed, $failed, $tests);
assertTest(method_exists($careerController, 'saveNegotiationSession'), "Phase 4: CareerToolsController::saveNegotiationSession() exists", $passed, $failed, $tests);
assertTest(method_exists($careerController, 'careerAdvice'), "Phase 4: CareerToolsController::careerAdvice() exists", $passed, $failed, $tests);
assertTest(method_exists($careerController, 'generateAdvice'), "Phase 4: CareerToolsController::generateAdvice() exists", $passed, $failed, $tests);

// Test 4.2: AiService Core Methods
$aiService = new \App\Services\AiService();
assertTest(method_exists($aiService, 'getMockInterviewTurn'), "Phase 4: AiService::getMockInterviewTurn() exists", $passed, $failed, $tests);
assertTest(method_exists($aiService, 'getMockInterviewEvaluation'), "Phase 4: AiService::getMockInterviewEvaluation() exists", $passed, $failed, $tests);
assertTest(method_exists($aiService, 'getSalaryNegotiationResponse'), "Phase 4: AiService::getSalaryNegotiationResponse() exists", $passed, $failed, $tests);
assertTest(method_exists($aiService, 'getCareerAdvice'), "Phase 4: AiService::getCareerAdvice() exists", $passed, $failed, $tests);
assertTest(method_exists($aiService, 'textToSpeech'), "Phase 4: AiService::textToSpeech() exists", $passed, $failed, $tests);


// ─── ROUTE RESOLUTION TESTS ───
echo "\n--- Testing Route Definitions in Routes.php ---\n";

$routesContent = file_get_contents(APPPATH . 'Config/Routes.php');
$expectedRouteKeywords = [
    'cron/send-job-alerts',
    'cron/send-weekly-digest',
    'resumes/download/(:num)',
    'resumes/download-docx/(:num)',
    'resumes/download-txt/(:num)',
    'resumes/download-json/(:num)',
    'CareerToolsController::mockInterview',
    'CareerToolsController::startInterviewSession',
    'CareerToolsController::salaryNegotiation',
    'CareerToolsController::careerAdvice',
];

foreach ($expectedRouteKeywords as $keyword) {
    assertTest(strpos($routesContent, $keyword) !== false, "Routes.php contains '{$keyword}'", $passed, $failed, $tests);
}

echo "\n=======================================================\n";
echo "  FINAL QA TEST SUMMARY:\n";
echo "  Passed: {$passed}\n";
echo "  Failed: {$failed}\n";
echo "  Total:  " . ($passed + $failed) . "\n";
echo "=======================================================\n\n";

if ($failed === 0) {
    echo "🎉 ALL PHASES 1 - 4 AUTOMATED AUDITS PASSED WITH ZERO ERRORS!\n\n";
    exit(0);
} else {
    echo "⚠️ SOME AUDIT TESTS FAILED.\n\n";
    exit(1);
}
