<?php

namespace App\Services;

/** Serialize a user's purchases and commit payment and entitlement together. */
class PurchaseTransaction
{
    public static function run(int $userId, callable $operation, ?\CodeIgniter\Database\BaseConnection $db = null)
    {
        $db ??= db_connect();
        // A previous rolled-back operation must not poison an unrelated root
        // transaction on a reused connection. Never clear a nested failure.
        if ($db->transDepth === 0) $db->resetTransStatus();
        if (!$db->transBegin()) throw new \RuntimeException('Could not start the transaction.');
        try {
            $table = $db->protectIdentifiers($db->prefixTable('users'));
            $suffix = $db->DBDriver === 'SQLite3' ? '' : ' FOR UPDATE';
            // SQLite obtains its write lock before reading any purchase state.
            if ($db->DBDriver === 'SQLite3') {
                $db->query("UPDATE {$table} SET id = id WHERE id = ?", [$userId]);
                if (!$db->transStatus()) {
                    throw new \RuntimeException('Could not lock the account for this transaction.');
                }
            }
            if (!$db->query("SELECT id FROM {$table} WHERE id = ?{$suffix}", [$userId])->getRow()) {
                throw new \RuntimeException('Purchase account not found.');
            }
            $result = $operation();
            if (!$db->transStatus()) {
                log_message('error', 'Transaction database error: ' . json_encode($db->error()));
                throw new \RuntimeException('The purchase could not be recorded.');
            }
            if (!$db->transCommit()) throw new \RuntimeException('Could not commit the transaction.');
            return $result;
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }
}
