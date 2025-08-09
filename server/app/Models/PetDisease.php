<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
