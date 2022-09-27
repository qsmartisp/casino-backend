<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ReSigninController;
use App\Http\Controllers\Auth\SigninController;
use App\Http\Controllers\Auth\SignoutController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Deposit\CoinbaseController;
use App\Http\Controllers\Deposit\EstchangeController as DepositEstchangeController;
use App\Http\Controllers\Deposit\Simplepay\QrCodeController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameHistoryController;
use App\Http\Controllers\GameLogController;
use App\Http\Controllers\InitialController;
use App\Http\Controllers\IpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VipController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Withdrawal\EstchangeController as WithdrawalEstchangeController;
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

Route::group(['prefix' => 'auth'], static function () {
    Route::post('signin', SigninController::class)
        ->name('api.auth.signin');
    Route::post('signup', SignupController::class)
        ->name('api.auth.signup');
    Route::post('signout', SignoutController::class)
        ->name('api.auth.signout')
        ->middleware('auth:sanctum');
    Route::post('resignin', ReSigninController::class)
        ->name('api.auth.resignin');
    Route::post('forgot-password', ForgotPasswordController::class)
        ->name('password.email')
        ->middleware('guest');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.reset')
        ->middleware('guest');
    Route::post('update-password', [ResetPasswordController::class, 'update'])
        ->name('password.update')
        ->middleware('auth:sanctum');
});

Route::get('init', InitialController::class)
    ->name('api.init');

Route::group(['prefix' => 'vip'], static function () {
    Route::get('/', [VipController::class, 'index'])
        ->name('api.vip.index');
});

Route::group(['prefix' => 'ip'], static function () {
    Route::get('/', IpController::class)
        ->name('api.ip.getCountry');
});

Route::group(['middleware' => 'auth:sanctum'], static function () {
    Route::get('profile', [ProfileController::class, 'show'])
        ->name('api.profile.show');
    Route::put('profile', [ProfileController::class, 'update'])
        ->name('api.profile.update');
    Route::post('profile/self-exclusion', [ProfileController::class, 'sendSelfExclusion'])
        ->name('api.profile.sendSelfExclusion');
    Route::post('profile/verification-request', [ProfileController::class, 'sendVerificationRequest'])
        ->name('api.profile.sendVerificationRequest');

    Route::get('profile/files', [FileController::class, 'index'])
        ->name('api.profile.files.index');
    Route::post('profile/files', [FileController::class, 'store'])
        ->name('api.profile.files.store');

    Route::group(['prefix' => 'sessions'], static function () {
        Route::get('/', [SessionController::class, 'list'])
            ->name('api.sessions.list');
        Route::delete('/', [SessionController::class, 'dropAll'])
            ->name('api.sessions.dropAll');
    });

    Route::group(['prefix' => 'session'], static function () {
        Route::delete('/', [SessionController::class, 'drop'])
            ->name('api.session.drop');
        Route::delete('{id}', [SessionController::class, 'dropById'])
            ->whereNumber('id')
            ->name('api.session.dropById');
    });

    Route::group(['prefix' => 'game'], static function () {
        Route::get('{slug}', [GameController::class, 'run'])
            ->name('api.game.run');
    });

    Route::group(['prefix' => 'games'], static function () {
        Route::get('history', [GameHistoryController::class, 'index'])
            ->name('api.games.history');
    });

    Route::group(['prefix' => 'games'], static function () {
        Route::get('logs', [GameLogController::class, 'index'])
            ->name('api.games.logs');
    });

    Route::group(['prefix' => 'wallets'], static function () {
        Route::get('/', [WalletController::class, 'index'])
            ->name('api.wallets.index');
    });

    Route::group(['prefix' => 'wallet'], static function () {
        Route::post('/', [WalletController::class, 'store'])
            ->name('api.wallet.store');
    });

    Route::group(['prefix' => 'wallet'], static function () {
        Route::post('/default', [WalletController::class, 'setDefault'])
            ->name('api.wallet.setDefault');
    });

    Route::group(['prefix' => 'deposit'], static function () {
        Route::post('coinbase', [CoinbaseController::class, 'start'])
            ->name('api.deposit.coinbase.start');
        Route::post('estchange', [DepositEstchangeController::class, 'create'])
            ->name('api.deposit.estchange.create');
        Route::group(['prefix' => 'simplepay'], static function () {
            Route::post('qrcode', [QrCodeController::class, 'create'])
                ->name('api.deposit.simplepay.qrcode.create');
        });
    });

    Route::group(['prefix' => 'withdrawal'], static function () {
        Route::post('estchange', [WithdrawalEstchangeController::class, 'store'])
            ->name('api.withdrawal.estchange.store');

        Route::get('estchange/rate/{code}', [WithdrawalEstchangeController::class, 'getRates'])
            ->name('api.withdrawal.estchange.rates');
    });

    Route::group(['prefix' => 'payments'], static function () {
        Route::get('/', [TransactionController::class, 'index'])
            ->name('api.payments.index');
    });
});

Route::group(['prefix' => 'games'], static function () {
    Route::get('/', [GameController::class, 'index'])
        ->name('api.games.index');
});

Route::group(['prefix' => 'game-demo'], static function () {
    Route::get('{slug}', [GameController::class, 'runDemo'])
        ->name('api.game.runDemo');
});

Route::group(['prefix' => 'providers'], static function () {
    Route::get('/', [ProviderController::class, 'list'])
        ->name('api.providers.list');
});

Route::group(['prefix' => 'feedback'], static function () {
    Route::post('/', [FeedbackController::class, 'store'])
        ->name('api.feedback.store');
});
