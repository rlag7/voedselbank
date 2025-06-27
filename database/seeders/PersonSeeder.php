<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Person::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->optional()->phoneNumber,
            ]);
        }
    }
}

