<?php

// Verify the approved copy and render every category without a database.
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'app/');
require APPPATH . 'Services/CategorySeoCopy.php';

function base_url($path = '') { return 'https://example.test/' . ltrim($path, '/'); }
function current_url() { return base_url('energy-oil-gas'); }
function uri_string() { return 'energy-oil-gas'; }
function esc($value, $context = 'html') { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function csrf_hash() { return 'test-token'; }
function csrf_field() { return ''; }
function check($condition, $message) { if (!$condition) throw new RuntimeException($message); }

final class CategorySeoPreview
{
    public array $sections = [];
    private string $active = '';
    public function extend($name) {}
    public function section($name) { $this->active = $name; ob_start(); }
    public function endSection() { $this->sections[$this->active] = ob_get_clean(); }
    public function render(string $slug, array $copy): void
    {
        $industry = (object) ['id' => 1, 'slug' => $slug, 'name' => $copy['name'],
            'seo_h1' => $copy['title'], 'description' => $copy['body']];
        $meta_description = $copy['meta_description'];
        $auth = new class { public function loggedIn() { return false; } };
        $jobs = []; $total_jobs = 0; $all_industries = []; $is_category = true;
        require APPPATH . 'Views/home/industry_hub.php';
    }
}

$entries = json_decode(file_get_contents(APPPATH . 'Config/category_seo.json'), true, 512, JSON_THROW_ON_ERROR);
check(count($entries) === 23, 'Expected all 23 categories');
$titles = $descriptions = [];
foreach ($entries as $slug => $entry) {
    $copy = \App\Services\CategorySeoCopy::find($slug);
    check($copy['title'] === $entry['title'] && $copy['body'] === $entry['body'], "Copy mismatch: $slug");
    check(\App\Services\CategorySeoCopy::find($slug . '-jobs') === $copy, "Legacy URL mismatch: $slug");
    check(\App\Services\CategorySeoCopy::find('', $entry['name']) === $copy, "Name lookup mismatch: $slug");
    check(strlen($copy['meta_description']) <= 160, "Overlong description: $slug");
    $titles[] = $copy['title']; $descriptions[] = $copy['meta_description'];
    $preview = new CategorySeoPreview();
    $preview->render($slug, $copy);
    $html = $preview->sections['content'];
    preg_match('/<h1>(.*?)<\/h1>/s', $html, $heading);
    check(html_entity_decode(strip_tags($heading[1]), ENT_QUOTES, 'UTF-8') === $entry['title'], "Wrong H1: $slug");
    check(str_contains($html, esc($entry['body'])), "Body missing on empty category: $slug");
    check(substr_count($html, '<h1>') === 1, "Duplicate H1: $slug");
    preg_match('/<script[^>]*>(.*?)<\/script>/s', $preview->sections['schema'], $schema);
    $graph = json_decode($schema[1], true, 512, JSON_THROW_ON_ERROR);
    check(count($graph['@graph']) === 1, "Empty category emitted ItemList: $slug");
}
check(count(array_unique($titles)) === 23, 'Duplicate titles');
check(count(array_unique($descriptions)) === 23, 'Duplicate descriptions');
check(\App\Services\CategorySeoCopy::find('finance-accounting')['title'] === $entries['accounting-finance-banking']['title'], 'Legacy finance alias');
check(\App\Services\CategorySeoCopy::find('information-technology-it-software')['title'] === $entries['information-technology-software']['title'], 'Legacy IT alias');
check(\App\Services\CategorySeoCopy::find('unknown-category') === null, 'Unknown category must retain database fallback');
echo "PASS: 23 category headings, body copies, unique descriptions, aliases, and empty-page rendering.\n";
