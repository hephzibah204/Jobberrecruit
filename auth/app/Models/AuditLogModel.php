<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'action',
        'resource',
        'resource_id',
        'details',
        'ip_address',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function log(string $action, ?string $resource = null, ?int $resourceId = null, $details = null)
    {
        $userId = auth()->id();
        $ip = request()->getIPAddress();

        if (is_array($details) || is_object($details)) {
            $details = json_encode($details);
        }

        return $this->insert([
            'user_id'     => $userId,
            'action'      => $action,
            'resource'    => $resource,
            'resource_id' => $resourceId,
            'details'     => $details,
            'ip_address'  => $ip,
        ]);
    }
}
