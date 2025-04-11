<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\QuarterlyReminders;

class QuarterlyRemindersModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new QuarterlyReminders();
        $this->assertInstanceOf(QuarterlyReminders::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new QuarterlyReminders();
        $expected = ['reminderId', 'day', 'month', 'quarter'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new QuarterlyReminders();
        $model->reminders();
        $this->assertTrue(true);
    }

}
