<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetMarket extends Model
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
        'user_id',
        'pet_id',
        'date',
        'type',
        'status',
        'description',
        'fee',
    ];

    /**
     * Get the user that owns the pet market entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pet that is part of the market entry.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
