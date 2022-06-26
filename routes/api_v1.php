<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function(){

    Route::controller(\App\Http\Controllers\AuthController::class)->prefix('/auth')->group(function() {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
        Route::post('/token', 'token')->name('token');
        Route::post('/restore', 'restore')->name('restore');
        Route::post('/restore/account', 'restoreAccount')->name('restore_account');
    });

});

Route::middleware(['auth:api', 'verified_custom'])->group(function(){

    Route::controller(\App\Http\Controllers\AuthController::class)->prefix('/auth')->group(function() {
        Route::post('/logout', 'logout')->name('logout');
        Route::post('/user', 'user')->name('user');
    });

    Route::controller(\App\Http\Controllers\EmployeeController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::post('/employees', 'index')->name('employee_index');
        Route::post('/employee/add', 'create')->name('employee_create');
        Route::get('/employees/{id}', 'show')->name('employee_show');
        Route::delete('/employees/{id}', 'destroy')->name('employee_destroy');
        Route::patch('/employees/{id}', 'update')->name('employee_update');
        Route::put('/employees/{id}', 'replace')->name('employee_replace');
    });
    
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    //return redirect('/home');
    return response()->json([
        'success' => 'true',
        'data' => [],
    ]);
})->middleware('auth:api')->name('verification.verify');

