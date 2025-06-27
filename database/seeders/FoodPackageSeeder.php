<?php

namespace Database\Seeders;

use App\Models\FoodPackage;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FoodPackageSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        Customer::all()->each(function ($customer) use ($faker) {
            FoodPackage::create([
                'customer_id' => $customer->id,
                'composition_date' => $faker->date(),
                'distribution_date' => $faker->optional()->date(),
            ]);
        });
    }
}
