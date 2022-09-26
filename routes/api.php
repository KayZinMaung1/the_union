<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DistrictController;
use App\Http\Controllers\Api\V1\StateController;
use App\Http\Controllers\Api\V1\TownshipController;
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

            // States
            Route::get('states', [StateController::class, 'index']);
            Route::post('states', [StateController::class, 'store']);
            Route::get('states/{state}', [StateController::class, 'show']);
            Route::put('states/{state}', [StateController::class, 'update']);
            Route::delete('states/{state}', [StateController::class, 'destroy']);

            // Districts
            Route::get('districts', [DistrictController::class, 'index']);
            Route::post('districts', [DistrictController::class, 'store']);
            Route::get('districts/{district}', [DistrictController::class, 'show']);
            Route::put('districts/{district}', [DistrictController::class, 'update']);
            Route::delete('districts/{district}', [DistrictController::class, 'destroy']);

            // Townships
            Route::get('townships', [TownshipController::class, 'index']);
            Route::post('townships', [TownshipController::class, 'store']);
            Route::get('townships/{township}', [TownshipController::class, 'show']);
            Route::put('townships/{township}', [TownshipController::class, 'update']);
            Route::delete('townships/{township}', [TownshipController::class, 'destroy']);
        });
    });
});
