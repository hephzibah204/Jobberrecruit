<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * JobberRecruit — AI Interviewer Voice (Gemini TTS)
 *
 * Route: $routes->post('api/interview/tts', 'InterviewVoice::speak');
 *
 * IN : { text: "...", persona: "corporate-hr" }
 * OUT: audio/wav binary
 * ON FAILURE: non-200 — the frontend silently falls back to the browser's
 *             built-in speech synthesis. The interview never breaks.
 *
 * WHY WAV IS BUILT HERE: Gemini returns RAW PCM (signed 16-bit, 24kHz, mono)
 * with no container. Browsers cannot play raw PCM from an <audio> tag. Most
 * integration guides tell you to shell out to ffmpeg — we don't, because
 * shared cPanel hosting usually has no ffmpeg. A WAV header is 44 bytes of
 * plain bytes; we prepend it in PHP. No external binary needed.
 *
 * CACHING: fixed lines (greetings, acknowledgements, the intro sequence)
 * repeat every single session. They are cached to disk after first
 * synthesis, so you pay for them once, not once per candidate.
 */
class InterviewVoice extends Controller
{
    private const MODEL = 'gemini-3.1-flash-tts-preview';
    // Upgrade to 'gemini-3.1-flash-tts-preview' for better quality at higher
    // cost — same request shape, just change this line.

    private const TIMEOUT_SECONDS = 8;

    private const SAMPLE_RATE = 24000;  // Gemini TTS always returns 24kHz mono
    private const CHANNELS    = 1;
    private const BITS        = 16;

    /** Cache lives 30 days; fixed lines rarely change. */
    private const CACHE_TTL = 2592000;

    /**
     * Persona → Gemini prebuilt voice + a style instruction.
     *
     * Gemini TTS accepts natural-language style direction in the prompt
     * itself, which is how we get six DIFFERENT-sounding interviewers
     * rather than one voice at six pitches (which is what the browser
     * engine was doing).
     *
     * Voice names are Gemini prebuilt voices. Audition them in Google AI
     * Studio and swap freely — only the voiceName string changes.
     */
    private const PERSONA_VOICES = [
        'corporate-hr' => [
            'voice' => 'Kore',
            'style' => 'Speak warmly and professionally, like an experienced Nigerian HR business partner putting a candidate at ease. Measured pace, clear articulation.',
        ],
        'big4-partner' => [
            'voice' => 'Charon',
            'style' => 'Speak with the calm authority of a senior audit partner. Deliberate, unhurried, precise. Slightly formal.',
        ],
        'startup-founder' => [
            'voice' => 'Puck',
            'style' => 'Speak energetically and casually, like a young Nigerian startup founder. Quicker pace, informal, direct.',
        ],
        'technical-lead' => [
            'voice' => 'Fenrir',
            'style' => 'Speak thoughtfully and evenly, like an engineering lead thinking through a problem. Neutral, focused, not performative.',
        ],
        'gov-recruiter' => [
            'voice' => 'Orus',
            'style' => 'Speak formally and deliberately, like a senior Nigerian civil service panel member. Slow, measured, procedural.',
        ],
        'banking-recruiter' => [
            'voice' => 'Aoede',
            'style' => 'Speak crisply and efficiently, like a banking sector recruiter who values precision. Professional, brisk, articulate.',
        ],
    ];

    public function speak(): ResponseInterface
    {
        $in   = $this->request->getJSON(true) ?? [];
        $text = trim((string)($in['text'] ?? ''));

        if ($text === '') {
            return $this->fail('missing text', 400);
        }
        if (mb_strlen($text) > 1200) {
            // Guard against a runaway request racking up cost.
            $text = mb_substr($text, 0, 1200);
        }

        $persona = (string)($in['persona'] ?? 'corporate-hr');
        $cfg     = self::PERSONA_VOICES[$persona] ?? self::PERSONA_VOICES['corporate-hr'];

        // ---- cache check ----------------------------------------------------
        $cacheKey  = 'tts_' . md5($persona . '|' . self::MODEL . '|' . $text);
        $cacheFile = WRITEPATH . 'tts_cache/' . $cacheKey . '.wav';

        if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < self::CACHE_TTL) {
            return $this->audioResponse(file_get_contents($cacheFile), true);
        }

        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'TTS: GEMINI_API_KEY not set in .env');
            return $this->fail('api key not configured', 500);
        }

        // ---- call Gemini ----------------------------------------------------
        // The style instruction goes in the prompt text itself — this is how
        // Gemini TTS steers delivery. The literal line to speak follows it.
        $prompt = $cfg['style'] . "\n\nSay exactly this and nothing else: " . $text;

        $body = [
            'contents' => [[
                'parts' => [['text' => $prompt]],
            ]],
            'generationConfig' => [
                'responseModalities' => ['AUDIO'],
                'speechConfig' => [
                    'voiceConfig' => [
                        'prebuiltVoiceConfig' => ['voiceName' => $cfg['voice']],
                    ],
                ],
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
            CURLOPT_CONNECTTIMEOUT => 3,
        ]);

        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            log_message('error', 'TTS cURL failure: ' . $curlErr);
            return $this->fail('upstream unreachable', 502);
        }
        if ($httpCode !== 200) {
            log_message('error', "TTS Gemini HTTP {$httpCode}: " . substr($raw, 0, 500));
            return $this->fail('upstream error', 502);
        }

        $decoded = json_decode($raw, true);
        $b64     = $decoded['candidates'][0]['content']['parts'][0]['inlineData']['data'] ?? null;

        if ($b64 === null) {
            log_message('error', 'TTS: no inlineData in response: ' . substr($raw, 0, 500));
            return $this->fail('no audio returned', 502);
        }

        $pcm = base64_decode($b64, true);
        if ($pcm === false || strlen($pcm) < 100) {
            log_message('error', 'TTS: base64 decode failed or audio too short');
            return $this->fail('invalid audio data', 502);
        }

        $wav = $this->pcmToWav($pcm);

        // ---- cache it -------------------------------------------------------
        $dir = dirname($cacheFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (is_dir($dir) && is_writable($dir)) {
            @file_put_contents($cacheFile, $wav);
        } else {
            log_message('warning', 'TTS cache dir not writable: ' . $dir);
        }

        return $this->audioResponse($wav, false);
    }

    /**
     * Prepend a 44-byte canonical WAV header to raw PCM.
     * Avoids any ffmpeg dependency — important on shared cPanel hosting.
     */
    private function pcmToWav(string $pcm): string
    {
        $dataLen   = strlen($pcm);
        $byteRate  = self::SAMPLE_RATE * self::CHANNELS * (self::BITS / 8);
        $blockAlign = self::CHANNELS * (self::BITS / 8);

        $header  = 'RIFF';
        $header .= pack('V', 36 + $dataLen);   // ChunkSize
        $header .= 'WAVE';
        $header .= 'fmt ';
        $header .= pack('V', 16);              // Subchunk1Size (PCM)
        $header .= pack('v', 1);               // AudioFormat = PCM
        $header .= pack('v', self::CHANNELS);
        $header .= pack('V', self::SAMPLE_RATE);
        $header .= pack('V', $byteRate);
        $header .= pack('v', $blockAlign);
        $header .= pack('v', self::BITS);
        $header .= 'data';
        $header .= pack('V', $dataLen);

        return $header . $pcm;
    }

    private function audioResponse(string $wav, bool $cached): ResponseInterface
    {
        return $this->response
            ->setHeader('Content-Type', 'audio/wav')
            ->setHeader('Content-Length', (string)strlen($wav))
            ->setHeader('X-TTS-Cache', $cached ? 'hit' : 'miss')
            ->setBody($wav);
    }

    private function fail(string $reason, int $code): ResponseInterface
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON(['error' => $reason]);
    }
}
