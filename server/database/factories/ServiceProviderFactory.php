<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceProvider>
 */
class ServiceProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $serviceTypes = ['Grooming', 'Training', 'Boarding', 'Walking'];

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'service_type' => fake()->randomElement($serviceTypes),
            'service_desc' => fake()->paragraph(),
            'rate_per_hour' => fake()->randomFloat(2, 10, 100),
            'rating' => fake()->randomFloat(1, 1, 5),
        ];
    }
}
