<?php

namespace App\Services;

class AiService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = getenv('GEMINI_API_KEY');
        $this->model  = getenv('GEMINI_MODEL') ?: 'gemini-2.5-flash';
    }

    /**
     * Generate content using Gemini API
     */
    public function generate($prompt)
    {
        if (empty($this->apiKey)) {
            return "AI Service is not configured. Please add GEMINI_API_KEY to your .env file.";
        }

        $url = $this->getApiUrl() . '?key=' . $this->apiKey;

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return "AI Error: " . $err;
        }

        $result = json_decode($response, true);
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        return "AI Error: " . ($result['error']['message'] ?? 'Unknown error occurred');
    }

    /**
     * Generate a professional summary
     */
    public function generateProfessionalSummary($experiences, $skills)
    {
        $prompt = "Generate a professional resume summary (2-3 sentences) based on the following experiences and skills:\n";
        $prompt .= "Experiences: " . implode(', ', $experiences) . "\n";
        $prompt .= "Skills: " . implode(', ', $skills) . "\n";
        $prompt .= "The summary should be impactful and professional.";

        return $this->generate($prompt);
    }

    /**
     * Improve a job description
     */
    public function improveDescription($description)
    {
        $prompt = "Improve the following job description to be more professional and include action verbs. Use bullet points if appropriate:\n\n";
        $prompt .= $description;

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

        $context = "You are an experienced hiring manager conducting a mock interview for a '{$jobTitle}' position with '{$candidateName}'. ";
        if ($companyName !== '') {
            $context .= "Company: {$companyName}. ";
        }
        $context .= "Difficulty level: {$difficulty}. ";
        $context .= 'Question pack: ' . $this->getQuestionPackContext($questionPack) . '. ';
        $context .= "Interview mode: {$interviewMode}. ";
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
        $context .= "Use the job description, requirements, submitted application context, and candidate profile as the scoring yardstick. ";
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

        $prompt = "You are a senior interview coach reviewing a completed mock interview for '{$candidateName}' applying for '{$jobTitle}'. ";
        if ($companyName !== '') {
            $prompt .= "Company: {$companyName}. ";
        }
        $prompt .= "Difficulty level: {$difficulty}. ";
        $prompt .= 'Question pack: ' . $this->getQuestionPackContext($questionPack) . '. ';
        $prompt .= "Interview mode: {$interviewMode}. ";
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
        $context = "You are a tough but fair HR representative in a salary negotiation. ";
        $context .= "The current offer details are: '{$offerDetails}'. ";
        $context .= "Engage in a realistic negotiation. Provide tips to the user on how they can improve their negotiation stance.";
        
        return $this->getChatResponse($message, $history, $context);
    }

    /**
     * Personalized Career Advice
     */
    public function getCareerAdvice($candidateProfile)
    {
        $prompt = "Act as a senior career coach. Based on this candidate profile: '{$candidateProfile}', provide 3-5 personalized career growth tips, recommended skills to learn, and potential career paths.";
        
        return $this->generate($prompt);
    }
    /**
     * Get a chat response with history and context
     */
    public function getChatResponse($message, $history = [], $context = '')
    {
        if (empty($this->apiKey)) {
            return "Chatbot is currently offline (API key missing).";
        }

        $url = $this->getApiUrl() . '?key=' . $this->apiKey;

        // Construct contents with history
        $contents = [];
        
        // System instruction as first message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => "You are JobberRecruit AI Assistant. Context: " . $context . ". Keep your responses helpful, concise, and professional. Use markdown for formatting if needed."]]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => "Understood. I am ready to help as JobberRecruit AI Assistant."]]
        ];

        // Add history
        foreach ($history as $chat) {
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
                'temperature' => 0.7,
                'maxOutputTokens' => 500,
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return "AI Error: " . $err;
        }

        $result = json_decode($response, true);
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        return "AI Error: " . ($result['error']['message'] ?? 'Unknown error occurred');
    }

    protected function getApiUrl(): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta/models/' . $this->model . ':generateContent';
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
}
