<?php

namespace Database\Seeders;

use App\Models\Allergy;
use Illuminate\Database\Seeder;

class AllergySeeder extends Seeder
{
    public function run(): void
    {
        $allergies = [
            ['name' => 'Peanuts', 'description' => 'Reacts to peanuts', 'risk' => 'Severe anaphylaxis'],
            ['name' => 'Gluten', 'description' => 'Intolerant to gluten', 'risk' => 'Digestive issues'],
            ['name' => 'Shellfish', 'description' => 'Seafood allergy', 'risk' => 'Swelling and breathing issues'],
            ['name' => 'Lactose', 'description' => 'Cannot digest lactose', 'risk' => 'Stomach pain'],
        ];

        foreach ($allergies as $a) {
            Allergy::create($a);
        }
    }
}
