<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $producten = [
            'Aardappelen, groente, fruit' => ['Appels', 'Bananen', 'Tomaten', 'Komkommer', 'Aardappelen'],
            'Kaas, vleeswaren' => ['Goudse kaas', 'Brie', 'Salami', 'Kipfilet', 'Ham'],
            'Zuivel, plantaardig en eieren' => ['Melk', 'Sojamelk', 'Havermelk', 'Eieren', 'Yoghurt'],
            'Bakkerij en banket' => ['Witbrood', 'Volkorenbrood', 'Croissant', 'Cake', 'Stroopwafels'],
            'Frisdrank, sappen, koffie en thee' => ['Cola', 'Sinaasappelsap', 'Koffie', 'Thee', 'Spa Blauw'],
            'Pasta, rijst en wereldkeuken' => ['Spaghetti', 'Macaroni', 'Rijst', 'Wraps', 'Couscous'],
            'Soepen, sauzen, kruiden en olie' => ['Tomatensoep', 'Pindasaus', 'Oregano', 'Zonnebloemolie', 'Bouillonblokjes'],
            'Snoep, koek, chips en chocolade' => ['Chips', 'Mars', 'Drop', 'Oreo', 'Chocoladereep'],
            'Baby, verzorging en hygiëne' => ['Luiers', 'Babydoekjes', 'Shampoo', 'Tandpasta', 'Zeep'],
        ];

        foreach ($producten as $categorieNaam => $items) {
            $category = ProductCategory::where('name', $categorieNaam)->first();

            if (!$category) {
                echo "⚠️ Categorie '$categorieNaam' niet gevonden. Sla over...\n";
                continue;
            }

            foreach ($items as $productNaam) {
                Product::create([
                    'category_id'        => $category->id,
                    'name'               => $productNaam,
                    'ean'                => $faker->unique()->ean13(),
                    'stock_quantity'     => $faker->numberBetween(10, 100),
                    'houdbaarheiddatum'  => $faker->optional()->dateTimeBetween('now', '+6 months'),
                    'omschrijving'       => $faker->sentence(6),
                    'status'             => 'actief',
                    'isactief'           => true,
                    'opmerking'          => $faker->optional()->sentence(),
                    'soortalergie'       => $faker->optional()->randomElement(['gluten', 'noten', 'geen']),
                ]);
            }
        }
    }
}
