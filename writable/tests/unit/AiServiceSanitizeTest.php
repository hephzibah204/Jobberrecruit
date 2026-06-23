<?php

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\AiService;

final class AiServiceSanitizeTest extends CIUnitTestCase
{
    private AiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AiService();
    }

    public function testAllowsAllowedTagsAndStripsDisallowed(): void
    {
        $input = '<p>Hello</p><script>alert(1)</script><style>body{}</style><div>World</div>';
        $output = $this->callSanitize($input);
        $this->assertStringContainsString('<p>Hello</p>', $output);
        $this->assertStringContainsString('<div>World</div>', $output);
        $this->assertStringNotContainsString('<script>', $output);
        $this->assertStringNotContainsString('<style>', $output);
    }

    public function testStripsAttributesOnNonImgTags(): void
    {
        $input = '<p class="foo" style="color:red" onclick="evil()">Text</p>';
        $output = $this->callSanitize($input);
        $this->assertStringContainsString('<p>Text</p>', $output);
        $this->assertStringNotContainsString('class=', $output);
        $this->assertStringNotContainsString('style=', $output);
    }

    public function testPermitsImgWithValidAttrs(): void
    {
        $input = '<img src="data:image/png;base64,abc123" alt="Test" width="100" height="50">';
        $output = $this->callSanitize($input);
        $this->assertStringContainsString('src="data:image/png;base64,abc123"', $output);
        $this->assertStringContainsString('alt="Test"', $output);
        $this->assertStringContainsString('width="100"', $output);
    }

    public function testStripsHttpImgSources(): void
    {
        $input = '<img src="http://example.com/img.jpg">';
        $output = $this->callSanitize($input);
        $this->assertStringNotContainsString('src="http://', $output);
    }

    public function testKeepsHttpsImgSources(): void
    {
        $input = '<img src="https://example.com/img.jpg">';
        $output = $this->callSanitize($input);
        $this->assertStringContainsString('src="https://example.com/img.jpg"', $output);
    }

    public function testStripsEventHandlersFromImg(): void
    {
        $input = '<img src="data:image/png;base64,abc" onerror="alert(1)">';
        $output = $this->callSanitize($input);
        $this->assertStringContainsString('src="data:image/png;base64,abc"', $output);
        $this->assertStringNotContainsString('onerror=', $output);
    }

    public function testAllowsListAndTableTags(): void
    {
        $input = '<ul><li>Item</li></ul><ol><li>One</li></ol><table><tr><td>Cell</td></tr></table>';
        $output = $this->callSanitize($input);
        $this->assertStringContainsString('<ul>', $output);
        $this->assertStringContainsString('<ol>', $output);
        $this->assertStringContainsString('<table>', $output);
        $this->assertStringContainsString('<td>', $output);
    }

    private function callSanitize(string $input): string
    {
        $ref = new ReflectionMethod($this->service, 'sanitizeHtml');
        $ref->setAccessible(true);
        return $ref->invoke($this->service, $input);
    }
}
