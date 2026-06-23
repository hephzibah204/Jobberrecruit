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
        $builder = $this->db->table('messages');
        $builder->join('conversations', 'conversations.id = messages.conversation_id');

        if ($userType === 'employer') {
            $employerModel = model(\App\Models\EmployerModel::class);
            $employer = $employerModel->where('user_id', $userId)->first();
            if (!$employer) return 0;
            $builder->where('conversations.employer_id', $employer->id);
            $builder->where('messages.sender_type', 'job_seeker');
        } else {
            $seekerModel = model(\App\Models\JobSeekerModel::class);
            $seeker = $seekerModel->where('user_id', $userId)->first();
            if (!$seeker) return 0;
            $builder->where('conversations.job_seeker_id', $seeker->id);
            $builder->where('messages.sender_type', 'employer');
        }

        $builder->where('messages.is_read', 0);
        return $builder->countAllResults();
    }
}
