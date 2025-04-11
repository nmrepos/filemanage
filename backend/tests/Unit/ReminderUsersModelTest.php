<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ReminderUsers;

class ReminderUsersModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new ReminderUsers();
        $this->assertInstanceOf(ReminderUsers::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new ReminderUsers();
        $expected = ['reminderId', 'userId'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new ReminderUsers();
        $model->user();
        $model->reminder();
        $this->assertTrue(true);
    }

}
