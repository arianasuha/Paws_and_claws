<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MedicalLog;
use Illuminate\Support\Carbon; // Import Carbon for date generation

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
            'visit_date' => $this->faker->date(),
            'vet_name' => $this->faker->optional(0.8)->name('male' | 'female'), // 80% chance of having a vet name
            'clinic_name' => $this->faker->optional(0.7)->company(), // 70% chance of having a clinic name
            'reason_for_visit' => $this->faker->sentence(5),
            'diagnosis' => $this->faker->sentence(4),
            'treatment_prescribed' => $this->faker->text(200),
            'notes' => $this->faker->optional(0.9)->text(500), // 90% chance of having notes
            'attachment_url' => $this->faker->optional(0.2)->imageUrl(), // 20% chance of a mock URL
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
