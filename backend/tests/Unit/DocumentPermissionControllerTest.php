<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\DocumentPermissionController;
use App\Repositories\Contracts\DocumentPermissionRepositoryInterface;

class DocumentPermissionControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of the repository interface
        $this->repository = $this->createMock(DocumentPermissionRepositoryInterface::class);
        // Inject it into the controller
        $this->controller = new DocumentPermissionController($this->repository);
    }

    public function testIndexReturnsJsonResponse()
    {
        // Fake data to be returned by the repository
        $fakeData = [
            ['id' => 1, 'permission' => 'read'],
            ['id' => 2, 'permission' => 'write']
        ];

        // Expect the "all" method to be called once and return fake data
        $this->repository->expects($this->once())
            ->method('all')
            ->willReturn($fakeData);

        // Call the index method and capture the response
        $response = $this->controller->index();

        // Convert the JSON response into an array for assertion
        $responseData = $response->getData(true);

        // Assert the response data matches the fake data
        $this->assertEquals($fakeData, $responseData);
    }

    public function testAddDocumentRolePermissionReturnsJsonResponse()
    {
        // Sample input data for role permission
        $input = ['role_id' => 1, 'document_id' => 5];
        // Expected repository output
        $expectedOutput = ['success' => true];

        // Expect the repository method to be invoked with the same input
        $this->repository->expects($this->once())
            ->method('addDocumentRolePermission')
            ->with($input)
            ->willReturn($expectedOutput);

        // Create a new request instance with the sample input
        $request = new Request($input);
        $response = $this->controller->addDocumentRolePermission($request);
        $responseData = $response->getData(true);

        $this->assertEquals($expectedOutput, $responseData);
    }

    public function testEditReturnsJsonResponse()
    {
        $id = 1;
        $expectedOutput = ['permission' => 'example'];

        // Expect the getDocumentPermissionList to be called with the correct ID
        $this->repository->expects($this->once())
            ->method('getDocumentPermissionList')
            ->with($id)
            ->willReturn($expectedOutput);

        $response = $this->controller->edit($id);
        $responseData = $response->getData(true);

        $this->assertEquals($expectedOutput, $responseData);
    }

    // You can add more tests for other methods similarly.
}
