<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Canned Goods', 'Fresh Produce', 'Bakery', 'Dairy', 'Frozen Foods'];

        foreach ($categories as $name) {
            ProductCategory::create(['name' => $name]);
        }
    }
}

