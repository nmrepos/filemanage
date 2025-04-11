<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DocumentShareableLink;

class DocumentShareableLinkModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new DocumentShareableLink();
        $this->assertInstanceOf(DocumentShareableLink::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new DocumentShareableLink();
        $expected = ['documentId', 'linkExpiryTime', 'password', 'linkCode', 'isAllowDownload', 'createdBy', 'createdDate', 'modifiedBy', 'isDeleted'];
        $this->assertEquals($expected, $model->getFillable());
    }

    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new DocumentShareableLink();
        $model->document();
        $this->assertTrue(true);
    }

}
