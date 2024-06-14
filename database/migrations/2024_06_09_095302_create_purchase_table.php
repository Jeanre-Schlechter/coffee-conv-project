<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cart_id');
            $table->float('total_amount');
            $table->boolean('is_paid');
            $table->enum('payment_status', ['created', 'pending_payment', 'payment_success', 'payment_failure']);
            $table->string('shipping_address');
            $table->string('shipping_information');
            $table->enum('shipping_status', ['waiting_approval', 'packing', 'in_transit', 'en_route', 'delivered', 'undelivered']);
            $table->string('reason_for_no_delivery');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cart_id')->references('id')->on('carts');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
