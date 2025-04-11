<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ReminderSchedulers;

class ReminderSchedulersModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new ReminderSchedulers();
        $this->assertInstanceOf(ReminderSchedulers::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new ReminderSchedulers();
        $expected = ['frequency', 'isActive', 'duration', 'documentId', 'isEmailNotification', 'isRead', 'createdDate', 'userId', 'subject', 'message'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new ReminderSchedulers();
        $model->documents();
        $this->assertTrue(true);
    }

}
