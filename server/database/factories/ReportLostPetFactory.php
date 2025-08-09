<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportLostPet>
 */
class ReportLostPetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['missing', 'found', 'resolved'];

        return [
            'location' => fake()->city(),
            'date_lost' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'user_id' => User::inRandomOrder()->first()->id,
            'pet_id' => Pet::inRandomOrder()->first()->id,
            'status' => fake()->randomElement($statuses),
        ];
    }
}
