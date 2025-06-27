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
        $customers = Customer::with('person')->get();
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
            'person_id' => 'required|exists:people,id',
            'number_of_adults' => 'required|integer|min:0',
            'number_of_children' => 'required|integer|min:0',
            'number_of_babies' => 'required|integer|min:0',
            'is_vegan' => 'required|boolean',
            'is_vegetarian' => 'required|boolean',
            'no_pork' => 'required|boolean',
        ]);

        Customer::create($request->all());

        return redirect()->route('employee.customers.index')->with('success', 'Customer created.');
    }

    public function show(Customer $customer)
    {
        return view('employee.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $people = Person::all();
        return view('employee.customers.edit', compact('customer', 'people'));
    }

    public function update(Request $request, Customer $customer)
    {
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

        return redirect()->route('employee.customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('employee.customers.index')->with('success', 'Customer deleted.');
    }
}
