<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Roles;

class RolesModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new Roles();
        $this->assertInstanceOf(Roles::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new Roles();
        $expected = ['name', 'createdBy', 'modifiedBy', 'isDeleted'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new Roles();
        $model->userRoles();
        $model->roleClaims();
        $model->documentRolePermissions();
        $model->documentAuditTrails();
        $this->assertTrue(true);
    }

}
