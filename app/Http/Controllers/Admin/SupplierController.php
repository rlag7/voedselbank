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
        $productCategories = ProductCategory::with('products')->get();
        return view('admin.suppliers.create', compact('productCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:100', 'regex:/^(?!\d+$).*/', Rule::unique('suppliers')],
            'address' => ['required', 'string', 'max:200', 'regex:/[a-zA-Z]/'],
            'contact_name' => ['required', 'string', 'max:100'],
            'contact_email' => ['required', 'email:dns', 'max:100'],
            'phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
            'supplier_type' => ['required', Rule::in(['supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'])],
            'products_and_quantities' => ['nullable', 'array'],
            'products_and_quantities.*' => ['regex:/^\d+:(?:[1-9][0-9]{0,2}|1000)$/'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'company_name.required' => 'De bedrijfsnaam is verplicht.',
            'company_name.regex' => 'De bedrijfsnaam mag niet alleen uit cijfers bestaan.',
            'company_name.unique' => 'Deze bedrijfsnaam bestaat al.',
            'address.required' => 'Het adres is verplicht.',
            'address.regex' => 'Voer een geldig adres in met letters.',
            'contact_name.required' => 'De naam van de contactpersoon is verplicht.',
            'contact_email.required' => 'Het e-mailadres is verplicht.',
            'contact_email.email' => 'Voer een geldig e-mailadres in.',
            'phone.required' => 'Het telefoonnummer is verplicht.',
            'phone.regex' => 'Voer een geldig telefoonnummer in.',
            'supplier_type.required' => 'Kies een type leverancier.',
            'products_and_quantities.*.regex' => 'Productaantal moet tussen 1 en 1000 liggen.',
        ]);

        $supplier = Supplier::create([
            'company_name' => $validated['company_name'],
            'address' => $validated['address'],
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'phone' => $validated['phone'],
            'supplier_type' => $validated['supplier_type'],
            'supplier_number' => strtoupper('SUP-' . rand(1000, 9999)),
            'is_active' => $request->has('is_active'),
        ]);

        if (!empty($validated['products_and_quantities'])) {
            foreach ($validated['products_and_quantities'] as $entry) {
                [$productId, $quantity] = explode(':', $entry);
                $supplier->products()->attach((int)$productId, [
                    'stock_quantity' => (int)$quantity,
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
        $productCategories = ProductCategory::with('products')->get();

        return view('admin.suppliers.edit', [
            'supplier' => $supplier,
            'productCategories' => $productCategories,
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:100', 'regex:/^(?!\d+$).*/', Rule::unique('suppliers')->ignore($supplier->id)],
            'address' => ['required', 'string', 'max:200', 'regex:/[a-zA-Z]/'],
            'contact_name' => ['required', 'string', 'max:100'],
            'contact_email' => ['required', 'email:dns', 'max:100'],
            'phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
            'supplier_type' => ['required', Rule::in(['supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'])],
            'products_and_quantities' => ['nullable', 'array'],
            'products_and_quantities.*' => ['regex:/^\d+:(?:[1-9][0-9]{0,2}|1000)$/'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'company_name.required' => 'De bedrijfsnaam is verplicht.',
            'company_name.unique' => 'Deze bedrijfsnaam is al in gebruik.',
            'company_name.regex' => 'De bedrijfsnaam mag niet alleen uit cijfers bestaan.',
            'address.required' => 'Het adres is verplicht.',
            'address.regex' => 'Voer een geldig adres in met letters.',
            'contact_name.required' => 'De naam van de contactpersoon is verplicht.',
            'contact_email.required' => 'Het e-mailadres is verplicht.',
            'contact_email.email' => 'Voer een geldig e-mailadres in.',
            'phone.required' => 'Het telefoonnummer is verplicht.',
            'phone.regex' => 'Voer een geldig telefoonnummer in.',
            'supplier_type.required' => 'Selecteer een type leverancier.',
            'products_and_quantities.*.regex' => 'Productaantal moet tussen 1 en 1000 liggen.',
        ]);

        $supplier->update([
            'company_name' => $validated['company_name'],
            'address' => $validated['address'],
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'phone' => $validated['phone'],
            'supplier_type' => $validated['supplier_type'],
            'is_active' => $request->has('is_active'),
        ]);

        // Sync producten
        $productData = [];
        if (!empty($validated['products_and_quantities'])) {
            foreach ($validated['products_and_quantities'] as $entry) {
                [$productId, $stock] = explode(':', $entry);
                $productData[(int)$productId] = [
                    'stock_quantity' => (int)$stock,
                    'last_delivery_date' => now(),
                ];
            }
        }

        $supplier->products()->sync($productData);

        return redirect()->route('admin.suppliers.index')->with('success', 'Leverancier succesvol bijgewerkt.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->is_active) {
            return redirect()->route('admin.suppliers.index')->with('error', 'Deze leverancier is actief en kan niet worden verwijderd.');
        }

        $supplier->products()->detach();
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Leverancier verwijderd.');
    }
}
