<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseLog extends Model
{
    use HasFactory;
    public const UPDATED_AT = null;
    public const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'symptoms',
        'causes',
        'treat_options',
        'severity',
    ];

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'pet_diseases', 'disease_id', 'pet_id');
    }
}
