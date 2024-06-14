<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';

    protected $fillable = ['user_id', 'total_amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, ProductsCart::class, 
            'cart_id', 
            'id', 
            'id', 
            'product_id'
        );
    }
}
