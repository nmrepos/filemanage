<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\UserRoles;

class UserRolesModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new UserRoles();
        $this->assertInstanceOf(UserRoles::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new UserRoles();
        $expected = ['userId', 'roleId'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new UserRoles();
        $model->user();
        $model->role();
        $this->assertTrue(true);
    }

}
