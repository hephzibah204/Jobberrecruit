<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Admin two-factor authentication enrolments.
 *
 * One row per admin user (user_id is the primary key). A row is created
 * when an admin starts setup (enabled = 0) and only starts being
 * enforced by AdminFilter once the admin confirms a valid TOTP code
 * (enabled = 1). Deleting a row disables 2FA for that admin entirely —
 * that is also the documented recovery path for a lost authenticator.
 */
class Admin2faModel extends Model
{
    protected $table            = 'admin_2fa';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'secret',
        'enabled',
        'confirmed_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
