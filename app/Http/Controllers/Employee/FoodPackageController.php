<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\FoodPackage;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Product;

class FoodPackageController extends Controller
{
    public function index()
    {
        // Pak alle pakketten met klant, pagineren
        $packages = FoodPackage::with('customer')->paginate(5);
        return view('employee.food_packages.index', compact('packages'));
    }

    public function create()
    {
        // Klanten en producten ophalen voor form
        $customers = Customer::all();
        $products = Product::all();
        return view('employee.food_packages.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        try {
            // Valideer data
            $validated = $request->validate($this->rules(), $this->messages());

            // Check of klant recent pakket kreeg (7 dagen)
            $sevenDaysAgo = Carbon::parse($validated['composition_date'])->subDays(7);
            $exists = FoodPackage::where('customer_id', $validated['customer_id'])
                ->where('composition_date', '>=', $sevenDaysAgo)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withErrors(['customer_id' => 'Klant kreeg in 7 dagen al een pakket.'])
                    ->withInput();
            }

            // Checkbox actief: 1 of 0
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;

            // Maak pakket aan
            FoodPackage::create($validated);

            return redirect()->route('employee.food_packages.index')
                ->with('success', 'Pakket aangemaakt.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['general' => 'Fout bij opslaan, probeer opnieuw.'])
                ->withInput();
        }
    }

    public function show(FoodPackage $foodPackage)
    {
        // Toon details pakket
        return view('employee.food_packages.show', compact('foodPackage'));
    }

    public function edit(FoodPackage $foodPackage)
    {
        // Klanten en producten voor edit formulier
        $customers = Customer::all();
        $products = Product::all();
        return view('employee.food_packages.edit', compact('foodPackage', 'customers', 'products'));
    }

    public function update(Request $request, FoodPackage $foodPackage)
    {
        // Valideer data
        $validated = $request->validate($this->rules(), $this->messages());

        // Check dubbele pakketten (behalve huidige)
        $sevenDaysAgo = Carbon::parse($validated['composition_date'])->subDays(7);
        $exists = FoodPackage::where('customer_id', $validated['customer_id'])
            ->where('id', '!=', $foodPackage->id)
            ->where('composition_date', '>=', $sevenDaysAgo)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['customer_id' => 'Klant kreeg in 7 dagen al een pakket.'])
                ->withInput();
        }

        // Checkbox actief
        $validated['is_active'] = $request->input('is_active', 0);

        // Update pakket
        $foodPackage->update($validated);

        return redirect()->route('employee.food_packages.index')
            ->with('success', 'Pakket bijgewerkt.');
    }

    public function destroy(FoodPackage $foodPackage)
    {
        // Verwijder niet als actief
        if ($foodPackage->is_active) {
            return redirect()->route('employee.food_packages.index')
                ->withErrors(['delete' => 'Actieve pakketten kunnen niet verwijderd worden.']);
        }

        // Verwijder pakket
        $foodPackage->delete();

        return redirect()->route('employee.food_packages.index')
            ->with('success', 'Pakket verwijderd.');
    }

    private function rules(): array
    {
        // Validatie regels
        return [
            'customer_id' => 'required|exists:customers,id',
            'composition_date' => ['required', 'date', 'after_or_equal:today'],
            'distribution_date' => ['nullable', 'date', 'after_or_equal:composition_date'],
        ];
    }

    private function messages(): array
    {
        // Nederlandse foutmeldingen
        return [
            'customer_id.required' => 'Selecteer een klant.',
            'customer_id.exists' => 'Gekozen klant ongeldig.',
            'composition_date.required' => 'Samenstel datum verplicht.',
            'composition_date.date' => 'Voer geldige samenstel datum in.',
            'composition_date.after_or_equal' => 'Samenstel datum mag niet in verleden zijn.',
            'distribution_date.date' => 'Voer geldige distributiedatum in.',
            'distribution_date.after_or_equal' => 'Distributiedatum mag niet voor samenstel datum zijn.',
        ];
    }
}
