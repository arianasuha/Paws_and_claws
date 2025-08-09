<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
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
        'med_name',
        'dosage',
        'start_date',
        'end_date',
        'reminder_time',
    ];

    /**
     * Get the pet that the reminder is for.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
