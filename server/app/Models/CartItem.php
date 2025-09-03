<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    public const CREATED_AT = null;
    public const UPDATED_AT = null;
    
    protected $fillable = [
        'cart_id',
        'food_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

     public function product()
    {
        return $this->belongsTo(PetProduct::class, 'product_id');
    }
}