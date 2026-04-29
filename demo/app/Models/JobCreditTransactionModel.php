<?php

namespace App\Models;

use CodeIgniter\Model;

class JobCreditTransactionModel extends Model
{
    protected $table = 'job_credit_transactions';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id',
        'type',           // credit_in | credit_out | reset
        'credits',
        'reference',      // job_id | subscription_id | bundle_id | 'monthly_reset'
        'description',
        'meta'            // JSON for extra data (e.g. plan features used)
    ];
}
