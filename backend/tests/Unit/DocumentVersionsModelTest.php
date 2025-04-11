<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DocumentVersions;

class DocumentVersionsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new DocumentVersions();
        $this->assertInstanceOf(DocumentVersions::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new DocumentVersions();
        $expected = ['documentId', 'url', 'createdBy', 'modifiedBy', 'isDeleted', 'location'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new DocumentVersions();
        $model->users();
        $model->documents();
        $this->assertTrue(true);
    }

}
