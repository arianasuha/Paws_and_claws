<?php

namespace Tests\Unit\Vet;

use App\Models\User;
use App\Models\Vet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class VetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_vet()
    {
        $vet = Vet::factory()->create([
            'clinic_name' => 'Happy Paws Clinic',
            'specialization' => 'Surgery',
            'services_offered' => 'Surgical procedures and emergency care',
            'working_hour' => 'Mon-Fri 9AM-5PM',
        ]);

        $this->assertInstanceOf(Vet::class, $vet);
        $this->assertEquals('Happy Paws Clinic', $vet->clinic_name);
        $this->assertEquals('Surgery', $vet->specialization);
        $this->assertEquals('Surgical procedures and emergency care', $vet->services_offered);
        $this->assertEquals('Mon-Fri 9AM-5PM', $vet->working_hour);
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $vet = new Vet();
        $fillable = [
            'user_id',
            'clinic_name',
            'specialization',
            'services_offered',
            'working_hour',
        ];

        $this->assertEquals($fillable, $vet->getFillable());
    }

    #[Test]
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create(['is_vet' => true]);
        $vet = Vet::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $vet->user);
        $this->assertEquals($user->id, $vet->user->id);
        $this->assertTrue($vet->user->is_vet);
    }

    #[Test]
    public function it_has_no_timestamps()
    {
        $vet = Vet::factory()->create();

        $this->assertNull($vet->getCreatedAtColumn());
        $this->assertNull($vet->getUpdatedAtColumn());
        $this->assertNull($vet->created_at);
        $this->assertNull($vet->updated_at);
    }

    #[Test]
    public function it_uses_factory_with_custom_specialization()
    {
        $vet = Vet::factory()->withSpecialization('Dentistry')->create([
            'clinic_name' => 'Smile Vets',
        ]);

        $this->assertEquals('Dentistry', $vet->specialization);
        $this->assertEquals('Smile Vets', $vet->clinic_name);
        $this->assertTrue($vet->user->is_vet);
    }

    #[Test]
    public function it_enforces_unique_user_id()
    {
        $user = User::factory()->create(['is_vet' => true]);
        Vet::factory()->create(['user_id' => $user->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->expectExceptionMessageMatches('/UNIQUE constraint failed: vets.user_id/');

        Vet::factory()->create(['user_id' => $user->id]);
    }
}