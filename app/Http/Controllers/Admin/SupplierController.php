<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    // Display a list of all suppliers with their related products


    public function index()
    {
        $suppliers = Supplier::with('products')->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    // Show the form to create a new supplier
    public function create()
    {
        $productCategories = ProductCategory::with('products')->get();
        return view('admin.suppliers.create', compact('productCategories'));
    }

    // Handle storing a new supplier into the database
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        // Concatenate full address
        $address = "{$validated['street']} {$validated['house_number']}, {$validated['postal_code']} {$validated['city']}";

        // Create the supplier
        $supplier = Supplier::create([
            'company_name'    => $validated['company_name'],
            'address'         => $address,
            'contact_name'    => $validated['contact_name'],
            'contact_email'   => $validated['contact_email'],
            'phone'           => $validated['phone'],
            'supplier_type'   => $validated['supplier_type'],
            'supplier_number' => strtoupper('SUP-' . rand(1000, 9999)),
            'is_active'       => $request->has('is_active'),
        ]);

        // Attach selected products with quantities
        if (!empty($validated['products_and_quantities'])) {
            foreach ($validated['products_and_quantities'] as $entry) {
                [$productId, $quantity] = explode(':', $entry);
                $supplier->products()->attach((int)$productId, [
                    'stock_quantity'     => max(1, min((int)$quantity, 1000)),
                    'last_delivery_date' => now(),
                ]);
            }
        }

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Leverancier succesvol toegevoegd.');
    }

    // Display a single supplier with product details
    public function show(Supplier $supplier)
    {
        $supplier->load('products');
        return view('admin.suppliers.show', compact('supplier'));
    }

    // Show the edit form for an existing supplier
    public function edit(Supplier $supplier)
    {
        $productCategories = ProductCategory::with('products')->get();
        return view('admin.suppliers.edit', compact('supplier', 'productCategories'));
    }

    // Handle the update of a supplier
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate(
            $this->validationRules($supplier->id),
            $this->validationMessages()
        );

        // Update full address
        $address = "{$validated['street']} {$validated['house_number']}, {$validated['postal_code']} {$validated['city']}";

        // Update supplier data
        $supplier->update([
            'company_name'  => $validated['company_name'],
            'address'       => $address,
            'contact_name'  => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'phone'         => $validated['phone'],
            'supplier_type' => $validated['supplier_type'],
            'is_active'     => $request->has('is_active'),
        ]);

        // Sync products and quantities
        $productData = [];
        if (!empty($validated['products_and_quantities'])) {
            foreach ($validated['products_and_quantities'] as $entry) {
                [$productId, $stock] = explode(':', $entry);
                $productData[(int)$productId] = [
                    'stock_quantity'     => max(1, min((int)$stock, 1000)),
                    'last_delivery_date' => now(),
                ];
            }
        }
        $supplier->products()->sync($productData);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Leverancier succesvol bijgewerkt.');
    }

    // Delete a supplier if not active
    public function destroy(Supplier $supplier)
    {
        if ($supplier->is_active) {
            return redirect()
                ->route('admin.suppliers.index')
                ->with('error', 'Deze leverancier is actief en kan niet worden verwijderd.');
        }

        $supplier->products()->detach();
        $supplier->delete();

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Leverancier verwijderd.');
    }

    /**
     * Gemeenschappelijke validatieregels voor store/update.
     *
     * @param int|null $ignoreId
     * @return array
     */
    protected function validationRules($ignoreId = null): array
    {
        $uniqueCompany = Rule::unique('suppliers', 'company_name');
        if ($ignoreId) {
            $uniqueCompany->ignore($ignoreId);
        }

        return [
            'company_name'              => [
                'required', 'string', 'max:100',
                'regex:/^(?=.*[A-Za-z]).+$/',   // Minimaal één letter
                $uniqueCompany,
            ],
            'street'                    => [
                'required', 'string', 'max:100',
                'regex:/^[\p{L}\s\-]+$/u',      // Alleen letters, spaties, koppeltekens
            ],
            'house_number'              => ['required', 'integer', 'min:1'],
            'postal_code'               => [
                'required', 'string',
                'regex:/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/', // NL-formaat 1234 AB
            ],
            'city'                      => [
                'required', 'string', 'max:50',
                'regex:/^[\p{L}\s\-]+$/u',
            ],
            'contact_name'              => [
                'required', 'string', 'max:100',
                'regex:/^[\p{L}\s]+$/u',         // Alleen letters en spaties
            ],
            'contact_email'             => ['required', 'email:dns', 'max:100'],
            'phone'                     => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
            'supplier_type'             => [
                'required',
                Rule::in(['supermarkt','groothandel','boer','instelling','overheid','particulier']),
            ],
            'products_and_quantities'   => ['nullable', 'array'],
            'products_and_quantities.*' => ['regex:/^\d+:(?:[1-9][0-9]{0,2}|1000)$/'],
            'is_active'                 => ['nullable', 'boolean'],
        ];
    }

    /**
     * Alle foutmeldingen in het Nederlands.
     *
     * @return array
     */
    protected function validationMessages(): array
    {
        return [
            'company_name.required'            => 'Bedrijfsnaam is verplicht.',
            'company_name.string'              => 'Bedrijfsnaam moet een tekst zijn.',
            'company_name.max'                 => 'Bedrijfsnaam mag maximaal 100 tekens bevatten.',
            'company_name.regex'               => 'Bedrijfsnaam moet minimaal één letter bevatten.',
            'company_name.unique'              => 'Deze bedrijfsnaam is al in gebruik.',

            'street.required'                  => 'Straatnaam is verplicht.',
            'street.string'                    => 'Straatnaam moet een tekst zijn.',
            'street.max'                       => 'Straatnaam mag maximaal 100 tekens bevatten.',
            'street.regex'                     => 'Straatnaam mag alleen letters, spaties en koppeltekens bevatten.',

            'house_number.required'            => 'Huisnummer is verplicht.',
            'house_number.integer'             => 'Huisnummer moet een geheel getal zijn.',
            'house_number.min'                 => 'Huisnummer moet minimaal 1 zijn.',

            'postal_code.required'             => 'Postcode is verplicht.',
            'postal_code.regex'                => 'Postcode moet in het formaat 1234 AB zijn.',

            'city.required'                    => 'Woonplaats is verplicht.',
            'city.string'                      => 'Woonplaats moet een tekst zijn.',
            'city.max'                         => 'Woonplaats mag maximaal 50 tekens bevatten.',
            'city.regex'                       => 'Woonplaats mag alleen letters, spaties en koppeltekens bevatten.',

            'contact_name.required'            => 'Contactpersoon is verplicht.',
            'contact_name.string'              => 'Contactpersoon moet een tekst zijn.',
            'contact_name.max'                 => 'Contactpersoon mag maximaal 100 tekens bevatten.',
            'contact_name.regex'               => 'Contactpersoon mag alleen letters en spaties bevatten.',

            'contact_email.required'           => 'E-mail is verplicht.',
            'contact_email.email'              => 'E-mail moet geldig zijn.',
            'contact_email.max'                => 'E-mail mag maximaal 100 tekens bevatten.',

            'phone.required'                   => 'Telefoonnummer is verplicht.',
            'phone.regex'                      => 'Telefoonnummer moet uit 7–15 cijfers bestaan, optioneel voorafgegaan door +.',

            'supplier_type.required'           => 'Leverancierstype is verplicht.',
            'supplier_type.in'                 => 'Ongeldig leverancierstype geselecteerd.',

            'products_and_quantities.array'    => 'Producten en aantallen moet een lijst zijn.',
            'products_and_quantities.*.regex'  => 'Productaantallen moeten in formaat ID:1–1000 zijn (bijv. 42:10).',

            'is_active.boolean'                => 'De “actief”-vlag moet waar of onwaar zijn.',
        ];
    }
}
