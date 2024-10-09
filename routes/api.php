<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\NewsArticleController;
use App\Http\Controllers\UserPreferenceController;

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::put('/user/preferences', [UserPreferenceController::class, 'update']);
    Route::get('/authors', [NewsArticleController::class, 'getAuthors']);
});

Route::get('/articles', [NewsArticleController::class, 'index']);
Route::get('/sources', [NewsArticleController::class, 'getSources']);
