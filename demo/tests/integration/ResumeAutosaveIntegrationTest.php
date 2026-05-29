<?php

use CodeIgniter\Test\FeatureTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Helpers\AuthTestHelper;

final class ResumeAutosaveIntegrationTest extends FeatureTestCase
{
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
        $this->assertTrue(in_array($result->getStatusCode(), [200, 201]));
        $body = json_decode((string)$result->getBody(), true);
        $this->assertArrayHasKey('autosave_id', $body);
    }
}
