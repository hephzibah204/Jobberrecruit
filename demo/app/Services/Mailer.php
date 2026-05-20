<?php

namespace App\Services;

use Config\Services;

class Mailer
{
    protected $email;

    public function __construct()
    {
        $this->email = Services::email();
    }

    /**
     * Clear the email object's state
     * 
     * @return self
     */
    public function clear(): self
    {
        $this->email->clear();
        return $this;
    }

    // Add proxy methods for commonly used email methods
    public function setTo($to, $name = '')
    {
        $this->email->setTo($to, $name);
        return $this;
    }

    public function setFrom($from, $name = '', $returnPath = null)
    {
        $this->email->setFrom($from, $name, $returnPath);
        return $this;
    }

    public function setSubject($subject)
    {
        $this->email->setSubject($subject);
        return $this;
    }

    public function setMessage($message)
    {
        $this->email->setMessage($message);
        return $this;
    }

    public function setMailType($type)
    {
        $this->email->setMailType($type);
        return $this;
    }

    public function setAltMessage($altMessage)
    {
        $this->email->setAltMessage($altMessage);
        return $this;
    }

    public function setReplyTo($replyto, $name = '')
    {
        $this->email->setReplyTo($replyto, $name);
        return $this;
    }

    public function send()
    {
        return $this->email->send();
    }

    /**
     * Send a verification email using templated views.
     */
    public function sendVerifyEmail(string $to, string $subject, array $data): bool
    {
        $fullname = $data['fullname'] ?? '';
        $verifyUrl = $data['verifyUrl'] ?? '';
        $siteName = $data['siteName'] ?? 'JobberRecruit';

        $this->clear();

        $html = view('emails/verify_email_html', [
            'fullname' => $fullname,
            'verifyUrl' => $verifyUrl,
            'siteName' => $siteName,
        ]);
        $text = view('emails/verify_email_text', [
            'fullname' => $fullname,
            'verifyUrl' => $verifyUrl,
            'siteName' => $siteName,
        ]);

        \Config\Services::$bypassQueue = true;
        $result = $this->setTo($to)
            ->setFrom(env('email_from_address') ?: 'no-reply@' . parse_url(base_url(), PHP_URL_HOST), env('email_from_name') ?: $siteName)
            ->setSubject($subject)
            ->setMessage($html)
            ->setAltMessage($text)
            ->send();
        \Config\Services::$bypassQueue = false;

        return $result;
    }

    public function sendResetPassword(string $to, string $subject, array $data): bool
    {
        $fullname = $data['fullname'] ?? '';
        $resetLink = $data['resetLink'] ?? '';
        $siteName = $data['siteName'] ?? 'JobberRecruit';

        $this->clear();

        $html = view('emails/password_reset_html', [
            'fullname' => $fullname,
            'resetLink' => $resetLink,
            'siteName' => $siteName,
        ]);
        $text = view('emails/password_reset_text', [
            'fullname' => $fullname,
            'resetLink' => $resetLink,
            'siteName' => $siteName,
        ]);

        \Config\Services::$bypassQueue = true;
        $result = $this->setTo($to)
            ->setFrom(env('email_from_address') ?: 'no-reply@' . parse_url(base_url(), PHP_URL_HOST), env('email_from_name') ?: $siteName)
            ->setSubject($subject)
            ->setMessage($html)
            ->setAltMessage($text)
            ->send();
        \Config\Services::$bypassQueue = false;

        return $result;
    }

    /**
     * Send email using a view template
     */
    public function sendTemplate(
        string $to,
        string $subject,
        string $view,
        array $data = [],
        ?string $replyTo = null
    ): bool {
        $this->clear();

        $message = view($view, $data);

        $this->setTo($to)
            ->setSubject($subject)
            ->setMessage($message)
            ->setMailType('html');

        if ($replyTo) {
            $this->setReplyTo($replyTo);
        }

        return $this->send();
    }

    /**
     * Get the underlying email instance
     */
    public function getEmailInstance()
    {
        return $this->email;
    }
}
