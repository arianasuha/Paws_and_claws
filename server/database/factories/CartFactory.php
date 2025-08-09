<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\PetProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'product_id' => PetProduct::inRandomOrder()->first()->product_id,
            'quantity' => fake()->numberBetween(1, 10),
        ];
    }
}
