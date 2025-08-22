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
        // First, randomly select a type
        $type = $this->faker->randomElement(['sale', 'adoption']);

        // Then, select a valid status based on the chosen type
        if ($type === 'adoption') {
            $status = $this->faker->randomElement(['available', 'adopted']);
        } else { // 'sale'
            $status = $this->faker->randomElement(['available', 'sold']);
        }

        return [
            'user_id' => User::factory(),
            'pet_id' => Pet::factory(),
            'date' => $this->faker->date(),
            'type' => $type,
            'status' => $status,
            'description' => $this->faker->text(200),
            'fee' => ($type === 'sale') ? $this->faker->randomFloat(2, 50, 500) : null,
        ];
    }
}