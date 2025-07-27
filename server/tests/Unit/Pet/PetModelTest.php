<?php

namespace Tests\Unit\Pet;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_pet()
    {
        $pet = Pet::factory()->create([
            'name' => 'Buddy',
            'species' => 'Dog',
            'breed' => 'Golden Retriever',
            'dob' => '2020-01-01',
            'gender' => 'Male',
            'weight' => 30,
            'height' => 60,
            'image_url' => 'https://example.com/buddy.jpg',
        ]);

        $this->assertInstanceOf(Pet::class, $pet);
        $this->assertEquals('Buddy', $pet->name);
        $this->assertEquals('Dog', $pet->species);
        $this->assertEquals('Golden Retriever', $pet->breed);
        $this->assertEquals('2020-01-01', $pet->dob->format('Y-m-d'));
        $this->assertEquals('Male', $pet->gender);
        $this->assertEquals(30, $pet->weight);
        $this->assertEquals(60, $pet->height);
        $this->assertEquals('https://example.com/buddy.jpg', $pet->image_url);
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $pet = new Pet();
        $fillable = [
            'user_id', // Changed from 'owner_id' to 'user_id'
            'name',
            'species',
            'breed',
            'dob',
            'gender',
            'weight',
            'height',
            'image_url',
        ];

        $this->assertEquals($fillable, $pet->getFillable());
    }

    #[Test]
    public function it_casts_attributes_correctly()
    {
        $pet = Pet::factory()->create([
            'dob' => '2020-01-01',
            'weight' => 30,
            'height' => 60,
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $pet->dob);
        $this->assertIsInt($pet->weight);
        $this->assertIsInt($pet->height);
    }

    #[Test]
    public function it_belongs_to_an_owner()
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $user->id]); // Changed from 'owner_id' to 'user_id'

        $this->assertInstanceOf(User::class, $pet->owner);
        $this->assertEquals($user->id, $pet->owner->id);
    }

    #[Test]
    public function it_has_no_timestamps()
    {
        $pet = new Pet();

        $this->assertNull($pet->getUpdatedAtColumn());
        $this->assertNull($pet->getCreatedAtColumn());
    }

    #[Test]
    public function it_can_handle_nullable_fields()
    {
        $pet = Pet::factory()->create([
            'weight' => null,
            'height' => null,
            'image_url' => null,
        ]);

        $this->assertNull($pet->weight);
        $this->assertNull($pet->height);
        $this->assertNull($pet->image_url);
    }
}