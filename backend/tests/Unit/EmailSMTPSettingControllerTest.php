<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\EmailSMTPSettingController;
use App\Repositories\Contracts\EmailSMTPSettingRepositoryInterface;

class EmailSMTPSettingControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of the EmailSMTPSettingRepositoryInterface.
        $this->repository = $this->createMock(EmailSMTPSettingRepositoryInterface::class);
        // Instantiate the controller with the mock repository.
        $this->controller = new EmailSMTPSettingController($this->repository);
    }

    public function testIndexReturnsJsonResponse()
    {
        $fakeData = [
            ['id' => 1, 'userName' => 'test@example.com'],
            ['id' => 2, 'userName' => 'smtp@domain.com']
        ];
        
        $this->repository->expects($this->once())
            ->method('all')
            ->willReturn($fakeData);

        $response = $this->controller->index();

        // Assert that the response is a JSON response with the expected data.
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($fakeData, $response->getData(true));
    }

    public function testCreateReturnsValidationErrors()
    {
        // Test validation failure by providing an empty request (missing userName).
        $request = Request::create('/email-smtp', 'POST', []);
        
        $response = $this->controller->create($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(409, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertArrayHasKey('userName', $data);
    }


    public function testEditReturnsJsonResponse()
    {
        $id = 1;
        $expectedData = ['id' => $id, 'userName' => 'edit@example.com'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedData);

        $response = $this->controller->edit($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedData, $response->getData(true));
    }

    public function testUpdateReturnsValidationErrors()
    {
        // Attempt update with missing required field.
        $id = 1;
        $request = Request::create("/email-smtp/$id", 'PUT', []);
        
        $response = $this->controller->update($request, $id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(409, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertArrayHasKey('userName', $data);
    }

    public function testDestroyReturns204Response()
    {
        $id = 1;
        $repositoryReturn = true;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn($repositoryReturn);

        $response = $this->controller->destroy($id);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
