<?php

namespace Tests\Feature\Pet;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PetControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_list_pets()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Pet::factory()->count(15)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/pets');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'name',
                        'gender',
                        'species',
                        'breed',
                        'dob',
                        'image_url',
                    ],
                ],
                'current_page',
                'last_page',
            ]);
    }

    #[Test]
    public function it_can_create_a_pet_without_image()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'name' => 'Buddy',
            'gender' => 'male',
            'species' => 'Dog',
            'breed' => 'Golden Retriever',
            'dob' => '2020-01-01',
        ];

        $response = $this->postJson('/api/pets', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'user_id',
                'name',
                'gender',
                'species',
                'breed',
                'dob',
                'image_url',
            ])
            ->assertJsonFragment([
                'name' => 'Buddy',
                'user_id' => $user->id,
                'dob' => '2020-01-01',
                'image_url' => null,
            ]);

        $this->assertDatabaseHas('pets', [
            'name' => 'Buddy',
            'user_id' => $user->id,
            'dob' => '2020-01-01',
            'image_url' => null,
        ]);
    }

    #[Test]
    public function it_can_create_a_pet_with_image()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'name' => 'Buddy',
            'gender' => 'male',
            'species' => 'Dog',
            'breed' => 'Golden Retriever',
            'dob' => '2020-01-01',
            'image_url' => UploadedFile::fake()->image('pet.jpg'),
        ];

        $response = $this->postJson('/api/pets', $data);

        $response->dump(); // Keep for debugging

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'user_id',
                'name',
                'gender',
                'species',
                'breed',
                'dob',
                'image_url',
            ])
            ->assertJsonFragment([
                'name' => 'Buddy',
                'user_id' => $user->id,
                'dob' => '2020-01-01',
            ]);

        $pet = Pet::where('name', 'Buddy')->first();
        $this->assertNotNull($pet->image_url);
        $this->assertStringContainsString('pet_images', $pet->image_url);
        // Fix the storage path by removing the incorrect 'public/' prefix
        $storagePath = str_replace('/storage/', '', $pet->image_url);
        \Log::info($storagePath);
        Storage::disk('public')->assertExists($storagePath);
    }

    #[Test]
    public function it_fails_to_create_pet_with_invalid_data()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'name' => '',
            'gender' => 'invalid',
            'species' => '',
            'dob' => 'invalid-date',
        ];

        $response = $this->postJson('/api/pets', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'gender', 'species', 'dob']);
    }

    #[Test]
    public function it_fails_to_create_pet_for_another_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'name' => 'Buddy',
            'gender' => 'male',
            'species' => 'Dog',
            'breed' => 'Golden Retriever',
            'dob' => '2020-01-01',
            'user_id' => $otherUser->id,
        ];

        $response = $this->postJson('/api/pets', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id'])
            ->assertJsonFragment(['errors' => ['user_id' => ['You can only register pets for yourself.']]]);
    }

    #[Test]
    public function it_can_show_a_pet()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $pet = Pet::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/pets/{$pet->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $pet->id]);
    }

    #[Test]
    public function it_fails_to_show_nonexistent_pet()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/pets/999');

        $response->assertStatus(404)
            ->assertJsonFragment(['error' => 'Pet not found']);
    }

    #[Test]
    public function it_can_update_own_pet_without_image_change()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $pet = Pet::factory()->create(['user_id' => $user->id, 'image_url' => null]);

        $data = [
            'name' => 'Max',
            'gender' => 'male',
            'species' => 'Dog',
            'breed' => 'Labrador',
            'dob' => '2021-01-01',
        ];

        $response = $this->putJson("/api/pets/{$pet->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => 'Pet information updated successfully.',
                'pet' => [
                    'id' => $pet->id,
                    'user_id' => $user->id,
                    'name' => 'Max',
                    'gender' => 'male',
                    'species' => 'Dog',
                    'breed' => 'Labrador',
                    'dob' => '2021-01-01',
                    'image_url' => null,
                ],
            ]);

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'name' => 'Max',
            'dob' => '2021-01-01',
            'image_url' => null,
        ]);
    }

    #[Test]
    public function it_can_update_own_pet_with_new_image()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $pet = Pet::factory()->create([
            'user_id' => $user->id,
            'image_url' => Storage::url('pet_images/old.jpg'),
        ]);

        Storage::disk('public')->put('pet_images/old.jpg', 'old image content');

        $data = [
            'name' => 'Max',
            'gender' => 'male',
            'species' => 'Dog',
            'breed' => 'Labrador',
            'dob' => '2021-01-01',
            'image_url' => UploadedFile::fake()->image('new.jpg'),
        ];

        $response = $this->putJson("/api/pets/{$pet->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => 'Pet information updated successfully.',
                'pet' => [
                    'id' => $pet->id,
                    'user_id' => $user->id,
                    'name' => 'Max',
                    'gender' => 'male',
                    'species' => 'Dog',
                    'breed' => 'Labrador',
                    'dob' => '2021-01-01',
                ],
            ]);

        $updatedPet = Pet::find($pet->id);
        $this->assertNotNull($updatedPet->image_url);
        $this->assertStringContainsString('pet_images', $updatedPet->image_url);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $updatedPet->image_url));
        Storage::disk('public')->assertMissing('pet_images/old.jpg');
    }

    #[Test]
    public function it_can_update_own_pet_with_image_removal()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $pet = Pet::factory()->create([
            'user_id' => $user->id,
            'image_url' => Storage::url('pet_images/old.jpg'),
        ]);

        Storage::disk('public')->put('pet_images/old.jpg', 'old image content');

        $data = [
            'name' => 'Max',
            'gender' => 'male',
            'species' => 'Dog',
            'breed' => 'Labrador',
            'dob' => '2021-01-01',
            'image_url' => null,
        ];

        $response = $this->putJson("/api/pets/{$pet->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => 'Pet information updated successfully.',
                'pet' => [
                    'id' => $pet->id,
                    'user_id' => $user->id,
                    'name' => 'Max',
                    'gender' => 'male',
                    'species' => 'Dog',
                    'breed' => 'Labrador',
                    'dob' => '2021-01-01',
                    'image_url' => null,
                ],
            ]);

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'name' => 'Max',
            'dob' => '2021-01-01',
            'image_url' => null,
        ]);
        Storage::disk('public')->assertMissing('pet_images/old.jpg');
    }

    #[Test]
    public function it_fails_to_update_another_users_pet()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);
        $pet = Pet::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->putJson("/api/pets/{$pet->id}", ['name' => 'Max']);

        $response->assertStatus(403)
            ->assertJsonFragment(['error' => 'Unauthorized. You can only update your own pets.']);
    }

    #[Test]
    public function it_fails_to_update_nonexistent_pet()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/pets/999', ['name' => 'Max']);

        $response->assertStatus(404)
            ->assertJsonFragment(['error' => 'Pet not found']);
    }

    #[Test]
    public function it_can_delete_own_pet_with_image()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $pet = Pet::factory()->create([
            'user_id' => $user->id,
            'image_url' => Storage::url('pet_images/pet.jpg'),
        ]);

        Storage::disk('public')->put('pet_images/pet.jpg', 'image content');

        $response = $this->deleteJson("/api/pets/{$pet->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
        Storage::disk('public')->assertMissing('pet_images/pet.jpg');
    }

    #[Test]
    public function it_fails_to_delete_another_users_pet()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);
        $pet = Pet::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->deleteJson("/api/pets/{$pet->id}");

        $response->assertStatus(403)
            ->assertJsonFragment(['error' => 'Unauthorized. You can only update your own pets.']);
    }

    #[Test]
    public function it_fails_to_delete_nonexistent_pet()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/pets/999');

        $response->assertStatus(404)
            ->assertJsonFragment(['error' => 'Pet not found']);
    }

    #[Test]
    public function it_fails_to_access_endpoints_without_authentication()
    {
        $pet = Pet::factory()->create();

        $response = $this->getJson('/api/pets');
        $response->assertStatus(401)
            ->assertJson(['errors' => 'You are not authenticated']);

        $response = $this->postJson('/api/pets', []);
        $response->assertStatus(401)
            ->assertJson(['errors' => 'You are not authenticated']);

        $response = $this->getJson("/api/pets/{$pet->id}");
        $response->assertStatus(401)
            ->assertJson(['errors' => 'You are not authenticated']);

        $response = $this->putJson("/api/pets/{$pet->id}", []);
        $response->assertStatus(401)
            ->assertJson(['errors' => 'You are not authenticated']);

        $response = $this->deleteJson("/api/pets/{$pet->id}");
        $response->assertStatus(401)
            ->assertJson(['errors' => 'You are not authenticated']);
    }
}