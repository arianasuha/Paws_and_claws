<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->firstName(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'species' => $this->faker->randomElement(['Dog', 'Cat', 'Bird']),
            'breed' => $this->faker->word(),
            'dob' => $this->faker->date('Y-m-d', 'now - 1 year'),
            'image_url' => null, // Set to null to match controller logic
            'height' => null, // Nullable, not set by default
            'weight' => null, // Nullable, not set by default
        ];
    }
}