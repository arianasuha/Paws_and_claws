<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Reminder; // Add the Reminder model to the imports
use Illuminate\Database\Eloquent\Factories\Factory;

class ReminderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reminder::class;

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
            // Use Pet::factory() to create a pet automatically
            'pet_id' => Pet::factory(),
            'med_name' => fake()->word(),
            'dosage' => fake()->randomElement(['1 tablet', '100mg', '2ml']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reminder_time' => fake()->time(),
        ];
    }
}