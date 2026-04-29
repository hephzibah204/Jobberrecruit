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

        $this->fromEmail = env('email.from_email', 'noreply@jobberrecruit.com');
        $this->fromName = env('email.from_name', 'Jobber Recruit');
        $this->SMTPHost = env('email.SMTPHost', '');
        $this->SMTPUser = env('email.SMTPUser', '');
        $this->SMTPPass = env('email.SMTPPass', '');
        $this->SMTPPort = (int)env('email.SMTPPort', 587);
        $this->SMTPCrypto = env('email.SMTPCrypto', 'tls');
    }
}
