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
    });

    Route::controller(\App\Http\Controllers\EmployeeController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::get('/employees/{id}', 'show')->name('employee_show');
        Route::delete('/employees/{id}', 'destroy')->name('employee_destroy');
        Route::patch('/employees/{id}', 'update')->name('employee_update');
        Route::put('/employees/{id}', 'replace')->name('employee_replace');
    });
    
    Route::controller(\App\Http\Controllers\DisheController::class)->prefix('/')->group(function() {
        Route::get('/dishes/{id}', 'show')->name('dishe_show');
    });
    Route::controller(\App\Http\Controllers\DisheController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::delete('/dishes/{id}', 'destroy')->name('dishe_destroy');
        Route::patch('/dishes/{id}', 'update')->name('dishe_update');
        Route::put('/dishes/{id}', 'replace')->name('dishe_replace');
        Route::patch('/dishes/{id}/active', 'active')->name('dishe_active');
        Route::patch('/dishes/{id}/inactive', 'inactive')->name('dishe_inactive');
    });

    Route::controller(\App\Http\Controllers\CategoryController::class)->prefix('/')->group(function() {
        Route::get('/categories/{id}', 'show')->name('categorie_show');
    });
    Route::controller(\App\Http\Controllers\CategoryController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::delete('/categories/{id}', 'destroy')->name('categorie_destroy');
        Route::patch('/categories/{id}', 'update')->name('categorie_update');
        Route::put('/categories/{id}', 'replace')->name('categorie_replace');
        Route::patch('/categories/{id}/active', 'active')->name('categorie_active');
        Route::patch('/categories/{id}/inactive', 'inactive')->name('categorie_inactive');
    });

    Route::controller(\App\Http\Controllers\ModifierController::class)->prefix('/')->group(function() {
        Route::get('/modifiers/{id}', 'show')->name('modifier_show');
    });
    Route::controller(\App\Http\Controllers\ModifierController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::delete('/modifiers/{id}', 'destroy')->name('modifier_destroy');
        Route::patch('/modifiers/{id}', 'update')->name('modifier_update');
        Route::put('/modifiers/{id}', 'replace')->name('modifier_replace');
    });

    Route::controller(\App\Http\Controllers\TableController::class)->prefix('/')->group(function() {
        Route::get('/tables/{id}', 'show')->name('table_show');
    });
    Route::controller(\App\Http\Controllers\TableController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::delete('/tables/{id}', 'destroy')->name('table_destroy');
        Route::patch('/tables/{id}', 'update')->name('table_update');
        Route::put('/tables/{id}', 'replace')->name('table_replace');
    });

    Route::controller(\App\Http\Controllers\InviteController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::get('/invites/{id}', 'show')->name('invite_show');
        Route::delete('/invites/{id}', 'destroy')->name('invite_destroy');
    });

    Route::controller(\App\Http\Controllers\OrderController::class)->prefix('/')->group(function() {
        Route::get('/orders/{id}', 'show')->name('order_show');
        Route::delete('/orders/{id}', 'destroy')->name('order_destroy');
        Route::patch('/orders/{id}', 'update')->name('order_update');
        Route::put('/orders/{id}', 'replace')->name('order_replace');
    });
    Route::controller(\App\Http\Controllers\OrderController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::patch('/orders/{id}/assignee', 'assignee')->name('order_assignee');
        Route::patch('/orders/{id}/assignee/{user_id}', 'assigneeTo')->name('order_assignee_to');
    });

    Route::controller(\App\Http\Controllers\MenuController::class)->prefix('/')->group(function() {
        Route::get('/menu/{id}', 'show')->name('menu_show');
        Route::get('/menu/{id}/dishes', 'getDishe')->name('menu_get_dishe');
        Route::get('/menu/{id}/categories', 'getCategory')->name('menu_get_category');
        Route::get('/menu/{id}/modifiers', 'getModifier')->name('menu_get_modifier');  
    });
    Route::controller(\App\Http\Controllers\MenuController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::delete('/menu/{id}', 'destroy')->name('menu_destroy');
        Route::patch('/menu/{id}', 'update')->name('menu_update');
        Route::put('/menu/{id}', 'replace')->name('menu_replace');
        Route::post('/menu/{id}/dish', 'createDishe')->name('menu_create_dishe');
        Route::post('/menu/{id}/category', 'createCategory')->name('menu_create_category');
        Route::post('/menu/{id}/modifier', 'createModifier')->name('menu_create_modifier');
    });
    

    Route::controller(\App\Http\Controllers\ProjectController::class)->prefix('/')->group(function() {
        Route::get('/projects/{id}/menu', 'getMenu')->name('project_get_menu');
        Route::get('/projects/{id}/orders', 'getOrder')->name('project_get_order');
        Route::post('/projects/{id}/order', 'createOrder')->name('project_create_order');
        Route::get('/projects/{id}/tables', 'getTable')->name('project_get_table');
    });
    Route::controller(\App\Http\Controllers\ProjectController::class)->middleware('admin_protect')->prefix('/')->group(function() {
        Route::post('/projects/{id}/menu', 'createMenu')->name('project_create_menu');
        Route::get('/projects/{id}/employees', 'getUser')->name('project_get_user');
        Route::post('/projects/{id}/table', 'createTable')->name('project_create_table');
        Route::get('/projects/{id}/invites', 'getInvite')->name('project_get_invite');
        Route::post('/projects/{id}/invites', 'createInvite')->name('project_create_invite');
    });
    Route::controller(\App\Http\Controllers\ProjectController::class)->middleware('owner_protect')->prefix('/')->group(function() {
        Route::get('/projects/{id}', 'show')->name('project_show');
        Route::delete('/projects/{id}', 'destroy')->name('project_destroy');
        Route::patch('/projects/{id}', 'update')->name('project_update');
        Route::put('/projects/{id}', 'replace')->name('project_replace');
        Route::post('/projects', 'create')->name('project_create');
        Route::get('/projects', 'get')->name('project_get');
    });


/*
Заведения: name, alias, logo, active (alias здесь должен быть уникальным)
Меню: name, active, order
Блюда: category_id, name, description, price, photo, active, order
Категории:  name, active, order
Модификаторы: menu_id, price, name, active, order
Столы:  name, order, active
Пользователи: first_name, last_name, avatar
Заказы: table_id, user_id, name, status, sum
Инвайты: для инвайтов не нужны методы PUT/PATCH (пусть удалят и создают новый)
*/

});

Route::post('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    //return redirect('/home');
    return response()->json([
        'success' => 'true',
        'data' => [],
    ]);
})->middleware('auth:api')->name('verification.verify');



Route::any('{any}', function(){
    return response()->json([
        'status'    => false,
        'message'   => 'Page Not Found.',
    ], 404);
})->where('any', '.*');