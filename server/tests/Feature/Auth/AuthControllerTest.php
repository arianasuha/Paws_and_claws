<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Log;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'token_type',
                'expires_at',
            ])
            ->assertJsonFragment([
                'token_type' => 'Bearer',
            ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }

    #[Test]
    public function it_fails_to_login_with_invalid_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function it_fails_to_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment(['errors' => 'Credentials are incorrect.']);
    }

    #[Test]
    public function it_fails_to_login_with_inactive_user()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password123!'),
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment(['errors' => 'Credentials are incorrect.']);
    }

    #[Test]
    public function it_fails_to_login_with_invalid_data()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid-email', // Invalid email format
            'password' => '', // Missing password
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    #[Test]
    public function it_can_logout_authenticated_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => 'Successfully logged out.']);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'token' => explode('|', $token)[1],
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }
}