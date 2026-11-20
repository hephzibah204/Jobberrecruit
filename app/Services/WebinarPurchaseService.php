<?php
namespace App\Services;

use App\Models\PaymentModel;
use App\Models\WebinarModel;
use App\Models\WebinarRegistrationModel;
use CodeIgniter\Database\BaseConnection;

class WebinarPurchaseService
{
    private BaseConnection $db;
    public function __construct(?BaseConnection $db = null) { $this->db = $db ?? db_connect(); }

    public function register(int $userId, int $webinarId, bool $wallet = false): array
    {
        $result = PurchaseTransaction::run($userId, function () use ($userId, $webinarId, $wallet) {
            $webinar = (new WebinarModel($this->db))->find($webinarId);
            if (!$webinar) throw new \InvalidArgumentException('Webinar not found.');
            if ($prior = $this->registration($userId, $webinarId)) return ['id'=>(int) $prior->id, 'created'=>false];
            if (!WebinarModel::registrationOpen($webinar)) throw new \InvalidArgumentException('Registration for this webinar is closed.');
            $price = ($webinar->access_type ?? 'free') === 'paid' ? (float) $webinar->price : 0.0;
            if (!is_finite($price) || $price < 0) throw new \InvalidArgumentException('Invalid webinar price.');
            if ($price > 0 && !$wallet) throw new \InvalidArgumentException('Payment is required for this webinar.');
            if ($price > 0) {
                $reference = 'webinar_wallet_' . bin2hex(random_bytes(16));
                (new WalletService($this->db))->debit($userId, $price, 'webinar', $reference, $webinarId, 'Webinar registration');
                if (!(new PaymentModel($this->db))->insert(['user_id'=>$userId, 'reference'=>$reference,
                    'amount'=>$price, 'amount_paid'=>$price, 'currency'=>'NGN', 'status'=>'paid',
                    'payment_method'=>'wallet', 'paid_at'=>date('Y-m-d H:i:s'),
                    'metadata'=>json_encode(['type'=>'webinar','user_id'=>$userId,'webinar_id'=>$webinarId])])) {
                    throw new \RuntimeException('Payment could not be recorded.');
                }
            }
            return $this->createRegistration($userId, $webinarId);
        }, $this->db);
        if ($result['created']) $this->notify($userId, $webinarId);
        return $result;
    }

    public function createOrder(int $userId, int $webinarId): array
    {
        $webinar = (new WebinarModel($this->db))->find($webinarId);
        if (!$webinar || !WebinarModel::registrationOpen($webinar) || ($webinar->access_type ?? '') !== 'paid') {
            throw new \InvalidArgumentException('This webinar is unavailable for purchase.');
        }
        if ($this->registration($userId, $webinarId)) throw new \InvalidArgumentException('You are already registered.');
        $price = (float) $webinar->price;
        $reference = (new PaymentFulfillmentService($this->db))->createPending($userId, 'webinar', $price, ['webinar_id'=>$webinarId]);
        return ['reference'=>$reference, 'amount'=>$price,
            'metadata'=>['type'=>'webinar','user_id'=>$userId,'webinar_id'=>$webinarId,'reference'=>$reference]];
    }

    public function fulfill(array $data, ?int $expectedUserId = null, ?int $expectedWebinarId = null): array
    {
        $payments = new PaymentModel($this->db);
        $order = $payments->where('reference', (string) ($data['reference'] ?? ''))->first();
        if (!$order || ($expectedUserId !== null && (int) $order['user_id'] !== $expectedUserId)) {
            throw new \InvalidArgumentException('Payment order not found for this account.');
        }
        $stored = PaymentFulfillmentService::metadata($order);
        $webinarId = (int) ($stored['webinar_id'] ?? 0);
        if (($stored['type'] ?? '') !== 'webinar' || $webinarId < 1 || ($expectedWebinarId !== null && $webinarId !== $expectedWebinarId)) {
            throw new \InvalidArgumentException('Payment belongs to a different webinar.');
        }
        $userId = (int) $order['user_id'];
        $result = PurchaseTransaction::run($userId, function () use ($payments, $order, $data, $webinarId, $userId) {
            $identity = $this->db->table('auth_identities')->where('user_id', $userId)->where('type','email_password')->get()->getRow();
            $meta = PaymentFulfillmentService::metadata($data);
            PaymentFulfillmentService::validate($data, $order, $meta, (string) ($identity->secret ?? ''));
            if ((int) ($meta['webinar_id'] ?? 0) !== $webinarId) throw new \InvalidArgumentException('Payment webinar does not match.');
            if (!(new WebinarModel($this->db))->find($webinarId)) throw new \InvalidArgumentException('Webinar not found.');
            $prior = $this->registration($userId, $webinarId);
            $result = $prior ? ['id'=>(int) $prior->id, 'created'=>false] : $this->createRegistration($userId, $webinarId);
            if (!$payments->update($order['id'], ['status'=>'paid','amount_paid'=>$order['amount'],
                'paid_at'=>($order['paid_at'] ?? null) ?: date('Y-m-d H:i:s'), 'payment_method'=>'paystack',
                'channel'=>$data['channel'] ?? 'card','gateway_response'=>json_encode($data)])) {
                throw new \RuntimeException('Payment could not be recorded.');
            }
            return $result;
        }, $this->db);
        if ($result['created']) $this->notify($userId, $webinarId);
        return $result + ['type'=>'webinar','webinar_id'=>$webinarId];
    }

    private function registration(int $userId, int $webinarId): ?object
    {
        return (new WebinarRegistrationModel($this->db))->where('user_id',$userId)->where('webinar_id',$webinarId)->first();
    }

    private function createRegistration(int $userId, int $webinarId): array
    {
        $id = (new WebinarRegistrationModel($this->db))->insert(['user_id'=>$userId,'webinar_id'=>$webinarId]);
        if (!$id || !(new WebinarModel($this->db))->where('id',$webinarId)->increment('registrants_count',1)) {
            throw new \RuntimeException('Registration could not be saved.');
        }
        return ['id'=>(int) $id,'created'=>true];
    }

    private function notify(int $userId, int $webinarId): void
    {
        if (ENVIRONMENT === 'testing') return;
        try {
            $user = model(\App\Models\UserModel::class)->find($userId);
            $webinar = (new WebinarModel($this->db))->find($webinarId);
            if ($user && $webinar) (new EmailNotificationService())->sendWebinarRegistrationNotification($user, $webinar);
        } catch (\Throwable $e) { log_message('error', 'Webinar confirmation failed: ' . $e->getMessage()); }
    }
}
