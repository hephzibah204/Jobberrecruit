<?php

namespace App\Models;

use CodeIgniter\Model;

class JobQueueModel extends Model
{
    protected $table            = 'queue_jobs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['queue', 'payload', 'attempts', 'status', 'error', 'available_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Dispatch a job to the queue
     */
    public function dispatch($type, $data, $queue = 'default', $delay = 0)
    {
        $payload = [
            'type' => $type,
            'data' => $data
        ];

        return $this->insert([
            'queue' => $queue,
            'payload' => json_encode($payload),
            'status' => 'pending',
            'available_at' => date('Y-m-d H:i:s', time() + $delay)
        ]);
    }

    /**
     * Get pending jobs
     */
    public function getPending($limit = 10)
    {
        return $this->where('status', 'pending')
                    ->where('available_at <=', date('Y-m-d H:i:s'))
                    ->orderBy('created_at', 'ASC')
                    ->findAll($limit);
    }
}
