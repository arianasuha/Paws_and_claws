<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_user_with_valid_data()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'is_active' => true,
            'is_admin' => false,
            'is_vet' => false,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'is_active' => true,
            'is_admin' => false,
            'is_vet' => false,
        ]);

        $this->assertTrue(Hash::check('Password123!', $user->password));
    }

    #[Test]
    public function it_generates_slug_from_email()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john.doe@example.com',
            'password' => 'Password123!',
            'is_active' => true,
            'is_admin' => false,
            'is_vet' => false,
        ]);

        $this->assertEquals('johndoe-at-examplecom', $user->slug);
    }

    #[Test]
    public function it_hashes_password_on_creation()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'is_active' => true,
            'is_admin' => false,
            'is_vet' => false,
        ]);

        $this->assertNotEquals('Password123!', $user->password);
        $this->assertTrue(Hash::check('Password123!', $user->password));
    }

    #[Test]
    public function it_throws_validation_exception_for_short_password()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters.');

        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'Short1!',
        ]);
    }

    #[Test]
    public function it_throws_validation_exception_for_password_without_uppercase()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Password must contain at least one uppercase letter.');

        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123!',
        ]);
    }

    #[Test]
    public function it_throws_validation_exception_for_password_without_lowercase()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Password must contain at least one lowercase letter.');

        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'PASSWORD123!',
        ]);
    }

    #[Test]
    public function it_throws_validation_exception_for_password_without_number()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Password must contain at least one number.');

        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'Password!',
        ]);
    }

    #[Test]
    public function it_throws_validation_exception_for_password_without_special_character()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Password must contain at least one special character.');

        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'Password123',
        ]);
    }

    #[Test]
    public function it_casts_boolean_attributes_correctly()
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'is_active' => 1,
            'is_admin' => 0,
            'is_vet' => 1,
        ]);

        $this->assertIsBool($user->is_active);
        $this->assertIsBool($user->is_admin);
        $this->assertIsBool($user->is_vet);
        $this->assertTrue($user->is_active);
        $this->assertFalse($user->is_admin);
        $this->assertTrue($user->is_vet);
    }

    #[Test]
    public function it_hides_password_and_remember_token_in_serialization()
    {
        $user = User::factory()->create([
            'password' => Hash::make('Password123!'),
            'remember_token' => 'random_token',
        ]);

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    #[Test]
    public function it_uses_factory_correctly()
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->email);
        $this->assertNotNull($user->username);
        $this->assertNotNull($user->slug);
        $this->assertTrue($user->is_active);
        $this->assertFalse($user->is_admin);
        $this->assertFalse($user->is_vet);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    #[Test]
    public function it_uses_factory_with_custom_password()
    {
        $customPassword = 'CustomPass123!';
        $user = User::factory()->withPassword($customPassword)->create();

        $this->assertTrue(Hash::check($customPassword, $user->password));
    }
}