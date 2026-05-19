<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table      = 'messages';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'conversation_id',
        'sender_id',
        'sender_type',
        'message',
        'is_read',
        'read_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getConversationMessages(int $conversationId, int $userId, string $userType): array
    {
        $messages = $this->where('conversation_id', $conversationId)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        if (!empty($messages)) {
            $now = date('Y-m-d H:i:s');
            $readField = $userType === 'employer' ? 'employer_last_read' : 'seeker_last_read';
            model(ConversationModel::class)->update($conversationId, [
                $readField => $now,
            ]);

            $this->where('conversation_id', $conversationId)
                ->where('sender_id !=', $userId)
                ->where('is_read', 0)
                ->set(['is_read' => 1, 'read_at' => $now])
                ->update();
        }

        return $messages;
    }

    public function getUnreadCount(int $userId, string $userType): int
    {
        $senderType = $userType === 'employer' ? 'job_seeker' : 'employer';
        return $this->where('sender_type', $senderType)
            ->where('is_read', 0)
            ->countAllResults();
    }
}
