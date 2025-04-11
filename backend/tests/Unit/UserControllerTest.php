<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\UserController;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mockery;

class UserControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a mock of the UserRepositoryInterface.
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        // Instantiate the controller with the repository mock.
        $this->controller = new UserController($this->repository);
    }

    public function testIndexReturnsJsonResponse()
    {
        $expected = [
            ['id' => 1, 'firstName' => 'John'],
            ['id' => 2, 'firstName' => 'Jane']
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
            ['id' => 1, 'firstName' => 'John']
        ];
        $this->repository->expects($this->once())
             ->method('getUsersForDropdown')
             ->willReturn($expected);

        $response = $this->controller->dropdown();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expected, $response->getData(true));
    }

    public function testCreateFailsValidation()
    {
        // Missing 'email' and 'firstName' should trigger validation errors.
        $request = Request::create('/users', 'POST', []);
        $response = $this->controller->create($request);
        
        // Expect a JSON response with status 409.
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(409, $response->getStatusCode());
        
        // Validation errors are returned (at least for 'email' and/or 'firstName').
        $data = $response->getData(true);
        $this->assertTrue(isset($data['email']) || isset($data['firstName']));
    }

    public function testEditReturnsJsonResponse()
    {
        $id = 1;
        $expected = ['id' => $id, 'firstName' => 'Test'];
        $this->repository->expects($this->once())
             ->method('findUser')
             ->with($id)
             ->willReturn($expected);
        
        $response = $this->controller->edit($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expected, $response->getData(true));
    }

    public function testUpdateCallsRepository()
    {
        $id = 1;
        $requestData = [
            'firstName' => 'Updated',
            'lastName' => 'User',
            'phoneNumber' => '12345',
            'userName' => 'updateduser',
            'email' => 'update@example.com',
            'roleIds' => [1, 2]
        ];
        $request = Request::create("/users/{$id}", 'PUT', $requestData);

        // Simulate repository find() call.
        $model = (object)[
            'id' => $id,
            'firstName' => 'Old',
            'lastName' => 'User'
        ];
        $this->repository->expects($this->once())
             ->method('find')
             ->with($id)
             ->willReturn($model);
        $updatedResult = ['id' => $id, 'firstName' => 'Updated'];
        $this->repository->expects($this->once())
             ->method('updateUser')
             ->with($model, $id, $requestData['roleIds'])
             ->willReturn($updatedResult);

        $response = $this->controller->update($request, $id);
        $this->assertEquals($updatedResult, $response);
    }

    public function testUpdateUserProfileCallsRepository()
    {
        $requestData = ['firstName' => 'UpdatedProfile'];
        $request = Request::create('/users/update-profile', 'PUT', $requestData);
        $this->repository->expects($this->once())
             ->method('updateUserProfile')
             ->with($request)
             ->willReturn($requestData);
        $response = $this->controller->updateUserProfile($request);
        $this->assertEquals($requestData, $response);
    }

    public function testChangePasswordFailsWhenOldPasswordDoesNotMatch()
    {
        $oldPassword = 'wrong';
        $newPassword = 'newsecret';
        $requestData = ['oldPassword' => $oldPassword, 'newPassword' => $newPassword];
        $request = Request::create('/users/change-password', 'POST', $requestData);
        
        // Set up Auth::user() to return a dummy user with a known hashed password.
        $dummyUser = new class {
            public $password;
        };
        $dummyUser->password = Hash::make('correct');
        
        Auth::shouldReceive('user')
             ->once()
             ->andReturn($dummyUser);
        
        $response = $this->controller->changePassword($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertEquals('Old Password does not match!', $data['message']);
    }

    public function testForgotPasswordCallsRepository()
    {
        $requestData = ['email' => 'forgot@example.com'];
        $request = Request::create('/users/forgot-password', 'POST', $requestData);
        $expected = ['status' => 'ok'];
        $this->repository->expects($this->once())
             ->method('forgotPassword')
             ->with($request)
             ->willReturn($expected);
        $response = $this->controller->forgotpassword($request);
        $this->assertEquals($expected, $response);
    }

    public function testGetUserInfoForResetPasswordCallsRepository()
    {
        $id = 1;
        $expected = ['id' => $id, 'email' => 'info@example.com'];
        $this->repository->expects($this->once())
             ->method('getUserInfoForResetPassword')
             ->with($id)
             ->willReturn($expected);
        $response = $this->controller->getUserInfoForResetPassword($id);
        $this->assertEquals($expected, $response);
    }

    public function testResetPasswordCallsRepository()
    {
        $requestData = ['token' => 'abc', 'password' => 'newsecret'];
        $request = Request::create('/users/reset-password', 'POST', $requestData);
        $expected = ['status' => 'password reset'];
        $this->repository->expects($this->once())
             ->method('resetPassword')
             ->with($request)
             ->willReturn($expected);
        $response = $this->controller->resetPassword($request);
        $this->assertEquals($expected, $response);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
