<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use App\Models\EmergencyShelter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmergencyShelter>
 */
class EmergencyShelterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmergencyShelter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),

            'user_id' => User::factory(),

            'request_date' => $this->faker->date(),
        ];
    }
}
