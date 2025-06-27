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
            'supplier_number' => ['nullable', 'string', 'max:20', Rule::unique('suppliers')],
            'products' => ['nullable', 'array'],
            'products.*' => ['integer', 'exists:products,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $supplier = Supplier::create([
            'company_name' => $validated['company_name'],
            'address' => $validated['address'] ?? null,
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'phone' => $validated['phone'] ?? null,
            'supplier_type' => $validated['supplier_type'],
            'supplier_number' => $validated['supplier_number'] ?? 'SUPPLY' . rand(1000, 9999),
            'is_active' => $request->has('is_active'),
        ]);

        if (!empty($validated['products'])) {
            foreach ($validated['products'] as $productId) {
                $supplier->products()->attach($productId, [
                    'stock_quantity' => 0,
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
        $products = Product::all();
        $selectedProducts = $supplier->products->pluck('id')->toArray();
        return view('admin.suppliers.edit', compact('supplier', 'products', 'selectedProducts'));
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
            'products' => ['nullable', 'array'],
            'products.*' => ['integer', 'exists:products,id'],
            'is_active' => ['nullable', 'boolean'],
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

        $supplier->products()->sync([]);
        if (!empty($validated['products'])) {
            foreach ($validated['products'] as $productId) {
                $supplier->products()->attach($productId, [
                    'stock_quantity' => 0,
                    'last_delivery_date' => now(),
                ]);
            }
        }

        return redirect()->route('admin.suppliers.index')->with('success', 'Leverancier bijgewerkt.');
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
