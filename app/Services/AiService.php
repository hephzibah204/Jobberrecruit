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
        'chat'      => 'gemini-3.5-flash-lite', // Interactive chatbot widget & turns
        'fast'      => 'gemini-3.5-flash-lite', // Fast short text generation, summaries & bullet points
        'balanced'  => 'gemini-3.5-flash-lite', // Career advice, salary negotiation, cover letters
        'reasoning' => 'gemini-3.5-flash-lite', // CV scoring/reviews, interview evaluations, aptitude test generation
        'audio'     => 'gemini-3.1-flash',      // Multimodal audio transcription & speech processing
    ];

    protected ?string $apiKey = null;
    protected string $model = 'gemini-3.5-flash-lite';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->model  = env('GEMINI_MODEL') ?: 'gemini-3.5-flash-lite';
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
    public function generate($prompt, string $task = 'fast', int $customTtl = 0)
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
                'maxOutputTokens' => 1500,
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
        // Build a concise prompt that asks for a single polished summary wrapped in a predictable HTML fragment.
        // We instruct the model to return either a single HTML fragment wrapped in <div class="premium-summary">...</div>
        // OR plain text. The server will sanitize any HTML returned. Avoid markdown or multiple choices.
        $promptParts = [];
        if (!empty($experiences)) {
            $promptParts[] = 'Experiences: ' . implode('; ', $experiences);
        }
        if (!empty($education)) {
            $promptParts[] = 'Education: ' . implode('; ', $education);
        }
        if (!empty($skills)) {
            $skillsList = is_array($skills) ? $skills : array_map('trim', explode(',', (string)$skills));
            $promptParts[] = 'Skills: ' . implode(', ', $skillsList);
        }

        $context = implode("\n", $promptParts);

        $prompt = "You are ResumeAI, a professional resume assistant. Return ONE polished professional resume summary of 2-3 sentences. " .
                  "Return either (A) a single HTML fragment wrapped in <div class=\"premium-summary\"> ... </div> with no attributes on inner tags, or (B) plain text. " .
                  "Do NOT provide multiple options, do NOT use markdown (no ** or *), and do not include scripts or style tags.\n\n";

        if (!empty($context)) {
            $prompt .= $context . "\n\n";
        }

        $prompt .= "Requirements:\n" .
                   "- Output must be concise and recruiter-focused (2-3 sentences).\n" .
                   "- If returning HTML, prefer simple tags: <p>, <br>, <strong>, <em>, <ul>, <ol>, <li>, <div>, <span>.\n" .
                   "- Wrap the fragment in exactly one <div class=\"premium-summary\"> ... </div> when returning HTML.\n" .
                   "- If plain text is returned, do not include any markup.\n" .
                   "Provide only the summary text or the HTML fragment as described, nothing else.";

        return $this->generate($prompt);
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

        $context = "[[MOCK_INTERVIEW_SESSION:{$questionPack}]] You are an experienced hiring manager conducting a mock interview for a '{$jobTitle}' position with '{$candidateName}'. ";
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
                'maxOutputTokens' => 250,
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$err && $httpCode === 200) {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $reply = trim($result['candidates'][0]['content']['parts'][0]['text']);
                return strip_tags(str_replace(['**', '*'], '', $reply));
            }
        }

        return $this->getFallbackNegotiationReply($message, $offerDetails);
    }

    private function getFallbackNegotiationReply($message, $offerDetails)
    {
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
     * Convert text to speech via Gemini's native TTS model. Returns a
     * base64-encoded WAV string, or null if unavailable/failed (callers
     * should fall back to browser speech synthesis).
     */
    public function textToSpeech(string $text, string $voiceName = 'Kore'): ?string
    {
        $text = trim($text);
        if (empty($this->apiKey) || $text === '') {
            return null;
        }

        $ttsModel = env('GEMINI_TTS_MODEL') ?: 'gemini-3.1-flash-tts-preview';
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $ttsModel . ':generateContent?key=' . $this->apiKey;

        $payload = [
            'contents' => [[
                'role'  => 'user',
                'parts' => [['text' => $text]],
            ]],
            'generationConfig' => [
                'responseModalities' => ['AUDIO'],
                'speechConfig' => [
                    'voiceConfig' => [
                        'prebuiltVoiceConfig' => ['voiceName' => $voiceName],
                    ],
                ],
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || ! $response) {
            log_message('error', 'Gemini TTS failed: ' . $err);
            return null;
        }

        $result = json_decode($response, true);
        $inline = $result['candidates'][0]['content']['parts'][0]['inlineData'] ?? null;
        $b64Pcm = $inline['data'] ?? null;
        $mimeType = $inline['mimeType'] ?? 'audio/L16;rate=24000';

        if (! $b64Pcm) {
            log_message('error', 'Gemini TTS returned no audio: ' . ($result['error']['message'] ?? json_encode($result)));
            return null;
        }

        $pcm = base64_decode($b64Pcm);
        $sampleRate = 24000;
        if (preg_match('/rate=(\d+)/', $mimeType, $m)) {
            $sampleRate = (int) $m[1];
        }

        return base64_encode($this->pcmToWav($pcm, $sampleRate));
    }

    /**
     * Wrap raw 16-bit PCM audio in a WAV container so browsers can play it
     * directly via an <audio> element, no client-side decoding needed.
     */
    protected function pcmToWav(string $pcm, int $sampleRate, int $channels = 1, int $bitsPerSample = 16): string
    {
        $byteRate = (int) ($sampleRate * $channels * $bitsPerSample / 8);
        $blockAlign = (int) ($channels * $bitsPerSample / 8);
        $dataSize = strlen($pcm);

        $header = 'RIFF' . pack('V', 36 + $dataSize) . 'WAVE'
            . 'fmt ' . pack('V', 16)
            . pack('v', 1)
            . pack('v', $channels)
            . pack('V', $sampleRate)
            . pack('V', $byteRate)
            . pack('v', $blockAlign)
            . pack('v', $bitsPerSample)
            . 'data' . pack('V', $dataSize);

        return $header . $pcm;
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
        if (stripos($prompt, 'professional resume summary') !== false || (stripos($prompt, 'Experiences:') !== false && stripos($prompt, 'Skills:') !== false)) {
            // Extract experiences and skills
            $experiencesStr = 'your professional experience';
            $skillsStr = 'core industry skills';
            if (preg_match('/Experiences:\s*(.*)/i', $prompt, $matches)) {
                $experiencesStr = trim(explode("\n", $matches[1])[0]);
            }
            if (preg_match('/Skills:\s*(.*)/i', $prompt, $matches)) {
                $skillsStr = trim(explode("\n", $matches[1])[0]);
            }

            return "Results-driven professional with expertise in {$skillsStr}. Proven track record of delivering high-impact solutions, optimizing operational workflows, and driving efficiency based on experience in {$experiencesStr}. Skilled at collaborating with cross-functional teams to accelerate project delivery and achieve strategic goals.";
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
            return [];
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
                'maxOutputTokens' => 2000,
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) return [];

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

        return [];
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);

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
    public function transcribeAudio(string $base64Data, string $mimeType = 'audio/webm'): ?string
    {
        if (empty($this->apiKey)) {
            log_message('error', 'AiService::transcribeAudio - GEMINI_API_KEY is missing');
            return null;
        }

        $url = $this->getApiUrl('audio') . '?key=' . $this->apiKey;

        $prompt = "You are an expert transcriptionist. Transcribe the provided audio accurately. Output ONLY the raw transcribed text with proper punctuation. Do not add any introductory, concluding, or explanatory text.";

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
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$response) {
            log_message('error', 'Gemini transcription failed: ' . $err);
            return null;
        }

        $result = json_decode($response, true);
        
        if (isset($result['error'])) {
            log_message('error', 'Gemini transcription API Error: ' . json_encode($result['error']));
            return null;
        }

        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
        return trim($text);
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
        $text = trim($existingSummary);
        $textClean = preg_replace('/^(?:I\s+(?:am\s+(?:an?|experienced)|have\s+been|work\s+as)?\s*)/i', '', $text);
        $textClean = rtrim(trim($textClean), '.');

        if (preg_match('/^(?:experienced|skilled|certified|dedicated|passionate|seasoned)\s+/i', $textClean)) {
            return "Accomplished and " . lcfirst($textClean) . ". Proven track record of delivering measurable outcomes, optimizing workflows, and collaborating effectively across teams to achieve strategic goals.";
        }

        return "Results-driven professional with proven expertise as a " . lcfirst($textClean) . ". Recognized for strategic problem-solving, high-impact execution, and strong cross-functional collaboration. Dedicated to driving operational excellence and delivering measurable value in dynamic organizational environments.";
    }
}
