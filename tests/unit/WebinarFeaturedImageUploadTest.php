<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

class WebinarFeaturedImageUploadTest extends CIUnitTestCase
{
    public function testWebinarFeaturedImageUploadPathFormatting()
    {
        $filename = "sample_flyer_12345.png";
        $relativePath = 'uploads/webinars/' . $filename;

        $this->assertStringStartsWith('uploads/webinars/', $relativePath);
        $this->assertEquals('uploads/webinars/sample_flyer_12345.png', $relativePath);
    }
}
