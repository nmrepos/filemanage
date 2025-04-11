<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\UserClaims;

class UserClaimsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new UserClaims();
        $this->assertInstanceOf(UserClaims::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new UserClaims();
        $expected = ['userId', 'actionId', 'claimType'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new UserClaims();
        $model->action();
        $model->user();
        $this->assertTrue(true);
    }

}
