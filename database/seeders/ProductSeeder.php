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
        $allergies = ['gluten', 'pinda\'s', 'lactose', 'schaaldieren', 'noten', 'geen'];

        foreach (ProductCategory::all() as $category) {
            for ($i = 0; $i < 5; $i++) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => ucfirst($faker->unique()->words(2, true)),
                    'ean' => $faker->unique()->ean13(),
                    'stock_quantity' => $faker->numberBetween(5, 200),
                    'houdbaarheiddatum' => $faker->optional()->dateTimeBetween('now', '+6 months'),
                    'omschrijving' => $faker->sentence(6),
                    'status' => $faker->randomElement(['actief', 'inactief']),
                    'isactief' => $faker->boolean(80),
                    'opmerking' => $faker->optional(0.4)->sentence(),
                    'soortalergie' => $faker->randomElement($allergies),
                ]);
            }
        }
    }
}
