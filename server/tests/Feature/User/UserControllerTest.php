<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_list_users_for_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        Sanctum::actingAs($admin);
        User::factory()->count(15)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'username',
                        'address',
                        'is_admin',
                        'is_active',
                        'is_vet',
                        'slug',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'current_page',
                'last_page',
            ])
            ->assertJsonCount(10, 'data');
    }

    #[Test]
    public function it_fails_to_list_users_for_non_admin()
    {
        $user = User::factory()->create(['is_admin' => false]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/users');

        $response->assertStatus(403)
            ->assertJson(['errors' => 'You are not authorized to view all users.']);
    }

    #[Test]
    public function it_fails_to_list_users_without_authentication()
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401)
            ->assertJson(['errors' => 'You are not authenticated']);
    }

    #[Test]
    public function it_can_show_own_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
            ]);
    }

    #[Test]
    public function it_can_show_user_by_slug()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/users/{$user->slug}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
            ]);
    }

    #[Test]
    public function it_can_show_any_user_for_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $otherUser = User::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/users/{$otherUser->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $otherUser->id,
                'email' => $otherUser->email,
                'username' => $otherUser->username,
            ]);
    }

    #[Test]
    public function it_fails_to_show_another_user_for_non_admin()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/users/{$otherUser->id}");

        $response->assertStatus(403)
            ->assertJson(['errors' => 'You are not authorized to view this user.']);
    }

    #[Test]
    public function it_fails_to_show_nonexistent_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'User not found']);
    }

    #[Test]
    public function it_fails_to_show_user_without_authentication()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(401)
            ->assertJson(['errors' => 'You are not authenticated']);
    }

    #[Test]
    public function it_can_create_a_user()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'address' => '123 Main St',
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201)
            ->assertJson(['success' => 'User created successfully. Please verify your email to activate your account.']);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address' => '123 Main St',
        ]);
    }

    #[Test]
    public function it_fails_to_create_user_with_invalid_data()
    {
        $data = [
            'first_name' => '', // Nullable, so no error
            'email' => 'invalid-email',
            'username' => '',
            'password' => 'weak',
            'password_confirmation' => 'different',
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'username', 'password']);
    }

    #[Test]
    public function it_fails_to_create_user_with_duplicate_email()
    {
        $existingUser = User::factory()->create(['email' => 'john.doe@example.com']);

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonFragment(['errors' => ['email' => ['The email address is already in use.']]]);
    }

    #[Test]
    public function it_fails_to_create_user_with_is_admin()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'is_admin' => true,
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(403)
            ->assertJson(['errors' => 'You are not authorized to create an admin user.']);
    }

    #[Test]
    public function it_can_create_admin_user()
    {
        $data = [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'username' => 'adminuser',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'address' => '456 Admin St',
        ];

        $response = $this->postJson('/api/admin/users', $data);

        $response->assertStatus(201)
            ->assertJson(['success' => 'User created successfully. Please verify your email to activate your account.']);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'username' => 'adminuser',
            'is_admin' => true,
        ]);
    }

    #[Test]
    public function it_can_update_own_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => 'updated@example.com',
            'username' => 'updateduser',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => 'User updated successfully.',
                'first_name' => 'Updated',
                'last_name' => 'Name',
                'email' => 'updated@example.com',
                'username' => 'updateduser',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Updated',
            'email' => 'updated@example.com',
            'username' => 'updateduser',
        ]);
    }

    #[Test]
    public function it_can_update_user_by_slug()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => 'updated@example.com',
            'username' => 'updateduser',
        ];

        $response = $this->putJson("/api/users/{$user->slug}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => 'User updated successfully.',
                'first_name' => 'Updated',
                'last_name' => 'Name',
                'email' => 'updated@example.com',
                'username' => 'updateduser',
            ]);
    }

    #[Test]
    public function it_can_update_any_user_for_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $otherUser = User::factory()->create();
        Sanctum::actingAs($admin);

        $data = [
            'first_name' => 'AdminUpdated',
            'last_name' => 'Name',
            'email' => 'admin.updated@example.com',
            'username' => 'adminupdateduser',
        ];

        $response = $this->putJson("/api/users/{$otherUser->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => 'User updated successfully.',
                'first_name' => 'AdminUpdated',
                'email' => 'admin.updated@example.com',
                'username' => 'adminupdateduser',
            ]);
    }

    #[Test]
    public function it_fails_to_update_another_user_for_non_admin()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'first_name' => 'Unauthorized',
        ];

        $response = $this->putJson("/api/users/{$otherUser->id}", $data);

        $response->assertStatus(403)
            ->assertJson(['errors' => 'You are not authorized to update this user.']);
    }

    #[Test]
    public function it_fails_to_update_user_with_is_admin()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'first_name' => 'John',
            'is_admin' => true,
        ];

        $response = $this->putJson("/api/users/{$user->id}", $data);

        $response->assertStatus(403)
            ->assertJson(['errors' => 'You are not authorized to change account status.']);
    }

    #[Test]
    public function it_fails_to_update_nonexistent_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/users/999', ['first_name' => 'Test']);

        $response->assertStatus(404)
            ->assertJson(['error' => 'User not found']);
    }

    #[Test]
    public function it_fails_to_update_user_without_authentication()
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/users/{$user->id}", ['first_name' => 'Test']);

        $response->assertStatus(401)
            ->assertJson(['errors' => 'You are not authenticated']);
    }

    #[Test]
    public function it_can_delete_own_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function it_can_delete_user_by_slug()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->slug}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function it_fails_to_delete_another_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/users/{$otherUser->id}");

        $response->assertStatus(403)
            ->assertJson(['errors' => 'You are not authorized to delete this user.']);
    }

    #[Test]
    public function it_fails_to_delete_nonexistent_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/users/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'User not found']);
    }

    #[Test]
    public function it_fails_to_delete_user_without_authentication()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(401)
            ->assertJson(['errors' => 'You are not authenticated']);
    }
}