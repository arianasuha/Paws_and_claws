<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Vet\VetController;
use App\Http\Controllers\Pet\PetProductController;
use App\Http\Controllers\Pet\PetMarketController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItemsController;
use App\Http\Controllers\Order\CartController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Pet\ReportLostPetController;
use App\Http\Controllers\Medical\MedicalLogController;
use App\Http\Controllers\Medical\DiseaseLogController;
use App\Http\Controllers\Pet\PetMedicalController;
use App\Http\Controllers\Pet\PetDiseaseController;
use App\Http\Controllers\Pet\PetReminderController;
use App\Http\Controllers\User\UserReminderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceProviderController;


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

    Route::get('/lost-pets', [ReportLostPetController::class, 'index'])
        ->name('api.getLostPets');

    Route::post('/lost-pets', [ReportLostPetController::class, 'store'])
        ->name('api.createLostPetReport');

    Route::get('/lost-pets/{lostPetReport}', [ReportLostPetController::class, 'show'])
        ->name('api.getLostPetReport');

    Route::patch('/lost-pets/{lostPetReport}', [ReportLostPetController::class, 'update'])
        ->name('api.updateLostPetReport');

    Route::delete('/lost-pets/{lostPetReport}', [ReportLostPetController::class, 'destroy'])
        ->name('api.deleteLostPetReport');

    Route::get('/medical-logs', [MedicalLogController::class, 'index'])
        ->name('api.getMedicalLogs');

    Route::get('/medical-logs/{medicalLog}', [MedicalLogController::class, 'show'])
        ->name('api.getMedicalLog');

    Route::post('/medical-logs', [MedicalLogController::class, 'store'])
        ->name('api.createMedicalLog');

    Route::patch('/medical-logs/{medicalLog}', [MedicalLogController::class, 'update'])
        ->name('api.updateMedicalLog');

    Route::delete('/medical-logs/{medicalLog}', [MedicalLogController::class, 'destroy'])
        ->name('api.deleteMedicalLog');

    Route::get('/disease-logs', [DiseaseLogController::class, 'index'])
        ->name('api.getDiseaseLogs');

    Route::get('/disease-logs/{diseaseLog}', [DiseaseLogController::class, 'show'])
        ->name('api.getDiseaseLog');

    Route::post('/disease-logs', [DiseaseLogController::class, 'store'])
        ->name('api.createDiseaseLog');

    Route::patch('/disease-logs/{diseaseLog}', [DiseaseLogController::class, 'update'])
        ->name('api.updateDiseaseLog');

    Route::delete('/disease-logs/{diseaseLog}', [DiseaseLogController::class, 'destroy'])
        ->name('api.deleteDiseaseLog');

    Route::get('/pet-medicals', [PetMedicalController::class, 'index'])
        ->name('api.getPetMedicals');

    Route::get('/pet-medicals/{petMedical}', [PetMedicalController::class, 'show'])
        ->name('api.getPetMedical');

    Route::post('/pet-medicals', [PetMedicalController::class, 'store'])
        ->name('api.createPetMedical');

    Route::patch('/pet-medicals/{petMedical}', [PetMedicalController::class, 'update'])
        ->name('api.updatePetMedical');

    Route::delete('/pet-medicals/{petMedical}', [PetMedicalController::class, 'destroy'])
        ->name('api.deletePetMedical');

    Route::get('/pet-diseases', [PetDiseaseController::class, 'index'])
        ->name('api.getPetDiseases');

    Route::get('/pet-diseases/{petDisease}', [PetDiseaseController::class, 'show'])
        ->name('api.getPetDisease');

    Route::post('/pet-diseases', [PetDiseaseController::class, 'store'])
        ->name('api.createPetDisease');

    Route::patch('/pet-diseases/{petDisease}', [PetDiseaseController::class, 'update'])
        ->name('api.updatePetDisease');

    Route::delete('/pet-diseases/{petDisease}', [PetDiseaseController::class, 'destroy'])
        ->name('api.deletePetDisease');

    Route::get('/reminders', [PetReminderController::class, 'index'])
        ->name('api.getReminders');

    Route::post('/reminders', [PetReminderController::class, 'store'])
        ->name('api.createReminder');

    Route::get('/reminders/{reminder}', [PetReminderController::class, 'show'])
        ->name('api.getReminder');

    Route::patch('/reminders/{reminder}', [PetReminderController::class, 'update'])
        ->name('api.updateReminder');

    Route::delete('/reminders/{reminder}', [PetReminderController::class, 'destroy'])
        ->name('api.deleteReminder');

    Route::get('/user-reminders', [UserReminderController::class, 'index'])
        ->name('api.getUserReminders');

    Route::post('/user-reminders', [UserReminderController::class, 'store'])
        ->name('api.createUserReminder');

    Route::get('/user-reminders/{userReminder}', [UserReminderController::class, 'show'])
        ->name('api.getUserReminder');

    Route::patch('/user-reminders/{userReminder}', [UserReminderController::class, 'update'])
        ->name('api.updateUserReminder');

    Route::delete('/user-reminders/{userReminder}', [UserReminderController::class, 'destroy'])
        ->name('api.deleteUserReminder');

    Route::get('/reviews', [ReviewController::class, 'index'])
        ->name('api.getReviews');

    Route::post('/reviews', [ReviewController::class, 'store'])
        ->name('api.createReview');

    Route::get('/reviews/{review}', [ReviewController::class, 'show'])
        ->name('api.getReview');

    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])
        ->name('api.updateReview');

    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
        ->name('api.deleteReview');

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
});
