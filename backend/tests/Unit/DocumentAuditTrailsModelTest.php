<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DocumentAuditTrails;

class DocumentAuditTrailsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $audit = new DocumentAuditTrails();
        $this->assertInstanceOf(DocumentAuditTrails::class, $audit);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $audit = new DocumentAuditTrails();
        $expected = [
            'documentId', 'operationName','assignToUserId','assignToRoleId',
            'createdBy', 'modifiedBy', 'isDeleted'
        ];
        $this->assertEquals($expected, $audit->getFillable());
    }

    /** @test */
    public function it_has_custom_timestamps()
    {
        $this->assertEquals('createdDate', DocumentAuditTrails::CREATED_AT);
        $this->assertEquals('modifiedDate', DocumentAuditTrails::UPDATED_AT);
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $audit = new DocumentAuditTrails();
        $this->assertFalse($audit->incrementing);
    }

    /** @test */
    public function it_has_relationship_methods()
    {
        $audit = new DocumentAuditTrails();

        $this->assertTrue(method_exists($audit, 'user'));
        $this->assertTrue(method_exists($audit, 'document'));
        $this->assertTrue(method_exists($audit, 'roles'));
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new DocumentAuditTrails();
        $model->user();
        $model->document();
        $model->roles();
        $this->assertTrue(true);
    }
}
