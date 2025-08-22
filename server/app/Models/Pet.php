<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function diseases()
    {
        return $this->belongsToMany(DiseaseLog::class, 'pet_diseases', 'pet_id', 'disease_id');
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

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function medicalLogs()
    {
        return $this->belongsToMany(MedicalLog::class, 'pet_medicals');
    }
}
