<?php

namespace App\Services;

use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;

class WalletService
{
    protected WalletModel $walletModel;
    protected WalletTransactionModel $txModel;
    protected BaseConnection $db;

    public function __construct()
    {
        $this->walletModel = model(WalletModel::class);
        $this->txModel     = model(WalletTransactionModel::class);
        $this->db          = db_connect();
    }

    public function getOrCreateWallet(int $userId)
    {
        $wallet = $this->walletModel->where('user_id', $userId)->first();

        if (! $wallet) {
            try {
                $id = $this->walletModel->insert([
                    'user_id' => $userId,
                    'balance' => 0,
                ]);

                return $this->walletModel->find($id);
            } catch (\Throwable $e) {
                // Handle concurrent create attempts by reloading the existing wallet.
                $wallet = $this->walletModel->where('user_id', $userId)->first();
                if ($wallet) {
                    return $wallet;
                }

                throw $e;
            }
        }

        return $wallet;
    }

    public function credit(
        int $userId,
        float $amount,
        string $source,
        string $reference,
        ?int $sourceId = null,
        ?string $description = null
    ): void {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Credit amount must be greater than zero');
        }

        $this->db->transBegin();

        try {
            $wallet = $this->getOrCreateWallet($userId);
            $lockedWallet = $this->lockWalletForUpdate((int) $wallet->id);

            $before = (float) $lockedWallet->balance;
            $after  = $before + $amount;

            $this->walletModel->update($lockedWallet->id, ['balance' => $after]);

            $this->txModel->insert([
                'wallet_id'       => $lockedWallet->id,
                'type'            => 'credit',
                'amount'          => $amount,
                'balance_before'  => $before,
                'balance_after'   => $after,
                'source'          => $source,
                'source_id'       => $sourceId,
                'reference'       => $reference,
                'description'     => $description,
            ]);

            if ($this->db->transStatus() === false) {
                throw new DatabaseException('Wallet credit failed');
            }

            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function debit(
        int $userId,
        float $amount,
        string $source,
        string $reference,
        ?int $sourceId = null,
        ?string $description = null
    ): void {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Debit amount must be greater than zero');
        }

        if ($this->txModel->where('reference', $reference)->countAllResults() > 0) {
            return;
        }

        $this->db->transBegin();

        try {
            $wallet = $this->getOrCreateWallet($userId);
            $lockedWallet = $this->lockWalletForUpdate((int) $wallet->id);

            if ($lockedWallet->is_locked || $lockedWallet->balance < $amount) {
                throw new \RuntimeException('Insufficient wallet balance');
            }

            $before = (float) $lockedWallet->balance;
            $after  = $before - $amount;

            $this->walletModel->update($lockedWallet->id, ['balance' => $after]);

            $this->txModel->insert([
                'wallet_id'       => $lockedWallet->id,
                'type'            => 'debit',
                'amount'          => $amount,
                'balance_before'  => $before,
                'balance_after'   => $after,
                'source'          => $source,
                'source_id'       => $sourceId,
                'reference'       => $reference,
                'description'     => $description,
            ]);

            if ($this->db->transStatus() === false) {
                throw new DatabaseException('Wallet debit failed');
            }

            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    private function lockWalletForUpdate(int $walletId): object
    {
        // IMPORTANT: must already be inside a transaction
        $wallet = $this->db->query(
            'SELECT * FROM wallets WHERE id = ? FOR UPDATE',
            [$walletId]
        )->getRow();

        if (! $wallet) {
            throw new \RuntimeException('Wallet not found');
        }

        return $wallet;
    }

    // private function lockWalletForUpdate(int $walletId): object
    // {
    //     $wallet = $this->db->table('wallets')
    //         ->where('id', $walletId)
    //         ->lockForUpdate()
    //         ->get()
    //         ->getRow();

    //     if (! $wallet) {
    //         throw new \RuntimeException('Wallet not found');
    //     }

    //     return $wallet;
    // }
}
