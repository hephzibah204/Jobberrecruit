<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Helpers\AuthTestHelper;

final class AiImageProxyIntegrationTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use AuthTestHelper;
    use DatabaseTestTrait;

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
        $result->assertStatus(400);
    }

    public function testRejectsHttpUrl(): void
    {
        $result = $this->call('post', 'candidate/resumes/ai/proxy-image', [
            'origin_url' => 'http://example.com/img.jpg',
        ]);
        $result->assertStatus(400);
        $result->assertSee('https');
    }

    public function testRejectsInvalidUrl(): void
    {
        $result = $this->call('post', 'candidate/resumes/ai/proxy-image', [
            'origin_url' => 'not-a-url',
        ]);
        $result->assertStatus(400);
    }

    /**
     * Requires network access to download the remote image.
     * @group network
     */
    public function testAcceptsHttpsUrlAndReturnsProxiedUrl(): void
    {
        $result = $this->withHeaders(['Accept' => 'application/json'])
                       ->call('post', 'candidate/resumes/ai/proxy-image', [
            'origin_url' => 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&s=200',
        ]);
        
        $result->assertOK();

        $body = json_decode($result->getJSON(), true);
        $this->assertIsArray($body, 'Body was not valid JSON: ' . $result->getBody());
        $this->assertArrayHasKey('url', $body);
        $this->assertStringContainsString('/uploads/ai-images/', $body['url']);
    }
}
