<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
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
        'order_id',
        'product_id',
        'quantity',
    ];

    /**
     * Get the order that the item belongs to.
     */
    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class, 'order_id', 'order_id');
    }

    /**
     * Get the product that the order item is for.
     */
    public function petProduct(): BelongsTo
    {
        return $this->belongsTo(PetProduct::class, 'product_id', 'product_id');
    }
}
