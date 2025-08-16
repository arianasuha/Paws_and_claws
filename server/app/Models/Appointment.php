<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
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
        'pet_id',
        'vet_id',
        'app_date',
        'app_time',
        'visit_reason',
        'status',
    ];

    /**
     * Get the pet that the appointment belongs to.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Get the veterinarian that the appointment belongs to.
     */
    public function vet(): BelongsTo
    {
        return $this->belongsTo(Vet::class);
    }

    public function medicalLog()
    {
        return $this->hasOne(MedicalLog::class, 'app_id');
    }
}
