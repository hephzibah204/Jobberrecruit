<?php
namespace App\Models;

use CodeIgniter\Model;

class AiImageModel extends Model
{
    protected $table = 'ai_images';
    protected $primaryKey = 'id';
    protected $allowedFields = ['origin_url','proxied_path','checksum','mime','size','status','error','created_at','processed_at'];
    protected $useTimestamps = false;

    public function findPending($limit = 20)
    {
        return $this->where('status', 'pending')->orderBy('created_at', 'ASC')->findAll($limit);
    }

    public function findByOriginUrl(string $url)
    {
        return $this->where('origin_url', $url)->first();
    }

    public function markCompleted(int $id, string $proxiedPath, string $checksum, string $mime, int $size): void
    {
        $this->update($id, [
            'proxied_path' => $proxiedPath,
            'checksum' => $checksum,
            'mime' => $mime,
            'size' => $size,
            'status' => 'completed',
            'processed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function markFailed(int $id, string $error): void
    {
        $this->update($id, [
            'status' => 'failed',
            'error' => $error,
            'processed_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
