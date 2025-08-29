<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyShelter extends Model
{
    use HasFactory;
    public const UPDATED_AT = null;
    public const CREATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emergency_pet_shelter';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pet_id',
        'user_id',
        'request_date',
    ];


    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
