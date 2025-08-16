<?php

// File: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pet;
use App\Models\Vet;
use App\Models\DiseaseLog;
use App\Models\PetProduct;
use App\Models\PetDisease;
use App\Models\PetMarket;
use App\Models\Appointment;
use App\Models\ReportLostPet;
use App\Models\ServiceProvider;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reminder;
use App\Models\UserReminder;
use App\Models\PetMedical;
use App\Models\MedicalLog;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed tables with no dependencies first.
        User::factory()->count(10)->create();
        Vet::factory()->count(10)->create();
        ServiceProvider::factory()->count(10)->create();
        MedicalLog::factory()->count(10)->create();
        Reminder::factory()->count(10)->create();
        DiseaseLog::factory()->count(10)->create();
        PetProduct::factory()->count(10)->create();

        // 2. Seed tables that depend on the first group.
        Pet::factory()->count(10)->create();
        Cart::factory()->count(10)->create();
        Review::factory()->count(10)->create();
        Order::factory()->count(10)->create();
        ReportLostPet::factory()->count(10)->create();

        // 3. Seed tables that depend on the previous two groups.
        Appointment::factory()->count(10)->create();
        // \Log::info('Created 10 appointments');

        // 4. Finally, seed the many-to-many pivot tables, which depend on multiple other tables.
        PetDisease::factory()->count(10)->create();
        // \Log::info('Created 10 pet diseases');
        PetMedical::factory()->count(10)->create();
        PetMarket::factory()->count(10)->create();
        OrderItem::factory()->count(10)->create();
        UserReminder::factory()->count(10)->create();
    }
}
