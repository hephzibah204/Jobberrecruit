<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Helpers\AuthTestHelper;

final class ResumeAutosaveIntegrationTest extends CIUnitTestCase
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

        $result = $this->withHeaders(['Accept' => 'application/json'])
                       ->call('post', 'candidate/resumes/autosave', ['snapshot' => json_encode($snapshot)]);
        
        $result->assertOK();
        $body = json_decode($result->getJSON(), true);
        $this->assertArrayHasKey('autosave_id', $body);
    }
}
