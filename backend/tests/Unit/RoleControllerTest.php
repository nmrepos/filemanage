<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\RoleController;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Models\Roles;
use Mockery;

class RoleControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of the RoleRepositoryInterface.
        $this->repository = $this->createMock(RoleRepositoryInterface::class);
        // Instantiate the controller with the repository mock.
        $this->controller = new RoleController($this->repository);
    }

    public function testIndexReturnsJsonResponse()
    {
        $expected = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'User'],
        ];

        $this->repository->expects($this->once())
            ->method('all')
            ->willReturn($expected);

        $response = $this->controller->index();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expected, $response->getData(true));
    }

    public function testDropdownReturnsJsonResponse()
    {
        $expected = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'User'],
        ];

        $this->repository->expects($this->once())
            ->method('getRolesForDropdown')
            ->willReturn($expected);

        $response = $this->controller->dropdown();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expected, $response->getData(true));
    }

    public function testCreateFailsValidationWhenNameIsMissing()
    {
        // Create a request without a name field.
        $request = Request::create('/roles', 'POST', []);
        $response = $this->controller->create($request);

        // Expect a JSON response with status 409.
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(409, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertArrayHasKey('name', $data);
    }

    public function testCreateSucceeds()
    {
        $requestData = ['name' => 'NewRole', 'otherField' => 'value'];
        $request = Request::create('/roles', 'POST', $requestData);
        $expected = ['id' => 1, 'name' => 'NewRole', 'otherField' => 'value'];

        $this->repository->expects($this->once())
            ->method('createRole')
            ->with($requestData)
            ->willReturn($expected);

        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($expected), $response->getContent());
    }

    public function testEditReturnsJsonResponse()
    {
        $id = 1;
        $expected = ['id' => $id, 'name' => 'TestRole'];

        $this->repository->expects($this->once())
            ->method('findRole')
            ->with($id)
            ->willReturn($expected);

        $response = $this->controller->edit($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expected, $response->getData(true));
    }

    public function testDestroyReturnsNoContentResponse()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(true);

        $response = $this->controller->destroy($id);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
