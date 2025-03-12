<?php

namespace App\Http\Controllers;

use App\Models\LoginAudit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        // Using JWT authentication; returns a token if successful
        $token = Auth::guard('api')->claims([])->attempt($credentials);

        $remoteIP = $request->ip();
        // Log the login attempt in the audit table
        LoginAudit::create(
            [
            'userName'  => $request->input('email'),
            'loginTime' => Carbon::now(),
            'remoteIP'  => $remoteIP,
            'status'    => $token ? 'Success' : 'Error',
            'latitude'  => $request->input('latitude'),
            'longitude' => $request->input('longitude')
            ]
        );
        
        if (!$token) {
            return response()->json(
                [
                'status'  => 'error',
                'message' => 'Unauthorized'
                ], 401
            );
        }
        
        return response()->json(
            [
            'status' => 'success',
            'token'  => $token,
            'user'   => Auth::user()
            ]
        );
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(
            [
            'status'  => 'success',
            'message' => 'Logged out successfully'
            ]
        );
    }
}
