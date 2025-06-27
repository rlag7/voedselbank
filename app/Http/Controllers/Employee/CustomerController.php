<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Toon overzicht van alle klanten
     */
    public function index()
    {
        $customers = Customer::with(['person', 'allergies'])->get();
        return view('employee.customers.index', compact('customers'));
    }

    /**
     * Toon formulier om klant toe te voegen
     */
    public function create()
    {
        $people = Person::all();
        return view('employee.customers.create', compact('people'));
    }

    /**
     * Sla nieuwe klant op
     */
    public function store(Request $request)
    {
        $request->validate([
            'person_id' => [
                'required',
                'exists:people,id',
                function ($attribute, $value, $fail) {
                    if (Customer::where('person_id', $value)->exists()) {
                        $fail('Dit e-mailadres is al gekoppeld aan een bestaande klant.');
                    }
                }
            ],
            'number_of_adults' => 'required|integer|min:0',
            'number_of_children' => 'required|integer|min:0',
            'number_of_babies' => 'required|integer|min:0',
            'is_vegan' => 'required|boolean',
            'is_vegetarian' => 'required|boolean',
            'no_pork' => 'required|boolean',
        ]);

        try {
            Customer::create($request->all());
            return redirect()->route('employee.customers.index')->with('success', 'Klant succesvol toegevoegd.');
        } catch (\Exception $e) {
            Log::error('Fout bij toevoegen klant: ' . $e->getMessage());
            return back()->with('error', 'Er ging iets mis bij het toevoegen van de klant.');
        }
    }

    /**
     * Toon details van een klant
     */
    public function show(Customer $customer)
    {
        return view('employee.customers.show', compact('customer'));
    }

    /**
     * Toon het edit-formulier
     */
    public function edit(Customer $customer)
    {
        if (!$customer->is_active) {
            return redirect()->route('employee.customers.index')
                ->with('error', 'Deze klant is gedeactiveerd en kan niet worden aangepast.');
        }

        $people = Person::all();
        return view('employee.customers.edit', compact('customer', 'people'));
    }

    /**
     * Update een klant
     */
    public function update(Request $request, Customer $customer)
    {
        if (!$customer->is_active) {
            return redirect()->route('employee.customers.index')
                ->with('error', 'Deze klant is gedeactiveerd en kan niet worden aangepast.');
        }

        $request->validate([
            'person_id' => 'required|exists:people,id',
            'number_of_adults' => 'required|integer|min:0',
            'number_of_children' => 'required|integer|min:0',
            'number_of_babies' => 'required|integer|min:0',
            'is_vegan' => 'boolean',
            'is_vegetarian' => 'boolean',
            'no_pork' => 'boolean',
        ]);

        try {
            $customer->update([
                ...$request->all(),
                'is_vegan' => $request->boolean('is_vegan'),
                'is_vegetarian' => $request->boolean('is_vegetarian'),
                'no_pork' => $request->boolean('no_pork'),
            ]);
            return redirect()->route('employee.customers.index')->with('success', 'Klantgegevens succesvol bijgewerkt.');
        } catch (\Exception $e) {
            Log::error('Fout bij bijwerken klant: ' . $e->getMessage());
            return back()->with('error', 'Er ging iets mis bij het bijwerken van de klant.');
        }
    }

    /**
     * Verwijder een klant
     */
    public function destroy(Customer $customer)
    {
        if ($customer->is_active) {
            return redirect()->route('employee.customers.index')
                ->with('error', 'Actieve klantaccounts kunnen niet worden verwijderd.');
        }

        try {
            $customer->delete();
            return redirect()->route('employee.customers.index')->with('success', 'Klant succesvol verwijderd.');
        } catch (\Exception $e) {
            Log::error('Fout bij verwijderen klant: ' . $e->getMessage());
            return back()->with('error', 'Fout bij verwijderen van klant.');
        }
    }

    /**
     * Toggle de actieve status
     */
    public function toggleActive(Customer $customer)
    {
        try {
            $customer->is_active = !$customer->is_active;
            $customer->save();
            return redirect()->route('employee.customers.index')->with('success', 'Klantstatus succesvol aangepast.');
        } catch (\Exception $e) {
            Log::error('Toggle status fout: ' . $e->getMessage());
            return back()->with('error', 'Klantstatus kon niet worden gewijzigd.');
        }
    }

    /**
     * (Optioneel) Voorbeeld stored procedure aanroepen
     */
    public function storedProcedureExample()
    {
        try {
            $result = DB::select('CALL GetActiveCustomers()');
            return view('employee.customers.index', ['customers' => $result]);
        } catch (\Exception $e) {
            Log::error('Stored procedure fout: ' . $e->getMessage());
            return back()->with('error', 'Kon gegevens niet ophalen.');
        }
    }
}
