<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Reminder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserReminder>
 */
class UserReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'reminder_id' => Reminder::inRandomOrder()->first()->reminder_id,
        ];
    }
}
