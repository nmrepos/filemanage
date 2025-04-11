<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DocumentUserPermissions;

class DocumentUserPermissionsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new DocumentUserPermissions();
        $this->assertInstanceOf(DocumentUserPermissions::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new DocumentUserPermissions();
        $expected = ['documentId', 'userId', 'isTimeBound', 'startDate', 'endDate', 'isAllowDownload', 'createdBy', 'modifiedBy', 'isDeleted'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new DocumentUserPermissions();
        $model->user();
        $model->document();
        $this->assertTrue(true);
    }

}
