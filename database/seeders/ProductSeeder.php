<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate 20 random products
        for ($i = 0; $i < 20; $i++) {
            DB::table('products')->insert([
                'name' => 'Product ' . ($i + 1),
                'description' => 'Description of Product ' . ($i + 1),
                'price' => rand(10, 100), // Random price between 10 and 100
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
