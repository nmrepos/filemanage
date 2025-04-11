<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Reminders;

class RemindersModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new Reminders();
        $this->assertInstanceOf(Reminders::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new Reminders();
        $expected = ['subject', 'message', 'frequency', 'startDate', 'endDate', 'dayOfWeek', 'isRepeated', 'isEmailNotification', 'documentId', 'createdBy', 'modifiedBy', 'isDeleted'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new Reminders();
        $model->documents();
        $model->reminderUsers();
        $model->dailyReminders();
        $model->remindernotifications();
        $model->halfYearlyReminders();
        $model->quarterlyReminders();
        $this->assertTrue(true);
    }

}
