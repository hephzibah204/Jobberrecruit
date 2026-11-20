<?php

namespace App\Services;

class EdgeTtsService
{
    private const CACHE_TTL = 2592000; // 30 days

    /**
     * Persona -> Microsoft Edge Neural Voice & tuning
     * Authentic Nigerian accents:
     * - en-NG-EzinneNeural: Female Nigerian accent (warm, articulate, professional)
     * - en-NG-AbeoNeural: Male Nigerian accent (grounded, natural, professional)
     */
    private const PERSONA_CONFIG = [
        'corporate-hr' => [
            'voice' => 'en-NG-EzinneNeural',
            'rate'  => '+0%',
            'pitch' => '+0Hz',
            'gender'=> 'female',
        ],
        'banking-recruiter' => [
            'voice' => 'en-NG-EzinneNeural',
            'rate'  => '+2%',
            'pitch' => '+0Hz',
            'gender'=> 'female',
        ],
        'big4-partner' => [
            'voice' => 'en-NG-AbeoNeural',
            'rate'  => '-2%',
            'pitch' => '-1Hz',
            'gender'=> 'male',
        ],
        'startup-founder' => [
            'voice' => 'en-NG-AbeoNeural',
            'rate'  => '+3%',
            'pitch' => '+1Hz',
            'gender'=> 'male',
        ],
        'technical-lead' => [
            'voice' => 'en-NG-AbeoNeural',
            'rate'  => '+0%',
            'pitch' => '+0Hz',
            'gender'=> 'male',
        ],
        'gov-recruiter' => [
            'voice' => 'en-NG-AbeoNeural',
            'rate'  => '-4%',
            'pitch' => '-2Hz',
            'gender'=> 'male',
        ],
    ];

    /**
     * Get voice configuration for a persona
     */
    public function getPersonaConfig(string $persona): array
    {
        return self::PERSONA_CONFIG[$persona] ?? self::PERSONA_CONFIG['corporate-hr'];
    }

    /**
     * Synthesize text to speech using Edge-TTS Nigerian voices.
     * Returns the MP3 file path if successful, or null on failure.
     */
    public function synthesizeToFile(string $text, string $persona = 'corporate-hr'): ?string
    {
        $text = trim($text);
        if ($text === '') {
            return null;
        }

        $cfg = $this->getPersonaConfig($persona);
        $voice = $cfg['voice'];
        $rate = $cfg['rate'];
        $pitch = $cfg['pitch'];

        $cacheDir = WRITEPATH . 'tts_cache';
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        $cacheKey = 'edge_' . md5($persona . '|' . $voice . '|' . $rate . '|' . $pitch . '|' . $text);
        $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.mp3';

        // Check disk cache
        if (is_file($cacheFile) && filesize($cacheFile) > 200 && (time() - filemtime($cacheFile)) < self::CACHE_TTL) {
            return $cacheFile;
        }

        // Primary: shell commands — the cPanel Python-App virtualenv's
        // edge-tts (auto-discovered below) is the path verified working
        // in production 2026-11-19. The pure-PHP client is the last
        // resort at the bottom of this method.

        // Temporary input file to avoid CLI escaping / quoting issues
        $tempInput = tempnam(sys_get_temp_dir(), 'edge_tts_');
        file_put_contents($tempInput, $text);

        $pythonScript = ROOTPATH . 'scripts' . DIRECTORY_SEPARATOR . 'edge_tts_synth.py';

        $commands = [
            // Dedicated Python script runner (fastest & handles rate/pitch via edge_tts Python module)
            'python ' . escapeshellarg($pythonScript) . ' ' . escapeshellarg($tempInput) . ' ' . escapeshellarg($voice) . ' ' . escapeshellarg($cacheFile) . ' ' . escapeshellarg($rate) . ' ' . escapeshellarg($pitch),
            'py ' . escapeshellarg($pythonScript) . ' ' . escapeshellarg($tempInput) . ' ' . escapeshellarg($voice) . ' ' . escapeshellarg($cacheFile) . ' ' . escapeshellarg($rate) . ' ' . escapeshellarg($pitch),
            // Fallback directly to edge-tts executable
            'edge-tts --voice ' . escapeshellarg($voice) . ' --file ' . escapeshellarg($tempInput) . ' --write-media ' . escapeshellarg($cacheFile),
            'python -m edge_tts --voice ' . escapeshellarg($voice) . ' --file ' . escapeshellarg($tempInput) . ' --write-media ' . escapeshellarg($cacheFile),
        ];

        // cPanel "Setup Python App" (CloudLinux) virtualenvs: if a venv
        // python exists, prefer it — it carries the edge_tts module from
        // pip even though the system PATH has no python at all (every
        // bare `python` attempt on this host exits 127).
        $homeDir = dirname(rtrim(ROOTPATH, DIRECTORY_SEPARATOR));
        $venvPythons = array_merge(
            glob($homeDir . '/virtualenv/*/*/bin/python3') ?: [],
            glob($homeDir . '/virtualenv/*/*/bin/python') ?: [],
            glob($homeDir . '/virtualenv/*/*/*/bin/python3') ?: []
        );
        $venvCommands = [];
        foreach (array_unique($venvPythons) as $venvPy) {
            $venvCommands[] = escapeshellarg($venvPy) . ' -m edge_tts --voice ' . escapeshellarg($voice)
                . ' --rate=' . escapeshellarg($rate) . ' --pitch=' . escapeshellarg($pitch)
                . ' --file ' . escapeshellarg($tempInput) . ' --write-media ' . escapeshellarg($cacheFile);
        }
        if ($venvCommands) {
            $commands = array_merge($venvCommands, $commands);
        }

        $generated = false;
        foreach ($commands as $cmd) {
            $descriptors = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ];

            $process = @proc_open($cmd, $descriptors, $pipes);
            if (is_resource($process)) {
                fclose($pipes[0]);
                $stdout = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                $stderr = stream_get_contents($pipes[2]);
                fclose($pipes[2]);
                $code = proc_close($process);

                if ($code === 0 && is_file($cacheFile) && filesize($cacheFile) > 200) {
                    $generated = true;
                    break;
                } else {
                    log_message('debug', "EdgeTtsService: Command failed ({$cmd}) code {$code}: {$stderr}");
                }
            }
        }

        if ($generated) {
            @unlink($tempInput);
            return $cacheFile;
        }

        // Last resort: pure-PHP Edge TTS client (no Python needed).
        // NOTE: Microsoft's endpoint currently rejects this client's
        // handshake with HTTP 403 from datacenter IPs (verified
        // 2026-11-19 from both the sandbox and this server), while the
        // Python edge-tts in the virtualenv succeeds — so this runs
        // only when the shell path is unavailable.
        try {
            $client = new EdgeTtsClient();
            $mp3 = $client->synthesize($text, $voice, $rate, $pitch);
            if ($mp3 !== null && @file_put_contents($cacheFile, $mp3) !== false
                && is_file($cacheFile) && filesize($cacheFile) > 200) {
                @unlink($tempInput);
                return $cacheFile;
            }
        } catch (\Throwable $e) {
            log_message('error', 'EdgeTtsService: PHP-native client failed: ' . $e->getMessage());
        }

        @unlink($tempInput);

        return null;
    }

    /**
     * Synthesize text to MP3 binary string
     */
    public function synthesize(string $text, string $persona = 'corporate-hr'): ?string
    {
        $file = $this->synthesizeToFile($text, $persona);
        if ($file && is_file($file)) {
            return file_get_contents($file);
        }
        return null;
    }
}
