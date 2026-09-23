<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

class CourseModulesCreationTest extends CIUnitTestCase
{
    public function testCourseModuleDataMapping()
    {
        $moduleTitles = ['Module 1: Intro', 'Module 2: Advanced'];
        $moduleDescriptions = ['Overview of course', 'Deep dive topics'];
        $moduleSources = ['none', 'youtube'];
        $moduleYoutubeUrls = ['', 'https://www.youtube.com/watch?v=12345'];

        $mapped = [];
        foreach ($moduleTitles as $idx => $title) {
            $mapped[] = [
                'course_id' => 99,
                'title' => $title,
                'description' => $moduleDescriptions[$idx],
                'content_source' => $moduleSources[$idx],
                'youtube_url' => $moduleSources[$idx] === 'youtube' ? $moduleYoutubeUrls[$idx] : null,
                'order_index' => $idx + 1
            ];
        }

        $this->assertCount(2, $mapped);
        $this->assertEquals('Module 1: Intro', $mapped[0]['title']);
        $this->assertEquals('https://www.youtube.com/watch?v=12345', $mapped[1]['youtube_url']);
        $this->assertNull($mapped[0]['youtube_url']);
    }
}
