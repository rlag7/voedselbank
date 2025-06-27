@extends('dashboard')

@section('dashboard-content')
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="mx-auto w-full md:w-1/2">
        <h1 class="text-2xl font-semibold mb-4">Leverancier Bewerken</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Bedrijfsgegevens -->
            <div>
                <label class="block mb-1">Bedrijfsnaam *</label>
                <input type="text" name="company_name" value="{{ old('company_name', $supplier->company_name) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Adres -->
            @php
                $addressParts = explode(',', $supplier->address);
                $streetHouse = isset($addressParts[0]) ? explode(' ', trim($addressParts[0])) : [];
                $postalCity = isset($addressParts[1]) ? explode(' ', trim($addressParts[1])) : [];
                $street = implode(' ', array_slice($streetHouse, 0, -1));
                $house = end($streetHouse);
                $postal = $postalCity[0] ?? '';
                $city = implode(' ', array_slice($postalCity, 1));
            @endphp

            <div>
                <label class="block mb-1">Straat *</label>
                <input type="text" name="street" value="{{ old('street', $street ?? '') }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">Huisnummer *</label>
                <input type="number" name="house_number" value="{{ old('house_number', $house ?? '') }}"
                       class="w-full border rounded px-3 py-2" required min="1">
            </div>

            <div>
                <label class="block mb-1">Postcode *</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $postal ?? '') }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">Stad *</label>
                <input type="text" name="city" value="{{ old('city', $city ?? '') }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- Contactgegevens -->
            <div>
                <label class="block mb-1">Contactpersoon *</label>
                <input type="text" name="contact_name" value="{{ old('contact_name', $supplier->contact_name) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">E-mailadres *</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $supplier->contact_email) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">Telefoon *</label>
                <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">Type leverancier *</label>
                <select name="supplier_type" class="w-full border rounded px-3 py-2" required>
                    @foreach(['supermarkt', 'groothandel', 'boer', 'instelling', 'overheid', 'particulier'] as $type)
                        <option value="{{ $type }}" @selected($supplier->supplier_type === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" class="mr-2" value="1" {{ $supplier->is_active ? 'checked' : '' }}>
                    Actief
                </label>
            </div>

            <!-- Producten -->
            <hr class="my-4">
            <h2 class="text-xl font-semibold mb-2">Producten koppelen</h2>

            <div id="product-container" class="space-y-4">
                @foreach($supplier->products as $product)
                    <div class="flex items-center space-x-2 product-select-group">
                        <select name="products_and_quantities[]" class="product-select border rounded px-3 py-2 w-full">
                            @foreach($productCategories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($category->products as $p)
                                        <option value="{{ $p->id }}:{{ $product->pivot->stock_quantity }}"
                                            @selected($p->id == $product->id)>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <input type="number" min="1" max="1000" value="{{ $product->pivot->stock_quantity }}"
                               class="border rounded px-2 py-1 w-24 stock-input" oninput="syncStock(this)">
                        <button type="button" onclick="removeProduct(this)" class="text-red-600 font-bold text-xl">&times;</button>
                    </div>
                @endforeach
            </div>

            <button type="button" onclick="addProduct()" class="mt-2 text-sm text-blue-600 hover:underline">+ Voeg product toe</button>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mt-6 w-full">
                Opslaan
            </button>
        </form>
    </div>

    <script>
        const categories = @json($productCategories);
        const usedProducts = new Set(
            Array.from(document.querySelectorAll('.product-select option:checked')).map(o => o.value.split(':')[0])
        );

        function addProduct() {
            const container = document.getElementById('product-container');

            let select = document.createElement('select');
            select.name = 'products_and_quantities[]';
            select.className = 'product-select border rounded px-3 py-2 w-full';
            let hasOption = false;

            categories.forEach(category => {
                let group = document.createElement('optgroup');
                group.label = category.name;

                category.products.forEach(product => {
                    if (!usedProducts.has(String(product.id))) {
                        let option = document.createElement('option');
                        option.value = `${product.id}:1`;
                        option.textContent = product.name;
                        group.appendChild(option);
                        hasOption = true;
                    }
                });

                if (group.children.length > 0) select.appendChild(group);
            });

            if (!hasOption) return alert('Alle producten zijn al gekoppeld.');

            let quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.min = '1';
            quantityInput.max = '1000';
            quantityInput.value = '1';
            quantityInput.className = 'border rounded px-2 py-1 w-24 stock-input';
            quantityInput.oninput = function () {
                syncStock(this);
            };

            let removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '&times;';
            removeBtn.className = 'text-red-600 font-bold text-xl';
            removeBtn.onclick = function () {
                const pid = select.value.split(':')[0];
                usedProducts.delete(pid);
                this.parentNode.remove();
            };

            select.onchange = function () {
                const pid = this.value.split(':')[0];
                if (usedProducts.has(pid)) {
                    alert('Dit product is al gekozen.');
                    this.parentNode.remove();
                    return;
                }
                usedProducts.add(pid);
            };

            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-center space-x-2 product-select-group';
            wrapper.appendChild(select);
            wrapper.appendChild(quantityInput);
            wrapper.appendChild(removeBtn);

            container.appendChild(wrapper);
        }

        function syncStock(input) {
            const wrapper = input.closest('.product-select-group');
            const select = wrapper.querySelector('select');
            const selected = select.value.split(':')[0];
            const quantity = Math.min(1000, Math.max(1, parseInt(input.value) || 1));
            select.value = `${selected}:${quantity}`;
        }

        function removeProduct(btn) {
            const wrapper = btn.closest('.product-select-group');
            const select = wrapper.querySelector('select');
            const pid = select.value.split(':')[0];
            usedProducts.delete(pid);
            wrapper.remove();
        }
    </script>
@endsection
