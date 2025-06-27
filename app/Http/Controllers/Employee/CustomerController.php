<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Person;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['person', 'allergies'])->get();
        return view('employee.customers.index', compact('customers'));
    }

    public function create()
    {
        $people = Person::all();
        return view('employee.customers.create', compact('people'));
    }

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

        Customer::create($request->all());

        return redirect()->route('employee.customers.index')
            ->with('success', 'Klant succesvol toegevoegd.');
    }

    public function show(Customer $customer)
    {
        return view('employee.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        if (!$customer->is_active) {
            return redirect()->route('employee.customers.index')
                ->with('error', 'Deze klant is gedeactiveerd en kan niet worden aangepast.');
        }

        $people = Person::all();
        return view('employee.customers.edit', compact('customer', 'people'));
    }

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
            'is_vegan' => 'required|boolean',
            'is_vegetarian' => 'required|boolean',
            'no_pork' => 'required|boolean',
        ]);

        $customer->update($request->all());

        return redirect()->route('employee.customers.index')->with('success', 'Klantgegevens succesvol bijgewerkt.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->is_active) {
            return redirect()->route('employee.customers.index')
                ->with('error', 'Actieve klantaccounts kunnen niet worden verwijderd.');
        }

        $customer->delete();

        return redirect()->route('employee.customers.index')
            ->with('success', 'Klant succesvol verwijderd.');
    }


    public function toggleActive(Customer $customer)
    {
        $customer->is_active = !$customer->is_active;
        $customer->save();

        return redirect()->route('employee.customers.index')
            ->with('success', 'Klantstatus succesvol aangepast.');
    }
}
