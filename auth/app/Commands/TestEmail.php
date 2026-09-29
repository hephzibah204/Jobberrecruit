<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

class TestEmail extends BaseCommand
{
    protected $group       = 'Email';
    protected $name        = 'email:test';
    protected $description = 'Send a test email and print debug logs';

    public function run(array $params)
    {
        $to = $params[0] ?? null;
        if (!$to) {
            CLI::error('Please specify a recipient email address: php spark email:test recipient@example.com');
            return;
        }

        CLI::write("Initializing email service...", "cyan");
        Services::$bypassQueue = true;
        $email = Services::email(false);
        Services::$bypassQueue = false;

        $email->setTo($to);
        $email->setSubject("JobberRecruit SMTP Test");
        $email->setMessage("<p>This is a test email sent from the JobberRecruit CLI tool to verify SMTP settings.</p>");

        CLI::write("Sending test email to: " . $to, "cyan");

        if ($email->send()) {
            CLI::write("Success! Email sent successfully.", "green");
        } else {
            CLI::error("Error! Failed to send email.");
            CLI::write($email->printDebugger(['headers', 'subject', 'body', 'smtp']), "yellow");
        }
    }
}
