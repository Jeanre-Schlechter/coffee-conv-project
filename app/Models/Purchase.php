<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';

    protected $fillable = ['user_id', 'cart_id', 'total_amount', 'is_paid', 'payment_status', 'shipping_address', 'shipping_information', 'shipping_status', 'reason_for_no_delivery'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
