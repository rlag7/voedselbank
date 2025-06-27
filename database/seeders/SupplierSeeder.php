<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $types = ['supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'];
        $products = Product::all();

        if ($products->count() === 0) {
            $this->command->warn('⚠️ Geen producten gevonden. Seed eerst de producten.');
            return;
        }

        for ($i = 0; $i < 6; $i++) {
            $supplier = Supplier::create([
                'company_name'    => $faker->company,
                'address'         => $faker->address,
                'contact_name'    => $faker->name,
                'contact_email'   => $faker->unique()->safeEmail,
                'phone'           => $faker->optional()->phoneNumber,
                'supplier_type'   => $faker->randomElement($types),
                'supplier_number' => strtoupper('SUP-' . $faker->unique()->numerify('####')),
                'is_active'       => $faker->boolean(80),
            ]);

            $assignedProducts = $products->random(rand(1, 4));

            foreach ($assignedProducts as $product) {
                $supplier->products()->attach($product->id, [
                    'stock_quantity'     => $faker->numberBetween(5, 100),
                    'last_delivery_date' => $faker->dateTimeBetween('-30 days', 'now'),
                ]);
            }
        }
    }
}
