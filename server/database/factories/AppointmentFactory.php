<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Vet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['scheduled', 'completed', 'canceled'];

        return [
            'pet_id' => Pet::inRandomOrder()->first()->id,
            'vet_id' => Vet::inRandomOrder()->first()->id,
            'app_date' => fake()->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'app_time' => fake()->time('H:i'),
            'visit_reason' => fake()->sentence(),
            'status' => fake()->randomElement($statuses),
        ];
    }
}
