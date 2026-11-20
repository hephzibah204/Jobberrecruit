<?php

use App\Models\JobQueueModel;
use App\Services\EmailQueueWorker;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;

final class QueueWorkflowRegressionTest extends CIUnitTestCase
{
    private $connection;
    private JobQueueModel $queue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = Database::connect(config('Database')->tests, false);
        $this->connection->query('CREATE TABLE queue_jobs (id INTEGER PRIMARY KEY AUTOINCREMENT, queue TEXT, payload TEXT, attempts INTEGER DEFAULT 0, status TEXT, error TEXT, available_at TEXT, created_at TEXT, updated_at TEXT)');
        require_once APPPATH . 'Database/Migrations/2026-10-16-000001_AddQueueLeases.php';
        $migration = new \App\Database\Migrations\AddQueueLeases(Database::forge($this->connection));
        $migration->up();
        $this->queue = new JobQueueModel($this->connection);
    }

    protected function tearDown(): void
    {
        $this->connection->close();
        parent::tearDown();
    }

    public function testDelayedEmailsAreNotClaimedAndCampaignTypeLivesInPayload(): void
    {
        $this->queue->dispatch('campaign_email', ['email' => 'later@example.com'], 'default', 3600);
        $id = $this->queue->dispatch('campaign_email', ['email' => 'now@example.com']);
        $claimed = $this->queue->claimNext(['campaign_email']);
        $this->assertSame((int) $id, (int) $claimed->id);
        $this->assertNull($this->queue->claimNext(['campaign_email']));
        $this->assertTrue($this->queue->finishClaim($claimed, true));
    }

    public function testWorkersCannotClaimTheSameActiveJob(): void
    {
        $this->queue->dispatch('newsletter_email', []);
        $first = $this->queue->claimNext();
        $secondWorker = new JobQueueModel($this->connection);
        $this->assertNull($secondWorker->claimNext());
        $this->assertTrue($this->queue->finishClaim($first, true));
    }

    public function testAbandonedClaimsAreRecoveredAndOldOwnerCannotAcknowledge(): void
    {
        $this->queue->dispatch('newsletter_email', []);
        $old = $this->queue->claimNext();
        $this->queue->update($old->id, ['reserved_until' => '2000-01-01 00:00:00']);
        $new = $this->queue->claimNext();
        $this->assertSame((int) $old->id, (int) $new->id);
        $this->assertNotSame($old->claim_token, $new->claim_token);
        $this->assertFalse($this->queue->finishClaim($old, true));
        $this->assertTrue($this->queue->finishClaim($new, true));
    }

    public function testFailuresBackOffAndStopAtThreeAttempts(): void
    {
        $id = $this->queue->dispatch('transactional_email', []);
        $worker = new class($this->queue) extends EmailQueueWorker {
            protected function deliver(string $type, array $data): bool { return false; }
        };
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $this->queue->update($id, ['available_at' => '2000-01-01 00:00:00']);
            $this->assertSame(1, $worker->process(1)['jobs_failed']);
            $row = $this->queue->find($id);
            $this->assertSame($attempt, (int) $row->attempts);
            $this->assertSame($attempt === 3 ? 'failed' : 'pending', $row->status);
            $this->assertNull($this->queue->claimNext());
        }
    }
}
