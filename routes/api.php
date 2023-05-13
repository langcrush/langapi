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
        Route::post('/register', 'register');
        Route::get('/confirm', 'confirm');
        Route::post('/recover', 'recover');
        Route::post('/isvalidrecover', 'isvalidrecover');
        Route::post('/setnew', 'setnew');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::delete('/deleteacc', [AuthController::class, 'deleteAcc']);

        Route::apiResource('users', UserController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('words', WordController::class);
        Route::apiResource('stats', StatController::class);
    });
});
