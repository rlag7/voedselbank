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

    <form method="POST" action="{{ route('admin.suppliers.store') }}" class="space-y-4">
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
            <label class="block mb-1">Contactpersoon *</label>
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
            <label class="block mb-1">Leveranciersnummer *</label>
            <input type="text" name="supplier_number" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" class="mr-2" value="1">
                Actief
            </label>
        </div>

        <hr class="my-4">

        <h2 class="text-xl font-semibold mb-2">Producten koppelen</h2>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-1">Categorie *</label>
                <select id="category-select" class="w-full border rounded px-3 py-2">
                    <option value="">-- Kies categorie --</option>
                    @foreach($productCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Product *</label>
                <select name="products[]" id="product-select" class="w-full border rounded px-3 py-2" disabled>
                    <option value="">-- Kies eerst een categorie --</option>
                </select>
            </div>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mt-4">Opslaan</button>
    </form>

    <script>
        const allCategories = @json($productCategories);
        const categorySelect = document.getElementById('category-select');
        const productSelect = document.getElementById('product-select');

        categorySelect.addEventListener('change', function () {
            const categoryId = parseInt(this.value);
            productSelect.innerHTML = '';

            if (!categoryId) {
                productSelect.disabled = true;
                productSelect.innerHTML = '<option>-- Kies eerst een categorie --</option>';
                return;
            }

            const selectedCategory = allCategories.find(cat => cat.id === categoryId);

            if (selectedCategory && selectedCategory.products.length > 0) {
                selectedCategory.products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    productSelect.appendChild(option);
                });
                productSelect.disabled = false;
            } else {
                productSelect.innerHTML = '<option>Geen producten gevonden</option>';
                productSelect.disabled = true;
            }
        });
    </script>
@endsection
