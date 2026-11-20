<?php

namespace App\Controllers;

use App\Services\EdgeTtsService;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * JobberRecruit — AI Interviewer Voice (Edge TTS Nigerian Neural Voices)
 *
 * Route: $routes->post('api/interview/tts', 'InterviewVoice::speak');
 *
 * IN : { text: "...", persona: "corporate-hr" }
 * OUT: audio/mpeg binary
 * ON FAILURE: non-200 — the frontend silently falls back to the browser's
 *             built-in speech synthesis. The interview never breaks.
 *
 * VOICES: Authentic Microsoft Edge Neural Nigerian voices:
 *         - en-NG-EzinneNeural (Female) -> corporate-hr (Chioma), banking-recruiter (Ngozi)
 *         - en-NG-AbeoNeural (Male)   -> big4-partner (Bankole), startup-founder (Tomiwa),
 *                                        technical-lead (Emeka), gov-recruiter (Alhaji Musa)
 */
class InterviewVoice extends Controller
{
    private EdgeTtsService $edgeTts;

    public function __construct()
    {
        $this->edgeTts = new EdgeTtsService();
    }

    public function speak(): ResponseInterface
    {
        $in   = $this->request->getJSON(true) ?? [];
        $text = trim((string)($in['text'] ?? ''));

        if ($text === '') {
            return $this->fail('missing text', 400);
        }
        if (mb_strlen($text) > 1200) {
            $text = mb_substr($text, 0, 1200);
        }

        $persona = (string)($in['persona'] ?? 'corporate-hr');

        // ---- 1. Primary: Edge-TTS Nigerian Voices ----------------------------
        $audioFile = $this->edgeTts->synthesizeToFile($text, $persona);
        if ($audioFile && is_file($audioFile)) {
            $audioData = file_get_contents($audioFile);
            return $this->response
                ->setHeader('Content-Type', 'audio/mpeg')
                ->setHeader('Content-Length', (string)strlen($audioData))
                ->setHeader('X-TTS-Engine', 'EdgeTTS-Nigerian')
                ->setBody($audioData);
        }

        // ---- 2. No Gemini fallback (owner decision 2026-11-19): audio is
        // Edge TTS Nigerian voices only. On failure the frontend falls back
        // to the browser's built-in speech synthesis.

        return $this->fail('synthesis unavailable', 503);
    }



    private function fail(string $reason, int $code): ResponseInterface
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON(['error' => $reason]);
    }
}
