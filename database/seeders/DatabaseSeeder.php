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
            CustomerSeeder::class,
            AddressSeeder::class,
            SupplierSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            AllergySeeder::class,
            CustomerAllergySeeder::class,
            FoodPackageSeeder::class,
            FoodPackageProductSeeder::class,
        ]);
    }
}
