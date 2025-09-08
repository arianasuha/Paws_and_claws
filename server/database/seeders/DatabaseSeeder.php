<?php

// File: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pet;
use App\Models\Vet;
use App\Models\PetProduct;
use App\Models\PetMarket;
use App\Models\Appointment;
use App\Models\ReportLostPet;
use App\Models\ServiceProvider;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PetMedical;
use App\Models\MedicalLog;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\CartItem;
use App\Models\EmergencyShelter;
use App\Models\Payment;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        Vet::factory()->count(10)->create();
        ServiceProvider::factory()->count(10)->create();
        MedicalLog::factory()->count(10)->create();
        PetProduct::factory()->count(10)->create();
        Category::factory()->count(10)->create();

        Pet::factory()->count(10)->create();
        Cart::factory()->count(10)->create();
        Order::factory()->count(10)->create();
        ReportLostPet::factory()->count(10)->create();
        Notification::factory()->count(10)->create();

        EmergencyShelter::factory()->count(10)->create();
        Appointment::factory()->count(10)->create();
        CartItem::factory()->count(10)->create();

        // \Log::info('Created 10 appointments');

        // \Log::info('Created 10 pet diseases');
        PetMedical::factory()->count(10)->create();
        PetMarket::factory()->count(10)->create();
        OrderItem::factory()->count(10)->create();
        Payment::factory()->count(10)->create();
    }
}
