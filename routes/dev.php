<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dev Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register dev web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "dev" middleware group. Now create something great!
|
*/

Route::get('logs/old', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
Route::get('/', static fn() => view('dev/welcome'));
