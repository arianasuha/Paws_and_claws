<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceProvider::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $serviceTypes = ['Grooming', 'Training', 'Boarding', 'Walking'];

        return [
            // This is the key change: Laravel will create a new User and get its ID.
            'user_id' => User::factory(),
            'service_type' => fake()->randomElement($serviceTypes),
            'service_desc' => fake()->paragraph(),
            'rate_per_hour' => fake()->randomFloat(2, 10, 100),
            'rating' => fake()->randomFloat(1, 1, 5),
        ];
    }
}