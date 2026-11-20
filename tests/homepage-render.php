<?php
// Render the real homepage and shared navigation with deterministic, offline data.
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once FCPATH . 'app/Services/NigerianJobLocations.php';
require_once FCPATH . 'app/Helpers/url_helper.php';
function base_url($path = '') { return 'http://homepage.test/' . ltrim($path, '/'); }
function esc($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function uri_string() { return ''; }
function csrf_field() { return '<input type="hidden" name="csrf_test" value="fixture">'; }
function csrf_hash() { return 'fixture'; }
function auth() { return new class { function user() { return null; } function loggedIn() { return false; } }; }
function resolve_image_url(...$args) { return base_url('images/favicon.png'); }
function time_ago($date) { return 'Today'; }
class HomepagePreview
{
    public array $sections = [];
    private string $active = '';
    function extend($name) {}
    function section($name) { $this->active = $name; ob_start(); }
    function endSection() { $this->sections[$this->active] = ob_get_clean(); }
    function render()
    {
        $auth = auth(); $q = ''; $savedJobIds = []; $activeJobsCount = 12;
        $states = [(object) ['id' => 1, 'name' => 'Lagos']];
        $categories = array_map(fn($i) => (object) ['id' => $i, 'name' => ['Technology', 'Finance', 'Healthcare', 'Engineering'][$i - 1], 'slug' => ['information-technology', 'accounting-finance-banking', 'healthcare-pharmaceuticals', 'engineering-technical'][$i - 1], 'job_count' => 3], range(1, 4));
        $categories[0]->url = base_url('information-technology-jobs');
        $top_locations = $GLOBALS['homepageTestLocations'] ?? [(object) ['name' => 'Lagos', 'url' => base_url('jobs?state_id=1'), 'job_count' => 12]];
        $jobs = array_map(fn($i) => (object) ['id' => $i, 'title' => 'Software Engineer', 'employer_name' => 'Example Company', 'created_at' => date('Y-m-d'), 'location' => 'Lagos', 'is_featured' => $i === 1], range(1, 12));
        $jobs[0]->show_trust_badge = true;
        $jobs = $GLOBALS['homepageTestJobs'] ?? $jobs;
        $featured_courses = []; $testimonials = [];
        require FCPATH . 'app/Views/home/index.php';
        echo '<!doctype html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $this->sections['styles'] . '</head><body class="homepage-reference">';
        require FCPATH . 'app/Views/partials/svg_sprites.php';
        require FCPATH . 'app/Views/templates/partials/header.php';
        echo $this->sections['content'];
        require FCPATH . 'app/Views/templates/partials/footer.php';
        echo $this->sections['scripts'] . '</body></html>';
    }
}
(new HomepagePreview())->render();
