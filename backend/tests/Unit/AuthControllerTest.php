<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\LoginAudit;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that login() returns a 401 error response when authentication fails.
     */
    public function testLoginFailure()
    {
        // Set a dummy remote IP for getIp().
        $_SERVER['REMOTE_ADDR'] = '192.0.2.1';

        // Prepare request data.
        $credentials = ['email' => 'test@example.com', 'password' => 'secret'];
        $request = Request::create('/login', 'POST', $credentials);
        $request->merge(['latitude' => '0', 'longitude' => '0']);

        // Stub Auth: initial authentication returns false.
        Auth::shouldReceive('claims')
            ->with([])
            ->once()
            ->andReturnSelf();
        Auth::shouldReceive('attempt')
            ->with($credentials)
            ->once()
            ->andReturn(false);

        // Let LoginAudit::create() run normally; DatabaseTransactions will roll back changes.

        // Use a dummy userRepository (not used on authentication failure).
        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $controller = new AuthController($userRepositoryMock);
        $response = $controller->login($request);
        $responseData = json_decode($response->getContent(), true);

        // Assert that a 401 response is returned.
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Unauthorized', $responseData['message']);
    }

    /**
     * Test that login() returns a success response when authentication succeeds.
     *
     * We stub Auth and DB calls so that no real DB operations occur.
     */
    public function testLoginSuccess()
    {
        $_SERVER['REMOTE_ADDR'] = '192.0.2.1';

        $credentials = ['email' => 'test@example.com', 'password' => 'secret'];
        $request = Request::create('/login', 'POST', $credentials);
        $request->merge(['latitude' => '0', 'longitude' => '0']);

        // First, stub Auth so that the initial authentication returns a token.
        Auth::shouldReceive('claims')
            ->with([])
            ->once()
            ->andReturnSelf();
        Auth::shouldReceive('attempt')
            ->with($credentials)
            ->once()
            ->andReturn('first_token');

        // Stub Auth::user() to return a dummy user.
        $dummyUser = (object)[
            'id'          => 1,
            'firstName'   => 'John',
            'lastName'    => 'Doe',
            'email'       => 'test@example.com',
            'userName'    => 'johndoe',
            'phoneNumber' => '1234567890'
        ];
        Auth::shouldReceive('user')
            ->once()
            ->andReturn($dummyUser);

        // Create a separate query builder mock for the first DB chain (userRoles).
        $userRolesQuery = \Mockery::mock();
        $userRolesQuery->shouldReceive('select')
            ->with('roleClaims.claimType')
            ->once()
            ->andReturnSelf();
        $userRolesQuery->shouldReceive('leftJoin')
            ->with('roles', 'roles.id', '=', 'userRoles.roleId')
            ->once()
            ->andReturnSelf();
        $userRolesQuery->shouldReceive('leftJoin')
            ->with('roleClaims', 'roleClaims.roleId', '=', 'roles.id')
            ->once()
            ->andReturnSelf();
        $userRolesQuery->shouldReceive('where')
            ->with('userRoles.userId', '=', $dummyUser->id)
            ->once()
            ->andReturnSelf();
        $userRolesQuery->shouldReceive('get')
            ->once()
            ->andReturn(collect([])); // Return an empty collection for role-based claims.

        // Create another query builder mock for the second DB chain (userClaims).
        $userClaimsQuery = \Mockery::mock();
        $userClaimsQuery->shouldReceive('select')
            ->with('claimType')
            ->once()
            ->andReturnSelf();
        $userClaimsQuery->shouldReceive('where')
            ->with('userClaims.userId', '=', $dummyUser->id)
            ->once()
            ->andReturnSelf();
        $userClaimsQuery->shouldReceive('get')
            ->once()
            ->andReturn(collect([])); // Return an empty collection for individual claims.

        // Stub the two distinct calls to DB::table() to return the query builder mocks.
        DB::shouldReceive('table')
            ->with('userRoles')
            ->once()
            ->andReturn($userRolesQuery);
        DB::shouldReceive('table')
            ->with('userClaims')
            ->once()
            ->andReturn($userClaimsQuery);

        // Now, stub the second authentication call (with claims) to return the final token.
        Auth::shouldReceive('claims')
            ->with(\Mockery::type('array'))
            ->once()
            ->andReturnSelf();
        Auth::shouldReceive('attempt')
            ->with($credentials)
            ->once()
            ->andReturn('final_token');

        // Let LoginAudit::create() run normally.
        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $controller = new AuthController($userRepositoryMock);
        $response = $controller->login($request);
        $responseData = json_decode($response->getContent(), true);

        // Verify that the final response contains a success status and final token.
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('final_token', $responseData['authorisation']['token']);
        $this->assertEquals('bearer', $responseData['authorisation']['type']);
    }
}
