<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * JobberRecruit — AI Interview Question Generator (Gemini 2.5 Flash)
 *
 * Route:  $routes->post('api/interview/questions', 'InterviewQuestions::generate');
 *
 * Contract (must not change — the frontend depends on it exactly):
 *   IN : { job, field, itype, diff, exp, count, focus, salaryBand, company, arrangement }
 *   OUT: { "questions": ["...", "...", ...] }   // length === count
 *   ON ANY FAILURE: non-200 or missing "questions" — the frontend then
 *   silently uses its own curated question banks. Never return a partial
 *   or padded list; a clean failure is better than a degraded interview.
 */
class InterviewQuestions extends Controller
{
    private const TIMEOUT_SECONDS = 15;

    private const MODEL = 'gemini-3.5-flash-lite';

    /** Human-readable field labels — the slug alone is a poor prompt signal. */
    private const FIELD_LABELS = [
        'software-developer'       => 'Software Development & Engineering',
        'data-analysis'            => 'Data & Business Analysis',
        'accounting-fundamentals'  => 'Accounting and Finance',
        'digital-marketing'        => 'Digital Marketing',
        'social-media-content'     => 'Social Media & Content Creation',
        'office-admin'             => 'Office & Administration',
        'sales-business-dev'       => 'Sales & Business Development',
        'customer-service'         => 'Customer Service & Support',
        'human-resources'          => 'Human Resources & Recruitment',
        'engineering-technical'    => 'Engineering & Technical Trades',
        'logistics-supply-chain'   => 'Logistics & Supply Chain',
        'legal-compliance'         => 'Legal & Compliance',
        'healthcare-medical'       => 'Healthcare & Medical',
        'education-training'       => 'Education & Training',
        'hospitality'              => 'Hospitality, Hotel & Restaurant',
        'manufacturing-production' => 'Manufacturing & Production',
        'it-support'               => 'IT Support & Helpdesk',
        'project-management'       => 'Project & Product Management',
        'design-ux'                => 'Design (UX/UI & Graphic)',
        'general'                  => 'General / Cross-functional',
    ];

    private const SALARY_BANDS = [
        'b1' => 'below NGN 150,000 per month',
        'b2' => 'NGN 150,000 to 300,000 per month',
        'b3' => 'NGN 300,000 to 600,000 per month',
        'b4' => 'NGN 600,000 to 1,000,000 per month',
        'b5' => 'above NGN 1,000,000 per month',
    ];

    public function generate(): ResponseInterface
    {
        $in = $this->request->getJSON(true) ?? [];

        // ---- validate input -------------------------------------------------
        $job = trim((string)($in['job'] ?? ''));
        if ($job === '') {
            return $this->fail('missing job title', 400);
        }

        $count = (int)($in['count'] ?? 8);
        $count = max(3, min(9, $count));            // mirror the frontend's own clamp

        $field       = (string)($in['field'] ?? 'general');
        $itype       = (string)($in['itype'] ?? 'behavioral');
        $diff        = (string)($in['diff'] ?? 'medium');
        $exp         = (string)($in['exp'] ?? 'mid');
        $focus       = (string)($in['focus'] ?? 'balanced');
        $salaryBand  = (string)($in['salaryBand'] ?? '');
        $company     = (string)($in['company'] ?? 'any');
        $arrangement = (string)($in['arrangement'] ?? 'onsite');

        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'GEMINI_API_KEY is not set in .env');
            return $this->fail('api key not configured', 500);
        }

        // ---- build the prompt ----------------------------------------------
        $prompt = $this->buildPrompt(
            $job, $field, $itype, $diff, $exp, $count,
            $focus, $salaryBand, $company, $arrangement
        );

        // ---- call Gemini ----------------------------------------------------
        $body = [
            'contents' => [[
                'parts' => [['text' => $prompt]],
            ]],
            'generationConfig' => [
                'temperature'        => 0.9,   // variety across repeat sessions
                'maxOutputTokens'    => 1400,
                'response_mime_type' => 'application/json',
            ],
        ];

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/'
             . self::MODEL . ':generateContent';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($body),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-goog-api-key: ' . $apiKey,
            ],
            CURLOPT_TIMEOUT        => self::TIMEOUT_SECONDS,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);

        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            log_message('error', 'Gemini cURL failure: ' . $curlErr);
            return $this->fail('upstream unreachable', 502);
        }
        if ($httpCode !== 200) {
            // Log the actual upstream body — this is what tells you whether it's
            // a bad key (403), quota (429), or a malformed request (400).
            log_message('error', "Gemini HTTP {$httpCode}: " . substr($raw, 0, 500));
            return $this->fail('upstream error', 502);
        }

        // ---- parse the response ---------------------------------------------
        $decoded = json_decode($raw, true);
        $text    = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if ($text === null) {
            log_message('error', 'Gemini returned no text part: ' . substr($raw, 0, 500));
            return $this->fail('empty upstream response', 502);
        }

        $questions = $this->extractQuestions($text);

        if (count($questions) < min(3, $count)) {
            log_message('error', 'Gemini returned too few usable questions: ' . substr($text, 0, 400));
            return $this->fail('insufficient questions', 502);
        }

        // Trim to the exact count. Never pad — a short-but-real list is fine,
        // padding with filler would be worse than the curated fallback.
        $questions = array_slice($questions, 0, $count);

        return $this->response->setJSON(['questions' => $questions]);
    }

    /**
     * Pull a clean string array out of the model's reply.
     * Handles: raw JSON array, JSON wrapped in ```json fences, or an object
     * with a "questions" key — models drift between these.
     */
    private function extractQuestions(string $text): array
    {
        $text = trim($text);
        if (preg_match('/\{[\s\S]*\}|\[[\s\S]*\]/', $text, $matches)) {
            $text = $matches[0];
        }

        $parsed = json_decode($text, true);

        if (is_array($parsed) && isset($parsed['questions']) && is_array($parsed['questions'])) {
            $parsed = $parsed['questions'];
        }

        if (!is_array($parsed)) {
            return [];
        }

        $out = [];
        foreach ($parsed as $q) {
            if (!is_string($q)) {
                continue;
            }
            $q = trim($q);
            // Strip any leading numbering the model added despite instructions
            $q = preg_replace('/^\s*\d+[\.\)]\s*/', '', $q);
            // mbstring is not guaranteed on shared cPanel hosting — degrade
            // gracefully rather than fataling on a missing extension.
            $len = function_exists('mb_strlen') ? mb_strlen($q) : strlen($q);
            if ($len > 10) {
                $out[] = $q;
            }
        }
        return $out;
    }

    private function buildPrompt(
        string $job, string $field, string $itype, string $diff, string $exp,
        int $count, string $focus, string $salaryBand, string $company,
        string $arrangement
    ): string {
        $fieldLabel = self::FIELD_LABELS[$field] ?? 'General / Cross-functional';

        $typeRule = match ($itype) {
            'technical'  => "ALL questions must be technical, probing real working knowledge of {$fieldLabel}.",
            'leadership' => "Focus on leadership: managing people, difficult decisions, conflict, accountability. These stay universal — do NOT make them {$fieldLabel}-specific.",
            'mixed'      => "Mix roughly half behavioural/situational questions with half technical questions specific to {$fieldLabel}. Question 1 must be a general opener about the candidate themselves.",
            default      => "All questions are behavioural or situational — about how the candidate has worked, decided, and handled real situations. These stay universal; do NOT make them {$fieldLabel}-specific.",
        };

        $diffRule = match ($diff) {
            'easy' => 'Keep questions approachable and clearly worded — this is an early-confidence practice session.',
            'hard' => 'Make questions demanding and probing, the kind a senior panel would ask. Expect the candidate to defend their reasoning.',
            default => 'Pitch questions at a standard professional interview level.',
        };

        $expRule = match ($exp) {
            'entry'  => 'The candidate is entry-level or a recent graduate. Do NOT ask about managing teams or years of experience they cannot have.',
            'senior' => 'The candidate is senior. Ask about scale, ownership, strategic judgement, and mentoring others.',
            default  => 'The candidate is mid-level, with a few years of real working experience.',
        };

        $focusRule = match ($focus) {
            'star'       => 'Bias strongly toward questions that invite a STAR-structured answer ("tell me about a time when...").',
            'roleskills' => "Weight the set toward practical {$fieldLabel} skills over general questions.",
            'culture'    => 'Bias toward motivation, values, working style, and fit.',
            'salary'     => 'At least one question MUST be genuine salary-negotiation practice.',
            default      => 'Keep a balanced spread of question styles.',
        };

        $salaryRule = '';
        if ($salaryBand !== '' && isset(self::SALARY_BANDS[$salaryBand])) {
            $salaryRule = "If you generate a salary or negotiation question, anchor it to the candidate's stated target of "
                        . self::SALARY_BANDS[$salaryBand]
                        . ' — reference that actual range rather than a generic figure.';
        }

        $companyRule = match ($company) {
            'startup'       => 'Frame the setting as a fast-moving startup: ownership, ambiguity, wearing many hats.',
            'multinational' => 'Frame the setting as a structured multinational: process, cross-team coordination, compliance.',
            'sme'           => 'Frame the setting as a Nigerian SME: resourcefulness, breadth of responsibility.',
            'government'    => 'Frame the setting as a public-sector body: due process, documentation, accountability.',
            default         => '',
        };

        $arrangementRule = in_array($arrangement, ['remote', 'hybrid'], true)
            ? "The role is {$arrangement}; one question may probe self-management or remote collaboration."
            : '';

        return <<<PROMPT
You are an experienced Nigerian recruiter, based in Nigeria, preparing an interview for a candidate practising for a real job interview with a Nigerian employer.

Everything you write must be grounded in the Nigerian workplace. This is not a US or UK interview. See the NIGERIAN CONTEXT rules below — they override any default assumptions you might otherwise bring.

TARGET JOB TITLE: {$job}
TECHNICAL FIELD: {$fieldLabel}

Generate exactly {$count} interview questions.

HOW TO USE JOB TITLE vs FIELD — these are two separate signals:
- The JOB TITLE drives personalisation: the candidate's career narrative, why this role, their motivation.
- The FIELD drives the subject matter of any technical question.
If they appear to disagree, that is deliberate — the candidate chose both. Honour each one for its own purpose. Do NOT average them into something vague, and do NOT ignore one.

{$typeRule}
{$diffRule}
{$expRule}
{$focusRule}
{$salaryRule}
{$companyRule}
{$arrangementRule}

NIGERIAN CONTEXT — HARD REQUIREMENT, NOT A PREFERENCE:
- This candidate is interviewing for a job IN NIGERIA, with a Nigerian employer. Every question must make sense to someone working in Lagos, Abuja, Port Harcourt or Kano — not New York or London.
- Currency is ALWAYS naira (₦). Never dollars, never pounds, never euros. If a question involves money, budgets, revenue, or salary, express it in naira at realistic Nigerian levels.
- Use Nigerian professional reference points where they genuinely fit: NYSC, ICAN, ANAN, ACCA, CIPM, CITN, COREN, NUC, WAEC/JAMB, PENCOM, FIRS, CBN, NAFDAC, SON, the Nigerian Labour Act, state vs federal structures.
- Use Nigerian business realities where relevant: power supply and generator/diesel costs, fuel and logistics costs, multiple bank apps and transfer culture, POS agents, WhatsApp as a primary business channel, traffic and commute realities in Lagos, import/customs delays, forex scarcity and its effect on pricing, informal-sector interaction, family and community obligations around work.
- NEVER reference: 401(k), IRS, Social Security, HMRC, NHS, NI numbers, GCSEs, "college" in the American sense, dollars/pounds, US or UK employment law, Thanksgiving, Black Friday framing, US state names, or any other US/UK-specific institution, holiday, or system. If you catch yourself writing one, replace it with the Nigerian equivalent.
- Sector examples should be Nigerian-relevant: banking and fintech, oil and gas, telecoms, FMCG, agriculture and agribusiness, logistics, education, healthcare, public sector, hospitality.
- Write in Nigerian professional English. Natural and clear, not Americanised slang and not stiff British formality.
- The point is realism, not decoration: do not jam a local reference into every single question just to prove local knowledge. A question about teamwork can simply be about teamwork. But when context IS present, it must be Nigerian context.
- Write questions a real human recruiter would speak aloud. Natural, direct, conversational.
- Each question must be self-contained and answerable in 1-3 minutes.
- No preamble, no numbering, no commentary, no headings.
- Do not repeat the same underlying question in different words.

OUTPUT FORMAT — this is strict:
Return ONLY a JSON array of exactly {$count} strings. Nothing else. No markdown fences, no explanation.

Example of the required shape:
["First question here?", "Second question here?"]
PROMPT;
    }

    private function fail(string $reason, int $code): ResponseInterface
    {
        // The frontend only checks for a non-200 / missing "questions" key and
        // then falls back silently. The reason here is for YOUR logs and for
        // manual curl testing — the candidate never sees it.
        return $this->response
            ->setStatusCode($code)
            ->setJSON(['error' => $reason]);
    }
}
