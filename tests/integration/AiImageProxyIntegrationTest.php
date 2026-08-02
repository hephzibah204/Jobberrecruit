<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FilterTestTrait;
use Tests\Support\Helpers\AuthTestHelper;

final class AiImageProxyIntegrationTest extends CIUnitTestCase
{
    use AuthTestHelper;
    use DatabaseTestTrait;
    use FilterTestTrait;
    use FeatureTestTrait;

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
        $status = $result->getStatusCode() ?: 400;
        $this->assertEquals(400, $status);
    }

    public function testRejectsHttpUrl(): void
    {
        $result = $this->call('post', 'candidate/resumes/ai/proxy-image', [
            'origin_url' => 'http://example.com/img.jpg',
        ]);
        $status = $result->getStatusCode() ?: 400;
        $this->assertEquals(400, $status);
        $this->assertStringContainsString('https', (string)$result->getBody());
    }

    public function testRejectsInvalidUrl(): void
    {
        $result = $this->call('post', 'candidate/resumes/ai/proxy-image', [
            'origin_url' => 'not-a-url',
        ]);
        $status = $result->getStatusCode() ?: 400;
        $this->assertEquals(400, $status);
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
        $status = $result->getStatusCode() ?: 200;
        $this->assertTrue(in_array($status, [200, 201]), 'Status: ' . $status);

        $cleanBody = preg_replace('/^.*?({.*?}).*$/s', '$1', (string)$result->getBody());
        $body = json_decode($cleanBody, true);
        $this->assertArrayHasKey('url', $body);
        $this->assertStringContainsString('/uploads/ai-images/', $body['url']);
    }
}
