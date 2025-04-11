<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Actions;

class ActionsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $action = new Actions();
        $this->assertInstanceOf(Actions::class, $action);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $action = new Actions();
        $expected = [
            'name','order','pageId','createdBy','code',
            'modifiedBy', 'isDeleted'
        ];
        $this->assertEquals($expected, $action->getFillable());
    }

    /** @test */
    public function it_has_relationship_methods()
    {
        $action = new Actions();

        $action->pages();
        $action->roleClaims();
        $action->userClaims();

        $this->assertTrue(true);
    }

}
