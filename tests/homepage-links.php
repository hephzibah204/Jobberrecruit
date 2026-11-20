<?php
// Offline route-coverage regression: execute route declarations, not the app/DB.
define('ENVIRONMENT', 'testing');
function service($name) { return new class { public function routes($routes) {} }; }
class HomepageRouteInventory
{
    public array $paths = [];
    public array $redirects = [];
    private string $prefix = '';
    public function group($prefix, ...$args)
    {
        $previous = $this->prefix;
        $this->prefix = trim($previous . '/' . $prefix, '/');
        $callback = end($args);
        $callback($this);
        $this->prefix = $previous;
    }
    public function __call($method, $args)
    {
        if (in_array($method, ['get', 'match', 'add', 'addRedirect'], true)) {
            $path = $method === 'match' ? $args[1] : $args[0];
            $this->paths[] = trim($this->prefix . '/' . $path, '/');
            if ($method === 'addRedirect') $this->redirects[$path] = $args[1];
        }
        return $this;
    }
    public function contains(string $path): bool
    {
        foreach ($this->paths as $pattern) {
            $quoted = preg_quote($pattern, '~');
            $quoted = str_replace(['\(\:num\)', '\(\:segment\)', '\(\:any\)'], ['[0-9]+', '[^/]+', '.+'], $quoted);
            if (preg_match('~^' . $quoted . '$~', $path)) return true;
        }
        return false;
    }
}
$routes = new HomepageRouteInventory();
require dirname(__DIR__) . '/app/Config/Routes.php';
ob_start();
require __DIR__ . '/homepage-render.php';
$html = ob_get_clean();
preg_match_all('/<a href="([^"]+)" class="cat-card">/', $html, $categoryLinks);
if (count($categoryLinks[1]) !== 4) throw new RuntimeException('Expected four category cards');
foreach ($categoryLinks[1] as $href) {
    $url = parse_url(html_entity_decode($href, ENT_QUOTES, 'UTF-8'));
    if (!str_ends_with($url['path'], '-jobs') || isset($url['query'])) {
        throw new RuntimeException('Category card must open a category landing page: ' . $href);
    }
}
preg_match_all('/\bhref="([^"]+)"/', $html, $matches);
$checked = [];
foreach ($matches[1] as $href) {
    $url = parse_url(html_entity_decode($href, ENT_QUOTES, 'UTF-8'));
    if (($url['host'] ?? '') !== 'homepage.test') continue;
    $path = trim($url['path'] ?? '', '/');
    if (preg_match('~\.(css|png|jpg|svg|xml)$~', $path)) continue;
    if (!$routes->contains($path)) throw new RuntimeException('Unmatched homepage link: ' . $href);
    $checked[$path] = true;
}
foreach (['ai-tools/resume-builder' => 'candidate/resumes/build', 'ai-tools/mock-interview' => 'candidate/career-tools/mock-interview', 'training/cv-revamp' => 'cv-review', 'referral' => '/#referral'] as $old => $target) {
    if (($routes->redirects[$old] ?? null) !== $target) throw new RuntimeException('Missing redirect: ' . $old);
    if (!$routes->contains(trim(parse_url($target, PHP_URL_PATH), '/'))) throw new RuntimeException('Missing redirect target: ' . $target);
}
echo count($checked) . " distinct homepage links match declared routes; all four legacy redirects have valid targets.\n";
