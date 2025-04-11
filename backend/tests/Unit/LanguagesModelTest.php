<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Languages;

class LanguagesModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new Languages();
        $this->assertInstanceOf(Languages::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new Languages();
        $expected = ['code', 'name', 'order', 'imageUrl', 'createdBy', 'modifiedBy', 'isDeleted', 'isRTL'];
        $this->assertEquals($expected, $model->getFillable());
    }
}
