<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DocumentTokens;

class DocumentTokensModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new DocumentTokens();
        $this->assertInstanceOf(DocumentTokens::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new DocumentTokens();
        $expected = ['documentId', 'token', 'createdDate'];
        $this->assertEquals($expected, $model->getFillable());
    }
}
