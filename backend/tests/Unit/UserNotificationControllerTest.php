<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\UserNotificationController;
use App\Repositories\Contracts\UserNotificationRepositoryInterface;

class UserNotificationControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of the UserNotificationRepositoryInterface.
        $this->repository = $this->createMock(UserNotificationRepositoryInterface::class);
        // Instantiate the controller with the repository mock.
        $this->controller = new UserNotificationController($this->repository);
    }

    public function testIndexReturnsJsonResponse()
    {
        $expectedNotifications = [
            ['id' => 1, 'message' => 'Notification 1'],
            ['id' => 2, 'message' => 'Notification 2']
        ];

        // Expect the getTop10Notification method to be called once.
        $this->repository->expects($this->once())
            ->method('getTop10Notification')
            ->willReturn($expectedNotifications);

        $response = $this->controller->index();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedNotifications, $response->getData(true));
    }

    public function testGetNotificationsReturnsJsonResponseWithHeaders()
    {
        // Prepare query parameters.
        $queryArray = [
            'pageSize' => 10,
            'skip'     => 5,
            'filter'   => 'unread'
        ];

        // Convert to an object (as done in controller).
        $queryObject = (object)$queryArray;

        $expectedNotifications = [
            ['id' => 1, 'message' => 'Notification A'],
            ['id' => 2, 'message' => 'Notification B']
        ];
        $expectedCount = 20;

        // Expect getUserNotificaionCount() to be called with the query object.
        $this->repository->expects($this->once())
            ->method('getUserNotificaionCount')
            ->with($this->callback(function($q) use ($queryObject) {
                return $q->pageSize == $queryObject->pageSize &&
                       $q->skip == $queryObject->skip &&
                       $q->filter == $queryObject->filter;
            }))
            ->willReturn($expectedCount);

        // Expect getUserNotificaions() to be called with the query object.
        $this->repository->expects($this->once())
            ->method('getUserNotificaions')
            ->with($this->callback(function($q) use ($queryObject) {
                return $q->pageSize == $queryObject->pageSize &&
                       $q->skip == $queryObject->skip &&
                       $q->filter == $queryObject->filter;
            }))
            ->willReturn($expectedNotifications);

        $request = Request::create('/notifications', 'GET', $queryArray);

        $response = $this->controller->getNotifications($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedNotifications, $response->getData(true));
        // Check the headers.
        $this->assertEquals($expectedCount, $response->headers->get('totalCount'));
        $this->assertEquals($queryArray['pageSize'], $response->headers->get('pageSize'));
        $this->assertEquals($queryArray['skip'], $response->headers->get('skip'));
    }

    public function testMarkAsReadReturnsJsonResponseWithStatus200()
    {
        $requestData = ['id' => 1, 'read' => true];
        $expectedResult = ['status' => 'updated'];

        // Expect markAsRead to be called with the request.
        $this->repository->expects($this->once())
             ->method('markAsRead')
             ->with($this->callback(function($req) use ($requestData) {
                 return $req->all() == $requestData;
             }))
             ->willReturn($expectedResult);

        $request = Request::create('/notifications/mark-as-read', 'POST', $requestData);
        $response = $this->controller->markAsRead($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResult, $response->getData(true));
    }

    public function testMarkAllAsReadReturnsJsonResponseWithStatus200()
    {
        $expectedResult = ['status' => 'all marked as read'];

        // Expect markAllAsRead to be called.
        $this->repository->expects($this->once())
            ->method('markAllAsRead')
            ->willReturn($expectedResult);

        $response = $this->controller->markAllAsRead();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResult, $response->getData(true));
    }
}
