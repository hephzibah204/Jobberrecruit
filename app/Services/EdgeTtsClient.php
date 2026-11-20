<?php

namespace App\Services;

/**
 * Pure-PHP Microsoft Edge TTS client — no Python, no shell, no edge-tts
 * binary required (owner decision 2026-11-19, after the production shared
 * host proved to have no Python at all: every shell attempt exited 127
 * "command not found").
 *
 * Speaks the Edge Read Aloud WebSocket protocol directly over TLS:
 *   wss://speech.platform.bing.com/consumer/speech/synthesize/readaloud/edge/v1
 * authenticated with the public trusted-client token and a Sec-MS-GEC
 * token derived from the current time (the same scheme the official
 * Edge client and the edge-tts project use).
 *
 * Returns MP3 bytes (audio-24khz-48kbitrate-mono-mp3) or null on failure.
 * Failures are logged at error level so production logs show the cause.
 */
class EdgeTtsClient
{
    private const TRUSTED_TOKEN = '6A5AA1D4EAFF4E9FB37E23D68491D6F4';
    private const HOST          = 'speech.platform.bing.com';
    private const GEC_VERSION   = '1-143.0.3650.75';
    private const USER_AGENT    = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0';
    private const ORIGIN        = 'chrome-extension://jdiccldimpdaibmpdkjnbmckianbfold';
    private const WS_GUID       = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';

    public function synthesize(string $text, string $voice = 'en-NG-EzinneNeural', string $rate = '+0%', string $pitch = '+0Hz', int $timeoutSec = 25): ?string
    {
        $text = trim($text);
        if ($text === '') {
            return null;
        }

        $requestId    = bin2hex(random_bytes(16));
        $connectionId = bin2hex(random_bytes(16));
        $gecVersion   = (string) (env('EDGE_TTS_GEC_VERSION') ?: self::GEC_VERSION);

        $path = '/consumer/speech/synthesize/readaloud/edge/v1'
            . '?TrustedClientToken=' . self::TRUSTED_TOKEN
            . '&Sec-MS-GEC=' . $this->generateGecToken()
            . '&Sec-MS-GEC-Version=' . $gecVersion
            . '&ConnectionId=' . $connectionId;

        $ctx = stream_context_create([
            'ssl' => [
                'verify_peer'      => true,
                'verify_peer_name' => true,
                'SNI_enabled'      => true,
                'peer_name'        => self::HOST,
            ],
        ]);

        $errno  = 0;
        $errstr = '';
        $sock = @stream_socket_client('tls://' . self::HOST . ':443', $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $ctx);
        if ($sock === false) {
            log_message('error', "EdgeTtsClient: TLS connect failed: {$errstr} ({$errno})");
            return null;
        }
        stream_set_blocking($sock, true);
        stream_set_timeout($sock, $timeoutSec);

        try {
            if (!$this->handshake($sock, $path)) {
                return null;
            }

            $config = json_encode([
                'context' => [
                    'synthesis' => [
                        'audio' => [
                            'metadataoptions' => [
                                'sentenceBoundaryEnabled' => 'false',
                                'wordBoundaryEnabled'     => 'false',
                            ],
                            'outputFormat' => 'audio-24khz-48kbitrate-mono-mp3',
                        ],
                    ],
                ],
            ]);
            $this->sendFrame($sock, "X-Timestamp:" . $this->timestamp() . "\r\n"
                . "Content-Type:application/json; charset=utf-8\r\n"
                . "Path:speech.config\r\n\r\n" . $config, 0x1);

            $ssml = $this->buildSsml($text, $voice, $rate, $pitch);
            $this->sendFrame($sock, "X-RequestId:{$requestId}\r\n"
                . "Content-Type:application/ssml+xml\r\n"
                . "X-Timestamp:" . $this->timestamp() . "\r\n"
                . "Path:ssml\r\n\r\n" . $ssml, 0x1);

            $audio = $this->readAudio($sock, $timeoutSec);

            if ($audio === null || strlen($audio) <= 200) {
                log_message('error', 'EdgeTtsClient: no usable audio returned (voice ' . $voice . ', bytes ' . strlen((string) $audio) . ')');
                return null;
            }
            return $audio;
        } catch (\Throwable $e) {
            log_message('error', 'EdgeTtsClient: ' . $e->getMessage());
            return null;
        } finally {
            @fclose($sock);
        }
    }

    /**
     * Sec-MS-GEC token: SHA-256 of the current Windows FILETIME tick count
     * rounded down to a 5-minute boundary, concatenated with the trusted
     * client token, upper-case hex.
     */
    private function generateGecToken(): string
    {
        $ticks = (time() + 11644473600) * 10000000; // 100ns ticks since 1601-01-01
        $rounded = $ticks - ($ticks % 3000000000);  // round down to 300s
        return strtoupper(hash('sha256', (string) $rounded . self::TRUSTED_TOKEN));
    }

    private function timestamp(): string
    {
        $now = microtime(true);
        $sec = (int) $now;
        $ms  = (int) (($now - $sec) * 1000);
        return gmdate('Y-m-d\TH:i:s', $sec) . sprintf('.%03dZ', $ms);
    }

    private function buildSsml(string $text, string $voice, string $rate, string $pitch): string
    {
        $v = htmlspecialchars($voice, ENT_QUOTES, 'UTF-8');
        $r = htmlspecialchars($rate, ENT_QUOTES, 'UTF-8');
        $p = htmlspecialchars($pitch, ENT_QUOTES, 'UTF-8');
        $t = htmlspecialchars($text, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        return "<speak version='1.0' xmlns='http://www.w3.org/2001/10/synthesis' xml:lang='en-NG'>"
            . "<voice name='{$v}'><prosody pitch='{$p}' rate='{$r}' volume='100%'>{$t}</prosody></voice></speak>";
    }

    private function handshake($sock, string $path): bool
    {
        $key = base64_encode(random_bytes(16));

        $muid = strtoupper(bin2hex(random_bytes(16)));

        $request = "GET {$path} HTTP/1.1\r\n"
            . 'Host: ' . self::HOST . "\r\n"
            . "Upgrade: websocket\r\n"
            . "Connection: Upgrade\r\n"
            . "Sec-WebSocket-Key: {$key}\r\n"
            . "Sec-WebSocket-Version: 13\r\n"
            . 'Origin: ' . self::ORIGIN . "\r\n"
            . 'User-Agent: ' . self::USER_AGENT . "\r\n"
            . "Pragma: no-cache\r\n"
            . "Cache-Control: no-cache\r\n"
            . "Accept-Encoding: gzip, deflate, br\r\n"
            . "Accept-Language: en-US,en;q=0.9\r\n"
            . "Cookie: muid={$muid}\r\n"
            . "\r\n";

        if (!$this->writeAll($sock, $request)) {
            log_message('error', 'EdgeTtsClient: handshake write failed');
            return false;
        }

        $statusLine = fgets($sock);
        if ($statusLine === false || strpos($statusLine, ' 101') === false) {
            log_message('error', 'EdgeTtsClient: handshake rejected: ' . trim((string) $statusLine));
            return false;
        }

        $accept = null;
        while (($line = fgets($sock)) !== false) {
            $line = trim($line);
            if ($line === '') {
                break; // end of headers; fgets never over-reads into frames
            }
            if (stripos($line, 'Sec-WebSocket-Accept:') === 0) {
                $accept = trim(substr($line, strlen('Sec-WebSocket-Accept:')));
            }
        }

        $expected = base64_encode(sha1($key . self::WS_GUID, true));
        if ($accept !== null && !hash_equals($expected, $accept)) {
            log_message('error', 'EdgeTtsClient: Sec-WebSocket-Accept mismatch');
            return false;
        }
        return true;
    }

    /** Send one masked client frame (FIN set). */
    private function sendFrame($sock, string $payload, int $opcode): void
    {
        $len = strlen($payload);
        $head = chr(0x80 | $opcode);
        if ($len < 126) {
            $head .= chr(0x80 | $len);
        } elseif ($len < 65536) {
            $head .= chr(0x80 | 126) . pack('n', $len);
        } else {
            $head .= chr(0x80 | 127) . pack('NN', 0, $len);
        }
        $mask = random_bytes(4);
        $masked = '';
        for ($i = 0; $i < $len; $i++) {
            $masked .= $payload[$i] ^ $mask[$i % 4];
        }
        if (!$this->writeAll($sock, $head . $mask . $masked)) {
            throw new \RuntimeException('WebSocket write failed');
        }
    }

    private function writeAll($sock, string $data): bool
    {
        $len = strlen($data);
        $off = 0;
        while ($off < $len) {
            $written = @fwrite($sock, substr($data, $off));
            if ($written === false || $written === 0) {
                return false;
            }
            $off += $written;
        }
        return true;
    }

    private function readExact($sock, int $n): ?string
    {
        $buf = '';
        while (strlen($buf) < $n) {
            $chunk = @fread($sock, $n - strlen($buf));
            if ($chunk === false || $chunk === '') {
                $meta = stream_get_meta_data($sock);
                if (!empty($meta['timed_out']) || feof($sock)) {
                    return null;
                }
                continue;
            }
            $buf .= $chunk;
        }
        return $buf;
    }

    /**
     * Read one complete WebSocket message (handles fragmentation and
     * ping/pong). Returns [opcode, payload] or null on timeout/close-EOF.
     */
    private function readMessage($sock): ?array
    {
        $data = '';
        $opcode = null;

        while (true) {
            $b = $this->readExact($sock, 2);
            if ($b === null) {
                return null;
            }
            $fin    = (ord($b[0]) & 0x80) !== 0;
            $op     = ord($b[0]) & 0x0F;
            $masked = (ord($b[1]) & 0x80) !== 0;
            $len    = ord($b[1]) & 0x7F;

            if ($len === 126) {
                $ext = $this->readExact($sock, 2);
                if ($ext === null) return null;
                $len = unpack('n', $ext)[1];
            } elseif ($len === 127) {
                $ext = $this->readExact($sock, 8);
                if ($ext === null) return null;
                $parts = unpack('N2', $ext);
                $len = $parts[2]; // payloads here are far below 4 GB
            }

            $maskKey = null;
            if ($masked) {
                $maskKey = $this->readExact($sock, 4);
                if ($maskKey === null) return null;
            }

            $payload = $len > 0 ? $this->readExact($sock, $len) : '';
            if ($payload === null) return null;
            if ($masked && $maskKey !== null) {
                $un = '';
                for ($i = 0; $i < $len; $i++) {
                    $un .= $payload[$i] ^ $maskKey[$i % 4];
                }
                $payload = $un;
            }

            if ($op === 0x9) { // ping -> pong
                $this->sendFrame($sock, $payload, 0xA);
                continue;
            }
            if ($op === 0x8) { // close
                return [0x8, ''];
            }
            if ($op === 0xA) { // pong, ignore
                continue;
            }

            if ($opcode === null) {
                $opcode = $op;
            }
            $data .= $payload;
            if ($fin) {
                return [$opcode, $data];
            }
        }
    }

    /** Collect audio frames until turn.end, close, or timeout. */
    private function readAudio($sock, int $timeoutSec): ?string
    {
        $audio = '';
        $deadline = time() + $timeoutSec;

        while (time() <= $deadline) {
            $msg = $this->readMessage($sock);
            if ($msg === null) {
                break; // timeout or EOF
            }
            [$op, $payload] = $msg;

            if ($op === 0x8) {
                break;
            }
            if ($op === 0x2) { // binary: 2-byte header length + headers + MP3
                if (strlen($payload) < 2) {
                    continue;
                }
                $headerLen = unpack('n', substr($payload, 0, 2))[1];
                $header = substr($payload, 2, $headerLen);
                if (strpos($header, 'Path:audio') !== false) {
                    $audio .= substr($payload, 2 + $headerLen);
                }
                continue;
            }
            if ($op === 0x1 && strpos($payload, 'Path:turn.end') !== false) {
                return $audio;
            }
        }

        return $audio !== '' ? $audio : null;
    }
}
