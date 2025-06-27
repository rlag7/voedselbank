<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        Customer::all()->each(function ($customer) use ($faker) {
            Address::create([
                'customer_id' => $customer->id,
                'street' => $faker->streetName,
                'house_number' => $faker->numberBetween(1, 300),
                'postal_code' => $faker->postcode,
                'city' => $faker->city,
            ]);
        });
    }
}
