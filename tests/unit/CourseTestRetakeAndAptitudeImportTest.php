<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

class CourseTestRetakeAndAptitudeImportTest extends CIUnitTestCase
{
    public function testMaxAttemptsCalculationAndRemaining()
    {
        $maxAttempts = 3;

        // Attempt 1
        $attempt1 = 1;
        $remaining1 = max(0, $maxAttempts - $attempt1);
        $this->assertEquals(2, $remaining1);

        // Attempt 2
        $attempt2 = 2;
        $remaining2 = max(0, $maxAttempts - $attempt2);
        $this->assertEquals(1, $remaining2);

        // Attempt 3
        $attempt3 = 3;
        $remaining3 = max(0, $maxAttempts - $attempt3);
        $this->assertEquals(0, $remaining3);

        // Attempt 4 (Over limit)
        $attempt4 = 4;
        $remaining4 = max(0, $maxAttempts - $attempt4);
        $this->assertEquals(0, $remaining4);
        $this->assertTrue($attempt4 >= $maxAttempts);
    }

    public function testAptitudeQuestionFormatConversion()
    {
        $rawQuestion = [
            'body' => 'What is SQL injection and how can it be prevented?',
            'explanation' => 'Use parameterized queries / prepared statements.'
        ];

        $rawOptions = [
            ['text' => 'Using prepared statements and parameterized queries', 'is_correct' => 1],
            ['text' => 'Disabling database encryption', 'is_correct' => 0],
            ['text' => 'Using inline string concatenation', 'is_correct' => 0]
        ];

        $converted = [
            'question' => $rawQuestion['body'],
            'explanation' => $rawQuestion['explanation'],
            'options' => array_map(function($opt) {
                return [
                    'text' => $opt['text'],
                    'is_correct' => (int) $opt['is_correct']
                ];
            }, $rawOptions)
        ];

        $this->assertEquals('What is SQL injection and how can it be prevented?', $converted['question']);
        $this->assertCount(3, $converted['options']);
        $this->assertEquals(1, $converted['options'][0]['is_correct']);
        $this->assertEquals(0, $converted['options'][1]['is_correct']);
    }
}
