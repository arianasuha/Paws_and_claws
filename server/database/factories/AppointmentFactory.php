<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'provider_id' => User::factory(),
            'pet_id' => Pet::factory(),
            'app_date' => $this->faker->date(),
            'app_time' => $this->faker->time(),
            'visit_reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'completed', 'canceled']),

        ];
    }
}
