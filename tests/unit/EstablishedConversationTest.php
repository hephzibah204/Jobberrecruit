<?php

use App\Models\ConversationModel;
use CodeIgniter\Test\CIUnitTestCase;

final class EstablishedConversationTest extends CIUnitTestCase
{
    public function testReplyAccessRequiresMatchingParticipantsAndMessageHistory(): void
    {
        $db = \Config\Database::connect([
            'DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true,
        ], false);
        try {
            $db->query('CREATE TABLE conversations (id INTEGER PRIMARY KEY, employer_id INTEGER, job_seeker_id INTEGER)');
            $db->query('CREATE TABLE messages (id INTEGER PRIMARY KEY, conversation_id INTEGER)');
            $db->table('conversations')->insert(['id' => 1, 'employer_id' => 12, 'job_seeker_id' => 24]);
            $model = new ConversationModel($db);
            $this->assertFalse($model->hasEstablishedConversation(12, 24), 'Empty deep-linked threads must not bypass unlocks');
            $db->table('messages')->insert(['id' => 1, 'conversation_id' => 1]);
            $this->assertTrue($model->hasEstablishedConversation(12, 24), 'Existing participants may continue their conversation');
            $this->assertFalse($model->hasEstablishedConversation(13, 24), 'Another employer must not gain access');
            $this->assertFalse($model->hasEstablishedConversation(12, 25), 'History with another candidate must not grant access');
        } finally {
            $db->close();
        }
    }
}
