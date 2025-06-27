<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (ProductCategory::all() as $category) {
            for ($i = 0; $i < 3; $i++) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $faker->unique()->words(2, true),
                    'ean' => $faker->unique()->ean13(),
                    'stock_quantity' => $faker->numberBetween(10, 100),
                ]);
            }
        }
    }
}
