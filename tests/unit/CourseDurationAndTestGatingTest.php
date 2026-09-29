<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Controllers\ElearningController;

class CourseDurationAndTestGatingTest extends CIUnitTestCase
{
    private ElearningController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ElearningController();
    }

    public function testFormatCourseDurationWithNumericValueAndUnit()
    {
        $resDays = $this->controller->formatCourseDuration(null, '5', 'Days');
        $this->assertSame('5 Days', $resDays);

        $resDaySingle = $this->controller->formatCourseDuration(null, '1', 'days');
        $this->assertSame('1 Day', $resDaySingle);

        $resHours = $this->controller->formatCourseDuration(null, '12', 'Hours');
        $this->assertSame('12 Hours', $resHours);

        $resHourSingle = $this->controller->formatCourseDuration(null, '1', 'hour');
        $this->assertSame('1 Hour', $resHourSingle);
    }

    public function testFormatCourseDurationWithRawStringInputs()
    {
        $res1 = $this->controller->formatCourseDuration('10 days');
        $this->assertSame('10 Days', $res1);

        $res2 = $this->controller->formatCourseDuration('3 hours');
        $this->assertSame('3 Hours', $res2);

        $res3 = $this->controller->formatCourseDuration('2 weeks');
        $this->assertSame('14 Days', $res3);

        $res4 = $this->controller->formatCourseDuration('4');
        $this->assertSame('4 Days', $res4);
    }
}
