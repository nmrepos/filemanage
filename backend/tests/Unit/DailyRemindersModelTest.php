<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DailyReminders;

class DailyRemindersModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $reminder = new DailyReminders();
        $this->assertInstanceOf(DailyReminders::class, $reminder);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $reminder = new DailyReminders();
        $expected = ['reminderId', 'dayOfWeek', 'isActive'];
        $this->assertEquals($expected, $reminder->getFillable());
    }

    /** @test */
    public function it_casts_is_active_as_boolean()
    {
        $reminder = new DailyReminders();
        $this->assertArrayHasKey('isActive', $reminder->getCasts());
        $this->assertEquals('boolean', $reminder->getCasts()['isActive']);
    }

    /** @test */
    public function it_has_relationship_method()
    {
        $reminder = new DailyReminders();
        $this->assertTrue(method_exists($reminder, 'reminders'));
    }

    /** @test */
    public function it_is_not_incrementing_and_has_no_timestamps()
    {
        $reminder = new DailyReminders();
        $this->assertFalse($reminder->incrementing);
        $this->assertFalse($reminder->timestamps);
    }
}
