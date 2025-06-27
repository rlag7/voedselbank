<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\FoodPackage;
use App\Models\Customer;
use Illuminate\Http\Request;

class FoodPackageController extends Controller
{
    public function index()
    {
        $packages = FoodPackage::with('customer')->paginate(5);
        return view('employee.food_packages.index', compact('packages'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('employee.food_packages.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        FoodPackage::create($validated);

        return redirect()->route('employee.food_packages.index')->with('success', 'Pakket aangemaakt.');
    }


    public function show(FoodPackage $foodPackage)
    {
        return view('employee.food_packages.show', compact('foodPackage'));
    }

    public function edit(FoodPackage $foodPackage)
    {
        $customers = Customer::all();
        return view('employee.food_packages.edit', compact('foodPackage', 'customers'));
    }

    public function update(Request $request, FoodPackage $foodPackage)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'composition_date' => ['required', 'date', 'after_or_equal:today'],
            'distribution_date' => ['nullable', 'date', 'after_or_equal:composition_date'],
        ], [
            'customer_id.required' => 'Selecteer een klant.',
            'customer_id.exists' => 'De geselecteerde klant is ongeldig.',
            'composition_date.required' => 'De samenstel datum is verplicht.',
            'composition_date.date' => 'Voer een geldige samenstel datum in.',
            'composition_date.after_or_equal' => 'De samenstel datum mag niet in het verleden liggen.',
            'distribution_date.date' => 'Voer een geldige distributiedatum in.',
            'distribution_date.after_or_equal' => 'De distributiedatum mag niet vóór de samenstel datum liggen.',
        ]);

        $data = $request->only(['customer_id', 'composition_date', 'distribution_date']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $foodPackage->update($data);

        return redirect()->route('employee.food_packages.index')->with('success', 'Package updated.');
    }

    public function destroy(FoodPackage $foodPackage)
    {
        $foodPackage->delete();
        return redirect()->route('employee.food_packages.index')->with('success', 'Package deleted.');
    }

    private function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'composition_date' => ['required', 'date', 'after_or_equal:today'],
            'distribution_date' => ['nullable', 'date', 'after_or_equal:composition_date'],
        ];
    }

    private function messages(): array
    {
        return [
            'customer_id.required' => 'Selecteer een klant.',
            'customer_id.exists' => 'De geselecteerde klant is ongeldig.',
            'composition_date.required' => 'De samenstel datum is verplicht.',
            'composition_date.date' => 'Voer een geldige samenstel datum in.',
            'composition_date.after_or_equal' => 'De samenstel datum mag niet in het verleden liggen.',
            'distribution_date.date' => 'Voer een geldige distributiedatum in.',
            'distribution_date.after_or_equal' => 'De distributiedatum mag niet vóór de samenstel datum liggen.',
        ];
    }

}

