<?php
// Render the real location view with deterministic data, without a database.
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'app/');
require_once APPPATH . 'Services/RemoteLandingPage.php';
function current_url() { return base_url('jobs-in-lagos-state'); }
function base_url($path = '') { return 'http://homepage.test/' . ltrim($path, '/'); }
function esc($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function uri_string() { return ''; }
function csrf_field() { return '<input type="hidden" name="csrf_test" value="fixture">'; }
function csrf_hash() { return 'fixture'; }
function auth() { return new class { function user() { return ($GLOBALS['argv'][2] ?? '') === 'signed-in' ? (object) ['user_type' => 'job_seeker'] : null; } function loggedIn() { return $this->user() !== null; } }; }
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
        $auth = auth(); $savedJobIds = [1];
        $name = ($GLOBALS['argv'][1] ?? '') === 'other' ? 'Rivers' : 'Lagos';
        $state = (object) ['id' => 1, 'name' => $name, 'region' => 'South West', 'capital' => 'Ikeja', 'description' => 'Find verified roles in ' . $name . '.', 'slug' => 'lagos-state'];
        if (str_starts_with($GLOBALS['argv'][1] ?? '', 'remote')) {
            $state->slug = 'remote';
            $state->name = 'Remote State';
            $state->capital = 'Nigeria';
        }
        if (($GLOBALS['argv'][1] ?? '') === 'nationwide') {
            $state->slug = 'nigeria-nationwide';
            $state->name = 'Nationwide';
        }
        $meta_description = 'Browse verified jobs in ' . $name . ', Nigeria.';
        $title = 'Jobs in ' . $name;
        $jobs = array_map(fn($i) => (object) ['id' => $i, 'title' => 'Software Engineer', 'employer_name' => 'Example Company', 'created_at' => date('Y-m-d'), 'state_name' => $name, 'is_featured' => $i === 1, 'is_verified' => 1, 'is_anonymous' => $i === 2, 'slug' => 'engineer-' . $i, 'min_salary' => 200000, 'max_salary' => 350000], range(1, 6));
        if (in_array($GLOBALS['argv'][1] ?? '', ['empty', 'remote-empty'], true)) $jobs = [];
        $total_jobs = count($jobs);
        $all_states = [(object) ['id' => 1, 'name' => $name, 'slug' => 'lagos-state'], (object) ['id' => 2, 'name' => 'Abuja', 'slug' => 'fct-abuja']];
        require FCPATH . 'app/Views/home/location_hub.php';
        echo '<!doctype html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $this->sections['styles'] . '</head><body class="homepage-reference">';
        require FCPATH . 'app/Views/partials/svg_sprites.php';
        require FCPATH . 'app/Views/templates/partials/header.php';
        echo $this->sections['content'];
        require FCPATH . 'app/Views/templates/partials/footer.php';
        echo $this->sections['scripts'] . '</body></html>';
    }
}
(new HomepagePreview())->render();
