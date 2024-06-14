<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    protected $table = 'products_cart';

    protected $fillable = ['product_quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
