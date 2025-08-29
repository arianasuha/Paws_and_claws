<?php

namespace Database\Factories;

use App\Models\MedicalLog;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PetMedical>
 */
class PetMedicalFactory extends Factory
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
            'medical_id' => MedicalLog::factory(),
        ];
    }
}
