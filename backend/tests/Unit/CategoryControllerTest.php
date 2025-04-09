<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryControllerTest extends TestCase
{
    /**
     * Test that index() calls findWhere with ['parentId' => null] and returns JSON.
     */
    public function testIndexReturnsJson()
    {
        $expectedData = [
            ['id' => 1, 'name' => 'Category 1', 'parentId' => null]
        ];

        $repositoryMock = $this->createMock(CategoryRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('findWhere')
            ->with(['parentId' => null])
            ->willReturn($expectedData);

        $controller = new CategoryController($repositoryMock);
        $response = $controller->index();

        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    /**
     * Test that create() returns a 409 response when validation fails.
     */
    public function testCreateFailsValidation()
    {
        // Create a request with an empty payload so that the required field "name" fails.
        $request = Request::create('/categories', 'POST', []);

        $repositoryMock = $this->createMock(CategoryRepositoryInterface::class);
        $controller = new CategoryController($repositoryMock);
        $response = $controller->create($request);

        $this->assertEquals(409, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('name', $responseData);
    }

    /**
     * Test that update() returns a 409 response when validation fails.
     */
    public function testUpdateFailsValidation()
    {
        $data = []; // Missing the required "name" field.
        $id = 5;
        $request = Request::create("/categories/{$id}", 'PUT', $data);

        $repositoryMock = $this->createMock(CategoryRepositoryInterface::class);
        $controller = new CategoryController($repositoryMock);
        $response = $controller->update($request, $id);

        $this->assertEquals(409, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('name', $responseData);
    }

    /**
     * Test that destroy() returns a 200 response when the category is successfully deleted.
     */
    public function testDestroySuccess()
    {
        $id = 3;
        $repositoryMock = $this->createMock(CategoryRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('deleteCategory')
            ->with($id)
            ->willReturn(true);

        $controller = new CategoryController($repositoryMock);
        $response = $controller->destroy($id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode([]), $response->getContent());
    }

    /**
     * Test that destroy() returns a 404 response when the category cannot be deleted.
     */
    public function testDestroyFail()
    {
        $id = 3;
        $repositoryMock = $this->createMock(CategoryRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('deleteCategory')
            ->with($id)
            ->willReturn(false);

        $controller = new CategoryController($repositoryMock);
        $response = $controller->destroy($id);

        $this->assertEquals(404, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Category can not be deleted. Document is assign to this category.', $responseData['message']);
    }

    /**
     * Test that subcategories() returns the expected list of subcategories.
     */
    public function testSubcategories()
    {
        $parentId = 2;
        $expectedData = [
            ['id' => 4, 'name' => 'Subcategory 1', 'parentId' => $parentId],
            ['id' => 5, 'name' => 'Subcategory 2', 'parentId' => $parentId],
        ];

        $repositoryMock = $this->createMock(CategoryRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('findWhere')
            ->with(['parentId' => $parentId])
            ->willReturn($expectedData);

        $controller = new CategoryController($repositoryMock);
        $response = $controller->subcategories($parentId);

        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    /**
     * Test that GetAllCategoriesForDropDown() returns all categories.
     */
    public function testGetAllCategoriesForDropDown()
    {
        $expectedData = [
            ['id' => 1, 'name' => 'Category 1'],
            ['id' => 2, 'name' => 'Category 2'],
        ];

        $repositoryMock = $this->createMock(CategoryRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('all')
            ->willReturn($expectedData);

        $controller = new CategoryController($repositoryMock);
        $response = $controller->GetAllCategoriesForDropDown();

        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }
}
