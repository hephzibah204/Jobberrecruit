<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RenewSubscriptions extends BaseCommand
{
    protected $group = 'Billing';
    protected $name = 'billing:renew-subscriptions';
    protected $description = 'Expire unpaid subscription periods; paid renewals are activated by verified payment events';

    public function run(array $params)
    {
        // Never extend a paid entitlement or fund a cash wallet just because
        // its end date passed. The payment webhook owns paid renewals.
        $model = model(\App\Models\UserSubscriptionModel::class);
        $model->where('is_active', 1)->where('ends_at <=', date('Y-m-d H:i:s'))
            ->set(['is_active' => 0])->update();
        CLI::write('Expired subscriptions closed. Paid renewals require a verified payment event.');
    }
}
