<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Allergy;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    public function index()
    {
        $allergies = Allergy::all();
        return view('employee.allergy.index', compact('allergies'));
    }

    public function create()
    {
        return view('employee.allergy.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:25|unique:allergies,name',
            'description' => 'required|string|max:50',
            'risk' => 'required|string|max:100',
        ]);

        Allergy::create($request->all());

        return redirect()->route('employee.allergy.index')->with('success', 'Allergy created.');
    }

    public function show(Allergy $allergy)
    {
        return view('employee.allergy.show', compact('allergy'));
    }

    public function edit(Allergy $allergy)
    {
        return view('employee.allergy.edit', compact('allergy'));
    }

    public function update(Request $request, Allergy $allergy)
    {
        $request->validate([
             'name' => 'required|string|max:25|unique:allergies,name,' . $allergy->id,
            'description' => 'required|string|max:50',
            'risk' => 'required|string|max:100',
        ]);

        $allergy->update($request->all());

        return redirect()->route('employee.allergy.index')->with('success', 'Allergy updated.');
    }

    public function destroy(Allergy $allergy)
    {
        $allergy->delete();
        return redirect()->route('employee.allergy.index')->with('success', 'Allergie succesvol verwijderd.');
    }
}
