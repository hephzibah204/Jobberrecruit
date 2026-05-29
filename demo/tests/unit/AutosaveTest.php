<?php

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\ResumeAutosaveModel;

final class AutosaveTest extends CIUnitTestCase
{
    protected $autosaveModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->autosaveModel = new ResumeAutosaveModel();
    }

    public function testInsertAndRetrieveSnapshot()
    {
        $snapshot = [
            'id' => null,
            'title' => 'Test Resume',
            'summary' => 'This is a test summary',
            'template_id' => 'classic',
            'experiences' => [
                ['company' => 'Acme', 'position' => 'Developer', 'description' => 'Built things']
            ],
            'education' => [],
            'skills' => 'PHP, JS'
        ];

        $data = [
            'resume_id' => 1,
            'user_id' => 1,
            'payload' => json_encode($snapshot),
            'metadata' => null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $id = $this->autosaveModel->insert($data);
        $this->assertIsInt($id);

        $row = $this->autosaveModel->find($id);
        $this->assertNotNull($row);
        $decoded = json_decode($row->payload, true);
        $this->assertEquals('Test Resume', $decoded['title']);
        $this->assertEquals('Acme', $decoded['experiences'][0]['company']);
    }
}
