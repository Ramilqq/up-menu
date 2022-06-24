<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;

/*
Route::get('/', function () {
    return view('welcome');
});
*/

/*
Route::get('/notification', function () {
    $invoice = User::find(1);

    return (new ResetPasswordNotification($invoice))
                ->toMail($invoice->user);
});
*/