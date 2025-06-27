@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Leverancier Wijzigen</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.suppliers.update', $supplier->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Leverancier gegevens -->
        <div>
            <label class="block mb-1">Bedrijfsnaam *</label>
            <input type="text" name="company_name" value="{{ old('company_name', $supplier->company_name) }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Adres</label>
            <input type="text" name="address" value="{{ old('address', $supplier->address) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block mb-1">Naam Contactpersoon *</label>
            <input type="text" name="contact_name" value="{{ old('contact_name', $supplier->contact_name) }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">E-mailadres *</label>
            <input type="email" name="contact_email" value="{{ old('contact_email', $supplier->contact_email) }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Telefoon</label>
            <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block mb-1">Type Leverancier *</label>
            <select name="supplier_type" class="w-full border rounded px-3 py-2" required>
                @foreach(['supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'] as $type)
                    <option value="{{ $type }}" {{ old('supplier_type', $supplier->supplier_type) === $type ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1">Leveranciernummer *</label>
            <input type="text" name="supplier_number" value="{{ old('supplier_number', $supplier->supplier_number) }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <hr>
        <h2 class="text-xl font-semibold mb-2">Producten koppelen</h2>
        <div id="product-select-container">
            @foreach ($products as $product)
                <div class="mb-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="products[]" value="{{ $product->id }}"
                            {{ in_array($product->id, old('products', $selectedProducts)) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $product->name }}</span>
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Opslaan
        </button>
    </form>
@endsection
