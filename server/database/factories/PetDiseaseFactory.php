<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\DiseaseLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PetDisease>
 */
class PetDiseaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'disease_id' => DiseaseLog::factory(),
        ];
    }
}
