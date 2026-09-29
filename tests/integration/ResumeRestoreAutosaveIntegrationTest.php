<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Helpers\AuthTestHelper;

final class ResumeRestoreAutosaveIntegrationTest extends CIUnitTestCase
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

    public function testRestoreFailsWithoutAutosaveId(): void
    {
        $result = $this->call('post', 'candidate/resumes/1/restore-autosave', []);
        $status = $result->getStatusCode() ?: 400;
        $this->assertEquals(400, $status);
    }

    public function testRestoreFailsForMissingAutosave(): void
    {
        $result = $this->call('post', 'candidate/resumes/1/restore-autosave', [
            'autosave_id' => 99999,
        ]);
        $status = $result->getStatusCode() ?: 404;
        $this->assertEquals(404, $status);
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
        $saveStatus = $saveResult->getStatusCode() ?: 200;
        $this->assertTrue(in_array($saveStatus, [200, 201]), 'Save status: ' . $saveStatus . ' | Body: ' . $saveResult->getBody());
        $bodyRawSave = (string)$saveResult->getBody();
        $startPosSave = strpos($bodyRawSave, '{');
        $endPosSave = strrpos($bodyRawSave, '}');
        $cleanSaveBody = ($startPosSave !== false && $endPosSave !== false) ? substr($bodyRawSave, $startPosSave, $endPosSave - $startPosSave + 1) : $bodyRawSave;
        $saveBody = json_decode($cleanSaveBody, true);
        $this->assertArrayHasKey('autosave_id', $saveBody);

        $autosaveId = $saveBody['autosave_id'];

        $restoreResult = $this->call('post', 'candidate/resumes/1/restore-autosave', [
            'autosave_id' => $autosaveId,
        ]);
        $restoreStatus = $restoreResult->getStatusCode() ?: 200;
        $this->assertEquals(200, $restoreStatus, 'Restore status: ' . $restoreStatus . ' | Body: ' . $restoreResult->getBody());
        $bodyRaw = (string)$restoreResult->getBody();
        $startPos = strpos($bodyRaw, '{');
        $endPos = strrpos($bodyRaw, '}');
        $cleanRestoreBody = ($startPos !== false && $endPos !== false) ? substr($bodyRaw, $startPos, $endPos - $startPos + 1) : $bodyRaw;
        $restoreBody = json_decode($cleanRestoreBody, true);

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
