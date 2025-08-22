<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetDisease extends Model
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
        'disease_id',
        'pet_id',
    ];

    /**
     * Get the disease log associated with the pet disease record.
     * * @return BelongsTo
     */
    public function disease(): BelongsTo
    {
        return $this->belongsTo(DiseaseLog::class, 'disease_id');
    }

    /**
     * Get the pet that has the disease record.
     * * @return BelongsTo
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
}
