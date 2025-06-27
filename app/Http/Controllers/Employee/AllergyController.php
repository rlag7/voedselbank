<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Allergy;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    // Toon een lijst van alle allergieën
    public function index()
    {
        $allergies = Allergy::all();
        return view('employee.allergy.index', compact('allergies'));
    }

    // Toon het formulier om een nieuwe allergie toe te voegen
    public function create()
    {
        return view('employee.allergy.create');
    }

    // Sla een nieuwe allergie op in de database
    public function store(Request $request)
    {
        // Valideer invoer
        $request->validate([
            'name' => 'required|string|max:25|unique:allergies,name',
            'description' => 'required|string|max:50',
            'risk' => 'required|string|max:100',
        ]);

        // Maak de allergie aan
        Allergy::create($request->all());

        // Redirect met succesmelding
        return redirect()->route('employee.allergy.index')
            ->with('success', 'Allergie succesvol aangemaakt.');
    }

    // Toon details van één allergie
    public function show(Allergy $allergy)
    {
        return view('employee.allergy.show', compact('allergy'));
    }

    // Toon het formulier om een allergie te bewerken
    public function edit(Allergy $allergy)
    {
        return view('employee.allergy.edit', compact('allergy'));
    }

    // Werk een bestaande allergie bij
    public function update(Request $request, Allergy $allergy)
    {
        // Valideer invoer
        $request->validate([
            'name' => 'required|string|max:25|unique:allergies,name,' . $allergy->id,
            'description' => 'required|string|max:50',
            'risk' => 'required|string|max:100',
        ]);

        // Update de allergie
        $allergy->update($request->all());

        // Redirect met succesmelding
        return redirect()->route('employee.allergy.index')
            ->with('success', 'Allergie succesvol bijgewerkt.');
    }

    // Verwijder een allergie als deze niet actief is
    public function destroy(Allergy $allergy)
    {
        // Controleer of de allergie actief is
        if ($allergy->is_actief) {
            return redirect()->route('employee.allergy.index')
                ->with('error', 'Deze allergie is actief en kan niet worden verwijderd.');
        }

        // Verwijder de allergie
        $allergy->delete();

        // Redirect met succesmelding
        return redirect()->route('employee.allergy.index')
            ->with('success', 'Allergie succesvol verwijderd.');
    }

    // Wissel de status van actief/inactief om
    public function toggle(Allergy $allergy)
    {
        // Zet actief op het tegenovergestelde
        $allergy->update(['is_actief' => !$allergy->is_actief]);

        // Redirect met melding
        return redirect()->route('employee.allergy.index')
            ->with('success', 'Allergiestatus succesvol gewijzigd.');
    }
}
