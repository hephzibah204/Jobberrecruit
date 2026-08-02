<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Spatie\Browsershot\Browsershot;

class TestCertificate extends BaseCommand
{
    protected $group       = 'Development';
    protected $name        = 'test:cert';
    protected $description = 'Renders and tests Browsershot export of a course certificate.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // Ensure mock enrollment exists
        $enrollment = $db->table('course_enrollments')->where('user_id', 1)->where('course_id', 3)->get()->getRowArray();
        if (!$enrollment) {
            CLI::write("Creating mock course enrollment first...");
            $mockEnrollment = [
                'course_id'         => 3,
                'user_id'           => 1,
                'payment_reference' => 'MOCK-REF-123',
                'status'            => 'completed',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
                'amount'            => 15000.00,
                'completed_at'      => date('Y-m-d H:i:s'),
                'progress'          => 100
            ];
            $db->table('course_enrollments')->insert($mockEnrollment);
            $enrollment = $db->table('course_enrollments')->where('user_id', 1)->where('course_id', 3)->get()->getRowArray();
        }

        $cert = $db->table('course_certificates')->select('id')->limit(1)->get()->getRowArray();

        if (!$cert) {
            CLI::write("No certificates found. Creating a mock certificate in the database...");
            
            // Insert mock certificate data
            $mockData = [
                'user_id'          => 1,
                'course_id'        => 3,
                'enrollment_id'    => $enrollment['id'],
                'certificate_code' => 'JR-MOCK-999',
                'issued_at'        => date('Y-m-d H:i:s'),
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s')
            ];
            
            $db->table('course_certificates')->insert($mockData);
            $cert = $db->table('course_certificates')->select('id')->limit(1)->get()->getRowArray();
        }

        $certificateId = $cert['id'];
        CLI::write("Testing with Certificate ID: {$certificateId}");

        $certModel = model(\App\Models\CourseCertificateModel::class);
        $certificate = $certModel->find($certificateId);
        $courseModel = model(\App\Models\CourseModel::class);
        $course = $courseModel->find($certificate['course_id']);
        $userModel = model(\App\Models\UserModel::class);
        $targetUser = $userModel->find($certificate['user_id']);

        // Resolve full name
        if (empty($targetUser->full_name)) {
            $seekerModel = model(\App\Models\JobSeekerModel::class);
            $seeker = $seekerModel->where('user_id', $targetUser->id)->first();
            if ($seeker) {
                $targetUser->full_name = $seeker->full_name ?? ($seeker->first_name . ' ' . $seeker->last_name);
            } else {
                $targetUser->full_name = $targetUser->username;
            }
        }

        // Generate the HTML view content
        $html = view('certificates/course_certificate', [
            'certificate' => $certificate,
            'course'      => $course,
            'user'        => $targetUser,
        ]);

        CLI::write("HTML View generated successfully. Length: " . strlen($html) . " bytes");

        // Test Browsershot generation
        CLI::write("Running Browsershot generation test...");
        try {
            $outputPath = WRITEPATH . 'temp/test-certificate-' . $certificate['certificate_code'] . '-' . time() . '.pdf';
            if (!is_dir(WRITEPATH . 'temp')) {
                mkdir(WRITEPATH . 'temp', 0777, true);
            }
            
            $browsershot = Browsershot::html($html)
                ->format('A4')
                ->landscape()
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->noSandbox();

            if (DIRECTORY_SEPARATOR === '\\') {
                $nodePath = 'C:\\Program Files\\nodejs\\node.exe';
                $npmPath  = 'C:\\Program Files\\nodejs\\npm.cmd';
                if (file_exists($nodePath)) {
                    $browsershot->setNodeBinary($nodePath);
                }
                if (file_exists($npmPath)) {
                    $browsershot->setNpmBinary($npmPath);
                }
            }

            $browsershot->save($outputPath);
            
            CLI::write("PDF generated successfully and saved to: " . $outputPath);
        } catch (\Throwable $e) {
            CLI::error("Error generating PDF: " . $e->getMessage());
        }
    }
}
