<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Alleen geldige ENUM-waarden (uit de migration)
        $types = ['supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'];

        for ($i = 0; $i < 10; $i++) {
            Supplier::create([
                'company_name'    => $faker->company,
                'address'         => $faker->address,
                'contact_name'    => $faker->name,
                'contact_email'   => $faker->unique()->safeEmail,
                'phone'           => $faker->optional()->phoneNumber,
                'supplier_type'   => $faker->randomElement($types),
                'supplier_number' => strtoupper('SUP-' . $faker->unique()->numerify('####')),
            ]);
        }
    }
}
