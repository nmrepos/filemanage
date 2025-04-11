<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\RoleClaims;

class RoleClaimsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new RoleClaims();
        $this->assertInstanceOf(RoleClaims::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new RoleClaims();
        $expected = ['roleId', 'actionId', 'claimType'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new RoleClaims();
        $model->action();
        $model->role();
        $this->assertTrue(true);
    }

}
