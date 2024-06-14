<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Machines',
                'description' => 'Category for machines',
                'logo' => 'machines_logo.png'
            ],
            [
                'name' => 'Coffee',
                'description' => 'Category for coffees',
                'logo' => 'coffee_logo.png'
            ],
            [
                'name' => 'Accessories',
                'description' => 'Category for accessories',
                'logo' => 'accessories_logo.png'
            ],
            [
                'name' => 'Cleaning Supplies',
                'description' => 'Category for cleaning supplies',
                'logo' => 'cleaning_supplies_logo.png'
            ],
        ];

        // Insert categories into the database
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
