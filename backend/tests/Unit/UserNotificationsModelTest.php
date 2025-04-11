<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\UserNotifications;

class UserNotificationsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new UserNotifications();
        $this->assertInstanceOf(UserNotifications::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new UserNotifications();
        $expected = ['userId', 'message', 'isRead', 'documentId'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new UserNotifications();
        $model->user();
        $model->documents();
        $this->assertTrue(true);
    }

}
