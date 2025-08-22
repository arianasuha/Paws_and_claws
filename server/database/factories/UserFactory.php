<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->unique()->userName(),
            // Use a strong password that meets your model's validation rules
            'password' => Hash::make('P@ssw0rd123'),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'is_admin' => $this->faker->boolean(5), // 5% chance of being an admin
            'is_vet' => $this->faker->boolean(10), // 10% chance of being a vet
            'remember_token' => Str::random(10),
        ];
    }
}