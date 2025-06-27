<?php

namespace Tests\Feature;

use App\Models\Allergy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllergyPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function allergies_are_displayed_on_the_allergy_page()
    {
        // Gegeven: Ik maak enkele allergieën aan
        $allergy1 = Allergy::create([
            'name' => 'Peanuts',
            'description' => 'Reacts to peanuts',
            'risk' => 'Severe anaphylaxis'
        ]);

        $allergy2 = Allergy::create([
            'name' => 'Gluten',
            'description' => 'Intolerant to gluten',
            'risk' => 'Digestive issues'
        ]);

        // Wanneer: De gebruiker de allergieënpagina bezoekt
        $response = $this->get(route('employee.allergy.index'));

        // Dan: Moet ik een lijst van allergieën zien
        $response->assertStatus(200); // Controleer of de status 200 (OK) is
        $response->assertSee($allergy1->name); // Controleer of de naam van de eerste allergie wordt weergegeven
        $response->assertSee($allergy2->name); // Controleer of de naam van de tweede allergie wordt weergegeven
        $response->assertSee($allergy1->description); // Controleer of de beschrijving van de eerste allergie wordt weergegeven
        $response->assertSee($allergy2->description); // Controleer of de beschrijving van de tweede allergie wordt weergegeven
    }
}
