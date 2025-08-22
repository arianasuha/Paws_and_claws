<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
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
        'reviewer',
        'reviewee',
        'rating',
        'review_text',
        'review_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'review_date' => 'datetime',
    ];

    /**
     * Get the reviewer associated with the review.
     */
    public function reviewerUser()
    {
        return $this->belongsTo(User::class, 'reviewer');
    }

    /**
     * Get the user being reviewed.
     */
    public function revieweeUser()
    {
        return $this->belongsTo(User::class, 'reviewee');
    }
}