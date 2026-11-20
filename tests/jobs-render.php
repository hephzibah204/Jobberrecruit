<?php
// Offline rendering of the real jobs PHP view with deterministic database-shaped data.
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'app/');
function base_url($path = '') { return 'http://jobs.test/' . ltrim($path, '/'); }
function current_url() { return base_url('jobs'); }
function esc($value, $context = 'html') { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function uri_string() { return 'jobs'; }
function csrf_hash() { return 'fixture'; }
function auth() { return new class { function user() { return null; } function loggedIn() { return false; } }; }
function humanize($value) { return ucwords(str_replace(['-', '_'], ' ', $value)); }
function format_job_url($job) { return base_url('job/view/' . $job->id); }
function jobPostingSchema($job, $base) { return ['@type' => 'JobPosting', 'title' => $job->title]; }
class JobsPreview
{
    public array $sections = [];
    private string $active = '';
    function extend($name) {}
    function section($name) { $this->active = $name; ob_start(); }
    function endSection() { $this->sections[$this->active] = ob_get_clean(); }
    function render()
    {
        $auth = auth(); $keywords = ''; $stateId = ''; $categoryId = ''; $industryId = '';
        $jobType = ''; $jobPosted = ''; $experienceLevel = ''; $salaryMin = ''; $salaryMax = ''; $workArrangement = '';
        $sort_by = 'newest'; $view_mode = 'grid'; $current_page = 1; $per_page = 24; $total_jobs = 62;
        $savedJobIds = [2]; $job_type_counts = ['full-time' => 30, 'contract' => 12];
        $industry_counts = [(object) ['id' => 1, 'job_count' => 15]];
        $industries = [(object) ['id' => 1, 'name' => 'Technology']];
        $categories = [(object) ['id' => 1, 'name' => 'Engineering']];
        $states = [(object) ['id' => 1, 'name' => 'Lagos'], (object) ['id' => 2, 'name' => 'Abuja']];
        $jobs = array_map(static fn($id) => (object) [
            'id' => $id, 'title' => ['Senior Software Engineer', 'Product Designer', 'Operations Manager', 'Data Analyst'][($id - 1) % 4],
            'slug' => 'role-' . $id, 'employer_name' => 'Example Company', 'created_at' => '2026-10-16',
            'location' => 'Lagos', 'is_featured' => $id < 3, 'featured_until' => '2099-01-01',
            'show_trust_badge' => $id === 1, 'anonymous' => false, 'is_anonymous' => false,
            'job_type' => 'full-time', 'salary' => 350000, 'salary_details' => '₦350,000 / month', 'application_method' => 'internal',
        ], range(1, 8));
        require APPPATH . 'Views/home/jobs.php';
        echo '<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $this->sections['styles'] . '</head><body class="homepage-reference">';
        require APPPATH . 'Views/partials/svg_sprites.php';
        require APPPATH . 'Views/templates/partials/header.php';
        echo $this->sections['content'];
        require APPPATH . 'Views/templates/partials/footer.php';
        $base = file_get_contents(APPPATH . 'Views/templates/base.php');
        if (preg_match('/<script>\s*function toggleMenu\(btn\).*?<\/script>/s', $base, $navigationScript)) echo $navigationScript[0];
        echo $this->sections['scripts'] . '</body></html>';
    }
}
(new JobsPreview())->render();
