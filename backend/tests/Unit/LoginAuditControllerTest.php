<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\LoginAuditController;
use App\Repositories\Contracts\LoginAuditRepositoryInterface;

class LoginAuditControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a mock of the LoginAuditRepositoryInterface.
        $this->repository = $this->createMock(LoginAuditRepositoryInterface::class);
        // Inject the mock repository into the controller.
        $this->controller = new LoginAuditController($this->repository);
    }

    public function testGetLoginAuditReturnsExpectedResponseWithHeaders()
    {
        // Define the query parameters passed in the request.
        $queryArray = [
            'pageSize' => 10,
            'skip'     => 20,
            'filter'   => 'test'
        ];

        // Convert the array to an object as done by the controller.
        $queryObject = (object)$queryArray;

        // Expected data from the repository.
        $expectedAudits = [
            ['id' => 1, 'action' => 'login'],
            ['id' => 2, 'action' => 'logout']
        ];
        $expectedCount = 100;

        // Set expectations on the repository.
        $this->repository->expects($this->once())
            ->method('getLoginAuditsCount')
            ->with($this->callback(function($passedQuery) use ($queryObject) {
                return $passedQuery->pageSize == $queryObject->pageSize &&
                       $passedQuery->skip == $queryObject->skip &&
                       $passedQuery->filter == $queryObject->filter;
            }))
            ->willReturn($expectedCount);

        $this->repository->expects($this->once())
            ->method('getLoginAudits')
            ->with($this->callback(function($passedQuery) use ($queryObject) {
                return $passedQuery->pageSize == $queryObject->pageSize &&
                       $passedQuery->skip == $queryObject->skip &&
                       $passedQuery->filter == $queryObject->filter;
            }))
            ->willReturn($expectedAudits);

        // Create a Request with the query parameters.
        $request = Request::create('/login-audit', 'GET', $queryArray);

        // Call the controller method.
        $response = $this->controller->getLoginAudit($request);

        // Verify that the response is a JSON response.
        $this->assertInstanceOf(JsonResponse::class, $response);
        // Assert that the JSON data in the response matches the expected audits.
        $this->assertEquals($expectedAudits, $response->getData(true));

        // Verify that the headers are set correctly.
        $this->assertEquals($expectedCount, $response->headers->get('totalCount'));
        $this->assertEquals($queryArray['pageSize'], $response->headers->get('pageSize'));
        $this->assertEquals($queryArray['skip'], $response->headers->get('skip'));
    }
}
