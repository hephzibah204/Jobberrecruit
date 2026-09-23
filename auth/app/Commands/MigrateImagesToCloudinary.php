<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MigrateImagesToCloudinary extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'migrate:images_to_cloudinary';
    protected $description = 'Migrates existing local employer logos and candidate profile pictures to Cloudinary';

    public function run(array $params)
    {
        if (
            !env('CLOUDINARY_NAME') ||
            !env('CLOUDINARY_API_KEY') ||
            !env('CLOUDINARY_API_SECRET')
        ) {
            CLI::error("Cloudinary credentials are missing in the .env file. Aborting.");
            return;
        }

        $cloudinary = new \Cloudinary\Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);

        $db = \Config\Database::connect();
        
        CLI::write('Starting migration...', 'yellow');

        // ==========================================
        // 1. MIGRATE EMPLOYER LOGOS
        // ==========================================
        CLI::write("\n--- Migrating Employer Logos ---", 'cyan');
        $employers = $db->table('employers')
            ->where('logo IS NOT NULL')
            ->where("logo != ''")
            ->where("logo NOT LIKE 'http%'")
            ->get()->getResultArray();

        CLI::write("Found " . count($employers) . " local employer logos to process.");

        $employerSuccess = 0;
        foreach ($employers as $employer) {
            $localPath = FCPATH . ltrim($employer['logo'], '/');
            
            if (file_exists($localPath)) {
                try {
                    $upload = $cloudinary->uploadApi()->upload(
                        $localPath,
                        ['folder' => 'jobberrecruit/employers']
                    );
                    
                    $db->table('employers')->where('id', $employer['id'])->update([
                        'logo' => $upload['secure_url']
                    ]);
                    
                    @unlink($localPath);
                    $employerSuccess++;
                    CLI::write("Employer ID {$employer['id']}: Uploaded successfully.", 'green');
                } catch (\Exception $e) {
                    CLI::error("Employer ID {$employer['id']}: Failed - " . $e->getMessage());
                }
            } else {
                CLI::write("Employer ID {$employer['id']}: File missing on disk ($localPath).", 'dark_gray');
            }
        }

        // ==========================================
        // 2. MIGRATE CANDIDATE PROFILE PICTURES
        // ==========================================
        CLI::write("\n--- Migrating Candidate Profile Pictures ---", 'cyan');
        $candidates = $db->table('job_seekers')
            ->where('profile_picture IS NOT NULL')
            ->where("profile_picture != ''")
            ->where("profile_picture NOT LIKE 'http%'")
            ->get()->getResultArray();

        CLI::write("Found " . count($candidates) . " local profile pictures to process.");

        $candidateSuccess = 0;
        foreach ($candidates as $candidate) {
            $localPath = FCPATH . ltrim($candidate['profile_picture'], '/');
            
            if (file_exists($localPath)) {
                try {
                    $upload = $cloudinary->uploadApi()->upload(
                        $localPath,
                        ['folder' => 'jobberrecruit/candidates']
                    );
                    
                    $db->table('job_seekers')->where('id', $candidate['id'])->update([
                        'profile_picture' => $upload['secure_url']
                    ]);
                    
                    @unlink($localPath);
                    $candidateSuccess++;
                    CLI::write("Candidate ID {$candidate['id']}: Uploaded successfully.", 'green');
                } catch (\Exception $e) {
                    CLI::error("Candidate ID {$candidate['id']}: Failed - " . $e->getMessage());
                }
            } else {
                CLI::write("Candidate ID {$candidate['id']}: File missing on disk ($localPath).", 'dark_gray');
            }
        }

        CLI::write("\n==========================================", 'yellow');
        CLI::write("Migration Complete!", 'green');
        CLI::write("Employers Migrated: $employerSuccess / " . count($employers));
        CLI::write("Candidates Migrated: $candidateSuccess / " . count($candidates));
        CLI::write("==========================================", 'yellow');
    }
}
