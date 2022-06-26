<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Models\User;

/*
Route::get('/notification/reset_pass', function () {
    $invoice = User::find(1);

    return (new \App\Notifications\ResetPasswordNotification($invoice))
                ->toMail($invoice->user);
});

Route::get('/notification/email_verif', function () {
    $invoice = User::find(1);

    return (new \App\Notifications\EmailVerificationNotification($invoice))
                ->toMail($invoice->user);
});

Route::get('/notification/reset_empl_create', function () {
    $invoice = User::find(1);

    return (new \App\Notifications\EmailEmployeeLoginNotification($invoice))
                ->toMail($invoice->user);
});
*/