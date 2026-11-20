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
    protected $allowedFields    = ['queue', 'payload', 'attempts', 'status', 'error', 'available_at', 'claim_token', 'reserved_until'];

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
     * Dispatch a job to the queue and execute it immediately in the background.
     */
    public function dispatchAndRun($type, $data, $queue = 'default', $delay = 0)
    {
        $id = $this->dispatch($type, $data, $queue, $delay);

        // Build command to execute spark queue:work
        $cmd = PHP_BINARY . ' ' . escapeshellarg(ROOTPATH . 'spark') . ' queue:work';

        if (DIRECTORY_SEPARATOR === '\\') {
            // Windows: Run in background using popen/start
            pclose(popen("start /B " . $cmd, "r"));
        } else {
            // Linux/macOS: Run in background using nohup or exec with redirect
            exec("nohup " . $cmd . " > /dev/null 2>&1 &");
        }

        return $id;
    }

    /**
     * Get pending jobs
     */
    public function getPending($limit = 10)
    {
        return $this->where('status', 'pending')
                    ->groupStart()->where('available_at <=', date('Y-m-d H:i:s'))->orWhere('available_at', null)->groupEnd()
                    ->orderBy('created_at', 'ASC')
                    ->findAll($limit);
    }

    public function claimNext(?array $types = null): ?object
    {
        $now = date('Y-m-d H:i:s');
        // Recover abandoned claims. Ownership tokens prevent late workers from
        // acknowledging a job which has since been claimed by another worker.
        $this->builder()->where('status', 'processing')->groupStart()
            ->where('reserved_until <', $now)
            ->orGroupStart()->where('reserved_until', null)->where('updated_at <', date('Y-m-d H:i:s', time() - 300))->groupEnd()
            ->groupEnd()->update(['status' => 'pending', 'claim_token' => null, 'reserved_until' => null]);

        $afterId = 0;
        do {
            $candidates = $this->where('status', 'pending')->where('id >', $afterId)
                ->groupStart()->where('available_at <=', $now)->orWhere('available_at', null)->groupEnd()
                ->orderBy('id', 'ASC')->findAll(100);
            foreach ($candidates as $job) {
                $afterId = (int) $job->id;
                $payload = json_decode($job->payload, true) ?: [];
                if ($types !== null && !in_array($payload['type'] ?? '', $types, true)) {
                    continue;
                }
                if ((int) $job->attempts >= 3) {
                    $this->builder()->where('id', $job->id)->where('status', 'pending')
                        ->update(['status' => 'failed', 'error' => 'Retry limit reached after an abandoned delivery.']);
                    continue;
                }
                $token = bin2hex(random_bytes(24));
                $this->builder()->where('id', $job->id)->where('status', 'pending')
                    ->groupStart()->where('available_at <=', $now)->orWhere('available_at', null)->groupEnd()
                    ->set('attempts', 'attempts + 1', false)
                    ->update(['status' => 'processing', 'claim_token' => $token,
                        'reserved_until' => date('Y-m-d H:i:s', time() + 300), 'updated_at' => $now]);
                if ($this->db->affectedRows() === 1) {
                    return $this->where('id', $job->id)->where('claim_token', $token)->first();
                }
            }
        } while (count($candidates) === 100);
        return null;
    }

    public function finishClaim(object $job, bool $success, ?string $error = null, bool $permanent = false): bool
    {
        $status = $success ? 'completed' : (($permanent || (int) $job->attempts >= 3) ? 'failed' : 'pending');
        $this->builder()->where('id', $job->id)->where('status', 'processing')->where('claim_token', $job->claim_token)
            ->update(['status' => $status, 'error' => $success ? null : $error,
                'claim_token' => null, 'reserved_until' => null, 'updated_at' => date('Y-m-d H:i:s'),
                'available_at' => date('Y-m-d H:i:s', time() + 60 * (2 ** max(0, (int) $job->attempts - 1)))]);
        return $this->db->affectedRows() === 1;
    }
}
