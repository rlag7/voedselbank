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
// Validate input including company name, contact info, address, and linked products
$validated = $request->validate([
'company_name' => ['required', 'string', 'max:100', 'regex:/^(?!\d+$).*/', Rule::unique('suppliers')],
'street' => ['required', 'string', 'max:100'],
'house_number' => ['required', 'integer', 'min:1'],
'postal_code' => ['required', 'string', 'max:10'],
'city' => ['required', 'string', 'max:50'],
'contact_name' => ['required', 'string', 'max:100'],
'contact_email' => ['required', 'email:dns', 'max:100'],
'phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
'supplier_type' => ['required', Rule::in(['supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'])],
'products_and_quantities' => ['nullable', 'array'],
'products_and_quantities.*' => ['regex:/^\d+:(?:[1-9][0-9]{0,2}|1000)$/'],
'is_active' => ['nullable', 'boolean'],
], [
'house_number.min' => 'Huisnummer moet minimaal 1 zijn.',
'products_and_quantities.*.regex' => 'Productaantallen moeten tussen 1 en 1000 liggen.',
]);

// Concatenate full address from multiple fields
$address = "{$validated['street']} {$validated['house_number']}, {$validated['postal_code']} {$validated['city']}";

// Create the supplier with generated supplier number
$supplier = Supplier::create([
'company_name' => $validated['company_name'],
'address' => $address,
'contact_name' => $validated['contact_name'],
'contact_email' => $validated['contact_email'],
'phone' => $validated['phone'],
'supplier_type' => $validated['supplier_type'],
'supplier_number' => strtoupper('SUP-' . rand(1000, 9999)),
'is_active' => $request->has('is_active'),
]);

// Attach selected products with stock quantities
if (!empty($validated['products_and_quantities'])) {
foreach ($validated['products_and_quantities'] as $entry) {
[$productId, $quantity] = explode(':', $entry);
$supplier->products()->attach((int)$productId, [
'stock_quantity' => max(1, min((int)$quantity, 1000)),
'last_delivery_date' => now(),
]);
}
}

return redirect()->route('admin.suppliers.index')->with('success', 'Leverancier succesvol toegevoegd.');
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

return view('admin.suppliers.edit', [
'supplier' => $supplier,
'productCategories' => $productCategories,
]);
}

// Handle the update of a supplier
public function update(Request $request, Supplier $supplier)
{
// Validate input, including unique company name except for current supplier
$validated = $request->validate([
'company_name' => ['required', 'string', 'max:100', Rule::unique('suppliers')->ignore($supplier->id)],
'street' => ['required', 'string', 'max:100'],
'house_number' => ['required', 'integer', 'min:1'],
'postal_code' => ['required', 'string', 'max:10'],
'city' => ['required', 'string', 'max:50'],
'contact_name' => ['required', 'string', 'max:100'],
'contact_email' => ['required', 'email', 'max:100'],
'phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
'supplier_type' => ['required', Rule::in(['supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'])],
'products_and_quantities' => ['nullable', 'array'],
'products_and_quantities.*' => ['regex:/^\d+:(?:[1-9][0-9]{0,2}|1000)$/'],
'is_active' => ['nullable', 'boolean'],
], [
'house_number.min' => 'Huisnummer moet minimaal 1 zijn.',
'products_and_quantities.*.regex' => 'Productaantallen moeten tussen 1 en 1000 liggen.',
]);

// Update the address field
$address = "{$validated['street']} {$validated['house_number']}, {$validated['postal_code']} {$validated['city']}";

// Update supplier data
$supplier->update([
'company_name' => $validated['company_name'],
'address' => $address,
'contact_name' => $validated['contact_name'],
'contact_email' => $validated['contact_email'],
'phone' => $validated['phone'],
'supplier_type' => $validated['supplier_type'],
'is_active' => $request->has('is_active'),
]);

// Sync the product quantities with pivot table
$productData = [];
if (!empty($validated['products_and_quantities'])) {
foreach ($validated['products_and_quantities'] as $entry) {
[$productId, $stock] = explode(':', $entry);
$productData[(int)$productId] = [
'stock_quantity' => max(1, min((int)$stock, 1000)),
'last_delivery_date' => now(),
];
}
}

$supplier->products()->sync($productData);

return redirect()->route('admin.suppliers.index')->with('success', 'Leverancier succesvol bijgewerkt.');
}

// Delete a supplier if not active
public function destroy(Supplier $supplier)
{
// Prevent deletion if supplier is marked active
if ($supplier->is_active) {
return redirect()->route('admin.suppliers.index')->with('error', 'Deze leverancier is actief en kan niet worden verwijderd.');
}

// Detach all related products before deletion
$supplier->products()->detach();
$supplier->delete();

return redirect()->route('admin.suppliers.index')->with('success', 'Leverancier verwijderd.');
}
}
