<?php
define('FCPATH', __DIR__ . '/public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);
require 'public/index.php';

use App\Models\ConversationModel;
use App\Models\MessageModel;

$conversationModel = model(ConversationModel::class);
$messageModel = model(MessageModel::class);

$employerId = 1;
$seekerId = 1;

try {
    $conversationId = $conversationModel->insert([
        'employer_id' => $employerId,
        'job_seeker_id' => $seekerId,
        'job_id' => null,
        'last_message' => 'Test message',
        'last_message_at' => date('Y-m-d H:i:s'),
        'is_active' => 1,
    ]);
    if (!$conversationId) {
        echo "Failed to create conversation: " . json_encode($conversationModel->errors()) . "\n";
    } else {
        echo "Created conversation ID: $conversationId\n";
    }
} catch (\Exception $e) {
    echo "Exception creating conversation: " . $e->getMessage() . "\n";
}

try {
    $insertData = [
        'conversation_id' => 1,
        'sender_id' => 1,
        'sender_type' => 'employer',
        'message' => 'Hello',
        'is_read' => 0,
    ];
    $messageId = $messageModel->insert($insertData);
    if (!$messageId) {
        echo "Failed to create message: " . json_encode($messageModel->errors()) . "\n";
    } else {
        echo "Created message ID: $messageId\n";
    }
} catch (\Exception $e) {
    echo "Exception creating message: " . $e->getMessage() . "\n";
}
