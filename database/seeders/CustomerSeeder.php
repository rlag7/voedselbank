<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Person;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Person::skip(5)->take(5)->get()->each(function ($person) {
            Customer::create([
                'person_id' => $person->id,
                'number_of_adults' => rand(1, 3),
                'number_of_children' => rand(0, 4),
                'number_of_babies' => rand(0, 2),
                'is_vegan' => rand(0, 1),
                'is_vegetarian' => rand(0, 1),
                'no_pork' => rand(0, 1),
                'active' => rand(0, 1),
            ]);
        });
    }
}

