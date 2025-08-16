<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Pet;
use App\Models\ReportLostPet;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportLostPetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReportLostPet::class;

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
            // Use nested factories to create a User and a Pet automatically
            'user_id' => User::factory(),
            'pet_id' => Pet::factory(),
            'status' => fake()->randomElement($statuses),
        ];
    }
}