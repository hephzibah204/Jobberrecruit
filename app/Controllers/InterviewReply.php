<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * JobberRecruit — AI Interviewer Comprehension (Gemini)
 *
 * Route: $routes->post('api/interview/reply', 'InterviewReply::respond');
 *
 * THIS IS THE ONE THAT MAKES IT FEEL REAL.
 *
 * The existing frontend picks follow-ups by pattern matching — word count,
 * STAR keywords, presence of digits. It has no idea what the candidate
 * actually SAID. A candidate could answer a leadership question by
 * describing a fishing trip and it would ask them to "quantify the result".
 *
 * This endpoint sends the real question, the real answer, and the real
 * conversation history to Gemini and gets back a genuinely contextual
 * reaction — one that references what the candidate actually said.
 *
 * IN : {
 *        question:   "the question that was asked",
 *        answer:     "what the candidate actually said",
 *        history:    [{q:"...", a:"..."}, ...],   // earlier turns, may be empty
 *        persona:    "corporate-hr",
 *        job:        "Senior Accountant",
 *        field:      "accounting-fundamentals",
 *        itype:      "mixed",
 *        askedFollowUpAlready: false
 *      }
 * OUT: {
 *        "acknowledgement": "short human reaction to what they said",
 *        "followUp": "a probing question, or null to move on",
 *        "onTopic": true
 *      }
 * ON FAILURE: non-200 — frontend falls back to its existing pattern-matched
 *             logic. The interview continues either way.
 */
class InterviewReply extends Controller
{
    private const MODEL = 'gemini-3.5-flash-lite';

    private const TIMEOUT_SECONDS = 15;

    private const PERSONA_VOICE = [
        'corporate-hr'      => 'a warm, professional Nigerian HR business partner. Encouraging but still probing.',
        'big4-partner'      => 'a senior audit partner at a Big Four firm. Direct, expects precision, does not flatter.',
        'startup-founder'   => 'a young Nigerian startup founder. Casual, energetic, impatient with vagueness.',
        'technical-lead'    => 'an engineering lead. Focused on reasoning and trade-offs, not buzzwords.',
        'gov-recruiter'     => 'a formal Nigerian civil service panel member. Procedural, respectful, measured.',
        'banking-recruiter' => 'a banking sector recruiter. Crisp, numbers-focused, professional.',
    ];

    public function respond(): ResponseInterface
    {
        $in = $this->request->getJSON(true) ?? [];

        $question = trim((string)($in['question'] ?? ''));
        $answer   = trim((string)($in['answer'] ?? ''));

        if ($question === '' || $answer === '') {
            return $this->fail('missing question or answer', 400);
        }

        // Cap input — a runaway answer should not blow up cost or latency.
        if (mb_strlen($answer) > 4000) {
            $answer = mb_substr($answer, 0, 4000);
        }

        $persona   = (string)($in['persona'] ?? 'corporate-hr');
        $job       = (string)($in['job'] ?? 'this role');
        $field     = (string)($in['field'] ?? 'general');
        $itype     = (string)($in['itype'] ?? 'behavioral');
        $history   = is_array($in['history'] ?? null) ? $in['history'] : [];
        $alreadyFollowedUp = !empty($in['askedFollowUpAlready']);

        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'Reply: GEMINI_API_KEY not set in .env');
            return $this->fail('api key not configured', 500);
        }

        $prompt = $this->buildPrompt(
            $question, $answer, $history, $persona, $job, $field, $itype, $alreadyFollowedUp
        );

        $body = [
            'contents' => [[
                'parts' => [['text' => $prompt]],
            ]],
            'generationConfig' => [
                'temperature'        => 0.8,
                'maxOutputTokens'    => 500,
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
            log_message('error', 'Reply cURL failure: ' . $curlErr);
            return $this->fail('upstream unreachable', 502);
        }
        if ($httpCode !== 200) {
            log_message('error', "Reply Gemini HTTP {$httpCode}: " . substr($raw, 0, 500));
            return $this->fail('upstream error', 502);
        }

        $decoded = json_decode($raw, true);
        $text    = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if ($text === null) {
            log_message('error', 'Reply: no text part: ' . substr($raw, 0, 400));
            return $this->fail('empty upstream response', 502);
        }

        $parsed = $this->extractJson($text);
        if ($parsed === null) {
            log_message('error', 'Reply: unparseable model output: ' . substr($text, 0, 400));
            return $this->fail('unparseable response', 502);
        }

        $ack      = isset($parsed['acknowledgement']) ? trim((string)$parsed['acknowledgement']) : '';
        $followUp = isset($parsed['followUp']) && $parsed['followUp'] !== null
                    ? trim((string)$parsed['followUp']) : null;
        $onTopic  = array_key_exists('onTopic', $parsed) ? (bool)$parsed['onTopic'] : true;

        if ($ack === '') {
            return $this->fail('empty acknowledgement', 502);
        }
        // A one-word follow-up is a model glitch, not a real question.
        if ($followUp !== null && mb_strlen($followUp) < 10) {
            $followUp = null;
        }
        // Respect the frontend's one-follow-up-per-question rule.
        if ($alreadyFollowedUp) {
            $followUp = null;
        }

        return $this->response->setJSON([
            'acknowledgement' => $ack,
            'followUp'        => $followUp,
            'onTopic'         => $onTopic,
        ]);
    }

    private function extractJson(string $text): ?array
    {
        $text = trim($text);
        if (preg_match('/\{[\s\S]*\}/', $text, $matches)) {
            $text = $matches[0];
        }
        $parsed = json_decode($text, true);
        return is_array($parsed) ? $parsed : null;
    }

    private function buildPrompt(
        string $question, string $answer, array $history, string $persona,
        string $job, string $field, string $itype, bool $alreadyFollowedUp
    ): string {
        $personaDesc = self::PERSONA_VOICE[$persona] ?? self::PERSONA_VOICE['corporate-hr'];

        $historyBlock = '';
        if (!empty($history)) {
            $lines = [];
            // Only the last few turns — enough for real callbacks, not so much
            // that latency and cost climb.
            foreach (array_slice($history, -4) as $turn) {
                $q = trim((string)($turn['q'] ?? ''));
                $a = trim((string)($turn['a'] ?? ''));
                if ($q === '' || $a === '') {
                    continue;
                }
                if (mb_strlen($a) > 600) {
                    $a = mb_substr($a, 0, 600) . '...';
                }
                $lines[] = "You asked: {$q}\nThey answered: {$a}";
            }
            if (!empty($lines)) {
                $historyBlock = "EARLIER IN THIS INTERVIEW:\n" . implode("\n\n", $lines) . "\n\n";
            }
        }

        $followUpRule = $alreadyFollowedUp
            ? 'You have ALREADY asked a follow-up on this question. Set "followUp" to null — do not ask another. Just acknowledge and move on.'
            : 'Decide honestly whether a follow-up is warranted. If the answer was genuinely complete and specific, set "followUp" to null and move on — a real interviewer does not probe every single answer. Only follow up when there is a real gap worth probing.';

        return <<<PROMPT
You are {$personaDesc}

You are based in Nigeria, interviewing for a Nigerian employer. Everything you say must fit the Nigerian workplace — not a US or UK one.

You are interviewing a candidate for the role of: {$job}
Interview type: {$itype}

{$historyBlock}YOU JUST ASKED:
{$question}

THE CANDIDATE ANSWERED:
{$answer}

Respond as the interviewer would, in the moment.

RULES — these matter more than sounding impressive:

1. ACTUALLY READ THE ANSWER. Your acknowledgement must reference something specific they genuinely said. Never produce a generic line that would fit any answer. If they mentioned a number, a company situation, a decision, a person — react to THAT.

2. IF THE ANSWER DOES NOT ADDRESS THE QUESTION, say so politely and set "onTopic" to false. A real interviewer notices when someone dodges or misunderstands. Do not pretend a non-answer was a good answer.

3. IF THEY SAID THEY DON'T KNOW, be gracious. Do not punish honesty. Acknowledge it and move on, or gently offer a smaller version of the question.

4. {$followUpRule}

5. If something they just said genuinely connects to something they said EARLIER in this interview, you may reference that connection — but only if the link is real. Do not invent a connection to seem attentive.

6. Keep the acknowledgement to 1-2 sentences. Spoken aloud, natural, human. No bullet points, no headings, no coaching lecture. You are speaking, not writing a report.

7. Do not score, rate, or grade the answer out loud. No percentages, no "that was a 7/10". Feedback comes later in a written report, not mid-interview.

8. NIGERIAN CONTEXT — HARD REQUIREMENT:
   - Money is ALWAYS naira. Never dollars, pounds or euros.
   - Where professional bodies or institutions come up, use Nigerian ones: ICAN, ANAN, ACCA, CIPM, CITN, COREN, NYSC, CBN, FIRS, PENCOM, NAFDAC.
   - Where working realities come up, use Nigerian ones: power supply and generator costs, Lagos traffic and commute, POS and transfer culture, WhatsApp as a business channel, forex and import delays, state vs federal structures.
   - NEVER reference 401(k), IRS, Social Security, HMRC, NHS, GCSEs, "college" in the American sense, US/UK employment law, or any other US/UK-specific institution or system.
   - Write in Nigerian professional English — natural and clear, not Americanised slang, not stiff British formality.
   - Do not decorate every sentence with local references. The rule is: when context appears, it must be Nigerian context.

OUTPUT — strict JSON only, no markdown fences, no commentary:
{
  "acknowledgement": "your spoken reaction, 1-2 sentences",
  "followUp": "a single probing question, or null",
  "onTopic": true
}
PROMPT;
    }

    private function fail(string $reason, int $code): ResponseInterface
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON(['error' => $reason]);
    }
}
