<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiseaseLog>
 */
class DiseaseLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $severities = ['Low', 'Medium', 'High', 'Critical'];

        return [
            'symptoms' => fake()->paragraph(),
            'causes' => fake()->paragraph(),
            'treat_options' => fake()->paragraph(),
            'severity' => fake()->randomElement($severities),
        ];
    }
}
