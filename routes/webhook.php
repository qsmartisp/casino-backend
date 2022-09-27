<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
|
| Here is where you can register webhook routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "API" middleware group. Enjoy building your webhooks!
|
*/

// todo: refactoring; add middlewares
// OneWallet aka Fundist
Route::any('ow', \App\Http\Controllers\Webhook\OneWalletController::class)
    ->name('api.webhook.ow');

// BGaming
Route::any('bg/{request_type}', \App\Http\Controllers\Webhook\BGamingController::class)
    ->name('api.webhook.bgaming')
    ->middleware([
        'webhook.request.dto.merge:bgaming',
        'webhook.logs.store:bgaming',
        'webhook.store.bgaming',
    ]);

// Coinbase
Route::any('cb', \App\Http\Controllers\Webhook\CoinbaseController::class)
    ->name('api.webhook.coinbase')
    ->middleware([
        'webhook.request.dto.merge:coinbase',
        'webhook.logs.store:coinbase',
        'webhook.store.coinbase',
    ]);

// Estchange
Route::any('ec', \App\Http\Controllers\Webhook\EstchangeController::class)
    ->name('api.webhook.estchange')
    ->middleware([
        'webhook.request.dto.merge:estchange',
        'webhook.logs.store:estchange',
        'webhook.store.estchange',
    ]);

// Simplepay
Route::any('sp', \App\Http\Controllers\Webhook\SimplepayController::class)
    ->name('api.webhook.simplepay')
    ->middleware([
        'webhook.request.dto.merge:simplepay',
        'webhook.logs.store:simplepay',
        'webhook.store.simplepay',
    ]);
