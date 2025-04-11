<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ActionsController;
use App\Repositories\Contracts\ActionsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class ActionsControllerTest extends TestCase
{
    /**
     * Test that index() returns the ordered actions with a 200 status.
     */
    public function testIndexReturnsOrderedActions()
    {
        // Create some fake actions data
        $actions = [
            ['id' => 1, 'order' => 1, 'name' => 'Action One'],
            ['id' => 2, 'order' => 2, 'name' => 'Action Two'],
        ];

        // Create a mock for the ActionsRepositoryInterface
        $repositoryMock = $this->createMock(ActionsRepositoryInterface::class);

        // Expect orderBy('order') to be called and return the same mock instance for chaining
        $repositoryMock->expects($this->once())
            ->method('orderBy')
            ->with('order')
            ->willReturnSelf();

        // Then expect the all() method to be called and return the fake data
        $repositoryMock->expects($this->once())
            ->method('all')
            ->willReturn($actions);

        // Instantiate the controller with the mocked repository
        $controller = new ActionsController($repositoryMock);

        // Call the index() method on the controller
        $response = $controller->index();

        // Assert the response status and content are as expected
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($actions), $response->getContent());
    }

    /**
     * Test that create() returns validation errors when required fields are missing.
     */
    public function testCreateFailsValidation()
    {
        // Create an empty request (missing required fields)
        $request = Request::create('/actions', 'POST', []);

        // The repository is not used if validation fails, so we can use a dummy mock
        $repositoryMock = $this->createMock(ActionsRepositoryInterface::class);

        $controller = new ActionsController($repositoryMock);

        // Call create() and capture the response (validation errors)
        $response = $controller->create($request);

        // The controller returns a MessageBag on validation failure.
        $this->assertInstanceOf(MessageBag::class, $response);
        $this->assertTrue($response->has('name'));
        $this->assertTrue($response->has('order'));
        $this->assertTrue($response->has('pageId'));
        $this->assertTrue($response->has('modifiedBy'));
    }

    /**
     * Test that create() returns a successful response when valid data is provided.
     */
    public function testCreateSucceeds()
    {
        // Valid data that passes the validation rules
        $postData = [
            'name' => 'New Action',
            'order' => 1,
            'pageId' => 10,
            'modifiedBy' => 'User123'
        ];
        $request = Request::create('/actions', 'POST', $postData);

        // Create a mock repository
        $repositoryMock = $this->createMock(ActionsRepositoryInterface::class);

        // Expect the createAction method to be called with the provided data
        // and return an array representing the created action (e.g., with an id)
        $expectedResult = array_merge($postData, ['id' => 123]);
        $repositoryMock->expects($this->once())
            ->method('createAction')
            ->with($postData)
            ->willReturn($expectedResult);

        $controller = new ActionsController($repositoryMock);

        // Call the create() method and capture the response
        $response = $controller->create($request);

        // Assert that the status code is 201 (created)
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $response->getContent());
    }

    /**
     * Test that update() successfully calls the repository and returns a 204 status.
     */
    public function testUpdateAction()
    {
        // Data for the update
        $updateData = [
            'name' => 'Updated Action',
            'order' => 2,
            'pageId' => 10,
            'modifiedBy' => 'User456'
        ];
        $id = 123;
        $request = Request::create("/actions/{$id}", 'PUT', $updateData);

        // Create a mock repository
        $repositoryMock = $this->createMock(ActionsRepositoryInterface::class);

        // Expect update() to be called with the update data and id, returning something (e.g., true)
        $repositoryMock->expects($this->once())
            ->method('update')
            ->with($updateData, $id)
            ->willReturn(true);

        $controller = new ActionsController($repositoryMock);

        // Call update() and capture the response
        $response = $controller->update($request, $id);

        // A 204 status is expected (no content)
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test that destroy() successfully calls the repository and returns a 204 status.
     */
    public function testDestroyAction()
    {
        $id = 123;

        // Create a mock repository
        $repositoryMock = $this->createMock(ActionsRepositoryInterface::class);

        // Expect delete() to be called with the given id and return something (e.g., true)
        $repositoryMock->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(true);

        $controller = new ActionsController($repositoryMock);

        // Call destroy() and capture the response
        $response = $controller->destroy($id);

        // Assert that the status code is 204
        $this->assertEquals(204, $response->getStatusCode());
    }
}
