<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ReminderNotifications;

class ReminderNotificationsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new ReminderNotifications();
        $this->assertInstanceOf(ReminderNotifications::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new ReminderNotifications();
        $expected = ['reminderId', 'subject', 'description', 'fetchDateTime', 'isDeleted', 'isEmailNotification'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new ReminderNotifications();
        $model->reminders();
        $this->assertTrue(true);
    }

}
