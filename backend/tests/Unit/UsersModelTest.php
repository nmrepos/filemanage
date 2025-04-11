<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Users;

class UsersModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new Users();
        $this->assertInstanceOf(Users::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new Users();
        $expected = ['firstName', 'lastName', 'userName', 'email', 'emailConfirmed', 'phoneNumberConfirmed', 'twoFactorEnabled', 'lockoutEnabled', 'accessFailedCount', 'password', 'isDeleted', 'phoneNumber', 'resetPasswordCode'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new Users();
        $model->userRoles();
        $model->userClaims();
        $model->documentUserPermissions();
        $model->userNotifications();
        $this->assertTrue(true);
    }

}
