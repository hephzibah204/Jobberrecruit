<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    // protected $allowedFields = [
    //     'user_id','wallet_id','purpose',
    //     'reference','amount','amount_paid',
    //     'currency','status','gateway_response',
    //     'channel','ip_address','metadata'
    // ];

    protected $allowedFields = [
        'user_id',
        'employer_id',
        'reference',
        'amount',
        'status',
        'payment_method',
        'metadata',
        'paid_at'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    protected $cast = [
        'metadata' => 'json'
    ];
}
