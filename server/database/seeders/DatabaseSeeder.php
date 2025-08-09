<?php

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
use App\Models\Checkout;
use App\Models\OrderItem;
use App\Models\Reminder;
use App\Models\UserReminder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        Pet::factory()->count(10)->create();

        Vet::factory()->count(10)->create();

        PetProduct::factory()->count(10)->create();

        DiseaseLog::factory()->count(10)->create();

        PetDisease::factory()->count(10)->create();

        PetMarket::factory()->count(10)->create();

        Appointment::factory()->count(10)->create();

        ReportLostPet::factory()->count(10)->create();

        ServiceProvider::factory()->count(10)->create();

        Cart::factory()->count(10)->create();

        Checkout::factory()->count(10)->create();

        OrderItem::factory()->count(10)->create();

        Reminder::factory()->count(10)->create();

        UserReminder::factory()->count(10)->create();
    }
}

