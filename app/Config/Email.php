<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'info@jobberrecruit.com';
    public string $fromName   = 'Jobber Recruit Team';
    public string $recipients = '';

    public string $userAgent = 'JobberRecruit Mailer';

    /**
     * Protocol
     */
    public string $protocol = 'smtp';

    /**
     * SMTP Settings (loaded from .env)
     */
    public string $SMTPHost = '';
    public string $SMTPUser = '';
    public string $SMTPPass = '';
    public int    $SMTPPort = 587;
    public int    $SMTPTimeout = 30;
    public bool   $SMTPKeepAlive = false;
    public string $SMTPCrypto = 'tls';

    /**
     * Email formatting
     */
    public bool   $wordWrap = true;
    public int    $wrapChars = 76;
    public string $mailType = 'html';
    public string $charset  = 'UTF-8';
    public bool   $validate = true;

    /**
     * Headers
     */
    public int    $priority = 3;
    public string $CRLF    = "\r\n";
    public string $newline = "\r\n";

    /**
     * BCC / Debug
     */
    public bool $BCCBatchMode = false;
    public int  $BCCBatchSize = 200;
    public bool $DSN = false;

    public function __construct()
    {
        parent::__construct();

        $mailtrapToken = env('MAILTRAP_API_TOKEN') 
            ?: (env('MAILTRAP_API_KEY') 
            ?: (env('MAILTRAP_TOKEN') 
            ?: (env('mailtrap_api_key') ?: 'f61d16194a672c3c36fe382d5ebd2f76')));

        $this->fromEmail  = env('email.from_email') ?: (env('email.fromEmail') ?: (env('FROM_EMAIL') ?: 'info@jobberrecruit.com'));
        $this->fromName   = env('email.from_name') ?: (env('email.fromName') ?: (env('site_name') ?: 'JobberRecruit'));
        $this->protocol   = env('email.protocol', 'smtp');
        $this->SMTPHost   = env('email.SMTPHost', env('SMTP_HOST', 'live.smtp.mailtrap.io'));
        $this->SMTPUser   = env('email.SMTPUser', env('SMTP_USER', 'api'));
        $this->SMTPPass   = env('email.SMTPPass', env('SMTP_PASS', $mailtrapToken));
        $this->SMTPPort   = (int) (env('email.SMTPPort') ?: (env('SMTP_PORT') ?: 587));
        $this->SMTPCrypto = env('email.SMTPCrypto', env('SMTP_CRYPTO', 'tls'));

        if ($this->protocol === 'smtp' && empty($this->SMTPHost)) {
            $this->protocol = 'mail';
        }
    }
}
