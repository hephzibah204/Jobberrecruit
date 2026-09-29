<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\TestModel;

class AdminAptitudeTestCreationTest extends CIUnitTestCase
{
    public function testUniqueSlugGenerationLogic()
    {
        $title = "Fullstack Web Developer Aptitude Test!";
        $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        $this->assertEquals("fullstack-web-developer-aptitude-test-", $baseSlug);
        
        // Sanitize trailing dash
        $cleanSlug = trim($baseSlug, '-');
        $this->assertEquals("fullstack-web-developer-aptitude-test", $cleanSlug);
    }

    public function testQuestionPayloadFormatConversion()
    {
        $rawQuestions = [
            [
                'question' => 'What is 2 + 2?',
                'explanation' => 'Simple math',
                'options' => [
                    ['text' => '3', 'is_correct' => 0],
                    ['text' => '4', 'is_correct' => 1]
                ]
            ]
        ];

        $json = json_encode($rawQuestions);
        $decoded = json_decode($json, true);

        $this->assertCount(1, $decoded);
        $this->assertEquals('What is 2 + 2?', $decoded[0]['question']);
        $this->assertEquals(1, $decoded[0]['options'][1]['is_correct']);
    }
}
