<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;


Route::post('/login', [AuthController::class, 'login'])
    ->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('api.logout');

    Route::get('/user', [UserController::class, 'index'])
        ->name('api.getUser');

    Route::get('/user/{user}', [UserController::class, 'show'])
        ->name('api.getUserByIdentifier');

    Route::patch('/user/{user}', [UserController::class, 'update'])
        ->name('api.updateUser');

    Route::delete('/user/{user}', [UserController::class, 'destroy'])
        ->name('api.deleteUser');
    });

Route::post('/user', [UserController::class, 'createUser'])
    ->name('api.createUser');

Route::post('/user/admin', [UserController::class, 'createAdminUser'])
    ->name('api.createAdminUser');
