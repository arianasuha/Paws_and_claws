<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DiseaseLog;

class DiseaseLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiseaseLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'symptoms' => $this->faker->text(200),
            'causes' => $this->faker->text(200),
            'treat_options' => $this->faker->text(200),
            'severity' => $this->faker->randomElement(['low', 'medium', 'high']),
        ];
    }
}