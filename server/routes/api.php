<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Vet\VetController;
use App\Http\Controllers\Pet\PetProductController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItemsController;
use App\Http\Controllers\Order\CartController;


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

    Route::patch('/pets/{pet}', [PetController::class, 'update'])
        ->name('api.updatePet');

    Route::delete('/pets/{pet}', [PetController::class, 'destroy'])
        ->name('api.deletePet');

    Route::get('/vets', [VetController::class, 'index'])
        ->name('api.getVet');

    Route::get('/vets/{vet}', [VetController::class, 'show'])
        ->name('api.getVetByIdentifier');

    Route::patch('/vets/{vet}', [VetController::class, 'update'])
        ->name('api.updateVet');

    Route::delete('/vets/{vet}', [VetController::class, 'destroy'])
        ->name('api.deleteVet');

    Route::get('/pet-products', [PetProductController::class, 'index'])
        ->name('api.getPetProducts');

    Route::get('/pet-products/{petProduct}', [PetProductController::class, 'show'])
        ->name('api.getPetProduct');

    Route::post('/pet-products', [PetProductController::class, 'createProduct'])
        ->name('api.createPetProduct');

    Route::patch('/pet-products/{petProduct}', [PetProductController::class, 'update'])
        ->name('api.updatePetProduct');

    Route::delete('/pet-products/{petProduct}', [PetProductController::class, 'destroy'])
        ->name('api.deletePetProduct');

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('api.getOrders');

    Route::post('/orders', [OrderController::class, 'createOrder'])
        ->name('api.createOrder');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('api.getOrder');

    Route::patch('/orders/{order}', [OrderController::class, 'update'])
        ->name('api.updateOrder');

    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])
        ->name('api.deleteOrder');

    Route::get('/order-items', [OrderItemsController::class, 'index'])
        ->name('api.getOrderItems');

    Route::post('/order-items', [OrderItemsController::class, 'store'])
        ->name('api.createOrderItem');

    Route::get('/order-items/{item}', [OrderItemsController::class, 'show'])
        ->name('api.getOrderItem');

    Route::patch('/order-items/{item}', [OrderItemsController::class, 'update'])
        ->name('api.updateOrderItem');

    Route::delete('/order-items/{item}', [OrderItemsController::class, 'destroy'])
        ->name('api.deleteOrderItem');

    Route::get('/carts', [CartController::class, 'index'])
        ->name('api.getCarts');

    Route::post('/carts', [CartController::class, 'store'])
        ->name('api.createCart');

    Route::patch('/carts/{cart}', [CartController::class, 'update'])
        ->name('api.updateCart');

    Route::delete('/carts/{cart}', [CartController::class, 'destroy'])
        ->name('api.deleteCart');
});
