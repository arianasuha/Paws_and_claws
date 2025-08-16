<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Pet; // Add the Pet model to the imports
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
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
            'species' => $this->faker->randomElement(['Dog', 'Cat', 'Bird', 'Rabbit']),
            'breed' => $this->faker->word(),
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'weight' => $this->faker->numberBetween(1, 50),
            'height' => $this->faker->numberBetween(10, 100),
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}