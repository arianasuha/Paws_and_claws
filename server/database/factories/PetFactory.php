<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    protected $model = Pet::class;

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
            'species' => $this->faker->randomElement(['Dog', 'Cat', 'Bird', 'Fish', 'Rabbit']),
            'breed' => $this->faker->word(),
            'dob' => $this->faker->dateTimeBetween('-15 years', '-1 month')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'weight' => $this->faker->optional(0.8)->numberBetween(1, 50), // 80% chance of a value, 20% chance of null
            'height' => $this->faker->optional(0.8)->numberBetween(10, 100), // 80% chance of a value, 20% chance of null
            'image_url' => $this->faker->optional(0.5)->imageUrl(640, 480, 'animals'), // 50% chance of a value
        ];
    }
}