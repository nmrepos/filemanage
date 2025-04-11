<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PageHelper;

class PageHelperModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new PageHelper();
        $this->assertInstanceOf(PageHelper::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new PageHelper();
        $expected = ['name', 'code', 'description'];
        $this->assertEquals($expected, $model->getFillable());
    }
}
