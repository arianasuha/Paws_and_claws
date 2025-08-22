<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Associate a notification with a user by creating a new User
            // or using an existing one. `User::factory()` is the standard way.
            'user_id' => User::factory(),

            // Generate a fake sentence for the notification subject
            'subject' => $this->faker->sentence(),

            // Generate a fake paragraph for the notification message
            'message' => $this->faker->paragraph(),

            // Randomly set the notification as read or unread
            'is_read' => $this->faker->boolean(),
        ];
    }

    /**
     * Indicate that the notification is unread.
     *
     * @return static
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => false,
        ]);
    }

    /**
     * Indicate that the notification is read.
     *
     * @return static
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
        ]);
    }
}
