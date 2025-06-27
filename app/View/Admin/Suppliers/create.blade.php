@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Nieuwe Leverancier Toevoegen</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.suppliers.store') }}" class="space-y-4" id="supplier-form">
        @csrf

        <div>
            <label class="block mb-1">Bedrijfsnaam *</label>
            <input type="text" name="company_name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Adres</label>
            <input type="text" name="address" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block mb-1">Naam Contactpersoon *</label>
            <input type="text" name="contact_name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">E-mailadres *</label>
            <input type="email" name="contact_email" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Telefoon</label>
            <input type="text" name="phone" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block mb-1">Type Leverancier *</label>
            <select name="supplier_type" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Kies --</option>
                <option value="supermarkt">Supermarkt</option>
                <option value="groothandel">Groothandel</option>
                <option value="boer">Boer</option>
                <option value="instelling">Instelling</option>
                <option value="overheid">Overheid</option>
                <option value="particulier">Particulier</option>
            </select>
        </div>

        <div>
            <label class="block mb-1">Leveranciernummer *</label>
            <input type="text" name="supplier_number" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" class="mr-2" value="1">
                Actief
            </label>
        </div>

        <hr>
        <h2 class="text-xl font-semibold mb-2">Producten koppelen</h2>
        <div id="product-select-container">
            <div class="flex items-center space-x-2 mb-2 product-select-wrapper">
                <select name="products[]" class="product-select border rounded px-2 py-1 w-full">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <button type="button" onclick="removeProduct(this)" class="text-red-600 font-bold">&times;</button>
            </div>
        </div>
        <button type="button" onclick="addProduct()" class="bg-gray-200 px-2 py-1 rounded text-sm">+ Voeg product toe</button>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mt-4">Opslaan</button>
    </form>

    <script>
        function addProduct() {
            const container = document.getElementById('product-select-container');
            const products = @json($products);
            let options = '';
            for (let product of products) {
                options += `<option value="${product.id}">${product.name}</option>`;
            }

            const html = `
                <div class="flex items-center space-x-2 mb-2 product-select-wrapper">
                    <select name="products[]" class="product-select border rounded px-2 py-1 w-full">
                        ${options}
                    </select>
                    <button type="button" onclick="removeProduct(this)" class="text-red-600 font-bold">&times;</button>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);

            updateSelectOptions();
        }

        function removeProduct(button) {
            button.closest('.product-select-wrapper').remove();
            updateSelectOptions();
        }

        function updateSelectOptions() {
            const selectedValues = Array.from(document.querySelectorAll('.product-select')).map(sel => sel.value);
            document.querySelectorAll('.product-select').forEach(select => {
                const currentValue = select.value;
                Array.from(select.options).forEach(option => {
                    option.disabled = selectedValues.includes(option.value) && option.value !== currentValue;
                });
            });
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select')) {
                updateSelectOptions();
            }
        });

        document.addEventListener('DOMContentLoaded', updateSelectOptions);
    </script>
@endsection
