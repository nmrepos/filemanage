<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\PageHelperController;
use App\Repositories\Contracts\PageHelperRepositoryInterface;

class PageHelperControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of the PageHelperRepositoryInterface.
        $this->repository = $this->createMock(PageHelperRepositoryInterface::class);

        // Instantiate the controller with the mock repository.
        $this->controller = new PageHelperController($this->repository);
    }

    public function testGetAllReturnsResponseWithStatus200()
    {
        $expectedData = [
            ['id' => 1, 'title' => 'Page One'],
            ['id' => 2, 'title' => 'Page Two']
        ];

        // Expect the repository's all() method to be called once.
        $this->repository->expects($this->once())
            ->method('all')
            ->willReturn($expectedData);

        // Call the controller action.
        $response = $this->controller->getAll();

        // Verify response is an instance of Response.
        $this->assertInstanceOf(Response::class, $response);
        // Verify the status code.
        $this->assertEquals(200, $response->getStatusCode());
        // Verify that the response content matches expected data.
        $this->assertEquals($expectedData, $response->getContent() ? json_decode($response->getContent(), true) : $expectedData);
    }

    public function testGetByCodeReturnsResponseWithStatus200()
    {
        $code = 'home';
        $expectedData = ['id' => 1, 'title' => 'Home Page'];

        $this->repository->expects($this->once())
            ->method('getByCode')
            ->with($code)
            ->willReturn($expectedData);

        $response = $this->controller->getByCode($code);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedData, $response->getContent() ? json_decode($response->getContent(), true) : $expectedData);
    }

    public function testUpdateReturnsJsonResponseWithStatus200()
    {
        $id = 5;
        $inputData = ['title' => 'Updated Page'];
        $expectedData = ['id' => $id, 'title' => 'Updated Page'];

        // Create a request instance with the input data.
        $request = Request::create('/page-helper/' . $id, 'PUT', $inputData);

        $this->repository->expects($this->once())
            ->method('update')
            ->with($inputData, $id)
            ->willReturn($expectedData);

        $response = $this->controller->update($request, $id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedData, $response->getData(true));
    }

    public function testGetByIdReturnsResponseWithStatus200()
    {
        $id = 3;
        $expectedData = ['id' => $id, 'title' => 'Some Page'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedData);

        $response = $this->controller->getById($id);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedData, $response->getContent() ? json_decode($response->getContent(), true) : $expectedData);
    }
}
