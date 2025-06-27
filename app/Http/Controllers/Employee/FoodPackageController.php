<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\FoodPackage;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FoodPackageController extends Controller
{
    public function index()
    {
        // Toon voedselpakketten met klantinformatie, met pagination
        $packages = FoodPackage::with('customer')->paginate(5);
        return view('employee.food_packages.index', compact('packages'));
    }

    public function create()
    {
        // Haal alle klanten op voor de dropdown
        $customers = Customer::all();
        return view('employee.food_packages.create', compact('customers'));
    }

    public function store(Request $request)
    {
        try {
            // Valideer invoer
            $validated = $request->validate($this->rules(), $this->messages());

            // Controleer of klant recent al een pakket heeft gekregen
            $compositionDate = Carbon::parse($validated['composition_date']);
            $sevenDaysAgo = $compositionDate->copy()->subDays(7);

            $hasRecentPackage = FoodPackage::where('customer_id', $validated['customer_id'])
                ->where('composition_date', '>=', $sevenDaysAgo)
                ->exists();

            if ($hasRecentPackage) {
                return redirect()->back()
                    ->withErrors(['customer_id' => 'Deze klant heeft in de afgelopen 7 dagen al een voedselpakket ontvangen.'])
                    ->withInput();
            }

            // Zet actief op basis van checkbox
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;

            FoodPackage::create($validated);

            return redirect()->route('employee.food_packages.index')
                ->with('success', 'Voedselpakket succesvol aangemaakt.');
        } catch (\Exception $e) {
            // Foutafhandeling
            return redirect()->back()
                ->withErrors(['general' => 'Er is iets misgegaan bij het opslaan. Probeer het opnieuw.'])
                ->withInput();
        }
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
        // Zelfde validatie en check als in store()
        $validated = $request->validate($this->rules(), $this->messages());

        $compositionDate = Carbon::parse($validated['composition_date']);
        $sevenDaysAgo = $compositionDate->copy()->subDays(7);

        $hasRecentPackage = FoodPackage::where('customer_id', $validated['customer_id'])
            ->where('id', '!=', $foodPackage->id)
            ->where('composition_date', '>=', $sevenDaysAgo)
            ->exists();

        if ($hasRecentPackage) {
            return redirect()->back()
                ->withErrors(['customer_id' => 'Deze klant heeft in de afgelopen 7 dagen al een voedselpakket ontvangen.'])
                ->withInput();
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $foodPackage->update($validated);

        return redirect()->route('employee.food_packages.index')
            ->with('success', 'Voedselpakket succesvol bijgewerkt.');
    }

    public function destroy(FoodPackage $foodPackage)
    {
        // Verwijder het pakket
        $foodPackage->delete();
        return redirect()->route('employee.food_packages.index')->with('success', 'Package deleted.');
    }

    // Validatieregels
    private function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'composition_date' => ['required', 'date', 'after_or_equal:today'],
            'distribution_date' => ['nullable', 'date', 'after_or_equal:composition_date'],
        ];
    }

    // Aangepaste foutmeldingen in het Nederlands
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
