<?php

use App\Http\Requests\EmergencyShelter\EmergencyShelterUpdateRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Vet\VetController;
use App\Http\Controllers\Pet\PetProductController;
use App\Http\Controllers\Pet\PetMarketController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Pet\ReportLostPetController;
use App\Http\Controllers\Medical\MedicalLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\Pet\EmergencyShelterController;

Route::post('/login', [AuthController::class, 'login'])
    ->name('api.login');

Route::post('/users', [UserController::class, 'createUser'])
    ->name('api.createUser');

Route::post('/admin/users', [UserController::class, 'createAdminUser'])
    ->name('api.createAdminUser');

Route::post('/vets', [VetController::class, 'createVet'])
    ->name('api.createVet');

Route::post('/service-providers', [ServiceProviderController::class, 'create'])
    ->name('api.createServiceProvider');


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

    Route::get('/pet-markets', [PetMarketController::class, 'index'])
        ->name('api.getPetMarkets');

    Route::post('/pet-markets', [PetMarketController::class, 'create'])
        ->name('api.createPetMarket');

    Route::get('/pet-markets/{petMarket}', [PetMarketController::class, 'show'])
        ->name('api.getPetMarket');

    Route::patch('/pet-markets/{petMarket}', [PetMarketController::class, 'update'])
        ->name('api.updatePetMarket');

    Route::delete('/pet-markets/{petMarket}', [PetMarketController::class, 'destroy'])
        ->name('api.deletePetMarket');

    Route::get('/appointments', [AppointmentController::class, 'index'])
        ->name('api.getAppointments');

    Route::post('/appointments', [AppointmentController::class, 'store'])
        ->name('api.createAppointment');

    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])
        ->name('api.getAppointment');

    Route::patch('/appointments/{appointment}', [AppointmentController::class, 'update'])
        ->name('api.updateAppointment');

    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])
        ->name('api.deleteAppointment');

    Route::get('/reports/lost-pets', [ReportLostPetController::class, 'index'])
        ->name('api.getLostPets');

    Route::post('/reports/lost-pets', [ReportLostPetController::class, 'store'])
        ->name('api.createLostPetReport');

    Route::get('/reports/lost-pets/{id}', [ReportLostPetController::class, 'show'])
        ->name('api.getLostPetReport');

    Route::patch('/reports/lost-pets/{id}', [ReportLostPetController::class, 'update'])
        ->name('api.updateLostPetReport');

    Route::delete('/reports/lost-pets/{id}', [ReportLostPetController::class, 'destroy'])
        ->name('api.deleteLostPetReport');

    Route::get('/medicalpet-logs/{petId}', [MedicalLogController::class, 'index'])
        ->name('api.getMedicalLogs');

    Route::get('/medical-logs/{medicalLog}', [MedicalLogController::class, 'show'])
        ->name('api.getMedicalLog');

    Route::post('/medical-logs', [MedicalLogController::class, 'store'])
        ->name('api.createMedicalLog');

    Route::patch('/medical-logs/{medicalLog}', [MedicalLogController::class, 'update'])
        ->name('api.updateMedicalLog');

    Route::delete('/medical-logs/{medicalLog}', [MedicalLogController::class, 'destroy'])
        ->name('api.deleteMedicalLog');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('api.getNotifications');

    Route::get('/notifications/available', [NotificationController::class, 'isNotiAvailable'])
        ->name('api.isNotiAvailable');

    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])
        ->name('api.getNotification');

    Route::put('/notifications/{notification}', [NotificationController::class, 'markAsRead'])
        ->name('api.markNotificationAsRead');

    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])
        ->name('api.deleteNotification');

    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('api.getCategories');

    Route::get('/categories/{id}', [CategoryController::class, 'show'])
        ->name('api.getCategory');

    Route::post('/categories', [CategoryController::class, 'create'])
        ->name('api.createCategory');

    Route::patch('/categories/{id}', [CategoryController::class, 'update'])
        ->name('api.updateCategory');

    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
        ->name('api.deleteCategory');

    Route::get('/service-providers', [ServiceProviderController::class, 'index'])
        ->name('api.getServiceProviders');

    Route::get('/service-providers/{id}', [ServiceProviderController::class, 'show'])
        ->name('api.getServiceProvider');

    Route::patch('/service-providers/{id}', [ServiceProviderController::class, 'update'])
        ->name('api.updateServiceProvider');

    Route::delete('/service-providers/{id}', [ServiceProviderController::class, 'destroy'])
        ->name('api.deleteServiceProvider');

    Route::get('/shelters', [EmergencyShelterController::class, 'index'])
        ->name('api.getServiceProviders');

    Route::get('/shelterspet/{shelterId}', [EmergencyShelterController::class, 'show'])
        ->name('api.getServiceProvider');

    Route::post('/shelters', [EmergencyShelterController::class, 'store'])
        ->name('api.updateServiceProvider');

    Route::delete('/shelters/{shelterId}', [EmergencyShelterController::class, 'destroy'])
        ->name('api.deleteServiceProvider');
});
