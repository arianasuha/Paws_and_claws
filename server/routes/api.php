<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Vet\VetController;


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

    Route::get('/pet', [PetController::class, 'index'])
        ->name('api.getPet');

    Route::get('/pet/{pet}', [PetController::class, 'show'])
    ->name('api.getPetByIdentifier');

    Route::patch('/pet/{pet}', [PetController::class, 'update'])
    ->name('api.updatePet');

    Route::delete('/pet/{pet}', [PetController::class, 'destroy'])
    ->name('api.deletePet');

    Route::get('/vet', [PetController::class, 'index'])
        ->name('api.getPet');

    Route::get('/vet/{vet}', [PetController::class, 'show'])
    ->name('api.getVetByIdentifier');

    Route::patch('/vet/{vet}', [PetController::class, 'update'])
    ->name('api.updateVet');

    Route::delete('/vet/{vet}', [PetController::class, 'destroy'])
    ->name('api.deleteVet');
        });


Route::post('/user', [UserController::class, 'createUser'])
    ->name('api.createUser');

Route::post('/user/admin', [UserController::class, 'createAdminUser'])
    ->name('api.createAdminUser');

Route::post('/createPet', [PetController::class, 'createPet'])
            ->name('api.createPet');

Route::post('/createVet', [VetController::class, 'createVet'])
            ->name('api.createVet');
