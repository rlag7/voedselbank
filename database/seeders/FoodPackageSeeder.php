<?php

namespace Database\Seeders;

use App\Models\FoodPackage;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class FoodPackageSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        Customer::all()->each(function ($customer) use ($faker) {
            // Zorg dat composition_date vandaag of later is
            $compositionDate = Carbon::today()->addDays(rand(0, 10));

            // distribution_date is optioneel, maar als hij er is moet hij gelijk of na composition_date zijn
            $distributionDate = $faker->boolean(70) // 70% kans op een distributiedatum
                ? $compositionDate->copy()->addDays(rand(0, 5))
                : null;

            FoodPackage::create([
                'customer_id'        => $customer->id,
                'composition_date'   => $compositionDate->toDateString(),
                'distribution_date'  => $distributionDate ? $distributionDate->toDateString() : null,
                'is_active'          => $faker->boolean(), // optioneel: zet deze ook erbij als je het veld hebt
            ]);
        });
    }
}
