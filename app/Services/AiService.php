<?php

namespace App\Services;

use App\Models\AiImageModel;

class AiService
{
    /**
     * Model Router Task Mappings
     * Automatically routes tasks to the best Gemini model for speed, cost, or reasoning depth.
     */
    protected array $taskModelMap = [
        'chat'      => 'gemini-3.8-flash', // Interactive chatbot widget & turns
        'fast'      => 'gemini-3.8-flash', // Fast short text generation, summaries & bullet points
        'balanced'  => 'gemini-3.8-flash', // Career advice, salary negotiation, cover letters
        'reasoning' => 'gemini-3.8-flash', // CV scoring/reviews, interview evaluations, aptitude test generation
        'audio'     => 'gemini-3.8-flash',      // Multimodal audio transcription & speech processing
    ];

    protected ?string $apiKey = null;
    protected string $model = 'gemini-3.8-flash';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->model  = env('GEMINI_MODEL') ?: 'gemini-3.8-flash';
    }

    /**
     * Resolve the target model for a given task type or custom model key
     */
    public function resolveModel(string $task = 'fast'): string
    {
        // Check if specific task override exists in environment variables (e.g. GEMINI_MODEL_CHAT)
        $envTaskKey = 'GEMINI_MODEL_' . strtoupper($task);
        if ($envTaskModel = env($envTaskKey)) {
            return $envTaskModel;
        }

        if ($task !== 'audio' && env('GEMINI_MODEL')) {
            return $this->model;
        }

        return $this->taskModelMap[$task] ?? ($this->taskModelMap['fast']);
    }

    /**
     * Get API URL for a specific model or task
     */
    protected function getApiUrl(string $task = 'fast'): string
    {
        $selectedModel = $this->resolveModel($task);
        return 'https://generativelanguage.googleapis.com/v1beta/models/' . $selectedModel . ':generateContent';
    }

    /**
     * Cache TTL configuration per task (in seconds)
     */
    protected array $cacheTtlMap = [
        'fast'      => 86400,    // 24 hours for short text prompts & summaries
        'balanced'  => 86400,    // 24 hours for cover letters & career advice
        'reasoning' => 259200,   // 3 days for CV reviews & aptitude question sets
        'audio'     => 43200,    // 12 hours for audio transcriptions
    ];

    /**
     * Generate content using Gemini API with model routing and intelligent response caching
     */
    public function generate($prompt, string $task = 'fast', int $customTtl = 0, int $maxOutputTokens = 1500)
    {
        if (empty($this->apiKey)) {
            return $this->handleGenerateFallback($prompt, "AI Service is not configured. Please add GEMINI_API_KEY to your .env file.");
        }

        // 1. Check Response Cache (if enabled)
        $cacheEnabled = env('AI_CACHE_ENABLED', true);
        $cacheKey = 'ai_gen_' . md5($task . '_' . $prompt);
        if ($cacheEnabled) {
            try {
                $cache = \Config\Services::cache();
                if ($cachedResponse = $cache->get($cacheKey)) {
                    log_message('info', "AI Cache HIT for task: {$task} (Key: {$cacheKey})");
                    return $cachedResponse;
                }
            } catch (\Throwable $e) {
                // Ignore cache read failures
            }
        }

        $url = $this->getApiUrl($task) . '?key=' . $this->apiKey;

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'maxOutputTokens' => $maxOutputTokens,
            ],
        ];

        $payloadJson = json_encode($payload);
        $lastErr = '';
        $maxAttempts = 3;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            $errno = curl_errno($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (! $err && $errno === 0 && $httpCode < 500) {
                $result = json_decode($response, true);

                if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    $textResult = $result['candidates'][0]['content']['parts'][0]['text'];

                    // Save response to cache
                    if ($cacheEnabled && !empty($textResult)) {
                        try {
                            $ttl = $customTtl > 0 ? $customTtl : ($this->cacheTtlMap[$task] ?? 86400);
                            $cache = \Config\Services::cache();
                            $cache->save($cacheKey, $textResult, $ttl);
                        } catch (\Throwable $e) {
                            // Ignore cache write failures
                        }
                    }

                    return $textResult;
                }

                if (isset($result['error'])) {
                    $lastErr = $result['error']['message'] ?? 'Unknown API error';
                    log_message('error', "Gemini generate API error (HTTP {$httpCode}, attempt {$attempt}/{$maxAttempts}): {$lastErr}");
                    break;
                }

                $lastErr = 'Unrecognized API response shape';
                log_message('error', "Gemini generate API returned no candidates (HTTP {$httpCode}, attempt {$attempt}/{$maxAttempts}): " . substr((string) $response, 0, 500));
                break;
            }

            $lastErr = $err ?: ("HTTP {$httpCode}" . ($errno ? ", cURL error code {$errno}" : ''));
            log_message('error', "Gemini generate request failed (attempt {$attempt}/{$maxAttempts}): {$lastErr}");

            if ($attempt < $maxAttempts) {
                usleep(300000 * $attempt);
            }
        }

        return $this->handleGenerateFallback($prompt, $lastErr);
    }

    /**
     * Generate a professional summary
     */
    public function generateProfessionalSummary($experiences = [], $skills = [], $education = [])
    {
        $promptParts = [];
        if (!empty($experiences) && is_array($experiences)) {
            $filteredExp = array_filter(array_map('trim', $experiences), fn($e) => $e !== '');
            if (!empty($filteredExp)) {
                $promptParts[] = 'Work Experience / Positions: ' . implode('; ', $filteredExp);
            }
        }
        if (!empty($education) && is_array($education)) {
            $filteredEdu = array_filter(array_map('trim', $education), fn($e) => $e !== '');
            if (!empty($filteredEdu)) {
                $promptParts[] = 'Education: ' . implode('; ', $filteredEdu);
            }
        }
        if (!empty($skills)) {
            $skillsList = is_array($skills) ? $skills : array_map('trim', explode(',', (string)$skills));
            $skillsList = array_values(array_filter(array_map('trim', $skillsList), fn($s) => $s !== ''));
            if (!empty($skillsList)) {
                $promptParts[] = 'Skills / Competencies: ' . implode(', ', $skillsList);
            }
        }

        $context = implode("\n", $promptParts);

        $prompt = "You are ResumeAI, a professional executive resume writer and career strategist.\n\n" .
                  "OBJECTIVE:\n" .
                  "Write ONE clear, compelling, recruiter-focused professional resume summary (2 to 3 sentences, maximum 600 characters) strictly reflecting the candidate's verified background below.\n\n" .
                  "MANDATORY GUIDELINES:\n" .
                  "1. Truthful: Use ONLY facts explicitly provided in the candidate data block. Never invent years of experience, metrics, degrees, or unmentioned technologies.\n" .
                  "2. Direct & Role-Focused: Lead immediately with their actual job title/profession (e.g., 'Senior Software Engineer with a focus on...'). Do NOT use awkward phrasing like 'based on experience in Senior Software Engineer'.\n" .
                  "3. Strict Cliché Prohibition: Never use empty buzzwords such as 'Results-driven professional', 'Proven track record', 'dynamic professional', 'optimizing operational workflows', or 'cross-functional teams to accelerate project delivery'.\n" .
                  "4. Context Isolation: The formatting guidelines and prompt labels are instructions for YOU, NOT candidate skills. Never output phrases like 'expertise in Requirements:', 'Skills include Requirements', or similar meta-text.\n" .
                  "5. Output Format: Return plain text ONLY. No markdown bolding (** or *), no quotes, and no introductory remarks.\n\n";

        if (!empty($context)) {
            $prompt .= "=== CANDIDATE PROFILE DATA ===\n" . $context . "\n=== END CANDIDATE DATA ===\n\n";
        }

        $prompt .= "Provide only the polished 2-3 sentence summary text:";

        $result = $this->generate($prompt);

        // Post-processing sanity cleanup
        if (!empty($result)) {
            $result = preg_replace('/\b(?:with\s+)?expertise in Requirements:?\s*(?:and\s+)?/i', '', $result);
            $result = preg_replace('/\bbased on experience in\b/i', 'with experience as a', $result);
            $result = preg_replace('/^(?:Results-driven\s+)?professional\s*\.\s*/i', '', $result);
            $result = preg_replace('/\s+([.,;:!?])/', '$1', $result);
            $result = preg_replace('/([.,;:!?]){2,}/', '$1', $result);
            $result = trim(preg_replace('/\s+/', ' ', $result));
        }

        return $result;
    }

    /**
     * Improve a candidate's existing professional summary
     */
    public function improveSummary($currentSummary, $skills = [])
    {
        $cleanSummary = trim((string)$currentSummary);
        $skillsText = '';
        if (!empty($skills)) {
            $skillsList = is_array($skills) ? $skills : array_map('trim', explode(',', (string)$skills));
            $skillsList = array_filter($skillsList);
            if (!empty($skillsList)) {
                $skillsText = "\nSkills Context: " . implode(', ', $skillsList);
            }
        }

        $prompt = "You are an executive resume writer and professional profile specialist. Elevate and improve the candidate's existing professional summary into a compelling 2-3 sentence executive profile.\n\n" .
                  "STRICT RULES (CRITICAL):\n" .
                  "1. Read the candidate's existing summary carefully and understand their authentic role, background, and employment context.\n" .
                  "2. Improve and polish the wording to be impactful, recruiter-focused, and professional.\n" .
                  "3. Strictly preserve all underlying facts, actual achievements, and organizations mentioned.\n" .
                  "4. DO NOT replace their background with a generic profile, and DO NOT invent unrelated industries, degrees, or certifications.\n" .
                  "5. DO NOT provide conversational commentary, greetings, quotes, or meta-text (such as 'Here is an improved summary' or 'This is how to improve...').\n" .
                  "6. Return ONLY the improved summary text directly.\n\n" .
                  "Candidate's existing summary:\n" . $cleanSummary . $skillsText;

        return $this->generate($prompt);
    }

    /**
     * Improve a candidate work experience or achievement description
     */
    public function improveDescription($description, $jobTitle = '')
    {
        $cleanDesc = trim((string)$description);
        $context = '';
        if (!empty($jobTitle)) {
            $context = "Job Title Context: " . trim((string)$jobTitle) . "\n";
        }

        $prompt = "You are a professional resume writer and talent expert. Improve and strengthen the candidate's entered work experience or achievement description to be more impactful, using strong action verbs and professional phrasing.\n\n" .
                  "CRITICAL REQUIREMENTS (STRICTLY ENFORCED):\n" .
                  "1. Read the candidate's existing content carefully and understand the actual context, employment role, and organization (e.g. if the candidate writes 'I recruit for Jobber Recruit', understand that they work in talent acquisition/recruitment for Jobber Recruit).\n" .
                  "2. Improve the existing wording directly to sound authoritative, accomplished, and professional.\n" .
                  "3. Preserve all underlying facts, real duties, and organization names. DO NOT invent unrelated experience or different organizations.\n" .
                  "4. DO NOT provide generic suggestions or boilerplate unrelated to the supplied text.\n" .
                  "5. Strengthen the entered achievement directly as ONE cohesive professional statement or achievement bullet. DO NOT provide multiple alternative options or lists.\n" .
                  "6. DO NOT output conversational preamble, explanation, headings, or meta-text (such as 'Here is an improved version', 'This is how to improve that you recruit for...', 'Option 1:', etc.). Return ONLY the strengthened version directly.\n\n" .
                  $context . "Candidate's existing content:\n" . $cleanDesc;

        return $this->generate($prompt);
    }

    /**
     * Generate achievement bullets for candidate resumes
     */
    public function generateBullets($description, $jobTitle = '')
    {
        $cleanDesc = trim((string)$description);
        $context = '';
        if (!empty($jobTitle)) {
            $context = "Job Title Context: " . trim((string)$jobTitle) . "\n";
        }

        $prompt = "You are an expert resume writer. Convert the candidate's existing information into 3-5 concise, highly relevant, professional bullet points.\n\n" .
                  "CRITICAL REQUIREMENTS (STRICTLY ENFORCED):\n" .
                  "1. Read the candidate's existing content and understand their actual employment context and role.\n" .
                  "2. Convert their real responsibilities and achievements into professional bullet points starting with strong action verbs.\n" .
                  "3. Keep the content factually connected to the original information. DO NOT invent unrelated responsibilities, achievements, metrics, or organizations.\n" .
                  "4. Avoid generic suggestions unrelated to the supplied text.\n" .
                  "5. Return ONLY the bullet points, each starting with '• ' and separated by newline characters. DO NOT include any conversational preamble, intro text, or explanation.\n\n" .
                  $context . "Candidate's existing content:\n" . $cleanDesc;

        return $this->generate($prompt);
    }

    /**
     * Generate a structured mock interview turn with STAR scoring.
     *
     * @param array<int, array<string, mixed>> $history
     * @param array<string, mixed>             $options
     *
     * @return array<string, mixed>
     */
    public function getMockInterviewTurn(string $message, array $history = [], array $options = [], string $candidateName = ''): array
    {
        $jobTitle = (string) ($options['job_title'] ?? '');
        if ($jobTitle === '') {
            $jobTitle = (string) ($options['candidate_job_title'] ?? '');
        }
        $difficulty = (string) ($options['difficulty'] ?? 'medium');
        $questionPack = (string) ($options['question_pack'] ?? 'general');
        $interviewMode = (string) ($options['interview_mode'] ?? 'chat');
        $webcamEnabled = ! empty($options['webcam_enabled']);
        $companyName = trim((string) ($options['company_name'] ?? ''));
        $jobDescription = trim((string) ($options['job_description'] ?? ''));
        $jobRequirements = trim((string) ($options['job_requirements'] ?? ''));
        $jobSkills = trim((string) ($options['job_skills'] ?? ''));
        $candidateProfile = trim((string) ($options['candidate_profile'] ?? ''));
        $coverLetter = trim((string) ($options['cover_letter'] ?? ''));
        $summaryNote = trim((string) ($options['summary_note'] ?? ''));

        $interviewType = (string) ($options['interview_type'] ?? '');
        $duration = (string) ($options['duration'] ?? '');
        $personality = (string) ($options['personality'] ?? '');
        $experience = (string) ($options['experience'] ?? '');
        $focus = (string) ($options['focus'] ?? '');
        $salary = (string) ($options['salary'] ?? '');
        $arrangement = (string) ($options['arrangement'] ?? '');
        $language = (string) ($options['language'] ?? '');
        $companyType = (string) ($options['company_type'] ?? '');

        $clientHour = isset($options['client_hour']) ? (int) $options['client_hour'] : (int) date('G');
        $timeGreeting = (string) ($options['client_time_greeting'] ?? ($clientHour < 12 ? 'Good morning' : ($clientHour < 17 ? 'Good afternoon' : 'Good evening')));

        $context = "[[MOCK_INTERVIEW_SESSION:{$questionPack}]] You are an experienced hiring manager conducting a mock interview for a '{$jobTitle}' position with '{$candidateName}'. ";
        $context .= "Candidate local time of day: {$timeGreeting} (hour {$clientHour}:00). GREETING RULE: If greeting the candidate, you MUST match their time of day: '{$timeGreeting}'. Never say 'Good morning' in the afternoon, evening, or night! ";
        if ($companyName !== '') {
            $context .= "Company: {$companyName}. ";
        }
        if ($companyType !== '' && $companyType !== 'any') {
            $context .= "Company Type: {$companyType}. ";
        }
        $context .= "Difficulty level: {$difficulty}. ";
        $context .= 'Question pack: ' . $this->getQuestionPackContext($questionPack) . '. ';
        $context .= "Interview mode: {$interviewMode}. ";
        if ($interviewType !== '') {
            $context .= "Interview Type/Style: {$interviewType}. ";
        }
        if ($personality !== '') {
            $context .= "Your persona/profile: {$personality}. Adopt this character and recruiter tone throughout. ";
        }
        if ($experience !== '') {
            $context .= "Candidate expected level: {$experience}. ";
        }
        if ($focus !== '' && $focus !== 'balanced') {
            $context .= "Focus areas: {$focus}. ";
        }
        if ($salary !== '') {
            $context .= "Candidate expected salary band: {$salary}. ";
        }
        if ($arrangement !== '') {
            $context .= "Work arrangement: {$arrangement}. ";
        }
        if ($language !== '') {
            $context .= "Language/Dialect preference: {$language}. ";
        }
        if ($webcamEnabled) {
            $context .= "The candidate is practicing in webcam mode, so keep the tone realistic for a live video interview. ";
        }
        if ($summaryNote !== '') {
            $context .= $summaryNote . ' ';
        }
        if ($jobDescription !== '') {
            $context .= "Job description: {$jobDescription}. ";
        }
        if ($jobRequirements !== '') {
            $context .= "Job requirements: {$jobRequirements}. ";
        }
        if ($jobSkills !== '') {
            $context .= "Key job skills: {$jobSkills}. ";
        }
        if ($candidateProfile !== '') {
            $context .= "Candidate profile summary: {$candidateProfile}. ";
        }
        if ($coverLetter !== '') {
            $context .= "Candidate cover letter summary: {$coverLetter}. ";
        }
        $context .= "Use the job description, requirements, submitted application context, and candidate profile as the scoring yardstick. CRITICAL REQUIREMENT: Critically evaluate the candidate's answers. If the candidate provides wrong, incorrect, irrelevant, or incomplete answers, or simply says they do not know, detect this immediately. You must lower their STAR scores (1-3 out of 10) for that turn, and provide clear corrective feedback in 'feedback' and 'star_tip' pointing out the mistake or gap. ";
        $context .= "YOU control the interview: decide every question yourself, in real time, based on the candidate's answers so far — never repeat a question or pull from a fixed script. ";
        $context .= "QUESTION SOURCE REQUIREMENT: 'next_question' must be grounded in this specific candidate's listed skills, years of experience, education, target role, cover letter, and (if given) prior answers — reference an actual skill, project type, tool, or experience level named above. Do NOT ask generic, one-size-fits-all interview questions ('Tell me about a challenge you faced') unless you tie it explicitly to something in the candidate's background. If little candidate background is available, ask questions grounded in the job title/description/requirements instead of generic filler. Vary question difficulty and topic based on the candidate's stated experience level. ";
        $context .= "Return ONLY valid JSON with this exact shape: ";
        $context .= '{"feedback":"","next_question":"","interviewer_reply":"","star_score":0,"star_breakdown":{"situation":0,"task":0,"action":0,"result":0},"star_tip":"","focus_area":""}. ';
        $context .= "Use integer scores from 1 to 10 for star_score and each STAR breakdown item. ";
        $context .= "feedback should be brief, next_question should ask exactly one question, interviewer_reply should combine brief feedback and the next question in a natural spoken way, star_tip should tell the candidate how to improve their STAR answer, and focus_area should be 2-5 words. ";
        $context .= "Ask realistic questions tied to the job requirements and gaps you detect. ";
        $context .= "Keep the interviewer_reply concise and natural for chat or voice playback.";

        $response = $this->getChatResponse($message, $history, $context);
        $decoded = $this->extractJsonObject($response);

        if (is_array($decoded)) {
            $feedback = (string) ($decoded['feedback'] ?? '');
            $nextQuestion = (string) ($decoded['next_question'] ?? '');
            $reply = (string) ($decoded['interviewer_reply'] ?? '');

            if ($reply === '') {
                $reply = trim($feedback . ' ' . $nextQuestion);
            }

            if ($reply !== '') {
                $reply = preg_replace('/^(Good morning|Good afternoon|Good evening|Good day)[,.]?\s*/i', $timeGreeting . ', ', $reply);
            }
            if ($nextQuestion !== '') {
                $nextQuestion = preg_replace('/^(Good morning|Good afternoon|Good evening|Good day)[,.]?\s*/i', $timeGreeting . ', ', $nextQuestion);
            }

            return [
                'message' => $reply,
                'feedback' => $feedback,
                'next_question' => $nextQuestion,
                'star_score' => (int) ($decoded['star_score'] ?? 0),
                'star_breakdown' => [
                    'situation' => (int) (($decoded['star_breakdown']['situation'] ?? 0)),
                    'task'      => (int) (($decoded['star_breakdown']['task'] ?? 0)),
                    'action'    => (int) (($decoded['star_breakdown']['action'] ?? 0)),
                    'result'    => (int) (($decoded['star_breakdown']['result'] ?? 0)),
                ],
                'star_tip' => (string) ($decoded['star_tip'] ?? ''),
                'focus_area' => (string) ($decoded['focus_area'] ?? ''),
                'difficulty' => $difficulty,
                'question_pack' => $questionPack,
            ];
        }

        return [
            'message' => is_string($response) ? $response : 'Let us continue the interview. Tell me more about your approach.',
            'feedback' => '',
            'next_question' => '',
            'star_score' => 0,
            'star_breakdown' => [
                'situation' => 0,
                'task'      => 0,
                'action'    => 0,
                'result'    => 0,
            ],
            'star_tip' => '',
            'focus_area' => '',
            'difficulty' => $difficulty,
            'question_pack' => $questionPack,
        ];
    }

    /**
     * Create a final mock interview evaluation.
     *
     * @return array<string, mixed>
     */
    public function getMockInterviewEvaluation(array $history, array $options = [], string $candidateName = ''): array
    {
        $jobTitle = (string) ($options['job_title'] ?? '');
        if ($jobTitle === '') {
            $jobTitle = (string) ($options['candidate_job_title'] ?? '');
        }
        $difficulty = (string) ($options['difficulty'] ?? 'medium');
        $questionPack = (string) ($options['question_pack'] ?? 'general');
        $interviewMode = (string) ($options['interview_mode'] ?? 'chat');
        $webcamEnabled = ! empty($options['webcam_enabled']);
        $companyName = trim((string) ($options['company_name'] ?? ''));
        $jobDescription = trim((string) ($options['job_description'] ?? ''));
        $jobRequirements = trim((string) ($options['job_requirements'] ?? ''));
        $jobSkills = trim((string) ($options['job_skills'] ?? ''));
        $candidateProfile = trim((string) ($options['candidate_profile'] ?? ''));
        $coverLetter = trim((string) ($options['cover_letter'] ?? ''));
        $summaryNote = trim((string) ($options['summary_note'] ?? ''));
        $transcript = [];

        foreach ($history as $chat) {
            $speaker = ($chat['sender'] ?? 'model') === 'user' ? 'Candidate' : 'Interviewer';
            $message = trim((string) ($chat['message'] ?? ''));

            if ($message === '') {
                continue;
            }

            $transcript[] = $speaker . ': ' . $message;
        }

        $interviewType = (string) ($options['interview_type'] ?? '');
        $duration = (string) ($options['duration'] ?? '');
        $personality = (string) ($options['personality'] ?? '');
        $experience = (string) ($options['experience'] ?? '');
        $focus = (string) ($options['focus'] ?? '');
        $salary = (string) ($options['salary'] ?? '');
        $arrangement = (string) ($options['arrangement'] ?? '');
        $language = (string) ($options['language'] ?? '');
        $companyType = (string) ($options['company_type'] ?? '');

        $prompt = "You are a senior interview coach reviewing a completed mock interview for '{$candidateName}' applying for '{$jobTitle}'. ";
        if ($companyName !== '') {
            $prompt .= "Company: {$companyName}. ";
        }
        if ($companyType !== '' && $companyType !== 'any') {
            $prompt .= "Company Type: {$companyType}. ";
        }
        $prompt .= "Difficulty level: {$difficulty}. ";
        $prompt .= 'Question pack: ' . $this->getQuestionPackContext($questionPack) . '. ';
        $prompt .= "Interview mode: {$interviewMode}. ";
        if ($interviewType !== '') {
            $prompt .= "Interview Type/Style: {$interviewType}. ";
        }
        if ($personality !== '') {
            $prompt .= "Interviewer persona: {$personality}. ";
        }
        if ($experience !== '') {
            $prompt .= "Candidate expected level: {$experience}. ";
        }
        if ($focus !== '' && $focus !== 'balanced') {
            $prompt .= "Focus areas: {$focus}. ";
        }
        if ($salary !== '') {
            $prompt .= "Candidate expected salary band: {$salary}. ";
        }
        if ($arrangement !== '') {
            $prompt .= "Work arrangement: {$arrangement}. ";
        }
        if ($language !== '') {
            $prompt .= "Language/Dialect preference: {$language}. ";
        }
        if ($webcamEnabled) {
            $prompt .= "The practice was done in webcam mode, so include concise video interview guidance. ";
        }
        if ($summaryNote !== '') {
            $prompt .= $summaryNote . ' ';
        }
        if ($jobDescription !== '') {
            $prompt .= "Job description: {$jobDescription}. ";
        }
        if ($jobRequirements !== '') {
            $prompt .= "Job requirements: {$jobRequirements}. ";
        }
        if ($jobSkills !== '') {
            $prompt .= "Key job skills: {$jobSkills}. ";
        }
        if ($candidateProfile !== '') {
            $prompt .= "Candidate profile summary: {$candidateProfile}. ";
        }
        if ($coverLetter !== '') {
            $prompt .= "Candidate cover letter summary: {$coverLetter}. ";
        }
        $prompt .= "Score performance against the job needs and the candidate's submitted application context. ";
        $prompt .= "Analyze the transcript and return ONLY valid JSON with this exact shape: ";
        $prompt .= '{"overall_score":0,"communication_score":0,"confidence_score":0,"relevance_score":0,"star_average":0,"star_summary":"","summary":"","strengths":[""],"improvements":[""],"next_steps":[""]}. ';
        $prompt .= "Use integer scores from 1 to 10. ";
        $prompt .= "Keep summary to 2-3 sentences. ";
        $prompt .= "Keep star_summary to one short sentence focused on the candidate's STAR storytelling quality. ";
        $prompt .= "Provide exactly 3 strengths, 3 improvements, and 3 next_steps. ";
        $prompt .= "Transcript:\n" . implode("\n", $transcript);

        $response = $this->generate($prompt);
        $decoded = $this->extractJsonObject($response);

        if (is_array($decoded)) {
            return [
                'overall_score'       => (int) ($decoded['overall_score'] ?? 0),
                'communication_score' => (int) ($decoded['communication_score'] ?? 0),
                'confidence_score'    => (int) ($decoded['confidence_score'] ?? 0),
                'relevance_score'     => (int) ($decoded['relevance_score'] ?? 0),
                'star_average'        => (int) ($decoded['star_average'] ?? 0),
                'star_summary'        => (string) ($decoded['star_summary'] ?? ''),
                'summary'             => (string) ($decoded['summary'] ?? ''),
                'strengths'           => array_values(array_slice((array) ($decoded['strengths'] ?? []), 0, 3)),
                'improvements'        => array_values(array_slice((array) ($decoded['improvements'] ?? []), 0, 3)),
                'next_steps'          => array_values(array_slice((array) ($decoded['next_steps'] ?? []), 0, 3)),
                'raw'                 => $response,
            ];
        }

        return [
            'overall_score'       => 0,
            'communication_score' => 0,
            'confidence_score'    => 0,
            'relevance_score'     => 0,
            'star_average'        => 0,
            'star_summary'        => '',
            'summary'             => is_string($response) ? $response : 'Interview review unavailable right now.',
            'strengths'           => [],
            'improvements'        => [],
            'next_steps'          => [],
            'raw'                 => $response,
        ];
    }

    /**
     * Salary Negotiation Simulator
     */
    public function getSalaryNegotiationResponse($message, $history = [], $offerDetails = '')
    {
        if (empty($this->apiKey)) {
            log_message('error', 'AiService::getSalaryNegotiationResponse - GEMINI_API_KEY is missing; serving context fallback');
            return $this->getFallbackNegotiationReply($message, $offerDetails);
        }

        $url = $this->getApiUrl('chat') . '?key=' . $this->apiKey;

        $systemPrompt = "You are a professional Nigerian hiring manager / HR recruiter in a live salary negotiation simulation. "
            . "Context & Scenario: {$offerDetails}. "
            . "Rules:\n"
            . "1. Respond directly in-character to the candidate's last message as the hiring manager/recruiter.\n"
            . "2. Speak naturally in 1 to 3 concise sentences (max 60 words).\n"
            . "3. If the candidate makes a strong evidence-backed ask with figures, be willing to move closer to their target or offer non-monetary concessions (review timelines, bonus, flexible work).\n"
            . "4. If their ask is unsupported or aggressive, push back politely, citing budget limits and internal equity.\n"
            . "5. Do NOT include markdown formatting like asterisks or quotes around the reply. Speak directly.";

        $contents = [];
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $systemPrompt]]
        ];
        $contents[] = [
            'role'  => 'model',
            'parts' => [['text' => "Understood. I am ready to negotiate as the hiring manager."]]
        ];

        // Append recent history
        $recentHistory = is_array($history) ? array_slice($history, -6) : [];
        foreach ($recentHistory as $chat) {
            $sender = ($chat['sender'] ?? 'user') === 'user' ? 'user' : 'model';
            $msg = (string)($chat['message'] ?? '');
            if ($msg !== '') {
                $contents[] = [
                    'role'  => $sender,
                    'parts' => [['text' => $msg]]
                ];
            }
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => (string)$message]]
        ];

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature'     => 0.6,
                // 1024 (was 250): the old cap was shared with the model's
                // internal "thinking", so visible replies were cut off
                // mid-sentence. Thinking is disabled below so the whole
                // budget goes to the reply (the prompt caps it at 60 words).
                'maxOutputTokens' => 1024,
                'thinkingConfig'  => ['thinkingBudget' => 0],
            ]
        ];

        $payloadJson = json_encode($payload);
        $lastErr = '';
        $maxAttempts = 2;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            $err = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($err) {
                $lastErr = $err;
                log_message('error', "Gemini negotiation request failed (attempt {$attempt}/{$maxAttempts}): {$err}");
                continue;
            }

            if ($httpCode === 400 && isset($payload['generationConfig']['thinkingConfig'])) {
                // This model rejected the thinking config: drop it and retry
                // with the plain payload rather than failing the turn.
                unset($payload['generationConfig']['thinkingConfig']);
                $payloadJson = json_encode($payload);
                $lastErr = 'HTTP 400 (thinkingConfig rejected; retrying without it)';
                log_message('error', "Gemini negotiation API error (HTTP 400, attempt {$attempt}/{$maxAttempts}): thinkingConfig rejected, retrying without it");
                continue;
            }

            if ($httpCode !== 200) {
                $lastErr = 'HTTP ' . $httpCode;
                log_message('error', "Gemini negotiation API error (HTTP {$httpCode}, attempt {$attempt}/{$maxAttempts}): " . substr((string) $response, 0, 500));
                continue;
            }

            $result = json_decode($response, true);
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $finish = $result['candidates'][0]['finishReason'] ?? '';

            if (trim((string) $text) === '') {
                $lastErr = 'empty candidate text';
                log_message('error', "Gemini negotiation returned no text (attempt {$attempt}/{$maxAttempts}, finishReason: {$finish}): " . substr((string) $response, 0, 500));
                continue;
            }

            if ($finish === 'MAX_TOKENS') {
                // Never serve a reply that was cut off mid-sentence.
                $lastErr = 'reply truncated (MAX_TOKENS)';
                log_message('error', "Gemini negotiation reply truncated at MAX_TOKENS (attempt {$attempt}/{$maxAttempts}): " . substr((string) $text, 0, 200));
                continue;
            }

            $reply = trim((string) $text);
            return strip_tags(str_replace(['**', '*'], '', $reply));
        }

        log_message('error', 'AiService::getSalaryNegotiationResponse - all attempts failed (' . $lastErr . '); serving context fallback');
        return $this->getFallbackNegotiationReply($message, $offerDetails);
    }

    private function getFallbackNegotiationReply($message, $offerDetails)
    {
        // Context-aware fallback: reference the figures actually in play so
        // the reply stays on the conversation even when the AI is unreachable.
        $offer = null;
        $target = null;
        if (preg_match('/Offer:\\s*(?:\\x{20A6})?\\s*([\\d,]+)/iu', (string) $offerDetails, $m)) {
            $offer = (int) str_replace(',', '', $m[1]);
        }
        if (preg_match('/Target:\\s*(?:\\x{20A6})?\\s*([\\d,]+)/iu', (string) $offerDetails, $m)) {
            $target = (int) str_replace(',', '', $m[1]);
        }
        $fmt = static function ($n) {
            return "\u{20A6}" . number_format($n);
        };

        $ask = null;
        if (preg_match('/\\x{20A6}\\s*([\\d,]+)/u', (string) $message, $m)) {
            $ask = (int) str_replace(',', '', $m[1]);
        } elseif (preg_match('/\\b(\\d{2,3})\\s*k\\b/i', (string) $message, $m)) {
            $ask = ((int) $m[1]) * 1000;
        }

        if ($ask && $offer && $ask > $offer) {
            $mid = (int) (round((($offer + $ask) / 2) / 1000) * 1000);
            return "I hear you on " . $fmt($ask) . ". That is above the " . $fmt($offer) . " we opened with for this role, but I can meet you partway at " . $fmt($mid) . " if we agree on clear deliverables for the first quarter. Can you work with that?";
        }
        if ($offer && $target && $target > $offer) {
            $mid = (int) (round((($offer + $target) / 2) / 1000) * 1000);
            return "I understand where you are coming from. Our opening for this role is " . $fmt($offer) . "; the most I can stretch today is " . $fmt($mid) . ", with a performance review at six months to revisit the rest. What would make that work for you?";
        }
        if ($offer) {
            return "I hear your point. The band for this role starts at " . $fmt($offer) . ", but there is flexibility on the review timeline, the bonus structure and health coverage. Which of those matters most to you?";
        }

        $responses = [
            "I hear your point regarding the scope of the role. While our base band is firm at this level, could we look at a 6-month performance review to adjust your compensation?",
            "That's a reasonable ask given your background. The most I can stretch the base is halfway towards your target, provided we align on immediate quarterly deliverables.",
            "I understand where you're coming from. We have strict internal pay bands for this grade, but we can offer flexibility on the signing bonus and health coverage.",
            "I appreciate you sharing those benchmarks. If you're willing to commit to leading the upcoming transition, I can defend a 10% increase to leadership."
        ];
        return $responses[array_rand($responses)];
    }

    /**
     * Personalized Career Advice
     */
    public function getCareerAdvice($candidateProfile)
    {
        $prompt = "Act as a senior career coach. Based on this candidate profile: '{$candidateProfile}', provide 3-5 personalized career growth tips, recommended skills to learn, and potential career paths.";
        
        return $this->generate($prompt, 'balanced');
    }

    /**
     * Generate a tailored cover letter
     */
    public function generateCoverLetter(array $params): string
    {
        $jobTitle = $params['job_title'] ?? 'the position';
        $companyName = $params['company_name'] ?? 'your company';
        $jobDescription = $params['job_description'] ?? '';
        $candidateName = $params['candidate_name'] ?? 'the candidate';
        $candidateSkills = $params['candidate_skills'] ?? '';
        $candidateExperience = $params['candidate_experience'] ?? '';
        $candidateEducation = $params['candidate_education'] ?? '';

        $prompt = "Write a professional, compelling cover letter for {$candidateName} applying for the '{$jobTitle}' position at {$companyName}.\n\n";
        $prompt .= "Candidate Skills: {$candidateSkills}\n";
        $prompt .= "Candidate Experience: {$candidateExperience}\n";
        $prompt .= "Candidate Education: {$candidateEducation}\n";
        if ($jobDescription) {
            $prompt .= "Job Description: {$jobDescription}\n";
        }
        $prompt .= "\nRequirements:\n";
        $prompt .= "- Keep it to 3-4 paragraphs\n";
        $prompt .= "- Highlight relevant skills and experience that match the job\n";
        $prompt .= "- Be professional but enthusiastic\n";
        $prompt .= "- Include a strong opening and closing\n";
        $prompt .= "- Do NOT include placeholders or bracketed text\n";
        $prompt .= "- Use the candidate's actual name in the greeting";

        return $this->generate($prompt, 'balanced');
    }

    /**
     * Generate a comprehensive CV review using AI
     */
    public function generateCvReview(array $params): string
    {
        $fullName       = $params['full_name'] ?? 'Candidate';
        $targetRole     = $params['target_role'] ?? '';
        $industry       = $params['industry'] ?? '';
        $feedbackRequest = $params['feedback_request'] ?? '';
        $cvContent      = $params['cv_content'] ?? '';
        $plan           = $params['plan'] ?? 'basic';

        $detailLevel = $plan === 'premium' ? 'very detailed, line-by-line' : ($plan === 'professional' ? 'detailed' : 'general');

        $prompt = "You are an expert professional CV reviewer and career coach. Review the following CV for {$fullName}.\n\n";
        $prompt .= "Target Role: {$targetRole}\n";
        $prompt .= "Industry: {$industry}\n";
        $prompt .= "Specific Feedback Request: {$feedbackRequest}\n";
        $prompt .= "Plan Level: {$plan} ({$detailLevel} review)\n\n";
        $prompt .= "CV Content:\n{$cvContent}\n\n";

        $prompt .= "Provide a {$detailLevel} structured review covering:\n";
        $prompt .= "1. **Overall Assessment** (2-3 sentence summary of the CV's effectiveness)\n";
        $prompt .= "2. **Strengths** (bullet points of what works well)\n";
        $prompt .= "3. **Areas for Improvement** (specific, actionable suggestions)\n";
        $prompt .= "4. **Format & Design** (layout, length, readability feedback)\n";
        $prompt .= "5. **Content Analysis** (experience descriptions, achievements, keywords)\n";

        if ($plan === 'premium') {
            $prompt .= "6. **ATS Compatibility** (keyword optimization, formatting for applicant tracking systems)\n";
            $prompt .= "7. **Rewritten Sections** (rewrite 2-3 weak bullet points with stronger language)\n";
            $prompt .= "8. **LinkedIn Profile Tips** (suggestions for aligning LinkedIn with this CV)\n";
            $prompt .= "9. **Cover Letter Tips** (key points to highlight in a cover letter for {$targetRole})\n";
        }

        if ($plan === 'professional') {
            $prompt .= "6. **ATS Compatibility** (keyword optimization suggestions)\n";
        }

        $prompt .= "\nWrite in a professional, encouraging tone. Be honest but constructive. Format with clear markdown headings and bullet points.";

        $result = $this->generate($prompt, 'reasoning');

        if (empty($result) || str_starts_with($result, 'AI Error') || str_starts_with($result, 'AI Service is not configured')) {
            $result = $this->handleCvReviewFallback($params);
        }

        return $result;
    }

    /**
     * Fallback CV review when API is unavailable
     */
    protected function handleCvReviewFallback(array $params): string
    {
        $name = $params['full_name'] ?? 'Candidate';
        $role = $params['target_role'] ?? 'your target role';
        $plan = $params['plan'] ?? 'basic';

        $review = "## CV Review for {$name}\n\n";
        $review .= "### Overall Assessment\n\n";
        $review .= "Thank you for submitting your CV for review. Our AI review service is currently being enhanced with additional capabilities. ";
        $review .= "Below is a preliminary assessment based on best practices for {$role} roles.\n\n";
        $review .= "### Key Recommendations\n\n";
        $review .= "- **Tailor your CV** specifically for {$role} — highlight relevant experience and skills first\n";
        $review .= "- **Quantify achievements** — use numbers, percentages, and concrete results\n";
        $review .= "- **Use strong action verbs** — led, developed, implemented, achieved, optimized\n";
        $review .= "- **Keep it concise** — 1-2 pages maximum for most roles\n";
        $review .= "- **Check ATS keywords** — include industry-standard terms from the job description\n\n";

        if (in_array($plan, ['professional', 'premium'], true)) {
            $review .= "### Premium Insights\n\n";
            $review .= "As a {$plan} plan user, you'll receive a detailed line-by-line review once our team completes the full assessment. ";
            $review .= "This preliminary AI review provides initial guidance.\n\n";
            $review .= "### Next Steps\n\n";
            $review .= "Our professional CV reviewers will provide additional personalized feedback within the stated turnaround time. ";
            $review .= "You'll be notified when the full review is ready.\n";
        } else {
            $review .= "### Next Steps\n\n";
            $review .= "Review the suggestions above and revise your CV accordingly. ";
            $review .= "For a more comprehensive review, consider upgrading to our Professional or Premium plan.\n";
        }

        return $review;
    }

    /**
     * Get a chat response with history and context
     */
    public function getChatResponse($message, $history = [], $context = '')
    {
        if (empty($this->apiKey)) {
            return $this->handleChatFallback($message, $history, $context, "AI Service is not configured. Please add GEMINI_API_KEY to your .env file.");
        }

        $url = $this->getApiUrl('chat') . '?key=' . $this->apiKey;

        // Construct contents with history
        $contents = [];
        
        // System instruction as first message
        // Clear system instruction: reply naturally as an expert career advisor/coach.
        // Follow the 4-step coaching framework: 1. Explain Issue, 2. Recommend Solution, 3. Show Proposed Improvement, 4. Invite candidate to apply using 'Apply Suggestion'.
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => "You are ResumeAI, an expert career coach and resume consultant. Context: " . $context . ". CRITICAL RULE: You must act strictly as an adviser/coach. Never assume changes should be auto-applied. When analyzing or improving content: 1. Explain the issue (why it is weak/problematic). 2. Make a concrete recommendation. 3. Show the proposed improvement clearly. 4. Allow the candidate to decide by prompting them to click 'Apply Suggestion'. Use clean HTML formatting (p, strong, em, ul, ol, li, blockquote, div) but avoid style attributes or scripts. Return only the coaching advice and proposed improvements to be displayed." ]]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => "Understood. I will act as an advisor and career coach following the 4-step framework (Explain Issue, Recommend, Show Proposed Improvement, and Invite Candidate to Apply Suggestion)."]]
        ];

        // Add last 4 history turns for speed & context balance
        $recentHistory = array_slice($history, -4);
        foreach ($recentHistory as $chat) {
            $contents[] = [
                'role' => ($chat['sender'] === 'user') ? 'user' : 'model',
                'parts' => [['text' => $chat['message']]]
            ];
        }

        // Add current message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.5,
                'maxOutputTokens' => 1024,
            ]
        ];

        $payloadJson = json_encode($payload);
        $lastErr = '';
        $maxAttempts = 3;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            $errno = curl_errno($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (! $err && $errno === 0 && $httpCode < 500) {
                $result = json_decode($response, true);

                if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    $raw = $result['candidates'][0]['content']['parts'][0]['text'];

                    // Sanitize via DOM to allow a controlled set of elements and attributes (images, tables)
                    return $this->sanitizeHtml($raw);
                }

                if (isset($result['error'])) {
                    $lastErr = $result['error']['message'] ?? 'Unknown API error';
                    log_message('error', "Gemini chat API error (HTTP {$httpCode}, attempt {$attempt}/{$maxAttempts}): {$lastErr}");
                    // Non-retryable API-level error (bad request, invalid key, etc.) — stop retrying.
                    break;
                }

                $lastErr = 'Unrecognized API response shape';
                log_message('error', "Gemini chat API returned no candidates (HTTP {$httpCode}, attempt {$attempt}/{$maxAttempts}): " . substr((string) $response, 0, 500));
                break;
            }

            $lastErr = $err ?: ("HTTP {$httpCode}" . ($errno ? ", cURL error code {$errno}" : ''));
            log_message('error', "Gemini chat request failed (attempt {$attempt}/{$maxAttempts}): {$lastErr}");

            if ($attempt < $maxAttempts) {
                usleep(300000 * $attempt); // 300ms, 600ms backoff before retrying
            }
        }

        return $this->handleChatFallback($message, $history, $context, $lastErr);
    }

    /**
     * Convert text to speech with Microsoft Edge TTS Nigerian voices only
     * (owner decision 2026-11-19: no Gemini TTS). Female personas use
     * en-NG-EzinneNeural, male personas en-NG-AbeoNeural, via
     * EdgeTtsService. Returns a base64-encoded MP3 string, or null when
     * synthesis is unavailable (callers fall back to browser speech).
     *
     * $voiceName accepts an Edge voice name (en-NG-EzinneNeural /
     * en-NG-AbeoNeural), an Edge persona key (corporate-hr, ...), or a
     * legacy Gemini voice name (Kore = female, Puck = male), which is
     * mapped to the matching Nigerian voice.
     */
    public function textToSpeech(string $text, string $voiceName = 'en-NG-EzinneNeural'): ?string
    {
        $text = trim($text);
        if ($text === '') {
            return null;
        }

        $persona = $this->edgePersonaForVoice($voiceName);

        try {
            $edgeTts = new EdgeTtsService();
            $mp3Data = $edgeTts->synthesize($text, $persona);
            if ($mp3Data !== null && strlen($mp3Data) > 200) {
                return base64_encode($mp3Data);
            }
            log_message('error', 'AiService::textToSpeech EdgeTTS returned no audio (persona: ' . $persona . ')');
        } catch (\Throwable $e) {
            log_message('error', 'AiService::textToSpeech EdgeTTS failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Map a requested voice to an Edge TTS persona (Nigerian voices).
     */
    protected function edgePersonaForVoice(string $voiceName): string
    {
        $v = strtolower(trim($voiceName));

        $personas = ['corporate-hr', 'banking-recruiter', 'big4-partner', 'startup-founder', 'technical-lead', 'gov-recruiter'];
        if (in_array($v, $personas, true)) {
            return $v;
        }
        if (str_contains($v, 'abeo') || in_array($v, ['puck', 'charon', 'fenrir', 'orus', 'male'], true)) {
            return 'technical-lead'; // en-NG-AbeoNeural (male)
        }
        return 'corporate-hr'; // en-NG-EzinneNeural (female)
    }


    /**
     * Sanitize HTML allowing only a strict set of tags and safe attributes.
     * This is a conservative sanitizer using DOMDocument.
     * Allowed tags: p, br, strong, em, ul, ol, li, h3, h4, div, span, table, thead, tbody, tr, th, td, img
     * Allowed attributes on img: src (must be data: or http(s) and match whitelist), alt, width, height
     * No inline styles, no event handlers, no other attributes allowed.
     */
    protected function sanitizeHtml(string $html): string
    {
        // Remove script/style blocks first
        $html = preg_replace('#<script[^>]*>.*?</script>#is', '', $html);
        $html = preg_replace('#<style[^>]*>.*?</style>#is', '', $html);

        // Load into DOMDocument
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        // Ensure there is a wrapper element
        $htmlWrapped = '<div>' . $html . '</div>';
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $htmlWrapped);
        $body = $dom->getElementsByTagName('body')->item(0);

        // Only allow a minimal set of tags. We will also strip all attributes except img[src|alt|width|height].
        $allowedTags = ['p','br','strong','em','ul','ol','li','h3','h4','div','span','table','thead','tbody','tr','th','td','img'];

        // Walk recursively and sanitize nodes
        $this->sanitizeNodeChildren($dom, $body, $allowedTags);

        // Extract innerHTML of wrapper div
        $wrapper = $body->firstChild;
        $inner = '';
        if ($wrapper) {
            foreach ($wrapper->childNodes as $child) {
                $inner .= $dom->saveHTML($child);
            }
        }

        // Final pass: rewrite img src to proxied local URLs using DB lookups (async queue).
        // Only rewrite if a proxied image already exists; otherwise enqueue and keep original URL.
        try {
            $docForRewrite = new \DOMDocument();
            libxml_use_internal_errors(true);
            $docForRewrite->loadHTML(mb_convert_encoding('<div>' . $inner . '</div>', 'HTML-ENTITIES', 'UTF-8'));
            $imgs = $docForRewrite->getElementsByTagName('img');
            $imgModel = null;
            foreach ($imgs as $img) {
                $src = $img->getAttribute('src');
                if ($src && preg_match('#^https?://#i', $src)) {
                    if (stripos($src, 'https://') !== 0) {
                        $img->removeAttribute('src');
                        continue;
                    }

                    // Check DB for existing proxied image
                    if ($imgModel === null) {
                        $imgModel = new AiImageModel();
                    }
                    $existing = $imgModel->findByOriginUrl($src);
                    if ($existing && $existing->status === 'completed' && $existing->proxied_path) {
                        $img->setAttribute('src', base_url($existing->proxied_path));
                    } elseif (!$existing) {
                        // Enqueue for async processing
                        $imgModel->insert([
                            'origin_url' => $src,
                            'status' => 'pending',
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                    // If existing but not yet completed, keep original URL
                }
            }

            // Extract rewritten inner HTML
            $body = $docForRewrite->getElementsByTagName('body')->item(0);
            $wrapper = $body?->firstChild;
            $rewritten = '';
            if ($wrapper) {
                foreach ($wrapper->childNodes as $child) {
                    $rewritten .= $docForRewrite->saveHTML($child);
                }
            }

            return trim($rewritten ?: $inner);
        } catch (\Throwable $e) {
            return trim($inner);
        }
    }

    protected function sanitizeNodeChildren(\DOMDocument $dom, \DOMNode $node, array $allowedTags)
    {
        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $child = $node->childNodes->item($i);
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $tag = strtolower($child->nodeName);
                if (!in_array($tag, $allowedTags, true)) {
                    // Replace node with its text content or children
                    // Move children up one level
                    while ($child->hasChildNodes()) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);
                    continue;
                }

                // Sanitize attributes: allow no attributes except img[src,alt,width,height].
                if ($child->hasAttributes()) {
                    foreach (iterator_to_array($child->attributes) as $attr) {
                        $name = strtolower($attr->name);
                        $value = $attr->value;

                        if ($tag === 'img') {
                            if (!in_array($name, ['src','alt','width','height'], true)) {
                                $child->removeAttribute($name);
                                continue;
                            }
                            if ($name === 'src') {
                                // Allow only data: URIs or https URLs (no http to avoid mixed content)
                                if (!preg_match('#^(data:image/|https://)#i', $value)) {
                                    $child->removeAttribute('src');
                                }
                            }
                            continue;
                        }

                        // Remove any other attribute on non-img tags
                        $child->removeAttribute($name);
                    }
                }

                // Recurse into children
                $this->sanitizeNodeChildren($dom, $child, $allowedTags);
            } elseif ($child->nodeType === XML_COMMENT_NODE) {
                // remove comments
                $node->removeChild($child);
            } elseif ($child->nodeType === XML_TEXT_NODE) {
                // leave text nodes as-is
            } else {
                // remove other node types
                $node->removeChild($child);
            }
        }
    }

    protected function getQuestionPackContext(string $questionPack): string
    {
        $packs = [
            'general' => 'General professional interview questions suitable for most job roles',
            'engineering' => 'Technical and delivery-focused questions for software, data, and engineering roles',
            'product' => 'Product thinking, prioritization, stakeholder, and execution questions',
            'sales' => 'Prospecting, objection handling, targets, and customer relationship questions',
            'marketing' => 'Campaign planning, analytics, brand, and growth questions',
            'support' => 'Customer support, service recovery, communication, and empathy questions',
            'operations' => 'Process improvement, coordination, ownership, and execution questions',
            
            // Newly matched from mockup
            'software-developer' => 'Technical, coding, system design, and architecture questions for software engineering roles',
            'data-analysis' => 'Data analysis, statistics, SQL, data modeling, and reporting questions',
            'accounting-fundamentals' => 'Accounting, finance, audit, bookkeeping, and budgeting questions',
            'digital-marketing' => 'SEO, SEM, digital marketing strategies, campaigns, and growth metrics',
            'social-media-content' => 'Social media, content creation, brand strategy, and community engagement questions',
            'office-admin' => 'Office administration, scheduling, operations, and support questions',
            'sales-business-dev' => 'Sales, prospecting, objection handling, closing, and business development questions',
            'customer-service' => 'Customer support, empathy, conflict resolution, and communication questions',
            'human-resources' => 'HR, hiring, conflict resolution, training, and employee engagement questions',
            'engineering-technical' => 'Civil, mechanical, electrical engineering, or site/field technical questions',
            'logistics-supply-chain' => 'Logistics, supply chain, procurement, and warehouse operations questions',
            'legal-compliance' => 'Legal compliance, corporate governance, contract law, and regulatory questions',
            'healthcare-medical' => 'Medical care, patient safety, clinical practice, and healthcare compliance questions',
            'education-training' => 'Pedagogy, classroom management, training development, and instructional design questions',
            'hospitality' => 'Guest relations, hospitality operations, service recovery, and hotel/restaurant service questions',
            'manufacturing-production' => 'Manufacturing line management, safety standards, quality control, and production scheduling',
            'it-support' => 'IT helpdesk, troubleshooting, system admin, and user support questions',
            'project-management' => 'Project management, product thinking, Scrum, Agile prioritization, and execution questions',
            'design-ux' => 'UX/UI design, design thinking, prototyping, and graphic design questions',
        ];

        return $packs[$questionPack] ?? $packs['general'];
    }

    /**
     * Extract a JSON object from a model response.
     *
     * @return array<string, mixed>|null
     */
    protected function extractJsonObject(string $response): ?array
    {
        $trimmed = trim($response);
        $decoded = json_decode($trimmed, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{[\s\S]*\}/', $trimmed, $matches) !== 1) {
            return null;
        }

        $decoded = json_decode($matches[0], true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Handle fallback responses for generate() when offline or API fails
     */
    protected function handleGenerateFallback($prompt, $err)
    {
        // Preserve the provider/network reason in server logs. The previous
        // catch-all labelled every failure as "offline", hiding invalid keys,
        // API restrictions, quota errors, and unsupported models.
        log_message('error', 'Gemini generation failed: ' . (string) $err);

        // 1a. Improve Existing Professional Summary Fallback
        if (preg_match('/Candidate\'s existing summary:\s*(.*)/is', $prompt, $matches)) {
            $existingSummary = trim($matches[1]);
            if (preg_match('/^(.*?)(?:\nSkills Context:|\Z)/is', $existingSummary, $sumMatches)) {
                $existingSummary = trim($sumMatches[1]);
            }
            if (!empty($existingSummary)) {
                return $this->formatStrengthenedSummaryFallback($existingSummary, $prompt);
            }
        }

        // 1b. Improve Description & Bullets Fallback
        if (stripos($prompt, 'Candidate\'s existing content:') !== false
            || stripos($prompt, 'entered work experience or achievement description') !== false
            || stripos($prompt, 'Improve the following work experience description') !== false
            || stripos($prompt, 'Convert the candidate\'s existing information into') !== false
            || stripos($prompt, 'resume bullet points') !== false
            || stripos($prompt, 'Improve the following job description') !== false
            || stripos($prompt, 'Improve the following') !== false) {

            $rawDesc = '';
            if (preg_match('/Candidate\'s existing content:\s*(.*)/is', $prompt, $matches)) {
                $rawDesc = trim($matches[1]);
            } else {
                $rawDesc = trim(substr($prompt, strripos($prompt, "\n\n") + 2));
            }

            $jobTitle = '';
            if (preg_match('/Job Title Context:\s*(.*)/i', $prompt, $matches)) {
                $jobTitle = trim(explode("\n", $matches[1])[0]);
            }

            $isBullets = (stripos($prompt, 'bullet points') !== false || stripos($prompt, 'Convert the candidate\'s existing information') !== false);

            if ($isBullets) {
                return $this->formatBulletsFallback($rawDesc, $jobTitle);
            }

            return $this->formatStrengthenedDescriptionFallback($rawDesc, $jobTitle);
        }

        // 1c. Professional Summary Fallback (New Generation)
        if (stripos($prompt, 'professional resume summary') !== false || stripos($prompt, 'ResumeAI') !== false || (stripos($prompt, 'Experiences:') !== false && stripos($prompt, 'Skills:') !== false)) {
            // Extract experiences and skills
            $experiencesStr = '';
            $skillsStr = '';
            if (preg_match('/^(?:Experiences|Work Experience \/ Positions):[ \t]*([^\r\n]*)/m', $prompt, $matches)) {
                $experiencesStr = trim($matches[1]);
            }
            if (preg_match('/^(?:Skills|Skills \/ Competencies):[ \t]*([^\r\n]*)/m', $prompt, $matches)) {
                $skillsStr = trim($matches[1]);
            }
            $summary = $experiencesStr !== '' ? 'Experienced ' . $experiencesStr . '.' : '';
            if ($skillsStr !== '') {
                $summary .= ($summary !== '' ? ' ' : '') . 'Key proficiencies include ' . $skillsStr . '.';
            }
            return $summary ?: 'Professional dedicated to delivering high quality technical solutions and measurable outcomes.';
        }

        // 2. Career Advice Fallback
        if (stripos($prompt, 'career coach') !== false || stripos($prompt, 'career advice') !== false || stripos($prompt, 'career growth') !== false) {
            // Extract name, skills, bio from prompt
            $name = 'Candidate';
            $skills = 'General Skills';
            $bio = '';

            if (preg_match('/Name:\s*([^,]+)/i', $prompt, $matches)) {
                $name = trim($matches[1]);
            }
            if (preg_match('/Skills:\s*([^,]+)/i', $prompt, $matches)) {
                $skills = trim($matches[1]);
            }
            if (preg_match('/Bio:\s*(.*)/i', $prompt, $matches)) {
                $bio = trim($matches[1]);
            }

            $skillsLower = strtolower($skills . ' ' . $bio);

            // Determine highly tailored offline response based on skills
            if (preg_match('/(php|codeigniter|laravel|symfony|wordpress|drupal|yii)/i', $skillsLower)) {
                return "Personalized Career Growth for {$name}\n\n" .
                       "As a PHP and Backend Web Development professional, you are positioned in a resilient market. To move from mid-level to senior or lead roles, focus on the following growth areas:\n\n" .
                       "Core Strategic Growth Tips:\n" .
                        "1. Master modern PHP ecosystems: deepen PHP 8.x knowledge and advanced design patterns in frameworks like Laravel or CodeIgniter.\n" .
                        "2. Bridge the frontend gap: learn a modern frontend framework such as Vue.js or React to gain full-stack capability.\n" .
                        "3. Database performance tuning: study indexing, query profiling, caching (Redis/Memcached), and schema optimization.\n\n" .
                        "Advanced skills to learn:\n" .
                        "- Microservices & API design (REST, gRPC) and message brokers (RabbitMQ, Kafka).\n" .
                        "- DevOps & Cloud (Docker, CI/CD, AWS/GCP).\n" .
                        "- Testing & quality tools (PHPUnit, PHPStan, Psalm).\n\n" .
                        "Recommended career paths:\n" .
                        "- Senior Full-Stack Engineer\n" .
                        "- Backend Solutions Architect\n" .
                        "- Technical Lead";
            } elseif (preg_match('/(javascript|js|react|vue|angular|node|next\.js|nextjs|typescript|svelte|frontend|html|css)/i', $skillsLower)) {
                return "Personalized Career Growth for {$name}\n\n" .
                       "As a JavaScript/TypeScript and frontend specialist, your skills are in high demand. To stand out as a top interface engineer, consider the following:\n\n" .
                        "Core strategic growth tips:\n" .
                        "1. Deepen core JS/TS knowledge: master async patterns, memory management, and strict TypeScript types.\n" .
                        "2. Adopt modern rendering architectures: learn SSR, SSG, and incremental hydration with Next.js or Nuxt.\n" .
                        "3. Focus on UX performance and web vitals optimization.\n\n" .
                        "Advanced skills to learn:\n" .
                        "- State management and data fetching patterns (Redux Toolkit, Zustand, TanStack Query).\n" .
                        "- Build tools and bundlers (Vite, Webpack) and monorepo approaches.\n" .
                        "- Testing suites (Playwright, Cypress, Vitest/Jest).\n\n" .
                        "Recommended career paths:\n" .
                        "- Senior Frontend Architect\n" .
                        "- Full-Stack Developer\n" .
                        "- Product Engineer";
            } elseif (preg_match('/(python|django|flask|fastapi|machine learning|ml|ai|data science|nlp|deep learning|pandas|numpy|tensorflow)/i', $skillsLower)) {
                return "Personalized Career Growth for {$name}\n\n" .
                       "As a Python and intelligent systems professional, you are well-positioned for leadership in AI and data engineering. Focus on these areas:\n\n" .
                        "Core strategic growth tips:\n" .
                        "1. Scale computational pipelines: move from notebooks to distributed systems (PySpark, Dask, Ray).\n" .
                        "2. Productionize ML models: build MLOps pipelines (MLflow, Kubeflow, BentoML).\n" .
                        "3. Solidify software engineering best practices: modular code, type hints, async, and testing.\n\n" .
                        "Advanced skills to learn:\n" .
                        "- APIs and backend systems (FastAPI, Django REST, GraphQL).\n" .
                        "- Cloud and databases (SageMaker, Snowflake, BigQuery, vector DBs).\n" .
                        "- Containerization and orchestration (Docker, Kubernetes).\n\n" .
                        "Recommended career paths:\n" .
                        "- AI / Machine Learning Engineer\n" .
                        "- Senior Data Architect\n" .
                        "- Principal Software Engineer (Data Systems)";
            } elseif (preg_match('/(java|spring|spring boot|hibernate|maven|gradle)/i', $skillsLower)) {
                return "### 🚀 Personalized Career Growth for {$name}\n\n" .
                       "As an **Enterprise Java & Systems Engineer**, you hold a foundation in building highly scalable, reliable, and secure corporate systems. To transition into principal or architectural engineering roles, adopt the following milestones:\n\n" .
                       "### 💡 Core Strategic Growth Tips\n" .
                       "1. **Deconstruct Monoliths to Microservices**: Master microservice design, container orchestration, service discovery, API gateways, and event-driven patterns in Spring Cloud.\n" .
                       "2. **Deepen Performance Engineering**: Learn JVM internals, garbage collection tuning, thread safety, profiling tools (JProfiler, VisualVM), and asynchronous reactive architectures (Spring WebFlux).\n" .
                       "3. **Emphasize Enterprise Security**: Focus on modern OAuth2, OIDC, JWT authentication patterns, and secure coding practices.\n\n" .
                       "### 📚 Advanced Skills to Learn\n" .
                       "* **Distributed Systems**: Kafka/RabbitMQ, Redis Distributed Caching, and Cassandra/DynamoDB databases.\n" .
                       "* **Infrastructure as Code**: Terraform, Ansible, and Kubernetes orchestration.\n" .
                       "* **Modern Languages**: Expand into Kotlin or Go to complement your backend systems expertise.\n\n" .
                       "### 🛣️ Recommended Career Paths\n" .
                       "* **Enterprise Solutions Architect**: Designing massive backend microservice networks and data layers.\n" .
                       "* **Lead Systems Engineer**: Directing high-availability infrastructure projects and platform engineering.\n" .
                       "* **Technical Manager**: Bridging software delivery goals with team management and project planning.";
            } elseif (preg_match('/(design|ui|ux|figma|adobe|photoshop|illustrator|graphic|sketch|wireframe|mockup)/i', $skillsLower)) {
                return "### 🚀 Personalized Career Growth for {$name}\n\n" .
                       "As a **UI/UX and Product Design professional**, you possess the vital skill of translating complex product objectives into delightful user experiences. To step into design leadership and senior product design, prioritize these areas:\n\n" .
                       "### 💡 Core Strategic Growth Tips\n" .
                       "1. **Champion Data-Driven Design**: Base your decisions on quantitative analytics (Hotjar, Google Analytics) and rigorous user research rather than subjective aesthetic trends.\n" .
                       "2. **Master Design Systems**: Architect cohesive, highly accessible, scalable component libraries in Figma that sync flawlessly with frontend frameworks.\n" .
                       "3. **Learn Interaction & Prototyping**: Enhance your utility by building advanced micro-interactions and realistic, high-fidelity prototypes.\n\n" .
                       "### 📚 Advanced Skills to Learn\n" .
                       "* **User Research Methodologies**: Usability testing, user journey mapping, persona modeling, and cognitive walkthroughs.\n" .
                       "* **Basic Frontend Literacy**: Understanding HTML, CSS, and component state logic (helps in collaborating with engineers).\n" .
                       "* **Product Strategy**: Alignment of design metrics with core business KPIs and conversion optimizations.\n\n" .
                       "### 🛣️ Recommended Career Paths\n" .
                       "* **Lead Product Designer**: Driving user experience strategy for flagship business applications.\n" .
                       "* **Design Systems Lead**: Standardizing interface patterns, accessibility guidelines, and components.\n" .
                       "* **Creative Director / UX Manager**: Mentoring design talent and steering product design direction.";
            } else {
                // Universal default career advice
                return "### 🚀 Personalized Career Growth for {$name}\n\n" .
                       "As a **Professional in the tech and business ecosystem**, you hold highly versatile competencies. To stand out and accelerate your trajectory to executive and expert-level leadership, implement the following roadmap:\n\n" .
                       "### 💡 Core Strategic Growth Tips\n" .
                       "1. **Develop a T-Shaped Skill Profile**: Maintain broad competency in cross-functional business domains (product, marketing, communication) while cultivating extreme mastery in your core technical area.\n" .
                       "2. **Quantify Your Accomplishments**: Focus your work output around measurable business metrics (revenue generated, costs optimized, time saved) to easily demonstrate impact.\n" .
                       "3. **Cultivate Mentorship & Leadership**: Proactively volunteer to guide junior professionals, speak at events, or write technical articles to build your industry authority.\n\n" .
                       "### 📚 Advanced Skills to Learn\n" .
                       "* **Agile & Delivery Methodologies**: Scrum Master, Kanban, or Product Owner methodologies.\n" .
                       "* **Data Analysis**: Fundamental SQL, data visualization (Tableau, PowerBI), or data-driven decision making.\n" .
                       "* **Public Speaking & Negotiating**: Master the art of presenting complex ideas to executive stakeholders.\n\n" .
                       "### 🛣️ Recommended Career Paths\n" .
                       "* **Senior Domain Expert**: Transitioning into specialized high-value consulting or engineering positions.\n" .
                       "* **Technical Project Manager / Scrum Master**: Orchestrating product delivery and guiding cross-functional teams.\n" .
                       "* **Engineering Manager / Team Lead**: Directing technical execution while managing and coaching professionals.";
            }
        }

        // 3. Cover Letter Fallback
        if (stripos($prompt, 'cover letter') !== false) {
            $jobTitle = 'the position';
            $companyName = 'your company';
            $candidateName = 'Candidate';
            $skills = 'my core skills';
            $experience = 'my professional experience';

            if (preg_match('/applying for the \'(.*?)\'/i', $prompt, $matches)) {
                $jobTitle = $matches[1];
            }
            if (preg_match('/at (.*?)\n/i', $prompt, $matches)) {
                $companyName = trim($matches[1]);
            }
            if (preg_match('/Candidate Skills:\s*(.*)/i', $prompt, $matches)) {
                $skills = trim(explode("\n", $matches[1])[0]);
            }
            if (preg_match('/Candidate Experience:\s*(.*)/i', $prompt, $matches)) {
                $experience = trim(explode("\n", $matches[1])[0]);
            }
            if (preg_match('/Write a professional, compelling cover letter for (.*?)\s+applying/i', $prompt, $matches)) {
                $candidateName = trim($matches[1]);
            }

            return "Dear Hiring Manager,\n\n" .
                   "I am writing to express my enthusiastic interest in the **{$jobTitle}** position at **{$companyName}**. With a strong engineering foundation, a dedicated skill set in **{$skills}**, and robust background in **{$experience}**, I am fully prepared to make a positive, immediate contribution to your engineering team.\n\n" .
                   "Throughout my career, I have focused on designing scalable system architectures, optimizing application performance, and working collaboratively across cross-functional departments. I am highly inspired by {$companyName}'s standing in the market and your commitment to deploying state-of-the-art technological solutions. I would welcome the opportunity to apply my skills to help accelerate your current software delivery milestones.\n\n" .
                   "Thank you for your time, consideration, and review of my application. I look forward to discussing how my background, technical expertise, and career aspirations align with the strategic goals of your team.\n\n" .
                   "Sincerely,\n" .
                   "**{$candidateName}**";
        }

        // 5. Mock Interview Evaluation Fallback
        if (stripos($prompt, 'senior interview coach reviewing a completed mock interview') !== false || stripos($prompt, 'overall_score') !== false) {
            return json_encode([
                "overall_score" => 8,
                "communication_score" => 8,
                "confidence_score" => 7,
                "relevance_score" => 8,
                "star_average" => 7,
                "star_summary" => "Demonstrated solid structure, but could emphasize measurable results more.",
                "summary" => "You've shown strong technical domain knowledge and logical problem-solving abilities. Focus on polishing the 'Result' phase of your STAR stories by quantifying your accomplishments.",
                "strengths" => [
                    "Clear and logical explanations of complex architectures",
                    "Strong alignment of skills with the core job requirements",
                    "Professional and structured communication style"
                ],
                "improvements" => [
                    "Include more quantifiable metrics (e.g., percentages, revenue, time saved)",
                    "Elaborate more on the 'Task' and constraints you faced",
                    "Polishing transition statements between STAR sections"
                ],
                "next_steps" => [
                    "Prepare 2-3 specific STAR stories focusing purely on measurable impact",
                    "Practice pacing and structuring your answers under 2 minutes",
                    "Review advanced system design or domain concepts for this role"
                ]
            ]);
        }

        // Fallback catch-all
        return 'AI Error: ' . (string) $err;
    }

    /**
     * Handle fallback responses for getChatResponse() when offline or API fails
     */
    protected function handleChatFallback($message, $history, $context, $err)
    {
        $messageLower = strtolower($message);

        // 1. Mock Interview Session Fallback
        if (preg_match('/\[\[MOCK_INTERVIEW_SESSION(?::([a-z0-9\-]+))?\]\]/i', $context, $sessionMatch)) {
            $questionPack = $sessionMatch[1] ?? 'general';
            $questions = $this->getFallbackInterviewQuestions($questionPack);

            // Skip questions already asked this session, cycling through the
            // field-specific pool so a longer interview doesn't repeat itself.
            $askedCount = (int) floor(count($history) / 2);
            $qIndex = $askedCount % count($questions);
            $nextQuestion = $questions[$qIndex];

            $eval = $this->evaluateFallbackAnswer($message);

            return json_encode([
                "feedback" => $eval['feedback'],
                "next_question" => $nextQuestion,
                "interviewer_reply" => $eval['opener'] . " Next question: {$nextQuestion}",
                "star_score" => $eval['score'],
                "star_breakdown" => [
                    "situation" => $eval['score'],
                    "task" => $eval['score'],
                    "action" => $eval['score'],
                    "result" => $eval['score']
                ],
                "star_tip" => $eval['tip'],
                "focus_area" => $eval['focus_area']
            ]);
        }

        // 2. Resume Coach Fallback
        if (stripos($context, 'ResumeAI') !== false || stripos($context, 'resume consultant') !== false) {
            $historyCount = count($history);

            if ($historyCount === 0 || $historyCount === 2) {
                return "Hello! I am ResumeAI, your dedicated Career & Resume Coach. My role is to analyze your resume, explain areas for growth, and provide high-impact recommendations you can review and apply with a single click.\n\nTo begin, what is your target role and primary industry?";
            }

            if ($historyCount === 4) {
                return "Let's review your Professional Summary:\n\n" .
                       "<strong>1. Issue Identified:</strong> Many summaries default to generic clichés (like 'hardworking individual') without establishing immediate executive presence or scope.\n\n" .
                       "<strong>2. Coach Recommendation:</strong> Open with your exact professional title, years of specialized experience, and 2-3 core high-value competencies.\n\n" .
                       "Please share your years of experience and top career achievement so I can craft a tailored recommendation.";
            }

            if ($historyCount === 6) {
                return "<div class='coach-advice-card'>" .
                       "<p><strong>1. Issue:</strong> The previous summary needed stronger leadership impact and quantifiable scope.</p>" .
                       "<p><strong>2. Recommendation:</strong> Frame achievements around driving operational efficiency, cost optimization, and measurable deliverables.</p>" .
                       "<p><strong>3. Proposed Improvement:</strong></p>" .
                       "<blockquote>Accomplished professional with a proven track record of optimizing systems, delivering high-impact initiatives, and cross-functional leadership to drive measurable business outcomes.</blockquote>" .
                       "<p><strong>4. Your Decision:</strong> Review the suggested summary above. Click <strong>Apply Suggestion</strong> below to insert it into your Professional Summary, or reply with edits!</p>" .
                       "</div>";
            }

            // Resume STAR Experience Coaching
            return "<div class='coach-advice-card'>" .
                   "<p><strong>1. Experience Review:</strong> Let's sharpen your Work Experience bullets using the <strong>STAR method</strong> (Situation, Task, Action, Result).</p>" .
                   "<p><strong>2. Recommendation:</strong> Always lead with strong action verbs (e.g. <em>Spearheaded, Engineered, Orchestrated</em>) rather than passive duties ('Responsible for').</p>" .
                   "<p>Share a project or duty you handled, and I will generate a polished STAR bullet recommendation for you to review and apply.</p>" .
                   "</div>";
        }

        // Default chat response
        return "I am currently online in backup mode. How can I help you progress your career goals today?";
    }

    /**
     * Field-specific question pools for the offline mock-interview fallback,
     * keyed the same as getQuestionPackContext() so every job field selectable
     * in the interview setup gets realistic, role-relevant questions instead
     * of generic software-engineering ones.
     *
     * @return array<int, string>
     */
    protected function getFallbackInterviewQuestions(string $questionPack): array
    {
        $banks = [
            'software-developer' => [
                "Tell me about a time you engineered a feature or optimization that significantly improved application performance. What metrics did you track?",
                "Can you describe a situation where you disagreed with a colleague or product owner on a technical decision? How did you resolve it?",
                "How do you approach learning a completely new language, framework, or technology when starting a high-priority project?",
                "Tell me about a challenging bug or architecture issue you ran into recently. What was your systematic debugging approach?",
                "Describe a time you had to balance writing clean, maintainable code against a tight deadline. What tradeoffs did you make?",
                "Walk me through how you'd design and review a code change that touches a critical, high-traffic part of a system.",
            ],
            'data-analysis' => [
                "Tell me about a time your analysis changed a business decision. How did you communicate the insight to non-technical stakeholders?",
                "Describe a situation where the data you were given was messy or incomplete. How did you handle it?",
                "Walk me through how you validated a surprising or counter-intuitive result before presenting it.",
                "Tell me about a dashboard or report you built that a team actually used regularly. What made it stick?",
                "Describe a time you had to choose between a quick, rough analysis and a slower, more rigorous one. How did you decide?",
            ],
            'accounting-fundamentals' => [
                "Tell me about a time you caught a discrepancy in the books before it became a bigger problem. What did you do?",
                "Describe how you've handled a tight month-end or year-end close under pressure.",
                "Walk me through a time you had to explain a financial variance to a non-finance manager.",
                "Tell me about a situation where you improved a reconciliation or reporting process.",
                "Describe a time you identified a compliance or audit risk. How did you address it?",
            ],
            'digital-marketing' => [
                "Tell me about a campaign you ran that underperformed. What did you change, and what was the result?",
                "Describe how you allocated a limited budget across channels and how you measured ROI.",
                "Walk me through a time you used data to justify a change in marketing strategy.",
                "Tell me about a piece of content or campaign that significantly outperformed expectations. Why do you think it worked?",
                "Describe how you approach A/B testing a landing page or ad creative.",
            ],
            'social-media-content' => [
                "Tell me about a post or campaign that went unexpectedly viral, or unexpectedly flopped. What did you learn?",
                "Describe how you handle negative comments or a small PR issue on a brand's social account.",
                "Walk me through your process for planning a month of content for a brand.",
                "Tell me about a time you had to adapt content strategy based on engagement analytics.",
                "Describe a collaboration with a designer, photographer, or influencer that didn't go as planned. What happened?",
            ],
            'office-admin' => [
                "Tell me about a time you had to juggle competing priorities from multiple managers. How did you handle it?",
                "Describe a process you improved that made the office run more smoothly.",
                "Walk me through how you handle a scheduling conflict between important meetings.",
                "Tell me about a time you managed a confidential or sensitive matter carefully.",
                "Describe how you stay organized when handling many small tasks at once.",
            ],
            'sales-business-dev' => [
                "Tell me about a deal you lost. What did you learn, and what would you do differently?",
                "Walk me through how you handle a prospect who keeps raising the same objection.",
                "Describe a time you exceeded your sales target. What specifically drove that result?",
                "Tell me about a long sales cycle you managed — how did you keep the deal moving?",
                "Describe how you qualify a lead before investing significant time in them.",
            ],
            'customer-service' => [
                "Tell me about the most difficult customer you've handled. How did you resolve the situation?",
                "Describe a time you went beyond your role to solve a customer's problem.",
                "Walk me through how you stay calm and professional when a customer is upset.",
                "Tell me about a time you had to say no to a customer request. How did you handle it?",
                "Describe how you'd handle a recurring complaint that suggests a deeper process problem.",
            ],
            'human-resources' => [
                "Tell me about a time you mediated a conflict between two employees or teams.",
                "Describe how you've handled a sensitive disciplinary or performance conversation.",
                "Walk me through how you'd design an onboarding process for a new team.",
                "Tell me about a hiring decision you're proud of, and what made that candidate stand out.",
                "Describe a time you had to balance company policy with an employee's individual circumstances.",
            ],
            'engineering-technical' => [
                "Tell me about a time you identified a safety or quality issue on site. What did you do?",
                "Describe a project where you had to work within strict technical specifications or regulations.",
                "Walk me through how you troubleshoot equipment or system failures in the field.",
                "Tell me about a time you had to coordinate with multiple trades or teams on a project.",
                "Describe a time a project ran over budget or behind schedule. How did you respond?",
            ],
            'logistics-supply-chain' => [
                "Tell me about a time you resolved a supply disruption or delayed shipment.",
                "Describe how you've optimized a warehouse process or delivery route.",
                "Walk me through how you manage inventory accuracy across multiple locations.",
                "Tell me about a time you negotiated better terms with a supplier or carrier.",
                "Describe a situation where you had to balance cost savings against delivery reliability.",
            ],
            'legal-compliance' => [
                "Tell me about a time you identified a compliance risk before it became an issue.",
                "Describe how you've explained a complex legal or regulatory requirement to a non-legal team.",
                "Walk me through how you review a contract for risk before it's signed.",
                "Tell me about a time you had to push back on a business decision for compliance reasons.",
                "Describe how you stay current with changing regulations relevant to your role.",
            ],
            'healthcare-medical' => [
                "Tell me about a time you had to make a quick decision under pressure to protect patient safety.",
                "Describe how you communicate difficult information to a patient or their family.",
                "Walk me through how you handle a disagreement with a colleague about a course of treatment.",
                "Tell me about a time you caught an error before it reached the patient.",
                "Describe how you manage a high patient load while maintaining quality of care.",
            ],
            'education-training' => [
                "Tell me about a time you adapted your teaching approach for a struggling student.",
                "Describe how you handle a disruptive classroom or training session.",
                "Walk me through how you design a lesson or training module from scratch.",
                "Tell me about a time you used assessment data to change how you taught a topic.",
                "Describe a time you had a difficult conversation with a parent, student, or trainee.",
            ],
            'hospitality' => [
                "Tell me about a time you turned an unhappy guest into a satisfied one.",
                "Describe how you handle a service failure during a peak, high-pressure period.",
                "Walk me through how you'd handle an overbooking or resource shortage situation.",
                "Tell me about a time you went out of your way to create a memorable guest experience.",
                "Describe how you coordinate with other departments to deliver smooth service.",
            ],
            'manufacturing-production' => [
                "Tell me about a time you identified and fixed a quality control issue on the line.",
                "Describe how you've handled a safety incident or near-miss.",
                "Walk me through how you'd respond to an unexpected production line stoppage.",
                "Tell me about a time you improved throughput or reduced waste in a process.",
                "Describe how you balance production speed targets against quality standards.",
            ],
            'it-support' => [
                "Tell me about the most difficult technical issue you've resolved for a user.",
                "Describe how you prioritize tickets when you have multiple urgent requests at once.",
                "Walk me through how you explain a technical problem to a non-technical user.",
                "Tell me about a time you prevented a recurring issue by fixing the root cause.",
                "Describe how you handle a user who is frustrated or upset about a system outage.",
            ],
            'project-management' => [
                "Tell me about a project that was at risk of missing its deadline. What did you do?",
                "Describe how you handle scope creep from a stakeholder mid-project.",
                "Walk me through how you keep a cross-functional team aligned on priorities.",
                "Tell me about a time you had to deliver bad news about a project's timeline or budget.",
                "Describe a project retrospective where the team identified a meaningful process improvement.",
            ],
            'design-ux' => [
                "Tell me about a design decision you made that was based on user research, not personal preference.",
                "Describe a time your design was challenged by an engineer or stakeholder. How did you respond?",
                "Walk me through your process for taking a project from wireframe to final design.",
                "Tell me about a usability issue you caught in testing and how you fixed it.",
                "Describe how you balance visual polish against development feasibility and deadlines.",
            ],
            'general' => [
                "Could you walk me through a situation where you had to manage conflicting priorities under a tight deadline?",
                "Tell me about a time you took ownership of a problem that wasn't technically your responsibility.",
                "Describe a time you received difficult feedback. How did you respond?",
                "Walk me through a goal you set for yourself and how you achieved it.",
                "Tell me about a time you had to learn something completely new quickly to get a job done.",
            ],
        ];

        // Aliases so short-form question_pack values used elsewhere in the app
        // (e.g. 'engineering', 'sales', 'marketing', 'support') resolve too.
        $aliases = [
            'engineering' => 'software-developer',
            'sales' => 'sales-business-dev',
            'marketing' => 'digital-marketing',
            'support' => 'customer-service',
            'operations' => 'logistics-supply-chain',
            'product' => 'project-management',
        ];
        $questionPack = $aliases[$questionPack] ?? $questionPack;

        return $banks[$questionPack] ?? $banks['general'];
    }

    /**
     * Lightweight, rule-based read of an interview answer for the offline
     * fallback path — not a substitute for the real model, but enough to
     * avoid rubber-stamping every reply with the same canned praise.
     *
     * @return array{score:int, feedback:string, opener:string, tip:string, focus_area:string}
     */
    protected function evaluateFallbackAnswer(string $message): array
    {
        $message = trim($message);
        $wordCount = $message === '' ? 0 : str_word_count($message);

        $isNonAnswer = $message === '' || (bool) preg_match(
            '/^\s*(i\s*don\'?t\s*(know|understand)|not\s*sure|no\s*idea|skip|pass|n\/?a)\W*$/i',
            $message
        ) || (bool) preg_match('/\b(i\s*don\'?t\s*(know|understand)|not\s*sure|no\s*idea)\b/i', $message);

        if ($isNonAnswer) {
            return [
                'score' => 2,
                'feedback' => "That's alright — try to give a real example even if it's a rough one; a partial answer scores far better than no answer.",
                'opener' => "No problem, let's try a different angle.",
                'tip' => "Pick any real situation from your work or studies, even a small one, and walk through what you did and what happened.",
                'focus_area' => "Giving a Concrete Example",
            ];
        }

        $hasStarCues = (bool) preg_match(
            '/\b(situation|task|action|result|because|so that|as a result|specifically|for example|led to|which resulted|i decided|i implemented)\b/i',
            $message
        );
        $hasMetrics = (bool) preg_match('/\d+(\.\d+)?\s*(%|percent|hours?|days?|weeks?|months?|₦|naira|\$|users?|customers?|x\b)/i', $message)
            || (bool) preg_match('/\d/', $message);

        if ($wordCount < 12) {
            return [
                'score' => 4,
                'feedback' => "That's a start, but the answer is quite brief — interviewers want to hear the full story: what the situation was, what you did, and what happened as a result.",
                'opener' => "Thanks — let's build on that with more detail next time.",
                'tip' => "Aim for 3-5 sentences: set up the situation, describe your specific action, then state the measurable result.",
                'focus_area' => "Answer Depth",
            ];
        }

        if ($hasStarCues && $hasMetrics) {
            return [
                'score' => 8,
                'feedback' => "Strong answer — it's structured clearly and backed with specifics, which is exactly what interviewers look for.",
                'opener' => "That's a great, well-structured answer.",
                'tip' => "Keep leading with numbers like that — quantified results are what make an answer memorable.",
                'focus_area' => "STAR Storytelling Structure",
            ];
        }

        if ($hasStarCues || $hasMetrics) {
            return [
                'score' => 6,
                'feedback' => "Good answer with a clear example, but it would land even stronger with the missing piece — either a concrete number/outcome, or a clearer statement of the result.",
                'opener' => "Thanks for sharing that.",
                'tip' => "Close the story with a specific, measurable result — a number, a percentage, or a clear before/after.",
                'focus_area' => "Quantifying Results",
            ];
        }

        return [
            'score' => 5,
            'feedback' => "That covers the general idea, but try structuring it more explicitly around Situation, Task, Action, and Result so the interviewer can follow the story.",
            'opener' => "Thanks for sharing that.",
            'tip' => "Explicitly separate the four parts: what was happening, what you needed to do, what you actually did, and what the outcome was.",
            'focus_area' => "STAR Storytelling Structure",
        ];
    }

    /**
     * Generate custom aptitude test questions using Gemini AI for a specific job role
     */
    public function generateCustomAptitudeQuestions(string $jobTitle, string $jobDescription = '', int $numQuestions = 5, string $difficulty = 'intermediate'): array
    {
        if (empty($this->apiKey)) {
            return $this->getFallbackAptitudeQuestions($jobTitle, $jobDescription, $numQuestions, $difficulty);
        }

        $cacheEnabled = env('AI_CACHE_ENABLED', true);
        $cacheKey = 'ai_aptitude_' . md5($jobTitle . '_' . $jobDescription . '_' . $numQuestions . '_' . $difficulty);
        if ($cacheEnabled) {
            try {
                $cache = \Config\Services::cache();
                if ($cachedQuestions = $cache->get($cacheKey)) {
                    log_message('info', "AI Aptitude Cache HIT for job: {$jobTitle}");
                    return is_array($cachedQuestions) ? $cachedQuestions : (json_decode($cachedQuestions, true) ?? []);
                }
            } catch (\Throwable $e) {
                // Ignore cache read failures
            }
        }

        $prompt = "You are an expert HR Assessment & Psychometric Testing Specialist with deep expertise in the Nigerian and African employment market.
Generate {$numQuestions} high-quality, professional multiple-choice aptitude test questions specifically tailored for candidate screening for the job position: '{$jobTitle}'. The target difficulty level is: {$difficulty}.
Job Description Context: {$jobDescription}

LOCALIZATION GUIDELINES:
- All monetary and financial references MUST strictly use Nigerian Naira (₦ or NGN) (e.g. ₦150,000, ₦500,000, ₦1,200,000). Never use USD, EUR, or GBP.
- Use authentic Nigerian names when using situational case studies or scenarios (e.g. Ade, Ngozi, Emeka, Folake, Amina, Chidi, Babatunde, Fatima, Olumide, Zainab).
- Use Nigerian workplace locations and contexts (e.g. Lagos, Abuja, Port Harcourt, Ibadan, Kano, Victoria Island, Ikeja) and Nigerian regulatory standards (e.g. FIRS, CAMA, CBN, PENCOM, ITF) where applicable.

Respond strictly in valid JSON format matching this exact schema:
[
  {
    \"question\": \"Question stem text here\",
    \"difficulty\": \"intermediate\",
    \"explanation\": \"Brief explanation of why the correct option is right\",
    \"options\": [
      {\"text\": \"Option A text\", \"is_correct\": 1},
      {\"text\": \"Option B text\", \"is_correct\": 0},
      {\"text\": \"Option C text\", \"is_correct\": 0},
      {\"text\": \"Option D text\", \"is_correct\": 0}
    ]
  }
]
Do not include any Markdown code block formatting or backticks, return raw JSON string only.";

        $url = $this->getApiUrl('reasoning') . '?key=' . $this->apiKey;
        $payload = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'temperature' => 0.3,
                'maxOutputTokens' => 8000,
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 75);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            return $this->getFallbackAptitudeQuestions($jobTitle, $jobDescription, $numQuestions, $difficulty);
        }

        $json = json_decode($response, true);
        $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';

        $text = trim(preg_replace('/^```(?:json)?|```$/m', '', $text));
        $decoded = json_decode($text, true);

        if (is_array($decoded) && !empty($decoded)) {
            if ($cacheEnabled) {
                try {
                    $cache = \Config\Services::cache();
                    $cache->save($cacheKey, $decoded, 604800); // Cache generated aptitude questions for 7 days
                } catch (\Throwable $e) {
                    // Ignore cache write failures
                }
            }
            return $decoded;
        }

        return $this->getFallbackAptitudeQuestions($jobTitle, $jobDescription, $numQuestions, $difficulty);
    }

    /**
     * Fallback tailored aptitude questions if external AI service is unreachable
     */
    public function getFallbackAptitudeQuestions(string $jobTitle, string $jobDescription = '', int $numQuestions = 5, string $difficulty = 'intermediate'): array
    {
        $jt = strtolower($jobTitle . ' ' . $jobDescription);

        // General pool defined upfront so specialized pools can fall back or pad smoothly
        $generalPool = [
            [
                'question' => "You have two high-priority assignments with conflicting deadlines due by 5:00 PM today for different department heads. What is the most professional initial action?",
                'difficulty' => $difficulty,
                'explanation' => "Transparent, proactive communication allows managers to align on true organizational priorities rather than leaving deliverables to chance.",
                'options' => [
                    ['text' => "Communicate proactively with both stakeholders to evaluate business urgency and agree on delivery order", 'is_correct' => 1],
                    ['text' => "Silently ignore the more difficult project and hope it is forgotten", 'is_correct' => 0],
                    ['text' => "Leave the office early to avoid confrontation", 'is_correct' => 0],
                    ['text' => "Submit incomplete, untested drafts for both projects without notice", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "An upset client calls complaining that a service delivery was delayed due to public holidays in Nigeria. Which communication approach demonstrates professional customer service?",
                'difficulty' => $difficulty,
                'explanation' => "Acknowledging the client's frustration with empathy and providing concrete action steps resolves issues effectively.",
                'options' => [
                    ['text' => "Listen attentively, acknowledge the inconvenience with empathy, and provide an exact revised timeline with tracking updates", 'is_correct' => 1],
                    ['text' => "Argue with the customer and blame logistics providers entirely", 'is_correct' => 0],
                    ['text' => "Place the customer on indefinite hold until they hang up", 'is_correct' => 0],
                    ['text' => "Tell the client that their expectations are unreasonable", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "A project requires analyzing ₦1,500,000 in promotional campaign spending across three branches: Lagos, Abuja, and Port Harcourt. If Lagos received 50% and Abuja received 30%, how much was allocated to Port Harcourt?",
                'difficulty' => $difficulty,
                'explanation' => "Lagos: 50% (₦750,000), Abuja: 30% (₦450,000). Total = 80%. Port Harcourt received the remaining 20% of ₦1,500,000 = ₦300,000.",
                'options' => [
                    ['text' => "₦300,000", 'is_correct' => 1],
                    ['text' => "₦250,000", 'is_correct' => 0],
                    ['text' => "₦400,000", 'is_correct' => 0],
                    ['text' => "₦150,000", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "When presenting sensitive quarterly performance metrics to senior leadership, what is the best practice for data clarity?",
                'difficulty' => $difficulty,
                'explanation' => "Clear summaries highlighting key drivers, actionable findings, and visual charts allow leaders to make informed decisions quickly.",
                'options' => [
                    ['text' => "Present concise visual charts highlighting key performance indicators, core drivers, and actionable recommendations", 'is_correct' => 1],
                    ['text' => "Read unformatted raw database exports line-by-line", 'is_correct' => 0],
                    ['text' => "Omit underperforming figures to make results look better", 'is_correct' => 0],
                    ['text' => "Avoid answering questions regarding data sources", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "In workplace ethics and confidentiality, how should an employee handle sensitive candidate resumes and personal contact details?",
                'difficulty' => $difficulty,
                'explanation' => "Under data protection standards (such as NDPR), personal and candidate data must be stored securely and processed solely for legitimate employment screening.",
                'options' => [
                    ['text' => "Store securely on authorized company systems and share exclusively on a strict need-to-know basis", 'is_correct' => 1],
                    ['text' => "Forward candidate resumes to personal social media groups", 'is_correct' => 0],
                    ['text' => "Leave printed CVs unattended in open public workspaces", 'is_correct' => 0],
                    ['text' => "Sell applicant telephone numbers to third-party telemarketers", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "During a cross-functional strategy meeting, you disagree with a technical proposal put forward by a colleague. How should you voice your perspective?",
                'difficulty' => $difficulty,
                'explanation' => "Constructive dissent focuses objectively on project goals, evidence, and risk analysis without personal criticism.",
                'options' => [
                    ['text' => "Acknowledge the colleague's objective and present your data-backed alternative highlighting tradeoffs and solutions", 'is_correct' => 1],
                    ['text' => "Interrupt aggressively and tell the meeting room that the proposal is incompetent", 'is_correct' => 0],
                    ['text' => "Stay quiet in the meeting and complain behind their back to other teammates", 'is_correct' => 0],
                    ['text' => "Walk out of the meeting abruptly", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "You discover a calculation error in a report that was already sent to senior leadership by your team. What is the most appropriate action?",
                'difficulty' => $difficulty,
                'explanation' => "Integrity and accountability require immediate correction with the updated data and an explanation of the variance.",
                'options' => [
                    ['text' => "Notify your manager immediately, provide the corrected figures, and issue a polite revision to stakeholders", 'is_correct' => 1],
                    ['text' => "Hope that no one notices the error and delete your local copies of the spreadsheet", 'is_correct' => 0],
                    ['text' => "Blame an intern or junior team member publicly", 'is_correct' => 0],
                    ['text' => "Falsify subsequent month records to artificially offset the difference", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "Which framework is most effective for prioritizing multiple daily tasks based on urgency and importance?",
                'difficulty' => $difficulty,
                'explanation' => "The Eisenhower Matrix categorizes activities by urgent vs important to ensure strategic priorities receive dedicated attention.",
                'options' => [
                    ['text' => "The Eisenhower Matrix (Urgent vs Important quadrant categorization)", 'is_correct' => 1],
                    ['text' => "First-In, Never-Out randomness", 'is_correct' => 0],
                    ['text' => "Focusing exclusively on the easiest tasks while postponing critical deliverables", 'is_correct' => 0],
                    ['text' => "Responding to incoming messages without planning", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "A vendor in Ikeja sends an aggressive email demanding immediate payment for an unverified invoice. What is the best professional response?",
                'difficulty' => $difficulty,
                'explanation' => "Professional correspondence maintains composure, references verified invoice verification procedures, and states a realistic review timeline.",
                'options' => [
                    ['text' => "Respond politely acknowledging receipt, reference standard procurement verification stages, and state an estimated timeline", 'is_correct' => 1],
                    ['text' => "Reply with angry insults and threaten to cancel all contracts", 'is_correct' => 0],
                    ['text' => "Approve payment immediately without checking receipt of goods", 'is_correct' => 0],
                    ['text' => "Block the vendor's email address permanently", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "A colleague on your project team is consistently falling behind their milestones, affecting your deliverables. What step should you take first?",
                'difficulty' => $difficulty,
                'explanation' => "Direct, supportive peer inquiry often identifies bottlenecks early and allows joint problem-solving before escalation.",
                'options' => [
                    ['text' => "Schedule a brief one-on-one check-in to ask about roadblocks and collaborate on how to get the timeline back on track", 'is_correct' => 1],
                    ['text' => "Complain to the Managing Director immediately without speaking to the colleague", 'is_correct' => 0],
                    ['text' => "Stop working on your own part of the project in protest", 'is_correct' => 0],
                    ['text' => "Post about the colleague's delays on public social media", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "In remote or hybrid work environments across Nigeria, what is the best practice for maintaining transparency and alignment?",
                'difficulty' => $difficulty,
                'explanation' => "Structured asynchronous updates, daily standups, and centralized task boards keep teams aligned regardless of location.",
                'options' => [
                    ['text' => "Regular daily standups, clear written documentation, and transparent task board updates", 'is_correct' => 1],
                    ['text' => "Working in complete isolation without updating communication channels", 'is_correct' => 0],
                    ['text' => "Calling colleagues unexpectedly at midnight for non-emergencies", 'is_correct' => 0],
                    ['text' => "Refusing to document project decisions or meeting action items", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "What does emotional intelligence (EQ) in the workplace primarily entail?",
                'difficulty' => $difficulty,
                'explanation' => "EQ involves self-awareness, emotional regulation, empathy, and adept relationship management in diverse professional contexts.",
                'options' => [
                    ['text' => "The ability to perceive, understand, and constructively manage your emotions and empathize with colleagues", 'is_correct' => 1],
                    ['text' => "The ability to memorize technical textbooks verbatim", 'is_correct' => 0],
                    ['text' => "Suppression of all communication with coworkers", 'is_correct' => 0],
                    ['text' => "Winning every workplace argument through aggression", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "When faced with an urgent decision where you only have 70% of the ideal data available, what is the recommended leadership approach?",
                'difficulty' => $difficulty,
                'explanation' => "Calculated decision-making evaluates core risks, defines rollback options, and executes iteratively rather than suffering analysis paralysis.",
                'options' => [
                    ['text' => "Assess key risks, choose a pragmatic path with defined fallback measures, and iterate as new data arrives", 'is_correct' => 1],
                    ['text' => "Freeze all business operations indefinitely until 100% certainty is reached", 'is_correct' => 0],
                    ['text' => "Flip a coin without analyzing any available data points", 'is_correct' => 0],
                    ['text' => "Delegate the entire decision to someone else to avoid personal responsibility", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "A junior colleague makes an honest clerical mistake that results in a minor client inconvenience. How should a team lead address it?",
                'difficulty' => $difficulty,
                'explanation' => "Constructive coaching turns mistakes into learning opportunities while establishing preventive safeguards.",
                'options' => [
                    ['text' => "Review the mistake privately with them, explain the root cause, and implement a checklist to prevent recurrence", 'is_correct' => 1],
                    ['text' => "Humiliate the junior colleague in front of the entire company", 'is_correct' => 0],
                    ['text' => "Terminate their employment immediately without inquiry", 'is_correct' => 0],
                    ['text' => "Pretend the mistake never happened and let the client remain confused", 'is_correct' => 0]
                ]
            ],
            [
                'question' => "If an employee witnesses suspicious financial transactions or regulatory non-compliance in their organization, what is the ethical procedure?",
                'difficulty' => $difficulty,
                'explanation' => "Established whistleblowing channels and internal audit/compliance protocols provide confidential, protected reporting avenues.",
                'options' => [
                    ['text' => "Report through established internal audit, compliance, or confidential whistleblowing channels with documentation", 'is_correct' => 1],
                    ['text' => "Solicit bribes from the perpetrators to keep quiet", 'is_correct' => 0],
                    ['text' => "Destroy evidence to avoid getting involved", 'is_correct' => 0],
                    ['text' => "Ignore company policy and post sensitive company records to Reddit", 'is_correct' => 0]
                ]
            ]
        ];

        if (preg_match('/(software|developer|engineer|tech|code|frontend|backend|fullstack|php|python|javascript|it|web)/', $jt)) {
            $pool = [
                [
                    'question' => "When optimizing a database-driven application handling thousands of concurrent users in Lagos, which practice most effectively prevents N+1 query performance bottlenecks?",
                    'difficulty' => $difficulty,
                    'explanation' => "Eager loading queries the related models in a single optimized query rather than running an additional query for each individual record.",
                    'options' => [
                        ['text' => "Eager loading related relational models upfront using JOINs or batched IN queries", 'is_correct' => 1],
                        ['text' => "Running lazy-loaded relationships inside a nested foreach loop", 'is_correct' => 0],
                        ['text' => "Removing all primary and foreign key constraints on relational tables", 'is_correct' => 0],
                        ['text' => "Disabling caching headers on API endpoints", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Which of the following HTTP status codes should a REST API return when a candidate submits an application with an invalid email format?",
                    'difficulty' => $difficulty,
                    'explanation' => "422 Unprocessable Entity indicates semantic or input validation errors, whereas 400 Bad Request indicates malformed request syntax.",
                    'options' => [
                        ['text' => "422 Unprocessable Entity or 400 Bad Request", 'is_correct' => 1],
                        ['text' => "500 Internal Server Error", 'is_correct' => 0],
                        ['text' => "401 Unauthorized", 'is_correct' => 0],
                        ['text' => "301 Moved Permanently", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In continuous integration and modern software delivery, what is the primary purpose of writing automated unit tests?",
                    'difficulty' => $difficulty,
                    'explanation' => "Unit tests isolate individual components or methods to ensure regressions are caught quickly before code is merged.",
                    'options' => [
                        ['text' => "To verify individual methods and functions work correctly in isolation and catch regressions early", 'is_correct' => 1],
                        ['text' => "To replace manual User Acceptance Testing entirely", 'is_correct' => 0],
                        ['text' => "To increase code execution duration and network load", 'is_correct' => 0],
                        ['text' => "To test third-party payment gateway uptime", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "To prevent Cross-Site Scripting (XSS) attacks when outputting candidate-submitted text in a web view, which safeguard must be applied?",
                    'difficulty' => $difficulty,
                    'explanation' => "HTML entity encoding (such as esc() or htmlspecialchars()) ensures user strings cannot be interpreted as executable HTML/JavaScript.",
                    'options' => [
                        ['text' => "Context-aware HTML escaping and entity encoding (e.g., esc() or htmlspecialchars())", 'is_correct' => 1],
                        ['text' => "Encrypting the database connection string with AES-256", 'is_correct' => 0],
                        ['text' => "Rendering raw HTML directly with unescaped echo tags", 'is_correct' => 0],
                        ['text' => "Increasing session cookie expiration timeout to 30 days", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is the recommended Git branching practice before introducing a major new feature to a production repository?",
                    'difficulty' => $difficulty,
                    'explanation' => "Creating an isolated feature branch preserves production stability on main and facilitates peer code review via pull requests.",
                    'options' => [
                        ['text' => "Create a dedicated feature branch from main/dev and submit a Pull Request after testing", 'is_correct' => 1],
                        ['text' => "Commit untested code directly to the production main branch using --force", 'is_correct' => 0],
                        ['text' => "Disable git hooks and delete git commit history", 'is_correct' => 0],
                        ['text' => "Share personal SSH private keys with all team members", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Which technique is the most effective defense against SQL Injection vulnerabilities in backend database queries?",
                    'difficulty' => $difficulty,
                    'explanation' => "Prepared statements and parameterized queries separate query structure from user data, preventing arbitrary command injection.",
                    'options' => [
                        ['text' => "Using prepared statements with parameterized query placeholders", 'is_correct' => 1],
                        ['text' => "Concatenating user inputs directly into the SQL string", 'is_correct' => 0],
                        ['text' => "Relying purely on JavaScript client-side input validation", 'is_correct' => 0],
                        ['text' => "Storing database credentials in plain text in HTML forms", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In web application architecture, what is the primary role of an in-memory datastore like Redis?",
                    'difficulty' => $difficulty,
                    'explanation' => "Redis provides microsecond read/write performance suitable for session state, API caching, and fast message brokering.",
                    'options' => [
                        ['text' => "High-throughput caching, session management, and rate limiting in RAM", 'is_correct' => 1],
                        ['text' => "Permanent relational table storage with ACID transaction rollback files", 'is_correct' => 0],
                        ['text' => "Compiling CSS and JavaScript client bundles", 'is_correct' => 0],
                        ['text' => "Running cron daemon jobs directly on the operating system", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "When an application needs to send automated onboarding emails without delaying the user's web page response, which pattern is recommended?",
                    'difficulty' => $difficulty,
                    'explanation' => "Asynchronous job queues decouple long-running operations (like SMTP or external API calls) from the synchronous HTTP request/response cycle.",
                    'options' => [
                        ['text' => "Pushing email jobs to an asynchronous background worker queue (e.g. RabbitMQ, Redis Queue)", 'is_correct' => 1],
                        ['text' => "Executing synchronous SMTP socket connections in the HTTP request controller thread", 'is_correct' => 0],
                        ['text' => "Asking the candidate to wait 60 seconds on the page while the email finishes sending", 'is_correct' => 0],
                        ['text' => "Triggering email delivery via an infinite client-side JavaScript loop", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is the primary benefit of containerizing a web application with Docker?",
                    'difficulty' => $difficulty,
                    'explanation' => "Docker packages application code with its exact runtime, OS libraries, and configurations, ensuring consistent execution across development and production.",
                    'options' => [
                        ['text' => "Guarantees environment consistency and dependency isolation between local dev and cloud production", 'is_correct' => 1],
                        ['text' => "Automatically writes bug-free application logic without programming", 'is_correct' => 0],
                        ['text' => "Eliminates the requirement for database backups", 'is_correct' => 0],
                        ['text' => "Triples network bandwidth speed on mobile devices", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Which HTTP method should be used according to REST conventions when updating only specific fields of a resource?",
                    'difficulty' => $difficulty,
                    'explanation' => "PATCH is intended for partial modifications to an existing resource, while PUT replaces the entire resource representation.",
                    'options' => [
                        ['text' => "PATCH", 'is_correct' => 1],
                        ['text' => "GET", 'is_correct' => 0],
                        ['text' => "DELETE", 'is_correct' => 0],
                        ['text' => "HEAD", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In database indexing, why should developers avoid creating indexes on columns that undergo frequent mass write updates?",
                    'difficulty' => $difficulty,
                    'explanation' => "Every INSERT, UPDATE, and DELETE requires updating all corresponding B-tree indexes, creating significant write latency.",
                    'options' => [
                        ['text' => "Each write operation incurs overhead as the database rebalances the B-tree index structures", 'is_correct' => 1],
                        ['text' => "Indexes prevent read queries from accessing the column entirely", 'is_correct' => 0],
                        ['text' => "Indexed columns automatically convert text to binary numbers", 'is_correct' => 0],
                        ['text' => "Databases only support a maximum of one index per database instance", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is the main function of Cross-Origin Resource Sharing (CORS) in web browsers?",
                    'difficulty' => $difficulty,
                    'explanation' => "CORS is a browser security mechanism that restricts scripts on one origin from making unauthorized cross-origin requests to another domain.",
                    'options' => [
                        ['text' => "To control which external origins and domains are permitted to access API resources via browser HTTP requests", 'is_correct' => 1],
                        ['text' => "To compress image files before sending them over mobile cellular networks", 'is_correct' => 0],
                        ['text' => "To encrypt database passwords using private cryptographic keys", 'is_correct' => 0],
                        ['text' => "To convert PHP classes into JavaScript functions automatically", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In JSON Web Token (JWT) architecture, what guarantees that the payload has not been tampered with in transit?",
                    'difficulty' => $difficulty,
                    'explanation' => "The JWT signature is calculated using the header, payload, and a secret key; any modification invalidates the signature check.",
                    'options' => [
                        ['text' => "The cryptographic digital signature verified against the secret or public key", 'is_correct' => 1],
                        ['text' => "Base64 encoding which provides military-grade encryption", 'is_correct' => 0],
                        ['text' => "The client browser's local cache timestamp", 'is_correct' => 0],
                        ['text' => "The DNS provider's nameserver records", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is the primary role of a Continuous Integration (CI) pipeline in software development?",
                    'difficulty' => $difficulty,
                    'explanation' => "CI pipelines automatically compile, lint, and run tests whenever new code is committed, identifying defects before merge.",
                    'options' => [
                        ['text' => "To automatically run test suites, code linters, and build checks whenever changes are pushed", 'is_correct' => 1],
                        ['text' => "To automatically invoice clients for software development hours", 'is_correct' => 0],
                        ['text' => "To generate marketing social media posts for new software releases", 'is_correct' => 0],
                        ['text' => "To disable git branch merges indefinitely", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "When designing a decoupled microservices architecture, which protocol is best suited for resilient, asynchronous event broadcasting?",
                    'difficulty' => $difficulty,
                    'explanation' => "Message brokers and event streams (e.g. Kafka, RabbitMQ) provide decoupled publish/subscribe event broadcasting.",
                    'options' => [
                        ['text' => "A message broker utilizing a Publish/Subscribe pattern (e.g., Kafka, RabbitMQ)", 'is_correct' => 1],
                        ['text' => "Direct synchronous HTTP POST calls in an unbuffered chain", 'is_correct' => 0],
                        ['text' => "Sharing a single mutable SQLite file on a shared network drive", 'is_correct' => 0],
                        ['text' => "Manual copy-pasting of JSON payloads between server consoles", 'is_correct' => 0]
                    ]
                ]
            ];
        } elseif (preg_match('/(sale|market|growth|business dev|account manager)/', $jt)) {
            $pool = [
                [
                    'question' => "A corporate prospect in Victoria Island expresses hesitation over pricing for an annual enterprise contract. What is the most effective sales response?",
                    'difficulty' => $difficulty,
                    'explanation' => "Uncovering specific business requirements and quantifying ROI builds credibility and addresses value before negotiating price.",
                    'options' => [
                        ['text' => "Inquire into their primary operational challenges and demonstrate measurable ROI and efficiency gains", 'is_correct' => 1],
                        ['text' => "Immediately offer a 60% price cut without understanding their business needs", 'is_correct' => 0],
                        ['text' => "End communication and archive the lead immediately", 'is_correct' => 0],
                        ['text' => "Criticize competitor pricing models aggressively", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Which key metric best measures how effectively a sales pipeline converts newly generated marketing leads into paying customers?",
                    'difficulty' => $difficulty,
                    'explanation' => "Lead-to-Customer conversion rate measures the end-to-end efficiency of qualifying and closing leads.",
                    'options' => [
                        ['text' => "Lead-to-Customer Conversion Rate (%)", 'is_correct' => 1],
                        ['text' => "Total number of unread marketing emails sent", 'is_correct' => 0],
                        ['text' => "Average employee tenure in months", 'is_correct' => 0],
                        ['text' => "Website hosting server uptime", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is the primary objective of a Customer Relationship Management (CRM) platform in a high-growth business?",
                    'difficulty' => $difficulty,
                    'explanation' => "CRMs centralize customer touchpoints, interaction history, deal stages, and revenue forecasting.",
                    'options' => [
                        ['text' => "To centralize client interaction history, pipeline velocity, and follow-up accountability", 'is_correct' => 1],
                        ['text' => "To automate legal contract dispute litigation", 'is_correct' => 0],
                        ['text' => "To replace human sales representatives completely", 'is_correct' => 0],
                        ['text' => "To generate static PDF invoices manually", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In digital B2B marketing, which channel typically generates the highest qualification rate for corporate decision-makers in Nigeria?",
                    'difficulty' => $difficulty,
                    'explanation' => "LinkedIn outreach and targeted thought leadership allow precise B2B targeting by job seniority, industry, and geography.",
                    'options' => [
                        ['text' => "Targeted LinkedIn B2B campaigns and personalized consultative outreach", 'is_correct' => 1],
                        ['text' => "Unsolicited mass bulk SMS blast to unverified numbers", 'is_correct' => 0],
                        ['text' => "Consumer entertainment meme pages", 'is_correct' => 0],
                        ['text' => "Cold flyers distributed at motor parks", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What strategy most reliably reduces Customer Churn and increases Customer Lifetime Value (LTV)?",
                    'difficulty' => $difficulty,
                    'explanation' => "Proactive onboarding, responsive account management, and ongoing value reviews secure client retention.",
                    'options' => [
                        ['text' => "Structured client onboarding, regular business reviews, and proactive customer success support", 'is_correct' => 1],
                        ['text' => "Ignoring existing customers once the initial contract is signed", 'is_correct' => 0],
                        ['text' => "Charging unannounced surprise maintenance fees", 'is_correct' => 0],
                        ['text' => "Removing customer support channels", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is considered a healthy Customer Lifetime Value (LTV) to Customer Acquisition Cost (CAC) ratio for sustainable business growth?",
                    'difficulty' => $difficulty,
                    'explanation' => "An LTV:CAC ratio of 3:1 or higher indicates that revenue earned from a customer significantly exceeds the cost incurred to acquire them.",
                    'options' => [
                        ['text' => "3:1 or higher (LTV is at least triple the CAC)", 'is_correct' => 1],
                        ['text' => "1:3 (spending triple the acquisition cost compared to revenue)", 'is_correct' => 0],
                        ['text' => "1:1 (breaking exactly even with zero operational margin)", 'is_correct' => 0],
                        ['text' => "0:5 (spending money with zero customer revenue)", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "When a prospective B2B client mentions they are already using a competitor's software, what is the best consultative response?",
                    'difficulty' => $difficulty,
                    'explanation' => "Acknowledging their current solution while asking open questions uncovers unmet needs or pain points without alienating the prospect.",
                    'options' => [
                        ['text' => "Acknowledge the competitor respectfully, then inquire what gaps, bottlenecks, or features they wish was handled better", 'is_correct' => 1],
                        ['text' => "Tell them the competitor's software is completely worthless and defective", 'is_correct' => 0],
                        ['text' => "Hang up immediately and delete the prospect's contact", 'is_correct' => 0],
                        ['text' => "Promise custom features for free that your engineering team cannot build", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In an inbound digital marketing strategy, what role do lead magnets (e.g. whitepapers, salary guides) serve in the marketing funnel?",
                    'difficulty' => $difficulty,
                    'explanation' => "Lead magnets exchange high-value industry content for prospect contact information, converting anonymous visitors into qualified leads.",
                    'options' => [
                        ['text' => "Exchanging valuable industry insights for prospect contact details to initiate the nurturing sequence", 'is_correct' => 1],
                        ['text' => "Replacing the need for sales representatives entirely", 'is_correct' => 0],
                        ['text' => "Increasing corporate server storage bills unnecessarily", 'is_correct' => 0],
                        ['text' => "Directly billing client credit cards automatically", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is the primary indicator of product-market fit according to modern growth benchmarks?",
                    'difficulty' => $difficulty,
                    'explanation' => "High organic user retention and repeat engagement demonstrate that customers find enduring value in the product.",
                    'options' => [
                        ['text' => "Strong organic customer retention, referral expansion, and high Net Promoter Scores", 'is_correct' => 1],
                        ['text' => "A single spike in signups driven by one expensive advertisement", 'is_correct' => 0],
                        ['text' => "Winning a visual website design contest", 'is_correct' => 0],
                        ['text' => "Registering a registered trademark with the Corporate Affairs Commission (CAC)", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In enterprise sales with multiple procurement stakeholders, which persona is known as the 'Economic Buyer'?",
                    'difficulty' => $difficulty,
                    'explanation' => "The Economic Buyer holds discretionary budget control and final authority to release funds for contract payment.",
                    'options' => [
                        ['text' => "The senior executive with discretionary budget authorization and final purchasing approval", 'is_correct' => 1],
                        ['text' => "The end-user intern who tests the trial software features daily", 'is_correct' => 0],
                        ['text' => "The external building security guard", 'is_correct' => 0],
                        ['text' => "The customer service representative answering general phone queries", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Which email outreach practice most effectively improves cold email open rates and engagement for B2B prospects?",
                    'difficulty' => $difficulty,
                    'explanation' => "Personalized subject lines and opening hooks referencing the prospect's company achieve dramatically higher open and reply rates.",
                    'options' => [
                        ['text' => "Relevant, personalized subject lines that address specific industry pain points and clear calls-to-action", 'is_correct' => 1],
                        ['text' => "Writing subject lines in ALL CAPS with deceptive 'RE: Payment Overdue' tags", 'is_correct' => 0],
                        ['text' => "Attaching 50MB uncompressed zip files to the first email", 'is_correct' => 0],
                        ['text' => "BCCing 5,000 random email addresses simultaneously", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is the primary distinction between Organic Search Optimization (SEO) and Pay-Per-Click Advertising (PPC)?",
                    'difficulty' => $difficulty,
                    'explanation' => "SEO builds sustainable long-term authority and traffic, while PPC generates immediate visibility that ceases once ad spend stops.",
                    'options' => [
                        ['text' => "SEO yields sustainable long-term organic traffic through search authority, while PPC generates paid clicks that stop when budget ends", 'is_correct' => 1],
                        ['text' => "SEO requires daily payment to Google for every impression", 'is_correct' => 0],
                        ['text' => "PPC is illegal under Nigerian consumer protection standards", 'is_correct' => 0],
                        ['text' => "SEO only functions on mobile phones", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In sales pipeline management, what does 'Pipeline Velocity' measure?",
                    'difficulty' => $difficulty,
                    'explanation' => "Pipeline velocity measures the speed and volume at which prospective deals travel through stages to generate closed revenue.",
                    'options' => [
                        ['text' => "The speed and dollar value at which qualified opportunities move through sales stages to close", 'is_correct' => 1],
                        ['text' => "The average typing speed of the sales team members", 'is_correct' => 0],
                        ['text' => "The physical transit time of couriers delivering paper contracts", 'is_correct' => 0],
                        ['text' => "The latency of the company's fiber-optic internet connection", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "How should an Account Executive handle an enterprise lead whose budget cycle has just been frozen until Q3?",
                    'difficulty' => $difficulty,
                    'explanation' => "Nurturing the relationship with relevant insights and scheduling a formal check-in prior to the budget re-opening maintains deal momentum.",
                    'options' => [
                        ['text' => "Agree on a scheduled re-engagement date, continue sharing relevant market case studies, and remain top-of-mind", 'is_correct' => 1],
                        ['text' => "Harass the executive with multiple phone calls every single morning", 'is_correct' => 0],
                        ['text' => "Permanently delete the account and block the contact on LinkedIn", 'is_correct' => 0],
                        ['text' => "Send an invoice anyway and threaten litigation", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Which of the following describes an Account-Based Marketing (ABM) strategy?",
                    'difficulty' => $difficulty,
                    'explanation' => "ABM treats high-value corporate accounts as individual markets, coordinating bespoke marketing campaigns and sales outreach.",
                    'options' => [
                        ['text' => "Tailoring bespoke marketing campaigns and customized outreach specifically to high-value target companies", 'is_correct' => 1],
                        ['text' => "Purchasing random generic consumer billboard advertisements along major highways", 'is_correct' => 0],
                        ['text' => "Sending identical mass newsletters to every consumer on an unsegmented list", 'is_correct' => 0],
                        ['text' => "Exclusively marketing on physical radio stations", 'is_correct' => 0]
                    ]
                ]
            ];
        } elseif (preg_match('/(account|financ|audit|tax|bookkeep)/', $jt)) {
            $pool = [
                [
                    'question' => "Under Nigerian tax regulations (FIRS), what is the standard rate of Value Added Tax (VAT) applied on non-exempt goods and services?",
                    'difficulty' => $difficulty,
                    'explanation' => "Under the Nigerian Finance Act, standard VAT is 7.5%.",
                    'options' => [
                        ['text' => "7.5%", 'is_correct' => 1],
                        ['text' => "5.0%", 'is_correct' => 0],
                        ['text' => "10.0%", 'is_correct' => 0],
                        ['text' => "15.0%", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Which fundamental accounting principle requires revenues and matching expenses to be recorded in the period they occur, rather than when cash is transferred?",
                    'difficulty' => $difficulty,
                    'explanation' => "The Accrual Principle matches revenues and related expenses within the same accounting cycle.",
                    'options' => [
                        ['text' => "Accrual Principle", 'is_correct' => 1],
                        ['text' => "Cash Basis Principle", 'is_correct' => 0],
                        ['text' => "Prudence Principle only", 'is_correct' => 0],
                        ['text' => "Historical Cost Principle", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "When performing a monthly bank reconciliation, a credit memo listed on the bank statement that has not been recorded in the company books should be:",
                    'difficulty' => $difficulty,
                    'explanation' => "A bank credit memo represents money added by the bank (e.g. direct collection or interest), which must be added to the cash ledger balance.",
                    'options' => [
                        ['text' => "Added to the cash book balance", 'is_correct' => 1],
                        ['text' => "Deducted from the bank statement balance", 'is_correct' => 0],
                        ['text' => "Ignored until the financial year-end audit", 'is_correct' => 0],
                        ['text' => "Recorded as an accrued liability", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What constitutes the core equation represented on a corporate Balance Sheet?",
                    'difficulty' => $difficulty,
                    'explanation' => "Assets = Liabilities + Owner's Equity is the fundamental accounting equation.",
                    'options' => [
                        ['text' => "Assets = Liabilities + Equity", 'is_correct' => 1],
                        ['text' => "Assets = Revenue - Operating Expenses", 'is_correct' => 0],
                        ['text' => "Liabilities = Assets + Cash Flow", 'is_correct' => 0],
                        ['text' => "Equity = Gross Margin - Taxation", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Under the Nigerian Pension Reform Act, what is the mandatory minimum employer contribution percentage towards an employee's Retirement Savings Account (RSA)?",
                    'difficulty' => $difficulty,
                    'explanation' => "The Pension Reform Act mandates at least 10% employer contribution and 8% employee contribution.",
                    'options' => [
                        ['text' => "10% of monthly emoluments", 'is_correct' => 1],
                        ['text' => "5% of basic salary", 'is_correct' => 0],
                        ['text' => "8% of gross profit", 'is_correct' => 0],
                        ['text' => "15% of annual bonus", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is the primary function of Withholding Tax (WHT) in the Nigerian taxation system?",
                    'difficulty' => $difficulty,
                    'explanation' => "Withholding Tax serves as an advance payment of income tax deducted at source from contract payments and dividends.",
                    'options' => [
                        ['text' => "An advance deduction of income tax at source, creditable against final tax liabilities", 'is_correct' => 1],
                        ['text' => "A penalty tax imposed exclusively on bankrupt enterprises", 'is_correct' => 0],
                        ['text' => "An optional voluntary donation to state government authorities", 'is_correct' => 0],
                        ['text' => "A custom duty paid exclusively on imported luxury aircraft", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Under the Straight-Line Depreciation method, what is the annual depreciation expense for company equipment purchased for ₦5,000,000 with a salvage value of ₦500,000 and a 5-year useful life?",
                    'difficulty' => $difficulty,
                    'explanation' => "Depreciation = (Cost - Salvage Value) / Useful Life = (₦5,000,000 - ₦500,000) / 5 = ₦4,500,000 / 5 = ₦900,000 per year.",
                    'options' => [
                        ['text' => "₦900,000 per year", 'is_correct' => 1],
                        ['text' => "₦1,000,000 per year", 'is_correct' => 0],
                        ['text' => "₦750,000 per year", 'is_correct' => 0],
                        ['text' => "₦500,000 per year", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "How is the Working Capital ratio (Current Ratio) calculated on a corporate financial statement?",
                    'difficulty' => $difficulty,
                    'explanation' => "Current Ratio = Current Assets divided by Current Liabilities, indicating short-term solvency.",
                    'options' => [
                        ['text' => "Current Assets divided by Current Liabilities", 'is_correct' => 1],
                        ['text' => "Total Revenue divided by Operating Expenses", 'is_correct' => 0],
                        ['text' => "Net Profit divided by Total Shareholder Equity", 'is_correct' => 0],
                        ['text' => "Long-term Debt divided by Cash and Bank Equivalents", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In financial auditing and internal controls, what is the core purpose of Segregation of Duties (SoD)?",
                    'difficulty' => $difficulty,
                    'explanation' => "Separating transaction authorization, custody of assets, and record-keeping reduces errors and prevents fraudulent misappropriation.",
                    'options' => [
                        ['text' => "To prevent fraud and error by ensuring no single employee has control over all phases of a transaction", 'is_correct' => 1],
                        ['text' => "To double the paperwork required to complete simple office tasks", 'is_correct' => 0],
                        ['text' => "To eliminate the need for accounting software", 'is_correct' => 0],
                        ['text' => "To allow junior officers to override senior executive signatures", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "In an inflationary environment, which inventory valuation method produces lower reported Cost of Goods Sold (COGS) and higher gross profit?",
                    'difficulty' => $difficulty,
                    'explanation' => "First-In, First-Out (FIFO) assigns older, cheaper acquisition costs to COGS, leaving higher-cost inventory on the balance sheet.",
                    'options' => [
                        ['text' => "FIFO (First-In, First-Out)", 'is_correct' => 1],
                        ['text' => "LIFO (Last-In, First-Out)", 'is_correct' => 0],
                        ['text' => "Weighted Average Costing", 'is_correct' => 0],
                        ['text' => "Specific Unit Identification only", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Which of the following expenditures should be capitalized on the Balance Sheet as Capital Expenditure (CapEx) rather than expensed as OpEx?",
                    'difficulty' => $difficulty,
                    'explanation' => "Acquisition of productive assets that yield economic benefits over multiple years (e.g. factory equipment, building premises) are capitalized.",
                    'options' => [
                        ['text' => "Purchasing a new commercial generator and building extension to expand production capacity", 'is_correct' => 1],
                        ['text' => "Monthly office electric power bill and generator diesel consumption", 'is_correct' => 0],
                        ['text' => "Purchasing standard A4 printer paper for daily administrative correspondence", 'is_correct' => 0],
                        ['text' => "Routine monthly janitorial cleaning supplies", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "Under Nigerian tax law (Finance Act), how are small companies with annual gross turnover under ₦25,000,000 treated for Companies Income Tax (CIT)?",
                    'difficulty' => $difficulty,
                    'explanation' => "Under the Nigerian Finance Act, small enterprises with turnover under ₦25 million are exempted from Companies Income Tax (0% CIT rate).",
                    'options' => [
                        ['text' => "They are subject to 0% Companies Income Tax (CIT exempt)", 'is_correct' => 1],
                        ['text' => "They pay a flat rate of 30% of total revenue", 'is_correct' => 0],
                        ['text' => "They must pay an advance surcharge of ₦5,000,000 upon incorporation", 'is_correct' => 0],
                        ['text' => "They are prohibited from opening corporate bank accounts", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What are the three core sections that comprise the Statement of Cash Flows?",
                    'difficulty' => $difficulty,
                    'explanation' => "A Cash Flow Statement categorizes cash inflows and outflows into Operating Activities, Investing Activities, and Financing Activities.",
                    'options' => [
                        ['text' => "Operating Activities, Investing Activities, and Financing Activities", 'is_correct' => 1],
                        ['text' => "Sales Revenue, Gross Margin, and Net Profit", 'is_correct' => 0],
                        ['text' => "Current Assets, Non-Current Assets, and Retained Earnings", 'is_correct' => 0],
                        ['text' => "Domestic Transactions, Foreign Currency Swaps, and Cryptocurrencies", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "What is the meaning of 'Materiality' in external audit standards?",
                    'difficulty' => $difficulty,
                    'explanation' => "An item or misstatement is material if its omission or misstatement could reasonably influence the economic decisions of financial statement users.",
                    'options' => [
                        ['text' => "The threshold at which financial misstatements could reasonably influence the economic decisions of users", 'is_correct' => 1],
                        ['text' => "The physical weight of paper invoices kept in filing cabinets", 'is_correct' => 0],
                        ['text' => "The total number of staff employed in the accounting department", 'is_correct' => 0],
                        ['text' => "The brand name of the computer hardware used to generate spreadsheets", 'is_correct' => 0]
                    ]
                ],
                [
                    'question' => "How should purchased proprietary software licenses used across a 3-year term be accounted for?",
                    'difficulty' => $difficulty,
                    'explanation' => "Proprietary software licenses are recognized as intangible non-current assets and amortized systematically over their 3-year useful life.",
                    'options' => [
                        ['text' => "Capitalized as an intangible asset and amortized systematically over the 3-year period", 'is_correct' => 1],
                        ['text' => "Expensed 100% on the final day of the third year", 'is_correct' => 0],
                        ['text' => "Recorded as an equity reserve with zero expense recognition", 'is_correct' => 0],
                        ['text' => "Ignored on all financial statements", 'is_correct' => 0]
                    ]
                ]
            ];
        } else {
            $pool = $generalPool;
        }

        // Ensure pool satisfies requested count by supplementing with general questions if needed
        if (count($pool) < $numQuestions && $pool !== $generalPool) {
            foreach ($generalPool as $gq) {
                if (count($pool) >= $numQuestions) break;
                $already = false;
                foreach ($pool as $existing) {
                    if ($existing['question'] === $gq['question']) { $already = true; break; }
                }
                if (!$already) {
                    $pool[] = $gq;
                }
            }
        }

        // Return the exact requested number of questions
        $take = min(count($pool), max(1, $numQuestions));
        return array_slice($pool, 0, $take);
    }

    /**
     * Generate custom course test questions using Gemini AI for a course
     */
    public function generateCourseTestQuestions(string $courseTitle, string $courseDescription = '', int $numQuestions = 5): array
    {
        if (empty($this->apiKey)) {
            return $this->getFallbackCourseTestQuestions($courseTitle, $numQuestions);
        }

        $cacheEnabled = env('AI_CACHE_ENABLED', true);
        $cacheKey = 'ai_course_test_' . md5($courseTitle . '_' . $courseDescription . '_' . $numQuestions);
        if ($cacheEnabled) {
            try {
                $cache = \Config\Services::cache();
                if ($cachedQuestions = $cache->get($cacheKey)) {
                    return is_array($cachedQuestions) ? $cachedQuestions : (json_decode($cachedQuestions, true) ?? []);
                }
            } catch (\Throwable $e) {
                // Ignore cache read errors
            }
        }

        $prompt = "You are an expert Instructional Designer & Curriculum Assessment Specialist with expertise in Nigerian and African corporate training.
Generate {$numQuestions} high-quality, professional multiple-choice assessment test questions specifically tailored to evaluate student knowledge for the course: '{$courseTitle}'.
Course Description Context: {$courseDescription}

LOCALIZATION GUIDELINES:
- All monetary and financial calculations or case questions MUST strictly use Nigerian Naira (₦ or NGN) (e.g. ₦150,000, ₦500,000, ₦2,500,000). Never use USD, EUR, or GBP.
- Use authentic Nigerian names for illustrative scenarios (e.g. Ade, Ngozi, Emeka, Folake, Amina, Chidi, Babatunde, Fatima).
- Reference Nigerian business contexts, logistics, and workplace scenarios where applicable.

Respond strictly in valid JSON format matching this exact schema:
[
  {
    \"question\": \"Question stem text here\",
    \"options\": [
      {\"text\": \"Option A text\", \"is_correct\": 1},
      {\"text\": \"Option B text\", \"is_correct\": 0},
      {\"text\": \"Option C text\", \"is_correct\": 0},
      {\"text\": \"Option D text\", \"is_correct\": 0}
    ],
    \"explanation\": \"Brief explanation of why the correct option is right\"
  }
]
Do not include any Markdown code block formatting or backticks, return raw JSON string only.";

        $url = $this->getApiUrl('reasoning') . '?key=' . $this->apiKey;
        $payload = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'temperature' => 0.3,
                'maxOutputTokens' => 4000,
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 75);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) return $this->getFallbackCourseTestQuestions($courseTitle, $numQuestions);

        $json = json_decode($response, true);
        $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $text = trim(preg_replace('/^```(?:json)?|```$/m', '', $text));
        $decoded = json_decode($text, true);

        if (is_array($decoded) && !empty($decoded)) {
            if ($cacheEnabled) {
                try {
                    $cache = \Config\Services::cache();
                    $cache->save($cacheKey, $decoded, 604800);
                } catch (\Throwable $e) {}
            }
            return $decoded;
        }

        return $this->getFallbackCourseTestQuestions($courseTitle, $numQuestions);
    }

    /**
     * Fallback questions if offline or API key missing
     */
    public function getFallbackCourseTestQuestions(string $courseTitle, int $numQuestions = 3): array
    {
        $all = [
            [
                'question' => "What is the primary objective of the '{$courseTitle}' course?",
                'options' => [
                    ['text' => "To build practical, recruiter-ready skills and industry competency", 'is_correct' => 1],
                    ['text' => "To memorize theoretical concepts without practical application", 'is_correct' => 0],
                    ['text' => "To fulfill an optional attendance requirement", 'is_correct' => 0],
                    ['text' => "None of the above", 'is_correct' => 0]
                ],
                'explanation' => "The primary goal is practical skill mastery and verifiable industry competence."
            ],
            [
                'question' => "How should candidates apply the knowledge gained in this course?",
                'options' => [
                    ['text' => "By building portfolio evidence and updating their professional resume", 'is_correct' => 1],
                    ['text' => "By keeping their achievements private", 'is_correct' => 0],
                    ['text' => "By ignoring modern industry standards", 'is_correct' => 0],
                    ['text' => "By delaying practical application indefinitely", 'is_correct' => 0]
                ],
                'explanation' => "Active portfolio building and resume alignment demonstrate verifiable skill mastery."
            ],
            [
                'question' => "What passing score is required on final assessments to earn a verified JobberRecruit certificate?",
                'options' => [
                    ['text' => "70%", 'is_correct' => 1],
                    ['text' => "40%", 'is_correct' => 0],
                    ['text' => "50%", 'is_correct' => 0],
                    ['text' => "60%", 'is_correct' => 0]
                ],
                'explanation' => "Candidates must achieve at least 70% on the final assessment to earn a certificate."
            ]
        ];

        return array_slice($all, 0, max(1, min(count($all), $numQuestions)));
    }
    /**
     * Transcribe audio using Gemini 1.5 Flash multimodal capabilities
     */
    public function transcribeAudio(string $base64Data, string $mimeType = 'audio/webm', ?string $instruction = null): ?string
    {
        if (empty($this->apiKey)) {
            log_message('error', 'AiService::transcribeAudio - GEMINI_API_KEY is missing');
            return null;
        }

        // MediaRecorder uploads often carry codec parameters
        // (audio/webm;codecs=opus) or container aliases (video/webm for an
        // audio-only recording). Gemini wants a clean, supported mime type.
        $mimeType = strtolower(trim(explode(';', $mimeType)[0]));
        if ($mimeType === 'video/webm') {
            $mimeType = 'audio/webm';
        }
        if ($mimeType === 'audio/x-m4a' || $mimeType === 'video/mp4') {
            $mimeType = 'audio/mp4';
        }

        $url = $this->getApiUrl($mimeType === 'application/pdf' ? 'fast' : 'audio') . '?key=' . $this->apiKey;

        $prompt = $instruction ?? "You are an expert transcriptionist. Transcribe the provided audio accurately. Output ONLY the raw transcribed text with proper punctuation. Do not add any introductory, concluding, or explanatory text.";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data' => $base64Data,
                            ],
                        ],
                    ],
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1,
                // Transcription is mechanical: thinking off and a capped
                // output make it several times faster and stop the model
                // spending the response budget on hidden reasoning.
                'maxOutputTokens' => 2048,
                'thinkingConfig' => ['thinkingBudget' => 0],
            ]
        ];

        $maxAttempts = 2;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($err || !$response) {
                log_message('error', "Gemini transcription failed (attempt {$attempt}/{$maxAttempts}): {$err}");
                continue;
            }

            $result = json_decode($response, true);

            if (isset($result['error'])) {
                log_message('error', "Gemini transcription API Error (HTTP {$httpCode}, attempt {$attempt}/{$maxAttempts}): " . json_encode($result['error']));
                if ($httpCode === 400 && isset($payload['generationConfig']['thinkingConfig'])) {
                    // Model rejected the thinking config: drop it and retry
                    // plain rather than failing every transcription.
                    unset($payload['generationConfig']['thinkingConfig']);
                    continue;
                }
                return null;
            }

            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
            return trim($text);
        }

        return null;
    }

    /**
     * Fallback formatter to improve an achievement description preserving context and facts.
     */
    protected function formatStrengthenedDescriptionFallback(string $rawDesc, string $jobTitle = ''): string
    {
        $text = trim($rawDesc);
        if (empty($text)) {
            return "Consistently execute high-priority job responsibilities, driving operational excellence and contributing to key team objectives.";
        }

        // Clean conversational noise or leading prefixes
        $cleaned = preg_replace('/^(?:I\s+(?:am\s+responsible\s+for|was\s+responsible\s+for|am\s+|work\s+as\s+a\s+|work\s+as\s+|help\s+with\s+|do\s+|handle\s+)?)/i', '', $text);
        $cleaned = trim($cleaned, " \t\n\r\0\x0B\"'`*-•");

        // Extract organization/company if mentioned ("for [Company]", "at [Company]")
        $company = '';
        if (preg_match('/\b(?:for|at)\s+([A-Z0-9][A-Za-z0-9\s&.\'-]+)/', $text, $m)) {
            $company = trim($m[1]);
            $company = rtrim($company, '.,;!');
        }

        $context = strtolower($text . ' ' . $jobTitle);

        // 1. HR, Recruitment & Talent Acquisition
        if (preg_match('/\b(recruit|recruiting|recruiter|talent|hiring|headhunt|staffing|sourcing|interviewing|onboard|onboarding)\b/', $context)) {
            $target = $company ? "for {$company}" : "across organizational units";
            return "Execute end-to-end recruitment and talent acquisition initiatives {$target}, proactively sourcing, screening, and onboarding top-tier talent aligned with key business and hiring objectives.";
        }

        // 2. Customer Support & Service
        if (preg_match('/\b(customer|client service|support|complaint|refund|helpdesk|call center|inquir)\b/', $context)) {
            $target = $company ? "for {$company}" : "to ensure client satisfaction";
            return "Deliver high-touch customer support and swift issue resolution {$target}, managing client communications, resolving inquiries, and upholding superior service quality and satisfaction.";
        }

        // 3. Sales, Business Development & Account Management
        if (preg_match('/\b(sale|sales|selling|account executive|business development|client acquisition|revenue|quota|closing)\b/', $context)) {
            $target = $company ? "for {$company}" : "to drive business growth";
            return "Drive revenue expansion and prospective client acquisition strategies {$target}, consistently identifying market opportunities, cultivating key stakeholder relationships, and closing high-value deals.";
        }

        // 4. Marketing, Content & Communications
        if (preg_match('/\b(market|marketing|seo|social media|content|campaign|branding|copywriting|growth|advertis)\b/', $context)) {
            $target = $company ? "at {$company}" : "to maximize brand reach";
            return "Design and implement high-impact marketing campaigns {$target}, boosting audience engagement, optimizing digital channels, and driving measurable brand growth.";
        }

        // 5. Software Engineering & Development
        if (preg_match('/\b(develop|developer|code|coding|software|engineer|engineering|programming|fullstack|backend|frontend|api|devops)\b/', $context)) {
            $target = $company ? "at {$company}" : "for core technical systems";
            return "Architect, develop, and maintain robust software solutions {$target}, writing clean, testable code and optimizing system performance, scalability, and uptime.";
        }

        // 6. UI/UX & Graphic Design
        if (preg_match('/\b(design|ui|ux|graphic|figma|designer|wireframe|prototype|visual)\b/', $context)) {
            $target = $company ? "for {$company}" : "across digital interfaces";
            return "Design intuitive, user-centered digital interfaces and visual experiences {$target}, translating product requirements into elegant wireframes, prototypes, and production-ready assets.";
        }

        // 7. Finance, Accounting & Auditing
        if (preg_match('/\b(finance|accounting|accountant|audit|bookkeeping|tax|payroll|budget|budgeting|ledger)\b/', $context)) {
            $target = $company ? "at {$company}" : "to maintain fiscal integrity";
            return "Oversee financial reporting, reconciliation, and budgetary compliance {$target}, maintaining rigorous fiscal controls and delivering actionable financial insights.";
        }

        // 8. Teaching, Education & Training
        if (preg_match('/\b(teach|teacher|teaching|instructor|tutor|train|trainer|training|curriculum|students|cohort)\b/', $context)) {
            $target = $company ? "at {$company}" : "for learner cohorts";
            return "Deliver engaging instructional curricula and interactive training programs {$target}, evaluating learner progress and fostering an inclusive, high-achievement educational environment.";
        }

        // 9. Healthcare & Nursing
        if (preg_match('/\b(nurse|nursing|patient|clinical|hospital|clinic|healthcare|medical|triage)\b/', $context)) {
            $target = $company ? "at {$company}" : "in clinical settings";
            return "Provide compassionate, evidence-based patient care and clinical coordination {$target}, adhering strictly to medical protocols, safety standards, and multidisciplinary treatment plans.";
        }

        // 10. Operations, Administration & Leadership
        if (preg_match('/\b(manage|manager|management|lead|leadership|supervisor|director|operations|admin|office|logistics)\b/', $context)) {
            $target = $company ? "at {$company}" : "across operational units";
            return "Direct operational workflows and team deliverables {$target}, establishing key performance standards and implementing process improvements to achieve strategic milestones.";
        }

        // Universal factual fallback
        $firstWord = strtolower(strtok($cleaned, " "));
        $actionVerbs = ['lead', 'manage', 'organize', 'coordinate', 'create', 'build', 'conduct', 'supervise', 'handle', 'prepare', 'execute', 'implement'];
        if (in_array($firstWord, $actionVerbs)) {
            return ucfirst($cleaned) . ($company ? " for {$company}" : "") . ", demonstrating strong professional competence and driving consistent operational impact.";
        }

        return "Execute " . lcfirst($cleaned) . ($company ? " for {$company}" : "") . ", applying professional best practices to achieve consistent operational efficiency and organizational objectives.";
    }

    /**
     * Fallback formatter to generate bullet points preserving context and facts.
     */
    protected function formatBulletsFallback(string $rawDesc, string $jobTitle = ''): string
    {
        $text = trim($rawDesc);
        $company = '';
        if (preg_match('/\b(?:for|at)\s+([A-Z0-9][A-Za-z0-9\s&.\'-]+)/', $text, $m)) {
            $company = trim($m[1]);
            $company = rtrim($company, '.,;!');
        }

        $context = strtolower($text . ' ' . $jobTitle);

        if (preg_match('/\b(recruit|recruiter|recruiting|talent|hiring|headhunt|staffing|sourcing)\b/', $context)) {
            $compStr = $company ? " for {$company}" : "";
            return "• Coordinate end-to-end recruitment lifecycle and talent acquisition processes{$compStr}.\n" .
                   "• Proactively source, screen, and assess qualified candidates across diverse role specifications.\n" .
                   "• Collaborate closely with hiring managers to optimize applicant pipelines and reduce time-to-hire.\n" .
                   "• Facilitate structured interview evaluations and provide seamless onboarding support to ensure candidate success.";
        }

        if (preg_match('/\b(customer|client service|support|complaint|refund|helpdesk|call center)\b/', $context)) {
            $compStr = $company ? " for {$company}" : "";
            return "• Deliver professional customer support and prompt inquiry resolution{$compStr}.\n" .
                   "• Manage high-volume customer correspondence across phone, email, and live communication channels.\n" .
                   "• Resolve complex client escalations while maintaining exceptional customer satisfaction metrics.\n" .
                   "• Document common customer pain points and recommend workflow improvements to the leadership team.";
        }

        if (preg_match('/\b(sale|sales|selling|account executive|business development|client acquisition|revenue)\b/', $context)) {
            $compStr = $company ? " for {$company}" : "";
            return "• Drive revenue expansion and prospective client outreach{$compStr}.\n" .
                   "• Manage the full sales cycle from initial lead qualification to contractual close.\n" .
                   "• Cultivate enduring client partnerships and deliver persuasive product presentations.\n" .
                   "• Consistently meet and exceed performance targets through strategic relationship management.";
        }

        // Generic line-by-line fallback preserving candidate's text
        $lines = array_filter(array_map('trim', explode("\n", $text)));
        $bullets = [];
        foreach ($lines as $line) {
            $line = ltrim($line, "*-• \t");
            if (empty($line)) continue;
            $line = preg_replace('/^(?:I\s+(?:was\s+|am\s+)?)/i', '', $line);
            $bullets[] = "• " . ucfirst($line);
        }

        if (count($bullets) <= 1) {
            $compStr = $company ? " for {$company}" : "";
            $cleaned = !empty($bullets) ? $bullets[0] : ("• Execute key responsibilities" . $compStr);
            return $cleaned . "\n" .
                   "• Maintain high standards of quality, accuracy, and compliance across all daily deliverables.\n" .
                   "• Collaborate actively with cross-functional stakeholders to optimize operational efficiency.";
        }

        return implode("\n", $bullets);
    }

    /**
     * Fallback formatter to improve an existing professional summary preserving candidate background.
     */
    protected function formatStrengthenedSummaryFallback(string $existingSummary, string $prompt = ''): string
    {
        // An unavailable AI provider must not add unsupported career claims.
        return trim($existingSummary);
    }
}
