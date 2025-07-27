<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pet;
use App\Models\Vet;
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
    }
}
