<?php

use CodeIgniter\Test\FeatureTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Helpers\AuthTestHelper;

final class ResumeRestoreAutosaveIntegrationTest extends FeatureTestCase
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

    public function testRestoreFailsWithoutAutosaveId(): void
    {
        $result = $this->call('post', 'candidate/resumes/1/restore-autosave', []);
        $this->assertEquals(400, $result->getStatusCode());
    }

    public function testRestoreFailsForMissingAutosave(): void
    {
        $result = $this->call('post', 'candidate/resumes/1/restore-autosave', [
            'autosave_id' => 99999,
        ]);
        $this->assertEquals(404, $result->getStatusCode());
    }

    public function testAutosaveThenRestoreRoundTrip(): void
    {
        $snapshot = [
            'id' => null,
            'title' => 'Round Trip Resume',
            'summary' => 'Restored summary content',
            'template_id' => 'modern',
            'experiences' => [
                ['company' => 'Acme', 'position' => 'Engineer', 'start_date' => '2020-01-01', 'end_date' => '2023-12-31', 'is_current' => false, 'description' => 'Did things'],
            ],
            'education' => [
                ['institution' => 'State U', 'degree' => 'BS', 'field_of_study' => 'CS', 'graduation_year' => '2019'],
            ],
            'skills' => 'PHP, Testing',
        ];

        $saveResult = $this->call('post', 'candidate/resumes/autosave', ['snapshot' => json_encode($snapshot)]);
        $this->assertTrue(in_array($saveResult->getStatusCode(), [200, 201]));
        $saveBody = json_decode((string)$saveResult->getBody(), true);
        $this->assertArrayHasKey('autosave_id', $saveBody);

        $autosaveId = $saveBody['autosave_id'];

        $restoreResult = $this->call('post', 'candidate/resumes/1/restore-autosave', [
            'autosave_id' => $autosaveId,
        ]);
        $this->assertEquals(200, $restoreResult->getStatusCode());
        $restoreBody = json_decode((string)$restoreResult->getBody(), true);

        $this->assertArrayHasKey('payload', $restoreBody);
        $this->assertSame($snapshot['title'], $restoreBody['payload']['title']);
        $this->assertSame($snapshot['summary'], $restoreBody['payload']['summary']);
        $this->assertSame($snapshot['template_id'], $restoreBody['payload']['template_id']);
        $this->assertCount(1, $restoreBody['payload']['experiences']);
        $this->assertSame('Acme', $restoreBody['payload']['experiences'][0]['company']);
        $this->assertCount(1, $restoreBody['payload']['education']);
        $this->assertSame('State U', $restoreBody['payload']['education'][0]['institution']);
        $this->assertArrayHasKey('created_at', $restoreBody);
    }
}
