<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// New routes for login and registration
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
