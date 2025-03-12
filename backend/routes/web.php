<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApplicationController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/api/application', [ApplicationController::class, 'index']);