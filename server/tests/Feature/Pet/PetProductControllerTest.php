<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\PetProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PetProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that we can get a list of pet products.
     */
    public function test_it_can_get_a_list_of_pet_products()
    {
        PetProduct::factory()->count(5)->create();

        $response = $this->getJson('/api/pet_products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'description', 'price', 'stock', 'image_url']
                     ],
                     'meta' => [
                         'total', 'per_page', 'current_page'
                     ]
                 ]);
    }

    /**
     * Test that we can create a pet product.
     */
    public function test_it_can_create_a_pet_product()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('dog_toy.jpg');

        $data = [
            'name' => 'Dog Toy',
            'description' => 'A durable toy for dogs.',
            'price' => 12.50,
            'stock' => 100,
            'image_url' => $file,
        ];

        $response = $this->postJson('/api/pet_products', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'name' => 'Dog Toy',
                     'description' => 'A durable toy for dogs.',
                     'price' => '12.50',
                     'stock' => 100,
                 ]);

        $this->assertDatabaseHas('pet_products', [
            'name' => 'Dog Toy',
        ]);

        Storage::disk('public')->assertExists('product_images/' . $file->hashName());
    }

    /**
     * Test that creating a pet product fails with invalid data.
     */
    public function test_it_returns_validation_errors_when_creating_with_invalid_data()
    {
        $data = [
            'name' => '', // Name is required
            'price' => 'invalid-price', // Price must be numeric
            'stock' => -1, // Stock must be positive
        ];

        $response = $this->postJson('/api/pet_products', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'price', 'stock']);
    }

    /**
     * Test that we can show a specific pet product.
     */
    public function test_it_can_show_a_specific_pet_product()
    {
        $petProduct = PetProduct::factory()->create();

        $response = $this->getJson('/api/pet_products/' . $petProduct->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $petProduct->id,
                     'name' => $petProduct->name,
                 ]);
    }

    /**
     * Test that a 404 is returned if the pet product is not found.
     */
    public function test_it_returns_404_if_pet_product_is_not_found()
    {
        $response = $this->getJson('/api/pet_products/999');

        $response->assertStatus(404)
                 ->assertJson(['error' => 'Pet product not found']);
    }

    /**
     * Test that we can update a pet product.
     */
    public function test_it_can_update_a_pet_product()
    {
        Storage::fake('public');
        $oldFile = UploadedFile::fake()->image('old_image.jpg')->store('product_images', 'public');

        $petProduct = PetProduct::factory()->create(['image_url' => Storage::url($oldFile)]);

        $newFile = UploadedFile::fake()->image('new_image.png');

        $data = [
            'name' => 'Updated Dog Food',
            'price' => 25.99,
            'image_url' => $newFile,
        ];

        $response = $this->putJson('/api/pet_products/' . $petProduct->id, $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => 'Pet product updated successfully.',
                     'pet_product' => [
                         'name' => 'Updated Dog Food',
                         'price' => '25.99',
                     ],
                 ]);

        $this->assertDatabaseHas('pet_products', [
            'id' => $petProduct->id,
            'name' => 'Updated Dog Food',
        ]);

        Storage::disk('public')->assertExists('product_images/' . $newFile->hashName());
        Storage::disk('public')->assertMissing(str_replace('storage/', '', Storage::url($oldFile)));
    }

    /**
     * Test that we can delete a pet product.
     */
    public function test_it_can_delete_a_pet_product()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('delete_me.jpg')->store('product_images', 'public');

        $petProduct = PetProduct::factory()->create(['image_url' => Storage::url($file)]);

        $response = $this->deleteJson('/api/pet_products/' . $petProduct->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('pet_products', [
            'id' => $petProduct->id,
        ]);

        Storage::disk('public')->assertMissing(str_replace('storage/', '', Storage::url($file)));
    }
}
