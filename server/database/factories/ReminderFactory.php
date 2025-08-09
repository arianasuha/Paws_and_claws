<?php

namespace Database\Factories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reminder>
 */
class ReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', 'now');
        $endDate = fake()->dateTimeBetween($startDate, '+1 month');

        return [
            'pet_id' => Pet::inRandomOrder()->first()->id,
            'med_name' => fake()->word(),
            'dosage' => fake()->randomElement(['1 tablet', '100mg', '2ml']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reminder_time' => fake()->time(),
        ];
    }
}
