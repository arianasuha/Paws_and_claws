<?php

namespace Database\Factories;

use App\Models\Checkout;
use App\Models\PetProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Checkout::inRandomOrder()->first()->order_id,
            'product_id' => PetProduct::inRandomOrder()->first()->product_id,
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }
}
