<script src="https://cdn.tailwindcss.com"></script>


@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-xl font-semibold text-center mb-6">Voedselpakket Bewerken</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 max-w-xl mx-auto text-sm shadow-sm">
            @foreach ($errors->all() as $error)
                <p class="mb-1">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="flex justify-center">
        <form method="POST" action="{{ route('employee.food_packages.update', $foodPackage) }}"
              class="bg-gray-100 p-6 rounded border border-gray-300 w-full max-w-xl space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm text-gray-700 mb-1">Selecteer Klant</label>
                <select name="customer_id" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $foodPackage->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->person->name }} (ID {{ $customer->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Samenstel Datum</label>
                <input type="date" name="composition_date"
                       value="{{ $foodPackage->composition_date }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
            </div>
            @error('composition_date')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

            <div>
                <label class="block text-sm text-gray-700 mb-1">Distributiedatum</label>
                <input type="date" name="distribution_date"
                       value="{{ $foodPackage->distribution_date }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
            </div>
            @error('distribution_date')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

            <div>
                <input type="hidden" name="is_active" value="0" />
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="form-checkbox"
                        {{ $foodPackage->is_active ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Actief</span>
                </label>
            </div>

            <div class="text-center">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition text-sm">
                    Bijwerken
                </button>
            </div>
        </form>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const composition = document.querySelector('[name="composition_date"]');
        const distribution = document.querySelector('[name="distribution_date"]');

        function showError(input, message) {
            let errorEl = input.parentElement.querySelector('.input-error');
            if (!errorEl) {
                errorEl = document.createElement('p');
                errorEl.className = 'input-error text-sm text-red-700 bg-red-100 border border-red-300 rounded px-3 py-2 mt-2';
                input.parentElement.appendChild(errorEl);
            }
            errorEl.innerText = message;
        }

        function clearError(input) {
            const errorEl = input.parentElement.querySelector('.input-error');
            if (errorEl) errorEl.remove();
        }

        form.addEventListener('submit', function (e) {
            let valid = true;
            clearError(composition);
            clearError(distribution);

            const today = new Date().toISOString().split('T')[0];
            const compDate = composition.value;
            const distDate = distribution.value;

            if (compDate && compDate < today) {
                showError(composition, 'De samenstel datum mag niet in het verleden liggen.');
                valid = false;
            }

            if (distDate && compDate && distDate < compDate) {
                showError(distribution, 'De distributiedatum mag niet eerder zijn dan de samenstel datum.');
                valid = false;
            }

            if (!valid) e.preventDefault();
        });
    });
</script>
