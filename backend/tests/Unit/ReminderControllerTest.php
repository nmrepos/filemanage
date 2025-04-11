<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\ReminderController;
use App\Repositories\Contracts\ReminderRepositoryInterface;

class ReminderControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of the ReminderRepositoryInterface.
        $this->repository = $this->createMock(ReminderRepositoryInterface::class);

        // Instantiate the controller with the mock repository.
        $this->controller = new ReminderController($this->repository);
    }

    public function testGetRemindersReturnsJsonResponseWithHeaders()
    {
        $queryArray = [
            'pageSize' => 10,
            'skip'     => 5,
            'filter'   => 'active'
        ];
        $queryObject = (object) $queryArray;

        $expectedReminders = [
            ['id' => 1, 'message' => 'Reminder 1'],
            ['id' => 2, 'message' => 'Reminder 2'],
        ];
        $expectedCount = 50;

        // Expect getRemindersCount to be called with the query object.
        $this->repository->expects($this->once())
            ->method('getRemindersCount')
            ->with($this->callback(function($q) use ($queryObject) {
                return $q->pageSize == $queryObject->pageSize &&
                    $q->skip == $queryObject->skip &&
                    $q->filter == $queryObject->filter;
            }))
            ->willReturn($expectedCount);

        // Expect getReminders to be called similarly.
        $this->repository->expects($this->once())
            ->method('getReminders')
            ->with($this->callback(function($q) use ($queryObject) {
                return $q->pageSize == $queryObject->pageSize &&
                    $q->skip == $queryObject->skip &&
                    $q->filter == $queryObject->filter;
            }))
            ->willReturn($expectedReminders);

        $request = Request::create('/reminders', 'GET', $queryArray);

        $response = $this->controller->getReminders($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedReminders, $response->getData(true));

        // Verify headers are set.
        $this->assertEquals($expectedCount, $response->headers->get('totalCount'));
        $this->assertEquals($queryArray['pageSize'], $response->headers->get('pageSize'));
        $this->assertEquals($queryArray['skip'], $response->headers->get('skip'));
    }

    public function testGetReminderForLoginUserReturnsJsonResponseWithHeaders()
    {
        $queryArray = [
            'pageSize' => 5,
            'skip'     => 0,
            'user'     => 'testUser'
        ];
        $queryObject = (object) $queryArray;

        $expectedReminders = [
            ['id' => 10, 'message' => 'User Reminder']
        ];
        $expectedCount = 1;

        $this->repository->expects($this->once())
            ->method('getReminderForLoginUserCount')
            ->with($this->callback(function($q) use ($queryObject) {
                return $q->pageSize == $queryObject->pageSize &&
                       $q->skip == $queryObject->skip &&
                       $q->user == $queryObject->user;
            }))
            ->willReturn($expectedCount);

        $this->repository->expects($this->once())
            ->method('getReminderForLoginUser')
            ->with($this->callback(function($q) use ($queryObject) {
                return $q->pageSize == $queryObject->pageSize &&
                       $q->skip == $queryObject->skip &&
                       $q->user == $queryObject->user;
            }))
            ->willReturn($expectedReminders);

        $request = Request::create('/reminders/login-user', 'GET', $queryArray);

        $response = $this->controller->getReminderForLoginUser($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedReminders, $response->getData(true));

        $this->assertEquals($expectedCount, $response->headers->get('totalCount'));
        $this->assertEquals($queryArray['pageSize'], $response->headers->get('pageSize'));
        $this->assertEquals($queryArray['skip'], $response->headers->get('skip'));
    }

    public function testAddReminderReturnsCreatedResponse()
    {
        $inputData = [
            'title' => 'New Reminder',
            'message' => 'This is a reminder.'
        ];

        $expectedRepositoryReturn = ['id' => 1, 'title' => 'New Reminder'];

        // Expect addReminders to be called with the request data.
        $this->repository->expects($this->once())
            ->method('addReminders')
            ->with($inputData)
            ->willReturn($expectedRepositoryReturn);

        $request = Request::create('/reminders', 'POST', $inputData);
        $response = $this->controller->addReminder($request);

        // Check that the response is a Response instance with 201 status code.
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedRepositoryReturn), $response->getContent());
    }

    public function testUpdateReminderReturnsCreatedResponse()
    {
        $id = 2;
        $inputData = [
            'title' => 'Updated Reminder',
            'message' => 'Updated message.'
        ];
        $expectedRepositoryReturn = ['id' => $id, 'title' => 'Updated Reminder'];

        $this->repository->expects($this->once())
            ->method('updateReminders')
            ->with($this->callback(function($req) use ($inputData) {
                return $req->all() == $inputData;
            }), $id)
            ->willReturn($expectedRepositoryReturn);

        $request = Request::create("/reminders/$id", 'PUT', $inputData);
        $response = $this->controller->updateReminder($request, $id);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedRepositoryReturn), $response->getContent());
    }

    public function testEditReturnsJsonResponse()
    {
        $id = 3;
        $expectedReminder = ['id' => $id, 'title' => 'Reminder Title'];

        $this->repository->expects($this->once())
            ->method('findReminder')
            ->with($id)
            ->willReturn($expectedReminder);

        $response = $this->controller->edit($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedReminder, $response->getData(true));
    }

    public function testDeleteReminderReturnsNoContentResponse()
    {
        $id = 4;
        $expectedRepositoryReturn = true;

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn($expectedRepositoryReturn);

        $response = $this->controller->deleteReminder($id);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteReminderCurrentUserReturnsResponse()
    {
        $id = 5;
        $expectedRepositoryReturn = ['deleted' => true];

        $this->repository->expects($this->once())
            ->method('deleteReminderCurrentUser')
            ->with($id)
            ->willReturn($expectedRepositoryReturn);

        $response = $this->controller->deleteReminderCurrentUser($id);
        // We assume the controller returns a plain Response with the repository's value.
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(json_encode($expectedRepositoryReturn), $response->getContent());
    }
}
