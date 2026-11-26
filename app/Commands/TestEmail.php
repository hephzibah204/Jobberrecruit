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
        $email = Services::email(false);

        $rendered = (new \App\Services\EmailTemplateService())->render('test_email', []);

        $email->setTo($to);
        $email->setSubject($rendered['subject']);
        $email->setMessage($rendered['html']);
        if (!empty($rendered['text'])) {
            $email->setAltMessage($rendered['text']);
        }

        CLI::write("Sending test email to: " . $to, "cyan");

        if ($email->send()) {
            CLI::write("Success! Email sent successfully.", "green");
        } else {
            CLI::error("Error! Failed to send email.");
            CLI::write($email->printDebugger(), "yellow");
        }
    }
}
