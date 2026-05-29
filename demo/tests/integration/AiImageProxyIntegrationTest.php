<?php

use CodeIgniter\Test\FeatureTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FilterTestTrait;
use Tests\Support\Helpers\AuthTestHelper;

final class AiImageProxyIntegrationTest extends FeatureTestCase
{
    use AuthTestHelper;
    use DatabaseTestTrait;
    use FilterTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = true;
    protected $refresh     = true;
    protected $namespace   = null;

    protected $setUpMethods = [
        'resetFactories',
        'mockCache',
        'mockEmail',
        'mockSession',
        'setUpAuth',
    ];

    public function testRejectsMissingOriginUrl(): void
    {
        $result = $this->call('post', 'candidate/resumes/ai/proxy-image', []);
        $this->assertEquals(400, $result->getStatusCode());
    }

    public function testRejectsHttpUrl(): void
    {
        $result = $this->call('post', 'candidate/resumes/ai/proxy-image', [
            'origin_url' => 'http://example.com/img.jpg',
        ]);
        $this->assertEquals(400, $result->getStatusCode());
        $body = json_decode((string)$result->getBody(), true);
        $this->assertStringContainsString('https', ($body['messages'] ?? $body['message'] ?? ''));
    }

    public function testRejectsInvalidUrl(): void
    {
        $result = $this->call('post', 'candidate/resumes/ai/proxy-image', [
            'origin_url' => 'not-a-url',
        ]);
        $this->assertEquals(400, $result->getStatusCode());
    }

    /**
     * Requires network access to download the remote image.
     * @group network
     */
    public function testAcceptsHttpsUrlAndReturnsProxiedUrl(): void
    {
        $result = $this->call('post', 'candidate/resumes/ai/proxy-image', [
            'origin_url' => 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&s=200',
        ]);
        $this->assertTrue(in_array($result->getStatusCode(), [200, 201]), 'Status: ' . $result->getStatusCode());

        $body = json_decode((string)$result->getBody(), true);
        $this->assertArrayHasKey('url', $body);
        $this->assertStringContainsString('/uploads/ai-images/', $body['url']);
    }
}
