<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\AiImageModel;

final class AiImageModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $migrateOnce = false;
    protected $namespace = 'App';

    private AiImageModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new AiImageModel();
    }

    public function testInsertPendingAndFindByOrigin(): void
    {
        $id = $this->model->insert([
            'origin_url' => 'https://example.com/img.png',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->assertIsInt($id);

        $row = $this->model->findByOriginUrl('https://example.com/img.png');
        $this->assertNotNull($row);
        $this->assertEquals('pending', $row->status);
    }

    public function testFindPendingReturnsPendingOnly(): void
    {
        $this->model->insert(['origin_url' => 'https://a.com/1.png', 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s')]);
        $this->model->insert(['origin_url' => 'https://a.com/2.png', 'status' => 'completed', 'proxied_path' => 'uploads/2.png', 'checksum' => 'abc', 'mime' => 'image/png', 'size' => 100, 'created_at' => date('Y-m-d H:i:s')]);

        $pending = $this->model->findPending(10);
        $this->assertCount(1, $pending);
        $this->assertEquals('https://a.com/1.png', $pending[0]->origin_url);
    }

    public function testMarkCompleted(): void
    {
        $id = $this->model->insert([
            'origin_url' => 'https://example.com/photo.jpg',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->model->markCompleted($id, 'uploads/photo.jpg', 'checksum123', 'image/jpeg', 2048);

        $row = $this->model->find($id);
        $this->assertEquals('completed', $row->status);
        $this->assertEquals('checksum123', $row->checksum);
        $this->assertEquals('uploads/photo.jpg', $row->proxied_path);
    }

    public function testMarkFailed(): void
    {
        $id = $this->model->insert([
            'origin_url' => 'https://example.org/bad.png',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->model->markFailed($id, 'Download error');

        $row = $this->model->find($id);
        $this->assertEquals('failed', $row->status);
        $this->assertEquals('Download error', $row->error);
    }
}
