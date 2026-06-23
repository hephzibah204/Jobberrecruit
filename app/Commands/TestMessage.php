<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ConversationModel;
use App\Models\MessageModel;

class TestMessage extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'test:message';
    protected $description = 'Test sending a message';

    public function run(array $params)
    {
        $conversationModel = model(ConversationModel::class);
        $messageModel = model(MessageModel::class);

        $employerId = 1;
        $seekerId = 1;
        $userId = 1;
        $message = "Test message from CLI";

        try {
            $conversation = $conversationModel
                ->where('employer_id', $employerId)
                ->where('job_seeker_id', $seekerId)
                ->first();

            if (!$conversation) {
                CLI::write("Creating new conversation...", "yellow");
                $conversationId = $conversationModel->insert([
                    'employer_id' => $employerId,
                    'job_seeker_id' => $seekerId,
                    'job_id' => null,
                    'last_message' => $message,
                    'last_message_at' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                ]);

                if (!$conversationId) {
                    CLI::error("Failed to create conversation: " . json_encode($conversationModel->errors()));
                    return;
                }
            } else {
                CLI::write("Updating existing conversation...", "yellow");
                $conversationId = $conversation['id'];
                $conversationModel->update($conversationId, [
                    'last_message' => $message,
                    'last_message_at' => date('Y-m-d H:i:s'),
                ]);
            }

            CLI::write("Conversation ID: $conversationId", "green");

            $insertData = [
                'conversation_id' => $conversationId,
                'sender_id' => $userId,
                'sender_type' => 'employer',
                'message' => $message,
                'is_read' => 0,
            ];

            CLI::write("Inserting message...", "yellow");
            $messageId = $messageModel->insert($insertData);

            if (!$messageId) {
                CLI::error("Failed to send message: " . json_encode($messageModel->errors()));
            } else {
                CLI::write("Message sent successfully. ID: $messageId", "green");
            }
        } catch (\Exception $e) {
            CLI::error("Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}
