<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reviewer' => User::factory(),
            'reviewee' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'review_text' => $this->faker->paragraph(),
            'review_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}