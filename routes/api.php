<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::namespace('Api\V1')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('io-register', [AuthController::class, 'register']);
        Route::post('io-login', [AuthController::class, 'login']);

        Route::middleware(['auth:api'])->group(function () {
            // Users
            Route::get('users', [AuthController::class, 'index']);
            Route::get('user', [AuthController::class, 'user']);
            Route::put('users/{user}', [AuthController::class, 'update']);
            Route::get('logout', [AuthController::class, 'logout']);
            Route::delete('users/{user}', [AuthController::class, 'destroy']);
            Route::get('users/{user}', [AuthController::class, 'show']);
        });
    });
});
