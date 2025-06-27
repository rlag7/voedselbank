<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('products')->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $productCategories = \App\Models\ProductCategory::with('products')->get();
        return view('admin.suppliers.create', compact('productCategories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:100', Rule::unique('suppliers')],
            'address' => ['nullable', 'string', 'max:200'],
            'contact_name' => ['required', 'string', 'max:100'],
            'contact_email' => ['required', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'supplier_type' => ['required', Rule::in([
                'supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'
            ])],
            'supplier_number' => ['required', 'string', 'max:20', Rule::unique('suppliers')],
            'products_and_quantities' => ['nullable', 'array'],
            'products_and_quantities.*' => ['string', 'regex:/^\d+:\d+$/'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'company_name.required' => 'De bedrijfsnaam is verplicht.',
            'company_name.unique' => 'Deze bedrijfsnaam bestaat al.',
            'contact_name.required' => 'De naam van de contactpersoon is verplicht.',
            'contact_email.required' => 'Het e-mailadres is verplicht.',
            'contact_email.email' => 'Voer een geldig e-mailadres in.',
            'supplier_type.required' => 'Kies een type leverancier.',
            'supplier_number.required' => 'Het leveranciersnummer is verplicht.',
            'supplier_number.unique' => 'Dit leveranciersnummer bestaat al.',
            'products_and_quantities.*.regex' => 'Producten en aantallen moeten geldig zijn.',
        ]);

        $supplier = Supplier::create([
            'company_name' => $validated['company_name'],
            'address' => $validated['address'] ?? null,
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'phone' => $validated['phone'] ?? null,
            'supplier_type' => $validated['supplier_type'],
            'supplier_number' => $validated['supplier_number'],
            'is_active' => $request->has('is_active'),
        ]);

        if (!empty($validated['products_and_quantities'])) {
            foreach ($validated['products_and_quantities'] as $entry) {
                [$productId, $quantity] = explode(':', $entry);
                $productId = (int) $productId;
                $quantity = max((int) $quantity, 0); // voorkom negatieve aantallen

                $supplier->products()->attach($productId, [
                    'stock_quantity' => $quantity,
                    'last_delivery_date' => now(),
                ]);
            }
        }

        return redirect()->route('admin.suppliers.index')->with('success', 'Leverancier succesvol toegevoegd.');
    }


    public function show(Supplier $supplier)
    {
        $supplier->load('products');
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        $productCategories = \App\Models\ProductCategory::with('products')->get();

        return view('admin.suppliers.edit', [
            'supplier' => $supplier,
            'productCategories' => $productCategories,
        ]);
    }


    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:100', Rule::unique('suppliers')->ignore($supplier->id)],
            'address' => ['nullable', 'string', 'max:200'],
            'contact_name' => ['required', 'string', 'max:100'],
            'contact_email' => ['required', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'supplier_type' => ['required', Rule::in([
                'supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'
            ])],
            'supplier_number' => ['required', 'string', 'max:20', Rule::unique('suppliers')->ignore($supplier->id)],
            'products_and_quantities' => ['nullable', 'array'],
            'products_and_quantities.*' => ['string', 'regex:/^\d+:\d+$/'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'company_name.required' => 'De bedrijfsnaam is verplicht.',
            'company_name.unique' => 'Deze bedrijfsnaam is al in gebruik.',
            'contact_name.required' => 'De naam van de contactpersoon is verplicht.',
            'contact_email.required' => 'Het e-mailadres is verplicht.',
            'contact_email.email' => 'Voer een geldig e-mailadres in.',
            'supplier_number.required' => 'Het leveranciersnummer is verplicht.',
            'supplier_number.unique' => 'Dit leveranciersnummer is al in gebruik.',
            'supplier_type.required' => 'Selecteer een type leverancier.',
            'products_and_quantities.*.regex' => 'Productinformatie is ongeldig.',
        ]);

        $supplier->update([
            'company_name' => $validated['company_name'],
            'address' => $validated['address'] ?? null,
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'phone' => $validated['phone'] ?? null,
            'supplier_type' => $validated['supplier_type'],
            'supplier_number' => $validated['supplier_number'],
            'is_active' => $request->has('is_active'),
        ]);

        // Producten synchroniseren
        $productData = [];
        if (!empty($validated['products_and_quantities'])) {
            foreach ($validated['products_and_quantities'] as $entry) {
                [$productId, $stock] = explode(':', $entry);
                $productId = (int) $productId;
                $stock = max(0, (int) $stock); // garandeer >= 0
                $productData[$productId] = [
                    'stock_quantity' => $stock,
                    'last_delivery_date' => now(),
                ];
            }
        }

        $supplier->products()->sync($productData);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Leverancier succesvol bijgewerkt.');
    }


    public function destroy(Supplier $supplier)
    {
        if ($supplier->is_active) {
            return redirect()->route('admin.suppliers.index')
                ->with('error', 'Deze leverancier is actief en kan niet worden verwijderd.');
        }

        $supplier->products()->detach();
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Leverancier verwijderd.');
    }
}
