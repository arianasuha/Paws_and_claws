<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Vet\VetController;


Route::post('/login', [AuthController::class, 'login'])
    ->name('api.login');

Route::post('/users', [UserController::class, 'createUser'])
    ->name('api.createUser');

Route::post('/admin/users', [UserController::class, 'createAdminUser'])
    ->name('api.createAdminUser');

Route::post('/vets', [VetController::class, 'createVet'])
            ->name('api.createVet');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('api.logout');

    Route::get('/users', [UserController::class, 'index'])
        ->name('api.getUser');

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('api.getUserByIdentifier');

    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->name('api.updateUser');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('api.deleteUser');

    Route::get('/pets', [PetController::class, 'index'])
        ->name('api.getPet');

    Route::get('/pets/{pet}', [PetController::class, 'show'])
    ->name('api.getPetByIdentifier');

    Route::post('/pets', [PetController::class, 'createPet'])
            ->name('api.createPet');

    Route::put('/pets/{pet}', [PetController::class, 'update'])
    ->name('api.updatePet');

    Route::delete('/pets/{pet}', [PetController::class, 'destroy'])
    ->name('api.deletePet');

    Route::get('/vets', [VetController::class, 'index'])
        ->name('api.getVet');

    Route::get('/vets/{vet}', [VetController::class, 'show'])
    ->name('api.getVetByIdentifier');

    Route::put('/vets/{vet}', [VetController::class, 'update'])
    ->name('api.updateVet');

    Route::delete('/vets/{vet}', [VetController::class, 'destroy'])
    ->name('api.deleteVet');
});
