<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user', [UserController::class, 'me'])->middleware('auth');
Route::post('/set-access-token', [UserController::class, 'saveAccessToken'])->middleware('auth');
Route::get('/customers', CustomerController::class)->middleware('auth')->name('customers');
