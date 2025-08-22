<?php

namespace Database\Factories;

use App\Models\CartItems;
use App\Models\Cart;
use App\Models\PetProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItems>
 */
class CartItemsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartItems::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // The factory correctly creates a Cart and a Product and gets their IDs.
            'cart_id' => Cart::factory(),
            'product_id' => PetProduct::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
