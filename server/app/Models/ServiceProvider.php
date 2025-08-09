<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceProvider extends Model
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
        'service_type',
        'service_desc',
        'rate_per_hour',
        'rating',
    ];

    /**
     * Get the user that owns the service provider account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
