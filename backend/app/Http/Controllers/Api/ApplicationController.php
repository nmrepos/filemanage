<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    //
    public function index()
    {
        $app = Application::first();
        return response()->json($app);
    }
}
