<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Healthcheck
 *
 * Check that the service is up. If everything is okay, you'll get a 200 OK response.
 *
 * Otherwise, the request will fail with a 400 error, and a response listing the failed services.
 *
 * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
 * @responseField status The status of this API (`up` or `down`).
 * @responseField services Map of each downstream service and their status (`up` or `down`).
 */
Route::get('/user', [UserController::class, 'me'])->middleware('auth:api');
Route::get('/customers', [CustomerController::class, 'index'])->middleware('auth:api');
Route::post('/customers', [CustomerController::class, 'store'])->middleware('auth:api');
Route::get('/customers/{customer}', [CustomerController::class, 'get'])->middleware('auth:api');
Route::put('/customers/{customer}', [CustomerController::class, 'update'])->middleware('auth:api');
Route::delete('/customers/{customer}', [CustomerController::class, 'delete'])->middleware('auth:api');
