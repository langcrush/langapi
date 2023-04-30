<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\WordController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\StatController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', ['as' => 'login', 'uses' => 'login']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('words', WordController::class);
        Route::apiResource('stats', StatController::class);
    });
});
