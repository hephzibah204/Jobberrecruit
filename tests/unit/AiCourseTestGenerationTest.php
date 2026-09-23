<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\AiService;

class AiCourseTestGenerationTest extends CIUnitTestCase
{
    private AiService $aiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->aiService = new AiService();
    }

    public function testGenerateCourseTestQuestionsReturnsStructuredQuestions()
    {
        $questions = $this->aiService->generateCourseTestQuestions('Fullstack Web Development', 'Master HTML, CSS, JavaScript, and PHP', 3);

        $this->assertIsArray($questions);
        $this->assertNotEmpty($questions);
        $this->assertCount(3, $questions);

        $firstQ = $questions[0];
        $this->assertArrayHasKey('question', $firstQ);
        $this->assertArrayHasKey('options', $firstQ);
        $this->assertIsArray($firstQ['options']);
        $this->assertNotEmpty($firstQ['options']);
    }

    public function testGetFallbackCourseTestQuestions()
    {
        $fallback = $this->aiService->getFallbackCourseTestQuestions('Python Data Science', 2);
        $this->assertIsArray($fallback);
        $this->assertCount(2, $fallback);
        $this->assertStringContainsString('Python Data Science', $fallback[0]['question']);
    }
}
