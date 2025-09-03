<?php

// File: app/Models/MedicalLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalLog extends Model
{
    use HasFactory;
    // The timestamps are now handled by the migration file, so these constants should be removed.
    // public const UPDATED_AT = null;
    // public const CREATED_AT = null;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visit_date',
        'vet_name',
        'clinic_name',
        'reason_for_visit',
        'diagnosis',
        'treatment_prescribed',
        'notes',
        'attachment_url',
    ];

    /**
     * Get the pets associated with this medical log.
     */
    public function pets()
    {
        return $this->belongsToMany(
            Pet::class,
            'pet_medicals',
            'medical_id',
            'pet_id'
        );
    }
}
