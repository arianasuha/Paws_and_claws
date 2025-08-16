<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PetMarket;
use App\Models\User;
use App\Models\Pet;

class PetMarketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PetMarket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pet_id' => Pet::factory(),
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['sale', 'adoption']),
            'status' => $this->faker->randomElement(['available', 'sold', 'adopted', 'pending']),
            'description' => $this->faker->text(200),
            'fee' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}