<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Pages;

class PagesModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new Pages();
        $this->assertInstanceOf(Pages::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new Pages();
        $expected = ['name', 'order', 'modifiedBy', 'isDeleted'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new Pages();
        $model->actionas();
        $this->assertTrue(true);
    }

}
