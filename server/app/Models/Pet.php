<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;
    public const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'species',
        'breed',
        'dob',
        'gender',
        'weight',
        'height',
        'image_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dob' => 'date:Y-m-d',
            'weight' => 'integer',
            'height' => 'integer',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function petMarkets()
    {
        return $this->hasMany(PetMarket::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reportLostPet()
    {
        return $this->hasOne(ReportLostPet::class);
    }

    public function medicalLogs(): BelongsToMany
    {
        return $this->belongsToMany(MedicalLog::class, 'pet_medicals', 'pet_id', 'medical_id');
    }

    public function emergencyShelters(): HasMany
    {
        return $this->hasMany(EmergencyShelter::class, 'pet_id', 'id');
    }
}
