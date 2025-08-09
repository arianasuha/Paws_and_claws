<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PetMarket>
 */
class PetMarketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['adoption', 'sale', 'breeding'];
        $statuses = ['available', 'pending', 'sold'];

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'pet_id' => Pet::inRandomOrder()->first()->id,
            'date' => Carbon::now()->subDays(rand(1, 30)),
            'type' => fake()->randomElement($types),
            'status' => fake()->randomElement($statuses),
            'description' => fake()->paragraph(),
            'fee' => fake()->randomFloat(2, 10, 1000),
        ];
    }
}
