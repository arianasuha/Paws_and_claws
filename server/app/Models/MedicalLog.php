<?php

// File: app/Models/MedicalLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalLog extends Model
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
        'app_id',
        'treat_pres',
        'diagnosis',
    ];

    /**
     * Get the app that the medical log belongs to.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'app_id');
    }

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'pet_medicals');
    }
}
