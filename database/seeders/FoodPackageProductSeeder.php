<?php

namespace Database\Seeders;

use App\Models\FoodPackage;
use App\Models\Product;
use Illuminate\Database\Seeder;

class FoodPackageProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        FoodPackage::all()->each(function ($package) use ($products) {
            $package->products()->attach(
                $products->random(rand(1, 4))->pluck('id')->mapWithKeys(function ($id) {
                    return [$id => ['quantity' => rand(1, 5)]];
                })->toArray()
            );
        });
    }
}
