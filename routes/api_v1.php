<?php

use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('guest')->group(function(){
    Route::post('/auth/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
    Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/auth/token', [\App\Http\Controllers\AuthController::class, 'token'])->name('token');

    Route::post('/auth/restore', [\App\Http\Controllers\AuthController::class, 'restore'])->name('restore');
    Route::post('/auth/restore/account', [\App\Http\Controllers\AuthController::class, 'restoreAccount'])->name('restore_account');

});

Route::middleware(['auth:api', 'verified_custom'])->group(function(){
    
    Route::post('/auth/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::post('/auth/user', [\App\Http\Controllers\AuthController::class, 'user'])->name('user');

});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    //return redirect('/home');
    return response()->json([
        'success' => 'true',
        'data' => [],
    ]);
})->middleware('auth:api')->name('verification.verify');

/*
- /auth/register [POST] регистрация
- /auth/login [POST] вход в систему
- /auth/logout [POST] выход из системы
- /auth/token [POST] продление Access Token на 30 минут (через Refresh Token)
- /auth/restore [POST] отправка на email сообщения на восстановление пароля (позже
будет принимать email или phone)
- /auth/restore/account [POST] смена пароля (? - нужно дополнительно обсудить)
*/

