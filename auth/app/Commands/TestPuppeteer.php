<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\CourseCertificateModel;
use App\Models\CourseModel;
use App\Models\UserModel;
use Spatie\Browsershot\Browsershot;

class TestPuppeteer extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:puppeteer';
    protected $description = 'Generate a test certificate PDF using Puppeteer';

    public function run(array $params)
    {
        CLI::write("Fetching first certificate...", 'yellow');
        
        $certModel = model(CourseCertificateModel::class);
        $certificate = $certModel->first();
        if (!$certificate) {
            CLI::write("No certificates found in the DB. Using dummy data...", 'yellow');
            $certificate = [
                'id' => 1,
                'certificate_code' => 'JR-CV-2026-8F3A21',
                'issued_at' => date('Y-m-d H:i:s'),
                'course_id' => 1,
                'user_id' => 1
            ];
            $course = (object) [
                'id' => 1,
                'title' => 'Mastering the ATS: Build a CV That Gets Interviews',
                'duration' => '6 Modules &middot; 8 Hours'
            ];
            $user = (object) [
                'id' => 1,
                'full_name' => 'Adebayo Martins',
                'username' => 'adebayo'
            ];
        } else {
            $courseModel = model(CourseModel::class);
            $course = $courseModel->find($certificate['course_id']);
            $userModel = model(UserModel::class);
            $user = $userModel->find($certificate['user_id']);
        }

        CLI::write("Rendering view...", 'yellow');
        $html = view('certificates/course_certificate', [
            'certificate' => $certificate,
            'course' => $course,
            'user' => $user,
        ]);

        $outputFile = WRITEPATH . 'certificate_puppeteer_test_4.pdf';
        if (file_exists($outputFile)) {
            @unlink($outputFile);
        }

        CLI::write("Running Puppeteer...", 'yellow');
        try {
            Browsershot::html($html)
                ->format('A4')
                ->landscape()
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->noSandbox()
                ->save($outputFile);
            CLI::write("Success! PDF generated at: {$outputFile}", 'green');
        } catch (\Exception $e) {
            CLI::error("Error generating PDF: " . $e->getMessage());
        }
    }
}
