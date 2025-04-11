<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\HalfYearlyReminders;

class HalfYearlyRemindersModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new HalfYearlyReminders();
        $this->assertInstanceOf(HalfYearlyReminders::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new HalfYearlyReminders();
        $expected = ['reminderId', 'day', 'month', 'quarter'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new HalfYearlyReminders();
        $model->reminders();
        $this->assertTrue(true);
    }

}
