<?php

// 1. Bootstrap CodeIgniter 4 using the modern CI 4.6+ BootConsole method
define('FCPATH', dirname(__DIR__) . '/public/');
require dirname(__DIR__) . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';

// Define ENVIRONMENT to bypass framework bootConsole bug
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

\CodeIgniter\Boot::bootConsole($paths);

echo "=========================================================\n";
echo "       JobberRecruit Mailtrap SMTP Verification Script    \n";
echo "=========================================================\n\n";

// 2. Load and parse production environment config for SMTP details
$emailConfig = new \Config\Email();

$envProductionPath = dirname(__DIR__) . '/.env.production';
if (file_exists($envProductionPath)) {
    echo "Loading credentials from production env: {$envProductionPath}\n";
    $lines = file($envProductionPath);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim(trim($val), '"\'');
            if (strpos($key, 'email.') === 0) {
                $prop = substr($key, 6);
                if ($prop === 'SMTPHost') {
                    $emailConfig->SMTPHost = $val;
                } elseif ($prop === 'SMTPUser') {
                    $emailConfig->SMTPUser = $val;
                } elseif ($prop === 'SMTPPass') {
                    $emailConfig->SMTPPass = $val;
                } elseif ($prop === 'SMTPPort') {
                    $emailConfig->SMTPPort = (int)$val;
                } elseif ($prop === 'SMTPCrypto') {
                    $emailConfig->SMTPCrypto = $val;
                } elseif ($prop === 'fromEmail') {
                    $emailConfig->fromEmail = $val;
                } elseif ($prop === 'fromName') {
                    $emailConfig->fromName = $val;
                }
            }
        }
    }
} else {
    echo "WARNING: .env.production file not found! Falling back to standard config.\n";
}

// Override settings locally to bypass Windows PHP 8.2 OpenSSL stream socket bug
echo "NOTE: Bypassing Windows PHP 8.2 OpenSSL TLS stream bug by connecting on port 2525 without crypto wrapper.\n";
$emailConfig->SMTPPort = 2525;
$emailConfig->SMTPCrypto = '';

echo "SMTP Settings to verify:\n";
echo " - Host:   {$emailConfig->SMTPHost}\n";
echo " - User:   {$emailConfig->SMTPUser}\n";
echo " - Port:   {$emailConfig->SMTPPort}\n";
echo " - Crypto: [None / Bypassed]\n";
echo " - From:   \"{$emailConfig->fromName}\" <{$emailConfig->fromEmail}>\n\n";

// 3. Initialize Email Service with our config
\Config\Services::$bypassQueue = true;
$email = \Config\Services::email($emailConfig, false);
\Config\Services::$bypassQueue = false;

// 4. Set From explicitly (Mailtrap requires sending from verified sender)
$email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);

// 5. Formulate test message
$testRecipient = 'hephzibah204@gmail.com'; // Admin or verified test email
$email->setTo($testRecipient);
$email->setSubject('JobberRecruit Mailtrap SMTP Verification Test');

$htmlContent = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; padding: 30px; color: #334155; }
        .card { background-color: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 30px; max-width: 500px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { color: #005DA8; font-size: 20px; margin-top: 0; border-bottom: 2px solid #F5A623; padding-bottom: 10px; }
        p { font-size: 14px; line-height: 1.6; }
        .footer { font-size: 11px; color: #64748b; margin-top: 25px; text-align: center; }
    </style>
</head>
<body>
    <div class='card'>
        <h1>✅ Mailtrap SMTP Connection Successful!</h1>
        <p>This email confirms that the Mailtrap SMTP API configuration inside your JobberRecruit environment is fully functional and successfully authenticated.</p>
        <p><strong>SMTP Host:</strong> {$emailConfig->SMTPHost}</p>
        <p><strong>Sent At:</strong> " . date('Y-m-d H:i:s') . "</p>
        <div class='footer'>
            JobberRecruit Ltd | 6 Ojulari Rd, Lekki Penninsula II, Lagos, Nigeria
        </div>
    </div>
</body>
</html>
";

$email->setMessage($htmlContent);
$email->setMailType('html');

echo "Sending verification email to {$testRecipient}...\n";

if ($email->send()) {
    echo "\n🎉 SUCCESS! Mailtrap SMTP sent the email successfully via CodeIgniter.\n";
    echo "=========================================================\n";
} else {
    echo "\n❌ ERROR: SMTP delivery failed!\n";
    echo "=========================================================\n";
    echo $email->printDebugger(['headers', 'subject', 'body']);
}
