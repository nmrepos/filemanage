<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Categories;

class CategoriesModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $category = new Categories();
        $this->assertInstanceOf(Categories::class, $category);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $category = new Categories();
        $expected = [
            'name', 'description', 'createdBy', 'parentId',
            'modifiedBy', 'isDeleted'
        ];
        $this->assertEquals($expected, $category->getFillable());
    }

    /** @test */
    public function it_has_custom_timestamps()
    {
        $this->assertEquals('createdDate', Categories::CREATED_AT);
        $this->assertEquals('modifiedDate', Categories::UPDATED_AT);
    }

    /** @test */
    public function it_casts_created_date_as_date()
    {
        $category = new Categories();
        $this->assertArrayHasKey('createdDate', $category->getCasts());
        $this->assertEquals('date', $category->getCasts()['createdDate']);
    }

    /** @test */
    public function it_has_relationship_methods()
    {
        $category = new Categories();
        $this->assertTrue(method_exists($category, 'documents'));
        $this->assertTrue(method_exists($category, 'childs'));
    }
}
