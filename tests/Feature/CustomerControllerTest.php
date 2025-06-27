<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function employee_can_create_customer()
    {
        // Zorg ervoor dat de 'employee' rol bestaat
        Role::create(['name' => 'employee']);

        // Arrange: een gebruiker met de rol "employee" via Spatie
        $employee = User::factory()->create();
        $employee->assignRole('employee');

        $person = Person::factory()->create();

        // Act: inloggen als medewerker en klant aanmaken
        $response = $this->actingAs($employee)->post(route('employee.customers.store'), [
            'person_id' => $person->id,
            'number_of_adults' => 2,
            'number_of_children' => 1,
            'number_of_babies' => 0,
            'is_vegan' => true,
            'is_vegetarian' => false,
            'no_pork' => true,
        ]);

        // Assert: redirect en database check
        $response->assertRedirect(route('employee.customers.index'));
        $this->assertDatabaseHas('customers', [
            'person_id' => $person->id,
            'number_of_adults' => 2,
            'number_of_children' => 1,
            'is_vegan' => true,
            'is_vegetarian' => false,
            'no_pork' => true,
        ]);
    }
}
