<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vet>
 */
class VetFactory extends Factory
{
    /**
     * The default specialization used by the factory.
     */
    protected static ?string $specialization = 'General Practice';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['is_vet' => true]),
            'clinic_name' => $this->faker->unique()->company . ' Veterinary Clinic',
            'specialization' => static::$specialization,
            'services_offered' => $this->faker->sentence(10),
            'working_hour' => $this->faker->randomElement([
                'Mon-Fri 9AM-5PM',
                'Mon-Sat 8AM-6PM',
                'Mon-Fri 10AM-4PM',
                '24/7 Emergency',
            ]),
        ];
    }

    /**
     * Indicate that the vet has a specific clinic name.
     *
     * @param string $clinicName
     * @return $this
     */
    public function withClinicName(string $clinicName): static
    {
        return $this->state(fn (array $attributes) => [
            'clinic_name' => $clinicName,
        ]);
    }

    /**
     * Indicate that the vet has a specific specialization.
     *
     * @param string $specialization
     * @return $this
     */
    public function withSpecialization(string $specialization): static
    {
        return $this->state(fn (array $attributes) => [
            'specialization' => $specialization,
        ]);
    }
}