<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWishlist extends Model
{
    protected $table = 'products_wishlist';

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class);
    }
}
