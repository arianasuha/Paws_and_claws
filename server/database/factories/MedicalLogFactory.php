<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MedicalLog;
use App\Models\Appointment;

class MedicalLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MedicalLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'app_id' => Appointment::factory(),
            'treat_pres' => $this->faker->text(200),
            'diagnosis' => $this->faker->text(200),
        ];
    }
}