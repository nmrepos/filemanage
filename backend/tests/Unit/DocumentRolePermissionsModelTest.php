<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DocumentRolePermissions;

class DocumentRolePermissionsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $perm = new DocumentRolePermissions();
        $this->assertInstanceOf(DocumentRolePermissions::class, $perm);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $perm = new DocumentRolePermissions();
        $expected = [
            'documentId', 'roleId', 'isTimeBound', 'startDate', 'endDate',
            'isAllowDownload', 'createdBy', 'modifiedBy', 'isDeleted'
        ];
        $this->assertEquals($expected, $perm->getFillable());
    }

    /** @test */
    public function it_has_custom_timestamps()
    {
        $this->assertEquals('createdDate', DocumentRolePermissions::CREATED_AT);
        $this->assertEquals('modifiedDate', DocumentRolePermissions::UPDATED_AT);
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $perm = new DocumentRolePermissions();
        $this->assertFalse($perm->incrementing);
    }

    /** @test */
    public function it_has_relationship_methods()
    {
        $perm = new DocumentRolePermissions();

        $this->assertTrue(method_exists($perm, 'role'));
        $this->assertTrue(method_exists($perm, 'document'));
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new DocumentRolePermissions();
        $model->role();
        $model->document();
        $this->assertTrue(true);
    }
}
