<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PersonSeeder::class,
            UserSeeder::class,
            AddressSeeder::class,

            ProductCategorySeeder::class, // categorieën vóór producten
            ProductSeeder::class,         // producten vóór leveranciers
            SupplierSeeder::class,        // leveranciers kunnen producten krijgen

            AllergySeeder::class,

            CustomerSeeder::class,
            CustomerAllergySeeder::class,

            FoodPackageSeeder::class,
            FoodPackageProductSeeder::class,
        ]);

    }
}
