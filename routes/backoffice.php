<?php

use AppBackoffice\Http\Controllers\Auth\RefreshController;
use AppBackoffice\Http\Controllers\Auth\SigninController;
use AppBackoffice\Http\Controllers\Auth\SignoutController;
use AppBackoffice\Http\Controllers\UserController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('signin', SigninController::class)
        ->name('backoffice.api.auth.signin');

    Route::post('signout', SignoutController::class)
        ->name('backoffice.api.auth.signout')
        ->middleware('auth:jwt');

    Route::post('refresh', RefreshController::class)
        ->name('backoffice.api.auth.refresh')
        ->middleware('auth:jwt');

});

Route::group(['middleware' => 'auth:jwt'], static function () {
    Route::group(['prefix' => 'users'], static function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('backoffice.api.users.index');

    });

});
