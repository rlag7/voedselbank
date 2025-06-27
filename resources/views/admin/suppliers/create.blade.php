@extends('dashboard')

@section('dashboard-content')
    <div class="mx-auto w-full md:w-1/2">
        <script src="https://cdn.tailwindcss.com"></script>
        <h1 class="text-2xl font-semibold mb-4">Nieuwe Leverancier Toevoegen</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.suppliers.store') }}" class="space-y-4">
            @csrf

            <!-- standaard velden -->
            <div>
                <label class="block mb-1">Bedrijfsnaam *</label>
                <input type="text" name="company_name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Straat *</label>
                    <input type="text" name="street" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-1">Huisnummer *</label>
                    <input type="number" name="house_number" class="w-full border rounded px-3 py-2" required min="1">
                </div>
                <div>
                    <label class="block mb-1">Postcode *</label>
                    <input type="text" name="postal_code" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-1">Stad *</label>
                    <input type="text" name="city" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div>
                <label class="block mb-1">Contactpersoon *</label>
                <input type="text" name="contact_name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">E-mailadres *</label>
                <input type="email" name="contact_email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Telefoon *</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Type leverancier *</label>
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
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" class="mr-2" value="1">
                    Actief
                </label>
            </div>

            <!-- Product selectie -->
            <hr class="my-4">
            <h2 class="text-xl font-semibold mb-2">Producten koppelen</h2>

            <div>
                <label class="block mb-1 font-semibold">Categorie *</label>
                <select id="category-select" class="w-full border rounded px-3 py-2">
                    <option value="">-- Kies categorie --</option>
                    @foreach($productCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="selected-products-area" class="space-y-4 mt-4"></div>

            <button type="button" id="add-category-btn" class="text-blue-600 hover:underline text-sm mt-2">+ Voeg extra categorie toe</button>

            <button type="submit" class="block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mt-6 w-full">
                Opslaan
            </button>
        </form>
    </div>

    <script>
        const allCategories = @json($productCategories);
        const categorySelect = document.getElementById('category-select');
        const selectedProductsArea = document.getElementById('selected-products-area');
        const addCategoryBtn = document.getElementById('add-category-btn');
        let selectedProductIds = new Set();

        function createProductSelect(category) {
            const container = document.createElement('div');
            container.classList.add('border', 'rounded', 'p-3', 'bg-gray-50');

            const title = document.createElement('h3');
            title.classList.add('font-semibold', 'mb-2');
            title.textContent = category.name;
            container.appendChild(title);

            const wrapper = document.createElement('div');
            wrapper.classList.add('flex', 'items-center', 'space-x-2', 'mb-2');

            const select = document.createElement('select');
            select.className = 'w-full border rounded px-3 py-2';

            category.products.forEach(product => {
                if (!selectedProductIds.has(product.id)) {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    select.appendChild(option);
                }
            });

            if (select.options.length === 0) {
                const opt = document.createElement('option');
                opt.textContent = 'Geen producten beschikbaar';
                select.appendChild(opt);
                select.disabled = true;
            }

            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.min = '1';
            quantityInput.max = '1000';
            quantityInput.value = '1';
            quantityInput.required = true;
            quantityInput.className = 'w-24 border rounded px-2 py-1';
            quantityInput.placeholder = 'Aantal';

            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'products_and_quantities[]';

            const updateHiddenInput = () => {
                productInput.value = select.value + ':' + quantityInput.value;
            };

            select.addEventListener('change', () => {
                selectedProductIds.add(parseInt(select.value));
                updateHiddenInput();
            });

            quantityInput.addEventListener('input', updateHiddenInput);

            updateHiddenInput();

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '&times;';
            removeBtn.className = 'text-red-500 font-bold text-xl';
            removeBtn.onclick = () => {
                const removedId = parseInt(select.value);
                selectedProductIds.delete(removedId);
                container.remove();
            };

            wrapper.appendChild(select);
            wrapper.appendChild(quantityInput);
            wrapper.appendChild(removeBtn);

            container.appendChild(wrapper);
            container.appendChild(productInput);

            selectedProductsArea.appendChild(container);

            // trigger immediately
            if (select.value) selectedProductIds.add(parseInt(select.value));
        }

        categorySelect.addEventListener('change', () => {
            const catId = parseInt(categorySelect.value);
            const category = allCategories.find(c => c.id === catId);
            if (category) {
                createProductSelect(category);
                categorySelect.value = "";
            }
        });

        addCategoryBtn.addEventListener('click', () => {
            categorySelect.focus();
        });
    </script>
@endsection
