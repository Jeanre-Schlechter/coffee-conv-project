<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductCart extends Pivot
{
    protected $table = 'products_cart';

    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'product_id', 'product_quantity'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
