<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Config\Gamification;

/**
 * @internal
 */
final class GamificationTest extends CIUnitTestCase
{
    public function testGamificationConfigDefaults(): void
    {
        $config = config('Gamification') ?? new Gamification();

        $this->assertSame(200, $config->xpPerMockInterview);
        $this->assertSame(100, $config->xpPerJobApplication);
        $this->assertSame(150, $config->xpPerAptitudeTest);
        $this->assertSame(150, $config->xpPerSalaryNegotiation);
        $this->assertSame(500, $config->xpPerLevel);
    }

    public function testLevelProgressionAtZeroXp(): void
    {
        $config = new Gamification();
        $data = $config->getLevelData(0);

        $this->assertSame(1, $data['level']);
        $this->assertSame(2, $data['next_level']);
        $this->assertSame(500, $data['next_level_threshold']);
        $this->assertSame(500, $data['needed_xp']);
        $this->assertSame(0, $data['progress_pct']);
        $this->assertSame(1, $config->calculateLevel(0));
    }

    public function testLevelProgressionPartialProgress(): void
    {
        $config = new Gamification();
        $data = $config->getLevelData(250);

        $this->assertSame(1, $data['level']);
        $this->assertSame(2, $data['next_level']);
        $this->assertSame(500, $data['next_level_threshold']);
        $this->assertSame(250, $data['needed_xp']);
        $this->assertSame(50, $data['progress_pct']);
    }

    public function testLevelProgressionMilestone(): void
    {
        $config = new Gamification();
        $data = $config->getLevelData(500);

        $this->assertSame(2, $data['level']);
        $this->assertSame(3, $data['next_level']);
        $this->assertSame(1000, $data['next_level_threshold']);
        $this->assertSame(500, $data['needed_xp']);
        $this->assertSame(0, $data['progress_pct']);
        $this->assertSame(2, $config->calculateLevel(500));
    }

    public function testLevelProgressionHigherLevel(): void
    {
        $config = new Gamification();
        $data = $config->getLevelData(1250);

        $this->assertSame(3, $data['level']);
        $this->assertSame(4, $data['next_level']);
        $this->assertSame(1500, $data['next_level_threshold']);
        $this->assertSame(250, $data['needed_xp']);
        $this->assertSame(50, $data['progress_pct']);
        $this->assertSame(3, $config->calculateLevel(1250));
    }
}
