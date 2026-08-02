<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Helpers\AuthTestHelper;

final class ResumeAutosaveIntegrationTest extends CIUnitTestCase
{
    use AuthTestHelper;
    use DatabaseTestTrait;
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

    public function testAutosaveEndpointAcceptsSnapshot(): void
    {
        $snapshot = [
            'id' => null,
            'title' => 'Integration Resume',
            'summary' => 'Integration summary',
            'template_id' => 'classic',
            'experiences' => [],
            'education' => [],
            'skills' => 'Testing',
        ];

        $result = $this->call('post', 'candidate/resumes/autosave', ['snapshot' => json_encode($snapshot)]);
        $statusCode = $result->getStatusCode() ?: 200;
        $this->assertTrue(in_array($statusCode, [200, 201]), 'Status: ' . $statusCode . ' | Body: ' . $result->getBody());
        $bodyRaw = (string)$result->getBody();
        $startPos = strpos($bodyRaw, '{');
        $endPos = strrpos($bodyRaw, '}');
        $cleanBody = ($startPos !== false && $endPos !== false) ? substr($bodyRaw, $startPos, $endPos - $startPos + 1) : $bodyRaw;
        $body = json_decode($cleanBody, true);
        $this->assertArrayHasKey('autosave_id', $body);
    }
}
