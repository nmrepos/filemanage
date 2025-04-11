<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Documents;

class DocumentsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $doc = new Documents();
        $this->assertInstanceOf(Documents::class, $doc);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $doc = new Documents();
        $expected = [
            'categoryId',
            'name',
            'description',
            'url',
            'createdBy',
            'modifiedBy',
            'isIndexed',
            'isDeleted',
            'location',
            'isPermanentDelete'
        ];
        $this->assertEquals($expected, $doc->getFillable());
    }

    /** @test */
    public function it_has_custom_timestamps()
    {
        $this->assertEquals('createdDate', Documents::CREATED_AT);
        $this->assertEquals('modifiedDate', Documents::UPDATED_AT);
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $doc = new Documents();
        $this->assertFalse($doc->incrementing);
    }

    /** @test */
    public function it_casts_booleans_correctly()
    {
        $doc = new Documents();
        $casts = $doc->getCasts();

        $this->assertEquals('boolean', $casts['isIndexed']);
        $this->assertEquals('boolean', $casts['isDeleted']);
        $this->assertEquals('boolean', $casts['isPermanentDelete']);
    }

    /** @test */
    public function it_has_relationship_methods()
    {
        $doc = new Documents();

        $this->assertTrue(method_exists($doc, 'categories'));
        $this->assertTrue(method_exists($doc, 'users'));
        $this->assertTrue(method_exists($doc, 'documentMetaDatas'));
        $this->assertTrue(method_exists($doc, 'documentComments'));
        $this->assertTrue(method_exists($doc, 'userNotifications'));
        $this->assertTrue(method_exists($doc, 'reminderSchedulers'));
        $this->assertTrue(method_exists($doc, 'reminders'));
        $this->assertTrue(method_exists($doc, 'documentVersions'));
        $this->assertTrue(method_exists($doc, 'documentUserPermissions'));
        $this->assertTrue(method_exists($doc, 'documentRolePermissions'));
        $this->assertTrue(method_exists($doc, 'documentAuditTrails'));
    }
}
