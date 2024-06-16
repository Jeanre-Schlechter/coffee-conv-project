<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConvertMainImageToBlob extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the existing column (if any)
            $table->dropColumn('main_image');


        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {

            // Recreate the original column if needed
            $table->string('main_image')->nullable(); // Or adjust the type based on your needs
        });
    }
}
