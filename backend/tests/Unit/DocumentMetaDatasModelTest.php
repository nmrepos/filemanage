<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DocumentMetaDatas;

class DocumentMetaDatasModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $meta = new DocumentMetaDatas();
        $this->assertInstanceOf(DocumentMetaDatas::class, $meta);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $meta = new DocumentMetaDatas();
        $expected = [
            'documentId', 'metatag', 'createdBy',
            'modifiedBy', 'isDeleted'
        ];
        $this->assertEquals($expected, $meta->getFillable());
    }

    /** @test */
    public function it_has_custom_timestamps()
    {
        $this->assertEquals('createdDate', DocumentMetaDatas::CREATED_AT);
        $this->assertEquals('modifiedDate', DocumentMetaDatas::UPDATED_AT);
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $meta = new DocumentMetaDatas();
        $this->assertFalse($meta->incrementing);
    }

    /** @test */
    public function it_has_relationship_methods()
    {
        $meta = new DocumentMetaDatas();

        $this->assertTrue(method_exists($meta, 'user'));
        $this->assertTrue(method_exists($meta, 'documents'));
    }
}
