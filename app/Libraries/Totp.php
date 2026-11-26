<?php

namespace App\Libraries;

/**
 * Minimal RFC 6238 TOTP implementation (SHA-1, 30-second step, 6 digits)
 * used for admin two-factor authentication.
 *
 * Pure PHP on purpose: no TOTP/OTP package is installed (see
 * composer.lock), and hash_hmac()/random_bytes() are core. Secrets are
 * Base32 (RFC 4648 alphabet) without padding, the form authenticator
 * apps expect in otpauth:// URIs.
 */
class Totp
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    private const STEP            = 30;
    private const DIGITS          = 6;

    /**
     * Generate a new random Base32 secret. 20 bytes of entropy produce
     * exactly 32 Base32 characters.
     */
    public static function generateSecret(): string
    {
        return self::base32Encode(random_bytes(20));
    }

    /**
     * Encode raw bytes as unpadded Base32.
     */
    public static function base32Encode(string $data): string
    {
        $encoded = '';
        $buffer  = 0;
        $bits    = 0;

        foreach (str_split($data) as $char) {
            $buffer = ($buffer << 8) | ord($char);
            $bits  += 8;

            while ($bits >= 5) {
                $bits    -= 5;
                $encoded .= self::BASE32_ALPHABET[($buffer >> $bits) & 0x1F];
            }
        }

        if ($bits > 0) {
            $encoded .= self::BASE32_ALPHABET[($buffer << (5 - $bits)) & 0x1F];
        }

        return $encoded;
    }

    /**
     * Decode a Base32 string (padding and spaces tolerated).
     * Returns null when the input contains non-Base32 characters.
     */
    public static function base32Decode(string $data): ?string
    {
        $data = strtoupper(rtrim(str_replace(' ', '', $data), '='));

        if ($data === '' || preg_match('/[^A-Z2-7]/', $data)) {
            return null;
        }

        $map     = array_flip(str_split(self::BASE32_ALPHABET));
        $decoded = '';
        $buffer  = 0;
        $bits    = 0;

        foreach (str_split($data) as $char) {
            $buffer = ($buffer << 5) | $map[$char];
            $bits  += 5;

            if ($bits >= 8) {
                $bits    -= 8;
                $decoded .= chr(($buffer >> $bits) & 0xFF);
            }
        }

        return $decoded;
    }

    /**
     * The 6-digit code for a Base32 secret at a point in time
     * (defaults to now).
     */
    public static function getCode(string $secret, ?int $timestamp = null): string
    {
        $key = self::base32Decode($secret);
        if ($key === null) {
            return '';
        }

        $counter = intdiv($timestamp ?? time(), self::STEP);

        // 8-byte big-endian counter, per RFC 4226.
        $binaryCounter = pack('N2', intdiv($counter, 4294967296), $counter % 4294967296);
        $hash          = hash_hmac('sha1', $binaryCounter, $key, true);

        // Dynamic truncation.
        $offset = ord($hash[19]) & 0x0F;
        $value  = ((ord($hash[$offset]) & 0x7F) << 24)
            | ((ord($hash[$offset + 1]) & 0xFF) << 16)
            | ((ord($hash[$offset + 2]) & 0xFF) << 8)
            | (ord($hash[$offset + 3]) & 0xFF);

        return str_pad((string) ($value % (10 ** self::DIGITS)), self::DIGITS, '0', STR_PAD_LEFT);
    }

    /**
     * Verify a user-supplied code with a tolerance of $window steps on
     * either side (default 1, covering clock skew and slow entry).
     * Comparisons use hash_equals().
     */
    public static function verifyCode(string $secret, string $code, int $window = 1, ?int $timestamp = null): bool
    {
        $code = str_replace(' ', '', trim($code));

        if (strlen($code) !== self::DIGITS || ! ctype_digit($code)) {
            return false;
        }

        $timestamp ??= time();

        for ($i = -$window; $i <= $window; $i++) {
            $expected = self::getCode($secret, $timestamp + ($i * self::STEP));
            if ($expected !== '' && hash_equals($expected, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build the otpauth:// URI an authenticator app would consume.
     * Shown to the admin as text (no QR library is bundled, and the
     * secret must never be sent to a third-party QR service).
     */
    public static function getProvisioningUri(string $secret, string $account, string $issuer): string
    {
        $label = rawurlencode($issuer) . ':' . rawurlencode($account);

        return 'otpauth://totp/' . $label
            . '?secret=' . $secret
            . '&issuer=' . rawurlencode($issuer)
            . '&algorithm=SHA1&digits=' . self::DIGITS . '&period=' . self::STEP;
    }
}
